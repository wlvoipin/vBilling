<?php
/**
 * @package FS_CURL
 * @license BSD
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @contributor Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
 * @version 2.0
 * fs_cdr.php
 */
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	header('Location: index.php');
}

/**
 * Class for inserting xml CDR records
 * @return object
 */
class fs_cdr extends fs_curl {

	public $cdr;
	public $xml_cdr;
	public $values          = array();
	public $fields          = array();
	public $total_sell_cost = 0;
	public $total_buy_cost  = 0;
	public $parent_id       = 0;

/**
 * This is where we instantiate our parent and set up our CDR object
 */
public function fs_cdr() {
	$this->fs_curl();
	$this->cdr = stripslashes($this->request['cdr']);
	$this->xml_cdr = new SimpleXMLElement($this->cdr);
}

/**
 * v_round: specific function to use the same precision everywhere
 */
public function v_round($number) {
	$precision = 4;
	return round($number, $precision);
}

/**
 * calculate_costs: Calculates the cost for admin, reseller and customer
 */
public function calculate_costs($buyrate, $buyrateinitblock, $buyrateincrement, $rateinitial, $initblock, $billingblock)
{
	// // Basic variable initialization. Converted to handy variables for easy understanding	
	$callduration     = $this->xml_cdr->variables->billsec;					// billsec. Actual duration of the call
	// $buyrate          = $this->xml_cdr->variables->cost_rate;				// Our buying rate
	// $buyrateinitblock = $this->xml_cdr->variables->buyblock_min_duration;	// Minimum call duration charged by carrier
	// $buyrateincrement = $this->xml_cdr->variables->buy_initblock;			// Minimum increment after initial duration of call
	// $rateinitial      = $this->xml_cdr->variables->sell_rate;				// Our selling rate to the customer
	// $initblock        = $this->xml_cdr->variables->sellblock_min_duration;	// Minimum sell duration we want to charge after connection
	// $billingblock     = $this->xml_cdr->variables->sell_initblock;			// Sell_init_block
	$buycost          = 0;

	//	Calculate our buying cost
	// If user called less than minimum call duration, we set initial call duration equal to minimum duration of the call. Hint (30/30)
	if ((float)$callduration < (float)$buyrateinitblock)	// for some reason, it has to be float .. can't understand realy :-<
	{
		$callduration = $buyrateinitblock;
	}

	// Calculate actual buying cost
	if (((float)$buyrateincrement > 0) && ((float)$callduration > (float)$buyrateinitblock)) {
		$mod_sec = $callduration % $buyrateincrement;
		if ($mod_sec>0) $callduration += ($buyrateincrement - $mod_sec);
	}
	$buycost = ($callduration/60) * $buyrate;

	// Calculate our selling cost
	if ((float)$callduration < (float)$initblock) {
		$callduration = $initblock;
	}

	// Calculate actual selling cost
	if (((float)$billingblock > 0) && ((float)$callduration > (float)$initblock)) {
		$mod_sec = $callduration % $billingblock;
		if ($mod_sec>0) {
			$callduration += ($billingblock - $mod_sec);
		}
	}
	$sellcost = ($callduration/60) * $rateinitial;
	// $this->debug("BUY COST total_buy_cost: ".$this->v_round($buycost));
	// $this->debug("SELL COST total_sell_cost: ".$this->v_round($sellcost));
	return array('buycost'=>$this->v_round($buycost), 'sellcost'=>$this->v_round($sellcost));	// For admin only ??
}

/**
 * update customer balance
 *
 * @return void
 *
 **/
function update_balance()
{
	// Only if "NORMAL_CLEARING" or "ALLOTTED_TIMEOUT"
	if(($this->xml_cdr->variables->hangup_cause == 'NORMAL_CLEARING' || $this->xml_cdr->variables->hangup_cause == 'ALLOTTED_TIMEOUT') && $this->xml_cdr->variables->billsec > 0)
	{
		$ret_costs = $this->calculate_costs(
										  $this->xml_cdr->variables->cost_rate
										, $this->xml_cdr->variables->buyblock_min_duration
										, $this->xml_cdr->variables->buy_initblock
										, $this->xml_cdr->variables->sell_rate
										, $this->xml_cdr->variables->sellblock_min_duration
										, $this->xml_cdr->variables->sell_initblock
										);

		$query = "UPDATE customers SET customer_balance = (customer_balance - ".$ret_costs[sellcost].") WHERE customer_id = '".$this->xml_cdr->variables->customer_id."'";
		// $this->debug($query);
		$this->db->exec($query);

		//if this customer has a parent deduct his parent balance 
		if($this->xml_cdr->variables->parent_id != '0')
		{
			$query = "UPDATE customers SET customer_balance = (customer_balance - ".$ret_costs[buycost].") WHERE customer_id = '".$this->xml_cdr->variables->parent_id."'";
			// $this->debug($query);
			$this->db->exec($query);
		}

		// if this customer has a grand parent deduct his balance too 
		if($this->xml_cdr->variables->grand_parent_id != '0')
		{
			if($this->xml_cdr->variables->reseller_rate_group != '0' && $this->xml_cdr->variables->reseller_rate_group != '') //precaution
			{
				//get the Reseller rate data
				$query = "SELECT * FROM ".$this->xml_cdr->variables->reseller_rate_group." WHERE id = ".$this->xml_cdr->variables->reseller_rate_id."";
				// $this->debug($query);
				$objReseller = $this->db->queryAll($query);

				// reseller buy and sell cost
				$admin_sell_buy_cost = $this->calculate_costs(
															  $objReseller[0]['cost_rate']
															, $objReseller[0]['buyblock_min_duration']
															, $objReseller[0]['buy_initblock']
															, $objReseller[0]['sell_rate']
															, $objReseller[0]['sellblock_min_duration']
															, $objReseller[0]['sell_initblock']
															);

				$total_reseller_buy_cost = $admin_sell_buy_cost[buycost];
				$total_reseller_sell_cost = $admin_sell_buy_cost[sellcost];

				$query = "UPDATE customers SET customer_balance = (customer_balance - '".$total_reseller_buy_cost."') WHERE customer_id = '".$this->xml_cdr->variables->grand_parent_id."' ";
				// $this->debug($query);
				$this->db->exec($query);

			}
		}	// for if parent_id != 0

		//if this customer has a parent deduct his parent balance 
		if($this->xml_cdr->variables->parent_id != '0')
		{
			$query = "UPDATE customers SET customer_balance = (customer_balance - ".$ret_costs[buycost].") WHERE customer_id = '".$this->xml_cdr->variables->parent_id."'";
			$this->debug($query);
			$this->db->exec($query);
		}

	}
}	// function update_balance

/**
 * This is where we run the bulk of our logic through other methods
 */
public function main() {
	$this->set_records_values();
	$this->update_balance();
	$this->insert_cdr();

	//check if there were multiple gateways and there are few failed gateways attempts 
	if($this->xml_cdr->variables->multi_gateway == 1 && isset($this->xml_cdr->variables->FAILED_GATEWAY_total) )
	{
		//if there are failed gateways go to this function for the rest of the processing
		$this->multi_gateway_cdr();
	}
}	// for function main()   

/**
 * This method will take the db fields and paths defined above and
 * set the values array to be used for the insert
 */

public function set_records_values() {
	$failed_gateways_count = 0;
	if(isset($this->xml_cdr->variables->FAILED_GATEWAY_total))
	{
		$failed_gateways_count = $this->xml_cdr->variables->FAILED_GATEWAY_total;
	}

	/**
 	 * Calculate total_admin_sell_cost, total_admin_buy_cost, total_reseller_sell_cost, total_reseller_buy_cost
	 *
	 * Permutations
	 *
	 * if customer parent_id = 0 , this means that this customer was directly created by the admin , no need to calculate
	 * if customer parent_id != 0, but grand_parent_id = 0 this means it is a level 2 customer which was not created by admin
	 * now we have to calculate admin rates but not reseller rates becuasse direct rates will apply on reseller
	 *
	 * if customer parent_id != 0, and grand_parent_id != 0 this mean it is a level 1 customer calculate both admin and reseller rates,
	 * direct rate will apply on his parent 
	 *
	 * if parent_id = 0 (calculate nothing)
	 * if parent_id != 0 && grand_parent_id = 0 (only calculate admin rates)
	 * if parent_id != 0 && grand_parent_id != 0 (calculate both admin and reseller rates)
	 *
	 */

$total_admin_sell_cost    = 0;
$total_reseller_sell_cost = 0;
$total_admin_buy_cost     = 0;
$total_reseller_buy_cost  = 0;

if(($this->xml_cdr->variables->hangup_cause == 'NORMAL_CLEARING' || $this->xml_cdr->variables->hangup_cause == 'ALLOTTED_TIMEOUT') && $this->xml_cdr->variables->billsec > 0)
{
	if($this->xml_cdr->variables->parent_id != '0' && $this->xml_cdr->variables->grand_parent_id == '0') //calculate only admin rates
	{
		if($this->xml_cdr->variables->admin_rate_group != '0' && $this->xml_cdr->variables->admin_rate_group != '') //precaution
		{
			//get the admin rate data
			$query = "SELECT * FROM ".$this->xml_cdr->variables->admin_rate_group." WHERE id = ".$this->xml_cdr->variables->admin_rate_id."";
			$this->debug($query);
			$objAdmin = $this->db->queryAll($query);

			//admin buy and sell cost

			$admin_sell_buy_cost = $this->calculate_costs(
														  $objAdmin[0]['cost_rate']
														, $objAdmin[0]['buyblock_min_duration']
														, $objAdmin[0]['buy_initblock']
														, $objAdmin[0]['sell_rate']
														, $objAdmin[0]['sellblock_min_duration']
														, $objAdmin[0]['sell_initblock']
														);
			$total_admin_buy_cost = $admin_sell_buy_cost[buycost];
			$total_admin_sell_cost = $admin_sell_buy_cost[sellcost];
		}
	}
	else 
	if ($this->xml_cdr->variables->parent_id != '0' && $this->xml_cdr->variables->grand_parent_id != '0') //calculate both admin and reseller rates
	{
		if($this->xml_cdr->variables->admin_rate_group != '0' && $this->xml_cdr->variables->admin_rate_group != '' && $this->xml_cdr->variables->reseller_rate_group != '0' && $this->xml_cdr->variables->reseller_rate_group != '') //precaution
		{
			//get the admin rate data
			$query = "SELECT * FROM  ".$this->xml_cdr->variables->admin_rate_group." WHERE id = ".$this->xml_cdr->variables->admin_rate_id."";
			$this->debug($query);
			$objAdmin = $this->db->queryAll($query);

			// admin buy and sell cost
			$admin_sell_buy_cost = $this->calculate_costs(
														  $objAdmin[0]['cost_rate']
														, $objAdmin[0]['buyblock_min_duration']
														, $objAdmin[0]['buy_initblock']
														, $objAdmin[0]['sell_rate']
														, $objAdmin[0]['sellblock_min_duration']
														, $objAdmin[0]['sell_initblock']
														);
			$total_admin_buy_cost = $admin_sell_buy_cost[buycost];
			$total_admin_sell_cost = $admin_sell_buy_cost[sellcost];

			//get the Reseller rate data
			$query = "SELECT * FROM  ".$this->xml_cdr->variables->reseller_rate_group." WHERE id = ".$this->xml_cdr->variables->reseller_rate_id."";
			$this->debug($query);
			$objReseller = $this->db->queryAll($query);

			// reseller buy and sell cost
			$admin_sell_buy_cost = $this->calculate_costs(
														  $objReseller[0]['cost_rate']
														, $objReseller[0]['buyblock_min_duration']
														, $objReseller[0]['buy_initblock']
														, $objReseller[0]['sell_rate']
														, $objReseller[0]['sellblock_min_duration']
														, $objReseller[0]['sell_initblock']
														);
			$total_reseller_buy_cost = $admin_sell_buy_cost[buycost];
			$total_reseller_sell_cost = $admin_sell_buy_cost[sellcost];
		}	// for if admin_rate_group != 0
	}
}

$ret_costs = $this->calculate_costs(
									  $this->xml_cdr->variables->cost_rate
									, $this->xml_cdr->variables->buyblock_min_duration
									, $this->xml_cdr->variables->buy_initblock
									, $this->xml_cdr->variables->sell_rate
									, $this->xml_cdr->variables->sellblock_min_duration
									, $this->xml_cdr->variables->sell_initblock
									);

$this->fields["total_buy_cost"]            = $ret_costs[buycost];
$this->fields["total_sell_cost"]           = $ret_costs[sellcost];
$this->fields["total_admin_buy_cost"]      = '$total_admin_buy_cost';
$this->fields["total_reseller_buy_cost"]   = '$total_reseller_buy_cost';
$this->fields["total_admin_sell_cost"]     = '$total_admin_sell_cost';
$this->fields["total_reseller_sell_cost"]  = '$total_reseller_sell_cost';
$this->fields["caller_id_name"]            = '$this->xml_cdr->callflow[0]->caller_profile->caller_id_name';
$this->fields["caller_id_number"]          = '$this->xml_cdr->callflow[0]->caller_profile->caller_id_number';
$this->fields["destination_number"]        = '$this->xml_cdr->callflow[0]->caller_profile->destination_number';
$this->fields["context"]                   = '$this->xml_cdr->callflow[0]->caller_profile->context';
$this->fields["duration"]                  = '$this->xml_cdr->variables->duration';
$this->fields["billsec"]                   = '$this->xml_cdr->variables->billsec';
$this->fields["hangup_cause"]              = '$this->xml_cdr->variables->hangup_cause';
$this->fields["uuid"]                      = '$this->xml_cdr->callflow[0]->caller_profile->uuid';
$this->fields["read_codec"]                = '$this->xml_cdr->variables->read_codec';
$this->fields["write_codec"]               = '$this->xml_cdr->variables->write_codec';
$this->fields["network_addr"]              = '$this->xml_cdr->callflow[0]->caller_profile->network_addr';
$this->fields["username"]                  = '$this->xml_cdr->callflow[0]->caller_profile->username';
$this->fields["sip_user_agent"]            = 'urldecode($this->xml_cdr->variables->sip_user_agent)';
$this->fields["sip_hangup_disposition"]    = '$this->xml_cdr->variables->sip_hangup_disposition';
$this->fields["ani"]                       = '$this->xml_cdr->callflow[0]->caller_profile->ani';
$this->fields["created_time"]              = '$this->xml_cdr->callflow[0]->times->created_time';
$this->fields["profile_created_time"]      = '$this->xml_cdr->callflow[0]->times->profile_created_time';
$this->fields["progress_media_time"]       = '$this->xml_cdr->callflow[0]->times->progress_media_time';
$this->fields["answered_time"]             = '$this->xml_cdr->callflow[0]->times->answered_time';
$this->fields["bridged_time"]              = '$this->xml_cdr->callflow[0]->times->bridged_time';
$this->fields["hangup_time"]               = '$this->xml_cdr->callflow[0]->times->hangup_time';
$this->fields["customer_group_rate_table"] = '$this->xml_cdr->variables->customer_group_rate_table';
$this->fields["customer_prepaid"]          = '$this->xml_cdr->variables->customer_prepaid';
$this->fields["customer_balance"]          = '$this->xml_cdr->variables->customer_balance';
$this->fields["customer_id"]               = '$this->xml_cdr->variables->customer_id';
$this->fields["customer_acc_num"]          = '$this->xml_cdr->variables->customer_acc_num';     
$this->fields["cidr"]                      = '$this->xml_cdr->variables->cidr';
$this->fields["sell_rate"]                 = '$this->xml_cdr->variables->sell_rate';
$this->fields["cost_rate"]                 = '$this->xml_cdr->variables->cost_rate';
$this->fields["buyblock_min_duration"]     = '$this->xml_cdr->variables->buyblock_min_duration';
$this->fields["sellblock_min_duration"]    = '$this->xml_cdr->variables->sellblock_min_duration';
$this->fields["buy_initblock"]             = '$this->xml_cdr->variables->buy_initblock';
$this->fields["sell_initblock"]            = '$this->xml_cdr->variables->sell_initblock';
$this->fields["gateway"]                   = '$this->xml_cdr->variables->gateway';
$this->fields["sofia_id"]                  = '$this->xml_cdr->variables->sofia_id';
$this->fields["country_id"]                = '$this->xml_cdr->variables->country_id';
$this->fields["rate_id"]                   = '$this->xml_cdr->variables->rate_id';
$this->fields["lcr_carrier_id"]            = '$this->xml_cdr->variables->lcr_carrier_id';
$this->fields["is_multi_gateway"]          = '$this->xml_cdr->variables->multi_gateway';
$this->fields["total_failed_gateways"]     = '$failed_gateways_count';
$this->fields["parent_reseller_id"]        = '$this->xml_cdr->variables->parent_id';
$this->fields["grand_parent_reseller_id"]  = '$this->xml_cdr->variables->grand_parent_id';
$this->fields["reseller_level"]            = '$this->xml_cdr->variables->reseller_level';
$this->fields["admin_rate_group"]          = '$this->xml_cdr->variables->admin_rate_group';
$this->fields["admin_rate_id"]             = '$this->xml_cdr->variables->admin_rate_id';
$this->fields["reseller_rate_group"]       = '$this->xml_cdr->variables->reseller_rate_group';
$this->fields["reseller_rate_id"]          = '$this->xml_cdr->variables->reseller_rate_id';
// $this->fields["post_paid_balance"] =  '$this->xml_cdr->variables->post_paid_balance';

foreach ($this->fields as $field => $run) {
	eval("\$str = $run;");
	$this->values["$field"] = "'$str'";
}
}

/**
 * finally we insert the CDR in the DB
 */
public function insert_cdr() {
	$query = sprintf(
		"INSERT INTO cdr (%s) VALUES (%s);",
		join(',', array_keys($this->values)), join(',', $this->values)
		);
	// $this->debug($query);
	$this->db->exec($query);
	$this->parent_id = $this->db->lastInsertId();
}

/**
 * function to check and validate if calls are being sent through
 * more than 1 gateway (Multi Gateway, Gateway failover etc)
 */
public function multi_gateway_cdr()
{

	$failed_gateway_total      = $this->xml_cdr->variables->FAILED_GATEWAY_total; //total num of failed gateways 
	$gateway_sequence          = $this->xml_cdr->variables->current_application_data; //get the gateway sequence 
	$gateway_sequence_exploded = explode('%5Bgateway%3D', $gateway_sequence); //extract gateway names 
	$gateway_names             = array();

	for($i=1; $i < count($gateway_sequence_exploded); $i++) //extract gateway names continues 
	{
		$explode_first_part = explode('%5Dsofia/gateway/', $gateway_sequence_exploded[$i]);
		$gateway_part_first = $explode_first_part[0];
		$gateway_names[] = $gateway_part_first; //save the gateways names in an array 
	}

	$row_index = 1;
	for($j=0; $j<$failed_gateway_total; $j++)
	{
		$failed_gateway_name = $gateway_names[$j]; //failed gateway name

		//$cause_text = '$this->xml_cdr->variables->FAILED_GATEWAY_'.$row_index.'';
		/*$myFile = "test.txt";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $cause_text);
		fclose($fh);*/
		$cause_text               = '%3Changup_cause%3ENORMAL_TEMPORARY_FAILURE%3C/hangup_cause%3E';
		$cause_text_explode       = explode('%3Changup_cause%3E', $cause_text); //extract hangup cause
		$cause_text_explode_again = explode('%3C/hangup_cause%3E', $cause_text_explode[1]);  //extract hangup cause countinues
		$cause                    = $cause_text_explode_again[0]; // failed gateway cause

		//set the new fields array 
		$new_fields                              = array();
		$new_fields["total_buy_cost"]            = '0';
		$new_fields["total_sell_cost"]           = '0';
		$new_fields["total_admin_buy_cost"]      = '0';
		$new_fields["total_reseller_buy_cost"]   = '0';
		$new_fields["total_admin_sell_cost"]     = '0';
		$new_fields["total_reseller_sell_cost"]  = '0';
		$new_fields["caller_id_name"]            = '$this->xml_cdr->callflow[0]->caller_profile->caller_id_name';
		$new_fields["caller_id_number"]          = '$this->xml_cdr->callflow[0]->caller_profile->caller_id_number';
		$new_fields["destination_number"]        = '$this->xml_cdr->callflow[0]->caller_profile->destination_number';
		$new_fields["context"]                   = '$this->xml_cdr->callflow[0]->caller_profile->context';
		$new_fields["duration"]                  = '$this->xml_cdr->variables->duration';
		$new_fields["billsec"]                   = '0';
		$new_fields["hangup_cause"]              = '$cause';
		$new_fields["uuid"]                      = '$this->xml_cdr->callflow[0]->caller_profile->uuid';
		$new_fields["read_codec"]                = '$this->xml_cdr->variables->read_codec';
		$new_fields["write_codec"]               = '$this->xml_cdr->variables->write_codec';
		$new_fields["network_addr"]              = '$this->xml_cdr->callflow[0]->caller_profile->network_addr';
		$new_fields["username"]                  = '$this->xml_cdr->callflow[0]->caller_profile->username';
		$new_fields["sip_user_agent"]            = 'urldecode($this->xml_cdr->variables->sip_user_agent)';
		$new_fields["sip_hangup_disposition"]    = '$this->xml_cdr->variables->sip_hangup_disposition';
		$new_fields["ani"]                       = '$this->xml_cdr->callflow[0]->caller_profile->ani';
		$new_fields["created_time"]              = '$this->xml_cdr->callflow[0]->times->created_time';
		$new_fields["profile_created_time"]      = '$this->xml_cdr->callflow[0]->times->profile_created_time';
		$new_fields["progress_media_time"]       = '$this->xml_cdr->callflow[0]->times->progress_media_time';
		$new_fields["answered_time"]             = '$this->xml_cdr->callflow[0]->times->answered_time';
		$new_fields["bridged_time"]              = '$this->xml_cdr->callflow[0]->times->bridged_time';
		$new_fields["hangup_time"]               = '$this->xml_cdr->callflow[0]->times->hangup_time';
		$new_fields["customer_group_rate_table"] = '$this->xml_cdr->variables->customer_group_rate_table';
		$new_fields["customer_prepaid"]          = '$this->xml_cdr->variables->customer_prepaid';
		$new_fields["customer_balance"]          = '$this->xml_cdr->variables->customer_balance';
		$new_fields["customer_id"]               = '$this->xml_cdr->variables->customer_id';
		$new_fields["customer_acc_num"]          = '$this->xml_cdr->variables->customer_acc_num';
		$new_fields["cidr"]                      = '$this->xml_cdr->variables->cidr';
		$new_fields["sell_rate"]                 = '$this->xml_cdr->variables->sell_rate';
		$new_fields["cost_rate"]                 = '$this->xml_cdr->variables->cost_rate';
		$new_fields["buyblock_min_duration"]     = '$this->xml_cdr->variables->buyblock_min_duration';
		$new_fields["sellblock_min_duration"]    = '$this->xml_cdr->variables->sellblock_min_duration';
		$new_fields["buy_initblock"]             = '$this->xml_cdr->variables->buy_initblock';
		$new_fields["sell_initblock"]            = '$this->xml_cdr->variables->sell_initblock';
		$new_fields["gateway"]                   = '$failed_gateway_name';
		$new_fields["sofia_id"]                  = '$this->xml_cdr->variables->sofia_id';
		$new_fields["country_id"]                = '$this->xml_cdr->variables->country_id';
		$new_fields["rate_id"]                   = '$this->xml_cdr->variables->rate_id';
		$new_fields["lcr_carrier_id"]            = '$this->xml_cdr->variables->lcr_carrier_id';
		$new_fields["parent_id"]                 = '$this->parent_id';
		$new_fields["parent_reseller_id"]        = '$this->xml_cdr->variables->parent_id';
		$new_fields["grand_parent_reseller_id"]  = '$this->xml_cdr->variables->grand_parent_id';
		$new_fields["reseller_level"]            = '$this->xml_cdr->variables->reseller_level';
		$new_fields["admin_rate_group"]          = '$this->xml_cdr->variables->admin_rate_group';
		$new_fields["admin_rate_id"]             = '$this->xml_cdr->variables->admin_rate_id';
		$new_fields["reseller_rate_group"]       = '$this->xml_cdr->variables->reseller_rate_group';
		$new_fields["reseller_rate_id"]          = '$this->xml_cdr->variables->reseller_rate_id';
		//set the new values 
		$new_values = array();
		foreach ($new_fields as $field => $run) {
			eval("\$str = $run;");
			$new_values["$field"] = "'$str'";
		}

		//create the query
		$query = sprintf(
			"INSERT INTO cdr (%s) VALUES (%s);",
			join(',', array_keys($new_values)), join(',', $new_values)
			);

		//execute query 
		$this->db->exec($query);

		//increment counter
		$row_index = $row_index + 1;
	}  
}	// function multi_gateway_cdr
}
?>