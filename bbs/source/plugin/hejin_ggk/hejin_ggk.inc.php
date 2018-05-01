<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/hejin_ggk/config.inc.php';
$sitename = $_G['setting']['bbname'];	
$model = addslashes($_GET['model']);
if($plugininfo['hjggk_xzwxllq']){
	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
	if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
		include template('hejin_ggk:index/weixin');
		exit;
	}
}

//刮刮卡中奖领取
if(submitcheck('add_ggkjl')){
	
	$ggkjid = intval($_POST['ggkjid']);
	$gid = intval($_POST['gid']);
	if($_COOKIE['hjbox_openid']){
		$openid = addslashes($_COOKIE['hjbox_openid']);
		$uinfo = C::t('#hejin_ggk#hjbox_users')->fetch_by_openid($openid);
		if(count($uinfo)){
			if(!$uinfo['telphone']){
				if(addslashes($_POST['telphone'])){
					$useupdataa = array(
						'telphone' => addslashes($_POST['telphone']),
					);
					$useropen = C::t('#hejin_ggk#hjbox_users')->update_by_id(intval($uinfo['id']),$useupdataa);
				}
			}
			if($ggkjid){
				$ggkjl = C::t('#hejin_ggk#hjggk_jiangs')->fetch_by_id($ggkjid);
				if($ggkjl['yizjnub']<$ggkjl['number']){
					$ggkjldata = array(
							'gid' => $gid,
							'uid' => intval($uinfo['id']),
							'openid' => $openid,
							'telphone' => addslashes($_POST['telphone']),
							'ggkjid' => $ggkjid,
							'snno'=>$ggkjid.generate_password(9),
							'add_time' => time(),
					);
					$ggkzjjl = C::t('#hejin_ggk#hjggk_zjjls')->insert($ggkjldata);
					if($ggkzjjl){
						$gxggkjls = array(
							'yizjnub' => $ggkjl['yizjnub']+1,
						);
						$gxggkjlsok = C::t('#hejin_ggk#hjggk_jiangs')->update_by_id($ggkjid,$gxggkjls);
						if($gxggkjlsok){
							header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
						}else{
							header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
						}
					}else{
						header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
					}
				}else{
					header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
				}
			}else{
				header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
			}
		}

	}else{
		header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
	}
}










