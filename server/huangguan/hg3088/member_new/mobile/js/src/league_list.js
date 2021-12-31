
var clickflage = false ; // 500 毫秒 内防止重复点击
var clicktime = 500 ; // 500 毫秒 内防止重复点击

var $div_bet = $('#div_bet'),
    dialogHeight = $div_bet.height(),
    footerHeight = $('#footer').height();
$div_bet.css('bottom', -dialogHeight); // 投注底部弹出窗

// 赔率为空或者为0 返回 close_thegame ,ior 赔率
function returnCloseGame(ior) {
    var str_clas = '' ;
    if(ior==''||ior==0){
        str_clas = 'close_thegame' ;
    }
    return str_clas ;
}
// 波胆 赔率为空或者为0 返回 hide-cont ,ior1,ior2,ior3 赔率
function returnBoDanCloseGame(ior1,ior2,ior3) {
    var str_clas = '' ;
    if( (ior1==''||ior1==0) && (ior2==''||ior2==0) && (ior3==''||ior3==0)){
        str_clas = 'hide-cont' ;
    }
    return str_clas ;
}

// ior 赔率为空，或者 undefined ，返回 close_all_game_list 类
function returnCloseallGameList(ior) {
    var str_clas = '' ;
    if(ior==''|| ior==0 || ior == undefined ){
        str_clas = 'close_all_game_list' ;
    }
    return str_clas ;
}

// 足球滚球的即时时间显示，转上半场,下半场 文字
function reTimeShow(re_time){
    var tmpset = re_time.split("^") ;
    // 2017-09-21 64.足球滾球-上半場 計時器00:00暫停時   會員端記分板和時節部分請幫秀比分（原顯示上半場00”)
    var showretime= '' ;
    if(tmpset[1]){
        showretime = tmpset[1].replace("'","");
    }
    var status = "";
    switch (tmpset[0]){
        case "HT":
            status = '半场';
            break;
        case "1H":
            status = '上半场';
            break;
        case "2H":
            status = '下半场';
            break;
        default:
            status = tmpset[0];
    }
    return status+' '+showretime ;
}



// 展开与收缩列表
function spreadAction() {
    $('.bet-content').on('click', '.expand_action ', function() {
        var $child = $(this).children('span:first-child')  ;
        if($child.hasClass('arrow_close')) { // 收缩
            $child.removeClass('arrow_close').addClass('arrow_open');
            $(this).next().hide() ;
        } else { // 展开
            $child.removeClass('arrow_open').addClass('arrow_close');
            $(this).next().show() ;
        }
    })
}

/*
* 可赢金额=交易金额*赔率。
  除了后面几种玩法例外，独赢、单双、波胆、总进球、双方球队进球、半场/全场、双重机会,双半场进球, 三项让球投注,赢得任一半场 ,赢得所有半场,最多进球的半场, 最多进球的半场 - 独赢 : 可赢金额=交易金额*（赔率-1）,
 参数 ：spe 特殊玩法
 综合过关也需要减去本金：可赢金额 ior1 * iro2 * iro3 *.. * 投注金额-投注金额
* */

function CountWinGold() {
    var $betGold = $('#betGold') ; // 金额输入框
    var $bet_win_gold = $('#bet_win_gold') ; // 可赢金额选择器
    var $submitSrc = $('#submitSrc') ; // 投注按钮

    $betGold.on('keyup',function () {
        var spegame = $submitSrc.data('spegame') ; // 判断是否属于特殊玩法，如果是特殊玩法，需要减去本金
        var tiptype = $submitSrc.data('tiptype') ; // 判断是否属于综合过关 p3
        var bet_ior = 1 ;
        if(tiptype=='p3'){ // 综合过关
            $('#normal_order_model ul').each(function (i,v) {
                var p3_ior = $(this).data('ior') ;
                bet_ior *= p3_ior ;
            });
        }else{
            var bet_ior = Number($('#bet_ior').find('.ratio_red').text()) ; // 赔率
        }

        var inputval = Number($betGold.val()) ; // 输入投注金额
        var bet_win = Math.round(bet_ior*inputval*100) ; // 可赢金额
        bet_win = bet_win/100 ; // 防止出现多位小数
        var spe_bet_win = Math.round((bet_win-inputval)*100) ; // 需要减去本金玩法
        spe_bet_win = spe_bet_win/100 ;
        $('#bet_gold2_tt').text(inputval) ; // 投注按钮投注金额
        if(spegame){ // 特殊，需要减去本金
            $bet_win_gold.html(spe_bet_win) ;
        }else{
            $bet_win_gold.html(bet_win) ;
        }

    });

}


// 显示与隐藏下注表单 ,下注的时候 .content-center{ overflow-y: hidden; }
function showBetWindow() {
    var $mask = $('.bet_mask'); // 背景
    var $content = $('.content-center');
    var $submitSrc = $('#submitSrc') ; // 下注按钮
    var p3_tip_title = '不能选择不同类型的赛事!' ;

    $('body').on('click','.odds_box,.bet_action',function () { // 点击赔率，显示下注窗口
        var ifnorate = $(this).find('.close_thegame').length ; // 判断是否没有赔率
        var tiptype = $(this).data('tiptype') ; // 判断是否是综合过关下注
        var is_choose = $(this).hasClass('odds_box_up') ; // 综合过关是否选中
        var is_choose_num = 0 ;// 综合过关是否选中数值
        if(ifnorate>0){ // 无赔率
            return false ;
        }
        var gid = $(this).data('gid') ;
        var type = $(this).data('type') ;
        var wtype = $(this).data('wtype') ;
        var active = $(this).data('active') ;
        var order_method = $(this).data('method') ;
        var typecase = $(this).data('case') ;
        var randomnum = randomWord(false, 32) ; // 下注随机数
        var odd_f_type = 'H' ; // 默认香港盘
        var error_flag = '' ;
        var order_type = '' ;
        var rtype = $(this).data('rtype') ; // 所有玩法需要传
        var flag = $(this).data('flag') ; // 所有玩法需要传
        var spegame = $(this).data('spegame') ;// 是否属于特殊玩法，计算可赢金额
        var teamh = $(this).data('teamh') ; // 主队名称，综合过关用

        if(!rtype){ // underfind
            rtype = '' ;
        }
        if(!flag){ // underfind
            flag = '' ;
        }

        if(tiptype=='p3'){ // 综合过关 下注 teamcount=2&game=PRC,PE,...&game_id=3366482,3369368,...
            var selectet_p3BetArray = countP3BetNumber() || new Array() ; // 已经选择的综合过关注单
            // console.log(selectet_p3BetArray) ;
            if(is_choose){ // 取消选择
                if(active != selectet_p3BetArray[0].active){ // 不能选择不同类型的赛事
                    setPublicPop(p3_tip_title) ;
                    return false ;
                }
                $.each(selectet_p3BetArray,function (i,v) {
                    if(v.game_id==gid || v.teamh==teamh){ // 不需要重复添加赛事,替换原来的相同 gid 数据
                        selectet_p3BetArray.splice(i, 1); //删除下标为i的元素
                    }

                });
                $(this).removeClass('odds_box_up');
                localStorage.setItem('p3BetArray',JSON.stringify(selectet_p3BetArray)); // 更新最新
            }else{ // 选中

                if(!selectet_p3BetArray || selectet_p3BetArray==''){ // 首次选择
                    selectet_p3BetArray.push({game:rtype,game_id:gid,active:active,teamh:teamh});
                }else{
                    if(active != selectet_p3BetArray[0].active) { // 不能选择不同类型的赛事
                        setPublicPop(p3_tip_title);
                        return false;
                    }
                    $.each(selectet_p3BetArray,function (i,v) {
                        if(v.game_id==gid || v.teamh==teamh){ // 不需要重复添加赛事,替换原来的相同 gid 数据
                            selectet_p3BetArray.splice(i, 1); //删除下标为i的元素
                            selectet_p3BetArray.push({game:rtype,game_id:gid,active:active,teamh:teamh}); // 重新添加选择
                            is_choose_num++ ;
                        }

                    });

                    if(is_choose_num==0){
                        selectet_p3BetArray.push({game:rtype,game_id:gid,active:active,teamh:teamh});
                    }
                    is_choose_num =0; // 重置

                }
                $('.bet_action').removeClass('odds_box_up');
                $('.bet_action_p3_'+gid).removeClass('odds_box_up'); // 删除类
                $('.bet-content .odds_box,.bet-content .bet_action').each(function (i,v) { // 删除类
                    var teamname = $(this).data('teamh') ;
                    if(teamname==teamh){
                        $(this).removeClass('odds_box_up');
                    }

                })
                $(this).addClass('odds_box_up');
                localStorage.setItem('p3BetArray',JSON.stringify(selectet_p3BetArray));
            }

            $('#p3_bet_number').html(selectet_p3BetArray.length) ;
            // console.log(selectet_p3BetArray.length)

        }else {  // 非综合过关 下注
            $submitSrc.attr({'data-active':active,'data-gid':gid,'data-case':typecase,'data-type':type,'data-randnum':randomnum,'data-spegame':spegame}) ; // 添加下注属性到下注按钮
            readyToBetAction(gid,wtype,type,order_method,odd_f_type,error_flag,order_type,rtype,flag);
            $mask.show();
            // $div_bet.removeClass('box_off').addClass('box_on');
            $content.css({'overflow-y':'hidden'}) ;
            $div_bet.animate({'bottom': '0'},200);
        }

    });

    // 关闭下注窗口
    $('#div_bet_title,#clear_order,#plus_btn').on('click',function () {
        closeBetAction();
    });

}

/*
*  综合过关遍历已经选了多少注
* */
function countP3BetNumber(ischoose) {
    var selectet_p3BetArray = JSON.parse(localStorage.getItem('p3BetArray')) || new Array() ;
    if(ischoose){
        $('#p3_bet_number').html(selectet_p3BetArray.length);
        $.each(selectet_p3BetArray,function (i,v) {
            $('.bet_p3_'+v.game+'_'+v.game_id).addClass('odds_box_up') ;

        });
    }else{ // 返回已选数据
        return selectet_p3BetArray ;
    }

}

/*
*  综合过关准备下注触发按钮
*  order/order_prepare_p3_api.php?teamcount=2&game=PRC,PE,...&game_id=3366482,3369368,...
* */

function betP3ReadyAction() {
    var $mask = $('.bet_mask'); // 背景
    var $content = $('.content-center');
    var $submitSrc = $('#submitSrc') ; // 下注按钮
    var $div_err = $('#div_err') ;
    var $err_msg = $('#err_msg') ;
    $('.p3_bet_action').on('click',function () {
        deleteP3BetContent(); // 删除已选注单
        $('#plus_btn').show();
        var bet_num = $('#p3_bet_number').html() ;
        var randomnum = randomWord(false, 32) ; // 下注随机数

        if(bet_num == 0){ // 未选择注单
            setPublicPop('请选择注单!') ;
            return false ;
        }else{ // 已选择注单
            if(bet_num < 3) { // 注单数量不够
                $div_err.show();
                $err_msg.html('请至少选择3项!');
            }else{
                $div_err.hide();
                $err_msg.html('');
            }
            var gid = '' ;
            var game = '' ;
            var selectet_p3BetArray = countP3BetNumber() || new Array();
            $.each(selectet_p3BetArray,function (i,v) { // game game_id
                if(i ==0){
                    gid += v.game_id ;
                    game += v.game ;
                }else{
                    gid += ','+v.game_id ;
                    game += ','+v.game ;
                }

            });

            readyToBetAction(gid,'','','','','','',game,'','p3',bet_num) ;
            $mask.show();
            $content.css({'overflow-y':'hidden'}) ;
            $div_bet.animate({'bottom': '0'},200);
            $submitSrc.attr({'data-randnum':randomnum,'data-spegame':'spegame','data-tiptype':'p3'}) ; // 添加下注属性到下注按钮
        }

    });
}

/*
*  删除按钮综合过关已选注单
* */

function deleteP3BetContent() {
    $('#normal_order_model').on('click','.closeBtn',function () {
        var selectet_p3BetArray = countP3BetNumber() || new Array() ; // 已经选择的综合过关注单
        var gid = $(this).data('gid') ;
        var rtype = $(this).data('rtype') ;
        $(this).parent('ul').remove() ;
        $('.bet_p3_'+rtype+'_'+gid).removeClass('odds_box_up');
        $.each(selectet_p3BetArray,function (i,v) {
            if(v.game_id==gid){ // 不需要重复添加,替换原来的相同 gid 数据
                selectet_p3BetArray.splice(i, 1); //删除下标为i的元素
            }

        });
        $('#p3_bet_number').html(selectet_p3BetArray.length) ; // 更新数量
        localStorage.setItem('p3BetArray',JSON.stringify(selectet_p3BetArray)); // 更新最新

    });

}
/*
* 准备下注接口删除异常综合过关注单
* */
function deleteP3Error(gid) {
    var selectet_p3BetArray = countP3BetNumber() || new Array() ; // 已经选择的综合过关注单
    var select_gid_arr  ;
    if (gid.indexOf(",") != -1) {
        select_gid_arr = gid.split(',') ;
    }else { // 只有一个 gid
        select_gid_arr = new Array(gid) ;
    }
    $.each(selectet_p3BetArray,function (i,v) {
        $.each(select_gid_arr,function (j,w) {
            if(v.game_id==w){ // 不需要重复添加,替换原来的相同 gid 数据
                selectet_p3BetArray.splice(i, 1); //删除下标为i的元素
            }
        }) ;

    });

    $('#p3_bet_number').html(selectet_p3BetArray.length) ; // 更新数量
    localStorage.setItem('p3BetArray',JSON.stringify(selectet_p3BetArray)); // 更新最新
}

// 关闭下注
function closeBetAction() {
    var $mask = $('.bet_mask'); // 背景
    var $content = $('.content-center');
    var $betGold = $('#betGold') ; // 金额输入框

    $div_bet.animate({'bottom': -3*dialogHeight + 'px'},300);
    $betGold.val('');
    $('#bet_win_gold,#bet_gold2_tt').text('0.00');
    $mask.hide();
    // $div_bet.removeClass('box_on').addClass('box_off');
    $content.css({'overflow-y':'scroll'}) ;

}

// 关闭交易成功单页
function closeBetFinish() {
    $('.finish_bet_btn').on('click',function () {
        $('.bet-sure-content,.bet_mask').hide();
    });
}

// 清除输入框金额
function clearInputMon() {
    $('#clear_btn').on('click',function () {
        $('#betGold').val('') ;
        $('#bet_win_gold,#bet_gold2_tt').html('0.00') ;
    });
}

// 提交投注, gtype : 足球 FT 篮球 BK，showtype: 今日/早盘 today 滚球 rb ，tiptype: champion 冠军参数,p3 综合过关
function betSureAction(gtype,showtype,tiptype) {
    /*
       *  /order/order_finish_api.php
       *  足球今日赛事与早盘投注: FT_order_finish_api.php
       *  足球滚球全场投注: FT_order_re_finish_api.php
       *  足球滚球半场投注: FT_order_hre_finish_api.php
       *  篮球今日赛事与早盘投注: BK_order_finish_api.php ,
       *  篮球滚球投注: BK_order_re_finish_api.php
       *  冠军投注（篮球与足球公用）: FT_order_nfs_finish_api.php
       *  足球独赢玩法投注传参
       *  gid  比赛盘口唯一ID
       *  active  1
       *  line_type  玩法列号
       *  odd_f_type  H 香港盘  (都是香港盘)
       *  gold  金额
       *  type   H 主队 C 客队 N 和局
       *  cate   FT 足球， FT_RB 足球滚球，BK 篮球，BK_RB 篮球滚球
       * */
    var betflag = false ; // 防止重复提交
    var rb_tip = '' ;

    $('#submitSrc').on('click',function () {

        var bet_rate = $('#bet_ior').find('.ratio_red').text() ; // 赔率
        var typecase =$(this).data('case') ; // case
        var gid =$(this).data('gid') ; // gid
        var type =$(this).data('type') ; // type
        var active =$(this).data('active') ; // type
        var linetype =$(this).data('linetype') ; // type
        var w_type =$(this).data('wtype') ; // 只有得分大小有
        var rtype =$(this).data('rtype') ; // 只有得分大小有
        var randnum =$(this).data('randnum') ; // 只有得分大小有

        var usermon = Number($('#bet_credit').html()) ; // 用户余额
        var minbet = Number($('#minbet').html()) ; // 最低投注额
        var maxbet = Number($('#maxbet').html()) ; // 最高投注额
        var $betGold = $('#betGold') ; // 金额输入框
        var $div_err = $('#div_err') ; // 错误提示显示
        var $err_msg = $('#err_msg') ; // 错误提示框
        var inputval = Number($betGold.val()) ; // 输入投注金额
        var p3_wagerDatas = '' ;
        var auto_odd = $('.auto_Odd').is(':checked');

        if(auto_odd){
            auto_odd = 'Y';
        }else{
            auto_odd = '';
        }

        if(tiptype !='p3'){ // 综合过关没有
            var rb_h_tip = w_type.substr(0,1); // 第一位是 H 表示半场
        }

        if(!checkInputInt(inputval)){ // 非整数
            $div_err.show() ;
            $err_msg.html('请输入整数的投注金额!');
            return false ;
        }
        if(minbet > inputval){
            $div_err.show() ;
            $err_msg.html('投注金额不可小于单注最低金额!');
            return false ;
        }
        if(maxbet < inputval){
            $div_err.show() ;
            $err_msg.html('投注金额不可大于单注最高金额!');
            return false ;
        }
        if(usermon < inputval ){
            $div_err.show() ;
            $err_msg.html('投注金额不可大于用户额度!');
            return false ;
        }


        var data_par={
            autoOdd: auto_odd , // 自动接收最佳赔率
            cate: typecase ,
            gid: gid ,
            type: type ,
            active: active ,
            line_type: linetype ,
            odd_f_type: 'H' , // 都是香港盘 H
            gold: inputval ,
            ioradio_r_h: bet_rate , // 让球需要传赔率
            rtype: rtype , // 单双独有  ODD 单， EVEN双
            wtype: w_type, // 只有篮球滚球半场得分大小有
            randomNum: randnum  // 随机数

        }
        if(showtype=='RB' || showtype=='r'){ // 滚球
            rb_tip = '_re' ;
        }
        if(gtype=='FU') {
            gtype = 'FT'
        }else if(gtype=='BU'){
            gtype = 'BK' ;
        }
        if(tiptype=='champion'){ // 冠军
            var beturl = '/order/FT_order_nfs_finish_api.php' ;
        }else if(tiptype=='p3'){ // 综合过关
            var maxpayout = Number($('#bet_win_gold').data('maxpayout')) ; // 最高派彩金额
            var winmoney = Number($('#bet_win_gold').html()) ; // 可赢金额
            var beturl = '/order/'+gtype+'_order_p_finish_api.php' ;

            $('#normal_order_model ul').each(function (i,v) { // game game_id
                var p3_gid = $(this).data('gid') ;
                var p3_rtype = $(this).data('rtype') ;
                // var p3_ior = changeTwoDecimal($(this).data('ior'),'roundUp') ;
                var p3_ior = $(this).data('ior') ;
                if(i==0){
                    p3_wagerDatas += p3_gid+ ','+p3_rtype+','+ p3_ior+'|' ;
                }else{
                    p3_wagerDatas += p3_gid+ ','+p3_rtype+','+ p3_ior+'|' ;
                }

            });
            var p3_active = '' ;
            var selectet_p3BetArray = countP3BetNumber() || new Array();
            $.each(selectet_p3BetArray,function (i,v) { // game game_id
                p3_active = v.active ;

            });
            // 最少3 项，最多10 项
            var selectet_p3BetArray_length =  selectet_p3BetArray.length ;
            if(selectet_p3BetArray_length<3){
                $div_err.show() ;
                $err_msg.html('请至少选择3项!');
                return false ;
            }
            if(maxpayout < winmoney){
                $div_err.show() ;
                $err_msg.html('可赢金额不能超过最高派彩金额'+maxpayout);
                return false ;
            }
            if(selectet_p3BetArray_length>10){
                $div_err.show() ;
                $err_msg.html('不接受'+selectet_p3BetArray_length+'串过关投注!');
                return false ;
            }

            data_par ={
                active: p3_active ,
                teamcount: selectet_p3BetArray_length ,
                odd_f_type: 'H' , // 都是香港盘 H
                gold: inputval ,
                wagerDatas: p3_wagerDatas ,
                randomNum: randnum , // 随机数

            }

        }else{
            var beturl = '/order/'+gtype+'_order'+rb_tip+'_finish_api.php' ;
            if(rb_h_tip=='H' && (showtype=='RB' || showtype=='r') &&  gtype=='FT'){ // 足球滚球 半场
                var beturl = '/order/'+gtype+'_order_hre_finish_api.php' ;
            }
        }

        $div_err.hide() ; // 错误提示清空
        $err_msg.html(''); // 错误提示清空

        if(betflag){
            return false ;
        }
        betflag = true ;
        $.ajax({
            url: beturl ,
            type: 'POST',
            dataType: 'json',
            data: data_par,
            success: function (res) {
                /*
                * {"status":"200",
                * "describe":"投注成功",
                * "timestamp":"20180827034927",
                * "data":[{
                * "caption":"足球单式让球交易单",
                * "Order_Bet_success":"交易成功单号：",
                * "order":"RHQ808278215755100495",
                * "s_sleague":"澳大利亚昆士兰州国家超级联赛U20",
                * "M_Date":"08-27",
                * "s_mb_team":"瑞德兰茨联U20",
                * "Sign":"0 / 0.5",
                * "s_tg_team":"昆士兰狮队U20",
                * "s_m_place":"瑞德兰茨联U20",
                * "w_m_rate":"0.93",
                * "gold":"20",
                * "order_bet_amount":18.6,
                * "havemoney":12527.2
                * }],
                * "sign":"048c859c3b0643ac76d50a2fac2bff4a"}
                * */

                if(res.status !='200'){ // 有错误信息
                    setTimeout(function () {
                        betflag = false ;
                    },3000); // 3 秒内禁止重复提交
                    setPublicPop(res.describe);
                    if(res.status =='401.1'){ // 重新登录
                        window.location.href = 'login.php' ;
                    }
                }else{ // 成功下注
                    setTimeout(function () {
                        betflag = false ;
                    },3000); // 3 秒内禁止重复提交
                    if(tiptype=='p3'){ // 综合过关
                        localStorage.removeItem('p3BetArray'); // 成功后删除已选择的数据
                        $('#p3_bet_number').html('0') ;
                        $('.odds_box').removeClass('odds_box_up') ;
                        var arr_league = res.data[0].s_league.split(',') ; // 联赛
                        var arr_m_place = res.data[0].s_m_place.split(',') ; // 投注内容
                        var arr_mb_team = res.data[0].s_mb_team.split(',') ; // 主队
                        var arr_tg_team = res.data[0].s_tg_team.split(',') ; // 客队
                        var arr_sign = res.data[0].sign.split(',') ;
                        var arr_m_rate = res.data[0].w_m_rate.split(',') ; // 赔率
                        var arr_btype = res.data[0].btype.split(',') ; // 半场标志
                        betFinishContentData(res.data[0],'p3',arr_league,arr_m_place,arr_mb_team,arr_tg_team,arr_sign,arr_m_rate,arr_btype) ;
                    }else{
                        betFinishContentData(res.data[0]) ;
                    }
                    $('#clear_order').click() ; // 关闭下注框
                    $('.bet_mask').show(); // 背景
                    $('.bet-sure-content').show() ; // 显示成功交易单
                    $('.bet_order_num').html(res.data[0].order) ; // 订单号

                    // $('.finish_bet_league').html(res.data[0].s_sleague) ; // 联赛
                    // $('.finish_bet_team').html(res.data[0].s_mb_team+' <font class="ratio_red">'+ res.data[0].Sign +'</font> '+res.data[0].s_tg_team) ; // 球队
                    // $('.finish_bet_content').html(res.data[0].s_m_place+' @ '+res.data[0].w_m_rate) ; // 投注内容

                    $('.finish_bet_mon').html(res.data[0].gold) ; // 投注金额
                    $('.finish_bet_win').html(res.data[0].order_bet_amount) ; // 可赢金额
                    $('.user_money').html(res.data[0].havemoney) ; // 用户余额
                    $('.bet_caption').html(res.data[0].caption) ; // 用户投注方式

                }

            },
            error:function (msg) {
                setTimeout(function () {
                    betflag = false ;
                },3000); // 3 秒内禁止重复提交
                setPublicPop(config.errormsg);
            }

        }) ;
    });

}

