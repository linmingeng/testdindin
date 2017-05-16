define(['ionic', 'config', 'main.services', 'main.devices'], function(){
    'use strict';

    var ctrl = angular.module('main.controllers', ['main.services', 'main.devices']);

    ctrl.controller('tabsCtrl', ['utils', 'myCart', '$scope', '$rootScope', '$state', function(utils, myCart, $scope, $rootScope, $state){
        var dt = {
            cartBadge: 0
        };

        $scope.dt = dt;

        var syncMyCart = function(){
             dt.cartBadge = myCart.calcTotal(utils.getAppid()).count;
             if(dt.cartBadge == 0){
                 utils.remove('myCart');            //删除购物车数据
                 utils.remove('flushCartTime');     //删除购物车清空时间
             }else if(!utils.get('flushCartTime')){
                 utils.set('flushCartTime', parseInt(new Date().getTime()/1000)+60*30);  //设置购物车清空时间
             }
        };

        syncMyCart();

        $rootScope.$on('syncIt', function(event, appid) {
            syncMyCart();     //同步购物车数据
        });

        $scope.toState = function(state, param){
            if(utils.stateName() != state){
                utils.toState(state, param);
            }
        };

    }]);

    ctrl.controller('indexCtrl', ['utils', '$scope', '$ionicSlideBoxDelegate', function(utils, $scope, $ionicSlideBoxDelegate){
        $ionicSlideBoxDelegate.slide(0);
        $scope.enter = function(){
            utils.goState('home');
        };
    }]);

    ctrl.controller('homeCtrl', ['utils', 'common', 'myCart', '$scope', '$timeout', '$ionicSlideBoxDelegate', '$interval', '$rootScope','$stateParams',
      function(utils, common, myCart, $scope, $timeout, $ionicSlideBoxDelegate, $interval, $rootScope,$stateParams){
        $scope.utils = utils;
        var dt = {
            myPois: utils.getMyPois(),
            appid: utils.getAppid(),
            appName: utils.getAppName(),
            app: utils.get('app'),
            sort_goods: [],
            conf: utils.getAppConf() || {},
            stateName: utils.stateName(),
            spinner: 1,
            items: [],
            curPage: 0,
            hasMore: false,
            groupid: $stateParams['groupid']?parseInt($stateParams['groupid']):0,
            subGroupid: 0,
            hideItems: {},
            showMoreGoods: false
        };
          //如果groupid大于0获取groups中的home配置
          //groupid获取子分类
          if(dt.groupid>0) {
              // switch (dt.groupid){
              //     case 10000:
              //         dt.conf.home = {"slide":{"show":1,"factor":0.5,"ads":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000"}]},"adv":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000"}],"btns":[[{"icon":"mall","color":"#9ACD32","txt":"购物","link":"tab/home/shop/0/-2"},{"icon":"hongbao","color":"#EE6A50","txt":"随机红包","link":"tab/activity/hongbao"},{"icon":"hongbao","color":"#EE6A50","txt":"随机红包","link":"tab/activity/hongbao"},{"icon":"hongbao","color":"#EE6A50","txt":"随机红包","link":"tab/activity/hongbao"},{"icon":"coupon-01","color":"#FFB90F","txt":"领优惠券","link":"tab/activity/coupon"}]],"goods":[{"groupid":0,"sub_groupid":-6,"title":"秒杀","tip":"快快抢，手慢无！","num":2,"style":0}],"more_goods":{"groupid":0,"sub_groupid":-11,"title":"猜你喜欢","tip":"您可能还喜欢的商品","style":0},"special":{"specialid":1,"close":1,"banner_img":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","list_img":[{"image":"/upload/1/2016-06/pic/201606240818599658.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240818599658.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240818599658.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240818599658.png","url":"tab/activity/10000"},{"image":"/upload/1/2016-06/pic/201606240818599658.png","url":"tab/activity/10000"}]}};
              //
              //         break;
              // }
              var getHomeSubGroup = function (opt) {
                  common.cacheReq('getHomeSubGroup', {
                      params: {
                          appid: dt.appid,
                          groupid: dt.groupid
                      }
                  }, 'getHomeSubGroup_' + dt.appid+dt.groupid, function (data) {
                      if (data.code == 200) {
                          dt.conf.home.btns[0] = data.home_sub_groups;
                      }
                  }, opt);
              };
              getHomeSubGroup();
          }

          $scope.dt = dt;


        dt.memberConf = dt.conf.member || {};
        dt.groupsConf = dt.conf.groups || {};
        dt.homeConf = dt.conf.home || {};
        dt.homeConf.slide = dt.homeConf.slide || {};
        // dt.homeConf.adv = dt.homeConf.adv || {};
        dt.homeConf.special = dt.homeConf.special || {};
        dt.homeConf.more_goods = dt.homeConf.more_goods || {};
        dt.homeConf.more_goods.groupid = dt.homeConf.more_goods.groupid || 0;
        dt.homeConf.more_goods.sub_groupid = dt.homeConf.more_goods.sub_groupid || 0;

        // dt.ad = [{"type":1,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]},{"type":1,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]},{"type":2,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]}];

        var it = $interval(function(){
            utils.countDown($scope);
        }, 1000);

        $scope.$on('$destroy', function() {
            $interval.cancel(it);
        });

        var getHome = function(opt){
            common.cacheReq('getAppHome', {params: {appid: dt.appid}, silentReq: true}, 'appHome_'+dt.appid, function(data){
                if(data.code == 200){
                    dt.sort_goods = data.sort_goods;
                    for(var k in dt.sort_goods){
                        for(var i in dt.sort_goods[k].items){
                            dt.hideItems[dt.sort_goods[k].items[i].goodsid] = 1;
                            dt.sort_goods[k].items[i].image = utils.getImgUrl(dt.sort_goods[k].items[i].image);
                            dt.sort_goods[k].items[i].tag = utils.getGoodsTag(dt.sort_goods[k].items[i]);
                            dt.items.push(dt.sort_goods[k].items[i]);
                            utils.initCountDown($scope, true);
                        }
                    }
                }
                dt.spinner = 0;
                utils.broadcastScroll($scope, opt);
                $timeout(function(){
                    dt.hasMore = true;
                },500);
            },opt);
        };

        getHome();
        //add yyh 2017.4.20首页分组
          var getHomeGroupsList = function(opt){
              common.cacheReq('getHomeGroupsList', {params: {aid: dt.appid,groupid:dt.groupid} }, 'getHomeGroupsList'+dt.appid+'_'+dt.groupid, function(data){
                  if(data.code == 200){
                      $scope.dt.homeGroups = data.home_groups;
                  }
              },opt);
          };

          getHomeGroupsList();

          //add yyh 2017.4.20首页广告
          var getAd = function(opt){
              dt.channel = dt.groupid > 0 ? 2 : 1;//1首页2分类页
              common.cacheReq('getAd', {params: {appid: dt.appid,groupid: dt.groupid,channel:dt.channel} }, 'ad_list_'+dt.appid+'_'+dt.groupid+'_'+dt.channel, function(data){
                  if(data.code == 200){
                      $scope.dt.ad = data.ad_list;
                  }
              },opt);
          };
          getAd();

        //add yyh 2017.4.20首页专题
          var getSpecial = function(opt){
              common.cacheReq('getSpecialList', {params: {appid: dt.appid,groupid: dt.groupid,style:1} }, 'special_'+dt.appid+'_'+dt.groupid+'_1', function(data){
                  if(data.code == 200){
                      $scope.dt.SpecialList = data.special_list;
                  }
              },opt);
          };
          getSpecial();




        var getData = function(page, opt, concat){
            page = page>0?page:1;
            dt.curPage = page;
            common.pageReq('getAppGoods', {params: {appid: dt.appid, gid: dt.homeConf.more_goods.groupid, sub_gid: dt.homeConf.more_goods.sub_groupid, page: page}}, page, 'goodsid', 'appGoods_'+dt.appid+'_'+dt.groupid+'_'+dt.subGroupid, $scope, opt, function(){
                dt.showMoreGoods = dt.items.length?true:false;
                dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                utils.initCountDown($scope);
            }, concat);
        };

        $scope.loadMore = function(){
            if(dt.curPage<2){
                getData(dt.curPage+1, 'infinite', true);
            }else{
                $scope.dt.isEmpty = false;
                $scope.dt.hasMore = false;
            }
        };

        $scope.doRefresh = function(){
            dt.items = [];
            dt.curPage = 0;
            dt.hasMore = false;
            dt.hideItems = {};
            dt.showMoreGoods = false;
            $timeout(function(){
                getHome('refresh');
            },500);
        };

        $scope.moreGoods = function(groupid, sub_groupid){
            utils.goUrl('shop/'+(groupid || 0)+'/'+(sub_groupid || 0))
        };

        $scope.orderIt = function(item){
            myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
        };

        $scope.goGoods = function(goodsid){
            utils.goState('goods', {goodsid: goodsid});
        };

        $rootScope.$on('syncIt', function(event, appid) {
            dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
        });

        $scope.addQuantity = function(item){
            myCart.addQuantity(dt.app, dt.items, item);
        };

        $scope.subQuantity = function(item){
            myCart.subQuantity(dt.app, dt.items, item);
        };

        $scope.calcTotal = function(){
            return myCart.calcTotal(dt.appid);
        };

        $scope.ifNeedDeliveryFee = function(){
            return myCart.ifNeedDeliveryFee(dt.app);
        };

        $scope.toUrl = function(url){
            url = url.replace(/&amp;/g,'&');
            url = url.replace('tab/shopping/','');
            url = url.replace('tab/mine/','');
            url = url.replace('tab/home/','');
            url = url.replace('tab/','');
            utils.goUrl(url);
        };
    }]);

    ctrl.controller('shopCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$rootScope', '$stateParams', '$interval'
      , function(utils, common, $scope, myCart, $timeout, $rootScope, $stateParams, $interval){
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                items: [],
                curPage: 0,
                hasMore: false,
                isEmpty: false,
                groups: {},
                groupid: $stateParams['groupid']?parseInt($stateParams['groupid']):0,
                groupName: null,
                subGroups: null,
                subGroupid: $stateParams['sub_groupid']?parseInt($stateParams['sub_groupid']):-1,
                subGroupName: ''
            };
            $scope.dt = dt;
            dt.tagsConf = dt.conf.tags || {};
            dt.memberConf = dt.conf.member || {};
            dt.banner = dt.conf.home.goods[0]||{};

            dt.groups = dt.app.groups;
            if(dt.groups['gid_'+dt.groupid]){
                dt.groupName = dt.groups['gid_'+dt.groupid].name;
                dt.subGroups = dt.groups['gid_'+dt.groupid].sub_groups;
            }else{
                dt.groupName = '全部商品';
                dt.subGroups = [];
            }

            dt.subGroupName = '';
            for(var k in dt.subGroups){
                if(dt.subGroupid == dt.subGroups[k].sub_groupid ){
                    dt.subGroupName = dt.subGroups[k].name;
                }
            }

            $scope.getPageTitle = function(){
                if(dt.groupName && dt.subGroupName && dt.subGroupid > 0){
                    return dt.groupName+'-'+dt.subGroupName;
                }else if(dt.groupName && dt.subGroupid == 0){
                    return dt.groupName;
                }else if(dt.subGroupid == -14){
                    return dt.tagsConf[14]?dt.tagsConf[14].txt : '秒杀（未开始）';
                }else if(dt.subGroupid == -13){
                    return dt.tagsConf[13]?dt.tagsConf[13].txt : '秒杀（已结束）';
                // }else if(dt.subGroupid == -12){
                //     return dt.tagsConf[12]?dt.tagsConf[12].txt : '店铺推荐';
                }else if(dt.subGroupid == -11){
                    return dt.tagsConf[11]?dt.tagsConf[11].txt : '猜你喜欢';
                }else if(dt.subGroupid == -10){
                    return dt.tagsConf[10]?dt.tagsConf[10].txt : '换购';
                }else if(dt.subGroupid == -9){
                    return dt.tagsConf[9]?dt.tagsConf[9].txt : '特卖';
                }else if(dt.subGroupid == -8){
                    return dt.tagsConf[8]?dt.tagsConf[8].txt : '返现';
                }else if(dt.subGroupid == -7){
                    return dt.tagsConf[7]?dt.tagsConf[7].txt : '试吃';
                }else if(dt.subGroupid == -6){
                    return dt.tagsConf[6]?dt.tagsConf[6].txt : '秒杀（抢购中）';
                }else if(dt.subGroupid == -5){
                    return dt.tagsConf[5]?dt.tagsConf[5].txt : '预售';
                }else if(dt.subGroupid == -4){
                    return '推荐商品';
                }else if(dt.subGroupid == -3){
                    return '热卖商品';
                }else if(dt.subGroupid == -2){
                    return '新品上架';
                }else if(dt.subGroupid == -1){
                    return '全部商品';
                }
            };

            var it = $interval(function(){
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });

            var getData = function(page, opt){
                page = page>0?page:1;
                dt.curPage = page;
                common.pageReq('getAppGoods', {params: {appid: dt.appid, gid: dt.groupid, sub_gid: dt.subGroupid, page: page}}, page, 'goodsid', 'appGoods_'+dt.appid+'_'+dt.groupid+'_'+dt.subGroupid, $scope, opt, function(){
                    utils.initCountDown($scope);
                    dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                });
            };

            $scope.loadMore = function(){
                getData(dt.curPage+1, 'infinite');
            };

            $scope.loadMore();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData(1, 'refresh');
                },500);
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goPay = function(){
                utils.remove('payOrder');
                utils.goState('pay');
            };
            //已结束 抢购中 未开始链接地址
            $scope.goThenUrl = function(subGroupid){
                var then_url = 'shop/0/'+subGroupid;
                utils.goUrl(then_url);
            }

            $scope.goCart = function(){
                utils.goUrl('cart?back=true');
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

    }]);

    ctrl.controller('specialCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$stateParams','$rootScope', '$interval','$location','$anchorScroll'
        , function(utils, common, $scope, myCart, $timeout, $stateParams, $rootScope, $interval,$location,$anchorScroll){

            $scope.demo = function (obj_id) {
                $location.hash(obj_id);
                $anchorScroll();
                //移动到锚点
            };
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                items: [],
                specialid: $stateParams['specialid'] || 0,
                specialData: null
            };
            dt.memberConf = dt.conf.member || {};

            $scope.dt = dt;

            var it = $interval(function(){
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });

            var getData = function(opt){
                common.cacheReq('getSpecialDetail', {params: {specialid: dt.specialid}}, 'specialDetail_'+dt.specialid, function(data){
                    if(data.code == 200){
                        delete data.code;
                        dt.specialData = data;
                        for(var k in dt.specialData.items){
                            for(var i in dt.specialData.items[k].items){
                                dt.specialData.items[k].items[i].tag = utils.getGoodsTag(dt.specialData.items[k].items[i]);
                            }
                        }
                        dt.items = utils.uniqueData(data.items || [],'goodsid');
                        dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                    }
                    $timeout(function(){
                        utils.initCountDown($scope);
                        utils.broadcastScroll($scope, opt);
                    }, 500);        //延时处理，否则会连续请求下一页
                }, opt);
            };

            getData();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData('refresh');
                },500);
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goPay = function(){
                utils.remove('payOrder');
                utils.goState('pay');
            };

            $scope.goCart = function(){
                utils.goUrl('cart?back=true');
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

    }]);

    ctrl.controller('groupsCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$stateParams','$rootScope', '$interval','$location','$anchorScroll'
        , function(utils, common, $scope, myCart, $timeout, $stateParams, $rootScope, $interval,$location,$anchorScroll){
            $scope.winHeight = {
                //高度hearder 49 fooder 43
                "height" : utils.size().height-49-43+'px'
            }
            $scope.demo = function (obj_id) {
                $location.hash(obj_id);
                $anchorScroll();


                // $(this).parents('li').addClass("on").siblings().removeClass("on");
                //移动到锚点
            };
            $scope.selectedWhich = function (row) {
                $scope.selectedRow = row;
            }


            $scope.switchCheckBox = function($event, value) {
                 // console.log(value)
                if (value) {
                    $($event.target).addClass("on");
                } else {
                    $($event.target).removeClass("on");
                }
            }
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                items: [],
                specialid: $stateParams['specialid'] || 0,
                specialData: null
            };
            dt.memberConf = dt.conf.member || {};

            $scope.dt = dt;

            var it = $interval(function(){
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });
    // 点击样式
            var getData = function(opt){
                common.cacheReq('getGroupsList', {params: {appid: dt.appid}}, 'groupsList'+ dt.appid, function(data){
                    if(data.code == 200){

                        delete data.code;
                        dt.specialData = data;
                        dt.items = utils.uniqueData(data.items || [],'goodsid');
                        dt.items = myCart.syncData(dt.items, dt.appid);

                        $scope.listgroup=data;
                        $scope.secondgroup=data.sub_groups;


                    }
                    $timeout(function(){
                        utils.initCountDown($scope);
                        utils.broadcastScroll($scope, opt);
                    }, 500);        //延时处理，否则会连续请求下一页
                }, opt);
            };

            getData();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData('refresh');
                },500);
            };


    }]);

    ctrl.controller('cartCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$location', '$rootScope', '$interval'
      , function(utils, common, $scope, myCart, $timeout, $location, $rootScope, $interval){
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                content: ''
            };

            $scope.dt = dt;

            var it = $interval(function(){
                dt.cd = myCart.getCountDown();
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });

            var getPage = function(opt){
                common.getPage(dt.appid, 'cart', function(data){
                    if(data.code == 200){
                        dt.content = data.content || '';
                    }
                    utils.broadcastScroll($scope, opt);
                }, opt);
            };

            var getData = function(opt){
                dt.items = myCart.get(null, dt.appid) || [];
                utils.initCountDown($scope, true);
                getPage(opt);
            };

            getData();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData('refresh');
                },500);
            };

            $scope.showBack  = function(){
                if(utils.stateName() != 'app.cart' || $location.search()['back']){
                    return true;
                }else {
                    return false;
                }
            };

            var getLike = function(page, opt, concat){
                page = page>0?page:1;
                dt.curPage = page;
                common.pageReq('getAppGoods', {params: {appid: dt.appid, sub_gid: -11, page: page}}, page, 'goodsid', 'appGoods_'+dt.appid+'_'+dt.groupid+'_'+dt.subGroupid, $scope, opt, function(){
                    dt.showMoreGoods = dt.items.length?true:false;
                    dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                    utils.initCountDown($scope);
                }, concat);
            };
            getLike();
            $scope.loadMore = function(){
                if(dt.curPage<2){
                    getLike(dt.curPage+1, 'infinite', true);
                }else{
                    $scope.dt.isEmpty = false;
                    $scope.dt.hasMore
                }
            };

            $scope.toUrl = function(url){
                url = url.replace(/&amp;/g,'&');
                utils.toUrl(url);
            };

            $scope.goPay = function(){
                utils.remove('payOrder');
                utils.goState('pay');
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.get(null, appid) || [];                //同步购物车数据
                utils.initCountDown($scope, true);
            });

            $scope.addQuantity = function(item){
                if(item.models > 0 && item.models_data && item.models_data['m_'+item.modelsid]){
                    myCart.addQuantity(dt.app, '', item, item.models_data['m_'+item.modelsid]);
                    return;
                }
                myCart.addQuantity(dt.app, '', item, '');
            };

            $scope.subQuantity = function(item){
                if(item.models > 0 && item.models_data && item.models_data['m_'+item.modelsid]){
                    myCart.subQuantity(dt.app, '', item, item.models_data['m_'+item.modelsid], true);
                    return;
                }
                myCart.subQuantity(dt.app, '', item, '', true);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid, dt.items);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

    }]);

    ctrl.controller('goodsCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$rootScope', '$stateParams', '$ionicScrollDelegate', '$ionicSlideBoxDelegate', '$interval'
      , function(utils, common, $scope, myCart, $timeout, $rootScope, $stateParams, $ionicScrollDelegate, $ionicSlideBoxDelegate, $interval){
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                items: [],
                goodsid: $stateParams['goodsid'] || 0,
                curItem: {},
                curInfoImages: []
            };
            $scope.dt = dt;
            dt.tagsConf = dt.conf.tags || {};
            dt.memberConf = dt.conf.member || {};

            $scope.shareIt = function(){
                common.shareIt($scope, dt.curItem.name, (dt.curItem.slogan && dt.curItem.slogan !='null')?dt.curItem.slogan:dt.curItem.name, '', dt.curItem.image);
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goPay = function(){
                utils.remove('payOrder');
                utils.goState('pay');
            };

            $scope.goCart = function(){
                utils.goUrl('cart?back=true');
            };

            var getBestItems = function(groupid, goodsid){
                common.cacheReq('getBestGoods', {params: {gid: groupid, goodsid: goodsid}}, 'bestGoods_'+groupid+'_'+goodsid, function(data){
                    if(data.code == 200){
                        for(var i in data.results){
                            dt.items.push(data.results[i]);
                        }
                        dt.items = utils.uniqueData(dt.items,'goodsid');
                        dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                        utils.initCountDown($scope, false);
                    }
                });
            };

            var getData = function(opt){
                //获取当前商品信息
                common.cacheReq('getGoodsDetail', {params: {goodsid: dt.goodsid}}, 'goodsDetail_'+dt.goodsid, function(data){
                    utils.broadcastScroll($scope, opt);
                    if(data.code == 200){
                        delete data.code;
                        dt.storeid = data.storeid;
                        data.image = utils.getImgUrl(data.image);
                        data.image1 = utils.getImgUrl(data.image1);
                        data.image2 = utils.getImgUrl(data.image2);
                        data.image3 = utils.getImgUrl(data.image3);
                        data.imgs = [];
                        if(data.image){
                            data.imgs.push(data.image);
                        }
                        if(data.image1){
                            data.imgs.push(data.image1);
                        }
                        if(data.image2){
                            data.imgs.push(data.image2);
                        }
                        if(data.image3){
                            data.imgs.push(data.image3);
                        }
                        dt.items = utils.uniqueData([data],'goodsid');
                        dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                        utils.initCountDown($scope, false);
                        dt.curItem = dt.items[0];
                        dt.curInfoImages = utils.getImages(dt.curItem.info);
                        $ionicSlideBoxDelegate.update();
                        common.initWxShare(dt.curItem.name, (dt.curItem.slogan && dt.curItem.slogan !='null')?dt.curItem.slogan:dt.curItem.name, '', dt.curItem.image);
                        getBestItems(dt.curItem.groupid, dt.goodsid);
                        common.getPage(dt.appid, 'goods_middle', function(data){
                            if(data.code == 200){
                                dt.goods_middle = data.content || '';
                            }
                        });

                        common.cacheReq('getStore', {params: {appid: dt.appid,storeid: dt.storeid} }, 'getStore_'+dt.appid+'_'+dt.storeid, function(data){
                            if(data.code == 200){
                                $scope.dt.store = data.store[0];
                            }
                        },opt);
                    }else{
                        utils.tip('商品不存在或者已下架！');
                    }
                }, opt);

            };

            getData();
            var it = $interval(function(){
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData('refresh');
                },500);
            };
            $scope.toStoreGoods = function(){
                var store_goods_url = 'app/storegoods/'+dt.store.storeid+'/0/';
                utils.toUrl(store_goods_url);
            }
            $scope.toStore = function(){
                var store_goods_url = 'app/store/'+dt.store.storeid;
                utils.toUrl(store_goods_url);
            }
            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);             //同步购物车数据
            });

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

    }]);

    ctrl.controller('newsDetailCtrl', ['utils', 'common', '$scope', '$stateParams', '$timeout',
      function(utils, common, $scope, $stateParams, $timeout){
        $scope.utils = utils;
        var dt = {
            data: null
        };
        $scope.dt = dt;

        var getData = function(opt){
            var newsid = $stateParams['newsid'];
            common.cacheReq('getNewsDetail', {params: {newsid: newsid}}, 'news_'+newsid, function(data){
                if(data.code == 200){
                    dt.data = data;
                }
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };
    }]);

    ctrl.controller('msgDetailCtrl', ['utils', 'common', '$scope', '$stateParams', '$timeout',
      function(utils, common, $scope, $stateParams, $timeout){
        $scope.utils = utils;
        var dt = {
            data: null
        };
        $scope.dt = dt;

        var getData = function(opt){
            var msgid = $stateParams['msgid'];
            common.cacheReq('getMsgDetail', {params: {msgid: msgid}}, 'msg_'+msgid, function(data){
                if(data.code == 200){
                    dt.data = data;
                }
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };
    }]);

    ctrl.controller('payCtrl', ['utils', 'common', '$scope', 'myCart', 'footprint', '$rootScope', '$ionicPopup'
      , function(utils, common, $scope, myCart, footprint, $rootScope, $ionicPopup){
        $scope.utils = utils;
        $scope.common = common;
        var dt = {
            appid: utils.getAppid(),
            app: utils.get('app'),
            conf: utils.getAppConf(),
            totalPrice: 0,          //商品总价
            curReduce: [],          //当前享受的优惠
            address: null,
            score: 0,               //积分换购商品：换购积分
            orderType: 0,           //订单类型
            deliveryAt: 0,          //预售商品发货日期
            payOrder: utils.get('payOrder'),    //单个购买
            curAddr: myCart.getParam('curAddr') || null,
            payment: myCart.getParam('payment') || 1,
            note: myCart.getParam('note') || null,
            invoice: myCart.getParam('invoice') || null,
            couponid: myCart.getParam('couponid') || null,
            couponTypeid: myCart.getParam('couponTypeid') || null,
            couponConsume: myCart.getParam('couponConsume') || null,
            couponMoney: myCart.getParam('couponMoney') || null,
            hongbaoid: myCart.getParam('hongbaoid') || null,
            hongbaoTypeid: myCart.getParam('hongbaoTypeid') || null,
            hongbaoConsume: myCart.getParam('hongbaoConsume') || null,
            hongbaoMoney: myCart.getParam('hongbaoMoney') || null,
            sexArr: utils.config.sexArr,
            newbie: utils.getProfile('newbie') || null,
            profile: utils.getProfile()
        };

        dt.memberConf = dt.conf.member || {};

        //计算商品总价
        var calcTotal = function(){
            dt.totalPrice = 0;
            for(var i in dt.app.items){
                dt.totalPrice += dt.app.items[i].quantity * dt.app.items[i].tag.prices.pr;
            }
            dt.totalPrice = parseFloat(dt.totalPrice.toFixed(2));
        };

        //初始化数据
        function initData(){
            if(dt.payOrder){
                dt.app = dt.payOrder;
                dt.app.items = utils.initTags(dt.app.items);               //更新商品标签
                dt.orderType = dt.app.items[0].tag.status;
                dt.deliveryAt = dt.app.items[0].delivery_at*1000;
                dt.score = dt.app.items[0].tag.prices.pr;
            }else{
                var t = myCart.get();
                var cartIsEmpty = true;
                for(var i in t){
                    if(dt.appid == t[i].appid){
                        dt.app = t[i];
                        dt.app.items = utils.initTags(dt.app.items);       //更新商品标签
                        cartIsEmpty = false;
                        break;
                    }
                }
                if(cartIsEmpty){
                    utils.goBack();
                    return ;
                }
            }

            //设置支付方式
            dt.payment = dt.app.payment==2?1:dt.app.payment;
            myCart.setParam('payment', dt.payment);

            calcTotal();
            //计算当前可享受的优惠
            if(dt.app.activitis && dt.app.activitis.length){
                var tmp_reduce;
                var tmp_consume = 0;
                var time = parseInt(new Date().getTime()/1000);
                for(var k in dt.app.activitis){
                    //时效性检查
                    if((dt.app.activitis[k].start_at == 0 && dt.app.activitis[k].end_at == 0) || (dt.app.activitis[k].start_at > 0 && dt.app.activitis[k].end_at > 0 && time > dt.app.activitis[k].start_at && time < dt.app.activitis[k].end_at)){
                        if(dt.app.activitis[k].type == 0){
                            dt.app.activitis[k].consume = parseFloat(dt.app.activitis[k].consume);
                            if(dt.totalPrice >= dt.app.activitis[k].consume && dt.app.activitis[k].consume >= tmp_consume){
                                tmp_consume = dt.app.activitis[k].consume;
                                tmp_reduce = dt.app.activitis[k];
                            }
                        }else{
                            dt.curReduce.push(dt.app.activitis[k]);
                        }
                        dt.app.activitis[k].close = 0;
                    }else{
                        dt.app.activitis[k].close = 1;    //过期或未开始的活动
                    }
                }
                if(tmp_reduce){
                    dt.curReduce.unshift(tmp_reduce);
                }
            }
        }

        initData();

        $rootScope.$on('renewPay', function(event, data) {      //todo: tmp
            initData();
        });

        $scope.dt = dt;

        $scope.ifGoShop = function(flag){
            if(!flag){
                return ;
            }
            utils.confirm('系统提示', '是否去凑单，享受更大的优惠？', function(ok){
                if(ok){
                    utils.goBack();
                }
            });
        };

        //获取收货地址
        var getAddress = function(){
            if(utils.get('wx_auth') || utils.get('auth') ){
                common.cacheReq('getAddress', {}, 'address_'+utils.getProfile('userid')+'_'+utils.getAppid(), function(data){
                    if(data.code == 200 && data.results){
                        dt.address = data.results;
                        if(dt.address.length){
                            if(!myCart.getParam('curAddr')){                            //未选择过收货地址，则默认使用第一个地址
                                dt.curAddr = dt.address[0];
                                myCart.setParam('curAddr', dt.curAddr);
                            }
                        }
                    }
                });
            }
        };

        getAddress();

        var refreshDt = function(){
            initData();
            getAddress();
        };

        $rootScope.$on('useAddress', function(event, data) {
            dt.curAddr = data;
            myCart.setParam('curAddr', data);
        });

        $rootScope.$on('unuseAddress', function(event, data) {
            dt.curAddr = null;
            myCart.removeParam('curAddr');
        });

        //计算可优惠金额（在线支付专享）
        $scope.getReduceMoney = function(){
            var reduceMoney = 0;
            for(var k in dt.curReduce){
                if(dt.curReduce[k].type == 0 && dt.payment == 1){        //满减优惠（在线支付专享）
                    reduceMoney += parseFloat(dt.curReduce[k].money);
                }else if(dt.curReduce[k].type == 1 && dt.payment == 1){  //新用户首次购物立减（在线支付专享）
                    if(dt.newbie == 1){                                  //新用户
                        reduceMoney += parseFloat(dt.curReduce[k].money);
                    }
                }
            }
            //优惠券的优惠额度
            if(dt.couponMoney && dt.totalPrice >= dt.couponConsume){
                reduceMoney +=parseFloat(dt.couponMoney);
            }
            //红包的优惠额度
            if(dt.hongbaoMoney && dt.totalPrice >= dt.hongbaoConsume){
                reduceMoney +=parseFloat(dt.hongbaoMoney);
            }
            return reduceMoney;
        };

        //计算可返现金额
        $scope.getReturnMoney = function(){
            var returnMoney = 0;
            var time = parseInt(new Date().getTime()/1000);
            var hasRt = function(item){
                if(item.active_at <= time && time < item.resume_at && item.status == 8 && item.return_money > 0){
                    return true;
                }
            };
            for(var k in dt.app.items){
                if(hasRt(dt.app.items[k])){
                    returnMoney +=parseFloat(dt.app.items[k].return_money*dt.app.items[k].quantity);
                }
            }
            return returnMoney;
        };

        //计算应支付的价格
        $scope.getPayMoney = function(){
            if(dt.orderType == 10){         //积分换购：支付邮费即可
                return 0;
            }
            return parseFloat(dt.totalPrice) - parseFloat($scope.getReduceMoney()); //减去优惠额度
        };

        //是否免运费
        $scope.ifNeedDeliveryFee = function(){
            if(dt.orderType == 10){         //积分换购：支付邮费即可
                return true;
            }
            if(dt.app.free_send_price == 0 || (dt.app.delivery_fee > 0 && dt.totalPrice < dt.app.free_send_price) ){
                return true;
            }else{
                return false;
            }
        };

        $scope.showReduceMoney = function(){
            var r = $scope.getReduceMoney();
            if(!$scope.ifNeedDeliveryFee()){
                return parseFloat(r)+parseFloat(dt.app.delivery_fee);
            }
            return r;
        };

        //设置支付方式
        $scope.setPayment = function(payment){
            dt.payment = payment;
            myCart.setParam('payment', payment);
        };

        $scope.goGoods = function(goodsid){
            utils.goState('goods', {goodsid: goodsid});
        };

        $scope.goCoupon = function(hongbao){
            if(hongbao){
                common.ifGoUrl('hongbao?useful=1&consume='+dt.totalPrice, refreshDt);
            }else{
                common.ifGoUrl('coupon?useful=1&consume='+dt.totalPrice, refreshDt);
            }
        };

        $scope.goAddress = function(){
            common.ifGoUrl('pay/address', refreshDt);
        };

        $scope.goAddressAdd = function(){
            common.ifGoUrl('pay/address/add', refreshDt);
        };

        var goOrderDetail = function(orderid){
            utils.goState('order-detail', {orderid: orderid});
        };

        var payIt = function (){
            footprint({'func': 'payIt'});       //记录脚印
            if(!utils.get('auth')){
                common.login(refreshDt);
                return ;
            }
            if(!dt.curAddr){
                utils.tip('请选择一个收货地址！');
                return ;
            }
            var data = {};
            data.appid = dt.appid;
            if(dt.payOrder){
                var buyData = {};
                buyData[dt.app.items[0].goodsid] = {goodsid: dt.app.items[0].goodsid, name: dt.app.items[0].name, modelsid: 0, quantity: dt.app.items[0].quantity};
                data.goods = JSON.stringify(buyData);
            }else{
                data.goods = JSON.stringify(myCart.produceOrder(dt.appid));
            }
            data.addressid = dt.curAddr.addressid;
            data.payment = dt.payment;
            data.couponid = dt.couponid;
            data.coupon_typeid = dt.couponTypeid;
            data.hongbaoid = dt.hongbaoid;
            data.hongbao_typeid = dt.hongbaoTypeid;
            data.note = dt.note;
            data.invoice = dt.invoice;
            data.newbie = dt.newbie;
            data.order_type = dt.orderType;
            data.inviter_uid = utils.getInviterUid();
            data.modal = utils.getModal();
            common.req('addOrder', {data: data}, function(data, status, header){
                utils.showLoading('请稍等...', 1);
                if(data.alert || status != 200 || !data.orderid || !data.pay_token){
                    //utils.goBack('cart');          //返回购物车
                    return ;
                }
                if(dt.couponid){
                    utils.removeCache('myCoupon_0_'+dt.appid+'_'+utils.getProfile('userid')+'_0');
                    utils.removeCache('myCoupon_0_'+dt.appid+'_'+utils.getProfile('userid')+'_1');
                }
                if(dt.hongbaoid){
                    utils.removeCache('myCoupon_1_'+dt.appid+'_'+utils.getProfile('userid')+'_0');
                    utils.removeCache('myCoupon_1_'+dt.appid+'_'+utils.getProfile('userid')+'_1');
                }
                if(dt.payOrder){
                    utils.remove('payOrder');
                }else{
                    myCart.remove(null, dt.appid);     //清空当前应用的购物车
                }
                myCart.removeParam();                   //清空当前应用的支付缓存数据
                var profile = utils.getProfile();
                profile.newbie = 0;
                utils.setProfile(profile);              //一旦下单，就不再是新手了

                $rootScope.$broadcast('syncIt', dt.appid);

                common.payIt(utils.getProfile('userid'), utils.getAppid(), data.orderid, data.pay_token, function(success, txt){
                    if(!success){
                        utils.alert('支付失败！');
                    }
                    goOrderDetail(data.orderid);        //查看自己的订单
                });

            });
        }

        $scope.payIt = payIt;

    }]);

    ctrl.controller('payNoteCtrl', ['utils', '$scope', 'myCart', function(utils, $scope, myCart){
        var dt = {
            note: myCart.getParam('note') || '',
            order_notes: utils.getAppConf().order_notes || ''
        };

        $scope.dt = dt;

        $scope.setNote = function(note){
           dt.note= note;
           myCart.setParam('note', note);
           utils.goBack();
        };

    }]);

    ctrl.controller('payInvoiceCtrl', ['utils', '$scope', 'myCart', function(utils, $scope, myCart){
        var dt = {
            invoice: myCart.getParam('invoice')
        };

        $scope.dt = dt;

        $scope.setInvoice = function(invoice){
           dt.invoice = invoice;
           myCart.setParam('invoice', invoice);
           utils.goBack();
        };

    }]);

    ctrl.controller('loginCtrl', ['utils', 'common', '$scope', '$state', '$timeout', '$rootScope', function(utils, common, $scope, $state, $timeout, $rootScope){
        $scope.utils = utils;
        var formCtrl = {
            conf: {
                keyName: 'login'
            },
            phone: {
                label: '手机号码',
                name: 'phone',
                pattern: '/^[1][1-9][\\d]{9}$/',
                require: true
            },
            password: {
                label: '密码',
                name: 'password',
                require: true
            },
            models: {
                phone: null,
                password: null
            }
        }
        $scope.formCtrl = formCtrl;

        $timeout(function(){
            formCtrl.models.phone = utils.get('phone');
        }, 1000);

        formCtrl.conf.success = function(data){
            if(data.code >= 400){
                formCtrl.models.password = null;
                return ;
            }
            if(data.code == 200){
                utils.set('phone', formCtrl.models.phone);
                utils.set('auth', data.auth);
                utils.setProfile(data.profile);
            }
            //utils.goHome();
            utils.goState('account');
            utils.alert('恭喜，登录成功！');
        };

        $scope.login = function(type){
            utils.tip('请稍候...');
            if('wechat' == type){
                common.wechatAuth(function(success, data){
                    if(success){
                        utils.goState('account', {}, true); //直接进入个人中心
                    }
                });
            }else if('weibo' == type){

            }else if('ww' == type){

            }
        };

    }]);

    ctrl.controller('registerCtrl', ['utils', 'common', '$scope', 'geoService', '$ionicModal', '$timeout', '$rootScope',
      function(utils, common, $scope, geoService, $ionicModal, $timeout, $rootScope){
        var formCtrl = {
            conf: {
                keyName: 'register'
            },
            phone: {
                label: '手机号码',
                name: 'phone',
                pattern: '/^[1][1-9][\\d]{9}$/',
                require: true
            },
            username: {
                label: '用户名',
                name: 'username',
                require: true
            },
            code: {
                label: '验证码',
                name: 'code',
                require: true
            },
            password: {
                label: '密码',
                name: 'password',
                pattern: '/^[a-zA-Z0-9]{6,31}$/i',
                warning: '密码格式错误, 长度不能小于6位数!',
                require: true
            },
            cityid: {
                label: '城市编码',
                name: 'cityid',
                require: false
            },
            address: {
                label: '地址',
                name: 'address',
                require: false
            },
            business: {
                label: '商圈',
                name: 'business',
                require: false
            },
            lng: {
                label: 'lng',
                name: 'lng',
                require: false
            },
            lat: {
                label: 'lat',
                name: 'lat',
                require: false
            },
            models: {
                phone: null,
                username: null,
                code: null,
                password: null,
                cityid: null,
                address: null,
                business: null
            },
            SMSConf: {
                timeout: 0,
                maxTime: 60,
                timer: null
            },
            step: 1,
            stepTitle: ['', '注册', '提交验证码', '设置密码']
        };

        $scope.formCtrl = formCtrl;

        var myCurPois = utils.getMyPois();
        if(myCurPois){
            formCtrl.models.cityid = myCurPois.cityid;
            formCtrl.models.address = myCurPois.address;
            formCtrl.models.business = myCurPois.business;
            formCtrl.models.lng = myCurPois.lng;
            formCtrl.models.lat = myCurPois.lat;
        }

        geoService.getLocal(function(data){
            if(data){
                var myPois = {
                    cityid: data.cityCode,
                    city: data.addressComponent.city,
                    address: data.formatted_address.replace(data.addressComponent.province,''),
                    communityid: 0,
                    community: '',
                    business: data.business,
                    lng: data.location.lng,
                    lat: data.location.lat
                };
                formCtrl.models.cityid = myPois.cityid;
                formCtrl.models.address = myPois.address;
                formCtrl.models.business = myPois.business;
                formCtrl.models.lng = myPois.lng;
                formCtrl.models.lat = myPois.lat;
                utils.setMyPois(myPois);    //保存我的位置信息
            }
        },function(error){});

        formCtrl.conf.success = function(data) {
            formCtrl.step = 1;
            formCtrl.models.phone = null;
            formCtrl.models.username = null;
            formCtrl.models.code = null;
            formCtrl.models.password = null;
            formCtrl.models.cityid = null;
            formCtrl.models.address = null;
            formCtrl.models.business = null;
            formCtrl.models.lng = null;
            formCtrl.models.lat = null;
            if(data.code == 200){
                utils.set('phone', formCtrl.models.phone);
                utils.set('auth', data.auth);
                utils.setProfile(data.profile);
                //utils.goHome();
                utils.goState('account');
                utils.alert('恭喜，注册成功！');
            }else if(data.code == 406.001){     //已注册直接登录
                utils.goState('login');
            }
        };
        dt = {
            spinner: 1
        };
        $scope.dt = dt;
        $scope.agreement = null;
        $ionicModal.fromTemplateUrl('tpl/agreement.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });

        $scope.showAgreement = function(){
            common.getPage(utils.getAppid(), 'agreement', function(data){
                if(data.code == 200){
                    $scope.agreement = data.content || '';
                    $scope.modal.show();
                }
                dt.spinner = 0;
            });
        };

        $scope.$on('$destroy', function(){
            $scope.modal && $scope.modal.remove();
        });

        $scope.close = function(){
            $scope.modal && $scope.modal.hide();
        };

        $scope.goStep = function(idx){
            if(idx == 0){
                utils.goBack('login');
                return ;
            }
            if(formCtrl.step ==1 && idx == 2){
                $scope.sendSMS();
            }else if(formCtrl.step ==2 && idx == 3){
                $scope.verifySMS();
                return;
            }
            formCtrl.step = idx;
        };

        var keepTime = function(time){
            if(time <= 0)
                return formCtrl.SMSConf.timeout = 0;

            if(!time && formCtrl.SMSConf.timer)
                $timeout.cancel(formCtrl.SMSConf.timer);

            time = time || formCtrl.SMSConf.maxTime;

            formCtrl.SMSConf.timer = $timeout(function(){
                formCtrl.SMSConf.timeout = time - 1;
                keepTime(formCtrl.SMSConf.timeout);
            }, 1000);

        };

        $scope.sendSMS = function(){
            if(formCtrl.SMSConf.timeout != 0){
                return;
            }
            keepTime();
            common.req('registerCode', {data: {phone: formCtrl.models.phone}}, function(data){
                utils.set('phone', formCtrl.models.phone);
                if(data.code >= 400){
                    $timeout.cancel(formCtrl.SMSConf.timer);
                    formCtrl.SMSConf.timeout = 0;
                    $scope.goStep(1);
                }
            });
        };

        $scope.verifySMS = function(){
            common.req('verifyCode', {data: {phone: formCtrl.models.phone, code: formCtrl.models.code}}, function(data){
                if(data.code == 200){
                    formCtrl.step = 3;
                }else if(data.code == 406.002){     //验证码错误
                    formCtrl.models.code = null;
                }
            });
        };
    }]);

    ctrl.controller('bindCtrl', ['utils', '$scope', 'geoService', function(utils, $scope, geoService){
        var formCtrl = {
            conf: {
                keyName: 'bind'
            },
            phone: {
                label: '手机号码',
                name: 'phone',
                pattern: '/^[1][1-9][\\d]{9}$/',
                keyName: 'bindCode',
                require: true
            },
            code: {
                label: '验证码',
                name: 'code',
                require: true
            },
            password: {
                label: '密码',
                name: 'password',
                pattern: '/^[a-zA-Z0-9]{6,31}$/i',
                warning: '密码格式错误, 长度不能小于6位数!',
                require: true
            },
            cityid: {
                label: '城市编码',
                name: 'cityid',
                require: false
            },
            address: {
                label: '地址',
                name: 'address',
                require: false
            },
            business: {
                label: '商圈',
                name: 'business',
                require: false
            },
            lng: {
                label: 'lng',
                name: 'lng',
                require: false
            },
            lat: {
                label: 'lat',
                name: 'lat',
                require: false
            },
            models: {
                phone: utils.get('phone'),
                code: null,
                password: null,
                cityid: null,
                address: null,
                business: null
            },
            SMSConf: {
                timeout: 0,
                maxTime: 60,
                timer: null
            },
            step: 1
        };
        $scope.formCtrl = formCtrl;

        var myCurPois = utils.getMyPois();
        if(myCurPois){
            formCtrl.models.cityid = myCurPois.cityid;
            formCtrl.models.address = myCurPois.address;
            formCtrl.models.business = myCurPois.business;
            formCtrl.models.lng = myCurPois.lng;
            formCtrl.models.lat = myCurPois.lat;
        }

        geoService.getLocal(function(data){
            if(data){
                var myPois = {
                    cityid: data.cityCode,
                    city: data.addressComponent.city,
                    address: data.formatted_address.replace(data.addressComponent.province,''),
                    communityid: 0,
                    community: '',
                    business: data.business,
                    lng: data.location.lng,
                    lat: data.location.lat
                };
                formCtrl.models.cityid = myPois.cityid;
                formCtrl.models.address = myPois.address;
                formCtrl.models.business = myPois.business;
                formCtrl.models.lng = myPois.lng;
                formCtrl.models.lat = myPois.lat;
                utils.setMyPois(myPois);    //保存我的位置信息
            }
        },function(error){});

        formCtrl.conf.success = function(data) {
            if(data.code == 200){
                formCtrl.models.cityid = null;
                formCtrl.models.address = null;
                formCtrl.models.business = null;
                formCtrl.models.lng = null;
                formCtrl.models.lat = null;
                utils.set('phone', formCtrl.models.phone);
                utils.set('auth', data.auth);
                utils.setProfile(data.profile);
                utils.goState('account');
                utils.alert('恭喜，绑定成功！');
            }
        };

        $scope.goStep = function(idx){
            if(idx == 0){
                utils.goBack('login');
                return ;
            }
            formCtrl.step = idx;
            if(idx == 2){
                $scope.$apply();
            }
        }
    }]);

    ctrl.controller('forgotCtrl', ['utils', '$scope', function(utils, $scope){
        var formCtrl = {
            conf: {
                keyName: 'forgot'
            },
            phone: {
                label: '手机号码',
                name: 'phone',
                pattern: '/^[1][1-9][\\d]{9}$/',
                keyName: 'forgotCode',
                require: true
            },
            code: {
                label: '验证码',
                name: 'code',
                require: true
            },
            password: {
                label: '密码',
                name: 'password',
                pattern: '/^[a-zA-Z0-9]{6,31}$/i',
                warning: '密码格式错误, 长度不能小于6位数!',
                require: true
            },
            models: {
                phone: utils.get('phone'),
                code: null,
                password: null
            },
            SMSConf: {
                timeout: 0,
                maxTime: 60,
                timer: null
            },
            step: 1,
            stepTitle: ['', '找回密码', '设置密码']
        };
        $scope.formCtrl = formCtrl;

        formCtrl.conf.success = function(data) {
            if(data.code == 200){
                utils.remove('auth');
                utils.remove('wx_auth');
                utils.remove('profile');
                utils.set('phone', formCtrl.models.phone);
                utils.tip(data.msg);
                formCtrl.step = 1;
                formCtrl.models.phone = null;
                formCtrl.models.code = null;
                formCtrl.models.password = null;
                utils.goState('login');
            }
        };

        $scope.goStep = function(idx){
            if(idx == 0){
                utils.goBack('login');
                return ;
            }
            formCtrl.step = idx;
            if(idx == 2){
                $scope.$apply();
            }
        }
    }]);

    ctrl.controller('ordersCtrl', ['utils', 'common', '$scope', '$timeout', '$rootScope',
      function(utils, common, $scope, $timeout, $rootScope){
        $scope.utils = utils;
        var dt = {
            items: [],
            curPage: 0,
            hasMore: true,
            isEmpty: false,
            userid: utils.getProfile('userid'),
            appid: utils.getAppid(),
            conf: utils.getAppConf() || {}
        };
        dt.memberConf = dt.conf.member || {};
        $scope.dt = dt;

        var getData = function(page, opt){
            page = page>0?page:1;
            dt.curPage = page;
            common.pageReq('getOrders', {params: {page: page}}, page, 'orderid', 'orders_'+dt.userid+'_'+dt.appid, $scope, opt);
        };

        $scope.loadMore = function(){
            getData(dt.curPage+1, 'infinite');
        };

        $scope.doRefresh = function(){
            $timeout(function(){
                getData(1, 'refresh');
            },500);
        };

        $rootScope.$on('updateOrders', function(event, data) {
            getData(1, 'refresh');
        });

        $scope.payIt = function(item){
            if(item.status == 1 && item.pay_token){
                common.payIt(utils.getProfile('userid'), item.appid, item.orderid, item.pay_token, function(success, txt){
                    if(success){
                        getData(1, 'refresh');
                    }else{
                        utils.alert('支付失败！');
                    }
                }); //开始支付
            }
        };

        $scope.receiveIt = function(item){
            common.receiveIt(item, dt.memberConf);
        };

        $scope.commentIt = function(item){
            common.commentIt(item, dt.memberConf);
        };

        $scope.viewOrder = function(orderid){
            utils.goUrl('order/'+orderid);
        };

        $scope.removeOrder = function(order, index){
            utils.confirm('删除订单确认', '您确定删除此订单吗？', function(ok) {
                if(ok) {
                    common.req('removeOrder', {params: {orderid: order.orderid}}, function(data){
                        if(data.code == 200){
                            dt.items.splice(index, 1);
                            utils.removeCache('order_detail_'+order.orderid);
                            utils.removeCache('orders_'+dt.userid+'_'+dt.appid);
                        }
                    });
                }
            });
        };
    }]);

    ctrl.controller('orderDetailCtrl', ['utils', 'common', '$scope', 'footprint', '$stateParams', '$timeout', '$rootScope',
      function(utils, common, $scope, footprint, $stateParams, $timeout, $rootScope){
        $scope.utils = utils;
        var dt = {
            conf: utils.getAppConf(),
            orderid: $stateParams['orderid'] || 0,
            sexArr: utils.config.sexArr,
            data: null
        };
        dt.memberConf = dt.conf.member || {};

        $scope.dt = dt;

        var getData = function (opt){
            common.cacheReq('getOrder', {params: {orderid: dt.orderid}}, 'order_detail_'+dt.orderid, function(data){
                if(data.code == 200 ){
                    data.total = 0;
                    data.app_logo = utils.getImgUrl(data.app_logo);
                    for(var i in data.order_goods){
                        if(data.order_type != 10){      //非积分换购
                            data.total += data.order_goods[i].quantity * data.order_goods[i].price;
                        }
                        data.order_goods[i].image = utils.getImgUrl(data.order_goods[i].image);
                    }
                    dt.data = data;
                }
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };

        $scope.payIt = function(item){
            if(item.status == 1 && item.pay_token){
                common.payIt(utils.getProfile('userid'), item.appid, item.orderid, item.pay_token, function(success, txt){
                    if(success){
                        getData('refresh');
                    }else{
                        utils.alert('支付失败！');
                    }
                }); //开始支付
            }
        };

        $scope.goGoods = function(goodsid){
            utils.goState('goods', {goodsid: goodsid});
        };

        $scope.receiveIt = function(item){
            common.receiveIt(item, dt.memberConf);
        };

        $scope.commentIt = function(item){
            common.commentIt(item, dt.memberConf);
        };

        $scope.goDeliveryDetail = function(company, postid){
            if( company && postid && company !='NULL' && postid !='NULL' && utils.config.delivery_company[company]){
                utils.goState('delivery-detial', {company:utils.config.delivery_company[company],postid: postid});
            }
        };

        $scope.removeOrder = function(orderid){
            utils.confirm('删除订单确认', '您确定删除此订单吗？', function(ok) {
                if(ok) {
                    common.req('removeOrder', {params: {orderid: orderid}}, function(data){
                        if(data.code == 200){
                            utils.removeCache('order_detail_'+orderid);
                            utils.removeCache('orders_'+dt.userid+'_'+dt.appid);
                            $rootScope.$broadcast('updateOrders', 1);
                            utils.goBack();
                        }
                    });
                }
            });
        };

    }]);

    ctrl.controller('deliveryDetailCtrl', ['utils', 'common', '$scope', 'footprint', '$stateParams', '$timeout',
      function(utils, common, $scope, footprint, $stateParams, $timeout){
        $scope.utils = utils;
        var dt = {
            company: $stateParams['company'] || '',
            postid: $stateParams['postid'] || '',
            delivery_data: null
        };

        $scope.dt = dt;

        var getData = function (opt){
            common.cacheReq('getDeliveryDetail', {params: {company: dt.company,postid: dt.postid}}, 'delivery_detail_'+dt.company+'_'+dt.postid, function(data){
                if(data.code == 200 ){
                    dt.delivery_data = data.delivery_data;
                }
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };

    }]);

    ctrl.controller('addressCtrl', ['utils', 'common', '$scope', 'myCart', '$timeout', '$rootScope',
      function(utils, common, $scope, myCart, $timeout, $rootScope){
        $scope.utils = utils;
        var dt = {
            data: [],
            addModal: null,
            isEmpty: false,
            sexArr: utils.config.sexArr,
            userid: utils.getProfile('userid'),
            appid: utils.getAppid()
        };

        $scope.dt = dt;

        var getData = function (opt){
            common.cacheReq('getAddress', {}, 'address_'+dt.userid+'_'+dt.appid, function(data){
                if(data.code == 200 ){
                    dt.data = data.results || [];
                    dt.isEmpty = !dt.data.length;
                }
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };

        $rootScope.$on('updateAddress', function(event, data) {
            getData('refresh');
        });

        $scope.goAddressAdd = function(){
            utils.goState('address-add');
        };

        $scope.ifSelected = function(addressid){
            var curAddr = myCart.getParam('curAddr') || {};
            return addressid == curAddr.addressid;
        };

        $scope.useAddr = function(addr){
            if(utils.stateName() != 'app.address'){
                $rootScope.$broadcast('useAddress', addr);
                utils.goBack();
            }
        };

        $scope.removeAddr = function(addr, index){
            utils.confirm('删除地址确认', '您确定删除此收货地址吗？', function(ok) {
                if(ok) {
                    common.req('removeAddress', {params: {addressid: addr.addressid}}, function(data){
                        if(data.code == 200){
                            var curAddr = myCart.getParam('curAddr');
                            if((curAddr && curAddr.addressid == addr.addressid) || utils.stateName() != 'app.address'){
                                $rootScope.$broadcast('unuseAddress', null);
                            }
                            $rootScope.$broadcast('updateAddress', data);
                        }
                    });
                }
            });
        }
    }]);

    ctrl.controller('addressAddCtrl', ['utils', '$scope', '$timeout', '$rootScope'
      , function(utils, $scope, $timeout, $rootScope){
        var formCtrl = {
            conf: {
                keyName: 'addAddress'
            },
            name: {
                label: '收货人',
                name: 'name',
                pattern: '/[\\u4E00-\\u9FA5]/gm',
                warning: '收货人必须为中文名!',
                require: true
            },
            sex: {
                label: '性别',
                name: 'sex',
                warning: '请选择性别!',
                require: true
            },
            phone: {
                label: '手机号',
                name: 'phone',
                pattern: '/(^(0[0-9]{2,3}\\-)?([2-9][0-9]{6,7})+(\\-[0-9]{1,4})?$)|(^((\\(\\d{3}\\))|(\\d{3}\\-))?(1[0-9]\\d{9})$)/',
                require: true
            },
            area: {
                label: '地区信息',
                name: 'area',
                require: true
            },
            address: {
                label: '收货地址',
                name: 'address',
                require: true
            },
            zip: {
                label: '邮政编码',
                name: 'zip',
                require: true
            },
            models: {
                name: null,
                sex: utils.get('profile','sex'),
                phone: utils.get('profile','phone'),
                area: null,
                address: null,
                zip: null
            }
        };

        $scope.formCtrl = formCtrl;

        var tmp_address = '';

        var newAddress = utils.get('newAddress');
        if(newAddress){
            tmp_address = newAddress['address'];
            for(var k in newAddress){
                formCtrl.models[k] = newAddress[k];     //从cache里同步数据
            }
        }

        var cityPickerCb = function(){
            formCtrl.models.area = dt.cityPicker.areaData.join(' ');
        };
        var dt = {
            cityPicker: {
                placeholder: '请选择地区信息',
                areaData: [],
                backdrop: true,
                backdropClickToClose: true,
                defaultAreaData: formCtrl.models.area?formCtrl.models.area.split(' '):'',
                buttonClicked: cityPickerCb,
                tag: ' '
            }
        };
        $scope.dt = dt;

        $scope.setSex = function (sex){
            formCtrl.models.sex = sex;
            utils.set('newAddress', formCtrl.models);
        };

        $scope.saveIt = function (btnId){
            utils.set('newAddress', formCtrl.models);
            utils.saveIt(btnId);
        };

        formCtrl.conf.success = function(data) {
            if(data.code == 200){
                formCtrl.models.name = null;
                formCtrl.models.sex = utils.get('profile','sex');
                formCtrl.models.house_number = null;
                utils.remove('newAddress');
                utils.tip('添加收货地址成功! ');
                $rootScope.$broadcast('updateAddress', data);
                utils.goBack('address');
            }
        };
    }]);

    ctrl.controller('passwordCtrl', ['utils', '$scope', '$timeout', function(utils, $scope, $timeout){
        $scope.utils = utils;
        var formCtrl = {
            conf: {
                keyName: 'setPassword'
            },
            password: {
                label: '当前密码',
                name: 'oldpassword',
                require: true
            },
            newPassword: {
                label: '新密码',
                name: 'password',
                pattern: '/^[a-zA-Z0-9]{6,31}$/i',
                warning: '密码格式错误, 长度不能小于6位数!',
                require: true
            },
            newPasswordAgain: {
                label: '重复密码',
                name: 'repassword',
                seemAs: 'password',
                require: true
            },
            models: {
                password: null,
                newPassword: null,
                newPasswordAgain: null
            }
        };

        $scope.formCtrl = formCtrl;

        formCtrl.conf.success = function(data){
            if(data.code == 200){
                utils.goState('login');
            }
        }
    }]);

    ctrl.controller('profileCtrl', ['utils', 'common', '$scope', 'footprint', 'avatarService', 'uploader', '$ionicActionSheet', '$rootScope', '$timeout'
      , function(utils, common, $scope, footprint, avatarService, uploader, $ionicActionSheet, $rootScope, $timeout){
        $scope.utils = utils;
        var profile = utils.getProfile();
        $scope.profile = profile;
        $scope.defaultFace = utils.getDefaultFace();

        var sexArr = utils.config.mySexArr;
        $scope.sexStr = sexArr[$scope.profile.sex || 0] || null;

        $rootScope.$on('updateProfile', function(event, flag) {
            if(flag){
                $scope.profile = utils.getProfile();
                $scope.sexStr = sexArr[$scope.profile.sex || 0] || null;
            }
        });

        $scope.changeAvatar = function(){
            var actionSheet = $ionicActionSheet.show({
                buttons: [
                    { text: '<i class="icon iconfont icon-camera"></i> 拍照' },
                    { text: '<i class="icon iconfont icon-image"></i> 从相册选择' }
                ],
                titleText: '修改头像',
                cancelText: '取消',
                buttonClicked: function(index) {
                    utils.tip('请稍等...');
                    avatarService.modifyAvatar(index, utils.get('auth'), function(data){
                        data = JSON.parse(data.response);
                        if(data.code == 200){
                            $scope.profile.avatar = utils.getImgUrl(data.result.url);
                            utils.setProfile($scope.profile);
                        }else{
                            utils.alert('头像上传失败！请稍后重试！');
                        }
                    }, function(){});
                    actionSheet();
                }
            });
        };

        $scope.selectAvatar = function(){
            footprint({'func': 'selectAvatar', 'userid': $scope.profile.userid});       //记录脚印
            if(utils.getModal() == 'h5'){
                $('#avatarFile').click();
                $("#avatarFile").unbind( "change" );
                $("#avatarFile").change( function() {
                    uploader('avatarFile', function(filedId, url){
                        var profile = utils.getProfile();
                        profile.avatar = utils.getImgUrl(url);
                        utils.setProfile(profile);
                        $rootScope.$broadcast('updateProfile', true);
                    }, {folder: 'avatar', type: 'avatar', pictype: 3, minSize: 1024, maxSize: 1024*1024*5});
                });
            }else {
                $scope.changeAvatar();
            }
        };

        var logoutNow = function(){
            common.req('logout', {}, function(){});
        };

        $scope.logout = function(){
            utils.confirm('退出确认', '您确定退出此账户吗？', function(ok) {
                if(ok) {
                    footprint({'func': 'logout', 'userid': $scope.profile.userid});       //记录脚印
                    utils.remove('auth');
                    utils.remove('wx_auth');
                    utils.remove('profile');
                    utils.remove('cache');  //退出帐户清空全部缓存
                    //utils.removeCache('profile_'+dt.userid);
                    utils.clearCookie();
                    $rootScope.$broadcast('updateProfile', true);
                    logoutNow();
                    utils.goState('account');
                }
            });
        };

        $scope.bindWechat = function(wx_nickname){
            if(wx_nickname){
                utils.tip('当前绑定的微信号：'+wx_nickname);
            }else{
                common.wechatBind();
            }
        };

    }]);

    ctrl.controller('nicknameCtrl', ['utils', '$scope', '$timeout', function(utils, $scope, $timeout){
        $scope.utils = utils;
        var formCtrl = {
            conf: {
                keyName: 'setNickname'
            },
            nickname: {
                label: '昵称',
                name: 'nickname',
                require: true
            },
            models: {
                nickname: null
            }
        };

        $scope.formCtrl = formCtrl;

        $scope.profile = utils.getProfile();
        formCtrl.models.nickname = $scope.profile.nickname || null;

        formCtrl.conf.success = function(data) {
            if(data.code == 200){
                $scope.profile.nickname = formCtrl.models.nickname;
                utils.setProfile($scope.profile);
                utils.goState('profile');
            }
        };

    }]);

    ctrl.controller('birthdayCtrl', ['utils', '$scope', '$timeout', function(utils, $scope, $timeout){
        $scope.utils = utils;
        var formCtrl = {
            conf: {
                keyName: 'setBirthday'
            },
            birthday: {
                label: '生日',
                name: 'birthday',
                require: true
            },
            models: {
                birthday: null
            }
        };

        $scope.formCtrl = formCtrl;

        $scope.profile = utils.getProfile();
        if(!$scope.profile.birthday || $scope.profile.birthday =='NULL'){
            $scope.profile.birthday = '1990-01-01';
        }
        formCtrl.models.birthday = new Date($scope.profile.birthday);

        formCtrl.conf.success = function(data) {
            if(data.code == 200){
                var year = formCtrl.models.birthday.getFullYear();
                var month = formCtrl.models.birthday.getMonth()+1;
                var day = formCtrl.models.birthday.getDate();
                if( month < 10){
                    month = "0"+month;
                }
                if( day < 10){
                    day = "0"+day;
                }
                $scope.profile.birthday = year+"-"+month+"-"+day;
                utils.setProfile($scope.profile);
                utils.goState('profile');
            }
        };

    }]);

    ctrl.controller('sexCtrl', ['utils', '$scope', 'common', function(utils, $scope, common){

        var dt = {
            sexArr: utils.config.mySexArr,
            sex: null
        };

        $scope.dt = dt;

        $scope.profile = utils.getProfile();
        $scope.dt.sex = $scope.profile.sex;

        $scope.setSex = function(sex){
            common.req('setSex', {params: {sex: sex}}, function(data){
                if(data.code == 200){
                    $scope.dt.sex = sex;
                    $scope.profile.sex = sex;
                    utils.setProfile($scope.profile);
                    utils.goState('profile');
                }
            });
        };
    }]);

    ctrl.controller('complainCtrl', ['utils', '$scope', '$timeout', function(utils, $scope, $timeout){
        $scope.utils = utils;
        var formCtrl = {
            conf: {
                keyName: 'setComplain'
            },
            content: {
                label: '投诉意见',
                name: 'content',
                require: true
            },
            models: {
                content: null
            }
        };

        $scope.formCtrl = formCtrl;

        formCtrl.conf.success = function(data) {
            utils.goState('account');
        };
    }]);

    ctrl.controller('accountCtrl', ['utils', 'common', '$scope', '$rootScope', '$timeout',
      function(utils, common, $scope, $rootScope, $timeout){
        $scope.utils = utils;
        $scope.common = common;
        var dt = {
            appid: utils.getAppid(),
            app: utils.get('app'),
            userLeves: {},
            memberData: {},
            profile: utils.getProfile(),
            defaultFace: utils.getDefaultFace()
        };
        $scope.dt = dt;

        var getConf = function(){
            dt.conf = utils.getAppConf();
            dt.memberConf = dt.conf.member || {};
            dt.traderConf = dt.conf.trader || {};
        };

        getConf();

        $rootScope.$on('initApp', function(event, flag) {
            getConf();
        });

        dt.score_txt = '积分';
        dt.exp_txt = '经验';
        if(dt.conf.member){
            dt.score_txt = dt.conf.member.score_txt || dt.score_txt;
            dt.exp_txt = dt.conf.member.exp_txt || dt.exp_txt;
        }
        var getMemberData = function(){
            if(utils.stateName() != 'app.member'){
                return ;
            }
            var curLevel = dt.profile.level>0?dt.profile.level:1;
            var nextLevel,data;
            var getLevel = function(lv){
                curLevel = lv;
                nextLevel = parseInt(curLevel)+1;
                data = {cur: dt.userLeves[curLevel]};
                if(dt.userLeves[nextLevel] && dt.userLeves[nextLevel].auto_up == 1){
                    data.next = dt.userLeves[nextLevel];
                    dt.needExp = parseInt(data.next.exp) - parseInt(dt.profile.exp);
                    if(dt.needExp <=0){
                        getLevel(nextLevel+1);
                    }
                }else{
                    delete data.next;
                    nextLevel = '';
                }
            };

            getLevel(curLevel);     //自动升级
            dt.curLevel = curLevel;
            dt.curColor = {'background-color': data.cur.color};
            dt.nextLevel = nextLevel;
            dt.nextColor = {'color': data.next?data.next.color:''};
            dt.memberData = data;
            var date = new Date();
            var bd = new Date(dt.profile.birthday);
            if(date.getDate() == bd.getDate() && date.getMonth() == bd.getMonth() && dt.conf.member.birthday > 0){
                dt.isBirthday = 1;
            }
        };

        var getUserLevels = function(opt){
            if(utils.stateName() != 'app.member'){
                return ;
            }
            common.cacheReq('getUserLevels', {params: {appid: dt.appid}}, 'user_levels_'+appid, function(data){
                if(data.code == 200){
                    delete data.code;
                    dt.userLeves = data;
                    getMemberData();
                }
            }, opt);
        };

        var getData = function(){
            dt.profile = utils.getProfile();
            dt.phone = utils.showPhone(dt.profile.phone);
            getUserLevels();
        };

        getData();

        var refreshData = function(){
            getConf();
            common.refreshProfile(function(data){
                dt.profile = data;
                getMemberData();
                utils.broadcastScroll($scope, 'refresh');
            });
        };

        $scope.doRefresh = function(){
            $timeout(function(){
                refreshData();
            },500);
        };

        $rootScope.$on('updateProfile', function(event, flag) {
            if(flag){
                dt.profile = utils.getProfile();
                dt.phone = utils.showPhone(dt.profile.phone);
            }
        });

    }]);

    ctrl.controller('traderCtrl', ['utils', 'common', '$scope', '$rootScope', '$timeout',
      function(utils, common, $scope, $rootScope, $timeout){
        $scope.utils = utils;
        $scope.common = common;

        var dt = {
            traderLeves: {},
            conf: utils.getAppConf()
        };
        dt.traderConf = dt.conf.trader || {};

        var initTrader = function(){
            dt.orders = 0;
            dt.money = 0;
            dt.uv = 0;
            dt.trader = {income: (dt.traderConf.send_money || 0), level: 0, exp: 0};
            dt.curLevel = '';
            dt.curColor = {};
            dt.userid = utils.getProfile('userid');
            dt.appid = utils.getAppid();
        };

        initTrader();

        $scope.dt = dt;

        var getTraderLevels = function(opt){
            common.cacheReq('getTraderLevels', {params: {appid: dt.appid}}, 'trader_levels_'+dt.appid, function(data){
                if(data.code == 200){
                    delete data.code;
                    dt.traderLevels = data;
                    if(dt.trader.level && data[dt.trader.level]){
                        dt.curLevel = data[dt.trader.level];
                        dt.curColor = {'color': dt.curLevel.color};
                    }
                }
            }, opt);
        };

        var getData = function(opt){
            if(dt.userid == 0){
                initTrader();
                return ;
            }
            common.cacheReq('getTraderInfo', {}, 'trader_info_'+dt.userid+'_'+dt.appid, function(data){
                if(data.code == 200){
                    dt.orders = data.orders;
                    dt.money = data.money;
                    dt.uv = data.uv;
                    if(data.trader){
                        dt.trader = data.trader;
                    }else{
                        initTrader();
                    }
                }else{
                    initTrader();
                }
                getTraderLevels();
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData();

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh');
            },500);
        };

        $rootScope.$on('updateProfile', function(event, flag) {
            if(flag){
                initTrader();
            }
        });

        $scope.ifGoUrl = function(uri){
            var txt = dt.traderConf.txt || '分销商';
            if(dt.curLevel){
                if(dt.trader.status == -1){
                    utils.alert('您的'+txt+'资格被冻结，有疑问请联系客服！');
                }else if(dt.trader.status == 0){
                    utils.alert('您的'+txt+'资格暂未通过审核！请联系客服！');
                }else {
                    utils.goUrl(uri);
                }
            }else{
                utils.confirm('', '是否立即申请成为“'+txt+'”？', function(ok){
                    if(ok){
                        utils.goUrl('activity/trader');
                    }
                });
            }
        };

    }]);

    ctrl.controller('pageReqCtrl', ['utils', 'common', '$scope', '$rootScope', '$timeout',
      function(utils, common, $scope, $rootScope, $timeout){
        $scope.utils = utils;
        var dt = {
            items: [],
            curPage: 0,
            hasMore: true,
            isEmpty: false,
            userid: utils.getProfile('userid'),
            appid: utils.getAppid(),
            conf: utils.getAppConf(),
            traderLevels: {},
            trader: ""
        };
        $scope.dt = dt;
        dt.traderConf = dt.conf.trader || {};

        var getTraderData = function(opt){
            common.cacheReq('getTraderInfo', {}, 'trader_info_'+dt.userid+'_'+dt.appid, function(data){
                if(data.code == 200){
                    dt.trader = data.trader;
                }
            }, opt);
        };

        var getTraderLevels = function(){
            common.cacheReq('getTraderLevels', {params: {appid: dt.appid}}, 'trader_levels_'+dt.appid, function(data){
                if(data.code == 200){
                    delete data.code;
                    dt.traderLevels = data;
                }
            });
        };

        var resid, uk, ck;
        var cur_state = utils.stateName();
        if('app.trader-report' == cur_state){
            resid = 'getReportList';
            uk = 'reportid';
            ck = 'report_list_'+dt.userid+'_'+dt.appid;
        }else if('app.trader-customer' == cur_state){
            resid = 'getCustomerList';
            uk = 'userid';
            ck = 'customer_list_'+dt.userid+'_'+dt.appid;
        }else if('app.trader-partner' == cur_state){
            resid = 'getTraderList';
            uk = 'userid';
            ck = 'trader_list_'+dt.userid+'_'+dt.appid;
            getTraderLevels();
        }else if('app.trader-income' == cur_state){
            resid = 'getIncomeList';
            uk = 'incomeid';
            ck = 'income_list_'+dt.userid+'_'+dt.appid;
            getTraderData();
        }else if('app.trader-withdraw' == cur_state){
            resid = 'getWithdrawList';
            uk = 'withdrawid';
            ck = 'withdraw_list_'+dt.userid+'_'+dt.appid;
            getTraderData();
        }else if('app.msg' == cur_state){
            resid = 'getMsg';
            uk = 'msgid';
            ck = 'msg_'+dt.appid;
            utils.removeCache('profile_'+dt.userid);    //删除profile
        }else if('app.news' == cur_state){
            resid = 'getNews';
            uk = 'newsid';
            ck = 'news_'+dt.appid;
        }else if('app.score_log' == cur_state){
            resid = 'getScoreLog';
            uk = 'score_logid';
            ck = 'score_log_'+dt.userid+'_'+dt.appid;
            dt.score_txt = '积分';
            dt.exp_txt = '经验';
            if(dt.conf.member){
                dt.score_txt = dt.conf.member.score_txt || dt.score_txt;
                dt.exp_txt = dt.conf.member.exp_txt || dt.exp_txt;
            }
        }

        var getData = function(page, opt){
            page = page>0?page:1;
            dt.curPage = page;
            common.pageReq(resid, {params: {page: page}}, page, uk, ck, $scope, opt);
            if('refresh' == opt && ('app.trader-withdraw' == cur_state || 'app.trader-income' == cur_state)){
                getTraderData(opt);
            }
        };

        $scope.loadMore = function(){
            getData(dt.curPage+1, 'infinite');
        };

        $scope.doRefresh = function(){
            $timeout(function(){
                getData(1, 'refresh');
            },500);
        };

        $scope.shareIt = function(){
            common.shareIt($scope, utils.getAppName(), '', utils.getAppServer()+'/?inviter_uid='+(utils.getProfile('userid') || 0));
        };

        $scope.withdraw = function(){
            var money = dt.trader?dt.trader.money:0;
            var min_withdraw = parseFloat(dt.traderConf.min_withdraw) || 0;
            if(parseFloat(money) < min_withdraw && min_withdraw > 0){
                utils.alert('可提现金额不足，<br>最小提现金额：'+min_withdraw+'元！');
                return ;
            }
            utils.confirm('提现确认', '当前可提现金额为：'+money+'元，<br>是否全部提现？', function(ok){
                if(ok){
                    common.req('withdrawNow', {params: {money: money}}, function(data){
                        if(data.code == 200){
                            if(data.withdrawid > 0){
                                utils.removeCache('withdraw_list_'+dt.userid+'_'+dt.appid);
                                utils.removeCache('income_list_'+dt.userid+'_'+dt.appid);
                                getTraderData('refresh');
                                getData(1, 'refresh');
                                utils.alert('您已成功申请提现，请耐心等待！');
                            }
                        }
                    });
                }
            });
        };

    }]);

    ctrl.controller('setupCtrl', ['utils', '$scope', 'common', 'footprint', '$rootScope', function(utils, $scope, common, footprint, $rootScope){
        $scope.utils = utils;
        var dt = {};
        $scope.dt = dt;
        $scope.profile = utils.getProfile();

        if($scope.profile && $scope.profile.receive){
            $scope.dt.receive = $scope.profile.receive==1?true:false;
            $rootScope.setReceive($scope.dt.receive);
        }else{
            $scope.dt.receive = $rootScope.receivePush=='true'?true:false;
        }

        $scope.setReceive = function(){
            common.req('setReceive', {params: {receive: $scope.dt.receive?1:0}}, function(data){
                if(data.code == 200){
                    $scope.profile = utils.getProfile();
                    $scope.profile.receive = $scope.dt.receive?1:0;
                    utils.setProfile($scope.profile);
                }else{
                    $rootScope.setReceive($scope.dt.receive);
                }
            });
        };


        $scope.call = function(){
            utils.confirm('拨打电话', '您确定拨打“'+utils.getServiceTel()+'”吗？', function(ok) {
                if(ok) {
                    utils.call(utils.getServiceTel());
                }
            });
        };

        $scope.clearCache = function(){
            utils.confirm('清空缓存确认', '您确定清空所有数据缓存吗？', function(ok) {
                if(ok) {
                    footprint({'func': 'clearCache'});       //记录脚印
                    var data = {};
                    if(utils.get('myPois')){
                        data.myPois = utils.get('myPois');
                    }
                    if(utils.get('curPois')){
                        data.curPois = utils.get('curPois');
                    }
                    if(utils.get('visitDt')){
                        data.visitDt = utils.get('visitDt');
                    }
                    if(utils.get('profile')){
                        data.profile = utils.get('profile');
                    }
                    if(utils.get('wx_auth')){
                        data.wx_auth = utils.get('wx_auth');
                    }
                    if(utils.get('auth')){
                        data.auth = utils.get('auth');
                    }
                    if(utils.get('phone')){
                        data.phone = utils.get('phone');
                    }
                    if(utils.get('app')){
                        data.app = utils.get('app');
                    }
                    if(utils.get('app_conf')){
                        data.app_conf = utils.get('app_conf');
                    }
                    utils.clearAll();
                    for(var k in data){
                        utils.set(k, data[k]);
                    }
                    utils.tip('清空缓存成功!')
                }
            });
        };
    }]);

    ctrl.controller('aboutCtrl', ['utils', 'common', '$scope', 'downloadInstallService', 'appInfoService', 'fileService', '$ionicPopup',
      function(utils, common, $scope, downloadInstallService, appInfoService, fileService, $ionicPopup){
        $scope.utils = utils;

        $scope.downAndInstall = function(){
            downloadInstallService.download('http://static.louxia100.com/com.louxia100.apk',function(){
                alert('success');
            },function(){
                alert('error');
            });
        };

        $scope.open = function(url){
            cordova.InAppBrowser.open(url, '_blank', 'location=yes');
        };

        appInfoService.info(function(ver){
            $scope.curVersion = ver;
        },function(){
            $scope.curVersion = utils.getVersion();
        });

    }]);

    ctrl.controller('couponCtrl', ['utils', 'common', '$scope', 'footprint', 'myCart', '$timeout', '$location', '$rootScope', '$ionicScrollDelegate',
      function(utils, common, $scope, footprint, myCart, $timeout, $location, $rootScope, $ionicScrollDelegate){
        $scope.utils = utils;

        var dt = {
            items: [],
            curPage: 0,
            hasMore: true,
            isEmpty: false,
            userid: utils.getProfile('userid'),
            appid: utils.getAppid(),
            type: 0,           //0:未使用 1:已使用 2:已过期
            hongbao: 0
        };
        $scope.dt = dt;

        if(utils.stateName() == 'app.hongbao' ){
            dt.hongbao = 1;
        }

        $scope.showUseful = function(){
            if($location.search()['useful'] == 1 ){
                return true;
            }
            return false;
        };

        $scope.ifShow = function(type, consume){
            var curConsume = $location.search()['consume'] || 0;
            if(type == 0 && $scope.showUseful() && parseFloat(curConsume) < parseFloat(consume) ){
                return false;
            }
            return true;
        };

        var getData = function(page, opt){
            page = page>0?page:1;
            dt.curPage = page;
            common.pageReq('getCoupon', {params: {page: page, hongbao: dt.hongbao, type: dt.type}}, page, 'couponid', 'myCoupon_'+dt.hongbao+'_'+dt.appid+'_'+dt.userid+'_'+dt.type, $scope, opt);
        };

        $scope.loadMore = function(){
            getData(dt.curPage+1, 'infinite');
        };

        $scope.doRefresh = function(){
            $timeout(function(){
                getData(1, 'refresh');
            },500);
        };

        $scope.clickBtn = function(type){
            dt.isEmpty = false;
            dt.type = type;
            $ionicScrollDelegate.$getByHandle('couponScroll').scrollTop();
            getData(1);
        };

        $scope.useCoupon = function(appid, couponid, coupon_typeid, consume, money){
            footprint({'func': 'useCoupon', 'hongbao': dt.hongbao, 'couponid': couponid, 'coupon_typeid': coupon_typeid, 'consume': consume, 'money': money});       //记录脚印
            if($location.search()['useful']){    //选择可用的优惠券、红包
                var curConsume = $location.search()['consume'] || 0;
                if( parseFloat(curConsume) < parseFloat(consume)){
                    if(dt.hongbao == 1){
                        utils.alert('无法使用当前红包！');
                    }else{
                        utils.alert('无法使用当前优惠券！');
                    }
                    return;
                }
                if(dt.hongbao == 1){
                    myCart.setParam('hongbaoid', couponid);
                    myCart.setParam('hongbaoTypeid', coupon_typeid);
                    myCart.setParam('hongbaoConsume', consume);
                    myCart.setParam('hongbaoMoney', money);
                }else{
                    myCart.setParam('couponid', couponid);
                    myCart.setParam('couponTypeid', coupon_typeid);
                    myCart.setParam('couponConsume', consume);
                    myCart.setParam('couponMoney', money);
                }
                utils.goState('pay');
                return;
            }
            utils.confirm('系统提示', '现在开始去购物吗？', function(ok) {
                if(ok) {
                    utils.goHome();
                }
            });
        };
    }]);

    ctrl.controller('subShopCtrl', ['utils', 'common', 'geoService', '$scope', '$timeout', function(utils, common, geoService, $scope, $timeout){
        $scope.utils = utils;
        $scope.common = common;
        var dt = {
            userid: utils.getProfile('userid'),
            appid: utils.getAppid(),
            conf: utils.getAppConf() || {},
            type: 0,           //0:附近门店 1:全部门店
            items: [],
            curPage: 0,
            hasMore: true,
            isEmpty: false,
            sub_shopid: 0,
            name: '',
            notice: '',
            address: '',
            tel: '',
            lng: '',
            lat: '',
            content: '',
            activity: 0,
            spinner: 1
        };
        $scope.dt = dt;
        dt.memberConf = dt.conf.member || {};

        var getSubShop = function(page, opt){
            page = page>0?page:1;
            dt.curPage = page;
            common.pageReq('getSubShop', {params: {page: page}}, page, 'sub_shopid', 'subShop_'+dt.appid, $scope, opt);
        };

        var getNearby = function(point, opt, cb){
            common.req('getNearSubShop', {params: {appid: dt.appid, lat:point.latitude, lng:point.longitude }}, function(data){
                dt.sub_shopid = data.sub_shopid || 0;
                dt.name = data.name || '';
                dt.notice = data.notice || '';
                dt.address = data.address || '';
                dt.tel = data.tel || '';
                dt.lng = data.lng || '';
                dt.lat = data.lat || '';
                if(data.code == 200){
                    cb && cb();
                }else if(data.code == 404){
                    utils.alert('请靠近签到位置再来签到！');
                }
                utils.broadcastScroll($scope, opt);
                utils.hideLoading();
            });
        };

        var locateNow = function(showLoading, opt, cb){
            if(showLoading){
                utils.showLoading('正在定位中...', 1);
            }
            geoService.getPoint(function(point){
                getNearby(point, opt, cb);
            },function(error){
                utils.hideLoading();
                utils.tip('定位失败，无法签到！');
            });
        };

        var getData = function(showLoading, opt, page){
            if(dt.type == 1){
                getSubShop(page, opt);
                return;
            }
            //获取活动单页
            common.getPage(dt.appid, 'lbs_sign', function(data){
                if(data.code == 200){
                    dt.title = data.title;
                    dt.content = data.content;
                    dt.activity = data.activity;
                }
                dt.spinner = 0;
                utils.broadcastScroll($scope, opt);
            }, opt);

            //自动定位
            locateNow(showLoading, opt);
        };

        if(utils.stateName() == 'app.sub_shop-sign'){
            dt.type = 0;
            getData(true);
        }else{
            dt.type = 1;
        }

        $scope.loadMore = function(){
            dt.type = 1;
            getData(!dt.type, 'infinite', dt.curPage+1);
        };

        $scope.doRefresh = function(){
            $timeout(function(){
                getData(true, 'refresh', 1);
            },500);
        };

        var getScore = function(){
            common.getScore('lbs_sign', dt.sub_shopid, dt.lat, dt.lng );
        };

        $scope.lbsSign = function(){
            if(dt.sub_shopid > 0 && dt.lat && dt.lng){
                getScore();
            }else{
                locateNow(true, '', getScore);
            }
        };

    }]);

    ctrl.controller('pageCtrl', ['utils', 'common', '$scope', '$timeout', '$stateParams', '$location',
      function(utils, common, $scope, $timeout, $stateParams, $location){
        $scope.utils = utils;
        $scope.common = common;
        var dt = {
            userid: utils.getProfile('userid'),
            appid: utils.getAppid(),
            conf: utils.getAppConf(),
            pageid: '',
            page:{},
            inviter:{},
            spinner: 1,
            stateName: utils.stateName()
        };
        $scope.dt = dt;

        console.log(dt);


        var time = new Date().getTime();
        dt.pageid = $stateParams['pageid'] || '';

        if( dt.stateName == 'app.list'){
            dt.pageid = dt.stateName.replace('app.','');
        }

        var getInviter = function(){
            dt.inviter = {
                'userid' : dt.userid,
                'nickname' : utils.getProfile('nickname'),
                'avatar' : utils.getProfile('avatar'),
                'sex' : utils.getProfile('sex'),
            };
        };
        var inviter_uid = dt.userid || 0;
        if($location.search()['inviter_uid']){
            inviter_uid = parseInt($location.search()['inviter_uid']);
            if(dt.pageid == 'haibao'){     //宣传海报页
                common.cacheReq('getInfo', {params: {uid: inviter_uid}}, 'user_info_'+inviter_uid, function(data){
                    if(data.code == 200 ){
                        delete data.code;
                        data.avatar = utils.getImgUrl(data.avatar);
                        dt.inviter = data;
                    }else{
                        getInviter();
                    }
                });
            }else{
                getInviter();
            }
        }else{
            getInviter();
        }

        if(dt.pageid == 'haibao' || dt.pageid == 'qrcode'){     //宣传海报页
            common.cacheReq('wxQrcode', {params: {uid: inviter_uid}}, 'wxQrcode_'+inviter_uid, function(data){
                if(data.code == 200 ){
                    $scope.wxQrcode = data.qrcode;
                }
            });
            $scope.qrcode = utils.getAppServer()+'/plugins/qrcode/qrcode.php?data='+utils.getAppServer()+'/?inviter_uid='+inviter_uid;
        }

        if(dt.pageid == 'qrcode'){                              //我的二维码
            $scope.myqrcode = utils.getAppServer()+'/plugins/qrcode/qrcode.php?data={"userid":'+utils.getProfile('userid')+',"phone":'+utils.getProfile('phone')+',"app_userid":'+utils.getProfile('app_userid')+'}';
            $scope.mybarcode = utils.getAppServer()+'/plugins/barcode/barcode.php?data='+utils.getProfile('phone')+'';
        }

        $scope.shareIt = function(){
            common.shareIt($scope, dt.page.title, (dt.page.desc && dt.page.desc !='null')?dt.page.desc:dt.page.content, '', dt.page.img || dt.inviter.avatar);
        };

        $scope.hideNavBar = function(){
            if(dt.page.hide_bar == 1){
                return true;
            }
            return false;
        };
        var get_content_style = function(){
            var style = {};
            if(dt.page.bg_color && dt.page.bg_color !='NULL'){
                style['background-color'] = dt.page.bg_color;
            }
            if(dt.page.bg_img && dt.page.bg_img !='NULL'){
                style['background-image'] = 'url('+dt.page.bg_img+')';
                style['background-repeat'] = 'no-repeat';
                style['background-position'] = 'top center';
                style['background-size'] = '100%';
            }
            $scope.content_style = style;
        };

        var getData = function(opt, pageid){
            common.getPage(dt.appid, pageid, function(data){
                if(data.code == 200){
                    delete data.code;
                    data.img = utils.getImgUrl(data.img);
                    data.bg_img = utils.getImgUrl(data.bg_img);
                    dt.page = data;
                    get_content_style();
                    common.initWxShare(dt.page.title, (dt.page.desc && dt.page.desc !='null')?dt.page.desc:dt.page.content, '', dt.page.img || dt.inviter.avatar);
                }
                dt.spinner = 0;
                utils.broadcastScroll($scope, opt);
            }, opt);
        };

        getData('', dt.pageid);

        $scope.doRefresh = function(){
            $timeout(function(){
                getData('refresh', dt.pageid);
            },500);
        };

        $scope.toUrl = function(url){
            url = url.replace(/&amp;/g,'&');
            utils.toUrl(url);
        };

        /*判断活动是否进行中*/
        var checkIng = function(conf){
            if(!conf || conf.open !=1 ){
                utils.alert('活动暂未开始，请联系客服！');
                return ;
            }
            if(Date.parse(conf.start_at) > time){
                utils.alert('活动开始时间：'+conf.start_at);
                return ;
            }
            if(Date.parse(conf.end_at) < time){
                utils.alert('本次活动已结束，请期待下一次吧！');
                return ;
            }
            return true;
        };

        /* 领取优惠券 */
        $scope.getCoupon = function(){
            if(checkIng(dt.conf.coupon)){
                common.ifCallback(function(){
                    common.req('addCoupon', {}, function(data){});
                });
            }
        };

        /* 领取红包 */
        $scope.getHongbao = function(){
            if(checkIng(dt.conf.hongbao)){
                common.ifCallback(function(){
                    common.req('addHongbao', {}, function(data){});
                });
            }
        };

        /* 申请分销商 */
        $scope.applyTrader = function(){
            if(checkIng(dt.conf.trader)){
                common.ifCallback(function(){
                    common.req('traderAdd', {}, function(data){});
                });
            }
        };

    }]);



    ctrl.controller('storeCtrl', ['utils', 'common', 'myCart', '$scope', '$timeout', '$ionicSlideBoxDelegate', '$interval', '$rootScope','$stateParams',
        function(utils, common, myCart, $scope, $timeout, $ionicSlideBoxDelegate, $interval, $rootScope,$stateParams){
            $scope.utils = utils;
            var dt = {
                myPois: utils.getMyPois(),
                appid: utils.getAppid(),
                appName: utils.getAppName(),
                app: utils.get('app'),
                sort_goods: [],
                conf: utils.getAppConf() || {},
                stateName: utils.stateName(),
                spinner: 1,
                items: [],
                curPage: 0,
                hasMore: false,
                storeid: $stateParams['storeid']?parseInt($stateParams['storeid']):0,
                subGroupid: 0,
                hideItems: {},
                showMoreGoods: false
            };
            //如果groupid大于0获取groups中的home配置
            //groupid获取子分类
            if(dt.groupid>0) {
                var getHomeSubGroup = function (opt) {
                    common.cacheReq('getHomeSubGroup', {
                        params: {
                            appid: dt.appid,
                            groupid: dt.groupid
                        }
                    }, 'getHomeSubGroup_' + dt.appid+dt.groupid, function (data) {
                        if (data.code == 200) {
                            dt.conf.home.btns[0] = data.home_sub_groups;
                        }
                    }, opt);
                };
                getHomeSubGroup();
            }
            $scope.dt = dt;
            var getstore = function (opt) {
                common.cacheReq('getStore', {params: {appid: dt.appid,storeid: dt.storeid} }, 'getStore_'+dt.appid+'_'+dt.storeid, function(data){
                    if(data.code == 200){
                        $scope.dt.store = data.store[0];
                        dt.storeid = data.store[0].storeid;
                    }
                },opt);

                common.cacheReq('getAd', {params: {appid: dt.appid,groupid: dt.groupid,storeid:dt.storeid,channel:5} }, 'ad_list_'+dt.appid+'_'+dt.groupid+'_'+dt.storeid+'_'+dt.storeid+'_5', function(data){
                    if(data.code == 200){
                        $scope.dt.ad = data.ad_list;
                    }
                },opt);

                common.cacheReq('getSpecialList', {params: {appid: dt.appid,groupid: dt.groupid,storeid:dt.storeid,style:2} }, 'special_'+dt.appid+'_'+dt.groupid+'_'+dt.storeid+'_2', function(data){
                    if(data.code == 200){
                        $scope.dt.SpecialList = data.special_list;
                    }
                },opt);
                utils.broadcastScroll($scope, opt);
            }
            getstore();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getstore('refresh');
                },500);
            };

            $scope.dt.nav = -1;

            $scope.moreGoods = function(groupid, sub_groupid){
                utils.goUrl('shop/'+(groupid || 0)+'/'+(sub_groupid || 0))
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

            $scope.toUrl = function(url){
                url = url.replace(/&amp;/g,'&');
                url = url.replace('tab/shopping/','');
                url = url.replace('tab/mine/','');
                url = url.replace('tab/home/','');
                url = url.replace('tab/','');
                utils.goUrl(url);
            };
        }]);

    ctrl.controller('storeGoodsCtrl', ['utils', 'common', 'myCart', '$scope', '$timeout', '$ionicSlideBoxDelegate', '$interval', '$rootScope','$stateParams',
        function(utils, common, myCart, $scope, $timeout, $ionicSlideBoxDelegate, $interval, $rootScope,$stateParams){
            $scope.utils = utils;
            var dt = {
                myPois: utils.getMyPois(),
                appid: utils.getAppid(),
                appName: utils.getAppName(),
                app: utils.get('app'),
                sort_goods: [],
                conf: utils.getAppConf() || {},
                stateName: utils.stateName(),
                spinner: 1,
                items: [],
                curPage: 0,
                hasMore: false,
                storeid: $stateParams['storeid']?parseInt($stateParams['storeid']):0,
                recommend: $stateParams['recommend']?parseInt($stateParams['recommend']):0,
                groupid: $stateParams['groupid']?parseInt($stateParams['groupid']):0,
                nav: $stateParams['recommend']?parseInt($stateParams['recommend']):0,
                subGroupid: 0,
                hideItems: {},
                showMoreGoods: false
            };
            //如果groupid大于0获取groups中的home配置
            //groupid获取子分类
            if(dt.groupid>0) {
                var getHomeSubGroup = function (opt) {
                    common.cacheReq('getHomeSubGroup', {
                        params: {
                            appid: dt.appid,
                            groupid: dt.groupid
                        }
                    }, 'getHomeSubGroup_' + dt.appid+dt.groupid, function (data) {
                        if (data.code == 200) {
                            dt.conf.home.btns[0] = data.home_sub_groups;
                        }
                    }, opt);
                };
                getHomeSubGroup();
            }

            $scope.dt = dt;

            //猜你喜欢
            var getData = function(page, opt, concat){
                page = page>0?page:1;
                dt.curPage = page;
                common.pageReq('getStoreGoods', {params: {appid: dt.appid, page: page,storeid:dt.storeid,recommend:dt.recommend,groupid:dt.groupid}}, page, 'goodsid', 'appGoods_'+dt.appid+'_'+dt.groupid+'_'+dt.subGroupid+'_'+dt.recommend+'_'+dt.groupid, $scope, opt, function(){
                    dt.showMoreGoods = dt.items.length?true:false;
                    dt.items = myCart.syncData(dt.items, dt.appid);     //同步购物车数据
                    utils.initCountDown($scope);
                }, concat);

                common.cacheReq('getStore', {params: {appid: dt.appid,storeid: dt.storeid} }, 'getStore_'+dt.appid+'_'+dt.storeid, function(data){
                    if(data.code == 200){
                        $scope.dt.store = data.store[0];
                    }
                },opt);

                common.cacheReq('getGroup', {params: {groupid: dt.groupid} }, 'getGroup_'+dt.groupid, function(data){
                    if(data.code == 200){
                        $scope.dt.group_info = data.group_info[0];
                    }
                },opt);
                utils.broadcastScroll($scope, opt);
            };
            getData();

            $scope.loadMore = function(){
                getData(dt.curPage+1, 'infinite', true);
            };

            $scope.doRefresh = function(){
                dt.items = [];
                dt.curPage = 0;
                dt.hasMore = false;
                dt.hideItems = {};
                dt.showMoreGoods = false;
                $timeout(function(){
                    getData('refresh');
                },500);
            };

            $scope.moreGoods = function(groupid, sub_groupid){
                utils.goUrl('shop/'+(groupid || 0)+'/'+(sub_groupid || 0))
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

            $scope.toUrl = function(url){
                url = url.replace(/&amp;/g,'&');
                url = url.replace('tab/shopping/','');
                url = url.replace('tab/mine/','');
                url = url.replace('tab/home/','');
                url = url.replace('tab/','');
                utils.goUrl(url);
            };
        }]);


    ctrl.controller('storeClassCtrl', ['utils', 'common', 'myCart', '$scope', '$timeout', '$ionicSlideBoxDelegate', '$interval', '$rootScope','$stateParams',
        function(utils, common, myCart, $scope, $timeout, $ionicSlideBoxDelegate, $interval, $rootScope,$stateParams){
            $scope.utils = utils;
            var dt = {
                myPois: utils.getMyPois(),
                appid: utils.getAppid(),
                appName: utils.getAppName(),
                app: utils.get('app'),
                sort_goods: [],
                conf: utils.getAppConf() || {},
                stateName: utils.stateName(),
                spinner: 1,
                items: [],
                curPage: 0,
                hasMore: false,
                storeid: $stateParams['storeid']?parseInt($stateParams['storeid']):0,
                subGroupid: 0,
                hideItems: {},
                showMoreGoods: false
            };

            $scope.dt = dt;

            //猜你喜欢
            var getData = function(page, opt, concat){
                common.cacheReq('getStore', {params: {appid: dt.appid,storeid: dt.storeid} }, 'getStore_'+dt.appid+'_'+dt.storeid, function(data){
                    if(data.code == 200){
                        $scope.dt.store = data.store[0];
                        console.log($scope.dt.store);
                    }
                },opt);
                utils.broadcastScroll($scope, opt);
            };
            getData();

            $scope.doRefresh = function(){
                dt.items = [];
                dt.curPage = 0;
                dt.hasMore = false;
                dt.hideItems = {};
                dt.showMoreGoods = false;
                $timeout(function(){
                    getData('refresh');
                },500);
            };

            $scope.moreGoods = function(groupid, sub_groupid){
                utils.goUrl('shop/'+(groupid || 0)+'/'+(sub_groupid || 0))
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

            $scope.toUrl = function(url){
                url = url.replace(/&amp;/g,'&');
                url = url.replace('tab/shopping/','');
                url = url.replace('tab/mine/','');
                url = url.replace('tab/home/','');
                url = url.replace('tab/','');
                utils.goUrl(url);
            };
            //跳转到店铺分类商品列表
            $scope.toStoreGroup = function(groupid){
                var group_url = 'storegoods/'+dt.storeid+'/0/'+groupid;
                utils.goUrl(group_url);
            };
        }]);
    ctrl.controller('overseaCtrl', ['utils', 'common', 'myCart', '$scope', '$timeout', '$ionicSlideBoxDelegate', '$interval', '$rootScope','$stateParams',
        function(utils, common, myCart, $scope, $timeout, $ionicSlideBoxDelegate, $interval, $rootScope,$stateParams){
            $scope.utils = utils;
            var dt = {
                appid: utils.getAppid(),
                appName: utils.getAppName(),
                app: utils.get('app'),
                conf: utils.getAppConf() || {},
                origin_place: $stateParams['origin_place']?parseInt($stateParams['origin_place']):0,
            };

            $scope.dt = dt;


            dt.memberConf = dt.conf.member || {};
            dt.groupsConf = dt.conf.groups || {};
            dt.homeConf = dt.conf.home || {};
            dt.homeConf.slide = dt.homeConf.slide || {};
            // dt.homeConf.adv = dt.homeConf.adv || {};
            dt.homeConf.special = dt.homeConf.special || {};
            dt.homeConf.more_goods = dt.homeConf.more_goods || {};
            dt.homeConf.more_goods.groupid = dt.homeConf.more_goods.groupid || 0;
            dt.homeConf.more_goods.sub_groupid = dt.homeConf.more_goods.sub_groupid || 0;

            // dt.ad = [{"type":1,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]},{"type":1,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]},{"type":2,"detail":[{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"},{"image":"/upload/1/2016-06/pic/201606240819548294.png","url":"tab/activity/10000","name":"分类"}]}];

            var it = $interval(function(){
                utils.countDown($scope);
            }, 1000);

            $scope.$on('$destroy', function() {
                $interval.cancel(it);
            });

            var getData = function(opt){
                var channel = dt.origin_place > 0 ? 4 : 3;
                var style = dt.origin_place > 0 ? 4 : 3;
                //add yyh 2017.4.20广告
                common.cacheReq('getAd', {params: {appid: dt.appid,groupid: dt.groupid,channel:channel,origin_place:dt.origin_place} }, 'ad_list_'+dt.appid+'_'+dt.groupid+'_'+channel+'_'+dt.origin_place, function(data){
                    if(data.code == 200){
                        $scope.dt.ad = data.ad_list;
                    }
                },opt);

                //add yyh 2017.4.20专题
                common.cacheReq('getSpecialList', {params: {appid: dt.appid,groupid: dt.groupid,style:style,origin_place:dt.origin_place} }, 'special_'+dt.appid+'_'+dt.groupid+'_'+style+'_'+dt.origin_place, function(data){
                    if(data.code == 200){
                        $scope.dt.SpecialList = data.special_list;
                    }
                },opt);
                utils.broadcastScroll($scope, opt);
            };
            getData();

            $scope.doRefresh = function(){
                $timeout(function(){
                    getData('refresh');
                },500);
            };

            $scope.moreGoods = function(groupid, sub_groupid){
                utils.goUrl('shop/'+(groupid || 0)+'/'+(sub_groupid || 0))
            };

            $scope.orderIt = function(item){
                myCart.orderIt(dt.app, item, item.tag.status, item.tag.ing);
            };

            $scope.goGoods = function(goodsid){
                utils.goState('goods', {goodsid: goodsid});
            };

            $rootScope.$on('syncIt', function(event, appid) {
                dt.items = myCart.syncData(dt.items, appid);     //同步购物车数据
            });

            $scope.addQuantity = function(item){
                myCart.addQuantity(dt.app, dt.items, item);
            };

            $scope.subQuantity = function(item){
                myCart.subQuantity(dt.app, dt.items, item);
            };

            $scope.calcTotal = function(){
                return myCart.calcTotal(dt.appid);
            };

            $scope.ifNeedDeliveryFee = function(){
                return myCart.ifNeedDeliveryFee(dt.app);
            };

            $scope.toUrl = function(url){
                url = url.replace(/&amp;/g,'&');
                url = url.replace('tab/shopping/','');
                url = url.replace('tab/mine/','');
                url = url.replace('tab/home/','');
                url = url.replace('tab/','');
                utils.goUrl(url);
            };
        }]);
});