//刮刮卡
elseif($model == 'ggk'){
	$gid = intval($_GET['gid']);
	if(!$_COOKIE['hjbox_openid']){
		if($_GET['openid']){
			$openid = addslashes($_GET['openid']);
			setcookie('hjbox_openid', $openid, time()+31536000);
			header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
		}
	}else{
		$cookie = addslashes($_COOKIE['hjbox_openid']);
		if($plugininfo['hjggk_daysong']){
			$uinfo = C::t('#hejin_ggk#hjbox_users')->fetch_by_openid($cookie);
			if($uinfo['is_gz']){
				$did = strtotime(date('Y-m-d'));
				$jfjljl = C::t('#hejin_ggk#hjggk_jfjls')->fetch_by_udid(intval($uinfo['id']),$did);
				if(!count($jfjljl)){
					$jfjldata = array(
						'uid'=>intval($uinfo['id']),
						'openid'=>$cookie,
						'daytime'=>$did,
					);
					$addjfjl = C::t('#hejin_ggk#hjggk_jfjls')->insert($jfjldata);
					if($addjfjl){
						$userjfadd = array(
							'yuliua' => intval($uinfo['yuliua']+$plugininfo['hjggk_daysong']),
						);
						$upuhjf = C::t('#hejin_ggk#hjbox_users')->update_by_id(intval($uinfo['id']),$userjfadd);
						if($upuhjf){
							header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");
						}
					}
				}
			}
		}
		if($_GET['openid']){
			header("Location: ".HEJIN_URL."&model=ggk&gid=".$gid."");	
		}
	}
	
	if($gid){
		$vote =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
		$jplist = C::t('#hejin_ggk#hjggk_jiangs')->fetch_all_gid($gid);
		
		//print_r($jplist);
		
		if($_COOKIE['hjbox_openid']){
			$openids = addslashes($_COOKIE['hjbox_openid']);
			$ggkzjle =  C::t('#hejin_ggk#hjggk_zjjls')->fetch_by_ogid($gid,$openids);
			$ggkzjlec = count($ggkzjle);
		}
		if($vote['start_time']<time() && $vote['end_time']>time()){
			
			if($_COOKIE['hjbox_openid']){
				$openid = addslashes($_COOKIE['hjbox_openid']);
				$uinfo = C::t('#hejin_ggk#hjbox_users')->fetch_by_openid($openid);
								
				if(count($uinfo) && $uinfo['is_gz']==1){

					$okgua = 0;
					if($vote['daynub']){
						$did = strtotime(date('Y-m-d'));
						$disgkg = C::t('#hejin_ggk#hjggk_gkjilus')->fetch_by_gudid($gid,intval($uinfo['id']),$did);
						if($disgkg['gkbub'] < $vote['daynub']){
							$okgua=1;
						}else{
							$okgua=0;
						}
					}else{
						$okgua=1;
					}
					if($okgua){
						$uid = $uinfo['id'];
						$ggknub =  floor($uinfo['yuliua']/$vote['jifen']);
						if($ggknub>0){
							$status = 5;
							$jpzlnub = count($jplist);
							$prize_arr = array();
							foreach ($jplist as $key => $value){
								if($key==0){
									$prize_arr[1] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>0,
										'end'=>$value['number'],
									);
								}
								if($key==1){
									$prize_arr[2] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-1]['number'],
										'end'=>$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==2){
									$prize_arr[3] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==3){
									$prize_arr[4] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==4){
									$prize_arr[5] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==5){
									$prize_arr[6] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==6){
									$prize_arr[7] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-6]['number']+$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-6]['number']+$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
								if($key==7){
									$prize_arr[8] = array(
										'id'=>$value['id'],
										'prize'=>$value['title'],
										'v'=>$value['number'],
										'start'=>$jplist[$key-7]['number']+$jplist[$key-6]['number']+$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number'],
										'end'=>$jplist[$key-7]['number']+$jplist[$key-6]['number']+$jplist[$key-5]['number']+$jplist[$key-4]['number']+$jplist[$key-3]['number']+$jplist[$key-2]['number']+$jplist[$key-1]['number']+$value['number'],
									);
								}
							}
					
							$prize_arr[$jpzlnub+1] =array(
								'id'=> $jpzlnub+1,
								'prize'=>lang('plugin/hejin_ggk', 'xxcanyu'),
								'v'=>$vote['jilv']*$prize_arr[$jpzlnub]['end']-$prize_arr[$jpzlnub-1]['end'],
								'start'=>$prize_arr[$jpzlnub]['end'],
								'end'=>$vote['jilv']*$prize_arr[$jpzlnub]['end'],
							);

		    				$result = $jpzlnub+2; 
		   					$randNum = mt_rand(1, $vote['jilv']*$prize_arr[$jpzlnub]['end']); 
		    				foreach ($prize_arr as $k => $v) {
		    					if ($v['v']>0){//奖项存在或者奖项之外
		    						if ($randNum>$v['start']&&$randNum<=$v['end']){
		    							$result=$k;
		    							break;
		    						}
		    					}
		    				}
							$gkjlbh = intval($prize_arr[$result]['id']);
							if($result>=$jpzlnub+1){
								$zjl = 0;
								$winprize = lang('plugin/hejin_ggk', 'xxcanyu');
							}else{
								$usesfzg = C::t('#hejin_ggk#hjggk_zjjls')->fetch_by_gjuid($gid,$gkjlbh,$openid);
								if(!count($usesfzg)){
									$ggkjl = C::t('#hejin_ggk#hjggk_jiangs')->fetch_by_id($gkjlbh);
									if($ggkjl['yizjnub']<$ggkjl['number']){
										$zjl = 1;						
										$winprize = $prize_arr[$result]['prize'];
										$zjlpid= $prize_arr[$result]['id'];
									}else{
										$zjl = 0;
										$winprize = lang('plugin/hejin_ggk', 'xxcanyu');
									}
								}else{
									$zjl = 0;
									$winprize = lang('plugin/hejin_ggk', 'xxcanyu');
								}
						
							}
					}else{
						//没有刮刮卡机会状态
						$status = 4;
					}
					}else{
						//今日刮刮卡次数达到限制
						$status = 6;
					}
				}else{
					//未关注
					$status = 3;
				}
			}else{
				//未关注
				$status = 3;
			}
		}else{
			if($vote['start_time']>time()){
				//未开始状态
				$status = 1;
			}elseif($vote['end_time']<time()){
				//已结束状态
				$status = 2;
			}	
		}
		include template('hejin_ggk:index/guaka');
	}
}



