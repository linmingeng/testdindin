$(function(){	
    var swiper = new Swiper('.swiper-container', {
    	effect:'fade', 
    	autoplay: 3000, 
    	speed:300,
        pagination: '.swiper-pagination',
        autoplayDisableOnInteraction : false,
        paginationClickable: true,
        autoHeight: true, 
        grabCursor : true,
        onSlideChangeStart: function(swiper){
	      var index = swiper.activeIndex;
	      if(index==0){	    	  
	    	  $('#banner').css('background','#fe4481');
	      }else if(index==1){	    	  
	    	  $('#banner').css('background','#6CB4EF');
	      }else if(index==2){//1	    	  
	    	  $('#banner').css('background','#feda7c');
	      }else if(index==3){
	    	  $('#banner').css('background','#37b4ee');
	      }else if(index==4){//2
	    	  $('#banner').css('background','#FF5D5B');
	      }else if(index==5){//3
	    	  $('#banner').css('background','#c9f4d8');
	      }else if(index==6){//4
	    	  $('#banner').css('background','#F6E7E4');
	      }
	    }
    });
	checkLang();	
	news_tab();
	news_scol();
	$(".action-right li").on('click',function(){action($(this));});	
})


//****************判断终端**********************/
function checkLang(){
  var oMobile = /iphone|ipod|ipad|android|blackberry|opera|mini|smartphone|iemobile/i.test(navigator.userAgent.toLowerCase());
    if (oMobile){
    	$("#fixed-nav-left,#fixed-hot").hide();    	
    	$(".header").css({'position':'fixed','top':0,'left':0,'background':'#fff','z-index':45});
    	$(".nav-left").css({'position':'absolute','top':'0','left':'-222px','background':'#fff','margin-right':0,'z-index':48,});   
    	$(".nav-center").css({'overflow':'hidden','width':'100%'});
    	$(".nav-right").css({'margin-left':'50px'});  
    	nav_show1(); 	
    	// banner_touch();
    	hiddenfix();


    	$(window).resize(function(){
			nav_show();
			hiddenfix();			
		})

    	/**************************获取头部高赋给main的绝对定位 top值**************/
		function hiddenfix(){
			var hearderH=$(".header").height()+'px';
			$("#banner").css({'margin-top':hearderH});
		}

		/***********菜单显示*************/
		function nav_show1(){
			var BodyWidth=$(window).width();
				$("#nav-p a").on('touchstart',function(){				
					if($(this).hasClass('on')){
						$(this).removeClass('on');
						$(".nav-left").css({'height':'43px','overflow':' hidden','margin-left':'0'});
						$(".nav-center").css({'overflow':'hidden'});
					}else{
						$(this).addClass('on');
						$(".nav-left").css({'height':'auto','overflow':' visible','margin-left':'220px'});
						$(".nav-center").css({'overflow':'visible'})
					}					
				})
		    };
    }else{
  //   	banner();
		// $("#banner-list img").mousemove(function(){
		// 	clearInterval(tim);
		// });
		// $("#banner-list img").mouseout(function(){
		// 	tim=setInterval(banner_paly,2000);
		// });
		soltop = $(window).scrollTop();	

		fix_nav_show("#fixed-nav-left","#fixed-hot");
		$("#fix-hot-btn").on('click',function(){fix_hot($(this));});		
		navMeroWidth($(".menu-meor-box"),$(".center").eq(0));
		nav_show();

		$(window).resize(function(){			
			navMeroWidth($(".menu-meor-box"),$(".center").eq(0));
		})


		$(window).scroll(function(){
			soltop = $(window).scrollTop();	
			fix_nav_show("#fixed-nav-left","#fixed-hot");	
			fix_nav_on("#pro-box .pro","#fixed-nav li");
		})

		/*****悬浮 菜单 点击平滑滚动*******/
		$("#fixed-nav li").on('click',function(){
			var i=$(this).index();
			if(i==$("#fixed-nav li").length-1){		
				$("html,body").animate({scrollTop:0},500);	
				$("#fixed-nav li").removeClass('on');
			}else{
				var this_top= $(".pro").eq(i).offset().top;	
				$("html,body").animate({scrollTop:this_top},500);
			}
		});

		/*****悬浮 彩单 滚动距离添加当前高亮******/
		function fix_nav_on(pro,fix_nav){
		    $(pro).each(function(){
		    	var x=$(this).index(),this_top=$(this).offset().top-100;
		    	if(soltop>=this_top){
		    	$(fix_nav).eq(x).addClass('on').siblings().removeClass('on');
		    	}    	
		    })
		};

		/*****悬浮 彩单 滚动距离显隐*******/
		function fix_nav_show(fix_nav,fixed_hot){
			$(fix_nav).hide();
			var box_main=$("#main").offset().top;
		    var winWidth=$(window).width();	
		    if(winWidth>=700 && soltop>=box_main){
		    	$(fix_nav).show();
				$(fixed_hot).show();
			}else{	
				$(fix_nav).hide();
				$(fixed_hot).hide();
			}
		};

		/*****悬浮 热卖显隐*******/
		function fix_hot(obj){
			var fixed_hot=$(".fixed-nav-right").eq(0);	
			if(!obj.hasClass("on")){
				obj.addClass('on').css({'left':'-20px'});	
				fixed_hot.animate({'right':'-120px'},100);
				obj.text("+");
			}else{
				obj.removeClass('on').css({'left':'0'});	
				fixed_hot.animate({'right':'0'},400);
				obj.text("-");
			};
		}


		/************菜单宽度计算*************/
		function navMeroWidth(nav_obj,ocenter){
			var w=ocenter.width();
			 	w1=ocenter.width()-468+'px';
			 	w2=ocenter.width()-268+'px';
			 	w3=ocenter.width()-50+'px';
				if(w<=1199){
					nav_obj.css({'width':w2});
				}else if(w<1000){
					nav_obj.css({'width':w3});
				}else{
					nav_obj.css({'width':w1});
				} 
		}

		/***********菜单显示*************/
		function nav_show(){
			var BodyWidth=$(window).width();
			if(BodyWidth<=1200){
				$(".nav-left").mousemove(function(){
					$(this).css({'height':'auto','overflow':' visible'});
				})
				$(".nav-left").mouseleave(function(){
					$(this).css({'height':'45px','overflow':'hidden'});
				})
				nav_meor();
			}else if (BodyWidth<=850) {

			}else{
				nav_meor();
			}
		};


		//****************菜单更多显示**********************/
		function nav_meor(){
			$('#nav-menu li').each(function(index){
				$(this).mouseover(function(){
					$(this).addClass('on').siblings('li').removeClass('on');
					$("#menu-meor-box").show(100);
					$("#menu-meor-box .menu-meor").hide().eq(index).show();
				});
			})
			$(".nav-left").mouseleave(function(){
				$("#menu-meor-box").hide();
				$('#nav-menu li').removeClass('on');
			})	
		}

	}
}


