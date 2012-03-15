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
      <td width="825" class="heading"> New Carrier </td>
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
      <td align="center" height="20" colspan="3"><div class="form-container">
          <form enctype="multipart/form-data"  method="post" action="" name="addCarrier" id="addCarrier">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <tbody>
                <tr>
                  <td align="left" width="10%"><span class="required">*</span> Carrier Name:</td>
                  <td align="left"><input type="text" value="" name="carriername" id="carriername" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                  <td align="left" width="100%" colspan="2" style="font-size:14px; text-decoration:underline;padding-top:30px;padding-bottom:20px;">Carrier Gateway Details:</td>
                </tr>
              </tbody>
            </table>
            <select  id="hidden_box_for_ajax" style="display:none;">
              <?php echo all_gateways_with_use_count();?>
            </select>
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <thead>
                <tr>
                  <th align="left">Gateway</th>
                  <th align="left">Add Gateway Prefix</th>
                  <th align="left">Add Gateway Suffix</th>
                  <th align="left">Codec</th>
                </tr>
              </thead>
              <tbody id="dynamic">
                <tr>
                  <td align="left"><select name="prefix[]" id="prefix" class="textfield parent_prefix">
                      <?php echo all_gateways_with_use_count();?>
                    </select>
                    <span class="required">*</span></td>
                  <td align="left"><input type="text" value="" onkeypress='validate(event)' name="pre[]" id="pre" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="" onkeypress='validate(event)' name="suffix[]" id="suffix" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="" name="codec[]" id="codec" maxlength="50" class="textfield"></td>
                  <td align="left"><img src="<?php echo base_url();?>assets/images/plus.gif" class="add_field" /></td>
                </tr>
              </tbody>
            </table>
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <tbody>
                <tr>
                  <td align="center" colspan="2"><input border="0" id="submitaddCarrierForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div></td>
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

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

    var prev_val = '';
    var main_count = 0;
    $('.add_field').live('click', function(){
        
        $('.parent_prefix').each(function(){
            $("#hidden_box_for_ajax option[value='"+$(this).val()+"']").remove();
        });
        
        main_count = main_count + 1;
        var drop_down_contents = '';
        
        $("#hidden_box_for_ajax option").each(function()
        {
            drop_down_contents += '<option value="'+$(this).val()+'">'+$(this).text()+'</option>'
        });

        if(drop_down_contents != '')
        {
            $("#dynamic").append('<tr class="optional" id="'+main_count+'"><td align="left"><select name="prefix[]" id="prefix_'+main_count+'" class="textfield parent_prefix">'+drop_down_contents+'</select></td><td align="left"><input type="text" value="" name="pre[]" id="pre_'+main_count+'" maxlength="50" class="textfield"></td><td align="left"><input type="text" value="" name="suffix[]" id="suffix_'+main_count+'" maxlength="50" class="textfield"></td><td align="left"><input type="text" value="" name="codec[]" id="codec_'+main_count+'" maxlength="50" class="textfield"></td><td align="left"><img src="<?php echo base_url();?>assets/images/button_cancel.png" class="remove_field" /></td></tr>');
        }
        else
        {
            $('.success').hide();
            $('.error').html("ERROR: Cannot add new row because no more gateways available.");
            $('.error').fadeOut();
            $('.error').fadeIn();
            document.getElementById('err_div').scrollIntoView();
            $.unblockUI();
            return false;
        }       
    });
    
    $('.remove_field').live('click', function(){
        $('.error').fadeOut();
        
        var prefix_val = '';
        var prefix_text = '';
        var data = '';
        
        prefix_val = $(this).parent().parent().find('.parent_prefix').val();
        prefix_text = $(this).parent().parent().find(".parent_prefix option[value='"+prefix_val+"']").text();
        data = '<option value="'+prefix_val+'">'+prefix_text+'</option>';
        
        $("#hidden_box_for_ajax").append(data);

        $(this).parent().parent().remove();
    });
    
    $('.parent_prefix').live('change', function(){
        var curr_val = $(this).val();
        var curr = $(this);
        $('.parent_prefix').not(curr).each(function(){
            if($(this).val() == curr_val)
            {
                alert("You have already selected that value. Duplicate values are not allowed");
                curr.val(prev_val);
                return false;
            }
        });
    });
    
    $('.parent_prefix').live('click', function(){
        prev_val = $(this).val();
    });
    
    $('#addCarrier').submit(function(){
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
                
				var carriername            = $('#carriername').val();
				var prefix                 = $('#prefix').val();
				var name_error             = 0;
				var gateway_required_error = 0;
				var optional_error         = 0;
				var text                   = '';
       
       if(carriername == '')
       {
            name_error = 1;
       }
       if(prefix == '' || prefix == null)
       {
            gateway_required_error = 1;
       }
       
       $('.optional').each(function(){
            var id = $(this).attr('id');
            
            if($('#prefix_'+id+'').val() == '')
            {
                optional_error = 1;
                return false;
            }
        });
       
       if(name_error == 1)
       {
            text += "Please enter carrier name.";
       }
       
       if(gateway_required_error == 1)
       {
            text += "<br/>Please Select Gateway in the first row.";
       }
       
       if(optional_error == 1)
       {
            text += "<br/>Please select gateway in the optional rows or either remove them.";
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
           var form = $('#addCarrier').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"carriers/insert_new_carrier",
					data: form,
                    success: function(html){
                            $('.error').hide();
                            $('.success').html("Carrier added successfully.");
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
</script>