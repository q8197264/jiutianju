<?php
/*
 * 出处：草根吧
 * 官网: Www.Caogen8.Co
 * 备用网址: www.Cgzz8.com (请收藏备用!)
 * 本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 * 技术支持/更新维护：QQ 2575163778
 * 
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function tomoutput(){
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $tom_link_rule = include DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php";
        if(isset($tom_link_rule['tom_kanjia'])){
            $content = ob_get_contents();
            $content = str_replace("plugin.php?id=tom_kanjia", $tom_link_rule['tom_kanjia']['rk']."?id=".$tom_link_rule['tom_kanjia']['bs'], $content);
            ob_end_clean();
            $_G['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
            echo $content;
        }
    }
    exit;
}

function tomheader($string){
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $tom_link_rule = include DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php";
        if(isset($tom_link_rule['tom_kanjia'])){
            $string = str_replace("plugin.php?id=tom_kanjia", $tom_link_rule['tom_kanjia']['rk']."?id=".$tom_link_rule['tom_kanjia']['bs'], $string);
        }
    }
    dheader($string);
    
    return;
}

function tom_link_replace($string){
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $tom_link_rule = include DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php";
        if(isset($tom_link_rule['tom_kanjia'])){
            $string = str_replace("plugin.php?id=tom_kanjia", $tom_link_rule['tom_kanjia']['rk']."?id=".$tom_link_rule['tom_kanjia']['bs'], $string);
        }
    }
    return $string;
}

