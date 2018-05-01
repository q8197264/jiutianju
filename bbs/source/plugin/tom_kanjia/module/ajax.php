<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$kid      = isset($_GET['kid'])? intval($_GET['kid']):0;
$formhash     = isset($_GET['formhash'])? addslashes($_GET['formhash']):"";

$kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($kid);

if($_GET['act'] == 'add' && $formhash == FORMHASH && $kanjiaInfo){
    
    $name          = isset($_GET['bmname'])? daddslashes(diconv(urldecode($_GET['bmname']),'utf-8')):'';
    $tel        = isset($_GET['bmtel'])? addslashes($_GET['bmtel']):'';
    $openid          = isset($_GET['openid'])? daddslashes(diconv(urldecode($_GET['openid']),'utf-8')):'';
    
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_kid_tel($kid,$tel);
    if($userInfo){
        
        if(!empty($openid) && empty($userInfo['openid'])){
            $updateData = array();
            $updateData['openid'] = $openid;
            C::t('#tom_kanjia#tom_kanjia_user')->update($userInfo['id'],$updateData);
        }
        
        $lifeTime = 86400*30;
        $_SESSION['tom_kanjia_kid'.$kid.'_userid'] = $userInfo['id'];
        dsetcookie('tom_kanjia_kid'.$kid.'_userid',$userInfo['id'],$lifeTime);
        echo '201';exit;
    }
    
    $insertData = array();
    $insertData['kj_id']        = $kanjiaInfo['id'];
    $insertData['price']        = $kanjiaInfo['goods_price'];
    $insertData['name']         = $name;
    $insertData['tel']          = $tel;
    $insertData['openid']       = $openid;
    $insertData['add_time']     = TIMESTAMP;
    if(C::t('#tom_kanjia#tom_kanjia_user')->insert($insertData)){
        $userid = C::t('#tom_kanjia#tom_kanjia_user')->insert_id();
        $lifeTime = 86400*30;
        $_SESSION['tom_kanjia_kid'.$kid.'_userid'] = $userid;
        dsetcookie('tom_kanjia_kid'.$kid.'_userid',$userid,$lifeTime);
        
        $userCount = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} ");
        $updateData = array();
        $updateData['user_count'] = $userCount;
        C::t('#tom_kanjia#tom_kanjia')->update($kid,$updateData);
        
        echo '200';exit;
        
    }
    echo '404';exit;
}else if($_GET['act'] == 'zikan' && $formhash == FORMHASH && $kanjiaInfo){
    $outArr = array(
        'status'=> 1,
        'price'=> 0,
    );
    
    $uid      = isset($_GET['uid'])? intval($_GET['uid']):0;
    
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($uid);
    
    if(TIMESTAMP < $kanjiaInfo['start_time']){
        echo json_encode($outArr); exit;
    }

    if(TIMESTAMP > $kanjiaInfo['end_time']){
        echo json_encode($outArr); exit;
    }
    
    if($userInfo){
        
        if($userInfo['is_zikan'] == 1){
            $outArr = array(
                'status'=> 100,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }
        
        if($userInfo['price'] <= $kanjiaInfo['base_price']){
            $updateData = array();
            $updateData['is_zikan'] = 1;
            C::t('#tom_kanjia#tom_kanjia_user')->update($uid,$updateData);

            $insertData = array();
            $insertData['kj_id']        = $kid;
            $insertData['user_id']      = $uid;
            $insertData['name']         = $userInfo['name'];
            $insertData['price']        = 0;
            $insertData['price_after']  = $userInfo['price'];
            $insertData['ip']           = bindec(decbin(ip2long($_G['clientip'])));
            $insertData['add_time']     = TIMESTAMP;
            C::t('#tom_kanjia#tom_kanjia_log')->insert($insertData);
        
            $outArr = array(
                'status'=> 200,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }
        
        $priceListTmp = C::t('#tom_kanjia#tom_kanjia_price')->fetch_all_list(" AND kj_id={$kid} AND start_price<={$userInfo['price']} AND end_price>{$userInfo['price']} ","ORDER BY add_time DESC",0,1);
        $randPrice = 0.01;
        if($priceListTmp && $priceListTmp[0]){
            $min_price = $priceListTmp[0]['min_price']*100;
            $max_price = $priceListTmp[0]['max_price']*100;
            $randPriceTmp = mt_rand($min_price,$max_price);
            $randPrice = $randPriceTmp/100;
        }
        
        $kanjiaPrice = 0;
        $kanjiaJia = false;
        if($kanjiaInfo['kj_type'] == 1){
            $kanjiaPrice = $randPrice;
        }else if($kanjiaInfo['kj_type'] == 2){
            $randNum = mt_rand(1, 10);
            if($randNum > 5){
                $kanjiaPrice = $randPrice;
            }else{
                $kanjiaPrice = 0;
            }
        }else if($kanjiaInfo['kj_type'] == 3){
            $kanjiaPrice = $randPrice;
            $randNum = mt_rand(1, 10);
            if($randNum > 5){
                $kanjiaJia = true;
            }
        }
        
        $newPrice = 0;
        if($kanjiaJia){
            $newPrice = $userInfo['price'] + $kanjiaPrice;
        }else{
            if($userInfo['price'] > $kanjiaPrice){
                $newPrice = $userInfo['price'] - $kanjiaPrice;
            }else{
                $newPrice = 0;
            }
        }
        
        if($newPrice <= $kanjiaInfo['base_price']){
            $newPrice = $kanjiaInfo['base_price'];
            $kanjiaPrice = $userInfo['price'] - $kanjiaInfo['base_price'];
        }
        
        $updateData = array();
        $updateData['price'] = $newPrice;
        $updateData['is_zikan'] = 1;
        $updateData['kanjia_time'] = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_user')->update($uid,$updateData);
        
        $insertData = array();
        $insertData['kj_id']        = $kid;
        $insertData['user_id']      = $uid;
        $insertData['name']         = $userInfo['name'];
        if($kanjiaJia){
            $insertData['price']        = "+".$kanjiaPrice;
        }else{
            $insertData['price']        = $kanjiaPrice;
        }
        $insertData['price_after']  = $newPrice;
        $insertData['ip']           = bindec(decbin(ip2long($_G['clientip'])));
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_log')->insert($insertData);
        
        $outArr = array(
            'status'=> 200,
            'price'=> $insertData['price'],
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
            'price'=> 0,
        );
        echo json_encode($outArr); exit;
    }
    
    
}else if($_GET['act'] == 'kanjia' && $formhash == FORMHASH && $kanjiaInfo){
    
    $outArr = array(
        'status'=> 1,
        'price'=> 0,
    );
    
    $uid      = isset($_GET['uid'])? intval($_GET['uid']):0;
    $name     = isset($_GET['name'])? daddslashes(diconv(urldecode($_GET['name']),'utf-8')):'';
    $openid     = isset($_GET['openid'])? daddslashes(diconv(urldecode($_GET['openid']),'utf-8')):'';
    
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($uid);
    
    if(TIMESTAMP < $kanjiaInfo['start_time']){
        echo json_encode($outArr); exit;
    }

    if(TIMESTAMP > $kanjiaInfo['end_time']){
        echo json_encode($outArr); exit;
    }
    if(!empty($kanjiaInfo['goods_num'])){
        $userBasePricecount1 = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} AND price<={$kanjiaInfo['base_price']} AND status=0 ");
        $userBasePricecount2 = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$kid} AND price>{$kanjiaInfo['base_price']} AND dh_status=1 ");
        $userBasePricecount = $userBasePricecount1 + $userBasePricecount2;
        if($userBasePricecount >= $kanjiaInfo['goods_num']){
            echo json_encode($outArr); exit;
        }
    }
    
    # IP
    $kanjiaConfig['xz_area_id'] = trim($kanjiaConfig['xz_area_id']);
    $xzArea = 1;
    if($kanjiaConfig['open_taobao_ip'] == 1){
        $ipdata = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$_G['clientip']);
        $ipInfo = json_decode($ipdata, true);
        if(is_array($ipInfo) && $ipInfo['code'] == 0){
            if($kanjiaConfig['xz_area_type'] == 1){
                if($ipInfo['data']['region_id'] != $kanjiaConfig['xz_area_id']){
                    $xzArea = 0;
                }
            }else if($kanjiaConfig['xz_area_type'] == 2){
                if($ipInfo['data']['city_id'] != $kanjiaConfig['xz_area_id']){
                    $xzArea = 0;
                }
            }
        }else{
            $xzArea = 2;
        }
    }
    if($xzArea == 0){
        $outArr = array(
            'status'=> 301,
            'price'=> 0,
        );
        echo json_encode($outArr); exit;
    }
    if($xzArea == 2){
        $outArr = array(
            'status'=> 303,
            'price'=> 0,
        );
        echo json_encode($outArr); exit;
    }
    # IP
    
    # NUM
    if($kanjiaConfig['oauth2_check'] == 1){
        $userBangKanjiacount = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_count(" AND kj_id={$kid} AND openid='{$openid}' ");
        if($userBangKanjiacount >= $kanjiaConfig['bangkanjia_num']){
            $outArr = array(
                'status'=> 302,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }
    }else{
        $ip2longTmp = bindec(decbin(ip2long($_G['clientip'])));
        $userBangKanjiacount = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_count(" AND kj_id={$kid} AND ip={$ip2longTmp} ");
        if($userBangKanjiacount >= $kanjiaConfig['bangkanjia_num']){
            $outArr = array(
                'status'=> 302,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }
    }
    # NUM
    
    if($userInfo){
        if($userInfo['price'] <= $kanjiaInfo['base_price']){
            $outArr = array(
                'status'=> 100,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }
        
        $cookieUseridKanjia = getcookie('tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia');
        if($cookieUseridKanjia){
            $outArr = array(
                'status'=> 201,
                'price'=> 0,
            );
            echo json_encode($outArr); exit;
        }else{
            if($_SESSION['tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia']){
                $outArr = array(
                    'status'=> 201,
                    'price'=> 0,
                );
                echo json_encode($outArr); exit;
            }
        }
        
        if($kanjiaConfig['oauth2_check'] == 1){
            $logInfo = C::t('#tom_kanjia#tom_kanjia_log')->fetch_by_openid($kid,$uid,$openid);
            if($logInfo){
                $outArr = array(
                    'status'=> 201,
                    'price'=> 0,
                );
                echo json_encode($outArr); exit;
            }
        }
        
        $priceListTmp = C::t('#tom_kanjia#tom_kanjia_price')->fetch_all_list(" AND kj_id={$kid} AND start_price<={$userInfo['price']} AND end_price>{$userInfo['price']} ","ORDER BY add_time DESC",0,1);
        $randPrice = 0.01;
        if($priceListTmp && $priceListTmp[0]){
            $min_price = $priceListTmp[0]['min_price']*100;
            $max_price = $priceListTmp[0]['max_price']*100;
            $randPriceTmp = mt_rand($min_price,$max_price);
            $randPrice = $randPriceTmp/100;
        }
        
        if($userInfo['status'] == 1){
            $randPrice = 0;
            $kanjiaInfo['kj_type'] = 1;
        }

        $kanjiaPrice = 0;
        $kanjiaJia = false;
        if($kanjiaInfo['kj_type'] == 1){
            $kanjiaPrice = $randPrice;
        }else if($kanjiaInfo['kj_type'] == 2){
            $randNum = mt_rand(1, 10);
            if($randNum > 5){
                $kanjiaPrice = $randPrice;
            }else{
                $kanjiaPrice = 0;
            }
        }else if($kanjiaInfo['kj_type'] == 3){
            $kanjiaPrice = $randPrice;
            $randNum = mt_rand(1, 10);
            if($randNum > 5){
                $kanjiaJia = true;
            }
        }

        $newPrice = 0;
        if($kanjiaJia){
            $newPrice = $userInfo['price'] + $kanjiaPrice;
        }else{
            if($userInfo['price'] > $kanjiaPrice){
                $newPrice = $userInfo['price'] - $kanjiaPrice;
            }else{
                $newPrice = 0;
            }
        }
        
        if($newPrice <= $kanjiaInfo['base_price']){
            $newPrice = $kanjiaInfo['base_price'];
            $kanjiaPrice = $userInfo['price'] - $kanjiaInfo['base_price'];
        }

        $updateData = array();
        $updateData['price'] = $newPrice;
        $updateData['kanjia_time'] = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_user')->update($uid,$updateData);

        $insertData = array();
        $insertData['kj_id']        = $kid;
        $insertData['user_id']      = $uid;
        $insertData['name']         = $name;
        $insertData['openid']       = $openid;
        if($kanjiaJia){
            $insertData['price']        = "+".$kanjiaPrice;
        }else{
            $insertData['price']        = $kanjiaPrice;
        }
        $insertData['price_after']  = $newPrice;
        $insertData['ip']           = bindec(decbin(ip2long($_G['clientip'])));
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_log')->insert($insertData);

        $lifeTime = 86400*30;
        $_SESSION['tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia'] = 1;
        dsetcookie('tom_kanjia_kid'.$kid.'_userid_'.$uid.'_kanjia',1,$lifeTime);

        $outArr = array(
            'status'=> 200,
            'price'=> $insertData['price'],
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
            'price'=> 0,
        );
        echo json_encode($outArr); exit;
    }
    
    
}else if($_GET['act'] == 'duihuan' && $formhash == FORMHASH && $kanjiaInfo){
    $outArr = array(
        'status'=> 1,
    );
    
    $uid      = isset($_GET['uid'])? intval($_GET['uid']):0;
    $dh_pwd  = isset($_GET['dh_pwd'])? addslashes($_GET['dh_pwd']):'';
    
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($uid);
    
    if($userInfo){
        if($dh_pwd == $kanjiaInfo['dh_pwd']){
            
            $updateData = array();
            $updateData['dh_status'] = 1;
            C::t('#tom_kanjia#tom_kanjia_user')->update($userInfo['id'],$updateData);
            
            $outArr = array(
                'status'=> 200,
            );
            echo json_encode($outArr); exit;
            
        }else{
            $outArr = array(
                'status'=> 100,
            );
            echo json_encode($outArr); exit;
        }
    }

    $outArr = array(
        'status'=> 404,
    );
    echo json_encode($outArr); exit;
}else{
    exit('error');
}

?>
