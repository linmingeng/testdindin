<!DOCTYPE html>
<!-- saved from url=(0038)http://www.dindin.com/personalInfo.jsp -->
<html dir="ltr" lang="en"><!--<![endif]--><head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<script type="text/javascript" charset="utf-8" src="./js/contains.js"></script>
<script type="text/javascript" async="" src="./js/mv.js"></script>
<script type="text/javascript" async="" src="./js/mba.js"></script>
<script type="text/javascript" charset="utf-8" src="./js/taskMgr.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/views.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>个人资料</title>
<!--<base href="http://velikorodnov.com/opencart/shopme/demo6/">-->
<script charset="utf-8" async="" src="./js/i.js" id="_da"></script><!--<base href=".">-->
<base href=".">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
<!-- Version 2.0.3 -->
<!-- <script id="facebook-jssdk" 

src="http://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.3&appId=109031762530738"></script> -->
<script type="text/javascript" async="" src="./js/mvl.js"></script>
<script src="./js/hm.js"></script>
<script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>

<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./css/responsive.css">

<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?

family=Roboto:300,400,700,900" media="screen"> -->
<link rel="stylesheet" href="./css/owl.carousel.min.css">
<link rel="stylesheet" href="./css/owl.theme.default.min.css">
<script src="./js/jquery.matchHeight.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./js/shopme_common.js"></script>

<script type="text/javascript" src="./js/tweetfeed.min.js"></script>
<script type="text/javascript" src="./js/owl.carousel.js"></script>
<!-- Custom css -->
<!-- Custom script -->
<link rel="stylesheet" type="text/css" href="./css/webuploader.css">
<link rel="stylesheet" type="text/css" href="./css/diyUpload.css">
<script type="text/javascript" src="./js/webuploader.html5only.js"></script>
<script type="text/javascript" src="./js/diyUpload.js"></script>
<link rel="stylesheet" href="./css/General.css">
<!-- Custom styling -->
<!-- Custom fonts -->
<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700,900" 

media="screen"> -->
<script src="./js/share.js"></script>
<link rel="stylesheet" href="./css/share_style0_32.css"></head>
<body class="account-account style-4 ">
<iframe src="javascript:false" title="" frameborder="0" tabindex="-1" style="position: absolute; width: 0px; height: 0px; border: 0px;" src="./other/saved_resource.html"></iframe>
<iframe style="display: none;" src="./other/saved_resource(1).html"></iframe>

<!-- Cookie Control -->  
    <title></title>
 <!-- 右侧客服浮窗 -->
<link rel="stylesheet" href="./css/fix.css">
<script charset="utf-8" src="./other/wpa.php"></script>

<script>

    $(function(){
        if($(window).width()<767){
            $('#fix').fadeOut();
        }else{
            $('#fix').fadeIn();
        }
        initShare();
    })
    
    function initShare(){
        window._bd_share_config = {
            common : {
                bdText : "叮叮网 - 进口商品热卖排行榜",
                bdDesc : "叮叮网 - 进口商品热卖排行榜.叮叮网中国进口商品热卖榜，本栏商品全部为畅销热卖品，深受国内消费者喜欢的海淘佳品。",   
                bdUrl : "http://www.dindin.com/images/dindin_logo.png",     
                bdPic : "http://www.dindin.com/images/dindin_logo.png",
                onBeforeClick:function(cmd,config){
                    var host= window.location.host;
                    var bdUrl='http://';
                    /* var itemId = document.getElementById('itemid').value;
                    if(host=='www.dindin.com'){
                        bdUrl=bdUrl+host+'/goodsDetail_id_'
                    }else{
                        bdUrl=bdUrl+host+'/hello/goodsDetail_id_';
                    } */
                    bdUrl="http://www.dindin.com/hotSaleAction!findHotSaleGoods.go";
                    config.bdUrl=bdUrl;
                    return config;
                }
            },
            share : [{
                "bdSize" : 32
            }]/*,
            image : [{
                viewType : 'list',
                viewPos : 'top',
                viewColor : 'black',
                viewSize : '16',
                viewList : ['weixin','tqq','tsina','tieba','sqq','qzone','fbook']
            }],
            selectShare : [{
                "bdselectMiniList" : ['weixin','tqq','tsina','tieba','sqq','qzone','fbook']
            }]*/
        }
        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='js/share.js'];
        //with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
    }
    
    
    BizQQWPA.addCustom({aty: '1', a: '1003', nameAccount: 4008622111, selector: 'BizQQWPA'});
    jQuery(function(){
        jQuery('#fix ul li .back').click(function(){
            jQuery('html,body').animate({scrollTop:0},300);
        });
        jQuery('#fix ul li').each(function(){
            jQuery(this).find('a').hover(function(){
                jQuery(this).find('div').css({display:'block'});
            },function(){
                jQuery(this).find('div').css({display:'none'});
            })
        })
        jQuery('#fix ul li span').hover(function(){
            jQuery(this).find('dl').css({display:'block'});
            jQuery(this).find('dl').next().show();
        },function(){
            jQuery(this).find('dl').css({display:'none'});
            jQuery(this).find('dl').next().hide();
        })
        $('#fix .gototop').click(function(){
            $('body,html').animate({scrollTop:0},1500);
        })
    })

