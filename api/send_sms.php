<?php
/**
 * 发送短信验证码
 *
 * 命令 ：nohup /usr/local/php/bin/php /data/wwwroot/saas/api/send_sms.php > /data/wwwroot/saas/api/logs/send_sms.txt &
 * @author funfly
 */
set_time_limit(0);                      //超时设置：采集大量数据时用到
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html; charset=utf-8');
include __DIR__.'/config/config.php';
include __DIR__.'/libraries/utils.php';
include __DIR__.'/libraries/mysqli.php';

set_error_handler("errorHandler");	     //设置异常捕捉函数

class send_sms {
    
    function __construct() {
      
    }
    
    function send($re){
        if($re['status'] != 0){
            return ;    
        }
        $conf = $this->getConf($re['appid']);
        if($conf['sms'] != 1 || $conf['sms_num'] < 1){
            $this->db->update('the_sms',array($re['smsid'],array('status' => -3 )));        //无法发送
            return ;                //未开启短信发送或短信发送剩余量不足
        }
        if($conf['name']){
            $conf['name'] = mb_convert_encoding($conf['name'], 'gb2312', 'utf-8');
        }
        if($conf['sms_sign']){
            $conf['sms_sign'] = mb_convert_encoding($conf['sms_sign'], 'gb2312', 'utf-8');
        }else{
            $conf['sms_sign'] = '叮叮网';    //默认签名
        }
        if($re['appid'] == 1 || $conf['name'] == $conf['sms_sign']){
            $conf['name'] = '';             //应用id为1或签名和name一致时，短信内容不下发name信息
        }
        if($conf['sms_tail']){
            $conf['sms_tail'] = mb_convert_encoding($conf['sms_tail'], 'gb2312', 'utf-8');
        }
        $trader_title = '分销商';
        if(isset($conf['trader']) && isset($conf['trader']['txt']) && $conf['trader']['txt']){
            $trader_title = $conf['trader']['txt'];
            $trader_title = mb_convert_encoding($trader_title, 'gb2312', 'utf-8');
        }
        
        //sort: 短信分类；0=注册验证码; 1=重置密码验证码; 2=登录验证码; 3=注册密码; 4=申请分销商; 5=分销商审核通过; 6=分销商身份被冻结; 7=发货通知; 8=发红包/优惠券通知; 9=自动设为已收货; 10=自动评价
        $url = "http://112.74.134.17:8860";
        $username = '700032';
        //$url = "http://112.74.134.174:8860";
        //$username = '151125';
        $phone = $re['phone'];
        
        if($re['sort'] == 10){
            if($re['data'] && gettype(json_decode($re['data'], 1)) == 'array'){
                $sdt = json_decode($re['data'], 1);    
            }
            $adt = '';
            $order_number = '';
            if($sdt['order_number']){
                $sdt['order_number'] = mb_convert_encoding($sdt['order_number'], 'gb2312', 'utf-8');
                $order_number = ''.$sdt['order_number'].'';
            }
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的订单'.$order_number.'已被系统自动设置为“完成”状态。'.$conf['sms_tail'];
        }else if($re['sort'] == 9){
            if($re['data'] && gettype(json_decode($re['data'], 1)) == 'array'){
                $sdt = json_decode($re['data'], 1);    
            }
            $adt = '';
            $order_number = '';
            if($sdt['order_number']){
                $sdt['order_number'] = mb_convert_encoding($sdt['order_number'], 'gb2312', 'utf-8');
                $order_number = ''.$sdt['order_number'].'';
            }
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的订单'.$order_number.'已被系统自动设置为“已收货”状态。'.$conf['sms_tail'];
        }else if($re['sort'] == 8){
            if($re['data'] && gettype(json_decode($re['data'], 1)) == 'array'){
                $sdt = json_decode($re['data'], 1);    
            }
            $adt = '';
            if($sdt['words']){
                $sdt['words'] = mb_convert_encoding($sdt['words'], 'gb2312', 'utf-8');
                $content = '【'.$conf['sms_sign'].'】'.$sdt['words'].' '.$conf['sms_tail'];
            }
        }else if($re['sort'] == 7){   
            if($re['data'] && gettype(json_decode($re['data'], 1)) == 'array'){
                $sdt = json_decode($re['data'], 1);    
            }
            $adt = '';
            $order_number = '';
            if($sdt['order_number']){
                $sdt['order_number'] = mb_convert_encoding($sdt['order_number'], 'gb2312', 'utf-8');
                $order_number = ''.$sdt['order_number'].'';
            }
            if($sdt['delivery_company']){
                $sdt['delivery_company'] = mb_convert_encoding($sdt['delivery_company'], 'gb2312', 'utf-8');
                $adt .= '快递：'.$sdt['delivery_company'].'，';
            }
            if($sdt['delivery_sn']){
                $sdt['delivery_sn'] = mb_convert_encoding($sdt['delivery_sn'], 'gb2312', 'utf-8');
                $adt .= '单号：'.$sdt['delivery_sn'].'，';
            }
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的订单'.$order_number.'已发货，'.$adt.'请注意查收。'.$conf['sms_tail'];
        }else if($re['sort'] == 6){
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的'.$trader_title.'资格已被冻结，如有疑问，请联系客服。'.$conf['sms_tail'];
        }else if($re['sort'] == 5){
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的'.$trader_title.'资格已生效，您可以在“我是'.$trader_title.'”里查看并分享您的海报。'.$conf['sms_tail'];
        }else if($re['sort'] == 4){
            $content = '【'.$conf['sms_sign'].'】感谢您申请成为我们'.$conf['name'].'的'.$trader_title.'，我们的工作人员将与您进一步联系。'.$conf['sms_tail'];
        }else if($re['sort'] == 3){
            $content = '【'.$conf['sms_sign'].'】您'.$conf['name'].'的登录帐号 '.$phone.'，密码 '.$re['code'].'，如非本人操作，请忽略本短信。'.$conf['sms_tail'];
        }else if($re['sort'] == 2 || $re['sort'] == 1 || $re['sort'] == 0){
            $content = '【'.$conf['sms_sign'].'】'.$conf['name'].'验证码 '.$re['code'].'，请您在5分钟内填写。如非本人操作，请忽略本短信。'.$conf['sms_tail'];
        }
        if(!$content){
            $this->db->update('the_sms',array($re['smsid'],array('status' => -2 )));        //无法发送
            return ;    
        }
        $content = mb_convert_encoding($content, 'utf-8', 'gb2312');
        $password = '6GEK54IR3V';
        $data['cust_code'] = $username;
        $data['destMobiles'] = $phone;
        $data['content'] = $content;
        $data['sp_code'] = '';
        $data['sign'] = md5(urlencode($content) . $password);
        
        $ch = curl_init ();    
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $o="";
        foreach ($data as $k=>$v){
            if($k=='destMobiles'){
                $o.= "$k=".$v."&";
            }else{
                $o.= "$k=".urlencode($v)."&";
            }
        }
        $post_data=substr($o,0,-1);
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $rep = curl_exec ( $ch );
        $success = false;
        if(!curl_errno($ch)){
            $info = curl_getinfo($ch);
            if($info['http_code'] == 200){
                $rep = urldecode($rep);
                $rep = explode(':',$rep);
                if($rep[0] == 'SUCCESS'){
                    $success = true;
                }
            }
        }
        curl_close ( $ch );
        if($success){
            $this->db->update('the_sms',array($re['smsid'],array('status' => 1 )));         //发送成功
            $sql = "UPDATE `the_app_sets` SET `sms_num` = `sms_num`- 1, `sms_send` = `sms_send`+ 1 WHERE appid='".$re['appid']."'";
            $this->db->updateX($sql);                                                       //扣除短信发送量
        }else{
            $this->db->update('the_sms',array($re['smsid'],array('status' => -1 )));        //发送失败
        }
        return;
    }
    
