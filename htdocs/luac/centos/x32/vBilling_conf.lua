--[[
-- Version: MPL 1.1
--
-- The contents of this file are subject to the Mozilla Public License
-- Version 1.1 (the "License"); you may not use this file except in
-- compliance with the License. You may obtain a copy of the License at
-- http://www.mozilla.org/MPL/
-- 
-- Software distributed under the License is distributed on an "AS IS"
-- basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
-- License for the specific language governing rights and limitations
-- under the License.
-- 
-- The Original Code is "vBilling - VoIP Billing and Routing Platform"
-- 
-- The Initial Developer of the Original Code is 
-- Digital Linx [<] info at digitallinx.com [>]
-- Portions created by Initial Developer (Digital Linx) are Copyright (C) 2011
-- Initial Developer (Digital Linx). All Rights Reserved.
--
-- Contributor(s)
-- "Muhammad Naseer Bhatti <nbhatti at gmail.com>"
--
--
-- vBilling, LUA Script
-- version 0.1.2
--
-- Various config elements for vBilling.lua
--
]]

-- START of config options
--[[
DSN name for the DSN to be configured with ODBC. This is required. If not configured,
script will not work.
]]
DSN                          = "__VBILLING_DB__"

--[[
MySQL database username
]]
DB_USER                      = "__MYSQL_USERNAME__"

--[[
MySQL database password
]]
DB_PASSWORD                  = "__MYSQL_PASSWORD__"

--[[
Enable vBilling debug output of LUA processing
]]
VBILLING_DEBUG               = "0"

--[[
FreeSWITCH console log level for vBilling LUA output. NOTICE is nice, as it has different color :)
]]
FREESWITCH_CONSOLE_LOG_LEVEL = "NOTICE"

-- END of config options
