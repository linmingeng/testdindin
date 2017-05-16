<?php
/**
 * ���Ͷ�����֤��
 *
 * ���� ��nohup /usr/local/php/bin/php /data/wwwroot/saas/api/send_sms.php > /data/wwwroot/saas/api/logs/send_sms.txt &
 * @author funfly
 */
set_time_limit(0);                      //��ʱ���ã��ɼ���������ʱ�õ�
ini_set("track_errors" ,true);			       //��������
date_default_timezone_set('PRC');       //����ʱ��
header('Content-type: text/html; charset=utf-8');
include __DIR__.'/config/config.php';
include __DIR__.'/libraries/utils.php';
include __DIR__.'/libraries/mysqli.php';

set_error_handler("errorHandler");	     //�����쳣��׽����

class send_sms {
    
    function __construct() {
      
    }
    
    function send($re){
        if($re['status'] != 0){
            return ;    
        }
        $conf = $this->getConf($re['appid']);
        if($conf['sms'] != 1 || $conf['sms_num'] < 1){
            $this->db->update('the_sms',array($re['smsid'],array('status' => -3 )));        //�޷�����
            return ;                //δ�������ŷ��ͻ���ŷ���ʣ��������
        }
        if($conf['name']){
            $conf['name'] = mb_convert_encoding($conf['name'], 'gb2312', 'utf-8');
        }
        if($conf['sms_sign']){
            $conf['sms_sign'] = mb_convert_encoding($conf['sms_sign'], 'gb2312', 'utf-8');
        }else{
            $conf['sms_sign'] = '������';    //Ĭ��ǩ��
        }
        if($re['appid'] == 1 || $conf['name'] == $conf['sms_sign']){
            $conf['name'] = '';             //Ӧ��idΪ1��ǩ����nameһ��ʱ���������ݲ��·�name��Ϣ
        }
        if($conf['sms_tail']){
            $conf['sms_tail'] = mb_convert_encoding($conf['sms_tail'], 'gb2312', 'utf-8');
        }
        $trader_title = '������';
        if(isset($conf['trader']) && isset($conf['trader']['txt']) && $conf['trader']['txt']){
            $trader_title = $conf['trader']['txt'];
            $trader_title = mb_convert_encoding($trader_title, 'gb2312', 'utf-8');
        }
        
        //sort: ���ŷ��ࣻ0=ע����֤��; 1=����������֤��; 2=��¼��֤��; 3=ע������; 4=���������; 5=���������ͨ��; 6=��������ݱ�����; 7=����֪ͨ; 8=�����/�Ż�ȯ֪ͨ; 9=�Զ���Ϊ���ջ�; 10=�Զ�����
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
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'�Ķ���'.$order_number.'�ѱ�ϵͳ�Զ�����Ϊ����ɡ�״̬��'.$conf['sms_tail'];
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
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'�Ķ���'.$order_number.'�ѱ�ϵͳ�Զ�����Ϊ�����ջ���״̬��'.$conf['sms_tail'];
        }else if($re['sort'] == 8){
            if($re['data'] && gettype(json_decode($re['data'], 1)) == 'array'){
                $sdt = json_decode($re['data'], 1);    
            }
            $adt = '';
            if($sdt['words']){
                $sdt['words'] = mb_convert_encoding($sdt['words'], 'gb2312', 'utf-8');
                $content = '��'.$conf['sms_sign'].'��'.$sdt['words'].' '.$conf['sms_tail'];
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
                $adt .= '��ݣ�'.$sdt['delivery_company'].'��';
            }
            if($sdt['delivery_sn']){
                $sdt['delivery_sn'] = mb_convert_encoding($sdt['delivery_sn'], 'gb2312', 'utf-8');
                $adt .= '���ţ�'.$sdt['delivery_sn'].'��';
            }
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'�Ķ���'.$order_number.'�ѷ�����'.$adt.'��ע����ա�'.$conf['sms_tail'];
        }else if($re['sort'] == 6){
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'��'.$trader_title.'�ʸ��ѱ����ᣬ�������ʣ�����ϵ�ͷ���'.$conf['sms_tail'];
        }else if($re['sort'] == 5){
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'��'.$trader_title.'�ʸ�����Ч���������ڡ�����'.$trader_title.'����鿴���������ĺ�����'.$conf['sms_tail'];
        }else if($re['sort'] == 4){
            $content = '��'.$conf['sms_sign'].'����л�������Ϊ����'.$conf['name'].'��'.$trader_title.'�����ǵĹ�����Ա��������һ����ϵ��'.$conf['sms_tail'];
        }else if($re['sort'] == 3){
            $content = '��'.$conf['sms_sign'].'����'.$conf['name'].'�ĵ�¼�ʺ� '.$phone.'������ '.$re['code'].'����Ǳ��˲���������Ա����š�'.$conf['sms_tail'];
        }else if($re['sort'] == 2 || $re['sort'] == 1 || $re['sort'] == 0){
            $content = '��'.$conf['sms_sign'].'��'.$conf['name'].'��֤�� '.$re['code'].'��������5��������д����Ǳ��˲���������Ա����š�'.$conf['sms_tail'];
        }
        if(!$content){
            $this->db->update('the_sms',array($re['smsid'],array('status' => -2 )));        //�޷�����
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
            $this->db->update('the_sms',array($re['smsid'],array('status' => 1 )));         //���ͳɹ�
            $sql = "UPDATE `the_app_sets` SET `sms_num` = `sms_num`- 1, `sms_send` = `sms_send`+ 1 WHERE appid='".$re['appid']."'";
            $this->db->updateX($sql);                                                       //�۳����ŷ�����
        }else{
            $this->db->update('the_sms',array($re['smsid'],array('status' => -1 )));        //����ʧ��
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
    
    //��ȡ������Ϣ
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
