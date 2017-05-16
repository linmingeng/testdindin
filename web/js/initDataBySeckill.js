$(function(){
	initSeckill();
})
var haomiao = 1000;
function initSeckill(html){
	
	$.ajax({
		async : true,
		type : 'POST',
		dataType : "json",
		url : 'seckillAction!initSeckillData.go',
		beforeSend: function(){
			var str = '<div style="position:absolute;left:50%;top:50%;margin:-17px 0 0 -17px!important;"><img src="./images/loader6.gif" ></div>';
		 	$("#seckillPlan").html(str);
		},
		success : function(data) {
			if(data!=null){
				var serverTime =  data.serverTime;
				var xsqgBeginTime =  data.xgBeginTime;
				var xsqgEndTime =  data.xgEndTime;
				var xgList =  data.xgGoodsList;
				
				var xgHtml="";
				if(xgList.length>0){
					
					for(var i=0;i<xgList.length;i++){
						var ars = new Array();
						ars.push("goodsView_id_"+xgList[i].ids+".htm");
						ars.push("cart.add('"+xgList[i].ids+"','1','../"+xgList[i].picLink+"','other','false');");
						ars.push("goodsDetail_id_"+xgList[i].ids+".htm");
						ars.push("../"+xgList[i].picLink);
						ars.push(xgList[i].itemName);
						//天
						ars.push("00");
						//小时
						ars.push("00");
						//分
						ars.push("00");
						//秒
						ars.push("00");
						ars.push("goodsDetail_id_"+xgList[i].ids+".htm");
						ars.push(xgList[i].itemName);
						var times = 0;
						
						//尚未开始抢购
						if(serverTime<xsqgBeginTime){
							ars.push(xgList[i].itemPrice);
							ars.push(xgList[i].marketPrice);
							ars.push(xgList[i].discount);
							ars.push(xgList[i].itemName);
							ars.push("距开始仅剩");
							times = xsqgBeginTime - serverTime;
							if(i==0){
								
								setInterval(function(){
								
								times = times -1*haomiao; //秒数减1
								spKillTimeFun(times,'N');
							},haomiao);
							
							}
							
						}else{//已经开始抢购了
							ars.push(xgList[i].marketPrice);
							ars.push(xgList[i].itemPrice);
							ars.push(xgList[i].discount);
							ars.push(xgList[i].itemName);
							ars.push("距结束仅剩");
							times = xsqgEndTime - serverTime;
							if(i==0){
								
								setInterval(function(){
								times = times -1*haomiao; //秒数减1
								spKillTimeFun(times,'Y');
								
							},haomiao);
							}
						}
							
							if(xgList[i].ycdNationalFlag!=''){
								
								//国家国旗 
								ars.push("<img src=\"./"+xgList[i].ycdNationalFlag+"\" alt=\""+xgList[i].ycdName+"\"  style=\"width:36px;height:auto;\">");xgList[i].ycdNationalFlag
							}else{
								
								ars.push('');
							}
							
							//设置原产地
							/*if('7400102'==xgList[i].ycdId){//美国
								
							
							}else if("7200102"==xgList[i].ycdId){//德国
								
								//国家国旗 ./images/countryImg/deguo.jpg
								ars.push("<img src=\"./images/countryImg/deguo.jpg\" alt=\"德国\" style=\"width:36px;height:auto;\">");
							}else if("7100102"==xgList[i].ycdId){//韩国
								
								//国家国旗 ./images/countryImg/deguo.jpg
								ars.push("<img src=\"./images/countryImg/hanguo.jpg\" alt=\"韩国\" style=\"width:36px;height:auto;\">");
							}else{
								
								//国家国旗 ./images/countryImg/deguo.jpg
								ars.push('');
							}*/
						xgHtml+=getHtml(ars);
					}
					$("#seckillPlan").html(xgHtml);
				}else{
				$("#seckillPlan").html('<div style="text-align:center;font-size:14px;color:#999;height:80px;line-height:80px;">暂无抢购商品！</div>');
			}
				
			}
			$('.owl-carousel').owlCarousel({
				      loop:true,
				      margin:10,
				      nav:true,
				      dots:false,
				      responsive:{
				          0:{
				              items:1
				          },
				          600:{
				              items:3
				          },
				          1000:{
				              items:5
				          }
				      }
				  });
			if($(".quickview").colorbox!=undefined){
					$(".quickview").colorbox({
						iframe:true,
						width:900,
						maxWidth:"90%",
						height:705,
						maxHeight:"90%",
						className: "quickview",
						onClosed: function() {
						}
					});
				}
		}
	})
};

