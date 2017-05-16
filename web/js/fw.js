/**
 *@deprecated:管理系统通用JS
 *@author:funfly
 *@since:2010-03-11
 */

//取鼠标位置开始
function getMouseXY(e){
    if(!document.all){
        mouseX=e.pageX;
        mouseY=e.pageY;
    }else{
        mouseX=document.documentElement.scrollLeft + window.event.x;
        mouseY=document.documentElement.scrollTop + window.event.y;
    }
    mouseX=parseInt(mouseX);
    mouseY=parseInt(mouseY);
}
//取鼠标位置结束

//计算字符长度开始
function getLen(str){
   var totallength=0;
   strLength = str.length;
   for(var i=0;i<strLength;i++){
       var intCode=str.charCodeAt(i);
       if (intCode>=0&&intCode<=128){
           totallength=totallength + 1; //非中文单个字符长度加1
       }
       else {
           totallength=totallength + 2; //中文字符长度则加2
       }
   }
   return totallength;
}
//计算字符长度结束

//判断中文字符串是否在一定长度范围内
function isChineseStr(str,minLen,maxLen){

    var re=/[^\u4e00-\u9fa5]/;
    if(re.test(str)){
        return false;
    }

    var strLen = getLen(str);
    if(strLen > maxLen || strLen < minLen){
        return false;
    }
    return true;
}

//设置透明度开始
function setOpacity(e,i){
    $(e).css({"filter":"alpha(opacity="+(10*i)+")","-moz-opacity":""+(i/10)+""});
}
//设置透明度结束

//调用父框架的提示信息
function msg(m){
    if(top.location.href == window.location.href){
        alert(m);
    }else{
        parent.msgShow(m);
    }
}

/**
 * 在某个地方显示3秒钟的提示信息
 * @param    jq        要显示提示信息的jquery对象
 * @param    m         要显示的提示信息
 */
function showtip(jq,m){
    if(m=="") {m="请提交该项数据！";};
    var os = jq.offset();
    $("#tooltip").css({
        position: "absolute",
        top: os.top + jq.height(),
        left: os.left + 20
    }).html(m).show().fadeOut(3000);
}

/**
 * 绑定数据验证
 * 提供一下内置验证（我们约定，css中不要设置以val-开头，这里都留给做表单验证）
 * val-check 必选
 * val-must  必填字段
 * val-mail  验证邮箱
 * val-date-[yyyy-MM-dd] 验证日期第三项为日期格式，默认为yyyy-MM-dd。如果要自定义格式，则必须包含yyyy MM dd这3项。如val-date-yyyy年MM月dd日
 * val-ip           IP地址
 * val-url          URL
 * val-zip          邮编
 * val-qq           QQ
 * val-username     用户名
 * val-password     密码
 * val-idcard       身份证
 * val-phone        电话
 * val-mobile       手机
 * val-alpha-[min]-[max]    都是字母
 * val-alnum-[min]-[max]    字母+数字
 * val-chinese-[min]-[max]  中文
 * val-num-[min]-[max] 验证是否是数字字符串，按照数字的多少验证，比如5到10位的整数
 * val-int-[min]-[max] 验证是否数字，按照数字的大小验证比如大于十小于一千
 * val-cmp-[cmpId] 比较两个表单是否一致，cmpId就是需要比较的表单内容
 * val-fun-[checkFun] 函数比较，checkFun表示自定义函数名
 * val-[min]-[max] 如果第二个节点不是上面的，而且是数字，那么我们就只判断字符串长度
 * val-str2-[min]-[max] 判断字符串长度,兼容中英文
 * <input class="val-mail val-num-0-20 val-fun-checkFun val-cmp-cmpId" title="错误信息"/>
 *
 * @param sm  绑定的submit按钮
 * @param dom 绑定的容器，会对该容器下所有包含验证信息的字段进行验证
 * @param smFun 如果点击对应的sm按钮通过验证后所执行的操作
 */
