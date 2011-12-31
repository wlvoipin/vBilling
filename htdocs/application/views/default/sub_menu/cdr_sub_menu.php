<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>cdr/" <?php if($sub_selected == 'list_cdr') { echo 'class="sub_selected"' ;}?>>List CDR</a></li>
            <li><a href="<?php echo base_url();?>cdr/gateways_stats" <?php if($sub_selected == 'gateways_stats') { echo 'class="sub_selected"' ;}?>>Gateways Statistics</a></li>
            <li><a href="<?php echo base_url();?>cdr/customer_stats" <?php if($sub_selected == 'customer_stats') { echo 'class="sub_selected"' ;}?>>Customer Stats</a></li>
            <li><a href="<?php echo base_url();?>cdr/call_destination" <?php if($sub_selected == 'call_destination') { echo 'class="sub_selected"' ;}?>>Call Destination</a></li>
        </ul>
        <div class="clr"></div>
    </div>