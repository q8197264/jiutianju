<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$kid      = isset($_GET['kid'])? intval($_GET['kid']):0;
$uid      = isset($_GET['uid'])? intval($_GET['uid']):0;
$page      = isset($_GET['page'])? intval($_GET['page']):1;

$openid = '';
$nickname = '';
$headimgurl = '';
$subscribe = 0;
$appid = trim($kanjiaConfig['kanjia_appid']);
$appsecret = trim($kanjiaConfig['kanjia_appsecret']);
include DISCUZ_ROOT.'./source/plugin/tom_kanjia/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
$wxJssdkConfig = $weixinClass->get_jssdk_config();
if($kanjiaConfig['oauth2_check'] == 1){
    if($kanjiaConfig['oauth2_userinfo'] == 1 && $kanjiaConfig['oauth2_guanzu'] == 0){
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth3.php';
    }else if($kanjiaConfig['oauth2_guanzu'] == 1){
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth4.php';
    }else{
        include DISCUZ_ROOT.'./source/plugin/tom_kanjia/oauth2.php';
    }
}else{
    $kanjiaConfig['oauth2_userinfo'] = 0;
    $kanjiaConfig['oauth2_guanzu'] = 0;
}
if(!empty($nickname)){
    $nickname = diconv($nickname,'utf-8');
}

$kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($kid);
if(!$kanjiaInfo){
    $kanjiaList = C::t('#tom_kanjia#tom_kanjia')->fetch_all_list("","ORDER BY id DESC",0,1);
    $kanjiaInfo = $kanjiaList['0'];
    $kid = $kanjiaInfo['id'];
}

$cookieUserid = getcookie('tom_kanjia_kid'.$kid.'_userid');
if(!$cookieUserid){
    if($_SESSION['tom_kanjia_kid'.$kid.'_userid']){
        $cookieUserid = $_SESSION['tom_kanjia_kid'.$kid.'_userid'];
    }
}else{
    if($kanjiaConfig['oauth2_check'] == 1 && !empty($openid)){
        $userInfoOpenidTmp = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_kid_openid($kid,$openid);
        if($userInfoOpenidTmp){
            $lifeTime = 86400*30;
            $_SESSION['tom_kanjia_kid'.$kid.'_userid'] = $userInfoOpenidTmp['id'];
            dsetcookie('tom_kanjia_kid'.$kid.'_userid',$userInfoOpenidTmp['id'],$lifeTime);
            $cookieUserid = $userInfoOpenidTmp['id'];
        }
    }
}

$userInfo = array();
if($uid){
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($uid);
    if($userInfo){
    }else{
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&kid={$kid}");
        exit;
    }
}else{
    if($cookieUserid){
        $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($cookieUserid);
        if($userInfo){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$cookieUserid}");
            exit;
        }else{
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&kid={$kid}");
            exit;
        }
    }else{
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_kanjia&kid={$kid}");
        exit;
    }
}

$isSelf = 0;
if($cookieUserid == $uid){
    $isSelf = 1;
}

$isHelpKanjia = 0;
$cookieUseridKanjia = getcookie('tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia');
if($cookieUseridKanjia){
    $isHelpKanjia = 1;
}else{
    if($_SESSION['tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia']){
        $isHelpKanjia = 1;
    }
}

$logListCount = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_count(" AND kj_id={$kid} AND user_id={$uid} ");

