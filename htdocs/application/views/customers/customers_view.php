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
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>customers/" > 
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="17%">
                        Account Num
                    </td>

                    <td width="17%">
                        Company
                    </td>

                    <td width="17%">
                        First Name
                    </td>

                    <td width="17%">
                        Type
                    </td>
                    
                    <td width="17%">
                        Sort By
                    </td>
                    
                    <td width="17%" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="11%" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td><input type="text" id="filter_account_num" name="filter_account_num" value="<?php echo $filter_account_num;?>" class="numeric" maxlength="10"></td>
                    <td><input type="text" id="filter_company" name="filter_company" value="<?php echo $filter_company;?>" maxlength="50"></td>
                    <td><input type="text" id="filter_first_name" name="filter_first_name" value="<?php echo $filter_first_name;?>" maxlength="50"></td>
                    <td>
                        <select name="filter_type" id="filter_type">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_type == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_type == '0'){ echo "selected";}?>>Disabled</option>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort">
                            <option value="">Select</option>
                            <option value="name_asc" <?php if($filter_sort == 'name_asc'){ echo "selected";}?>>Name - ASC</option>
                            <option value="name_dec" <?php if($filter_sort == 'name_dec'){ echo "selected";}?>>Name - DESC</option>
                            <option value="balance_asc" <?php if($filter_sort == 'balance_asc'){ echo "selected";}?>>Balance - ASC</option>
                            <option value="balance_dec" <?php if($filter_sort == 'balance_dec'){ echo "selected";}?>>Balance - DESC</option>
                        </select>
                    </td>
                </tr>
            
        </table>
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table  width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="13" width="8%" align="center">Account No</td>
                        <td width="13%" align="left">Company</td>
                        <td width="13%" align="left">Name</td>
                        <td width="13%" align="left">Email</td>
                        <td width="6%" align="left">Country</td>
                        <td width="13%" align="center">Phone</td>
                        <td width="10%" align="center">Balance($)</td>
                        <td width="9%" align="center">Enabled</td>
                    </tr>
                    
                    <tr><td colspan="8" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($customers->num_rows() > 0) {?>
                        
                        <?php foreach ($customers->result() as $row): ?>
                            <tr class="main_text">
                                
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <td height="25" align="center"><?php echo anchor_popup('customers/edit_customer/'.$row->customer_id.'', $row->customer_acc_num, $atts); ?></td>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'edit_customers') == 1)
                                            {
                                ?>
                                                <td height="25" align="center"><?php echo anchor_popup('customers/edit_customer/'.$row->customer_id.'', $row->customer_acc_num, $atts); ?></td>
                                <?php 
                                            }
                                            else
                                            {
                                ?>
                                                <td height="25" align="center"><?php echo $row->customer_acc_num; ?></td>
                                <?php
                                            }
                                        }
                                ?>
                                
                                <td align="left"><?php echo $row->customer_company; ?></td>
                                <td align="left"><?php echo $row->customer_firstname; ?></td>
                                <td align="left"><?php echo $row->customer_contact_email; ?></td>
                                <td align="left"><?php echo country_any_cell($row->customer_country, 'countryname'); ?></td>
                                <td align="center"><?php echo $row->customer_phone; ?></td>
                                <td align="center"><?php echo $row->customer_balance; ?></td>
                                
                                <?php if($this->session->userdata('user_type') == 'admin'){?>
                                    <td align="center"><input type="checkbox" id="<?php echo $row->customer_id;?>" class="enable_checkbox" <?php if($row->customer_enabled == 1){ echo 'checked="checked"';}?>/></td>
                                <?php 
                                    } else if($this->session->userdata('user_type') == 'sub_admin'){
                                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'enable_disable_customers') == 1)
                                            {
                                ?>
                                                <td align="center"><input type="checkbox" id="<?php echo $row->customer_id;?>" class="enable_checkbox" <?php if($row->customer_enabled == 1){ echo 'checked="checked"';}?>/></td>
                                <?php 
                                            }
                                            else
                                            {
                                ?>
                                                <td align="center"><?php if($row->customer_enabled == 1){ echo 'Enabled';} else{ echo "Disabled";}?></td>
                                <?php
                                            }
                                        }
                                ?>
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
    
    <div id="dialog-confirm-enable" title="Enable The Customer?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable This Customer?</p>
    </div>
    
    <div id="dialog-confirm-disable" title="Disable The Customer?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable This Customer?</p>
    </div>

    <script type="text/javascript">
        $('.enable_checkbox').click(function(){
            var curr_chk = $(this);
            var id = $(this).attr('id');
            var enable = '';
            
            if ($(this).is(':checked'))
            {
                enable = 1;
            }
            else
            {
                enable = 0;
            }
            
            if(enable == 1)
            {
                $( "#dialog-confirm-enable" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'customer_id='+id+'&status=1';
                            $.ajax({
                                type: "POST",
                                url: base_url+"customers/enable_disable_customer",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-enable" ).dialog( "close" );
                                    $('.success').html("Customer Enabled Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            curr_chk.attr('checked', false);
                        }
                    }
                });
            }
            else
            {
                $( "#dialog-confirm-disable" ).dialog({
                    resizable: false,
                    height:180,
                    modal: true,
                    buttons: {
                        "Continue": function() {
                            var data  = 'customer_id='+id+'&status=0';
                            $.ajax({
                                type: "POST",
                                url: base_url+"customers/enable_disable_customer",
                                data: data,
                                success: function(html){
                                    $( "#dialog-confirm-disable" ).dialog( "close" );
                                    $('.success').html("Customer Disabled Successfully.");
                                    $('.success').fadeOut();
                                    $('.success').fadeIn();
                                    document.getElementById('success_div').scrollIntoView();
                                }
                            });
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                            curr_chk.attr('checked', true);
                        }
                    }
                });
            }
        });
        
        $('.numeric').numeric();
        
        $('#reset').live('click', function(){
            $('#filter_account_num').val('');
            $('#filter_company').val('');
            $('#filter_first_name').val('');
            $('#filter_type').val('');
            return false;
        });
    </script>
    