#!/bin/bash
# 
#  install.sh
#  scripts
#  
#  FreeSWITCH and vBilling install script v1.0
#
#  Updated by Muhammad Naseer Bhatti on 2011-09-15.
#  Copyright 2011 Digital Linx. All rights reserved.
# 

# Define some variables
# VBILLING_REPO=git://github.com/digitallinx/vBilling.git
VBILLING_REPO=git://github.com/digitallinx/vBilling.git
API_REPO=git://github.com/digitallinx/plivo.git
TEMPDIR=$(/bin/mktemp -d)
FS_INSTALL_SCRIPT="$TEMPDIR"/scripts/freeswitch/freeswitch_install.sh
API_INSTALL_SCRIPT="$TEMPDIR"/scripts/api_install.sh
API_INSTALL_PATH=/home/vBilling/api

# Prompt user for installation of FS and vBilling on same machine or split install
# We currently support single instance install as ease of management for the user (YET)
# Distributed setup is highly recommended and is only supported by professional services
# Distributed setup is supported by OpenSIPS/FreeSWITCH and vBilling

clear
echo ""
read -n 1 -p "*** Do you want to install FreeSWITCH and vBilling on the same machine? (y/n) : "
echo

if [ $REPLY   = "y" ]; then
	clear
	echo ""
	echo "*** The setup will install all the necessary components on the current machine"
	read -n 1 -p "*** Press any key to continue..."
	clear

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
	echo "*** Setting up Prerequisites and Dependencies"

	case $DIST in
		'DEBIAN')
		export DEBIAN_FRONTEND=noninteractive
		apt-get -y update
		apt-get -y install git-core
		;;
		'CENTOS')
		yum -y update
		VERS=$(cat /etc/redhat-release |cut -d' ' -f4 |cut -d'.' -f1)
		if [ "$VERS" = "6" ]
			then
			yum -y install git
		else
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
	git clone $VBILLING_REPO $TEMPDIR
	chmod 700 $FS_INSTALL_SCRIPT
	/$FS_INSTALL_SCRIPT $TEMPDIR
elif [ $REPLY = "n" ]; then
	clear
	echo ""
	echo "*** Installation of split instance is not supported through this script yet"
	echo "*** Please send an mail to \"support@vbilling.org\" in order to have a distributed setup"
	echo ""
	echo "*** Installation aborted"
	echo ""
	# We remove all local source, in order to download fresh files against updates
	rm -rf $TEMPDIR
	exit 1
else
	clear
	echo ""
	echo "*** Your input was not correct, installation aborted."
	echo ""
	# We remove all local source, in order to download fresh files against updates
	rm -rf $TEMPDIR
	exit 1
fi

# FS install is good. Let's move forward for API install
clear
echo ""
read -n 1 -p "*** Press any key to install vBilling API..."
echo ""
chmod 700 $API_INSTALL_SCRIPT
/$API_INSTALL_SCRIPT $API_INSTALL_PATH

# FS, API install complete. Continue to vBilling install
clear
echo ""
echo "*** vBilling source is not online at the moment. Please send an email to"
echo "*** \"support@vbilling.org\" and ask for a manual install."
echo "*** Install finished"
echo ""

# We remove all local source, in order to download fresh files against updates
rm -rf $TEMPDIR