$infpStr = $bk_infoStr = '';
if($isSelf == 1){
    $infpStr = discuzcode($kanjiaInfo['info'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
    $infpStr = str_replace("{USERNUM}", $logListCount, $infpStr);
    $infpStr = str_replace("{NOWPRICE}", $userInfo['price'], $infpStr);
}else{
    $bk_infoStr = discuzcode($kanjiaInfo['bk_info'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
    $bk_infoStr = str_replace("{USERNUM}", $logListCount, $bk_infoStr);
    $bk_infoStr = str_replace("{NAME}", $userInfo['name'], $bk_infoStr);
    $bk_infoStr = str_replace("{NOWPRICE}", $userInfo['price'], $bk_infoStr);
}
$moren_percent = (($userInfo['price']-$kanjiaInfo['base_price'])/($kanjiaInfo['goods_price']-$kanjiaInfo['base_price']))*100;
$moren_percent = 100 - ceil($moren_percent);

if(!preg_match('/^http:/', $kanjiaInfo['pic_url']) ){
    $kanjia_pic_url = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$kanjiaInfo['pic_url'];
}else{
    $kanjia_pic_url = $kanjiaInfo['pic_url'];
}

if(!preg_match('/^http:/', $kanjiaInfo['share_logo']) ){
    $share_logo_url = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$kanjiaInfo['share_logo'];
}else{
    $share_logo_url = $kanjiaInfo['share_logo'];
}

if(!preg_match('/^http:/', $kanjiaInfo['ads_picurl']) ){
    $ads_picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$kanjiaInfo['ads_picurl'];
}else{
    $ads_picurl = $kanjiaInfo['ads_picurl'];
}

$start_time = dgmdate($kanjiaInfo['start_time'],"Y-m-d",$tomSysOffset);
$end_time = dgmdate($kanjiaInfo['end_time'],"Y-m-d",$tomSysOffset);
$goodinfo = stripslashes($kanjiaInfo['goodinfo']);
$content = stripslashes($kanjiaInfo['content']);

DB::query("UPDATE ".DB::table('tom_kanjia')." SET clicks=clicks+1 WHERE id='{$kid}'", 'UNBUFFERED');

$daojishiTimes = $kanjiaInfo['end_time']-TIMESTAMP;

$showBtnBox = 1;
if(TIMESTAMP < $kanjiaInfo['start_time']){
    $showBtnBox = 2;
}

if(TIMESTAMP > $kanjiaInfo['end_time']){
    $showBtnBox = 3;
}

$userBasePricecount = 0;
if(!empty($kanjiaInfo['goods_num'])){
    $userBasePricecount1 = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} AND price<={$kanjiaInfo['base_price']} AND status=0 ");
    $userBasePricecount2 = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} AND price>{$kanjiaInfo['base_price']} AND dh_status=1 ");
    $userBasePricecount = $userBasePricecount1 + $userBasePricecount2;
    if($userBasePricecount >= $kanjiaInfo['goods_num']){
        $showBtnBox = 4;
    }
}
$goodinfo = str_replace("{GOODSNUM}", $kanjiaInfo['goods_num'], $goodinfo);
$goodinfo = str_replace("{SALENUM}", $userBasePricecount, $goodinfo);

$pagesize = 10;
$start = ($page-1)*$pagesize;	
$where = " AND kj_id={$kid} AND user_id={$uid} ";
$order = " ORDER BY add_time DESC ";
$logListTmp = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_list($where,$order,$start,$pagesize);
$logList = array();
if(is_array($logListTmp) && !empty($logListTmp)){
    foreach ($logListTmp as $key => $value){
        $logList[$key] = $value;
    }
}
$showNextPage = 1;
if(($start + $pagesize) >= $logListCount){
    $showNextPage = 0;
}
$allPageNum = ceil($logListCount/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$uid}&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$uid}&page={$nextPage}";

$phb_num = 10;
if($kanjiaConfig['phb_num'] > 0){
    $phb_num = $kanjiaConfig['phb_num'];
}
$userListTmp = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_list(" AND kj_id={$kid} AND status=0 ","ORDER BY price ASC, kanjia_time ASC, id DESC",0,$phb_num);
$userList = array();
$i = 1;
foreach ($userListTmp as $key => $value) {
    $userList[$key]['user_no'] = $i;
    $userList[$key]['user_name'] = cutstr($value['name'],2,"***");
    $userList[$key]['user_price'] = $value['price'];
    $userList[$key]['even'] = 0;
    if($i%2 == 0){
        $userList[$key]['even'] = 1;
        $userList[$key]['class'] = "two";
    }
    $i++;
}

$showGuanzuBox = 0;
if(isset($_GET['from']) && !empty($_GET['from'])){
    $showGuanzuBox = 1;
}

$showDuihuanBtn = 0;
if($kanjiaConfig['open_duihuan'] == 1 && $isSelf){
    if($kanjiaConfig['open_lower_price'] == 1){
        if($userInfo['price'] <= $kanjiaInfo['base_price']){
            $showDuihuanBtn = 1;
        }
    }else{
        $showDuihuanBtn = 1;
    }
}

if($userInfo['dh_status'] == 1){
	$showBtnBox = 5;
}

$shareTitle = $kanjiaInfo['share_title'];
$shareTitle = str_replace("{NAME}", $userInfo['name'], $shareTitle);
$shareDesc = $kanjiaInfo['share_desc'];
$shareDesc = str_replace("{NAME}", $userInfo['name'], $shareDesc);
$shareLogo = $share_logo_url;
$shareUrl = $_G['siteurl']."plugin.php?id=tom_kanjia&mod=index&kid={$kid}&uid={$uid}";


$ajaxZikanUrl = "plugin.php?id=tom_kanjia&mod=ajax&act=zikan&kid={$kid}&uid={$uid}&formhash={$formhash}";
$ajaxKanjiaUrl = "plugin.php?id=tom_kanjia&mod=ajax&act=kanjia&kid={$kid}&uid={$uid}";
$ajaxDuihuanUrl = "plugin.php?id=tom_kanjia&mod=ajax&act=duihuan&kid={$kid}&uid={$uid}";

$num_a = mt_rand(1, 9);
$num_b = mt_rand(1, 9);
$num_count = $num_a + $num_b;

$check_r = mt_rand(1, 2);

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && $kanjiaConfig['open_in_wx'] == 1) {
    include template("tom_kanjia:weixin"); 
}else{
    if($kanjiaInfo['template_id'] == 'baise'){
        include template("tom_kanjia:baise/index");  
    }else if($kanjiaInfo['template_id'] == 'huangse'){
        include template("tom_kanjia:huangse/index");  
    }else if($kanjiaInfo['template_id'] == 'lanlvse'){
        include template("tom_kanjia:lanlvse/index");  
    }else if($kanjiaInfo['template_id'] == 'lanse'){
        include template("tom_kanjia:lanse/index");  
    }else{
        include template("tom_kanjia:index");  
    }
}

?>
