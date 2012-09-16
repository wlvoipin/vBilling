SET @ORIGINAL_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @ORIGINAL_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @ORIGINAL_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES,NO_AUTO_VALUE_ON_ZERO,NO_AUTO_CREATE_USER';

CREATE TABLE `RG_TEMP_418921519_33` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_customer` tinyint(1) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_33`(`id`,`username`,`password`,`type`,`is_customer`,`customer_id`,`enabled`) SELECT `id`,`username`,`password`,`type`,`is_customer`,`customer_id`,`enabled` FROM `accounts`;

DROP TABLE `accounts`;

ALTER TABLE `RG_TEMP_418921519_33` RENAME TO `accounts`;

CREATE TABLE `RG_TEMP_418921519_34` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `view_customers` tinyint(1) NOT NULL DEFAULT 0,
  `new_customers` tinyint(1) NOT NULL DEFAULT 0,
  `enable_disable_customers` tinyint(1) NOT NULL DEFAULT 0,
  `edit_customers` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_cdr` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_rates` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_billing` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_acl` tinyint(1) NOT NULL DEFAULT 0,
  `new_acl` tinyint(1) NOT NULL DEFAULT 0,
  `edit_acl` tinyint(1) NOT NULL DEFAULT 0,
  `delete_acl` tinyint(1) NOT NULL DEFAULT 0,
  `change_type_acl` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_sip` tinyint(1) NOT NULL DEFAULT 0,
  `new_sip` tinyint(1) NOT NULL DEFAULT 0,
  `delete_sip` tinyint(1) NOT NULL DEFAULT 0,
  `enable_disable_sip` tinyint(1) NOT NULL DEFAULT 0,
  `view_customers_balance` tinyint(1) NOT NULL DEFAULT 0,
  `add_deduct_balance` tinyint(1) NOT NULL DEFAULT 0,
  `view_carriers` tinyint(1) NOT NULL DEFAULT 0,
  `new_carriers` tinyint(1) NOT NULL DEFAULT 0,
  `edit_carriers` tinyint(1) NOT NULL DEFAULT 0,
  `enable_disable_carriers` tinyint(1) NOT NULL DEFAULT 0,
  `delete_carriers` tinyint(1) NOT NULL DEFAULT 0,
  `view_rate_groups` tinyint(1) NOT NULL DEFAULT 0,
  `new_rate_groups` tinyint(1) NOT NULL DEFAULT 0,
  `edit_rate_groups` tinyint(1) NOT NULL DEFAULT 0,
  `enable_disable_rate_groups` tinyint(1) NOT NULL DEFAULT 0,
  `delete_rate_groups` tinyint(1) NOT NULL DEFAULT 0,
  `new_rate` tinyint(1) NOT NULL DEFAULT 0,
  `import_csv` tinyint(1) NOT NULL DEFAULT 0,
  `view_cdr` tinyint(1) NOT NULL DEFAULT 0,
  `view_gateway_stats` tinyint(1) NOT NULL DEFAULT 0,
  `view_customer_stats` tinyint(1) NOT NULL DEFAULT 0,
  `view_call_destination` tinyint(1) NOT NULL DEFAULT 0,
  `view_biling` tinyint(1) NOT NULL DEFAULT 0,
  `view_invoices` tinyint(1) NOT NULL DEFAULT 0,
  `generate_invoices` tinyint(1) NOT NULL DEFAULT 0,
  `mark_invoices_paid` tinyint(1) NOT NULL DEFAULT 0,
  `view_profiles` tinyint(1) NOT NULL DEFAULT 0,
  `new_profiles` tinyint(1) NOT NULL DEFAULT 0,
  `delete_profiles` tinyint(1) NOT NULL DEFAULT 0,
  `freeswitch_status` tinyint(1) NOT NULL DEFAULT 0,
  `profile_details` tinyint(1) NOT NULL DEFAULT 0,
  `new_gateway` tinyint(1) NOT NULL DEFAULT 0,
  `delete_gateway` tinyint(1) NOT NULL DEFAULT 0,
  `edit_gateway` tinyint(1) NOT NULL DEFAULT 0,
  `delete_settings` tinyint(1) NOT NULL DEFAULT 0,
  `edit_settings` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_34`(`id`,`user_id`,`view_customers`,`new_customers`,`enable_disable_customers`,`edit_customers`,`view_customers_cdr`,`view_customers_rates`,`view_customers_billing`,`view_customers_acl`,`new_acl`,`edit_acl`,`delete_acl`,`change_type_acl`,`view_customers_sip`,`new_sip`,`delete_sip`,`enable_disable_sip`,`view_customers_balance`,`add_deduct_balance`,`view_carriers`,`new_carriers`,`edit_carriers`,`enable_disable_carriers`,`delete_carriers`,`view_rate_groups`,`new_rate_groups`,`edit_rate_groups`,`enable_disable_rate_groups`,`delete_rate_groups`,`new_rate`,`import_csv`,`view_cdr`,`view_gateway_stats`,`view_customer_stats`,`view_call_destination`,`view_biling`,`view_invoices`,`generate_invoices`,`mark_invoices_paid`,`view_profiles`,`new_profiles`,`delete_profiles`,`freeswitch_status`,`profile_details`,`new_gateway`,`delete_gateway`,`edit_gateway`,`delete_settings`,`edit_settings`) SELECT `id`,`user_id`,`view_customers`,`new_customers`,`enable_disable_customers`,`edit_customers`,`view_customers_cdr`,`view_customers_rates`,`view_customers_billing`,`view_customers_acl`,`new_acl`,`edit_acl`,`delete_acl`,`change_type_acl`,`view_customers_sip`,`new_sip`,`delete_sip`,`enable_disable_sip`,`view_customers_balance`,`add_deduct_balance`,`view_carriers`,`new_carriers`,`edit_carriers`,`enable_disable_carriers`,`delete_carriers`,`view_rate_groups`,`new_rate_groups`,`edit_rate_groups`,`enable_disable_rate_groups`,`delete_rate_groups`,`new_rate`,`import_csv`,`view_cdr`,`view_gateway_stats`,`view_customer_stats`,`view_call_destination`,`view_biling`,`view_invoices`,`generate_invoices`,`mark_invoices_paid`,`view_profiles`,`new_profiles`,`delete_profiles`,`freeswitch_status`,`profile_details`,`new_gateway`,`delete_gateway`,`edit_gateway`,`delete_settings`,`edit_settings` FROM `accounts_restrictions`;

