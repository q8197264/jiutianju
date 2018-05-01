<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/config.inc.php';
require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/hj_function.php';
$model = addslashes($_GET['model']);

if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
	include_once('./source/plugin/hejin_toupiao/TieTuKu.class.php');
	define('MY_ACCESSKEY', $hejintoupiao['hjtp_accessk']);
	define('MY_SECRETKEY', $hejintoupiao['hjtp_secretkey']);
	$tcxcid = $hejintoupiao['hjtp_tcxcid'];
}
$symodel = $hejintoupiao['hjtp_symodel']?$hejintoupiao['hjtp_symodel']:'votea';
$phmodel = $hejintoupiao['hjtp_phmodel']?$hejintoupiao['hjtp_phmodel']:'rank';
$topmodel = $hejintoupiao['hjtp_topmodel']?$hejintoupiao['hjtp_topmodel']:'top300';
$xqmodel = $hejintoupiao['hjtp_xqmodel']?$hejintoupiao['hjtp_xqmodel']:'detail';


if(submitcheck('signup')){
	if($_POST['vid']){
		$vid = intval($_POST['vid']);
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id($vid);
		$ishave = count($vote);
		if($ishave){
			if($vote['start_time']<time() && $vote['over_time']>time()){
				if($_COOKIE['hjbox_openid']){
					$openid = addslashes($_COOKIE['hjbox_openid']);
					$user =  C::t('#hejin_toupiao#hjbox_users')->fetch_by_openid($openid);
					if($user){
						if($user['is_gz']==1){
							$zuopinhs = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_uid_vid(intval($user['id']),$vid);
							if(count($zuopinhs)){
								header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopinhs['id']."");
							}else{
								$addzp = array();
								$addzp['vid'] = $vid;
								$addzp['uid'] = intval($user['id']);
								$addzp['openid'] = $openid;
								$addzp['zpname'] = addslashes(strip_tags($_POST['zpname']));
								$addzp['telphone'] = addslashes($_POST['telphone']);
								$addzp['content'] = addslashes(strip_tags($_POST['content']));
								if($vote['is_sh']==1){
									$addzp['is_show'] = 0;
								}
								if(!empty($_POST['fileup'])){
									foreach($_POST['fileup'] as $key=>$file){
										if($key==0){
											$bdpica = savepic($file);
											if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
												$addzp['pica'] = tcupload($bdpica,$tcxcid);
											}else{
												$addzp['pica'] = $bdpica;
											}
											
										}
										if($key==1){
											$bdpicb = savepic($file);
											if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
												$addzp['picb'] = tcupload($bdpicb,$tcxcid);
											}else{
												$addzp['picb'] = $bdpicb;
											}
										}
										if($key==2){
											$bdpicc = savepic($file);
											if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
												$addzp['picc'] = tcupload($bdpicc,$tcxcid);
											}else{
												$addzp['picc'] = $bdpicc;
											}
										}
										if($key==3){
											$bdpicd = savepic($file);
											if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
												$addzp['picd'] = tcupload($bdpicd,$tcxcid);
											}else{
												$addzp['picd'] = $bdpicd;
											}
										}
										if($key==4){
											$bdpice = savepic($file);
											if($hejintoupiao['hjtp_tuchuang'] && $hejintoupiao['hjtp_accessk'] && $hejintoupiao['hjtp_secretkey'] && $hejintoupiao['hjtp_tcxcid']){
												$addzp['pice'] = tcupload($bdpice,$tcxcid);
											}else{
												$addzp['pice'] = $bdpice;
											}
										}
									}
								}
								$zuopinadd =  C::t('#hejin_toupiao#hjtp_zuopins')->insert($addzp);
								if($zuopinadd){
									$upvidzps = array(
										'zuopins' => intval($vote['zuopins']+1),
									);
									$upvzpsa =  C::t('#hejin_toupiao#hjtp_votes')->update_by_id($vid,$upvidzps);
									if(!$user['telphone']){
										$userup = array(
											'telphone' =>addslashes($_POST['telphone']),
										);
										$upuser =  C::t('#hejin_toupiao#hjbox_users')->update_by_id(intval($user['id']),$userup);
									}
									header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopinadd."");
								}else{
									header("Location: ".HEJIN_URL."&model=signup&vid=".$vid."");
								}
							}
						}
					}
				}
			}
		}
	}
}




//搜索
/*if(submitcheck('seachid')){
	$vid = intval($_POST['vid']);
	if($_POST['keyword']){
		if(is_numeric($_POST['keyword'])){
			$zid = intval(intval($_POST['keyword'])-$vid*10000);
			$zphave = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_zvid($zid,$vid);
			if(count($zphave)){
				header("Location: ".HEJIN_URL."&model=detail&zid=".$zid."");
			}
		}else{
			$keyword = addslashes($_POST['keyword']);
			$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_key_all_vid($vid,$keyword);
			$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
			if($_COOKIE['hjbox_openid']){
				$yopenid = addslashes($_COOKIE['hjbox_openid']);
				$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
				if(count($havezp)){
					$ishavezp = 1;
				}
			}
			if($vote['yuliua']==2){
				include template('hejin_toupiao:index2/search');
			}else{
				include template('hejin_toupiao:index/search');
			}
		}
	}
}*/



