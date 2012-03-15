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
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/edit_customer/<?php echo $customer_id;?>"><span>Customer Information</span></a></li>
                                    
                                    <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/customer_cdr/<?php echo $customer_id;?>"><span>Customer CDR</span></a></li>
                                    
                                    <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/customer_rates/<?php echo $customer_id;?>"><span>Rates</span></a></li>
                                    
                                    <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/invoices/<?php echo $customer_id;?>"><span>Billing</span></a></li>
                                    
                                     <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/customer_acl_nodes/<?php echo $customer_id;?>"><span>ACL Nodes</span></a></li>
                                    
                                    <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/sip_access/<?php echo $customer_id;?>"><span>SIP Credentials</span></a></li>
                                    
                                     <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>reseller/customers/manage_balance/<?php echo $customer_id;?>"><span>Manage Balance</span></a></li>
                                     
                                </ul>
                            </td>
                        </tr>
                    </table>
                    </td>

                </tr>
            </table>
            </td>
        </tr>
        
        <?php if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3'){?>
        <tr>
            <td colspan="3">
                <ul id="breadcrumb">
                    <li>NAME ::</li>
                    <?php
                        echo '<li><a href="#">'.customer_full_name($customer_id).'</a></li>';
                    ?>
                    <li>TYPE ::</li>
                    <?php
                        $type = customer_any_cell($customer_id, 'reseller_level');
                        if($type == 0)
                        {
                            echo '<li><a href="#">Customer</a></li>';
                        }
                        else
                        {
                            echo '<li><a href="#">Reseller</a></li>';
                        }
                    ?>
                    <li>PARENT ::</li>
                    <?php
                        $parent = customer_any_cell($customer_id, 'parent_id');
                        if($parent == $this->session->userdata('customer_id'))
                        {
                            echo '<li><a href="#">You !</a></li>';
                        }
                        else
                        {
                            echo '<li><a href="'.base_url().'reseller/customers/edit_customer/'.$parent.'">'.customer_full_name($parent).'</a></li>';
                        }
                    ?>
                </ul>
            </td>
        </tr>
        <?php } ?>