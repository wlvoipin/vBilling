<ul id="navcss">
	
    <li <?php if($selected == "dashboard") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/images/icons/home.png" /> <span>Dashboard</span> </a></li>
            
            
    <?php if($this->session->userdata('user_type') == 'admin'){?>
        <li <?php if($selected == "customers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>customers/"><img src="<?php echo base_url();?>assets/images/icons/customers.png"/> <span>Customers</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers') == 1)
        {
    ?>
            <li <?php if($selected == "customers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>customers/"><img src="<?php echo base_url();?>assets/images/icons/customers.png"/> <span>Customers</span> </a></li>
    <?php 
        }
    }
    ?>
    
    
    
    <?php if($this->session->userdata('user_type') == 'admin'){?>
        <li <?php if($selected == "carriers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>carriers/"><img src="<?php echo base_url();?>assets/images/icons/rss.png" style="width:25px;margin-top:-6px"/> <span>Carriers</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_carriers') == 1)
        {
    ?>
            <li <?php if($selected == "carriers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>carriers/"><img src="<?php echo base_url();?>assets/images/icons/rss.png" style="width:25px;margin-top:-6px"/> <span>Carriers</span> </a></li>
    <?php 
        }
    }
    ?>
    
    
    
    
    
    
    <?php if($this->session->userdata('user_type') == 'admin'){?>
        <li <?php if($selected == "groups") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>groups/"><img src="<?php echo base_url();?>assets/images/icons/linkOn.png"/> <span>Rate Groups</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_rate_groups') == 1)
        {
    ?>
            <li <?php if($selected == "groups") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>groups/"><img src="<?php echo base_url();?>assets/images/icons/linkOn.png"/> <span>Rate Groups</span> </a></li>
    <?php 
        }
    }
    ?>
    
    
    
    
    <?php if($this->session->userdata('user_type') == 'admin'){?>
       <li <?php if($selected == "cdr") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>cdr/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span>CDR</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_cdr') == 1)
        {
    ?>
            <li <?php if($selected == "cdr") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>cdr/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span>CDR</span> </a></li>
    <?php 
        }
    }
    ?>
    
    
    
    <?php if($this->session->userdata('user_type') == 'admin'){?>
       <li <?php if($selected == "billing") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>billing/"><img src="<?php echo base_url();?>assets/images/icons/billing.png"/> <span>Billing</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_biling') == 1)
        {
    ?>
            <li <?php if($selected == "billing") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>billing/"><img src="<?php echo base_url();?>assets/images/icons/billing.png"/> <span>Billing</span> </a></li>
    <?php 
        }
    }
    ?>
    
    
    
    <?php if($this->session->userdata('user_type') == 'admin'){?>
       <li <?php if($selected == "freeswitch") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>freeswitch/"><img src="<?php echo base_url();?>assets/images/icons/freeswitch.png"/> <span>FreeSWITCH</span> </a></li>
    <?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_profiles') == 1)
        {
    ?>
            <li <?php if($selected == "freeswitch") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>freeswitch/"><img src="<?php echo base_url();?>assets/images/icons/freeswitch.png"/> <span>FreeSWITCH</span> </a></li>
    <?php 
        }
    }
    ?>
    
    <?php if($this->session->userdata('user_type') == 'admin') {?>
        <li <?php if($selected == "manage_accounts") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>manage_accounts/"><img src="<?php echo base_url();?>assets/images/icons/lock.png"/> <span>Manage Accounts</span> </a></li>
    <?php } ?>
    
    <?php if($this->session->userdata('user_type') == 'admin') {?>
        <li <?php if($selected == "settings") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>settings/"><img src="<?php echo base_url();?>assets/images/icons/settings.png"/> <span>Settings</span> </a></li>
    <?php } ?>
</ul>
<div class="clr"></div>
    <div id="shadowDiv"></div>