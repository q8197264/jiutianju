<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_love/oauth2.php';

$yuan_score_listStr = str_replace("\r\n","{n}",$jyConfig['yuan_score_list']); 
$yuan_score_listStr = str_replace("\n","{n}",$yuan_score_listStr);
$yuan_score_listTmpArr = explode("{n}", $yuan_score_listStr);

$yuan_scoreArr = array();
if(is_array($yuan_score_listTmpArr) && !empty($yuan_score_listTmpArr)){
    foreach ($yuan_score_listTmpArr as $key => $value){
        if(!empty($value)){
            list($yuan, $score) = explode("|", $value);
            $yuan = intval($yuan);
            $score = intval($score);
            if(!empty($yuan) && !empty($score)){
                $yuan_scoreArr[$yuan] = $score;
            }
        }
    }
}

if(empty($__UserInfo['openid']) && !empty($openid)){
    $updateData = array();
    $updateData['openid'] = $openid;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
}

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

