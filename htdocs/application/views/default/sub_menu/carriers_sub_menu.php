<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>carriers/" <?php if($sub_selected == 'list_carriers') { echo 'class="sub_selected"' ;}?>>List Carriers</a></li>
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>carriers/new_carrier" <?php if($sub_selected == 'new_carrier') { echo 'class="sub_selected"' ;}?>>New Carrier</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_carriers') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>carriers/new_carrier" <?php if($sub_selected == 'new_carrier') { echo 'class="sub_selected"' ;}?>>New Carrier</a></li>
            <?php 
                }
            }
            ?>
            
        </ul>
        <div class="clr"></div>
    </div>