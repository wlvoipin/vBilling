#!/bin/bash
# 
# install.sh
# scripts
#  
# vBilling and FreeSWITCH install script v1.5
# Copyright 2011-12 Digital Linx. All rights reserved.
#
# Last update 15-09-2012
#
# Define some variables
#
VBILLING_REPO=git://github.com/digitallinx/vBilling.git
TEMPDIR=$(/bin/mktemp -d)
FS_GIT_REPO=git://git.freeswitch.org/freeswitch.git
FS_INSTALL_PATH=/home/vBilling/freeswitch
FS_BASE_PATH=/usr/local/src/
FS_USER=freeswitch
VBILLING_DB=vBilling
VBILLING_DB_USER=vBilling
CURRENT_PATH=$PWD
UPGRADE="0"

# License acceptance (MPL 1.1)
clear
echo "***"
wget --no-check-certificate -q -O MPL-v1.1.txt https://github.com/digitallinx/vBilling/raw/master/COPYING
more MPL-v1.1.txt
echo "***"
echo "*** vBilling License MPL V1.1"
echo "***"
echo "*** This Source Code Form is subject to the terms of the Mozilla Public"
echo "*** License, v1.1. If a copy of the MPL was not distributed with this package,"
echo "*** You can obtain one from http://mozilla.org/MPL/1.1/"
echo "***"
echo "*** Copyright (c) 2011-2012. Digital Linx"
echo "***"
echo "*** I agree to be bound by the terms of the license - [YES/NO]"
echo "*** "
read ACCEPT

while [ "$ACCEPT" != "yes" ] && [ "$ACCEPT" != "Yes" ] && [ "$ACCEPT" != "YES" ] && [ "$ACCEPT" != "no" ] && [ "$ACCEPT" != "No" ] && [ "$ACCEPT" != "NO" ]; do
	echo "I agree to be bound by the terms of the license - [YES/NO]"
	read ACCEPT
done

if [ "$ACCEPT" != "yes" ] && [ "$ACCEPT" != "Yes" ] && [ "$ACCEPT" != "YES" ]; then
	echo "License rejected !"
	exit 0
else
	echo "Licence accepted !"
fi

# Check if vBilling already installed
if [ -d "/home/vBilling" ]; then
	UPGRADE="1"
	clear
	echo "***"
	read -n 1 -p "*** vBilling already installed. Please any key to upgrade ... "
fi

# Determine the OS architecture
if [ ${HOSTTYPE} == "x86_64" ]; then
	ARCH=x64
else
	ARCH=x32
fi

# Set htdocs path based on the distro
if [ -f /etc/debian_version ] ; then
	VBILLING_HTML="/var/www"
else [ -f /etc/redhat-release ]
	VBILLING_HTML="/var/www/html"
fi

# Prompt user for installation of FS and vBilling on same machine or split install
# We currently support single instance install as ease of management for the user (YET)
# Distributed setup is highly recommended and is only supported by professional services
# Distributed setup is supported by OpenSIPS/FreeSWITCH and vBilling

if [ ${UPGRADE} == "0" ]; then
clear
echo "***"
echo "*** This installation script *must only* run on a fresh installed OS."
read -n 1 -p "*** Failure to do so may result in data loss and/or corruption. Press any key to continue ... "
echo "***"

# Distributed setup is not supported yet through the script. Contact vbilling@digitallinx.com for a quote if required
# This is only supported by professional services and is a paid service
clear
echo "***"
read -n 1 -p "*** Do you want to install FreeSWITCH and vBilling on the same machine? (y/n) : "
echo

