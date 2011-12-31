#!/bin/bash
# 
#  install.sh
#  scripts
#  
#  FreeSWITCH and vBilling install script v1.1
#  Copyright 2011-12 Digital Linx. All rights reserved.
#

# Define some variables
VBILLING_REPO=git://github.com/digitallinx/vBilling.git
TEMPDIR=$(/bin/mktemp -d)
FS_GIT_REPO=git://git.freeswitch.org/freeswitch.git
FS_INSTALL_PATH=/home/vBilling/freeswitch
FS_BASE_PATH=/usr/local/src/
FS_USER=freeswitch
CURRENT_PATH=$PWD

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
	read -n 1 -p "*** Press any key to continue ..."
	clear

	# Identify Linux Distribution
	if [ -f /etc/debian_version ] ; then
		DIST="DEBIAN"
	elif [ -f /etc/redhat-release ] ; then
		DIST="CENTOS"
	else
		echo ""
		echo "*** This Installer should be run only on CentOS 6.x or Debian based system"
		echo ""
		exit 1
	fi

	clear
	echo "*** Setting up Prerequisites and Dependencies"

	case $DIST in
		'DEBIAN')
		export DEBIAN_FRONTEND=noninteractive
        apt-get -y update
        apt-get -y install autoconf automake autotools-dev binutils bison build-essential cpp curl flex g++ gcc git-core libapache2-mod-php5 libaudiofile-dev libc6-dev libdb-dev libexpat1 libgdbm-dev libgnutls-dev libmcrypt-dev libncurses5-dev libnewt-dev libpcre3 libpopt-dev libsctp-dev libsqlite3-dev libtiff4 libtiff4-dev libtool libx11-dev libxml2 libxml2-dev libjpeg-dev libssl-dev lksctp-tools lua5.1 lynx m4 make mcrypt mysql-server ncftp nmap openssl php5 php5-dev php5-mhash php5-gd php5-mysql php5-mcrypt php-apc pkg-config sox sqlite3 ssl-cert ssl-cert unixodbc-dev unzip zip zlib1g-dev zlib1g-dev sox
		;;
		'CENTOS')
		yum -y update
		VERS=$(cat /etc/redhat-release | cut -d ' ' -f3 | cut -d '.' -f1)
        COMMON_PKGS="autoconf automake bzip2 cpio curl curl-devel curl-devel expat-devel fileutils git gcc-c++ gettext-devel gnutls-devel httpd libjpeg-devel libogg-devel libtiff-devel libtool libvorbis-devel lua-devel lua-static make mysql-server ncurses-devel nmap openssl openssl-devel openssl-devel patch php php-bcmath php-cli php-common php-gd php-mbstring php-mysql php-pdo php-xml unixODBC unixODBC-devel unzip wget zip zlib zlib-devel bison sox"
		if [ "$VERS" = "6" ]
			then
			yum -y install $COMMON_PKGS
		else
			echo "CentOS version < 6 is not supported. Exiting ..."
			exit 1
		fi
		;;
	esac

# Install FreeSWITCH
cd "$FS_BASE_PATH"
git clone "$FS_GIT_REPO"
cd "$FS_BASE_PATH"/freeswitch
sh bootstrap.sh && ./configure --prefix="$FS_INSTALL_PATH"
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
#applications/mod_lcr
#applications/mod_nibblebill
#applications/mod_spandsp
#applications/mod_distributor
applications/mod_expr
applications/mod_esf
codecs/mod_g723_1
codecs/mod_amr
codecs/mod_g729
#codecs/mod_com_g729
codecs/mod_ilbc
codecs/mod_speex
dialplans/mod_dialplan_xml
endpoints/mod_sofia
#endpoints/mod_loopback
event_handlers/mod_event_socket
#event_handlers/mod_cdr_csv
formats/mod_native_file
formats/mod_sndfile
formats/mod_local_stream
formats/mod_tone_stream
languages/mod_lua
xml_int/mod_xml_curl
xml_int/mod_xml_cdr
timers/mod_timerfd
EOF

make && make install

# We don't need default config files. We use our own, mainly for XML_CURL
rm -rf "$FS_INSTALL_PATH"/conf
mkdir -p "$FS_INSTALL_PATH"/conf

