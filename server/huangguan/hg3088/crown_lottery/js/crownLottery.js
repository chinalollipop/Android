//关闭浮窗
function closedFloat(elemt){
	$(elemt).parent().parent().hide()
}

$(document).ready(function(){
	$('.crownLottery_content_join ul li').click(function(){
		if($(this).index()<=2){
			$(this).addClass('current').siblings('li').removeClass('current');
			$('.crownLottery_content_join .joinCopywriting').eq($(this).index()).show().siblings().hide()
		}
	});
});

function cancel() {
    var data = {};
    data.action='logout';
    $.ajax({
        dataType : "json",
        type : "POST",
        url : 'crown_sign.php?_=' + Math.random(),
        data : data,
        success:function(item) {
            if(item && item.code == 0){
                // alert(item.message);
            }
        }
    });
    window.location.href = '/';
}

function agree() {
    var cpUrl = getQueryString("url");
    window.location.href = cpUrl;
}

var guest_flag = false;
function guestLogin() {
    if(guest_flag){
        alert('正在登录中...');
    }
    guest_flag = true;

    var data = {};
    data.action='guest_login';
    $.ajax({
        dataType : "json",
        type : "POST",
        url : 'crown_sign.php?_=' + Math.random(),
        data : data,
        success:function(item) {
            guest_flag = false;
            if(item && item.code == 0){
                var ifm = document.getElementById("myLottery");
                ifm.src = item.data.urlLogin;
                $("#index").hide();
                $("body").addClass('skin_red');
                $(".agree_win").show();
                $("#yesBtn").attr('href', item.data.cpUrl);
                // window.location.href = "crownLottery_free.html?url=" + item.data.cpUrl;
            }else{
                alert(item.message || "登录失败");
            }
        },
        error:function(){
            guest_flag = false;
            alert('网络异常，请稍后重试！');
        }
    });
}

var login_flag = false;
function login() {
    if(login_flag){
        alert('正在登录中...');
    }
    login_flag = true;

    var username = $("#userName").val();
    if ("" == username){
        alert("帐号不能为空");
        $("#userName").focus();
        return false;
    }
    var userPwd = $("#userPwd").val();
    if ("" == userPwd){
        alert("密码不能为空");
        $("#userPwd").focus();
        return false;
    }

    var data = {
        action : "login",
        username: username,
        password: hex_md5(userPwd),
        loginSrc: 0
    };

    $.ajax({
        dataType : "json",
        type : "POST",
        url : 'crown_sign.php?_=' + Math.random(),
        data : data,
        success:function(item) {
            login_flag = false;
            if(item && item.code == 0){
                var ifm = document.getElementById("myLottery");
                ifm.src = item.data.urlLogin;
                $("#index").hide();
                $("body").addClass('skin_red');
                $(".agree_win").show();
                $("#yesBtn").attr('href', item.data.cpUrl);
            }else{
                alert(item.message || "登录失败");
            }
        },
        error:function(){
            login_flag = false;
            alert('网络异常，请稍后重试！');
        }
    });
}

var mem_flag = false ; // 防止重复提交
function register() {
    if(!VerifyData()){ // 没有通过前端验证
        return false ;
    }
    if(mem_flag){
        alert('注册信息提交中...');
        return false ;
    }
    mem_flag = true ;
    var introducer = removeAllSpace($("input[name='introducer']").val());
    var username = removeAllSpace($("input[name='username']").val());
    var password = removeAllSpace($("input[name='password']").val());
    var password2 = removeAllSpace($("input[name='password2']").val());
    var alias = removeAllSpace($("input[name='alias']").val());
    var paypassword = removeAllSpace($("input[name='paypassword']").val());
    var phone = removeAllSpace($("input[name='phone']").val());
    var wechat = removeAllSpace($("input[name='wechat']").val());
    var data = {
        action : "register",
        introducer : introducer,
        username : username,
        password : hex_md5(password),
        password2 : hex_md5(password2),
        alias : alias,
        paypassword: paypassword,
        phone : phone,
        wechat : wechat
    };
    $.ajax({
        url : 'crown_sign.php?_=' + Math.random(),
        type: 'POST',
        dataType: 'json',
        data: data,
        success:function(item) {
            mem_flag = false;
            if(item && item.code == 0){
                var ifm = document.getElementById("myLottery");
                ifm.src = item.data.urlLogin;
                window.location.href = "crownLottery_free.html?url=" + item.data.cpUrl;
            }else{
                alert(item.message || "登录失败");
            }
        },
        error:function(){
            mem_flag = false;
            alert('网络异常，请稍后重试！');
        }
    });
}

