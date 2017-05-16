<?php
/**
 * 发送失败的短信验证码回调
 *
 * 命令 ：nohup /usr/local/php/bin/php /data/wwwroot/saas/api/send_sms.php > /data/wwwroot/saas/api/logs/send_sms.txt &
 * @author funfly
 */
//set_time_limit(0);                      //超时设置：采集大量数据时用到
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html; charset=utf-8');
include __DIR__.'/config/config.php';
include __DIR__.'/libraries/utils.php';

set_error_handler("errorHandler");	     //设置异常捕捉函数

class send_sms_notify {
    
    function __construct() {
      
    }
    
    function notify(){
        $report = request('report');
        if($report){
            $report = str_replace('^',chr(10),$report);    
            $file = './logs/send_sms_'.date('Y-m-d').'.txt';
            $handle = fopen($file,'a');
            fwrite($handle, $report, 4096);
            fclose($handle);
            echo 'success';
        }
    }
    
}

$send_sms_notify = new send_sms_notify;
$send_sms_notify->notify();
