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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="cufon-active cufon-ready" xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

        <script src="<?php echo base_url();?>assets/js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/blockUI.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/timepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.alphanumeric.pack.js" type="text/javascript"></script>
        
        <link href="<?php echo base_url();?>assets/css/login.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/css/jquery_ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript">
        //<![CDATA[
        base_url = '<?php echo base_url();?>';
        //]]>
        </script>

        <title><?php echo $this->lang->line('main_screen_title_bar');?></title>

</head>

<body class="login">
<div class="login-box">
<div class="login-border">
<div class="login-style">
	<div class="login-header">
		<div class="logo clear">
			
            <?php if(settings_any_cell('logo', '0') != ''){ ?>
                <img src="<?php echo base_url();?>media/images/<?php echo settings_any_cell('logo', '0');?>" class="picture" />
            <?php } else { ?>
                <img src="<?php echo base_url();?>assets/images/logo.png" alt="" class="picture">
            <?php } ?>

		</div>
	</div>
    <div class="error" id="err_div" <?php if($this->session->flashdata('error_message') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('error_message');?> </div>
			<div class="success" id="success_div" style="display:none;"></div>
	<form name="loginForm" id="loginForm" action="<?php echo base_url();?>home/do_login" method="post" enctype="multipart/form-data">
		
		<div class="login-inside">
			<div class="login-data">
				<div class="row clear">
					<label for="user"><?php echo $this->lang->line('main_screen_username');?>
					</label>
    					<input size="25" class="text" id="username" name="username" type="text">
    				</div>
 				<div class="row clear">
					<label for="password"><?php echo $this->lang->line('main_screen_password');?></label>
					<input size="25" class="text" name="password" id="password" type="password">
				</div>
				<input class="button" value="Login" id="client_submit" type="submit">
                 
			</div>
			
		</div></form>
		<div class="login-footer clear">
		</div>
</div>
</div>
</div>

 <script type="text/javascript">
    $('#loginForm').submit(function(){
        var username = $('#username').val();
        var password = $('#password').val();
        
        var text = '';
        
        if(username == '')
        {
            text += "<?php echo $this->lang->line('login_username_prompt');?><br/>";
        }
        
        if(password == '')
        {
            text += "<?php echo $this->lang->line('login_password_prompt');?><br/>";
        }
        
        if(text != '')
        {
            $('.success').hide();
            $('.error').html(text);
            $('.error').fadeOut();
            $('.error').fadeIn();
            document.getElementById('err_div').scrollIntoView();
            $.unblockUI();
            return false;
        }
    });
</script>
</body></html>