<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>billing/" <?php if($sub_selected == 'summary_billing') { echo 'class="sub_selected"' ;}?>>Billing Summary</a></li>
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>billing/invoices/" <?php if($sub_selected == 'list_invoices') { echo 'class="sub_selected"' ;}?>>Invoices</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_invoices') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>billing/invoices/" <?php if($sub_selected == 'list_invoices') { echo 'class="sub_selected"' ;}?>>Invoices</a></li>
            <?php 
                }
            }
            ?>
        </ul>
        <div class="clr"></div>
    </div>