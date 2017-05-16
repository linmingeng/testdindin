define(['ionic', 'storage'], function() {
    'use strict';

    var service = angular.module('main.services', ['ionic', 'LocalStorageModule']);

    service.factory('req', ['utils', 'config', '$http', '$location', '$rootScope', function(utils, config, $http, $location, $rootScope){
        return function(arg){
            var k = arg.keyName || null;
            var conf = {};
            angular.copy(arg, conf);

            if(k){
                var t = config.resConfig[k];
                conf.apiType = t.apiType;
                conf.url = t.url;
                conf.method = t.method;
                delete conf.keyName;
            }
            if(!conf.headers)
                conf.headers = {};

            function setParams(conf){
                var l = conf.url.split(':'),
                    d = conf.params;
                for(var i=1;i<l.length;i++){
                    var a = l[i].split('/');
                        k = a[0];
                    if(d[k]){
                        l[i] = d[k];
                        for(var j=1;j<a.length;j++){
                            l[i] += '/' + a[j];
                        }
                        delete(d[k]);
                    }else{
                        console.error('Missing parameter "' + k + '"!');
                    }
                }
                conf.url = l.join('');
                return conf;
            }
            if(conf.url.indexOf(':', conf.url.indexOf('https:')?-1: 6) != -1){
                conf = setParams(conf);
            }
            if(conf.url.indexOf(':', conf.url.indexOf('http:')?-1: 5) != -1){
                conf = setParams(conf);
            }
            if(conf.apiType == 'plugins'){         //调用plugins
                conf.url = utils.getAppServer() + '/' +conf.url + '?';
            }else{
                conf.url = utils.getApiServer() + conf.url;
            }
            delete conf.apiType;
            if(utils.get('auth')){
                angular.extend(conf.headers, {'auth': utils.get('auth')});
            }
            if(utils.get('wx_auth')){
                angular.extend(conf.headers, {'wx_auth': utils.get('wx_auth')});
            }
            var conn = $rootScope.conn;
            var platform = $rootScope.device.platform || '';
            var platform_version = $rootScope.device.version || '';
            conf.url += '&modal='+utils.getModal()+'&version='+utils.getVersion()+'&platform='+platform+'&platform_version='+platform_version+'&conn='+conn+'&reqid='+utils.getReqid()+'&inviter_uid='+utils.getInviterUid()+'&appid='+utils.getAppid()+'&userid='+(utils.getProfile('userid') || 0)+'&time=' + new Date().getTime();
            conf.timeout = 10000;
            if(!conf.silentReq){
                utils.showLoading();
                if('none' == conn){
                    utils.alert('网络错误！请检查您的网络设置！');
                }
            }
            var r = $http(conf);
            r.success(function(d, s, h, c){
                utils.hideLoading();
                d = d || {};
                if(d.msg){
                    utils.tip(d.msg);
                }
                if(d.tip){
                    utils.tip(d.tip);
                }
                if(d.alert){
                    utils.alert(d.alert);
                }
                if(d.confirm){
                    utils.confirm(d.confirm.til, d.confirm.txt, function(ok){
                        if(ok){
                            utils.toUrl(d.confirm.link);
                        }
                    });
                }
                if(d.link){
                    if(d.link == 'back'){
                        utils.goBack();
                    }else{
                        utils.toUrl(d.link);
                    }
                }
                if(d.code >= 400){
                    if((d.code == 403) && $location.url() != '/app/login'){
                        utils.remove('auth');
                        utils.remove('wx_auth');
                        utils.remove('profile');
                        $rootScope.login();
                        return;
                    }
                }
                if(s >= 500 && !conf.silentReq){
                    utils.tip('系统繁忙！请稍后重试！');
                    return ;
                }
            });
            r.error(function(d, s, h, c){
                utils.hideLoading();
                if(s == 0 && !conf.silentReq && 'none' != conn){
                    utils.alert('网络错误！请检查您的网络设置！');
                }
                d = d || {};
            });
            return r;
        };
    }]);

    service.factory('cacheReq', ['utils', 'req', function(utils, req){
        return function(conf, cacheKey, storeKey){
            if(!cacheKey)
                return console.error('losing params in cache service!');
            var data = null,
                cache = utils.getCache(cacheKey, storeKey) || {},
                hasCache = false,
                hasExpires = true,
                request = null;

            if(!conf.headers){
                conf.headers = {};
            }
            if(!cache.data){
                cache.data = {};
            }
            if(conf.params && conf.params.page){
                data = cache.data[conf.params.page];
            }else{
                data = cache.data;
            }
            if(!utils.isEmpty(data)){
                hasCache = true;
            }
            if(hasCache){
                if(cache.expiresTime){
                    if(Date.parse(cache.expiresTime) > new Date().getTime() ){     //缓存未过期
                        hasExpires = false;
                    }
                }
                if(cache.modifyTime){
                    angular.extend(conf.headers, {'IF_MODIFIED_SINCE': cache.modifyTime});
                }
                conf.silentReq = true;     //有缓存时，静默更新
            }
            if(conf.refresh){
                hasExpires = true;
            }
            if(hasExpires){
                request = req(conf);
                request.success(function(d, s, h){
                    if(s != 304){
                        if(h('Last-Modified')){
                            cache.modifyTime = h('Last-Modified');
                        }
                        if(h('Expires')){
                            cache.expiresTime = h('Expires');
                        }
                        if(conf.params && conf.params.page){
                            cache.data[conf.params.page] = d;
                        }else{
                            cache.data = d;
                        }
                        utils.setCache(cacheKey, cache, storeKey);
                    }else{
                        if(h('Expires')){
                            cache.expiresTime = h('Expires');
                            utils.setCache(cacheKey, cache, storeKey);
                        }
                    }
                });
            }

            return {
                success: function(fn){
                    if(hasCache){
                        fn(data);
                    }
                    if(hasExpires){
                        request.success(function(d, s, h, c){
                            if(s != 304 ){      //没缓存，且有新数据时，才回调
                                if('object' != typeof(d)){
                                    d = {};
                                }
                                fn(d, s, h, c);
                            }
                        });
                    }
                },
                error: function(fn){
                    if(hasExpires){
                        request.error(fn);
                    }
                }
            }
        };
    }]);

    service.factory('geoReq', ['utils', 'config', '$http', function(utils, config, $http){
        return function(arg){
            var k = arg.keyName || null;
            var conf = {};
            angular.copy(arg, conf);

            if(k){
                var t = config.resConfig[k];
                conf.url = t.url;
                conf.method = t.method;
                delete conf.keyName;
            }
            if(!conf.headers)
                conf.headers = {};

            function setParams(conf){
                var l = conf.url.split(':'),
                    d = conf.params;
                for(var i=1;i<l.length;i++){
                    var a = l[i].split('/');
                        k = a[0];
                    if(d[k]){
                        l[i] = d[k];
                        for(var j=1;j<a.length;j++){
                            l[i] += '/' + a[j];
                        }
                        delete(d[k]);
                    }
                    else
                        console.error('Missing parameter "' + k + '"!');
                }
                conf.url = l.join('');

                return conf;
            }

            if(conf.url.indexOf(':', conf.url.indexOf('https:')?-1: 6) != -1){
                conf = setParams(conf);
            }

            if(conf.url.indexOf(':', conf.url.indexOf('http:')?-1: 5) != -1){
                conf = setParams(conf);
            }

            if(config.baiduGeoConfig.host)
                conf.url = config.baiduGeoConfig.host + conf.url;

            if(config.baiduGeoConfig.ak && conf.params)
                conf.params.ak = config.baiduGeoConfig.ak;

            if(config.baiduGeoConfig.geotable_id && conf.params)
                conf.params.geotable_id = config.baiduGeoConfig.geotable_id;

            if(conf.method == 'JSONP')
                conf.params.callback = 'JSON_CALLBACK';

            conf.timeout = 10000;

            utils.showLoading();

            var r = $http(conf);

            r.success(function(d, s, h, c){
                utils.hideLoading();
                if('object' != typeof(d)){
                    d = {};
                }
                if(s == 200 && d.status != 0 && !d.message){
                    utils.tip('网络请求失败！');
                    return ;
                }
                if(d.message){
                    utils.tip(d.message);
                }
            });
            r.error(function(d, s, h, c){
                utils.hideLoading();
                if(s == 0){
                    utils.tip('网络异常！请检查您的网络设置！');
                }
            });
            return r;
        }
    }]);

    service.factory('confFactory', ['config', function(config){
        return function (k, p){
            var t = getConf(config.resConfig, 0),r = {};
            function getConf(c, i){
                if(i >= k.length || !c[k[i]])
                    return null;
                if(i == k.length - 1)
                    return c[k[i]];
                c = c[k[i++]];
                return getConf(c, i);
            }
            for(var i in t){
                r[i] = t[i];
            }
            for(var i in p){
                r[i] = p[i];
            }
            return r;
        };
    }]);

    service.factory('common', ['utils', 'req', 'cacheReq', '$rootScope', '$ionicPopup', '$ionicBackdrop', '$ionicModal', '$timeout', 'footprint', '$location', '$state',
      function(utils, req, cacheReq, $rootScope, $ionicPopup, $ionicBackdrop, $ionicModal, $timeout, footprint, $location, $state){
        return {
            getAppDetail: function(appid, cb, refresh, silentReq){            //[未使用]获取应用信息
                var conf = utils.confFactory(['getAppDetail'], {params: {appid: appid}});
                if('undefined' == typeof silentReq){
                    silentReq = true;
                }
                conf.silentReq = silentReq;
                conf.refresh = refresh;
                if('none' == utils.checkConn(conf.silentReq)){
                    cb && cb({});
                    return ;
                }
                var dt = {
                    app: {},
                    groups: {},
                    groupsNum: 0
                };
                var req = cacheReq(conf,'appDetail_'+appid, 'store');
                var that = this;
                req.success(function (data) {
                    if(data.code == 200){
                        delete data.code;
                        dt.app = data;
                        dt.app.logo = utils.getImgUrl(dt.app.logo);
                        dt.app.default_face = utils.getImgUrl(dt.app.default_face);
                        dt.app.loading_image = utils.getImgUrl(dt.app.loading_image);
                        if(data.groups){
                            for(var k in data.groups){
                                dt.groupsNum ++;
                                dt.groups[k] = data.groups[k];
                            }
                        }
                        cb && cb(dt);
                    }else{
                        cb && cb(null);
                    }
                });
                req.error(function(d){
                    cb && cb(d || {});
                });
            },
            req: function(resid, params, cb){
                var conf = utils.confFactory([resid], params || {});
                if('none' == utils.checkConn(conf.silentReq)){
                    cb && cb({});
                    return ;
                }
                var fn = function(d, s, h, c){
                    cb && cb(d || {}, s, h, c);
                };
                var r = req(conf).success(fn).error(fn);
            },
            cacheReq: function(resid, params, ck, cb, opt){
                var conf = utils.confFactory([resid], params || {});
                if('none' == utils.checkConn(conf.silentReq)){
                    cb && cb({});
                    return ;
                }
                var fn = function(d, s, h, c){
                    cb && cb(d || {}, s, h, c);
                };
                if('refresh' == opt){
                    utils.removeCache(ck);
                }
                var r = cacheReq(conf, ck);
                r.success(fn);
                r.error(fn);
            },
            pageReq: function(resid, params, page, uk, ck, $scope, opt, cb, concat){
                var dt = {};
                var conf = utils.confFactory([resid], params || {});
                var end_req = function(hasMore, opt){
                    cb && cb(dt);
                    $timeout(function(){
                        $scope.dt.hasMore = hasMore;
                        $scope.dt.isEmpty = !$scope.dt.items.length;
                        utils.broadcastScroll($scope, opt);
                    }, 500);        //延时处理，否则会连续请求下一页
                };
                var success_fn = function(data){
                    if(data.code == 200){
                        if(page == 1 && 'undefined' == typeof concat){
                            $scope.dt.items = data.results || [];
                        }else{
                            $scope.dt.items = $scope.dt.items.concat(data.results || []);
                        }
                        if(uk){
                            $scope.dt.items = utils.uniqueData($scope.dt.items || [], uk);
                        }
                        end_req(!!data.next, opt);
                    }else{
                        end_req(false, opt);
                    }
                };
                var error_fn = function(){
                    end_req(false, opt);
                };
                if(ck){
                    if('refresh' == opt){
                        utils.removeCache(ck);
                    }
                    var r = cacheReq(conf, ck);
                    r.success(success_fn);
                    r.error(error_fn);
                }else{
                    req(conf).success(success_fn).error(error_fn);
                }
            },
            getPage: function(appid, pageid, cb, opt){
                this.cacheReq('getPage', {params: {pageid: pageid}}, 'page_'+appid+'_'+pageid, cb, opt);
            },
            loginNow: function(phone, loginCb, newbie){
                if( !$rootScope.loginData ){
                    $rootScope.loginData = {phone: utils.get('phone'), password: '', agree: 1, error: 0};
                }
                var that = this;

                $rootScope.forgot = function(){
                    $rootScope.loginPopup.close();
                    utils.goUrl('forgot');
                };
                var btnTxt = '登录';
                var okStr = '恭喜！登录成功！';
                var til = '请输入登录密码';
                var wechat_userid = 0;
                if(newbie == 3){        //绑定用户(已注册的手机帐)
                    btnTxt = '绑定';
                    okStr = '恭喜！绑定成功！';
                    til = '请输入登录密码';
                    wechat_userid = utils.getProfile('userid');   //新的微信帐户id
                }else if(newbie == 2){  //绑定用户(新手机帐户)
                    btnTxt = '绑定';
                    okStr = '恭喜！绑定成功！';
                    til = '请设置登录密码';
                }else if(newbie == 1){
                    btnTxt = '注册';
                    okStr = '恭喜！注册成功！';
                    til = '请设置登录密码';
                }
                $rootScope.loginPopup = $ionicPopup.confirm({
                    title: til,
                    template: '<input type="password" ng-model="loginData.password" style="border:1px solid #ccc;padding:0 8px;">'+(newbie==0?'<div style="width:100%;text-align:right;padding-top: 10px;"> <a ng-click="forgot();" href="javascript:;">忘记密码？</a><div>':''),
                    scope: $rootScope,
                    buttons: [
                        {
                            text: '取消',
                            type: 'button-clear button-positive split',
                            onTap: function(e){
                                return 'false';
                            }
                        },
                        {
                            text: btnTxt,
                            type: 'button-clear button-positive',
                            onTap: function(e){
                                if(!$rootScope.loginData.password){
                                    e.preventDefault();
                                }else{
                                    return $rootScope.loginData.password;
                                }
                            }
                        }
                    ]
                });

                $rootScope.loginPopup.then(function(password) {
                    if(password && password != 'false'){
                        $rootScope.loginData.password = '';
                        if(/^[a-zA-Z0-9_]{6,18}$/.test(password)){
                            var conf = utils.confFactory(['phoneLogin'], {data: {phone: phone, password: password, wechat_userid: wechat_userid}});
                            req(conf).success(function(data){
                                if(data.code == 200){
                                    if(data.passwordError){
                                         $rootScope.loginData.error++;
                                         if($rootScope.loginData.error >= 2){
                                             $ionicPopup.confirm({
                                                 title: '重置密码',
                                                 template: '您需要重置密码吗？',
                                                 cancelText: '不需要',
                                                 cancelType: 'button-clear button-positive split',
                                                 okText: '好的',
                                                 okType: 'button-clear button-positive'
                                             }).then(function(res) {
                                                 if(res) {
                                                     utils.goState('forgot');
                                                 }else{
                                                     that.loginNow(phone, loginCb, newbie);
                                                 }
                                             });
                                         }else{
                                             utils.tip('登录密码错误，请重试！');
                                             that.loginNow(phone, loginCb, newbie);
                                         }
                                    }else{
                                        utils.setProfile(data.profile);
                                        utils.set('auth', data.auth);
                                        utils.remove('wx_auth');
                                        utils.alert(okStr);
                                        loginCb && loginCb();
                                    }
                                }else{
                                    that.loginNow(phone, loginCb, newbie);
                                }
                            }).error(function(){
                                that.loginNow(phone, loginCb, newbie);
                            });
                        }else{
                            $rootScope.loginData.password = '';
                            utils.tip('密码不合法！<br>密码由6到18位的数字或英文字符组成！');
                            that.loginNow(phone, loginCb, newbie);
                        }
                    }
                });
            },
            login: function(loginCb, phoneModal){
                $rootScope.loginData = {phone: utils.get('phone'), password: '', agree: 1, error: 0};
                var that = this;
                var tonggles = utils.getTonggles();
                $rootScope.viewAgreement = function(){
                    $rootScope.loginPopup.close();
                    utils.goUrl('page/agreement');
                };
                var title = '登录/注册';
                var template = '<input type="text" placeholder="请输入您的手机号码" ng-model="loginData.phone" style="border:1px solid #ccc;padding:0 8px;">';
                var bind = 0;       //bing 0=注册新用户 1=微信用户绑定手机号 2=微信用户手机验证登录
                var profile = utils.getProfile();
                if(profile.userid > 0 && (profile.user_type == 2 || profile.user_type == 3)){    //user_type: {"array":[["手机注册","0"],["用户名注册","1"],["微信授权","2"],["关注公众号","3"]],"mysql":""}
                    if(profile.phone && profile.phone != 'NULL'){
                        utils.set('phone',profile.phone);           //设置登录的手机号
                        if(utils.get('wx_auth')){
                            utils.set('auth',utils.get('wx_auth')); //直接登录
                            if(!tonggles.quick_login){
                                utils.goState('account', {}, true); //直接进入个人中心
                                return ;
                            }
                            loginCb && loginCb();
                            return ;
                        }
                        bind = 2;
                        title = '登录';
                        if(!tonggles.quick_login){
                            utils.goState('login', {}, true);       //直接进入登录页
                            return ;
                        }
                    }else{
                        bind = 1;
                        title = '绑定手机号';
                        if(!tonggles.quick_login){
                            utils.goState('bind', {}, true);        //直接进入绑定页
                            return ;
                        }
                    }
                }else{
                    if(!tonggles.quick_login){
                        utils.goState('login', {}, true);           //直接进入登录页
                        return ;
                    }
                }
                $rootScope.selectLoginType = function(type){
                    if(type > 0){
                        that.login(loginCb, type);
                    }else{
                        utils.tip('请稍等...');
                        that.wechatAuth();
                    }
                    $rootScope.loginSelectPopup.close();
                };
                if(bind == 0 && 'undefined' == typeof phoneModal && tonggles.wx_login ){
                    $rootScope.loginSelectPopup && $rootScope.loginSelectPopup.close();
                    $rootScope.loginSelectPopup = $ionicPopup.confirm({
                        title: '登录/注册',
                        template: '<span class="row"><span class="col col-50 center"><i class="icon iconfont icon-weixin" style="font-size:36pt;color:#69af05" ng-click="selectLoginType(0)" ></i></span><span class="col col-50 center"><i class="icon iconfont icon-iostelephone" style="font-size:36pt;color:#387ef5" ng-click="selectLoginType(1)" ></i></span></span>',
                        scope: $rootScope,
                        buttons: [{
                            text: '取消',
                            type: 'button-clear button-positive split'
                        }]
                    });
                    return ;
                }
                if(bind != 2){
                    template += '<div style="width:100%;text-align:left;padding-top: 10px;"><i class="icon iconfont icon-ioscheckmark" ng-click="loginData.agree?loginData.agree=0:loginData.agree=1;" ng-class="{\'agree\':loginData.agree,\'disagree\':!loginData.agree}"></i> <a ng-click="viewAgreement();" href="javascript:;">同意《服务协议》</a><div>';
                }
                $rootScope.loginPopup && $rootScope.loginPopup.close();
                $rootScope.loginPopup = $ionicPopup.confirm({
                    title: title,
                    template: template,
                    scope: $rootScope,
                    buttons: [
                        {
                            text: '取消',
                            type: 'button-clear button-positive split',
                            onTap: function(e){
                                return 'false';
                            }
                        },
                        {
                            text: '提交',
                            type: 'button-clear button-positive',
                            onTap: function(e){
                                if(!$rootScope.loginData.agree || !$rootScope.loginData.phone){
                                    e.preventDefault();
                                }else{
                                    return $rootScope.loginData.phone;
                                }
                            }
                        }
                    ]
                });

                $rootScope.loginPopup.then(function(phone) {
                    if(phone && phone != 'false') {
                        if(/^[1][1-9][\d]{9}$/.test(phone)){
                            var conf;
                            if(bind == 1){      //微信帐户绑定手机号码
                                conf = utils.confFactory(['phoneBind'], {data: {phone: phone, userid: profile.userid }});
                            }else{
                                conf = utils.confFactory(['phoneReg'], {data: {phone: phone}});
                            }
                            req(conf).success(function(data){
                                utils.set('phone', phone);
                                if(data.code == 200){
                                    that.loginNow(phone, loginCb, data.newbie);
                                }else{
                                    that.login(loginCb);
                                }
                            }).error(function(){
                                that.login(loginCb);
                            });
                        }else{
                            utils.tip('请输入正确的手机号码！');
                            that.login(loginCb);
                        }
                    }
                });
            },
            receiveIt: function(item, memberConf){
                var that = this;
                utils.confirm('收货确认', '您是否已收到货物？', function(ok){
                    if(ok){
                        that.req('received', {params: {orderid: item.orderid}}, function(data){
                            if(data.code == 200){
                                if(data.status > 0){
                                    item.status = 4;
                                    utils.removeCache('order_detail_'+item.orderid);
                                    utils.removeCache('orders_'+item.userid+'_'+item.appid);
                                    utils.confirm('系统提示', '您已确认收货'+(memberConf.open == 1?'。<br>现在评价可立即获得'+(memberConf.score_txt||'积分'):'')+'。<br>是否对本次服务进行评价？', function(ok){
                                        if(ok){
                                            that.commentIt(item, memberConf);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            },
            commentIt: function(item, memberConf){
                var that = this;
                $rootScope.commentData = {star: 5, comment: ''};
                var template = '<div style="width:100%;text-align:left;padding-bottom: 10px;font-size:10pt;" class="comment_star">评分：';
                template += '<i class="icon iconfont icon-iosstar" ng-click="commentData.star = 1;" ng-class="{\'on\':commentData.star > 0}"></i> ';
                template += '<i class="icon iconfont icon-iosstar" ng-click="commentData.star = 2;" ng-class="{\'on\':commentData.star > 1}"></i> ';
                template += '<i class="icon iconfont icon-iosstar" ng-click="commentData.star = 3;" ng-class="{\'on\':commentData.star > 2}"></i> ';
                template += '<i class="icon iconfont icon-iosstar" ng-click="commentData.star = 4;" ng-class="{\'on\':commentData.star > 3}"></i> ';
                template += '<i class="icon iconfont icon-iosstar" ng-click="commentData.star = 5;" ng-class="{\'on\':commentData.star > 4}"></i> <br></div><textarea placeholder="请输入评价内容" ng-model="commentData.comment" style="border:1px solid #ccc;padding:5px;font-size:10pt;"></textarea>';
                $rootScope.commentPopup = $ionicPopup.confirm({
                    title: '评价',
                    template: template,
                    scope: $rootScope,
                    buttons: [
                        {
                            text: '取消',
                            type: 'button-clear button-positive split',
                            onTap: function(e){
                                return 'false';
                            }
                        },
                        {
                            text: '提交',
                            type: 'button-clear button-positive',
                            onTap: function(e){
                                if(!$rootScope.commentData.comment) {
                                    utils.tip('请输入评价内容！');
                                    e.preventDefault();
                                    return;
                                }
                                return $rootScope.commentData;
                            }
                        }
                    ]
                });

                $rootScope.commentPopup.then(function(commentData) {
                    if(commentData != 'false' && 'object' == typeof commentData){
                        commentData.orderid = item.orderid;
                        that.req('comment', {data: commentData}, function(data){
                            if(data.code == 200){
                                if(data.status > 0){
                                    item.status = 5;
                                    utils.removeCache('order_detail_'+item.orderid);
                                    utils.removeCache('orders_'+item.userid+'_'+item.appid);
                                    var str = '评价成功！';
                                    if(data.score > 0 && memberConf.open == 1){
                                        str += '<br>获得：'+data.score+(memberConf.score_txt||'积分');
                                    }
                                    utils.alert(str);
                                }
                            }
                        });
                    }
                });
            },
            ifGoState: function(state, cb){
                if(utils.get('auth')){
                    utils.goState(state);
                    return ;
                }
                this.login(cb);
            },
            ifGoUrl: function(url, cb){
                if(utils.get('auth')){
                    utils.goUrl(url);
                    return ;
                }
                this.login(cb);
            },
            ifCallback: function(fn){
                if(utils.get('auth')){
                    fn();
                    return ;
                }
                this.login();
            },
            getScore: function(type, sub_shopid, lat, lng, data, cb){
                var that = this;
                this.ifCallback(function(){
                    var params = {type: type};
                    if(sub_shopid){
                        params.sub_shopid = sub_shopid;
                    }
                    if(lat){
                        params.lat = lat;
                    }
                    if(lng){
                        params.lng = lng;
                    }
                    if(data){
                        params.data = data;
                    }
                    var conf = utils.confFactory(['scoreAdd'], {params: params});
                    req(conf).success(function(data){
                        if(data.code == 200){
                            if(data.state == 1){
                                //that.refreshProfile();
                                that.reqProfile(null, true);
                                //utils.goBack();
                            }
                        }
                        cb && cb(data);
                    });
                });
            },
            initWxJsApiNow : function(conf){
                if( 'undefined' == typeof wx){
                    return ;
                }
                wx.config({
                    debug: false,
                    appId: conf.appId,
                    timestamp: conf.timestamp,
                    nonceStr: conf.nonceStr,
                    signature: conf.signature,
                    jsApiList: [
                        // 所有要调用的 API 都要加到这个列表中
                        'checkJsApi',
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage',
                        'onMenuShareQQ',
                        'onMenuShareWeibo',
                        'onMenuShareQZone',
                        'hideMenuItems',
                        'showMenuItems',
                        'hideAllNonBaseMenuItem',
                        'showAllNonBaseMenuItem',
                        'translateVoice',
                        'startRecord',
                        'stopRecord',
                        'onVoiceRecordEnd',
                        'playVoice',
                        'onVoicePlayEnd',
                        'pauseVoice',
                        'stopVoice',
                        'uploadVoice',
                        'downloadVoice',
                        'chooseImage',
                        'previewImage',
                        'uploadImage',
                        'downloadImage',
                        'getNetworkType',
                        'openLocation',
                        'getLocation',
                        'hideOptionMenu',
                        'showOptionMenu',
                        'closeWindow',
                        'scanQRCode',
                        'chooseWXPay',
                        'openProductSpecificView',
                        'addCard',
                        'chooseCard',
                        'openCard'
                    ]
                });
                return true;
            },
            initWxJsApi : function(cb){
                var cur_url = window.location.href.replace(window.location.hash,'');
                var that = this;
                this.cacheReq('wxJsApi', {params: {cur_url: cur_url}}, 'wxJsApi_'+utils.getAppid(), function(data){
                    if(data.code == 200){
                        if(that.initWxJsApiNow(data)){
                            cb && cb();
                        }
                    }
                });
            },
            getShareDt : function(title, desc, link, imgUrl){
                var appName = utils.getAppName();
                if('undefined' != typeof title && title !='NULL' && title){
                    title = utils.atrim(utils.delTag(title));
                }else{
                    title = window.document.title.replace(appName,'');
                    if(title){
                        title = appName+'-'+title;
                    }else{
                        title = appName;
                    }
                }
                if('undefined' != typeof desc && desc !='NULL' && desc){
                    desc = utils.atrim(utils.delTag(desc));
                }else{
                    desc = appName+' '+utils.getDomain();
                    var nickname = utils.getProfile('nickname');
                    if(nickname){
                        desc ='我是【'+nickname+'】，为您推荐：'+desc;
                    }
                }
                if('undefined' == typeof link || !link || link =='NULL'){
                    var uri = window.location.hash;
                    if(uri){
                        uri = uri.replace(/inviter_uid=(\d+)/i,'');
                        if(uri.indexOf('?') >= 0){
                            uri +='&';
                        }else{
                            uri +='?';
                        }
                        uri = uri.replace('?&','?');
                        uri +='inviter_uid='+(utils.getProfile('userid') || 0);
                        uri = uri.replace('#','?uri=');
                    }else{
                        uri ='?uri=/app/home?inviter_uid='+(utils.getProfile('userid') || 0);
                    }
                    link = utils.getAppServer()+'/'+uri;
                }
                if('undefined' == typeof imgUrl || !imgUrl || imgUrl =='NULL'){
                    imgUrl = utils.getAppLogo();
                }
                imgUrl = imgUrl.replace('_original','_thumb');  //分享时尝试使用缩略图
                var tmp = imgUrl.toLowerCase();
                if(tmp.indexOf('https://') < 0 && tmp.indexOf('http://') < 0){
                    var s = tmp.indexOf('upload');
                    if(s >= 0){
                        imgUrl = utils.getStaticServer()+'/'+imgUrl.substr(s);
                    }else{
                        if(utils.getModal() == 'h5'){
                            var pt = window.location.pathname;
                            if(pt){
                                pt = pt.split('/');
                                if(pt.length > 1){
                                    pt.pop();
                                    pt = pt.join('/');
                                }
                            }
                            imgUrl = utils.getStaticServer()+pt+'/'+imgUrl;
                        }else{
                            imgUrl = utils.getStaticServer()+'/'+utils.getVersion().substr(0,4)+'/'+imgUrl;
                        }
                    }
                }
                return {title:title, desc:desc, link:link, imgUrl:imgUrl};
            },
            initWxShare : function(title, desc, link, imgUrl, cb){
                $rootScope.$broadcast('clearWxShareT', 1);
                if( 'undefined' == typeof wx){
                    return ;
                }
                var shareDt = this.getShareDt(title, desc, link, imgUrl);
                this.initWxJsApi(function(){
                    var getDt = function(act){
                        return {
                            title: shareDt.title,
                            desc: shareDt.desc,
                            link: shareDt.link,
                            imgUrl: shareDt.imgUrl,
                            trigger: function (res) {
                                cb && cb('trigger');
                                footprint({'func': 'shareIt', 'act':act+':tap'});
                            },
                            complete: function (res) {
                                cb && cb('complete');
                                footprint({'func': 'shareIt', 'act':act+':complete', 'msg': res});
                            },
                            success: function (res) {
                                cb && cb('success');
                                footprint({'func': 'shareIt', 'act':act+':success'});
                            },
                            cancel: function (res) {
                                cb && cb('cancel');
                                footprint({'func': 'shareIt', 'act':act+':cancel'});
                            },
                            fail: function (res) {
                                cb && cb('fail');
                                footprint({'func': 'shareIt', 'act':act+':fail', 'msg': res});
                            }
                        };
                    };
                    var dt;

                    //监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
                    dt = getDt('onMenuShareTimeline');
                    delete dt.complete;
                    wx.onMenuShareAppMessage(dt);

                    //监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
                    dt = getDt('onMenuShareTimeline');
                    delete dt.desc;
                    delete dt.complete;
                    wx.onMenuShareTimeline(dt);

                    //监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
                    wx.onMenuShareQQ(getDt('onMenuShareQQ'));

                    //监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
                    wx.onMenuShareWeibo(getDt('onMenuShareWeibo'));

                    //监听“分享到QZone”按钮点击、自定义分享内容及分享接口
                    wx.onMenuShareQZone(getDt('onMenuShareQZone'));

                });
            },
            //todo: 未使用
            wxChooseImage : function(cb, sizeType, sourceType){
                if( 'undefined' == typeof wx){
                    return ;
                }
                if('undefined' == typeof sizeType){
                    sizeType = ['compressed'];
                }
                if('undefined' == typeof sourceType){
                    sourceType = ['album', 'camera'];
                }
                this.initWxJsApi(function(){
                    wx.ready(function () {
                        wx.checkJsApi({
                            jsApiList: [
                                'chooseImage'
                            ],
                            success: function (res) {
                                if(res.checkResult.chooseImage){
                                    wx.chooseImage({
                                        count:1,
                                        sizeType: sizeType,           //可以指定是原图还是压缩图，默认二者都有
                                        sourceType: sourceType,    //可以指定来源是相册还是相机，默认二者都有
                                        success: function (res) {
                                            cb && cb(res);
                                        }
                                    });
                                }
                            }
                        });
                    });
                });
            },
            //todo: 未使用
            wxUploadImage : function(localId, cb){
                if( 'undefined' == typeof wx){
                    return ;
                }
                if('undefined' == typeof sizeType){
                    sizeType = ['compressed'];
                }
                if('undefined' == typeof sourceType){
                    sourceType = ['album', 'camera'];
                }
                this.initWxJsApi(function(){
                    wx.ready(function () {
                        wx.checkJsApi({
                            jsApiList: [
                                'uploadImage'
                            ],
                            success: function (res) {
                                if(res.checkResult.uploadImage){
                                    wx.uploadImage({
                                        localId:localId,
                                        success: function (res) {
                                            cb && cb('success', res);
                                        },
                                        fail: function (res) {
                                            cb && cb('fail', res);
                                        }
                                    });
                                }
                            }
                        });
                    });
                });
            },
            refreshProfile: function(cb){
                var that = this;
                if(that.refreshProfileLock){
                    return ;
                }
                that.refreshProfileLock = true;
                that.req('getProfile', {}, function(data){
                    if(data.code == 200){
                        delete data.code;
                        utils.setProfile(data);
                    }
                    that.refreshProfileLock = false;
                    cb && cb(data);
                });
            },
            reqProfile: function(cb, refresh){
                var that = this;
                if(that.cacheReqProfileLock){
                    return ;
                }
                var userid = utils.getProfile('userid');
                if(!userid){
                    return ;
                }
                if(refresh){
                    utils.removeCache('profile_'+userid);
                }
                that.cacheReqProfileLock = true;
                that.cacheReq('getProfile', {}, 'profile_'+userid, function(data){
                    if(data.code == 200){
                        delete data.code;
                        utils.setProfile(data);
                    }
                    that.cacheReqProfileLock = false;
                    cb && cb(data);
                });
            },
            wechatLogin: function(code, cb, hybrid){
                var that = this;
                this.req('wxLogin', {params: {code: code, mobile: hybrid}}, function(data){
                    var success = 0;
                    if(data.code == 200 && data.wx_auth){      //微信还需要绑定手机号
                        utils.set('wx_auth', data.wx_auth);
                        if(data.auth){
                            utils.set('auth', data.auth);      //绑定过手机号，自动登录
                        }
                        that.refreshProfile();
                        success = 1;
                    }
                    cb && cb(success, data);
                });
            },
            wechatAuth: function(cb){
                var that = this;
                var tonggles = utils.getTonggles();
                if(tonggles.env == 'wechat' && tonggles.wx_login){
                    window.location.href = utils.getProtocol()+'//'+utils.getDomain()+'/?uri=app'+$state.current.url;
                }else if(tonggles.env == 'app' && tonggles.wx_login){
                    Wechat.isInstalled(function (installed) {
                        if(installed){
                            Wechat.auth("snsapi_userinfo", function (response) {
                                that.wechatLogin(response.code,function(success, data){
                                    if(success){
                                        utils.alert('微信授权成功！');
                                    }else{
                                        utils.alert('微信授权失败！');
                                    }
                                    cb && cb(success, data);
                                }, true);       //微信授权后自动登录
                            }, function (reason) {});
                        }else{
                            utils.tip('无法进行微信授权！');
                        }
                    });
                }else{
                    utils.tip('无法进行微信授权！');
                }
            },
            wechatBindNow: function(code, cb, hybrid){
                var that = this;
                this.req('wxBind', {params: {code: code, mobile: hybrid}}, function(data){
                    var success = 0;
                    if(data.code == 200 && data.nickname){
                        that.refreshProfile();
                        success = 1;
                    }
                    cb && cb(success, data);
                });
            },
            wechatBind: function(){
                var that = this;
                var tonggles = utils.getTonggles();
                if(tonggles.env == 'wechat' && tonggles.wx_login){
                    window.location.href = utils.getProtocol()+'//'+utils.getDomain()+'/?bind=wx&uri=app'+$state.current.url;
                }else if(tonggles.env == 'app' && tonggles.wx_login){
                    Wechat.isInstalled(function (installed) {
                        if(installed){
                            Wechat.auth("snsapi_userinfo", function (response) {
                                that.wechatBindNow(response.code,function(success, data){}, true);
                            }, function (reason) {});
                        }else{
                            utils.tip('无法进行微信绑定！');
                        }
                    });
                }else{
                    utils.tip('无法进行微信绑定！');
                }
            },
            shareIt: function($scope, title, desc, link, imgUrl){
                footprint({'func': 'shareIt'});       //记录脚印
                var tonggles = utils.getTonggles();
                if(tonggles.env == 'wechat' && tonggles.wx_share){
                    $ionicBackdrop.retain();
                    $ionicModal.fromTemplateUrl('tpl/wx_tip.html', {
                        scope: $scope,
                        animation: 'ease-in-out'
                    }).then(function(modal) {
                        $scope.modal = modal;
                        $scope.modal.show();
                    });
                    $scope.closeModal = function(){
                        $scope.modal && $scope.modal.hide();
                        $timeout(function(){
                            $ionicBackdrop.release();
                        }, 300);
                    };
                    this.initWxShare(title, desc, link, imgUrl, function(state){
                        $scope.closeModal && $scope.closeModal();
                    });
                }else if(tonggles.env == 'app' && tonggles.wx_share){
                    var shareDt = this.getShareDt(title, desc, link, imgUrl);
                    Wechat.isInstalled(function (installed) {
                        if(installed){
                            var dt = {
                                message: {
                                    title: shareDt.title,
                                    description: shareDt.desc,
                                    thumb: shareDt.imgUrl,
                                    media:{
                                        //type: Wechat.Type.LINK,
                                        webpageUrl: shareDt.link
                                    }
                                },
                                scene: Wechat.Scene.TIMELINE
                            };
                            Wechat.share(dt, function () {
                                utils.tip("分享成功！");
                            }, function (reason) { });
                        }else{
                            utils.tip('您暂时无法分享到微信！');
                        }
                    });
                }else{
                    utils.tip('您暂时无法进行分享！');
                }
            },
            payIt: function(userid, appid, orderid, token, cb){
                var tonggles = utils.getTonggles();
                if(tonggles.env == 'wechat' && tonggles.wx_pay){           //在微信里访问时，直接跳转到微信支付
                    window.location.href = utils.getProtocol()+'//'+utils.getDomain()+'/plugins/wechat/WxPay.php?userid='+userid+'&appid='+appid+'&orderid='+orderid+'&token='+token;
                }else if(tonggles.env == 'app' && tonggles.wx_pay){
                    var that = this;
                    Wechat.isInstalled(function (installed) {
                        if(installed){
                            that.req('wxPayHybrid', {params: {userid:userid,appid:appid,orderid:orderid,token:token}}, function(data){
                                if(data.code == 200){
                                    utils.tip('请稍等...');
                                    var params = {
                                        mch_id: data.partnerid,     // merchant id
                                        prepay_id: data.prepayid,   // prepay id returned from server
                                        nonce: data.noncestr,       // nonce string returned from server
                                        timestamp: data.timestamp,  // timestamp
                                        sign: data.sign,            // signed string
                                    };
                                    Wechat.sendPaymentRequest(params, function () {
                                        alert('恭喜！支付成功！');
                                        cb && cb(true);
                                    }, function (reason) {
                                        cb && cb(false, reason);
                                    });
                                }else{
                                    cb && cb(false);
                                }
                            });
                        }else{
                            utils.alert('您暂时无法使用微信支付！');
                        }
                    });
                }else{
                    utils.alert('您暂时无法进行支付！');
                }
            }
        };
    }]);

    service.factory('myCart', ['utils', 'footprint', '$rootScope', function(utils, footprint, $rootScope){
        var keyName = 'myCart';
        return {
            get: function(itemid, appid, modelsid){
                if('undefined' == typeof modelsid){
                    modelsid = 0;
                }
                var all = utils.get(keyName);
                if(!itemid && !appid){
                    return all;
                }
                var app = all?all[appid]: null;
                if(!itemid){
                    return app?app.items: null;
                }
                for(var i in app.items){
                    if(app.items[i].goodsid == itemid && app.items[i].modelsid == modelsid){
                        return app.items[i];
                    }
                }
                return null;
            },
            set: function(item, app){
                var all = utils.get(keyName) || {};
                if(!item || !app){
                    return console.error('Missing params in set function!');
                }
                if(item.models > 0 && item.models_data && item.models_data['m_'+item.modelsid]){
                    item.models_data['m_'+item.modelsid].quantity = item.quantity;          //补齐型号数据的数量
                }
                if(this.get(null, app.appid)){
                    var appItems = all[app.appid].items;
                    for(var i in appItems){
                        if(appItems[i].goodsid == item.goodsid && appItems[i].modelsid == item.modelsid){
                            if(item.quantity){
                                appItems[i].quantity = item.quantity;
                                if(item.models > 0 && item.models_data && item.models_data['m_'+item.modelsid]){    //修正数据
                                    appItems[i].models = item.models;
                                    appItems[i].models_data = item.models_data;
                                }
                            }else{
                                return this.remove(item.goodsid, app.appid, item.modelsid);
                            }
                            return utils.set(keyName, all);
                        }
                    }
                    appItems.push(item);
                    return utils.set(keyName, all);
                }else{
                    app.items = [item];
                    all[app.appid] = app;
                    return utils.set(keyName, all);
                }
            },
            remove: function(itemid, appid, modelsid){
                if('undefined' == typeof modelsid){
                    modelsid = 0;
                }
                if(!itemid && !appid){
                    return utils.remove(keyName);
                }
                var all = utils.get(keyName),
                    app = all[appid];

                if(!itemid){
                    delete all[appid];
                    return utils.set(keyName, all);
                }
                if(app){
                    for(var i in app.items){
                        if(app.items[i].goodsid == itemid && app.items[i].modelsid == modelsid){
                            if(app.items.length == 1){
                                delete all[appid];
                            }else{
                                app.items.splice(i, 1);
                            }
                            return utils.set(keyName, all);
                        }
                    }
                    return null;
                }
                return null;
            },
            //购物车清空倒计时
            getCountDown: function(){
                var appid = utils.getAppid();
                var time = parseInt(new Date().getTime()/1000);
                var flushCartTime = utils.get('flushCartTime') || 0;
                if(!flushCartTime){
                    return ;
                }
                if(time > flushCartTime){
                    utils.remove(keyName);      //清空购物车
                    utils.remove('flushCartTime');
                    $rootScope.$broadcast('syncIt', utils.getAppid());
                    return ;
                }
                var m,s;
                s = flushCartTime - time;
                m = parseInt(s/60);
                s -= m*60;
                if(s < 10){
                    s ='0'+s;
                }
                return m+':'+s;
            },
            syncData: function(data, appid){
                if(!data || !appid){
                    return [];
                }
                var appItems = this.get(null, appid);
                for(var j in data){
                    data[j].quantity = 0;
                    if(data[j].models > 0 && data[j].models_data ){
                        for(var m in data[j].models_data){
                            data[j].models_data[m].quantity = 0;
                        }
                    }
                    if(appItems){
                        for(var i in appItems){
                            if(appItems[i].goodsid == data[j].goodsid ){
                                data[j].quantity += appItems[i].quantity;
                                if(data[j].models > 0 && data[j].models_data && data[j].models_data['m_'+appItems[i].modelsid]){
                                    data[j].models_data['m_'+appItems[i].modelsid].quantity = appItems[i].quantity;
                                    if(appItems[i].models_data['m_'+appItems[i].modelsid]){
                                        appItems[i].models_data['m_'+appItems[i].modelsid].quantity = appItems[i].quantity;
                                    }
                                }
                            }
                        }
                    }
                }
                return data;
            },
            produceOrder: function(appid){
                var r = {},all = this.get();
                for(var i in all){
                    if(all[i].appid == appid){
                        var items = all[i].items;
                        for(var j in items)
                            r[items[j].goodsid+'_'+items[j].modelsid] = {goodsid: items[j].goodsid, name: items[j].name, modelsid: items[j].modelsid, quantity: items[j].quantity};
                    }
                }
                return r;
            },
            setParam: function (k, v){
                var payCache = utils.get('payCache') || {};
                var appid = utils.getAppid();
                payCache[appid] = payCache[appid] || {};
                payCache[appid][k] = v;
                utils.set('payCache', payCache);
            },
            getParam: function (k){
                var payCache = utils.get('payCache') || {};
                var appid = utils.getAppid();
                payCache[appid] = payCache[appid] || {};
                if(k){
                    return payCache[appid][k];
                }
                return payCache[appid];
            },
            removeParam: function (){
                var payCache = utils.get('payCache') || {};
                var appid = utils.getAppid();
                delete payCache[appid];
                utils.set('payCache', payCache);
            },
            calcTotal: function(appid, items){
                var r = {
                    count: 0,
                    totalPrice: 0,
                    return_money: 0
                };
                if(appid){
                    var time = parseInt(new Date().getTime()/1000);
                    var hasRt = function(item){
                        if(item.active_at <= time && time < item.resume_at && item.status == 8 && item.return_money > 0){
                            return true;
                        }
                    };
                    if('undefined' == typeof items){
                        items = this.get(null, appid);
                        items = utils.initTags(items);
                    }
                    for(var i in items){
                        r.count += items[i].quantity*1;
                        r.totalPrice += items[i].tag.prices.pr * items[i].quantity;
                        if(hasRt(items[i])){
                            r.return_money +=parseFloat(items[i].tag.prices.rt * items[i].quantity);
                        }
                    }
                }
                return r;
            },
            updateCart: function(appid, total){
                if('undefined' == typeof total){
                    total = this.calcTotal(appid);
                }
                if(!total || total.totalPrice < this.getParam('hongbaoConsume')){
                    this.setParam('hongbaoid', null);
                    this.setParam('hongbaoTypeid', null);
                    this.setParam('hongbaoConsume', null);
                    this.setParam('hongbaoMoney', null);
                }
                if(!total || total.totalPrice < this.getParam('couponConsume')){
                    this.setParam('couponid', null);
                    this.setParam('couponTypeid', null);
                    this.setParam('couponConsume', null);
                    this.setParam('couponMoney', null);
                }
            },
            syncQuantity: function(items, goodsid, quantity, modelsid){
                if('undefined' == typeof modelsid){
                    modelsid = 0;
                }
                $rootScope.$broadcast('syncIt', utils.getAppid());
                if(items){
                    for(var idx in items){
                        if(items[idx].goodsid == goodsid){
                            if(modelsid){                 //多型号
                                items[idx].models_data['m_'+modelsid].quantity = quantity;
                                items[idx].quantity = 0;
                                for(var m in items[idx].models_data){
                                    items[idx].quantity += items[idx].models_data[m].quantity;
                                }
                            }else{
                                items[idx].quantity = quantity;
                            }
                            this.updateCart(utils.getAppid());
                            return;
                        }
                    }
                }
                this.updateCart(utils.getAppid());
            },
            addQuantity: function(app, items, goods, opt){
                if('undefined' == typeof opt){
                    if(goods.models > 0 && goods.models_data){               //存在多型号
                        var appItems = this.get(null, app.appid);
                        var models_quantity = {};
                        if(appItems){
                            for(var k in appItems){
                                if(appItems[k].goodsid == goods.goodsid){
                                    models_quantity[appItems[k].modelsid] = appItems[k].quantity;
                                }
                            }
                        }
                        var opts = [];
                        for(var k in goods.models_data){
                            if(goods.models_data[k].store > 0){
                                goods.models_data[k].image = utils.getImgUrl(goods.models_data[k].image);
                                goods.models_data[k].tag = utils.getGoodsTag(goods.models_data[k]);
                                goods.models_data[k].text = '￥'+goods.models_data[k].tag.prices.pr+' <span class="dark">'+goods.models_data[k].name+'</span>';
                                if(models_quantity[goods.models_data[k].modelsid]){
                                    goods.models_data[k].text += ' <span class="assertive">x'+models_quantity[goods.models_data[k].modelsid]+'</span>';
                                }
                                opts.push(goods.models_data[k]);
                            }
                        }
                        if(opts.length == 0){
                            opt = '';
                            utils.alert('当前商品都被抢光啦！');
                            return ;
                        }else if(opts.length == 1){
                            opt = opts[0];
                        }else{
                            var that = this;
                            utils.actionSheet('请选择', opts, function(opt){
                                that.addQuantity(app, items, goods, opt);
                            });
                            return;
                        }
                    }else{                                                  //不存在多型号
                        opt = '';
                    }
                }
                var item = {};
                angular.copy(goods, item);
                if(opt){
                    item.modelsid = opt.modelsid;
                    item.name = opt.name;
                    item.image = opt.image;
                    item.original_price = opt.original_price;
                    item.price = opt.price;
                    item.return_money = opt.return_money;
                    item.quantity = opt.quantity;
                    item.tag = opt.tag;
                    item.store = opt.store;
                }else{
                    item.modelsid = 0;
                }
                if(!item.quantity){
                    item.quantity = 0;
                }
                if(item.tag.status == 10 ){                     //10=积分兑换;
                    utils.alert('当前商品无法加入购物车！');
                    return ;
                }else if((item.tag.status == 5 || item.tag.status == 6) && item.tag.ing == 0){  //5=预售、6=秒杀开始前，无法加入购物车
                    var tags = utils.getAppConf().tags || {};
                    var tag;
                    if(tags[item.tag.status]){
                        tag = tags[item.tag.status].txt;
                    }else{
                        tags[item.tag.status] = {};
                    }
                    tag = tag || '促销';
                    utils.alert(tag+'活动暂未开始！');
                    return ;
                }
                if(item.limit_num > 0 && item.quantity + 1 > item.limit_num){
                    utils.alert('当前商品限购'+item.limit_num+'件！');
                    return ;
                }
                if(item.quantity + 1 > item.store){
                    if(item.store == 0){
                        utils.alert('当前商品都被抢光啦！');
                    }else{
                        utils.alert('当前商品库存不足！');
                    }
                    return ;
                }
                if(item.quantity == 99){
                    return;
                }
                item.quantity++;
                this.set(item, app);
                this.syncQuantity(items, item.goodsid, item.quantity, item.modelsid);
                this.addCartSuccess(event, item.quantity);
                footprint({'func': 'addQuantity', 'goodsid': item.goodsid, 'quantity': item.quantity, 'modelsid': item.modelsid});       //记录脚印
            },
            subQuantity: function(app, items, goods, opt, fromCart){
                if('undefined' == typeof opt){
                    if(goods.models > 0 && goods.models_data){                  //存在多型号
                        var appItems = this.get(null, app.appid);
                        var opts = [];
                        var tmpOpt = {};
                        if(appItems){
                            for(var k in appItems){
                                if(appItems[k].models_data){
                                    tmpOpt = appItems[k].models_data['m_'+appItems[k].modelsid];
                                    if(appItems[k].goodsid == goods.goodsid && tmpOpt){
                                        tmpOpt.tag = utils.getGoodsTag(tmpOpt);
                                        tmpOpt.text = '￥'+tmpOpt.tag.prices.pr+' <span class="dark">'+tmpOpt.name+'</span>';
                                        tmpOpt.text += ' <span class="assertive">x'+appItems[k].quantity+'</span>';
                                        opts.push(tmpOpt);
                                    }
                                }
                            }
                            if(opts.length == 0){
                                opt = '';
                            }else if(opts.length == 1){
                                opt = opts[0];
                            }else{
                                var that = this;
                                utils.actionSheet('请选择', opts, function(opt){
                                    that.subQuantity(app, items, goods, opt, fromCart);
                                });
                                return;
                            }
                        }else{
                            opt = '';
                        }
                    }else{                                                      //不存在多型号
                        opt = '';
                    }
                }
                var item = {};
                angular.copy(goods, item);
                if(opt){
                    item.modelsid = opt.modelsid;
                    item.name = opt.name;
                    item.image = opt.image;
                    item.original_price = opt.original_price;
                    item.price = opt.price;
                    item.return_money = opt.return_money;
                    item.quantity = opt.quantity;
                    item.tag = opt.tag;
                    item.store = opt.store;
                }else{
                    item.modelsid = 0;
                }
                if(item.quantity == 1 && fromCart){
                    var that = this;
                    utils.confirm('购物车提示', '确定移除该商品吗？', function(ok) {
                        if(ok) {
                            item.quantity--;
                            that.set(item, app);
                            that.syncQuantity(items, item.goodsid, item.quantity, item.modelsid);
                        }
                    });
                    return ;
                }
                if(item.quantity == 0 || !item.quantity){
                    return;
                }
                item.quantity--;
                this.set(item, app);
                this.syncQuantity(items, item.goodsid, item.quantity, item.modelsid);
                footprint({'func': 'subQuantity', 'goodsid': item.goodsid, 'quantity': item.quantity});       //记录脚印
            },
            ifNeedDeliveryFee: function(app){
                var total = this.calcTotal(app.appid);
                var totalPrice = total?total.totalPrice:0;
                if(app.free_send_price == 0 || (app.delivery_fee > 0 && totalPrice < app.free_send_price) ){
                    return true;
                }else{
                    return false;
                }
            },
            orderIt : function(app, goods, status, ing, opt){
                if('undefined' == typeof opt){
                    if(goods.models > 0 && goods.models_data){               //存在多型号
                        var appItems = this.get(null, app.appid);
                        var models_quantity = {};
                        if(appItems){
                            for(var k in appItems){
                                if(appItems[k].goodsid == goods.goodsid){
                                    models_quantity[appItems[k].modelsid] = appItems[k].quantity;
                                }
                            }
                        }
                        var opts = [];
                        for(var k in goods.models_data){
                            if(goods.models_data[k].store > 0){
                                goods.models_data[k].image = utils.getImgUrl(goods.models_data[k].image);
                                goods.models_data[k].tag = utils.getGoodsTag(goods.models_data[k]);
                                goods.models_data[k].text = '￥'+goods.models_data[k].tag.prices.pr+' <span class="dark">'+goods.models_data[k].name+'</span>';
                                if(models_quantity[goods.models_data[k].modelsid]){
                                    goods.models_data[k].text += ' <span class="assertive">x'+models_quantity[goods.models_data[k].modelsid]+'</span>';
                                }
                                opts.push(goods.models_data[k]);
                            }
                        }
                        if(opts.length == 0){
                            opt = '';
                            utils.alert('当前商品都被抢光啦！');
                            return ;
                        }else if(opts.length == 1){
                            opt = opts[0];
                        }else{
                            var that = this;
                            utils.actionSheet('请选择', opts, function(opt){
                                that.orderIt(app, goods, status, ing, opt);
                            });
                            return;
                        }
                    }else{                                                  //不存在多型号
                        opt = '';
                    }
                }
                if(opt){
                    goods.modelsid = opt.modelsid;
                    goods.name = opt.name;
                    goods.image = opt.image;
                    goods.original_price = opt.original_price;
                    goods.price = opt.price;
                    goods.return_money = opt.return_money;
                    goods.quantity = opt.quantity;
                    goods.tag = opt.tag;
                    goods.store = opt.store;
                }else{
                    goods.modelsid = 0;
                }
                var tags = utils.getAppConf().tags || {};
                var tag;
                if(tags[status]){
                    tag = tags[status].txt;
                }else{
                    tags[status] = {};
                }
                tag = tag || '促销';
                if(status == 5 || status == 6){
                    if(ing == 0){
                        utils.alert(''+tag+'活动暂未开始！');
                        return;
                    }
                }else if(status == 10 ){    //积分换购
                    var curScore = utils.getProfile('score') || 0;
                    if(parseInt(curScore) < parseInt(goods.original_price)){
                        var conf = utils.getAppConf();
                        var memberConf = conf.member || {};
                        utils.alert('对不起，'+(memberConf.score_txt || '积分')+'不足，无法'+(tags[status].btn || '换购')+'！');
                        return;
                    }
                }
                goods.quantity = 1;
                if(goods.quantity + 1 > goods.store){
                    if(goods.store == 0){
                        utils.alert('当前商品都被抢光啦！');
                    }else{
                        utils.alert('当前商品库存不足！');
                    }
                    return ;
                }
                footprint({'func': 'orderIt', 'goodsid': goods.goodsid, 'quantity': goods.quantity});       //记录脚印
                var payOrder = app;
                payOrder.items = [goods];
                utils.set('payOrder', payOrder);
                var totalPrice = 0;
                for(var i in payOrder.items){
                    totalPrice += payOrder.items[i].quantity * payOrder.items[i].tag.prices.pr;
                }
                this.updateCart(app.appid, {totalPrice:totalPrice});
                utils.goState('pay');
            },
            //成功加入购物车
            addCartSuccess: function(event, quantity){
                var elm,pElm,i=0,iElm,aElm,dElm;
                elm = $('.icon-shoppingcart');
                if(!$rootScope.hideTabs || elm.length == 1){
                    i = 0;
                }else{
                    pElm = elm.parent().parent();
                    for(var ii=1; ii < pElm.length; ii++){
                        if(pElm[ii]['$attr-nav-view'] == 'active' && pElm[ii].offsetWidth > 0 && pElm[ii].offsetHeight > 0){
                            i = ii;
                        }
                    }
                }
                iElm = elm[i];
                aElm = elm.parent()[i];
                dElm = elm.parent().parent()[i];

                var toX = dElm.offsetLeft + aElm.offsetLeft + parseInt(iElm.offsetWidth/2);
                if(toX == 0){
                    toX = 31;
                }
                var toY = dElm.offsetTop + aElm.offsetTop + parseInt(iElm.offsetHeight/2);
                if(toY == 0){
                    toY = window.innerHeight || document.documentElement.clientHeight;
                    toY -=30;
                }
                $("body").append("<div id='red_dot' style='top:"+event.clientY+"px;left:"+event.clientX+"px;'></div>");
                $("#red_dot").animate({top: toY,left: toX,opacity: 0}, 500);
                setTimeout(function(){
                    $("#red_dot").remove();
                    if(quantity == 1){
                        //utils.tip('成功加入购物车！');
                    }
                }, 500);
            }
        };
    }]);

    service.factory('footprint', ['utils', 'req', '$state', '$stateParams', function(utils, req, $state, $stateParams){
        var dt = [];
        return function(act, state, params){
            if('undefined' == typeof params){
                params = $stateParams;
            }
            if('undefined' == typeof state){
                state = $state.current.name;
                params = $stateParams;
            }
            var data = {
                'state' : state,
                'appid' : utils.getAppid(),
                'inviter_uid' : utils.getInviterUid(),
                'params' : params,
                'act' : act,
                'time' : new Date().getTime()
            };
            dt.push(data);
            if(dt.length >= 5){
                var conf = utils.confFactory(['addFootprint'], {data: {reqid : utils.getReqid(), userid: utils.get('profile', 'userid'), data: JSON.stringify(dt)}});
                dt = [];
                conf.silentReq = true;
                req(conf).success(function(data){}).error(function(){});
            }
        }
    }]);

    service.factory('utils', ['localStorageService', 'config', 'confFactory', '$location', '$state', '$ionicLoading', '$ionicPopup', '$ionicActionSheet', '$ionicHistory', '$ionicScrollDelegate', '$timeout', '$ionicSideMenuDelegate', '$rootScope'
        , function(localStorageService, config, confFactory, $location, $state, $ionicLoading, $ionicPopup, $ionicActionSheet, $ionicHistory, $ionicScrollDelegate, $timeout, $ionicSideMenuDelegate, $rootScope){
        return {
            config : config,
            confFactory : confFactory,
            call: function(number, success, failure) {
                if(window.plugins && window.plugins.CallNumber){
                    window.plugins.CallNumber.callNumber(success, failure, number);
                }
            },
            size: function(){
               var width = window.innerWidth || document.documentElement.clientWidth;
               var height = window.innerHeight || document.documentElement.clientHeight;
               return {width: width, height: height};
            },
            broadcastScroll : function ($scope, opt){
                if('refresh' == opt){
                    $scope.$broadcast('scroll.refreshComplete');
                }else if('infinite' == opt){
                    $scope.$broadcast('scroll.infiniteScrollComplete');
                }
            },
            scrollTo : function (id, left, top){
                if(id){
                    $timeout(function(){
                        $ionicScrollDelegate.$getByHandle(id).scrollTo(left, top, false);
                    }, 0);
                }
            },
            //去掉html标签
            delTag : function (str){
               return str.replace(/<[^>]+>/g,"");
            },
            //去掉重复空格
            atrim : function (str){
               return str.replace(/\s+/g," ");
            },
            isEmpty : function(o){
                for(var k in o){
                    return false;
                }
                return true;
            },
            isWeiXin : function (){
                var ua = window.navigator.userAgent.toLowerCase();
                if(ua.match(/MicroMessenger/i) == 'micromessenger'){
                    return true;
                }else{
                    return false;
                }
            },
            alert: function (txt){
               $ionicPopup.alert({
                   title: '系统提示',
                   cssClass: 'myPopup',
                   template: txt,
                   okText: '确定',
                   okType: 'button-clear button-positive'
               });
            },
            confirm: function(til, txt, fn){
                $ionicPopup.confirm({
                    title: til || '请确认',
                    cssClass: 'myPopup',
                    template: txt || '',
                    buttons: [
                        {
                            text: '取消',
                            type: 'button-clear button-positive split',
                            onTap: function(){return false;}
                        },
                        {
                            text: '确定',
                            type: 'button-clear button-positive',
                            onTap: function(){return true;}
                        }
                    ]
               }).then(fn);
            },
            /*
            opts : [{text:'xxx'}]
            */
            actionSheet: function(til, opts, fn, cssClass){
                var buts = [];
                for(var k in opts){
                    buts.push({ text: opts[k].text });
                }
                var aSheet = $ionicActionSheet.show({
                    buttons: buts,
                    titleText: til || '请选择',
                    cancelText: '取消',
                    cssClass: cssClass || 'mini',
                    buttonClicked: function(index) {
                        fn(opts[index]);
                        aSheet();
                    }
                });
            },
            tip : function (txt, iElm){
                if(!txt)
                    return;
                $ionicLoading.show({
                    template: txt,
                    noBackdrop: true
                });

                $timeout(function(){
                    $ionicLoading.hide();
                }, txt.length*100 < 2000?2000: txt.length*100);
            },
            showLoading : function(txt, delay){
                $ionicLoading.show({
                    template: txt || '加载中...',
                    noBackdrop: true,
                    delay: delay || 1000,
                    duration: 10000
                });
            },
            hideLoading : function(){
                $ionicLoading.hide();
            },
            loginConfirm: function (){
                this.confirm('登录提示','您还没有登录，是否现在去登录？',function(ok) {
                    if(ok) {
                        $state.transitionTo('app.login');
                    }
               });
            },
            stateName : function(){
                return $state.current.name;
            },
            toState : function(state, param, disableAnimate){
                if(state){
                    if('object' != typeof param){
                        param = {};
                    }
                    if(disableAnimate){
                        $ionicHistory.nextViewOptions({
                            disableAnimate: true,
                            disableBack: false,
                            historyRoot: false
                        });
                    }
                    $state.transitionTo(state, param);
                }
            },
            goState : function(state, param, disableAnimate){
                if(state){
                    if('object' != typeof param){
                        param = {};
                    }
                    if(disableAnimate){
                        $ionicHistory.nextViewOptions({
                            disableAnimate: true,
                            disableBack: false,
                            historyRoot: false
                        });
                    }
                    state = 'app.'+state;
                    $state.transitionTo(state, param);
                }
            },
            toUrl : function(url){
                if(url){
                    $location.url(url);
                }
            },
            goUrl : function(url){
                if(url){
                    url = '/app/'+url;
                    $location.url(url);
                }
            },
            hasHistory : function(){
                return $ionicHistory.backTitle();
            },
            goBack : function(state, param){
                if($ionicHistory.backTitle()){
                    $ionicHistory.goBack();
                }else{
                    if(state){
                        state = 'app.'+state;
                        $state.transitionTo(state, param);
                    }else{
                        $location.url(config.homeUrl);
                    }
                }
            },
            goHome : function(){
                $location.url(config.homeUrl);
            },
            getImages : function(str){
                if( ! str){
                    return ;
                }
                var imgReg = /<img.*?(?:>|\/>)/gi;
                var srcReg = /src=[\'\"]?([^\'\"]*)[\'\"]?/i;
                var result = str.match(imgReg);
                if(! result){
                    return ;
                }
                var n = result.length;
                if( ! n){
                    return ;
                }
                var images = [];
                for (var i = 0; i < n; i++) {
                    var src = result[i].match(srcReg);
                    if(src[1]){
                        images[i] = this.getImgUrl(src[1]);
                    }
                }
                if( ! images.length){
                    return ;
                }
                return images;
            },
            getStaticServer : function(){
               return this.get('visitDt', 'static_server') || static_server;
            },
            getAppServer : function(){
               return this.get('visitDt', 'app_server') || app_server;
            },
            getApiServer : function(){
               return this.get('visitDt', 'api_server') || api_server;
            },
            getProtocol : function(){
               return this.get('visitDt', 'protocol') || protocol;
            },
            getDomain : function(){
               return this.get('visitDt', 'domain') || domain;
            },
            getModal : function(){
               return this.get('visitDt', 'modal') || modal;
            },
            getVersion : function(){
               return this.get('visitDt', 'version') || version;
            },
            getReqid : function(){
               return this.get('visitDt', 'reqid') || reqid;
            },
            getAppid : function(){
               return this.get('visitDt', 'appid') || appid;
            },
            getAppName : function(){
               return this.get('visitDt', 'app_name') || app_name;
            },
            getAppLogo : function(){
               return this.get('visitDt', 'app_logo') || app_logo;
            },
            getInviterUid : function(){
               return this.get('visitDt', 'inviter_uid') || inviter_uid;
            },
            getDefaultFace : function(){
               return this.get('visitDt', 'default_face') || default_face;
            },
            getServiceTel : function(){
               return this.get('visitDt', 'service_tel') || service_tel;
            },
            getAppConf : function(){
                return this.get('app_conf') || app_conf || {};
            },
            getTonggles: function(){
                var tonggles = this.getAppConf().tonggles || {};
                var modal = this.getModal();
                var quick_login = false;
                if(tonggles.quick_login && tonggles.quick_login == 1){
                    quick_login = true;
                }
                var ret = {
                    env: modal,                         //当前运行时环境，app wechat browser
                    wx_login: false,
                    wx_share: false,
                    wx_pay: false,
                    jpush: false,
                    quick_login: quick_login
                };
                if(modal == 'h5'){
                    if(this.isWeiXin()){
                        ret.env = 'wechat';              //在微信里访问h5版本
                        ret.wx_login = tonggles.wx_login == 1?true:false;
                        ret.wx_share = tonggles.wx_share == 1?true:false;
                        ret.wx_pay = tonggles.wx_pay == 1?true:false;
                    }else{
                        ret.env = 'browser';            //在非微信或浏览器里访问h5版本
                    }
                }else if(modal == 'android' || modal == 'ios' ){
                    ret.env = 'app';                    //在APP里访问
                    if('undefined' != typeof Wechat){   //APP支持Wechat插件
                        ret.wx_login = tonggles.mobile_login == 1?true:false;
                        ret.wx_share = tonggles.mobile_share == 1?true:false;
                        ret.wx_pay = tonggles.mobile_pay == 1?true:false;
                    }
                    if(window.plugins && window.plugins.jPushPlugin){
                        ret.jpush = tonggles.jpush == 1?true:false;
                    }
                }
                return ret;
            },
            getImgUrl: getImgUrl || function(img){
                if(!img || img == 'NULL'){
                    return '';
                }
                var tmp = img.toLowerCase();
                if(tmp.indexOf('https://') < 0 && tmp.indexOf('http://') < 0){
                    var s = tmp.indexOf('upload');
                    if(s >= 0){
                        img = this.getStaticServer()+img.substr(s);
                    }
                }
                return img;
            },
            showShare : function(){
                var tonggles = this.getTonggles();
                if((tonggles.env == 'app' || tonggles.env == 'wechat') && tonggles.wx_share ){
                    return true;
                }
            },
            showPhone : function(phone){
                return phone?phone.substr(0,3)+'****'+phone.substr(7,4):'';
            },
            remove : function(k){
                if(k){
                    localStorageService.remove(k);
                }
            },
            clearAll : function(){
                localStorageService.clearAll();
            },
            set : function(k, v){
                if(k){
                    localStorageService.set(k, v);
                }
            },
            get : function(key, k){
                if(!key){
                    return null;
                }
                var cache = localStorageService.get(key);
                if(k && cache){
                    return cache[k];
                }else{
                    return cache;
                }
            },
            removeCache: function(k, sk){
                var cache = this.get(sk || 'cache');
                if(cache && cache[k]){
                    delete cache[k];
                    this.set(sk || 'cache', cache);
                }
            },
            setCache : function(k, v, sk){
                var cache = this.get(sk || 'cache') || {};
                if(k){
                    cache[k] = v;
                    this.set(sk || 'cache', cache);
                }
            },
            getCache : function(k, sk){
                var cache = this.get(sk || 'cache') || {};
                if(k){
                    return cache[k];
                }
                return cache;
            },
            setProfile : function(data){
                this.set('profile', data || {});
                $rootScope.$broadcast('updateProfile', true);
            },
            getProfile : function(k){
                var profile = this.get('profile') || {};
                if(profile.wx_nickname && profile.wx_nickname == 'NULL'){
                    profile.wx_nickname = '';
                }
                profile.avatar = this.getImgUrl(profile.avatar);
                if(k){
                    return profile[k];
                }
                return profile;
            },
            //去掉省份补齐城市和街道
            makeAddress : function(address, province, city, district ){
                if(address){
                    if(province){
                        address = address.replace(province, '');
                        address = address.replace(province.replace('省', ''), '');
                        address = address.replace(province.replace('市', ''), '');
                    }
                    if(city){
                        address = address.replace(city, '');
                        address = address.replace(city.replace('地区', ''), '');
                        address = address.replace(city.replace('市', ''), '');
                    }
                    var district_tmp = district.replace('区', '');
                    district_tmp = district_tmp.replace('县', '');
                    if(address.indexOf(district_tmp) >= 0 || address.indexOf('区') >= 0 || address.indexOf('县') >= 0){
                        address = city+address;
                    }else{
                        address = city+district+address;
                    }
                }
                return address;
            },
            setMyPois : function(pois){
                if(pois){
                    this.set('myPois', pois);
                }
            },
            getMyPois : function(){
                return this.get('myPois');
            },
            setCurPois : function(pois){
                if(pois){
                    this.set('curPois', pois);
                }
            },
            getCurPois : function(){
                return this.get('curPois');
            },
            initTags : function(items){
                for(var i in items){
                    items[i].tag = this.getGoodsTag(items[i]);
                }
                return items;
            },
            initCountDown : function($scope, renew, key){
                var cd;
                var items = $scope.dt.items;
                if(key){
                    items = $scope.dt[key];
                }
                $scope.cds = {};
                for(var i in items){
                    if(renew){
                        items[i].tag = this.getGoodsTag(items[i]);
                    }
                    if(items[i].tag && items[i].tag.show_cd >= 1){    //CD
                        cd = this.getCountDown(items[i].active_at, items[i].resume_at);
                        if(cd){
                            $scope.cds[items[i].goodsid] = cd;
                        }
                    }
                }
            },
            countDown : function($scope, key){
                var cd;
                var renew = false;
                for(var goodsid in $scope.cds){
                    cd = this.getCountDown($scope.cds[goodsid].active_at, $scope.cds[goodsid].resume_at);
                    if(cd){
                        $scope.cds[goodsid] = cd;
                    }else{
                        delete $scope.cds[goodsid];
                        renew = true;
                    }
                }
                var that = this;
                if(renew){
                    $rootScope.$broadcast('renewPay', 1);
                    $timeout(function(){
                        that.initCountDown($scope, true, key);
                    }, 1000);
                }
            },
            getCountDown : function(active_at, resume_at){
                var time = parseInt(new Date().getTime()/1000);
                var s,d,h,m;
                if(active_at > time){
                    s = active_at - time;
                }else if(active_at < time && time < resume_at ){
                    s = resume_at - time;
                }else {
                    return;
                }
                d = parseInt(s/60/60/24);
                s -= d*60*60*24;
                h = parseInt(s/60/60);
                s -= h*60*60;
                m = parseInt(s/60);
                s -= m*60;

                var cd = '';
                if(active_at < time && time < resume_at ){
                    cd = '剩';
                }
                if(d){
                    cd = cd+d+'天';
                }
                if(h){
                    cd = cd+h+'时';
                }
                cd = cd+m+'分'+s+'秒';
                if(active_at > time){
                    cd = cd+'后开始';
                }
                return {active_at: active_at, resume_at: resume_at, cd: cd};
            },
            getGoodsTag : function(item){
                var conf = this.getAppConf() || {};
                var show_discount = conf.show_discount || 0;
                var tags = conf.tags || {};
                var time = parseInt(new Date().getTime()/1000);
                var ing;
                if(item.active_at > time){
                    ing = 0;
                }else if(item.active_at <= time && time < item.resume_at ){
                    ing = 1;
                }else if(time >= item.resume_at){
                    ing = -1;
                }
                var re = {
                    txt:'',             //标签文本
                    btn: '',            //按钮文本
                    status: item.status,//出售类型
                    prices: {},         //标价
                    ing: ing,           //促销状态 -1: 已完成 0: 未开始 1: 进行中
                    show_cd: 0,         //0：不显示CD 1: 显示CD
                    show_limit: conf.show_limit || 0,      //0：不显示限购 1: 显示限购
                    limit_num: item.limit_num   //实际限购数量
                };
                if(!tags[item.status]){
                    tags[item.status] = {};
                }
                if(item.status == 5 && ing >= 0){
                    re.txt = tags[5].txt;   //'预售';
                }else if(item.status == 6 && ing >= 0){
                    re.txt = tags[6].txt;   //'秒杀';
                }else if(item.status == 7 && ing == 1){
                    re.txt = tags[7].txt;   //'试吃';
                }else if(item.status == 8 && ing == 1 && parseFloat(item.return_money) > 0){
                    re.txt = tags[8].txt;   //'返现';
                }else if(item.status == 9 && ing == 1 && parseFloat(item.price) > 0 && parseFloat(item.original_price) > 0 && parseFloat(item.original_price) > parseFloat(item.price) ){
                    re.txt = tags[9].txt;   //'特卖';
                }else if(item.status == 10){
                    re.txt = tags[10].txt || '兑换';
                    re.btn = tags[10].btn || '换购';
                    re.limit_num = 1;       //限购1件
                }else{                      //默认原价出售
                    re.status = 0;
                }
                re.prices = {pr: parseFloat(item.original_price)};                          //默认显示原价（正常出售）
                if(re.status == 5 || re.status == 6 || re.status == 7 || re.status == 9 ){  //显示现价+原价+折扣
                    re.prices.pr = parseFloat(item.price);
                    if(show_discount){
                        re.prices.dis = parseInt(item.price*100/item.original_price)/10;
                    }
                    re.prices.prt = parseFloat(item.original_price);
                }else if(re.status == 8){                                                   //显示原价+返现
                    re.prices.rt = parseFloat(item.return_money);
                }
                if(re.status == 5 || re.status == 6 || re.status == 7 || re.status == 8 || re.status == 9 ){
                    if(re.ing >= 0){
                        re.show_cd = tags[re.status].show_cd || 0;                          //自定义是否CD
                    }
                }
                return re;
            },
            uniqueData: function(data, key){
                if(!data || !key){
                    return data;
                }
                var uniqueids = [];
                var uniqueData = [];
                for(var k in data){
                    if(uniqueids.indexOf(data[k][key]) == -1){
                        uniqueids.push(data[k][key]);
                        if(data[k].image){
                            data[k].image = this.getImgUrl(data[k].image);
                        }
                        if(data[k].image1){
                            data[k].image1 = this.getImgUrl(data[k].image1);
                        }
                        if(data[k].image2){
                            data[k].image2 = this.getImgUrl(data[k].image2);
                        }
                        if(data[k].image3){
                            data[k].image3 = this.getImgUrl(data[k].image3);
                        }
                        if(data[k].app_logo){
                            data[k].app_logo = this.getImgUrl(data[k].app_logo);
                        }
                        if(data[k].image_flow){
                            data[k].image_flow = this.getImgUrl(data[k].image_flow);
                        }
                        if('goodsid' == key){
                            data[k].tag = this.getGoodsTag(data[k]);
                        }
                        uniqueData.push(data[k]);
                    }
                }
                return uniqueData;
            },
            setCookie: function(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; " + expires+"; path=/";
            },
            getCookie: function(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i=0; i<ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1);
                    if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
                }
                return "";
            },
            clearCookie: function(){
                var keys = document.cookie.match(/[^ =;]+(?=\=)/g);
                if (keys) {
                    for(var i in keys){
                        this.setCookie(keys[i], '', -1);
                    }
                }
            },
            saveIt: function(btnId){
                $timeout(function() {       //没有timeout会报错：$apply already in progress
                    $("#"+btnId).click();
                }, 0);
            },
            checkConn: function(silent){
                if('none' == $rootScope.conn && !silent){
                    this.alert('网络错误！请检查您的网络设置！');
                }
                return $rootScope.conn;
            },
            renderSize : function (value){
                if(null == value || value == ''){
                    return "0 Bytes";
                }
                var unitArr = new Array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
                var index=0;

                var srcsize = parseFloat(value);
                var quotient = srcsize;
                while(quotient>=1024){
                    index +=1;
                    quotient=quotient/1024;
                }
                if(quotient > parseInt(quotient)){
                    return quotient.toFixed(2)+""+unitArr[index];
                }else{
                    return quotient+""+unitArr[index];
                }
            },
            toggleLeftSideMenu: function(){
                $ionicSideMenuDelegate.toggleLeft();
            }
        };
    }]);

    service.factory('uploader', ['utils', '$rootScope', '$http', function(utils, $rootScope, $http){
        var types = {
            'image': '图片',
            'video': '视频',
            'audio': '音频',
            'pem': 'pem格式证书',
            'avatar': '头像'
        };

        /*
        opts = {folder: 'folder', type: 'image', pictype: 0, minSize: 0, maxSize: 1024}
        */
        return function(filedId, cb, opts){
            opts = opts || {};
            var folder = opts.folder || '';
            var pictype = opts.pictype || 0;
            var minSize = opts.minSize || 0;
            var maxSize = opts.maxSize || 0;
            /*
            if(minSize <= 0 || minSize < utils.config.uploadMin){
                minSize = utils.config.uploadMin;
            }
            if(maxSize <= 0 || maxSize > utils.config.uploadMax){
                maxSize = utils.config.uploadMax;
            }
            */
            var type = opts.type || 'image';
            if(!types[type]){
                console.error('不支持的文件类型:'+type);
                return ;
            }
            var files = document.getElementById(filedId).files;
            if(!files.length){
                utils.tip('请选择'+types[type]+'文件！');
                return ;
            }
            var item = files[0];
            if(minSize && item.size < minSize){
                utils.tip(''+types[type]+'不能小于'+utils.renderSize(minSize)+'！');
                return ;
            }
            if(maxSize && item.size > maxSize){
                utils.tip(''+types[type]+'不能大于'+utils.renderSize(maxSize)+'！');
                return ;
            }
            if(type == 'pem'){
                if(item.name.toLowerCase().indexOf('.pem') == -1){
                    utils.tip('请选择'+types[type]+'文件！');
                    return ;
                }
            }else{
                var mtype = type;
                if(type == 'avatar'){
                    mtype = 'image';
                }
                var mime = item.type.split('/');
                if(mime[0] != mtype){
                    utils.tip('请选择'+types[type]+'文件！');
                    return ;
                }
            }

            var uploadProgress = function(evt){
                utils.showLoading('上传中...'+Math.round((evt.loaded) * 100 / evt.total)+'%', 1);
            };
            var uploadFailed = function(evt){
                utils.tip('上传失败，请重试！');
                cb && cb(filedId);
            };

            utils.showLoading('上传中...',1);
            var uploadForm = new FormData();
            uploadForm.append("filedata", item);
            var xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", uploadProgress, false);
            xhr.addEventListener("error", uploadFailed, false);
            //xhr.addEventListener("load", uploadComplete, false);
            //xhr.addEventListener("abort", uploadCanceled, false);
            var conn = $rootScope.conn;
            var platform = $rootScope.device.platform || '';
            var platform_version = $rootScope.device.version || '';
            var url = utils.getApiServer()+'/uploader/'+type+'&pictype='+pictype+'&folder='+folder+'';
            url += '&modal='+utils.getModal()+'&version='+utils.getVersion()+'&platform='+platform+'&platform_version='+platform_version+'&conn='+conn+'&reqid='+utils.getReqid()+'&inviter_uid='+utils.getInviterUid()+'&appid='+utils.getAppid()+'&userid='+(utils.getProfile('userid') || 0)+'&time=' + new Date().getTime();
            xhr.open("POST", url);
            xhr.send(uploadForm);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    var body = xhr.responseText;
                    if (xhr.status == 200) {
                        try{
                            body = JSON.parse(body);
                        }catch(e){
                            uploadFailed();
                            return ;
                        }
                        if(body.msg){
                             utils.tip(body.msg);
                             return ;
                        }
                        if(body.result && body.result['url']){
                            cb && cb(filedId, body.result['url']);
                        }
                    }else{
                        uploadFailed();
                    }
                    utils.hideLoading();
                }
            };

        };

}]);
})