function checkVal(sm,dom,smFun){
    var n = 0;

    var valUtil = function(arr,v,tdom){
        v = $.trim(v);
        var len = arr.length,b=null;
        for(var i=0; i<len; i++){
            var a = arr[i].split("-");
            if(a[1]=="check") {
                if(!tdom.attr("checked")){
                    return false;
                }
                continue;
            }
            if(a[1]=="must") {
                if (v=="") {
                    return false;
                }
                continue;
            }
            if(v=="") continue;
            switch (a[1]){
                case "ip":
                    if (!/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(v)) return false;
                    continue;
                case "phone":
                    if (!/^((0[1-9]{3})?(0[12][0-9])?[-])?\d{7,8}$/.test(v)) return false;
                    continue;
                case "mobile":
                    if (!/^(13|15|18)+[0-9]{9}$/.test(v)) return false;
                    continue;
                case "date":
                    var f = (a[3] == undefined)?"yyyy-MM-dd":a[3];//格式
                    var m="MM",d="dd";y="yyyy";
                    var regex = '^'+f.replace(y,'\\d{4}').replace(m,'\\d{2}').replace(d,'\\d{2}')+'$';
                    if(!new RegExp(regex).test(v)) return false;
                    var s = v.substr(f.indexOf(y),4)+"/"+v.substr(f.indexOf(m),2)+"/"+v.substr(f.indexOf(d),2);
                    if (isNaN(new Date(s))) return false;
                    continue;
                case "datetime":
                    var f = (a[3] == undefined)?"yyyy-MM-dd HH:mm:ss":a[3];//格式
                    var m="MM",d="dd";y="yyyy";h="HH";mi="mm";s="ss";
                    var regex = '^'+f.replace(y,'\\d{4}').replace(m,'\\d{2}').replace(d,'\\d{2}').replace(h,'\\d{2}').replace(mi,'\\d{2}').replace(s,'\\d{2}')+'$';
                    if(!new RegExp(regex).test(v)) {return false};
                    if (isNaN(new Date(v.substr(f.indexOf(y),4),v.substr(f.indexOf(m),2),v.substr(f.indexOf(d),2),v.substr(f.indexOf(h),2),v.substr(f.indexOf(mi),2),v.substr(f.indexOf(s),2)))) {return false};
                    continue;
                case "zip":
                    if (!/^[0-9]{6}$/.test(v)) return false;
                    continue;
                case "qq":
                    if (!/^[0-9]{5,15}$/.test(v)) return false;
                    continue;
                case "username":
                    vLen = getLen(v);
                    if (vLen<5 || vLen>12) return false;
                    if (!/^[a-zA-Z0-9_\u4e00-\u9fa5]+$/.test(v)) return false;
                    continue;
                case "password":
                    if (!/^[a-zA-Z0-9_]{5,12}$/.test(v)) return false;
                    continue;
                case "idcard":
                    if (!/^([a-zA-Z0-9]{15}|[a-zA-Z0-9]{18})$/.test(v)) return false;
                    continue;
                case "url":
                    if (!/^([a-zA-Z0-9_-]*[.]){1,3}[a-zA-Z0-9_-]{2,3}$/.test(v)) return false;
                    continue;
                case "mail":
                    if (!/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)) return false;
                    continue;
                case "cmp":
                    if(v != $("#"+a[2]).val()) return false;
                    continue;
                case "reg":
                    if (!v.match(RegExp(a[2]))) return false;
                    continue;
                case "fun":
                    if (!(new Function("return "+a[2])()).call(this,e.target.value)) return false;
                    continue;
                case "alpha":
                    b = getDef(a,'alpha');
                    if (!v.match(RegExp("^[A-Za-z]{"+b[2]+","+b[3]+"}$"))) return false;
                    continue;
                case "alnum":
                    b = getDef(a,'alnum');
                    if (!v.match(RegExp("^[A-Za-z0-9]{"+b[2]+","+b[3]+"}$"))) return false;
                    continue;
                case "chinese":
                    b = getDef(a,'chinese');
                    if (!v.match(RegExp("^[\u4e00-\u9fa5]{"+b[2]+","+b[3]+"}$"))) return false;
                    continue;
                case "int":
                    if (isNaN(v)) return false;
                    b = getDef(a,'int');
                    v = parseInt(v);
                    if (v<b[2] || v>b[3]) return false;
                    continue;
                case "num":
                    b = getDef(a,'num');
                    if (!v.match(RegExp("^[-+]?[\d]{"+b[2]+","+b[3]+"}$"))) return false;
                    continue;
                case "str2":
                    b = getDef(a,'str2');
                    vLen = getLen(v);
                    if (vLen<b[2] || vLen>b[3]) return false;
                    continue;
                case "str":
                    b = getDef(a,'str');
                    vLen = getLen(v);
                    if (vLen<b[2] || vLen>b[3]) return false;
                    continue;
                default:
                    a[1] = (a[1] == undefined) ? 0 : parseInt(a[1]);
                    a[2] = (a[2] == undefined) ? 60000 : parseInt(a[2]);
                    if (v.length<a[1] || v.length>a[2]) return false;
                    continue;
            }
        }
        return true;
    }

    var getLen = function(str){
        var len = 0;
        for (var i=0; i<str.length; i++) {
          if (str.charCodeAt(i) > 127)
           len += 2; //utf8格式下中文占3位，gb2312请修改位2位
          else
           len++;
        }
        return len;
    }

    var getDef = function(a,t){
        a[2] = (a[2] == undefined) ? 1 : a[2];
        a[3] = (a[3] == undefined) ? (t=="int")?Number.MAX_VALUE:60000 : a[3];//firefox到7万就报错了
        return a;
    }
    
    $("#"+dom+" :input").each(function(){
        var arr = $(this).attr("class").split(" ");
        var val = new Array();
        var j = 0;
        $.each(arr,function(){
            if (this.substr(0,4) == "val-"){
                val[j] = this;
                j++;
            }
        })
        if (j > 0){
            $(this).unbind("blur");
            $(this).bind("blur",[sm,val],function(e){
                var v = e.target.value;
                var t = $(e.target);
                if (v=="") return;
                if (!valUtil(e.data[1],v,t)){
                    errnum ++;
                    showtip(t,t.attr("title"));
                    errorLine = $(this).attr("errorLine");
                    if(errorLine != "hide"){    //隐藏输入框的提示线条
                        if(!this.readOnly) t.addClass("val_error");
                    }
                    
                } else {
                    if(!this.readOnly) t.removeClass("val_error");
                }
            })
        }
    })
    

    $("#"+sm).unbind("click");
    $("#"+sm).click(function(){
        var ok=true;
        $("#"+dom+" :input").each(function(){
                
            var _t = $(this),dis = _t.css("display");
            var arr = _t.attr("class").split(" ");

            var v = _t.val();
            if (dis =="none" && v.length < 15){
                var tmp = v.substr(0,5);
                var tmp2 = v.substr(0,12);
                tmp = tmp.toLowerCase();
                tmp2 = tmp2.toLowerCase();
                if (tmp == "<br>"){
                    v = v.substr(4);
                } else if(tmp == '<br/>'){
                    v = v.substr(5);
                } else if(tmp == '<br />'){
                    v = v.substr(6);
                } else if (tmp2 == '<p></p>'){
                    v = v.substr(7);
                } else if (tmp2 == '<p><br></p>'){
                    v = v.substr(11);
                } else if (tmp2 == '<p><br/></p>'){
                    v = v.substr(12);
                } else if (tmp2 == '<p><br /></p>'){
                    v = v.substr(13);
                }
            }
            for(var i=0;i<arr.length;i++){
                if (arr[i].substr(0,4) == "val-"){
                     if (!valUtil([arr[i]],v,_t)){
                        if(dis =="none"){//为不可见元素
                            var next = $(_t.next());
                            if (!next.hasClass("xhe_default")){
                                next = next.next();
                            }
                            if (next.hasClass("xhe_default")){
                                if (ok){
                                    xhe_v = $("#xhe0_iframe").contents().find(".editMode").html();
                                    if (xhe_v.length < 15){
                                        var tmp = xhe_v.substr(0,5);
                                        var tmp2 = xhe_v.substr(0,14);
                                        tmp = tmp.toLowerCase();
                                        tmp2 = tmp2.toLowerCase();
                                        if (tmp == "<br>"){
                                            xhe_v = xhe_v.substr(4);
                                        } else if(tmp == '<br/>'){
                                            xhe_v = xhe_v.substr(5);
                                        } else if(tmp == '<br />'){
                                            xhe_v = xhe_v.substr(6);
                                        } else if (tmp2 == '<p></p>'){
                                            xhe_v = xhe_v.substr(7);
                                        } else if (tmp2 == '<p><br></p>'){
                                            xhe_v = xhe_v.substr(11);
                                        } else if (tmp2 == '<p><br/></p>'){
                                            xhe_v = xhe_v.substr(12);
                                        } else if (tmp2 == '<p><br /></p>'){
                                            xhe_v = xhe_v.substr(13);
                                        } else if (tmp2 == '<p>&nbsp;</p>'){
                                            xhe_v = xhe_v.substr(14);
                                        }
                                    }
                                    if(xhe_v ==""){
                                        ok=false;
                                        showtip(next,_t.attr("title"));
                                        next.find("table.xheLayout").addClass("val_error");
                                    }else{
                                        next.find("table.xheLayout").removeClass("val_error");
                                    }
                                }else{
                                    next.find("table.xheLayout").addClass("val_error");
                                }
                            } else {
                                ok = false;
                            }
                        } else {
                            if (ok){
                                ok=false;
                                showtip(_t,_t.attr("title"));
                                if(!this.readOnly) this.select();
                            }
                            errorLine = $(this).attr("errorLine");
                            if(errorLine != "hide"){    //隐藏输入框的提示线条
                                if(!this.readOnly) _t.addClass("val_error");
                            }
                        }
                        break;
                    }else{
                        if(dis =="none"){//为不可见元素
                            var next = $(_t.next());
                            if (!next.hasClass("xhe_default")){
                                next = next.next();
                            }
                            if (next.hasClass("xhe_default")){
                                if (ok){
                                    xhe_v = $("#xhe0_iframe").contents().find(".editMode").html();
                                    if (xhe_v.length < 15){
                                        var tmp = xhe_v.substr(0,5);
                                        var tmp2 = xhe_v.substr(0,14);
                                        tmp = tmp.toLowerCase();
                                        tmp2 = tmp2.toLowerCase();
                                        if (tmp == "<br>"){
                                            xhe_v = xhe_v.substr(4);
                                        } else if(tmp == '<br/>'){
                                            xhe_v = xhe_v.substr(5);
                                        } else if(tmp == '<br />'){
                                            xhe_v = xhe_v.substr(6);
                                        } else if (tmp2 == '<p></p>'){
                                            xhe_v = xhe_v.substr(7);
                                        } else if (tmp2 == '<p><br></p>'){
                                            xhe_v = xhe_v.substr(11);
                                        } else if (tmp2 == '<p><br/></p>'){
                                            xhe_v = xhe_v.substr(12);
                                        } else if (tmp2 == '<p><br /></p>'){
                                            xhe_v = xhe_v.substr(13);
                                        } else if (tmp2 == '<p>&nbsp;</p>'){
                                            xhe_v = xhe_v.substr(14);
                                        }
                                    }
                                    if(xhe_v ==""){
                                        ok=false;
                                        showtip(next,_t.attr("title"));
                                        next.find("table.xheLayout").addClass("val_error");
                                    }else{
                                        next.find("table.xheLayout").removeClass("val_error");
                                    }
                                }else{
                                    next.find("table.xheLayout").removeClass("val_error");
                                }
                            } else {
                                ok = false;
                            }
                        }
                    }
                }
            }
        })
        if(ok && $.isFunction(smFun)) {
            smFun();
            $("#"+dom+" .val_error").removeClass("val_error");
        }
    })
}
