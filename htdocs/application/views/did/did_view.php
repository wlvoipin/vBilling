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
        <form method="get" action="<?php echo base_url();?>did/index" >
            <table width="612" cellspacing="0" cellpadding="0" border="0" id="filter_table">

                <tr>
                    <td width="170">
                        DID Number
                    </td>
                    <?php if($filter_did_type != 'sub_admin'){?>
                    <td width="170">
                        Customer Name
                    </td>
                    <?php } ?>

                    <td width="170">
                        Carrier Name
                    </td>

                    <td width="150">
                        Type
                    </td>

                    <td width="76" rowspan="2">
                        <input type="submit" name="searchFilter" value="SEARCH" class="button blue" style="float:right;margin-top:5px;margin-right:10px" />
                    </td>

                    <td width="46" rowspan="2">
                        <a href="#" id="reset" class="button orange" style="float:left;margin-top:5px;">RESET</a>
                    </td>

                </tr>

                <tr>
                    <td><input type="text" id="filter_did_number" name="filter_did_number" value="<?php echo $filter_did_number;?>" /></td>
                <?php if($filter_did_type != 'sub_admin'){ ?>
                    <td><input type="text" id="filter_customer_id" name="filter_customer_id" value="<?php echo $filter_customer_id; ?>" /></td>
                    <td><input type="text" id="filter_carrier_id" name="filter_carrier_id" value="<?php echo $filter_carrier_id;?>" /></td>
                <?php } ?>
                    <td>
                        <select name="filter_enabled" id="filter_enabled" style="width:150px;">
                            <option value="">Select</option>
                            <option value="1" <?php if($filter_enabled == '1'){ echo "selected";}?>>Enabled</option>
                            <option value="0" <?php if($filter_enabled == '0'){ echo "selected";}?>>Disabled</option>
                        </select>
                    </td>

                </tr>

            </table>
        </form>
    </div>
</div>
<!--***************** END FILTER BOX ****************************-->

<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr class="bottom_link">
                    <td height="20" width="5%" align="center">&nbsp;</td>
                    <td width="16%" align="left">Carrier Name</td>
                    <td width="16%" align="left">Customer Name</td>
                    <td width="12%" align="left">DID Number</td>
                    <td width="16%" align="left">ACL IP Address</td>
                    <td width="23%" align="center"> (Enable/Disable)</td>
                    <td width="31%" align="left">Delete</td>
                </tr>
                <?php if($filter_did_type != 'sub_admin'){?>
                <tr><td colspan="7" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    <?php } else { ?>
                <tr><td colspan="6" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    <?php } ?>


                <?php if($did->num_rows() > 0) {?>

                    <?php foreach ($did->result() as $row): ?>
                    <tr class="main_text">
                        <td align="center">&nbsp;</td>

                        <?php if($row->type != 'sub_admin'){?>
                        <td align="left"><?php echo carrier_any_cell($row->carrier_id, 'carrier_name'); ?></td>
                        <td align="left"><?php echo customer_any_cell($row->customer_id, 'customer_firstname').' '.customer_any_cell($row->customer_id, 'customer_lastname'); ?></td>
                        <td align="left"><a href="<?php echo base_url();?>did/edit_did/<?php echo $row->did_id;?>"><?php echo $row->did_number; ?></a></td>
                        <td align="left"><?php echo $row->acl_ip; ?></td>
                        <td align="center"><input type="checkbox" id="<?php echo $row->did_id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
                        <td align="left"><a href="#" id="<?php echo $row->did_id;?>" class="delete_did"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                        <?php } else { ?>
                            <td width="3%" align="left"><?php echo $row->did_number; ?></td>
                            <td width="4%" align="center"><input type="checkbox" id="<?php echo $row->did_id;?>" class="enable_checkbox" <?php if($row->enabled == 1){ echo 'checked="checked"';}?>/></td>
                            <td width="6%" align="left"><a href="#" id="<?php echo $row->did_id;?>" class="delete_did"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;margin-left:15px;border:none;cursor:pointer;" /></a></td>
                        <?php } ?>
                    </tr>

                        <?php if($filter_did_type != 'sub_admin'){?>
                            <tr style="height:5px;"><td colspan="7" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php } else { ?>
                            <tr style="height:5px;"><td colspan="5" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                        <?php } ?>

                        <?php endforeach; ?>

                    <?php
                }
                else
                {
                    $colspan = 5;
                    if($filter_did_type != 'sub_admin'){
                        $colspan = 7;
                    }
                    echo '<tr><td align="center" colspan="'.$colspan.'" style="color:red;">No Results Found</td></tr>';
                }
                ?>
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

<div id="dialog-confirm-enable" title="Enable the DID?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Enable this DID?</p>
</div>

<div id="dialog-confirm-disable" title="Disable the DID?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Disable this DID?</p>
</div>

<div id="dialog-confirm-delete" title="Delete the DID?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are You Sure Want To Delete this DID?</p>
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
                        var data  = 'did_id='+id+'&status=1';
                        $.ajax({
                            type: "POST",
                            url: base_url+"did/enable_disable_did",
                            data: data,
                            success: function(html){
                                $( "#dialog-confirm-enable" ).dialog( "close" );
                                $('.success').html("DID Enabled Successfully.");
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
                        var data  = 'did_id='+id+'&status=0';
                        $.ajax({
                            type: "POST",
                            url: base_url+"did/enable_disable_did",
                            data: data,
                            success: function(html){
                                $( "#dialog-confirm-disable" ).dialog( "close" );
                                $('.success').html("DID Disabled Successfully.");
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

    $('.delete_did').live('click', function(){
        var id = $(this).attr('id');
        var curr_row = $(this).parent().parent();

        $( "#dialog-confirm-delete" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            buttons: {
                "Continue": function() {
                    var data  = 'did_id='+id;
                    $.ajax({
                        type: "POST",
                        url: base_url+"did/delete_did",
                        data: data,
                        success: function(html){
                            $( "#dialog-confirm-delete" ).dialog( "close" );
                            curr_row.fadeOut();
                            $('.success').html("DID Deleted Successfully.");
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                        }
                    });
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });

        return false;
    });

    $('#reset').live('click', function(){
        $('#filter_table input[type="text"]').val('');
        $('#filter_table select').val('');
        return false;
    });

    $('#new_did_type').change(function(){
        if($(this).val() != '')
        {
            $('#new_did_type_form').submit();
        }
    });
</script>
