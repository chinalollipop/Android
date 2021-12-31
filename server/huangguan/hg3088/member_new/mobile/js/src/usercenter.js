/*
 *  会员中心公用
 * */

var submitflag = false ; // 防止重复提交
var alertinfo ={ // 配置提示信息,
    chg_bank:'请选择银行！',
    bank_Account:'请输入正确的银行账号！',
    bank_Address:'请输入银行地址！',
    paypassword1:'请输入正确的提款密码！',
    paypassword2:'请输入确认密码！',
    realname : '请填写真实姓名！',
    phone : '请填写手机号码！',
    wechat :'请填写微信号码！',
    birthday :'请填写您的生日！'
};

// 体育退出
function loginOutSport() {
    delLoginStatus();
    $.ajax({
        url: '/logout.php',
        type: 'POST',
        dataType: 'json',
        data: '',
        success:function(res){
            if(res.status =='200'){ // 退出成功
                alertComing('您已退出登录!') ;
                window.location.href = '/' ;
            }else{
                alertComing('退出异常!') ;
            }

        },
        error:function () {
            alertComing(config.errormsg) ;
        }
    });
}

/*
 *  获取银行卡列表
 *  curBnak 当前已绑定银行
 * */
function getBnakNameList(curBnak) {
    var ajaxUrl = '/account/bankcard.php';
    $.ajax({
        type: 'POST',
        url: ajaxUrl ,
        data:{action_type:'banks'} ,
        dataType:'json',
        success:function(res){
            var str = ' <option value="" selected="selected" >***选择银行***</option>' ;
            for(var i=0;i<res.data.length;i++){
                str +='<option value="'+ res.data[i] +'" '+ (curBnak==res.data[i]?'selected':'') +' >'+ res.data[i] +'</option>' ;
            }
            $('#chg_bank').html(str) ;
        },
        error:function(){

        }
    });
}

/*
 *  绑定银行卡
 * */
function bindBankAction(){
    $('.bind_bank_btn').on('click',function () {

        if(submitflag){
            return false ;
        }

        var chg_bank = setbank.chg_bank.value ;
        var bank_Account = $('#bank_Account').val(); // 银行账号
        var bank_Address = $('#bank_Address').val(); // 银行地址
        var bankFlag = $("#bankFlag").val();
        var paypassword1 = $('#paypassword1').val();
        var paypassword2 = $('#paypassword2').val();
        var paypaslength = $('#paypassword1').length ;

        if(!chg_bank){
            setPublicPop(alertinfo.chg_bank);
            $('.chg_bank').focus();
            return false;
        }

        if(!(bank_Account || isBankAccount(bank_Account))){
            setPublicPop(alertinfo.bank_Account);
            $('.bank-account').focus();
            return false;
        }
        if(!bank_Address){
            setPublicPop(alertinfo.bank_Address);
            $('.bank-address').focus();
            return false;
        }

        if(paypaslength ==1 && (paypassword1==''|| !isNumber(paypassword1) || paypassword1.length < 6 || paypassword1.length > 6) ){
            setPublicPop(alertinfo.paypassword1);
            flag = false ;
            return false;
        }


        if(paypaslength==1 && (paypassword2=='' || paypassword1 != paypassword2) ){
            setPublicPop('两次输入的提款密码不一致！');
            $('.paypassword2').focus();
            return false;
        }

        // window.setbank.submit();

        var data ={
            chg_bank: chg_bank , // 开户银行
            bank_Account: bank_Account , // 银行账户
            bank_Address: bank_Address , // 银行地址
            bankFlag: bankFlag , // 绑定账号标识
            paypassword1: paypassword1,
            paypassword2: paypassword2
        };
        submitflag = true ;
        $.ajax({
            url: '/account/updatebank.php?action=add' ,
            type: 'POST',
            dataType: 'json',
            data: data ,
            success: function (res) {
                if(res.status =='200'){
                    submitflag = false ;
                    alertComing(res.describe);
                    window.location.href = './withdraw.php';
                }else{ // 失败
                    submitflag = false ;
                    setPublicPop(res.describe);
                }
            },
            error:function () {
                submitflag = false ;
                setPublicPop(config.errormsg);
            }
        });

    })
}

// 编辑银行账号
function editBankAccount(){
    $('.show-bank-account').focus(function () {
        $(this).attr('type','hidden');
        $('#bank_Account').attr('type','text');
    })
}

