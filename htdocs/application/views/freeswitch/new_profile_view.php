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
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
		<td width="21" height="35"></td>
		<td width="825" class="heading">
			New Sofia Profile            </td>
			<td width="178">
				<table cellspacing="0" cellpadding="0" width="170" height="42" class="search_col">
					<tbody><tr>
						<td align="center" width="53" valign="bottom">&nbsp;</td>
					</tr>

					<tr>
						<td align="center" width="53" valign="top">&nbsp;</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
		<tr>
			<td background="<?php echo base_url();?>assets/images/line.png" height="7" colspan="3"></td>
		</tr>
		<tr>
			<td height="10"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3"><div class="error" id="err_div" style="display:none;"></div></td>
		</tr>
		<tr>
			<td colspan="3"><div class="success" id="success_div" style="display:none;"></div></td>
		</tr>
		<tr>
			<td align="center" height="20" colspan="3">
				<form enctype="multipart/form-data"  method="post" action="" name="addProfile" id="addProfile">
					<table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
						<tbody>
							<tr>
								<td align="right" width="45%"><span class="required">*</span> Profile Name:</td>
								<td align="left" width="55%"><input type="text" value="vBilling" name="name" id="name" maxlength="50" class="textfield"></td>
							</tr>
							<tr>
								<td align="right"><span class="required">*</span> SIP IP Address:</td>
								<td align="left"><input type="text" value="" name="sipip" id="sipip" maxlength="50" class="textfield"></td>
							</tr>
							<tr>
								<td align="right"><span class="required">*</span> SIP Port:</td>
								<td align="left"><input type="text" value="5060" name="sipport" id="sipport" maxlength="50" class="textfield"></td>
							</tr>
							<tr>
								<td align="right" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td align="center" colspan="2"><input border="0" id="submitaddProfileForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
							</tr>
						</tbody></table>
					</form>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td height="5"></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td height="20" colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</tbody></table>
		
<script type="text/javascript">
		$('#addProfile').submit(function(){
			//show wait msg 
			$.blockUI({ css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} 
		});

		var name = $('#name').val();
		var sipip = $('#sipip').val();
		var sipport = $('#sipport').val();
		var required_error = 0;

		//common required fields check
		if(name == '' || sipip == '' || sipport == '')
		{
			required_error = 1;
		}

		var text = "";

		if(required_error == 1)
		{
			text += "Fields With * Are Required Fields<br/>";
		}

		if(text != '')
		{
			$('.success').hide();
			$('.error').html(text);
			$('.error').fadeOut();
			$('.error').fadeIn();
			document.getElementById('err_div').scrollIntoView();
			$.unblockUI();
			return false;
		}
		else
		{
			var form = $('#addProfile').serialize();
			$.ajax({
				type: "POST",
				url: base_url+"freeswitch/insert_new_profile",
				data: form,
				success: function(html){
					if(html == '')
					{
						$('.error').hide();
						$('.success').html("Profile added successfully.");
						$('.success').fadeOut();
						$('.success').fadeIn();
						document.getElementById('success_div').scrollIntoView();
						$.unblockUI();
					}
					else
					{
						$('.success').hide();
						$('.error').html(""+html+"");
						$('.error').fadeOut();
						$('.error').fadeIn();
						document.getElementById('err_div').scrollIntoView();
						$.unblockUI();
					}
				}
			});
			return false;
		}
		return false;
	});
</script>