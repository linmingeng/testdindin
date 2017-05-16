function showAddressEditor(id) {
    var region = $regionInit({
        div: "regionArea",
        regionId: (parseInt(id) >= 0) ? id: $regionGetIdByName(id),
        onChange: function(obj) {
            var regionId = "";
            if ((obj.currId)[2]) {
                regionId = (obj.currId)[2];
            }
            if ((obj.currId)[6]) {
                regionId = (obj.currId)[4] ? (obj.currId)[4] : (obj.currId)[2];
            }
            $id("address_regionId").value = regionId;
            return true;
        }
    });
}

function $regionInit(obj) {
    var option = {id:Math.floor(Math.random() * 1000), div:"", regionId:"", currId:["", "", "", "", "", "", false, ""], onChange:function (obj) {
        return true;
    }};
    if (!window._regionZindex) {
        window._regionZindex = 100;
    } else {
        window._regionZindex -= 1;
    }
    var regionMap = $getRegionMap();
    for (var i in obj) {
        option[i] = obj[i];
    }
    if (option.div == "") {
        return;
    } else {
		/*document.getElementById(option.div).innerHTML = "<div class=\"area\" style=\"z-index:" + _regionZindex + ";\"><span class=\"province\" id=\"provinceName_" + option.id + "\" >- \u9009\u62e9\u7701 -</span><div class=\"provincelist\" id=\"provinceList_" + option.id + "\" style=\"display:none;z-index:10000\"><iframe class=\"maskiframe\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" vspace=\"0\" frameborder=\"0\" scrolling=\"no\"></iframe><a href=\"#pl\">\u5317\u4eac</a> <a href=\"#pl\">\u5929\u6d25</a> <a href=\"#pl\">\u4e0a\u6d77</a> <a href=\"#pl\">\u91cd\u5e86</a><br /><a href=\"#pl\">\u5e7f\u4e1c</a> <a href=\"#pl\">\u5e7f\u897f</a> <a href=\"#pl\">\u6d77\u5357</a> <a href=\"#pl\">\u5c71\u4e1c</a> <a href=\"#pl\">\u6c5f\u82cf</a> <a href=\"#pl\">\u5b89\u5fbd</a> <a href=\"#pl\">\u6d59\u6c5f</a><br /><a href=\"#pl\">\u798f\u5efa</a> <a href=\"#pl\">\u6e56\u5317</a> <a href=\"#pl\">\u6e56\u5357</a> <a href=\"#pl\">\u6cb3\u5357</a> <a href=\"#pl\">\u6c5f\u897f</a> <a href=\"#pl\">\u5409\u6797</a> <a href=\"#pl\">\u9ed1\u9f99\u6c5f</a><br /><a href=\"#pl\">\u8fbd\u5b81</a> <a href=\"#pl\">\u56db\u5ddd</a> <a href=\"#pl\">\u4e91\u5357</a> <a href=\"#pl\">\u8d35\u5dde</a> <a href=\"#pl\">\u897f\u85cf</a> <a href=\"#pl\">\u9655\u897f</a> <a href=\"#pl\">\u9752\u6d77</a><br /><a href=\"#pl\">\u7518\u8083</a> <a href=\"#pl\">\u5b81\u590f</a> <a href=\"#pl\">\u65b0\u7586</a> <a href=\"#pl\">\u6cb3\u5317</a> <a href=\"#pl\">\u5c71\u897f</a> <a href=\"#pl\">\u5185\u8499\u53e4</a><br /><a href=\"#pl\">\u9999\u6e2f</a> <a href=\"#pl\">\u6fb3\u95e8</a> <a href=\"#pl\">\u53f0\u6e7e</a> <a href=\"#pl\">\u6d77\u5916</a></div><select name=\"cityId\" id=\"cityId_" + option.id + "\" style=\"width:100px;\"><option style=\"color:#666\" value=\"\">- \u9009\u62e9\u5e02 -</option></select> <select name=\"areaId\" id=\"areaId_" + option.id + "\" style=\"width:100px;\"><option style=\"color:#666\" value=\"\">- \u9009\u62e9\u533a -</option></select><input type=\"hidden\" name=\"provinceId\" id=\"provinceId_" + option.id + "\" /></div>";*/
        //注释掉海外
        document.getElementById(option.div).innerHTML = "<div class=\"area\" style=\"z-index:" + _regionZindex + ";\"><span class=\"province\" id=\"provinceName_" + option.id + "\" >- \u9009\u62e9\u7701 -</span><div class=\"provincelist\" id=\"provinceList_" + option.id + "\" style=\"display:none;z-index:10000\"><iframe class=\"maskiframe\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" vspace=\"0\" frameborder=\"0\" scrolling=\"no\"></iframe><a href=\"#pl\">\u5317\u4eac</a> <a href=\"#pl\">\u5929\u6d25</a> <a href=\"#pl\">\u4e0a\u6d77</a> <a href=\"#pl\">\u91cd\u5e86</a><br /><a href=\"#pl\">\u5e7f\u4e1c</a> <a href=\"#pl\">\u5e7f\u897f</a> <a href=\"#pl\">\u6d77\u5357</a> <a href=\"#pl\">\u5c71\u4e1c</a> <a href=\"#pl\">\u6c5f\u82cf</a> <a href=\"#pl\">\u5b89\u5fbd</a> <a href=\"#pl\">\u6d59\u6c5f</a><br /><a href=\"#pl\">\u798f\u5efa</a> <a href=\"#pl\">\u6e56\u5317</a> <a href=\"#pl\">\u6e56\u5357</a> <a href=\"#pl\">\u6cb3\u5357</a> <a href=\"#pl\">\u6c5f\u897f</a> <a href=\"#pl\">\u5409\u6797</a> <a href=\"#pl\">\u9ed1\u9f99\u6c5f</a><br /><a href=\"#pl\">\u8fbd\u5b81</a> <a href=\"#pl\">\u56db\u5ddd</a> <a href=\"#pl\">\u4e91\u5357</a> <a href=\"#pl\">\u8d35\u5dde</a> <a href=\"#pl\">\u897f\u85cf</a> <a href=\"#pl\">\u9655\u897f</a> <a href=\"#pl\">\u9752\u6d77</a><br /><a href=\"#pl\">\u7518\u8083</a> <a href=\"#pl\">\u5b81\u590f</a> <a href=\"#pl\">\u65b0\u7586</a> <a href=\"#pl\">\u6cb3\u5317</a> <a href=\"#pl\">\u5c71\u897f</a> <a href=\"#pl\">\u5185\u8499\u53e4</a><br /><a href=\"#pl\">\u9999\u6e2f</a> <a href=\"#pl\">\u6fb3\u95e8</a> <a href=\"#pl\">\u53f0\u6e7e</a> </div><select name=\"cityId\" id=\"cityId_" + option.id + "\" style=\"width:100px;\"><option style=\"color:#666\" value=\"\">- \u9009\u62e9\u5e02 -</option></select> <select name=\"areaId\" id=\"areaId_" + option.id + "\" style=\"width:100px;\"><option style=\"color:#666\" value=\"\">- \u9009\u62e9\u533a -</option></select><input type=\"hidden\" name=\"provinceId\" id=\"provinceId_" + option.id + "\" /></div>";
    }
    option.regionPath = $regionGetPath(option.regionId);
    var plist = document.getElementById("provinceList_" + option.id);
    $addEvent(document.getElementById("provinceName_" + option.id), "click", function (event) {
        try {
            plist.style.display = (plist.style.display == "none") ? "" : "none";
            event = (event) ? event : window.event;
            event.cancelBubble = true;
        }
        catch (e) {
        }
    });
    $addEvent(document, "click", function (event) {
        event = (event) ? event : window.event;
        try {
            document.getElementById("provinceList_" + option.id).style.display = "none";
        }
        catch (e) {
        }
    });
    $addEvent(document.getElementById("provinceList_" + option.id), "click", function (event) {
        try {
            event = (event) ? event : window.event;
            event.cancelBubble = true;
        }
        catch (e) {
        }
    });
    $addEvent(document.getElementById("cityId_" + option.id), "change", function (event) {
        try {
            var _this = event.srcElement || event.target;
            _this.onCityChange();
        }
        catch (e) {
        }
    });
    $addEvent(document.getElementById("areaId_" + option.id), "change", function (event) {
        var _this = event.srcElement || event.target;
        _this.onAreaChange();
    });
    var alist = plist.getElementsByTagName("a");
    for (var i = 0; i < alist.length; i++) {
        $addEvent(alist[i], "click", function (event) {
            var _this = event.srcElement || event.target;
            var _pname = _this.innerHTML;
            document.getElementById("provinceId_" + option.id).onProvinceChange($regionGetIdByProvinceName(_pname));
            document.getElementById("provinceList_" + option.id).style.display = "none";
        });
    }
    document.getElementById("provinceId_" + option.id).onProvinceChange = function (pid) {
        document.getElementById("provinceName_" + option.id).innerHTML = regionMap[pid][0];
        document.getElementById("provinceId_" + option.id).value = pid;
        var clist = regionMap[this.value][2];
        var cdom = document.getElementById("cityId_" + option.id);
        cdom.options.length = 1;
        document.getElementById("areaId_" + option.id).options.length = 1;
        document.getElementById("areaId_" + option.id).selectedIndex = 0;
        for (var i in clist) {
            cdom.options[cdom.options.length] = new Option(clist[i][0], i);
        }
        var hasAera = false;
        for (var i in regionMap[pid][2]) {
            if (regionMap[pid][2][i].length >= 3) {
                hasAera = true;
            }
        }
        document.getElementById("areaId_" + option.id).style.display = hasAera ? "" : "none";
        getRegionEnd();
    };
    document.getElementById("cityId_" + option.id).onCityChange = function () {
        if (document.getElementById("provinceName_" + option.id).innerHTML != option.currId[1]) {
            document.getElementById("provinceName_" + option.id).innerHTML = option.currId[1];
            document.getElementById("provinceId_" + option.id).value = option.currId[0];
        }
        var cdom = document.getElementById("areaId_" + option.id);
        cdom.options.length = 1;
        if (document.getElementById("cityId_" + option.id).value != "") {
            var clist = regionMap[document.getElementById("provinceId_" + option.id).value][2][this.value][2];
            document.getElementById("areaId_" + option.id).style.display = (regionMap[document.getElementById("provinceId_" + option.id).value][2][this.value].length < 3) ? "none" : "";
        } else {
            var clist = {};
            document.getElementById("areaId_" + option.id).style.display = "";
        }
        for (var i in clist) {
            cdom.options[cdom.options.length] = new Option(clist[i][0], i);
        }
        getRegionEnd();
    };
    document.getElementById("areaId_" + option.id).onAreaChange = function () {
        if (document.getElementById("provinceName_" + option.id).innerHTML != option.currId[1]) {
            document.getElementById("provinceName_" + option.id).innerHTML = option.currId[1];
            document.getElementById("provinceId_" + option.id).value = option.currId[0];
        }
        getRegionEnd();
    };
    if (option.regionPath[6]) {
        if (option.regionPath[0] != "" && option.regionPath[1] != "") {
            document.getElementById("provinceId_" + option.id).onProvinceChange(option.regionPath[0]);
        }
        if (option.regionPath[2] != "" && option.regionPath[3] != "") {
            document.getElementById("cityId_" + option.id).value = option.regionPath[2];
            document.getElementById("cityId_" + option.id).onCityChange();
        }
        if (option.regionPath[4] != "" && option.regionPath[5] != "") {
            document.getElementById("areaId_" + option.id).value = option.regionPath[4];
            document.getElementById("areaId_" + option.id).onAreaChange();
        }
    }
    function getRegionEnd() {
        option.currId = [document.getElementById("provinceId_" + option.id).value, document.getElementById("provinceName_" + option.id).innerHTML, "", "", "", "", false, document.getElementById("provinceName_" + option.id).innerHTML];
        document.getElementById("provinceId_" + option.id).value = document.getElementById("provinceId_" + option.id).value;
        document.getElementById("provinceName_" + option.id).innerHTML = document.getElementById("provinceName_" + option.id).innerHTML;
        var cityDom = document.getElementById("cityId_" + option.id);
        if (cityDom.value != "") {
            option.currId = [parseInt(option.currId[0]), option.currId[1], parseInt(cityDom.value), cityDom.options[cityDom.selectedIndex].text, "", "", false, parseInt(cityDom.value)];
            if (option.currId[2]) {
                if (regionMap[option.currId[0]][2][option.currId[2]].length < 3) {
                    option.currId[6] = true;
                }
            }
            var areaDom = document.getElementById("areaId_" + option.id);
            if (parseInt(areaDom.value)) {
                option.currId = [option.currId[0], option.currId[1], option.currId[2], option.currId[3], parseInt(areaDom.value), areaDom.options[areaDom.selectedIndex].text, true, parseInt(areaDom.value)];
            }
        }
        option.onChange(option);
        return option.currId;
    }
    return option;
}

