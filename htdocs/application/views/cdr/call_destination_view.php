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
    <form method="get" action="<?php echo base_url();?>cdr/call_destination" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="14%">
                        Date From
                    </td>

                    <td width="14%">
                        Date To
                    </td>
                    
                    <td width="14%">
                        Country
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
                    <td><input type="text" name="filter_date_from" value="<?php echo $filter_date_from;?>" class="datepicker" readonly></td>
                    <td><input type="text" name="filter_date_to" value="<?php echo $filter_date_to;?>" class="datepicker" readonly></td>
                    
                    <td>
                        <select name="filter_countries">
                            <?php echo all_countries($filter_countries);?>
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
                        <td height="20" width="20%" align="center">Country</td>
                        <td width="20%" align="center">Total Minutes</td>
                        <td width="20%" align="center">Total Calls</td>
                        <td width="20%" align="center">Total Sell Cost</td>
                        <td width="20%" align="center">Total Buy Cost</td>
                    </tr>
                    
                    <?php if($countries->num_rows() > 0) {?>
                        
                        <?php foreach ($countries->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center" height="30"><?php echo country_any_cell($row->id, 'countryname'); ?></td>
                                
                                <!-- Dynamic calls started from here for each field -->
                                
                                <!--**************GET TOTAL MINUTES FOR COUNTRY ******************-->
                                <?php 
                                    $total_duration = $this->cdr_model->get_country_total_mins($row->id, $filter_date_from, $filter_date_to);
                                    
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
                                <td align="center"><?php echo $total_duration_in_min_sec.'&nbsp;'.$filter_display_results; ?></td>
                                
                                <!--**************GET TOTAL CALLS FOR CUSTOMER ******************-->
                                <td align="center"><?php echo $this->cdr_model->get_country_total_calls($row->id, $filter_date_from, $filter_date_to); ?></td>
                                
                                <!--**************GET TOTAL SELL COST FOR CUSTOMER ******************-->
                                <td align="center"><?php echo round($this->cdr_model->get_country_total_sell_cost($row->id, $filter_date_from, $filter_date_to), 4); ?></td>
                                
                                <!--**************GET TOTAL BUY COST FOR CUSTOMER ******************-->
                                <td align="center"><?php echo round($this->cdr_model->get_country_total_buy_cost($row->id, $filter_date_from, $filter_date_to), 4); ?></td>
                                
                            </tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="5">No Records Found</td></tr>'; } ?>
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