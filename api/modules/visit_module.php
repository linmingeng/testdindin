<?php
/**
 * 访问者模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-11-24
 */
class visit_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
        $this->pageSize = 10;
    }
    
    //添加
    function addNow($data){
        if(!is_array($data) || !isset($data['reqid'])){
            return ;
        }
        $data['add_at'] = time();
        $data['flag'] = 1;
        $visitid = $this->db->add('the_visit',$data);   //写入记录
        return array("visitid" => $visitid );
    }
    
    //修改
    function updateNow($data){
        if(!is_array($data) || !isset($data['reqid'])){
            return ;
        }
        $reqid = $data['reqid'];
        unset($data['reqid']);
        unset($data['add_at']);
        unset($data['flag']);
        
        $arr = array();
        foreach($data as $key => $val){
            if($val != ''){
                $arr[] = " `".$key."` = '".$val."' "; 
            }
        }
        $sql = 'UPDATE `the_visit` SET '.implode($arr,",").' WHERE `reqid` = "'.$reqid.'" ';

        $status = $this->db->updateX($sql);   //写入记录
        return array("status" => $status );
    }
    
    //获取用户访问数
    function getUv($appid, $userid, $days = 7){
        $days = max($days,0);
        $uv = 0;
        $cur_date = strtotime(date('Y-m-d'));
        $add_at = $cur_date - 86400*$days;
        $whereArr = array();
        array_push($whereArr, "`appid` = ".$appid."");
        array_push($whereArr, "`inviter_uid` = ".$userid."");
        array_push($whereArr, "`flag` = 1");
        $where = implode(" AND ",$whereArr);
        $sql = "SELECT COUNT(*) AS `uv` FROM `the_visit` WHERE ".$where." AND `add_at` > '".$add_at."' ";    //计算总数
        $res = $this->db->getX($sql);
        if(is_array($res)){
            $uv = $res[0]["uv"];
        }
        return $uv;
    }
}