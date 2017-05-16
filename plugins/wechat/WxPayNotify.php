<?php
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include '../config/config.php';
include '../libraries/utils.php';
include '../libraries/mysqli.php';
include '../../redis/Redis_inc.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

$protocol = 'http:';
if(isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
    $protocol = 'https:';
}

function showError($msg){
    echo '<span style="color:#EF4F4F;font-size:24px">'.$msg.'</span>';
    echo '<br><br><button style="width:100%; padding:0 14px; height:42px; border-radius: 5px;background-color:#f7f7f7; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#454545;  font-size:16px;" type="button" onclick="window.locatioin.href=\''.$protocol.'//www.dindin.com\'" >请访问叮叮网</button>';
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    showError('请返回重试！code: 1001');
}

$host = strtolower($host);
$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = getAppid($domain);

$whereStr = "`domain` = '".$domain."'";
if($appid){
    $whereStr = "`appid` = '".$appid."'";
}

$sql = "SELECT `appid`,`name`,`ver`,`typeid`,`wx_appid`,`wx_appsecret`,`mchid`,`mchkey`,`sslcert`,`sslkey` FROM `the_app_sets` WHERE ".$whereStr." AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    showError('当前应用不存在！code: 1003');
}
$appData = $ret[0];

function getConf($k){
    global $appData;
    $conf = array(
        'APPID' => $appData['wx_appid'],
        'APPSECRET' => $appData['wx_appsecret'],
        'MCHID' => $appData['mchid'],
        'MCHKEY' => $appData['mchkey'],
        'SSLCERT_PATH' => '../'.$appData['sslcert'],
        'SSLKEY_PATH' => '../'.$appData['sslkey']
    );
    if(!isset($conf[$k])){
        showError('配置信息“'.$k.'”不存在！code: 1005');
    }
    return $conf[$k];
}

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

$cache = new Redis_cache();

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        Log::DEBUG("QueryorderError:" . json_encode($result));
        return false;
    }
    
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        global $appData,$secret_key,$db,$cache;
        
        if($data['appid'] != getConf('APPID')){
            $msg = "appid不匹配";
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        
        if(!$data['attach'] || gettype(json_decode($data['attach'], true)) != 'array' ){
            $msg = "attach数据异常";
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        $attach = json_decode($data['attach'], true);
        
        $tmp = explode('_', $data['out_trade_no']);
        $out_trade_no = $tmp[0];
        
        if($attach['order_number'] != $out_trade_no){
            $msg = "out_trade_no不匹配";
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        
        $userid = $attach['userid'];
        $appid = $attach['appid'];
        $orderid = $attach['orderid'];
        $token = $attach['token'];
        
        if($userid <= 0 || $appid <= 0 || $orderid <= 0 || !$token ){
            $msg = 'attach数据无效';
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        if(md5($userid.' '.$appid.' '.$orderid.' '.$secret_key) != $token){
            $msg = 'token无效';
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        
        $notfiyOutput = array();
        
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            Log::DEBUG("Call Back Error: ".$msg."; Call Back Data:" . json_encode($data));
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        
        //更新订单的支付状态 pay_status: {"array":[["未支付","0"],["开始支付","1"],["支付完成","2"]],"mysql":""}
        $sql = "UPDATE `the_order` SET `status` = '2', `pay_status` = '2', `pay_at` = '".time()."', `update_at` = '".time()."' WHERE `orderid` = '".$orderid."' AND `appid` = '".$appid."' AND `userid` = '".$userid."' AND `order_number` = '".$attach['order_number']."' AND `pay_status` = '1' AND `status` = '1' ";
        $state = $db->updateX($sql);
        if(!$state){
            $msg = "订单信息更新失败";
            Log::DEBUG("Call Back Error: ".$msg."; SQL: ".$sql."; Call Back Data:" . json_encode($data));
            return false;   
        }
        
        $key = 'order:'.$appid.':'.$userid.':1';
        $cache->del($key);                                  //清除缓存
        $key = 'order_detail:'.$appid.':'.$userid.':'.$orderid;
        $cache->del($key);                                  //清除缓存
        
        //库存处理
        $sql = "SELECT `goods_info` FROM `the_order` WHERE `orderid` = '".$orderid."' ";
        $gres = $db->getX($sql);
        if(count($gres) && isset($gres[0]["goods_info"]) && $gres[0]["goods_info"]){
            $goods_info = $gres[0]['goods_info'];
            if( gettype(json_decode($goods_info,1)) == 'array'){
                $goods_info = json_decode($goods_info,1);
                foreach($goods_info["reduce_goods"] as $gd){
                    if($goods_info['reduce_late'] == 2){         //reduce_late: 0=下单时减库存，发货时加出售量，1=发货时减少库存同时加出货量，2=付款时减库存，发货时加出售量
                        if($gd['modelsid']){
                            $sql = 'UPDATE `the_models` SET `store` = `store` - '.$gd['quantity'].' WHERE `modelsid` = '.$gd['modelsid'].' AND `store` > 0 ';
                        }else{
                            $sql = 'UPDATE `the_goods` SET `store` = `store` - '.$gd['quantity'].' WHERE `goodsid` = '.$gd['goodsid'].' AND `store` > 0 ';
                        }
                        $db->updateX($sql);                   //更新商品订单占用量和销量
                    }
                }
            }
        }
        
        return true;
    }
}


$notify = new PayNotifyCallBack();

$notify->Handle(false);