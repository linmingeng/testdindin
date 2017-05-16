<!DOCTYPE html>
<!-- saved from url=(0047)http://www.dindin.com/buyAction!buySingle.go#pl -->
<html dir="ltr" lang="en"><!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<script type="text/javascript" charset="utf-8" async="" src="./js/contains.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/taskMgr.js"></script>
<script type="text/javascript" charset="utf-8" async="" src="./js/views.js"></script>

<title>叮叮网 - 提交订单</title>

<link rel="icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="http://www.dindin.com/dindinv2Images/favicon.ico" type="image/x-icon">
<meta name="Description" content="商城，正品，低价，贴心服务，购物无忧，团购，限时抢购">
<meta name="Keywords" content="商城,智付,智汇宝,会员,VIP,叮叮网,购物,智付,智游卡,团购">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" async="" src="./js/mv.js"></script>
<script type="text/javascript" async="" src="./js/mba.js"></script>
<script type="text/javascript" async="" src="./js/mvl.js"></script>
<script src="./js/hm.js"></script>
<script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>

<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./css/responsive.css">

<link rel="stylesheet" type="text/css" href="./css/quickcheckout.css" media="screen">
<link rel="stylesheet" type="text/css" href="./css/bootstrap-datetimepicker.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="./css/address.css" media="screen">
	
<script type="text/javascript" src="./js/owl.carousel.min.js"></script>
<script src="./js/jquery.matchHeight.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./js/shopme_common.js"></script>

<script type="text/javascript" src="./js/quickcheckout.block.js"></script>
<script type="text/javascript" src="./js/moment.js"></script>
<script type="text/javascript" src="./js/bootstrap-datetimepicker.min.js"></script>
<style type="text/css"></style>
<script type="text/javascript" src="./js/tweetfeed.min.js"></script>
<script type="text/javascript" src="./js/address.js"></script>
<script type="text/javascript" src="./js/regiondata.js"></script>
<script type="text/javascript" src="./js/ajaxfileupload.js"></script>
<script type="text/javascript" src="./js/cart.js"></script>
<script type="text/javascript" src="./js/load_data.js"></script>
<link rel="stylesheet" href="./css/General.css">
<script type="text/javascript">
$(function(){
	showAddressEditor();
	
	//初始化收货地址
	initAddress();
	//初始化价格
	countPrice();
	
	$("#payment-address input[type='radio']").click(function() {
		var id = $(this).attr("id");
		//新建div
		if(id=="payment-address-new"){
			$("#payment-new").show();
			$("#payment-existing").hide();
		}else if(id=="payment-address-existing"){//选择div
			$("#payment-new").hide();
			$("#payment-existing").show();
		}
	})
	//优惠券
	$("#coupon-heading").click(function() {
		//$("#payment-new").hide();
		if($("#coupon-content").is(":hidden")){
			$("#coupon-content").show();
			
			//判断是否能够使用优惠券
			checkYhj();
		}else{
			$("#coupon-content").hide();
		}
	})
	//礼品劵
	$("#voucher-heading").click(function() {
		if($("#voucher-content").is(":hidden")){
			$("#voucher-content").show();
		}else{
			$("#voucher-content").hide();
		}
	})
	
	
	$("#buyNumStr").blur(function(){
		refresh();
	})
	
	//收货人姓名
	$('#receiveName').blur(function(){
			
			var receiveName = $(this).val();
			if($.trim(receiveName)==''){
				
				$(this).addClass("errorRed");
				return false;
			}
			$(this).removeClass("errorRed");
	});
	
	//收货人电话
	$('#receiveMobile').blur(function(){
			
			var receiveMobile = $(this).val();
			if($.trim(receiveMobile)==''){
				
				$(this).addClass("errorRed");
				return false;
			}
			$(this).removeClass("errorRed");
	});
	
	//收货人地址
	$('#receiveAddr').blur(function(){
			
			var receiveAddr = $(this).val();
			if($.trim(receiveAddr)==''){
				
				$(this).addClass("errorRed");
				return false;
			}
			$(this).removeClass("errorRed");
	});
	
	//收货人邮编
	$('#receivePostCode').blur(function(){
			
			var receiveAddr = $(this).val();
			if($.trim(receiveAddr)==''){
				
				$(this).addClass("errorRed");
				return false;
			}
			$(this).removeClass("errorRed");
	});
	
	//身份证号码
	$('#idCard').blur(function(){
			
			var idCard = $(this).val();
			if($.trim(idCard)==''){
				
				$(this).addClass("errorRed");
				return false;
			}
			$(this).removeClass("errorRed");
	});
	
	
	$('#idCard2').blur(function(){
			
			var idCard2 = $(this).val();
			if($.trim(idCard2)==''){
				
				$(this).addClass("errorRed");
				return false;
			}else{
			
				if(!checkIdCard(idCard2)){
					$(this).next().show();
					$(this).addClass("errorRed");
					$("#idCardMsg").show();
					return;
				}
				$("#idCardMsg").hide();
				$(this).removeClass("errorRed");
				
			}
			
					
			$(this).removeClass("errorRed");
	});
	
	//地址改变事件
	$("#address").change(function(){
		
		var addressStr = $("#address option:selected").text();
		if($.trim(addressStr)!=''){
		
			var arr = addressStr.split(",");
			
			//填充收货人姓名
			var arr = addressStr.split(",");
			if($.trim(arr[4])==''){
			
				$("#receiveName2").val('');	
			}else{
				
				var szhmArr =  arr[1].split("：");
				$("#receiveName2").val(szhmArr[1]);
			}
			
			//改变身份证号码
			if($.trim(arr[4])==''){
				
				$("#idCard2").val('');
			}else{
				
				var szhmArr =  arr[4].split("：");
				$("#idCard2").val(szhmArr[1]);
			}
			
			//身份证照片
			$("#fileList").empty();
			var valArr = $("#address option:selected").val().split(",");
			$("#addressId").val(valArr[valArr.length-1]);
			var htmlStr="";
			//是否存在身份证照片
			var idPicImgArr = $("#idPic"+valArr[valArr.length-1]).val().split(",")
			$.each(idPicImgArr,function(i){
				var imgUrl = idPicImgArr[i].substr(idPicImgArr[i].indexOf(":")+1,idPicImgArr[i].length);
				if(imgUrl!='undefined'){
					var imgIdStr = idPicImgArr[i].substr(0,idPicImgArr[i].indexOf(":"));
					htmlStr += "<img name='"+imgIdStr+"' width='100' id='"+imgIdStr+"' src='"+imgUrl+"' /><a id='a"+imgIdStr+"' onclick='delPicAttr(\""+imgIdStr+"\",\"a"+imgIdStr+"\")'>删除</a>"
				}
			});
			$("#fileList").append(htmlStr);
		}	
	});
})



