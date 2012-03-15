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
<!--POP UP ATTRIBUTES-->
<?php 
$atts_reseller = array(
	'width'      => '1000',
	'height'     => '800',
	'scrollbars' => 'yes',
	'status'     => 'no',
	'resizable'  => 'yes',
	'screenx'    => '0',
	'screeny'    => '0'
	);
?>
<!--END POP UP ATTRIBUTES-->

<ul id="navcss">

	<!-- 
	<li <?php if($selected == "dashboard") { echo 'class="current"';} ?>>
	<a  href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/images/icons/home.png" /> <span>Dashboard</span> </a><
	/li> 
	-->

	<li <?php if($selected == "customers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>reseller/customers/"><img src="<?php echo base_url();?>assets/images/icons/customers.png"/> <span><?php echo $this->lang->line('reseller_main_menu_customer_sub_resellers');?></span> </a></li>        

	<li <?php if($selected == "groups") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>reseller/groups/"><img src="<?php echo base_url();?>assets/images/icons/linkOn.png"/> <span><?php echo $this->lang->line('reseller_main_menu_rate_groups');?></span> </a></li>

	<li <?php if($selected == "cdr") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>reseller/cdr/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span><?php echo $this->lang->line('reseller_main_menu_call_details');?></span> </a></li>

	<li <?php if($selected == "billing") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>reseller/billing/"><img src="<?php echo base_url();?>assets/images/icons/billing.png"/> <span><?php echo $this->lang->line('reseller_main_menu_billing_invoicing');?></span> </a></li>

	<li <?php if($selected == "settings") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>reseller/settings/"><img src="<?php echo base_url();?>assets/images/icons/settings.png"/> <span><?php echo $this->lang->line('reseller_main_menu_settings');?></span> </a></li>

	<li><?php echo anchor_popup('reseller/info/', $this->lang->line('reseller_main_menu_my_info'), $atts_reseller); ?></li>

</ul>
<div class="clr"></div>
<div id="shadowDiv"></div>
