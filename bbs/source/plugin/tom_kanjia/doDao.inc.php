<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$tomSysOffset = getglobal('setting/timeoffset');

$kanjiaConfig = $_G['cache']['plugin']['tom_kanjia'];
$kj_id = isset($_GET['kj_id'])? intval($_GET['kj_id']):0;

$kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($kj_id);

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    
    $userListTmp = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_list(" AND kj_id={$kj_id} AND status=0 ","ORDER BY price ASC, kanjia_time ASC, id DESC ",0,10000);
    $userList = array();
    foreach ($userListTmp as $key => $value) {
        $userList[$key]['user_name'] = $value['name'];
        $userList[$key]['user_tel'] = $value['tel'];
        $userList[$key]['user_price'] = $value['price'];
        if($value['dh_status'] == 1){
            $userList[$key]['dh_status'] = lang('plugin/tom_kanjia','dh_status_ok2');
        }else{
            $userList[$key]['dh_status'] = lang('plugin/tom_kanjia','dh_status_no2');
        }
    }

    $user_name = lang('plugin/tom_kanjia','user_name');
    $user_tel = lang('plugin/tom_kanjia','user_tel');
    $user_price = lang('plugin/tom_kanjia','user_price');
    $dh_status = lang('plugin/tom_kanjia','dh_status');

    $listData[] = array($user_name,$user_tel,$user_price,$dh_status); 
    $i = 1;
    foreach ($userList as $v){
        $lineData = array();
        $lineData[] = $v['user_name'];
        $lineData[] = $v['user_tel'];
        $lineData[] = $v['user_price'];
        $lineData[] = $v['dh_status'];
        $listData[] = $lineData;
        $i++;
    }
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition:filename=exportKanjia.xls");

    foreach ($listData as $fields){
        foreach ($fields as $k=> $v){
            $str = @diconv("$v",CHARSET,"GB2312");
            echo $str ."\t";
        }
        echo "\n";
    }
    exit;
}else{
    exit('Access Denied');
}

?>
