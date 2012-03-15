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
    $row = $group->row();
?>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Assigned Group Details            </td>
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
    <td align="center" height="20" colspan="3">
        <div class="form-container">
       
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                <input type="hidden" name="rate_group_id" id="rate_group_id" value="<?php echo $rate_group_id; ?>" />
                <tbody>
                
                <tr>
                    <td align="left" width="10%">Rate Group Name:</td>
                    <td align="left">"<?php echo $row->group_name;?>"</td>
                </tr>
                
                <tr>
                    <td align="left" width="100%" colspan="2" style="font-size:14px; text-decoration:underline;padding-top:30px;padding-bottom:20px;">Group Associated Rates:</td>
                </tr>
            </tbody></table>
            
            <table cellspacing="0" cellpadding="0" border="0" width="95%" class="search_col">
                
                <thead>
                    <tr class="bottom_link">
                        <td width="8%" align="center">Country Code</td>
                        <td width="8%" align="center">Buy Rate</td>
                        <td width="8%" align="center">Buy Init Block</td>
                        <td width="8%" align="center">Enabled</td>
                        <td width="8%" align="center">Validity</td>
                        <td width="8%" align="center">Options</td>
                    </tr>
                    <tr><td colspan="7" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                </thead>
                
                <tbody id="dynamic">
                    <?php if($group_rates->num_rows() > 0) {?>
                    <?php foreach($group_rates->result() as $rowRate){ ?>
                    
                    <?php 
                        $check_carrier_exists = carrier_exists($rowRate->carrier_id);
                        $bg = '';
                        if($check_carrier_exists == 0)
                        {
                            $bg = 'style="background:#F28585;"';
                        }
                    ?>
                        <tr class="main_text" <?php echo $bg;?>>
                            <td align="center" class="buy_digit"><a href="<?php echo base_url();?>groups/update_rate/<?php echo $rowRate->id;?>/<?php echo $rate_group_id;?>">
							<?php echo $rowRate->digits; ?></a></td>
                            <td align="center" class="buy_rate"><?php echo $rowRate->sell_rate; ?></td>
                            <td align="center" class="buy_init"><?php echo $rowRate->sell_initblock; ?></td>
                            
                            <td align="center"><?php if($rowRate->enabled == 1){ echo 'Enabled';} else {echo "Disabled";}?></td>
                            
                            <?php if($check_carrier_exists != 0){?>
                                <td align="center">Valid</td>
                            <?php } else { ?>
                                <td align="center">Invalid (Carrier Missing)</td>
                            <?php } ?>
                            
                            
                            <td align="center"><a href="#" class="define_new_rate" id="<?php echo $rowRate->id; ?>">Define New Rate</a></td>
                            
                        </tr>
                        <tr style="height:5px;"><td colspan="7" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                    <?php } ?>
                    <?php } else { ?>
                        
                        <tr class="main_text"><td align="center" colspan="7">No Records Found</td></tr>
                    <?php } ?>
                </tbody>
            </table>
            
        
        </div>
    </td>
</tr>

<tr>
            <td colspan="3" align="right"> 
                <table>
                    <tr>
                        <td id="paginationWKTOP" colspan="3" style="margin-right:25px">
                            <?php echo $this->pagination->create_links();?>
                        </td>
                    </tr>
                </table>
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
    
    <style>
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
        select { margin-bottom:12px; width:100%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
	</style>
    
    <div id="dialog-form" title="Define New Rate" style="display:none;">
        <fieldset>
            <label id="code_lbl"></label>
            <label id="buy_rate_lbl"></label>
            <label id="buy_init_lbl"></label>
        </fieldset>
        
        <div class="error" id="err_div" style="display:none;"></div>
        <div class="success" id="success_div" style="display:none;"></div>

        <form method="post" action="" id="addRate">
        <fieldset>
            <label>Group <span class="required">*</span></label>
            <select name="rate_group" id="rate_group" class="text ui-widget-content ui-corner-all">
                <?php echo show_group_select_box_reseller();?>
            </select>
            
            <label>Sell Rate <span class="required">*</span></label>
            <input type="text" name="sell_rate" id="sell_rate" value="" class="text ui-widget-content ui-corner-all numeric" />
            
            <label>Sell Init Block <span class="required">*</span></label>
            <input type="text" name="sell_init" id="sell_init" value="" class="text ui-widget-content ui-corner-all numeric" />
            
            <input border="0" id="submitaddRateForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png">
        </fieldset>
        
            <input type="hidden" id="parent_rate_id" name="parent_rate_id" />
            <input type="hidden" id="parent_sell_rate" />
            <input type="hidden" id="parent_sell_init" />
            
        </form>
    </div>
    
    <script type="text/javascript">
        $('.define_new_rate').click(function(){
            $('.error').hide();
            $('.success').hide();
            
			var rate_id   = $(this).attr('id');
			var buy_rate  = $(this).parent().parent().find('.buy_rate').html();
			var buy_init  = $(this).parent().parent().find('.buy_init').html();
			var buy_digit = $(this).parent().parent().find('.buy_digit').html();
            
            $('#code_lbl').html('Country Code: <b>'+buy_digit+'</b>');
            $('#buy_rate_lbl').html('Buy Rate: <b>'+buy_rate+'</b>');
            $('#buy_init_lbl').html('Buy Init Block: <b>'+buy_init+'</b>');
            
            $('#parent_rate_id').val(rate_id);
            $('#parent_sell_rate').val(buy_rate);
            $('#parent_sell_init').val(buy_init);
            
            $('#sell_rate').val(buy_rate);
            $('#sell_init').val(buy_init);
            
				$( "#dialog-form" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
                return false;
			});
            
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
                            
                    var sell_rate           = $('#sell_rate').val();
                    var sell_init           = $('#sell_init').val();
                    var rate_group          = $('#rate_group').val();
                    var parent_rate_id       = $('#parent_rate_id').val();
                    var reseller_buy_rate   = $('#parent_sell_rate').val();
                    var reseller_buy_init   = $('#parent_sell_init').val();
                    
                    
                    var required_error = 0;
                    var rate_error = 0;
                    var init_error = 0;
                    //common required fields check
                    if(sell_rate == '' || sell_init == '' || rate_group == '')
                    {
                        required_error = 1;
                    }
                    
                    if(sell_rate != '')
                    {
                        if(sell_rate < reseller_buy_rate)
                        {
                            rate_error = 1;
                        }
                    }
                    
                    if(sell_init != '')
                    {
                        if(sell_init < reseller_buy_init)
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
                        text += "Sell Rate Cannot Be Less Than Buy Rate<br/>";
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
                        $.unblockUI();
                        return false;
                    }
                    else
                    {
                       var form = $('#addRate').serialize();
                        $.ajax({
                                type: "POST",
                                url: base_url+"reseller/groups/insert_new_rate",
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
                                            $('.success').html("Rate added successfully.");
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
            
            $('.numeric').numeric({allow:'.'});
    </script>