<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=price&kj_id='.$_GET['kj_id']; 
$modListUrl = $adminListUrl.'&tmod=price&kj_id='.$_GET['kj_id'];
$modFromUrl = $adminFromUrl.'&tmod=price&kj_id='.$_GET['kj_id'];

if($_GET['act'] == 'add'){
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    if(submitcheck('submit')){
        
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia_price')->insert($insertData);
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
    $priceInfo = C::t('#tom_kanjia#tom_kanjia_price')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($priceInfo);
        C::t('#tom_kanjia#tom_kanjia_price')->update($priceInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($priceInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    C::t('#tom_kanjia#tom_kanjia_price')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{

    $kj_id = $_GET['kj_id'];
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['kj_id']);
    $pagesize = 15;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_kanjia#tom_kanjia_price')->fetch_all_count(" AND kj_id={$kj_id} ");
    $priceList = C::t('#tom_kanjia#tom_kanjia_price')->fetch_all_list(" AND kj_id={$kj_id} ","ORDER BY add_time DESC",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th width="10%">ID</th>';
    echo '<th>' . $Lang['start_end_price'] . '</th>';
    echo '<th>' . $Lang['min_max_price'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($priceList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['start_price']. ' - '.$value['end_price'] . '</td>';
        echo '<td>' . $value['min_price'].' - '.$value['max_price'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['price_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&kj_id='.$kj_id.'&formhash='.FORMHASH.'">' . $Lang['delete'] . '</a>';
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
    
    $start_price          = isset($_GET['start_price'])? addslashes($_GET['start_price']):'';
    $end_price        = isset($_GET['end_price'])? addslashes($_GET['end_price']):'';
    $min_price    = isset($_GET['min_price'])? addslashes($_GET['min_price']):'';
    $max_price    = isset($_GET['max_price'])? addslashes($_GET['max_price']):'';
    
    $data['kj_id']      = $_GET['kj_id'];
    $data['start_price']         = $start_price;
    $data['end_price']          = $end_price;
    $data['min_price']         = $min_price;
    $data['max_price']          = $max_price;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'start_price'          => '',
        'end_price'           => "",
        'min_price'          => "",
        'max_price'           => "",
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(array('title'=>$Lang['start_price'],'name'=>'start_price','value'=>$options['start_price'],'msg'=>$Lang['start_price_msg']),"input");
    tomshowsetting(array('title'=>$Lang['end_price'],'name'=>'end_price','value'=>$options['end_price'],'msg'=>$Lang['end_price_msg']),"input");
    tomshowsetting(array('title'=>$Lang['min_price'],'name'=>'min_price','value'=>$options['min_price'],'msg'=>$Lang['min_price_msg']),"input");
    tomshowsetting(array('title'=>$Lang['max_price'],'name'=>'max_price','value'=>$options['max_price'],'msg'=>$Lang['max_price_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['price_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['price_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['price_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['price_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['price_edit'],"",true);
    }else if($_GET['act'] == 'photo'){
        tomshownavli($Lang['price_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['price_photo'],"",true);
    }else{
        tomshownavli($Lang['price_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['price_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
