
/*
2018新增开始
* */

// 设置cookie
function setCookieAction(theName,theValue,theDay){
    if((theName != "")&&(theValue !="")){
        expDay = "Web,01 Jan 2026 18:56:35 GMT";
        if(theDay != null){
            theDay = eval(theDay);
            setDay = new Date();
            setDay.setTime(setDay.getTime()+(theDay*1000*60*60*24));
            expDay = setDay.toGMTString();
        }
        //document.cookie = theName+"="+escape(theValue)+";expires="+expDay;
        document.cookie = theName+"="+escape(theValue)+";path=/;expires="+expDay+";";
        return true;
    }
    return false;
}
// 获取cookie
 function getCookieAction(theName){
    theName += "=";
    theCookie = document.cookie+";";
    start = theCookie.indexOf(theName);
    if(start != -1){
        end = theCookie.indexOf(";",start);
        return unescape(theCookie.substring(start+theName.length,end));
    }
    return false;
};
// 删除cookie
 function delCookieAction(theName){
     var exp = new Date();
     exp.setTime(exp.getTime() - 1);
     var cval=getCookieAction(theName);
     if(cval!=null){
         document.cookie= theName + "='';path=/;expires="+exp.toGMTString();
     }

}
/* *
 * 四舍五入保留小数
 * num 保留几位
 */
function advFormatNumber(value, num) {
    var a_str = formatNumber(value, num);
    var a_int = parseFloat(a_str);
    if (value.toString().length > a_str.length) {
        var b_str = value.toString().substring(a_str.length, a_str.length + 1);
        var b_int = parseFloat(b_str);
        if (b_int < 5) {
            return a_str;
        } else {
            var bonus_str, bonus_int;
            if (num == 0) {
                bonus_int = 1;
            } else {
                bonus_str = "0."
                for (var i = 1; i < num; i ++ )
                    bonus_str += "0";
                bonus_str += "1";
                bonus_int = parseFloat(bonus_str);
            }
            a_str = formatNumber(a_int + bonus_int, num)
        }
    }
    return a_str;
}

function formatNumber(value, num){
  var a, b, c, i;
  a = value.toString();
  b = a.indexOf('.');
  c = a.length;
  if (num == 0) {
    if (b != - 1) {
      a = a.substring(0, b);
    }
  } else {
    if (b == - 1) {
      a = a + ".";
      for (i = 1; i <= num; i ++ ) {
        a = a + "0";
      }
    } else {
      a = a.substring(0, b + num + 1);
      for (i = c; i <= b + num; i ++ ) {
        a = a + "0";
      }
    }
  }
  return a;
}


function hash(string, length) {
  var length = length ? length : 32;
  var start = 0;
  var i = 0;
  var result = '';
  filllen = length - string.length % length;
  for(i = 0; i < filllen; i++) {
    string += "0";
  }
  while(start < string.length) {
    result = stringxor(result, string.substr(start, length));
    start += length;
  }
  return result;
}

function stringxor(s1, s2) {
  var s = '';
  var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  var max = Math.max(s1.length, s2.length);
  for(var i=0; i<max; i++)
  {
    var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
    s += hash.charAt(k % 52);
  }
  return s;
}

var evalscripts = new Array();
// 向页面添加js
function appendscript(src, text, reload, charset) {
  var id = hash(src + text);
  if(!reload && in_array(id, evalscripts)) return;
  if(reload && document.getElementById(id))
  {
      document.getElementById(id).parentNode.removeChild(document.getElementById(id));
  }
  evalscripts.push(id);
  var scriptNode = document.createElement("script");
  scriptNode.type = "text/javascript";
  scriptNode.id = id;
  //scriptNode.charset = charset;
  try
  {
    if(src)
    {
      scriptNode.src = src;
    }
    else if(text)
    {
      scriptNode.text = text;
    }
      document.getElementById('append_parent').appendChild(scriptNode);
  }
  catch(e)
  {}
}

function in_array(needle, haystack) {
  if(typeof needle == 'string' || typeof needle == 'number')
  {
    for(var i in haystack)
    {
      if(haystack[i] == needle)
      {
        return true;
      }
    }
  }
  return false;
}

//数字验证 过滤非法字符
function clearNoNum(obj){
    //先把非数字的都替换掉，除了数字和.
    obj.value = obj.value.replace(/[^\d.]/g,"");
    //必须保证第一个为数字而不是.
    obj.value = obj.value.replace(/^\./g,"");
    //保证只有出现一个.而没有多个.
    obj.value = obj.value.replace(/\.{2,}/g,".");
    //保证.只出现一次，而不能出现两次以上
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    if(obj.value != ''){
        var re=/^\d+\.{0,1}\d{0,2}$/;
        if(!re.test(obj.value))
        {
            obj.value = obj.value.substring(0,obj.value.length-1);
            return false;
        }
    }
}

//是否是中文
function isChinese(str){
    return /[\u4E00-\u9FA0]/.test(str);
}

// 金额快速选取
function change(money){
    var $mon = $('.fast_choose');
    var val = $mon.val() ;
    if(val == ''){
        val = 0;
    }
    $mon.val( parseInt(money) + parseInt(val) );
}


// 美东时间设置
function setAmerTime(el,type) {
    var today = new Date();
    today.setHours(today.getHours() - 12);
    var y = today.getFullYear();
    var m = today.getMonth() + 1;
    var d = today.getDate();
    var h = today.getHours();
    var mm = today.getMinutes();
    var s = today.getSeconds();
    m =  checkTime(m);
    d = checkTime(d);
    h = checkTime(h);
    mm = checkTime(mm);
    s = checkTime(s);
    if(type =='day'){
        $(el).val(y+"-"+m+"-"+d ); // 只到天
    }else{
        $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm+":"+s);
    }

}
/**
 * 1位数补0为2位数
 * @param i
 * @returns {*}
 */
function checkTime(i) {
    if (i<10) { i="0" + i ;}
    return i ;
}

// 打开游戏规则
function openGameRoul() {
    $('.to_game_roul').on('click',function () {
        var result_url = '/tpl/QA_sport.html' ;
        window.open(result_url,"saiguo","width=800,height=700,status=no,location=no");
    });
}

