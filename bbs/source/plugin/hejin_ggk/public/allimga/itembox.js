//itembox 通用项目



$(document).ready(function () {
    var css = '.data-itembox{width:100%;display:box;margin:0 0;background-color:white;border-bottom:0.05rem solid #DDDDDD;}';



    //项目 > 点击感应 empp
    css += '.empp{background-color:rgba(0,0,0,0);position:absolute;}';

    //项目 > 图片框(左边) O7Vv
    css += '.O7Vv{padding:0.2rem 0 0.2rem 0.2rem;}';
    css += '.O7Vv IMG{background-color:#F8F8F8;background-image:url(' + $.icon.loading + ');background-position:center center;}';


    //项目 > 信息框(中间) e5Ax
    //项目 > 信息框(中间) > 上框(标题) Ol5y
    //项目 > 信息框(中间) > 下框 cPRw
    css += '.e5Ax{padding:0.2rem 0 0.2rem 0.2rem;box-flex:1.0;}';
    css += '.Ol5y{white-space:nowrap;}';
    css += '.Ol5y H1{font-size:0.4rem;}';
    css += '.cPRw H2{font-size:0.4rem;color:#777;}';
    css += '.cPRw H2 STRONG{color:red;}';


    //项目 > 右边框 e5Ax
    css += '.U9Vo{font-size:0.5rem;}';


    //项目 > 调用模式 > 价格 uwMH
    css += '.uwMH{font-size:0.38rem;color:#777;}';
    css += '.uwMH STRONG{color:red;}';


    //附加右边红色按钮 MI3B
    css += '.MI3B{position:relative;border-radius:0.08rem;display:inline-block;color:white;font-size:0.4rem;background-color:#D42C2C;margin:0 0.15rem 0 0;padding:0.15rem 0.3rem;}';



    $.unobtrusive('itembox', { data: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;
        var userstatus = $.userstatus();
        var data = params.data;



        var CND = false;

        //示例
        var DDD = function (s) {
            s = s.replace(/\{(([A-Z]{3})\(([\w\,\.]+)\)#)?([a-zA-Z][0-9a-zA-Z]*)\}/ig, function ($$, $1, $2, $3, $4) {
                var result = data[$4];

                if ($2 == 'PRI') {
                    result = '<strong>' + result + '榄币</strong><span>(即¥' + result / 100 + ')</span>';
                }
                if ($2 == 'IMG') {
                    var size = parseFloat($3) * 50;
                    if (isNaN(size) || size <= 0) {
                        size = 100;
                    }

                    if (!result) {
                        result = $.icon.nopicture;
                    }
                    else {
                        result = $.narrowImage(result, size, size);
                    }


                }
                if ($2 == 'CND') {
                    CND = true;
                    result = '<span class="data-countdown" data-countdown="true" data-countdown-count="' + result + '" data-countdown-conclusion="..."></span>';
                }

                return result;
            });

            s = s.replace(/\[/ig, '<');
            s = s.replace(/\]/ig, '>');

            return s;
        };



        //项目 > 点击感应 empp
        var empp = $('<div class="empp"></div>').appendTo(dom);


        //项目 > 图片框(左边) O7Vv
        //项目 > 图片框(左边) O7Vv
        var O7Vv;
        if (data.templateImage) {
            O7Vv = $('<div class="O7Vv">' + DDD(data.templateImage) + '</div>').appendTo(dom);

            O7Vv.find('img').each(function () {
                var $this = $(this);
                $this.attr('data-xti5', $this.attr('src'));
                $this.attr('src', $.icon.blank);
            });

            O7Vv.find('img').lazyload({ data_attribute: 'xti5' });
        }
        else if (!data.templateImage && data.image) {
            O7Vv = $('<div class="O7Vv"><img style="width:2.2rem;height:2.2rem;" data-xti5="' + $.narrowImage(data.image, 110, 110) + '" src="' + $.icon.blank + '"/></div>').appendTo(dom);
            O7Vv.find('img').lazyload({ data_attribute: 'xti5' });
        }
        else if (!data.templateImage && data.image === '') {
            O7Vv = $('<div class="O7Vv"><img style="width:2.2rem;height:2.2rem;" src="' + $.icon.nopicture + '"/></div>').appendTo(dom);
        }


        var htmlInfo = '';
        //项目 > 信息框(右边)

        if (data.templateInfo) {
            $.each(data.templateInfo.split('$'), function (index, value) {
                htmlInfo += '<h2>' + DDD(value) + '</h2>';
            });

            htmlInfo = '<div style="float:left;">' + htmlInfo + '</div>';
        }


        var htmlTitle = '';
        if (data.title) {
            htmlTitle = '<div class="Ol5y"><h1 class="ellipsis">' + $.htmlEncode(data.title) + '</h1></div>';
        }


        //项目 > 信息框(中间) e5Ax
        var e5Ax = $('<div class="e5Ax">' + htmlTitle + '<div class="cPRw">' + htmlInfo + '<div style="height:0;clear:both;"></div></div></div>').appendTo(dom);


        //项目 > 信息框(右边) U9Vo
        if (data.templateRight) {
            var U9Vo = $('<div class="U9Vo">' + DDD(data.templateRight) + '</div>').appendTo(dom);
        }


        empp.width(dom.outerWidth()).height(dom.outerHeight()).fastClick(function () {
            if (data.url) {
                $.link(data.url);
            }
        });

        if (CND) {
            dom.find('.data-countdown').executeUnobtrusive('countdown');
        }



    }, css);
});