function checkData() {
	var receiveName = $("#receiveName").val();
	var receiveAddr = $("#receiveAddr").val();
	var receivePostCode = $("#receivePostCode").val();
	var receiveMobile = $("#receiveMobile").val();
	var provinceId = $("#provinceId").val();
	var idCard = $("#idCard").val();
	
	if (receiveName == '' || receiveName == "姓名" || receiveName.length > 20) {
		$('#receiveName').addClass("errorRed");
		return false;
	}else{
		$('#receiveName').removeClass("errorRed");
	}
	
	if (receiveMobile == '' || receiveMobile == "手机号" || isNaN(receiveMobile)) {
		$('#receiveMobile').addClass("errorRed");
		return false;
	}else{
		$('#receiveMobile').removeClass("errorRed");
	}
	var area= $("select[name='areaId']");
	var areaId = $("select[name='areaId'] option:selected");
	if(area.css("display")=="inline-block"){
		
		if(areaId.text()=="- 选择区 -"){
			
			$("#regionArea").addClass("errorRed");
			return;
		}else{
			$("#regionArea").removeClass("errorRed");
		}
	}else{
			var city = $("select[name='cityId']");
			var cityId = $("select[name='cityId'] option:selected");
			if(city.css("display")=="inline-block"){
				if(cityId.text()=="- 选择市 -"){
				
					$("#regionArea").addClass("errorRed");
					return;
				}
			}
			$("#regionArea").removeClass("errorRed");
	}
	
	
	//errorRed
	if (receiveAddr == '' || receiveAddr == "街道地址" || receiveAddr.length > 50) {
		$('#receiveAddr').addClass("errorRed");
		return false;
	}else{
		$('#receiveAddr').removeClass("errorRed");
	}
	if (receivePostCode == '' || receivePostCode == "邮编" || receivePostCode.length > 6 || isNaN(receivePostCode)) {
		$('#receivePostCode').addClass("errorRed");
		return false;
	}else{
		$('#receivePostCode').removeClass("errorRed");
	}
	
	if($.trim(idCard)==''){
		
		$('#idCard').addClass("errorRed");
		return false;
	}else{
		
		if(!checkIdCard(idCard)){
			$(this).next().show();
			$('#idCard').addClass("errorRed");
			$("#idCardMsg").show();
			return;
		}
		$("#idCardMsg").hide();
		$('#idCard').removeClass("errorRed");
	}
	
	return true;
}

/**
 * 检查身份证
 */
//function checkIdCard(idCard){
//	var isValid = false;
//	$.ajax({
//		   type: "POST",
//		   url: "receiveAddressAction!checkIdCard.go",
//		   data: "po.idCard="+idCard,
//		   async: false,
//		   dataType:'json',
//		   success: function(data){
//
//				if(data.result==1){
//
//					isValid= false;
//				}else{
//
//					isValid = true;
//				}
//		   }
//	});
//	return isValid;
//
//}
function checkIdCard(idCard) {
    //15位和18位身份证号码的正则表达式
    var regIdCard = /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;
    //如果通过该验证，说明身份证格式正确，但准确性还需计算
    if (regIdCard.test(idCard)) {
        if (idCard.length == 18) {
            var idCardWi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //将前17位加权因子保存在数组里
            var idCardY = new Array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2); //这是除以11后，可能产生的11位余数、验证码，也保存成数组
            var idCardWiSum = 0; //用来保存前17位各自乖以加权因子后的总和
            for (var i = 0; i < 17; i++) {
                idCardWiSum += idCard.substring(i, i + 1) * idCardWi[i];
            }
            var idCardMod = idCardWiSum % 11;//计算出校验码所在数组的位置
            var idCardLast = idCard.substring(17);//得到最后一位身份证号码
            //如果等于2，则说明校验码是10，身份证号码最后一位应该是X
            if (idCardMod == 2) {
                if (idCardLast == "X" || idCardLast == "x") {
                    return true;
                    //alert("恭喜通过验证啦！");
                } else {
                    return false;
                    //alert("身份证号码错误！");
                }
            } else {
                //用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码
                if (idCardLast == idCardY[idCardMod]) {
                    //alert("恭喜通过验证啦！");
                    return true;
                } else {
                    return false;
                    //alert("身份证号码错误！");
                }
            }
        }
    } else {
        //alert("身份证格式不正确!");
        return false;
    }
}