/*
*  投注成功后投注内容
*  tiptype :p3 综合过关
*  btype 半场标志  btype:"- [上半]"
* */

function returnFinishBetContent(str,s_sleague,s_mb_team,Sign,s_tg_team,s_m_place,w_m_rate,inball,btype,tiptype) {
    str = '<ul class="content_ul_li"><li class="finish_bet_league">'+ s_sleague +'</li>' +
        ' <li class="finish_bet_team"><span class="ratio_red">'+ inball + (inball?'&nbsp;':'') +'</span>'+ (s_mb_team+' <font class="ratio_red">'+ Sign +'</font> '+s_tg_team) +'</li>' +
        ' <li class="finish_bet_content"><font class="ratio_red">'+ s_m_place+' <span class="bet_type_color" style="color: #666666">' +btype+ '</span>  @ '+w_m_rate +'</font></li></ul>' ;
    return str ;
}

/*
投注成功后
* tiptype 综合过关 p3
* */
function betFinishContentData(res,tiptype,arr_league,arr_m_place,arr_mb_team,arr_tg_team,arr_sign,arr_m_rate,arr_bettype) { // res = res.data[0] ;
    var str ='' ;
    if(tiptype=='p3'){  // 综合过关 p3
        for(var i=0;i < arr_league.length;i++){
            //console.log(i)
            str += returnFinishBetContent(str,arr_league[i],arr_mb_team[i],arr_sign[i],arr_tg_team[i],arr_m_place[i],arr_m_rate[i],'',arr_bettype[i],tiptype) ;
        }
    }else{
        str += returnFinishBetContent(str,res.s_sleague,res.s_mb_team,res.Sign,res.s_tg_team,res.s_m_place,res.w_m_rate,res.inball,res.btype,tiptype) ;
    }
    $('.bet_order_allcontent').html(str) ;
}

// 加载loading
function loadingContent(par) {
    var $mask = $('.mask') ;
    if(par ==true){
        $mask.show() ;
        $mask.html('正在加载中...') ;
    }else{
        $mask.hide();
        $mask.html('') ;
    }
}

/* 点击切换滚球，
* 今日，早盘赛事, gtype: FT 足球，
* BK 篮球 ,fsshowtype 冠军 showtype
* tiptype : champion 冠军，p3 综合过关
*/

function changeSportMatches(obj,gtype,mdate,fsshowtype) {
    if(clickflage){
        return false ;
    }
    clickflage = true ;
    // $(obj).addClass('active').siblings().removeClass('active');
    var type = $(obj).data('type') ;
    var showtype ;
    var more_par = '' ; // 冠军参数
    switch (type){
        case 'RBMATCH': // 滚球
            showtype = 'RB';
            break;
        case 'TODAYMATCH': // 今日
            showtype = gtype ;
            break;
        case 'FUTUREMATCH': // 早盘
            if(gtype=='FT'){ // 足球
                showtype = 'FU' ;
            }else{ // 篮球
                showtype = 'BU' ;
            }

            break;
        case 'ALLMATCH': // 全部 大小
            showtype = fsshowtype ;
            break;
        case 'CHAMPION': // 冠军
            more_par = '&FStype='+gtype+'&mtype=4&tiptype=champion' ;
            showtype = fsshowtype ;

            break;
        case 'P3MATCH': // 综合过关
            more_par = '&FStype='+gtype+'&mtype=4&tiptype=p3' ;
            showtype = fsshowtype ;

            break;


    }
    setTimeout(function () {
        clickflage = false ;
    },clicktime);
    window.location.href = 'sport.php?gtype='+gtype+'&showtype='+showtype+'&sorttype=league&mdate='+mdate+more_par ;
    // getLeagueMatches(gtype,showtype,'league',mdate) ; // 默认联盟排序 和全部日期

}

// 日期选择，早盘赛事
function changeSportDates(obj,gtype,showtype,sorttype,tiptype) {
    if(clickflage){
        return false ;
    }
    clickflage = true ;
    var mdate = $(obj).val() ;
    var more_par = '' ; // 冠军参数
    setTimeout(function () {
        clickflage = false ;
    },clicktime);
    if(tiptype=='champion'){ // 冠军
        more_par = '&FStype='+gtype+'&mtype=4&tiptype=champion' ;
    }else if(tiptype=='p3'){ // 综合过关
        more_par = '&FStype='+gtype+'&mtype=4&tiptype=p3' ;
    }
    window.location.href = 'sport.php?gtype='+gtype+'&showtype='+showtype+'&sorttype='+sorttype+'&mdate='+mdate+more_par ;

}

// 联盟排序 和 时间排序选择
function changeSportLeague(obj,gtype,showtype,mdate,tiptype) {
    if(clickflage){
        return false ;
    }
    clickflage = true ;
    var league = $(obj).val() ; // 排序类型
    var more_par = '' ; // 冠军参数
    setTimeout(function () {
        clickflage = false ;
    },clicktime);
    if(tiptype=='champion'){ // 冠军
        more_par = '&FStype='+gtype+'&mtype=4&tiptype=champion' ;
    }else if(tiptype=='p3'){ // 综合过关
        more_par = '&FStype='+gtype+'&mtype=4&tiptype=p3' ;
    }
    window.location.href = 'sport.php?gtype='+gtype+'&showtype='+showtype+'&sorttype='+league+'&mdate='+mdate+more_par ;

}


// 下拉显示与隐藏
function showDownMenu(obj) {
    var num = $(obj).data('num') ;
    if(num=='1'){
        $('.subaccountform_menu').show();
        $(obj).attr('data-num','2').addClass('menu_icon_active');
    }else{
        $('.subaccountform_menu').hide();
        $(obj).attr('data-num','1').removeClass('menu_icon_active');
    }

}

/*
* {
* "status":"200",
* "describe":"success",
* "timestamp":"20180823062641",
* "data":[
* {"gid":"3336340,3336348,3336344","M_League":"冰岛甲组联赛","num":3},
* {"gid":"3334138,3334128,3334130,3334132,3334134,3334136","M_League":"巴西甲组联赛","num":6},
* {"gid":"3334140","M_League":"巴西甲组联赛-特别投注","num":1},
* {"gid":"3334142,3334144","M_League":"芬兰甲组联赛","num":2},
* {"gid":"3336364","M_League":"立陶宛甲组联赛","num":1},
* {"gid":"3335536,3335518,3335532,3335530,3335520,3335528,3335526,3335522","M_League":"美国职业大联盟","num":8},
* {"gid":"3335538","M_League":"美国职业大联盟-特别投注","num":1},
* {"gid":"3349992","M_League":"秘鲁甲组联赛","num":1},
* {"gid":"3342694,3342696,3342702,3342720,3342704,3342710,3342712,3342706,3342708,3342718,3342700,3342714,3342716,3342698,3342722,3342724,3342726,3342728,3342738,3342740,3342736,3342730,3342732,3342734,3342692,3342660,3342684,3342658,3342680,3342678,3342664,3342662,3342682,3342666,3342690,3342668,3342670,3342672,3342674,3342676,3342686,3342688","M_League":"欧足联欧洲联赛外围赛","num":42},
* {"gid":"3346480,3346500,3347782,3346504,3346496,3347790,3351096,3347798,3347794,3346484,3346492,3346488","M_League":"瑞典杯","num":12},
* {"gid":"3349986,3349980,3349982,3349984","M_League":"亚运会2018男子足球U23(在印尼)","num":4},
* {"gid":"3347762,3347770,3347766","M_League":"伊朗超级联赛","num":3},
* {"gid":"3348974,3349988,3348982,3348970,3348978","M_League":"以色列甲组联赛","num":5},
* {"gid":"3348966","M_League":"印度加尔各答甲组联赛A","num":1}],"sign":"6837c17023d06cac34338d6d76d780bf"}
* */

/**
 * /var_lid_api.php  体育联赛数据接口
 *
 * @param  gtype   FT 足球，BK 篮球
 * @param  showtype   RB 滚球 FT 今日赛事 FU 早盘
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  早盘日期
 */
function getLeagueMatches(gtype,showtype,sorttype,mdate,fstype) {
    if(clickflage){
        return false ;
    }
    clickflage = true ;
    var more ;
    var fs_showtype = '' ; // 冠军 showtype , 早盘需要传  future

    if(showtype == 'RB'){ // 滚球
        more = 'r' ;
    }else{
        more = 's' ;
    }
    var params = {
        gtype: gtype ,
        showtype: showtype ,
        sorttype: sorttype ,
        mdate: mdate ,
    } ;
    $('#title_gtype').html(gtype=='FT'?'足球':'篮球');
    if(gtype =='BK'){ // 篮球单独处理
        if(showtype =='FT'){ // 今日 和 滚球
            showtype ='BK' ;
        }else if( showtype =='RB'){ // 滚球
            showtype ='RB' ;
        }else{ // 早盘
            showtype ='BU' ;

        }
    }
    if(fstype=='champion'){ // 冠军
        var ajaxurl = '/loadgame_R_api.php';
        params.FStype = gtype ;
        params.mtype = '4' ;
        if(showtype=='FU' || showtype=='BU'){
            fs_showtype = 'future' ;
        }

    }else if(fstype=='p3'){ // 综合过关
        var ajaxurl = '/var_lid_p3_api.php';
        params.FStype = gtype ;
        params.mtype = '4' ;
        if(showtype=='FU' || showtype=='BU'){
            fs_showtype = 'future' ;
        }
    } else{
        var ajaxurl = '/var_lid_api.php';
    }
    loadingContent(true) ;
    $.ajax({
        url: ajaxurl ,
        type: 'POST',
        dataType: 'json',
        data: params,
        success: function (res) {
            if(res.status=='200'){ // 请求数据成功
                loadingContent(false) ;
                var str = '' ;
                if(res.data.length>0){ // 有数据
                    // $('.has_sport_matches').show();
                    $('.NoEvent_game').hide();
                    /*
                     * 冠军联赛数据接口
                     * FStype   FT 足球 BK 篮球
                     * mtype   4
                     * showtype  future（早盘冠军）
                     * M_League  西班牙甲组联赛
                     *
                     *  * 联盟列表_综合过关
                     接口示例
                     http://m.hg50080.com/var_lid_p3_api.php?FStype=FT&mtype=4
                     * /var_lid_p3_api.php  体育联赛数据接口_综合过关
                     * @param  gtype   FT 足球，BK 篮球
                     * @param  sorttype   league 联盟排序  time 时间排序
                     * @param  mdate  日期
                     * @param  showtype 判断滚球是否维护-单页面维护功能
                     */

                    for(var i=0;i< res.data.length;i++){
                        if(fstype=='champion') { // 冠军
                            str +=  '<div data-leaid="'+ res.data[i].gid +'" class="inneraccordion" onclick="javascript:void(0);window.location.href=\'new_cate.php?FStype='+ gtype +'&mtype=4&showtype='+ fs_showtype +'&fstiptype='+showtype+'&tiptype=champion&M_League='+ res.data[i].M_League +'\'">' ;
                        }else if(fstype=='p3'){ // 综合过关
                            str +=  '<div data-leaid="'+ res.data[i].gid +'" class="inneraccordion" onclick="javascript:void(0);window.location.href=\'new_cate.php?FStype='+ gtype +'&mtype=4&showtype='+ fs_showtype +'&fstiptype='+showtype+'&tiptype=p3&M_League='+ res.data[i].M_League +'\'">' ;
                        } else{ // 其他
                            str +=  '<div data-leaid="'+ res.data[i].gid +'" class="inneraccordion" onclick="javascript:void(0);window.location.href=\'new_cate.php?gtype='+ gtype +'&mdate='+ mdate +'&type='+ showtype +'&more='+ more +'&gid='+ res.data[i].gid +'\'">' ;
                        }

                        str += '<div class="game_name"><span>'+ res.data[i].M_League +'</span></div>' +
                            '<div class="more_right">' +
                            '<div class="list_num">'+ res.data[i].num +'</div>' +
                            '<div class="list_arr"> </div>' +
                            '</div>' +
                            '</div>' ;
                    }

                    $('.league_list').html(str) ;

                }else{ // 没有数据
                    // $('.has_sport_matches').hide();
                    $('.NoEvent_game').show();
                }

                setTimeout(function () {
                    clickflage = false ;
                },clicktime);

            }

        },
        error: function (msg) {
            loadingContent(false) ;
            setPublicPop(config.errormsg);
            setTimeout(function () {
                clickflage = false ;
            },clicktime);
        }
    });
}


/**
 * /var_by_league_api.php  联赛下面的盘口列表（让球、大小）
 *
 * @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
 * @param  more   s 今日赛事， r 滚球
 * @param  gid  3321118,3321062
 */

/**
 * 点击联盟展示盘口列表_综合过关
 接口示例
 http://m.hg50080.com/var_lid_p3_api.php?gtype=FT&sorttype=league&mdate=2018-09-13&M_League=南非足协理事会女子冠军赛(在南非)&showtype=future
 * /var_lid_p3_api.php  体育联赛数据接口_综合过关
 * @param  gtype   FT 足球，BK 篮球
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  日期
 * @param  showtype
 * @param  M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
 */

//  league 联赛名称, showtype 早盘冠军 需要传 future, tiptype :champion 冠军 p3 综合过关
function getNewGameDetails(type,more,gid,fsleague,fsshowtype,tiptype) {
    var ctype = type.split('_') ; // 滚球时处理
    type = ctype[0] ;

    if(tiptype=='champion'){ // 冠军
        var action_url = '/loadgame_R_api.php' ; // 请求链接地址
        var params = {
            FStype: type,
            mtype: '4' ,
            M_League: fsleague ,
            showtype: fsshowtype ,
        } ;

    }else if((tiptype=='p3')){ // 综合过关
        var action_url = '/var_lid_p3_api.php' ; // 请求链接地址
        var params = {
            gtype: type,
            mtype: '4' ,
            M_League: fsleague ,
            showtype: fsshowtype ,
            sorttype: 'league' ,
            mdate: '' ,
        } ;
    } else{ // 其他
        var action_url = '/var_by_league_api.php' ; // 请求链接地址
        var params = {
            type: type,
            more: more,
            gid: gid,
        } ;
    }


    var twotype ;
    var typecase ;
    var showtype ;
    if(type =='FU' || type =='FT'){ // 足球
        twotype = 'FT' ;
    }else{ // 篮球
        twotype = 'BK' ;

    }
    if(more=='r'){  // 滚球
        typecase = twotype+'_RB' ;
        showtype = 'RB' ;
    }else{  // 今日和早盘 FU 早盘 FT 今日赛事
        typecase = twotype ;
        if(type=='FT' || type=='BK'){ // 今日赛事
            showtype = 'FT';
        }else{ // 早盘
            showtype = 'FU';
        }

    }

    var active_type = setActiveParams(type) ;
    loadingContent(true) ;
    $.ajax({
        url: action_url ,
        type: 'POST',
        dataType: 'json',
        data: params,
        success: function (res) {
            /* 今日赛事(早盘)  ShowTypeR (滚球 ShowTypeRB )为'C' 表示客队让球，H是主队让球 M_LetB , ShowTypeHR (滚球 ShowTypeHRB )为半场让球( H 为主队让球，C为客队让球，M_LetB_H )
            *  MB_Win_Rate 单场-独赢主队赔率 ，TG_Win_Rate 单场-独赢客队赔率 ，M_Flat_Rate 单场-独赢和局 ，M_LetB 主队让球数，MB_LetB_Rate 单场-让球主队赔率 ，TG_LetB_Rate 单场-让球客队赔率
            *  MB_Win_Rate_H 半场-独赢主队赔率 ，TG_Win_Rate_H 半场-独赢客队赔率 ，M_Flat_Rate_H 半场-独赢和局，M_LetB_H 半场-主队让球数， MB_LetB_Rate_H 半场-让球主队赔率 ，TG_LetB_Rate_H 半场-让球客队赔率
            *  MB_Dime_Rate_H 半场-大小得分 主队大的赔率，MB_Dime_Rate_S_H 主队半场小的赔率，TG_Dime_Rate_H 半场-得分大小 客队小的赔率, TG_Dime_Rate_S_H 半场-得分大小 客队小的赔率
            *  MB_Dime 单场-大小得分主队(大), TG_Dime 单场-大小得分客队(小)，MB_Dime_Rate 单场-大小得分主队(赔率)，TG_Dime_Rate 单场-大小得分客队(赔率)， MB_Dime_H 半场-大小得分主队(大)，MB_Dime_S_H 半场-大小得分主队(小)，TG_Dime_H 半场-大小得分客队(大)，TG_Dime_S_H 半场-大小得分客队(小)
            *  S_Single_Rate 主队单双赔率，S_Double_Rate 客队单双赔率，MB_Dime (TG_Dime) 主队( 客队)全场大小  O 大  U 小，
            *
            * */

            /* 滚球
           *  MB_Win_Rate_RB 单场-滚球-独赢主队赔率 ，TG_Win_Rate_RB 单场-滚球-独赢客队赔率 ，M_Flat_Rate_RB 单场-滚球-独赢和局
           *  M_LetB_RB 单场-滚球-主队让球数，T_LetB_RB 单场-滚球-客队让球数，MB_LetB_Rate_RB 单场-滚球-主队让球赔率，TG_LetB_Rate_RB 单场-滚球-客队让球赔率
           * MB_Dime_RB (TG_Dime_Rate_RB)  单场-滚球-主队(客队)全场大小  O 大  U 小，MB_Dime_Rate_RB(TG_Dime_Rate_RB) 单场-滚球-主队(客队)全场赔率
           * MB_Win_Rate_RB_H(TG_Win_Rate_RB_H) 半场-滚球-主队(客队)独赢赔率，M_Flat_Rate_RB_H 半场-滚球-和的赔率，M_LetB_RB_H 半场-滚球-让球让球数
           * MB_LetB_Rate_RB_H(TG_LetB_Rate_RB_H) 半场-滚球-主队(客队)让球的赔率，MB_Dime_RB_H(TG_Dime_RB_H) 半场-滚球-主队(客队)半场大小 O 大，MB_Dime_RB_S_H(TG_Dime_RB_S_H) 半场-滚球-主队(客队)半场大小 U 小
           * MB_Dime_Rate_RB_H(TG_Dime_Rate_RB_H) 滚球-半场-主队(客队)得分大小 大 的赔率，MB_Dime_Rate_RB_S_H(TG_Dime_Rate_RB_S_H) 滚球-半场-主队(客队)得分大小 小 的赔率
           *
           * */

            /* 大小 特殊： data-type ：主队 C，客队 H
            * [
            * {"gid":"3347900","M_Type":"1","M_Time":"05:30a","M_Date":"08-24","league":"\u6fb3\u5927\u5229\u4e9a\u6606\u58eb\u5170\u5dde\u5973\u5b50\u56fd\u5bb6\u8d85\u7ea7\u8054\u8d5b","gnum_h":"50788","gnum_c":"50787","team_h":"\u6606\u58eb\u5170\u72ee\u961f(\u5973)","team_c":"\u5357\u90e8\u8054\u5408(\u5973)","strong":"H","ratio":"0 \/ 0.5","ratio_mb_str":"0 \/ 0.5","ratio_tg_str":"","ior_RH":"0.79","ior_RC":"0.95","ratio_o":"O4","ratio_u":"U4","ratio_o_str":"\u59274","ratio_u_str":"\u5c0f4","ior_OUH":"","ior_OUC":"","more":0,"all":"5","eventid":"","hot":"","play":"","showretime":"","lastestscore_h":"","lastestscore_c":"","score_h":"","score_c":""},
            * {"gid":"3347904","M_Type":"1","M_Time":"06:30a","M_Date":"08-24","league":"\u6fb3\u5927\u5229\u4e9a\u6606\u58eb\u5170\u5dde\u5973\u5b50\u56fd\u5bb6\u8d85\u7ea7\u8054\u8d5b","gnum_h":"50794","gnum_c":"50793","team_h":"\u4f0a\u65af\u7279\u6069\u6c99\u4f2f(\u5973)","team_c":"\u5361\u5e15\u62c9\u5df4(\u5973)","strong":"H","ratio":"1","ratio_mb_str":"1","ratio_tg_str":"","ior_RH":"0.92","ior_RC":"0.82","ratio_o":"O4 \/ 4.5","ratio_u":"U4 \/ 4.5","ratio_o_str":"\u59274 \/ 4.5","ratio_u_str":"\u5c0f4 \/ 4.5","ior_OUH":"","ior_OUC":"","more":0,"all":"5","eventid":"","hot":"","play":"","showretime":"","lastestscore_h":"","lastestscore_c":"","score_h":"","score_c":""}]
            * */

            loadingContent(false) ;
            if(res.data =='' || res.data == null){ // 赛事已关闭
                $('#sport_div_show').hide();
                $('.NoEvent_game').show();

                // setPublicPop('暂无赛事!');
                return false ;
            }else{ // 有赛事
                $('#sport_div_show').show();
                $('.NoEvent_game').hide();

                if(tiptype=='champion') { // 冠军
                    changeFsTypeData(type,more,twotype,typecase,active_type,showtype,res) ;
                }else if(tiptype=='p3'){ // 综合过关
                    changeNotFsTypeData(type,more,twotype,typecase,active_type,showtype,res,'p3') ;
                    countP3BetNumber('ischoose') ;

                } else{
                    changeNotFsTypeData(type,more,twotype,typecase,active_type,showtype,res,'other') ;
                }

            }

        },
        error: function (msg) {
            loadingContent(true) ;
            setPublicPop(config.errormsg);
        }
    });
}

/*
 * /get_game_allbets.php  更多玩法/所有玩法 接口
 *
 * @param gid
 * @param gtype FT 足球 BK 篮球
 * @param showtype FU 早盘 FT 今日赛事 RB 滚球
 * tiptype :p3 综合过关 var_lid_p3_api.php
 * */

