<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

loaducenter();
$jyConfig = $_G['cache']['plugin']['tom_love'];
$pluginScriptLang = $scriptlang['tom_love'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowYear = dgmdate($_G['timestamp'], 'Y',$tomSysOffset);
$nowTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;

if($_GET['act'] == 'pic'){
    $callback = $_GET['callback'];
    $uid = isset($_GET['uid'])? intval($_GET['uid']):0;
    $outArr = array();
    $picListTmp = C::t('#tom_love#tom_love_pic')->fetch_all_list(" AND user_id ={$uid} ","ORDER BY id DESC",0,100);
    $picList = array();
    if(is_array($picListTmp) && !empty($picListTmp)){
        foreach($picListTmp as $key => $value){
            $picList[$key] = $value;
            if(!preg_match('/^http:/', $value['pic_url'])){
                if(strpos($value['pic_url'], 'source/plugin/tom_love') === false){
                    $picList[$key]['pic_url'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['pic_url'];
                }else{
                    $picList[$key]['pic_url'] = $value['pic_url'];
                }
            }else{
                $picList[$key]['pic_url'] = $value['pic_url'];
            }
        }
    }
    $i = 1;
    if(is_array($picList) && !empty($picList)){
        foreach ($picList as $key => $value) {
            $outArr[$i] = $value['pic_url'];
            $i++;
        }
    }
    $outStr = '';
    $outStr = json_encode($outArr);
    if($callback){
        $outStr = $callback . "(" . $outStr. ")";
    }
    echo $outStr;
    die();
}else if($_GET['act'] == 'city'){
    $callback = $_GET['callback'];
    $pid = isset($_GET['pid'])? intval($_GET['pid']):0;
    $outArr = array();
    $cityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($pid);
    if(is_array($cityList) && !empty($cityList)){
        foreach ($cityList as $key => $value) {
            $outArr[$key]['id'] = $value['id'];
            $outArr[$key]['name'] = diconv($value['name'],CHARSET,'utf-8');
        }
    }
    $outStr = '';
    $outStr = json_encode($outArr);
    if($callback){
        $outStr = $callback . "(" . $outStr. ")";
    }
    echo $outStr;
    die();
}else if($_GET['act'] == 'area'){
    $callback = $_GET['callback'];
    $pid = isset($_GET['pid'])? intval($_GET['pid']):0;
    $outArr = array();
    $areaList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($pid);
    if(is_array($areaList) && !empty($areaList)){
        foreach ($areaList as $key => $value) {
            $outArr[$key]['id'] = $value['id'];
            $outArr[$key]['name'] = diconv($value['name'],CHARSET,'utf-8');
        }
    }
    $outStr = '';
    $outStr = json_encode($outArr);
    if($callback){
        $outStr = $callback . "(" . $outStr. ")";
    }
    echo $outStr;
    die();
    
}else if($_GET['act'] == 'contact' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $uid = isset($_GET['uid'])? intval($_GET['uid']):0;
    $gid = isset($_GET['gid'])? intval($_GET['gid']):0;
    
    $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($uid);
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($jyConfig['contact_score']) && $jyConfig['contact_score'] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }
    
    $insertData = array();
    $insertData['user_id'] = $uid;
    $insertData['gx_user_id'] = $gid;
    $insertData['type_id'] = 1;
    C::t('#tom_love#tom_love_guanxi')->insert($insertData);
    
    if($__UserInfo['vip_id'] == 0){
        $updateData = array();
        $updateData['score'] = $__UserInfo['score']-$jyConfig['contact_score'];
        C::t('#tom_love#tom_love')->update($uid,$updateData);
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['contact_score'];
        $insertData['log_type'] = 9;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }
    
    $toUser = C::t('#tom_love#tom_love')->fetch_by_id($gid);
    $appid = trim($jyConfig['love_appid']);  
    $appsecret = trim($jyConfig['love_appsecret']); 
    include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
    $weixinClass = new weixinClass($appid,$appsecret);
    include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUser['last_smstp_time'] + 1;
    if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=info&uid={$uid}");
        $contact_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_love','contact_template_first'));
        $flowersData = array(
            'first'         => $contact_template_first,
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
    
}else if($_GET['act'] == 'anlian' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $uid = isset($_GET['uid'])? intval($_GET['uid']):0;
    $gid = isset($_GET['gid'])? intval($_GET['gid']):0;
    
    $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($uid);
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($jyConfig['anlian_score']) && $jyConfig['anlian_score'] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }
    
    $insertData = array();
    $insertData['user_id'] = $uid;
    $insertData['gx_user_id'] = $gid;
    $insertData['type_id'] = 2;
    C::t('#tom_love#tom_love_guanxi')->insert($insertData);
    
    DB::query("UPDATE ".DB::table('tom_love')." SET anlians=anlians+1 WHERE id='$gid'", 'UNBUFFERED');
    
    if($__UserInfo['vip_id'] == 0){
        $updateData = array();
        $updateData['score'] = $__UserInfo['score']-$jyConfig['anlian_score'];
        C::t('#tom_love#tom_love')->update($uid,$updateData);
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['anlian_score'];
        $insertData['log_type'] = 8;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }
    
    $toUser = C::t('#tom_love#tom_love')->fetch_by_id($gid);
    $appid = trim($jyConfig['love_appid']);  
    $appsecret = trim($jyConfig['love_appsecret']); 
    include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
    $weixinClass = new weixinClass($appid,$appsecret);
    include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUser['last_smstp_time'] + 1;
    if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=info&uid={$uid}");
        $anlian_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_love','anlian_template_first'));
        $flowersData = array(
            'first'         => $anlian_template_first,
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
}else if($_GET['act'] == 'hello' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    
    $uid = isset($_GET['uid'])? intval($_GET['uid']):0;
    $tid = isset($_GET['tid'])? intval($_GET['tid']):0;
    
    $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($uid);
    $toUser = C::t('#tom_love#tom_love')->fetch_by_id($tid);
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($jyConfig['hello_score']) && $jyConfig['hello_score'] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }
    
    if(!empty($__UserInfo['bbs_uid']) && !empty($toUser['bbs_uid'])){
        $helloTmpArr = explode("\n", $jyConfig['hello_txt']);
        $helloArr = array();
        if(is_array($helloTmpArr) && !empty($helloTmpArr)){
            foreach ($helloTmpArr as $key => $value){
                $value = trim($value);
                if(!empty($value)){
                    $helloArr[] = $value;
                }
            }
        }
        
        $randKey = array_rand($helloArr, 1);
        $sms_object = lang('plugin/tom_love','sms_object');
        
        sendpm($toUser['bbs_uid'], $sms_object, $helloArr[$randKey], $__UserInfo['bbs_uid']);
        
        $appid = trim($jyConfig['love_appid']);  
        $appsecret = trim($jyConfig['love_appsecret']); 
        include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
        include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
        $toUserInfo = C::t('#tom_love#tom_love')->fetch_by_bbs_uid($tid);
        $weixinClass = new weixinClass($appid,$appsecret);
        $access_token = $weixinClass->get_access_token();
        $nextSmsTime = $toUserInfo['last_smstp_time'] + 1800;
        if($access_token && !empty($toUserInfo['openid']) && TIMESTAMP > $nextSmsTime ){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_love&mod=sms");
            $sms_template_first = str_replace("{NICKNAME}",$toUserInfo['nickname'], lang('plugin/tom_love','sms_template_first'));
            $smsData = array(
                'first'         => $sms_template_first,
                'keyword1'      => $text,
                'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSmsTm20702951($toUserInfo['openid'],$jyConfig['template_tm20702951'],$smsData);

            $updateData = array();
            $updateData['last_smstp_time'] = TIMESTAMP;
            C::t('#tom_love#tom_love')->update($toUserInfo['id'],$updateData);

        }
        
        if($__UserInfo['vip_id'] == 0){
            $updateData = array();
            $updateData['score'] = $__UserInfo['score']-$jyConfig['hello_score'];
            C::t('#tom_love#tom_love')->update($uid,$updateData);
            
            $insertData = array();
            $insertData['user_id'] = $__UserInfo['id'];
            $insertData['score_value'] = $jyConfig['hello_score'];
            $insertData['log_type'] = 10;
            $insertData['log_time'] = TIMESTAMP;
            C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        }
        
    }
    
    echo json_encode($outArr); exit;
}else if($_GET['act'] == 'delanlian' && $_GET['formhash'] == FORMHASH){
    $outArr = array(
        'status'=> 200,
    );
    $id = isset($_GET['aid'])? intval($_GET['aid']):0;
    
    $guanxi = C::t('#tom_love#tom_love_guanxi')->fetch_by_id($id);
    if($guanxi){
       DB::query("UPDATE ".DB::table('tom_love')." SET anlians=anlians-1 WHERE id='{$guanxi['gx_user_id']}'", 'UNBUFFERED'); 
    }
    C::t('#tom_love#tom_love_guanxi')->delete($id);
    
    echo json_encode($outArr); exit;
}else if($_GET['act'] == 'share' && $_GET['formhash'] == FORMHASH){
    
    if($jyConfig['share_score_s'] == 0){
        echo '1';exit;
    }
    
    $uid = isset($_GET['uid'])? intval($_GET['uid']):0;
    
    $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($uid);
    
    $todayShareTimes = C::t('#tom_love#tom_love_share')->fetch_all_count(" AND user_id = {$uid}  AND share_time > {$nowTime} ");
    if($__UserInfo && $todayShareTimes < $jyConfig['share_time']){
        
        $updateData = array();
        $updateData['score'] = $__UserInfo['score'] + $jyConfig['share_score_num'];
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
        
        $insertData = array();
        $insertData['user_id'] = $uid;
        $insertData['share_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_share')->insert($insertData);
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['share_score_num'];
        $insertData['log_type'] = 3;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }
    
    echo '1';exit;
}else if($_GET['act'] == 'clicks' && $_GET['formhash'] == FORMHASH){
    $commonInfo = C::t('#tom_love#tom_love_common')->fetch_by_id(1);
    $updateData = array();
    $updateData['clicks'] = $commonInfo['clicks']+1;
    C::t('#tom_love#tom_love_common')->update(1,$updateData);
    echo 1;exit;

}else if($_GET['act'] == 'sexRefresh' && $_GET['formhash'] == FORMHASH){
    $outStr = '';
    
    $sexStart = isset($_GET['sexStart']) ? intval($_GET['sexStart']) : 0;
    $uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    
    $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($uid);
    
    $sexWhere = '';
    if(is_array($__UserInfo) && !empty($__UserInfo)){
        if($__UserInfo['sex'] == 1){
            $sexWhere = ' AND vip_id = 1 AND sex = 2';
        }else if($__UserInfo['sex'] == 2){
            $sexWhere = ' AND vip_id = 1 AND sex = 1';
        }else{
            $sexWhere = 'AND vip_id = 1';
        }
    }else{
        $sexWhere = ' AND vip_id = 1';
    }
    
    $sexVipCount = C::t('#tom_love#tom_love')->fetch_all_count($sexWhere);
    $pagesize = 3;
    $start = $randStart = $randEnd = 0;

    if($sexVipCount > $pagesize){
        $randEnd = $sexVipCount - $pagesize;
        $Start = mt_rand($randStart, $randEnd);
        if( $sexStart == $Start ){
            if(($sexStart+1) <=$randEnd){
                $Start = $sexStart+1;
            }else if(($sexStart-1) >= 0){
                $Start = $sexStart-1;
            }else{
                $outStr = '206';    
                echo json_encode($outStr);
                exit;
            }
        }
    }else{
        $outStr = '206';
        echo json_encode($outStr);
        exit;
    }

    $sexVipListTmp = C::t('#tom_love#tom_love')->fetch_all_list($sexWhere, 'ORDER BY id DESC', $Start, 3);
    if(is_array($sexVipListTmp) && !empty($sexVipListTmp)){
        foreach($sexVipListTmp as $key => $value){
            $value['height'] = intval($value['height']);
            if($value['year'] > 0){    
                if($jyConfig['age_type_id'] == 1){
                    $value['age'] = $nowYear - $value['year'];
                }else{
                    $value['age'] = $nowYear - $value['year'] + 1;
                }
            }else{
                $value['age'] = '';
            }
            if(!preg_match('/^http:/', $value['avatar'])){
                if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                    $value['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
                }
            }
            
            $outStr.='<div class="propmt-bar">';
                $outStr.='<a class="propmt" href="plugin.php?id=tom_love&mod=info&uid='.$value['id'].'">';
                    $outStr.='<div class="p-img">';
                        $outStr.='<img src="'.$value['avatar'].'">';
                        $outStr.='<div class="map"></div>';
                    $outStr.='</div>';
                    $outStr.='<p class="p-name">'.$value['nickname'].'</p>';
                    $outStr.='<p class="p-xinxi">';
                    if(!empty($value['age'])){
                        $outStr.=$value['age'].lang("plugin/tom_love", "sui").'&nbsp;';
                    }
                    $outStr.=$value['height'].lang("plugin/tom_love", "cm").'</p>';
                $outStr.='</a>';
            $outStr.='</div>';
        }
        
        $outStr.='<input type="hidden" id="sexStart" value="'.$Start.'" name="sexStart">';
    }else{
        $outStr = '205';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
}else if($_GET['act'] == 'loadMoreVip' && $_GET['formhash'] == FORMHASH){
    $outStr = '';
    
    $loadPage = isset($_GET['loadPage']) ? intval($_GET['loadPage']) : 1;
    $nowTime = TIMESTAMP;

    if($jyConfig['must_info'] == 1){
        $where = " AND recommend = 1 AND recommend_time > {$nowTime} AND status = 1 AND year>0 ";
    }else{
        $where = " AND recommend = 1 AND recommend_time > {$nowTime} AND status = 1 ";
    }
    
    $loadPagesize = 8;
    $loadStart = ($loadPage - 1)*$loadPagesize;
    
    $loadUserData = C::t('#tom_love#tom_love')->fetch_all_list($where," ORDER BY recommend_do_time DESC,id DESC", $loadStart, $loadPagesize);
    $loadUserDataCount = C::t('#tom_love#tom_love')->fetch_all_count($where);

    if(is_array($loadUserData) && !empty($loadUserData)){
        foreach ($loadUserData as $key => $value){
            $value['describe'] = dhtmlspecialchars($value['describe']);
            if($value['year'] > 0){
                if($jyConfig['age_type_id'] == 1){
                    $value['age'] = $nowYear - $value['year'];
                }else{
                    $value['age'] = $nowYear - $value['year'] + 1;
                }
            }else{
                $value['age'] = '';
            }
            if(!preg_match('/^http:/', $value['avatar'])){
                if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                    $value['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
                }
            }
            $sex = $value['sex'] == 1 ? 'boy' : 'girl' ;
            $outStr.= '<div class="'.$sex.' renwu clearfix">';
                $outStr.= '<div class="photo">';
                    $outStr.= '<a href="plugin.php?id=tom_love&mod=info&uid='.$value['id'].'">';
                        $outStr.= '<img src="'.$value['avatar'].'">';
                    $outStr.= '</a>';
                $outStr.= '</div>';
                $outStr.= '<div class="v-xq">';
                    $outStr.= '<a href="plugin.php?id=tom_love&mod=info&uid='.$value['id'].'">';
                        $outStr.= '<div class="v-name">';
                            $outStr.= '<span class="sex"></span>';
                            $outStr.= '<span class="name">'.$value['nickname'].'</span>';
                            if($value['renzheng'] == 1){ $outStr.= '<span class="rengzheng"></span>';}
                            if($value['renzheng'] == 1){ $outStr.= '<span class="vp"></span>';}
                            $outStr.= '<span class="tp">'.$value['pic_num'].'</span>';
                        $outStr.= '</div>';
                        $outStr.= '<div class="v-xinxi">';
                            if(!empty($value['age'])){
                                $outStr.= '<span class="margin-right">'.$value['age'].lang("plugin/tom_love", "sui").'</span>';
                            }
                            if($value['friend']){ $outStr.= '<span class="margin-right">'.lang("plugin/tom_love", "friend").'</span>';}
                            if($value['marriage']){ $outStr.= '<span class="margin-right">'.lang("plugin/tom_love", "marriage").'</span>';}
                        $outStr.= '</div>';
                    /*    $outStr.= '<div class="v-tj">';
                            $outStr.= '<span class="have">'.lang("plugin/tom_love", "havecar").'</span>';
                            $outStr.= '<span class="have">'.lang("plugin/tom_love", "havehours").'</span>';
                            $outStr.= '<span class="have">'.lang("plugin/tom_love", "gaoerfu").'</span>';
                        $outStr.= '</div>'; */
                        $outStr.= '<div class="v-dubai">'.$value['describe'].'</div>';
                    $outStr.= '</a>';
                /*    $outStr.= '<div class="v-chat">';
                        $outStr.= '<a class="anlian" href="javascript:void(0);">'.lang("plugin/tom_love", "anlianTa").'</a>';
                        $outStr.= '<a class="chat" href="javascript:void(0);">'.lang('plugin/tom_love', "liaoyiliao").'</a>';
                    $outStr.= '</div>';*/
                    
                $outStr.= '</div>';
            $outStr.= '</div>';
        }
    }else{
        $outStr = '205';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else{
    echo 'error';exit;
}

