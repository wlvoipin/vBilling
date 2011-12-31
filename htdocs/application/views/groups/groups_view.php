<br/>
<div class="success" id="success_div" style="display:none;"></div>

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>groups/" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="25%">
                        Groups
                    </td>
.
                    <td width="25%">
                        Type
                    </td>

                    <td width="25%" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="25%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td>
                        <select name="filter_groups" id="filter_groups" style="width:150px;">
                            <?php echo show_group_select_box($filter_groups);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_group_type" id="filter_group_type" style="width:150px;">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_group_type == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_group_type == '0'){ echo "selected";}?>>Disabled</option>
                        </select>
                    </td>
                    
                </tr>
            
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="20" width="10%" align="center">ID</td>
                        <td width="20%" align="left">Group Name</td>
                        <td width="8%" align="center">Enabled</td>
                        <td width="62%" align="left">Options</td>
                    </tr>
                    
                    <?php if($groups->num_rows() > 0) {?>
                        
                        <?php foreach ($groups->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center"><a href="<?php echo base_url();?>groups/update_group/<?php echo $row->id;?>"><?php echo $row->id; ?></a></td>
                                <td align="left"><?php echo $row->group_name; ?></td>
                                
                                <td align="center"><input type="checkbox" id="<?php echo $row->id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
                                
                                <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_group"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                
                            </tr>
                        <?php endforeach;?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="4" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
        
        <tr>
            <td id="paginationWKTOP">
                <?php echo $this->pagination->create_links();?>
            </td>
        </tr>
    </tbody></table>
    
    <div id="dialog-confirm-enable" title="Enable The Group?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable This Group?</p>
    </div>
    
    <div id="dialog-confirm-disable" title="Disable The Group?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable This Group?</p>
    </div>
    
    <div id="dialog-confirm-delete" title="Delete The Group?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This Group?</p>
    </div>
    
    <div id="dialog-confirm-again" title="Delete The Group?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This Group Is In Use. Do You Still Want To Delete It?</p>
    </div>

    <script type="text/javascript">
        $('.enable_checkbox').click(function(){
            var curr_chk = $(this);
            var id = $(this).attr('id');
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
                            var data  = 'group_id='+id+'&status=1';
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/enable_disable_group",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-enable" ).dialog( "close" );
                                    $('.success').html("Group Enabled Successfully.");
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
                            var data  = 'group_id='+id+'&status=0';
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/enable_disable_group",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-disable" ).dialog( "close" );
                                    $('.success').html("Group Disabled Successfully.");
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
        
        $('.delete_group').live('click', function(){
            var id = $(this).attr('id');
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'group_id='+id;
                            $.ajax({
                                type: "POST",
                                url: base_url+"groups/check_group_in_use",
                                data: data,
                                success: function(html){
                                    if(html == 'in_use')
                                    {
                                        $( "#dialog-confirm-enable" ).dialog( "close" );
                                        $( "#dialog-confirm-again" ).dialog({
                                                                        resizable: false,
                                                                        height:180,
                                                                        modal: true,
                                                                        buttons: {
                                                                            "Continue": function() {
                                                                                var data  = 'group_id='+id;
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: base_url+"groups/delete_group",
                                                                                    data: data,
                                                                                    success: function(html){
                                                                                        $( "#dialog-confirm-again" ).dialog( "close" );
                                                                                        $( "#dialog-confirm-delete" ).dialog( "close" );
                                                                                        curr_row.fadeOut();
                                                                                        $('.success').html("Group & Its Associated Rate Table Deleted Successfully.");
                                                                                        $('.success').fadeOut();
                                                                                        $('.success').fadeIn();
                                                                                        document.getElementById('success_div').scrollIntoView();
                                                                                    }
                                                                                });
                                                                            },
                                                                            Cancel: function() {
                                                                                $( "#dialog-confirm-delete" ).dialog( "close" );
                                                                                $("#dialog-confirm-again").dialog( "close" );
                                                                            }
                                                                        }
                                                                    });
                                    }
                                    else if(html == 'deleted')
                                    {
                                        $( "#dialog-confirm-delete" ).dialog( "close" );
                                        curr_row.fadeOut();
                                        $('.success').html("Group & Its Associated Rate Table Deleted Successfully.");
                                        $('.success').fadeOut();
                                        $('.success').fadeIn();
                                        document.getElementById('success_div').scrollIntoView();
                                    }
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
        
        $('#reset').live('click', function(){
            $('#filter_groups').val('');
            $('#filter_group_type').val('');
            return false;
        });
    </script>
