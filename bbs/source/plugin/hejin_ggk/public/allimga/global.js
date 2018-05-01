
;(function ($, window, undefined) {
    
    $.userstatus = function() {
        var crumbs = $.crumbs();

        if(!$.__userstatus__) {
            $.__userstatus__ = {};
        }

        return window.userstatus;
    };

    $.crumbs = function() {
        return (window.crumbs || []);
    };

    $.checkPermission = function(permission) {
        var r = false;

        var userstatus = $.userstatus();
        if(userstatus && userstatus.permissions)
        {
            $.each(userstatus.permissions, function(index, value) {
                if(permission == value) {
                    r = true;
                }
            });
        }

        return r;
    };


    $.addMenuOperation = function (operation) {
        
        if ($.iHuE && operation && $.checkPermission(operation.permission)) {
            var item = $.iHuE;
            item.menu.show();

            var li = $('<li></li>').text(operation.text).appendTo(item.submenu.find('ul'));
            li.click(function() {
                $.link(operation.url);
            });

        }
    };

    $.icon = {
        loading: 'data:image/gif;base64,R0lGODlhCgAKALMIAP7+/uDg4MzMzL29vbCwsJycnI2NjXx8fP4BAgAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFBwAIACwAAAAACgAKAAAELxChUM4pQaJh+xnTYRwAwB2UAYxGwFUBEFiqt1rxXA0Bu2KWQUmE2niCGooFI4kAACH5BAUHAAgALAEAAQAIAAgAAAQaEElkJiKhCklQsMiBDCBIitbXIZsRrFIFSBEAIfkEBQcACAAsAQABAAgACAAABB8QIUOOlAKMMtHJFSJIgWGQ6GQEmEeI1lkMwHgdxIlEACH5BAUHAAgALAIAAQAGAAgAAAQZEI2JkAlgGBkCEhUCVMCIHCHoDdYABJtERQAh+QQFBwAIACwCAAEABgAIAAAEFxAdhIwUCIiJCDEEII1k9nnUIYhblSIRACH5BAUHAAgALAIAAQAGAAgAAAQZEKFDihwSEIGMGIYkDpjYDUIoEMAlFcQhRQAh+QQFBwAIACwBAAEACAAIAAAEGhBJdIyZKOSBUQHApSHWIVVXRoJFN2ijVGIRACH5BAUHAAgALAEAAQAIAAgAAAQgEEl5jjQCBVEMGleAdJUkAMMAZEhlFJr3Ia8gCnI5IREAIfkEBQcACAAsAQACAAgABgAABBkQHQTAOMeYgrpAA8BNiPBJSGGVXaphIYBGADs=',
        blank: 'data:image/gif;base64,R0lGODlhAQABAJEAAAAAAP///////wAAACH5BAEHAAIALAAAAAABAAEAAAICVAEAOw==',
        avatar: 'data:image/gif;base64,R0lGODlhRgBGALMAALu7u93d3b29vczMzNXV1b+/v8PDw8/Pz8fHx9nZ2dPT09fX18HBwdvb29HR0cnJySH5BAAHAP8ALAAAAABGAEYAAAT/MMhJq7046827/2AojmRpnmiqrmzrvnAsz3Rt33iu7/xJ/L2R48EQGAWGQyLIITAA0GjUQGBmCAapNqqwWhLZ7ZbqpSjE6EFZkniit4zGOvFGV8vn+vawPui3D31/WgiCg1GFZX6HUHxlC4yNawORAGplCJUAaw+VBYaMl14ElQtzYYMGcmtYh11rEq11ZLATi2+OtWyZaLS6EpRoD0u/Erd7xbYCaLm/CbxiCMS6CQ/Lb8O6DkV/Bg6s1pEMdz0D15pQA+Q2BOboWwXqqzIJDgXvdQgL8y6y+LMDprFw8u+QKBZuCv5h4AKSwkOmWOR5+OfViokU6xxM0SmjnkAsITp6xNbC2pGTKFOqXIkSZLKXMGPKnEmzps2bOHPq3CkjAgA7',
        logo: '',
        triangle: 'data:image/gif;base64,R0lGODlhMgAyAJEAAP///wAAAP///wAAACH5BAEHAAIALAAAAAAyADIAAAKvlI+py43hopwmQIqzsFf7xnXfeIQi+ZknmqkrO7kv7Mgzvdg3/uh8pLP8QMEdrigcJpBJZYVpREGbwymVZ73SsloWt0v6glPiKKU8bqHNwDWb6JYGAXRkuEgH2Mn4epHfp/enMZW3h2FlOHiW6BeEOJcnOSmpwhhJmenIEYOk+WnZ9qX4WFNG6gMnhmqjurrZmuPGKiM7C1u75GZB66K7a9UDHPw0TGzMtYG8zGxSAAA7',
        success: 'data:image/gif;base64,R0lGODlhNAA0APcAAPH68PD67+/67u/57e356+757Oz46ur46On35+T24uP14OP14eD03eH03t7029/03N3z2tzz2dzz2Nvy19ry19bx09bx0tjx1dXw0dPwz9Tw0NHvzdLvztDvzNDuy87uycvtx83tyMnsxMrsxcfrwsPqvcPqvr7ouLvntbrntLblr7Xlrq7jp6zipa3ipqrho6jhoKfgn6XgnaPfmqDel5/dl5rckZvckpjbj5nbkJbajJXai5TaipLZiJPZiZHZh5HYh4/YhZDYho3Xg47XhIzXgovXgYnWforWf4bVe4PUd4TUeYLTd4HTdn7Sc3/SdH3RcXzRcHnQbXrQbnjQa3fPanTOZ3LOZnLNZW/NYm7MYWvLXmvLXWrLXGnKW2bKWGfKWWXJV2PIVWHIU2LIVF/HUF/HUV3GTlzGTVvGTFnFSlrFS1jFSVfER1bER1XERlTDRVLDQ1PDRFDCQFLCQk7BPk/BP03BPUzAPEvAOkrAOUi/N0a+NUW+NEa+NkK9MUG9MEO9MkC8Lz+8Lj+8LT67LDy7Kj27Kzu6KTq6KDm6Jze5JTa5JDi5JjW4IzS4IjO4ITK3HzG3HjC3HTO3IC+2HC22Gi62Gyy1GSy1GCu1Fyq0Fyi0FSe0FCm0FiazEyazEiWzESSyECOyD////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAKQALAAAAAA0ADQAAAj/AEkJHEiwoMGDCBMqXMiwocOHpETo8KKnkSVOoDJ+4mRpUZ4tOEQIgOjwQ5A2hyZt+hSqpcuXnzZJIrTmBweSCTMwmaPoIqiXQEOJGhoKlCdLiuIc2YCTIAIaagxV8vRTaFCXQ7OG+uRpEiE0MpqSurDEjqNMnX4OjVSFRQWDFVxUiaS1U6ZHeJJEIEnCyx9JnlgOTYPCIYo0RI1G6oNFxMMOXQZNVXvHMUkRd0QV7QrIS4eGFLIU8jm0hliBNYaC4lRJUBUGCwtMCXQprahIb08LrBCpKKdLfaIspKEnUiehd3Lr3n3HdyQ9MHKiebTpJ+7lBXkX3fSoDAaESg5l/1KrHPvACpo/ZRpE5CCGOpUEmzZvMHWoTpPgTDDooxFVUXfQh1BmRiFyQ0EAsGEJKENZJmBBIgz1ySVjFETCIZwIlQZ9EWThYREFIRaKJ39oQBAPkzAoSmHYJdCGJppkAkZBKEgYCQ0EdbHJWvRpoUlLnsxYEF2haGIFQXt8MlQV5jlhSVZBGlTFUJ3QQVAjQoniAnZCVJIVKICMYJALRBlCkCZZldcUDI9klFEhJhyEnmaTENRJVgc5oKcDDKVAiJuhPBIWQlltQpCKohxURx+M3pRQCIl8MsoooVTyg0JZdUJQloka5McmnHDShpoDTSDHnUNV0sRCWX2yKZ6ecv8ylCd2BGDQAGzsONQmVhjAqoSHwlqQGZlkxQkXBxB0wBayDsUJHQQw1KqdwhIEARqdTCrKJVQQNMUlmbbRQENZaXJmmnIaIimlluwgUA6XuPTJIB40NKcodQ6kSJZbHnTCIFg9MgMNkGh1yAoOkSkKKIcQlIeSojCJkAqPuAQKI5EACokKD00pSidwELTFj7cpBMQkWaUsiiRCQETkJlMQhEMkELOIkBOYiDKptpo8AVGNonwSyQsEgSCIJxoudIUlO49iCRckiciJIPsNBMAZl0Ds4EEKkKFVGApAFOHCl5hhkA6JbILcQg9YggkmlSBAEoGeJHJgQRbAMclxosz79yBqmnEyiRsSHHREIeMJRSp26G2FCSFDIGQBGo5QFcp1AmoHyiaNjFH1QTLUEcl/ydFXQXObUwJHCwoBYAQfk/yHuW68LcyJJH0MYatCDGAhSCUYlaZbarZfAogWDzRUwheEXIIRcls7hJlmRknyhxSfM4SBFHxQoolgohBmGGLpaUKJH1hk39ACT9yxCFpq3dYWqXHNlVgnmChCRxFhNzVDGYSIXfw0cxWVbaUrgxhDDEZyGgwgAQ49sdxVWkKUA14iEW3oAZ/M04AaiCEQkNDEcSYIik5sIhKCOIMNkvc3UmyABlagQ0o8kZZPmFASh3gDFFwAgRb68IcDDwoIADs=',
        failure: 'data:image/gif;base64,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',
        nopicture: 'data:image/gif;base64,R0lGODlhjACMANUAAPX19fT09PPz8/Ly8vHx8fDw8O/v7+7u7u3t7ezs7Ovr6+rq6unp6ejo6Ofn5+bm5uXl5eTk5OPj4+Li4uHh4eDg4N/f397e3t3d3dzc3Nvb29ra2tnZ2djY2NfX19bW1tXV1dTU1NPT09LS0tHR0dDQ0M/Pz87Ozs3NzczMzMvLy8rKysnJycjIyMfHx8bGxsXFxcTExMPDw8LCwsHBwcDAwL+/v76+vr29vby8vLu7u7q6uv///wAAAAAAAAAAACH5BAEHADwALAAAAACMAIwAAAb/QJ5wSCwaj8ikcslsOp/QqHRKrVqv2Kx2y+16v+CweEwum8/otHrNbrvf8Lh8Tq/b7/i8fs/v+/+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq0mOS8lGQ2vVzAsLzM2OTYqIBS3URMtLMfIMTU4OzImGw/DSx3I1dUvMjbaLCIWCtJFJNbj1jE0NDozJxwQ4Crk8OMyyzgtIxcMrQvG8f3WMLxy0EjhYUKqCv4SwpuXIweMEhhsjfqgsCI8gL1+BQNVwqLHeMpq6IhRot2mjyj77bLhYNOAMwoyjJMIDso7ZCgqUYCxo6fP/59AgwodKhSGsAEpqoGoNONFyqfXZgi5eQxDJX5Qs7KYWs3kpBVgGQQYS7as2bNo05JlAHYFV2SW2gYAQLeu3bt48+q12/YtCxNxwRo4QLiw4cOIEys23JcHVQ+BVyBYTLky5cZUL0SebPgBBw6cLYtmDNZvtEptQx+goM1GC9WjLWOGu9mwiha4YVSIHRuzChUkLqU2bKLabt6ifavoIBysagnITIi+8KLnCw+wESuv0FyyYQEOOGAoUKByCRtOddWoMeFyace/aVIaXpiA3cosZJC7YWGxckz0GTbAAIMtVkIu8NgQgWK+iQCgc8gdwIF+/cSwQGK+bfCgd7wxEP+DQihk55sw3WVX2QlY9SNDe4f5tsCGJi72AIIKvZBAi+/9lkmAhVFgwgokLGiYOBa9oAGObjkWwo4QDnmDDFDiMEJhDNBokWqYZcAkh4RxMIM1M6igwAEjoPRCf4VhJsGWoTFAYTkkJJDeRyeQlqQKmgRIQorVzFACnxVZmOZ7gLGJwKFzkvPlUxkMmiRzhiKwAaBaIVOCo0JYkKdzh55QaT+HItCYfJsdWoBaqKZKFgGYcpLaoQLsJeus9xHW2CZtoRnhrhbQhitYNhAl7LBE2eCrJsV9mtIJnTSAgrIfoUBqTdRWa+212Gar7bbcduvtt+CGK+645JZr7rnopqsk7rrstuvuu/DGK++89NZr77345qvvvvz26++/AAcs8MAESxEEADs='
    };

    $.remUnit = function() {
        var r = parseInt(document.documentElement.style.fontSize);
        return (isNaN(r) ? 16 : r);
    };

    $.loadScript = function (src, callback) {
        var data = $.k0M2 = $.k0M2 || {};

        if (data[src] === undefined) {
            data[src] = new Array();
            if(callback) {
                data[src].push(callback);
            }

            $.ajax({
              url: src,
              dataType: 'script',
              success: function () {
                
                $.each(data[src], function (index, value) {
                    value();
                });
                data[src] = true;

              },
              cache: true
            });

            return false;
        }
        else if (data[src] === true) {
            if(callback) {
                callback();
            }
            
            return false;
        }
        else {
            if(callback) {
                data[src].push(callback);
            }
            return false;
        }
    };

    $.fn.fastClick = function(fn){
        var $this = $(this);

        if($.support.touch) {
            var clickStart = { x: 0, y: 0, scroll: 0 }, trackingClick = false;

            $this.bind('touchstart', function() {
                trackingClick = true;
			    clickStart.x = event.targetTouches[0].clientX;
			    clickStart.y = event.targetTouches[0].clientY;
			    clickStart.scroll = window.pageYOffset;

                return true;
            });

            $this.bind('touchmove', function() {
			    if (trackingClick) {
				    if (Math.abs(event.targetTouches[0].clientX - clickStart.x) > 10 || Math.abs(event.targetTouches[0].clientY - clickStart.y) > 10) {
					    trackingClick = false;
				    }
			    }
	            
			    return true;
            });

            $this.bind('touchend', function() {
            	if (!trackingClick || Math.abs(window.pageYOffset - clickStart.scroll) > 5) {
				    return true;
			    }

                fn.call($this.get(0));
            });
        }
        else {
            $this.click(fn);
        }

        return $this;
    };

    $.fn.selected = function(value) {
        var $this = $(this);
        var option = $this.find('option[value=\'' + value + '\']').get(0);
        option.selected = true;
    };

    $.buildAccountDisplay = function(name, displayName) {
        var r = '';

        if(displayName && displayName.length > 0) {
            r += '<strong style="color:#0000FF;">' + displayName + '</strong>';
        }
        else
        {
            r += '<em style="color:#666666;">' + name + '</em>';
        }

        return r;
    };

    $.buildPostOperations = function (userstatus, postID, redirect) {
        var r = null;

        if (userstatus && $.checkPermission('systemPost')) {
            r = $('<div class="XxaK" style="position:relative;z-index:1;"></div>');
            var puDH = $('<div class="puDH" style="background-color:#F3F2F0;padding:0.15rem;height:0.55rem;width:0.55rem;border-radius:0.1rem;border:solid 0.05rem #EEE;"><img style="background-color:#F3F2F0;width:0.55rem;height:0.55rem;" src="data:image/gif;base64,R0lGODlhIwAhAOYAAFVVVZ2dnevr61ZWVmpqatXV1bq6uoeHh1hYWPn5+dPT0+Pj46mpqZWVlV5eXnd3d8fHx+Hh4fX19WZmZoqKinR0dH5+fv///6+vr+np6e/v71paWnBwcP///9nZ2cLCwpOTk5mZmaGhoczMzHx8fPHx8YKCgrW1tff391xcXGBgYOXl5W5ubo6OjmZmZp+fn6urq+3t7fPz89vb23JycqOjo4mJifz8/NfX15mZmbu7u4GBgc/Pz+fn53l5ecnJybGxsYWFhf///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAEIALAAAAAAjACEAAAf/gEKCg4RCBQcOCIqLKTs8hZCRhAo+AwCXmJg0I5KECRKgoRIaARuZpwAIIDGiooIxFCoDs7SzqKi1tQ5BK0ImlrfBwqgWNw7DyMgbNyGyyc+ZDiBCEgwkFdjZ2tvc3SQBGp3i4503Ah4LCYMJCx7hgxoeERLrCzMxN5EaDQ4WCoMjLKgQIUNQAhETKvwYVGDHhBAZIkU45gDGoFIACAgQJEEFgA0hBhnw6KCAxGMpGFxEAGDCRmoeETQYdAKlSUgRTAEQsfJjRGrHALTIJwQDSwT/cNIKMKgBrZ8SaNkgCoNW0kIRljZ9ylEqVasStQpyOguqV0FVZ10llHUW07FctKmdFZJ2wNpBbQe8FUJ2gNlZU9GCVep2a9mugL+qDVsY7mG5iQUvJqzXsF/EAwLTHYxVLN+4USNvnty58efHoTMrtsu4suPLkFVLZk15b9+/skfTLj2gQcEEIGjhELSA1gEU1ERwZkuLg44SHzhgarFCQPBZBAyUgPAA011BeQeoIKCC5aUNBAjovDR+gnkA34VkhYYs/goW9IURmBEJhY4J+aGiAhAFSXLDgQgmqOCCCEISCAA7" /></div>').appendTo(r);
            puDH.click(function () {
                if (confirm('是否确认删除帖子？')) {
                    if(!redirect) {
                        redirect = location.href;
                    }

                    $.link($.setUrlQuery('/user/SystemPostDelete?id=' + postID, 'redirect', redirect));

                }
            });
        }

        return r;
    };

    $.getRCCodeUrl = function(value) {
        var r;

        value = value.replace('~', 'http://203.195.131.28');
        r = '/service/RCCode?value=' + encodeURIComponent(value);
        
        return r;
    };

    $.getImageInfo = function(url) {
        var r = null;

        url.replace(/(\d{4})(\d{2})(\d{2})\/(\d{2})(\d{2})(\d{2})_(\d+)_(\d+)x(\d+)_(\d+)[\,\.](jpg|png|gif)/ig, function($$, year, month, day, hours, minutes, seconds, accountid, width, height, id, extension){
            r = {};
            r.width = parseInt(width);
            r.height = parseInt(height);

            r.time = new Date();
            r.time.setFullYear(parseInt(year));
            r.time.setMonth(parseInt(month) - 1);
            r.time.setDate(parseInt(day));
            r.time.setHours(parseInt(hours));
            r.time.setMinutes(parseInt(minutes));
            r.time.setSeconds(parseInt(seconds));

            r.accountid = parseInt(accountid);
            r.id = parseInt(id);
            r.extension = extension;
        });

        return r;
    };
    
    $.link = function (url) {
        window.location.href = url;
    };

    $.logon = function(fn, message) {
        var userstatus = $.userstatus();

        if(!userstatus) {
            var url = $.setUrlQuery('/user/logon', 'redirect', location.href);
            if(message) {
                url = $.setUrlQuery(url, 'message', message)
            }
            $.link(url);
        }
        else {
            fn();
        }
    };

    $.addStyleSheet = function (css) {
        $('head').append($('<style type="text/css"></style>').text(css));
    };

    $.randomNumber = function (min, max) {
        return parseInt(Math.random() * (max - min + 1) + min);
    };

    $.randomString = function (digit) {
        var r = '';
        var letters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for(i = 0; i < digit; i++) {
            r += letters.substr($.randomNumber(0, 61), 1);
        }
        return r;
    };

    $.newID = function () {
        return 'o' + $.randomNumber(0, 9999999999);
    };

    $.unparam = function (value) {
        var r = {};
        var ss = value.split('&');
        for (var i = 0; i < ss.length; i++) {
            if (ss[i].search(/(^\w+)\=(.*)/) >= 0) {
                var mtch = ss[i].match(/(^\w+)\=(.*)/);
                r[mtch[1]] = decodeURIComponent(mtch[2]);
            }
        }
        return r;
    };

    $.querys = function () {
        return $.unparam(window.location.search.replace(/^\?/g, ''));
    };

    $.setUrlQuery = function(url, name, value) {
        var me = function(url, name, value) {
            var querys = {};
            var path = '';
            url.replace(/^([^\?]+)\??(.*)$/ig, function($$, $1, $2) {
                path = $1;
                if($2 && $2.length > 0) {
                    $.each($2.split('&'), function(index, value) {
                        value.replace(/^([^=]+)\=(.*)$/ig, function($$, $1, $2) {
                            if($1 && $1.length > 0) {
                                querys[$1] = $2;
                            }
                        });
                    });
                }
            });

            querys[name] = value;
            var newQuerys = [];
            $.each(querys, function(key, value) {
                if(value){
                    newQuerys.push(key + '=' + encodeURIComponent(value));
                }
            });

            return path + (newQuerys.length > 0 ? '?' : '') + newQuerys.join('&');
        };
        
        var r = '';
        if($.isPlainObject(name)) {
            r = url;
            $.each(name, function(key, value){
                r = me(r, key, value);
            });
        }
        else {
            r = me(url, name, value);
        }

        return r;
    };

    $.locators = (function () {
        var r = {};

        $.each(window.location.pathname.replace(/^.*[\\\/]+/ig, '').split('_'), function(index, value) {
            if(index > 0) {
                value.replace(/^([a-zA-Z][0-9a-zA-Z]*)\,(.*)$/, function($$, $1, $2) {
                    r[$1] = $2;
                });
            }
        });

        return r;
    });

    $.setUrlLocator = function(url, name, value) {
        var me = function(url, name, value) {
            var path;
            var locators = {};
            var querys;

            url.replace(/^([^\?_]+)(_[^\/\?]*)?\??(.*)$/ig, function($$, $1, $2, $3) {
                path = $1;
                if($2) {
                    $.each($2.split('_'), function(index, value) {
                        if(index > 0) {
                            value.replace(/^([a-zA-Z][0-9a-zA-Z]*)\,(.*)$/, function(a, b, c) {
                                
                                locators[b] = c;
                            });
                        }
                    });
                }

                querys = $3;
            });

            locators[name] = value;
            
            var newLocators = [];
            $.each(locators, function(key, value) {
                if(value) {
                    newLocators.push(key + ',' + value);
                }
            });
            
            return path + (newLocators.length > 0 ? '_' : '') + newLocators.join('_') + (querys && querys.length > 0 ? '?' : '') + querys;
        };
        
        var r = '';
        if($.isPlainObject(name)) {
            r = url;
            $.each(name, function(key, value){
                r = me(r, key, value);
            });
        }
        else {
            r = me(url, name, value);
        }

        return r;
    };

    $.cover = function (state, close, timer) {
        if(!$.__cover__) {
            $.__cover__ = {};
            $.__cover__.dom = $('<div id="rI0W6i" style="position:fixed;top:0;left:0;background-color:rgba(0,0,0,0.78);width:100%;height:100%;z-index:10000;"></div>').appendTo($('body'));
            
            $.__cover__.dom.bind($.support.touch ? 'touchstart' : 'click', function(){
                if(!$.__cover__.timer) {
                    $.__cover__.close();
                    $(this).hide();
                }
            });
        }

        $.__cover__.timer = timer;

        $.__cover__.close = close;
        
        if(state) {
            $.__cover__.dom.show();

            if($.__cover__.timer > 0) {
                setTimeout(function()
                {
                   $.__cover__.dom.hide();
                   $.__cover__.close();
                }, $.__cover__.timer * 1000);
            }
        }
        else {
            $.__cover__.dom.hide();
        }

    };

    $.message = function (text, time) {
        if(!$.__message__) {
            $.__message__ = {messageHandle:0};
            $.__message__.dom = $('<div id="DiYg" style="' + $.compatibleCss('position:fixed;margin:0 auto;border-radius:0.18rem;bottom:30%;left:25%;width:46%;z-index:99;padding:0.3rem 2%;background-color:rgba(0,0,0,0.7);height:1rem;font-size:0.4rem;color:white;display:box;box-align:center;text-align:center;box-pack:center;') + '"></div>').appendTo($('body'));
        }

        if($.__message__.messageHandle != 0) {
            clearTimeout($.__message__.messageHandle);
        }

        time = time || 3000;
        $.__message__.dom.text(text).fadeIn();
        setTimeout(function() {
            $.__message__.dom.fadeOut();
        }, time);
    };

    $.fn.executeUnobtrusive = function(name) {
        var data = $.__unobtrusive__[name];
        
        var key = 'data-' + name;
        var $this = $(this);

        var getparams = function (dom, name, types) {
            var r = {};

            $.each(types, function(key, value) {
                var v = dom.attr('data-' + name + '-' + key);
                
                if(v) {
                    if(value == 'Integer') {
                        v = parseInt(v);
                    }
                    else if(value == 'Float') {
                        v = parseFloat(v);
                    }
                    else if(value == 'Json') {
                        v = $.parseJSON(v);
                    }
                    else if(value == 'String') {
                        v = v;
                    }
                }

                r[key] = v;
            });

            return r;
        };

        if($this.attr(key) == 'true') {
            $this.attr(key, 'false');
            $this.addClass('data-' + name);

            if(!data.already)
            {
                $.addStyleSheet($.compatibleCss(data.css));
            }

            data.fn.call({dom: $this, params: getparams($this, name, data.types)});

            data.already = true;
        }
    };


    $.unobtrusive = function(name, types, fn, css) {
        var data = $.__unobtrusive__ = $.__unobtrusive__ || {};

        data[name] = {types: types, fn: fn, css: css};

        $('*[data-' + name + '=true]').each(function() {
            $(this).executeUnobtrusive(name);
        });
    };


    $.htmlEncode = function(s) {
        
        if (typeof(s) == 'string') {
            s = s.replace('<', '&lt;');
            s = s.replace('>', '&gt;');
        }

        return s;

    };


    $.narrowImage = function(image, width, height, mode) {
        var r;

        mode = mode || '$$';
        
        if(mode == 'full') {
            mode = '$$';
        }
        if(mode == 'adaptive') {
            mode = '!!';
        }

        if(mode.search(/[^\!\$]/ig) > -1) {
            mode = '$$';
        }
        
        mode = mode.replace(/\$/ig, '$$$$');

        if(image && image.search(/\,([a-z0-9]+)[\!\$]{2}\d+x\d+$/ig) > -1) {
            r = image.replace(/\,([a-z0-9]+)[\!\$]{2}\d+x\d+$/ig, '.$1');
        }
        else if(image && image.search(/\.[a-z0-9]+$/ig) > -1) {
            r = image.replace(/\.([a-z0-9]+)$/ig, '.$1');
        }
        else{
            r = image;
        }

        return r;
    }

    $.getAccountAvatar = function(id, width, height) {
        return '/service/accountavatar?id=' + id + '&width=' + width + '&height=' + height;
    };

    $.compatibleCss = function(css){
        css = css.replace(/display\:\s*box\;/ig, 'display:-moz-box;display:-webkit-box;display:box;');
        css = css.replace(/(background\-image\:)\s*(linear\-gradient\([^;]+\);)/ig, function($0, $1, $2) {
            return $0 + $1 + '-webkit-' + $2 + $1 + '-moz-' + $2;
        });

        css = css.replace(/(box\-flex|box\-align|box\-pack|box\-orient|transform|filter|opacity)(\:\s*[^;]+;)/ig, function($0, $1, $2) {
            return $0 + '-webkit-' + $1 + $2 + '-moz-' + $1 + $2;
        });

        return css;
    };



    $.filterContent = function(s) {
        var r = s;

        if(r) {
            r = r.replace(/<img([^<>]+?)class="IyLb"([^<>]+?)>/ig, '');
            r = r.replace(/<img([^<>]+?)class="nE0M"([^<>]+?)>/ig, '');
            r = r.replace(/<br>/ig, '');
            r = r.replace(/<div[^<>]*>\s*<\/div>/ig, '');
            r = r.replace(/<\!\-\-\;[\d\-]+\;\-\-\>/ig, '');
        }
        else {
            r = '';
        }

        return r;
    };


    $.cookies = (function () {
        
        var c = function () { };
        c.prototype.get = function (name) {
            var reg = new RegExp('(^|;)\\s*' + escape(name) + '\\s*\=(.+?)(;|$)');

            var r = document.cookie.match(reg);
            if (r) {
                return  unescape(r[2]);
            }
            else {
                return null;
            }
        };
        c.prototype.set = function (name, value) {
            var arr = new Array();

            var expires = new Date();
            expires.setTime(expires.getTime() + 31557600000);

            arr.push(escape(name) + '=' + escape(value));
            arr.push('expires=' + expires.toGMTString());
            arr.push('path=/');

            document.cookie = arr.join(';');
        };

        return new c();
    })();

    $(document).ready(function() {
        $.cookies.set('USERAGENT', navigator.userAgent.toString().replace(/[^a-zA-Z0-9]/g, ''));
        
        var userstatus = $.userstatus();

        window.onload = function() {
            document.documentElement.style.fontSize = parseInt(window.innerWidth/10)+'px';
            var i = 0;
            var handle = setInterval(function() {
                document.documentElement.style.fontSize = parseInt(window.innerWidth/10)+'px';
                i++;
                if(i >= 6) {
                    clearInterval(handle);
                }
            }, 1000);
        };
        
        $('*[data-import]').each(function() {
        });

        $(document).find('*[oninit]').each(function () {
            eval($(this).attr('oninit'));
            $(this).removeAttr('oninit');
        });

        var errorcode = {
            '1001': '登录密码错误',
            '1002': '没有足够的榄币支付',
            '1003': '目标账户不存在',
            '1004': '提现密码错误',
            '2101': '抢优惠券数量超过限额',
            '3102': '编辑框没有输入内容',
            '2101': '抢优惠券超过限额'
        };

        var message = $.querys()['message'];
        if (message) {
            message = message.replace(/^error\-(\d+)/ig, function($$, $1) {
                if(errorcode[$1]) {
                    return errorcode[$1];
                }
                else {
                    return $$;
                }
            });

            setTimeout(function () {
                $.message(message);
            }, 1000);
        }

        $.addStyleSheet('.loading{background-image:url(' + $.icon.loading + ');background-position:center center;background-color:#F8F8F8;}');
    });


})(jQuery, this);



