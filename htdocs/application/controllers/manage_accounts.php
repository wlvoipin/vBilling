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

class Manage_accounts extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('manage_accounts_model');

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

	function index($filter_account_type = '')
	{
		if($filter_account_type == '')
		{
			$filter_account_type = 'admin';
		}
		else
		{
			if($filter_account_type != 'admin' && $filter_account_type != 'customer')
			{
				$filter_account_type = 'admin';
			}
		}

		$filter_enabled         = '';
		$search                 = '';
		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_enabled        = $this->input->get('filter_enabled');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Your Search Criteria";
		}

		$data['filter_account_type'] = $filter_account_type;
		$data['filter_enabled']      = $filter_enabled;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'manage_accounts/index/'.$filter_account_type.'/?searchFilter='.$search.'&filter_enabled='.$filter_enabled.'';
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

		$data['count'] = $this->manage_accounts_model->get_all_accounts_count($filter_account_type, $filter_enabled);
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

		$data['accounts']      =   $this->manage_accounts_model->get_all_accounts($config['per_page'],$config['uri_segment'], $filter_account_type, $filter_enabled);

		$data['page_name']		=	'list_access_accounts';
		$data['selected']       =   'manage_accounts';
		if($filter_account_type == 'admin')
		{
			$data['sub_selected']		=	'admin_accounts';
		}
		else if($filter_account_type == 'customer')
		{
			$data['sub_selected']		=	'customers_accounts';
		}
		else
		{
			$data['sub_selected']		=	'admin_accounts';
		}

		$data['page_title']		=	'ACCOUNTS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/manage_accounts_sub_menu';
		$data['main_content']	=	'accounts/accounts_view';
		$this->load->view('default/template',$data);
	}

	/*function new_account()
	{
	$account_type = $this->input->post('new_account_type');
	if($account_type == '')
	{
	$account_type = 'admin';
	}
	else
	{
	if($account_type != 'admin' && $account_type != 'customer')
	{
	$account_type = 'admin';
	}
	}

$data['account_type'] = $account_type;

$data['customers_with_no_accounts']      =   $this->manage_accounts_model->customers_with_no_accounts();

$data['page_name']		=	'new_account';
$data['selected']       =   'manage_accounts';
$data['sub_selected']   =   '';

$data['page_title']		=	'NEW ACCOUNT';
$data['main_menu']	    =	'default/main_menu/main_menu';
$data['sub_menu']	    =	'default/sub_menu/manage_accounts_sub_menu';
$data['main_content']	=	'accounts/new_account_view';
$this->load->view('default/template',$data);
}

function create_new_account()
{
$data['account_type'] = $this->input->post('hidden_account_type');
$data['username'] = $this->db->escape($this->input->post('username'));
$data['password'] = md5($this->input->post('password'));
$check_username_availability = $this->manage_accounts_model->check_username_availability($data['username']);

if($data['account_type'] == 'customer')
{
$data['customer_id'] = $this->input->post('customer');
}

if($check_username_availability->num_rows() > 0) //username already in use
{
echo "username_in_use";
exit;
}
else
{
$this->manage_accounts_model->create_new_account($data);
echo "success";
exit;
}
}*/

function enable_disable_account()
{
	$account_id = $this->input->post('account_id');
	$status = $this->input->post('status');
	$this->manage_accounts_model->enable_disable_account($account_id, $status);
}

function delete_account()
{
	$account_id = $this->input->post('account_id');
	$this->manage_accounts_model->delete_account($account_id);
}

}