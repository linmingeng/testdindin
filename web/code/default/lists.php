<?php
/**
 * 文章列表
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class lists {
    public $articleTable = "the_article";   //表名
    public $sortTable = "the_sort";         //分类表名
    public $sort = "aid";                   //默认排序字段: 主键
    public $direct = "desc";                //默认排序方式: desc or asc
    public $nextDirect;                     //下次排序方式
    public $pageSize = 20;                  //默认每页记录数: 15
    public $curPage;
    public $results;
    public $pagingStr;
    public $act;
    public $type;
    public $sid;
    public $ssid = 0;
    public $keywords;
    public $aid;
    public $all;
    public $msg;
    
    function __construct() {
        global $db;
        
        $act = trim($_REQUEST['act']);
        $type = (int)$_REQUEST['type'];
        $sid = (int)$_REQUEST['sid'];
        $ssid = (int)$_REQUEST['ssid'];
        $keywords = trim($_REQUEST['keywords']);
        $aid = $_REQUEST['aid'];
        $all = (int)$_REQUEST['all'];
        $curPage = (int)$_REQUEST['page'];
        $msg = trim($_REQUEST['msg']);
        
        $articleTable = $this->articleTable;
        
        if($ssid < 1){
            $ssid = $sid;
        }
        
        $this->msg = $msg;
        
        if(isset($_REQUEST['sort'])){
            $sort = strtolower($_REQUEST['sort']);
            if($sort != ""){
                $this->sort = $sort;
            }
        }
        
        if(isset($_REQUEST['direct'])){
            $direct = strtolower($_REQUEST['direct']);
            if(in_array($direct,array('asc','desc'))){
                $this->direct = $direct;
            }
        }
        
        if($this->direct == "desc"){
            $nextDirect = "asc";
        }else{
            $nextDirect = "desc";
        }
        $this->nextDirect = $nextDirect;
        
        $this->act = $act;
        $this->type = $type;
        $this->sid = $sid;
        $this->ssid = $ssid;
        $this->keywords = $keywords;
        $this->aid = $aid;
        $this->all = $all;
        $this->curPage = $curPage;
        
        $sortArticleId = $this->getSortArticleId();
        if($sortArticleId > 0){
            location("?do=view&sid=".$sid."&ssid=".$ssid."&aid=".$sortArticleId); //当前分类是单篇文章;直接转向文章阅读页
        }else{
            $this->getPageArticles();
        }
    }
    
    /**
     *示例：$sidStr = $obj->getSortArticleId();
     *返回：单篇文章分类的文章ID号
    */
    function getSortArticleId(){
        global $db;
        
        $ssid = $this->ssid;
        $articleTable = $this->articleTable;
        $sortTable = $this->sortTable;
        
        $sql = "SELECT 1 FROM `".$sortTable."` WHERE `sid` = '".$ssid."' AND `article` = 1 AND `check` = 1 AND `flag` = 1 ";
        $resultSort = $db->getX($sql);
        if($resultSort == ""){
            return 0;
        }
        
        $sql = "SELECT `aid` FROM `".$articleTable."` WHERE `sid` = '".$ssid."' AND `check` = 1 AND `flag` = 1 ORDER BY `best` DESC,`besttime` DESC,`aid` DESC LIMIT 0,1";
        $result = $db->getX($sql);
        if($result == ""){
            return 0;
        }else{
            return $result[0]['aid'];
        }
        
    }
    
    /**
     *示例：$sidStr = $obj->getPageArticles();
     *返回：当前分类下全部子分类子栏目的文章(分页);多维数组
    */
    function getPageArticles(){
        global $db;
        
        $act = $this->act;
        $type = $this->type;
        $sid = $this->sid;
        $ssid = $this->ssid;
        $keywords = $this->keywords;
        $aid = $this->aid;
        $all = $this->all;
        $curPage = $this->curPage;
        $pageSize = $this->pageSize;
        $articleTable = $this->articleTable;
        $sortTable = $this->sortTable;
        $sort = $this->sort;
        $direct = $this->direct;
        
        if($act == "search"){       //搜索
            if($keywords == ""){
                $sqlStr = " WHERE `check` = 1 AND `flag` = 1 ";
            }else{
                if($type == 1){          //搜索文章内容
                    $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `content` LIKE '%".$keywords."%' ";
                }else if($type == 2){    //搜索文章ID
                    $keywords = (int)$keywords;
                    $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `aid` = '".$keywords."' ";
                }else if($type == 3){    //搜索发布者
                    $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `author` = '".$keywords."' ";
                }else {                  //搜索文章标题
                    $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `title` LIKE '%".$keywords."%' ";
                }
            }
        }else{
            $sorts = new sorts;
            $subSidStr = $sorts->getSidStr($ssid);
            if($subSidStr == ""){
                $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `sid` = '".$ssid."' ";
            }else{
                $sqlStr = " WHERE `check` = 1 AND `flag` = 1 and `sid` IN (".$subSidStr.$ssid.") ";
            }
        }
        
        if($all > 0){
            //直接获取总记录数
            $allRecords = $all;
        }else{
            $countSql = "SELECT COUNT(1) AS `allRecords` FROM `".$articleTable."` ".$sqlStr." ";
            $countResult = $db->getX($countSql);
            $allRecords = (int)$countResult[0]['allRecords'];
        }
        
        if($allRecords < 1){
            $this->curPage = 1;
            $this->results = array();
            $this->pagingStr = "";
            return;
        }
        
        //计算总页数
        $allPage = ceil($allRecords / $pageSize);
        $curPage = max($curPage,1);
        $curPage = min($curPage,$allPage);
        $start = ($curPage-1)*$pageSize;
        $this->curPage = $curPage;
        
        $sql = "SELECT `aid`,`title`,`posttime` FROM `".$articleTable."` ".$sqlStr." ORDER BY `".$sort."` ".$direct." LIMIT ".$start.",".$pageSize." ";
        $results = $db->getX($sql);
        $this->results = $results;
        
        //生成分页
        $obj = new paging;
        $pagingStr = $obj->showPaging($curPage,$pageSize,$allRecords);
        $this->pagingStr = $pagingStr;
        
    }
    
    function getUrlX() {
        $nextDirect = $this->nextDirect;
        $req = $_GET + $_POST;
        foreach($req as $key => $val){
            $key = strtolower($key);
            if($key != "sort" && $key != "direct"){
                $queryArray[] = $key."=".$val;
            }
        }
        $urlX = "?".implode($queryArray,"&");
        $urlX .= "&direct=".$nextDirect;
        return $urlX;
    }
    
}

$obj = new lists;
$urlX = $obj->getUrlX();
$results = $obj->results;
$pagingStr = $obj->pagingStr;
$curPage = $obj->curPage;
$pageSize = $obj->pageSize;
$keywords = $obj->keywords;
$type = $obj->type;
$act = $obj->act;
$sort = $obj->sort;
$direct = $obj->direct;
$nextDirect = $obj->nextDirect;
$sid = $obj->sid;
$ssid = $obj->ssid;
$msg = $obj->msg;

$sorts = new sorts;
$sortsTree = $sorts->getSortsTree($sid,$ssid);
$bigSortname = $sorts->bigSortname;
$bigSid = $sorts->bigSid;
$curSortname = $sorts->curSortname;
$curSid = $sorts->curSid;
$curSortIsArticle = $sorts->curSortIsArticle;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>