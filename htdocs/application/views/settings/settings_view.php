    <link type="text/css" href="<?php echo base_url();?>assets/css/ui.multiselect.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/ui.multiselect.js"></script>
	<script type="text/javascript">
		$(function(){
			$(".multiselect").multiselect({sortable: false, searchable: false});
		});
	</script>
    
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            GENERAL SETTINGS            </td>
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
        <form enctype="multipart/form-data"  method="post" action="<?php echo base_url();?>settings/update_settings" name="updateSettings" id="updateSettings">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <tbody>
                            <tr>
                                <td align="right" width="45%"><span class="required">*</span> Company Name:</td>
                                <td align="left" width="55%"><input type="text" value="<?php echo $this->settings_model->settings_any_cell('company_name');?>" name="company_name" id="company_name" class="textfield"></td>
                            </tr>
                            
                            <tr>
                                <td align="right"><span class="required">*</span>Company Logo:</td>
                                <td align="left"><input type="file"  name="userfile" id="userfile" class="textfield"></td>
                                <input type="hidden" id="hidden_logo" value="<?php echo $this->settings_model->settings_any_cell('logo');?>"/>
                            </tr>
                            <tr>
                                <td align="right"></td>
                                <td align="left"><img src="<?php echo base_url();?>media/images/<?php echo $this->settings_model->settings_any_cell('logo');?>" height="30px"/> </td>
                            </tr>
                            
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitupdateSettingsForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
                </tr>
            </tbody></table>
        </form>
    </td>
