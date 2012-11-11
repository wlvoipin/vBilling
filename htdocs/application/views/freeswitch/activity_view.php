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
* VOIP Services [<] info at voipservices.co.za [>]
* Portions created by Initial Developer (VOIP Services) are Copyright (C) 2011
* Initial Developer (VOIP Services). All Rights Reserved.
*
* Contributor(s)
* "Digital Linx - <vbilling at digitallinx.com>"
*
* vBilling - VoIP Billing and Routing Platform
* version 0.1.3
*
*/
?>
<head>
<script src="<?php echo base_url();?>assets/js/tablefilter.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.mcdropdown.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.bgiframe.js" type="text/javascript"></script>


<script type="text/javascript">
    $('.cutcalls').live('click', function(){
        var id = $(this).attr('id');
        var curr_row = $(this).parent().parent();

        $( "#dialog-confirm-delete" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            buttons: {
                "Continue": function() {
                    var data  = 'uuid='+id;
                    $.ajax({
                        type: "POST",
                        url: base_url+"freeswitch/cutcall",
                        data: data,
                        success: function(html){
                            $( "#dialog-confirm-delete" ).dialog( "close" );
                            curr_row.fadeOut();
                            $('.success').html("Call Disconnected Successfully.");
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
            }
        });
    });

    // on DOM ready
    $(document).ready(function (){
        $("#filter_customers").mcDropdown("#quick_customer_filter_list");

//woraround for fixing the input width of mcDropDown
        $('div.mcdropdown input[type="text"]').css("width","114px");
    });


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

    $('#searchFilter').click(function(){
        $('#filterForm').attr('action', ''+base_url+'activity/');
    });

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

<style type="text/css" media="screen">
    @import "<?php echo base_url();?>assets/css/filtergrid.css";
    @import "<?php echo base_url();?>assets/css/style.css";

    .sbHolder{
        width:250px;
    }
    .sbOptions{
        width:250px;
    }
</style>
</head>

<body>
<br/>
<br/>
    <div width="825" style="margin-left: 20px; color: #c2414d; font-family: arial; font-size: 18px; font-weight: bold;">
        Realtime Call Activity (Reload page to refresh)
    </div>
<br/>
<br/>
<div class="success" id="success_div" style="display:none;"></div>
<div id="dialog-confirm-delete" title="Disconnect The Call?" style="display:none;">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure want to disconnect this call?
	</p>
</div>

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

<table id="table1" width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr class="bottom_link">
                <td height="20" width="8%" align="center">UUID</td>
                <td width="7%" align="center">Direction</td>
                <td width="7%" align="center">Customer</td>
                <td width="4%" align="center">CLI</td>
                <td width="7%" align="center">Destination</td>
                <td width="3%" align="center">State</td>
                <td width="7%" align="center">Gateway</td>
                <td width="7%" align="center">Delay</td>
                <td width="7%" align="center">Duration</td>
                <td width="7%" align="center">Action</td>
            </tr>
            <tr>
                <td colspan="16" id="shadowDiv" style="height:5px;margin-top:-1px"></td>
            </tr>

            <?php if(get_calls_count() != 0) {
                $x = 0; ?>
                <?php while ($x < get_calls_count()){ ?></td>
                    <?php $uuid = get_attribute_uuid($x); ?>
                    <tr class="main_text">
                        <td align="center"><?php echo $uuid; ?></td>
                        <td align="center"><?php echo get_attribute('direction'); ?></td>
                        <td align="center">
							<?php $customer_acc_num = get_uuid_dump($uuid, 'variable_customer_acc_num');
							if (get_attribute('direction') == 'inbound')
							{
								$customer_acc_num =  get_did_customer(get_attribute('dest'));
								echo $customer_acc_num;
							}
							else
							{
								echo $customer_acc_num;
							}
							?>
						</td>
                        <td align="center"><?php echo get_uuid_dump($uuid, 'Caller-ANI'); ?></td>
                        <td align="center"><?php echo get_attribute('dest'); ?></td>
                        <td align="center"><?php echo get_attribute('callstate'); ?></td>

                        <td align="center"><?php if (get_attribute('direction') == 'inbound') {
                                                    echo "Caller Network";
                                                }
                                                else {
                                                    echo get_uuid_dump($uuid, 'variable_gateway');
                                                } ?>
                        </td>
                        <td align="center"><?php if (get_attribute('direction') == 'inbound') {
                            echo "Caller Network";
                        }
                        else {
							if (get_uuid_dump($uuid, 'Caller-Channel-Progress-Media-Time') > 0) {
                            $diff = get_uuid_dump($uuid, 'Caller-Channel-Progress-Media-Time') - get_uuid_dump($uuid, 'Caller-Channel-Created-Time'); $res = round($diff / 1000000,2); echo $res .' sec';
							}
							else echo 0;
                        } ?>
                        </td>
                        <td align="center"><?php $diff=time()-strtotime(get_attribute('created'));
                                                 $hours = floor($diff / 3600);
                                                 $mins = floor(($diff - ($hours*3600)) / 60);
                                                 $seconds = floor(($diff - ($hours*3600) - ($mins*60)));
                                                 echo $hours. ':' .$mins. ':' .$seconds; ?>
                        </td>

                        <?php if($this->session->userdata('user_type') == 'admin'){?>
                            <td align="center">
                                <a href="#" id="<?php echo $uuid; ?>" class="cutcalls"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                            </td>
                        <?php } else if($this->session->userdata('user_type') == 'sub_admin') {
                            if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'cut_calls') == 1) { ?>
                                <td align="center">
                                    <a href="#" id="<?php echo $uuid;?>" class="cutcalls"><img src="<?php echo base_url();?>assets/images/button_cancel.png" style="width:16px;border:none;cursor:pointer;" /></a>
                                </td>
                            <?php } else { ?>
                                <td align="center">---</td>
                            <?php }
                        } ?>
                    </tr>
        <!--    <td align="center"><?php // $uuid = get_attribute_xml(); echo $uuid; ?></td>-->
        <!--  <td align="center"><?php // print_r (get_uuid_dump_xml($uuid)); ?></td>-->

        <?php $x = $x + 1;
                } ?>
                    <tr style="height:5px;">
                        <td colspan="16" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td>
                    </tr>
            <?php } else { echo '<tr><td align="center" style="color:red;" colspan="16">No Active Calls</td></tr>'; } ?>

        </table>
		<?
		if (get_calls_count() > 0){
		?>
<script language="javascript" type="text/javascript">
    var table1Filters = {
        col_1: "select",
        col_2: "select",
        col_5: "select",
        col_6: "select",
        col_7: "select",
        col_11: "none"
    }
    setFilterGrid("table1",0,table1Filters);
</script>
<?}?>
</body>
