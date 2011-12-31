<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>customers/" <?php if($sub_selected == 'list_customer') { echo 'class="sub_selected"' ;}?>>List Customer</a></li>
            <li><a href="<?php echo base_url();?>customers/new_customer" <?php if($sub_selected == 'new_customer') { echo 'class="sub_selected"' ;}?>>New Customer</a></li>
        </ul>
        <div class="clr"></div>
    </div>