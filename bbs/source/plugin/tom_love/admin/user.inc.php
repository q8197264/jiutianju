<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=user';
$modListUrl = $adminListUrl.'&tmod=user';
$modFromUrl = $adminFromUrl.'&tmod=user';

$get_list_url_value = get_list_url("tom_love_admin_user_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
if($formhash == FORMHASH && $act == 'show'){
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    
    $sexName = '';
    if($info['sex'] == 1){
        $sexName = $pluginScriptLang['man'];
    }else{
        $sexName = $pluginScriptLang['woman'];
    }
    $jyTypeOne = "";
    $jyTypeTwo = "";
    if($info['friend'] == 1){
        $jyTypeOne= $pluginScriptLang['jy'];
    }
    if($info['marriage'] == 1){
        $jyTypeTwo= $pluginScriptLang['hl'];
    }
    
    $countryStr = "";
    $provinceStr = "";
    $cityStr = "";
    if($info['country_id'] == 1){
        $countryStr = $pluginScriptLang['china'];
    }
    
    if($info['year'] > 0){
        if($jyConfig['age_type_id'] == 1){
            $age = $nowYear - $info['year'];
        }else{
            $age = $nowYear - $info['year'] + 1;
        }
    }else{
        $age = '-';
    }
    if(!preg_match('/^http:/', $info['avatar'])){
        if(strpos($info['avatar'], 'source/plugin/tom_love') === false){
            $info['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$info['avatar'];
        }else{
            $info['avatar'] = $info['avatar'];
        }
    }
    
    $score = $info['score'];
    
    $picList = C::t('#tom_love#tom_love_pic')->fetch_all_list(" AND user_id ={$info['id']} ","ORDER BY id DESC",0,$jyConfig['user_show_pic_list_length']);
    
    $fenhao = $pluginScriptLang['fenhao'];
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['user_info'] . '</th></tr>';
    echo '<tr><td width="150" align="right"><b>'.$pluginScriptLang['avatar'].$fenhao.'</b></td><td><img src="'.$info['avatar'].'" width="40" height="40" />&nbsp;<a href="'.$modBaseUrl.'&act=delavatar&uid='.$info['id'].'&formhash='.FORMHASH.'"">('.$pluginScriptLang['delete'].')</a></td></tr>';
    echo '<tr><td align="right"><b>UID'.$fenhao.'</b></td><td>' . $info['bbs_uid'] . '</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['bbs_username'].$fenhao.'</b></td><td><a href="home.php?mod=space&uid='.$info['bbs_uid'].'"target="_blank" >' . $info['bbs_username'] . '</a></td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['nickname'].$fenhao.'</b></td><td>'.$info['nickname'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['sex'].$fenhao.'</b></td><td>'.$sexName.'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['age'].$fenhao.'</b></td><td>'.$age.'&nbsp;<a href="'.$modBaseUrl.'&act=edityear&id='.$info['id'].'&formhash='.FORMHASH.'">(' . $pluginScriptLang['edit_year'] . ')</a></td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['marital'].$fenhao.'</b></td><td>'.$maritalArray[$info['marital_id']].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['score'].$fenhao.'</b></td><td>'.$score.'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['wx'].$fenhao.'</b></td><td>'.$info['wx'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['qq'].$fenhao.'</b></td><td>'.$info['qq'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['tel'].$fenhao.'</b></td><td>'.$info['tel'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['jy_type'].$fenhao.'</b></td><td>'.$jyTypeOne.' '.$jyTypeTwo.'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['work'].$fenhao.'</b></td><td>'.$worksArray[$info['work_id']].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['edu'].$fenhao.'</b></td><td>'.$eduArray[$info['edu_id']].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['pay'].$fenhao.'</b></td><td>'.$payArray[$info['pay_id']].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['height'].$fenhao.'</b></td><td>'.$info['height'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['weight'].$fenhao.'</b></td><td>'.$info['weight'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['area'].$fenhao.'</b></td><td>'.$info['area'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['hjarea'].$fenhao.'</b></td><td>'.$info['hjarea'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['describe'].$fenhao.'</b></td><td>'.$info['describe'].'</td></tr>';
    echo '<tr><td align="right"><b>'.$pluginScriptLang['pic_list'].$fenhao.'</b></td><td>';
    if(is_array($picList) && !empty($picList)){
        
        $i = 1;
        foreach ($picList as $key => $value){
            if(!preg_match('/^http:/', $value['pic_url'])){
                if(strpos($value['pic_url'], 'source/plugin/tom_love') === false){
                    $value['pic_url'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['pic_url'];
                }else{
                    $value['pic_url'] = $value['pic_url'];
                }
            }else{
                $value['pic_url'] = $value['pic_url'];
            }
            echo '<img src="'.$value['pic_url'].'" width="80" height="80" />&nbsp;<a href="'.$modBaseUrl.'&act=delpic&uid='.$info['id'].'&picid='.$value['id'].'&formhash='.FORMHASH.'">('.$pluginScriptLang['delete'].')</a>&nbsp;&nbsp;&nbsp;';
            if($i%4 == 0){
                echo "<br/>";
            }
            $i++;
        }
    }
    echo '</td></tr>';
    showtablefooter();
}else if($formhash == FORMHASH && $act == 'editscore'){
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $score = intval($_GET['score']);
        $updateData = array();
        $updateData['score'] = $score;
        C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=editscore&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['edit_score'] . '</th></tr>';
        echo '<tr><td width="100" align="right"><b>'.$pluginScriptLang['nickname'].$fenhao.'</b></td><td>'.$info['nickname'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$pluginScriptLang['edit_score'].$fenhao.'</b></td><td><input name="score" type="text" value="'.$info['score'].'" size="20" /> '.$pluginScriptLang['edit_score_msg'].'</td></tr>';
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'edityear'){
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $year = intval($_GET['year']);
        $updateData = array();
        $updateData['year'] = $year;
        C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=edityear&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['edit_year'] . '</th></tr>';
        echo '<tr><td width="100" align="right"><b>'.$pluginScriptLang['nickname'].$fenhao.'</b></td><td>'.$info['nickname'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$pluginScriptLang['edit_year'].$fenhao.'</b></td><td><input name="year" type="text" value="'.$info['year'].'" size="20" /> '.$pluginScriptLang['edit_year_msg'].'</td></tr>';
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'editvip'){
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $vip_id = intval($_GET['vip_id']);
        $vip_time     = isset($_GET['vip_time'])? addslashes($_GET['vip_time']):'';
        $vip_time     = strtotime($vip_time);
        if($jyConfig['user_editvip_sure'] == 1){
            $updateData = array();
            $updateData['vip_id'] = $vip_id;
            $updateData['vip_time'] = $vip_time;
            C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
        }
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=editvip&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['edit_vip_title'] .'('.$info['nickname']. ')</th></tr>';
        $vip_id_item = array(0=>$pluginScriptLang['edit_vip_id_0'],1=>$pluginScriptLang['edit_vip_id_1']);
        tomshowsetting(array('title'=>$pluginScriptLang['edit_vip_id'],'name'=>'vip_id','value'=>$info['vip_id'],'msg'=>$pluginScriptLang['edit_vip_id_msg'],'item'=>$vip_id_item),"radio");
        tomshowsetting(array('title'=>$pluginScriptLang['edit_vip_time'],'name'=>'vip_time','value'=>$info['vip_time'],'msg'=>$pluginScriptLang['edit_vip_time_msg']),"calendar");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'addtz'){
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
        
        $insertData = array();
        $insertData['user_id']     = $_GET['id'];
        $insertData['type']        = $jyConfig['user_addtz_type_value'];
        $insertData['title']       = $pluginScriptLang['sys_tz_title'];
        $insertData['content']     = $content;
        $insertData['tz_time']     = TIMESTAMP;
        $insertData['is_read']     = $jyConfig['user_addtz_is_read_value'];
        C::t('#tom_love#tom_love_tz')->insert($insertData);
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=addtz&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' .$info['nickname'].$fenhao. $pluginScriptLang['sys_tz_title'] . '</th></tr>';
        tomshowsetting(array('title'=>$pluginScriptLang['sys_tz_title'],'name'=>'content','value'=>'','msg'=>''),"textarea");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'addqunfa'){
    
    $renzheng       = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $recommend      = isset($_GET['recommend'])? intval($_GET['recommend']):0;
    $status         = isset($_GET['status'])? intval($_GET['status']):0;
    $all            = isset($_GET['all'])? intval($_GET['all']):1;
    
    if(submitcheck('submit')){
        $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
        $content = urlencode($content);
        $modQunfaListUrl = $modListUrl.'&act=doqunfa&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&content='.$content.'&formhash='.FORMHASH;
        cpmsg($pluginScriptLang['qunfa_add_msg'], $modQunfaListUrl, 'loadingform');
    }else{
        
        $where = "";
        if(!empty($renzheng)){
            if($renzheng == 1){
                $where.= " AND renzheng=0 ";
            }
            if($renzheng == 2){
                $where.= " AND renzheng=1 ";
            }
        }
        if(!empty($recommend)){
            if($recommend == 1){
                $where.= " AND recommend=0 ";
            }
            if($recommend == 2){
                $where.= " AND recommend=1 ";
            }
        }
        if(!empty($status)){
            $where.= " AND status=$status ";
        }
        if(!empty($all)){
            if($all == 1){
                $where.= " AND year>0 ";
            }
            if($all == 2){
                $where.= " AND year=0 ";
            }
        }
        $count = C::t('#tom_love#tom_love')->fetch_all_count($where,"","");
        
        $qunfa_tz_title2 = str_replace("{NUM}", $count, $pluginScriptLang['qunfa_tz_title2']);
        
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=addqunfa&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' .$qunfa_tz_title2. '</th></tr>';
        tomshowsetting(array('title'=>$pluginScriptLang['sys_tz_title'],'name'=>'content','value'=>'','msg'=>''),"textarea");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'doqunfa'){
    
    $renzheng       = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $recommend      = isset($_GET['recommend'])? intval($_GET['recommend']):0;
    $status         = isset($_GET['status'])? intval($_GET['status']):0;
    $all            = isset($_GET['all'])? intval($_GET['all']):1;
    $page           = isset($_GET['page'])? intval($_GET['page']):1;
    $nextpage = $page + 1;
    
    $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
    $content = urldecode($content);
    
    $pagesize = $jyConfig['user_doqunfa_pagesize_value'];
    $start = ($page-1)*$pagesize;	
    
    $where = "";
    if(!empty($renzheng)){
        if($renzheng == 1){
            $where.= " AND renzheng=0 ";
        }
        if($renzheng == 2){
            $where.= " AND renzheng=1 ";
        }
    }
    if(!empty($recommend)){
        if($recommend == 1){
            $where.= " AND recommend=0 ";
        }
        if($recommend == 2){
            $where.= " AND recommend=1 ";
        }
    }
    if(!empty($status)){
        $where.= " AND status=$status ";
    }
    if(!empty($all)){
        if($all == 1){
            $where.= " AND year>0 ";
        }
        if($all == 2){
            $where.= " AND year=0 ";
        }
    }
    $count = C::t('#tom_love#tom_love')->fetch_all_count($where,"","");
    $allPageNum = ceil($count/$pagesize);
    
    if($page <= $allPageNum){
        
        $userList = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize,"","");
        
        if(is_array($userList) && !empty($userList)){
            foreach ($userList as $key => $value){
                $insertData = array();
                $insertData['user_id']     = $value['id'];
                $insertData['type']        = 1;
                $insertData['title']       = $pluginScriptLang['sys_tz_title'];
                $insertData['content']     = $content;
                $insertData['tz_time']     = TIMESTAMP;
                $insertData['is_read']     = 0;
                C::t('#tom_love#tom_love_tz')->insert($insertData);
            }
        }
        
        $qunfa_do_msg = str_replace("{PAGES}", $page, $pluginScriptLang['qunfa_do_msg']);
        $qunfa_do_msg = str_replace("{COUNT}", $allPageNum, $qunfa_do_msg);
        
        $modQunfaListUrl = $modListUrl.'&act=doqunfa&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&content='.$_GET['content'].'&page='.$nextpage.'&formhash='.FORMHASH;
        cpmsg($qunfa_do_msg, $modQunfaListUrl, 'loadingform');
        
    }else{
        cpmsg($pluginScriptLang['qunfa_do_success'], $modListUrl, 'succeed');
    }
}else if($formhash == FORMHASH && $act == 'addqfmbxx'){
    
    $renzheng       = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $recommend      = isset($_GET['recommend'])? intval($_GET['recommend']):0;
    $status         = isset($_GET['status'])? intval($_GET['status']):0;
    $all            = isset($_GET['all'])? intval($_GET['all']):1;
    
    if(submitcheck('submit')){
        $title    = isset($_GET['title'])? addslashes($_GET['title']):'';
        $content  = isset($_GET['content'])? addslashes($_GET['content']):'';
        $link     = isset($_GET['link'])? addslashes($_GET['link']):'';
        $title    = urlencode($title);
        $content  = urlencode($content);
        $link     = urlencode($link);
        $modQambxxListUrl = $modListUrl.'&'.$jyConfig['user_doqfmbxx_act'].'&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&title='.$title.'&content='.$content.'&link='.$link.'&formhash='.FORMHASH;
        cpmsg($pluginScriptLang['qfmbxx_add_msg'], $modQambxxListUrl, 'loadingform');
    }else{
        
        $title      = isset($_GET['title'])? addslashes($_GET['title']):'';
        $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
        $link       = isset($_GET['link'])? addslashes($_GET['link']):'http://';
        $title      = urldecode($title);
        $content    = urldecode($content);
        $link       = urldecode($link);
        
        $where = "";
        if(!empty($renzheng)){
            if($renzheng == 1){
                $where.= " AND renzheng=0 ";
            }
            if($renzheng == 2){
                $where.= " AND renzheng=1 ";
            }
        }
        if(!empty($recommend)){
            if($recommend == 1){
                $where.= " AND recommend=0 ";
            }
            if($recommend == 2){
                $where.= " AND recommend=1 ";
            }
        }
        if(!empty($status)){
            $where.= " AND status=$status ";
        }
        if(!empty($all)){
            if($all == 1){
                $where.= " AND year>0 ";
            }
            if($all == 2){
                $where.= " AND year=0 ";
            }
        }
        $count = C::t('#tom_love#tom_love')->fetch_all_count($where,"","");
        
        $qfmbxx_tz_title2 = str_replace("{NUM}", $count, $pluginScriptLang['qfmbxx_tz_title2']);
        
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=addqfmbxx&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' .$qfmbxx_tz_title2. '</th></tr>';
        tomshowsetting(array('title'=>$pluginScriptLang['qfmbxx_sms_title'],'name'=>'title','value'=>$title,'msg'=>$pluginScriptLang['qfmbxx_sms_title_msg']),"input");
        tomshowsetting(array('title'=>$pluginScriptLang['qfmbxx_sms_content'],'name'=>'content','value'=>$content,'msg'=>''),"textarea");
        tomshowsetting(array('title'=>$pluginScriptLang['qfmbxx_sms_link'],'name'=>'link','value'=>$link,'msg'=>''),"input");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'doqfmbxx'){
    
    $renzheng       = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $recommend      = isset($_GET['recommend'])? intval($_GET['recommend']):0;
    $status         = isset($_GET['status'])? intval($_GET['status']):0;
    $all            = isset($_GET['all'])? intval($_GET['all']):1;
    $page           = isset($_GET['page'])? intval($_GET['page']):1;
    $nextpage = $page + 1;
    
    $title      = isset($_GET['title'])? addslashes($_GET['title']):'';
    $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
    $link       = isset($_GET['link'])? addslashes($_GET['link']):'';
    $title      = urldecode($title);
    $content    = urldecode($content);
    $link       = urldecode($link);
        
    $pagesize = 20;
    $start = ($page-1)*$pagesize;	
    
    $where = "";
    if(!empty($renzheng)){
        if($renzheng == 1){
            $where.= " AND renzheng=0 ";
        }
        if($renzheng == 2){
            $where.= " AND renzheng=1 ";
        }
    }
    if(!empty($recommend)){
        if($recommend == 1){
            $where.= " AND recommend=0 ";
        }
        if($recommend == 2){
            $where.= " AND recommend=1 ";
        }
    }
    if(!empty($status)){
        $where.= " AND status=$status ";
    }
    if(!empty($all)){
        if($all == 1){
            $where.= " AND year>0 ";
        }
        if($all == 2){
            $where.= " AND year=0 ";
        }
    }
    $count = C::t('#tom_love#tom_love')->fetch_all_count($where,"","");
    $allPageNum = ceil($count/$pagesize);
    
    if($page <= $allPageNum){
        
        $userList = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize,"","");
        
        $appid = trim($jyConfig['love_appid']); 
        $appsecret = trim($jyConfig['love_appsecret']); 
        include DISCUZ_ROOT.'./source/plugin/tom_love/weixin.class.php';
        include DISCUZ_ROOT.'./source/plugin/tom_love/class/templatesms.class.php';
        $weixinClass = new weixinClass($appid,$appsecret);
        $access_token = $weixinClass->get_access_token();
        $templateSmsClass = new templateSms($access_token, "");
        
        if(is_array($userList) && !empty($userList)){
            foreach ($userList as $key => $value){
                if($access_token && !empty($value['openid']) && $jyConfig['user_allow_doqfmbxx']==1){
                    $smsData = array(
                        'first'         => $title,
                        'keyword1'      => '-',
                        'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                        'remark'        => $content
                    );
                    $r = $templateSmsClass->sendSmsTm20702951($value['openid'],$jyConfig['template_tm20702951'],$smsData,$link);
//                    if($r){}else{
//                        $modaddQambxxListUrl = $modListUrl.'&act=addqfmbxx&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&title='.$_GET['title'].'&content='.$_GET['content'].'&link='.$_GET['link'].'&formhash='.FORMHASH;
//                        cpmsg($pluginScriptLang['qfmbxx_do_fail'], $modaddQambxxListUrl, 'succeed');
//                    }
                    
                }
            }
        }
        
        $qfmbxx_do_msg = str_replace("{PAGES}", $page, $pluginScriptLang['qfmbxx_do_msg']);
        $qfmbxx_do_msg = str_replace("{COUNT}", $allPageNum, $qfmbxx_do_msg);
        
        $modQambxxListUrl = $modListUrl.'&'.$jyConfig['user_doqfmbxx_act'].'&renzheng='.$renzheng.'&recommend='.$recommend.'&status='.$status.'&all='.$all.'&title='.$_GET['title'].'&content='.$_GET['content'].'&link='.$_GET['link'].'&page='.$nextpage.'&formhash='.FORMHASH;
        cpmsg($qfmbxx_do_msg, $modQambxxListUrl, 'loadingform');
        
    }else{
        cpmsg($pluginScriptLang['qfmbxx_do_success'], $modListUrl, 'succeed');
    }
}else if($formhash == FORMHASH && $act == 'updateanlians'){
    set_time_limit(0);
    $userList = C::t('#tom_love#tom_love')->fetch_all_list("","ORDER BY add_time DESC",0,10000,"","");
    if(is_array($userList) && !empty($userList)){
        foreach ($userList as $key => $value){
            $anlianCount = C::t('#tom_love#tom_love_guanxi')->fetch_all_count(" AND type_id=2 AND gx_user_id={$value['id']} ");
            $updateData = array();
            $updateData['anlians'] = $anlianCount;
            C::t('#tom_love#tom_love')->update($value['id'],$updateData);
        }
    }
    cpmsg($pluginScriptLang['act_success'],$modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'rzdel'){
    $updateData = array();
    $updateData['renzheng'] = 0;
    C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
    C::t('#tom_love#tom_love_renzheng')->update_renzheng_status(0,$_GET['id']);
    cpmsg($pluginScriptLang['act_success'],$modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'rzadd'){
    $updateData = array();
    $updateData['renzheng'] = 1;
    C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'recdel'){
    $updateData = array();
    $updateData['recommend'] = 0;
    C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'recadd'){
    
    $info = C::t('#tom_love#tom_love')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $recommend_time     = isset($_GET['recommend_time'])? addslashes($_GET['recommend_time']):'';
        $recommend_time     = strtotime($recommend_time);
        $updateData = array();
        $updateData['recommend'] = $jyConfig['user_recadd_recommend_value'];
        $updateData['recommend_time'] = $recommend_time;
        $updateData['recommend_do_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=recadd&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['add_recommend_time'] .'('.$info['nickname']. ')</th></tr>';
        tomshowsetting(array('title'=>$pluginScriptLang['add_recommend_time'],'name'=>'recommend_time','value'=>$info['recommend_time'],'msg'=>$pluginScriptLang['add_recommend_time_msg']),"calendar");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'fenghao'){
    $updateData = array();
    $updateData['status'] = $jyConfig['user_fenghao_value'];
    C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'normal'){
    $updateData = array();
    $updateData['status'] = $jyConfig['user_normal_value'];
    C::t('#tom_love#tom_love')->update($_GET['id'],$updateData);
    cpmsg($pluginScriptLang['act_success'],$modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'del'){
    C::t('#tom_love#tom_love')->delete($_GET['id']);
    C::t('#tom_love#tom_love_shuoshuo')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_guanxi')->delete_by_gid($_GET['id']);
    C::t('#tom_love#tom_love_pic')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_share')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_renzheng')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_rec')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_tz')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_order')->delete_by_uid($_GET['id']);
    C::t('#tom_love#tom_love_report')->delete_by_uid($_GET['id']);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'delavatar'){
    $uid = intval($_GET['uid']);
    $updateData = array();
    $updateData['avatar'] = 'source/plugin/tom_love/images/avatar_default.jpg';
    C::t('#tom_love#tom_love')->update($uid,$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl.'&act=show&id='.$uid.'&formhash='.FORMHASH, 'succeed');
}else if($formhash == FORMHASH && $act == 'delpic'){
    $uid = intval($_GET['uid']);
    $picid = intval($_GET['picid']);
    C::t('#tom_love#tom_love_pic')->delete($picid);
    $pic_num = C::t('#tom_love#tom_love_pic')->fetch_all_count(" AND user_id ={$uid} ");
    $updateData = array();
    $updateData['pic_num'] = $pic_num;
    C::t('#tom_love#tom_love')->update($uid,$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl.'&act=show&id='.$uid.'&formhash='.FORMHASH, 'succeed');
}else if($act == 'listorder'){
    $pagesize = $jyConfig['user_listorder_pagesize_value'];
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_love#tom_love_order')->fetch_all_count(" AND order_status=2 ");
    $orderList = C::t('#tom_love#tom_love_order')->fetch_all_list(" AND order_status=2 ","ORDER BY id DESC",$start,$pagesize);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['order_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $pluginScriptLang['nickname'] . '</th>';
    echo '<th>OPENID</th>';
    echo '<th>' . $pluginScriptLang['order_order_type'] . '</th>';
    echo '<th>' . $pluginScriptLang['order_pay_price'] . '</th>';
    echo '<th>' . $pluginScriptLang['order_pay_time'] . '</th>';
    echo '</tr>';
    foreach ($orderList as $key => $value){
        $pay_time = dgmdate($value['pay_time'], 'Y-m-d H:i:s',$tomSysOffset);
        $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
        echo '<tr>';
        echo '<td><a target="_blank" href="'.$adminBaseUrl.'&tmod=user&act=show&id='.$value['user_id'].'&formhash='.FORMHASH.'">' . $__UserInfo['nickname'] . '</a></td>';
        echo '<td>' . $value['openid'] . '</td>';
        if($value['order_type'] == $jyConfig['user_listorder_order_type1_value']){
            echo '<td>' . $value['score_value']  . $pluginScriptLang['order_score_value'] . '</td>';
        }else if($value['order_type'] == $jyConfig['user_listorder_order_type2_value']){
            echo '<td>' . $value['time_value']  . $pluginScriptLang['order_vip1_value'] . '</td>';
        }
        
        echo '<td>' . $value['pay_price'] . '</td>';
        echo '<td>' . $pay_time . '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl."&act=listorder");	
    showsubmit('', '', '', '', $multi, false);
}else{
    
    set_list_url("tom_love_admin_user_list");
    
    $bbs_username   = !empty($_GET['bbs_username'])? addslashes($_GET['bbs_username']):'';
    $nickname       = !empty($_GET['nickname'])? addslashes($_GET['nickname']):'';
    $vip_id         = isset($_GET['vip_id'])? intval($_GET['vip_id']):0;
    $renzheng       = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $recommend      = isset($_GET['recommend'])? intval($_GET['recommend']):0;
    $status         = isset($_GET['status'])? intval($_GET['status']):0;
    $all            = isset($_GET['all'])? intval($_GET['all']):1;
    
    $pagesize = $jyConfig['user_list_pagesize_value'];
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    
    $where = "";
    if(!empty($vip_id)){
        if($vip_id == 1){
            $where.= " AND vip_id=1 ";
        }
    }
    if(!empty($renzheng)){
        if($renzheng == 1){
            $where.= " AND renzheng=0 ";
        }
        if($renzheng == 2){
            $where.= " AND renzheng=1 ";
        }
    }
    if(!empty($recommend)){
        if($recommend == 1){
            $where.= " AND recommend=0 ";
        }
        if($recommend == 2){
            $where.= " AND recommend=1 ";
        }
    }
    if(!empty($status)){
        $where.= " AND status=$status ";
    }
    if(!empty($all)){
        if($all == 1){
            $where.= " AND year>0 ";
        }
        if($all == 2){
            $where.= " AND year=0 ";
        }
    }
    $count = C::t('#tom_love#tom_love')->fetch_all_count($where,$bbs_username,$nickname);
    $userList = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize,$bbs_username,$nickname);
    
    $modBasePageUrl = $modBaseUrl."&renzheng={$renzheng}&recommend={$recommend}&status={$status}&all={$all}";
    $uSiteUrl = urlencode($_G['siteurl']);
    showtableheader();
    $pluginScriptLang['love_help_1']  = str_replace("{SITEURL}", $_G['siteurl'], $pluginScriptLang['love_help_1']);
    $pluginScriptLang['love_help_2']  = str_replace("{SITEURL}", $_G['siteurl'], $pluginScriptLang['love_help_2']);
    $pluginScriptLang['love_help_3']  = str_replace("{SITEURL}", $_G['siteurl'], $pluginScriptLang['love_help_3']);
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['love_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $pluginScriptLang['love_help_1'] . '</li>';
    echo '<li>' . $pluginScriptLang['love_help_3'] . '</li>';
    echo '<li>' . $pluginScriptLang['love_help_2'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $fenhao = $pluginScriptLang['fenhao'];
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['search_list'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>'.$pluginScriptLang['bbs_username'].$fenhao.'</b></td><td><input name="bbs_username" type="text" value="'.$bbs_username.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>'.$pluginScriptLang['nickname'].$fenhao.'</b></td><td><input name="nickname" type="text" value="'.$nickname.'" size="40" /></td></tr>';
    
    $vip_id_1 = "";
    if($vip_id == 1){ $vip_id_1 = "selected";}
    echo '<tr><td width="100" align="right"><b>' . $pluginScriptLang['edit_vip_id'] . '</b></td><td><select name="vip_id" >';
    echo '<option value="0">'.$pluginScriptLang['edit_vip_id'].'</option>';
    echo '<option value="1" '.$vip_id_1.'>'.$pluginScriptLang['edit_vip_id_1'].'</option>';
    echo '</select></td></tr>';
    
    $renzheng_1 = $renzheng_2 = "";
    if($renzheng == 1){ $renzheng_1 = "selected";}
    if($renzheng == 2){ $renzheng_2 = "selected";}
    echo '<tr><td width="100" align="right"><b>' . $pluginScriptLang['renzheng'] . '</b></td><td><select name="renzheng" >';
    echo '<option value="0">'.$pluginScriptLang['renzheng'].'</option>';
    echo '<option value="1" '.$renzheng_1.'>'.$pluginScriptLang['rz_no'].'</option>';
    echo '<option value="2" '.$renzheng_2.'>'.$pluginScriptLang['rz_yes'].'</option>';
    echo '</select></td></tr>';
    
    $recommend_1 = $recommend_2 = "";
    if($recommend == 1){ $recommend_1 = "selected";}
    if($recommend == 2){ $recommend_2 = "selected";}
    echo '<tr><td width="100" align="right"><b>' . $pluginScriptLang['recommend'] . '</b></td><td><select name="recommend" >';
    echo '<option value="0">'.$pluginScriptLang['recommend'].'</option>';
    echo '<option value="1" '.$recommend_1.'>'.$pluginScriptLang['rec_no'].'</option>';
    echo '<option value="2" '.$recommend_2.'>'.$pluginScriptLang['rec_yes'].'</option>';
    echo '</select></td></tr>';
    
    $status_1 = $status_2 = "";
    if($status == 1){ $status_1 = "selected";}
    if($status == 2){ $status_2 = "selected";}
    echo '<tr><td width="100" align="right"><b>' . $pluginScriptLang['status'] . '</b></td><td><select name="status" >';
    echo '<option value="0">'.$pluginScriptLang['status'].'</option>';
    echo '<option value="1" '.$status_1.'>'.$pluginScriptLang['normal'].'</option>';
    echo '<option value="2" '.$status_2.'>'.$pluginScriptLang['fenghao'].'</option>';
    echo '</select></td></tr>';
    
    $all_1 = $all_2 = "";
    if($all == 1){ $all_1 = "selected";}
    if($all == 2){ $all_2 = "selected";}
    echo '<tr><td width="100" align="right"><b>' . $pluginScriptLang['info_all_user'] . '</b></td><td><select name="all" >';
    echo '<option value="0">'.$pluginScriptLang['info_all_user'].'</option>';
    echo '<option value="1" '.$all_1.'>'.$pluginScriptLang['info_ok_user'].'</option>';
    echo '<option value="2" '.$all_2.'>'.$pluginScriptLang['info_no_user'].'</option>';
    echo '</select></td></tr>';
    
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    tomshownavheader();
    tomshownavli($pluginScriptLang['qunfa_tz_title'],$modBasePageUrl.'&act=addqunfa&formhash='.FORMHASH,false);
    tomshownavli($pluginScriptLang['qfmbxx_tz_title'],$modBasePageUrl.'&act=addqfmbxx&formhash='.FORMHASH,false);
    tomshownavli($pluginScriptLang['update_anlians'],$modBaseUrl.'&act=updateanlians&formhash='.FORMHASH,false);
    tomshownavli($pluginScriptLang['order_list'],$modBaseUrl.'&act=listorder&formhash='.FORMHASH,false);
    tomshownavli($pluginScriptLang['report_list'],$adminBaseUrl.'&tmod=report',false);
    tomshownavfooter();
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['user_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>UID</th>';
    echo '<th>' . $pluginScriptLang['bbs_username'] . '</th>';
    echo '<th>' . $pluginScriptLang['nickname'] . '</th>';
    echo '<th>' . $pluginScriptLang['sex'] . '</th>';
    echo '<th>' . $pluginScriptLang['age'] . '</th>';
    echo '<th>' . $pluginScriptLang['score'] . '</th>';
    echo '<th>' . $pluginScriptLang['jy_type'] . '</th>';
    echo '<th>' . $pluginScriptLang['edit_vip_id'] . '</th>';
    echo '<th>' . $pluginScriptLang['closed'] . '</th>';
    echo '<th>' . $pluginScriptLang['renzheng'] . '</th>';
    echo '<th>' . $pluginScriptLang['recommend'] . '</th>';
    echo '<th>' . $pluginScriptLang['status'] . '</th>';
    echo '<th>' . $pluginScriptLang['add_time'] . '</th>';
    echo '<th>' . $pluginScriptLang['handle'] . '</th>';
    echo '</tr>';
    foreach ($userList as $key => $value){
        $addTime = dgmdate($value['add_time'], 'Y-m-d',$tomSysOffset);
        $edit_vip_idName = "";
        if($value['vip_id'] == 1){
            $edit_vip_idName = '<font color="#009933">'.$pluginScriptLang['edit_vip_id_1'].'</font>';
        }else{
            $edit_vip_idName = $pluginScriptLang['edit_vip_id_0'];
        }
        $closeName = "";
        if($value['closed'] == 1){
            $closeName = '<font color="#FF0000">'.$pluginScriptLang['close'].'</font>';
        }else{
            $closeName = '<font color="#009933">'.$pluginScriptLang['open'].'</font>';
        }
        $sexName = '';
        if($value['sex'] == 1){
            $sexName = $pluginScriptLang['man'];
        }else{
            $sexName = $pluginScriptLang['woman'];
        }
        $jyTypeOne = "";
        $jyTypeTwo = "";
        if($value['friend'] == 1){
            $jyTypeOne= $pluginScriptLang['jy'];
        }
        if($value['marriage'] == 1){
            $jyTypeTwo= $pluginScriptLang['hl'];
        }
        
        if($value['year'] > 0){
            if($jyConfig['age_type_id'] == 1){
                $age = $nowYear - $value['year'];
            }else{
                $age = $nowYear - $value['year'] + 1;
            }
        }else{
            $age = '-';
        }
        
        echo '<tr>';
        echo '<td>' . $value['bbs_uid'] . '</td>';
        echo '<td> <a href="home.php?mod=space&uid='.$value['bbs_uid'].'"target="_blank" >' . $value['bbs_username'] . '</a></td>';
        if(!empty($value['openid'])){
            echo '<td>' . $value['nickname'] . '<img src="source/plugin/tom_love/images/openid_ico.png"/></td>';
        }else{
           echo '<td>' . $value['nickname'] . '</td>'; 
        }
        echo '<td>' . $sexName . '</td>';
        echo '<td>' . $age . '</td>';
        echo '<td>' . $value['score'] . '</td>';
        echo '<td>' . $jyTypeOne." ".$jyTypeTwo . '</td>';
        echo '<td>' . $edit_vip_idName . '</td>';
        echo '<td>' . $closeName . '</td>';
        if($value['renzheng'] == 1){
            echo '<td><font color="#009933">' . $pluginScriptLang['rz_yes'] . '</font>(<a href="'.$modBaseUrl.'&act=rzdel&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rz_del'] . '</a>)</td>';
        }else{
            echo '<td><font color="#FF0000">' . $pluginScriptLang['rz_no'] . '</font>(<a href="'.$modBaseUrl.'&act=rzadd&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rz_add'] . '</a>)</td>';
        }
        if($value['recommend'] == 1){
            echo '<td><font color="#009933">' . $pluginScriptLang['rec_yes'] . '</font>(<a href="'.$modBaseUrl.'&act=recadd&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rec_add'] . '</a>)(<a href="'.$modBaseUrl.'&act=recdel&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rec_del'] . '</a>)</td>';
        }else{
            echo '<td><font color="#FF0000">' . $pluginScriptLang['rec_no'] . '</font>(<a href="'.$modBaseUrl.'&act=recadd&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rec_add'] . '</a>)</td>';
        }
        if($value['status'] == 1){
            echo '<td><font color="#009933">' . $pluginScriptLang['normal'] . '</font>(<a href="'.$modBaseUrl.'&act=fenghao&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['fenghao'] . '</a>)</td>';
        }else{
            echo '<td><font color="#FF0000">' . $pluginScriptLang['fenghao'] . '</font>(<a href="'.$modBaseUrl.'&act=normal&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['normal'] . '</a>)</td>';
        }
        echo '<td>' . $addTime . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=show&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['show'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=editscore&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['edit_score'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $pluginScriptLang['delete'] . '</a><br/>';
        echo '<a href="'.$modBaseUrl.'&act=editvip&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['edit_vip_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=addtz&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['sys_tz_title'] . '</a>';
        //echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['delete'] . '</a>';
        
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$pluginScriptLang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;
}

