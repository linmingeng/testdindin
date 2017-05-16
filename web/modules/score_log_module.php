<?php
/**
 * 积分日志模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class score_log_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
        $filedsArr = array(
            "`score_logid`",
            "`type`",
            "`score`",
            "`data`",
            "`add_at`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
    }
    
    //获取积分日志列表
    function getScoreLogs($appid = 0, $userid = 0, $page = 1, $type = ''){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        if($appid){
            array_push($whereArr, "`appid` = ".$appid.""); 
        }
        if($userid){
            array_push($whereArr, "`userid` = ".$userid.""); 
        }
        if($type != ''){
            array_push($whereArr, "`type` = ".intval($type).""); 
        }
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_score_log` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_score_log` WHERE ".$where." ORDER BY `score_logid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //获取最近几天的积分记录
    function getLastLogs($appid, $userid, $type, $days = 1, $sub_shopid = 0){
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid.""); 
        array_push($whereArr, "`userid` = ".$userid.""); 
        array_push($whereArr, "`type` = ".$type.""); 
        if($days){
            $time = strtotime(date('Y-m-d'))-($days-1)*86400;
            array_push($whereArr, "`add_at` > ".$time.""); 
            array_push($whereArr, "`add_at` < ".time().""); 
        }
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT `add_at`,`score` FROM `the_score_log` WHERE ".$where." "; 
        return $this->db->getX($sql);
    }
    
    //判断是否已领取过
    function checkIfGot($appid, $userid, $type, $days = 1, $year = 1, $sub_shopid){
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid.""); 
        array_push($whereArr, "`userid` = ".$userid.""); 
        array_push($whereArr, "`type` = ".$type.""); 
        if($sub_shopid){
            array_push($whereArr, "`sub_shopid` = ".$sub_shopid.""); 
        }
        if($days > 0 || $year > 0){
            if($days){
                $time = strtotime(date('Y-m-d'))-($days-1)*86400;
            }else{
                $curYear = date('Y');
                if($year > 1){
                    $curYear = $curYear - ($year-1);
                }
                $time = strtotime(date($year.'-1-1'));
            }
            array_push($whereArr, "`add_at` >= ".$time.""); 
            array_push($whereArr, "`add_at` <= ".time().""); 
        }
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_score_log` WHERE ".$where." ";    //计算总数
        $countRes = $this->db->getX($countSql);
        if(is_array($countRes)){
            return $countRes[0]["count"];
        }
        return 0;
    }
    
    //添加
    function addNow($data){
        return $this->db->add('the_score_log',$data);   //写入记录
    }
    
}