function initAddress(){
	$.ajax({
		async : false,
		type : 'POST',
		dataType : "json",
		url : 'receiveAddressAction!getListAddress.go',
		/* data : {
			id : id
		}, */
		success : function(msg) {
			if(msg=="isNotLogin"){
				alert("登陆已过期或未登陆,请重新登陆！");
			}else{
				$("#address").empty();
				for(var i=0;i<msg.length;i++){
					var id =msg[i].id;
					var regionId = msg[i].regionId;
					var regionIdStr = $regionGetStr(regionId);
					var regionIdSplitStr = $regionGetSplitStr(regionId);
					var receiveAddr = msg[i].receiveAddr;
					var receiveName = msg[i].receiveName;
					var receiveMobile = msg[i].receiveMobile;
					var receivePostCode = msg[i].receivePostCode;
					var receiveTel = msg[i].receiveTel;
					//增加身份证号码
					var idCard2 = msg[i].idCard;
					//var txt =regionIdStr+receiveAddr+",收货人："+receiveName+",联系电话："+receiveMobile+"、"+receiveTel+",邮编："+receivePostCode;
					//var val = regionIdSplitStr+","+receiveAddr+","+receiveName+","+receiveMobile+"、"+receiveTel+","+receivePostCode;
					
					var txt =regionIdStr+receiveAddr+",收货人："+receiveName;//",联系电话："+receiveMobile+"、"+receiveTel+",邮编："+receivePostCode;
					var val = regionIdSplitStr+","+receiveAddr+","+receiveName;//","+receiveMobile+"、"+receiveTel+","+receivePostCode;
					
					if($.trim(receiveMobile)!=''){
						txt+=",联系电话："+receiveMobile;
						val+=","+receiveMobile;
					}
					if($.trim(receiveTel)!=''){
					
						txt+="、"+receiveTel;
						val+="、"+receiveTel;
					}
					if($.trim(receivePostCode)!=''){
						
						txt+=",邮编："+receivePostCode;
						val+=","+receivePostCode;
					}
					if($.trim(idCard2)!=''){
						
						txt+=",身份证："+idCard2;
					}
					val+=","+id;					
					
					
					
					
					$("#address").append("<option value='"+val+"'>"+txt+"</option>");  //为Select追加一个Option(下拉项) 
					//身份证照片
					var frontPic = msg[i].frontPic;//正面
					var reversePic = msg[i].reversePic;//反面
					$("#dvHidden").append("<input type='hidden' id='idPic"+id+"' value=frontPic:"+frontPic+",reversePic:"+reversePic+" />");					
					if(i==0){
					
						$("#idCard2").val(idCard2);	
						$("#receiveName2").val(receiveName);
						
						$("#addressId").val(id);
						var htmlStr = "";
						if($.trim(frontPic)!='')
						{
							htmlStr += "<img name='frontPic' id='frontPic' width='100' src='"+frontPic+"' /><a id='afrontPic' onclick='delPicAttr(\"frontPic\",\"afrontPic\")'>删除</a>"
						}
						if($.trim(reversePic)!='')
						{
							htmlStr += "<img name='reversePic' id='reversePic' width='100' src='"+reversePic+"' /><a id='areversePic' onclick='delPicAttr(\"reversePic\",\"areversePic\")'>删除</a>"
						}
						if(htmlStr!="")
						{
							$("#fileList").append(htmlStr);
						}
					}
				}
				
			}
		}
	})
}

function saveAddress(){
	if(!checkData()){
		return;
	}
	
	var data =  $('#receiveForm').serialize();
	data = encodeURI(data)
	$.ajax({
		async : false,
		type : 'POST',
		dataType : "json",
		url : 'receiveAddressAction!save.go',
		data :data,
		success : function(msg) {
			if(msg=="success"){
				initAddress();
			}else{
				alert(msg);
			}
			$("#payment-new").hide();
			$("#payment-existing").show();
			//$("#payment-address-new").removeAttr("checked");
			//alert($("#payment-address input[type='radio']:checked").attr("id"));
			//$("#payment-address-new").attr("checked",false);
			//$("#payment-address-existing").attr("checked",true);
			
			$("#payment-address-existing").get(0).checked=true;
			$("#receiveForm").get(0).reset();
			$(".quickcheckout-content .radio").eq(0).find('.existing-address').addClass("existing-img").parent().siblings(".radio").find(".existing-address").removeClass("existing-img");
		}
	})
}




function refresh(){
	//相关检验
	if(!checkDate()){
		return false;
	}
	//计算价格
	if(!countPrice()){
		return false;
	}
	//是否能够使用优惠券
	if(!checkYhj()){
		return false;
	}
}

