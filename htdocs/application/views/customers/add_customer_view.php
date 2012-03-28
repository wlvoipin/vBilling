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
  <tbody>
    <tr>
      <td width="21" height="35"></td>
      <td width="825" class="heading"> New Customer </td>
      <td width="178"><table cellspacing="0" cellpadding="0" width="170" height="42" class="search_col">
          <tbody>
            <tr>
              <td align="center" width="53" valign="bottom">&nbsp;</td>
            </tr>
            <tr>
              <td align="center" width="53" valign="top">&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
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
      <td align="center" height="20" colspan="3"><form enctype="multipart/form-data"  method="post" action="" name="addCust" id="addCust">
          <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
            <tbody>
              <tr>
                <td align="right"><span class="required">*</span> Type:</td>
                <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_customer_type');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><select class="textfield" name="type" id="type">
                    <option value="0" selected>Normal Customer</option>
                    <option value="3">Reseller (Level- 3)</option>
                    <option value="2">Reseller (Level- 2)</option>
                  </select></td>
              </tr>
              <tr>
                <td align="right" width="45%"><span class="required">*</span> Firstname:</td>
                <td align="left" width="55%"><input onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_firstname');?>', HAUTO, VAUTO)" onmouseout="return nd()" name="firstname" type="text" class="textfield" id="firstname" maxlength="50"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Lastname:</td>
                <td align="left"><input onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_lastname');?>', HAUTO, VAUTO)" onmouseout="return nd()" type="text" value="" name="lastname" id="lastname" maxlength="50" class="textfield"></td>
              </tr>
              <tr>
                <td align="right">Company Name:</td>
                <td align="left"><input onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_company');?>', HAUTO, VAUTO)" onmouseout="return nd()" type="text" value="" name="companyname" id="companyname" maxlength="45" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Email:</td>
                <td align="left"><input onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_email');?>', HAUTO, VAUTO)" onmouseout="return nd()" type="text" value="" name="email" id="email" maxlength="50" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Account type:</td>
                <td align="left"><select onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_account_type');?>', HAUTO, VAUTO)" onmouseout="return nd()" name="account_type" id="account_type" class="textfield">
                    <option value="">Select Account type</option>
                    <option value="1">Prepaid</option>
                    <option value="0">Postpaid</option>
                  </select></td>
              </tr>
              <tr class="account_type_dependent" style="display:none;">
                <td align="right"><span class="required">*</span> Credit Limit:</td>
                <td align="left"><input name="creditlimit" type="text" class="textfield numeric" id="creditlimit" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_postpaid_credit_limit');?>', HAUTO, VAUTO)" onmouseout="return nd()" value="0" maxlength="10"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Bill Cycle:</td>
                <td align="left"><select onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_bill_cycle');?>', HAUTO, VAUTO)" onmouseout="return nd()" name="billing_cycle" id="billing_cycle" class="textfield">
                    <option value="">Select Bill Cycle</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="bi_weekly">Bi-Weekly</option>
                    <option value="monthly">Monthly</option>
                  </select></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Concurrent Calls:</td>
                <td align="left"><input name="maxcalls" type="text" class="textfield numeric" id="maxcalls" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_concurrent_calls');?>', HAUTO, VAUTO)" onmouseout="return nd()" maxlength="11"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Address:</td>
                <td align="left"><input type="text" value="" name="address" id="address" maxlength="150" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> City:</td>
                <td align="left"><input type="text" value="" name="city" id="city" maxlength="20" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> State:</td>
                <td align="left"><input type="text" value="" name="state" id="state" maxlength="45" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Zipcode:</td>
                <td align="left"><input type="text" value="" name="zipcode" id="zipcode" maxlength="10" class="textfield"></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Country:</td>
                <td align="left"><select class="textfield" name="country" id="country">
                    <?php echo all_countries();?>
                  </select></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Phone:</td>
                <td align="left"><input type="text" style="width:55px" value="" name="prefix" id="prefix" maxlength="10" class="textfield" readonly >
                  <input name="phone" type="text" class="textfield numeric" id="phone" style="width:119px" maxlength="41"></td>
              </tr>
              <tr>
                <td align="right">Timezone:</td>
                <td align="left"><select onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_timezone');?>', HAUTO, VAUTO)" onmouseout="return nd()" class="textfield" name="timezone" id="timezone">
                    <?php echo all_timezones();?>
                  </select></td>
              </tr>
              <tr>
                <td align="right"><span class="required">*</span> Rate Rate Group:</td>
                <td align="left"><select onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_rate_group');?>', HAUTO, VAUTO)" onmouseout="return nd()" id="group" name="group" class="textfield">
                    <?php echo show_group_select_box_valid_invalid();?>
                  </select></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_attach_cdr_with_email');?>', HAUTO, VAUTO)" onmouseout="return nd()"><input type="checkbox" id="cdr_check" value="1" name="cdr_check">
                  &nbsp;Attach CDR With Email</td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_billing_same_as_email');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input type="checkbox" id="same_check" value="1" name="same_check">
                  &nbsp;Billing email same as contact email</td>
              </tr>
              <tr id="cdr_tr">
                <td align="center" colspan="2"><table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                    <tbody>
                      <tr>
                        <td align="right" width="45%"><span class="required">*</span> Billing Email:</td>
                        <td align="left" width="55%"><input type="text" value="" name="cdr_email" id="cdr_email" maxlength="50" class="textfield"></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_allow_cp_access');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input type="checkbox" id="access_chk" value="Y" name="access_chk">
                  &nbsp;Allow User Control Panel Access</td>
              </tr>
              <tr id="userpass" style="display:none">
                <td align="center" colspan="2"><table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                    <tbody>
                      <tr>
                        <td align="right" width="45%"><span class="required">*</span> Username:</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_username_for_cp');?>', HAUTO, VAUTO)" onmouseout="return nd()" width="55%"><input name="username" type="text" class="textfield" id="username" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="required">*</span> Password:</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_password_for_cp');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input name="password" type="password" class="textfield" id="password" value="" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="required">*</span>Confirm Password:</td>
                        <td align="left"><input type="password" value="" name="confirmpassword" id="confirmpassword" class="textfield"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="required">*</span>Total # of ACL Nodes:</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_number_of_acl_nodes');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input name="tot_acl_nodes" type="text" class="textfield numeric" id="tot_acl_nodes" value="5" maxlength="3"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="required">*</span>Total # of SIP Accounts:</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_number_of_sip_accounts');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input name="tot_sip_acc" type="text" class="textfield numeric" id="tot_sip_acc" value="5" maxlength="3"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="required">*</span>SIP IP (Select All That Apply):</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_profile_ip_address');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><select  name="sip_ip[]"  id="sip_ip" class="textfield" multiple="multiple" size="5">
                            <?php echo get_all_sip_ips(); ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="left" onmouseover="return overlib('<?php echo $this->lang->line('add_customer_view_email_information_customer');?>', HAUTO, VAUTO)" onmouseout="return nd()" ><input type="checkbox" value="Y" name="email_check" id="email_check">
                          &nbsp;Email information to customer</td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="right" colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" colspan="2"><input border="0" id="submitAddCustForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
              </tr>
            </tbody>
          </table>
        </form></td>
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
  </tbody>
</table>
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
        var creditlimit = $('#creditlimit').val();
        var accounttype = $('#account_type').val();
        var group = $('#group').val();
        var billing_cycle = $('#billing_cycle').val();
        var cdr_email = $('#cdr_email').val();
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
            text += "Password and Confirm Password do not match<br/>";
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
					url: base_url+"customers/insert_new_customer",
					data: form,
                    success: function(html){
                        if(html == 'email_in_use')
                        {
                            $('.success').hide();
                            $('.error').html("Customer email already in use.");
                            $('.error').fadeOut();
                            $('.error').fadeIn();
                            document.getElementById('err_div').scrollIntoView();
                            $.unblockUI();
                        }
                        else if(html == 'group_invalid')
                        {
                            $('.success').hide();
                            $('.error').html("The selected group is in-valid.");
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
                            $('.success').html("Customer added successfully.");
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
            url: base_url+"customers/get_country_prefix",
            data: 'id='+val,
            success: function(html){
                $('#prefix').val('+'+html+'');
            }
       });
    });
    
    $(document).ready(function() {
        $('#cdr_check').attr('checked', false);
        $('#access_chk').attr('checked', false);
    });
    
    $('.numeric').numeric();
</script> 
