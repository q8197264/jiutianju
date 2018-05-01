<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = '';

$tom_kanjia_field = C::t('#tom_kanjia#tom_kanjia')->fetch_all_field();
if (!isset($tom_kanjia_field['dh_pwd'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `dh_pwd` varchar(255) DEFAULT NULL;\n";
}
if (!isset($tom_kanjia_field['ads_picurl'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `ads_picurl` varchar(255) DEFAULT NULL;\n";
}
if (!isset($tom_kanjia_field['ads_link'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `ads_link` varchar(255) DEFAULT NULL;\n";
}
if (!isset($tom_kanjia_field['buy_url'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `buy_url` varchar(255) DEFAULT NULL;\n";
}
if (!isset($tom_kanjia_field['clicks'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `clicks` int(11) DEFAULT '0';\n";
}
if (!isset($tom_kanjia_field['virtual_clicks'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `virtual_clicks` int(11) DEFAULT '0';\n";
}
if (!isset($tom_kanjia_field['user_count'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `user_count` int(11) DEFAULT '0';\n";
}
if (!isset($tom_kanjia_field['template_id'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `template_id` varchar(255) DEFAULT NULL;\n";
}
if (!isset($tom_kanjia_field['mp3_link'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia')." ADD `mp3_link` varchar(255) DEFAULT NULL;\n";
}

$tom_kanjia_user_field = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_field();
if (!isset($tom_kanjia_user_field['dh_status'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia_user')." ADD `dh_status` tinyint(4) DEFAULT '0';\n";
}
if (!isset($tom_kanjia_user_field['kanjia_time'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_kanjia_user')." ADD `kanjia_time` int(11) DEFAULT '0';\n";
}

if (!empty($sql)) {
	runquery($sql);
}

$finish = TRUE;

?>