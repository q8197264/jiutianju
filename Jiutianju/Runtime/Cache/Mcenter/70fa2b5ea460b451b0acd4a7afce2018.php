<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title><?php if(!empty($seo_title)): echo ($seo_title); ?>_<?php endif; echo ($CONFIG["site"]["sitename"]); ?>会员中心</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		<?php if($CONFIG[site][concat] != 1): ?><link rel="stylesheet" href="/static/default/wap/css/base.css">
		<link rel="stylesheet" href="/static/default/wap/css/mcenter.css"/>
		<script src="/static/default/wap/js/jquery.js"></script>
		<script src="/static/default/wap/js/base.js"></script>
		<script src="/static/default/wap/other/layer.js"></script>
		<script src="/static/default/wap/other/roll.js"></script>
		<script src="/static/default/wap/js/public.js"></script>
		<?php else: ?>
		<link rel="stylesheet" href="/static/default/wap/css/??base.css,mcenter.css" />
		<script src="/static/default/wap/js/??jquery.js,base.js,roll.js,layer.js,public.js"></script><?php endif; ?>
	</head>
	<body>
<style>
.icon-sign-out, .top-fixed .top-back i { font-size: 18px;}
.top-fixed {border-bottom: none;}
.top-fixed .top-search input {border-radius:2px;}
.top-fixed .top-share, .top-fixed .top-back {padding-right: 0px;}
.top-fixed .top-title {font-size: 14px;}
.top-fixed .top-share a {width: 50px !important;}
p, .p {margin-bottom: 0px;}
.member-top { margin-top: 0px;}
</style>

	<header class="top-fixed bg-yellow bg-inverse transparent" id="header">
		<div class="top-back">
			<a id="search-btn" href="javascript:void(0);"><i class="icon-search"></i></a>
		</div>
		<div class="top-title">
			会员中心
		</div>
        <div class="top-search" style="display:none;">
			<form method="post" action="<?php echo U('all/index');?>">
				<input name="keyword" placeholder="输入关键字"  />
				<button type="submit" class="icon-search"></button> 
			</form>
		</div>
		<div class="top-share">
			<?php if($msg_day > 0): ?><a href="<?php echo U('mcenter/message/index');?>">
<i class="icon-envelope"></i>
<span class="badge bg-red jiaofei"><?php echo ($msg_day); ?></span>
</a>
<?php else: ?>
    <?php if(empty($sign_day)): ?><a href="<?php echo U('mobile/sign/signed');?>" class="top-addr icon-plus"> 签到</a>    
    <?php else: ?>
    <a href="<?php echo U('mobile/passport/logout');?>" class="top-addr icon-sign-out"></a><?php endif; endif; ?>
		</div>
	</header>


<div class="member-top">
<div class="member-info">
<div class="user-avatar"> 
<img src="<?php echo config_img($MEMBER['face']);?>"> 
</div>
<div class="user-name"> 
<span>
				<?php if(!empty($MEMBER['nickname'])): echo ($MEMBER["nickname"]); ?> 
                <?php else: ?>
                <?php echo ($MEMBER["account"]); endif; ?>
                <sup>VIP <?php echo ($MEMBER['rank_id']); ?></sup></span> 
</div>
</div>
<div class="member-collect">
<span><a href="<?php echo u('money/index');?>"><em><?php echo round($MEMBER['money']/100,2);?></em><p>我的余额</p></a> </span>
<span><a href="<?php echo u('money/index');?>"><em><?php echo ($MEMBER["integral"]); ?></em><p>我的积分</p></a> </span>
<span><a href="<?php echo u('mobile/favorites/index');?>">
<i class="goods-browse"></i><p>我的足迹</p></a> </span>
</div>
</div>

  <script type="text/javascript">
	$(function(){
		$("#search-btn").click(function(){
			if($(".top-search").css("display")=='block'){
				$(".top-search").hide();
				$(".top-title").show(200);
			}
			else{
				$(".top-search").show();
				$(".top-title").hide(200);
			}
		});
		$("#search-bar li").each(function(e){
			$(this).click(function(){
				if($(this).hasClass("on")){
					$(this).parent().find("li").removeClass("on");
					$(this).removeClass("on");
					$(".serch-bar-mask").hide();
				}
				else{
					$(this).parent().find("li").removeClass("on");
					$(this).addClass("on");
					$(".serch-bar-mask").show();
				}
				$(".serch-bar-mask .serch-bar-mask-list").each(function(i){
					
					if(e==i){
						$(this).parent().find(".serch-bar-mask-list").hide();
						$(this).show();
					}
					else{
						$(this).hide();
					}
					$(this).find("li").click(function(){
						$(this).parent().find("li").removeClass("on");
						$(this).addClass("on");
					});
				});
			});
		});
	});
	

	</script>  
    

