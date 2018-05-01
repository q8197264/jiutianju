<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';
$page     = isset($_GET['page'])? intval($_GET['page']):1;

$where = '';
$pageType = '';
if($act == 'meanlian'){
    $pageType = 'anlian';
    $where = " AND type_id=2 AND user_id={$__UserInfo['id']} ";
}else if($act == 'anlianme'){
    $pageType = 'anlian';
    $where = " AND type_id=2 AND gx_user_id={$__UserInfo['id']} ";
}

$pagesize = 8;
$start = ($page-1)*$pagesize;

$guanxiData = C::t('#tom_love#tom_love_guanxi')->fetch_all_list($where,"ORDER BY id DESC",$start,$pagesize);
$guanxiDataCount = C::t('#tom_love#tom_love_guanxi')->fetch_all_count($where);

$guanxiList = array();
if(is_array($guanxiData) && !empty($guanxiData)){
    foreach ($guanxiData as $key => $value){
        
        switch ($act){
            case "meanlian":
                $user = C::t('#tom_love#tom_love')->fetch_by_id($value['gx_user_id']);
                break;
            case "anlianme":
                $user = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
                break;
        }
        $guanxiList[$key] = $value;
        $guanxiList[$key]['user'] = $user;
        $guanxiList[$key]['describe'] = dhtmlspecialchars($user['describe']);
        $guanxiList[$key]['age'] = $nowYear - $user['year'] + 1;
        if(!preg_match('/^http:/', $user['avatar'])){
            if(strpos($user['avatar'], 'source/plugin/tom_love') === false){
                $guanxiList[$key]['user']['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$user['avatar'];
            }else{
                $guanxiList[$key]['user']['avatar'] = $user['avatar'];
            }
        }else{
            $guanxiList[$key]['user']['avatar'] = $user['avatar'];
        }
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $guanxiDataCount){
    $showNextPage = 0;
}
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_love&mod=guanxi&act={$act}&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_love&mod=guanxi&act={$act}&page={$nextPage}";

$deleteUrl = "plugin.php?id=tom_love:ajax&act=delanlian&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:guanxi");