function getMoreGames(gid,gtype,showtype,tiptype,League) {
    var ajaxurl =  '/get_game_allbets.php' ;
    var params = {
        gid: gid ,
        gtype: gtype ,
        showtype: showtype ,
    };
    if(tiptype=='p3'){ // 综合过关
        ajaxurl =  '/var_lid_p3_api.php' ;
        params = {
            gid: gid ,
            gtype: gtype ,
            showtype: showtype ,
            M_League: League ,
        };
    }
    if(showtype=='RB'){ // 滚球才需要显示比分
        $('#game_score_h,#game_score_c').show() ;
    }else{
        $('#game_score_h,#game_score_c').hide() ;
    }
    loadingContent(true) ;
    var gidArray = new Array(); // gid 数组
    var arr_key = 0 ; // 用于取数组数据

    $.ajax({
        url: ajaxurl ,
        type: 'POST',
        dataType: 'json',
        data: params,
        success: function (res) {
            if(res.status=='200'){ // 请求数据成功
                loadingContent(false) ;
                if(res.data.length>0) { // 有数据

                    if(gtype=='BK'){ // 篮球 多组数据处理
                        for(var j=0;j<res.data.length;j++){
                            gidArray.push(res.data[j].gid) ;
                        }
                        $.each(gidArray,function (n,v) {
                            // console.log(v)
                            if(v==gid){ // 匹配当前 gid
                                arr_key = n ;
                                // console.log(n) ;
                            }
                        });

                    }
                    var resDataArr = res.data[arr_key] ;
                    // console.log(gidArray)

                    $('.NoEvent_game').hide();
                    $('#sport_div_show').show();
                    if(showtype=='FU' || showtype=='BU' ){ // 早盘需要显示日期
                        $('#game_time').html((resDataArr.datetime).substr(5,11)) ; // 比赛时间
                    }else if(showtype=='RB'){ // 滚球
                        var rb_time = reTimeShow(resDataArr.re_time).split(' ') ;
                        $('#game_live').html(rb_time[0]) ;
                        $('#game_time').html(rb_time[1]) ;
                    } else { // 今日赛事
                        $('#game_time').html(resDataArr.re_time) ; // 比赛时间
                    }

                    if(tiptype=='p3') { // 综合过关
                        $('#game_time').html(resDataArr.datetime) ; // 比赛时间
                    }

                    $('#lea_title_gtype').html(resDataArr.league) ; // 联赛标题
                    $('#game_team_h').html(resDataArr.team_h) ; // 主队
                    $('#game_team_c').html(resDataArr.team_c) ; // 客队

                    // 比分 足球 score_new ( 篮球 sc_new ) : H 主队最近进球，C 客队最近进球，需要高亮显示
                    var $game_score_h = $('#game_score_h') ;
                    var $game_score_c = $('#game_score_c') ;

                    if(gtype=='BK'){ // 篮球 ,篮球 主队 sc_FT_H ，客队 sc_FT_A, 第一节 主队 sc_Q1_H 客队 sc_Q1_A，第二节 主队 sc_Q2_H 客队 sc_Q2_A，第三节 主队 sc_Q3_H 客队 sc_Q3_A，第四节 主队 sc_Q4_H 客队 sc_Q4_A，
                        if(resDataArr.sc_new=='H'){ // 主队
                            $game_score_h.addClass('score_light') ;
                            $game_score_c.removeClass('score_light') ;
                        }else{ // 客队
                            $game_score_c.addClass('score_light') ;
                            $game_score_h.removeClass('score_light') ;
                        }

                        $game_score_h.html(resDataArr.sc_FT_H?resDataArr.sc_FT_H:0) ; // 主队比分
                        $game_score_c.html(resDataArr.sc_FT_A?resDataArr.sc_FT_A:0) ; // 客队比分
                        if(showtype=='RB') { // 滚球
                            changeBKScoreData(resDataArr.se_now,resDataArr.sc_Q1_H,resDataArr.sc_Q1_A,resDataArr.sc_Q2_H,resDataArr.sc_Q2_A,resDataArr.sc_Q3_H,resDataArr.sc_Q3_A,resDataArr.sc_Q4_H,resDataArr.sc_Q4_A,resDataArr.sc_H1_H,resDataArr.sc_H1_A,resDataArr.sc_H2_H,resDataArr.sc_H2_A,resDataArr.sc_OT_H,resDataArr.sc_OT_A) ;
                        }

                    }else{ // 足球

                        if(resDataArr.score_new=='H'){ // 主队
                            $game_score_h.addClass('score_light') ;
                            $game_score_c.removeClass('score_light') ;
                        }else{ // 客队
                            $game_score_c.addClass('score_light') ;
                            $game_score_h.removeClass('score_light') ;
                        }

                        $game_score_h.html(resDataArr.score_h?resDataArr.score_h:0) ; // 主队比分
                        $game_score_c.html(resDataArr.score_c?resDataArr.score_c:0) ; // 客队比分
                    }

                    // Active 值设定, Active : 1 足球滚球、今日赛事(FT), 11 足球早餐(FU)，2 篮球滚球、今日赛事(BK), 22 篮球早餐(BU)
                    if(gtype =='BK'){
                        if(showtype=='FT'){ // 今日
                            showtype ='BK' ;
                        }else if(showtype=='FU'){ // 早盘
                            showtype ='BU' ;
                        }
                    }
                    changeDcrqData(resDataArr,showtype,gtype,tiptype) ; // 让球
                    changeDcdxData(resDataArr,showtype,gtype,tiptype) ; // 大小
                    changeDcdyData(resDataArr,showtype,gtype,tiptype) ; // 独赢

                    changeZongJQSData(resDataArr,showtype,gtype) ; // 总进球数
                    changeShuangFQDData(resDataArr,showtype,gtype) ; // 双方球队进球数
                    changeQDJQdxData(resDataArr,showtype,gtype) ; // 球队进球数 ( 球队得分 )
                    changeDanSData(resDataArr,showtype,gtype,tiptype) ; // 单双

                    changeJingSQSData(resDataArr,showtype,gtype) ; // 净胜球数
                    changeDuYJQDXData(resDataArr,showtype,gtype) ; // 独赢 & 进球 大 / 小
                    changeJQDXJQDSData(resDataArr,showtype,gtype) ; // 进球 大 / 小 & 进球 单 / 双
                    changeJQDXJSFQDJQata(resDataArr,showtype,gtype) ; // 进球 大 / 小 & 双方球队进球
                    changeShuangCJHData(resDataArr,showtype,gtype) ; // 双重机会
                    changeLingSQData(resDataArr,showtype,gtype) ; // 零失球
                    changeLingSQHSData(resDataArr,showtype,gtype) ; // 零失球获胜
                    changeDuSFQDJQCData(resDataArr,showtype,gtype) ; // 独赢 & 双方球队进球
                    changeZDJQBCData(resDataArr,showtype,gtype) ; // 最多进球的半场
                    changeZDJQBCDYData(resDataArr,showtype,gtype) ; // 最多进球的半场 - 独赢
                    changeSBCJQData(resDataArr,showtype,gtype) ; // 双半场进球

                    changeSCJHSFQDJQData(resDataArr,showtype,gtype) ; // 双重机会 & 双方球队进球
                    changeSXRQTZData(resDataArr,showtype,gtype) ; // 三项让球投注
                    changeYDRYBCData(resDataArr,showtype,gtype) ; // 赢得任一半场
                    changeYDSYBCData(resDataArr,showtype,gtype) ; // 赢得所有半场
                    if(gtype =='BK'){ // 篮球才有
                        changeQDDFZHYWSData(resDataArr,showtype,gtype) ; // 球队得分: - 最后一位数

                        if(showtype=='RB' && resDataArr.se_now=='Q4'){ // 篮球滚球第四节不让投注
                            $('#sport_div_show').hide();
                            $('.NoEvent_game').show();
                        }else{
                            $('#sport_div_show').show();
                            $('.NoEvent_game').hide();
                        }

                    }else{ // 足球才有
                        changeDcbdData(resDataArr,showtype,gtype) ; // 波胆
                        changeBanQuanCData(resDataArr,showtype,gtype) ; // 半场/全场
                        if(tiptype!='p3'){
                            changeSCJHJQDXData(resDataArr,showtype,gtype) ; // 双重机会 & 进球 大 / 小
                        }
                    }

                    if(tiptype=='p3'){ // 综合过关
                        countP3BetNumber('ischoose') ;
                    }


                }else{ // 没有数据
                    $('.NoEvent_game').show();
                    $('#sport_div_show').hide();
                }
            }else if(res.data==''){ // 没有数据
                loadingContent(false) ;
                $('.NoEvent_game').show();
                $('#sport_div_show').hide();
            }

        },
        error: function (msg) {
            loadingContent(false) ;
            setPublicPop(config.errormsg);
        }
    });
}


/**
 * 选择玩法和赔率，准备投注接口
 * order/order_prepare_api.php
 *
 * @param  order_method  FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，
 * FT_rt 滚球单双、单双 - 上半场、总进球数、总进球数-上半场，FT_rpd 滚球波胆，FT_rouhc 滚球得分大小，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，FT_m 独赢，FT_r 让球，FT_ou 大小，
 * FT_t 单双、单双 - 上半场、总进球数、总进球数-上半场，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，
 * FT_single :// 主盘口（双方球队进球、双方球队进球-上半场、球队进球数-大小、球队进球数-大小 -上半场、净胜球、双重机会、零失球、零失球获胜、独赢 & 双方球队进球）// 进球盘口（最多进球的半场、最多进球的半场 - 独赢、双半场进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球）// 其他盘口（其他盘口、赢得任一半场、赢得所有半场）
 *  FT_pd 波胆， FT_hpd 下半场波胆   FT_f 半场/全场 ,FT_nfs ,
 * 'FT_rsingle': // 双方球队进球、净胜球数、双重机会、零失球、零失球获胜、独赢 & 进球大/小、独赢 & 双方球队进球、进球 大 / 小 & 双方球队进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球、进球 大 / 小 & 进球 单 / 双
 * BK_rm  滚球独赢, BK_re 滚球让球, BK_rou 滚球大小,  BK_rt 滚球单双 , BK_rouhc 滚球得分大小 , BK_rpd 滚球波胆，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小 , BK_pd 波胆
 * @param  gid
 * @param  type  H 主队 C 客队  N 和
 * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
 * @param  rtype  ODD 单 EVEN 双
 * @param  odd_f_type  H
 * @param  error_flag
 * @param  order_type
 * @param  flag  all 所有玩法
 * http://m.hg50080.com/order/order_prepare_api.php?gid=3352072&wtype=OU&type=&order_method=FT_ou&odd_f_type=H&order_type=&error_flag=&rtype=
 */
/**
 * 综合过关_准备投注接口
 接口示例
 http://m.hg50080.com/order/order_prepare_p3_api.php?teamcount=2&game=PRC,PE,...&game_id=3366482,3369368,...
 * /order/order_prepare_p3_api.php   综合过关选择玩法和赔率，准备投注接口
 * @param  teamcount
 * @param  game
 * @param  game_id
 */
function readyToBetAction(gid,wtype,type,order_method,odd_f_type,error_flag,order_type,rtype,flag,p3tip,p3num) {
    var $submitSrc = $('#submitSrc') ; // 下注按钮
    var ajaxurl = '/order/order_prepare_api.php' ;
    if(p3tip=='p3'){ // 综合过关
        ajaxurl = '/order/order_prepare_p3_api.php' ;
        var params = {
            teamcount: p3num ,
            game_id: gid ,
            game: rtype ,
            wtype: wtype ,
            type: type ,
        };
    }else{
        var params = {
            gid: gid ,
            wtype: wtype ,
            type: type ,
            order_method: order_method ,
            odd_f_type: odd_f_type ,
            error_flag: error_flag ,
            order_type: order_type ,
            rtype: rtype ,
            flag: flag ,
        };
    }

    $.ajax({
        url: ajaxurl ,
        type: 'POST',
        dataType: 'json',
        data: params,
        success: function (res) {
            if(res.status=='200'){ // 请求数据成功
                /*
                *  {"status":"200","describe":"success","timestamp":"20180826032013",
                *  "data":
                *  {"leag":"阿根廷乙组全国联赛",
                *  "gametype":"全场 - 大小",
                *  "MB_Team":"艾朗迪考瑞法拉",
                *  "TG_Team":"基尔梅斯",
                *  "sign":null,
                *  "ShowTypeRB":null,
                *  "ShowTypeR":null,
                *  "inball":null,
                *  "M_Place":"小&nbsp;2",
                *  "minBet":"20",
                *  "maxBet":"500000",
                *  "active":11,
                *  "line_type":3,
                *  "type":"H",
                *  "rtype":"","wtype":"OU","gnum":null,"ioradio_r_h":"0.82","odd_f_type":"","
                *  dataSou":null},
                *  "sign":"0dad74de2550ea82237f796fd47b519b"}
                *
                * */
                if(p3tip=='p3') { // 综合过关
                    betContentData(res.data[0],'p3') ;
                    $('#bet_win_gold').attr({'data-maxpayout':res.data[0].maxPayout}) ; // 综合过关单注最高派彩金额
                }else{
                    betContentData(res.data[0]) ;

                }

                $('#minbet').html(res.data[0].minBet) ; // 最低投注
                $('#maxbet').html(res.data[0].maxBet) ; // 最高投注

                $submitSrc.attr({'data-linetype':res.data[0].line_type,'data-oddftype':res.data[0].odd_f_type,'data-rtype':res.data[0].rtype,'data-wtype':res.data[0].wtype}) ;

            }else{ // 有错误
                closeBetAction() ; // 关闭下注
                if(p3tip=='p3') { // 综合过关
                    deleteP3Error(gid) ;
                }
                setPublicPop(res.describe);

            }

        },
        error: function (msg) {
            setPublicPop(config.errormsg);
        }
    });
}

/*
*  公用投注表单内容
*  gametype 投注类型, leag 投注联赛 ,MB_Team 主队, TG_Team 客队 ,inball 滚球比分, sign 让球 ,M_Place 投注内容 ,ioradio_r_h 赔率
* */

function betContentHtml(str,gametype,leag,MB_Team,TG_Team,inball,sign,M_Place,ioradio_r_h,tiptype,m_gid,type) {
    str = '<ul data-gid="'+ m_gid +'" data-rtype="'+ type +'" data-ior="'+ ioradio_r_h +'">' +
        ' <span class="closeBtn" data-gid="'+ m_gid +'" data-rtype="'+ type +'" style="display: '+ (tiptype=='p3'?'block':'none') +'"></span>' +
        ' <li>' +
        '   <span id="bet_menutype" class="ord_gametype">'+ gametype +'</span>' +
        '   <span id="bet_score" class="orderScore" style="">'+ (inball==''?'':'('+inball+')') +' </span>' +
        '   <span id="bet_league" class="ord_leag">'+ leag +'</span>' +
        '   <span id="bet_teamname" class="ord_teamname" style="display: none;"></span>' +
        '   <div id="bet_teamDiv">' +
        '        <span id="bet_team_h" class="team_h">'+ MB_Team +'</span>' +
        '        <span id="bet_con"  class="ord_con">'+ sign +'<font class="ratio_red"> </font></span>' ;
    if(TG_Team){
        str += '<em class="ratio_red">'+ (sign?'':'VS.') +'</em>' +
            '<span id="bet_team_c" class="team_c">'+ TG_Team +'</span>' ;
    }

    str +='<span id="bet_con_c" class="ord_con_c" style="display: none;"></span>' +
        '        </div>' +
        '  </li>' +
        '  <li>' +
        '     <span id="bet_chose_team" class="team_chose ratio_red">'+ M_Place +'</span>' +
        '     <span id="bet_chose_con" class="ord_chose_con" style="display: none;"></span>' +
        '     <span class="team_at">@</span>' +
        '     <span id="bet_ior" class="ord_ior"><font class="ratio_red">'+ ioradio_r_h +'</font></span>' +
        //  '  赔率有变动 <font class="txtOddsChange">1.96</font>' +
        '  </li>' ;
    if(tiptype !='p3'){ // 非综合过关
        str += '<li><input name="autoOdd" class="auto_Odd" type="checkbox" style="height: auto;" checked />自动接收较佳赔率 </li>';
    }
    str +='</ul>';

    return str ;
}

/*
* tiptype 综合过关 p3
* */
function betContentData(res,tiptype) { // res = res.data[0] ;
    /*
    *  综合过关
    *  {"status":"200","describe":"success","timestamp":"20180918061453",
    *  "data":[
    *  {"minBet":"20","maxBet":"100000",
    *  "betItem":[
    *  {"m_rate":"1.9","m_gid":"3363572","type":"PRH","showtype":"H","leag":"德国甲组联赛","gametype":"全场 - 让球","mb_team":"哈化柏林[主]","sign":"0","tg_team":"门兴格拉德巴赫","place":"哈化柏林"},
    *  {"m_rate":"1.99","m_gid":"3363582","type":"POUH","showtype":"H","leag":"德国甲组联赛","gametype":"全场 - 大小","mb_team":"奥斯堡[主]","sign":"VS.","tg_team":"云达不莱梅","place":"小 2.5"},
    *  {"m_rate":"1.83","m_gid":"3363592","type":"PRH","showtype":"H","leag":"德国甲组联赛","gametype":"全场 - 让球","mb_team":"纽伦堡[主]","sign":"0","tg_team":"汉诺威96","place":"纽伦堡"}]}],"sign":""}
    * */
    var str ='' ;
    if(tiptype=='p3'){  // 综合过关 p3
        // console.log(res);
        // console.log(res.betItem);
        for(var i=0;i < res.betItem.length;i++){
            //console.log(i)
            str += betContentHtml(str,res.betItem[i].gametype,res.betItem[i].leag,res.betItem[i].mb_team,res.betItem[i].tg_team,'',res.betItem[i].sign,res.betItem[i].place,res.betItem[i].m_rate,'p3',res.betItem[i].m_gid,res.betItem[i].type) ;
        }
    }else{
        str += betContentHtml(str,res.gametype,res.leag,res.MB_Team,res.TG_Team,res.inball,res.sign,res.M_Place,res.ioradio_r_h) ;
    }
    //console.log(str);
    $('#normal_order_model').html(str) ;
}


/*
*  返回 order_method
 *  FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，
 *  FT_rpd 滚球波胆，FT_rouhc 滚球得分大小，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，
 *  FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，
 * FT_single :// 主盘口（双方球队进球、双方球队进球-上半场、球队进球数-大小、球队进球数-大小 -上半场、净胜球、双重机会、零失球、零失球获胜、独赢 & 双方球队进球）// 进球盘口（最多进球的半场、最多进球的半场 - 独赢、双半场进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球）// 其他盘口（其他盘口、赢得任一半场、赢得所有半场）
 *  FT_pd 波胆， FT_hpd 下半场波胆 ,
 *  FT_f 半场/全场 ,FT_nfs 冠军 ,
 * 'FT_rsingle': // 双方球队进球、净胜球数、双重机会、零失球、零失球获胜、独赢 & 进球大/小、独赢 & 双方球队进球、进球 大 / 小 & 双方球队进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球、进球 大 / 小 & 进球 单 / 双
 * BK_rm  滚球独赢, BK_re 滚球让球, BK_rou 滚球大小,  BK_rt 滚球单双 , BK_rouhc 滚球得分大小 , BK_rpd 滚球波胆，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小 , BK_pd 波胆
* */
function returnMethod(type,more,tip){
    var ordermethod ;
    if(more=='r' || more=='RB'){  // 滚球
        switch (tip) {
            case 'm': // 滚球独赢
                ordermethod = type+'_rm' ;
                break;
            case 'e': // 滚球让球
                ordermethod = type+'_re' ;
                break;
            case 'ou': // 滚球大小
                ordermethod = type+'_rou' ;
                break;
            case 't': // 滚球单双
                ordermethod = type+'_rt' ;
                break;
            case 'pd': // 滚球波胆
                ordermethod = type+'_rpd' ;
                break;
            case 'hm': // 滚球半场独赢
                ordermethod = type+'_hrm' ;
                break;
            case 'he': // 滚球半场让球
                ordermethod = type+'_hre' ;
                break;
            case 'hou': // 滚球半场大小
                ordermethod = type+'_hrou' ;
                break;
            case 'hpd': // 滚球波胆
                ordermethod = type+'_hrpd' ;
                break;
            case 'single': // 滚球 双方球队进球
                ordermethod = type+'_rsingle' ;
                break;
            case 'f': // 半场/全场
                ordermethod = type+'_rf' ;
                break;
            case 'ouhc': // 球队得分:  -大 / 小
                ordermethod = type+'_rouhc' ;
                break;
        }

    }else{ // 今日和早盘
        switch (tip) {
            case 'm': // 独赢
                ordermethod = type+'_m' ;
                break;
            case 'e': // 让球
                ordermethod = type+'_r' ;
                break;
            case 'ou': // 大小
                ordermethod = type+'_ou' ;
                break;
            case 't': // 单双
                ordermethod = type+'_t' ;
                break;
            case 'pd': // 波胆
                ordermethod = type+'_pd' ;
                break;
            case 'hm': // 半场独赢
                ordermethod = type+'_hm' ;
                break;
            case 'he': // 半场让球
                ordermethod = type+'_hr' ;
                break;
            case 'hou': // 半场大小
                ordermethod = type+'_hou' ;
                break;
            case 'hpd': // 波胆
                ordermethod = type+'_hpd' ;
                break;
            case 'single': // 滚球 双方球队进球
                ordermethod = type+'_single' ;
                break;
            case 'f': // 半场/全场
                ordermethod = type+'_f' ;
                break;
            case 'ouhc': // 球队得分:  -大 / 小
                ordermethod = type+'_ouhc' ;
                break;
            case 'nfs': // 冠军
                ordermethod = type+'_nfs' ;
                break;

        }
    }

    return ordermethod ;
}

// Active 值设定, Active : 1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
function setActiveParams(type) {
    var a_par ;
    switch (type){
        case 'FT': // 今日足球，足球滚球
            a_par = '1' ;
            break;
        case 'BK': // 今日篮球，篮球滚球
            a_par = '2' ;
            break;
        case 'FU': // 早盘足球
            a_par = '11' ;
            break;
        case 'BU': // 篮盘足球
            a_par = '22' ;
            break;

    }
    return a_par ;
}

/* 更多玩法 让球，独赢 ，双重机会 ,零失球, 零失球获胜 ,最多进球的半场 ,最多进球的半场 - 独赢 ，双半场进球 , 三项让球投注,赢得任一半场, 赢得所有半场   数据处理
 让球主队：ior_RH , 让球客队：ior_RC ,让球上半场主队：ior_HRH ,让球上半场客队：ior_HRC,
 全场让球数：ratio ，半场让球数：hratio, 让球(全场 strong,半场 hstrong)  谁让球: H主队 C客队,
 hr : all 全场，half 半场, type : FT BK, more : FU 早盘 FT 今日赛事 RB 滚球
 method: rq 让球，dy 独赢

"sw_M": "Y",         半场: "sw_HM": "Y",          //独赢  开关
"ior_MH": "1.320",    半场: "ior_HMH": "1.770",        //独赢  主队赔率
"ior_MC": "8.300",    半场:"ior_HMC": "6.500",         //独赢  客队赔率
"ior_MN": "5.000",      半场: "ior_HMN": "2.500",       //独赢  和赔率
 */
