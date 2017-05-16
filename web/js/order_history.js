$(document).ready(function(){
    var tobody = "allOrder";
    var pageDiv = "itemList_pagebar";
    var pagebarWrap = "pagebarWrap";
    var data = "page=1&status=-1";
    initAllOrders(data,tobody,pageDiv,pagebarWrap);

    //单个复选框 选择
    $('#ordersByPay').delegate("input[type=checkbox]",'click',function(){
        if(this.checked){

            $("#megerForm").append(getInputHtml(this .value));
        }else{

            $("#"+this.value).remove();
        }

    });

    $("a[class=megerPay]").click(function(){
        var checkedLength = $('#ordersByPay input[type=checkbox]:checked').length;
        if(checkedLength>0){
            if(confirm("确定要合并付款？")){

                $("a[class=megerPay]").attr("disabled","disabled");
                megerForm.submit();
            }
        }else{

            alert("请选择需要合并付款的订单！");
        }
    });

    $('#payCk_1,#payCk_2').click(function(){

        var isCheck = this.checked;
        $("#megerForm").html("");
        $('#ordersByPay input[type=checkbox]').each(function(i,o){

            o.checked = isCheck;
            if(isCheck){

                $("#megerForm").append(getInputHtml(o.value));
            }

        });

        //为兼容火狐
        document.getElementById("payCk_1").checked=isCheck;
        document.getElementById("payCk_2").checked=isCheck;
		/*$('#payCk_1,#payCk_2').attr("checked",isCheck);*/
    });



});

