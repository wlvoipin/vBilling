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

class did_model extends CI_Model {

	// list all did
	function get_all_did($num, $offset, $filter_did_number, $filter_did_type, $filter_carrier_id, $filter_customer_id)
	{
		if($offset == ''){$offset='0';}
        
        $order_by = "ORDER BY did_number";
      /* if($filter_sort == 'name_asc')
        {
            $order_by = "ORDER BY did_name ASC";
        }
        else if($filter_sort == 'name_dec')
        {
            $order_by = "ORDER BY did_name DESC";
        }
        else
        {
            $order_by = "ORDER BY did_id DESC";
        } */

		$where = '';
		$where .= "WHERE did_id != '' ";

		if($filter_did_number != '')
		{
			if(is_numeric($filter_did_number))
			{
				$where .= 'AND did_number = '.$this->db->escape($filter_did_number).' ';
			}
		}

		if($filter_did_type != '')
		{
			if(is_numeric($filter_did_type))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_did_type)." ";
			}
		}

        if($filter_carrier_id != '')
        {
            if(is_numeric($filter_carrier_id))
            {
                $where .= "AND carrier_id = ".$this->db->escape($filter_carrier_id)." ";
            }
        }
        if($filter_customer_id != '')
        {
            if(is_numeric($filter_customer_id))
            {
                $where .= "AND customer_id = ".$this->db->escape($filter_customer_id)." ";
            }
        }
		$sql = "SELECT * FROM did ".$where." ".$order_by." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_did_count($filter_did_number, $filter_did_type, $filter_carrier_id, $filter_customer_id)
	{
		$where = '';
		$where .= "WHERE did_id != '' ";
        $order_by = "ORDER BY did_number";
		if($filter_did_number != '')
		{
			if(is_numeric($filter_did_number))
			{
				$where .= 'AND did_number = '.$this->db->escape($filter_did_number).' ';
			}
		}

		if($filter_did_type != '')
		{
			if(is_numeric($filter_did_type))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_did_type)." ";
			}
		}

        if($filter_carrier_id != '')
        {
            if(is_numeric($filter_carrier_id))
            {
                $where .= 'AND carrier_id = '.$this->db->escape($filter_carrier_id).' ';
            }
        }

        if($filter_customer_id != '')
        {
            if(is_numeric($filter_customer_id))
            {
                $where .= 'AND customer_id = '.$this->db->escape($filter_customer_id).' ';
            }
        }

        $sql = "SELECT * FROM did ".$where." ".$order_by."";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}

    function did_any_cell($did_id, $col_name)
    {
        $sql = "SELECT * FROM did WHERE did_id = '".$did_id."' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->$col_name;
    }

	function get_single_did($did_id)
	{
		$sql = "SELECT * FROM did WHERE did_id='".$did_id."'";
		$query = $this->db->query($sql);
		return $query;
	}

	//enable disable did
	function enable_disable_did($data)
	{
		$sql = "UPDATE did SET enabled = '".$data['status']."' WHERE did_id = '".$data['did_id']."'";
		$query = $this->db->query($sql);
	}

	//populate did select box
	function did_select_box($did_id = '')
	{
		$sql = "SELECT * FROM did";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select DID</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($did_id == $row->did_id)
				{
					$data .= '<option value="'.$row->did_id.'" selected>'.$row->did_number.'</option>';
				}
				else
				{
					$data .= '<option value="'.$row->did_id.'">'.$row->did_number.'</option>';
				}
			}
		}
		return $data;
	}

	function show_did_select_box_valid_invalid($did_id)
	{
		$sql = "SELECT * FROM did WHERE enabled = '1'";
		$query = $this->db->query($sql);

		$data = '';
		$data .= '<option value="">Select DID</option>';

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($did_id == $row->did_id)
				{
					$data .= '<option value="'.$row->did_id.'" selected>'.$row->did_name.' ('.$this->did_valid_invalid($row->did_id).')</option>';
				}
				else
				{
					$data .= '<option value="'.$row->did_id.'">'.$row->did_name.' ('.$this->did_valid_invalid($row->did_id).')</option>';
				}
			}
		}

		return $data;
	}

	function did_valid_invalid($did_id)
	{
		$invalid = 0;
		$invalid_txt = '';

		//check if the did gateways exists 
		$sql3 = "SELECT * FROM did_gateway WHERE did_id = '".$did_id."' ";
		$query3 = $this->db->query($sql3);

		if($query3->num_rows() > 0) //if did has gateways
		{
			foreach($query3->result() as $didRow)
			{
				$gateway_name   = $didRow->gateway_name;
				$sofia_id       = $didRow->prefix_sofia_id;

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
		else //if did does not have gateways 
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

	function insert_new_did($carrier_id, $customer_id, $did_number)
	{
		$sql = "INSERT INTO did (did_number, carrier_id, customer_id, enabled) VALUES (".$did_number.",".$carrier_id.",".$customer_id.", 1)";
        $query = $this->db->query($sql);
		return $this->db->insert_id();
	}

	function update_did($did_id, $did_number, $customer_id, $carrier_id)
	{
		$sql = "UPDATE did SET did_number ='".$did_number."', customer_id ='".$customer_id."', carrier_id ='".$carrier_id."' WHERE did_id='".$did_id."'";
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
		$sql = "SELECT profile_name FROM sofia_conf WHERE did_id = '".$sofia_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->profile_name;
	}

	function gateway_use_count($gateway_name, $sofia_id)
	{
		$sql = "SELECT COUNT(*) AS count_gateway FROM did_gateway WHERE prefix = '".$gateway_name."' AND prefix_sofia_id = '".$sofia_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->count_gateway;
	}

	function delete_did($did_id)
	{
		//delete did
		$sql = "DELETE FROM did WHERE did_id='".$did_id."' LIMIT 1";
		$query = $this->db->query($sql);

	}
    
    function update_gateway_priority($row_id, $order)
    {
        $sql = "UPDATE did_gateway SET priority = '".$order."' WHERE did_id='".$row_id."'";
		$query = $this->db->query($sql);
    }
}
?>