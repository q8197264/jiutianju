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
.header--mini{border-bottom:2px solid <?php echo ($color); ?>!important;color:#666;display:block;min-width:980px}
.header--mini .wrapper{margin:0 auto;padding:20px 0;width:980px}
.header--mini .site-logo{width:128px;width:54px;height:36px;background-position:-262px -165px}
.header--mini .login-block{float:right;line-height:36px}
.header--mini .login-block .tip{margin-right:10px;vertical-align:sub}
.header--mini .login-block{float:right;line-height:36px}
.header--mini .login-block .login{padding:3px 10px}
.btn-small{padding:3px 20px;font-size:12px}
.btn{background-color:<?php echo ($color); ?>!important;background-size:100%;background-image:-webkit-linear-gradient(top,<?php echo ($color); ?>!important,<?php echo ($color); ?>!important);background-image:linear-gradient(to bottom,<?php echo ($color); ?>!important,<?php echo ($color); ?>!important)}
.btn,.btn-hot,.btn-normal{display:inline-block;vertical-align:middle;padding:8px 20px;font-size:14px;font-weight:700;-webkit-font-smoothing:antialiased;line-height:1.5;letter-spacing:.1em;text-align:center;text-decoration:none;border-width:0 0 1px;border-style:solid;background-repeat:repeat-x;border-radius:2px;-moz-user-select:-moz-none;-ms-user-select:none;-webkit-user-select:none;user-select:none;cursor:pointer}
.btn,.btn.hover,.btn:focus,.btn:hover{border-color:#0B8074;color:#fff;zoom:1}
.loginInput7{width:320px}
.loginInput8{margin-left:20px}
.loginInput2016{width: 270px;}
.loginInput2017{width:120px;float: left;}
.loginInput2016, .loginInput2017{height:38px; line-height:38px;color: #888;font-size: 15px;padding-left: 10px;border: 1px solid #aaa;}
.footer--mini{border-top:1px solid #EEE;padding-top:20px;text-align:center;margin-top:20px}
.footer--mini p span{font-size:12px}
.qqlink .qqlink_wz{width:120px;}
.footer--mini .copyright{font-size:12px;font-family:initial}
.footer--mini .copyright a,.footer--mini .copyright span{color:#999}
.loginTable tr td span{color:#888;top: -2px;}
.loginTable tr td span.error{color:red!important}
.register_tabLi.on span{ background:#fff;color:<?php echo ($color); ?>;padding:3px 15px; }

.register_tabLi span .icon {float: left;margin-right:-5px;font-size: 0;margin-top: 15px;}
.register_tabLi span .icon--email {width: 14px;height: 11px;background-position: -846px -677px;}
.register_tabLi span .icon--phone { width: 9px;height: 13px; background-position: -373px -706px;}
.register_tabLi span .icon--email, .register_tabLi span .icon--phone {background-image: url(/themes/default/Pchome/statics/images/login.png);}

.ywz_zhuce_huixian{background:#d6d3d3;}
.ywz_zhuce_hongxianwenzi{float:left; width:80px; margin-left:5px; text-align:center; color:#b0adad; font-size:12px; }
.ywz_zhuce_hongxian{ background:#ff3300; }
.ywz_zhuce_hongxian2{ background: #099;}
.ywz_zhuce_hongxian3{ background: #060;}
.ywz_zhuce_huixian, .ywz_zhuce_hongxian,  .ywz_zhuce_hongxian2, .ywz_zhuce_hongxian3{float:left; width:80px; height:10px; margin-top:10px; _margin-top:0px; margin-left:5px; _height:8px;font-size:0px;}
</style>



<div class="header--mini">
    <div class="wrapper cf">
        <a class="site-logo" href="/">欢迎注册<?php echo ($CONFIG['site']['sitename']); ?></a>
        <div class="login-block">
            <span class="tip">已有<?php echo ($CONFIG['site']['sitename']); ?>账号？</span>
            <a class="btn btn-small login" href="<?php echo U('passport/login');?>">登录</a>
        </div>
    </div>
</div>


	<div class="main"> 
		<script>
                var mobile_timeout;
                var mobile_count = 100;
                var mobile_lock = 0;
                $(function () {
                    $("#m_zcyz").click(function () {
                        if (mobile_lock == 0) {
                            mobile_lock = 1;
                            $.ajax({
                                url: '<?php echo U("pchome/passport/sendsms");?>',
                                data: 'mobile=' + $("#mobile").val(),
                                type: 'post',
                                success: function (data) {
                                    if (data == 1) {
                                        mobile_count = 60;
                                        BtnCount();
                                    } else {
                                        mobile_lock = 0;
                                        error(data);
                                    }
                                }
                            });
                        }
                    });
                });

                BtnCount = function () {
                    if (mobile_count == 0) {
                        $('#m_zcyz').html("重新发送");
                        mobile_lock = 0;
                        clearTimeout(mobile_timeout);
                    }
                    else {
                        mobile_count--;
                        $('#m_zcyz').html("获取(" + mobile_count.toString() + ")秒");
                        mobile_timeout = setTimeout(BtnCount, 1000);
                    }
                };
            </script>

		<div class="loginBox">

			<div class="loginMid2">

				

				<div class="loginMidNr">

					<div class="register_tab"> 

						<script>
								$(function () {
									$(".register_tabLi").each(function (i) {
										$(this).click(function () {
											$(".register_tabLi").removeClass("on");
											$(this).addClass("on");
											$(".loginTableBox").each(function (e) {
												if (i == e) {
													$(".loginTableBox").removeClass("on");
													$(this).addClass("on");
												}
												else {
													$(this).removeClass("on");
												}
											});
										});
									});
								});
						</script>

						<ul>

					
							<li class="register_tabLi on"><span><i class="icon icon--email"></i>手机注册</span></li>
                            <?php if($CONFIG['register']['register_mobile'] == 0): ?><li class="register_tabLi"><span><i class="icon icon--phone"></i>邮箱注册</span></li><?php endif; ?>

						</ul>

					</div>

 

					<form  action="<?php echo U('passport/register');?>" method="post" target="baocms_frm" >
						<div class="loginTableBox on">
							<table cellpadding="0" cellspacing="0" width="100%" class="loginTable">
                            <?php if($fuser): ?><tr>
										<td>推荐人：<?php echo ($fuser['nickname']); ?><input type="hidden" name="fuid" value="<?php echo ($fuser['user_id']); ?>" /></td>
									</tr><?php endif; ?>
								<tr>
									<td>
										<input  class="loginInput2016"  name="data[account]" id="mobile"  type="text"  placeholder="手机号"  />
                                        <span>请填写手机号码</span>
									</td>
								</tr>
								<tr>
									<td>
										<input name="data[password]" type="password" placeholder="创建密码"  id="tbPassword" class="loginInput2016 ywz_zhuce_kuangwenzi1" value="" />
                                        
                                        <div class="ywz_zhuce_huixian" id='pwdLevel_1'> </div>
                                        <div class="ywz_zhuce_huixian" id='pwdLevel_2'> </div>
                                        <div class="ywz_zhuce_huixian" id='pwdLevel_3'> </div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 弱</div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 中</div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 强</div>
            
            
                                        <span>密码请输入<a style="color:#F00"><?php echo ($CONFIG['register']['register_password']); ?>-20</a>个字符</span>
									</td>
								</tr>
								<tr>
									<td>
										<input name="password2"  type="password"  placeholder="再次输入密码"  class="loginInput2016" value="" />
                                        
                                       
                                        <span>重复输入密码</span>
									</td>
								</tr>
								<tr>
									<td>
										<input  class="loginInput2017 register_input" name="scode"  type="text"  value="" placeholder="短信验证码" />
										<a href="javascript:void(0);" class="btn loginInput8" id="m_zcyz" style="text-decoration: none;">发送验证码</a></td>
								</tr>
								<tr>
									<td class="agreen">
										<input type="checkbox"  name="is_agree" <?php if($CONFIG['register']['register_service'] == 1): ?>value="1"  checked="checked"  <?php else: ?>value="0"<?php endif; ?>/>
										阅读并同意<a href="<?php echo ($CONFIG['register']['register_service_url']); ?>" target="_blank">&nbsp;服务协议</a></td>
								</tr>
								<tr>
									<td>
										<input type="submit" value="注册" class="btn loginInput7"/>
									</td>
								</tr>
							</table>
						</div>
					</form>
                    
                    
      <?php if($CONFIG['register']['register_mobile'] == 0): ?><form  action="<?php echo U('passport/register');?>" method="post" target="baocms_frm"  name="register">
						<div class="loginTableBox">
							<table cellpadding="0" cellspacing="0" width="100%" class="loginTable">
                            <?php if($fuser): ?><tr>
										<td>推荐人：<?php echo ($fuser['nickname']); ?><input type="hidden" name="fuid" value="<?php echo ($fuser['user_id']); ?>" /></td>
									</tr><?php endif; ?>
								<tr>
									<td>
										<input  class="loginInput2016"  name="data[account]"   id="reg-account" type="text"  placeholder="邮箱地址"  />
                                        <span>请输合法的电子邮件</span>
									</td>
								</tr>
								<tr>
									<td>
										<input name="data[password]" type="password"   id="tbPassword"  placeholder="创建密码" class="loginInput2016  ywz_zhuce_kuangwenzi1" value="" />
                                         <div class="ywz_zhuce_huixian" id='pwdLevel_1'> </div>
                                        <div class="ywz_zhuce_huixian" id='pwdLevel_2'> </div>
                                        <div class="ywz_zhuce_huixian" id='pwdLevel_3'> </div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 弱</div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 中</div>
                                        <div class="ywz_zhuce_hongxianwenzi"> 强</div>
                                        
                                        
                                        <span>密码请输入<a style="color:#F00"><?php echo ($CONFIG['register']['register_password']); ?>-20</a>个字符</span>
									</td>
								</tr>
								<tr>
									<td>
										<input name="password2"  type="password" placeholder="再次输入密码"  class="loginInput2016" value="" />
                                        <span>请重复输入您的密码</span>
									</td>
								</tr>
                                
                                <?php if($CONFIG['register']['register_yzm'] == 1): ?><tr>
									<td>
										<input  class="loginInput2017" name="yzm"  type="text"  value="" placeholder="输入验证码" />
										<img id="bao_img_code" src="__ROOT__/index.php?g=app&m=verify&a=index&mt=<?php echo time();?>" /><em><a rel="bao_img_code" class="yzm_code" href="javascript:void(0);">换一张</a></em></td>
								</tr><?php endif; ?>
								<tr>
									<td class="agreen">

										<input type="checkbox" name="is_agree"  <?php if($CONFIG['register']['register_service'] == 1): ?>value="1"  checked="checked"  <?php else: ?>value="0"<?php endif; ?>/>

										阅读并同意<a href="<?php echo ($CONFIG['register']['register_service_url']); ?>" target="_blank">&nbsp;服务协议</a></td>

								</tr>
								<tr>
									<td>
										<input type="submit" value="注册" class="btn loginInput7"/>
									</td>
								</tr>
							</table>
						</div><?php endif; ?>					</form>
					

				</div>

			</div>   

		</div>

        

     <script type="text/javascript">
        $('#tbPassword').focus(function () {
            $('#pwdLevel_1').attr('class', 'ywz_zhuce_hongxian');
            $('#tbPassword').keyup();
        });
        $('#tbPassword').keyup(function () {
            var __th = $(this);

            if (!__th.val()) {
                $('#pwd_tip').hide();
                $('#pwd_err').show();
                Primary();
                return;
            }
            if (__th.val().length < 6) {
                $('#pwd_tip').hide();
                $('#pwd_err').show();
                Weak();
                return;
            }
            var _r = checkPassword(__th);
            if (_r < 1) {
                $('#pwd_tip').hide();
                $('#pwd_err').show();
                Primary();
                return;
            }

            if (_r > 0 && _r < 2) {
                Weak();
            } else if (_r >= 2 && _r < 4) {
                Medium();
            } else if (_r >= 4) {
                Tough();
            }
            $('#pwd_tip').hide();
            $('#pwd_err').hide();
        });
        function Primary() {
            $('#pwdLevel_1').attr('class', 'ywz_zhuce_huixian');
            $('#pwdLevel_2').attr('class', 'ywz_zhuce_huixian');
            $('#pwdLevel_3').attr('class', 'ywz_zhuce_huixian');
        }

        function Weak() {
            $('#pwdLevel_1').attr('class', 'ywz_zhuce_hongxian');
            $('#pwdLevel_2').attr('class', 'ywz_zhuce_huixian');
            $('#pwdLevel_3').attr('class', 'ywz_zhuce_huixian');
        }

        function Medium() {
            $('#pwdLevel_1').attr('class', 'ywz_zhuce_hongxian');
            $('#pwdLevel_2').attr('class', 'ywz_zhuce_hongxian2');
            $('#pwdLevel_3').attr('class', 'ywz_zhuce_huixian');
        }

        function Tough() {
            $('#pwdLevel_1').attr('class', 'ywz_zhuce_hongxian');
            $('#pwdLevel_2').attr('class', 'ywz_zhuce_hongxian2');
            $('#pwdLevel_3').attr('class', 'ywz_zhuce_hongxian3');
        }
        function checkPassword(pwdinput) {
            var maths, smalls, bigs, corps, cat, num;
            var str = $(pwdinput).val()
            var len = str.length;

            var cat = /.{16}/g
            if (len == 0) return 1;
            if (len > 16) { $(pwdinput).val(str.match(cat)[0]); }
            cat = /.*[\u4e00-\u9fa5]+.*$/
            if (cat.test(str)) {
                return -1;
            }
            cat = /\d/;
            var maths = cat.test(str);
            cat = /[a-z]/;
            var smalls = cat.test(str);
            cat = /[A-Z]/;
            var bigs = cat.test(str);
            var corps = corpses(pwdinput);
            var num = maths + smalls + bigs + corps;

            if (len < 6) { return 1; }

            if (len >= 6 && len <= 8) {
                if (num == 1) return 1;
                if (num == 2 || num == 3) return 2;
                if (num == 4) return 3;
            }

            if (len > 8 && len <= 11) {
                if (num == 1) return 2;
                if (num == 2) return 3;
                if (num == 3) return 4;
                if (num == 4) return 5;
            }

            if (len > 11) {
                if (num == 1) return 3;
                if (num == 2) return 4;
                if (num > 2) return 5;
            }
        }
        function corpses(pwdinput) {
            var cat = /./g
            var str = $(pwdinput).val();
            var sz = str.match(cat)
            for (var i = 0; i < sz.length; i++) {
                cat = /\d/;
                maths_01 = cat.test(sz[i]);
                cat = /[a-z]/;
                smalls_01 = cat.test(sz[i]);
                cat = /[A-Z]/;
                bigs_01 = cat.test(sz[i]);
                if (!maths_01 && !smalls_01 && !bigs_01) { return true; }
            }
            return false;
        }
    </script>     





        <div class="qqdibu" style=" margin-left:90px;clear:both">
            <ul class="qqlink">
						<li class="qqlink_wz">其他合作账号登录：</li>
						<?php if(!empty($CONFIG['connect']['qq_app_id'])): ?><li><a href="<?php echo U('passport/qqlogin');?>"></a></li><?php endif; ?>
						<?php if(!empty($CONFIG['connect']['wb_app_id'])): ?><li class="li3"><a href="<?php echo U('passport/wblogin');?>"></a></li><?php endif; ?>
					</ul>
            </div>
	</div>
</div>

<div class="footer--mini">
    <p class="copyright">
       <span>copyright 2013-2113 <?php echo ($_SERVER['HTTP_HOST']); ?> All Rights Reserved <?php echo ($CONFIG["site"]["sitename"]); ?>版权所有 - <?php echo ($CONFIG["site"]["tongji"]); ?></span>
        <span class="f1"><?php echo ($CONFIG["site"]["icp"]); ?> </span>
    </p>
</div>