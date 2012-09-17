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

class Customer extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('customer_model');
		$this->load->model('groups_model');
		$this->load->model('manage_accounts_model');
        $this->load->model('billing_model');
		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}
		else
		{
			if($this->session->userdata('user_type') != 'customer')
			{
				redirect ('home/');
			}
		}
	}

	function index()
	{
		$data['page_name']		=	'customer_dashboard';
		$data['selected']		=	'customer_dashboard';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'DASHBOARD';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/dashboard';
		$this->load->view('default/template',$data);
	}

	function my_account()
	{
		$data['page_name']		=	'my_account';
		$data['selected']		=	'';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'MY ACCOUNT';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/my_account';
		$this->load->view('default/template',$data);
	}

	function update_my_account()
	{
		$data['username']       = $this->db->escape($this->input->post('username'));
		$data['old_username']   = $this->db->escape($this->input->post('old_username'));

		$check_username_availability_count = 0;

		if($data['username'] != $data['old_username']) //if entered username not equal to previous username 
        {
            $check_username_availability = $this->manage_accounts_model->check_username_availability($this->input->post('username'));
            if($check_username_availability->num_rows() > 0) //username already in use
            {
                $check_username_availability_count = 1;
            }
        }
		
        $data['pass']           = $this->input->post('password');
		$data['password']       = md5($this->input->post('password'));

		if($check_username_availability_count == 1)
		{
			$this->session->set_flashdata('error','Username already taken. Try different username');
		}
		else
		{
			if($data['username'] != $data['old_username']) //if entered username not equal to previous username 
            {
                $this->customer_model->update_user_username($data);
            }

			if($data['pass'] != '') //if user want to change password
			{
				$this->customer_model->update_user_password($data);
			}
            
            if($data['username'] != $data['old_username']) //if entered username not equal to previous username 
            {
                //update session variable 
                $data = array(
                    'username' => $this->input->post('username')
                    );
                $this->session->set_userdata($data);
            }

			$this->session->set_flashdata('success','Information updated successfully');
		}
	}

	//edit customer view 
	function edit_customer()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data['customer']       =   $this->customer_model->get_single_customer($customer_id);
		$data['customer_id']    =   $customer_id;

		$data['page_name']		=	'edit_customer';
		$data['selected']		=	'customers_info';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE CUSTOMER';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/edit_customer_view';
		$this->load->view('default/template',$data);
	}

	//update customer db
	function update_customer_db()
	{
		$data['customer_id']    = $this->input->post('customer_id');

		$data['firstname']      = $this->input->post('firstname');
		$data['lastname']       = $this->input->post('lastname');
		$data['companyname']    = $this->input->post('companyname');
		$data['address']        = $this->input->post('address');
		$data['city']           = $this->input->post('city');
		$data['state']          = $this->input->post('state');
		$data['zipcode']        = $this->input->post('zipcode');
		$data['country']        = $this->input->post('country');
		$data['prefix']         = $this->input->post('prefix');
		$data['phone']          = $this->input->post('phone');
		$data['timezone']       = $this->input->post('timezone');

		$update = $this->customer_model->update_restricted_customer_db($data);
	}

	//customer rate view
	function customer_rates()
	{
		$customer_id = $this->session->userdata('customer_id');
		$filter_display_results = 'min';

		//for filter & search
		$filter_start_date   = '';
		$filter_end_date     = '';
		$filter_carriers     = '';
		$filter_rate_type    = '';
        $filter_sort         = '';
		$search              = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_start_date      = $this->input->get('filter_start_date');
			$filter_end_date        = $this->input->get('filter_end_date');
			$filter_carriers        = $this->input->get('filter_carriers');
			$filter_rate_type       = $this->input->get('filter_rate_type');
			$filter_display_results = $this->input->get('filter_display_results');
            $filter_sort            = $this->input->get('filter_sort');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Search Criteria";
		}

		if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		if (!checkdateTime($filter_start_date))
		{
			$filter_start_date   = '';
		}

		if (!checkdateTime($filter_end_date))
		{
			$filter_end_date   = '';
		}

		$data['filter_start_date']          = $filter_start_date;
		$data['filter_end_date']            = $filter_end_date;
		$data['filter_carriers']            = $filter_carriers;
		$data['filter_rate_type']           = $filter_rate_type;
		$data['filter_display_results']     = $filter_display_results;
        $data['filter_sort']                = $filter_sort;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'customer/customer_rates/?searchFilter='.$search.'&filter_start_date='.$filter_start_date.'&filter_end_date='.$filter_end_date.'&filter_carriers='.$filter_carriers.'&filter_rate_type='.$filter_rate_type.'&filter_display_results='.$filter_display_results.'&filter_sort='.$filter_sort.'';
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




		$customer_group_id      =   $this->customer_model->customer_any_cell($customer_id, 'customer_rate_group');

		if($customer_group_id != '' && $customer_group_id != '0')
		{
			$customer_group_table   =   $this->groups_model->group_any_cell($customer_group_id, 'group_rate_table');
			$data['count']          =   $this->customer_model->customer_rates_count($customer_group_table, $filter_start_date, $filter_end_date, $filter_carriers, $filter_rate_type);
			$data['rates']          =   $this->customer_model->customer_rates($config['per_page'], $config['uri_segment'], $customer_group_table, $filter_start_date, $filter_end_date, $filter_carriers, $filter_rate_type, $filter_sort);
		}
		else
		{
			$data['rates']  = "not_found";
			$data['count']  = 0;
		}


		$config['total_rows'] = $data['count'];
		$this->pagination->initialize($config);

		$data['msg_records_found'] = "".$data['count']."&nbsp;".$msg_records_found."";




		$data['customer_id']    =   $customer_id;
		$data['tbl_name']       =   $customer_group_table;

		$data['page_name']		=	'rates_customer';
		$data['selected']		=	'customers_rate';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'CUSTOMER RATES';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/rate_customer_view';
		$this->load->view('default/template',$data);
	}

	function get_country_prefix()
	{
		$id = $this->input->post('id');
		echo country_any_cell($id, 'countryprefix');
	}
	/*
	//enable or disable customer rate 
	function enable_disable_customer_rate()
	{
	$data['rate_id']            = $this->input->post('rate_id');
	$data['status']             = $this->input->post('status');
	$data['tbl_name']           = $this->input->post('tbl_name');

if($data['tbl_name'] != '')
{
$this->customer_model->enable_disable_customer_rate($data);
}
}
*/

