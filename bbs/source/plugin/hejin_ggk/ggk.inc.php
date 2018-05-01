<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/hejin_ggk/config.inc.php';
$model = addslashes($_GET['model']);

if(submitcheck('add_ggk')){
	$ggkdata = array();
	if($_POST['title']){
		$ggkdata['title'] = addslashes($_POST['title']);
	}
	if($_FILES['icon']){
		if($_FILES['icon']['error']==0){
			$ggkdata['icon'] = uploads('icon','upload');
		}
	}
	if($_POST['guize']){
		$ggkdata['guize'] = addslashes($_POST['guize']);
	}
	if($_POST['wxurl']){
		$ggkdata['wxurl'] = addslashes($_POST['wxurl']);
	}
	$ggkdata['password'] = intval($_POST['password']);
	$ggkdata['daynub'] = intval($_POST['daynub']);
	$ggkdata['jilv'] = intval($_POST['jilv']);
	$ggkdata['jifen'] = intval($_POST['jifen']);
	if($_POST['start_time']){
		$start_time = addslashes($_POST['start_time']);
		$ggkdata['start_time'] = strtotime($start_time);
	}
	if($_POST['end_time']){
		$end_time = addslashes($_POST['end_time']);
		$ggkdata['end_time'] = strtotime($end_time);
	}
	$ggkdata['add_time'] = time();
	$ggkadd = C::t('#hejin_ggk#hjggk_ggks')->insert($ggkdata);
	if($ggkadd){
		$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk';
		cpmsg(lang('plugin/hejin_ggk', 'addstok'), $url, 'succeed');	
	}
}


if(submitcheck('edit_ggk')){
	$gid = intval($_POST['gid']);
	if($gid){
		$ggkdata = array();
		if($_POST['title']){
			$ggkdata['title'] = addslashes($_POST['title']);
		}
		if($_FILES['icon']){
			if($_FILES['icon']['error']==0){
				$ggkdata['icon'] = uploads('icon','upload');
			}
		}
			$ggkdata['guize'] = addslashes($_POST['guize']);
		if($_POST['wxurl']){
			$ggkdata['wxurl'] = addslashes($_POST['wxurl']);
		}
		$ggkdata['password'] = intval($_POST['password']);
		$ggkdata['daynub'] = intval($_POST['daynub']);
		$ggkdata['jilv'] = intval($_POST['jilv']);
		$ggkdata['jifen'] = intval($_POST['jifen']);
		if($_POST['start_time']){
			$start_time = addslashes($_POST['start_time']);
			$ggkdata['start_time'] = strtotime($start_time);
		}
		if($_POST['end_time']){
			$end_time = addslashes($_POST['end_time']);
			$ggkdata['end_time'] = strtotime($end_time);
		}
		$ggkedit = C::t('#hejin_ggk#hjggk_ggks')->update_by_id($gid,$ggkdata);
		if($ggkedit){
			$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk';
			cpmsg(lang('plugin/hejin_ggk', 'editstok'), $url, 'succeed');	
		}
	}
}




//添加刮刮卡奖项处理ok
if(submitcheck('add_js')){
	$gid = intval($_POST['gid']);
	if($gid){
		$jiadata = array();
		if($_POST['title']){
			$jiadata['title'] = addslashes($_POST['title']);
		}
		$jiadata['gid'] = $gid;
		if($_POST['number']){
			$jiadata['number'] = intval($_POST['number']);
		}
		if($_FILES['pic']['error']==0){
			$jiadata['pic'] = uploads('pic','upload');
		}
		if($_POST['jpname']){
			$jiadata['jpname'] = addslashes($_POST['jpname']);
		}
		if($_POST['sjname']){
			$jiadata['sjname'] = addslashes($_POST['sjname']);
		}
		if($_POST['wxurl']){
			$jiadata['wxurl'] = addslashes($_POST['wxurl']);
		}
		if($_POST['sjhdurl']){
			$jiadata['sjhdurl'] = addslashes($_POST['sjhdurl']);
		}
		if($_POST['telphone']){
			$jiadata['telphone'] = addslashes($_POST['telphone']);
		}
		if($_FILES['sjpic']){
			if($_FILES['sjpic']['error']==0){
				$jiadata['sjpic'] = uploads('sjpic','upload');
			}
		}
		if($_POST['address']){
			$jiadata['address'] = addslashes($_POST['address']);
		}
		if($_POST['descrip']){
			$jiadata['descrip'] = addslashes($_POST['descrip']);
		}
		if($_POST['start_us']){
			$jiadata['start_us'] = intval($_POST['start_us']);
		}
		$jiaadd = C::t('#hejin_ggk#hjggk_jiangs')->insert($jiadata);
		if($jiaadd){
			$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&model=jiangs&gid='.$gid;
			cpmsg(lang('plugin/hejin_ggk', 'addstok'), $url, 'succeed');	
		}
		
	}
}


