<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
   微信支付回调接口文件
*/

define('APPTYPEID', 127);
define('CURSCRIPT', 'plugin');
define('DISABLEXSSCHECK', true); 

$_GET['id'] = 'tom_tongcheng';

require substr(dirname(__FILE__), 0, -28).'/source/class/class_core.php';

$discuz = C::app();
$cachelist = array('plugin', 'diytemplatename');

$discuz->cachelist = $cachelist;
$discuz->init();

define('CURMODULE', 'tom_tongcheng');

$_G['siteurl'] = substr($_G['siteurl'], 0, -28);
$_G['siteroot'] = substr( $_G['siteroot'], 0, - 28);

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

$wxpay_appid        = trim($tongchengConfig['wxpay_appid']);
$wxpay_mchid        = trim($tongchengConfig['wxpay_mchid']);
$wxpay_key          = trim($tongchengConfig['wxpay_key']);
$wxpay_appsecret    = trim($tongchengConfig['wxpay_appsecret']);

define("TOM_WXPAY_APPID", $wxpay_appid);
define("TOM_WXPAY_MCHID", $wxpay_mchid);
define("TOM_WXPAY_KEY", $wxpay_key);
define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/wxpay/lib/WxPay.Api.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/wxpay/lib/WxPay.Notify.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/wxpay/log.php';

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

$logDir = DISCUZ_ROOT."./source/plugin/tom_tongcheng/logs/";
if(!is_dir($logDir)){
    mkdir($logDir, 0777,true);
}else{
    chmod($logDir, 0777); 
}
$logHandler= new CLogFileHandler(DISCUZ_ROOT."./source/plugin/tom_tongcheng/logs/".date("Y-m-d").".log");
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify{
    
	public function Queryorder($transaction_id){
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
			return true;
		}
		return false;
	}
	
	public function NotifyProcess($data, &$msg){
        global $_G;
        
        Log::DEBUG("call back:" . json_encode($data));
        
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
            Log::DEBUG("error:can shu cuo wu");
            $msg = "can shu cuo wu";
			return false;
		}
		if(!$this->Queryorder($data["transaction_id"])){
            Log::DEBUG("error:ding dan cha xu shi bai");
            $msg = "ding dan cha xu shi bai";
			return false;
		}
        
        if(isset($data['result_code']) && $data['result_code']=='SUCCESS'){
        }else{
            Log::DEBUG("error:result_code error");
            $msg = "result_code error";
            return false;
        }
        
        if(isset($data['out_trade_no']) && !empty($data['out_trade_no'])){
        }else{
            Log::DEBUG("error:out_trade_no error");
            $msg = "out_trade_no error";
            return false;
        }
        
        $orderInfo = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_by_order_no($data['out_trade_no']);
        if($orderInfo && $orderInfo['order_status'] == 1){
            $updateData = array();
            $updateData['order_status'] = 2;
            $updateData['pay_time'] = TIMESTAMP;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($orderInfo['id'],$updateData);
            
            Log::DEBUG("update order:" . json_encode($orderInfo));
            
            $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($orderInfo['tongcheng_id']);
            
            if($orderInfo['order_type'] == 1){
                
                $updateData = array();
                $updateData['status'] = 1;
                $updateData['pay_status'] = 2;
                $updateData['refresh_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng')->update($orderInfo['tongcheng_id'],$updateData);
                
            }else if($orderInfo['order_type'] == 2){
                
                $updateData = array();
                $updateData['refresh_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng')->update($orderInfo['tongcheng_id'],$updateData);
                
            }else if($orderInfo['order_type'] == 3){
                
                $toptime = TIMESTAMP;
                if($tongchengInfo['toptime'] > TIMESTAMP){
                    $toptime = $tongchengInfo['toptime'] + $orderInfo['time_value']*86400;
                }else{
                    $toptime = TIMESTAMP + $orderInfo['time_value']*86400;
                }
                $updateData = array();
                $updateData['topstatus'] = 1;
                $updateData['toptime'] = $toptime;
                C::t('#tom_tongcheng#tom_tongcheng')->update($tongchengInfo['id'],$updateData);
            }
            
            # fc start
            $tcadminfilename = DISCUZ_ROOT.'./source/plugin/tom_tcadmin/tom_tcadmin.inc.php';
            if(file_exists($tcadminfilename)){
                
                $tcadminConfig = $_G['cache']['plugin']['tom_tcadmin'];
                $fc_scale = $tcadminConfig['fc_scale'];
                $fc_money = $orderInfo['pay_price']*($fc_scale/100);
                $fc_money = number_format($fc_money,2);
                
                if($tcadminConfig['open_fc'] == 1){
                    
                    Log::DEBUG("update fc_money:" . $fc_money);
                    
                    $walletInfo = C::t('#tom_tcadmin#tom_tcadmin_wallet')->fetch_by_site_id($orderInfo['site_id']);
                
                    $old_money = 0;
                    if($walletInfo){
                        $old_money = $walletInfo['account_balance'];

                        $updateData = array();
                        $updateData['account_balance']   = $walletInfo['account_balance'] + $fc_money;
                        $updateData['total_income']   = $walletInfo['total_income'] + $fc_money;
                        C::t('#tom_tcadmin#tom_tcadmin_wallet')->update($walletInfo['id'],$updateData);
                    }else{
                        $insertData = array();
                        $insertData['site_id']              = $orderInfo['site_id'];
                        $insertData['account_balance']      = $fc_money;
                        $insertData['total_income']         = $fc_money;
                        $insertData['add_time']             = TIMESTAMP;
                        C::t('#tom_tcadmin#tom_tcadmin_wallet')->insert($insertData);
                    }

                    $insertData = array();
                    $insertData['site_id']      = $orderInfo['site_id'];
                    $insertData['log_type']     = 1;
                    $insertData['change_money'] = $fc_money;
                    $insertData['old_money']    = $old_money;
                    $insertData['beizu']        = contentFormat($tongchengInfo['content']);
                    $insertData['order_no']     = $orderInfo['order_no'];
                    $insertData['order_type']   = $orderInfo['order_type'];
                    $insertData['log_ip']       = $_G['clientip'];
                    $insertData['log_time']     = TIMESTAMP;
                    C::t('#tom_tcadmin#tom_tcadmin_wallet_log')->insert($insertData);
                }
            }
            # fc end
            
        }
        
		return true;
	}
    
}

Log::DEBUG("begin notify3");
$notify = new PayNotifyCallBack();
$notify->Handle(false);

