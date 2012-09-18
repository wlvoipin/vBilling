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

class Groups_model extends CI_Model {

	// list all customers
	
	function getAllLocalizationGroups(){
		$data = array();
		$sql = "SELECT * FROM localization_groups ORDER BY name ASC";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$data[] = $row;
			}			
		}
		$query->free_result();
		return $data;
	}
	
	function get_all_localization_groups($num, $offset, $filter_groups, $filter_group_type, $filter_sort)
	{
		if($offset == ''){$offset='0';}

		$order_by = "";
		if($filter_sort == 'name_asc')
		{
			$order_by = "ORDER BY name ASC";
		}
		else if($filter_sort == 'name_dec')
		{
			$order_by = "ORDER BY name DESC";
		}
		else
		{
			$order_by = "ORDER BY id DESC";
		}

		$where = '';
		$where .= "WHERE 1 ";

		if($filter_groups != '')
		{
			if(is_numeric($filter_groups))
			{
				$where .= 'AND id = '.$this->db->escape($filter_groups).' ';
			}
		}

		if($filter_group_type != '')
		{
			if(is_numeric($filter_group_type))
			{
				$where .= 'AND enabled = '.$filter_group_type.' ';
			}
		}

		$sql = "SELECT * FROM localization_groups ".$where." ".$order_by." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_localization_groups_count($filter_groups, $filter_group_type)
	{
		$where = '';
		$where .= "WHERE 1 ";

		if($filter_groups != '')
		{
			if(is_numeric($filter_groups))
			{
				$where .= 'AND id = '.$this->db->escape($filter_groups).' ';
			}
		}

		if($filter_group_type != '')
		{
			if(is_numeric($filter_group_type))
			{
				$where .= 'AND enabled = '.$filter_group_type.' ';
			}
		}

		$sql = "SELECT * FROM localization_groups ".$where." ";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}
	
	function get_single_localization_group($localization_group_id)
	{
		$sql = "SELECT * FROM localization_groups WHERE id='".$localization_group_id."'";
		$query = $this->db->query($sql);
		return $query;
	}	
	
	//***************CARRIER GATEWAYS ********************************
	function get_localization_rules($localization_group_id)
	{
		$sql = "SELECT * FROM localization_rules WHERE localization_id = '".$localization_group_id."' ORDER BY name ASC";
		$query = $this->db->query($sql);
		return $query;
	}	
	
	//new group
	function insert_new_localization_group($name)
	{
		$sql = "INSERT INTO localization_groups (name, enabled) VALUES ('".$name."', '1') ";
		$query = $this->db->query($sql);
		return $this->db->insert_id();
	}	
	
	 
	function enable_disable_localization_group($data)
	{
		$sql = "UPDATE localization_groups SET enabled = '".$data['status']."' WHERE id = '".$data['localization_id']."'";
		return $query = $this->db->query($sql);
	}	
	
	function enable_disable_localization_rules($data)
	{
		$sql = "UPDATE localization_rules SET enabled = '".$data['status']."' WHERE localization_id = '".$data['localization_id']."'";
		return $query = $this->db->query($sql);
	}	
	
	function insert_localization_rules($localization_id, $name,$cut, $add, $enabled)
	{
		$sql = "INSERT INTO localization_rules (localization_id, name,enabled, lcut, ladd) VALUES ('".$localization_id."', '".$name."', '".$enabled."', '".$cut."', '".$add."')";
		return $query = $this->db->query($sql);		
		 
	}
	
	function update_localization_group($groupname, $localization_id)
	{
		$sql = "UPDATE localization_groups SET name='".$groupname."' WHERE id='".$localization_id."'";
		return $query = $this->db->query($sql);		
	}
	
	function delete_localization_rules($localization_id)
	{
		$sql = "DELETE FROM localization_rules WHERE localization_id = '".$localization_id."' ";
		$query = $this->db->query($sql);		 
		return $query;
	}	
	
	//populate localization group select box
	function localization_group_select_box($group_id)
	{
		$sql = "SELECT * FROM localization_groups ORDER BY name ASC ";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Localization Group</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($rate_group_id == $row->id)
				{
					$data .= '<option value="'.$row->id.'" selected>'.$row->name.'</option>';
				}
				else
				{
					$data .= '<option value="'.$row->id.'">'.$row->name.'</option>';
				}
			}
		}

		return $data;
	}	
	
	function delete_localization_group($localization_group_id)
	{
		//delete the group
		$sql = "DELETE FROM localization_groups WHERE id = '".$localization_group_id."' ";
		return $query = $this->db->query($sql);	
	}	

	// list all customers
	function get_all_groups($num, $offset, $filter_groups, $filter_group_type, $filter_sort)
	{
		if($offset == ''){$offset='0';}

		$order_by = "";
		if($filter_sort == 'name_asc')
		{
			$order_by = "ORDER BY group_name ASC";
		}
		else if($filter_sort == 'name_dec')
		{
			$order_by = "ORDER BY group_name DESC";
		}
		else
		{
			$order_by = "ORDER BY id DESC";
		}

		$where = '';
		$where .= "WHERE created_by = '0' ";

		if($filter_groups != '')
		{
			if(is_numeric($filter_groups))
			{
				$where .= 'AND id = '.$this->db->escape($filter_groups).' ';
			}
		}

		if($filter_group_type != '')
		{
			if(is_numeric($filter_group_type))
			{
				$where .= 'AND enabled = '.$filter_group_type.' ';
			}
		}

		$sql = "SELECT * FROM groups ".$where." ".$order_by." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_groups_count($filter_groups, $filter_group_type)
	{
		$where = '';
		$where .= "WHERE created_by = '0' ";

		if($filter_groups != '')
		{
			if(is_numeric($filter_groups))
			{
				$where .= 'AND id = '.$this->db->escape($filter_groups).' ';
			}
		}

		if($filter_group_type != '')
		{
			if(is_numeric($filter_group_type))
			{
				$where .= 'AND enabled = '.$filter_group_type.' ';
			}
		}

		$sql = "SELECT * FROM groups ".$where." ";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}

	//single id data 
	function get_single_group($rate_group_id)
	{
		$sql = "SELECT * FROM groups WHERE id='".$rate_group_id."'";
		$query = $this->db->query($sql);
		return $query;
	}

	//any cell 
	function group_any_cell($rate_group_id, $col_name)
	{
		$sql = "SELECT * FROM groups WHERE id = '".$rate_group_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();

		return $row->$col_name;
	}

	//get group rates 
	function group_rates($num, $offset, $group_table_name)
	{
		if($offset == ''){$offset='0';}

		$sql = "SELECT * FROM ".$group_table_name." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function group_rates_count($group_table_name)
	{
		$sql = "SELECT * FROM ".$group_table_name." ";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}

	//edit group 
	function edit_group_db($data)
	{
		$sql = "UPDATE groups SET group_name='".$data['groupname']."' WHERE id='".$data['rate_group_id']."'";
		$query = $this->db->query($sql);
	}

	//enable disable carrier
	function enable_disable_group($data)
	{
		$sql = "UPDATE groups SET enabled = '".$data['status']."' WHERE id = '".$data['rate_group_id']."'";
		$query = $this->db->query($sql);
		$sql = "SELECT group_rate_table	from groups WHERE id = '".$data['rate_group_id']."'";
		$query = $this->db->query($sql);
		foreach($query->result() as $row)
		{
			$sql = "UPDATE $row->group_rate_table SET enabled = '".$data['status']."'";
			$query = $this->db->query($sql);
		}
	}

	//new group
	function insert_new_rate_group($name)
	{
		$sql = "INSERT INTO groups (group_name, enabled) VALUES ('".$name."', '1') ";
		$query = $this->db->query($sql);
		return $this->db->insert_id();
	}

	//create new customer rate table
	function create_new_rate_group_rate_tbl($insert_id)
	{
		$sql = "CREATE TABLE IF NOT EXISTS `lcr_group_".$insert_id."` (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`digits` bigint(20) NOT NULL,
			`country_id` int(4) NOT NULL,
			`sell_rate` float(11,4) unsigned NOT NULL,
			`cost_rate` float(11,4) unsigned NOT NULL,
			`sellblock_min_duration` int(4) NOT NULL,
			`buyblock_min_duration` int(4) NOT NULL,
			`buy_initblock` int(4) NOT NULL,
			`sell_initblock` int(4) NOT NULL,
			`remove_rate_prefix` int(15) NOT NULL,
			`remove_rate_suffix` int(15) NOT NULL,
			`carrier_id` int(11) NOT NULL,
			`lead_strip` int(11) NOT NULL,
			`trail_strip` int(11) NOT NULL,
			`prefix` varchar(20) NOT NULL,
			`suffix` varchar(20) NOT NULL,
			`lcr_profile` int(3) DEFAULT NULL,
			`date_start` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			`date_end` datetime NOT NULL DEFAULT '2030-12-31 00:00:00',
			`quality` int(5) NOT NULL,
			`reliability` int(5) NOT NULL,
			`enabled` tinyint(1) NOT NULL DEFAULT '1',
			`lrn` tinyint(1) NOT NULL DEFAULT '0',
			`admin_rate_group` varchar(50) DEFAULT NULL,
			`admin_rate_id` int(11) NOT NULL DEFAULT '0',
			`reseller_rate_group` varchar(50) DEFAULT NULL,
			`reseller_rate_id` int(11) NOT NULL DEFAULT '0',
		  KEY `id` (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1;";
		$query = $this->db->query($sql);

		$sql2 = "UPDATE groups SET group_rate_table = 'lcr_group_".$insert_id."' WHERE id = '".$insert_id."'";
		$query2 = $this->db->query($sql2);
	}

	//populate group select box
	function group_select_box($rate_group_id = '')
	{
		$sql = "SELECT * FROM groups WHERE created_by = '0' ";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Rate Group</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($rate_group_id == $row->id)
				{
					$data .= '<option value="'.$row->id.'" selected>'.$row->group_name.'</option>';
				}
				else
				{
					$data .= '<option value="'.$row->id.'">'.$row->group_name.'</option>';
				}
			}
		}

		return $data;
	}

	//show group select box with valid or invalid options
	function show_group_select_box_valid_invalid($rate_group_id)
	{
		$sql = "SELECT * FROM groups WHERE created_by = '0'";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Rate Group</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($rate_group_id == $row->id)
				{
					$data .= '<option value="'.$row->id.'" selected>'.$row->group_name.' ('.$this->group_valid_invalid($row->group_rate_table).')</option>';
				}
				else
				{
					$data .= '<option value="'.$row->id.'">'.$row->group_name.' ('.$this->group_valid_invalid($row->group_rate_table).')</option>';
				}
			}
		}

		return $data;
	}

	//group valid or invalid 
	function group_valid_invalid($group_tbl_name)
	{
		$invalid = 0;
		$invalid_txt = '';

		$sql = "SELECT * FROM ".$group_tbl_name." LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) //if rates are defined for the group
		{
		/*
		//return "VALID";
		foreach($query->result() as $row)
		{
		$carrier_id = $row->carrier_id;

//check if the carrier exists 
$sql2 = "SELECT * FROM carriers WHERE id = '".$carrier_id."' ";
$query2 = $this->db->query($sql2);

if($query2->num_rows() > 0) //yes carrier is found
{
//check if the carrier gateways exists 
$sql3 = "SELECT * FROM carrier_gateway WHERE carrier_id = '".$carrier_id."' ";
$query3 = $this->db->query($sql3);

if($query3->num_rows() > 0) //if carrier has gateways
{
foreach($query3->result() as $carrierRow)
{
$gateway_name   = $carrierRow->gateway_name;
$sofia_id       = $carrierRow->prefix_sofia_id;

$sql4 = "SELECT * FROM sofia_gateways WHERE sofia_id = '".$sofia_id."' && gateway_name = '".$gateway_name."' ";
$query4 = $this->db->query($sql4);

if($query4->num_rows() == 0) //gateway found
{
$invalid = 1;
$invalid_txt = 'Gateway Missing';
break;
}
}
}
else //if carriers does not have gateways 
{
$invalid = 1;
$invalid_txt = 'Gateways Not defined';
break;
}
}
else //carrier not found might have been deleted
{
$invalid = 1;
$invalid_txt = 'Carrier Not Found';
break;
}

}
*/

}
else //if rates are not defined for the group
{
	$invalid = 1;
	$invalid_txt = 'Rates Not Defined';
}

if($invalid == 0)
{
	return "VALID";
}
else
{
	return "IN-VALID -- ".$invalid_txt."";
}
}

