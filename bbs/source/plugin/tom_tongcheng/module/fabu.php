<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if($_GET['act'] == 'step2'){
    
    $type_id = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($typeInfo['model_id']);
    $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$type_id}  "," ORDER BY paixu ASC,id DESC ",0,50);
    $tagList = C::t('#tom_tongcheng#tom_tongcheng_model_tag')->fetch_all_list(" AND type_id={$type_id} "," ORDER BY paixu ASC,id DESC ",0,50);
    $attrListTmp = C::t('#tom_tongcheng#tom_tongcheng_model_attr')->fetch_all_list(" AND type_id={$type_id} "," ORDER BY paixu ASC,id DESC ",0,50);
    
    $attrList = array();
    if(is_array($attrListTmp) && !empty($attrListTmp)){
        foreach ($attrListTmp as $key => $value){
            $attrList[$key] = $value;
            if($value['type'] == 2){
                $value_listStr = str_replace("\r\n","{n}",$value['value']); 
                $value_listStr = str_replace("\n","{n}",$value_listStr);
                $attrList[$key]['list'] = explode("{n}", $value_listStr);
            }
        }
    }
    
    $fabuPayStatus = 0;
    if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0 && $__UserInfo['editor']==0){
        $fabuPayStatus = 1;
    }
    
    $minDateTime = TIMESTAMP-60;
    $minDateTime = dgmdate($minDateTime,"Y-m-d H:i:s",$tomSysOffset);
    $maxDateTime = TIMESTAMP + 365*86400;
    $maxDateTime = dgmdate($maxDateTime,"Y-m-d H:i:s",$tomSysOffset);
    
    $areaList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($__CityInfo['id']);
    $cityList = array();
    $i = 0;
    if(is_array($areaList) && !empty($areaList)){
        foreach ($areaList as $key => $value){
            $cityList[$i]['id'] = $value['id'];
            $cityList[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
            $streetListTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($value['id']);
            $j = 0;
            if(is_array($streetListTmp) && !empty($streetListTmp)){
                foreach ($streetListTmp as $kk => $vv){
                    $cityList[$i]['sub'][$j]['id'] = $vv['id'];
                    $cityList[$i]['sub'][$j]['name'] = diconv($vv['name'],CHARSET,'utf-8');
                    $j++;
                }
            }
            $i++;
        }
    }
    $cityData = urlencode(json_encode($cityList));
    
    
    $picYasuSize = 640;
    if($tongchengConfig['pic_yasu_size'] > 100){
        $picYasuSize = $tongchengConfig['pic_yasu_size'];
    }
    
    $showPhoneDialog = 0;
    if($tongchengConfig['fabu_must_phone']==1 && $__UserInfo['editor']==0){
        if(empty($__UserInfo['tel'])){
            $showPhoneDialog = 1;
        }
    }
    
    $defaultXm = '';
    $defaultTel = '';
    $lastTongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND user_id={$__UserInfo['id']} "," ORDER BY id DESC ",0,1);
    if($lastTongchengListTmp && $lastTongchengListTmp[0]['add_time'] > 0){
        $defaultXm =  $lastTongchengListTmp[0]['xm'];
        $defaultTel =  $lastTongchengListTmp[0]['tel'];
    }
    
    $uploadUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=upload&act=photo&formhash=".FORMHASH;
    $payUrl = "plugin.php?id=tom_tongcheng:pay&site={$site_id}";
    $shareLinkUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id=";
    $buyLinkUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=buy&tongcheng_id=";
    $myListUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist&type=2";
    $myListAllUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:fabu_step2");  
    
}else{
    
    $model_id = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
    
    if(!empty($model_id)){
        $typeListTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$model_id} "," ORDER BY paixu ASC,id DESC ",0,20);
        if(count($typeListTmp) == 1){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&act=step2&type_id=".$typeListTmp[0]['id']);exit;
        }
    }
    
    $modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,50);
    
    $modelList = array();
    if(is_array($modelListTmp) && !empty($modelListTmp)){
        foreach ($modelListTmp as $key => $value){
            $modelList[$key] = $value;
            if(!preg_match('/^http/', $value['picurl']) ){
                if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                    $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
                }else{
                    $picurl = $value['picurl'];
                }
            }else{
                $picurl = $value['picurl'];
            }
            $modelList[$key]['picurl'] = $picurl;
            
            $typeListTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$value['id']} "," ORDER BY paixu ASC,id DESC ",0,20);
            $modelList[$key]['showType'] = 0;
            if(count($typeListTmp) > 1){
                $modelList[$key]['showType'] = 1;
            }
            $modelList[$key]['typeList'] = $typeListTmp;
        }
    }
    
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:fabu_step1");  
    
}





