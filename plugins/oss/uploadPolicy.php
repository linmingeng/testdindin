<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include './lib/policy.php';

$policy = new policy;
$policy->userid = 302;
$po = $policy->get();
echo(json_encode($po));
