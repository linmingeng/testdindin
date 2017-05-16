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

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

$user_info = array(
    'user_type' => 0,
    'userid' => 0,
    'username' => 'guest',
    'nickname' => 'guest',
    'login_ip' => '',
    'login_at' => 0,
);
//获取登录用户信息
$auth_data = '';
if( ! empty($_SERVER['HTTP_AUTH'])) {
    $auth_data = $_SERVER['HTTP_AUTH'];
}else if( ! empty($_COOKIE[$cookiepre.'auth'])) {
    $auth_data = $_COOKIE[$cookiepre.'auth'];
}else if( ! empty($_SERVER['HTTP_WX_AUTH'])) {
    $auth_data = $_SERVER['HTTP_WX_AUTH'];
}else if( ! empty($_COOKIE[$cookiepre.'wx_auth'])) {
    $auth_data = $_COOKIE[$cookiepre.'wx_auth'];
} 
if($auth_data){
    $auth_data = explode(" ", myEncode($auth_data, 'DECODE'));
}
if(is_array($auth_data) && count($auth_data)){
    $user_info = array(
        'user_type' => $auth_data[0],
        'userid' => $auth_data[1],
        'username' => $auth_data[2],
        'nickname' => $auth_data[3],
        'login_ip' => $auth_data[4],
        'login_at' => $auth_data[5],
    );
}

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

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$host = strtolower($host);
$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = getAppid($domain);

$whereStr = "`domain` = '".$domain."'";
if($appid){
    $whereStr = "`appid` = '".$appid."'";
}

$sql = "SELECT `appid`,`name`,`ver`,`typeid`,`wx_appid`,`wx_appsecret`,`jsapi_token`,`jsapi_ticket` FROM `the_app_sets` WHERE ".$whereStr." AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    response('当前应用不存在！code: 1003');
}
$appData = $ret[0];
if($appData['wx_appid'] == '' || $appData['wx_appid'] == 'NULL' || $appData['wx_appsecret'] == '' || $appData['wx_appsecret'] == 'NULL' ){
    response('暂未启用二维码！code: 1004');
}
function httpsReq($url, $json = true, $data = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    if($data){
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    }
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

function getJsApiTicket($appData, $scene_str) {
    global $db;
    
    $token = getJsApiToken($appData);
    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
    if($scene_str){
        $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$scene_str.'"}}}';
    }else{
        response('缺少参数！scene_str');
    }
    $res = httpsReq($url, 1, $data);
    return $res;
}

function getQrcodeData($appData, $scene_str) {
    global $db;
    
    $res = getJsApiTicket($appData, $scene_str);
    if(is_array($res) && isset($res['ticket'])){
        $res['qrcode'] = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$res['ticket'];    
    }
    return $res;
}

//判断是否分销商
$sql = "SELECT `traderid`,`status`,`qrcode` FROM `the_trader` WHERE `userid` = '".$user_info['userid']."' AND `appid` = '".$appData['appid']."' ";
$ret = $db->getX($sql);
$traderData = array();
if(is_array($ret) && count($ret)){
    $traderData = $ret[0];
}
if(isset($traderData['status']) && $traderData['status'] == 1 ){
    if($traderData['qrcode']){
        unset($traderData['status']);
        response($traderData);
    }else{
        $qrDt = getQrcodeData($appData, 'dindin_'.$appData['appid'].'_'.$user_info['userid']);
        if(isset($qrDt['qrcode']) && isset($qrDt['ticket']) && $qrDt['qrcode'] && $qrDt['ticket']){
            $db->update('the_trader', array($traderData['traderid'], array('qrcode' => $qrDt['qrcode'], 'row_url' => $qrDt['url'], 'ticket' => $qrDt['ticket'])));
            response($qrDt);
        }else{
            response('生成二维码失败，请稍后重试！code: 1006');
        }
    }
}else{
    response('对不起，您没有资格生成二维码！code: 1007');
}
