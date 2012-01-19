<tr>
            <td align="left" valign="middle" colspan="3" >
            <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0">
                <tr>
                    <td align="left" valign="middle" >
                    <table width="100%" border="0" cellspacing="0" align="left" cellpadding="0">
                        <tr>
                            <td align="left" valign="middle">
                                <ul id="navcss">
                                    <li <?php if($selected == 'customer_dashboard'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/"><span>Dashboard</span></a></li>
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/edit_customer"><span>My Information</span></a></li>
                                    <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_cdr"><span>CDR List</span></a></li>
                                    <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_rates"><span>Rates</span></a></li>
                                    <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/invoices"><span>Billing</span></a></li>
                                    <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_acl_nodes"><span>ACL Nodes</span></a></li>
                                    <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/sip_access"><span>SIP Credentials</span></a></li>
                                     <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/manage_balance"><span>Balance History</span></a></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    </td>

                </tr>
            </table>
            </td>
        </tr>