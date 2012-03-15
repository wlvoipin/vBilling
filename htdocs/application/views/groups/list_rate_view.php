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
 <div class="success" id="success_div" <?php if($this->session->flashdata('success_message') == '') { echo 'style="display:none;"'; }?>>
 
 <?php 
    if($this->session->flashdata('success_message') != ''){
        echo $this->session->flashdata('success_message');
    }
 ?> 
</div>

<!--********************************FILTER BOX************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;"><?php echo $msg_records_found;?></div>
    <form method="get" action="<?php echo base_url();?>groups/list_rates/" id="global_search"> 
        <table width="1233" cellspacing="0" cellpadding="0" border="0" id="filter_table">
             
                <tr>
                    <td width="172">
                        Groups
                    </td>
                    
                    <td width="172">
                        Carriers
                    </td>
                    
                    <td width="172">
                        Country
                    </td>
                    
                    <td width="173">
                        Country Code
                    </td>

                    <td width="173">
                        Sort By
                    </td>

                    <td width="173" rowspan="2">
                        <input type="submit" name="searchFilter" id="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>
                    
                    <td width="198" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>
                
                </tr>
            
                <tr>
                    <td>
                        <select name="filter_groups" id="filter_groups" style="width:150px;">
                            <?php echo show_group_select_box($filter_groups);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_carriers" id="filter_carriers" style="width:150px;">
                            <?php echo show_carrier_select_box($filter_carriers);?>
                        </select>
                    </td>
                    
                    <td>
                        <select name="filter_country">
                            <?php echo all_countries($filter_country);?>
                        </select>
                    </td>
                    
                    <td><input type="text" name="filter_destination" id="filter_destination" value="<?php echo $filter_destination;?>" /></td>
                    
                    <td>
                        <select name="filter_sort" id="filter_sort" style="width:124px;">
                            <option value="">Select</option>
                            <option value="name_asc" <?php if($filter_sort == 'name_asc'){ echo "selected";}?>>Rate Group - ASC</option>
                            <option value="name_dec" <?php if($filter_sort == 'name_dec'){ echo "selected";}?>>Rate Group - DESC</option>
                        </select>
                    </td>
                    
                </tr>
                
                <tr class="destination_string"><td colspan="7">&nbsp;</td></tr>
                <tr class="destination_string" style="display:none;">
                    <td colspan="7" style="background:#ccc">
                        <table width="100%">
                            
                            <tr>
                                <td><input type="radio" name="destination_advance_filter" value="exact" <?php if($destination_advance_filter == 'exact'){ echo "checked"; }?> /> Exact</td>
                                <td><input type="radio" name="destination_advance_filter" value="contain" <?php if($destination_advance_filter == 'contain'){ echo "checked"; }?> /> Contain</td>
                                <td><input type="radio" name="destination_advance_filter" value="begin" <?php if($destination_advance_filter == 'begin'){ echo "checked"; }?> /> Begin With</td>
                                <td><input type="radio" name="destination_advance_filter" value="end" <?php if($destination_advance_filter == 'end'){ echo "checked"; }?>/> End With</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            
        </table>
            <div id="batch_this" style="display:none;"></div>
            <input type="hidden" name="is_batch" id="is_batch" value="0" /> 
    </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<!--*******************************BATCH UPDATE BOX************************************-->
