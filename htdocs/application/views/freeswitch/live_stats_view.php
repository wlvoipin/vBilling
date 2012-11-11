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
?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.mcdropdown.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.qtip.css" type="text/css">
<script
	src="<?php echo base_url();?>assets/js/jquery.qtip.min.js" type="text/javascript">
</script>

<br />
<div
	class="success" id="success_div" style="display: none;"></div>

<!--POP UP ATTRIBUTES-->
<?php 
$atts = array(
		'width'      => '1000',
		'height'     => '800',
		'scrollbars' => 'yes',
		'status'     => 'yes',
		'resizable'  => 'yes',
		'screenx'    => '0',
		'screeny'    => '0'
);
?>
<!--END POP UP ATTRIBUTES--> 

<!--********************************FILTER BOX************************--><!--***************** END FILTER BOX ****************************-->

<table width="100%" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
          <style>
.sbHolder {
	width: 250px;
}

.sbOptions {
	width: 250px;
}
</style>
          <tr>
            <td colspan="16">&nbsp;</td>
          </tr>
          <tr class="bottom_link">
            <td width="7%" align="center">Date/Time</td>
            <td width="7%" align="center">Dialled Number</td>
            <td width="7%" align="center">Call Duration</td>
            <td width="7%" align="center">Call Status</td>
            <td width="7%" align="center">Carrier</td>
            <td width="7%" align="center">Gateway</td>
            <td width="7%" align="center">Customer IP Address</td>
            <td width="7%" align="center">Customer Account Number</td>
          </tr>
          <tr>
            <td colspan="16" id="shadowDiv"
								style="height: 5px; margin-top: -1px"></td>
          </tr>
		  
		  <?php
		  	
		  // echo $live_stats;
		  echo "\n";
		  ?>
		  
		  
          <?php if($live_stats->num_rows() > 0) {?>
          <?php foreach ($cdr->result() as $row): ?>
          <tr class="main_text">
            <td align="center" height="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <?php 
							if($filter_display_results == 'sec')
							{
								$billsec        = $row->billsec; // by default bill is in sec
								$sellrate       = $row->sell_rate / 60; // sell rate per sec
								$sellrate       = round($sellrate, 4);
								$costrate       = $row->cost_rate / 60; // cost rate per sec
								$costrate       = round($costrate, 4);
							}
							else
							{
								$billsec        = $row->billsec / 60; // convert to min
								$billsec        = round($billsec, 4);
								$sellrate       = $row->sell_rate; // sell rate by default is in min
								$costrate       = $row->cost_rate; // cost rate by default is in min
							}
							?>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <?php if ($row->gateway != ""){ ?>
            <td align="center">&nbsp;</td>
            <?php
							} else {
								?>
            <td align="center">&nbsp;</td>
            <?php
							}
							?>
            <td align="center" <?php if($row->total_failed_gateways > 0) {?>
								class="selector" style="color: red; font-weight: bold;"
							<?php } ?> id="<?php echo $row->id;?>">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <?php if ($row->customer_id != 0) {?>
            <td align="center">&nbsp;</td>
            <?php
							}
									else {?>
            <td align="center">&nbsp;</td>
            <?php }?>
            <?php if($row->parent_reseller_id == '0'){ ?>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
            <td colspan="16" align="center">&nbsp;</td>
            <?php } else { ?>
            <?php } ?>
            <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
            <?php } else { ?>
            <?php } ?>
            <?php } else { ?>
            <!--get admin rates -->
            <?php
							$getRate = $this->groups_model->get_single_rate($row->admin_rate_id , $row->admin_rate_group);
							// $getRateRow = $getRate->row();
							$getRateRow = $row->getRate;

							if($filter_display_results == 'sec')
							{
								$sellrate       = $getRateRow->sell_rate / 60; // sell rate per sec
								$sellrate       = v_round($sellrate);
								$costrate       = $getRateRow->cost_rate / 60; // cost rate per sec
								$costrate       = v_round($costrate);
							}
							else
							{
								$sellrate       = $getRateRow->sell_rate; // sell rate by default is in min
								$costrate       = $getRateRow->cost_rate; // cost rate by default is in min
							}
							?>
            <?php if ($sellrate != 0)
														{?>
            <?php
															} else {?>
            <?php }?>
            
            <!-- <td align="center"><?php echo $getRateRow->sell_initblock; ?></td> -->
            
            <?php if ($costrate != 0)
																{?>
            <?php
																	} else {?>
            <?php }?>
            
            <!-- <td align="center"><?php echo $getRateRow->buy_initblock; ?></td> -->
            <?php if ($row->total_admin_sell_cost != 0)
																		{?>
            <?php
																			} else {?>
            <?php }?>
            <?php if ($row->total_admin_buy_cost != 0)
																				{?>
            <?php
																					} else {?>
            <?php }?>
            <?php } ?>
          </tr>
          <tr style="height: 5px;">
            <td colspan="16" id="shadowDiv"
								style="height: 5px; margin-top: 0px; background-color: #fff"></td>
          </tr>
          <?php endforeach;?>
          <?php } else { echo '<tr><td align="center" style="color:red;" colspan="16">No Results Found</td></tr>'; 
} ?>
        </table></td>
    </tr>
    <tr>
      <td id="paginationWKTOP"><?php echo $this->pagination->create_links();?></td>
    </tr>
    <script type="text/javascript">
																			$(".selector").each(function() {
																				$(this).qtip({
																					content: {
																						text: '<img src="'+base_url+'assets/images/loading.gif" alt="Loading..." />',
																						ajax: {
																							type: "POST",
																							url: base_url+"cdr/tooltip",
																							data: 'id='+ this.id,
																							success: function(html){
																								this.set('content.text', html);
																							}
																						}
																					},
																					style: {
																						classes: 'ui-tooltip-blue ui-tooltip-shadow'
																					},
																					hide: {
																						fixed: true,
																						event: 'unfocus'
																						//event: false,
																						//inactive: 3000
																					},
																					position: {
																						at: 'bottom center', // at the bottom right of...
																						my: 'top center'
																					},
																				});
																			});
																			</script>
  </tbody>
