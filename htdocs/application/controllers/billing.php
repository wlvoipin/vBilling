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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('billing_model');
		$this->load->library('pdf');
		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}
		else
		{
			if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
            
            if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/');
			}
            
            if($this->session->userdata('user_type') == 'sub_admin')
            {
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_biling') == 0)
                {
                    redirect ('home/');
                }
            }
		}
	}

	function index()
	{
		//for filter & search
		$filter_result_days     = 1;
		$filter_carriers        = '';
		$search                 = '';

		if($this->input->get('searchFilter'))
		{
			$filter_result_days     = $this->input->get('filter_result_days');
			$filter_carriers        = $this->input->get('filter_carriers');
			$search                 = $this->input->get('searchFilter');
		}

		if(is_numeric($filter_result_days))
		{
			if($filter_result_days > 30)
			{
				$filter_result_days = 1;
			}

			if($filter_result_days == 0)
			{
				$filter_result_days = 1;
			}
		}
		else
		{
			$filter_result_days = 1;
		}


		$data['filter_result_days']         = $filter_result_days;
		$data['filter_carriers']            = $filter_carriers;

		$data['page_name']		=	'billing_list';
		$data['selected']		=	'billing';
		$data['sub_selected']   =   'summary_billing';
		$data['page_title']		=	'BILLING';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/billing_sub_menu';
		$data['main_content']	=	'billing/billing_view';
		$this->load->view('default/template',$data);
	}

	function invoices()
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_invoices') == 0)
            {
                redirect ('billing/');
            }
        }
        
        //for filter & search
		$filter_date_from       = '';
		$filter_date_to         = '';
		$filter_customers       = '';
		$filter_billing_type    = '';
		$filter_status          = '';
        $filter_sort            = '';
        $filter_contents        = 'all';
		$search                 = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_date_from       = $this->input->get('filter_date_from');
			$filter_date_to         = $this->input->get('filter_date_to');
			$filter_customers       = $this->input->get('filter_customers');
			$filter_billing_type    = $this->input->get('filter_billing_type');
			$filter_status          = $this->input->get('filter_status');
            $filter_sort            = $this->input->get('filter_sort');
			$search                 = $this->input->get('searchFilter');
            $filter_contents        = $this->input->get('filter_contents');
			$msg_records_found      = "Records Found Based On Search Criteria";
		}

		if($filter_date_from != '')
		{
			if (!checkdateTime($filter_date_from))
			{
				//$filter_date_from   = '';
			}
		}

		if($filter_date_to != '')
		{
			if (!checkdateTime($filter_date_to))
			{
				//$filter_date_to   = '';
			}
		}
        
        if($filter_contents == '' || ($filter_contents != 'all' && $filter_contents != 'my'))
        {
            $filter_contents = "all";
        }

		$data['filter_date_from']           = $filter_date_from;
		$data['filter_date_to']             = $filter_date_to;
		$data['filter_customers']           = $filter_customers;
		$data['filter_billing_type']        = $filter_billing_type;
		$data['filter_status']              = $filter_status;
        $data['filter_sort']                = $filter_sort;
        $data['filter_contents']            = $filter_contents;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'billing/invoices/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_customers='.$filter_customers.'&filter_billing_type='.$filter_billing_type.'&filter_status='.$filter_status.'&filter_sort='.$filter_sort.'&filter_contents='.$filter_contents.'';
		$config['page_query_string'] = TRUE;

		$config['num_links'] = 6;

		$config['cur_tag_open'] = '<span class="current">';
		$config['cur_tag_close'] = '</span> ';

		$config['next_link'] = 'next';
		$config['next_tag_open'] = '<span class="next-site">';
		$config['next_tag_close'] = '</span>';

		$config['prev_link'] = 'previous';
		$config['prev_tag_open'] = '<span class="prev-site">';
		$config['prev_tag_close'] = '</span>';

		$config['first_link'] = 'first';
		$config['last_link'] = 'last';

		$data['count'] = $this->billing_model->get_invoices_count($filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type, $filter_status, $filter_contents);
		$config['total_rows'] = $data['count'];

		if(isset($_GET['per_page']))
		{
			if(is_numeric($_GET['per_page']))
			{
				$config['uri_segment'] = $_GET['per_page'];
			}
			else
			{
				$config['uri_segment'] = '';
			}
		}
		else
		{
			$config['uri_segment'] = '';
		}

		$this->pagination->initialize($config);

		$data['msg_records_found'] = "".$data['count']."&nbsp;".$msg_records_found."";

		$data['invoices']       =   $this->billing_model->get_invoices($config['per_page'],$config['uri_segment'],$filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type, $filter_status, $filter_sort, $filter_contents);

		$data['page_name']		=	'invoices_list';
		$data['selected']		=	'billing';
		$data['sub_selected']   =   'list_invoices';
		$data['page_title']		=	'INVOICES';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/billing_sub_menu';
		$data['main_content']	=	'billing/invoices_view';
		$this->load->view('default/template',$data);
	}

	function download_invoice($invoice_id = '')
	{
		if($invoice_id == '')
		{
			redirect ('billing/invoices/');
		}

		if (file_exists('media/invoices/'.$invoice_id.'.pdf')) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=media/invoices/'.$invoice_id.'.pdf');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('media/invoices/'.$invoice_id.'.pdf'));
			ob_clean();
			flush();
			readfile('media/invoices/'.$invoice_id.'.pdf');
			exit;
		}
		else
		{
			redirect ('billing/invoices/');
		}
	}

	function download_cdr($invoice_id = '')
	{
		if($invoice_id == '')
		{
			redirect ('billing/invoices/');
		}

		if (file_exists('media/invoices/'.$invoice_id.'_cdr.pdf')) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=media/invoices/'.$invoice_id.'_cdr.pdf');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('media/invoices/'.$invoice_id.'_cdr.pdf'));
			ob_clean();
			flush();
			readfile('media/invoices/'.$invoice_id.'_cdr.pdf');
			exit;
		}
		else
		{
			redirect ('billing/invoices/');
		}
	}
    
    /*function download_cdr_admin($invoice_id = '')
	{
		if($invoice_id == '')
		{
			redirect ('billing/invoices/');
		}

		if (file_exists('media/invoices/'.$invoice_id.'_cdr_admin.pdf')) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=media/invoices/'.$invoice_id.'_cdr_admin.pdf');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('media/invoices/'.$invoice_id.'_cdr_admin.pdf'));
			ob_clean();
			flush();
			readfile('media/invoices/'.$invoice_id.'_cdr_admin.pdf');
			exit;
		}
		else
		{
			redirect ('billing/invoices/');
		}
	}*/

	function generate_manual_invoice()
	{
		set_time_limit(0);
		$this->load->library('user_agent');

		if($this->agent->referrer() == '')
		{
			redirect ('billing/invoices/');
		}

		if ($urlParts = parse_url($this->agent->referrer()))
		{
			$baseUrl = $urlParts["scheme"] . "://" . $urlParts["host"] . $urlParts["path"];
			if($baseUrl != ''.base_url().'billing/invoices' && $baseUrl != ''.base_url().'billing/invoices/')
			{
				redirect ('billing/invoices/');
			}
		}

		$customer_id = $this->input->post('new_inv_customer');
		$misc_charges = $this->input->post('misc_charges');

		if($customer_id == '')
		{
			redirect ('billing/invoices/');
		}

		if(!is_numeric($misc_charges)) //invalid value redirect 
		{
			redirect ('billing/invoices/');
		}

		if(!is_numeric($customer_id)) //invalid value redirect 
		{
			redirect ('billing/invoices/');
		}

		$misc_charges = round($misc_charges, 4);

		$current_date = date('Y-m-d');
		$current_date_time = strtotime($current_date);

		//get all customers whos invoicing date is today 
		$sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email FROM customers WHERE customer_id = '".$customer_id."' ";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) //if we found the customer
		{
			$row = $query->row();

			$billing_cycle     =   $row->customer_billing_cycle;
			$is_prepaid         =   $row->customer_prepaid;

			//other info 
			$customer_name          = $row->customer_firstname.' '.$row->customer_lastname;
			$customer_address       = $row->customer_address;
			$customer_city_country  = $row->customer_city.', '.$row->customer_country;
			$customer_contact       = $row->customer_phone_prefix.$row->customer_phone;
			$customer_send_cdr      = $row->customer_send_cdr;
			$customer_email         = $row->customer_billing_email;

			//get the last invoice id generated for particular customer 
			$sql2 = "SELECT MAX(id) AS id FROM invoices WHERE customer_id = '".$customer_id."' ";
			$query2 = $this->db->query($sql2);
			$row2 = $query2->row();

			if($row2->id != '') //if there is invoice generated last time 
			{
				$sql3 = "SELECT to_date FROM invoices WHERE id = '".$row2->id."' ";
				$query3 = $this->db->query($sql3);
				$row3 = $query3->row();

				$date_from = $row3->to_date;
			}
			else //this is the first invoice 
			{
				$m= date("m");
				$d= date("d");
				$y= date("Y");
				if($billing_cycle == 'daily')
				{
					$d =  date('Y-m-d',mktime(0,0,0,$m,($d-1),$y));
					$date_from = strtotime($d);
				}
				else if($billing_cycle == 'weekly')
				{
					$d =  date('Y-m-d',mktime(0,0,0,$m,($d-7),$y));
					$date_from = strtotime($d);
				}
				else if($billing_cycle == 'bi_weekly')
				{
					$d =  date('Y-m-d',mktime(0,0,0,$m,($d-14),$y));
					$date_from = strtotime($d);
				}
				else if($billing_cycle == 'monthly')
				{
					$d =  date('Y-m-d',mktime(0,0,0,$m,($d-30),$y));
					$date_from = strtotime($d);
				}
			}

			// to 2011-12-16 00:00:00  (less or equal to 2011-12-16 00:00:00)
			// from 2011-12-15 00:00:00 (greater than from starting from 2011-12-15 00:00:01)

			$cdr_from   = $date_from * 1000000; //convert into micro seconds
			$cdr_to     = $current_date_time * 1000000; //convert into micro seconds

			//sum total invoice amount from cdr between 2 dates 
			$sql4 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."'";
			$query4 = $this->db->query($sql4);
			$row4 = $query4->row();

			$total_invoice_amount   = $row4->total_invoice_amount;
			if($total_invoice_amount == '')
			{
				$total_invoice_amount = 0;
			}

			$actual_invoice_amount = $total_invoice_amount;
			$total_invoice_amount = $total_invoice_amount + $misc_charges;
			$total_calls_made       = $row4->total_calls;

			$status = "pending";

			if($total_invoice_amount == 0)
			{
				$status = "paid";
			}
			else if($is_prepaid == '1')
			{
				$status = "paid";
			}

			//generate random invoice number 
			$check = 0;
			do {
				$invoice_number = 'INV-'.rand(1,999).rand(1,999).rand(1,99);
				$check_invoice_number_existis = $this->billing_model->check_invoice_number_existis($invoice_number);

				if($check_invoice_number_existis == 0)
				{
					$check = 1;
				}
			} while ($check == 0);

			$due_date = $current_date_time + 604800; //generated date + 7 days

			//now we are all good to insert the invoice 
			$sql5 = "INSERT INTO invoices (invoice_id , customer_id, from_date, to_date, total_charges, total_calls, customer_prepaid, invoice_generated_date, due_date, status, misc_charges) VALUES ('".$invoice_number."', '".$customer_id."', '".$date_from."', '".$current_date_time."', '".$total_invoice_amount."', '".$total_calls_made."' ,'".$is_prepaid."', '".$current_date_time."', '".$due_date."', '".$status."', '".$misc_charges."')";
			$query5 = $this->db->query($sql5);

			//update the next invoice date for customer 

			$m= date("m");
			$d= date("d");
			$y= date("Y");
			if($billing_cycle == 'daily')
			{
				$d =  date('Y-m-d',mktime(0,0,0,$m,($d+1),$y));
				$next_invoice_date = strtotime($d);
			}
			else if($billing_cycle == 'weekly')
			{
				$d =  date('Y-m-d',mktime(0,0,0,$m,($d+7),$y));
				$next_invoice_date = strtotime($d);
			}
			else if($billing_cycle == 'bi_weekly')
			{
				$d =  date('Y-m-d',mktime(0,0,0,$m,($d+14),$y));
				$next_invoice_date = strtotime($d);
			}
			else if($billing_cycle == 'monthly')
			{
				$d =  date('Y-m-d',mktime(0,0,0,$m,($d+30),$y));
				$next_invoice_date = strtotime($d);
			}
			$sql6 = "UPDATE customers SET next_invoice_date = '".$next_invoice_date."' WHERE customer_id = '".$customer_id."'";
			$query6 = $this->db->query($sql6);

			//generate pdf invoice and save 

			$obj = new $this->pdf;
			// set document information
			$obj->SetSubject('INVOICE');
			$obj->SetKeywords('DigitalLinx, INVOICE, CDR');

			// add a page
			$obj->AddPage();

			$obj->SetFont('helvetica', '', 6);

			$tbl = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;margin-left:auto;margin-right:auto;padding:5px">

				<tbody>

				<tr>
				<td align="center" colspan="2">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody><tr>
				<td align="right" style="font-weight:bold;color:red" colspan="2">Invoice # '.$invoice_number.'</td>
			</tr>

				<tr>
				<td align="right" style="font-weight:bold;" colspan="2">Date: '.$current_date.'</td>
				</tr>

				<tr>
				<td align="right" style="font-weight:bold;" colspan="2">Due Date: '.date('Y-m-d H:i:s', $due_date).'</td>
				</tr>
				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			From:
			</td>
				</tr>

				<tr>
				<td align="left">
				<table width="100%">
				<tbody><tr><td>DigitalLinx.com</td></tr>
				<tr><td>P.O. Box 305319</td></tr>
				<tr><td>Riyadh, Saudi Arabia</td></tr>
				<tr><td>Contac: +966-548805579</td></tr>
				</tbody></table>
				</td>
				</tr>
				
				</tbody></table>
				</td>

				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			To:
			</td>
				</tr>

				<tr>
				<td align="left">
				<table width="100%" style="float:right">
				<tbody><tr><td>'.$customer_name.'</td></tr>
				<tr><td>'.$customer_address.'</td></tr>
				<tr><td>'.$customer_city_country.'</td></tr>
				<tr><td>Contact: '.$customer_contact.'</td></tr>
				</tbody></table>
				</td>
				</tr>

				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			Billing Period From:
			</td>
				</tr>

				<tr>
				<td align="left">
				'.date('Y-m-d', $date_from).'
				</td>
				</tr>


				</tbody></table>
				</td>

				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			Billing Period To:
			</td>
				</tr>

				<tr>
				<td align="left">
				'.$current_date.'
				</td>
				</tr>

				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td align="center" colspan="2">
				<table width="100%" style="border: 5px solid #dadada;">
			<tbody><tr style="background-color:#777777;color:#fff">
			<td>Total Calls</td>
				<td>Total Charges</td>
				<td align="center">Total</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>'.$total_calls_made.'</td>
				<td>'.$actual_invoice_amount.'</td>
				<td align="center">'.$actual_invoice_amount.'</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td align="right" style="font-weight:bold;">Tax</td>
				<td style="font-weight:bold;text-align:center;">N/A</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td align="right" style="font-weight:bold;">Other</td>
				<td style="font-weight:bold;text-align:center;">'.$misc_charges.'</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>

				<tr style="background-color:#ccc">
			<td>&nbsp;</td>
				<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
			<td style="font-weight:bold;color:#3172C6;text-align:center">'.$total_invoice_amount.'</td>
			</tr>
				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td style="text-align: justify; font-style: italic; color: rgb(136, 136, 136); padding: 55px;" colspan="2">
				Please contact us immediately if you have any concerns regarding this invoice.<br/><br/>

				Thanks,<br/>
				DigitalLinx.com
				info@digitallinx.com
				</td>
				</tr>

				</tbody></table>';

			$obj->writeHTML($tbl, true, false, false, false, '');

			//Close and output PDF document
			$obj->Output('media/invoices/'.$invoice_number.'.pdf', 'F');

			//create cdr pdf 

			$objcdr = new $this->pdf;
			// set document information
			$objcdr->SetSubject('INVOICE CDR');
			$objcdr->SetKeywords('DigitalLinx, INVOICE, CDR');

			// add a page
			$objcdr->AddPage();
			$objcdr->SetFont('helvetica', '', 6);

			$sql6 = "SELECT * FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."'";
			$query6 = $this->db->query($sql6);

			$tbl_cdr = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;margin-left:auto;margin-right:auto;padding:5px">

				<tbody>

				<tr>
				<td align="center" colspan="2">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody><tr>
				<td align="right" style="font-weight:bold;color:red" colspan="2">Invoice # '.$invoice_number.'</td>
			</tr>

				<tr>
				<td align="right" style="font-weight:bold;" colspan="2">Date: '.$current_date.'</td>
				</tr>

				<tr>
				<td align="right" style="font-weight:bold;" colspan="2">Due Date: '.date('Y-m-d H:i:s', $due_date).'</td>
				</tr>
				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			Billing Period From:
			</td>
				</tr>

				<tr>
				<td align="left">
				'.date('Y-m-d', $date_from).'
				</td>
				</tr>


				</tbody></table>
				</td>

				<td align="center">
				<table width="100%" style="background-color:#dadada;padding:5px;border:5px solid #cccccc;">
			<tbody>
				<tr>
				<td style="background-color:#777777;color:#fff;padding-left:10px" align="left">
			Billing Period To:
			</td>
				</tr>

				<tr>
				<td align="left">
				'.$current_date.'
				</td>
				</tr>

				</tbody></table>
				</td>
				</tr>

				<tr>
				<td height="30px" colspan="2">&nbsp;</td>
				</tr>

				</tbody></table>';

			$tbl_cdr .= '<table cellspacing="0" cellpadding="1" border="1" width="100%">
				<tr style="background-color:grey; color:#ffffff;">
			<td height="20" align="center">Date/Time</td>
				<td align="center">Destination</td>
				<td align="center">Bill Duration</td>
				<td align="center">Total Charges</td>
				</tr>';
			if($query6->num_rows() > 0)
			{
				foreach ($query6->result() as $row6)
				{
					$tbl_cdr .=   '<tr>
						<td align="center" height="30">'.date("Y-m-d H:i:s", $row6->created_time/1000000).'</td>
						<td align="center">'.$row6->destination_number.'</td>
						<td align="center">'.$row6->billsec.'</td>
						<td align="center">'.$row6->total_sell_cost.'</td></tr>';   
				}
			}
			else
			{
				$tbl_cdr .=   '<tr><td colspan="4" style="color:red;" align="center">No Calls Were Made During This Period</td></tr>'; 
			}

			$tbl_cdr .=  '</table>';

			$objcdr->writeHTML($tbl_cdr, true, false, false, false, '');

			//Close and output PDF document
			$objcdr->Output('media/invoices/'.$invoice_number.'_cdr.pdf', 'F');

			//time to send email to the customer 


			if($customer_email != '')
			{
				if($customer_send_cdr == 0) //dont attach cdr
				{
					$this->load->library('email');
					$this->email->clear(TRUE);
					$this->email->from('noreply@digitallinx.com', 'Digital Linx');
					$this->email->to($customer_email);
					//$this->email->cc('cc@email.com');
					$this->email->subject('INVOICE -- (Dated: '.$current_date.')');
					$this->email->message('Please see the attachment with this email to view your invoice:<br/><br/>

						<b>Billing Period From: &nbsp; '.date('Y-m-d', $date_from).'</b><br/>
						<b>Billing Period To: &nbsp; '.$current_date.'</b><br/>
						<br/><br/>

						Thanks,<br/>
						Digital Linx
						');
					$this->email->attach('media/invoices/'.$invoice_number.'.pdf');
					$this->email->send();
				}
				else
				{
					$this->load->library('email');
					$this->email->clear(TRUE);
					$this->email->from('noreply@digitallinx.com', 'Digital Linx');
					$this->email->to($customer_email);
					//$this->email->cc('cc@email.com');
					$this->email->subject('INVOICE -- (Dated: '.$current_date.')');
					$this->email->message('Please see the attachment with this email to view your invoice:<br/><br/>

						<b>Billing Period From: &nbsp; '.date('Y-m-d', $date_from).'</b><br/>
						<b>Billing Period To: &nbsp; '.$current_date.'</b><br/>
						<br/><br/>

						Thanks & Regards,<br/>
						DigitalLinx,
						');
					$this->email->attach('media/invoices/'.$invoice_number.'.pdf');
					$this->email->attach('media/invoices/'.$invoice_number.'_cdr.pdf');
					$this->email->send();
				}
			}
		} 
		$this->session->set_flashdata('success','Invoice Generated Successfully.');
		redirect ('billing/invoices/');
	}

	function mark_as_paid($id = '')
	{
		if($id == '')
		{
			redirect ('billing/invoices/');
		}

		if(!is_numeric($id)) //invalid value redirect 
		{
			redirect ('billing/invoices/');
		}
        
        if(invoices_any_cell($id, 'parent_id') != '0')
        {
            redirect ('billing/invoices/');
        }

		$this->billing_model->mark_as_paid($id);
		$this->session->set_flashdata('success','Invoice Paid Successfully.');
		redirect ('billing/invoices/');
	}
}