<?php 
    $allowedExtensions = array("csv","CSV"); //allowed extensions
    $maxSize = 512000; // 500 kb
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
                    
                    $count = 1;
                    $row = 0;
                     while (($data = fgetcsv($handle, $_FILES['csvfile']['size'], ",")) !== FALSE)
                     {
                        $sql = "INSERT INTO ".$group_table_name." (digits, sell_rate, cost_rate, buy_initblock, sell_initblock, carrier_id) VALUES ('".mysql_real_escape_string($data[$row])."', '".mysql_real_escape_string($data[$row + 1])."', '".mysql_real_escape_string($data[$row + 2])."', '".mysql_real_escape_string($data[$row + 3])."', '".mysql_real_escape_string($data[$row + 4])."', '".$carrier."')";
                        $this->db->query($sql);
                        
                        $count = $count + 1;
                        if($count == 6)
                        {
                            $count = 1;
                            $row = $row + 6;
                        }
                     }
               
                     fclose($handle);
                     $msg = "File Imported Successfully.";
                 }
                 else
                 {
                    $err = "ERROR: File size is grater than 500 KB.";
                 }
            }
            else
            {
                $err = "ERROR: Invalid CSV File.";
            }
        }
        else
        {
            $err = "ERROR: This group does not have rate table associated with it.";
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
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <tbody>
                
                <tr>
                    <td align="right" width="45%"><span class="required">*</span> CSV File:</td>
                    <td align="left" width="55%"><input type="file" name="csvfile" id="csvfile" class="textfield"><a href="<?php echo base_url();?>assets/css/Book1.csv" style="margin-left:10px; font-size:10px;" >Dowload Sample CSV</a></td>
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
                    <td align="right"><span class="required">*</span> Group:</td>
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
    $('#importRate').submit(function(){
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
                                return true;
                            }
                    }
				});
        }
        return false;
    });
    
</script>