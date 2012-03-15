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

//show group select box
function carrier_any_cell($carrier_id, $col_name)
{
	$CI = & get_instance();
	$sql = "SELECT * FROM carriers WHERE id = '".$carrier_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();
	return $row->$col_name;
}

//show carrier select box
function show_carrier_select_box($carrier_id = '')
{
	$CI = & get_instance();
	$CI->load->model('carriers_model');
	$result	=	$CI->carriers_model->carrier_select_box($carrier_id);
	return $result;
}

//show carrier select box with valid invalid check
function show_carrier_select_box_valid_invalid($carrier_id = '')
{
	$CI = & get_instance();
	$CI->load->model('carriers_model');
	$result	=	$CI->carriers_model->show_carrier_select_box_valid_invalid($carrier_id);
	return $result;
}

function all_gateways_with_use_count($gateway_name = '', $sofia_id = '')
{
	$CI = & get_instance();
	$CI->load->model('carriers_model');
	$result	=	$CI->carriers_model->all_gateways_with_use_count($gateway_name, $sofia_id);
	return $result;
}

function carrier_exists($carrier_id)
{
	$CI = & get_instance();
	$sql = "SELECT * FROM carriers WHERE id = '".$carrier_id."' ";
	$query = $CI->db->query($sql);
	return $query->num_rows();
}
?>