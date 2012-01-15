<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table  width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="20" width="10%" align="center">Profile Name</td>
                        <td width="20%" align="center">Details</td>
                        <td width="60%" align="left">Options</td>
                    </tr>
                    <tr><td colspan="3" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($profiles->num_rows() > 0) {?>
                        
                        <?php foreach ($profiles->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center"><?php echo $row->profile_name;?></td>
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <td align="center"><a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $row->id;?>">View Details</a></td>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'profile_details') == 1)
                                            {
                                ?>
                                                <td align="center"><a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $row->id;?>">View Details</a></td>
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
                                
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_profile"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'delete_profiles') == 1)
                                            {
                                ?>
                                                <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_profile"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
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
                                
                            </tr>
                            <tr style="height:5px;"><td colspan="3" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
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
