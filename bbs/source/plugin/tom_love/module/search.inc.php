<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? trim($_GET['act']):'';

$searchBox = 0;
$listBox = 0;
if($act == 'list'){
    $listBox = 1;
    
    $nickname = isset($_GET['nickname'])? daddslashes(diconv(urldecode($_GET['nickname']),'utf-8')):'';
    $renzheng = isset($_GET['renzheng'])? intval($_GET['renzheng']):0;
    $sex = isset($_GET['sex'])? intval($_GET['sex']):0;
    $type = isset($_GET['type'])? intval($_GET['type']):0;
    $marital = isset($_GET['marital'])? intval($_GET['marital']):0;
    $country = isset($_GET['country'])? intval($_GET['country']):0;
    $province = isset($_GET['province'])? intval($_GET['province']):0;
    $city = isset($_GET['city'])? intval($_GET['city']):0;
    $area = isset($_GET['area'])? intval($_GET['area']):0;
    
    $hjcountry = isset($_GET['hjcountry'])? intval($_GET['hjcountry']):0;
    $hjprovince = isset($_GET['hjprovince'])? intval($_GET['hjprovince']):0;
    $hjcity = isset($_GET['hjcity'])? intval($_GET['hjcity']):0;
    $hjarea = isset($_GET['hjarea'])? intval($_GET['hjarea']):0;
    
    $job = isset($_GET['job'])? intval($_GET['job']):0;
    $age = isset($_GET['age'])? intval($_GET['age']):0;
    
    $page     = isset($_GET['page'])? intval($_GET['page']):1;
    $pagesize = 8;
    $start = ($page-1)*$pagesize;
    
    $where = " AND nickname !='' AND status = 1 ";
    if(!empty($nickname)){
        $where = " AND status = 1 ";
    }
    
    $urlParam ="&nickname={$_GET['nickname']}";
    
    if($jyConfig['must_info'] == 1){
        $where.=" AND year>0 ";
    }
    
    if($renzheng == 1){
        $where.=" AND renzheng = 1 ";
    }else if($renzheng == 2){
        $where.=" AND renzheng = 0 ";
    }
    $urlParam.="&renzheng={$renzheng}";
    
    if($sex == 1){
        $where.=" AND sex = 1 ";
    }else if($sex == 2){
        $where.=" AND sex = 2 ";
    }
    $urlParam.="&sex={$sex}";
    
    if($type == 1){
        $where.=" AND friend = 1 ";
    }else if($type == 2){
        $where.=" AND marriage = 1 ";
    }
    $urlParam.="&type={$type}";
    
    if($marital >= 1){
        $where.=" AND marital_id = {$marital} ";
    }
    $urlParam.="&marital={$marital}";
    
    if(!empty($country)){
        $where.=" AND country_id = {$country} ";
    }
    $urlParam.="&country={$country}";
    
    if(!empty($province)){
        $where.=" AND province_id = {$province} ";
    }
    $urlParam.="&province={$province}";
    
    if(!empty($city)){
        $where.=" AND city_id = {$city} ";
    }
    $urlParam.="&city={$city}";
    
    if(!empty($area)){
        $where.=" AND area_id = {$area} ";
    }
    $urlParam.="&area={$area}";
    
    if(!empty($hjcountry)){
        $where.=" AND hjcountry_id = {$hjcountry} ";
    }
    $urlParam.="&hjcountry={$hjcountry}";
    
    if(!empty($hjprovince)){
        $where.=" AND hjprovince_id = {$hjprovince} ";
    }
    $urlParam.="&hjprovince={$hjprovince}";
    
    if(!empty($hjcity)){
        $where.=" AND hjcity_id = {$hjcity} ";
    }
    $urlParam.="&hjcity={$hjcity}";
    
    if(!empty($hjarea)){
        $where.=" AND hjarea_id = {$hjarea} ";
    }
    $urlParam.="&hjarea={$hjarea}";
    
    if(!empty($job)){
        $where.=" AND work_id = {$job} ";
    }
    $urlParam.="&job={$job}";
    
    if(!empty($age)){
        if($age == 1){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 23;
                $endYear = $nowYear - 18;
            }else{
                $startYear = $nowYear - 23 + 1;
                $endYear = $nowYear - 18 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 2){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 27;
                $endYear = $nowYear - 24;
            }else{
                $startYear = $nowYear - 27 + 1;
                $endYear = $nowYear - 24 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 3){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 30;
                $endYear = $nowYear - 28;
            }else{
                $startYear = $nowYear - 30 + 1;
                $endYear = $nowYear - 28 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 4){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 34;
                $endYear = $nowYear - 31;
            }else{
                $startYear = $nowYear - 34 + 1;
                $endYear = $nowYear - 31 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 5){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 39;
                $endYear = $nowYear - 35;
            }else{
                $startYear = $nowYear - 39 + 1;
                $endYear = $nowYear - 35 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 6){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 45;
                $endYear = $nowYear - 40;
            }else{
                $startYear = $nowYear - 45 + 1;
                $endYear = $nowYear - 40 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 7){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 55;
                $endYear = $nowYear - 45;
            }else{
                $startYear = $nowYear - 55 + 1;
                $endYear = $nowYear - 45 + 1;
            }
            $where.=" AND year >= {$startYear} AND year <= {$endYear}";
        }else if($age == 8){
            if($jyConfig['age_type_id'] == 1){
                $endYear = $nowYear - 55;
            }else{
                $endYear = $nowYear - 55 + 1;
            }
            $where.=" AND year <= {$endYear} ";
        }else if($age == 9){
            if($jyConfig['age_type_id'] == 1){
                $startYear = $nowYear - 18;
            }else{
                $startYear = $nowYear - 18 + 1;
            }
            $where.=" AND year >= {$startYear} ";
        }
    }
    $urlParam.="&age={$age}";
    
    $userData = C::t('#tom_love#tom_love')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize,'',$nickname);
    $userDataCount = C::t('#tom_love#tom_love')->fetch_all_count($where,'',$nickname);
    
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
    $allPageNum = ceil($userDataCount/$pagesize);
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $nextPageUrl = "plugin.php?id=tom_love&mod=search&act=list{$urlParam}&page={$nextPage}";
    $prePageUrl  = "plugin.php?id=tom_love&mod=search&act=list{$urlParam}&page={$prePage}";
}else{
    $searchBox = 1;
    
    $provinceId = 0;
    $cityId = 0;
    $areaId = 0;
    $provinceList = C::t('#tom_love#tom_love_district')->fetch_all_by_level(1);
    $cityList = array();
    $areaList = array();
    if(isset($jyConfig['search_district']) && !empty($jyConfig['search_district'])){
        $setDistrict = C::t('#tom_love#tom_love_district')->fetch_by_level_name($jyConfig['search_district']);
        if($setDistrict){
            if($setDistrict['level']==1){
                $provinceId = $setDistrict['id'];
                $cityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($provinceId);
            }else if($setDistrict['level']==2){
                $provinceId = $setDistrict['upid'];
                $cityId = $setDistrict['id'];
                $cityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($provinceId);
                $areaList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($cityId);
            }else if($setDistrict['level']==3){
                $cityInfo = C::t('#tom_love#tom_love_district')->fetch_by_id($setDistrict['upid']);
                $provinceId = $cityInfo['upid'];
                $cityId = $setDistrict['upid'];
                $areaId = $setDistrict['id'];
                $cityList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($provinceId);
                $areaList = C::t('#tom_love#tom_love_district')->fetch_all_by_upid($cityId);
            }
        }
    }
    
    $hjprovinceList = C::t('#tom_love#tom_love_district')->fetch_all_by_level(1);
    
}

$listUrl = "plugin.php?id=tom_love&mod=search&act=list&";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:search");