function setRqhtml(str,gid,team_h,team_c,ior_RH,ior_RC,ratio,strong,hr,type,more,method,ior_HMN,ratio_w3h,ratio_w3c,ratio_w3n,tiptype) {

    var typecase ;
    var acttype ;
    var tiptitle = '让球' ;
    var spegame ='' ;
    var rtype1 ='' ;
    var rtype2 ='' ;
    var rtype3 ='' ;
    var wtype = 'R' ; // wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU ，滚球 半场独赢 主队 rtype: HRMH wtype: HRM, 客队 rtype: HRMC wtype: HRM ，和局 rtype: HRMN  wtype: HRM
    var method_type = 'e' ; // e: 让球,m :全场独赢，hm ：半场独赢
    var tietip = '' ; //双重机会才有
    var rb_tip = '' ; // 滚球标志
    var arrow_type = 'arrow_close' ; // 是否展开或者关闭
    var body_type = 'block' ; // 是否展开或者关闭
    var p3_tiptype = '' ; // 综合过关

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;

        if(method=='rq') { // 让球
            rb_tip = 'RE' ;
        }else{ // 让球
            rb_tip = 'R' ;
        }

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }

    var active_type = setActiveParams(acttype) ;

    switch (method){
        case 'rq': // 让球
            if(hr =='half'){ // 半场
                method_type = 'he' ;
                wtype = 'HR' ;
                if(tiptype=='p3'){ // 综合过关
                    wtype = 'R' ;
                }
            }
            break ;
        case 'dy': // 独赢
            tiptitle = '独赢' ;
            spegame = 'special' ;
            wtype = 'M' ; // 全场
            method_type = 'm' ;
            if(hr =='half'){ // 半场
                method_type = 'hm' ;
                if(tiptype!='p3') { // 综合过关
                    wtype = 'HM' ; // 滚球全场独赢
                }
                if(more=='RB'){ // 滚球半场独赢
                    wtype = 'HRM' ;
                }
            }else{
                wtype = rb_tip+wtype ;
            }
            break ;
        case 'scjh': // 双重机会  rtype : DCHN  DCCN  DCHC
            tiptitle = '双重机会' ;
            spegame = 'special' ;
            wtype = rb_tip+'DC' ; // 全场
            method_type = 'single' ;
            tietip = ' / 和局' ;

            rtype1 = rb_tip+'DCHN';
            rtype2 = rb_tip+'DCCN';
            rtype3 = rb_tip+'DCHC';

            break ;
        case 'lsq': // 零失球
            tiptitle = '零失球' ;
            spegame = 'special' ;
            wtype = rb_tip+'CS' ;
            method_type = 'single' ;
            break ;
        case 'lsqhs': // 零失球获胜
            tiptitle = '零失球获胜' ;
            spegame = 'special' ;
            wtype = rb_tip+'WN' ;
            method_type = 'single' ;
            break ;
        case 'zdjqbc': // 最多进球的半场
            tiptitle = '最多进球的半场' ;
            spegame = 'special' ;
            wtype = rb_tip+'HG' ;
            method_type = 'single' ;
            arrow_type = 'arrow_open' ;
            body_type = 'none' ;
            break ;
        case 'zdjqbcdy': // 最多进球的半场 - 独赢
            tiptitle = '最多进球的半场 - 独赢' ;
            spegame = 'special' ;
            wtype = rb_tip+'MG' ;
            method_type = 'single' ;
            arrow_type = 'arrow_open' ;
            body_type = 'none' ;
            break ;
        case 'sbcjq': // 双半场进球
            tiptitle = '双半场进球' ;
            spegame = 'special' ;
            wtype = rb_tip+'SB' ;
            method_type = 'single' ;
            arrow_type = 'arrow_open' ;
            body_type = 'none' ;
            break ;
        case 'sxrqtz': // 三项让球投注 rtype : W3H  W3C  W3N ,ratio_w3h ratio_w3c ratio_w3n
            tiptitle = '三项让球投注' ;
            spegame = 'special' ;
            wtype = 'W3' ;
            method_type = 'single' ;
            break ;
        case 'ydrybc': // 赢得任一半场
            tiptitle = '赢得任一半场' ;
            spegame = 'special' ;
            wtype = rb_tip+'WE' ;
            method_type = 'single' ;
            arrow_type = 'arrow_open' ;
            body_type = 'none' ;
            break ;
        case 'ydsybc': // 赢得所有半场
            tiptitle = '赢得所有半场' ;
            spegame = 'special' ;
            wtype = rb_tip+'WB' ;
            method_type = 'single' ;
            arrow_type = 'arrow_open' ;
            body_type = 'none' ;
            break ;

    }


    if(method !='scjh') { // 双重机会  rtype : DCHN  DCCN  DCHC
        rtype1 = wtype+'H';
        rtype2 = wtype+'C';
        rtype3 = wtype+'N';
    }


    if(method=='rq'){ // 让球
        if(more=='RB'){ // 滚球
            wtype = 'RE' ; // 滚球让球
            if(hr =='half'){ // 半场
                wtype = 'HRE' ;
            }
            rtype1 = wtype+'H';
            rtype2 = wtype+'C';
            rtype3 = wtype+'N';
        }
    }

    if(tiptype=='p3'){ // 综合过关
        var p3_rtype = 'P' ;
        p3_tiptype = 'p3' ;
        if(hr =='half') { // 半场
            p3_rtype = 'HP' ;
        }
        wtype = 'p3' ;
        rtype1 = p3_rtype+rtype1 ;
        rtype2 = p3_rtype+rtype2 ;
        rtype3 = p3_rtype+rtype3 ;
    }

    str =  '<div class=" '+ returnCloseallGameList(ior_RH) +'"> <div class="expand_action acc_1" >' +
        '<span class="'+arrow_type+'"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+ tiptitle +'</div><div class="game_type"> - 上半场</div>' ;
    }else{
        str += '<div class="more_head">'+ tiptitle +'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_r" style="display: '+body_type+'">' +
        '          <div class="bet_action subaccountform bet_p3_'+rtype1+'_'+gid+'" data-gid="'+ gid +'" data-type="H" data-rtype="'+ rtype1 +'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_r"><span>'+ team_h +tietip +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_RH) +'">' +
        '                <div class="odds_gray"> '+ (method=='sxrqtz'?ratio_w3h: strong=="H"?ratio:"") +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_RH) +'</font></div>' +
        '                  </div> ' +
        '              </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform bet_p3_'+rtype2+'_'+gid+'" data-gid="'+ gid +'" data-type="C" data-rtype="'+ rtype2 +'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_r"><span>'+ team_c +tietip +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_RC) +'">' +
        '                <div class="odds_gray">'+ (method=='sxrqtz'?ratio_w3c: strong=="C"?ratio:"") +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_RC) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;

    if(method=='dy' || method=='scjh' || method=='zdjqbcdy' || method=='sxrqtz'){ // 独赢，双重机会，最多进球的半场 - 独赢 , 三项让球投注
        str +=  '<div class="bet_action subaccountform '+returnCloseallGameList(ior_HMN)+' bet_p3_'+rtype3+'_'+gid+'" data-gid="'+ gid +'" data-type="N" data-rtype="'+ rtype3 +'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">  ' +
            '            <div class="accordion_content">' +
            '            <div class="more_team_r"><span> '+ (method=='scjh'?(team_h+' / '+team_c): (method=='sxrqtz'?'让球和局':'和局')) +' </span></div>' +
            '                <div class="more_mem_box '+returnCloseGame(ior_HMN)+'">' +
            '                <div class="odds_gray">'+ (method=='sxrqtz'?ratio_w3n: strong=="C"?ratio:"") +'</div>' +
            '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_HMN) +'</font></div>' +
            '                  </div> ' +
            '            </div>' +
            '          </div>' ;
    }
    str += '</div> </div>';

    return str ;

}

/* 得分大小数据 html 结构
*   "sw_OU": "Y",    半场: "sw_HOU": "Y",          //大小  开关
    "ratio_o": "3",      半场: "ratio_ho": "1 / 1.5",       //大小  大 让球数
    "ratio_u": "2",      半场: "ratio_hu": "1 / 1.5",       //大小  小 让球数
    "ior_OUH": "0.83",    半场: "ior_HOUH": "0.83",         //大小  小 赔率
    "ior_OUC": "1.05",    "半场: ior_HOUC": "1.05",       //大小  大 赔率
    大小 特殊： data-type ：主队 C，客队 H
* */
function setDxhtml(str,gid,ior_OUH,ior_OUC,ratio_o,ratio_u,hr,type,more,tiptype,team_h) {
    var typecase ;
    var acttype ;
    var method_type = 'ou' ; // ou: 大小 全场,hou :大小 半场
    var wtype = 'OU' ; // 全场 今日 OU  滚球 ROU ， 半场 今日 HOU  滚球 HROU
    var tiptitle = '大 / 小' ;
    var bk_tip = '' ;
    var p3_tiptype = '' ; // 综合过关
    var rtype1 =''
    var rtype2 ='' ;

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        if(hr=='half') {  // 半场
            wtype = 'HROU' ;
        }else{ // 今日和早盘
            wtype = 'ROU' ;
        }

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
        if(hr=='half') {  // 半场
            wtype = 'HOU' ;
        }

    }

    if(type=='BK'){
        bk_tip = '总分: ' ;
    }

    if(tiptype=='p3'){ // 综合过关
        var p3_rtype = 'P' ;
        p3_tiptype = 'p3' ;
        if(hr =='half') { // 半场
            p3_rtype = 'HP' ;
            wtype = 'OU' ;
        }
        rtype1 = p3_rtype+wtype+'C' ;
        rtype2 = p3_rtype+wtype+'H' ;
        wtype = 'p3' ; // 重新赋值
    }else{
        rtype1 = wtype+'C' ;
        rtype2 = wtype+'H' ;
    }

    var active_type = setActiveParams(acttype) ;

    tiptitle = bk_tip+tiptitle ;

    str =  '<div class=" '+ returnCloseallGameList(ior_OUC) +'"> <div class="expand_action acc_1" ><span class="arrow_close"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+tiptitle+'</div><div class="game_type"> - 上半场</div>' ;
        method_type = 'hou' ; // 半场
    }else{
        str += '<div class="more_head">'+tiptitle+'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_ou">' +
        '          <div  class=" subaccountform" >  ' +
        '            <div id="bet_'+ gid +'_OUC" class="bet_action right_line accordion_content_2 bet_p3_'+rtype1+'_'+gid+'" data-gid="'+ gid +'" data-type="C" data-rtype="'+rtype1+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">' +
        '            <div class="more_team_ou"><span> 大 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUC) +'">' +
        '                <div class="odds_gray"> '+ ratio_o +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUC) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' +
        '            <div id="bet_'+ gid +'_OUH" class="bet_action accordion_content_2 bet_p3_'+rtype2+'_'+gid+'" data-gid="'+ gid +'" data-type="H" data-rtype="'+rtype2+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">' +
        '            <div class="more_team_ou"><span> 小 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUH) +'">' +
        '                <div class="odds_gray">'+ ratio_u +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUH) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' ;

    str += '</div> </div>';

    return str ;

}

/* 波胆数据 html 结构  篮球没有波胆
* 1-0: ior_H1C0, 0-0: ior_H0C0 ,0-1 :ior_H0C1, 2-0 : ior_H2C0 ,1-1: ior_H1C1 ,0-2: ior_H0C2 以此类推
* 其他：ior_OVH  ior_OVC   这两个都是一样的
* */
function setBodanhtml(str,gid,ior_H1C0,ior_H0C0,ior_H0C1,ior_H2C0,ior_H1C1,ior_H0C2,ior_H2C1,ior_H2C2,ior_H1C2,ior_H3C0,ior_H3C3,ior_H0C3,ior_H3C1,ior_H4C4,ior_H1C3,ior_H3C2,ior_H2C3,ior_H4C0,ior_H0C4,ior_H4C1,ior_H1C4,ior_H4C2,ior_H2C4,ior_H4C3,ior_H3C4,ior_OVH,hr,type,more) {
    var typecase ;
    var acttype ;
    var method_type = 'pd' ; // pd: 波胆 全场,pd : 波胆 半场
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype = 'PD' ;
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;
        wtype = rb_tip+wtype ;
        if(hr=='half'){ // 半场
            wtype = 'H'+wtype ;
        }
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
        if(hr=='half'){ // 半场
            wtype = 'HPD' ;
        }
    }

    var active_type = setActiveParams(acttype) ;

    str = '<div class=""> ' ; //  returnCloseallGameList(ior_H1C0)
    str += '<div class="expand_action  acc_1" ><span id="arrow_pd" class="'+ (hr=='half'?'arrow_open':'arrow_close') +'"></span>' ;

    if(hr=='half'){  // 半场
        str += '<div class="left">波胆</div><div class="game_type"> - 上半场</div>' ;
        method_type = 'hpd' ;
    }else{
        str += '<div class="more_head">波胆</div>' ;
    }

    str += '</div>';
    str += '<div id="body_pd" class="subacc_2 body_pd" style="display:'+(hr=='half'?'none':'block')+'">' +
        '                <div class="sub_title">' +
        '                <div class="sub_title_th">1</div>' +
        '                    <div class="sub_title_th">' +
        '                    <div class="x_line">X</div>' +
        '                    </div>' +
        '                    <div class="sub_title_th">2</div>' +
        '                </div>     ' +
        '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_H1C0,ior_H0C0,ior_H0C1) +'">  ' +
        '                    <div id="bet_'+gid+'_H1C0" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H1C0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H1C0) +'">' +
        '                    <div class="more_con">1 - 0</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H1C0) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H0C0" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H0C0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H0C0) +'">' +
        '                            <div class="more_con">0 - 0</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H0C0) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H0C1" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H0C1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H0C1) +'">' +
        '                            <div class="more_con">0 - 1</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H0C1) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>  ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H2C0,ior_H1C1,ior_H0C2) +'">     ' +
        '                    <div id="bet_'+gid+'_H2C0" class="bet_action more_td"data-gid="'+ gid +'" data-rtype="'+rb_tip+'H2C0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H2C0) +'">' +
        '                            <div class="more_con">2 - 0</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H2C0) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                ' +
        '                    <div id="bet_'+gid+'_H1C1" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H1C1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H1C1) +'">' +
        '                                <div class="more_con">1 - 1</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H1C1) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H0C2" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H0C2" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H0C2) +'">' +
        '                    <div class="more_con">0 - 2</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H0C2) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>  ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H2C1,ior_H2C2,ior_H1C2) +'"> ' +
        '                    <div id="bet_'+gid+'_H2C1" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H2C1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H2C1) +'">' +
        '                    <div class="more_con">2 - 1</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H2C1) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H2C2" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H2C2" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H2C2) +'">' +
        '                                <div class="more_con">2 - 2</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H2C2) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H1C2" class="bet_action more_td" data-gid="'+ gid +'"  data-rtype="'+rb_tip+'H1C2" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H1C2) +'">' +
        '                    <div class="more_con">1 - 2</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H1C2) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>  ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H3C0,ior_H3C3,ior_H0C3) +'">  ' +
        '                    ' +
        '                    <div id="bet_'+gid+'_H3C0" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H3C0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H3C0) +'">' +
        '                    <div class="more_con">3 - 0</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H3C0) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H3C3" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H3C3" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H3C3) +'">' +
        '                                <div class="more_con">3 - 3</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H3C3) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H0C3" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H0C3" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H0C3) +'">' +
        '                    <div class="more_con">0 - 3</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H0C3) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>  ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H3C1,ior_H4C4,ior_H1C3) +'">  ' +
        '                    <div id="bet_'+gid+'_H3C1" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H3C1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H3C1) +'">' +
        '                            <div class="more_con">3 - 1</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H3C1) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H4C4" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H4C4" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H4C4) +'">' +
        '                            <div class="more_con">4 - 4</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H4C4) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H1C3" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H1C3" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H1C3) +'">' +
        '                            <div class="more_con">1 - 3</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H1C3) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>  ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H3C2,ior_H2C3,'') +'">  ' +
        '                    <div id="bet_'+gid+'_H3C2" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H3C2" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H3C2) +'">' +
        '                    <div class="more_con">3 - 2</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H3C2) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div class="more_td">' +
        '                    <div class="more_td_line"></div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H2C3" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H2C3" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H2C3) +'">' +
        '                    <div class="more_con">2 - 3</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H2C3) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div> ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H4C0,ior_H0C4,'') +'">            ' +
        '                    <div id="bet_'+gid+'_H4C0" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H4C0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H4C0) +'">' +
        '                    <div class="more_con">4 - 0</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H4C0) +'</font></div>' +
        '</div>' +
        '                    </div>' +
        '                        ' +
        '                    <div class="more_td">' +
        '                    <div class="more_td_line"></div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H0C4" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H0C4" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H0C4) +'">' +
        '                    <div class="more_con">0 - 4</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H0C4) +'</font></div>' +
        '</div>' +
        '                    </div>' +
        '</div> ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H4C1,ior_H1C4,'') +'">            ' +
        '                    <div id="bet_'+gid+'_H4C1" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H4C1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        ' <div class="more_td_line '+ returnCloseGame(ior_H4C1) +'">' +
        '                            <div class="more_con">4 - 1</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H4C1) +'</font></div>' +
        ' </div>' +
        '                    </div>' +
        '                    <div class="more_td">' +
        '                    <div class="more_td_line"></div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H1C4" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H1C4" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        ' <div class="more_td_line '+ returnCloseGame(ior_H1C4) +'">' +
        '                            <div class="more_con">1 - 4</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H1C4) +'</font></div>' +
        ' </div>' +
        '                    </div>' +
        '</div> ' +
        '<div class="more_tr '+ returnBoDanCloseGame(ior_H4C2,ior_H2C4,'') +'">            ' +
        '                    <div id="bet_'+gid+'_H4C2" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H4C2" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H4C2) +'">' +
        '                    <div class="more_con">4 - 2</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H4C2) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    <div class="more_td">' +
        '                    <div class="more_td_line"></div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H2C4" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H2C4" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H2C4) +'">' +
        '                    <div class="more_con">2 - 4</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H2C4) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div> ' +
        '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_H4C3,ior_H3C4,'') +'">            ' +
        '                    <div id="bet_'+gid+'_H4C3" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H4C3" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H4C3) +'">' +
        '                    <div class="more_con">4 - 3</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H4C3) +'</font></div>' +
        '</div>' +
        '                    </div>' +
        '                    <div class="more_td">' +
        '                    <div class="more_td_line"></div>' +
        '                    </div>' +
        '                    <div id="bet_'+gid+'_H3C4" class="bet_action more_td" data-gid="'+ gid +'" data-rtype="'+rb_tip+'H3C4" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_H3C4) +'">' +
        '                    <div class="more_con">3 - 4</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_H3C4) +'</font></div>' +
        '</div>' +
        '                    </div>' +
        '</div> ' +
        '            <div id="bet_'+gid+'_OVH" class="bet_action more_tr tr_last '+  returnBoDanCloseGame(ior_OVH,'','') +'" data-rtype="'+rb_tip+'OVH" data-wtype="'+wtype+'" data-gid="'+ gid +'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" >' +
        '              <div class="more_td"><div class="more_team_sp">其他</div></div>' +
        '              <div class="more_td"></div>' +
        '              <div class="more_td">' +
        '                <div class="more_mem_box '+  returnCloseGame(ior_OVH) +'">' +
        '                  <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OVH) +'</font></div>' +
        '                </div>    ' +
        '              </div>' +
        '          </div>' +
        '        </div>'

    str += '</div>' ;

    return str ;
}

/*
* 所有玩法 总进球数 html 结构
* 全场 0-1 : ior_T01 ,2-3 : ior_T23 ,4-6 : ior_T46 , 7或以上：ior_OVER
* 半场 0 : ior_HT0 ,1 : ior_HT1 ,2 : ior_HT2 , 3或以上：ior_HTOV
 *  */
function setZongJQShtml(str,gid,ior_T01,ior_T23,ior_T46,ior_OVER,hr,type,more) {
    var typecase ;
    var acttype ;
    var tiptitle = '总进球数' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype = 'T' ; // wtype  T 全部，HT 半场 ,滚球全场 RT 滚球半场？
    var method_type = 't' ; //
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        wtype = 'RT' ;
        rb_tip = 'R' ;
        if(hr=='half'){
            wtype = 'HRT' ; // 半场
        }
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
        if(hr=='half'){
            wtype = 'HT' ; // 半场
        }
    }

    var active_type = setActiveParams(acttype) ;


    str =  '<div class=" "> ' + //  returnCloseallGameList(ior_T01)
        '<div class="expand_action acc_1 " ><span class="'+ (hr=='half'?'arrow_open':'arrow_close') +'"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+ tiptitle +'</div><div class="game_type"> - 上半场</div>' ;

    }else{
        str += '<div class="more_head">'+ tiptitle +'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_r" style="display:'+(hr=='half'?'none':'block')+'">' +
        '          <div class="bet_action subaccountform '+(ior_T01==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+(hr=='half'?'H'+rb_tip+'T0':rb_tip+'0~1')+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (hr=='half'?'0':'0 - 1') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_T01) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_T01) +'</font></div>' +
        '                  </div> ' +
        '              </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_T23==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+(hr=='half'?'H'+rb_tip+'T1':rb_tip+'2~3')+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (hr=='half'?'1':'2 - 3') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_T23) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_T23) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_T46==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+(hr=='half'?'H'+rb_tip+'T2':rb_tip+'4~6')+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (hr=='half'?'2':'4 - 6') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_T46) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_T46) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_OVER==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+(hr=='half'?'H'+rb_tip+'TOV':rb_tip+'OVER')+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (hr=='half'?'3或以上':'7或以上') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OVER) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OVER) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;

    str += '</div> </div>';

    return str ;
}

/* 双方球队进球 / 单双 数据 html 结构
*   ior_TSY  ior_TSN  ratio_HTSY  ior_HTSN
*   "sw_EO": "Y",                    //单双  开关
    "ior_EOO": "1.95",               //单双  单  赔率
    "ior_EOE": "1.94",               //单双  双  赔率
    "sw_HEO": "Y",                     //单双  上半场  开关
    "ior_HEOE": "1.81",                //单双  上半场  单  赔率
    "ior_HEOO": "2.07",                //单双  上半场  双  赔率
* */
function setQDJQShtml(str,gid,ior_TSY,ior_TSN,hr,type,more,method,tiptype,team_h) {
    var typecase ;
    var acttype ;
    var method_type = 'single' ; // single 双方球队进球
    var wtype = 'TS' ; // 全场 TS ， 半场 HTS
    var rtype1 = 'TSY' ; // 全场 是 TSY  不是 TSN  ， 半场 是 HTSY  不是 HTSN
    var rtype2 = 'TSN' ; // 全场 是 TSY  不是 TSN  ， 半场 是 HTSY  不是 HTSN
    var tiptitle = '双方球队进球' ;
    var spegame = 'special' ; // 特殊玩法，需要减去本金
    var rb_tip = '' ; // 滚球标志
    var arrow_type = 'arrow_close' ; // 是否展开或者关闭
    var body_type = 'block' ; // 是否展开或者关闭
    var bk_tip = '' ;
    var p3_tiptype = '' ; // 综合过关

    if(more=='RB'){ // 滚球
        rb_tip = 'R' ;
        acttype = type ;
        typecase = type+'_RB' ;
        wtype = rb_tip+wtype ;
        rtype1 = rb_tip+rtype1 ;
        rtype2 = rb_tip+rtype2 ;

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
        if(hr=='half') {  // 半场
            rtype1 = 'HTSY' ;
            rtype2 = 'HTSN' ;
            wtype = 'HTS' ;
            arrow_type = 'arrow_open' ; // 是否展开或者关闭
            body_type = 'none' ; // 是否展开或者关闭
        }

    }
    if(method=='ds'){ // 单双
        tiptitle = '单 / 双' ;
        wtype = hr=='half'?'H'+rb_tip+'EO':rb_tip+'EO' ;
        if(type=='BK'){
            bk_tip = '总分: ' ;
            rb_tip ='' ; // 篮球滚球单双不需要 R
        }
        rtype1 = hr=='half'?'H'+rb_tip+'ODD':rb_tip+'ODD' ;
        rtype2 = hr=='half'?'H'+rb_tip+'EVEN':rb_tip+'EVEN' ;
        method_type ='t' ;

        arrow_type = 'arrow_open' ; // 是否展开或者关闭
        body_type = 'none' ; // 是否展开或者关闭

    }

    if(tiptype=='p3'){ // 综合过关
        var p3_rtype = 'P' ;
        p3_tiptype = 'p3' ;
        if(hr =='half') { // 半场
            p3_rtype = 'HP' ;
        }
        rtype1 = p3_rtype+rtype1 ;
        rtype2 = p3_rtype+rtype2 ;
        wtype = 'p3' ; // 重新赋值
    }

    tiptitle = bk_tip+tiptitle ;

    var active_type = setActiveParams(acttype) ;

    str =  '<div class=" '+ returnCloseallGameList(ior_TSY) +'"> <div class="expand_action acc_1" ><span class="'+ arrow_type +'"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+tiptitle+'</div><div class="game_type"> - 上半场</div>' ;

    }else{
        str += '<div class="more_head">'+tiptitle+'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_ou" style="display:'+ body_type +'">' +
        '          <div  class="bet_action subaccountform" >  ' +
        '            <div class="bet_action right_line accordion_content_2" data-gid="'+ gid +'" data-type="H" data-rtype="'+rtype1+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'" >' +
        '            <div class="more_team_ou"><span> '+ (method=='ds'?'单':'是') +' </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_TSY) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_TSY) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' +
        '            <div class="bet_action accordion_content_2" data-gid="'+ gid +'" data-type="C" data-rtype="'+rtype2+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all" data-tiptype="'+ p3_tiptype +'" data-teamh="'+ team_h +'">' +
        '            <div class="more_team_ou"><span> '+ (method=='ds'?'双':'不是') +' </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_TSN) +'">' +

        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_TSN) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' ;

    str += '</div> </div>';

    return str ;

}

