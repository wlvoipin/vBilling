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

		
        
        /*
        
        //what are the possible scenerios for generating invoices ?
        
        //for admin 
        
            1) generate invoices for all normal customers (parent_id = 0 && reseller_level = 0) -- common function --
            2) generate invoices for reseller 3 (parent_id = 0 && reseller_level = 3)
            3) generate invoices for reseller 2 (parent_id = 0 && reseller_level = 2)
        
        //for reseller 3
        
            1) generate invoices for all normal customers (reseller_level = 0) -- common function --
            2) generate invoices for all reseller 2 (parent_id = reseller_3_id)
            
        // for reseller 2
        
            1) generate invoices for all normal customers (reseller_level = 0) -- common function --
            
            so basically there are 6 different scenerios which we have to take care of and it is damn tough job :P
            
            for all normal customers we can use one common function . 
            
            Question: now question arises that why one common function dont they belong to different parents?
            
            Good  Question :)
            
            Answer: we will use one common function because on normal customers direct rates are applied from their respective rate group
                    so if a normal customer belong to admin than admin rate table will be applied and obviously his cdr will be generated according to
                    his rate table which is assigned to him same is the case with other normal customers belong to reseller 3 or 2. so for normal 
                    customers we dont have to see who was his parent.
        
        */
		//get all customers whos invoicing date is today 
        
        $this->generate_invoices_for_all_normal_customers();
        $this->generate_invoices_for_admin_reseller();
        $this->generate_invoices_parent_r3_child_r2();
		 
	}
    
    /**********************************************************************************************************************/
    /****************************************INVOICES FOR NORMAL CUSTOMERS*************************************************/
    /**********************************************************************************************************************/
    function generate_invoices_for_all_normal_customers()
    {
        $current_date = date('Y-m-d');
		$current_date_time = strtotime($current_date);
        
        $sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE next_invoice_date = '".$current_date_time."' && reseller_level = '0' ";
        $query = $this->db->query($sql);
        
        //test query
        //$sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE reseller_level = '0' ";
		

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
                $customer_parent_id    = $row->parent_id;
                $customer_grand_parent_id    = $row->grand_parent_id;

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
                
                /*//for testing only 
                $cdr_to     = time() * 1000000; //convert into micro seconds*/
                
				//sum total invoice amount from cdr between 2 dates 
				$sql4 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0'";
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
				$invoice_number = 'INV-'.rand(1,999).rand(1,9).time();
                
                //due date
				$due_date = $current_date_time + 604800; //generated date + 7 days

				//now we are all good to insert the invoice 
				$sql5 = "INSERT INTO invoices (invoice_id , customer_id, from_date, to_date, total_charges, total_calls, customer_prepaid, invoice_generated_date, due_date, status, parent_id, grand_parent_id) VALUES ('".$invoice_number."', '".$customer_id."', '".$date_from."', '".$current_date_time."', '".$total_invoice_amount."', '".$total_calls_made."' ,'".$is_prepaid."', '".$current_date_time."', '".$due_date."', '".$status."', '".$customer_parent_id."', '".$customer_grand_parent_id."')";
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
                    <td>Destination</td>
                    <td>Total Calls</td>
					<td align="center">Total Charges</td>
					
					</tr>';
                    
                    $sql99 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls, destination_number FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' GROUP BY destination_number";
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
                            $e_destination      = $row99->destination_number;
                            
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
                    
                    $terms = settings_any_cell('invoice_terms', customer_any_cell($customer_id, 'parent_id'));
                    if($terms != '')
                    {
                        $tbl .='<tr>
                        <td style="text-align: justify; font-style: italic; color: rgb(136, 136, 136); padding: 55px;" colspan="2">
                        '.$terms.'
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

				$sql6 = "SELECT * FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' ";
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
    
    
    
    /**********************************************************************************************************************/
    /****************************************INVOICES FOR ADMIN RESELLERS*************************************************/
    /**********************************************************************************************************************/
    function generate_invoices_for_admin_reseller()
    {
        $current_date = date('Y-m-d');
		$current_date_time = strtotime($current_date);
        
        $sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE next_invoice_date = '".$current_date_time."' && reseller_level != '0' && parent_id = '0'";
        $query = $this->db->query($sql);
        
        /*//for testing only 
        $sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE reseller_level != '0' && parent_id = '0'";*/
        
		

		if($query->num_rows() > 0) //if there are resellers whose next inv date is today 
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
                $customer_parent_id    = $row->parent_id;
                $customer_grand_parent_id    = $row->grand_parent_id;

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
                
                /*//for testing only 
                $cdr_to     = time() * 1000000; //convert into micro seconds*/
                
				//sum total invoice amount from cdr between 2 dates 
                
                //get the reseller 3 own total invoice amount and total calls he made (than we will see for his customers )
				$sql4 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0'";
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
                
                //now sum total sell cost for his customers and total calls his childrens makes according to admin rates 
                $sqlResellerChild = "SELECT SUM(total_admin_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0'";
				$queryResellerChild = $this->db->query($sqlResellerChild);
				$rowResellerChild = $queryResellerChild->row();
                
                $total_reseller_children_invoice_amount   = $rowResellerChild->total_invoice_amount;
				if($total_reseller_children_invoice_amount == '')
				{
					$total_reseller_children_invoice_amount = 0;
				}
				$total_reseller_children_calls_made       = $rowResellerChild->total_calls;
                
                $grand_reseller_inv_amt = $total_invoice_amount + $total_reseller_children_invoice_amount;
                $grand_reseller_tot_calls = $total_calls_made + $total_reseller_children_calls_made;
                
				//generate random invoice number 
				$invoice_number = 'INV-'.rand(1,999).rand(1,9).time();
                
                //due date
				$due_date = $current_date_time + 604800; //generated date + 7 days

				//now we are all good to insert the invoice 
				$sql5 = "INSERT INTO invoices (invoice_id , customer_id, from_date, to_date, total_charges, total_calls, customer_prepaid, invoice_generated_date, due_date, status, parent_id, grand_parent_id) VALUES ('".$invoice_number."', '".$customer_id."', '".$date_from."', '".$current_date_time."', '".$grand_reseller_inv_amt."', '".$grand_reseller_tot_calls."' ,'".$is_prepaid."', '".$current_date_time."', '".$due_date."', '".$status."', '".$customer_parent_id."', '".$customer_grand_parent_id."')";
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
                        <td colspan="2" style="background-color:#777777;color:#fff" align="center">Your Billing Details</td>
                    </tr>

					<tr>
					<td align="center" colspan="2">
					<table width="100%" style="border: 5px solid #dadada;">
				<tbody><tr style="background-color:#777777;color:#fff">
				<td>Destination</td>
                <td>Total Calls</td>
					<td align="center">Total Charges</td>
					</tr>';
                    
                    $sql99 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls, destination_number FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' GROUP BY destination_number";
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
                            $e_destination      = $row99->destination_number;
                            
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
					<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$e_grand_total.'</td>
				</tr>';
                
                $tbl .= '</tbody></table>
					</td>
					</tr>';
                
                /***********************************************************/
                //now we have to show his childrens data group by destination 
                 /***********************************************************/   
                 
                 	$tbl .= '<tr>
                        <td height="30px" colspan="2">&nbsp;</td>
					</tr>
                    
                    <tr>
                        <td colspan="2" style="background-color:#777777;color:#fff" align="center">Your Customers Billing Details</td>
                    </tr>

					<tr>
					<td align="center" colspan="2">
					<table width="100%" style="border: 5px solid #dadada;">
				<tbody><tr style="background-color:#777777;color:#fff">
                    <td>Destination</td>
                    <td>Total Calls</td>
					<td align="center">Total Charges</td>
					</tr>';
                    
                    $sql100 = "SELECT SUM(total_admin_sell_cost) as total_invoice_amount, COUNT(*) as total_calls, destination_number FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' GROUP BY destination_number";
                    $query100 = $this->db->query($sql100);
                    
                    $child_e_grand_total = 0;
                    if($query100->num_rows() > 0)
                    {
                        
                        foreach($query100->result() as $row100)
                        {
                            $child_e_tot_inv_amt  = $row100->total_invoice_amount;
                            if($child_e_tot_inv_amt == '')
                            {
                                $child_e_tot_inv_amt = 0;
                            }
                            $child_e_grand_total = $child_e_grand_total + $child_e_tot_inv_amt;
                            
                            $child_e_total_calls_made  = $row100->total_calls;
                            $child_e_destination      = $row100->destination_number;
                            
                        $tbl .= '<tr style="background-color:#ccc">
                            <td>'.$child_e_destination.'</td>
                            <td>'.$child_e_total_calls_made.'</td>
                                <td align="center">'.$child_e_tot_inv_amt.'</td>
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
					<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$child_e_grand_total.'</td>
				</tr>';
                
                $tbl .= '<tr>
                        <td height="30px" colspan="2">&nbsp;</td>
					</tr>';
                
                $tbl .= '<tr style="background-color:#777777;color:#fff">
                    <td colspan="3" align="center">Grand Total</td>
                    </tr>';
                $tbl .=	'<tr style="background-color:#ccc">
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
					<td align="right" style="font-weight:bold;color:#3172C6">Grand Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$grand_reseller_inv_amt.'</td>
				</tr>
					</tbody></table>
					</td>
					</tr>

					<tr>
					<td height="30px" colspan="2">&nbsp;</td>
					</tr>';
                
                    $terms = settings_any_cell('invoice_terms', customer_any_cell($customer_id, 'parent_id'));
                    if($terms != '')
                    {
                        $tbl .='<tr>
                        <td style="text-align: justify; font-style: italic; color: rgb(136, 136, 136); padding: 55px;" colspan="2">
                        '.$terms.'
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

				$sql6 = "SELECT * FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' ";
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

				$tbl_cdr .= '<table cellspacing="0" cellpadding="1" border="1" width="100%">';
					
                    $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
				<td height="20" align="center" colspan="4">Your CDR Details</td>
					</tr>';
                    
                    $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
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
                
                $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
				<td height="20" align="center" colspan="4">Your Customers CDR Details</td>
					</tr>';
                    
                $sql7 = "SELECT * FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' ";
				$query7 = $this->db->query($sql7);
                
                if($query7->num_rows() > 0)
				{
					foreach ($query7->result() as $row7)
					{
						$tbl_cdr .=   '<tr>
							<td align="center" height="30">'.date("Y-m-d H:i:s", $row7->created_time/1000000).'</td>
							<td align="center">'.$row7->destination_number.'</td>
							<td align="center">'.$row7->billsec.'</td>
							<td align="center">'.$row7->total_admin_sell_cost.'</td></tr>';   
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
    
    
    
    
    /**********************************************************************************************************************/
    /****************************************INVOICES FOR RESELLER 2 (PARENT RESELLER 3)*************************************************/
    /**********************************************************************************************************************/
    function generate_invoices_parent_r3_child_r2()
    {
        $current_date = date('Y-m-d');
		$current_date_time = strtotime($current_date);
        
        $sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE next_invoice_date = '".$current_date_time."' && reseller_level = '2' && parent_id != '0'";
        $query = $this->db->query($sql);
        
        /*//for testing only 
        $sql = "SELECT customer_id, customer_billing_cycle, customer_prepaid, next_invoice_date, customer_firstname, customer_lastname, customer_address, customer_city, customer_country, customer_phone_prefix, customer_phone, customer_send_cdr, customer_billing_email, parent_id, grand_parent_id FROM customers WHERE reseller_level = '2' && parent_id != '0'";*/
        
		

		if($query->num_rows() > 0) //if there are resellers whose next inv date is today 
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
                $customer_parent_id    = $row->parent_id;
                $customer_grand_parent_id    = $row->grand_parent_id;

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
                
                /*//for testing only 
                $cdr_to     = time() * 1000000; //convert into micro seconds*/
                
				//sum total invoice amount from cdr between 2 dates 
                
                //get the reseller 2 own total invoice amount and total calls he made (than we will see for his customers )
				$sql4 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0'";
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
                
                //now sum total sell cost for his customers and total calls his childrens makes according to reseller 3 rates 
                $sqlResellerChild = "SELECT SUM(total_reseller_sell_cost) as total_invoice_amount, COUNT(*) as total_calls FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0'";
				$queryResellerChild = $this->db->query($sqlResellerChild);
				$rowResellerChild = $queryResellerChild->row();
                
                $total_reseller_children_invoice_amount   = $rowResellerChild->total_invoice_amount;
				if($total_reseller_children_invoice_amount == '')
				{
					$total_reseller_children_invoice_amount = 0;
				}
				$total_reseller_children_calls_made       = $rowResellerChild->total_calls;
                
                $grand_reseller_inv_amt = $total_invoice_amount + $total_reseller_children_invoice_amount;
                $grand_reseller_tot_calls = $total_calls_made + $total_reseller_children_calls_made;
                
				//generate random invoice number 
				$invoice_number = 'INV-'.rand(1,999).rand(1,9).time();
                
                //due date
				$due_date = $current_date_time + 604800; //generated date + 7 days

				//now we are all good to insert the invoice 
				$sql5 = "INSERT INTO invoices (invoice_id , customer_id, from_date, to_date, total_charges, total_calls, customer_prepaid, invoice_generated_date, due_date, status, parent_id, grand_parent_id) VALUES ('".$invoice_number."', '".$customer_id."', '".$date_from."', '".$current_date_time."', '".$grand_reseller_inv_amt."', '".$grand_reseller_tot_calls."' ,'".$is_prepaid."', '".$current_date_time."', '".$due_date."', '".$status."', '".$customer_parent_id."', '".$customer_grand_parent_id."')";
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
                        <td colspan="2" style="background-color:#777777;color:#fff" align="center">Your Billing Details</td>
                    </tr>

					<tr>
					<td align="center" colspan="2">
					<table width="100%" style="border: 5px solid #dadada;">
				<tbody><tr style="background-color:#777777;color:#fff">
				<td>Destination</td>
                <td>Total Calls</td>
					<td align="center">Total Charges</td>
					</tr>';
                    
                    $sql99 = "SELECT SUM(total_sell_cost) as total_invoice_amount, COUNT(*) as total_calls, destination_number FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' GROUP BY destination_number";
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
                            $e_destination      = $row99->destination_number;
                            
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
					<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$e_grand_total.'</td>
				</tr>';
                
                $tbl .= '</tbody></table>
					</td>
					</tr>';
                
                /***********************************************************/
                //now we have to show his childrens data group by destination 
                 /***********************************************************/   
                 
                 	$tbl .= '<tr>
                        <td height="30px" colspan="2">&nbsp;</td>
					</tr>
                    
                    <tr>
                        <td colspan="2" style="background-color:#777777;color:#fff" align="center">Your Customers Billing Details</td>
                    </tr>

					<tr>
					<td align="center" colspan="2">
					<table width="100%" style="border: 5px solid #dadada;">
				<tbody><tr style="background-color:#777777;color:#fff">
                    <td>Destination</td>
                    <td>Total Calls</td>
					<td align="center">Total Charges</td>
					</tr>';
                    
                    $sql100 = "SELECT SUM(total_reseller_sell_cost) as total_invoice_amount, COUNT(*) as total_calls, destination_number FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' GROUP BY destination_number";
                    $query100 = $this->db->query($sql100);
                    
                    $child_e_grand_total = 0;
                    if($query100->num_rows() > 0)
                    {
                        
                        foreach($query100->result() as $row100)
                        {
                            $child_e_tot_inv_amt  = $row100->total_invoice_amount;
                            if($child_e_tot_inv_amt == '')
                            {
                                $child_e_tot_inv_amt = 0;
                            }
                            $child_e_grand_total = $child_e_grand_total + $child_e_tot_inv_amt;
                            
                            $child_e_total_calls_made  = $row100->total_calls;
                            $child_e_destination      = $row100->destination_number;
                            
                        $tbl .= '<tr style="background-color:#ccc">
                            <td>'.$child_e_destination.'</td>
                            <td>'.$child_e_total_calls_made.'</td>
                                <td align="center">'.$child_e_tot_inv_amt.'</td>
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
					<td align="right" style="font-weight:bold;color:#3172C6">Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$child_e_grand_total.'</td>
				</tr>';
                
                $tbl .= '<tr>
                        <td height="30px" colspan="2">&nbsp;</td>
					</tr>';
                
                $tbl .= '<tr style="background-color:#777777;color:#fff">
                    <td colspan="3" align="center">Grand Total</td>
                    </tr>';
                $tbl .=	'<tr style="background-color:#ccc">
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
					<td align="right" style="font-weight:bold;color:#3172C6">Grand Total</td>
				<td style="font-weight:bold;color:#3172C6;text-align:center">'.$grand_reseller_inv_amt.'</td>
				</tr>
					</tbody></table>
					</td>
					</tr>

					<tr>
					<td height="30px" colspan="2">&nbsp;</td>
					</tr>';
                
                    $terms = settings_any_cell('invoice_terms', customer_any_cell($customer_id, 'parent_id'));
                    if($terms != '')
                    {
                        $tbl .='<tr>
                        <td style="text-align: justify; font-style: italic; color: rgb(136, 136, 136); padding: 55px;" colspan="2">
                        '.$terms.'
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

				$sql6 = "SELECT * FROM cdr WHERE customer_id = '".$customer_id."' AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' ";
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

				$tbl_cdr .= '<table cellspacing="0" cellpadding="1" border="1" width="100%">';
					
                    $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
				<td height="20" align="center" colspan="4">Your CDR Details</td>
					</tr>';
                    
                    $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
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
                
                $tbl_cdr .= '<tr style="background-color:grey; color:#ffffff;">
				<td height="20" align="center" colspan="4">Your Customers CDR Details</td>
					</tr>';
                    
                $sql7 = "SELECT * FROM cdr WHERE (parent_reseller_id = '".$customer_id."' || grand_parent_reseller_id = '".$customer_id."') AND (hangup_cause = 'ALLOTTED_TIMEOUT' || hangup_cause = 'NORMAL_CLEARING') AND billsec > 0 AND created_time > '".sprintf("%.0f", $cdr_from)."' AND created_time <= '".sprintf("%.0f", $cdr_to)."' AND parent_id = '0' ";
				$query7 = $this->db->query($sql7);
                
                if($query7->num_rows() > 0)
				{
					foreach ($query7->result() as $row7)
					{
						$tbl_cdr .=   '<tr>
							<td align="center" height="30">'.date("Y-m-d H:i:s", $row7->created_time/1000000).'</td>
							<td align="center">'.$row7->destination_number.'</td>
							<td align="center">'.$row7->billsec.'</td>
							<td align="center">'.$row7->total_reseller_sell_cost.'</td></tr>';   
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