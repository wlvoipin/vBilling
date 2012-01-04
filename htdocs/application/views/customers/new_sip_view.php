<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            New SIP Credentials            </td>
            <td width="178">
            <table cellspacing="0" cellpadding="0" width="170" height="42" class="search_col">
                <tbody><tr>
                    <td align="center" width="53" valign="bottom">&nbsp;</td>
                </tr>
                
                <tr>
                    <td align="center" width="53" valign="top">&nbsp;</td>
                </tr>
            </tbody></table>
            </td>
        </tr>
        <tr>
        <td background="<?php echo base_url();?>assets/images/line.png" height="7" colspan="3"></td>
        </tr>
        
        <?php require_once("pop_up_menu.php");?>

                <tr>
            <td height="10"></td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="error" id="err_div" style="display:none;"></div></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div" style="display:none;"></div></td>
        </tr>
              
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="addSipAccess" id="addSipAccess">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>"/>
                
                <tbody>
                
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> Username:</td>
                    <td align="left" width="55%"><input type="text" name="username" id="username" readonly maxlength="6" class="textfield" value="<?php echo $username;?>"></td>
                </tr>
                
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> Password:</td>
                    <td align="left" width="55%"><input type="text" name="password" id="password" readonly maxlength="8" class="textfield" value="<?php echo $password;?>"></td>
                </tr>
                
                <tr>
                    <td align="right"><span class="required">*</span> SIP IP:</td>
                    <td align="left">
                        <select  name="sip_ip" id="sip_ip" class="textfield">
                            <?php echo get_all_sip_ips(); ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitaddSipAccessForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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

<tr>
    <td height="5"></td>
    <td></td>
    <td></td>
</tr>


<tr>
    <td height="20" colspan="3">&nbsp;</td>
</tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    </tbody></table>

<script type="text/javascript">
    
    
    $('#addSipAccess').submit(function(){
        //show wait msg 
    $.blockUI({ css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                    } 
                });
                
        var username    = $('#username').val();
        var password    = $('#password').val();
        var sip_ip      = $('#sip_ip').val();
        
        var required_error = 0;
        var password_error = 0;
        
        //common required fields check
        if(username == '' || password == '' || sip_ip == '')
        {
            required_error = 1;
        }
        
        if(password != '')
        {
            if(password.length < 6)
            {
                password_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields.<br/>";
        }
        
        if(password_error == 1)
        {
            text += "Password must be atleast 6 characters long.<br/>";
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
        else
        {
           var form = $('#addSipAccess').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"customers/insert_new_sip_access",
					data: form,
                    success: function(html){
                        $('.error').hide();
                        $('.success').html("New SIP Credentials Added Successfully.");
                        $('.success').fadeOut();
                        $('.success').fadeIn();
                        document.getElementById('success_div').scrollIntoView();
                        $.unblockUI();
                    }
				});
                
            return false;
        }
        return false;
    });
    
   // $('.numeric').numeric({allow:"."});
    
</script>