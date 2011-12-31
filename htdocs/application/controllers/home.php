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

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('home_model');
	}

	function index()
	{
		//validate login
		if (user_login())
		{
			if($this->session->userdata('user_type') == 'admin')
			{
				redirect ('home/dashboard');
			}
			else if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
		}

		$this->load->view('login.php'); 
	}

	function dashboard()
	{
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

		$data['page_name']		=	'dashboard';
		$data['selected']		=	'dashboard';
		$data['page_title']		=	'DASHBOARD';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'';
		$data['main_content']	=	'dashboard';
		$this->load->view('default/template',$data);      
	}

	function check_credentials()
	{
		$username = $this->db->escape($this->input->post('username'));
		$password = md5($this->input->post('password'));

		$check_credentials = $this->home_model->check_credentials($username, $password);

		if($check_credentials->num_rows() > 0)
		{
			echo "valid_user";
			exit;
		}
		else
		{
			echo "invalid";
			exit;
		}
	}

	function do_login()
	{
		//validate login
		if (user_login())
		{
			if($this->session->userdata('user_type') == 'admin')
			{
				redirect ('home/dashboard');
			}
			else if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
		}

		$username = $this->db->escape($this->input->post('username'));
		$password = md5($this->input->post('password'));

		$check_credentials = $this->home_model->check_credentials($username, $password);

		if($check_credentials->num_rows() == 1)
		{
			$row	=	$check_credentials->row();

			if ($row->type == 'admin') //if the user is admin
			{
				$newdata = array(
					'username'          =>  $row->username,
					'user_id'           =>  $row->id,
					'user_type'         =>  $row->type,
					'user_logged_in'    =>  true
					);
				$this->session->set_userdata($newdata);
				redirect('home/dashboard');
			}
			if ($row->type == 'customer') //if the user is admin
			{
				$newdata = array(
					'username'          =>  $row->username,
					'user_id'           =>  $row->id,
					'user_type'         =>  $row->type,
					'customer_id'       =>  $row->customer_id,
					'is_customer'       =>  true,
					'user_logged_in'    =>  true
					);
				$this->session->set_userdata($newdata);
				redirect('customer/');
			}
		}
		else
		{
			$this->session->set_flashdata('error_message','Invalid Username or Password.');
			redirect('home/');
		}
	}

	function logout()
	{
		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}

		$this->session->sess_destroy();
		redirect('home/');			
	}
}
