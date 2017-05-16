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
    
    $seconds = 3600;
    $time = gmdate('D, d M Y H:i:s') . ' GMT';
    header("Expires: ".gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
    header('Last-Modified:'.$time);
    header('Cache-Control:max-age='.$seconds);
    header('ETag:'.md5($ret.$time));
    echo $ret;
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    response('请返回重试！code: 1001');
}

$host = strtolower($host);
$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = getAppid($domain);

$whereStr = "`domain` = '".$domain."'";
if($appid){
    $whereStr = "`appid` = '".$appid."'";
}

$sql = "SELECT `appid`,`name`,`ver`,`typeid`,`wx_share`,`wx_appid`,`wx_appsecret`,`jsapi_token`,`jsapi_ticket` FROM `the_app_sets` WHERE ".$whereStr." AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    response('当前应用不存在！code: 1003');
}
$appData = $ret[0];
if(!$appData['wx_share'] ){
    response('暂未启用微信分享！code: 1005');
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

function getJsApiToken($appData) {
    global $db;
    $tokenData = $appData['jsapi_token'];
    if(gettype(json_decode($tokenData, 1)) == 'array' ){
        $data = json_decode($tokenData, 1);
    }else{
        $data = array('expire' => 0, 'token' => '');
    }
    if ($data['expire'] < time()) {
        // 如果是企业号用以下URL获取access_token
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$appData['wx_appid']."&corpsecret=".$appData['wx_appsecret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appData['wx_appid']."&secret=".$appData['wx_appsecret'];
        $res = httpsReq($url);
        $token = $res['access_token'];
        if ($token) {
            $data['expire'] = time() + 7000;
            $data['token'] = $token;
            //保存到db
            $db->update('the_app_sets',array($appData['appid'], array('jsapi_token' => json_encode($data))));
        }
    } else {
        $token = $data['token'];
    }
    return $token;
}

function getJsApiTicket($appData) {
    global $db;
    
    $ticketData = $appData['jsapi_ticket'];
    if(gettype(json_decode($ticketData, 1)) == 'array' ){
        $data = json_decode($ticketData, 1);
    }else{
        $data = array('expire' => 0, 'ticket' => '');
    }
    if ($data['expire'] < time()) {
        $token = getJsApiToken($appData);
        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=".$token;
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$token;
        $res = httpsReq($url);
        $ticket = $res['ticket'];
        if ($ticket) {
            $data['expire'] = time() + 7000;
            $data['ticket'] = $ticket;
            //保存到db
            $db->update('the_app_sets',array($appData['appid'], array('jsapi_ticket' => json_encode($data))));
        }
    } else {
        $ticket = $data['ticket'];
    }
    return $ticket;
}

function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

function getSignPackage($appData) {
    $jsapiTicket = getJsApiTicket($appData);
    $url = request('cur_url');
    if(!$url){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    $timestamp = time();
    $nonceStr = createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
        "appId"     => $appData['wx_appid'],
        "nonceStr"  => $nonceStr,
        "timestamp" => $timestamp,
        "url"       => $url,
        "signature" => $signature,
        "rawString" => $string
    );
    return $signPackage; 
}
response(getSignPackage($appData));
