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

class Groups extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('reseller/groups_model');
		$this->load->model('reseller/carriers_model');

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

	function index()
	{
		$filter_groups          = '';
		$filter_group_type      = '';
        $filter_sort            = '';
		$search                 = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_groups          = $this->input->get('filter_groups');
			$filter_group_type      = $this->input->get('filter_group_type');
            $filter_sort            = $this->input->get('filter_sort');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Search Criteria";
		}

		$data['filter_groups']              = $filter_groups;
		$data['filter_group_type']          = $filter_group_type;
        $data['filter_sort']                = $filter_sort;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'reseller/groups/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_group_type='.$filter_group_type.'&filter_sort='.$filter_sort.'';
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

		$data['count'] = $this->groups_model->get_all_groups_count($filter_groups, $filter_group_type);
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

		$data['groups']         =   $this->groups_model->get_all_groups($config['per_page'],$config['uri_segment'], $filter_groups, $filter_group_type, $filter_sort);
		$data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'list_rate_groups';
		$data['page_title']		=	'GROUPS';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/groups_view';
		$this->load->view('default/template',$data);
	}
    
    function assigned_rate_group()
    {
		$data['groups']         =   $this->groups_model->get_assigned_rate_group();
		$data['page_name']		=	'assigned_group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'list_assigned_groups';
		$data['page_title']		=	'ASSIGNED GROUP';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/assigned_group_view';
		$this->load->view('default/template',$data);
    }

	//enable or disable customer 
	function enable_disable_group()
	{
		$data['rate_group_id'] = $this->input->post('rate_group_id');
		$data['status']        = $this->input->post('status');
		$this->groups_model->enable_disable_group($data);
	}

	//new group
	function new_rate_group()
	{
		$data['page_name']		=	'new_group';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'new_rate_group';
		$data['page_title']		=	'NEW GROUP';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/add_group_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_rate_group()
	{
		$data['groupname']		=	$this->input->post('groupname');
		$insert_id = $this->groups_model->insert_new_rate_group($data['groupname']);

		//create group table
		$this->groups_model->create_new_rate_group_rate_tbl($insert_id);
	}
    
    function assigned_group_details($rate_group_id)
    {
        if(customer_any_cell($this->session->userdata('customer_id'), 'customer_rate_group') != $rate_group_id || $rate_group_id == '' || !is_numeric($rate_group_id))
        {
            redirect ('reseller/groups/');
        }
        
        $data['group']          =   $this->groups_model->get_single_group($rate_group_id);
		$group_rate_table_name  =   $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		
        //for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '100';
		$config['base_url'] = base_url().'reseller/groups/assigned_group_details/'.$rate_group_id.'/?';
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

		$data['count'] = $this->groups_model->assigned_group_rates_count($group_rate_table_name);
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
        
        $data['group_rates']    =   $this->groups_model->assigned_group_rates($config['per_page'],$config['uri_segment'],$group_rate_table_name);
		$data['rate_group_id']       =   $rate_group_id;

		$data['page_name']		=	'assigned_group_details';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'new_rate';
		$data['page_title']		=	'ASSIGNED GROUP DETAILS';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/assigned_group_detail_view';
		$this->load->view('default/template',$data);
    }
    
	function update_group($rate_group_id)
	{
		if(group_any_cell($rate_group_id, 'created_by') != $this->session->userdata('customer_id') || $rate_group_id == '' || !is_numeric($rate_group_id))
        {
            redirect ('reseller/groups/');
        }
        
        $data['group']          =   $this->groups_model->get_single_group($rate_group_id);
		$group_rate_table_name  =   $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		
        //for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '100';
		$config['base_url'] = base_url().'reseller/groups/update_group/'.$rate_group_id.'/?';
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

		$data['count'] = $this->groups_model->group_rates_count($group_rate_table_name);
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
        
        $data['group_rates']    =   $this->groups_model->group_rates($config['per_page'],$config['uri_segment'],$group_rate_table_name);
		$data['rate_group_id']  =   $rate_group_id;

		$data['page_name']		=	'edit_group';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE GROUP';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/edit_group_view';
		$this->load->view('default/template',$data);
	}

	function edit_group_db()
	{
		$data['groupname']		=	$this->input->post('groupname');
		$data['rate_group_id']		=	$this->input->post('rate_group_id');

		$this->groups_model->edit_group_db($data);
	}

	function check_group_in_use()
	{
		$rate_group_id	=	$this->input->post('rate_group_id');

		$check_in_use = $this->groups_model->check_group_in_use($rate_group_id);

		if($check_in_use->num_rows() > 0)
		{
			echo "in_use";
			exit;
		}
		else
		{
			$this->groups_model->delete_group($rate_group_id);
			echo "deleted";
			exit;
		}
	}

	function delete_group()
	{
		$rate_group_id	=	$this->input->post('rate_group_id');
		$this->groups_model->delete_group($rate_group_id);
	}

	//****************************rates functions******************************

	function new_rate($rate_group_id = '')
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_rate') == 0)
            {
                redirect ('groups/');
            }
        }
        
        $data['rate_group_id']		=	$rate_group_id;
        
        $data['page_name']		=	'new_rate';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'new_rate';
		$data['page_title']		=	'NEW RATE';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'groups/add_rate_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_rate()
	{
		$data['sellrate']       = $this->input->post('sell_rate');
        $data['sellblock']      = $this->input->post('sell_init');
        $data['group']          = $this->input->post('rate_group');
        $data['parent_rate_id']  = $this->input->post('parent_rate_id');
        
        $group_rate_table_name  = $this->groups_model->group_any_cell($data['group'], 'group_rate_table');
        
        if($group_rate_table_name != '')
        {
            $parent_rate_dtl = $this->groups_model->get_single_rate($data['parent_rate_id'], group_any_cell(customer_any_cell($this->session->userdata('customer_id'), 'customer_rate_group'), 'group_rate_table'));
            
            $row = $parent_rate_dtl->row();
            
            $data['digits']         = $row->digits;
            $data['costrate']       = $row->sell_rate; //parent sell rate is reseller buy rate
            $data['buyblock']       = $row->sell_initblock; //parent sell init is reseller buy init
            $data['sellblock_min_duration']     = $row->sellblock_min_duration;
            $data['buyblock_min_duration']      = $row->buyblock_min_duration;
            $data['leadstrip']      = $row->lead_strip;
            $data['trailstrip']     = $row->trail_strip;
            $data['prefix']         = $row->prefix;
            $data['suffix']         = $row->suffix;
            $data['profile']        = $row->lcr_profile;
            $data['startdate']      = $row->date_start;
            $data['enddate']        = $row->date_end;
            $data['quality']        = $row->quality;
            $data['reliability']    = $row->reliability;
            $data['lrn']            = $row->lrn;
            $data['carrier']        = $row->carrier_id;
            $data['country']        = $row->country_id;

            $rate_duplicate = $this->groups_model->check_rate_duplicate($data['digits'], $data['sellrate'], $group_rate_table_name);

            if($rate_duplicate->num_rows() == 0)
            {
                $this->groups_model->insert_new_rate($data, $group_rate_table_name);
            }
            else
            {
                echo "duplicate";
                exit;
            }
        }
	}

	//enable or disable rate 
	function enable_disable_rate()
	{
		$rate_group_id              = $this->input->post('rate_group_id');
		$group_rate_table_name      = $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		$data['rate_id']            = $this->input->post('rate_id');
		$data['status']             = $this->input->post('status');
		$this->groups_model->enable_disable_rate($data, $group_rate_table_name);
	}

	function update_rate($rate_id = '', $rate_group_id = '')
	{
		if(!is_numeric($rate_id) || $rate_id == '' || !is_numeric($rate_group_id) || $rate_group_id == '' || group_any_cell($rate_group_id, 'created_by') != $this->session->userdata('customer_id'))
        {
            redirect ('reseller/groups/');
        }
        
        $data['rate_id']        =   $rate_id;
		$data['rate_group_id']  =   $rate_group_id;
		$group_rate_table_name  =   $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		$data['rate']           =   $this->groups_model->get_single_rate($rate_id, $group_rate_table_name);

		$data['page_name']		=	'edit_rate';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE RATE';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/edit_rate_view';
		$this->load->view('default/template',$data);
	}

	function edit_rate_db()
	{
		$data['rate_id']                = $this->input->post('rate_id');
		$data['rate_group_id']          = $this->input->post('rate_group_id');
		$data['group_rate_table_name']  = $this->groups_model->group_any_cell($data['rate_group_id'], 'group_rate_table');

		$data['rate']        = $this->input->post('rate');
		$data['sellblock']   = $this->input->post('sellblock');
        
        $data['old_rate']   = $this->input->post('old_rate');
        $data['old_digits']   = $this->input->post('old_digits');
		
		if($data['group_rate_table_name'] != '')
        {
            if($data['rate'] != $data['old_rate'])
            {
                $rate_duplicate = $this->groups_model->check_rate_duplicate($data['old_digits'], $data['rate'], $data['group_rate_table_name']);

                if($rate_duplicate->num_rows() == 0)
                {
                    $this->groups_model->edit_rate_db($data);
                }
                else
                {
                    echo "duplicate";
                    exit;
                }
            }
            else
            {
                $this->groups_model->edit_rate_db($data);
            }
        }
	}

	function delete_group_rate()
	{
		$rate_group_id                   = $this->input->post('rate_group_id');
		$group_rate_table_name      = $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		$rate_id                    = $this->input->post('rate_id');
		$this->groups_model->delete_group_rate($rate_id, $group_rate_table_name);
	}

	function carrier_valid_invalid()
	{
		$carrier_id = $this->input->post('carrier_id');
		$check_carrier_validity = $this->carriers_model->carrier_valid_invalid($carrier_id);

		if($check_carrier_validity == 'VALID')
		{
			echo "valid";
			exit;
		}
		else //selected carrier is invalid ... insertion not allowed
		{
			echo "carrier_invalid";
			exit;
		}
	}
    
    function list_rates()
    {
        $filter_display_results = 'sec';
        
        $filter_groups          = '';
        $filter_carriers        = '';
        $filter_country         = '';
		$filter_destination     = '';
        $destination_advance_filter = 'exact';
        $filter_sort            = '';
		$search                 = '';
        
        $do_batch = 0;

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_groups          = $this->input->get('filter_groups');
			$filter_carriers        = $this->input->get('filter_carriers');
            $filter_country         = $this->input->get('filter_country');
            $filter_destination     = $this->input->get('filter_destination');
            $destination_advance_filter     = $this->input->get('destination_advance_filter');
            $filter_sort            = $this->input->get('filter_sort');
            $filter_display_results = $this->input->get('filter_display_results');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Search Criteria";
            
            /****FOR BATCH ONLY *****/
            $do_batch               = $this->input->get('is_batch');
		}
        
        if($destination_advance_filter != 'exact' && $destination_advance_filter != 'contain' && $destination_advance_filter != 'begin' && $destination_advance_filter != 'end')
        {
            $destination_advance_filter = 'exact';
        }
        
        if($do_batch == 1)
        {
            $is_sell_rate       = $this->input->get('is_sell_rate');
            $sell_rate_value    = $this->input->get('sell_rate_value');
            $action_sell_rate   = $this->input->get('action_sell_rate');
            
            $is_sell_init       = $this->input->get('is_sell_init');
            $sell_init_value    = $this->input->get('sell_init_value');
            $action_sell_init   = $this->input->get('action_sell_init');
            
            if($is_sell_rate == 1 || $is_sell_init == 1)
            {
                $get_full_batch_query = $this->groups_model->get_all_rates_to_perform_batch($filter_groups, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
                
                $this->groups_model->perform_batch($get_full_batch_query, $is_sell_rate, $sell_rate_value, $action_sell_rate, $is_sell_init, $sell_init_value, $action_sell_init);
                
                $this->session->set_flashdata('success_message','Batch Update Performed Successfully.');
                redirect ('reseller/groups/list_rates/');
            }
        }
        
        if($filter_display_results   == '')
		{
			$filter_display_results   = 'min';
		}

		if($filter_display_results != 'min' && $filter_display_results != 'sec')
		{
			$filter_display_results   = 'min';
		}

		$data['filter_groups']              = $filter_groups;
		$data['filter_carriers']            = $filter_carriers;
        $data['filter_country']             = $filter_country;
        $data['filter_destination']         = $filter_destination;
        $data['destination_advance_filter']         = $destination_advance_filter;
        $data['filter_sort']                = $filter_sort;
        $data['filter_display_results']     = $filter_display_results;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'reseller/groups/list_rates/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_carriers='.$filter_carriers.'&filter_country='.$filter_country.'&filter_destination='.$filter_destination.'&destination_advance_filter='.$destination_advance_filter.'&filter_sort='.$filter_sort.'&filter_display_results='.$filter_display_results.'';
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

		$data['count'] = $this->groups_model->get_all_rates_count($filter_groups, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
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

		$data['rate']         =   $this->groups_model->get_all_rates($config['per_page'],$config['uri_segment'], $filter_groups, $filter_carriers, $filter_country, $filter_destination, $filter_sort, $destination_advance_filter);
		$data['page_name']		=	'list_rate_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'list_rates';
		$data['page_title']		=	'LIST RATES';
		$data['main_menu']	    =	'default/main_menu/reseller_main_menu';
		$data['sub_menu']	    =	'default/sub_menu/reseller/groups_sub_menu';
		$data['main_content']	=	'reseller/groups/list_rate_view';
		$this->load->view('default/template',$data);
    }
}