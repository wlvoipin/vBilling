<?Php 
    $current_group_id = '';
    if(isset($rate_group_id))
    {
        $current_group_id = $rate_group_id;
    }
?>
<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>groups/" <?php if($sub_selected == 'list_rate_groups') { echo 'class="sub_selected"' ;}?>>List Rate Groups</a></li>
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>groups/new_rate_group" <?php if($sub_selected == 'new_rate_group') { echo 'class="sub_selected"' ;}?>>New Rate Group</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_rate_groups') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>groups/new_rate_group" <?php if($sub_selected == 'new_rate_group') { echo 'class="sub_selected"' ;}?>>New Rate Group</a></li>
            <?php 
                }
            }
            ?>
            
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>groups/new_rate/<?php echo $current_group_id;?>" <?php if($sub_selected == 'new_rate') { echo 'class="sub_selected"' ;}?>>New Rate</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_rate') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>groups/new_rate/<?php echo $current_group_id;?>" <?php if($sub_selected == 'new_rate') { echo 'class="sub_selected"' ;}?>>New Rate</a></li>
            <?php 
                }
            }
            ?>
            
            
            <?php if($this->session->userdata('user_type') == 'admin'){?>
                <li><a href="<?php echo base_url();?>groups/import_by_csv" <?php if($sub_selected == 'import_by_csv') { echo 'class="sub_selected"' ;}?>>Import By CSV</a></li>
            <?php 
            } else if($this->session->userdata('user_type') == 'sub_admin'){
                if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'import_csv') == 1)
                {
            ?>
                    <li><a href="<?php echo base_url();?>groups/import_by_csv" <?php if($sub_selected == 'import_by_csv') { echo 'class="sub_selected"' ;}?>>Import By CSV</a></li>
            <?php 
                }
            }
            ?>
            
        </ul>
        <div class="clr"></div>
    </div>