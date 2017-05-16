<?php
require_once BASE_PATH.'/modules/sms_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';
/**
 * 短信验证码控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class sms {
    
    function __construct(){
        $this->sms_module = new sms_module();
    }
        
    //调用网址：[POST] http://localhost/api/index.php?/sms/code
    function code_post(){
        $checkChar = new checkChar;

        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        $sort = (int)request('sort');
        if(!$sort){
            //手机号码是否已注册
            $user_module = new user_module();
            if($user_module->checkPhone($phone)){
                error(406.001,'该手机号码已注册，请直接登录！');    
            }
        }
        $appid = (int)request('appid');
       
        return $this->sms_module->addNow($phone, $sort, $appid); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/sms/bind_code
    function bind_code_post(){
        $checkChar = new checkChar;

        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        $sort = (int)request('sort');
        if(!$sort){
            //手机号码是否已注册
            $user_module = new user_module();
            if($user_module->checkBindPhone($phone)){
                error(406.001,'该手机号码已被使用，请更换！');    
            }
        }
        $appid = (int)request('appid');
       
        return $this->sms_module->addNow($phone, $sort, $appid); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/sms/verify
    function verify_post(){
        $checkChar = new checkChar;

        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        $code = request('code');
        if( ! $code){
            error(406,'请输入验证码！');    
        }
        
        $res = $this->sms_module->verifyNow($phone, $code); 
        if( ! $res){
            error(406.002,'错误的验证码');  
        }
        
        return array("code" => 200);
    } 
    
}