//刮刮卡商家展示页
if($model == 'gjiang'){
	if($_GET['jid']){
		$jid = intval($_GET['jid']);
		$zid = intval($_GET['zid']);
		$jpinfo = C::t('#hejin_ggk#hjggk_jiangs')->fetch_by_id($jid);
		include template('hejin_ggk:index/shangjia');	
	}
}




//刮过卡减数量
elseif($model == 'guaguo'){
	if($_COOKIE['hjbox_openid']){
		$openid = addslashes($_COOKIE['hjbox_openid']);
		$uinfo = C::t('#hejin_ggk#hjbox_users')->fetch_by_openid($openid);
		$gid = intval($_GET['gid']);
		if(count($uinfo) && $uinfo['is_gz']){
			if($_GET['formhash']==formhash()){
				$guaka = C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
				$userjf = array(
					'yuliua' => $uinfo['yuliua']-$guaka['jifen'],
				);
				$did = strtotime(date('Y-m-d'));
				$disgkg = C::t('#hejin_ggk#hjggk_gkjilus')->fetch_by_gudid($gid,intval($uinfo['id']),$did);
				if(count($disgkg)){
					$upgkjilu = array(
						'gkbub'=>$disgkg['gkbub']+1,
					);
					$upgklu = C::t('#hejin_ggk#hjggk_gkjilus')->update_by_id(intval($disgkg['id']),$upgkjilu);
				}else{
					$gkjilu = array(
						'gid'=>$gid,
						'uid'=>intval($uinfo['id']),
						'openid'=>$openid,
						'dayid'=>$did,
						'gkbub'=>1,
					);
					$addgklu = C::t('#hejin_ggk#hjggk_gkjilus')->insert($gkjilu);
				}

				$upjf = C::t('#hejin_ggk#hjbox_users')->update_by_id(intval($uinfo['id']),$userjf);
				if($upjf){
					$ggkcs = array(
						'gjcs'=>$guaka['gjcs']+1,
					);
					$gjcsup = C::t('#hejin_ggk#hjggk_ggks')->update_by_id($gid,$ggkcs);
				}
			}
		}else{
			echo 1;
		}
	}else{
		echo 1;
	}
}
//商家发奖入口
elseif($model == 'shangjia'){
	$gid = intval($_GET['gid']);
	if($gid){
		if($_COOKIE['hjggk_login'.$gid]){
			header("Location: ".HEJIN_URL."&model=zhongj&gid=".$gid."");
		}else{
			include template('hejin_ggk:index/pass');
		}
	}
}

//判断登录密码
if($model == 'checkpass'){
	$gid = intval($_GET['gid']);
	$paw = addslashes($_GET['paw']);
	if($gid && $paw){
		$wxqinfo =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
		$ishave = count($wxqinfo);
		if($ishave){
			if($wxqinfo['password']==$paw){
				$mimazt = 1;
				setcookie('hjggk_login'.$gid, '1', time()+31536000);
			}
		}
	}
	echo $mimazt;
}

//刮刮卡中奖纪录
elseif($model == 'zhongj'){
	$gid = intval($_GET['gid']);
	if($gid){
		if(!$_COOKIE['hjggk_login'.$gid]){
			header("Location: ".HEJIN_URL."&model=shangjia&gid=".$gid."");
		}else{
			$voteinfo =  C::t('#hejin_ggk#hjggk_ggks')->fetch_by_id($gid);
			$jianges =  C::t('#hejin_ggk#hjggk_zjjls')->fetch_all_gid($gid);
			include template('hejin_ggk:index/ggkzj');
		}
	}
}

//领取奖品确认
elseif($model == 'qurenlq'){
	if($_GET['formhash']==formhash()){
		$zid = intval($_GET['zid']);
		$gid = intval($_GET['gid']);
		if($_COOKIE['hjggk_login'.$gid]){
			if($zid){
				$lqqrdata = array(
					'is_lq'=>1,
				);
				$lqqr =  C::t('#hejin_ggk#hjggk_zjjls')->update_by_id($zid,$lqqrdata);
				if($lqqr){
					header("Location: ".HEJIN_URL."&model=zhongj&gid=".$gid."");
				}
			}
		}else{
			header("Location: ".HEJIN_URL."&model=zhongj&gid=".$gid."");
		}
	}
}


function generate_password($length = 8) {  
    $chars = 'abcd0123456789';  
    $password = '';  
    for ( $i = 0; $i < $length; $i++ )  
    {  
   	 $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
    }  
    return $password;  
} 


?>