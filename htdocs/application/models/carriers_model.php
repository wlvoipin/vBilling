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

class Carriers_model extends CI_Model {

	// list all carriers
	function get_all_carriers($num, $offset, $filter_carriers, $filter_carrier_type, $filter_sort)
	{
		if($offset == ''){$offset='0';}
        
        $order_by = "";
        if($filter_sort == 'name_asc')
        {
            $order_by = "ORDER BY carrier_name ASC";
        }
        else if($filter_sort == 'name_dec')
        {
            $order_by = "ORDER BY carrier_name DESC";
        }
        else
        {
            $order_by = "ORDER BY id DESC";
        }
        
        
		$where = '';
		$where .= "WHERE id != '' ";

		if($filter_carriers != '')
		{
			if(is_numeric($filter_carriers))
			{
				$where .= 'AND id = '.$this->db->escape($filter_carriers).' ';
			}
		}

		if($filter_carrier_type != '')
		{
			if(is_numeric($filter_carrier_type))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_carrier_type)." ";
			}
		}

		$sql = "SELECT * FROM carriers ".$where." ".$order_by." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_carriers_count($filter_carriers, $filter_carrier_type)
	{
		$where = '';
		$where .= "WHERE id != '' ";
	
		if($filter_carriers != '')
		{
			if(is_numeric($filter_carriers))
			{
				$where .= 'AND id = '.$this->db->escape($filter_carriers).' ';
			}
		}
	
		if($filter_carrier_type != '')
		{
			if(is_numeric($filter_carrier_type))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_carrier_type)." ";
			}
		}
	
		$sql = "SELECT * FROM carriers ".$where." ";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}

	function get_single_carrier($carrier_id)
	{
		$sql = "SELECT * FROM carriers WHERE id='".$carrier_id."'";
		$query = $this->db->query($sql);
		return $query;
	}

	//enable disable carrier
	function enable_disable_carrier($data)
	{
		$sql = "UPDATE carriers SET enabled = '".$data['status']."' WHERE id = '".$data['carrier_id']."'";
		$query = $this->db->query($sql);
	}

	//populate carrier select box
	function carrier_select_box($carrier_id = '')
	{
		$sql = "SELECT * FROM carriers";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Carrier</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($carrier_id == $row->id)
				{
					$data .= '<option value="'.$row->id.'" selected>'.$row->carrier_name.'</option>';
				}
				else
				{
					$data .= '<option value="'.$row->id.'">'.$row->carrier_name.'</option>';
				}
			}
		}

		return $data;
	}

	function show_carrier_select_box_valid_invalid($carrier_id)
	{
		$sql = "SELECT * FROM carriers WHERE enabled = '1'";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select Carrier</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($carrier_id == $row->id)
				{
					$data .= '<option value="'.$row->id.'" selected>'.$row->carrier_name.' ('.$this->carrier_valid_invalid($row->id).')</option>';
				}
				else
				{
					$data .= '<option value="'.$row->id.'">'.$row->carrier_name.' ('.$this->carrier_valid_invalid($row->id).')</option>';
				}
			}
		}

		return $data;
	}

	function carrier_valid_invalid($carrier_id)
	{
		$invalid = 0;
		$invalid_txt = '';

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

	function insert_new_carrier($carriername)
	{
		$sql = "INSERT INTO carriers (carrier_name, enabled) VALUES ('".$carriername."', '1')";
		$query = $this->db->query($sql);
		return $this->db->insert_id();
	}

	function insert_carrier_gateways($carrier_id, $prefix, $suffix, $codec, $sofia_id, $pre, $priority)
	{
		$sql = "INSERT INTO carrier_gateway (carrier_id, gateway_name, prefix, suffix, codec, prefix_sofia_id, enabled, priority) VALUES ('".$carrier_id."', '".$prefix."', '".$pre."', '".$suffix."', '".$codec."', '".$sofia_id."', '1', '".$priority."')";
		$query = $this->db->query($sql);
	}

	function update_carrier($carriername, $carrier_id)
	{
		$sql = "UPDATE carriers SET carrier_name='".$carriername."' WHERE id='".$carrier_id."'";
		$query = $this->db->query($sql);
	}

	//***************CARRIER GATEWAYS ********************************
	function carrier_gateways($carrier_id)
	{
		$sql = "SELECT * FROM carrier_gateway WHERE carrier_id = '".$carrier_id."' ORDER BY priority ASC";
		$query = $this->db->query($sql);
		return $query;
	}

	function delete_carrier_gateways($carrier_id)
	{
		$sql = "DELETE FROM carrier_gateway WHERE carrier_id='".$carrier_id."' ";
		$query = $this->db->query($sql);
	}

	function all_gateways_with_use_count($gateway_name, $sofia_id)
	{
		$sql = "SELECT * FROM sofia_gateways GROUP BY gateway_name, sofia_id";
		$query = $this->db->query($sql);

		$data = '';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($gateway_name == $row->gateway_name && $sofia_id == $row->sofia_id)
				{
					$data .= '<option value="'.$row->gateway_name.'|'.$row->sofia_id.'" selected>'.$row->gateway_name.' -- '.$this->sofia_name($row->sofia_id).' ('.$this->gateway_use_count($row->gateway_name, $row->sofia_id).')</option>';
				}
				else
				{
					$data .= '<option value="'.$row->gateway_name.'|'.$row->sofia_id.'">'.$row->gateway_name.' -- '.$this->sofia_name($row->sofia_id).' ('.$this->gateway_use_count($row->gateway_name, $row->sofia_id).')</option>';
				}
			}
		}
		return $data;
	}

	function sofia_name($sofia_id)
	{
		$sql = "SELECT profile_name FROM sofia_conf WHERE id = '".$sofia_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->profile_name;
	}

	function gateway_use_count($gateway_name, $sofia_id)
	{
		$sql = "SELECT COUNT(*) AS count_gateway FROM carrier_gateway WHERE prefix = '".$gateway_name."' AND prefix_sofia_id = '".$sofia_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->count_gateway;
	}

	function delete_carrier($carrier_id)
	{
		//delete carrier
		$sql = "DELETE FROM carriers WHERE id='".$carrier_id."' LIMIT 1";
		$query = $this->db->query($sql);

		//delete carrier gateways
		$sql2 = "DELETE FROM carrier_gateway WHERE carrier_id='".$carrier_id."'";
		$query2 = $this->db->query($sql2);
	}
    
    function update_gateway_priority($row_id, $order)
    {
        $sql = "UPDATE carrier_gateway SET priority = '".$order."' WHERE id='".$row_id."'";
		$query = $this->db->query($sql);
    }
}
?>