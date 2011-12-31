<?php
/**
* @package FS_CURL
* @license BSD
* @author Raymond Chandler (intralanman) <intralanman@gmail.com>
* @version 1.1
* @contributor Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
* initial page hit in all curl requests
*/

$application_folder = '../application/';
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace("\\", "/", $application_folder));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.'/'))
	{
		exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
	}
	define('APPPATH', BASEPATH.$application_folder.'/');
}

/**
* define for the time that execution of the script started
*/
define('START_TIME', ereg_replace('^0\.([0-9]+) ([0-9]+)$', '\2.\1', microtime()));

/**
* Pre-Class initialization die function
* This function should be called on any
* critical error condition before the fs_curl
* class is successfully instantiated.
* @return void
*/

function file_not_found($no=false, $str=false, $file=false, $line=false) {
	if ($no == E_STRICT) {
		return;
	}
	header('Content-Type: text/xml');
	printf("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n");
	printf("<document type=\"freeswitch/xml\">\n");
	printf("  <section name=\"result\">\n");
	printf("    <result status=\"not found\"/>\n");
	printf("  </section>\n");
	if (!empty($no) && !empty($str) && !empty($file) &&!empty($line)) {
			printf("  <!-- ERROR: $no - ($str) on line $line of $file -->\n");
		}
		printf("</document>\n");
		exit();
	}
	/*
	TODO -- Fix error_reporting
	*/
error_reporting(E_ALL);
set_error_handler('file_not_found');

if (!class_exists('XMLWriter')) {
	trigger_error(
		"XMLWriter Class NOT Found... You Must install it before using this package"
		, E_USER_ERROR
		);
}

if (!(@include_once('fs_curl.php'))
|| !(@include_once( BASEPATH . 'config/constants.php'))) {
	trigger_error(
		'could not include fs_curl.php or constants.php', E_USER_ERROR
		);
}

if (!is_array($_REQUEST)) {
	trigger_error('$_REQUEST is not an array');
}

if (array_key_exists('cdr', $_REQUEST)) {
	$section = 'cdr';
} else {
	$section = $_REQUEST['section'];
}
$section_file = sprintf('fs_%s.php', $section);

/**
* this include will differ based on the section that's passed
*/
if (!(@include_once($section_file))) {
	trigger_error("unable to include $section_file");
}

switch ($section) {
	case 'configuration':
	if (!array_key_exists('key_value', $_REQUEST)) {
		trigger_error('key_value does not exist in $_REQUEST');
	}
	$config = $_REQUEST['key_value'];
	$processor = sprintf('configuration/%s.php', $config);
	$class = str_replace('.', '_', $config);
	if (!(@include_once($processor))) {
		trigger_error("unable to include $processor");
	}
	$conf = new $class;
	$conf -> comment("class name is $class");
	break;
	case 'directory':
	$conf = new fs_directory();
	break;
	case 'cdr':
	$conf = new fs_cdr();
	break;
}
// $conf -> debug('---- Start _REQUEST ----');
// $conf -> debug($_REQUEST);
// $conf -> debug('---- End _REQUEST ----');
$conf -> main();
$conf -> output_xml();
?>
