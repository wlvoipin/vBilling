<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>manage_accounts/index/admin" <?php if($sub_selected == 'admin_accounts') { echo 'class="sub_selected"' ;}?>>Admin Accounts</a></li>
            <li><a href="<?php echo base_url();?>manage_accounts/index/customer" <?php if($sub_selected == 'customers_accounts') { echo 'class="sub_selected"' ;}?>>Customer Accounts</a></li>
        </ul>
        <div class="clr"></div>
    </div>