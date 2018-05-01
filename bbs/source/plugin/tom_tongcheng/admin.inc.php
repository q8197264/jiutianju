<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$Lang = $scriptlang['tom_tongcheng'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tongcheng&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tongcheng&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_tongcheng&pmod=admin';
$uSiteUrl = urlencode($_G['siteurl']);

$tomSysOffset = getglobal('setting/timeoffset');

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.form.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tongcheng.core.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.upload.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';
$tongchengConfig = get_tongcheng_config($pluginid);
$Lang = formatLang($Lang);

if($_GET['tmod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/index.php';
}else if($_GET['tmod'] == 'model'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/model.php';
}else if($_GET['tmod'] == 'type'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/type.php';
}else if($_GET['tmod'] == 'cate'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/cate.php';
}else if($_GET['tmod'] == 'attr'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/attr.php';
}else if($_GET['tmod'] == 'tag'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/tag.php';
}else if($_GET['tmod'] == 'topnews'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/topnews.php';
}else if($_GET['tmod'] == 'user'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/user.php';
}else if($_GET['tmod'] == 'tousu'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/tousu.php';
}else if($_GET['tmod'] == 'focuspic'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/focuspic.php';
}else if($_GET['tmod'] == 'order'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/order.php';
}else if($_GET['tmod'] == 'common'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/common.php';
}else if($_GET['tmod'] == 'sites'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/sites.php';
}else if($_GET['tmod'] == 'district'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/district.php';
}else if($_GET['tmod'] == 'nav'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/nav.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/addon.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/index.php';
}


