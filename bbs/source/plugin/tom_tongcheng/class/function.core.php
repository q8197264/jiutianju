<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


function wx_iconv_recurrence($value) {
    if(is_array($value)) {
        foreach($value AS $key => $val) {
            $value[$key] = wx_iconv_recurrence($val);
        }
    } else {
        $value = diconv($value, 'utf-8', CHARSET);
    }
    return $value;
}

function contentFormat($content){
    
    $content = dhtmlspecialchars($content);
    
    $pos = strpos($content,"|+|+|+|+|+|+|+|+|+|");
    if($pos === false){
        return $content;
    }else if($pos === 0){
        return "";
    }else{
        return substr($content, 0, $pos);
    }
    
}
