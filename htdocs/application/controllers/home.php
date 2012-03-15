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

/**
 * Home class. Initialize the login and logout routine and sets different user
 * level access (admin, reseller and customer)
 *
 * @package vBilling
 * @author "Digital Linx - <vbilling at digitallinx.com>"
 **/
class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('home_model');
	}

/**
 * Validates the login type and define appropriate access
 *
 * @return void
 **/
	function index()
	{
		//validate login
		if (user_login())
		{
			if($this->session->userdata('user_type') == 'admin' || $this->session->userdata('user_type') == 'sub_admin')
			{
				// redirect ('home/dashboard');
				redirect ('customers');
			}
			else if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
			else if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/customers/');
			}
		}

		$this->load->view('login.php'); 
	}

/**
 * provides different dashboards for each login level
 *
 * @return void
 **/
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
			else if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/customers/');
			}
		}

		$data['page_name']    = 'dashboard';
		$data['selected']     = 'dashboard';
		$data['page_title']   = 'DASHBOARD';
		$data['main_menu']    = 'default/main_menu/main_menu';
		$data['sub_menu']     = '';
		$data['main_content'] = 'dashboard';
		$this->load->view('default/template',$data);      
	}

/**
 * Validate user credentials for login
 *
 * @return void
 **/
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

/**
 * Does actual login and redirects user to their homepage as per their access
 *
 * @return void
 **/
	function do_login()
	{
		//validate login
		if (user_login())	// works if user already logged in
		{
			if($this->session->userdata('user_type') == 'admin' || $this->session->userdata('user_type') == 'sub_admin')
			{
				// redirect ('home/dashboard');
				redirect ('customers');
			}
			else if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
			else if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/customers/');
			}
		}

		$username = $this->db->escape($this->input->post('username'));
		$password = md5($this->input->post('password'));	// All passwords are saved as md5 hashes

		$check_credentials = $this->home_model->check_credentials($username, $password);

		if($check_credentials->num_rows() == 1)
		{
			$row = $check_credentials->row();

			if ($row->type == 'admin' || $row->type == 'sub_admin') //if the user is admin or type sub_admin
			{
				$newdata = array(
					'username'       =>  $row->username,
					'user_id'        =>  $row->id,
					'user_type'      =>  $row->type,
					'user_logged_in' =>  true
					);
				$this->session->set_userdata($newdata);
				// redirect('home/dashboard');
				redirect ('customers');
			}
			if ($row->type == 'customer') //if the user is customer
			{
				$newdata = array(
					'username'       =>  $row->username,
					'user_id'        =>  $row->id,
					'user_type'      =>  $row->type,
					'customer_id'    =>  $row->customer_id,
					'is_customer'    =>  true,
					'user_logged_in' =>  true
					);
				$this->session->set_userdata($newdata);
				redirect('customer/');
			}
			if ($row->type == 'reseller') //if the user is reseller
			{
				$newdata = array(
					'username'       =>  $row->username,
					'user_id'        =>  $row->id,
					'user_type'      =>  $row->type,
					'customer_id'    =>  $row->customer_id,
					'is_customer'    =>  true,
					'user_logged_in' =>  true
					);
				$this->session->set_userdata($newdata);
				redirect('reseller/customers/');
			}
		}
		else
		{
			$this->session->set_flashdata('error_message','Invalid Username or Password.');
			redirect('home/');
		}
	}

/**
* This function logout the user and destroys the session
*
* @return void
**/
	function logout()
	{
		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}

		$this->session->sess_destroy();	// Destroy the session and redirects back to /home/
		redirect('home/');			
	}
} // END class