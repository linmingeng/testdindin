<?php
/**
 * 注册
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class register {
    
    public $userTable = 'the_user';     //用户表名
    public $poolTable = 'the_pool';     //基金池表名
    public $msg ;                       //提示信息
    public $ul ;                        //转向地址
    public $sec = 3000;                 //转向时间，默认3秒后转向：3000
    public $sex = "保密";               //默认性别：男 女 保密
    public $ucsyn;                      //同步登录/退出的代码
    
    function __construct() {
        $act = trim($_REQUEST['act']);
        $parentid = (int)($_REQUEST['parentid']);
        $id = (int)($_REQUEST['id']);
        if($id > 0){
            $this->parentid = $id;
        }else{
            $this->parentid = $parentid;
        }
        if($act == "reg"){
            return $this->regNow();
        }
    }
    
    function regNow() {
        global $db;
        
        
        $idcard = htmlspecialchars(trim($_REQUEST['idcard']));
        $username = htmlspecialchars(trim($_REQUEST['username']));
        $password = trim($_REQUEST['password']);
        $rePassword = trim($_REQUEST['rePassword']);
        $name = htmlspecialchars(trim($_REQUEST['name']));
        $birthday = htmlspecialchars(trim($_REQUEST['birthday']));
        $sex = htmlspecialchars(trim($_REQUEST['sex']));
        $province = htmlspecialchars(trim($_REQUEST['province']));
        $city = htmlspecialchars(trim($_REQUEST['city']));
        $address = htmlspecialchars(trim($_REQUEST['address']));
        $zip = htmlspecialchars(trim($_REQUEST['zip']));
        $phone = htmlspecialchars(trim($_REQUEST['phone']));
        $mobile = htmlspecialchars(trim($_REQUEST['mobile']));
        $qq = htmlspecialchars(trim($_REQUEST['qq']));
        $email = htmlspecialchars(trim($_REQUEST['email']));
        $msn = htmlspecialchars(trim($_REQUEST['msn']));
        $verifyCode = strtolower($_REQUEST['verifyCode']);
        $verifyCodeCheck = strtolower($_SESSION['verifyCode']);
        
        $userTable = $this->userTable;
            
        $username = $name;
        
        $parentid = $this->parentid;
        
        $this->idcard = $idcard;
        $this->username = $username;
        $this->password = $password;
        $this->rePassword = $rePassword;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->sex = $sex;
        $this->province = $province;
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->qq = $qq;
        $this->email = $email;
        $this->msn = $msn;
        
        $checkChar = new checkChar;
        
        if(!$checkChar->checkNow("idcard",$idcard)){
            $this->msg = "请输入正确的身份证号码！";
            return;
        }
        /*
        if(!$checkChar->checkNow("str2-5-30",$username)){
            $this->msg = "用户名为5到12个字符，可包含汉字、字母、数字、下划线！";
            return;
        }
        */
        if(!$checkChar->checkNow("str2-5-30",$password)){
            $this->msg = "密码为5到12个字符，可包含字母、数字、下划线，区分大小写！";
            return;
        }
        
        if($password != $rePassword){
            $this->msg = "确认密码不正确！";
            return;
        }
        
        if(!$checkChar->checkNow("str2-4-10",$name)){
            $this->msg = "真实姓名在4到10个字符以内！";
            return;
        }
        
        if($province == "" || $city == ""){
            $this->msg = "请选择所在地区！";
            return;
        }
        /*
        if(!$checkChar->checkNow("date",$birthday)){
            $this->msg = "请输入正确的出生日期！";
            return;
        }
        
        if(!in_array($sex,array('男','女','保密'))){
            $this->msg = "请选择正确的性别！";
            return;
        }
        
        if(!$checkChar->checkNow("str2-10-100",$address)){
            $this->msg = "联系地址在10到100个字符以内！";
            return;
        }
        
        if(!$checkChar->checkNow("zip",$zip)){
            $this->msg = "请输入正确的邮政编码！";
            return;
        }
        */
        if(!$checkChar->checkNow("phone",$phone)){
            $this->msg = "请输入正确的联系电话！";
            return;
        }
        
        if(!$checkChar->checkNow("mobile",$mobile)){
            $this->msg = "请输入正确的手机号码！";
            return;
        }
        
        if(!$checkChar->checkNow("qq",$qq)){
            $this->msg = "请输入正确的QQ号码！";
            return;
        }
        
        if(!$checkChar->checkNow("mail",$email)){
            $this->msg = "请输入正确的E-mail！";
            return;
        }
        /*
        if(!$checkChar->checkNow("mail",$msn)){
            $this->msg = "请输入正确的MSN！";
            return;
        }
        */
        if(!$checkChar->checkNow("str2-4-4",$verifyCode)){
            $this->msg = "请输入正确的验证码！";
            return;
        }
        
        if($verifyCode != $verifyCodeCheck){
            $this->msg = "请输入正确的验证码！";
            return;
        }
        
        
        //判断身份证号码是否已经被注册
        $sql = "SELECT * FROM `".$userTable."` WHERE `idcard` ='".$idcard."' ";
        $result = $db->getX($sql);
        if(count($result) > 0){
            $this->msg = "该身份证号码已经被注册，请更换身份证号码！";
            return;
        }
        
        //在UCenter注册用户信息
        if($email == ""){
            $ucemail = date("YmdHos").rand(1,999)."@none.com";
        }else{
            $ucemail = $email;
        }
        $uid = uc_user_register($username, $password, $ucemail);
        if($uid <= 0) {
            if($uid == -1) {
                $this->msg = "用户名不合法！";
                return;
            } elseif($uid == -2) {
                $this->msg = "包含不允许注册的词语！";
                return;
            } elseif($uid == -3) {
                $this->msg = "用户名已经存在！";
                return;
            } elseif($uid == -4) {
                $this->msg = "E-mail 地址错误！";
                return;
            } elseif($uid == -5) {
                $this->msg = "该 E-mail 不允许注册！";
                return;
            } elseif($uid == -6) {
                $this->msg = "该 Email 已经被注册！";
                return;
            } else {
                $this->msg = "未知异常，请重试！";
                return;
            }
        }else{

            $year = "";
            $month = "";
            $day = "";
            
            if($birthday != ""){
                $birthdayArray = explode("-",$birthday);    
                if(isset($birthdayArray[0])){
                    $year = $birthdayArray[0];
                }
                
                if(isset($birthdayArray[1])){
                    $month = $birthdayArray[1];
                }
                
                if(isset($birthdayArray[2])){
                    $day = $birthdayArray[2];
                }
            }
            
            $dataArray = array(
                'uid' => $uid,
                'parentid' => $parentid,
                'idcard' => $idcard,
                'username' => $username,
                'password' => md5($password),
                'pword' => uc_authcode($password, 'ENCODE'),
                'name' => $name,
                'accountname' => $name,
                //'year' => $year,
                //'month' => $month,
                //'day' => $day,
                //'sex' => $sex,
                'province' => $province,
                'city' => $city,
                //'address' => $address,
                //'zip' => $zip,
                'phone' => $phone,
                'mobile' => $mobile,
                'qq' => $qq,
                'email' => $email
                //'msn' => $msn
            );
            
            $results = $db->add($userTable,$dataArray);
            $this->ul = "?do=member";     
            $this->sec = 1000;
            $this->msg = "恭喜，注册成功！";
            
            //处理基金池
            $this->dealPool($uid,$parentid);
            
            //登陆成功,使用 uc_authcode 函数 对 Cookie 进行加密。也可以使用自己的函数。
            _setcookie('auth', uc_authcode($uid."\t".$username."\t".time(), 'ENCODE'), 86400);
            
            //生成同步登录/退出的代码
            $ucsyn = uc_user_synlogin($uid);
            $this->ucsyn = $ucsyn;
        }
    }
    
    function dealUpPool($parentid,$level){
        global $db;
        
        $userTable = $this->userTable;
        $poolTable = $this->poolTable;
        $parentid = (int)$parentid;
        $level = (int)$level;
        if($parentid == 0){
            return ;    
        }
        
        if($level < 2 || $level > 6 ){
            return ;    
        }
        
        $sql = "UPDATE `".$poolTable."` SET `icount` = `icount` + 1 WHERE `uid` = '".$parentid."' AND `level` = '".$level."' ";
        $temp  = $db->updateX($sql);
        if($temp == 0){
            $data = array(
                'uid' => $parentid,
                'level' => $level,
                'opentime' => date("Y-m-d H:i:s"),
                'icount' => 1,
                'allmoney' => $allmoney,
                'payouts' => $payouts,
                'nowmoney' => $nowmoney,
                'infomark' => $infomark,
            );  
            $db->add($poolTable,$data);
        }
        
        //获取推荐人ID
        $tempUser = $db->get($userTable,$parentid);
        if(count($tempUser) == 0){
            return ;    
        }
        
        $pid = $tempUser[0]['parentid'];
        $lv = $level + 1;
        $this->dealUpPool($pid,$lv);
        
    }
    
    function dealPool($uid,$parentid){
        global $db;
        
        $poolTable = $this->poolTable;
        $uid = (int)$uid;
        $parentid = (int)$parentid;
        
        $data = array(
            'uid' => $uid,
            'level' => 1,
            'opentime' => date("Y-m-d H:i:s"),
            'icount' => 1,
            'allmoney' => $allmoney,
            'payouts' => $payouts,
            'nowmoney' => $nowmoney,
            'infomark' => $infomark,
        );
        
        $db->add($poolTable,$data);
        
        //处理上一级
        $this->dealUpPool($parentid,2);
            
    }
}

$obj = new register;
$msg = $obj->msg;
$ul = $obj->ul;
$sec = $obj->sec;
$ucsyn = $obj->ucsyn;

$parentid = $obj->parentid;
$idcard = $obj->idcard;
$username = $obj->username;
$name = $obj->name;
$birthday = $obj->birthday;
$sex = $obj->sex;
$province = $obj->province;
$city = $obj->city;
$address = $obj->address;
$zip = $obj->zip;
$phone = $obj->phone;
$mobile = $obj->mobile;
$qq = $obj->qq;
$email = $obj->email;
$msn = $obj->msn;


include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>