if [ ${REPLY}   = "y" ]; then
	clear
	echo "***"
	echo "*** The setup will install all the necessary components on the current machine"
	read -n 1 -p "*** Press any key to continue ..."
	clear

	# Identify Linux Distribution
	if [ -f /etc/debian_version ] ; then
		DIST="DEBIAN"
	elif [ -f /etc/redhat-release ] ; then
		DIST="CENTOS"
	else
		echo "***"
		echo "*** This Installer should be run only on CentOS 6.x or Debian based system"
		echo "***"
		exit 1
	fi

	clear
	echo "*** Setting up Prerequisites and Dependencies"

	case ${DIST} in
		'DEBIAN')
		export DEBIAN_FRONTEND=noninteractive
        apt-get -y update
        apt-get -y install apache2 autoconf automake build-essential chkconfig dmidecode g++ gawk git-core git-core gnutls-bin libapache2-mod-php5 libncurses5 libjpeg62-dev libmyodbc libncurses5-dev libtool libtool libxml2 libexpat1-dev libapr1-dev libpcre3 libpcre3-dev lua5.1 make bsd-mailx mysql-server php-apc php5 php5-gd php5-mcrypt php5-mhash php5-mysql pkg-config python-dev unixodbc-dev
		;;
		'CENTOS')
		yum -y update
		VERS=$(cat /etc/redhat-release | cut -d ' ' -f3 | cut -d '.' -f1)
        COMMON_PKGS="bison bzip2 cpio curl curl-devel dmidecode expat-devel gcc-c++ git gnutls-devel httpd libjpeg-devel libogg-devel libtiff-devel libtool libvorbis-devel lua-static make mailx mysql-connector-odbc mysql-server ncurses-devel openssl-devel php php-bcmath php-cli php-common php-gd php-mbstring php-mysql php-pdo php-xml python-devel unixODBC unixODBC-devel which zlib zlib-devel"
		if [ ${VERS} = "6" ]
			then
			yum -y install ${COMMON_PKGS}
			# Disable SELinux
			if [ -f /etc/selinux/config ]; then
				sed -i "s#SELINUX=enforcing#SELINUX=disabled#g" /etc/selinux/config
				sed -i "s#SELINUX=permissive#SELINUX=disabled#g" /etc/selinux/config
				setenforce 0
			fi
		else
			# We are not supporting CentOS < 6 anymore. Reason is timers/mod_timerfd which is not supported in older kernels
			echo "CentOS version < 6 is not supported. Exiting ..."
			exit 1
		fi
		;;
	esac

# Install and configure FreeSWITCH
cd ${FS_BASE_PATH}
git clone ${FS_GIT_REPO}
cd ${FS_BASE_PATH}/freeswitch
sh bootstrap.sh && ./configure --prefix=${FS_INSTALL_PATH}
[ -f modules.conf ] && mv modules.conf modules.conf.bak

# We just need necessary modules
cat << 'EOF' > modules.conf
loggers/mod_console
loggers/mod_logfile
loggers/mod_syslog
applications/mod_commands
applications/mod_dptools
applications/mod_fifo
applications/mod_db
applications/mod_hash
applications/mod_expr
applications/mod_esf
codecs/mod_g723_1
codecs/mod_amr
codecs/mod_g729
# codecs/mod_com_g729
codecs/mod_ilbc
codecs/mod_speex
dialplans/mod_dialplan_xml
endpoints/mod_sofia
# endpoints/mod_loopback
event_handlers/mod_event_socket
# event_handlers/mod_cdr_csv
formats/mod_native_file
formats/mod_sndfile
formats/mod_local_stream
formats/mod_tone_stream
languages/mod_lua
xml_int/mod_xml_curl
xml_int/mod_xml_cdr
EOF

make && make install

# We don't need default config files. We use our own, mainly for XML_CURL :-) We have all the the magic here
rm -rf ${FS_INSTALL_PATH}/conf
mkdir -p ${FS_INSTALL_PATH}/conf

# Setup freeswitch.xml
cat << 'EOF' > ${FS_INSTALL_PATH}/conf/freeswitch.xml
<?xml version="1.0"?>
<document type="freeswitch/xml">
	<X-PRE-PROCESS cmd="set" data="domain=$${local_ip_v4}" />
	<X-PRE-PROCESS cmd="set" data="domain_name=$${domain}" />
	<X-PRE-PROCESS cmd="set" data="codecs=PCMU,PCMA,GSM,G723,G729" />
	<X-PRE-PROCESS cmd="set" data="console_loglevel=crit" />

	<!-- vBilling Custom Defines. START -->
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_curl_url=http://127.0.0.1/xmlcurl/index.php" />
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_curl_binding=configuration|directory" />
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_cdr_url=http://127.0.0.1/xmlcurl/index.php" />
	<X-PRE-PROCESS cmd="set" data="event_socket_listen_ip=127.0.0.1" />
	<X-PRE-PROCESS cmd="set" data="event_socket_listen_port=8021" />
	<X-PRE-PROCESS cmd="set" data="event_socket_password=ClueCon" />
	<!-- vBilling Custom Defines. END -->

	<section name="configuration" description="Various Configuration">
		<configuration name="modules.conf" description="Modules">
			<modules>
				<!-- We load these two modules from mod_xml_curl. We set all the params 
					from there. If you want to enable these modules from without loading from 
					mod_xml_curl, you would have to provide appropriate config files -->
				<!--
				<load module="mod_console"/>
				<load module="mod_logfile"/>
				-->
				<load module="mod_xml_curl" />
			</modules>
		</configuration>

		<configuration name="lua.conf" description="LUA Configuration">
			<settings>
				<param name="script-directory" value="/home/vBilling/freeswitch/scripts/?.lua" />
				<param name="script-directory" value="$${base_dir}/scripts/?.lua" />
				<param name="xml-handler-script" value="vBilling.lua" />
				<param name="xml-handler-bindings" value="dialplan" />
			</settings>
		</configuration>

		<configuration name="switch.conf" description="Modules">
			<settings>
				<!-- You are welcome to increase the max-session limit. Make sure to 
					tune MySQL and the web server for large number of connections -->
				<param name="max-sessions" value="1000" />
				<param name="sessions-per-second" value="25" />
				<param name="switchname" value="vBilling" />

				<!--RTP port range -->
				<param name="rtp-start-port" value="16000" />
				<param name="rtp-end-port" value="62000" />
				<param name="dump-cores" value="true" />
				<param name="rtp-enable-zrtp" value="false" />
				<param name="enable-early-hangup" value="true" />
				<param name="auto-clear-sql" value="true" />
				<param name="max-db-handles" value="1000" />
				<param name="db-handle-timeout" value="5" />
				<param name="auto-create-schemas" value="true" />
			</settings>
			<!-- Any variables defined here will be available in every channel, in 
				the dialplan etc -->
		</configuration>

		<configuration name="xml_curl.conf" description="cURL XML Gateway">
			<bindings>
				<binding name="production">
					<param name="gateway-url" value="$${vBilling_xml_curl_url}"
						bindings="$${vBilling_xml_curl_binding}" />
				</binding>
			</bindings>
		</configuration>
	</section>
