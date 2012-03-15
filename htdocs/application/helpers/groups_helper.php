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
function group_any_cell($rate_group_id, $col_name)
{
	$CI = & get_instance();
	$sql = "SELECT * FROM groups WHERE id = '".$rate_group_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();
	return $row->$col_name;
}

//show group select box
function show_group_select_box($rate_group_id = '')
{
	$CI = & get_instance();
	$CI->load->model('groups_model');
	$result	=	$CI->groups_model->group_select_box($rate_group_id);
	return $result;
}

//show group select box with options valid or invalid
function show_group_select_box_valid_invalid($rate_group_id = '')
{
	$CI = & get_instance();
	$CI->load->model('groups_model');
	$result	=	$CI->groups_model->show_group_select_box_valid_invalid($rate_group_id);
	return $result;
}
?>