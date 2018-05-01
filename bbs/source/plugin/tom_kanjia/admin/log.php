<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=log&kj_id='.$_GET['kj_id'].'&user_id='.$_GET['user_id']; 
$modListUrl = $adminListUrl.'&tmod=log&kj_id='.$_GET['kj_id'].'&user_id='.$_GET['user_id']; 
$modFromUrl = $adminFromUrl.'&tmod=log&kj_id='.$_GET['kj_id'].'&user_id='.$_GET['user_id']; 

if($_GET['act'] == 'add'){
}else{
    $kj_id = $_GET['kj_id'];
    $user_id = $_GET['user_id'];
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($_GET['user_id']);
    $pagesize = 15;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_count(" AND kj_id={$kj_id} AND user_id={$user_id} ");
    $userList = C::t('#tom_kanjia#tom_kanjia_log')->fetch_all_list(" AND kj_id={$kj_id} AND user_id={$user_id} ","ORDER BY add_time DESC",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['log_name'] . '</th>';
    echo '<th>' . $Lang['log_price'] . '</th>';
    echo '<th>' . $Lang['log_price_after'] . '</th>';
    echo '<th>IP</th>';
    echo '<th>' . $Lang['log_time'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($userList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $value['price'] . '</td>';
        echo '<td>' . $value['price_after'] . '</td>';
        echo '<td>' . long2ip($value['ip']) . '</td>';
        echo '<td>' . dgmdate($value['add_time'],"Y-m-d H:i",$tomSysOffset) . '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
    }else{
        tomshownavli($Lang['log_list_title'],$modBaseUrl,true);
    }
    tomshownavfooter();
}

?>