function $regionGetIdByProvinceName(Prov) {
    if(Prov!= null && Prov!=""){
        var regionMap = $getRegionMap();
        for (var i in regionMap) {
            if (Prov == regionMap[i][0]) {
                return i;
            }
        }
    }
    return "";
}
//按地名获取ID
function $regionGetIdByName(Prov) {
    if(Prov!= null && Prov!=""){
        var regionMap = $getRegionMap();
        return bianli(Prov,regionMap,0);
    }else{

        return "";
    }
}
function bianli(Prov,regionMap,istime) {
	var id = 0;
    for (var i in regionMap) {
        if (Prov == regionMap[i][0]) {
            return i;
        }else{
           id = bianli(Prov,regionMap[i][2],istime++);
		}
		if(id > 0){
			return id;
		}
    }
}


function $regionGetPath(rid) {
    var regionMap = $getRegionMap();
    var regionPath = ["", "", "", "", "", "", false];
    if (!(parseInt(rid) >= 0)) {
        return regionPath;
    }
    for (var i in regionMap) {
        if (i.toString() == rid.toString()) {
            regionPath = [i, regionMap[i][0], "", "", "", "", true];
            return regionPath;
        } else {
            regionPath[0] = i;
            regionPath[1] = regionMap[i][0];
            for (var j in regionMap[i][2]) {
                if (j.toString() == rid.toString()) {
                    regionPath = [regionPath[0], regionPath[1], j, regionMap[i][2][j][0], "", "", true];
                    return regionPath;
                } else {
                    regionPath[2] = j;
                    regionPath[3] = regionMap[i][2][j][0];
                    for (var k in regionMap[i][2][j][2]) {
                        if (k.toString() == rid.toString()) {
                            regionPath = [regionPath[0], regionPath[1], regionPath[2], regionPath[3], k, regionMap[i][2][j][2][k][0], true];
                            return regionPath;
                        }
                    }
                    regionPath[2] = "";
                    regionPath[3] = "";
                }
            }
            regionPath[0] = "";
            regionPath[1] = "";
        }
    }
    return regionPath;
}

