<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=index'; 
$modListUrl = $adminListUrl.'&tmod=index';
$modFromUrl = $adminFromUrl.'&tmod=index';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_kanjia#tom_kanjia')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
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
    $kanjiaInfo = C::t('#tom_kanjia#tom_kanjia')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($kanjiaInfo);
        C::t('#tom_kanjia#tom_kanjia')->update($kanjiaInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($kanjiaInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    C::t('#tom_kanjia#tom_kanjia')->delete_by_id($_GET['id']);
    C::t('#tom_kanjia#tom_kanjia_log')->delete_by_kj_id($_GET['id']);
    C::t('#tom_kanjia#tom_kanjia_price')->delete_by_kj_id($_GET['id']);
    C::t('#tom_kanjia#tom_kanjia_user')->delete_by_kj_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    $pagesize = 15;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_kanjia#tom_kanjia')->fetch_all_count("");
    $kanjiaList = C::t('#tom_kanjia#tom_kanjia')->fetch_all_list("","ORDER BY add_time DESC",$start,$pagesize);
 
    echo '<script src="source/plugin/tom_kanjia/images/admin.js"></script>';
    showtableheader();
    $Lang['kanjia_help_1']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['kanjia_help_1']);
    $Lang['kanjia_help_2']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['kanjia_help_2']);
    $Lang['kanjia_help_5']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['kanjia_help_5']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['kanjia_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['kanjia_help_1'] . '</li>';
    echo '<li>' . $Lang['kanjia_help_2'] . '</li>';
    echo '<li>' . $Lang['kanjia_help_3'] . '</li>';
    echo '<li>' . $Lang['kanjia_help_4'] . '</li>';
    echo '<li>' . $Lang['kanjia_help_5'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th width="10%">' . $Lang['id'] . '</th>';
    echo '<th>' . $Lang['title'] . '</th>';
    echo '<th>' . $Lang['start_time'] . '</th>';
    echo '<th>' . $Lang['end_time'] . '</th>';
    echo '<th>' . $Lang['clicks'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($kanjiaList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['title'] . '</td>';
        echo '<td>' . dgmdate($value['start_time'],"Y-m-d H:i",$tomSysOffset) . '</td>';
        echo '<td>' . dgmdate($value['end_time'],"Y-m-d H:i",$tomSysOffset) . '</td>';
        echo '<td>' . $value['clicks'] . '</td>';
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=user&kj_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_list_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$adminBaseUrl.'&tmod=price&kj_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['price_list_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['kanjia_edit']. '</a>&nbsp;|&nbsp;';
        //echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['delete'] . '</a>';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
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
    
    $title          = isset($_GET['title'])? addslashes($_GET['title']):'';
    $template_id    = isset($_GET['template_id'])? addslashes($_GET['template_id']):'';
    $style_id       = isset($_GET['style_id'])? intval($_GET['style_id']):1;
    $kj_type        = isset($_GET['kj_type'])? intval($_GET['kj_type']):0;
    $start_time     = isset($_GET['start_time'])? addslashes($_GET['start_time']):'';
    $start_time     = strtotime($start_time);
    $end_time       = isset($_GET['end_time'])? addslashes($_GET['end_time']):'';
    $end_time       = strtotime($end_time);
    $goods_price    = isset($_GET['goods_price'])? addslashes($_GET['goods_price']):'';
    $base_price     = isset($_GET['base_price'])? addslashes($_GET['base_price']):'';
    $goods_num      = isset($_GET['goods_num'])? intval($_GET['goods_num']):0;
    $dh_pwd         = isset($_GET['dh_pwd'])? addslashes($_GET['dh_pwd']):'';
    $buy_url        = isset($_GET['buy_url'])? addslashes($_GET['buy_url']):'';
    $add_msg        = isset($_GET['add_msg'])? addslashes($_GET['add_msg']):'';
    $info           = isset($_GET['info'])? addslashes($_GET['info']):'';
    $bk_info        = isset($_GET['bk_info'])? addslashes($_GET['bk_info']):'';
    $goodinfo       = isset($_GET['goodinfo'])? addslashes($_GET['goodinfo']):'';
    $content        = isset($_GET['content'])? addslashes($_GET['content']):'';
    $share_title    = isset($_GET['share_title'])? addslashes($_GET['share_title']):'';
    $share_desc     = isset($_GET['share_desc'])? addslashes($_GET['share_desc']):'';
    $guanzu_desc    = isset($_GET['guanzu_desc'])? addslashes($_GET['guanzu_desc']):'';
    $guanzu_url     = isset($_GET['guanzu_url'])? addslashes($_GET['guanzu_url']):'';
    $ads_link       = isset($_GET['ads_link'])? addslashes($_GET['ads_link']):'';
    $must_gz        = isset($_GET['must_gz'])? intval($_GET['must_gz']):0;
    $virtual_clicks = isset($_GET['virtual_clicks'])? intval($_GET['virtual_clicks']):0;
    $mp3_link       = isset($_GET['mp3_link'])? addslashes($_GET['mp3_link']):'';
    $paixu          = isset($_GET['paixu'])? intval($_GET['paixu']):'';
    
    $pic_url = "";
    if($_GET['act'] == 'add'){
        $pic_url        = tomuploadFile("pic_url");
    }else if($_GET['act'] == 'edit'){
        $pic_url        = tomuploadFile("pic_url",$infoArr['pic_url']);
    }
    
    $share_logo = "";
    if($_GET['act'] == 'add'){
        $share_logo        = tomuploadFile("share_logo");
    }else if($_GET['act'] == 'edit'){
        $share_logo        = tomuploadFile("share_logo",$infoArr['share_logo']);
    }
    
    $ads_picurl = "";
    if($_GET['act'] == 'add'){
        $ads_picurl        = tomuploadFile("ads_picurl");
    }else if($_GET['act'] == 'edit'){
        $ads_picurl        = tomuploadFile("ads_picurl",$infoArr['ads_picurl']);
    }

    $data['title']        = $title;
    $data['template_id']  = $template_id;
    $data['style_id']     = $style_id;
    $data['kj_type']      = $kj_type;
    $data['start_time']   = $start_time;
    $data['end_time']     = $end_time;
    $data['pic_url']      = $pic_url;
    $data['goods_price']  = $goods_price;
    $data['base_price']   = $base_price;
    $data['goods_num']    = $goods_num;
    $data['dh_pwd']       = $dh_pwd;
    $data['buy_url']      = $buy_url;
    $data['add_msg']      = $add_msg;
    $data['info']         = $info;
    $data['bk_info']      = $bk_info;
    $data['goodinfo']     = $goodinfo;
    $data['content']      = $content;
    $data['share_title']  = $share_title;
    $data['share_desc']   = $share_desc;
    $data['guanzu_desc']  = $guanzu_desc;
    $data['guanzu_url']   = $guanzu_url;
    $data['share_logo']   = $share_logo;
    $data['must_gz']      = $must_gz;
    $data['ads_picurl']   = $ads_picurl;
    $data['ads_link']     = $ads_link;
    $data['virtual_clicks']        = $virtual_clicks;
    $data['mp3_link']        = $mp3_link;
    $data['paixu']        = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'title'         => '',
        'template_id'   => '',
        'style_id'      => 1,
        'kj_type'       => 1,
        'start_time'    => time(),
        'end_time'      => time(),
        'pic_url'       => "",
        'goods_price'   => "0.00",
        'base_price'    => "0.00",
        'goods_num'     => 0,
        'dh_pwd'       => "",
        'buy_url'       => "",
        'add_msg'       => "",
        'info'          => "",
        'bk_info'          => "",
        'goodinfo'      => "",
        'content'       => "",
        'share_title'   => "",
        'share_desc'    => "",
        'guanzu_desc'    => "",
        'guanzu_url'    => "",
        'share_logo'    => "",
        'must_gz'       => 0,
        'ads_picurl'    => "",
        'ads_link'    => "",
        'virtual_clicks'       => 0,
        'mp3_link'    => "",
        'paixu'         => 100,
    );
    $options = array_merge($options, $infoArr);
    
    $template_items = array('default'=>$Lang['template_default']);
    if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_kanjia/template/baise/index.htm')){
        $template_items['baise'] = $Lang['template_baise'];
    }
    if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_kanjia/template/huangse/index.htm')){
        $template_items['huangse'] = $Lang['template_huangse'];
    }
    if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_kanjia/template/lanlvse/index.htm')){
        $template_items['lanlvse'] = $Lang['template_lanlvse'];
    }
    if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_kanjia/template/lanse/index.htm')){
        $template_items['lanse'] = $Lang['template_lanse'];
    }
    
    tomshowsetting(array('title'=>$Lang['title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['title_msg']),"input");
    tomshowsetting(array('title'=>$Lang['template_id'],'name'=>'template_id','value'=>$options['template_id'],'msg'=>$Lang['template_id_msg'],'item'=>$template_items),"select");
    $style_id_item = array(1=>$Lang['style_id_1'],2=>$Lang['style_id_2'],3=>$Lang['style_id_3']);
    tomshowsetting(array('title'=>$Lang['style_id'],'name'=>'style_id','value'=>$options['style_id'],'msg'=>$Lang['style_id_msg'],'item'=>$style_id_item),"select");
    $kj_type_item = array(1=>$Lang['kj_type_1'],2=>$Lang['kj_type_2'],3=>$Lang['kj_type_3']);
    tomshowsetting(array('title'=>$Lang['kj_type'],'name'=>'kj_type','value'=>$options['kj_type'],'msg'=>$Lang['kj_type_msg'],'item'=>$kj_type_item),"radio");
    tomshowsetting(array('title'=>$Lang['start_time'],'name'=>'start_time','value'=>$options['start_time'],'msg'=>$Lang['start_time_msg']),"calendar");
    tomshowsetting(array('title'=>$Lang['end_time'],'name'=>'end_time','value'=>$options['end_time'],'msg'=>$Lang['end_time_msg']),"calendar");
    tomshowsetting(array('title'=>$Lang['pic_url'],'name'=>'pic_url','value'=>$options['pic_url'],'msg'=>$Lang['pic_url_msg']),"file");
    tomshowsetting(array('title'=>$Lang['goods_price'],'name'=>'goods_price','value'=>$options['goods_price'],'msg'=>$Lang['goods_price_msg']),"input");
    tomshowsetting(array('title'=>$Lang['base_price'],'name'=>'base_price','value'=>$options['base_price'],'msg'=>$Lang['base_price_msg']),"input");
    tomshowsetting(array('title'=>$Lang['goods_num'],'name'=>'goods_num','value'=>$options['goods_num'],'msg'=>$Lang['goods_num_msg']),"input");
    tomshowsetting(array('title'=>$Lang['dh_pwd'],'name'=>'dh_pwd','value'=>$options['dh_pwd'],'msg'=>$Lang['dh_pwd_msg']),"input");
    tomshowsetting(array('title'=>$Lang['buy_url'],'name'=>'buy_url','value'=>$options['buy_url'],'msg'=>$Lang['buy_url_msg']),"input");
    tomshowsetting(array('title'=>$Lang['add_msg'],'name'=>'add_msg','value'=>$options['add_msg'],'msg'=>$Lang['add_msg_msg']),"textarea");
    tomshowsetting(array('title'=>$Lang['info'],'name'=>'info','value'=>$options['info'],'msg'=>$Lang['info_msg']),"textarea");
    tomshowsetting(array('title'=>$Lang['bk_info'],'name'=>'bk_info','value'=>$options['bk_info'],'msg'=>$Lang['bk_info_msg']),"textarea");
    tomshowsetting(array('title'=>$Lang['goodinfo'],'name'=>'goodinfo','value'=>$options['goodinfo'],'msg'=>$Lang['goodinfo_msg']),"text");
    tomshowsetting(array('title'=>$Lang['content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['content_msg']),"text");
    tomshowsetting(array('title'=>$Lang['share_title'],'name'=>'share_title','value'=>$options['share_title'],'msg'=>$Lang['share_title_msg']),"input");
    tomshowsetting(array('title'=>$Lang['share_desc'],'name'=>'share_desc','value'=>$options['share_desc'],'msg'=>$Lang['share_desc_msg']),"input");
    tomshowsetting(array('title'=>$Lang['share_logo'],'name'=>'share_logo','value'=>$options['share_logo'],'msg'=>$Lang['share_logo_msg']),"file");
    tomshowsetting(array('title'=>$Lang['guanzu_desc'],'name'=>'guanzu_desc','value'=>$options['guanzu_desc'],'msg'=>$Lang['guanzu_desc_msg']),"input");
    tomshowsetting(array('title'=>$Lang['guanzu_url'],'name'=>'guanzu_url','value'=>$options['guanzu_url'],'msg'=>$Lang['guanzu_url_msg']),"input");
    $must_gz_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(array('title'=>$Lang['must_gz'],'name'=>'must_gz','value'=>$options['must_gz'],'msg'=>$Lang['must_gz_msg'],'item'=>$must_gz_item),"radio");
    tomshowsetting(array('title'=>$Lang['ads_picurl'],'name'=>'ads_picurl','value'=>$options['ads_picurl'],'msg'=>$Lang['ads_picurl_msg']),"file");
    tomshowsetting(array('title'=>$Lang['ads_link'],'name'=>'ads_link','value'=>$options['ads_link'],'msg'=>$Lang['ads_link_msg']),"input");
    tomshowsetting(array('title'=>$Lang['virtual_clicks'],'name'=>'virtual_clicks','value'=>$options['virtual_clicks'],'msg'=>$Lang['virtual_clicks_msg']),"input");
    tomshowsetting(array('title'=>$Lang['mp3_link'],'name'=>'mp3_link','value'=>$options['mp3_link'],'msg'=>$Lang['mp3_link_msg']),"input");
    tomshowsetting(array('title'=>$Lang['paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['kanjia_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['kanjia_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['kanjia_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['kanjia_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['kanjia_edit'],"",true);
    }else{
        tomshownavli($Lang['kanjia_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['kanjia_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
