<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

session_start();
define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$ucenterConfig = $_G['cache']['plugin']['tom_ucenter'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
require_once libfile('function/discuzcode');
$appid = trim($ucenterConfig['wxpay_appid']);  
$appsecret = trim($ucenterConfig['wxpay_appsecret']);
$prand = rand(1, 1000);
$cssVersion = "20160829";

include DISCUZ_ROOT.'./source/plugin/tom_ucenter/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

$__UcenterMemberInfo = array();
$userStatus = false;
$cookieOpenid = getcookie('tom_ucenter_member_openid');
if(!empty($cookieOpenid)){
    $__UcenterMemberInfo = C::t('#tom_ucenter#tom_ucenter_member_openid')->fetch_by_openid($cookieOpenid);
    if($__UcenterMemberInfo && !empty($__UcenterMemberInfo['openid'])){
        $userStatus = true;
    }
}

if(!$userStatus){
    $openid     = '';
    $nickname   = '';
    $headimgurl = '';
    include DISCUZ_ROOT.'./source/plugin/tom_ucenter/oauth3.php';
    $nickname = diconv($nickname,'utf-8');
    $__UcenterMemberInfo = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_openid($openid);
    if($__UcenterMemberInfo){
        $updateData = array();
        $updateData['nickname']    = $nickname;
        $updateData['picurl']     = $headimgurl;
        C::t('#tom_ucenter#tom_ucenter_member')->update($__UcenterMemberInfo['uid'],$updateData);
        $lifeTime = 86400;
        dsetcookie('tom_ucenter_member_openid',$openid,$lifeTime);
    }else{
        $insertData = array();
        $insertData['openid']      = $openid;
        $insertData['nickname']    = $nickname;
        $insertData['picurl']      = $headimgurl;
        $insertData['add_time']    = TIMESTAMP;
        if(C::t('#tom_ucenter#tom_ucenter_member')->insert($insertData)){
            $__UcenterMemberInfo = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_openid($openid);
            $lifeTime = 86400;
            dsetcookie('tom_ucenter_member_openid',$openid,$lifeTime);
        }
    }
}

echo 'no index';exit;
