
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
		alert('��������Ч�����ڣ����磺2004'+split+'01'+split+'23��');
		state = -1;
	}
	else
	{
                var s=str.split(split);
                if (s[1] > 12 || s[1] < 1)
                {
                	alert('��������Ч���·ݣ��·�ֻ���� 1 �� 12 ֮�䡣');
			state = -1;
                }
                // ÿ���µ����������һ��
		if (s[1]==4 || s[1]==6 || s[1]==9 || s[1]==11) maxDays = 30;
		else if (s[1]==2) {
			if (s[0] % 4 > 0) maxDays = 28;
			else if (s[0] % 100 == 0 && s[0] % 400 > 0) maxDays = 28;
			else maxDays = 29;
		}
		else maxDays = 31;

                if (s[2]>maxDays || s[2]<1)
		{
			alert('��������Ч���գ�'+s[0]+'��'+s[1]+'�·ݵ���ֻ���� 1 �� '+maxDays+' ֮�䡣');
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
 * ��� obj �Ƿ�ʱ������
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 * split obj ��ʱ���ַ���ʹ�����ַָ�����һ���� '-'
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
		alert(message + '��������Ч�����ڣ����磺2004'+split+'01'+split+'23��');
		state = -1;
	}
	else
	{
                var s=str.split(split);
                if (s[1] > 12 || s[1] < 1)
                {
                	alert(message + '��������Ч���·ݣ��·�ֻ���� 1 �� 12 ֮�䡣');
			state = -1;
                }
                // ÿ���µ����������һ��
		if (s[1]==4 || s[1]==6 || s[1]==9 || s[1]==11) maxDays = 30;
		else if (s[1]==2) {
			if (s[0] % 4 > 0) maxDays = 28;
			else if (s[0] % 100 == 0 && s[0] % 400 > 0) maxDays = 28;
			else maxDays = 29;
		}
		else maxDays = 31;

                if (s[2]>maxDays || s[2]<1)
		{
			alert(message + '��������Ч���գ�'+s[0]+'��'+s[1]+'�·ݵ���ֻ���� 1 �� '+maxDays+' ֮�䡣');
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
 * ��� obj �Ƿ�������ֵ
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 */
function existString(obj, message)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('������' + message + '��');  // �����룺��
		obj.select();
		return false;
	}
		
	return true;
}
/**
 * ��� obj ��û�г��������
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 * maxLength �����
 */
function maxLength(obj, message, maxLength)
{
	if (obj.value == null) {
		return true;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '������󳤶�' + maxLength + '�����������롣');
		obj.select();
		return false;
	}
	return true;
}
/**
 * ��� obj �Ƿ�������ֵ����û�г��������
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 * maxLength �����
 */
function maxStringLength(obj, message, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('������' + message + '��');  // �����룺��
		obj.select();
		return false;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '������󳤶�' + maxLength + '�����������롣');
		obj.select();
		return false;
	}
	return true;
}

/**
 * ��� obj �Ƿ�������ֵ����û�г��������
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 * maxLength �����
 */
function maxLength(obj, message, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		return true;
	}	
	if (obj.value.length > maxLength){
	  alert(message + '������󳤶�' + maxLength + '�����������롣');
		obj.select();
		return false;
	}
	return true;
}
/**
 * ��� obj �Ƿ�������ֵ���Ƿ��㹻��̳��ȣ���û�г��������
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
 * minLength ��̳���
 * maxLength �����
 */
function existStringLength(obj, message, minLength, maxLength)
{
	if (obj.value == null || obj.value.length < 1)
	{
		alert('\u8bf7\u8f93\u5165\uff1a' + message + '\u3002');  // �����룺��
		obj.select();
		return false;
	}	
	if (obj.value.length < minLength){
	  alert(message + '������С����' + minLength + '�����������롣'); 
		obj.select();
		return false;
	}
	if (obj.value.length > maxLength){
	  alert(message + '������󳤶�' + maxLength + '�����������롣');
		obj.select();
		return false;
	}
	return true;
}
/**
 * ��� obj �Ƿ���ѡ��
 * obj �����Ķ���
 * falseValue ʧ��ʱ��ֵ
 * message obj ��������˼��������ʾ
 */
function checkSelect(obj, flaseValue, message)
{
	if (obj.value == flaseValue)
	{
		alert('��ѡ��' + message + '��');
		obj.focus();
		return false;
	}
	
	return true;
}
/**
 * ��� obj �Ƿ���ѡ��(��ָradio��ѡ��)
 * obj �����Ķ���
 * message obj ��������˼��������ʾ
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
        alert("��ѡ��"+message);
        return false;
  }
	return true;

}
/**
 * ��� obj �Ƿ����ַ�������
 * obj �����Ķ���
 * name obj ��������˼��������ʾ
 */
