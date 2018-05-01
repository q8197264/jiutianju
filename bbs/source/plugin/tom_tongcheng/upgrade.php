<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = '';

$tom_tongcheng_field = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_field();
if (!isset($tom_tongcheng_field['site_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `site_id` int(11) DEFAULT '1';\n";
}
if (!isset($tom_tongcheng_field['city_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `city_id` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_field['area_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `area_id` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_field['street_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `street_id` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_field['auto_click_time'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `auto_click_time` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_field['shenhe_status'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng')." ADD `shenhe_status` int(11) DEFAULT '1';\n";
}

$tom_tongcheng_common_field = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_all_field();
if (!isset($tom_tongcheng_common_field['forbid_word'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_common')." ADD `forbid_word` text;\n";
}

$tom_tongcheng_focuspic_field = C::t('#tom_tongcheng#tom_tongcheng_focuspic')->fetch_all_field();
if (!isset($tom_tongcheng_focuspic_field['site_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_focuspic')." ADD `site_id` int(11) DEFAULT '1';\n";
}

$tom_tongcheng_model_field = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_field();
if (!isset($tom_tongcheng_model_field['area_select'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_model')." ADD `area_select` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_model_field['must_shenhe'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_model')." ADD `must_shenhe` int(11) DEFAULT '0';\n";
}

$tom_tongcheng_order_field = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_field();
if (!isset($tom_tongcheng_order_field['site_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_order')." ADD `site_id` int(11) DEFAULT '1';\n";
}

$tom_tongcheng_topnews_field = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_field();
if (!isset($tom_tongcheng_topnews_field['site_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_topnews')." ADD `site_id` int(11) DEFAULT '1';\n";
}

$tom_tongcheng_user_field = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_field();
if (!isset($tom_tongcheng_user_field['last_smstp_time'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_user')." ADD `last_smstp_time` int(11) DEFAULT '0';\n";
}
if (!isset($tom_tongcheng_user_field['editor'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_user')." ADD `editor` int(11) DEFAULT '0';\n";
}

if (!empty($sql)) {
	runquery($sql);
}

$sql = <<<EOF
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_district` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `level` tinyint(3) unsigned DEFAULT '0',
      `upid` mediumint(8) unsigned DEFAULT '0',
      `displayorder` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_upid` (`upid`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_nav` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `site_id` int(11) DEFAULT '1',
      `type` int(11) DEFAULT '0',
      `model_id` int(11) DEFAULT '0',
      `title` varchar(255) DEFAULT NULL,
      `picurl` varchar(255) DEFAULT NULL,
      `link` varchar(255) DEFAULT NULL,
      `nsort` int(11) DEFAULT '10',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_sites` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `manage_openid1` varchar(255) DEFAULT NULL,
      `manage_openid2` varchar(255) DEFAULT NULL,
      `manage_openid3` varchar(255) DEFAULT NULL,
      `manage_user_id` int(11) DEFAULT '0',
      `city_id` int(11) DEFAULT '0',
      `name` varchar(255) DEFAULT NULL,
      `logo` varchar(255) DEFAULT NULL,
      `kefu_qrcode` varchar(255) DEFAULT NULL,
      `share_title` varchar(255) DEFAULT NULL,
      `share_desc` varchar(255) DEFAULT NULL,
      `share_pic` varchar(255) DEFAULT NULL,
      `status` int(11) DEFAULT '1',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_tz` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT '0',
      `type` int(11) DEFAULT '0',
      `content` text,
      `tz_time` int(11) DEFAULT '0',
      `is_read` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

EOF;

    runquery($sql);
    
$sql = '';

$tom_tongcheng_sites_field = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_field();
if (!isset($tom_tongcheng_sites_field['manage_user_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_tongcheng_sites')." ADD `manage_user_id` int(11) DEFAULT '0';\n";
}

if (!empty($sql)) {
	runquery($sql);
}

$finish = TRUE;

