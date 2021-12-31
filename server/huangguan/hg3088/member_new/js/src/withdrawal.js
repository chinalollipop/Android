
jQuery.browser = {};
$(function(){
    /*右侧点击数字加到input框*/
    var sum = 0;
    $(".betAmount li").each(function () {
        //$(this).on("click",function(){
        $(this).click(function(){
            sum += $(this).val();
            $("#Money").val(sum);
            countUsdtMount();
        });
    });

    /*清空input框*/
    $(".ord_delBTN").click(function(){
        sum = 0;
        $("#Money").val("").focus();
    });

    /*密码小键盘*/
    var fn = 1;
    $(".withdrawpassword2").focus(function(){
        $(".pwd_num").show();
    });

    $('.pwd_num .num').on('click',function(){
        var n = $(this).html();
        if(fn<6){
            $("#address"+fn).val(n);
            fn+=1;
            $("#address"+fn).focus();

            $('.pwd_num #delete,#btnReset').on('click',function(){
                keyClear();
            })

        }else if(fn==6){
            $("#address"+fn).val(n);
            $(".pwd_num").hide();

            $('.pwd_num #delete,#btnReset').on('click',function(){
                keyClear();
            })
        }

    })
    function keyClear(){
        fn = 1;
        $("#address"+fn).val();
        $(".pwd_num").hide();
    }

    /*关闭小键盘*/
    $('.pwd_num .close').on('click',function(){
        $(".pwd_num").hide();
    }) ;

    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }

}) ;

//取款密码输入完自动跳到下一个
onload = function(){
    var txts = withdrawal_passwd.getElementsByTagName("input");
    var delt = document.getElementById('delete'); // 键盘清空按钮
    var btnR = document.getElementById('btnReset'); // 重新填写按钮
    for(var i = 0; i<txts.length;i++){
        var t = txts[i];
        t.index = i;
        //t.setAttribute("readonly", true);
        //txts[0].removeAttribute("readonly");
        t.onkeyup=function(event){
            var e = event || window.event || arguments.callee.caller.arguments[0];
            //console.log(this)
            this.value=this.value.replace(/^(.).*$/,'$1');
            if(e && e.keyCode==8){
                txtClear();
            }else{
                this.setAttribute("readonly", true);
                var next = this.index + 1;
                if(next > txts.length - 1) {
                    $(".pwd_num").hide();
                    return;
                }
                //txts[next].removeAttribute("readonly");
                txts[next].focus();
            }
        }
        delt.onclick=function(){
            txtClear();
        }
        btnR.onclick=function(){
            document.getElementById('Money').value = '' ; // 清空金额
            $('.pay_to_usdt').text(0);
            txtClear();
        }
        function txtClear(){ // 清空提款密码输入框
            t.value="";
            $("#withdrawal_passwd input").val("");
            $(".pwd_num").hide();
            $("#address1").focus();
        }
    }
    //txts[0].removeAttribute("readonly");
}

function VerifyData() {
    var $mominput = document.getElementById('Money') ;
    var memmoney = $('#hgmoney').data('val') ; // 用户当前余额
    if (document.getElementById('Bank_Name').value == "") {
        alert("请输入开户银行！")
        document.getElementById('Bank_Name').focus();
        return false;
    }
    // if (document.getElementById('Bank_Account').value == "") {
    //     alert("请输入银行账号！");
    //     document.getElementById('Bank_Account').focus();
    //     return false;
    // }
    if ($mominput.value == "") {
        alert("请输入提款金额！");
        $mominput.focus();
        return false;
    }
    if (document.getElementById('withdrawal_passwd').value == "") {
        alert("请输入取款密码！");
        document.getElementById('withdrawal_passwd').focus();
        return false;
    }

    if ($mominput.value !="") {
        if($mominput.value > memmoney ){
            alert("提款金额不能大于帐号金额！")
            //document.main.Money.focus();
            $mominput.focus();
            return false;
        }
    }
    if ($mominput.value !="") {
        if($mominput.value <100 ){
            alert("提款金额不能小于100元！")
            $mominput.focus();
            return false;
        }
    }
}

function Verifywithdrawpassword() {
    if (document.getElementById('address1').value !== "" &&document.getElementById('address2').value !== ""&&
        document.getElementById('address3').value !== ""&&document.getElementById('address4').value !== ""&&
        document.getElementById('address5').value == ""&&document.getElementById('address6').value == "") {
        alert("尊敬的客户，为了您的资金安全，建议您前往投注区页面顶部更改密码的地方，将取款密码修改为六位");
    }

    return true;
}

function agjb(uid,ctr){
    $.jBox('get:tran.php?uid='+uid+'&ctr='+ctr, {
        title: "额度转换",
        width: 400,height: "auto",border: 0,showIcon: false,buttons: {}
    });
}