//模版二搜索
if(submitcheck('seachid')){
	$vid = intval($_POST['vid']);
	if($_POST['keyword']){
		if(is_numeric($_POST['keyword'])){
			if($hejintoupiao['hjtp_numbtp']){
				$zid = intval($_POST['keyword']);
			}else{
				$zid = intval(intval($_POST['keyword'])-$vid*10000);
			}
			
			$zphave = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_isshow_zvid($zid,$vid);
			if(count($zphave)){
				header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$zid."");
			}
		}else{
			$keyword = addslashes($_POST['keyword']);
			header("Location: ".HEJIN_URL."&model=searchkey&vid=".$vid."&key=".$keyword."");
		}
	}
}

//oauth接口
if(empty($model)){
	if (isset($_GET['code'])){
		if(isset($_GET['state'])){
			$pid = intval($_GET['state']);
    		$data = getfirst($jzappid, $jzappsecret, $_GET['code']);
			$openid = addslashes($data['openid']);
			if($openid){
				$user = C::t('#hejin_box#hjbox_users')->fetch_by_openid($openid);
				if(count($user)){
					setcookie('hjbox_openid', $openid, time()+31536000);
					if($_COOKIE['hjtp_tzdetail']==1){
						header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$pid);
					}elseif($_COOKIE['hjtp_tzdetail']==2){
						header("Location: ".HEJIN_URL."&model=".$phmodel."&vid=".$pid);
					}else{
						header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$pid);
					}
					exit;
				}else{
					$adduserdata = array();
					$adduserdata['openid']= addslashes($openid);
					$adduserdata['gztime']= time();
					$adduserdata['yuliuc']= 1;
					$adduser = C::t('#hejin_box#hjbox_users')->insert($adduserdata);
					if($adduser){
						setcookie('hjbox_openid', $openid, time()+31536000);
						if($_COOKIE['hjtp_tzdetail']==1){
							header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$pid);
						}elseif($_COOKIE['hjtp_tzdetail']==2){
							header("Location: ".HEJIN_URL."&model=".$phmodel."&vid=".$pid);
						}else{
							header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$pid);
						}
						exit;
					}
				}
			}else{
				echo lang('plugin/hejin_toupiao', 'wxsqurlcw');
				exit;
			}
		}
	}
}




//投票活动列表页
if($model == 'vote'){
	$vid = intval($_GET['vid']);
	if(!$_COOKIE['hjbox_openid']){
		if($_GET['openid']){
			$openid = addslashes($_GET['openid']);
			setcookie('hjbox_openid', $openid, time()+31536000);
			header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$vid."");
			exit;
		}else{
			header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$vid."");
			exit;
		}
	}else{
		if($_GET['openid']){
			if($_GET['openid']!=$_COOKIE['hjbox_openid']){
				$openid = addslashes($_GET['openid']);
				setcookie('hjbox_openid', $openid, time()+31536000);
			}
			header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$vid."");
			exit;
		}else{
			header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$vid."");
			exit;
		}
	}
}



//投票活动列表页
else if($model == 'index'){
	$vid = intval($_GET['vid']);
	if($vid){
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id($vid);
		$ishave = count($vote);
		if($ishave){
			$supply =  C::t('#hejin_toupiao#hjtp_supply')->fetch_by_vid($vid);
			if($_COOKIE['hjbox_openid']){
				$yopenid = addslashes($_COOKIE['hjbox_openid']);
				$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
				if(count($havezp)){
					$ishavezp = 1;
				}
			}
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
			
			include_once ("page.class.php");
			$page=$_GET['page'];
			$zuopines =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_all_vid($vid);
			$totail = count($zuopines);
			$number = 20;
			$url = HEJIN_URL.'&model=index&vid='.$vid.'&page={page}';
			$my_page=new PageClass($totail,$number,$page,$url);//参数设定：总记录，每页显示的条数，当前页，连接的地址
			$startnum = $my_page->page_limit;
			$count = $my_page->myde_size;
			if($supply){
				if($supply['paixu']==1){
					$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_psp_vid($vid,$startnum,$count);
				}elseif($supply['paixu']==2){
					$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_limit_vid($vid,$startnum,$count);
				}elseif($supply['paixu']==3){
					$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_bmdx_vid($vid,$startnum,$count);
				}
			}else{
				$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_psp_vid($vid,$startnum,$count);
			}
			$page_string = $my_page->myde_indexpage();
			include template('hejin_toupiao:index/pcindex');

		}else{
			$ishavevote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
			if(count($ishavevote)){
				echo lang('plugin/hejin_toupiao', 'ceshiyh');
			}
		}
	}
}



//投票活动列表页
else if($model == $symodel){
	$vid = intval($_GET['vid']);
	if($vid){
	if((!$_COOKIE['hjbox_openid']) && $hejintoupiao['hjtp_bgztp'] && $jzappid && $jzappsecret){
		header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$jzappid."&redirect_uri=".HEJIN_URL."&response_type=code&scope=snsapi_base&state=".$vid);
		exit;
	}else{
	
		
		
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id($vid);
		$ishave = count($vote);
		if($ishave){
			if($_COOKIE['hjbox_openid']){
				$yopenid = addslashes($_COOKIE['hjbox_openid']);
				$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
				if(count($havezp)){
					$ishavezp = 1;
				}
			}
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
			
			include_once ("page.class.php");
			$page=$_GET['page'];
			$zuopines =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_all_vid($vid);
			$totail = count($zuopines);
			$number = 20;
			$url = HEJIN_URL.'&model='.$symodel.'&vid='.$vid.'&page={page}';
			$my_page=new PageClass($totail,$number,$page,$url);//参数设定：总记录，每页显示的条数，当前页，连接的地址
			$startnum = $my_page->page_limit;
			$count = $my_page->myde_size;
			$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ys_limit_vid($vid,$startnum,$count);
			if($vote['yuliua']==2){
				$page_string = $my_page->myde_writewxa();
			}else{
				$page_string = $my_page->myde_writewx();
			}
			
			if($hejintoupiao['hjtp_jssdk'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']){
				require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/jssdk.php';
				$jssdk = new JSSDK($hejinbox['hjbox_appid'], $hejinbox['hjbox_appsecret']);
				$signPackage = $jssdk->GetSignPackage();
			}
			if($_GET['info']==1){
				echo '<pre>';
				print_r($vote);
				echo '</pre>';
			}else{
				if($vote['yuliua']==2){
					include template('hejin_toupiao:index2/vote');
				}else{
					include template('hejin_toupiao:index/vote');
				}
			}

		}else{
			$ishavevote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
			if(count($ishavevote)){
				echo lang('plugin/hejin_toupiao', 'ceshiyh');
			}
		}
	}
	}
}

