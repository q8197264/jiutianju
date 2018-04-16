<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <?php echo ($CONFIG['site']['headinfo']); ?>
        <title><?php if($config['title'])echo $config['title'];else echo $seo_title; ?></title>
        <meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
        <meta name="description" content="<?php echo ($seo_description); ?>" />
        <link href="/themes/suning/Pchome/statics/css/style.css" rel="stylesheet" type="text/css">
        <script> var BAO_PUBLIC = '__PUBLIC__'; var BAO_ROOT = '__ROOT__';</script>
        <script src="__TMPL__statics/js/jquery.js"></script>
        <!--<script src="__PUBLIC__/js/layer/layer.js"></script>-->
        <!--<script src="__TMPL__statics/js/jquery.flexslider-min.js"></script>-->
        <!--<script src="__TMPL__statics/js/js.js"></script>-->
        <!--<script src="__PUBLIC__/js/web.js"></script>-->
        <!--<script src="__TMPL__statics/js/baocms.js"></script>-->
    </head>
    <body>
        <iframe id="baocms_frm" name="baocms_frm" style="display:none;"></iframe> 
<div class="topOne">
    <div class="nr">
        <?php if(empty($MEMBER)): ?><div class="left">您好，欢迎访问<?php echo ($CONFIG["site"]["sitename"]); ?><a href="<?php echo U('pchome/passport/login');?>" class="on">登陆</a>|<a href="<?php echo U('passport/register');?>">注册</a>
        <?php else: ?>
        <div class="left">欢迎 <b style="color: red;font-size:14px;"><?php echo ($MEMBER["nickname"]); ?></b> 来到<?php echo ($CONFIG["site"]["sitename"]); ?>&nbsp;&nbsp; <a href="<?php echo U('member/index/index');?>" >个人中心</a>|<a href="<?php echo U('pchome/passport/logout');?>" >退出登录</a><?php endif; ?>
        
        
        <div class="topSm"> <span class="topSmt"><em>&nbsp;</em></span>
            <div class="topSmnr"><img src="__PUBLIC__/img/wx.png" width="90" height="90" />
                <p>扫描下载客户端</p>
            </div>
        </div>
    </div>
    <div class="right">
        <ul>
            <li class="liOne"><a class="liOneB" href="<?php echo U('member/order/index');?>" >我的订单</a></li>
            <li class="liOne"><a class="liOneA" href="javascript:void(0);">我的服务<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo U('member/order/index');?>">我的订单</a></li>
                        <li><a href="<?php echo U('member/ele/index');?>">我的外卖</a></li>
                        <li><a href="<?php echo U('member/yuyue/index');?>">我的预约</a></li>
                        <li><a href="<?php echo U('member/dianping/index');?>">我的评价</a></li>
                        <li><a href="<?php echo U('member/favorites/index');?>">我的收藏</a></li>                                    
                        <li><a href="<?php echo U('member/myactivity/index');?>">我的活动</a></li>
                        <li><a href="<?php echo U('member/life/index');?>">会员服务</a></li>
                        <li><a href="<?php echo U('member/set/nickname');?>">帐号设置</a></li>
                    </ul>
                </div>
            </li>
            <span>|</span>
            <li class="liOne liOne_visit"><a class="liOneA" href="javascript:void(0);">最近浏览<em>&nbsp;</em></a>
                <div class="list liOne_visit_pull">
                    <ul>
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
            <li class="liOne"> <a class="liOneA" href="javascript:void(0);">我是商家<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo U('shangjia/login/index');?>">商家登陆</a></li>
                        <li><a href="<?php echo U('shangjia/index/index');?>">微信营销</a></li>
                    </ul>
                </div>
            </li>
            <span>|</span>
            <li class="liOne"> <a class="liOneA" href="javascript:void(0);">快捷导航<em>&nbsp;</em></a>
                <div class="list">
                    <ul>
                        <li><a href="<?php echo U('pchome/shop/index');?>">商家列表</a></li>
                        <li><a href="<?php echo U('pchome/jifen/index');?>">积分商城</a></li>
                        <li><a href="<?php echo U('pchome/billboard/index');?>">商家榜单</a></li>
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

            //$.cookie('views', '', { expires: -1 }); 
            //$(".liOne_visit_pull ul li").remove();
            //$(".liOne_visit_pull p.empty").html("您还没有浏览记录");
        })
    });
