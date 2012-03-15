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
<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            <?php echo $this->lang->line('reseller_popup_title_bar_customer_acl_nodes');?>            </td>
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
        
        <?php require_once("pop_up_menu.php");?>

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
        <table cellspacing="0" cellpadding="0" border="0" width="95%" class="search_col">
                
                <thead>
                    
                    
                    <?php if(customer_any_cell($customer_id, 'parent_id') == $this->session->userdata('customer_id')){?>
                        <tr class="main_text" style="background:none;">
                            <td align="right" colspan="5"><a href="<?php echo base_url();?>reseller/customers/new_acl_node/<?php echo $customer_id;?>"><?php echo $this->lang->line('reseller_popup_new_acl_node');?></a></td>
                        </tr>
                    <?php } ?>
                           
                    <tr class="bottom_link">
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_popup_acl_ip_address');?></td>
<!--
                        <td width="20%" align="center">ACL List</td>
                        <td width="20%" align="center">ACL List Policy</td>
-->
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_popup_enable_disable');?></td>
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_popup_action_delete_acl');?></td>
                    </tr>
                     <tr><td colspan="5" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($acl_nodes->num_rows() > 0) {?>
                                <?php foreach($acl_nodes->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        
                                        
                                        <?php if(customer_any_cell($customer_id, 'parent_id') == $this->session->userdata('customer_id')){?>
                                            <td align="center"><a href="<?php echo base_url();?>reseller/customers/edit_acl_node/<?php echo $row->id; ?>/<?php echo $customer_id;?>"><?php echo $row->cidr; ?></a></td>
                                        <?php } else { ?>
                                             <td align="center"><?php echo $row->cidr; ?></td>
                                        <?php } ?>
                                        
<!--
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'acl_name'); ?></td>
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'default_policy'); ?></td>
-->

                                        <?php if(customer_any_cell($customer_id, 'parent_id') == $this->session->userdata('customer_id')){?>
                                            <td align="center">
                                                <select class="node_deny_allow" id="<?php echo $row->id; ?>">
                                                    <option value="allow" <?php if($row->type == 'allow'){ echo "selected"; }?>><?php echo $this->lang->line('reseller_popup_enable_acl');?></option>
                                                    <option value="deny" <?php if($row->type == 'deny'){ echo "selected"; }?>><?php echo $this->lang->line('reseller_popup_disable_acl');?></option>
                                                </select>
                                            </td>
                                        <?php } else { ?>
                                            <td align="center"><?php if($row->type == 'allow'){ echo "$this->lang->line('reseller_popup_enable_acl')"; } else { echo "$this->lang->line('reseller_popup_disable_acl')";;}?></td>
                                        <?php } ?>
                                        
                                        <?php if(customer_any_cell($customer_id, 'parent_id') == $this->session->userdata('customer_id')){?>
                                            <td align="center">
                                                <a href="#" id="<?php echo $row->id;?>" class="delete_node"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                            </td>
                                        <?php } else { ?>
                                                <td align="center">---</td>
                                        <?php } ?>
                                        
                                    </tr>
                                    <tr style="height:5px;"><td colspan="5" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                                <?php } ?>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="5" style="color:red;"><?php echo $this->lang->line('reseller_popup_no_records_found');?></td></tr>
                            <?php } ?>
                    
                </tbody>
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
    
    <div id="dialog-confirm-delete" title="<?php echo $this->lang->line('reseller_popup_dialog_confirm_delete');?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo $this->lang->line('reseller_popup_alert_delete_acl');?></p>
    </div>
    
    <div id="dialog-confirm-update" title="<?php echo $this->lang->line('reseller_popup_dialog_confirm_update_type');?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo $this->lang->line('reseller_popup_alert_enable_disable_acl');?></p>
    </div>
    
<script type="text/javascript">
        $('.delete_node').live('click', function(){
            var id = $(this).attr('id');
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'node_id='+id;
                            $.ajax({
                                type: "POST",
                                url: base_url+"reseller/customers/delete_acl_node",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    curr_row.fadeOut();
                                    $('.success').html("<?php echo $this->lang->line('reseller_popup_acl_node_deleted');?>");
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
        
        $('.node_deny_allow').change(function(){
            var id = $(this).attr('id');
            var value = $(this).val();
            
            $( "#dialog-confirm-update" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'node_id='+id+'&value='+value;
                            $.ajax({
                                type: "POST",
                                url: base_url+"reseller/customers/change_acl_node_type",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-update" ).dialog( "close" );
                                    $('.success').html("<?php echo $this->lang->line('reseller_popup_acl_node_changed');?>");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            if(value == 'allow')
                            {
                                $('#'+id+'').val('deny');
                            }
                            else
                            {
                                $('#'+id+'').val('allow');
                            }
                        }
                    }
                });
                
                return false;
    });
</script>