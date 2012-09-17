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
    $row = $localization_group->row();
?>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
  <tbody>
    <tr>
      <td width="21" height="35"></td>
      <td width="825" class="heading"> Update Localization Group </td>
      <td width="178"><table cellspacing="0" cellpadding="0" width="170" height="42" class="search_col">
          <tbody>
            <tr>
              <td align="center" width="53" valign="bottom">&nbsp;</td>
            </tr>
            <tr>
              <td align="center" width="53" valign="top">&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
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
      <td align="center" height="20" colspan="3"><div class="form-container">
          <form enctype="multipart/form-data"  method="post" action="" name="addLocalizationGroup" id="addLocalizationGroup">
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <input type="hidden" name="localization_id" id="localization_id" value="<?php echo $localization_id; ?>" />
              <tbody>
                <tr>
                  <td align="left" width="10%"><span class="required">*</span><span class="heading">Group</span></td>
                  <td align="left"><input type="text" value="<?php echo $row->name;?>" name="groupname" id="groupname" maxlength="50" class="textfield"></td>
                </tr>
                <tr>
                  <td align="left" width="100%" colspan="2" style="font-size:14px; text-decoration:underline;padding-top:30px;padding-bottom:20px;">Rules Details:</td>
                </tr>
              </tbody>
            </table>
            
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <thead>
                <tr>
                  <th align="left">&nbsp;</th>
                  <th align="left"> Name </th>
                  <th align="left">Cut</th>
                  <th align="left">Add</th>
                  <th align="left">Ena bled</th>
                </tr>
              </thead>
              <tbody id="dynamic">
                <?php 
                        $rowCount = 0;
                    foreach($localization_rules->result() as $rowRules){
                    ?>
               
                <tr class="optional" id="<?php echo $rowCount;?>">
                  <td align="left">&nbsp; </td>
                  <td align="left">*<input type="text" value="<?php echo $rowRules->name;?>" onkeypress='validate(event)' name="pre[]" id="pre_<?php echo $rowCount;?>" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="<?php echo $rowRules->lcut;?>" onkeypress='validate(event)' name="cut[]" id="cut_<?php echo $rowCount;?>" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="<?php echo $rowRules->ladd;?>" onkeypress='validate(event)' name="add[]" id="add_<?php echo $rowCount;?>" maxlength="15" class="textfield"></td>
                  <td align="left">
                  <?php if($rowRules->enabled==1) { $checked = "checked='checked'"; }else{ $checked = '';} ?>
                  
               <input type="checkbox" value="<?php echo $rowRules->enabled;?>" name="enabled[]" id="enabled_<?php echo $rowCount;?>" <?php echo $checked ;?>></td>
                  <td align="left"><img src="<?php echo base_url();?>assets/images/button_cancel.png" class="remove_field" /></td>
                </tr>
                
                <?php 
                            $rowCount = $rowCount + 1;
                        } 
                    ?>
				<?php //if($rowCount == 0){?>
                <tr>                 
                  <td align="left" colspan="5"><img src="<?php echo base_url();?>assets/images/plus.gif" class="add_field" /></td>
                </tr>
                
 <!--               <tr>
                  <td align="left">&nbsp;</td>
                  <td align="left"><input type="text" value="" onkeypress='validate(event)' name="pre[]" id="pre" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="" onkeypress='validate(event)' name="cut[]" id="cut" maxlength="15" class="textfield"></td>
                  <td align="left"><input type="text" value="" onkeypress='validate(event)' name="add[]" id="cut" maxlength="15" class="textfield"></td>
                  <td align="left"><img src="<?php echo base_url();?>assets/images/plus.gif" class="add_field" /></td>
                </tr>-->
                
                <?php //} ?>
              </tbody>
            </table>
            <table cellspacing="3" cellpadding="2" border="0" width="95%" class="search_col">
              <tbody>
                <tr>
                  <td align="center" colspan="2"><input border="0" id="submitaddCarrierForm" type="image" src="<?php echo base_url();?>assets/images/btn-submit.png"></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div></td>
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
  </tbody>