</document>
EOF

# Add a system user to run FreeSWITCH as
useradd -c "FreeSwitch Voice Switching System" -d ${FS_INSTALL_PATH} -M -s /bin/false ${FS_USER}

# We lock freeswitch user password to avoid any security issues .. ?
passwd -l ${FS_USER}
chown -R ${FS_USER}:${FS_USER} ${FS_INSTALL_PATH}

# Just making sure we have good and prefered binaries in our path :)
cat << 'EOF' >> /etc/profile
#
# START -- Path added by vBilling for FreeSWITCH binaries
export PATH=/home/vBilling/freeswitch/bin:${PATH}
# E N D -- Path added by vBilling for FreeSWITCH binaries
#
EOF

# Sourcing a profile from the script will not work, as the script and the user logged in have different envs
# It only works for within the execution of the script.
source /etc/profile

# FreeSWITCH install and config is done. Let's move forward
# Install FreeSWITCH init scripts
case ${DIST} in
	"DEBIAN")
cat << 'EOF' > /etc/init.d/freeswitch
#! /bin/sh
### BEGIN INIT INFO
# Provides:          freeswitch
# Required-Start:    $network $local_fs $remote_fs
# Required-Stop:     $network $local_fs $remote_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: The FreeSWITCH Voice Switching System
# Description:       An advanced platform for voice services
### END INIT INFO

PATH=/sbin:/usr/sbin:/bin:/usr/bin:/home/vBilling/freeswitch/bin
DESC="FreeSWITCH Voice Switching System"
NAME=freeswitch
DAEMON=/home/vBilling/freeswitch/bin/$NAME
FREESWITCH_PARAMS="-nonat -nc"
USER=freeswitch
PIDFILE=/home/vBilling/freeswitch/run/$NAME.pid
SCRIPTNAME=/etc/init.d/$NAME
WORKDIR=/home/vBilling/$NAME

# Check if we are being executed by init
CALLEDSCRIPT=`basename $0`

if [ -r /etc/default/$NAME ]; then . /etc/default/$NAME; fi                                           

if [ "$FREESWITCH_ENABLED" != "true" ]; then 
    echo "$DESC not enabled yet. Edit /etc/default/$NAME first."
    exit 0 ;
fi

# Read configuration variable file if it is present
[ -r /etc/default/$NAME ] && . /etc/default/$NAME

# Load the VERBOSE setting and other rcS variables
. /lib/init/vars.sh

# Define LSB log_* functions.
# Depend on lsb-base (>= 3.0-6) to ensure that this file is present.
. /lib/lsb/init-functions

#
# Function that sets ulimit values for the daemon
#
do_setlimits() {
	ulimit -c unlimited
	ulimit -d unlimited
	ulimit -f unlimited
	# ulimit -i unlimited
	ulimit -n 999999
	# ulimit -q unlimited
	# ulimit -u unlimited
	ulimit -v unlimited
	# ulimit -x unlimited
	ulimit -s 240
	ulimit -l unlimited
	return 0
}

#
# Function that starts the daemon/service
#
do_start()
{
	# Return
	#   0 if daemon has been started
	#   1 if daemon was already running
	#   2 if daemon could not be started
	start-stop-daemon -d $WORKDIR -c $USER --start --quiet --pidfile $PIDFILE --exec $DAEMON --test > /dev/null \
		|| return 1
	do_setlimits
	start-stop-daemon -d $WORKDIR -c $USER --start --quiet --pidfile $PIDFILE --exec $DAEMON -- \
		$FREESWITCH_PARAMS \
		|| return 2
	# Add code here, if necessary, that waits for the process to be ready
	# to handle requests from services started subsequently which depend
	# on this one.  As a last resort, sleep for some time.
}

