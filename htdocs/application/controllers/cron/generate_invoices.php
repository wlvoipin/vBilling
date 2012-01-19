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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Generate_Invoices extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('billing_model');
		$this->load->library('pdf');
	}

	function index()
	{
		set_time_limit(0); //may be script can take longer as expected so setting time limit to 0 will not make any time restriction on the script 

		$current_date = date('Y-m-d');
		$current_date_time = strtotime($current_date);

		//get all customers whos invoicing date is today 
		$sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email FROM customers WHERE next_invoice_date = '".$current_date_time."' ";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) //if there are customers whose next inv date is today 
		{
			foreach($query->result() as $row)
			{
				$customer_id   = $row->customer_id;
				$billing_cycle = $row->customer_billing_cycle;
				$is_prepaid    = $row->customer_prepaid;

				//other info 
				$customer_name         = $row->customer_firstname.' '.$row->customer_lastname;
				$customer_address      = $row->customer_address;
				$customer_city_country = $row->customer_city.', '.$row->customer_country;
				$customer_contact      = $row->customer_phone_prefix.$row->customer_phone;
				$customer_send_cdr     = $row->customer_send_cdr;
				$customer_email        = $row->customer_billing_email;

				//get the last invoice id generated for particular customer 
				$sql2 = "SELECT MAX(id) AS id FROM invoices WHERE customer_id = '".$customer_id."' ";
				$query2 = $this->db->query($sql2);
				$row2 = $query2->row();

				if($row2->id != '') //if there is invoice generated last time 
				{
					$sql3   = "SELECT to_date FROM invoices WHERE id = '".$row2->id."' ";
					$query3 = $this->db->query($sql3);
					$row3   = $query3->row();

					$date_from = $row3->to_date;
				}
				else //this is the first invoice 
				{
					$m= date("m");
					$d= date("d");
					$y= date("Y");

					if($billing_cycle == 'daily')
					{
						$dd =  date('Y-m-d',mktime(0,0,0,$m,($d-1),$y));
						$date_from = strtotime($dd);
					}
					else if($billing_cycle == 'weekly')
					{
						$dd =  date('Y-m-d',mktime(0,0,0,$m,($d-7),$y));
						$date_from = strtotime($dd);
					}
					else if($billing_cycle == 'bi_weekly')
					{
						$dd =  date('Y-m-d',mktime(0,0,0,$m,($d-14),$y));
						$date_from = strtotime($dd);
					}
					else if($billing_cycle == 'monthly')
					{
						$dd =  date('Y-m-d',mktime(0,0,0,$m,($d-30),$y));
						$date_from = strtotime($dd);
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
				$sql5 = "INSERT INTO invoices (invoice_id , customer_id, from_date, to_date, total_charges, total_calls, customer_prepaid, invoice_generated_date, due_date, status) VALUES ('".$invoice_number."', '".$customer_id."', '".$date_from."', '".$current_date_time."', '".$total_invoice_amount."', '".$total_calls_made."' ,'".$is_prepaid."', '".$current_date_time."', '".$due_date."', '".$status."')";
				$query5 = $this->db->query($sql5);

				//update the next invoice date for customer 

				$m= date("m");
				$d= date("d");
				$y= date("Y");
				if($billing_cycle == 'daily')
				{
					$dd =  date('Y-m-d',mktime(0,0,0,$m,($d+1),$y));
					$next_invoice_date = strtotime($dd);
				}
				else if($billing_cycle == 'weekly')
				{
					$dd =  date('Y-m-d',mktime(0,0,0,$m,($d+7),$y));
					$next_invoice_date = strtotime($dd);
				}
				else if($billing_cycle == 'bi_weekly')
				{
					$dd =  date('Y-m-d',mktime(0,0,0,$m,($d+14),$y));
					$next_invoice_date = strtotime($dd);
				}
				else if($billing_cycle == 'monthly')
				{
					$dd =  date('Y-m-d',mktime(0,0,0,$m,($d+30),$y));
					$next_invoice_date = strtotime($dd);
				}
				$sql6 = "UPDATE customers SET next_invoice_date = '".$next_invoice_date."' WHERE customer_id = '".$customer_id."'";
				$query6 = $this->db->query($sql6);

				//generate pdf invoice and save 

				$obj = new $this->pdf;
				// set document information
				$obj->SetSubject('Invoice');
				$obj->SetKeywords('Digital Linx, Invoice, CDR');

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
					<tr><td>Contact: +966-548805579</td></tr>
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
					<tr><td>Contac: '.$customer_contact.'</td></tr>
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
					</tr>';
                    
                    $sql99 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' GROUP BY country_id";
                    $query99 = $this->db->query($sql99);
                    
                    $e_grand_total = 0;
                    if($query99->num_rows() > 0)
                    {
                        
                        foreach($query99->result() as $row99)
                        {
                            $e_tot_inv_amt  = $row99->total_invoice_amount;
                            $e_grand_total = $e_grand_total + $e_tot_inv_amt;
                            if($e_tot_inv_amt == '')
                            {
                                $e_tot_inv_amt = 0;
                            }
                            $e_total_calls_made       = $row99->total_calls;
                            $e_destination      = country_any_cell($row99->country_id, 'countryname');
                            
                        $tbl .= '<tr style="background-color:#ccc">
                            <td>'.$e_destination.'</td>
                            <td>'.$e_total_calls_made.'</td>
                                <td align="center">'.$e_tot_inv_amt.'</td>
                                </tr>';
                        }
                    }
                    else
                    {
                        $tbl .= '<tr style="background-color:#ccc">
                            <td>--</td>
                            <td>0</td>
                                <td align="center">0</td>
                                </tr>';
                    }

				$tbl .=	'<tr style="background-color:#ccc">
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
					<td style="font-weight:bold;text-align:center;">N/A</td>
					</tr>

					<tr style="background-color:#ccc">
				<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>

					<tr style="background-color:#ccc">
				<td>&nbsp;</td>
					<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$e_grand_total.'</td>
				</tr>
					</tbody></table>
					</td>
					</tr>

					<tr>
					<td height="30px" colspan="2">&nbsp;</td>
					</tr>';
                    
                    if(settings_any_cell('invoice_terms') != '')
                    {
                        $tbl .='<tr>
                        <td style="text-align: justify; font-style: italic; color: rgb(136, 136, 136); padding: 55px;" colspan="2">
                        '.settings_any_cell('invoice_terms').'
                        </td>
                        </tr>';
                    }

					$tbl .='</tbody></table>';

				$obj->writeHTML($tbl, true, false, false, false, '');

				//Close and output PDF document
				$obj->Output('media/invoices/'.$invoice_number.'.pdf', 'F');

				//create cdr pdf 

				$objcdr = new $this->pdf;
				// set document information
				$objcdr->SetSubject('Invoice CDR');
				$objcdr->SetKeywords('Digital Linx, Invoice, CDR');

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
					$tbl_cdr .=   '<tr><td colspan="4" style="color:red;" align="center">No Calls Made During This Period</td></tr>'; 
				}

				$tbl_cdr .=  '</table>';

				$objcdr->writeHTML($tbl_cdr, true, false, false, false, '');

				//Close and output PDF document
				$objcdr->Output('media/invoices/'.$invoice_number.'_cdr.pdf', 'F');
                
                /*************************************************************************/
                /********************CREATE CDR FOR ADMIN VIEW ***************************/
                /*************************************************************************/
                //create cdr pdf 

				$admincdr = new $this->pdf;
				// set document information
				$admincdr->SetSubject('Invoice CDR');
				$admincdr->SetKeywords('Digital Linx, Invoice, CDR');

				// add a page
				$admincdr->AddPage();

				$admincdr->SetFont('helvetica', '', 6);

				$sql11 = "SELECT * FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."'";
				$query11 = $this->db->query($sql11);

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
                
                $sql12 = "SELECT value FROM settings WHERE setting_name = 'optional_cdr_fields_include'";
				$query12 = $this->db->query($sql12);
                $row12 = $query12->row();
                $data_array = explode(',',$row12->value);
                
				$tbl_cdr .= '<table cellspacing="0" cellpadding="1" border="1" width="100%">
					<tr style="background-color:grey; color:#ffffff;">
				<td height="20" align="center">Date/Time</td>
					<td align="center">Destination</td>
					<td align="center">Bill Duration</td>
					<td align="center">Total Charges</td>';
                    
                    if(count($data_array) > 0)
                    {
                        if(in_array('caller_id_number',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">Caller ID Num</td>';
                        }
                        if(in_array('duration',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">Duration</td>';
                        }
                        if(in_array('network_addr',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">Network Address</td>';
                        }
                        if(in_array('username',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">Username</td>';
                        }
                        if(in_array('sip_user_agent',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">SIP User Agent</td>';
                        }
                        if(in_array('ani',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">ANI</td>';
                        }
                        if(in_array('cidr',$data_array))
                        {
                            $tbl_cdr .= '<td align="center">CIDR</td>';
                        }
                        if(in_array('sell_rate',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Sell Rate</td>';
                        }
                        if(in_array('cost_rate',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Cost Rate</td>';
                        }
                        if(in_array('buy_initblock',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Buy Init Block</td>';
                        }
                        if(in_array('sell_initblock',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Sell Init Block</td>';
                        }
                        if(in_array('total_buy_cost',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Total Buy Cost</td>';
                        }
                        if(in_array('gateway',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Gateway</td>';
                        }
                        if(in_array('total_failed_gateways',$data_array)) 
                        {
                            $tbl_cdr .= '<td align="center">Total Failed Gateways</td>';
                        }
                    }
                    $tbl_cdr .= '</tr>';
                  
                
                    
				if($query11->num_rows() > 0)
				{
					foreach ($query11->result() as $row11)
					{
						$tbl_cdr .=   '<tr>
							<td align="center" height="30">'.date("Y-m-d H:i:s", $row6->created_time/1000000).'</td>
							<td align="center">'.$row11->destination_number.'</td>
							<td align="center">'.$row11->billsec.'</td>
							<td align="center">'.$row11->total_sell_cost.'</td>';
                            
                            if(count($data_array) > 0)
                            {
                                if(in_array('caller_id_number',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->caller_id_number.'</td>';
                                }
                                if(in_array('duration',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->duration.'</td>';
                                }
                                if(in_array('network_addr',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->network_addr.'</td>';
                                }
                                if(in_array('username',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->username.'</td>';
                                }
                                if(in_array('sip_user_agent',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->sip_user_agent.'</td>';
                                }
                                if(in_array('ani',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->ani.'</td>';
                                }
                                if(in_array('cidr',$data_array))
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->cidr.'</td>';
                                }
                                if(in_array('sell_rate',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->sell_rate.'</td>';
                                }
                                if(in_array('cost_rate',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->cost_rate.'</td>';
                                }
                                if(in_array('buy_initblock',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->buy_initblock.'</td>';
                                }
                                if(in_array('sell_initblock',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->sell_initblock.'</td>';
                                }
                                if(in_array('total_buy_cost',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->total_buy_cost.'</td>';
                                }
                                if(in_array('gateway',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->gateway.'</td>';
                                }
                                if(in_array('total_failed_gateways',$data_array)) 
                                {
                                    $tbl_cdr .= '<td align="center">'.$row11->total_failed_gateways.'</td>';
                                }
                            }
                            $tbl_cdr .= '</tr>';   
					}
				}
				else
				{
					$tbl_cdr .=   '<tr><td colspan="4" style="color:red;" align="center">No Calls Made During This Period</td></tr>'; 
				}

				$tbl_cdr .=  '</table>';

				$admincdr->writeHTML($tbl_cdr, true, false, false, false, '');

				//Close and output PDF document
				$admincdr->Output('media/invoices/'.$invoice_number.'_cdr_admin.pdf', 'F');

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
						$this->email->subject('Invoice -- (Dated: '.$current_date.')');
						$this->email->message('Please see the attachment with this email to view your invoice:<br/><br/>

							<b>Billing Period From: &nbsp; '.date('Y-m-d', $date_from).'</b><br/>
							<b>Billing Period To: &nbsp; '.$current_date.'</b><br/>
							<br/><br/>

							Thanks & Regards,<br/>
							Digital Linx,
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
						$this->email->subject('Invoice -- (Dated: '.$current_date.')');
						$this->email->message('Please see the attachment with this email to view your invoice:<br/><br/>

							<b>Billing Period From: &nbsp; '.date('Y-m-d', $date_from).'</b><br/>
							<b>Billing Period To: &nbsp; '.$current_date.'</b><br/>
							<br/><br/>

							Thanks & Regards,<br/>
							Digital Linx,
							');
						$this->email->attach('media/invoices/'.$invoice_number.'.pdf');
						$this->email->attach('media/invoices/'.$invoice_number.'_cdr.pdf');
						$this->email->send();
					}
				}
			}
		} 
	}	
}