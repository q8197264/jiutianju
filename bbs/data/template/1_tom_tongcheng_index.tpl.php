<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html><html>
<head>
<?php if($isGbk) { ?>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<?php } else { ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<title><?php echo $__SitesInfo['name'];?></title>
<link rel="stylesheet" href="source/plugin/tom_tongcheng/images/style.css?v=<?php echo $cssJsVersion;?>" />
<link href="source/plugin/tom_tongcheng/images/swiper.min.css" rel="stylesheet" />
<script src="source/plugin/tom_tongcheng/images/jquery.min-2.1.3.js" type="text/javascript"></script>
<script src="source/plugin/tom_tongcheng/images/swiper.min.js" type="text/javascript"></script>
<script src="source/plugin/tom_tongcheng/images/global.js?v=<?php echo $cssJsVersion;?>" type="text/javascript"></script>
<script src="source/plugin/tom_tongcheng/images/layer_mobile/layer.js?v=<?php echo $cssJsVersion;?>" type="text/javascript"></script>
<script type="text/javascript">
    var commonjspath = 'source/plugin/tom_tongcheng/images';
</script>
<script src="source/plugin/tom_tongcheng/images/common.js?v=<?php echo $cssJsVersion;?>" type="text/javascript" type="text/javascript"></script>
<style>
    .layui-m-layer0 .layui-m-layerchild{width: 70%;}
    .layui-m-layercont{padding: 5px 3px;}
</style>
</head>
<body class="body-bg">
<header class="header header-index">
    <section class="wrap header-bg">
        <section class="logo">
             <img src="<?php echo $logoSrc;?>" />
             <a><span style="position: absolute;top: 1em;margin-left: 3em;font-size: 1em;color: #FFF;right: 1em;"><?php echo $tongchengConfig['area_name'];?></span></a>
        </section>
        <form class="sec-search flex" id="search_form" onsubmit="return false;">
            <section class="sec-input">
                <input type="text" placeholder="找顺风车，找工作，租房子" name="keyword" value="" />
            </section>
            <section class="search-btn">
                <input type="hidden" name="formhash" value="<?php echo $formhash;?>">
                <button class="btn id_search_btn" type="button">搜索</button>
            </section>
        </form>
       <script>
        $(".id_search_btn").click( function (){ 
            $.ajax({
                type: "GET",
                url: "<?php echo $searchUrl;?>",
                data: $('#search_form').serialize(),
                success: function(msg){
                    window.location = msg;
                }
            });
        });
        </script>
    </section>
</header>
<section class="mainer">
   <section class="wrap">
        <section class="nav-list">
             <section class="nav-list-tit">
                  <section class="nav-list-tit-l">
                       <div class="dt_xh">
                            <span>本站浏览：<?php echo $clicksNumTxt;?><?php if($clicksNum > 10000) { ?>万<?php } ?> &nbsp; 发布总量：<?php echo $fabuNumTxt;?><?php if($fabuNum > 10000) { ?>万<?php } ?></span>
                       </div>
                  </section>
             </section>
             <section class="nav-li swiper-container swiper-container-nav">
                <div class="swiper-wrapper">
                    <ul class="swiper-slide">
                    <?php if(is_array($navList)) foreach($navList as $key => $val) { ?>                        <li>
                            <a href="<?php echo $val['link'];?>">
                              <section class="nav-li-pic">
                                    <img src="<?php echo $val['picurl'];?>"/>
                              </section>
                              <p><?php echo $val['title'];?></p>
                            </a>
                        </li>
                        <?php if(($navCount> 10 && $val['i'] == 10) || ($navCount> 20 && $val['i'] == 20) || ($navCount> 30 && $val['i'] == 30) || ($navCount> 40 && $val['i'] == 40) ) { ?>
                        </ul>
                        <ul class="swiper-slide">
                        <?php } ?>
                    <?php } ?> 
                    </ul>
                </div>
                  <?php if($navCount> 10  ) { ?>
                 <div class="swiper-pagination swiper-pagination-nav" style="bottom: 5px;"></div>
                 <?php } ?>
                <section class="clear"></section>
             </section>
        </section>
        <section class="index-scroll-ad">
             <ul>
                 <?php if(is_array($topnewsList)) foreach($topnewsList as $key => $val) { ?>                  <li><a href="<?php echo $val['link'];?>" target="_blank"><?php echo $val['title'];?></a></li>
                  <?php } ?> 
             </ul>
        </section>
       <?php if($focuspicList) { ?>
       <div class="clear5"></div>
       <div class="swiper-container swiper-container-focuspic">
            <div class="swiper-wrapper">
                <?php if(is_array($focuspicList)) foreach($focuspicList as $key => $val) { ?>                <div class="swiper-slide">
                    <a href="<?php echo $val['link'];?>"><img src="<?php echo $val['picurl'];?>" width="100%"></a>
                </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination swiper-pagination-focuspic"></div>
        </div>
       <?php } ?>
        <div class="clear5"></div>
        <section class="tc-sec">
             <ul class="tab-navs index-navs">
                  <div class="tab-scroll" >
                       <li class="tab-nav index-nav active" onclick="indexLoadList(0);">最新信息</li>
                       <?php if(is_array($modelList)) foreach($modelList as $key => $val) { ?>                       <li class="tab-nav index-nav"><a href="javascript:void(0);" onclick="indexLoadList(<?php echo $val['id'];?>);"><?php echo $val['name'];?></a></li>
                       <?php } ?> 
                  </div>
             </ul>
             <section class="tc-sec mt0" id="index-list">
                 <div class="tcui-loadmore" style="padding:1em"><i class="tcui-loading"></i><span class="tcui-loadmore__tips">正在加载...</span></div>
             </section>
             <section style="display: none;" id="load-html">
                <div class="tcui-loadmore" style="padding:1em"><i class="tcui-loading"></i><span class="tcui-loadmore__tips">正在加载...</span></div>
             </section>
             <section  style="display: none;" id="no-list-html">
                <div class="clear10" style="height:7em;"></div>
                  <div class="tcui-loadmore tcui-loadmore_line">
                       <span class="tcui-loadmore__tips">没有信息</span>
                  </div>
                  <div class="btn-group-block">
                       <a class="id-fabu-url" href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=fabu">开始发布一条</a>
                       <div class="clear10" style="height:7em;"></div>
                  </div>
             </section>
        </section>
   </section>
</section>
<?php if($subscribeFlag==2) { ?>
<section id="subscribe">
    <div class="subscribe_box">
        <span>接收私信、信息审核通知等实时提醒</span>
        <div class="right">
            <div class="guanzu_show"><a onclick="guanzu();">关注</a></div>
            <div class="guanzu_close" onclick="hide_guanzu();">x</div>
        </div>
    </div>
</section>
<?php } ?>
<section class="pic_info id-pic-tip box_hide clearfix" style="z-index: 999;height: 2000px;position: fixed;">
<div class="pic_info_in id-pic-tip-in" style="top: 0px; height: 550px; background-image: url();"></div>
</section>
<div style="background: rgb(26, 170, 241);bottom:70px;" class="site-float" >
    <a  onclick="kefu();" style="color: #FFF;width: 25px;height: 25px;line-height: 25px;display: block;">客服</a> 
</div><?php include template('tom_tongcheng:footer'); ?><script src="source/plugin/tom_tongcheng/images/index.js" type="text/javascript"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    var swiper1 = new Swiper('.swiper-container-nav', {
        pagination: '.swiper-pagination-nav',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 100000,
        autoplayDisableOnInteraction: false
    });
    <?php if($focuspicList) { ?>
    var swiper2 = new Swiper('.swiper-container-focuspic', {
        pagination: '.swiper-pagination-focuspic',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });
    <?php } ?>
    loadList('',1);
});

