<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_ucenter_address;
DROP TABLE IF EXISTS pre_tom_ucenter_district;
DROP TABLE IF EXISTS pre_tom_ucenter_member;
DROP TABLE IF EXISTS pre_tom_ucenter_scorelog;

EOF;

runquery($sql);

$finish = TRUE;

