<?php
require_once BASE_PATH.'/modules/goods_module.php';

class cart{
    
    function __construct(){

    }
    function index_get(){
        $cart = json_decode($_COOKIE['cart'],true);
        $count = 0;
        foreach($cart as $goods_id=>$quantity){
            if($quantity > 0){
                $goodsids[$goods_id] = $goods_id;
                $count += $quantity;
            }
        }
        if($count <= 0){
            return ;
        }
        $goods_moduel = new goods_module();
        $goods_list = $goods_moduel->getSaleGoods(array_values($goodsids));
        $total_price = 0;
        foreach($goods_list as &$val){
            $val['quantity'] = $cart[$val['goodsid']];
            $val['goods_total_price'] = $val['price']*$val['quantity'];
            $total_price += $val['goods_total_price'];
        }
        $res['goods_list'] = $goods_list;
        $res['total_price'] = $total_price;
        return $res;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/city/list?provinceid=1
    function list_get(){
        global $cache,$controller,$method;
        
        $provinceid = (int)request('provinceid');
        
        $key = $controller.':'.$method.':'.$provinceid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->city_module->getList($provinceid); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
    
    
}