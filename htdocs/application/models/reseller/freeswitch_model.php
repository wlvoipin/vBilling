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

class Freeswitch_model extends CI_Model {

	// list all profiles
	function get_all_profiles()
	{
		$sql = "SELECT * FROM sofia_conf";
		$query = $this->db->query($sql);
		return $query;
	}

	//any cell 
	function sofia_profile_any_cell($sofia_id, $col_name)
	{
		$sql = "SELECT * FROM sofia_conf WHERE id = '".$sofia_id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();

		return $row->$col_name;
	}

	//any cell 
	function sofia_settings_any_cell($id, $col_name)
	{
		$sql = "SELECT * FROM sofia_settings WHERE id='".$id."' ";
		$query = $this->db->query($sql);
		$row = $query->row();

		return $row->$col_name;
	}

	//check profile name in use
	function check_profile_name_in_use($name)
	{
		$sql = "SELECT * FROM sofia_conf WHERE profile_name = '".$name."'";
		$query = $this->db->query($sql);
		return $query; 
	}

	//check sip ip conflicts
	function check_sip_ip_conflicts($sip_ip)
	{
		$sql = "SELECT * FROM sofia_settings WHERE param_name = 'sip-ip' && param_value = '".$sip_ip."'";
		$query = $this->db->query($sql);
		return $query;
	}

	//check sip port conflicts 
	function check_sip_port_conflicts($sip_port, $sofia_id)
	{
		$sql = "SELECT * FROM sofia_settings WHERE param_name = 'sip-port' && param_value = '".$sip_port."' && sofia_id = '".$sofia_id."'";
		$query = $this->db->query($sql);
		return $query;
	}

	//create sofia profile 
	function create_sofia_profile($name)
	{
		$sql = "INSERT INTO sofia_conf (profile_name) VALUES ('".$name."')";
		$query = $this->db->query($sql);
		return $this->db->insert_id();
	}

	//insert default sofia settings 
	function insert_default_sofia_settings($new_sofia_id, $sip_ip, $sip_port)
	{
		$sql1 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'sip-ip', '".$sip_ip."') ";
		$sql2 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'sip-port', '".$sip_port."') ";
		$sql3 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'rtp-ip', '".$sip_ip."') ";

		//hardcoded settings 
		$sql4 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'inbound-reg-force-matching-username', 'true') ";
		$sql5 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'dialplan', 'XML') ";
		$sql6 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'manual-redirect', 'false') ";
		$sql7 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'disable-transfer', 'true') ";
		$sql8 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'user-agent-string', 'vBilling - http://www.vbilling.org/') ";
		$sql9 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'enable-100rel', 'false') ";
		$sql10 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'tls', 'false') ";
		$sql11 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'dtmf-duration', '2000') ";
		$sql12 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'all-reg-options-ping', 'true') ";
		$sql13 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'unregister-on-options-fail', 'true') ";
		$sql14 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'aggressive-nat-detection', 'true') ";
		$sql15 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'enable-timer', 'false') ";
		$sql16 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'minimum-session-expires', '60') ";
		$sql17 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'session-timeout', '60') ";
		$sql18 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'debug', 'info') ";
		$sql19 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'sip-trace', 'false') ";
		$sql20 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'apply-inbound-acl', 'default') ";
		$sql21 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'rtp-timeout-sec', '60') ";
		$sql22 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'disable-transcoding', 'true') ";
		$sql23 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'inbound-bypass-media', 'true') ";
		$sql24 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'inbound-proxy-media', 'false') ";
		$sql25 = "INSERT INTO sofia_settings (sofia_id, param_name, param_value) VALUES ('".$new_sofia_id."', 'auth-calls', 'true') ";

		$this->db->query($sql1);
		$this->db->query($sql2);
		$this->db->query($sql3);
		$this->db->query($sql4);
		$this->db->query($sql5);
		$this->db->query($sql6);
		$this->db->query($sql7);
		$this->db->query($sql8);
		$this->db->query($sql9);
		$this->db->query($sql10);
		$this->db->query($sql11);
		$this->db->query($sql12);
		$this->db->query($sql13);
		$this->db->query($sql14);
		$this->db->query($sql15);
		$this->db->query($sql16);
		$this->db->query($sql17);
		$this->db->query($sql18);
		$this->db->query($sql19);
		$this->db->query($sql20);
		$this->db->query($sql21);
		$this->db->query($sql22);
		$this->db->query($sql23);
		$this->db->query($sql24);
		$this->db->query($sql25);

	}

	//delete sofia profile 
	function delete_sofia_profile($sofia_id)
	{
		$sql1 = "DELETE FROM sofia_conf WHERE id = '".$sofia_id."' ";
		$sql2 = "DELETE FROM sofia_gateways WHERE sofia_id = '".$sofia_id."' ";
		$sql3 = "DELETE FROM sofia_settings WHERE sofia_id = '".$sofia_id."' ";

		$this->db->query($sql1);
		$this->db->query($sql2);
		$this->db->query($sql3);
	}

	function get_sofia_gateways($sofia_id)
	{
		$sql = "SELECT * FROM sofia_gateways where sofia_id = '".$sofia_id."' group by gateway_name";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_sofia_settings($sofia_id, $type = '')
	{
		$condition = "";
		if($type != '')
		{
			$condition = "AND b.type = '".$type."'";
		}

		$sql = "SELECT a.*,b.type FROM sofia_settings a LEFT JOIN sofia_settings_params b ON b.param_name = a.param_name WHERE sofia_id = '".$sofia_id."' ".$condition."";
		$query = $this->db->query($sql);
		return $query;
	}

	function getSofiaSettingsAllTypes($type = '')
	{
		$sql = "SELECT distinct(type) FROM sofia_settings_params ORDER BY type ASC";
		$query = $this->db->query($sql);

		$data = '';
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($type == $row->type)
				{
					$data .= "<option value='".$row->type."' selected>".$row->type."</option>";
				}
				else
				{
					$data .= "<option value='".$row->type."'>".$row->type."</option>";
				}
			}
		}
		echo $data;
	}

	function gateway_name_in_use($sofia_id, $gateway_name)
	{
		$sql = "SELECT * FROM sofia_gateways WHERE sofia_id = '".$sofia_id."' && gateway_name = '".$gateway_name."'";
		$query = $this->db->query($sql);
		return $query;
	}

	function check_gateway_proxy($sofia_id, $proxy)
	{
		$sql = "SELECT * FROM sofia_gateways WHERE sofia_id = '".$sofia_id."' && gateway_param = 'proxy' && gateway_value = '".$proxy."'";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_gateway_detail($sofia_id, $gateway_name)
	{
		$sql = "SELECT * FROM  sofia_gateways  WHERE sofia_id = '".$sofia_id."' && gateway_name = '".$gateway_name."'";
		$query = $this->db->query($sql);
		return $query;
	}

	function get_gateway_config_cell($id, $col_name)
	{
		$sql="SELECT * FROM sofia_gateways WHERE id='".$id."' ";
		$query = $this->db->query($sql);

		$row = $query->row();

		return $row->$col_name;
	}
}
?>