/*
 *  绑定真实姓名
 * */
function doAction(){
    if(submitflag){
        return false;
    }
    var realname = $('#realname').val();
//        var phone = $('#phone').val();
    var wechat = $('#wechat').val();
    var birthday = $("#birthday").val();

    if(!realname){
        setPublicPop(alertinfo.realname);
        $('#realname').focus();
        return false;
    }
    if(!isChinese(realname)){
        setPublicPop('请输入正确的真实姓名');
        $('#realname').focus();
        return false;
    }
//        if(!phone){
//            setPublicPop(alertinfo.phone);
//            $('#phone').focus();
//            return false;
//        }
//        if(!isMobel(phone)){
//            setPublicPop('请输入正确的手机号码!');
//            $('#phone').focus();
//            return false;
//        }
//         if(!wechat){
//             setPublicPop(alertinfo.wechat);
//             $('#wechat').focus();
//             return false;
//         }
//         if (!isWechat(wechat)){
//             setPublicPop('请输入正确的微信号码!');
//             $('#wechat').focus();
//             return false;
//         }
//         if(!birthday){
//             setPublicPop(alertinfo.birthday);
//             $('#birthday').focus();
//             return false;
//         }

    var data ={
        realname : realname,
//            phone : phone,
        wechat : wechat,
        birthday : birthday
    };
    submitflag = true ;
    $.ajax({
        url: '/account/update_realname.php',
        type: 'POST',
        dataType: 'json',
        data: data ,
        success: function (res) {
            if(res.status =='200'){
                submitflag = false;
                alertComing(res.describe);
                window.location.href = '../account.php';
            }else{ // 失败
                submitflag = false;
                setPublicPop(res.describe);
            }
        },
        error:function () {
            submitflag = false;
            setPublicPop(config.errormsg);
        }
    });
}

// 额度转账
function tranUserMoney() {
    $("#trans_blance").click(function(){
        var f_blance = $('select[name="f_blance"] option').not(function(){ return !this.selected }).val(); // 转出平台
        var t_blance = $('select[name="t_blance"] option').not(function(){ return !this.selected }).val(); // 转入平台
        var blance = $("input[name='blance']").val(); // 金额

        if(f_blance.length==0 || t_blance.length==0){
            setPublicPop('请选择转出和转入方');
            return false;
        }
        if(blance.length==0 || blance<1){
            setPublicPop('请填写转账金额');
            return false;
        }

        if( f_blance == t_blance ){
            setPublicPop("转出方与转入方相同");
            return false;
        }
        if((f_blance!=='hg' && t_blance !=='hg')){
            setPublicPop('真人,电竞,彩票,棋牌,电子不能相互转账');
            return false ;
        }

        if(submitflag){
            alertComing('3秒内请勿重复提交!') ;
            return false ;
        }
        setTimeout(function () {
            submitflag = false ;
        },3000);
        submitflag = true ;

        var dat={
            id:id,
            uid: uid,
            userName:userName,
            action:'fundLimitTrans',
            f: f_blance,
            t: t_blance,
            b: blance
        };

        tranMoneyAction(f_blance,t_blance,dat);

    });
}

/*
 * 转账
 *  t_blance 转出方
 *  t_blance 转入方
 *  dat 参数
 * */
