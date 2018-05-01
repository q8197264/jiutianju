<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$kid      = isset($_GET['kid'])? intval($_GET['kid']):0;

$openid = '';
$nickname = '';
$headimgurl = '';
$subscribe = 0;
$appid = trim($kanjiaConfig['kanjia_appid']);
$appsecret = trim($kanjiaConfig['kanjia_appsecret']);
include DISCUZ_ROOT.'./source/plugin/tom_kanjia/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
$wxJssdkConfig = $weixinClass->get_jssdk_config();
if($kanjiaConfig['oauth2_check'] == 1){
    if($kanjiaConfig['oauth2_userinfo'] == 1 && $kanjiaConfig['oauth2_guanzu'] == 0){
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth3.php';
    }else if($kanjiaConfig['oauth2_guanzu'] == 1){
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth4.php';
    }else{
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth2.php';
    }
}
if(!empty($nickname)){
    $nickname = diconv($nickname,'utf-8');
}

$kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($kid);
if(!$kanjiaInfo){
    $kanjiaList = C::t('#tom_kanjia#tom_kanjia')->fetch_all_list("","ORDER BY id DESC",0,1);
    $kanjiaInfo = $kanjiaList['0'];
    $kid = $kanjiaInfo['id'];
}

$cookieUserid = getcookie('tom_kanjia_kid'.$kid.'_userid');
if(!$cookieUserid){
    if($_SESSION['tom_kanjia_kid'.$kid.'_userid']){
        $cookieUserid = $_SESSION['tom_kanjia_kid'.$kid.'_userid'];
    }
}
if($cookieUserid && $cookieUserid > 0){
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($cookieUserid);
    if($userInfo){
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$cookieUserid}");
        exit;
    }
}else{
    if($kanjiaConfig['oauth2_check'] == 1 && !empty($openid)){
        $userInfoOpenidTmp = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_kid_openid($kid,$openid);
        if($userInfoOpenidTmp){
            $lifeTime = 86400*30;
            $_SESSION['tom_kanjia_kid'.$kid.'_userid'] = $userInfoOpenidTmp['id'];
            dsetcookie('tom_kanjia_kid'.$kid.'_userid',$userInfoOpenidTmp['id'],$lifeTime);
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$userInfoOpenidTmp['id']}");
            exit;
        }
    }
}

if(!preg_match('/^http:/', $kanjiaInfo['pic_url']) ){
    $kanjia_pic_url = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$kanjiaInfo['pic_url'];
}else{
    $kanjia_pic_url = $kanjiaInfo['pic_url'];
}

if(!preg_match('/^http:/', $kanjiaInfo['ads_picurl']) ){
    $ads_picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$kanjiaInfo['ads_picurl'];
}else{
    $ads_picurl = $kanjiaInfo['ads_picurl'];
}

$content = stripslashes($kanjiaInfo['content']);
$add_msg = discuzcode($kanjiaInfo['add_msg'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
$clicks_num = $kanjiaInfo['clicks'] + $kanjiaInfo['virtual_clicks'];

if($kanjiaInfo['user_count'] == 0){
    $userCount = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} ");
    $updateData = array();
    $updateData['user_count'] = $userCount;
    C::t('#tom_kanjia#tom_kanjia')->update($kid,$updateData);
    $kanjiaInfo['user_count'] = $userCount;
}

DB::query("UPDATE ".DB::table('tom_kanjia')." SET clicks=clicks+1 WHERE id='{$kid}'", 'UNBUFFERED');

$ajaxUrl = "plugin.php?id=tom_kanjia&mod=ajax";

$showGuanzuBox = 0;
if(isset($_GET['from']) && !empty($_GET['from'])){
    $showGuanzuBox = 1;
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && $kanjiaConfig['open_in_wx'] == 1) {
    include template("tom_kanjia:weixin"); 
}else{
    if($kanjiaInfo['template_id'] == 'baise'){
        include template("tom_kanjia:baise/add");  
    }else if($kanjiaInfo['template_id'] == 'huangse'){
        include template("tom_kanjia:huangse/add");  
    }else if($kanjiaInfo['template_id'] == 'lanlvse'){
        include template("tom_kanjia:lanlvse/add");  
    }else if($kanjiaInfo['template_id'] == 'lanse'){
        include template("tom_kanjia:lanse/add");  
    }else{
        include template("tom_kanjia:add");  
    }
}
?>
