<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'list';


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:personal");  




