<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_love/oauth2.php';

if(empty($__UserInfo['openid']) && !empty($openid)){
    $updateData = array();
    $updateData['openid'] = $openid;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
}

require_once libfile('function/discuzcode');
$vip_payString = discuzcode($jyConfig['vip_pay_msg'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:templatesms");