//投票排行
else if($model == $phmodel){
	$vid = intval($_GET['vid']);
	/*if(!$_COOKIE['hjbox_openid']){
		if($_GET['openid']){
			$openid = addslashes($_GET['openid']);
			setcookie('hjbox_openid', $openid, time()+31536000);
			header("Location: ".HEJIN_URL."&model=".$phmodel."&vid=".$vid."");
		}
	}else{
		if($_GET['openid']){
			if($_GET['openid']!=$_COOKIE['hjbox_openid']){
				$openid = addslashes($_GET['openid']);
				setcookie('hjbox_openid', $openid, time()+31536000);
			}
			header("Location: ".HEJIN_URL."&model=".$phmodel."&vid=".$vid."");
		}
	}*/
	if($vid){
	if((!$_COOKIE['hjbox_openid']) && $hejintoupiao['hjtp_bgztp'] && $jzappid && $jzappsecret){
		header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$jzappid."&redirect_uri=".HEJIN_URL."&response_type=code&scope=snsapi_base&state=".$zid);
		setcookie('hjtp_tzdetail', 2, time()+60);
		exit;
	}else{

		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id($vid);
		$ishave = count($vote);
		if($ishave){
			if($_COOKIE['hjbox_openid']){
				$yopenid = addslashes($_COOKIE['hjbox_openid']);
				$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
				if(count($havezp)){
					$ishavezp = 1;
				}
			}
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
			include_once ("page.class.php");
			$page=$_GET['page'];
			if($_GET['page']){
				$pagenub = $_GET['page'];
			}else{
				$pagenub = 1;
			}
			$zuopines =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ysa_all_vid($vid);
			$totail = count($zuopines);
			if($vote['yuliua']==2){
				$number = 50;
			}else{
				$number = 20;
			}
			
			$url = HEJIN_URL.'&model='.$phmodel.'&vid='.$vid.'&page={page}';
			$my_page=new PageClass($totail,$number,$page,$url);//参数设定：总记录，每页显示的条数，当前页，连接的地址
			$startnum = $my_page->page_limit;
			$count = $my_page->myde_size;
			$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_ysa_limit_vid($vid,$startnum,$count);
			if($vote['yuliua']==2){
				$page_string = $my_page->myde_writewxa();
			}else{
				$page_string = $my_page->myde_writewx();
			}
			if($hejintoupiao['hjtp_jssdk'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']){
				require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/jssdk.php';
				$jssdk = new JSSDK($hejinbox['hjbox_appid'], $hejinbox['hjbox_appsecret']);
				$signPackage = $jssdk->GetSignPackage();
			}
			if($vote['yuliua']==2){
				include template('hejin_toupiao:index2/rank');
			}else{
				include template('hejin_toupiao:index/rank');
			}
				
		}
	}
	}
}


else if($model == $topmodel){
	$vid = intval($_GET['vid']);
	if($vid && !$_GET['openid']){
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id($vid);
		$ishave = count($vote);
		if($ishave){
			if($_COOKIE['hjbox_openid']){
				$yopenid = addslashes($_COOKIE['hjbox_openid']);
				$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
				if(count($havezp)){
					$ishavezp = 1;
				}
			}
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
			$topnumber = intval($hejintoupiao['hjtp_topnub']);
			$phlist = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_phb_vid($vid,$topnumber);
			if($vote['yuliua']!=2){
				include template('hejin_toupiao:index/top');
			}
		}
	}
}



