<?php
/**
 * 每日生成分销商报表
 *
 * 命令 ：nohup /usr/local/php/bin/php /data/wwwroot/saas/api/trader_report_daily.php > /data/wwwroot/saas/api/logs/trader_report_daily.txt &
 * @author funfly
 */
set_time_limit(0);                      //超时设置：采集大量数据时用到
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html; charset=utf-8');
include __DIR__.'/config/config.php';
include __DIR__.'/libraries/utils.php';
include __DIR__.'/libraries/mysqli.php';

set_error_handler("errorHandler");	     //设置异常捕捉函数

class trader_report_daily {
    
    function __construct() {
      
    }
    
    //生成报表
    function createReport($re){
        $appid = $re['appid'];
        $userid = $re['userid'];
        $days = 1;
        $data = array(
            'uv' => 0,
            'orders' => 0,
            'money' => 0
        );
        $cur_date = strtotime(date('Y-m-d'));
        $add_at = $cur_date - 86400*$days;
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`inviter_uid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT COUNT(*) AS `orders`, SUM(`price`) AS `money` FROM `the_order` WHERE ".$where." AND `add_at` > '".$add_at."' AND `add_at` < '".$cur_date."' ";    //计算总数
        $res = $this->db->getX($sql);
        if(is_array($res)){
            $data['orders'] = $res[0]["orders"];
            $data['money'] = $res[0]["money"]?$res[0]["money"]:0;
        }
        
        $sql = "SELECT COUNT(*) AS `uv` FROM `the_visit` WHERE ".$where." AND `add_at` > '".$add_at."' AND `add_at` < '".$cur_date."' ";    //计算总数
        $res = $this->db->getX($sql);
        if(is_array($res)){
            $data['uv'] = $res[0]["uv"];
        }
        
        if( $data['uv'] > 0 || $data['orders'] > 0 || $data['money'] > 0){
            $data['appid'] = $appid;
            $data['userid'] = $userid;
            $data['add_at'] = $add_at;
            $data['flag'] = 1;
            $this->db->add('the_report',$data);
        }
        
        return;
    }
    
    function connectMysql(){
        global $dbConfig;
        $db = new mysql;
        $db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);
        $this->db = $db;
    }
    
    function getData($traderid = 0){
        return $this->db->getX('SELECT `traderid`,`appid`,`userid` FROM `the_trader` WHERE `traderid` > '.(int)$traderid.' AND `status` >= 0 ORDER BY `traderid` ASC LIMIT 100 ');
    }
    
    function run($traderid){
        $this->connectMysql();
        $next_traderid = 0;
        $res = $this->getData($traderid);
        if(!count($res)){
            //echo '-';
            echo ' done!'.chr(10);
            return ;
        }else{
            $i = 0;
            foreach($res as $re){
                $this->createReport($re);
                //echo '-'.$i;
                $i++;
                if($i >= 10){
                    sleep(1);
                }
                $next_traderid = $re['traderid'];
            }
        }
        $this->run($next_traderid);
    }
    
}

echo 'Run at '.date('Y-m-d H:i:s');
$trader_report_daily = new trader_report_daily;
$trader_report_daily->run();
