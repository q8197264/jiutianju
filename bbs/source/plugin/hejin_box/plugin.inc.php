<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$plugindata = $_G['cache']['plugin']['hejin_box'];
$model = addslashes($_GET['model']);
if(empty($model)){
	echo json_encode($plugindata);
}

elseif($model=='menulist'){
	$leixin = array(
			1=>'view',
			2=>'click',
			3=>'scancode_push',
			4=>'pic_photo_or_album',
			5=>'location_select',
	);
	$zhudhs = C::t('#hejin_box#hjbox_buttons')->fetch_azhu_all();
	$zhunub = count($zhudhs);
	$dharray = '{"button":[';
	foreach($zhudhs as $key=>$zhudh){
		$fendhs = C::t('#hejin_box#hjbox_buttons')->fetch_afen_sid(intval($zhudh['id']));
		if(count($fendhs)){
			$dharray.='{"name":"'.g2u($zhudh['title']).'","sub_button":[';
			foreach($fendhs as $keys=>$fendh){
				if($fendh['type']==1){
					$ftype='url';
				}else{
					$ftype='key';
				}
				if($keys==count($fendhs)-1){
					$dharray.='{"type":"'.$leixin[$fendh['type']].'","name":"'.g2u($fendh['title']).'","'.$ftype.'":"'.g2u($fendh['content']).'"}';
				}else{
					$dharray.='{"type":"'.$leixin[$fendh['type']].'","name":"'.g2u($fendh['title']).'","'.$ftype.'":"'.g2u($fendh['content']).'"},';
				}
			}
			if($key==$zhunub-1){
				$dharray.=']}';
			}else{
				$dharray.=']},';
			}
			
		}else{
			if($zhudh['type']==1){
				$type='url';
			}else{
				$type='key';
			}
			if($key==$zhunub-1){
				$dharray.='{"type":"'.$leixin[$zhudh['type']].'","name":"'.g2u($zhudh['title']).'","'.$type.'":"'.g2u($zhudh['content']).'"}';
			}else{
				$dharray.='{"type":"'.$leixin[$zhudh['type']].'","name":"'.g2u($zhudh['title']).'","'.$type.'":"'.g2u($zhudh['content']).'"},';
			}
		}
	}
	$dharray.=']}';
	echo $dharray;
}


function u2g($a) {
        return is_array($a) ? array_map('u2g', $a) : diconv($a, 'UTF-8', CHARSET);
}
function g2u($a) {
        return is_array($a) ? array_map('g2u', $a) : diconv($a, CHARSET, 'UTF-8');
}


?>