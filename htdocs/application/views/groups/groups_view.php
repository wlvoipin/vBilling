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
<br/>
<div class="success" id="success_div" style="display:none;"></div>

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
  <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>groups/" >
      <table width="729" cellspacing="0" cellpadding="0" border="0" id="filter_table">
        <tr>
          <td width="150"> Groups </td>
          <td width="150"> Type </td>
          <td width="143"> Sort By </td>
          <td width="143" rowspan="2"><input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" /></td>
          <td width="143" rowspan="2"><a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a></td>
        </tr>
        <tr>
          <td><select name="filter_groups" id="filter_groups" style="width:150px;">
              <?php echo show_group_select_box($filter_groups);?>
            </select></td>
          <td><select name="filter_group_type" id="filter_group_type" style="width:150px;">
              <option value="">Select</option>
              <option value="1" <?php if($filter_group_type == '1'){ echo "selected";}?>>Enabled</option>
              <option value="0" <?php if($filter_group_type == '0'){ echo "selected";}?>>Disabled</option>
            </select></td>
          <td><select name="filter_sort" id="filter_sort" style="width:124px;">
              <option value="">Select</option>
              <option value="name_asc" <?php if($filter_sort == 'name_asc'){ echo "selected";}?>>Rate Group - ASC</option>
              <option value="name_dec" <?php if($filter_sort == 'name_dec'){ echo "selected";}?>>Rate Group - DESC</option>
            </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table  width="100%" cellpadding="0" cellspacing="0">
  
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="bottom_link">
            <td width="20%" align="center">Rate Group Name</td>
            <td width="8%" align="center">Enabled</td>
            <td width="62%" align="left">Options</td>
          </tr>
          <tr>
            <td colspan="4" id="shadowDiv" style="height:5px;margin-top:-1px"></td>
          </tr>
          <?php if($groups->num_rows() > 0) {?>
          <?php foreach ($groups->result() as $row): ?>
          <tr class="main_text">
            <?php if($this->session->userdata('user_type') == 'admin'){?>
            <td align="center"><a href="<?php echo base_url();?>groups/update_group/<?php echo $row->id;?>"><?php echo $row->group_name; ?></a></td>
            <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_rate_groups') == 1)
                                            {
                                ?>
            <td align="center"><a href="<?php echo base_url();?>groups/update_group/<?php echo $row->id;?>"><?php echo $row->group_name; ?></a></td>
            <?php 
                                            }
                                            else
                                            {
                                ?>
            <td align="center"><?php echo $row->group_name; ?></td>
            <?php
                                            }
                                        }
                                ?>
            <?php if($this->session->userdata('user_type') == 'admin'){?>
            <td align="center"><input type="checkbox" id="<?php echo $row->id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
            <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'enable_disable_rate_groups') == 1)
                                            {
                                ?>
            <td align="center"><input type="checkbox" id="<?php echo $row->id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
            <?php 
                                            }
                                            else
                                            {
                                ?>
            <td align="center"><?php if($row->enabled == 1){ echo 'Enabled';} else { echo "Disabled";}?></td>
            <?php
                                            }
                                        }
                                ?>
            <?php if($this->session->userdata('user_type') == 'admin'){?>
            <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_group"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
            <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'delete_rate_groups') == 1)
                                            {
                                ?>
            <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_group"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
            <?php 
                                            }
                                            else
                                            {
                                ?>
            <td align="left">---</td>
            <?php
                                            }
                                        }
                                ?>
          <tr style="height:5px;">
            <td colspan="4" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td>
          </tr>
          <?php endforeach;?>
          <?php } else { echo '<tr><td align="center" colspan="4" style="color:red;">No Results Found</td></tr>'; } ?>
      </table></td>
  </tr>
  <tr>
    <td id="paginationWKTOP"><?php echo $this->pagination->create_links();?></td>
  </tr>
    </tbody>
</table>
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
                            var data  = 'rate_group_id='+id+'&status=1';
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
                            var data  = 'rate_group_id='+id+'&status=0';
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
                            var data  = 'rate_group_id='+id;
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
                                                                                var data  = 'rate_group_id='+id;
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
