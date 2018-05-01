<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tid     = isset($_GET['tid'])? trim($_GET['tid']):0;

$toUser = C::t('#tom_love#tom_love')->fetch_by_id($tid);

if($_GET['act'] == 'send' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    if($tid == $__UserInfo['id']){
        $outArr['status'] = 1;
        echo json_encode($outArr);exit;
    }
    
    $flowers_num = isset($_GET['flowers_num'])? intval($_GET['flowers_num']):1;
    
    $buyScore = $flowers_num*$jyConfig['flowers_score'];
    
    if($buyScore > $__UserInfo['score']){
        $outArr['status'] = 101;
        echo json_encode($outArr); exit;
    }
    
    $updateData = array();
    $updateData['flowers'] = $toUser['flowers']+$flowers_num;
    C::t('#tom_love#tom_love')->update($tid,$updateData);
    
    //if($__UserInfo['vip_id'] == 0){
        $updateData = array();
        $updateData['score'] = $__UserInfo['score']-$buyScore;
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $buyScore;
        $insertData['log_type'] = 11;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    //}
    
    $content = lang('plugin/tom_love','tz_flowers_content');
    $content = str_replace("{UID}", $__UserInfo['id'], $content);
    $content = str_replace("{NAME}", $__UserInfo['nickname'], $content);
    $content = str_replace("{NUM}", $flowers_num, $content);
    
    $insertData = array();
    $insertData['user_id']     = $tid;
    $insertData['type']        = 1;
    $insertData['title']       = lang('plugin/tom_love','tz_flowers_title');
    $insertData['content']     = $content;
    $insertData['tz_time']     = TIMESTAMP;
    $insertData['is_read']     = 0;
    C::t('#tom_love#tom_love_tz')->insert($insertData);
    
    include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUser['last_smstp_time'] + 1;
    if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=sms&act=tzlist");
        $flowers_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_love','flowers_template_first'));
        $flowers_template_first = str_replace("{NUM}",$flowers_num, $flowers_template_first);
        $flowersData = array(
            'first'         => $flowers_template_first,
            'keyword1'      => '-',
            'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
            'remark'        => ''
        );
        $r = $templateSmsClass->sendSmsTm20702951($toUser['openid'],$jyConfig['template_tm20702951'],$flowersData);
        
        $updateData = array();
        $updateData['last_smstp_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($toUser['id'],$updateData);

    }
    
    echo json_encode($outArr); exit;
}

$sendUrl   = "plugin.php?id=tom_love&mod=flowers&tid={$tid}";


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:flowers");

