<?php
/**
 * 用来生成图片验证码
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
if(!isset($_SESSION)){
    session_start();
}
class verifyCode {
    private $defWidth = 70;        //图片默认宽度
    private $defHeight = 28;       //图片默认高度
   
    function __construct(){
        
    }

    /**
     * 使用Gd库生成验证码
     * @param int $sessionName      session名
     * @param string $str           字符
     * @param int $width            宽度
     * @param int $height           高度
     */
    function show($sessionName = "verifyCode",$str = "",$width = "",$height = ""){
        if($sessionName == ""){
            $sessionName = "verifyCode";
        }
        
        //不存在imageCreate函数则认为当前环境不支持GD库
        if (function_exists('imagecreate')) {
            //产生4个字符的随机字符串作为验证码
            $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $code = array();
            for ($i=0; $i<4; $i++) {
                $code[] = $str[mt_rand(1,strlen($str))-1];
            }
            //将验证码写入到Session，忽略大小写
            $_SESSION[$sessionName] = strtolower(implode('',$code));
            
            $width = 50;    //图片宽度
            $height = 20;    //图片高度
            $im = ImageCreate($width,$height);    //创建图形
            ImageColorAllocate($im,255,255,255); //填充背景颜色为白色
            //用淡色给图形添加杂色
            for ($i=0; $i<100; $i++) {
                $pxcolor = ImageColorAllocate($im,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
                ImageSetPixel($im,mt_rand(0,$width),mt_rand(0,$height),$pxcolor);
            }
            //用深色调绘制边框
            $bordercolor = ImageColorAllocate($im,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
            ImageRectangle($im,0,0,$width-1,$height-1,$bordercolor);
            //用比较明显的颜色写上验证码文字
            $offset = 5;
            foreach ($code as $char) {
                $textcolor = ImageColorAllocate($im,mt_rand(0,250),mt_rand(0,150),mt_rand(0,250));
                ImageChar($im,5,$offset,2,$char,$textcolor);
                $offset += 10;
            }
            //禁止缓存
            header("pragma:no-cache\r\n");
            header("Cache-Control:no-cache\r\n");
            header("Expires:0\r\n");
            //检查系统支持的文件类型，优先级为PNG->JPEG->GIF
            if (ImageTypes() & IMG_PNG) {
                header('Content-Type:image/png');
                ImagePNG($im);
            } elseif (ImageTypes() & IMG_JPEG) {
                header('Content-Type:image/jpeg');
                ImageJPEG($im);
            } else {
                header('Content-Type:image/gif');
                ImageGif($im);
            }
        } else {
            //不支持GD库，则输出默认验证码ABCD
            $_SESSION[$sessionName] = 'fnww';
            
            header('Content-Type:image/jpeg');
            $fp = fopen('show.jpg','rb');
            echo fread($fp,filesize('show.jpg'));
            fclose($fp);
        }

    }
}
?>