<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
                <?php 
                    if($account_type == 'admin')
                    {
                        echo "New Admin Account";
                    }
                    else if($account_type == 'customer')
                    {
                        echo "New Customer Account";
                    }
                ?>
            </td>
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
        <td colspan="3"><div class="error" id="err_div" style="display:none;"></div></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div" style="display:none;"></div></td>
        </tr>

<?php if($account_type == 'admin'){?>        
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="addAdminAcc" id="addAdminAcc">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                <input type="hidden" value="<?php echo $account_type;?>" name="hidden_account_type"/>
                <tbody>
                
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> Username:</td>
                    <td align="left" width="55%"><input type="text" name="username" id="username" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Password:</td>
                    <td align="left"><input type="password" name="password" id="password" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span>Confirm Password:</td>
                    <td align="left"><input type="password" name="confirmpassword" id="confirmpassword" maxlength="50" class="textfield"></td>
                </tr>
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitaddAdminAccForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
                </tr>
            </tbody></table>
        </form>
    </td>
</tr>
<?php } else if($account_type == 'customer') {?>

    <?php if($customers_with_no_accounts->num_rows() > 0){?>
    <tr>
        <td align="center" height="20" colspan="3">
            <form enctype="multipart/form-data"  method="post" action="" name="addCustAcc" id="addCustAcc">
                <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                    <input type="hidden" value="<?php echo $account_type;?>" name="hidden_account_type"/>
                    <tbody>
                    
                    <tr>
                        <td align="right" width="45%"><span class="required">*</span> Customer:</td>
                        <td align="left" width="55%">
                            <select class="textfield" name="customer" id="customer">
                                <option value="">Select Customer</option>
                                <?php 
                                    foreach($customers_with_no_accounts->result() as $row)
                                    {
                                        echo '<option value="'.$row->customer_id.'">'.$row->customer_firstname.'</option>';
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="45%"><span class="required">*</span> Username:</td>
                        <td align="left" width="55%"><input type="text" name="username" id="username" maxlength="50" class="textfield"></td>
                    </tr>
                    <tr>
                        <td align="right"><span class="required">*</span> Password:</td>
                        <td align="left"><input type="password" name="password" id="password" maxlength="50" class="textfield"></td>
                    </tr>
                    
                    <tr>
                        <td align="right" colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><input border="0" id="submitaddCustAccForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                        
                        
                    </tr>
                </tbody></table>
            </form>
        </td>
    </tr>
    <?php } else { echo '<tr><td align="center" height="20" colspan="3" style="color:red;">Either you have setup accounts for all customers or no customers defined yet.</td></tr>'; } ?>

<?php } ?>

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
    $('#addAdminAcc').submit(function(){
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
                
        var username            = $('#username').val();
        var password            = $('#password').val();
        var confirmpassword     = $('#confirmpassword').val();
        
        var text = "";
        
        //common required fields check
        if(username == '' || password == '' || confirmpassword == '')
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(password != '' && confirmpassword != '')
        {
            if(password != confirmpassword)
            {
                text += "Password and Confirm Password did not match<br/>";
            }
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
           var form = $('#addAdminAcc').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"manage_accounts/create_new_account",
					data: form,
                    success: function(html){
                        if(html == 'username_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("Username has already been taken.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == "success")
                        {
                            $('.error').hide();
                            $('.success').html("Account created successfully.");
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                            $.unblockUI();
                        }
                    }
				});
                
            return false;
        }
        return false;
    });
    
    //customer account 
    $('#addCustAcc').submit(function(){
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
        var customer = $('#customer').val();
        
        var required_error = 0;
        
        //common required fields check
        if(username == '' || password == '' || customer == '')
        {
            required_error = 1;
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
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
           var form = $('#addCustAcc').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"manage_accounts/create_new_account",
					data: form,
                    success: function(html){
                        if(html == 'username_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("Username has already been taken.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == "success")
                        {
                            $('.error').hide();
                            $('.success').html("Account created successfully.");
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                            $.unblockUI();
                            $("#customer option[value='"+customer+"']").remove();
                        }
                    }
				});
                
            return false;
        }
        return false;
    });
</script>