#
# Function that stops the daemon/service
#
do_stop()
{
	# Return
	#   0 if daemon has been stopped
	#   1 if daemon was already stopped
	#   2 if daemon could not be stopped
	#   other if a failure occurred
	$DAEMON -stop
	start-stop-daemon -d $WORKDIR -c $USER --stop --quiet --retry=TERM/30/KILL/5 --pidfile $PIDFILE --name $NAME
	RETVAL="$?"
	[ "$RETVAL" = 2 ] && return 2
	# Wait for children to finish too if this is a daemon that forks
	# and if the daemon is only ever run from this initscript.
	# If the above conditions are not satisfied then add some other code
	# that waits for the process to drop all resources that could be
	# needed by services started subsequently.  A last resort is to
	# sleep for some time.
	start-stop-daemon -d $WORKDIR -c $USER --stop --quiet --oknodo --retry=0/30/KILL/5 --exec $DAEMON
	[ "$?" = 2 ] && return 2
	# Many daemons don't delete their pidfiles when they exit.
	rm -f $PIDFILE
	return "$RETVAL"
}

#
# Function that sends a SIGHUP to the daemon/service
#
do_reload() {
	#
	# If the daemon can reload its configuration without
	# restarting (for example, when it is sent a SIGHUP),
	# then implement that here.
	#
	start-stop-daemon -c $USER --stop --signal 1 --quiet --pidfile $PIDFILE --name $NAME
	return 0
}

case "$1" in
  start)
	[ "$VERBOSE" != no ] && log_daemon_msg "Starting $DESC" "$NAME"
	do_start
	case "$?" in
		0|1) [ "$VERBOSE" != no ] && log_end_msg 0 ;;
		2) [ "$VERBOSE" != no ] && log_end_msg 1 ;;
	esac
	;;
  stop)
	[ "$VERBOSE" != no ] && log_daemon_msg "Stopping $DESC" "$NAME"
	do_stop
	case "$?" in
		0|1) [ "$VERBOSE" != no ] && log_end_msg 0 ;;
		2) [ "$VERBOSE" != no ] && log_end_msg 1 ;;
	esac
	;;
  reload|force-reload)
	#
	# If do_reload() is not implemented then leave this commented out
	# and leave 'force-reload' as an alias for 'restart'.
	#
	log_daemon_msg "Reloading $DESC" "$NAME"
	do_reload
	log_end_msg $?
	;;
  restart)
	#
	# If the "reload" option is implemented then remove the
	# 'force-reload' alias
	#
	log_daemon_msg "Restarting $DESC" "$NAME"
	do_stop
	case "$?" in
	  0|1)
		do_start
		case "$?" in
			0) log_end_msg 0 ;;
			1) log_end_msg 1 ;; # Old process is still running
			*) log_end_msg 1 ;; # Failed to start
		esac
		;;
	  *)
	  	# Failed to stop
		log_end_msg 1
		;;
	esac
	;;
  *)
	echo "Usage: $SCRIPTNAME {start|stop|restart|reload|force-reload}" >&2
	exit 3
	;;
esac

:
EOF

	chmod 755 /etc/init.d/freeswitch
	echo "FREESWITCH_ENABLED=true" > /etc/default/freeswitch
	chkconfig --add apache2
	chkconfig apache2 on
	chkconfig --add freeswitch
	chkconfig freeswitch on
	chkconfig --add mysql
	chkconfig mysql on
	;;

	"CENTOS")
cat  << 'EOF' > /etc/init.d/freeswitch
#!/bin/bash
#
# freeswitch:       Starts the freeswitch Daemon
#
# chkconfig: 345 90 02
# description: FreeSwitch
### BEGIN INIT INFO
# Provides: $freeswitch
# Required-Start: $local_fs
# Required-Stop: $local_fs
# Default-Start:  2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: Freeswitch
# Description: Freeswitch

### END INIT INFO

# Source function library.
. /etc/init.d/functions
. /etc/sysconfig/network

PATH=/sbin:/usr/sbin:/bin:/usr/bin:/home/vBilling/freeswitch/bin
DESC="FreeSwitch Voice Switching System"
NAME=freeswitch
DAEMON=/home/vBilling/freeswitch/bin/$NAME
FREESWITCH_PARAMS="-nonat -nc"
PIDFILE=/home/vBilling/freeswitch/run/$NAME.pid
prog=freeswitch

do_setlimits() {
        ulimit -c unlimited
        ulimit -d unlimited
        ulimit -f unlimited
        ulimit -i unlimited
        ulimit -n 999999
        ulimit -q unlimited
        ulimit -u unlimited
        ulimit -v unlimited
        ulimit -x unlimited
        ulimit -s 240
        ulimit -l unlimited
        return 0
}

