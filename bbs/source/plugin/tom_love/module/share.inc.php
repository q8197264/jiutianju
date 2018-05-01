<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/discuzcode');
$shareString = discuzcode($jyConfig['share_page'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:share");
