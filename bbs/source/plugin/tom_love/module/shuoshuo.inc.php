<?php

if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

if($act == 'save' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 200,
    );
    
    if('utf-8' != CHARSET) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && $_G['setting']['mobile']['allowmobile'] == 1 && $_G['setting']['mobile']['mobileforward'] == 1){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $content = isset($_GET['describe'])? daddslashes($_GET['describe']):'';
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($jyConfig['shuoshuo_score']) && $jyConfig['shuoshuo_score'] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }

    $bmpicliArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "bmpicli") !== false){
            $bmpicliArr[] = addslashes($value);
        }
    }
    
    $insertData = array();
    $insertData['user_id'] = $__UserInfo['id'];
    $insertData['content'] = $content;
    $insertData['ss_time'] = TIMESTAMP;
    if(C::t('#tom_love#tom_love_shuoshuo')->insert($insertData)){
        $shuoshuoId = C::t('#tom_love#tom_love_shuoshuo')->insert_id();
        foreach ($bmpicliArr as $key => $value){
            $insertData = array();
            $insertData['ss_id'] = $shuoshuoId;
            $insertData['picurl'] = $value;
            C::t('#tom_love#tom_love_shuoshuo_photo')->insert($insertData);
        }
        
        if($__UserInfo['vip_id'] == 0){
            $updateData = array();
            $updateData['score'] = $__UserInfo['score']-$jyConfig['shuoshuo_score'];
            C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
            
            $insertData = array();
            $insertData['user_id'] = $__UserInfo['id'];
            $insertData['score_value'] = $jyConfig['shuoshuo_score'];
            $insertData['log_type'] = 12;
            $insertData['log_time'] = TIMESTAMP;
            C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
        }
    }

    echo json_encode($outArr); exit;
}else if($act == 'zan' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $ssid      = isset($_GET['reSsid'])? intval($_GET['reSsid']):0;
    $replyuid  = isset($_GET['reUid'])? intval($_GET['reUid']):0;
    
    $user = C::t('#tom_love#tom_love')->fetch_by_id($replyuid);
    
    $insertData = array();
    $insertData['ss_id']              = $ssid;
    $insertData['zan_user_id']        = $user['id'];
    $insertData['zan_user_nickname']  = $user['nickname'];
    $insertData['zan_user_avatar']    = $user['avatar'];
    $insertData['zan_user_sex']       = $user['sex'];
    $insertData['zan_time']           = TIMESTAMP;
    C::t('#tom_love#tom_love_shuoshuo_zan')->insert($insertData);
    
    $ssZanDataCount = C::t('#tom_love#tom_love_shuoshuo_zan')->fetch_all_count(" AND ss_id={$ssid} ");
    $updateData = array();
    $updateData['zan_count'] = $ssZanDataCount;
    C::t('#tom_love#tom_love_shuoshuo')->update($ssid,$updateData);
    
    echo json_encode($outArr); exit;
}else if($act == 'reply' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $ssid      = isset($_GET['reSsid'])? intval($_GET['reSsid']):0;
    $replyuid  = isset($_GET['reUid'])? intval($_GET['reUid']):0;
    $content   = isset($_GET['txtContentAdd'])? daddslashes(diconv(urldecode($_GET['txtContentAdd']),'utf-8')):'';
    
    $shuoshuoInfo = C::t('#tom_love#tom_love_shuoshuo')->fetch_by_id($ssid);
    $user = C::t('#tom_love#tom_love')->fetch_by_id($replyuid);
    
    $insertData = array();
    $insertData['ss_id']                = $ssid;
    $insertData['reply_user_id']        = $user['id'];
    $insertData['reply_user_nickname']  = $user['nickname'];
    $insertData['reply_user_avatar']    = $user['avatar'];
    $insertData['reply_user_sex']       = $user['sex'];
    $insertData['content']              = $content;
    $insertData['reply_time']           = TIMESTAMP;
    C::t('#tom_love#tom_love_shuoshuo_reply')->insert($insertData);
    
    $ssReplyDataCount = C::t('#tom_love#tom_love_shuoshuo_reply')->fetch_all_count(" AND ss_id={$ssid} ");
    $updateData = array();
    $updateData['reply_count'] = $ssReplyDataCount;
    C::t('#tom_love#tom_love_shuoshuo')->update($ssid,$updateData);
    
    $toUser = C::t('#tom_love#tom_love')->fetch_by_id($shuoshuoInfo['user_id']);
    include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUser['last_smstp_time'] + 1;
    if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=shuoshuo&uid={$shuoshuoInfo['user_id']}");
        $reply_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_love','reply_template_first'));
        $flowersData = array(
            'first'         => $reply_template_first,
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
}else if($act == 'del_shuoshuo' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 1,
    );
    
    $ssid      = isset($_GET['reSsid'])? intval($_GET['reSsid']):0;
    
    $shuoshuoInfo = C::t('#tom_love#tom_love_shuoshuo')->fetch_by_id($ssid);
    
    if($shuoshuoInfo['user_id'] == $__UserInfo['id'] ){
        C::t('#tom_love#tom_love_shuoshuo')->delete($ssid);
        C::t('#tom_love#tom_love_shuoshuo_photo')->delete_by_ssid($ssid);
        C::t('#tom_love#tom_love_shuoshuo_reply')->delete_by_ssid($ssid);
        C::t('#tom_love#tom_love_shuoshuo_zan')->delete_by_ssid($ssid);
        $outArr = array(
            'status'=> 200,
        );
    }
    echo json_encode($outArr); exit;
    
}else if($act == 'del_reply' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 1,
    );
    
    $replyId      = isset($_GET['replyId'])? intval($_GET['replyId']):0;
    
    $replyInfo = C::t('#tom_love#tom_love_shuoshuo_reply')->fetch_by_id($replyId);
    
    if($replyInfo['reply_user_id'] == $__UserInfo['id'] ){
        C::t('#tom_love#tom_love_shuoshuo_reply')->delete($replyId);
        $ssReplyDataCount = C::t('#tom_love#tom_love_shuoshuo_reply')->fetch_all_count(" AND ss_id={$replyInfo['ss_id']} ");
        $updateData = array();
        $updateData['reply_count'] = $ssReplyDataCount;
        C::t('#tom_love#tom_love_shuoshuo')->update($replyInfo['ss_id'],$updateData);
        $outArr = array(
            'status'=> 200,
        );
    }
    
    echo json_encode($outArr); exit;
}else if($act == 'loadMore'){
    $outStr = '';
    
    $page     = isset($_GET['page'])? intval($_GET['page']):1;
    $uid     = isset($_GET['uid'])? intval($_GET['uid']):0;
    $pagesize = 10;
    $start = ($page-1)*$pagesize;

    $where = "";
    if(!empty($uid)){
        $where = " AND user_id={$uid} ";
    }
    $ssData = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_list($where,"ORDER BY ss_time DESC",$start,$pagesize);
    $ssDataCount = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_count($where);
    
    $ssList = array();
    if(is_array($ssData) && !empty($ssData)){
        foreach ($ssData as $key => $value){
            
            $user = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
            if($user['status'] == 2){
                continue;
            }

            $ssList[$key]         = $value;
            $ssList[$key]['content']    = dhtmlspecialchars($value['content']);
            
            $ssList[$key]['userinfo'] = $user;
            if(is_array($user) && !empty($user)){   
                if(!preg_match('/^http:/', $user['avatar'])){
                    if(strpos($user['avatar'], 'source/plugin/tom_love') === false){
                        $ssList[$key]['userinfo']['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$user['avatar'];
                    }else{
                        $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
                    }
                }else{
                    $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
                }
            }
        
            $ssPhotoData = C::t('#tom_love#tom_love_shuoshuo_photo')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,2);
            $ssList[$key]['ss_photo'] = $ssPhotoData;
            if(is_array($ssPhotoData) && !empty($ssPhotoData)){
                foreach($ssPhotoData as $kp => $vp){
                    if(!preg_match('/^http:/', $vp['picurl'])){
                        if(strpos($vp['picurl'], 'source/plugin/tom_love') === false){
                            $ssList[$key]['ss_photo'][$kp]['picurl'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$vp['picurl'];
                        }else{
                            $ssList[$key]['ss_photo'][$kp]['picurl'] = $vp['picurl'];
                        }
                    }else{
                        $ssList[$key]['ss_photo'][$kp]['picurl'] = $vp['picurl'];
                    }
                }
            }
            
            $ssReplyDataTmp = C::t('#tom_love#tom_love_shuoshuo_reply')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,50);
            $ssReplyData = array();
            if(is_array($ssReplyDataTmp) && !empty($ssReplyDataTmp)){
                foreach ($ssReplyDataTmp as $k => $v){
                    $ssReplyData[$k] = $v;
                    $ssReplyData[$k]['content']    = dhtmlspecialchars($v['content']);
                    if(!preg_match('/^http:/', $v['reply_user_avatar'])){
                        if(strpos($v['reply_user_avatar'], 'source/plugin/tom_love') === false){
                             $ssReplyData[$k]['reply_user_avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$v['reply_user_avatar'];
                        }else{
                            $ssReplyData[$k]['reply_user_avatar'] = $v['reply_user_avatar'];
                        }
                    }else{
                        $ssReplyData[$k]['reply_user_avatar'] = $v['reply_user_avatar'];
                    }
                }
            }
            $ssList[$key]['ss_reply'] = $ssReplyData;
            
            $ssZanData = C::t('#tom_love#tom_love_shuoshuo_zan')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,20);
            $ssList[$key]['ss_zan'] = $ssZanData;
            if(is_array($ssZanData) && !empty($ssZanData)){
                foreach($ssZanData as $k => $v){
                    if(!preg_match('/^http:/', $v['zan_user_avatar'])){
                        if(strpos($v['zan_user_avatar'], 'source/plugin/tom_love') === false){
                            $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$v['zan_user_avatar'];
                        }else{
                            $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = $v['zan_user_avatar'];
                        }
                    }else{
                        $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = $v['zan_user_avatar'];
                    }
                }
            }
            
            $is_zan_flag = 0;
            if(is_array($ssZanData) && !empty($ssZanData)){
                foreach ($ssZanData as $kk => $vv){
                    if($vv['zan_user_id'] == $__UserInfo['id']){
                        $is_zan_flag = 1;
                    }
                }
            }
            $ssList[$key]['is_zan_flag'] = $is_zan_flag;
        }
    }
    
    if(is_array($ssList) && !empty($ssList)){
    foreach ($ssList as $key => $value){
        $outStr.="<ul class=\"content\" id=\"shuoshuo-{$value['id']}\" style=\"border-bottom-color: rgb(214,214,214); border-bottom-width: 1px; position: relative;\">";
            $outStr.="<li class=\"on\">";
              $outStr.="<dl class=\"clearfix\">";
                $outStr.="<dt style=\"position: relative;\">";
                    $outStr.="<a href=\"plugin.php?id=tom_love&mod=info&uid={$value['userinfo']['id']}\">";
                        $outStr.="<img width=\"100%\" src=\"{$value['userinfo']['avatar']}\" width=\"40px\" height=\"40px\">";
                    $outStr.="</a>";
                $outStr.="</dt>";
                $outStr.="<dd style=\"position: relative;\">";
                    $outStr.="<label style=\"font-size:14px; color: #F90271\">";
                        if($value['userinfo']['sex'] == 1){
                            $outStr.="<font class='man' color=\"#5a85ce\">".lang('plugin/tom_love','man_ico')."</font>";
                        }else{
                            $outStr.="<font class='woman' color=\"#F90271\">".lang('plugin/tom_love','woman_ico')."</font>";
                        }
                        $outStr.="<font color=\"#5a85ce\">{$value['userinfo']['nickname']}:</font>";
                        if($value['user_id'] == $__UserInfo['id']){
                            $outStr.="<span class=\"identity Check\"  onclick=\"deleteShuoshuo({$value['id']});\">".lang('plugin/tom_love','shuoshuo_lang_del')."</span>&nbsp;";
                        }
                        $ss_time = dgmdate($value['ss_time'], 'Y-m-d',$tomSysOffset);
                        $outStr.="<span class=\"fr c858 f12\">{$ss_time}</span>";
                    $outStr.="</label>";
                    $outStr.="<div class=\"f14\" style=\"line-height: 1.5; -ms-word-wrap: normal;\">";
                        $outStr.="<div id=\"contc-314004\">{$value['content']}</div>";
                        if(is_array($value['ss_photo']) && !empty($value['ss_photo'])){
                        $outStr.="<div class=\"on_img clearfix\">";
                           $outStr.="<ul id=\"Gallery\" class=\"gallery\">";
                                foreach ($value['ss_photo'] as $k1 => $v1){
                                    $outStr.="<li><img src=\"{$v1['picurl']}\"  style=\"max-height:120px;max-width:120px;\"></a></li>";
                                }
                           $outStr.="</ul>";
                        $outStr.="</div>";
                        }
                        $outStr.="<div class=\"gj c858 clearfix\">";
                            $show_praise_onclick = "";
                            if($value['is_zan_flag'] == 0){
                                $show_praise_onclick = "onclick=\"praise({$value['id']})\"";
                            }
                            $show_class_zaned = "";
                            if($value['is_zan_flag'] == 1){
                                $show_class_zaned = "zaned";
                            }
                            $outStr.="<span class=\"t_r\" id=\"btn-zan{$value['id']}\" {$show_praise_onclick}>";
                                $outStr.="<I class=\"gj_za {$show_class_zaned}\"></I>".lang('plugin/tom_love','shuoshuo_lang_zan')."<EM id=\"zanCount-{$value['id']}\">{$value['zan_count']}</EM> ";
                            $outStr.="</span> ";
                            $outStr.="<span class=\"t_r\" id=\"btn-reply{$value['id']}\" ssid=\"{$value['id']}\"  articlereplybtn=\"\">";
                                $outStr.="<I class=\"gj_p\"></I>".lang('plugin/tom_love','shuoshuo_lang_ping')."<EM id=\"replyCount-{$value['id']}\">{$value['reply_count']}</EM> ";
                            $outStr.="</span>";
                        $outStr.="</div>";
                    $outStr.="</div>";
                $outStr.="</dd>";
              $outStr.="</dl>";
            $outStr.="</li>";
            if(is_array($value['ss_zan']) && !empty($value['ss_zan'])){
            $outStr.="<li class=\"in rel artcomment\" id=\"zan-{$value['id']}\" style=\"border-bottom-color: rgb(214, 214, 214);border-bottom-width: 1px;border-bottom-style: solid;\">";
                $outStr.="<aside class=\"rel bz_img clearfix\">";
                    foreach ($value['ss_zan'] as $k2 => $v2){
                    $outStr.="<a href=\"plugin.php?id=tom_love&mod=info&uid={$v2['zan_user_id']}\">";
                        $outStr.="<img src=\"{$v2['zan_user_avatar']}\" width=\"35px\" height=\"35px\">";
                    $outStr.="</a>";
                    }
                    $outStr.="<i class=\"shang abs\"></i>";
                $outStr.="</aside>";
            $outStr.="</li>";
            }
            if(is_array($value['ss_reply']) && !empty($value['ss_reply'])){
            foreach ($value['ss_reply'] as $k3 => $v3){
            $outStr.="<li class=\"in rel artcomment\" id=\"reply-{$v3['id']}\" style=\"border-bottom-color: rgb(214, 214, 214); border-bottom-width: 1px; border-bottom-style: solid;\">";
            $outStr.="<dl class=\"clearfix\">";
                $outStr.="<dt>";
                $outStr.="<a href=\"plugin.php?id=tom_love&mod=info&uid={$v3['reply_user_id']}\">";
                    $outStr.="<img  src=\"{$v3['reply_user_avatar']}\" width=\"30px\" height=\"30px\">";
                $outStr.="</a> ";
                $outStr.="</dt>";
                $outStr.="<dd>";
                    $outStr.="<label style=\"font-size:14px; color: #F90271\">";
                    if($v3['reply_user_sex'] == 1){
                        $outStr.="<font color=\"#5a85ce\">".lang('plugin/tom_love','man_ico')."</font>";
                    }else{
                        $outStr.="<font color=\"#F90271\">".lang('plugin/tom_love','woman_ico')."</font>";
                    }
                    $outStr.="<font color=\"#5a85ce\">{$v3['reply_user_nickname']}:</font>";
                    if($v3['reply_user_id'] == $__UserInfo['id']){
                        $outStr.="<span class=\"identity Check\" onclick=\"deleteReply({$v3['id']})\">".lang('plugin/tom_love','shuoshuo_lang_del')."</span>";
                    }
                    $reply_time = dgmdate($v3['reply_time'], 'Y-m-d',$tomSysOffset);
                    $outStr.="<span class=\"fr c858 f12\">{$reply_time}</span> ";
                $outStr.="</label>";
                $outStr.="<P>{$v3['content']}</P>";
                $outStr.="</dd>";
            $outStr.="</dl>";
            $outStr.="<I class=\"ping abs\"></I>";
            $outStr.="</li>";
            }}
        $outStr.="</ul>";
    }}else{
        $outStr = '205';
    }
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
}else if($act == 'addshuoshuo'){
    
    $shuoshuoUrl = "plugin.php?id=tom_love&mod=shuoshuo";
    $uploadUrl = "plugin.php?id=tom_love&mod=upload&act=shuoshuo&formhash=".FORMHASH;
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_love:addshuoshuo");
}else{
    $page     = isset($_GET['page'])? intval($_GET['page']):1;
    $uid     = isset($_GET['uid'])? intval($_GET['uid']):0;

    $pagesize = 10;
    $start = ($page-1)*$pagesize;

    $where = "";
    if(!empty($uid)){
        $where = " AND user_id={$uid} ";
    }
    $ssData = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_list($where,"ORDER BY ss_time DESC",$start,$pagesize);
    $ssDataCount = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_count($where);
    
    $ssList = array();
    if(is_array($ssData) && !empty($ssData)){
        foreach ($ssData as $key => $value){
            
            $user = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
            if($user['status'] == 2){
                continue;
            }
            $ssList[$key]         = $value;
            $ssList[$key]['content']    = dhtmlspecialchars($value['content']);
            $ssList[$key]['userinfo'] = $user;
            if(is_array($user) && !empty($user)){    
                if(!preg_match('/^http:/', $user['avatar'])){
                    if(strpos($user['avatar'], 'source/plugin/tom_love') === false){
                        $ssList[$key]['userinfo']['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$user['avatar'];
                    }else{
                        $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
                    }
                }else{
                    $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
                }
            }
            
            $ssPhotoData = C::t('#tom_love#tom_love_shuoshuo_photo')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,2);
            $ssList[$key]['ss_photo'] = $ssPhotoData;
            if(is_array($ssPhotoData) && !empty($ssPhotoData)){    
                foreach($ssPhotoData as $kp => $vp){
                    if(!preg_match('/^http:/', $vp['picurl'])){
                        if(strpos($vp['picurl'], 'source/plugin/tom_love') === false){
                            $ssList[$key]['ss_photo'][$kp]['picurl'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$vp['picurl'];
                        }else{
                            $ssList[$key]['ss_photo'][$kp]['picurl'] = $vp['picurl'];
                        }
                    }else{
                        $ssList[$key]['ss_photo'][$kp]['picurl'] = $vp['picurl'];
                    }
                }
            }
                
            $ssReplyDataTmp = C::t('#tom_love#tom_love_shuoshuo_reply')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,50);
            $ssReplyData = array();
            if(is_array($ssReplyDataTmp) && !empty($ssReplyDataTmp)){
                foreach ($ssReplyDataTmp as $k => $v){
                    $ssReplyData[$k] = $v;
                    $ssReplyData[$k]['content']    = dhtmlspecialchars($v['content']);
                    if(!preg_match('/^http:/', $v['reply_user_avatar'])){
                        if(strpos($v['reply_user_avatar'], 'source/plugin/tom_love') === false){
                            $ssReplyData[$k]['reply_user_avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$v['reply_user_avatar'];
                        }else{
                            $ssReplyData[$k]['reply_user_avatar'] = $v['reply_user_avatar'];
                        }
                    }else{
                        $ssReplyData[$k]['reply_user_avatar'] = $v['reply_user_avatar'];
                    }
                }
            }
            $ssList[$key]['ss_reply'] = $ssReplyData;
            
            $ssZanData = C::t('#tom_love#tom_love_shuoshuo_zan')->fetch_all_list(" AND ss_id={$value['id']} ","ORDER BY id DESC",0,20);
            $ssList[$key]['ss_zan'] = $ssZanData;
            if(is_array($ssZanData) && !empty($ssZanData)){
                foreach($ssZanData as $k => $v){
                    if(!preg_match('/^http:/', $v['zan_user_avatar'])){
                        if(strpos($v['zan_user_avatar'], 'source/plugin/tom_love') === false){
                            $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$v['zan_user_avatar'];
                        }else{
                            $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = $v['zan_user_avatar'];
                        }
                    }else{
                        $ssList[$key]['ss_zan'][$k]['zan_user_avatar'] = $v['zan_user_avatar'];
                    }
                }
            }
            
            $is_zan_flag = 0;
            if(is_array($ssZanData) && !empty($ssZanData)){
                foreach ($ssZanData as $kk => $vv){
                    if($vv['zan_user_id'] == $__UserInfo['id']){
                        $is_zan_flag = 1;
                    }
                }
            }
            $ssList[$key]['is_zan_flag'] = $is_zan_flag;
            
        }
    }
    
    $showNextPage = 1;
    if(($start + $pagesize) >= $ssDataCount){
        $showNextPage = 0;
    }
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $nextPageUrl = "plugin.php?id=tom_love&mod=shuoshuo&page={$nextPage}";
    $prePageUrl = "plugin.php?id=tom_love&mod=shuoshuo&page={$prePage}";
    
    $shuoshuoUrl = "plugin.php?id=tom_love&mod=shuoshuo";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_love:shuoshuo");
}

function wx_iconv_recurrence($value) {
	if(is_array($value)) {
		foreach($value AS $key => $val) {
			$value[$key] = wx_iconv_recurrence($val);
		}
	} else {
		$value = diconv($value, 'utf-8', CHARSET);
	}
	return $value;
}
