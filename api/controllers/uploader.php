<?php
require_once BASE_PATH.'/libraries/upload.php';
require_once BASE_PATH.'/libraries/image.php';
require_once BASE_PATH.'/modules/user_module.php';
/**
 * 上传文件
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-14
 */
class uploader {
    
    public $attachdir = "../upload";    //文件保存路径
    public $upNum = 0;                  //当前已上传文件个数
    public $limitNum = 0;               //上传文件个数限制：0=>无限制 
    public $inIframe = 0;               //是否是Iframe调用 0=>否 1=>是 
    public $sfn;                        //Iframe调用时，选择文件后回调的js函数名
    public $fn;                         //Iframe调用时，上传后回调的js函数名
    public $rfn;                        //Iframe调用时，重置的js函数名
    public $results;
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    public $upext = "jpg,jpeg,gif,png"; //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid   
    public $filePath;                   //重新上传时，上次上传的文件路径 
    public $fileType;                   //上传的文件类型：other swf flv media pic
    public $picType = 0;                //上传的图片时，图片上传方式：0=>保存原图 1=>保存原图并生成缩略图 2=>不保存原图，只保存缩略图
    public $thumbWidth = 0;             //上传的图片时，缩略图长度：0=>默认 300
    public $thumbHeight = 0;            //上传的图片时，缩略图高度：0=>默认 200
    public $saveDir = "";               //保存目录
    
    function __construct() {
       
        $upNum = (int)$_REQUEST['upNum'];
        $limitNum = (int)$_REQUEST['limitNum'];
        $inIframe = (int)$_REQUEST['inIframe'];
        $sfn = trim($_REQUEST['sfn']);
        $fn = trim($_REQUEST['fn']);
        $rfn = trim($_REQUEST['rfn']);
        $fileType = trim($_REQUEST['fileType']);
        $filePath = trim($_REQUEST['filePath']);
        $picType = (int)$_REQUEST['picType'];
        $thumbWidth = (int)$_REQUEST['thumbWidth'];
        $thumbHeight = (int)$_REQUEST['thumbHeight'];
        
        if($thumbWidth <= 0){
            $thumbWidth = 400;
        }
        
        if($thumbHeight <= 0){
            $thumbHeight = 300;
        }
        
        if($fileType == "other"){
            $this->upext = 'zip,rar,txt,doc';       //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid    
        }else if($fileType == "swf"){
            $this->upext = 'swf';                   //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid    
        }else if($fileType == "flv"){
            $this->upext = 'flv';                   //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid    
        }else if($fileType == "media"){
            $this->upext = 'wmv,avi,wma,mp3,mid,m4a';   //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid    
        }else if($fileType == "pem"){
            $this->upext = 'pem';                   //上传扩展名:pem
        }else{
            $fileType = 'pic';
            $this->upext = 'jpg,jpeg,gif,png';      //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid    
        } 
        
        $this->upNum = $upNum;
        $this->limitNum = $limitNum;
        $this->inIframe = $inIframe;
        $this->sfn = $sfn;
        $this->fn = $fn;
        $this->rfn = $rfn;
        $this->fileType = $fileType;
        $this->filePath = $filePath;
        $this->picType = $picType;
        $this->thumbWidth = $thumbWidth;
        $this->thumbHeight = $thumbHeight;
        
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader
    function post(){
        return $this->uploadNow();
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader/avatar
    function avatar_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $this->picType = 3;
        $this->thumbWidth = 240;
        $this->thumbHeight = 240;
        $this->saveDir = 'avatar';  //设置保存目录
        $res = $this->uploadNow();
        if($res["result"]["url"]){
            $user_module = new user_module();
            $user_module->modifyNow(array("avatar" => $res["result"]["url"]));
            
            $key = 'profiles:'.$user_info['userid'];
            $cache->del($key);                                  //删除缓存
            
            $key = 'avatar:'.$user_info['userid'];
            $cache->del($key);                                  //删除缓存
        
        }
        return $res;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader/voice
    function voice_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $folder = $_REQUEST['folder'];
        $this->saveDir = $folder;  //设置保存目录
        $this->fileType = 'media';
        $this->upext = 'm4a';
        $res = $this->uploadNow();
        return $res;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader/image
    function image_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $folder = $_REQUEST['folder'];
        $pictype = (int)$_REQUEST['pictype'];
        $this->picType = $pictype;
        $this->thumbWidth = 240;
        $this->thumbHeight = 240;
        $this->saveDir = $folder;  //设置保存目录
        $res = $this->uploadNow();
        return $res;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader/pem
    function pem_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $folder = $_REQUEST['folder'];
        $this->saveDir = $folder;  //设置保存目录
        $this->fileType = 'pem';
        $this->upext = 'pem';
        $res = $this->uploadNow();
        return $res;
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/uploader/p12
    function p12_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $folder = $_REQUEST['folder'];
        $this->saveDir = $folder;  //设置保存目录
        $this->fileType = 'p12';
        $this->upext = 'p12';
        $res = $this->uploadNow();
        return $res;
    }
    
    function get(){
        $re = array(
            're' => 1
        );
        return $re;
    }
    
    function uploadNow() {
        $fileType = $this->fileType;
        $attachdir = $this->attachdir;
        $upNum = $this->upNum;
        $upext = $this->upext;
        $picType = $this->picType;
        $thumbWidth = $this->thumbWidth;
        $thumbHeight = $this->thumbHeight;

        if(!in_array($fileType,array('pic','swf','flv','media','other','pem','p12'))){
            return '不支持的文件类型';    
        }

        $upload = new upload;
        if($fileType == "other"){
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 5242880;          //最大上传大小
        }else if($fileType == "swf"){
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 52428800;         //最大上传大小
        }else if($fileType == "flv"){
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 52428800;         //最大上传大小
        }else if($fileType == "media"){
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 52428800;         //最大上传大小
        }else if($fileType == "pem"){
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 1024*100;         //最大上传大小
        }else{
            $upload->minattachsize = 1;                //最小上传大小
            $upload->maxattachsize = 10485760;         //最大上传大小
        } 
        $upload->upext = $upext;                       //上传扩展名:txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid   
        $upload->fileType = $fileType;
        $upload->attachdir =$attachdir;                //保存到上一级的 upload 目录
        $upload->picType =$picType;
        $upload->thumbWidth =$thumbWidth;
        $upload->thumbHeight =$thumbHeight;
        $upload->saveDir = $this->saveDir;
        $results = $upload->uploadNow();
        
        $data = array();
        $data["msg"] = $results['err'];
        $data["result"] = $results['msg'];
        return $data;
    }
    
}