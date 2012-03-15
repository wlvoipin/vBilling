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
                <?php 
                    if($account_type == 'admin')
                    {
                        echo "New Sub-Admin Account";
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
                    <td align="left" width="55%"><input type="text" name="username" id="username" maxlength="15" class="textfield"></td>
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
                    <td align="right" valign="top"><span class="required">*</span>Define Access Level:</td>
                    <td align="left" valign="top">
                        <div>
                            <ul id="access_hirarchy">
                                <li class="group">CUSTOMERS 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span> <span class="full">Full Access<input type="checkbox" class="full_access f_a_vc_sub_sub_sub_sub_sub f_a_vc_sub_sub f_a_vc_sub_sub_sub_sub f_a_vc_sub_sub_sub f_a_vc_sub_sub f_a_vc"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View Customers <input type="checkbox" class="parent" id="vc" tabindex="vc" value="view_customers" name="access[]"/>
                                            <ul>
                                                <li>New Customer <input type="checkbox" class="child" tabindex="vc" id="vc_sub" value="new_customers" name="access[]"/></li>
                                                <li>Enable/Disable Customer <input type="checkbox" class="child" tabindex="vc" id="vc_sub" value="enable_disable_customers" name="access[]"/></li>
                                                <li>Edit Customer <input type="checkbox" class="parent child" tabindex="vc" id="vc_sub_sub" value="edit_customers" name="access[]"/>
                                                    <ul>
                                                        <li>View Customer CDR <input type="checkbox" class="child" tabindex="vc_sub_sub" value="view_customers_cdr" name="access[]"/></li>
                                                        <li>View Customer Rates <input type="checkbox" class="child" tabindex="vc_sub_sub" value="view_customers_rates" name="access[]"/></li>
                                                        <li>View Customer Billing <input type="checkbox" class="child" tabindex="vc_sub_sub" value="view_customers_billing" name="access[]"/></li>
                                                        <li>View Customer ACL Nodes <input type="checkbox" class="parent child" tabindex="vc_sub_sub" id="vc_sub_sub_sub" value="view_customers_acl" name="access[]"/>
                                                            <ul>
                                                                <li>Create New ACL Node <input type="checkbox" class="child" tabindex="vc_sub_sub_sub" value="new_acl" name="access[]"/></li>
                                                                <li>Edit ACL Node <input type="checkbox" class="child" tabindex="vc_sub_sub_sub" value="edit_acl" name="access[]"/></li>
                                                                <li>Delete Existing  ACL Nodes <input type="checkbox" class="child" tabindex="vc_sub_sub_sub" value="delete_acl" name="access[]"/></li>
                                                                <li>Change Type of ACL Node <input type="checkbox" class="child" tabindex="vc_sub_sub_sub" value="change_type_acl" name="access[]"/></li>
                                                            </ul>
                                                        </li>
                                                        <li>View SIP Credentials <input type="checkbox" class="parent child" tabindex="vc_sub_sub" id="vc_sub_sub_sub_sub" value="view_customers_sip" name="access[]"/>
                                                            <ul>
                                                                <li>Create New SIP Account <input type="checkbox" class="child" tabindex="vc_sub_sub_sub_sub" value="new_sip" name="access[]"/></li>
                                                                <li>Delete SIP Account <input type="checkbox" class="child" tabindex="vc_sub_sub_sub_sub" value="delete_sip" name="access[]"/></li>
                                                                <li>Enable/Disable SIP Account <input type="checkbox" class="child" tabindex="vc_sub_sub_sub_sub" value="enable_disable_sip" name="access[]"/></li>
                                                            </ul>
                                                        </li>
                                                        <li>View Manage Balance <input type="checkbox" class="parent child" tabindex="vc_sub_sub" id="vc_sub_sub_sub_sub_sub" value="view_customers_balance" name="access[]"/>
                                                            <ul>
                                                                <li>Add/Deduct Manage Balance <input type="checkbox" class="child" tabindex="vc_sub_sub_sub_sub_sub" value="add_deduct_balance" name="access[]"/></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="group">CARRIERS 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span>
                                    <span class="full" style="padding-left:89px">Full Access<input type="checkbox" class="full_access f_a_cr"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View Carriers <input type="checkbox" class="parent" id="cr" tabindex="cr" value="view_carriers" name="access[]"/>
                                            <ul>
                                                <li>Create New Carriers <input type="checkbox" class="child" tabindex="cr" value="new_carriers" name="access[]"/></li>
                                                <li>Edit Carriers <input type="checkbox" class="child" tabindex="cr" value="edit_carriers" name="access[]"/></li>
                                                <li>Enable/Disable Carriers <input type="checkbox" class="child" tabindex="cr" value="enable_disable_carriers" name="access[]"/></li>
                                                <li>Delete Carriers <input type="checkbox" class="child" tabindex="cr" value="delete_carriers" name="access[]"/></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="group">RATE GROUPS 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span>
                                    <span class="full" style="padding-left:60px">Full Access<input type="checkbox" class="full_access f_a_rg"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View Rate Groups <input type="checkbox" class="parent" id="rg" tabindex="rg" value="view_rate_groups" name="access[]"/>
                                            <ul>
                                                <li class="sub_group">Create New Rate Groups <input type="checkbox" class="child" tabindex="rg" value="new_rate_groups" name="access[]"/></li>
                                                <li class="sub_group">Edit Rate Groups <input type="checkbox" class="child" tabindex="rg" value="edit_rate_groups" name="access[]"/></li>
                                                <li class="sub_group">Enable/Disable Rate Groups <input type="checkbox" class="child" tabindex="rg" value="enable_disable_rate_groups" name="access[]"/></li>
                                                <li class="sub_group">Delete Rate Groups <input type="checkbox" class="child" tabindex="rg" value="delete_rate_groups" name="access[]"/></li>
                                                <li class="sub_group">Create New Rate <input type="checkbox" class="child" tabindex="rg" value="new_rate" name="access[]"/></li>
                                                <li class="sub_group">Import Rate By CSV <input type="checkbox" class="child" tabindex="rg" value="import_csv" name="access[]"/></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="group">CDR 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span>
                                    <span class="full" style="padding-left:129px">Full Access<input type="checkbox" class="full_access f_a_cdr"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View CDR <input type="checkbox" class="parent" id="cdr" tabindex="cdr" value="view_cdr" name="access[]"/>
                                            <ul>
                                                <li class="sub_group">Gateway Stats <input type="checkbox" class="child" tabindex="cdr" value="view_gateway_stats" name="access[]"/></li>
                                                <li class="sub_group">Customer Stats <input type="checkbox" class="child" tabindex="cdr" value="view_customer_stats" name="access[]"/></li>
                                                <li class="sub_group">Call Destination <input type="checkbox" class="child" tabindex="cdr" value="view_call_destination" name="access[]"/></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="group">BILLING 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span>
                                    <span class="full" style="padding-left:104px">Full Access<input type="checkbox" class="full_access f_a_billing f_a_billing_sub"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View Billing Summary <input type="checkbox" class="parent" id="billing" tabindex="billing" value="view_biling" name="access[]"/>
                                            <ul>
                                                <li class="sub_group">View Invoices <input type="checkbox" class="parent child" tabindex="billing" id="billing_sub" value="view_invoices" name="access[]"/>
                                                    <ul>
                                                        <li>Generate Invoices <input type="checkbox" class="child" tabindex="billing_sub" value="generate_invoices" name="access[]"/></li>
                                                        <li>Mark Invoices As Paid <input type="checkbox" class="child" tabindex="billing_sub" value="mark_invoices_paid" name="access[]"/></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="group">FREESWITCH 
                                    <span class="btnspan"><a href="#" class="btn"><img src="<?php echo base_url();?>assets/images/down_arrow.gif"/></a></span>
                                    <span class="full" style="padding-left:69px">Full Access<input type="checkbox" class="full_access f_a_freeswitch f_a_freeswitch_sub"/> </span>
                                    
                                    <ul style="display:none" class="hid">
                                        <li class="sub_group">View Profiles <input type="checkbox" class="parent" id="freeswitch" tabindex="freeswitch" value="view_profiles" name="access[]"/>
                                            <ul>
                                                <li>New Profile <input type="checkbox" class="child" tabindex="freeswitch" value="new_profiles" name="access[]"/></li>
                                                <li>Delete Profile <input type="checkbox" class="child" tabindex="freeswitch" value="delete_profiles" name="access[]"/></li>
                                                <li>FREESWITCH Status <input type="checkbox" class="child" tabindex="freeswitch" value="freeswitch_status" name="access[]"/></li>
                                                <li class="sub_group">View Profile Details <input type="checkbox" class="parent child" tabindex="freeswitch"  id="freeswitch_sub" value="profile_details" name="access[]"/>
                                                    <ul>
                                                        <li>Create New Gateway <input type="checkbox" class="child" tabindex="freeswitch_sub" value="new_gateway" name="access[]"/></li>
                                                        <li>Delete Gateway <input type="checkbox" class="child" tabindex="freeswitch_sub" value="delete_gateway" name="access[]"/></li>
                                                        <li>Edit Gateway <input type="checkbox" class="child" tabindex="freeswitch_sub" value="edit_gateway" name="access[]"/></li>
                                                        <li>Delete Settings <input type="checkbox" class="child" tabindex="freeswitch_sub" value="delete_settings" name="access[]"/></li>
                                                        <li>Edit Settings <input type="checkbox" class="child" tabindex="freeswitch_sub" value="edit_settings" name="access[]"/></li>
                                                    </ul>
                                                </li>
                                                
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </td>
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
    <script type="text/javascript">
        $('.btn').click(function(){
                var src = base_url+'assets/images/up_arrow.gif';
                if($(this).find('img').attr('src') == ''+base_url+'assets/images/up_arrow.gif')
                {
                    src = base_url+'assets/images/down_arrow.gif';
                }
                $(this).find('img').attr('src', ''+src+'');
                $(this).parent().parent().find('.hid').toggle();
            return false;
        });
        
        $('.full_access').click(function(){
            if ($(this).is(':checked'))
            {
                $(this).parent().parent().find('input[type=checkbox]').attr('checked', true);
            }
            else
            {
                $(this).parent().parent().find('input[type=checkbox]').attr('checked', false);
            }
        });
        
        $('.parent').click(function(){
            var tabindex = $(this).attr('tabindex');
            if (!$(this).is(':checked'))
            {
                $(this).parent().find('input[type=checkbox]').attr('checked', false);
                $('.f_a_'+tabindex+'').attr('checked', false);
            }
        });
        
        $('.child').click(function(){
            var parent_id = $(this).attr('tabindex');
            
            if ($(this).is(':checked')) //if checked 
            {
                if (!$('#'+parent_id+'').is(':checked'))
                {
                    alert("Please Select Its Parent First");
                    $(this).attr('checked', false);
                }
            }
            else //if not checked 
            {
                $('.f_a_'+parent_id+'').attr('checked', false); //uncheck full access 
            }
        });
    </script>
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
                        <td align="left" width="55%"><input type="text" name="username" id="username" maxlength="15" class="textfield"></td>
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
       
        if(!$('#vc').is(':checked') && !$('#cr').is(':checked') && !$('#rg').is(':checked') && !$('#cdr').is(':checked') && !$('#billing').is(':checked') && !$('#freeswitch').is(':checked'))
        {
            text += "Please Define Access Level<br/>";
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