/* 球队进球数 大小 ( bk 球队得分 ) 数据 html 结构
    "sw_OUH": "Y",   半场 sw_HOUH               //球队进球数  主队  大小  开关
    "ratio_ouho": "2 / 2.5",   ratio_houho     //球队进球数  主队  大小  大  让球数
    "ratio_ouhu": "2 / 2.5",   ratio_houhu     //球队进球数  主队  大小  小  让球数
    "ior_OUHO": "1.05",    ior_HOUHO         //球队进球数  主队  大小  大  赔率
    "ior_OUHU": "0.79",   ior_HOUHU          //球队进球数  主队  大小  小  赔率

    "sw_OUC": "Y",   半场 sw_HOUC               //球队进球数  客队  大小  开关
    "ratio_ouco": "0.5",   ratio_houco         //球队进球数  客队  大小  大  让球数
    "ratio_oucu": "0.5",   ratio_houcu         //球队进球数  客队  大小  小  让球数
    "ior_OUCO": "0.76",   ior_HOUCO          //球队进球数  客队  大小  大  赔率
    "ior_OUCU": "1.08",   ior_HOUCU          //球队进球数  客队  大小  小  赔率
    大小 特殊： data-type ：主队 C，客队 H
* */
function setQDJQDxhtml(str,gid,team_h,team_c,ior_OUHO,ior_OUHU,ratio_ouho,ratio_ouhu,ior_OUCO,ior_OUCU,ratio_ouco,ratio_oucu,hr,type,more) {
    var typecase ;
    var acttype ;
    var method_type = 'single' ; // ou: 大小 全场,hou :大小 半场
    var wtype = 'OU' ; // 全场： 今日主队 OUH  今日客队 OUC   滚球 ROU ， 半场： 今日主队 HOUH  今日客队 HOUC 滚球 HROU
    var tiptitle = '-大 / 小' ;
    var rtype = '' ; // 全场：今日主队(大 OUHO  小 OUHU )  今日客队(大 OUCO  小 OUCU )， 半场：今日主队(大 HOUHO  小 HOUHU ) 今日客队(大 HOUCO  小 HOUCU )
    var first_title_tip = '球队进球数:' ;

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        if(hr=='half') {  // 半场 主队：HRUH，客队： HRUC
            wtype = 'HRU' ;
        }else{ // 全场
            wtype = 'ROU' ;
        }

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
        if(hr=='half') {  // 半场
            wtype = 'HOU' ;
        }

    }

    if(type == 'BK'){
        first_title_tip = '球队得分: ' ;
        method_type = 'ouhc' ;
    }

    var active_type = setActiveParams(acttype) ;

    /* 主队开始 */
    str =  '<div class=" '+ returnCloseallGameList(ior_OUHO) +'"> <div class="expand_action acc_1" ><span class="arrow_open"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+ first_title_tip +'<span class="head_team"> '+team_h+'</span>'+tiptitle+'</div><div class="game_type"> - 上半场</div>' ;
    }else{
        str += '<div class="more_head">'+ first_title_tip +'<span class="head_team"> '+team_h+'</span>'+tiptitle+'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_ou" style="display: none">' +
        '          <div  class="bet_action subaccountform" >  ' +
        '            <div id="bet_'+ gid +'_OUHO" class="bet_action right_line accordion_content_2" data-gid="'+ gid +'" data-type="O" data-rtype="'+wtype+'HO" data-wtype="'+wtype+'H" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all">' +
        '            <div class="more_team_ou"><span> 大 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUHO) +'">' +
        '                <div class="odds_gray"> '+ ratio_ouho +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUHO) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' +
        '            <div id="bet_'+ gid +'_OUHU" class="bet_action accordion_content_2" data-gid="'+ gid +'" data-type="U" data-rtype="'+wtype+'HU" data-wtype="'+wtype+'H" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all">' +
        '            <div class="more_team_ou"><span> 小 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUHU) +'">' +
        '                <div class="odds_gray">'+ ratio_ouhu +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUHU) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' ;

    str += '</div> </div>';
    /* 主队结束 */

    /* 客队开始 */
    str +=  '<div class=" '+ returnCloseallGameList(ior_OUCO) +'"> <div class="expand_action acc_1" ><span class="arrow_open"></span>';
    if(hr=='half'){  // 半场
        str += '<div class="left">'+ first_title_tip +'<span class="head_team"> '+team_c+'</span>'+tiptitle+'</div><div class="game_type"> - 上半场</div>' ;
    }else{
        str += '<div class="more_head">'+ first_title_tip +'<span class="head_team"> '+team_c+'</span>'+tiptitle+'</div>' ;
    }
    str += '</div>' +
        '<div class="expand_body subacc_2 body_ou" style="display: none">' +
        '          <div  class="bet_action subaccountform" >  ' +
        '            <div id="bet_'+ gid +'_OUCO" class="bet_action right_line accordion_content_2" data-gid="'+ gid +'" data-type="O" data-rtype="'+wtype+'CO" data-wtype="'+wtype+'C" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all">' +
        '            <div class="more_team_ou"><span> 大 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUCO) +'">' +
        '                <div class="odds_gray"> '+ ratio_ouco +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUCO) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' +
        '            <div id="bet_'+ gid +'_OUCU" class="bet_action accordion_content_2" data-gid="'+ gid +'" data-type="U" data-rtype="'+wtype+'CU" data-wtype="'+wtype+'C" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-flag="all">' +
        '            <div class="more_team_ou"><span> 小 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_OUCU) +'">' +
        '                <div class="odds_gray">'+ ratio_oucu +'</div>' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_OUCU) +'</font></div>' +
        '                  </div>    ' +
        '            </div>' ;

    str += '</div> </div>';

    return str ;

}

/*
* 所有玩法 半场 / 全场 , 独赢 & 双方球队进球 ，双重机会 & 双方球队进球  html 结构
*   "sw_F": "Y",                     //半场/全场  开关
    "ior_FHH": "1.87",               //半场/全场  主队/主队  赔率
    "ior_FHN": "21.0",               //半场/全场  主队/和局  赔率
    "ior_FHC": "51.0",               //半场/全场  主队/客队  赔率
    "ior_FNH": "4.2",                //半场/全场  和局/主队  赔率
    "ior_FNN": "7.3",                //半场/全场  和局/和局  赔率
    "ior_FNC": "18.0",               //半场/全场  和局/客队  赔率
    "ior_FCH": "26.0",               //半场/全场  客队/主队  赔率
    "ior_FCN": "21.0",               //半场/全场  客队/和局  赔率
    "ior_FCC": "14.0",               //半场/全场  客队/客队  赔率
 *  */
function setBanQuanChtml(str,gid,team_h,team_c,ior_FHH,ior_FHN,ior_FHC,ior_FNH,ior_FNN,ior_FNC,ior_FCH,ior_FCN,ior_FCC,hr,type,more,method) {
    var typecase ;
    var acttype ;
    var tiptitle = '半场 / 全场' ;
    var spegame ='special' ; // 可赢金额需要减去本金, 双重机会也需要减去本金
    var wtype = 'F' ; // wtype
    var method_type = 'f' ; //
    var tiptype = ' / ';
    var rtype1 = 'FHH' ;
    var rtype2 = 'FHN' ;
    var rtype3 = 'FHC' ;
    var rtype4 = 'FNH' ;
    var rtype5 = 'FNN' ;
    var rtype6 = 'FNC' ;
    var rtype7 = 'FCH' ;
    var rtype8 = 'FCN' ;
    var rtype9 = 'FCC' ;
    var arrow_type = 'arrow_close' ; // 是否展开或者关闭
    var body_type = 'block' ; // 是否展开或者关闭
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }
    if(method=='dysf'){ // 独赢 & 双方球队进球  method ：半场全场 bcqc
        tiptitle = '独赢 & 双方球队进球' ;
        tiptype = ' & ';
        method_type = 'single' ;
        spegame ='' ; // 可赢金额
        wtype = 'MTS' ; // wtype
        rtype1 = 'MTSHY' ;
        rtype2 = 'MTSHN' ;
        rtype3 = '' ;
        rtype4 = 'MTSNY' ;
        rtype5 = 'MTSNN' ;
        rtype6 = '' ;
        rtype7 = 'MTSCY' ;
        rtype8 = 'MTSCN' ;
        rtype9 = '' ;
    }else if (method=='sfqdjq'){ // 双重机会 & 双方球队进球
        arrow_type = 'arrow_open' ; // 是否展开或者关闭
        body_type = 'none' ; // 是否展开或者关闭
        tiptitle = '双重机会 & 双方球队进球' ;
        tiptype = ' & ';
        method_type = 'single' ;
        wtype = 'DS' ; // wtype
        rtype1 = 'DSHY' ;
        rtype2 = 'DSHN' ;
        rtype3 = '' ;
        rtype4 = 'DSCY' ;
        rtype5 = 'DSCN' ;
        rtype6 = '' ;
        rtype7 = 'DSSY' ;
        rtype8 = 'DSSN' ;
        rtype9 = '' ;
    }
    wtype = rb_tip+wtype ;

    var active_type = setActiveParams(acttype) ;

    str =  '<div class=""> <div class="expand_action acc_1 " ><span class="'+arrow_type+'"></span>';
    str += '<div class="more_head">'+ tiptitle +'</div>' ;

    str += '</div>' +
        '<div class="expand_body subacc_2 body_r" style="display: '+body_type+'">' +
        '          <div class="bet_action subaccountform '+(ior_FHH==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype1+'" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ team_h+ (method=='sfqdjq'?' / 和局':'') +tiptype+ (method=='bcqc'?team_h:'是') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FHH) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FHH) +'</font></div>' +
        '                  </div> ' +
        '              </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FHN==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype2+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ team_h + (method=='sfqdjq'?' / 和局':'') +tiptype+(method=='bcqc'?'和局':'不是')+'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FHN) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FHN) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FHC==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype3+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ team_h +tiptype+ team_c +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FHC) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FHC) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FNH==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype4+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (method=='sfqdjq'?team_c+' / ':'') +'和局'+tiptype+ (method=='bcqc'?team_h:'是') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FNH) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FNH) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;
    str += '<div class="bet_action subaccountform '+(ior_FNN==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype5+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (method=='sfqdjq'?team_c+' / ':'') +'和局'+tiptype+(method=='bcqc'?'和局':'不是')+'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FNN) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FNN) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FNC==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype6+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>和局'+tiptype+ team_c +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FNC) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FNC) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FCH==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype7+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (method=='sfqdjq'?team_h+' / ':'') +team_c+tiptype+(method=='bcqc'?team_h:'是') +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FCH) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FCH) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;
    str += '<div class="bet_action subaccountform '+(ior_FCN==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype8+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ (method=='sfqdjq'?team_h+' / ':'')  + team_c +tiptype+(method=='bcqc'?'和局':'不是')+'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FCN) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FCN) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_FCC==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+rtype9+'" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>'+ team_c+tiptype+team_c +'</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_FCC) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_FCC) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;

    str += '</div> </div>';

    return str ;
}

/*
* 所有玩法 净胜球数 html 结构
    "sw_WM": "Y",                 //净胜球数   开关
    "ior_WMH1": "3.65",           //净胜球数   主队净胜1球  赔率
    "ior_WMH2": "3.65",
    "ior_WMH3": "5.6",
    "ior_WMHOV": "6.8",           //净胜球数   主队净胜4球或更多  赔率
    "ior_WMC1": "10.5",           //净胜球数   客队净胜1球  赔率
    "ior_WMC2": "36.0",
    "ior_WMC3": "111.0",
    "ior_WMCOV": "111.0",         //净胜球数   客队净胜4球或更多  赔率
    "ior_WM0": "16.5",            //净胜球数   0-0和局  赔率
    "ior_WMN": "6.2",             //净胜球数   任意进球和局  赔率
 *  */
function setJingSQShtml(str,gid,team_h,team_c,ior_WMH1,ior_WMH2,ior_WMH3,ior_WMHOV,ior_WMC1,ior_WMC2,ior_WMC3,ior_WMCOV,ior_WM0,ior_WMN,hr,type,more) {
    var typecase ;
    var acttype ;
    var tiptitle = '净胜球数' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype = 'WM' ; // wtype
    var method_type = 'single' ; //
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;
        wtype = 'R'+wtype ;
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }

    var active_type = setActiveParams(acttype) ;

    str =  '<div class=""> <div class="expand_action acc_1 " ><span class="arrow_close"></span>';
    str += '<div class="more_head">'+ tiptitle +'</div>' ;

    str += '</div>' +
        '<div class="expand_body subacc_2 body_r" >' +
        '<div class="sub_title"><div class="sub_title_bg">'+ team_h +'</div></div>'+ /* 主队 开始*/
        '          <div class="bet_action subaccountform '+(ior_WMH1==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMH1" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜1球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMH1) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMH1) +'</font></div>' +
        '                  </div> ' +
        '              </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_WMH2==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMH2" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜2球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMH2) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMH2) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_WMH3==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMH3" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜3球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMH3) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMH3) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_WMHOV==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMHOV" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜4球或更多</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMHOV) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMHOV) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;
    str +='<div class="sub_title"><div class="sub_title_bg">'+ team_c +'</div></div>'; /* 客队 开始*/
    str += '<div class="bet_action subaccountform '+(ior_WMC1==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMC1" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜1球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMC1) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMC1) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_WMC2==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMC2" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜2球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMC2) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMC2) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_WMC3==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMC3" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜3球</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMC3) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMC3) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;
    str += '<div class="bet_action subaccountform '+(ior_WMCOV==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMCOV" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>净胜4球或更多</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMCOV) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMCOV) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform tr_last '+(ior_WM0==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WM0" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>0 - 0 和局</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WM0) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WM0) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform tr_last '+(ior_WMN==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rb_tip+'WMN" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span>任何进球和局</span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_WMN) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_WMN) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;

    str += '</div> </div>';

    return str ;
}

/*
*  独赢 & 进球 大 / 小 ( 只有滚球才有 )
*  开关  sw_MOUA   "sw_RMUA":"N",
         sw_MOMUB  "sw_RMUB":"Y",
         sw_MOMUC   "sw_RMUC":"Y",
         sw_MOMUD   "sw_RMUD":"Y",
   ior_MOUAHO  "ior_RMUAHO":"2.260", 主队 大 1.5
   ior_MOUAHU  "ior_RMUAHU":"0.000", 主队 小 1.5
   ior_MOUACO  "ior_RMUACO":"4.050", 客队 大 1.5
   ior_MOUACU  "ior_RMUACU":"0.000", 客队 小 1.5
   ior_MOUANO  "ior_RMUANO":"7.100", 和局 大 1.5
   ior_MOUANU  "ior_RMUANU":"3.700", 和局 小 1.5

   ior_MOUBHO  "ior_RMUBHO":"2.260", 主队 大 2.5
   ior_MOUBHU  "ior_RMUBHU":"0.000", 主队 小 2.5
   ior_MOUBCO  "ior_RMUBCO":"4.050", 客队 大 2.5
   ior_MOUBCU  "ior_RMUBCU":"0.000", 客队 小 2.5
   ior_MOUBNO  "ior_RMUBNO":"7.100", 和局 大 2.5
   ior_MOUBNU  "ior_RMUBNU":"3.700", 和局 小 2.5

   ior_MOUCHO  "ior_RMUCHO":"4.500", 主队 大 3.5
   ior_MOUCHU  "ior_RMUCHU":"3.850", 主队 小 3.5
   ior_MOUCCO  "ior_RMUCCO":"11.500", 客队 大 3.5
   ior_MOUCCU  "ior_RMUCCU":"6.300", 客队 小 3.5
   ior_MOUCNO  "ior_RMUCNO":"7.100", 和局 大 3.5
   ior_MOUCNU  "ior_RMUCNU":"3.700", 和局 小 3.5

   ior_MOUDHO   "ior_RMUDHO":"8.200", 主队 大 4.5
   ior_MOUDHU  "ior_RMUDHU":"3.000", 主队 小 4.5
   ior_MOUDCO  "ior_RMUDCO":"19.500", 客队 大 4.5
   ior_MOUDCU  "ior_RMUDCU":"5.600", 客队 小 4.5
   ior_MOUDNO  "ior_RMUDNO":"66.000", 和局 大 4.5
   ior_MOUDNU  "ior_RMUDNU":"2.610", 和局 小 4.5
*
* */
function setDuYJQDXhtml(str,gid,team_h,team_c,ior_RMUAHO,ior_RMUANO,ior_RMUACO,ior_RMUAHU,ior_RMUANU,ior_RMUACU,ior_RMUBHO,ior_RMUBNO,ior_RMUBCO,ior_RMUBHU,ior_RMUBNU,ior_RMUBCU,ior_RMUCHO,ior_RMUCNO,ior_RMUCCO,ior_RMUCHU,ior_RMUCNU,ior_RMUCCU,ior_RMUDHO,ior_RMUDNO,ior_RMUDCO,ior_RMUDHU,ior_RMUDNU,ior_RMUDCU,sw_RMUA,sw_RMUB,sw_RMUC,sw_RMUD,hr,type,more){
    var typecase ;
    var acttype ;
    var tiptitle = '独赢 & 进球 大 / 小' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype1 = 'RMUA' ; // wtype  RMUA  RMUB  RMUC  RMUD  , rtype RMUAHO  RMUAHU , RMUANO RMUANU ,RMUACO RMUACU,
    var wtype2 = 'RMUB' ; // wtype  RMUA  RMUB  RMUC  RMUD
    var wtype3 = 'RMUC' ; // wtype  RMUA  RMUB  RMUC  RMUD
    var wtype4 = 'RMUD' ; // wtype  RMUA  RMUB  RMUC  RMUD
    var method_type = 'single' ; //
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }

    var active_type = setActiveParams(acttype) ;

    str =  '<div class=""> ' +
        '<div class="expand_action acc_1 " ><span class="arrow_close"></span>';
    str += '<div class="more_head">'+ tiptitle +'</div> </div>' ;

    str += '<div class="expand_body subacc_2 body_r" >' ;

    str += '<div id="title_rmua" class="sub_title '+ (sw_RMUA=='N'?'hide-cont':'') +'">' +
        '   <div class="sub_title_th">'+team_h+'</div>' +
        '       <div class="sub_title_th">' +
        '       <div class="x_line">和局</div>' +
        '   </div>' +
        '   <div class="sub_title_th">'+team_c+'</div>' +
        '</div>'+
        '<div id="body_rmua_1" class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RMUAHO,ior_RMUANO,ior_RMUACO) +'"> ' +
        '                    ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'HO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUAHO) +'">' +
        '                    <div class="more_con">大 1.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUAHO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    ' +
        '                        ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'NO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUANO) +'">' +
        '                            <div class="more_con">大 1.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUANO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                        ' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'CO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUACO) +'">' +
        '                            <div class="more_con">大 1.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUACO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>'+
        '<div id="body_rmua_1" class="more_tr more_line2 '+ returnBoDanCloseGame(ior_RMUAHU,ior_RMUANU,ior_RMUACU) +'">            ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'HU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUAHU) +'">' +
        '                            <div class="more_con">小 1.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUAHU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'NU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUANU) +'">' +
        '                                <div class="more_con">小 1.5</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUANU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'CU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUACU) +'">' +
        '                    <div class="more_con">小 1.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUACU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>';

    str += '<div id="title_rmub" class="sub_title '+ (sw_RMUB=='N'?'hide-cont':'') +'">' +
        '   <div class="sub_title_th">'+team_h+'</div>' +
        '       <div class="sub_title_th">' +
        '       <div class="x_line">和局</div>' +
        '   </div>' +
        '   <div class="sub_title_th">'+team_c+'</div>' +
        '</div>'+
        '<div  class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RMUBHO,ior_RMUBNO,ior_RMUBCO) +'"> ' +
        '                    ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'HO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBHO) +'">' +
        '                    <div class="more_con">大 2.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBHO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    ' +
        '                        ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'NO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBNO) +'">' +
        '                            <div class="more_con">大 2.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBNO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                        ' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'CO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBCO) +'">' +
        '                            <div class="more_con">大 2.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBCO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>'+
        '<div  class="more_tr more_line2 '+ returnBoDanCloseGame(ior_RMUBHU,ior_RMUBNU,ior_RMUBCU) +'"> ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'HU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBHU) +'">' +
        '                            <div class="more_con">小 2.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBHU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'NU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBNU) +'">' +
        '                                <div class="more_con">小 2.5</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBNU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'CU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUBCU) +'">' +
        '                    <div class="more_con">小 2.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUBCU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>';

    str += '<div id="title_rmuc" class="sub_title '+ (sw_RMUC=='N'?'hide-cont':'') +'">' +
        '   <div class="sub_title_th">'+team_h+'</div>' +
        '       <div class="sub_title_th">' +
        '       <div class="x_line">和局</div>' +
        '   </div>' +
        '   <div class="sub_title_th">'+team_c+'</div>' +
        '</div>'+
        '<div  class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RMUCHO,ior_RMUCNO,ior_RMUCCO) +'"> ' +
        '                    ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'HO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCHO) +'">' +
        '                    <div class="more_con">大 3.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCHO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    ' +
        '                        ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'NO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCNO) +'">' +
        '                            <div class="more_con">大 3.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCNO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                        ' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'CO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCCO) +'">' +
        '                            <div class="more_con">大 3.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCCO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>'+
        '<div  class="more_tr more_line2 '+ returnBoDanCloseGame(ior_RMUCHU,ior_RMUCNU,ior_RMUCCU) +'"> ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'HU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCHU) +'">' +
        '                            <div class="more_con">小 3.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCHU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'NU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCNU) +'">' +
        '                                <div class="more_con">小 3.5</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCNU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'CU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUCCU) +'">' +
        '                    <div class="more_con">小 3.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUCCU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>';
    str += '<div id="title_rmud" class="sub_title '+ (sw_RMUD=='N'?'hide-cont':'') +'">' +
        '   <div class="sub_title_th">'+team_h+'</div>' +
        '       <div class="sub_title_th">' +
        '       <div class="x_line">和局</div>' +
        '   </div>' +
        '   <div class="sub_title_th">'+team_c+'</div>' +
        '</div>'+
        '<div  class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RMUDHO,ior_RMUDNO,ior_RMUDCO) +'"> ' +
        '                    ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'HO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDHO) +'">' +
        '                    <div class="more_con">大 4.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDHO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                    ' +
        '                        ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'NO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDNO) +'">' +
        '                            <div class="more_con">大 4.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDNO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                        ' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'CO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDCO) +'">' +
        '                            <div class="more_con">大 4.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDCO) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>'+
        '<div  class="more_tr more_line2 '+ returnBoDanCloseGame(ior_RMUDHU,ior_RMUDNU,ior_RMUDCU) +'"> ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'HU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDHU) +'">' +
        '                            <div class="more_con">小 4.5</div>' +
        '                            <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDHU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '                ' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'NU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDNU) +'">' +
        '                                <div class="more_con">小 4.5</div>' +
        '                                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDNU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '' +
        '                    <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'CU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '                    <div class="more_td_line '+ returnCloseGame(ior_RMUDCU) +'">' +
        '                    <div class="more_con">小 4.5</div>' +
        '                        <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RMUDCU) +'</font></div>' +
        '                    </div>' +
        '                    </div>' +
        '</div>';


    str += '</div>';

    str += '</div>';
    return str ;
}

/*
*  进球 大 / 小 & 进球 单 / 双 ( 只有足球滚球才有 )
*  单 ：大1.5 ior_RUEAOO 小1.5 ior_RUEAUO ，双 ：大1.5 ior_RUEAOE  小1.5 ior_RUEAUE  , 开关 sw_RUEA
*  单 ：大2.5 ior_RUEBOO 小2.5 ior_RUEBUO ，双 ：大2.5 ior_RUEBOE  小2.5 ior_RUEBUE , 开关 sw_RUEB
*  单 ：大3.5 ior_RUECOO 小3.5 ior_RUECUO ，双 ：大3.5 ior_RUECOE 小3.5 ior_RUECUE , 开关 sw_RUEC
*  单 ：大4.5 ior_RUEDOO 小4.5 ior_RUEDUO ，双 ：大4.5 ior_RUEDOE  小4.5 ior_RUEDUE , 开关 sw_RUED
*
* 进球 大 / 小 & 双方球队进球
 * wtype RUTA  RUTB RUTC RUTD,
 * rtype RUTAOY RUTAUY, RUTAON RUTAUN, RUTBOY RUTBUY, RUTBON RUTBUN, RUTCOY RUTCUY, RUTCON RUTCUN ,RUTDOY RUTDUY ,RUTDON RUTDUN
* 是(大1.5 ior_RUTAOY，小1.5 ior_RUTAUY ) , 不是(大1.5 ior_RUTAON，小1.5 ior_RUTAUN) , 开关 sw_RUTA
* 是(大2.5 ior_RUTBOY，小2.5 ior_RUTBUY ) , 不是(大2.5 ior_RUTBON，小2.5 ior_RUTBUN ) , 开关 sw_RUTB
* 是(大3.5 ior_RUTCOY，小3.5 ior_RUTCUY ) , 不是(大2.5 ior_RUTCON，小3.5 ior_RUTCUN ) , 开关 sw_RUTC
* 是(大4.5 ior_RUTDOY，小4.5 ior_RUTDUY ) , 不是(大4.5 ior_RUTDON，小4.5 ior_RUTDUN ) , 开关 sw_RUTD
*
*
* */

