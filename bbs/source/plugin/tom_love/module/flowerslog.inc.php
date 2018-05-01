<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pagesize = 15;
$start = ($page-1)*$pagesize;
$flowerslogListTmp = C::t('#tom_love#tom_love_flowers_log')->fetch_all_list(" AND user_id = {$__UserInfo['id']}", " ORDER BY id DESC", $start, $pagesize);
$flowerslogCount = C::t('#tom_love#tom_love_flowers_log')->fetch_all_count("AND user_id = {$__UserInfo['id']}");
$flowerslogList = array();
if(is_array($flowerslogListTmp) && !empty($flowerslogListTmp)){
    foreach($flowerslogListTmp as $key => $value){
        $flowerslogList[$key] = $value;
        $flowerslogList[$key]['txt'] = htmlspecialchars_decode($value['txt']);
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $flowerslogCount){
    $showNextPage = 0;
}
$total = ceil($shopGoodsCount/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_love&mod=flowerslog&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_love&mod=flowerslog&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:flowerslog");