//投票作品详情页
else if($model == $xqmodel){
	if($_GET['zid']){
		$zid = intval($_GET['zid']);
		
	if((!$_COOKIE['hjbox_openid']) && $hejintoupiao['hjtp_bgztp'] && $jzappid && $jzappsecret){
		header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$jzappid."&redirect_uri=".HEJIN_URL."&response_type=code&scope=snsapi_base&state=".$zid);
		setcookie('hjtp_tzdetail', 1, time()+60);
		exit;
	}else{
		
		
		$zpinfo = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_id($zid);
		if(count($zpinfo)){
			if($_COOKIE['hjbox_openid']){
				if($_COOKIE['hjbox_openid']==$zpinfo['openid']){
					$myself = 1;
				}else{
					$myself = 0;
				}
				
				$zuopinhs = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid(addslashes($_COOKIE['hjbox_openid']),intval($zpinfo['vid']));
				if(count($zuopinhs)){
					$havezp = 1;
				}else{
					$havezp = 0;
				}
			}else{
				$havezp = 0;
				$myself = 0;
			}
			if($hejintoupiao['hjtp_detailno']){
				$upszuopin = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_uptps_vid(intval($zpinfo['vid']),intval($zpinfo['toupiaos']));
				if(count($upszuopin)){
					foreach($upszuopin as $key=>$zpzt){
						if($zpzt['id']==$zid){
							$zpnumber = $key+1;
						}	
					}
				}
				if($zpnumber!=1){
					$shangym = $zpnumber-2;
					$xiangcps = $upszuopin[$shangym]['toupiaos']-$zpinfo['toupiaos']+1;
				}
			}
			
			$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_bya_id(intval($zpinfo['vid']));
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
			if($hejintoupiao['hjtp_jssdk'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']){
				require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/jssdk.php';
				$jssdk = new JSSDK($hejinbox['hjbox_appid'], $hejinbox['hjbox_appsecret']);
				$signPackage = $jssdk->GetSignPackage();
			}
			if($vote['yuliua']==2){
				include template('hejin_toupiao:index2/detail');
			}else{
				include template('hejin_toupiao:index/detail');
			}
			

					
		}else{
			header("Location: ".HEJIN_URL."&model=vote&vid=".$zpinfo['vid']."");
		}
	}
	}
}
//搜索悬浮
else if($model == 'search'){
	$vid = intval($_GET['vid']);
	if($vid){
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
		$ishave = count($vote);
		if($ishave){
			include template('hejin_toupiao:index2/searcha');
		}
	}
}
//搜索关键字
else if($model == 'searchkey'){
	$keyword = addslashes(strip_tags($_GET['key']));
	$vid = intval($_GET['vid']);
	$zuopins = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_key_all_vid($vid,$keyword);
	$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
			$picarray = explode(',',$vote['pic']);
			if(count($picarray)>1){
				$ispicarr = 1;
			}else{
				$ispicarr = 0;
			}
	if($_COOKIE['hjbox_openid']){
		$yopenid = addslashes($_COOKIE['hjbox_openid']);
		$havezp =  C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_openid_vid($yopenid,$vid);
		if(count($havezp)){
			$ishavezp = 1;
		}
	}
			if($hejintoupiao['hjtp_jssdk'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']){
				require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/jssdk.php';
				$jssdk = new JSSDK($hejinbox['hjbox_appid'], $hejinbox['hjbox_appsecret']);
				$signPackage = $jssdk->GetSignPackage();
			}
	if($vote['yuliua']==2){
		include template('hejin_toupiao:index2/search');
	}else{
		include template('hejin_toupiao:index/search');
	}
}

//投票活动报名
else if($model == 'signup'){
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
    	$shebei=1;
	}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
    	$shebei=2;
	}else{
    	$shebei=3;
	}
	$vid = intval($_GET['vid']);
	if($hejintoupiao['hjtp_picnum']<1){
		$xzpic = 0;
		$picnum = 1;
	}else{
		$xzpic = $hejintoupiao['hjtp_picnum']-1;
		$picnum = $hejintoupiao['hjtp_picnum'];
	}
	$page = intval($_GET['page']);
	if(!$_COOKIE['hjbox_openid']){
		if($_GET['openid']){
			$openid = addslashes($_GET['openid']);
			setcookie('hjbox_openid', $openid, time()+31536000);
			header("Location: ".HEJIN_URL."&model=signup&vid=".$vid."");
			exit;
		}
	}else{
		if($_GET['openid']){
			if($_GET['openid']!=$_COOKIE['hjbox_openid']){
				$openid = addslashes($_GET['openid']);
				setcookie('hjbox_openid', $openid, time()+31536000);
			}
			header("Location: ".HEJIN_URL."&model=signup&vid=".$vid."");
			exit;
		}
	}
	if($vid){
		$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
		$zid =  intval($_GET['zid'])?intval($_GET['zid']):0;
		$picarray = explode(',',$vote['pic']);
		if(count($picarray)>1){
			$ispicarr = 1;
		}else{
			$ispicarr = 0;
		}
		$ishave = count($vote);
		if($hejintoupiao['hjtp_jssdk'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret']){
			require_once DISCUZ_ROOT.'./source/plugin/hejin_toupiao/jssdk.php';
			$jssdk = new JSSDK($hejinbox['hjbox_appid'], $hejinbox['hjbox_appsecret']);
			$signPackage = $jssdk->GetSignPackage();
		}
		if($ishave){
			if($vote['start_time']>time()){
				$bmzt = 1;//报名还没有开始
			}elseif($vote['over_time']<time()){
				$bmzt = 2;//报名已经结束
			}else{
				if($_COOKIE['hjbox_openid']){
					$openid = addslashes($_COOKIE['hjbox_openid']);
					$user =  C::t('#hejin_toupiao#hjbox_users')->fetch_by_openid($openid);
					if($user){
						if($user['is_gz']==1){
							$zuopinhs = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_uid_vid(intval($user['id']),$vid);
							if(count($zuopinhs)){
								header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopinhs['id']."");
							}else{
								$bmzt = 4;//已关注
							}
							
						}else{
							$bmzt = 3;//未关注
						}
					}else{
						$bmzt = 3;//未关注
					}
				}else{
					$bmzt = 3;//未关注
				}
			}
			if($vote['yuliua']==2){
				include template('hejin_toupiao:index2/signup');
			}else{
				include template('hejin_toupiao:index/signup');
			}
		}
	}
}