function check_group_in_use($rate_group_id)
{
	$sql = "SELECT * FROM customers WHERE customer_rate_group = '".$rate_group_id."' ";
	$query = $this->db->query($sql);
	return $query;
}

function update_localization_group_sip($localization_id)
	{
		$sql = "UPDATE directory SET localization_id='0' WHERE localization_id='".$localization_id."'";
		return $query = $this->db->query($sql);		
	}
	
function update_localization_group_acl($localization_id)
	{
		$sql = "UPDATE acl_nodes SET localization_id='0' WHERE localization_id='".$localization_id."'";
		return $query = $this->db->query($sql);		
	}
	

function check_localization_group_in_use_by_sip($localization_id )
{
	$sql = "SELECT * FROM directory WHERE localization_id  = '".$localization_id ."' ";
	$query = $this->db->query($sql);
	if($query->num_rows()>0){
		return TRUE;
	}	
	return FALSE;
}


function check_localization_group_in_use_by_acl($localization_id )
{
	$sql = "SELECT * FROM acl_nodes WHERE localization_id  = '".$localization_id ."' ";
	$query = $this->db->query($sql);
	if($query->num_rows()>0){
		return TRUE;
	}	
	return FALSE;
}

function delete_group($rate_group_id)
{
	$group_rate_table = $this->group_any_cell($rate_group_id, 'group_rate_table');

	//delete the group
	$sql = "DELETE FROM groups WHERE id = '".$rate_group_id."' ";
	$query = $this->db->query($sql);

	//delete the group rate table
	$sql2 = "DROP TABLE ".$group_rate_table."";
	$query2 = $this->db->query($sql2);

	//remove from customer table 
	$sql3 = "UPDATE customers SET customer_rate_group = '0' WHERE customer_rate_group = '".$rate_group_id."' ";
	$query3 = $this->db->query($sql3);
}