</tr>
<tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            INVOICE SETTINGS            </td>
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
<tr><td background="http://localhost/vb2/htdocs/assets/images/line.png" height="7" colspan="3"></td></tr>
<tr>
            <td height="10"></td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="error" id="err_div_inv" <?php if($this->session->flashdata('error_inv') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('error_inv');?></div></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div_inv" <?php if($this->session->flashdata('success_inv') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success_inv');?></div></td>
        </tr>
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="<?php echo base_url();?>settings/update_inv_settings" name="updateInvSettings" id="updateInvSettings">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <tbody>
                            <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left"><input type="checkbox" id="same_logo" value="1" name="same_logo" <?php if($this->settings_model->settings_any_cell('company_logo_as_invoice_logo') == '1'){ echo "checked"; }?>/>&nbsp;Use Site Logo For Invoices</td>
                            </tr>
                            
                            
                            <tr class="inv_logo_tr" <?php if($this->settings_model->settings_any_cell('company_logo_as_invoice_logo') == '1'){ echo 'style="display:none;"'; }?>>
                                <td align="right"><span class="required">*</span>Invoice Logo:</td>
                                <td align="left"><input type="file"  name="userfile" id="userfile_inv" class="textfield"></td>
                                <input type="hidden" id="hidden_inv_logo" value="<?php echo $this->settings_model->settings_any_cell('invoice_logo');?>"/>
                            </tr>
                            
                            <tr class="inv_logo_tr" <?php if($this->settings_model->settings_any_cell('company_logo_as_invoice_logo') == '1'){ echo 'style="display:none;"'; }?>>
                                <td align="right"></td>
                                
                                <?php if($this->settings_model->settings_any_cell('company_logo_as_invoice_logo') == '0'){?>
                                <td align="left"><img src="<?php echo base_url();?>media/images/<?php echo $this->settings_model->settings_any_cell('invoice_logo');?>" height="30px"/> </td>
                                <?php  } else {?>
                                <td align="left"></td>
                                <?php } ?>
                            </tr>
                            
                            <tr>
                                <td align="right" width="45%">Invoice Terms & Conditions:</td>
                                <td align="left" width="55%">
                                <textarea name="inv_footer" id="inv_footer" rows="10" cols="50"><?php echo $this->settings_model->settings_any_cell('invoice_terms');?></textarea>
                            </tr>
                            
                            <tr>
                                <td align="right" width="45%">CDR Invoice Fields To Show (Optional):</td>
                                <td align="left" width="55%">
                                <select size="5" multiple="multiple" name="extra_cdr[]" id="extra_cdr" class="field multiselect">
                                
                                <?php 
                                    $extra_cdr = $this->settings_model->settings_any_cell('optional_cdr_fields_include');
                                    $data_array = explode(',', $extra_cdr);
                                ?>
                                    <option value="caller_id_number" <?php if(in_array('caller_id_number',$data_array)) { echo "selected";} ?>> Caller ID Num</option>
                                    <option value="duration" <?php if(in_array('duration',$data_array)) { echo "selected";} ?>>Duration</option>
                                    <option value="network_addr" <?php if(in_array('network_addr',$data_array)) { echo "selected";} ?>>Network Address</option>
                                    <option value="username" <?php if(in_array('username',$data_array)) { echo "selected";} ?>>Username</option>
                                    <option value="sip_user_agent" <?php if(in_array('sip_user_agent',$data_array)) { echo "selected";} ?>>SIP User Agent</option>
                                    <option value="ani" <?php if(in_array('ani',$data_array)) { echo "selected";} ?>>ANI</option>
                                    <option value="cidr" <?php if(in_array('cidr',$data_array)) { echo "selected";} ?>>CIDR</option>
                                    <option value="sell_rate" <?php if(in_array('sell_rate',$data_array)) { echo "selected";} ?>>Sell Rate</option>
                                    <option value="cost_rate" <?php if(in_array('cost_rate',$data_array)) { echo "selected";} ?>>Cost Rate</option>
                                    <option value="buy_initblock" <?php if(in_array('buy_initblock',$data_array)) { echo "selected";} ?>>Buy Init Block</option>
                                    <option value="sell_initblock" <?php if(in_array('sell_initblock',$data_array)) { echo "selected";} ?>>Sell Init Block</option>
                                    <option value="total_buy_cost" <?php if(in_array('total_buy_cost',$data_array)) { echo "selected";} ?>>Total Buy Cost</option>
                                    <option value="gateway" <?php if(in_array('gateway',$data_array)) { echo "selected";} ?>>Gateway</option>
                                    <option value="total_failed_gateways" <?php if(in_array('total_failed_gateways',$data_array)) { echo "selected";} ?>>Total Failed Gateways</option>
                                </select>
                            </tr>
                            
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitupdateInvSettingsForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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
    
    /*****************GENERAL SETTINGS VALIDATION ********************************/
    $('#updateSettings').submit(function(){
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
                
        var company_name = $('#company_name').val();
        var userfile = $('#userfile').val();
        var hidden_logo = $('#hidden_logo').val();
        
        var required_error = 0;
        
        if(company_name == '')
        {
            required_error = 1;
        }
        
        if(hidden_logo == '')
        {
            if(userfile == '')
            {
                required_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(text != '')
        {
            $('.success').hide();
            $('#err_div').html(text);
            $('.error').fadeOut();
            $('#err_div').fadeIn();
            document.getElementById('err_div').scrollIntoView();
            $.unblockUI();
            return false;
        }
        else
        {
           return true;
        }
        return false;
    });
    
    /*****************INVOICE SETTINGS VALIDATION ********************************/
    $('#updateInvSettings').submit(function(){
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
                
       // var inv_footer = $('#inv_footer').val();
        var userfile_inv = $('#userfile_inv').val();
        var hidden_inv_logo = $('#hidden_inv_logo').val();
        var required_error = 0;
        
        if(!$('#same_logo').is(':checked'))
        {
            if(hidden_inv_logo == '')
            {
                if(userfile_inv == '')
                {
                    required_error = 1;
                }
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(text != '')
        {
            $('.success').hide();
            $('#err_div_inv').html(text);
            $('.error').fadeOut();
            $('#err_div_inv').fadeIn();
            document.getElementById('err_div_inv').scrollIntoView();
            $.unblockUI();
            return false;
        }
        else
        {
           return true;
        }
        return false;
    });
    
    $('#same_logo').click(function(){
        if ($(this).is(':checked'))
        {
            $('.inv_logo_tr').hide();
        }
        else
        {
            $('.inv_logo_tr').show();
        }
    });
    
</script>