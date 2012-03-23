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
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            <?php echo $this->lang->line('my_account_title_bar');?>            </td>
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
                                <td align="right" width="45%"><span class="required">*</span><?php echo $this->lang->line('my_account_username');?></td>
                                <td align="left" width="55%"><input type="text" value="<?php echo $this->session->userdata('username');?>" name="username" id="username" class="textfield"></td>
                                <input type="hidden" value="<?php echo $this->session->userdata('username');?>" name="old_username" id="old_username" class="textfield">
                            </tr>
                            
                            <tr>
                                <td align="right"><span class="required">*</span><?php echo $this->lang->line('my_account_new_password');?></td>
                                <td align="left"><input type="password" name="password" id="password" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span><?php echo $this->lang->line('my_account_confirm_password');?></td>
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
        var old_username = $('#old_username').val();
        var password = $('#password').val();
        var confirmpassword = $('#confirmpassword').val();
        
        var required_error = 0;
        var pass_mismatched_error = 0;
        
        if(username == '')
        {
            required_error = 1;
        }
        
        if(password != '' || confirmpassword != '')
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
            text += "<?php echo $this->lang->line('my_account_fields_are_required');?><br/>";
        }
        
        if(pass_mismatched_error == 1)
        {
            text += "<?php echo $this->lang->line('my_account_password_confirm_do_not_match');?><br/>";
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