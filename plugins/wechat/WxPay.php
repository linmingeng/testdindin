<?php 
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: text/html;charset=utf-8');
include '../config/config.php';
include '../libraries/utils.php';
include '../libraries/mysqli.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

$protocol = 'http:';
if(isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'){
    $protocol = 'https:';
}

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信安全支付</title>
    <script type="text/javascript">
    function goBack(){
        window.location.href = '<?php echo $protocol;?>//<?php echo $host;?>/?uri=/app/orders';
    }
    </script>
</head>
<body style="text-align:center;">
    <br/>
    <br/>
    <?php
    function showError($msg, $domain = ''){
        echo '<span style="color:#EF4F4F;font-size:24px">'.$msg.'</span>';
        echo '<br><br><button style="width:100%; padding:0 14px; height:42px; border-radius: 5px;background-color:#f7f7f7; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#454545;  font-size:16px;" type="button" onclick="goBack()" >返回</button>';
        exit;
    }
    
    //note 禁止对全局变量注入
    if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
        showError('请返回重试！code: 1001');
    }
    
    $token = trim(request('token'));
    $userid = intval(request('userid'));
    $appid = intval(request('appid'));
    $orderid = intval(request('orderid'));
    
    if($userid <= 0 || $appid <= 0 || $orderid <= 0 || !$token ){
        showError('无效的请求！code: 1001');
    }
    if(md5($userid.' '.$appid.' '.$orderid.' '.$secret_key) != $token){
        showError('无效的请求！code: 1002');
    }
    
    $code = trim(request('code'));
    $state = trim(request('state'));
    $redirected = trim(request('redirected'));
    
    $sql = "SELECT `name`,`domain`,`ver`,`typeid`,`wx_pay`,`wx_appid`,`wx_appsecret`,`mchid`,`mchkey`,`sslcert`,`sslkey` FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `flag` = 1 ";
    $ret = $db->getX($sql);
    if(!is_array($ret) || !count($ret)){
        showError('当前应用不存在！请返回！code: 1003');
    }
    $appData = $ret[0];
    if(!$appData['wx_pay'] ){
        response('暂未启用微信支付！code: 1004');
    }
    function getConf($k){
        global $appData;
        $conf = array(
            'APPID' => $appData['wx_appid'],
            'APPSECRET' => $appData['wx_appsecret'],
            'MCHID' => $appData['mchid'],
            'MCHKEY' => $appData['mchkey'],
            'SSLCERT_PATH' => '../'.$appData['sslcert'],
            'SSLKEY_PATH' => '../'.$appData['sslkey']
        );
        if(!isset($conf[$k])){
            showError('配置信息“'.$k.'”不存在！code: 1005');
        }
        return $conf[$k];
    }
    
    require_once "../lib/WxPay.Api.php";
    require_once "WxPay.JsApiPay.php";
    
    $pay_url = $protocol."//".$host.$_SERVER['PHP_SELF']."?redirected=true&userid=".$userid."&appid=".$appid."&orderid=".$orderid."&token=".$token;
    $redirect_uri = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.getConf('APPID').'&redirect_uri='.urlencode($pay_url).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
    if($code && $state == 'STATE'){
        $sql = "SELECT `orderid`,`order_number`,`appid`,`userid`,`goods_info`,`price`,`status` FROM `the_order` WHERE `orderid` = '".$orderid."' AND `flag` = 1 ";
        $ret = $db->getX($sql);
        if(!is_array($ret) || !count($ret)){
            showError('订单不存在！请返回！code: 1007');
        }
        $orderData = $ret[0];
        if($orderData['appid'] != $appid){
            showError('当前应用不存在该订单！code: 1008');
        }else if($orderData['userid'] != $userid){
            showError('当前用户不存在该订单！code: 1009');
        }else if($orderData['status'] == 0){
            showError('订单已过期！请返回！code: 1010');
        }else if($orderData['status'] > 1){
            showError('订单已支付，无需重复支付！请返回！code: 1011');
        }elseif($orderData['status'] < 0){
            showError('订单存在异常！请联系客服！code: 1012');
        }
    }else if($redirected == ''){                //主动请求授权
        header('location:'.$redirect_uri);      
        exit;
    }else if($redirected == 'true' && !$code){  //没返回code则：授权失败
        showError('请返回重试！code: 1013');
    }
    $order_number = $orderData['order_number'];
    $body = $appData['name'].'订单号：'.$order_number;
    $attachData = $orderData;
    $attachData['token'] = $token;
    unset($attachData['goods_info']);
    unset($attachData['price']);
    unset($attachData['status']);
    $attach = myjson_encode($attachData);            //附加信息为：orderid
    $trade_no = $order_number.'_'.rand(1000,9999);   //订单号
    $total_fee = $orderData['price']*100;            //价格（分）
    $goods_tag = $body;
    $notify_url = $protocol."//".$host.str_replace('WxPay.php','WxPayNotify.php',$_SERVER['PHP_SELF']);   //支付回调地址
    
    //①、获取用户openid
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();
    if(!$openId){
        header('location:'.$protocol.'//'.$host.'/?uri=/app/orders');      
        exit;
    }
    
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody($body);
    $input->SetAttach($attach);
    $input->SetOut_trade_no($trade_no);
    $input->SetTotal_fee($total_fee);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 3600));
    $input->SetGoods_tag($goods_tag);
    $input->SetNotify_url($notify_url);
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $order = WxPayApi::unifiedOrder($input);
    if($order['return_code'] == 'FAIL' ){
        showError('微信支付服务器忙！<br>'.$order['return_msg'].'');
    }else if($order['result_code'] != 'SUCCESS' ){
        showError('微信支付服务器忙！<br>err_code: '.$order['err_code'].'<br>err_code_des: '.$order['err_code_des'].'');
    } 
    
    $jsApiParameters = $tools->GetJsApiParameters($order);
    
    $data = array(
        'wx_open_id' => $openId,
        'wx_pay_order' => $order,
        'wx_pay_parameters' => $jsApiParameters
    );
    
    //更新 订单信息 
    $sql = "UPDATE `the_order` SET `pay_data`='".json_encode($data)."', `pay_status` = 1, `pay_at` = '".time()."', `update_at` = '".time()."'  WHERE `pay_status` < 2 AND `orderid` = '".$orderData['orderid']."' ";
    $state = $db->updateX($sql);
    if(!$state){
        showError('无效的请求！code: 1002');    
    }
    ?>
    <script type="text/javascript">
    function jsApiCall(){
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $data['wx_pay_parameters']; ?>,
            function(res){
                if(res.err_msg == 'get_brand_wcpay_request:ok'){            //支付成功
                    alert('恭喜！支付成功！');
                    goBack();
                }else if(res.err_msg == 'get_brand_wcpay_request:cancel'){  //取消支付
                    //alert('您取消了支付！');
                } 
            }
        );
    }
    function callpay(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    </script>

    订单号：<br/><br/>
    <?php
        echo $order_number;
    ?>
    <br/><br/>
    <?php
    /*
        if($orderData['goods_info']){
            echo '商品：<br/><br/>';
            echo $orderData['goods_info'];
            echo '<br/><br/>';
        }
    */
    ?>
    <font color="#69af05"><b>支付金额为<span style="color:#EF4F4F;font-size:42px"><?php echo $total_fee/100;?></span>元</b></font><br/><br/>
    <div align="center">
        <button style="width:98%; padding:0 14px; height:42px; border-radius: 5px;background-color:#69af05; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#ffffff;  font-size:16px;" type="button" onClick="callpay()" >立即支付</button>
        <br>
        <br>
        <button style="width:98%; padding:0 14px; height:42px; border-radius: 5px;background-color:#f7f7f7; border:1px solid rgba(0, 0, 0, 0.2); cursor: pointer;  color:#454545;  font-size:16px;" type="button" onClick="goBack()" >返回</button>
    </div>
</body>
</html>