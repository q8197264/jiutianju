<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$title = '';
$content = '';
if($_GET['aid'] == 1){
    
    $title = lang('plugin/tom_love','aboutus_title');
    require_once libfile('function/discuzcode');
    $content = discuzcode($jyConfig['love_aboutus'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
}else if($_GET['aid'] == 2){
    
    $title = lang('plugin/tom_love','xieyi_title');
    require_once libfile('function/discuzcode');
    $content = discuzcode($jyConfig['love_xieyi'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
}else if($_GET['aid'] == 3){
    
    $title = lang('plugin/tom_love','gonggao_title');
    require_once libfile('function/discuzcode');
    $content = discuzcode($jyConfig['index_gonggao_msg'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
}



$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_love:article");
