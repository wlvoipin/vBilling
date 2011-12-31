<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            MY ACCOUNT            </td>
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
                <tr>
            <td height="10"></td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="error" id="err_div" <?php if($this->session->flashdata('error') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('error');?></div></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success');?></div></td>
        </tr>
              
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="updateMyAccount" id="updateMyAccount">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <tbody>
                
                            <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left"><input type="checkbox" id="chng_username" value="Y" name="chng_username"/>&nbsp;Change Username</td>
                            </tr>
                            <tr>
                                <td align="right" width="45%"><span class="required">*</span> Username:</td>
                                <td align="left" width="55%"><input type="text" value="<?php echo $this->session->userdata('username');?>" name="username" id="username" class="textfield"></td>
                                <input type="hidden" value="<?php echo $this->session->userdata('username');?>" name="old_username" id="old_username" class="textfield">
                            </tr>
                            
                            <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left"><input type="checkbox" id="chng_password" value="Y" name="chng_password"/>&nbsp;Change Password</td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>New Password:</td>
                                <td align="left"><input type="password" name="password" id="password" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Confirm Password:</td>
                                <td align="left"><input type="password"  name="confirmpassword" id="confirmpassword" class="textfield"></td>
                            </tr>
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitupdateMyAccountForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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
    
    
    $('#updateMyAccount').submit(function(){
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
                
        var username = $('#username').val();
        var password = $('#password').val();
        var confirmpassword = $('#confirmpassword').val();
        
        var required_error = 0;
        var pass_mismatched_error = 0;
        
        if($('#chng_username').is(':checked'))
        {
            if(username == '')
            {
                required_error = 1;
            }
        }
        
        if($('#chng_password').is(':checked'))
        {
            if(password == '' || confirmpassword == '')
            {
                required_error = 1;
            }
            
            if(password != confirmpassword)
            {
                pass_mismatched_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(pass_mismatched_error == 1)
        {
            text += "Password and Confirm Password did not match<br/>";
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
           var form = $('#updateMyAccount').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"customers/update_my_account",
					data: form,
                    success: function(html){
                        window.location = base_url+"customers/my_account";
                    }
				});
                
            return false;
        }
        return false;
    });
    
</script>