//修改刮刮卡奖项处理ok
if(submitcheck('edit_js')){
	$gid = intval($_POST['gid']);
	$jid = intval($_POST['jid']);
	if($jid){
		$jiadata = array();
		if($_POST['title']){
			$jiadata['title'] = addslashes($_POST['title']);
		}
		if($_POST['number']){
			$jiadata['number'] = intval($_POST['number']);
		}
		if($_FILES['pic']['error']==0){
			$jiadata['pic'] = uploads('pic','upload');
		}
			$jiadata['jpname'] = addslashes($_POST['jpname']);
			$jiadata['sjname'] = addslashes($_POST['sjname']);
			$jiadata['wxurl'] = addslashes($_POST['wxurl']);
			$jiadata['sjhdurl'] = addslashes($_POST['sjhdurl']);
			$jiadata['telphone'] = addslashes($_POST['telphone']);
		if($_FILES['sjpic']){
			if($_FILES['sjpic']['error']==0){
				$jiadata['sjpic'] = uploads('sjpic','upload');
			}
		}
			$jiadata['address'] = addslashes($_POST['address']);
			$jiadata['descrip'] = addslashes($_POST['descrip']);
		if($_POST['start_us']){
			$jiadata['start_us'] = intval($_POST['start_us']);
		}else{
			$jiadata['start_us'] = 0;
		}
		$jiaadd = C::t('#hejin_ggk#hjggk_jiangs')->update_by_id($jid,$jiadata);
		if($jiaadd){
			$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&model=jiangs&gid='.$gid;
			cpmsg(lang('plugin/hejin_ggk', 'editok'), $url, 'succeed');	
		}
		
	}
}




//投票活动列表
if(empty($model)){
	include_once ("page.class.php");
	$page=$_GET['page'];
	$stlist = C::t('#hejin_ggk#hjggk_ggks')->fetch_all();
	$totail = count($stlist);
	$number = 20;
	$url = $SELF.'?action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&page={page}';
	$my_page=new PageClass($totail,$number,$page,$url);//参数设定：总记录，每页显示的条数，当前页，连接的地址
	$startnum = $my_page->page_limit;
	$count = $my_page->myde_size;


	$stlists = C::t('#hejin_ggk#hjggk_ggks')->fetch_limit($startnum,$count);

	$page_string = $my_page->myde_write();

	include template('hejin_ggk:admin/ggklist');
}


//添加刮刮卡活动
elseif($model == 'addggk'){
	include template('hejin_ggk:admin/addggk');
}

//修改刮刮卡活动
elseif($model == 'edit'){
	$gid = intval($_GET['gid']);
	if($gid){
		$ggk =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
		include template('hejin_ggk:admin/editggk');
	}
}

