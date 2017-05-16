<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html;charset=utf-8');
if(isset($APP_PATH)){
    $APP_PATH .= '/plugins';
}else{
    $APP_PATH = substr(__FILE__, 0, -11);
}
include $APP_PATH.'/config/config.php';
include $APP_PATH.'/libraries/utils.php';
include $APP_PATH.'/libraries/mysqli.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

function showError($msg){
    echo '<span style="color:#EF4F4F;font-size:24px">'.$msg.'</span>';
    echo '<br><br><button style="width:100%; padding:0 14px; height:42px; border-radius: 5px;background-color:#f7f7f7; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#454545;  font-size:16px;" type="button" onclick="window.locatioin.href=\'http://www.dindin.com\'" >请访问叮叮网</button>';
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    showError('请返回重试！code: 1001');
}

$host = strtolower($host);
$domain = str_replace('www.','',$host);
$domain = str_replace('test.','',$domain);

$appid = (int)$_REQUEST["appid"];       //url里获取appid
if(!$appid){
    $appid = getAppid($domain);         //域名里获取appid
}
if($appid){
    $appDomain = $appid.".dindin.com";
}else{
    $appDomain = $domain;
}

$whereStr = "`the_app_sets`.`domain` = '".$domain."'";
if($appid){
    $whereStr = "`the_app_sets`.`appid` = '".$appid."'";
}
$whereStr .= " AND `the_app`.`appid` = `the_app_sets`.`appid` ";

$sql = "SELECT `the_app_sets`.`appid`,`the_app_sets`.`typeid`,`the_app_sets`.`ver`,`the_app_sets`.`ios_url`,`the_app_sets`.`android_url`,`the_app_sets`.`qrcode_url`,`the_app`.`name`,`the_app`.`slogon`,`the_app`.`description`,`the_app`.`logo` FROM `the_app_sets`,`the_app` WHERE ".$whereStr." AND `the_app`.`check` = 1 AND `the_app`.`flag` = 1 ";
$ret = $db->getX($sql);
if(!is_array($ret) || !count($ret)){
    showError('当前应用不存在！code: 1003');
}
$appData = $ret[0];

$statics_server = "http://cdn.dindin.com/"; //CDN加速服务
$img_url = $statics_server."plugins";
if(!$appData['logo'] || $appData['logo'] == 'NULL'){
    $appData['logo'] = '../portal/img/logo.png ';
}
function getImgUrl($url){
    global $statics_server;
    if(!$url){
        return $url;    
    }
    if(strpos(strtolower($url), 'http://') === false){
        if(strpos(strtolower($url), 'upload') === false && strpos(strtolower($url), 'download') === false && strpos(strtolower($url), 'portal') === false){
            $url = $statics_server.'plugins/'.$url;
        }else{
            $url = $statics_server.str_replace('../','',$url);
        }
    }
    $url = str_replace($statics_server.'/',$statics_server,$url);
    return $url;
}
$appData['logo'] = getImgUrl($appData['logo']);
$appData['ios_url'] = getImgUrl($appData['ios_url']);
$appData['android_url'] = getImgUrl($appData['android_url']);
$appData['qrcode_url'] = getImgUrl($appData['qrcode_url']);

$page_url = "http://".$host."/";
$to = strtolower(trim(request('to')));

if($to == 'wechat'){
    //todo: 显示二维码
}else if($to == 'down'){
    include $APP_PATH.'/tpls/down.tpl.php';
}else {
    showError('参数异常！code: 1005, to: '.request('to'));
}