$(document).ready(function(){
   $.get("<?php echo $ajaxCommonClicksUrl;?>");
   $.get("<?php echo $ajaxUpdateTopstatusUrl;?>");
   $.get("<?php echo $ajaxAutoClickUrl;?>");
   $.get("<?php echo $ajaxShenheSmsUrl;?>");
});

function kefu(){
    layer.open({
        content: '<img src="<?php echo $kefuQrcodeSrc;?>">'
        ,btn: '确认'
      });
}

function guanzu(){
    layer.open({
        content: '<img src="<?php echo $tongchengConfig['fwh_qrcode'];?>">'
        ,btn: '确认'
      });
}

function hide_guanzu(){
    $("#subscribe").hide();
}

var loadModeId = 0;
var loadPage = 1;
function indexLoadList(modelId){
    loadModeId = modelId;
    loadPage = 1;
    loadList(modelId,1);
}

var loadHtml = $("#load-html").html();
var noListHtml = $("#no-list-html").html();
var loadListStatus = 0;
function loadList(modelId,Page) {
    if(loadListStatus == 1){
        return false;
    }
    loadListStatus = 1;
    $("#index-list").html(loadHtml);
    $.ajax({
        type: "GET",
        url: "<?php echo $ajaxLoadListUrl;?>",
        data: { model_id:modelId,page:Page},
        success: function(msg){
            loadListStatus = 0;
            var data = eval('('+msg+')');
            if(data == 205){
                $("#index-list").html(noListHtml);
                $(".id-fabu-url").attr("href",'<?php echo $fabuUrl;?>'+modelId); 
                return false;
            }else{
                loadPage += 1;
                $("#index-list").html(data);
            }
        }
    });
}

$(window).scroll(function () {
    var scrollTop       = $(this).scrollTop();
    var scrollHeight    = $(document).height();
    var windowHeight    = $(this).height();
    if ((scrollTop + windowHeight) >= (scrollHeight * 0.9)) {
        scrollLoadList();
    }
});

