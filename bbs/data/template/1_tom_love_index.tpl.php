<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html>
<head>
<?php if($isGbk) { ?>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<?php } else { ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
<title><?php echo $jyConfig['plugin_name'];?></title>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="source/plugin/tom_love/images/style.css?v=<?php echo $cssJsVersion;?>" rel="stylesheet" type="text/css">
<link href="source/plugin/tom_love/images/index.css?v=<?php echo $cssJsVersion;?>" rel="stylesheet" type="text/css">
<link href="source/plugin/tom_love/images/swiper.min.css" rel="stylesheet" />
<script src="source/plugin/tom_love/images/swiper.min.js" type="text/javascript"></script>
<script src="source/plugin/tom_love/images/jquery.js" type="text/javascript"></script>
<script type="text/javascript">var commonjspath = 'source/plugin/tom_love/images';</script>
<script src="source/plugin/tom_love/images/common.js?v=<?php echo $cssJsVersion;?>" type="text/javascript"></script>
<script type="text/javascript">var usiteurl = "<?php echo $uSiteUrl;?>";var plugin_id="tom_love";var check_r="1";</script>
<base target="_self">
</head>
<body id="index_body">
<section id="header">
<div class="header-title clearfix">
<div class="title">
<img src="<?php echo $jyConfig['plugin_logo'];?>">
<span><?php echo $jyConfig['plugin_name'];?></span>
</div>
<div class="count">
<span class="ct">会员数 <span> <?php echo $userAllCount;?></span></span>
<span class="ct">浏览量 <span> <?php echo $clicksNumTxt;?><?php if($clicksNum > 10000) { ?>万<?php } ?></span></span>
</div>
<div class="guanzu"><a id="guanzu" href="javascript:void(0);">关注</a></div>
</div>
<!--<div class="header-term clearfix">
<div class="fl-term"></div>
<div class="fr-term"></div>
</div>-->
    <?php if($slideList) { ?>
<div class="slide-box swiper-container">
<div class="slide-list swiper-wrapper clearfix">
            <?php if(is_array($slideList)) foreach($slideList as $key => $value) { ?><div class="item-area swiper-slide">
                <a href="<?php echo $value['link'];?>">
                	<img src="<?php echo $value['picurl'];?>" title="<?php echo $value['title'];?>">
                </a>
            </div>
            <?php } ?>
</div>
        <div class="swiper-pagination"></div>
</div>
    <?php } ?>
</section>
<section id="nav">
<div class="nav-list clearfix">
<a href="plugin.php?id=tom_love&amp;mod=search&amp;act=list&amp;sex=2">
<img src="source/plugin/tom_love/images/Bzhaonvy.png">
<h6>找女友</h6>
</a>
<a href="plugin.php?id=tom_love&amp;mod=search&amp;act=list&amp;sex=1">
<img src="source/plugin/tom_love/images/Bzhaony.png">
<h6>找男友</h6>
</a>
<a href="plugin.php?id=tom_love&amp;mod=search&amp;act=list&amp;renzheng=1">
<img src="source/plugin/tom_love/images/Brenzhhy.png">
<h6>认证会员</h6>
</a>
<a href="plugin.php?id=tom_love&amp;mod=phb">
<img src="source/plugin/tom_love/images/Bpaih.png">
<h6>排行榜</h6>
</a>
<a href="plugin.php?id=tom_love&amp;mod=shuoshuo">
<img src="source/plugin/tom_love/images/Bjiaoy.png">
<h6>交友广场</h6>
</a>
</div>
</section>
<section id="main">
<div class="marquee">
<div class="title">
<marquee direction="left">
<a href="plugin.php?id=tom_love&amp;mod=article&amp;aid=3"><?php echo $jyConfig['index_gonggao_msg'];?></a>
</marquee>
</div>
</div>
    <?php if($newUserList) { ?>
<div class="newly-bar">
<div class="newly-area clearfix"><?php if(is_array($newUserList)) foreach($newUserList as $key => $value) { ?>            <div class="added <?php if($value['sex'] == 1) { ?>boy<?php } else { ?>girl<?php } ?>">
<div class="xq clearfix">
                    <a href="plugin.php?id=tom_love&amp;mod=info&amp;uid=<?php echo $value['id'];?>">
                        <div class="portrait"><img src="<?php echo $value['avatar'];?>" /></div>
                        <div class="xinxi">
                            <div class="user-name"><span class="sex"></span><span><?php echo $value['nickname'];?></span><?php if($value['renzheng'] == 1) { ?><span class="rengzheng"></span><?php } if($value['vip_id'] == 1) { ?><span class="vp"></span><?php } ?></div>
                            <div class="user-xq girl">
                                <?php if($value['age'] != '') { ?><span><?php echo $value['age'];?>岁</span><?php } if($value['friend'] == 1) { ?><span>交友</span><?php } if($value['marriage'] == 1) { ?><span>婚恋</span><?php } ?>
                            </div>
                        <!--<div class="user-term claerfix">
                                <span></span><span></span>
                            </div>-->
                            <div class="user-dbai"><?php echo $value['describe'];?></div>
                            <div class="new"></div>
                        </div>
                    </a>
</div>
<!--<div class="status clearfix">
<div class="crush">
<a class="" href="javascript:void(0);"></a>
</div>
<div class="chat">
<a class="" href="javascript:void(0);"></a>
</div>
</div>-->
</div>
<?php } ?>
</div>
</div>
    <?php } ?>
    <?php if($jyConfig['index_adbox_1']) { ?>
    <div class="guangao_list clearfix">
        <div class="guangao_list_box clearfix">
            <?php echo $jyConfig['index_adbox_1'];?>
        </div>
    </div>
    <?php } ?>
<div class="wonderful">
<div class="wonder-title clearfix">
<span class="w-left">精彩话题</span>
<a href="plugin.php?id=tom_love&amp;mod=shuoshuo" class="w-right">更多话题&gt;</a>
</div>
<div class="wonder-content"><?php if(is_array($ssList)) foreach($ssList as $key => $value) { ?>            <div class="human-list clearfix">
                <a href="plugin.php?id=tom_love&amp;mod=shuoshuo">
                    <div class="toux"><img src="<?php echo $value['userinfo']['avatar'];?>"></div>
                    <div class="content">
                        <div class="u-name"><?php if($value['userinfo']['sex'] == 1) { ?><span class="man"> </span><?php } else { ?><span class="woman"> </span><?php } ?><?php echo $value['userinfo']['nickname'];?>&nbsp;<?php if($value['userinfo']['renzheng'] == 1) { ?><img src="source/plugin/tom_love/images/Zhuyrenzhb.png" width="15" height="16"><?php } ?>&nbsp;<?php if($value['userinfo']['vip_id'] == 1) { ?><img src="source/plugin/tom_love/images/vip.png" width="20" height="12"><?php } ?><span class="date-time"><?php echo dgmdate($value[ss_time], 'u');?></span></div>
                        <div class="u-xy"><?php echo $value['content'];?></div>
                    <!--<div class="u-xz">
                            <a class="anlian" href="javascript:void(0);"></a>
                            <a class="chat" href="javascript:void(0);"></a>
                        </div>-->
                    </div>
                </a>
</div>
            <?php } ?>
</div>
</div>
<?php if($sexVipList) { ?>
<div class="match">
<div class="match-title clearfix">
<span class="fate">缘分匹配</span>
<a href="javascript:void(0);" class="refresh" id="refresh">换一批</a>
</div>
<div class="match-list clearfix" id="match-list">
            <?php if(is_array($sexVipList)) foreach($sexVipList as $key => $value) { ?>            <div class="propmt-bar">
<a class="propmt" href="plugin.php?id=tom_love&amp;mod=info&amp;uid=<?php echo $value['id'];?>">
<div class="p-img">
<img src="<?php echo $value['avatar'];?>">
<div class="map"></div>
</div>
<p class="p-name"><?php echo $value['nickname'];?></p>
<p class="p-xinxi"><?php if($value['age']) { ?><?php echo $value['age'];?>岁<?php } ?> <?php echo $value['height'];?>cm</p>
</a>
</div>
            <?php } ?>
</div>
</div>
    <?php } ?>
<div class="vip">
<div class="vip-title">
<span class="remoed">推荐会员</span>
<a href="plugin.php?id=tom_love&amp;mod=rec">我要上首页</a>
</div>
<div class="vip-list">
            <?php if(is_array($userList)) foreach($userList as $key => $value) { ?><div class="renwu <?php if($value['sex'] == 1) { ?>boy<?php } else { ?>girl<?php } ?> clearfix">
<div class="photo"><a href="plugin.php?id=tom_love&amp;mod=info&amp;uid=<?php echo $value['id'];?>"><img src="<?php echo $value['avatar'];?>"></a></div>
<div class="v-xq">
                    <a href="plugin.php?id=tom_love&amp;mod=info&amp;uid=<?php echo $value['id'];?>">
                        <div class="v-name">
                            <span class="sex"><?php if($value['sex'] == 1) { ?>♂<?php } else { ?>♀<?php } ?></span>
                            <span class="name"><?php echo $value['nickname'];?></span>
                            <?php if($value['renzheng'] == 1) { ?>
                                <span class="rengzheng"></span>
                            <?php } ?>
                            <?php if($value['vip_id'] == 1) { ?>
                                <span class="vp"></span>
                            <?php } ?>
                            <span class="tp"><?php echo $value['pic_num'];?></span>
                        </div>
                        <div class="v-xinxi">
                            <?php if($value['age']) { ?>
                            <span><?php echo $value['age'];?>岁</span>
                            <?php } ?>
                            <?php if($value['friend'] == 1) { ?>
                            <span>交友</span>
                            <?php } ?>
                            <?php if($value['marriage'] == 1) { ?>
                            <span>婚恋</span>
                            <?php } ?>
                        </div>
                        <!--<div class="v-tj">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>-->
                        <div class="v-dubai"><?php echo $value['describe'];?></div>
                    </a>
<!--<div class="v-chat">
<a class="anlian" href="javascript:void(0);"></a>
<a class="chat" href="javascript:void(0);"></a>
</div>-->
</div>
</div>
<?php } ?>
</div> 
        <div id="m2" class="more" style="display: none;">
            <img src="source/plugin/tom_love/images/loading.gif" style="display:inline;">正在加载...
        </div>
        <div id="m3" class="more" style="display:none">没有更多数据...</div>
</div>
</section>
<?php if($jyConfig['index_adbox_2']) { ?>
<section class="guangao_list clearfix">
<div class="guangao_list_box clearfix">
        <?php echo $jyConfig['index_adbox_2'];?>
    </div>
</section>
<?php } ?>
<section id="code" class="box_hide">
<div class="code-bar">
<div class="ewcode"><img src="<?php echo $jyConfig['fuwuhao_qrcode'];?>"></div>
<div class="ew"><img src="source/plugin/tom_love/images/wenzi.png"></div>
<div class="code_close"></div>
</div>
</section>
<!-- footer start-->
<section class="footer_block clearfix"></section>
<footer class="footer_main clearfix">
<ul class="clearfix">
        <li class="footer_nav_index2"><a href="plugin.php?id=tom_love&amp;mod=index&amp;prand=<?php echo $prand;?>"><span class="nav2">缘 份</span></a></li>
        <li class="footer_nav_search1"><a href="plugin.php?id=tom_love&amp;mod=search&amp;act=list&amp;prand=<?php echo $prand;?>"><span class="nav1">搜 索</span></a></li>
        <li class="footer_nav_ss1"><a href="plugin.php?id=tom_love&amp;mod=shuoshuo&amp;prand=<?php echo $prand;?>"><span class="nav1">交友广场</span></a></li>
        <li class="<?php echo $footerNavSmsClass;?>"><a href="plugin.php?id=tom_love&amp;mod=sms&amp;prand=<?php echo $prand;?>"><span class="nav1">消 息</span></a></li>
        <li class="footer_nav_my1"><a href="plugin.php?id=tom_love&amp;mod=my&amp;prand=<?php echo $prand;?>"><span class="nav1">我</span></a></li>
    </ul>
</footer>
<!-- footer end-->
<script>
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideToolbar');
});
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" type="text/javascript"></script>
<script src="source/plugin/tom_love/images/index.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });
    $("body").append("<i id='go-top'></i>");
    var o = 1500;
