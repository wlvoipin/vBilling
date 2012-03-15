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

/*
	Language localization
*/
$lang['main_screen_title_bar']                       = ':: کے لئے لاگ ان کریں vBilling ::';
$lang['main_screen_username']                        = ': صارف کا نام';
$lang['main_screen_password']                        = ': پاس ورڈ';

$lang['login_username_prompt']                       = 'براہ کرم صارف کا نام درج کریں';
$lang['login_password_prompt']                       = 'براہ مہربانی پاس ورڈ درج کریں';

$lang['my_account_title_bar']                        = 'میرا اکاؤنٹ';
$lang['my_account_username']                         = ' صارف کا نام:';
$lang['my_account_new_password']                     = ' نیا پاس ورڈ:';
$lang['my_account_confirm_password']                 = ' پاس ورڈ تصدیق:';
$lang['my_account_fields_are_required']              = '* کے ساتھ خانوں کی ضرورت ہے';
$lang['my_account_password_confirm_do_not_match']    = 'پاس ورڈ اور تصدیق پاس ورڈ نہیں ملتی ہے';

/*
	For main_menu (top header)
*/
$lang['main_menu_customers_resellers']               = 'Customers / Resellers';
$lang['main_menu_carriers']                          = 'کیریئرز';
$lang['main_menu_rate_groups']                       = 'قیمت گروپ';
$lang['main_menu_call_details']                      = '(CDR) کال کی تفصیلات';
$lang['main_menu_billing_invoicing']                 = 'بلنگ / رسید';
$lang['main_menu_freeswitch']                        = 'FreeSWITCH';
$lang['main_menu_manage_accounts']                   = 'اکاؤنٹس کا انتظام';
$lang['main_menu_settings']                          = 'ترتیبات';
$lang['main_menu_phpsysinfo']                        = 'phpSysInfo';
$lang['reseller_balance']                            = ' :آپ کی موجودہ بیلنس';

/*
	Reseller main_menu (top header)
*/
$lang['reseller_main_menu_customer_sub_resellers']   = 'Customers / Sub-Resellers';
$lang['reseller_main_menu_rate_groups']              = 'Rate Groups';
$lang['reseller_main_menu_call_details']             = 'Call Details (CDR)';
$lang['reseller_main_menu_billing_invoicing']        = 'Billing / Invoicing';
$lang['reseller_main_menu_settings']                 = 'Settings';
$lang['reseller_main_menu_my_info']                  = 'My Info';

/*
	For customers view via the admin panel (popup)
*/
$lang['customers_popup_new_acl_node']                = 'NEW ACL NODE';
$lang['customers_popup_acl_cidr']                    = 'ایڈریس IP';
$lang['customers_popup_enable_disable']              = 'فعال کریں غیر / فعال کریں';
$lang['customers_popup_action_delete_acl']           = 'Action (Delete)';
$lang['customers_popup_enable_acl']                  = 'فعال';
$lang['customers_popup_disable_acl']                 = 'غیر فعال';
$lang['customers_popup_alert_enable_disable_acl']    = 'Are You Sure Want To Update This ACL Node type?';
$lang['customers_popup_alert_delete_acl']            = 'Are You Sure Want To Delete This ACL Node?';
$lang['customers_popup_acl_node_deleted']            = 'ACL Node Deleted Successfully';
$lang['customers_popup_acl_node_changed']            = 'ACL Node Type Updated Successfully';
$lang['customers_popup_no_records_found']            = 'کوئی ریکارڈ نہیں ملا';
$lang['customers_popup_dialog_confirm_delete']       = 'Delete The ACL Node?';
$lang['customers_popup_dialog_confirm_update']       = 'Update The ACL Node Type?';

/*
	For customers view via their own panel
*/
$lang['customer_view_new_acl_node']                  = 'NEW ACL NODE';
$lang['customer_view_acl_node']                      = 'ACL Node(s)';
$lang['customer_view_acl_nodes_ramining']            = 'Remaining';
$lang['customer_view_acl_cannot_add_more_acl_nodes'] = 'Cannot add more ACL Nodes';
$lang['customer_view_ip_address']                    = 'IP Address';
$lang['customer_view_enable_acl']                    = 'Enable';
$lang['customer_view_disable_acl']                   = 'Disable';
$lang['customer_view_enable_disable']                = 'Enable/Disable';
$lang['customer_view_action_delete_acl']             = 'Action (Delete)';
$lang['customer_view_enable_acl']                    = 'Enable';
$lang['customer_view_disable_acl']                   = 'Disable';
$lang['customer_view_alert_enable_disable_acl']      = 'Are You Sure Want To Update This ACL Node type?';
$lang['customer_view_alert_delete_acl']              = 'Are You Sure Want To Delete This ACL Node?';
$lang['customer_view_acl_node_deleted']              = 'ACL Node Deleted Successfully';
$lang['customer_view_acl_node_changed']              = 'ACL Node Type Updated Successfully';
$lang['customer_view_dialog_confirm_delete']         = 'Delete The ACL Node?';
$lang['customer_view_dialog_confirm_update_type']    = 'Update The ACL Node Type?';
$lang['customer_view_no_records_found']              = 'No Records Found';

// Customer popup menu
$lang['customer_popup_menu_my_information']          = 'My Information';
$lang['customer_popup_menu_call_details']            = 'Call Details';
$lang['customer_popup_menu_call_rates']              = 'Call Rates';
$lang['customer_popup_menu_billing_invoicing']       = 'Billing / Invoicing';
$lang['customer_popup_menu_acl_nodes']               = 'ACL Nodes';
$lang['customer_popup_menu_sip_credentials']         = 'SIP Credentials';
$lang['customer_popup_menu_balance_history']         = 'Balance / Payment History';

