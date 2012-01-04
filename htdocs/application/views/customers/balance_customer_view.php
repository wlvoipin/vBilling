<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Customer Balance History            </td>
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
              
<tr>
    <td align="center" height="20" colspan="3">
        <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <thead>
                    
                    <tr class="main_text balance_form" style="display:none;">
                        <td colspan="3">
                            <form enctype="multipart/form-data"  method="post" action="" name="addSubtractBlnce" id="addSubtractBlnce">
                                <input type="hidden" name="customer_id" value="<?php echo $customer_id;?>" />
                                
                                <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                                    <tbody>
                                        <tr>
                                            <td align="right" width="45%"><span class="required">*</span> Balance to add/deduct:</td>
                                            <td align="left" width="55%"><input type="text" value="" name="balance" id="balance" maxlength="50" class="textfield"></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><span class="required">*</span> Action:</td>
                                            <td align="left">
                                                <select  name="action" id="action" class="textfield">
                                                    <option value="added">Add Balance</option>
                                                    <option value="deducted">Deduct Balance</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" colspan="2"><input border="0" id="submitaddSubtractBlnceForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </td>
                    </tr>
                    
                    <tr class="main_text">
                        <td align="right" colspan="3"><a href="#" class="add_deduct_balance">Add/Deduct Balance</a></td>
                    </tr>
                    
                    <tr class="bottom_link">
                        <td width="34%" align="center">Date</td>
                        <td width="33%" align="center">Modified Balance</td>
                        <td width="33%" align="center">Action</td>
                    </tr>
                </thead>
                
                <tbody id="dynamic">
                            <?php if($history->num_rows() > 0) {?>
                                <?php foreach($history->result() as $row){ ?>
                                
                                    <tr class="main_text">
                                        <td align="center"><?php echo date('Y-m-d', $row->date); ?></td>
                                        <td align="center"><?php echo $row->balance; ?></td>
                                        <td align="center"><?php echo strtoupper($row->action); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                
                                <tr class="main_text"><td align="center" colspan="3" style="color:red;">No Records Found</td></tr>
                            <?php } ?>
                    
                </tbody>
            </table>
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
    $('#addSubtractBlnce').submit(function(){
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
                
        var balance = $('#balance').val();
        var action = $('#action').val();
        
        var required_error = 0;
        
        //common required fields check
        if(balance == '' || action == '')
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
           var form = $('#addSubtractBlnce').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"customers/add_deduct_balance",
					data: form,
                    success: function(html){
                        $('.error').hide();
                        $('.success').html("Customer Balance "+action+" successfully.");
                        $('.success').fadeOut();
                        $('.success').fadeIn();
                        document.getElementById('success_div').scrollIntoView();
                        $.unblockUI();
                        
                        $("#dynamic").prepend(html);
                    }
				});
                
            return false;
        }
        return false;
    });
    
    $('.add_deduct_balance').live('click', function(){
        $(".balance_form").toggle();
        return false; 
    });
</script>