</script>
  


<!-- Old IE Control -->
<div class="outer_container" id="cont-container">
<!-- header 部分 -->

    <title></title>
   
<?php include './views/head.php';?>

<!-- 内容部分 -->
<div class="breadcrumb_wrapper container">
    <ul class="breadcrumb">
        <li><a href="?index">首页</a></li>
        <li><a href="?personalInfo/view">帐户</a></li>
        <li><a href="?personalInfo/view">个人资料</a></li>
    </ul>
</div>
<div id="notification" class="container"></div>
<div class="container">
    <div class="row">
        <div id="column-left" class="col-md-3 col-sm-4">
            <h3>我的帐户</h3>
            <div class="list-group box">                
                <a href="?personalInfo/view" class="list-group-item dark_hover">个人资料</a>
                <a href="?receiveAddress/view" class="list-group-item dark_hover">地址管理</a> 
                <a href="?order/list" class="list-group-item dark_hover">我的订单</a>
            </div>
        </div>
        <h5 class="titles col-lg-9 col-md-9 col-sm-8">
            <div style="float: left;"><strong>您的基本信息</strong></div>
            <div class="pull-right right-tip"><i class="fa fa-volume-down"></i>补全档案即获得100积分，赶紧行动吧！</div>
        </h5>
        <div id="content" class="col-md-9 col-sm-8">
            <form action="?personalInfo/view" method="post" name="userInfoForm" id="userInfoForm">
            <input type="hidden" name="ajax" value="1">
              <div class="bordered_content padded box " id="jiben-info">
                    <div class="row info-list">
                        <div class="col-sm-10 col-lg-3 col-md-3">
                            <div class="imgs">
                                <img src="./image/member-default-img.png">
                            </div>
                            <div class="name"><strong><?php echo $ret[0]["phone"]?></strong></div>
                        </div>
                        <div class="col-sm-10 col-lg-5 col-md-5 i-info">
                            <div><span class="i-name">邮箱：</span><strong><?php echo $ret[0]["email"]?></strong></div>
                            <div><span class="i-name">叮叮号：</span><strong><?php echo $ret[0]["phone"]?></strong></div>
                            <div><span class="i-name">上次登录时间：</span><?php echo date('Y-m-d H:i:s', $ret[0]["login_at"])?></div>
                        </div>
                    </div>
              </div> 
              <h5 class="titles" style="display:inline-block;" id="personal-info"><strong>个人资料</strong></h5>
              <div class="bordered_content padded box ">
                   <div class="form-group">
                    <label class="control-label">昵称</label>
                    <input type="text" name="nickname" maxlength="10" id="nickname" class="form-control username" value="<?php echo $ret[0]["nickname"]?>" required="required">
                  </div>    
                  <div class="form-group">
                    <label class="control-label">真实姓名  <span style="color: red;">*</span></label>
                    <input type="text" name="realname" maxlength="10" id="realname" class="form-control username" value="<?php echo $ret[0]["realname"]?>">

                  </div>
                 <div class="form-group">
                        <label class="control-label">身份证号码  <span style="color: red;">*</span></label>
                        <input type="text" name="idcard" maxlength="18" id="idcard" onpaste="return false;" class="form-control username" value="<?php echo $ret[0]["idcard"]?>" onkeyup="value=value.replace(/[\W]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\W]/g,&#39;&#39;))" required="required">
                  </div>
                  <div class="form-group">
                        <div class="control-label" style="color:#777777;">性别 : </div>
                        <input type="radio" name="sex" class="sex" checked="" value="1"> 男 &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="sex" class="sex" value="2"> 女
                  </div>
                  <?php $birthday = explode('.',$ret[0]['birthday']);?>
                  <div class="form-group">
                    <div class="birthday">
                        <div class="control-label" style="color:#777777;">生日 </div>
                        <div class="span-item" style="width: 100px;">
                            <i class="span-bg"></i>
                            <div class="selected-active s-y">
                            <?php echo $birthday[0]>0 ? $birthday[0] : '请选择';?></div>
                            <div class="selec select-year">
                            </div>
                        </div>
                        <span class="span-text">年</span>
                        <div class="span-item">
                            <i class="span-bg"></i>
                            <div class="selected-active s-m">
                            <?php echo $birthday[1]>0 ? $birthday[1] :'请选择';?></div>
                            <div class="selec select-month">
                            </div>
                        </div>
                        <span class="span-text">月</span>
                        <div class="span-item">
                            <i class="span-bg"></i>
                            <div class="selected-active s-d">
                            <?php echo $birthday[2]>0 ? $birthday[2] :'请选择';?></div>
                            <div class="selec select-day">
                            </div>
                        </div>
                        <span class="span-text">日</span>
                        <div class="clearfix"></div>
                    </div>
                  </div>
              </div>
              <h5 class="titles" style="display:inline-block;"><strong>联系方式</strong></h5>
              <div class="bordered_content padded box ">
                  <div class="form-group">
                    <label class="control-label">QQ</label>
                    <input type="text" name="qq" id="qq" onpaste="return false;" maxlength="15" style="ime-mode:disabled;" class="form-control username" value="<?php echo $ret[0]["qq"]?>" onkeyup="value=value.replace(/[\Wa-zA-z]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\Wa-zA-z]/g,&#39;&#39;))">
                  </div>
                  <div class="form-group">
                    <label class="control-label">联系电话 <span style="color: red;">*</span></label>
                    <input type="text" name="phone" id="phone" maxlength="11" class="form-control username" value="<?php echo $ret[0]["phone"]?>" onkeyup="value=value.replace(/[\Wa-zA-z]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\Wa-zA-z]/g,&#39;&#39;))" required="required">
                  </div>
                  <div class="form-group">
                    <label class="control-label">联系地址</label>
                    <input type="text" name="address" class="form-control username" value="<?php echo $ret[0]["address"]?>">
                  </div>
                  <div class="form-group">
                    <label class="control-label">电子邮箱</label>
                    <input type="text" name="email" class="form-control username" value="<?php echo $ret[0]["email"]?>">
                  </div>
                  <div class="form-group">
                    <label class="control-label">邮政编码</label>
                    <input type="text" name="zip" maxlength="6" id="zip" class="form-control username" value="<?php echo $ret[0]["zip"]?>" onkeyup="value=value.replace(/[\Wa-zA-z]/g,&#39;&#39;) " onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\Wa-zA-z]/g,&#39;&#39;))">
                  </div>
              </div>
              <input type="hidden" name="birthday" id="bir">
              </form>
          </div>
    <div style="text-align: center;padding-bottom: 30px">
          <a id="saveBtn"  class="btn btn-primary">保存</a>
    </div>
    </div>           
   </div>
   </div>