/*****头部下拉******/
function action(action_obj){
	if(!action_obj.hasClass("on")){
		action_obj.addClass('on').siblings('li').removeClass("on");
	}else{
		action_obj.removeClass("on");
	}
}

/************banner切换**************/
var tim;
var bannerList;
var bannerBtn;
i=0;
// function banner(){
// 	bannerList=$("#banner-list li");	
// 	for(var x=0; x<bannerList.length; x++){
// 		if(x==0){
// 			var str='<a class="on" href="javascript:void(0);"></a>';
// 		}else{
// 			var str='<a href="javascript:void(0);"></a>';
// 		}		
// 		$("#banner-btn").append(str);
// 		bannerBtn=$("#banner-btn a");	
// 		bannerBtn.mousemove(function(event) {
// 			var thisIndex=$(this).index();
// 			i=	thisIndex;		
// 			$(this).addClass('on').siblings('a').removeClass('on');
// 			bannerList.eq(thisIndex).addClass('on').siblings('li').removeClass('on');
// 		});
// 	}
	
// 	bannerList.eq(i).addClass('on');	
// 	tim=setInterval(banner_paly,2000);
// }

// function banner_touch(){
// 	bannerList=$("#banner-list li");
// 	for(var x=0; x<bannerList.length; x++){
// 		if(x==0){
// 			var str='<a class="on" href="javascript:void(0);"></a>';
// 		}else{
// 			var str='<a href="javascript:void(0);"></a>';
// 		}		
// 		$("#banner-btn").append(str);
// 		bannerBtn=$("#banner-btn a");	
// 		bannerBtn.on('touchstart',function(event) {
// 			var thisIndex=$(this).index();
// 			i=thisIndex;
// 			$(this).addClass('on').siblings('a').removeClass('on');
// 			bannerList.eq(thisIndex).addClass('on').siblings('li').removeClass('on');
// 		});
// 	}
	