<div class="member-center">
      <dl class="mt5">
        <dd>
          <ul id="order_ul">
              <li><a href="<?php echo U('tuan/index');?>"><i class="icon-bookmark-o"></i><p>抢购</p></a></li>
              <?php if($open_mall == 1): ?><li><a href="<?php echo LinkTo('goods/index',array('aready'=>1));?>"><i class="icon-cart-plus"></i><p>购物</p></a></li><?php endif; ?>
              <li><a href="<?php echo U('eleorder/index');?>"><i class="icon-cutlery"></i><p>外卖</p></a></li>
       
              <li><a href="<?php echo LinkTo('booking/index');?>"><i class="icon-tty"></i><p>订座</p></a></li>

              <li><a href="<?php echo U('breaks/index');?>"><i class="icon-history"></i><p>买单</p></a></li>
          </ul>
        </dd>
      </dl>
      
</div>
    
    
<div class="blank-10 bg"></div>
<div class="panel-list">
	<ul>

<li><a href="<?php echo U('information/index');?>"><span class="icon-gears"></span>账户管理<i class="icon-angle-right"></i></a></li>

<?php if($is_shop != null): ?><li><a href="<?php echo u('store/index/index');?>"><span class="icon-home"></span>管理我的商家<font>（<?php echo ($is_shop_name); ?>）</font><i class="icon-angle-right"></i></a></li><?php endif; ?>
<?php $worker = D('Shopworker')->where(array('user_id'=>$MEMBER['user_id']))->find(); $user_delivery = D('Delivery')->where(array('user_id'=>$MEMBER['user_id'],'audit'=>1,'closed'=>0))->find(); ?>
<?php if(!empty($worker)): ?><li><a href="<?php echo U('worker/index/index');?>"><span class="icon-paper-plane-o"></span>店员中心登录<font>（<?php echo ($worker['name']); ?>）</font><i class="icon-angle-right"></i></a></li><?php endif; ?>

<?php if(!empty($user_delivery)): ?><li><a href="<?php echo U('delivery/index/index');?>"><span class="icon-bus"></span>配送员中心登录<font>（<?php echo ($user_delivery['name']); ?>）</font><i class="icon-angle-right"></i></a></li><?php endif; ?>

<li><a href="<?php echo u('money/index');?>"><span class="icon-money"></span>余额充值<i class="icon-angle-right"></i></a></li>

	</ul>

</div>

<div class="blank-10 bg"></div>



<div class="panel-list">

	<ul>

		<li>

			<a href="<?php echo U('tuancode/index');?>">

				<span class="icon-credit-card"></span>

				我的团购券&nbsp; 

                <?php if($code > 0): ?><font>待消费：(<b><?php echo ($code); ?></b>)</font>

                <?php else: endif; ?> 

                

				<i class="icon-angle-right"></i>

			</a>

		</li>



 
<li><a href="<?php echo U('pinche/index');?>"><span class="icon-car"></span>我的拼车<i class="icon-angle-right"></i></a></li>        
<li><a href="<?php echo U('hotel/index');?>"><span class="icon-hotel"></span>我的酒店<i class="icon-angle-right"></i></a></li>      
<li><a href="<?php echo U('farm/index');?>"><span class="icon-paw"></span>我的农家乐<i class="icon-angle-right"></i></a></li>      


		<li>

			<a href="<?php echo U('coupon/index');?>">
				<span class="icon-tags"></span>
				我的优惠券&nbsp; 
                <?php if($coupon > 0): ?><font>未使用：(<b><?php echo ($coupon); ?></b>)</font>
                <?php else: endif; ?> 
				<i class="icon-angle-right"></i>
			</a>
		</li>

        <li>

			<a href="<?php echo U('yuyue/index',array('status'=>2));?>">
				<span class="icon-tty"></span>
				我的预约
                <?php if($shop_yuyue > 0): ?><font>未使用：(<b><?php echo ($shop_yuyue); ?></b>)</font>
                <?php else: endif; ?> 
				<i class="icon-angle-right"></i>
			</a>
		</li>
        
        <?php if($open_tieba == 1): ?><li><a href="<?php echo U('tieba/index');?>"><span class="icon-comments"></span>我的贴吧&nbsp; 
        <?php if($tieba > 0): ?><!--如果贴吧不等于0-->
        <font>(<?php echo ($tieba); ?>)</font><!--显示数据-->
        <?php else: endif; ?>  
        <?php if($counts['tieba'] != null): ?><font>今日：(<b><?php echo ($counts["tieba"]); ?></b>)</font>  
        <?php else: endif; ?>  
        <i class="icon-angle-right"></i>
        </a>
    </li><?php endif; ?>
<?php if($open_community == 1): ?><li><a href="<?php echo u('member/xiaoqu');?>"><span class="icon-ils"></span>我的小区 
				<?php if($xiaoqu > 0): ?><font>(<?php echo ($xiaoqu); ?>)</font> 
                <?php else: endif; ?> <i class="icon-angle-right"></i></a>
