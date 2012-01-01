<div class="menu">
        <ul>
            <li <?php if($selected == "dashboard") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>">Dashboard</a></li>
            
            <li <?php if($selected == "customers") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>customers/">Customers</a></li>
            
            <li <?php if($selected == "carriers") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>carriers/">Carriers</a></li>
            
            <li <?php if($selected == "groups") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>groups/">Rate Groups</a></li>
            
            <li <?php if($selected == "cdr") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>cdr/">CDR</a></li>
            
            <li <?php if($selected == "billing") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>billing/">Billing</a></li>
            
            <li <?php if($selected == "freeswitch") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>freeswitch/">FreeSWITCH</a></li>
            
            <li <?php if($selected == "manage_accounts") { echo 'class="menu-selected"';} ?>><a class="center_hassub" href="<?php echo base_url();?>manage_accounts/" style="width:135px;">Manage Accounts</a></li>
            
        </ul>
    </div>

    <div class="clr"></div>
    <div id="shadowDiv"></div>