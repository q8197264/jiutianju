<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=user&kj_id='.$_GET['kj_id']; 
$modListUrl = $adminListUrl.'&tmod=user&kj_id='.$_GET['kj_id'];
$modFromUrl = $adminFromUrl.'&tmod=user&kj_id='.$_GET['kj_id'];

$doDaoUrl = $_G['siteurl'].'plugin.php?id=tom_kanjia:doDao&kj_id='.$_GET['kj_id'];

if($_GET['act'] == 'add'){
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    if(submitcheck('submit')){
        
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_user')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    $userInfo = C::t('#tom_kanjia#tom_kanjia_user')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($userInfo);
        C::t('#tom_kanjia#tom_kanjia_user')->update($userInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($userInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'delete'){
    C::t('#tom_kanjia#tom_kanjia_user')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'fenhao'){
    $updateData = array();
    $updateData['status'] = 1;
    C::t('#tom_kanjia#tom_kanjia_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'recover'){
    $updateData = array();
    $updateData['status'] = 0;
    C::t('#tom_kanjia#tom_kanjia_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'duihuan'){
    $updateData = array();
    $updateData['dh_status'] = 1;
    C::t('#tom_kanjia#tom_kanjia_user')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    $user_id  = isset($_GET['user_id'])? intval($_GET['user_id']):'';
    $user_tel  = isset($_GET['user_tel'])? daddslashes($_GET['user_tel']):'';
    
    $kj_id = $_GET['kj_id'];
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    $pagesize = 15;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;
    
    $whereStr = " AND kj_id={$kj_id} ";
    if(!empty($user_tel)){
        $whereStr.= " AND tel='{$user_tel}' ";
    }
    if(!empty($user_id)){
        $whereStr.= " AND id='{$user_id}' ";
    }
    
    $count = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" {$whereStr} ");
    $userList = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_list(" {$whereStr} ","ORDER BY add_time DESC",$start,$pagesize);
    __create_nav_html();
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><td width="100" align="right"><b>UID</b></td><td><input name="user_id" type="text" value="'.$user_id.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>'.$Lang['search_user_tel'].'</b></td><td><input name="user_tel" type="text" value="'.$user_tel.'" size="40" /></td></tr>';
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    showtableheader();
    echo '<tr class="header">';
    echo '<th width="10%">uid</th>';
    echo '<th>' . $Lang['user_name'] . '</th>';
    echo '<th>' . $Lang['user_tel'] . '</th>';
    echo '<th>' . $Lang['user_price'] . '</th>';
    echo '<th>' . $Lang['status'] . '</th>';
    echo '<th>' . $Lang['dh_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($userList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $value['tel'] . '</td>';
        echo '<td>' . $value['price'] . '</td>';
        if($value['status'] == 0){
            echo '<td>' . $Lang['status_normal'] . '</td>';
        }else{
            echo '<td>' . $Lang['status_fenhao'] . '</td>';
        }
        if($value['dh_status'] == 0){
            echo '<td>' . $Lang['dh_status_no'] . '</td>';
        }else{
            echo '<td>' . $Lang['dh_status_ok'] . '</td>';
        }
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=log&user_id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['log_list_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['user_edit']. '</a>&nbsp;|&nbsp;';
        if($value['status'] == 0){
            echo '<a href="'.$modBaseUrl.'&act=fenhao&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">--' . $Lang['fenhao'] . '--</a>&nbsp;|&nbsp;';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=recover&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">--' . $Lang['recover'] . '--</a>&nbsp;|&nbsp;';
        }
        echo '<a href="'.$modBaseUrl.'&act=duihuan&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['duihuan'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=delete&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name          = isset($_GET['name'])? addslashes($_GET['name']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $price        = isset($_GET['price'])? addslashes($_GET['price']):'';
    
    $data['kj_id']      = $_GET['kj_id'];
    $data['name']         = $name;
    $data['tel']          = $tel;
    $data['price']          = $price;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'          => '',
        'tel'           => "",
        'price'         => "0.00",
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(array('title'=>$Lang['user_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['user_name_msg']),"input");
    tomshowsetting(array('title'=>$Lang['user_tel'],'name'=>'tel','value'=>$options['tel'],'msg'=>$Lang['user_tel_msg']),"input");
    tomshowsetting(array('title'=>$Lang['user_price'],'name'=>'price','value'=>$options['price'],'msg'=>$Lang['user_price_msg']),"input");
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl,$doDaoUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['user_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['user_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['user_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['user_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['user_edit'],"",true);
    }else{
        tomshownavli($Lang['user_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['user_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['user_dao'],$doDaoUrl,false);
    }
    tomshownavfooter();
}

?>
