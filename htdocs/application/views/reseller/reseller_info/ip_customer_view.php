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
            <?php echo $this->lang->line('reseller_view_acl_nodes');?>            </td>
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
        <td colspan="3"><div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success');?></div></td>
        </tr>
              
<tr>
    <td align="center" height="20" colspan="3">
        <table cellspacing="0" cellpadding="0" border="0" width="95%" class="search_col">
                
                <thead>
                    <?php 
                        $used_acl_nodes_count   = restricted_customer_acl_nodes_count($customer_id);
                        $limit_of_acl_nodes     = customer_access_any_cell($customer_id, 'total_acl_nodes');
                        $remaining = $limit_of_acl_nodes -  $used_acl_nodes_count;
                        
                        if($used_acl_nodes_count < $limit_of_acl_nodes)
                        {
                    ?>
                        <tr class="main_text" style="background:none;">
                            <td align="right" colspan="5">
                                <a href="<?php echo base_url();?>reseller/info/new_acl_node"><?php echo $this->lang->line('reseller_view_new_acl_node');?></a>
                                <br/>
                                <?php echo $this->lang->line('reseller_view_you_can_add_upto');?> <?php echo $limit_of_acl_nodes;?> <?php echo $this->lang->line('reseller_view_acl_node');?> (<?php echo $remaining;?> <?php echo $this->lang->line('reseller_view_acl_nodes_ramining');?>)
                            </td>
                        </tr>
                    <?php } else {?>
                        <tr class="main_text" style="background:none;">
                            <td align="right" colspan="5"><a href="#"><?php echo $this->lang->line('reseller_view_acl_cannot_add_more_acl_nodes');?></a></td>
                        </tr>
                    <?php } ?>
                    
                    <tr class="bottom_link">
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_view_ip_address');?></td>
<!--
                        <td width="20%" align="center">ACL List</td>
                        <td width="20%" align="center">ACL List Policy</td>
-->
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_view_enable_disable');?></td>
                        <td width="20%" align="center"><?php echo $this->lang->line('reseller_view_action_delete_acl');?></td>
                    </tr>
                    <tr><td colspan="5" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($acl_nodes->num_rows() > 0) {?>
                                <?php foreach($acl_nodes->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        <td align="center"><a href="<?php echo base_url();?>reseller/info/edit_acl_node/<?php echo $row->id; ?>"><?php echo $row->cidr; ?></a></td>
<!--
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'acl_name'); ?></td>
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'default_policy'); ?></td>
-->
                                        <td align="center">
                                            <select class="node_deny_allow" id="<?php echo $row->id; ?>">
                                                <option value="allow" <?php if($row->type == 'allow'){ echo "selected"; }?>>Allowed</option>
                                                <option value="deny" <?php if($row->type == 'deny'){ echo "selected"; }?>>Denied</option>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <a href="#" id="<?php echo $row->id;?>" class="delete_node"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                        </td>
                                        
                                    </tr>
                                    <tr style="height:5px;"><td colspan="5" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                                <?php } ?>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="5" style="color:red;"><?php echo $this->lang->line('reseller_view_no_records_found');?></td></tr>
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
    
    <div id="dialog-confirm-delete" title="<?php echo $this->lang->line('reseller_view_dialog_confirm_delete');?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo $this->lang->line('reseller_view_alert_delete_acl');?></p>
    </div>
    
    <div id="dialog-confirm-update" title="<?php echo $this->lang->line('reseller_view_dialog_confirm_update_type');?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo $this->lang->line('reseller_view_alert_enable_disable_acl');?></p>
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
                                    window.location = base_url+"reseller/info/customer_acl_nodes";
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
                                    $('.success').html("<?php echo $this->lang->line('reseller_view_acl_node_changed');?>");
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