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
<script type="text/javascript">
if(!window.opener){
	window.location = '../../home/';
}
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
		<td width="21" height="35"></td>
		<td width="825" class="heading">
			My Balance            </td>
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

		<?php require_once("pop_up_menu.php");?>

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
				<table cellspacing="0" cellpadding="0" border="0" width="95%" class="search_col">

					<thead>



						<tr class="bottom_link">
							<td align="center">&nbsp;</td>
						</tr>
						<tr><td id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
					</thead>

					<tbody id="dynamic">                                
						<tr class="main_text">
							<td align="center">Your Current Balance is: $ <?php
						if($my_balance->num_rows() > 0)
						{
							foreach ($my_balance->result() as $row)
								echo $row->customer_balance;
						}
						else
						{
							echo "Not Found";
						}
						?>
					</td>
				</tr>
				<tr style="height:5px;"><td id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>

			</tbody>
		</table>
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
