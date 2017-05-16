
function showtitle(url)
{
  window.open(url, 'sample', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=400,left=200,top=200');
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function checkDate(obj, split)
{
	// '^\d{4}-\d{1,2}-\d{1,2}$'
	var str = obj.value;
	var re = '[0-9]{4}' +split+ '[0-9]{1,2}' +split+ '[0-9]{1,2}';
	var r = str.match(re);
	var state = 0;
	var maxDays = 31;
	// alert(str + ' , ' + r);
	if (r==null)
	{
		alert('请输入有效的日期，例如：2004'+split+'01'+split+'23。');
		state = -1;
	}
	else
	{
                var s=str.split(split);
                if (s[1] > 12 || s[1] < 1)
                {
                	alert('请输入有效的月份，月份只能在 1 至 12 之间。');
			state = -1;
                }
                // 每个月的最大日数不一样
		if (s[1]==4 || s[1]==6 || s[1]==9 || s[1]==11) maxDays = 30;
		else if (s[1]==2) {
			if (s[0] % 4 > 0) maxDays = 28;
			else if (s[0] % 100 == 0 && s[0] % 400 > 0) maxDays = 28;
			else maxDays = 29;
		}
		else maxDays = 31;

                if (s[2]>maxDays || s[2]<1)
		{
			alert('请输入有效的日，'+s[0]+'年'+s[1]+'月份的日只能在 1 至 '+maxDays+' 之间。');
			state = -1;
		}
	}
	if (state == -1)
	{
		obj.focus();
		obj.select();
		return false;
	}
	return true;	
}
/**
 * 检查 obj 是否时间类型
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 * split obj 的时间字符串使用哪种分隔符，一般是 '-'
 */
function checkDate(obj, message, split)
{
	// '^\d{4}-\d{1,2}-\d{1,2}$'
	var str = obj.value;
	var re = '[0-9]{4}' +split+ '[0-9]{1,2}' +split+ '[0-9]{1,2}';
	var r = str.match(re);
	var state = 0;
	var maxDays = 31;
	// alert(str + ' , ' + r);
	if (r==null)
	{
		alert(message + '请输入有效的日期，例如：2004'+split+'01'+split+'23。');
		state = -1;
	}
	else
	{
                var s=str.split(split);
                if (s[1] > 12 || s[1] < 1)
                {
                	alert(message + '请输入有效的月份，月份只能在 1 至 12 之间。');
			state = -1;
                }
                // 每个月的最大日数不一样
		if (s[1]==4 || s[1]==6 || s[1]==9 || s[1]==11) maxDays = 30;
		else if (s[1]==2) {
			if (s[0] % 4 > 0) maxDays = 28;
			else if (s[0] % 100 == 0 && s[0] % 400 > 0) maxDays = 28;
			else maxDays = 29;
		}
		else maxDays = 31;

                if (s[2]>maxDays || s[2]<1)
		{
			alert(message + '请输入有效的日，'+s[0]+'年'+s[1]+'月份的日只能在 1 至 '+maxDays+' 之间。');
			state = -1;
		}
	}
	if (state == -1)
	{
		obj.focus();
		obj.select();
		return false;
	}
	return true;	
}

/**
 * 检查 obj 是否有输入值
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 */
function existString(obj, message)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('请输入' + message + '。');  // 请输入：。
		obj.select();
		return false;
	}
		
	return true;
}
/**
 * 检查 obj 有没有超过最长长度
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 * maxLength 最长长度
 */
function maxLength(obj, message, maxLength)
{
	if (obj.value == null) {
		return true;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '超过最大长度' + maxLength + '，请重新输入。');
		obj.select();
		return false;
	}
	return true;
}
/**
 * 检查 obj 是否有输入值，有没有超过最长长度
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 * maxLength 最长长度
 */