function agentLogin() {
    var data = {
        action : "agent_login"
    };
    $.ajax({
        url : 'crown_sign.php?_=' + Math.random(),
        type : 'POST',
        dataType : 'json',
        data : data,
        success: function (item) {
            if(item && item.code == 0){
                $("#agent_login").attr('href', item.data.agent_url)
            }else{
                alert(item.message || "操作失败");
            }
        },
        error: function () {
            alert('网络异常，请稍后重试！');
        }
    });
}

var agent_flag = false; // 防止重复提交
function agentRegister() {
    if(agent_flag){
        alert('加盟申请提交中...');
        return false ;
    }
    agent_flag = true ;
    var bankname = $("input[name='bank_name']").val();
    var bankaccount = removeAllSpace($("input[name='bank_account']").val());
    var bankaddress = removeAllSpace($("input[name='bank_address']").val());

    if(!VerifyData('agents')){ // 没有通过前端验证
        return false ;
    }
    if(bankaccount == '' || !isBankAccount(bankaccount)){
        alert('请输入正确的银行卡账号!');
        return false ;
    }
    if(bankaddress == ''){
        alert('请输入开户行地址!');
        return false ;
    }

    var username = removeAllSpace($("input[name='username']").val());
    var password = removeAllSpace($("input[name='password']").val());
    var password2 = removeAllSpace($("input[name='password2']").val());
    var alias = removeAllSpace($("input[name='alias']").val());
    var phone = removeAllSpace($("input[name='phone']").val());
    var wechat = removeAllSpace($("input[name='wechat']").val());

    var data = {
        action : "agent_register",
        username : username,
        password : hex_md5(password),
        password2 : hex_md5(password2),
        alias : alias,
        bank_name : bankname,
        bank_address : bankaddress,
        bank_account : bankaccount,
        phone : phone,
        wechat : wechat
    };

    $.ajax({
        url : 'crown_sign.php?_=' + Math.random(),
        type : 'POST',
        dataType : 'json',
        data : data ,
        success: function (item) {
            agent_flag = false;
            if(item && item.code == 0){
                alert(item.message);
                window.location.href = item.data.agent_url;
            }else{
                alert(item.message || "登录失败");
            }
        },
        error: function () {
            agent_flag = false;
            alert('网络异常，请稍后重试！');
        }
    });
}


function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if ( r != null ){
        return unescape(r[2]);
    }else{
        return null;
    }
}

function VerifyData(tip){
    var flag = true ;
    if (removeAllSpace(_$('username').value) == "") {
        alert('所需帐号不能为空');
        flag = false ;
        return false;
    }
    if (!isNum(removeAllSpace(_$('username').value))){
        alert('请输入正确的账号！格式：以英文+数字,长度5-15');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('username').value).length < 5 || removeAllSpace(_$('username').value).length > 15) {
        alert('账号需在5-15位之间');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value) == "") {
        alert('所需密码不能为空');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value).length < 6 || removeAllSpace(_$('password').value).length > 15) {
        alert('密码需在6-15位之间');
        flag = false ;
        return false;
    }
    if (removeAllSpace(_$('password').value) != removeAllSpace(_$('password2').value)) {
        alert('请检查账户密码与确认密码一致');
        flag = false ;
        return false;
    }
    if (!check_null(removeAllSpace(_$('alias').value))) {
        alert('真实姓名不能为空');
        flag = false ;
        return false;
    }else if(!isChinese(removeAllSpace(_$('alias').value))){
        alert('请输入正确的真实姓名');
        flag = false ;
        return false;
    }
    if(!tip){ // 代理注册没有
        if(removeAllSpace(_$('paypassword').value) =='' || !isNumber(removeAllSpace(_$('paypassword').value) ) || removeAllSpace(_$('paypassword').value).length < 6 || removeAllSpace(_$('paypassword').value).length > 6){
            alert('请输入正确的提款密码');
            flag = false ;
            return false;
        }
    }
    if (!isWechat(removeAllSpace(_$('wechat').value)) || removeAllSpace(_$('wechat').value)==''){
        alert('请输入正确的微信号码!');
        flag =false ;
        return false;
    }
    if(removeAllSpace(_$('phone').value)=='' || !isMobel(removeAllSpace(_$('phone').value))){
        alert('请输入正确的手机号码!');
        flag =false ;
        return false;
    }
    return flag;
}

// 去除所有空格
function removeAllSpace(str) {
    return str.replace(/\s+/g, "");
}

function _$(a){
    return document.getElementById(a);
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

// 微信号码
function isWechat(val) {
    var reg = /^[-_a-zA-Z0-9]{4,25}$/ ;
    return reg.test(val) ;
}

// 银行帐号验证
function isBankAccount(val) {
    var reg = /^[0-9]{10,20}$/;
    return reg.test(val) ;
}