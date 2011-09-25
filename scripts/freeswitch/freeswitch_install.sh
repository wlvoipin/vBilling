#!/bin/bash
#
# FreeSWITCH Installation script for CentOS 5.x/6.x and Debian based distros 
# (Debian 6.x , Ubuntu 10.04 and above)
# This script gears toward configuration which will be used for vBilling only!
#
# DO NOT USE FOR REGULAR INSTALLATION OF FREESWICH!
#
# Copyright (c) 2011 Digital Linx. See LICENSE for details.

# TODO 
# Configure FS install script to use xml_curl for FS configuration

# Define some variables
TEMPDIR=$1
FS_GIT_REPO=git://git.freeswitch.org/freeswitch.git
FS_INSTALL_PATH=/home/vBilling/freeswitch
FS_CONF_FSXML="$TEMPDIR"/scripts/freeswitch/freeswitch.xml
FS_CONF_COMBINED="$TEMPDIR"/scripts/freeswitch/conf/freeswitch_combined_config.sh
FS_INIT_DEBIAN="$TEMPDIR"/scripts/freeswitch/initscripts/debian/freeswitch
FS_INIT_CENTOS="$TEMPDIR"/scripts/freeswitch/initscripts/centos/freeswitch
FS_CONF_PATH_MODULE="$TEMPDIR"/scripts/freeswitch/modules.conf
FS_BASE_PATH=/usr/src/
CURRENT_PATH=$PWD

# Identify Linux Distribution
if [ -f /etc/debian_version ] ; then
	DIST="DEBIAN"
elif [ -f /etc/redhat-release ] ; then
	DIST="CENTOS"
else
	echo ""
	echo "*** This Installer should be run on a CentOS or a Debian based system"
	echo ""
	exit 1
fi

clear
echo ""
echo "*** FreeSWITCH will be installed in \"$FS_INSTALL_PATH\""
echo "*** Press any key to continue or CTRL-C to exit"
echo ""
read INPUT

echo "*** Setting up Prerequisites and Dependencies for FreeSWITCH"
case $DIST in
	'DEBIAN')
	apt-get -y update
	apt-get -y install autoconf automake autotools-dev binutils bison build-essential cpp curl flex g++ gcc git-core less libaudiofile-dev libc6-dev libdb-dev libexpat1 libgdbm-dev libgnutls-dev libmcrypt-dev libncurses5-dev libnewt-dev libpcre3 libpopt-dev libsctp-dev libsqlite3-dev libtiff4 libtiff4-dev libtool libx11-dev libxml2 libxml2-dev lksctp-tools lynx m4 make mcrypt nano ncftp nmap openssl sox sqlite3 ssl-cert ssl-cert unixodbc-dev unzip zip zlib1g-dev zlib1g-dev libjpeg-dev sox
	;;
	'CENTOS')
	yum -y update
	VERS=$(cat /etc/redhat-release |cut -d' ' -f4 |cut -d'.' -f1)
	COMMON_PKGS=" autoconf automake bzip2 cpio curl curl-devel curl-devel expat-devel fileutils gcc-c++ gettext-devel gnutls-devel libjpeg-devel libogg-devel libtiff-devel libtool libvorbis-devel make ncurses-devel nmap openssl openssl-devel openssl-devel perl patch unixODBC unixODBC-devel unzip wget zip zlib zlib-devel bison sox"
	if [ "$VERS" = "6" ]
		then
		yum -y install $COMMON_PKGS git
	else
		yum -y install $COMMON_PKGS
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
# sh bootstrap.sh && ./configure --prefix=$FS_INSTALL_PATH
# [ -f modules.conf ] && rm -f modules.conf

# We will copy modules.conf file customized for our API
# cp $FS_CONF_PATH_MODULE .
# make && make install

# Just making sure we have good binaries in our path :)
echo "" >> /etc/profile
echo "### START -- Path added by vBilling for FreeSWITCH binaries" >> /etc/profile
echo "export PATH=\$PATH:/home/vBilling/freeswitch/bin" >> /etc/profile
echo "### END -- Path added by vBilling for FreeSWITCH binaries" >> /etc/profile
echo "" >> /etc/profile
source /etc/profile
cd $FS_INSTALL_PATH/conf

# We do not want any of the configs. Let's make room for our own
rm -rf "$FS_INSTALL_PATH"/conf/*

# Instead copy our own generated XML files. We use xml_curl for everything
cp $FS_CONF_FSXML $FS_INSTALL_PATH/conf/freeswitch.xml
cd $CURRENT_PATH

# Install init scripts
case $DIST in
	"DEBIAN")
	# Download FS init script
	cp $FS_INIT_DEBIAN /etc/init.d/freeswitch
	chmod 755 /etc/init.d/freeswitch
	echo "FREESWITCH_ENABLED=true" > /etc/default/freeswitch
	cd /etc/rc2.d
	ln -s /etc/init.d/freeswitch S99freeswitch
	;;

	"CENTOS")
	# Download FS init script
	cp $FS_INIT_CENTOS /etc/init.d/freeswitch
	chmod 755 /etc/init.d/freeswitch
	chkconfig --add freeswitch
	;;
esac

# Add FreeSWITCH user without any password
useradd -M -d $FS_INSTALL_PATH -c "FreeSWITCH Engine User" -s /bin/false freeswitch

# Install Complete
# Let's start the service(s)
clear
echo ""
echo "*** Congratulations, FreeSWITCH is now installed at \"$FS_INSTALL_PATH\""
read -n 1 -p "*** Press any key to start FreeSWITCH now ..."
/etc/init.d/freeswitch start
read -n 1 -p "*** Press any key to continue..."
exit 0
