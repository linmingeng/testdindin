<?php
require_once BASE_PATH.'/modules/coupon_module.php';
/**
 * 优惠券控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class coupon {
    
    function __construct(){
        $this->coupon_module = new coupon_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/coupon/list&page=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            return array('count' => 0, 'next' => '', 'previous' => '','results' => array());
        }
        
        $hongbao = (int)request('hongbao');
        $appid = (int)request('appid');
        $consume = floatval(request('consume'));
        $type = (int)request('type');
        $page = (int)request('page');
        $page = max($page,1);
        if($consume){               //获取当前可用的优惠券
            return $this->coupon_module->getCoupon($page, $hongbao, $appid, $consume, $type);    
        }
        $key = 'coupon:'.$hongbao.':'.$appid.':'.$user_info['userid'].':'.$type.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->coupon_module->getCoupon($page, $hongbao, $appid, $consume, $type);    
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
}