function getHtml(arg){
	var template = "<div class=\"item\">"
	   +"<div class=\"image_wrap\"> "
	   +"<div class=\"btn-holder top\">" 
	   /*+"<a class=\"btn btn-icon wishlist\" onclick=\"wishlist.add('59');\" data-toggle=\"tooltip\" data-placement=\"right\" data-original-title=\"Add to Wish List\"><i class=\"fa fa-heart\"></i></a>"*/ 
	   /*+"<a class=\"btn btn-icon compare\" onclick=\"compare.add('59');\" data-toggle=\"tooltip\" data-original-title=\"Compare this Product\"><i class=\"fa fa-star\"></i></a>"*/ 
	   +"<div class=\"centered\">" 
	   +"<div class=\"centered_cell\">" 
	   +"<a class=\"btn btn-dark qlook quickview cboxElement\" href=\"{0}\" data-toggle=\"tooltip\" data-original-title=\"预览\"><i class=\"fa fa-eye\"></i><span>预览</span></a>" 
	   +"<span class=\"style-4-break\"></span>" 
	   +"<a class=\"btn btn-primary cart\" onclick=\"{1}\" data-toggle=\"tooltip\" data-placement=\"left\"  data-original-title=\"加入购物车\"><i class=\"icon-basket\"></i><span>加入购物车</span></a>" 
	   +"</div>" 
	   +"</div>" 
	   +"</div>" 
	   +"<div class=\"image\">" 
	   +"<div class=\"product-guoqi\" style=\"position:absolute;right:-22px;top:0;z-index: 11111;\">{16}</div>"
	   +"<div class=\"discount\" style=\"display:\">{13}</div>" 
		   +"<a href=\"{2}\"><img src=\"{3}\" alt=\"{4}\" /></a>" 
			   +"</div>"
			   +"</div>"
			   +"<div class=\"details_wrap\">" 
			   +"<div class=\"information_wrapper\">" 
			   +"<div class=\"seckill-time\">" 
			   +"<div style=\"color:#757575;margin-bottom:5px;\">{15}</div>"
			   +"<span class=\"times\"><span>0</span><span>0</span> : <span>0</span><span>0</span> : <span>0</span><span>0</span></span>"
			   +"</div>" 
			   +"<div style=\"padding-left:0;\" class=\"name nameEllipsis\">"
			   +"<a href=\"{9}\" title=\"{14}\">{10}</a>"
				   +"</div>"
				   +"<div style=\"padding-left:0;\" class=\"price_rating_table\">" 
				   +"<div style=\"text-align:left;font-size: 18px;\" class=\"price\">" 
				   +"<span class=\"price-old\">&yen;{11}</span> " 
				   +"<span class=\"price-new\">&yen;{12}</span>" 
				   +"</div>" 
				   +"</div>"
				   +"</div>"
				   +"</div>"
				   +"</div>";
	
	return template.format(arg[0],arg[1],arg[2],arg[3],arg[4],arg[5],arg[6],arg[7],arg[8],arg[9],arg[10],arg[11],arg[12],arg[13],arg[14],arg[15],arg[16]);

};

