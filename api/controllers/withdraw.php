<?php
require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/trader_module.php';
require_once BASE_PATH.'/modules/income_module.php';
require_once BASE_PATH.'/modules/withdraw_module.php';
/**
 * 提现记录控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class withdraw {
    
    function __construct(){
        $this->withdraw_module = new withdraw_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/withdraw/apply&appid=1
    function apply_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $money = floatval(request('money'));
        
        $app_conf_module = new app_conf_module;
        $conf = $app_conf_module->getConf($appid,'trader');
        if(!isset($conf['trader']) || $conf['trader']['open'] !=  1){
            return array('alert' => '暂时无法提现，请联系客服！');
        }
        if(!$conf['trader']['txt']){
            $conf['trader']['txt'] = '分销商';
        }
        $trader_module = new trader_module;
        $traderData = $trader_module->getTraderMoney($appid, $user_info['userid']);
        if(is_array($traderData)){
            if($traderData['status'] == 0){          //分销商状态；-1=冻结; 0=未审核; 1=已审核; 
                return array('alert' => '您的“'.$conf['trader']['txt'].'”资格暂未审核，<br>如有疑问请联系客服！');
            }else if($traderData['status'] == -1){   //分销商状态；-1=冻结; 0=未审核; 1=已审核; 
                return array('alert' => '您的“'.$conf['trader']['txt'].'”资格已被冻结，<br>如有疑问请联系客服！');
            }
            if($conf['trader']['min_withdraw'] > 0 && $conf['trader']['min_withdraw'] > $money){
                return array('alert' => '可提现金额不足，<br>最小提现金额：'.$conf['trader']['min_withdraw'].'元！');
            }
            if($money < $traderData['money']){
                return array('alert' => '可提现金额不足！');
            }
            $reduce = $trader_module->reduceTraderMoney($appid, $user_info['userid'], $money);   //扣除余额
            if($reduce){
                $data = array(
                    'appid' => $appid,
                    'userid' => $user_info['userid'],
                    'money' => $money,
                    'type' => 1,
                    'inviter_uid' => $traderData['inviter_uid'],
                    'status' => 0,
                    'add_at' => time(),
                    'flag' => 1
                );
                $withdrawid = $this->withdraw_module->addNow($data);  //添加提现记录
                
                $key = 'withdraw:'.$appid.':'.$user_info['userid'].':1';
                $cache->del($key);                                                      //清除缓存
                
                $key = 'user_trader_info:'.$appid.':'.$user_info['userid'];
                $cache->del($key);                                                      //清除缓存
                
                if($withdrawid && $traderData['inviter_uid']){         //处理 上线奖励
                    $invTraderData = $trader_module->getTraderDetail($appid, $traderData['inviter_uid']);
                    if(is_array($invTraderData) && isset($invTraderData['status']) && $invTraderData['status'] == 1){
                        $prize_rate = (int)$invTraderData['sub_rate'];
                        $prize_money = floor($money*$prize_rate)/100;
                        $income_module = new income_module;
                        $dt = array(
                            'appid' => $appid,
                            'userid' => $traderData['inviter_uid'],
                            'orderid' => 0,
                            'type' => 2,      //分类；0=未知收入; 1=订单提成; 2=下线提现奖励; 3=系统奖励; 7=提现到账户余额; 8=提现到支付宝; 9=提现到银行卡; 
                            'money' => $prize_money,
                            'status' => 1,    //状态；0=未完成; 1=完成; 
                            'data' => json_encode(array('userid' => $user_info['userid'], 'withdrawid' => $withdrawid)),
                            'add_at' => time()
                        );
                        $incomeid = $income_module->addNow($dt);    //添加收入日志
                        if($incomeid){                              //发放奖励
                            $success = $trader_module->sendMoney($appid, $traderData['inviter_uid'], $prize_money, round($prize_money));
                        }
                    }
                }
                return array('withdrawid' => $withdrawid);
            }else{
                return '可提现金额不足！';
            }
        }else{
            return '请重新登录！';
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/withdraw/list&page=1&appid=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'withdraw:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->withdraw_module->getWithdraw($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}