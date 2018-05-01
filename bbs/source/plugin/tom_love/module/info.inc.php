<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid     = isset($_GET['uid'])? trim($_GET['uid']):0;

$info = C::t('#tom_love#tom_love')->fetch_by_id($uid);
if($info['year'] > 0){    
    if($jyConfig['age_type_id'] == 1){
        $age = $nowYear - $info['year'];
    }else{
        $age = $nowYear - $info['year'] + 1;
    }
}else{
    $age = '';
}
$describe = dhtmlspecialchars($info['describe']);
$mate_demands = dhtmlspecialchars($info['mate_demands']);
$themeSrc = 'source/plugin/tom_love/images/Zhuytait.png';
if(!empty($info['theme'])){
    if(!preg_match('/^http/', $info['theme'])){
            if(strpos($info['theme'], 'source/plugin/tom_love') === false){
                $themeSrc = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$info['theme'];
            }else{
                $themeSrc = $info['theme'];
            }
        }else{
            $themeSrc = $info['theme'];
    }
}
if($info){    
    if(!preg_match('/^http/', $info['avatar'])){
            if(strpos($info['avatar'], 'source/plugin/tom_love') === false){
                $info['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$info['avatar'];
            }else{
                $info['avatar'] = $info['avatar'];
            }
        }else{
            $info['avatar'] = $info['avatar'];
    }
}

$picListTmp = C::t('#tom_love#tom_love_pic')->fetch_all_list(" AND user_id ={$uid} ","ORDER BY id DESC",0,3);
$picList = array();
if(is_array($picListTmp) && !empty($picListTmp)){
    foreach($picListTmp as $key => $value){
        $picList[$key] = $value;
        if(!preg_match('/^http:/', $value['pic_url'])){
            if(strpos($value['pic_url'], 'source/plugin/tom_love') === false){
                $picList[$key]['pic_url'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['pic_url'];
            }else{
                $picList[$key]['pic_url'] = $value['pic_url'];
            }
        }else{
            $picList[$key]['pic_url'] = $value['pic_url'];
        }
    }
}

$contactTag = 0;
$contactUser = C::t('#tom_love#tom_love_guanxi')->fetch_by_contact($__UserInfo['id'],$info['id']);
if($contactUser || $info['id']==$__UserInfo['id']){
    $contactTag = 1;
}

$anlianTag = 0;
$anlianUser = C::t('#tom_love#tom_love_guanxi')->fetch_by_anlian($__UserInfo['id'],$info['id']);
if($anlianUser || $info['id']==$__UserInfo['id']){
    $anlianTag = 1;
}

$contactUrl = "plugin.php?id=tom_love:ajax&act=contact&uid={$__UserInfo['id']}&gid={$info['id']}&formhash=".FORMHASH;
$anlianUrl = "plugin.php?id=tom_love:ajax&act=anlian&uid={$__UserInfo['id']}&gid={$info['id']}&formhash=".FORMHASH;
$helloUrl = "plugin.php?id=tom_love:ajax&act=hello&uid={$__UserInfo['id']}&tid={$info['id']}&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:info");

