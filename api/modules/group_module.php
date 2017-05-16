<?php
/**
 * 商品分组模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class group_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
       
        $filedsArr = array(
            "`groupid`",
            "`name`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //获取应用的商品目录
    function getGroups($appid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_group` WHERE `appid` = ".$appid." AND `flag` = 1 ORDER BY `ordernum` DESC,`groupid` DESC ";
        $res = $this->db->getX($sql);
        if(is_array($res)){

            $endRes = $this->getEndGroups($appid);

            $subGroups = array();
            $subRes = $this->getSubGroups($appid);
            if(is_array($subRes)){
                foreach($subRes as $subRe){

                    if(is_array($endRes)){
                        foreach($endRes as $endRe){
                            if($endRe['sub_groupid'] == $subRe['sub_groupid']){
                                $subRe['end_groups'][] = $endRe;
                            }
                        }
                    }

                    $subGroups[$subRe['groupid']][] = $subRe;
                }
            }


            foreach($res as  $re){
                if(isset($subGroups[$re['groupid']])){
                    $re['sub_groups'] = $subGroups[$re['groupid']];
                }else{
                    $re['sub_groups'] = array();
                }
                $data['gid_'.$re['groupid']] = $re;
            }
        }
        return $data;
    }

    //获取应用的首页商品目录
    function getHomeGroups($appid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_group` WHERE `appid` = ".$appid." AND `flag` = 1 ORDER BY `ordernum` DESC,`groupid` DESC ";
        $res = $this->db->getX($sql);
        return $res;
    }
    
    function getSubGroups($appid){
        $filedsArr = array(
            "`groupid`",
            "`sub_groupid`",
            "`image`",
            "`name`"
        );
        $fileds = implode(",",$filedsArr);
        $sql = "SELECT ".$fileds." FROM `the_sub_group` WHERE `appid` = ".$appid." AND `flag` = 1 ORDER BY `ordernum` DESC,`sub_groupid` DESC ";
        return $this->db->getX($sql);
    }

    function getEndGroups($appid){
        $filedsArr = array(
            "`groupid`",
            "`sub_groupid`",
            "`end_groupid`",
            "`image`",
            "`name`"
        );
        $fileds = implode(",",$filedsArr);
        $sql = "SELECT ".$fileds." FROM `the_end_group` WHERE `appid` = ".$appid." AND `flag` = 1 ORDER BY `ordernum` DESC,`sub_groupid` DESC,`end_groupid` DESC ";
        return $this->db->getX($sql);
    }


    function getHomeSubGroups($appid,$groupid=0){
        $filedsArr = array(
            "`groupid`",
            "`sub_groupid`",
            "`name`",
            "`image`",
        );
        $fileds = implode(",",$filedsArr);
        $where = " `appid` = ".$appid." AND `flag` = 1 ";
        if($groupid>0){
            $where .= ' AND groupid='.$groupid;
            $limit = ' limit 5';
        }
        $sql = "SELECT ".$fileds." FROM `the_sub_group` WHERE ".$where." ORDER BY `ordernum` DESC,`sub_groupid` DESC ".$limit;

        return $this->db->getX($sql);
    }

    function getStoreGroups($groupids=0){
        $filedsArr = array(
            "`groupid`",
            "`name`",
        );
        if(empty($groupids)){
            $groupids = 0;
        }
        $fileds = implode(",",$filedsArr);
        $where = " `groupid` in (".$groupids.") AND `flag` = 1 ";
        $sql = "SELECT ".$fileds." FROM `the_group` WHERE ".$where." ORDER BY `ordernum` DESC ";
        return $this->db->getX($sql);
    }
}