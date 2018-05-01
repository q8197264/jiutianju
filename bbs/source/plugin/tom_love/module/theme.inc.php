<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

$uploadUrl = "plugin.php?id=tom_love&mod=upload&act=theme&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:theme");

