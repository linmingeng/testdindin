<?php
/**
 * 积分策略模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class score_conf_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
        $filedsArr = array(
            "`score_confid`",
            "`level`",
            "`sex`",
            "`factor`",
            "`start_at`",
            "`end_at`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
    }
    
    //获取积分策略列表
    function getScoreConfs($appid = 0, $page = 1){
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
        array_push($whereArr, "`flag` = 1"); 
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_score_conf` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_score_conf` WHERE ".$where." ORDER BY `score_confid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //获取应用的积分配置策略
    function getConfs($appid){
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid.""); 
        array_push($whereArr, "`flag` = 1"); 
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT ".$this->fileds." FROM `the_score_conf` WHERE ".$where." "; 
        return $this->db->getX($sql);
    }
    
}