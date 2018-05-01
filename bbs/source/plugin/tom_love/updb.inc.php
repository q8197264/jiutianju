<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/plugin');

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    
    $sql = '';

    $tom_love_field = C::t('#tom_love#tom_love')->fetch_all_field();
    if (!isset($tom_love_field['anlians'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `anlians` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['vip_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `vip_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['vip_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `vip_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['recommend_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `recommend_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['recommend_do_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `recommend_do_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['mate_demands'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `mate_demands` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_love_field['last_smstp_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `last_smstp_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['hjcountry_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `hjcountry_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['hjprovince_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `hjprovince_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['hjcity_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `hjcity_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['hjarea_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `hjarea_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_field['hjarea'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `hjarea` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_love_field['sign_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love')." ADD `sign_time` int(11) DEFAULT '0';\n";
    }
    
    $tom_love_shuoshuo_field = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_field();
    if (!isset($tom_love_shuoshuo_field['reply_count'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love_shuoshuo')." ADD `reply_count` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_love_shuoshuo_field['zan_count'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love_shuoshuo')." ADD `zan_count` int(11) DEFAULT '0';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }

    $sql = <<<EOF

    CREATE TABLE IF NOT EXISTS `pre_tom_love_order` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `order_no` varchar(255) DEFAULT NULL,
      `order_type` int(11) DEFAULT '1',
      `openid` varchar(255) DEFAULT NULL,
      `user_id` int(11) DEFAULT '0',
      `score_value` int(11) DEFAULT '0',
      `time_value` int(11) DEFAULT '0',
      `pay_price` decimal(10,2) DEFAULT '0.00',
      `order_time` int(11) DEFAULT '0',
      `pay_time` int(11) DEFAULT '0',
      `order_status` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;


    CREATE TABLE IF NOT EXISTS `pre_tom_love_report` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT NULL,
      `report_user_id` int(11) DEFAULT '0',
      `report_content` varchar(255) DEFAULT NULL,
      `report_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_love_common` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `clicks` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_love_shuoshuo_photo` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ss_id` int(11) DEFAULT '0',
      `picurl` varchar(255) DEFAULT NULL,
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

    CREATE TABLE IF NOT EXISTS `pre_tom_love_shuoshuo_reply` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ss_id` int(11) DEFAULT '0',
      `reply_user_id` int(11) DEFAULT '0',
      `reply_user_nickname` varchar(255) DEFAULT NULL,
      `reply_user_avatar` varchar(255) DEFAULT NULL,
      `reply_user_sex` int(11) DEFAULT '0',
      `content` varchar(255) DEFAULT NULL,
      `reply_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

    CREATE TABLE IF NOT EXISTS `pre_tom_love_shuoshuo_zan` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ss_id` int(11) DEFAULT '0',
      `zan_user_id` int(11) DEFAULT '0',
      `zan_user_nickname` varchar(255) DEFAULT NULL,
      `zan_user_avatar` varchar(255) DEFAULT NULL,
      `zan_user_sex` int(11) DEFAULT '0',
      `zan_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_love_district`(
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `level` tinyint(3) unsigned DEFAULT '0',
      `upid` mediumint(8) unsigned DEFAULT '0',
      `displayorder` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_love_sign`(
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT '0',
      `sign_txt` varchar(255) DEFAULT NULL,
      `time_key` int(11) DEFAULT '0',
      `sign_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
          
    CREATE TABLE IF NOT EXISTS `pre_tom_love_focuspic` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) DEFAULT NULL,
      `picurl` varchar(255) DEFAULT NULL,
      `link` varchar(255) DEFAULT NULL,
      `fsort` int(11) DEFAULT '10',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM; 

EOF;

    runquery($sql);

    $sql = '';

    $tom_love_order_field = C::t('#tom_love#tom_love_order')->fetch_all_field();
    if (!isset($tom_love_order_field['order_type'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love_order')." ADD `order_type` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_love_order_field['time_value'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_love_order')." ADD `time_value` int(11) DEFAULT '0';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }


    echo 'OK';exit;
    
}else{
    exit('Access Denied');
}
