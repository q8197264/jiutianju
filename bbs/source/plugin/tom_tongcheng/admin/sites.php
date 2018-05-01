<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=sites';
$modListUrl = $adminListUrl.'&tmod=sites';
$modFromUrl = $adminFromUrl.'&tmod=sites';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        
        $siteList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" ","ORDER BY id DESC",0,1);
        $siteId = 100001;
        if(is_array($siteList) && !empty($siteList) && isset($siteList['0']) && $siteList['0']['id']>0){
            $siteId = $siteList['0']['id']+1;
        }
        
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['id'] = $siteId;
        C::t('#tom_tongcheng#tom_tongcheng_sites')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($sitesInfo);
        C::t('#tom_tongcheng#tom_tongcheng_sites')->update($sitesInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($sitesInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'open'){
    
    $updateData = array();
    $updateData['status'] = 1;
    C::t('#tom_tongcheng#tom_tongcheng_sites')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'close'){
    
    $updateData = array();
    $updateData['status'] = 2;
    C::t('#tom_tongcheng#tom_tongcheng_sites')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'url'){
    
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    $url = "{SITEURL}plugin.php?id=tom_tongcheng&site={$_GET['id']}&mod=index";
    $url  = str_replace("{SITEURL}", $_G['siteurl'], $url);
    __create_nav_html();
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' .$sitesInfo['name'].'</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['sites_url_title'] . '&nbsp;<input name="" readonly="readonly" type="text" value="'.$url.'" size="100" />' . $Lang['sites_url_title_msg'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(""," ORDER BY id DESC ",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['sites_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li><font color="#fd0d0d">' . $Lang['sites_help_1'] . '</font></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['sites_id'] . '</th>';
    echo '<th>' . $Lang['sites_name'] . '</th>';
    echo '<th>' . $Lang['sites_city'] . '</th>';
    echo '<th>' . $Lang['sites_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($sitesList as $key => $value) {
        
        $cityInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['city_id']);
        
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $cityInfo['name'] . '</td>';
        if($value['status'] == 1){
            echo '<td><font color="#238206">' . $Lang['sites_status_1'] . '</font></td>';
        }else{
            echo '<td><font color="#fd0d0d">' . $Lang['sites_status_2'] . '</font></td>';
        }
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=url&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_url_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_edit']. '</a>&nbsp;|&nbsp;';
        if($value['status'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=close&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_status_2']. '</a>';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=open&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_status_1']. '</a>';
        }
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $manage_user_id = isset($_GET['manage_user_id'])? intval($_GET['manage_user_id']):0;
    $name           = isset($_GET['name'])? addslashes($_GET['name']):'';
    $city_id           = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $share_title           = isset($_GET['share_title'])? addslashes($_GET['share_title']):'';
    $share_desc           = isset($_GET['share_desc'])? addslashes($_GET['share_desc']):'';
    
    
    $logo = "";
    if($_GET['act'] == 'add'){
        $logo        = tomuploadFile("logo");
    }else if($_GET['act'] == 'edit'){
        $logo        = tomuploadFile("logo",$infoArr['logo']);
    }
    
    $kefu_qrcode = "";
    if($_GET['act'] == 'add'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode");
    }else if($_GET['act'] == 'edit'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode",$infoArr['kefu_qrcode']);
    }
    
    $share_pic = "";
    if($_GET['act'] == 'add'){
        $share_pic        = tomuploadFile("share_pic");
    }else if($_GET['act'] == 'edit'){
        $share_pic        = tomuploadFile("share_pic",$infoArr['share_pic']);
    }
    
    $data['manage_user_id']      = $manage_user_id;
    $data['name']      = $name;
    $data['logo']      = $logo;
    $data['kefu_qrcode']      = $kefu_qrcode;
    $data['city_id']   = $city_id;
    $data['share_title']   = $share_title;
    $data['share_desc']   = $share_desc;
    $data['share_pic']   = $share_pic;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'manage_user_id'           => 0,
        'name'              => '',
        'logo'              => '',
        'kefu_qrcode'       => '',
        'city_id'           => 0,
        'share_title'       => '',
        'share_desc'       => '',
        'share_pic'       => '',
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['sites_manage_user_id'],'name'=>'manage_user_id','value'=>$options['manage_user_id'],'msg'=>$Lang['sites_manage_user_id_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['sites_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_logo'],'name'=>'logo','value'=>$options['logo'],'msg'=>$Lang['sites_logo_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['sites_kefu_qrcode'],'name'=>'kefu_qrcode','value'=>$options['kefu_qrcode'],'msg'=>$Lang['sites_kefu_qrcode_msg']),"file");
    
    $cityList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_level(1);
    $cityStr = '<tr class="header"><th>'.$Lang['sites_city'].'</th><th></th></tr>';
    $cityStr.= '<tr><td width="300"><select style="width: 260px;" name="city_id" id="city_id">';
    foreach ($cityList as $key => $value){
        if($value['id'] == $options['city_id']){
            $cityStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $cityStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $cityStr.= '</select></td><td>'.$Lang['sites_city_msg'].'</td></tr>';
    echo $cityStr;

    tomshowsetting(true,array('title'=>$Lang['sites_share_title'],'name'=>'share_title','value'=>$options['share_title'],'msg'=>$Lang['sites_share_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_share_desc'],'name'=>'share_desc','value'=>$options['share_desc'],'msg'=>$Lang['sites_share_desc_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_share_pic'],'name'=>'share_pic','value'=>$options['share_pic'],'msg'=>$Lang['sites_share_pic_msg']),"file");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['sites_edit'],"",true);
    }else{
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['sites_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


