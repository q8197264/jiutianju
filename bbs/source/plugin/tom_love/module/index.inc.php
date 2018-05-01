<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page     = isset($_GET['page'])? intval($_GET['page']):1;


$userRecCheck = C::t('#tom_love#tom_love')->fetch_all_list(" AND recommend = 1 AND recommend_time = 0 ","ORDER BY id DESC",0,100);
if(is_array($userRecCheck) && !empty($userRecCheck)){
    foreach ($userRecCheck as $key => $value){
        $updateData = array();
        $updateData['recommend_time'] = TIMESTAMP + 7*86400;
        $updateData['recommend_do_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($value['id'],$updateData);
    }
}

$pagesize = 8;
$start = ($page-1)*$pagesize;
$nowTime = TIMESTAMP;

if($jyConfig['must_info'] == 1){
    $where = " AND recommend = 1 AND recommend_time > {$nowTime} AND status = 1 AND year>0 ";
}else{
    $where = " AND recommend = 1 AND recommend_time > {$nowTime} AND status = 1 ";
}

$userData = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY recommend_do_time DESC,id DESC",$start,$pagesize);
$userDataCount = C::t('#tom_love#tom_love')->fetch_all_count($where);

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

$showNextPage = 1;
if(($start + $pagesize) >= $userDataCount){
    $showNextPage = 0;
}

$userAllCount = C::t('#tom_love#tom_love')->fetch_all_count(" AND status = 1 ");
$clicksNum = $commonInfo['clicks'] + $jyConfig['virtual_clicks'];
$clicksNumTxt = $clicksNum;
if($clicksNum>10000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,2); 
}else if($clicksNum>1000000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,0);
}
$allPageNum = ceil($userDataCount/$pagesize);

$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_love&mod=index&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_love&mod=index&page={$nextPage}";
$infoUrl = "plugin.php?id=tom_love&mod=info&uid=";

$newWhere = '';
if($jyConfig['must_info'] == 1){
    $newWhere = " AND status = 1 AND year>0 ";
}else{
    $newWhere = " AND status = 1 ";
}
$newUserListTmp = C::t('#tom_love#tom_love')->fetch_all_list($newWhere, 'ORDER BY id DESC', 0, 2);
$newUserList = array();
if(is_array($newUserListTmp) && !empty($newUserListTmp)){
    foreach($newUserListTmp as $key => $value){
        $newUserList[$key] = $value;
        $newUserList[$key]['describe'] = dhtmlspecialchars($value['describe']);
        if($value['year'] > 0){
            if($jyConfig['age_type_id'] == 1){
                $newUserList[$key]['age'] = $nowYear - $value['year'];
            }else{
                $newUserList[$key]['age'] = $nowYear - $value['year'] + 1;
            }
        }else{
            $newUserList[$key]['age'] = '';
        }
        if(!preg_match('/^http:/', $value['avatar'])){
            if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                $newUserList[$key]['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
            }else{
                $newUserList[$key]['avatar'] = $value['avatar'];
            }
        }else{
            $newUserList[$key]['avatar'] = $value['avatar'];
        }
    }
}

$slideData = C::t('#tom_love#tom_love_focuspic')->fetch_all_list("", "ORDER BY fsort ASC,id DESC",0,10);
$slideList = array();
if(is_array($slideData) && !empty($slideData)){
    foreach($slideData as $key => $value){
        $slideList[$key] = $value;
        $slideList[$key]['title'] = dhtmlspecialchars($value['title']);
        if(!preg_match('/^http:/', $value['picurl'])){
            $slideList[$key]['picurl'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['picurl'];
        }else{
            $slideList[$key]['picurl'] = $value['picurl'];
        }
    } 
}

$oldStart = 0;
$sexWhere = '';
if(is_array($__UserInfo) && !empty($__UserInfo)){
    if($__UserInfo['sex'] == 1){
        $sexWhere = ' AND vip_id = 1 AND sex = 2';
    }else if($__UserInfo['sex'] == 2){
        $sexWhere = ' AND vip_id = 1 AND sex = 1';
    }else{
        $sexWhere = ' AND vip_id = 1';
    }
}else{
    $sexWhere = ' AND vip_id = 1';
}
$sexVipCount = C::t('#tom_love#tom_love')->fetch_all_count($sexWhere);
$sexStart = $randStart = $randEnd = 0;
if($sexVipCount){
    $pagesize = 3;
    if($sexVipCount > $pagesize){
        $randEnd = $sexVipCount - $pagesize;
        $sexStart = mt_rand($randStart, $randEnd);
        if( $sexStart == $oldStart ){
            if(($oldStart+1) <=$randEnd){
                $sexStart = $oldStart+1;
            }else if(($oldStart-1) >= 0){
                $sexStart = $oldStart-1;
            }
        }
    }
}

$sexVipListTmp = C::t('#tom_love#tom_love')->fetch_all_list($sexWhere, 'ORDER BY id DESC', $sexStart, 3);
$sexVipList = array();
if(is_array($sexVipListTmp) && !empty($sexVipListTmp)){
    foreach($sexVipListTmp as $key => $value){
        $sexVipList[$key] = $value;
        $sexVipList[$key]['height'] = intval($value['height']);
        if($value['year'] > 0){    
            if($jyConfig['age_type_id'] == 1){
                $sexVipList[$key]['age'] = $nowYear - $value['year'];
            }else{
                $sexVipList[$key]['age'] = $nowYear - $value['year'] + 1;
            }
        }else{
            $sexVipList[$key]['age'] = '';
        }
        if(!preg_match('/^http:/', $value['avatar'])){
            if(strpos($value['avatar'], 'source/plugin/tom_love') === false){
                $sexVipList[$key]['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['avatar'];
            }else{
                $sexVipList[$key]['avatar'] = $value['avatar'];
            }
        }else{
            $sexVipList[$key]['avatar'] = $value['avatar'];
        }
    }
}

$refreshVipUrl = 'plugin.php?id=tom_love:ajax&formhash='.$formhash.'&uid='.$__UserInfo['id'];

$ssData = C::t('#tom_love#tom_love_shuoshuo')->fetch_all_list("","ORDER BY ss_time DESC",0,2);
$ssList = array();
if(is_array($ssData) && !empty($ssData)){
    foreach ($ssData as $key => $value){
        $ssList[$key]         = $value;
        $ssList[$key]['content']    = dhtmlspecialchars($value['content']);
        $user = C::t('#tom_love#tom_love')->fetch_by_id($value['user_id']);
        $ssList[$key]['userinfo'] = $user;
        if(!preg_match('/^http:/', $user['avatar'])){
            if(strpos($user['avatar'], 'source/plugin/tom_love') === false){
                $ssList[$key]['userinfo']['avatar'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$user['avatar'];
            }else{
                $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
            }
        }else{
            $ssList[$key]['userinfo']['avatar'] = $user['avatar'];
        }
    }
}

$recommendLoadMoreUrl = 'plugin.php?id=tom_love:ajax&formhash='.$formhash;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:index");



