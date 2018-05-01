<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_buttons')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_news')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_replys')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_users')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_wfl')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjbox_token')."");
$finish = TRUE;
?>