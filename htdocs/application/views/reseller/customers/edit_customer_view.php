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
<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>

<?php 
    $row = $customer->row();
?>


<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Update Customer            </td>
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
        <td colspan="3"><div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success');?></div></td>
        </tr>

<?php 
    //if this customer is the child of the logged in user ... means logged in user is his parent 
    //allow to edit the customer 
    if($row->parent_id == $this->session->userdata('customer_id'))
    {
?>        
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="addCust" id="addCust">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>"/>
                
                <tbody>
                
                <tr style="display:none;">
                    <td align="right"><span class="required">*</span> Type:</td>
                    <td align="left">
                        <select class="textfield" name="type" id="type">
                            <option value="0" <?php if($row->reseller_level == '0'){ echo "selected";} ?>>Normal Customer</option>
                            <option value="2" <?php if($row->reseller_level == '2'){ echo "selected";} ?>>Reseller (Level- 2)</option>
                        </select>
                </td></tr>
                
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> Firstname:</td>
                    <td align="left" width="55%"><input type="text" value="<?php echo $row->customer_firstname;?>" name="firstname" id="firstname" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Lastname:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_lastname;?>" name="lastname" id="lastname" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right">Company Name:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_company;?>" name="companyname" id="companyname" maxlength="45" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Email:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_contact_email;?>" name="email" id="email" maxlength="100" class="textfield"></td>
                    <input type="hidden" name="oldemail" id="oldemail" value="<?php echo $row->customer_contact_email;?>"/>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Account type:</td>
                    <td align="left">
                        <select  name="account_type" id="account_type" class="textfield">
                            <option value="">Select Account type</option>
                            <option value="1" <?php if($row->customer_prepaid == 1){ echo "selected";}?> >Prepaid</option>
                            <option value="0" <?php if($row->customer_prepaid == 0){ echo "selected";}?> >Postpaid</option>
                        </select>
                    </td>
                </tr>
                <tr class="account_type_dependent" <?php if($row->customer_prepaid == 1){ echo 'style="display:none;"';}?> >
                    <td align="right"><span class="required">*</span> Credit Limit:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_credit_limit;?>" name="creditlimit" id="creditlimit" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Bill Cycle:</td>
                    <td align="left">
                        <select  name="billing_cycle" id="billing_cycle" class="textfield">
                            <option value="">Select Bill Cycle</option>
                            <option value="daily" <?php if($row->customer_billing_cycle == "daily"){ echo "selected";}?>>Daily</option>
                            <option value="weekly" <?php if($row->customer_billing_cycle == "weekly"){ echo "selected";}?>>Weekly</option>
                            <option value="bi_weekly" <?php if($row->customer_billing_cycle == "bi_weekly"){ echo "selected";}?>>Bi-Weekly</option>
                            <option value="monthly" <?php if($row->customer_billing_cycle == "monthly"){ echo "selected";}?>>Monthly</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Concurrent Calls:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_max_calls;?>" name="maxcalls" id="maxcalls" class="textfield"></td>
                </tr>
                
                <tr>
                    <td align="right"><span class="required">*</span> Address:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_address;?>" name="address" id="address" maxlength="150" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> City:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_city;?>" name="city" id="city" maxlength="45" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> State:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_state;?>" name="state" id="state" maxlength="45" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Zipcode:</td>
                    <td align="left"><input type="text" value="<?php echo $row->customer_zip;?>" name="zipcode" id="zipcode" maxlength="10" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Country:</td>
                    <td align="left">
                        <select class="textfield" name="country" id="country">
                            <?php echo all_countries($row->customer_country);?>
                        </select>
                </td></tr>
                <tr>
                    <td align="right"><span class="required">*</span> Phone:</td>
                    <td align="left">
                    <input type="text" style="width:55px" value="<?php echo $row->customer_phone_prefix;?>" name="prefix" id="prefix" maxlength="10" class="textfield" readonly >
                    <input type="text" style="width:119px" value="<?php echo $row->customer_phone;?>" name="phone" id="phone" maxlength="45" class="textfield"></td>
                </tr>
                <tr>
                    <td align="right">Timezone:</td>
                    <td align="left">
                        <select class="textfield" name="timezone" id="timezone">
                            <?php echo all_timezones($row->customer_timezone);?>                       
                        </select>
                </td></tr>
                <tr>
                    <td align="right"><span class="required">*</span> Rate Group:</td>
                    
                    <!-- if customer is not reseller allow to change the rate group -->
                    <?php if($row->reseller_level == 0){?>
                    <td align="left">
                        <select id="group" name="group" class="textfield">
                            <?php echo show_group_select_box_valid_invalid_reseller($row->customer_rate_group);?>
                        </select>
                    </td>
                    <?php } else {?>
                    <!-- dont allow to change the rate group -->
                        <td align="left">
                            <?php echo group_any_cell($row->customer_rate_group, 'group_name');?>
                            <select id="group" name="group" class="textfield" style="display:none;">
                                <?php echo show_group_select_box_valid_invalid_reseller($row->customer_rate_group);?>
                            </select>
                        </td>
                    <?php } ?>
                </tr>
                
                <tr>
                    <td align="right">&nbsp;</td>
                    <td align="left"><input type="checkbox" id="cdr_check" value="1" name="cdr_check" <?php if($row->customer_send_cdr == '1'){ echo 'checked="checked"'; }?>>&nbsp;Attach CDR With Email</td>
                </tr>
                <tr>
                    <td align="right">&nbsp;</td>
                    <td align="left"><input type="checkbox" id="same_check" value="1" name="same_check">&nbsp;Billing email same as contact email</td>
                </tr>
                <tr id="cdr_tr">
                    <td align="center" colspan="2">
                        <table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                            <tbody><tr>
                                <td align="right" width="45%"><span class="required">*</span> Billing Email:</td>
                                <td align="left" width="55%"><input type="text" value="<?php echo $row->customer_billing_email;?>" name="cdr_email" id="cdr_email" maxlength="100" class="textfield"></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                
                <?php 
                    if($customer_access->num_rows() > 0){
                        $rowAccess = $customer_access->row();
                ?>
                <tr>
                    <td align="center" colspan="2" style="background:#dadada;padding:5px;">User Panel Access Info</td>
                </tr>
                <tr id="userpass">
                    <td align="center" colspan="2">
                        <table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                            <tbody>
                            
                            <tr>
                                <td align="right" width="45%"><span class="required">*</span> Username:</td>
                                <td align="left" width="55%"><input type="text" value="<?php echo $rowAccess->username;?>" name="username" id="username" class="textfield"></td>
                                <input type="hidden" value="<?php echo $rowAccess->username;?>" name="old_username" id="old_username" class="textfield">
                            </tr>
                            
                            
                            <tr>
                                <td align="right"><span class="required">*</span> Password:</td>
                                <td align="left"><input type="password" name="password" id="password" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Confirm Password:</td>
                                <td align="left"><input type="password"  name="confirmpassword" id="confirmpassword" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Total # of ACL Nodes:</td>
                                <td align="left"><input type="text" value="<?php echo customer_access_any_cell($customer_id, 'total_acl_nodes');?>" name="tot_acl_nodes" id="tot_acl_nodes" class="textfield numeric" maxlength="2"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Total # of SIP Accounts:</td>
                                <td align="left"><input type="text" value="<?php echo customer_access_any_cell($customer_id, 'total_sip_accounts');?>" name="tot_sip_acc" id="tot_sip_acc" class="textfield numeric" maxlength="2"></td>
                            </tr>
                            
                            <tr style="display:none;">
                                <td align="right"><span class="required">*</span> SIP IP: (Select All That Apply)</td>
                                <td align="left">
                                    <select  name="sip_ip[]" id="sip_ip" class="textfield" multiple="multiple" size="5" >
                                        <?php echo get_all_sip_ips_customer($customer_id); ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left"><input type="checkbox" value="Y" name="email_check" id="email_check" readonly>&nbsp;Email this Information to Customer</td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <?php } else { ?>
                    <tr>
                    <td align="right">&nbsp;</td>
                    <td align="left"><input type="checkbox" id="access_chk" value="Y" name="access_chk">&nbsp;Allow Userpanel Access</td>
                </tr>
                <tr id="userpass" style="display:none">
                    <td align="center" colspan="2">
                        <table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                            <tbody><tr>
                                <td align="right" width="45%"><span class="required">*</span> Username:</td>
                                <td align="left" width="55%"><input type="text" value="" name="username" id="username" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span> Password:</td>
                                <td align="left"><input type="password" value="" name="password" id="password" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Confirm Password:</td>
                                <td align="left"><input type="password" value="" name="confirmpassword" id="confirmpassword" class="textfield"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Total # of ACL Nodes:</td>
                                <td align="left"><input type="text" value="5" name="tot_acl_nodes" id="tot_acl_nodes" class="textfield numeric" maxlength="2"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span>Total # of SIP Accounts:</td>
                                <td align="left"><input type="text" value="5" name="tot_sip_acc" id="tot_sip_acc" class="textfield numeric" maxlength="2"></td>
                            </tr>
                            <tr>
                                <td align="right"><span class="required">*</span> SIP IP (Select All That Apply):</td>
                                <td align="left">
                                    <select  name="sip_ip[]"  id="sip_ip" class="textfield" multiple="multiple" size="5">
                                        <?php echo get_all_sip_ips_customer_not_selected($this->session->userdata('customer_id')); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left"><input type="checkbox" value="Y" name="email_check" id="email_check">&nbsp;Email this Information to Customer</td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <?php }?>
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitAddCustForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
                </tr>
            </tbody></table>
            <input type="hidden" name="has_user_access" value="<?php echo $customer_access->num_rows();?>" />
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
    $('#same_check').click(function(){
        if ($(this).is(':checked'))
        {
            $('#cdr_email').val($('#email').val());
        }
        else
        {
            $('#cdr_email').val('');
        }
    });
    
    $('#access_chk').click(function(){
        if ($(this).is(':checked'))
        {
            $('#userpass').show();
        }
        else
        {
            $('#userpass').hide();
        }
    });
    
    $('#account_type').change(function(){
        var val = $(this).val();
        if(val == '0')
        {
            $('.account_type_dependent').show();
        }
        else if(val == '1' || val == "")
        {
            $('.account_type_dependent').hide();
        }
    });
    
    $('#country').change(function(){
        var val = $(this).val();
        
        $.ajax({
            type: "POST",
            url: base_url+"reseller/customers/get_country_prefix",
            data: 'id='+val,
            success: function(html){
                $('#prefix').val('+'+html+'');
            }
       });
    });
</script>

<?php if($customer_access->num_rows() > 0){ ?>
    <script type="text/javascript">
$('#addCust').submit(function(){
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
                
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var companyname = $('#companyname').val();
        var email = $('#email').val();
        var maxcalls = $('#maxcalls').val();
        var address = $('#address').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var zipcode = $('#zipcode').val();
        var country = $('#country').val();
        var prefix = $('#prefix').val();
        var phone = $('#phone').val();
        var timezone = $('#timezone').val();
        var billingcycle = $('#billingcycle').val();
        var creditlimit = $('#creditlimit').val();
        var accounttype = $('#account_type').val();
        var cdr_email = $('#cdr_email').val();
        var group = $('#group').val();
        var billing_cycle = $('#billing_cycle').val();
        
        var username = $('#username').val();
        var password = $('#password').val();
        var confirmpassword = $('#confirmpassword').val();
        
        var tot_acl_nodes = $('#tot_acl_nodes').val();
        var tot_sip_acc = $('#tot_sip_acc').val();
        
        
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        
        var required_error = 0;
        var cdr_email_err = 0;
        var email_error = 0;
        var pass_mismatched_error = 0;
        
        //common required fields check
        if(firstname == '' || lastname == '' || maxcalls == '' || address == '' || city == '' || state == '' || zipcode == '' || country == '' || group == '' || tot_acl_nodes == '' || tot_sip_acc == '' || billing_cycle == '')
        {
            required_error = 1;
        }
        
        //billing type check 
        if(accounttype == '')
        {
            required_error = 1;
        }
        else
        {
            if(accounttype == '0' && creditlimit == '')
            {
                required_error = 1;
            }
            
            if(accounttype == '0' && billingcycle == '')
            {
                required_error = 1;
            }
        }
        
        //email check 
        if(email == '')
        {
            required_error = 1;
        }
        else
        {
            if(!pattern.test(email))
            {
                email_error = 1;
            }
        }
           
        //cdr check
        if(cdr_email == '')
        {
            required_error = 1;
        }
        else
        {
            if(!pattern.test(cdr_email))
            {
                cdr_email_err = 1;
            }
        }
        
        //access check
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
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(email_error == 1)
        {
            text += "Please Enter Valid Customer Email Address<br/>";
        }
        
        if(cdr_email_err == 1)
        {
            text += "Please Enter Valid Billing Email Address";
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
           var form = $('#addCust').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"reseller/customers/update_customer_db",
					data: form,
                    success: function(html){
                        if(html == 'email_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("Customer email is already in use.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == 'group_invalid')
                        {
                            $('.success').hide();
                            $('.error').html("The selected group is in valid.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == 'username_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("This Username:"+username+" has already been taken.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == "success")
                        {
                            $('.error').hide();
                            $('.success').html("Customer updated successfully.");
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                            $.unblockUI();
                            location.reload();
                        }
                    }
				});
                
            return false;
        }
        return false;
    });
</script>
<?php } else { ?>
<script type="text/javascript">
$('#addCust').submit(function(){
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
                
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var companyname = $('#companyname').val();
        var email = $('#email').val();
        var maxcalls = $('#maxcalls').val();
        var address = $('#address').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var zipcode = $('#zipcode').val();
        var country = $('#country').val();
        var prefix = $('#prefix').val();
        var phone = $('#phone').val();
        var timezone = $('#timezone').val();
        var billingcycle = $('#billingcycle').val();
        var creditlimit = $('#creditlimit').val();
        var accounttype = $('#account_type').val();
        var cdr_email = $('#cdr_email').val();
        var group = $('#group').val();
        var billing_cycle = $('#billing_cycle').val();
        
        var username = $('#username').val();
        var password = $('#password').val();
        var confirmpassword = $('#confirmpassword').val();
        var tot_acl_nodes = $('#tot_acl_nodes').val();
        var tot_sip_acc = $('#tot_sip_acc').val();
        var sip_ip = $('#sip_ip').val();
        
        
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        
        var required_error = 0;
        var cdr_email_err = 0;
        var email_error = 0;
        var pass_mismatched_error = 0;
        
        //common required fields check
        if(firstname == '' || lastname == '' || maxcalls == '' || address == '' || city == '' || state == '' || zipcode == '' || country == '' || group == '' || billing_cycle == '')
        {
            required_error = 1;
        }
        
        //billing type check 
        if(accounttype == '')
        {
            required_error = 1;
        }
        else
        {
            if(accounttype == '0' && creditlimit == '')
            {
                required_error = 1;
            }
            
            if(accounttype == '0' && billingcycle == '')
            {
                required_error = 1;
            }
        }
        
        //email check 
        if(email == '')
        {
            required_error = 1;
        }
        else
        {
            if(!pattern.test(email))
            {
                email_error = 1;
            }
        }
           
        //cdr check
        if(cdr_email == '')
        {
            required_error = 1;
        }
        else
        {
            if(!pattern.test(cdr_email))
            {
                cdr_email_err = 1;
            }
        }
        
        //access check
        if($('#access_chk').is(':checked'))
        {
            if(username == '' || password == '' || confirmpassword == '' || tot_acl_nodes == '' || tot_sip_acc == '' || sip_ip == '' || sip_ip == null)
            {
                required_error = 1;
            }
            
            if(password != '' && confirmpassword != '')
            {
                if(password != confirmpassword)
                {
                    pass_mismatched_error = 1;
                }
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(email_error == 1)
        {
            text += "Please Enter Valid Customer Email Address<br/>";
        }
        
        if(cdr_email_err == 1)
        {
            text += "Please Enter Valid Billing Email Address";
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
           var form = $('#addCust').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"reseller/customers/update_customer_db",
					data: form,
                    success: function(html){
                        if(html == 'email_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("Customer email is already in use.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == 'group_invalid')
                        {
                            $('.success').hide();
                            $('.error').html("The selected group is in valid.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == 'username_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("This Username:"+username+" has already been taken.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == "success")
                        {
                            $('.error').hide();
                            $('.success').html("Customer updated successfully.");
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                            $.unblockUI();
                            location.reload();
                        }
                    }
				});
                
            return false;
        }
        return false;
    });
</script>
<?php } ?>

<!--IS CHILD CHECK ELSE PART -->
<?php } else { ?>
<tr>
    <td align="center" height="20" colspan="3">
        
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <tbody>
                
                <tr>
                    <td align="right" width="45%">Firstname:</td>
                    <td align="left" width="55%"><?php echo $row->customer_firstname;?></td>
                </tr>
                <tr>
                    <td align="right">Lastname:</td>
                    <td align="left"><?php echo $row->customer_lastname;?></td>
                </tr>
                <tr>
                    <td align="right">Company Name:</td>
                    <td align="left"><?php echo $row->customer_company;?></td>
                </tr>
                <tr>
                    <td align="right">Email:</td>
                    <td align="left"><?php echo $row->customer_contact_email;?></td>
                </tr>
                <tr>
                    <td align="right">Account type:</td>
                    <td align="left">
                        <?php if($row->customer_prepaid == 1){ echo "Prepaid";} else { echo "Postpaid"; }?>
                    </td>
                </tr>
                <tr class="account_type_dependent" <?php if($row->customer_prepaid == 1){ echo 'style="display:none;"';}?> >
                    <td align="right">Credit Limit:</td>
                    <td align="left"><?php echo $row->customer_credit_limit;?></td>
                </tr>
                <tr>
                    <td align="right">Bill Cycle:</td>
                    <td align="left"> <?php echo $row->customer_billing_cycle;?> </td>
                </tr>
                <tr>
                    <td align="right">Max Calls:</td>
                    <td align="left"><?php echo $row->customer_max_calls;?></td>
                </tr>
                
                <tr>
                    <td align="right">Address:</td>
                    <td align="left"><?php echo $row->customer_address;?></td>
                </tr>
                <tr>
                    <td align="right">City:</td>
                    <td align="left"><?php echo $row->customer_city;?></td>
                </tr>
                <tr>
                    <td align="right">State:</td>
                    <td align="left"><?php echo $row->customer_state;?></td>
                </tr>
                <tr>
                    <td align="right">Zipcode:</td>
                    <td align="left"><?php echo $row->customer_zip;?></td>
                </tr>
                <tr>
                    <td align="right">Country:</td>
                    <td align="left"> <?php  echo country_any_cell($row->customer_country, 'countryname');?> </td>
                </tr>
                <tr>
                    <td align="right">Phone:</td>
                    <td align="left"> <?php echo $row->customer_phone_prefix.'-'.$row->customer_phone;?></td>
                </tr>
                <tr>
                    <td align="right">Timezone:</td>
                    <td align="left"> <?php echo timezone_any_cell($row->customer_timezone, 'timezone_location').' '.timezone_any_cell($row->customer_timezone, 'gmt');?></td>
                </tr>
                <tr>
                    <td align="right">Group:</td>
                    <td align="left">
                        <?php echo group_any_cell($row->customer_rate_group, 'group_name');?>
                    </td>
                </tr>
                <tr>
                    <td align="right">Billing Email:</td>
                    <td align="left"><?php echo $row->customer_billing_email;?></td>
                </tr>
                
                <?php 
                    if($customer_access->num_rows() > 0){
                        $rowAccess = $customer_access->row();
                ?>
                <tr>
                    <td align="center" colspan="2" style="background:#dadada;padding:5px;">User Panel Access Info</td>
                </tr>
                <tr id="userpass">
                    <td align="center" colspan="2">
                        <table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                            <tbody>
                            
                            <tr>
                                <td align="right" width="45%">Username:</td>
                                <td align="left" width="55%"><?php echo $rowAccess->username;?></td>
                            </tr>
                            <tr>
                                <td align="right">Total # of ACL Nodes:</td>
                                <td align="left"><?php echo customer_access_any_cell($customer_id, 'total_acl_nodes');?></td>
                            </tr>
                            <tr>
                                <td align="right">Total # of SIP Accounts:</td>
                                <td align="left"><?php echo customer_access_any_cell($customer_id, 'total_sip_accounts');?></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <?php } else { ?>
                    <tr>
                    <td align="center" colspan="2" style="background:#dadada;padding:5px;">No User Panel Access</td>
                </tr>
                <?php }?>
                
            </tbody></table>
            
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
<?php } ?>