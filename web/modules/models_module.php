<?php
/**
 * 商品型号模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class models_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       $filedsArr = array(
            "`modelsid`",
            "`appid`",
            "`goodsid`",
            "`goods_sn`",
            "`name`", 
            "`image`", 
            "`original_price`", 
            "`price`", 
            "`return_money`", 
            "`store`"
        );
        $this->fileds = implode(",",$filedsArr);
    }
    
    //批量获取商品型号
    function getModels($goodsid){
        if(is_array($goodsid)){
            $whereStr = "`goodsid` IN (".implode(",",$goodsid).") ";
        }else{
            $whereStr = "`goodsid` = '".$goodsid."'";
        }
        $sql = "SELECT ".$this->fileds." FROM `the_models` WHERE ".$whereStr." AND `check` = 1 AND `flag` = 1 ";
        $results = $this->db->getX($sql);
        if(count($results)){
            $data = array();
            foreach($results as $models){
                $goodsid = $models["goodsid"];
                $data[$goodsid]['m_'.$models["modelsid"]] = $models;
            }
            return $data;
        }
    }
    
}