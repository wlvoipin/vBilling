#!/bin/bash
#
# FreeSWITCH Installation script for CentOS 5.x/6.x and Debian based distros 
# (Debian 6.x , Ubuntu 10.04 and above)
#
# This script gears toward configuration files which are going to be used with vBilling
#
# Copyright (c) 2011 Digital Linx. See LICENSE for details.

# Define some variables
#####################################################
FS_GIT_REPO=git://git.freeswitch.org/freeswitch.git
FS_CONF_PATH_FSXML=https://raw.github.com/digitallinx/vBilling/master/scripts/freeswitch/freeswitch.xml
FS_CONF_COMBINED=https://github.com/digitallinx/vBilling/raw/master/scripts/freeswitch/conf/freeswitch_combined_config.sh
FS_CONF=freeswitch_combined_config.sh
FS_CONF_PATH_MODULE=https://raw.github.com/digitallinx/vBilling/master/scripts/freeswitch/modules.conf
FS_INSTALLED_PATH=/usr/local/freeswitch
FS_BASE_PATH=/usr/src/
CURRENT_PATH=$PWD
VBILLING_GATEWAY_PORT=7665
#VBILLING_EMAIL="vbilling-robot@digitallinx.com"
#VBILLING_GATEWAY_HOST=vbilling-api.digitallinx.com
#####################################################

# Identify Linux Distribution
if [ -f /etc/debian_version ] ; then
    DIST="DEBIAN"
elif [ -f /etc/redhat-release ] ; then
    DIST="CENTOS"
else
    echo ""
    echo "This Installer should run on a CentOS or a Debian based system only!"
    echo ""
    exit 1
fi

clear
echo ""
echo "FreeSWITCH will be installed in '$FS_INSTALLED_PATH'"
echo "Press Enter to continue or CTRL-C to exit"
echo ""
read INPUT

echo "Setting up Prerequisites and Dependencies for FreeSWITCH"
case $DIST in
    'DEBIAN')
        apt-get -y update
        apt-get -y install autoconf automake autotools-dev binutils bison build-essential cpp curl flex g++ gcc git-core libaudiofile-dev libc6-dev libdb-dev libexpat1 libgdbm-dev libgnutls-dev libmcrypt-dev libncurses5-dev libnewt-dev libpcre3 libpopt-dev libsctp-dev libsqlite3-dev libtiff4 libtiff4-dev libtool libx11-dev libxml2 libxml2-dev lksctp-tools lynx m4 make mcrypt ncftp nmap openssl sox sqlite3 ssl-cert ssl-cert unixodbc-dev unzip zip zlib1g-dev zlib1g-dev
        ;;
    'CENTOS')
        yum -y update
        VERS=$(cat /etc/redhat-release |cut -d' ' -f4 |cut -d'.' -f1)

	if [ "$VERS" = "6" ]
	then
		yum -y install autoconf automake bzip2 cpio curl curl-devel curl-devel expat-devel fileutils gcc-c++ gettext-devel gnutls-devel libjpeg-devel libogg-devel libtiff-devel libtool libvorbis-devel make ncurses-devel nmap openssl openssl-devel openssl-devel perl patch unixODBC unixODBC-devel unzip wget zip zlib zlib-devel git
	else
		yum -y install autoconf automake bzip2 cpio curl curl-devel curl-devel expat-devel fileutils gcc-c++ gettext-devel gnutls-devel libjpeg-devel libogg-devel libtiff-devel libtool libvorbis-devel make ncurses-devel nmap openssl openssl-devel openssl-devel perl patch unixODBC unixODBC-devel unzip wget zip zlib zlib-devel

		#install the RPMFORGE Repository
		if [ ! -f /etc/yum.repos.d/rpmforge.repo ]
		then
			# Install RPMFORGE Repo
			rpm --import http://apt.sw.be/RPM-GPG-KEY.dag.txt
