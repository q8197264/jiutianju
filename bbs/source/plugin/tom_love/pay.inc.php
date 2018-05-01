<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

/**
   1 待支付 2 已支付
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$jyConfig = $_G['cache']['plugin']['tom_love'];

$wxpay_appid        = trim($jyConfig['love_appid']);
$wxpay_mchid        = trim($jyConfig['wxpay_mchid']);
$wxpay_key          = trim($jyConfig['wxpay_key']);
$wxpay_appsecret    = trim($jyConfig['love_appsecret']);

define("TOM_WXPAY_APPID", $wxpay_appid);
define("TOM_WXPAY_MCHID", $wxpay_mchid);
define("TOM_WXPAY_KEY", $wxpay_key);
define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_love/wxpay/lib/WxPay.Api.php';

$act = isset($_GET['act'])? addslashes($_GET['act']):"score";

if($act == "score" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );

    $user_id    = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $openid    = isset($_GET['openid'])? daddslashes($_GET['openid']):"";
    $pay_price  = intval($_GET['pay_price'])>0? intval($_GET['pay_price']):1;
    
    $userinfo = C::t('#tom_love#tom_love')->fetch_by_id($user_id);
    if(!$userinfo){
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    $yuan_score_listStr = str_replace("\r\n","{n}",$jyConfig['yuan_score_list']); 
    $yuan_score_listStr = str_replace("\n","{n}",$yuan_score_listStr);
    $yuan_score_listTmpArr = explode("{n}", $yuan_score_listStr);

    $yuan_scoreArr = array();
    if(is_array($yuan_score_listTmpArr) && !empty($yuan_score_listTmpArr)){
        foreach ($yuan_score_listTmpArr as $key => $value){
            if(!empty($value)){
                list($yuan, $score) = explode("|", $value);
                $yuan = intval($yuan);
                $score = intval($score);
                if(!empty($yuan) && !empty($score)){
                    $yuan_scoreArr[$yuan] = $score;
                }
            }
        }
    }
    
    if(!isset($yuan_scoreArr[$pay_price])){
        $outArr = array(
            'status'=> 302,
        );
        echo json_encode($outArr); exit;
    }

    $order_no = "JY".date("YmdHis")."-".mt_rand(111111, 666666);
    $order_name = lang('plugin/tom_love','wxpay_score_order');
    $order_name = diconv($order_name,CHARSET,'utf-8');
    $notifyUrl = $_G['siteurl']."source/plugin/tom_love/notify.php";
    $order_price = $pay_price*100;

    $orderInput = new WxPayUnifiedOrder();
    $orderInput->SetBody($order_name);		
    $orderInput->SetAttach("tom_love");		
    $orderInput->SetOut_trade_no($order_no);	
    $orderInput->SetTotal_fee($order_price);	
    $orderInput->SetGoods_tag("null");	
    $orderInput->SetNotify_url($notifyUrl);	
    $orderInput->SetTrade_type("JSAPI");
    $orderInput->SetOpenid($openid);
    $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

    if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){

        $insertData = array();
        $insertData['order_no']         = $order_no;
        $insertData['openid']           = $openid;
        $insertData['user_id']          = $user_id;
        $insertData['score_value']      = $yuan_scoreArr[$pay_price];
        $insertData['pay_price']        = $pay_price;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_love#tom_love_order')->insert($insertData)){
            $order_id = C::t('#tom_love#tom_love_order')->insert_id();

            $jsapi = new WxPayJsApiPay();
            $jsapi->SetAppid($orderInfo["appid"]);
            $timeStamp = time();
            $timeStamp = "$timeStamp";
            $jsapi->SetTimeStamp($timeStamp);
            $jsapi->SetNonceStr(WxPayApi::getNonceStr());
            $jsapi->SetPackage("prepay_id=" . $orderInfo['prepay_id']);
            $jsapi->SetSignType("MD5");
            $jsapi->SetPaySign($jsapi->MakeSign());
            $parameters = $jsapi->GetValues();

            $outArr = array(
                'status'=> 200,
                'parameters' => $parameters,
            );
            echo json_encode($outArr); exit;
        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 303,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "vip" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );

    $user_id    = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $openid     = isset($_GET['openid'])? daddslashes($_GET['openid']):"";
    $month_id   = intval($_GET['month_id'])>0? intval($_GET['month_id']):1;
    $vip_id     = intval($_GET['vip_id'])>0? intval($_GET['vip_id']):1;
    
    $userinfo = C::t('#tom_love#tom_love')->fetch_by_id($user_id);
    if(!$userinfo){
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    $yuan_vip1_listStr = str_replace("\r\n","{n}",$jyConfig['yuan_vip1_list']); 
    $yuan_vip1_listStr = str_replace("\n","{n}",$yuan_vip1_listStr);
    $yuan_vip1_listTmpArr = explode("{n}", $yuan_vip1_listStr);
    
    $yuan_vip1Arr = array();
    if(is_array($yuan_vip1_listTmpArr) && !empty($yuan_vip1_listTmpArr)){
        foreach ($yuan_vip1_listTmpArr as $key => $value){
            if(!empty($value)){
                list($month, $price) = explode("|", $value);
                $month = intval($month);
                $price = intval($price);
                if(!empty($month) && !empty($price)){
                    $yuan_vip1Arr[$month] = $price;
                }
            }
        }
    }

    if(!isset($yuan_vip1Arr[$month_id])){
        $outArr = array(
            'status'=> 302,
        );
        echo json_encode($outArr); exit;
    }
    
    $order_no = "JY".date("YmdHis")."-".mt_rand(111111, 666666);
    $order_name = lang('plugin/tom_love','wxpay_vip_order');
    $order_name = diconv($order_name,CHARSET,'utf-8');
    $notifyUrl = $_G['siteurl']."source/plugin/tom_love/notify.php";
    $order_price = $yuan_vip1Arr[$month_id]*100;

    $orderInput = new WxPayUnifiedOrder();
    $orderInput->SetBody($order_name);		
    $orderInput->SetAttach("tom_love");		
    $orderInput->SetOut_trade_no($order_no);	
    $orderInput->SetTotal_fee($order_price);	
    $orderInput->SetGoods_tag("null");	
    $orderInput->SetNotify_url($notifyUrl);	
    $orderInput->SetTrade_type("JSAPI");
    $orderInput->SetOpenid($openid);
    $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

    if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){
        
        if($vip_id == 1){
            $order_type = 2;
        }else if($vip_id == 2){
            $order_type = 3;
        }

        $insertData = array();
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = $order_type;
        $insertData['openid']           = $openid;
        $insertData['user_id']          = $user_id;
        $insertData['score_value']      = 0;
        $insertData['time_value']       = $month_id;
        $insertData['pay_price']        = $yuan_vip1Arr[$month_id];
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_love#tom_love_order')->insert($insertData)){
            $order_id = C::t('#tom_love#tom_love_order')->insert_id();

            $jsapi = new WxPayJsApiPay();
            $jsapi->SetAppid($orderInfo["appid"]);
            $timeStamp = time();
            $timeStamp = "$timeStamp";
            $jsapi->SetTimeStamp($timeStamp);
            $jsapi->SetNonceStr(WxPayApi::getNonceStr());
            $jsapi->SetPackage("prepay_id=" . $orderInfo['prepay_id']);
            $jsapi->SetSignType("MD5");
            $jsapi->SetPaySign($jsapi->MakeSign());
            $parameters = $jsapi->GetValues();

            $outArr = array(
                'status'=> 200,
                'parameters' => $parameters,
            );
            echo json_encode($outArr); exit;
        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 303,
        );
        echo json_encode($outArr); exit;
    }
    
}else{
    $outArr = array(
        'status'=> 111111,
    );
    echo json_encode($outArr); exit;
}