//判断是否能使用优惠券
function checkYhj(){
	var id = $("#itemid").val();
	var total = $("#total_id_"+id).html()*1;
	if(total >100){
		$("#button-coupon").get(0).disabled = false;
	}else{
		$("#button-coupon").get(0).disabled = true;
		$("#yhqMoney").html(0);
		$("#couponid").val(0);
	}
}

//相关校验
function checkDate(){
	var startBuyMin = $("#startBuyMin").val()*1;
	var qgMax = $("#qgMax").val()*1;
	var buyNum = $("#buyNumStr").val()*1;
	var j = 0;
  	if(isNaN(buyNum)){
  		alert("提示：数量只能输入数字！");
  		return false;
  	}
  	var goodSpecificationId = $("#goodSpecificationId").val();
	var _m =$("#dgsl").val()*1;
  	if(goodSpecificationId!= null && goodSpecificationId!=undefined && goodSpecificationId*1 >0){
  		_m = $("#goodSpecificationStock").val()*1;
  	}
	if(_m==0){
		alert("提示：该商品库存不足！");
		return false;
	}
	if(buyNum>_m){
		alert("提示：该商品库存不足！");
		return false;
	}
	if(startBuyMin!=null && startBuyMin!="" && (startBuyMin*1) > 0 && buyNum < startBuyMin*1){
		alert("提示：购买数量不能小于起售数量！");
		return false;
	}
	if(qgMax!=null && qgMax!="" && (qgMax*1) > 0 && buyNum > qgMax*1){
		alert("提示：购买数量不能大于限购数！限购"+qgMax+"件");
		return false;
	}
	return true;
}

//计算价格
function countPrice(){
	var buyNum = $("#buyNumStr").val()*1;
	var tgjg = $("#tgjg").val()*1;
	var id = $("#itemid").val();
	var price = tgjg;
	var fdjgListStr = $("#fdjgList").val();
	if(fdjgListStr!=null && fdjgListStr!="" ){
		var fdjgList = JSON.parse(fdjgListStr);
		for(var i = 0;fdjgList.length;i++){
			var lastNum = fdjgList[i].lastNum;
			var beginNum = fdjgList[i].beginNum;
			var fdjg = fdjgList[i].fdjg;
			if(lastNum==-1 && buyNum >=beginNum){
				price = fdjg;
				break;
			}else if(buyNum >=beginNum && buyNum<= lastNum){
				price = fdjg;
				break;
			}
		}
	}
	
	var total = buyNum*price;
	//优惠金额
	var yhqMoney = $("#yhqMoney").html()*1;
	//单价
	$("#price").html(price);
	//总价
	$("#total_id_"+id).html(total.toFixed(2));
	//小计
	$("#amounts").html(total.toFixed(2));
	//总价
	$("#sumAmounts").html((total-yhqMoney).toFixed(2));
}
//上一次优惠价格
var temp = 0;
function yhq(){	
	var yhq = $("#yhq").val()*1;
	var sumAmounts = $("#sumAmounts").html()*1;
	var yhqMoney = $("#yhqMoney").html(yhq);
	$("#sumAmounts").html(sumAmounts-yhq+temp);
	temp = yhq;
	//后台取优惠券用
	if(yhq!=null && yhq!="" && yhq > 0){
		$("#couponid").val(yhq);
	}else{
		$("#couponid").val(0);
	}
}

