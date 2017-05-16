<?php
/**
 * 订单模型
 * @author      karlwu
 * @email       karlwu@domogo.com.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2017-04-25
 */
class ddcard_order_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`ddcard_orderid`",
            "`phone`",
            "`name`",
            "`sex`",
            "`country`",
            "`province`",
            "`city`",
            "`district`",
            "`address`",
            "`zip`",
            "`card_sn`",
            "`invoice_title`",
            "`note`",
            "`add_at`",
        );
        
       $this->fileds = implode(",",$filedsArr);

    }

    //添加
    function addNow(){

        if( ! request('card_sn')){
            return "叮叮卡批次号不能为空！";
        }

        $data = array(
            "phone" => request('phone'),
            "name" => request('name'),
            "sex" => request('sex'),
            "country" => request('country'),
            "province" => request('province'),
            "city" => request('city'),
            "district" => request('district'),
            "address" => request('address'),
            "zip" => request('zip'),
            "card_sn" => request('card_sn'),
            "invoice_title" => request('invoice_title'),
            "note" => request('note'),
            "add_at" => time()
        );

        $ddrderid = $this->db->add('the_ddcard_order',$data);   //写入记录
        if( $ddrderid){
            return array("msg" => "订单添加成功！", "ddrderid" => $ddrderid );
        }else{
            return "订单添加失败！请重试！";
        }
    }

}