</script>   
<div class="topTwo">
    <div class="left">
        <?php if(!empty($CONFIG['site']['logo'])): ?><h1><a href="<?php echo U('pchome/index/index');?>"><img width="215" height="65" src="__ROOT__/attachs/<?php echo ($CONFIG["site"]["logo"]); ?>" /></a></h1>
            <?php else: ?>
            <h1><a href="<?php echo U('pchome/index/index');?>"><img width="215" height="65" src="__PUBLIC__/img/logo_03.png" /></a></h1><?php endif; ?> 
        <div class="changeCity"><a href="<?php echo U('pchome/city/index');?>" class="changeCity_name"><?php echo ($city_name); ?></a>
        </div>
    </div>
    <div class="left center">
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
        	<form id="search_form"  method="post" action="<?php echo U('pchome/all/index');?>">
            <div class="selectBox"> <span class="select"  id="selectBoxInput">全部</span>
                <div  class="selectList">
                    <ul>
<li><a href="javascript:void(0);" <?php if($ctl == 'all'){?> cur='true'<?php }?> rel="<?php echo U('pchome/all/index');?>">全部</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'shop'){?> cur='true'<?php }?> rel="<?php echo U('pchome/shop/index');?>">商家</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'tuan'){?> cur='true'<?php }?>rel="<?php echo U('pchome/tuan/index');?>">抢购</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'life'){?> cur='true'<?php }?>rel="<?php echo U('pchome/life/index');?>">生活</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'mall'){?> cur='true'<?php }?>rel="<?php echo U('pchome/mall/index');?>">商品</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'tieba'){?> cur='true'<?php }?>rel="<?php echo U('pchome/tieba/index');?>">贴吧</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'news'){?> cur='true'<?php }?>rel="<?php echo U('pchome/news/index');?>">新闻</a></li>
<li><a href="javascript:void(0);" <?php if($ctl == 'community'){?> cur='true'<?php }?>rel="<?php echo U('pchome/xiaoqu/index');?>">小区</a></li>
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
    </div>
    <div class="right topTwo_b">
    	<div class="tel">
            <p>7x24客服电话</p>
        	<p class="on">13451886017</p>
        </div>
    </div>
</div>
<div class="nav">

    <div class="navList">


        <ul>

            <li class="navListAll"><!-- <img src="__TMPL__statics/images/ico_1.png" class="left"/> -->
                <!--<i class="nav-bz left"></i>--><span class="navListAllt">全部抢购分类</span>
                <!--<div class="shadowy navAll">此处显示  class "navAll" 的内容</div>-->
            </li>
            <?php $map = array('status' => 4); $navigations = D('Navigation'); $nav = $navigations ->where($map)->order(array('orderby' => 'asc'))->fetchAll(); ?>

            <?php if(is_array($nav)): $index = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$var): $mod = ($index % 2 );++$index; if(($var["parent_id"] == 0)): ?><li class="navLi"><a
                        <?php if($var["target"] == 1): ?>target="_blank"<?php endif; ?>
                        <?php if($ctl == $var['title']): ?>class="navA on"
                            <?php else: ?>
                            class="navA"<?php endif; ?>
                        title="<?php echo ($var['nav_name']); ?>" href="<?php echo ($var['url']); ?>" ><?php echo ($var['nav_name']); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

        </ul>

    </div>

</div>

