<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

$pagesize = 8;

$start = ($page-1)*$pagesize;	
$count = C::t('#tom_kanjia#tom_kanjia')->fetch_all_count("");
$kanjiaListTmp = C::t('#tom_kanjia#tom_kanjia')->fetch_all_list("","ORDER BY paixu ASC , add_time DESC ",$start,$pagesize);

$kanjiaList = array();
if(is_array($kanjiaListTmp) && !empty($kanjiaListTmp)){
    foreach ($kanjiaListTmp as $key => $value){
        $kanjiaList[$key] = $value;
        
        $kanjiaList[$key]['doing'] = 1;
        if(TIMESTAMP < $value['start_time']){
            $kanjiaList[$key]['doing'] = 2;
        }
        if(TIMESTAMP > $value['end_time']){
            $kanjiaList[$key]['doing'] = 3;
        }
        if($kanjiaList[$key]['doing'] == 1 || $kanjiaList[$key]['doing'] == 2){
            $shengyuTimes = $value['end_time'] - TIMESTAMP;
            $kanjiaList[$key]['shengyu_days'] = ceil($shengyuTimes/86400);
        }
        
        if(!preg_match('/^http:/', $value['pic_url']) ){
            $pic_url = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['pic_url'];
        }else{
            $pic_url = $value['pic_url'];
        }
        $kanjiaList[$key]['pic_url'] = $pic_url;
        
        if($value['user_count'] == 0){
            $userCount = C::t('#tom_kanjia#tom_kanjia_user')->fetch_all_count(" AND kj_id={$value['id']} ");
            $updateData = array();
            $updateData['user_count'] = $userCount;
            C::t('#tom_kanjia#tom_kanjia')->update($value['id'],$updateData);
            $kanjiaList[$key]['user_count'] = $userCount;
        }
        $kanjiaList[$key]['rand'] = mt_rand(111111, 999999);
        
    }
}
$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_kanjia&mod=list&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_kanjia&mod=list&page={$nextPage}";

$userAllCount = C::t('#tom_kanjia#tom_kanjia')->fetch_all_sun_user_count(" ");
$clicksAllCount = C::t('#tom_kanjia#tom_kanjia')->fetch_all_sun_clicks(" ");

$showGuanzuBox = 0;
if(isset($_GET['from']) && !empty($_GET['from'])){
    $showGuanzuBox = 1;
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && $kanjiaConfig['open_in_wx'] == 1) {
    include template("tom_kanjia:weixin"); 
}else{
    if($kanjiaConfig['list_template_id'] == 2){
        include template("tom_kanjia:list/hong_list");  
    }else if($kanjiaConfig['list_template_id'] == 3){
        include template("tom_kanjia:list/lan_list");  
    }else if($kanjiaConfig['list_template_id'] == 4){
        include template("tom_kanjia:list/rou_list");  
    }else{
        include template("tom_kanjia:list/bai_list");  
    }
    
}
?>
