<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$pluginScriptLang = $scriptlang['tom_love'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowYear = dgmdate($_G['timestamp'], 'Y',$tomSysOffset);

$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_love&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_love&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_love&pmod=admin';

include DISCUZ_ROOT.'./source/plugin/tom_love/class/admin.love.php';
$jyConfig = get_love_config($pluginid);
$pluginScriptLang = formatLang($pluginScriptLang);

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_love/config/works.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_love/config/works.utf.php';
}

include DISCUZ_ROOT.'./source/plugin/tom_love/tom.func.php';
if($_GET['tmod'] == 'user'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/user.inc.php';
    
}else if($_GET['tmod'] == 'rec'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/rec.inc.php';
    
}else if($_GET['tmod'] == 'rz'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/rz.inc.php';
    
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/addon.inc.php';
    
}else if($_GET['tmod'] == 'ss'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/ss.inc.php';
    
}else if($_GET['tmod'] == 'report'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/report.inc.php';
    
}else if($_GET['tmod'] == 'district'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/district.inc.php';

}else if($_GET['tmod'] == 'focuspic'){ 
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/focuspic.inc.php';

}else{
    include DISCUZ_ROOT.'./source/plugin/tom_love/admin/user.inc.php';
}

