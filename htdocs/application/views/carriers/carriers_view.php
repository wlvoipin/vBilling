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
<div class="info">Drag the rows of carrier details to set their priorities</div>
<div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>>
<?php echo $this->session->flashdata('success');?>
</div>

<script src="<?php echo base_url();?>assets/js/jquery.tablednd_0_5.js" type="text/javascript"></script>
<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;">
<?php echo $msg_records_found;?>
</div>
    <form method="get" action="<?php echo base_url();?>carriers/" > 
        <table width="729" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="150">
                        Carriers
                    </td>
                    <td width="150">
                        Type
                    </td>
                    
                    <td width="143">
                        Sort By
                    </td>

                    <td width="143" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="143" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td>
                        <select name="filter_carriers" id="filter_carriers" style="width:150px;">
                            <?php echo show_carrier_select_box($filter_carriers);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_carrier_type" id="filter_carrier_type" style="width:150px;">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_carrier_type == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_carrier_type == '0'){ echo "selected";}?>>Disabled</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort" style="width:124px;">
                            <option value="">Select</option>
                            <option value="name_asc" <?php if($filter_sort == 'name_asc'){ echo "selected";}?>>Carrier Name - ASC</option>
                            <option value="name_dec" <?php if($filter_sort == 'name_dec'){ echo "selected";}?>>Carrier Name - DESC</option>
                        </select>
                    </td>
                    
                </tr>
            
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table  width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="20" width="10%" align="center">&nbsp;</td>
                        <td width="20%" align="center">Carrier Name</td>
                        <td width="20%" align="center">Action (Disable)</td>
                        <td width="30%" align="center">Action (Delete)</td>
                    </tr>
                    
                    <tr><td colspan="5" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if ($carriers->num_rows() > 0){ ?>
                    
                    <?php foreach ($carriers->result() as $row): ?>
                        <tr class="main_text">
                            <td align="center"><a href="#" class="show_hide_dtl" id="<?php echo $row->id;?>"><img src="<?php echo base_url();?>assets/images/details.jpg" /></a></td>
                            
                            
                            <?php if($this->session->userdata('user_type') == 'admin'){?>
                                <td align="center"><a href="<?php echo base_url();?>carriers/update_carrier/<?php echo $row->id;?>"><?php echo $row->carrier_name; ?></a></td>
                            <?php 
                                } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_carriers') == 1)
                                        {
                            ?>
                                            <td align="center"><a href="<?php echo base_url();?>carriers/update_carrier/<?php echo $row->id;?>"><?php echo $row->carrier_name; ?></a></td>
                            <?php 
                                        }
                                        else
                                        {
                            ?>
                                            <td align="center"><?php echo $row->carrier_name; ?></td>
                            <?php
                                        }
                                    }
                            ?>
                                
                            <?php if($this->session->userdata('user_type') == 'admin'){?>
                                <td align="center"><input type="checkbox" id="<?php echo $row->id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
                            <?php 
                                } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'enable_disable_carriers') == 1)
                                        {
                            ?>
                                            <td align="center"><input type="checkbox" id="<?php echo $row->id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
                            <?php 
                                        }
                                        else
                                        {
                            ?>
                                            <td align="center"><?php if($row->enabled == 1){ echo 'Enabled';} else { echo 'Disabled';}?></td>
                            <?php
                                        }
                                    }
                            ?>
                            
                            
                            <?php if($this->session->userdata('user_type') == 'admin'){?>
                                <td align="center">
                                    <a href="#" id="<?php echo $row->id;?>" class="delete_carrier"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                </td>
                            <?php 
                                } else if($this->session->userdata('user_type') == 'sub_admin'){
                                        if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'delete_carriers') == 1)
                                        {
                            ?>
                                            <td align="center">
                                                <a href="#" id="<?php echo $row->id;?>" class="delete_carrier"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                            </td>
                            <?php 
                                        }
                                        else
                                        {
                            ?>
                                            <td align="center">---</td>
                            <?php
                                        }
                                    }
                            ?>
                        </tr>
                        
                        <?php 
                            $getGateways = $this->carriers_model->carrier_gateways($row->id);
                            
                            $nodrop = '';
                            if($getGateways->num_rows() == 1)
                            {
                                $nodrop = 'class="nodrop nodrag"';
                            }
                        ?>
                        <tr style="font-size:13px;background:#EFEFEF;display:none;" class="details_<?php echo $row->id;?>">
                            <td colspan="5">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="table-<?php echo $row->id;?>" class="dragabletbl">
                                    <thead style="background: none repeat scroll 0% 0% rgb(77, 77, 77); color: rgb(255, 255, 255);">
                                        <tr>
                                            <th align="left" class="original_orange">Gateway</th>
                                            <th align="left" class="original_orange">Prefix</th>
                                            <th align="left" class="original_orange">Suffix</th>
                                            <th align="left" class="original_orange">Codec</th>
                                            <th align="center" class="original_orange">Priority</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                           <?php 
                                                $count = 1;
                                                $bg = '';
                                                foreach($getGateways->result() as $row){
                                                
                                                if($count % 2)
                                                {
                                                    $bg = "bgcolor='#BDE5F8'";
                                                }
                                                else
                                                {
                                                    $bg = "bgcolor='#88D4F7'";
                                                }
                                                    $gateway_name   = $row->gateway_name;
                                                    $prefix         = $row->prefix;
                                                    $suffix         = $row->suffix;
                                                    $codec          = $row->codec;
                                                    
                                                    if($prefix == '')
                                                    { $prefix = 'N/A'; }
                                                    
                                                    if($suffix == '')
                                                    { $suffix = 'N/A'; }
                                                    
                                                    if($codec == '')
                                                    { $codec = 'N/A'; }
                                                    
                                                    echo '<tr id="'.$row->id.'" '.$nodrop.' '.$bg.'>
                                                            <td align="left" height="30px">'.$gateway_name.'</td>
                                                            <td align="left">'.$prefix.'</td>
                                                            <td align="left">'.$suffix.'</td>
                                                            <td align="left">'.$codec.'</td>
                                                            <td align="center">'.$row->priority.'</td>
                                                         </tr>';
                                                    $count++;
                                                }
                                           ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr style="height:5px;"><td colspan="5" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                    <?php endforeach;?>
                    
                    <?php } else { echo '<td align="center" colspan="4" style="color:red;">No Results Found</td>'; }?>
                                    
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
    
    <div id="dialog-confirm-enable" title="Enable The Carrier?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable This Carrier?</p>
    </div>
    
    <div id="dialog-confirm-disable" title="Disable The Carrier?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable This Carrier?</p>
    </div>
    
    <div id="dialog-confirm-delete" title="Delete The Carrier?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This Carrier?</p>
    </div>
    <div id="extra" style="display:none;"></div>
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
                            var data  = 'carrier_id='+id+'&status=1';
                            $.ajax({
                                type: "POST",
                                url: base_url+"carriers/enable_disable_carrier",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-enable" ).dialog( "close" );
                                    $('.success').html("Carrier Enabled Successfully.");
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
                            var data  = 'carrier_id='+id+'&status=0';
                            $.ajax({
                                type: "POST",
                                url: base_url+"carriers/enable_disable_carrier",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-disable" ).dialog( "close" );
                                    $('.success').html("Carrier Disabled Successfully.");
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
        
        $('.delete_carrier').live('click', function(){
            var id = $(this).attr('id');
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'carrier_id='+id;
                            $.ajax({
                                type: "POST",
                                url: base_url+"carriers/delete_carrier",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    curr_row.fadeOut();
                                    $('.success').html("Carrier Deleted Successfully.");
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
        
        $('#reset').live('click', function(){
            $('#filter_carriers').val('');
            $('#filter_carrier_type').val('');
            return false;
        });
        
        $(".show_hide_dtl").click(function(){
            var id = $(this).attr('id');
            $(".details_"+id+"").toggle();
            return false;
        });
        
        $('.dragabletbl').tableDnD({
            onDragClass: "dragging",
            onDrop: function(table, row) {
                var tbl = table.id;
                var split = tbl.split('-');
                $('#extra').load(base_url+"carriers/update_gateway_priority/?"+$.tableDnD.serialize()+"&carrier_id="+split[1]+"",function() {
                  location.reload();
                });
            }
        });
		
    </script>
