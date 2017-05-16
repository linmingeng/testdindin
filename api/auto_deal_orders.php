<?php
/**
 * 自动处理订单
 *
 * 命令 ：nohup /usr/local/php/bin/php /data/wwwroot/saas/api/auto_deal_orders.php > /data/wwwroot/saas/api/logs/auto_deal_orders.txt &
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

class auto_deal_orders {
    
    private $expireDays = 7;
    private $orderExpireDays = 1;
    
    function __construct() {
      
    }
    
    //获取配置信息 TODO: cache
    function getConf($appid){
        $data = array();
        $sql = "SELECT `member`,`trader` FROM `the_app_conf` WHERE `appid` = '".$appid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            $data['member'] = myjson_decode($data['member']);
            $data['trader'] = myjson_decode($data['trader']);
        }
        return $data;
    }
    
    //增加积分 
    function addScore($appid, $userid, $score = 0, $exp = 0){
        $userid = (int)$userid;
        $score = (int)$score;
        $exp = (int)$exp;
        $setArr = array();
        if($score){
            $setArr[] = "`score` = `score` + ".$score."";
        }
        if($exp){
            $setArr[] = "`exp` = `exp` + ".$exp."";
        }
        if(!count($setArr)){
            return 0;    
        }
        $sql = "UPDATE `the_app_user` SET ".implode(' , ', $setArr).",`update_at` = '".time()."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
    
    //添加积分日志
    function addScoreLog($data){
        return $this->db->add('the_score_log',$data);   //写入记录
    }
    
    //发放订单提成
    function sendOrderPrize($appid, $userid, $orderid, $money){
        //$sql = "UPDATE `the_income` SET `status` = 1 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `money` = '".$money."' AND `type` = 1 AND `status` = 0 AND `flag` = 1 ";
        $sql = "UPDATE `the_income` SET `status` = 1 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `type` = 1 AND `status` = 0 AND `flag` = 1 ";
        $state = $this->db->updateX($sql); 
        if($state){
            $success = $this->sendMoney($appid, $userid, $money, round($money));
            if(!$success){  
                //回滚
                $sql = "UPDATE `the_income` SET `status` = 0 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `money` = '".$money."' AND `type` = 1 AND `status` = 1 AND `flag` = 1 ";
                $this->db->updateX($sql); 
            }
        }
        return $state;
    }
    
    //给分销商发提成 
    function sendMoney($appid, $userid, $money = 0, $exp = 0){
        $appid = (int)$appid;
        $userid = (int)$userid;
        $money = floatval($money);
        $exp = (int)$exp;
        $setArr = array();
        if($money){
            $setArr[] = "`money` = `money` + ".$money."";
            $setArr[] = "`income` = `income` + ".$money."";
        }
        if($exp){
            $setArr[] = "`exp` = `exp` + ".$exp."";
        }
        if(!count($setArr)){
            return 0;    
        }
        $sql = "UPDATE `the_trader` SET ".implode(' , ', $setArr)." WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' AND `status` = 1 ";
        return $this->db->updateX($sql);
    }
    
    //处理过期订单
    function dealExpiresOrder($orderData){
        $sql = "UPDATE `the_order` SET `status` = 0, `inviter_uid` = 0, `prize_money` = 0, `prize_rate` = 0, `update_at`='".time()."' WHERE `orderid` = '".$orderData['orderid']."' AND `status` = 1 ";
        $ok = $this->db->updateX($sql);
        if($ok){
            //更新分销商奖励
            if($orderData['inviter_uid'] > 0 && $orderData['prize_status'] == 0 &&  $orderData['prize_money'] > 0 ){
                $sql = 'UPDATE `the_income` SET `flag` = 0 WHERE `appid` = '.$orderData['appid'].' AND  `userid` = '.$orderData['inviter_uid'].' AND  `orderid` = '.$orderData['orderid'].'';
                $this->db->updateX($sql);
            }
        
            if(isset($orderData["goods_info"]) && $orderData["goods_info"]){
                $goods_info = $orderData['goods_info'];
                if( gettype(json_decode($goods_info,1)) == 'array'){
                    $goods_info = json_decode($goods_info,1);
                    //0=下单时减库存，1=发货时减少库存，2=付款时减库存
                    foreach($goods_info["reduce_goods"] as $gd){
                        $upStr = '';
                        if($goods_info['reduce_late'] == 0){ //reduce_late: 0=下单时减库存，发货时加出售量，1=发货时减少库存同时加出货量，2=付款时减库存，发货时加出售量
                            $upStr = ' `store` = `store` + '.$gd['quantity'].', ';
                        }
                        $upStr .= ' `orders` = `orders` - '.$gd['quantity'].' ';  //减掉未发货的数量
                        if($upStr){
                            if($gd['modelsid']){
                                $sql = 'UPDATE `the_models` SET '.$upStr.' WHERE `modelsid` = '.$gd['modelsid'].'';
                            }else{
                                $sql = 'UPDATE `the_goods` SET '.$upStr.' WHERE `goodsid` = '.$gd['goodsid'].'';
                            }
                            $this->db->updateX($sql);                   //更新商品订单占用量和销量
                        }
                    }
                }
            }
        }
        return;
    }
    
    //处理订单
    function dealOrder($re){
        if($re['status'] == 3){         //已发货，待收货;  自动设置为已收货，待评价
            $sql = "UPDATE `the_order` SET `update_at`='".time()."' , `status` = 4, `comment_status` = 1 WHERE `orderid` = '".$re['orderid']."' AND `status` = 3 ";
            $flag = $this->db->updateX($sql);
            if($flag){
                //发送通知短信
                $sms_data = array(
                    "appid" => $re['appid'],
                    "phone" => $re['phone'],
                    "sort" => 9,        //sort: 短信分类；0=注册验证码; 1=重置密码验证码; 2=登录验证码; 3=注册密码; 4=申请分销商; 5=分销商审核通过; 6=分销商身份被冻结; 7=发货通知; 8=发红包/优惠券通知; 9=自动设为已收货; 10=自动评价
                    "code" => '',
                    "data" => myjson_encode(array('name' => $re['name'], 'order_number' => $re['order_number'])),
                    "add_at" => time()
                );
                $this->db->add('the_sms', $sms_data);
            }
        }else if($re['status'] == 4){   //已收货，待评价; 自动评价并完成订单
            $sql = "UPDATE `the_order` SET `update_at`='".time()."' , `status` = 5 WHERE `orderid` = '".$re['orderid']."' AND `status` = 4 ";
            $flag = $this->db->updateX($sql);
            if($flag){
                //发送通知短信
                $sms_data = array(
                    "appid" => $re['appid'],
                    "phone" => $re['phone'],
                    "sort" => 10,        //sort: 短信分类；0=注册验证码; 1=重置密码验证码; 2=登录验证码; 3=注册密码; 4=申请分销商; 5=分销商审核通过; 6=分销商身份被冻结; 7=发货通知; 8=发红包/优惠券通知; 9=自动设为已收货; 10=自动评价并完成订单
                    "code" => '',
                    "data" => myjson_encode(array('name' => $re['name'], 'order_number' => $re['order_number'])),
                    "add_at" => time()
                );
                $this->db->add('the_sms', $sms_data);
                
                $user_info['userid'] = $re['userid'];   
                $appid = $re['appid'];
                $prizeData = $re;
                
                //获取应用配置
                $res = $this->getConf($appid);
                $conf = '[]';
                if(count($res)&& isset($res['conf']) ){
                    $conf = $res['conf'];
                }
                if(gettype(json_decode($conf, 1)) == 'array'){
                    $conf = json_decode($conf, 1);
                }
                
                $updateOrderDt = array();
                
                //发放积分、经验
                if(isset($conf['member']) == 1 && $conf['member']['open'] == 1 && $prizeData['score_status'] == 0 && ($prizeData['score'] > 0 || $prizeData['exp'] > 0 ) ){
                    $state = $this->addScore($appid, $user_info['userid'], $prizeData['score'], $prizeData['exp']);
                    if($state){
                        $updateOrderDt['score_status'] = 1;
                        $updateOrderDt['score_time'] = time();
                        $dt = array(
                            'appid' => $appid,
                            'sub_shopid' => 0,
                            'userid' => $user_info['userid'],
                            'type' => 2,      //{"array":[["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"]],"mysql":""}
                            'score' => $prizeData['score'],
                            'exp' => $prizeData['exp'],
                            'orderid' => $prizeData['orderid'],
                            'data' => json_encode(array('order_number' => $prizeData['order_number'])),
                            'add_at' => time()
                        );
                        $ok = $this->addScoreLog($dt);   //添加积分日志
                        if($ok){
                            $score = $prizeData['score'];
                        }
                    }
                }
                
                //分销商奖励
                if(isset($conf['trader']) == 1 && $conf['trader']['open'] == 1 && $prizeData['prize_status'] == 0 && $prizeData['prize_money'] > 0 && $prizeData['inviter_uid'] > 0){
                   $ok = $this->sendOrderPrize($appid, $prizeData['inviter_uid'], $prizeData['orderid'], $prizeData['prize_money']);   //发放订单提成
                    if($ok){
                        $updateOrderDt['prize_status'] = 1;
                        $prize_money = $prizeData['prize_money'];
                        //todo 通知分销商：提成已到账
                    }
                }
                
                //返现到帐户余额 TODO 未实现??
                
                if(count($updateOrderDt)){
                    $this->db->update('the_order', array($re['orderid'], $updateOrderDt));  //更新订单的奖励发放状态
                }
            }
        }
        
        return;
    }
    
    function connectMysql(){
        global $dbConfig;
        $db = new mysql;
        $db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);
        $this->db = $db;
    }
    
    function getExpireOrders(){
        $sql = 'SELECT `orderid`,`appid`,`app_name`,`userid`,`inviter_uid`,`prize_money`,`prize_status`,`goods_info`,`status` FROM `the_order` WHERE `update_at` < '.(time()-$this->orderExpireDays*86400).' AND `flag` = 1 AND `status` = 1 ORDER BY `orderid` ASC LIMIT 100 ';
        return $this->db->getX($sql);
    }
    
    function getData(){
        $sql = 'SELECT `orderid`,`order_number`,`appid`,`app_name`,`userid`,`name`,`phone`,`return_money`,`return_status`,`inviter_uid`,`prize_money`,`prize_status`,`exp`,`score`,`score_status`,`status` FROM `the_order` WHERE `update_at` < '.(time()-$this->expireDays*86400).' AND `flag` = 1 AND `status` IN (3,4) ORDER BY `orderid` ASC LIMIT 100 ';
        return $this->db->getX($sql);
    }
    
    function run(){
        $this->connectMysql();
        $res = $this->getExpireOrders();
        if(!count($res)){
            //echo '-';
        }else{
            $i = 0;
            foreach($res as $re){
                $this->dealExpiresOrder($re);
                //echo '-'.$i;
                $i++;
            }
        }
        
        $res = $this->getData();
        if(!count($res)){
            //echo '-';
            sleep(5);
        }else{
            $i = 0;
            foreach($res as $re){
                $this->dealOrder($re);
                //echo '-'.$i;
                $i++;
            }
        }
        $this->run();
    }
    
}

echo 'Run at '.date('Y-m-d H:i:s');
$auto_deal_orders = new auto_deal_orders;
$auto_deal_orders->run();
 