<tr>
            <td align="left" valign="middle" colspan="3" >
            <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0">
                <tr>
                    <td align="left" valign="middle" >
                    <table width="100%" border="0" cellspacing="0" align="left" cellpadding="0">
                        <tr>
                            <td align="left" valign="middle">
                                <ul id="navcss">
                                    <li <?php if($selected == 'customers_info'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/edit_customer/<?php echo $customer_id;?>"><span>Customer Information</span></a></li>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_cdr/<?php echo $customer_id;?>"><span>Customer CDR</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_cdr') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_cdr'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_cdr/<?php echo $customer_id;?>"><span>Customer CDR</span></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_rates/<?php echo $customer_id;?>"><span>Rates</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_rates') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_rate'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_rates/<?php echo $customer_id;?>"><span>Rates</span></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/invoices/<?php echo $customer_id;?>"><span>Billing</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_billing') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'billing'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/invoices/<?php echo $customer_id;?>"><span>Billing</span></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_acl_nodes/<?php echo $customer_id;?>"><span>ACL Nodes</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_acl') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'customers_ip'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/customer_acl_nodes/<?php echo $customer_id;?>"><span>ACL Nodes</span></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                    
                                    
                                    <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/sip_access/<?php echo $customer_id;?>"><span>SIP Credentials</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_sip') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'sip_access'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/sip_access/<?php echo $customer_id;?>"><span>SIP Credentials</span></a></li>
                                    <?php 
                                        }
                                    }
                                    ?>
                                    
                                     
                                     
                                      <?php if($this->session->userdata('user_type') == 'admin'){?>
                                        <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/manage_balance/<?php echo $customer_id;?>"><span>Manage Balance</span></a></li>
                                    <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers_balance') == 1)
                                        {
                                    ?>
                                            <li <?php if($selected == 'manage_balance'){ echo 'class="current"';}?>><a href="<?php echo base_url();?>customers/manage_balance/<?php echo $customer_id;?>"><span>Manage Balance</span></a></li>
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