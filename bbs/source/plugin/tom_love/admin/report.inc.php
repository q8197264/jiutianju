<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=report';
$modListUrl = $adminListUrl.'&tmod=report';
$modFromUrl = $adminFromUrl.'&tmod=report';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
if($formhash == FORMHASH && $act == 'del'){
    C::t('#tom_love#tom_love_report')->delete($_GET['id']);
    cpmsg($pluginScriptLang['act_success'], $modListUrl, 'succeed');
}else{
    $pagesize = 10;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_love#tom_love_report')->fetch_all_count("");
    $reportList = C::t('#tom_love#tom_love_report')->fetch_all_list("","ORDER BY report_time DESC",$start,$pagesize);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $pluginScriptLang['report_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>ID</th>';
    echo '<th>' . $pluginScriptLang['re_user_id'] . '</th>';
    echo '<th>' . $pluginScriptLang['report_user_id'] . '</th>';
    echo '<th>' . $pluginScriptLang['report_content'] . '</th>';
    echo '<th>' . $pluginScriptLang['report_time'] . '</th>';
    echo '<th>' . $pluginScriptLang['handle'] . '</th>';
    echo '</tr>';
    foreach ($reportList as $key => $value){
        $reportTime = dgmdate($value['report_time'], 'Y-m-d H:i:s',$tomSysOffset);
        $__UserInfo = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
        $reportUserInfo = C::t('#tom_love#tom_love')->fetch_by_id($value['report_user_id']);
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td><a target="_blank" href="'.$adminBaseUrl.'&tmod=user&act=show&id='.$value['user_id'].'&formhash='.FORMHASH.'">' . $__UserInfo['nickname'] . '</a></td>';
        echo '<td><a target="_blank" href="'.$adminBaseUrl.'&tmod=user&act=show&id='.$value['report_user_id'].'&formhash='.FORMHASH.'">' . $reportUserInfo['nickname'] . '</a></td>';
        echo '<td>' . $value['report_content'] . '</td>';
        echo '<td>' . $reportTime . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $pluginScriptLang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

