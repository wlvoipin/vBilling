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

class Info extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('reseller/customer_model');
		$this->load->model('reseller/groups_model');
		$this->load->model('reseller/manage_accounts_model');
        $this->load->model('reseller/billing_model');
        $this->load->model('reseller/cdr_model');
		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}
		else
		{
			if($this->session->userdata('user_type') != 'reseller')
			{
				redirect ('home/');
			}
		}
	}

	//edit customer view 
	function index()
	{
		$customer_id            = $this->session->userdata('customer_id');
		$data['customer']       =   $this->customer_model->get_single_customer($customer_id);
		$data['customer_id']    =   $customer_id;

		$data['page_name']		=	'edit_customer';
		$data['selected']		=	'customers_info';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE CUSTOMER';
		$data['main_menu']	    =	'';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'reseller/reseller_info/edit_customer_view';
        $data['dont_show_this'] = 1;
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
	$data['main_content']	=	'reseller/reseller_info/ip_customer_view';
    $data['dont_show_this'] = 1;
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
        $data['selected']		=	'customers_ip';
        $data['sub_selected']   =   '';
        $data['page_title']		=	'NEW ACL NODE';
        $data['main_menu']	    =	'';
        $data['sub_menu']	    =	'';
        $data['main_content']	=	'reseller/reseller_info/new_acl_node_view';
        $data['dont_show_this'] = 1;
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
	$this->customer_model->insert_new_acl_node_reseller($customer_id, $ip, $cidr);

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
		redirect('reseller/reseller_info/customer_acl_nodes/'.$customer_id.''); 
	}

	$data['page_name']		=	'edit_acl_node';
	$data['selected']		=	'customers_ip';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'UPDATE ACL NODE';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'reseller/reseller_info/edit_acl_node_view';
    $data['dont_show_this'] = 1;
	$this->load->view('default/template',$data);
}

//**************************** CUSTOMER SIP ACCESS FUNCTION *************************************//

function sip_access()
{
	$customer_id            = $this->session->userdata('customer_id');
    $data['sip_access']     =   $this->customer_model->customer_sip_access($customer_id);
	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_sip_access';
	$data['selected']		=	'sip_access';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'CUSTOMER SIP CREDENTIALS';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'reseller/reseller_info/sip_customer_view';
    $data['dont_show_this'] = 1;
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
		$data['main_content']	=	'reseller/reseller_info/new_sip_view';
        $data['dont_show_this'] = 1;
		$this->load->view('default/template',$data);
	}
	else
	{
		redirect ('customer/sip_access');
	}
}

function insert_new_sip_access()
{
	$customer_id    =   $this->session->userdata('customer_id');
	$username       =   $this->input->post('username');
	$password       =   $this->input->post('password');
    $cid            =   $this->input->post('cid');
    
    $getdomain      =   $this->input->post('sip_ip');
	$explode        =   explode('|', $getdomain);

	$domain     = $explode[0];
	$sofia_id   = $explode[1];

	$this->customer_model->insert_new_sip_access_reseller($customer_id, $username, $password, $domain, $sofia_id, $cid);
}
//**************************** MANAGE BALANCE ************************************//
function manage_balance()
{
	$customer_id    =   $this->session->userdata('customer_id');
    $data['history']        =   $this->customer_model->customer_balance_history($customer_id);
	$data['customer_id']    =   $customer_id;

	$data['page_name']		=	'customer_manage_balance';
	$data['selected']		=	'manage_balance';
	$data['sub_selected']   =   '';
	$data['page_title']		=	'MANAGE CUSTOMER BALANCE';
	$data['main_menu']	    =	'';
	$data['sub_menu']	    =	'';
	$data['main_content']	=	'reseller/reseller_info/balance_customer_view';
    $data['dont_show_this'] = 1;
	$this->load->view('default/template',$data);
}

function my_balance()
{
	$customer_id            = $this->session->userdata('customer_id');
	$data['my_balance']     = $this->customer_model->my_balance($customer_id);
	$data['customer_id']    = $customer_id;

	$data['page_name']      = 'my_balance';
	$data['selected']       = 'my_balance';
	$data['sub_selected']   = '';
	$data['page_title']     = 'MY BALANCE';
	$data['main_menu']      = '';
	$data['sub_menu']       = '';
	$data['main_content']   = 'reseller/reseller_info/my_balance_view';
	$data['dont_show_this'] = 1;
	$this->load->view('default/template',$data);
}

}