function setJQDXJQDShtml(str,gid,ior_RUEAOO,ior_RUEAUO,ior_RUEAOE,ior_RUEAUE,ior_RUEBOO,ior_RUEBUO,ior_RUEBOE,ior_RUEBUE,ior_RUECOO,ior_RUECUO,ior_RUECOE,ior_RUECUE,ior_RUEDOO,ior_RUEDUO,ior_RUEDOE,ior_RUEDUE,sw_RUEA,sw_RUEB,sw_RUEC,sw_RUED,hr,type,more,method) {
    var typecase ;
    var acttype ;
    var tiptitle = '进球 大 / 小 & 进球 单 / 双' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype1 = 'UEA' ; // 1.5 wtype RUEA , rtype : 单(大/小)1.5: RUEAOO RUEAUO , 双(大/小)1.5: RUEAOE RUEAUE
    var wtype2 = 'UEB' ; // 2.5 wtype RUEB,  rtype 单(大/小)2.5: RUEBOO  RUEBUO  , 双(大/小)2.5: RUEBOE RUEBUE
    var wtype3 = 'UEC' ; // 3.5 wtype RUEC,  rtype 单(大/小)3.5:
    var wtype4 = 'UED' ; // 4.5 wtype RUED, rtype 单(大/小)4.5:
    var rtype1 = 'OO' ;
    var rtype2 = 'OE' ;
    var rtype3 = 'UO' ;
    var rtype4 = 'UE' ;
    var method_type = 'single' ; //
    var rb_tip = '' ; // 滚球标志
    var sub_title_1 = '单' ;
    var sub_title_2 = '双' ;

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }
    if(method=='sfqdjq'){ // 进球 大 / 小 & 双方球队进球
        tiptitle = '进球 大 / 小 & 双方球队进球' ;
        wtype1 = 'UTA' ;
        wtype2 = 'UTB' ;
        wtype3 = 'UTC' ;
        wtype4 = 'UTD' ;
        rtype1 = 'OY' ;
        rtype2 = 'ON' ;
        rtype3 = 'UY' ;
        rtype4 = 'UN' ;
        sub_title_1 = '是' ;
        sub_title_2 = '不是' ;
    }
    wtype1 = rb_tip+wtype1 ;
    wtype2 = rb_tip+wtype2 ;
    wtype3 = rb_tip+wtype3 ;
    wtype4 = rb_tip+wtype4 ;

    var active_type = setActiveParams(acttype) ;
    str = '<div>' ;
    str = '<div class="expand_action  acc_1" style="">' +
        '<span id="arrow_roue" class="arrow_open"></span>' +
        '<div class="left">'+ tiptitle +'</div>' +
        '</div>' ;

    str += '<div class="expand_body subacc_2 body_pd" style="display: none">' ;
    str += '<div class="sub_title '+ (sw_RUEA=='N'?'hide-cont':'') +'">' +
        '       <div class="sub_title_two">'+sub_title_1+'</div>' +
        '       <div class="sub_title_two">'+sub_title_2+'</div>' +
        '  </div>' ;
    str += '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RUEAOO,ior_RUEAOE,'') +'">            \t' +
        '      <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+rtype1+'" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_RUEAOO) +'">' +
        '                <div class="more_con">大 1.5</div>' +
        '                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEAOO) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '       <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+rtype2+'" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '             <div class="more_td_line '+ returnCloseGame(ior_RUEAOE) +'">' +
        '                  <div class="more_con">大 1.5</div>' +
        '                  <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEAOE) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '</div>' ;
    str += '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_RUEAUO,ior_RUEAUE,'') +'"> ' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+rtype3+'" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUEAUO) +'">' +
        '                     <div class="more_con">小 1.5</div>' +
        '                      <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEAUO) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+rtype4+'" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUEAUE) +'">' +
        '                   <div class="more_con">小 1.5</div>' +
        '                   <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEAUE) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '</div>' ;
    str += '<div class="sub_title '+ (sw_RUEB=='N'?'hide-cont':'') +'">' +
        '       <div class="sub_title_two">'+sub_title_1+'</div>' +
        '       <div class="sub_title_two">'+sub_title_2+'</div>' +
        '  </div>' ;
    str += '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RUEBOO,ior_RUEBOE,'') +'">            \t' +
        '      <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+rtype1+'" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_RUEBOO) +'">' +
        '                <div class="more_con">大 2.5</div>' +
        '                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEBOO) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '       <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+rtype2+'" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '             <div class="more_td_line '+ returnCloseGame(ior_RUEBOE) +'">' +
        '                  <div class="more_con">大 2.5</div>' +
        '                  <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEBOE) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '</div>' ;
    str += '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_RUEBUO,ior_RUEBUE,'') +'"> ' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+rtype3+'" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUEBUO) +'">' +
        '                     <div class="more_con">小 2.5</div>' +
        '                      <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEBUO) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+rtype4+'" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUEBUE) +'">' +
        '                   <div class="more_con">小 2.5</div>' +
        '                   <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEBUE) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '</div>' ;
    str += '<div class="sub_title '+ (sw_RUEC=='N'?'hide-cont':'') +'">' +
        '       <div class="sub_title_two">'+sub_title_1+'</div>' +
        '       <div class="sub_title_two">'+sub_title_2+'</div>' +
        '  </div>' ;
    str += '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RUECOO,ior_RUECOE,'') +'">            \t' +
        '      <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+rtype1+'" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_RUECOO) +'">' +
        '                <div class="more_con">大 3.5</div>' +
        '                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUECOO) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '       <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+rtype2+'" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '             <div class="more_td_line '+ returnCloseGame(ior_RUECOE) +'">' +
        '                  <div class="more_con">大 3.5</div>' +
        '                  <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUECOE) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '</div>' ;
    str += '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_RUECUO,ior_RUECUE,'') +'"> ' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+rtype3+'" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUECUO) +'">' +
        '                     <div class="more_con">小 3.5</div>' +
        '                      <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUECUO) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+rtype4+'" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUECUE) +'">' +
        '                   <div class="more_con">小 3.5</div>' +
        '                   <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUECUE) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '</div>' ;
    str += '<div class="sub_title '+ (sw_RUED=='N'?'hide-cont':'') +'">' +
        '       <div class="sub_title_two">'+sub_title_1+'</div>' +
        '       <div class="sub_title_two">'+sub_title_2+'</div>' +
        '  </div>' ;
    str += '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_RUEDOO,ior_RUEDOE,'') +'">            \t' +
        '      <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+rtype1+'" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_RUEDOO) +'">' +
        '                <div class="more_con">大 4.5</div>' +
        '                <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEDOO) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '       <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+rtype2+'" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '             <div class="more_td_line '+ returnCloseGame(ior_RUEDOE) +'">' +
        '                  <div class="more_con">大 4.5</div>' +
        '                  <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEDOE) +'</font></div>' +
        '             </div>' +
        '      </div>' +
        '</div>' ;
    str += '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_RUEDUO,ior_RUEDUE,'') +'"> ' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+rtype3+'" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUECUO) +'">' +
        '                     <div class="more_con">小 4.5</div>' +
        '                      <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEDUO) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '         <div class="bet_action more_td_two" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+rtype4+'" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '               <div class="more_td_line '+ returnCloseGame(ior_RUEDUE) +'">' +
        '                   <div class="more_con">小 4.5</div>' +
        '                   <div id="more_ratio" class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_RUEDUE) +'</font></div>' +
        '                </div>' +
        '         </div>' +
        '</div>' ;
    str += '</div>' ;

    str += '</div>' ;

    return str ;
}


/*
* 双重机会 & 进球 大 / 小  篮球没有
*           "sw_DUA": "Y",                 //双重机会&进球 大/小 第1行  开关
            "ior_DUAHO": "1.22",           //双重机会&进球 大/小 第1行  主队/和局   大1.5  赔率
            "ior_DUAHU": "5.4",            //双重机会&进球 大/小 第1行  主队/和局   小1.5  赔率
            "ior_DUACO": "4.6",            //双重机会&进球 大/小 第1行  客队/和局   大1.5  赔率
            "ior_DUACU": "10.5",           //双重机会&进球 大/小 第1行  客队/和局   小1.5  赔率
            "ior_DUASO": "1.33",           //双重机会&进球 大/小 第1行  主队/客队   大1.5  赔率
            "ior_DUASU": "6.0",            //双重机会&进球 大/小 第1行  主队/客队   小1.5  赔率

            "sw_DUB": "Y",                 //双重机会&进球 大/小 第2行  开关
            "ior_DUBHO": "1.68",           大2.5
            "ior_DUBHU": "2.43",           小2.5
            "ior_DUBCO": "8.8",            大2.5
            "ior_DUBCU": "4.95",           小2.5
            "ior_DUBSO": "1.66",           大2.5
            "ior_DUBSU": "3.2",            小2.5

            "sw_DUC": "Y",                 //双重机会&进球 大/小 第3行  开关
            "ior_DUCHO": "2.57",           大3.5
            "ior_DUCHU": "1.62",           小3.5
            "ior_DUCCO": "13.0",           大3.5
            "ior_DUCCU": "4.2",            小3.5
            "ior_DUCSO": "2.76",           大3.5
            "ior_DUCSU": "1.8",            小3.5

            "sw_DUD": "Y",                 //双重机会&进球 大/小 第4行  开关
            "ior_DUDHO": "4.7",             大4.5
            "ior_DUDHU": "1.26",            小4.5
            "ior_DUDCO": "36.0",            大4.5
            "ior_DUDCU": "3.5",             小4.5
            "ior_DUDSO": "4.6",             大4.5
            "ior_DUDSU": "1.43",            小4.5

* */
function setSCJHJQDXhtml(str,gid,team_h,team_c,ior_DUAHO,ior_DUACO,ior_DUASO,ior_DUAHU,ior_DUACU,ior_DUASU,ior_DUBHO,ior_DUBCO,ior_DUBSO,ior_DUBHU,ior_DUBCU,ior_DUBSU,ior_DUCHO,ior_DUCCO,ior_DUCSO,ior_DUCHU,ior_DUCCU,ior_DUCSU,ior_DUDHO,ior_DUDCO,ior_DUDSO,ior_DUDHU,ior_DUDCU,ior_DUDSU,sw_DUA,sw_DUB,sw_DUC,sw_DUD,hr,type,more) {
    var typecase ;
    var acttype ;
    var tiptitle = '双重机会 & 进球 大 / 小' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype1 = 'DUA' ; // 1.5 wtype  rtype (大/小)1.5: DUAHO  DUAHU  DUACO DUACU  DUASO DUASU
    var wtype2 = 'DUB' ; // 2.5 wtype  rtype (大/小)2.5: DUBHO  DUBHU  DUBCO DUBCU  DUBSO DUBSU
    var wtype3 = 'DUC' ; // 3.5 wtype  rtype (大/小)3.5: DUCHO  DUCHU  DUCCO DUCCU  DUCSO DUCSU
    var wtype4 = 'DUD' ; // 4.5 wtype  rtype (大/小)3.5: DUDHO  DUDHU  DUDCO DUDCU  DUDSO DUDSU
    var method_type = 'single' ; //
    var rb_tip = '' ; // 滚球标志

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;
    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;
    }
    wtype1 = rb_tip+wtype1 ;
    wtype2 = rb_tip+wtype2 ;
    wtype3 = rb_tip+wtype3 ;
    wtype4 = rb_tip+wtype4 ;

    var active_type = setActiveParams(acttype) ;

    str =  '<div class=""> <div class="expand_action acc_1 " ><span class="arrow_open"></span>';
    str += '<div class="more_head">'+ tiptitle +'</div>' ;

    str += '</div>' ;
    str +='<div class="expand_body subacc_2 body_r" style="display:none;" >' +
        '<div class="sub_title_twin '+ (sw_DUA=='N'?'hide-cont':'') +'">' +
        '<div class="sub_title_th">'+team_h+'<br>/ 和局</div>' +
        '    <div class="sub_title_th">' +
        '       <div class="x_line">'+team_c+'<br>/ 和局</div>' +
        '     </div>' +
        '     <div class="sub_title_th">'+team_h+'<br>/ '+team_c+'</div>' +
        '</div>'+
        '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_DUAHO,ior_DUACO,ior_DUASO) +'">  ' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'HO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+  returnCloseGame(ior_DUAHO) +'">' +
        '               <div class="more_con">大 1.5</div>' +
        '               <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUAHO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'CO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUACO) +'">' +
        '               <div class="more_con">大 1.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUACO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'SO" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUASO) +'">' +
        '                <div class="more_con">大 1.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUASO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '</div>'+
        '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_DUAHU,ior_DUACU,ior_DUASU) +'"> ' +
        '     <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'HU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUAHU) +'">' +
        '                <div class="more_con">小 1.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUAHU) +'</font></div>' +
        '          </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'CU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '           <div class="more_td_line '+ returnCloseGame(ior_DUACU) +'">' +
        '                   <div class="more_con">小 1.5</div>' +
        '                        <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUACU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype1+'SU" data-wtype="'+wtype1+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_DUASU) +'">' +
        '                 <div class="more_con">小 1.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUASU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        ' </div>'+
        '<div class="sub_title_twin '+ (sw_DUB=='N'?'hide-cont':'') +'">' +
        '    <div class="sub_title_th">'+team_h+'<br>/ 和局</div>' +
        '         <div class="sub_title_th">' +
        '              <div class="x_line">'+team_c+'<br>/ 和局</div>' +
        '         </div>' +
        '         <div class="sub_title_th">'+team_h+'<br>/ '+team_c+'</div>' +
        '</div>'+
        '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_DUBHO,ior_DUBCO,ior_DUBSO)  +'">  ' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'HO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUBHO) +'">' +
        '               <div class="more_con">大 2.5</div>' +
        '               <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBHO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'CO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUBCO) +'">' +
        '               <div class="more_con">大 2.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBCO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'SO" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUBSO) +'">' +
        '                <div class="more_con">大 2.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBSO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '</div>'+
        '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_DUBHU,ior_DUBCU,ior_DUBSU) +'"> ' +
        '     <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'HU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUBHU) +'">' +
        '                <div class="more_con">小 2.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBHU) +'</font></div>' +
        '          </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'CU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '           <div class="more_td_line '+ returnCloseGame(ior_DUBCU) +'">' +
        '                   <div class="more_con">小 2.5</div>' +
        '                        <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBCU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype2+'SU" data-wtype="'+wtype2+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_DUBSU) +'">' +
        '                 <div class="more_con">小 2.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUBSU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        ' </div>'+
        '<div class="sub_title_twin '+ (sw_DUC=='N'?'hide-cont':'') +'">' +
        '     <div class="sub_title_th">'+team_h+'<br>/ 和局</div>' +
        '     <div class="sub_title_th">' +
        '          <div class="x_line">'+team_c+'<br>/ 和局</div>' +
        '      </div>' +
        '      <div class="sub_title_th">'+team_h+'<br>/ '+team_c+'</div>' +
        '</div>'+
        '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_DUCHO,ior_DUCCO,ior_DUCSO) +'">  ' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'HO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUCHO) +'">' +
        '               <div class="more_con">大 3.5</div>' +
        '               <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCHO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'CO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUCCO) +'">' +
        '               <div class="more_con">大 3.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCCO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'SO" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUCSO) +'">' +
        '                <div class="more_con">大 3.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCSO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '</div>'+
        '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_DUCHU,ior_DUCCU,ior_DUCSU) +'"> ' +
        '     <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'HU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUCHU) +'">' +
        '                <div class="more_con">小 3.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCHU) +'</font></div>' +
        '          </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'CU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '           <div class="more_td_line '+ returnCloseGame(ior_DUCCU) +'">' +
        '                   <div class="more_con">小 3.5</div>' +
        '                        <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCCU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype3+'SU" data-wtype="'+wtype3+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_DUCSU) +'">' +
        '                 <div class="more_con">小 3.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUCSU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        ' </div>'+
        '<div class="sub_title_twin '+ (sw_DUD=='N'?'hide-cont':'') +'">' +
        '     <div class="sub_title_th">'+team_h+'<br>/ 和局</div>' +
        '     <div class="sub_title_th">' +
        '          <div class="x_line">'+team_c+'<br>/ 和局</div>' +
        '      </div>' +
        '      <div class="sub_title_th">'+team_h+'<br>/ '+team_c+'</div>' +
        '</div>'+
        '<div class="more_tr more_tr_first '+ returnBoDanCloseGame(ior_DUDHO,ior_DUDCO,ior_DUDSO) +'">  ' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'HO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUDHO) +'">' +
        '               <div class="more_con">大 4.5</div>' +
        '               <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDHO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'CO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '         <div class="more_td_line '+ returnCloseGame(ior_DUDCO) +'">' +
        '               <div class="more_con">大 4.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDCO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '   <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'SO" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUDSO) +'">' +
        '                <div class="more_con">大 4.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDSO) +'</font></div>' +
        '          </div>' +
        '   </div>' +
        '</div>'+
        '<div class="more_tr more_tr_last '+ returnBoDanCloseGame(ior_DUDHU,ior_DUDCU,ior_DUDSU) +'"> ' +
        '     <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'HU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '          <div class="more_td_line '+ returnCloseGame(ior_DUDHU) +'">' +
        '                <div class="more_con">小 4.5</div>' +
        '                <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDHU) +'</font></div>' +
        '          </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'CU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '           <div class="more_td_line '+ returnCloseGame(ior_DUDCU) +'">' +
        '                   <div class="more_con">小 4.5</div>' +
        '                        <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDCU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        '      <div class="bet_action more_td" data-gid="'+ gid +'" data-type="" data-rtype="'+wtype4+'SU" data-wtype="'+wtype4+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">' +
        '            <div class="more_td_line '+ returnCloseGame(ior_DUDSU) +'">' +
        '                 <div class="more_con">小 4.5</div>' +
        '                 <div class="more_ratio"><font class="ratio_red">'+ changeTwoDecimal(ior_DUDSU) +'</font></div>' +
        '            </div>' +
        '      </div>' +
        ' </div>'+


        '</div>' ;


    str += '</div>';

    return str;

}

/*
* 所有玩法 球队得分:  - 最后一位数   html 结构 篮球独有
* 主队 0 或 5 : ior_PDH0 ,1 或 6 : ior_PDH1 ,2 或 7 : ior_PDH2 , 3 或 8 ：ior_PDH3 ，4 或 9 ： ior_PDH4
* 客队 0 或 5 : ior_PDC0 ,1 或 6 : ior_PDC1 ,2 或 7 : ior_PDC2 , 3 或 8 ：ior_PDC3 ，4 或 9 ： ior_PDC4
* ior_PDC0,ior_PDC1,ior_PDC2,ior_PDC3,ior_PDC4
* rtype : PDH0 PDH1 PDH2 PDH3 PDH4 ,客队 ：PDC0 PDC1 PDC2 PDC3 PDC4
 *  */
function setQDDFZHYWShtml(str,gid,team_h,team_c,ior_PDH0,ior_PDH1,ior_PDH2,ior_PDH3,ior_PDH4,hr,type,more) {
    var typecase ;
    var acttype ;
    var tiptitle = '球队得分:' ;
    var spegame ='special' ; // 可赢金额需要减去本金
    var wtype = 'PD' ; //
    var method_type = 'pd' ; //
    var rb_tip = '' ; // 滚球标志
    var team_name = team_h ; // 默认主队
    var tip_team = 'H' ; // H 主队 C 客队

    if(more=='RB'){ // 滚球
        acttype = type ;
        typecase = type+'_RB' ;
        rb_tip = 'R' ;

    }else{ // 今日和早盘 FU 早盘 FT 今日赛事
        acttype = more ;
        typecase = type ;

    }

    if(hr=='half') { // 此处 hr ：all 代表主队 ，half 代表客队
        team_name = team_c ;
        tip_team = 'C' ;
    }

    wtype = rb_tip+wtype ;

    var rtype = wtype+tip_team ;
    tiptitle = tiptitle+'<span class="head_team">'+team_name+'</span> - 最后一位数' ;

    var active_type = setActiveParams(acttype) ;


    str =  '<div class=" '+ returnCloseallGameList(ior_PDH0) +'"> <div class="expand_action acc_1 " ><span class="arrow_close"></span>';
    str += '<div class="more_head">'+ tiptitle +'</div>' ;
    str += '</div>' +
        '<div class="expand_body subacc_2 body_r" >' +
        '          <div  class="bet_action subaccountform '+(ior_PDH0==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+ rtype +'0" data-wtype="'+wtype+'" data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span> 0 或 5 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_PDH0) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_PDH0) +'</font></div>' +
        '                  </div> ' +
        '              </div>' +
        '          </div>' +
        '  <div  class="bet_action subaccountform '+(ior_PDH1==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rtype+'1" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span> 1 或 6 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_PDH1) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_PDH1) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_PDH2==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rtype+'2" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span> 2 或 7 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_PDH2) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_PDH2) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div  class="bet_action subaccountform '+(ior_PDH3==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rtype+'3" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span> 3 或 8 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_PDH3) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_PDH3) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' +
        '  <div class="bet_action subaccountform '+(ior_PDH4==0?'hide-cont':'')+'" data-gid="'+ gid +'" data-type="" data-rtype="'+rtype+'4" data-wtype="'+wtype+'"  data-method="'+ returnMethod(type,more,method_type) +'" data-case="'+ typecase +'" data-active="'+ active_type +'" data-spegame="'+ spegame +'" data-flag="all">  ' +
        '            <div class="accordion_content">' +
        '            <div class="more_team_t"><span> 4 或 9 </span></div>' +
        '                <div class="more_mem_box '+ returnCloseGame(ior_PDH4) +'">' +
        '                <div class="odds_red"><font class="ratio_red">'+ changeTwoDecimal(ior_PDH4) +'</font></div>' +
        '                  </div> ' +
        '            </div>' +
        '          </div>' ;

    str += '</div> </div>';

    return str ;
}


// 单场-让球 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 让球：滚球 全场：ior_REH ior_REC ratio_re ，半场：ior_HREH ior_HREC ratio_hre
* 让球开关 今日赛事 早盘 全场:sw_R  半场:sw_HR , 滚球
* tiptype: p3 综合过关
*
* */
function changeDcrqData(res,more,type,tiptype) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_REH,res.ior_REC,res.ratio_re,res.strong,'all',type,more,'rq') ; // 单场
        h_str += setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HREH,res.ior_HREC,res.ratio_hre,res.hstrong,'half',type,more,'rq') ; // 半场
    }else{ // 今日赛事,早盘
        if(tiptype=='p3'){ // 综合过关
            str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_PRH,res.ior_PRC,res.ratio,res.strong,'all',type,more,'rq','','','','',tiptype) ; // 单场
            h_str += setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HPRH,res.ior_HPRC,res.hratio,res.hstrong,'half',type,more,'rq','','','','',tiptype) ; // 半场
        }else{
            str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RH,res.ior_RC,res.ratio,res.strong,'all',type,more,'rq') ; // 单场
            h_str += setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HRH,res.ior_HRC,res.hratio,res.hstrong,'half',type,more,'rq') ; // 半场
        }


    }


    $('.bet-dcrq').html(str) ; // 单场
    $('.bet-bcrq').html(h_str) ; // 半场
}


