<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=attr&model_id='.$_GET['model_id'].'&type_id='.$_GET['type_id'];
$modListUrl = $adminListUrl.'&tmod=type&act=edit&model_id='.$_GET['model_id'].'&id='.$_GET['type_id'];
$modFromUrl = $adminFromUrl.'&tmod=attr&model_id='.$_GET['model_id'].'&type_id='.$_GET['type_id'];

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_model_attr')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','encattr');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $attrInfo = C::t('#tom_tongcheng#tom_tongcheng_model_attr')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($attrInfo);
        C::t('#tom_tongcheng#tom_tongcheng_model_attr')->update($attrInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'encattr');
        showtableheader();
        __create_info_html($attrInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_model_attr')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $type       = isset($_GET['type'])? intval($_GET['type']):1;
    $value        = isset($_GET['value'])? addslashes($_GET['value']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):100;
    
    $data['model_id']   = $_GET['model_id'];
    $data['type_id']   = $_GET['type_id'];
    $data['name']       = $name;
    $data['type']       = $type;
    $data['value']       = $value;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'           => '',
        'type'           => 1,
        'value'           => '',
        'paixu'          => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['attr_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['attr_name_msg']),"input");
    $type_item = array(1=>$Lang['attr_type1'],2=>$Lang['attr_type2'],3=>$Lang['attr_type3']);
    tomshowsetting(true,array('title'=>$Lang['attr_type'],'name'=>'type','value'=>$options['type'],'msg'=>$Lang['attr_type_msg'],'item'=>$type_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['attr_value'],'name'=>'value','value'=>$options['value'],'msg'=>$Lang['attr_value_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['attr_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['attr_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl,$modListUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['attr_add'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['attr_edit'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }
    tomshownavfooter();
}


