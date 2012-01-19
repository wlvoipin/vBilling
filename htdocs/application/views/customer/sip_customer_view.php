<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            SIP Credentials            </td>
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
                        $used_sip_acc_count  = restricted_customer_sip_acc_count($customer_id);
                        $limit_of_sip_acc = customer_access_any_cell($customer_id, 'total_sip_accounts');
                        $remaining = $limit_of_sip_acc -  $used_sip_acc_count;
                        
                        if($used_sip_acc_count < $limit_of_sip_acc)
                        {
                    ?>
                        <tr class="main_text" style="background:none;">
                            <td align="right" colspan="6">
                                <a href="<?php echo base_url();?>customer/new_sip_access">NEW SIP ACCESS</a>
                                <br/>
                                You can add upto <?php echo $limit_of_sip_acc;?> SIP Accounts (<?php echo $remaining;?> Remaining)
                            </td>
                        </tr>
                    <?php } else {?>
                        <tr class="main_text" style="background:none;">
                            <td align="right" colspan="6"><a href="#">Cannot Add More SIP Accounts</a></td>
                        </tr>
                    <?php } ?>
                    
                    <tr class="bottom_link">
                        <td width="15%" align="center">Username</td>
                        <td width="15%" align="center">Password</td>
                        <td width="15%" align="center">CID</td>
                        <td width="20%" align="center">Domain</td>
                        <td width="15%" align="center">Sofia Profile</td>
                        <td width="15%" align="center">Options</td>
                    </tr>
                    <tr><td colspan="6" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($sip_access->num_rows() > 0) {?>
                                <?php foreach($sip_access->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        <td align="center"><?php echo $row->username; ?></td>
                                        <td align="center" id="reset_pass"><a class="reset_pass" href="#" id="<?php echo $row->id; ?>">Reset Password</a></td>
                                        <td align="center"><?php echo $row->cid; ?></td>
                                        <td align="center"><?php echo $row->domain; ?></td>
                                        <td align="center"><?php echo sofia_profile_name($row->domain_sofia_id); ?></td>
                                        
                                        <td align="center">
                                            <a href="#" id="<?php echo $row->id;?>" class="delete_access"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                        </td>
                                        
                                    </tr>
                                    <tr style="height:5px;"><td colspan="6" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                                <?php } ?>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="6" style="color:red;">No Records Found</td></tr>
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
    
    <div id="dialog-confirm-delete" title="Delete The SIP Credentials?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This SIP Credentials?</p>
    </div>
    
    
<script type="text/javascript">
        $('.delete_access').live('click', function(){
            var id = $(this).attr('id');
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'record_id='+id;
                            $.ajax({
                                type: "POST",
                                url: base_url+"customer/delete_sip_access",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    /*curr_row.fadeOut();
                                    $('.success').html("SIP Credentials Deleted Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();*/
                                    window.location = base_url+"customer/sip_access";
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
        
        $('.reset_pass').live('click', function(){
            var curr = $(this);
            var id = $(this).attr('id');
            var data  = 'record_id='+id;
            
            $.ajax({
                type: "POST",
                url: base_url+"customer/reset_sip_password",
                data: data,
                success: function(html){
                        $('.error').hide();
                        $('.success').html('Your password has been reset successfully. Your new password is ('+html+')');
                        $('.success').fadeOut();
                        $('.success').fadeIn();
                        curr.parent().html(html);
                        document.getElementById('success_div').scrollIntoView();
                }
            });
           return false;
        });
</script>