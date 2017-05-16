<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>叮叮网——零门槛、零费用、三分钟快速搞定移动应用</title>
    <style type="text/css" >
        html{color: #333;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;-webkit-tap-highlight-color: rgba(0,0,0,0); background:#fff; font-family:"Lucida Grande", Verdana, "Microsoft Yahei", STXihei, hei;}
        body{font-family:"Lucida Grande", Verdana, "Microsoft Yahei", STXihei, hei; background:#fff;}
        body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td, hr, button, article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {margin: 0;padding: 0}
        em,dfn{font-style:normal}
        ul{list-style:none;}
        li,dl,dt{list-style:none}
        img{border:0;}
        i{ font-style:normal;}
        em{font-style:normal}
        strong{ font-weight:bold; font-weight:700;}
        input{ font-size:12px; vertical-align:middle; outline:none;}
        table{border-collapse:collapse;border-spacing:0}
        p{word-wrap:break-word}
        a:link, a:visited {text-decoration: none;}
        a{text-decoration:none;blr:expression_r(this.onFocus=this.blur())}
        a{outline:none;blr:expression(this.onFocus=this.blur());}
        .header {padding: 4px 0;background: #EFEFEF;text-align: center;}
        .header img{height: 42px;}
        .app-info{text-align:left; margin:10px 30px;line-height:1.5;}
        .app-name{text-align:center; margin:20px 20px 0 20px;}
        .app-logo{text-align:center; margin:40px 20px 0 20px;}
        .app-logo img{width: 35%;min-width: 120px;max-width: 240px;}
        .qrcode{text-align:center; margin:20px 20px 0 20px;font-size:10px;line-height:1.5;}
        .qrcode img{width: 50%;min-width: 180px;max-width: 360px;}
        .down-button{width:100%; text-align:center;}
        .down-button p{display:block; padding-top:20px;height:52px; }
        .down-button a{display:inline-block; width:250px; height:52px; background-size:250px; height:52px;}
        .down-button a.button-android{display:inline-block; width:250px; height:52px;  background:url(<?php echo $img_url;?>/imgs/android-button.png) no-repeat; background-size:250px; height:52px;}
        .down-button a.button-appStore{display:inline-block; width:250px; height:52px;  background:url(<?php echo $img_url;?>/imgs/iPhone-button.png) no-repeat; background-size:250px; height:52px;}
        .down-button a.button-h5{display:inline-block; width:250px; height:52px;  background:url(<?php echo $img_url;?>/imgs/h5-button.png) no-repeat; background-size:250px; height:52px;}
        .down-footer{position:fixed; bottom:0px; width:100%;}
        .down-footer img{width:100%;}
    </style>
    <script type="text/javascript">
        var display = function (id, display) {
            window.scrollTo(0,0);
            document.getElementById(id).style.display = display;
        };
        
        var appCommon = {
            isIOS: function () {
                var u = navigator.userAgent;
                var app = navigator.appVersion;
                return !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
            },

            isAndroid: function () {
                var u = navigator.userAgent;
                var app = navigator.appVersion;
                return u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
            }
        };
        
        var init = function () {
            if (appCommon.isAndroid()) {
                display('actionsAndroid', '');
            } else if (appCommon.isIOS()) {
                display('actionsAppStore', '');
            } 
        };
        
        function isWeiXin() {
            var ua = window.navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                return true;
            } else {
                return false;
            }
        }

        function downloadbyAndroid() {
            if (isWeiXin()) {
                display('divWeChat', 'inline');
                return false;
            }
            return true;
        }

        function downloadbyIPhoneAppStore() {
            if (isWeiXin()) {
                display('divWeChat', 'inline');
                return false;
            }
            return true;
        }

    </script>
</head>
<body>
    <div class="header">
        <a href="http://www.dindin.com/?fromDownload" target="_blank"><img src="<?php echo $img_url;?>/imgs/header-logo.png"></a>
    </div>
    <div class="app-logo">
        <img src="<?php echo $appData['logo'];?>">
    </div>
    <div class="app-name">
        <?php echo $appData['name'];?>
    </div>
    <div class="down-button">
        <?php 
            if($appData['android_url']){
        ?>
        <p id="actionsAndroid" style="display: none;">
            <a class="button-android" onclick="return downloadbyAndroid();" href="<?php echo $appData['android_url'];?>">
            </a>
        </p>
        <?php 
        }
            if($appData['ios_url']){
        ?>
        <p id="actionsAppStore" style="display: none;">
            <a onclick="return downloadbyIPhoneAppStore();" href="<?php echo $appData['ios_url'];?>" class="button-appStore"></a>
        </p>
        <?php    
        }
        ?>
        <p id="actionsH5" >
            <a href="http://<?php echo $appDomain;?>/" class="button-h5"></a>
        </p>
    </div>
    <?php 
        if($appData['qrcode_url']){
    ?>
    <div class="qrcode">
        ↓请关注我们的微信公众号↓<br>
        <img src="<?php echo $appData['qrcode_url'];?>">
    </div>
    <?php    
        }
        if($appData['description']){
    ?>
    <div class="app-info">
        简介：<br>
        &nbsp; &nbsp;&nbsp; &nbsp;<?php echo $appData['description'];?>
    </div>
    <?php    
        }
    ?>
    <div id="divWeChat" onclick="display('divWeChat', 'none');" style="background-color: Black; display: none; filter: Alpha(opacity=80); opacity: 0.8; position: absolute; top: 0; z-index: 10000; width: 100%; height: 100%; text-align: center; padding-top: 20px;">
        <img src="<?php echo $img_url;?>/imgs/down_weixin_tishi.png" />
    </div>
    <script type="text/javascript">
        init();
    </script>
</body>
</html>
 