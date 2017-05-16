define(['ionic', 'main.controllers', 'main.services', 'main.template', 'main.directives', 'main.devices', 'ionicLazyLoad', 'citypicker'], function() {
    'use strict';

    return angular.module('main', ['ionic', 'main.controllers', 'main.services', 'main.template', 'main.directives', 'main.devices', 'ionicLazyLoad', 'citypicker','ui.router'])
    .run(['utils', 'common', 'myCart', 'footprint', 'geoService', '$rootScope', '$state', '$location', '$timeout', 'loadTemplateCache', '$interval', '$document',
      function(utils, common, myCart, footprint, geoService, $rootScope, $state, $location, $timeout, loadTemplateCache, $interval, $document) {

        t1 && clearTimeout(t1);
        t2 && clearTimeout(t2);
        t3 && clearTimeout(t3);

        $rootScope.conn = '';           //当前网络状态
        $rootScope.device = {};         //当前设备信息

        var tonggles = utils.getTonggles();

        $rootScope.receivePush = utils.get('receivePush');
        var setReceive = function(receive) {
            utils.set('receivePush', receive);
            $rootScope.receivePush = receive;
            if(tonggles.jpush){
                if(receive){
                    window.plugins.jPushPlugin.resumePush();
                }else{
                    window.plugins.jPushPlugin.stopPush();
                }
            }
        };
        $rootScope.setReceive = setReceive;

        //极光推送
        if(tonggles.jpush){
            $rootScope.device = device;

            var pushid = '';
            var hasInitJPush = false;

            var getConnType = function(){
                $rootScope.conn = navigator.connection.type;
            };

            var setTagsWithAlias = function(){
                var appid = utils.getAppid();
                var userid = utils.getProfile('userid');
                var app_userid = utils.getProfile('app_userid');
                var tags = [];
                var alias = 'guest';
                if(pushid && appid > 0 && userid > 0 && app_userid > 0){
                    tags = ["appid_"+appid,"userid_"+userid,"app_userid_"+app_userid];
                    alias = appid+"_"+userid;
                }
                window.plugins.jPushPlugin.setTagsWithAlias(tags, alias);
            };

            var bindDevice = function(){
                if(utils.get('auth')){
                    var data = {};
                    if(ionic.Platform.isIPad()) {
                        data.ipad_pushid = pushid;
                    }else if (ionic.Platform.isIOS()) {
                        data.ios_pushid = pushid;
                    }else if (ionic.Platform.isWindowsPhone()) {
                        data.wp_pushid = pushid;
                    }else if (ionic.Platform.isAndroid()) {
                        data.android_pushid = pushid;
                    }
                    common.req('setPushid', {data: data}, function(){});
                }
                setTagsWithAlias();
            };

            var getPushid = function() {
                if(pushid){
                    onGetPushid(pushid);
                }else{
                    window.plugins.jPushPlugin.getRegistrationID(onGetPushid);
                }
            };

            var onGetPushid = function(data) {
                if (!data) {
                    var t1 = window.setTimeout(getPushid, 1000);
                }else{
                    pushid = data;
                    bindDevice();
                }
            };

            $rootScope.$on('updateProfile', function(event, flag) {
                if(flag){
                    var profile = utils.get('profile');
                    if(profile.receive){
                        getPushid();    //获取pushid并绑定设备、设置tag、alias
                        setReceive(profile.receive);
                    }
                }
            });

            var initJPush = function(){
                if(!hasInitJPush && $rootScope.conn != 'none' ){
                    window.plugins.jPushPlugin.init();
                    getPushid();
                    if (device.platform != "Android") {
                        window.plugins.jPushPlugin.setDebugModeFromIos();
                        window.plugins.jPushPlugin.setApplicationIconBadgeNumber(0);
                    } else {
                        window.plugins.jPushPlugin.setDebugMode(true);
                        window.plugins.jPushPlugin.setStatisticsOpen(true);
                    }
                    hasInitJPush = true;
                    setReceive($rootScope.receivePush);     //设置是否接收push
                }
            };

            var onOpenNotification = function(event) {
                var alertContent;
                if (device.platform == "Android") {
                    alertContent = window.plugins.jPushPlugin.openNotification.alert;
                } else {
                    alertContent = event.aps.alert;
                }
                utils.alert(alertContent);
            };

            var onReceiveNotification = function(event) {
                var alertContent;
                if (device.platform == "Android") {
                    alertContent = window.plugins.jPushPlugin.receiveNotification.alert;
                } else {
                    alertContent = event.aps.alert;
                }
                utils.alert(alertContent);
            };

            var onReceiveMessage = function(event) {
                var message;
                if (device.platform == "Android") {
                    message = window.plugins.jPushPlugin.receiveMessage.message;
                } else {
                    message = event.content;
                }
                utils.alert(message);
            };

            var onOffline = function(){
                $rootScope.conn = 'none';
            };

            var onOnline = function(){
                getConnType();
                initJPush();
            };

            var onTagsWithAlias = function(event) {
                var result = "result code:" + event.resultCode + " ";
                result += "tags:" + event.tags + " ";
                result += "alias:" + event.alias + " ";
                console.log(result);
            };

            document.addEventListener("jpush.setTagsWithAlias", onTagsWithAlias, false);
            document.addEventListener("jpush.openNotification", onOpenNotification, false);
            document.addEventListener("jpush.receiveNotification", onReceiveNotification, false);
            document.addEventListener("jpush.receiveMessage", onReceiveMessage, false);
            document.addEventListener("offline", onOffline, false);
            document.addEventListener("online", onOnline, false);
            document.addEventListener('deviceready', function() {
                if('undefined' != typeof StatusBar){
                    StatusBar.show();
                    StatusBar.overlaysWebView(true);
                    StatusBar.styleDefault();
                }
                getConnType();
                initJPush();
            }, false);
        }

        //加载模板
        loadTemplateCache();

        var wxShareT;

        $rootScope.$on('$stateChangeSuccess',function(event, toState, toParams, fromState, fromParams){
            footprint({});                  //记录脚印
            $rootScope.homeActive = '';
            $rootScope.listActive = '';
            $rootScope.cartActive = '';
            $rootScope.newsActive = '';
            $rootScope.accountActive = '';
            if(toState.name == 'app.home' || toState.name == 'app.list'  || toState.name == 'app.cart' || toState.name == 'app.news'|| toState.name == 'app.account'||toState.name == 'app.oversea' ){
                $rootScope[toState.name.replace('app.','')+'Active'] = 'tab-item-active';
                if($location.search()['back']){
                    $rootScope.hideTabs = 'tabs-item-hide';
                }else{
                    $rootScope.hideTabs = '';
                }
            }else {
                $rootScope.hideTabs = 'tabs-item-hide';
            }
            if(tonggles.env == 'wechat' && tonggles.wx_share){
                wxShareT = setTimeout(function(){
                    common.initWxShare();       //修正WxJsApi的分享接口数据
                }, 1000);
            }
        });

        $rootScope.$on('clearWxShareT', function(event, data) {
            wxShareT && clearTimeout(wxShareT);
        });

        $interval(function(){
            $rootScope.cartCd = myCart.getCountDown() || '购物车';
        }, 1000);

        $rootScope.login = function(){
            common.login();
        };

        if(code){
            if(bind == 'wx'){
                //微信授权后自动绑定
                common.wechatBindNow(code, function(success, data){
                    if(success){
                        utils.alert('绑定成功');
                    }
                });
            }else if(!utils.get('auth')){
                //微信授权后自动登录
                common.wechatLogin(code, function(success, data){
                    if(success){
                        utils.goState('account', {}, true);         //直接进入个人中心
                    }
                });
            }
        }

        var statistic = function(){             //收集用户信息
            var show_time = new Date().getTime();
            var visitDt = {};
            if('object' == typeof vDt){
                visitDt = vDt;
                visitDt.show_time = show_time;
                visitDt.seconds = show_time - visitDt.in_time;
            }
            visitDt.url = $location.absUrl();
            visitDt.userid = utils.get('profile', 'userid');
            visitDt.phone = utils.get('profile', 'phone') || utils.get('phone');
            visitDt.sex = utils.get('profile', 'sex');
            visitDt.newbie = utils.get('profile', 'newbie');
            var pois = utils.getMyPois();
            for(var k in pois){
                visitDt[k] = pois[k];
            }
            utils.set('visitDt', visitDt);

            common.req('updateVisit', {data: visitDt}, function(){});
        };

        var getNearby = function(point){
            geoService.getLocalData(point, function(data){
                if(data && data.pois && data.pois.length){
                    var curPois;
                    for(var k in data.pois){
                        curPois = {
                            cityid: data.cityCode,
                            city: data.addressComponent.city,
                            districtid: 0,
                            district: data.addressComponent.district,
                            communityid: 0,
                            name: data.pois[k].name,
                            address: utils.makeAddress(data.pois[k].addr, data.addressComponent.province, data.addressComponent.city, data.addressComponent.district),
                            business: data.business,
                            lng: data.pois[k].point.x,
                            lat: data.pois[k].point.y
                        };
                        break;
                    }
                    if(curPois){
                        $rootScope.$broadcast('changeMyPois', curPois);
                        utils.setCurPois(curPois);
                        utils.setMyPois(curPois);
                    }
                }
                statistic();                                            //记录统计数据
            },function(){
                statistic();                                            //记录统计数据
            },1);
        };

        var locateNow = function(){
            if(utils.getMyPois()){
                statistic();                                            //记录统计数据
                return ;
            }
            geoService.getPoint(getNearby,function(error){
                geoService.getPointByIp(getNearby, function(error){
                    statistic();                                         //记录统计数据
                });                                                      //IP定位
            });
        };

        var initTab = function(){
            var appConf = utils.getAppConf() || {};
            $rootScope.tabsConf = appConf.tabs || {};
        };

        initTab();

        utils.remove('cache');                  //清空缓存

        $rootScope.initApp = function(){        //接受index.html的调用
            locateNow();
            initTab();
            $rootScope.$broadcast('initApp', 1);
        };

        document.getElementById("loading-view").style.display = 'none';
        document.getElementById("main-view").style.display = 'block';

    }])
    .config(['config', '$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$provide', function(config, $stateProvider, $urlRouterProvider, $ionicConfigProvider, $provide) {
        $ionicConfigProvider.navBar.alignTitle('center');
        $ionicConfigProvider.backButton.text('返回').previousTitleText(false);
        $ionicConfigProvider.views.transition('platform').maxCache(20).forwardCache(true);
        $ionicConfigProvider.tabs.style('standard').position('bottom');
        $ionicConfigProvider.scrolling.jsScrolling(true);
        $ionicConfigProvider.form.checkbox('circle');

        $stateProvider
        .state('index', {
            cache: true,
            url: '/index',
            templateUrl: 'tpl/index.html',
            controller: 'indexCtrl'
        })
        .state('app', {
            url: '/app',
            'abstract': true,
            templateUrl: 'tpl/tabs.html',
            controller: 'tabsCtrl'
        })
        .state('app.home', {
            cache: true,
            url: '/home/{groupid:[0-9]*}',
            views: {
                'tab-home': {
                    templateUrl: 'tpl/home.html',
                    controller: 'homeCtrl'
                }
            }
        })
        .state('app.list', {
            cache: true,
            url: '/list',
            views: {
                'tab-list': {
                    templateUrl: 'tpl/list.html',
                    controller: 'groupsCtrl'
                }
            }
        })

        .state('app.special', {
            cache: true,
            url: '/special/:specialid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/special.html',
                    controller: 'specialCtrl'
                }
            }
        })
        .state('app.cart', {
            cache: true,
            url: '/cart',
            views: {
                'tab-shopping': {
                    templateUrl: 'tpl/cart.html',
                    controller: 'cartCtrl'
                }
            }
        })
        .state('app.goods', {
            cache: true,
            url: '/goods/:goodsid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/goods.html',
                    controller: 'goodsCtrl'
                }
            }
        })
        .state('app.msg', {
            cache: false,       //不缓存
            url: '/msg',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/msg.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.msg-detail', {
            cache: true,
            url: '/msg/{msgid:[0-9]+}',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/msg_detail.html',
                    controller: 'msgDetailCtrl'
                }
            }
        })
        .state('app.news', {
            cache: true,
            url: '/news',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/news.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.news-detail', {
            cache: true,
            url: '/news/{newsid:[0-9]+}',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/news_detail.html',
                    controller: 'newsDetailCtrl'
                }
            }
        })
        .state('app.shop', {
            cache: true,
            url: '/shop/:groupid/:sub_groupid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/shop.html',
                    controller: 'shopCtrl'
                }
            }
        })
        .state('app.pay', {
            cache: false,       //不缓存
            url: '/pay',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/pay.html',
                    controller: 'payCtrl'
                }
            }
        })
        .state('app.pay-note', {
            cache: true,
            url: '/pay/note',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/pay_note.html',
                    controller: 'payNoteCtrl'
                }
            }
        })
        .state('app.pay-invoice', {
            cache: true,
            url: '/pay/invoice',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/pay_invoice.html',
                    controller: 'payInvoiceCtrl'
                }
            }
        })
        .state('app.orders', {
            cache: true,
            url: '/orders',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/orders.html',
                    controller: 'ordersCtrl'
                }
            }
        })

        .state('app.order-detail', {
            cache: true,
            url: '/order/:orderid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/order_detail.html',
                    controller: 'orderDetailCtrl'
                }
            }
        })
        .state('app.pay-address', {
            cache: true,
            url: '/pay/address',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/address.html',
                    controller: 'addressCtrl'
                }
            }
        })
        .state('app.address', {
            cache: true,
            url: '/address',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/address.html',
                    controller: 'addressCtrl'
                }
            }
        })
        .state('app.delivery-detial', {
            cache: true,
            url: '/delivery/:company/:postid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/delivery_detail.html',
                    controller: 'deliveryDetailCtrl'
                }
            }
        })
        .state('app.address-add', {
            cache: false,       //不缓存
            url: '/address/add',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/address_add.html',
                    controller: 'addressAddCtrl'
                }
            }
        })
        .state('app.pay-address-add', {
            cache: false,       //不缓存
            url: '/pay/address/add',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/address_add.html',
                    controller: 'addressAddCtrl'
                }
            }
        })
        .state('app.account', {
            cache: true,
            url: '/account',
            views: {
                'tab-mine': {
                    templateUrl: 'tpl/account.html',
                    controller: 'accountCtrl'
                }
            }
        })
        .state('app.member', {
            cache: true,
            url: '/member',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/member.html',
                    controller: 'accountCtrl'
                }
            }
        })
        .state('app.profile', {
            cache: true,
            url: '/profile',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/profile.html',
                    controller: 'profileCtrl'
                }
            }
        })
        .state('app.trader', {
            cache: true,
            url: '/trader',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader.html',
                    controller: 'traderCtrl'
                }
            }
        })
        .state('app.trader-withdraw', {
            cache: true,
            url: '/trader/withdraw',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader_withdraw.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.trader-income', {
            cache: true,
            url: '/trader/income',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader_income.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.trader-partner', {
            cache: true,
            url: '/trader/partner',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader_partner.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.trader-customer', {
            cache: true,
            url: '/trader/customer',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader_customer.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.trader-report', {
            cache: true,
            url: '/trader/report',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/trader_report.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.setup', {
            cache: false,       //不缓存
            url: '/setup',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/setup.html',
                    controller: 'setupCtrl'
                }
            }
        })
        .state('app.sex', {
            cache: true,
            url: '/sex',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/sex.html',
                    controller: 'sexCtrl'
                }
            }
        })
        .state('app.nickname', {
            cache: true,
            url: '/nickname',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/nickname.html',
                    controller: 'nicknameCtrl'
                }
            }
        })
        .state('app.birthday', {
            cache: true,
            url: '/birthday',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/birthday.html',
                    controller: 'birthdayCtrl'
                }
            }
        })
        .state('app.password', {
            cache: true,
            url: '/password',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/password.html',
                    controller: 'passwordCtrl'
                }
            }
        })
        .state('app.complain', {
            cache: true,
            url: '/complain',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/complain.html',
                    controller: 'complainCtrl'
                }
            }
        })
        .state('app.about', {
            cache: true,
            url: '/about',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/about.html',
                    controller: 'aboutCtrl'
                }
            }
        })
        .state('app.page', {
            cache: true,
            url: '/page/:pageid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/page.html',
                    controller: 'pageCtrl'
                }
            }
        })
        .state('app.activity', {
            cache: true,
            url: '/activity/:pageid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/page.html',
                    controller: 'pageCtrl'
                }
            }
        })
        .state('app.sub_shop', {
            cache: true,
            url: '/sub_shop',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/sub_shop.html',
                    controller: 'subShopCtrl'
                }
            }
        })
        .state('app.sub_shop-sign', {
            cache: true,
            url: '/sub_shop/sign',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/sub_shop.html',
                    controller: 'subShopCtrl'
                }
            }
        })
        .state('app.score_log', {
            cache: true,
            url: '/score_log',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/score_log.html',
                    controller: 'pageReqCtrl'
                }
            }
        })
        .state('app.coupon', {
            cache: true,
            url: '/coupon',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/coupon.html',
                    controller: 'couponCtrl'
                }
            }
        })
        .state('app.hongbao', {
            cache: true,
            url: '/hongbao',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/hongbao.html',
                    controller: 'couponCtrl'
                }
            }
        })
        .state('app.login', {
            cache: true,
            url: '/login',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/login.html',
                    controller: 'loginCtrl'
                }
            }
        })
        .state('app.register', {
            cache: true,
            url: '/register',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/register.html',
                    controller: 'registerCtrl'
                }
            }
        })
        .state('app.forgot', {
            cache: true,
            url: '/forgot',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/forgot.html',
                    controller: 'forgotCtrl'
                }
            }
        })
        .state('app.bind', {
            cache: false,
            url: '/bind',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/bind.html',
                    controller: 'bindCtrl'
                }
            }
        })
        .state('app.store', {
            cache: false,
            url: '/store/:storeid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/store.html',
                    controller: 'storeCtrl'
                }
            }
        })
        .state('app.storegoods', {
            cache: false,
            url: '/storegoods/:storeid/{recommend:[0-1]}/{groupid:[0-9]*}',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/storegoods.html',
                    controller: 'storeGoodsCtrl'
                }
            }
        })
        .state('app.storeclass', {
            cache: false,
            url: '/storeclass/:storeid',
            views: {
                'tab-main': {
                    templateUrl: 'tpl/storeclass.html',
                    controller: 'storeClassCtrl'
                }
            }
        })
        .state('app.oversea', {
            cache: false,
            url: '/oversea/{origin_place:[0-9]*}',
            views: {
                'tab-oversea': {
                    templateUrl: 'tpl/oversea.html',
                    controller: 'overseaCtrl'
                }
            }
        })
        $urlRouterProvider.otherwise(config.homeUrl);
    }]);
});