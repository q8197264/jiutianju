<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$TOMCLOUDHOST = "http://discuzapi.tomwx.net";
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_love&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=90&addonId=tom_love&baseUrl='.  urlencode($urlBaseUrl));