$(window).scroll(function() {
$("#go-top").css($(window).scrollTop() > o ? {
opacity: 1
}: {
opacity: 0
});
});
$("#go-top").on("click",
function() {
$(window).scrollTop(0);
});
})

$('#guanzu').click(function(){
$('#code').removeClass('box_hide');
})
$('.code_close').click(function(){
$('#code').addClass('box_hide');
})

var flag = 1;
var pageIndex = 2;
function loadMore() {
    
    if(pageIndex > 50){
        tusi("最多加载50页");
    }
    
    if(flag == 1) {
        flag = 0;
        $("#m2").show();
        $.ajax({
            type: "GET",
            url: "<?php echo $recommendLoadMoreUrl;?>",
            data: { act:"loadMoreVip",loadPage:pageIndex},
            success: function(msg){
                var data = eval('('+msg+')');
                if(data == 205){
                    $("#m2").hide();
                    $("#m3").show();
                    return false;
                }else{
                    $("#m2").hide();
                    pageIndex += 1;
                    $(".vip-list").append(data);
                    flag = 1;
                }
            }
        })
    }
}
$(window).scroll(function () {
    var scrollTop       = $(this).scrollTop();
    var scrollHeight    = $(document).height();
    var windowHeight    = $(this).height();
    if ((scrollTop + windowHeight) >= (scrollHeight * 0.9)) {
        loadMore();
    }
});

