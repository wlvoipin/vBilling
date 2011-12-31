<?Php 
    $current_group_id = '';
    if(isset($group_id))
    {
        $current_group_id = $group_id;
    }
?>
<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>groups/" <?php if($sub_selected == 'list_groups') { echo 'class="sub_selected"' ;}?>>List Groups</a></li>
            <li><a href="<?php echo base_url();?>groups/new_group" <?php if($sub_selected == 'new_group') { echo 'class="sub_selected"' ;}?>>New Group</a></li>
            <li><a href="<?php echo base_url();?>groups/new_rate/<?php echo $current_group_id;?>" <?php if($sub_selected == 'new_rate') { echo 'class="sub_selected"' ;}?>>New Rate</a></li>
            <li><a href="<?php echo base_url();?>groups/import_by_csv" <?php if($sub_selected == 'import_by_csv') { echo 'class="sub_selected"' ;}?>>Import By CSV</a></li>
        </ul>
        <div class="clr"></div>
    </div>