//******************************* RATES FUNCTIONS ********************************
//insert new rate 
function insert_new_rate($data, $group_rate_table_name)
{
	$sql = "INSERT INTO ".$group_rate_table_name."(digits, country_id, sell_rate, cost_rate, sellblock_min_duration, buyblock_min_duration, buy_initblock, sell_initblock, remove_rate_prefix, remove_rate_suffix, carrier_id, lead_strip, trail_strip, prefix, suffix, lcr_profile, date_start, date_end, quality, reliability, enabled, lrn) VALUES ('".$data['digits']."', '".$data['country']."', '".$data['rate']."', '".$data['costrate']."', '".$data['sellblock_min_duration']."', '".$data['buyblock_min_duration']."', '".$data['buyblock']."', '".$data['sellblock']."', '".$data['remove_rate_prefix']."', '".$data['remove_rate_suffix']."', '".$data['carrier']."', '".$data['leadstrip']."', '".$data['trailstrip']."', '".$data['prefix']."', '".$data['suffix']."', '".$data['profile']."', '".$data['startdate']."', '".$data['enddate']."', '".$data['quality']."', '".$data['reliability']."', '1', '".$data['lrn']."')";
	$query = $this->db->query($sql);
}

function enable_disable_rate($data, $group_rate_table_name)
{
	$sql = "UPDATE ".$group_rate_table_name." SET enabled = '".$data['status']."' WHERE id = '".$data['rate_id']."'";
	$query = $this->db->query($sql);
}