function getInputHtml(orderId){

    return '<input type="hidden" name="oids" value="'+orderId+'" id="'+orderId+'"/>';
}
var statusStr="";
function initAllOrders(parameter,tobody,pageDiv,pagebarWrap){

    console.log(parameter);
    $.ajax({
        async: true,
        type: "post",
        dataType: "json",
        url: "?/order/list",
        data:parameter+'&ajax=1',
        success : function(data){
            if(data.code == 200){
                //总的订单数
                var countTotal =  data.count;
                //分页总数
                var pageTotal =  data.pageTotal;
                //当前页数
                var pageIndex =  data.pageIndex;
                //如果是待支付   增加复选框
				var detail_url = '?/order/detail/orderid/';
                if(tobody=='ordersByPay'){
                    var templateStr='<tr>';
                    templateStr +='<td class="order-info" colspan="5"><div class="orderNum"><div>订单号： </div><div><em class="on">{1}</em></div></div>';
                    templateStr +='<div class="shouhuoRen"><div>收货人： </div><div><em class="on"></em></div></div>';
                    templateStr +='<div class="orderTime"><div>下单时间： </div><div><em>{14}</em></div></div>';
                    //templateStr +='<div class="order_trash"><i class="trash"></i></div>'; 删除订单
                    templateStr +='</td>';
                    templateStr +='</tr>';
                    templateStr +='<tr>';
                    templateStr +='<td><div><input type="checkbox" name="pay_checkbox" value="{15}"></div></td>';
                    templateStr +='<td><a href="?goodsDetail/view/goodsid/{2}.htm" target="_blank"><img src="../{3}" alt="{4}" title="{5}"><p class="goods-name name nameSubStr">{7}</p></a></td>';
                    templateStr +='<td>x{9}</td>';
                    templateStr +='<td align="center"><span class="active">￥{8}</span></td>';
                    templateStr +='<td class="order-status"><span>{10}</span><a href="'+detail_url+'{0}" class="active">订单详情</a></td>';
                    templateStr +='</tr><tr><td colspan="5">{12}</td></tr>';
                }else if(tobody=='allOrders'){
                    var templateStr='<tr>';
                    templateStr +='<td class="order-info" colspan="5"><div class="orderNum"><div>订单号： </div><div><em class="on">{1}</em></div></div>';
                    templateStr +='<div class="shouhuoRen"><div>收货人： </div><div><em class="on">{13}</em></div></div>';
                    templateStr +='<div class="orderTime"><div>下单时间： </div><div><em>{14}</em></div></div>';
                    //templateStr +='<div class="order_trash"><i class="trash"></i></div>'; 删除订单
                    templateStr +='</td>';
                    templateStr +='</tr>';
                    templateStr +='<tr>';
                    templateStr +='<td><div><input type="checkbox" name="pay_checkbox" value="{15}"></div></td>';
                    templateStr +='<td><a href="?goodsDetail/view/goodsid/{2}.htm" target="_blank"><img src="../{3}" alt="{4}" title="{5}"><p class="goods-name name nameSubStr">{7}</p></a></td>';
                    templateStr +='<td>x{9}</td>';
                    templateStr +='<td align="center"><span class="active">￥{8}</span></td>';
                    templateStr +='<td class="order-status"><span>{10}</span><a href="'+detail_url+'{0}" class="active">订单详情</a></td>';
                    templateStr +='</tr><tr><td colspan="5">{12}</td></tr>';
                    // templateStr +=' <div class="editBox"><a class="bt_small active edit-btn" target="_blank" href="http://www.dindin.com/buyAction!newToSelectPayPage.go?oids=71446">去付款</a><a onclick="cancelOrder(71446)" href="http://www.dindin.com/order_history.jsp#" class="edit-btn">取消订单</a></div></td>';
                }else{

                    var templateStr='<tr>';
                    templateStr +='<td class="order-info" colspan="5"><div class="orderNum"><div>订单号： </div><div><em class="on">{1}</em></div></div>';
                    templateStr +='<div class="shouhuoRen"><div>收货人： </div><div><em class="on">{13}</em></div></div>';
                    templateStr +='<div class="orderTime"><div>下单时间： </div><div><em>{14}</em></div></div>';
                    //templateStr +='<div class="order_trash"><i class="trash"></i></div>'; 删除订单
                    templateStr +='</td>';
                    templateStr +='</tr>';
                    templateStr +='<tr>';
                    templateStr +='';
                    templateStr +='<td colspan="2" style="text-align:left"><a href="?goodsDetail/view/goodsid/{2}.htm"><img src="../{3}" alt="{4}" title="{5}"><p class="goods-name name nameSubStr">{7}</p></a></td>';
                    templateStr +='<td>x{9}</td>';
                    templateStr +='<td align="center"><span class="active">￥{8}</span></td>';
                    templateStr +='<td class="order-status"><span>{10}</span><a href="'+detail_url+'{0}" class="active">订单详情</a></td>';
                    templateStr +='</tr><tr><td colspan="5">{12}</td></tr>';
                }
                //订单列表
                var list =  data.results;

                var html ="";
                for(var i=0;i<data.results.length;i++){
                    var order  = data.results[i];
                    //订单编号
                    var dealOrderId =  order.order_number;
                    //订单id
                    var dealCode =  order.orderid;
                    //实付价格
                    var dealPayFeeTotal = order.price;
                    //操作按钮
                    var operationStr =  createOperate(order);

                    var goods = order.order_goods[0];
                    var pic = goods.image;
                    var name = goods.name;
                    var goodsId =goods.goodsid;
                    var pirce = parseFloat(parseFloat(goods.price)/parseFloat(goods.quantity));
                    var num = goods.quantity;
                    var receiveName = order.name;
                    var createTime = order.add_at;
                    // var state_arr = {0:'取消订单',1:'待支付',2:'待发货',3:'已发货',4:'已收货',5:'订单完成'};
                    // var statusStr = state_arr[order.status];
                    var str = templateStr.format(dealCode,dealOrderId,goodsId,pic,name,name,goodsId,name,pirce.toFixed(2),num,statusStr,dealPayFeeTotal,operationStr,receiveName,createTime,dealCode);

                    html+=str;
                }
                $("#"+tobody).html(html);
                if($(window).width()>1200){
                    nameSubStr($('#content .history p.name.nameSubStr'),40);//限制商品名称字数
                }else if($(window).width()<767){
                    nameSubStr($('#content .history p.name.nameSubStr'),15);//限制商品名称字数
                }

                if(html!=null && html!=""){
                    loadPage(pageIndex,pageTotal,pageDiv,pagebarWrap);
                }

            }
        }
    })
}