function maxStringLength(obj, message, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('请输入' + message + '。');  // 请输入：。
		obj.select();
		return false;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '超过最大长度' + maxLength + '，请重新输入。');
		obj.select();
		return false;
	}
	return true;
}

/**
 * 检查 obj 是否有输入值，有没有超过最长长度
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 * maxLength 最长长度
 */
function maxLength(obj, message, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		return true;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '超过最大长度' + maxLength + '，请重新输入。');
		obj.select();
		return false;
	}
	return true;
}
/**
 * 检查 obj 是否有输入值，是否足够最短长度，有没有超过最长长度
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 * minLength 最短长度
 * maxLength 最长长度
 */
function existStringLength(obj, message, minLength, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('\u8bf7\u8f93\u5165\uff1a' + message + '\u3002');  // 请输入：。
		obj.select();
		return false;
	}	
	if (obj.value.length < minLength){
	  alert(message + '不足最小长度' + minLength + '，请重新输入。'); 
		obj.select();
		return false;
	}
	if (obj.value.length > maxLength){
	  alert(message + '超过最大长度' + maxLength + '，请重新输入。');
		obj.select();
		return false;
	}
	return true;
}
/**
 * 检查 obj 是否有选择
 * obj 被检查的对象
 * falseValue 失败时的值
 * message obj 的中文意思，用于提示
 */
function checkSelect(obj, flaseValue, message)
{
	if (obj.value == flaseValue)
	{
		alert('请选择：' + message + '。');
		obj.focus();
		return false;
	}
	
	return true;
}
/**
 * 检查 obj 是否有选择(特指radio单选框)
 * obj 被检查的对象
 * message obj 的中文意思，用于提示
 */
function checkRadioSelect(obj,  message)
{

flag=0;
for(i=0;i<obj.length;i++)
  {
    if(obj[i].checked)
	  {
            flag=1;
	  }
  }
if(flag==0)
  {
        alert("请选择"+message);
        return false;
  }
	return true;

}
/**
 * 检查 obj 是否是字符和数字
 * obj 被检查的对象
 * name obj 的中文意思，用于提示
 */
