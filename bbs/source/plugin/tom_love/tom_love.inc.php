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
$nowMonth = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),1,dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
$uSiteUrl = urlencode($_G['siteurl']);
$prand = rand(1, 1000);
$cssJsVersion = "20160919";

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_love/config/works.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_love/config/works.utf.php';
}

$publicModArr = array('index','search','phb','article');

$__UserInfo = array();
$noReadSmsNum = $noReadTzNum = 0;
$footerNavSmsClass = "footer_nav_sms1";

$cookieUserid = getcookie('tom_love_uid');
if(!$cookieUserid && $_SESSION['tom_love_uid']){
    $cookieUserid = $_SESSION['tom_love_uid'];
}
$cookieUserInfo = C::t('#tom_love#tom_love')->fetch_by_id($cookieUserid);
if($cookieUserInfo){
    $__UserInfo = $cookieUserInfo;
}

$userAvatarPic = '';
$userThemePic = '';
if($__UserInfo){
    if(!empty($__UserInfo['avatar'])){
        if(!preg_match('/^http/', $__UserInfo['avatar'])){
            if(strpos($__UserInfo['avatar'], 'source/plugin/tom_love') === false){
                $userAvatarPic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$__UserInfo['avatar'];
            }else{
                $userAvatarPic = $__UserInfo['avatar'];
            }
        }else{
            $userAvatarPic = $__UserInfo['avatar'];
        }
    }   
    if(!empty($__UserInfo['theme'])){
        if(!preg_match('/^http/', $__UserInfo['theme'])){
            if(strpos($__UserInfo['theme'], 'source/plugin/tom_love') === false){
                $userThemePic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$__UserInfo['theme'];
            }else{
                $userThemePic = $__UserInfo['theme'];
            }
        }else{
            $userThemePic = $__UserInfo['theme'];
        }
    }
}

if(!in_array($_GET['mod'], $publicModArr) && empty($__UserInfo)){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love:register");exit;
}

if(!empty($__UserInfo)){
    
    if($_GET['mod'] != 'my' && $_GET['mod'] != 'avatar' && $_GET['mod'] != 'renzheng' && $_GET['mod'] != 'upload'){
        if(empty($__UserInfo['nickname'])){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=my&act=info");exit;
        }

        if(empty($__UserInfo['avatar']) || $__UserInfo['avatar'] == "source/plugin/tom_love/images/avatar_default.jpg"){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=avatar");exit;
        }

        if($jyConfig['must_info']){
            if(empty($__UserInfo['describe'])){
                dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=my&act=info");exit;
            }
        }

        if($jyConfig['must_renzheng']){
            if($_GET['mod'] == 'sms'){
                if(isset($_GET['act']) && $_GET['act'] == 'view'){
                    if(empty($__UserInfo['renzheng'])){
                        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=renzheng");exit;
                    }
                }
            }else{
               if(empty($__UserInfo['renzheng'])){
                    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=renzheng");exit;
                } 
            }
        }
    }
    
    $noReadSmsNum = uc_pm_checknew($__UserInfo['bbs_uid'],0);
    $noReadTzNum = C::t('#tom_love#tom_love_tz')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND type=1 AND is_read=0 ");
    
    if($noReadSmsNum > 0 || $noReadTzNum > 0){
        $footerNavSmsClass = "footer_nav_sms3";
    }
    
    if($__UserInfo['vip_id'] > 0){
        if($__UserInfo['vip_time'] <= TIMESTAMP){
            $__UserInfo['vip_id'] = 0;
            $updateData = array();
            $updateData['vip_id'] = 0;
            C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
        }
    }
    
    if($__UserInfo['status'] == 2 && $_GET['mod'] != 'my' && $_GET['mod'] != 'article'){
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_love&mod=my");exit;
    }
    
}

$commonInfo = C::t('#tom_love#tom_love_common')->fetch_by_id(1);
if(is_array($commonInfo) && !empty($commonInfo)){}else{
    $insertData = array();
    $insertData['clicks'] = 0;
    C::t('#tom_love#tom_love_common')->insert($insertData);
    $commonInfo = C::t('#tom_love#tom_love_common')->fetch_by_id(1);
}
$ajaxClicksUrl = $_G['siteurl']."plugin.php?id=tom_love:ajax&act=clicks&formhash=".FORMHASH;

$appid = trim($jyConfig['love_appid']);  
$appsecret = trim($jyConfig['love_appsecret']); 
include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
$wxJssdkConfig = $weixinClass->get_jssdk_config();

$shareTitle = $jyConfig['share_title'];
$shareDesc = $jyConfig['share_desc'];
$shareLogo = $jyConfig['share_logo'];
$shareUrl = $_G['siteurl']."plugin.php?id=tom_love&mod=index";

if(!empty($__UserInfo)){
    $shareAjaxUrl = $_G['siteurl']."plugin.php?id=tom_love:ajax&act=share&uid={$__UserInfo['id']}&formhash=".FORMHASH;
}else{
    $shareAjaxUrl = $_G['siteurl']."plugin.php?id=tom_love";
}

$jyConfig['age_type_id'] = intval($jyConfig['age_type_id']);

if($_GET['mod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/index.inc.php';
    
}else if($_GET['mod'] == 'search'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/search.inc.php';
    
}else if($_GET['mod'] == 'shuoshuo'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/shuoshuo.inc.php';
    
}else if($_GET['mod'] == 'sms'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/sms.inc.php';
    
}else if($_GET['mod'] == 'my'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/my.inc.php';
    
}else if($_GET['mod'] == 'info'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/info.inc.php';
    
}else if($_GET['mod'] == 'avatar'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/avatar.inc.php';
    
}else if($_GET['mod'] == 'theme'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/theme.inc.php';
    
}else if($_GET['mod'] == 'photo'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/photo.inc.php';
    
}else if($_GET['mod'] == 'renzheng'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/renzheng.inc.php';
    
}else if($_GET['mod'] == 'share'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/share.inc.php';
    
}else if($_GET['mod'] == 'score'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/score.inc.php';
    
}else if($_GET['mod'] == 'scorepay'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/scorepay.inc.php';
    
}else if($_GET['mod'] == 'vippay'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/vippay.inc.php';
    
}else if($_GET['mod'] == 'guanxi'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/guanxi.inc.php';
    
}else if($_GET['mod'] == 'flowers'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/flowers.inc.php';
    
}else if($_GET['mod'] == 'upload'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/upload.inc.php';
    
}else if($_GET['mod'] == 'phb'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/phb.inc.php';
    
}else if($_GET['mod'] == 'rec'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/rec.inc.php';
    
}else if($_GET['mod'] == 'report'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/report.inc.php';
    
}else if($_GET['mod'] == 'templatesms'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/templatesms.inc.php';
    
}else if($_GET['mod'] == 'article'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/article.inc.php';
    
}else if($_GET['mod'] == 'scorelog'){
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/scorelog.inc.php';
    
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_love/module/index.inc.php';
}

