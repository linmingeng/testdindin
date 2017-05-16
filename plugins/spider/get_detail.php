<?php 
set_time_limit(0);                      //超时设置：采集大量数据时用到
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

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

function req($url, $json = true){
    $header = array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
    $ch = curl_init ();    
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 0 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_HTTPHEADER,$header);
    $rep = curl_exec ( $ch );
    $success = false;
    if(!curl_errno($ch)){
        $info = curl_getinfo($ch);
        if($info['http_code'] == 200){
            if($json){
                if(gettype(json_decode($rep, 1)) == 'array' ){
                    return json_decode($rep, 1);
                }else{
                    return ;    
                }
            }
            return $rep;
        }
    }
    curl_close ( $ch );
}

function getTags($str){
    //preg_match_all ("|<img src=\"(.*)\">|U",$str,$out, PREG_PATTERN_ORDER);
    preg_match_all ("|<dd class=\"pro-bq\">(.*)</dd>|U",$str,$out, PREG_PATTERN_ORDER);
    print_r($out);
    exit;
    if(is_array($out) && isset($out[1])  && isset($out[1][0]) ){
        return '<img src="'.$out[1][0].'">';    
    }
    return '';
}
function getCountry($str){
    preg_match_all ("|<img src=\"(.*)\">|U",$str,$out, PREG_PATTERN_ORDER);
    //preg_match_all ("|align=\"absmiddle\" />&nbsp;(.*) | </b>|U",$str,$out, PREG_PATTERN_ORDER);
    print_r($out);
    exit;
    if(is_array($out) && isset($out[1])  && isset($out[1][0]) ){
        return '<img src="'.$out[1][0].'">';    
    }
    return '';
}

$sql = "SELECT `id` FROM `sh_product` WHERE `flag` = 0 ";
$ret = $db->getX($sql);
foreach($ret as $re){
    $url = 'http://m.saohuo7.com/pro_detail.aspx?id='.$re['id'];
    $body = req($url,false);
    $body = str_replace("'","\'",$body);
    $sql = "UPDATE `sh_product` SET `body` = '".$body."'  WHERE `id` = '".$re['id']."' ";
    $db->updateX($sql);
}