//*********************** CUSTOMER ACL NODES FUNCTION ****************************************//

function customer_acl_nodes()
{
	$customer_id = $this->session->userdata('customer_id');	
	$data['acl_nodes']      =   $this->customer_model->customer_acl_nodes($customer_id);
	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_acl_nodes';
	$data['selected']		=	'customers_ip';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'CUSTOMER ACL NODES';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'customer/ip_customer_view';
	$this->load->view('default/template',$data);
}

function new_acl_node()
{
	$customer_id = $this->session->userdata('customer_id');
	$used_acl_nodes_count   = restricted_customer_acl_nodes_count($customer_id);
	$limit_of_acl_nodes     = customer_access_any_cell($customer_id, 'total_acl_nodes');

	if($used_acl_nodes_count < $limit_of_acl_nodes)
	{
		$data['customer_id']    =   $customer_id;

		$data['page_name']		=	'new_acl_node';
		$data['selected']		=	'new_acl_node';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'NEW ACL NODE';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/new_acl_node_view';
		$this->load->view('default/template',$data);
	}
	else
	{
		redirect ('customer/customer_acl_nodes');
	}
}

function insert_new_acl_node()
{
	$customer_id = $this->input->post('customer_id');
	$ip = $this->input->post('ip');
	$cidr = $this->input->post('cidr');
	$this->customer_model->insert_new_acl_node($customer_id, $ip, $cidr);
	$this->session->set_flashdata('success','ACL Node added successfully.');

	//relaod acl
	$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
	$cmd = "api reloadacl";
	$response = $this->esl->event_socket_request($fp, $cmd);
	echo $response; 
	fclose($fp);
}

