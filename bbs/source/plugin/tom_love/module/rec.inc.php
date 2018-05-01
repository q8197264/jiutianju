<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

$rec_score_listStr = str_replace("\r\n","{n}",$jyConfig['rec_score_list']);
$rec_score_listStr = str_replace("\n","{n}",$rec_score_listStr);
$rec_score_listTmpArr = explode("{n}", $rec_score_listStr);

$rec_scoreArr = array();
if(is_array($rec_score_listTmpArr) && !empty($rec_score_listTmpArr)){
    foreach ($rec_score_listTmpArr as $key => $value){
        if(!empty($value)){
            list($month, $score) = explode("|", $value);
            $month = intval($month);
            $score = intval($score);
            if(!empty($month) && !empty($score)){
                $rec_scoreArr[$month] = $score;
            }
        }
    }
}

if($act == 'addrec' && $_GET['formhash'] == FORMHASH){
    
    $day_id   = intval($_GET['day_id'])>0? intval($_GET['day_id']):0;
    
    $outArr = array(
        'status'=> 200,
    );
    
    if(!isset($rec_scoreArr[$day_id])){
        $outArr['status'] = 301;
        echo json_encode($outArr); exit;
    }
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($rec_scoreArr) && $rec_scoreArr[$day_id] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }
    if($__UserInfo['recommend'] == 0){
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['rec_status'] = 1;
        $insertData['rec_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_rec')->insert($insertData); 
    }
    
    $recommend_time = TIMESTAMP;
    if($__UserInfo['recommend_time'] > TIMESTAMP){
        $recommend_time = $__UserInfo['recommend_time'] + $day_id*86400;
    }else{
        $recommend_time = TIMESTAMP + $day_id*86400;
    }
    
    if($__UserInfo['vip_id'] == 0){
        $updateData = array();
        $updateData['recommend_time'] = $recommend_time;
        $updateData['recommend_do_time'] = TIMESTAMP;
        $updateData['score'] = $__UserInfo['score']-$rec_scoreArr[$day_id];
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $rec_scoreArr[$day_id];
        $insertData['log_type'] = 7;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }else{
        $updateData = array();
        $updateData['recommend_time'] = $recommend_time;
        $updateData['recommend_do_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
    }
    
    
    echo json_encode($outArr); exit;
}else{
    
    $recStatus = 0;
    $recInfo = C::t('#tom_love#tom_love_rec')->fetch_by_uid($__UserInfo['id']);
    if($recInfo && $recInfo['rec_status'] == 1 && $__UserInfo['recommend'] == 0){
        $recStatus = 1;
    }
    
    if($__UserInfo['recommend_time'] > 0){
        $recommendTime = dgmdate($__UserInfo['recommend_time'], 'Y-m-d',$tomSysOffset);
    }else{
        $recommendTime = ' - - ';
    }

    $recUrl   = "plugin.php?id=tom_love&mod=rec&act=addrec";

    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_love:rec");
    
}

