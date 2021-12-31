<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('lottery');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;


/**
 * 獲取上一期结果
 * @param  $game_code
 * */
function getlastresult($database  , $game_code ) {
    global $cpMasterDbLink;
    if($game_code != 69) { //以下数据结构一样
        if($game_code == 51) {  //北京PK十    game_code=51  (10位)
            $table=$database['cpDefault']['prefix']."saicheopen";
        } else if($game_code == 2) { //欢乐生肖  game_code=2
            $table=$database['cpDefault']['prefix']."3dopen";
        } else if($game_code == 3 || $game_code == 47) { //广东快乐十分 game_code=3    重庆幸运农场 game_code=47
            $table=$database['cpDefault']['prefix']."tenopen";

        }else if($game_code == 304){ // pc 蛋蛋
            $table=$database['cpDefault']['prefix']."pcddopen";
        }
        $sql="select round,endtime,number from $table where game_code=$game_code and endtime<".time()." ";
        $sql.="and number<>'' order by id desc limit 1";

        $res = mysqli_query($cpMasterDbLink,$sql);
        $row = mysqli_fetch_assoc($res);
        $result['round']=@$row['round'];
        $result['number']=explode(",",$row['number']);
        $result['numTotal']= array_sum($result['number']);
    } else { //香港六合彩    game_code=69    香港六合彩数据不一样
        $table=$database['cpDefault']['prefix']."xq_open";
        $xglhc_sql="select round, endTime,openTime, Num1,Num2,Num3,Num4,Num5,Num6,NumTm from ".$table." where endtime<".time()." ";
        $xglhc_sql.="and Num1 <>0 AND  Num2 <>0 AND Num3 <>0 AND Num4 <>0 AND Num5 <>0 AND Num6 <>0 AND NumTm <>0 ORDER BY id DESC limit 1";

        $res = mysqli_query($cpMasterDbLink,$xglhc_sql);
        $row = mysqli_fetch_assoc($res);

        $result['round']=@$row['round'];
        $result['number'][]=@$row['Num1'];
        $result['number'][]=@$row['Num2'];
        $result['number'][]=@$row['Num3'];
        $result['number'][]=@$row['Num4'];
        $result['number'][]=@$row['Num5'];
        $result['number'][]=@$row['Num6'];
        $result['number'][]=@$row['NumTm']; // 特码

        $result['numTotal']=array_sum($result['number']);
        $result['endTime']=date("Y-m-d", $row['endTime']);
        $result['openTime']=date("Y-m-d", $row['openTime']);
    }
    return $result;
}


// 获取上一期开奖结果
$xglhc_result = getlastresult($database , 69 );    //香港六合彩   game_code=69
$bjpks_result = getlastresult($database , 51);    //北京PK十   game_code=51
$cqssc_result = getlastresult($database , 2);     //欢乐生肖  game_code=2
$gdklsf_result = getlastresult($database , 3);      //广东快乐十分 game_code=3
$cqxync_result = getlastresult($database , 47);     //重庆幸运农场 game_code=47
$pcdd_result = getlastresult($database , 304);     //pc 蛋蛋 game_code=304

$gamResult = [
    51 =>$bjpks_result,
    2 =>$cqssc_result,
    47 =>$cqxync_result,
    3 =>$gdklsf_result,
    69 =>$xglhc_result,
    304 =>$pcdd_result,
];
$gamResult = json_encode($gamResult);

// 香港彩下一期时间处理
$nextRound = $xglhc_result['round']+1;
$xglhc_next_result = getnextresult($nextRound );    //香港六合彩下期开奖时间
function getnextresult($nextRound){
    global $database,$cpMasterDbLink;
    $table=$database['cpDefault']['prefix']."xq_open";
    $xglhc_sql="select round, endTime,openTime from ".$table." where round='$nextRound'";
    $res = mysqli_query($cpMasterDbLink,$xglhc_sql);
    $row = mysqli_fetch_assoc($res);
//    $nextresult['openTime']=date("Y-m-d H:i:s", $row['openTime']);
    $nextresult['openTime'] = date("m", $row['openTime']).'月'.date("d", $row['openTime']).'日21:30';
    return $nextresult;
}

