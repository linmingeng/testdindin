<title>My html 'header.html' starting page</title>

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?63dc04777ecd9e2bbc85fa861ce7a9ca";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
  })();



</script>
<link href="./css/common_style.css" rel="stylesheet" media="screen">
<style type="text/css">
    /*悬浮购物车样式*/
    .cart-fixed {width:45px;height:45px;position:fixed;bottom:10px;left:10px;background:rgb(246, 68, 99);border-radius:100%;display:none;z-index:99;}
    .cart-fixed i{display: block;width: 30px;height: 30px;margin: 0 auto;background-size: 30px 30px;
        position: absolute;left: 50%;top: 50%;margin: -15px 0 0 -15px;}
        .cart-fixed a{display:block;width:100%;height:100%;}
        .cart-fixed .cartNum{font-size: 13px;color: #67850E;background: #fff;border-radius: 100%;font-weight: bold;position: absolute;right: 7px;bottom: 4px;
            display: block;padding: 2px;min-width: 20px;min-height: 20px;text-align:center;}
            .nameEllipsis{TEXT-OVERFLOW: ellipsis;WHITE-SPACE: nowrap;OVERFLOW: hidden;}
            .cart_holder i.fa-shopping-cart,.icon_holder .fa-star{color:#4ac4fa;}
            .menu_cell nowrap .fa-sort-desc,.main_menu .fa-sort-desc{position: absolute;right: 4px;top: 12px;color: #A3A1A1;}
            .menu_wrapper .categories.vertical.megamenu .fa-sort-desc,.vertical .sub_trigger .fa-sort-desc{position: absolute;top: 17px;right: 20px;-webkit-transform: rotate(-94deg);-o-transform: rotate(-94deg);-moz-transform: rotate(-94deg);-ms-transform: rotate(-94deg);}
    .has-sub>ul li .wrapper{/*position: absolute;
    z-index: 2;
    left: -1px;
    top: 55px;
    background: #ffffff;
    border: 1px solid #eaeaea;
    padding: 10px 17px;
    margin-top: 15px;
    display: table;
        opacity: 0;
    visibility: hidden;
    height: 0;
    transition: margin-top 300ms, opacity 200ms, visibility 200ms;
    white-space: normal;
    max-width: 1139px !important;*/}
    .wrapper ul{position: static;border:none;}
    .has-sub>ul{width: 800px;}
    #top-menu .ul2 li a,#top-menu .ul2 li{padding: 4px;}
    .main_menu > ul.horizontal > li > a {

    }
    #cont-container{overflow: hidden;}
    #seckillLi img:nth-child(1){width:68px;position:absolute;right:0;top:-22px;}
    #pifa a img{position:absolute;transform:rotate(90deg);width:44px;height:82px!important;z-index:99;left:20px;top:-20px;}
    .seckill_hot{position:absolute;display:none;width: 28px;top: 2px;left: 80px;}
    @media screen and (min-width: 992px){
        #top-menu .has-sub>ul{width: 800px;}
    }
    @media screen and (max-width: 991px){
        #top-menu .has-sub>ul{width: 600px;}
        #seckillLi img:nth-child(1){display:none;}
        .seckill_hot{display:block;}
    }
    @media screen and (max-width: 640px){
        #top-menu .has-sub>ul{width: 100%;}
    }
    @media only screen and (max-width: 1199px) and (min-width: 992px){
        .main_menu > ul.horizontal > li > a {
            padding-left: 8px;
            padding-right: 18px;
        }
    }
    .megamenu .hover-menu .sec-cate{font-weight:bold;}
    .megamenu .three-cate{overflow:hidden;}
    .megamenu .three-cate li{float:left;width: auto!important;min-width: 57px;margin-right: 11px;height:20px;line-height:20px;margin-bottom:5px;}
    .megamenu .three-cate li a{font-size:12px;color:#428bca;}
    .megamenu .three-cate li a:hover{text-decoration: underline;}
    .megamenu .hover-menu .menu>ul>li{margin-bottom:5px;}
    .username.error-msg,.userpwd.error-msg,.verifycode.error-msg{border:1px solid rgb(243, 170, 170);}
    .active_caizhuang{background:#F72AC6;}
    .active_select{background:#018bc8;}
    .active_select>a,.active_select i.fa-sort-desc,.active_caizhuang>a,.active_caizhuang i.fa-sort-desc{color:#fff;}
    .namesubstr{font-size:13px;}
    .shopping-cart {
        -webkit-transition: all 200ms ease-in-out;
        -moz-transition: all 200ms ease-in-out;
        -ms-transition: all 200ms ease-in-out;
        -o-transition: all 200ms ease-in-out;
        position: absolute;
        left: 7px;
        background: url(./image/shop_cart_img.png) no-repeat;
        width: 35px;
        height: 35px;
        top: 2px;
    }


    #cart .cart_holder .total{color:#fff;}
    .outer_container .menu_table .menu_cell .main_menu ul#top-menu li a{border:none;}
    #cart .cart_holder .count{top: 11px;color: #fff;width: 21px;height: 21px;left: 22px;border-radius: 100%;line-height: 21px;}
    #main-category .first-cate{padding-left:20px;}
    #main-category .first-cate img{margin-right:5px;}
    #ajax_search_results .search_word_item{height:30px;line-height:30px;}

    .my-dindin{position:relative;padding-left: 10px!important;}
    .my-dindin>a{color:rgb(14, 162, 205);}
    .my-dindin:hover{background:#fff;border-left:1px solid #BAB8B8;cursor:pointer;}
    .my-dindin i{width:7px;height:7px;display:inline-block;border-top:1px solid rgb(14, 162, 205);border-right:1px solid rgb(14, 162, 205);vertical-align: text-top;
        transition:all .5s ease;transform:rotate(132deg);-wibkit-transform:rotate(132deg);-o-transform:rotate(132deg);-ms-transform:rotate(132deg);-moz-transform:rotate(132deg);}
        .my-dindin i.on{transform: rotate(-44deg);-wibkit-transform:rotate(-44deg);-o-transform:rotate(-44deg);-ms-transform:rotate(-44deg);-moz-transform:rotate(-44deg);margin-top: 5px;}
        .my-dindin .a-sub-menu{position:absolute;left:-1px;top:21px;width:87px;z-index: 9999;background: #fff;border: 1px solid #BAB8B8;border-top:none;padding-top:5px;text-align: left;
            display:none;}
            .my-dindin .a-sub-menu a{padding-left: 10px;}
            .my-dindin .a-sub-menu>div{height:30px;line-height:30px;}
            .my-dindin .a-sub-menu>div:hover{background:#F7F7F7;}
            .inputsr{padding-top:18px;}
            .downApp{color:rgb(14, 162, 205);display:none;margin-left:10px;}
            @media screen and (max-width:767px){
                .inputsr{padding-top:0;}
                .downApp{display:inline-block;}
                .gifimg{display:none;}
            }
</style>
    
    <script src="./js/jquery.md5.js" type="text/javascript"></script>
    <script src="./js/cart.js" type="text/javascript"></script>
    <script type="text/javascript">
        var _mvq = _mvq || [];
        _mvq.push(['$setAccount', 'm-199926-0']);
        
        _mvq.push(['$logConversion']);
        (function() {
            var mvl = document.createElement('script');
            mvl.type = 'text/javascript'; mvl.async = true;
            mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(mvl, s);
        })();    
    </script> 
    <div class="main-bgs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cont-left">
                        <div id="nologin" style="">您好，欢迎光临！请
                            <a id="popup_login1" href="javascript:void(0)" style="color:#009de0">登录</a> 
                            <a class="gifimg" href="?/user/register" style="color:#009de0">免费注册</a>
                            <a class="downApp" href="#">APP下载</a>
                        </div>
                        <div id="user_login" style="display:none;">
                            <a href="javascript:void(0)" id="merber-go"><span id="user_nick" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;display: inline-block;max-width: 100px;vertical-align: sub;"></span> </a>
                            <span>欢迎来到叮叮网！</span>
                            <a href="javascript:void(0)" id="loginout" class="cboxElement2">退出</a>
                            <a class="downApp" href="#">APP下载</a>
                        </div>
                        
                    </div>
                    <div class="cont-right">
                        <ul style="margin-bottom: 0px; ">
                            <li class="my-dindin">
                                <a href="?personalInfo/view">我的叮叮 <i></i></a>
                                <div class="a-sub-menu">
                                    <div><a href="?/order/list">我的订单</a></div>
                                    <div><a href="?receiveAddress/view">地址管理</a></div>
                                </div>
                            </li>
                            <!-- <li class="webchat"><a href="javascript:void(0)">手机版</a>-->
                            <li class="webchat"><a href="javascript:void(0)">手机APP</a>
                                <div class="webchatimg"><img src="./image/dindin.png"></div>
                            </li>
                            <li class="service">客服热线：<span>400-0591-576</span></li>
                            <li><a href="http://www.dindin.com/join_us.jsp" target="_blank" style="color:red;">商城招商</a></li>

                            <li><a href="http://mer.dindin.com/">我是卖家</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.my-dindin').hover(function(){$('.a-sub-menu').show();$('.my-dindin i').addClass('on');},function(){$('.a-sub-menu').hide();$('.my-dindin i').removeClass('on')})
    </script>
    <div class="header_wrapper sticky_menu header5">
        <div class="container">
            <div class="row header" style="padding-top: 10px;">
                <!-- logo -->
                <div class="col-md-3 col-xs-5 col-sm-3 tablet_center mobile_center logos" style="padding: 0;">
                    <div class="logo ">
                        <a href="http://www.dindin.com"><img src="./image/dindin_logo.png" alt="叮叮网" class="logo"></a>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6 baoyou" style="padding-top:8px;padding-left:0;">
                    <ul>
                        <li><a href="http://www.dindin.com/activity/surprise.jsp"><img src="./image/zheng.jpg"></a></li>
                        <li><a href="http://www.dindin.com/activity/surprise.jsp"><img src="./image/mian.jpg"></a></li>
                        <li><a href="http://www.dindin.com/register.jsp"><img src="./image/jifen.jpg"></a></li>
                    </ul>
                </div>
                <!-- promo -->
                <!-- language / currency -->
                <!-- search -->

                <div class="col-sm-12 col-md-5 col-lg-5 col-xs-12 input-search pull-right inputsr">
                    <div class="search_wrapper">
                        <i class="fa fa-search" style="position: absolute;right: 0;top:0;font-size: 24px;color: #fff;width: 74px;height: 33px;line-height: 32px;text-align: center;cursor:pointer;background: #009de0;" onclick="gotoSearch()"></i>
                        <div id="search">
                            <input type="text" name="search" class="search_input" placeholder="输入关键字" id="search_input">
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container ends -->
        <div class="menu_wrapper">
            <div class="sticky_wrapper sticky">
                <div class="outer_container" style="background: #444444;height:40px;">
                    <div class="container nopaddings">
                        <div class="menu_table">

                            <div class="menu_cell nowrap">
                                <div class="main_menu">
                                    <ul style="margin: 0px;">
                                        <li class="trigger"><a class="v_menu_trigger light_bg_color"><i class="fa fa-reorder" style="font-size:23px;margin-right:5px;vertical-align: middle;"></i><font>商品分类<i class="fa fa-sort-desc" style="color: #E7E7F3;"></i></font></a></li>
                                    </ul>

                                    <ul class="categories vertical megamenu" id="main-category">
                                        <li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_600"><img src="./image/cate_icon_1.png">母婴专区<i class="fa fa-sort-desc"></i></a>
                                        <div class="wrapper" style=" width:630px">
                                        <div class="row">
                                        <div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class="">
                                        <a href="http://www.dindin.com/search.jsp?classId=945&subClassId=%25E5%25A5%25B6%25E7%25B2%2589%25E8%25BE%2585%25E9%25A3%259F&PTAG=20051.15.1" class="main-menu sec-cate" id="second_945">奶粉辅食</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=951&subClassId=%25E9%2585%258D%25E6%2596%25B9%25E5%25A5%25B6%25E7%25B2%2589&PTAG=20051.15.1" id="951">配方奶粉</a></li><li><a href="http://www.dindin.com/search.jsp?classId=955&subClassId=%25E5%25AD%2595%25E5%25A6%2587%25E5%25A5%25B6%25E7%25B2%2589&PTAG=20051.15.1" id="955">孕妇奶粉</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1161&subClassId=%25E7%25B1%25B3%25E7%25B2%2589/%25E7%25B1%25B3%25E7%25B3%258A&PTAG=20051.15.1" id="1161">米粉/米糊</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1162&subClassId=%25E5%25AE%259D%25E5%25AE%259D%25E9%259B%25B6%25E9%25A3%259F&PTAG=20051.15.1" id="1162">宝宝零食</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1163&subClassId=%25E8%2594%25AC%25E6%259E%259C/%25E8%2582%2589%25E6%25B3%25A5&PTAG=20051.15.1" id="1163">蔬果/肉泥</a></li></ul></li>
                                        <li class=""><a href="http://www.dindin.com/search.jsp?classId=1164&subClassId=%25E6%25AF%258D%25E5%25A9%25B4%25E5%2581%25A5%25E5%25BA%25B7&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1164">母婴健康</a>
                                        <ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1165&subClassId=DHA&PTAG=20051.15.1" id="1165">DHA</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1166&subClassId=%25E7%25BB%25B4%25E7%2594%259F%25E7%25B4%25A0&PTAG=20051.15.1" id="1166">维生素</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1167&subClassId=%25E6%25B7%25B1%25E6%25B5%25B7%25E9%25B1%25BC%25E6%25B2%25B9&PTAG=20051.15.1" id="1167">深海鱼油</a></li></ul></li>
                                        <li class=""><a href="http://www.dindin.com/search.jsp?classId=1169&subClassId=%25E6%25B4%2597%25E6%258A%25A4%25E6%25B8%2585%25E6%25B4%2581&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1169">洗护清洁</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1170&subClassId=%25E6%258A%25A4%25E8%2582%25A4%25E7%2594%25A8%25E5%2593%2581&PTAG=20051.15.1" id="1170">护肤用品</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1171&subClassId=%25E7%2588%25BD%25E8%25BA%25AB%25E9%2598%25B2%25E7%2597%25B1&PTAG=20051.15.1" id="1171">爽身防痱</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1172&subClassId=%25E9%25A9%25B1%25E8%259A%258A/%25E9%25A9%25B1%25E8%2599%25AB&PTAG=20051.15.1" id="1172">驱蚊/驱虫</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1175&subClassId=%25E6%25B4%2597%25E5%258F%2591%25E6%25B2%2590%25E6%25B5%25B4&PTAG=20051.15.1" id="1175">洗发沐浴</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1175&subClassId=%25E6%25B4%2597%25E5%258F%2591%25E6%25B2%2590%25E6%25B5%25B4&PTAG=20051.15.1" id="1177">医药护理</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1178&subClassId=%25E7%2590%2586%25E5%258F%2591/%25E6%258A%25A4%25E5%258F%2591&PTAG=20051.15.1" id="1178">理发/护发</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1184&subClassId=%25E7%2589%2599%25E5%2588%25B7/%25E7%2589%2599%25E8%2586%258F&PTAG=20051.15.1" id="1184">牙刷/牙膏</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1195&subClassId=%25E6%258A%25A4%25E8%2587%2580%25E9%259C%259C&PTAG=20051.15.1" id="1195">护臀霜</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1179&subClassId=%25E5%2593%25BA%25E8%2582%25B2%25E5%2596%2582%25E5%2585%25BB&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1179">哺育喂养</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1180&subClassId=%25E5%25A5%25B6%25E7%2593%25B6/%25E5%25A5%25B6%25E5%2598%25B4&PTAG=20051.15.1" id="1180">奶瓶/奶嘴</a></li>
                                        <li><a href="http://www.dindin.com/search.jsp?classId=1181&subClassId=%25E9%25A4%2590%25E5%2585%25B7&PTAG=20051.15.1" id="1181">餐具</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1183&subClassId=%25E5%25AD%25A6%25E9%25A5%25AE%25E6%259D%25AF/%25E6%25B0%25B4%25E6%259D%25AF&PTAG=20051.15.1" id="1183">学饮杯/水杯</a></li></ul></li>
                                        <li class=""><a href="http://www.dindin.com/search.jsp?classId=1185&subClassId=%25E7%25BA%25B8%25E5%25B0%25BF%25E8%25A3%25A4/%25E6%25B9%25BF%25E5%25B7%25BE&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1185">纸尿裤/湿巾</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1186&subClassId=%25E7%25BA%25B8%25E5%25B0%25BF%25E8%25A3%25A4/%25E6%258B%2589%25E6%258B%2589%25E8%25A3%25A4&PTAG=20051.15.1" id="1186">纸尿裤/拉拉裤</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1187&subClassId=%25E5%25A9%25B4%25E5%2584%25BF%25E6%25B9%25BF%25E5%25B7%25BE%2520&PTAG=20051.15.1" id="1187">婴儿湿巾 </a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_19276.htm"><img src="./image/ct1.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_601"><img src="./image/cate_icon_4.png">全球美食<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px">
                                        <div class="row">
                                        <div class="col-sm-6  mobile-enabled">
                                        <div class="row">
                                        <div class="col-sm-12 hover-menu">
                                        <div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=1026&subClassId=%25E8%25BF%259B%25E5%258F%25A3%25E9%25A5%25AE%25E5%2593%2581&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1026">进口饮品</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1028&subClassId=%25E5%2592%2596%25E5%2595%25A1&PTAG=20051.15.1" id="1028">咖啡</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1029&subClassId=%25E8%258C%25B6&PTAG=20051.15.1" id="1029">茶</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1135&subClassId=%25E9%2585%2592&PTAG=20051.15.1" id="1135">酒</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1536&subClassId=%25E6%2597%25A9%25E9%25A4%2590%25E9%25BA%25A6%25E7%2589%2587&PTAG=20051.15.1" id="1536">早餐麦片</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1537&subClassId=%25E6%259E%259C%25E6%25B1%2581/%25E7%2589%259B%25E4%25B9%25B3&PTAG=20051.15.1" id="1537">果汁/牛乳</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=989&subClassId=%25E8%25BF%259B%25E5%258F%25A3%25E9%259B%25B6%25E9%25A3%259F&PTAG=20051.15.1" class="main-menu sec-cate" id="second_989">进口零食</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1314&subClassId=%25E9%25A5%25BC%25E5%25B9%25B2&PTAG=20051.15.1" id="1314">饼干</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1315&subClassId=%25E7%2589%25B9%25E8%2589%25B2%25E7%25B3%2596%25E6%259E%259C&PTAG=20051.15.1" id="1315">特色糖果</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1316&subClassId=%25E6%25B3%25A1%25E9%259D%25A2&PTAG=20051.15.1" id="1316">泡面</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1317&subClassId=%25E6%259E%259C%25E5%25B9%25B2%25E5%259D%259A%25E6%259E%259C&PTAG=20051.15.1" id="1317">果干坚果</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1318&subClassId=%25E4%25B9%25B3%25E5%2593%2581/%25E8%2582%2589%25E5%25B9%25B2&PTAG=20051.15.1" id="1318">乳品/肉干</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1321&subClassId=%25E5%25B7%25A7%25E5%2585%258B%25E5%258A%259B&PTAG=20051.15.1" id="1321">巧克力</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1322&subClassId=%25E8%2586%25A8%25E5%258C%2596/%25E7%25BD%2590%25E5%25A4%25B4&PTAG=20051.15.1" id="1322">膨化/罐头</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1025&subClassId=%25E7%25B2%25AE%25E6%25B2%25B9%25E8%25B0%2583%25E5%2591%25B3&PTAG=20051.15.1" class="main-menu sec-cate" id="second_1025">粮油调味</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1309&subClassId=%25E8%25BF%259B%25E5%258F%25A3%25E7%25B2%25AE%25E6%25B2%25B9&PTAG=20051.15.1" id="1309">进口粮油</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1310&subClassId=%25E5%2592%2596%25E5%2596%25B1%25E9%2585%25B1&PTAG=20051.15.1" id="1310">咖喱酱</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1312&subClassId=%25E5%2585%25B6%25E5%25AE%2583%25E8%25B0%2583%25E5%2591%25B3%25E9%2585%25B1&PTAG=20051.15.1" id="1312">其它调味酱</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1543&amp;subClassId=%25E9%259D%25A2%25E6%259D%25A1&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1543">面条</a><ul class="three-cate" style="color:#666;padding-left:30px;"></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_18904.htm"><img src="./image/ct2.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_597"><img src="./image/cate_icon_3.png">美妆个护<i class="fa fa-sort-desc"></i></a>
                                        <div class="wrapper" style=" width:630px"><div class="row"><div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=957&amp;subClassId=%25E7%25BE%258E%25E5%25A6%2586%25E7%25B3%25BB%25E5%2588%2597&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_957">美妆系列</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=959&amp;subClassId=%25E5%2594%2587%25E8%2586%258F/%25E5%258F%25A3%25E7%25BA%25A2&amp;PTAG=20051.15.1" id="959">唇膏/口红</a></li><li><a href="http://www.dindin.com/search.jsp?classId=963&amp;subClassId=BB%25E9%259C%259C/CC%25E9%259C%259C&amp;PTAG=20051.15.1" id="963">BB霜/CC霜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1272&amp;subClassId=%25E9%25A6%2599%25E6%25B0%25B4&amp;PTAG=20051.15.1" id="1272">香水</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=967&amp;subClassId=%25E9%259D%25A2%25E9%2583%25A8%25E6%258A%25A4%25E8%2582%25A4&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_967">面部护肤</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=968&amp;subClassId=%25E7%2588%25BD%25E8%2582%25A4%25E6%25B0%25B4&amp;PTAG=20051.15.1" id="968">爽肤水</a></li><li><a href="http://www.dindin.com/search.jsp?classId=969&amp;subClassId=%25E4%25B9%25B3%25E6%25B6%25B2&amp;PTAG=20051.15.1" id="969">乳液</a></li><li><a href="http://www.dindin.com/search.jsp?classId=971&amp;subClassId=%25E9%2598%25B2%25E6%2599%2592%25E9%259C%259C&amp;PTAG=20051.15.1" id="971">防晒霜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=972&amp;subClassId=%25E9%259D%25A2%25E9%259C%259C&amp;PTAG=20051.15.1" id="972">面霜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1159&amp;subClassId=%25E9%259D%25A2%25E8%2586%259C&amp;PTAG=20051.15.1" id="1159">面膜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1237&amp;subClassId=%25E7%259C%25BC%25E9%259C%259C&amp;PTAG=20051.15.1" id="1237">眼霜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1238&amp;subClassId=%25E6%25B4%2581%25E9%259D%25A2/%25E5%258D%25B8%25E5%25A6%2586&amp;PTAG=20051.15.1" id="1238">洁面/卸妆</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1239&amp;subClassId=%25E7%25B2%25BE%25E5%258D%258E&amp;PTAG=20051.15.1" id="1239">精华</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1240&amp;subClassId=%25E7%259C%25BC%25E8%2586%259C&amp;PTAG=20051.15.1" id="1240">眼膜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1241&amp;subClassId=%25E6%258A%25A4%25E8%2582%25A4%25E5%25A5%2597%25E8%25A3%2585&amp;PTAG=20051.15.1" id="1241">护肤套装</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1549&amp;subClassId=%25E7%259C%25BC%25E9%2583%25A8%25E7%25B2%25BE%25E5%258D%258E&amp;PTAG=20051.15.1" id="1549">眼部精华</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1156&amp;subClassId=%25E5%25A5%25B3%25E5%25A3%25AB%25E6%258A%25A4%25E7%2590%2586&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1156">女士护理</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1157&amp;subClassId=%25E5%258D%25AB%25E7%2594%259F%25E5%25B7%25BE&amp;PTAG=20051.15.1" id="1157">卫生巾</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1158&amp;subClassId=%25E5%258D%25AB%25E7%2594%259F%25E6%258A%25A4%25E5%259E%25AB&amp;PTAG=20051.15.1" id="1158">卫生护垫</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1243&amp;subClassId=%25E8%25BA%25AB%25E4%25BD%2593%25E6%258A%25A4%25E7%2590%2586&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1243">身体护理</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1244&amp;subClassId=%25E8%25BA%25AB%25E4%25BD%2593%25E4%25B9%25B3/%25E5%2596%25B7%25E9%259B%25BE&amp;PTAG=20051.15.1" id="1244">身体乳/喷雾</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1245&amp;subClassId=%25E6%2589%258B%25E8%25B6%25B3%25E6%258A%25A4%25E7%2590%2586&amp;PTAG=20051.15.1" id="1245">手足护理</a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_18608.htm"><img src="./image/ct3.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_1323"><img src="./image/cate_icon_5.png">营养健康<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px"><div class="row"><div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=1335&amp;subClassId=%25E8%2590%25A5%25E5%2585%25BB%25E6%2588%2590%25E5%2588%2586&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1335">营养成分</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1336&amp;subClassId=%25E7%25BB%25B4%25E7%2594%259F%25E7%25B4%25A0/%25E7%259F%25BF%25E7%2589%25A9%25E8%25B4%25A8&amp;PTAG=20051.15.1" id="1336">维生素/矿物质</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1337&amp;subClassId=%25E9%25B1%25BC%25E6%25B2%25B9&amp;PTAG=20051.15.1" id="1337">鱼油</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1338&amp;subClassId=%25E6%2588%2590%25E4%25BA%25BA%25E5%25A5%25B6%25E7%25B2%2589&amp;PTAG=20051.15.1" id="1338">成人奶粉</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1341&amp;subClassId=%25E6%25BB%258B%25E8%25A1%25A5%25E7%2587%2595%25E7%25AA%259D&amp;PTAG=20051.15.1" id="1341">滋补燕窝</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1342&amp;subClassId=%25E8%259C%2582%25E8%2583%25B6/%25E8%259C%2582%25E8%259C%259C&amp;PTAG=20051.15.1" id="1342">蜂胶/蜂蜜</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1343&amp;subClassId=%25E8%2583%25B6%25E5%258E%259F%25E8%259B%258B%25E7%2599%25BD&amp;PTAG=20051.15.1" id="1343">胶原蛋白</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1346&amp;subClassId=%25E8%2591%25A1%25E8%2590%2584%25E7%25B1%25BD&amp;PTAG=20051.15.1" id="1346">葡萄籽</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1354&amp;subClassId=%25E5%2581%25A5%25E5%25BA%25B7%25E5%258A%259F%25E6%2595%2588&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1354">健康功效</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1356&amp;subClassId=%25E5%2585%25B3%25E8%258A%2582%25E5%2585%25BB%25E6%258A%25A4&amp;PTAG=20051.15.1" id="1356">关节养护</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1357&amp;subClassId=%25E5%25A2%259E%25E5%25BC%25BA%25E5%2585%258D%25E7%2596%25AB&amp;PTAG=20051.15.1" id="1357">增强免疫</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1360&amp;subClassId=%25E8%2582%25A0%25E8%2583%2583%25E5%2585%25BB%25E6%258A%25A4&amp;PTAG=20051.15.1" id="1360">肠胃养护</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1361&amp;subClassId=%25E5%2587%258F%25E8%2582%25A5%25E7%2598%25A6%25E8%25BA%25AB&amp;PTAG=20051.15.1" id="1361">减肥瘦身</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1364&amp;subClassId=%25E5%25BF%2583%25E8%2584%2591%25E5%2581%25A5%25E5%25BA%25B7&amp;PTAG=20051.15.1" id="1364">心脑健康</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1365&amp;subClassId=%25E6%258A%2597%25E8%25A1%25B0%25E8%2580%2581/%25E6%258A%2597%25E6%25B0%25A7%25E5%258C%2596&amp;PTAG=20051.15.1" id="1365">抗衰老/抗氧化</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1367&amp;subClassId=%25E8%25B0%2583%25E8%258A%2582%25E5%2586%2585%25E5%2588%2586%25E6%25B3%258C&amp;PTAG=20051.15.1" id="1367">调节内分泌</a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_18607.htm"><img src="./image/ct4.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_1208"><img src="./image/cate_icon_2.png">家居生活<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px"><div class="row"><div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=1209&amp;subClassId=%25E5%25B1%2585%25E5%25AE%25B6%25E6%2597%25A5%25E7%2594%25A8&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1209">居家日用</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1286&amp;subClassId=%25E9%25A9%25B1%25E8%259A%258A%25E9%25A9%25B1%25E8%2599%25AB&amp;PTAG=20051.15.1" id="1286">驱蚊驱虫</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1292&amp;subClassId=%25E7%259C%25BC%25E7%25BD%25A9%25E5%258F%25A3%25E7%25BD%25A9&amp;PTAG=20051.15.1" id="1292">眼罩口罩</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1305&amp;subClassId=%25E8%25A1%25A3%25E6%259C%258D%25E6%25B8%2585%25E6%25B4%2581&amp;PTAG=20051.15.1" id="1305">衣服清洁</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1306&amp;subClassId=%25E5%25AE%25B6%25E5%25BA%25AD%25E6%25B8%2585%25E6%25B4%2581%25E5%2589%2582&amp;PTAG=20051.15.1" id="1306">家庭清洁剂</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1252&amp;subClassId=%25E6%25B4%2597%25E5%258F%2591%25E6%258A%25A4%25E5%258F%2591&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1252">洗发护发</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1253&amp;subClassId=%25E6%25B4%2597%25E5%258F%2591%25E6%25B0%25B4&amp;PTAG=20051.15.1" id="1253">洗发水</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1254&amp;subClassId=%25E6%258A%25A4%25E5%258F%2591%25E7%25B4%25A0&amp;PTAG=20051.15.1" id="1254">护发素</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1255&amp;subClassId=%25E6%25B4%2597%25E6%258A%25A4%25E5%258F%2591%25E5%25A5%2597%25E8%25A3%2585&amp;PTAG=20051.15.1" id="1255">洗护发套装</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1260&amp;subClassId=%25E6%25B2%2590%25E6%25B5%25B4%25E6%25B8%2585%25E6%25B4%2581&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1260">沐浴清洁</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1261&amp;subClassId=%25E6%25B2%2590%25E6%25B5%25B4%25E9%259C%25B2&amp;PTAG=20051.15.1" id="1261">沐浴露</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1264&amp;subClassId=%25E5%258F%25A3%25E8%2585%2594%25E6%258A%25A4%25E7%2590%2586&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1264">口腔护理</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1266&amp;subClassId=%25E7%2589%2599%25E5%2588%25B7/%25E7%2589%2599%25E7%25BA%25BF&amp;PTAG=20051.15.1" id="1266">牙刷/牙线</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1267&amp;subClassId=%25E7%2589%2599%25E8%2586%258F/%25E7%2589%2599%25E7%25B2%2589&amp;PTAG=20051.15.1" id="1267">牙膏/牙粉</a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_18504.htm"><img src="./image/ct5.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_614"><img src="./image/cate_icon_7.png">饰品手表<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px"><div class="row"><div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=1023&amp;subClassId=%25E6%2597%25B6%25E5%25B0%259A%25E8%2585%2595%25E8%25A1%25A8&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1023">时尚腕表</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1144&amp;subClassId=%25E7%25B2%25BE%25E8%2587%25B4%25E5%25A5%25B3%25E8%25A1%25A8&amp;PTAG=20051.15.1" id="1144">精致女表</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1145&amp;subClassId=%25E8%2587%25B3%25E5%25B0%258A%25E7%2594%25B7%25E8%25A1%25A8&amp;PTAG=20051.15.1" id="1145">至尊男表</a></li></ul></li><li class=""><a href="http://www.dindin.com/search.jsp?classId=1031&amp;subClassId=%25E6%25B5%2581%25E8%25A1%258C%25E9%25A5%25B0%25E5%2593%2581&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1031">流行饰品</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1032&amp;subClassId=%25E6%2589%258B%25E9%2593%25BE/%25E6%2589%258B%25E9%2595%25AF&amp;PTAG=20051.15.1" id="1032">手链/手镯</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1033&amp;subClassId=%25E8%2580%25B3%25E7%258E%25AF&amp;PTAG=20051.15.1" id="1033">耳环</a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_18605.htm"><img src="./image/ct6.jpg" width="100%"></a></div></div></div></li><li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_1368"><img src="./image/cate_icon_6.png">奢侈品<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px"><div class="row"><div class="col-sm-6  mobile-enabled"><div class="row"><div class="col-sm-12 hover-menu"><div class="menu"><ul><li class=""><a href="http://www.dindin.com/search.jsp?classId=1369&amp;subClassId=%25E6%25BD%25AE%25E6%25B5%2581%25E7%25AE%25B1%25E5%258C%2585&amp;PTAG=20051.15.1" class="main-menu sec-cate" id="second_1369">潮流箱包</a><ul class="three-cate" style="color:#666;padding-left:30px;"><li><a href="http://www.dindin.com/search.jsp?classId=1370&amp;subClassId=%25E6%2596%259C%25E6%258C%258E%25E5%258C%2585&amp;PTAG=20051.15.1" id="1370">斜挎包</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1371&amp;subClassId=%25E5%258F%258C%25E8%2582%25A9%25E5%258C%2585&amp;PTAG=20051.15.1" id="1371">双肩包</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1372&amp;subClassId=%25E6%2589%258B%25E6%258F%2590/%25E6%2589%258B%25E6%258B%25BF%25E5%258C%2585&amp;PTAG=20051.15.1" id="1372">手提/手拿包</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1375&amp;subClassId=%25E9%2592%25B1%25E5%258C%2585/%25E5%258D%25A1%25E5%258C%2585&amp;PTAG=20051.15.1" id="1375">钱包/卡包</a></li><li><a href="http://www.dindin.com/search.jsp?classId=1538&amp;subClassId=%25E5%258D%2595%25E8%2582%25A9%25E5%258C%2585&amp;PTAG=20051.15.1" id="1538">单肩包</a></li></ul></li></ul></div></div></div></div><div class="col-sm-6  mobile-enabled"><a href="http://www.dindin.com/goodsDetail_id_19154.htm"><img src="./image/ct7.jpg" width="100%"></a></div></div></div></li></ul>
                                    <script type="text/javascript">


                                    </script>
                                </div>
                            </div>

                            <div class="menu_cell menu_holder fill">
                                <a class="mini_menu_trigger visible-xs visible-sm"><i class="fa fa-list"></i></a>
                                <div class="main_menu links">

                                    <ul class="links_holder horizontal  white_color" id="top-menu" style="display: none;">
                                        <li class="has-sub top">
                                            <a href="?index">首页</a>

                                        </li>

                                        <li class="has-sub top" id="allCountry">
                                            <a href="javascript:;" class="sub_trigger">全部国家<i class="fa fa-sort-desc"></i></a>
                                            <ul style="padding: 13px 15px 5px; overflow: hidden; width: 882px; margin-left: -92px;">
                                                <li>
                                                    <div class="wrapper">
                                                        <div class="row">
                                                            <div class="col-sm-3  mobile-enabled">
                                                                <div class="row">
                                                                    <div class="col-sm-12 hover-menu">
                                                                        <div class="menu">
                                                                            <ul class="ul1" style="position:static;border:none;width:100%;">
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7300101" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/baxi.jpg">&nbsp;&nbsp;巴西</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7200102" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/deguo.jpg">&nbsp;&nbsp;德国</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7200103" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/faguo.jpg">&nbsp;&nbsp;法国</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7200104" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/helan.jpg">&nbsp;&nbsp;荷兰</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7100102" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/hanguo.jpg">&nbsp;&nbsp;韩国</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7400102" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/meiguo.jpg">&nbsp;&nbsp;美国</a></li>
                                                                            </ul>
                                                                        </div></div></div></div>
                                                            <div class="col-sm-3  mobile-enabled">
                                                                <div class="row">
                                                                    <div class="col-sm-12 hover-menu">
                                                                        <div class="menu">
                                                                            <ul class="ul1" style="position:static;border:none;width:100%;">
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7100201" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/malaixiya.jpg">&nbsp;&nbsp;马来西亚</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7100202" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/taiguo.jpg">&nbsp;&nbsp;泰国</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7200101" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/yingguo.jpg">&nbsp;&nbsp;英国</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7200105" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/yidali.jpg">&nbsp;&nbsp;意大利</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7500101" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/aodaliya.jpg?1">&nbsp;&nbsp;澳大利亚</a></li>
                                                                                <li><a href="http://www.dindin.com/all_country.jsp?ycdId=7100302" class="main-menu with-submenu" style="padding-left:5px;"><img src="./image/hongkong.jpg">&nbsp;&nbsp;中国香港</a></li>
                                                                            </ul>
                                                                        </div></div></div></div>
                                                            <div class="col-sm-6" style="padding-top: 20px;">
                                                                <a href="http://www.dindin.com/shop/shop_id_587.htm"><img src="./image/country-img-587.jpg"></a>
                                                            </div>
                                                        </div></div></li>

                                            </ul>
                                        </li>

                                        <li class="has-sub top" id="hotSaleLi">
                                            <a href="http://www.dindin.com/hotSaleAction!newFindHotSaleGoods.go">畅销排行</a>
                                        </li>

                                        <li class="has-sub top active_select" id="seckillLi">
                                            <a href="http://www.dindin.com/seckillAction!findxgGood.go">限时抢购<img src="./image/lower.png"><img class="seckill_hot" src="./image/seckill_hot.png"></a>
                                        </li>







                                        <!--<li class="top" id="malai"><a href="http://www.dindin.com/all_country.jsp?ycdId=7500101">澳大利亚</a></li>-->
                                        <li class="top" id="malai"><a href="http://www.dindin.com/all_country.jsp?ycdId=7100201">马来西亚</a></li>
                                        <li class="top" id="pifa"><a href="http://www.dindin.com/b2b/index.html">叮叮批发<img src="./image/pifa_1.gif"></a></li>
                                        <li class="top" id="legou"><a target="_blank" href="http://www.dindin.com/shop/shop_id_318.htm">美国直邮</a></li>
                                        <!--  <li class="top" id="mother"><a href="./mother_channel.jsp">母婴</a></li>
                                        <li class="top" id="caizhuang"><a href="./cosmetics.jsp">美妆</a></li>
                                        <li class="top" id="luxury"><a href="./luxury.jsp">奢侈品</a></li>
                                        <li class="top" id="care"><a href="./care.jsp">美食保健</a></li>
                                    -->
                                        <!-- foreach sections ends -->
                                    </ul>
                                    <!-- if sections ends -->      </div>
                            </div>

                            <div class="menu_cell right nowrap">

                                <div id="cart">
                                    <a href="?cart/index" id="cart-total">
                                        <div class="mini_cart hover_icon">
                                            <div class="cart_holder">
                                                <i class="shopping-cart"></i>
                                                购物车&nbsp;&nbsp;<i class="fa fa-sort-desc"></i>&nbsp;
                                                <span class="count" style="background: #90ba13!important;">0</span></div>
                                        </div>
                                    </a>
                                    <!--      <div class="empty-cart" style="display:none;">-->
                                    <!--      	<p>您的购物车是空的！</p>-->
                                    <!--      </div>-->
                                </div>      </div>

                        </div>
                    </div>
                </div>
</div>
</div>
<!-- menu_wrapper ends --></div>
<!-- <div class="menu_wrapper"></div> -->
<script type="text/javascript">
isNextObj = false; //是否跳到下一步标识
//登录
$('#popup_login1').click(function(){
  isNextObj = false;
      //$('.loginbox .username').focus();
      $('.loginbox').fadeIn();
      $('#cboxOverlay').css('opacity','0.9').fadeIn('slow');
  })
$('.v_menu_trigger').click(function() {
    $('.menu_wrapper .mini_menu_trigger').removeClass('active');
    $('.menu_wrapper ul.links_holder').hide();
    $(this).toggleClass('active')
        //$('.menu_wrapper ul.categories').toggleClass('active');
    });
// Mobile Mini Menu  //
$('.mini_menu_trigger').click(function() {
    $('.v_menu_trigger').removeClass('active');
    $('ul.categories').removeClass('active');
    $('ul.links_holder').slideToggle(500);
    $('.mini_menu_trigger').toggleClass('active');
});           
$('.header .sub_trigger, .header_top_line_wrapper .sub_trigger').click(function(e) {
    if ($(window).width() < 767) {
        e.preventDefault();
        $(this).parent().find('>ul').stop(true, true).slideToggle(350)
        .end().siblings().find('>ul').slideUp(350);
    }
});
$('.main_menu .sub_trigger').click(function(e) {
    if ($(window).width() < 991) {
        e.preventDefault();
        $(this).parent().find('>ul').stop(true, true).slideToggle(350)
        .end().siblings().find('>ul').slideUp(350);
    }
});
$('ul.categories .sub_trigger').click(function(e) {
    if ($(window).width() < 991) {
        e.preventDefault();
        $(this).parent().find('>ul').stop(true, true).slideToggle(350)
        .end().siblings().find('>ul').slideUp(350);
        $(this).parent().find('.wrapper').stop(true, true).slideToggle(350)
        .end().siblings().find('.wrapper').slideUp(350);
    }
});
// Sticky menu
var menu_to_top = $('.menu_wrapper').offset().top;
var stickymenu = function(){
    var window_to_top = $(window).scrollTop();
    if (window_to_top > menu_to_top) { 
        $('.sticky').addClass('active');
    } else {
        $('.sticky').removeClass('active'); 
    }};
    $(window).scroll(function() {
        stickymenu();
    });
    
   $('#user_nick').click(function(){
    var flag = userLogin.isLogin();
    if(!flag){
         $('#changeimg_link').click();//弹出框之前刷新验证码
         alert("您尚未登录,请登录！");
         userLogin.loginAlert({action:function(){
            location.reload(true);
        }}
        );
     }else{
        location.href="?memberIndex/view";
    }


})
</script>
<!-- 登录弹框 -->
<div id="colorbox" class="login loginbox" role="dialog" tabindex="-1" style="">
    <div id="cboxWrapper" style="min-height: 319px; max-width: 370px;width:100%;">
        <div>
            <div id="cboxTopLeft" style="float: left;"></div>
            <div id="cboxTopCenter" style="float: left; max-width: 370px;"></div>
            <div id="cboxTopRight" style="float: left;"></div>
        </div>
        <div style="clear: left;">
            <div id="cboxMiddleLeft" style="float: left; min-height: 319px;"></div>
            <div id="cboxContent" style="float: left; max-width: 370px; min-height: 319px;width:100%;">
                <div id="cboxLoadedContent" style="max-width: 370px; overflow: auto; min-height: 319px;">
                    <div class="popup_login_holder">
                        <div class="top">
                            <div class="heading">
                                <a href="?/user/register" class="btn btn-default pull-right regis">注册</a>
                                <h2>登录</h2> 
                            </div>
                            <form action="/loginAction!login.go" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="error-message" style="color:red;margin-bottom:8px;"></div>
                                    <label class="control-label">手机号或邮箱</label>
                                    <input type="text" name="u" class="form-control username" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">密码</label>
                                    <input type="password" name="p" class="form-control userpwd" value="">
                                    <!--<img class="safe-info" src="./image/safe_1.png" style="display:none;"/>-->
                                </div>
                                <input id="login-button" type="button" value="登录" class="btn btn-primary">

                            </form>
                        </div>
                    </div></div><div id="cboxTitle" style="float: left; display: block;"></div><div id="cboxCurrent" style="float: left; display: none;"></div><a id="cboxPrevious" style="display: none;"></a><a id="cboxNext" style="display: none;"></a><button id="cboxSlideshow" style="display: none;"></button><div id="cboxLoadingOverlay" style="float: left; display: none;"></div><div id="cboxLoadingGraphic" style="float: left; display: none;"></div><div id="cboxClose" class="loginclose">close</div></div><div id="cboxMiddleRight" style="float: left; height: 390px;"></div></div><div style="clear: left;"><div id="cboxBottomLeft" style="float: left;"></div><div id="cboxBottomCenter" style="float: left; width: 370px;"></div><div id="cboxBottomRight" style="float: left;"></div></div></div><div style="position: absolute; width: 9999px; visibility: hidden; max-width: none; display: none;"></div></div>
                    <script type="text/javascript" src="./js/login.js"></script>
                    <script type="text/javascript">
                        $(function(){
                            $("#search_input").bind('keydown', function(ev) {
                                try {
                                    if(ev.keyCode == 13) {
                                        gotoSearch();
                                    }
                                }
                                catch(e) {}
                            });
                            $(document).bind('keydown', function(ev) {
                                try {
                                    if(ev.keyCode == 13) {
                                        var verifycode =$.trim($("#verifycode").val());
                                        if(verifycode!=null && verifycode!=""){
                                            login();
                                        }
                                    }
                                }
                                catch(e) {}
                            });
                        })
//isNextObj = '';

 //登录事件
 $('#login-button').click(function(){
    login();
})
 function login(){
    if($('.username').val()!=''&&$('.userpwd').val()!=''&&$('.verifycode').val()!=''){
        userLogin.getLogin({username:$('.username').val(),userpwd:$('.userpwd').val(),verifycode:$('.verifycode').val()});
        //$('.error-message').text('');
    }else{
        $('.error-message').text('各项不能为空');
        $('#changeimg_link').click();
    }
}
$('.username,.userpwd,.verifycode').blur(function(){
    if($(this).val()==''||$(this).val()==null){
        $(this).addClass('error-msg');
    }else{
        $(this).removeClass('error-msg');
    }
})
$('.username').blur(function(){
    var tex = $(this).val();
    if(tex!=''){
        if(!isNaN(tex)){ //表示是全数字
            //if(!(/^1[3|4|5|8][0-9]\d{4,9}$/.test(tex))){ //如果不是手机,则表示是QQ号
                if(tex.length>12){ 
                    $('.error-message').text('请输入正确的叮叮号');
                }else{
                    $('.error-message').text('');
                }
            //}else{
                //alert(0)
                //if(tex.length!=11){ 
                    //alert('请输入正确的手机号');
                //}
            //}
        }else{//email
            var str =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!str.test(tex)){
                $('.error-message').text('请输入正确的叮叮号');
            }else{
                $('.error-message').text('');
            }
        }
    }
})
$('.userpwd').blur(function(){
    var pwd = $(this).val();
    if(pwd!=''){
        if(pwd.length<6){
            $('.error-message').text('密码长度不够');
        }
//      else if(pwd.length<10){
//          $('.safe-info').attr('src','./image/safe_1.png').show();
//          $('.error-message').text('');
//      }else if(pwd.length<15){
//          $('.safe-info').attr('src','./image/safe_2.png').show();
//          $('.error-message').text('');
//      }else if(pwd.length<50){
//          $('.safe-info').attr('src','./image/safe_3.png').show();
//          $('.error-message').text('');
//      }
else{
    $('.error-message').text('');
}
}
})
//刷新验证码
$('#changeimg_link').click(function(){
    $('#imgVerify').attr('src','./createParityImage.html?'+Math.random());
})
$('#loginout').click(function(){
    //isNextObj = true;
    //isNextObj = true;
    userLogin.logout();
    //var u = new userLogin();
    //u.isNext = true;
    //userLogin.isNext = true;
    //userLogin.loginAlert();
})
$('.goodsname').each(function(){
    var goodname = $(this).text();
    if(goodname.length>30){
        $(this).text(goodname.substr(0,30)+'...');
    }
})
// Sticky header
var header_to_top = $('.row.header .logo').offset().top;
var stickyheader = function(){
    var window_to_top = $(window).scrollTop();
    if (window_to_top > header_to_top) { 
        $('.header_sticky').addClass('active');
    } else {
        $('.header_sticky').removeClass('active'); 
    }};
    $(window).scroll(function() {
        stickyheader();
    });
    function gotoSearch(){
        var val = $.trim($("#search_input").val());
        if(val!=null && val!=""){
            val = encodeURI(encodeURI(val));
            window.location="http://www.dindin.com/search.jsp?q_show="+val+"&q="+val+"&PTAG=20051.15.1";
        }
    }
</script>
<style type="text/css">
    #colorbox1 .product img {
        float: left;
        margin-right: 20px;
    }
    #colorbox1 .cart_notification .product {
        padding: 19px 20px;
        display: table;
        width: 100%;
    }
    #colorbox1 .cart_notification .bottom {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
    }
    #colorbox1 .bottom {
        padding: 20px 19px;
        border-top: 1px solid #eaeaea;
    }
    #colorbox1{visibility: visible;
        top: 50%;
        left: 50%;
        position: fixed;
        width: 384px;
        height: 200px;
        z-index: 10000;
        margin-left: -150px;
        margin-top: -100px;
        display: none;}
        #colorbox1 #cboxWrapper{width:100%;}
        #colorbox1 #cboxContent{height:200px;}
        @media screen and (max-width:380px){
            #colorbox1 {width:310px;margin-left:-155px;}
        }
        #colorbox{}
</style>
    <div id="colorbox1" class="login" role="dialog" tabindex="-1" style="display: none;">
        <div id="cboxWrapper">
            <div>
                <div id="cboxTopLeft" style="float: left;"></div>
                <div id="cboxTopCenter" style="float: left; width: 400px;"></div>
                <div id="cboxTopRight" style="float: left;"></div>
            </div>
            <div style="clear: left;">
                <div id="cboxContent">
                    <div id="cboxLoadedContent">
                        <div class="cart_notification">
                            <div class="product">
                                <img src="./image/dindin-logo.png" width="80" style="height:80px!important;">
                                <span>您已成功添加 到您的 <a href="./shop/gouwuche.html" target="_blank">购物车</a>!</span>
                            </div>
                            <div class="bottom success" style="text-align: center;">
                                <a class="btn btn-primary" href="./shop/gouwuche.html" onclick="$(&#39;#colorbox1&#39;).hide();$(&#39;#cboxOverlay&#39;).fadeOut(&#39;slow&#39;);" target="_blank">去购物车</a>
                                <a class="btn btn-primary" onclick="$(&#39;#colorbox1&#39;).hide();$(&#39;#cboxOverlay&#39;).fadeOut(&#39;slow&#39;);">继续购物</a>
                            </div>
                            <div class="bottom same" style="display:none;text-align: center;">
                                <a class="btn btn-primary" href="javascript:$(&#39;#colorbox1&#39;).hide();$(&#39;#cboxOverlay&#39;).fadeOut(&#39;slow&#39;);">不要添加</a>
                                <a class="btn btn-primary" href="./shop/gouwuche.html" onclick="$(&#39;#colorbox1&#39;).hide();$(&#39;#cboxOverlay&#39;).fadeOut(&#39;slow&#39;);" target="_blank">去购物车</a>
                            </div>
                            <div class="bottom gotodetail abc" style="display:none;text-align: center;">
                                <a class="btn btn-primary" href="./index.html">去宝贝详情页面</a>
                                <a class="btn btn-primary" onclick="$(&#39;#colorbox1&#39;).hide();$(&#39;#cboxOverlay&#39;).fadeOut(&#39;slow&#39;);">继续购物</a>
                            </div>
                        </div>
                    </div>
                    <div id="cboxTitle" style="float: left; display: block;"></div>
                    <div id="cboxCurrent" style="float: left; display: none;"></div>
                    <a id="cboxPrevious" style="display: none;"></a>
                    <a id="cboxNext" style="display: none;"></a>
                    <button id="cboxSlideshow" style="display: none;"></button>
                    <div id="cboxLoadingOverlay" style="float: left; display: none;"></div>
                    <div id="cboxLoadingGraphic" style="float: left; display: none;"></div>
                    <div id="cboxClose" class="loginclose">close</div>
                </div>
            </div>
            <div style="clear: left;">
                <div id="cboxBottomLeft" style="float: left;"></div>
                <div id="cboxBottomCenter" style="float: left; width: 400px;"></div>
                <div id="cboxBottomRight" style="float: left;"></div>
            </div>
        </div>
        <div style="position: absolute; width: 9999px; visibility: hidden; max-width: none; display: none;"></div>
    </div>
    <script type="text/javascript" src="./js/load_data.js"></script>
    
    <script>
       $('.loginclose').click(function(){
        $('.loginbox').fadeOut('slow');
        $('#cboxOverlay').fadeOut('slow');
        $('#colorbox1').fadeOut('slow');
    //$('#cboxOverlay').fadeOut('slow');
})
</script>

<div class="breadcrumb_wrapper container" style="display: none;"></div>
<div id="notification" class="container"></div>
<script type="text/javascript">
    $("li.home").addClass("current");
    $(".breadcrumb_wrapper").hide();
</script>