function $addEvent(obj, type, handle) {
    if (window.addEventListener) {
        obj.addEventListener(type, handle, false);
    } else {
        if (window.attachEvent) {
            obj.attachEvent("on" + type, handle);
        } else {
            obj["on" + type] = handle;
        }
    }
}

function $id(id) {
    return typeof (id) == "string" ? document.getElementById(id) : id;
}

/**
 * 根据id获取选择地址(省、市、区域)
 * @param rid
 * @returns
 * 			省、市、区域 字符串
 * @author ljy
 */
function $regionGetStr(rid) {
    var regionMap = $getRegionMap();
    if (rid == "") {
        return "";
    }
    var path = $regionGetPath(rid);
    var pStr = (path[1]) ? path[1] + ((regionMap[path[0]][1] == 1) ? "" : "\u7701") : "";
    var cStr = (path[3]) ? path[3] : "";
    if (path[2]) {
        if (regionMap[path[0]][2][path[2]][1] == 1) {
            pStr = "";
        }
        if ((regionMap[path[0]][1] == 2)) {
            pStr = "";
        }
    }
    var aStr = (path[5]) ? path[5] : "";
    return pStr + cStr + aStr;
}

/**
 * 根据id获取选择地址(省、市、区域)
 * @param rid
 * @returns
 * 			省-市-区域 字符串(以“-”分割)
 * @author ljy
 */
function $regionGetSplitStr(rid) {
    var regionMap = $getRegionMap();
    if (rid == "") {
        return "";
    }
    var path = $regionGetPath(rid);
    var pStr = (path[1]) ? path[1] + ((regionMap[path[0]][1] == 1) ? "" : "\u7701") : "";
    var cStr = (path[3]) ? path[3] : "";
    if (path[2]) {
        if (regionMap[path[0]][2][path[2]][1] == 1) {
            pStr = "";
        }
        if ((regionMap[path[0]][1] == 2)) {
            pStr = "";
        }
    }
    var aStr = (path[5]) ? path[5] : "";
    return pStr +"-"+ cStr+"-" + aStr;
}