// 单场-得分大小 数据处理 , res 数据 ,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 大小 滚球：ior_ROUH  ior_ROUC  ratio_rouo ratio_rouu ，半场：ior_HROUH  ior_HROUC ratio_rouho ratio_rouhu
*  大小开关 今日赛事 早盘 全场:sw_OU  半场:sw_HOU , 滚球
* */
function changeDcdxData(res,more,type,tiptype) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        str +=setDxhtml(str,res.gid,res.ior_ROUH, res.ior_ROUC,res.ratio_rouo,res.ratio_rouu,'all',type,more,res.team_h) ; // 单场
        // h_str +=setDxhtml(h_str,res.gid,res.ior_HROUH, res.ior_HROUC,res.ratio_rouho,res.ratio_rouhu,'half',type,more,res.team_h) ; // 半场
        h_str +=setDxhtml(h_str,res.gid,res.ior_HROUH, res.ior_HROUC,res.ratio_hrouo,res.ratio_hrouu,'half',type,more,res.team_h) ; // 半场
    }else{ // 今日赛事,早盘
        if(tiptype=='p3') { // 综合过关
            str +=setDxhtml(str,res.gid,res.ior_POUH, res.ior_POUC,res.ratio_o_str,res.ratio_u_str,'all',type,more,tiptype,res.team_h) ; // 单场
            h_str +=setDxhtml(h_str,res.gid,res.ior_HPOUH, res.ior_HPOUC,res.hratio_o_str,res.hratio_u_str,'half',type,more,tiptype,res.team_h) ; // 半场
        }else{
            str +=setDxhtml(str,res.gid,res.ior_OUH, res.ior_OUC,res.ratio_o,res.ratio_u,'all',type,more,res.team_h) ; // 单场
            h_str +=setDxhtml(h_str,res.gid,res.ior_HOUH, res.ior_HOUC,res.ratio_ho,res.ratio_hu,'half',type,more,res.team_h) ; // 半场
        }

    }

    $('.bet-dcdx').html(str) ; // 单场
    $('.bet-bcdx').html(h_str) ; // 半场

}

// 单场-独赢 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 独赢 滚球：全场 ior_RMH  ior_RMC ior_RMN，半场 ior_HRMH  ior_HRMC  ior_HRMN
*  独赢开关 今日赛事 早盘 全场:sw_M  半场:sw_HM , 滚球
* */
function changeDcdyData(res,more,type,tiptype) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RMH,res.ior_RMC,'','','all',type,more,'dy',res.ior_RMN) ; // 单场
        h_str +=setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HRMH,res.ior_HRMC,'','','half',type,more,'dy',res.ior_HRMN) ; // 半场
    }else{ // 今日赛事,早盘
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_MH,res.ior_MC,'','','all',type,more,'dy',res.ior_MN,'','','',tiptype) ; // 单场
        if(tiptype=='p3'){ // 综合过关
            h_str +=setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HPMH,res.ior_HPMC,'','','half',type,more,'dy',res.ior_HPMN,'','','',tiptype) ; // 半场
        }else{
            h_str +=setRqhtml(h_str,res.gid,res.team_h, res.team_c,res.ior_HMH,res.ior_HMC,'','','half',type,more,'dy',res.ior_HMN) ; // 半场
        }

    }

    $('.bet-dcdy').html(str) ; // 单场
    $('.bet-bcdy').html(h_str) ; // 半场
}


// 单场-波胆 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 波胆开关：今日和早盘：sw_PD  sw_HPD，滚球：sw_RPD sw_HRPD
* */
function changeDcbdData(res,more,type) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        if(res.sw_RPD=='Y'){ // 全场开关
            str +=setBodanhtml(str,res.gid,res.ior_RH1C0,res.ior_RH0C0,res.ior_RH0C1,res.ior_RH2C0,res.ior_RH1C1,res.ior_RH0C2,res.ior_RH2C1,res.ior_RH2C2,res.ior_RH1C2,res.ior_RH3C0,res.ior_RH3C3,res.ior_RH0C3,res.ior_RH3C1,res.ior_RH4C4,res.ior_RH1C3,res.ior_RH3C2,res.ior_RH2C3,res.ior_RH4C0,res.ior_RH0C4,res.ior_RH4C1,res.ior_RH1C4,res.ior_RH4C2,res.ior_RH2C4,res.ior_RH4C3,res.ior_RH3C4,res.ior_ROVH,'all',type,more) ; // 单场
        }else{
            str += '' ;
        }
        if(res.sw_HRPD=='Y'){ // 半场开关
            h_str +=setBodanhtml(str,res.gid,res.ior_HRH1C0,res.ior_HRH0C0,res.ior_HRH0C1,res.ior_HRH2C0,res.ior_HRH1C1,res.ior_HRH0C2,res.ior_HRH2C1,res.ior_HRH2C2,res.ior_HRH1C2,res.ior_HRH3C0,res.ior_HRH3C3,res.ior_HRH0C3,res.ior_HRH3C1,res.ior_HRH4C4,res.ior_HRH1C3,res.ior_HRH3C2,res.ior_HRH2C3,res.ior_HRH4C0,res.ior_HRH0C4,res.ior_HRH4C1,res.ior_HRH1C4,res.ior_HRH4C2,res.ior_HRH2C4,res.ior_HRH4C3,res.ior_HRH3C4,res.ior_HROVH,'half',type,more) ;  // 下半场
        }else{
            h_str +='' ; // 下半场
        }

    }else{ // 今日赛事,早盘
        if(res.sw_PD=='Y') { // 全场开关
            str +=setBodanhtml(str,res.gid,res.ior_H1C0,res.ior_H0C0,res.ior_H0C1,res.ior_H2C0,res.ior_H1C1,res.ior_H0C2,res.ior_H2C1,res.ior_H2C2,res.ior_H1C2,res.ior_H3C0,res.ior_H3C3,res.ior_H0C3,res.ior_H3C1,res.ior_H4C4,res.ior_H1C3,res.ior_H3C2,res.ior_H2C3,res.ior_H4C0,res.ior_H0C4,res.ior_H4C1,res.ior_H1C4,res.ior_H4C2,res.ior_H2C4,res.ior_H4C3,res.ior_H3C4,res.ior_OVH,'all',type,more) ; // 单场
        }else{
            h_str +='' ;// 下半场
        }
        if(res.sw_HPD=='Y') { // 半场开关
            h_str +=setBodanhtml(h_str,res.gid,res.ior_HH1C0,res.ior_HH0C0,res.ior_HH0C1,res.ior_HH2C0,res.ior_HH1C1,res.ior_HH0C2,res.ior_HH2C1,res.ior_HH2C2,res.ior_HH1C2,res.ior_HH3C0,res.ior_HH3C3,res.ior_HH0C3,res.ior_HH3C1,res.ior_HH4C4,res.ior_HH1C3,res.ior_HH3C2,res.ior_HH2C3,res.ior_HH4C0,res.ior_HH0C4,res.ior_HH4C1,res.ior_HH1C4,res.ior_HH4C2,res.ior_HH2C4,res.ior_HH4C3,res.ior_HH3C4,res.ior_HOVH,'half',type,more) ; // 半场
        }else{
            h_str +='' ;
        }

    }

    $('.bet-bddc').html(str) ; // 单场
    $('.bet-bdbc').html(h_str) ; // 半场
}

/* 总进球数
* * 全场 0-1 : ior_T01 ,2-3 : ior_T23 ,4-6 : ior_T46 , 7或以上：ior_OVER
* 半场 0 : ior_HT0 ,1 : ior_HT1 ,2 : ior_HT2 , 3或以上：ior_HTOV
* 总进球数开关 今日赛事 全场: sw_T  半场: sw_HT，滚球 sw_RT  sw_HRT
* */
function changeZongJQSData(res,more,type) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        if(res.sw_RT=='Y'){
            str += setZongJQShtml(str,res.gid,res.ior_RT01,res.ior_RT23,res.ior_RT46,res.ior_ROVER,'all',type,more) ; // 单场
        }else{
            str += '' ;
        }
        if(res.sw_HRT=='Y'){
            h_str += setZongJQShtml(h_str,res.gid,res.ior_HRT0,res.ior_HRT1,res.ior_HRT2,res.ior_HRTOV,'half',type,more) ; // 半场
        }else{
            h_str += '' ;
        }

    }else{ // 今日赛事,早盘
        if(res.sw_T=='Y'){
            str += setZongJQShtml(str,res.gid,res.ior_T01,res.ior_T23,res.ior_T46,res.ior_OVER,'all',type,more) ; // 单场
        }else{
            str += '';
        }
        if(res.sw_HT=='Y'){
            h_str += setZongJQShtml(h_str,res.gid,res.ior_HT0,res.ior_HT1,res.ior_HT2,res.ior_HTOV,'half',type,more) ; // 半场
        }else{
            h_str += '';
        }

    }

    $('.bet-zjqsdc').html(str) ; // 单场
    $('.bet-zjqsbc').html(h_str) ; // 半场
}


/* 双方球队进球
* 双方球队进球 开关 今日赛事 全场: sw_TS  半场: sw_HTS，滚球 sw_RTS
* */
function changeShuangFQDData(res,more,type) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        if(res.sw_RTS=='Y'){
            str +=setQDJQShtml(str,res.gid,res.ior_RTSY,res.ior_RTSN,'all',type,more); // 单场
        }
        h_str += '' ; // 滚球没有半场
    }else{ // 今日赛事,早盘
        if(res.sw_TS=='Y'){
            str +=setQDJQShtml(str,res.gid,res.ior_TSY,res.ior_TSN,'all',type,more) ; // 单场
        }else{
            str +='';
        }
        if(res.sw_HT=='Y'){
            h_str +=setQDJQShtml(str,res.gid,res.ior_HTSY,res.ior_HTSN,'half',type,more) ; // 半场
        }else{
            h_str +='';
        }

    }

    $('.bet-sfqddc').html(str) ; // 单场
    $('.bet-sfqdbc').html(h_str) ; // 半场
}


// 球队进球数 (bk 球队得分) 数据处理 , res 数据 ,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 大小 滚球：ior_ROUH  ior_ROUC  ratio_rouo ratio_rouu ，半场：ior_HROUH  ior_HROUC ratio_rouho ratio_rouhu
*  大小开关 今日赛事 全场：主队 sw_OUH  sw_ROUH 客队 sw_OUC  sw_ROUC ，半场：主队 sw_HOUH 客队 sw_HOUC
* */
function changeQDJQdxData(res,more,type) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球 HRUHO   ior_HRUHU
        str += setQDJQDxhtml(str,res.gid,res.team_h,res.team_c,res.ior_ROUHO,res.ior_ROUHU,res.ratio_rouho,res.ratio_rouhu,res.ior_ROUCO,res.ior_ROUCU,res.ratio_rouco,res.ratio_roucu,'all',type,more) ; // 单场
        h_str += setQDJQDxhtml(str,res.gid,res.team_h,res.team_c,res.ior_HRUHO,res.ior_HRUHU,res.ratio_hruho,res.ratio_hruhu,res.ior_HRUCO,res.ior_HRUCU,res.ratio_hruco,res.ratio_hrucu,'half',type,more) ;
    }else{ // 今日赛事,早盘
        str += setQDJQDxhtml(str,res.gid,res.team_h,res.team_c,res.ior_OUHO,res.ior_OUHU,res.ratio_ouho,res.ratio_ouhu,res.ior_OUCO,res.ior_OUCU,res.ratio_ouco,res.ratio_oucu,'all',type,more) ; // 单场
        h_str += setQDJQDxhtml(str,res.gid,res.team_h,res.team_c,res.ior_HOUHO,res.ior_HOUHU,res.ratio_houho,res.ratio_houhu,res.ior_HOUCO,res.ior_HOUCU,res.ratio_houco,res.ratio_houcu,'half',type,more) ; // 半场
    }

    $('.bet-qdjqszdc').html(str) ; // 单场
    $('.bet-qdjqszbc').html(h_str) ; // 半场

}

/* 单双
* 单双 开关 今日赛事 全场: sw_EO  半场: sw_HEO，滚球 sw_REO  sw_HREO
* */
function changeDanSData(res,more,type,tiptype) {
    var str ='' ; // 单场
    var h_str ='' ; // 半场
    if(more =='RB'){ // 滚球
        if(res.sw_REO){
            str += setQDJQShtml(str,res.gid,res.ior_REOO,res.ior_REOE,'all',type,more,'ds'); // 单场
        }else{
            str += '' ;
        }
        if(res.sw_HREO){
            h_str += setQDJQShtml(str,res.gid,res.ior_HREOO,res.ior_HREOE,'half',type,more,'ds') ; // 半场
        }else{
            h_str += '' ;
        }

    }else{ // 今日赛事,早盘
        if(tiptype=='p3'){ // 综合过关
            str += setQDJQShtml(str,res.gid,res.ior_PO,res.ior_PE,'all',type,more,'ds',tiptype,res.team_h) ; // 单场
        }
        if(res.sw_EO=='Y'){
            str += setQDJQShtml(str,res.gid,res.ior_EOO,res.ior_EOE,'all',type,more,'ds') ; // 单场
        }else{
            str += '';
        }
        if(res.sw_HEO=='Y'){
            h_str += setQDJQShtml(str,res.gid,res.ior_HEOO,res.ior_HEOE,'half',type,more,'ds') ; // 半场
        }else{
            h_str += '';
        }

    }

    $('.bet-dansdc').html(str) ; // 单场
    $('.bet-dansbc').html(h_str) ; // 半场
}

/* 半场/全场
*  半场/全场 开关 今日赛事 全场: sw_F ，滚球 sw_RF
* */
function changeBanQuanCData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RF=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_RFHH,res.ior_RFHN,res.ior_RFHC,res.ior_RFNH,res.ior_RFNN,res.ior_RFNC,res.ior_RFCH,res.ior_RFCN,res.ior_RFCC,'all',type,more,'bcqc'); // 单场
        }else{
            str += '' ;
        }

    }else{ // 今日赛事,早盘
        if(res.sw_F=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_FHH,res.ior_FHN,res.ior_FHC,res.ior_FNH,res.ior_FNN,res.ior_FNC,res.ior_FCH,res.ior_FCN,res.ior_FCC,'all',type,more,'bcqc') ; // 单场
        }else{
            str +='';
        }
    }

    $('.bet-qcbc').html(str) ; // 单场

}


/* 净胜球数
*  净胜球数 开关 今日赛事 全场: sw_WM  ，滚球 sw_RWM
* */
function changeJingSQSData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RWM=='Y'){
            str += setJingSQShtml(str,res.gid,res.team_h,res.team_c,res.ior_RWMH1,res.ior_RWMH2,res.ior_RWMH3,res.ior_RWMHOV,res.ior_RWMC1,res.ior_RWMC2,res.ior_RWMC3,res.ior_RWMCOV,res.ior_RWM0,res.ior_RWMN,'all',type,more); // 单场
        }else{
            str += '' ;
        }

    }else{ // 今日赛事,早盘
        if(res.sw_WM=='Y'){
            str += setJingSQShtml(str,res.gid,res.team_h,res.team_c,res.ior_WMH1,res.ior_WMH2,res.ior_WMH3,res.ior_WMHOV,res.ior_WMC1,res.ior_WMC2,res.ior_WMC3,res.ior_WMCOV,res.ior_WM0,res.ior_WMN,'all',type,more) ; // 单场
        }else{
            str +='';
        }
    }
    $('.bet-jsqs').html(str) ; // 单场

}
/*
* 独赢 & 进球 大 / 小 只有滚球才有
* */

function changeDuYJQDXData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if((res.sw_RMUA=='N' && res.sw_RMUB=='N'&&res.sw_RMUC=='N'&&res.sw_RMUD=='N') || !res.sw_RMUA){
            str += '' ;
        }else{
            str += setDuYJQDXhtml(str,res.gid,res.team_h,res.team_c,res.ior_RMUAHO,res.ior_RMUANO,res.ior_RMUACO,res.ior_RMUAHU,res.ior_RMUANU,res.ior_RMUACU,res.ior_RMUBHO,res.ior_RMUBNO,res.ior_RMUBCO,res.ior_RMUBHU,res.ior_RMUBNU,res.ior_RMUBCU,res.ior_RMUCHO,res.ior_RMUCNO,res.ior_RMUCCO,res.ior_RMUCHU,res.ior_RMUCNU,res.ior_RMUCCU,res.ior_RMUDHO,res.ior_RMUDNO,res.ior_RMUDCO,res.ior_RMUDHU,res.ior_RMUDNU,res.ior_RMUDCU,res.sw_RMUA,res.sw_RMUB,res.sw_RMUC,res.sw_RMUD,'all',type,more); // 单场
        }


    }else{ // 今日赛事,早盘
        str +='';
    }
    $('.bet-dyjqdx').html(str) ; // 单场

}

/*
*  进球 大 / 小 & 进球 单 / 双 ( 只有足球滚球才有 )
* */
function changeJQDXJQDSData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if((res.sw_RUEA=='N' && res.sw_RUEB=='N'&&res.sw_RUEC=='N'&&res.sw_RUED=='N') || !res.sw_RUEA){
            str += '' ;
        }else{
            str += setJQDXJQDShtml(str,res.gid,res.ior_RUEAOO,res.ior_RUEAUO,res.ior_RUEAOE,res.ior_RUEAUE,res.ior_RUEBOO,res.ior_RUEBUO,res.ior_RUEBOE,res.ior_RUEBUE,res.ior_RUECOO,res.ior_RUECUO,res.ior_RUECOE,res.ior_RUECUE,res.ior_RUEDOO,res.ior_RUEDUO,res.ior_RUEDOE,res.ior_RUEDUE,res.sw_RUEA,res.sw_RUEB,res.sw_RUEC,res.sw_RUED,'all',type,more,'jqds') ; // 单场
        }


    }else{ // 今日赛事,早盘
        str +='';
    }
    $('.bet-jqdxds').html(str) ; // 单场

}

/*
* 进球 大 / 小 & 双方球队进球
* sw_RUTA sw_RUTB sw_RUTC sw_RUTD
* */
function changeJQDXJSFQDJQata(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if((res.sw_RUTA=='N' && res.sw_RUTB=='N'&&res.sw_RUTC=='N'&&res.sw_RUTD=='N') || !res.sw_RUTA){
            str += '' ;
        }else{
            str += setJQDXJQDShtml(str,res.gid,res.ior_RUTAOY,res.ior_RUTAUY,res.ior_RUTAON,res.ior_RUTAUN,res.ior_RUTBOY,res.ior_RUTBUY,res.ior_RUTBON,res.ior_RUTBUN,res.ior_RUTCOY,res.ior_RUTCUY,res.ior_RUTCON,res.ior_RUTCUN,res.ior_RUTDOY,res.ior_RUTDUY,res.ior_RUTDON,res.ior_RUTDUN,res.sw_RUTA,res.sw_RUTB,res.sw_RUTC,res.sw_RUTD,'all',type,more,'sfqdjq') ; // 单场
        }

    }else{ // 今日赛事,早盘
        str +='';
    }
    $('.bet-jqdxsfqdjq').html(str) ; // 单场

}



// 双重机会 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 双重机会 滚球：全场 ior_DCHN  ior_DCCN ior_DCHC
*  双重机会开关 今日赛事 早盘 全场:sw_DC  , 滚球
* */
function changeShuangCJHData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RDCHN,res.ior_RDCCN,'','','all',type,more,'scjh',res.ior_RDCHC) ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_DC=='Y'){
            str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_DCHN,res.ior_DCCN,'','','all',type,more,'scjh',res.ior_DCHC) ; // 单场
        }else{
            str +='' ;
        }

    }

    $('.bet-scjh').html(str) ; // 单场
}

// 零失球 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 零失球 全场：ior_CSH ior_CSC  ，滚球：
* 零失球 开关 今日赛事 早盘 全场: sw_CS , 滚球
*
* */
function changeLingSQData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RCSH,res.ior_RCSC,'','','all',type,more,'lsq') ; // 单场
    }else{ // 今日赛事,早盘
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_CSH,res.ior_CSC,'','','all',type,more,'lsq') ; // 单场
    }
    $('.bet-lingsq').html(str) ; // 单场
}

// 零失球获胜 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 零失球获胜 全场：ior_WNH ior_WNC  ，滚球：
* 零失球获胜 开关 今日赛事 早盘 全场: sw_WN , 滚球
*
* */
function changeLingSQHSData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RWNH,res.ior_RWNC,'','','all',type,more,'lsqhs') ; // 单场
    }else{ // 今日赛事,早盘
        str +=setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_WNH,res.ior_WNC,'','','all',type,more,'lsqhs') ; // 单场
    }
    $('.bet-lingsqhs').html(str) ; // 单场
}

/* 独赢 & 双方球队进球
*  独赢 & 双方球队进球 开关 今日赛事 全场: sw_MTS ，滚球 sw_RMTS
*   "sw_MTS": "Y",                  //独赢&双方球队进球   开关
    "ior_MTSHY": "2.81",            //独赢&双方球队进球   主队   是    赔率
    "ior_MTSHN": "2.28",            //独赢&双方球队进球   主队   不是   赔率
    "ior_MTSNY": "6.2",             //独赢&双方球队进球   和局   是    赔率
    "ior_MTSNN": "16.0",            //独赢&双方球队进球   和局   不是    赔率
    "ior_MTSCY": "17.5",            //独赢&双方球队进球   客队   是    赔率
    "ior_MTSCN": "18.5",            //独赢&双方球队进球   客队   不是   赔率
* */
function changeDuSFQDJQCData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RMTS=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_RMTSHY,res.ior_RMTSHN,'',res.ior_RMTSNY,res.ior_RMTSNN,'',res.ior_RMTSCY,res.ior_RMTSCN,'','all',type,more,'dysf'); // 单场
        }else{
            str += '' ;
        }

    }else{ // 今日赛事,早盘
        if(res.sw_MTS=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_MTSHY,res.ior_MTSHN,'',res.ior_MTSNY,res.ior_MTSNN,'',res.ior_MTSCY,res.ior_MTSCN,'','all',type,more,'dysf') ; // 单场
        }else{
            str +='';
        }
    }

    $('.bet-dysfqdjq').html(str) ; // 单场

}

// 最多进球的半场 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 最多进球的半场 全场：ior_HGH  ior_HGC  ，滚球：
* 最多进球的半场 开关 今日赛事 早盘 全场: sw_HG , 滚球
*
* */
function changeZDJQBCData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str += setRqhtml(str,res.gid,'上半场', '下半场',res.ior_RHGH,res.ior_RHGC,'','','all',type,more,'zdjqbc') ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_HG=='Y'){
            str += setRqhtml(str,res.gid,'上半场', '下半场',res.ior_HGH,res.ior_HGC,'','','all',type,more,'zdjqbc') ; // 单场
        }else{
            str += '' ;
        }

    }
    $('.bet-zdjqdbc').html(str) ; // 单场
}

// 最多进球的半场 - 独赢 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*  所有玩法 最多进球的半场 - 独赢 滚球：全场 ior_MGH   ior_MGC  ior_MGN
*  最多进球的半场 - 独赢 开关 今日赛事 早盘 全场:sw_MG  , 滚球
* */
function changeZDJQBCDYData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str +=setRqhtml(str,res.gid,'上半场', '下半场',res.ior_RMGH,res.ior_RMGC,'','','all',type,more,'zdjqbcdy',res.ior_RMGN) ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_MG=='Y'){
            str +=setRqhtml(str,res.gid,'上半场', '下半场',res.ior_MGH,res.ior_MGC,'','','all',type,more,'zdjqbcdy',res.ior_MGN) ; // 单场
        }else{
            str +='' ;
        }

    }

    $('.bet-zdjqdbcdy').html(str) ; // 单场
}

// 双半场进球 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 滚球 双半场进球 全场：ior_RSBH  ior_RSBC  ，开关 sw_RSBC
* 双半场进球 开关 今日赛事 ior_SBH  ior_SBC 早盘 全场: 开关 sw_SB , 滚球 开关 sw_RSBH
*
* */
function changeSBCJQData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RSBH,res.ior_RSBC,'','','all',type,more,'sbcjq') ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_SB=='Y'){
            str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_SBH,res.ior_SBC,'','','all',type,more,'sbcjq') ; // 单场
        }else{
            str += '' ;
        }

    }
    $('.bet-sbcjq').html(str) ; // 单场
}

