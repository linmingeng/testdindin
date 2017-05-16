<?php
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';

class personalInfo {

    function __construct() {
        
        $this->user_module = new user_module();
    }

    function get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        $this->personalInfo_view();
        return array(1,2,3);
    }
    function personalInfo_view(){
        location('index.php?/personalInfo/view');
    }
    
    function view_get(){
        global $user_info;
        if ($user_info["userid"]=="") {
            location('index.php?/user/login');
        }
        return $this->user_module->getUsers($user_info["userid"]);
    }

    /*资料填写*/
    function view_post(){
        global $user_info;
        $checkChar = new checkChar;
        $ret['qq'] = (int)request('qq');                    //qq
        $ret['phone'] = trim(request('phone'));             //电话
        $ret['realname'] = trim(request('realname'));       //真实名字
        // $ret['problem'] = trim(request('problem'));         //问题
        // $ret['answer'] = trim(request('answer'));           //答案
        $ret['nickname'] =  trim(request('nickname'));      //昵称
        $ret['sex'] =  trim(request('sex'));                //性别
        $ret['address'] =  trim(request('address'));        //地址
        $ret['zip'] =  trim(request('zip'));                //邮编
        $ret['idcard'] =  trim(request('idcard'));          //身份证
        $ret['birthday'] =  trim(request('birthday'));      //生日
        $ret['email'] =  trim(request('email'));            //电子邮箱
        //手机检验
        if( ! $ret['phone']){
            error(406,'请输入手机号码！');  
        }

        if( ! $checkChar->checkNow("mobile",$ret['phone'])){
            error(406,'手机号码格式不对！');  
               
        }
        
        $user_module = new user_module();
        if($user_module->checkData($ret['phone'],$user_info["userid"])){
                if($user_module->checkPhone($ret['phone'])){
                    error(406,'手机号已被占用！');  
                }
            }
        if($user_module->checkData($ret['idcard'],$user_info["userid"])){
                if($user_module->checkIdcard($ret['idcard'])){
                    error(406,'身份证已被使用！');  
                }
            }
        if($user_module->checkData($ret['nickname'],$user_info["userid"])){
                if($user_module->checkNickname($ret['nickname'])){
                    error(406,'昵称已被使用！');
                }     
            }    
        $userid = $user_module->updateNow($user_info["userid"],$ret);

        if($userid){ 
            return array('code'=>200);    
        } 
    }
}