function getOrderList(page,turnPageFlag)//获取订单列表
{
    if(turnPageFlag){
        var type = $("#orderTabs .active").text();
        if(type=="全部订单"){
            var tobody = "allOrder";
            var pageDiv = "itemList_pagebar";
            var pagebarWrap = "pagebarWrap";
            var data = "page=1&status=-1";
            initAllOrders(data,tobody,pageDiv,pagebarWrap);
        }else if(type=="待支付"){
            var data = "page=1&status=1";
            initAllOrders(data,"ordersByPay","itemList_pageByPaybar","pagebarByPayWrap");
        }else if(type=="待发货"){
            var data = "page=1&status=2";
            initAllOrders(data,"ordersByFa","itemList_pageByFabar","pagebarByFaWrap");
        }else if(type=="待收货"){
            var data = "page=1&status=3";
            initAllOrders(data, "ordersByShou", "itemList_pageByShoubar","pagebarByShouWrap");
        }else if(type=="已完成"){
            var data = "page=1&status=4";
            initAllOrders(data, "ordersBySuccess", "itemList_pageBySuccessbar","pagebarBySuccessWrap");
        }else if(type=="已退单"){
            var data = "page=1&status=0";
            initAllOrders(data, "ordersByCancel", "itemList_pageByCancelbar","pagebarByCancelWrap");
        }
    }
}

function checkLoadOrderList(o){
    var type = o;
    if(type=="ordersByPay"){
        $('.history .shop-history,.history .wrap_page').hide();
        $('#o-pay,#pagebarByPayWrap').show();
        //var data = {"pc.vo.ddzts":1,"pc.vo.goodsTypes":1,"pc.vo.paymentTypes":4};
        var data = "page=1&status=1";
        initAllOrders(data,"ordersByPay","itemList_pageByPaybar","pagebarByPayWrap");

        //清楚form数据
        $("#megerForm").html("");
        $("#o-pay input[type=checkbox]").attr("checked",false);

    }else if(type=="ordersByFa"){
        $('.history .shop-history,.history .wrap_page').hide();
        $('#o-fa,#pagebarByFaWrap').show();
        //var data = {"pc.vo.ddzts":1,"pc.vo.goodsTypes":1,"pc.vo.paymentTypes":5};
        var data = "page=1&status=2";
        initAllOrders(data,"ordersByFa","itemList_pageByFabar","pagebarByFaWrap");
    } else if (type == "ordersByShou") {
        $('.history .shop-history,.history .wrap_page').hide();
        $('#o-shou,#pagebarByShouWrap').show();
        //var data = {"pc.vo.ddzts" : 1,"pc.vo.goodsTypes" : 2,"pc.vo.paymentTypes" : ""};
        var data = "page=1&status=3";
        initAllOrders(data, "ordersByShou", "itemList_pageByShoubar","pagebarByShouWrap");
    }else if(type == "ordersBySuccess"){

        $('.history .shop-history,.history .wrap_page').hide();
        $('#o-success,#pagebarBySuccessWrap').show();
        var data = "page=1&status=4";
        initAllOrders(data, "ordersBySuccess", "itemList_pageBySuccessbar","pagebarBySuccessWrap");
    }else if(type == "ordersByCancel"){

        var data = "page=1&status=0";
        initAllOrders(data, "ordersByCancel", "itemList_pageByCancelbar","pagebarByCancelWrap");
    }else if (type == "allOrders") {
        $('.history .shop-history,.history .wrap_page').hide();
        $('#all-order,#pagebarWrap').show();
        var tobody = "allOrder";
        var pageDiv = "itemList_pagebar";
        var pagebarWrap = "pagebarWrap";
        var data = "page=1&status=-1";
        initAllOrders(data,tobody,pageDiv,pagebarWrap);
    }
}

