<?php 
/*
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * The Original Code is "vBilling - VoIP Billing and Routing Platform"
 * 
 * The Initial Developer of the Original Code is 
 * Digital Linx [<] info at digitallinx.com [>]
 * Portions created by Initial Developer (Digital Linx) are Copyright (C) 2011
 * Initial Developer (Digital Linx). All Rights Reserved.
 *
 * Contributor(s)
 * "Digital Linx - <vbilling at digitallinx.com>"
 *
 * vBilling - VoIP Billing and Routing Platform
 * version 0.1.3
 *
 */

# override the default TCPDF config file
if(!defined('K_TCPDF_EXTERNAL_CONFIG')) {	
	define('K_TCPDF_EXTERNAL_CONFIG', TRUE);
}

# include TCPDF
require(APPPATH.'config/tcpdf'.EXT);
require_once($tcpdf['base_directory'].'/tcpdf.php');

/************************************************************
* TCPDF - CodeIgniter Integration
* Library file
* ----------------------------------------------------------
* @author Jonathon Hill http://jonathonhill.net
* @version 1.0
* @package tcpdf_ci
***********************************************************/
class pdf extends TCPDF {


	/**
	* TCPDF system constants that map to settings in our config file
	*
	* @var array
	* @access private
	*/
private $cfg_constant_map = array(
	'K_PATH_MAIN'	=> 'base_directory',
	'K_PATH_URL'	=> 'base_url',
	'K_PATH_FONTS'	=> 'fonts_directory',
	'K_PATH_CACHE'	=> 'cache_directory',
	'K_PATH_IMAGES'	=> 'image_directory',
	'K_BLANK_IMAGE' => 'blank_image',
	'K_SMALL_RATIO'	=> 'small_font_ratio',
	);


/**
* Settings from our APPPATH/config/tcpdf.php file
*
* @var array
* @access private
*/
private $_config = array();


/**
* Initialize and configure TCPDF with the settings in our config file
*
*/
function __construct() {

	# load the config file
	require(APPPATH.'config/tcpdf'.EXT);
	$this->_config = $tcpdf;
	unset($tcpdf);



	# set the TCPDF system constants
	foreach($this->cfg_constant_map as $const => $cfgkey) {
		if(!defined($const)) {
			define($const, $this->_config[$cfgkey]);
			#echo sprintf("Defining: %s = %s\n<br />", $const, $this->_config[$cfgkey]);
		}
	}

	# initialize TCPDF		
	parent::__construct(
		$this->_config['page_orientation'], 
		$this->_config['page_unit'], 
		$this->_config['page_format'], 
		$this->_config['unicode'], 
		$this->_config['encoding'], 
		$this->_config['enable_disk_cache']
		);


	# language settings
	if(is_file($this->_config['language_file'])) {
		include($this->_config['language_file']);
		$this->setLanguageArray($l);
		unset($l);
	}

	# margin settings
	$this->SetMargins($this->_config['margin_left'], $this->_config['margin_top'], $this->_config['margin_right']);

	# header settings
	$this->print_header = $this->_config['header_on'];
	#$this->print_header = FALSE; 
	$this->setHeaderFont(array($this->_config['header_font'], '', $this->_config['header_font_size']));
	$this->setHeaderMargin($this->_config['header_margin']);
	$this->SetHeaderData(
		$this->_config['header_logo'], 
		$this->_config['header_logo_width'], 
		$this->_config['header_title'], 
		$this->_config['header_string']
		);

	# footer settings
	$this->print_footer = $this->_config['footer_on'];
	$this->setFooterFont(array($this->_config['footer_font'], '', $this->_config['footer_font_size']));
	$this->setFooterMargin($this->_config['footer_margin']);

	# page break
	$this->SetAutoPageBreak($this->_config['page_break_auto'], $this->_config['footer_margin']);

	# cell settings
	$this->cMargin = $this->_config['cell_padding'];
	$this->setCellHeightRatio($this->_config['cell_height_ratio']);

	# document properties
	$this->author = $this->_config['author'];
	$this->creator = $this->_config['creator'];

	# font settings
	#$this->SetFont($this->_config['page_font'], '', $this->_config['page_font_size']);

	# image settings
	$this->imgscale = $this->_config['image_scale'];

}
}