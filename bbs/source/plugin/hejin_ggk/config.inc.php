<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//插件目录常量
define('HEJIN_PATH', $_G['siteurl'].'source/plugin/hejin_ggk/');
define('HEJIN_ROOT', dirname(__FILE__));
define('HEJIN_URL', $_G['siteurl'].'plugin.php?id=hejin_ggk');
define('SITE_URL', $_G['siteurl']);
$SELF = $_SERVER["PHP_SELF"];
$plugininfo = $_G['cache']['plugin']['hejin_ggk'];
$hejin_box = $_G['cache']['plugin']['hejin_box'];



$uid = intval($_G['uid']);
$formhash = $_G['formhash'];

if($_G['charset']=='gbk'){
	$charset = 'gb2312';
}
elseif($_G['charset']=='utf-8'){
		
	$charset = 'UTF-8';
		
}


?>