DROP TABLE `accounts_restrictions`;

ALTER TABLE `RG_TEMP_418921519_34` RENAME TO `accounts_restrictions`;

CREATE TABLE `RG_TEMP_418921519_35` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `acl_name` varchar(128) NOT NULL,
  `default_policy` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_35`(`id`,`acl_name`,`default_policy`) SELECT `id`,`acl_name`,`default_policy` FROM `acl_lists`;

DROP TABLE `acl_lists`;

ALTER TABLE `RG_TEMP_418921519_35` RENAME TO `acl_lists`;

CREATE TABLE `RG_TEMP_418921519_36` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `localization_id` int(11) NULL,
  `cidr` varchar(45) NOT NULL,
  `type` varchar(16) NOT NULL,
  `list_id` int(10) unsigned NOT NULL,
  `added_by` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_36`(`id`,`customer_id`,`cidr`,`type`,`list_id`,`added_by`,`localization_id`) SELECT `id`,`customer_id`,`cidr`,`type`,`list_id`,`added_by`,NULL FROM `acl_nodes`;

DROP TABLE `acl_nodes`;

ALTER TABLE `RG_TEMP_418921519_36` RENAME TO `acl_nodes`;

CREATE TABLE `RG_TEMP_418921519_37` (
  `id` int(11) NOT NULL auto_increment,
  `carrier_id` int(11) NULL,
  `gateway_name` varchar(250) NOT NULL,
  `carrier_profile_name` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `codec` varchar(255) NOT NULL,
  `prefix_sofia_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `carrier_id`(`carrier_id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_37`(`id`,`carrier_id`,`gateway_name`,`carrier_profile_name`,`prefix`,`suffix`,`codec`,`prefix_sofia_id`,`priority`,`enabled`) SELECT `id`,`carrier_id`,`gateway_name`,`carrier_profile_name`,`prefix`,`suffix`,`codec`,`prefix_sofia_id`,`priority`,`enabled` FROM `carrier_gateway`;

DROP TABLE `carrier_gateway`;

ALTER TABLE `RG_TEMP_418921519_37` RENAME TO `carrier_gateway`;

CREATE TABLE `RG_TEMP_418921519_38` (
  `id` int(11) NOT NULL auto_increment,
  `carrier_name` varchar(255) NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_38`(`id`,`carrier_name`,`enabled`) SELECT `id`,`carrier_name`,`enabled` FROM `carriers`;

DROP TABLE `carriers`;

ALTER TABLE `RG_TEMP_418921519_38` RENAME TO `carriers`;

CREATE TABLE `RG_TEMP_418921519_39` (
  `id` int(11) NOT NULL auto_increment,
  `caller_id_name` varchar(255) NOT NULL DEFAULT '',
  `caller_id_number` varchar(255) NOT NULL DEFAULT '',
  `destination_number` varchar(255) NOT NULL DEFAULT '',
  `context` varchar(255) NOT NULL DEFAULT '',
  `duration` varchar(255) NOT NULL DEFAULT '',
  `created_time` varchar(255) NOT NULL DEFAULT '',
  `profile_created_time` varchar(255) NOT NULL DEFAULT '',
  `progress_media_time` varchar(255) NOT NULL DEFAULT '',
  `answered_time` varchar(255) NOT NULL DEFAULT '',
  `bridged_time` varchar(255) NOT NULL DEFAULT '',
  `hangup_time` varchar(255) NOT NULL DEFAULT '',
  `billsec` varchar(255) NOT NULL DEFAULT '',
  `hangup_cause` varchar(255) NOT NULL DEFAULT '',
  `uuid` varchar(255) NOT NULL DEFAULT '',
  `read_codec` varchar(255) NOT NULL DEFAULT '',
  `write_codec` varchar(255) NOT NULL DEFAULT '',
  `network_addr` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `sip_user_agent` varchar(255) NOT NULL DEFAULT '',
  `sip_hangup_disposition` varchar(255) NOT NULL DEFAULT '',
  `ani` varchar(255) NOT NULL DEFAULT '',
  `customer_group_rate_table` varchar(255) NOT NULL DEFAULT '',
  `customer_prepaid` tinyint(1) NOT NULL,
  `customer_balance` decimal(11,4) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_acc_num` int(10) NOT NULL,
  `cidr` varchar(255) NOT NULL DEFAULT '',
  `sell_rate` decimal(11,4) NOT NULL,
  `cost_rate` decimal(11,4) NOT NULL,
  `buyblock_min_duration` int(3) NOT NULL,
  `sellblock_min_duration` int(3) NOT NULL,
  `buy_initblock` int(3) NOT NULL,
  `sell_initblock` int(3) NOT NULL,
  `total_sell_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `total_admin_sell_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `total_reseller_sell_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `total_buy_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `total_admin_buy_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `total_reseller_buy_cost` decimal(11,4) NOT NULL DEFAULT 0.0000,
  `gateway` varchar(255) NOT NULL,
  `sofia_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `lcr_carrier_id` int(11) NOT NULL,
  `is_multi_gateway` tinyint(1) NOT NULL DEFAULT 0,
  `total_failed_gateways` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `parent_reseller_id` int(11) NOT NULL DEFAULT '0',
  `grand_parent_reseller_id` int(11) NOT NULL DEFAULT '0',
  `reseller_level` tinyint(1) NOT NULL DEFAULT 0,
  `admin_rate_group` varchar(50) NULL,
  `admin_rate_id` int(11) NOT NULL DEFAULT '0',
  `reseller_rate_group` varchar(50) NULL,
  `reseller_rate_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IX_cdr`(`customer_id`, `hangup_cause`, `created_time`, `billsec`, `total_sell_cost`),
  KEY `IX_cdr2`(`gateway`, `hangup_cause`, `created_time`, `sofia_id`),
  KEY `IX_cdr3`(`gateway`, `sofia_id`, `created_time`),
  KEY `IX_parent_id_created_time`(`parent_id`, `created_time`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_39`(`id`,`caller_id_name`,`caller_id_number`,`destination_number`,`context`,`duration`,`created_time`,`profile_created_time`,`progress_media_time`,`answered_time`,`bridged_time`,`hangup_time`,`billsec`,`hangup_cause`,`uuid`,`read_codec`,`write_codec`,`network_addr`,`username`,`sip_user_agent`,`sip_hangup_disposition`,`ani`,`customer_group_rate_table`,`customer_prepaid`,`customer_balance`,`customer_id`,`customer_acc_num`,`cidr`,`sell_rate`,`cost_rate`,`buyblock_min_duration`,`sellblock_min_duration`,`buy_initblock`,`sell_initblock`,`total_sell_cost`,`total_admin_sell_cost`,`total_reseller_sell_cost`,`total_buy_cost`,`total_admin_buy_cost`,`total_reseller_buy_cost`,`gateway`,`sofia_id`,`country_id`,`rate_id`,`lcr_carrier_id`,`is_multi_gateway`,`total_failed_gateways`,`parent_id`,`parent_reseller_id`,`grand_parent_reseller_id`,`reseller_level`,`admin_rate_group`,`admin_rate_id`,`reseller_rate_group`,`reseller_rate_id`) SELECT `id`,`caller_id_name`,`caller_id_number`,`destination_number`,`context`,`duration`,`created_time`,`profile_created_time`,`progress_media_time`,`answered_time`,`bridged_time`,`hangup_time`,`billsec`,`hangup_cause`,`uuid`,`read_codec`,`write_codec`,`network_addr`,`username`,`sip_user_agent`,`sip_hangup_disposition`,`ani`,`customer_group_rate_table`,`customer_prepaid`,`customer_balance`,`customer_id`,`customer_acc_num`,`cidr`,`sell_rate`,`cost_rate`,`buyblock_min_duration`,`sellblock_min_duration`,`buy_initblock`,`sell_initblock`,`total_sell_cost`,`total_admin_sell_cost`,`total_reseller_sell_cost`,`total_buy_cost`,`total_admin_buy_cost`,`total_reseller_buy_cost`,`gateway`,`sofia_id`,`country_id`,`rate_id`,`lcr_carrier_id`,`is_multi_gateway`,`total_failed_gateways`,`parent_id`,`parent_reseller_id`,`grand_parent_reseller_id`,`reseller_level`,`admin_rate_group`,`admin_rate_id`,`reseller_rate_group`,`reseller_rate_id` FROM `cdr`;

DROP TABLE `cdr`;

ALTER TABLE `RG_TEMP_418921519_39` RENAME TO `cdr`;

CREATE TABLE `RG_TEMP_418921519_40` (
  `id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `uuid` varchar(500) NOT NULL,
  `charges` decimal(11,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_40`(`id`,`customer_id`,`uuid`,`charges`) SELECT `id`,`customer_id`,`uuid`,`charges` FROM `check_cdr_time`;

DROP TABLE `check_cdr_time`;

ALTER TABLE `RG_TEMP_418921519_40` RENAME TO `check_cdr_time`;

CREATE TABLE `RG_TEMP_418921519_41` (
  `id` int(11) NOT NULL auto_increment,
  `param_name` varchar(255) NOT NULL,
  `param_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_41`(`id`,`param_name`,`param_value`) SELECT `id`,`param_name`,`param_value` FROM `console_conf`;

DROP TABLE `console_conf`;

ALTER TABLE `RG_TEMP_418921519_41` RENAME TO `console_conf`;

CREATE TABLE `RG_TEMP_418921519_42` (
  `id` smallint(20) NOT NULL auto_increment,
  `countrycode` char(3) NOT NULL,
  `alpha2code` char(2) NOT NULL,
  `countryprefix` varchar(10) NOT NULL,
  `gmttime` varchar(15) NOT NULL,
  `countryname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_42`(`id`,`countrycode`,`alpha2code`,`countryprefix`,`gmttime`,`countryname`) SELECT `id`,`countrycode`,`alpha2code`,`countryprefix`,`gmttime`,`countryname` FROM `countries`;

DROP TABLE `countries`;

ALTER TABLE `RG_TEMP_418921519_42` RENAME TO `countries`;

CREATE TABLE `RG_TEMP_418921519_43` (
  `id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `total_sip_accounts` int(11) NOT NULL,
  `total_acl_nodes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_43`(`id`,`customer_id`,`total_sip_accounts`,`total_acl_nodes`) SELECT `id`,`customer_id`,`total_sip_accounts`,`total_acl_nodes` FROM `customer_access_limitations`;

DROP TABLE `customer_access_limitations`;

ALTER TABLE `RG_TEMP_418921519_43` RENAME TO `customer_access_limitations`;

CREATE TABLE `RG_TEMP_418921519_44` (
  `id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NULL,
  `balance` decimal(11,4) NULL DEFAULT 0.0000,
  `action` varchar(20) NULL,
  `date` varchar(250) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_44`(`id`,`customer_id`,`balance`,`action`,`date`) SELECT `id`,`customer_id`,`balance`,`action`,`date` FROM `customer_balance_history`;

DROP TABLE `customer_balance_history`;

ALTER TABLE `RG_TEMP_418921519_44` RENAME TO `customer_balance_history`;

CREATE TABLE `RG_TEMP_418921519_45` (
  `id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `domain_sofia_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_45`(`id`,`customer_id`,`domain`,`domain_sofia_id`) SELECT `id`,`customer_id`,`domain`,`domain_sofia_id` FROM `customer_sip`;

DROP TABLE `customer_sip`;

ALTER TABLE `RG_TEMP_418921519_45` RENAME TO `customer_sip`;

CREATE TABLE `RG_TEMP_418921519_46` (
  `customer_id` int(11) NOT NULL auto_increment,
  `customer_acc_num` int(20) NOT NULL,
  `customer_company` varchar(50) NULL,
  `customer_firstname` varchar(50) NULL,
  `customer_lastname` varchar(50) NULL,
  `customer_contact_email` varchar(50) NULL,
  `customer_address` varchar(150) NULL,
  `customer_city` varchar(20) NULL,
  `customer_state` varchar(20) NULL,
  `customer_country` varchar(45) NULL,
  `customer_phone` varchar(45) NULL,
  `customer_phone_prefix` varchar(10) NOT NULL,
  `customer_fax` varchar(45) NULL,
  `customer_zip` varchar(10) NULL,
  `customer_timezone` varchar(10) NOT NULL,
  `customer_rate_group` int(11) NOT NULL,
  `rate_limit_check` tinyint(1) NOT NULL DEFAULT 0,
  `customer_low_rate_limit` decimal(10,4) NULL,
  `customer_high_rate_limit` decimal(10,4) NULL,
  `customer_flat_rate` decimal(10,4) NULL,
  `customer_prepaid` int(11) NULL DEFAULT '1',
  `customer_balance` double(10,4) NULL DEFAULT 0.0000,
  `customer_credit_limit` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `customer_enabled` int(11) NULL DEFAULT '0',
  `customer_max_calls` int(11) NOT NULL DEFAULT '0',
  `customer_send_cdr` int(11) NULL,
  `customer_billing_email` varchar(50) NULL,
  `next_invoice_date` varchar(255) NOT NULL,
  `customer_billing_cycle` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `grand_parent_id` int(11) NOT NULL DEFAULT '0',
  `reseller_level` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB checksum=1;

INSERT INTO `RG_TEMP_418921519_46`(`customer_id`,`customer_acc_num`,`customer_company`,`customer_firstname`,`customer_lastname`,`customer_contact_email`,`customer_address`,`customer_city`,`customer_state`,`customer_country`,`customer_phone`,`customer_phone_prefix`,`customer_fax`,`customer_zip`,`customer_timezone`,`customer_rate_group`,`rate_limit_check`,`customer_low_rate_limit`,`customer_high_rate_limit`,`customer_flat_rate`,`customer_prepaid`,`customer_balance`,`customer_credit_limit`,`customer_enabled`,`customer_max_calls`,`customer_send_cdr`,`customer_billing_email`,`next_invoice_date`,`customer_billing_cycle`,`parent_id`,`grand_parent_id`,`reseller_level`) SELECT `customer_id`,`customer_acc_num`,`customer_company`,`customer_firstname`,`customer_lastname`,`customer_contact_email`,`customer_address`,`customer_city`,`customer_state`,`customer_country`,`customer_phone`,`customer_phone_prefix`,`customer_fax`,`customer_zip`,`customer_timezone`,`customer_rate_group`,`rate_limit_check`,`customer_low_rate_limit`,`customer_high_rate_limit`,`customer_flat_rate`,`customer_prepaid`,`customer_balance`,`customer_credit_limit`,`customer_enabled`,`customer_max_calls`,`customer_send_cdr`,`customer_billing_email`,`next_invoice_date`,`customer_billing_cycle`,`parent_id`,`grand_parent_id`,`reseller_level` FROM `customers`;

DROP TABLE `customers`;

ALTER TABLE `RG_TEMP_418921519_46` RENAME TO `customers`;

CREATE TABLE `RG_TEMP_418921519_47` (
  `id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `localization_id` int(11) NULL,
  `username` varchar(255) NOT NULL,
  `cid` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `domain_sofia_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_47`(`id`,`customer_id`,`username`,`cid`,`domain`,`domain_id`,`domain_sofia_id`,`added_by`,`enabled`,`localization_id`) SELECT `id`,`customer_id`,`username`,`cid`,`domain`,`domain_id`,`domain_sofia_id`,`added_by`,`enabled`,NULL FROM `directory`;

DROP TABLE `directory`;

ALTER TABLE `RG_TEMP_418921519_47` RENAME TO `directory`;

CREATE TABLE `RG_TEMP_418921519_48` (
  `id` int(11) NOT NULL auto_increment,
  `directory_id` int(11) NULL,
  `param_name` varchar(255) NULL,
  `param_value` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_48`(`id`,`directory_id`,`param_name`,`param_value`) SELECT `id`,`directory_id`,`param_name`,`param_value` FROM `directory_params`;

DROP TABLE `directory_params`;

ALTER TABLE `RG_TEMP_418921519_48` RENAME TO `directory_params`;

CREATE TABLE `RG_TEMP_418921519_49` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` varchar(100) NOT NULL,
  `group_description` varchar(250) NOT NULL,
  `group_rate_table` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB avg_row_length=39;

INSERT INTO `RG_TEMP_418921519_49`(`id`,`group_name`,`group_description`,`group_rate_table`,`enabled`,`created_by`) SELECT `id`,`group_name`,`group_description`,`group_rate_table`,`enabled`,`created_by` FROM `groups`;

DROP TABLE `groups`;

ALTER TABLE `RG_TEMP_418921519_49` RENAME TO `groups`;

CREATE TABLE `RG_TEMP_418921519_50` (
  `id` int(11) NOT NULL auto_increment,
  `hangup_cause` varchar(250) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_50`(`id`,`hangup_cause`) SELECT `id`,`hangup_cause` FROM `hangup_causes`;

DROP TABLE `hangup_causes`;

ALTER TABLE `RG_TEMP_418921519_50` RENAME TO `hangup_causes`;

CREATE TABLE `RG_TEMP_418921519_51` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_id` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `total_charges` decimal(11,4) NOT NULL,
  `total_calls` int(11) NOT NULL,
  `total_tax` decimal(11,4) NOT NULL,
  `tax_rate` decimal(11,4) NOT NULL,
  `misc_charges` decimal(11,4) NOT NULL,
  `misc_charges_description` varchar(255) NOT NULL,
  `customer_prepaid` int(11) NOT NULL,
  `invoice_generated_date` varchar(255) NOT NULL,
  `due_date` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `grand_parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_51`(`id`,`invoice_id`,`customer_id`,`from_date`,`to_date`,`total_charges`,`total_calls`,`total_tax`,`tax_rate`,`misc_charges`,`misc_charges_description`,`customer_prepaid`,`invoice_generated_date`,`due_date`,`status`,`parent_id`,`grand_parent_id`) SELECT `id`,`invoice_id`,`customer_id`,`from_date`,`to_date`,`total_charges`,`total_calls`,`total_tax`,`tax_rate`,`misc_charges`,`misc_charges_description`,`customer_prepaid`,`invoice_generated_date`,`due_date`,`status`,`parent_id`,`grand_parent_id` FROM `invoices`;

DROP TABLE `invoices`;

ALTER TABLE `RG_TEMP_418921519_51` RENAME TO `invoices`;

CREATE TABLE `RG_TEMP_418921519_52` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `conf_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_52`(`id`,`conf_name`) SELECT `id`,`conf_name` FROM `modless_conf`;

DROP TABLE `modless_conf`;

ALTER TABLE `RG_TEMP_418921519_52` RENAME TO `modless_conf`;

CREATE TABLE `RG_TEMP_418921519_53` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `module_name` varchar(64) NOT NULL,
  `load_module` tinyint(1) NOT NULL DEFAULT 1,
  `priority` int(10) unsigned NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_mod`(`module_name`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_53`(`id`,`module_name`,`load_module`,`priority`) SELECT `id`,`module_name`,`load_module`,`priority` FROM `post_load_modules_conf`;

DROP TABLE `post_load_modules_conf`;

ALTER TABLE `RG_TEMP_418921519_53` RENAME TO `post_load_modules_conf`;

CREATE TABLE `RG_TEMP_418921519_54` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `enable_rate_limits` tinyint(4) NOT NULL DEFAULT 0,
  `logo` varchar(250) NOT NULL,
  `invoice_logo` varchar(250) NOT NULL,
  `invoice_terms` text NOT NULL,
  `company_logo_as_invoice_logo` tinyint(1) NOT NULL,
  `optional_cdr_fields_include` text NOT NULL
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_54`(`id`,`customer_id`,`company_name`,`enable_rate_limits`,`logo`,`invoice_logo`,`invoice_terms`,`company_logo_as_invoice_logo`,`optional_cdr_fields_include`) SELECT `id`,`customer_id`,`company_name`,`enable_rate_limits`,`logo`,`invoice_logo`,`invoice_terms`,`company_logo_as_invoice_logo`,`optional_cdr_fields_include` FROM `settings`;

DROP TABLE `settings`;

ALTER TABLE `RG_TEMP_418921519_54` RENAME TO `settings`;

CREATE TABLE `RG_TEMP_418921519_55` (
  `id` int(11) NOT NULL auto_increment,
  `param_name` varchar(100) NOT NULL,
  `param_value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_55`(`id`,`param_name`,`param_value`) SELECT `id`,`param_name`,`param_value` FROM `socket_client_conf`;

DROP TABLE `socket_client_conf`;

ALTER TABLE `RG_TEMP_418921519_55` RENAME TO `socket_client_conf`;

CREATE TABLE `RG_TEMP_418921519_56` (
  `id` int(11) NOT NULL auto_increment,
  `profile_name` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_56`(`id`,`profile_name`) SELECT `id`,`profile_name` FROM `sofia_conf`;

DROP TABLE `sofia_conf`;

ALTER TABLE `RG_TEMP_418921519_56` RENAME TO `sofia_conf`;

CREATE TABLE `RG_TEMP_418921519_57` (
  `id` int(11) NOT NULL auto_increment,
  `sofia_id` int(11) NULL,
  `domain_name` varchar(255) NULL,
  `parse` tinyint(1) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_57`(`id`,`sofia_id`,`domain_name`,`parse`) SELECT `id`,`sofia_id`,`domain_name`,`parse` FROM `sofia_domains`;

DROP TABLE `sofia_domains`;

ALTER TABLE `RG_TEMP_418921519_57` RENAME TO `sofia_domains`;

CREATE TABLE `RG_TEMP_418921519_58` (
  `id` int(11) NOT NULL auto_increment,
  `sofia_id` int(11) NULL,
  `gateway_name` varchar(255) NULL,
  `gateway_param` varchar(255) NULL,
  `gateway_value` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_58`(`id`,`sofia_id`,`gateway_name`,`gateway_param`,`gateway_value`) SELECT `id`,`sofia_id`,`gateway_name`,`gateway_param`,`gateway_value` FROM `sofia_gateways`;

DROP TABLE `sofia_gateways`;

ALTER TABLE `RG_TEMP_418921519_58` RENAME TO `sofia_gateways`;

CREATE TABLE `RG_TEMP_418921519_59` (
  `id` int(11) NOT NULL auto_increment,
  `param_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_59`(`id`,`param_name`) SELECT `id`,`param_name` FROM `sofia_gateways_params`;

DROP TABLE `sofia_gateways_params`;

ALTER TABLE `RG_TEMP_418921519_59` RENAME TO `sofia_gateways_params`;

CREATE TABLE `RG_TEMP_418921519_60` (
  `id` int(11) NOT NULL auto_increment,
  `sofia_id` int(11) NULL,
  `param_name` varchar(255) NULL,
  `param_value` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_60`(`id`,`sofia_id`,`param_name`,`param_value`) SELECT `id`,`sofia_id`,`param_name`,`param_value` FROM `sofia_settings`;

DROP TABLE `sofia_settings`;

ALTER TABLE `RG_TEMP_418921519_60` RENAME TO `sofia_settings`;

CREATE TABLE `RG_TEMP_418921519_61` (
  `id` int(11) NOT NULL auto_increment,
  `type` text NOT NULL,
  `param_name` varchar(255) NOT NULL,
  `param_value_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_61`(`id`,`type`,`param_name`,`param_value_type`) SELECT `id`,`type`,`param_name`,`param_value_type` FROM `sofia_settings_params`;

DROP TABLE `sofia_settings_params`;

ALTER TABLE `RG_TEMP_418921519_61` RENAME TO `sofia_settings_params`;

CREATE TABLE `RG_TEMP_418921519_62` (
  `id` int(11) NOT NULL auto_increment,
  `param_name` varchar(255) NOT NULL,
  `param_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_62`(`id`,`param_name`,`param_value`) SELECT `id`,`param_name`,`param_value` FROM `switch_conf`;

DROP TABLE `switch_conf`;

ALTER TABLE `RG_TEMP_418921519_62` RENAME TO `switch_conf`;

CREATE TABLE `RG_TEMP_418921519_63` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `timezone_location` varchar(30) NOT NULL DEFAULT '',
  `gmt` varchar(11) NOT NULL DEFAULT '',
  `offset` tinyint(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_63`(`id`,`timezone_location`,`gmt`,`offset`) SELECT `id`,`timezone_location`,`gmt`,`offset` FROM `timezones`;

DROP TABLE `timezones`;

ALTER TABLE `RG_TEMP_418921519_63` RENAME TO `timezones`;

CREATE TABLE `RG_TEMP_418921519_64` (
  `id` int(11) NOT NULL auto_increment,
  `param_name` varchar(255) NOT NULL,
  `param_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_64`(`id`,`param_name`,`param_value`) SELECT `id`,`param_name`,`param_value` FROM `xml_cdr_conf`;

DROP TABLE `xml_cdr_conf`;

ALTER TABLE `RG_TEMP_418921519_64` RENAME TO `xml_cdr_conf`;

CREATE TABLE `RG_TEMP_418921519_65` (
  `id` int(11) NOT NULL auto_increment,
  `directory_id` int(11) NULL,
  `var_name` varchar(255) NULL,
  `var_value` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `RG_TEMP_418921519_65`(`id`,`directory_id`,`var_name`,`var_value`) SELECT `id`,`directory_id`,`var_name`,`var_value` FROM `directory_vars`;

DROP TABLE `directory_vars`;

ALTER TABLE `RG_TEMP_418921519_65` RENAME TO `directory_vars`;

CREATE TABLE `version` (
  `id` int(11) NOT NULL auto_increment,
  `version` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `localization_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NULL,
  `enabled` int(1) NOT NULL DEFAULT '1' COMMENT '1- enabled; 0 -not enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `localization_rules` (
  `id` int(11) NOT NULL auto_increment,
  `localization_id` int(11) NOT NULL,
  `name` varchar(255) NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT 1,
  `lcut` varchar(255) NULL,
  `ladd` varchar(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_localization_rules_id`(`id`),
  KEY `IX_localization_rules`(`lcut`, `ladd`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS=@ORIGINAL_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@ORIGINAL_UNIQUE_CHECKS;
SET SQL_MODE=@ORIGINAL_SQL_MODE;
