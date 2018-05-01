jQuery(function () {
    var $ = jQuery,
        $wrap = $('#uploader'),
        $queue = $('#uploader .filelist').appendTo($wrap.find('.queueList')),
        $placeHolder = $wrap.find('.placeholder'),
        fileCount = $('#uploader .filelist li').length,
        fileSize = 0,
        MaxCount=5,
        ratio = window.devicePixelRatio || 1,
        thumbnailWidth = 130 * ratio,
        thumbnailHeight = 130 * ratio,
        state = 'pedding',
        percentages = {},
        supportTransition = (function () {
            var s = document.createElement('p').style,
                r = 'transition' in s ||
                      'WebkitTransition' in s ||
                      'MozTransition' in s ||
                      'msTransition' in s ||
                      'OTransition' in s;
            s = null;
            return r;
        })(),
        uploader;
    if (!WebUploader.Uploader.support()) {
        alert('&#x4F60;&#x7684;&#x624B;&#x673A;&#x4E0D;&#x652F;&#x6301;&#x4E0A;&#x4F20;&#xFF01;');
    }
    uploader = WebUploader.create({
        pick: {
            id: '#filePicker',
            label: '',
            multiple:false
        },
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        compress: {
            width: 640,
            height: 640,
            quality: 90,
            allowMagnify: false,
            crop: false,
            compressSize: 0,
            preserveHeaders: true
        },
        auto: true,
        server: '/fileupload.php',
        sendAsBinary: true,
        fileNumLimit: 5,
        fileSizeLimit: 200 * 1024 * 1024,
        fileSingleSizeLimit: 30 * 1024 * 1024
    });
    if (fileCount >= MaxCount) {
        $(uploader.options.pick.id).hide()
    }
    else {
        $(uploader.options.pick.id).show()
    }
    $("#uploader .file-panel").on('click', 'span', function () {
        var $this = this;
        var img = $(this).parent().parent().find("input[name='photoUrl']").val();
        $($this).parent().parent().find('.file-panel').off().end().remove();
        fileCount = $('#uploader .filelist li').length;
        if (fileCount >= MaxCount) {
            $(uploader.options.pick.id).hide();
        } else {
            $(uploader.options.pick.id).show();
        }
       /* $.ajax({
            url: "/del.ashx",
            type: 'post',
            dataType: 'text',
            data: { Path: encodeURIComponent(img) },
            async: false,
            success: function (resultData) {
                if (resultData == "true") {
             
               
                } else {
                    alert('亲，图片删除失败，请稍后再试！');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });*/
    });
    function addFile(file) {
        fileCount = $('#uploader .filelist li').length;
        if (fileCount >= MaxCount) {
            alert('亲，您最多只能传' + MaxCount + '张图片！');
            $(uploader.options.pick.id).hide();
        }
        else {
            $(uploader.options.pick.id).show();

            var $li = $('<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>' +
                    '<p class="progress"><span></span></p>' +
                    '</li>'),
                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span></div>').appendTo($li),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find('p.imgWrap'),
                showError = function (code) {
                    switch (code) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;
                        case 'interrupt':
                            text = '上传暂停';
                            break;
                        default:
                            text = '上传失败，请重试';
                            break;
                    }
                };
            if (file.getStatus() === 'invalid') {
                showError(file.statusText);
            } else {
                $wrap.text('预览中');
                uploader.makeThumb(file, function (error, src) {
                    if (error) {
                        $wrap.text('不能预览');
                        return;
                    }
                    var img = $('<img src="' + src + '">');
                    $wrap.empty().append(img);
                }, thumbnailWidth, thumbnailHeight);
                percentages[file.id] = [file.size, 0];
                file.rotation = 0;
            }
            file.on('statuschange', function (cur, prev) {
                if (prev === 'progress') {
                    $prgress.hide().width(0);
                }
                if (cur === 'error' || cur === 'invalid') {
                    console.log(file.statusText);
                    showError(file.statusText);
                    percentages[file.id][1] = 1;
                } else if (cur === 'interrupt') {
                    showError('interrupt');
                } else if (cur === 'queued') {
                    percentages[file.id][1] = 0;
                } else if (cur === 'progress') {
                    $prgress.css('display', 'block');
                } else if (cur === 'complete') {
                    $li.append('<span class="success"></span>');

                }
                $li.removeClass('state-' + prev).addClass('state-' + cur);
            });
            $btns.on('click', 'span', function () {
                uploader.removeFile(file);
            });
            $li.appendTo($queue);
            fileCount = $('#uploader .filelist li').length;
            if (fileCount >= MaxCount) {
                $(uploader.options.pick.id).hide();
            }
        }
    }
    function removeFile(file) {
        var $li = $('#' + file.id);
        delete percentages[file.id];
        $li.off().find('.file-panel').off().end().remove();
        fileCount = $('#uploader .filelist li').length
        if (fileCount >= MaxCount) {
            $(uploader.options.pick.id).hide()
        } else {
            $(uploader.options.pick.id).show()
        }
    }
    function setState(val) {
        var file, stats;
        if (val === state) {
            return;
        }
        state = val;
        switch (state) {
            case 'pedding':
                $queue.parent().removeClass('filled');
                $queue.hide();
                uploader.refresh();
                break;
            case 'ready':
                $queue.parent().addClass('filled');
                $queue.show();
                uploader.refresh();
                break;
            case 'uploading':
                break;
            case 'paused':
                break;
            case 'confirm':
                stats = uploader.getStats();
                if (stats.successNum && !stats.uploadFailNum) {
                    setState('finish');
                    return;
                }
                break;
            case 'finish':
                stats = uploader.getStats();
                if (!stats.successNum) {
                    state = 'done';
                }
                break;
        }
    }
     
    uploader.onUploadProgress = function (file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('.progress span');
        $percent.css('width', percentage * 100 + '%');
        percentages[file.id][1] = percentage;
    };
    uploader.onFileQueued = function (file) {
        fileCount = $('#uploader .filelist li').length;
        if (fileCount > MaxCount) {
            $(uploader.options.pick.id).hide();
        }
        else {
            $(uploader.options.pick.id).show();
        }
        fileSize += file.size;
        addFile(file);
        setState('ready');
    };
    uploader.onFileDequeued = function (file) {
        fileCount = $('#uploader .filelist li').length;
        //alert(fileCount + "*" + MaxCount);
        if (fileCount > MaxCount) {
            $(uploader.options.pick.id).hide();
        } else {
            $(uploader.options.pick.id).show();
        }
        fileSize -= file.size;
        if (!fileCount) {
            setState('pedding');
        }
        removeFile(file);
    };
    uploader.onUploadSuccess = function (file, response) {
        $("#" + file.id).append($('<span class="item_input"><textarea name="imagestexts" class="bewrite" cols="13" rows="2" style="resize: none" data-rule-maxlength="150" placeholder="作品说明"></textarea></span>'));
        $("#" + file.id).append($('<input type="hidden" value="' + response.message + '"  name="photoUrl" />'));
    };
    uploader.on('all', function (type) {
        var stats;
        switch (type) {
            case 'uploadFinished':
                setState('confirm');
                break;
            case 'startUpload':
                setState('uploading');
                break;
            case 'stopUpload':
                setState('paused');
                break;
        }
    });
    uploader.onError = function (code) {
        switch (code) {
            case 'F_DUPLICATE':
                alert('亲，图片已经添加请不要重复添加哦！');
                break;
            case 'Q_EXCEED_NUM_LIMIT':
                alert('亲，您最多只能传' + MaxCount + '张图片！');
                $(uploader.options.pick.id).hide();
                break;
            default:
                alert('Eroor: ' + code);
                break;
        }
    };
});
