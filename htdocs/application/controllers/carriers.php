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

class Carriers extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('carriers_model');

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
            
            if($this->session->userdata('user_type') == 'sub_admin')
            {
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_carriers') == 0)
                {
                    redirect ('home/');
                }
            }
		}
	}

	function index()
	{
		$filter_carriers        = '';
		$filter_carrier_type    = '';
        $filter_sort            = '';
		$search                 = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_carriers        = $this->input->get('filter_carriers');
			$filter_carrier_type    = $this->input->get('filter_carrier_type');
            $filter_sort                = $this->input->get('filter_sort');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Your Search Criteria";
		}

		$data['filter_carriers']            = $filter_carriers;
		$data['filter_carrier_type']        = $filter_carrier_type;
        $data['filter_sort']            = $filter_sort;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'carriers/?searchFilter='.$search.'&filter_carriers='.$filter_carriers.'&filter_carrier_type='.$filter_carrier_type.'&filter_sort='.$filter_sort.'';
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

		$data['count'] = $this->carriers_model->get_all_carriers_count($filter_carriers, $filter_carrier_type);
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

		$data['carriers']      =   $this->carriers_model->get_all_carriers($config['per_page'],$config['uri_segment'], $filter_carriers, $filter_carrier_type, $filter_sort);
		$data['page_name']		=	'carrier_view';
		$data['selected']		=	'carriers';
		$data['sub_selected']   =   'list_carriers';
		$data['page_title']		=	'CARRIERS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/carriers_sub_menu';
		$data['main_content']	=	'carriers/carriers_view';
		$this->load->view('default/template',$data);
	}

	//enable or disable customer 
	function enable_disable_carrier()
	{
		$data['carrier_id']         = $this->input->post('carrier_id');
		$data['status']             = $this->input->post('status');
		$this->carriers_model->enable_disable_carrier($data);
	}

	function new_carrier()
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_carriers') == 0)
            {
                redirect ('carriers/');
            }
        }
        
        $data['page_name']		=	'new_carrier';
		$data['selected']		=	'carriers';
		$data['sub_selected']   =   'new_carrier';
		$data['page_title']		=	'NEW CARRIER';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/carriers_sub_menu';
		$data['main_content']	=	'carriers/add_carrier_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_carrier()
	{
		$carriername = $this->input->post('carriername');
		$prefix = $this->input->post('prefix'); // gateway
		$suffix = $this->input->post('suffix');
		$codec = $this->input->post('codec');
		$pre = $this->input->post('pre');

		$insert_id = $this->carriers_model->insert_new_carrier($carriername);
        
        $priority = 0;
		foreach($prefix as $a => $b){
			$prefix_explode = explode('|', $prefix[$a]);
			$gateway_name = $prefix_explode[0];
			$sofia_id = $prefix_explode[1];

			$this->carriers_model->insert_carrier_gateways($insert_id, $gateway_name, $suffix[$a], $codec[$a], $sofia_id, $pre[$a], $priority);
            $priority = $priority + 1;
		}

	}

	function update_carrier($carrier_id)
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_carriers') == 0)
            {
                redirect ('carriers/');
            }
        }
        
        $data['carrier']            =   $this->carriers_model->get_single_carrier($carrier_id);
		$data['carrier_gateways']   =   $this->carriers_model->carrier_gateways($carrier_id);
		$data['carrier_id']     =   $carrier_id;

		$data['page_name']		=	'edit_carrier';
		$data['selected']		=	'carriers';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE CARRIER';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/carriers_sub_menu';
		$data['main_content']	=	'carriers/edit_carrier_view';
		$this->load->view('default/template',$data);
	}

	function edit_carrier_db()
	{
		$carrier_id = $this->input->post('carrier_id');
		$carriername = $this->input->post('carriername');
		$prefix = $this->input->post('prefix');
		$suffix = $this->input->post('suffix');
		$codec = $this->input->post('codec');
		$pre = $this->input->post('pre');

		//update carrier name
		$this->carriers_model->update_carrier($carriername, $carrier_id);

		//delete all carriers gateway 
		$this->carriers_model->delete_carrier_gateways($carrier_id);
        
        $priority = 0;
		foreach($prefix as $a => $b){
			$prefix_explode = explode('|', $prefix[$a]);
			$gateway_name = $prefix_explode[0];
			$sofia_id = $prefix_explode[1];

			$this->carriers_model->insert_carrier_gateways($carrier_id, $gateway_name, $suffix[$a], $codec[$a], $sofia_id, $pre[$a], $priority);
            $priority = $priority + 1;
		}
	}


	function delete_carrier()
	{
		$carrier_id = $this->input->post('carrier_id');
		$this->carriers_model->delete_carrier($carrier_id);
	}
    
    function update_gateway_priority()
    {
        $carrier = $_GET["carrier_id"];
        $arr     = $_GET["table-".$carrier.""];
        
        $order = 0;
        $jump = 0;
        foreach($arr as $row_id) {
            if($jump != 0)
            {
                $this->carriers_model->update_gateway_priority($row_id, $order);
                $order = $order + 1;
            }
            $jump = $jump + 1;
        }
        $this->session->set_flashdata('success','Gateway Priorities updated successfully.');
    }

}