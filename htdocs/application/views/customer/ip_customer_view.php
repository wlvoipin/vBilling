<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            ACL Nodes            </td>
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
        <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <thead>
                    <?php 
                        $used_acl_nodes_count   = restricted_customer_acl_nodes_count($customer_id);
                        $limit_of_acl_nodes     = customer_access_any_cell($customer_id, 'total_acl_nodes');
                        $remaining = $limit_of_acl_nodes -  $used_acl_nodes_count;
                        
                        if($used_acl_nodes_count < $limit_of_acl_nodes)
                        {
                    ?>
                        <tr class="main_text">
                            <td align="right" colspan="5">
                                <a href="<?php echo base_url();?>customer/new_acl_node">NEW ACL NODE</a>
                                <br/>
                                You can add upto <?php echo $limit_of_acl_nodes;?> ACL Nodes (<?php echo $remaining;?> Remaining)
                            </td>
                        </tr>
                    <?php } else {?>
                        <tr class="main_text">
                            <td align="right" colspan="5"><a href="#">Cannot add more ACL Nodes</a></td>
                        </tr>
                    <?php } ?>
                    
                    <tr class="bottom_link">
                        <td width="20%" align="center">CIDR</td>
                        <td width="20%" align="center">ACL List</td>
                        <td width="20%" align="center">ACL List Policy</td>
                        <td width="20%" align="center">TYPE</td>
                        <td width="20%" align="center">Options</td>
                    </tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($acl_nodes->num_rows() > 0) {?>
                                <?php foreach($acl_nodes->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        <td align="center"><a href="<?php echo base_url();?>customer/edit_acl_node/<?php echo $row->id; ?>"><?php echo $row->cidr; ?></a></td>
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'acl_name'); ?></td>
                                        <td align="center"><?php echo acl_list_any_cell($row->list_id, 'default_policy'); ?></td>
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
                                <?php } ?>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="5" style="color:red;">No Records Found</td></tr>
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
    
    <div id="dialog-confirm-delete" title="Delete The ACL Node?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This ACL Node?</p>
    </div>
    
    <div id="dialog-confirm-update" title="Update The ACL Node Type?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Update This ACL Node type?</p>
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
                                url: base_url+"customer/delete_acl_node",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    /*
                                    curr_row.fadeOut();
                                    $('.success').html("ACL Node Deleted Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();*/
                                    window.location = base_url+"customer/customer_acl_nodes";
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
                                url: base_url+"customer/change_acl_node_type",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-update" ).dialog( "close" );
                                    $('.success').html("ACL Node Type Changed Successfully.");
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