    function connectMysql(){
        global $dbConfig;
        $db = new mysql;
        $db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);
        $this->db = $db;
    }
    
    function getData(){
        return $this->db->getX('SELECT * FROM `the_sms` WHERE `flag` = 1 AND `status` = 0 ORDER BY `smsid` DESC LIMIT 100 ');
    }
    
    //获取配置信息
    function getConf($appid){
        $data = array();
        $sql = "SELECT `the_app_sets`.`name`,`the_app_sets`.`sms`,`the_app_sets`.`sms_num`,`the_app_sets`.`sms_sign`,`the_app_sets`.`sms_tail`,`the_app_conf`.`trader` FROM `the_app_sets`,`the_app_conf` WHERE `the_app_sets`.`appid` = '".$appid."' AND  `the_app_conf`.`appid` = '".$appid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            $data['trader'] = myjson_decode($ret[0]['trader']);
        }
        return $data;
    }
    
    function run(){
        $this->connectMysql();
        $res = $this->getData();
        if(!count($res)){
            //echo '-';
            sleep(5);
        }else{
            $i = 0;
            foreach($res as $re){
                $this->send($re);
                //echo '-'.$i;
                $i++;
                if($i >= 10){
                    sleep(1);
                }
            }
        }
        $this->run();
    }
    
}

echo 'Run at '.date('Y-m-d H:i:s');
$send_sms = new send_sms;
$send_sms->run();
