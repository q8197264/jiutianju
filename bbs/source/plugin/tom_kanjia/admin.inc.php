<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$LangTmp = $scriptlang['tom_kanjia'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_kanjia&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_kanjia&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_kanjia&pmod=admin';
$uSiteUrl = urlencode($_G['siteurl']);

$tomSysOffset = getglobal('setting/timeoffset');

$Lang  = array();
if(is_array($LangTmp) && !empty($LangTmp)){
    foreach ($LangTmp as $key => $value){
        $Lang[$key] = htmlspecialchars_decode($value);
    }
}

include DISCUZ_ROOT.'./source/plugin/tom_kanjia/tom.func.php';
if($_GET['tmod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/index.php';
}else if($_GET['tmod'] == 'price'){
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/price.php';
}else if($_GET['tmod'] == 'user'){
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/user.php';
}else if($_GET['tmod'] == 'log'){
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/log.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/addon.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_kanjia/admin/index.php';
}

?>
