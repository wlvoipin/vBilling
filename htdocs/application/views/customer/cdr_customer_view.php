<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody>
        <tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            CDR List            </td>
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
    </tbody>
</table>
        
<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>customer/customer_cdr/" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="11%">
                        Date From
                    </td>

                    <td width="11%">
                        Date To
                    </td>

                    <td width="11%">
                        Phone Num
                    </td>

                    <td width="11%">
                        Caller IP
                    </td>
                    
                    <td width="11%">
                        Gateways
                    </td>

                    <td width="11%">
                        Call Type
                    </td>
                    
                    <td width="8%">
                        Results In
                    </td>
                    
                    <td width="11%">
                        Sort By
                    </td>

                    <td width="11%" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="7%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" name="filter_date_from" value="<?php echo $filter_date_from;?>" class="datepicker" readonly></td>
                    <td><input type="text" name="filter_date_to" value="<?php echo $filter_date_to;?>" class="datepicker" readonly></td>
                    <td><input type="text" name="filter_phonenum" value="<?php echo $filter_phonenum;?>" class="numeric"></td>
                    <td><input type="text" name="filter_caller_ip" value="<?php echo $filter_caller_ip;?>" class="ip"></td>
                    
                    <td>
                        <select name="filter_gateways">
                            <?php 
                                if($filter_gateways != '')
                                {
                                    if (strpos($filter_gateways,'|') !== false) {
                                        $explode = explode('|', $filter_gateways);
                                        $gateway = $explode[0];
                                        $profile = $explode[1];
                                        if(isset($gateway) && isset($profile))
                                        {
                                            if(!is_numeric($profile))
                                            {
                                                $gateway = '';
                                                $profile = '';
                                            }
                                        }
                                        else
                                        {
                                            $gateway = '';
                                            $profile = '';
                                        }
                                    }
                                    else
                                    {
                                        $gateway = '';
                                        $profile = '';
                                    }
                                }
                                else
                                {
                                    $gateway = '';
                                    $profile = '';
                                }
                            ?>
                            <?php echo gateways_drop_down($gateway, $profile);?>
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
                            
                            <option value="sellrate_asc" <?php if($filter_sort == 'sellrate_asc'){ echo "selected";}?>>Sell Rate - ASC</option>
                            <option value="sellrate_dec" <?php if($filter_sort == 'sellrate_dec'){ echo "selected";}?>>Sell rate - DESC</option>
                            
                            <option value="costrate_asc" <?php if($filter_sort == 'costrate_asc'){ echo "selected";}?>>Cost Rate - ASC</option>
                            <option value="costrate_dec" <?php if($filter_sort == 'costrate_dec'){ echo "selected";}?>>Cost rate - DESC</option>
                            
                            <option value="sellinit_asc" <?php if($filter_sort == 'sellinit_asc'){ echo "selected";}?>>Sell Init Block - ASC</option>
                            <option value="sellinit_dec" <?php if($filter_sort == 'sellinit_dec'){ echo "selected";}?>>Sell Init Block - DESC</option>
                            
                            <option value="buyinit_asc" <?php if($filter_sort == 'buyinit_asc'){ echo "selected";}?>>Buy Init Block - ASC</option>
                            <option value="buyinit_dec" <?php if($filter_sort == 'buyinit_dec'){ echo "selected";}?>>Buy Init Block - DESC</option>
                            
                            <option value="totcharges_asc" <?php if($filter_sort == 'totcharges_asc'){ echo "selected";}?>>Total Charges - ASC</option>
                            <option value="totcharges_dec" <?php if($filter_sort == 'totcharges_dec'){ echo "selected";}?>>Total Charges - DESC</option>
                            
                            <option value="totcost_asc" <?php if($filter_sort == 'totcost_asc'){ echo "selected";}?>>Total Cost - ASC</option>
                            <option value="totcost_dec" <?php if($filter_sort == 'totcost_dec'){ echo "selected";}?>>Total Cost - DESC</option>
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
                        <td height="20" width="8%" align="center">Date/Time</td>
                        <td width="7%" align="center">Destination</td>
                        <td width="7%" align="center">Bill Duration</td>
                        <td width="7%" align="center">Hangup Cause</td>
                        <td width="7%" align="center">IP Address</td>
                        <td width="7%" align="center">Username</td>
                        <td width="7%" align="center">Sell Rate</td>
                        <td width="7%" align="center">Sell Init Block</td>
                        <td width="7%" align="center">Cost Rate</td>
                        <td width="7%" align="center">Buy Init Block</td>
                        <td width="7%" align="center">Total Charges</td>
                        <td width="7%" align="center">Total Cost</td>
                        <td width="7%" align="center">Margin</td>
                        <td width="7%" align="center">Markup</td>
                    </tr>
                    
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
                                <td align="center"><?php echo $billsec.'&nbsp;'.$filter_display_results; ?></td>
                                <td align="center"><?php echo $row->hangup_cause; ?></td>
                                <td align="center"><?php echo $row->network_addr; ?></td>
                                <td align="center"><?php echo $row->username; ?></td>
                                <td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                <td align="center"><?php echo $row->sell_initblock; ?></td>
                                <td align="center"><?php echo $costrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                <td align="center"><?php echo $row->buy_initblock; ?></td>
                                
                                <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                    <td align="center"><?php echo $row->total_sell_cost; ?></td>
                                <?php } else { ?>
                                    <td align="center">0</td>
                                <?php } ?>
                                
                                <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                    <td align="center"><?php echo $row->total_buy_cost; ?></td>
                                <?php } else { ?>
                                    <td align="center">0</td>
                                <?php } ?>
                                
                                <td align="center">&nbsp;</td>
                                <td align="center">&nbsp;</td>
                            </tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="14">No Results Found</td></tr>'; } ?>
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
    </script>