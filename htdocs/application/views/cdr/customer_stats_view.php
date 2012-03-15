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
    <form method="get" action="<?php echo base_url();?>cdr/customer_stats" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="14%">
                        Customers
                    </td>

                    <td width="14%">
                        Display Results In
                    </td>

                    <td width="14%" rowspan="2">
                        <input type="submit" name="searchFilter" id="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="9%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td>
                        <?php 
                            if($filter_contents == 'all')
                            {
                                echo admin_cdr_cust_select_all();
                            }
                            else if($filter_contents == 'my')
                            {
                                echo admin_cdr_cust_select_my();
                            }
                        ?>
                        <input type="text" name="filter_customers" id="filter_customers" value="" />
                    </td>

                    <td>
                        <select name="filter_display_results">
                            <option value="min" <?php if($filter_display_results == 'min'){ echo "selected";}?>>Minutes</option>
                            <option value="sec" <?php if($filter_display_results == 'sec'){ echo "selected";}?>>Seconds</option>
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
                    
                    <style>
                        .sbHolder{
                            width:250px;
                        }
                        .sbOptions{
                            width:250px;
                        }
                    </style>
                    <tr>
                        <td colspan="17">
                            <div style="float:right;height:55px">
							    <div class="button white">
                            <select id="filter_contents_select">
                                <option value="all" <?php if($filter_contents == 'all'){ echo "selected";}?>>Stats For All Customers/Resellers</option>
                                <option value="my" <?php if($filter_contents == 'my'){ echo "selected";}?>>Stats For My Customers/Resellers</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="bottom_link">
                        <td height="20" width="10%" align="left" style="font-size:10px;">Customer</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Balance</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Credit Limit</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(Today)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(Today)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(Yesterday)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(Yesterday)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(2 Days Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(2 Days Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(3 Days Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(3 Days Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(Week Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(Week Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(2 Week Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(2 Week Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Calls<br/>(Month Ago)</td>
                        <td height="20" width="5%" align="center" style="font-size:10px;">Billing<br/>(Month Ago)</td>
                    </tr>
                    <tr><td colspan="17" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($customers->num_rows() > 0) {?>
                        
                        <?php foreach ($customers->result() as $row): ?>
                            <tr class="main_text">
                                <td align="left" height="30"><?php echo anchor_popup('customers/edit_customer/'.$row->customer_id.'', $row->customer_firstname.'&nbsp;'.$row->customer_lastname, $atts); ?></td>
                                <td align="center"><?php echo $row->customer_balance; ?></td>
                               
                                <?php if($row->customer_prepaid == '1'){?>
                                <td align="center">N/A</td>
                                <?php } else {?>
                                <td align="center"><?php echo $row->customer_credit_limit; ?></td>
                                <?php } ?>
                                
                                <!-- Dynamic calls started from here for each field -->
                                
                                <!--************** TODAY ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, 'today'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, 'today');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, 'today');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** YESTERDAY ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, 'yesterday'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, 'yesterday');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, 'yesterday');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** 2 DAYS AGO  ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, '2_days_ago'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, '2_days_ago');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, '2_days_ago');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** 3 DAYS AGO  ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, '3_days_ago'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, '3_days_ago');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, '3_days_ago');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** WEEK AGO  ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, 'week_ago'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, 'week_ago');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, 'week_ago');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** 2 WEEK AGO  ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, '2_week_ago'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, '2_week_ago');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, '2_week_ago');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                <!--************** MONTH AGO  ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_customer_total_calls($row->customer_id, 'month_ago'); ?></td>
                                <?php 
                                    $total = $this->cdr_model->get_customer_total_sell_cost($row->customer_id, 'month_ago');
                                    
                                    if($total == '')
                                    {
                                        $total = 0;
                                    }
                                ?>
                                <?php 
                                   $total_duration = '';
                                   $total_duration = $this->cdr_model->get_customer_total_mins($row->customer_id, 'month_ago');
                                    
                                    if($total_duration == '')
                                    {
                                        $total_duration = 0;
                                    }
                                    
                                    if($filter_display_results == 'sec')
                                    {
                                        $total_duration_in_min_sec = $total_duration; //be defualt it is in sec
                                    }
                                    else
                                    {
                                        $total_duration_in_min_sec = $total_duration / 60; //convert to min
                                        $total_duration_in_min_sec = round($total_duration_in_min_sec, 4);
                                    }
                                ?>
                                <td align="center"><?php echo $total.'<br/>('.$total_duration_in_min_sec.'&nbsp;'.$filter_display_results.')'; ?></td>
                                
                                
                                
                            </tr>
                            <tr style="height:5px;"><td colspan="17" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="17">No Customers Defined Yet</td></tr>'; } ?>
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
        $('#reset').live('click', function(){
            $('#filter_table input[type="text"]').val('');
            $('#filter_table select').val('');
            return false;
        });
        
        $('.datepicker').datetimepicker({
            showSecond: true,
            showMillisec: false,
            timeFormat: 'hh:mm:ss',
            dateFormat: 'yy-mm-dd'
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
        $('div.mcdropdown input[type="text"]').css("width","122px");
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
                    $('#filter_table input[type="text"]').val('');
                    $('#filter_table select').val('');
                    $('#filter_customers').val('');
                    
                    //put the selected value in the hidden search form field 
                    $('#filter_contents').val(val);
                    
                    //click the submit button of search form
                    $('#searchFilter').click();
                }
            });
		});
		</script>