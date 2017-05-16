function addressSubmit() {
	if (checkData()) {
		$(addForm).submit();
	}
}
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
		
		if(!checkIdCard()){
			$(this).next().show();
			$('#idCard').addClass("errorRed");
			return;
		}
		$('#idCard').removeClass("errorRed");
	}
	
	
	
	return true;
}

$(document).ready(function(){
	
	/**
	 *  身份证失去焦点
	 */
	$('#idCard').blur(function(){
		
		var idCard = $(this).val();
		if($.trim(idCard)==''){
			
			$(this).addClass("errorRed");
			return false;
		}
		if(!checkIdCard()){
			$(this).addClass("errorRed");
			$(this).next().show();
			return false;
		}
		$(this).next().hide();
		$(this).removeClass("errorRed");
	});
	
	/**
	 *  收货人姓名
	 */
	$('#receiveName').blur(function(){
		
		var idCard = $(this).val();
		if($.trim(idCard)==''){
			
			$(this).addClass("errorRed");
			return false;
		}
		$(this).removeClass("errorRed");
	});
	
	
	/**
	 *  手机号码
	 */
	$('#receiveMobile').blur(function(){
		
		var idCard = $(this).val();
		if($.trim(idCard)==''){
			
			$(this).addClass("errorRed");
			return false;
		}
		$(this).removeClass("errorRed");
	});
	
	
	/**
	 *  收货地址
	 */
	$('#receiveAddr').blur(function(){
		
		var idCard = $(this).val();
		if($.trim(idCard)==''){
			
			$(this).addClass("errorRed");
			return false;
		}
		$(this).removeClass("errorRed");
	});
	
	
	/**
	 *  邮编
	 */
	$('#receivePostCode').blur(function(){
		
		var idCard = $(this).val();
		if($.trim(idCard)==''){
			
			$(this).addClass("errorRed");
			return false;
		}
		$(this).removeClass("errorRed");
	});
	
	
});

/**
 * 检查身份证
 */
function checkIdCard(){
	var idCard = $("#idCard").val();
	var isValid = false;
	$.ajax({
		   type: "POST",
		   url: "receiveAddressAction!checkIdCard.go",
		   data: "po.idCard="+idCard,
		   async: false,
		   dataType:'json',
		   success: function(data){
				
				if(data.result==1){
					
					isValid= false;
				}else{
					
					isValid = true;
				}
		   }
	});
	return isValid;
	
}