function submitBuy(){

	$("#button-payment-method").addClass("disabled");
	//检验数据
//	if(!checkDate()){
//		$("#button-payment-method").removeClass("disabled");
//		return false;
//	}

//
//	var buyNum = $("#buyNumStr").val()*1;
//	$("#buynum").val(buyNum);
//	var liuyan = $("#liuyanStr").val();
//	$("#info").val(liuyan);
//
//	var addressStr = $("#address").val();
//	if(addressStr!=null && addressStr!=undefined && addressStr!=""){
//		var o = addressStr.split(",");
//		var oo =o[0].split("-");
//		$("#privince").val(oo[0]);
//		$("#city").val(oo[1]);
//		$("#area").val(oo[2]);
//		$("#recvaddr").val(o[1]);
//		$("#recvname").val(o[2]);
//		var ooo = o[3].split("、")
//		$("#moblie").val(ooo[0]);
//		$("#phone").val(ooo[1]);
//		$("#postcode").val(o[4]);
//		//区域id
//		var receiveId_ = o[5];
//		$("#receiveId").val(receiveId_);
//		$("#receiveIdCard").val($("#idCard2").val());
//		//身份证照片
//		var addressId = $("#addressId").val();
//		$("#fileUrlStr").val($("#idPic"+addressId).val());
//
//		//收货人姓名
//		$("#receiveName_address").val($("#receiveName2").val());
//		$("#recvname").val($("#receiveName2").val());
//	}
//
//	if($.trim(addressStr)==''||$.trim(addressStr)==null){
//
//		$("#button-payment-method").removeClass("disabled");
//		alert('请选择收货地址！');
//		return ;
//	}
	
	//交易身份证
	var idCard2 = $('#idCard2').val();
	var receiveName2 = $('#receiveName2').val();
	if($.trim(receiveName2) == ''){
        alert('姓名不能为空！');

        $("#button-payment-method").removeClass("disabled");
        return ;
    }
//	if($.trim(idCard2)==''){
//		$('#idCard2').addClass("errorRed");
//		$('#idCard2').focus();
//		$("#button-payment-method").removeClass("disabled");
//		alert('身份证号码不能为空！');
//		return;
//	}else if(!checkIdCard(idCard2)){
//		$('#idCard2').addClass("errorRed");
//		$('#idCard2').focus();
//		$("#button-payment-method").removeClass("disabled");
//		$("#idCardMsg").show();
//        alert('身份证号码错误！');
//		return;
//	}
    var urlStr ="?/order/submit";
	var addressid = $('#address').val();
     $.ajax({
         url:urlStr,
         type : 'POST',
         dataType:'json',
         data: {addressid:addressid,ajax:1},
         success:function(data){
             if(data.code == 200){
                 $('#orderid').val(data.orderid);
                 location.href = "?/order/pay/orderid/"+data.orderid;
//                 $("#buyform").submit();
             }else{

                 $("#button-payment-method").removeClass("disabled");
                 alert(data.msg);
                 return ;
             }
         }
     });

    $("#button-payment-method").removeClass("disabled");
     return ;
	$("#idCardMsg").hide();
	$('#idCard').removeClass("errorRed");
	
	
	$("#payment_method").val($("input[name='payMethodRadio']:checked").val());
	
	//判断是否登陆
	var flag = userLogin.isLogin();
	if(flag){
		//var url = 'buyAction!buySingle.go';
		//$("#buyForm").attr("action", url).submit();	
		/*
			$("#tip-id,.mask").fadeIn();
			return false;
		*/
		/**/
		$("#buyform").submit();	
	}else{
		userLogin.loginAlert({action:function(){
					overlay();
					$("#buyform").submit();	
			}}
		);
	}
}
//显示身份证照片上传
function showIdCardPic()
{
	if($("#chkIdCardPic").is(":checked"))
	{
		$("#dvIdCardPic").css("display","block");
	}else{
		$("#dvIdCardPic").css("display","none");
	}
}
function saveIdCardPic()
{
	var addressId = $("#addressId").val();
	var dvHtml = $("#fileList");
	var size = $("#fileList>img").length;
	if(size==2){
		alert("最多上传2张图片!");
		return;
	}
	if(addressId!='')
	{
		var picType=$("#idPic"+addressId).val();
	    var picTypeArr;
	    if(picType!="")
	    {
	       picTypeArr = picType.split(",");
	    }
	    var picNum=0;
	    $.each(picTypeArr,function(i){
	    	var imgUrl = picTypeArr[i].substr(picTypeArr[i].indexOf(":")+1,picTypeArr[i].length);
	    	if(imgUrl=='undefined')
	    	{
	    		if(i==0)
	    		{
	    			picNum=1;
	    			return false;
	    		}else{
	    			picNum=2;
	    			return false;
	    		}
	    	}
	    });
		var idCard =$("#idCard2").val();
		var urlStr = "buyAction!uploadIdCardPic.go?idCardPicNum="+picNum+"&receiveIdCard="+idCard;
		
		 $.ajaxFileUpload({
	        url: urlStr, 
	        secureuri: false, //是否需要安全协议，设置为false
	        fileElementId: 'uploadFile', //文件上传域的ID
	        dataType: 'json', //返回值类型 设置为json
	        success: function (data, status)  //服务器成功响应处理函数
	        {
			   var msg = data.message;
	           if(msg=="上传成功!")
	           {
	        	   var fileURL = data.fileUrl;		           
		           var imgHtml = "<img";
		           imgHtml += " src=\""+fileURL+"?"+ Math.random()+"\"";
	        	   var typeVal="";
	        	   $.each(picTypeArr,function(i){
	        		    var imgUrl = picTypeArr[i].substr(picTypeArr[i].indexOf(":")+1,picTypeArr[i].length);	        		    
						if(imgUrl=='undefined'){
							var imgIdStr = picTypeArr[i].substr(0,picTypeArr[i].indexOf(":"));
							imgHtml += " name='"+imgIdStr+"' id='"+imgIdStr+"' ";
		        	        imgHtml += " width='100'/><a id='a"+imgIdStr+"' onclick='delPicAttr(\""+imgIdStr+"\",\"a"+imgIdStr+"\")'>删除</a>";
		        	        if(i==0){
								typeVal = imgIdStr+":"+fileURL+","+picTypeArr[i+1];								
							}else{
								typeVal += imgIdStr+":"+fileURL;
							}
							return false;
						}else{
							typeVal += picTypeArr[i]+",";
						}
	        	   });
		           dvHtml.append(imgHtml);
		           $("#idPic"+addressId).val(typeVal);
	           }
	           alert(msg);	           
	        },
	        error: function (data, status, e)//服务器响应失败处理函数
	        {
	           alert("上传失败!");
	           console.log("数据"+data);
	           console.log("错误"+e);
	        }
     	});
	}else{
		alert("系统出现问题，请重新选择收货人!");
	}
}
function delPicAttr(imgId,aId)
{
	var addressId = $("#addressId").val();
	$("#"+imgId).remove();
	$("#"+aId).remove();
	var picTypeArr= $("#idPic"+addressId).val().split(",");
	var typeVal="";
	$.each(picTypeArr,function(i){
		var val = picTypeArr[i].indexOf(imgId);				
		if(val>-1)
		{
			if(i==0){
				typeVal = imgId+":undefined"+","+picTypeArr[i+1];
				return false;
			}else{
				typeVal += imgId+":undefined";
			}
		}else{
			typeVal += picTypeArr[i]+",";
		}
	});
	$("#idPic"+addressId).val(typeVal);
	
	/*var urlStr ="buyAction!delIDCardPic.go?fileUrlStr="+$("#"+imgId).attr('src');
	$.ajax({
		url:urlStr,
		type : 'POST',
		dataType:'json',
		success:function(data){
			alert(data);
			
		}		
	});*/
}
</script>

