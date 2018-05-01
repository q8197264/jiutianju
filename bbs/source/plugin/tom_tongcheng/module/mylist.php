<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($__UserInfo['editor']==1){
    $tongchengConfig['free_refresh_times'] = 9999;
}

$page  = intval($_GET['page'])>0? intval($_GET['page']):1;
$type  = intval($_GET['type'])>0? intval($_GET['type']):0;

$where = " AND user_id={$__UserInfo['id']} ";
$order = " ORDER BY refresh_time DESC,id DESC ";
if($type == 1){
    $where.= " AND status=1 ";
}
if($type == 2){
    $where.= " AND pay_status=1 ";
}
if($type == 3){
    $where.= " AND finish=1 ";
}
if($type == 4){
    $where.= " AND (shenhe_status=2 OR shenhe_status=3) AND (pay_status=0 OR pay_status=2) ";
    $order = " ORDER BY shenhe_status ASC,id DESC ";
}

$pagesize = 8;
$start = ($page - 1)*$pagesize;

$count = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" {$where} ");
$tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" {$where} "," {$order} ",$start,$pagesize);

$tongchengList = array();
foreach ($tongchengListTmp as $key => $value) {
    $tongchengList[$key] = $value;
    $tongchengList[$key]['content'] = contentFormat($value['content']);

    $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']); 
    $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);

    $tongchengAttrListTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
    $tongchengTagListTmp = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
    $tongchengPhotoListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
    $tongchengPhotoListTmp = array();
    if(is_array($tongchengPhotoListTmpTmp) && !empty($tongchengPhotoListTmpTmp)){
        foreach ($tongchengPhotoListTmpTmp as $kk => $vv){
            if(!preg_match('/^http/', $value['picurl']) ){
                $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
            }else{
                $picurl = $vv['picurl'];
            }
            $tongchengPhotoListTmp[$kk] = $picurl;
        }
    }
    $tongchengList[$key]['userInfo'] = $userInfoTmp;
    $tongchengList[$key]['typeInfo'] = $typeInfoTmp;
    $tongchengList[$key]['attrList'] = $tongchengAttrListTmp;
    $tongchengList[$key]['tagList'] = $tongchengTagListTmp;
    $tongchengList[$key]['photoList'] = $tongchengPhotoListTmp;
    
    $payRefreshStatus = 0;
    $shengyuRefreshTimes = 0;
    if($tongchengConfig['free_refresh_times'] > 0){
        $refresh_log_count = C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->fetch_all_count(" AND tongcheng_id={$value['id']} AND time_key={$nowDayTime} ");
        if($tongchengConfig['free_refresh_times'] > $refresh_log_count){
            $shengyuRefreshTimes = $tongchengConfig['free_refresh_times'] - $refresh_log_count;
        }else{
            $payRefreshStatus = 1;
        }
    }else{
        $payRefreshStatus = 1;
    }
    
    $tongchengList[$key]['payRefreshStatus'] = $payRefreshStatus;
    $tongchengList[$key]['shengyuRefreshTimes'] = $shengyuRefreshTimes;
    
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist&type={$type}&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist&type={$type}&page={$nextPage}";


$ajaxUpdateStatusUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=updateStatus&&formhash=".$formhash;
$ajaxFinishStatusUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=updateFinish&&formhash=".$formhash;
$ajaxrefreshUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=refresh&&formhash=".$formhash;
$payUrl = "plugin.php?id=tom_tongcheng:pay&site={$site_id}&act=pay&formhash=".$formhash;
$payRefreshUrl = "plugin.php?id=tom_tongcheng:pay&site={$site_id}&act=refresh&formhash=".$formhash;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:mylist");  




