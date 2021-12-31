getverifycode() ; // 获取验证码
var submit_num = 0; // 限定 4 次
var submit_flag = false; // 防止重复提交
function submitdata(){
    if(submit_flag){
        return false ;
    }
    var errtips=$("#JS-tips-wrap");
    var _username=$("#js-username").val(); // 用户名
    var _realname=$("#js-realname").val(); // 真实姓名
    var _paypassword = $("#pay-password").val(); // 取款密码
    var _birthday = $("#birthday").val(); // 生日
    var new_password = $("#new_password").val(); // 新密码
    var _verifycode=$("#js-verifycode").val(); // 验证码
    var action_to = $('.action_to').val() ; // 当前是几级页面
    var datapars ={} ; // 需要传的参数
    if(action_to =='t1s'){ // 一级页面
        if(_username==""){
            errtips.addClass("error").text("请输入会员帐号！");
            $("#js-username").focus();
            return false;
        }
        datapars={
            action: action_to ,
            username:_username,
        }
    }else if(action_to =='t2s'){ // 二级页面
        if(_realname==""){
            errtips.addClass("error").text("请输入真实姓名！");
            $("#js-realname").focus();
            return false;
        }
        if(_paypassword ==''){
            errtips.addClass("error").text("请输入取款密码！");
            $("#pay-password").focus();
            return false;
        }

        // if(_birthday ==''){
        //     errtips.addClass("error").text("请选择您的生日！");
        //     $("#birthday").focus();
        //     return false;
        // }
        datapars={
            action: action_to ,
            username:_username,
            realname:_realname,
            paypassword:_paypassword,
            //birthday:_birthday+' 00:00:00',
        }
    }else if( action_to== 't3s'){ // 三级页面
        if(new_password =='' || new_password.length <6 || new_password.length >15){
            errtips.addClass("error").text("请输入6-15位登录密码！");
            $("#new_password").focus();
            return false;
        }
        if(new_password != $('#confirm_password').val()){
            errtips.addClass("error").text("密码与确认密码不一致！");
            $("#confirm_password").focus();
            return false;
        }
        datapars={
            action: action_to ,
            username:_username ,
            newpassword:new_password ,
        }
    }


    if(_verifycode==''){
        errtips.addClass("error").text("请输入验证码!!");
        $("#js-verifycode").focus();
        return false;
    }
    if(_verifycode !=verify_code){
        errtips.addClass("error").text("验证码输入不正确!!");
        $("#js-verifycode").focus();
        return false;
    }
    errtips.addClass("error").text("");
    $("#js-btn-submit").attr("disabled", true);
    submit_flag = true ;
    if(submit_num>3){
        showPopup('已超过提交次数，请稍后再试!');
        return false ;
    }
    $.ajax({
        type: "post",
        url: "/app/member/account/forgetservice.php",
        data: datapars ,
        dataType: "json",
        success: function(data){
            submit_num++ ;
            if(data){ // 成功返回数据
                submit_flag = false ;
                $("#js-btn-submit").removeAttr("disabled");
                if(data.err <0){ // 有错误 -1 帐号不存在
                    showPopup(data.msg);
                    getverifycode() ;
                    return false ;
                }else if(data.err == 1){ // 跳转到二级页面
                    location.href='after_forget_psw.php?username='+_username
                }else if(data.err == 2){ // 跳转到三级页面
                    location.href='after_two_forget_psw.php?username='+_username
                }else if(data.err == 3){ // 三级页面提交表单
                    showPopup(data.msg);
                    setTimeout(function () {
                        window.close();
                    },2000);
                }
            }


        },
        error:function(XMLHttpRequest, textStatus, errorThrown){
            submit_flag = false ;
            getverifycode() ;
            $("#js-btn-submit").removeAttr("disabled");
            showPopup("其它错误");
        }
    });
}
function showPopup($title) { //弹窗
    var $str = '<div class="overlay_login" style="display:block!imporatnt"></div>'+
        '<div class="popup_login" style="display:block">'+
        '<h2>提示</h2>'+
        '<div class="popup_content">'+
        '<p>' +$title+ '</p>'+
        '</div>'+
        '<div class="popup_btn">'+
        '<a href="javascript:void(0);" id="cancel_bet">确定</a>'+
        '</div>'+
        '</div>';

    if(!($("body").hasClass('overlay_login_login')) && $(".popup_login").length != 1){
        $("body").append($str);
    }

    $(document).on('click','#cancel_bet',function(){
        $(".popup_login,.overlay_login").remove();
    })
}

// 生成验证码
var verify_code ;
function getverifycode(){
    var arr =[] ;
    var color="white|green|blue|gray|pink"; //在内存变量color里定义5种颜色
    color=color.split("|"); //定义数组5个元素分别放置5种颜色
    for(var i=0;i<4;i++){ // 生成4个随机数
        var num = Math.floor(Math.random()*10) ;
        arr.push(num) ;
    }
    verify_code= arr.join('') ;
    // console.log(arr) ;
    var str='' ;
    for(var i=0;i<arr.length;i++){
        //下面这一行是把名★叫blink的对象★的color属性随机改成5种颜色的一种
        str +='<span class="code" style="color:'+color[parseInt(Math.random() * color.length)]+'">'+arr[i]+'</span>' ;
    }
    document.getElementsByClassName('new_code')[0].innerHTML=str ;

}