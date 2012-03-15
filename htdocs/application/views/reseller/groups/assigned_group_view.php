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

<table  width="100%" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    
                    <tr class="bottom_link">
                        <td width="20%" align="center">Rate Group Name</td>
                        <td width="8%" align="center">Enabled</td>
                    </tr>
                    <tr><td colspan="3" id="shadowDiv" style="height:5px;margin-top:-1px"></td></tr>
                    
                    <?php if($groups->num_rows() > 0) {?>
                        
                        <?php foreach ($groups->result() as $row): ?>
                            <tr class="main_text">
                                
                                <td align="center"><a href="<?php echo base_url();?>reseller/groups/assigned_group_details/<?php echo $row->id;?>"><?php echo $row->group_name; ?></a></td>
                                <td align="center"><?php if($row->enabled == 1){ echo 'Enabled';} else { echo "Disabled"; }?></td>
                                
                                <tr style="height:5px;"><td colspan="3" id="shadowDiv" style="height:5px;margin-top:0px;background-color:#fff"></td></tr>
                            </tr>
                        <?php endforeach;?>
                        
                    <?php } else { echo '<tr><td align="center" colspan="3" style="color:red;">No Results Found</td></tr>'; } ?>                    
                    </tbody>
                </table>
            </td>
        </tr>
        
    </tbody></table>
    
    