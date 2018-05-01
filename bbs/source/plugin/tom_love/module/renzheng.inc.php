<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

if($act == 'save' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 200,
    );
    
    $id = isset($_GET['rid'])? intval($_GET['rid']):'';
    $xm = isset($_GET['xm'])? daddslashes(diconv(urldecode($_GET['xm']),'utf-8')):'';
    $tel = isset($_GET['tel'])? daddslashes($_GET['tel']):'';
    $sfzh = isset($_GET['sfzh'])? daddslashes($_GET['sfzh']):'';
    $content = isset($_GET['content'])? daddslashes(diconv(urldecode($_GET['content']),'utf-8')):'';
    $pic_z = isset($_GET['pic_z'])? daddslashes($_GET['pic_z']):'';
    $pic_f = isset($_GET['pic_f'])? daddslashes($_GET['pic_f']):'';
    
    if($__UserInfo['vip_id'] == 0){
        if(!empty($jyConfig['renzheng_score']) && $jyConfig['renzheng_score'] > $__UserInfo['score']){
            $outArr['status'] = 101;
            echo json_encode($outArr); exit;
        }
    }
    
    $updateData = array();
    $updateData['xm'] = $xm;
    $updateData['tel'] = $tel;
    $updateData['sfzh'] = $sfzh;
    $updateData['content'] = $content;
    $updateData['pic_z'] = $pic_z;
    $updateData['pic_f'] = $pic_f;
    $updateData['renzheng_time'] = TIMESTAMP;
    $updateData['renzheng_status'] = 1;
    C::t('#tom_love#tom_love_renzheng')->update($id,$updateData);
    
    if($__UserInfo['vip_id'] == 0){
        $updateData = array();
        $updateData['score'] = $__UserInfo['score']-$jyConfig['renzheng_score'];
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData); 
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['renzheng_score'];
        $insertData['log_type'] = 6;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }
    
    
    echo json_encode($outArr); exit;
}else{
    
    $renzhengInfo = C::t('#tom_love#tom_love_renzheng')->fetch_by_uid($__UserInfo['id']);
    if(!$renzhengInfo){
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['pic_z'] = "source/plugin/tom_love/images/pic_z.png";
        $insertData['pic_f'] = "source/plugin/tom_love/images/pic_f.png";
        C::t('#tom_love#tom_love_renzheng')->insert($insertData);
        $renzhengInfo = C::t('#tom_love#tom_love_renzheng')->fetch_by_uid($__UserInfo['id']);
    }else{
        if(!preg_match('/^http:/', $renzhengInfo['pic_z'])){
            if(strpos($renzhengInfo['pic_z'], 'source/plugin/tom_love') === false){
                $renzhengInfo['pic_z'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$renzhengInfo['pic_z'];
            }else{
                $renzhengInfo['pic_z'] = $renzhengInfo['pic_z'];
            }
        }else{
            $renzhengInfo['pic_z'] = $renzhengInfo['pic_z'];
        }
        if(!preg_match('/^http:/', $renzhengInfo['pic_f'])){
            if(strpos($renzhengInfo['pic_f'], 'source/plugin/tom_love') === false){
                $renzhengInfo['pic_f'] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'common/'.$renzhengInfo['pic_f'];
            }else{
                $renzhengInfo['pic_f'] = $renzhengInfo['pic_f'];
            }
        }else{
            $renzhengInfo['pic_f'] = $renzhengInfo['pic_f'];
        }
    }
    
    require_once libfile('function/discuzcode');
    $renzhengString = discuzcode($jyConfig['renzheng'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

    $uploadUrl1 = "plugin.php?id=tom_love&mod=upload&act=renzheng1&formhash=".FORMHASH;
    $uploadUrl2 = "plugin.php?id=tom_love&mod=upload&act=renzheng2&formhash=".FORMHASH;
    
    $renzhengUrl = "plugin.php?id=tom_love&mod=renzheng";
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:renzheng");