</table>
<script type="text/javascript">

    var prev_val = '';
    var main_count = <?php echo $rowCount - 1; ?>;
    $('.add_field').live('click', function(){        
       main_count = main_count + 1;       
       $("#dynamic").append('<tr class="optional" id="'+main_count+'"><td align="left"></td><td align="left"><input type="text" value="" name="pre[]" id="pre_'+main_count+'" maxlength="50" class="textfield"></td><td align="left">*<input type="text" value="" name="cut[]" id="cut_'+main_count+'" maxlength="50" class="textfield"></td><td align="left"><input type="text" value="" name="add[]" id="add_'+main_count+'" maxlength="50" class="textfield"></td><td align="left"><input type="checkbox" value="0" name="enabled[]" id="enabled_'+main_count+'"></td><td align="left"><img src="<?php echo base_url();?>assets/images/button_cancel.png" class="remove_field" /></td></tr>');      
           
    });
    
    $('.remove_field').live('click', function(){
        $('.error').fadeOut();
        $(this).parent().parent().remove();
    });
	
	function helperCheckSameCutAndAdd(argid,cut,add){
		var isSameRule = 0;
		$('.optional').each(function(){
            var id = $(this).attr('id');
			
			if(id!=argid && $('#cut_'+id+'').val() == cut && $('#add_'+id+'').val() == add)
            {
              //alert(argid+' == '+id +' | '+$('#cut_'+id+'').val()+' == '+cut+' | '+$('#add_'+id+'').val()+' == '+add);			  				
			  isSameRule = 1;
			  return false;//ther can not be same add and cut value
            }			
        });
		 
		if(isSameRule==1){
			return false;
		}else{
			return true; 		
		}
	}
     
	function checkSameCutAndAdd(){
		var isSameRule1 = 0;
		$('.optional').each(function(){
            var id = $(this).attr('id');
			var isSameRule = helperCheckSameCutAndAdd(id,$('#cut_'+id+'').val(),$('#add_'+id+'').val());			
			if(isSameRule==false){
				isSameRule1 = 1;
				return false;
			}			
        });
		if(isSameRule1==1){
			return false;
		}else{
			return true; 		
		}
	}  
    
    $('#addLocalizationGroup').submit(function(){
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
       var groupname = $('#groupname').val();       
       
       var name_error = 0;       
       var optional_error = 0;
       var text = '';
 
       
       if(groupname == '')
       {
            name_error = 1;
       }    
       
       
       $('.optional').each(function(){
            var id = $(this).attr('id');
            
            if($('#pre_'+id+'').val() == '')
            {
                optional_error = 1;
                return false;
            }
			
			if($('#cut_'+id+'').val() != '' && isNaN($('#cut_'+id+'').val()))
            {
                optional_error = 1;
                return false;
            }
			
			if($('#add_'+id+'').val() != '' && isNaN($('#add_'+id+'').val()))
            {
                optional_error = 1;
                return false;
            }
			
        });
       
       if(name_error == 1)
       {
            text += "Please enter group name.";
       }
       
       if(optional_error == 1)
       {
            text += "<br/>Please select rule name in the optional rows or either remove them.";
       }
       
	   var isSameRule = checkSameCutAndAdd();
	    
	   if(isSameRule==false){
	   	 text += "<br/>There must not be same rules exists.";
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
		   var form = $('#addLocalizationGroup').serialize();		   
            $.ajax({
                    type: "POST",
					url: base_url+"groups/edit_localization_group_db",
					data: form,
                    success: function(html){		 					 
                          
						    $('.error').hide();
                            $('.success').html("Localization group updated successfully."+html);
                            $('.success').fadeOut();
                            $('.success').fadeIn();
                            document.getElementById('success_div').scrollIntoView();
                            $.unblockUI();
                    },
					error: function(xhr,abc,errorthrou){
						//alert(xhr.status+' - '+abc+' - '+errorthrou);
					}
				});
                
            return false;
        }
    return false;
    });
</script>