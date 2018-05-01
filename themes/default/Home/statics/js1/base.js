$(function () {
    $("#weixin1").hover(
      function () {
          $('#weixin').show();
      },
      function () {
          $('#weixin').hide();
      }
    );
    $("#mobile1").hover(
     function () {
         $('#mobile').show();
     },
     function () {
         $('#mobile').hide();
     }
   );
    initSearch();
    $(".searchBox-input").keydown(function () {
        if (event.keyCode == 13) {
            $(".searchBox-btn").click();
            return false;
        }
    })

    $(".short-pub").hover(function(){
        $(".pub-drop").show();
    },function(){
        $(".pub-drop").hide();
    })
})



//取对象的坐标
function getxy(e) {
    var a = new Array()
    var t = e.offsetTop;
    var l = e.offsetLeft;
    var w = e.offsetWidth;
    var h = e.offsetHeight;
    while (e = e.offsetParent) {
        t += e.offsetTop;
        l += e.offsetLeft;
    }
    a[0] = t; a[1] = l; a[2] = w; a[3] = h;
    return a;
};



function QueryString(qs) {
    var s = location.href.toLowerCase();
    qs = qs.toLowerCase();
    s = s.replace("?", "?&").split("&");
    var re = "";
    for (i = 1; i < s.length; i++)
        if (s[i].indexOf(qs + "=") == 0)
            re = s[i].replace(qs + "=", "");
    return re;
};

var scTop = function () {
    if (typeof window.pageYOffset != 'undefined') {
        return window.pageYOffset;
    } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
        return document.documentElement.scrollTop
    } else if (typeof document.body != 'undefined') {
        return document.body.scrollTop;
    }
}

function addFavorite(url, title) {
    try {
        window.external.addFavorite(url, title);
    }
    catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            alert("请按Ctrl+D键添加到收藏夹");
        }
    }
}


function selectedCheckBox(name) {
    $(":checkbox[name='" + name + "']").each(function () {
        if ($(this).prop("checked")) {
            $(this).removeAttr("checked");
        }
        else {
            $(this).attr("checked", "checked");
        }
    });
    setCheckBoxBg();
}


function toWap(url) {
    $.cookie("holdPc", "");
    window.location.href ="http://"+ url;
}

//设置图片尺寸
$.fn.setImgSize = function (options) {
    var elements = this;
    var $container;
    var settings = {
        width: 0,
        height: 0.
    };
    if (options) {
        $.extend(settings, options);
    };
    function init() {
        elements.each(function () {
            var image = new Image()
            image.src = $(this).attr("src");
            var img = $(this);
            image.onload = function () {
                var toW = settings.width;
                var toH = settings.height;

                //一次缩放
                if (image.width > image.height) {
                    toH = toW * image.height / image.width;
                }
                else if (image.width < image.height) {
                    toW = toH * image.width / image.height;

                }
                else {
                    if (image.height > settings.height || image.width > settings.width) {
                        if (toH > settings.height) {
                            toH = settings.height;
                            toW = toH * image.width / image.height;
                        }
                        else {
                            toW = image.width;
                            toH = toW * image.height / image.width;
                        }
                    }
                }
                //二次缩放
                if (toH > settings.height || toW > settings.width) {
                    if (toH > settings.height) {
                        toH = settings.height;
                        toW = toH * image.width / image.height;
                    }
                    else {
                        toW = image.width;
                        toH = toW * image.height / image.width;
                    }
                }
                img.width(toW).height(toH);
            }
        })

    };
    init();
};


Date.prototype.format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}


$.fn.slide = function (options) {
    var elements = this;
    var $container;
    var slideTime = null;
    var curIndex = 0;
    var settings = {
        speed: '8000',
        fadeSpeed: '500'
    };
    function init() {
        elements.attr("init", "true");
        var showFirst = false;
        if (elements.find("li:hidden").index()>0) {
            showFirst = true;
        }
       
        curIndex = elements.find("li:hidden").index();
        if (elements.find(".pic li").length > 0) {
            if (!showFirst)
                slide_show();
            else {
                elements.find(".number a").eq(0).addClass("cur");
                elements.find(".title a").eq(0).show();
            }
            if (elements.find(".pic li").length == 1)
                return;
            slideTime = setInterval(slide_show, settings.speed);
            elements.find(".number a").mouseover(function () {
                curIndex = $(".number a").index(this);
                slide_show();
            });
            elements.find(".spic li").click(function () {
                curIndex = $(".spic li").index(this);
                slide_show();
            });
            elements.hover(function () {
                if (slideTime) {
                    clearInterval(slideTime);
                }
            }, function () {
                slideTime = setInterval(slide_show, settings.speed);
            });
        }
    };
    if (options) {
        $.extend(settings, options);
    };
    function slide_show() {
        elements.find(".title a").hide();
        elements.find(".title a").eq(curIndex).show();
        elements.find(".number a").eq(curIndex).addClass("cur").siblings("a").removeClass("cur");
        elements.find(".pic li").stop(true, true).fadeOut(settings.fadeSpeed);
        elements.find(".pic li").eq(curIndex).delay(settings.fadeSpeed).fadeIn(settings.fadeSpeed);
        elements.find(".spic li").eq(curIndex).addClass("cur").siblings("li").removeClass("cur");
        curIndex++;
        if (curIndex == elements.find(".pic li").length)
            curIndex = 0;
    };
    if (elements.attr("init") != "true") {
        init();
    }

};

