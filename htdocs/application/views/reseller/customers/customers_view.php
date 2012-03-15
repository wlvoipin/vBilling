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
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.mcdropdown.css" />

<!--POP UP ATTRIBUTES-->
<?php 
    $atts = array(
                  'width'      => '1000',
                  'height'     => '800',
                  'scrollbars' => 'yes',
                  'status'     => 'yes',
                  'resizable'  => 'yes',
                  'screenx'    => '0',
                  'screeny'    => '0'
                );
?>
<!--END POP UP ATTRIBUTES-->
<br/>
<div class="success" id="success_div" style="display:none;"></div>

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>reseller/customers/" id="cust_filter_form"> 
        <table width="1233" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="170">
                        Account Number
                    </td>

                    <td width="170">
                        Company
                    </td>

                    <td width="170">
                        First Name
                    </td>
                    
                    <td width="170">
                        Customer
                    </td>

                    <td width="151">
                        Account Status
                    </td>
                    
                    <td width="151">
                        Sort By
                    </td>
                    
                    <td width="151" rowspan="2">
                        <input type="submit" name="searchFilter" id="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="100" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" id="filter_account_num" name="filter_account_num" value="<?php echo $filter_account_num;?>" class="numeric" maxlength="10"></td>
                    <td><input type="text" id="filter_company" name="filter_company" value="<?php echo $filter_company;?>" maxlength="50"></td>
                    <td><input type="text" id="filter_first_name" name="filter_first_name" value="<?php echo $filter_first_name;?>" maxlength="50"></td>
                    
                    <td>
                        <?php echo show_quick_customer_filter_reseller();?>  
                        
                        <input type="text" name="quick_customer_filter" id="quick_customer_filter" value="" />
                    </td>
                    
                    <td>
                        <select name="filter_type" id="filter_type">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_type == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_type == '0'){ echo "selected";}?>>Disabled</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort">
                            <option value="">Select</option>
                            <option value="name_asc" <?php if($filter_sort == 'name_asc'){ echo "selected";}?>>Name - ASC</option>
                            <option value="name_dec" <?php if($filter_sort == 'name_dec'){ echo "selected";}?>>Name - DESC</option>
                            <option value="balance_asc" <?php if($filter_sort == 'balance_asc'){ echo "selected";}?>>Balance - ASC</option>
                            <option value="balance_dec" <?php if($filter_sort == 'balance_dec'){ echo "selected";}?>>Balance - DESC</option>
                        </select>
                    </td>
                </tr>
                
                <!--***hidden field for filter contents *******-->
                <input type="hidden" name="filter_contents" id="filter_contents" value="<?php echo $filter_contents;?>"/>
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
                    
                    <!--this filter contents is only intended for reseller 3-->
                    <?php if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3') {?>
                    <tr>
                        <td colspan="10">
                            <div style="float:right;height:55px">
							    <div class="button white">
                            <select id="filter_contents_select">
                                <option value="all" <?php if($filter_contents == 'all'){ echo "selected";}?>>Show All Customers/Resellers</option>
                                <option value="my" <?php if($filter_contents == 'my'){ echo "selected";}?>>Show My Customers/Resellers</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3'){?>
                        <tr class="bottom_link">
                            <td height="13" width="8%" align="center">Account No</td>
                            <td width="15%" align="left">Name</td>
                            <td width="8%" align="left">Type</td>
                            <td width="15%" align="left">Parent</td>
                            <td width="8%" align="left">Company</td>
                            <td width="14%" align="left">Email</td>
                            <td width="8%" align="left">Country</td>
                            <td width="8%" align="center">Phone</td>
                            <td width="8%" align="center">Balance</td>
                            <td width="8%" align="center">Enabled</td>
                        </tr>
                        
                        <tr><td colspan="10" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                        
                        <?php if($customers->num_rows() > 0) {?>
                            
                            <?php foreach ($customers->result() as $row): ?>
                                <tr class="main_text">
                                    
                                    <td height="25" align="center"><?php echo anchor_popup('reseller/customers/edit_customer/'.$row->customer_id.'', $row->customer_acc_num, $atts); ?></td>
                                    <td align="left"><?php echo $row->customer_firstname.' '.$row->customer_lastname; ?></td>
                                    <?php
                                        $cust_type = "Customer";
                                        if($row->reseller_level != 0)
                                        {
                                            $cust_type = "Reseller";
                                        }
                                    ?>
                                    <td align="left" style="font-weight:bold;"><?php echo $cust_type; ?></td>
                                    <?php
                                        $parent = $row->parent_id;
                                        if($parent == $this->session->userdata('customer_id'))
                                        {
                                            echo '<td align="left" style="font-weight:bold;">You !</td>';
                                        }
                                        else
                                        {
                                            echo '<td align="left" style="font-weight:bold;">'.anchor_popup('reseller/customers/edit_customer/'.$row->parent_id.'', customer_full_name($row->parent_id), $atts).'</td>';
                                        }
                                    ?>
                                    <td align="left"><?php echo $row->customer_company; ?></td>
                                    
                                    <td align="left"><?php echo $row->customer_contact_email; ?></td>
                                    <td align="left"><?php echo country_any_cell($row->customer_country, 'countryname'); ?></td>
                                    <td align="center"><?php echo $row->customer_phone; ?></td>
                                    <td align="center"><?php echo $row->customer_balance; ?></td>
                                    
                                    <td align="center"><input type="checkbox" id="<?php echo $row->customer_id;?>" class="enable_checkbox" <?php if($row->customer_enabled == 1){ echo 'checked="checked"';}?>/></td>
                                </tr>
                                <tr style="height:5px;"><td colspan="10" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                            <?php endforeach;?>
                               
                        <?php } else { echo '<tr><td align="center" style="color:red;" colspan="10">No Results Found</td></tr>'; } ?>
                    <?php } else { ?>
                                <tr class="bottom_link">
                                    <td height="13" width="10%" align="center">Account No</td>
                                    <td width="17%" align="left">Name</td>
                                    <td width="14%" align="left">Company</td>
                                    <td width="17%" align="left">Email</td>
                                    <td width="12%" align="left">Country</td>
                                    <td width="10%" align="center">Phone</td>
                                    <td width="10%" align="center">Balance</td>
                                    <td width="10%" align="center">Enabled</td>
                                </tr>
                                
                                <tr><td colspan="8" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                                
                                <?php if($customers->num_rows() > 0) {?>
                                    
                                    <?php foreach ($customers->result() as $row): ?>
                                        <tr class="main_text">
                                            
                                            <td height="25" align="center"><?php echo anchor_popup('reseller/customers/edit_customer/'.$row->customer_id.'', $row->customer_acc_num, $atts); ?></td>
                                            <td align="left"><?php echo $row->customer_firstname.' '.$row->customer_lastname; ?></td>
                                            
                                            <td align="left"><?php echo $row->customer_company; ?></td>
                                            
                                            <td align="left"><?php echo $row->customer_contact_email; ?></td>
                                            <td align="left"><?php echo country_any_cell($row->customer_country, 'countryname'); ?></td>
                                            <td align="center"><?php echo $row->customer_phone; ?></td>
                                            <td align="center"><?php echo $row->customer_balance; ?></td>
                                            
                                            <td align="center"><input type="checkbox" id="<?php echo $row->customer_id;?>" class="enable_checkbox" <?php if($row->customer_enabled == 1){ echo 'checked="checked"';}?>/></td>
                                        </tr>
                                        <tr style="height:5px;"><td colspan="8" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                                    <?php endforeach;?>
                                       
                                <?php } else { echo '<tr><td align="center" style="color:red;" colspan="8">No Results Found</td></tr>'; } ?>
                    <?php } ?>
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
    
    <div id="dialog-confirm-enable" title="Enable The Customer?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable This Customer?</p>
    </div>
    
    <div id="dialog-confirm-disable" title="Disable The Customer?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable This Customer?</p>
    </div>

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
                            var data  = 'customer_id='+id+'&status=1';
                            $.ajax({
                                type: "POST",
                                url: base_url+"reseller/customers/enable_disable_customer",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-enable" ).dialog( "close" );
                                    $('.success').html("Customer Enabled Successfully.");
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
                            var data  = 'customer_id='+id+'&status=0';
                            $.ajax({
                                type: "POST",
                                url: base_url+"reseller/customers/enable_disable_customer",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-disable" ).dialog( "close" );
                                    $('.success').html("Customer Disabled Successfully.");
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
        
        $('.numeric').numeric();
        
        $('#reset').live('click', function(){
            $('#filter_account_num').val('');
            $('#filter_company').val('');
            $('#filter_first_name').val('');
            $('#filter_type').val('');
            $('#filter_sort').val('');
            return false;
        });
       
    </script>
    
    <!--**************************Multi DropDown Select Box ************************-->
     <script src="<?php echo base_url();?>assets/js/jquery.mcdropdown.js" type="text/javascript"></script>
     <script src="<?php echo base_url();?>assets/js/jquery.bgiframe.js" type="text/javascript"></script>
     <script type="text/javascript">
	<!--//
	// on DOM ready
	$(document).ready(function (){
		$("#quick_customer_filter").mcDropdown("#quick_customer_filter_list");
        
        //this line is written because when msDropdown is initiated it also make it empty as i am 
        //saving the value in this field also on its setValue function 
        $('#filter_account_num').val(<?php echo $filter_account_num;?>);
        
        //this is to make the option selected 
        var dd = $("#quick_customer_filter").mcDropdown();
        dd.setValue(<?php echo $filter_account_num;?>);
	});
	//-->
	</script>
    <!--************************END*************************-->
    
    <!--****FILTER CONTENTS CHANGE BEHAVIOR ***********-->
		<script type="text/javascript">
		$(function () {
			$("#filter_contents_select").selectbox({
                onChange: function (val, inst) {
                    
                    //reset the searach form 
                    $('#filter_account_num').val('');
                    $('#filter_company').val('');
                    $('#filter_first_name').val('');
                    $('#filter_type').val('');
                    $('#filter_sort').val('');
                    
                    //put the selected value in the hidden search form field 
                    $('#filter_contents').val(val);
                    
                    //click the submit button of search form
                    $('#searchFilter').click();
                }
            });
		});
		</script>