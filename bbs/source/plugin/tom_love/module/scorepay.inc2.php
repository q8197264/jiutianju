<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_love/oauth2.php';

$scoreArr = array(
    '1' => $jyConfig['one_yuan_score']*1,
    '2' => $jyConfig['one_yuan_score']*2,
    '5' => $jyConfig['one_yuan_score']*5,
    '10' => $jyConfig['one_yuan_score']*10,
    '20' => $jyConfig['one_yuan_score']*20,
);

require_once libfile('function/discuzcode');
$scoreString = discuzcode($jyConfig['score_page'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$payUrl   = "plugin.php?id=tom_love:pay";

$orderlogData = C::t('#tom_love#tom_love_order')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND order_status=2 ","ORDER BY order_time DESC",0,50);
$orderlogList = array();
if(is_array($orderlogData) && !empty($orderlogData)){
    foreach ($orderlogData as $key => $value){
        $orderlogList[$key] = $value;
    }
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:scorepay");
?>

