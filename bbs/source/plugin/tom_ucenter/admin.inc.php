<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$Lang = $scriptlang['tom_ucenter'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_ucenter&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_ucenter&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_ucenter&pmod=admin';

$tomSysOffset = getglobal('setting/timeoffset');


include DISCUZ_ROOT.'./source/plugin/tom_ucenter/class/tom.form.php';
include DISCUZ_ROOT.'./source/plugin/tom_ucenter/class/ucenter.core.php';
include DISCUZ_ROOT.'./source/plugin/tom_ucenter/class/tom.upload.php';
$ucenterConfig = get_ucenter_config($pluginid);
$Lang = formatLang($Lang);

if($_GET['tmod'] == 'member'){
    include DISCUZ_ROOT.'./source/plugin/tom_ucenter/admin/member.php';
}else if($_GET['tmod'] == 'district'){
    include DISCUZ_ROOT.'./source/plugin/tom_ucenter/admin/district.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_ucenter/admin/addon.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_ucenter/admin/goods.php';
}

