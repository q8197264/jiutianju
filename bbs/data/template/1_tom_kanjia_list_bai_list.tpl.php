<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html>
<head>
<?php if($isGbk) { ?>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<?php } else { ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php } ?>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>全部砍价商品</title>
<link href="source/plugin/tom_kanjia/images/list/reset.css" rel="stylesheet" type="text/css">
<link href="source/plugin/tom_kanjia/images/list/bai_list.css" rel="stylesheet" type="text/css">
<script src="source/plugin/tom_kanjia/images/list/click300ms.js" type="text/javascript"></script>
<script src="source/plugin/tom_kanjia/images/list/jquery.min2.14.js" type="text/javascript"></script>
</head>
<body>
<?php if($kanjiaConfig['list_pic_url']) { ?>    
<section class="x_header-bar">
<div class="header-banner clearfix">
<img src="<?php echo $kanjiaConfig['list_pic_url'];?>" />
</div>
</section>
<?php } ?>
<section class="x_details clearfix">
<div class="x_browse clearfix">
<span class="b-num fl">浏览人数：<span class="red-color"><?php echo $clicksAllCount;?></span></span>
<span class="fr">参与人数：<span class="red-color"><?php echo $userAllCount;?></span></span>
</div>
<div class="shop-area">
<ul class=" shop-list clearfix">
            <?php if(is_array($kanjiaList)) foreach($kanjiaList as $key => $val) { ?><li class="clearfix">
<div class="sp-img fl">
<img src="<?php echo $val['pic_url'];?>" />
<span class="sy">剩<?php echo $val['shengyu_days'];?>天</span>
</div>
<div class="xq">
<div class="q-title"><?php echo $val['title'];?></div>
<div class="q-two ">
<span class="old-p fl">原价：<span class="t-d">￥<?php echo $val['goods_price'];?></span></span>
<span class="q-cy red-color fr"><?php echo $val['user_count'];?>人参与</span>
</div>
<div class="q-price clearfix">
<?php if($kanjiaConfig['open_base_price']==1 ) { ?><span class="new-p red-color fl">￥<?php echo $val['base_price'];?></span><?php } ?>
                        <?php if($val['doing'] == 1) { ?>
                            <?php if($val['must_gz'] == 1 && $showGuanzuBox == 1 ) { ?>
                            <a href="<?php echo $val['guanzu_url'];?>"><span class="ljk red-bg fr">立即砍价</span></a>
                            <?php } else { ?>
                            <a href="plugin.php?id=tom_kanjia&amp;kid=<?php echo $val['id'];?>&amp;randKey=<?php echo $val['rand'];?>"><span class="ljk red-bg fr">立即砍价</span></a>
                            <?php } ?>
                        <?php } ?>
                        <?php if($val['doing'] == 2) { ?>
                        <span class="ljk fr">未开始</span>
                        <?php } ?>
                        <?php if($val['doing'] == 3) { ?>
                        <span class="ljk fr">已结束</span>
                        <?php } ?>
</div>
</div>
</li>
            <?php } ?>
</ul>
        <div class="pages clearfix">
            <ul class="clearfix">
                <li style="width: 40%;"><?php if($page > 1) { ?><a href="<?php echo $prePageUrl;?>">上一页</a><?php } else { ?><span>上一页</span><?php } ?></li>
                <li style="width: 20%;"><span><?php echo $page;?>/<?php echo $allPageNum;?></span></li>
                <li style="width: 40%;"><?php if($showNextPage == 1) { ?><a href="<?php echo $nextPageUrl;?>">下一页</a><?php } else { ?><span>下一页</span><?php } ?></li>
            </ul>
        </div>
</div>
</section>
</body>
</html>