//投票
else if($model == 'ticket'){
	if($_GET['formhash']==formhash()){
		if($_GET['zid']){
			$data = array();
			$zid = intval($_GET['zid']);
			if($hejintoupiao['hjtp_numbtp']){
				$data['status']=888;//编号投票
			}else{
			if($_COOKIE['hjbox_openid']){
				$openid = addslashes($_COOKIE['hjbox_openid']);
				$user =  C::t('#hejin_toupiao#hjbox_users')->fetch_by_openid($openid);
				if(count($user)){
					if($user['is_gz']==1){
						$zuopin = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_id($zid);
						if(count($zuopin)){
						if($zuopin['is_show']!=1){
							$data['status']=107;
						}else{
							$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id(intval($zuopin['vid']));
							if($vote['vote_time']>time()){
								$data['status']=103;//投票还未开始
							}elseif($vote['end_time']<time()){
								$data['status']=104;//投票已经结束
							}elseif(($vote['start_time']<time()) && ($vote['over_time']>time()) && $vote['yuliub'] && ($zuopin['toupiaos']>=$vote['yuliub'])){
								$data['status']=120;//报名期间达到投票限制数
							}else{
								if($hejintoupiao['hjtp_ipxz'] && $hejintoupiao['hjtp_ipid']){
									$tpip = GetIP();
									$ipdata = get_ip_data($tpip);
									if($ipdata){
										if($hejintoupiao['hjtp_ipfw']==1){
											$ipid = $ipdata['region_id'];
										}elseif($hejintoupiao['hjtp_ipfw']==2){
											$ipid = $ipdata['city_id'];
										}
										if($ipid==$hejintoupiao['hjtp_ipid']){
											$quyuxz = 1;
										}else{
											$quyuxz = 0;
										}
									}else{
										$quyuxz = 1;
									}
								}else{
									$quyuxz = 1;
								}
								
								if($quyuxz==1){//ip范围限制
								
									$today = date('Y-m-d',time());
									if($hejintoupiao['hjtp_tpxzmos']==2){
										$timedate = 1111111111;
									}else{
										$timedate = strtotime($today);
									}
									$utpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_today_uid_vid(intval($user['id']),intval($vote['id']),$timedate);
									
									
									$ip = GetIP();//获取ip流程
									if($vote['ipnubs']>0){
										$iptpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_today_ip_vid(addslashes($ip),intval($vote['id']),$timedate);
										if(count($iptpjls)<$vote['ipnubs']){
											if(count($utpjls)<$vote['tpnub']){
												if($hejintoupiao['hjtp_tpxznub']){
													$usetpjl = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_by_zvudid($zid,intval($vote['id']),intval($user['id']),$timedate);
													if(count($usetpjl)){
														$tpxznub = 0;
													}else{
														$tpxznub = 1;
													}
												}else{
													$tpxznub = 1;
												}
												if($tpxznub==1){//判断用户是否已经给这个用户投过一票
													if($hejintoupiao['hjtp_zdpbzp']){
														$sptime = time()-60;
														$spnubmer = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_sptime_all($zid,intval($sptime));
														$spnub = count($spnubmer);
														if($spnub>$hejintoupiao['hjtp_zdpbzp']){
															$xzspzdpb = 0;
														}else{
															$xzspzdpb = 1;
														}
													}else{
														$xzspzdpb = 1;
													}
													if($xzspzdpb){
														//写投票流程
														$tpdata = array();
														$tpdata['zid'] = $zid;
														$tpdata['uid'] = intval($user['id']);
														$tpdata['vid'] = intval($vote['id']);
														$tpdata['openid'] = $openid;
														$tpdata['ips'] = addslashes($ip);
														$tpdata['timedate'] = $timedate;
														$tpdata['yuliua'] = time();
														$addtpjl = C::t('#hejin_toupiao#hjtp_tpjles')->insert($tpdata);
														if($addtpjl){
															$data['status']=108;//投票成功
															$zptpup = array();
															
															if($hejintoupiao['hjtp_qxgzjp']){
																if($zuopin['yuliua']){
																	$zptpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_zid_all($zid);
																	$zptpup['toupiaos'] = count($zptpjls);
																	$zptpup['yuliua'] = intval($zuopin['yuliua']+1);
																}else{
																	
																	$zptpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_zid_all($zid);
																	$zptpup['toupiaos'] = count($zptpjls);
																	$zptpup['yuliua'] = count($zptpjls);
																}
															}else{
																$zptpup['toupiaos'] = intval($zuopin['toupiaos']+1);
																$zptpup['yuliua'] = intval($zuopin['toupiaos']+1);
															}
															$upzptps = C::t('#hejin_toupiao#hjtp_zuopins')->update_by_id($zid,$zptpup);
															$votetpup = array();
															$votetpup['toupiaos'] = intval($vote['toupiaos']+1);
															$upvotetp =  C::t('#hejin_toupiao#hjtp_votes')->update_by_id(intval($vote['id']),$votetpup);
															if($hejintoupiao['hjtp_tpjl'] && $hejintoupiao['hjtp_tpjlnub']){
																$upjifen = array();
																$upjifen['yuliua'] = intval($user['yuliua']+$hejintoupiao['hjtp_tpjlnub']);
																$jifenzj =  C::t('#hejin_toupiao#hjbox_users')->update_by_id(intval($user['id']),$upjifen);
															}

															//投票微信提醒功能
															if($hejintoupiao['hjtp_tpwxtx'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret'] && ($zuopin['openid']!='hejin')){
																
																$upszuopin = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_uptps_vid(intval($zuopin['vid']),intval($zptpup['toupiaos']));
																if(count($upszuopin)){
																	foreach($upszuopin as $key=>$zpzt){
																		if($zpzt['id']==$zuopin['id']){
																			$zpnumber = $key+1;
																		}	
																	}
																}
																if($zpnumber!=1){
																	$shangym = $zpnumber-2;
																	$xiangcps = $upszuopin[$shangym]['toupiaos']-$zptpup['toupiaos']+1;
																}

																if($user['nickname']){
																	$content = wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxa')).wxtostr($user['nickname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxb')).wxtostr($zuopin['zpname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxc')).$zptpup['toupiaos'].wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxd')).$zpnumber.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxe')).$xiangcps.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxf'))."\n <a href='".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopin['id']."'>".wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxg'))."</a>";
																}else{
																	$content = wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxaa')).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxb')).wxtostr($zuopin['zpname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxc')).$zptpup['toupiaos'].wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxd')).$zpnumber.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxe')).$xiangcps.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxf'))."\n <a href='".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopin['id']."'>".wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxg'))."</a>";
																}
																$sendmsg = sendmsg($hejinbox['hjbox_appid'],$hejinbox['hjbox_appsecret'],$zuopin['openid'],$content);
																
															}
															//结束


														}else{
															$data['status']=107;//投票不成功
														}
													}else{
														$zdpbspdata = array(
																		'is_show' => 3,
														);
														$zdpbsp =  C::t('#hejin_toupiao#hjtp_zuopins')->update_by_id($zid,$zdpbspdata);
													}
													
													
												}else{
													$data['status']=109;//今日已经给这个用户投过票了
												}
											}else{
												$data['status']=106;//此用户今日已无法投票
											}
										}else{
											$data['status']=105;//此ip下今日已无法投票
										}
									}else{
										if(count($utpjls)<$vote['tpnub']){
											if($hejintoupiao['hjtp_tpxznub']){
												$usetpjl = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_by_zvudid($zid,intval($vote['id']),intval($user['id']),$timedate);
												if(count($usetpjl)){
													$tpxznub = 0;
												}else{
													$tpxznub = 1;
												}
											}else{
												$tpxznub = 1;
											}
											if($tpxznub){//判断用户是否已经给这个用户投过一票

												if($hejintoupiao['hjtp_zdpbzp']){
													$sptime = time()-60;
													$spnubmer = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_sptime_all($zid,intval($sptime));
													$spnub = count($spnubmer);
													if($spnub>$hejintoupiao['hjtp_zdpbzp']){
														$xzspzdpb = 0;
													}else{
														$xzspzdpb = 1;
													}
												}else{
													$xzspzdpb = 1;
												}
												if($xzspzdpb){
										
													//写投票流程	
													$tpdata = array();
													$tpdata['zid'] = $zid;
													$tpdata['uid'] = intval($user['id']);
													$tpdata['vid'] = intval($vote['id']);
													$tpdata['openid'] = $openid;
													$tpdata['ips'] = addslashes($ip);
													$tpdata['timedate'] = $timedate;
													$tpdata['yuliua'] = time();
													$addtpjl = C::t('#hejin_toupiao#hjtp_tpjles')->insert($tpdata);
													if($addtpjl){
														$data['status']=108;//投票成功
														$zptpup = array();
															if($hejintoupiao['hjtp_qxgzjp']){
																if($zuopin['yuliua']){
																	$zptpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_zid_all($zid);
																	$zptpup['toupiaos'] = count($zptpjls);
																	$zptpup['yuliua'] = intval($zuopin['yuliua']+1);
																}else{
																	$zptpjls = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_zid_all($zid);
																	$zptpup['toupiaos'] = count($zptpjls);
																	$zptpup['yuliua'] = count($zptpjls);
																}
															}else{
																$zptpup['toupiaos'] = intval($zuopin['toupiaos']+1);
																$zptpup['yuliua'] = intval($zuopin['toupiaos']+1);
															}
														$upzptps = C::t('#hejin_toupiao#hjtp_zuopins')->update_by_id($zid,$zptpup);
														$votetpup = array();
														$votetpup['toupiaos'] = intval($vote['toupiaos']+1);
														$upvotetp =  C::t('#hejin_toupiao#hjtp_votes')->update_by_id(intval($vote['id']),$votetpup);
														if($hejintoupiao['hjtp_tpjl'] && $hejintoupiao['hjtp_tpjlnub']){
															$upjifen = array();
															$upjifen['yuliua'] = intval($user['yuliua']+$hejintoupiao['hjtp_tpjlnub']);
															$jifenzj =  C::t('#hejin_toupiao#hjbox_users')->update_by_id(intval($user['id']),$upjifen);
														}

															//投票微信提醒功能
															if($hejintoupiao['hjtp_tpwxtx'] && ($hejinbox['hjbox_wxgzrz']==2) && $hejinbox['hjbox_appid'] && $hejinbox['hjbox_appsecret'] && ($zuopin['openid']!='hejin')){
																
																$upszuopin = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_uptps_vid(intval($zuopin['vid']),intval($zptpup['toupiaos']));
																if(count($upszuopin)){
																	foreach($upszuopin as $key=>$zpzt){
																		if($zpzt['id']==$zuopin['id']){
																			$zpnumber = $key+1;
																		}	
																	}
																}
																if($zpnumber!=1){
																	$shangym = $zpnumber-2;
																	$xiangcps = $upszuopin[$shangym]['toupiaos']-$zptpup['toupiaos']+1;
																}

																if($user['nickname']){
																	$content = wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxa')).wxtostr($user['nickname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxb')).wxtostr($zuopin['zpname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxc')).$zptpup['toupiaos'].wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxd')).$zpnumber.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxe')).$xiangcps.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxf'))."\n <a href='".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopin['id']."'>".wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxg'))."</a>";
																}else{
																	$content = wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxaa')).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxb')).wxtostr($zuopin['zpname']).wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxc')).$zptpup['toupiaos'].wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxd')).$zpnumber.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxe')).$xiangcps.wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxf'))."\n <a href='".HEJIN_URL."&model=".$xqmodel."&zid=".$zuopin['id']."'>".wxtostr(lang('plugin/hejin_toupiao', 'tpwxtxg'))."</a>";
																}
																$sendmsg = sendmsg($hejinbox['hjbox_appid'],$hejinbox['hjbox_appsecret'],$zuopin['openid'],$content);
																
															}
															//结束
													}else{
														$data['status']=107;//投票不成功
													}

												}else{
													$zdpbspdata = array(
																	'is_show' => 3,
													);
													$zdpbsp =  C::t('#hejin_toupiao#hjtp_zuopins')->update_by_id($zid,$zdpbspdata);
												}


											}else{
												$data['status']=109;//今日已经给这个用户投过票了	
											}
										}else{
											$data['status']=106;//此用户今日已无法投票
										}
									}
								
								}else{
									$data['status']=110;//ip不在限制范围中
								}

							}
						}
						}
					}else{
						$data['status']=102;	
					}
				}else{
					$data['status']=102;	
				}
			}else{
				$data['status']=102;	
			}
				
			}
		}
	}
	echo $data['status'];
}



