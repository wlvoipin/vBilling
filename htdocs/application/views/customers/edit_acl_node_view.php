<script type="text/javascript">
if(!window.opener){
window.location = '../../home/';
}
</script>
<?php 
    $row = $acl_node->row();
?>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tbody><tr>
            <td width="21" height="35"></td>
            <td width="825" class="heading">
            Update ACL Node            </td>
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
        <form enctype="multipart/form-data"  method="post" action="" name="addAclNode" id="addAclNode">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
                
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>"/>
                <input type="hidden" name="node_id" id="node_id" value="<?php echo $acl_node_id;?>"/>
                
                <tbody>
                
                <tr>
                    <?php 
                        $cidr_split = explode('/', $row->cidr);
                    ?>
                    <td align="right" width="45%"><span class="required">*</span> IP:</td>
                    <td align="left" width="55%"><input type="text" name="ip" id="ip" value="<?php echo $cidr_split[0];?>" maxlength="50" class="textfield numeric"></td>
                </tr>
                <tr>
                    <td align="right"><span class="required">*</span> CIDR:</td>
                    <td align="left">
                        <select  name="cdr" id="cdr" class="textfield">
                            <?php 
                            for($i=0; $i<=32; $i++){
                                if($i == $cidr_split[1])
                                {
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                            }
                            ?>
                               
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td align="right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input border="0" id="submitaddAclNodeForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                    
                    
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
    
    
    $('#addAclNode').submit(function(){
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
                
        var ip = $('#ip').val();
        var cdr = $('#cdr').val();
        
        var pattern = /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/;
        
        var required_error = 0;
        var ip_error = 0;
        
        //common required fields check
        if(ip == '' || cdr == '')
        {
            required_error = 1;
        }
        
        if(ip != '')
        {
            if(!pattern.test(ip))
            {
                ip_error = 1;
            }
        }
        
        var text = "";
        
        if(required_error == 1)
        {
            text += "Fields With * Are Required Fields<br/>";
        }
        
        if(ip_error == 1)
        {
            text += "Invalid IP Address<br/>";
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
           var form = $('#addAclNode').serialize();
            $.ajax({
                    type: "POST",
					url: base_url+"customers/update_acl_node_db",
					data: form,
                    success: function(html){
                        $('.error').hide();
                        $('.success').html("Customer ACL Node Updated Successfully.");
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
    
    $('.numeric').numeric({allow:"."});
    
</script>