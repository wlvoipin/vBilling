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
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody>
        <tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Call Details
		</td>
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
                    
                    <td width="8%">
                        Quick Filter
                    </td>
                    
                    <td width="8%">
                        Duration From
                    </td>

                    <td width="8%">
                        Duration To
                    </td>

                    <td width="11%">
                        Phone Num
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
                            
                            <option value="sellinit_asc" <?php if($filter_sort == 'sellinit_asc'){ echo "selected";}?>>Sell Init Block - ASC</option>
                            <option value="sellinit_dec" <?php if($filter_sort == 'sellinit_dec'){ echo "selected";}?>>Sell Init Block - DESC</option>
                            
                            <option value="totcharges_asc" <?php if($filter_sort == 'totcharges_asc'){ echo "selected";}?>>Total Charges - ASC</option>
                            <option value="totcharges_dec" <?php if($filter_sort == 'totcharges_dec'){ echo "selected";}?>>Total Charges - DESC</option>
                            
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
                        <td height="20" width="15%" align="center">Date/Time</td>
                        <td width="15%" align="center">Destination</td>
                        <td width="10%" align="center">Call Duration</td>
                        <td width="20%" align="center">Hangup Cause</td>
                        <td width="10%" align="center">Account Number</td>
                        <td width="10%" align="center">Sell Rate</td>
                        <td width="10%" align="center">Sell Init Block</td>
                        <td width="10%" align="center">Total Charges</td>
                    </tr>
                    <tr><td colspan="8" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
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
                                    }
                                    else
                                    {
                                        $billsec        = $row->billsec / 60; // convert to min
                                        $billsec        = round($billsec, 4);
                                        $sellrate       = $row->sell_rate; // sell rate by default is in min
                                    }
                                ?>
                                <td align="center"><?php echo $billsec.'&nbsp;'.$filter_display_results; ?></td>
                                <td align="center"><?php echo $row->hangup_cause; ?></td>
                                <td align="center"><?php echo $row->customer_acc_num; ?></td>
                                <td align="center"><?php echo $sellrate.'&nbsp;/&nbsp'.$filter_display_results; ?></td>
                                <td align="center"><?php echo $row->sell_initblock; ?></td>
                                
                                <?php if(($row->hangup_cause == 'NORMAL_CLEARING' || $row->hangup_cause == 'ALLOTTED_TIMEOUT') && $row->billsec > 0) {?>
                                    <td align="center"><?php echo $row->total_sell_cost; ?></td>
                                <?php } else { ?>
                                    <td align="center">0</td>
                                <?php } ?>
                                
                            </tr>
                            <tr style="height:5px;"><td colspan="8" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="8">No Results Found</td></tr>'; } ?>
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
                url: base_url+"customer/get_calculated_date_time",
                data: 'val='+val,
                success: function(html){
                    var split = html.split('|');
                    $('#filter_date_from').val(split[0]);
                    $('#filter_date_to').val(split[1]);
                }
            });
        });
    </script>