<div id="subMenu">
	<ul>
		<li><a href="<?php echo base_url();?>freeswitch/" <?php if($sub_selected == 'list_profiles') { echo 'class="sub_selected"' ;}?>>List Profiles</a></li>
		<li><a href="<?php echo base_url();?>freeswitch/new_profile" <?php if($sub_selected == 'new_profile') { echo 'class="sub_selected"' ;}?>>New Profile</a></li>
		<li><a href="<?php echo base_url();?>freeswitch/freeswitch_esl" <?php if($sub_selected == 'freeswitch_esl') { echo 'class="sub_selected"' ;}?>>FreeSWITCH Status</a></li>
	</ul>
	<div class="clr"></div>
</div>