<?php
require_once BASE_PATH.'/modules/order_module.php';
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';
require_once BASE_PATH.'/modules/income_module.php';
/**
 * 订单控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class order {
    
    function __construct(){
        $this->order_module = new order_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/order/list&page=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'order:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->order_module->getOrder($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/order/detail/orderid/1
    function detail_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        $orderid = (int)request('orderid');
        $orderid = max($orderid,1);
        $key = 'order_detail:'.$appid.':'.$user_info['userid'].':'.$orderid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->order_module->getDetail($appid, $user_info['userid'], $orderid);  
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/order/add
    function add_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $key = 'order:'.$appid.':'.$user_info['userid'].':1';
        $cache->del($key);                                  //清除缓存
        
        return $this->order_module->addNow($appid, $user_info['userid']); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/order/del/orderid/1
    function del_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $orderid = (int)request('orderid');
        $ret = $this->order_module->delNow($appid, $user_info['userid'], $orderid); 
        if($ret['status']){
            $key = 'order:'.$appid.':'.$user_info['userid'].':1';
            $cache->del($key);                                  //清除缓存
            
            $key = 'order_detail:'.$appid.':'.$user_info['userid'].':'.$orderid;
            $cache->del($key);                                  //清除缓存
        }
        return $ret;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/order/received/orderid/1
    function received_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $orderid = (int)request('orderid');
        $appid = (int)request('appid');
        
        $status = $this->order_module->received($appid, $user_info['userid'], $orderid); 
        if($status){
            $key = 'order:'.$appid.':'.$user_info['userid'].':1';
            $cache->del($key);                                  //清除缓存
            
            $key = 'order_detail:'.$appid.':'.$user_info['userid'].':'.$orderid;
            $cache->del($key);                                  //清除缓存
            
            return array('status' => $status); 
        }else{
            return "请求失败！"; 
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/order/comment
    function comment_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $orderid = (int)request('orderid');
        $appid = (int)request('appid');
        $star = (int)request('star');
        $comment = trim(request('comment'));
        
        $status = $this->order_module->comment($appid, $user_info['userid'], $orderid, $star, $comment); 
        if($status){
            $key = 'order:'.$appid.':'.$user_info['userid'].':1';
            $cache->del($key);                                  //清除缓存
            
            $key = 'order_detail:'.$appid.':'.$user_info['userid'].':'.$orderid;
            $cache->del($key);                                  //清除缓存
            $score = 0;
            $prize_money = 0;

            $prizeData = $this->order_module->getPrizeData($appid, $user_info['userid'], $orderid);
            if(is_array($prizeData)){
                         
                //获取应用配置
                $app_conf_module = new app_conf_module;
                $conf = $app_conf_module->getConf($appid, array('member','trader'));
                $updateOrderDt = array();
                
                //发放积分、经验
                if(isset($conf['member']) && $conf['member']['open'] == 1 && $prizeData['score_status'] == 0 && ($prizeData['score'] > 0 || $prizeData['exp'] > 0 ) ){
                    $app_user_module = new app_user_module;
                    $state = $app_user_module->addScore($appid, $user_info['userid'], $prizeData['score'], $prizeData['exp']);
                    if($state){
                        $updateOrderDt['score_status'] = 1;
                        $updateOrderDt['score_time'] = time();
                        $score_log_module = new score_log_module;
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
                        $ok = $score_log_module->addNow($dt);   //添加积分日志
                        if($ok){
                            $score = $prizeData['score'];
                        }
                    }
                }
                
                //分销商奖励
                if(isset($conf['trader']) && $conf['trader']['open'] == 1 && $prizeData['prize_status'] == 0 && $prizeData['prize_money'] > 0 && $prizeData['inviter_uid'] > 0){
                    $income_module = new income_module;
                    $ok = $income_module->sendOrderPrize($appid, $prizeData['inviter_uid'], $prizeData['orderid'], $prizeData['prize_money']);   //发放订单提成
                    if($ok){
                        $updateOrderDt['prize_status'] = 1;
                        $prize_money = $prizeData['prize_money'];
                        //todo 通知分销商：提成已到账
                    }
                }
                
                //返现到帐户余额 TODO 未实现??
                
                if(count($updateOrderDt)){
                    $this->order_module->update(array($re['orderid'], $updateOrderDt));  //更新订单的奖励发放状态
                }
            }
            
            return array('status' => $status,'score' => $score,'prize_money' => $prize_money); 
        }else{
            return "评价失败！请刷新后重试！"; 
        }
    } 
    
}