echo '
[rpmforge]
name = Red Hat Enterprise $releasever - RPMforge.net - dag
mirrorlist = http://apt.sw.be/redhat/el5/en/mirrors-rpmforge
enabled = 0
protect = 0
gpgkey = file:///etc/pki/rpm-gpg/RPM-GPG-KEY-rpmforge-dag
gpgcheck = 1
' > /etc/yum.repos.d/rpmforge.repo
		fi
	yum -y --enablerepo=rpmforge install git-core
	fi
    ;;
esac

# Install FreeSWITCH
cd $FS_BASE_PATH
git clone $FS_GIT_REPO
cd $FS_BASE_PATH/freeswitch
sh bootstrap.sh && ./configure
[ -f modules.conf ] && rm -rf modules.conf

# We will download modules.conf file customized for our API
wget --no-check-certificate $FS_CONF_PATH_MODULE

# Good to go, let's now compile and install FreeSWITCH
make && make install
cd $FS_INSTALLED_PATH/conf

# We do not want any of the configs. Let's make room for our own
rm -rf $FS_INSTALLED_PATH/conf/*
mkdir $FS_INSTALLED_PATH/conf/autoload_configs

# Instead download the files
wget --no-check-certificate $FS_CONF_PATH_FSXML

# We download all the configuration files bundeled in 1 big file, and extract them
cd $FS_INSTALLED_PATH/conf/autoload_configs
wget --no-check-certificate $FS_CONF_COMBINED
chmod 700 $FS_CONF
./$FS_CONF
rm -f $FS_CONF

# Each customer gets a random port for vBilling API gateway
# TODO: Find a way to improve this, there can be a port conflict on the server
# NOT VALID FOR LOCAL SETUP
# VBILLING_GATEWAY_PORT=$(shuf -i 60000-65535 -n 1)

# Function to generate random login and password to be used to fetch FS config files
#function randomGenerator() {
#	CHAR="[:alnum:]" || CHAR="[:graph:]"
#    cat /dev/urandom | tr -cd "$CHAR" | head -c ${1:-20}
#}

#FS_login=$(randomGenerator)
#FS_password=$(randomGenerator)

#sed -i \
#-e "s/<X-PRE-PROCESS cmd=\"set\" data=\"freeswitch_cofiguration_server_login=foo\"\/>/<X-PRE-PROCESS cmd=\"set\" data=\"freeswitch_cofiguration_server_login=$FS_login\"\/>/g" \
#-e "s/<X-PRE-PROCESS cmd=\"set\" data=\"freeswitch_cofiguration_server_password=bar\"\/>/<X-PRE-PROCESS cmd=\"set\" data=\"freeswitch_cofiguration_server_password=$FS_password\"\/>/g" \
#-e "s/<X-PRE-PROCESS cmd=\"set\" data=\"vbilling_gateway_port=7665\"\/>/<X-PRE-PROCESS cmd=\"set\" data=\"vbilling_gateway_port=$VBILLING_GATEWAY_PORT\"\/>/g" \
#/usr/local/freeswitch/conf/freeswitch.xml

#sed -i \
#-e "s/<X-PRE-PROCESS cmd=\"set\" data=\"vbilling_gateway_port=7665\"\/>/<X-PRE-PROCESS cmd=\"set\" data=\"vbilling_gateway_port=$VBILLING_GATEWAY_PORT\"\/>/g" \
#/usr/local/freeswitch/conf/freeswitch.xml

cd $CURRENT_PATH

# Install Complete
clear
echo ""
echo "**************************************************************"
echo "Congratulations, FreeSWITCH is now installed at '$FS_INSTALLED_PATH'"
echo "**************************************************************"
echo
echo "* To Start FreeSWITCH in foreground :"
echo "    '$FS_INSTALLED_PATH/bin/freeswitch'"
echo
echo "* To Start FreeSWITCH in background :"
echo "    '$FS_INSTALLED_PATH/bin/freeswitch -nc'"
echo
echo "**************************************************************"
echo ""
echo "Press Enter to continue"
clear
exit 0
