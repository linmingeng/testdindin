<?php
/**
 * 首页
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class index {
    public $articleTable = "the_article";  //表名
    public $sortTable = "the_sort";     //分类表名
    
    function __construct() {
        
    }
    
    /**
     *参数：$sid: 分类或栏目ID号
     *参数：$num: 获取的条数
     *示例：$return = $obj->getArticleLists($sid);
     *返回：当前分类下全部子分类子栏目的文章;多维数组
    */
    function getArticleLists($sid = 0,$num = 1){
        global $db;
        
        $sid = (int)$sid;
        $num = (int)$num;
        if($num < 1){
           $num = 1; 
        }
        
        $articleTable = $this->articleTable;
        $sortTable = $this->sortTable;
        
        $sorts = new sorts;
        $subSidStr = $sorts->getSidStr($sid);
        if($subSidStr == ""){
            $sqlTemp = " `sid` = '".$sid."' ";
        }else{
            $sqlTemp = " `sid` IN (".$subSidStr.$sid.") ";
        }
        
        $results = array();
        $sql = "SELECT `aid`,`sid`,`title`,`titlecolor`,`fonttype`,`posttime` FROM `".$articleTable."` WHERE ".$sqlTemp." AND `check`=1 AND `flag`=1 ORDER BY `best` DESC,`besttime` DESC,`aid` DESC LIMIT 0,".$num." ";
        $resultTemps = $db->getX($sql);
        foreach($resultTemps as $resultTemp){
            $resultTemp['bigSid'] = $sid;
            $results[] = $resultTemp;
        }
        return $results;
    }
    
    
    /**
     *参数：$sid: 分类或栏目ID号
     *参数：$num: 获取的条数
     *示例：$return = $obj->getPhotoArticleLists($sid);
     *返回：当前分类下全部子分类子栏目的图片文章;多维数组
    */
    function getPhotoArticleLists($sid = 0,$num = 1){
        global $db;
        
        $sid = (int)$sid;
        $num = (int)$num;
        if($num < 1){
           $num = 1; 
        }
        
        $articleTable = $this->articleTable;
        $sortTable = $this->sortTable;
        
        $sorts = new sorts;
        $subSidStr = $sorts->getSidStr($sid);
        if($subSidStr == ""){
            $sqlTemp = " `sid` = '".$sid."' ";
        }else{
            $sqlTemp = " `sid` IN (".$subSidStr.$sid.") ";
        }
        
        $results = array();
        $sql = "SELECT `aid`,`sid`,`title`,`picurl`,`posttime` FROM `".$articleTable."` WHERE ".$sqlTemp." AND `check`=1 AND `flag`=1 ORDER BY `best` DESC,`besttime` DESC,`aid` DESC LIMIT 0,".$num." ";
        
        $resultTemps = $db->getX($sql);
        if($resultTemps != ""){
            foreach($resultTemps as $resultTemp){
                $resultTemp['bigSid'] = $sid;
                $results[] = $resultTemp;
            }
        }
        return $results;
    }
    
}

$obj = new index;
$planArticles = $obj->getArticleLists(3,5);
$photoArticles = $obj->getPhotoArticleLists(4,3);

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>