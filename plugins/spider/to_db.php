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
    $ch = curl_init ();    
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 0 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
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

exit;
$type = 1;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}


$type = 2;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}


$type = 3;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}


$type = 4;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}


$type = 5;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}


$type = 6;
$data = file_get_contents('saohuo7/'.$type.'.html');
$data = json_decode($data,1);
foreach($data as $dt){
    unset($dt['id2']);
    $dt['name'] = str_replace("'","\'",$dt['name']);
    $dt['type'] = $type;
    $db->add('sh_product',$dt);   //写入记录
}

