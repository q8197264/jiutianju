<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/discuzcode');
$add_weixin_msg = discuzcode($jyConfig['add_weixin_msg'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);


include template("tom_love:pc/weixin");

