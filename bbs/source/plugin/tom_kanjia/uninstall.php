<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_kanjia;
DROP TABLE IF EXISTS pre_tom_kanjia_log;
DROP TABLE IF EXISTS pre_tom_kanjia_price;
DROP TABLE IF EXISTS pre_tom_kanjia_user;

EOF;

runquery($sql);

$finish = TRUE;

?>