function edit_acl_node($node_id = '')
{
	$customer_id = $this->session->userdata('customer_id');
	$data['customer_id']    =   $customer_id;
	$data['acl_node_id']    =   $node_id;
	$data['acl_node']       =   $this->customer_model->customer_acl_nodes_single($node_id, $customer_id);

	if($data['acl_node']->num_rows() == 0)
	{
		redirect('customer/customer_acl_nodes'); 
	}
	$data['page_name']		=	'edit_acl_node';
	$data['selected']		=	'';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'UPDATE ACL NODE';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'customer/edit_acl_node_view';
	$this->load->view('default/template',$data);
}

function update_acl_node_db()
{
	$node_id = $this->input->post('node_id');
	$ip = $this->input->post('ip');
	$cidr = $this->input->post('cidr');
	$this->customer_model->update_acl_node_db($node_id, $ip, $cidr);

	//relaod acl
	$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
	$cmd = "api reloadacl";
	$response = $this->esl->event_socket_request($fp, $cmd);
	echo $response; 
	fclose($fp);
}

function delete_acl_node()
{
	$node_id = $this->input->post('node_id');
	$this->customer_model->delete_acl_node($node_id);
	$this->session->set_flashdata('success','ACL Node deleted successfully.');

	//relaod acl
	$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
	$cmd = "api reloadacl";
	$response = $this->esl->event_socket_request($fp, $cmd);
	echo $response; 
	fclose($fp);
}

function change_acl_node_type()
{
	$node_id = $this->input->post('node_id');
	$value = $this->input->post('value');
	$this->customer_model->change_acl_node_type($node_id, $value);

	//relaod acl
	$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
	$cmd = "api reloadacl";
	$response = $this->esl->event_socket_request($fp, $cmd);
	echo $response; 
	fclose($fp);
}

//**************************** CUSTOMER SIP ACCESS FUNCTION *************************************//

function sip_access()
{
	$customer_id = $this->session->userdata('customer_id');
	$data['sip_access']     =   $this->customer_model->customer_sip_access($customer_id);
	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_sip_access';
	$data['selected']		=	'sip_access';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'CUSTOMER SIP CREDENTIALS';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'customer/sip_customer_view';
	$this->load->view('default/template',$data);
}

function new_sip_access()
{
	$customer_id = $this->session->userdata('customer_id');
	$used_sip_acc_count  = restricted_customer_sip_acc_count($customer_id);
	$limit_of_sip_acc = customer_access_any_cell($customer_id, 'total_sip_accounts');

	if($used_sip_acc_count < $limit_of_sip_acc)
	{
		$check = 0;
		do {
			$username = rand(1,999).rand(1,999);
			$check_username_existis = $this->customer_model->check_sip_username_existis($username);

			if($check_username_existis == 0)
			{
				$check = 1;
			}
		} while ($check == 0);

		$data['customer_id']    =   $customer_id;
		$data['username']       =   $username;
		$data['password']       =   rand(1,999).rand(1,999).rand(1,99);

		$data['page_name']		=	'new_sip_access';
		$data['selected']		=	'sip_access';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'NEW SIP CREDENTIALS';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'customer/new_sip_view';
		$this->load->view('default/template',$data);
	}
	else
	{
		redirect ('customer/sip_access');
	}
}

function reset_sip_password()
{
    $password   =   rand(1,999).rand(1,999).rand(1,99);
    $record_id  = $this->input->post('record_id');
    
    $sql = "SELECT * FROM directory WHERE id = '".$record_id."'";
    $query = $this->db->query($sql);
    $row = $query->row();
    
    $domain = $row->domain;
    $username = $row->username;
    
    $new_password = $username.':'.$domain.':'.$password;
	$new_password = md5($new_password);
    
    $sql2 = "UPDATE directory_params SET param_value = '".$new_password."' WHERE directory_id = '".$record_id."' ";
    $query2 = $this->db->query($sql2); 
    
    echo $password;
}

function insert_new_sip_access()
{
	$customer_id   = $this->input->post('customer_id');
	$username      = $this->input->post('username');
	$password      = $this->input->post('password');
	$cid           = $this->input->post('cid');
	// $did_id        = $this->input->post('did_id');
	// $forwardnumber = $this->input->post('forwardnumber');
	// $forwardip     = $this->input->post('forwardip');            

	$getdomain      =   $this->input->post('sip_ip');
	$explode = explode('|', $getdomain);

	$domain     = $explode[0];
	$sofia_id   = $explode[1];

	$this->customer_model->insert_new_sip_access($customer_id, $username, $password, $domain, $sofia_id, $cid);// , $did_id, $forwardnumber, $forwardip);
	$this->session->set_flashdata('success','SIP account added successfully.');
}

