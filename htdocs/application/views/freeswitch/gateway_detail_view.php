<br/>
<div class="success" id="success_div" style="display:none;"></div>
<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0" id="main-sofia">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                                <div class="left"><?php echo $gateway_name;?> Configuration</div> 
                                
                                <div class="right main_head_btns">
                                    <a href="<?php echo base_url();?>freeswitch/edit_gateway/<?php echo $sofia_id;?>/<?php echo $gateway_name;?>" >EDIT</a>
                                    
                                    &nbsp; | &nbsp;
                                    <a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">BACK</a>
                                    
                                </div>
                                
                            </th>
                        </tr>
                        
                        <tr class="bottom_link">
                            <th height="20" align="left">Gateway Parameter</th>
                            <th align="left">Values</th>
                            <th align="left">Options</th>
                        </tr>
                    </thead>
                    
                    <tbody id="ajax-main-content">
                    <?php if($gateways->num_rows() > 0) {?>
                        
                        <?php 
                            $count = 1;
                            $bg = '';
                            
                            foreach ($gateways->result() as $row): 
                            
                            if($count % 2)
                            {
                                $bg = "bgcolor='#E6E5E5'";
                            }
                            else
                            {
                                $bg = "";
                            }
                        ?>
                            <tr class="main_text" height="20px" <?php echo $bg; ?>>
                                <td align="left"><?php echo $row->gateway_param;?></td>
                                <td align="left"><?php echo $row->gateway_value;?></td>
                                
                                <?php if($row->gateway_param != 'username' && $row->gateway_param != 'password' && $row->gateway_param != 'proxy' && $row->gateway_param != 'register' && $row->gateway_param != 'channels') {?>
			
                                <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_gateway_config"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>            
                                
                                <?php } else { echo "<td align='left'>&nbsp;</td>";} ?>
                                
                                
                            </tr>
                        <?php 
                            $count++;
                            endforeach;
                        ?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody></table>
    

    <script type="text/javascript">
        $('.delete_gateway_config').click(function(){
     
		var curr_div = $(this).parent().parent();
		var id = $(this).attr('id');
		var answer = confirm("Are you sure want to delete this gateway configuration?")
		if (answer){
               //show wait msg 
            $.blockUI({ css: { 
                            border: 'none', 
                            padding: '15px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .5, 
                            color: '#fff' 
                            } 
                        });
                    $.ajax({
                        type: "POST",
                            url: base_url+"freeswitch/delete_gateway_config",
                            data: 'id='+id,
                        success: function(html){
                            $.unblockUI();
                            alert('Gateway configuration Deleted Successfully');
                            curr_div.fadeOut();
                        }
                        });
			}

			return false;
			});
    </script>