$(document).ready(function () {
    var css = '.data-userstatus{cursor:pointer;height:1.4rem;display:box;box-pack:end;}';


    //项目 > 消息图标 d8kw
    css += '.d8kw{width:1.4rem;height:1.4rem;background-color:#B62525;}';
    css += '.ahmU{font-size:0.5rem;width:1.4rem;height:1.4rem;}';
    css += '.ahmU IMG{margin:0.38rem 0 0 0.24rem;width:0.93rem;display:inline-block;}';
    css += '.rAK8{position:absolute;font-size:0.28rem;margin:0.7rem 0 0 0.75rem;color:#B62525;background-color:white;border-radius:0.26rem;width:0.47rem;height:0.47rem;line-height:0.47rem;text-align:center;border:0.05rem solid #B62525;font-family:SimSun;}';


    //项目 > 用户头像 T6KF
    css += '.T6KF{width:1.4rem;height:1.4rem;background-color:#C82929;}';
    css += '.VEjQ{background-color:#FFFFFF;margin:0.05rem 0 0 0.05rem;display:inline-block;width:1.2rem;height:1.2rem;border:0.06rem solid White;outline:none;}';
    css += '.VEjQ IMG{width:100%;height:100%;display:block;outline:none;}';


    $.unobtrusive('userstatus', {}, function () {
        var dom = this.dom;
        var params = this.params;
        var userstatus = $.userstatus();


        var T6KF = $('<div class="T6KF"><div class="VEjQ" style="border-radius:0.65rem;"><img style="border-radius:0.65rem;" src="' + $.icon.avatar + '" /></div></div>').appendTo(dom);
        if (userstatus) {
            if (userstatus.accountid > 0) {
                T6KF.find('img').attr('src', userstatus.avatarpic);
            }
        }
        T6KF.click(function () {
            $.link('plugin.php?id=hejin_share&model=user');
        });


    }, css);

    /*===================================================== 返回开始 =====================================================*/

    css = '.data-back {outline:none;display:box;}';

    //内包裹 Chq1
    //内包裹 > 返回图标(向左箭头) Wng6
    //内包裹 > 文本 I3qV
    css += '.Chq1{height:1.4rem;padding:0 0.29rem 0 0.33rem;}';
    css += '.gQnO{background-color:#B62525;}';
    css += '.Wng6{height:1.4rem;}';
    css += '.Wng6 DIV{-webkit-transform:rotate(45deg);width:0.24rem;height:0.24rem;border:solid #FFF;border-width:0 0 0.06rem 0.06rem;}';
    css += '.I3qV{font-size:0.48rem;color:White;margin-left:0.08rem;height:1.4rem;line-height:1.4rem;}';

    $.unobtrusive('back', {}, function () {
        
        var getText = function () {
            var crumbs = $.crumbs();

            var r = '';
            if (crumbs.length == 1 && crumbs[0].type == 'url') {
                r = crumbs[0].text;
            }
            else if (crumbs.length == 1 || crumbs.length == 2) {
                r = crumbs[crumbs.length - 1].text;
            }
            else if (crumbs.length >= 3) {
                r = crumbs[2].text;
            }

            return r;
        }

        var dom = this.dom;
        var params = this.params;

        var inner = $('<div class="Chq1 display_box"><div class="Wng6 display_box boxAlign_center"><div></div></div><div class="I3qV display_box">' + getText() + '</div></div>').appendTo(dom);
        inner.fastClick(function () {
            inner.addClass('gQnO');
            window.location.href="plugin.php?id=hejin_share"; 
            setTimeout(function () {
                inner.removeClass('gQnO');
            }, 500);
        });


    }, css);
    /*===================================================== 返回结束 =====================================================*/



    //UYzi = senddata.imageid 图片ID
    css = '.data-inputimage{font-size:0;}';

    //a6Hm 图片项目
    css += '.data-inputimage .a6Hm{border:0.07rem solid #AAAAAA;display:block;margin:0.1rem 0.5rem 0.1rem 0;float:left;}';
    css += '.data-inputimage .a6Hm DEL{position: absolute; cursor: pointer;width:0.6rem;height:0.6rem;line-height:0.6rem;font-size:0.5rem;border-radius:0.37rem;text-align:center;color:#FFFFFF;border:solid #FFFFFF 0.07rem;background-color: #FF0000;margin-top:-0.4rem; }';
    css += '.data-inputimage .a6Hm IMG{background-color:#DDDDDD;background-image:url(' + $.icon.loading + ');background-position:center center;float:left;display:block; }';

    //a6Hm 添加图片
    css += '.data-inputimage .m0cB {border:0.07rem solid #AAAAAA;background: #AAAAAA no-repeat center center url(data:image/gif;base64,R0lGODlhIQAhAIAAAP///////yH5BAEHAAEALAAAAAAhACEAAAJNjI8Iu+kPDWuxwgmsTnj70H1aKFZkeU2oqa4p475UzLW0Yt9SzmL+DwwKh8Si8YhMKmeWk855g9KkMarLusKitCWuyPsBe8Qb8ogXKQAAOw==);display:block;float:left;margin:0.1rem 0.5rem 0.1rem 0;fonct-size:0;}';
    css += '.data-inputimage .m0cB INPUT{z-index:200;opacity:0;filter:alpha(opacity=0);-ms-filter:\'alpha(opacity=0)\';display: block;}';

    $.unobtrusive('inputimage', { name: 'String', identifier: 'String', verify: 'String', value: 'Json', setting: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;

        var identifier = $('<input name="' + params.name + '" type="hidden" value="' + params.identifier + ':' + params.verify + '" />').appendTo(dom);
        var images = $('<div></div>').appendTo(dom);

        var fileSelected = function () {
            var file = this.files[0];
            var $this = $(this);


            if (file) {
                if (!window.FormData) {
                    alert('系统版本太低，请使用安卓4.0或以上版本上传！');
                }
                else if ((/\.(gif|jpg|png)$/i).test(file.name)) {
                    var $image = buildImage({ path: $.icon.blank });
                    uploadImage(file, $image.attr('id'));
                }
                else {
                    alert('错误：' + file.name);
                    //alert('文件格式错误，请上传 jpg, png, gif 格式的文件');
                }
            }
        };

        var uploadImage = function (file, track) {
            var fd = new FormData();
            fd.append('upload', file);
            fd.append('track', track);
            fd.append('identifier', params.identifier);
            fd.append('verify', params.verify);
            fd.append('multi', (params.setting.multi ? '1' : '0'));
            var xhr = new XMLHttpRequest();
            xhr.addEventListener('load', function (e) {
                var result = $.parseJSON(e.target.responseText);
                var $image = $('#' + result.track + ' img').attr('src', $.narrowImage(result.path, parseInt(params.setting.width * 60), parseInt(params.setting.height * 60), '$$'));
            }, false);
            xhr.open('POST', '/service/UploadImage');
            xhr.send(fd);
        };

        var deleteImage = function () {
            var $image = $(this).parent();
            var senddata = {};
            senddata.track = $image.attr('id');
            senddata.identifier = params.identifier;
            senddata.verify = params.verify;
            senddata.imageid = $image.data('UYzi');

            $.post('/service/DeleteImage', senddata, function (result) {
                $image.remove();

                if (!params.setting.multi && dom.find('.a6Hm').size() == 0) {
                    addImageButton.show();
                }

            }, 'json');
        };

        buildImage = function (value) {
            if (!params.setting.multi) {
                var count = dom.find('.a6Hm').size();
                if (count == 0) {
                    addImageButton.hide();
                }
                else if (count > 0) {
                    addImageButton.hide();
                    return false;
                }
            }

            var src = value.path;
            if (src.search(/^data\:/ig) != 0) {
                src = $.narrowImage(src, parseInt(params.setting.width * 60), parseInt(params.setting.height * 60), '$$');
            }

            var tmpl = '<span class="a6Hm" style="width: ' + params.setting.width + 'rem; height: ' + params.setting.height + 'rem"><del>×</del><img style="width: ' + params.setting.width + 'rem; height: ' + params.setting.height + 'rem" src="' + src + '" /></span>';
            var r = $(tmpl).insertBefore(images.find('.m0cB')).attr('id', $.newID());
            r.data('UYzi', value.id);
            r.find('del').click(deleteImage).get(0).style.marginLeft = (params.setting.width - 0.3) + 'rem';
            return r;

        };

        var addImageButton = (function () {
            var tmpl = '<span class="m0cB" style="display: none; width: ' + params.setting.width + 'rem; height: ' + params.setting.height + 'rem;"><input style="width:' + params.setting.width + 'rem;height:' + params.setting.height + 'rem;" type="file"/></span>';
            var r = $(tmpl).appendTo(images);
            r.find('input[type=file]').change(fileSelected);
            return r;
        })();

        if (params.value.items) {
            var i = -1;

            $.each(params.value.items, function (key, value) {
                i++;
                if (params.setting.multi) {
                    buildImage(value);
                }
                else if (i == 0) {
                    buildImage(value);
                }
            });

            $('<div style="clear:both;"></div>').appendTo(images);
        }

        if (params.setting.multi) {
            addImageButton.show();
        }
        else if (dom.find('.a6Hm').size() == 0) {
            addImageButton.show();
        }

    }, css);





    css = '.data-slider{}';
    css += '.swiper-container{margin:0 auto;position:relative;overflow:hidden;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;z-index:1;}.swiper-wrapper{position:relative;width:100%;-webkit-transition-property:-webkit-transform,left,top;-webkit-transition-duration:0s;-webkit-transform:translate3d(0px,0,0);-webkit-transition-timing-function:ease;-moz-transition-property:-moz-transform, left, top;-moz-transition-duration:0s;-moz-transform:translate3d(0px,0,0);-moz-transition-timing-function:ease;-o-transition-property:-o-transform,left,top;-o-transition-duration:0s;-o-transform:translate3d(0px,0,0);-o-transition-timing-function:ease;-o-transform:translate(0px,0px);-ms-transition-property:-ms-transform, left, top;-ms-transition-duration:0s;-ms-transform:translate3d(0px,0,0);-ms-transition-timing-function:ease;transition-property:transform,left,top;transition-duration:0s;transform:translate3d(0px,0,0);transition-timing-function:ease;}.swiper-free-mode>.swiper-wrapper{-webkit-transition-timing-function: ease-out;-moz-transition-timing-function: ease-out;-ms-transition-timing-function: ease-out;-o-transition-timing-function:ease-out;transition-timing-function: ease-out;margin:0 auto;}.swiper-slide{float:left;}.swiper-wp8-horizontal{-ms-touch-action:pan-y;}.swiper-wp8-vertical{-ms-touch-action:pan-x;}';
    css += '.swiper-pagination-switch{width: 0.25rem;height:0.25rem;background-color:#DDDDDD;display:inline-block;border-radius:0.15rem;margin-right:0.1rem;margin-left:0.1rem;}';
    css += '.swiper-active-switch{background-color:#00FFFF; }';
    css += '.data-slider IMG{background:url(' + $.icon.loading + ') center center no-repeat;}';

    css += '.OBc7{width:1rem;height:1rem;position:absolute;z-index:3;opacity:0.5;}.OBc7 IMG{width:1rem;width:1rem;background-image:none}';

    $.unobtrusive('slider', {}, function () {
        var dom = this.dom;
        var params = this.params;

        var width = dom.width();
        var height = 0;
        var wrapper = $('<div class="swiper-container"><div style="width:' + width + 'px;" class="swiper-wrapper"></div></div>').find('.swiper-wrapper');

        dom.find('>*').each(function () {
            var $this = $(this);
            var h = $this.outerHeight(true);
            $this.wrap('<div class="swiper-slide" style="width:' + width + 'px;font-size:0.5rem;"></div>').parent().appendTo(wrapper);
            height = (h > height ? h : height);
        });

        wrapper.parent().appendTo(dom);
        var pagination = $('<div class="' + $.newID() + '" style="width:' + width + 'px;height:0.3rem;position:absolute;z-index:2;font-size:0;text-align:center;margin-top:-0.42rem;"></div>').appendTo(dom);


        var mySwiper = new Swiper('.swiper-container', {
            pagination: '.' + pagination.attr('class'),
            loop: true
        });

        if (height > 0) {
            wrapper.height(height);
            wrapper.parent().height(height);
            dom.height(height);
            dom.find('.swiper-slide').height(height);
        }

    }, css);


    css = '.data-poster{display:none;width:100%;height:4.5rem;}';
    css += '.N9as{width:100%;height:4.5rem;}';
    css += '.N9as IMG{width:100%;height:4.5rem;}';
    $.unobtrusive('poster', {}, function () {
        var dom = this.dom;
        var params = this.params;
        var crumbs = $.crumbs();
        if (crumbs.length >= 3) {
            if (crumbs[2].description.length > 0) {
                dom.show();

                dom.attr('data-slider', 'true');

                var images = crumbs[2].description.split('|');
                $.each(images, function (index, value) {
                    $('<div class="N9as"><img src="' + value + '" /></div>').appendTo(dom);
                });

                dom.executeUnobtrusive('slider');
            }
        }
    }, css);


    css = '.data-vote{cursor:pointer;height:0.83rem;background-color:#FFFFFF;border-radius:0.13rem;border:0.03rem solid #DDDDDD;display:box;box-align:stretch;background-image:linear-gradient(top,#F5F7FA,#F0F2F5);}';

    //项目 > 图标框 C4VI
    css += '.C4VI{display:box;box-align:center;padding:0 0.01rem 0 0.2rem;}';
    css += '.C4VI IMG{display:block;height:0.55rem;}';

    //项目 > 文本框 p75Q
    css += '.p75Q{font-size:0.3rem;line-height:0.83rem;min-width:0.8rem;padding-right:0.08rem;text-align:center;color:#999;}';

    $.unobtrusive('vote', { id: 'Integer', table: 'Integer', value: 'Integer', count: 'Integer', text: 'String', mode: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;


        var iconA = 'R0lGODlhIAAiAOYAAP////Pz9vTy9fPy9fPx9PPv8vLw8/Pu8vHu8vTt8fTr7vTn6/Tl6fXj5/Xk6PTj5/Lj5/Xh5vXf5PTg5fPf5PXd4vbd4fbc4PPb3/Xb4PTa3vXZ3fPY3fXW2vfT2PPV2vXT2PXT1/bT2PbS1/bQ1vfO0/bO1PXO0/TN0vbKz/XJzvXGy/fCx/fBx/a/xva/xfa+xPW9xPe5v/mutfersvmkrPikq/qfp/qcpPqco/mco/qcpfmbpPqZofuWnvqXn/qUnPuTm/qTm/qSm/uRmvmSmvqRmfqQmfqPmPuMlfuLk/uJk/uJkvuJkfuHkPqGj/uGj/uCi/x9h/x9hvx8hfx4gvx0fv1yff1xfP1wev1wef1td/1sd/5pc/1pdf1ocv5ncv1ncv5mcf5lcP5ibf5jbv5hbf5fa/5fav5eav5eaf5daf9caP5caf9aZv9aZf9ZZf9ZZP5aZ/9YY/9XYv9XY/9WYf9VYf9WYv9UYP///wAAAAAAAAAAAAAAAAAAACH5BAEHAHoALAAAAAAgACIAAAf/gHqCg4SFHllnNwKFjI2ODWZ5kjiOlZVEd0wleHgilp+DDm95EgA+d0GgoDN3SgAAFnhdqp9SdyyvAGx5CbSOF3V1ArlgeRG+jUJ3RrkAkQvIhSJweRm5BJwB0YMHWnlDzSN4V9rbBEt3UwPNPXc823oKUHdjFc0AV3kdvi1jdP90xGy4B+DHnTsAAW7ZwUBQlSqCukjKsybKBIKvfmCZyDEPGRN65swRdCYPxpP3CpBgcsfMg38kTaKcmSvJnRww9ZSkyRMEHi85d/JslgJNwqMxhzY7ISaNU6d5uASVqRSjCzxOplY9WeOODa1bCTa58wKs0qJH/2kwO5TpU6huaAywDQsgBB4reuaGlWEHiR5OgtBQpQtAxx0af/EEHkxTBRpOkNfkWZF4cVUUTd/GyUOhsh7BhF9BwOMGgWfQoYHceSII8GfGSjkcOfjBIUQ9X/JA3s27N2Q5MRrBKNOxuPE8YYpgIBQIADs=';
        var iconB = 'R0lGODlhIAAiAOYAAPPz9vTy9fPx9PLw8/Pu8vTt8fHu8vTr7vTn6/Tl6fXk6PXj5/Tj5/Xh5vPf5Pbc4PPb3/Ta3vXV2ffT2PPU2fbT2PbO1PXGy/fBx/a/xfa+xPW9xPmutfersvikq/qhqfqdpfqcpfmcpfqbovqTm/uTm/mSmvuRmvqPmPuIkfuHkPqHkPuFj/x+h/x8hfx6hPx4gvx0fv1yff1yfP1wef1td/1sd/1qdv5pc/1ocv1ncv5lcP5jb/5ibf5ibv5jbv5hbf5fa/5fav5aZ/9aZv9aZf9ZZP9ZZf9YZP9YY/9XYv9XY/9VYf9WYv9UYP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAE8ALAAAAAAgACIAAAfrgE+Cg4SFEzM+HwGFjI2OCzxOkiCOlZUnTJKSFZadgwpFmpIlnp4cmaI4pZ0tqKIFq44PSKKaDbGNJK6iCLiFFUe1mgC+gwQ0wpIyxMUCLLuiIsVPBynQohKxGDtK3UrJot7eNSEJgi0tgjjg7Mk9Fk9JSYJB7faiQAzd9Pf9TiP7ntTzZ+9GwIEE2R0Jxi8hOxsHHbJTEVFiMg8VLdbKkFGjpggdPRIZEFJjjCclLaJ40qSJICEeRXVg6fIJzJiSLtB8iVOSg502exIxAPRmzBWCWvKMaYSCoBcvBOWIOWRDIw0/LOowAYFQIAA7';

        var C4VI = $('<div class="C4VI"><img src="' + $.icon.blank + '" /></div>').appendTo(dom);

        var p75Q = $('<div class="p75Q">' + params.count + '</div>').appendTo(dom);


        var icon = C4VI.find('img');
        icon.attr('src', 'data:image/gif;base64,' + (params.value == 0 ? iconA : iconB));


        dom.click(function () {
            $.logon(function () {
                $.post('/user/ParticipateVote', { ownertable: params.table, ownerid: params.id, value: 1 }, function (result) {
                    if (result.value == 1) {
                        icon.attr('src', 'data:image/gif;base64,' + iconB);
                        params.value = result.value;

                        p75Q.text(parseInt(p75Q.text()) + 1);
                    }
                }, 'json');
            });
        });


    }, css);



    css = '.data-accountcard{}';

    //项目 > 外包表 GHlK
    //项目 > 外包表 头像 zp0H
    css += '.GHlK{}';
    css += '.zp0H{border:0.04rem solid #CCCCCC;}';
    css += '.zp0H IMG{background-position:center center}';

    //项目 > 外包表 > 信息框 tmGp
    css += '.tmGp{padding-left:0.1rem;}';
    css += '.tmGp H1{font-size:0.35rem;white-space:nowrap;}';
    css += '.tmGp H2{font-size:0.3rem;white-space:nowrap;color:#B0B1B3;}';

    $.unobtrusive('accountcard', { id: 'Integer', name: 'String', displayname: 'String', show: 'String' }, function () {
        
        var dom = this.dom;
        var params = this.params;

        var avatarWidth = 0.8;
        var avatarHeight = 0.8;

        var avatarSrc = $.getAccountAvatar(params.id, 70, 70);
        var avatarSize = 'width:' + avatarWidth + 'rem;height:' + avatarHeight + 'rem;border-radius:' + (avatarWidth / 1.8) + 'rem;';

        var inner = $('<table class="GHlK"><tr><td><div class="zp0H" style="' + avatarSize + '"><img src="' + $.icon.avatar + '" data-JhNJ="' + avatarSrc + '" style="' + avatarSize + '"/></div></td><td><div class="tmGp"><h1>' + $.buildAccountDisplay(params.name, params.displayname) + '</h1><h2>' + params.show + '</h2></div></td></tr></table>').appendTo(dom);

        var avatar = inner.find('.zp0H img').lazyload({ data_attribute: 'JhNJ' });

    }, css);


    css = '.data-menus{position:fixed;bottom:0;font-size:0;width:100%;z-index:100;box-shadow:0 0 0.1rem 0 rgba(0,0,0,0.2);background-color:#EEEEEE;background-image:linear-gradient(top,#FFFFFF,#E0E0E0);}';

    //外包表格
    css += '.MnhZ{width:100%;font-size:0.5rem;}';


    //主项目
    css += '.p1eF{cursor:pointer;width:29.3%;}';
    css += '.p1eF.hover{background-color:#DDD;}';
    css += '.p1eF STRONG{-webkit-user-select:none;display:block;font:0.47rem normal;height:0.85rem;line-height:0.85rem;margin:0.225rem 0 0.225rem 0;border-left:0.05rem solid #FFFFFF;border-right:0.05rem solid #DDDDDD;text-align:center;}';

    //子项目
    css += '.s9jR{width:3.65rem;margin-bottom:1.45rem;position:fixed;bottom:0;z-index:99;display:none;}';
    css += '.s9jR UL{border-radius:0.16rem;background:#FFFFFF;border:0.04rem solid #CCCCCC;box-shadow:0 0 0.2rem rgba(0,0,0,0.3);}';
    css += '.s9jR LI{cursor:pointer;font-size:0.45rem;border-top:0.04rem solid #E5E5E5;padding:0.4rem 0 0.4rem 0;margin:0 0.2rem 0 0.2rem;text-align:center;color:#373737;}.s9jR LI:first-child{border-width:0}';

    //--子项目箭头
    css += '.s9jR .hDU6{-webkit-transform:rotate(45deg);width:0.25rem;height:0.25rem;background-color:#FFFFFF;margin:-0.16rem auto 0 auto;border:solid #CCCCCC;border-width: 0 0.04rem 0.04rem 0;}';

    //项目 > 管理按钮 BmUA
    //项目 > 管理按钮 > 管理操作 m6Sg
    css += '.BmUA{text-align:center;vertical-align:middle;font-size:0;width:12%;}';
    css += '.BmUA IMG{width:0.5rem;height:0.5rem;}';
    css += '.m6Sg{position:fixed;width:100%;padding:0.2rem 0;margin-bottom:1.29rem;background-color:white;bottom:0;z-index:99;display:none;left:0;}';
    css += '.m6Sg LI{font-size:0.4rem;background-color:#FFF8F8;margin:0.5rem;border:0.04rem solid #E36A6A;color:#B34A4A;padding:0.3rem;border-radius:0.12rem;}';



    $.iHuE = null;
    $.unobtrusive('menus', { values: 'Json' }, function () {
        var dom = this.dom;

        if (window.hideMenus === true) {
            dom.css('display', 'none').hide();
            return null;
        }
        var params = this.params;
        var userstatus = $.userstatus();
        var values = params.values;

        var items = [];

        var inner = $('<table class="MnhZ"><tr></tr></table>').appendTo(dom).find('tr');

        var touchHandle = 0;

        $.each(values, function (index, value) {
            var item = {};
            item.operation = false;
            item.submenu = $('<div class="s9jR"><ul></ul><div class="hDU6"></div></div>').appendTo('body');

            $.each(value.items, function (LUw9, qqBN) {
                $('<li>' + qqBN.text + '</li>').appendTo(item.submenu.find('ul')).click(function () {
                    $.link(qqBN.url);
                });
            });

            item.menu = $('<td class="p1eF"><strong>' + value.text + '</strong></td>').appendTo(inner);

            if ($.support.touch) {
                item.menu.bind('touchstart', function () {
                    if (touchHandle != 0) {
                        clearTimeout(touchHandle);
                        $.each(items, function (index, value) {
                            value.menu.removeClass('hover');
                        });
                    }

                    $(this).addClass('hover');
                }).bind('touchend', function () {
                    touchHandle = setTimeout(function () {
                        touchHandle = 0;
                        $.each(items, function (index, value) {
                            value.menu.removeClass('hover');
                        });
                    }, 250);
                });
            }

            item.operation = false;

            items.push(item);
        });

        var hideSubmenu = function (exception) {
            for (var i = 0; i < items.length; i++) {
                if (!(items[i].submenu == exception)) {
                    items[i].submenu.hide();
                }
            }
        };

        if (userstatus) {
            var item = $.iHuE = {};
            item.operation = true;
            item.submenu = $('<div class="m6Sg"><ul></ul></div>').appendTo('body');
            item.menu = $('<td class="p1eF BmUA"><img src="data:image/gif;base64,R0lGODlhKAAoAIAAAHp8gP///yH5BAEHAAEALAAAAAAoACgAAAKNhI95waEPo2pO2msohrS39WVeN42ayIBmmqwhcMJuhcx0bJeues/6yuOZfkNUb9fyGYUjYnNpLNaUMlYVmERemR5nF3qVbqbWsRl8NsfGtrb7DY/L53R4ep3e4POXPZvqpwf4cjdYdpaDhmhIU6gViJG4xScJ2cfIF/Y0mVdJuPj4mSlhOUrWaBpR+lAAADs=" /></td>').hide().appendTo(inner).bind($.support.touch ? 'touchstart' : 'click', function () {
                
                hideSubmenu(item.submenu);
                item.submenu.slideDown(200);

                if (coverHandle !== 0) {
                    clearTimeout(coverHandle);
                }

                cover.css('background-color', 'rgba(0,0,0,0.5)').show();
            });

            items.push(item);
        }


        var cover = $('<div id="mva0" style="position:fixed;top:0;left:0;display:none;width:100%;height:100%;z-index:98;"></div>').appendTo('body');
        var coverHandle = 0;
        cover.bind($.support.touch ? 'touchstart' : 'click', function () {
            var $this = $(this);

            $this.css('background-color', 'rgba(0,0,0,0)');

            hideSubmenu(null);

            coverHandle = setTimeout(function () {
                $this.hide();
            }, 800);

        });


        $.each(items, function (index, value) {
            var item = value;
            if (!item.operation) {
                item.menu.bind($.support.touch ? 'touchstart' : 'click', function () {
                    var $this = $(this);
                    var LSPACE = 0.25 * $.remUnit();
                    var RSPACE = 0.25 * $.remUnit();
                    var $W = $this.outerWidth();
                    var $L = $this.offset().left;
                    var W = item.submenu.outerWidth();

                    var L = $L + ($W / 2) - (W / 2);

                    L = L < LSPACE ? LSPACE : L;
                    L = (L + W) > (dom.width() - RSPACE) ? dom.width() - RSPACE - W : L;

                    hideSubmenu(item.submenu);
                    item.submenu.css({ left: parseInt(L) + 'px' });
                    item.submenu.show();

                    if (coverHandle !== 0) {
                        clearTimeout(coverHandle);
                    }

                    cover.css('background-color', 'rgba(0,0,0,0.5)').show();
                });
            }
        });


    }, css);

    css = '.data-lazy{background:#DD0000 url(' + $.icon.loading + ') center center no-repeat;}';
    css += '';

    $.unobtrusive('lazy', { source: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;

        dom.attr('src', $.icon.blank);

        dom.lazyload({
            data_attribute: 'lazy-source'
        });

    }, css);


    css = '.data-share{height:3.1rem;width:9.3rem;font-size:0.3rem;margin:0 auto 0 auto;}';
    css += '.TIrJ{font-size:0;}';

    css += '.IYie{height:1.9rem;}';
    css += '.IYie IMG{width:9rem;display:block;margin-left:auto;margin-right:auto;}';

    css += '.jZtQ{float:left;letter-spacing:0.1rem;}';
    css += '.jZtQ IMG{width:4.2rem;margin-left:0.24rem;display:block;margin-right:auto;}';

    css += '.BHZK{float:right;}';
    css += '.BHZK IMG{width:4.2rem;margin-right:0.24rem;display:block;margin-left:auto;}';

    css += '.U6B4{position:absolute;margin-left:5.57rem;width:1.7rem;height:1.55rem;line-height:1.55rem;font-size:0.58rem;color:#FFF;text-align:center;}';

    var Hb5W;
    css += '.Hb5W{position:fixed;top:0;right:0.3rem;z-index:101;display:none;width:5rem;}';
    css += '.Hb5W IMG{width:5rem;}';

    $.unobtrusive('share', { integral: 'Integer', hide: 'Integer' }, function () {
        var dom = this.dom;
        var params = this.params;
        var userstatus = $.userstatus();

        var fn = function () {
            $.logon(function () {
                if (!Hb5W) {
                    Hb5W = $('<div class="Hb5W"><img src="/Images/CD.png"/></div>').appendTo('body');
                }

                Hb5W.show();

                $.cover(true, function () {
                    Hb5W.hide();
                });

            }, '请登录后分享给朋友或朋友圈');
        };

        var IYieImage = '';
        var IYieIntegral = '';
        if (params.integral === undefined || params.integral === null || params.integral == 0) {
            IYieImage = '/Images/global/PJpz.png';
        }
        else {
            IYieImage = '/Images/global/wLcN.png';
            if (params.hide == 1) {
                IYieIntegral = '<div class="U6B4">1~100</div>';
            }
            else {
                IYieIntegral = '<div class="U6B4" style="font-size:0.66rem;">' + params.integral + '</div>';
            }
        }

        var IYie = $('<div class="IYie TIrJ">' + IYieIntegral + '<img src="' + IYieImage + '"/></div>').appendTo(dom);
        IYie.find('img').click(fn);
        IYie.find('.U6B4').click(fn);


        var jZtQ = $('<div class="jZtQ TIrJ"><img src="/Images/global/HIdq.png"/></div>').appendTo(dom).find('img').click(function () {
            $.link('/user/Register');
        });

        var BHZK = $('<div class="BHZK TIrJ"><img src="/Images/global/c9mf.png"/></div>').appendTo(dom).find('img').click(function () {
            $.link('http://mp.weixin.qq.com/s?__biz=MzAwMDA0Mjc0Ng==&mid=200483699&idx=1&sn=7d203e133017ab3bc4676ca64f49cf01#rd');
        });



    }, css);



    css = '.data-release{}';

    css = '.data-release BUTTON{font-size:0.45rem;height:0.9rem;line-height:0.5rem;padding:0 0.3rem;}';

    $.unobtrusive('release', { text: 'String', url: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;
        var crumbs = $.crumbs();
        var crumb = crumbs[crumbs.length - 1];

        var url = params.url;
        var button = $('<button class="buttonA">' + params.text + '</button>').appendTo(dom);
        button.click(function () {
            var url;

            if (params.url.search(/owner\=/ig) == -1) {
                url = $.setUrlQuery(params.url, 'owner', crumb.id);
            }
            else {
                url = params.url;
            }

            url = $.setUrlQuery(url, 'redirect', location.href);

            $.link(url);

        });

    }, css);


    css = '.data-inputnumber{}';
    $.unobtrusive('inputnumber', {}, function () {
        var dom = this.dom;
        dom.keyup(function () {
            var $this = $(this);
            var v = $this.val();
            if (v.search(/[^\d]/ig) > -1) {
                $this.val(v.replace(/[^\d]/ig, ''));
            }
        });
    }, css);




    css = '.data-valid{}';

    $.unobtrusive('valid', { setting: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;

        var form = dom.closest('form');




        //必须填写
        var __required = function (value) {
            var defaults = { text: '必须填写' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };

            if (dom.val().trim().length > 0) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };


        //字符长度
        var __length = function (value) {
            var defaults = { text: 'error' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };

            var v = dom.val();
            var L = (v.match(/[^\x00-\xff]/ig) || []).length * 2 + (v.match(/[\x00-\xff]/ig) || []).length;

            if (L == 0) {
                r.success = true;
            }
            else if (value.min <= L && L <= value.max) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };


        //邮箱
        var __email = function (value) {
            var defaults = { text: '请正确填写邮箱地址' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };

            var val = dom.val().trim();
            if (val.search(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/ig) >= 0) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };


        //银行账号
        var __bankAccount = function (value) {
            var defaults = { text: '请正确填写银行账号' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };

            var val = dom.val().trim();
            if (val.search(/^(\d{4}\s)+\d+$/ig) >= 0) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };



        //纯数字
        var __number = function (value) {
            var defaults = { text: '请输入纯数字' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };

            var val = dom.val().trim();
            if (val.search(/^\d+$/ig) >= 0) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };



        //纯字母
        var __letter = function (value) {
            var defaults = { text: '字符 "{0}" 不允许使用' };
            value = $.extend(defaults, value);


            var r = { success: true, text: value.text };

            var val = dom.val().trim();
            if (val.search(/[^\w\u4E00-\u9FA5]/ig) >= 0) {
                r.success = false;

                var illegal = dom.val().match(/[^\w\u4E00-\u9FA5]/i);
                r.text = value.text.format([illegal]);
            }
            else {
                r.success = true;
            }


            return r;
        };


        var __equal = function (value) {
            var defaults = { text: '请重复输入' };
            value = $.extend(defaults, value);

            var r = { success: true, text: value.text };
            var V1 = dom.val();
            var V2 = dom.closest('form').find('*[name=' + value.name + ']').val();

            if (V1 == V2) {
                r.success = true;
            }
            else {
                r.success = false;
            }

            return r;
        };

        //自然数
        var __count = function (value) {
            var defaults = { text: '请输入数量' };
            value = $.extend(defaults, value);

            var r = { success: false, text: value.text };
            var v = 0;

            var val = dom.val().trim();
            if (val.search(/^\d+$/ig) == 0) {
                v = parseInt(val);
                if (v >= 0) {
                    r.success = true;
                }
            }

            if (r.success) {
                if (value.min !== undefined && v < value.min) {
                    r.success = false;
                }

                if (value.max !== undefined && v > value.max) {
                    r.success = false;
                }
            }

            return r;
        };

        //浮点数
        var __float = function (value) {
            var defaults = { text: '请输入数量' };
            value = $.extend(defaults, value);

            var r = { success: false, text: value.text };
            var v = 0;

            var val = dom.val().trim();
            if (val.search(/^\d+$/ig) == 0) {
                v = parseFloat(val);
                if (v >= 0) {
                    r.success = true;
                }
            }

            return r;
        };



        var validate = function () {
            var result = null;

            for (key in params.setting) {
                var value = (params.setting[key] === true ? {} : params.setting[key]);

                eval('result = __' + key + '(value);');
                if (result.success == false) {
                    break;
                }
            }

            if (result.success) {

            }
            else {
                $.message(result.text);
            }

            return result.success;
        }

        dom.blur(function () {
            validate();
        });



        if (form.data('rrGQ') === undefined) {
            form.data('rrGQ', new Array());
            form.data('rrGQ').push(validate);

            form.submit(function () {
                var r = true;

                $.each(form.data('rrGQ'), function (index, value) {
                    var result = value();
                    if (!result) {
                        r = false;
                    }
                });

                if (!r) {
                    return false;
                }

            });
        }
        else {
            form.data('rrGQ').push(validate);
        }

    }, css);


    css = '.data-tab{display:box;box-pack:center;box-align:stretch;}';
    css += '.data-tab .A9g8:first-child{border-left-width:0.06rem;border-radius:0.1rem 0 0 0.1rem;}';
    css += '.data-tab .A9g8:last-child{border-right-width:0.06rem;border-radius:0 0.1rem 0.1rem 0;}';
    css += '.A9g8{font-size:0.4rem;padding:0 0.4rem 0 0.4rem;display:box;box-align:center;background-color:white;text-align:center;border:solid;border-width:0.06rem 0.06rem 0.06rem 0;}';
    css += '.exky{color:#FFF!important;}';

    $.unobtrusive('tab', { items: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;

        var color = '#D42C2C';

        $.each(params.items, function (index, value) {
            var item;

            if (value.value == 1) {
                item = $('<div style="background-color:' + color + ';border-color:' + color + '" class="A9g8 exky"></div>').appendTo(dom);
            }
            else {
                item = $('<div style="color:' + color + ';border-color:' + color + '" class="A9g8"></div>').appendTo(dom);
            }

            item.text(value.text).fastClick(function () {
                var url = location.href;
                url = 'plugin.php?id=hejin_share'+value.name;

                $.link(url);
            });

        });

        if (dom.find('.exky').size() == 0) {
            dom.find('.A9g8:eq(0)').addClass('exky').css('background-color', color);
        }
    }, css);


    css = '.data-tabz{display:box;width:100%;background-color:white;border-bottom:0.05rem solid #DDD;}';
    css += '.vWkX{box-flex:1.0;padding:0.20rem 0;border:solid #FFF;border-width:0.1rem 0 0.1rem 0;}';
    css += '.vWkX:first-child SPAN{border-left-width:0;}';
    css += '.data-tabz .ko9Z{border-color:#FFF #FFF #D42C2C #FFF}';
    css += '.vWkX SPAN{font-size:0.4rem;text-align:center;display:block;border-left:0.05rem solid #DDD;color:#777;}';

    $.unobtrusive('tabz', { items: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;

        $.each(params.items, function (index, value) {
            $('<div class="vWkX ' + (($.locators()[value.name] == value.value && value.value) || value.url == location.pathname.replace(/_.+$/ig, '') ? 'ko9Z' : '') + '"><span>' + value.text + '</span></div>').appendTo(dom).fastClick(function () {
                var url;
                if (value.url) {
                    url = value.url;
                }
                else {
                    var locators = {};
                    locators[value.name] = value.value;

                    url = $.setUrlQuery(location.href, { 'message': null, 'page': null });
                    url = $.setUrlLocator(url, locators);
                }

                $.link(url);
            });
        });

        if (dom.find('.ko9Z').size() == 0) {
            dom.find('.vWkX:eq(0)').addClass('ko9Z');
        }

    }, css);


    css = '.data-pagebar{height:0.98rem;text-align:center;margin:0 0.3rem 0 0.3rem;padding-top:0.3rem;padding-bottom:0.4rem;}';

    //项目 > 上一页按钮 I4v7
    //项目 > 下一页按钮 Q47J

    css += '.I4v7{display:box;cursor:pointer;float:left;}';
    css += '.I4v7 DIV{position:absolute;height:0.98rem;width:2.55rem;line-height:0.98rem;font-size:0.4rem;color:#AAA;}';
    css += '.I4v7 IMG{height:0.98rem;}';

    css += '.Q47J{display:box;cursor:pointer;float:right;}';
    css += '.Q47J DIV{position:absolute;height:0.98rem;width:2.54rem;line-height:0.98rem;font-size:0.4rem;color:#AAA;}';
    css += '.Q47J IMG{height:0.98rem;}';

    //项目 > 中间 qt3s
    css += '.qt3s{font-size:0.5rem;text-align:center;height:0.98rem;line-height:0.98rem;}';

    css += '.w2EL DIV{color:#333;}';


    $.unobtrusive('pagebar', { total: 'Integer', size: 'Integer', index: 'Integer' }, function () {
        var dom = this.dom;
        var params = this.params;


        var a = 'R0lGODlhmAA6APcAAP////7+/v39/fz8/Pv7+/r6+vn5+fn6+vj4+Pf39/b29vb39/X19fX19vT19fT09PPz8/Pz9PLy8vHy8/Hy8vHx8vHx8fDw8PDw8e/v8O/v7+/w8O7v7+7u8O7u7u3t7ezs7Ozt7evs7Ovr7Ovr6+rr6+rq6+rq6urr7Onq6+np6ejq6+nq7Ojp6ujp6ejo6efn5+bo6efn6Obm5+bm5uXl5eXl5uTk5OTl5uPk5ePk5OPj5OPj4+Lk5uLj5uLj4+Li4+Li4uHj5OLj5ODi4+Dg4ODh49/g4t/f397g497g4t3g4t3f4dzf4d7e393d3tzd3tzd3dvd4Nvd39nc3dnc3tvb29jb3dra2trb29rb3dnb3tnZ2dnZ2tfa3dja3NjY2NbZ2tbZ29jZ2tfX19fY2NbX2dbX19bW1tXX2dXX2NXW19XW1tXV1tTW2NTW19TV2NXW2NTV19TV1dTU1dPV19PV1tPU1NPT1NLU19HU1tPU1dLT1NHT1tHT1M/T1tDT1tDS08/S1dHS087S1c/R083R1M7R1dDR09DR0c7Q087Q0s/Q0s/Q0c3P0szP0czP1MzP0svO0crO0c3NzsrN0MrN0cnM0MjM0MjLz8jLzsfLz8nLzMbKzsfKzsXJzsXJzcTIzcTIzMPHzMLHy8LGysXGxsXGx8HGysTFxsHFycTFxcDEyMHExr/Dxr/Dx77Cxb7Bxb7BxL3Aw77Awr2/wr6/v72+vr2+wLy9vru8vP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHALsALAAAAACYADoAAAj/AHcJHEiwoMGDuzwU4YKmocOHECNKnEixosWLGDNOJBMEAsKPIAmeAAODwQAAKFOqXMmypcuXMGPKnEnzJQQkMELqLJihjYyTNYMKHUq06MwaL3buVGFFgdGnUKNKbYlFgVKQMJAQCMC1q9evYMOKHUu2rNmzaNN21cDj6sECNGgIUEu3rt27eM8WKOO24AMgJvIKHky4cFg6fQdqaHPBsOPHkNEiTiziDIPImDNnvpP4RZcEAkKLHk26tOnTqFOrXs26tevUePrOeFLgte3buHPrzs3nagEbPwbsHk68uPHVvXcyGOJigPPn0KNLn069uvXr2LNr367dz84NfEJw/x9Pvrz58+idD9JZwg6F9PDjy59/PVBIGWYYENjPv7///wAGKOCABBZo4IEIJkgAIiDhoAUCCkYo4YQUVkhhIR/lcEQBFnbo4YcgHoihQQwQQUQBKKao4oostujiizDGKOOMNNZo44qLGBQBIync6OOPQAYp5JAo5kiQA4uMQOSSTDbp5IyOELSAES0YYOWVWGap5ZZcdunll2CGKeaYZHIZ5UBCxFDmmmy26eabcGb5yEA9MIHAnXjmqeeefPbp55+ABirooIQW+qckAk2QxwKGNuroo5BGKumeiO4iBQuTZqrpppwaaskuBCiyQAKklmrqqaimquqqrLbq6quwxv8qa6uVCBTJrLjmquuuvPaq6iUCeYGCAsQWa+yxyCar7LLMNuvss9BGK22zmAjUQR4NTKvtttx26+23yGYy0BJLgGvuueimK+0mAx2gyQgMxCvvvPTWa++9+Oar77789uvvv/h6QtAKk3AA8MEIJ6zwwgzP20lBPkBSQcMUV2zxxfx+YlAShlTwwMcghyzyyCSXbPLJKKes8sostzwyKAc1cYjHLtds880456xzKAg1IYgFEAQt9NBEF2300UgnrfTSTDft9NNDi/LRFDNDbfXVWGettdajgFQFIRVsLfbYZJfNNCkhUfGzBGy37fbbcMct99x012333XjnrbcEpej/VMUfFuwt+OCEF2744KjstEUfgR/u+OOQRz63KkpdAQgGFmSu+eacd+7556CHLvropJdueumsXPVFH5if7vrrsMcuu+ypXyWGHq3PrvvuvPce+it9haHHBcQXb/zxyCev/PLMN+/889BH77wriaWRhvTYZ6/99txvD0tiu1jf/fjkl28+87GAv4saamjg/vvwxy///PTXb//9+Oev//71y6L+Lm5wA/8GSMACGvCAA5zF/xAgBzkg8IEQjKAE51eL/+0iAXLghAsmyMEOetB+JmiFBQUSBVpA4YMoTOEEx7CHEQrEBI04hRM8QMMa2vCGOMyhDnfIwx768IdADKIOUnBBAhcOxAaUSAUbdvCBIDrxiVCMohR1SIIbJCIXWTBiQVQwB1PcQhdgDKMYx0jGMprxjGhMoxrXyEYz2mIVYACBFudIxzra8Y54zKMe9yiQgAAAOw==';

        var b = 'R0lGODlhmAA6APcAAP////7+/v39/fz8/Pv7+/r6+/r6+vr7+/n5+fj5+fj4+Pf3+Pf39/f4+Pb39/b29/b29vX29vX19vX19fX29/T19vT19fT09fT09PPz8/P09PLy8vLz8/Hx8fDw8PDx8e/w8O/v8O/v7+7u7+7u7u7v7+3t7u3t7e3u7uzs7Ovr7Ovr6+rq6+np6unp6enq6+jo6efn5+bn6Obn5+bm5+bm5uXm6OTk5OTl5uPk5ePj5OPj4+Pk5uLj5eLj5OLi4+Li4uHj5eHi4+Hi4uHh4uHh4eHi5N/g4d7f393d3t3d3dze4Nvb3Nvb29rc3tnc3tvc3Nra2tra29rb3dnb3dna29nZ2tja3Nfa3NjY2tjY2NjZ2tfZ29bZ29fX2NfX19fY2tfY2NbY2tbX2NbW1tXX2dXW2dbX2dXV1tXV1dTW2NTW19PV2NPV19LV2NTV1dPU1NPU1tLU19LU1tLU1NHU19HT1M/T1tDT19DT1tLT1c7S1dHS1M/S1c/R1NDR0s7R1M3R1M3R1c3Q08zQ1M7Q0c7Q0szQ083P0c7Oz87P0c3P0szP0cvP08vP0svO0crO0srO0cnO0crN0srN0MjM0MnMz8jLzsjLzcfLz8fKzcfJzMfJzcfHx8bIy8XHycTGyMXGxsPExsLDxcDCxMDBwr2/wb2+vr2+v7u8vP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAKoALAAAAACYADoAAAj/AFUJVNWhCJmDCBMqXMiwocOHECNKnOgwCpAOAzNq3MhxI4EYOzAAGEmypMmTKFOqXMmypcuXKglkuPElRcebOAXSWAGzp8+fQIO+NKDjB4acSAeSACK0qdOnUFFu0GIzaU4lEAJo3cq1q9evYMOKHUu2rNmzWxU0cWH15oAwaOPKnUu3rlkCSGK05aiAid2/gAML/nqjhoG9GRf4Hcy4seOyLIwiFsigyePLmDF7SFNiMgMpAkKLHk26tOnTqFOrXs26tevUGLyoQPz5te3buHPrzs1gC4y9tXcLH068uGoDSWi0ZVBlgPPn0KNLn069uvXr2LNr365dCI7DSBlY/+FOvrz58+jTO4dB5GjO3urjy59P/zoKOyDeZyHAv7///wAGKOCABBZo4IEIJqggAR/EwQJODICx4IQUVmjhhRZOUIYMN0WI4YcghigiggpMgUNHDpxhwIostujiizDGKOOMNNZo44045ujiET0MsFGKOgYp5JBEFmnkijn0cIBGDpRx5JNQRimljTj0wKQZCGSp5ZZcdunll2CGKeaYZJZp5plf+mDlQA6ogeabcMYp55x0cnmEDwM9wIYCfPbp55+ABirooIQWauihiCaqKKFc2CCQnotGKumklFZqKaAaAJKAKg+4cemnoIYq6qI8BMGpHAykquqqrLbq6quwxv8q66y01mrrrbJy4IcqFNSB66/ABivssMS6msEgvOIBwbLMNuvss9BGK+201FZr7bXYZkstD0/wmoe24IYr7rjklvvssbNRcIe57Lbr7rvZYkGFQBH0McG9+Oar77789uvvvwAHLPDABBfsLw5lDBTBHgY37PDDEEcscb4vZMKhQBIIMvHGHHfsscAmVGJERhIEgsHJKKes8sost+zyyzDHLPPMNNe88giRLKGRBITY7PPPQAct9NAYlDCJExtJcEgGTDft9NNQRy311FRXbfXVWGettdM4z7tRBY1sLfbYZJdtttkjTOL1146c7fbbcMd9dQmSrP11JBvkrffefPf/7fffgAcu+OCEF2744RvgfAVOFeCN+OOQRy755I+LAEkXOVlACeWcd+7554GH8IgYSFlgSQeop6766qy37vrrsMcu++y01047zgmXfrrtvPfu++/AB/9I7khdcEnwyCev/PKzP8JGWxdg4sH01Fdv/fXYZ6/99tx37/334HfPyBp7XaBJ+Oinr/767K/PSBuIXcBJ+/TXb//92y8yx2QXbCLC/wAMoAAHSMACGvCACEygAhfIQAMaYn+T0YAnGkjBClrwghikICL0MBmBXOATGQyhCEdIQgIqgg8dFEgBSEGCErrwhTA84AxAQYcUDqQQQ4ihDndYwjGYYgs2HAgLbETRAhIY8YhITKISl8jEJjrxiVCMohSnKIVR/IEtQRwIFFCBhhucYIpgDKMYx0jGJgLhDaVIhA6yuBEMfCEUp0iFHOdIxzra8Y54zKMe98jHPvoRj6foBBywyMZCGvKQiEykIhfJyEY6MicBAQA7'

        var previous = $('<div class="I4v7"><div>&nbsp;&nbsp;上一页</div><img src="data:image/gif;base64,' + a + '" /></div>').appendTo(dom);

        var next = $('<div class="Q47J"><div>下一页&nbsp;&nbsp;</div><img src="data:image/gif;base64,' + b + '" /></div>').appendTo(dom);

        var count = parseInt(params.total / params.size);
        if (count != (params.total / params.size)) {
            count++;
        }
        if (count == 0) {
            count = 1;
        }

        var qt3s = $('<div class="qt3s">' + (params.index + 1) + '/' + count + '</div>').appendTo(dom);

        if (params.index > 0) {
            previous.addClass('w2EL').fastClick(function () {
                $.link($.setUrlQuery(location.href, 'page', params.index - 1));
            });
        }
        if (params.index < count - 1) {
            next.addClass('w2EL').fastClick(function () {
                $.link($.setUrlQuery(location.href, 'page', params.index + 1));
            });
        }


    }, css);


    css = '.data-inputcount{}';

    //项目 > 外包裹 L2xc
    //项目 > 外包裹 > 文本框 YCoP
    //项目 > 外包裹 > 减按钮 bzeu
    //项目 > 外包裹 > 加按钮 XJeQ
    //项目 > 单位 tgyH
    css += '.L2xc{width:4rem;height:0.9rem;font-size:0;display:box;}';
    css += '.YCoP{border-width:0;width:1.2rem;height:0.8rem;line-height:0.8rem;background-color:#FFF;margin-top:0;box-flex:1.0;text-align:center;display:block;border:solid #AAA;border-width:0.05rem 0 0.05rem 0}';
    css += '.bzeu,.XJeQ{font-size:0.5rem;line-height:0.8rem;height:0.8rem;text-align:center;background-color:#F0F0F0;width:0.8rem;border:0.05rem solid #AAA;color:#777;}';
    css += '.tgyH{line-height:0.8rem;padding-left:0.2rem;color:#777;font-size:0.5rem;}';


    $.unobtrusive('inputcount', { min: 'Integer', max: 'Integer', unit: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;


        var input = dom.addClass('YCoP');
        dom = dom.wrap('<div class="L2xc"></div>').parent();


        var getValue = function (add) {
            var r = parseInt(input.val());
            if (isNaN(r) || r < 0) {
                r = params.min;
            }
            if (add != null) {
                r += add;
            }
            if (!isNaN(params.max)) {
                if (r > params.max) {
                    r = params.max;
                }
            }
            if (r < params.min) {
                r = params.min;
            }

            return r;
        };

        var addition = $('<div class="bzeu">-</div>').prependTo(dom).fastClick(function () {
            input.val(getValue(-1));
        });

        var subtraction = $('<div class="XJeQ">+</div>').appendTo(dom).fastClick(function () {
            input.val(getValue(1));
        });

        if (!params.min) {
            params.min = 0;
        }

        input.change(function () {
            input.val(getValue(0));
        });

        if (params.unit) {
            $('<span class="tgyH">' + params.unit + '</span>').appendTo(dom);
        }

    }, css);


    css = '';
    $.unobtrusive('rccode', { value: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;
        dom.attr('src', $.getRCCodeUrl(params.value));
    }, css);


    css = '';
    $.unobtrusive('integral', { value: 'Integer' }, function () {
        var dom = this.dom;
        var params = this.params;

        dom.html('<span>' + params.value + '榄币(</span><strong>即¥' + (params.value / 100) + '</strong><span>)</span>');

    }, css);



    css = '.U4gf{cursor:pointer;height:0.83rem;background-color:#FFF;border-radius:0.13rem;border:0.03rem solid #DDDDDD;display:box;box-align:stretch;background-image:linear-gradient(top,#F5F7FA,#F0F2F5);}';
    css += '.iLmq{height:0.83rem;line-height:0.83rem;display:box;box-align:center;padding:0 0.01rem 0 0.2rem;}';
    css += '.iLmq IMG{height:0.53rem;display:block;}';
    css += '.QpjP{height:0.83rem;line-height:0.83rem;min-width:0.8rem;padding:0 0.3rem 0 0.15rem;color:#999;font-size:0.4rem;}';

    $.unobtrusive('replybutton', {}, function () {
        var dom = this.dom;
        var params = this.params;

        dom.html('<div class="U4gf" style="float:right;margin-left:0.2rem;"><div class="iLmq"><img src="data:image/gif;base64,R0lGODlhKAAlAPcAADZos+Pq836ezbrL5Ep3uqe922KKw9Xe7Zex1vLz9jprtXCSyFiDwOzx9s3X6bTG4UBvt4+p0+vu9N3l8fP1+VJ+vcfT56G52oun0WmNxneZzK/E3kZzuThos9Ha6TxttfX3+V+Hw4Wi0LnK4056vJ212OHn8PHz9+ft88LS5e/x9dfg7pWt1W+RyKq/3XqbzDhqs/P190RyuD5utWaMxUl1uXSXysrV51yFweHp81V/v83Z6r3O5e/y99Pd6tvi773M42yRx+br8Ymn0Zuz16W727fJ4aC32fH19+nt9Njh7VB8ve3x9e/z9TZotbDE32GJw0x4uo+r1I2o0zpstWiNxYGhz3aZza3B3d/m8kJwt1R+vlyFw+Pp8sPR5cvY6ThoteXr8nyczbnJ4zxst6m+3OHp8UBut6G32WaPxcDP5Eh2u1qFwcjV55Gr1Dhqtc/a6+ru9VaBv9zj79Hc6+/z96/D37fH4lJ8v26TxmSKxEZ0uWuPx6O52nmZzMPT5dPd7bvL4lSAv9/l8Zmy18fV6Yej0+br85201+Xr8/Hz9WaNxevv9aW73Ux4vV2HwTxutent9e3x983X60BwudHb6+Pn8Ofs9dnh7z5ut9vj7+Xp81aBwfP3+ae93ZGr1bfJ47HE33CVyUp4u0R0uUl2uZWv1bHD30JwuWCJxdff7Z+326u/37vN5ViFwXmZy8/Z6r/N42yTx4mn04Ohz1yHw8PR58vZ6W6TyaO723ibzf///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHALsALAAAAAAoACUAAAj/AHcJHEiw4K4cXwYoHLAjgMGHEB8eWBWEA4yLGC8SEFVkQsSPA+2EyPhhSYgqVXCQoAKgpQI+I0A+HCBHgQItL1gBasCzJ084BWzM6NAhhAWZApNoUACATYFLFKJKnTo1QIkKLQ01ALlCEIBSWHpQHUu2RxEtAKA4hIhpFIAMJsjKJYsJRwcGOR6i8Gqjwdy/VJPQAGBga8EXMGhEAsxYagA5AKQUVKNAxoTGmCn4+PABEMEQAI5kzjwFgI2BDmBwSDIac5YZMzzukgLAyonWmPkAKCIQCoAHuDGX6KBBIAQAK8bOcfFlbCA7cahiKlNprBoYOnZJuMh66o89RDdM/xVhE4cKqZW0dKAygiogGHtOBLh4e2ofoh0W1KdwvCUdqQi0BIAY+1HwAwxanMDERV0k4KCD112EwQkP4tBSJg06GAp+pjzoIBwwlNLDLjUA4ACFHhZRxRAoeKjKK0GkgKKDJVQhRRIeJmBEB4+MKAoAfcyY45BEFukgC7WdsEsRHejRg5FQRpnAI78JtAkEMJwo5ZYewoLgIQNFAAANKnBppg0ATKGkQAGQAAAhQppZJBAKcGBGQSlQQYULccrpoRKlAPDEQ1hcVEKffs6hAwBDjPhQATb5EYafHrbhphhMfBQIBwCQMAGlErjxAQARqACSCkpg5YGcliASBQwE3IhhKlJyfCDBg2Z4kaGHEjhwRB5DQTCFEI7KdGALKiRrAg4XRYHDIovgEIVNNslBRBazIrULkk/00MMNS1DhBxTgCcgBDi9ccECy2g50SClLSOCBAFRoEUiyyQohBL7stksQBh1ssYWeNPiQrb8ghTEqAB8sAMTBCMtkBBYWoNBvxNp6WyzGEQUEADs=" /></div><div class="QpjP">回复</div></div>');

    }, css);



    css = '.data-operations{width:1rem;height:1rem;background-color:#F0F0F0;position:relative;z-index:10002;display:box;box-align:center;box-pack:center;}';
    css += '.sE52{width:0.5rem;height:0.5rem;display:block;}';
    css += '.p18r{width:1rem;height:1rem;position:absolute;}';
    css += '.oQPX{font-size:0.45rem;background-color:#F0F0F0;position:absolute;display:none;padding:0.15rem 0.2rem 0.15rem 0.2rem}';
    css += '.oQPX LI{white-space:nowrap;padding:0.3rem 0.4rem 0.3rem 0.4rem;border-top:0.05rem solid #DDD;color:#555;}';
    css += '.oQPX LI:first-child{border-top-width:0;}';

    $.unobtrusive('operations', { menus: 'Json' }, function () {
        var dom = this.dom;
        var params = this.params;
        var menus = params.menus;


        var domWidth = dom.width();
        var domHeight = dom.height();


        var sE52 = $('<img class="sE52" src=""/>').prependTo(dom)

        var p18r = $('<div class="p18r"></div>').prependTo(dom);

        var oQPX = $('<ul class="oQPX" style="margin-top:' + domHeight + 'px;"></ul>').prependTo(dom);


        if (menus.length == 1 && menus[0].type == 'edit') {
            sE52.css({ width: '0.65rem', height: '0.63rem' }).attr('src', 'data:image/gif;base64,R0lGODlhMAAuANUAAFVVVV5eXrW1tX5+ftHR0ZmZmWxsbMfHx+Pj46Ojo46OjmJiYnp6etnZ2VZWVr+/v8zMzKGhoaurq729vWpqaoWFhWZmZnV1ddfX18XFxZ6enqmpqbu7u3FxcYODg7GxsZKSktvb21hYWGBgYM/Pz4eHh3x8fMnJyZGRkZmZmaWlpa2trf///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHACwALAAAAAAwAC4AAAb/QJZwSCwaj0hjIZFsOp9DkMMRGToA2Kx2y+1qKSRjJVtlXb3odBZMDA3H2OpZTd+yh6FB5U2e1+sdGG0XU3tCcBF+f2kdDUQEBlqGLBUbCH5QmUIEFFweRZiaTycLXihEoaJJD6VdAROoWqqrrVwBGaCys0YCIl4UEEapu0ISimthwrrELAm+XYFDBLFZzCwFz1wMjpsG1FjMUl7bQ6QA3+e7JcdYHgjlreizeWgKRA8j1VbLmXnsAAXeDamVTsiwJyEIedEgcJ8+g/ycQEJjSdlDMxGTTPTyAcmwg0gO5HMFyyM/kEZYeQnAocnHjEVUusLl8iTMIb1+BXPy8qKRigu/kvG06bNIiE5bGmXqCc4IAQFCSBAkt5Ro0yISFpQUiYVq1YsoBwAYcUDIhBHuVDEtiKfViGQHGmpam3IOBW676BZBkYXBhmnE9BIZoGBCA7l5rbK1ZhLsTcYOr2IsClle5MWVLUOknHmyZJSd104ZTbq06dOnt6BbxNrL6tawHceeLZv2bCJBAAA7');

            var menu = menus[0];
            p18r.fastClick(function () {
                $.link($.setUrlQuery(menu.url, 'redirect', location.href));
            });
        }
        else {
            sE52.attr('src', 'data:image/gif;base64,R0lGODlhJAAcAJEAADMzMzExOjg4Of///yH5BAEHAAMALAAAAAAkABwAAAJBFD6pdu0PI1BK2ttowjzq0XXfSJbmiabqyrbuC8fyTC+ZxoSiputfH/oBJbWi8YhMKpdMY+6TGxJ50ouwOqVgHwUAOw==');

            $.each(menus, function (index, value) {
                var V = value;
                $('<li></li>').text(value.text).appendTo(oQPX).click(function () {
                    var $this = $(this);

                    if (V.url) {
                        var url = V.url;
                        url = $.setUrlQuery(url, 'redirect', location.href);
                        if (V.query) {
                            if (confirm(V.query)) {
                                $.link(url);
                            }
                        }
                        else {
                            $.link(url);
                        }
                    }

                });
            });

            p18r.fastClick(function () {

                $.cover(true, function () {
                    oQPX.hide();
                });

                oQPX.show();
                oQPX.css('margin-left', '-' + (oQPX.outerWidth() - domWidth) + 'px');

            });
        }



    }, css);



    css = '.data-countdown{font-size:0.35rem;white-space:nowrap;}';
    css = '.data-countdown STRONG{color:red;}';

    $.unobtrusive('countdown', { count: 'Integer', conclusion: 'String' }, function () {
        var dom = this.dom;
        var params = this.params;

        var C = params.count;

        var getTime = function (count) {
            var r = {};

            r.day = parseInt(count / 86400);
            count -= r.day * 86400;

            r.hours = parseInt(count / 3600);
            count -= r.hours * 3600;

            r.minutes = parseInt(count / 60);
            count -= r.minutes * 60;

            r.seconds = count;

            return r;
        };

        var fn = function () {
            C--;
            var time = getTime(C);

            var text = '';
            if (C > 0) {
                if (time.day > 0) {
                    text += '<strong>' + time.day + '</strong><span>天</span>';
                }
                if (time.hours > 0) {
                    text += '<strong>' + time.hours + '</strong><span>小时</span>';
                }
                if (time.minutes > 0) {
                    text += '<strong>' + time.minutes + '</strong><span>分</span>';
                }

                text += '<strong>' + time.seconds + '</strong><span>秒</span>';
            }
            else {
                text = '<strong>' + params.conclusion + '</strong>';
            }

            dom.html(text);
        };

        setInterval(fn, 1000);

        fn();

    }, css);

});



