define(['app', 'ionic'], function(app){
    'use strict';
    app.constant('config', {
        homeUrl: '/app/home/',
        baiduGeoConfig: {
            host: 'https://api.map.baidu.com/',
            ak: 'GcVjt8GnhVNpnyVRwuxG43i9',
            geotable_id: 117289
        },
        uploadMin: 1024*1,
        ploadMax: 1024*1024*1,
        mySexArr: ['保密','男','女'],
        sexArr: ['','先生','女士'],
        orderStatus: {
            0: '已过期',
            1: '待付款',
            2: '已付款，待发货',
            3: '已发货，待收货',
            4: '已收货，待评价',
            5: '订单完成'
        },
        incomeType: {
            0: '未知收入',
            1: '订单提成',
            2: '下线提现奖励',
            3: '系统奖励',
            7: '提现到账户余额',
            8: '提现到支付宝',
            9: '提现到银行卡'
        },
        withdrawType: {
            0: '提现到余额',
            1: '提现到微信',
            2: '提现到支付宝',
            3: '提现到银行卡'
        },
        scoreType: {
            "-2": "管理员扣除",
            "-1": "系统扣除",
            0: "兑换商品",
            1: "系统赠送",
            2: "线上购物获得",
            3: "线下购物获得",
            4: "每日签到获得",
            5: "连续签到奖励",
            6: "LBS签到获得",
            7: "生日祝福获得",
            8: "系统返还",
            9: "管理员发放",
            10: "邀请奖励"
        },
        delivery_company: {
            '中通' : 'zhongtong',
            '圆通' : 'yuantong',
            '申通' : 'shentong',
            '韵达' : 'yunda',
            '顺丰' : 'shunfeng',
            '宅急送' : 'zhaijisong',
            '全峰' : 'quanfengkuaidi',
            '天天' : 'tiantian'
        },
        banner: {
            width: 640,
            height: 220,
            changeTime: 5000
        },
        resConfig: {
            wxLogin: {
                url: 'plugins/wechat/WxLogin.php',
                method: 'GET',
                apiType: 'plugins'
            },
            wxBind: {
                url: 'plugins/wechat/WxBind.php',
                method: 'GET',
                apiType: 'plugins'
            },
            wxJsApi: {
                url: 'plugins/wechat/WxJsApi.php',
                method: 'GET',
                apiType: 'plugins'
            },
            wxQrcode: {
                url: 'plugins/wechat/WxQrcode.php',
                method: 'GET',
                apiType: 'plugins'
            },
            wxPayHybrid: {
                url: 'plugins/wechat/WxPayHybrid.php',
                method: 'GET',
                apiType: 'plugins'
            },
            getDeliveryDetail: {
                url: 'delivery/detail',
                method: 'GET'
            },
            getSlideNews: {
                url: 'news/best',
                method: 'GET'
            },
            setPushid: {
                url: 'user/pushid',
                method: 'POST'
            },
            login: {
                url: 'user/login',
                method: 'POST'
            },
            updateVisit: {
                url: 'visit/update',
                method: 'POST'
            },
            addFootprint: {
                url: 'footprint/add',
                method: 'POST'
            },
            logout: {
                url: 'user/logout',
                method: 'GET'
            },
            getAppGoods: {
                url: 'app/goods/aid/:appid',
                method: 'GET'
            },
            getAppHome: {
                url: 'app/home/aid/:appid',
                method: 'GET'
            },
            getAppDetail: {
                url: 'app/detail/aid/:appid',
                method: 'GET'
            },
            getAppConf: {
                url: 'app/conf/aid/:appid',
                method: 'GET'
            },
            getSubShop: {
                url: 'sub_shop/list',
                method: 'GET'
            },
            getSubShopDetail: {
                url: 'sub_shop/detail',
                method: 'GET'
            },
            getNearSubShop: {
                url: 'sub_shop/near_list',
                method: 'GET'
            },
            getPage: {
                url: 'page/detail',
                method: 'GET'
            },
            getSpecialDetail: {
                url: 'app/special',
                method: 'GET'
            },
            getBestGoods: {
                url: 'goods/best/gid/:gid',
                method: 'GET'
            },
            getGoodsDetail: {
                url: 'goods/detail',
                method: 'GET'
            },
            getUnread: {
                url: 'msg/unread',
                method: 'GET'
            },
            getMsg: {
                url: 'msg/list',
                method: 'GET'
            },
            getMsgDetail: {
                url: 'msg/detail',
                method: 'GET'
            },
            getNews: {
                url: 'news/list',
                method: 'GET'
            },
            getNewsDetail: {
                url: 'news/detail',
                method: 'GET'
            },
            getTraderLevels: {
                url: 'trader_level/list',
                method: 'GET'
            },
            getUserLevels: {
                url: 'user_level/list',
                method: 'GET'
            },
            getCityList: {
                url: 'city/list',
                method: 'GET'
            },
            getDistrictList: {
                url: 'district/list',
                method: 'GET'
            },
            getCommunityList: {
                url: 'community/list',
                method: 'GET'
            },
            getCouponType: {
                url: 'coupon_type/list',
                method: 'GET'
            },
            addCoupon: {
                url: 'coupon_type/receive_coupon',
                method: 'GET'
            },
            addHongbao: {
                url: 'coupon_type/receive_hongbao',
                method: 'GET'
            },
            getCoupon: {
                url: 'coupon/list',
                method: 'GET'
            },
            getInfo: {
                url: 'user/info',
                method: 'GET'
            },
            getProfile: {
                url: 'user/profiles',
                method: 'GET'
            },
            traderAdd: {
                url: 'trader/add',
                method: 'POST'
            },
            getTraderInfo: {
                url: 'trader/info',
                method: 'GET'
            },
            getTraderList: {
                url: 'trader/list',
                method: 'GET'
            },
            getCustomerList: {
                url: 'trader/customer',
                method: 'GET'
            },
            getWithdrawList: {
                url: 'withdraw/list',
                method: 'GET'
            },
            withdrawNow: {
                url: 'withdraw/apply',
                method: 'GET'
            },
            getIncomeList: {
                url: 'income/list',
                method: 'GET'
            },
            getReportList: {
                url: 'report/list',
                method: 'GET'
            },
            setNickname: {
                url: 'user/nickname',
                method: 'POST'
            },
            setBirthday: {
                url: 'user/birthday',
                method: 'POST'
            },
            setSex: {
                url: 'user/sex',
                method: 'POST'
            },
            setReceive: {
                url: 'app_user/receive',
                method: 'POST'
            },
            scoreAdd: {
                url: 'app_user/score_add',
                method: 'GET'
            },
            getScoreLog: {
                url: 'score_log/list',
                method: 'GET'
            },
            setPassword: {
                url: 'user/password',
                method: 'POST'
            },
            setComplain: {
                url: 'leave_word/add',
                method: 'POST'
            },
            getAddress: {
                url: 'address/list',
                method: 'GET'
            },
            removeAddress: {
                url: 'address/del',
                method: 'POST'
            },
            addAddress: {
                url: 'address/add',
                method: 'POST'
            },
            getOrders: {
                url: 'order/list',
                method: 'GET'
            },
            getOrder: {
                url: 'order/detail',
                method: 'GET'
            },
            removeOrder: {
                url: 'order/del',
                method: 'POST'
            },
            received: {
                url: 'order/received',
                method: 'GET'
            },
            comment: {
                url: 'order/comment',
                method: 'POST'
            },
            addOrder: {
                url: 'order/add',
                method: 'POST'
            },
            bindCode: {
                url: 'sms/bind_code',
                method: 'POST'
            },
            registerCode: {
                url: 'sms/code',
                method: 'POST'
            },
            verifyCode: {
                url: 'sms/verify',
                method: 'POST'
            },
            phoneReg: {
                url: 'user/phone_reg',
                method: 'POST'
            },
            phoneBind: {
                url: 'user/phone_bind',
                method: 'POST'
            },
            phoneLogin: {
                url: 'user/phone_login',
                method: 'POST'
            },
            register: {
                url: 'user/add',
                method: 'POST'
            },
            forgotCode: {
                url: 'user/forgot_code',
                method: 'POST'
            },
            forgot: {
                url: 'user/password_reset',
                method: 'POST'
            },
            bind: {
                url: 'user/bind',
                method: 'POST'
            },
            uploadAvatar: {
                url: 'uploader/avatar',
                method: 'POST'
            },
            //GPS坐标转百度坐标
            geoconv: {
                url: 'geoconv/v1/',
                method: 'JSONP'
            },
            //百度LBS云反解析地理位置
            geocoder: {
                url: 'geocoder/v2/',
                method: 'JSONP'
            },
            //百度LBS云检索应用数据
            geosearch: {
                url: 'geosearch/v3/nearby',
                method: 'JSONP'
            },
            //IP定位
            geoip: {
                url: 'location/ip',
                method: 'JSONP'
            },
            /**********************author YYH*****************************/

            getSpecialList: {
                url: 'app/speciallist',
                method: 'GET'
            }
            ,getAdList: {
                url: 'app/adlist',
                method: 'GET'
            }
            ,getHomeGroupsList: {
                url: 'app/homegroups',
                method: 'GET'
            }
            ,getHomeSubGroup: {
                url: 'app/homesubgroup',
                method: 'GET'
            }
            ,getAd: {
                url: 'app/adlist',
                method: 'GET'
            }
            ,getStore:{
                url: 'store/store/aid/:appid/storeid/:storeid',
                method: 'GET'
            }
            ,getStoreGoods: {
                url: 'store/goods/aid/:appid/storeid/:storeid/recommend/:recommend/gid/:groupid',
                method: 'GET'
            }
            ,getGroupsList: {
                url: 'app/groups/aid/:appid',
                method: 'GET'
            }
            ,getGroup: {
                url: 'store/group/groupid/:groupid',
                method: 'GET'
            }
        }
    });
});