//浏览量记录
else if($model == 'clicks'){
	if($_GET['formhash']==formhash()){
		if($_GET['vid']){
			$vid = intval($_GET['vid']);
			$vote =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
			if(count($vote)){
				$upvotells = array(
					'clicks' => intval($vote['clicks']+1),
				);
				if($_GET['up']==1){$upvotells['is_sh']=0;}
				$upllsvote =  C::t('#hejin_toupiao#hjtp_votes')->update_by_id($vid,$upvotells);
			}
		}
		if($_GET['zid']){
			$zid = intval($_GET['zid']);
			$zpinfo = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_id($zid);
			if(count($zpinfo)){
				$upzplls = array(
					'liulans' => intval($zpinfo['liulans']+1),
				);
				$upllszp = C::t('#hejin_toupiao#hjtp_zuopins')->update_by_id($zid,$upzplls);
			}
		}
		
	}
}

//关注
/*
elseif($model == 'guanzhu'){
	include template('hejin_toupiao:guanzhu');
}
*/


//导出某用户的投票记录
else if($model == 'dcexcel'){
	$zid = intval($_GET['zid']);
	if($zid){
		header("Content-Type:text/html;charset=utf-8");
		$zpinfo = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_by_id($zid);
		$str ="USERID,OPENID,IP,TIME\n"; 
		$tplist = C::t('#hejin_toupiao#hjtp_tpjles')->fetch_zid_all($zid);
		foreach($tplist as $key=>$tps){
        	$str .= $tps['uid'].",".$tps['openid'].",".$tps['ips'].",".date('Y-m-d H:i:s',$tps['yuliua'])."\n"; 
		}
    		$filename = $zpinfo['zpname'].'.csv'; //设置文件名   
    		export_csv($filename,$str); //导出   
	}
}


