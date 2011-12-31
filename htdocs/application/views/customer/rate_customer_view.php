<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Rates            </td>
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
            <td colspan="3" align="center">
                <!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>customer/customer_rates/" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="14%">
                        Start Date
                    </td>

                    <td width="14%">
                        End Date
                    </td>

                    <td width="14%">
                        Carriers
                    </td>

                    <td width="14%">
                        Type
                    </td>
                    
                    <td width="14%">
                        Display Results In
                    </td>

                    <td width="14%" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="10%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" name="filter_start_date" value="<?php echo $filter_start_date;?>" class="datepicker" readonly></td>
                    <td><input type="text" name="filter_end_date" value="<?php echo $filter_end_date;?>" class="datepicker" readonly></td>
                    
                    <td>
                        <select name="filter_carriers">
                            <?php echo show_carrier_select_box($filter_carriers);?>
                        </select>
                    </td>

                    <td>
                        <select name="filter_rate_type">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_rate_type == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_rate_type == '0'){ echo "selected";}?>>Disabled</option>
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
            </td>
        </tr>
              
<tr>
    <td align="center" height="20" colspan="3">
        <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <thead>
                    <tr class="bottom_link">
                        <td width="8%" align="center">Country Code</td>
                        <td width="8%" align="center">Sell Rate</td>
                        <td width="8%" align="center">Sell Init Block</td>
                        <td width="8%" align="center">Start Date</td>
                        <td width="8%" align="center">End Date</td>
                        <td width="8%" align="center">Enabled</td>
                    </tr>
                </thead>
                
                <tbody id="dynamic">
                    <?php if($rates != 'not_found'){?>
                    
                            <?php if($rates->num_rows() > 0) {?>
                            <input type="hidden" name="tbl_name" id="tbl_name" value="<?php echo $tbl_name;?>" />
                            <?php foreach($rates->result() as $rowRate){ ?>
                            
                            <?php 
                                $check_carrier_exists = carrier_exists($rowRate->carrier_id);
                                $bg = '';
                                if($check_carrier_exists == 0)
                                {
                                    $bg = 'style="background:#F28585;"';
                                }
                            ?>
                                <tr class="main_text" <?php echo $bg;?>>
                                    <td align="center"><?php echo $rowRate->digits; ?></td>
                                    
                                    <?php 
                                        if($filter_display_results == 'min')
                                        {
                                            $sellinitblock  = $rowRate->sell_initblock / 60; // convert to min
                                            $sellinitblock  = round($sellinitblock, 4); 
                                            
                                            $sellrate       = $rowRate->sell_rate; // sell rate by default is in min 
                                        }
                                        else
                                        {
                                            $sellinitblock  = $rowRate->sell_initblock; // by default sell init block is in seconds
                                            
                                            $sellrate       = $rowRate->sell_rate / 60; // sell rate per sec
                                            $sellrate       = round($sellrate, 4);
                                        }
                                    ?>
                                    <td align="center"><?php echo $sellrate.'&nbsp;/&nbsp;'.$filter_display_results; ?></td>
                                    <td align="center"><?php echo $sellinitblock.'&nbsp;/&nbsp;'.$filter_display_results; ?></td>
                                    <td align="center"><?php echo $rowRate->date_start; ?></td>
                                    <td align="center"><?php echo $rowRate->date_end; ?></td>
                                    
                                    <td align="center"><?php if($rowRate->enabled == 1){ echo 'YES';} else { echo 'NO'; }?></td>
                                </tr>
                            <?php } ?>
                                <tr>
                                    <td  colspan="13">
                                        <div style="float:right;" id="paginationWKTOP"><?php echo $this->pagination->create_links();?></div>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="6" style="color:red;">No Records Found</td></tr>
                            <?php } ?>
                    <?php } else {?>
                        <tr class="main_text"><td align="center" colspan="6" style="color:red;">No Rate Group Found</td></tr>
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

                            
    </tbody></table>
    
    <script type="text/javascript">
        $('.datepicker').datetimepicker({
            showSecond: true,
            showMillisec: false,
            timeFormat: 'hh:mm:ss',
            dateFormat: 'yy-mm-dd'
        });
        
        $('#reset').live('click', function(){
            $('#filter_table input[type="text"]').val('');
            $('#filter_table select').val('');
            return false;
        });
    </script>
