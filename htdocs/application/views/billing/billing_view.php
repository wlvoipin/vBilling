<br/>
<div class="success" id="success_div" style="display:none;"></div>

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
  <div class="button white">
    <form method="get" action="<?php echo base_url();?>billing/" >
      <table width="798" cellspacing="0" cellpadding="0" border="0" id="filter_table">
        <tr>
          <td width="253"> Result for days </td>
          <td width="195"> Carrier </td>
          <td width="195" rowspan="2"><input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" /></td>
          <td width="155" rowspan="2"><a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a></td>
        </tr>
        <tr>
          <td><input type="text" name="filter_result_days" value="<?php echo $filter_result_days;?>" class="numeric"/>
            (max 30 days)</td>
          <td><select name="filter_carriers">
              <?php echo show_carrier_select_box($filter_carriers);?>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table width="100%" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr class="bottom_link">
              <td height="20" width="25%" align="left" style="font-size:10px;">Date</td>
              <td height="20" width="25%" align="center" style="font-size:10px;">Total Calls</td>
              <td height="20" width="25%" align="center" style="font-size:10px;">Total Amount</td>
              <td height="20" width="25%" align="center" style="font-size:10px;">Total Profit</td>
            </tr>
            <tr>
              <td colspan="4" id="shadowDiv" style="height:5px;margin-top:-1px"></td>
            </tr>
            <?php 
                        $m= date("m");
                        $de= date("d");
                        $y= date("Y");
                        for($i=0; $i<$filter_result_days; $i++){
                            $d =  date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y));
                            $date_frm = strtotime($d);
                            $date_to = "".$d." 23:59:59";
                            $date_to = strtotime($date_to);
                            
                            $tot_calls  = $this->billing_model->get_summary_total_calls($date_frm, $date_to, $filter_carriers);
                            $tot_amount = $this->billing_model->get_summary_total_amount($date_frm, $date_to, $filter_carriers);
                            $tot_profit  = $this->billing_model->get_summary_total_profit($date_frm, $date_to, $filter_carriers);
                            
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
        </table></td>
    </tr>
  </tbody>
</table>
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