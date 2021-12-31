/*
* 2018
* */
var alertComTime = 2000;
var configmsg = {
    accountmsg:'请输入5-15位帐号',
    txtmsg:'请输入内容'
}

/* 正则验证参考 */
var REGULAR_0  =[/^d[0-9A-Za-z]{0,}$/,/^d(?![a-zA-Z]+$)[0-9A-Za-z]{5,11}$/];//账号
var REGULAR_1  =[/^[0-9A-Za-z]{6,12}$/,/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/];//密码
var REGULAR_3  =[/^(?![0-9]+$)[\a-zA-Z0-9\u4E00-\u9FA5]+$/];//真实姓名
var REGULAR_4  =[/[0-9A_Za-z_\u4e00-\u9fa5.&=+$%-+@!~*?:,#`^\(\)<>{}\[\]{};'‘’]{2,}/,/^((https|http|ftp|rtsp|mms):\/\/)?(([0-9a-z_!~*'().&=+$%-]+:)?[0-9a-z_!~*'().&=+$%-]+@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/?)|(\/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+\/?)$/];//其它推广网址  ,推广网址
var REGULAR_5  =[/[0-9A_Za-z_\u4e00-\u9fa5.&=+$%-+@!~*?:,#`^\(\)<>{}\[\]{};'‘’]{2,}/]
var REGULAR_6  =[/^(1)([0-9]{10})$/];//手机
var REGULAR_7  =[/^[1-9][0-9]{4,}$/];//QQ
var REGULAR_8  =[/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/];//Email
var REGULAR_10 =[/^[0-9]{10,20}$/];//银行账号
var REGULAR_11 =[/([\u4e00-\u9fa5]{2})+(.*)/,/^[\u4e00-\u9fa5]{2,}$/];//银行省份,银行县市
var REGULAR_13 =[/^[0-9]{4}$/];//取款密码
var REGULAR_14 =[/^[[0-9a-zA-Z]{4}$/];//验证码
// var reg = /^[a-z0-9]+$/g;  //验证用户名，小写字母，数字组合
// var reg = /^[0-9]+$/g; // 验证数字


/* 是否是正确的手机号 */
function isMobel(value){
    var tel = /^1[3|4|5|6|7|8|9|][0-9]{9}$/;

    if(tel.test(value)){
        return true;
    }else{
        return false;
    }
}

/* 验证中文字符，真实姓名 */
function isChinese(val) {
    var tx = /[\u4E00-\u9FA5]{2,7}/g;
    return tx.test(val) ;
}

// qq号码验证
function isQQNumber(val) {
    var reg = /^[1-9][0-9]{4,}$/; // qq 号码
    return reg.test(val) ;
}
// 微信号码
function isWechat(val) {
    var reg = /^[-_a-zA-Z0-9]{4,25}$/ ;
    return reg.test(val) ;
}
// 邮箱验证
function isEmailAddress(val) {
    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/ ;
    return reg.test(val) ;
}


// 银行帐号验证
function isBankAccount(val) {
    var reg = /^[0-9]{10,20}$/;
    return reg.test(val) ;
}


/* 验证字符串是否为空 */
function check_null(string){
    var i=string.length;
    var j = 0;
    var k = 0;
    var flag = true;
    while (k<i){
        if (string.charAt(k)!= " ")
            j = j+1;
        k = k+1;
    }
    if(j==0){
        flag = false;
    }
    return flag;
}

/* 输入是否为字母与数字组合数字 */
function isNum(N){
    var Ns=/^[A-Za-z0-9]{5,15}$/;
    if (!Ns.test(N)){
        return false;
    }else{
        return true;
    }
}

// 纯数字验证
function isNumber(val) {
    var reg = /^[0-9]+$/g;
    return reg.test(val) ;
}

function trim(str){ //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

// 去掉网页多余行
function deleteMoreLine() {
    var a=document.body.innerHTML;
    document.body.innerHTML=a.replace(/\ufeff/g,'');
}

// 登录输入验证
// function chk_acc(alertuser,alertpassword){
//     if(document.getElementById('UserName').value =="") {
//         alert(alertuser);
//         document.LoginForm.UserName.focus();
//         return false;
//     }
//     if(document.getElementById('PassWord').value =="") {
//         alert(alertpassword);
//         document.LoginForm.PassWord.focus();
//         return false;
//     }
//     LoginForm.action;
// }
// 鼠标放上去改变颜色
function sbar(st){st.style.backgroundColor='#E0E0E0';}
function cbar(st){st.style.backgroundColor='';}

// 复制文本
function copyUrl2() {
    var Url2=document.getElementById("url");
    Url2.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    layer.msg('已复制好，可贴粘。',{time:alertComTime,shade: [0.2, '#000']});
}


/*
 * 还原金额，去除逗号
 * */
function returnMoney(s) {
    return parseFloat(s.replace(/[^\d\.-]/g, ""));
}

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

};


// 滚动记住位置
function setBodyScroll(){
    if(getCookieAction("var_scroll")!=null){
        document.body.scrollTop=getCookieAction("var_scroll") ;
    }
    $(window).on('scroll',function(){
        var scrollTop = $(window).scrollTop() || $(window).pageYOffset  ;
        // console.log(scrollTop);
        setCookieAction('var_scroll',scrollTop);
        // $floatBox.stop().animate({top:scrollTop+11});
    });
}

/*
* 登录
* type : mobile // 手机版
* */
function loginAccountAction(type) {
    // 记住账号
    var rem_username = localStorage.getItem('username');
    if(rem_username){
        document.all.checkbox.checked = true;
        $('.loginUserName').val(rem_username);
    }

    $('.submit_login').off().on('click',function () {
        if(!type){type='';}
        var url = '/app/agents/chk_login.php';
        var pcChangUrl = '/app/agents/chg_pw.php'; // 更改密码
        var $username = $('.loginUserName');
        var username = $username.val();
        var $password = $('.loginPassWord');
        var password = $password.val();
        var $userlevel = $('.userLevel');
        var userlevel = $userlevel.val();
        var captcha = $('.loginCaptcha').val();
        var remeber_me = document.all.checkbox.checked; // 记住账号

        if(username =="") {
            layer.msg('请输入用户名',{time:alertComTime,shade: [0.2, '#000']});
            $username.focus();
            return false;
        }
        if(password =="") {
            layer.msg('请输入密码',{time:alertComTime,shade: [0.2, '#000']});
            $password.focus();
            return false;
        }
        if(remeber_me){
            localStorage.setItem('username',username);
        }else {
            localStorage.removeItem('username');
        }

        var dataRes ={
            actionType:'login',
            type:type,
            level:userlevel,
            UserName:username,
            PassWord:password,
            captcha:captcha
        };
        $.ajax({
            type: 'POST',
            url:url,
            data:dataRes,
            dataType:'json',
            success:function(res){
                switch (res.status){
                    case '200': // 登录成功
                        if(!type){ // PC
                            parent.bb_mem_index.location = url+'?level='+res.data.level+'&uid='+res.data.uid;
                        }else{ // 手机
                            parent.loadPageBox.location = '/m/tpl/middle_index.php';
                        }
                        break;
                    case '300': // 跳转到更改密码
                        if(!type){ // PC
                            parent.bb_mem_index.location = pcChangUrl;
                        }else{ // 手机
                            parent.loadPageBox.location = '/m/tpl/middle_chgpwd.php?type=sz&navtitle=修改密码';
                        }
                        break;
                    default:
                        layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                }

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });


    });

}

// 设置高度
function settingHeight(cla) {
    var height_1 = window.innerHeight; // IOS UC浏览器 iframe 获取高度不对
    var height_2 = parent.parent.innerHeight; // 为了兼容 IOS UC浏览器 iframe 获取高度问题
    var win_height = height_2>height_1?height_1:height_1;
    $(cla).css({'height':win_height});
    window.addEventListener('resize', function() {  // 窗口变化
        $(cla).css({'height':win_height});
    })
}

/*
  * 四舍五入保留2位小数（若第二位小数为0，则保留一位小数）
  * dec 保留位数
  * */
function keepTwoDecimal(num,dec) {
    if(!dec){dec=2;} // 默认两位
    var result = parseFloat(num);
    if (isNaN(result)) {
        return false;
    }
    result = Math.round(num * 100) / 100;
    result = result.toFixed(dec)
    return result;

}
