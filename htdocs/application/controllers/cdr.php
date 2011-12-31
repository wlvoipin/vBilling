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

class Cdr extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('cdr_model');

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
		}
	}

	function index()
	{

		$filter_display_results = 'sec';

		//this is defualt start and end time  
		$startTime = date('Y-m-d');
		$startTime = strtotime($startTime);
		$endTime = time();

		//for filter & search
		$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		$filter_phonenum    = '';
		$filter_caller_ip   = '';
		$filter_customers   = '';
		$filter_groups      = '';
		$filter_gateways    = '';
		$filter_call_type   = '';
		$filter_quick       = '';
		$duration_from      = '';
		$duration_to        = '';
		$search             = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_date_from       = $this->input->get('filter_date_from');
			$filter_date_to         = $this->input->get('filter_date_to');
			$filter_phonenum        = $this->input->get('filter_phonenum');
			$filter_caller_ip       = $this->input->get('filter_caller_ip');
			$filter_customers       = $this->input->get('filter_customers');
			$filter_groups          = $this->input->get('filter_groups');
			$filter_gateways        = $this->input->get('filter_gateways');
			$filter_call_type       = $this->input->get('filter_call_type');
			$filter_quick           = $this->input->get('filter_quick');
			$duration_from          = $this->input->get('duration_from');
			$duration_to            = $this->input->get('duration_to');
			$filter_display_results = $this->input->get('filter_display_results');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Your Search Criteria";
		}

		if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data['filter_date_from']           = $filter_date_from;
		$data['filter_date_to']             = $filter_date_to;
		$data['filter_phonenum']            = $filter_phonenum;
		$data['filter_caller_ip']           = $filter_caller_ip;
		$data['filter_customers']           = $filter_customers;
		$data['filter_groups']              = $filter_groups;
		$data['filter_gateways']            = $filter_gateways;
		$data['filter_call_type']           = $filter_call_type;
		$data['filter_quick']               = $filter_quick;
		$data['duration_from']              = $duration_from;
		$data['duration_to']                = $duration_to;
		$data['filter_display_results']     = $filter_display_results;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'cdr/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_phonenum='.$filter_phonenum.'&filter_caller_ip='.$filter_caller_ip.'&filter_customers='.$filter_customers.'&filter_groups='.$filter_groups.'&filter_gateways='.$filter_gateways.'&filter_call_type='.$filter_call_type.'&filter_display_results='.$filter_display_results.'&filter_quick='.$filter_quick.'&duration_from='.$duration_from.'&duration_to='.$duration_to.'';
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

		$data['count'] = $this->cdr_model->get_cdr_main_count($filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_customers, $filter_groups, $filter_gateways, $filter_call_type, $duration_from, $duration_to);
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

		$data['cdr']            =   $this->cdr_model->get_all_cdr_data($config['per_page'],$config['uri_segment'],$filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_customers, $filter_groups, $filter_gateways, $filter_call_type, $duration_from, $duration_to);
		$data['page_name']		=	'view_cdr_data';
		$data['selected']		=	'cdr';
		$data['sub_selected']   =   'list_cdr';
		$data['page_title']		=	'CDR DETAILS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/cdr_sub_menu';
		$data['main_content']	=	'cdr/cdr_view';
		$this->load->view('default/template',$data);
	}

	function gateways_stats()
	{
		$filter_display_results = 'min';

		//this is defualt start and end time  
		$startTime = time() - 86400; //last 24hrs 
		$endTime = time();

		//for filter & search
		$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		$filter_gateways    = '';
		$filter_call_type   = 'answered';
		$search             = '';

		if($this->input->get('searchFilter'))
		{
			$filter_date_from       = $this->input->get('filter_date_from');
			$filter_date_to         = $this->input->get('filter_date_to');
			$filter_gateways        = $this->input->get('filter_gateways');
			$filter_call_type       = $this->input->get('filter_call_type');
			$search                 = $this->input->get('searchFilter');
			$filter_display_results = $this->input->get('filter_display_results');
		}

		if($filter_call_type   == '')
		{
			$filter_call_type   = 'answered';
		}

		if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data['filter_date_from']           = $filter_date_from;
		$data['filter_date_to']             = $filter_date_to;
		$data['filter_gateways']            = $filter_gateways;
		$data['filter_call_type']           = $filter_call_type;
		$data['filter_display_results']     = $filter_display_results;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'cdr/gateways_stats/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_gateways='.$filter_gateways.'&filter_call_type='.$filter_call_type.'&filter_display_results='.$filter_display_results.'';
		$config['page_query_string'] = TRUE;

		$config['num_links'] = 2;

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

		$data['count'] = $this->cdr_model->get_all_gateways_count($filter_gateways);
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

		$data['all_gateways']   =   $this->cdr_model->get_all_gateways($config['per_page'],$config['uri_segment'],$filter_gateways);
		$data['page_name']		=	'view_gateways_stats';
		$data['selected']		=	'cdr';
		$data['sub_selected']   =   'gateways_stats';
		$data['page_title']		=	'GATEWAYS STATS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/cdr_sub_menu';
		$data['main_content']	=	'cdr/gateways_stats_view';
		$this->load->view('default/template',$data);
	}

	function customer_stats()
	{
		$filter_display_results = 'min';

		$filter_customers   = '';
		$search             = '';

		if($this->input->get('searchFilter'))
		{
			$filter_customers       = $this->input->get('filter_customers');
			$search                 = $this->input->get('searchFilter');
			$filter_display_results = $this->input->get('filter_display_results');
		}

		if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		$data['filter_customers']           = $filter_customers;
		$data['filter_display_results']     = $filter_display_results;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'cdr/customer_stats/?searchFilter='.$search.'&filter_customers='.$filter_customers.'&filter_display_results='.$filter_display_results.'';
		$config['page_query_string'] = TRUE;

		$config['num_links'] = 2;

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

		$data['count'] = $this->cdr_model->get_all_customers_count($filter_customers);
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

		$data['customers']      =   $this->cdr_model->get_all_customers($config['per_page'],$config['uri_segment'], $filter_customers);
		$data['page_name']		=	'customer_stats';
		$data['selected']		=	'cdr';
		$data['sub_selected']   =   'customer_stats';
		$data['page_title']		=	'CUSTOMER STATISTICS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/cdr_sub_menu';
		$data['main_content']	=	'cdr/customer_stats_view';
		$this->load->view('default/template',$data);
	}

	function call_destination()
	{
		$filter_display_results = 'min';

		//this is defualt start and end time  
		$startTime = time() - 86400; //last 24hrs 
		$endTime = time();

		//for filter & search
		$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		$filter_countries   = '';
		$search             = '';

		if($this->input->get('searchFilter'))
		{
			$filter_date_from       = $this->input->get('filter_date_from');
			$filter_date_to         = $this->input->get('filter_date_to');
			$filter_countries       = $this->input->get('filter_countries');
			$search                 = $this->input->get('searchFilter');
			$filter_display_results = $this->input->get('filter_display_results');
		}

		if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data['filter_date_from']           = $filter_date_from;
		$data['filter_date_to']             = $filter_date_to;
		$data['filter_countries']           = $filter_countries;
		$data['filter_display_results']     = $filter_display_results;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'cdr/call_destination/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_countries='.$filter_countries.'&filter_display_results='.$filter_display_results.'';
		$config['page_query_string'] = TRUE;

		$config['num_links'] = 2;

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

		$data['count'] = $this->cdr_model->get_all_countries_count($filter_countries, $filter_date_from, $filter_date_to);
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

		$data['countries']      =   $this->cdr_model->get_all_countries($config['per_page'],$config['uri_segment'], $filter_countries, $filter_date_from, $filter_date_to);
		$data['page_name']		=	'call_destination';
		$data['selected']		=	'cdr';
		$data['sub_selected']   =   'call_destination';
		$data['page_title']		=	'CALL DESTINATION DETAILS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/cdr_sub_menu';
		$data['main_content']	=	'cdr/call_destination_view';
		$this->load->view('default/template',$data);
	}

	function get_calculated_date_time()
	{
		$value = $this->input->post('val');

		$return_val = '';

		$current_date_time = date('Y-m-d H:i:s');
		$curr_date_starting_from_12_Am = "".date('Y-m-d')." 00:00:00";

		if($value == 'today' || $value == '')
		{
			$return_val = $curr_date_starting_from_12_Am.'|'.$current_date_time;
		}
		else if($value == 'last_hour')
		{
			$time = time();
			$last_hour = $time - 3600;
			$last_hour = date('Y-m-d H:i:s', $last_hour);
			$return_val = $last_hour.'|'.$current_date_time;
		}
		else if($value == 'last_24_hour')
		{
			$time = time();
			$last_24_hour = $time - 86400;
			$last_24_hour = date('Y-m-d H:i:s', $last_24_hour);
			$return_val = $last_24_hour.'|'.$current_date_time;
		}
		echo $return_val;
	}

	function export_pdf()
	{
		$startTime = date('Y-m-d');
		$startTime = strtotime($startTime);
		$endTime = time();

		$filter_date_from       = $this->input->get('filter_date_from');
		$filter_date_to         = $this->input->get('filter_date_to');
		$filter_phonenum        = $this->input->get('filter_phonenum');
		$filter_caller_ip       = $this->input->get('filter_caller_ip');
		$filter_customers       = $this->input->get('filter_customers');
		$filter_groups          = $this->input->get('filter_groups');
		$filter_gateways        = $this->input->get('filter_gateways');
		$filter_call_type       = $this->input->get('filter_call_type');
		$filter_quick           = $this->input->get('filter_quick');
		$duration_from          = $this->input->get('duration_from');
		$duration_to            = $this->input->get('duration_to');
		$filter_display_results = $this->input->get('filter_display_results');

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data_cdr   =   $this->cdr_model->export_cdr_data($filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_customers, $filter_groups, $filter_gateways, $filter_call_type, $duration_from, $duration_to);

		if($data_cdr->num_rows() > 0)
		{
			$this->load->library('pdf');

			// set document information
			$this->pdf->SetSubject('CDR Export');
			$this->pdf->SetKeywords('DigitalLinx, CDR, export');

			// add a page
			$this->pdf->AddPage();

			$this->pdf->SetFont('helvetica', '', 6);

			$tbl = '<table cellspacing="0" cellpadding="1" border="1" width="100%">
				<tr style="background-color:grey; color:#ffffff;">
			<td height="20" width="8%" align="center">Date/Time</td>
				<td width="7%" align="center">Destination</td>
				<td width="7%" align="center">Bill Duration</td>
				<td width="7%" align="center">Hangup Cause</td>
				<td width="7%" align="center">IP Address</td>
				<td width="7%" align="center">Username</td>
				<td width="7%" align="center">Sell Rate</td>
				<td width="7%" align="center">Sell Init Block</td>
				<td width="7%" align="center">Cost Rate</td>
				<td width="7%" align="center">Buy Init Block</td>
				<td width="7%" align="center">Total Charges</td>
				<td width="7%" align="center">Total Cost</td>
				<td width="7%" align="center">Margin</td>
				<td width="7%" align="center">Markup</td>
				</tr>';
			foreach ($data_cdr->result() as $row)
			{
				$tbl .=   '<tr>
					<td align="center" height="30">'.date("Y-m-d H:i:s", $row->created_time/1000000).'</td>
					<td align="center">'.$row->destination_number.'</td>
					<td align="center">'.$row->billsec.'</td>
					<td align="center">'.$row->hangup_cause.'</td>
					<td align="center">'.$row->network_addr.'</td>
					<td align="center">'.$row->username.'</td>
					<td align="center">'.$row->sell_rate.'</td>
					<td align="center">'.$row->sell_initblock.'</td>
					<td align="center">'.$row->cost_rate.'</td>
					<td align="center">'.$row->buy_initblock.'</td>';

				if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {
					$tbl .=  '<td align="center">'.$row->total_sell_cost.'</td>';
				} else {
					$tbl .= '<td align="center">0</td>';
				}

				if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {
					$tbl .=  '<td align="center">'.$row->total_buy_cost.'</td>';
				} else {
					$tbl .= '<td align="center">0</td>';
				}

				$tbl .= '<td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>';
			}

			$tbl .=  '</table>';

			$this->pdf->writeHTML($tbl, true, false, false, false, '');

			//Close and output PDF document
			$this->pdf->Output('cdr.pdf', 'I');
		}
		else
		{
			redirect('cdr/');
		}
	}

	function export_excel()
	{
		$startTime = date('Y-m-d');
		$startTime = strtotime($startTime);
		$endTime = time();

		$filter_date_from       = $this->input->get('filter_date_from');
		$filter_date_to         = $this->input->get('filter_date_to');
		$filter_phonenum        = $this->input->get('filter_phonenum');
		$filter_caller_ip       = $this->input->get('filter_caller_ip');
		$filter_customers       = $this->input->get('filter_customers');
		$filter_groups          = $this->input->get('filter_groups');
		$filter_gateways        = $this->input->get('filter_gateways');
		$filter_call_type       = $this->input->get('filter_call_type');
		$filter_quick           = $this->input->get('filter_quick');
		$duration_from          = $this->input->get('duration_from');
		$duration_to            = $this->input->get('duration_to');
		$filter_display_results = $this->input->get('filter_display_results');

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data_cdr   =   $this->cdr_model->export_cdr_data($filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_customers, $filter_groups, $filter_gateways, $filter_call_type, $duration_from, $duration_to);

		if($data_cdr->num_rows() > 0)
		{
			$this->load->library('Spreadsheet_Excel_Writer');
			$workbook = new Spreadsheet_Excel_Writer();

			$format_bold =& $workbook->addFormat();
			$format_bold->setBold();

			$format_head =& $workbook->addFormat();
			$format_head->setBold();
			$format_head->setPattern(1);
			$format_head->setFgColor('red');
			$format_head->setBgColor('white');
			$format_head->setAlign('merge');

			$format_title =& $workbook->addFormat();
			$format_title->setBold();
			$format_title->setPattern(1);
			$format_title->setFgColor('white');
			$format_title->setBgColor('grey');
			$format_title->setAlign('merge');

			$format_cell =& $workbook->addFormat();
			$format_cell->setAlign('merge');

			$worksheet =& $workbook->addWorksheet();
			$worksheet->write(0, 0, "CDR EXPORT :: DigitalLinx.com", $format_head);
			// Couple of empty cells to make it look better
			$worksheet->write(0, 1, "", $format_head);
			$worksheet->write(0, 2, "", $format_head);
			$worksheet->write(0, 3, "", $format_head);
			$worksheet->write(0, 4, "", $format_head);
			$worksheet->write(0, 5, "", $format_head);
			$worksheet->write(0, 6, "", $format_head);
			$worksheet->write(0, 7, "", $format_head);
			$worksheet->write(0, 8, "", $format_head);
			$worksheet->write(0, 9, "", $format_head);
			$worksheet->write(0, 10, "", $format_head);
			$worksheet->write(0, 11, "", $format_head);
			$worksheet->write(0, 12, "", $format_head);
			$worksheet->write(0, 13, "", $format_head);
			$worksheet->write(0, 14, "", $format_head);
			$worksheet->write(0, 15, "", $format_head);
			$worksheet->write(0, 16, "", $format_head);
			$worksheet->write(0, 17, "", $format_head);
			$worksheet->write(0, 18, "", $format_head);
			$worksheet->write(0, 19, "", $format_head);
			$worksheet->write(0, 20, "", $format_head);
			$worksheet->write(0, 21, "", $format_head);
			$worksheet->write(0, 22, "", $format_head);

			$worksheet->write(1, 0, "Date/Time", $format_title);
			$worksheet->write(1, 1, "", $format_title);
			$worksheet->write(1, 2, "Destination", $format_title);
			$worksheet->write(1, 3, "", $format_title);
			$worksheet->write(1, 4, "Bill Duration", $format_title);
			$worksheet->write(1, 5, "", $format_title);
			$worksheet->write(1, 6, "Hangup Cause", $format_title);
			$worksheet->write(1, 7, "", $format_title);
			$worksheet->write(1, 8, "IP Address", $format_title);
			$worksheet->write(1, 9, "", $format_title);
			$worksheet->write(1, 10, "Username", $format_title);
			$worksheet->write(1, 11, "", $format_title);
			$worksheet->write(1, 12, "Sell Rate", $format_title);
			$worksheet->write(1, 13, "", $format_title);
			$worksheet->write(1, 14, "Sell Init Block", $format_title);
			$worksheet->write(1, 15, "", $format_title);
			$worksheet->write(1, 16, "Cost Rate", $format_title);
			$worksheet->write(1, 17, "", $format_title);
			$worksheet->write(1, 18, "Buy Init Block", $format_title);
			$worksheet->write(1, 19, "", $format_title);
			$worksheet->write(1, 20, "Total Charges", $format_title);
			$worksheet->write(1, 21, "", $format_title);
			$worksheet->write(1, 22, "Total Cost", $format_title);
			$worksheet->write(1, 23, "", $format_title);
			$worksheet->write(1, 24, "Margin", $format_title);
			$worksheet->write(1, 25, "", $format_title);
			$worksheet->write(1, 26, "Markup", $format_title);
			$worksheet->write(1, 27, "", $format_title);

			$count = 2;
			foreach($data_cdr->result() as $row)
			{
				$worksheet->write($count, 0, "".date("Y-m-d H:i:s", $row->created_time/1000000)."", $format_cell);
				$worksheet->write($count, 1, "", $format_cell);
				$worksheet->write($count, 2, "".$row->destination_number."", $format_cell);
				$worksheet->write($count, 3, "", $format_cell);
				$worksheet->write($count, 4, "".$row->billsec."", $format_cell);
				$worksheet->write($count, 5, "", $format_cell);
				$worksheet->write($count, 6, "".$row->hangup_cause."", $format_cell);
				$worksheet->write($count, 7, "", $format_cell);
				$worksheet->write($count, 8, "".$row->network_addr."", $format_cell);
				$worksheet->write($count, 9, "", $format_cell);
				$worksheet->write($count, 10, "".$row->username."", $format_cell);
				$worksheet->write($count, 11, "", $format_cell);
				$worksheet->write($count, 12, "".$row->sell_rate."", $format_cell);
				$worksheet->write($count, 13, "", $format_cell);
				$worksheet->write($count, 14, "".$row->sell_initblock."", $format_cell);
				$worksheet->write($count, 15, "", $format_cell);
				$worksheet->write($count, 16, "".$row->cost_rate."", $format_cell);
				$worksheet->write($count, 17, "", $format_cell);
				$worksheet->write($count, 18, "".$row->buy_initblock."", $format_cell);
				$worksheet->write($count, 19, "", $format_cell);

				if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {
					$worksheet->write($count, 20, "".$row->total_sell_cost."", $format_cell);
					$worksheet->write($count, 21, "", $format_cell);
				}
				else{
					$worksheet->write($count, 20, "0", $format_cell);
					$worksheet->write($count, 21, "", $format_cell);
				}

				if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {
					$worksheet->write($count, 22, "".$row->total_buy_cost."", $format_cell);
					$worksheet->write($count, 23, "", $format_cell);
				}
				else {
					$worksheet->write($count, 22, "0", $format_cell);
					$worksheet->write($count, 23, "", $format_cell);
				}

				$worksheet->write($count, 24, "-", $format_cell);
				$worksheet->write($count, 25, "", $format_cell);
				$worksheet->write($count, 26, "-", $format_cell);
				$worksheet->write($count, 27, "", $format_cell);


				$count = $count + 1;
			}
			$workbook->send('test.xls');
			$workbook->close();
		}
		else
		{
			redirect('cdr/');
		}
	}

	function export_csv()
	{
		$startTime = date('Y-m-d');
		$startTime = strtotime($startTime);
		$endTime = time();

		$filter_date_from       = $this->input->get('filter_date_from');
		$filter_date_to         = $this->input->get('filter_date_to');
		$filter_phonenum        = $this->input->get('filter_phonenum');
		$filter_caller_ip       = $this->input->get('filter_caller_ip');
		$filter_customers       = $this->input->get('filter_customers');
		$filter_groups          = $this->input->get('filter_groups');
		$filter_gateways        = $this->input->get('filter_gateways');
		$filter_call_type       = $this->input->get('filter_call_type');
		$filter_quick           = $this->input->get('filter_quick');
		$duration_from          = $this->input->get('duration_from');
		$duration_to            = $this->input->get('duration_to');
		$filter_display_results = $this->input->get('filter_display_results');

		if($filter_date_from == '')
		{
			$filter_date_from   = date('Y-m-d H:i:s', $startTime);
		}
		else
		{
			if (!checkdateTime($filter_date_from))
			{
				$filter_date_from   = date('Y-m-d H:i:s', $startTime);
			}
		}

		if($filter_date_to == '')
		{
			$filter_date_to     = date('Y-m-d H:i:s', $endTime);
		}
		else
		{
			if (!checkdateTime($filter_date_to))
			{
				$filter_date_to   = date('Y-m-d H:i:s', $endTime);
			}
		}

		$data_cdr   =   $this->cdr_model->export_cdr_data_csv($filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_customers, $filter_groups, $filter_gateways, $filter_call_type, $duration_from, $duration_to);

		if($data_cdr->num_rows() > 0)
		{
			$headers = array('Date/Time', 'Destination', 'Bill Duration', 'Hangup Cause', 'IP Address', 'Username', 'Sell Rate', 'Sell Init Block', 'Cost Rate','Buy Init Block', 'Total Charges', 'Total Cost', 'Margin', 'Markup');

			$fp = fopen('php://output', 'w');
			if ($fp) {
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="export.csv"');
				header('Pragma: no-cache');
				header('Expires: 0');
				fputcsv($fp, $headers);

				foreach ($data_cdr->result_array() as $row)
				{
					print '"' . stripslashes(implode('","',$row)) . "\"\n";
				}
				die;
			}
		}
		else
		{
			redirect('cdr/');
		}
	}
}
