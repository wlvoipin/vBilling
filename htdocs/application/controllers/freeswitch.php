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

class Freeswitch extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('freeswitch_model');
		// //validate login
		// if (!user_login())
		// {
		// redirect ('home/');
		// }
		// else
		// {
		// if($this->session->userdata('user_type') == 'customer')
		// {
		// redirect ('customer/');
		// }
		// }
	}

	function index()
	{
		$data['profiles']       =   $this->freeswitch_model->get_all_profiles();
		$data['page_name']		=	'view_profiles';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   'list_profiles';
		$data['page_title']		=	'PROFILES';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/profile_view';
		$this->load->view('default/template',$data);
	}

	function new_profile()
	{
		$data['page_name']		=	'new_profile';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   'new_profile';
		$data['page_title']		=	'NEW PROFILE';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/new_profile_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_profile()
	{
		$name       = $this->input->post('name');  
		$sip_ip     = $this->input->post('sipip');  
		$sip_port   = $this->input->post('sipport');

		$check_name_in_use = $this->freeswitch_model->check_profile_name_in_use($name);
		if($check_name_in_use->num_rows() > 0)
		{
			echo "Profile name already exists"; 
			exit;
		}
		else //name check passed
		{
			$check_sip_ip_conflicts = $this->freeswitch_model->check_sip_ip_conflicts($sip_ip);
			$conflict = 0;
			if($check_sip_ip_conflicts->num_rows() > 0)
			{
				foreach($check_sip_ip_conflicts->result() as $row)
				{
					$sofia_id = $row->sofia_id;

					$check_sip_port_conflicts = $this->freeswitch_model->check_sip_port_conflicts($sip_port, $sofia_id);

					if($check_sip_port_conflicts->num_rows() > 0) //we found the conflicting
					{
						$conflict = 1;
						break;
					}
				}
			}

			if($conflict == 0) //if there are no conflicts
			{
				$new_sofia_id = $this->freeswitch_model->create_sofia_profile($name);
				$this->freeswitch_model->insert_default_sofia_settings($new_sofia_id, $sip_ip, $sip_port);

				//restart esl server
				$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
				$cmd = "api sofia profile ".$name." start";
				$response = $this->esl->event_socket_request($fp, $cmd);
				fclose($fp);
			}
			else
			{
				echo "ERROR: Port (".$sip_port.") already in use for the SIP IP (".$sip_ip.")";
				exit;
			}

		}
	}

	function delete_profile()
	{
		$sofia_id = $this->input->post('sofia_id');
		$profile_name = $this->freeswitch_model->sofia_profile_any_cell($sofia_id, 'profile_name');
		$this->freeswitch_model->delete_sofia_profile($sofia_id);

		//restart esl
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api sofia profile ".$profile_name." stop";
		$response = $this->esl->event_socket_request($fp, $cmd);
		fclose($fp);
	}

	function profile_detail($sofia_id)
	{
		$type = "";
		if(isset($_POST['sofia_sett_param_type']))
		{
			$type = $this->input->post('sofia_sett_param_type');
		}

		$data['gateways']		=	$this->freeswitch_model->get_sofia_gateways($sofia_id);
		$data['settings']		=	$this->freeswitch_model->get_sofia_settings($sofia_id, $type);
		$data['sofia_id']       =   $sofia_id;
		$data['type']       	=   $type;
		$data['page_name']		=	'sofia_profile_detail';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'SOFIA PROFILE DETAILS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/sofia_detail_view';
		$this->load->view('default/template',$data);
	}

	function get_settings_edit_contents()
	{
		$type   = $this->input->post('type');
		$id     = $this->input->post('id');
		$condition = "";
		$data = "";

		if($type == '')
		{
			$condition = "";
		}
		else
		{
			$condition = "AND b.type = '".$type."'";
		}

		$sql = "SELECT a.*,b.type FROM sofia_settings a LEFT JOIN sofia_settings_params b ON b.param_name = a.param_name WHERE sofia_id = '".$id."' ".$condition."";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{

			foreach($query->result() as $row) {
				$data .= '<tr height="30px">
					<td>
					<select name="settings_param[]" class="textfield settings_param">
					<option value="'.$row->param_name.'">'.$row->param_name.'</option>
					</select>
					</td>
					<td><input type="text" name="settings_value[]" class="settings_value textfield" value="'.$row->param_value.'"/></td>
					</tr>';
			}												
		}
		else
		{
			$data .= '<tr><td align="center" colspan="2" style="color:red;">No Results Found</td></tr>';
		}
		echo $data;
	}

	function update_settings()
	{
		$settings_param = $this->input->post('settings_param');
		$settings_value = $this->input->post('settings_value');
		$profile_id = $this->input->post('hidden_profile_id');
		$type = $this->input->post('setting_type');
		$profile_name = $this->freeswitch_model->sofia_profile_any_cell($profile_id, 'profile_name');

		if($type == '')
		{
			$sql = "DELETE FROM sofia_settings WHERE sofia_id = '".$profile_id."' ";
			$query = $this->db->query($sql);
		}
		else
		{
			$sql2 = "DELETE a.* FROM sofia_settings a LEFT JOIN sofia_settings_params b ON b.param_name = a.param_name WHERE b.type = '".$type."' && a.sofia_id = '".$profile_id."' ";
			$query2 = $this->db->query($sql2);
		}

		for($i=0; $i < count($settings_param); $i++){

			$sql3 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$profile_id."', '".$settings_param[$i]."', '".$settings_value[$i]."') ";
			$query3 = $this->db->query($sql3);
		}

		//restart esl server
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api sofia profile ".$profile_name." rescan reloadxml";
		$response = $this->esl->event_socket_request($fp, $cmd);
		fclose($fp);
	}

	function update_settings_on_add_row()
	{
		$profile_id = $_POST['hidden_profile_id'];
		$form_fields_count = $_POST['form_fields_count'];
		$type = $_POST['setting_type'];
		$profile_name = $this->freeswitch_model->sofia_profile_any_cell($profile_id, 'profile_name');

		if($type == '')
		{
			$sql = "DELETE FROM sofia_settings WHERE sofia_id = '".$profile_id."'";
			$query = $this->db->query($sql);
		}
		else
		{
			$sql = "DELETE a.* FROM sofia_settings a LEFT JOIN sofia_settings_params b ON b.param_name = a.param_name WHERE b.type = '".$type."' && a.sofia_id = '".$profile_id."' ";
			$query = $this->db->query($sql);
		}
		$condition = '';
		$count = 0;

		if($form_fields_count > 0)
		{
			$settings_param = $this->input->post('settings_param');
			$settings_value = $this->input->post('settings_value');
			for($i=0; $i < count($settings_param); $i++){

				$sql2 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$profile_id."', '".$settings_param[$i]."', '".$settings_value[$i]."') ";
				$query2 = $this->db->query($sql2);

				if($count == 0)
				{
					$condition .= "WHERE param_name != '".$settings_param[$i]."'";
				}
				else
				{
					$condition .= "AND param_name != '".$settings_param[$i]."'";
				}
				$count = $count + 1;
			}

			//restart esl server
			$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
			$cmd = "api sofia profile ".$profile_name." rescan reloadxml";
			$response = $this->esl->event_socket_request($fp, $cmd);
			fclose($fp);
		}

		$new_row = '';
		if($condition == '' && $type != '')
		{
			$query3 = "SELECT * FROM sofia_settings_params WHERE type = '".$type."' ";
		}
		else if($condition != '' && $type == '')
		{
			$query3 = "SELECT * FROM sofia_settings_params ".$condition."";
		}
		else if($condition != '' && $type != '')
		{
			$query3 = "SELECT * FROM sofia_settings_params ".$condition." AND type='".$type."' ";
		}
		else if($condition == '' && $type == '')
		{
			$query3 = "SELECT * FROM sofia_settings_params";
		}

		$sql3 = $this->db->query($query3);

		if($sql3->num_rows() > 0)
		{
			echo '<tr height="30px">';
			echo '<td>';
			echo '<select class="settings_param textfield" name="settings_param[]">';
			foreach($sql3->result() as $row) 
			{
				$name = $row->param_name;
				echo "<option value='".$name."'>".$name."</option>";
			}
			echo '</select>';
			echo '</td>';
			echo '<td><input type="text" class="settings_value textfield" name="settings_value[]"></td>';
			echo '</tr>';
		}
		else
		{
			echo 'end';
		}
	}

	function get_settings_main_contents()
	{
		$type   = $this->input->post('type');
		$id     = $this->input->post('id');
		$condition = "";
		$data = "";

		if($type == '')
		{
			$condition = "";
		}
		else
		{
			$condition = "AND b.type = '".$type."'";
		}

		$query = "SELECT a.*,b.type FROM sofia_settings a LEFT JOIN sofia_settings_params b ON b.param_name = a.param_name WHERE sofia_id = '".$id."' ".$condition."";
		$sql = $this->db->query($query);

		if($sql->num_rows() > 0)
		{
			$countt = 1;
			foreach($sql->result() as $row) {
				if($countt % 2)
				{
					$bg = "bgcolor='#E6E5E5'";
				}
				else
				{
					$bg = "";
				}
				$data .= '<tr class="main_text" height="20px" '.$bg.'>
					<td align="left">'.$row->param_name.'</td>
					<td align="left">'.$row->param_value.'</td>
					<td align="left"><a class="delete_setting" id="'.$row->id.'" href="#"><img style="width:16px;margin-left:15px;border:none;cursor:pointer;" src="'.base_url().'assets/images/button_cancel.png"></a></td>
				</tr>';
				$countt++;                           
			}												
		}
		else
		{
			$data .= '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>';
		}
		echo $data;
	}

	function delete_single_setting()
	{
		$id = $this->input->post('id');
		$profile_name = $this->freeswitch_model->sofia_profile_any_cell($this->freeswitch_model->sofia_settings_any_cell($id, 'sofia_id'), 'profile_name');

		$query = "DELETE FROM sofia_settings WHERE id = '".$id."' ";
		$sql = $this->db->query($query);

		//restart esl server
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api sofia profile ".$profile_name." rescan reloadxml";
		$response = $this->esl->event_socket_request($fp, $cmd);
		fclose($fp);
	}

	function new_gateway($sofia_id)
	{
		$data['sofia_id']       =   $sofia_id;
		$data['page_name']		=	'sofia_profile_new_gateway';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'NEW GATEWAY';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/new_gateway_view';
		$this->load->view('default/template',$data);
	}

	function insert_new_gateway()
	{
		$gateway_name   = $this->input->post('name');
		$username       = $this->input->post('username');
		$password       = $this->input->post('password');
		$proxy          = $this->input->post('proxy');
		$register       = $this->input->post('register');
        $channels       = $this->input->post('channels');
		$sofia_id       = $this->input->post('hidden_profile_id');
		$profile_name   = $this->freeswitch_model->sofia_profile_any_cell($sofia_id, 'profile_name');
		$name_in_use    = $this->freeswitch_model->gateway_name_in_use($sofia_id, $gateway_name);

		if($name_in_use->num_rows() > 0)
		{
			echo "gateway_name_in_use";
			exit;
		}
		else
		{
			$checkProxy = $this->freeswitch_model->check_gateway_proxy($sofia_id, $proxy);
			if($checkProxy->num_rows() > 0) //proxy already exists
			{
				echo "proxy_in_use";
				exit;
			}
			else
			{
				$sql1 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'username', '".$username."') ";
				$sql2 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'password', '".$password."') ";
				$sql3 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'proxy', '".$proxy."') ";
				$sql4 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'register', '".$register."') ";
                $sql5 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'channels', '".$channels."') ";
                $sql6 = "INSERT INTO sofia_gateways (sofia_id,gateway_name,gateway_param,gateway_value) VALUES ('".$sofia_id."', '".$gateway_name."', 'caller-id-in-from', 'true') ";
				$this->db->query($sql1);
				$this->db->query($sql2);
				$this->db->query($sql3);
				$this->db->query($sql4);
                $this->db->query($sql5);
                $this->db->query($sql6);

				//restart esl server
				$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
				$cmd = "api sofia profile ".$profile_name." rescan reloadxml";
				$response = $this->esl->event_socket_request($fp, $cmd);
				fclose($fp);
			}
		}
	}

	function delete_gateway()
	{
		$id             = $this->input->post('sofia_id');
		$gateway_name   = $this->input->post('gateway_name');
		$profile_name   = $this->freeswitch_model->sofia_profile_any_cell($id, 'profile_name');
		
		$sql    = "DELETE FROM sofia_gateways WHERE sofia_id = '".$id."' && gateway_name = '".$gateway_name."' ";
		$query  = $this->db->query($sql);

		//restart esl server
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api sofia profile ".$profile_name." killgw ".$gateway_name." reloadxml";
		$response = $this->esl->event_socket_request($fp, $cmd);
		fclose($fp);
	}

	function gateway_detail($sofia_id, $gateway_name)
	{
		$data['sofia_id']       =   $sofia_id;
		$data['gateway_name']   =   $gateway_name;
		$data['gateways']       =   $this->freeswitch_model->get_gateway_detail($sofia_id, $gateway_name);
		$data['page_name']		=	'gateway_detail_view';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'GATEWAY DETAILS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/gateway_detail_view';
		$this->load->view('default/template',$data);
	}

	function edit_gateway($sofia_id, $gateway_name)
	{
		$data['sofia_id']       =   $sofia_id;
		$data['gateway_name']   =   $gateway_name;
		$data['gateways']       =   $this->freeswitch_model->get_gateway_detail($sofia_id, $gateway_name);
		$data['page_name']		=	'edit_gateway_view';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   '';
		$data['page_title']		=	'EDIT GATEWAY';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/gateway_edit_view';
		$this->load->view('default/template',$data);
	}

	function edit_gateway_db_add_row()
	{
		$profile_id         = $this->input->post('hidden_profile_id');
		$gateway_name       = $this->input->post('hidden_gateway_name');
		$form_fields_count  = $this->input->post('form_fields_count');
		$profile_name       = $this->freeswitch_model->sofia_profile_any_cell($profile_id, 'profile_name');
		$condition = '';
		$count = 0;

		if($form_fields_count > 0)
		{
			$gateway_param = $this->input->post('gateway_param');
			$gateway_value = $this->input->post('gateway_value');
			$query = "DELETE FROM sofia_gateways WHERE sofia_id = '".$profile_id."' && gateway_name = '".$gateway_name."'";
			$sql = $this->db->query($query);

			for($i=0; $i < count($gateway_param);  $i++){
				$query2 = "INSERT INTO sofia_gateways (sofia_id, gateway_name, gateway_param, gateway_value) VALUES ('".$profile_id."', '".$gateway_name."', '".$gateway_param[$i]."', '".$gateway_value[$i]."') ";
				$sql2 = $this->db->query($query2);

				if($count == 0)
				{
					$condition .= "WHERE param_name != '".$gateway_param[$i]."'";
				}
				else
				{
					$condition .= "AND param_name != '".$gateway_param[$i]."'";
				}
				$count = $count + 1;
			}

			//restart esl server
			$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
			$cmd = "api sofia profile ".$profile_name." killgw ".$gateway_name."";
			$response = $this->esl->event_socket_request($fp, $cmd);
				usleep(5000000); //sleep for 5 seconds 
			$cmd2 = "api sofia profile ".$profile_name." rescan";
			$response2 = $this->esl->event_socket_request($fp, $cmd2);
			fclose($fp);
		}

		$new_row = '';
		$query3 = "SELECT * FROM sofia_gateways_params ".$condition." ";
		$sql3 = $this->db->query($query3);
		if($sql3->num_rows() > 0)
		{
			echo '<tr class="main_text" height="30px">';
			echo '<td align="center">';
			echo '<select class="textfield gateway_param gateway_param_box" name="gateway_param[]">';
			$countt = 0;
            $default_value_for_caller_in_from = '';
            foreach($sql3->result() as $rowSql) 
			{
				$name = $rowSql->param_name;
                if($countt == 0)
                {
                    if($name == 'caller-id-in-from')
                    {   
                        $default_value_for_caller_in_from = 'true';
                    }
                }
				echo "<option value='".$name."'>".$name."</option>";
                $countt = $countt + 1;
			}
			echo '</select>';
			echo '</td>';
			echo '<td align="center"><input type="text" class="textfield gateway_value" name="gateway_value[]" value="'.$default_value_for_caller_in_from.'"></td>';
			echo '</tr>';
		}
		else
		{
			echo 'end';
		}
	}

	function edit_gateway_db_form()
	{
		$gateway_param      = $this->input->post('gateway_param');
		$gateway_value      = $this->input->post('gateway_value');
		$profile_id         = $this->input->post('hidden_profile_id');
		$gateway_name       = $this->input->post('hidden_gateway_name');
		$profile_name       = $this->freeswitch_model->sofia_profile_any_cell($profile_id, 'profile_name');

		$query = "DELETE FROM sofia_gateways WHERE sofia_id = '".$profile_id."' && gateway_name = '".$gateway_name."' ";
		$sql = $this->db->query($query);

		for($i=0; $i < count($gateway_param); $i++){
			$query2= "INSERT INTO sofia_gateways (sofia_id, gateway_name, gateway_param, gateway_value) VALUES ('".$profile_id."', '".$gateway_name."', '".$gateway_param[$i]."', '".$gateway_value[$i]."') ";
			$sql2 = $this->db->query($query2);
		}

		//restart esl server
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);

		$cmd = "api sofia profile ".$profile_name." killgw ".$gateway_name."";
		$response = $this->esl->event_socket_request($fp, $cmd);

		usleep(5000000); //sleep for 5 seconds 

		$cmd2 = "api sofia profile ".$profile_name." rescan";
		$response2 = $this->esl->event_socket_request($fp, $cmd2);
		fclose($fp);
	}

	function delete_gateway_config()
	{
		$id             = $this->input->post('id');
		$gateway_name   = $this->freeswitch_model->get_gateway_config_cell($id, 'gateway_name');
		$profile_name   = $this->freeswitch_model->sofia_profile_any_cell($this->freeswitch_model->get_gateway_config_cell($id, 'sofia_id'), 'profile_name');

		$query  = "DELETE FROM sofia_gateways WHERE id = '".$id."' ";
		$sql    = $this->db->query($query);

		//restart esl server
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);

		$cmd = "api sofia profile ".$profile_name." killgw ".$gateway_name."";
		$response = $this->esl->event_socket_request($fp, $cmd);

		usleep(5000000); //sleep for 5 seconds 

		$cmd2 = "api sofia profile ".$profile_name." rescan";
		$response2 = $this->esl->event_socket_request($fp, $cmd2);
		fclose($fp);
	}
	//***************************************ESL FUNCTIONS *******************************************
	function freeswitch_esl()
	{
		$data['page_name']		=	'freeswitch_esl';
		$data['selected']		=	'freeswitch';
		$data['sub_selected']   =   'freeswitch_esl';
		$data['page_title']		=	'FREESWITCH ESL';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/freeswitch_sub_menu';
		$data['main_content']	=	'freeswitch/esl_view';
		$this->load->view('default/template',$data);
	}

	//generate the xml
	function generate_xml()
	{
		header ("Content-Type:text/xml");
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api show calls count as xml";
		$response = $this->esl->event_socket_request($fp, $cmd);
		fclose($fp);
		print $response;
	}

	function get_manual_command_data()
	{
		$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
		$cmd = "api ".$_GET['command']."";
		$response = $this->esl->event_socket_request($fp, $cmd);
		echo nl2br($response); 
		fclose($fp);
	}

	// function reload_acl()
	// {
	// 	$fp = $this->esl->event_socket_create($this->esl->ESL_host, $this->esl->ESL_port, $this->esl->ESL_password);
	// 	$cmd = "api reloadacl";
	// 	$response = $this->esl->event_socket_request($fp, $cmd);
	// 	echo $response; 
	// 	fclose($fp);
	// }

	function get_connected_calls_ajax()
	{
		echo get_connected_calls();
	}

	function get_server_status()
	{
		echo get_status();
	}
}
