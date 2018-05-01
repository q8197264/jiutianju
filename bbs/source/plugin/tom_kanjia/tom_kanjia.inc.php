<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

session_start();
define('TPL_DEFAULT', true);
$kanjiaConfig = $_G['cache']['plugin']['tom_kanjia'];
$tomSysOffset = getglobal('setting/timeoffset');
$formhash = FORMHASH;
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
require_once libfile('function/discuzcode');
$uSiteUrl = urlencode($_G['siteurl']);
$appid = trim($kanjiaConfig['kanjia_appid']);  
$appsecret = trim($kanjiaConfig['kanjia_appsecret']);
$prand = rand(1, 1000);
$cssJsVersion = "20160802";

if($_GET['mod'] == 'index'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/module/index.php';
    
}else if($_GET['mod'] == 'add'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/module/add.php';
    
}else if($_GET['mod'] == 'ajax'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/module/ajax.php';
    
}else if($_GET['mod'] == 'list'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/module/list.php';
    
}else{
    
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/module/add.php';
}

?>