// 	bannerList.eq(i).addClass('on');
// 	tim=setInterval(banner_paly,5000);
	
// 	bannerList.find('a').find('img').eq(0).on('touchmove',function(event){		
// 		var thisIndex=$(this).parent().parent('li').index();
// 		var touch= e.touch[0];
//         if(touch.pageX>10){
//            i=thisIndex+1;
//         }
//         if(touch.pageX<-10){
//            i=thisIndex-1;
//         }	
// 	}); 
// }

// function banner_paly(){	
// 		bannerList=$("#banner-list li");	
// 		bannerBtn=$("#banner-btn a");
// 		if(i<bannerList.length-1){
// 			i=i+1;	
// 			bannerList.eq(i).addClass('on').siblings('li').removeClass('on');
// 			bannerBtn.eq(i).addClass('on').siblings('a').removeClass('on');				
// 		}else{
// 			i=0;
// 			bannerList.eq(i).addClass('on').siblings('li').removeClass('on');
// 			bannerBtn.eq(i).addClass('on').siblings('a').removeClass('on');
// 		}
// 		// console.log(i);
// 	};

//新闻切换
function news_tab(){
	var news_btn=$("#index-news-btn a");
	var news_box=$("#index-news-list div");
		news_btn.mouseover(function(event) {
		thisIndex=$(this).index();
		$(this).addClass('on').siblings('a').removeClass('on');
		news_box.eq(thisIndex).addClass('on').siblings('div').removeClass('on');
	});
}

//交易滚动
var tradingTim;
function news_scol(){   
	tradingTop=0;
	trading=$("#trading");
	var trading_box=("#trading-box");
	tradingHeight=trading.height();
	tradingTim=setInterval(trading_paly,150);
	

}
function trading_paly(){
	tradingTop=tradingTop-2;
	if(-(tradingTop)>tradingHeight-165){
		tradingTop=0;
		trading.css({'top':tradingTop+'px'});
	}else{
		trading.css({'top':tradingTop+'px'});
	}
}

$("#trading").mousemove(function(){
  clearInterval(tradingTim);
});

$("#trading").mouseout(function(){
 	tradingTim=setInterval(trading_paly,100);
});
// /**************************获取头部高赋给main的绝对定位 top值**************/
// function hiddenfix(){
// 	var hearderH=$(".header").height()+'px';
// 	$("#banner").css({'margin-top':hearderH});
// }

// /***获取banner图片高 设置父级元素高度 达到自适应***/
// function banner_h(obj){
// 	//获取banner第一个li元素 并赋值给变量
// 	var dom=$(obj).find('li').eq(0);
// 	//获取第一个li元素里的图片高 并赋值给变量
// 	var b_h=dom.find("img").height()+'px';
// 	//将获取到的高度赋给li元素父级
// 	$(obj).css({'height':b_h});
// }