function receipt(id){
    if(confirm("是否确认收货?")){
        if(id != null){
            $.ajax({
                async:true,
                type:'POST',
                dataType:"json",
                url:"orderAction!updateOrder.go",
                data:{id:id},
                success:function(msg){
                    alert(msg);
                    location.reload(true);
                }
            })
        }
    }
}
function cancelOrder(id) {
    if (confirm("是否确认取消订单?")) {
        if (id != null) {
            $.ajax({
                async : true,
                type : 'POST',
                dataType : "json",
                url : "orderAction!deleteOrder.go",
                data : {
                    id : id
                },
                success : function(msg) {
                    alert(msg);
                    location.reload(true);
                }
            })
        }
    }
}
/*
 *提醒卖家发货
 */
function remindSeller(o,orderId){
    if(confirm("确定要提醒卖家?")){
        $(o).attr("disabled","disabled");
        $.ajax({
            async:true,
            type:'POST',
            dataType:"json",
            url:"orderAction!remindSellerForDeliverGoods.go",
            data:{'orderIds':orderId},
            success:function(msg){
                alert(msg.message);
                //location.reload(true);
            }
        })
    }
}
function createOperate(o)//创建特定状态订单对应的操作
{
    //var detail = '<a href="{dealDetailLink}" target="_blank">订单详情</a>'.replace(/{dealDetailLink}/g,o.dealDetailLink);
    var detail = '';
    var str = '';
    statusStr="";
    switch (o.status) {
        case 'DDZT_1_PAYMENT_1_GOODSTYPE_1':
        case 'DDZT_1_PAYMENT_3_GOODSTYPE_1':
        case 'DDZT_1_PAYMENT_4_GOODSTYPE_1'://等待支付
        case 'DDZT_1_PAYMENT_10_GOODSTYPE_1':
        case '1':
            str += '<div class="editBox"><a class="bt_small active edit-btn" target="_blank" href="?/order/pay/orderid/'
                + o.orderid + '">去付款</a>';
            str += '</div>';
            str += detail;
            statusStr="待支付";
            break;

        case '3':
        case 'DDZT_1_PAYMENT_3_GOODSTYPE_2':
        case 'DDZT_1_PAYMENT_4_GOODSTYPE_2':
        case 'DDZT_1_PAYMENT_5_GOODSTYPE_2':
        case 'DDZT_2_PAYMENT_5_GOODSTYPE_2':
        case 'DDZT_2_PAYMENT_1_GOODSTYPE_2':
        case 'DDZT_2_PAYMENT_3_GOODSTYPE_2':
        case 'DDZT_2_PAYMENT_4_GOODSTYPE_2'://确认收货
            str += '<div class="editBox"><a class="bt_small active edit-btn" onclick="receipt(' + o.orderid
                + ')">确认收货</a></div>';
            str += detail;
            statusStr="待收货";
            break;
        case '2':
        case 'DDZT_2_PAYMENT_1_GOODSTYPE_1':
        case 'DDZT_2_PAYMENT_3_GOODSTYPE_1':
        case 'DDZT_2_PAYMENT_4_GOODSTYPE_1':
            str += '<div class="editBox"><a class="bt_small active edit-btn" onclick="remindSeller(this,'
                + o.orderid + ')">提醒发货</a></div>';
            str += detail;
            statusStr="待发货";
            break;
        case '4':
            statusStr="已完成";
        	break;
        case '5':
        case 'DDZT_2_PAYMENT_1_GOODSTYPE_3':
        case 'DDZT_2_PAYMENT_3_GOODSTYPE_3':
        case 'DDZT_2_PAYMENT_4_GOODSTYPE_3':
            if(o.commentCount==null||o.commentCount==''){

                str += '<div class="editBox"><a href="goodsComment!toCommentPage.go?order.id='+o.orderid+'" class="edit-btn">评 价</a></div>';
            }

            statusStr="已完成";
            break;
        case 'DDZT_0_PAYMENT_1_GOODSTYPE_1':
        case 'DDZT_0_PAYMENT_3_GOODSTYPE_1':
        case 'DDZT_0_PAYMENT_4_GOODSTYPE_1':
            statusStr="已退单";
            break;
        default://其他情况
            str += detail;
            break;
    }
    return str;
}
function loadPage(pageIndex, pageTotal, pageDiv, pagebarWrap) {
    //当前页
    //var index =parseInt(num,10)+1;
    //总页数
    var count = 4;
    //$("#itemList_pagebar")
    if (!createPageBar($("#" + pageDiv),
            '<a  onclick="{event}"><span>{i}</span></a>',
            '<a  class="on"><span>{i}</span></a>',
            'getOrderList({i},true)', pageIndex, pageTotal)) {//无须页码条
        alert("page error!");
        $("#" + pagebarWrap).hide();
    } else {//须页码条
        $("#" + pagebarWrap).show();
    }
}

