<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.mcdropdown.css" />
<br/>
<div class="success" id="success_div" <?php if($this->session->flashdata('success') == '') { echo 'style="display:none;"'; }?>><?php echo $this->session->flashdata('success');?> </div>
<div class="error" id="err_div" style="display:none;"></div>
<!--POP UP ATTRIBUTES-->
<?php 
    $atts = array(
                  'width'      => '1000',
                  'height'     => '800',
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
                        <?php 
                            if($filter_contents == 'all')
                            {
                                echo admin_cdr_cust_select_all();
                            }
                            else if($filter_contents == 'my')
                            {
                                echo admin_cdr_cust_select_my();
                            }
                        ?>
                        <input type="text" name="filter_customers" id="filter_customers" value="" />
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
                <!--***hidden field for filter contents *******-->
                <input type="hidden" name="filter_contents" id="filter_contents" value="<?php echo $filter_contents;?>"/>
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->



<?php if($this->session->userdata('user_type') == 'admin'){?>
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
                            <input type="submit" id="sssearchFilter" name="sssearchFilter" value="Generate Invoice Till Today 12:00:00 am" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
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
<?php 
    } else if($this->session->userdata('user_type') == 'sub_admin'){
            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'generate_invoices') == 1)
            {
?>
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
                                        <input type="submit" id="ssssearchFilter" name="ssssearchFilter" value="Generate Invoice Till Today 12:00:00 am" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
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
<?php 
            }
        }
?>
         
                                
<table width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <style>
                        .sbHolder{
                            width:250px;
                        }
                        .sbOptions{
                            width:250px;
                        }
                    </style>
                    <tr>
                        <td colspan="13">
                            <div style="float:right;height:55px">
							    <div class="button white">
                            <select id="filter_contents_select">
                                <option value="all" <?php if($filter_contents == 'all'){ echo "selected";}?>>All Customers / Resellers</option>
                                <option value="my" <?php if($filter_contents == 'my'){ echo "selected";}?>>My Customers / Resellers</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    
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
                    <tr><td colspan="13" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
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
                                
                                
                                <?php if($row->parent_id == '0'){?>
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <?php if($latest_status == 'pending' || $latest_status == 'over_due') {?>
                                    <td align="center" height="30"><a href="<?php echo base_url(); ?>billing/mark_as_paid/<?php echo $row->id; ?>">Mark as Paid</a></td>
                                    <?php } else {?>
                                    <td align="center" height="30">-</td>
                                    <?php } ?>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'mark_invoices_paid') == 1)
                                            {
                                ?>
                                                <?php if($latest_status == 'pending' || $latest_status == 'over_due') {?>
                                                <td align="center" height="30"><a href="<?php echo base_url(); ?>billing/mark_as_paid/<?php echo $row->id; ?>">Mark as Paid</a></td>
                                                <?php } else {?>
                                                <td align="center" height="30">-</td>
                                                <?php } ?>
                                <?php 
                                            }
                                            else
                                            {
                                ?>
                                                <td align="center" height="30">-</td>
                                <?php
                                            }
                                        }
                                ?>
                                
                                <?php } else {?>
                                    <td align="center" height="30">-</td>
                                <?php } ?>
                            </tr>
                            <tr style="height:5px;"><td colspan="13" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
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
    
    <!--****FILTER CONTENTS CHANGE BEHAVIOR ***********-->
		<script type="text/javascript">
		$(function () {
			$("#filter_contents_select").selectbox({
                onChange: function (val, inst) {
                    
                    //reset the searach form 
                    $('#filter_table input[type="text"]').val('');
                    $('#filter_table select').val('');
                    
                    //put the selected value in the hidden search form field 
                    $('#filter_contents').val(val);
                    
                    //click the submit button of search form
                    $('#searchFilter').click();
                }
            });
		});
		</script>
    
    <!--**************************Multi DropDown Select Box ************************-->
         <script src="<?php echo base_url();?>assets/js/jquery.mcdropdown.js" type="text/javascript"></script>
         <script src="<?php echo base_url();?>assets/js/jquery.bgiframe.js" type="text/javascript"></script>
         <script type="text/javascript">
        <!--//
        // on DOM ready
        $(document).ready(function (){
            $("#filter_customers").mcDropdown("#quick_customer_filter_list");
            
            //this is to make the option selected 
            var dd = $("#filter_customers").mcDropdown();
            dd.setValue(<?php echo $filter_customers;?>);
            
            //woraround for fixing the input width of mcDropDown
            $('div.mcdropdown input[type="text"]').css("width","129px");
        });
        //-->
        </script>
        <!--************************END*************************-->
