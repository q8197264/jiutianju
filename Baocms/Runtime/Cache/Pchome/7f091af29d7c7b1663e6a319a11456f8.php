<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php echo ($CONFIG['site']['headinfo']); ?>
        <title><?php if($config['title'])echo $config['title'];else echo $seo_title; ?></title>
        <meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
        <meta name="description" content="<?php echo ($seo_description); ?>" />
        <link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/themes/default/Pchome/statics/css/<?php echo ($ctl); ?>.css" />
        <script> var BAO_PUBLIC = '__PUBLIC__'; var BAO_ROOT = '__ROOT__';</script>
        <script src="__TMPL__statics/js/jquery.js"></script>
        <script src="__PUBLIC__/js/layer/layer.js"></script>
        <script src="__TMPL__statics/js/jquery.flexslider-min.js"></script>
        <script src="__TMPL__statics/js/js.js"></script>
        <script src="__PUBLIC__/js/web.js"></script>
        <script src="__TMPL__statics/js/baocms.js"></script>
    </head>
<style>
/*背景*/
.nav, .searchBox .submit, .sy_hottjJd, .cityInfor_nr .cityInfor_list .nr:hover .link .img, .topBackOn, .goods_flListA.on, .nearbuy_hotNum, .sy_sjcpBq1, .spxq_qgjjKk, .spxq_xqT li.on code, .hdxq_ljct, .qg-sp-tab span.on, .dcsy_topLi:hover .dcsy_topLiTu, .sjsy_ljzx, .dui-huan, .locaNr_serAn, .seat-check.on, .spxq_xqMapList li.on, .hdsy_Licj_l em, .hdsy_LicjA, .tBarFabu .sub_btn .btn, .dcsy_topLi.on .dcsy_topLiTu, .liveBtn, .cloudBuy_list .btn, .jfsy_spA, .jfsy_flexslider .flex-control-nav .flex-active, .spxq_xqT li.on a, .subBtn, .cloudBuy_cont_tab ul li.on, .zy_doorSer_detail .nrForm .btn, .basic-info .action .write,  .sy_coupon_tab a.on, .sales-promotion .tag-tuan, .comment_input p .pn, .goods .tm-fcs-panel span.y a, .login_btndz{ background-color: <?php echo ($color); ?>!important; }
/*文字颜色*/
.sy_FloorBtz .bt, .fontcl3, .topOne .nr .left a.on, .liOne:hover .liOneA, .spxq_qgsnum, .nearbuy_zjClear, .zixunList .wz .bt a, .spxq_pjAn, .sjsy_newsList h3, .locaTopDl a, .liOne .list ul li a:hover, .spxq_xqMapT, .spxq_table td a, .hdsy_Licj_l, .hdsy_Libms, .zixunDetail h1, .zixun_hot h3, .liveTab li,.shfw_xq_new li, .jfsy_jffzT, .jfsy_wellcome .blue, .maincl, .m-detail-main-winner-content .user, .pointcl, .liOne_visit_pull .empty a, .liOne_visit_pull .empty a, .goods .tm-price, .intro a, .comment .price{color: <?php echo ($color); ?>!important; }/*边框top上*/
.sy_FloorBt, .qg-sp-tab span.on, .zixun_hot h3, .liveTab {border-bottom: 1px solid <?php echo ($color); ?>!important;}/*边框*/
.spxq_qgjjKk, .hdxq_tgList, .liOne .list ul, .liOne_visit .liOne_visit_pull, .seat-check.on, .liveSearchLeft{border: 1px solid <?php echo ($color); ?>!important;}