$.fn.showDiv = function (settings) {
    var elements = this;
    var $container;
    var defaultSettings = {
        dockId: '',
        opacity: '0.5',
        clickClose: 'true',
        position: 'bottom',
        showMask: 'false',
        dock: null
    };

    settings = $.extend(defaultSettings, settings);
    function init() {
        if (elements.css("display") != "none") {
            elements.hide();
            mask = $("body").find(".mask");
            mask.hide();
        } else {

            if (settings.showMask) {
                $("body").append('<div class="mask"></div>');
                mask = $("body").find(".mask");
                mask.width(document.body.scrollWidth).height(document.body.scrollHeight).fadeTo("fast", settings.opacity);
                mask.click(function () {
                    mask.remove();
                    elements.hide();
                })
            }
            if (settings.dockId == "") {
                elements.show();
                var divXy = getxy(elements.get(0));
                //elements.css("left", (document.body.clientWidth - elements.outerWidth()) / 2 + "px");
                //elements.css("top", (window.screen.availHeight - elements.outerHeight()) / 2 + document.body.scrollTop);

                elements.css("left", "50%");
                elements.css("top", "50%");
                elements.css("margin-top", "-" + divXy[3] / 2 + "px");
                elements.css("margin-left", "-" + divXy[2] / 2 + "px");

            } else if (settings.dock != null) {
                elements.show();
                var divXy = getxy(elements.get(0));
                elements.css("left", (document.body.clientWidth - elements.outerWidth()) / 2 + "px");
                elements.css("top", (window.screen.availHeight - elements.outerHeight()) / 2 + document.body.scrollTop);

            } else {
                var xy = getxy($("#" + settings.dockId).get(0));
                if (settings.position == "right") {
                    elements.css("left", xy[1] + xy[2] + 5 + "px");
                    elements.css("top", xy[0] + "px");
                }
                else if (settings.position == "top") {
                    elements.css("left", xy[1] + "px");
                    elements.css("top", xy[0] + "px");
                }
                else if (settings.position == "rightBottom") {
                    elements.css("left", xy[1] + xy[2] - elements.width() + "px");
                    elements.css("top", xy[0] + xy[3] + 5 + "px");
                }
                else {
                    elements.css("left", xy[1] + "px");
                    elements.css("top", xy[0] + xy[3] + 5 + "px");
                }
            }
            elements.show();
            elements.click(function () {
                elements.hide();
                $("body").find(".mask").hide();
            })
        }
        

    };
    init();
};

function showMap(_point) {
    if ($("#map-box").length > 0) {
        $(".mask-bg,#map-box").remove();
        return;
    }
    if (point != "") {
        $bg = $("<div class='mask-bg'></div>");
        $("body").append($bg);
        $bg.css("height", document.body.scrollHeight);
        $box = $("<div class='mask-box' id='map-box'><a class='map-close' href='javascript:;showMap();'></a><div id='map-div'></div></div>");
        $("body").append($box);
        var ss = _point.split(',');
        var map = new BMap.Map("map-div");
        var point = new BMap.Point(ss[0], ss[1]);
        map.centerAndZoom(point, 15);
        map.addControl(new BMap.NavigationControl());
        map.addControl(new BMap.ScaleControl());
        map.addControl(new BMap.OverviewMapControl());
        map.enableScrollWheelZoom();
        var marker = new BMap.Marker(point);
        map.addOverlay(marker);
    }
};


function loadUserLogin() {
    $.ajax({
        cache: false,
        url: "/account/islogin", success: function (data) {
            $("#simpleLogin").removeClass("loadingLogin");
            $("#simpleLogin .no").show();
            if (data != "" && data != "\"\"") {
                if(data.NickName==undefined)
                    data = JSON.parse(data);
                $("#simpleLogin .yes").show();
                $("#simpleLogin .no").hide();
                $("#simpleLogin .nickname a").html(data.NickName);
                $("#simpleLogin .t-face img").attr("src", data.Face);;
                $("#simpleLogin .yes .new-msg").before("<div><a href=\"/my/\">个人中心</a></div>");
                if (data.NewMsgCount > 0)
                    $("#simpleLogin .new-msg").show();
                if (data.IsFranAdmin)
                    $("#simpleLogin .yes .new-msg").before("<div><a href=\"/admin/\">后台管理</a></div>");
                if (data.IsShopOwner)
                    $("#simpleLogin .yes .new-msg").before("<div><a href=\"/shopmanage\">快店管理</a></div>");
                if (data.IsFxOwner)
                    $("#simpleLogin .yes .new-msg").before("<div><a href=\"/fxmanage\">分销管理</a></div>");
            }
        }
    })
};