function scrollLoadList() {
    if(loadListStatus == 1){
        return false;
    }
    if(loadPage > 50){
        return false;
    }
    loadListStatus = 1;
    $.ajax({
        type: "GET",
        url: "<?php echo $ajaxLoadListUrl;?>",
        data: { model_id:loadModeId,page:loadPage},
        success: function(msg){
            loadListStatus = 0;
            var data = eval('('+msg+')');
            if(data == 205){
                return false;
            }else{
                loadPage += 1;
                $("#index-list").append(data);
            }
        }
    });
}

$(function() {
    setInterval(function() {
        var e = $(".index-scroll-ad ul");
        e.scrollTo({
            to: e.find("li").eq(0).height(),
            durTime: 800,
            delay: 80,
            callback: function() {
                var a = e.find("li").eq(0),
                i = a.clone(!0);
                e.scrollTop(0),
                a.remove(),
                e.append(i)
            }
        })
    },
    2e3)
});

$(document).on("click", ".index-nav",function() {
    var e = $(this),
    a = e.parent();
    a.find(".active").removeClass("active"),
    e.addClass("active");
    var i = e.data("id");
});

/* list js start */
$(document).on("click", ".detail-time-icon",function() {
    var t = $(this),
    a = t.next();
    if (!a.hasClass("detail-toolbar")) {
        var id = t.data("id"),message = t.data("message"),user_id = t.data("user-id"),tel = t.data("tel"),tousu = t.data("tousu");
        a = $('<div class="detail-toolbar"><A href="'+tousu+'" rel="nofollow"><img src="source/plugin/tom_tongcheng/images/icon-report.png">投诉</A><a href="javascript:void(0);" onclick="collect('+user_id+','+id+');" class="ajax-post"><img src="source/plugin/tom_tongcheng/images/icon-collect.png">收藏</a><A href="'+message+'"><img src="source/plugin/tom_tongcheng/images/icon-message.png">私信</A><A href="tel:'+tel+'" class="ajax-get"><img src="source/plugin/tom_tongcheng/images/icon-tel.png">电话</A></div>'),
        t.after(a);
    }
    a.hasClass("active") ? a.removeClass("active") : a.addClass("active");
});
$(document).on("click",function(t) {
    var a = $(t.target);
    a.hasClass("detail-time-icon") || $(".detail-toolbar").removeClass("active");
});
$(document).on("click", ".detail-toggle,article",function() {
    var t = $(this).parent(),
    a = t.find("article"),
    i = t.find(".act-bar"),
    id = t.data("id"),
    e = i.find("img");
    $.get("<?php echo $ajaxClicksUrl;?>"+id);
    return a.attr("oldheight") ? (a.css("max-height", a.attr("oldheight") + "px"), a.removeAttr("oldheight"), t.find(".detail-toggle").show(), t.find(".act-bar").hide(), void 0) : (a.attr("oldheight", parseInt(a.css("max-height"), 10)), a.css("max-height", "none"), t.find(".detail-toggle").hide(), i.show(), e.attr("url") && e.attr("src", e.attr("url")).removeAttr("url"), !1)
});
function collect(user_id,tongcheng_id){
    $.ajax({
        type: "GET",
        url: "<?php echo $ajaxCollectUrl;?>",
        data: "user_id="+user_id+"&tongcheng_id="+tongcheng_id,
        success: function(msg){
            if(msg == '100'){
                tusi("已经收藏");
            }else if(msg == '200'){
                tusi("收藏成功");
            }else{
                tusi("收藏失败");
            }
        }
    });
}
function showPic(picurl,id){
    var photo_list = $("#photo_list_"+id).val();
    var picarr = photo_list.split('|');
    wx.previewImage({
        current: picurl,
        urls: picarr 
    });
    //$(".id-pic-tip").removeClass('box_hide');
    //$('.id-pic-tip-in').css('background-image', 'url(' + picurl + ')');
}

$(".pic_info").on("click", function(){
    $(".id-pic-tip").addClass('box_hide');
    $('.id-pic-tip-in').css('background-image', '');
});
/* list js end */
</script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wxJssdkConfig["appId"];?>',
    timestamp: <?php echo $wxJssdkConfig["timestamp"];?>,
    nonceStr: '<?php echo $wxJssdkConfig["nonceStr"];?>',
    signature: '<?php echo $wxJssdkConfig["signature"];?>',
    jsApiList: [
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'previewImage'
    ]
});
wx.ready(function () {
    wx.onMenuShareTimeline({
        title: '<?php echo $shareTitle;?>',
        link: '<?php echo $shareUrl;?>', 
        imgUrl: '<?php echo $shareLogo;?>', 
        success: function () { 
        },
        cancel: function () { 
        }
    });
    wx.onMenuShareAppMessage({
        title: '<?php echo $shareTitle;?>',
        desc: '<?php echo $shareDesc;?>',
        link: '<?php echo $shareUrl;?>',
        imgUrl: '<?php echo $shareLogo;?>',
        type: 'link',
        dataUrl: '',
        success: function () { 
        },
        cancel: function () { 
        }
    });
});
</script>
</body>
</html>