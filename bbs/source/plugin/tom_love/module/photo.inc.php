<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

if($act == 'del' && $_GET['formhash'] == FORMHASH){
    $picid = intval($_GET['picid']);
    C::t('#tom_love#tom_love_pic')->delete($picid);
    
    $pic_num = C::t('#tom_love#tom_love_pic')->fetch_all_count(" AND user_id ={$__UserInfo['id']} ");
    $updateData = array();
    $updateData['pic_num'] = $pic_num;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
}

$picListTmp = C::t('#tom_love#tom_love_pic')->fetch_all_list(" AND user_id ={$__UserInfo['id']} ","ORDER BY id DESC",0,100);
$picList = array();
if(is_array($picListTmp) && !empty($picListTmp)){
    foreach($picListTmp as $key => $value){
        $picList[$key] = $value;
        if(!preg_match('/^http:/', $value['pic_url'])){
            if(strpos($value['pic_url'], 'source/plugin/tom_love') === false){
                $picList[$key]['pic_url'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$value['pic_url'];
            }else{
                $picList[$key]['pic_url'] = $value['pic_url'];
            }
        }else{
            $picList[$key]['pic_url'] = $value['pic_url'];
        }
    }
}

$delUrl = "plugin.php?id=tom_love&mod=photo&act=del&formhash=".FORMHASH."&picid=";
$uploadUrl = "plugin.php?id=tom_love&mod=upload&act=photo&formhash=".FORMHASH;

$allow_upload_num = $jyConfig['pic_num'] - $__UserInfo['pic_num'];
if($allow_upload_num < 1){
    $allow_upload_num = 0;
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:photo");

