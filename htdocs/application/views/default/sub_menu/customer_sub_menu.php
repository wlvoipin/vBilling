<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>customers/" <?php if($sub_selected == 'list_customer') { echo 'class="sub_selected"' ;}?>>List Customer</a></li>
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>customers/new_customer" <?php if($sub_selected == 'new_customer') { echo 'class="sub_selected"' ;}?>>New Customer</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_customers') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>customers/new_customer" <?php if($sub_selected == 'new_customer') { echo 'class="sub_selected"' ;}?>>New Customer</a></li>
            <?php 
                }
            }
            ?>
        </ul>
        <div class="clr"></div>
    </div>