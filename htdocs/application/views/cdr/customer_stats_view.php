<!--POP UP ATTRIBUTES-->
<?php 
    $atts = array(
                  'width'      => '800',
                  'height'     => '600',
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
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="9%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td>
                        <select name="filter_customers">
                            <?php echo customer_drop_down($filter_customers);?>
                        </select>
                    </td>

                    <td>
                        <select name="filter_display_results">
                            <option value="min" <?php if($filter_display_results == 'min'){ echo "selected";}?>>Minutes</option>
                            <option value="sec" <?php if($filter_display_results == 'sec'){ echo "selected";}?>>Seconds</option>
                        </select>
                    </td>
                    
                </tr>
            
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table style="border: 1px groove;" width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
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