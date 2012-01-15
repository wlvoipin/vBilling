<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>cdr/" <?php if($sub_selected == 'list_cdr') { echo 'class="sub_selected"' ;}?>>List CDR</a></li>
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>cdr/gateways_stats" <?php if($sub_selected == 'gateways_stats') { echo 'class="sub_selected"' ;}?>>Gateways Statistics</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_gateway_stats') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>cdr/gateways_stats" <?php if($sub_selected == 'gateways_stats') { echo 'class="sub_selected"' ;}?>>Gateways Statistics</a></li>
            <?php 
                }
            }
            ?>
            
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>cdr/customer_stats" <?php if($sub_selected == 'customer_stats') { echo 'class="sub_selected"' ;}?>>Customer Stats</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customer_stats') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>cdr/customer_stats" <?php if($sub_selected == 'customer_stats') { echo 'class="sub_selected"' ;}?>>Customer Stats</a></li>
            <?php 
                }
            }
            ?>
            
            
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>cdr/call_destination" <?php if($sub_selected == 'call_destination') { echo 'class="sub_selected"' ;}?>>Call Destination</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_call_destination') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>cdr/call_destination" <?php if($sub_selected == 'call_destination') { echo 'class="sub_selected"' ;}?>>Call Destination</a></li>
            <?php 
                }
            }
            ?>
        </ul>
        <div class="clr"></div>
    </div>