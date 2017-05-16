<?php
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/user_level_module.php';
require_once BASE_PATH.'/modules/order_module.php';
require_once BASE_PATH.'/modules/ddcard_order_module.php';
require_once BASE_PATH.'/modules/score_log_module.php';

/**
 * 开放数据相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-07-04
 */
class data {
    
    function __construct(){
        global $cache,$appid;
        if(!$appid){
            error(403, '鉴权失败');
        }
        $this->cache = $cache;
        $this->appid = $appid;
    }
    
    //添加用户
    function add_user_post(){
        $phone = trim(request('phone'));
        $app_user_module = new app_user_module;
        return $app_user_module->user_add($this->appid, $phone);
    }
    
    //获取全部用户列表
    function user_list_get(){
        $update_at = (int)request('update_at');
        $skip = (int)request('skip');
        $app_user_module = new app_user_module;
        return $app_user_module->getLatestUser($this->appid, $update_at, $skip);
    }
    
    //积分经验同步
    function sync_score_get(){
        $userid = (int)request('userid');
        $score = (int)request('score');
        $exp = (int)request('exp');
        $logid = (int)request('logid');
        $app_user_module = new app_user_module;
        $data = array(
            "status" => 0,
            "msg" => "",
        );
        $scoreAndExp = $app_user_module->getScoreAndExp($this->appid, $userid);                             //获取可用积分和经验
        if(!is_array($scoreAndExp)){
            $data['msg'] = '用户不存在，更新失败';
        }
        
        $user_level_module = new user_level_module;
        
        if($score >= 0 && $exp >= 0){
            if($score == 0 && $exp == 0){
                $data['msg'] = '数据不合法，更新失败';
            }else{
                $ret = $app_user_module->addScore($this->appid, $userid, $score, $exp);     //增加积分、经验
                if($ret){
                    $data['status'] = 1; 
                    
                    //处理自动升降级
                    $levelRes = $user_level_module->getUserLevels($this->appid);
                    if(count($levelRes)) {
                        $cur_level = 0;
                        foreach($levelRes as $level){
                            if($level['auto_up'] && $scoreAndExp['exp']+$exp >= $level['exp']){
                                $cur_level = $level['level'];
                            }
                        }
                        if($cur_level){
                            $app_user_module->updateLevel($userid, $this->appid, $cur_level);   //更新用户等级
                        }
                    }
                    
                    $score_log_module = new score_log_module;
                    $dt = array(
                        'appid' => $this->appid,
                        'sub_shopid' => 0,
                        'userid' => $userid,
                        'type' => 9,      //{"array":[["客户系统扣减","-2"],["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"],["客户系统发放","9"]],"mysql":""}
                        'score' => $score,
                        'exp' => $exp,
                        'data' => myjson_encode(array('logid' => $logid)),
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt); //写日志 
                }
            }
        }else if($score < 0 && $exp < 0 ){
            if($scoreAndExp['score'] < abs($score)){
                $score = $scoreAndExp['score']*-1;
            }
            if($scoreAndExp['exp'] < abs($exp)){
                $exp = $scoreAndExp['exp']*-1;
            }
            if($scoreAndExp['score'] >= abs($score) && $scoreAndExp['exp'] >= abs($exp)){
                $ret = $app_user_module->useScoreAndExp($this->appid, $userid, abs($score), abs($exp)); //扣除积分、经验
                if($ret){
                    $data['status'] = 1; 
                    
                    //处理自动升降级
                    $levelRes = $user_level_module->getUserLevels($this->appid);
                    if(count($levelRes)) {
                        $cur_level = 0;
                        foreach($levelRes as $level){
                            if($level['auto_up'] && $scoreAndExp['exp']-abs($exp) >= $level['exp']){
                                $cur_level = $level['level'];
                            }
                        }
                        if($cur_level){
                            $app_user_module->updateLevel($userid, $this->appid, $cur_level);   //更新用户等级
                        }
                    }
                    
                    $score_log_module = new score_log_module;
                    $dt = array(
                        'appid' => $this->appid,
                        'sub_shopid' => 0,
                        'userid' => $userid,
                        'type' => -2,      //{"array":[["客户系统扣减","-2"],["系统扣除","-1"],["兑换使用","0"],["系统赠送","1"],["线上购物获得","2"],["线下购物获得","3"],["每日签到获得","4"],["连续签到奖励","5"],["LBS签到获得","6"],["生日祝福获得","7"],["系统返还","8"],["客户系统发放","9"]],"mysql":""}
                        'score' => abs($score),
                        'exp' => abs($exp),
                        'data' => myjson_encode(array('logid' => $logid)),
                        'add_at' => time()
                    );
                    $score_log_module->addNow($dt); //写日志 
                }else{
                    $data['msg'] = '更新失败';
                }
            }else{
                $data['msg'] = '更新失败，当前的剩余积分为'.$scoreAndExp['score'].'，剩余经验值为：'.$scoreAndExp['exp'].'';
            }
        }else{
            $data['msg'] = '数据不合法，更新失败';
        }
        return $data;
    }
    
    //获取全部订单列表
    function order_list_get(){
        $update_at = (int)request('update_at');
        $skip = (int)request('skip');
        $order_module = new order_module;
        return $order_module->getLatestOrder($this->appid, 0, $update_at, $skip);
    }
    
    //获取某个用户订单列表
    function user_orders_get(){
        $update_at = (int)request('update_at');
        $userid = (int)request('userid');
        $skip = (int)request('skip');
        $order_module = new order_module;
        return $order_module->getLatestOrder($this->appid, $userid, $update_at, $skip);
    }
    
    //订单取消
    function order_cancel_get(){
        $userid = (int)request('userid');
        $orderid = (int)request('orderid');
        
        $order_module = new order_module;
        $ret = $order_module->cancel($this->appid, $userid, $orderid);
        if($ret['status']){
            $key = 'order:'.$this->appid.':'.$userid.':1';
            $this->cache->del($key);                                  //清除缓存
            
            $key = 'order_detail:'.$this->appid.':'.$userid.':'.$orderid;
            $this->cache->del($key);                                  //清除缓存
        }
        return $ret;
    }
    
    //订单发货
    function order_delivery_post(){
        $userid = (int)request('userid');
        $orderid = (int)request('orderid');
        $send_sms = (int)request('send_sms');
        $delivery_company = trim(request('delivery_company'));
        $delivery_sn = trim(request('delivery_sn'));
        
        $order_module = new order_module;
        $ret = $order_module->delivery($this->appid, $userid, $orderid, $send_sms, $delivery_company, $delivery_sn);
        if($ret['status']){
            $key = 'order:'.$this->appid.':'.$userid.':1';
            $this->cache->del($key);                                  //清除缓存
            
            $key = 'order_detail:'.$this->appid.':'.$userid.':'.$orderid;
            $this->cache->del($key);                                  //清除缓存
        }
        return $ret;
    }


    //调用网址：[POST] http://localhost/api/open.php?/data/add_ddorder
    function add_ddorder_get(){
        $ddcard_order_module = new ddcard_order_module;
        return $ddcard_order_module->addNow();
    }


}