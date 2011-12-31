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
        
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/css/jquery_ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript">
        //<![CDATA[
        base_url = '<?php echo base_url();?>';
        //]]>
        </script>
    
        <title>:: <?php echo $page_title;?> ::</title>
</head>

<body>
<div class="container">
  <div id="HeaderDiv">
    <div id="logoDiv"><img src="<?php echo base_url();?>assets/images/logo.png" alt="Digital Linx" height="33" width="167"></div>
    
    
    <div id="top-links">
        <?php 
        if($this->session->userdata('user_type') == 'admin'){
            echo '<a href="#">Welcome Admin</a>&nbsp;|&nbsp;';
            echo '<a href="'.base_url().'customers/my_account">My Account</a>&nbsp;|&nbsp;';
        }
        else if($this->session->userdata('user_type') == 'customer'){
            echo '<a href="#">Welcome Customer</a>&nbsp;|&nbsp;';
            echo '<a href="'.base_url().'customer/my_account">My Account</a>&nbsp;|&nbsp;';
        }
        ?>
        <a href="<?php echo base_url();?>home/logout" class="active">Sign out</a>
    </div>
    
  <div class="clr"></div>
 </div>