<?php
/**
 * 优惠券类型模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class coupon_type_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`coupon_typeid`",
            "`appid`",
            "`type`",
            "`tip`",
            "`consume`",
            "`money`",
            "`quantity`",
            "`send`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        $this->pageSize = 10;
    }
    
    //获取可领取的优惠券类型列表
    function getCouponType($appid = 0, $page = 1, $type = 0){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`type` = ".$type."");
        array_push($whereArr, "`status` = 2");
        array_push($whereArr, "`flag` = 1");
        array_push($whereArr, "`quantity` > 0");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_coupon_type` WHERE ".$where." ";    //计算总数
        $countRes = $this->db->getX($countSql);
          
        if(is_array($countRes)){
            $data["count"] = $countRes[0]["count"];
        }
        $page = min($page,ceil($data["count"]/$this->pageSize));
        if($data["count"]){
            if($data["count"] > $this->pageSize*$page){
                $data["next"] = $page+1;
            }
            if($page > 1){
                $data["previous"] = $page-1;
            }
            $limit = $this->pageSize*($page-1).",".$this->pageSize;
            $sql = "SELECT ".$this->fileds." FROM `the_coupon_type` WHERE ".$where." ORDER BY `coupon_typeid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //更新优惠券类型信息
    function updateQuantity($coupon_typeid, $quantity = 1){
        $this->db->updateX("UPDATE `the_coupon_type` SET `quantity` = `quantity` - ".$quantity." , `send` = `send` + ".$quantity." WHERE `coupon_typeid` = '".$coupon_typeid."' AND `quantity` >= ".$quantity."");
    }
    
    //获取优惠券类型信息
    function getCouponTypes($coupon_typeids, $more){
        if(is_array($coupon_typeids)){
            $whereStr = "`coupon_typeid` IN (".implode(",",$coupon_typeids).") ";
        }else{
            $whereStr = "`coupon_typeid` = '".intval($coupon_typeids)."'";
        }
        $filedsArr = array(
            "`coupon_typeid`",
            "`appid`",
            "`type`",
            "`tip`",
            "`consume`",
            "`money`",
            "`expires`",
            "`status`"
        );
        if($more){
            $filedsArr = array_merge(array(
                "`quantity`",
                "`expires`",
                "`status`"
            ), $filedsArr);
        }
        $fileds = implode(",",$filedsArr);
        $sql = "SELECT ".$fileds." FROM `the_coupon_type` WHERE ".$whereStr." AND `flag` = 1 ";
        $coupon_types = $this->db->getX($sql);
        if(count($coupon_types)){
            $data = array();
            foreach($coupon_types as $coupon_type){
                $data[$coupon_type["coupon_typeid"]] = $coupon_type;
            }    
            return $data;
        }
    }
}