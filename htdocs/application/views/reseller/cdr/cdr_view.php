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

<br/>
<div class="success" id="success_div" style="display:none;"></div>

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

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;">
        <?php echo $msg_records_found;?> 
    </div>
    
    <div style="margin-top:5px;margin-bottom:-5px;">
        <a href="#" alt="Export As PDF" title="Export As PDF" class="exp_pdf"><img src="<?php echo base_url();?>assets/images/export-pdf.gif"/></a>
        <a href="#" alt="Export As EXCEL" title="Export As EXCEL" class="exp_exl"><img src="<?php echo base_url();?>assets/images/export-excel.png"/></a>
        <a href="#" alt="Export As CSV" title="Export As CSV" class="exp_csv"><img src="<?php echo base_url();?>assets/images/export-csv.png"/></a>
    </div>
    
    <form method="get" action="<?php echo base_url();?>cdr/" id="filterForm"> 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="8%">
                        Date From
                    </td>

                    <td width="8%">
                        Date To
                    </td>
                    
                    <td width="8%">
                        Quick Filter
                    </td>
                    
                    <td width="8%">
                        Duration From
                    </td>

                    <td width="8%">
                        Duration To
                    </td>

                    <td width="8%">
                        Phone Num
                    </td>

                    <td width="8%">
                        Customers
                    </td>
                
                
                    <td width="8%">
                        Groups
                    </td>

                    <td width="8%">
                        Call Type
                    </td>
                    
                    <td width="8%">
                        Results In
                    </td>
                    
                    <td width="8%">
                        Sort By
                    </td>

                    <td width="8%" rowspan="2">
                        <input type="submit" id="searchFilter" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="6%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" name="filter_date_from" id="filter_date_from" value="<?php echo $filter_date_from;?>" class="datepicker" readonly></td>
                    <td><input type="text" name="filter_date_to" id="filter_date_to" value="<?php echo $filter_date_to;?>" class="datepicker" readonly></td>
                    
                    <td>
                        <select name="filter_quick" id="filter_quick">
                            <option value="">Select</option>
                            <option value="today" <?php if($filter_quick == 'today'){ echo "selected";}?>>Today</option>
                            <option value="last_hour" <?php if($filter_quick == 'last_hour'){ echo "selected";}?>>Last Hour</option>
                            <option value="last_24_hour" <?php if($filter_quick == 'last_24_hour'){ echo "selected";}?>>Last 24 Hour</option>
                        </select>
                    </td>
                    
                    <td><input type="text" name="duration_from" value="<?php echo $duration_from;?>" class="numeric" maxlength="4"></td>
                    <td><input type="text" name="duration_to" value="<?php echo $duration_to;?>" class="numeric" maxlength="4"></td>
                    
                    <td><input type="text" name="filter_phonenum" value="<?php echo $filter_phonenum;?>" class="numeric"></td>
                    
                    <td>
                        
                        <?php 
                            if($filter_contents == 'all')
                            {
                                echo reseller_cdr_cust_select_all();
                            }
                            else if($filter_contents == 'my')
                            {
                                echo reseller_cdr_cust_select_my();
                            }
                        ?>
                        <input type="text" name="filter_customers" id="filter_customers" value="" />
                    </td>
                    
                    <td>
                        <select name="filter_groups">
                            <?php echo show_group_select_box_reseller($filter_groups);?>
                        </select>
                    </td>

                    <td>
                        <select name="filter_call_type">
                            <?php echo hangup_causes_drop_down($filter_call_type);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_display_results">
                            <option value="min" <?php if($filter_display_results == 'min'){ echo "selected";}?>>Minutes</option>
                            <option value="sec" <?php if($filter_display_results == 'sec'){ echo "selected";}?>>Seconds</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort" style="width:124px;">
                            <option value="">Select</option>
                            
                            <option value="date_asc" <?php if($filter_sort == 'date_asc'){ echo "selected";}?>>Date - ASC</option>
                            <option value="date_dec" <?php if($filter_sort == 'date_dec'){ echo "selected";}?>>Date - DESC</option>
                            
                            <option value="billduration_asc" <?php if($filter_sort == 'billduration_asc'){ echo "selected";}?>>Bill Duration - ASC</option>
                            <option value="billduration_dec" <?php if($filter_sort == 'billduration_dec'){ echo "selected";}?>>Bill Duration - DESC</option>
                            
                            <option value="failedgateways_asc" <?php if($filter_sort == 'failedgateways_asc'){ echo "selected";}?>>Failed Gateways - ASC</option>
                            <option value="failedgateways_dec" <?php if($filter_sort == 'failedgateways_dec'){ echo "selected";}?>>Failed Gateways - DESC</option>
                            
                            <option value="sellrate_asc" <?php if($filter_sort == 'sellrate_asc'){ echo "selected";}?>>Sell Rate - ASC</option>
                            <option value="sellrate_dec" <?php if($filter_sort == 'sellrate_dec'){ echo "selected";}?>>Sell rate - DESC</option>
                            
                            <option value="costrate_asc" <?php if($filter_sort == 'costrate_asc'){ echo "selected";}?>>Cost Rate - ASC</option>
                            <option value="costrate_dec" <?php if($filter_sort == 'costrate_dec'){ echo "selected";}?>>Cost rate - DESC</option>
                            
                            <!-- <option value="sellinit_asc" <?php if($filter_sort == 'sellinit_asc'){ echo "selected";}?>>Sell Init Block - ASC</option>
                            <option value="sellinit_dec" <?php if($filter_sort == 'sellinit_dec'){ echo "selected";}?>>Sell Init Block - DESC</option>
                            
                            <option value="buyinit_asc" <?php if($filter_sort == 'buyinit_asc'){ echo "selected";}?>>Buy Init Block - ASC</option>
                            <option value="buyinit_dec" <?php if($filter_sort == 'buyinit_dec'){ echo "selected";}?>>Buy Init Block - DESC</option> -->
                            
                            <option value="totcharges_asc" <?php if($filter_sort == 'totcharges_asc'){ echo "selected";}?>>Total Charges - ASC</option>
                            <option value="totcharges_dec" <?php if($filter_sort == 'totcharges_dec'){ echo "selected";}?>>Total Charges - DESC</option>
                            
                            <option value="totcost_asc" <?php if($filter_sort == 'totcost_asc'){ echo "selected";}?>>Total Cost - ASC</option>
                            <option value="totcost_dec" <?php if($filter_sort == 'totcost_dec'){ echo "selected";}?>>Total Cost - DESC</option>
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

