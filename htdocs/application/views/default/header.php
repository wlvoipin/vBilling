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
<!--<style type="text/css" >
div.paging {
/*	font-family: georgia, Serif; */
	font-family: Lucida Grande, Arial, Sans-serif;
	font-size: 12px;
	line-height: 1.5em;
	color: #111111;
}
</style>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script src="<?php echo base_url();?>assets/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/blockUI.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/timepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.alphanumeric.pack.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/vBilling.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/overlib.js" type="text/javascript"></script>
		
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/css/jquery_ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript">
        //<![CDATA[
        base_url = '<?php echo base_url();?>';
        //]]>
        </script>
    
        <title>:: <?php echo $page_title;?> ::</title>
        
        <!--[if gt IE 5]>
        <link href="<?php echo base_url();?>assets/css/ie_fix.css" rel="stylesheet" type="text/css">
        <![endif]-->
        
        <!--********************JQUERY STYLE DROP DOWN *****************-->
        <link href="<?php echo base_url();?>assets/css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.selectbox-0.1.3.js"></script>
</head>

<body>
<div class="container">
  <div id="HeaderDiv">
    <div id="logoDiv">
        
        <!--******if logged in user is admin or sub-admin********-->
        <?php if($this->session->userdata('user_type') == 'admin' || $this->session->userdata('user_type') == 'sub_admin'){?>
            <?php if(settings_any_cell('logo', '0') != ''){ ?>
                <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" height="33" />
            <?php } else { ?>
                <img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167">
            <?php } ?>
        
        <!--******if logged in user is customer********-->
        <?php } else if($this->session->userdata('user_type') == 'customer'){ ?>
                <!--****** if customer parent is admin ********-->
                <?php if(customer_any_cell($this->session->userdata('customer_id'), 'parent_id') == '0'){?>
                    <?php if(settings_any_cell('logo', '0') != ''){ ?>
                        <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" height="33" />
                    <?php } else { ?>
                        <img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167">
                    <?php } ?>
                
                <!--****** customer parent not admin ********-->
                <?php } else { ?>
                    <!--****** check logo for customer parent ********-->
                    <?php if(settings_any_cell('logo', customer_any_cell($this->session->userdata('customer_id'), 'parent_id')) != ''){ ?>
                        <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', customer_any_cell($this->session->userdata('customer_id'), 'parent_id'));?>" height="33" />
                    <?php } else { ?>
                        <!--****** check logo for admin ********-->
                        <?php if(settings_any_cell('logo', '0') != ''){ ?>
                            <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" height="33" />
                        <!--****** apply default ********-->
                        <?php } else { ?>
                            <img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167">
                        <?php } ?>
                    <?php } ?>
                <?php }?>
        
        <!--******if logged in user is reseller********-->
        <?php } else if($this->session->userdata('user_type') == 'reseller'){?>
                    <!--****** Check for reseller logo  ********-->
                    <?php if(settings_any_cell('logo', $this->session->userdata('customer_id')) != ''){ ?>
                        <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', $this->session->userdata('customer_id'));?>" height="33" />
                    <?php } else { ?>
                    <!--****** Check reseller level   ********-->
                        <?php if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3'){?>
                            <!--****** check logo for admin ********-->
                            <?php if(settings_any_cell('logo', '0') != ''){ ?>
                                <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" height="33" />
                            <!--****** apply default ********-->
                            <?php } else { ?>
                                <img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167">
                            <?php } ?>
                        <?php } else { ?>
                            <!--****** check logo for reseller parent ********-->
                            <?php if(settings_any_cell('logo', customer_any_cell($this->session->userdata('customer_id'), 'parent_id')) != ''){ ?>
                                <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', customer_any_cell($this->session->userdata('customer_id'), 'parent_id'));?>" height="33" />
                            <?php } else { ?>
                                <!--****** check logo for admin ********-->
                                <?php if(settings_any_cell('logo', '0') != ''){ ?>
                                    <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" height="33" />
                                <!--****** apply default ********-->
                                <?php } else { ?>
                                    <img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167">
                                <?php } ?>
                            <?php } ?>
                        
                        <?php } ?>
                    <?php } ?>
            <?php } ?>
               
    </div>
    
    <?php if(!isset($dont_show_this)){?>
    <div id="top-links">
        <?php 
        if($this->session->userdata('user_type') == 'admin' || $this->session->userdata('user_type') == 'sub_admin'){
            echo '<a href="#">Welcome Admin</a>&nbsp;|&nbsp;';
            echo '<a href="'.base_url().'customers/my_account">My Account</a>&nbsp;|&nbsp;';
        }
        else if($this->session->userdata('user_type') == 'customer'){
            echo '<a href="#">Welcome Customer</a>&nbsp;|&nbsp;';
            echo '<a href="'.base_url().'customer/my_account">My Account</a>&nbsp;|&nbsp;';
        }
        else if($this->session->userdata('user_type') == 'reseller'){
            echo '<a href="#">Welcome Reseller</a>&nbsp;|&nbsp;';
            echo '<a href="'.base_url().'reseller/customers/my_account">My Account</a>&nbsp;|&nbsp;';
        }
        ?>
        <a href="<?php echo base_url();?>home/logout" class="active">Sign out</a>
    </div>
    <?php } ?>
    
  <div class="clr"></div>
 </div>