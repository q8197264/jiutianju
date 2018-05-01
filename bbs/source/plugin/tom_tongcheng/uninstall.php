<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_tongcheng;
DROP TABLE IF EXISTS pre_tom_tongcheng_attr;
DROP TABLE IF EXISTS pre_tom_tongcheng_collect;
DROP TABLE IF EXISTS pre_tom_tongcheng_common;
DROP TABLE IF EXISTS pre_tom_tongcheng_focuspic;
DROP TABLE IF EXISTS pre_tom_tongcheng_model;
DROP TABLE IF EXISTS pre_tom_tongcheng_model_attr;
DROP TABLE IF EXISTS pre_tom_tongcheng_model_cate;
DROP TABLE IF EXISTS pre_tom_tongcheng_model_tag;
DROP TABLE IF EXISTS pre_tom_tongcheng_model_type;
DROP TABLE IF EXISTS pre_tom_tongcheng_order;
DROP TABLE IF EXISTS pre_tom_tongcheng_photo;
DROP TABLE IF EXISTS pre_tom_tongcheng_pm;
DROP TABLE IF EXISTS pre_tom_tongcheng_pm_lists;
DROP TABLE IF EXISTS pre_tom_tongcheng_pm_message;
DROP TABLE IF EXISTS pre_tom_tongcheng_refresh_log;
DROP TABLE IF EXISTS pre_tom_tongcheng_tag;
DROP TABLE IF EXISTS pre_tom_tongcheng_topnews;
DROP TABLE IF EXISTS pre_tom_tongcheng_tousu;
DROP TABLE IF EXISTS pre_tom_tongcheng_user;
DROP TABLE IF EXISTS pre_tom_tongcheng_visitor;
DROP TABLE IF EXISTS pre_tom_tongcheng_district;
DROP TABLE IF EXISTS pre_tom_tongcheng_nav;
DROP TABLE IF EXISTS pre_tom_tongcheng_sites;
DROP TABLE IF EXISTS pre_tom_tongcheng_tz;
        
EOF;

runquery($sql);

$finish = TRUE;

