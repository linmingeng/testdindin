<?php
require_once BASE_PATH.'/modules/trader_module.php';
/**
 * 收支记录模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class income_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`incomeid`",
            "`type`",
            "`money`",
            "`status`",
            "`add_at`",
        );
        
       $this->fileds = implode(",",$filedsArr);
        
       $this->pageSize = 15;
    }

    //获取列表
    function getIncome($appid, $userid, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`userid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_income` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_income` WHERE ".$where." ORDER BY `incomeid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //添加
    function addNow($data){
        return $this->db->add('the_income',$data);   //写入记录
    }
    
    //发放订单提成
    function sendOrderPrize($appid, $userid, $orderid, $money){
        //$sql = "UPDATE `the_income` SET `status` = 1 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `money` = '".$money."' AND `type` = 1 AND `status` = 0 AND `flag` = 1 ";
        $sql = "UPDATE `the_income` SET `status` = 1 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `type` = 1 AND `status` = 0 AND `flag` = 1 ";
        $state = $this->db->updateX($sql); 
        if($state){
            $trader_module = new trader_module;
            $success = $trader_module->sendMoney($appid, $userid, $money, round($money));
            if(!$success){  
                //回滚
                $sql = "UPDATE `the_income` SET `status` = 0 WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `orderid` = '".$orderid."' AND `money` = '".$money."' AND `type` = 1 AND `status` = 1 AND `flag` = 1 ";
                $this->db->updateX($sql); 
            }
        }
        return $state;
    }
    
}