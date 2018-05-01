<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$_G['setting']['switchwidthauto']=0;
$_G['setting']['allowwidthauto']=1;


$page     = isset($_GET['page'])? intval($_GET['page']):1;
$recid     = isset($_GET['recid'])? intval($_GET['recid']):0;

$pagesize = 15;
$start = ($page-1)*$pagesize;

$where = " AND status = 1 AND avatar !='' AND avatar != 'source/plugin/tom_weixin_jy/images/avatar_default.jpg' AND nickname !='' ";

if($recid == 1){
    $where.= " AND recommend = 1 ";
}

if($jyConfig['must_info'] == 1){
    $where.=" AND year>0 ";
}

$userData = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize);
$userDataCount = C::t('#tom_love#tom_love')->fetch_all_count($where);

$userList = array();
if(is_array($userData) && !empty($userData)){
    foreach ($userData as $key => $value){
        $userList[$key] = $value;
        $userList[$key]['describe'] = dhtmlspecialchars($value['describe']);
        if($value['year']> 0 ){
            if($jyConfig['age_type_id'] == 1){
                $userList[$key]['age'] = $nowYear - $value['year'];
            }else{
                $userList[$key]['age'] = $nowYear - $value['year'] + 1;
            }
        }else{
            $userList[$key]['age'] = '';
        }
        $userList[$key]['time'] = dgmdate($value['add_time'], 'Y-m-d',$tomSysOffset);
        $userList[$key]['first_pic'] = $value['avatar'];
        if(!preg_match('/^http:/', $value['avatar'])){
            if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                $userList[$key]['first_pic'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
            }else{
                $userList[$key]['first_pic'] = $value['avatar'];
            }
        }else{
            $userList[$key]['first_pic'] = $value['avatar'];
        }
    }
}

$paging = helper_page :: multi($userDataCount, $pagesize, $page, "plugin.php?id=tom_love:pc&mod=index&recid={$recid}", 0, 11, false, false);

$navtitle = $jyConfig['seo_title'];
$metakeywords =  $jyConfig['seo_keywords'];
$metadescription = $jyConfig['seo_description'];

include template("tom_love:pc/index");

