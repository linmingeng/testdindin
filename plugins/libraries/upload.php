<?php
/**
 * 用来上传各种文件
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
!defined('IN_FUNREST') && exit('Access Denied');

class upload {
    
    public  $immediate;
    public  $upfile;
    public  $fileType = 'file';                                                         //文件类型：img=>图片,swf=>flash动画,flv=>flv视频,media=>媒体文件,other=>其他
    public  $attachdir = 'upload';                                                      //上传文件保存路径，结尾不要带/ :upload
    public  $dirtype = 2;                                                               //1:按天存入目录 2:按月存入目录 3:按年存入目录  建议使用按月存
    public  $minattachsize = 10240;                                                     //最小上传大小，默认是10K:10240
    public  $maxattachsize = 2097152;                                                   //最大上传大小，默认是2M:2097152
    public  $upext = 'doc,txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid';    //上传扩展名:doc,txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid
    public  $msgtype = 2;                                                               //返回上传参数的格式：1，只返回url，2，返回参数数组
    public  $picType = 0;                //上传的图片时，图片上传方式：0=>保存原图 1=>保存原图并生成缩略图 2=>不保存原图，只保存缩略图 3=>不保存原图，只保存缩略图（自动裁剪） 
    public  $thumbWidth = 0;             //上传的图片时，缩略图长度：0=>默认 300
    public  $thumbHeight = 0;            //上传的图片时，缩略图高度：0=>默认 200
    public  $saveDir = "";               //保存目录
    
    function __construct() {
        $immediate = $_REQUEST['immediate'];
        $upfile = $_FILES['filedata'];
        
        $this->immediate = $immediate;
        $this->upfile = $upfile;
        
    }
    
    function getSize($size){
        if($size >= 1024*1024*1024){
            return floor($size/(1024*1024*1024)).'GB';
        }else if($size >= 1024*1024){
            return floor($size/(1024*1024)).'MB';
        }else if($size >= 1024){
            return floor($size/1024).'KB';
        }else{
            return $size.'B';
        }
    }
    
    function uploadNow(){

        $immediate = $this->immediate;
        $attachdir = $this->attachdir;
        $dirtype = $this->dirtype;
        $minattachsize = $this->minattachsize;
        $maxattachsize = $this->maxattachsize;
        $upext = $this->upext;
        $msgtype = $this->msgtype;
        $upfile = $this->upfile;
        $fileType = $this->fileType;
        $picType = $this->picType;
        $thumbWidth = $this->thumbWidth;
        $thumbHeight = $this->thumbHeight;
        
        if($thumbWidth <= 0){
            $thumbWidth = 400;
        }
        
        if($thumbHeight <= 0){
            $thumbHeight = 300;
        }
        
        $this->thumbWidth = $thumbWidth;
        $this->thumbHeight = $thumbHeight;
        
        $err = "";
        $msg = "";

        if(!empty($upfile['error'])){
            switch($upfile['error']){
                case '1':
                    $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                    break;
                case '2':
                    $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                    break;
                case '3':
                    $err = '文件上传不完全';
                    break;
                case '4':
                    $err = '无文件上传';
                    break;
                case '6':
                    $err = '缺少临时文件夹';
                    break;
                case '7':
                    $err = '写文件失败';
                    break;
                case '8':
                    $err = '上传被其它扩展中断';
                    break;
                case '999':
                default:
                    $err = '无有效错误代码';
            }
        }else if(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none'){
                $err = '无文件上传';
        }else{
            $temppath=$upfile['tmp_name'];
            $fileinfo=pathinfo($upfile['name']);
            $extension=$fileinfo['extension'];
            if(preg_match('/'.str_replace(',','|',$upext).'/i',$extension)){
                $filesize=filesize($temppath);
                if($filesize > $maxattachsize){
                    $err='文件大小超过'.$this->getSize($maxattachsize).'';
                }else if($filesize < $minattachsize){
                    $err='文件大小不足'.$this->getSize($minattachsize).'';
                }else{
                    switch($dirtype){
                        case 1: $attach_subdir = date('Y-m-d'); break;
                        case 2: $attach_subdir = date('Y-m'); break;
                        case 3: $attach_subdir = date('Y'); break;
                    }
                    
                    if($this->saveDir) {                                //设置保存目录
                        $mid = $this->saveDir;
                    }else{
                        $mid = 1;
                        if(isset($_SESSION['mid'])) {                   //读session记忆的模型ID
                            $mid = (int)$_SESSION['mid'];
                        }
                        $mid = max($mid,1);
                    }
                    
                    $attach_dir_module = $attachdir.'/'.$mid;
                    if(!is_dir($attach_dir_module)){
                        @mkdir($attach_dir_module, 0777);
                        @fclose(fopen($attach_dir_module.'/index.htm', 'w'));
                    }
                    
                    $attach_dir_temp = $attach_dir_module.'/'.$attach_subdir;
                    if(!is_dir($attach_dir_temp)){
                        @mkdir($attach_dir_temp, 0777);
                        @fclose(fopen($attach_dir_temp.'/index.htm', 'w'));
                    }
                    
                    $attach_dir = $attach_dir_temp.'/'.$fileType;
                    if(!is_dir($attach_dir)){
                        @mkdir($attach_dir, 0777);
                        @fclose(fopen($attach_dir.'/index.htm', 'w'));
                    }
                    
                    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);

                    //上传图片生成缩略图
                    if(preg_match('/'.str_replace(',','|',"jpg,jpeg,gif,png").'/i',$extension)){               
                        if($picType == 1){          //1=>保存原图并生成缩略图
                            $filename = date("YmdHis").mt_rand(1000,9999).'_original.'.$extension;
                            $target = $attach_dir.'/'.$filename;
                            move_uploaded_file($upfile['tmp_name'],$target);
                            
                            $image = new image($target);
                            $image->resizeImage($thumbWidth,$thumbHeight);
                            $image->save(1,$attach_dir."/",str_replace("_original","_thumb",$filename));
                        }else if($picType == 2){    //2=>不保存原图，只保存缩略图
                            $filename = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                            $target = $attach_dir.'/'.$filename;
                            move_uploaded_file($upfile['tmp_name'],$target);
                            
                            $image = new image($target);
                            $image->resizeImage($thumbWidth,$thumbHeight);
                            $image->save(1,$attach_dir."/",$filename);
                        }else if($picType == 3){    //3=>不保存原图，只保存缩略图（自动裁剪） 
                            $filename = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                            $target = $attach_dir.'/'.$filename;
                            move_uploaded_file($upfile['tmp_name'],$target);
                            
                            $image = new image($target);
                            $image->cropper($attach_dir."/".$filename, $thumbWidth,$thumbHeight);
                        }else {                     //0=>保存原图
                            $filename = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                            $target = $attach_dir.'/'.$filename;
                            move_uploaded_file($upfile['tmp_name'],$target);
                        } 
                    }else{           
                        $filename = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                        $target = $attach_dir.'/'.$filename;
                        move_uploaded_file($upfile['tmp_name'],$target);
                        
                    }
                    
                    //处理专属的图片服务器
                    //$target = str_replace("../img.qonou.com","http://img.qonou.com",$target);
                    
                    if($immediate=='1')$target='!'.$target;
                    if($msgtype==1){
                        $msg=$target;    
                    }else{
                        $filesize = (int)($filesize/1024);
                        $msg=array('url'=>$target,'localname'=>$upfile['name'],'fileType'=>$fileType,'fileUrl'=>$target,'extension'=>$extension,'filesize'=>$filesize);//id参数固定不变，仅供演示，实际项目中可以是数据库ID
                    }
                    
                }
            }else{
                $err='上传文件扩展名必需为：'.$upext;
            }
            @unlink($temppath);
        }
        return array('err'=>$err,'msg'=>$msg);
    }

}
?>