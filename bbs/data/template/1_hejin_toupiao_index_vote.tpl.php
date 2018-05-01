<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta charset="<?php echo $charset;?>">
    <title><?php echo stripslashes($vote['title']);?></title>
    <meta name="description" content="<?php echo stripslashes($vote['content']);?>v29">
    <link rel="stylesheet" href="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/touch.css">    
<script src="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/jquery-2.1.3.min.js" type="text/javascript"></script>

                 <script src="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/app.js" type="text/javascript"></script>
                 <script src="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/jquery.masonry.min.js" type="text/javascript"></script>
  <style>
.slider{display:none;}
.focus span{width:5px;height:5px;margin-left:5px;border-radius:50%;background:#CDCDCD;font-size:0}
.focus span.current{background:red;}
</style>
<?php if($hejintoupiao['hjtp_musicurl']) { ?>
<style>
            /*音乐图标*/
            #audio_btn {
                position: absolute;
                right: 10px;
                top: 28px;
                z-index: 200;
                display: none;
                width: 50px;
                height: 50px;
                background-repeat: no-repeat;
                cursor: pointer;
            }
            .loading_background {
                background-image: url(<?php echo HEJIN_PATH;?>public/music/music_loading.gif);
                background-size: 30px 30px;
                opacity: 0.5;
                background-position: center center;
            }
            .loading_yinfu {
                position: absolute;
                left: 10px;
                top: 10px;
                width: 30px;
                height: 30px;
                background-image: url(<?php echo HEJIN_PATH;?>public/music/music_yinfu.png);
                background-repeat: no-repeat;
                background-position: center center;
            }
            .play_yinfu {
                background-image: url(<?php echo HEJIN_PATH;?>public/music/music.gif);
                background-repeat: no-repeat;
                background-position: center center;
                background-size: 60px 60px;
            }
            .rotate {
                position: absolute;
                left: 10px;
                top: 10px;
                width: 30px;
                height: 30px;
                background-size: 100% 100%;
                background-image: url(<?php echo HEJIN_PATH;?>public/music/music_off.png);
                -webkit-animation: rotating 1.2s linear infinite;
                -moz-animation: rotating 1.2s linear infinite;
                -o-animation: rotating 1.2s linear infinite;
                animation: rotating 1.2s linear infinite;
            }
            @-webkit-keyframes rotating {
                from {
                    -webkit-transform: rotate(0deg);
                }
                to {
                    -webkit-transform: rotate(360deg);
                }
            }
            @keyframes rotating {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
            @-moz-keyframes rotating {
                from {
                    -moz-transform: rotate(0deg);
                }
                to {
                    -moz-transform: rotate(360deg);
                }
            }
            .off {
                background-image: url(<?php echo HEJIN_PATH;?>public/music/music_no.png);
                background-size: 30px 30px;
                background-repeat: no-repeat;
                background-position: center center;
            }
 .qimo8{ overflow:hidden; width:300px;position:absolute;bottom:10px;left:50%;margin-left:-150px;font-size:18px;color:#fff}
.qimo8 .qimo {width:8000%; height:30px;}
.qimo8 .qimo div{ float:left;}
.qimo8 .qimo ul{float:left; height:30px; overflow:hidden; zoom:1; }
.qimo8 .qimo ul li{float:left; line-height:30px; list-style:none;margin-right: 50px}
</style>
<?php } ?>    
    </head>
<body>
<?php if($hejintoupiao['hjtp_jssdk'] && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']) { ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<script type="text/javascript">
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?php echo $signPackage["appId"];?>', // 必填，公众号的唯一标识
        timestamp: "<?php echo $signPackage['timestamp'];?>", // 必填，生成签名的时间戳
        nonceStr: '<?php echo $signPackage["nonceStr"];?>', // 必填，生成签名的随机串
        signature: '<?php echo $signPackage["signature"];?>',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    wx.ready(function(){
        wx.error(function(res){
            console.log(res);
        });
        //朋友圈
        wx.onMenuShareTimeline({
            title: '<?php echo stripslashes($vote[title]);?>', // 分享标题
            link: '<?php echo HEJIN_URL;?>&model=<?php echo $symodel;?>&vid=<?php echo $vote['id'];?>', // 分享链接
            imgUrl: '<?php echo stripslashes($vote[icon]);?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        //分享给朋友
        wx.onMenuShareAppMessage({
            title: '<?php echo stripslashes($vote[title]);?>', // 分享标题
            desc: '<?php echo stripslashes($vote[content]);?>', // 分享描述
            link: '<?php echo HEJIN_URL;?>&model=<?php echo $symodel;?>&vid=<?php echo $vote['id'];?>', // 分享链接
            imgUrl: '<?php echo stripslashes($vote[icon]);?>', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
<?php } if($hejintoupiao['hjtp_musicurl']) { ?>
<div class="video_exist play_yinfu" id="audio_btn" style="display: block;">
<div id="yinfu" class="rotate"></div>
<audio preload="auto" autoplay id="media" src="<?php echo $hejintoupiao['hjtp_musicurl'];?>" loop></audio>
</div>
<?php } ?>
<header>
<img src="<?php echo stripslashes($vote[icon]);?>" alt="shareImg" width="0px" height="0px"/>
    <div class="m_head clearfix">
    	<?php if($ispicarr) { ?>
        <div class="slider">
<ul>
        <?php if(is_array($picarray)) foreach($picarray as $picar) { ?>        <?php $urlpic = explode('|',$picar)?>            <?php if(count($urlpic)>1) { ?>
            	<li><a href="<?php echo $urlpic['1'];?>"><img src="<?php echo $urlpic['0'];?>"/></a></li>
            <?php } else { ?>
    					<li><a href="#"><img src="<?php echo $picar;?>"/></a></li>
            <?php } ?>            
        <?php } ?>
  	</ul>
</div>
        <?php } else { ?>
        	<img src="<?php echo stripslashes($vote['pic']);?>" />
        <?php } ?>
        <?php if($vote['yuliua']==8 or $vote['yuliua']==10) { ?>
        	<div class="search">
    <form action="" id="search_form" method="post">
    <input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
    <input type="hidden" name="vid" value="<?php echo $vid;?>" />
        <div class="search_con">
            <div class="btn"><input type="submit" name="seachid" id="searchBtn" value="搜索"></div>
            <div class="text_box"><input type="search" id="searchText" value="" name="keyword" placeholder="搜名字或编号" autocomplete="off"></div>
        </div>
    </form>
</div>
        <?php } ?>
        <div class="num_box">
        <?php if($vote['yuliua']!=8 and $vote['yuliua']!=10) { ?>
            <?php if($ishavezp) { ?><a href="<?php echo HEJIN_URL;?>&model=<?php echo $xqmodel;?>&zid=<?php echo $havezp['id'];?>" class="join_us">我的参赛</a><?php } else { if($vote['start_time']<time() && $vote['over_time']>time()) { ?><a href="<?php echo HEJIN_URL;?>&model=signup&vid=<?php echo $vote['id'];?>" class="join_us">我要报名</a><?php } } ?> 
        <?php } ?>     
        
        
<?php if($vote['end_time']>time() && $hejintoupiao['hjtp_djsgn']) { ?>
<div align="center" align="center">
<center>
  <p class="STYLE2"> <span class="STYLE1">距离活动结束还有:</span><br />
  </p>
    <br>
      <p> <DIV id="CountMsg" class="STYLE1">
    <span id="t_d">00天</span>
    <span id="t_h">00时</span>
    <span id="t_m">00分</span>
    <span id="t_s">00秒</span>
</DIV>
</p>
      <br />
  </center>

<script type="text/javascript">
    function getRTime(){
        var EndTime= new Date('<?php echo date("Y/m/d H:i:s",$vote[end_time]);?>'); //截止时间 前端路上 http://www.51xuediannao.com/qd63/
        var NowTime = new Date();
        var t =EndTime.getTime() - NowTime.getTime();
        /*var d=Math.floor(t/1000/60/60/24);
        t-=d*(1000*60*60*24);
        var h=Math.floor(t/1000/60/60);
        t-=h*60*60*1000;
        var m=Math.floor(t/1000/60);
        t-=m*60*1000;
        var s=Math.floor(t/1000);*/

        var d=Math.floor(t/1000/60/60/24);
        var h=Math.floor(t/1000/60/60%24);
        var m=Math.floor(t/1000/60%60);
        var s=Math.floor(t/1000%60);

        document.getElementById("t_d").innerHTML = d + "天";
        document.getElementById("t_h").innerHTML = h + "时";
        document.getElementById("t_m").innerHTML = m + "分";
        document.getElementById("t_s").innerHTML = s + "秒";
    }
    setInterval(getRTime,1000);
    </script>
    <?php } ?>

        
             
            <ul class="num_box_ul">
                <li>
                    <span class="text">已报名</span>
                    <span><?php echo $vote['zuopins'];?></span>
                </li>
                <li>
                    <span class="text"><?php echo $hejintoupiao['hjtp_tpzdy'];?>人次</span>
                    <span><?php echo $vote['toupiaos']+$vote['xntps'];?></span>
                </li>
                <li>
                    <span class="text">访问量</span>
                    <span><?php echo $vote['clicks']+$vote['xnlls'];?></span>
                </li>
            </ul>
                    <?php if($vote['yuliua']==8 or $vote['yuliua']==10) { ?>
            <?php if($ishavezp) { ?><a href="<?php echo HEJIN_URL;?>&model=<?php echo $xqmodel;?>&zid=<?php echo $havezp['id'];?>" class="join_us">我的参赛</a><?php } else { if($vote['start_time']<time() && $vote['over_time']>time()) { ?><a href="<?php echo HEJIN_URL;?>&model=signup&vid=<?php echo $vote['id'];?>" class="join_us">我要报名</a><?php } } ?> 
        <?php } ?>          

            <?php if($vote['yuliua']!=8 and $vote['yuliua']!=10) { ?>
            <img src="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/mw_004.jpg" />
            <?php } ?>
        </div>
        <?php if($vote['yuliua']!=8 and $vote['yuliua']!=10) { ?>
        <div class="search">
    <form action="" id="search_form" method="post">
    <input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
    <input type="hidden" name="vid" value="<?php echo $vid;?>" />
        <div class="search_con">
            <div class="btn"><input type="submit" name="seachid" id="searchBtn" value="搜索"></div>
            <div class="text_box"><input type="search" id="searchText" value="" name="keyword" placeholder="搜名字或编号" autocomplete="off"></div>
        </div>
    </form>
</div> 
<?php } ?>   </div>
</header>

<?php if($hejintoupiao['hjtp_dlggurl']) { if($hejintoupiao['hjtp_dlgglj']) { ?>
    	<a href="<?php echo $hejintoupiao['hjtp_dlgglj'];?>"><img src="<?php echo $hejintoupiao['hjtp_dlggurl'];?>" width="100%"></a>
<?php } else { ?>
<img src="<?php echo $hejintoupiao['hjtp_dlggurl'];?>" width="100%">
<?php } } ?>

<section class="content" id="get_info" data-rid="503" data-sort="" data-kw="" data-page="">
    <div class="text_a clearfix" id="sort">
        <a href="<?php echo HEJIN_URL;?>&model=<?php echo $symodel;?>&vid=<?php echo $vote['id'];?>" class="active">最新参赛</a>
        <a href="<?php echo HEJIN_URL;?>&model=<?php echo $phmodel;?>&vid=<?php echo $vote['id'];?>" ><?php echo $hejintoupiao['hjtp_tpzdy'];?>排行</a>
        <a href="<?php echo HEJIN_URL;?>&model=<?php echo $topmodel;?>&vid=<?php echo $vote['id'];?>#top300">TOP<?php echo $hejintoupiao['hjtp_topnub'];?></a>
        <?php if($hejintoupiao['hjtp_fenlanmc']) { ?><a href="<?php echo $hejintoupiao['hjtp_fenlanlj'];?>"><?php echo $hejintoupiao['hjtp_fenlanmc'];?></a><?php } ?>
    </div>
    <div class="blank20"></div>
    <div id="pageCon" class="match_page masonry" style="padding-bottom: 50px">
        <ul class="list_box masonry clearfix" style="position: relative;">
        <?php if(is_array($zuopins)) foreach($zuopins as $zuopin) { ?>                      <li class="picCon">
                        <div>
                            <i class="number"><?php if($hejintoupiao['hjtp_numbtp']) { ?><?php echo $zuopin['id'];?><?php } else { echo $vote['id']*10000+$zuopin['id'];?><?php } ?>号</i>
                            <a href="<?php echo HEJIN_URL;?>&model=<?php echo $xqmodel;?>&zid=<?php echo $zuopin['id'];?>" class="img">
                            <?php if(strpos($zuopin['pica'], '://')==false) { ?>
                                <img src="<?php echo HEJIN_PATH;?><?php echo stripslashes($zuopin['pica']);?>" alt="">
                            <?php } else { ?>
                            	<img src="<?php echo stripslashes($zuopin['pica']);?>" alt="">
                            <?php } ?>
                            </a>
                            <div class="clearfix">
                                <p><?php if($vote['yuliua']!=8 and $vote['yuliua']!=10) { ?>
                                    <?php echo strip_tags(stripslashes($zuopin['zpname']));?><br/>
                                    <?php echo $zuopin['toupiaos'];?>票
                                    <?php } else { ?>
                                    <?php echo strip_tags(stripslashes($zuopin['zpname']));?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo $zuopin['toupiaos'];?>票
                                    <?php } ?>
                                </p>
                                <a href="" class="vote" data-itid="<?php echo $zuopin['id'];?>" data-vote_num="<?php echo $vote['id'];?>" data-rule_id="<?php echo $vote['id'];?>"><?php echo $hejintoupiao['hjtp_tpzdy'];?></a>
                            </div>
                        </div>
                    </li>
                   <?php } ?> 
                                </ul>

    </div>

    <div class="pagination pagination-centered"> <ul><?php echo $page_string;?></ul></div>
</section>
<?php if($vote['yuliua']!=8 and $vote['yuliua']!=10) { ?>
<img class="bg" src="<?php echo HEJIN_PATH;?>public/index<?php echo $vote['yuliua'];?>/mw_005.jpg" />
<?php } ?>

<section class="rules">
    <div class="text">    <?php if($vote['shuomingta']) { ?>
        <div class="prize"><?php echo stripslashes($vote['shuomingta']);?></div>
            <div class="neirong"><?php echo replace($vote['shuomingca']);?></div>
    <?php } ?>
    <?php if($vote['shuomingtb']) { ?>
        <div class="prize"><?php echo stripslashes($vote['shuomingtb']);?></div>
             <div class="neirong"><?php echo replace($vote['shuomingcb']);?></div>
    <?php } ?>
</div>
    <div class="text">    <?php if($vote['shuomingtc']) { ?>
        <div class="ways"><?php echo stripslashes($vote['shuomingtc']);?></div>
            <div class="neirong"> <?php echo replace($vote['shuomingcc']);?></div>
    <?php } ?>
</div>
<div style=" height:60px; width:100%; display:block;"></div>
</section>

<section>
    <div class="pop" id="guanzhu" style="display:none">
        <div class="mengceng"></div>
        <div class="pop_up">
                        <p class="tit_p"><?php echo $hejintoupiao['hjtp_ydgzbt'];?></p>
            <p class="tit_txt"><?php echo stripslashes($vote['wxgzts']);?></p>
            <a href="<?php echo stripslashes($vote['wxgzurl']);?>" class="gz_btn"><?php echo $hejintoupiao['hjtp_ydgzan'];?></a>
                    </div>
    </div>
    <div class="pop" id="voted" style="display:none;">
        <div class="mengceng"></div>
        <div class="pop_up">
            <span class="closed close_pop_up" id="toupiaook">&nbsp;</span>
            <p class="tit_p" id="dia_title">投票成功!</p>
            <p class="tit_txt" id="content">恭喜您为您支持的作品投上了一票！</p>
            <?php if($hejintoupiao['hjtp_tpjl'] && $hejintoupiao['hjtp_tpjlnub']) { ?><p class="tit_txt" id="content"><?php echo $hejintoupiao['hjtp_tpjlsm'];?></p><?php } ?>
            <?php if($hejintoupiao['hjtp_tpjl'] && $hejintoupiao['hjtp_tpjlnub'] && $vote['yuliud']) { ?><a href="<?php echo stripslashes($vote['yuliud']);?>" class="gz_btn"><?php echo $hejintoupiao['hjtp_jfxftitle'];?></a><?php } elseif($hejintoupiao['hjtp_tpjl'] && $hejintoupiao['hjtp_tpjlnub'] && $hejintoupiao['hjtp_jfxfurl']) { ?><a href="<?php echo stripslashes($hejintoupiao['hjtp_jfxfurl']);?>" class="gz_btn"><?php echo $hejintoupiao['hjtp_jfxftitle'];?></a><?php } ?>
            <p class="tit_txt" id="subcontent"></p>
        </div>
    </div>
    


    
    <div class="pop" id="voting" style="display:none;">
        <div class="mengceng"></div>
        <div class="pop_up">
            <span class="closed close_pop_up">&nbsp;</span>
            <p class="tit_p" id="voting_title"></p>
            <p class="tit_txt" id="voting_content"></p>
        </div>
    </div>
 <?php if($hejintoupiao['hjtp_tpyzm']) { ?>   
    <div class="pop" id="validata" style="display:none;">
        <div class="mengceng"></div>
        <div class="pop_up" style="width:310px;">
            <script src="http://api.geetest.com/get.php?gt=<?php echo $hejintoupiao['hjtp_tpyzmid'];?>&width=280" type="text/javascript" type="text/javascript"></script>
        </div>
    </div>
  <?php } ?>  
    <div class="share_overmask" style="display: none;">
        <div class="share_arrow"></div>
        <div class="share_words"></div>
    </div>
</section>

<link rel="stylesheet" type="text/css" href="<?php echo HEJIN_PATH;?>public/index2/daohang.css">

<div class="bot_main">
  <ul>
    <li class="ico_1"><span class="ico"><img src="<?php echo HEJIN_PATH;?>public/index2/i1.png" /></span><span class="txt">首页</span></li>
    <li class="ico_2"><span class="ico"><img src="<?php echo HEJIN_PATH;?>public/index2/i3.png" /></span><span class="txt">排名</span></li>
    <?php if($ishavezp) { ?>
    <li class="ico_3"><span class="ico"><img src="<?php echo HEJIN_PATH;?>public/index2/i11.png" /></span><span class="txt">我的</span></li>
    <?php } else { ?>
    	<?php if($vote['start_time']<time() && $vote['over_time']>time()) { ?>
    <li class="ico_3"><span class="ico"><img src="<?php echo HEJIN_PATH;?>public/index2/i11.png" /></span><span class="txt">报名</span></li>
        <?php } ?>
    <?php } ?>
    
    <li class="ico_4"><span class="ico"><img src="<?php echo HEJIN_PATH;?>public/index2/i4.png" /></span><span class="txt"><?php echo $hejintoupiao['hjtp_dbdhti'];?></span></li>
  </ul>
</div>

<?php if($hejintoupiao['hjtp_musicurl']) { ?>
<script>
$(function() {
                    var audio = $('#media');
                    audio[0].play();
                    $("#audio_btn").bind('click', function() {
                        $(this).hasClass("off") ? ($(this).addClass("play_yinfu").removeClass("off"), $("#yinfu").addClass("rotate"), $("#media")[0].play()) : ($(this).addClass("off").removeClass("play_yinfu"), $("#yinfu").removeClass("rotate"), $("#media")[0].pause());
                    });
                }); 
var scroll = document.getElementById("scroll");
var scroll1 = document.getElementById("scroll1");
var scroll2 = document.getElementById("scroll2");
scroll2.innerHTML=document.getElementById("scroll1").innerHTML;
function Marquee(){
if(scroll.scrollLeft-scroll2.offsetWidth>=0){
 scroll.scrollLeft-=scroll1.offsetWidth;
}
else{
 scroll.scrollLeft++;
}
}
var myvar=setInterval(Marquee,30);
scroll.onmouseout=function (){myvar=setInterval(Marquee,30);}
scroll.onmouseover=function(){clearInterval(myvar);}
</script>
<?php } ?>
<script type="text/javascript">
    $(function(){
        $("#toupiaook").on('click',function(){
                window.location.href = "<?php echo HEJIN_URL;?>&model=<?php echo $symodel;?>&vid=<?php echo $vote['id'];?>&page=<?php echo $page;?>";
        });
    });
$('.ico_1').on('click', function(){
  location.href = "<?php echo HEJIN_URL;?>&model=<?php echo $symodel;?>&vid=<?php echo $vote['id'];?>";
});
$('.ico_2').on('click', function(){
  location.href = "<?php echo HEJIN_URL;?>&model=<?php echo $phmodel;?>&vid=<?php echo $vote['id'];?>";
});
$('.ico_3').on('click', function(){
  <?php if($ishavezp) { ?>
  	location.href = "<?php echo HEJIN_URL;?>&model=<?php echo $xqmodel;?>&zid=<?php echo $havezp['id'];?>";
  <?php } else { ?>
  	<?php if($vote['start_time']<time() && $vote['over_time']>time()) { ?>
  		location.href = "<?php echo HEJIN_URL;?>&model=signup&vid=<?php echo $vote['id'];?>";
  	<?php } else { ?>
  		<?php if($vote['start_time']>time()) { ?>
$('#voting_title').html("<?php echo date('Y-m-d H:i',$vote['start_time']);?> 后才能报名！");
$('#voting_content').html('');
                        $('#voting').show();
  		<?php } else { ?>
$('#voting_title').html('报名已结束！');
$('#voting_content').html('');
                        $('#voting').show();
  		<?php } ?>
  	<?php } ?>
  <?php } ?>
});
$('.ico_4').on('click', function(){
<?php if($vote['yuliud']) { ?>
location.href = "<?php echo stripslashes($vote['yuliud']);?>";
<?php } else { ?>
location.href = "<?php echo $hejintoupiao['hjtp_dbdhurl'];?>";
<?php } ?>
});
</script>

<script type="text/javascript">
<?php if($hejintoupiao['hjtp_tpyzm']) { ?>

function gt_custom_ajax(result, selector, message) {
  if (result) {
$('#validata').hide();
            $.ajax({
                type: "GET",
                url: "<?php echo HEJIN_URL;?>&model=ticket",
                cache: false,
                data: {
                    zid:itid,
formhash:'<?php echo FORMHASH;?>'
                },
                success: function(data) {
                    if (data == 102) {//未关注
                        $('#guanzhu').show();
                    } else if (data == 108) {//投票成功
                        $('#voted').show();
                    } else if (data == 106) {//此用户今日已无法投票
$('#voting_title').html('无法投票');
$('#voting_content').html('<?php if($hejintoupiao['hjtp_tpxzmos']==1) { ?>您今日的票数已投完，请明日再投！<?php } else { ?>您本次活动的票数已投完，感谢您的参与！<?php } ?>');
                        $('#voting').show();
                    } else if (data == 105) {//此IP下今日已无法投票
$('#voting_title').html('无法投票');
$('#voting_content').html('此IP今日票数已投完，请明日再投！');
                        $('#voting').show();
                    } else if (data == 103) {//投票还未开始
$('#voting_title').html('投票还未开始');
$('#voting_content').html('请<?php echo date("Y-m-d H:i",$vote[vote_time]);?>后再来！');
                        $('#voting').show();
                    } else if (data == 104) {//投票已经结束
$('#voting_title').html('投票已经结束');
$('#voting_content').html('');
                        $('#voting').show();
                    } else if (data == 107) {//投票已经结束
$('#voting_title').html('投票失败');
$('#voting_content').html('此作品无法投票，可能出于审核中或已被屏蔽！');
                        $('#voting').show();
                    } else if (data == 110) {//ip不在限制区域中
$('#voting_title').html('投票失败');
$('#voting_content').html('<?php echo $hejintoupiao['hjtp_ipxzts'];?>');
                        $('#voting').show();
                    }else if (data == 109) {//投票已经结束
$('#voting_title').html('投票失败');
$('#voting_content').html('<?php echo $hejintoupiao['hjtp_tpxzts'];?>');
                        $('#voting').show();
                    }else if (data == 120) {//报名期间达到投票限制数
$('#voting_title').html('投票失败');
$('#voting_content').html('报名期内优先投票最多<?php echo $vote['yuliub'];?>票！正式投票为<?php echo date("m-d H:i",$vote[over_time]);?>开始，请到时再继续投票 感谢您的参与！');
                        $('#voting').show();
                    }else if (data == 888) {//编号投票
$('#voting_title').html('进入公众号，回复“TP' + itid + '”给我投票哦！');
$('#voting_content').html('<a href="<?php echo stripslashes($vote[wxgzurl]);?>" class="gz_btn">进入我们的公众号</a>');
                        $('#voting').show();
                    }
                }
            });
}else{
if(message == "Fail") {
}
if(message == "Abuse" || message == "Forbidden") {
}
  	}
  	$('.gt_refresh_button')[0].click(); 
}
var itid;
    $(function(){
        $('.vote').on('tap', function(e){
            e.preventDefault();
            var self = $(e.target).closest('.vote');
itid = self.data('itid');
$('#validata').show();
        });

        var container = $('#pageCon ul');

        container.imagesLoaded(function(){
            container.masonry({
                itemSelector: '.picCon'
            });
        });
    });
<?php } else { ?>
    $(function(){
        $('.vote').on('tap', function(e){
$('#voting_content').html('投票请求中。。。请耐心等待！');
$('#voting').show();
            e.preventDefault();
            var self = $(e.target).closest('.vote');
            $.ajax({
                type: "GET",
                url: "<?php echo HEJIN_URL;?>&model=ticket",
                cache: false,
                data: {
                    zid:self.data('itid'),
formhash:'<?php echo FORMHASH;?>'

                },
                success: function(data) {
$('#voting').hide();
                    if (data == 102) {//未关注
                        $('#guanzhu').show();
                    } else if (data == 108) {//投票成功
                        $('#voted').show();
                    } else if (data == 106) {//此用户今日已无法投票
$('#voting_title').html('无法投票');
$('#voting_content').html('<?php if($hejintoupiao['hjtp_tpxzmos']==1) { ?>您今日的票数已投完，请明日再投！<?php } else { ?>您本次活动的票数已投完，感谢您的参与！<?php } ?>');
                        $('#voting').show();
                    } else if (data == 105) {//此IP下今日已无法投票
$('#voting_title').html('无法投票');
$('#voting_content').html('此IP今日票数已投完，请明日再投！');
                        $('#voting').show();
                    } else if (data == 103) {//投票还未开始
$('#voting_title').html('投票还未开始');
$('#voting_content').html('请<?php echo date("Y-m-d H:i",$vote[vote_time]);?>后再来！');
                        $('#voting').show();
                    } else if (data == 104) {//投票已经结束
$('#voting_title').html('投票已经结束');
$('#voting_content').html('');
                        $('#voting').show();
                    } else if (data == 107) {//投票已经结束
$('#voting_title').html('投票失败');
$('#voting_content').html('此作品无法投票，可能出于审核中或已被屏蔽！');
                        $('#voting').show();
                    } else if (data == 110) {//ip不在限制区域中
$('#voting_title').html('投票失败');
$('#voting_content').html('<?php echo $hejintoupiao['hjtp_ipxzts'];?>');
                        $('#voting').show();
                    }else if (data == 109) {//投票已经结束
$('#voting_title').html('投票失败');
$('#voting_content').html('<?php echo $hejintoupiao['hjtp_tpxzts'];?>');
                        $('#voting').show();
                    }else if (data == 120) {//报名期间达到投票限制数
$('#voting_title').html('投票失败');
$('#voting_content').html('报名期内优先投票最多<?php echo $vote['yuliub'];?>票！正式投票为<?php echo date("m-d H:i",$vote[over_time]);?>开始，请到时再继续投票 感谢您的参与！');
                        $('#voting').show();
                    }else if (data == 888) {//编号投票
$('#voting_title').html('进入公众号，回复“TP' + self.data('itid') + '”给我投票哦！');
$('#voting_content').html('<a href="<?php echo stripslashes($vote[wxgzurl]);?>" class="gz_btn">进入我们的公众号</a>');
                        $('#voting').show();
                    }
                }
            });
        });

        var container = $('#pageCon ul');

        container.imagesLoaded(function(){
            container.masonry({
                itemSelector: '.picCon'
            });
        });
    });
<?php } ?>

    $(window).on('unload pageshow', function () {});
$.get('<?php echo HEJIN_URL;?>&model=clicks&vid=<?php echo $vote['id'];?>&formhash=<?php echo FORMHASH;?>');
</script>
<?php if($ispicarr) { ?>
  <script src="<?php echo HEJIN_PATH;?>public/slider/yxMobileSlider.js" type="text/javascript"></script>
<script type="text/javascript">
$(".slider").yxMobileSlider({during:5000,height:<?php echo $hejintoupiao['hjtp_hdgd'];?>});
var nowtime=new Date().getTime();
function _fresh(){
var endtime=new Date("2015/02/18 12:00:00");//这里设置的时间为2011年，您可以修改为其它时间。
//var nowtime = new Date();
var leftsecond=parseInt((endtime.getTime()-nowtime)/1000);
if(leftsecond<0){leftsecond=0;}
__d=parseInt(leftsecond/3600/24);
__h=parseInt((leftsecond/3600)%24);
__m=parseInt((leftsecond/60)%60);
__s=parseInt(leftsecond%60);
var sums=__d+__h+__m+__s;
var if_Receive="";
if(sums!=0){
var d=document.getElementById("_d");
var h=document.getElementById("_h");
var m=document.getElementById("_m");
var s=document.getElementById("_s");
h.innerHTML=__h+__d*24;
m.innerHTML=__m;
s.innerHTML=__s;
nowtime=nowtime+1000;
setTimeout(_fresh,1000);
}else if(!if_Receive){
document.getElementById("msg").innerHTML="";
}
}
_fresh();
</script>
<?php } ?>  
<div style="display:none"><?php echo $hejintoupiao['hjtp_dsftjdm'];?>v29</div></body>
</html>