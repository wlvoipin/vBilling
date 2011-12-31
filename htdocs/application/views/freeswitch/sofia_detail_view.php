<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                            <div class="left">GATEWAYS</div> 
                            
                            <div class="right main_head_btns">
                                <a href="<?php echo base_url();?>freeswitch/new_gateway/<?php echo $sofia_id;?>">NEW GATEWAY</a>
                            </div>
                            
                        </th>
                    </tr>
                    
                    <tr class="bottom_link">
                        <th height="20" width="10%" align="center">Gateway Name</th>
                        <th width="20%" align="center">Details</th>
                        <th width="60%" align="left">Options</th>
                    </tr>
                    </thead>
                    
                    
                    
                    <tbody>
                    <?php if($gateways->num_rows() > 0) {?>
                        
                        <?php foreach ($gateways->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center"><?php echo $row->gateway_name;?></td>
                                <td align="center"><a href="<?php echo base_url();?>freeswitch/gateway_detail/<?php echo $sofia_id;?>/<?php echo $row->gateway_name;?>">View Details</a></td>
                                
                                <td align="left"><a href="#" id="<?php echo $sofia_id.'|'.$row->gateway_name;?>" class="delete_gateway"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                
                            </tr>
                        <?php endforeach;?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody></table>
    
    <br/><br/>
    <!--*****************************SETTINGS DETAILS *************************************-->
    <table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0" id="main-sofia">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                                <div class="left">SETTINGS</div> 
                                
                                <div class="right main_head_btns">
                                    <a href="#" class="back-to-sofia-set" id="<?php echo $sofia_id;?>">CONTENTS</a> |
                                    <a href="#" class="edit-sofia-set" id="<?php echo $sofia_id;?>">EDIT</a>
                                </div>
                                
                                <div class="right" style="margin-top:5px; margin-right:10px;">
                                    <form method="post" action="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">
                                        <select name="sofia_sett_param_type" id="sofia_sett_param_type" class="textfield" onchange="this.form.submit();">
                                            <option value="" selected>All Types</option>
                                            <?php echo $this->freeswitch_model->getSofiaSettingsAllTypes($type);?>
                                        </select>
                                    </form>
                                </div>
                            </th>
                        </tr>
                        
                        <tr class="bottom_link">
                            <th height="20" align="left">Parameter Name</th>
                            <th align="left">Parameter Value</th>
                            <th align="left">Options</th>
                        </tr>
                    </thead>
                    
                    <tbody id="ajax-main-content">
                    <?php if($settings->num_rows() > 0) {?>
                        
                        <?php 
                            $count = 1;
                            $bg = '';
                            
                            foreach ($settings->result() as $rowSet): 
                            
                            if($count % 2)
                            {
                                $bg = "bgcolor='#E6E5E5'";
                            }
                            else
                            {
                                $bg = "";
                            }
                        ?>
                            <tr class="main_text" height="20px" <?php echo $bg; ?>>
                                <td align="left"><?php echo $rowSet->param_name;?></td>
                                <td align="left"><?php echo $rowSet->param_value;?></td>
                                
                                <?php if($rowSet->param_name != 'sip-ip' && $rowSet->param_name != 'sip-port' ) {?>
                                <td align="left"><a href="#" id="<?php echo $rowSet->id;?>" class="delete_setting"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                <?php } else { echo "<td>&nbsp;</td>";} ?>
                            </tr>
                        <?php 
                            $count++;
                            endforeach;
                        ?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody></table>
    
    <!--********************EDIT SOFIA SETTINGS ***************************-->
    <table style="border: 1px groove;display:none;" width="100%" cellpadding="0" cellspacing="0" id="edit-set-sofia">
        <tbody><tr>
            <td>
                
                <form action="" method="post" id="edit-settings-form">
                        <input type="hidden" name="hidden_profile_id" id="hidden_profile_id" value="<?php echo $sofia_id;?>"/>
                        <input type="hidden" name="setting_type" id="setting_type" value="<?php echo $type;?>"/>
                        <input type="hidden" name="form_fields_count" id="form_fields_count" value="<?php echo $settings->num_rows();?>" />
                        
                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="edit-tbl">
                    
                    <thead>
                    <tr>
                        <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                            <div class="left">SETTINGS</div> 
                            
                            <div class="right main_head_btns">
                                <a href="#" class="back-to-sofia-set" id="<?php echo $sofia_id;?>">CONTENTS</a> |
                                <a href="#" class="edit-sofia-set" id="<?php echo $sofia_id;?>">EDIT</a>
                            </div>
                            
                            <div class="right" style="margin-top:5px; margin-right:10px;">
                                <form method="post" action="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">
                                    <select name="sofia_sett_param_type" id="sofia_sett_param_type" class="textfield" onchange="this.form.submit();">
                                        <option value="" selected>All Types</option>
                                        <?php echo $this->freeswitch_model->getSofiaSettingsAllTypes($type);?>
                                    </select>
                                </form>
                            </div>
                        </th>
                    </tr>
                    
                    <tr class="bottom_link">
                        <th height="20" align="left">Parameter Name</td>
                        <th align="left">Parameter Value</td>
                    </tr>
                    </thead>
                    
                    
                        
                        <tbody id="ajax-sett-content">
                        </tbody>
                    
                    
                    <tfoot>
                        <tr>
                            <td><input class="button" type="submit" value="Submit" /></td>
                            <td><input class="button add-another-field" type="button" value="Add Another Field" /></td>
                        </tr>
                    </tfoot>
                    
                </table>
            </form>
            </td>
        </tr>
    </tbody></table>
    
    
    <div id="dialog-confirm-delete" title="Delete The Profile?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This Profile?</p>
    </div>

    <script type="text/javascript">
        
        $('.edit-sofia-set').live('click', function(){
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
                
            var id = $(this).attr('id');
            var type = $('#sofia_sett_param_type').val();
            var data  = 'id='+ id +'&type='+ type;
            $.ajax({
                    type: "POST",
                    url: base_url+"freeswitch/get_settings_edit_contents",
                    data: data,
                    success: function(html){
                        $('#main-sofia').hide();
                        $('#ajax-sett-content').html(html);
                        $('#edit-set-sofia').show();
                        $.unblockUI();
                    }
                });
            return false;
        });
        
        $('.add-another-field').live('click',function(){
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
            var type = $('#setting_type').val();
            var form = $('#edit-settings-form').serialize();
            var error = 0;
            $('.settings_value').each(function(){
                if($(this).val() == '')
                {
                    error = 1;
                    return false;
                }
            });
            
            if(error == 1)
            {
                $.unblockUI();
                alert("Values cannot be left blank");
                return false;
            }
            
            $.ajax({
                type: "POST",
                url: base_url+"freeswitch/update_settings_on_add_row",
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
                        if(type == '')
                        {
                            $.unblockUI();
                            alert('No More Parameters Are Available To Add (Settings Saved Successfully)');
                        }
                        else
                        {
                            $.unblockUI();
                            alert('No More Parameters Are Available To Add For The Type '+type+' (Settings Saved Successfully)');
                        }
                    }
                }
            });
        });
        
        $('#edit-settings-form').submit(function(){
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
            var type = $('#setting_type').val();
            var form = $('#edit-settings-form').serialize();
            var error = 0;
            
            $('.settings_value').each(function(){
                if($(this).val() == '')
                {
                    error = 1;
                    return false;
                }
            });
            
            if(error == 1)
            {
                $.unblockUI();
                alert("Values cannot be left blank");
                return false;
            }
            
            $.ajax({
                type: "POST",
                url: base_url+"freeswitch/update_settings",
                data: form,
                success: function(html){
                    $.unblockUI();
                    alert('Settings Updated Successfully');
                }
            });
            return false;
        });
        
        $('.back-to-sofia-set').live('click', function(){
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
            var type = $('#setting_type').val();
            var id = $('#hidden_profile_id').val();
            var data  = 'id='+ id +'&type='+ type;
            $.ajax({
                    type: "POST",
                    url: base_url+"freeswitch/get_settings_main_contents",
                    data: data,
                    success: function(html){
                       
                        $('#edit-set-sofia').hide();
                        $('#ajax-main-content').html(html);
                         $('#main-sofia').show();
                         $.unblockUI();
                        
                    }
                });
            return false;
        });
        
        
        $('.delete_setting').live('click', function(){
            var curr_div = $(this).parent().parent();
            var id = $(this).attr('id');
            var answer = confirm("Are you sure want to delete this setting?")
            if (answer){
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
                $.ajax({
                    type: "POST",
                    url: base_url+"freeswitch/delete_single_setting",
                    data: 'id='+id,
                    success: function(html){
                        $.unblockUI();
                        alert('Setting Deleted Successfully');
                        curr_div.fadeOut();
                    }
                });
            }
            
            return false;
        });
        
        $('.delete_gateway').live('click', function(){
            var curr_div = $(this).parent().parent();
            var id = $(this).attr('id');
            var split = id.split('|');
            var sofia_id = split[0];
            var gateway_name = split[1];
            var data  = 'sofia_id='+ sofia_id +'&gateway_name='+ gateway_name;
            var answer = confirm("Are you sure want to delete this gateway configuration?")
            if (answer){
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
                $.ajax({
                    type: "POST",
                    url: base_url+"freeswitch/delete_gateway",
                    data: data,
                    success: function(html){
                        $.unblockUI();
                        alert('Gateway configuration Deleted Successfully');
                        curr_div.fadeOut();
                    }
                });
            }
            
            return false;
        });
        
                    $('.settings_param').live('change', function(){
                        if($(this).val() == 'auth-calls')
                        {
                           $(this).parent().parent().find('.settings_value').val('true');
                        }
                        else
                        {
                             $(this).parent().parent().find('.settings_value').val('');
                        }
                    });
    </script>