</li><?php endif; ?> 
<?php if($open_huodong == 1): ?><li><a href="<?php echo u('huodong/index');?>"><span class="icon-star-o"></span>我报名的活动<i class="icon-angle-right"></i></a></li><?php endif; ?>
        <div class="blank-10 bg"></div>

<?php if($open_life == 1): ?><li>
        <a href="<?php echo U('life/index');?>"><span class="icon-truck"></span>我的同城信息&nbsp; 
        <?php if($life > 0): ?><font>(<?php echo ($life); ?>)</font>  
        <?php else: endif; ?>  
        <i class="icon-angle-right"></i>
        </a>
    </li><?php endif; ?>   
   
<?php if($open_jifen == 1): ?><li>
        <a href="<?php echo U('exchange/index');?>"><span class="icon-gift"></span>我的礼品&nbsp; 
        <?php if($lipin > 0): ?><font>(<?php echo ($lipin); ?>)</font>
        <?php else: endif; ?> 
        <i class="icon-angle-right"></i>
        </a>
    </li><?php endif; ?> 


<?php if($open_cloud == 1): ?><li>
        <a href="<?php echo U('cloud/index');?>"><span class="icon-send"></span>我的一元云购&nbsp; 
        <i class="icon-angle-right"></i>
        </a>
    </li><?php endif; ?> 

<?php if($open_lifeservice == 1): ?><li><a href="<?php echo U('appoint/index');?>"><span class="icon-umbrella"></span>我的家政<i class="icon-angle-right"></i></a></li><?php endif; ?> 


 <div class="blank-10 bg"></div>
<li><a href="<?php echo U('pao/index');?>"><span class="icon-truck"></span>我发布的跑腿&nbsp; <i class="icon-angle-right"></i></a></li>  
<li><a href="<?php echo U('pao/records');?>"><span class="icon-truck"></span>我抢单的跑腿&nbsp; <i class="icon-angle-right"></i></a></li>  

 <div class="blank-10 bg"></div>
 
 	   <?php if($distribution == 1): ?><!--如果开启分销-->
       <li><a href="<?php echo U('distribution/index');?>"><span class="icon-users"></span>我的分销<i class="icon-angle-right"></i></a></li>
       <?php else: endif; ?> 
<?php if($open_express == 1): ?><li><a href="<?php echo U('express/index');?>"><span class="icon-plane"></span>我的快递&nbsp; <i class="icon-angle-right"></i></a></li><?php endif; ?>         

		
<?php
if(empty($delivery_info)){ ?>      
	<li><a href="<?php echo U('member/sqdelivery');?>"><span class="icon-truck"></span>申请成为配送员&nbsp; <i class="icon-angle-right"></i></a></li>  
<?php
} else{ if($delivery_info['d_status']==1){ ?>
	<li><a href="<?php echo U('/delivery/index');?>"><span class="icon-truck"></span>配送员管理中心&nbsp; <i class="icon-angle-right"></i></a></li>  
<?php
 } else{ ?>
	<li><a href="javascript:void(0);"><span class="icon-truck"></span>配送员审核中，请等待&nbsp; <i class="icon-angle-right"></i></a></li>  
	<?php
 } } ?>

	</ul>

</div>
<div class="blank-10"></div>
<div class="container text-center">
		<a class="button button-block button-big bg-dot" href="<?php echo u('mobile/passport/logout');?>">退出后台</a>
</div>

<div class="blank-20"></div>
 <footer class="foot-fixed">
  <?php if($ctl == 'member'): ?><a class="foot-item  <?php if($ctl == 'member'): ?>active<?php endif; ?>" href="<?php echo u('mobile/index/index');?>">		
    <span class="icon icon-home"></span>
    <span class="foot-label">首页</span>
    </a>
  <?php else: ?>
  <a class="foot-item" href="<?php echo u('member/index');?>">		
    <span class="icon icon-home"></span>
    <span class="foot-label">首页</span>
    </a><?php endif; ?>
    
    <a class="foot-item " href="<?php echo LinkTo('mobile/life/release');?>">			
    <span class="icon icon-plus"></span><span class="foot-label">发布</span></a>
    
     <a class="foot-item  <?php if(($ctl == 'tuancode')): ?>active<?php endif; ?>" href="<?php echo u('tuancode/index');?>">			
    <span class="icon icon-folder"></span><span class="foot-label">抢购劵</span></a>
    
    
    
    <a class="foot-item  <?php if(($ctl == 'message') ||($act == 'xiaoxizhongxin')): ?>active<?php endif; ?>" href="<?php echo u('message/index');?>">			
    <span class="icon icon-volume-up"></span><span class="foot-label">消息</span></a>
    
    <a class="foot-item  <?php if(($ctl == 'money') || ($ctl == 'logs') || ($ctl == 'cash') ||($act == 'zijinguanli')): ?>active<?php endif; ?>" href="<?php echo u('information/index');?>">			
    <span class="icon icon-gear"></span><span class="foot-label">设置</span></a>
    
   
    </footer>


<iframe id="x-frame" name="x-frame" style="display:none;">
</iframe>
</body>
</html>