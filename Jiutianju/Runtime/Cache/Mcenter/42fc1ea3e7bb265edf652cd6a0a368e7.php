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
	<header class="top-fixed bg-yellow bg-inverse">
		<div class="top-back">
			<a class="top-addr" href="<?php echo U('index/index');?>"><i class="icon-angle-left"></i></a>
		</div>
		<div class="top-title">
			我的同城信息
		</div>
		<div class="top-share">
		</div>
	</header>
<style>
.xiaoqu-search{ margin-top:0.5rem;padding: 5px;}
.button-toolbar .button-group {padding: 0 10px;}
.list-media-x { margin-top: 0rem;}
</style>

<div class="line xiaoqu-search">
		<form method="post"  action="<?php echo U('life/index');?>" id="form1" class="form1">
			<div class="form-group">
				<div class="field">
					<div class="input-group">
						<span class="addbtn"><button type="button" class="button icon-search"></button></span>
						<input type="text" class="input" name="keyword" size="50" value="<?php echo ($keyword); ?>" placeholder="请输入关键字"  />
						<span class="addbtn"><button type="submit" class="button">搜索</button></span>
					</div>
				</div>
			</div>
		</form>
	</div>



 <div class="blank-10 bg"></div>
<div class="list-media-x" id="list-media">

	<ul>

  <?php if(is_array($list)): foreach($list as $key=>$var): ?><li class="line ">
      <dt><a class="x3">ID:<?php echo ($var["life_id"]); ?></a><a class="x9 text-right">发布日期：<?php echo (date('Y-m-d H:i:s ',$var["create_time"])); ?></a></dt>
        
      <dd class="zhong">
         <div class="12">
            <p class="text-small">标题：<?php echo ($var["title"]); ?></p>
            <p class="text-small">分类：<?php echo ($cates[$var['cate_id']]['cate_name']); ?></p>
            <p class="text-small">区域：<?php echo ($citys[$var['city_id']]['name']); ?> - <?php echo ($areas[$var['area_id']]['area_name']); ?> - <?php echo ($business[$var['business_id']]['business_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;浏览：<?php echo ($var["views"]); ?>次</p>
            <?php if($var['top_date'] >= $today): ?><p class="text-small text-dot">已置顶(<?php echo ($var["top_date"]); ?>到期)</p><?php endif; ?> 
            <?php if($var['urgent_date'] >= $today): ?><p class="text-small text-blue">已加急(<?php echo ($var["urgent_date"]); ?>到期)</p><?php endif; ?> 
         </div>
      </dd>
       
      <dl>
         <p class="padding-top x12"> 
         
        
         <?php if($var['top_date'] <= $today): ?><a class="button button-small bg-green" target="x-frame"  href="<?php echo U('life/top',array('life_id'=>$var['life_id'],'day'=>7));?>">置顶7天</a>
         <a class="button button-small bg-green"  target="x-frame" href="<?php echo U('life/top',array('life_id'=>$var['life_id'],'day'=>30));?>">置顶30天</a><?php endif; ?> 
         <?php if($var['urgent_date'] <= $today): ?><a class="button button-small bg-dot" target="x-frame" href="<?php echo U('life/urgent',array('life_id'=>$var['life_id'],'day'=>7));?>">加急7天</a>
         <a class="button button-small bg-dot" target="x-frame"  href="<?php echo U('life/urgent',array('life_id'=>$var['life_id'],'day'=>30));?>">加急30天</a><?php endif; ?>
         <?php if(($var["audit"]) == "0"): ?><a class="button button-small bg-gray">等待审核</a>
         <?php else: ?>
         <a class="button button-small bg-dot">正常</a>
         <a class="button button-small bg-blue" target="x-frame"  href="<?php echo U('life/flush',array('life_id'=>$var['life_id']));?>">刷新</a>
         <a class="button button-small bg-blue" href="<?php echo U('mobile/life/detail',array('life_id'=>$var['life_id']));?>">详情</a><?php endif; ?>
        
       </p>
      </dl>
    </li>
 
    <div class="blank-10 bg"></div><?php endforeach; endif; ?>    
  </ul>
</div> 

<div class="blank-20"></div>
<div class="container login-open">
<h5 style="text-align:center"><?php echo ($page); ?><!--分页代码不要忘记加--> </h5>
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

<strong></strong>