# Setup freeswitch.xml
cat << 'EOF' > "$FS_INSTALL_PATH"/conf/freeswitch.xml
<?xml version="1.0"?>
<document type="freeswitch/xml">
	<X-PRE-PROCESS cmd="set" data="domain=$${local_ip_v4}"/>
	<X-PRE-PROCESS cmd="set" data="domain_name=$${domain}"/>
	<X-PRE-PROCESS cmd="set" data="codecs=PCMU,PCMA,GSM,G723,G729"/>
	<X-PRE-PROCESS cmd="set" data="console_loglevel=info"/>

	<!--
	vBilling Custom Defines. START
	-->
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_curl_url=http://localhost/xmlcurl/index.php"/>
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_curl_binding=configuration|directory"/>
	<X-PRE-PROCESS cmd="set" data="vBilling_xml_cdr_url=http://localhost/xmlcurl/index.php"/>
	<X-PRE-PROCESS cmd="set" data="event_socket_listen_ip=127.0.0.1"/>
	<X-PRE-PROCESS cmd="set" data="event_socket_listen_port=8021"/>
	<X-PRE-PROCESS cmd="set" data="event_socket_password=ClueCon"/>
	<!--
	vBilling Custom Defines. END
	-->

	<section name="configuration" description="Various Configuration">
		<configuration name="modules.conf" description="Modules">
			<modules>
				<!-- <load module="mod_console"/>
				<load module="mod_logfile"/> -->
				<load module="mod_xml_curl"/>
			</modules>
		</configuration>

		<configuration name="switch.conf" description="Modules">
			<default-ptimes>
				<!-- set this to override the 20ms assumption of various codecs in the sdp with no ptime defined -->
				<!--<codec name="G729" ptime="40"/>-->
			</default-ptimes>
			<settings>
				<param name="max-sessions" value="200"/>
				<param name="sessions-per-second" value="30"/>
				<param name="switchname" value="vBilling"/>

				<!--RTP port range -->
				<param name="rtp-start-port" value="16000"/>-->
				<param name="rtp-end-port" value="42000"/>
				<param name="dump-cores" value="no"/>
				<param name="rtp-enable-zrtp" value="true"/>
			</settings>
			<!--Any variables defined here will be available in every channel, in the dialplan etc -->
		</configuration>

		<configuration name="xml_curl.conf" description="cURL XML Gateway">
			<bindings>
				<binding name="production">
					<param name="gateway-url" value="$${vBilling_xml_curl_url}" bindings="$${vBilling_xml_curl_binding}"/>
				</binding>
			</bindings>
		</configuration>
	</section>

	<section name="dialplan" description="Regex/XML Dialplan">
		<context name="default">
			<extension name="vBilling">
				<condition field="destination_number" expression="^(\d+)$">
					<action application="lua" data="/home/vBilling/freeswitch/scripts/vBilling.lua"/>
					<action application="bridge" data="${vBilling_bridge_data}"/>
				</condition>
			</extension>
		</context>
	</section>
</document>
EOF

useradd -c "FreeSwitch Voice Switching System" -d "$FS_INSTALL_PATH" -M -s /bin/false "$FS_USER"

# We lock freeswitch user password to avoid any security issues?
passwd -l "$FS_USER"
chown -R "$FS_USER"."$FS_USER" "$FS_INSTALL_PATH"

# Just making sure we have good and prefered binaries in our path :)
cat << 'EOF' >> /etc/profile
#
# START -- Path added by vBilling for FreeSWITCH binaries
export PATH=/home/vBilling/freeswitch/bin:$PATH
# E N D -- Path added by vBilling for FreeSWITCH binaries
#
EOF
source /etc/profile

# FreeSWITCH install and config is done. Let's move forward
# Install FreeSWITCH init scripts
case $DIST in
	"DEBIAN")
####################################
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

# PATH should only include /usr/* if it runs after the mountnfs.sh script
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
####################################
	chmod 755 /etc/init.d/freeswitch
	echo "FREESWITCH_ENABLED=true" > /etc/default/freeswitch
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
	chkconfig --add freeswitch
	;;
esac

cd "$CURRENT_PATH"
# Generate random password (for MySQL)
genpasswd() {
       	length=16
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
MYSQL_PASSWORD=$(genpasswd)
mysql -uroot -e "UPDATE mysql.user SET password=PASSWORD('${MYSQL_PASSWORD}') WHERE user='root'; FLUSH PRIVILEGES;"

# Save MySQL root password to a text file in /root
echo "MySQL Password set to '${MYSQL_PASSWORD}'. Remember to delete ~/.mysql.passwd" | tee ~/.mysql.passwd
chmod 400 ~/.mysql.passwd
read -n 1 -p "*** Press any key to continue ..."

# Pre Install Complete, let's move forward

# Enable required apache modules
if [ -f /etc/debian_version ] ; then
	/usr/sbin/a2enmod php5 rewrite
	/etc/init.d/apache2 restart
else [ -f /etc/redhat-release ]
	sed -i "s#    AllowOverride None#    AllowOverride All#g" /etc/httpd/conf/httpd.conf
	/etc/init.d/httpd restart
fi

elif [ $REPLY = "n" ]; then
	clear
	echo ""
	echo "*** Installation of split instance is not supported through this install script"
	echo "*** Please contact \"support@vbilling.org\" in order to have a distributed setup"
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
# We remove all local source, in order to download fresh files against updates
rm -rf $TEMPDIR

