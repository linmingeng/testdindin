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
$guest_info = array(
    'user_type' => 0,
    'userid' => 0,
    'username' => 'guest',
    'nickname' => 'guest',
    'login_ip' => '',
    'login_at' => 0,
);
$user_info = $guest_info;
//获取登录用户信息
$auth_data = '';
$session_key = '';
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
    $session_key = md5($auth_data);
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

include './libraries/session.php';
$session = new session;
/* 无法识别微信登录的用户，暂时注释
if($user_info['userid'] && $session_key){
    print_r($user_info);
    print_r($session_key);
    if(getIp() != $user_info['login_ip']){                                  //ip有变化
        $session->del($session_key);
        $user_info = $guest_info;
    }else{
        $session_userid = $session->get($session_key);                      //获取session信息
        print_r($session_userid);
        if(!$session_userid || $session_userid != $user_info['userid'] ){   //session信息过期
            $session->del($session_key);
            $user_info = $guest_info;
        }
    }
}
*/

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
            
            //统一60s的cache
            $global_ttl = 60;
            header("Expires: ".gmdate('D, d M Y H:i:s', time() + $global_ttl) . ' GMT');
            header('Last-Modified:'. gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control:max-age='.$global_ttl);
            //header('ETag:'.md5($ret));
            
            echo $ret;
        }
    }
} else {
    error(404);
}