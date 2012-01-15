<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
 <link href="<?php echo base_url();?>assets/css/jquery.qtip.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>assets/js/jquery.qtip.min.js" type="text/javascript"></script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody>
        <tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Customer CDR            </td>
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
    <form method="get" action="<?php echo base_url();?>customers/customer_cdr/<?php echo $customer_id;?>" > 
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
                    
                    <td width="11%">
                        Display Results In
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
                        <td width="7%" align="center">Gateway</td>
                        <td width="7%" align="center">Failed Gateways</td>
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
                                <td align="center"><?php echo $row->gateway; ?></td>
                                <td align="center" <?php if($row->total_failed_gateways > 0) {?>class="selector" style="color:red;font-weight:bold;"<?php } ?> id="<?php echo $row->id;?>"><?php echo $row->total_failed_gateways; ?></td>
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
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="16">No Results Found</td></tr>'; } ?>
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
    
    <script type="text/javascript">
    $(".selector").each(function() {
      $(this).qtip({
           content: {
              text: '<img src="'+base_url+'assets/images/loading.gif" alt="Loading..." />',
              ajax: {
                 type: "POST",
                    url: base_url+"cdr/tooltip",
                    data: 'id='+ this.id,
                    success: function(html){
                            this.set('content.text', html);
                        }
              }
           },
           style: {
              classes: 'ui-tooltip-blue ui-tooltip-shadow'
           },
           hide: {
              fixed: true,
              event: 'unfocus'
              //event: false,
              //inactive: 3000
           },
           position: {
              at: 'bottom center', // at the bottom right of...
              my: 'top center'
           },
       });
    });
</script>