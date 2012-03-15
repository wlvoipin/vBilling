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
<tr>
            <td align="left" valign="middle" colspan="3" >
            <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0">
                <tr>
                    <td align="left" valign="middle" >
                    <table width="100%" border="0" cellspacing="0" align="left" cellpadding="0">
                        <tr>
                            <td align="left" valign="middle">
                                <ul id="navcss">
<!--
                                    <li <?php if($selected == 'customer_dashboard'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/"><span>Dashboard</span></a></li>
-->
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/edit_customer"><span><?php echo $this->lang->line('customer_popup_menu_my_information');?></span></a></li>

                                    <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_cdr"><span><?php echo $this->lang->line('customer_popup_menu_call_details');?></span></a></li>

                                    <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_rates"><span><?php echo $this->lang->line('customer_popup_menu_call_rates');?></span></a></li>

                                    <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/invoices"><span><?php echo $this->lang->line('customer_popup_menu_billing_invoicing');?></span></a></li>

                                    <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/manage_balance"><span><?php echo $this->lang->line('customer_popup_menu_balance_history');?></span></a></li>

                                    <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_acl_nodes"><span><?php echo $this->lang->line('customer_popup_menu_acl_nodes');?></span></a></li>

                                    <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/sip_access"><span><?php echo $this->lang->line('customer_popup_menu_sip_credentials');?></span></a></li>


                                </ul>
                            </td>
                        </tr>
                    </table>
                    </td>

                </tr>
            </table>
            </td>
        </tr>