function l_alert(info, error, fun) {
    if (fun == undefined)
        layer.msg(info);
    else {
        layer.alert(info, {
            skin: 'layui-layer-red', closeBtn: 0
        }, function () {
            fun()
        });
    }
};

function toDate(value) {
    if (value == undefined) {
        return "";
    }
    /*json格式时间转js时间格式*/
    value = value.substr(1, value.length - 2);
    var obj = eval('(' + "{Date: new " + value + "}" + ')');
    var dateValue = obj["Date"];
    if (dateValue.getFullYear() < 1900) {
        return "";
    }

    return dateValue.format("yyyy-MM-dd hh:mm");
}

function cookie_queue_add(name, value) {
    var array = new Array();
    var items = $.cookie(name);
    if (items == null)
        items = "";
    items = items.split(',');
    array.push(value);
    for (var i = 0; i < items.length; i++) {
        if (items[i] != value)
            array.push(items[i]);
    }
    $.cookie(name, array.join(','), { path: "/", expires: 365 });
};
var searchTime;
function initSearch() {

    if ($(".searchBox").length > 0) {
        $(".searchBox-select").mouseenter(function () {
            $(".selectBox-wrapper").show();
        }).mouseleave(function () {
            searchTime = setTimeout(hideSearchDrop, 100);
        });
        $(".selectBox-wrapper").mouseenter(function () {
            if (searchTime != null)
                clearTimeout(searchTime);
            $(".selectBox-wrapper").show();
        }).mouseleave(function () {
            searchTime = setTimeout(hideSearchDrop, 100);
        });

        $(".selectBox-wrapper li").click(function () {

            $(".searchBox-select").html($(this).html());
            $(".searchBox-input").attr("c", $(this).attr("c"));
            $(".selectBox-wrapper").hide();
        });

    }
};

function goLogin() {
    var url = "";
    if (window.location.href != undefined)
        url = window.location.href;
    window.location.href = "/account/login?callbackurl=" + url;
};

function hideSearchDrop() {
    if (searchTime != null)
        clearTimeout(searchTime);
    $(".selectBox-wrapper").hide();
};

function search() {
    var k = $.trim($(".searchBox-input").val());
    if (k.length == 0)
        window.location.href = "/so/";
    else
        window.location.href = "/so/s?t=" + $(".searchBox-input").attr("c") + "&key=" + escape(k);
};

