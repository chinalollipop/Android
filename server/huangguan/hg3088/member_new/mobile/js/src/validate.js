
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
    var Ns=/^[A-Za-z0-9]{4,25}$/;
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

// 注册验证,  tip 代理注册
function VerifyData(tip){
    var iconstr = '<span class="error-icon">!</span>' ;
    var $error = document.getElementById('error_msg') ;
    var flag = true ;
    if (removeAllSpace(_$('username').value) == "") {
       // $error.innerHTML = iconstr+' 所需帐号不能为空';
        setPublicPop('所需帐号不能为空');
        flag = false ;
        return false;
    }
    if (!isNum(removeAllSpace(_$('username').value))){
        //$error.innerHTML = iconstr+' 请输入正确的账号！格式：以英文+数字,长度5-15';
        setPublicPop('请输入正确的账号！格式：以英文+数字,长度5-15');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('username').value).length < 5 || removeAllSpace(_$('username').value).length > 15) {
        //$error.innerHTML = iconstr+' 账号需在5-15位之间';
        setPublicPop('账号需在5-15位之间');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value) == "") {
        //$error.innerHTML = iconstr+' 所需密码不能为空';
        setPublicPop('所需密码不能为空');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value).length < 6 || removeAllSpace(_$('password').value).length > 15) {
       // $error.innerHTML = iconstr+' 密码需在6-15位之间';
        setPublicPop('密码需在6-15位之间');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value) != removeAllSpace(_$('password2').value)) {
        //$error.innerHTML = iconstr+' 请检查账户密码与确认密码一致!';
        setPublicPop('请检查账户密码与确认密码一致');
        flag = false ;
        return false;
    }

    // if (!check_null(removeAllSpace(_$('alias').value))) {
    //     //$error.innerHTML = iconstr+' 真实姓名不能为空';
    //     setPublicPop('真实姓名不能为空');
    //     flag = false ;
    //     return false;
    // }else if(!isChinese(removeAllSpace(_$('alias').value))){
    //     //$error.innerHTML = iconstr+' 请输入正确的真实姓名';
    //     setPublicPop('请输入正确的真实姓名');
    //     flag = false ;
    //     return false;
    // }
    // if(!tip){ // 代理注册没有
    //     if(removeAllSpace(_$('paypassword').value) =='' || !isNumber(removeAllSpace(_$('paypassword').value) ) || removeAllSpace(_$('paypassword').value).length < 6 || removeAllSpace(_$('paypassword').value).length > 6){
    //         //$error.innerHTML = iconstr+' 请输入正确的提款密码';
    //         setPublicPop('请输入正确的提款密码');
    //         flag = false ;
    //         return false;
    //     }
    // }
    //
    var phone = $('#phone').val();
    var wechat = $('#wechat').val();
    var qq = $('#qq').val();
    if(phone!=undefined && (removeAllSpace(phone)=='' || !isMobel(removeAllSpace(phone)))){
        //$error.innerHTML = iconstr+' 请输入正确的手机号码!';
        setPublicPop('请输入正确的手机号码!');
        flag =false ;
        return false;
    }
    if(wechat!=undefined && (!isWechat(removeAllSpace(wechat)) || removeAllSpace(wechat)=='')){
        //$error.innerHTML = iconstr+' 请输入正确的微信号码!';
        setPublicPop('请输入正确的微信号码!');
        flag =false ;
        return false;
    }
    if(qq!=undefined && (!isQQNumber(removeAllSpace(qq)) || removeAllSpace(qq)=='')){
        setPublicPop('请输入正确的QQ号码!');
        flag =false ;
        return false;
    }

    // if(!tip){ // 代理注册没有
    //     if ( _$('birthday').value ==''){
    //         setPublicPop('请选择出生日期!');
    //         flag =false ;
    //         return false;
    //     }
    // }


    // if (_$('question').value == ''){
    //     $error.innerHTML = iconstr+' 请选择提示问题!';
    //     flag = false ;
    //     return false;
    // }
    // if (_$('answer').value == ''){
    //     $error.innerHTML = iconstr+' 请输入提示答案!';
    //     flag = false ;
    //     return false;
    // }
    if(!$('.checkbox-item').hasClass('checked')){
        //$error.innerHTML = iconstr+' 请确认已满18周岁!';
        setPublicPop('请同意本站协议条款')
        flag = false ;
        return false;
    }
   // $error.innerHTML ='' ; // 清空提示信息
    return flag ;
   // document.main.submit();
}

// 同意协议框
function agreeMentAction() {
    $('.checkbox-item').off().on('click', function() {
        if($(this).hasClass('checked')){
            $(this).removeClass('checked')
        }else{
            $(this).addClass('checked')
        }
    });
}