var Nyjo1;
window.SetAccountName = function ($dom) {

    if (!Nyjo1) {
        var css = '.Nyjo1{font-size:0.5rem;position:fixed;top:5rem;margin-left:10%;z-index:10003;display:none;width:80%;background-color:#EEE;height:4.2rem;border-radius:0.3rem;box-shadow:0.2rem 0.2rem 0.3rem #FFF inset, -0.2rem -0.2rem 0.3rem rgba(0,0,0,0.2) inset, 0.2rem 0.2rem 0.3rem rgba(0,0,0,0.15);}';
        css += '.A83t1{padding:0.55rem 0 0.28rem 0;}';
        css += '.A83t1 INPUT{font-size:0.5rem;height:1.1rem;margin:0 auto 0 auto;padding-left:0.25rem;width:6.7rem;display:block;border-radius:0.2rem;text-align:center;}';
        css += '.HjIy1 INPUT{padding:0.2rem 0.5rem 0.2rem 0.5rem;margin:0 auto 0 2.5rem;display:block;}';
        css += '.Zfnz1{font-size:0.33rem;padding:0 0 0.25rem 0;text-align:center;}';

        $.addStyleSheet(css);

        Nyjo1 = $('<div class="Nyjo1"><div class="A83t1"><input type="text" placeholder="请输入您的新账号" value="" /></div><div class="Zfnz1">输入您的新账号，按“确认修改”即可！</div><div class="HjIy1"><input class="buttonA" type="submit" value="确认修改" /></div></div>').appendTo('body');
        Nyjo1.find('.HjIy1 input').click(function () {
            inputAccountName = Nyjo1.find('.A83t1 input');

            var s = inputAccountName.val();

            if (s.search(/^[\u4E00-\u9FFF\w]+$/i) > -1) {
                $.post('/user/SetAccountName', {name: s}, function (result) {
                    

                    if(result.success == 1) {
                        Nyjo1.hide();
                        $.cover(false);
                        $dom.text(s);
                    }
                    else {
                        alert('您选择的账号名称已被使用，请重新选择！');
                    }
                    
                    
                }, 'json');
            }
            else {
                alert('榄讯账号不能使用特殊符号！');
            }
        });
    }

    Nyjo1.find('.A83t1 input').val($dom.text());

    Nyjo1.show();

    $.cover(true, function () {
        Nyjo1.hide();
    });


};
