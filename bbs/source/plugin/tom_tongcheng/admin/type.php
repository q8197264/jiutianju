<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=type&model_id='.$_GET['model_id'];
$modListUrl = $adminListUrl.'&tmod=model';
$modFromUrl = $adminFromUrl.'&tmod=type&model_id='.$_GET['model_id'];

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_model_type')->insert($insertData);
        $insertId = C::t('#tom_tongcheng#tom_tongcheng_model_type')->insert_id();
        cpmsg($Lang['act_success'], $adminListUrl.'&tmod=type&act=edit&model_id='.$_GET['model_id'].'&id='.$insertId, 'succeed');
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
    $id = intval($_GET['id'])>0? intval($_GET['id']):0;
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($typeInfo);
        C::t('#tom_tongcheng#tom_tongcheng_model_type')->update($typeInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $adminListUrl.'&tmod=type&act=edit&model_id='.$_GET['model_id'].'&id='.$id, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($typeInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
        
        $css = <<<EOF
<style type="text/css">
.tom_tc_li { padding-bottom: 10px; }
.tom_tc_li li{ height: 30px; width: 200px; text-decoration: none; border: 1px solid #0495c9; list-style-type: none; float: left; margin-left: 10px; line-height: 30px; padding-left: 5px; padding-right: 5px; margin-top: 5px; margin-bottom: 5px; }
.tom_tc_li li span{ float: right; font-size: 13px; }
</style>
EOF;
        echo $css;
        
        tomshownavheader();
        tomshownavli($Lang['cate_list_title'],"",true);
        tomshownavli($Lang['cate_add'],$adminBaseUrl.'&tmod=cate&model_id='.$_GET['model_id'].'&type_id='.$_GET['id']."&act=add",false);
        tomshownavfooter();
        $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$id}  "," ORDER BY paixu ASC,id DESC ",0,50);
        if(is_array($cateList) && !empty($cateList)){
            echo '<div class="tom_tc_li"><ul>';
            foreach ($cateList as $k1 => $v1){
                echo '<li>';
                echo ''.$v1['name'].'';
                echo '<span>';
                echo '<a href="'.$adminBaseUrl.'&tmod=cate&act=edit&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v1['id'].'&formhash='.FORMHASH.'">' . $Lang['edit']. '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$adminBaseUrl.'&tmod=cate&act=del&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v1['id'].'&formhash='.FORMHASH.'\');">' . $Lang['del'] . '</a>';
                echo '</span>';
                echo '</li>';
            }
            echo '</ul></div>';
        }
        
        tomshownavheader();
        tomshownavli($Lang['tag_list_title'],"",true);
        tomshownavli($Lang['tag_add'],$adminBaseUrl.'&tmod=tag&model_id='.$_GET['model_id'].'&type_id='.$_GET['id']."&act=add",false);
        tomshownavfooter();
        $tagList = C::t('#tom_tongcheng#tom_tongcheng_model_tag')->fetch_all_list(" AND type_id={$id} "," ORDER BY paixu ASC,id DESC ",0,50);
        if(is_array($tagList) && !empty($tagList)){
            echo '<div class="tom_tc_li"><ul>';
            foreach ($tagList as $k2 => $v2){
                echo '<li>';
                echo ''.$v2['name'].'';
                echo '<span>';
                echo '<a href="'.$adminBaseUrl.'&tmod=tag&act=edit&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v2['id'].'&formhash='.FORMHASH.'">' . $Lang['edit']. '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$adminBaseUrl.'&tmod=tag&act=del&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v2['id'].'&formhash='.FORMHASH.'\');">' . $Lang['del'] . '</a>';
                echo '</span>';
                echo '</li>';
            }
            echo '</ul></div>';
        }
        
        tomshownavheader();
        tomshownavli($Lang['attr_list_title'],"",true);
        tomshownavli($Lang['attr_add'],$adminBaseUrl.'&tmod=attr&model_id='.$_GET['model_id'].'&type_id='.$_GET['id']."&act=add",false);
        tomshownavfooter();
        $attrList = C::t('#tom_tongcheng#tom_tongcheng_model_attr')->fetch_all_list(" AND type_id={$id} "," ORDER BY paixu ASC,id DESC ",0,50);
        if(is_array($attrList) && !empty($attrList)){
            echo '<div class="tom_tc_li"><ul>';
            foreach ($attrList as $k3 => $v3){
                echo '<li>';
                echo ''.$v3['name'].'';
                echo '<span>';
                echo '<a href="'.$adminBaseUrl.'&tmod=attr&act=edit&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v3['id'].'&formhash='.FORMHASH.'">' . $Lang['edit']. '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$adminBaseUrl.'&tmod=attr&act=del&model_id='.$_GET['model_id'].'&type_id='.$_GET['id'].'&id='.$v3['id'].'&formhash='.FORMHASH.'\');">' . $Lang['del'] . '</a>';
                echo '</span>';
                echo '</li>';
            }
            echo '</ul></div>';
        }
        echo '<br/><br/><br/>';
        
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
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_model_type')->delete_by_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_attr')->delete_by_type_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_cate')->delete_by_type_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_model_tag')->delete_by_type_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    
    $free_status        = isset($_GET['free_status'])? intval($_GET['free_status']):'1';
    $fabu_price        = isset($_GET['fabu_price'])? addslashes($_GET['fabu_price']):'';
    $refresh_price        = isset($_GET['refresh_price'])? addslashes($_GET['refresh_price']):'';
    $top_price        = isset($_GET['top_price'])? addslashes($_GET['top_price']):'';
    
    $cate_title        = isset($_GET['cate_title'])? addslashes($_GET['cate_title']):'';
    $desc_title        = isset($_GET['desc_title'])? addslashes($_GET['desc_title']):'';
    $desc_content        = isset($_GET['desc_content'])? addslashes($_GET['desc_content']):'';
    $warning_msg        = isset($_GET['warning_msg'])? addslashes($_GET['warning_msg']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):100;

    $data['model_id']   = $_GET['model_id'];
    $data['name']       = $name;
    
    $data['free_status']    = $free_status;
    $data['fabu_price']    = $fabu_price;
    $data['refresh_price']    = $refresh_price;
    $data['top_price']    = $top_price;
    
    $data['cate_title']    = $cate_title;
    $data['desc_title']    = $desc_title;
    $data['desc_content']    = $desc_content;
    $data['warning_msg']    = $warning_msg;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'              => '',
        'free_status'       => 1,
        'fabu_price'        => '1.00',
        'refresh_price'     => '1.00',
        'top_price'         => '1.00',
        'cate_title'        => '',
        'desc_title'        => '',
        'desc_content'      => '',
        'warning_msg'       => '',
        'paixu'             => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['type_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['type_name_msg']),"input");
    
    $free_status_item = array(1=>$Lang['type_free_status_1'],2=>$Lang['type_free_status_2']);
    tomshowsetting(true,array('title'=>$Lang['type_free_status'],'name'=>'free_status','value'=>$options['free_status'],'msg'=>$Lang['type_free_status_msg'],'item'=>$free_status_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['type_fabu_price'],'name'=>'fabu_price','value'=>$options['fabu_price'],'msg'=>$Lang['type_fabu_price_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_refresh_price'],'name'=>'refresh_price','value'=>$options['refresh_price'],'msg'=>$Lang['type_refresh_price_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_top_price'],'name'=>'top_price','value'=>$options['top_price'],'msg'=>$Lang['type_top_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['type_cate_title'],'name'=>'cate_title','value'=>$options['cate_title'],'msg'=>$Lang['type_cate_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_desc_title'],'name'=>'desc_title','value'=>$options['desc_title'],'msg'=>$Lang['type_desc_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_desc_content'],'name'=>'desc_content','value'=>$options['desc_content'],'msg'=>$Lang['type_desc_content_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['type_warning_msg'],'name'=>'warning_msg','value'=>$options['warning_msg'],'msg'=>$Lang['type_warning_msg_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['type_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['type_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl,$modListUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['type_add'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['type_edit'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }
    tomshownavfooter();
}


