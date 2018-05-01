<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$url = $weixinClass->get_url();

$redirect_uri = urlencode($url);

$subscribeFlag = false; 

$oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";

if(isset($_GET['code']) && !empty($_GET['code'])){
    $code = $_GET['code'];
    $openid = get_oauth2_openid($code,$appid,$appsecret);
    if(!empty($openid)){
    }else{
        dheader('location:'.$oauth2_url);
        exit;
    }
    
}else{
    dheader('location:'.$oauth2_url);
    exit;
}

function get_oauth2_openid($code,$appid,$appsecret){
    $openid = '';
    $get_openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
    $return = get_html($get_openid_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['openid']) && !empty($content['openid'])){
            $openid = $content['openid'];
        }
    }
    return $openid;
}



?>
