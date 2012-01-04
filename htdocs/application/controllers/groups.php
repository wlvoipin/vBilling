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
		}
	}

	function index()
	{
		$filter_groups          = '';
		$filter_group_type      = '';
		$search                 = '';

		$msg_records_found = "Records Found";

		if($this->input->get('searchFilter'))
		{
			$filter_groups          = $this->input->get('filter_groups');
			$filter_group_type      = $this->input->get('filter_group_type');
			$search                 = $this->input->get('searchFilter');
			$msg_records_found      = "Records Found Based On Your Search Criteria";
		}

		$data['filter_groups']              = $filter_groups;
		$data['filter_group_type']          = $filter_group_type;

		//for pagging set information
		$this->load->library('pagination');
		$config['per_page'] = '20';
		$config['base_url'] = base_url().'groups/?searchFilter='.$search.'&filter_groups='.$filter_groups.'&filter_group_type='.$filter_group_type.'';
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

		$data['groups']         =   $this->groups_model->get_all_groups($config['per_page'],$config['uri_segment'], $filter_groups, $filter_group_type);
		$data['page_name']		=	'group_view';
		$data['selected']		=	'groups';
		$data['sub_selected']   =   'list_rate_groups';
		$data['page_title']		=	'GROUPS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/groups_sub_menu';
		$data['main_content']	=	'groups/groups_view';
		$this->load->view('default/template',$data);
	}

	//enable or disable customer 
	function enable_disable_group()
	{
		$data['rate_group_id']           = $this->input->post('rate_group_id');
		$data['status']             = $this->input->post('status');
		$this->groups_model->enable_disable_group($data);
	}

	//new group
	function new_rate_group()
	{
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
		$data['digits']         = $this->input->post('digits');
		$data['rate']           = $this->input->post('rate');
		$data['costrate']       = $this->input->post('costrate');
		$data['buyblock']       = $this->input->post('buyblock');
		$data['sellblock']      = $this->input->post('sellblock');
		$data['intrastate']     = $this->input->post('intrastate');
		$data['intralata']      = $this->input->post('intralata');
		$data['leadstrip']      = $this->input->post('leadstrip');
		$data['trailstrip']     = $this->input->post('trailstrip');
		$data['prefix']         = $this->input->post('prefix');
		$data['suffix']         = $this->input->post('suffix');
		$data['profile']        = $this->input->post('profile');
		$data['startdate']      = $this->input->post('startdate');
		$data['enddate']        = $this->input->post('enddate');
		$data['quality']        = $this->input->post('quality');
		$data['reliability']    = $this->input->post('reliability');
		$data['lrn']            = $this->input->post('lrn');
		$data['carrier']        = $this->input->post('carrier');
		$data['group']          = $this->input->post('group');
		$data['country']        = $this->input->post('country');

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
		$data['rate_group_id']               = $this->input->post('rate_group_id');
		$data['group_rate_table_name']  = $this->groups_model->group_any_cell($data['rate_group_id'], 'group_rate_table');

		$data['digits']      = $this->input->post('digits');
		$data['rate']        = $this->input->post('rate');
		$data['costrate']    = $this->input->post('costrate');
		$data['buyblock']    = $this->input->post('buyblock');
		$data['sellblock']   = $this->input->post('sellblock');
		$data['intrastate']  = $this->input->post('intrastate');
		$data['intralata']   = $this->input->post('intralata');
		$data['leadstrip']   = $this->input->post('leadstrip');
		$data['trailstrip']  = $this->input->post('trailstrip');
		$data['prefix']      = $this->input->post('prefix');
		$data['suffix']      = $this->input->post('suffix');
		$data['profile']     = $this->input->post('profile');
		$data['startdate']   = $this->input->post('startdate');
		$data['enddate']     = $this->input->post('enddate');
		$data['quality']     = $this->input->post('quality');
		$data['reliability'] = $this->input->post('reliability');
		$data['lrn']         = $this->input->post('lrn');
		$data['carrier']     = $this->input->post('carrier');
		$data['country']     = $this->input->post('country');
		$data['old_digits']  = $this->input->post('old_digits');
		$data['old_carrier'] = $this->input->post('old_carrier');

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
}