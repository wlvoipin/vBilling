<br/>
<div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success');?> </div>
<div class="error" id="err_div" style="display:none;"></div>
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

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;">
        <?php echo $msg_records_found;?> 
    </div>
    
    <form method="get" action="<?php echo base_url();?>billing/invoices/" id="filterForm"> 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="8%">
                        Date From
                    </td>

                    <td width="8%">
                        Date To
                    </td>
                    
                    <td width="8%">
                        Customers
                    </td>
                    
                    <td width="8%">
                        Billing Type
                    </td>
                    
                    <td width="8%">
                        Status
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
                        <select name="filter_customers">
                            <?php echo customer_drop_down($filter_customers);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_billing_type">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_billing_type == '1'){ echo "selected";}?>>Prepaid</option>
                            <option value="0" <?php if($filter_billing_type == '0'){ echo "selected";}?>>Postpaid</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_status">
                            <option value="">Select</option>
                            <option value="paid" <?php if($filter_status == 'paid'){ echo "selected";}?>>Paid</option>
                            <option value="pending" <?php if($filter_status == 'pending'){ echo "selected";}?>>Pending</option>
                            <option value="over_due" <?php if($filter_status == 'over_due'){ echo "selected";}?>>Over Due</option>
                        </select>
                    </td>
                </tr>
            
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<div class="info">
    * You can only generate invoices for postpaid customers having billing period Weekly, Bi-Weekly or Monthly.<br/>
    * You can only generate invoice if last invoice generated was more than one day ago. <br/>
    * You cannot generate invoice if last invoice was generated today.<br/>
    * An invoice will be generated from last generated invoice to current date starting from 12:00:00 am
    <a href="#" style="float:right" class="close">Close</a>
</div>

<div style="text-align:center;padding:10px">
    <div class="button white">
    
    <form method="post" action="<?php echo base_url();?>billing/generate_manual_invoice/" id="newInvForm"> 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="8%">
                        Customer
                    </td>

                    <td width="8%">
                        Misclleneous Charges
                    </td>
                    
                    <td width="1%" rowspan="2">
                        <input type="submit" id="searchFilter" name="searchFilter" value="Generate Invoice Till Today 12:00:00 am" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <select name="new_inv_customer" id="new_inv_customer">
                            <?php echo customer_drop_down_generate_invoice();?>
                        </select>
                    </td>
                    
                    <td><input type="text" name="misc_charges" id="misc_charges" class="numeric"></td>
                </tr>
            
        </table>
    </form>
    </div>
</div>

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
                        <td width="7%" align="center">Mark Paid</td>
                    </tr>
                    
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
                                <td align="center"><?php echo anchor_popup('customers/edit_customer/'.$row->customer_id.'', customer_full_name($row->customer_id), $atts); ?></td>
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
                                
                                <?php if($latest_status == 'pending' || $latest_status == 'over_due') {?>
                                <td align="center" height="30"><a href="<?php echo base_url(); ?>billing/mark_as_paid/<?php echo $row->id; ?>">Mark as Paid</a></td>
                                <?php } else {?>
                                <td align="center" height="30">-</td>
                                <?php } ?>
                            </tr>
                        <?php endforeach;?>
                           
                    <?php } else { echo '<tr><td align="center" style="color:red;" colspan="13">No Results Found</td></tr>'; } ?>
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
        
        $('.close').live('click', function(){
            $('.info').fadeOut('slow');
             return false;
        });
        
        $('#newInvForm').submit(function(){
            var new_inv_customer = $('#new_inv_customer').val();
            if(new_inv_customer == '')
            {
                $('.success').hide();
                $('.error').html('Please select customer');
                $('.error').fadeOut();
                $('.error').fadeIn();
                document.getElementById('err_div').scrollIntoView();
                return false;
            }
            else
            {
                return true;
            }
        });
    </script>
