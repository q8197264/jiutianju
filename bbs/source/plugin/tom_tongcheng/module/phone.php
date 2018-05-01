<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'';

if($act == 'save' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 200,
    );
    
    $tel = isset($_GET['tel'])? daddslashes($_GET['tel']):'';
    $tel_code = isset($_GET['tel_code'])? daddslashes($_GET['tel_code']):'';
    
    $get_tel_code = '';
    if(isset($_SESSION['tom_tongcheng_moblie_sms']) && !empty($_SESSION['tom_tongcheng_moblie_sms'])){
        $get_tel_code = $_SESSION['tom_tongcheng_moblie_sms'];
    }
    $get_tel = '';
    if(isset($_SESSION['tom_tongcheng_moblie_tel']) && !empty($_SESSION['tom_tongcheng_moblie_tel'])){
        $get_tel = $_SESSION['tom_tongcheng_moblie_tel'];
    }
    
    if($tel_code != $get_tel_code){
        $outArr['status'] = 201;
        echo json_encode($outArr); exit;
    }
    if($tel != $get_tel){
        $outArr['status'] = 202;
        echo json_encode($outArr); exit;
    }
        
    $updateData = array();
    $updateData['tel'] = $tel;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
    
    echo json_encode($outArr); exit;
}else if($act == 'huoqu' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 1,
    );
    $tel = isset($_GET['tel'])? daddslashes($_GET['tel']):'';
    
    if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_sms/sms.func.php')){
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    include DISCUZ_ROOT.'./source/plugin/tom_sms/sms.func.php';
    
    $code = substr(str_shuffle("012345678901234567890123456789"), 0, 6);
    
    $r = plugin_send_sms('tom_tongcheng_01', $tel, array('number'=>$code));
    
    $_SESSION['tom_tongcheng_moblie_sms'] = $code;
    $_SESSION['tom_tongcheng_moblie_tel'] = $tel;
    
    if($r['status'] == 'success'){
        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
        
    echo json_encode($outArr); exit;
}

$phoneUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=phone&formhash=".FORMHASH;
$saveUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=phone&act=save&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:phone");  




