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
		<a class="top-addr" href="<?php echo U('life/index');?>"><i class="icon-angle-left"></i></a>
	</div>
	<div class="top-title">
		多城市商家入驻
	</div>
</header>

<form class="fabu-form" method="post"  target="x-frame" action="<?php echo U('apply/index');?>">


<div class="blank-10 bg border-top"></div>

<div class="blank-10"></div>
 <div class="Upload-img-box">
                        <div class="Upload-btn"><input type="file" id="fileToUpload" name="fileToUpload" data-role="none">上传图片</div>
                        <div class="Upload-img">
                            <div class="list-img loading" style="display:none;"><img src=""></div>
                            <div class="list-img jq_photo" style="display:none;"></div>
                        </div>
                    </div>
                    <script type="text/javascript" src="/static/default/wap/js/ajaxfileupload.js"></script>
                    <script>
                        function ajaxupload() {
                            $(".loading").show();
                            $.ajaxFileUpload({
                                url: '<?php echo U("app/upload/upload",array("model"=>"life"));?>',
                                type: 'post',
                                fileElementId: 'fileToUpload',
                                dataType: 'text',
                                secureuri: false, //一般设置为false
                                success: function (data, status) {
                                    $(".loading").hide();
                                    var str = '<img src="__ROOT__/attachs/' + data + '"><input type="hidden" name="data[photo]" value="' + data + '" />';
                                    $(".jq_photo").show().html(str);
                                    $("#fileToUpload").unbind('change');
                                    $("#fileToUpload").change(function () {
                                        ajaxupload();
                                    });
                                }
                            });
                        }
                        $(document).ready(function () {
                            $("#fileToUpload").change(function () {
                                ajaxupload();
                            });
                            $(document).on("click", ".photo img", function () {
                                $(this).parent().remove();
                            });
                        });
                    </script>



<div class="row">
	<div class="line">
		<span class="x3">商户名称</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[shop_name]" />
		</span>
	</div>
</div>



<div class="row">
	<div class="line">
		<span class="x3">商家分类</span>
		<span class="x4">
			<select name="parent_id" id="parent_id" class="text-select">
				<option value="0" selected="selected">请选择</option>
				<?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"><?php echo ($var["cate_name"]); ?></option><?php endif; endforeach; endif; ?>
			</select>
		</span>
		<span class="x5">
			<select name="data[cate_id]" id="cate_id" class="text-select">
				<option value="0" selected="selected">← 选择子分类</option>
			    <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"><?php echo ($var["cate_name"]); ?></option><?php endif; endforeach; endif; ?>
			</select>
		</span>
         <script>

                                $(document).ready(function (e) {

                                    $("#parent_id").change(function () {

                                        var url = '<?php echo U("apply/shopcate",array("parent_id"=>"0000"));?>';

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
</div>


 



<div class="row">
	<div class="line">
		<span class="x3">地区</span>
		<span class="x3">
			<select name="data[city_id]" id="city_id" class="text-select">
				<option value="0" selected="selected">城市</option>
			</select>
		</span>
		<span class="x3">
			<select name="data[area_id]" id="area_id" class="text-select">
				<option value="0" selected="selected">← 地区</option>
			</select>
		</span>
        
        <span class="x3">
			<select name="data[business_id]" id="business_id" class="text-select">
				<option value="0" selected="selected">← 商圈</option>
			</select>
		</span>
        
        
	</div>
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
                
</div>
<div class="row">
	<div class="line">
		<span class="x3">地址</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[addr]"/>
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">靠近</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[near]" />
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">电话</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[tel]"  />
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">联系人</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[contact]" />
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">营业时间</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[business_time]" />
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">商铺描述</span>
		<span class="x9">
			<textarea rows="5" name="details" class="text-area" placeholder="请输入内容"></textarea>
		</span>
	</div>
</div>

    
<!--商家地图开始-->
<div class="line padding border-bottom">		
        <div id="login-input">
                        <div class="life-infor-float" >
                             <p class="life-infor-p">所在坐标：点击下面地图可以获取~</p>
                            <div id="login-input" class="life-infor-float">
                                <div class="left"><span>经度：</span><input type="text" name="data[lng]" id="data_lng" value="<?php echo (($lng)?($lng):''); ?>"/></div>
                                <div class="right"><span>纬度：</span><input type="text" name="data[lat]" id="data_lat" value="<?php echo (($lat)?($lat):''); ?>"/></div>
                            </div>
                            <div class="life-infor-float" >
                                <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=7b92b3afff29988b6d4dbf9a00698ed8"></script>
                                <div id="allmap" style="width: 100%; height:300px;"></div>
                                <script type="text/javascript">

                                // 百度地图API功能
                                var map = new BMap.Map("allmap");
                                map.centerAndZoom(new BMap.Point("<?php echo ($lng); ?>", "<?php echo ($lat); ?>"), 16);
                                function selectCallBack(id, name, v1, v2) {
                                    $("#" + id).val(v1);
                                    $("#" + name).val(v2);
                                    var point = new BMap.Point(v1, v2);
                                    var marker = new BMap.Marker(point);  // 创建标注
                                    map.clearOverlays();
                                    map.addOverlay(marker);              // 将标注添加到地图中

                                }
                                function showInfo(e) {
                                    selectCallBack('data_lng', 'data_lat', e.point.lng, e.point.lat);
                                }
                                map.addEventListener("click", showInfo);
                                </script>
                            </div>
                        </div>
                    </div>                    
	</div>
<!--商家地图结束-->

	<div class="container">
		<div class="blank-30"></div>
		<button  type="submit" class="button button-block button-big bg-dot">添加商家</button>
		<div class="blank-30"></div>
	</div>
		
</form>
		
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