<div class="clear"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.sy_flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });
        $('.hotShop_flexslider').flexslider({
            animation: "slide",
            direction: "vertical",
            easing: "swing",
            controlNav: false,
        });
    });//首页轮播js

    $(function () {
        $(".sy_buy_tab a").each(function (e) {
            $(this).mouseover(function (event) {
                $(".sy_buy_tab a").removeClass("on");
                $(this).addClass("on");
                $(".sy_buy_list_box .sy_buy_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".sy_buy_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页抢购部分结束
        $(".sy_waimai_tab li").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("li").removeClass("on");
                $(this).addClass("on");
                $(".sy_waimai_list_box .sy_waimai_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".sy_waimai_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页外卖部分结束
        $(".syShop_tab>.left a").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("a").removeClass("on");
                $(this).addClass("on");
                $(".syShop_list_box .syShop_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".syShop_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页本地商城部分结束
        $(".cityInfor_tab i").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("i").removeClass("on");
                $(this).addClass("on");
                $(".cityInfor_nr .cityInfor_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".cityInfor_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页同城信息部分结
        $(".sy_seatSwitch_tab li").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("li").removeClass("on");
                $(this).addClass("on");
                $(".sy_seatSwitch_box .sy_seatSwitch1").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".sy_seatSwitch1").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页订座部分结
        $(".sy_active_tab a").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("a").removeClass("on");
                $(this).addClass("on");
                $(".sy_active_list_box .sy_active_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".sy_active_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页活动部分结
        $(".sy_coupon_tab a").each(function (e) {
            $(this).mouseover(function (event) {
                $(this).parent().find("a").removeClass("on");
                $(this).addClass("on");
                $(".sy_coupon_list_box .sy_coupon_list").each(function (i) {
                    if (e == i)
                    {
                        $(this).parent().find(".sy_coupon_list").hide();
                        $(this).show();
                    }
                    else {
                        $(this).hide();
                    }
                });
            });
        });//首页优惠券部分结
    });
</script>
<!--首页第一部分开始-->
<div class="sy_hotgz">
    <div class="sy_flexslider">
        <ul class="slides">
            <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, closed=0 AND site_id=1 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ,0,3,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=1 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,3")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li class="list" style="background:url('__ROOT__/attachs/<?php echo ($item["photo"]); ?>') center center no-repeat;">
                     <a target="_blank" href="<?php echo ($item["link_url"]); ?>"></a>
            </li> <?php endforeach; ?>
        </ul>
    </div>
    <div class="pagewd posit_lr_box">
        <div class="left posit_l"><div class="menu_fllist2">
    <?php $i=0; ?>             
    <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item): if(($item["parent_id"]) == "0"): $i++;if($i <= 10){ ?>
        <div <?php if($i == 1): ?>class="item2 bo"<?php else: ?>class="item2"<?php endif; ?> >
            <h3>
                <div class="left"><span>&nbsp;</span><a class="menu_flt" title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo msubstr($item['cate_name'],0,2,'utf-8',false);?></a></div>
                <div class="right">
                    <?php $i2=0; ?>
                    <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == $item["cate_id"]): $i2++;if($i2 <= 2){ ?>
                        <a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo msubstr($item2['cate_name'],0,4,'utf-8',false);?></a>
                        <?php } endif; endforeach; endif; ?>
                    &gt;</div>
            </h3>
            <div style="height: 458px;" class="menu_flklist2">
                <div class="menu_fl2t"><a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo ($item["cate_name"]); ?></a></div>
                <div class="menu_fl2nr">
                    <ul>
                        <?php $k=0; ?>
                        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == $item["cate_id"]): $k++; ?>
                            <?php if($k%16 == 1): ?><li class="menu_fl2nrli">
                                    <ul> 
                                        <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li>
                                        <?php elseif($k%16 == 0): ?>
                                        <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li>
                                    </ul>
                                </li>
                                <?php else: ?>
                                <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li><?php endif; endif; endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php } endif; endforeach; endif; ?>
</div>
</div>
        <div class="right posit_r">
            <div class="sy_download_box">
                <div class="img"><img src="__TMPL__statics/images/sy_down.png" width="210" height="140" /></div>
                <div class="btn_box">
                    <a target="_blank" href="<?php echo ($CONFIG["site"]["android"]); ?>" class="btn bg_1">Andriod下载</a>
                    <a target="_blank" href="<?php echo ($CONFIG["site"]["ios"]); ?>" class="btn bg_2">IOS下载</a>
                </div>
            </div>
            <div class="sy_c1wx">
                <div class="left"><img src="__PUBLIC__/img/wx.png" width="100" height="103" /></div>
                <div class="right"><p>扫一扫体验微信O2O</p><img src="__TMPL__statics/images/sywx_03.png" width="64" height="45" /></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <ul class="sy_flsx">
        <li class="bg_1">
            <h3>热门抢购</h3>
        <?php $i=0; ?>
        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item): if(($item["is_hot"]) == "1"): $i++;if($i<10){ ?>
            <?php if($item['parent_id'] == 0): ?><a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo ($item['cate_name']); ?></a>
                <?php else: ?>
                <a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['parent_id'],'cate_id'=>$item['cate_id']));?>"><?php echo ($item['cate_name']); ?></a><?php endif; ?>
            <?php } endif; endforeach; endif; ?>
        </li>
        <li class="bg_2">
            <h3>全部区域</h3>
        <?php $i=0; ?>
        <?php if(is_array($areas)): foreach($areas as $key=>$item): $i++;if($i<=10&&$item['city_id'] == $city_id){ ?>
            <a href="<?php echo U('tuan/index',array('area'=>$item['area_id']));?>"><?php echo ($item['area_name']); ?></a>
            <?php } endforeach; endif; ?>
        </li>
        <li class="bg_3">
            <h3>热门商圈</h3>
        <?php $i=0; ?>
        <?php if(is_array($bizs)): foreach($bizs as $key=>$item): if(in_array($item['area_id'],$limit_area)&&$i<=10&&$item['is_hot']=='1'){ ?>
            <a href="<?php echo U('tuan/index',array('area'=>$item['area_id'],'business'=>$item['business_id']));?>"><?php echo ($bizs[$item['business_id']]['business_name']); ?></a>
            <?php $i++;} endforeach; endif; ?>
        </li>
    </ul>
</div>
<!--首页第一部分结束-->
<div class="pagewd" id="index-gun">
    <!--首页抢购部分开始-->
    <div class="sy_FloorBt" data="top_1" id="floor1">
        <div class="left sy_FloorBtz"><i class="ico_1"></i><span class="bt cl_1">抢购</span>
        
        
        
            <span class="sy_buy_tab">
                <?php $i=0; ?>       
                <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == "0"): $i++;if($i <= 10){ ?>
                    <a target="_blank" <?php if($i == 1) echo 'class="on"'; ?> title="<?php echo ($item2["cate_name"]); ?>抢购" href="<?php echo U('tuan/index',array('cat'=>$item2['cate_id']));?>"><?php echo ($item2["cate_name"]); ?></a>
                    <?php } endif; endforeach; endif; ?>
            </span>
        </div>
        <div class="right"><a  title="更多抢购" target="_blank" href="<?php echo U('tuan/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_buy_nr">
        <div class="left sy_buy_ad"><img src="__TMPL__statics/images/syBuy.png" /><a href="<?php echo U('tuan/nearby');?>" class="btn1"></a><a href="<?php echo U('tuan/index');?>" class="btn2"></a></div>
        <div class="right sy_buy_list_box">
        
        
         <!--团购循环开始-->
            <div class="sy_buy_list_box_bg">
                <?php $i2=0; ?>       
                <?php if(is_array($tuancates)): foreach($tuancates as $key=>$cate): if(($cate["parent_id"]) == "0"): $i2++;if($i2 <= 10){ ?>
                    <ul class="sy_buy_list" <?php if($i2 == 1) echo 'style="display:block;"'; ?>>
                        <?php $cate_ids = D('Tuancate')->getChildren($cate['cate_id']); $cate_ids = join(',',$cate_ids); ?>
                        <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Tuan,audit = 1 AND closed=0 AND city_id=$city_id AND cate_id IN ($cate_ids),600,orderby asc,sold_num desc,0,10,,"); if(!$items= $cache->get($token)){ $items = D("Tuan")->where("audit = 1 AND closed=0 AND city_id=$city_id AND cate_id IN ($cate_ids)")->order("orderby asc,sold_num desc")->limit("0,10")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                                <div class="syPub_list">
                                    <a target="_blank" title="<?php echo ($item['title']); ?>" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="200" height="112" /></a>
                                    <h3><a target="_blank" title="<?php echo ($item['title']); ?>" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>" class="overflow_clear"><?php echo msubstr($item['title'],0,15);?></a></h3>
                                    <p class="overflow_clear graycl"><?php echo msubstr($item['intro'],0,18);?></p>
                                    <hr style="border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                    <div class="btn_box">
                                        <div class="left price">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></div>
                                        <div class="right"><a target="_blank" title="<?php echo ($item['title']); ?>" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>" class="btn">立即抢购</a></div>
                                    </div>
                                </div>
                            </li> <?php endforeach; ?>
                    </ul>

                    <?php } endif; endforeach; endif; ?>

            </div>
             <!--团购循环结束-->
            
            
            
        </div>
        <div class="clear"></div>
    </div>
    <!--首页抢购部分结束-->


    <!--首页本地商城部分开始-->
    <div class="sy_FloorBt" data="top_3" id="floor3">
        <div class="left sy_FloorBtz"><i class="ico_3"></i><span class="bt cl_3">本地商城</span></div>
        <div class="right"><a target="_blank" href="<?php echo U('mall/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_cityBuy_nr">
        <div class="disct">
            <div class="left sy_cityBuy_bt bg_1"><i></i><h3>优惠专区</h3></div>
            <div class="right">
                <ul class="disct_list">

                    <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Goods,audit =1 AND closed=0 AND city_id = $city_id,orderby asc,sold_num desc,0,4,600,,"); if(!$items= $cache->get($token)){ $items = D("Goods")->where("audit =1 AND closed=0 AND city_id = $city_id")->order("orderby asc,sold_num desc")->limit("0,4")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                            <div class="syPub_list">
                                <div class="img"><a target="_blank" title="<?php echo ($item['title']); ?>" href="<?php echo U('mall/detail',array('goods_id'=>$item['goods_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="230" height="180" /><p><?php echo msubstr($item['title'],0,15);?></p></a></div>
                                <div class="btn_box">
                                    <div class="left price">¥<?php echo round($item['mall_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></div>
                                    <div class="right"><a target="_blank" title="<?php echo ($item['title']); ?>" href="<?php echo U('mall/detail',array('goods_id'=>$item['goods_id']));?>" class="btn">立即购买</a></div>
                                </div>
                            </div>
                        </li> <?php endforeach; ?>

                </ul>
            </div>
        </div>

        <div class="syGoods">
            <div class="left sy_cityBuy_bt bg_3"><i></i><h3>商品专区</h3></div>
            <div class="right syGoods_list_box">
                <ul class="syGoods_list">
                    <li>
                        <a target="_blank" title="休闲零食" href="<?php echo U('mall/index',array('cat'=>21));?>">
                            <i class="ico_1"></i>
                            <p>休闲零食</p>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="厨房用品" href="<?php echo U('mall/index',array('cat'=>14));?>">
                            <i class="ico_2"></i>
                            <p>厨房用品</p>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="家具电器" href="<?php echo U('mall/index',array('cat'=>14));?>">
                            <i class="ico_3"></i>
                            <p>家具电器</p>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="婚庆用品" href="<?php echo U('mall/index',array('cat'=>2));?>">
                            <i class="ico_4"></i>
                            <p>婚庆用品</p>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="生活用品" href="<?php echo U('mall/index',array('cat'=>6));?>">
                            <i class="ico_5"></i>
                            <p>生活用品</p>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="建材专区" href="<?php echo U('mall/index',array('cat'=>33));?>">
                            <i class="ico_6"></i>
                            <p>建材专区</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--首页本地商城部分结束-->


    <!--首页外卖部分开始-->
    <div class="sy_FloorBt" data="top_2" id="floor2">
        <div class="left sy_FloorBtz"><i class="ico_2"></i><span class="bt cl_2">外卖</span></div>
        <div class="right"><a target="_blank" href="<?php echo U('ele/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_waimai_nr">
        <div class="left">
            <ul class="sy_waimai_tab">
                <?php $elecates = D('Ele')->getEleCate();foreach($elecates as $k=>$v){ ?>
                <li <?php if(($k) == "1"): ?>class="on"<?php endif; ?>><?php echo ($v); ?></li>
                <?php } ?>
            </ul>
        </div>
        <div class="right sy_waimai_list_box">
            <?php foreach($elecates as $k=>$v){ ?>
            <ul class="sy_waimai_list" <?php if(($k) == "1"): ?>style="display:block;"<?php endif; ?>>
    <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Ele,audit = 1 AND cate like '%$k%' AND city_id=$city_id ,orderby asc,sold_num desc,0,4,600,,"); if(!$items= $cache->get($token)){ $items = D("Ele")->where("audit = 1 AND cate like '%$k%' AND city_id=$city_id ")->order("orderby asc,sold_num desc")->limit("0,4")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                        <div class="syPub_list">
                            <div class="img"><a title="<?php echo ($item["shop_name"]); ?>家外卖" target="_blank" href="<?php echo U('ele/shop',array('shop_id'=>$item['shop_id']));?>">
                            <?php $shop = D('Shop')->find($item['shop_id']); ?>
                                    <img alt="<?php echo ($item["shop_name"]); ?>家外卖" src="__ROOT__/attachs/<?php echo ($shop['photo']); ?>"  width="235" height="180" />
                                    <p><?php echo ($item["shop_name"]); ?></p></a>
                            </div>
                            <p class="black6"><span class="spxq_qgpstarBg"><span class="spxq_qgpstar spxq_qgpstar<?php echo ($shops[$item['shop_id']]['score']); ?>">

                                    </span></span><?php echo round($shops[$item['shop_id']]['score']/10,1);?>分&nbsp;&nbsp;&nbsp;月售<?php echo ($item["month_num"]); ?>单</p>
                            <p class="black6">起送:￥<?php echo round($item['since_money']/100,2);?>&nbsp;&nbsp;&nbsp;配送费:￥<?php echo round($item['logistics']/100,2);?>&nbsp;&nbsp;&nbsp;<?php echo ($item["distribution"]); ?>分钟</p>
                            <hr style="border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                            <div class="tag_box">
                                <?php if(($item["is_pay"]) == "1"): ?><i class="ico_1"></i><?php endif; ?>
                                <?php if(($item["is_new"]) == "1"): ?><i class="ico_2"></i><?php endif; ?>
                                <?php if(($item["is_fan"]) == "1"): ?><i class="ico_3"></i><?php endif; ?>
                            </div>
                        </div>
                    </li> <?php endforeach; ?>   
            </ul>
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>
    <!--首页外卖部分结束-->
    <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, closed=0 AND site_id=2 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ,0,1,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=2 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,1")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><div class="sy_adPosit"><a href="<?php echo ($item["link_url"]); ?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="1200" height="100"/></a></div> <?php endforeach; ?>
    
    <!--首页优惠券部分开始-->
    <div class="sy_FloorBt" data="top_8" id="floor8">
        <div class="left sy_FloorBtz"><i class="ico_8"></i><span class="bt cl_8">优惠券</span>
            <span class="sy_coupon_tab">
                <?php $couponcates = D('Shopcate')->fetchAll(); $i=0; foreach($couponcates as $k=>$val){ if($val['parent_id'] == 0){ $i++; ?>   

                <a href="<?php echo U('coupon/index',array('cat'=>$val['cate_id']));?>" <?php if(($i) == "1"): ?>class="on"<?php endif; ?>><?php echo ($val["cate_name"]); ?></a>
                <?php } } ?>

            </span>
        </div>
        <div class="right"><a target="_blank" href="<?php echo U('coupon/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_coupon_list_box">
        <?php $couponcates = D('Shopcate')->fetchAll(); $i=0; foreach($couponcates as $k=>$val){ if($val['parent_id'] == 0){ $i++; $cates = D('Shopcate')->getChildren($val['cate_id']); $cates = join(',',$cates); ?>   
        <ul class="sy_coupon_list" <?php if(($i) == "1"): ?>style="display:block;"<?php endif; ?>>
            <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Coupon,audit=1 AND closed=0 AND city_id=$city_id AND cate_id IN ($cates),600,downloads desc,views desc,0,5,,"); if(!$items= $cache->get($token)){ $items = D("Coupon")->where("audit=1 AND closed=0 AND city_id=$city_id AND cate_id IN ($cates)")->order("downloads desc,views desc")->limit("0,5")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                    <div class="syPub_list">
                        <a target="_blank" title='<?php echo ($item["title"]); ?>' href="<?php echo U('coupon/detail',array('coupon_id'=>$item['coupon_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="224" height="170" /></a>
                        <h3> <a target="_blank" title='<?php echo ($item["title"]); ?>' href="<?php echo U('coupon/detail',array('coupon_id'=>$item['coupon_id']));?>" class="overflow_clear"><?php echo ($item["title"]); ?></a></h3>
                        <div class="btn_box">
                            <div class="left"><p class="graycl">下载：<?php echo ($item["downloads"]); ?>次&nbsp;&nbsp;剩余：<?php echo ($item["num"]); ?>次</p></div>
                            <div class="right"> <a target="_blank" title='<?php echo ($item["title"]); ?>' href="<?php echo U('coupon/detail',array('coupon_id'=>$item['coupon_id']));?>" class="btn">下载</a></div>
                        </div>
                    </div>
                </li> <?php endforeach; ?>
        </ul>

        <?php } } ?>

    </div>
    <!--首页优惠券部分结束-->
    <!--首页订座部分开始-->
    <div class="sy_FloorBt" data="top_5" id="floor5">
        <div class="left sy_FloorBtz"><i class="ico_5"></i><span class="bt cl_5">订座</span></div>
        <div class="right"><a target="_blank" href="<?php echo U('ding/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_seat_nr">
        <div class="left sy_seat_list">
            <ul>
                <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Shop,600,is_ding=1 AND audit=1 AND closed=0 AND  city_id=$city_id,0,4,orderby asc,score desc,,"); if(!$items= $cache->get($token)){ $items = D("Shop")->where("is_ding=1 AND audit=1 AND closed=0 AND  city_id=$city_id")->order("orderby asc,score desc")->limit("0,4")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                        <div class="syPub_list">
                            <div class="img">
                                <a target="_blank" href="<?php echo U('ding/detail',array('shop_id'=>$item['shop_id']));?>">
                                    <img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="225" height="160" />
                                    <p class="overflow_clear"><?php echo ($item["addr"]); ?></p>
                                </a>
                            </div>
                            <h3><a target="_blank" href="<?php echo U('ding/detail',array('shop_id'=>$item['shop_id']));?>" class="overflow_clear"><?php echo ($item["shop_name"]); ?></a></h3>
                            <p class="graycl">价格<?php echo ($item['d1']); ?>&nbsp;&nbsp;环境<?php echo ($item['d2']); ?>&nbsp;&nbsp;服务<?php echo ($item['d3']); ?></p>
                            <hr style="border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                            <div class="btn_box">
                                <div class="left"><p class="graycl">浏览数：<?php echo ($item["view"]); ?></p></div>
                                <div class="right"><a target="_blank" href="<?php echo U('ding/detail',array('shop_id'=>$item['shop_id']));?>" class="btn">立即预订</a></div>
                            </div>
                        </div>
                    </li> <?php endforeach; ?>
            </ul>
        </div>
        <div class="right sy_seatSwitch">
            <ul class="sy_seatSwitch_tab">
                <li class="on">帮您找座位</li>
                <li>人气排行榜</li>
            </ul>
            <div class="sy_seatSwitch_box">
                <div class="sy_seatSwitch1" style="display:block;">
                    <script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
                    <form action="<?php echo U('ding/lists');?>" method="post">
                        <div class="num_box">
                            <input class="name" type="text"   name="date" value="<?php echo TODAY; ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  placeholder="日期" />
                            <select name="time" class="num">
                                <?php $cfg = D('Shopdingsetting')->getCfg(); ?>
                                <?php if(is_array($cfg)): $i = 0; $__LIST__ = $cfg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>

                            <div class="clear"></div>
                        </div>
                        <select name="area_id" class="addr">
                            <?php if(is_array($areas)): $i = 0; $__LIST__ = $areas;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if(($item["city_id"]) == $city_id): ?><option value="<?php echo ($item["area_id"]); ?>"><?php echo ($item["area_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <div class="num_box">
                            <?php $room=D('Shopdingroom')->getType(); ?>
                            <select name="number" class="num">
                                <?php if(is_array($room)): $i = 0; $__LIST__ = $room;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <input class="name" name="name" type="text" placeholder="商户名" />
                            <div class="clear"></div>
                        </div>
                        <input class="btn" type="submit" value="免费帮您订座" />
                    </form>
                </div>
                <div class="sy_seatSwitch1">
                    <ul class="hotBill">
                        <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Shop,600,is_ding=1 AND audit=1 AND closed=0 AND  city_id=$city_id,0,3,view desc,,"); if(!$items= $cache->get($token)){ $items = D("Shop")->where("is_ding=1 AND audit=1 AND closed=0 AND  city_id=$city_id")->order("view desc")->limit("0,3")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                                <a href="<?php echo U('ding/detail',array('shop_id'=>$item['shop_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="60" height="40" /></a>
                                <h3 class="overflow_clear"><a href="<?php echo U('ding/detail',array('shop_id'=>$item['shop_id']));?>"><?php echo ($item["shop_name"]); ?></a></h3>
                                <p><span class="spxq_qgpstarBg"><span class="spxq_qgpstar spxq_qgpstar<?php echo ($item["score"]); ?>"></span></span></p>
                            </li> <?php endforeach; ?>   
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!--首页订座部分结束-->
    <!--首页上门服务部分开
    <div class="sy_FloorBt" data="top_6" id="floor6">
        <div class="left sy_FloorBtz"><i class="ico_6"></i><span class="bt cl_6">上门服务</span></div>
        <div class="right"><a target="_blank" href="<?php echo U('lifeservice/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_doorSer_list">
        <ul>
            <?php $a =1; ?>
            <?php if(is_array($list)): foreach($list as $key=>$item): if($a <= 8): ?><li>
                        <a href="<?php echo U('lifeservice/detail',array('id'=>$a));?>">
                            <i class="ico_<?php echo ($a); ?>"></i>
                            <h3><?php echo ($item["t"]); ?></h3>
                            <p><?php if(empty($item['title'])): ?>暂无简介<?php else: echo bao_msubstr($item['title'],0,16,true); endif; ?></p>
                        </a>
                    </li><?php endif; ?>
                <?php $a++; endforeach; endif; ?>
        </ul>
    </div>
    <!--首页上门服务部分结束-->
    <!--首页活动部分开始-->
    <div class="sy_FloorBt" data="top_7" id="floor7">
        <div class="left sy_FloorBtz"><i class="ico_7"></i><span class="bt cl_7">活动</span>

        </div>
        <div class="right"><a target="_blank" href="<?php echo U('huodong/index');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="sy_active_nr">
        <div class="left sy_active_ad">
            <ul>
                <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, closed=0 AND site_id=38 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ,0,3,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=38 AND  city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,3")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li><a href="<?php echo ($item["link_url"]); ?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="252" height="160" /></a></li> <?php endforeach; ?>
            </ul>
        </div>
        <div class="right sy_active_list_box">
            <ul class="sy_active_list" style="display:block;">
                <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Activity,audit=1 AND closed=0 AND city_id=$city_id,activity_id desc,0,6,600,,"); if(!$items= $cache->get($token)){ $items = D("Activity")->where("audit=1 AND closed=0 AND city_id=$city_id")->order("activity_id desc")->limit("0,6")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li>
                        <div class="syPub_list">
                            <a target="_blank" href="<?php echo U('huodong/detail',array('activity_id'=>$item['activity_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="300" height="180" /></a>
                            <h3><a target="_blank" href="<?php echo U('huodong/detail',array('activity_id'=>$item['activity_id']));?>" class="overflow_clear"><?php echo ($item["title"]); ?></a></h3>
                            <div class="btn_box">
                                <div class="left"><p class="graycl">有效期：至<?php echo ($item["end_date"]); ?> </p></div>
                                <div class="right"><a target="_blank" href="<?php echo U('huodong/detail',array('activity_id'=>$item['activity_id']));?>" class="btn">立即参加</a></div>
                            </div>
                        </div>
                    </li> <?php endforeach; ?>

            </ul>

        </div>
        <div class="clear"></div>
    </div>
    <!--首页活动部分结束-->

    <!--首页同城信息部分开始-->
    <div class="sy_FloorBt" data="top_4" id="floor4">
        <div class="left sy_FloorBtz"><i class="ico_4"></i><span class="bt cl_4">同城信息</span></div>
        <div class="right"><a target="_blank" href="<?php echo U('life/main');?>">更多&gt;&gt;</a></div>
    </div>
    <div class="cityInfor_nr">
        <ul class="cityInfor_list" style="display:block;">
            <li class="nr">
                <h3>二手</h3>
                <div class="link">
                    <div class="left img ico_1"></div>
                    <ul>
                        <li><a  href="<?php echo U('life/index',array('cat'=>1));?>">手机及配件</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>2));?>">数码产品</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>4));?>">家用电器</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>5));?>">日常用品</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>车辆</h3>
                <div class="link">
                    <div class="left img ico_2"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>21));?>">二手轿车</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>24));?>">电动车</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>25));?>">自行车</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>27));?>">摩托车/燃气车</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>房屋</h3>
                <div class="link">
                    <div class="left img ico_3"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>47));?>">租房/出租</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>48));?>">个人租房</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>49));?>">二手房出售</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>52));?>">求租/求购</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>招聘</h3>
                <div class="link">
                    <div class="left img ico_4"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>67));?>">工人/技工</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>68));?>">销售/金融</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>71));?>">人事/行政/文员</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>72));?>">营业员/促销/零售</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>服务</h3>
                <div class="link">
                    <div class="left img ico_5"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>90));?>">招商加盟</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>95));?>">房屋装修</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>94));?>">公司注册</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>108));?>">搬家服务</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>培训</h3>
                <div class="link">
                    <div class="left img ico_6"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>58));?>">中小学教育</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>59));?>">职业技能</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>60));?>">学历教育</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>61));?>">电脑培训</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>求职</h3>
                <div class="link">
                    <div class="left img ico_7"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>38));?>">全职求职意向</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>39));?>">兼职求职意向</a></li>

                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
            <li class="nr">
                <h3>兼职</h3>
                <div class="link">
                    <div class="left img ico_8"></div>
                    <ul>
                        <li><a href="<?php echo U('life/index',array('cat'=>131));?>">家教/老师</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>132));?>">派发/促销</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>134));?>">学生兼职</a></li>
                        <li><a href="<?php echo U('life/index',array('cat'=>135));?>">餐厅/服务员</a></li>
                    </ul>
                </div>
                <a href="<?php echo U('life/main');?>" class="more">more</a>
            </li>
        </ul>

    </div>
    <!--首页同城信息部分结束-->