/*
function edit_sip_access($id, $customer_id)
{
$data['sip_access']     =   $this->customer_model->single_sip_access_data($id);
$data['customer_id']    =   $customer_id;
$data['record_id']      =   $id;

$data['page_name']		=	'edit_sip_access';
$data['selected']		=	'sip_access';
$data['sub_selected']   =   '';
$data['page_title']		=	'UPDATE SIP CREDENTIALS';
$data['main_menu']	    =	'';
$data['sub_menu']	    =	'';
$data['main_content']	=	'customer/edit_sip_view';
$this->load->view('default/template',$data);
}

function update_sip_access()
{
$customer_id    =   $this->input->post('customer_id');
$record_id      =   $this->input->post('record_id');

$username       =   $this->input->post('username');
$old_username   =   $this->input->post('old_username');
$password       =   $this->input->post('password');
$domain         =   $this->input->post('sip_ip');

if($old_username != $username)
{
$check_username_availability = $this->customer_model->check_sip_username_existis($username);

if($check_username_availability == 0)
{
$this->customer_model->update_sip_access($record_id, $username, $password, $domain);
}
else
{
echo "username_not_available";
exit;
}
}
else
{
$this->customer_model->update_sip_access($record_id, $username, $password, $domain);
}
}*/

function delete_sip_access()
{
	$record_id      =   $this->input->post('record_id');

	$this->customer_model->delete_sip_access($record_id);
	$this->session->set_flashdata('success','SIP Credentials Deleted Successfully.');
}

