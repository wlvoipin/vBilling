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
<?php 
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
            <li><a href="<?php echo base_url();?>groups/list_rates/" <?php if($sub_selected == 'list_rates') { echo 'class="sub_selected"' ;}?>>Batch Update Rates</a></li>
            <?php } ?>
            
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
				
				<li><a href="<?php echo base_url();?>groups/localization_groups" <?php if($sub_selected == 'localization_groups') { echo 'class="sub_selected"' ;}?>>Localization Groups</a></li>
				
				<li><a href="<?php echo base_url();?>groups/add_localization_groups" <?php if($sub_selected == 'add_localization_groups') { echo 'class="sub_selected"' ;}?>>Add Localization Groups</a></li>
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