//删除刮刮卡活动
elseif($model == 'del'){
	if($_GET['formhash']==formhash()){
		$gid = intval($_GET['gid']);
		if($gid){
			$gdel =  C::t('#hejin_ggk#hjggk_ggks')->delete_by_id($gid);
			if($gdel){
				$jdel = C::t('#hejin_ggk#hjggk_jiangs')->delete_by_gid($gid);//删除奖品
				$zdel = C::t('#hejin_ggk#hjggk_zjjls')->delete_by_gid($gid);//删除中奖纪录
				$gjjldel = C::t('#hejin_ggk#hjggk_gkjilus')->delete_by_gid($gid);//删除中奖纪录
				
				$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk';
				cpmsg(lang('plugin/hejin_ggk', 'delcg'), $url, 'succeed');	
			}
		}
	}
}



//刮刮卡奖项管理
elseif($model == 'jiangs'){
	$gid = intval($_GET['gid']);
	if($gid){
		$ggk =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
		$jianges =  C::t('#hejin_ggk#hjggk_jiangs')->fetch_all_gid($gid);
		include template('hejin_ggk:admin/jianglist');
	}
}

//添加刮刮卡奖项
elseif($model == 'addjs'){
	$gid = intval($_GET['gid']);
	if($gid){
		$ggkjall = C::t('#hejin_ggk#hjggk_jiangs')->fetch_all_gid($gid);
		if(count($ggkjall)<8){
			$voteinfo =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
			include template('hejin_ggk:admin/addjs');
		}else{
			$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&model=jiangs&gid='.$gid;
			cpmsg(lang('plugin/hejin_ggk', 'ggkjxzd'), $url, 'error');	
		}
	}
}

//修改刮刮卡奖项
elseif($model == 'editjs'){
	$jid = intval($_GET['jid']);
	if($jid){
		$jinfo =  C::t('#hejin_ggk#hjggk_jiangs')->fetch_by_id($jid);
		include template('hejin_ggk:admin/editjs');
	}
}

//删除刮刮卡奖项
elseif($model == 'deljs'){
	if($_GET['formhash']==formhash()){
		$jid = intval($_GET['jid']);
		$gid = intval($_GET['gid']);
		if($jid){
			$jdel =  C::t('#hejin_ggk#hjggk_jiangs')->delete_by_id($jid);
			if($jdel){
				$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&model=jiangs&gid='.$gid;
				cpmsg(lang('plugin/hejin_ggk', 'delcg'), $url, 'succeed');	
			}
		}
	}
}


//刮刮卡中奖纪录
elseif($model == 'zhongj'){
	$gid = intval($_GET['gid']);
	if($gid){
		$voteinfo =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
		$jianges =  C::t('#hejin_ggk#hjggk_zjjls')->fetch_all_gid($gid);
		include template('hejin_ggk:admin/ggkzj');
	}
}


//领取奖品确认
elseif($model == 'qurenlq'){
	if($_GET['formhash']==formhash()){
		$zid = intval($_GET['zid']);
		$gid = intval($_GET['gid']);
		if($zid){
			$lqqrdata = array(
				'is_lq'=>1,
			);
			$lqqr =  C::t('#hejin_ggk#hjggk_zjjls')->update_by_id($zid,$lqqrdata);
			if($lqqr){
				$url = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=hejin_ggk&pmod=ggk&model=zhongj&gid='.$gid;
				cpmsg(lang('plugin/hejin_ggk', 'bjlqok'), $url, 'succeed');	
			}
		}
	}
}



function uploads($postname,$dir){
	$tempFile = $_FILES[$postname]['tmp_name'];
    $fileTypes = array('jpg','jpeg','gif','png');
 	$fileParts = pathinfo($_FILES[$postname]['name']);
	$extension = strtolower($fileParts['extension']);
	$name   = date('mdHis').'-'.rand(100,999).'.'.$extension;
    $targetFolder = HEJIN_ROOT.'/'.$dir;
 	if(!is_dir($targetFolder)){mkdir($targetFolder,0777,TRUE);}
	@chmod($targetFolder,0777); 
	$loca   = $targetFolder.'/'.$name;
 	if (in_array($extension,$fileTypes)) {
		if(copy($tempFile,$loca)){
		   return $dir.'/'.$name;
		}
	}else{
		showmessage(lang('plugin/hejin_ggk', 'picgsbd'),'');
			   
	}
}

?>