<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,50);

$navList = array();
$modelList = array();
$i = 1;
$navCount = 0;
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
        
        $navList[$value['id']]['i'] = $i;
        $navList[$value['id']]['title']     = $value['name'];
        $navList[$value['id']]['picurl']    = $picurl;
        $navList[$value['id']]['link']      = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&model_id={$value['id']}";
        
        $i++;
        $navCount++;
    }
}

$navListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_nav')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY nsort ASC,id DESC ",0,100);
if(is_array($navListTmpTmp) && !empty($navListTmpTmp)){
    $i = 1;
    $navCount = 0;
    $navListTmp = $navList;
    $navList = array();
    foreach ($navListTmpTmp as $key => $value){
        $navList[$key] = $value;
        $navList[$key]['i'] = $i;
        
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        $navList[$key]['picurl'] = $picurl;
        
        if($value['type'] == 1 && $value['model_id'] > 0 && isset($navListTmp[$value['model_id']])){
            $navList[$key]['title']     = $navListTmp[$value['model_id']]['title'];
            $navList[$key]['picurl']    = $navListTmp[$value['model_id']]['picurl'];
            $navList[$key]['link']      = $navListTmp[$value['model_id']]['link'];
        }
        
        $i++;
        $navCount++;
    }
}

$topnewsList = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY paixu ASC,id DESC ",0,10);

$focuspicListTmp = C::t('#tom_tongcheng#tom_tongcheng_focuspic')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY fsort ASC,id DESC ",0,6);
$focuspicList = array();
foreach ($focuspicListTmp as $key => $value) {
    $focuspicList[$key] = $value;    
    if(!preg_match('/^http/', $value['picurl']) ){
        $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
    }else{
        $picurl = $value['picurl'];
    }
    $focuspicList[$key]['picurl'] = $picurl;
}

$oneCityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);

$citySitesArr = array();
if($__CityInfo['id'] > 0){
    $citySitesListTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND city_id={$__CityInfo['id']} AND status=1 "," ORDER BY id DESC ",0,100);
    if(is_array($citySitesListTmp) && !empty($citySitesListTmp)){
        foreach ($citySitesListTmp as $key => $value){
            $citySitesArr[] = $value['id'];
        }
    }
}
if(is_array($citySitesArr) && !empty($citySitesArr)){
    if($oneCityInfoTmp['id'] == $__CityInfo['id']){
        $citySitesArr[] = 1;
    }
}else{
    $citySitesArr = array('1');
}
$citySitesStr = implode(',', $citySitesArr);

$commonClicks = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_all_sun_clicks(" AND id IN({$citySitesStr}) ");
$clicksNum = $commonClicks + $tongchengConfig['virtual_clicks'];
$clicksNumTxt = $clicksNum;
if($clicksNum>10000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,2); 
}else if($clicksNum>1000000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,0);
}

$fabuNum = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count("  AND status=1 AND site_id IN({$citySitesStr}) ");
$fabuNum = $fabuNum + $tongchengConfig['virtual_fabunum'];
$fabuNumTxt = $fabuNum;
if($fabuNum>10000){
    $fabuNumTmp = $fabuNum/10000;
    $fabuNumTxt = number_format($fabuNumTmp,2); 
}else if($fabuNum>1000000){
    $fabuNumTmp = $fabuNum/10000;
    $fabuNumTxt = number_format($fabuNumTmp,0);
}

$logoSrc = "source/plugin/tom_tongcheng/images/logo.png";
if(!empty($tongchengConfig['logo_src'])){
    $logoSrc = $tongchengConfig['logo_src'];
}
$kefuQrcodeSrc = $tongchengConfig['kefu_qrcode'];
if($__SitesInfo['id'] > 1){
    if(!preg_match('/^http/', $__SitesInfo['logo'])){
        if(strpos($__SitesInfo['logo'], 'source/plugin/tom_tongcheng/') === FALSE){
            $logoSrc = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['logo'];
        }else{
            $logoSrc = $__SitesInfo['logo'];
        }
    }else{
        $logoSrc = $__SitesInfo['logo'];
    }
    if(!preg_match('/^http/', $__SitesInfo['kefu_qrcode'])){
        if(strpos($__SitesInfo['kefu_qrcode'], 'source/plugin/tom_tongcheng/') === FALSE){
            $kefuQrcodeSrc = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['kefu_qrcode'];
        }else{
            $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
        }
    }else{
        $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
    }
}

$subscribeFlag = 0;
$access_token = $weixinClass->get_access_token();
if($tongchengConfig['open_subscribe']==1 && !empty($__UserInfo['openid']) && !empty($access_token)){
    $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$__UserInfo['openid']}&lang=zh_CN";
    $return = get_html($get_user_info_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['subscribe'])){
            if($content['subscribe'] == 1){
                $subscribeFlag = 1;
            }else{
                $subscribeFlag = 2;
            }
        }
    }
}

$fabuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&model_id=";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:index");  




