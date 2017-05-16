<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include '../config/config.php';
include '../libraries/utils.php';
include '../libraries/mysqli.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

function response($ret){
    if( ! is_array($ret)){
        if(empty($ret)){
            $ret = array();
        }else{
            $ret = array('message' => htmlspecialchars($ret));
        }
    }
    if( ! isset($ret['code'])){     //补齐code
        if( ! count($ret)){
            $ret['code'] = 404;
        }else{
            $ret['code'] = 200;
        }
    }
    $ret = myjson_encode($ret);   
    $ret = str_replace(chr(10),'',$ret); 
    $ret = str_replace(chr(13),'',$ret);
    $ret = str_replace("\'",chr(39),$ret); 
    echo $ret;
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    response('请返回重试！code: 1001');
}

$token = trim(request('token'));
$userid = intval(request('userid'));
$appid = intval(request('appid'));
$orderid = intval(request('orderid'));

if($userid <= 0 || $appid <= 0 || $orderid <= 0 || !$token ){
    response('无效的请求！code: 1001');
}
if(md5($userid.' '.$appid.' '.$orderid.' '.$secret_key) != $token){
    response('无效的请求！code: 1002');
}

$sql = "SELECT `name`,`domain`,`ver`,`typeid`,`mobile_pay`,`mobile_appid`,`mobile_appsecret`,`mobile_mchid`,`mobile_mchkey`,`mobile_sslcert`,`mobile_sslkey` FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    response('当前应用不存在！请返回！code: 1003');
}
$appData = $ret[0];
if(!$appData['mobile_pay'] ){
    response('暂未启用微信支付！code: 1004');
}
$sql = "SELECT `orderid`,`order_number`,`appid`,`userid`,`goods_info`,`price`,`status` FROM `the_order` WHERE `orderid` = '".$orderid."' AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    response('订单不存在！请返回！code: 1007');
}
$orderData = $ret[0];
if($orderData['appid'] != $appid){
    response('当前应用不存在该订单！code: 1008');
}else if($orderData['userid'] != $userid){
    response('当前用户不存在该订单！code: 1009');
}else if($orderData['status'] == 0){
    response('订单已过期！请返回！code: 1010');
}else if($orderData['status'] > 1){
    response('订单已支付，无需重复支付！请返回！code: 1011');
}elseif($orderData['status'] < 0){
    response('订单存在异常！请联系客服！code: 1012');
}

function getConf($k){
    global $appData;
    $conf = array(
        'APPID' => $appData['mobile_appid'],
        'APPSECRET' => $appData['mobile_appsecret'],
        'MCHID' => $appData['mobile_mchid'],
        'MCHKEY' => $appData['mobile_mchkey'],
        'SSLCERT_PATH' => '../'.$appData['mobile_sslcert'],
        'SSLKEY_PATH' => '../'.$appData['mobile_sslkey']
    );
    if(isset($conf[$k])){
        return $conf[$k];
    }
}

function httpsReq($url, $json = true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    $rep = curl_exec($ch);
    if(!curl_errno($ch)){
        $info = curl_getinfo($ch);
        if($info['http_code'] == 200){
            if($json){
                if(gettype(json_decode($rep, 1)) == 'array' ){
                    $rep = json_decode($rep, 1);
                }else{
                    $rep = array();    
                }
            }
        }
    }
    curl_close($ch);
    return $rep;
}

/**
 * Generate a nonce string
 *
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=4_3
 */
function generateNonce(){
    return md5(uniqid('', true));
}

/**
 * Get a sign string from array using app key
 *
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=4_3
 */
function calculateSign($arr, $key){
    ksort($arr);

    $buff = "";
    foreach ($arr as $k => $v) {
        if ($k != "sign" && $k != "key" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }

    $buff = trim($buff, "&");

    return strtoupper(md5($buff . "&key=" . $key));
}

/**
 * Get xml from array
 */
function getXMLFromArray($arr){
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if (is_numeric($val)) {
            $xml .= sprintf("<%s>%s</%s>", $key, $val, $key);
        } else {
            $xml .= sprintf("<%s><![CDATA[%s]]></%s>", $key, $val, $key);
        }
    }

    $xml .= "</xml>";

    return $xml;
}

/**
 * Generate a prepay id
 *
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_1
 */
function generatePrepayId(){
    global $appData,$orderData,$host,$token;
    
    $order_number = $orderData['order_number'];
    $body = $appData['name'].'订单号：'.$order_number;
    
    $protocol = 'http:';
    if(isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
        $protocol = 'https:';
    }
    
    $attachData = $orderData;
    $attachData['token'] = $token;
    unset($attachData['goods_info']);
    unset($attachData['price']);
    unset($attachData['status']);
    $attach = myjson_encode($attachData);        
    
    $params = array(
        'attach'           => $attach,
        'appid'            => getConf('APPID'),
        'mch_id'           => getConf('MCHID'),
        'nonce_str'        => generateNonce(),
        'body'             => $body,
        'out_trade_no'     => $order_number.'_'.rand(1000,9999),   //订单号
        'total_fee'        => $orderData['price']*100,             //价格（分）
        'spbill_create_ip' => getIp(),
        'notify_url'       => $protocol."//".$host.str_replace('WxPayHybrid.php','WxPayHybridNotify.php',$_SERVER['PHP_SELF']),   //支付回调地址
        'trade_type'       => 'APP',
    );
    
    // add sign
    $params['sign'] = calculateSign($params, getConf('MCHKEY'));

    // create xml
    $xml = getXMLFromArray($params);

    // send request
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => "https://api.mch.weixin.qq.com/pay/unifiedorder",
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => array('Content-Type: text/xml'),
        CURLOPT_POSTFIELDS     => $xml,
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    // get the prepay id from response
    $xml = simplexml_load_string($result);
    return (string)$xml->prepay_id;
}

// re-sign it
$data = array(
    'appid'     => getConf('APPID'),
    'partnerid' => getConf('MCHID'),
    'prepayid'  => generatePrepayId(),
    'package'   => 'Sign=WXPay',
    'noncestr'  => generateNonce(),
    'timestamp' => time(),
);
$data['sign'] = calculateSign($data, getConf('MCHKEY'));

//更新 订单信息 
$sql = "UPDATE `the_order` SET `pay_data`='".json_encode($data)."', `pay_status` = 1, `pay_at` = '".time()."', `update_at` = '".time()."'  WHERE `pay_status` < 2 AND `orderid` = '".$orderData['orderid']."' ";
$state = $db->updateX($sql);
if(!$state){
    response('无效的请求！code: 1002');    
}

response($data);
