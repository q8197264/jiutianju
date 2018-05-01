<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=rec'; 
$modListUrl = $adminListUrl.'&tmod=rec';
$modFromUrl = $adminFromUrl.'&tmod=rec';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
if($formhash == FORMHASH && $act == 'ok'){
    $updateData = array();
    $updateData['recommend'] = 1;
    C::t('#tom_love#tom_love')->update($_GET['uid'],$updateData);
    $updateData = array();
    $updateData['rec_status'] = 2;
    C::t('#tom_love#tom_love_rec')->update($_GET['id'],$updateData);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else if($formhash == FORMHASH && $act == 'no'){
    if(submitcheck('submit')){
        $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
        $uid          = isset($_GET['uid'])? intval($_GET['uid']):0;
        
        $updateData = array();
        $updateData['recommend'] = 0;
        C::t('#tom_love#tom_love')->update($_GET['uid'],$updateData);
        $updateData = array();
        $updateData['rec_status'] = 3;
        C::t('#tom_love#tom_love_rec')->update($_GET['id'],$updateData);
        
        $insertData = array();
        $insertData['user_id']     = $uid;
        $insertData['type']        = 1;
        $insertData['title']       = $pluginScriptLang['rec_status3_title'];
        $insertData['content']     = $content;
        $insertData['tz_time']     = TIMESTAMP;
        $insertData['is_read']     = 0;
        C::t('#tom_love#tom_love_tz')->insert($insertData);
        cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
    }else{
        $fenhao = $pluginScriptLang['fenhao'];
        showformheader($modFromUrl.'&act=no&id='.$_GET['id'].'&uid='.$_GET['uid'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['rec_status3_title'] . '</th></tr>';
        tomshowsetting(array('title'=>$pluginScriptLang['rec_status3_msg'],'name'=>'content','value'=>'','msg'=>''),"textarea");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
    
}else{
    $pagesize = 10;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_love#tom_love_rec')->fetch_all_count(" ");
    $recList = C::t('#tom_love#tom_love_rec')->fetch_all_list(" ","ORDER BY rec_time DESC",$start,$pagesize);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['rec_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $pluginScriptLang['nickname'] . '</th>';
    echo '<th>' . $pluginScriptLang['rec_status'] . '</th>';
    echo '<th>' . $pluginScriptLang['rec_time'] . '</th>';
    echo '<th>' . $pluginScriptLang['handle'] . '</th>';
    echo '</tr>';
    foreach ($recList as $key => $value){
        $recTime = dgmdate($value['rec_time'], 'Y-m-d H:i:s',$tomSysOffset);
        $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
        
        $rec_name = '';
        if($value['rec_status'] == 1){
            $rec_name = $pluginScriptLang['rec_status1'];
        }else if($value['rec_status'] == 2){
            $rec_name = $pluginScriptLang['rec_status2'];
        }else if($value['rec_status'] == 3){
            $rec_name = $pluginScriptLang['rec_status3'];
        }
        
        echo '<tr>';
        echo '<td><a target="_blank" href="'.$adminBaseUrl.'&tmod=user&act=show&id='.$value['user_id'].'&formhash='.FORMHASH.'">' . $__UserInfo['nickname'] . '</a></td>';
        echo '<td>' . $rec_name . '</td>';
        echo '<td>' . $recTime . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=ok&uid='.$value['user_id'].'&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rec_ok'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=no&uid='.$value['user_id'].'&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['rec_no'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

