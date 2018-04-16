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

<style>

#login_frm { overflow: initial;float:right;}

.header--mini{min-width:980px;color:#666}

.header--mini .wrapper{margin:0 auto; padding-top:20px;width:980px}

.header--mini .site-logo{width:128px;width:54px;height:36px;background-position:-262px -165px;}

.header--mini .login-block{float:right;line-height:36px}

.header--mini .login-block .tip{margin-right:10px;vertical-align:sub}

.header--mini .login-block{float:right;line-height:36px}

.header--mini .login-block .login{padding:3px 10px}

.btn-small{padding:3px 20px;font-size:12px}

.loginBox {padding-top: 20px;}

.loginR {width: 530px;float: left;margin: 20px 0 0 30px !important; }

.btn{background-color:<?php echo ($color); ?>!important;background-size:100%;background-image:-webkit-linear-gradient(top,<?php echo ($color); ?>!important,<?php echo ($color); ?>!important);background-image:linear-gradient(to bottom,<?php echo ($color); ?>!important,<?php echo ($color); ?>!important)}

.btn,.btn-hot,.btn-normal{display:inline-block;vertical-align:middle;padding:8px 20px;font-size:14px;font-weight:700;-webkit-font-smoothing:antialiased;line-height:1.5;letter-spacing:.1em;text-align:center;text-decoration:none;border-width:0 0 1px;border-style:solid;background-repeat:repeat-x;border-radius:2px;-moz-user-select:-moz-none;-ms-user-select:none;-webkit-user-select:none;user-select:none;cursor:pointer}

.btn,.btn.hover,.btn:focus,.btn:hover{border-color:#0B8074;color:#fff;zoom:1}

.footer--mini{border-top:1px solid #EEE;padding-top:20px;text-align:center;margin-top: 20px;}

.footer--mini p span{ font-size:12px;}

.footer--mini .copyright{font-size:12px;font-family:initial}

.footer--mini .copyright a,.footer--mini .copyright span{color:#999}

.loginInput2016{width: 270px;}

.loginInput2017{width:120px;float: left;}

.loginInput2016, .loginInput2017{height:38px; line-height:38px;color: #888;font-size: 15px;padding-left: 10px;border: 1px solid #aaa;}

.qqlink .qqlink_wz{width:120px;}



</style>

<div class="header--mini">

    <div class="wrapper cf">

    	<?php if(!empty($city['photo'])): ?><h1><a href="<?php echo U('pchome/index/index');?>"><img src="__ROOT__/attachs/<?php echo ($city['photo']); ?>" width="215" height="65" /></a></h1>

            <?php else: ?>

            <h1><a href="<?php echo U('pchome/index/index');?>"><img src="__ROOT__/attachs/<?php echo ($CONFIG["site"]["logo"]); ?>" /></a></h1><?php endif; ?> 

    </div>

</div>



	<div class="main">

		<div class="loginBox">

			<form id="login_frm"  action="<?php echo U('passport/login');?>" method="post" target="baocms_frm">

				<div class="loginMid">

				

				<div class="loginMidNr">

				<table cellpadding="0" cellspacing="0" width="100%" class="loginTable">

					<tr>

						<td>

							<input type="text" id="account"  name="account" placeholder="邮箱/手机号"    class="loginInput2016" value="" />

						</td>

					</tr>

					<tr>

						<td>

							<input type="password" id="password"  name="password"  class="loginInput2016" placeholder="密码" value="" />

						</td>

					</tr>

					<tr>

						<td>

							<input  class="loginInput2017"  id="yzm"  name="yzm"  type="text"  value="" placeholder="验证码" />

							<img id="bao_img_code" src="__ROOT__/index.php?g=app&m=verify&a=index&mt=<?php echo time();?>" /><em><a rel="bao_img_code" class="yzm_code" href="javascript:void(0);">换一张</a></em></td>

					</tr>

					<tr>

						<td class="agreen"><a href="<?php echo U('passport/forget',array('way'=>2));?>">忘记密码?</a></td>

					</tr>

                    <tr>

						<td class="agreen"><a href="<?php echo U('passport/register');?>">还没有账号?免费注册</a></td>

					</tr>

					<tr>

						<input type="hidden" name="backurl" value="<?php echo ($backurl); ?>" />

						<td>

							<input type="submit" class="btn loginInput5" value="登 陆" />

						</td>

					</tr>

					<tr>

						<td><a href="<?php echo U('passport/register');?>" class="btn loginInput6">注 册</a></td>

					</tr>

				</table>

			</form>

			<ul class="qqlink">

				<li class="qqlink_wz">合作账号登录：</li>

				<?php if(!empty($CONFIG['connect']['qq_app_id'])): ?><li><a href="<?php echo U('passport/qqlogin');?>"></a></li><?php endif; ?>

				<?php if(!empty($CONFIG['connect']['wx_app_id'])): ?><li class="li2"><a href="<?php echo U('passport/wxlogin');?>"></a></li><?php endif; ?>

				<?php if(!empty($CONFIG['connect']['wb_app_id'])): ?><li class="li3"><a href="<?php echo U('passport/wblogin');?>"></a></li><?php endif; ?>

			</ul>

		</div>

	</div>

    

	<script>

    $(document).ready(function()    {      

        $("#account").focusout(function() {

            if($(this).val() ==""){ 

                $(this).val("请用您账户或者手机号码登录");

            }

        });

        $("#yzm").focusout(function() {

            if($(this).val() ==""){ 

                $(this).val("请输入验证码");

            }

        });

    });

    </script>

	<div class="loginR"> 

    

    <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, bg_date <= '{$today}' AND end_date >= '{$today}' AND city_id IN ({$city_ids}) AND closed=0 AND site_id=59,0,3,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" bg_date <= '{$today}' AND end_date >= '{$today}' AND city_id IN ({$city_ids}) AND closed=0 AND site_id=59")->order("orderby asc")->limit("0,3")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><a href="<?php echo ($item["link_url"]); ?>" title="<?php echo ($item["title"]); ?>" target="_blank"><img width="480" height="370" src="<?php echo config_img($item['photo']);?>" draggable="false"></a> <?php endforeach; ?>

		

	</div>

</div>

</div>

<div class="footer--mini">

    <p class="copyright">

       <span>copyright 2013-2113 <?php echo ($_SERVER['HTTP_HOST']); ?> All Rights Reserved <?php echo ($CONFIG["site"]["sitename"]); ?>版权所有 - <?php echo ($CONFIG["site"]["tongji"]); ?></span>

        <span class="f1"><?php echo ($CONFIG["site"]["icp"]); ?> </span>

    </p>

</div>