//限时抢购倒计时
function getKeepTime(begin,end){
	var times = 0;//总时间
	var date = new Date();
	var type = "start";
	var time = date.getTime()/1000;
	if(begin < time){
		type = "end";
		times = end - time;
	}else{
		times = begin - time;
	}
	var day=Math.floor(times/(60*60*24));
	var hour=Math.floor((times-day*24*60*60)/3600); 
	var minute=Math.floor((times-day*24*60*60-hour*3600)/60); 
	var second=Math.floor(times-day*24*60*60-hour*3600-minute*60);
	$('.time-list>div').eq(0).find('p').text(day>9?day.toString():'0'+day);
	$('.time-list>div').eq(1).find('p').text(hour>9?hour.toString():'0'+hour);
	$('.time-list>div').eq(2).find('p').text(minute>9?minute.toString():'0'+minute);
	$('.time-list>div').eq(3).find('p').text(second>9?second.toString():'0'+second);
	window.setInterval(function(){
			if(times>0){
				times = times -1; //秒数减1
				var day=Math.floor(times/(60*60*24));
				var hour=Math.floor((times-day*24*60*60)/3600); 
				var minute=Math.floor((times-day*24*60*60-hour*3600)/60); 
				var second=Math.floor(times-day*24*60*60-hour*3600-minute*60);
				$('.time-list').each(function(){
	                $(this).find('div').eq(0).find('p').text(day>9?day.toString():'0'+day);
	                $(this).find('div').eq(1).find('p').text(hour>9?hour.toString():'0'+hour);
	                $(this).find('div').eq(2).find('p').text(minute>9?minute.toString():'0'+minute);
	                $(this).find('div').eq(3).find('p').text(second>9?second.toString():'0'+second);
	            })    
				
			}
			if(times == 0 ){ //倒计时结束
				var nowTime = new Date(); 
				nowTime =nowTime.getFullYear()+ "/"+(nowTime.getMonth()+1)+"/"+nowTime.getDate()+" "+nowTime.getHours()+":"+nowTime.getMinutes()+":"+nowTime.getSeconds();
				var nowTimes = (new Date(nowTime)).getTime()/1000; //得到当前服务器时间秒数
				if(type="start"){
					var begin = begin;
					var end = end;
					times = parseInt(end,10)-parseInt(begin,10);
					type = "end";
//					$("#qgStr").html("距离结束还有：");
				}else{
					
				}
			}
		//}
	},1000);
	//倒计时 End
}

/**
 * 模板参数转换
 * @returns 
 * 			替换后完整的字段
 * @author ljy
 */
String.prototype.format=function()
{
  if(arguments.length==0) return this;
  for(var s=this, i=0; i<arguments.length; i++)
    s=s.replace(new RegExp("\\{"+i+"\\}","g"), arguments[i]);
  return s;
};

var spKillTimeFun = function(times,type){
	//alert(times);
	//second--;
	//$('.seckill-haomiao').text(haomiao>9?haomiao.toString():'0'+haomiao);
	//if(haomiao==0){ //毫秒数完了秒数减
		//haomiao = 99;
		
		if(times>0){
			var day=Math.floor(times/(60*60*24*1000));
			var hour=Math.floor((times-day*24*60*60*1000)/3600000); 
			var minute=Math.floor((times-day*24*60*60*1000-hour*3600*1000)/60000); 
			var second=Math.floor((times-day*24*60*60*1000-hour*3600*1000-minute*60*1000)/1000);
			//毫秒
			var misSeccond=Math.floor((times-day*24*60*60*1000-hour*3600*1000-minute*60*1000-second*1000)/100);
			/*$('.time-list>div').eq(0).find('p').text(day>9?day.toString():'0'+day);
			$('.time-list>div').eq(1).find('p').text(hour>9?hour.toString():'0'+hour);
			$('.time-list>div').eq(2).find('p').text(minute>9?minute.toString():'0'+minute);
			$('.time-list>div').eq(3).find('p').text(second>9?second.toString():'0'+second);	
			$('.time-list>div').eq(4).find('p').text('0'+misSeccond);*/	
			$(".times").html(formatSpan(hour)+" : "+formatSpan(minute)+" :  "+formatSpan(second)/*+" :   "+formatSpan(misSeccond)*/);
			/*<span>0</span>
	  			<span>0</span>
	  			:
	  			<span>0</span>
	  			<span>0</span>
	  			:
	  			<span>0</span>
	  			<span>0</span>
	  			:
	  			<span>0</span>
	  			<span>0</span>*/
			if("N"==type){
				$(".seckill-time .times span").css("background-color","#b0b0b0");
			}else{
				$(".seckill-time .times span").css("background-color","#003044");
			}
		}else{
			
			if(!isNaN(times)){
				setTimeout(function(){
					window.location.reload();
				},2000); 
				
			}
			
		}
	//}
}

function formatSpan(time){
	var timeStr = "";
	if(time>9){
		timeStr = "<span>"+time.toString().substring(0,1)+"</span><span>"+time.toString().substring(1,2)+"</span>";
	}else{
		timeStr = "<span>0</span><span>"+time+"</span>";
	}
	return timeStr;
}