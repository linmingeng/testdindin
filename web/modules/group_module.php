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
    
    function getSubGroups($appid){
        $filedsArr = array(
            "`groupid`",
            "`sub_groupid`",
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
    function groupTree($sub_groupid=0,$end_groupid=0){
        if($end_groupid > 0){
            $sql = "SELECT end_groupid,sub_groupid,name FROM `the_end_group` WHERE `end_groupid` ='$end_groupid' limit 1";
            $end_group = $this->db->getX($sql);
            $end_group = $end_group[0];
            $sub_groupid = $end_group['sub_groupid'];
            $crumbs[] = $end_group;
        }
        if($sub_groupid > 0){
            $sql = "SELECT sub_groupid,name FROM `the_sub_group` WHERE `sub_groupid` ='$sub_groupid' limit 1";
            $sub_group = $this->db->getX($sql);
            $sub_group = $sub_group[0];
            array_unshift($crumbs,$sub_group);
        }
        return $crumbs;
    }
}