var sexStart = "<?php echo $sexStart;?>";
sexStart = sexStart * 1;
$('#refresh').click(function(){
    var start = $("#sexStart").val();
    if(start){
        sexStart = start;
    }
    $.ajax({
        type: "GET",
        url: "<?php echo $refreshVipUrl;?>",
        data: {act:"sexRefresh",sexStart:sexStart},
        success: function(msg){
            var data = eval('('+msg+')');
            if(data == 205){
                tusi("出现未知错误");
            }else if(data == 206){
                tusi("已无更多");
            }else{
                $('#match-list').html(data);
            }
        } 
    })
})

</script>
<script type="text/javascript">
$(function() {
    FastClick.attach(document.body);
});
$(document).ready(function(){
  $.get("<?php echo $ajaxClicksUrl;?>");
});
wx.config({
    debug: false,
    appId: '<?php echo $wxJssdkConfig["appId"];?>',
    timestamp: <?php echo $wxJssdkConfig["timestamp"];?>,
    nonceStr: '<?php echo $wxJssdkConfig["nonceStr"];?>',
    signature: '<?php echo $wxJssdkConfig["signature"];?>',
    jsApiList: [
      'onMenuShareTimeline',
      'onMenuShareAppMessage'
    ]
});
wx.ready(function () {
    wx.onMenuShareTimeline({
        title: '<?php echo $shareTitle;?>',
        link: '<?php echo $shareUrl;?>', 
        imgUrl: '<?php echo $shareLogo;?>', 
        success: function () { 
            $.get("<?php echo $shareAjaxUrl;?>");
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
            $.get("<?php echo $shareAjaxUrl;?>");
        },
        cancel: function () { 
        }
    });
});
</script>
</body>
</html>