String.prototype.format = function() {
    if (arguments.length == 0)
        return this;
    for ( var s = this, i = 0; i < arguments.length; i++)
        s = s.replace(new RegExp("\\{" + i + "\\}", "g"), arguments[i]);
    return s;
};

/*公共JS*/
/*
 *生成页码条
 *@param	targetId	string	页码条标签id
 *@param	normalTpl	string	普通页码模板
 *@param	onTpl		string	当前页码模板
 *@param	evntTpl		string	事件模板
 *@param	cp			int		current page当前页
 *@param	tp			int		total page总页数
 *@return	true-成功生成页码条,false-生成失败,参数有误
 */
function createPageBar(targetId,normalTpl,onTpl,evntTpl,currentPage,totalPage)
{
    cp			= parseInt(currentPage,10);
    tp			= parseInt(totalPage,10);
    if(!targetId || !evntTpl || !onTpl || !normalTpl || cp<1 || cp>tp){return false;}//参数错误
    e          = targetId;
    onTpl      = onTpl.replace('{event}' , evntTpl);
    normalTpl  = normalTpl.replace('{event}' , evntTpl);
    offset     = 2;
    step	   = offset+2;


    //if(tp < 2){e.innerHTML='';return false;}//总页数小于2
    var lLack = 0, rLack = 0;//左右偏移的不足
    if( cp-2 < step ){ lLack = step - (cp-1); }
    if( tp-cp-1 < step ){ rLack = step - (tp-cp); }
    var le = cp-offset-rLack;
    var re = cp+offset+lLack;


    var str=[];
    if(cp > 1)//可点上一页
    {
        var s = '<a  onclick="{event}"><span class="before_page"><</span></a>'.replace('{event}' , evntTpl);
        s = s.replace('{i}',cp-1);
        str.push(s);
    }else{
        var s = '<a href="javascript:void(0);" style="" class="disabled"><span class="before_page"><</span></a>'.replace('{event}' , evntTpl);
        s = s.replace('{i}',cp-1);
        str.push(s);
    }

    if(le > 1)
    {
        str.push( normalTpl.replace(/{i}/g , 1) );
    }
    if(le == 3)
    {
        str.push( normalTpl.replace(/{i}/g , 2) );
    }
    if(le > 3)
    {
        str.push("<a href='#' class='on'><span>.</span></a><a href='#' class='on'><span>.</span></a>");
    }
    for(var j=cp,i=le; i<j; i++)
    {
        if(i<1){continue;}
        str.push( normalTpl.replace(/{i}/g , i) );
    }

    str.push( onTpl.replace(/{i}/g , cp) );

    for(var i=cp+1, j=re+1; i<j; i++)
    {
        if(i>tp){break;}
        str.push( normalTpl.replace(/{i}/g , i) );
    }
    if( re == tp-2 )
    {
        str.push( normalTpl.replace(/{i}/g , tp-1) );
    }
    if( re < tp-2 )
    {
        str.push("<a href='#' class='on'><span>.</span></a><a href='#' class='on'><span>.</span></a>");
    }
    if(re < tp)
    {
        str.push( normalTpl.replace(/{i}/g , tp) );

    }
    if(cp < tp)
    {
        var s = '<a  onclick="{event}"><span class="next_page">></span></a>'.replace('{event}' , evntTpl);
        s = s.replace('{i}',cp+1);
        str.push(s);
    }else{
        var s = '<a href="javascript:void(0);" style="color : darkgray"><span class="next_page">></span></a>'.replace('{event}' , evntTpl);
        s = s.replace('{i}',cp+1);
        str.push(s);

    }
    for(var i = 0; i< e.length; i++){
        e[i].innerHTML = str.join('');
    }
    return true;
}


//---------结束------------
