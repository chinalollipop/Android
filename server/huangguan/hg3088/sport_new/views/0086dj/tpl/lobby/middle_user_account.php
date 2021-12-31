<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$Alias=$_SESSION['Alias'];
$birthday=$_SESSION['birthday'];

$todaydate=date('Y-m-d'); // 当天日期
$yesday = date("Y-m-d",strtotime("-1 day"));
$yesday_15 = date("Y-m-d",strtotime("-14 day"));

//  单页面维护功能

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="memberWrap">
    <div class=" memberTit clearfix">
        <span class="to_usercenter account_icon fl titImg account_nav"></span>
    </div>
    <div class="payWay">
        <div class="static-content-user">
            <!-- 投注记录 -->
            <div class="account_bet_content">
                <div class="title" title="点击收起">账户投注记录</div>
                <div class="account_content_middle">
                    <div class="time_content">
                        <div class="time_li"><span class="tip">开始日期</span><span class="date"> <input type="text" class="begin_time_date fir_begin_time_date" readonly/></span></div>
                        <div class="time_li"><span class="tip">结束日期</span><span class="date"> <input type="text" class="end_time_date fir_end_time_date" readonly/></span></div>
                        <div class="time_li"><a href="javascript:;" class="bet_seach_btn" data-type="bet"> 查询 </a></div>
                    </div>
                    <div class="choose_date">
                        <ul>
                            <li class="active" data-begin="<?php echo $todaydate;?>" data-end="<?php echo $todaydate;?>">今日</li>
                            <li data-begin="<?php echo $yesday;?>" data-end="<?php echo $yesday;?>">昨天</li>
                            <li data-begin="<?php echo $yesday_15;?>" data-end="<?php echo $todaydate;?>">过去15天</li>
                        </ul>
                        <div class="choose_type">
                            类型&nbsp;
                            <select>
                                <option value="FT"> 体育足球 </option>
                                <option value="BK"> 体育篮球 </option>
                                <option value="FS"> 冠军 </option>
                                <option value="lottery"> 彩票 </option>
                                <option value="aglive"> AG视讯 </option>
                                <option value="aggame"> AG电子 </option>
                                <option value="agby"> AG捕鱼 </option>
                                <option value="kyqp"> 开元棋牌 </option>
                                <option value="lyqp"> 乐游棋牌 </option>
                                <option value="vgqp"> VG棋牌 </option>
                               <!-- <option value="hgqp"> 皇冠棋牌 </option>-->
                                <option value="klqp"> 快乐棋牌 </option>
                                <option value="mgdz"> MG电子 </option>
                                <option value="avia"> 泛亚电竞 </option>
                                <option value="fire"> 雷火电竞 </option>
                                <option value="oglive"> OG视讯 </option>
                                <option value="bbinlive"> BBIN视讯 </option>
                                <option value="cq9dz"> CQ9电子 </option>
                                <option value="mwdz"> MW电子 </option>
                                <option value="fgdz"> FG电子 </option>

                            </select>
                        </div>
                    </div>
                    <!-- // 是否取消 , Y  无效注单 N 有效注单 -->
                    <div class="choose_status">
                        <a href="javascript:;" class="active" data-check="Y" data-cancel="N"> 已结算注单 </a>
                        <a href="javascript:;" class="" data-check="N" data-cancel="N"> 未结算注单 </a>
                        <a href="javascript:;" class="" data-check="" data-cancel="Y"> 已取消注单 </a>
                        <span class="total_report">
                            <dl>总投注额：<dt class="total_report_bet text-red">0</dt> </dl>
                            <dl>有效投注额：<dt class="total_report_yxtz text-red">0</dt> </dl>
                            <dl>总输赢：<dt class="total_report_win text-red">0.00</dt> </dl>
                        </span>
                    </div>
                    <table class="detail_table">
                        <thead>
                            <tr class="st-hdr">
                                <th>编号</th>
                                <th>投注时间</th>
                                <th style="width: 260px;">投注详情</th>
                                <th>投注玩法</th>
                                <th>投注额</th>
                                <th>赢 / 输</th>
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody class="bet_record_content">
                            <tr class="no-record">
                                <td class="tac" colspan="7">
                                    <div class="empty-container">找不到相关记录</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 页码 -->
                    <div class="page_get_record pagination betrecord_page" data-type="bet">

                    </div>
                </div>
            </div>

            <!-- 交易记录 -->
            <div class="account_bet_content">
                <div class="title" title="点击收起">账户交易记录</div>
                <div class="account_content_middle">
                    <div class="time_content">
                        <div class="time_li"><span class="tip">开始日期</span><span class="date"> <input type="text" class="begin_time_date sec_begin_time_date" readonly/></span></div>
                        <div class="time_li"><span class="tip">结束日期</span><span class="date"> <input type="text" class="end_time_date sec_end_time_date" readonly/></span></div>
                        <div class="time_li"><a href="javascript:;" class="bet_seach_btn" data-type="deal"> 查询 </a></div>
                    </div>
                    <div class="choose_date">
                        <ul>
                            <li class="active" data-begin="<?php echo $todaydate;?>" data-end="<?php echo $todaydate;?>">今日</li>
                            <li data-begin="<?php echo $yesday;?>" data-end="<?php echo $yesday;?>">昨天</li>
                            <li data-begin="<?php echo $yesday_15;?>" data-end="<?php echo $todaydate;?>">过去15天</li>
                        </ul>

                    </div>
                    <div class="choose_status deal_choose_status">
                        <a href="javascript:;" class="active" data-check="S"> 存款 </a>
                        <a href="javascript:;" data-check="T"> 提款 </a>
                        <a href="javascript:;" data-check="Q"> 转账 </a>
                        <a href="javascript:;" data-check="R"> 返水 </a>
                    </div>
                    <table class="detail_table">
                        <thead>
                        <tr class="st-hdr">
                            <th>编号</th>
                            <th>提交时间</th>
                            <th>项目</th>
                            <th>金额</th>
                            <th>状态</th>
                            <th>备注</th>
                        </tr>
                        </thead>
                        <tbody class="deal_record_content">
                        <tr class="no-record">
                            <td class="tac" colspan="6">
                                <div class="empty-container">找不到相关记录</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- 页码 -->
                    <div class="page_get_record pagination dealrecord_page" data-type="deal">

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">


    $(function () {

        laydate.render({
            elem: '.fir_begin_time_date',
            defaultValue:setAmerTime('.fir_begin_time_date','day'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });
        laydate.render({
            elem: '.fir_end_time_date',
            defaultValue:setAmerTime('.fir_end_time_date','day'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });

        laydate.render({
            elem: '.sec_begin_time_date',
            defaultValue:setAmerTime('.sec_begin_time_date','day'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });
        laydate.render({
            elem: '.sec_end_time_date',
            defaultValue:setAmerTime('.sec_end_time_date','day'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });

        // 标题收起
        function titleToggle() {
            $('.account_bet_content .title').on('click',function () {
                $(this).next('.account_content_middle').toggle();
            })
        }

        function chooseClickAction() {
            $('.choose_date li').on('click',function () {  // 时间选择
                var start_time = $(this).attr('data-begin');
                var end_time = $(this).attr('data-end');
                var $this_btime = $(this).parents('.account_content_middle').find('.begin_time_date') ;
                var $this_etime = $(this).parents('.account_content_middle').find('.end_time_date') ;
                $this_btime.val(start_time);
                $this_etime.val(end_time);
                $(this).addClass('active').siblings().removeClass('active') ;
                $(this).parents('.account_content_middle').find('.bet_seach_btn').click() ;// 触发查询

            })

            $('.choose_status').on('click','a',function () {  // 注单状态选择
                $(this).addClass('active').siblings().removeClass('active');
                $(this).parents('.account_content_middle').find('.bet_seach_btn').click() ;// 触发查询

            })

            $('.choose_type select').on('change',function () {  // 类型选择
                $(this).parents('.account_content_middle').find('.bet_seach_btn').click() ;// 触发查询

            })

        }

        // 查询注单
        function seachBetRecord() {
            $('.bet_seach_btn').on('click',function () {
                var $this_parents = $(this).parents('.account_content_middle') ;
                var type = $this_parents.find('.choose_type select').find('option:selected').val();
                var tip = $this_parents.find('.choose_status').find('.active').attr('data-check');
                var status = $this_parents.find('.choose_status').find('.active').attr('data-cancel');
                var begintime = $this_parents.find('.begin_time_date').val()+' 00:00:00';
                var endtime = $this_parents.find('.end_time_date').val()+' 23:59:59';
                var seachtype = $(this).attr('data-type');
               // console.log(type);
                if(seachtype == 'bet'){ // 投注记录
                    getSportRecord(0,type,tip,status,begintime,endtime);
                }else{ // 交易记录
                    getDepositRecord(0,tip,'ALL',begintime,endtime) ;
                }

            })

        }

        // 注单页码切换
        function betRecordChangePage() {
            $('.page_get_record').on('click','.swShowPage',function () {
                var seachtype = $(this).parents('.page_get_record').attr('data-type');
                var $this_parents = $(this).parents('.account_content_middle') ;
                var type = $this_parents.find('.choose_type select').find('option:selected').val();
                var tip = $this_parents.find('.choose_status').find('.active').attr('data-check');
                var status = $this_parents.find('.choose_status').find('.active').attr('data-cancel');
                var begintime = $this_parents.find('.begin_time_date').val()+' 00:00:00';
                var endtime = $this_parents.find('.end_time_date').val()+' 23:59:59';
                var thispage = Number($(this).attr('topage'))-1 ;
                if(seachtype == 'bet'){ // 投注记录
                    getSportRecord(thispage,type,tip,status,begintime,endtime) ;
                }else{ // 交易记录
                    getDepositRecord(thispage,tip,'ALL',begintime,endtime) ;
                }

            })
        }

        function returnNotRecord(num) { // 返回注单为空
            var nodata ='<tr class="no-record">'+
                '<td class="tac" colspan="'+ num +'">'+
                '<div class="empty-container">找不到相关记录</div>'+
                '</td>'+
                '</tr>' ;
            return nodata ;
        }
        function returnPage(page,pagecount) { // 返回页码
            var pagestr = '' ;
            for(var j=0;j<pagecount;j++){ // 分页
                pagestr += ' <a href="javascript:void(0)" class="swShowPage '+ (page==j?'active':'') +'" topage="'+ (j+1) +'"> '+ (j+1) +' </a>';
            }
            return pagestr ;
        }
        /*
        * 查询投注记录
        * 获取数据接口 tip: N 未结注单 Y 已结注单
        * */

        var submitflag = false ; // 防止重复提交
        function getSportRecord(page,type,tip,status,begintime,endtime) {
            if(submitflag){
                return false ;
            }

            var datapars ={
                gtype :type , // 篮球 BK ，足球 FT
                Checked :tip , // 是否结算 ，N 未结注单 Y 已结注单
                Cancel :status , // 是否取消 , Y  无效注单 N 有效注单
                date_start :begintime ,
                date_end :endtime ,
                page : page , // 页面
            } ;
            var ajaxurl = '/app/member/api/historyWagersApi.php' ; // 足球,篮球
            switch (type){
                case 'lottery': // 彩票
                    ajaxurl = '/app/member/api/historyLotteryApi.php';
                    break;
                case 'aglive': // AG 视讯
                case 'aggame': // AG 电子
                case 'agby': // AG 捕鱼
                    ajaxurl = '/app/member/api/historyAgGameApi.php';
                    break;
                case 'kyqp': // 开元棋牌
                case 'lyqp': // 乐游棋牌
                case 'vgqp': // vg棋牌
                case 'klqp': // 快乐棋牌
                case 'hgqp': // hg棋牌
                case 'mgdz': // mg电子
                case 'avia': // 泛亚电竞
                case 'fire': // 雷火电竞
                case 'oglive': // OG视讯
                case 'bbinlive': // BBIN视讯
                case 'cq9dz': // CQ9电子
                case 'mwdz': // MW电子
                case 'fgdz': // FG电子
                    ajaxurl = '/app/member/api/historyChessApi.php';
                    break;

            }

            var $recordslist = $('.bet_record_content') ; // 内容
            var $betpage = $('.betrecord_page') ; // 页码
            var $total_report_bet = $('.total_report_bet') ; // 总投注额
            var $total_report_yxtz = $('.total_report_yxtz') ; // 有效投注额
            var $total_report_win = $('.total_report_win') ; // 总输赢

            submitflag = true ;
            $.ajax({
                url: ajaxurl ,
                type: 'POST',
                dataType: 'json',
                data: datapars ,
                success: function (res) {
                    if(res.status==200) { // 有数据返回

                        if(res.data.total==0){
                            submitflag = false ;
                            $betpage.hide();
                            $recordslist.html(returnNotRecord(7)) ;
                            $total_report_bet.html('0') ;
                            $total_report_yxtz.html('0') ;
                            $total_report_win.html('0.00') ;
                        }else { // 有数据
                            submitflag = false ;
                            // var pagestr = '' ;
                            // for(var j=0;j<res.data.page_count;j++){ // 分页
                            //     pagestr += ' <a href="javascript:void(0)" class="swShowPage '+ (page==j?'active':'') +'" topage="'+ (j+1) +'"> '+ (j+1) +' </a>';
                            // }
                            if(page == 0){
                                $total_report_bet.html(res.data.betscore_all) ;
                                $total_report_yxtz.html(res.data.betscore_all_yx) ;
                                $total_report_win.html(res.data.m_result_all) ;
                            }
                            $betpage.show();
                            $betpage.html(returnPage(page,res.data.page_count)) ;

                            var str = '' ;
                            var text_color;
                            var text_tip;
                            var p3_result = '' ;
                            for(var i=0;i<res.data.rows.length;i++){

                                if(Number(res.data.rows[i].M_Result) > 0){ // 赢
                                    text_tip = '赢' ;
                                    text_color = 'text-red' ;
                                }else if(Number(res.data.rows[i].M_Result) < 0){ // 输
                                    text_tip = '输' ;
                                    text_color = 'text-lose' ;
                                }else if(Number(res.data.rows[i].M_Result) == 0){ // 和局
                                    text_color = 'text-blue' ;
                                    if(type =='lottery' || type =='fire'){  // 彩票 ( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
                                        if(res.data.rows[i].cancel=='1'){
                                            text_tip = '已取消' ;
                                        }else if(res.data.rows[i].count=='0'){
                                            text_tip = '未结算' ;
                                        }else {
                                            text_tip = '和局' ;
                                        }

                                    }else{
                                        text_tip = '和局' ;
                                    }

                                }
                               // var BetScore = parseInt( res.data.rows[i].BetScore);
                                var BetScore = res.data.rows[i].BetScore;
                                // BetScore = BetScore.toFixed(2);
                                var M_Result = parseFloat( res.data.rows[i].M_Result);
                                M_Result = M_Result.toFixed(2);
                                str += '<tr class="wagers">' +
                                    '<td ><p class="order_no">' + res.data.rows[i].orderNo +'</p></td>'+
                                    '<td> <p>'+ res.data.rows[i].BetTime +'</p></td>';
                                str += '<td class="bet_content">';
                                if(type =='FT' || type =='BK'){ // 体育足球 篮球
                                    if(res.data.rows[i].Middle.length > 0){ // 综合过关
                                        for(var j=0;j<res.data.rows[i].Middle.length;j++){
                                            str += '<div class="bet_content_detail"><p>'+res.data.rows[i].Middle[j].M_League+'</p>'+
                                                '<p>'+ res.data.rows[i].Middle[j].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ res.data.rows[i].Middle[j].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ res.data.rows[i].Middle[j].vs_team_name2 +'</p>'+
                                                '<p><span class="text-blue">'+ res.data.rows[i].Middle[j].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ res.data.rows[i].Middle[j].bet_content +'</span> @ <span class="red_color">'+ res.data.rows[i].Middle[j].bet_rate +'</span></p></div>';
                                            p3_result +='<p style="color:#ff0000"> '+ res.data.rows[i].Middle[j].font_a +'</p>' ;
                                        }
                                    }else{ // 非综合过关 ,res.data.rows[i].font_a 全场比分，res.data.rows[i].corner_num 投注时比分

                                        str += '<div class="bet_content_detail"><p>'+res.data.rows[i].M_League+'</p>'+
                                            '<p>'+ res.data.rows[i].Title +'</p>'+
                                            '<p>'+ res.data.rows[i].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ res.data.rows[i].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ res.data.rows[i].vs_team_name2 +'&nbsp;&nbsp;<span class="red_color">'+res.data.rows[i].corner_num+'</span></p>'+
                                            '<p><span class="text-blue">'+ res.data.rows[i].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ res.data.rows[i].bet_content +'</span> @ <span class="red_color">'+ res.data.rows[i].bet_rate +'</span></p>' +
                                            '<p><strong style="color:green">'+ res.data.rows[i].isDanger +'</strong></p>'
                                        '</div>';

                                    }
                                }else{
                                    str += res.data.rows[i].betContent ;

                                }

                                str += '</td>';
                                str +='<td>'+ res.data.rows[i].Title +'</td>';
                                str +=  '<td class="bet_gold"><p>'+BetScore+'</p> </td>' ;

                                if(M_Result && M_Result !='NaN'){ // 帐户历史
                                    str +=  '<td class="game_result">'+M_Result+'</td>' ;
                                    if(res.data.rows[i].zt){ // 异常注单
                                        str += '<td class="'+text_color+'"><p><font color=#cc0000><b>'+ res.data.rows[i].zt +'</b></font></p> </td>' ;
                                    }else{
                                        str += '<td class="'+text_color+'">';
                                        if(res.data.rows[i].Middle.length > 0) { // 综合过关
                                            str += p3_result ;
                                        }else{ // 非综合过关
                                            str += '<p style="color:#ff0000">'+ res.data.rows[i].font_a  +'</p>' ;

                                        }
                                        str += '<p>'+ text_tip +'</p> </td>' ;

                                    }

                                }else{
                                    str +=  '<td > </td>' ;
                                    str += '<td > </td>';
                                }
                                str += '</tr>';
                            }

                            $recordslist.html(str) ;

                        }

                    }



                },
                error: function (msg) {
                    submitflag = false ;
                    layer.msg('网络异常',{time:alertTime});
                }
            });
        }

        /*
        *  查询交易记录
        * */
        function getDepositRecord(page,type,status,begintime,endtime) {

            if(submitflag){
                return false ;
            }

            var ajaxurl = '/app/member/api/dealRecordApi.php' ;
            var $recordslist = $('.deal_record_content') ; // 内容
            var $betpage = $('.dealrecord_page') ; // 页码

            submitflag = true ;
            $.ajax({
                url: ajaxurl ,
                type: 'POST',
                dataType: 'json',
                data: {
                    thistype: type ,  // type S 存款，T 提款
                    page:page ,
                    type_status:status , // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败 ,ALL 全部
                    date_start:begintime , // 开始时间
                    date_end:endtime , // 结束时间
                },
                success: function (res) {
                    if(res.status==200){ // 有数据返回

                        if(res.data.total==0){
                            submitflag = false ;
                            $betpage.hide();
                            $recordslist.html(returnNotRecord(6)) ;
                        }else{ // 有数据
                            submitflag = false ;
                            $betpage.show();
                            $betpage.html(returnPage(page,res.data.page_count)) ;

                            var str = '' ;
                            for(var i=0;i<res.data.rows.length;i++){
                                str += '<tr>' +
                                    '<td>'+ res.data.rows[i].Order_code +'</td>' +
                                    ' <td>'+ res.data.rows[i].Date +'</td>' ;
                                if(res.data.rows[i].Type=='T'){
                                    str += '<td>提款</td>' ;
                                }else if(res.data.rows[i].Type=='S'){
                                    if(res.data.rows[i].notes=='APP幸运红包活动'){
                                        str += '<td>红包</td>' ;
                                    }else{
                                        str += '<td>存款</td>' ;
                                    }
                                }else if(res.data.rows[i].Type=='R'){ // 返水
                                    str += '<td>返水</td>' ;
                                }else{ // 转账记录
                                    str += '<td>'+ returnChangeType(res.data.rows[i].From) +'</br><font color="red">转入</font></br>'+ returnChangeType(res.data.rows[i].To) +'</td>' ;
                                }
                                str += '<td>'+ res.data.rows[i].Gold +'</td>' ;
                                if(res.data.rows[i].Checked == 0 || res.data.rows[i].Checked == 2){ // 审核中 ,2 二次审核
                                    str += '<td class="font"><font color="green">审核中</font></td>';
                                }else if(res.data.rows[i].Checked == -1){ // 失败
                                    str += '<td class="font"><font color="#ccc">失败</font></td>';
                                }else if(res.data.rows[i].Checked == 1){ // 成功
                                    str += '<td class="font"><font color="red">成功</font></td>';
                                }
                                str += '<td ><font color="red">'+ res.data.rows[i].notes +'</font></td>';
                                str +=  '</tr>' ;

                            }

                            $recordslist.html(str) ;


                        }
                    }


                },
                error: function (msg) {
                    submitflag = false ;
                    layer.msg('网络异常',{time:alertTime});
                }
            });
        }

        // 额度转换类型处理 type
        function returnChangeType(type) {
            var str = '' ;
            switch (type){
                case 'hg':
                    str = '中心钱包';
                    break;
                case 'sc': // 皇冠体育
                    str ='皇冠体育' ;
                    break;
                case 'cp': // 彩票
                    str ='彩票平台' ;
                    break;
                case 'gmcp': // 三方彩票
                    str ='彩票平台' ;
                    break;
                case 'ag': // 彩票
                    str ='AG平台' ;
                    break;
                case 'ky': // 彩票
                    str ='开元棋牌' ;
                    break;
                case 'ly': // 乐游棋牌
                    str ='乐游棋牌' ;
                    break;
                case 'ff':
                    str ='皇冠棋牌' ;
                    break;
                case 'vg':
                    str ='VG棋牌' ;
                    break;
                case 'kl':
                    str ='快乐棋牌' ;
                    break;
                case 'mg':
                    str ='MG电子' ;
                    break;
                case 'avia':
                    str ='泛亚电竞' ;
                    break;
                case 'fire':
                    str ='雷火电竞' ;
                    break;
                case 'og':
                    str ='OG视讯' ;
                    break;
                case 'bbin':
                    str ='BBIN视讯' ;
                    break;
                case 'cq':
                    str ='CQ9电子' ;
                    break;
                case 'mw':
                    str ='MW电子' ;
                    break;
                case 'fg':
                    str ='FG电子' ;
                    break;
            }
            return str ;
        }

        chooseClickAction();
        seachBetRecord();
        titleToggle() ;
        betRecordChangePage();

    })
</script>