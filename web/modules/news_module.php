<?php
/**
 * 新闻模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class news_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`newsid`",
            "`title`",
            "`image`", 
            "`content`",
            "`add_at`", 
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 10;
    }
    
    //获取新闻
    function getNews($appid = 0, $page = 1){
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
        array_push($whereArr, "`check` = 1");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_news` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_news` WHERE ".$where." ORDER BY `newsid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            foreach($results as $key => $val){
                $results[$key]["add_at"] = formateTime($val["add_at"]);
            }
            $data["results"] = $results;
        }
        return $data;
    }
    
    //获取内容
    function getNewsDetail($newsid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_news` WHERE `newsid` = '".$newsid."' AND `flag` = 1 AND `check` = 1";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
            $data["add_at"] = formateTime($data["add_at"]);
        }
        return $data;
    }
    
    //获取推荐新闻
    function getBestNews(){
        $sql = "SELECT `newsid`,`title`,`image` FROM `the_news` WHERE `best` = 1 AND `flag` = 1 AND `check` = 1 ORDER BY `ordernum` ASC,`newsid` DESC LIMIT 3";
        return $this->db->getX($sql);
    }
    
    //获取应用新闻
    function getAppNews($appid){
        $appid = (int)$appid;
        $sql = "SELECT `newsid`,`title`,`image` FROM `the_news` WHERE `appid` = '".$appid."' AND `best` = 1 AND `flag` = 1 AND `check` = 1 ORDER BY `ordernum` ASC,`newsid` DESC LIMIT 3";
        return $this->db->getX($sql);
    }
}