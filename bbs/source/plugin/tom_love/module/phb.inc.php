<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$type     = isset($_GET['type'])? intval($_GET['type']):1;

$phb_num = 10;
if($jyConfig['phb_num'] > 0){
    $phb_num = $jyConfig['phb_num'];
}

if($jyConfig['must_info'] == 1){
    $where = " AND status = 1 AND year>0 ";
}else{
    $where = " AND status = 1 ";
}

$order = "ORDER BY add_time DESC";
if($type == 1){
    $order = "ORDER BY flowers DESC,add_time DESC";
}else if($type == 2){
    $order = "ORDER BY anlians DESC,add_time DESC";
}

$userData = C::t('#tom_love#tom_love')->fetch_all_list($where,$order,0,$phb_num);

$userList = array();
if(is_array($userData) && !empty($userData)){
    foreach ($userData as $key => $value){
        $userList[$key] = $value;
        $userList[$key]['describe'] = dhtmlspecialchars($value['describe']);
        if($value['year'] > 0){    
            if($jyConfig['age_type_id'] == 1){
                $userList[$key]['age'] = $nowYear - $value['year'];
            }else{
                $userList[$key]['age'] = $nowYear - $value['year'] + 1;
            }
        }else{
            $userList[$key]['age'] = '';
        }
        if(!preg_match('/^http:/', $value['avatar'])){
            if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                $userList[$key]['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
            }else{
                $userList[$key]['avatar'] = $value['avatar'];
            }
        }else{
            $userList[$key]['avatar'] = $value['avatar'];
        }
    }
}


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:phb");

