<?php
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/coupon_module.php';
require_once BASE_PATH.'/modules/coupon_type_module.php';
/**
 * 优惠券类型控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class coupon_type {
    
    function __construct(){
        $this->coupon_type_module = new coupon_type_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/coupon_type/list&page=1
    function list_get(){
        global $cache;
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'coupon_type:'.$appid.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->coupon_type_module->getCouponType($appid, $page, 0); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
    function randGetId($rateArr){
        asort($rateArr);
        $rate = mt_rand(0, array_sum($rateArr));
        $tmp = array('id' => 0, 'rate' => 0, 'min' => 0, 'max' => 0);
        foreach($rateArr as $k => $v){
            $min = $tmp['max']; 
            $max = $v + $tmp['max']; 
            $tmp = array('id' => $k, 'rate' => $v, 'min' => $min, 'max' => $max);
            if($rate > $tmp['min'] && $rate <= $tmp['max'] ){
                return $k;
            }
        }
        return 0;
    }
    
    function send_coupons($type){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($appid, array('coupon','hongbao'));
        if($type == 1){             //红包
            $tag = '红包';
            $conf = $conf['hongbao'];
        }else{                      //优惠券
            $tag = '优惠券';
            $conf = $conf['coupon'];
        }
        
        if($conf['open'] !=  1){
            return array('alert' => '活动暂未开始，请联系客服！');
        }
        if(strtotime($conf['start_at']) > time()){
            return array('alert' => '活动开始时间：'.$conf['start_at']);
        }
        if(strtotime($conf['end_at']) < time()){
            return array('alert' => '本次活动已结束，请期待下一次吧！');
        }
        
        $coupon_module = new coupon_module;
        
        if($conf['limit'] == 1){        //是否限制，用完优惠券/红包后才能领取新的
            if($type == 1){             //红包
                if($coupon_module->ifHasHongbao($user_info['userid'], $appid) > 0){
                    return array('alert' => '红包用完了，再来领取吧！<br> 你也可以将红包分享给好友！');
                }
            }else{                      //优惠券
                if($coupon_module->ifHasCoupon($user_info['userid'], $appid) > 0){
                    return array('alert' => '优惠券用完了，再来领取吧！<br> 你也可以将优惠券分享给好友！');
                }
            }
        }
        
        if(isset($conf['bag_send']) && count($conf['bag_send'])){   //整包发放
            $send_coupons = $conf['bag_send'];
            $coupon_typeids = array();
            foreach($send_coupons as $coupon_typeid => $quantity){
                $coupon_typeids[] = $coupon_typeid;
            }
            $coupons = $this->coupon_type_module->getCouponTypes($coupon_typeids, 1); 
            if(!count($coupons)){
                return array('alert' => '居然没抢到'.$tag.'！！！');
            }
            $money = 0;
            foreach($send_coupons as $coupon_typeid => $quantity){
                if(isset($coupons[$coupon_typeid]) && $coupons[$coupon_typeid]['status'] == 2 && $coupons[$coupon_typeid]['appid'] == $appid && $coupons[$coupon_typeid]['quantity'] >= $quantity){
                    $couponid = $coupon_module->addCoupon($user_info['userid'], $coupons[$coupon_typeid], $quantity); 
                    if($couponid){
                        $this->coupon_type_module->updateQuantity($coupon_typeid, $quantity); 
                        $money += $coupons[$coupon_typeid]['money']*$quantity;
                    }
                }
            }
            if($money > 0 ){
                $key = 'coupon:0:'.$appid.':'.$user_info['userid'].':0:1';
                $cache->del($key);                                  //删除缓存
                return array('alert' => '恭喜，抢到了“'.$money.'”元'.$tag.'！<br> 你也可以将'.$tag.'分享给好友！');
            }
            return array('alert' => '居然没抢到'.$tag.'！！！');
        }else{                          //通过概率发放
            $rateArr = $conf['rate'];
            $send_num = (int)$conf['send_num'];
            $send_num = max(1, $send_num);
            
            $ids = array();
            for($i = 0; $i < $send_num; $i++){
                $ids[] = $this->randGetId($rateArr);    //随机取出红包
            }
            $coupon_typeids = array_unique($ids);
            $coupons = $this->coupon_type_module->getCouponTypes($coupon_typeids, 1); 
            if(!count($coupons)){
                return array('alert' => '居然没抢到'.$tag.'！！！');
            }
            $money = 0;
            foreach($ids as $coupon_typeid){
                if(isset($coupons[$coupon_typeid]) && $coupons[$coupon_typeid]['status'] == 2 && $coupons[$coupon_typeid]['appid'] == $appid && $coupons[$coupon_typeid]['quantity'] >= 1){
                    $couponid = $coupon_module->addCoupon($user_info['userid'], $coupons[$coupon_typeid]); 
                    if($couponid > 0){
                        $this->coupon_type_module->updateQuantity($coupon_typeid); 
                        $money += $coupons[$coupon_typeid]['money'];
                    }
                }
            }
            if($money > 0){
                $key = 'coupon:1:'.$appid.':'.$user_info['userid'].':0:1';
                $cache->del($key);                                  //删除缓存
                return array('alert' => '恭喜，抢到了“'.$money.'”元'.$tag.'！<br> 你也可以将'.$tag.'分享给好友！');
            }
            return array('alert' => '居然没抢到'.$tag.'！！！');
        }
    }
    
    
    //调用网址：[GET] http://localhost/api/index.php?/coupon_type/receive_coupon
    function receive_coupon_get(){
        return $this->send_coupons(0);
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/coupon_type/receive_hongbao
    function receive_hongbao_get(){
        return $this->send_coupons(1);
    }

}