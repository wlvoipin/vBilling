<div id="subMenu">
	<ul>
		<li><a href="<?php echo base_url();?>freeswitch/" <?php if($sub_selected == 'list_profiles') { echo 'class="sub_selected"' ;}?>>List Profiles</a></li>
		
        <?php if($this->session->userdata('user_type') == 'admin'){?>
            <li><a href="<?php echo base_url();?>freeswitch/new_profile" <?php if($sub_selected == 'new_profile') { echo 'class="sub_selected"' ;}?>>New Profile</a></li>
        <?php 
        } else if($this->session->userdata('user_type') == 'sub_admin'){
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'new_profiles') == 1)
            {
        ?>
                <li><a href="<?php echo base_url();?>freeswitch/new_profile" <?php if($sub_selected == 'new_profile') { echo 'class="sub_selected"' ;}?>>New Profile</a></li>
        <?php 
            }
        }
        ?>
        
		
        <?php if($this->session->userdata('user_type') == 'admin'){?>
            <li><a href="<?php echo base_url();?>freeswitch/freeswitch_esl" <?php if($sub_selected == 'freeswitch_esl') { echo 'class="sub_selected"' ;}?>>FreeSWITCH Status</a></li>
        <?php 
        } else if($this->session->userdata('user_type') == 'sub_admin'){
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'freeswitch_status') == 1)
            {
        ?>
                <li><a href="<?php echo base_url();?>freeswitch/freeswitch_esl" <?php if($sub_selected == 'freeswitch_esl') { echo 'class="sub_selected"' ;}?>>FreeSWITCH Status</a></li>
        <?php 
            }
        }
        ?>
	</ul>
	<div class="clr"></div>
</div>