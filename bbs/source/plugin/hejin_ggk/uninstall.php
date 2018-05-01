<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
DB::query("DROP TABLE IF EXISTS ".DB::table('hjggk_ggks')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjggk_gkjilus')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjggk_jfjls')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjggk_jiangs')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('hjggk_zjjls')."");
$finish = TRUE;
?>