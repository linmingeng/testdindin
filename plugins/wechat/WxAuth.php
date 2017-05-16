<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html;charset=utf-8');
if(isset($APP_PATH)){
    $APP_PATH .= '/plugins';
}else{
    $APP_PATH = substr(__FILE__, 0, -19);
}
include $APP_PATH.'/config/config.php';
include $APP_PATH.'/libraries/utils.php';
include $APP_PATH.'/libraries/mysqli.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$protocol = 'http:';
if(isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
    $protocol = 'https:';
}

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

function showError($msg){
    echo '<span style="color:#EF4F4F;font-size:24px">'.$msg.'</span>';
    echo '<br><br><button style="width:100%; padding:0 14px; height:42px; border-radius: 5px;background-color:#f7f7f7; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#454545;  font-size:16px;" type="button" onclick="window.locatioin.href=\''.$protocol.'//www.dindin.com\'" >请访问叮叮网</button>';
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    showError('请返回重试！code: 1001');
}

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

$host = strtolower($host);
if(substr($host,0,4) == 'test'){
    $page_url = $protocol."//".$host."/www/";        //开发测试环境
    header('location:'.$page_url);
    exit;
}

$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = getAppid($domain);

$whereStr = "`domain` = '".$domain."'";
if($appid){
    $whereStr = "`appid` = '".$appid."'";
}
$sql = "SELECT `appid`,`name`,`ver`,`typeid`,`wx_login`,`wx_appid` FROM `the_app_sets` WHERE ".$whereStr." AND `flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    header('location:'.$protocol.'//m.dindin.com/');   //默认访问叮叮网
    return ;
    //showError('当前应用不存在！code: 1003');
}
$appData = $ret[0];

function is_weixin(){ 
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }    
    return false;
}

function getConf($k){
    global $appData;
    $conf = array(
        'APPID' => $appData['wx_appid']
    );
    if(!isset($conf[$k])){
        showError('配置信息“'.$k.'”不存在！code: 1005');
    }
    return $conf[$k];
}

$page_url = $protocol."//".$host."/".$appData['ver']."/";

if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != ''){
    $_SERVER['QUERY_STRING'] = strtolower($_SERVER['QUERY_STRING']);
    $_SERVER['QUERY_STRING'] = str_replace('&uri=','#',$_SERVER['QUERY_STRING']);
    $_SERVER['QUERY_STRING'] = str_replace('?uri=','#',$_SERVER['QUERY_STRING']);
    $page_url .= "?".str_replace('uri=','#',$_SERVER['QUERY_STRING']);
}

if(isset($_REQUEST['bind'])){
    $bind = $_REQUEST['bind'];
}

//已登录，直接访问APP
if($user_info['userid'] > 0 && !$bind){
    header('location:'.$page_url);
    exit;
}

if(is_weixin() && $appData['wx_login'] &&  $appData['wx_appid'] ){        //在微信中访问：需要授权
    $redirect_uri = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.getConf('APPID').'&redirect_uri='.urlencode($page_url).'&response_type=code&scope=snsapi_userinfo&state=STATE&connect_redirect=1#wechat_redirect';
    header('location:'.$redirect_uri);
}else{
    header('location:'.$page_url);
}
