<?php
/*
 * �������ݸ���
 * ����: Www.Caogen8.Co
 * ������ַ: www.Cgzz8.com (���ղر���!)
 * ����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 * ����֧��/����ά����QQ 2575163778
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

