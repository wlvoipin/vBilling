<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="20" width="10%" align="center">Profile Name</td>
                        <td width="20%" align="center">Details</td>
                        <td width="60%" align="left">Options</td>
                    </tr>
                    
                    <?php if($profiles->num_rows() > 0) {?>
                        
                        <?php foreach ($profiles->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center"><?php echo $row->profile_name;?></td>
                                <td align="center"><a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $row->id;?>">View Details</a></td>
                                
                                <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_profile"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                
                            </tr>
                        <?php endforeach;?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody></table>
    
    <div id="dialog-confirm-delete" title="Delete The Profile?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete This Profile?</p>
    </div>

    <script type="text/javascript">
        
        $('.delete_profile').live('click', function(){
            var id = $(this).attr('id');
            var curr_row = $(this).parent().parent();
            
            $( "#dialog-confirm-delete" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'sofia_id='+id;
                            $.ajax({
                                type: "POST",
                                url: base_url+"freeswitch/delete_profile",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-delete" ).dialog( "close" );
                                    curr_row.fadeOut();
                                    $('.success').html("Profile Deleted Successfully.");
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
