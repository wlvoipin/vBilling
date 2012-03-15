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
<table  width="100%" cellpadding="0" cellspacing="0" id="main-sofia">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th height="20" colspan="3" align="left" class="tbl_main_head">                        
                                <div class="left"><?php echo $gateway_name;?> Configuration</div> 
                                
                                
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <div class="right main_head_btns">
                                        <a href="<?php echo base_url();?>freeswitch/edit_gateway/<?php echo $sofia_id;?>/<?php echo $gateway_name;?>" >EDIT</a>
                                        
                                        &nbsp; | &nbsp;
                                        <a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">BACK</a>
                                        
                                    </div>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_gateway') == 1)
                                            {
                                ?>
                                                <div class="right main_head_btns">
                                                    <a href="<?php echo base_url();?>freeswitch/edit_gateway/<?php echo $sofia_id;?>/<?php echo $gateway_name;?>" >EDIT</a>
                                                    
                                                    &nbsp; | &nbsp;
                                                    <a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">BACK</a>
                                                    
                                                </div>
                                <?php 
                                            }
                                            else
                                            {
                                ?>
                                                <div class="right main_head_btns">
                                                    <a href="<?php echo base_url();?>freeswitch/profile_detail/<?php echo $sofia_id;?>">BACK</a>
                                                </div>
                                <?php
                                            }
                                        }
                                ?>
                                
                            </th>
                        </tr>
                        
                        <tr class="bottom_link">
                            <th height="20" align="left">Gateway Parameter</th>
                            <th align="left">Values</th>
                            <th align="left">Options</th>
                        </tr>
                        <tr><td colspan="3" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    </thead>
                    
                    <tbody id="ajax-main-content">
                    <?php if($gateways->num_rows() > 0) {?>
                        
                        <?php 
                            foreach ($gateways->result() as $row): 
                            
                        ?>
                            <tr class="main_text" height="20px">
                                <td align="left"><?php echo $row->gateway_param;?></td>
                                <td align="left"><?php echo $row->gateway_value;?></td>
                                
                                <?php if($row->gateway_param != 'username' && $row->gateway_param != 'password' && $row->gateway_param != 'proxy' && $row->gateway_param != 'register' && $row->gateway_param != 'channels') {?>
			
                                

                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_gateway_config"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_gateway') == 1)
                                            {
                                ?>
                                                <td align="left"><a href="#" id="<?php echo $row->id;?>" class="delete_gateway_config"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
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
                                
                                <?php } else { echo "<td align='left'>&nbsp;</td>";} ?>
                                
                                
                            </tr>
                            <tr style="height:5px;"><td colspan="3" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php 
                           
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
