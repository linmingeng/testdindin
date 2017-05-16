<?php
/**
 * 订单商品模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class order_goods_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`order_number`",
            "`goodsid`",
            "`goods_sn`",
            "`name`", 
            "`image`", 
            "`price`", 
            "`quantity`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //批量获取订单商品列表
    function getOrderGoods($order_numbers){
        if(is_array($order_numbers)){
            if(count($order_numbers) > 1){
                $whereStr = "`order_number` IN ('".implode("','",$order_numbers)."') ";
            }else{
                $whereStr = "`order_number` = '".$order_numbers[0]."'";
            }
        }else{
            $whereStr = "`order_number` = '".$order_numbers."'";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_order_goods` WHERE ".$whereStr." ";

        $order_goodss = $this->db->getX($sql);
        if(count($order_goodss)){
            $data = array();
            foreach($order_goodss as $order_goods){
                $order_number = $order_goods["order_number"];
                unset($order_goods["order_number"]);
                $data[$order_number][] = $order_goods;
            }    
            return $data;
        }
    }
    
}