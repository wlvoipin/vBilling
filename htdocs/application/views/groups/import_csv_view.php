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
<?php 
    $allowedExtensions = array("csv","CSV"); //allowed extensions
    $maxSize = 5096000; // ~5 MB
    $msg = "";
    $err = "";
    if(isset($_POST['group']) && isset($_POST['carrier']))
    {
        $fname = $_FILES['csvfile']['name'];
        $group = $_POST['group'];
        $carrier = $_POST['carrier'];
        $group_table_name = $this->groups_model->group_any_cell($group, 'group_rate_table');
		$ext = pathinfo($fname);
        
        if($group_table_name != '')
        {
            if(in_array($ext['extension'],$allowedExtensions))
            {
                if($_FILES['csvfile']['size'] <= $maxSize)
                {
                    $filename = $_FILES['csvfile']['tmp_name'];
                    $handle = fopen($filename, "r");
                    
                     while (($data = fgetcsv($handle, $_FILES['csvfile']['size'], ",")) !== FALSE)
                     {
                        if(count($data) == 5)
                        {
							$sql = "INSERT INTO ".$group_table_name." (digits, sell_rate, cost_rate, buy_initblock, sell_initblock, carrier_id, lcr_profile, quality, reliability, enabled, lrn) VALUES ('".mysql_real_escape_string($data[0])."', '".mysql_real_escape_string($data[1])."', '".mysql_real_escape_string($data[2])."', '".mysql_real_escape_string($data[3])."', '".mysql_real_escape_string($data[4])."', '".$carrier."', '0', '0', '0', '1', '0')";
                            $this->db->query($sql);
                        }
                     }
               
                     fclose($handle);
                     $msg = "File Imported Successfully.";
                 }
                 else
                 {
                    $err = "ERROR: File size is greater than 4 MB";
                 }
            }
            else
            {
                $err = "ERROR: Invalid CSV File.";
            }
        }
        else
        {
            $err = "ERROR: This rate group does not have rate table associated with it.";
        }
    }
?>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Import Rate Using CSV</td>
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
        <td colspan="3"><div class="error" id="err_div" <?php if($err == '') { echo 'style="display:none;"'; }?>><?php echo $err;?></div></td>
        </tr>
        
        <tr>
        <td colspan="3"><div class="success" id="success_div" <?php if($msg == '') { echo 'style="display:none;"'; }?>><?php echo $msg;?></div></td>
        </tr>
              
<tr>
    <td align="center" height="20" colspan="3">
        <form enctype="multipart/form-data"  method="post" action="" name="importRate" id="importRate">
            <table cellspacing="3" cellpadding="2" border="0" width="100%" class="search_col">
                
                <tbody>
                
                <tr>
                    <td align="right" width="312"><span class="required">*</span> CSV File:</td>
                    <td align="left" width="383"><input type="file" name="csvfile" id="csvfile" class="textfield"><a href="<?php echo base_url();?>assets/css/Book1.csv" style="margin-left:10px; font-size:10px;" >Dowload Sample CSV</a></td>
                </tr>
                
                <tr>
                    <td align="right"><span class="required">*</span> Carrier:</td>
                    <td align="left">
                        <select id="carrier" name="carrier" class="textfield">
                            <?php echo show_carrier_select_box_valid_invalid();?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> Rate Group:</td>
                    <td align="left">
                        <select id="group" name="group" class="textfield">
                            <?php echo show_group_select_box();?>
                        </select>
                    </td>
                </tr>
                
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitimportRateForm" name="submitimportRateForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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
    $('#submitimportRateForm').click(function(){
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
                
        var csvfile = $('#csvfile').val();
        var carrier = $('#carrier').val();
        var group = $('#group').val();
        
        
        var required_error = 0;
        
        //common required fields check
        if(csvfile == '' || carrier == '' || carrier == null || group == '' || group == null)
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
           $.ajax({
                    type: "POST",
					url: base_url+"groups/carrier_valid_invalid",
					data: 'carrier_id='+carrier,
                    success: function(html){
                            if(html == 'carrier_invalid')
                            {
                                $('.success').hide();
                                $('.error').html('This Carrier is Invalid.');
                                $('.error').fadeOut();
                                $('.error').fadeIn();
                                document.getElementById('err_div').scrollIntoView();
                                $.unblockUI();
                                return false;
                            }
                            else
                            {
                                $('#importRate').submit();
                                return true;
                            }
                    }
				});
        }
        return false;
    });
    
</script>