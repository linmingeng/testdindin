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

function getInfo($str){
    preg_match_all ("/<div class=\"pro-intro\" style=\"min-height: 300px; height: auto;\">([\s\S]*?)<\/div>/",$str,$out, PREG_PATTERN_ORDER);
    if(is_array($out) && isset($out[1])  && isset($out[1][0]) ){
        return $out[1][0];    
    }
    return '';
}
function getTags($str){
    preg_match_all ("/<dd class=\"pro-bq\">([\s\S]*?)<\/dd>/",$str,$out, PREG_PATTERN_ORDER);
    if(is_array($out) && isset($out[1])  && isset($out[1][0]) ){
        preg_match_all ("/<em>([\s\S]*?)<\/em>/",$out[1][0],$out, PREG_PATTERN_ORDER);
        if(is_array($out) && isset($out[1]) ){
            return implode(',',$out[1]);
        }
    }
    return '';
}
function getSlogan($str){
    preg_match_all ("/<div class=\"pro-brand\">([\s\S]*?)<\/div>/",$str,$out, PREG_PATTERN_ORDER);
    if(is_array($out) && isset($out[1])  && isset($out[1][0]) ){
        preg_match_all ("/<\/em>([\s\S]*?)<\/dd>/",$out[1][0],$out, PREG_PATTERN_ORDER);
        if(is_array($out) && isset($out[1])  && isset($out[1][0])){
            return $out[1][0];
        }
    }
    return '';
}
function getImgs($str){
    preg_match_all ("/<img alt=\"\" src=\"([\s\S]*?)\" \/>/",$str,$out, PREG_PATTERN_ORDER);
    if(is_array($out) && isset($out[1])  ){
        return $out[1];
    }
    return '';
}
$sql = "SELECT `id`,`image`,`body` FROM `sh_product` WHERE `flag` = 0 ";
$ret = $db->getX($sql);
foreach($ret as $re){
    if($re['body']){
        $info = getInfo($re['body']);
        $info = str_replace("'","\'",$info);
        $tags = getTags($re['body']);
        $tags = str_replace("'","\'",$tags);
        $slogan = getSlogan($re['body']);
        $slogan = str_replace("'","\'",$slogan);
        $imgs = getImgs($info);
        $imgs[] = $re['image'];
        $imgs = json_encode($imgs);
        
        //$re['image'] = str_replace('http://m.saohuo7.com/SYSUpLoadFolder/','/upload/1/sh/',$re['image']);
        //$info = str_replace('http://m.saohuo7.com/SYSUpLoadFolder/','/upload/1/sh/',$info);
        
        $sql = "UPDATE `sh_product` SET `info` = '".$info."',`tags` = '".$tags."',`slogan` = '".$slogan."',`imgs` = '".$imgs."',`image` = '".$re['image']."', `flag`=1 WHERE `id` = '".$re['id']."' ";
        
        $db->updateX($sql);
    }
}