</div>


<div class="indexpop" id="fox-food">
    <ul>
        <li><a href="#floor1"><div class="wz">抢购</div></a></li>
        <li><a href="#floor3"><div class="wz">商城</div></a></li>
        <li><a href="#floor2"><div class="wz">外卖</div></a></li>
        <li><a href="#floor8"><div class="wz">优惠</div></a></li>
        <li><a href="#floor5"><div class="wz">订座</div></a></li>
        <li><a href="#floor6"><div class="wz">上门</div></a></li>
        <li><a href="#floor7"><div class="wz">活动</div></a></li>
        <li><a href="#floor4"><div class="wz">信息</div></a></li>
    </ul>
</div>

<script src="__PUBLIC__/js/layer/layer.js"></script>
<script src="__TMPL__statics/js/jquery.flexslider-min.js"></script>
<script src="__TMPL__statics/js/js.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script src="__TMPL__statics/js/baocms.js"></script>
<script>
    $(function () {
        $("#fox-food li").each(function (e) {
            $(this).click(function (event) {
                $(".radius3").each(function (i) {
                    if (e == i)
                    {
                        $("html,body").animate({scrollTop: $(this).offset().top}, 500);
                        event.preventDefault();
                    }
                });
            });
        });
    });

    $(document).ready(function () {
        $(window).scroll(function () {
            var top = $(document).scrollTop();          //定义变量，获取滚动条的高度
            var menu = $("#fox-food");                      //定义变量，抓取#menu
            var items = $("#index-gun").find(".sy_FloorBt");    //定义变量，查找.item

            var curId = "";                             //定义变量，当前所在的楼层item #id 

            items.each(function () {
                var m = $(this);                        //定义变量，获取当前类
                var itemsTop = m.offset().top;        //定义变量，获取当前类的top偏移量
                if (top > itemsTop - 300) {
                    curId = "#" + m.attr("id");
                } else {
                    return false;
                }
            });

            //给相应的楼层设置cur,取消其他楼层的cur
            var curLink = menu.find(".cur");
            if (curId && curLink.attr("href") != curId) {
                curLink.removeClass("cur");
                menu.find("[href=" + curId + "]").addClass("cur");
            }
            // console.log(top);
        });
        /*控制*/
        $(window).scroll(function () {
            if ($(window).scrollTop() < 220) {
                $(".indexpop").css("top", "220px");
                $(".indexpop").css("bottom", "auto");
            }
            else {
                $(".indexpop").css("top", "40px");
                $(".indexpop").css("bottom", "auto");
            }
        });


    });
</script>
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