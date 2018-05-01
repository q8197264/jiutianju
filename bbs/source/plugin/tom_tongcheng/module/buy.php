<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'top';

$tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);

$modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($tongchengInfo['model_id']);
$typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);

$pay_price_arr = array();
for($i=1;$i<=30;$i++){
    $pay_price_arr[$i] = $typeInfo['top_price']*$i;
}

$toptime = dgmdate($tongchengInfo['toptime'],"Y-m-d",$tomSysOffset);

$payTopUrl = "plugin.php?id=tom_tongcheng:pay&site={$site_id}&act=top&formhash=".$formhash;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:buy");  