/*特殊的*/
.liOne:hover .liOneA {color: <?php echo ($color); ?>; border-left: 1px solid <?php echo ($color); ?>;border-right: 1px solid <?php echo ($color); ?>!important;}
.changeCity_link:after {border-bottom: 2px solid <?php echo ($color); ?>!important;border-right: 2px solid <?php echo ($color); ?>!important;}
.spxq_xqT {border-bottom: 1px solid <?php echo ($color); ?>!important;}
.hdsy_tabLi.on a {border-top: 2px solid <?php echo ($color); ?>!important;}
.spxq_slider .flex-control-thumbs li .flex-active {border-color: <?php echo ($color); ?> !important;}
.zixunRelet { border-top: 2px solid <?php echo ($color); ?>!important;}
.sy_sjcpLi:hover {border-color: <?php echo ($color); ?>!important;}
.navListAll{background-color: #17A994;}
.topTwo .menu {width: 18%; margin-top: 10px;float: right;color: #929292;font-size: 12px;text-align: center;}
.topTwo .ment_left {float: left;width: 33%;}
.topTwo .ment_left .ment_left_img img { width: 36px;height: 36px;}
.navA {position: relative;}
.navA .hot {display: block;width: 27px;height: 18px;background: url(/themes/default/Pchome/statics/images/header-hot.gif) no-repeat center top;position: absolute;right: -5px;top: 2px;}
.mod .mod-title .current {border-bottom: 2px solid <?php echo ($color); ?>;}
.topTwo .searchBox_r .searchBox {border: 2px solid <?php echo ($color); ?>;}
.superior {border-top: <?php echo ($color); ?> 2px solid;}
.navListAll {background-color: #3ac9aa;}
.navA.on, .navA:hover { background-color: #9C1F4B;}
</style>
<style>
.anchorBL{   display:none !important;  }  
</style>



    <body>

        <iframe id="baocms_frm" name="baocms_frm" style="display:none;"></iframe>  
<div id="topAlert">

<div class="topOne">
    <div class="nr">
        <?php if(empty($MEMBER)): ?><div class="left">您好，欢迎访问<?php echo ($CONFIG["site"]["sitename"]); ?>
        <a href="javascript:void(0);" class="on login_kuaijie" id="login">登陆</a>
        <script>
         $(document).ready(function () {
           $(".login_kuaijie").click(function(){
             ajaxLogin();
           })
         })
        </script>
        |<a href="<?php echo U('passport/register');?>">注册</a>
        <?php else: ?>
        <div class="left">欢迎 <b style="color: red;font-size:14px;"><?php echo ($MEMBER["nickname"]); ?></b> 来到<?php echo ($CONFIG["site"]["sitename"]); ?>&nbsp;&nbsp; 
        <a href="<?php echo u('member/index/index');?>" class="maincl" >个人中心</a>
        <a href="<?php echo u('pchome/passport/logout');?>" class="maincl" >退出登录</a><?php endif; ?>
        <!-- <a href="<?php echo U('download/index');?>" class="topSm blackcl6">下载手机客户端</a> -->
    </div>
    <div class="right">
        <ul>
            <li class="liOne"><a class="liOneB" href="<?php echo U('member/order/index');?>" >我的订单</a></li>
            <span>|</span>
            <li class="liOne"> <a class="liOneA" href="javascript:void(0);">我是商家<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo u('shangjia/login/index');?>">商家登陆</a></li>
                    </ul>
                </div>
            </li>
            <span>|</span>			
            <li class="liOne"><a class="liOneA" href="javascript:void(0);">我的服务<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo u('member/order/index');?>">我的订单</a></li>
                        <li><a href="<?php echo u('member/ele/index');?>">我的外卖</a></li>
                        <li><a href="<?php echo u('member/yuyue/index');?>">我的预约</a></li>
                        <li><a href="<?php echo u('member/dianping/index');?>">我的评价</a></li>
                        <li><a href="<?php echo u('member/favorites/index');?>">我的收藏</a></li>                                    
                        <li><a href="<?php echo u('member/myactivity/index');?>">我的活动</a></li>
                        <li><a href="<?php echo u('member/life/index');?>">会员服务</a></li>
                        <li><a href="<?php echo u('member/set/nickname');?>">帐号设置</a></li>
                    </ul>
                </div>
            </li>
            <span>|</span>
            <li class="liOne liOne_visit"><a class="liOneA" href="javascript:void(0);">最近浏览<em>&nbsp;</em></a>
                <div class="list liOne_visit_pull">
                    <ul style="border:none !important;">
                        <?php
 $views = unserialize(cookie('views')); $views = array_reverse($views, TRUE); if($views){ foreach($views as $v){ ?>
                        <li class="liOne_visit_pull_li">
                            <a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($v["photo"]); ?>" width="80" height="50" /></a>
                            <h5><a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><?php echo ($v["title"]); ?></a></h5>
                            <div class="price_box"><a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><em class="price">￥<?php echo ($v["tuan_price"]); ?></em><span class="old_price">￥<?php echo ($v["price"]); ?></span></a></div>
                        </li>
                        <?php }?>
                    </ul>
                    <p class="empty"><a href="javascript:;" id="emptyhistory">清空最近浏览记录</a></p>
                    <?php }else{?>
                    <p class="empty">您还没有浏览记录</p>
                    <?php } ?>
                </div>
            </li>
            <span>|</span>
<!--             <li class="liOne"> <a class="liOneA" href="javascript:void(0);">我是商家<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo u('shangjia/login/index');?>">商家登陆</a></li>
                    </ul>
                </div>
            </li>
            <span>|</span> -->
            <li class="liOne"> <a class="liOneA" href="javascript:void(0);">快捷导航<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                     		<li><a href="<?php echo u('pchome/hotel/index');?>">酒店频道</a><em class="hot"></em></li>
                                <li><a href="<?php echo u('pchome/farm/index');?>">农家乐频道</a><em class="hot"></em></li>
                                <li><a href="<?php echo u('pchome/tribe/index');?>">部落频道</a><em class="hot"></em></li>
                                <li><a href="<?php echo u('pchome/huodong/index');?>">活动频道</a></li>
                                <li><a href="<?php echo u('pchome/life/index');?>">同城信息</a></li>
                                <li><a href="<?php echo u('pchome/coupon/index');?>">优惠券</a></li>
                                <li><a href="<?php echo u('pchome/jifen/index');?>">积分商城</a></li>
                                <li><a href="<?php echo u('pchome/tieba/index');?>">新版贴吧</a></li>
                                <li><a href="<?php echo u('pchome/billboard/index');?>">商家榜单</a></li>
                                 <li><a href="<?php echo u('pchome/cloud/index');?>">一元云购</a></li>
                                <li><a href="<?php echo u('pchome/xiaoqu/index');?>">智慧小区</a></li>
                                <li><a href="<?php echo u('pchome/biz/index');?>">去哪儿了</a></li>
                                <li><a href="<?php echo u('pchome/news/index');?>">文章资讯</a></li>
                                <li><a href="<?php echo u('pchome/seller/index');?>">商家新闻</a></li>
                                <li><a href="<?php echo u('pchome/appoint/index');?>">新版家政</a></li>
                                <li><a href="<?php echo u('store/index/index');?>">商户管理</a></li>        
                    </ul>
                </div>
            </li>
  
        </ul>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        $("#emptyhistory").click(function(){
            $.get("<?php echo U('tuan/emptyviews');?>",function(data){
                if(data.status == 'success'){
                    $(".liOne_visit_pull ul li").remove();
                    $(".liOne_visit_pull p.empty").html("您还没有浏览记录");
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },'json')
        })
    });
</script>   
<style>
.liOne {z-index: 999;}
.common-banner--floor {width:1200px;}
.common-banner--newtop {width:1200px; height: 60px;margin:0px auto 0; border: none;padding: 0;overflow: hidden;}
.common-banner {position: relative;text-align: center;}
.common-banner--newtop .common-banner__sheet {width: 100%;}
.common-banner--floor .color--left { left: 0;}
.common-banner--floor .color {position: absolute; width: 50%;height: 60px;margin: 0;padding: 0;border: none;}
.common-banner--floor .color--right {right: 0;}
.common-banner--floor .common-banner__link { position: absolute;top: 0;left: 50%;width: 1200px; height: 60px; margin-left: -600px;}
.common-banner--newtop .common-banner__link { display: block;z-index: 9;}
.common-banner img { vertical-align: top;}
.cf:after {display: block;clear: both;height: 0;overflow: hidden;visibility: hidden;}
.common-banner--floor .close {z-index:10;}
.common-banner .close {position: absolute;top:8px;right:8px;background:url(/themes/default/Pchome/statics/images/tp_54.png) no-repeat center center;}
.common-close--iconfont-small { padding: 8px;}
</style>



<?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, closed=0 AND site_id=66 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ,0,1,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=66 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,1")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><div class="J-hub J-banner-newtop ui-slider common-banner common-banner--newtop common-banner--floor log-mod-viewed J-banner-stamp-active" >
            <div class="common-banner__sheet cf">
                <div class="color color--left" style="background:#83d8f5"></div>
                <div class="color color--right" style="background:#83d8f5"></div>
                <a class="common-banner__link" target="_blank" href="<?php echo ($item["link_url"]); ?>" >
                     <img  src="<?php echo config_img($item['photo']);?>" width="1200" height="60" >
                </a>
            </div><a href="javascript:void(0)" class="F-glob F-glob-close common-close--iconfont-small close" title="关闭"></a>
</div> <?php endforeach; ?>
<script>
    $(document).ready(function () {
		$(".common-close--iconfont-small").click(function () {
            $(".common-banner").hide();
        });
    });
</script>
<div class="topTwo">
    <div class="left">
        <?php if(!empty($CONFIG['site']['logo'])): if(!empty($city['photo'])): ?><h1><a href="<?php echo u('pchome/index/index');?>"><img src="<?php echo config_img($city['photo']);?>" width="215" height="65" /></a></h1>
            <?php else: ?>
            <h1><a href="<?php echo u('pchome/index/index');?>"><img src="<?php echo config_img($CONFIG['site']['logo']);?>" /></a></h1><?php endif; endif; ?> 
        <div class="changeCity">
            <p class="changeCity_name"><?php echo ($city_name); ?></p>
            <a href="<?php echo u('pchome/city/index');?>" class="graycl changeCity_link">更换城市</a>
        </div>
    </div>
    <div class="right searchBox_r">
    <script>
		$(document).ready(function () {
			$(".selectList li a").click(function () {
				$("#search_form").attr('action', $(this).attr('rel'));
				$("#selectBoxInput").html($(this).html());
				$('.selectList').hide();
			});

			$(".selectList a").each(function(){
				if($(this).attr("cur")){
					$("#search_form").attr('action', $(this).attr('rel'));
					$("#selectBoxInput").html($(this).html());                                
				}
			})
		});
	</script>

        <div class="searchBox">
        	<form id="search_form"  method="post" action="<?php echo u('pchome/all/index');?>">
            <div class="selectBox"> <span class="select"  id="selectBoxInput">全部</span>
                <div  class="selectList">
                    <ul>
<li><a href="javascript:void(0);" <?php if($ctl == 'all'){?> cur='true'<?php }?> rel="<?php echo u('pchome/all/index');?>">全部</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'shop'){?> cur='true'<?php }?> rel="<?php echo u('pchome/shop/index');?>">商家</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'tuan'){?> cur='true'<?php }?>rel="<?php echo u('pchome/tuan/index');?>">抢购</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'life'){?> cur='true'<?php }?>rel="<?php echo u('pchome/life/index');?>">生活</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'mall'){?> cur='true'<?php }?>rel="<?php echo u('pchome/mall/index');?>">商品</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'news'){?> cur='true'<?php }?>rel="<?php echo u('pchome/news/index');?>">新闻</a></li>

                    </ul>        

                </div>
            </div>

            <input type="text" class="text" <?php if($ctl != ding): ?>name="keyword" value="<?php echo (($keyword)?($keyword):'输入您要搜索的内容'); ?>"<?php endif; ?> onclick="if (value == defaultValue) {
                        value = '';
                        this.style.color = '#000'
                    }"  onBlur="if (!value) {
                                value = defaultValue;
                                this.style.color = '#999'
                            }" />

            <input type="submit" class="submit" value="搜索" />
            </form>
        </div>

        <div class="hotSearch">
            <?php $a =1; ?>
            <?php  $cache = cache(array('type'=>'File','expire'=> 43200)); $token = md5("Keyword,,0,8,43200,key_id desc,,"); if(!$items= $cache->get($token)){ $items = D("Keyword")->where("")->order("key_id desc")->limit("0,8")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; if($item['type'] == 0 or $item['type'] == 1): ?><a href="<?php echo u('pchome/shop/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                <?php elseif($item['type'] == 2): ?>
                    <a href="<?php echo u('pchome/tuan/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                <?php elseif($item['type'] == 3): ?>
                    <a href="<?php echo u('pchome/life/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                <?php elseif($item['type'] == 4): ?>
                    <a href="<?php echo u('pchome/mall/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a><?php endif; ?> <?php endforeach; ?>
        </div>
    </div>

    <div class="topTwo_cart_box right" id='cart'><em class="ico"></em>购物车<span id="num" class="num"><?php echo (($cartnum)?($cartnum):'0'); ?></span>
        <div class="topTwo_cart_list_box">
            <div class="box"  id='cart_show'>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script>
$('#cart').hover(function(){
	var link = "<?php echo U('pchome/mall/goods');?>";
	$("#cart_show").load(link);
});
	
</script>







   
<link rel="stylesheet" type="text/css" href="/static/default/webuploader/webuploader.css">
<script src="/static/default/webuploader/webuploader.min.js"></script>
<style>
.main {float: none;width: 1200px;margin: auto;}
</style>
<div class="cl"></div>
<div class="main">
    <ul class="fbMenu">
        <li class="cur">1.提交资料</li>
        <li>2.等待审核</li>
        <li>3.开店成功</li>
    </ul>
    <div class="fbMain">
        <h3>基本资料</h3>
        <form method="post" action="<?php echo U('shop/apply');?>" target="baocms_frm">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th><span class="color_r">*</span>商户名称：</th>
                    <td><input type="text" name="data[shop_name]"  class="but"  /><p>商户名称是店铺的名称，显示在店铺详情，最多20个字符</p></td>
                </tr>

				  <tr>
                    <th>
                        <link href="__PUBLIC__/js/uploadify/uploadify.css" type="text/css" rel="stylesheet">
                        <script src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js"></script>
                        店铺图片：
                    </th>

                    <td>
                        <div class="tuanfabu_nr">
                            <div class="left tuanfabu_scimg">
                                <div class="scimgBox">
                                   <!-- <a  class="radius3 tuan_topbt" href="javascript:void(0);">图片上传</a>  -->
                                    <input type="hidden" name="data[photo]" value="" id="data_photo" />
                                    <input id="photo_file" name="photo_file" type="file" multiple="true" value="" />
                                </div>
                                <p>建议尺寸<?php echo ($CONFIG["attachs"]["shopphoto"]["thumb"]); ?></p>
                            </div>
                            <div class="left">
                                <div class="tuanfabu_sjimg">
                                    <img id="photo_img" width="80" height="80" src="__ROOT__/attachs/<?php echo (($detail['photo'])?($detail['photo']):'default.jpg'); ?>">
                                </div>
                            </div>
                        </div>
                    
                        <script>
                            $("#photo_file").uploadify({
                                'swf': '__PUBLIC__/js/uploadify/uploadify.swf?t=<?php echo ($nowtime); ?>',
                                'uploader': '<?php echo U("app/upload/uploadify",array("model"=>"shopphoto"));?>',
                                'cancelImg': '__PUBLIC__/js/uploadify/uploadify-cancel.png',
                                'buttonText': '上传店铺图片',
                                'fileTypeExts': '*.gif;*.jpg;*.png',
                                'queueSizeLimit': 1,
                                'onUploadSuccess': function (file, data, response) {
                                    $("#data_photo").val(data);
                                    $("#photo_img").attr('src', '__ROOT__/attachs/' + data).show();
                                }
                            });

                        </script>
                    </td>
                </tr>
                <tr>
                    <th>
                        店铺logo：
                    </th>

                    <td>
                        <div class="tuanfabu_nr">
                            <div class="left tuanfabu_scimg">
                                <div class="scimgBox">
                                    
                                    <input type="hidden" name="data[logo]" value="" id="data_logo" />
                                    <input id="logo_file" name="logo_file" type="file" multiple="true" value="" />
                                </div>
                                <p>建议尺寸<?php echo ($CONFIG["attachs"]["shoplogo"]["thumb"]); ?></p>
                            </div>
                            <div class="left">
                                <div class="tuanfabu_sjimg">
                                    <img id="logo_img" width="80" height="80" src="__ROOT__/attachs/<?php echo (($detail['photo'])?($detail['photo']):'default.jpg'); ?>">
                                </div>
                            </div>
                        </div>


                        <script>
                            $("#logo_file").uploadify({
                                'swf': '__PUBLIC__/js/uploadify/uploadify.swf?t=<?php echo ($nowtime); ?>',
                                'uploader': '<?php echo U("app/upload/uploadify",array("model"=>"shoplogo"));?>',
                                'cancelImg': '__PUBLIC__/js/uploadify/uploadify-cancel.png',
                                'buttonText': '上传店铺logo',
                                'fileTypeExts': '*.gif;*.jpg;*.png',
                                'queueSizeLimit': 1,
                                'onUploadSuccess': function (file, data, response) {
                                    $("#data_logo").val(data);
                                    $("#logo_img").attr('src', '__ROOT__/attachs/' + data).show();
                                }
                            });

                        </script>
                    </td>
                </tr>
                 
                 
                <tr>
                    <th><span class="color_r">*</span> 分类：</th>
                    <td>
                        <div class="rcleix">
                            <select name="parent_id" id="parent_id" class="selects">
                                <option value="0">请选择...</option>
                                <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"><?php echo ($var["cate_name"]); ?></option><?php endif; endforeach; endif; ?>
                            </select>
                            <select id="cate_id" name="data[cate_id]" class="selects">
                                <option value="0">请选择...</option>
                            </select>
                            <script>
                                $(document).ready(function (e) {
                                    $("#parent_id").change(function () {
                                        var url = '<?php echo U("public/shopcate",array("parent_id"=>"0000"));?>';
                                        if ($(this).val() > 0) {
                                            var url2 = url.replace('0000', $(this).val());
                                            $.get(url2, function (data) {
                                                $("#cate_id").html(data);
                                            }, 'html');
                                        }
                                    });

                                });
                            </script>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th><span class="color_r">*</span> 地区：</th>
                    <td>
                        <div class="rcleix">
                            <select name="data[city_id]" style="float: left;"  id="city_id" class="selects"></select>
                            <select name="data[area_id]" style="float: left;"  id="area_id" class="selects"></select>
                            <select name="data[business_id]"  style="float: left;" id="business_id" class="selects"></select>
                        </div>
                    </td>
                </tr>



                <script src="<?php echo U('app/datas/cab',array('name'=>'cityareas'));?>"></script>
                <script>
                                var city_id = "<?php echo ($city_id); ?>";
                                var area_id = 0;
                                var business_id = 0;
                                $(document).ready(function () {
                                    var city_str = ' <option value="0">请选择...</option>';
                                    for (a in cityareas.city) {
                                        if (city_id == cityareas.city[a].city_id) {
                                            city_str += '<option selected="selected" value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        } else {
                                            city_str += '<option value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        }
                                    }
                                    $("#city_id").html(city_str);

                                    $("#city_id").change(function () {
                                        if ($("#city_id").val() > 0) {
                                            city_id = $("#city_id").val();
                                            var area_str = ' <option value="0">请选择...</option>';
                                            for (a in cityareas.area) {
                                                if (cityareas.area[a].city_id == city_id) {
                                                    if (area_id == cityareas.area[a].area_id) {
                                                        area_str += '<option selected="selected" value="' + cityareas.area[a].area_id + '">' + cityareas.area[a].area_name + '</option>';
                                                    } else {
                                                        area_str += '<option value="' + cityareas.area[a].area_id + '">' + cityareas.area[a].area_name + '</option>';
                                                    }
                                                }
                                            }
                                            $("#area_id").html(area_str);
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        } else {
                                            $("#area_id").html('<option value="0">请选择...</option>');
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        }

                                    });

                                    if (city_id > 0) {
                                        var area_str = ' <option value="0">请选择...</option>';
                                        for (a in cityareas.area) {
                                            if (cityareas.area[a].city_id == city_id) {
                                                if (area_id == cityareas.area[a].area_id) {
                                                    area_str += '<option selected="selected" value="' + cityareas.area[a].area_id + '">' + cityareas.area[a].area_name + '</option>';
                                                } else {
                                                    area_str += '<option value="' + cityareas.area[a].area_id + '">' + cityareas.area[a].area_name + '</option>';
                                                }
                                            }
                                        }
                                        $("#area_id").html(area_str);
                                    }


                                    $("#area_id").change(function () {
                                        if ($("#area_id").val() > 0) {
                                            area_id = $("#area_id").val();
                                            var business_str = ' <option value="0">请选择...</option>';
                                            for (a in cityareas.business) {
                                                if (cityareas.business[a].area_id == area_id) {
                                                    if (business_id == cityareas.business[a].business_id) {
                                                        business_str += '<option selected="selected" value="' + cityareas.business[a].business_id + '">' + cityareas.business[a].business_name + '</option>';
                                                    } else {
                                                        business_str += '<option value="' + cityareas.business[a].business_id + '">' + cityareas.business[a].business_name + '</option>';
                                                    }
                                                }
                                            }
                                            $("#business_id").html(business_str);
                                        } else {
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        }

                                    });

                                    if (area_id > 0) {
                                        var business_str = ' <option value="0">请选择...</option>';
                                        for (a in cityareas.business) {
                                            if (cityareas.business[a].area_id == area_id) {
                                                if (business_id == cityareas.business[a].business_id) {
                                                    business_str += '<option selected="selected" value="' + cityareas.business[a].business_id + '">' + cityareas.business[a].business_name + '</option>';
                                                } else {
                                                    business_str += '<option value="' + cityareas.business[a].business_id + '">' + cityareas.business[a].business_name + '</option>';
                                                }
                                            }
                                        }
                                        $("#business_id").html(business_str);
                                    }
                                    $("#business_id").change(function () {
                                        business_id = $(this).val();
                                    });
                                });
                </script> 


                <tr>
                    <th><span class="color_r">*</span>地址：</th>
                    <td>
                        <input type="text" name="data[addr]"  class="but"/>
                    </td>
                </tr>
                <tr>
                    <th>靠近：</th>
                    <td>
                        <input type="text" name="data[near]" class="but"/>
                    </td>
                </tr>

                <tr>
                    <th >商家坐标：</th>
                    <td>

                        <span class="mr10"><input type="text" name="data[lng]" id="lng" value="<?php echo (($detail["lng"])?($detail["lng"]):''); ?>" class="mapinputs w100" />经度</span>
                        <span><input type="text" name="data[lat]" id="lat" value="<?php echo (($detail["lat"])?($detail["lat"]):''); ?>" class="w100 mapinputs" />纬度</span>

                    </td>
                </tr>
                <tr>
                    <th>地图：</th>
                    <td>
                        <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=7b92b3afff29988b6d4dbf9a00698ed8"></script>
                        <div class="top" style="width:700px; margin-bottom: 20px;">
                            <div id="r-result"><input type="text" id="suggestId" class="mapinputs" size="20" value="合肥" />请输入</div>    
                        </div>
                        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
                        <div id="allmap" style="width: 600px; height:500px;"></div>
                        <script type="text/javascript">

                            // 百度地图API功能
                            var map = new BMap.Map("allmap");
                            var lng = "<?php echo ($CONFIG['site']['lng']); ?>";
                            var lat = "<?php echo ($CONFIG['site']['lat']); ?>";
                            if (!lng && !lat) {
                                map.centerAndZoom("合肥");
                                var point = new BMap.Point(117.260852, 31.825717);
                            } else {
                                map.centerAndZoom(new BMap.Point(lng, lat), 15);
                                var point = new BMap.Point(lng, lat);
                            }
                            map.centerAndZoom(point, 15);
                            var marker = new BMap.Marker(point);  // 创建标注
                            map.clearOverlays();
                            map.addOverlay(marker);              // 将标注添加到地图中
                            marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
                            function showPoint(e) {
                                document.getElementById('lat').value = e.point.lat;
                                document.getElementById('lng').value = e.point.lng;
                                var p = new BMap.Point(e.point.lng, e.point.lat);
                                var mk = new BMap.Marker(p);
                                map.clearOverlays();
                                map.addOverlay(mk);
                                mk.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
                            }
                            map.enableScrollWheelZoom(true);
                            map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
                            map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
                            map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
                            map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
                            map.addEventListener("click", showPoint);
                            function G(id) {
                                return document.getElementById(id);
                            }

                            var ac = new BMap.Autocomplete(//建立一个自动完成的对象
                                    {"input": "suggestId"
                                        , "location": map
                                    });

                            ac.addEventListener("onhighlight", function (e) {  //鼠标放在下拉列表上的事件
                                var str = "";
                                var _value = e.fromitem.value;
                                var value = "";
                                if (e.fromitem.index > -1) {
                                    value = _value.province + _value.city + _value.district + _value.street + _value.business;
                                }
                                str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

                                value = "";
                                if (e.toitem.index > -1) {
                                    _value = e.toitem.value;
                                    value = _value.province + _value.city + _value.district + _value.street + _value.business;
                                }
                                str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
                                G("searchResultPanel").innerHTML = str;
                            });

                            var myValue;
                            ac.addEventListener("onconfirm", function (e) {    //鼠标点击下拉列表后的事件
                                var _value = e.item.value;
                                myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
                                G("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

                                setPlace();
                            });


                            function setPlace() {
                                map.clearOverlays();    //清除地图上所有覆盖物
                                function myFun() {
                                    var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                                    map.centerAndZoom(pp, 18);
                                    map.addOverlay(new BMap.Marker(pp));    //添加标注
                                }
                                var local = new BMap.LocalSearch(map, {//智能搜索
                                    onSearchComplete: myFun
                                });
                                local.search(myValue);
                            }

                        </script>
                    </td>
                </tr>
                <tr>
                    <th><span class="color_r">*</span>电话：</th>
                    <td><input type="text" name="data[tel]" class="but"/></td>
                </tr>
                <tr>
                    <th><span class="color_r">*</span>联系人：</th>
                    <td><input type="text" name="data[contact]" class="but"/></td>
                </tr>
                <tr>
                    <th><span class="color_r">*</span>营业时间：</th>
                    <td width="859"><input type="text" name="data[business_time]"  class="but"  /><p>营业时间为商户经营时间设置，最多50个字符</p></td>
                </tr>
                <tr>
                    <th><span class="color_r">*</span>商铺描述：</th>
                    <td>
                        <textarea name="details" cols="100" rows="10" style="border:1px #dddddd solid;"></textarea>
                        <p style="float: none;">商铺描述信息，最多300个字符</p>
                    </td>
                </tr>
                <tr>
                    <th>验证码：</th>

                    <td>
                        <input type="text" name="yzm" class="yz" value="" />
                        <span class="yzm_code" rel="apply_code"><img  id="apply_code" src="__ROOT__/index.php?g=app&m=verify&a=index&mt=<?php echo time();?>" /></span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="btn_box"><input type="submit" class="aniu" value="立即开店"/></div>
                    </td>
                </tr>
            </table>
        </form>
    </div>

</div>
<div class="cl"></div>
<!--main end-->
<div class="footerOut">
    <?php if($ctl == index): ?><div class="foot_yqlj">
            友情链接：
            <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Links,audit=1 AND colsed=0 AND city_id=$city_id,0,10,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Links")->where("audit=1 AND colsed=0 AND city_id=$city_id")->order("orderby asc")->limit("0,10")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><a target="_blank" href="<?php echo ($item["link_url"]); ?>"><?php echo ($item["link_name"]); ?></a> <?php endforeach; ?>
            <a target="_blank" href="<?php echo U('Pchome/public/apply_link');?>">申请链接</a>
        </div><?php endif; ?>

    <div class="footer">
        <div class="footNav">
            <div class="left">
                <div class="footNavLi">
                    <ul>

                    	<li class="footerLi foot_contact">
                            <h3><i class="ico_1"></i>联系我们</h3>
                			<div class="nr">
                            	<p>客服电话：<b class="fontcl3"><?php echo ($CONFIG["site"]["tel"]); ?></b></p>
                                <p class="graycl">免费长途</p>
                                <p>在线客服：<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($CONFIG["site"]["qq"]); ?>&site=<?php echo ($CONFIG["site"]["host"]); ?>&menu=yes"><img src="__TMPL__statics/images/foot_btn.png"/></a></p>
                                <p>工作时间：周一至周日9:00-22:00</p>
                                <p class="graycl">法定节假日除外</p>
                            </div>
                        </li>

                        <li class="footerLi">
                            <h3><i class="ico_2"></i>公司信息</h3>
                            <ul class="list">
                                <li><a target="_blank" title="关于我们" href="<?php echo U('pchome/article/system',array('content_id'=>1));?>">关于我们</a></li>
                                <li><a target="_blank" title="联系我们" href="<?php echo U('pchome/article/system',array('content_id'=>3));?>">联系我们</a></li>
                                <li><a target="_blank" title="人才招聘" href="<?php echo U('pchome/article/system',array('content_id'=>2));?>">人才招聘</a></li>
                                <li><a target="_blank" title="免责声明" href="<?php echo U('pchome/article/system',array('content_id'=>6));?>">免责声明</a></li>
                            </ul>
                        </li>

                        <li class="footerLi">
                            <h3><i class="ico_3"></i>商户合作</h3>
                            <ul class="list">
                                <li><a href="<?php echo U('pchome/shop/apply');?>">商家入驻</a></li>
                                <li><a target="_blank" title="广告合作" href="<?php echo U('pchome/article/system',array('content_id'=>5));?>">广告合作</a></li>
                                <li><a href="<?php echo U('pchome/shangjia/index/index');?>">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="footerLi">
                            <h3><i class="ico_4"></i>用户帮助</h3>
                            <ul class="list">
                                <li><a target="_blank" title="服务协议" href="<?php echo U('pchome/article/system',array('content_id'=>7));?>">服务协议</a></li>
                                <li><a target="_blank" title="退款承诺" href="/">退款承诺</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="right"><p>扫一扫加关注</p><img src="__ROOT__/attachs/<?php echo ($CONFIG["site"]["wxcode"]); ?>" width="149" height="149" /></div>
        </div>
    </div>

	<div class="footerCopy">copyright 2013-2113 <?php echo ($_SERVER['HTTP_HOST']); ?> All Rights Reserved <?php echo ($CONFIG["site"]["sitename"]); ?>版权所有 - <?php echo ($CONFIG["site"]["icp"]); ?> <?php echo ($CONFIG["site"]["tongji"]); ?></div>
</div>  

<div class="topUp">
    <ul>
    	<li class="kefu"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($CONFIG["site"]["qq"]); ?>&site=<?php echo ($CONFIG["site"]["host"]); ?>&menu=yes"><div class="kefu_open maincl">在线客服</div></a></li>
        <li class="topBack"><div class="topBackOn">回到<br />顶部</div></li>
        <li class="topUpWx"><div class="topUpWxk"><img src="__ROOT__/attachs/<?php echo ($CONFIG["site"]["wxcode"]); ?>" width="149" height="149" /><p class="maincl">扫描二维码关注微信</p></div></li>
    </ul>
</div>



<script>
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 100) {
                $(".topUp").show();
                $(".indexpop").show();
            } else {
                $(".topUp").hide();
                $(".indexpop").hide();

            }

            var ctl = "<?php echo ($ctl); ?>";
            if(ctl == 'coupon'){
                if ($(window).scrollTop() > 665) {
                    $(".spxq_xqT2").show();
                } else {
                    $(".spxq_xqT2").hide();
                }

            }else{
                if ($(window).scrollTop() > '<?php echo ($height_num); ?>') {
                    $(".spxq_xqT2").show();
                } else {
                    $(".spxq_xqT2").hide();
                }
            }
        });

        $(".topBack").click(function () {
            $("html,body").animate({scrollTop: 0}, 200);
        });

		$(window).scroll(function(){
			var top = $(document).scrollTop();          //定义变量，获取滚动条的高度
			var menu = $(".topUp");                      //定义变量，抓取topUp
			var items = $(".footerOut");    //定义变量，查找footerOut 
			items.each(function(){
				var m=$(this);
				var itemsTop = m.offset().top;      //定义变量，获取当前类的top偏移量
				if(itemsTop-360 <= top-360){
					menu.css('bottom','360px');
					menu.css('top','auto');
				}else{
					menu.css('bottom','0px');
					menu.css('top','auto');
				}
			});
		});
    });
</script>
</body>
</html>