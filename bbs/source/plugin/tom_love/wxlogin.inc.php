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

$openid = '';
$nickname = '';
$headimgurl = '';
$appid = trim($jyConfig['love_appid']);  
$appsecret = trim($jyConfig['love_appsecret']); 
include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
include DISCUZ_ROOT.'./source/plugin/tom_love/oauth3.php';
$nickname = diconv($nickname,'utf-8');

if(function_exists('curl_init')){
    $imageData = get_html($headimgurl);
    $imageDir = "/source/plugin/tom_love/data/avatar/".date("Ym")."/";
    $imageName = "source/plugin/tom_love/data/avatar/".date("Ym")."/".md5($headimgurl).".jpg";

    $tomDir = DISCUZ_ROOT.'.'.$imageDir;
    if(!is_dir($tomDir)){
        mkdir($tomDir, 0777,true);
    }else{
        chmod($tomDir, 0777); 
    }
    $upload =  file_put_contents(DISCUZ_ROOT.'./'.$imageName, $imageData);
    if($upload){
        $headimgurl = $_G['siteurl'].$imageName;
    }
}

$getUserInfoByOpenid = C::t('#tom_love#tom_love')->fetch_by_openid($openid);
if($getUserInfoByOpenid){
    $lifeTime = 86400*30;
    dsetcookie('tom_love_uid',$getUserInfoByOpenid['id'],$lifeTime);
    $_SESSION['tom_love_uid'] = $getUserInfoByOpenid['id'];
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love");exit;
}

$username   = 'wx'.TIMESTAMP.mt_rand(1, 99);
$password   = mt_rand(111111, 999999);
$email      = 'wx'.TIMESTAMP.mt_rand(1, 99).'@null.null';

$uid = uc_user_register($username, $password, $email, '', '', $_G['clientip']);
if($uid <= 0) {
    
    echo $uid;exit;
    
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love:register");exit;
}
$init_arr = array('credits' => explode(',', $_G['setting']['initcredits']));
C::t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $_G['setting']['newusergroupid'], $init_arr);

if($_G['setting']['regctrl'] || $_G['setting']['regfloodctrl']) {
    C::t('common_regip')->delete_by_dateline($_G['timestamp']-($_G['setting']['regctrl'] > 72 ? $_G['setting']['regctrl'] : 72)*3600);
    if($_G['setting']['regctrl']) {
        C::t('common_regip')->insert(array('ip' => $_G['clientip'], 'count' => -1, 'dateline' => $_G['timestamp']));
    }
}

if($_G['setting']['regverify'] == 2) {
    C::t('common_member_validate')->insert(array(
        'uid' => $uid,
        'submitdate' => $_G['timestamp'],
        'moddate' => 0,
        'admin' => '',
        'submittimes' => 1,
        'status' => 0,
        'message' => '',
        'remark' => '',
    ), false, true);
    manage_addnotify('verifyuser');
}

include_once libfile('function/stat');
updatestat('register');

$insertData = array();
$insertData['bbs_uid'] = $uid;
$insertData['bbs_username'] = $username;
$insertData['nickname'] = $nickname;
$insertData['openid'] = $openid;
$insertData['score'] = $jyConfig['score_num'];
$insertData['avatar'] = $headimgurl;
$insertData['sex'] = 0;
$insertData['add_time'] = TIMESTAMP;
C::t('#tom_love#tom_love')->insert($insertData);

$getUserInfoByBbsid = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($uid);

$lifeTime = 86400*30;
dsetcookie('tom_love_uid',$getUserInfoByBbsid['id'],$lifeTime);
$_SESSION['tom_love_uid'] = $getUserInfoByBbsid['id'];

dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love");exit;

