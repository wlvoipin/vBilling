<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Defines for xmlcurl
|--------------------------------------------------------------------------
*/
/**
 * Defines the default dsn for the FS_PDO class
 */
define('DEFAULT_DSN', 'mysql:dbname=vBilling;host=127.0.0.1');

/**
 * Defines the default dsn login for the PDO class
 */
define('DEFAULT_DSN_LOGIN', 'MYSQL_USERNAME');

/**
* Defines the default dsn password for the PDOclass
*/
define('DEFAULT_DSN_PASSWORD', 'MYSQL_PASSWORD');

/**
 * Generic return success
 */
define('FS_CURL_SUCCESS', 0);

/**
 * Generic return success
 */
define('FS_SQL_SUCCESS', '00000');

/**
 * Generic return warning
 */
define('FS_CURL_WARNING', 1);

/**
 * Generic return critical
 */
define('FS_CURL_CRITICAL', 2);

/**
 * determines how the error handler handles warnings
 */
define('RETURN_ON_WARN', true);

/**
 * Determines whether or not users should be domain specific
 * If GLOBAL_USERS is true, user info will be returned for whatever
 * domain is passed.....
 * NOTE: using a1 hashes will NOT work with this setting
 */
define('GLOBAL_USERS', false);

/**
 * Define debug level... should not be used in production for performance reasons
 */
define('FS_CURL_DEBUG', 0);

/**
 * define how debugging should be done (depends on FS_CURL_DEBUG)
 * 0 syslog
 * 1 xml comment
 * 2 file (named in FS_DEBUG_FILE), take care when using this option as there's currently nothing to watch the file's size
 */
define('FS_DEBUG_TYPE', 1);

/**
 * File to use for debugging to file
 */
define('FS_DEBUG_FILE', '/tmp/FS_xml_curl.debug');

/* End of file constants.php */
/* Location: ./application/config/constants.php */