<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=model';
$modListUrl = $adminListUrl.'&tmod=model';
$modFromUrl = $adminFromUrl.'&tmod=model';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_model')->insert($insertData);
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
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($modelInfo);
        C::t('#tom_tongcheng#tom_tongcheng_model')->update($modelInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($modelInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'import'){
    
    include DISCUZ_ROOT.$tongchengConfig['admin_model_data'];
    
    foreach ($modelArr as $key => $value){
        $insertData = array();
        $insertData['name']     = $value['name'];
        $insertData['picurl']   = $value['picurl'];
        $insertData['paixu']    = $key;
        C::t('#tom_tongcheng#tom_tongcheng_model')->insert($insertData);
        $model_id = C::t('#tom_tongcheng#tom_tongcheng_model')->insert_id();
        
        foreach ($value['type'] as $k1 => $v1){
            $insertData = array();
            $insertData['model_id']     = $model_id;
            $insertData['name']         = $v1['name'];
            $insertData['cate_title']   = $v1['cate_title'];
            $insertData['desc_title']   = $v1['desc_title'];
            $insertData['desc_content'] = $v1['desc_content'];
            $insertData['warning_msg']  = $v1['warning_msg'];
            $insertData['paixu']        = $k1;
            C::t('#tom_tongcheng#tom_tongcheng_model_type')->insert($insertData);
            $type_id = C::t('#tom_tongcheng#tom_tongcheng_model_type')->insert_id();
            
            if(is_array($v1['cate']) && !empty($v1['cate'])){
                foreach ($v1['cate'] as $k2 => $v2){
                    $insertData = array();
                    $insertData['model_id']     = $model_id;
                    $insertData['type_id']      = $type_id;
                    $insertData['name']         = $v2;
                    $insertData['paixu']        = $k2;
                    C::t('#tom_tongcheng#tom_tongcheng_model_cate')->insert($insertData);
                }
            }
            
            if(is_array($v1['attr']) && !empty($v1['attr'])){
                foreach ($v1['attr'] as $k3 => $v3){
                    $insertData = array();
                    $insertData['model_id']     = $model_id;
                    $insertData['type_id']      = $type_id;
                    $insertData['name']         = $v3['name'];
                    $insertData['type']         = $v3['type'];
                    $insertData['value']        = implode("\n", $v3['list']);
                    $insertData['paixu']        = $k3;
                    C::t('#tom_tongcheng#tom_tongcheng_model_attr')->insert($insertData);
                }
            }
            
            if(is_array($v1['tag']) && !empty($v1['tag'])){
                foreach ($v1['tag'] as $k4 => $v4){
                    $insertData = array();
                    $insertData['model_id']     = $model_id;
                    $insertData['type_id']      = $type_id;
                    $insertData['name']         = $v4;
                    $insertData['paixu']        = $k4;
                    C::t('#tom_tongcheng#tom_tongcheng_model_tag')->insert($insertData);
                }
            }
            
        }
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_model')->delete_by_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_type')->delete_by_model_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_attr')->delete_by_model_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_cate')->delete_by_model_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_tag')->delete_by_model_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['model_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['model_help_1'] . '</li>';
    echo '<li><a href="javascript:void(0);" onclick="import_confirm(\''.$modBaseUrl.'&act=import&formhash='.FORMHASH.'\');" class="addtr" ><font color="#F60">'.$Lang['model_import'].'</font></a></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['model_picurl'] . '</th>';
    echo '<th>' . $Lang['model_name'] . '</th>';
    echo '<th>' . $Lang['type_free_status'] . '</th>';
    echo '<th>' . $Lang['type_price'] . '</th>';
    echo '<th>' . $Lang['model_num'] . '</th>';
    echo '<th>' . $Lang['model_must_shenhe'] . '</th>';
    echo '<th>' . $Lang['model_paixu'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($modelList as $key => $value) {
        
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        
        $modelCountTmp          = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND model_id={$value['id']} ");
        
        $modelUrl = $adminBaseUrl.'&tmod=index';
        
        echo '<tr>';
        echo '<td><img src="'.$picurl.'" width="40" /></td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>&nbsp;</td>';
        echo '<td>&nbsp;</td>';
        echo '<td><font color="#f00">' . $modelCountTmp . '</font>&nbsp;<a href="'.$modelUrl.'&model_id='.$value['id'].'"><font color="#928c8c">(' . $Lang['model_num_chakan'] . ')</font></a></td>';
        if($value['must_shenhe'] == 1){
            echo '<td><font color="#fd0d0d">' . $Lang['model_must_shenhe_1'] . '</font></td>';
        }else{
            echo '<td><font color="#238206">' . $Lang['model_must_shenhe_0'] . '</font></td>';
        }
        echo '<td>' . $value['paixu'] . '</td>';
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=type&act=add&model_id='.$value['id'].'&formhash='.FORMHASH.'"><font color="#FA6A03">' . $Lang['type_add']. '</font></a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['model_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        
        $typeList = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$value['id']} "," ORDER BY paixu ASC,id DESC ",0,20);
        
        if(is_array($typeList) && !empty($typeList) && $tongchengConfig['admin_tongcheng_type_list'] == 1){
            foreach ($typeList as $kk => $vv){
                $typeCountTmp          = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND type_id={$vv['id']} ");
                echo '<tr>';
                echo '<td>&nbsp;</td>';
                echo '<td><font color="#FA6A03">|--</font> ' . $vv['name'] . '</td>';
                if($vv['free_status'] == $tongchengConfig['admin_tongcheng_type_free_status_1']){
                    echo '<td><font color="#0a9409">' . $Lang['type_free_status_1'] . '</font></td>';
                }else if($vv['free_status'] == $tongchengConfig['admin_tongcheng_type_free_status_2']){
                    echo '<td><font color="#f00">' . $Lang['type_free_status_2'] . '</font></td>';
                }else{
                    echo '<td>-</td>';
                }
                echo '<td><font color="#f00">' . $vv['fabu_price'].'</font>'. $Lang['type_price_fabu'].'&nbsp;,&nbsp;<font color="#f00">'. $vv['refresh_price'].'</font>'. $Lang['type_price_refresh'] .'&nbsp;,&nbsp;<font color="#f00">'. $vv['top_price'].'</font>'. $Lang['type_price_top']  . '</td>';
                echo '<td><font color="#f00">' . $typeCountTmp . '</font>&nbsp;<a href="'.$modelUrl.'&model_id='.$value['id'].'"><font color="#928c8c">(' . $Lang['model_num_chakan'] . ')</font></a></td>';
                echo '<td>&nbsp;</td>';
                echo '<td>' . $vv['paixu'] . '</td>';
                echo '<td>';
                echo '<a href="'.$adminBaseUrl.'&tmod=type&act=edit&model_id='.$value['id'].'&id='.$vv['id'].'&formhash='.FORMHASH.'">' . $Lang['type_edit']. '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$adminBaseUrl.'&tmod=type&act=del&model_id='.$value['id'].'&id='.$vv['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
                echo '</td>';
                echo '</tr>';
            }
        }
        
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
function import_confirm(url){
  var r = confirm("{$Lang['makesure_import_msg']}")
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
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $area_select = isset($_GET['area_select'])? intval($_GET['area_select']):0;
    $must_shenhe = isset($_GET['must_shenhe'])? intval($_GET['must_shenhe']):0;
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):100;
    
    $picurl = "";
    if($_GET['act'] == 'add'){
        $picurl        = tomuploadFile("picurl");
    }else if($_GET['act'] == 'edit'){
        $picurl        = tomuploadFile("picurl",$infoArr['picurl']);
    }

    $data['name']       = $name;
    $data['picurl']     = $picurl;
    $data['area_select']= $area_select;
    $data['must_shenhe']= $must_shenhe;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'           => '',
        'picurl'         => '',
        'area_select'    => 0,
        'must_shenhe'    => 0,
        'paixu'          => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['model_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['model_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['model_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['model_picurl_msg']),"file");
    $area_select_item = array(0=>$Lang['model_area_select_0'],1=>$Lang['model_area_select_1']);
    tomshowsetting(true,array('title'=>$Lang['model_area_select'],'name'=>'area_select','value'=>$options['area_select'],'msg'=>$Lang['model_area_select_msg'],'item'=>$area_select_item),"radio");
    $must_shenhe_item = array(0=>$Lang['model_must_shenhe_0'],1=>$Lang['model_must_shenhe_1']);
    tomshowsetting(true,array('title'=>$Lang['model_must_shenhe'],'name'=>'must_shenhe','value'=>$options['must_shenhe'],'msg'=>$Lang['model_must_shenhe_msg'],'item'=>$must_shenhe_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['model_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['model_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['model_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['model_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['model_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['model_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['model_edit'],"",true);
    }else{
        tomshownavli($Lang['model_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['model_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


