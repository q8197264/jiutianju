<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

if($act == 'save' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $report_user_id = isset($_GET['report_user_id'])? intval($_GET['report_user_id']):0;
    $report_content = isset($_GET['report_content'])? daddslashes(diconv(urldecode($_GET['report_content']),'utf-8')):'';
    
    $insertData = array();
    $insertData['user_id']          = $__UserInfo['id'];
    $insertData['report_user_id']   = $report_user_id;
    $insertData['report_content']   = $report_content;
    $insertData['report_time']      = TIMESTAMP;
    C::t('#tom_love#tom_love_report')->insert($insertData);

    echo json_encode($outArr); exit;
}else{
    
    
    
    $report_user_id = isset($_GET['report_user_id'])? intval($_GET['report_user_id']):0;
    
    $reportUserInfo = C::t('#tom_love#tom_love')->fetch_by_id($report_user_id);
    
    $reportUrl = "plugin.php?id=tom_love&mod=report";
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_love:report");
}

