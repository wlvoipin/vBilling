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
<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody>
        <tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Customer Invoices            </td>
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
    <div style="color:green; font-weight:bold;">
        <?php echo $msg_records_found;?> 
    </div>
    
    <form method="get" action="<?php echo base_url();?>customers/invoices/<?php echo $customer_id;?>/" id="filterForm"> 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="8%">
                        Date From
                    </td>

                    <td width="8%">
                        Date To
                    </td>
                    
                    <td width="8%">
                        Status
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
                        <select name="filter_status">
                            <option value="">Select</option>
                            <option value="paid" <?php if($filter_status == 'paid'){ echo "selected";}?>>Paid</option>
                            <option value="pending" <?php if($filter_status == 'pending'){ echo "selected";}?>>Pending</option>
                            <option value="over_due" <?php if($filter_status == 'over_due'){ echo "selected";}?>>Over Due</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort" style="width:124px;">
                            <option value="">Select</option>
                            
                            <option value="date_asc" <?php if($filter_sort == 'date_asc'){ echo "selected";}?>>Generated Date - ASC</option>
                            <option value="date_dec" <?php if($filter_sort == 'date_dec'){ echo "selected";}?>>Generated Date - DESC</option>
                            
                            <option value="totcalls_asc" <?php if($filter_sort == 'totcalls_asc'){ echo "selected";}?>>Total Calls - ASC</option>
                            <option value="totcalls_dec" <?php if($filter_sort == 'totcalls_dec'){ echo "selected";}?>>Total Calls - DESC</option>
                            
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
                        <td height="20" width="8%" align="center">Generated Date</td>
                        <td width="8%" align="center">Due Date</td>
                        <td width="7%" align="center">Invoice #</td>
                        <td width="7%" align="center">Customer</td>
                        <td width="7%" align="center">Billing From</td>
                        <td width="7%" align="center">Billing To</td>
                        <td width="7%" align="center">Total Calls</td>
                        <td width="7%" align="center">Total Charges</td>
                        <td width="7%" align="center">Billing Type</td>
                        <td width="7%" align="center">Status</td>
                        <td width="7%" align="center">View Invoice</td>
                        <td width="7%" align="center">View CDR</td>
                    </tr>
                    <tr><td colspan="12" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($invoices->num_rows() > 0) {?>
                        
                        <?php foreach ($invoices->result() as $row): ?>
                        
                        <?php 
                            /*****CHECK FOR DUE DATE ****/
                            
                            if($row->status == 'pending')
                            {
                                $due_date = $row->due_date;
                                $current_date   = date('Y-m-d');
                                $current_date   = strtotime($current_date);
                                
                                if($current_date > $due_date)
                                {
                                    make_invoice_over_due($row->id);
                                }
                            }
                        ?>
                            <tr class="main_text">
                                <td align="center" height="30"><?php echo date("Y-m-d", $row->invoice_generated_date); ?></td>
                                <td align="center"><?php echo date("Y-m-d", $row->due_date); ?></td>
                                <td align="center"><?php echo $row->invoice_id; ?></td>
                                <td align="center"><?php echo customer_full_name($row->customer_id); ?></td>
                                <td align="center" height="30"><?php echo date("Y-m-d H:i:s", $row->from_date); ?></td>
                                <td align="center" height="30"><?php echo date("Y-m-d H:i:s", $row->to_date); ?></td>
                                <td align="center" height="30"><?php echo $row->total_calls; ?></td>
                                <td align="center" height="30"><?php echo $row->total_charges; ?></td>
                                
                                <?php
                                    if($row->customer_prepaid == '1')
                                    {
                                        $bill_type = "Prepaid";
                                    }
                                    else
                                    {
                                        $bill_type = "Postapid";
                                    }
                                ?>
                                <td align="center" height="30"><?php echo $bill_type; ?></td>
                                
                                <?php
                                    $latest_status = invoices_any_cell($row->id, 'status');
                                    if($latest_status == 'paid')
                                    {
                                        $inv_status = '<span class="button green" style="width:52px">PAID</span>';
                                    }
                                    else if($latest_status == 'pending')
                                    {
                                        $inv_status = '<span class="button original_orange" style="width:52px">PENDING</span>';
                                    }
                                    else if($latest_status == 'over_due')
                                    {
                                        $inv_status = '<span class="button red">OVER DUE</span>';
                                    }
                                ?>
                                <td align="center" height="30"><?php echo $inv_status; ?></td>
                                
                                <td align="center" height="30"><a href="<?php echo base_url(); ?>billing/download_invoice/<?php echo $row->invoice_id;?>"><img src="<?php echo base_url();?>assets/images/export-pdf.gif"/> View Invoice</a></td>
                                
                                <td align="center" height="30"><a href="<?php echo base_url(); ?>billing/download_cdr/<?php echo $row->invoice_id;?>"><img src="<?php echo base_url();?>assets/images/export-pdf.gif"/> View CDR</a></td>
                                
                            </tr>
                            <tr style="height:5px;"><td colspan="12" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="12">No Results Found</td></tr>'; } ?>
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
        $('.datepicker').datepicker({
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
