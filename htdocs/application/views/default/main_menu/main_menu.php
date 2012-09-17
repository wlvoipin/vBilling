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
<ul id="navcss">
  
  <!-- 
	<li <?php if($selected == "dashboard") { echo 'class="current"';} ?>>
	<a  href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/images/icons/home.png" /> <span>Dashboard</span> </a>
	</li>
	-->
  
  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "customers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>customers/"><img src="<?php echo base_url();?>assets/images/icons/customers.png"/> <span><?php echo $this->lang->line('main_menu_customers_resellers');?></span> </a></li>
  <?php 
		} else if($this->session->userdata('user_type') == 'sub_admin'){
			if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_customers') == 1)
			{
				?>
  <li <?php if($selected == "customers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>customers/"><img src="<?php echo base_url();?>assets/images/icons/customers.png"/> <span><?php echo $this->lang->line('main_menu_customers_resellers');?></span> </a></li>
  <?php 
			}
		}
		?>
  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "carriers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>carriers/"><img src="<?php echo base_url();?>assets/images/icons/rss.png" style="width:25px;margin-top:-6px"/> <span><?php echo $this->lang->line('main_menu_carriers');?></span> </a></li>
  <?php 
			} else if($this->session->userdata('user_type') == 'sub_admin'){
				if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_carriers') == 1)
				{
					?>
  <li <?php if($selected == "carriers") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>carriers/"><img src="<?php echo base_url();?>assets/images/icons/rss.png" style="width:25px;margin-top:-6px"/> <span><?php echo $this->lang->line('main_menu_carriers');?></span> </a></li>
  <?php 
				}
			}
			?>
  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "groups") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>groups/"><img src="<?php echo base_url();?>assets/images/icons/linkOn.png"/> <span><?php echo $this->lang->line('main_menu_rate_groups');?></span> </a></li>
  <?php 
				} else if($this->session->userdata('user_type') == 'sub_admin'){
					if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_rate_groups') == 1)
					{
						?>
  <li <?php if($selected == "groups") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>groups/"><img src="<?php echo base_url();?>assets/images/icons/linkOn.png"/> <span><?php echo $this->lang->line('main_menu_rate_groups');?></span> </a></li>
  <?php 
					}
				}
				?>
				    <!-- <?php if($this->session->userdata('user_type') == 'admin'){?>
				    <li <?php if($selected == "did") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>did/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span><?php echo $this->lang->line('main_menu_did');?></span> </a></li>
				    <?php
				} else if($this->session->userdata('user_type') == 'sub_admin'){
				    if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_did') == 1)
				    {
				        ?>
				        <li <?php if($selected == "did") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>did/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span><?php echo $this->lang->line('main_menu_did');?></span> </a></li>
				        <?php
				    }
				}
				    ?> -->


  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "cdr") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>cdr/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span><?php echo $this->lang->line('main_menu_call_details');?></span> </a></li>
  <?php 
					} else if($this->session->userdata('user_type') == 'sub_admin'){
						if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_cdr') == 1)
						{
							?>
  <li <?php if($selected == "cdr") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>cdr/"><img src="<?php echo base_url();?>assets/images/icons/cdr.png"/> <span><?php echo $this->lang->line('main_menu_call_details');?></span> </a></li>
  <?php 
						}
					}
					?>
  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "billing") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>billing/"><img src="<?php echo base_url();?>assets/images/icons/billing.png"/> <span><?php echo $this->lang->line('main_menu_billing_invoicing');?></span> </a></li>
  <?php 
						} else if($this->session->userdata('user_type') == 'sub_admin'){
							if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_biling') == 1)
							{
								?>
  <li <?php if($selected == "billing") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>billing/"><img src="<?php echo base_url();?>assets/images/icons/billing.png"/> <span><?php echo $this->lang->line('main_menu_billing_invoicing');?></span> </a></li>
  <?php 
							}
						}
						?>
  <?php if($this->session->userdata('user_type') == 'admin'){?>
  <li <?php if($selected == "freeswitch") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>freeswitch/"><img src="<?php echo base_url();?>assets/images/icons/freeswitch.png"/> <span><?php echo $this->lang->line('main_menu_freeswitch');?></span> </a></li>
  <?php 
							} else if($this->session->userdata('user_type') == 'sub_admin'){
								if(sub_admin_access_any_cell($this->session->userdata('user_id'), 'view_profiles') == 1)
								{
									?>
  <li <?php if($selected == "freeswitch") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>freeswitch/"><img src="<?php echo base_url();?>assets/images/icons/freeswitch.png"/> <span><?php echo $this->lang->line('main_menu_freeswitch');?></span> </a></li>
  <?php 
								}
							}
							?>
  <?php if($this->session->userdata('user_type') == 'admin') {?>
  <li <?php if($selected == "manage_accounts") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>manage_accounts/"><img src="<?php echo base_url();?>assets/images/icons/lock.png"/> <span><?php echo $this->lang->line('main_menu_manage_accounts');?></span> </a></li>
  <?php } ?>
  <?php if($this->session->userdata('user_type') == 'admin') {?>
  <li <?php if($selected == "settings") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>settings/"><img src="<?php echo base_url();?>assets/images/icons/settings.png"/> <span><?php echo $this->lang->line('main_menu_settings');?></span> </a></li>
  <?php } ?>
  
  <!-- <?php if($this->session->userdata('user_type') == 'admin') {?>
									<li <?php if($selected == "phpsysinfo") { echo 'class="current"';} ?>><a  href="<?php echo base_url();?>phpsysinfo/"><img src="<?php echo base_url();?>assets/images/icons/settings.png"/> <span><?php echo $this->lang->line('main_menu_phpsysinfo');?></span> </a></li>
									<?php } ?> -->
</ul>
<div class="clr"></div>
<div id="shadowDiv"></div>