<script src="./js/share.js"></script>
<link rel="stylesheet" href="./js/share_style0_32.css">
<script charset="utf-8" async="" src="./js/i.js" id="_da"></script></head>
<body class="quickcheckout-checkout style-4 ">
<iframe src="javascript:false" title="" frameborder="0" tabindex="-1" style="position: absolute; width: 0px; height: 0px; border: 0px;" src="./image/saved_resource.html"></iframe>
<iframe style="display: none;" src="./image/saved_resource(1).html"></iframe>

	<!-- Cookie Control -->

	<!-- Old IE Control -->
	<div class="outer_container" id="cont-container">
		<!-- header 部分 -->
		
<?php include './views/head.php';?>
  
<!-- 商品内容部分 -->
		<div class="breadcrumb_wrapper container">
			
		<ul class="breadcrumb">
				<li><a href="http://www.dindin.com/index.html">首页</a>
				</li>
				<li><a href="http://www.dindin.com/shopping_cart.html">购物车</a>
				</li>
				<li><a href="http://www.dindin.com/checkout.html">购买</a>
				</li>
			</ul></div>
		<div id="notification" class="container"></div>
		<div class="container">

			<div class="row">
				<div id="content" class="col-sm-12">
					<div id="social_login_content_holder"></div>
					<!-- Start -->
					<div id="warning-messages"></div>
					<div id="success-messages"></div>
					<link rel="stylesheet" media="screen" href="./css/quickcheckout_one.css">
					<link rel="stylesheet" media="screen" href="./css/quickcheckout_mobile.css">


					<div id="quickcheckout-countdown"></div>

					<div id="quickcheckoutconfirm">

						<div class="grid_holder">

							<div class="checkout-column">
								<div id="payment-address">
									<div class="quickcheckout-heading box-heading">
										<span>收货地址</span>
									</div>
									<div class="quickcheckout-content">
										<div class="radio">
											<label for="payment-address-existing" class="existing-address existing-img"><input type="radio" name="payment_address" value="0" id="payment-address-existing" checked="true" style="display: none;"> 用已存在的地址</label>
										</div>
										
										<div id="payment-existing" style="">
                                            <select name="address" id="address" class="form-control">
                                            <?php

                                            foreach($ret['address_list'] as $address){
                                                echo '<option value="'.$address['addressid'].'">'.$address['address'].'</option>';
                                            }
                                            ?>
                                            </select>
                                        </div>
									</div>
								</div>
							</div>
							<script>
								$(".quickcheckout-content .radio").each(function(){
									$(this).find(".existing-address").click(function(){
										$(this).addClass("existing-img").parent(".radio").siblings(".radio").find(".existing-address").removeClass("existing-img");
									})
								})
							</script>
							<div class="checkout-column">
								<div id="shipping-method">
									<div class="quickcheckout-heading box-heading">身份证(<span style="color: red;font-size: 13px;">温馨提示：身份证号码请使用与收件人对应的身份证号码，否则您购买的商品将无法通过海关的检查，谢谢配合。</span>)</div>
									<div class="quickcheckout-content">
										<span style="line-height:26px;">姓名</span>
										<table class="table">
											<tbody>
												<tr class="options-list">
													<td style="width:22px">
														<input type="text" style="width:250px;float:left;margin-right:5px;margin-bottom:0;" id="receiveName2" name="receiveName2" value="<?php echo $ret['address_list'][0]['name'];?>" maxlength="20" class="form-control">
													</td>
												</tr>
											</tbody>
										</table>
										<p style="line-height:26px;">身份证号码</p>
										<table class="table">
											<tbody>
												
												<tr class="options-list">
													<td style="width:22px">
														<input type="text" style="width:250px;float:left;margin-right:5px;margin-bottom:0;" onkeyup="value=value.replace(/[\W]/g,&#39;&#39;) " value="<?php echo $ret['address_list'][0]['idcard'];?>" onbeforepaste="clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[\W]/g,&#39;&#39;))" id="idCard2" name="idCard2" maxlength="18" class="form-control">
                                                        <div id="idCardMsg" style="float:left;margin-top:8px;color:red;display: none;">身份证号码格式错误！</div>
													</td>
												</tr>
											</tbody>
										</table>
										<div id="fileList">											
										</div>
										<div id="dvHidden" style="display: none;">
