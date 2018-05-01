<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

session_start();
loaducenter();
$formhash = FORMHASH;
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowYear = dgmdate($_G['timestamp'], 'Y',$tomSysOffset);
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;

$__UserInfo = array();
$userStatus = false;
$cookieOpenid = getcookie('tom_tongcheng_user_openid');
if(!empty($cookieOpenid)){
    $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($cookieOpenid);
    if($__UserInfo && !empty($__UserInfo['openid'])){
        $userStatus = true;
    }
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

if($_GET['act'] == 'list' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $model_id = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
    $type_id  = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    $cate_id  = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
    $user_id  = intval($_GET['user_id'])>0? intval($_GET['user_id']):0;
    $city_id  = intval($_GET['city_id'])>0? intval($_GET['city_id']):0;
    $area_id  = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;
    $street_id  = intval($_GET['street_id'])>0? intval($_GET['street_id']):0;
    $keyword = isset($_GET['keyword'])? daddslashes(diconv(urldecode($_GET['keyword']),'utf-8')):'';
    $page  = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize  = intval($_GET['pagesize'])>0? intval($_GET['pagesize']):8;
    $ordertype  = !empty($_GET['ordertype'])? addslashes($_GET['ordertype']):'new';
    
    if($site_id > 1){
        $sitesInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);
        if($sitesInfoTmp && $sitesInfoTmp['city_id']>0){
            $city_id = $sitesInfoTmp['city_id'];
        }
    }

    $whereStr = ' AND status=1 AND shenhe_status=1 ';
    if(!empty($model_id)){
        $whereStr.= " AND model_id={$model_id} ";
    }
    if(!empty($type_id)){
        $whereStr.= " AND type_id={$type_id} ";
    }
    if(!empty($cate_id)){
        $whereStr.= " AND cate_id={$cate_id} ";
    }
    if(!empty($user_id)){
        $whereStr.= " AND user_id={$user_id} ";
    }
    if(!empty($city_id)){
        $whereStr.= " AND city_id={$city_id} ";
    }
    if(!empty($area_id)){
        $whereStr.= " AND area_id={$area_id} ";
    }
    if(!empty($street_id)){
        $whereStr.= " AND street_id={$street_id} ";
    }
    
    $orderStr = " ORDER BY refresh_time DESC,id DESC ";
    $showTop = 0;
    if(!empty($model_id) || !empty($type_id) || !empty($cate_id) || !empty($keyword)){
        $showTop = 1;
        $orderStr = " ORDER BY topstatus DESC, refresh_time DESC,id DESC ";
    }
    if($tongchengConfig['new_show_top'] == 1){
        $showTop = 1;
        $orderStr = " ORDER BY topstatus DESC, refresh_time DESC,id DESC ";
    }
    
    $pagesize = $pagesize;
    $start = ($page - 1)*$pagesize;
    $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list($whereStr,$orderStr,$start,$pagesize,$keyword);
    
    $tongchengList = array();
    foreach ($tongchengListTmp as $key => $value) {
        $tongchengList[$key] = $value;
        $tongchengList[$key]['content'] = contentFormat($value['content']);

        $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']); 
        $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
        $siteInfoTmp = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
        if($value['site_id'] > 1){
            $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        }

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
        $tongchengList[$key]['siteInfo'] = $siteInfoTmp;
    }
    
    if(is_array($tongchengList) && !empty($tongchengList)){
        foreach ($tongchengList as $key => $val){
            
            $messageUrl = 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=message&act=create&tongcheng_id='.$val['id'].'&to_user_id='.$val['userInfo']['id'].'&formhash='.FORMHASH;
            $tousuUrl = 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=tousu&tongcheng_id='.$val['id'];
            
            $outStr.= '<div class="tcline-item">';
                   $outStr.= '<div class="avatar-label">';
                        $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'"><img src="'.$val['userInfo']['picurl'].'" class="avatar" /></a>';
                   $outStr.= '</div>';
                   $outStr.= '<div class="tcline-detail" data-id='.$val['id'].'>';
                        if($showTop == 1 && $val['topstatus'] == 1){
                            $outStr.= '<span><a style="background-color: #f15555;">'.lang("plugin/tom_tongcheng", "top").'</a></span>&nbsp; ';
                        }
                        $outStr.= '<span><a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=list&type_id='.$val['typeInfo']['id'].'">'.$val['typeInfo']['name'].'</a></span>&nbsp; ';
                        $outStr.= '<a class="username" href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'">'.$val['userInfo']['nickname'].'</a>';
                        $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'" class="ext-act"><img src="source/plugin/tom_tongcheng/images/icon-show.png" style="width: 12px;"> '.lang("plugin/tom_tongcheng", "template_xiangqing").' </a>';
                        $outStr.= '<article>';
                            if(is_array($val['tagList']) && !empty($val['tagList'])){
                             $outStr.= '<div class="detail-tags">';
                                 foreach ($val['tagList'] as $k1 => $v1){
                                  $outStr.= '<a class="span'.$k1.'">'.$v1['tag_name'].'</a>';
                                 }
                                  $outStr.= '<div class="clear"></div>';
                             $outStr.= '</div>';
                            }
                             $outStr.= '<p>'.$val['content'].'</p>';
                             if(is_array($val['attrList']) && !empty($val['attrList'])){
                                 foreach ($val['attrList'] as $k2 => $v2){
                                    $outStr.= '<p><font color="#F60">'.$v2['attr_name'].'&nbsp;:&nbsp;</font></b>'.$v2['value'].'</p>';
                                 }
                             }
                        $outStr.= '</article>';
                        $outStr.= '<div class="act-bar">';
                             $outStr.= '<a href="tel:'.$val['tel'].'" class="act blue"><img src="source/plugin/tom_tongcheng/images/icon-tel.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_tel").'</a>';
                             $outStr.= '<a href="'.$messageUrl.'" class="act"><img src="source/plugin/tom_tongcheng/images/icon-email.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_sms").'</a>';
                        $outStr.= '</div>';
                        $outStr.= '<div class="detail-toggle">'.lang("plugin/tom_tongcheng", "template_quanwen").'</div>';
                        if(is_array($val['photoList']) && !empty($val['photoList'])){
                        $outStr.= '<div class="detail-pics"><input type="hidden" name="photo_list_'.$val['id'].'" id="photo_list_'.$val['id'].'" value="'.implode('|', $val['photoList']).'">';
                            foreach ($val['photoList'] as $k3 => $v3){
                                $outStr.= '<a href="javascript:void(0);" onclick="showPic(\''.$v3.'\','.$val['id'].');"><img src="'.$v3.'"></a>';
                            }
                        $outStr.= '</div>';
                        }
                        $outStr.= '<div class="detail-time">';
                             $outStr.= '<a>';
                             $outStr.= '<span>'.dgmdate($val['refresh_time'], 'u').'</span>';
                             if($tongchengConfig['show_site_name'] == 1){
                                 $outStr.= '<span>&nbsp;'.lang("plugin/tom_tongcheng", "template_laiyuan").$val['siteInfo']['name'].'</span>';
                             }
                             $outStr.= '</a>';
                             $outStr.= '<div class="detail-time-icon" data-id="'.$val['id'].'" data-message="'.$messageUrl.'" data-tousu="'.$tousuUrl.'" data-tel="'.$val['tel'].'" data-user-id="'.$__UserInfo['id'].'"></div>';
                        $outStr.= '</div>';
                        if($val['finish'] == 1){
                            $outStr.= '<section class="mark-img succ"></section>';
                        }
                        $outStr.= '<div class="detail-cmt-wrap">';
                             $outStr.= '<i class="detail-cmtr"></i>';
                             $outStr.= '<div class="detail-cmt">';
                              $outStr.= '<div class="like-list">';
                                    $outStr.= '<img src="source/plugin/tom_tongcheng/images/icon-heart2.png" /> '.$val['clicks'].' '.lang("plugin/tom_tongcheng", "template_clicks").'';
                                    $outStr.= '<span >'.$val['collect'].'</span> '.lang("plugin/tom_tongcheng", "template_collect").' ';
                              $outStr.= '</div>';
                             $outStr.= '</div>';
                        $outStr.= '</div>';
                   $outStr.= '</div>';
              $outStr.= '</div>';
        }
    }else{
        $outStr = '205';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'collect' && $_GET['formhash'] == FORMHASH){
    
    $user_id        = intval($_GET['user_id'])>0? intval($_GET['user_id']):0;
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $collectListTmp = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_list(" AND user_id={$user_id} AND tongcheng_id={$tongcheng_id} "," ORDER BY id DESC ",0,1);
    
    if(is_array($collectListTmp) && !empty($collectListTmp)){
        echo 100;exit;
    }
    
    $insertData = array();
    $insertData['user_id']      = $user_id;
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['add_time']     = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng_collect')->insert($insertData)){
        DB::query("UPDATE ".DB::table('tom_tongcheng')." SET collect=collect+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');
        echo 200;exit;
    }
    
    echo 404;exit;
    
}else if($_GET['act'] == 'clicks' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    DB::query("UPDATE ".DB::table('tom_tongcheng')." SET clicks=clicks+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');
    echo 200;exit;
    
}else if($_GET['act'] == 'commonClicks' && $_GET['formhash'] == FORMHASH){
    
    DB::query("UPDATE ".DB::table('tom_tongcheng_common')." SET clicks=clicks+1 WHERE id='$site_id' ", 'UNBUFFERED');
    echo 200;exit;
    
}else if($_GET['act'] == 'updateTopstatus' && $_GET['formhash'] == FORMHASH){
    
    $cookiesTopstatus = getcookie('tom_tongcheng_update_topstatus');
    if(!empty($cookiesTopstatus) && $cookiesTopstatus==1){
    }else{
        $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND topstatus=1 AND toptime<".TIMESTAMP." "," ORDER BY refresh_time DESC,id DESC ",0,10);
        if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
            foreach ($tongchengListTmp as $key => $value){
                $updateData = array();
                $updateData['topstatus']     = 0;
                C::t('#tom_tongcheng#tom_tongcheng')->update($value['id'],$updateData);
            }
        }
        dsetcookie('tom_tongcheng_update_topstatus',1,300);
    }
    echo 200;exit;
    
}else if($_GET['act'] == 'get_search_url' && $_GET['formhash'] == FORMHASH){
    
    $keyword = isset($_GET['keyword'])? daddslashes(diconv(urldecode($_GET['keyword']),'utf-8')):'';
    
    $url = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&keyword=".urlencode(trim($keyword));
    echo $url;exit;
    
}else if($_GET['act'] == 'updateStatus' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    if($_GET['status'] == 1){
        $updateData = array();
        $updateData['status'] = 1;
        C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    }else if($_GET['status'] == 2){
        $updateData = array();
        $updateData['status'] = 2;
        C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    }
    
    echo 200;exit;
}else if($_GET['act'] == 'updateFinish' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    $updateData = array();
    $updateData['finish'] = 1;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    echo 200;exit;
    
}else if($_GET['act'] == 'refresh' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    $updateData = array();
    $updateData['refresh_time'] = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    $insertData = array();
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['time_key']     = $nowDayTime;
    $insertData['add_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->insert($insertData);
    
    echo 200;exit;
}else if($_GET['act'] == 'list_get_street' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $area_id   = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;
    
    $streetList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($area_id);
    
    if($area_id > 0 && is_array($streetList) && !empty($streetList)){
        $outStr = '<li class="item" data-id="0" data-name="'.lang("plugin/tom_tongcheng", "template_list_all").'">'.lang("plugin/tom_tongcheng", "template_list_all").'</li>';
        foreach ($streetList as $key => $value){
           $outStr.= '<li class="item" data-id="'.$value['id'].'" data-name="'.$value['name'].'">'.$value['name'].'</li>';
        }
    }else{
       $outStr = '100';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'list_get_cate' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $type_id   = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    
    $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$type_id}  "," ORDER BY paixu ASC,id DESC ",0,50);
    
    if(is_array($cateList) && !empty($cateList)){
        $outStr = '<li class="item" data-id="0" data-name="'.lang("plugin/tom_tongcheng", "template_list_all").'">'.lang("plugin/tom_tongcheng", "template_list_all").'</li>';
        foreach ($cateList as $key => $value){
           $outStr.= '<li class="item" data-id="'.$value['id'].'" data-name="'.$value['name'].'">'.$value['name'].'</li>';
        }
    }else{
       $outStr = '100';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'auto_click' && $_GET['formhash'] == FORMHASH){
    
    $cookies_auto_click_status = getcookie('tom_tongcheng_auto_click_status');
    $halfhour = TIMESTAMP - 1800;
    $threedays = TIMESTAMP - 86400*3;
    if($tongchengConfig['open_auto_click'] == 1){
        
        $auto_min_num = 5;
        $auto_max_num = 10;
        if($tongchengConfig['auto_min_num'] < $tongchengConfig['auto_max_num']){
            $auto_min_num = $tongchengConfig['auto_min_num'];
            $auto_max_num = $tongchengConfig['auto_max_num'];
        }
        
        $auto_click_num = mt_rand($auto_min_num, $auto_max_num);
        
        if(!empty($cookies_auto_click_status) && $cookies_auto_click_status==1){
        }else{
            $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND add_time<".$halfhour." AND auto_click_time<".$nowDayTime." AND refresh_time>".$threedays." "," ORDER BY id DESC ",0,10);
            if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
                foreach ($tongchengListTmp as $key => $value){
                    $updateData = array();
                    $updateData['clicks']     = $value['clicks'] + $auto_click_num;
                    $updateData['auto_click_time']     = $nowDayTime;
                    C::t('#tom_tongcheng#tom_tongcheng')->update($value['id'],$updateData);
                }
            }
            dsetcookie('tom_tongcheng_auto_click_status',1,300);
        }
    }
    
    echo 200;exit;
    
}else if($_GET['act'] == 'shenhe_sms' && $_GET['formhash'] == FORMHASH){
    
    $cookies_shenhe_sms_status = getcookie('tom_tongcheng_shenhe_sms_status');
    
    if(!empty($cookies_shenhe_sms_status) && $cookies_shenhe_sms_status==1){
        echo 404;exit;
    }else{
        $noShenheCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND site_id={$site_id} AND pay_status!=1 AND shenhe_status=2 ");
    
        if($noShenheCount >0){
        }else{
            echo 0;exit;
        }

        $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);

        $toUser = array();
        $toUserId = 0;
        if(!empty($sitesInfo['manage_user_id'])){
            $toUserId = $sitesInfo['manage_user_id'];
        }else if(!empty($tongchengConfig['manage_user_id'])){
            $toUserId = $tongchengConfig['manage_user_id'];
        }

        if(empty($toUserId)){
            echo 1;exit;
        }

        $toUserTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($toUserId);
        if($toUserTmp && !empty($toUserTmp['openid'])){
            $toUser = $toUserTmp;
        }else{
            echo 2;exit;
        }
        
        $appid = trim($tongchengConfig['wxpay_appid']);  
        $appsecret = trim($tongchengConfig['wxpay_appsecret']);
        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
        $weixinClass = new weixinClass($appid,$appsecret);

        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
        $access_token = $weixinClass->get_access_token();
        $nextSmsTime = $toUser['last_smstp_time'] + 300;

        if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&type=1");
            $shenhe_template_first = str_replace("{NUM}",$noShenheCount, lang('plugin/tom_tongcheng','shenhe_template_first'));
            $smsData = array(
                'first'         => $shenhe_template_first,
                'keyword1'      => '',
                'keyword2'      => '',
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

            if($r){
                $updateData = array();
                $updateData['last_smstp_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($toUser['id'],$updateData);
            }

        }

        dsetcookie('tom_tongcheng_shenhe_sms_status',1,300);
    }
    
    echo 200;exit;
    
}else{
    echo 'error';exit;
}

