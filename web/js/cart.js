$(function() {
	//同步购物车数据
    var cart = $.cookie('cart');
    var cart_info = eval('('+cart+')');
    var count = 0;
    for(goodsid in cart_info){
        var qua = parseInt(cart_info[goodsid]);
        count = count+qua;
    }
    $('.count').html(count);
    //加载目录
    // $.ajax({
    //     type: "POST",
    //     url: "?/index/menu",
    //     data: {ajax:1},
    //     async: false,
    //     dataType:'json',
    //     success: function(data){
    //         html = '';
    //         if(data.code == 200){
    //             delete data.code;
    //             for(key in data.all_groups){
    //                 var group = data.all_groups[key];
    //                 html +='<li class="top has_sub top first-cate-li"><a class="sub_trigger first-cate" onclick="showMenu(this)" id="First_600">';
    //                 html += group.name;
    //                 html += '<i class="fa fa-sort-desc"></i></a><div class="wrapper" style=" width:630px"> <div class="row"> <div class="col-sm-6 mobile-enabled"> <div class="row"> <div class="col-sm-12 hover-menu"> <div class="menu"> <ul>';
    //                 if(group.sub_groups.length >0){
    //                     for(ke in group.sub_groups){
    //                         var sub_group = group.sub_groups[ke];
    //                         html += '<li class=""><a href="?/goods/search/sub_groupid/';
    //                         html += sub_group.sub_groupid;
    //                         html += '" class="main-menu sec-cate" id="second_945">';
    //                         html += sub_group.name;
    //                         html += '</a><ul class="three-cate" style="color:#666;padding-left:30px;">';
    //                         if(group.sub_groups.length >0) {
    //                             for (k in sub_group.end_groups) {
    //                                 var end_group = sub_group.end_groups[k];
    //                                 html += '<li><a href="?/goods/search/end_groupid/';
    //                                 html += end_group.end_groupid;
    //                                 html += '" id="'+end_group.end_groupid+'">';
    //                                 html += end_group.name;
    //                                 html += '</a></li>';
    //                             }
    //                         }
    //                         html += '</ul></li>';
    //                     }
    //                 }
    //                 html += ' </ul> </div> </div> </div> </div> <div class="col-sm-6 mobile-enabled"><a href="/goodsDetail_id_19276.htm"><img src="./image/ct1.jpg" width="100%"></a> </div> </div> </div> </li>';
    //             }
    //             $('#main-category').html(html);
    //         }
    //     }
    // });
});





































