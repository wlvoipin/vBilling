<?php 
    $row = $group->row();
?>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Update Group            </td>
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
        <div class="form-container">
        <form enctype="multipart/form-data"  method="post" action="" name="addGroup" id="addGroup">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                <input type="hidden" name="rate_group_id" id="rate_group_id" value="<?php echo $rate_group_id; ?>" />
                <tbody>
                
                <tr>
                    <td align="left" width="10%"><span class="required">*</span> Group Name:</td>
                    <td align="left"><input type="text" value="<?php echo $row->group_name;?>" name="groupname" id="groupname" maxlength="50" class="textfield"></td>
                </tr>
                
                <tr>
                    <td align="left" width="10%">&nbsp;</td>
                    <td align="left"><input border="0" id="submitaddGroupForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                </tr>
                
                <tr>
                    <td align="left" width="100%" colspan="2" style="font-size:14px; text-decoration:underline;padding-top:30px;padding-bottom:20px;">Group Associated Rates:</td>
                </tr>
            </tbody></table>
            
            <table cellspacing="0" cellpadding="0" border="0" width="95%" class="search_col">
                
                <thead>
                    <tr class="bottom_link">
                        <td height="20" width="8%" align="center">ID</td>
                        <td width="8%" align="center">Country Code</td>
                        <td width="8%" align="center">Sell Rate</td>
                        <td width="8%" align="center">Cost Rate</td>
                        <td width="8%" align="center">buy_initblock</td>
                        <td width="8%" align="center">sell_initblock</td>
                        <td width="8%" align="center">intrastate_rate</td>
                        <td width="8%" align="center">intralata_rate</td>
                        <td width="8%" align="center">quality</td>
                        <td width="10%" align="center">reliability</td>
                        <td width="8%" align="center">Carrier</td>
                        <td width="8%" align="center">Enabled</td>
                        <td width="8%" align="center">Options</td>
                    </tr>
                    <tr><td colspan="13" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
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
                            <td align="center"><a href="<?php echo base_url();?>groups/update_rate/<?php echo $rowRate->id;?>/<?php echo $rate_group_id;?>"><?php echo $rowRate->id; ?></a></td>
                            <td align="center"><?php echo $rowRate->digits; ?></td>
                            <td align="center"><?php echo $rowRate->sell_rate; ?></td>
                            <td align="center"><?php echo $rowRate->cost_rate; ?></td>
                            <td align="center"><?php echo $rowRate->buy_initblock; ?></td>
                            <td align="center"><?php echo $rowRate->sell_initblock; ?></td>
                            <td align="center"><?php echo $rowRate->intrastate_rate; ?></td>
                            <td align="center"><?php echo $rowRate->intralata_rate; ?></td>
                            <td align="center"><?php echo $rowRate->quality; ?></td>
                            <td align="center"><?php echo $rowRate->reliability; ?></td>
                            
                            
                            
                            <?php if($check_carrier_exists != 0){?>
                                <td align="center"><a href="<?php echo base_url();?>carriers/update_carrier/<?php echo $rowRate->carrier_id;?>"><?php echo carrier_any_cell($rowRate->carrier_id, 'carrier_name');?></a></td>
                            <?php } else { ?>
                                <td align="center">Carrier Not Found</td>
                            <?php } ?>
                            
                            <td align="center"><input type="checkbox" id="<?php echo $rowRate->id;?>" class="enable_checkbox" <?php if($rowRate->enabled == 1){ echo 'checked="checked"';}?>/></td>
                            
                            <td align="center"><a href="#" id="<?php echo $rowRate->id;?>" class="delete_group_rate"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a></td>
                        </tr>
                        <tr style="height:5px;"><td colspan="13" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                    <?php } ?>
                    <?php } else { ?>
                        
                        <tr class="main_text"><td align="center" colspan="13">No Records Found</td></tr>
                    <?php } ?>
                </tbody>
            </table>
            
        </form>
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

<script type="text/javascript">
    
    $('#addGroup').submit(function(){
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
                
       var groupname = $('#groupname').val();
       
       var error = 0;
       var text = '';
       
       if(groupname == '')
       {
            error = 1;
       }
       
       if(error == 1)
       {
            text += "Please enter group name.";
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
           var form = $('#addGroup').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"groups/edit_group_db",
					data: form,
                    success: function(html){
                            $('.error').hide();
                            $('.success').html("Group updated successfully.");
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

    <div id="dialog-confirm-enable" title="Enable The Rate?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable This Rate?</p>
    </div>
    
    <div id="dialog-confirm-disable" title="Disable The Rate?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable This Rate?</p>
    </div>
    
    <div id="dialog-confirm-delete" title="Delete The Rate?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This Rate?</p>
    </div>

    <script type="text/javascript">
        $('.enable_checkbox').click(function(){
            var curr_chk = $(this);
            var id = $(this).attr('id');
            var rate_group_id = $('#rate_group_id').val();
            var enable = '';
            
            if ($(this).is(':checked'))
            {
                enable = 1;
            }
            else
            {
                enable = 0;
            }
            
            if(enable == 1)
            {
                $( "#dialog-confirm-enable" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'rate_id='+id+'&status=1&rate_group_id='+rate_group_id+'';
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/enable_disable_rate",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-enable" ).dialog( "close" );
                                    $('.success').html("Rate Enabled Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            curr_chk.attr('checked', false);
                        }
                    }
                });
            }
            else
            {
                $( "#dialog-confirm-disable" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'rate_id='+id+'&status=0&rate_group_id='+rate_group_id+'';
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/enable_disable_rate",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-disable" ).dialog( "close" );
                                    $('.success').html("Rate Disabled Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            curr_chk.attr('checked', true);
                        }
                    }
                });
            }
        });
        
        //delete group rate
        $('.delete_group_rate').live('click', function(){
            var id = $(this).attr('id');
            var rate_group_id = $('#rate_group_id').val();
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'rate_id='+id+'&rate_group_id='+rate_group_id+'';
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/delete_group_rate",
                                data: data,
                                success: function(html){
                                        $( "#dialog-confirm-delete" ).dialog( "close" );
                                        curr_row.fadeOut();
                                        $('.success').html("Group Rate Deleted Successfully.");
                                        $('.success').fadeOut();
                                        $('.success').fadeIn();
                                        document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
                
                return false;
        });
    </script>
