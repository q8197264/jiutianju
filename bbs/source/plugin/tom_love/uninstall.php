<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_love;
DROP TABLE IF EXISTS pre_tom_love_guanxi;
DROP TABLE IF EXISTS pre_tom_love_pic;
DROP TABLE IF EXISTS pre_tom_love_rec;
DROP TABLE IF EXISTS pre_tom_love_renzheng;
DROP TABLE IF EXISTS pre_tom_love_scorelog;
DROP TABLE IF EXISTS pre_tom_love_share;
DROP TABLE IF EXISTS pre_tom_love_shuoshuo;
DROP TABLE IF EXISTS pre_tom_love_tz;
DROP TABLE IF EXISTS pre_tom_love_order;
DROP TABLE IF EXISTS pre_tom_love_report;
DROP TABLE IF EXISTS pre_tom_love_common;
DROP TABLE IF EXISTS pre_tom_love_shuoshuo_photo;
DROP TABLE IF EXISTS pre_tom_love_shuoshuo_reply;
DROP TABLE IF EXISTS pre_tom_love_shuoshuo_zan;
DROP TABLE IF EXISTS pre_tom_love_district;
DROP TABLE IF EXISTS pre_tom_love_sign;
DROP TABLE IF EXISTS pre_tom_love_focuspic;
EOF;

runquery($sql);

$finish = TRUE;
