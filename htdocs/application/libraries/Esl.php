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

class Esl{
	public $ESL_password = "ClueCon";
	public $ESL_port = "8021";
	public $ESL_host = "localhost";

	function event_socket_create($ESL_host, $ESL_port, $ESL_password) {
		$fp = fsockopen($ESL_host, $ESL_port, $errno, $errdesc) 
			or die("Connection to $ESL_host failed");
		socket_set_blocking($fp,false);

		if ($fp) {
			while (!feof($fp)) {
				$buffer = fgets($fp, 1024);
				usleep(100); //allow time for reponse
				if (trim($buffer) == "Content-Type: auth/request") {
					fputs($fp, "auth $ESL_password\n\n");
					break;
				}
			}
			return $fp;
		}
		else {
			return false;
		}           
	}

	function event_socket_request($fp, $cmd) {
		if ($fp) {    
			fputs($fp, $cmd."\n\n");    
			usleep(200); //allow time for reponse
			$response = "";
			$i = 0;
			$contentlength = 0;
			while (!feof($fp)) {
				$buffer = fgets($fp, 4096);
				if ($contentlength > 0) {
					$response .= $buffer;
				}

				if ($contentlength == 0) { //if contentlenght is already don't process again
				if (strlen(trim($buffer)) > 0) { //run only if buffer has content
					$temparray = explode(":", trim($buffer));
					if ($temparray[0] == "Content-Length") {
						$contentlength = trim($temparray[1]);
					}
				}
			}
			usleep(100); //allow time for reponse
			//optional because of script timeout //don't let while loop become endless
			if ($i > 10000) { break; } 

			if ($contentlength > 0) { //is contentlength set
				//stop reading if all content has been read.
				if (strlen($response) >= $contentlength) {  
					break;
				}
			}
			$i++;
		}
		return $response;
	}
	else {
		echo "no handle";
	}
}
}
?>
