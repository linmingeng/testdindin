<?php
/**
 * 用来生成分页代码
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
!defined('IN_FUNREST') && exit('Access Denied');

class paging {

    public  $curPage;       //当前页数
    public  $allPage;       //全部页数
    public  $pageSize;      //每页记录数
    public  $allRecords;    //全部记录数

    function __construct() {

    }

    public function showPaging($curPage,$pageSize,$allRecords) {
        $curPage = (int)$curPage;
        $pageSize = (int)$pageSize;
        $allRecords = (int)$allRecords;
        $allPage = ceil($allRecords / $pageSize);

        $curPage = max($curPage,1);
        $curPage = min($curPage,$allPage);

        $this->curPage = $curPage;
        $this->allPage = $allPage;
        $this->pageSize = $pageSize;
        $this->allRecords = $allRecords;

        $url = $this->getUrl();

        if($curPage == 1 && $allPage == 1){
            $pageStr = "首页 &nbsp; 上一页 &nbsp; 下一页 &nbsp; 尾页 &nbsp; 第<span>".$curPage."/".$allPage."</span>页 &nbsp; 共<span>".$allRecords."</span>条";
        }else if($curPage == 1){
            $pageStr = "首页 &nbsp; 上一页 &nbsp; <a href='".$url."&all=".$allRecords."&page=".($curPage+1)."'>下一页</a> &nbsp; <a href='".$url."&all=".$allRecords."&page=".$allPage."'>尾页</a> &nbsp; 第<span>".$curPage."/".$allPage."</span>页 &nbsp; 共<span>".$allRecords."</span>条";
        }else if($curPage == $allPage){
            $pageStr = "<a href='".$url."&all=".$allRecords."&page=1'>首页</a> &nbsp; <a href='".$url."&all=".$allRecords."&page=".($curPage-1)."'>上一页</a> &nbsp; 下一页 &nbsp; 尾页 &nbsp; 第<span>".$curPage."/".$allPage."</span>页 &nbsp; 共<span>".$allRecords."</span>条";
        }else {
            $pageStr = "<a href='".$url."&all=".$allRecords."&page=1'>首页</a> &nbsp; <a href='".$url."&all=".$allRecords."&page=".($curPage-1)."'>上一页</a> &nbsp; <a href='".$url."&all=".$allRecords."&page=".($curPage+1)."'>下一页</a> &nbsp; <a href='".$url."&all=".$allRecords."&page=".$allPage."'>尾页</a> &nbsp; 第<span>".$curPage."/".$allPage."</span>页 &nbsp; 共<span>".$allRecords."</span>条";
        }
//<select name='pageSize' id='pageSize' onchange='window.location.href=\"".$url."&all=".$allRecords."&page=1&pageSize=\"+this.options[this.selectedIndex].value;'>
        
        $pageStr .= " &nbsp; 每页显示 
<select name='pageSize' id='pageSize' onchange='window.location.href=\"".str_replace("&pageSize=","&oldPageSize=",str_replace("&page=","&oldPage=",$url))."&all=".$allRecords."&page=".$curPage."&pageSize=\"+this.options[this.selectedIndex].value;'>
    <option value='".$pageSize."'>".$pageSize."</option>
    <option value='15' >15</option>
    <option value='30' >30</option>
    <option value='50' >50</option>
    <option value='100' >100</option>
    <option value='200' >200</option>
    <option value='300' >300</option>
    <option value='500' >500</option>
    <option value='1000' >1000</option>
</select>
         条";

        return $pageStr;
    }

    public function getUrl() {
        $req = $_GET + $_POST;
        foreach($req as $key => $val){
            $keyTemp = strtolower($key);
            if($keyTemp != "page" && $keyTemp != "all" && $keyTemp != "oldall" && $keyTemp != "msg"){
                $queryArray[] = $key."=".$val;
            }
        }
        $curUrl = "?".implode($queryArray,"&");
        return $curUrl;
    }

}
?>