do_start() {
	echo -n $"Starting $prog: "
        do_setlimits
        $DAEMON $FREESWITCH_PARAMS &>/dev/null
        RETVAL=$?
	[ $RETVAL -eq 0 ] && success || failure
        echo 
        return $RETVAL
}

do_stop() {
	echo -n $"Shutting down $prog: "
        $DAEMON -stop &>/dev/null
        RETVAL=$?
	[ $RETVAL -eq 0 ] && success || failure
        echo
        return $RETVAL
}

# See how we were called.
case "$1" in
  start)
        do_start
        ;;
  stop)
        do_stop
        ;;
  restart)
        do_stop
        echo "Waiting for daemon to exit..."
        sleep 5
        do_start
        ;;
  status)
        status -p $PIDFILE freeswitch
        ;;
  *)
        echo $"Usage: $0 {start|stop|restart|status}"
        exit 2
        ;;
esac

exit $RETVAL
EOF

	chmod 755 /etc/init.d/freeswitch
	chkconfig --add httpd
	chkconfig --levels 35 httpd on
	chkconfig --add freeswitch
	chkconfig --levels 35 freeswitch on
	chkconfig --add mysqld
	chkconfig --levels 35 mysqld on
	;;
esac

# cd ${CURRENT_PATH}
# Generate random password (for MySQL)
genpasswd() {
	length=$1
	[ "$length" == "" ] && length=16
	tr -dc A-Za-z0-9_ < /dev/urandom | head -c ${length} | xargs
}

# Start MySQL server
if [ -f /etc/debian_version ] ; then
	/etc/init.d/mysql restart
else [ -f /etc/redhat-release ]
	/etc/init.d/mysqld restart
fi

# Configure MySQL server
sleep 5
MYSQL_ROOT_PASSWORD=$(genpasswd)
mysql -uroot -e "UPDATE mysql.user SET password=PASSWORD('${MYSQL_ROOT_PASSWORD}') WHERE user='root'; FLUSH PRIVILEGES;"

# Save MySQL root password to a text file in /root
echo "***"
echo "MySQL password set to '${MYSQL_ROOT_PASSWORD}'. Remember to delete ~/.mysql_passwd" | tee ~/.mysql_passwd
chmod 400 ~/.mysql_passwd
read -n 1 -p "*** Press any key to continue ..."

