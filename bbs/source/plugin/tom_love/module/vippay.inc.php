<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_love/oauth2.php';

$yuan_vip1_listStr = str_replace("\r\n","{n}",$jyConfig['yuan_vip1_list']); 
$yuan_vip1_listStr = str_replace("\n","{n}",$yuan_vip1_listStr);
$yuan_vip1_listTmpArr = explode("{n}", $yuan_vip1_listStr);

$yuan_vip1Arr = array();
if(is_array($yuan_vip1_listTmpArr) && !empty($yuan_vip1_listTmpArr)){
    foreach ($yuan_vip1_listTmpArr as $key => $value){
        if(!empty($value)){
            list($month, $price) = explode("|", $value);
            $month = intval($month);
            $price = intval($price);
            if(!empty($month) && !empty($price)){
                $yuan_vip1Arr[$month] = $price;
            }
        }
    }
}

if(empty($__UserInfo['openid']) && !empty($openid)){
    $updateData = array();
    $updateData['openid'] = $openid;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
}

$vipTime = dgmdate($__UserInfo['vip_time'], 'Y-m-d',$tomSysOffset);

require_once libfile('function/discuzcode');
$vip_payString = discuzcode($jyConfig['vip_pay_msg'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$payUrl   = "plugin.php?id=tom_love:pay";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:vippay");

