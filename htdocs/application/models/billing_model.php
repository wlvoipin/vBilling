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

class Billing_model extends CI_Model {

    function get_summary_total_calls($date_frm, $date_to, $filter_carriers)
    {
        $date_frm = $date_frm * 10000;
        $date_to = $date_to * 10000;
        
        $where = '';
        if($filter_carriers != '')
		{
			if(is_numeric($filter_carriers))
			{
				$where .= 'AND lcr_carrier_id = '.$this->db->escape($filter_carriers).' ';
			}
		}
        
        $sql = "SELECT COUNT(*) as total_calls FROM cdr WHERE created_time >= '".sprintf("%.0f", $date_frm)."' && created_time <= '".sprintf("%.0f", $date_to)."' AND (hangup_cause = 'NORMAL_CLEARING' || hangup_cause = 'ALLOTTED_TIMEOUT') AND billsec > 0 ".$where."";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->total_calls;
    }
    
    function get_summary_total_amount($date_frm, $date_to, $filter_carriers)
    {
        $date_frm = $date_frm * 10000;
        $date_to = $date_to * 10000;
        
        $where = '';
        if($filter_carriers != '')
		{
			if(is_numeric($filter_carriers))
			{
				$where .= 'AND lcr_carrier_id = '.$this->db->escape($filter_carriers).' ';
			}
		}
        
        $sql = "SELECT SUM(total_sell_cost) as total_amount FROM cdr WHERE created_time >= '".sprintf("%.0f", $date_frm)."' && created_time <= '".sprintf("%.0f", $date_to)."' AND (hangup_cause = 'NORMAL_CLEARING' || hangup_cause = 'ALLOTTED_TIMEOUT') AND billsec > 0 ".$where." ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->total_amount;
    }
    
    function get_summary_total_profit($date_frm, $date_to, $filter_carriers)
    {
        $date_frm = $date_frm * 10000;
        $date_to = $date_to * 10000;
        
        $where = '';
        if($filter_carriers != '')
		{
			if(is_numeric($filter_carriers))
			{
				$where .= 'AND lcr_carrier_id = '.$this->db->escape($filter_carriers).' ';
			}
		}
        
        $sql = "SELECT SUM((total_sell_cost - total_buy_cost)) as profit FROM cdr WHERE created_time >= '".sprintf("%.0f", $date_frm)."' && created_time <= '".sprintf("%.0f", $date_to)."' AND (hangup_cause = 'NORMAL_CLEARING' || hangup_cause = 'ALLOTTED_TIMEOUT') AND billsec > 0 ".$where." ";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->profit;
    }
    
    function check_invoice_number_existis($invoice_number)
    {
        $sql = "SELECT * FROM invoices WHERE invoice_id = '".$invoice_number."' ";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    function get_invoices($num,$offset, $filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type, $filter_status, $filter_sort, $filter_contents)
	{
		if($offset == ''){$offset='0';}
        
        $order_by = "";
        if($filter_sort == 'date_asc')
        {
            $order_by = "ORDER BY invoice_generated_date ASC";
        }
        else if($filter_sort == 'date_dec')
        {
            $order_by = "ORDER BY invoice_generated_date DESC";
        }
        else if($filter_sort == 'totcalls_asc')
        {
            $order_by = "ORDER BY total_calls ASC";
        }
        else if($filter_sort == 'totcalls_dec')
        {
            $order_by = "ORDER BY total_calls DESC";
        }
        else if($filter_sort == 'totcharges_asc')
        {
            $order_by = "ORDER BY total_charges ASC";
        }
        else if($filter_sort == 'totcharges_dec')
        {
            $order_by = "ORDER BY total_charges DESC";
        }
        else
        {
            $order_by = "ORDER BY invoice_generated_date DESC";
        }
        
		$where = '';
		if($filter_contents == 'all')
        {
            $where .= "WHERE id != '' ";
        }
        else
        {
            $where .= "WHERE parent_id = '0' ";
        }

		if($filter_date_from != '')
		{
			$date_from = strtotime($filter_date_from);
			$where .= "AND invoice_generated_date >= '".$date_from."' ";
		}

		if($filter_date_to != '')
		{
			$date_to = strtotime($filter_date_to);
			$where .= "AND invoice_generated_date <= '".$date_to."' ";
		}

		if($filter_customers != '')
		{
			if(is_numeric($filter_customers))
			{
				$where .= 'AND customer_id = '.$this->db->escape($filter_customers).' ';
			}
		}
        
        if($filter_billing_type != '')
		{
			if(is_numeric($filter_billing_type))
			{
				$where .= 'AND customer_prepaid = '.$this->db->escape($filter_billing_type).' ';
			}
		}
        
        if($filter_status != '')
		{
			$where .= 'AND status = '.$this->db->escape($filter_status).' ';
		}

		$sql = "SELECT * FROM invoices ".$where." ".$order_by." LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}

	//invoices count for pagination 
	function get_invoices_count($filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type, $filter_status, $filter_contents)
	{
		$where = '';
		if($filter_contents == 'all')
        {
            $where .= "WHERE id != '' ";
        }
        else
        {
            $where .= "WHERE parent_id = '0' ";
        }
        
		if($filter_date_from != '')
		{
			$date_from = strtotime($filter_date_from);
			$where .= "AND invoice_generated_date >= '".$date_from."' ";
		}

		if($filter_date_to != '')
		{
			$date_to = strtotime($filter_date_to);
			$where .= "AND invoice_generated_date <= '".$date_to."' ";
		}

		if($filter_customers != '')
		{
			if(is_numeric($filter_customers))
			{
				$where .= 'AND customer_id = '.$this->db->escape($filter_customers).' ';
			}
		}
        
        if($filter_billing_type != '')
		{
			if(is_numeric($filter_billing_type))
			{
				$where .= 'AND customer_prepaid = '.$this->db->escape($filter_billing_type).' ';
			}
		}
        
        if($filter_status != '')
		{
			$where .= 'AND status = '.$this->db->escape($filter_status).' ';
		}

		$sql = "SELECT * FROM invoices ".$where."";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}
    
    function mark_as_paid($id)
    {
        $sql = "SELECT * FROM invoices WHERE id = '".$id."' ";
		$query = $this->db->query($sql);
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            
            $customer_id        = $row->customer_id;
            $total_charges      = $row->total_charges;
            $total_tax          = $row->total_tax;
            $misc_charges 	    = $row->misc_charges;
            
            $actual_amt = $total_charges - $total_tax - $misc_charges;
            
            $sql2 = "UPDATE invoices SET status = 'paid' WHERE id = '".$id."' ";
            $query2 = $this->db->query($sql2);
            
            $sql3 = "UPDATE customers SET customer_balance = (customer_balance + ".$actual_amt.") WHERE customer_id = '".$customer_id."' ";
            $query3 = $this->db->query($sql3);
        }
    }
}
?>