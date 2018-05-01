<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=user'; 
$modListUrl = $adminListUrl.'&tmod=user';
$modFromUrl = $adminFromUrl.'&tmod=user';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

if($formhash == FORMHASH && $act == 'info'){
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'ok'){
    
    $updateData = array();
    $updateData['status']     = 1;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'fenhao'){
    
    $updateData = array();
    $updateData['status']     = 2;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'add_editor'){
    
    $updateData = array();
    $updateData['editor']     = 1;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del_editor'){
    
    $updateData = array();
    $updateData['editor']     = 0;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    $user_id = !empty($_GET['user_id'])? addslashes($_GET['user_id']):0;
    $nickname = !empty($_GET['nickname'])? addslashes($_GET['nickname']):'';
    $editor = !empty($_GET['editor'])? intval($_GET['editor']):0;
    
    $where = "";
    if(!empty($user_id)){
        $where = " AND id=$user_id ";
    }
    if(!empty($editor)){
        $where = " AND editor=1 ";
    }
    
    $pagesize = 10;
    if(!empty($nickname)){
		$pagesize = 100;
	}
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_like_count($where,$nickname);
    $userList = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_like_list($where,"ORDER BY add_time DESC",$start,$pagesize,$nickname);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['user_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li><font color="#fd0d0d">' . $Lang['user_help_1'] . '</font></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['user_search_list'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['user_id'] . '</b></td><td><input name="user_id" type="text" value="'.$user_id.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['user_nickname'] . '</b></td><td><input name="nickname" type="text" value="'.$nickname.'" size="40" /></td></tr>';
    
    $editorStr = '<tr><td width="100" align="right"><b>'.$Lang['user_editor'].'</b></td>';
    $editorStr.= '<td><select style="width: 260px;" name="editor" id="editor">';
    $editorStr.=  '<option value="0">'.$Lang['user_editor'].'</option>';
    $editorStr.=  '<option value="1">'.$Lang['user_editor_1'].'</option>';
    $editorStr.= '</select></td></tr>';
    echo $editorStr;
    
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['user_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['user_id'] . '</th>';
    echo '<th>' . $Lang['user_picurl'] . '</th>';
    echo '<th>' . $Lang['user_nickname'] . '</th>';
    echo '<th>' . $Lang['user_tel'] . '</th>';
    echo '<th>' . $Lang['user_openid'] . '</th>';
    echo '<th>' . $Lang['user_num'] . '</th>';
    echo '<th>' . $Lang['user_editor'] . '</th>';
    echo '<th>' . $Lang['user_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($userList as $key => $value){
        $modelUrl = $adminBaseUrl.'&tmod=index';
        $userCountTmp          = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND user_id={$value['id']} ");
        echo '<tr>';
        echo '<td>'.$value['id'].'</td>';
        echo '<td><img src="'.$value['picurl'].'" width="40" /></td>';
        echo '<td>'.$value['nickname'].'</td>';
        echo '<td>'.$value['tel'].'</td>';
        echo '<td>'.$value['openid'].'</td>';
        echo '<td><font color="#f00">' . $userCountTmp . '</font>&nbsp;<a href="'.$modelUrl.'&user_id='.$value['id'].'"><font color="#928c8c">(' . $Lang['user_num_chakan'] . ')</font></a></td>';
        if($value['editor'] == 1){
            echo '<td><font color="#0a9409">'.$Lang['user_editor_1'].'</font></td>';
        }else{
            echo '<td><font color="#f00">'.$Lang['user_editor_0'].'</font></td>';
        }
        if($value['status'] == 1){
            echo '<td><font color="#0a9409">'.$Lang['user_status_1'].'</font></td>';
        }else{
            echo '<td><font color="#f00">'.$Lang['user_status_2'].'</font></td>';
        }
        echo '<td>';
        if($value['editor'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=del_editor&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_editor_btn_0']. '</a>&nbsp;|&nbsp;';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=add_editor&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_editor_btn_1']. '</a>&nbsp;|&nbsp;';
        }
        if($value['status'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=fenhao&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_status_2']. '</a>';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=ok&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_status_1']. '</a>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

