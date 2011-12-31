<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0" id="main-sofia">
        <tbody><tr>
            <td>
                <form action="" method="post" id="edit-gateways-form">
                    <input type="hidden" name="hidden_profile_id" value="<?php echo $sofia_id;?>"/>
                    <input type="hidden" name="hidden_gateway_name" value="<?php echo $gateway_name;?>"/>
                    <input type='hidden' name='form_fields_count' id='form_fields_count' value='<?php echo $gateways->num_rows();?>' />

                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="edit-tbl">
                    <thead>
                        <tr>
                            <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                                <div class="left">Edit <?php echo $gateway_name;?> Configuration</div> 
                                
                                <div class="right main_head_btns">
                                    <a href="<?php echo base_url();?>freeswitch/edit_gateway/<?php echo $sofia_id;?>/<?php echo $gateway_name;?>" >EDIT</a>
                                    &nbsp; | &nbsp;
                                    <a href="<?php echo base_url();?>freeswitch/gateway_detail/<?php echo $sofia_id;?>/<?php echo $gateway_name;?>">BACK</a>
                                    
                                </div>
                                
                            </th>
                        </tr>
                        
                        <tr class="bottom_link">
                            <th height="20" align="center">Parameter</th>
                            <th align="center">Values</th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <td align="center"><input class="button" type="submit" value="Submit" /></td>
                            <td align="center"><input class="button add-another-field" type="button" value="Add Another Field" /></td>
                        </tr>
                    </tfoot>
                    
                    
                    <tbody id="ajax-main-content">
                    <?php if($gateways->num_rows() > 0) {?>
                        
                        <?php 
                            foreach ($gateways->result() as $row): 
                            
                            $required = '';
                            if($row->gateway_param == 'username' || $row->gateway_param == 'password' || $row->gateway_param == 'proxy' || $row->gateway_param == 'register' || $row->gateway_param == 'channels')
                            {
                                $required = 'requiredd';
                            }
                            else
                            {
                                $required = '';
                            } 
                        ?>
                            <tr class="main_text" height="30px">
                                <td align="center">
                                    <select name="gateway_param[]" class="textfield gateway_param_box">
                                        <option value="<?php echo $row->gateway_param;?>"><?php echo $row->gateway_param;?></option>
                                    </select>
                                </td>
                                <td align="center">
                                    <input type="text" name="gateway_value[]" id="gateway_value_<?php echo $row->gateway_param;?>" class="textfield <?php echo $required;?>" value="<?php echo $row->gateway_value;?>"/>
                                </td>
                               
                            </tr>
                        <?php endforeach; ?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="2" style="color:red;">No Results Found (However, You Can Add New Row)</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </form>
            </td>
        </tr>
    </tbody></table>
    

   <script type="text/javascript">
$('.add-another-field').click(function(){
    
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
        
        var channels = $('#gateway_value_channels').val();
        
        var error = 0;
        var channels_error = 0;
        
        $("#edit-tbl").find('.requiredd').each(function(i){
            if($(this).val() == '')
            {
                error = 1;
            }
        });
        
        if(channels != '')
        {
            if(channels == 0 || channels > 10000)
            {
                channels_error = 1;
            }
        }
        
		if(error == 1)
		{
			$.unblockUI();
            alert("Username, Password, Proxy, Register, Channels are required fields.");
			return false;
		}
        
        if(channels_error == 1)
		{
			$.unblockUI();
            alert("Channels value should be between 1 to 10000");
			return false;
		}
        
        var form = $('#edit-gateways-form').serialize();
		$.ajax({
                type: "POST",
                url: base_url+"freeswitch/edit_gateway_db_add_row",
                data: form,
                success: function(html){
                    if(html != 'end')
                    {
                        $('#edit-tbl tbody').append(html); 
                        $('#form_fields_count').val('1');
                        $.unblockUI();
                    }
                    else
                    {
                        $.unblockUI();
                        alert('No More Parameters Are Available To Add');
                    }
                }
			});
	});

			$('#edit-gateways-form').submit(function(){
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
                        
                var channels = $('#gateway_value_channels').val();
        
        var error = 0;
        var channels_error = 0;
        
        $("#edit-tbl").find('.requiredd').each(function(i){
            if($(this).val() == '')
            {
                error = 1;
            }
        });
        
        if(channels != '')
        {
            if(channels == 0 || channels > 10000)
            {
                channels_error = 1;
            }
        }
        
		if(error == 1)
		{
			$.unblockUI();
            alert("Username, Password, Proxy, Register, Channels are required fields.");
			return false;
		}
        
        if(channels_error == 1)
		{
			$.unblockUI();
            alert("Channels value should be between 1 to 10000");
			return false;
		}      
                
                        var form = $('#edit-gateways-form').serialize();

                        $.ajax({
                            type: "POST",
                                url: base_url+"freeswitch/edit_gateway_db_form",
                                data: form,
                                success: function(html){
                                    $.unblockUI();
                                    alert('Gateways Configuration Updated Successfully');
                                }
                            });
					return false;
					});
                    
                    $('.gateway_param_box').live('change', function(){
                        if($(this).val() == 'caller-id-in-from')
                        {
                           $(this).parent().parent().find('.gateway_value').val('true');
                        }
                        else
                        {
                             $(this).parent().parent().find('.gateway_value').val('');
                        }
                    });
					</script>
