<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Customer SIP Credentials            </td>
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
        <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <thead>
                    <tr class="main_text">
                        <td align="right" colspan="5"><a href="<?php echo base_url();?>customers/new_sip_access/<?php echo $customer_id;?>">NEW SIP ACCESS</a></td>
                    </tr>
                    
                    <tr class="bottom_link">
                        <td width="20%" align="center">Username</td>
                        <td width="20%" align="center">Password</td>
                        <td width="20%" align="center">Domain</td>
                        <td width="20%" align="center">Sofia Profile</td>
                        <td width="20%" align="center">Options</td>
                    </tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($sip_access->num_rows() > 0) {?>
                                <?php foreach($sip_access->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        <td align="center"><?php echo $row->username; ?></td>
                                        <td align="center">******</td>
                                        <td align="center"><?php echo $row->domain; ?></td>
                                        <td align="center"><?php echo sofia_profile_name($row->domain_sofia_id); ?></td>
                                        
                                        <td align="center">
                                            <a href="#" id="<?php echo $row->id;?>" class="delete_access"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
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
                                url: base_url+"customers/delete_sip_access",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    curr_row.fadeOut();
                                    $('.success').html("SIP Credentials Deleted Successfully.");
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