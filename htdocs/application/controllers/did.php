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

class did extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('did_model');

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
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_did') == 0)
                {
                    redirect ('home/');
                }
            }
		}
	}

	function index()
	{
		$filter_did        = '';
		$filter_did_type    = '';
        $filter_sort            = '';
		$search                 = '';

		$msg_records_found = "Records Found";
		if($this->input->get('searchFilter'))
		{
            $filter_customer_id         = $this->input->get('filter_customer_id');
            $filter_carrier_id          = $this->input->get('filter_carrier_id');
            $filter_did_number          = $this->input->get('filter_did_number');
			$filter_did_type            = $this->input->get('filter_did_type');
            $filter_sort                = $this->input->get('filter_sort');
			$search                     = $this->input->get('searchFilter');
			$msg_records_found          = "Records Found Based On Search Criteria";
		}

		$data['filter_did_number']      = $filter_did_number;
		$data['filter_did_type']        = $filter_did_type;
        $data['filter_customer_id']     = $filter_customer_id;
        $data['filter_carrier_id']      = $filter_carrier_id;
        $data['filter_sort']            = $filter_sort;


		//for paging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'did/?searchFilter='.$search.'&filter_did='.$filter_did.'&filter_did_type='.$filter_did_type.'&filter_sort='.$filter_sort.'';
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
		$data['count'] = $this->did_model->get_all_did_count($filter_did_number, $filter_did_type, $filter_carrier_id, $filter_customer_id);
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

		$data['did']      =   $this->did_model->get_all_did($config['per_page'],$config['uri_segment'], $filter_did_number, $filter_did_type, $filter_carrier_id, $filter_customer_id);

        $data['page_name']		=	'did_view';
		$data['selected']		=	'did';
		$data['sub_selected']   =   'list_did';
		$data['page_title']		=	'DIDs';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/did_sub_menu';
		$data['main_content']	=	'did/did_view';
		$this->load->view('default/template',$data);
	}

	//enable or disable customer 
	function enable_disable_did()
	{
		$data['did_id']         = $this->input->post('did_id');
		$data['status']             = $this->input->post('status');
		$this->did_model->enable_disable_did($data);
	}

	function new_did()
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_did') == 0)
            {
                redirect ('did/');
            }
        }
        
        $data['page_name']		=	'new_did';
		$data['selected']		=	'did';
		$data['sub_selected']   =   'new_did';
		$data['page_title']		=	'NEW DID';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/did_sub_menu';
		$data['main_content']	=	'did/add_did_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_did()
	{
		$did_number   = $this->input->post('didnumber');
        $carrier_id   = $this->input->post('carrierid');
        $customer_id  = $this->input->post('customerid');
		$insert_id    = $this->did_model->insert_new_did($carrier_id, $customer_id, $did_number);
	}

	function edit_did($did_id)
	{
        $data['did_id']            =   $did_id;
        $data['did_number']        =   $this->did_model->did_any_cell($did_id,'did_number');
        $data['customer_id']       =   $this->did_model->did_any_cell($did_id,'customer_id');
        $data['carrier_id']        =   $this->did_model->did_any_cell($did_id,'carrier_id');

        $data['page_name']		=	'edit_did';
		$data['selected']		=	'did';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'Edit DID';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/did_sub_menu';
		$data['main_content']	=	'did/edit_did_view';
		$this->load->view('default/template',$data);
	}

	function update_did()
	{
		$did_id           =   $this->input->post('didid');
        $did_number       =   $this->input->post('didnumber');
        $customer_id      =   $this->input->post('customerid');
        $carrier_id       =   $this->input->post('carrierid');

		//update did name
		$insert_id = $this->did_model->update_did($did_id, $did_number, $customer_id, $carrier_id);
	}


	function delete_did()
	{
		$did_id = $this->input->post('did_id');
		$this->did_model->delete_did($did_id);
	}

}