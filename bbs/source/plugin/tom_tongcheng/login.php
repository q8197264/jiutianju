<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$__UserInfo = array();
$userStatus = false;
$cookieOpenid = getcookie('tom_tongcheng_user_openid');
if(!empty($cookieOpenid)){
    $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($cookieOpenid);
    if($__UserInfo && !empty($__UserInfo['openid'])){
        $userStatus = true;
    }
}

if(!$userStatus){
    $openid     = '';
    $nickname   = '';
    $headimgurl = '';
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/oauth3.php';
    $nickname = diconv($nickname,'utf-8');
    $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($openid);
    if($__UserInfo){
        $updateData = array();
        $updateData['nickname']   = $nickname;
        $updateData['picurl']     = $headimgurl;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
        $lifeTime = 86400;
        dsetcookie('tom_tongcheng_user_openid',$openid,$lifeTime);
    }else{
        $insertData = array();
        $insertData['openid']      = $openid;
        $insertData['nickname']    = $nickname;
        $insertData['picurl']      = $headimgurl;
        $insertData['add_time']    = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_user')->insert($insertData)){
            $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($openid);
            $lifeTime = 86400;
            dsetcookie('tom_tongcheng_user_openid',$openid,$lifeTime);
        }
    }
}

$pmNewNum = C::t('#tom_tongcheng#tom_tongcheng_pm')->fetch_all_newnum(" AND user_id={$__UserInfo['id']} ");
$noReadTzCount = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_count(" AND user_id='{$__UserInfo['id']}' AND is_read=0 ");
$pmNewNum = $pmNewNum + $noReadTzCount;

$__UserInfo['groupid'] = 0;
$__UserInfo['groupsiteid'] = 0;
if($tongchengConfig['manage_user_id'] == $__UserInfo['id']){
    $__UserInfo['groupid'] = 1;
}
if($__UserInfo['groupid'] == 0){
    $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_manage_user_id($__UserInfo['id']);
    if($siteInfoTmp){
        $tcadminfilename = DISCUZ_ROOT.'./source/plugin/tom_tcadmin/tom_tcadmin.inc.php';
        if(file_exists($tcadminfilename)){
            $__UserInfo['groupid'] = 2;
            $__UserInfo['groupsiteid'] = $siteInfoTmp['id'];
        }
    }
}