function get_single_rate($rate_id, $group_rate_table_name)
{
	$sql = "SELECT * FROM ".$group_rate_table_name." WHERE id='".$rate_id."'";
	$query = $this->db->query($sql);
	return $query;
}

//update rate
function edit_rate_db($data)
{
	$sql = "UPDATE ".$data['group_rate_table_name']." SET digits='".$data['digits']."', country_id='".$data['country']."', sell_rate='".$data['rate']."', cost_rate='".$data['costrate']."', sellblock_min_duration='".$data['sellblock_min_duration']."', buyblock_min_duration='".$data['buyblock_min_duration']."', buy_initblock='".$data['buyblock']."', sell_initblock='".$data['sellblock']."', remove_rate_prefix='".$data['remove_rate_prefix']."', remove_rate_suffix='".$data['remove_rate_suffix']."', carrier_id='".$data['carrier']."', lead_strip='".$data['leadstrip']."', trail_strip='".$data['trailstrip']."', prefix='".$data['prefix']."', suffix='".$data['suffix']."', lcr_profile='".$data['profile']."', date_start='".$data['startdate']."', date_end='".$data['enddate']."', quality='".$data['quality']."', reliability='".$data['reliability']."', lrn='".$data['lrn']."' WHERE id='".$data['rate_id']."'";
	$query = $this->db->query($sql);
}

function delete_group_rate($rate_id, $group_rate_table_name)
{
	$sql = "DELETE FROM  ".$group_rate_table_name." WHERE id = '".$rate_id."'";
	$query = $this->db->query($sql);
}

function check_rate_duplicate($digits, $carrier, $group_rate_table_name)
{
	$sql = "SELECT * FROM ".$group_rate_table_name." WHERE digits = '".$digits."' && carrier_id = '".$carrier."' ";
	$query = $this->db->query($sql);
	return $query;
}

