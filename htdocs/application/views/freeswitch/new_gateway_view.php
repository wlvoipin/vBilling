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
            New Sofia Profile Gateway           
            
            <a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>" class="right">BACK</a>
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
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="addGateway" id="addGateway">
            <input type="hidden" name="hidden_profile_id" id="hidden_profile_id" value="<?php echo $sofia_id;?>" />
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                <tbody>
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> Gateway Name:</td>
                    <td align="left" width="55%"><input type="text" value="" name="name" id="name" maxlength="50" class="textfield alphanumeric"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Username:</td>
                    <td align="left"><input type="text" value="vBilling" name="username" id="username" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span>Password:</td>
                    <td align="left"><input type="text" value="vbilling" name="password" id="password" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> IP Address:</td>
                    <td align="left"><input type="text" value="" name="proxy" id="proxy" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Channels:</td>
                    <td align="left"><input type="text" value="1" name="channels" id="channels" maxlength="5" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span>Register:</td>
                    <td align="left">
                        <select name="register" id="register" class="textfield">
                            <option value="false">False</option>
                            <option value="true">True</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitaddGatewayForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
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
    $('#addGateway').submit(function(){
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
                
        var name = $('#name').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var proxy = $('#proxy').val();
        var register = $('#register').val();
        var channels = $('#channels').val();
        var sofia_id = $('#hidden_profile_id').val();
        
        var required_error = 0;
        var channels_error = 0;
        
        //common required fields check
        if(name == '' || username == '' || password == '' || proxy == '' || register == '' || channels == '')
        {
            required_error = 1;
        }
        
        if(channels != '')
        {
            if(channels == 0 || channels > 10000)
            {
                channels_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(channels_error == 1)
        {
            text += "Channels value should be between 1 to 10000<br/>";
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
           var form = $('#addGateway').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"freeswitch/insert_new_gateway",
					data: form,
                    success: function(html){
                            if(html == 'gateway_name_in_use')
                            {
                                $('.success').hide();
                                $('.error').html("You already have a gateway named as "+name+"");
                                $('.error').fadeOut();
                                $('.error').fadeIn();
                                document.getElementById('err_div').scrollIntoView();
                                $.unblockUI();
                            }
                            else if(html == 'proxy_in_use')
                            {
                                $('.success').hide();
                                $('.error').html("Proxy already in use");
                                $('.error').fadeOut();
                                $('.error').fadeIn();
                                document.getElementById('err_div').scrollIntoView();
                                $.unblockUI();
                            }
                            else
                            {
                                $('.error').hide();
                                $('.success').html("Gateway created Successfully.");
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
    $('.alphanumeric').alphanumeric({allow:"_"});
</script>