function checkCharAndNum(obj, name)
{
	var str = obj.value;
	var re = '[^a-zA-Z0-9]{1,}';
	var r = str.match(re);	
	//alert("r = " + r + " , " + str);
	if (r!=null)
	{
		alert(name + '只接受字母a-z A-Z 和数字 0-9，不能包含其它字符。');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}
/**
 * 检查 obj 是否是 EMAIL 地址
 * obj 被检查的对象
 * name obj 的中文意思，用于提示
 * isMust表示Email地址是否可以为空,如果是0为可以为空，如果是1为不能为空
 */
function checkEMail(obj, name, isMust)
{
	var str = obj.value;
	if (str == null)
	  str = "";
	  
	if (isMust == 1){
		if (str.length < 1){
		  alert('请输入' + name + '。');  // 请输入：。
			obj.select();
			return false;
		}
	  }
	if (str.length > 0){
		var r1 = str.match('\\.+');	
		var r2 = str.match('\\@+');
		if(str.length<9)
		{ 
	   		alert("请正确填写Email");
	   		obj.focus();
	   		return false;
		}	
		if (r1 != null && r2 != null)
		{
		  return true;
		}
		else
		{
			alert(name + ' Email地址必须包括 . 和 @。');
			obj.focus();
			obj.select();
			return false;
		}
		 var reg=/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
		 if(!reg.test(str)){
			  alert("请给出一个有效的电子邮箱地址!");
	          return false;
		 }
	}
	
	return true;
}
/**
 * 检查 obj 是否是电话号码
 * obj 被检查的对象
 * name obj 的中文意思，用于提示
 */
function checkTel(obj, name)
{
	if(obj)
	{
		var str = obj.value;
		var re = '[^0-9-]{1,}';
		var r = str.match(re);	
		if (r!=null)
		{
			alert(name + '只接受数字 0-9 和 -，不能包含其它字符。');
			obj.focus();
			obj.select();
			return false;
		}
		return true;
	}
	else
	{
		return false;	
	}
}
/**
 * 检查 obj 是否是数字
 * obj 被检查的对象
 * name obj 的中文意思，用于提示
 */
function checkNum(obj, name)
{
	var str = obj.value;
	var re = '[^0-9.]{1,}';
	var r = str.match(re);	
	if (r!=null)
	{
		alert(name + '只接受数字');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}
/**
 * 检查 obj 是否是数字,
 * obj 被检查的对象
 * name obj 的中文意思，用于提示
 */
function checkNumber(obj, name)
{
	if (obj.value == null) {
		return true;
	}
	var str = obj.value;
	var re = '[^0-9.]{1,}';
	var r = str.match(re);	
	if (r!=null)
	{
		alert(name + '只接受数字 0-9 ，不能包含其它字符。');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}

<!--by zjg
//文件使用说明：
//1。转换变量
//函数1：trimall(data){};
//    删除变量中的左右空格符；
//2。字符变量的检测
//函数2：checkstring(name, data, allowednull, maxlength){}；
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       maxlength：变量得最大长度，中文为1个字符；
//函数3：checkgbk(name, data, allowednull, maxlength){}；
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       maxlength：变量得最大长度，中文为2个字符；
//3。邮件的检测
//函数4：checkemail(name, data, allowednull){};
//      name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//4。数字的检测
//函数5：checknumber(name, data, allowednull, minnumber, maxnumber){};
//       name:变量的描述；
//       date:变量的值；
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       minnumber：变量的最小值；                
//       maxnumber：变量得最大值；
//5。金额的检测
//函数6：checkJE(name, data, allowednull, minnumber, maxnumber){};
//       name:变量的描述；
//       date:变量的值；
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       minnumber：变量的最小值；                
//       maxnumber：变量得最大值；
//6。百分数的检测
//函数7：checkPercent(name, data, allowednull){};
//       name:变量的描述；
//       date:变量的值；
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//7。时间的检测
//函数8：checkdate(name, data, allowednull, mindate){};
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//函数9：checkdatev(name, data, allowednull, mindate){};
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       mindate:变量的最少值；
//8。电话和手机，
//函数10：checkphone(name, data, allowednull, maxlength)
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       mindate:变量的最少值；
//函数10：checkmobile(name, data, allowednull, maxlength)
//       name:变量的描述；
//       date:变量的值
//       allowednull:检测的方式，true:变量可以为空;false:变量不可以为空;
//       mindate:变量的最少值；

//       
//一个普通检测字符所以某个字符集的方法如：数字，字母等
//var validDigit="填写字符集的内容";
//var x;变量
//  for (var i = 0; i <x.value.length; i++)
//{
//if (validDigit.indexOf(x.value.charAt(i)) == - 1)
//{ window.alert ("提示说明");
//   x.focus();
//   return false;
//} 
// }
//-->
var validAlpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
var validDigit = "0123456789";
var validJE = "0123456789.";
var validTelephone = "0123456789-";
function trimall(data)
{
  var datastr = data;
  var leftspace = datastr.search(/\S/gi);
  if (leftspace > 0)
  {
    datastr = datastr.substr(leftspace);
  }

  leftspace = datastr.search(/\s/gi);
  var rightspace = 0;
  var endspace = 0;
  
  var leftstr = datastr;
  while (leftspace != -1) {
    rightspace = rightspace + leftspace;
    leftstr = leftstr.substr(leftspace);
    leftspace = leftstr.search(/\S/gi);
    if (leftspace != -1) {
      rightspace = rightspace + leftspace;
      leftstr = leftstr.substr(leftspace);
      leftspace = leftstr.search(/\s/gi);
      endspace = 0;
    } else {
      endspace = 1;
    }
  }

  if ((endspace != 0) && (rightspace > 0)) {
    datastr = datastr.substring(0, rightspace);
  }
  
  return datastr;
}
function checkstring(name, data, allowednull, maxlength)
{
  var datastr = data;
  var lefttrim = datastr.search(/\S/gi);
  
  if (lefttrim == -1) {
    if (allowednull) {
      return 1;
    } else {
      alert("" + name + "：不能为空");
      return -2;
    }
  }

  if (datastr.search(/[<>]/gi) != -1) {
    alert("" + name + "：包含非法字符<>");
    return -1;
  }
  
  datastr = trimall(datastr);

  if ((maxlength > 0) && (datastr.length > maxlength)) {
    alert("" + name + "：长度超过限制");
    return -3;
  }
  return 0;
}
function checkgbk(name, data, allowednull, maxlength)
{
  var datastr = data;
  var lefttrim = datastr.search(/\S/gi);
  
  if (lefttrim == -1) {
    if (allowednull) {
      return 1;
    } else {
      alert("" + name + "：不能为空");
      return -2;
    }
  }

  if (datastr.search(/[<>]/gi) != -1) {
    alert("" + name + "：包含非法字符<>");
    return -1;
  }
  
  datastr = trimall(datastr);
  var len = datastr.replace(/[^\x00-\xff]/g,'**').length;
  if ((maxlength >= 0) && (len > maxlength)) {
    alert("" + name + "：长度超过限制");
    return -3;
  }
  return 0;
}
function checkemail(name, data, allowednull)
{
  var datastr = data;
  var lefttrim = datastr.search(/\S/gi);
  
  if (lefttrim == -1) {
    if (allowednull) {
      return 1;
    } else {
      alert("" + name + "：输入一个正确的email，不要包含无效字符");
      return -1;
    }
  }

  datastr = trimall(datastr);

  var myRegExp = /[a-z0-9](([a-z0-9]|[_\-\.]([a-z0-9])*)*)@([a-z0-9]([a-z0-9]|[_\-][a-z0-9])*)((\.[a-z0-9]([a-z0-9]|[_\-][a-z0-9])*)*)/gi;
  var answerind = datastr.search(myRegExp);
  var answerarr = datastr.match(myRegExp);
  
  if (answerind == 0 && answerarr[0].length == datastr.length)
  {
    return 0;
  }
  
  alert("" + name + "：输入一个正确的email，不要包含无效字符");
  return -1;
}

function checknumber(name, data, allowednull, minnumber, maxnumber)
{
  var datastr = data;
  var lefttrim = datastr.search(/\S/gi);
  
  if (lefttrim == -1) {
    if (allowednull) {
      return 1;
    } else {
      alert("" + name + "：输入一个正确的数字");
      return -1;
    }
  }

  datastr = trimall(datastr);

  if (datastr.search(/\D/gi) != -1) {
    alert("" + name + "：输入一个正确的数字");
    return -1;
  }

  var aNum = parseInt(datastr, 10);
  if ((minnumber >= 0) && (aNum < minnumber)) {
    alert("" + name + "：超过下限(" + minnumber + ")");
    return -2;
  }
  if ((maxnumber >= 0) && (aNum > maxnumber)) {
    alert("" + name + "：超过上限(" + maxnumber + ")");
    return -3;
  }
  return 0;
}
function checkJE(name, data, allowednull, minnumber, maxnumber)
{
	var datastr = data;
	
	if(allowednull&&datastr==null) return -1;//如果该字段为不可空字段同时用户没填写则退出
	
	var lefttrim = datastr.search(/\S/gi);	
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "：输入正确的数字和.");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	
	if (datastr.search(/\D/gi) != -1) {
        for (var i = 0; i < datastr.length; i++)
        {
       if (validJE.indexOf(datastr.charAt(i)) == - 1)
        { 		
       alert("" + name + "：输入正确的数字和.");
        return -1;
        } 
        }
        }
	var aNum = parseInt(datastr, 10);
	if ((minnumber >= 0) && (aNum <minnumber)) {
		alert("" + name + "：超过下限(" + minnumber + ")");
		return -2;
	}
	if ((maxnumber >= 0) && (aNum >= maxnumber)) {
		alert("" + name + "：超过上限(" + maxnumber + ")");
		return -3;
	}
	return 0;
}
function checkPercent(name, data, allowednull)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);	
	if(allowednull&&datastr==null) return -1;//如果该字段为不可空字段同时用户没填写则退出
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "：输入一个正确的百分比（只需输入数字）");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	
	if (datastr.search(/\D/gi) != -1) {
		alert("" + name + "：输入一个正确的百分比（只需输入数字）");
		return -1;
	}
	
	var aNum = parseInt(datastr, 10);
	if (aNum < 0) {
		alert("" + name + "：超过下限(0)");
		return -2;
	}
	if ((aNum > 100)) {
		alert("" + name + "：超过上限(100)");
		return -3;
	}
	return 0;
}
function checkdatev(name, data, allowednull, mindate)
{
  var datastr = data;
  var lefttrim = datastr.search(/\S/gi);
  
  if (lefttrim == -1) {
    if (allowednull) {
      return 1;
    } else {
      alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
      return -1;
    }
  }

  datastr = trimall(datastr);
  datastr = datastr.replace(/-|\./gi,"/")
  if (datastr.search(/[^0-9/\s]/gi) != -1) {
    alert("" + name + "：日期中包含非法字符,格式为2002-08-12或2002-8-12");
    return -1;
  }

  var year, month, day;
  var myRegExp = /[/]/gi;
  var answerind = -1;

  answerind = datastr.search(myRegExp);
  if (answerind <= 0) {
    alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
    return -1;
  }
  year = parseInt(datastr.substring(0,answerind),10);
  if ((year < 1910) || (year > 2100)) {
    alert("" + name + "：年份的范围在1910-2100内");
    return -1;
  }
  if (datastr.length <= answerind + 1) {
    alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
    return -1;
  }
  datastr = datastr.substr(answerind + 1);
  
  answerind = datastr.search(myRegExp);
  if (answerind <= 0) {
    alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
    return -1;
  }
  month = parseInt(datastr.substring(0,answerind),10);
  if ((month == 0) || (month > 12)) {
    alert(month);
    alert(datastr.substring(0,answerind));
    alert("" + name + "：月份的范围在1-12内");
    return -1;
  }
  if (datastr.length <= answerind + 1) {
    alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
    return -1;
  }
  datastr = datastr.substr(answerind + 1);

  answerind = datastr.search(myRegExp);
  if (answerind != -1) {
    alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
    return -1;
  }
  day = parseInt(datastr,10);

  var maxday;
  switch (month) {
  case 1  :
  case 3  :
  case 5  :
  case 7  :
  case 8  :
  case 10 :
  case 12 :
    maxday = 31;
    break;
  case 4  :
  case 6  :
  case 9  :
  case 11 :
    maxday = 30;
    break;
  default :
    maxday = 28;
    if (year % 4 == 0) {
      if (year % 100 == 0) {
      	if (year % 400 == 0) {
      	  maxday = 29;
      	}
      } else {
	maxday = 29;
      }
    }
  }
  if ((day == 0) || (day > maxday)) {
    alert("" + name + "：天数的范围在1-" + maxday + "内");
    return -1;
  }

  var m1 = mindate;
  if (m1 != "") {
    m1 = m1.replace(/-|\./gi,"/")
    if (Date.parse(m1) > Date.parse("" + year + "/" + month + "/" + day)) {
      alert("" + name + "：输入的日期小于" + m1);
      return -2;
    }
  }
  return 0;
}
function checkdate(name, data, allowednull)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);
	if(allowednull&&datastr==null) return -1;//如果该字段为不可空字段同时用户没填写则退出
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	datastr = datastr.replace(/-|\./gi,"/")
		if (datastr.search(/[^0-9/\s]/gi) != -1) {
			alert("" + name + "：日期中包含非法字符,格式为2002-08-12或2002-8-12");
			return -1;
		}
		
		var year, month, day;
	var myRegExp = /[/]/gi;
	var answerind = -1;
	
	answerind = datastr.search(myRegExp);
	if (answerind <= 0) {
		alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
		return -1;
	}
	year = parseInt(datastr.substring(0,answerind),10);
	if ((year < 1910) || (year > 2100)) {
		alert("" + name + "：年份的范围在1910-2100内");
		return -1;
	}
	if (datastr.length <= answerind + 1) {
		alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
		return -1;
	}
	datastr = datastr.substr(answerind + 1);
	
	answerind = datastr.search(myRegExp);
	if (answerind <= 0) {
		alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
		return -1;
	}
	month = parseInt(datastr.substring(0,answerind),10);
	if ((month == 0) || (month > 12)) {
		alert(month);
		alert(datastr.substring(0,answerind));
		alert("" + name + "：月份的范围在1-12内");
		return -1;
	}
	if (datastr.length <= answerind + 1) {
		alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
		return -1;
	}
	datastr = datastr.substr(answerind + 1);
	
	answerind = datastr.search(myRegExp);
	if (answerind != -1) {
		alert("" + name + "：输入一个正确的日期,格式为2002-08-12或2002-8-12");
		return -1;
	}
	day = parseInt(datastr,10);
	
	var maxday;
	switch (month) {
	case 1  :
	case 3  :
	case 5  :
	case 7  :
	case 8  :
	case 10 :
	case 12 :
		maxday = 31;
		break;
	case 4  :
	case 6  :
	case 9  :
	case 11 :
		maxday = 30;
		break;
	default :
		maxday = 28;
		if (year % 4 == 0) {
			if (year % 100 == 0) {
				if (year % 400 == 0) {
					maxday = 29;
				}
			} else {
				maxday = 29;
			}
		}
	}
	if ((day == 0) || (day > maxday)) {
		alert("" + name + "：天数的范围在1-" + maxday + "内");
		return -1;
	}
	
	return 0;
}

