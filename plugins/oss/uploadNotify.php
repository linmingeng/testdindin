<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include './lib/notify.php';

$notify = new notify;
$res = $notify->get();
if($res && is_array($res)){
    echo(json_encode($res));
}else{
    header("http/1.1 403 Forbidden");
}