// 香港六合彩当前期数数据
$lhc_data = [
    'endtime' => $xglhc_next_result['openTime'],
    'isopen' => 1,
    'round' =>  $xglhc_result['round']+1,
];
$lhc_data = json_encode($lhc_data);

?>
<style>
    .w_1100{width: 1100px;margin: 0 auto;}
    .lottery_game_bg{background:center  no-repeat url(<?php echo TPL_NAME;?>images/lottery/lottery_bg.jpg);background-size:cover;height:770px;position:relative}
    .lottery_game_bg .lottery_game_choose p{color:rgba(255,255,255,.7);padding-top:7px;font-size:13px}
    .lottery_game_choose{display: inline-block;float: left;width:580px;padding-top:80px;}
    .lottery_game_bg .lottery_game_choose ul li{float:left;text-align:center;margin:9px;transition:all 0.3s;cursor: pointer;}
    .lottery_game_bg .lottery_game_choose ul li img{width:66px;margin:auto}
    .lottery_game_bg .lottery_game_choose ul li:hover{transform:scale(1.2)}
    .btn_lottery_sw{padding-top:40px;margin-left:35px}
    .lottery_game_content{display: inline-block;padding-top: 265px;margin-left: 20px;color:#fff;}
    .chose_qh:first-child{display:block}
    .chose_qh{display:none}
    .lottery_game_content h1{font-size:50px}
    .lottery_game_content p{padding-top:0;font-size:14px;color:rgba(218,213,213,.7)}
    .lottery_game_cj{color:rgba(255,255,255,.8);font-size:16px}
    .lottery_game_cj span{color:#ffa000}
    .lottery_game_choose ul{margin-left:25px}
    .btn_betting {margin-top: 15px;}
    .btn_betting img,.btn_lottery_sw img{display: inline-block;cursor: pointer;transition: all 0.3s;}
    .btn_betting img:hover, .btn_lottery_sw img:hover {transform: scale(1.05);}
    .last_number{border:1px solid rgba(0,0,0,.5);border-radius:5px;padding:8px;margin-bottom:5px}
    .last_number i{font-style:normal;display:inline-block;text-align: center;width: 33px;height: 33px;line-height: 33px;background:#f97304;margin:5px 7px 0 0;border-radius:3px}

    .lottery_game_choose .tran{display:none;width:250px;background:#323130;padding:12px 16px;border-radius:5px;box-shadow:0 0 3px #323130;-webkit-box-shadow:0 0 3px #323130;margin:0 0 0 210px}
    .lottery_game_choose .tran:before{content:'';position:absolute;width:0;height:0;border-left:7px solid transparent;border-right:7px solid transparent;border-bottom:9px solid #323130;margin:-20px 38px}
    .lottery_game_choose .online_in{background-color:#ffb400;color:#fff;text-decoration:none;border-radius:4px;padding:2px 10px;position:relative;margin-left:15px}
    .lottery_game_choose .online_in:before{position:absolute;top:6px;left:-8px;content:'';width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:8px solid #ffb400}
    .lottery_game_choose .tran .game{margin:5px 0 0 0;color:#fff}
    .lottery_game_choose .tran tr.b_rig{border-top:1px solid #323130;text-align:center}
    .lottery_game_choose .tran td{width:59%;padding:8px 0;display:inline-block}
    .lottery_game_choose .tran td:first-child{width:40%}
    .lottery_game_choose .game select{border:1px solid #424141;border-radius:5px;height:25px;color:#fff;padding:0 3px;background:#323130}
    .lottery_game_choose .jbox-button{border-radius:5px;background:#ffb400;cursor:pointer;border:0;height:30px;line-height:30px;color:#fff;font-size:16px;padding:1px 10px;width:100%}
    .lottery_game_choose .top td{font-size:20px}
    .lottery_game_choose input {width: 100%;border: 0;background: transparent;color: #fff;}
    .lottery_game_choose input::placeholder{color:#fff}
    .lottery_game_choose input::-webkit-input-placeholder{color:#fff}
    .lottery_game_choose input:-moz-input-placeholder{color:#fff}
    .lottery_game_choose input::-moz-input-placeholder{color:#fff}
    .lottery_game_choose input:-ms-input-placeholder{color:#fff}
    
</style>
<div class="container">
    <div class="lottery_game_bg">
        <div class="w_1100">
            <div class="lottery_game_choose">
                <img src="<?php echo TPL_NAME;?>images/lottery/lottery_logo.png" alt="">
                <ul class="lottery_list">
                  <!--  <li>
                        <img src="images/lottery/game_icon1.png" alt="">
                        <p>北京赛车</p>
                    </li>
                    <li>
                        <img src="images/lottery/game_icon2.png" alt="">
                        <p>欢乐生肖</p>
                    </li>-->

                </ul>
                <div style="clear: both"></div>
                <div class="btn_lottery_sw">
                    <img src="<?php echo TPL_NAME;?>images/lottery/try.png" alt="试玩" <?php echo $uid?'onclick="indexCommonObj.openGameCommon(this,\''.$uid.'\',\''.$cpUrl.'\')"> ':'class="to_testplaylogin"'; ?> >
                    <img src="<?php echo TPL_NAME;?>images/lottery/cp_change.png" alt="额度转换" class="show_cp_change" >
                </div>
                <!-- 额度转换窗口 -->
                <div class="tran" >

                    <!--<a href="javascript:;"  class="online_in">去存款</a>-->
                    <table border="0" cellspacing="1" cellpadding="0" class="game">
                        <tbody>
                        <tr class="top" align="center">
                            <td clospan="2">额度转换</td>
                        </tr>
                        <tr class="b_rig">
                            <td align="left">中心钱包</td>
                            <td align="left"><span class="user_member_amount">0.00</span></td>

                        </tr>
                        <tr class="b_rig">
                            <td align="left">彩票余额</td>
                            <td align="left"><span class="user_member_lottery_amount">0.00</span></td>
                        </tr>
                        <tr class="b_rig">
                            <td align="left" >
                                <select class="from_blance" >
                                    <option value="hg" selected="selected" data-from="hg">中心钱包</option>
                                    <option value="cp" data-from="cp">彩票余额</option>
                                </select>
                            </td>
                            <td align="left">
                                <select class="to_blance" >
                                    <option value="hg" data-to="hg">中心钱包</option>
                                    <option value="cp" selected="selected" data-to="cp">彩票余额</option>
                                </select>
                                <br>
                            </td>
                        </tr>
                        <tr class="b_rig">
                            <td align="left">
                                转换金额 &nbsp;￥
                            </td>
                            <td align="left">  <input type="number" class="transfer_input" placeholder="0.00" > </td>
                        </tr>

                        </tbody>
                    </table>
                    <input type="button" class="cp_change_btn jbox-button" value="提交转换" data-platform="cp">
                </div>


            </div>
            <div class="lottery_game_content">

           <!--     <div class="chose_qh">
                    <h1>香港六合彩</h1>
                    <p>历史悠久，时下最热门、最受欢迎的高端彩种 >></p>
                    <div class="lottery_game_cj">第<span>2019078</span>期开奖时间：07月11日 21：30</div>
                    <div class="btn_betting">
                        <img src="images/lottery/btn_betting.png" alt="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','<?php /*echo $cpUrl;*/?>')">
                    </div>
                </div>
                <div class="chose_qh">
                    <h1>欢乐生肖</h1>
                    <p>历史悠久，时下最热门、最受欢迎的高端彩种 >></p>
                    <div class="lottery_game_cj">第<span>2019078</span>期开奖时间：07月11日 21：30</div>
                    <div class="btn_betting">
                        <img src="images/lottery/btn_betting.png" alt="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','<?php /*echo $cpUrl;*/?>')">
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    clearTimeout(pk10_count) ;
    clearTimeout(cqssc_count) ;
    clearTimeout(gdklsf_count) ;
    clearTimeout(cqxync_count) ;
    clearTimeout(pcdd_count) ;

    var ajax_url = '/app/member/api/ajaxlottery.php';
    var pk10_count ;
    var cqssc_count ;
    var gdklsf_count ;
    var cqxync_count ;
    var pcdd_count ;
    var pk10_endtime = 0;
    var cqssc_endtime = 0;
    var gdklsf_endtime = 0;
    var cqxync_endtime = 0;
    var pcdd_endtime = 0;

    $(function () {


        var uid = '<?php echo $uid;?>';
        var cpUrl = '<?php echo $cpUrl;?>';
        var lhc_data = '<?php echo $lhc_data;?>';
        lhc_data = $.parseJSON(lhc_data);

        var gamResult = '<?php echo $gamResult;?>'; // 初始数据
        gamResult = $.parseJSON(gamResult);
       // console.log(gamResult)


        var lotteryList = [
                // {gamename:'北京赛车',gametype:'pk10',gamecode:51},
                // {gamename:'欢乐生肖',gametype:'cqssc',gamecode:2},
                // {gamename:'PC蛋蛋',gametype:'pcdd',gamecode:304},
                {gamename:'重庆幸运农场',gametype:'cqxync',gamecode:47},
                {gamename:'广东快乐十分',gametype:'klsf',gamecode:3},
                {gamename:'香港六合彩',gametype:'xglhc',gamecode:69},

        ];

        // 显示彩种
        function showLotteryList() {
            var str = ''; // 彩种列表
            var time_str = ''; // 彩种时间
            for (var i=0;i< lotteryList.length;i++){
                str +=' <li data-gameid="'+ lotteryList[i].gamecode +'">' +
                    '   <img src="<?php echo TPL_NAME;?>images/lottery/game_icon_'+ lotteryList[i].gamecode +'.png" alt="">' +
                    '     <p>'+ lotteryList[i].gamename +'</p>' +
                    '  </li>';

                time_str += '<div class="chose_qh chose_qh_'+ lotteryList[i].gamecode +'">' +
                    '                <h1>'+ lotteryList[i].gamename +'</h1>' +

                    '<div class="last_number">' ;
                $.each(gamResult,function (key,v) {
                    if(lotteryList[i].gamecode == key){
                        // console.log(key)
                        // console.log(v)
                        // console.log(v.number)
                        time_str +=  '<div class="lottery_game_cj">第<span > '+ (v.round?v.round:'') +' </span> 期开奖</div> ';
                        time_str += gameResultNumber(key,v);

                    }

                })

                time_str += '</div> '+
                    // '                <p>'+ lotteryList[i].title +'</p>' +
                    '                <div class="lottery_game_cj">' +
                    '第' ;
                if(lotteryList[i].gamecode == 69){ // 香港六合彩
                    time_str +=  '<span>'+ lhc_data.round +'</span>' +
                        '期开奖时间：'+ lhc_data.endtime +'</div>';
                }else{
                    time_str +=  '<span class="round_'+ lotteryList[i].gamecode +'"> </span>' +
                        '期开奖时间：<span class="endtime_'+ lotteryList[i].gamecode +'"> </span> </div>';
                }

                time_str += '                <div class="btn_betting">' +
                    '                    <img src="<?php echo TPL_NAME;?>images/lottery/btn_betting.png" alt="" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+cpUrl+'\')">' +
                    '                </div>' +
                    '            </div>' ;
            }

            $('.lottery_list').html(str);
            $('.lottery_game_content').html(time_str);
        }
        
        // 显示当前彩种
        function showLotteryGame(){
            $('.lottery_list li').hover(function () {
                var gameid = $(this).attr('data-gameid');
                $('.lottery_game_content .chose_qh').hide();
                $('.lottery_game_content').find('.chose_qh_'+gameid).show();
            })
        }

        // 开奖号码处理,type : 彩种id ,gameNum : v
        function gameResultNumber(type,gameNum){
            var numstr = '';
            switch (type){
                case '304': // pc 蛋蛋
                    for(var j=0;j<gameNum.number.length;j++){
                        numstr +=   '<i>'+ gameNum.number[j] +'</i>'+ (j<(gameNum.number.length-1)?'+ &nbsp;':'='+gameNum.numTotal) ;
                    }
                    break;
                case '69': // 香港六合彩
                    for(var j=0;j<gameNum.number.length;j++){
                        numstr +=   '<i>'+ gameNum.number[j] +'</i>'+ (j==(gameNum.number.length-2)?'+ &nbsp;':'') ;
                    }
                    break;
                default:
                    for(var j=0;j<gameNum.number.length;j++){
                        numstr +=   '<i>'+ gameNum.number[j] +'</i>';
                    }
            }
            return numstr ;
        }

        // 点击转账中心上下浮动 和额度转换弹窗出现
        function showCpChangeBtn(){
            $('.show_cp_change').click(function () {
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});return;
                }
                event.stopPropagation();
                $('.tran').stop().fadeToggle("slow","linear");
                $(this).stop().addClass('button-move');
                setTimeout(function () {
                    $(this).stop().removeClass('button-move');
                }, 200)
            })
        }

        // 额度转换
        function changeCpMoney() {
            $('.cp_change_btn').on('click',function () {
                var  plat = $(this).attr('data-platform');
                var  p_fm = $('.from_blance').find('option:selected').attr('data-from');
                var  p_to = $('.to_blance').find('option:selected').attr('data-to');
                var  mon  = $('.transfer_input').val() ; // 金额 ; // 金额
                if(!plat || !p_fm || !p_to){
                    layer.msg('请选择平台',{time:alertTime});
                    return ;
                }
                if( (p_fm !='hg' && p_to !='hg') || (p_fm =='hg' && p_to =='hg') ){
                    layer.msg('只能从体育转入或转出到其他平台',{time:alertTime});
                    return ;
                }

                if(mon ==0 || mon == NaN || mon ==null || mon=='加载中...'){
                    layer.msg('没有需要转入的金额',{time:alertTime});
                    return ;
                }
                indexCommonObj.transferAccounts(plat,p_fm,p_to,mon) ;
            })
        }

        indexCommonObj.getUserAllPlateMoney(uid) ;
        showCpChangeBtn();
        changeCpMoney();

        showLotteryList();
        showLotteryGame();
        
        //pk10_cound_endtime();
        //cqssc_cound_endtime();
        gdklsf_cound_endtime();
        cqxync_cound_endtime();
        //pcdd_cound_endtime();
        
    })

    //格式化時間
    function FormatTime(roundSecounds) {
        var hour = 0;	//時
        var minute = 0; //分
        var second = 0; //秒
        var str = "";
        var hourHtml = "";
        hour = Math.floor(roundSecounds / 60 / 60);
        minute = Math.floor((roundSecounds - (hour * 60 * 60)) / 60);
        second = Math.floor(roundSecounds - (hour * 60 * 60) - (minute * 60));
        if (minute < 10) {
            minute = "0" + minute;
        }
        if (second < 10) {
            second = "0" + second;
        }
        if (hour != 0) {
            if (hour < 10) {
                hourHtml = "0" + hour + ":";
            } else if (hour >= 10) {
                hourHtml = hour + ":";
            }
        }
        str = hourHtml + minute + ":" + second;
        return str;
    }

    // 北京PK10获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen
    function pk10_getroundinfo(){
        var dat = {};
        dat.game_type = 'pk10';
        dat.game_code = '51';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: ajax_url ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                pk10_endtime = msg.endtime ;
                $(".round_51").html(msg.round); // 期数
                $(".endtime_51").html(FormatTime(msg.endtime)); // 开奖时间

            }
        });
    }
    function pk10_cound_endtime() {
        pk10_count = setTimeout('pk10_cound_endtime()', 1000);
        if (pk10_endtime <= 0) {
            pk10_getroundinfo();
        } else {
            pk10_endtime--;
            $(".endtime_51").html(FormatTime(pk10_endtime));

        }

    }
    // 欢乐生肖获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen    game_code=2
    function cqssc_getroundinfo(){
        var dat = {};
        dat.game_type = 'cqssc';
        dat.game_code = '2';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: ajax_url ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                cqssc_endtime = msg.endtime ;
                $(".round_2").html(msg.round); // 期数
                $(".endtime_2").html(FormatTime(msg.endtime)); // 开奖时间
            }
        });
    }
    // 欢乐生肖倒计时
    function cqssc_cound_endtime() {
        cqssc_count = setTimeout('cqssc_cound_endtime()', 1000);
        if (cqssc_endtime <= 0) {
            cqssc_getroundinfo();
        } else {
            cqssc_endtime--;
            $(".endtime_2").html(FormatTime(cqssc_endtime));

        }

    }

    // 广东快乐十分获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen    game_code=3
    function klsf_getroundinfo(){
        var dat = {};
        dat.game_type = 'klsf';
        dat.game_code = '3';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: ajax_url ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                gdklsf_endtime = msg.endtime ;
                $(".round_3").html(msg.round); // 期数
                $(".endtime_3").html(FormatTime(msg.endtime)); // 开奖时间
            }
        });
    }

    // 广东快乐十分倒计时
    function gdklsf_cound_endtime() {
        gdklsf_count = setTimeout('gdklsf_cound_endtime()', 1000);
        if (gdklsf_endtime <= 0) {
            klsf_getroundinfo();
        } else {
            gdklsf_endtime--;
            $(".endtime_3").html(FormatTime(gdklsf_endtime));
        }

    }

    // 重庆幸运农场获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen    game_code=47
    function cqxync_getroundinfo(){
        var dat = {};
        dat.game_type = 'cqxync';
        dat.game_code = '47';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: ajax_url ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                cqxync_endtime = msg.endtime ;
                $(".round_47").html(msg.round); // 期数
                $(".endtime_47").html(FormatTime(msg.endtime)); // 开奖时间
            }
        });
    }
    // 重庆幸运农场
    function cqxync_cound_endtime() {
        cqxync_count = setTimeout('cqxync_cound_endtime()', 1000);
        if (cqxync_endtime <= 0) {
            cqxync_getroundinfo();
        } else {
            cqxync_endtime--;
            $(".endtime_47").html(FormatTime(cqxync_endtime));

        }

    }
    // PC蛋蛋 获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen    game_code=47
    function pcdd_getroundinfo(){
        var dat = {};
        dat.game_type = 'pcdd';
        dat.game_code = '304';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: ajax_url ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                pcdd_endtime = msg.endtime ;
                $(".round_304").html(msg.round); // 期数
                $(".endtime_304").html(FormatTime(msg.endtime)); // 开奖时间
            }
        });
    }
    // PC蛋蛋
    function pcdd_cound_endtime() {
        pcdd_count = setTimeout('pcdd_getroundinfo()', 1000);
        if (pcdd_endtime <= 0) {
            pcdd_getroundinfo();
        } else {
            cqxync_endtime--;
            $(".endtime_304").html(FormatTime(pcdd_endtime));

        }

    }
</script>