<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$act = isset($_GET['act'])? trim($_GET['act']):'';

if($__UserInfo['year'] > 0){    
    if($jyConfig['age_type_id'] == 1){
        $age = $nowYear - $__UserInfo['year'];
    }else{
        $age = $nowYear - $__UserInfo['year'] + 1;
    }
}else{
    $age = '';
}

$infoBox = 0;
$myBox = 0;
if($act == 'info'){
    $infoBox = 1;
    
    $provinceList = $hjprovinceList = C::t('#tom_love#tom_love_district')->fetch_all_by_level(1);
    $cityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($__UserInfo['province_id']);
    $areaList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($__UserInfo['city_id']);
    $hjcityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($__UserInfo['hjprovince_id']);
    $hjareaList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($__UserInfo['hjcity_id']);
    $yearArray = array();
    $startYear = $nowYear - 60;
    $endYear = $nowYear - 15;
    for($i=$startYear;$i<=$endYear;$i++){
        $yearArray[] = $i;
    }
    
}else if($act == 'saveInfo' && $_GET['formhash'] == FORMHASH){
    
    if('utf-8' != CHARSET) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && $_G['setting']['mobile']['allowmobile'] == 1 && $_G['setting']['mobile']['mobileforward'] == 1){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $friend = isset($_GET['friend'])? intval($_GET['friend']):0;
    $marriage = isset($_GET['marriage'])? intval($_GET['marriage']):0;
    $nickname = isset($_GET['nickname'])? daddslashes($_GET['nickname']):'';
    $sex = isset($_GET['sex'])? intval($_GET['sex']):0;
    $year = isset($_GET['year'])? intval($_GET['year']):0;
    $country = isset($_GET['country'])? intval($_GET['country']):0;
    $province = isset($_GET['province'])? intval($_GET['province']):0;
    $city = isset($_GET['city'])? intval($_GET['city']):0;
    $area = isset($_GET['area'])? intval($_GET['area']):0;
    
    $hjcountry = isset($_GET['hjcountry'])? intval($_GET['hjcountry']):0;
    $hjprovince = isset($_GET['hjprovince'])? intval($_GET['hjprovince']):0;
    $hjcity = isset($_GET['hjcity'])? intval($_GET['hjcity']):0;
    $hjarea = isset($_GET['hjarea'])? intval($_GET['hjarea']):0;
    
    $job = isset($_GET['job'])? intval($_GET['job']):0;
    $height = isset($_GET['height'])? intval($_GET['height']):0;
    $weight = isset($_GET['weight'])? intval($_GET['weight']):0;
    $edu = isset($_GET['edu'])? intval($_GET['edu']):0;
    $pay = isset($_GET['pay'])? intval($_GET['pay']):0;
    $marital = isset($_GET['marital'])? intval($_GET['marital']):0;
    $wx = isset($_GET['wx'])? daddslashes($_GET['wx']):0;
    $qq = isset($_GET['qq'])? daddslashes($_GET['qq']):0;
    $tel = isset($_GET['tel'])? daddslashes($_GET['tel']):'';
    $describe = isset($_GET['describe'])? daddslashes($_GET['describe']):'';
    $mate_demands = isset($_GET['mate_demands'])? daddslashes($_GET['mate_demands']):'';
    
    if(C::t('#tom_love#tom_love')->check_nickname($__UserInfo['id'],$nickname)){
        echo '201';exit;
    }
    
    $provinceStr = "";
    $cityStr = "";
    $areaStr = "";
    $provinceInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($province);
    $cityInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($city);
    $areaInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($area);
    if($provinceInfo){
        $provinceStr = $provinceInfo['name'];
    }
    if($cityInfo){
        $cityStr = $cityInfo['name'];
    }
    if($areaInfo){
        $areaStr = $areaInfo['name'];
    }
    
    $hjprovinceStr = "";
    $hjcityStr = "";
    $hjareaStr = "";
    $hjprovinceInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($hjprovince);
    $hjcityInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($hjcity);
    $hjareaInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($hjarea);
    if($hjprovinceInfo){
        $hjprovinceStr = $hjprovinceInfo['name'];
    }
    if($hjcityInfo){
        $hjcityStr = $hjcityInfo['name'];
    }
    if($hjareaInfo){
        $hjareaStr = $hjareaInfo['name'];
    }
    
    $updateData = array();
    
    if(empty($__UserInfo['describe']) && $jyConfig['zl_reward_score'] > 0){
        $updateData['score'] = $__UserInfo['score'] + $jyConfig['zl_reward_score'];
        
        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['zl_reward_score'];
        $insertData['log_type'] = 4;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
        
    }
    
    $updateData['friend'] = $friend;
    $updateData['marriage'] = $marriage;
    $updateData['nickname'] = $nickname;
    $updateData['sex'] = $sex;
    $updateData['year'] = $year;
    $updateData['country_id'] = $country;
    $updateData['province_id'] = $province;
    $updateData['city_id'] = $city;
    $updateData['area_id'] = $area;
    $updateData['hjcountry_id'] = $hjcountry;
    $updateData['hjprovince_id'] = $hjprovince;
    $updateData['hjcity_id'] = $hjcity;
    $updateData['hjarea_id'] = $hjarea;
    $updateData['work_id'] = $job;
    $updateData['height'] = $height;
    $updateData['weight'] = $weight;
    $updateData['edu_id'] = $edu;
    $updateData['pay_id'] = $pay;
    $updateData['marital_id'] = $marital;
    $updateData['describe'] = $describe;
    $updateData['mate_demands'] = $mate_demands;
    $updateData['wx'] = $wx;
    $updateData['qq'] = $qq;
    $updateData['tel'] = $tel;
    $updateData['area'] = $provinceStr." ".$cityStr." ".$areaStr;
    $updateData['hjarea'] = $hjprovinceStr." ".$hjcityStr." ".$hjareaStr;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
    echo '1';exit;
}else if($act == 'close' && $_GET['formhash'] == FORMHASH){
    $close = isset($_GET['close'])? intval($_GET['close']):0;
    $updateData = array();
    $updateData['closed'] = $close;
    C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);
    echo '1';exit;
}else if($act == 'sign' && $_GET['formhash'] == FORMHASH){
    
    $isTodaySignCount = C::t('#tom_love#tom_love_sign')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND time_key = {$nowTime} ");
    if($isTodaySignCount > 0 ){}else{
        $updateData = array();
        $updateData['score'] = $__UserInfo['score'] + $jyConfig['sign_score'];
        $updateData['sign_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love')->update($__UserInfo['id'],$updateData);

        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['time_key'] = $nowTime;
        $insertData['sign_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_sign')->insert($insertData);

        $insertData = array();
        $insertData['user_id'] = $__UserInfo['id'];
        $insertData['score_value'] = $jyConfig['sign_score'];
        $insertData['log_type'] = 5;
        $insertData['log_time'] = TIMESTAMP;
        C::t('#tom_love#tom_love_scorelog')->insert($insertData);
    }
    
    echo '1';exit;
}else{
    $myBox = 1;
    
    $recStatus = 0;
    $recInfo = C::t('#tom_love#tom_love_rec')->fetch_by_uid($__UserInfo['id']);
    if($recInfo && $recInfo['rec_status'] == 1 && $__UserInfo['recommend'] == 0){
        $recStatus = 1;
    }
    if($__UserInfo['recommend_time'] > 0){
        $recommendTime = dgmdate($__UserInfo['recommend_time'], 'Y-m-d',$tomSysOffset);
    }else{
        $recommendTime = ' - - ';
    }
    
    $renzhengStatus = 0;
    $renzhengInfo = C::t('#tom_love#tom_love_renzheng')->fetch_by_uid($__UserInfo['id']);
    if($__UserInfo['renzheng'] == 1){
        $renzhengStatus = 1;
    }else if($renzhengInfo && $renzhengInfo['renzheng_status'] == 1 && $__UserInfo['renzheng'] == 0){
        $renzhengStatus = 2;
    }
    
    $meanlianCount = C::t('#tom_love#tom_love_guanxi')->fetch_all_count(" AND type_id=2 AND user_id={$__UserInfo['id']} ");
    $anlianmeCount = C::t('#tom_love#tom_love_guanxi')->fetch_all_count(" AND type_id=2 AND gx_user_id={$__UserInfo['id']} ");
    
    $signCount = C::t('#tom_love#tom_love_sign')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND sign_time > {$nowMonth} ");
    
    $isTodaySignCount = C::t('#tom_love#tom_love_sign')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND time_key = {$nowTime} ");
    
    
    $showWanshanBtn = 0;
    if(empty($__UserInfo['describe']) && $jyConfig['zl_reward_score'] > 0){
        $showWanshanBtn = 1;
    }
    
}

$recUrl = "plugin.php?id=tom_love&mod=my&uid={$__UserInfo['id']}&act=rec&formhash=".FORMHASH;
$closeUrl = "plugin.php?id=tom_love&mod=my&uid={$__UserInfo['id']}&act=close&formhash=".FORMHASH;
$signUrl = "plugin.php?id=tom_love&mod=my&uid={$__UserInfo['id']}&act=sign&formhash=".FORMHASH;
$saveUrl = "plugin.php?id=tom_love&mod=my";
$backUrl = "plugin.php?id=tom_love&mod=my";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:my");


function wx_iconv_recurrence($value) {
    if(is_array($value)) {
        foreach($value AS $key => $val) {
            $value[$key] = wx_iconv_recurrence($val);
        }
    } else {
        $value = diconv($value, 'utf-8', CHARSET);
    }
    return $value;
}