function tranMoneyAction(f_blance,t_blance,dat) {
    if(dat.b<1){ // 余额不足
        setPublicPop('额度不足!');
        return false;
    }
    if((f_blance == 'hg' && t_blance=='ag') || (f_blance == 'ag' && t_blance=='hg')){ // 体育与真人，转账走这里
        $.ajax({
            type: 'POST',
            url:'/ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.status =='200'){
                    setPublicPop('转账成功，请查看余额!')
                    $('#blance,.alert_input').val(''); // 清空输入框
                    get_blance('balance');
                }else{
                    setPublicPop(ret.describe) ;
                }
            },
            error:function(ii,jj,kk){
                $('#trans_blance').attr('value','提交转换');
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='og') || (f_blance == 'og' && t_blance=='hg')) { // 皇冠与OG视讯
        $.ajax({
            type: 'POST',
            url:'/og/og_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data[0].hg_balance); // 皇冠余额
                    $('.og_money').html(item.data[0].og_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='bbin') || (f_blance == 'bbin' && t_blance=='hg')) { // 皇冠与OG视讯
        $.ajax({
            type: 'POST',
            url:'/bbin/bbin_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.bbin_money').html(item.data.bbin_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='cp') || (f_blance == 'cp' && t_blance=='hg')){ // 体育与彩票、体育转账走这里
        $.ajax({
            type: 'POST',
            url: '/ajaxTran.php',
            data: dat,
            dataType: 'json',
            success:function(res){

                if(res.status=='200'){ // 成功
                    // window.location.reload();
                    $('#blance,.alert_input').val(''); // 清空输入框
                    setPublicPop('转账成功，请查看余额!');
                    get_cp_blance('.hg_money','.cp_money') ;
                }else{
                    setPublicPop(res.describe);
                }
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='ky') || (f_blance == 'ky' && t_blance=='hg')) { // 皇冠与开元棋牌
        $.ajax({
            type: 'POST',
            url:'/ky/ky_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.ky_money').html(item.data.ky_balance); // 开元余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='ff') || (f_blance == 'ff' && t_blance=='hg')) { // 皇冠与皇冠棋牌
        $.ajax({
            type: 'POST',
            url:'/hgqp/hg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.ff_money').html(item.data.ff_balance); // 棋牌余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='vg') || (f_blance == 'vg' && t_blance=='hg')) { // 皇冠与VG棋牌
        $.ajax({
            type: 'POST',
            url:'/vgqp/vg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.vg_money').html(item.data.vg_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='kl') || (f_blance == 'kl' && t_blance=='hg')) { // 皇冠与快乐棋牌

        $.ajax({
            type: 'POST',
            url:'/klqp/kl_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.kl_money').html(item.data.kl_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='ly') || (f_blance == 'ly' && t_blance=='hg')) { // 皇冠与乐游棋牌
        $.ajax({
            type: 'POST',
            url:'/lyqp/ly_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.ly_money').html(item.data.ly_balance); // 乐游余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='mg') || (f_blance == 'mg' && t_blance=='hg')) { // 皇冠与MG电子
        $.ajax({
            type: 'POST',
            url:'/mg/mg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.mg_money').html(item.data.mg_balance); // mg余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='avia') || (f_blance == 'avia' && t_blance=='hg')) { // 皇冠与泛亚电竞
        $.ajax({
            type: 'POST',
            url:'/avia/avia_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data[0].hg_balance); // 皇冠余额
                    $('.avia_money').html(item.data[0].avia_balance); // 电竞余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='fire') || (f_blance == 'fire' && t_blance=='hg')) { // 皇冠与雷火电竞
        $.ajax({
            type: 'POST',
            url:'/thunfire/fire_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.fire_money').html(item.data.fire_balance); // 雷火电竞余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='sc') || (f_blance == 'sc' && t_blance=='hg')) { // 皇冠体育
        $.ajax({
            type: 'POST',
            url:'/sportcenter/sport_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.sc_money').html(item.data.sc_balance); // 皇冠体育余额
                    $('.hg_money').html(item.data.hg_balance); // 钱包中心余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='gmcp') || (f_blance == 'gmcp' && t_blance=='hg')) { // 三方彩票
        $.ajax({
            type: 'POST',
            url:'/gmcp/cp_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.gmcp_money').html(item.data.gmcp_balance); // 三方彩票余额
                    $('.hg_money').html(item.data.hg_balance); // 体育余额
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='cq') || (f_blance == 'cq' && t_blance=='hg')) { // 皇冠与CQ9电子
        $.ajax({
            type: 'POST',
            url:'/cq9/cq9_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.cq_money').html(item.data.cq_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='mw') || (f_blance == 'mw' && t_blance=='hg')) { // 皇冠与MW电子
        $.ajax({
            type: 'POST',
            url:'/mw/mw_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data[0].hg_balance); // 皇冠余额
                    $('.mw_money').html(item.data[0].mw_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
    if((f_blance == 'hg' && t_blance=='fg') || (f_blance == 'fg' && t_blance=='hg')) { // 皇冠与FG电子
        $.ajax({
            type: 'POST',
            url:'/fg/fg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(item){
                if (item.status == '200') {
                    $('#trans_blance').attr('value', '提交转换');
                    $('#blance,.alert_input').val(''); // 清空输入框
                    $('.hg_money').html(item.data.hg_balance); // 皇冠余额
                    $('.fg_money').html(item.data.fg_balance);
                    setPublicPop('转账成功，请查看余额！');
                } else {
                    setPublicPop(item.describe)
                }
            },
            error:function(){
                setPublicPop('网络错误，请稍后重试!');
            }
        });
    }
}


// 一键转账
function oneTransfer() {
    $('.top_user_ye a').off().on('click',function () {
        var $hg_money = $('.top_user_ye').find('.hg_money');
        var f_blance = 'hg'; // 转出平台
        var t_blance = $(this).attr('data-type'); // 转入平台
        var blance = $hg_money.text(); // 金额
        blance = blance.replace(',',''); // 去掉千位符
        blance = Math.floor(blance);
        // console.log(blance)
        if(blance<1){ // 余额不足
            setPublicPop('余额不足!请先充值!');
            return false;
        }
        var dat={
            f: f_blance,
            t: t_blance,
            b: blance,
            uid: uid
        };

        if(submitflag){
            alertComing('3秒内请勿重复提交!') ;
            return false ;
        }
        setTimeout(function () {
            submitflag = false ;
        },3000);
        submitflag = true ;

        tranMoneyAction(f_blance,t_blance,dat);
    })
}

// 一键回收
function oneRecovery() {
    $('.btn_retrieve').off().on('click',function () {
        $('.thirdYe').each(function (i,v) {
            var f_blance = $(this).find('a').attr('data-type'); // 转出平台
            var t_blance = 'hg'; // 转入平台
            var blance = $(this).find('span').text(); // 金额
            if(blance=='加载中' || blance=='加载中...'){
                blance ='0';
            }
            blance = blance.replace(',',''); // 去掉千位符,需要字符串，不能是 number
            blance = Math.floor(blance);
            // console.log(f_blance+'=='+blance);
            var dat={
                f: f_blance,
                t: t_blance,
                b: blance,
                uid: uid
            };
            tranMoneyAction(f_blance,t_blance,dat);
        })
    })
}

// 平台余额转账,转入转出操作
function clickChangeMoney() {
    $('.turn_inout').on('click','a',function () {

        var f_blance = $(this).attr('data-from') ; // 转出方
        var t_blance = $(this).attr('data-to') ; // 转入方
        var t_type = $(this).attr('data-type') ; // 特殊情况
        var fromplat = $('.plateform_sel option').not(function(){ return !this.selected }).attr('data-to') ; // 转出平台
        var blance = $('.alert_input').val() ;
        //var platform = $(this).data('platform');// 当前转账平台

        var dat={
            id: id,
            uid: uid,
            userName: userName,
            action: 'fundLimitTrans',
            f: f_blance,
            t: t_blance,
            b: blance
        };

        if(t_type){
            if(t_type=='in'){ // 转入到第三方
                dat.f = 'hg';
                dat.t = fromplat;
            }else{ // 转到平台
                dat.f = fromplat ;
                dat.t = 'hg';
            }
            if((dat.f=='' || dat.t=='')){
                setPublicPop('请选择转账平台');
                return false;
            }
            if(!checkInputFloat(blance) || blance==''){
                setPublicPop('请输入正确的转账金额');
                return false;
            }

            if(submitflag){
                alertComing('3秒内请勿重复提交!') ;
                return false ;
            }
            setTimeout(function () {
                submitflag = false ;
            },3000);
            submitflag = true ;

            tranMoneyAction(dat.f,dat.t,dat);
        }else{
            $(".change_pop_bg , .change_Pop-up").show();  // 显示弹窗
            $(document).off().on("click", '.change_pop_bg , .close_event,.change_Pop-up .change_login_btn ', function(){
                if($(this)[0].className=='change_login_btn'){ // 确定按钮回调函数
                    blance = $('.alert_input').val() ; // 这里需要重新取值
                    dat.b = blance;
                    if(!checkInputFloat(blance) || blance==''){
                        $(".change_pop_bg,.pop_close").hide();
                        setPublicPop('请输入正确的转账金额');
                        return false;
                    }

                    if(submitflag){
                        alertComing('3秒内请勿重复提交!') ;
                        return false ;
                    }
                    setTimeout(function () {
                        submitflag = false ;
                    },3000);
                    submitflag = true ;

                    tranMoneyAction(dat.f,dat.t,dat);
                }
                $(".change_pop_bg,.pop_close").hide();

            });

        }


    });
}