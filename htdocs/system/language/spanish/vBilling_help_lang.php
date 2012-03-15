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
	New Customer (admin)
*/

$lang['add_customer_view_firstname']                  = 'Customer firstname';
$lang['add_customer_view_lastname']                   = 'Customer lastname';
$lang['add_customer_view_company']                    = 'Customer company name';
$lang['add_customer_view_email']                      = 'Email address';
$lang['add_customer_view_account_type']               = 'Account type can be Prepaid or Postpaid.\
														<br><br>A prepaid account will not be able to make any more calls if the balance is finished.\
														<br><br>A postpaid account has credit limit. The balance can go down upto the credit limit defined.';
$lang['add_customer_view_postpaid_credit_limit']      = 'Credit limit of postpaid account';
$lang['add_customer_view_bill_cycle']                 = 'Billing Cycle on which the invoices will be generated';
$lang['add_customer_view_daily_cycle']                = 'Invoice will be generated daily';
$lang['add_customer_view_weekly_cycle']               = 'Invoice will be generated every week';
$lang['add_customer_view_bi_weekly_cycle']            = 'Invoice will be generated bi-weekly';
$lang['add_customer_view_monthly_cycle']              = 'Invoice will be generated every month';
$lang['add_customer_view_concurrent_calls']           = 'Maximum number of concurrent calls allowed for this customer.';
$lang['add_customer_view_timezone']                   = 'All CDR(s) will be displayed in customer\\\'s timezone in their control panel.';
$lang['add_customer_view_rate_group']                 = 'Select the rate group which customer belongs. A rate group contains many rates. The customer will be able to \
														make calls for the rates present in this group.\
														<br><br>If the rate group displays VALID, this means the group has valid rates in it. If it displays \
														INVALID, please go back to rate group and add some rates.';
$lang['add_customer_view_attach_cdr_with_email']      = 'Select if you would like to attach CDR(s) with invoices sent by email';
$lang['add_customer_view_billing_same_as_email']      = 'Select if billing email address is same as email address above';
$lang['add_customer_view_customer_type']              = 'Customer accounts are of three types\
														<br>\
														<br><b>Normal Customer</b>\
														<br>Normal customer are like end users. They can not resell the service. They are only allowed to create \
														their own SIP devices and add their IP address for authentication.\
														<br>\
														<br><b>Reseller (Level - 3)</b>\
														<br>\
														Reseller having access level 3 can further create level 2 resellers who can further create level 2 \
														resellers. If this reseller\\\'s balance goes out, none of their SIP accounts, customers and reseller \
														level 2 would be able to make any calls.\
														<br>\
														<br><b>Reseller (Level - 2)</b>\
														<br>\
														Reseller having access level 2 can only create their own SIP account or normal customers. If this \
														reseller\\\'s balance goes out, none of their SIP accounts and customers would be able to make any calls.';
$lang['add_customer_view_allow_cp_access']            = 'Select if you would like to allow customer access to their control panel';
$lang['add_customer_view_username_for_cp']            = 'Username for the control panel';
$lang['add_customer_view_password_for_cp']            = 'Password for the control panel';
$lang['add_customer_view_number_of_acl_nodes']        = 'Allow the customer to have this number of IP address he can add to authenticate';
$lang['add_customer_view_number_of_sip_accounts']     = 'Number of SIP accounts customer is allowed to create';
$lang['add_customer_view_profile_ip_address']         = 'Select sofia profile IP address on which customer would be allowed to login using SIP credentials';
$lang['add_customer_view_email_information_customer'] = 'Send a welcome email to the customer';
$lang['add_customer_view_']                           = '';
$lang['add_customer_view_']                           = '';
$lang['add_customer_view_']                           = '';
$lang['add_customer_view_']                           = '';
$lang['add_customer_view_']                           = '';
$lang['add_customer_view_']                           = '';

$lang['admin_view_new_rate_country'] = 'Select the country for which this country code is associated';
$lang['admin_view_new_rate_country_code'] = 'Input digits for which incoming calls will be matched against';
$lang['admin_view_new_rate_buying_rate'] = 'Your buying rate';
$lang['admin_view_new_rate_min_buying_block'] = 'Minimum amount in seconds you are charged by your provider';
$lang['admin_view_new_rate_buy_init_block'] = 'Increments in seconds for which you are charged by your provider';
$lang['admin_view_new_rate_sell_rate'] = 'The cost you want to sell to your customer';
$lang['admin_view_new_rate_min_sell_block'] = 'Minimum amount in seconds you would like to charge your customers using this rate';
$lang['admin_view_new_rate_sell_init_block'] = 'Increments in seconds you would like to charge your customers using this rate';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';
$lang['admin_view_new_rate_'] = '';














/* End of file vBilling_help_lang.php */
/* Location: ./system/language/english/vBilling_help_lang.php */