function checkphone(name, data, allowednull, maxlength)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);
	if(allowednull&&datastr==null) return -1;//如果该字段为不可空字段同时用户没填写则退出
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "：不能为空");
			return -2;
		}
	}
	
	if (datastr.search(/[<>]/gi) != -1) {
		alert("" + name + "：包含非法字符<>");
		return -1;
	}
	datastr = trimall(datastr);
	
	for (var i = 0; i < datastr.length; i++)
	{
		if (validTelephone.indexOf(datastr.charAt(i)) == - 1)
		{ 
			alert ("号码必须为数字和- ");
			return -1;
		} 
	}
	
	if ((maxlength > 0) && (datastr.length > maxlength)) {
		alert("" + name + "：长度超过限制");
		return -3;
	}
	return 0;
}

function checkmobile(name, data, allowednull, maxlength)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);

	if(allowednull&&datastr==null) return -1;//如果该字段为不可空字段同时用户没填写则退出
	if (lefttrim == -1) {
	
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "：不能为空");
			return -2;
		}
	}
	
	if (datastr.search(/[<>]/gi) != -1) {
		alert("" + name + "：包含非法字符<>");
		return -1;
	}
	
	datastr = trimall(datastr);
	
	for (var i = 0; i < datastr.length; i++)
	{
		if (validTelephone.indexOf(datastr.charAt(i)) == - 1)
		{ 
			alert ("手机必须为数字和-");
			return -1;
		} 
	}
	
	if ((maxlength > 0) && (datastr.length > maxlength)) {
		alert("" + name + "：长度超过限制");
		return -3;
	}
	
	return 0;
}