
jQuery.browser = {};
$(function(){
    /*右侧点击数字加到input框*/

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

            // $('.pwd_num #delete,#btnReset').on('click',function(){
            //     keyClear();
            // })

        }else if(fn==6){
            $("#address"+fn).val(n);
            $(".pwd_num").hide();

            // $('.pwd_num #delete,#btnReset').on('click',function(){
            //     keyClear();
            // })
        }

    })
    // function keyClear(){
    //     fn = 1;
    //     $("#address"+fn).val();
    //     $(".pwd_num").hide();
    // }

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

// onload = function(){
//     var txts = withdrawal_passwd.getElementsByTagName("input");
//     var delt = document.getElementById('delete'); // 键盘清空按钮
//     var btnR = document.getElementById('btnReset'); // 重新填写按钮
//     for(var i = 0; i<txts.length;i++){
//         var t = txts[i];
//         t.index = i;
//         t.onkeyup=function(event){
//             var e = event || window.event || arguments.callee.caller.arguments[0];
//             //console.log(this)
//             this.value=this.value.replace(/^(.).*$/,'$1');
//             if(e && e.keyCode==8){
//                 txtClear();
//             }else{
//                 this.setAttribute("readonly", true);
//                 var next = this.index + 1;
//                 if(next > txts.length - 1) {
//                     $(".pwd_num").hide();
//                     return;
//                 }
//
//                 txts[next].focus();
//             }
//         }
//         delt.onclick=function(){
//             txtClear();
//         }
//         btnR.onclick=function(){
//             document.getElementById('Money').value = '' ; // 清空金额
//             txtClear();
//         }
//         function txtClear(){ // 清空提款密码输入框
//             t.value="";
//             $("#withdrawal_passwd input").val("");
//             $(".pwd_num").hide();
//             $("#address1").focus();
//         }
//     }
//
// }


// function Verifywithdrawpassword() {
//     if (document.getElementById('address1').value !== "" &&document.getElementById('address2').value !== ""&&
//         document.getElementById('address3').value !== ""&&document.getElementById('address4').value !== ""&&
//         document.getElementById('address5').value == ""&&document.getElementById('address6').value == "") {
//         alert("尊敬的客户，为了您的资金安全，建议您前往投注区页面顶部更改密码的地方，将取款密码修改为六位");
//     }
//
//     return true;
// }

// function agjb(uid,ctr){
//     $.jBox('get:tran.php?uid='+uid+'&ctr='+ctr, {
//         title: "额度转换",
//         width: 400,height: "auto",border: 0,showIcon: false,buttons: {}
//     });
// }



