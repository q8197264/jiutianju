<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

if($act == 'send' && $_GET['formhash'] == FORMHASH){
    $text = isset($_GET['text'])? daddslashes(diconv(urldecode($_GET['text']),'utf-8')):'';
    $tid = isset($_GET['tid'])? intval($_GET['tid']):0;
    
    $smsDataCount = uc_pm_view_num($__UserInfo['bbs_uid'], $tid, 0);
    
    if($jyConfig['every_sms_score'] == 1){
        $smsDataCount = 0;
    }
    if($smsDataCount == 0){
        if($__UserInfo['vip_id'] == 0){
            if($__UserInfo['score'] >= $jyConfig['first_sms_score'] ){
                $updateData = array();
                $updateData['score'] = $__UserInfo['score']-$jyConfig['first_sms_score'];
                C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
                
                $insertData = array();
                $insertData['user_id'] = $__UserInfo['id'];
                $insertData['score_value'] = $jyConfig['first_sms_score'];
                $insertData['log_type'] = 13;
                $insertData['log_time'] = TIMESTAMP;
                C::t('#tom_love#tom_love_scorelog')->insert($insertData);
                
            }else{
                echo '100';exit;
            }
        }
    }
    
    $sms_object = lang('plugin/tom_love','sms_object');
    sendpm($tid, $sms_object, $text, $__UserInfo['bbs_uid']);
    
    include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
    $toUserInfo = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($tid);
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUserInfo['last_smstp_time'] + 1800;
    if($access_token && !empty($toUserInfo['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=sms");
        $sms_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_love','sms_template_first'));
        $smsData = array(
            'first'         => $sms_template_first,
            'keyword1'      => '-',
            'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
            'remark'        => $text
        );
        $r = $templateSmsClass->sendSmsTm20702951($toUserInfo['openid'],$jyConfig['template_tm20702951'],$smsData);
        
        $updateData = array();
        $updateData['last_smstp_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($toUserInfo['id'],$updateData);

    }
    
    echo '1';exit;
    
}else if($act == 'del' && $_GET['formhash'] == FORMHASH){
    $tid = isset($_GET['tid'])? intval($_GET['tid']):0;
    uc_pm_deleteuser($__UserInfo['bbs_uid'], array($tid));
    echo '1';exit;
    
}else if($act == 'view'){
    $pageType = 'view';
    $pmList = array();
    $page     = isset($_GET['page'])? intval($_GET['page']):0;
    $toBbsUid = isset($_GET['tobbsuid'])? intval($_GET['tobbsuid']):0;

    $pagesize = 5;
    $toUserPic = '';
    $toUserInfo = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($toBbsUid);
    if(!empty($toUserInfo['avatar']) && is_array($toUserInfo)){    
        if(!empty($toUserInfo['avatar'])){
            if(!preg_match('/^http/', $toUserInfo['avatar'])){
                if(strpos($toUserInfo['avatar'], 'source/plugin/tom_love') === false){
                    $toUserPic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$toUserInfo['avatar'];
                }else{
                    $toUserPic = $toUserInfo['avatar'];
                }
            }else{
                $toUserPic = $toUserInfo['avatar'];
            }
        }
    }
        
    $smsDataCount = uc_pm_view_num($__UserInfo['bbs_uid'], $toBbsUid, 0);
    if(!$page) {
        $page = ceil($smsDataCount/$pagesize);
    }
    uc_pm_readstatus($__UserInfo['bbs_uid'], array($toBbsUid));
    
    $pmList = uc_pm_view($__UserInfo['bbs_uid'], 0, $toBbsUid, 5, ceil($smsDataCount/$pagesize)-$page+1, $pagesize, 0, 0);
    $showNextPage = 1;
    $start = ($page-1)*$pagesize;
    if(($start + $pagesize) >= $smsDataCount){
        $showNextPage = 0;
    }
    $showPageBox = 0;
    if($smsDataCount > $pagesize){
        $showPageBox = 1;
    }
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=tom_love&mod=sms&act=view&tobbsuid=$toBbsUid&page={$prePage}";
    $nextPageUrl = "plugin.php?id=tom_love&mod=sms&act=view&tobbsuid=$toBbsUid&page={$nextPage}";
    
}else if($act == 'tzlist'){
    $pageType = 'tzlist';
    $page     = isset($_GET['page'])? intval($_GET['page']):1;

    $pagesize = 6;
    $start = ($page-1)*$pagesize;

    $where = " AND user_id={$__UserInfo['id']} AND type=1 ";
    $tzData = C::t('#tom_love#tom_love_tz')->fetch_all_list($where,"ORDER BY tz_time DESC",$start,$pagesize);
    $tzDataCount = C::t('#tom_love#tom_love_tz')->fetch_all_count($where);
    
    $tzList = array();
    if(is_array($tzData) && !empty($tzData)){
        foreach ($tzData as $key => $value){
            $updateData = array();
            $updateData['is_read'] = 1;
            C::t('#tom_love#tom_love_tz')->update($value['id'],$updateData);
            $value['content'] = htmlspecialchars_decode($value['content']);
            $tzList[$key] = $value;
        }
    }
    
    $showNextPage = 1;
    if(($start + $pagesize) >= $tzDataCount){
        $showNextPage = 0;
    }
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=tom_love&mod=sms&act=tzlist&page={$prePage}";
    $nextPageUrl = "plugin.php?id=tom_love&mod=sms&act=tzlist&page={$nextPage}";
}else{
    $pageType = 'list';
    $smsList = array();
    $page     = isset($_GET['page'])? intval($_GET['page']):1;
    $pagesize = 15;
    $result = uc_pm_list($__UserInfo['bbs_uid'], $page, $pagesize, 'inbox', "privatepm", 100);
    $smsDataCount = $result['count'];
    $listTmp = $result['data'];
    
    if(is_array($listTmp) && !empty($listTmp)){
        $today = $_G['timestamp'] - ($_G['timestamp'] + $_G['setting']['timeoffset'] * 3600) % 86400;
        foreach ($listTmp as $key => $value){
            if($value['pmtype'] = 1){
                $toUserInfo = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($value['touid']);
                if($toUserInfo){
                    $smsList[$key]= $value;
                    $smsList[$key]['__tonickname'] = $toUserInfo['nickname'];
                    $smsList[$key]['__toid'] = $toUserInfo['id'];
                    if(!preg_match('/^http:/', $toUserInfo['avatar'])){
                        if(strpos($toUserInfo['avatar'], 'source/plugin/tom_love') === false){
                            $smsList[$key]['__toavatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$toUserInfo['avatar'];
                        }else{
                            $smsList[$key]['__toavatar'] = $toUserInfo['avatar'];
                        }
                    }else{
                        $smsList[$key]['__toavatar'] = $toUserInfo['avatar'];
                    }
                }
            }
        }
    }
    $showNextPage = 1;
    $start = ($page-1)*$pagesize;
    if(($start + $pagesize) >= $smsDataCount){
        $showNextPage = 0;
    }
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=tom_love&mod=sms&page={$prePage}";
    $nextPageUrl = "plugin.php?id=tom_love&mod=sms&page={$nextPage}";
    $deleteUrl = "plugin.php?id=tom_love&mod=sms&act=del&formhash=".FORMHASH;
}

$smsUrl = "plugin.php?id=tom_love&mod=sms";

$isgbk = false;
if (CHARSET == 'gbk') $isgbk = true;
include template("tom_love:sms");
