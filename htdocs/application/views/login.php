<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="<?php echo base_url();?>assets/js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/blockUI.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/timepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.alphanumeric.pack.js" type="text/javascript"></script>
        
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/css/jquery_ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript">
        //<![CDATA[
        base_url = '<?php echo base_url();?>';
        //]]>
        </script>
    
        <title>:: LOGIN ::</title>
</head>

<body>
<div class="container">
  <div id="HeaderDiv">
    <div id="logoDiv"><img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167"></div>
    
  <div class="clr"></div>
 </div>
  
  <div class="content">
  	
	<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody><tr>
    <td height="35" width="21"></td>
    <td class="heading" width="825">Login</td>
    
  </tr>
   <tr>
    <td colspan="3" background="<?php echo base_url();?>assets/images/line.png" height="7"></td>
  </tr>
   <tr>
    <td height="10"></td>
    <td></td>
    <td></td>
  </tr>
  
        <tr>
        <td colspan="3">
            <div class="error" id="err_div" <?php if($this->session->flashdata('error_message') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('error_message');?> </div>
        </td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div" style="display:none;"></div></td>
        </tr>
        
    <tr>
    <td colspan="3" height="20">
    <form name="loginForm" id="loginForm" action="<?php echo base_url();?>home/do_login" method="post" enctype="multipart/form-data">
        <input name="action" value="submit" type="hidden">
    <table class="search_col" border="0" cellpadding="3" cellspacing="5" width="100%">
      <tbody><tr>
        <td align="right" height="25" width="45%"><span class="small_heading">Username:</span></td>
        <td align="left" width="55"><input name="username" id="username" class="textfield" type="text"></td>
      </tr>

      <tr>
        <td align="right" height="25"><span class="small_heading">Password:</span></td>
        <td align="left"><input class="textfield" name="password" id="password" type="password"></td>
      </tr>

      <tr>
        <td colspan="2" align="center"><input src="<?php echo base_url();?>assets/images/btn-submit.png" type="image"></td>
      </tr>
	  
    </tbody></table>
    </form>
    </td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
  </tr>
  
  </tbody></table>
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