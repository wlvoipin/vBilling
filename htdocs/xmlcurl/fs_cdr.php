<?php
/**
* @package  FS_CURL
* @contributor Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
* fs_cdr.php
*/
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	header('Location: index.php');
}

/**
* @package FS_CURL
* @license BSD
* @author Raymond Chandler (intralanman) <intralanman@gmail.com>
* @contributor Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
* @version 1.1
*
* Class for inserting xml CDR records
* @return object
*/
class fs_cdr extends fs_curl {
/**
* This variable will hold the XML CDR string
* @var string
*/
public $cdr;
/**
* This object is the objectified representation of the XML CDR
* @var XMLSimple Object
*/
public $xml_cdr;

/**
* This array will hold the db field and their corresponding value
* @var array
*/
public $values = array();

/**
* This array maps the database field names to XMLSimple paths
* @var array
*/
public $fields = array();
public $total_sell_cost = 0;
public $total_buy_cost = 0;

/**
* This is where we instantiate our parent and set up our CDR object
*/
public function fs_cdr() {
	$this->fs_curl();
	$this->cdr = stripslashes($this->request['cdr']);
	$this->xml_cdr = new SimpleXMLElement($this->cdr);
}

/**
* This is where we run the bulk of our logic through other methods
*/
public function main() {
	$this->set_record_values();
	$this->insert_cdr();
	if(($this->xml_cdr->variables->hangup_cause == 'NORMAL_CLEARING' || $this->xml_cdr->variables->hangup_cause == 'ALLOTTED_TIMEOUT') && $this->xml_cdr->variables->billsec > 0)
	{

		// If we have a good call, then deduct from customer account the duration of the call. No need of complex functions
		// We will not update the customer balance, after calculating the call cost, since we are already NORMAL_CLEARING and
		// duraton >0, we do the calc.

		// now that we have the new balance in $balance_after_charge, we can now update the DB
		$query = "UPDATE customers SET customer_balance = (customer_balance - (ceil(".$this->xml_cdr->variables->billsec." / ".$this->xml_cdr->variables->sell_initblock.") * (".$this->xml_cdr->variables->sell_rate." / 60) * ".$this->xml_cdr->variables->sell_initblock.")) WHERE customer_id = '".$this->xml_cdr->variables->customer_id."'";
		$this->debug($query);
		$this->db->exec($query);
	}
}   

/**
* This method will take the db fields and paths defined above and
* set the values array to be used for the insert
*/

public function set_record_values() {

	$this->fields["total_buy_cost"]  = round (ceil ((float)$this->xml_cdr->variables->billsec / (float)$this->xml_cdr->variables->buy_initblock) * ((float)$this->xml_cdr->variables->cost_rate / 60) * (float)$this->xml_cdr->variables->buy_initblock, 4);
	$this->fields["total_sell_cost"] = round (ceil((float)$this->xml_cdr->variables->billsec / (float)$this->xml_cdr->variables->sell_initblock) * ((float)$this->xml_cdr->variables->sell_rate / 60) * (float)$this->xml_cdr->variables->sell_initblock, 4);
	$this->fields["caller_id_name"] =  '$this->xml_cdr->callflow[0]->caller_profile->caller_id_name';
	$this->fields["caller_id_number"] =  '$this->xml_cdr->callflow[0]->caller_profile->caller_id_number';
	$this->fields["destination_number"] =  '$this->xml_cdr->callflow[0]->caller_profile->destination_number';
	$this->fields["context"] =  '$this->xml_cdr->callflow[0]->caller_profile->context';
	$this->fields["duration"] =  '$this->xml_cdr->variables->duration';
	$this->fields["billsec"] =  '$this->xml_cdr->variables->billsec';
	$this->fields["hangup_cause"] =  '$this->xml_cdr->variables->hangup_cause';
	$this->fields["uuid"] =  '$this->xml_cdr->callflow[0]->caller_profile->uuid';
	$this->fields["read_codec"] =  '$this->xml_cdr->variables->read_codec';
	$this->fields["write_codec"] =  '$this->xml_cdr->variables->write_codec';
	$this->fields["network_addr"] =  '$this->xml_cdr->callflow[0]->caller_profile->network_addr';
	$this->fields["username"] =  '$this->xml_cdr->callflow[0]->caller_profile->username';
	$this->fields["sip_user_agent"] =  'urldecode($this->xml_cdr->variables->sip_user_agent)';
	$this->fields["sip_hangup_disposition"] =  '$this->xml_cdr->variables->sip_hangup_disposition';
	$this->fields["ani"] =  '$this->xml_cdr->callflow[0]->caller_profile->ani';
	$this->fields["created_time"] =  '$this->xml_cdr->callflow[0]->times->created_time';
	$this->fields["profile_created_time"] =  '$this->xml_cdr->callflow[0]->times->profile_created_time';
	$this->fields["progress_media_time"] =  '$this->xml_cdr->callflow[0]->times->progress_media_time';
	$this->fields["answered_time"] =  '$this->xml_cdr->callflow[0]->times->answered_time';
	$this->fields["bridged_time"] =  '$this->xml_cdr->callflow[0]->times->bridged_time';
	$this->fields["hangup_time"] =  '$this->xml_cdr->callflow[0]->times->hangup_time';
	$this->fields["customer_group_rate_table"] =  '$this->xml_cdr->variables->customer_group_rate_table';
	$this->fields["customer_prepaid"] =  '$this->xml_cdr->variables->customer_prepaid';
	$this->fields["customer_balance"] =  '$this->xml_cdr->variables->customer_balance';
	$this->fields["customer_id"] =  '$this->xml_cdr->variables->customer_id';
	$this->fields["cidr"] =  '$this->xml_cdr->variables->cidr';
	$this->fields["sell_rate"] =  'urldecode($this->xml_cdr->variables->sell_rate)';
	$this->fields["cost_rate"] =  '$this->xml_cdr->variables->cost_rate';
	$this->fields["buy_initblock"] =  '$this->xml_cdr->variables->buy_initblock';
	$this->fields["sell_initblock"] =  '$this->xml_cdr->variables->sell_initblock';
	$this->fields["gateway"] =  '$this->xml_cdr->variables->gateway';
	$this->fields["sofia_id"] =  '$this->xml_cdr->variables->sofia_id';
	$this->fields["country_id"] =  '$this->xml_cdr->variables->country_id';
	$this->fields["rate_id"] =  '$this->xml_cdr->variables->rate_id';
	$this->fields["lcr_carrier_id"] =  '$this->xml_cdr->variables->lcr_carrier_id';
	// $this->fields["post_paid_balance"] =  '$this->xml_cdr->variables->post_paid_balance';

	foreach ($this->fields as $field => $run) {
		eval("\$str = $run;");
		$this->values["$field"] = "'$str'";
		// $this->debug($str);
	}
	// $this->debug(print_r($this->values, true));	//	Prints the CDR array, as we have mentioned the params above
}

/**
* finally do the insert of the CDR
*/
public function insert_cdr() {
	$query = sprintf(
		"INSERT INTO cdr (%s) VALUES (%s);",
		join(',', array_keys($this->values)), join(',', $this->values)
		);
	$this->debug($query);
	$this->db->exec($query);
}
}
?>