function foldAd(id, time, count) {
    if (isNaN(time))
        time = 2000;
    var img = new Image();
    var imgs = $("#ad" + id).find("img");
    if ($("#ad" + id).find("a").eq(0).css("display") != "none") {
        if (imgs.length > 1) {
            var sucCount = 0;
            img.src = imgs.eq(1).attr("src");
            img.onload = function () {
                sucCount++;
                fold();
            };
            imgs.eq(0).load(function () {
                sucCount++;
                fold();
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
        }
        else {
            imgs.eq(0).load(function () {
                $("#ad" + id).delay(time).animate({ height: "0px" }, time, function () {
                    $("#ad" + id).hide();
                });
            })
        }
        var count = $.cookie("foldAdCount" + id);
        if (isNaN(count))
            count = 0;
        count++;
        var myDate = new Date();
       
        $.cookie("foldAdCount" + id, count, { expires:new Date(myDate.toLocaleDateString() + " 23:59:59") });
    }

    function fold() {
       
        var imgs = $("#ad" + id).find("img");
        var time = imgs.eq(0).height() * 10;
        if (sucCount == 2) {
         
            $("#ad" + id).delay(2000).animate({ height: img.height + "px" }, time, function () {
                imgs.eq(1).parent().show();
                imgs.eq(0).parent().hide();
            });
        }
    }
};

$.fn.curtainAd = function (o) {
    o = $.extend({
        top: 60,  //广告距页面顶部距离
        left: 0,//广告左侧距离
        right: 0,//广告右侧距离
        width: 100,  //广告容器的宽度
        height: 360, //广告容器的高度
        minScreenW: 1024,//出现广告的最小屏幕宽度，当屏幕分辨率小于此，将不出现对联广告
        position: "left", //对联广告的位置left-在左侧出现,right-在右侧出现
        allowClose: true //是否允许关闭 
    }, o || {});
    var h = o.height;
    var showAd = true;
    var div = $(this);
    if (o.minScreenW >= $(window).width()) {
        div.hide();
        showAd = false;
    }
    else {
        div.css("display", "block");
        var closeHtml = '<div class="close"></div>';
        switch (o.position) {
            case "left":
                if (o.allowClose) {
                    div.prepend(closeHtml);
                    $(".close", div).click(function () { $(this).hide(); div.hide(); showAd = false; });
                    h += 20;
                }
                div.css({ position: "absolute", left: o.left + "px", top: o.top + "px", overflow: "hidden" });
                break;
            case "right":
                if (o.allowClose) {
                    div.prepend(closeHtml);
                    $(".close", div).click(function () { $(this).hide(); div.hide(); showAd = false; });
                    h += 20;
                }
                div.css({ position: "absolute", left: "auto", right: o.right + "px", top: o.top + "px", overflow: "hidden" });
                break;
        };
    };
    function _scroll() {
        if (!showAd) { return }
        var windowTop = $(window).scrollTop();
        if (div.css("display") != "none")
            div.stop().animate({ top: o.top + windowTop })
    };
    $(window).scroll(_scroll);
    $(document).ready(_scroll);

};

function goTop() {
    $("body,html").animate({ scrollTop: 0 }, 200, function () { i = false })
};

function fav(type, id, callback) {
    $.ajax({
        cache: false,
        url: "/functions/fav?type=" + type + "&id=" + id, success: function (data) {
            if (data.status) {
                alert('收藏成功');
                if (callback != undefined)
                    callback();
            }
            else
                alert(data.error);
        }
    })
};

function unfav(type, id, callback) {
    $.ajax({
        cache: false,
        url: "/functions/unfav?type=" + type + "&id=" + id, success: function (data) {
            if (data.status) {
                alert('取消收藏成功');
                if (callback != undefined)
                    callback();
            }
            else
                alert(data.error);
        }
    })
};

jQuery.cookie = function (name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

function subCHString(str, len, style) {
    var char_length = 0;
    for (var i = 0; i < str.length; i++) {
        var son_str = str.charAt(i);
        encodeURI(son_str).length > 2 ? char_length += 2 : char_length += 1;
        if (char_length >= len) {
            var sub_len = char_length == len ? i + 1 : i;
            return str.substr(0, sub_len) + style;
            break;
        }
    }
    return str;
};

function check_mobilePhone(str) {
    var f = /^[1]+[3,4,5,7,8]+\d{9}$/;
    return f.test(str);
};

function loadVerifyCode(id) {
    $("#" + id).attr("src", "/functions/GetValidateCode?time=" + new Date().getTime());
};


var parserDate = function (date) {
    var t = Date.parse(date.replace(/-/g, "/"));
    if (isNaN(t)) {
        return new Date(Date.parse(date.replace(/\//g, "-")));
    } else {
        return new Date(t);
    }
};

function countDown(obj, callback) {
 
    var _time = obj.attr("time");
   
    var cd = obj.attr("countdown");
    if (cd == "stop")
        return;
    var endTime = parserDate(_time);
    var ts = endTime - (new Date());
    if (ts < 1) {
        if (cd == "going") {
            if (callback != undefined)
                callback();
        }
        obj.attr("countdown", "stop");
        return;
    }
    obj.attr("countdown", "going");
    var d = parseInt(ts / 1000 / 60 / 60 / 24, 10);
    var h = parseInt(ts / 1000 / 60 / 60 % 24, 10);
    var m = parseInt(ts / 1000 / 60 % 60, 10);
    if(obj.attr("shows")=="true")
        var s = (ts / 1000 % 60.0).toFixed(1);
    else
        var s = parseInt(ts / 1000 % 60, 10);
    var ss = parseInt(ts / 1000, 10);
    obj.find("b").eq(0).html(d);
    obj.find("b").eq(1).html(h);
    obj.find("b").eq(2).html(m);
    obj.find("b").eq(3).html(s);
    endTime--;
    if (endTime <= 0) {
        obj.find("b").html("0");

    }

};

function scroll(obj, count) {
    if (count == undefined)
        count = 1;
    var $self = obj.find("ul:first");
    var lineHeight = $self.find("li:first").height();
    for (var i = 0; i < count; i++) {
        $self.find("li").eq(i).clone().appendTo($self);
    }
    $self.animate({ "margin-top": -lineHeight + "px" }, 1000, function () {
        for (var i = 0; i < count; i++) {
            $self.find("li:first").remove();
        }
        $self.css({ "margin-top": "0px" })
    })
}

//function killerrors() {
//    return true;
//}
//window.onerror = killerrors;