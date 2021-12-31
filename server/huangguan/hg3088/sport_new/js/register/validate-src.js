
/* 2018 */

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

// 获取 id 选择器
function _$(i){
    return document.getElementById(i);
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
function isNum(val){
    var reg=/^[A-Za-z0-9_]{4,25}$/;
    return reg.test(val) ;
}
/* 是否是正确的手机号 */
function isMobel(val){
    var tel = /^1[3|4|5|6|7|8|9|][0-9]{9}$/;
    return tel.test(val) ;

}

/* 验证中文字符，真实姓名 */
function isChinese(val) {
    var reg = /[\u4E00-\u9FA5]{2,7}/g;
    return reg.test(val) ;
}

// qq号码验证
function isQQNumber(val) {
    var reg = /^[1-9][0-9]{4,}$/; // qq 号码
    return reg.test(val) ;
}
// 微信号码
function isWechat(val) {
    //var reg = /^[a-zA-Z]([-_a-zA-Z0-9]{4,25})+$/ ;
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

// 验证是否为纯数字
function isNumber(val) {
    var reg = /^[0-9]+$/g;
    return reg.test(val) ;
}

// 回车键提交
function enterSubmitAction() {
    $('input').bind('keyup', function(event) { // enter 登录
        if (event.keyCode == "13") {
            //回车执行查询
            $('.submit-btn').click();
        }
    });
}
