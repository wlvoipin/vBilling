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
		$this->load->model('groups_model');
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
            
            if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/');
			}
            
            if($this->session->userdata('user_type') == 'sub_admin')
            {
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_rate_groups') == 0)
                {
                    redirect ('home/');
                }
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
		$config['base_url'] = base_url().'groups/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_group_type='.$filter_group_type.'&filter_sort='.$filter_sort.'';
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

		if ($data['count'] <= 1)
		{
			$msg_records_found = "Record Found";
		}
		else
		{
			$msg_records_found = "Records Found";			
		}

		$data['msg_records_found'] = "".$data['count']."&nbsp;".$msg_records_found."";

		$data['groups']         =   $this->groups_model->get_all_groups($config['per_page'],$config['uri_segment'], $filter_groups, $filter_group_type, $filter_sort);
		$data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'list_rate_groups';
		$data['page_title']		=	'GROUPS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/groups_view';
		$this->load->view('default/template',$data);
	}
	
	function delete_localization_group()
	{
		$localization_id	=	$this->input->post('localization_id');
		$this->groups_model->delete_localization_group($localization_id);
		$this->groups_model->update_localization_group_sip($localization_id);
		$this->groups_model->update_localization_group_acl($localization_id);
	}
	
		//enable or disable localization group 
	function enable_disable_localization_group()
	{
		$data['localization_id'] = $this->input->post('rate_group_id');
		$data['status']        = $this->input->post('status');
		$this->groups_model->enable_disable_localization_group($data);
	}	
	
	function check_localization_group_in_use()
	{	
		$localization_id	=	$this->input->post('localization_id');
		$check_in_use_acl = $this->groups_model->check_localization_group_in_use_by_acl($localization_id);
		$check_in_use_sip = $this->groups_model->check_localization_group_in_use_by_sip($localization_id);
		
		if($check_in_use_acl==TRUE || $check_in_use_sip==TRUE)
		{
			echo "in_use";
			exit;
		}
		else
		{
			$this->groups_model->delete_localization_group($localization_id);
			echo "deleted";
			exit;
		}
	}	

	//enable or disable rate group 
	function enable_disable_group()
	{
		$data['rate_group_id'] = $this->input->post('rate_group_id');
		$data['status']        = $this->input->post('status');
		$this->groups_model->enable_disable_group($data);
	}

	//new group
	function new_rate_group()
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_rate_groups') == 0)
            {
                redirect ('groups/');
            }
        }
        
        $data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'new_rate_group';
		$data['page_title']		=	'NEW GROUP';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/add_group_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_rate_group()
	{
		$data['groupname']		=	$this->input->post('groupname');
		$insert_id = $this->groups_model->insert_new_rate_group($data['groupname']);

		//create group table
		$this->groups_model->create_new_rate_group_rate_tbl($insert_id);
	}

	function update_group($rate_group_id)
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_rate_groups') == 0)
            {
                redirect ('groups/');
            }
        }
        
        $data['group']          =   $this->groups_model->get_single_group($rate_group_id);
		$group_rate_table_name  =   $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		
        //for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '100';
		$config['base_url'] = base_url().'groups/update_group/'.$rate_group_id.'/?';
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
		$data['rate_group_id']       =   $rate_group_id;

		$data['page_name']		=	'edit_group';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE GROUP';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/edit_group_view';
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
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/add_rate_view';
		$this->load->view('default/template',$data);
	}

	function import_by_csv()
	{
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'import_csv') == 0)
            {
                redirect ('groups/');
            }
        }
        
        $data['page_name']		=	'import_by_csv';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'import_by_csv';
		$data['page_title']		=	'IMPORT RATE BY CSV';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/import_csv_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_rate()
	{
		$data['digits']                 = $this->input->post('digits');
		$data['rate']                   = $this->input->post('rate');
		$data['costrate']               = $this->input->post('costrate');
		$data['sellblock_min_duration'] = $this->input->post('sellblock_min_duration');
		$data['buyblock_min_duration']  = $this->input->post('buyblock_min_duration');
		$data['buyblock']               = $this->input->post('buyblock');
		$data['sellblock']              = $this->input->post('sellblock');
		$data['remove_rate_prefix']     = $this->input->post('remove_rate_prefix');
		$data['remove_rate_suffix']     = $this->input->post('remove_rate_suffix');
		$data['leadstrip']              = $this->input->post('leadstrip');
		$data['trailstrip']             = $this->input->post('trailstrip');
		$data['prefix']                 = $this->input->post('prefix');
		$data['suffix']                 = $this->input->post('suffix');
		$data['profile']                = $this->input->post('profile');
		$data['startdate']              = $this->input->post('startdate');
		$data['enddate']                = $this->input->post('enddate');
		$data['quality']                = $this->input->post('quality');
		$data['reliability']            = $this->input->post('reliability');
		$data['lrn']                    = $this->input->post('lrn');
		$data['carrier']                = $this->input->post('carrier');
		$data['group']                  = $this->input->post('group');
		$data['country']                = $this->input->post('country');

		$group_rate_table_name  = $this->groups_model->group_any_cell($data['group'], 'group_rate_table');
		$check_carrier_validity = $this->carriers_model->carrier_valid_invalid($data['carrier']);

		if($check_carrier_validity == 'VALID')
		{
			if($group_rate_table_name != '')
			{
				$rate_duplicate = $this->groups_model->check_rate_duplicate($data['digits'], $data['carrier'], $group_rate_table_name);

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
		else //selected carrier is invalid ... insertion not allowed
		{
			echo "carrier_invalid";
			exit;
		}
	}

	//enable or disable rate 
	function enable_disable_rate()
	{
		$rate_group_id                   = $this->input->post('rate_group_id');
		$group_rate_table_name      = $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		$data['rate_id']            = $this->input->post('rate_id');
		$data['status']             = $this->input->post('status');
		$this->groups_model->enable_disable_rate($data, $group_rate_table_name);
	}

	function update_rate($rate_id, $rate_group_id)
	{
		$data['rate_id']        =   $rate_id;
		$data['rate_group_id']       =   $rate_group_id;
		$group_rate_table_name  =   $this->groups_model->group_any_cell($rate_group_id, 'group_rate_table');
		$data['rate']           =   $this->groups_model->get_single_rate($rate_id, $group_rate_table_name);

		$data['page_name']		=	'edit_rate';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'UPDATE RATE';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/edit_rate_view';
		$this->load->view('default/template',$data);
	}

	function edit_rate_db()
	{
		$data['rate_id']                = $this->input->post('rate_id');
		$data['rate_group_id']          = $this->input->post('rate_group_id');
		$data['group_rate_table_name']  = $this->groups_model->group_any_cell($data['rate_group_id'], 'group_rate_table');
		$data['digits']                 = $this->input->post('digits');
		$data['rate']                   = $this->input->post('rate');
		$data['costrate']               = $this->input->post('costrate');
		$data['sellblock_min_duration'] = $this->input->post('sellblock_min_duration');
		$data['buyblock_min_duration']  = $this->input->post('buyblock_min_duration');
		$data['buyblock']               = $this->input->post('buyblock');
		$data['sellblock']              = $this->input->post('sellblock');
		$data['remove_rate_prefix']     = $this->input->post('remove_rate_prefix');
		$data['remove_rate_suffix']     = $this->input->post('remove_rate_suffix');
		$data['leadstrip']              = $this->input->post('leadstrip');
		$data['trailstrip']             = $this->input->post('trailstrip');
		$data['prefix']                 = $this->input->post('prefix');
		$data['suffix']                 = $this->input->post('suffix');
		$data['profile']                = $this->input->post('profile');
		$data['startdate']              = $this->input->post('startdate');
		$data['enddate']                = $this->input->post('enddate');
		$data['quality']                = $this->input->post('quality');
		$data['reliability']            = $this->input->post('reliability');
		$data['lrn']                    = $this->input->post('lrn');
		$data['carrier']                = $this->input->post('carrier');
		$data['country']                = $this->input->post('country');
		$data['old_digits']             = $this->input->post('old_digits');
		$data['old_carrier']            = $this->input->post('old_carrier');

		$check_carrier_validity = $this->carriers_model->carrier_valid_invalid($data['carrier']);

		if($check_carrier_validity == 'VALID')
		{
			if($data['group_rate_table_name'] != '')
			{
				if($data['digits'] != $data['old_digits'] || $data['carrier'] != $data['old_carrier'])
				{
					$rate_duplicate = $this->groups_model->check_rate_duplicate($data['digits'], $data['carrier'], $data['group_rate_table_name']);

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
		else //selected carrier is invalid ... insertion not allowed
		{
			echo "carrier_invalid";
			exit;
		}
	}

	function delete_group_rate()
	{
		$rate_group_id              = $this->input->post('rate_group_id');
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
        if($this->session->userdata('user_type') != 'admin'){
            redirect ('home/');
        }
        $filter_display_results = 'sec';
        
		$filter_groups              = '';
		$filter_carriers            = '';
		$filter_country             = '';
		$filter_destination         = '';
		$destination_advance_filter = 'exact';
		$filter_sort                = '';
		$search                     = '';
		$do_batch                   = 0;
		
		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_groups              = $this->input->get('filter_groups');
			$filter_carriers            = $this->input->get('filter_carriers');
			$filter_country             = $this->input->get('filter_country');
			$filter_destination         = $this->input->get('filter_destination');
			$destination_advance_filter = $this->input->get('destination_advance_filter');
			$filter_sort                = $this->input->get('filter_sort');
			$filter_display_results     = $this->input->get('filter_display_results');
			$search                     = $this->input->get('searchFilter');
			$msg_records_found          = "Records Found Based On Search Criteria";
            
            /****FOR BATCH ONLY *****/
            $do_batch               = $this->input->get('is_batch');
		}
        
        if($destination_advance_filter != 'exact' && $destination_advance_filter != 'contain' && $destination_advance_filter != 'begin' && $destination_advance_filter != 'end')
        {
            $destination_advance_filter = 'exact';
        }
        
        if($do_batch == 1)
        {
			$is_sell_rate               = $this->input->get('is_sell_rate');
			$sell_rate_value            = $this->input->get('sell_rate_value');
			$action_sell_rate           = $this->input->get('action_sell_rate');

			$is_buy_rate                = $this->input->get('is_buy_rate');
			$buy_rate_value             = $this->input->get('buy_rate_value');
			$action_buy_rate            = $this->input->get('action_buy_rate');

			$is_sell_init               = $this->input->get('is_sell_init');
			$sell_init_value            = $this->input->get('sell_init_value');
			$action_sell_init           = $this->input->get('action_sell_init');

			$is_buy_init                = $this->input->get('is_buy_init');
			$buy_init_value             = $this->input->get('buy_init_value');
			$action_buy_init            = $this->input->get('action_buy_init');

			$is_buy_block_min           = $this->input->get('is_buy_block_min');
			$buy_block_min_value        = $this->input->get('buy_block_min_value');
			$action_buy_block_min_rate  = $this->input->get('action_buy_block_min_rate');

			$is_sell_block_min          = $this->input->get('is_sell_block_min');
			$sell_block_min_value       = $this->input->get('sell_block_min_value');
			$action_sell_block_min_rate = $this->input->get('action_sell_block_min_rate');

            if($is_sell_rate == 1 || $is_buy_rate == 1 || $is_sell_init == 1 || $is_buy_init == 1 || $is_buy_block_min == 1 || $is_sell_block_min == 1)
            {
                $get_full_batch_query = $this->groups_model->get_all_rates_to_perform_batch($filter_groups, $filter_carriers, $filter_country, $filter_destination, $destination_advance_filter);
                
                $this->groups_model->perform_batch($get_full_batch_query, $is_sell_rate, $sell_rate_value, $action_sell_rate, $is_buy_rate, $buy_rate_value, $action_buy_rate, $is_sell_init, $sell_init_value, $action_sell_init, $is_buy_init, $buy_init_value, $action_buy_init, $is_sell_block_min, $sell_block_min_value, $action_sell_block_min_rate, $is_buy_block_min, $buy_block_min_value, $action_buy_block_min_rate);
                
                $this->session->set_flashdata('success_message','Batch Update Performed Successfully.');
                redirect ('groups/list_rates/');
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
		$config['base_url'] = base_url().'groups/list_rates/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_carriers='.$filter_carriers.'&filter_country='.$filter_country.'&filter_destination='.$filter_destination.'&destination_advance_filter='.$destination_advance_filter.'&filter_sort='.$filter_sort.'&filter_display_results='.$filter_display_results.'';
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
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/list_rate_view';
		$this->load->view('default/template',$data);
    }
	
	/** 
	* Encodes string for use in XML
	* 
	* @access public * @param string * @return string 
	*/
	
	function localization_groups(){
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
		$config['base_url'] = base_url().'groups/localization_groups/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_group_type='.$filter_group_type.'&filter_sort='.$filter_sort.'';
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

		$data['count'] = $this->groups_model->get_all_localization_groups_count($filter_groups, $filter_group_type);
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

		if ($data['count'] <= 1)
		{
			$msg_records_found = "Record Found";
		}
		else
		{
			$msg_records_found = "Records Found";			
		}

		$data['msg_records_found'] = "".$data['count']."&nbsp;".$msg_records_found."";

		$data['groups'] 	 =   $this->groups_model->get_all_localization_groups($config['per_page'],$config['uri_segment'], $filter_groups, $filter_group_type, $filter_sort);
		
		$data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'localization_groups';
		$data['page_title']		=	'GROUPS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/localization-groups-view';
		$this->load->view('default/template',$data);

	}
	
	function edit_localization_group_db()
	{	
		$localization_id = $this->input->post('localization_id');		 
		$groupname = $this->input->post('groupname');			
		$this->groups_model->update_localization_group($groupname, $localization_id);		
		$this->groups_model->delete_localization_rules($localization_id);       
		foreach($_REQUEST['pre'] as $a => $b){	
			$name = $b;
			$cut = $_REQUEST['cut'][$a];
			$add = $_REQUEST['add'][$a];
			$enabled = isset($_REQUEST['enabled'][$a]) ? 1 : 0;					 
			$this->groups_model->insert_localization_rules($localization_id, $name, $cut, $add, $enabled);     			
		}
	}	
	
	function update_localization_group($localization_id)
	{
        $data['localization_group']            =   $this->groups_model->get_single_localization_group($localization_id);
		$data['localization_rules']   =   $this->groups_model->get_localization_rules($localization_id);
		$data['localization_id']     =   $localization_id;

		$data['page_name']		=	'edit_localization_group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'localization_groups';
		$data['page_title']		=	'GROUPS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/edit_localization_group_view';
		$this->load->view('default/template',$data);
	}	
	
	function insert_new_localization_group()
	{
		$data['groupname']		=	$this->input->post('groupname');
		$insert_id = $this->groups_model->insert_new_localization_group($data['groupname']);

		//create group table
		$this->groups_model->create_new_rate_group_rate_tbl($insert_id);
	}	
	
	function add_localization_groups(){
		if($this->session->userdata('user_type') == 'sub_admin')
        {
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_rate_groups') == 0)
            {
                redirect ('groups/');
            }
        }
        
        $data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'add_localization_groups';
		$data['page_title']		=	'NEW GROUP';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/add-localization-group-view';
		$this->load->view('default/template',$data);
	}
	
}