<!--											<input type="hidden" id="addressId" name="addressId" value="13018">-->
										<input type="hidden" id="idPic13018" value="frontPic:undefined,reversePic:undefined"></div>

										<input type="text" name="delivery_date" value="" class="hide">
										<select name="delivery_time" class="hide"><option value=""></option>
										</select>
									</div>
								</div>
								<div id="shipping-method" style="display: none;">
									<div class="quickcheckout-heading box-heading">运输方式</div>
									<div class="quickcheckout-content">
										<p>请选择运输方式</p>
										<table class="table">
											<tbody>
												<tr>
													<td colspan="3"><b>邮费</b>
													</td>
												</tr>
												<tr class="options-list">
													<td style="width:22px"><input type="radio" name="shipping_method" value="flat.flat" id="flat.flat" checked="checked"></td>
													<td><label for="flat.flat">统一邮费</label> - <label for="flat.flat"><span class="shipping-sum">￥0.00</span>
													</label>
													</td>
												</tr>
											</tbody>
										</table>

										<input type="text" name="delivery_date" value="" class="hide">
										<select name="delivery_time" class="hide"><option value=""></option>
										</select>
									</div>
								</div>
								<div id="payment-method" style="display: none;">
									<div class="quickcheckout-heading box-heading">支付方式</div>
									<div class="quickcheckout-content">
										<p>请选择支付方式</p>
										<table class="radio payment">
											<tbody>
												<tr class="highlight">
													<td style="width:22px"><input type="radio" name="payMethodRadio" value="dinPay" checked="checked"></td>
													<td valign="middle"><img src="./image/bankPay.jpg" title="银行卡支付" style="vertical-align: bottom;">
													</td>
												</tr>
												<tr class="highlight">
													<td style="width:22px"><input type="radio" name="payMethodRadio" value="wxPay"></td>
													<td valign="middle"><img src="./image/wxPay.jpg" title="微信支付">
													</td>

												</tr>
												<tr class="highlight">
													<td style="width:22px"><input type="radio" name="payMethodRadio" value="aliPay"></td>
													<td valign="middle"><img src="./image/alipay.jpg" title="支付宝支付">
													</td>

												</tr>
											</tbody>
										</table>

										<textarea name="survey" class="hide"></textarea>
									</div>
								</div>

							</div>
							<!-- not if 2 columns -->



							<div class="checkout-column cart">
								<!-- not if 2 columns -->

								<div id="cart1">
									<div class="quickcheckout-content cart">
										<div class="checkout-heading box-heading">确认商品信息</div>
										<div class="cart-info confirm">
											<table>
												<thead>
													<tr>
														<td class="image mobile_hide">图片</td>
														<td class="name">商品</td>
														<td class="quantity">数量</td>
														<td class="price mobile_hide">价格</td>
														<td class="total">总价</td>
													</tr>
												</thead>
												<tbody>
													<?php
                                                    foreach($ret['goods_list'] as $goods){
                                                        echo '<tr>
                                                            <td class="image mobile_hide">
                                                                <a href="?goodsDetail/view/goodsid/'.$goods['goodsid'].'"><img src="'.$goods['image'].'" alt="'.$goods['name'].'" title="'.$goods['name'].'">
                                                                </a>
                                                            </td>
                                                            <td class="name">
                                                            <a href="?goodsDetail/view/goodsid/'.$goods['goodsid'].'">
                                                                '.$goods['name'].'<span id="goodSpecificationStrSpan"></span>
                                                            </a>
                                                            </td>
                                                            <td class="quantity">
                                                                '.$goods['quantity'].'
                                                            </td>
                                                            <td class="unit-price mobile_hide">￥<sapn id="price">'.$goods['price'].'</sapn></td>
                                                            <td class="total">￥<span id="total_id_19348">'.$goods['goods_total_price'].'</span></td>
                                                        </tr>';
                                                    }
                                                    ?>
												</tbody>
												<tfoot class="confirm_totals price">
													<tr>
														<td class="names filler" colspan="2">运费:</td>
														<td class="filler mobile_hide"></td>
														<td class="filler mobile_hide"></td>
														<td class="amounts">￥0.00</td>
													</tr>
													<tr>
														<td class="names filler" colspan="2">总计:</td>
														<td class="filler mobile_hide"></td>
														<td class="filler mobile_hide"></td>
														<td class="amounts">￥<span id="sumAmounts"><?php echo $ret['total_price'];?></span></td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>


								<div id="terms">
									<div class="quickcheckout-content terms">
										<div class="confirm">
											<a id="button-payment-method" onclick="submitBuy()" class="btn btn-primary">确认提交订单</a>
										</div>
									</div>
								</div>

							</div>

						</div>

					</div>
				</div>
			</div>
		</div>


		<div class="clearfix footer_margin"></div>




  


    <title>My JSP 'footer.jsp' starting page</title>
    