<table width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <!--this filter contents is only intended for reseller 3-->
                    <?php if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3') {?>
                    <style>
                        .sbHolder{
                            width:250px;
                        }
                        .sbOptions{
                            width:250px;
                        }
                    </style>
                    <tr>
                        <td colspan="13">
                            <div style="float:right;height:55px">
							    <div class="button white">
                            <select id="filter_contents_select">
                                <option value="all" <?php if($filter_contents == 'all'){ echo "selected";}?>>CDR For All Customers/Resellers</option>
                                <option value="my" <?php if($filter_contents == 'my'){ echo "selected";}?>>CDR For My Customers/Resellers</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <tr class="bottom_link">
                        <td height="20" width="8%" align="center">Date/Time</td>
                        <td width="7%" align="center">Destination</td>
                        <td width="7%" align="center">Bill Duration</td>
                        <td width="7%" align="center">Hangup Cause</td>
                        
                        <td width="7%" align="center">Customer</td>
                        <td width="7%" align="center">Sell Rate</td>
                        <!-- <td width="7%" align="center">Sell Init Block</td> -->
                        <td width="7%" align="center">Buy Rate</td>
                        <!-- <td width="7%" align="center">Buy Init Block</td> -->
                        <td width="7%" align="center">Total Charges</td>
                        <td width="7%" align="center">Total Cost</td>
                        <td width="7%" align="center"></td>
                        <td width="7%" align="center"></td>
                    </tr>
                    <tr><td colspan="13" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($cdr->num_rows() > 0) {?>
                        
                        <?php foreach ($cdr->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center" height="30"><?php echo date("Y-m-d H:i:s", $row->created_time/1000000); ?></td>
                                <td align="center"><?php echo $row->destination_number; ?></td>
                                
                                <?php 
                                    if($filter_display_results == 'sec')
                                    {
                                        $billsec        = $row->billsec; // by default bill is in sec
                                        
                                        $sellrate       = $row->sell_rate / 60; // sell rate per sec
                                        $sellrate       = round($sellrate, 4);
                                        
                                        $costrate       = $row->cost_rate / 60; // cost rate per sec
                                        $costrate       = round($costrate, 4);
                                    }
                                    else
                                    {
                                        $billsec        = $row->billsec / 60; // convert to min
                                        $billsec        = round($billsec, 4);
                                        
                                        $sellrate       = $row->sell_rate; // sell rate by default is in min 
                                        $costrate       = $row->cost_rate; // cost rate by default is in min
                                    }
                                ?>

								<?php
								if ($billsec != 0){
									?>
	                                <td align="center"><?php echo $billsec.'&nbsp;'.$filter_display_results; ?></td>
								<?php
								}
								else
								{?>
								<td align="center"><?php echo "N / A"; ?></td>
								<?php
								}
								?>

                                <td align="center"><?php echo $row->hangup_cause; ?></td>
                                
								<?php if ($row->customer_id != 0) {?>
									<td align="center"><?php echo anchor_popup('reseller/customers/edit_customer/'.$row->customer_id.'', customer_full_name($row->customer_id)); ?></td>
									<?php
								}
								else {?>
					            <td align="center"><?php echo "N / A"; ?></td>
					            <?php }?>

                                
                                <!--
                                    ***************************************************************************************
                                                Here There are many things for which we have to take care 
                                                It will be different for reseller 3 and for reseller 2
                                    ***************************************************************************************
                                -->
                                
                                <?php 
                                    //if the user is reseller 3
                                    if(customer_any_cell($this->session->userdata('customer_id'), 'reseller_level') == '3'){
                                ?>
                                    <?php 
                                        //if this customer parent is reseller 3
                                        //than directly apply the rates from the cdr table 
                                        //because those rates are assigned by him for his customers
                                        if($row->parent_reseller_id == $this->session->userdata('customer_id'))
                                        {
                                    ?>
											<td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
	                                        <!-- <td align="center"><?php echo $row->sell_initblock; ?></td> -->
                                            <td align="center"><?php echo $costrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                            <!-- <td align="center"><?php echo $row->buy_initblock; ?></td> -->
                                            
                                            <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                                <td align="center"><?php echo $row->total_sell_cost; ?></td>
                                            <?php } else { ?>
                                                <td align="center">N / A</td>
                                            <?php } ?>
                                            
                                            <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                                <td align="center"><?php echo $row->total_buy_cost; ?></td>
                                            <?php } else { ?>
                                                <td align="center">N / A</td>
                                            <?php } ?>
                                            
                                            <td align="center">&nbsp;</td>
                                            <td align="center">&nbsp;</td>
                                    <?php 
                                        //if the customer is not reseller but grand parent is reseller 
                                        //which means this customer has a parent whose grand parent is reseller 3
                                        //now here we have to see what rate is applied by his parent 
                                        //and than what reseller 3 gave his parent rate
                                        // -->reseller 3 --> reseller 2 --> customer
                                        } else if($row->parent_reseller_id != $this->session->userdata('customer_id') && $row->grand_parent_reseller_id == $this->session->userdata('customer_id'))
                                        {
                                            $getRate = $this->groups_model->get_single_rate($row->reseller_rate_id , $row->reseller_rate_group);
                                            $getRateRow = $row->getRate;
                                            
                                            if($filter_display_results == 'sec')
                                            {
                                                $sellrate       = $getRateRow->sell_rate / 60; // sell rate per sec
                                                $sellrate       = v_round($sellrate);
                                                
                                                $costrate       = $getRateRow->cost_rate / 60; // cost rate per sec
                                                $costrate       = v_round($costrate);
                                            }
                                            else
                                            {
                                                $sellrate       = $getRateRow->sell_rate; // sell rate by default is in min 
                                                $costrate       = $getRateRow->cost_rate; // cost rate by default is in min
                                            }
                                    ?>
									<?php
									if ($sellrate != 0){
										?>
										<td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
									<?php
									}
									else
									{?>
									<td align="center"><?php echo "N / A"; ?></td>
									<?php
									}
									?>

                                            <!-- <td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td> -->
                                            <!-- <td align="center"><?php echo $getRateRow->sell_initblock; ?></td> -->
											<?php
											if ($costrate != 0){
												?>
												<td align="center"><?php echo $costrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
											<?php
											}
											else
											{?>
											<td align="center"><?php echo "N / A"; ?></td>
											<?php
											}
											?>
                                            <!-- <td align="center"><?php echo $getRateRow->buy_initblock; ?></td> -->
                                               
                                            <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
												<?php
												if ($row->total_reseller_sell_cost != 0){
													?>
	                                                <td align="center"><?php echo $row->total_reseller_sell_cost; ?></td>
												<?php
												}
												else
												{?>
												<td align="center"><?php echo "N / A"; ?></td>
												<?php
												}
												?>

												<?php
												if ($row->total_reseller_buy_cost != 0){
													?>
	                                                <td align="center"><?php echo $row->total_reseller_buy_cost; ?></td>
												<?php
												}
												else
												{?>
												<td align="center"><?php echo "N / A"; ?></td>
												<?php
												}
												?>

                                            <?php } else { ?>
                                                <td align="center">N / A</td>
                                                <td align="center">N / A</td>
                                            <?php } ?>
                                                
                                            <td align="center">&nbsp;</td>
                                            <td align="center">&nbsp;</td>
                                    <?php 
                                        }
                                    ?>
                                <?php 
                                    } else {
                                    //the logged user is reseller 2
                                    //directly apply rates
                                ?>
                                    <td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                    <!-- <td align="center"><?php echo $row->sell_initblock; ?></td> -->
                                    <td align="center"><?php echo $costrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                    <!-- <td align="center"><?php echo $row->buy_initblock; ?></td> -->
                                    
                                    <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                        <td align="center"><?php echo $row->total_sell_cost; ?></td>
                                    <?php } else { ?>
                                        <td align="center">N / A</td>
                                    <?php } ?>
                                    
                                    <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                        <td align="center"><?php echo $row->total_buy_cost; ?></td>
                                    <?php } else { ?>
                                        <td align="center">N / A</td>
                                    <?php } ?>
                                    
                                    
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                <?php } ?>
                            </tr>
                            <tr style="height:5px;"><td colspan="13" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="13">No Results Found</td></tr>'; } ?>
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
    
    <script type="text/javascript">
        $('.datepicker').datetimepicker({
            showSecond: true,
            showMillisec: false,
            timeFormat: 'hh:mm:ss',
            dateFormat: 'yy-mm-dd'
        });
        
        $('.ip').numeric({allow:"."});
        $('.numeric').numeric({allow:"."});
        
        $('#reset').live('click', function(){
            $('#filter_table input[type="text"]').val('');
            $('#filter_table select').val('');
            return false;
        });
        
        $('#filter_quick').live('change', function(){
            var val = $(this).val();
            $.ajax({
                type: "POST",
                url: base_url+"reseller/cdr/get_calculated_date_time",
                data: 'val='+val,
                success: function(html){
                    var split = html.split('|');
                    $('#filter_date_from').val(split[0]);
                    $('#filter_date_to').val(split[1]);
                }
            });
        });
        
        $('.exp_pdf').live('click', function(){
            $('#filterForm').attr('action', ''+base_url+'reseller/cdr/export_pdf/');
            $('#filterForm').submit();
            return false;
        });
        
        $('.exp_exl').live('click', function(){
            $('#filterForm').attr('action', ''+base_url+'reseller/cdr/export_excel/');
            $('#filterForm').submit();
            return false;
        });
        
        $('.exp_csv').live('click', function(){
            $('#filterForm').attr('action', ''+base_url+'reseller/cdr/export_csv/');
            $('#filterForm').submit();
            return false;
        });
        
        
        $('#searchFilter').click(function(){
            $('#filterForm').attr('action', ''+base_url+'reseller/cdr/');
        });
    </script>
    
    <!--****FILTER CONTENTS CHANGE BEHAVIOR ***********-->
		<script type="text/javascript">
		$(function () {
			$("#filter_contents_select").selectbox({
                onChange: function (val, inst) {
                    
                    //reset the searach form 
                    $('#filter_table input[type="text"]').val('');
                    $('#filter_table select').val('');
                    
                    //put the selected value in the hidden search form field 
                    $('#filter_contents').val(val);
                    
                    //click the submit button of search form
                    $('#searchFilter').click();
                }
            });
		});
		</script>
        
        <!--**************************Multi DropDown Select Box ************************-->
         <script src="<?php echo base_url();?>assets/js/jquery.mcdropdown.js" type="text/javascript"></script>
         <script src="<?php echo base_url();?>assets/js/jquery.bgiframe.js" type="text/javascript"></script>
         <script type="text/javascript">
        <!--//
        // on DOM ready
        $(document).ready(function (){
            $("#filter_customers").mcDropdown("#quick_customer_filter_list");
            
            //this is to make the option selected 
            var dd = $("#filter_customers").mcDropdown();
            dd.setValue(<?php echo $filter_customers;?>);
            
            //woraround for fixing the input width of mcDropDown
            $('div.mcdropdown input[type="text"]').css("width","114px");
        });
        //-->
        </script>
        <!--************************END*************************-->
