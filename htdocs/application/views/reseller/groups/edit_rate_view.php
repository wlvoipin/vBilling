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
<?php 
    $row = $rate->row();
?>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Update Rate            </td>
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
        <form enctype="multipart/form-data"  method="post" action="" name="addRate" id="addRate">
          <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                <input type="hidden" name="rate_id" id="rate_id" value="<?php echo $rate_id;?>"/>
                <input type="hidden" name="rate_group_id" id="rate_group_id" value="<?php echo $rate_group_id;?>"/>
                <tbody>
                
                
                <tr>
                    <td align="right" width="45%">Country Code:</td>
                    <td align="left" width="55%"><?php echo $row->digits;?></td>
                    <input type="hidden" name="old_digits" value="<?php echo $row->digits;?>" />
                </tr>
                <tr>
                    <td align="right">Buy Rate:</td>
                    <td align="left"><?php echo $row->cost_rate;?></td>
                </tr>
                <tr>
                    <td align="right">Buy Init Block:</td>
                    <td align="left"><?php echo $row->buy_initblock;?></td>
                </tr>
                
                <input type="hidden" id="hidden_buy_rate" value="<?php echo $row->cost_rate;?>" />
                <input type="hidden" id="hidden_buy_init" value="<?php echo $row->buy_initblock;?>"/>
                
                <tr>
                    <td align="right"><span class="required">*</span> Sell Rate:</td>
                    <td align="left"><input type="text" value="<?php echo $row->sell_rate;?>" name="rate" id="rate" maxlength="50" class="textfield"></td>
                    <input type="hidden" name="old_rate" value="<?php echo $row->sell_rate;?>" />
                </tr>
                
                <tr>
                    <td align="right"><span class="required">*</span> Sell Block:</td>
                    <td align="left"><input type="text" value="<?php echo $row->sell_initblock;?>" name="sellblock" id="sellblock" maxlength="50" class="textfield"></td>
                </tr>
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
            <tr>
                    <td align="center" colspan="2"><input border="0" id="submitaddRateForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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
    $('#addRate').submit(function(){
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
        
        var rate = $('#rate').val();
        var sellblock = $('#sellblock').val();
        
        var hidden_buy_rate = $('#hidden_buy_rate').val();
        var hidden_buy_init = $('#hidden_buy_init').val();
        
        var required_error = 0;
        var rate_error = 0;
        var init_error = 0;
        
        //common required fields check
        if(rate == '' || sellblock == '')
        {
            required_error = 1;
        }
        
        if(rate != '')
        {
            if(rate < hidden_buy_rate)
            {
                rate_error = 1;
            }
        }
        
        if(sellblock != '')
        {
            if(sellblock < hidden_buy_init)
            {
                init_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(rate_error == 1)
        {
            text += "Sell Rate Cannot Be Less Than Cost Rate<br/>";
        }
        
        if(init_error == 1)
        {
            text += "Sell Init Block Cannot Be Less Than Buy Init Block<br/>";
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
           var form = $('#addRate').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"reseller/groups/edit_rate_db",
					data: form,
                    success: function(html){
                            if(html == 'duplicate')
                            {
                                $('.success').hide();
                                $('.error').html('You cannot add rate with same digits and sell rate. An entry already exists');
                                $('.error').fadeOut();
                                $('.error').fadeIn();
                                document.getElementById('err_div').scrollIntoView();
                                $.unblockUI();
                            }
                            else
                            {
                                $('.error').hide();
                                $('.success').html("Rate updated successfully.");
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
    
   $('.datepicker').datetimepicker({
        showSecond: true,
        showMillisec: false,
        timeFormat: 'hh:mm:ss',
        dateFormat: 'yy-mm-dd'
    });
    
</script>