/*
	For reseller view via their own panel for their customers(popup)
*/
$lang['reseller_popup_title_bar_customer_acl_nodes'] = 'Customer ACL Nodes';
$lang['reseller_popup_new_acl_node']                 = 'NEW ACL NODE';
$lang['reseller_popup_acl_ip_address']               = 'IP Address';
$lang['reseller_popup_enable_disable']               = 'Enable/Disable';
$lang['reseller_popup_action_delete_acl']            = 'Action (Delete)';
$lang['reseller_popup_enable_acl']                   = 'Enable';
$lang['reseller_popup_disable_acl']                  = 'Disable';
$lang['reseller_popup_no_records_found']             = 'No Records Found';
$lang['reseller_popup_dialog_confirm_delete']        = 'Delete The ACL Node?';
$lang['reseller_popup_dialog_confirm_update_type']   = 'Update The ACL Node Type?';
$lang['reseller_popup_alert_delete_acl']             = 'Are You Sure Want To Delete This ACL Node?';
$lang['reseller_popup_alert_enable_disable_acl']     = 'Are You Sure Want To Update This ACL Node type?';
$lang['reseller_popup_acl_node_deleted']             = 'ACL Node Deleted Successfully';
$lang['reseller_popup_acl_node_changed']             = 'ACL Node Type Updated Successfully';

/*
	For reseller view via their own panel for their own view (popup MyInfo)
*/
$lang['reseller_view_acl_nodes']                     = 'ACL Nodes';
$lang['reseller_view_new_acl_node']                  = 'NEW ACL NODE';
$lang['reseller_view_you_can_add_upto']              = 'You can add upto';
$lang['reseller_view_acl_node']                      = 'ACL Node(s)';
$lang['reseller_view_acl_nodes_ramining']            = 'Remaining';
$lang['reseller_view_acl_cannot_add_more_acl_nodes'] = 'Cannot add more ACL Nodes';
$lang['reseller_view_ip_address']                    = 'IP Address';
$lang['reseller_view_enable_acl']                    = 'Enable';
$lang['reseller_view_disable_acl']                   = 'Disable';
$lang['reseller_view_enable_disable']                = 'Enable/Disable';
$lang['reseller_view_action_delete_acl']             = 'Action (Delete)';
$lang['reseller_view_enable_acl']                    = 'Enable';
$lang['reseller_view_disable_acl']                   = 'Disable';
$lang['reseller_view_alert_enable_disable_acl']      = 'Are You Sure Want To Update This ACL Node type?';
$lang['reseller_view_alert_delete_acl']              = 'Are You Sure Want To Delete This ACL Node?';
$lang['reseller_view_acl_node_deleted']              = 'ACL Node Deleted Successfully';
$lang['reseller_view_acl_node_changed']              = 'ACL Node Type Updated Successfully';
$lang['reseller_view_dialog_confirm_delete']         = 'Delete The ACL Node?';
$lang['reseller_view_dialog_confirm_update_type']    = 'Update The ACL Node Type?';
$lang['reseller_view_no_records_found']              = 'No Records Found';

$lang['admin_rate_menu_new_rate_heading']            = 'New Rate';
$lang['admin_rate_menu_update_rate_heading']         = 'Update Rate';
$lang['admin_rate_menu_country']                     = ' Country:';
$lang['admin_rate_menu_country_code']                = ' Country Code:';
$lang['admin_rate_menu_buying_rate']                 = ' Buying Rate:';
$lang['admin_rate_menu_min_buy_block']               = ' Minimum Buying Block:';
$lang['admin_rate_menu_buy_rate_init_block']         = ' Buy Init Block:';
$lang['admin_rate_menu_sell_rate']                   = ' Sell Rate:';
$lang['admin_rate_menu_min_sell_block']              = ' Minimum Selling Block:';
$lang['admin_rate_menu_sell_rate_init_block']        = ' Sell Init Block:';
$lang['admin_rate_menu_remove_prefix']               = ' Remove Prefix from Rate:';
$lang['admin_rate_menu_remove_suffix']               = ' Remove Suffix from Rate:';
$lang['admin_rate_menu_lead_strip']                  = '# of Lead Strip Digits:';
$lang['admin_rate_menu_trail_strip']                 = '# of Trail Strip Digits:';
$lang['admin_rate_menu_prefix']                      = 'Add Prefix to Rate:';
$lang['admin_rate_menu_suffix']                      = 'Add Suffix to Rate:';
$lang['admin_rate_menu_lcr_profile']                 = 'LCR Profile:';
$lang['admin_rate_menu_start_date']                  = ' Start Date:';
$lang['admin_rate_menu_end_date']                    = ' End Date:';
$lang['admin_rate_menu_quality']                     = ' Quality:';
$lang['admin_rate_menu_reliability']                 = ' Reliability:';
$lang['admin_rate_menu_lrn']                         = ' LRN:';
$lang['admin_rate_menu_carrier']                     = ' Carrier:';
$lang['admin_rate_menu_rate_group']                  = ' Rate Group:';
$lang['admin_new_rate_required_error']               = 'Fields With * Are Required';
$lang['admin_new_rate_rate_error']                   = 'Sell Rate Cannot Be Less Than Cost Rate';
$lang['admin_new_rate_this_carrier_is_invalid']      = 'This Carrier is Invalid';
$lang['admin_new_rate_duplicate_rate']               = 'You cannot add rate with same digits and carrier. An entry already exists';
$lang['admin_new_rate_rate_added']                   = 'Rate added successfully';
$lang['admin_new_rate_rate_updated']                 = 'Rate updated successfully';


/* End of file vBilling_lang.php */
/* Location: ./system/language/urdu/vBilling_lang.php */
