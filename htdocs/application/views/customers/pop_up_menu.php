<tr>
            <td align="left" valign="middle" colspan="3" >
            <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0">
                <tr>
                    <td align="left" valign="middle" >
                    <table width="100%" border="0" cellspacing="0" align="left" cellpadding="0">
                        <tr>
                            <td align="left" valign="middle">
                                <ul class="body-tab">
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/edit_customer/<?php echo $customer_id;?>"><b>Customer Information</b></a></li>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_cdr/<?php echo $customer_id;?>"><b>Customer CDR</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_cdr') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_cdr/<?php echo $customer_id;?>"><b>Customer CDR</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_rates/<?php echo $customer_id;?>"><b>Rates</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_rates') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_rates/<?php echo $customer_id;?>"><b>Rates</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/invoices/<?php echo $customer_id;?>"><b>Billing</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_billing') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/invoices/<?php echo $customer_id;?>"><b>Billing</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_acl_nodes/<?php echo $customer_id;?>"><b>ACL Nodes</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_acl') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_acl_nodes/<?php echo $customer_id;?>"><b>ACL Nodes</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/sip_access/<?php echo $customer_id;?>"><b>SIP Credentials</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_sip') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/sip_access/<?php echo $customer_id;?>"><b>SIP Credentials</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                     
                                     
                                      <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/manage_balance/<?php echo $customer_id;?>"><b>Manage Balance</b></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_balance') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/manage_balance/<?php echo $customer_id;?>"><b>Manage Balance</b></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                     
                                </ul>
                            </td>
                        </tr>
                    </table>
                    </td>

                </tr>
            </table>
            </td>
        </tr>