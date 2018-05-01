<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;

$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);

if(!$tongchengInfo){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
}

if($tongchengInfo['shenhe_status'] != 1){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist&type=4");exit;
}

$content = contentFormat($tongchengInfo['content']);
$title = cutstr($content,20,"...");
$desc = strip_tags($content);
$desc = str_replace("\n","",$desc);
$desc = str_replace("\r","",$desc);
$desc = str_replace("\r\n","",$desc);
$desc = cutstr($desc,80,"...");

$userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
$modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($tongchengInfo['model_id']);
$typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
$siteInfo = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
if($tongchengInfo['site_id'] > 1){
    $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($tongchengInfo['site_id']);
}

$attrList = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
$tagList = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
$photoListTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
$photoList = array();
if(is_array($photoListTmp) && !empty($photoListTmp)){
    foreach ($photoListTmp as $kk => $vv){
        if(!preg_match('/^http/', $vv['picurl']) ){
            $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
        }else{
            $picurl = $vv['picurl'];
        }
        $photoList[$kk] = $picurl;
    }
}
$photoListStr = implode('|', $photoList);

$modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,50);
    
$modelList = array();
$i = 1;
$modelCount = 0;
if(is_array($modelListTmp) && !empty($modelListTmp)){
    foreach ($modelListTmp as $key => $value){
        $modelList[$key] = $value;
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        $modelList[$key]['picurl'] = $picurl;
        $modelList[$key]['i'] = $i;
        $i++;
        $modelCount++;
    }
}

DB::query("UPDATE ".DB::table('tom_tongcheng')." SET clicks=clicks+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id=".$tongcheng_id;
$shareLogo  = $userInfo['picurl'];
if(is_array($photoList) && !empty($photoList) && !empty($photoList[0])){
    $shareLogo = $photoList[0];
}

$messageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=create&tongcheng_id=".$tongchengInfo['id'].'&to_user_id='.$userInfo['id'].'&formhash='.FORMHASH;
$tousuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=tousu&tongcheng_id=".$tongchengInfo['id'];

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:info");  




