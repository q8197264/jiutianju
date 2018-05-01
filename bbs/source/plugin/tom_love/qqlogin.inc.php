<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
session_start();
loaducenter();
define('TPL_DEFAULT', true);
$jyConfig = $_G['cache']['plugin']['tom_love'];
$tomSysOffset = getglobal('setting/timeoffset');
$Lang = $scriptlang['tom_love'];
$nowYear = dgmdate($_G['timestamp'], 'Y',$tomSysOffset);
$nowTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
$formhash = FORMHASH;

if(empty($_G['uid'])){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love:register");exit;
}

$user = uc_get_user($_G['uid'],1);
if($user['0'] > 0){

    $getUserInfoByBbsid = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($user['0']);
    $getUserInfoByOpenid = C::t('#tom_love#tom_love')->fetch_by_openid($openid);
    if($getUserInfoByBbsid){
        $lifeTime = 86400*30;
        dsetcookie('tom_love_uid',$getUserInfoByBbsid['id'],$lifeTime);
        $_SESSION['tom_love_uid'] = $getUserInfoByBbsid['id'];

    }else if($openid && $getUserInfoByOpenid){
        $updateData = array();
        $updateData['bbs_uid'] = $user['0'];
        $updateData['bbs_username'] = $user['1'];
        C::t('#tom_love#tom_love')->update($getUserInfoByOpenid['id'],$updateData);
        $lifeTime = 86400*30;
        dsetcookie('tom_love_uid',$getUserInfoByOpenid['id'],$lifeTime);
        $_SESSION['tom_love_uid'] = $getUserInfoByOpenid['id'];
    }else{
        $insertData = array();
        $insertData['bbs_uid'] = $user['0'];
        $insertData['bbs_username'] = $user['1'];
        $insertData['nickname'] = $user['1'];
        $insertData['openid'] = $openid;
        $insertData['score'] = $jyConfig['score_num'];
        $insertData['avatar'] = "source/plugin/tom_love/images/avatar_default.jpg";
        $insertData['sex'] = 0;
        $insertData['add_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->insert($insertData);

        $getUserInfoByBbsid = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($user['0']);

        $lifeTime = 86400*30;
        dsetcookie('tom_love_uid',$getUserInfoByBbsid['id'],$lifeTime);
        $_SESSION['tom_love_uid'] = $getUserInfoByBbsid['id'];
    }

    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love");exit;

}

dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love:register");exit;

