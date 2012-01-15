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
    
        <title>:: LOGIN ::</title>
</head>

<body class="login">

<div class="login-box">
<div class="login-border">
<div class="login-style">
	<div class="login-header">
		<div class="logo clear">
			<img src="<?php echo base_url();?>assets/images/logo.png" alt="" class="picture">
		</div>
	</div>
    <div class="error" id="err_div" <?php if($this->session->flashdata('error_message') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('error_message');?> </div>
			<div class="success" id="success_div" style="display:none;"></div>
	<form name="loginForm" id="loginForm" action="<?php echo base_url();?>home/do_login" method="post" enctype="multipart/form-data">
		
		<div class="login-inside">
			<div class="login-data">
				<div class="row clear">
					<label for="user">Username:</label>
    					<input size="25" class="text" id="username" name="username" type="text">
    				</div>
 				<div class="row clear">
					<label for="password">Password:</label>
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
            text += "Please Enter Username<br/>";
        }
        
        if(password == '')
        {
            text += "Please Enter Password<br/>";
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