// **************************** CDR FUNCTIONS ****************************//
function customer_cdr()
{
	$customer_id = $this->session->userdata('customer_id');
	$filter_display_results = 'min';

	//this is defualt start and end time  
	$startTime = date('Y-m-d');
	$startTime = strtotime($startTime);
	$endTime = time();

	//for filter & search
	$filter_date_from   = date('Y-m-d H:i:s', $startTime);
	$filter_date_to     = date('Y-m-d H:i:s', $endTime);
	$filter_phonenum    = '';
	$filter_caller_ip   = '';
	$filter_gateways    = '';
	$filter_call_type   = '';
    $filter_quick       = '';
    $duration_from      = '';
    $duration_to        = '';
    $filter_sort        = '';
	$search             = '';

	$msg_records_found = "Records Found";

	if($this->input->get('searchFilter'))
	{
		$filter_date_from       = $this->input->get('filter_date_from');
		$filter_date_to         = $this->input->get('filter_date_to');
		$filter_phonenum        = $this->input->get('filter_phonenum');
		$filter_caller_ip       = $this->input->get('filter_caller_ip');
		$filter_gateways        = $this->input->get('filter_gateways');
		$filter_call_type       = $this->input->get('filter_call_type');
		$filter_display_results = $this->input->get('filter_display_results');
        $filter_quick           = $this->input->get('filter_quick');
        $duration_from          = $this->input->get('duration_from');
        $duration_to            = $this->input->get('duration_to');
        $filter_sort            = $this->input->get('filter_sort');
		$search                 = $this->input->get('searchFilter');
		$msg_records_found      = "Records Found Based On Search Criteria";
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
	$data['filter_gateways']            = $filter_gateways;
	$data['filter_call_type']           = $filter_call_type;
	$data['filter_display_results']     = $filter_display_results;
    $data['filter_quick']               = $filter_quick;
    $data['duration_from']              = $duration_from;
    $data['duration_to']                = $duration_to;
    $data['filter_sort']                = $filter_sort;

	//for pagging set information
	$this->load->library('pagination');
	$config['per_page'] = '20';
	$config['base_url'] = base_url().'customer/customer_cdr/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_phonenum='.$filter_phonenum.'&filter_caller_ip='.$filter_caller_ip.'&filter_gateways='.$filter_gateways.'&filter_call_type='.$filter_call_type.'&filter_display_results='.$filter_display_results.'&filter_quick='.$filter_quick.'&duration_from='.$duration_from.'&duration_to='.$duration_to.'&filter_sort='.$filter_sort.'';
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

	$data['count'] = $this->customer_model->customer_cdr_count($customer_id, $filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_gateways, $filter_call_type, $duration_from, $duration_to);
	$config['total_rows'] = $data['count'];

	if(isset($_GET['per_page']))
	{
		$config['uri_segment'] = $_GET['per_page'];
	}
	else
	{
		$config['uri_segment'] = '';
	}

	$this->pagination->initialize($config);

	$data['msg_records_found'] = "".$data['count']."&nbsp;".$msg_records_found."";

	$data['cdr']            =   $this->customer_model->customer_cdr($config['per_page'],$config['uri_segment'], $customer_id, $filter_date_from, $filter_date_to, $filter_phonenum, $filter_caller_ip, $filter_gateways, $filter_call_type, $duration_from, $duration_to ,$filter_sort);

	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_cdr_data';
	$data['selected']		=	'customers_cdr';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'CUSTOMER CDR';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'customer/cdr_customer_view';
	$this->load->view('default/template',$data);
}

//**************************** MANAGE BALANCE ************************************//
function manage_balance()
{
	$customer_id            =   $this->session->userdata('customer_id');
	$data['history']        =   $this->customer_model->customer_balance_history($customer_id);
	$data['current_balance']        =   $this->customer_model->customer_balance($customer_id);
	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_manage_balance';
	$data['selected']		=	'manage_balance';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'MANAGE CUSTOMER BALANCE';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'customer/balance_customer_view';
	$this->load->view('default/template',$data);
}

/******************BILLING *********************************/
    function invoices()
    {
        $customer_id            =   $this->session->userdata('customer_id');
        $data['customer_id']    =   $customer_id;
        
        //for filter & search
        $filter_date_from       = '';
        $filter_date_to         = '';
        $filter_customers       = $customer_id;
        $filter_status          = '';
        $filter_sort            = '';
        $search                 = '';

        $msg_records_found = "Records Found";

        if($this->input->get('searchFilter'))
        {
            $filter_date_from       = $this->input->get('filter_date_from');
            $filter_date_to         = $this->input->get('filter_date_to');
            $filter_status          = $this->input->get('filter_status');
            $filter_sort            = $this->input->get('filter_sort');
            $search                 = $this->input->get('searchFilter');
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

        $data['filter_date_from']           = $filter_date_from;
        $data['filter_date_to']             = $filter_date_to;
        $data['filter_status']              = $filter_status;
        $data['filter_sort']                = $filter_sort;
        
        //for pagging set information
        $this->load->library('pagination');
        $config['per_page'] = '20';
        $config['base_url'] = base_url().'customer/invoices/?searchFilter='.$search.'&filter_date_from='.$filter_date_from.'&filter_date_to='.$filter_date_to.'&filter_status='.$filter_status.'&filter_sort='.$filter_sort.'';
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

        $data['count'] = $this->billing_model->get_invoices_count($filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type = '', $filter_status);
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

        $data['invoices']       =   $this->billing_model->get_invoices($config['per_page'],$config['uri_segment'],$filter_date_from, $filter_date_to, $filter_customers, $filter_billing_type = '', $filter_status, $filter_sort);
        
        $data['page_name']		=	'customer_invoices';
        $data['selected']		=	'billing';
        $data['sub_selected']   =   '';
        $data['page_title']		=	'INVOICES';
        $data['main_menu']	    =	'';
        $data['sub_menu']	    =	'';
        $data['main_content']	=	'customer/invoices_view';
        $this->load->view('default/template',$data);
    }
    
    function download_invoice($invoice_id = '')
    {
        if($invoice_id == '')
        {
            redirect ('customer/invoices/');
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
            redirect ('customer/invoices/');
        }
    }
    
    function download_cdr($invoice_id = '')
    {
        if($invoice_id == '')
        {
            redirect ('customer/invoices/');
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
            redirect ('customer/invoices/');
        }
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
}
