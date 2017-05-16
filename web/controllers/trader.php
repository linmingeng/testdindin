<?php
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/sms_module.php';
require_once BASE_PATH.'/modules/user_module.php';
require_once BASE_PATH.'/modules/app_user_module.php';
require_once BASE_PATH.'/modules/trader_module.php';
require_once BASE_PATH.'/modules/order_module.php';
require_once BASE_PATH.'/modules/visit_module.php';
require_once BASE_PATH.'/modules/income_module.php';
/**
 * 分销商控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class trader {
    
    function __construct(){
        $this->trader_module = new trader_module();
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/trader/add
    function add_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($appid,'trader');
        if($conf['trader']['open'] !=  1){
            return array('alert' => '活动暂未开始，请联系客服！');
        }
        if(strtotime($conf['trader']['start_at']) > time()){
            return array('alert' => '活动开始时间：'.$conf['trader']['start_at']);
        }
        if(strtotime($conf['trader']['end_at']) < time()){
            return array('alert' => '本次活动已结束，请期待下一次吧！');
        }
        if(!$conf['trader']['txt']){
            $conf['trader']['txt'] = '分销商';
        }
        $traderData = $this->trader_module->getTraderData($appid, $user_info['userid']);
        if(is_array($traderData)){
            if($traderData['status'] == 0){          //分销商状态；-1=冻结; 0=未审核; 1=已审核; 
                return array('alert' => '您已申请“'.$conf['trader']['txt'].'”，请等待审核！');
            }else if($traderData['status'] == -1){   //分销商状态；-1=冻结; 0=未审核; 1=已审核; 
                return array('alert' => '您的“'.$conf['trader']['txt'].'”资格已被冻结，<br>如有疑问请联系客服！');
            } 
            return array('confirm' => array('til' => '', 'txt' => '您已经是'.$conf['trader']['txt'].'啦！<br>去查看自己的收入吗？', 'link' => 'tab/mine/trader'));
            //return array('alert' => '您已经是'.$conf['trader']['txt'].'啦！');
        }else if($conf['trader']['need_buy'] == 1){
            $order_module = new order_module;
            if($order_module->hadBuy($appid, $user_info['userid']) == 0){
                return array('confirm' => array('til' => '', 'txt' => '购买我们的产品并体验后，<br>才能申请“'.$conf['trader']['txt'].'”。<br>现在去购买吗？', 'link' => 'tab/home'));
            }
        }
        $exp = (int)$conf['trader']['send_exp'];
        $money = $conf['trader']['send_money'];
        if($money){
            $exp += round($money);
        }
        $level = (int)$conf['trader']['level'];
        if($level < 1){
            $level = 1;
        }
        if($level > 3){
            $level = 3;
        }
        $inviter_uid = (int)request('inviter_uid');
        $app_user_module = new app_user_module;
        $app_user = $app_user_module->getAppUserDetail($appid, $user_info['userid']);
        if(count($app_user) && isset($app_user['inviter_uid']) && $inviter_uid == 0 ){
            $inviter_uid = $app_user['inviter_uid'];   //设置默认的邀请人id
        }
        
        $res = $this->trader_module->addNow($appid, $user_info['userid'], $traderData['inviter_uid'], $money, $exp, $level); 
        if(is_array($res) && $res['traderid'] > 0){
            $app_user_module->updateTrader($appid, $user_info['userid'], 1);        //设置分销商状态
            $iData = array(
                'appid' => $appid,
                'userid' => $user_info['userid'],
                'type' => 3,        //分类；0=未知收入; 1=订单提成; 2=下线提现奖励; 3=系统奖励; 7=提现到账户余额; 8=提现到支付宝; 9=提现
                'money' => $money,
                'data' => '{"act":"trader_apply"}',
                'status' => 1,
                'add_at' => time()
            );
            $income_module = new income_module;
            $income_module->addNow($iData);       //添加收入记录
            
            $user_module = new user_module;
            $phone = $user_module->getPhone($user_info['userid']);      //获取手机号
            if($phone){
                $sms_module = new sms_module();
                $sms_module->addNow($phone, 4, $appid);                 //发送加入分销商的通知短信
            }
            $key = 'user_trader_info:'.$appid.':'.$user_info['userid'];
            $cache->del($key);                                                      //清除缓存
        }
        return $res;
    } 
    
    //获取分销商伙伴列表, 调用网址：[GET] http://localhost/api/index.php?/trader/customer&page=1&appid=1
    function customer_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'trader_customer_list:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->trader_module->getCustomer($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //获取分销商伙伴列表, 调用网址：[GET] http://localhost/api/index.php?/trader/list&page=1&appid=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'trader_user_list:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->trader_module->getList($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //获取分销商信息, 调用网址：[GET] http://localhost/api/index.php?/trader/info&appid=1
    function info_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            return ;
        }
        
        $appid = (int)request('appid');
        $key = 'user_trader_info:'.$appid.':'.$user_info['userid'];
        $cache->get($key);                                  //获取缓存，处理304
        
        $trader = $this->trader_module->getTraderDetail($appid, $user_info['userid']);
        
        $order_module = new order_module;
        $data = $order_module->getTraderOrderNum($appid, $user_info['userid'], 7);   //7日订单 7日销售
        
        $visit_module = new visit_module;
        $data['uv'] = $visit_module->getUv($appid, $user_info['userid'], 7);        //7日访问
        
        if(count($trader)){
            $data['trader'] = $trader;
        }
        
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    }
    
    //获取分销商订单列表, 调用网址：[GET] http://localhost/api/index.php?/trader/order_list&page=1&appid=1 todo: 未使用
    function order_list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'trader_order_list:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $order_module = new order_module;
        $data = $order_module->getTraderOrder($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
}