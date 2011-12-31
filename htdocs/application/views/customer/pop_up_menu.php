<tr>
            <td align="left" valign="middle" colspan="3" >
            <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0">
                <tr>
                    <td align="left" valign="middle" >
                    <table width="100%" border="0" cellspacing="0" align="left" cellpadding="0">
                        <tr>
                            <td align="left" valign="middle">
                                <ul class="body-tab">
                                    <li <?php if($selected == 'customer_dashboard'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/"><b>Dashboard</b></a></li>
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/edit_customer"><b>My Information</b></a></li>
                                    <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_cdr"><b>CDR List</b></a></li>
                                    <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_rates"><b>Rates</b></a></li>
                                    <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/invoices"><b>Billing</b></a></li>
                                    <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/customer_acl_nodes"><b>ACL Nodes</b></a></li>
                                    <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/sip_access"><b>SIP Credentials</b></a></li>
                                     <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customer/manage_balance"><b>Balance History</b></a></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    </td>

                </tr>
            </table>
            </td>
        </tr>