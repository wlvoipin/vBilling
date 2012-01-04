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
* "Muhammad Naseer Bhatti <nbhatti at gmail.com>"
*
* vBilling - VoIP Billing and Routing Platform
* version 0.1.1
*
*/

class Groups_model extends CI_Model {

	// list all customers
	function get_all_groups($num, $offset, $filter_groups, $filter_group_type)
	{
		if($offset == ''){$offset='0';}

		$where = '';
		$where .= "WHERE id != '' ";

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

		$sql = "SELECT * FROM groups ".$where." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_groups_count($filter_groups, $filter_group_type)
	{
		$where = '';
		$where .= "WHERE id != '' ";

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
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`digits` varchar(15) NOT NULL,
			`country_id` int(11) NOT NULL,
			`sell_rate` float(11,5) unsigned NOT NULL,
			`cost_rate` float(11,5) unsigned NOT NULL,
			`buy_initblock` int(11) NOT NULL,
			`sell_initblock` int(11) NOT NULL,
			`intrastate_rate` float(11,5) unsigned NOT NULL,
			`intralata_rate` float(11,5) unsigned NOT NULL,
			`carrier_id` int(11) NOT NULL,
			`lead_strip` int(11) NOT NULL,
			`trail_strip` int(11) NOT NULL,
			`prefix` varchar(16) NOT NULL,
			`suffix` varchar(16) NOT NULL,
			`lcr_profile` varchar(32) DEFAULT NULL,
			`date_start` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
			`date_end` datetime NOT NULL DEFAULT '2030-12-31 00:00:00',
			`quality` float(10,6) NOT NULL,
			`reliability` float(10,6) NOT NULL,
			`enabled` tinyint(1) NOT NULL DEFAULT '1',
			`lrn` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		$query = $this->db->query($sql);

		$sql2 = "UPDATE groups SET 	group_rate_table = 'lcr_group_".$insert_id."' WHERE id = '".$insert_id."'";
		$query2 = $this->db->query($sql2);
	}

	//populate group select box
	function group_select_box($rate_group_id = '')
	{
		$sql = "SELECT * FROM groups";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Group</option>';

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
		$sql = "SELECT * FROM groups";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Group</option>';

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

		$sql = "SELECT * FROM ".$group_tbl_name."";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) //if rates are defined for the group
		{
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
		$sql = "INSERT INTO ".$group_rate_table_name."(digits, country_id, sell_rate, cost_rate, buy_initblock, sell_initblock, intrastate_rate, intralata_rate, carrier_id, lead_strip, trail_strip, prefix, suffix, lcr_profile, date_start, date_end, quality, reliability, enabled, lrn) VALUES ('".$data['digits']."', '".$data['country']."', '".$data['rate']."', '".$data['costrate']."', '".$data['buyblock']."', '".$data['sellblock']."', '".$data['intrastate']."', '".$data['intralata']."', '".$data['carrier']."', '".$data['leadstrip']."', '".$data['trailstrip']."', '".$data['prefix']."', '".$data['suffix']."', '".$data['profile']."', '".$data['startdate']."', '".$data['enddate']."', '".$data['quality']."', '".$data['reliability']."', '1', '".$data['lrn']."')";
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
		$sql = "UPDATE ".$data['group_rate_table_name']." SET digits='".$data['digits']."', country_id='".$data['country']."', sell_rate='".$data['rate']."', cost_rate='".$data['costrate']."', buy_initblock='".$data['buyblock']."', sell_initblock='".$data['sellblock']."', intrastate_rate='".$data['intrastate']."', intralata_rate='".$data['intralata']."', carrier_id='".$data['carrier']."', lead_strip='".$data['leadstrip']."', trail_strip='".$data['trailstrip']."', prefix='".$data['prefix']."', suffix='".$data['suffix']."', lcr_profile='".$data['profile']."', date_start='".$data['startdate']."', date_end='".$data['enddate']."', quality='".$data['quality']."', reliability='".$data['reliability']."', lrn='".$data['lrn']."' WHERE id='".$data['rate_id']."'";
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
}
?>