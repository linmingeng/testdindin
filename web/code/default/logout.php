<?php
/**
 * 退出登录
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class logout {
    
    public $ucsyn;                      //同步登录/退出的代码
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    
    function __construct() {
        $this->logoutNow();
    }
    
    function logoutNow() {
        global $siteConfig;
        
        //退出登录
        _setcookie('auth', '', 0);
        
        //生成同步退出的代码
        $ucsyn = uc_user_synlogout();
        $this->ucsyn = $ucsyn;
        
        $this->ul = "?do=index";     
        $this->sec = 1000;
        $this->msg = "退出成功!";
    }
    
}

$obj = new logout;
$ucsyn = $obj->ucsyn;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>