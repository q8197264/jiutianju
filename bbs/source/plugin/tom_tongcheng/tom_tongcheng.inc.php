<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

session_start();
define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
require_once libfile('function/discuzcode');
$appid = trim($tongchengConfig['wxpay_appid']);  
$appsecret = trim($tongchengConfig['wxpay_appsecret']);
$prand = rand(1, 1000);
$cssJsVersion = "20170428";

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

$wxJssdkConfig = array();
$wxJssdkConfig["appId"]     = "";
$wxJssdkConfig["timestamp"] = time();
$wxJssdkConfig["nonceStr"]  = "";
$wxJssdkConfig["signature"] = "";
$wxJssdkConfig = $weixinClass->get_jssdk_config();
$shareTitle = $tongchengConfig['wx_share_title'];
$shareDesc  = $tongchengConfig['wx_share_desc'];
$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index";
$shareLogo  = $tongchengConfig['wx_share_pic'];

$__SitesInfo = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
$__CityInfo = array('id'=>0,'name'=>'');
if($site_id > 1){
    $sitesInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);
    if($sitesInfoTmp){
        $__SitesInfo = $sitesInfoTmp;
        if($__SitesInfo['status'] == 2){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site=1&mod=index");exit;
        }
        if(!empty($__SitesInfo['city_id'])){
            $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($__SitesInfo['city_id']);
            if($cityInfoTmp){
                $__CityInfo = $cityInfoTmp;
            }
        }
        $shareTitle = $__SitesInfo['share_title'];
        $shareDesc  = $__SitesInfo['share_desc'];
        if(!preg_match('/^http/', $__SitesInfo['share_pic']) ){
            $shareLogo = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['share_pic'];
        }else{
            $shareLogo = $__SitesInfo['share_pic'];
        }
    }else{
        $site_id = 1;
    }
}
if($site_id == 1){
    $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);
    if($cityInfoTmp){
        $__CityInfo = $cityInfoTmp;
    }
}
$client = $_SERVER['HTTP_USER_AGENT'];
if (strpos($client , 'MicroMessenger') === true) {
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/login.php';
}

$ajaxLoadListUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=list&&formhash='.$formhash;
$ajaxCollectUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=collect&&formhash='.$formhash;
$ajaxClicksUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=clicks&&formhash='.$formhash.'&tongcheng_id=';
$searchUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=get_search_url';
$ajaxCommonClicksUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=commonClicks&&formhash='.$formhash;
$ajaxUpdateTopstatusUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=updateTopstatus&&formhash='.$formhash;
$ajaxAutoClickUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=auto_click&&formhash='.$formhash;
$ajaxShenheSmsUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=shenhe_sms&&formhash='.$formhash;

$__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
if(!$__CommonInfo){
    $insertData = array();
    $insertData['id']      = 1;
    C::t('#tom_tongcheng#tom_tongcheng_common')->insert($insertData);
}
if($site_id > 1){
    $__siteCommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id($site_id);
    if(!$__siteCommonInfo){
        $insertData = array();
        $insertData['id']      = $site_id;
        C::t('#tom_tongcheng#tom_tongcheng_common')->insert($insertData);
    }
}

if($_GET['mod'] == 'index'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/index.php';
    
}else if($_GET['mod'] == 'fabu'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/fabu.php';
    
}else if($_GET['mod'] == 'search'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/search.php';
    
}else if($_GET['mod'] == 'list'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/list.php';
    
}else if($_GET['mod'] == 'info'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/info.php';
    
}else if($_GET['mod'] == 'home'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/home.php';
    
}else if($_GET['mod'] == 'personal'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/personal.php';
    
}else if($_GET['mod'] == 'message'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/message.php';
    
}else if($_GET['mod'] == 'mycollect'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/mycollect.php';
    
}else if($_GET['mod'] == 'mylist'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/mylist.php';
    
}else if($_GET['mod'] == 'tousu'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/tousu.php';
    
}else if($_GET['mod'] == 'buy'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/buy.php';
    
}else if($_GET['mod'] == 'article'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/article.php';
    
}else if($_GET['mod'] == 'myorder'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/myorder.php';
    
}else if($_GET['mod'] == 'phone'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/phone.php';
    
}else if($_GET['mod'] == 'edit'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/edit.php';
    
}else if($_GET['mod'] == 'editcate'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/editcate.php';
    
}else if($_GET['mod'] == 'managerList'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/managerList.php';
    
}else if($_GET['mod'] == 'upload'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/upload.php';
    
}else{
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/index.php';
}