function checkCharAndNum(obj, name)
{
	var str = obj.value;
	var re = '[^a-zA-Z0-9]{1,}';
	var r = str.match(re);	
	//alert("r = " + r + " , " + str);
	if (r!=null)
	{
		alert(name + 'ֻ������ĸa-z A-Z ������ 0-9�����ܰ��������ַ���');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}
/**
 * ��� obj �Ƿ��� EMAIL ��ַ
 * obj �����Ķ���
 * name obj ��������˼��������ʾ
 * isMust��ʾEmail��ַ�Ƿ����Ϊ��,�����0Ϊ����Ϊ�գ������1Ϊ����Ϊ��
 */
function checkEMail(obj, name, isMust)
{
	var str = obj.value;
	if (str == null)
	  str = "";
	  
	if (isMust == 1){
		if (str.length < 1){
		  alert('������' + name + '��');  // �����룺��
			obj.select();
			return false;
		}
	  }
	if (str.length > 0){
		var r1 = str.match('\\.+');	
		var r2 = str.match('\\@+');
		if(str.length<9)
		{ 
	   		alert("����ȷ��дEmail");
	   		obj.focus();
	   		return false;
		}	
		if (r1 != null && r2 != null)
		{
		  return true;
		}
		else
		{
			alert(name + ' Email��ַ������� . �� @��');
			obj.focus();
			obj.select();
			return false;
		}
		 var reg=/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
		 if(!reg.test(str)){
			  alert("�����һ����Ч�ĵ��������ַ!");
	          return false;
		 }
	}
	
	return true;
}
/**
 * ��� obj �Ƿ��ǵ绰����
 * obj �����Ķ���
 * name obj ��������˼��������ʾ
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
			alert(name + 'ֻ�������� 0-9 �� -�����ܰ��������ַ���');
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
 * ��� obj �Ƿ�������
 * obj �����Ķ���
 * name obj ��������˼��������ʾ
 */
function checkNum(obj, name)
{
	var str = obj.value;
	var re = '[^0-9.]{1,}';
	var r = str.match(re);	
	if (r!=null)
	{
		alert(name + 'ֻ��������');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}
/**
 * ��� obj �Ƿ�������,
 * obj �����Ķ���
 * name obj ��������˼��������ʾ
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
		alert(name + 'ֻ�������� 0-9 �����ܰ��������ַ���');
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}

<!--by zjg
//�ļ�ʹ��˵����
//1��ת������
//����1��trimall(data){};
//    ɾ�������е����ҿո����
//2���ַ������ļ��
//����2��checkstring(name, data, allowednull, maxlength){}��
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       maxlength����������󳤶ȣ�����Ϊ1���ַ���
//����3��checkgbk(name, data, allowednull, maxlength){}��
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       maxlength����������󳤶ȣ�����Ϊ2���ַ���
//3���ʼ��ļ��
//����4��checkemail(name, data, allowednull){};
//      name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//4�����ֵļ��
//����5��checknumber(name, data, allowednull, minnumber, maxnumber){};
//       name:������������
//       date:������ֵ��
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       minnumber����������Сֵ��                
//       maxnumber�����������ֵ��
//5�����ļ��
//����6��checkJE(name, data, allowednull, minnumber, maxnumber){};
//       name:������������
//       date:������ֵ��
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       minnumber����������Сֵ��                
//       maxnumber�����������ֵ��
//6���ٷ����ļ��
//����7��checkPercent(name, data, allowednull){};
//       name:������������
//       date:������ֵ��
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//7��ʱ��ļ��
//����8��checkdate(name, data, allowednull, mindate){};
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//����9��checkdatev(name, data, allowednull, mindate){};
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       mindate:����������ֵ��
//8���绰���ֻ���
//����10��checkphone(name, data, allowednull, maxlength)
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       mindate:����������ֵ��
//����10��checkmobile(name, data, allowednull, maxlength)
//       name:������������
//       date:������ֵ
//       allowednull:���ķ�ʽ��true:��������Ϊ��;false:����������Ϊ��;
//       mindate:����������ֵ��

//       
//һ����ͨ����ַ�����ĳ���ַ����ķ����磺���֣���ĸ��
//var validDigit="��д�ַ���������";
//var x;����
//  for (var i = 0; i <x.value.length; i++)
//{
//if (validDigit.indexOf(x.value.charAt(i)) == - 1)
//{ window.alert ("��ʾ˵��");
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
      alert("" + name + "������Ϊ��");
      return -2;
    }
  }

  if (datastr.search(/[<>]/gi) != -1) {
    alert("" + name + "�������Ƿ��ַ�<>");
    return -1;
  }
  
  datastr = trimall(datastr);

  if ((maxlength > 0) && (datastr.length > maxlength)) {
    alert("" + name + "�����ȳ�������");
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
      alert("" + name + "������Ϊ��");
      return -2;
    }
  }

  if (datastr.search(/[<>]/gi) != -1) {
    alert("" + name + "�������Ƿ��ַ�<>");
    return -1;
  }
  
  datastr = trimall(datastr);
  var len = datastr.replace(/[^\x00-\xff]/g,'**').length;
  if ((maxlength >= 0) && (len > maxlength)) {
    alert("" + name + "�����ȳ�������");
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
      alert("" + name + "������һ����ȷ��email����Ҫ������Ч�ַ�");
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
  
  alert("" + name + "������һ����ȷ��email����Ҫ������Ч�ַ�");
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
      alert("" + name + "������һ����ȷ������");
      return -1;
    }
  }

  datastr = trimall(datastr);

  if (datastr.search(/\D/gi) != -1) {
    alert("" + name + "������һ����ȷ������");
    return -1;
  }

  var aNum = parseInt(datastr, 10);
  if ((minnumber >= 0) && (aNum < minnumber)) {
    alert("" + name + "����������(" + minnumber + ")");
    return -2;
  }
  if ((maxnumber >= 0) && (aNum > maxnumber)) {
    alert("" + name + "����������(" + maxnumber + ")");
    return -3;
  }
  return 0;
}
function checkJE(name, data, allowednull, minnumber, maxnumber)
{
	var datastr = data;
	
	if(allowednull&&datastr==null) return -1;//������ֶ�Ϊ���ɿ��ֶ�ͬʱ�û�û��д���˳�
	
	var lefttrim = datastr.search(/\S/gi);	
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "��������ȷ�����ֺ�.");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	
	if (datastr.search(/\D/gi) != -1) {
        for (var i = 0; i < datastr.length; i++)
        {
       if (validJE.indexOf(datastr.charAt(i)) == - 1)
        { 		
       alert("" + name + "��������ȷ�����ֺ�.");
        return -1;
        } 
        }
        }
	var aNum = parseInt(datastr, 10);
	if ((minnumber >= 0) && (aNum <minnumber)) {
		alert("" + name + "����������(" + minnumber + ")");
		return -2;
	}
	if ((maxnumber >= 0) && (aNum >= maxnumber)) {
		alert("" + name + "����������(" + maxnumber + ")");
		return -3;
	}
	return 0;
}
function checkPercent(name, data, allowednull)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);	
	if(allowednull&&datastr==null) return -1;//������ֶ�Ϊ���ɿ��ֶ�ͬʱ�û�û��д���˳�
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "������һ����ȷ�İٷֱȣ�ֻ���������֣�");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	
	if (datastr.search(/\D/gi) != -1) {
		alert("" + name + "������һ����ȷ�İٷֱȣ�ֻ���������֣�");
		return -1;
	}
	
	var aNum = parseInt(datastr, 10);
	if (aNum < 0) {
		alert("" + name + "����������(0)");
		return -2;
	}
	if ((aNum > 100)) {
		alert("" + name + "����������(100)");
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
      alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
      return -1;
    }
  }

  datastr = trimall(datastr);
  datastr = datastr.replace(/-|\./gi,"/")
  if (datastr.search(/[^0-9/\s]/gi) != -1) {
    alert("" + name + "�������а����Ƿ��ַ�,��ʽΪ2002-08-12��2002-8-12");
    return -1;
  }

  var year, month, day;
  var myRegExp = /[/]/gi;
  var answerind = -1;

  answerind = datastr.search(myRegExp);
  if (answerind <= 0) {
    alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
    return -1;
  }
  year = parseInt(datastr.substring(0,answerind),10);
  if ((year < 1910) || (year > 2100)) {
    alert("" + name + "����ݵķ�Χ��1910-2100��");
    return -1;
  }
  if (datastr.length <= answerind + 1) {
    alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
    return -1;
  }
  datastr = datastr.substr(answerind + 1);
  
  answerind = datastr.search(myRegExp);
  if (answerind <= 0) {
    alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
    return -1;
  }
  month = parseInt(datastr.substring(0,answerind),10);
  if ((month == 0) || (month > 12)) {
    alert(month);
    alert(datastr.substring(0,answerind));
    alert("" + name + "���·ݵķ�Χ��1-12��");
    return -1;
  }
  if (datastr.length <= answerind + 1) {
    alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
    return -1;
  }
  datastr = datastr.substr(answerind + 1);

  answerind = datastr.search(myRegExp);
  if (answerind != -1) {
    alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
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
    alert("" + name + "�������ķ�Χ��1-" + maxday + "��");
    return -1;
  }

  var m1 = mindate;
  if (m1 != "") {
    m1 = m1.replace(/-|\./gi,"/")
    if (Date.parse(m1) > Date.parse("" + year + "/" + month + "/" + day)) {
      alert("" + name + "�����������С��" + m1);
      return -2;
    }
  }
  return 0;
}
function checkdate(name, data, allowednull)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);
	if(allowednull&&datastr==null) return -1;//������ֶ�Ϊ���ɿ��ֶ�ͬʱ�û�û��д���˳�
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
			return -1;
		}
	}
	
	datastr = trimall(datastr);
	datastr = datastr.replace(/-|\./gi,"/")
		if (datastr.search(/[^0-9/\s]/gi) != -1) {
			alert("" + name + "�������а����Ƿ��ַ�,��ʽΪ2002-08-12��2002-8-12");
			return -1;
		}
		
		var year, month, day;
	var myRegExp = /[/]/gi;
	var answerind = -1;
	
	answerind = datastr.search(myRegExp);
	if (answerind <= 0) {
		alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
		return -1;
	}
	year = parseInt(datastr.substring(0,answerind),10);
	if ((year < 1910) || (year > 2100)) {
		alert("" + name + "����ݵķ�Χ��1910-2100��");
		return -1;
	}
	if (datastr.length <= answerind + 1) {
		alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
		return -1;
	}
	datastr = datastr.substr(answerind + 1);
	
	answerind = datastr.search(myRegExp);
	if (answerind <= 0) {
		alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
		return -1;
	}
	month = parseInt(datastr.substring(0,answerind),10);
	if ((month == 0) || (month > 12)) {
		alert(month);
		alert(datastr.substring(0,answerind));
		alert("" + name + "���·ݵķ�Χ��1-12��");
		return -1;
	}
	if (datastr.length <= answerind + 1) {
		alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
		return -1;
	}
	datastr = datastr.substr(answerind + 1);
	
	answerind = datastr.search(myRegExp);
	if (answerind != -1) {
		alert("" + name + "������һ����ȷ������,��ʽΪ2002-08-12��2002-8-12");
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
		alert("" + name + "�������ķ�Χ��1-" + maxday + "��");
		return -1;
	}
	
	return 0;
}

function checkphone(name, data, allowednull, maxlength)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);
	if(allowednull&&datastr==null) return -1;//������ֶ�Ϊ���ɿ��ֶ�ͬʱ�û�û��д���˳�
	if (lefttrim == -1) {
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "������Ϊ��");
			return -2;
		}
	}
	
	if (datastr.search(/[<>]/gi) != -1) {
		alert("" + name + "�������Ƿ��ַ�<>");
		return -1;
	}
	datastr = trimall(datastr);
	
	for (var i = 0; i < datastr.length; i++)
	{
		if (validTelephone.indexOf(datastr.charAt(i)) == - 1)
		{ 
			alert ("�������Ϊ���ֺ�- ");
			return -1;
		} 
	}
	
	if ((maxlength > 0) && (datastr.length > maxlength)) {
		alert("" + name + "�����ȳ�������");
		return -3;
	}
	return 0;
}

function checkmobile(name, data, allowednull, maxlength)
{
	var datastr = data;
	var lefttrim = datastr.search(/\S/gi);

	if(allowednull&&datastr==null) return -1;//������ֶ�Ϊ���ɿ��ֶ�ͬʱ�û�û��д���˳�
	if (lefttrim == -1) {
	
		if (allowednull) {
			return 1;
		} else {
			alert("" + name + "������Ϊ��");
			return -2;
		}
	}
	
	if (datastr.search(/[<>]/gi) != -1) {
		alert("" + name + "�������Ƿ��ַ�<>");
		return -1;
	}
	
	datastr = trimall(datastr);
	
	for (var i = 0; i < datastr.length; i++)
	{
		if (validTelephone.indexOf(datastr.charAt(i)) == - 1)
		{ 
			alert ("�ֻ�����Ϊ���ֺ�-");
			return -1;
		} 
	}
	
	if ((maxlength > 0) && (datastr.length > maxlength)) {
		alert("" + name + "�����ȳ�������");
		return -3;
	}
	
	return 0;
}