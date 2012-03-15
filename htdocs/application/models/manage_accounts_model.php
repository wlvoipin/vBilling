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

class Manage_accounts_model extends CI_Model {

	function get_all_accounts($num, $offset, $filter_account_type, $filter_enabled, $filter_username, $filter_cust_name)
	{
		if($offset == ''){$offset='0';}

		$where = '';
		$where .= "WHERE id != '' ";

		if($filter_account_type != '')
		{
			$where .= 'AND type = '.$this->db->escape($filter_account_type).' ';
		}

		if($filter_enabled != '')
		{
			if(is_numeric($filter_enabled))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_enabled)." ";
			}
		}
        
        if($filter_username != '')
		{
			$where .= "AND username LIKE '%".$this->db->escape_like_str($filter_username)."%' ";
		}
        
        if($filter_cust_name != '')
		{
			$where .= "AND (customer_firstname LIKE '%".$this->db->escape_like_str($filter_cust_name)."%' OR customer_lastname LIKE '%".$this->db->escape_like_str($filter_cust_name)."%')";
		}

        if($filter_account_type == 'sub_admin')
        {
            $sql = "SELECT * FROM accounts ".$where." LIMIT $offset,$num";
        }
        else
        {
            $sql = "SELECT a.*, b.customer_firstname, b.customer_lastname FROM accounts a LEFT JOIN customers b ON b.customer_id = a.customer_id ".$where." LIMIT $offset,$num";
        }
		$query = $this->db->query($sql);
		return $query;
	}

	function get_all_accounts_count($filter_account_type, $filter_enabled, $filter_username, $filter_cust_name)
	{
		$where = '';
		$where .= "WHERE id != '' ";

		if($filter_account_type != '')
		{
			$where .= 'AND type = '.$this->db->escape($filter_account_type).' ';
		}

		if($filter_enabled != '')
		{
			if(is_numeric($filter_enabled))
			{
				$where .= "AND enabled = ".$this->db->escape($filter_enabled)." ";
			}
		}
        
        if($filter_username != '')
		{
			$where .= "AND username LIKE '%".$this->db->escape_like_str($filter_username)."%' ";
		}
        
        if($filter_cust_name != '')
		{
			$where .= "AND (customer_firstname LIKE '%".$this->db->escape_like_str($filter_cust_name)."%' OR customer_lastname LIKE '%".$this->db->escape_like_str($filter_cust_name)."%')";
		}

		
        if($filter_account_type == 'sub_admin')
        {
            $sql = "SELECT * FROM accounts ".$where."";
        }
        else
        {
            $sql = "SELECT a.*, b.customer_firstname, b.customer_lastname FROM accounts a LEFT JOIN customers b ON b.customer_id = a.customer_id ".$where."";
        }
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}

	function customers_with_no_accounts()
	{
		$sql = "SELECT DISTINCT(customer_id) AS customer_id FROM accounts WHERE customer_id != '' && customer_id != '0'";
		$query = $this->db->query($sql);

		$where = '';
		$count = 0;
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($count == 0)
				{
					$where .= "WHERE customer_id != '".$row->customer_id."' ";
				}
				else
				{
					$where .= "AND customer_id != '".$row->customer_id."' ";
				}
				$count = 1;
			}
		}

		$sql2 = "SELECT customer_id, customer_firstname FROM customers ".$where." ";
		$query2 = $this->db->query($sql2);
		return $query2;
	}

	function check_username_availability($username)
	{
		$sql = "SELECT * FROM accounts WHERE username = '".$username."' ";
		$query = $this->db->query($sql);
		return $query;
	}

	function create_new_account($data)
	{
		if($data['account_type'] == 'admin')
		{
			$sql = 'INSERT INTO accounts (username, password, type, is_customer, customer_id, enabled) VALUES ('.$data['username'].', "'.$data['password'].'", "sub_admin", "0", "0", "1") ';
			$query = $this->db->query($sql);
            return $this->db->insert_id();
		}
	}
    
    function insert_sub_admin_access_restrictions($access, $user_id)
    {
        $sql = "INSERT INTO accounts_restrictions (user_id) VALUES ('".$user_id."') ";
        $query = $this->db->query($sql);
        
        for($i=0; $i<count($access); $i++)
        {
            $sql = "UPDATE accounts_restrictions SET ".$access[$i]." = '1' WHERE user_id = '".$user_id."' ";
            $query = $this->db->query($sql);
        }
    }

	function enable_disable_account($account_id, $status)
	{
		$sql = "UPDATE accounts SET enabled = '".$status."' WHERE id = '".$account_id."' ";
		$query = $this->db->query($sql);
	}

	function delete_account($account_id)
	{
		$sql = "DELETE FROM accounts WHERE id = '".$account_id."' LIMIT 1";
		$query = $this->db->query($sql);
	}
}
?>