<div style="text-align:center;padding:10px">
    <div class="button white">
    <div style="color:green; font-weight:bold;">
        <?php
            if($count == 1)
            {
                echo 'Perform Batch Update On Following Records';
            }
            else
            {
                echo 'Perform Batch Update On These '.$count.' Records ?';
            }
        ?>
    </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="batch_table">
                             
                <tr>
                    <td><input type="checkbox" name="is_buy_rate" value="1" class="is" /></td>
                    <td align="left">Buy Rate:</td>
                    <td>
                        <input type="text" name="buy_rate_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_buy_rate" value="equal" checked /> Equal 
                        <input type="radio" name="action_buy_rate" value="add" /> Add 
                        <input type="radio" name="action_buy_rate" value="subtract" /> Subtract
                    </td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="is_buy_block_min" value="1" class="is" /></td>
                    <td align="left">Minimum Buy Block:</td>
                    <td>
                        <input type="text" name="buy_block_min_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_buy_block_min_rate" value="equal" checked /> Equal 
                        <input type="radio" name="action_buy_block_min_rate" value="add" /> Add 
                        <input type="radio" name="action_buy_block_min_rate" value="subtract" /> Subtract
                    </td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="is_buy_init" value="1" class="is"/></td>
                    <td align="left">Buy Init block</td>
                    <td>
                        <input type="text" name="buy_init_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_buy_init" value="equal" checked /> Equal 
                        <input type="radio" name="action_buy_init" value="add" /> Add 
                        <input type="radio" name="action_buy_init" value="subtract" /> Subtract
                    </td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="is_sell_rate" value="1" class="is"/></td>
                    <td align="left">Sell Rate:</td>
                    <td>
                        <input type="text" name="sell_rate_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_sell_rate" value="equal" checked /> Equal 
                        <input type="radio" name="action_sell_rate" value="add" /> Add 
                        <input type="radio" name="action_sell_rate" value="subtract" /> Subtract
                    </td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="is_sell_block_min" value="1" class="is" /></td>
                    <td align="left">Minimum Sell Block:</td>
                    <td>
                        <input type="text" name="sell_block_min_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_sell_block_min_rate" value="equal" checked /> Equal 
                        <input type="radio" name="action_sell_block_min_rate" value="add" /> Add 
                        <input type="radio" name="action_sell_block_min_rate" value="subtract" /> Subtract
                    </td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="is_sell_init" value="1" class="is" /></td>
                    <td align="left">Sell Init Block:</td>
                    <td>
                        <input type="text" name="sell_init_value" class="is_val"/>
                    </td>
                    <td>
                        <input type="radio" name="action_sell_init" value="equal" checked /> Equal 
                        <input type="radio" name="action_sell_init" value="add" /> Add 
                        <input type="radio" name="action_sell_init" value="subtract" /> Subtract
                    </td>
                </tr>
                                
                <tr>
                    <td colspan="4" align="center">
                            <a href="#" id="do_batch" class="button orange" style="margin-top:5px;">Perform Batch Update</a>
                    </td>
                </tr>
        </table>
    </div>
</div>
<!--****************************END BATCH UPDATE BOX **********************************-->

<table  width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td height="20" width="11%" align="center">Group</td>
                        <td width="11%" align="center">Rate Table</td>
                        <td width="8%" align="center">Country Code</td>
                        <td width="11%" align="center">Country</td>
                        <td width="11%" align="center">Carrier</td>
                        <td width="8%" align="center">Buy Rate</td>
                        <td width="8%" align="center">Minimum Buy Block</td>
                        <td width="8%" align="center">Buy Init Block</td>
                        <td width="8%" align="center">Sell Rate</td>
                        <td width="8%" align="center">Minimum Sell Block</td>
                        <td width="8%" align="center">Sell Init Block</td>
                    </tr>
                    <tr><td colspan="11" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($rate->num_rows() > 0) {?>
                        
                        <?php foreach ($rate->result() as $row): ?>
                            <tr class="main_text">
                                <td align="center"><?php echo $row->group_name; ?></td>
                                <td align="center"><?php echo $row->tbl_name; ?></td>
                                <td align="center"><?php echo $row->digits; ?></td>
                                <td align="center"><?php echo $row->country_id; ?></td>
                                <td align="center"><?php echo $row->carrier_id; ?></td>

                                <td align="center"><?php echo $row->cost_rate; ?></td>
                                <td align="center"><?php echo $row->buyblock_min_duration; ?></td>
                                <td align="center"><?php echo $row->buy_initblock; ?></td>

                                <td align="center"><?php echo $row->sell_rate; ?></td>
                                <td align="center"><?php echo $row->sellblock_min_duration; ?></td>
                                <td align="center"><?php echo $row->sell_initblock; ?></td>

                                <tr style="height:5px;"><td colspan="11" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                            </tr>
                        <?php endforeach;?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="11" style="color:red;">No Results Found</td></tr>'; } ?>                    
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
        $('#reset').live('click', function(){
            $('#filter_groups').val('');
            $('#filter_group_type').val('');
            return false;
        });
        
        $('#do_batch').live('click', function(){
            
            var pass = false;
            var msg = "";
            $('.is').each(function(){
                if ($(this).is(':checked'))
                {
                    pass = true;
                }
            });
            
            if(pass)
            {
                $('.is').each(function(){
                    if ($(this).is(':checked'))
                    {
                        if($(this).parent().parent().find('.is_val').val() == '')
                        {
                            msg = "The Selected Operation Values Cannot Be Left Blank.";
                        }
                    }
                });
            }
            else
            {
                msg = "You did not select any operation to perform.";
            }
            
            if(msg == '')
            {
                $('#batch_this').append($('#batch_table'));
                $('#is_batch').val(1);
                $('#searchFilter').click();
            }
            else
            {
                alert(msg);
            }
            
            return false;
        });
        
        $('#filter_destination').click(function(){
            $('.destination_string').show();
            
        });
    </script>
