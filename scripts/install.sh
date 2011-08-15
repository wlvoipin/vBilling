#!/bin/bash
#
# FreeSWITCH and vBilling install script v0.2
#

# Define some variables
FS_INSTALL_SCRIPT=https://raw.github.com/digitallinx/vBilling/master/scripts/freeswitch/freeswitch_install.sh
FS_INSTALL_SCRIPT_NAME=freeswitch_install.sh

# Prompt the user for installation of FS and vBilling on same machine or split install
# We currently support single instance install as ease of management for the user

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
	echo "I am going to install FreeSWITCH and vBilling on same machine"
	read -n 1 -p "Press any key to continue..."
	clear
#	Remove any previous versions of FS install script
	rm -f $FS_INSTALL_SCRIPT_NAME
	wget --no-check-certificate $FS_INSTALL_SCRIPT
	chmod 700 $FS_INSTALL_SCRIPT_NAME
	./$FS_INSTALL_SCRIPT_NAME
	exit 1
	
elif [ $INSTALL_BOTH = "n" ]; then
	clear
	echo ""
	echo "*** Installation of split instance is not supported through this script yet"
	echo "*** Please contact vBilling@digitallinx.com for more info"
	echo ""
	echo "*** Installation aborted"
	echo ""
fi


