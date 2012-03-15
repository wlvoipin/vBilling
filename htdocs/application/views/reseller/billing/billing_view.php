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

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <form method="get" action="<?php echo base_url();?>reseller/billing/" > 
        <table width="668" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="285">
                        Result for days
                    </td>
                    
                    <td width="208" rowspan="2">
                        <input type="submit" name="searchFilter" id="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="175" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" name="filter_result_days" value="<?php echo $filter_result_days;?>" class="numeric"/> (max 30 days)</td>
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
                        <td colspan="4">
                            <div style="float:right;height:55px">
							    <div class="button white">
                            <select id="filter_contents_select">
                                <option value="all" <?php if($filter_contents == 'all'){ echo "selected";}?>>For All Customers/Resellers</option>
                                <option value="my" <?php if($filter_contents == 'my'){ echo "selected";}?>>For My Customers/Resellers</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <tr class="bottom_link">
                        <td height="20" width="25%" align="left" style="font-size:10px;">Date</td>
                        <td height="20" width="25%" align="center" style="font-size:10px;">Total Calls</td>
                        <td height="20" width="25%" align="center" style="font-size:10px;">Total Amount</td>
                        <td height="20" width="25%" align="center" style="font-size:10px;">Total Profit</td>
                    </tr>
                    <tr><td colspan="4" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    <?php 
                        $m= date("m");
                        $de= date("d");
                        $y= date("Y");
                        for($i=0; $i<$filter_result_days; $i++){
                            $d =  date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y));
                            $date_frm = strtotime($d);
                            $date_to = "".$d." 23:59:59";
                            $date_to = strtotime($date_to);
                            
                            $tot_calls  = $this->billing_model->get_summary_total_calls($date_frm, $date_to, $filter_contents);
                            $tot_amount = $this->billing_model->get_summary_total_amount($date_frm, $date_to, $filter_contents);
                            $tot_profit  = $this->billing_model->get_summary_total_profit($date_frm, $date_to, $filter_contents);
                            
                            if($tot_amount == '')
                            {
                                $tot_amount = 0;
                            }
                            
                            if($tot_profit == '')
                            {
                                $tot_profit = 0;
                            }
                            
                            echo '<tr class="main_text">
                                        <td align="left" height="30">'.$d.'</td>
                                        <td align="center">'.$tot_calls.'</td>
                                        <td align="center">'.$tot_amount.'</td>
                                        <td align="center">'.$tot_profit.'</td>
                                 </tr>';
                            echo '<tr style="height:5px;"><td colspan="4" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>';
                        } 
                    ?>
                    </tbody>
                </table>
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
        
        $('.numeric').numeric();
    </script>
    
    <!--****FILTER CONTENTS CHANGE BEHAVIOR ***********-->
		<script type="text/javascript">
		$(function () {
			$("#filter_contents_select").selectbox({
                onChange: function (val, inst) {
                    
                    //put the selected value in the hidden search form field 
                    $('#filter_contents').val(val);
                    
                    //click the submit button of search form
                    $('#searchFilter').click();
                }
            });
		});
		</script>