<?php include './views/foot.php'; ?>

		<!-- footer 部分 -->
	</div>
	<!-- .outer_container ends -->
	
	<!-- Resources dont needed until page load -->
	<script type="text/javascript" src="./js/jquery.cookie.js"></script>
	
	<div id="pageDate" style="display: none;">
		<form action="?/order/pay" method="post" id="buyform" name="buyform">
            <input type="hidden" id="orderid" name="orderid" value="">
	      <input id="goodSpecificationStr" type="hidden" name="goodSpecificationStr" value="" info="选择的商品属性名称"> 
		  <input id="goodSpecificationId" type="hidden" name="goodSpecificationId" value="" info="选择的商品属性id">
		  <input id="goodSpecificationStock" type="hidden" name="goodSpecificationStock" value="" info="规格属性库存">
		  <input id="startBuyMin" type="hidden" name="startBuyMin" value="" info="起购数量"> 
		  <input id="qgMax" type="hidden" name="qgMax" value="" info="限购数量"> 
		  <input id="fdjgList" type="hidden" name="fdjgList" value="" info="分段价"> 
		  <input id="buynum" type="hidden" name="buynum" info="购买数量,下单用"> 
		  <input id="dgsl" type="hidden" name="dgsl" value="99" info="库存"> 
		  <input id="tgjg" type="hidden" name="tgjg" value="66.0" info="单价"> 
		  <input id="itemid" name="cid" type="hidden" value="19348" info="商品id">
		  <input id="receiveId" name="receiveId" type="hidden" value="" info="地址Id">
		  <input id="payment_method" name="payment_method" type="hidden" value="" info="付款方式">
		  <input id="receiveIdCard" name="receiveIdCard" type="hidden" value="" info="身份证号码">
		    <input id="receiveName_address" name="receiveName" type="hidden" value="" info="收货人姓名">
		    <input id="fileUrlStr" name="fileUrlStr" type="hidden" value="" info="身份证正反面照">
		    
            <input id="couponid" name="couponid" type="hidden" value="" info="优惠券面额">
            <input id="privince" name="orderpo.province" type="hidden" value="" info="省">
            <input id="city" name="orderpo.city" type="hidden" value="" info="市">
            <input id="area" name="orderpo.area" type="hidden" value="" info="区">
            <input id="recvaddr" name="orderpo.receivedderss" type="hidden" value="" info="收货地址">
            <input id="recvname" name="orderpo.consigneeName" type="hidden" value="" info="收货人">
            <input id="moblie" name="orderpo.mobile" type="hidden" value="" info="手机号">
            <input id="phone" name="orderpo.phone" type="hidden" value="" info="电话号码">
            <input id="postcode" name="orderpo.postCode" type="hidden" value="" info="邮政编码">
			<input id="info" name="liuyan" type="hidden" value="" info="留言">
		  <input type="hidden" name="webwork.token.name" value="webwork.token">
<input type="hidden" name="webwork.token" value="CDHYSROEELSSJTZIAPF6HVPXAK9LK0O">

		</form>
	</div>
	<!--<div class="mask" style="position:fixed;width:100%;height:100%;left:0;top:0;z-index:998;background: #333333;opacity:0.8;display:none;"></div>
	--><!--<div id="tip-id" style="width:380px;height:200px;position:fixed;left:50%;margin:-100px 0 0 -190px;top:50%;z-index:999;
	background:#fff;border-radius: 3px;padding:15px;display:none;">
		<div style="position:relative;">
			<div id="cboxClose" class="close" style="right:0;top:0;" onclick="$('#tip-id,.mask').fadeOut()"></div>
			<h4 style="height: 30px;border-bottom: 1px solid #EDEDED;">温馨提示</h4>
			<div style="font-size:14px;line-height: 22px;padding: 10px 22px 3px 0px;color: #666;">您还没有完善您的身份信息,海外寄件需要提供真实的姓名和身份证号码,请谅解!</div>
			<div style="text-align: center;margin-top:15px;"><a href="userInfoManageAction!edit.go?anchor=anchor" class="btn btn-primary" style="color:#fff!important;">去完善您的资料</a></div>
		</div>
	</div>

-->
<div id="cboxOverlay" style="display: none;background:#333333;opacity:0.7">
	<div class="overlaytext" style="position:absolute;left:50%;top:40%;width:145px;margin-left:-72px;font-size:17px;">
		<div style="text-align:center;margin-bottom: 5px;"><img src="./image/loader10.gif"></div>
		<div style="color:#ffffff;width:168px;">处理中，请稍候...</div>
	</div>
</div>
<script>
function overlay(){
	$('#cboxOverlay').show();
}
</script>
 <!-- 右侧客服浮窗 -->
<link rel="stylesheet" href="./css/fix.css">
<script charset="utf-8" src="./image/wpa.php"></script>

<script>
	$(function(){
		$('#fix').fadeIn();
		initShare();
	})
	
	function initShare(){
		window._bd_share_config = {
			common : {
				bdText : "叮叮网 - 进口商品限时抢购",
				bdDesc : "叮叮网 - 进口商品限时抢购，全网最低，数量有限，先到先得，过时不候！",	
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

<div id="cboxOverlay" style="display: none;"></div>
<div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;"><div id="cboxWrapper">
<div>
<div id="cboxTopLeft" style="float: left;"></div>
<div id="cboxTopCenter" style="float: left;"></div>
<div id="cboxTopRight" style="float: left;"></div></div>
<div style="clear: left;"><div id="cboxMiddleLeft" style="float: left;"></div>
<div id="cboxContent" style="float: left;">
<div id="cboxTitle" style="float: left;"></div>
<div id="cboxCurrent" style="float: left;"></div>
<a id="cboxPrevious"></a><a id="cboxNext"></a><button id="cboxSlideshow"></button>
<div id="cboxLoadingOverlay" style="float: left;"></div>
<div id="cboxLoadingGraphic" style="float: left;"></div></div
<div id="cboxMiddleRight" style="float: left;"></div></div><div style="clear: left;"><div id="cboxBottomLeft" style="float: left;"></div>
<div id="cboxBottomCenter" style="float: left;"></div>
<div id="cboxBottomRight" style="float: left;"></div></div></div>
<div style="position: absolute; width: 9999px; visibility: hidden; display: none; max-width: none;"></div></div>
</body></html>