# Setup vBilling DB user, create database and import. Web config files will be configured later
VBILLING_MYSQL_PASSWORD=$(genpasswd)
mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "CREATE USER '${VBILLING_DB_USER}'@'localhost' IDENTIFIED BY '${VBILLING_MYSQL_PASSWORD}';"
mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "GRANT USAGE ON *.* TO '${VBILLING_DB_USER}'@'localhost' IDENTIFIED BY '${VBILLING_MYSQL_PASSWORD}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "GRANT ALL PRIVILEGES ON \`${VBILLING_DB}\` . * TO '${VBILLING_DB_USER}'@'localhost' WITH GRANT OPTION;"

# Download vBilling source
git clone ${VBILLING_REPO} ${TEMPDIR}/vBilling
cp -apr ${TEMPDIR}/vBilling/htdocs/*  ${VBILLING_HTML}/
cp -apr ${TEMPDIR}/vBilling/htdocs/.htaccess  ${VBILLING_HTML}/

# Create MySQL DB and import the database
mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "create database ${VBILLING_DB};"
mysql -u${VBILLING_DB_USER} -p${VBILLING_MYSQL_PASSWORD} ${VBILLING_DB} < ${TEMPDIR}/vBilling/htdocs/sql/vBilling_complete.sql

# Pre Install Complete, let's move forward

# Enable required apache modules
if [ -f /etc/debian_version ] ; then
	/usr/sbin/a2enmod php5 rewrite
	sed -i "s#		AllowOverride None#		AllowOverride All#g" /etc/apache2/sites-available/default
	/etc/init.d/apache2 restart
else [ -f /etc/redhat-release ]
	sed -i "s#    AllowOverride None#    AllowOverride All#g" /etc/httpd/conf/httpd.conf
	/etc/init.d/httpd restart
fi

# Generate random 30 charactor password for encryption key
ENCRYPTION_KEY=$(genpasswd 30)

# Setup some configuration files accordingly
sed -i "s#\$config\['encryption_key'\] = 'VerySecure';#\$config\['encryption_key'\] = '${ENCRYPTION_KEY}';#g" ${VBILLING_HTML}/application/config/config.php
sed -i "s#define('DEFAULT_DSN_LOGIN', 'MYSQL_USERNAME');#define('DEFAULT_DSN_LOGIN', '${VBILLING_DB_USER}');#g" ${VBILLING_HTML}/application/config/constants.php
sed -i "s#define('DEFAULT_DSN_PASSWORD', 'MYSQL_PASSWORD');#define('DEFAULT_DSN_PASSWORD', '${VBILLING_MYSQL_PASSWORD}');#g" ${VBILLING_HTML}/application/config/constants.php
sed -i "s#\$db\['default'\]\['username'\] = 'MYSQL_USERNAME';#\$db\['default'\]\['username'\] = '${VBILLING_DB_USER}';#g" ${VBILLING_HTML}/application/config/database.php
sed -i "s#\$db\['default'\]\['password'\] = 'MYSQL_PASSWORD';#\$db\['default'\]\['password'\] = '${VBILLING_MYSQL_PASSWORD}';#g" ${VBILLING_HTML}/application/config/database.php
sed -i "s#\$db\['default'\]\['database'\] = 'VBILLING_DB';#\$db\['default'\]\['database'\] = '${VBILLING_DB}';#g" ${VBILLING_HTML}/application/config/database.php

if [ -f /etc/debian_version ] ; then
	chown -R www-data:www-data ${VBILLING_HTML}
	chmod -R 777 ${VBILLING_HTML}/media/
	mkdir ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	chmod 777 ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	if [ -f ${VBILLING_HTML}/index.html ]; then
		rm -rf ${VBILLING_HTML}/index.html
	fi
cat << 'EOF' > /etc/odbc.ini
[vBilling]
Driver   = MySQL
Server   = 127.0.0.1
Port     = 3306
Database = __VBILLING_DB__
OPTION   = 67108864
EOF
	sed -i "s#__VBILLING_DB__#${VBILLING_DB}#g" /etc/odbc.ini
	sed -i "s#\[vBilling\]#\[${VBILLING_DB}\]#g" /etc/odbc.ini
cat << 'EOF' > /etc/odbcinst.ini
[MySQL]
Description = ODBC for MySQL
Driver      = /usr/lib/odbc/libmyodbc.so
Setup       = /usr/lib/odbc/libodbcmyS.so
FileUsage   = 1
Threading   = 0
UsageCount  = 1
EOF
	if [ $(cat /etc/debian_version | cut -d "." -f 1) == 6 ]; then
			ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
			ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
			ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling.cfg ${FS_INSTALL_PATH}/scripts/vBilling.cfg
			ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
			ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
	else
			ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
			ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
			ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling.cfg ${FS_INSTALL_PATH}/scripts/vBilling.cfg
			ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
			ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
	fi
sed -i "s#\"__VBILLING_DB__\"#\"${VBILLING_DB}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
sed -i "s#\"__MYSQL_USERNAME__\"#\"${VBILLING_DB_USER}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
sed -i "s#\"__MYSQL_PASSWORD__\"#\"${VBILLING_MYSQL_PASSWORD}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
/etc/init.d/freeswitch start
else [ -f /etc/redhat-release ]
	chown -R apache:apache ${VBILLING_HTML}
	chmod -R 777 ${VBILLING_HTML}/media/
	mkdir ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	chmod 777 ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	if [ -f ${VBILLING_HTML}/index.html ]; then
		rm -rf ${VBILLING_HTML}/index.html
	fi
cat << 'EOF' > /etc/odbc.ini
[vBilling]
Driver   = MySQL
Server   = 127.0.0.1
Port     = 3306
Database = __VBILLING_DB__
OPTION   = 67108864
EOF
	sed -i "s#__VBILLING_DB__#$VBILLING_DB#g" /etc/odbc.ini
	sed -i "s#\[vBilling\]#\[$VBILLING_DB\]#g" /etc/odbc.ini
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling.cfg ${FS_INSTALL_PATH}/scripts/vBilling.cfg
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
	sed -i "s#\"__VBILLING_DB__\"#\"${VBILLING_DB}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
	sed -i "s#\"__MYSQL_USERNAME__\"#\"${VBILLING_DB_USER}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
	sed -i "s#\"__MYSQL_PASSWORD__\"#\"${VBILLING_MYSQL_PASSWORD}\"#g" ${FS_INSTALL_PATH}/scripts/vBilling.cfg
/etc/init.d/freeswitch start
fi

# Setup cron job for daily invoicing
# Write out current crontab
crontab -u root -l > ${TEMPDIR}/root.cron
# echo new cron into cron file
echo "@daily wget --spider http://127.0.0.1/cron/generate_invoices >/dev/null 2>&1" >> ${TEMPDIR}/root.cron
# install new cron file
crontab -u root ${TEMPDIR}/root.cron
rm -rf ${TEMPDIR}/root.cron

# Install finished
clear
echo "***"
echo "*** The script has finished the install. If everything went well, you should be able to browse vBilling at:"
echo "***"
echo "*** vBilling Management Interface: http://SERVER_IP_ADDRESS/"
echo "*** (Replace SERVER_IP_ADDRESS with your server real IP)"
echo "***"
echo "*** Default Login: admin"
echo "*** Default Password: vBilling"
echo "***"
echo "*** We strongly recommend to change the default password after you login"
echo "***"
echo "*** This version of vBilling supports 250 concurrent calls with 50,000 calls per day"
echo "*** If you need more capacity, please contact vbilling@digitallinx.com"
echo "***"
echo "*** Don't forget to visit http://forum.vbilling.org/ for latest documentation and support options"
echo "*** For premium support, send an email to support@vbilling.org with your technical queries"
echo "***"
echo "*** For a quick start howto, please visit the following URL"
echo "*** http://forum.vbilling.org/viewtopic.php?f=6&t=3"
echo "***"

elif [ $REPLY = "n" ]; then
	clear
	echo "***"
	echo "*** Installation of split instance is not supported through this install script"
	echo "*** Please contact \"support@vbilling.org\" and ask for a distributed setup"
	echo "***"
	echo "*** Installation aborted"
	echo "***"
	# We remove all local source, in order to download fresh files against updates
	rm -rf ${TEMPDIR}
	exit 1
else
	clear
	echo "***"
	echo "*** Your input was not correct, installation aborted."
	echo "***"
	# We remove all local source, in order to download fresh files against updates
	rm -rf ${TEMPDIR}
	exit 1
fi
# We remove all local source, in order to download fresh files against updates
elif [ $UPGRADE = 1 ]; then
	clear
	echo "***"
	echo "*** WARNING .. WARNING .. WARNING ***"
	echo "*** The script will now try to upgrade existing PHP files and the database"
	echo "*** If you have made any changes to PHP files, they will be lost"
	echo "***"
	read -n 1 -p "*** Are you sure you would like to continue? (y/n) "
if [ $REPLY   = "y" ]; then
	clear
	echo "***"
	echo "*** Upgrading vBilling and all other components ..."
	echo "***"
# The upgrade should clone the repo to $TEMPDIR and replace the existing files with the new ones, and change permissions
# This should only work with PHP files and if there is any SQL upgrade.

# Download vBilling source (for the upgrade/update this time)
git clone $VBILLING_REPO ${TEMPDIR}/vBilling

# Take a backup of configuration files before overwriting existing ones
cp -apr ${VBILLING_HTML}/application/config/config.php ${TEMPDIR}/config.php
cp -apr ${VBILLING_HTML}/application/config/database.php ${TEMPDIR}/database.php
cp -apr ${VBILLING_HTML}/application/config/constants.php ${TEMPDIR}/constants.php
cp -apr ${TEMPDIR}/vBilling/htdocs/*  ${VBILLING_HTML}/
cp -apr ${TEMPDIR}/vBilling/htdocs/.htaccess  ${VBILLING_HTML}/

# Copy config files containing DB login/password and other config params back
cp ${TEMPDIR}/config.php ${VBILLING_HTML}/application/config/config.php
cp ${TEMPDIR}/database.php ${VBILLING_HTML}/application/config/database.php
cp ${TEMPDIR}/constants.php ${VBILLING_HTML}/application/config/constants.php

# Capture existing MySQL username and password and execute the queries to update the DB
if [ -f /etc/debian_version ] ; then
	VBILLING_DB_PASSWORD=$(cat /var/www/application/config/constants.php | grep DEFAULT_DSN_PASSWORD | cut -d ',' -f 2 | cut -d \''' -f 2)
	VBILLING_DB_USER=$(cat /var/www/application/config/constants.php | grep DEFAULT_DSN_LOGIN | cut -d ',' -f 2 | cut -d \''' -f 2)
	mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} < ${TEMPDIR}/vBilling/htdocs/sql/vBilling_upgrade_0_2_0.sql
else [ -f /etc/redhat-release ]
	VBILLING_DB_PASSWORD=$(cat /var/www/html/application/config/constants.php | grep DEFAULT_DSN_PASSWORD | cut -d ',' -f 2 | cut -d \''' -f 2)
	VBILLING_DB_USER=$(cat /var/www/html/application/config/constants.php | grep DEFAULT_DSN_LOGIN | cut -d ',' -f 2 | cut -d \''' -f 2)
	mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} < ${TEMPDIR}/vBilling/htdocs/sql/vBilling_upgrade_0_2_0.sql
fi

if [ -f /etc/debian_version ] ; then
	if [ $(cat /etc/debian_version | cut -d "." -f 1) == 6 ]; then
		rm -f ${FS_INSTALL_PATH}/scripts/vBilling.bin
		rm -f ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
		ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
		ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
		ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
		ln -s ${VBILLING_HTML}/bin/debian/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
	else
		rm -f ${FS_INSTALL_PATH}/scripts/vBilling.bin
		rm -f ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
		ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
		ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
		ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
		ln -s ${VBILLING_HTML}/bin/ubuntu/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
	fi
else [ -f /etc/redhat-release ]
	rm -f ${FS_INSTALL_PATH}/scripts/vBilling.bin
	rm -f ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.bin
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling.bin ${FS_INSTALL_PATH}/scripts/vBilling.lua
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.bin
	ln -s ${VBILLING_HTML}/bin/centos/${ARCH}/vBilling_functions.bin ${FS_INSTALL_PATH}/scripts/vBilling_functions.lua
fi

# Now this one is tricky. We need to know the lcr_group_* tables created already and update the structure as required.
# This can only be done with some dirty hacks

mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "\
delimiter '//'
create procedure AddColumnUnlessExists(
IN dbName tinytext,
IN tableName tinytext,
IN fieldName tinytext,
IN fieldAfter tinytext,
IN fieldDef text)
begin
IF NOT EXISTS (
SELECT * FROM information_schema.COLUMNS
WHERE column_name=fieldName
and table_name=tableName
and table_schema=dbName
)
THEN
set @ddl=CONCAT('ALTER TABLE ',dbName,'.',tableName,
' ADD COLUMN ',fieldName,' ', fieldDef, ' ', 'AFTER ', fieldAfter);
prepare stmt from @ddl;
execute stmt;
END IF;
end;
//
delimiter ';'"

for lcr_table_name in $(mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "show tables;" | grep -i lcr_group)
do
# Update lcr_group_(id) schema for all LCR tables

mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "ALTER TABLE $lcr_table_name DROP COLUMN intrastate_rate"
mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "ALTER TABLE $lcr_table_name DROP COLUMN intralata_rate"
mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "ALTER TABLE $lcr_table_name MODIFY COLUMN country_id int(4) NOT NULL, MODIFY COLUMN buy_initblock int(4) NOT NULL, MODIFY COLUMN sell_initblock int(4) NOT NULL , MODIFY COLUMN lcr_profile int(3) NULL, MODIFY COLUMN quality int(5) NOT NULL, MODIFY COLUMN reliability int(5) NOT NULL;"
mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "\
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'sellblock_min_duration', 'cost_rate' ,'int(4) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'buyblock_min_duration', 'sellblock_min_duration' ,'int(4) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'remove_rate_prefix', 'sell_initblock' ,'int(15) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'remove_rate_suffix', 'remove_rate_prefix' ,'int(15) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'admin_rate_group', 'lrn' ,'varchar(50) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'admin_rate_id', 'admin_rate_group' ,'int(11) NULL DEFAULT 0');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'reseller_rate_group', 'admin_rate_id' ,'varchar(50) NULL');
call AddColumnUnlessExists(Database(), '${lcr_table_name}', 'reseller_rate_id', 'reseller_rate_group' ,'int(11) NULL DEFAULT 0');"
done
mysql -u${VBILLING_DB_USER} -p${VBILLING_DB_PASSWORD} ${VBILLING_DB} -e "drop procedure AddColumnUnlessExists;"

# Fix permission on php files
if [ -f /etc/debian_version ] ; then
	chown -R www-data:www-data ${VBILLING_HTML}
	chmod -R 777 ${VBILLING_HTML}/media/
	mkdir ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	chmod 777 ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
elif [ -f /etc/redhat-release ]; then
	chown -R apache:apache ${VBILLING_HTML}
	chmod -R 777 ${VBILLING_HTML}/media/
	mkdir ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
	chmod 777 ${VBILLING_HTML}/application/3rdparty/tcpdf/cache/
fi

# Restart FreeSWITCH. Do we really need it?
/etc/init.d/freeswitch restart

# We should be good here by now :) Notify the user
clear
echo "***"
echo "*** The install script has performed all operations. If everything went well, you should be able"
echo "*** to browse vBilling with your old username and password."
echo "***"
echo "*** If something went wrong, please visit our forum at http://forum.vbilling.org/ and post your issue"
echo "***"
echo "*** For a quick start howto, please visit the following URL"
echo "*** http://forum.vbilling.org/viewtopic.php?f=6&t=3"
echo "***"

elif [ $REPLY = "n" ]; then
	clear
	echo "***"
	echo "*** You have selected not to continue with the upgrade. Exiting now ..."
	echo "***"
	rm -rf ${TEMPDIR}
	exit 1
else
	clear
	echo "***"
	echo "*** Your input was not correct, upgrade aborted."
	echo "***"
	rm -rf ${TEMPDIR}
	exit 1
fi	# for $REPLY
fi	# for if on topline, start of main instance
rm -rf ${TEMPDIR}
