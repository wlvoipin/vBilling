<div id="subMenu">
        <ul>
            <li><a href="<?php echo base_url();?>billing/" <?php if($sub_selected == 'summary_billing') { echo 'class="sub_selected"' ;}?>>Billing Summary</a></li>
            
            <li><a href="<?php echo base_url();?>billing/invoices/" <?php if($sub_selected == 'list_invoices') { echo 'class="sub_selected"' ;}?>>Invoices</a></li>
        </ul>
        <div class="clr"></div>
    </div>