// /*****悬浮 菜单 点击平滑滚动*******/
// $("#fixed-nav li").on('click',function(){
// 	var i=$(this).index();
// 	if(i==$("#fixed-nav li").length-1){		
// 		$("html,body").animate({scrollTop:0},500);	
// 		$("#fixed-nav li").removeClass('on');
// 	}else{
// 		var this_top= $(".pro").eq(i).offset().top;	
// 		$("html,body").animate({scrollTop:this_top},500);
// 	}
// });

// /*****悬浮 彩单 滚动距离添加当前高亮******/
// function fix_nav_on(pro,fix_nav){
//     $(pro).each(function(){
//     	var x=$(this).index(),this_top=$(this).offset().top-100;
//     	if(soltop>=this_top){
//     	$(fix_nav).eq(x).addClass('on').siblings().removeClass('on');
//     	}    	
//     })
// };

// /*****悬浮 彩单 滚动距离显隐*******/
// function fix_nav_show(fix_nav,fixed_hot){
// 	$(fix_nav).hide();
// 	var box_main=$("#main").offset().top;
//     var winWidth=$(window).width();	
//     if(winWidth>=700 && soltop>=box_main){
//     	$(fix_nav).show();
// 		$(fixed_hot).show();
// 	}else{	
// 		$(fix_nav).hide();
// 		$(fixed_hot).hide();
// 	}
// };

// /*****悬浮 热卖显隐*******/
// function fix_hot(obj){
// 	var fixed_hot=$(".fixed-nav-right").eq(0);	
// 	if(!obj.hasClass("on")){
// 		obj.addClass('on').css({'left':'-20px'});	
// 		fixed_hot.animate({'right':'20px'},100);
// 		obj.text("+");
// 	}else{
// 		obj.removeClass('on').css({'left':'0'});	
// 		fixed_hot.animate({'right':'140px'},400);
// 		obj.text("-");
// 	};
// }

// /*****头部下拉******/
// function action(action_obj){
// 	if(!action_obj.hasClass("on")){
// 		action_obj.addClass('on').siblings('li').removeClass("on");
// 	}else{
// 		action_obj.removeClass("on");
// 	}

// }

// /************菜单宽度计算*************/
// function navMeroWidth(nav_obj,ocenter){
// 	var w=ocenter.width();
// 	 	w1=ocenter.width()-468+'px';
// 	 	w2=ocenter.width()-268+'px';
// 	 	w3=ocenter.width()-50+'px';
// 		if(w<=1199){
// 			nav_obj.css({'width':w2});
// 		}else if(w<1000){
// 			nav_obj.css({'width':w3});
// 		}else{
// 			nav_obj.css({'width':w1});
// 		} 
// }

// /***********菜单显示*************/
// function nav_show(){
// 	var BodyWidth=$(window).width();
// 	if(BodyWidth<=1200){
// 		// $("#nav-p").click(function(){
// 		// 	if($(this).hasClass('on')){
// 		// 		$(this).removeClass('on');
// 		// 		$(this).parent('div').css({'height':'45px','overflow':'hidden'});
// 		// 	}else{
// 		// 		$(this).addClass('on');
// 		// 		$(this).parent('div').css({'height':'auto','overflow':' visible'});
// 		// 	}
// 		// });
// 		// 
// 		$(".nav-left").mousemove(function(){
// 			$(this).css({'height':'auto','overflow':' visible'});
// 		})
// 		$(".nav-left").mouseleave(function(){
// 			$(this).css({'height':'45px','overflow':'hidden'});
// 		})
// 		nav_meor();
// 	}else if (BodyWidth<=850) {

// 	}else{
// 		nav_meor();
// 	}
// };


// //****************菜单更多显示**********************/
// function nav_meor(){
// 	$('#nav-menu li').each(function(index){
// 		$(this).mouseover(function(){
// 			$(this).addClass('on').siblings('li').removeClass('on');
// 			$("#menu-meor-box").show(100);
// 			$("#menu-meor-box .menu-meor").hide().eq(index).show();
// 		});
// 	})
// 	$(".nav-left").mouseleave(function(){
// 		$("#menu-meor-box").hide();
// 		$('#nav-menu li').removeClass('on');
// 	})	
// }






