//导出excel
else if($model == 'daochu'){
	$vid = intval($_GET['vid']);
	if($vid){
		//header("Content-Type:text/html;charset=utf-8");
		$dcnumb = intval($hejintoupiao['hjtp_dcexcelnub']);
		$voteinfo =  C::t('#hejin_toupiao#hjtp_votes')->fetch_by_id($vid);
		$stlists = C::t('#hejin_toupiao#hjtp_zuopins')->fetch_phb_vid($vid,$dcnumb);
		if($_G['charset']=='gbk'){
			$str = lang('plugin/hejin_toupiao', 'mingci').",ID,".lang('plugin/hejin_toupiao', 'zpxming').",".lang('plugin/hejin_toupiao', 'telphone').",".lang('plugin/hejin_toupiao', 'zppic').",".lang('plugin/hejin_toupiao', 'zppic').",".lang('plugin/hejin_toupiao', 'zppic').",".lang('plugin/hejin_toupiao', 'zppic').",".lang('plugin/hejin_toupiao', 'zppic').",".lang('plugin/hejin_toupiao', 'yijuhua').",".lang('plugin/hejin_toupiao', 'clicknuba').",".lang('plugin/hejin_toupiao', 'votenuba')."\n"; 
			
		}elseif($_G['charset']=='utf-8'){
			$str = g2u(lang('plugin/hejin_toupiao', 'mingci')).",ID,".g2u(lang('plugin/hejin_toupiao', 'zpxming')).",".g2u(lang('plugin/hejin_toupiao', 'telphone')).",".g2u(lang('plugin/hejin_toupiao', 'zppic')).",".g2u(lang('plugin/hejin_toupiao', 'zppic')).",".g2u(lang('plugin/hejin_toupiao', 'zppic')).",".g2u(lang('plugin/hejin_toupiao', 'zppic')).",".g2u(lang('plugin/hejin_toupiao', 'zppic')).",".g2u(lang('plugin/hejin_toupiao', 'yijuhua')).",".g2u(lang('plugin/hejin_toupiao', 'clicknuba')).",".g2u(lang('plugin/hejin_toupiao', 'votenuba'))."\n"; 
		}

		foreach($stlists as $key=>$zuopin){
      		$mingci = $key+1;
        	$xingming = $zuopin['zpname'];
        	$dianhua = $zuopin['telphone'];
			if($zuopin['pica']){
				if (strpos($zuopin['pica'], '://')==false){
					$pica = HEJIN_PATH.$zuopin['pica'];
				}else{
					$pica = $zuopin['pica'];
				}
			}else{
				$pica = '';
			}
			if($zuopin['picb']){
				if (strpos($zuopin['picb'], '://')==false){
					$picb = HEJIN_PATH.$zuopin['picb'];
				}else{
					$picb = $zuopin['picb'];
				}
			}else{
				$picb = '';
			}
			if($zuopin['picc']){
				if (strpos($zuopin['picc'], '://')==false){
					$picc = HEJIN_PATH.$zuopin['picc'];
				}else{
					$picc = $zuopin['picc'];
				}
			}else{
				$picc = '';
			}
			if($zuopin['picd']){
				if (strpos($zuopin['picd'], '://')==false){
					$picd = HEJIN_PATH.$zuopin['picd'];
				}else{
					$picd = $zuopin['picd'];
				}
			}else{
				$picd = '';
			}
			if($zuopin['pice']){
				if (strpos($zuopin['pice'], '://')==false){
					$pice = HEJIN_PATH.$zuopin['pice'];
				}else{
					$pice = $zuopin['pice'];
				}
			}else{
				$pice = '';
			}
        	
        	$yijuhua = $zuopin['content'];
        	$clicknuba = $zuopin['liulans'];
        	$votenuba = $zuopin['toupiaos'];
			if($hejintoupiao['hjtp_numbtp']){
				$bianhao = $zuopin['id'];
			}else{
				$bianhao = $vid*10000+$zuopin['id'];
			}

			if($_G['charset']=='gbk'){
        		$str .= $mingci.",".$bianhao.",".$xingming.",".$dianhua.",".$pica.",".$picb.",".$picc.",".$picd.",".$pice.",".$yijuhua.",".$clicknuba.",".$votenuba."\n"; 
			}elseif($_G['charset']=='utf-8'){
        		$str .= $mingci.",".$bianhao.",".g2u($xingming).",".$dianhua.",".$pica.",".$picb.",".$picc.",".$picd.",".$pice.",".g2u($yijuhua).",".$clicknuba.",".$votenuba."\n"; 
			}

		}
    	$filename = $voteinfo['title'].'.csv'; //设置文件名   
    	export_csv($filename,$str); //导出   
	}
}



