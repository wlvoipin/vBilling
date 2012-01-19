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

//acl list tbl any cell
function acl_list_any_cell($id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM acl_lists WHERE id = '".$id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

//acl list tbl any cell
function country_any_cell($id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM countries WHERE id = '".$id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

//countries select box
function all_countries($id = '')
{
	$CI = & get_instance();

	$sql = "SELECT * FROM countries ORDER BY countryname ASC";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Country</option>';

	foreach($query->result() as $row)
	{
		if($id == $row->id)
		{
			$data .= '<option value="'.$row->id.'" selected>'.$row->countryname.'</option>';
		}
		else
		{
			$data .= '<option value="'.$row->id.'">'.$row->countryname.'</option>';
		}
	}
	echo $data;
}

//acl list tbl any cell
function timezone_any_cell($id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM timezones WHERE id = '".$id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

//countries select box
function all_timezones($id = '')
{
	$CI = & get_instance();

	$sql = "SELECT * FROM timezones ORDER BY id ASC";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Timezone</option>';

	foreach($query->result() as $row)
	{
		if($id == $row->id)
		{
			$data .= '<option value="'.$row->id.'" selected>'.$row->gmt.' '.$row->timezone_location.'</option>';
		}
		else
		{
			$data .= '<option value="'.$row->id.'">'.$row->gmt.' '.$row->timezone_location.'</option>';
		}
	}
	echo $data;
}

//sofia profile name 
function sofia_profile_name($sofia_id)
{
	$CI = & get_instance();
	$sql = "SELECT * FROM sofia_conf WHERE id = '".$sofia_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();
	return $row->profile_name;
}

//all sofia profile sip ips drop down 
function get_all_sip_ips($sipip = '', $sofia_id = '')
{
	$CI = & get_instance();

	$sql = "SELECT * FROM sofia_settings WHERE param_name = 'sip-ip' GROUP BY sofia_id";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select SIP IP</option>';

	foreach($query->result() as $row)
	{
		if($sipip == $row->param_value && $sofia_id == $row->sofia_id)
		{
			$data .= '<option value="'.$row->param_value.'|'.$row->sofia_id.'" selected>'.$row->param_value.' -- '.sofia_profile_name($row->sofia_id).'</option>';
		}
		else
		{
			$data .= '<option value="'.$row->param_value.'|'.$row->sofia_id.'">'.$row->param_value.' -- '.sofia_profile_name($row->sofia_id).'</option>';
		}
	}
	echo $data;
}

function customer_drop_down($customer_id = '')
{
	$CI = & get_instance();

	$sql = "SELECT customer_id, customer_firstname FROM customers";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Customer</option>';

	foreach($query->result() as $row)
	{
		if($customer_id == $row->customer_id)
		{
			$data .= '<option value="'.$row->customer_id.'" selected>'.$row->customer_firstname.'</option>';
		}
		else
		{
			$data .= '<option value="'.$row->customer_id.'" >'.$row->customer_firstname.'</option>';
		}
	}
	echo $data;
}

function gateways_drop_down($gateway_name = '', $sofia_id = '')
{
	$CI = & get_instance();

	$sql = "SELECT * FROM sofia_gateways GROUP BY gateway_name, sofia_id";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Gateway</option>';

	if($query->num_rows() > 0)
	{
		foreach($query->result() as $row)
		{
			if($gateway_name == $row->gateway_name && $sofia_id == $row->sofia_id)
			{
				$data .= '<option value="'.$row->gateway_name.'|'.$row->sofia_id.'" selected>'.$row->gateway_name.' -- '.sofia_profile_name($row->sofia_id).'</option>';
			}
			else
			{
				$data .= '<option value="'.$row->gateway_name.'|'.$row->sofia_id.'">'.$row->gateway_name.' -- '.sofia_profile_name($row->sofia_id).'</option>';
			}
		}
	}

	return $data;
}

function hangup_causes_drop_down($hangup_cause = '')
{
	$CI = & get_instance();

	$sql = "SELECT * FROM hangup_causes";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Type</option>';

	if($query->num_rows() > 0)
	{
		foreach($query->result() as $row)
		{
			if($hangup_cause == $row->hangup_cause)
			{
				$data .= '<option value="'.$row->hangup_cause.'" selected>'.$row->hangup_cause.'</option>';
			}
			else
			{
				$data .= '<option value="'.$row->hangup_cause.'">'.$row->hangup_cause.'</option>';
			}
		}
	}

	return $data;
}

function checkdateTime($dateTime)
{
	if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches))
	{ 
		if (checkdate($matches[2], $matches[3], $matches[1]))
		{ 
			return true; 
		} 
	} 
	return false;
}  

/*****************CUSTOMER ACCESS FUNCTION ****************************/

function restricted_customer_acl_nodes_count($customer_id)
{
	$CI = & get_instance();

	$sql = "SELECT COUNT(*) total_count FROM acl_nodes WHERE customer_id = '".$customer_id."' && added_by = '".$customer_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->total_count;
}

function restricted_customer_sip_acc_count($customer_id)
{
	$CI = & get_instance();

	$sql = "SELECT COUNT(*) total_count FROM directory WHERE customer_id = '".$customer_id."' && added_by = '".$customer_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->total_count;
}

function customer_access_any_cell($customer_id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM customer_access_limitations WHERE customer_id = '".$customer_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

function sub_admin_access_any_cell($user_id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM accounts_restrictions WHERE user_id = '".$user_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

function customer_full_name($customer_id)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM customers WHERE customer_id = '".$customer_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->customer_firstname.' '.$row->customer_lastname;
}

function invoices_any_cell($id, $col_name)
{
	$CI = & get_instance();

	$sql = "SELECT * FROM invoices WHERE id = '".$id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->$col_name;
}

function make_invoice_over_due($id)
{
    $CI = & get_instance();

	$sql = "UDATE INVOICES SET status = 'over_due' WHERE id = '".$id."' ";
	$query = $CI->db->query($sql);
}

function customer_drop_down_generate_invoice()
{
	$CI = & get_instance();
    
    $current_date = date('Y-m-d');
    $current_date = strtotime($current_date);
    
    
	$sql = "SELECT customer_id, customer_firstname, customer_lastname FROM customers WHERE customer_prepaid = '0' ";
	$query = $CI->db->query($sql);

	$data = '';
	$data .= '<option value="">Select Customer</option>';

	foreach($query->result() as $row)
	{
		$last_inv_date = last_invoice_generated_date($row->customer_id);
        
        if($last_inv_date != '')
        {
            $diff = abs($current_date - $last_inv_date);
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            
            if($days > 0)
            {
                $data .= '<option value="'.$row->customer_id.'" >'.$row->customer_firstname.' '.$row->customer_lastname.' -- Last Inv Generated '.$days.' day(s) ago</option>';
            }
        }
        else
        {
            $data .= '<option value="'.$row->customer_id.'" >'.$row->customer_firstname.' '.$row->customer_lastname.' (No inv generated yet)</option>';
        }
       
	}
	echo $data;
}

function last_invoice_generated_date($customer_id)
{
	$CI = & get_instance();
    $last_date = '';
    
	$sql = "SELECT MAX(id) as id FROM invoices WHERE customer_id = '".$customer_id."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();
    
    if($row->id != '')
    {
        $sql2 = "SELECT invoice_generated_date FROM invoices WHERE id = '".$row->id."' ";
        $query2 = $CI->db->query($sql2);
        $row2 = $query2->row();
        $last_date = $row2->invoice_generated_date;
    }
    
    return $last_date;
}

function settings_any_cell($col_name)
{
	$CI = & get_instance();

	$sql = "SELECT value FROM settings WHERE setting_name = '".$col_name."' ";
	$query = $CI->db->query($sql);
	$row = $query->row();

	return $row->value;
}
?>