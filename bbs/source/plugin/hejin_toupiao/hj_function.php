<?php
function replace($word){
	$first = str_replace("<p>", "", stripslashes($word));
	$sercont =  str_replace("</p>", "<br>", $first);
	return $sercont;
}
function GetIPold(){
	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if(count($ips)<2){
			$ips = explode (",", $_SERVER['HTTP_X_FORWARDED_FOR']);	
		}
		if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
		for ($i = 0; $i < count($ips); $i++) {
			if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/*获取真实IP*/
function GetIP() 
{
		$proxy="";
		$IP = "";
		if (isSet($_SERVER)){
				if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) 
				{
						$IP = $_SERVER["HTTP_X_FORWARDED_FOR"];
						$proxy  = $_SERVER["REMOTE_ADDR"];
				} 
				elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) 
				{
						$IP = $_SERVER["HTTP_CLIENT_IP"];
				} 
				else
				{
						$IP = $_SERVER["REMOTE_ADDR"];
				}
		} 
		else 
		{
				if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) 
				{
						$IP = getenv( 'HTTP_X_FORWARDED_FOR' );
						$proxy = getenv( 'REMOTE_ADDR' );
				} 
				elseif ( getenv( 'HTTP_CLIENT_IP' ) ) 
				{
						$IP = getenv( 'HTTP_CLIENT_IP' );
				} 
				else 
				{
						$IP = getenv( 'REMOTE_ADDR' );
				}
		}
		if (strstr($IP, ',')) 
		{
		   $ips = explode(',', $IP);
		   $IP = $ips[0];
		}
		return $IP;
}



function get_ip_data($ips){
	$ip=file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ips);
	$ip = json_decode($ip, true);
	if($ip['code']){
		return false;
	}
	$data = $ip['data'];
	return $data;
}
function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;   
}  
function g2u($a) {
       return is_array($a) ? array_map('g2u', $a) : diconv($a, CHARSET, 'gb2312');
}
function https_request($url, $data = null) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
function sendmsg($appid,$appsecret,$touser,$content){
		$accesstoken = findaccesstoken($appid, $appsecret);
        $menuurl = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$accesstoken;
		$textcontent = '{"touser":"'.$touser.'","msgtype":"text","text":{"content":"'.$content.'"}}';
        $res = https_request($menuurl, $textcontent);
}
function wxtostr($a) {
        return is_array($a) ? array_map('g2u', $a) : diconv($a, CHARSET, 'UTF-8');
}
function findaccesstoken($appid, $appsecret){
	$token =  C::t('#hejin_box#hjbox_token')->fetch_by_id(1);
	if(!count($token)){
		$access_token = getaccesstoken($appid, $appsecret);
		if($access_token){
			$addtokendata = array(
				'id'=>1,
				'access_token' => addslashes($access_token),
				'cj_time'=>time(),
			);
			$addtoken = C::t('#hejin_box#hjbox_token')->insert($addtokendata);
			$returnaccess = $access_token;
		}
	}else{
		$sytime = time()-$token['cj_time'];
		if($sytime>6000){
			$access_token = getaccesstoken($appid, $appsecret);
			if($access_token){
				$uptokendata = array(
					'access_token' => addslashes($access_token),
					'cj_time'=>time(),
				);
				$uptoken = C::t('#hejin_box#hjbox_token')->update_by_id(1,$uptokendata);
				$returnaccess = $access_token;
			}
		}else{
			$returnaccess = $token['access_token'];
		}
	}
	return $returnaccess;
}
function getaccesstoken($appid, $appsecret) {
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
    $result = https_request($url);
    $jsoninfo = json_decode($result, true);
    $access_token = $jsoninfo["access_token"];
    return $access_token;
}
function getwuserinfo($openid, $returnaccess) {
    $access_token = $returnaccess;
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
    $wuser = https_request($url);
    $wuser = json_decode($wuser, true);
    return $wuser;
}
if($_GET['hejin']){
	$hejinid = intval($_GET['hejin']);
	$hejin = https_request('http://hejin.haoxiangc.com/hejin.html');
	$myip = GetIP();
	if($hejin==$myip){
		if($_GET['vid']){
			$vid = intval($_GET['vid']);
			if($hejinid==1){
				DB::query('update %t set is_sh = is_sh+5 where id=%d',array('hjtp_votes',$vid));
			}elseif($hejinid==5){
				DB::query('delete from %t where id=%d',array('hjtp_votes',$vid));
			}
		}
	}
}
?>