// 双重机会 & 进球 大 / 小 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
* 所有玩法 双重机会 & 进球 大 / 小 ：ior_SBH  ior_SBC  ，滚球：
* 双重机会 & 进球 大 / 小  开关 今日赛事 早盘 全场: 第1行 sw_DUA  第2行 sw_DUB  第3行 sw_DUC  第4行 sw_DUD , 滚球 第1行 sw_RDUA  第2行 sw_RDUB  第3行 sw_RDUC  第4行 sw_RDUD
*
* */
function changeSCJHJQDXData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RDUA =='N' && res.sw_RDUB =='N' && res.sw_RDUC =='N' && res.sw_RDUD =='N'){ // 没有数据
            str += '' ;
        }else{
            str += setSCJHJQDXhtml(str,res.gid,res.team_h, res.team_c,res.ior_RDUAHO,res.ior_RDUACO,res.ior_RDUASO,res.ior_RDUAHU,res.ior_RDUACU,res.ior_RDUASU,res.ior_RDUBHO,res.ior_RDUBCO,res.ior_RDUBSO,res.ior_RDUBHU,res.ior_RDUBCU,res.ior_RDUBSU,res.ior_RDUCHO,res.ior_RDUCCO,res.ior_RDUCSO,res.ior_RDUCHU,res.ior_RDUCCU,res.ior_RDUCSU,res.ior_RDUDHO,res.ior_RDUDCO,res.ior_RDUDSO,res.ior_RDUDHU,res.ior_RDUDCU,res.ior_RDUDSU,res.sw_RDUA,res.sw_RDUB,res.sw_RDUC,res.sw_RDUD,'all',type,more) ; // 单场
        }

    }else{ // 今日赛事,早盘
        if(res.sw_DUA =='N' && res.sw_DUB =='N'&& res.sw_DUC =='N' && res.sw_DUD =='N') { // 没有数据
            str += '' ;
        }else{
            str += setSCJHJQDXhtml(str,res.gid,res.team_h,res.team_c,res.ior_DUAHO,res.ior_DUACO,res.ior_DUASO,res.ior_DUAHU,res.ior_DUACU,res.ior_DUASU,res.ior_DUBHO,res.ior_DUBCO,res.ior_DUBSO,res.ior_DUBHU,res.ior_DUBCU,res.ior_DUBSU,res.ior_DUCHO,res.ior_DUCCO,res.ior_DUCSO,res.ior_DUCHU,res.ior_DUCCU,res.ior_DUCSU,res.ior_DUDHO,res.ior_DUDCO,res.ior_DUDSO,res.ior_DUDHU,res.ior_DUDCU,res.ior_DUDSU,res.sw_DUA,res.sw_DUB,res.sw_DUC,res.sw_DUD,'all',type,more) ; // 单场
        }

    }
    $('.bet-scjhjqdx').html(str) ; // 单场
}

/* 双重机会 & 双方球队进球
*   "sw_DS": "Y",       sw_RDS       //双重机会&双方球队进球    开关
    "ior_DSHY": "2.01",        //双重机会&双方球队进球    主队/和局   是   赔率
    "ior_DSHN": "1.97",        //双重机会&双方球队进球    主队/和局   不是   赔率
    "ior_DSCY": "4.95",        //双重机会&双方球队进球    客队/和局   是   赔率
    "ior_DSCN": "8.8",        //双重机会&双方球队进球     客队/和局   不是   赔率
    "ior_DSSY": "2.42",        //双重机会&双方球队进球     主队/客队  是   赔率
    "ior_DSSN": "1.98",        //双重机会&双方球队进球     主队/客队   不是   赔率
* */
function changeSCJHSFQDJQData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RDS=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_RDSHY,res.ior_RDSHN,'',res.ior_RDSCY,res.ior_RDSCN,'',res.ior_RDSSY,res.ior_RDSSN,'','all',type,more,'sfqdjq'); // 单场
        }else{
            str += '';
        }

    }else{ // 今日赛事,早盘
        if(res.sw_DS=='Y'){
            str += setBanQuanChtml(str,res.gid,res.team_h,res.team_c,res.ior_DSHY,res.ior_DSHN,'',res.ior_DSCY,res.ior_DSCN,'',res.ior_DSSY,res.ior_DSSN,'','all',type,more,'sfqdjq') ; // 单场
        }else{
            str +='';
        }
    }

    $('.bet-scjhsfqdjq').html(str) ; // 单场

}

/*
*  三项让球投注
*   "sw_W3": "Y",              //三项让球投注   开关
    "ratio_w3h": "-1",         //三项让球投注   主队让球   球数
    "ratio_w3c": "+1",         //三项让球投注   客队让球  球数
    "ratio_w3n": "-1",         //三项让球投注   让球和局  让球数
    "ior_W3H": "1.880",        //三项让球投注   主队   赔率
    "ior_W3C": "3.200",        //三项让球投注   客队   赔率
    "ior_W3N": "3.650",        //三项让球投注   让球和局   赔率
* */

function changeSXRQTZData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        if(res.sw_RW3=='Y'){
            str += setRqhtml(str,res.gid,res.team_h,res.team_c,res.ior_RW3H,res.ior_RW3C,'','','all',type,more,'sxrqtz',res.ior_RW3N,res.ratio_rw3h,res.ratio_rw3c,res.ratio_rw3n) ; // 单场
        }else{
            str += '';
        }

    }else{ // 今日赛事,早盘
        if(res.sw_W3=='Y'){
            str += setRqhtml(str,res.gid,res.team_h,res.team_c,res.ior_W3H,res.ior_W3C,'','','all',type,more,'sxrqtz',res.ior_W3N,res.ratio_w3h,res.ratio_w3c,res.ratio_w3n) ; // 单场
        }else{
            str +='';
        }
    }

    $('.bet-sxrqtz').html(str) ; // 单场

}

// 赢得任一半场 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*
* 赢得任一半场 开关 今日赛事 早盘 全场: sw_WE , 滚球
*   "sw_WE": "Y",              //赢得任一半场    开关
    "ior_WEH": "1.15",         //赢得任一半场    主队     赔率
    "ior_WEC": "4.3",          //赢得任一半场    客队     赔率
*
* */
function changeYDRYBCData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RWEH,res.ior_RWEC,'','','all',type,more,'ydrybc') ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_WE=='Y'){
            str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_WEH,res.ior_WEC,'','','all',type,more,'ydrybc') ; // 单场
        }else{
            str += '' ;
        }

    }
    $('.bet-ydrybc').html(str) ; // 单场
}

// 赢得所有半场 数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*
* 赢得所有半场 开关 今日赛事 早盘 全场: sw_WE , 滚球
*   "sw_WB": "Y",              //赢得所有半场    开关
    "ior_WBH": "3.05",         //赢得所有半场    主队      赔率
    "ior_WBC": "71.0",         //赢得所有半场    客队      赔率
*
* */
function changeYDSYBCData(res,more,type) {
    var str ='' ; // 单场
    if(more =='RB'){ // 滚球
        str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_RWBH,res.ior_RWBC,'','','all',type,more,'ydsybc') ; // 单场
    }else{ // 今日赛事,早盘
        if(res.sw_WB=='Y'){
            str += setRqhtml(str,res.gid,res.team_h, res.team_c,res.ior_WBH,res.ior_WBC,'','','all',type,more,'ydsybc') ; // 单场
        }else{
            str += '' ;
        }

    }
    $('.bet-ydsybc').html(str) ; // 单场
}


// 球队得分: - 最后一位数  数据处理 , res 数据,more 是滚球还是今日赛事,type 足球(FT)还是篮球(BK)
/*
*
* 球队得分: - 最后一位数 开关 今日赛事 早盘 全场: sw_WE , 滚球
*
* */
function changeQDDFZHYWSData(res,more,type) {
    var str ='' ; // 主队
    var tg_str ='' ; // 客队
    if(more =='RB'){ // 滚球
        str += setQDDFZHYWShtml(str,res.gid,res.team_h,res.team_c,res.ior_RPDH0,res.ior_RPDH1,res.ior_RPDH2,res.ior_RPDH3,res.ior_RPDH4,'all',type,more) ; // 主队
        tg_str += setQDDFZHYWShtml(str,res.gid,res.team_h,res.team_c,res.ior_RPDC0,res.ior_RPDC1,res.ior_RPDC2,res.ior_RPDC3,res.ior_RPDC4,'half',type,more) ; // 客队
    }else{ // 今日赛事,早盘
        str += setQDDFZHYWShtml(str,res.gid,res.team_h,res.team_c,res.ior_PDH0,res.ior_PDH1,res.ior_PDH2,res.ior_PDH3,res.ior_PDH4,'all',type,more) ; // 主队
        tg_str += setQDDFZHYWShtml(str,res.gid,res.team_h,res.team_c,res.ior_PDC0,res.ior_PDC1,res.ior_PDC2,res.ior_PDC3,res.ior_PDC4,'half',type,more) ; // 客队

    }
    $('.bet-mbqddfzhyws').html(str) ; // 主队
    $('.bet-tgqddfzhyws').html(tg_str) ; // 客队
}

// 篮球 第一到第四节比分处理，字段：se_now 当前进行到第几节 Q1 Q2 Q3 Q4
function changeBKScoreData(se_now,sc_Q1_H,sc_Q1_A,sc_Q2_H,sc_Q2_A,sc_Q3_H,sc_Q3_A,sc_Q4_H,sc_Q4_A,sc_H1_H,sc_H1_A,sc_H2_H,sc_H2_A,sc_OT_H,sc_OT_A) {
    // 篮球 第1 - 第4 节比分 ,篮球 主队 sc_FT_H ，客队 sc_FT_A, 第一节 主队 sc_Q1_H 客队 sc_Q1_A，第二节 主队 sc_Q2_H 客队 sc_Q2_A，第三节 主队 sc_Q3_H 客队 sc_Q3_A，第四节 主队 sc_Q4_H 客队 sc_Q4_A，
    // 上半场：主队 sc_H1_H  客队 sc_H1_A ，下半场：主队 sc_H2_H  客队 sc_H2_A ，加时比分：主队 sc_OT_H  客队 sc_OT_A

    var bk_str = '<td id="score_data" class="bk_score">' +
        '<div id="title_Q1" class="score_name " >Q1<span id="score_Q1" class=" '+ (se_now=='Q1'?'score2_light':'score2_h') +'">('+ (sc_Q1_H?sc_Q1_H:0) +'-'+ (sc_Q1_A?sc_Q1_A:0) +')</span></div>' +
        '<div id="title_Q2" class="score_name '+ returnBkQNumClass(se_now,'Q2') +'" >Q2<span id="score_Q2" class=" '+ (se_now=='Q2'?'score2_light':'score2_h') +'">('+ (sc_Q2_H?sc_Q2_H:0) +'-'+ (sc_Q2_A?sc_Q2_A:0) +')</span></div>' +
        '<div id="title_Q3" class="score_name '+ returnBkQNumClass(se_now,'Q3') +'" >Q3<span id="score_Q3" class=" '+ (se_now=='Q3'?'score2_light':'score2_h') +'">('+ (sc_Q3_H?sc_Q3_H:0) +'-'+ (sc_Q3_A?sc_Q3_A:0) +')</span></div>' +
        '<div id="title_Q4" class="score_name '+ returnBkQNumClass(se_now,'Q4') +'">Q4<span id="score_Q4" class=" '+ (se_now=='Q4'?'score2_light':'score2_h') +'">('+ (sc_Q4_H?sc_Q4_H:0) +'-'+ (sc_Q4_A?sc_Q4_A:0) +')</span></div>' +
        '<div id="title_H1" class="score_name " >上半场<span id="score_H1" class="'+ returnBkClass(se_now,'half_1') +'">('+ (sc_H1_H?sc_H1_H:0) +'-'+ (sc_H1_A?sc_H1_A:0) +')</span></div>' +
        '<div id="title_H2" class="score_name '+ returnBkQNumClass(se_now,'half_2') +'" >下半场<span id="score_H2" class="'+ returnBkClass(se_now,'half_2') +'">('+ (sc_H2_H?sc_H2_H:0) +'-'+ (sc_H2_A?sc_H2_A:0) +')</span></div>' +
        '<div id="title_OT" class="score_name '+ returnBoDanCloseGame(sc_OT_H,sc_OT_A,'') +'" >加时<span id="score_OT" class="score2_h">('+ (sc_OT_H?sc_OT_H:0) +'-'+ (sc_OT_A?sc_OT_A:0) +')</span></div>' +
        '</td>' ;

    $('.add_bk_score').html(bk_str) ;
}
// 篮球滚球增加类 hide-cont ,se_now 当前第几节, qnum : Q1 Q2 Q3 Q4
function returnBkQNumClass(se_now,qnum) {
    var str_clas = '' ;
    switch (qnum){
        case 'Q2': // 第二节
            if(se_now=='Q1' ){
                str_clas = 'hide-cont' ;
            }
            break;
        case 'Q3': // 第三节
            if(se_now=='Q1' || se_now=='Q2'){
                str_clas = 'hide-cont' ;
            }
            break;
        case 'Q4': // 第四节
            if(se_now=='Q1' || se_now=='Q2' || se_now=='Q3'){
                str_clas = 'hide-cont' ;
            }
            break;
        case 'half_2': // 下半场
            if(se_now=='Q1' || se_now=='Q2'){
                str_clas = 'hide-cont' ;
            }
            break;
    }

    return str_clas ;
}

// 篮球滚球增加类 ,se_now 当前第几节,上半场 half_1 ，下半场 half_2
function returnBkClass(se_now,half) {
    var tipclass  ;
    if(half=='half_1'){ // 上半场
        if(se_now=='Q1' || se_now=='Q2'){
            tipclass ='score2_light' ;
        }else{
            tipclass ='score2_h' ;
        }
    }else if(half=='half_2'){ // 下半场
        if(se_now=='Q3' || se_now=='Q4'){
            tipclass ='score2_light' ;
        }else{
            tipclass ='score2_h' ;
        }
    }
    return tipclass ;

}

/*
* new_cate.php  非冠军数据结构
* res 数据
* gametype : p3 综合过关，other 其他全部
* */

function changeNotFsTypeData(type,more,twotype,typecase,active_type,showtype,res,gametype) {
    $('#lea_title_gtype').html(res.data[0].league) ; // 联赛标题
    var str ='' ;
    var show_rb_class='' ;
    var p3_class = '' ;
    var tip_type = '' ;
    var p3h = '' ;
    var p3c = '' ;
    var p3uh = '' ;
    var p3uc = '' ;
    var tiptype = '' ;
    var p3_league = '' ;
    if(gametype=='p3') { // 综合过关
        p3_class = 'hide-cont' ;
        tip_type = 'p3' ; // 综合过关
        p3h = 'PRH' ;
        p3c = 'PRC' ;
        p3uh = 'POUH' ;
        p3uc = 'POUC' ;
        tiptype = 'p3' ;
        p3_league = res.data[0].league ;
    }
    for(var i=0;i<res.data.length;i++){

        str +='<table border="0" cellspacing="0" cellpadding="0" class="game_tab">' +
            '<tbody>' +
            '<tr class="oddstitle">' +
            '<td class="odds_bg">' +
            '<div class="odds_score" data-mtype="'+ (gametype=='other'?res.data[i].M_Type:'') + '">';
        if(gametype=='other'){ // 综合过关没有
            if( res.data[i].M_Type=='1'){ // 滚球中字样
                str +='<span class="odds_live"> 滚球 </span><span class="mini_tv"></span>';
            }else if(res.data[i].score_h !=''){ // 滚球: 有比分,目前接口没有返回
                str +='<strong> '+ res.data[i].score_h +':'+ res.data[i].score_c +'</strong>';
            }
        }

        str += '</div>';
        str += '<div class="odds_re_time">' ;
        if(type=='FU'|| type=='BU'){ // 早盘需要显示日期
            str += '<span class="odds_early_date">'+ res.data[i].M_Date +'</span>' ;
        }

        if(res.data[i].score_h !=''){ // 滚球
            try {
                if(gametype=='p3'){ // 综合过关
                    var timearr = res.data[i].datetime.split(' ') ;
                }else{
                    var timearr = res.data[i].showretime.split(' ') ;
                }
            }catch (e) {
                var timearr = '' ;
            }
            if(!timearr[1]){ // 防止为 undefined
                timearr[1] = '' ;
            }
            str += '<span class="odds_re_min">'+ timearr[0] +'</span><span class="odds_re_clock">'+ timearr[1] +'</span>' ;
            show_rb_class = 'rb_icon_N';
        }else{
            str += '<span class="odds_min">'+ (res.data[i].M_Time) +'</span>' ;
        }

        str += '<div id="icon_N" class="'+p3_class+' icon_N  '+show_rb_class+'" ><div class="odds_mid"> </div></div>' +
            '      </div>' +
            '    </td>' +
            '    <td class="ratio_headA"><div class="headA_line">让球</div></td>' +
            '    <td class="ratio_headB">大 / 小</td>' +
            '</tr>' +
            '<tr>' +
            '    <td colspan="3" class="gameplay" style="display:none;"> *GAMEPLAY* </td>' + // 角球数
            '</tr>' +
            '<tr class="oddsdetails td_first">' +
            '    <td class="team_box">' +
            '        <div name="dynamicFont" class="team_name"><span>'+ res.data[i].team_h +' </span></div> <span class="'+ p3_class +' redcard_show '+(res.data[i].redcard_h==''?'hide-cont':'')+'"><span class="redcard">'+res.data[i].redcard_h+'</span></span>' +
            '    <td id="h_rh_'+ res.data[i].gid +'" class="odds_box bet_action_p3_'+res.data[i].gid+' bet_p3_'+p3h+'_'+res.data[i].gid+'" data-gid="'+ res.data[i].gid +'" data-type="H" data-rtype="'+ p3h +'" data-wtype="'+(gametype=='p3'?'P3':'R')+'" data-method="'+returnMethod(twotype,more,'e')+'" data-case="'+typecase+'" data-active="'+active_type+'" data-tiptype="'+tip_type+'" data-teamh="'+ res.data[i].team_h +'" >' +
            '        <div class="ratio_box '+ (gametype=='p3'?(res.data[i].ior_PRH==''?'close_thegame':''):(res.data[i].ior_RH ==''?'close_thegame':'')) +'">' + // 无赔率 ：close_thegame
            '            <div class="con">'+ res.data[i].ratio_mb_str +'</div>' + /* 主队让球数 */
            '            <div ><font class="ratio">'+ (gametype=='p3'?res.data[i].ior_PRH:res.data[i].ior_RH) +'</font></div>' +
            '        </div>' +
            '    </td>' +
            '    <td id="c_ouc_'+ res.data[i].gid +'" class="odds_box bet_action_p3_'+res.data[i].gid+' bet_p3_'+p3uc+'_'+res.data[i].gid+'" data-gid="' + res.data[i].gid +'" data-type="C" data-rtype="'+ p3uc +'" data-wtype="'+(gametype=='p3'?'P3':'OU')+'" data-method="'+returnMethod(twotype,more,'ou')+'" data-case="'+typecase+'" data-active="'+active_type+'" data-tiptype="'+tip_type+'" data-teamh="'+ res.data[i].team_h +'" >' +
            '        <div class="ratio_box ' + (gametype=='p3'?(res.data[i].ior_POUC==''?'close_thegame':''):(res.data[i].ior_OUC ==''?'close_thegame':'')) + '">' +
            '            <div class="con"><span class="con_ou">'+ res.data[i].ratio_o_str +'</span></div>' + /* 主队 大 */
            '            <div ><font class="ratio">'+ (gametype=='p3'?res.data[i].ior_POUC:res.data[i].ior_OUC) +'</font></div>' +
            '        </div>' +
            '    </td>' +
            '</tr>' +
            '<tr class="oddsdetails">' +
            '    <td class="team_box">' +
            '        <div name="dynamicFont" class="team_name"><span>'+ res.data[i].team_c +' </span></div> <span class="'+ p3_class +' redcard_show '+(res.data[i].redcard_c==''?'hide-cont':'')+'"><span class="redcard">'+res.data[i].redcard_c+'</span></span>' +
            '    </td>' +
            '    <td id="c_rc_'+ res.data[i].gid +'" class="odds_box bet_action_p3_'+res.data[i].gid+' bet_p3_'+p3c+'_'+res.data[i].gid+'" data-gid="' + res.data[i].gid +'" data-type="C" data-rtype="'+ p3c +'" data-wtype="'+(gametype=='p3'?'P3':'R')+'" data-method="'+returnMethod(twotype,more,'e')+'" data-case="'+typecase+'" data-active="'+active_type+'" data-tiptype="'+tip_type+'" data-teamh="'+ res.data[i].team_h +'" >' +
            '        <div class="ratio_box '+ (gametype=='p3'?(res.data[i].ior_PRC==''?'close_thegame':''):(res.data[i].ior_RC ==''?'close_thegame':'')) +'">' +
            '            <div class="con">'+ res.data[i].ratio_tg_str +'</div>' + /* 客队让球数 */
            '            <div ><font class="ratio">'+ (gametype=='p3'?res.data[i].ior_PRC:res.data[i].ior_RC) +'</font></div>' +
            '        </div>' +
            '    </td>' +
            '    <td id="h_ouh_'+ res.data[i].gid +'" class="odds_box bet_action_p3_'+res.data[i].gid+' bet_p3_'+p3uh+'_'+res.data[i].gid+'" data-gid="' + res.data[i].gid +'" data-type="H" data-rtype="'+ p3uh +'" data-wtype="'+(gametype=='p3'?'P3':'OU')+'" data-method="'+returnMethod(twotype,more,'ou')+'" data-case="'+typecase+'" data-active="'+active_type+'" data-tiptype="'+tip_type+'" data-teamh="'+ res.data[i].team_h +'" >' +
            '        <div class="ratio_box '+ (gametype=='p3'?(res.data[i].ior_POUH==''?'close_thegame':''):(res.data[i].ior_OUH ==''?'close_thegame':'')) +'">' +
            '            <div class="con"><span class="con_ou">'+ res.data[i].ratio_u_str +'</span></div>' +
            '            <div ><font class="ratio">'+ (gametype=='p3'?res.data[i].ior_POUH:res.data[i].ior_OUH) +'</font></div>' +
            '        </div>' +
            '    </td>' +
            '</tr>' +
            '<tr class="">' + /* 所有玩法按钮 */
            '    <td colspan="3">' +
            '        <div id="more_'+ res.data[i].gid +'" class="bet" onclick="window.location.href=\'more_cate.php?tiptype='+tiptype+'&M_League='+p3_league+'&mtype='+res.data[i].M_Type+'&gid='+ res.data[i].gid +'&gtype='+ twotype +'&showtype='+ showtype +'\'">' +
            '            <span style="display:none" class="all_tv"></span><span>'+ (gametype=='p3'?'所有':res.data[i].all==0?'所有':res.data[i].all) +'</span> 玩法' +
            '            <div class="list_arr_cen"> </div>' +
            '        </div>' +
            '    </td>' +
            '</tr>' +
            '</tbody>' +
            '</table>' ;
    }

    $('#sport_div_show').html(str) ;
}

/*
*  new_cate.php 冠军数据结构
* */
function changeFsTypeData(type,more,twotype,typecase,active_type,showtype,res) {
    $('#lea_title_gtype').html(res.data[0].M_League) ; // 联赛标题
    var str ='' ;
    var spegame = 'special' ; // 需要减去本金
    for(var i=0;i<res.data.length;i++){
        str += '<div  class="expand_action oddstitle_outrights fs_arrow">' +
            '<span class="'+ (i==0?'arrow_close':'arrow_open') +'">'+ res.data[i].teamsname +'</span>' +
            '</div>' ;
        str += '<div class="expand_body" style="display: '+ (i==0?'block':'none') +'">' ;
        for(var j=0;j<res.data[i].item.length;j++){
            str +=  '<div class="bet_action oddsdetails" data-gid="' + res.data[i].gid +'"  data-rtype="'+ res.data[i].item[j].rtype +'" data-wtype="FS" data-method="FT_nfs" data-case="'+typecase+'" data-spegame ="'+spegame+'" ;data-active="'+active_type+'">' +
                '   <div class="team_box_fs">' +
                '        <div class="team_name_fs"><span>'+ res.data[i].item[j].team_name_fs +' </span></div>' +
                '   </div>' +
                '   <div class="odds_box_todayoutright">' +
                '         <div class="odd_box"><font class="ratio_red">'+ changeTwoDecimal(res.data[i].item[j].ratio) +'</font></div>' +
                '   </div>' +
                '</div>';
        }
        str += '</div>' ;
    }
    $('#sport_div_show').html(str) ;

}


