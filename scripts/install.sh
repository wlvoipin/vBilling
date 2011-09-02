#!/bin/bash
#
# FreeSWITCH and vBilling install script v0.4
#

# Define some variables
FS_INSTALL_SCRIPT=https://raw.github.com/digitallinx/vBilling/master/scripts/freeswitch/freeswitch_install.sh
FS_INSTALL_SCRIPT_NAME=freeswitch_install.sh
API_INSTALL_SCRIPT=https://raw.github.com/digitallinx/vBilling/master/scripts/api_install.sh
API_INSTALL_SCRIPT_NAME=api_install.sh
API_INSTALL_PATH=/home/vBilling/api

# Prompt user for installation of FS and vBilling on same machine or split install
# We currently support single instance install as ease of management for the user (YET)
# Distributed setup is highly recommended and is only supported by consulting services

clear
echo ""
read -n 1 -p "Do you want to install FreeSWITCH and vBilling on the same machine? (y/n) : "
echo
if [ $REPLY = "y" ]; then
	INSTALL_BOTH=y
elif [ $REPLY = "n" ]; then
	INSTALL_BOTH=n
else
	clear
	echo ""
	echo "*** Your input was not correct, installation aborted."
	echo ""
	exit 1
fi

if [ $INSTALL_BOTH = "y" ]; then
	clear
	echo ""
	echo "I am going to install FreeSWITCH and vBilling on the same machine"
	read -n 1 -p "Press any key to continue..."
	clear
#	Remove any previous versions of FS install script
	rm -f $FS_INSTALL_SCRIPT_NAME
	wget --no-check-certificate $FS_INSTALL_SCRIPT
	chmod 700 $FS_INSTALL_SCRIPT_NAME
	./$FS_INSTALL_SCRIPT_NAME
#	exit 1

elif [ $INSTALL_BOTH = "n" ]; then
	clear
	echo ""
	echo "*** Installation of split instance is not supported through this script yet"
	echo "*** Please contact vBilling@digitallinx.com if you like to have a distributed setup for vBilling"
	echo ""
	echo "*** Installation aborted"
	echo ""
	exit 1
fi

echo ""
echo "Now going to install the Billing API"
read -n 1 -p "Press any key to continue..."
echo ""
wget --no-check-certificate $API_INSTALL_SCRIPT
chmod 750 $API_INSTALL_SCRIPT_NAME
./$API_INSTALL_SCRIPT_NAME $API_INSTALL_PATH
rm -f $API_INSTALL_SCRIPT_NAME

# FS, API install complete. Continue to vBilling install
clear
echo ""
echo "FreeSWITCH and vBilling API have been installed and configured successfully"
read -n 1 -p "Press any key to continue installing vBilling web application..."
echo ""
