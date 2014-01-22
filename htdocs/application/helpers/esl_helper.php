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

function get_status()
{
	$CI = & get_instance();
	$fp = $CI->esl->event_socket_create($CI->esl->ESL_host, $CI->esl->ESL_port, $CI->esl->ESL_password);
	$cmd = "api status";
	$response = $CI->esl->event_socket_request($fp, $cmd);
	echo nl2br($response); 
	fclose($fp);
}

function get_connected_calls()
{
	// $CI =& get_instance();
	// $CI->config->item('base_url');
	// 
	// Find a better way to secure this URL
	// 
    /*$xmlFileData = file_get_contents("http://127.0.0.1/freeswitch/generate_xml");
	$xmlData = new SimpleXMLElement($xmlFileData);
	$total_connected_calls = (string)$xmlData['row_count'];
	echo $total_connected_calls;*/
	
	$CI = & get_instance();
	$fp = $CI->esl->event_socket_create($CI->esl->ESL_host, $CI->esl->ESL_port, $CI->esl->ESL_password);
	$cmd = "api show calls count";
	$response = $CI->esl->event_socket_request($fp, $cmd);
	echo nl2br($response);
	fclose($fp);
}
?>