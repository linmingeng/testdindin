<?php

require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/order_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/controllers/address.php';
class order {

    function __construct() {
        
    }

    function get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        $this->order_view();
        return array(1,2,3);
    }
    function order_view(){
        location('index.php?/order/view');
    }
    
    function confirm_get(){
        global $user_info;
        //获取用户信息
        $user_module = new user_module();
        $user = $user_module->getInfo($user_info['userid']);
        $address_controllers = new address();
        $addresses = $address_controllers->addrList();
        foreach($addresses['results'] as $address){
            $address_list[] = array(
                'address'   => $address['area'].$address['address'].$address['phone'],
                'addressid' => $address['addressid'],
                'name'      => $user['name'],
                'idcard'    => $user['idcard']
            );
        }

        //商品信息
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
        $res['address_list'] = $address_list;

        return $res;
    }

    function submit_post(){
        $addressid = (int)request('addressid');
        $order_module = new order_module();
        $orderid = $order_module->pcAdd($addressid);
        if($orderid > 0){
            setcookie("cart", '');
            return array('code'=>200,'orderid'=>$orderid);
        }else{
            error(406,'订单提交失败！');
        }
    }

    function list_get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        return array(1,2,3);
    }
    function list_post(){
        global $user_info;
        if(!$user_info['userid']){
            return ;
        }
        $order_module = new order_module();
        $page = (int)request('page');
        $status = (int)request('status');
        $ret = $order_module->getOrder(1,$user_info['userid'],$page,$status);

        return $ret;
    }
    function detail_get(){
        global $user_info;
        $orderid = (int)request('orderid');
        $order_module = new order_module();
        $ret['order'] = $order_module->getDetail(1,$user_info['userid'],$orderid);
        $order = array('已过期','待付款','待发货','待收货','待评价','订单完成');
        $ret['order']['status'] = $order[$ret['order']['status']];
        foreach($ret['order']['order_goods'] as &$order_good){
            $order_good['total_price'] = $order_good['price']*$order_good['quantity'];
        }
        return $ret;
    }

    /**
     * @ 支付页面
    */
    function pay_get(){
        global $user_info;
        $userid = $user_info['userid'];
        if($userid <=0){
            error(406,'请先登录');
        }
        $orderid = intval(request('orderid'));
        $order_module = new order_module();
        $ret = $order_module->getDetail(1,$userid,$orderid);
        return $ret;
    }

}