</table>
<script type="text/javascript">
$('.datepicker').datetimepicker({
	showSecond: true,
	showMillisec: false,
	timeFormat: 'hh:mm:ss',
	dateFormat: 'yy-mm-dd'
});

$('.ip').numeric({allow:"."});
$('.numeric').numeric({allow:"."});

$('#reset').live('click', function(){
	$('#filter_table input[type="text"]').val('');
	$('#filter_table select').val('');
	return false;
});

$('#filter_quick').live('change', function(){
	var val = $(this).val();
	$.ajax({
		type: "POST",
		url: base_url+"cdr/get_calculated_date_time",
		data: 'val='+val,
		success: function(html){
			var split = html.split('|');
			$('#filter_date_from').val(split[0]);
			$('#filter_date_to').val(split[1]);
		}
	});
});

$('.exp_pdf').live('click', function(){
	$('#filterForm').attr('action', ''+base_url+'cdr/export_pdf/');
	$('#filterForm').submit();
	return false;
});

$('.exp_exl').live('click', function(){
	$('#filterForm').attr('action', ''+base_url+'cdr/export_excel/');
	$('#filterForm').submit();
	return false;
});

$('.exp_csv').live('click', function(){
	$('#filterForm').attr('action', ''+base_url+'cdr/export_csv/');
	$('#filterForm').submit();
	return false;
});


$('#searchFilter').click(function(){
	$('#filterForm').attr('action', ''+base_url+'cdr/');
});
</script> 

<!--****FILTER CONTENTS CHANGE BEHAVIOR ***********--> 
<script type="text/javascript">
$(function () {
	$("#filter_contents_select").selectbox({
		onChange: function (val, inst) {

			//reset the searach form 
			$('#filter_table input[type="text"]').val('');
			$('#filter_table select').val('');

			//put the selected value in the hidden search form field 
			$('#filter_contents').val(val);

			//click the submit button of search form
			$('#searchFilter').click();
		}
	});
});
</script> 

<!--**************************Multi DropDown Select Box ************************--> 
<script
	src="<?php echo base_url();?>assets/js/jquery.mcdropdown.js"
	type="text/javascript"></script> 
<script
	src="<?php echo base_url();?>assets/js/jquery.bgiframe.js"
	type="text/javascript"></script> 
<script type="text/javascript">
<!--//
// on DOM ready
$(document).ready(function (){
	$("#filter_customers").mcDropdown("#quick_customer_filter_list");

	//this is to make the option selected 
	var dd = $("#filter_customers").mcDropdown();
	dd.setValue(<?php echo $filter_customers;?>);

	//woraround for fixing the input width of mcDropDown
	$('div.mcdropdown input[type="text"]').css("width","114px");
});
//-->
</script> 
<!--************************END*************************--> 
