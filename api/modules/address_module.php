<?php
/**
 * 收货地址模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class address_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`addressid`",
            "`name`", 
            "`sex`",
            "`phone`",
            "`area`",
            "`address`",
            "`zip`",
            "`isdefault`", 
        );
        
       $this->fileds = implode(",",$filedsArr);
        
       $this->pageSize = 10;
    }

    //获取列表
    function getAddress($appid, $userid, $page = 1){
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
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_address` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fileds." FROM `the_address` WHERE ".$where." ORDER BY `ordernum` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //添加
    function addNow($appid, $userid){
        
        $name = request('name');
        if( ! $name){
            error(406,'请输入收货人名字！');    
        }
        $sex = request('sex');
        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号！');    
        }
        $area = request('area');
        if( ! $area){
            error(406,'请选择地区信息！');    
        }
        $address = request('address');
        if( ! $address){
            error(406,'请输入详细地址！');    
        }
        $zip = request('zip');
        if( ! $zip){
            error(406,'请输入邮政编码！');    
        }
       
        $isdefault = (int)request('isdefault');

        $data = array(
            "appid" => $appid,
            "userid" => $userid,
            "name" => $name,
            "sex" => $sex,
            "phone" => $phone,
            "area" => $area,
            "address" => $address,
            "zip" => $zip,
            "isdefault" => $isdefault,
            "add_at" => time()
        );

        $addressid = $this->db->add('the_address',$data);   //写入记录
        if( $addressid){
            return array("msg" => "添加成功！","addressid" => $addressid);    
        }else{
            return "添加失败！请重试！";  
        }
    }
    
    //删除
    function delNow($appid, $userid, $addressid){
        if( ! $addressid){
            return "删除失败！请重试！"; 
        }
        $stauts = $this->db->delX("DELETE FROM `the_address` WHERE `addressid` = ".$addressid." AND `appid` = ".$appid." AND `userid` = ".$userid." ");
        
        if( $stauts){
            return "删除成功！";    
        }else{
            return "删除失败！请重试！";  
        }
    }
    
    //获取详情
    function getDetail($addressid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_address` WHERE `addressid` = '".$addressid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
}