<?php
ini_set("track_errors", true);			       //开启错误跟踪
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include './config/config.php';
include './libraries/utils.php';
include './libraries/mysqli.php';
include '../redis/Redis_inc.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$cache = new Redis_cache();

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$_REQUEST = $_GET + $_POST;

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
   error(404);
}

/**
 * REST 接口规范：http://localhost/api/index.php?[controller]/[source]/[key]/[value]/[key]/[value]/...
 * REST 接口示例：http://localhost/api/index.php?articles/article/aid/3
 */
$_SERVER['QUERY_STRING'] = urldecode($_SERVER['QUERY_STRING']);
unset($_GET[$_SERVER['QUERY_STRING']]);
unset($_REQUEST[$_SERVER['QUERY_STRING']]);
$controller = '';
$method = '';
$form_id = '';
$mod_info = '/'.$_SERVER['QUERY_STRING'];
$mod_info = str_replace("=","/",$mod_info);
$mod_info = str_replace("&","/",$mod_info);
$mod_info = str_replace("//","/",$mod_info);
$mod_arr = explode('/',$mod_info);
if ( count($mod_arr) == 1 ) {
    $mod_arr[1] = 'index';
}
if ( $mod_arr[1] == '' ) {
    $mod_arr[1] = 'index';
}

$controller = $mod_arr[1];

foreach($mod_arr as $k => $v){
    if($k == 1){
        $controller = $mod_arr[1];
    }else if($k == 2){
        $method = $mod_arr[2];
    }else if($k % 2 == 1 && strlen($v)){
        $next_k = $k+1;
        if(isset($mod_arr[$next_k])){
            $_REQUEST[$v] = strtolower(raw_str($mod_arr[$next_k]));
        }else{
            $_REQUEST[$v] = '';
        }
    }
}

if( strpos(strtolower($_SERVER['CONTENT_TYPE']),'application/json') !== false ){
    $input = file_get_contents('php://input');
    if($input){
        $input_arr = json_decode($input,1);
        if( is_array($input_arr)){
            $_REQUEST = array_merge($_REQUEST,$input_arr);
        }
    }
}
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

/*
//生成Authorization
$time = time();
$appkey='0fa5de14e4880cc0324df4c298a9731b';
$secretkey='b8b38953c8b7ac8a87d61a6157a831ff';
$nonce = $time.':1234';
//$nonce = '1469426720:1234';

$request = ''.$nonce.''.chr(10).'GET'.chr(10).'test.www.dindin.com'.chr(10).'/api/open.php?/data/user_list&page=1'.chr(10).'';
//$request = ''.$nonce.''.chr(10).'GET'.chr(10).'test.www.dindin.com'.chr(10).'/api/open.php?/data/order_list&page=1'.chr(10).'';
//$request = ''.$nonce.''.chr(10).'GET'.chr(10).'test.www.dindin.com'.chr(10).'/api/open.php?/data/order_cancel&userid=295&orderid=37'.chr(10).'';
//$request = ''.$nonce.''.chr(10).'POST'.chr(10).'test.www.dindin.com'.chr(10).'/api/open.php?/data/order_delivery&userid=295&orderid=37'.chr(10).'';
//$request = ''.$nonce.''.chr(10).'POST'.chr(10).'test.www.dindin.com'.chr(10).'/api/open.php?/data/add_user'.chr(10).'';
$mac = base64_encode(hash_hmac('sha256', $request, $secretkey, true));
$authorization = "MAC appkey=".$appkey.",nonce=".$nonce.",mac=".$mac."";
echo 'Authorization='.$authorization.'\r\n';

exit;

/*
请求接口： GET http://test.m.dindin.com/api/open.php?/data/user_list&page=1
请求接口： GET http://test.m.dindin.com/api/open.php?/data/order_list&page=1

示例：
Authorization: MAC appkey=2YotnFZFEjr1zCsicMWpAA,nonce=1418752425123:dj83hs9s,mac=SLDJd4mg43cjQfElUs3Qub4L6xE=
                   
其中:
$appkey 为 应用唯一码
$nonce 为 时间戳 + ":" + 随机码 (客户端生成）,有效时间+-5分钟
Authorization 为请求签名:
    Authorization = TOKEN base64_encode(hash_hmac('sha256', $request, $secretkey, true));
    $request = $nonce + \n + REQUEST_METHOD + \n + HTTP_HOST + \n + REQUEST_URI + \n
        REQUEST_METHOD, 为请求的方法，大写，如 GET
        HTTP_HOST，为 http header 中的 host，区分大小写，如 dindin.com
        REQUEST_URI，为请求的地址（包含参数部分，不包含域名部分），区分大小写，如 /api/open.php?/app_user/list&page=1
    $secretkey 为 应用的密钥
        
*/
if (!function_exists('getallheaders')) {
    function getallheaders() {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

function getAuthorData(){
    $headers = getallheaders();
    if(!isset($headers['Authorization']) || !$headers['Authorization']){
        error(403, '请求header缺少Authorization');
    }
    preg_match_all("/MAC appkey=(.*),nonce=(.*),mac=(.*)/", $headers['Authorization'], $matches, PREG_SET_ORDER);
    if(isset($matches[0]) && count($matches[0]) == 4){
        return array(
            'appkey' => $matches[0][1],
            'nonce' => $matches[0][2],
            'mac' => $matches[0][3]
        );    
    }else{
        error(403, 'Authorization不合法');
    }
}

$authorData = getAuthorData();
$appkey = $authorData['appkey'];
$nonce = $authorData['nonce'];
$mac = $authorData['mac'];
if(!$appkey || !$nonce || !$mac){
    error(403, 'Authorization不合法');
}
$time = time();
$nonceArr = explode(':',$nonce);

if($nonceArr[0] > $time+60*5 || $nonceArr[0] < $time-60*5 ){
    error(403, '请求已过期，请检查nonce，或校准服务器时间');
}

//读取appkey
$keys = $cache->getData('appkey:'.$appkey);
if($keys){
    if( gettype(json_decode($keys,1)) == 'array'){
        $keys = json_decode($keys,1);
    }
}
if(!is_array($keys) || !isset($keys['appid']) || !isset($keys['secretkey'])){
    $keyRes = $db->getX("SELECT `appid`,`secretkey` FROM `the_app_key` WHERE `appkey`= '".$appkey."' ");
    if(!count($keyRes)){
        error(403, '无法识别的appkey');
    }
    $keys = $keyRes[0];
    $cache->setex('appkey:'.$appkey,3600,json_encode($keys));
}

$request = $nonce.chr(10).$_SERVER['REQUEST_METHOD'].chr(10).$_SERVER['HTTP_HOST'].chr(10).urldecode($_SERVER['REQUEST_URI']).chr(10);
$verify_mac = base64_encode(hash_hmac('sha256', $request, $keys['secretkey'], true));
if($verify_mac != $mac){
    error(403, 'mac验证失败');
}
$appid = $keys['appid'];

if($method){
    $method = $method.'_'.strtolower($_SERVER['REQUEST_METHOD']);
}else{
    $method = strtolower($_SERVER['REQUEST_METHOD']);
}
$controller_file = BASE_PATH."/controllers/".$controller.".php";

if (file_exists($controller_file)) {
    include $controller_file;
    $obj = new $controller();
    if ( ! method_exists($obj, $method)) {
        error(404);
    } else {
        if (is_callable(array($obj, $method))) {
            $ret = $obj -> $method();
            if( ! is_array($ret)){
                if(empty($ret)){
                    $ret = array();
                }else{
                    $ret = array('msg' => htmlspecialchars($ret));
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
        }
    }
} else {
    error(404);
}