// 更换银行 uid 用户id
function tranSetbank(uid) {
    var loadfalg = false ; // 防止重复提交
    var html = '<div class="msg-div">' +
        '<div class="fm" style="color:#c52000;">为了您的银行帐号安全，我们不建议您经常更换！</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>开户银行：</label><select id="chg_bank" style="width:210px" name="chg_bank"><span style="color:#c52000">*</span>' +
        '<option value="1" selected="selected" >***选择银行***</option>' ;
       for(var ii=0;ii<bank_name_list.length;ii++){
           html += '<option value="'+bank_name_list[ii]+'" '+(bank_name_list[ii]==Bank_Name?'selected':'')+'>'+bank_name_list[ii]+'</option>';
       }

    html +=' </select></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>银行账户：</label>' +
            '<input class="mn-ipt" type="text" id="chg_bank_account" name="chg_bank_account" style="width:210px" value="'+hide_Bank_Account+'">' +
        '</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>银行地址：</label>' +
            '<input class="mn-ipt" type="text" id="chg_bank_address" name="chg_bank_address" style="width:210px" value="'+Bank_Address+'">' +
        '</div>' ;

           html +='<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>TRC20的提币地址：</label>' +
               '<input class="mn-ipt" type="text" id="chg_usdt_address" name="chg_usdt_address" style="width:210px" value="'+hide_Usdt_Address+'" readonly>' +
               '<p class="red_color">如需修改提币地址，请联系客服</p>'+
               '</div>' ;

        html +='</div>';
    if(loadfalg){
        return false ;
    }
    var submit = function (v, h, f) {
        if (v == true) {

            var bank_acc = $("#chg_bank_account").val(); // 银行账号
           // var y_bank_acc = Bank_Account; // 原银行账号
            var usdt_add = $("#chg_usdt_address").val(); // usdt地址

            var dat = {};
            dat.uid = uid;
            dat.bank_name = $("#chg_bank").val(); // 银行名称
            dat.bank_address = $("#chg_bank_address").val(); // 银行地址
            dat.bank_account = bank_acc; // 银行账号
            //dat.usdt_address = usdt_add; // usdt 账号

            if(bank_acc==hide_Bank_Account){ // 没有变化
               // dat.bank_account = y_bank_acc;
                alert("未更换银行账号！");
                return false;
            }
            if(!usdt_add){
                if (dat.bank_name == "") {
                    alert("请选择开户银行！");
                    return false;
                }
                if (dat.bank_account == "") {
                    alert("请输入银行账号！");
                    return false;
                }
                if (dat.bank_address == "") {
                    alert("请输入银行地址！");
                    return false;
                }
            }

            loadfalg = true ;
            $.ajax({
                type: 'POST',
                url: '/app/member/money/updatebank.php',
                data: dat,
                dataType: 'json',

                success: function (res) {
                    if (res.code ==1) { // 更换成功
                        loadfalg = false ;
                        Bank_Name = res.resdata.Bank_Name;
                       // Bank_Account = res.resdata.Bank_Account;
                        hide_Bank_Account = res.resdata.Bank_Account_hide;
                        Bank_Address = res.resdata.Bank_Address;
                        //Usdt_Address = res.resdata.Usdt_Address;
                        hide_Usdt_Address = res.resdata.Usdt_Address_hide;

                        // 之前已经有帐号
                        $("#spn_bank").html(res.resdata.Bank_Name);  // 银行名称
                        $('#spn_bank_account').html(res.resdata.Bank_Account_hide) ; // 银行帐号

                        $('#Bank_Address').val(res.resdata.Bank_Address) ; // 银行地址
                        // 未绑定过银行帐号
                        $("#Bank_Name").val(res.resdata.Bank_Name); // 银行名称
                        //$("#Bank_Account").val(res.resdata.Bank_Account);  // 银行帐号

                        //$("#Usdt_Address").val(res.resdata.Usdt_Address);  // USDT帐号
                        $('#spn_usdt').html(res.resdata.Usdt_Address_hide) ; // USDT帐号
                        if(res.resdata.Usdt_Address){
                            $(".has_usdt").show();  // USDT金额显示
                        }

                        jBox.tip("更换成功", 'success');

                    } else {
                        loadfalg = false ;
                        jBox.tip("更换失败", 'success');
                    }


                },
                error: function (res) {
                    loadfalg = false ;
                    jBox.tip("数据更新失败，请稍后再试!",'success');

                }
            });

        }

    };

    jBox.confirm(html, "更换银行帐号", submit, {
        id: 'creditsChangeBank',
        width:370,
        showScrolling: false,
        buttons: {'提交更换': true, '取消更换': false}
    });
}
function  mainSubmit(cur){
    if(false == VerifyData()) {
        return false;
    }
    Verifywithdrawpassword();

    //var Usdt_Address = $('#Usdt_Address').val() ;
    var type = $(cur).attr('data-type') ; // 提款类型
    if(type=='usdt'){ // usdt 提款
        // if(!Usdt_Address){
        //     alert("请先绑定USDT账号!")
        //     return false;
        // }
    }else{
        main.usdt_rate.value = ''; // 银行提款置空
    }

    main.submit();
    document.getElementById('mainSubmit').onclick=null;
    document.getElementById('mainSubmit').style.cursor="not-allowed";
    document.getElementById('mainSubmit').style.backgroundColor="gray";
   // document.getElementById("next").innerHTML = "提交中...";
}
