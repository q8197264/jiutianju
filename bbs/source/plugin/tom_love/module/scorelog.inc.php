<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page     = isset($_GET['page'])? intval($_GET['page']):1;

$pagesize = 50;
$start = ($page-1)*$pagesize;

$count = C::t('#tom_love#tom_love_guanxi')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$scorelogData = C::t('#tom_love#tom_love_scorelog')->fetch_all_list(" AND user_id={$__UserInfo['id']} ","ORDER BY log_time DESC",$start,$pagesize);
$scorelogList = array();
if(is_array($scorelogData) && !empty($scorelogData)){
    foreach ($scorelogData as $logkey => $logvalue){
        $scorelogList[$logkey] = $logvalue;
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_love&mod=scorelog&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_love&mod=scorelog&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:scorelog");

