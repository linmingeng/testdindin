<?php
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/sms_module.php';
require_once BASE_PATH.'/libraries/checkChar.php';
/**
 * 用户相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class user {
    
    function __construct(){
        global $user_info;
        $this->userid = $user_info['userid'];
    }
    
    function check_login(){
        if(!$this->userid){
            location('index.php?/search/view');
        }
    }

    function check_post(){
        global $user_info;
        if($this->userid){
            return array('is_login'=>1,'user_info'=>$user_info);
        }
    }
    
    //修改密码
    function password_get(){
        $this->check_login();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/user/profile
    function profile_get(){
        $this->check_login();
        
        $user_module = new user_module();
        return $user_module->getProfiles();
    }
    //调用网址：[GET] http://localhost/api/index.php?/user
    function get(){
        $this->check_login();
        return array(1,2,3);
    }
    //调用网址：[GET] http://localhost/api/index.php?/user/login
    function login_get(){

        return array(1,2,3);
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/user/register
    function register_get(){
        
        return array(1,2,3);
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/user/phone_reg
    function phone_reg_post(){
        $checkChar = new checkChar;
        //request {"usrNumber":"15659830881","usrPwd":"123123","parityString":"123123","code":"200"}
//        $appid = (int)request('appid');
//        $phone = request('phone');
        $appid = 1;
        $phone = request('usrNumber');
        $password = request('usrPwd');
//        $modal = request('modal');
        if( ! $phone){
            error(406,'请输入手机号码！');
        }
        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }

//        if($modal != 'portal' ){    //门户模式暂时不开启手机验证码
//            $code = request('code');
//            if( ! $code){
//                error(406,'请输入验证码！');
//            }
//            $sms_module = new sms_module();
//            $res = $sms_module->verifyNow($phone, $code);
//            if( ! $res){
//                error(406,'验证码错误或者已失效！');
//            }
//        }

        $user_module = new user_module();
        if($user_module->checkPhone($phone)){       //已注册
            return array('newbie' => 0);            //0: 老用户 1：新用户 2：绑定用户
        }
        
        if($user_module->checkUsername($phone)){    //用户名已被占用
            $str = "23456789ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz";
            $randname = '';
            for ($i=0; $i<4; $i++) {
                $randname .= $str[mt_rand(1,strlen($str))-1];
            }
            $username = $randname.substr($phone,-4);
        }else{
            $username = $phone;
        }
        $actived = 0;
        
        $cityid = (int)request('cityid');
        $address = request('address');
        $business = request('business');
        
        $time = time();
        $ip = getIp();
        $avatar = request('avatar');
        $sex = (int)request('sex');
        $inviter_uid = (int)request('inviter_uid');
    
        $data = array(
            'appid' => $appid,
            'user_type' => 0,   //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
            'modal' => trim(request('modal')),
            'phone' => $phone,
            'username' => $username,
            'nickname' => $phone,
            'password' => md5($password),
            'avatar' => $avatar,
            'sex' => $sex,
            'reg_ip' => $ip,
            'add_at' => $time,
            "cityid" => $cityid,
            "address" => $address,
            "business" => $business,
            'actived' => $actived,
            'inviter_uid' => $inviter_uid,
            'lng' => request('lng'),
            'lat' => request('lat')
        );
        $userid = $user_module->regNow($data);
        if(!$userid){
            error(406,'系统繁忙，请重试！');
        }
        $user_module->doInviteScorePrize($appid, $inviter_uid, $userid);        //处理邀请奖励
        return array('newbie' => 1);       //0: 老用户 1：新用户 2：绑定用户
    } 
    //调用网址：[POST] http://localhost/api/index.php?/user/phone_reg
    function phone_bind_post(){
        $checkChar = new checkChar;

        $userid = (int)request('userid');
        $phone = request('phone');
        if( ! $userid){
            error(406,'缺少参数：userid');    
        }
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }
        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        
        $user_module = new user_module();
        $user = $user_module->checkPhoneBind($phone, $userid);
        if(is_array($user)){       //已注册
            if($user['wx_unionid'] && $user['wx_unionid'] !='NULL' ){
                error(406,'该号码已使用，请绑定其他手机号！');
            }
            return array('newbie' => 3);        //0: 老用户 1：新用户 2：绑定用户(新手机帐户) 3：绑定用户(已注册的手机帐户) 
        }
        
        if($user_module->checkUsername($phone, $userid)){    //用户名已被占用
            $str = "23456789ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz";
            $randname = '';
            for ($i=0; $i<4; $i++) {
                $randname .= $str[mt_rand(1,strlen($str))-1];
            }
            $username = $randname.substr($phone,-4);
        }else{
            $username = $phone;
        }
        
        $actived = 0;
        $cityid = request('cityid');
        $address = request('address');
        $business = request('business');
        
        $time = time();
        $ip = getIp();
        
        $data = array(
            'phone' => $phone,
            'username' => $username,
            'password' => '',
            "cityid" => $cityid,
            "address" => $address,
            "business" => $business,
            'actived' => $actived,
            'lng' => request('lng'),
            'lat' => request('lat')
        );
        $state = $user_module->updateNow($userid, $data);
        if(!$state){
            error(406,'系统繁忙，请重试！');
        }
        return array('newbie' => 2);        //0: 老用户 1：新用户 2：绑定用户(新手机帐户) 3：绑定用户(已注册的手机帐户) 
    }
    //手机登录 调用网址：[POST]http://localhost/api/index.php?/user/phone_login
    function phone_login_post(){
        $checkChar = new checkChar;
        $appid = (int)request('appid');
        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        
        $wechat_userid = (int)request('wechat_userid');
        
        $password = request('password');
        if( ! $password){
            error(406,'请输入登录密码！');
        }
        
        if( ! $checkChar->checkNow("password",$password)){
            error(406,'登录密码不合法！');
        }
        
        $app_module = new app_module;
        $app_name = $app_module->getAppName($appid);
        if(!$app_name){
            error(406,'应用不存在！appid:'.$appid);    
        }
        
        $user_module = new user_module();
        $ret = $user_module->checkPhoneLogin($appid, $app_name, $phone, $password, $wechat_userid);
        if( ! is_array($ret)){
            error(406,$ret);
        }
        return $ret;
    }
    
    //登录 调用网址：[POST]http://localhost/api/index.php?/user/login
    function login_post(){
        $checkChar = new checkChar;

        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        
        $password = request('password');

        if( ! $password){
            error(406,'请输入登录密码！');
        }

        if( ! $checkChar->checkNow("password",$password)){
            error(406,'登录密码不合法！');
        }

        $user_module = new user_module();
        $ret = $user_module->checkLogin($phone,$password);
        if( ! is_array($ret)){
            error(406,$ret);
        }
        $ret['code'] = 200;
        return $ret;
    }
    
    //退出登录
    function logout_get(){
        $user_module = new user_module();
        $user_module->logoutNow();
        return '退出成功！';
    }
    
    //注册
    function add_post(){
        $checkChar = new checkChar;
        
        $appid = (int)request('appid');
        $phone = trim(request('phone'));
        $modal = trim(request('modal'));
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        
        if($modal != 'portal' ){    //门户模式暂时不开启手机验证码
            $code = request('code');
            if( ! $code){
                error(406,'请输入验证码！');    
            }
            $sms_module = new sms_module();
            $res = $sms_module->verifyNow($phone, $code); 
            if( ! $res){
                error(406,'验证码错误或者已失效！');  
            }
        }
        $password = request('password');
        if( ! $password){
            error(406,'请输入登录密码！');
        }
        
        if( ! $checkChar->checkNow("password",$password)){
            error(406,'登录密码不合法！');
        }
        
        $user_module = new user_module();
        
        if($user_module->checkPhone($phone)){
            error(406.001,' "'.$phone.'" 已注册！请直接登录！');
        }
        
        $username = request('username');
        if( ! $username ){
            $str = "23456789ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz";
            $randname = '';
            for ($i=0; $i<4; $i++) {
                $randname .= $str[mt_rand(1,strlen($str))-1];
            }
            $username = $randname.substr($phone,-4);
        }else{
            if($user_module->checkUsername($username)){
                error(406,'用户名 "'.$username.'" 已被占用！');
            }
        }
        
        $cityid = (int)request('cityid');
        $address = request('address');
        $business = request('business');
        
        $nickname = request('nickname');
        if(!$nickname){
            $nickname = $username;
        }
        
        $time = time();
        $ip = getIp();
        $avatar = request('avatar');
        $sex = (int)request('sex');
        $inviter_uid = (int)request('inviter_uid');
        
        $data = array(
            'user_type' => 0,           //用户类型：{"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"]],"mysql":""}
            'appid' => $appid,
            'modal' => $modal,
            'phone' => $phone,
            'username' => $username,
            'nickname' => $nickname,
            'password' => md5($password),
            'avatar' => $avatar,
            'sex' => $sex,
            'reg_ip' => $ip,
            'add_at' => $time,
            "cityid" => $cityid,
            "address" => $address,
            "business" => $business,
            "inviter_uid" => $inviter_uid,
            'actived' => 1,
            'lng' => request('lng'),
            'lat' => request('lat')
        );
        
        $userid = $user_module->regNow($data);
        if($userid){
            $user_module->doInviteScorePrize($appid, $inviter_uid, $userid);        //处理邀请奖励
            if($modal != 'portal' ){    //门户模式暂时不开启手机验证码
                $sms_module->useNow($phone, $code); 
            }
            $data['userid'] = $userid;
            return $user_module->loginNow($data);
        }
        
        error(406,'注册失败！请重试！');
           
    }
    
    /** 绑定手机号码
     *  支持:
            手机号未被使用，直接绑定 
            手机号已预占，未激活，微信帐户可合并到手机号帐户
            手机号已激活，但未绑定微信帐户，微信帐户可合并到手机号帐户
     */
    function bind_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'身份无法识别，绑定失败！','alert');
        }
        $checkChar = new checkChar;
        
        $wechat_userid = $user_info['userid'];
        $appid = (int)request('appid');
        $phone = trim(request('phone'));
        $modal = trim(request('modal'));
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
        $sms_module = new sms_module();
        $res = $sms_module->verifyNow($phone, $code); 
        if( ! $res){
            error(406,'验证码错误或者已失效！');  
        }
        
        $password = request('password');
        if( ! $password){
            error(406,'请输入登录密码！');
        }
        
        if( ! $checkChar->checkNow("password",$password)){
            error(406,'登录密码不合法！');
        }
        
        $user_module = new user_module();
        
        //判断是否存在当前未绑定手机号的微信用户 
        $userRet = $user_module->getUser($wechat_userid);                     //获取未激活的用户基本资料
        if(!count($userRet)){
            error(403,'身份无法识别，绑定失败！','alert');
        }
        $wechat_user = $userRet[0];
        if($wechat_user['phone'] && $wechat_user['phone'] !='NULL' ){
            error(406,'您已绑定手机号码！');
        }
        
        $phone_userid = 0;
        $phone_state = -1;   //-1=手机号未被使用，直接绑定 0=手机号已预占，未激活，微信帐户可合并进来 1=手机号已激活，但未绑定微信帐户，微信帐户可合并进来
        $phone_user = $user_module->getUserByPhone($phone);                   //通过手机号获取用户信息
        if(is_array($phone_user)){
            if($phone_user['actived'] == 1 ){
                if($phone_user['wx_unionid'] && $phone_user['wx_unionid'] !='NULL' ){
                    error(406,' "'.$phone.'" 已被使用！请更换！');            //手机号已使用，且已绑定微信号
                }
                if(md5($password) != $phone_user['password']){
                    error(406,' "'.$phone.'" 已被使用！<br>请输入正确的密码或换手机号！');
                }
            }
            $phone_state = $phone_user['actived'];
            $phone_userid = $phone_user['userid'];
        }
        
        if($user_module->checkUsername($phone, $phone_user['userid'])){       //用户名已被占用
            $str = "23456789ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz";
            $randname = '';
            for ($i=0; $i<4; $i++) {
                $randname .= $str[mt_rand(1,strlen($str))-1];
            }
            $username = $randname.substr($phone,-4);
        }else{
            $username = $phone;
        }
        
        $cityid = request('cityid');
        $address = request('address');
        $business = request('business');
        
        $time = time();
        $ip = getIp();
        $inviter_uid = (int)request('inviter_uid');
        $actived = 1;
        $app_user_module = new app_user_module;
            
        if($phone_state == -1){         //-1=手机号未被使用，直接绑定
            $wechat_user['phone'] = $phone;
            $wechat_user['username'] = $username;
            $wechat_user['password'] = md5($password);
            $wechat_user['cityid'] = $cityid;
            $wechat_user['address'] = $address;
            $wechat_user['business'] = $business;
            $wechat_user['actived'] = $actived;
            $wechat_user['inviter_uid'] = $inviter_uid;
            $wechat_user['lng'] = request('lng');
            $wechat_user['lat'] = request('lat');
            $state = $user_module->updateNow($wechat_userid, $wechat_user);
            $user_data = $wechat_user;
            $app_user_module->updateAt($appid, $wechat_userid);         //更新时间戳
        }else if($phone_state == 0 || $phone_state == 1){               //0=手机号已预占，未激活，微信帐户可合并进来 1=手机号已激活，但未绑定微信帐户，微信帐户可合并进来
            $data = array(
                "cityid" => $cityid,
                "address" => $address,
                "business" => $business,
                'actived' => $actived,
                "inviter_uid" => $inviter_uid,
                'lng' => request('lng'),
                'lat' => request('lat')
            );
            if(!$phone_user['username'] || $phone_user['username'] == 'NULL'){
                $phone_user['username'] = $username;
            }
            $phone_user['password'] = md5($password);
            
            if(!$phone_user['nickname'] || $phone_user['nickname'] == 'NULL'){
                $phone_user['nickname'] = $wechat_user['nickname'];
            }
            if(!$phone_user['avatar'] || $phone_user['avatar'] == 'NULL'){
                $phone_user['avatar'] = $wechat_user['avatar'];
            }
            if(!$phone_user['sex'] || $phone_user['sex'] == 'NULL'){
                $phone_user['sex'] = $wechat_user['sex'];
            }
            $phone_user['wx_nickname'] = $wechat_user['wx_nickname'];
            $phone_user['wx_unionid'] = $wechat_user['wx_unionid'];
            $phone_user['cityid'] = $cityid;
            $phone_user['address'] = $address;
            $phone_user['business'] = $business;
            $phone_user['actived'] = $actived;
            $phone_user['inviter_uid'] = $inviter_uid;
            $phone_user['lng'] = request('lng');
            $phone_user['lat'] = request('lat');
            $state = $user_module->updateNow($phone_userid, $phone_user);     //更新手机号对应的帐户
            $user_data = $phone_user;
            
            $up = $wechat_user;
            $up['nickname'] =  $up['nickname'].'_bind';
            $up['wx_unionid'] =  $up['wx_unionid'].'_bind';
            $up['flag'] = 0;
            $user_module->updateNow($wechat_userid, $up);                     //删除微信号对应的帐户
            
            $app_user_module->deleteAppUser($appid, $wechat_userid);          //删除微信号对应的应用会员
            
            $app_user_level = $app_user_module->getAppUserLevel($appid, $user_data['userid']);          //获取当前用户所在应用的等级
            if(count($app_user_level) == 0){
                $app_user_module->addNow($appid, $user_data['userid'], 0, 0, 1, $modal, $inviter_uid, $cityid);  //用户未加入应用：写入应用会员表
            }
            $app_user_module->updateAt($appid, $user_data['userid']);         //更新时间戳
        }
        
        if($state){
            $user_module->doInviteScorePrize($appid, $inviter_uid, $user_data['userid']);               //处理邀请奖励
            $sms_module->useNow($phone, $code); 
            return $user_module->loginNow($user_data);
        }
        
        error(406,'绑定失败！请重试！');
           
    }
    
    //获取基本资料资料
    function info_get(){
        global $cache;
        
        $userid = (int)request('uid');
        $key = 'user_info:'.$userid;
        $cache->get($key);                               //获取缓存，处理304
        
        $user_module = new user_module();
        $re = $user_module->getInfo($userid);
        if($re && count($re)){
            $cache->set($key,$re);                       //设置缓存，增加额外的header
            return $re;
        }
    }
    //获取资料
    function profiles_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        //$key = 'profiles:'.$user_info['userid'];
        //$cache->get($key);                               //获取缓存，处理304
        
        $user_module = new user_module();
        $re = $user_module->getProfiles();
        if($re && count($re)){
            //$cache->set($key,$re);                       //设置缓存，增加额外的header
            return $re;
        }
        error(403,'请重新登录！');
    }
    //修改资料
    function myprofiles_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $nickname = request('nickname');
        $realname = request('realname');
        $sex = request('sex');
        $country = request('country');
        $province = request('province');
        $city = request('city');
        $district = request('district');
        $zip = request('zip');
        $company = request('company');
        if(!$nickname){
            error(406,'昵称不能为空');
        }
        if(!$realname){
            error(406,'真实姓名不能为空');
        }

        $data = array(
            'nickname' => $nickname,
            'realname' => $realname,
            'sex' => $sex,
            'country' => $country,
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'zip' => $zip,
            'company' => $company
        );
        $user_module = new user_module();
        $re = $user_module->modifyNow($data);
        if($re && count($re)){
            $cache->set($key,$re);                       //设置缓存，增加额外的header
            return '个人资料设置成功！';
        }else{
            return '';
        }
    }
    //修改用户名
    function username_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $checkChar = new checkChar;
        
        $username = request('username');
        if( ! $username){
            error(406,'请输入昵称！');    
        }
        
        if( ! $checkChar->checkNow("username",$username)){
            //error(406,'昵称不符合规范。<br>由中文、英文、数字、下划线组成！');
            return array('code' => 406, 'alert' => '用户名必须在5-12个字符之间，<br>由中文、英文、数字、下划线组成。');
        }
        
        $user_module = new user_module();
        
        if($user_module->checkUsername($username, $user_info['userid'])){
            error(406,'用户名 "'.$username.'" 已被使用，请更换！');
        }
        
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        
        $data = array(
            'username' => $username
        );
        
        $user_module->modifyNow($data);
        return $data;
    }
    //修改昵称
    function nickname_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $checkChar = new checkChar;
        
        $nickname = request('nickname');
        if( ! $nickname){
            error(406,'请输入昵称！');    
        }
        
        //if( ! $checkChar->checkNow("username",$nickname)){
            //error(406,'昵称不符合规范。<br>由中文、英文、数字、下划线组成！');
            //return array('code' => 406, 'alert' => '昵称必须在5-12个字符之间，<br>由中文、英文、数字、下划线组成。');
        //}
        
        $user_module = new user_module();
        
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        
        $data = array(
            'nickname' => $nickname
        );
        
        $user_module->modifyNow($data);
        return $data;
    }
    //修改生日
    function birthday_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $checkChar = new checkChar;
        
        $birthday = request('birthday');
        if( ! $birthday){
            error(406,'请选择您的生日！');    
        }
        
        $time = strtotime($birthday);
        $birthday = date('Y-m-d', $time);
        $user_module = new user_module();
        
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        
        $data = array(
            'birthday' => $birthday
        );
        
        $user_module->modifyNow($data);
        return $data;
    }
    //设置性别
    function sex_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $user_module = new user_module();
        
        $key = 'profiles:'.$user_info['userid'];
        $cache->del($key);                                  //删除缓存
        
        $data = array(
            'sex' => request('sex')
        );
        
        $user_module->modifyNow($data);
        return $data;
    }
    //设置pushid
    function pushid_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            return ;
        }
        $user_module = new user_module();
        $ipad_pushid = trim(request('ipad_pushid'));
        $ios_pushid = trim(request('ios_pushid'));
        $wp_pushid = trim(request('wp_pushid'));
        $android_pushid = trim(request('android_pushid'));
        
        $data = array();
        if($ipad_pushid){
            $data['ipad_pushid'] = $ipad_pushid;
        }
        if($ios_pushid){
            $data['ios_pushid'] = $ios_pushid;
        }
        if($wp_pushid){
            $data['wp_pushid'] = $wp_pushid;
        }
        if($android_pushid){
            $data['android_pushid'] = $android_pushid;
        }
        $user_module->modifyNow($data);
        return $data;
    }
    //修改密码
    function password_post(){
        global $user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $checkChar = new checkChar;
        
        $oldpassword = request('oldpassword');
        if( ! $oldpassword){
            error(406,'请输入原密码！');
        }
        
        $password = request('password');
        if( ! $password){
            error(406,'请输入新密码');
        }
        
        if( ! $checkChar->checkNow("password",$password)){
            error(406,'新密码不合法！');
        }
        
        $repassword = request('repassword');
        if( ! $repassword){
            error(406,'请重复输入新密码！');
        }
        
        if( $password != $repassword){
            error(406,'两次输入的新密码不一致！');
        }
        
        if($password == $oldpassword){
            error(406,'新密码不能与原密码一样！');
        }
        
        $user_module = new user_module();
        $re = $user_module->setPassword($oldpassword,$password);
        if($re == -1){
            error(406,'原密码错误！请重试！');
        }else if($re == 0){
            error(403,'请重新登录！');
        }
        return array("alert" => '密码修改成功！请重新登录！');
    }
    
    //获取重置密码的验证码
    function forgot_code_post(){
       
        $checkChar = new checkChar;
        $appid = (int)request('appid');
        $phone = request('phone');
        if( ! $phone){
            error(406,'请输入手机号码！');    
        }

        if( ! $checkChar->checkNow("mobile",$phone)){
            error(406,'请输入正确的手机号码！');    
        }
        
        $user_module = new user_module();
        if( ! $user_module->checkPhone($phone)){
            error(406,'该手机号码未注册！');    
        }
        $sort = 1;
        $sms_module = new sms_module();
        return $sms_module->addNow($phone, $sort, $appid); 
    }
    
    //通过短信验证码重设密码
    function password_reset_post(){
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
        
        $sms_module = new sms_module();
        $res = $sms_module->verifyNow($phone, $code); 
        if( ! $res){
            error(406,'验证码错误或者已失效！');  
        }
        
        $password = request('password');
        if( ! $password){
            error(406,'请输入登录密码！');
        }
        
        if( ! $checkChar->checkNow("password",$password)){
            error(406,'登录密码不合法！');
        }
        
        $user_module = new user_module();
        if( ! $user_module->checkPhone($phone)){
            error(406,'该手机号码未注册！');    
        }
        
        $re = $user_module->resetPassword($phone, $password);
        if( ! $re){
            error(406,'密码设置失败！请换个密码试试！');
        }
        $sms_module->useNow($phone, $code); 
        return array("alert" => '密码设置成功！请登录！');
        
    }
}