/*******************LIST RATES FUNCTIONS **************************/
function get_all_rates($num, $offset, $filter_groups, $filter_carriers, $filter_country, $filter_destination, $filter_sort, $destination_advance_filter)
{
	if($offset == ''){$offset='0';}

	$order_by = "";
	if($filter_sort == 'date_asc')
	{
		$order_by = "ORDER BY created_time ASC";
	}
	else if($filter_sort == 'date_dec')
	{
		$order_by = "ORDER BY created_time DESC";
	}
	else if($filter_sort == 'billduration_asc')
	{
		$order_by = "ORDER BY billsec ASC";
	}
	else if($filter_sort == 'billduration_dec')
	{
		$order_by = "ORDER BY billsec DESC";
	}
	else if($filter_sort == 'failedgateways_asc')
	{
		$order_by = "ORDER BY total_failed_gateways ASC";
	}
	else if($filter_sort == 'failedgateways_dec')
	{
		$order_by = "ORDER BY total_failed_gateways DESC";
	}
	else if($filter_sort == 'sellrate_asc')
	{
		$order_by = "ORDER BY sell_rate ASC";
	}
	else if($filter_sort == 'sellrate_dec')
	{
		$order_by = "ORDER BY sell_rate DESC";
	}
	else if($filter_sort == 'costrate_asc')
	{
		$order_by = "ORDER BY cost_rate ASC";
	}
	else if($filter_sort == 'costrate_dec')
	{
		$order_by = "ORDER BY cost_rate DESC";
	}
	else if($filter_sort == 'sellinit_asc')
	{
		$order_by = "ORDER BY sell_initblock ASC";
	}
	else if($filter_sort == 'sellinit_dec')
	{
		$order_by = "ORDER BY sell_initblock DESC";
	}
	else if($filter_sort == 'buyinit_asc')
	{
		$order_by = "ORDER BY buy_initblock ASC";
	}
	else if($filter_sort == 'buyinit_dec')
	{
		$order_by = "ORDER BY buy_initblock DESC";
	}
	else if($filter_sort == 'totcharges_asc')
	{
		$order_by = "ORDER BY total_sell_cost ASC";
	}
	else if($filter_sort == 'totcharges_dec')
	{
		$order_by = "ORDER BY total_sell_cost DESC";
	}
	else if($filter_sort == 'totcost_asc')
	{
		$order_by = "ORDER BY total_buy_cost ASC";
	}
	else if($filter_sort == 'totcost_dec')
	{
		$order_by = "ORDER BY total_buy_cost DESC";
	}
	else
	{
		$order_by = "ORDER BY created_time DESC";
	}

	$rateQuery = "";

	if($filter_groups != '')
	{
		if(is_numeric($filter_groups))
		{
			$groupRateTbl = 'SELECT * FROM groups WHERE id = '.$this->db->escape($filter_groups).' ';
			$groupQuery = $this->db->query($groupRateTbl);
			if($groupQuery->num_rows() > 0)
			{
				$row = $groupQuery->row();
				$rateQuery = $this->return_batch_rates($num, $offset, $row->id, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}
			else
			{
				$rateQuery = $this->return_batch_rates($num, $offset, '', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}

		}
		else
		{
			$rateQuery = $this->return_batch_rates($num, $offset, '', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
		}
	}
	else
	{
		$rateQuery = $this->return_batch_rates($num, $offset, '', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
	}

	$sql = "".$rateQuery."";
	$query = $this->db->query($sql);
	return $query;
}

function return_batch_rates($num, $offset, $group_id = '', $filter_carriers = '', $filter_country = '', $filter_destination = '', $destination_advance_filter)
{
	if($offset == ''){$offset='0';}

	$madeQuery = '';

	$whereRate = "WHERE id != '' ";
	if($filter_carriers != '')
	{
		if(is_numeric($filter_carriers))
		{
			$whereRate .= 'AND carrier_id = '.$this->db->escape($filter_carriers).' ';
		}
	}

	if($filter_country != '')
	{
		if(is_numeric($filter_country))
		{
			$whereRate .= 'AND country_id = '.$this->db->escape($filter_country).' ';
		}
	}

	if($filter_destination != '')
	{
		if(is_numeric($filter_destination))
		{
			if($destination_advance_filter == 'exact')
			{
				$whereRate .= 'AND digits = '.$this->db->escape($filter_destination).' ';
			}
			else if($destination_advance_filter == 'begin')
			{
				$whereRate .= "AND digits LIKE '".$this->db->escape_like_str($filter_destination)."%' ";
			}
			else if($destination_advance_filter == 'end')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."' ";
			}
			else if($destination_advance_filter == 'contain')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."%' ";
			}
		}
	}

	$where = '';

	if($group_id != '')
	{
		$where = "&& id = '".$group_id."' ";
	}

	//check if there is any group created by admin otherwise it will give sql error 
	$sqlChk    = "SELECT * FROM groups WHERE created_by = '0' LIMIT 1";
	$queryChk  = $this->db->query($sqlChk);
	if($queryChk->num_rows() > 0)
	{
		$sql    = "SELECT * FROM groups WHERE created_by = '0' ".$where." ";
		$query  = $this->db->query($sql);

		$numRow = $query->num_rows();
		$count = 0;

		foreach($query->result() as $row)
		{
			$madeQuery .= "(SELECT *, '".$row->group_rate_table."' as tbl_name, '".$row->group_name."' as group_name  FROM ".$row->group_rate_table." ".$whereRate.") ";
			if($count+1 < $numRow)
			{
				$madeQuery .= "UNION ALL ";
			}
			$count = $count + 1;
		}

		$madeQuery .= "LIMIT $offset,$num";
	}
	else
	{
		$madeQuery = "SELECT * FROM groups WHERE created_by = '0'";
	}

	return $madeQuery;
}

function get_all_rates_count($filter_groups, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter)
{
	$rateQuery = "";

	if($filter_groups != '')
	{
		if(is_numeric($filter_groups))
		{
			$groupRateTbl = 'SELECT * FROM groups WHERE id = '.$this->db->escape($filter_groups).' ';
			$groupQuery = $this->db->query($groupRateTbl);
			if($groupQuery->num_rows() > 0)
			{
				$row = $groupQuery->row();
				$rateQuery = $this->return_batch_rates_count($row->id, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}
			else
			{
				$rateQuery = $this->return_batch_rates_count('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}

		}
		else
		{
			$rateQuery = $this->return_batch_rates_count('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
		}
	}
	else
	{
		$rateQuery = $this->return_batch_rates_count('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
	}

	$sql = "".$rateQuery."";
	$query = $this->db->query($sql);
	return $query->num_rows();
}

function return_batch_rates_count($group_id = '', $filter_carriers = '', $filter_country = '', $filter_destination = '', $destination_advance_filter)
{
	$madeQuery = '';

	$whereRate = "WHERE id != '' ";
	if($filter_carriers != '')
	{
		if(is_numeric($filter_carriers))
		{
			$whereRate .= 'AND carrier_id = '.$this->db->escape($filter_carriers).' ';
		}
	}

	if($filter_country != '')
	{
		if(is_numeric($filter_country))
		{
			$whereRate .= 'AND country_id = '.$this->db->escape($filter_country).' ';
		}
	}

	if($filter_destination != '')
	{
		if(is_numeric($filter_destination))
		{
			if($destination_advance_filter == 'exact')
			{
				$whereRate .= 'AND digits = '.$this->db->escape($filter_destination).' ';
			}
			else if($destination_advance_filter == 'begin')
			{
				$whereRate .= "AND digits LIKE '".$this->db->escape_like_str($filter_destination)."%' ";
			}
			else if($destination_advance_filter == 'end')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."' ";
			}
			else if($destination_advance_filter == 'contain')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."%' ";
			}
		}
	}

	$where = '';

	if($group_id != '')
	{
		$where = "&& id = '".$group_id."' ";
	}

	//check if there is any group created by admin otherwise it will give sql error 
	$sqlChk    = "SELECT * FROM groups WHERE created_by = '0' LIMIT 1";
	$queryChk  = $this->db->query($sqlChk);
	if($queryChk->num_rows() > 0)
	{

		$sql    = "SELECT * FROM groups WHERE created_by = '0' ".$where." ";
		$query  = $this->db->query($sql);

		$numRow = $query->num_rows();
		$count = 0;

		foreach($query->result() as $row)
		{
			$madeQuery .= "(SELECT *, '".$row->group_rate_table."' as tbl_name, '".$row->group_name."' as group_name  FROM ".$row->group_rate_table." ".$whereRate.") ";
			if($count+1 < $numRow)
			{
				$madeQuery .= "UNION ALL ";
			}
			$count = $count + 1;
		}
	}
	else
	{
		$madeQuery = "SELECT * FROM groups WHERE created_by = '0'";
	}
	return $madeQuery;
}


//**PERFORMING BATCH UPDATE **//
function get_all_rates_to_perform_batch($filter_groups, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter)
{
	$rateQuery = "";

	if($filter_groups != '')
	{
		if(is_numeric($filter_groups))
		{
			$groupRateTbl = 'SELECT * FROM groups WHERE id = '.$this->db->escape($filter_groups).' ';
			$groupQuery = $this->db->query($groupRateTbl);
			if($groupQuery->num_rows() > 0)
			{
				$row = $groupQuery->row();
				$rateQuery = $this->return_batch_rates_to_perform_batch($row->id, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}
			else
			{
				$rateQuery = $this->return_batch_rates_to_perform_batch('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
			}

		}
		else
		{
			$rateQuery = $this->return_batch_rates_to_perform_batch('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
		}
	}
	else
	{
		$rateQuery = $this->return_batch_rates_to_perform_batch('', $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
	}

	$sql = "".$rateQuery."";
	$query = $this->db->query($sql);
	return $query;
}

function return_batch_rates_to_perform_batch($group_id = '', $filter_carriers = '', $filter_country = '', $filter_destination = '', $destination_advance_filter)
{
	$madeQuery = '';

	$whereRate = "WHERE id != '' ";
	if($filter_carriers != '')
	{
		if(is_numeric($filter_carriers))
		{
			$whereRate .= 'AND carrier_id = '.$this->db->escape($filter_carriers).' ';
		}
	}

	if($filter_country != '')
	{
		if(is_numeric($filter_country))
		{
			$whereRate .= 'AND country_id = '.$this->db->escape($filter_country).' ';
		}
	}

	if($filter_destination != '')
	{
		if(is_numeric($filter_destination))
		{
			if($destination_advance_filter == 'exact')
			{
				$whereRate .= 'AND digits = '.$this->db->escape($filter_destination).' ';
			}
			else if($destination_advance_filter == 'begin')
			{
				$whereRate .= "AND digits LIKE '".$this->db->escape_like_str($filter_destination)."%' ";
			}
			else if($destination_advance_filter == 'end')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."' ";
			}
			else if($destination_advance_filter == 'contain')
			{
				$whereRate .= "AND digits LIKE '%".$this->db->escape_like_str($filter_destination)."%' ";
			}
		}
	}

	$where = '';

	if($group_id != '')
	{
		$where = "&& id = '".$group_id."' ";
	}
	$sql    = "SELECT * FROM groups WHERE created_by = '0' ".$where." ";
	$query  = $this->db->query($sql);

	$numRow = $query->num_rows();
	$count = 0;

	foreach($query->result() as $row)
	{
		$madeQuery .= "(SELECT *, '".$row->group_rate_table."' as tbl_name, '".$row->group_name."' as group_name, '".$row->id."' as table_id  FROM ".$row->group_rate_table." ".$whereRate.") ";
		if($count+1 < $numRow)
		{
			$madeQuery .= "UNION ALL ";
		}
		$count = $count + 1;
	}

	return $madeQuery;
}

function perform_batch($get_full_batch_query, $is_sell_rate, $sell_rate_value, $action_sell_rate, $is_buy_rate, $buy_rate_value, $action_buy_rate, $is_sell_init, $sell_init_value, $action_sell_init, $is_buy_init, $buy_init_value, $action_buy_init, $is_sell_block_min, $sell_block_min_value, $action_sell_block_min_rate, $is_buy_block_min, $buy_block_min_value, $action_buy_block_min_rate)
{
	foreach($get_full_batch_query->result() as $row)
	{
		$set = "";
		$setForReseller = "";
		$count = 0;
		$countForReseller = "";

		if($is_sell_rate == 1)
		{
			if($action_sell_rate == 'add' || $action_sell_rate == 'subtract' || $action_sell_rate == 'equal')
			{
				if(is_numeric($sell_rate_value))
				{
					if($action_sell_rate == 'add')
					{
						$set .= 'sell_rate = (sell_rate + '.$sell_rate_value.')';
						$setForReseller .= 'cost_rate = (cost_rate + '.$sell_rate_value.')'; //admin sell rate is reseller buy rate
						$count = 1;
						$countForReseller = 1;
					}
					else if($action_sell_rate == 'subtract')
					{
						$set .= 'sell_rate = (sell_rate - '.$sell_rate_value.')';
						$setForReseller .= 'cost_rate = (cost_rate - '.$sell_rate_value.')'; //admin sell rate is reseller buy rate
						$count = 1;
						$countForReseller = 1;
					}
					else if($action_sell_rate == 'equal')
					{
						$set .= 'sell_rate = '.$sell_rate_value.'';
						$setForReseller .= 'cost_rate = '.$sell_rate_value.''; //admin sell rate is reseller buy rate
						$count = 1;
						$countForReseller = 1;
					}
				}
			}
		}

		//here we will not set reseller values becuase admin buy rate is not related to reseller anyway
		//no concern with the rate tables which reseller will create
		if($is_buy_rate == 1)
		{
			if($action_buy_rate == 'add' || $action_buy_rate == 'subtract' || $action_buy_rate == 'equal')
			{
				if(is_numeric($buy_rate_value))
				{
					if($action_buy_rate == 'add')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'cost_rate = (cost_rate + '.$buy_rate_value.')';
					}
					else if($action_buy_rate == 'subtract')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'cost_rate = (cost_rate - '.$buy_rate_value.')';
					}
					else if($action_buy_rate == 'equal')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'cost_rate = '.$buy_rate_value.'';
					}
				}
			}
		}

		if($is_sell_init == 1)
		{
			if($action_sell_init == 'add' || $action_sell_init == 'subtract' || $action_sell_init == 'equal')
			{
				if(is_numeric($sell_init_value))
				{
					if($action_sell_init == 'add')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sell_initblock = (sell_initblock + '.$sell_init_value.')';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buy_initblock = (buy_initblock + '.$sell_init_value.')'; //admin sell init is reseller buy init
					}
					else if($action_sell_init == 'subtract')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sell_initblock = (sell_initblock - '.$sell_init_value.')';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buy_initblock = (buy_initblock - '.$sell_init_value.')'; //admin sell init is reseller buy init
					}
					else if($action_sell_init == 'equal')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sell_initblock = '.$sell_init_value.'';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buy_initblock = '.$sell_init_value.''; //admin sell init is reseller buy init
					}
				}
			}
		}

		//admin personal matter 
		if($is_buy_init == 1)
		{
			if($action_buy_init == 'add' || $action_buy_init == 'subtract' || $action_buy_init == 'equal')
			{
				if(is_numeric($buy_init_value))
				{
					if($action_buy_init == 'add')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buy_initblock = (buy_initblock + '.$buy_init_value.')';
					}
					else if($action_buy_init == 'subtract')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buy_initblock = (buy_initblock - '.$buy_init_value.')';
					}
					else if($action_buy_init == 'equal')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buy_initblock = '.$buy_init_value.'';
					}
				}
			}
		}

		if($is_sell_block_min == 1)
		{
			if($action_sell_block_min_rate == 'add' || $action_sell_block_min_rate == 'subtract' || $action_sell_block_min_rate == 'equal')
			{
				if(is_numeric($sell_block_min_value))
				{
					if($action_sell_block_min_rate == 'add')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sellblock_min_duration = (sellblock_min_duration + '.$sell_block_min_value.')';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buyblock_min_duration = (buyblock_min_duration + '.$sell_block_min_value.')'; //admin minimum buy duration is reseller minimum buy duration
					}
					else if($action_sell_block_min_rate == 'subtract')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sellblock_min_duration = (sellblock_min_duration - '.$sell_block_min_value.')';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buyblock_min_duration = (buyblock_min_duration - '.$sell_block_min_value.')'; //admin minimum buy duration is reseller minimum buy duration
					}
					else if($action_sell_block_min_rate == 'equal')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'sellblock_min_duration = '.$sell_block_min_value.'';

						if($countForReseller == 1)
						{
							$setForReseller .= ',';
						}
						else
						{
							$countForReseller = 1;
						}
						$setForReseller .= 'buyblock_min_duration = '.$sell_block_min_value.''; //admin minimum buy duration is reseller minimum buy duration
					}
				}
			}
		}

		//admin personal matter 
		if($is_buy_block_min == 1)
		{
			if($action_buy_block_min_rate == 'add' || $action_buy_block_min_rate == 'subtract' || $action_buy_block_min_rate == 'equal')
			{
				if(is_numeric($buy_block_min_value))
				{
					if($action_buy_block_min_rate == 'add')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buyblock_min_duration = (buyblock_min_duration + '.$buy_block_min_value.')';
					}
					else if($action_buy_block_min_rate == 'subtract')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buyblock_min_duration = (buyblock_min_duration - '.$buy_block_min_value.')';
					}
					else if($action_buy_block_min_rate == 'equal')
					{
						if($count == 1)
						{
							$set .= ',';
						}
						else
						{
							$count = 1;
						}
						$set .= 'buyblock_min_duration = '.$buy_block_min_value.'';
					}
				}
			}
		}

		$sql = "UPDATE ".$row->tbl_name." SET ".$set." WHERE id = ".$row->id."";
		$query = $this->db->query($sql);

/************************************************************************/
/************************************************************************/
//now the admin rates has been updated we have to update the resellers group rate tables

$sql2    = "SELECT a.*, b.parent_id, b.customer_id FROM groups as a LEFT JOIN customers as b on b.customer_id = a.created_by WHERE created_by != '0' && parent_id = '0'";
$query2  = $this->db->query($sql2);

$numRow = $query2->num_rows();
if($numRow > 0)
{            
	foreach($query2->result() as $row2)
	{
		$sql3 = "UPDATE ".$row2->group_rate_table." SET ".$setForReseller." WHERE admin_rate_group = '".$row->tbl_name."' && admin_rate_id = ".$row->id."";
		$query3 = $this->db->query($sql3);
	}
}
}  
}
}
?>