<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

$wxpay_appid        = trim($tongchengConfig['wxpay_appid']);
$wxpay_mchid        = trim($tongchengConfig['wxpay_mchid']);
$wxpay_key          = trim($tongchengConfig['wxpay_key']);
$wxpay_appsecret    = trim($tongchengConfig['wxpay_appsecret']);

define("TOM_WXPAY_APPID", $wxpay_appid);
define("TOM_WXPAY_MCHID", $wxpay_mchid);
define("TOM_WXPAY_KEY", $wxpay_key);
define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/wxpay/lib/WxPay.Api.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

$act = isset($_GET['act'])? addslashes($_GET['act']):"fabu";

if($act == "fabu" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    if('utf-8' != CHARSET) {
        if(defined('IN_MOBILE')){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $user_id    = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $model_id   = isset($_GET['model_id'])? intval($_GET['model_id']):0;
    $type_id    = isset($_GET['type_id'])? intval($_GET['type_id']):0;
    $cate_id    = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $city_id    = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $area_id    = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    $street_id  = isset($_GET['street_id'])? intval($_GET['street_id']):0;
    $xm         = isset($_GET['xm'])? addslashes($_GET['xm']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
    
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($typeInfo['model_id']);
    
    if(empty($userInfo) || empty($typeInfo)){
        $outArr = array(
            'status'=> 500,
        );
        echo json_encode($outArr); exit;
    }
    
    if($userInfo['status'] != 1){
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    $lastTongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND user_id={$user_id} "," ORDER BY id DESC ",0,1);
    if($lastTongchengListTmp && $lastTongchengListTmp[0]['add_time'] > 0 && $userInfo['editor']==0){
        $nextFabuTime = $lastTongchengListTmp[0]['add_time'] + $tongchengConfig['fabu_next_minute']*60;
        if($nextFabuTime > TIMESTAMP){
            $outArr = array(
                'status'=> 305,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    $__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
    if(!empty($__CommonInfo['forbid_word'])){
        $forbid_word = preg_quote(trim($__CommonInfo['forbid_word']), '/');
        $forbid_word = str_replace(array("\\*"), array('.*'), $forbid_word);
        $forbid_word = '.*('.$forbid_word.').*';
        $forbid_word = '/^('.str_replace(array("\r\n", ' '), array(').*|.*(', ''), $forbid_word).')$/i';
        if(@preg_match($forbid_word, $content,$matches)) {
            $i = count($matches)-1;
            $word = '';
            if(isset($matches[$i]) && !empty($matches[$i])){
                $word = diconv($matches[$i],CHARSET,'utf-8');
            }
            $outArr = array(
                'status'=> 505,
                'word'=> $word,
            );
            echo json_encode($outArr); exit;
        }
                
    }
    
    $attrnameArr = $attrArr = $attrdateArr = $tagnameArr = $photoArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "attrname_") !== false){
            $attr_id = intval(ltrim($key, 'attrname_'));
            $attrnameArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attr_") !== false){
            $attr_id = intval(ltrim($key, 'attr_'));
            $attrArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attrdate_") !== false){
            $attr_id = intval(ltrim($key, 'attrdate_'));
            $value = str_replace("T", " ", $value);
            $attrdateArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "tagname_") !== false){
            $tag_id = intval(ltrim($key, 'tagname_'));
            $tagnameArr[$tag_id] = addslashes($value);
        }
        if(strpos($key, "photo_") !== false){
            $photoArr[] = addslashes($value);
        }
    }
    
    $tagArr = array();
    if(isset($_GET['tag']) && is_array($_GET['tag'])){
        foreach ($_GET['tag'] as $key => $value){
            $tagArr[] = intval($value);
        }
    }
    
    $search_content = '';
    if(is_array($attrArr) && !empty($attrArr)){
        foreach ($attrArr as $key => $value){
            $search_content.=''.$attrnameArr[$key].$value.'';
        }
    }
    if(is_array($tagArr) && !empty($tagArr)){
        foreach ($tagArr as $key => $value){
            $search_content.=''.$tagnameArr[$value].'';
        }
    }
    $insertData = array();
    $insertData['site_id']      = $site_id;
    $insertData['user_id']      = $user_id;
    $insertData['model_id']     = $model_id;
    $insertData['type_id']      = $type_id;
    $insertData['cate_id']      = $cate_id;
    $insertData['city_id']      = $city_id;
    $insertData['area_id']      = $area_id;
    $insertData['street_id']    = $street_id;
    $insertData['xm']           = $xm;
    $insertData['tel']          = $tel;
    $insertData['content']      = $content.'|+|+|+|+|+|+|+|+|+|'.$search_content.'-'.$xm.'-'.$tel;
    $insertData['refresh_time'] = TIMESTAMP;
    $insertData['add_time']     = TIMESTAMP;
    if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0 && $userInfo['editor']==0){
        $insertData['status']       = 2;
        $insertData['pay_status']   = 1;
    }else{
        $insertData['status']       = 1;
        $insertData['pay_status']   = 0;
    }
    if($modelInfo['must_shenhe'] == 1 && $userInfo['editor']==0){
        $insertData['shenhe_status']       = 2;
    }else{
        $insertData['shenhe_status']       = 1;
    }
    if(C::t('#tom_tongcheng#tom_tongcheng')->insert($insertData)){
        
        $tongchengId = C::t('#tom_tongcheng#tom_tongcheng')->insert_id();
        
        if(is_array($attrArr) && !empty($attrArr)){
            foreach ($attrArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($attrdateArr) && !empty($attrdateArr)){
            foreach ($attrdateArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['time_value']   = strtotime($value);
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($tagArr) && !empty($tagArr)){
            foreach ($tagArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['tag_id']       = $value;
                $insertData['tag_name']     = $tagnameArr[$value];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_tag')->insert($insertData);
            }
        }
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['picurl'] = $value;
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_photo')->insert($insertData);
            }
        }
        
        ## pay start
        if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0 && $userInfo['editor']==0){
            
            $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
            $order_name = lang('plugin/tom_tongcheng','order_type_1');
            $order_name = diconv($order_name,CHARSET,'utf-8');
            $notifyUrl = $_G['siteurl']."source/plugin/tom_tongcheng/notify.php";
            $order_price = $typeInfo['fabu_price']*100;

            $orderInput = new WxPayUnifiedOrder();
            $orderInput->SetBody($order_name);		
            $orderInput->SetAttach("tom_tongcheng");		
            $orderInput->SetOut_trade_no($order_no);	
            $orderInput->SetTotal_fee($order_price);	
            $orderInput->SetGoods_tag("null");	
            $orderInput->SetNotify_url($notifyUrl);	
            $orderInput->SetTrade_type("JSAPI");
            $orderInput->SetOpenid($userInfo['openid']);
            $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

            if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){

                $insertData = array();
                $insertData['site_id']          = $site_id;
                $insertData['order_no']         = $order_no;
                $insertData['order_type']       = 1;
                $insertData['user_id']          = $user_id;
                $insertData['openid']           = $userInfo['openid'];
                $insertData['tongcheng_id']     = $tongchengId;
                $insertData['pay_price']        = $typeInfo['fabu_price'];
                $insertData['order_status']     = 1;
                $insertData['order_time']       = TIMESTAMP;
                if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
                    $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

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
                        'tongcheng_id'=> $tongchengId,
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
        }
        ## pay end
        $outArr = array(
            'status'=> 200,
            'tongcheng_id'=> $tongchengId,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
    
}else if($act == "pay" && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=1 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0){
            
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        $order_name = lang('plugin/tom_tongcheng','order_type_1');
        $order_name = diconv($order_name,CHARSET,'utf-8');
        $notifyUrl = $_G['siteurl']."source/plugin/tom_tongcheng/notify.php";
        $order_price = $typeInfo['fabu_price']*100;

        $orderInput = new WxPayUnifiedOrder();
        $orderInput->SetBody($order_name);		
        $orderInput->SetAttach("tom_tongcheng");		
        $orderInput->SetOut_trade_no($order_no);	
        $orderInput->SetTotal_fee($order_price);	
        $orderInput->SetGoods_tag("null");	
        $orderInput->SetNotify_url($notifyUrl);	
        $orderInput->SetTrade_type("JSAPI");
        $orderInput->SetOpenid($userInfo['openid']);
        $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

        if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){

            $insertData = array();
            $insertData['site_id']          = $site_id;
            $insertData['order_no']         = $order_no;
            $insertData['order_type']       = 1;
            $insertData['user_id']          = $userInfo['id'];
            $insertData['openid']           = $userInfo['openid'];
            $insertData['tongcheng_id']     = $tongcheng_id;
            $insertData['pay_price']        = $typeInfo['fabu_price'];
            $insertData['order_status']     = 1;
            $insertData['order_time']       = TIMESTAMP;
            if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
                $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

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
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "refresh" && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=2 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($typeInfo['refresh_price'] > 0){
            
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        $order_name = lang('plugin/tom_tongcheng','order_type_2');
        $order_name = diconv($order_name,CHARSET,'utf-8');
        $notifyUrl = $_G['siteurl']."source/plugin/tom_tongcheng/notify.php";
        $order_price = $typeInfo['refresh_price']*100;

        $orderInput = new WxPayUnifiedOrder();
        $orderInput->SetBody($order_name);		
        $orderInput->SetAttach("tom_tongcheng");		
        $orderInput->SetOut_trade_no($order_no);	
        $orderInput->SetTotal_fee($order_price);	
        $orderInput->SetGoods_tag("null");	
        $orderInput->SetNotify_url($notifyUrl);	
        $orderInput->SetTrade_type("JSAPI");
        $orderInput->SetOpenid($userInfo['openid']);
        $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

        if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){

            $insertData = array();
            $insertData['site_id']          = $site_id;
            $insertData['order_no']         = $order_no;
            $insertData['order_type']       = 2;
            $insertData['user_id']          = $userInfo['id'];
            $insertData['openid']           = $userInfo['openid'];
            $insertData['tongcheng_id']     = $tongcheng_id;
            $insertData['pay_price']        = $typeInfo['refresh_price'];
            $insertData['order_status']     = 1;
            $insertData['order_time']       = TIMESTAMP;
            if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
                $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

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
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "top" && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    $days   = intval($_GET['days'])>1? intval($_GET['days']):1;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=3 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($typeInfo['top_price'] > 0){
            
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        $order_name = lang('plugin/tom_tongcheng','order_type_2');
        $order_name = diconv($order_name,CHARSET,'utf-8');
        $notifyUrl = $_G['siteurl']."source/plugin/tom_tongcheng/notify.php";
        $order_price = $typeInfo['top_price']*100*$days;

        $orderInput = new WxPayUnifiedOrder();
        $orderInput->SetBody($order_name);		
        $orderInput->SetAttach("tom_tongcheng");		
        $orderInput->SetOut_trade_no($order_no);	
        $orderInput->SetTotal_fee($order_price);	
        $orderInput->SetGoods_tag("null");	
        $orderInput->SetNotify_url($notifyUrl);	
        $orderInput->SetTrade_type("JSAPI");
        $orderInput->SetOpenid($userInfo['openid']);
        $orderInfo = WxPayApi::unifiedOrder($orderInput,300);

        if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){

            $insertData = array();
            $insertData['site_id']          = $site_id;
            $insertData['order_no']         = $order_no;
            $insertData['order_type']       = 3;
            $insertData['user_id']          = $userInfo['id'];
            $insertData['openid']           = $userInfo['openid'];
            $insertData['tongcheng_id']     = $tongcheng_id;
            $insertData['pay_price']        = $typeInfo['top_price']*$days;
            $insertData['time_value']       = $days;
            $insertData['order_status']     = 1;
            $insertData['order_time']       = TIMESTAMP;
            if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
                $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

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
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else{
    $outArr = array(
        'status'=> 111111,
    );
    echo json_encode($outArr); exit;
}


    

