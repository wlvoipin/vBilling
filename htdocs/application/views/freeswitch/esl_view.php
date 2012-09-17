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
<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
	<tbody><tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th height="20" colspan="3" align="left" class="tbl_main_head">                        
							<div class="left">Select refresh time from the right</div> 

							<div class="right main_head_btns">
								<?php 
							$ref_time = '';
							if(isset($_POST['ref_time']))
							{
								$ref_time = $_POST['ref_time'];
							}

							if($ref_time == '')
							{
								$ref_time = 5000;
							}
							?>
							Refresh Time
							<form action="<?php echo base_url();?>freeswitch/freeswitch_esl" method="POST">
								<select style="float:right" id="ref_time" name="ref_time" onchange="this.form.submit();">
									<option value="5000"  <?php if($ref_time == 5000) { echo "selected"; }?>>5 Seconds</option>
									<option value="10000" <?php if($ref_time == 10000){ echo "selected"; }?>>10 Seconds</option>
									<option value="15000" <?php if($ref_time == 15000){ echo "selected"; }?>>15 Seconds</option>
									<option value="20000" <?php if($ref_time == 20000){ echo "selected"; }?>>20 Seconds</option>
									<option value="25000" <?php if($ref_time == 25000){ echo "selected"; }?>>25 Seconds</option>
									<option value="30000" <?php if($ref_time == 30000){ echo "selected"; }?>>30 Seconds</option>
									<option value="35000" <?php if($ref_time == 35000){ echo "selected"; }?>>35 Seconds</option>
									<option value="40000" <?php if($ref_time == 40000){ echo "selected"; }?>>40 Seconds</option>
									<option value="45000" <?php if($ref_time == 45000){ echo "selected"; }?>>45 Seconds</option>
									<option value="50000" <?php if($ref_time == 50000){ echo "selected"; }?>>50 Seconds</option>
									<option value="55000" <?php if($ref_time == 55000){ echo "selected"; }?>>55 Seconds</option>
									<option value="60000" <?php if($ref_time == 60000){ echo "selected"; }?>>60 Seconds</option>
								</select>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:250px;color:green;font-weight:bold;">Total Connected Calls</td><td class="connected_calls" style="width:250px;color:green;font-weight:bold;"><?php echo get_connected_calls();?></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</tbody></table>
<br/><br/>

<!--*****************************SETTINGS DETAILS *************************************-->
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
	<tbody><tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th height="20" colspan="3" align="left" class="tbl_main_head">                        
							<div class="left">FreeSWITCH Status</div> 
						</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td style="width:250px;color:green;font-weight:bold;" id="status_command_area"><?php echo get_status();?></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</tbody></table>

<br/><br/>

<!--*****************************SETTINGS DETAILS *************************************-->
<!-- <table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
	<tbody><tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th height="20" colspan="3" align="left" class="tbl_main_head">
							<div class="left">COMMAND:</div>
							<div class="left" style="margin-left:10px; margin-top:-5px">
								<form action="" method="post">
									<input type="text" name="manual_command" id="manual_command" class="textfield"/>
									<a href="#" class="run_command" style="font-size:12px;">RUN</a>
								</form>
							</div>
						</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td><div id="manual_command_area" style="color:green;font-weight:bold;height:200px; overflow:scroll;">&nbsp;</div></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</tbody></table> -->

<script type="text/javascript" >
$('.run_command').click(function(){
	var command = $('#manual_command').val();

	$.get(base_url+"freeswitch/get_manual_command_data", { command: ''+command+''},
	function(html){
		if(html != '')
		{
			$('#manual_command_area').html(html);
		}
		else
		{
			$('#manual_command_area').html('Error processing request. Try Again');
		}
	});

	return false;
});

$(document).keypress(function(e) {
	if(e.keyCode == 13) {
		var command = $('#manual_command').val();

		$.get(base_url+"freeswitch/get_manual_command_data", { command: ''+command+''},
		function(html){
			if(html != '')
			{
				$('#manual_command_area').html(html);
			}
			else
			{
				$('#manual_command_area').html('Error processing request. Try Again');
			}
		});
		return false;
	}
});

var ref_time = $('#ref_time').val();
var refreshId = setInterval(function(){
	$('.connected_calls').load(base_url+'freeswitch/get_connected_calls_ajax');
	}, ref_time);

	var refreshStatus = setInterval(function(){
		$('#status_command_area').load(base_url+'freeswitch/get_server_status', function(response) {
			//alert(response);
		});

		}, ref_time);
</script>