else if($model == 'getxzid'){
	header("Content-Type:text/html;charset=utf-8");
	$tpip = GetIP();
	$ipdata = get_ip_data($tpip);
	echo '<p>您的当前IP：'.$tpip.'</p>';
	echo '<p>当前省份：'.$ipdata['region'].'省份区域ID:'.$ipdata['region_id'].'</p>';
	echo '<p>当前城市：'.$ipdata['city'].'城市区域ID:'.$ipdata['city_id'].'</p>';
	echo '<p>请根据您选择的“限制IP区域范围”来复制对应的ID填入到“限制地区ID”中去，比如 您选择了“城市限制” 就把上方的 “城市区域ID”中的值 复制填入到“限制地区ID”中去</p>';
}

else if($model == 'fromip'){
	header("Content-Type:text/html;charset=utf-8");
	if($_GET['ip']){
		$tpip = $_GET['ip'];
		$ipdata = get_ip_data($tpip);
		echo '<p>您的当前IP：'.$tpip.'</p>';
		echo '<p>当前省份：'.$ipdata['region'].'省份区域ID:'.$ipdata['region_id'].'</p>';
		echo '<p>当前城市：'.$ipdata['city'].'城市区域ID:'.$ipdata['city_id'].'</p>';
		echo '<p>请根据您选择的“限制IP区域范围”来复制对应的ID填入到“限制地区ID”中去，比如 您选择了“城市限制” 就把上方的 “城市区域ID”中的值 复制填入到“限制地区ID”中去</p>';
	}
}

else{
	if($_GET['vid']){
		$vid = intval($_GET['vid']);
		header("Location: ".HEJIN_URL."&model=".$symodel."&vid=".$vid."");
		exit;
	}elseif($_GET['zid']){
		$zid = intval($_GET['zid']);
		header("Location: ".HEJIN_URL."&model=".$xqmodel."&zid=".$zid."");
		exit;
	}
}

function savepic($post){
	$picname = '/Uploads/'.time().rand(100,999).'.jpeg';
    $file=HEJIN_ROOT.$picname;
    $base64=base64_decode($post);
    $save = file_put_contents($file, $base64);
	if($save){
		return $picname;
	}
}
function tcupload($file,$xcid){
	$ttk=new TTKClient(MY_ACCESSKEY,MY_SECRETKEY);
	$picurl = HEJIN_PATH.$file;
	$res=$ttk->uploadFile($xcid,$picurl);
	$res = str_replace("{", "", $res);
	$res = str_replace("}", "", $res);
	$res = str_replace('"', "", $res);
	$array = explode(',',$res);
	$s_url = str_replace('linkurl:', "", $array[5]);
	if($s_url){
		return stripslashes($s_url);
	}else{
		return $file;
	}
}

//获取初始信息：access_token（网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同） 和 openid 信息
function getfirst($appid, $appsecret, $code) {
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
    $result = https_request($url);
    $jsoninfo = json_decode($result, true);
    return $jsoninfo;
}

?>