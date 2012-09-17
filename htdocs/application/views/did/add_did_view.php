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
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
    <tbody><tr>
        <td width="21" height="35"></td>
        <td width="825" class="heading">
            New DID            </td>
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

    <tr>
        <td align="center" height="20" colspan="3">
            <form enctype="multipart/form-data"  method="post" action="" name="adddid" id="adddid">
                <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                    <tbody>
                    <tr>
                        <td align="right" width="45%"><span class="required">*</span> DID Number:</td>
                        <td align="left" width="55%"><input type="text" value="" name="didnumber" id="didnumber" maxlength="50" class="textfield"></td>
                    </tr>
                    <tr>
                        <td align="right" width="45%"><span class="required">*</span> Carrier Name:</td>
                        <td align="left">
                            <select name="carrierid" id="carrierid" class="textfield carrierid">
                                <?php echo show_carrier_select_box();?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="45%"><span class="required">*</span> Customer Name:</td>
                        <td align="left">
                            <select name="customerid" id="customerid" class="textfield customerid">
                                <?php echo customer_drop_down();?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><input border="0" id="submitadddidForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    </tr>
                    </tbody></table>
            </form>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td height="5"></td>
        <td></td>
        <td></td>
    </tr>


    <tr>
        <td height="20" colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    </tbody></table>

<script type="text/javascript">
    $('#adddid').submit(function(){
        //show wait msg
        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
        });

        var didnumberx = $('#didnumber').val();
        var customeridx = $('#customerid').val();
        var carrieridx = $('#carrierid').val();

        var required_error = 0;

        //common required fields check
        if(didnumberx == '')
        {
            required_error = 1;
        }

        if(customeridx == '')
        {
            required_error = 1;
        }

        if(carrieridx == '')
        {
            required_error = 1;
        }

        var text = "";

        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }

        if(text != '')
        {
            $('.success').hide();
            $('.error').html(text);
            $('.error').fadeOut();
            $('.error').fadeIn();
            document.getElementById('err_div').scrollIntoView();
            $.unblockUI();
            return false;
        }
        else
        {
            var form = $('#adddid').serialize();
            $.ajax({
                type: "POST",
                url: base_url+"did/insert_new_did",
                data: form,
                success: function(html){
                    $('.error').hide();
                    $('.success').html("DID added successfully.");
                    $('.success').fadeOut();
                    $('.success').fadeIn();
                    document.getElementById('success_div').scrollIntoView();
                    $.unblockUI();
                }
            });

            return false;
        }
        return false;
    });

</script>