<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$scoretype = 'extcredits1';
$scorename = $_G['setting']['extcredits'][1]['title'];
if(intval($jyConfig['bbs_score_type']) >= 1){
    $scoretype = 'extcredits'.$jyConfig['bbs_score_type'];
    $scorename = $_G['setting']['extcredits'][$jyConfig['bbs_score_type']]['title'];
}

$userBbsScore = C::t('#tom_love#tom_common_member_count')->result_by_uid($__UserInfo['bbs_uid'],$scoretype);

$scoreArr = array(
    '1' => $jyConfig['bbs_score_scale']*1,
    '2' => $jyConfig['bbs_score_scale']*2,
    '5' => $jyConfig['bbs_score_scale']*5,
    '10' => $jyConfig['bbs_score_scale']*10,
    '20' => $jyConfig['bbs_score_scale']*20,
);

if($_GET['act'] == 'recharge' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 0,
    );
    $bbs_score = isset($_GET['bbs_score'])? intval($_GET['bbs_score']):'';
    
    if($bbs_score > $userBbsScore){
        $outArr['status'] = 201;
    }else{
        $updateData = array();
        $updateData['score'] = $__UserInfo['score'] + $bbs_score*$jyConfig['bbs_score_scale'];
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
            
        $deductScore = -1 * $bbs_score;
        updatemembercount($__UserInfo['bbs_uid'] , array($scoretype => $deductScore));
        $outArr['status'] = 200;
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $bbs_score*$jyConfig['bbs_score_scale'];
        $insertData['log_type'] = 1;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
    }
    echo json_encode($outArr); exit;
}

require_once libfile('function/discuzcode');
$scoreString = discuzcode($jyConfig['score_page'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$rechargeUrl   = "plugin.php?id=tom_love&mod=score";

$scorelogData = C::t('#tom_love#tom_love_scorelog')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND log_type=1 ","ORDER BY log_time DESC",0,50);
$scorelogList = array();
if(is_array($scorelogData) && !empty($scorelogData)){
    foreach ($scorelogData as $logkey => $logvalue){
        $scorelogList[$logkey] = $logvalue;
    }
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:score");