<script type="text/javascript">
$('#views .owl-carousel,#sellect .owl-carousel').owlCarousel({
    items:4,
    margin: 10,
    nav: true,
    loop: true,
    responsive:false,
    dots:false
  })
</script>



<?php include './views/foot.php'; ?>
<!-- footer 部分 -->

  <!-- .outer_container ends -->
<!-- Resources dont needed until page load -->
<script type="text/javascript" src="./js/jquery.cookie.js"></script>

<script>
$(document).ready(function(){
    //获取地址栏参数值
    function getUrlName(name) { 
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
        var r = window.location.search.substr(1).match(reg); 
        if (r != null) return unescape(r[2]); return null; 
    };
    //alert(getUrlName('result'))
    if(getUrlName('anchor')!=null && getUrlName('anchor')!=''){
        jQuery('body,html').animate({scrollTop:$('#personal-info').offset().top-100},1000);//定位到个人资料位置
    }
    
    // var flag = userLogin.isLogin();
    // if(!flag){
    //      alert("您尚未登录,请登录！");
    //      userLogin.loginAlert({action:function(){
    //             location.reload(true);
    //         }}
    //     );
    // }
    
    //提交数据
    $("#saveBtn").click(function(){
        var idcard =$("#idcard").val();
        var realname =$("#realname").val();
        var qq = $("#qq").val();
        var zip = $("#zip").val(); 
        var phone = $("#phone").val();
        var y=$("div.s-y").html();
        var m=$("div.s-m").html();
        var d=$("div.s-d").html();
        if($.trim(realname)==""){
            alert("真实姓名不能为空！");
            return;
        }    
        if(isNaN(qq)){            
            alert("QQ号存在非数字！");
            return;
        }
        if($.trim(phone)==""){            
            alert("电话不能为空！");
            return;
        }
        if(isNaN(phone)){            
            alert("电话存在非数字！");
            return;
        }        
        if(isNaN(zip)){            
            alert("邮编存在非数字！");
            return;
        }
        if (y,m,d=="请选择") {
            alert("请选择出生日期");
            return;
        }else{
            var bird=y+"."+m+"."+d;
            $("#bir").val(bird);
        }
        if($.trim(idcard)==""){        
            alert("身份证号码不能为空");
            return;
        
        }else{        
            var uform=$("#userInfoForm").serialize();
            $.ajax({
               type: "POST",
               url: "?personalInfo/view",
               data: uform,
               dataType: "json",
               success: function(data){console.log(data);
                 if(data.code==200) {
                        alert("信息保存成功");
                        location.reload();
                    }else{
                        alert("信息保存失败，填写有误");
                  }
                }                               
            });
        }
        
        
    });
    
});
</script>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally 

accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="cboxOverlay" style="display: none;"></div>
<!-- 修改头像弹框 -->
<style>
#upload_user_img .loadimg img{width:100px;height:100px!important;}
</style>
<div class="modal fade bs-example-modal-static in " id="upload_user_img" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="false" style="display: none;">
     <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
               <h4 class="modal-title">上传头像</h4>
           </div>
           <div class="modal-body">
                <div id="box">
                    <div class="parentFileBox" style="width: 180px;"> 
                        <ul class="fileBoxUl">
                            <li id="fileBox_WU_FILE_1" class="diyUploadHover">
                                <div class="viewThumb">
                                <img src="./image/member-default-img.png" width="170" height="170">                             
                                </div>
                                <div class="diySuccess"></div>                  
                                <div class="diyFileName"></div>                 
                                <div class="diyBar">                            
                                    <div class="diyProgress"></div>                             
                                    <div class="diyProgressText">0%</div>                   
                                </div>              
                            </li>
                        </ul>
                        <span style="color:#a1a1a1;font-size: 12px;">只支持PNG、GIF、JPG等图片格式！</span>
                    </div>
                    <div id="fileFileName" class="webuploader-container">
                        <div class="webuploader-pick">点击选择图片</div>
                        <div id="rt_rt_1bf40bdjlikm1o3319cg1r3u10qi1" style="position: absolute; top: 0px; left: 0px; width: 169px; height: 36px; overflow: hidden;">
                            <input type="file" name="file" class="webuploader-element-invisible" multiple="multiple" accept="image/*">
                            <label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label>
                        </div>
                    </div>
                </div>
           </div>
       </div>
     </div>
 </div>
<script>
    // $(function(){
    //     var flag = userLogin.isLogin();
    // if(!flag){
    //      alert("您尚未登录,请登录！");
    //      userLogin.loginAlert({action:function(){
    //             location.reload(true);
    //         }}
    //     );
    // }
    // })
</script>
<script type="text/javascript">
/*
* 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
* 其他参数同WebUploader
*/

$('#fileFileName').diyUpload({
    //url:'userinfoEditAction!addPic.go',
    fileNumLimit:100,
    success:function( data ) {
        
        alert('aa');
                
    },
    error:function( err ) {
            
    }
});
</script>
<script type="text/javascript" src="./js/load_toolbar.js"></script>
<script type="text/javascript" src="./js/birthday-select.js"></script>
<div id="cboxOverlay" style="display: none;"></div>
<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;">
    <div id="cboxWrapper">
        <div>
            <div id="cboxTopLeft" style="float: left;"></div>
            <div id="cboxTopCenter" style="float: left;"></div>
            <div id="cboxTopRight" style="float: left;"></div>
        </div>
        <div style="clear: left;">
            <div id="cboxMiddleLeft" style="float: left;"></div>
            <div id="cboxContent" style="float: left;">
                <div id="cboxTitle" style="float: left;"></div>
                <div id="cboxCurrent" style="float: left;"></div><a id="cboxPrevious"></a>
                <a id="cboxNext"></a>
                <button id="cboxSlideshow"></button>
                <div id="cboxLoadingOverlay" style="float: left;"></div>
                <div id="cboxLoadingGraphic" style="float: left;"></div>
            </div>
            <div id="cboxMiddleRight" style="float: left;"></div>
        </div>
        <div style="clear: left;">
            <div id="cboxBottomLeft" style="float: left;"></div>
            <div id="cboxBottomCenter" style="float: left;"></div>
            <div id="cboxBottomRight" style="float: left;"></div>
        </div>
    </div>
    <div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div>
</div>
</body>
</html>