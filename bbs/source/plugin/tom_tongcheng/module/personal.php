<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'list';


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:personal");  




