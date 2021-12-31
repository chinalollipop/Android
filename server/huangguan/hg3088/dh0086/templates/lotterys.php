<script type="text/javascript">
    var arrNo=[
        {begindate:"2014-01-30",enddate:"2015-02-18",an:"马,蛇,龙,兔,虎,牛,鼠,猪,狗,鸡,猴,羊"},
        {begindate:"2015-02-19",enddate:"2016-02-07",an:"羊,马,蛇,龙,兔,虎,牛,鼠,猪,狗,鸡,猴"},
        {begindate:"2016-02-08",enddate:"2017-01-27",an:"猴,羊,马,蛇,龙,兔,虎,牛,鼠,猪,狗,鸡"},
        {begindate:"2017-01-28",enddate:"2018-02-15",an:"鸡,猴,羊,马,蛇,龙,兔,虎,牛,鼠,猪,狗"},
        {begindate:"2018-02-16",enddate:"2019-02-04",an:"狗,鸡,猴,羊,马,蛇,龙,兔,虎,牛,鼠,猪"},
        {begindate:"2019-02-05",enddate:"2020-01-24",an:"猪,狗,鸡,猴,羊,马,蛇,龙,兔,虎,牛,鼠"},
        {begindate:"2020-01-25",enddate:"2021-02-11",an:"鼠,猪,狗,鸡,猴,羊,马,蛇,龙,兔,虎,牛"},
        {begindate:"2021-02-12",enddate:"2022-01-31",an:"牛,鼠,猪,狗,鸡,猴,羊,马,蛇,龙,兔,虎"},
        {begindate:"2022-02-01",enddate:"2023-01-21",an:"虎,牛,鼠,猪,狗,鸡,猴,羊,马,蛇,龙,兔"},
        {begindate:"2023-01-22",enddate:"2024-02-09",an:"兔,虎,牛,鼠,猪,狗,鸡,猴,羊,马,蛇,龙"}];
    function getYearAnimal(a){for(var b="",c=0;c<arrNo.length;c++){var f=arrNo[c];if(a>=f.begindate&&a<=f.enddate){b=f.an;break;}}return b;}
    var get_animal_by_ball_time=function(a,b){var c=Number(a)%12-1;-1==c&&(c=11);return getYearAnimal(b).split(",")[c]};
</script>
<?php
require ("../include/config.inc.php");
require ("../include/address.mem.php");

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;

// 获取上一期开奖结果
$xglhc_result = getlastresult($database , 69 );    //香港六合彩   game_code=69
$bjpks_result = getlastresult($database , 51);    //北京PK十   game_code=51
$cqssc_result = getlastresult($database , 2);     //欢乐生肖  game_code=2
$gdklsf_result = getlastresult($database , 3);      //广东快乐十分 game_code=3
$cqxync_result = getlastresult($database , 47);     //重庆幸运农场 game_code=47

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
        $result['number'][1]=@$row['Num1'];
        $result['number'][2]=@$row['Num2'];
        $result['number'][3]=@$row['Num3'];
        $result['number'][4]=@$row['Num4'];
        $result['number'][5]=@$row['Num5'];
        $result['number'][6]=@$row['Num6'];
        $result['number']["te"]=@$row['NumTm'];

        $result['numTotal']=array_sum($result['number']);
        $result['endTime']=date("Y-m-d", $row['endTime']);
        $result['openTime']=date("Y-m-d", $row['openTime']);
    }
    return $result;
}

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

//// 香港彩时间处理
//$localdate = date("Y-m-d H:i:s",time()+43200) ; // 北京时间
//// echo $localdate ;
//$tom_localdate = date('Y-m-d',time()+36*60*60) ; // 明天时间
//$localdate = (string) $localdate ;
//$tom_localdate = (string) $tom_localdate ;
//$localdate_arr = explode(' ',$localdate) ;
//$xglhc_date = $localdate_arr[0].' 21:30'; // 固定开奖时间
//if($localdate < $xglhc_date){ // 取现在的时间
//    $show_date = $localdate ;
//}else{ // 取明天的时间
//    $show_date = $tom_localdate ;
//}
//// echo $xglhc_date ;
//$localdate_y = substr($show_date,0,4) ; // 年
//$localdate_m = substr($show_date,5,2); // 月
//$localdate_d = substr($show_date,8,2); // 月


?>
<style>
    .open_items .item:hover { border: 1px solid #ffce6f; }
    .cp_mn {
        margin: 0 auto;
        overflow: hidden;
        width: 900px;
        padding-bottom: 20px;
        font-size: 12px;
    }
    .cp_mn1 {
        float: left;
        display: inline;
        width: 100%;
        height: 70px;
        margin-bottom: 15px;
    }
    img {
        color: transparent;
        font-size: 0;
        vertical-align: middle;
        -ms-interpolation-mode: bicubic;
        border: none;
    }
    fieldset, img {
        border: none;
    }
    .g_w1000 {
        width: 900px;
        margin: 0 auto;
    }
    .open_items {
        margin-top: 5px;
    }
    .open_items .item {
        min-height: 130px;
        padding-top: 10px;
        background: #fff;
        border: 1px solid #fff;
    }
    .open_items .items_2 {
        background: #eff2f7;
        border: 1px solid #eff2f7;
    }
    .open_items .item .lo {
        width: 30px;
        float: left;
        height: 138px;
        margin-right: 7px;
    }
    .open_items .item .info {
        width: 370px;
        float: left;
        height: 138px;
    }
    .ll {
        float: left;
    }
    .open_items .item .info .t1 {
        height: 62px;
        padding-top: 16px;
        padding-bottom: 4px;
        background-repeat: no-repeat;
        background-position: top center;
        text-align: center;
    }
    a:link, a:visited {
        color: #fff;
        text-decoration: none;
    }
    .open_items .item .info .d6 {
        padding-top: 10px;
        height: 28px;
        line-height: 28px;
        text-align: left;
        color: #383838;
        font-weight: bold;
    }
    .open_term1 {
        color: #f73f56;
    }
    .open_items .item .info .t {
        width: 300px;
        height: 28px;
        line-height: 28px;
        color: #31373d;
    }
    .open_items .item .info .t .l {
        float: left;
        margin-left: -1px;
        color: #6F6C6C;
    }
    .open_items .item .info .t span {
        color: #ff8a00;
        font-weight: bold;
    }
    .open_items .item .info .t .next_term {
        margin: 0 auto;
        float: left;
    }
    .open_items .item .info .t .next_date {
        margin: 0 auto;
        float: left;
        color: #6F6C6C;
    }
    .cb {
        clear: both;
    }
    .lj_game {
        padding-left: 62px;
        margin-top: -7px;
    }
    a.golj_game {
        background: url(./images/golj.png) no-repeat;
        width: 200px;
        height: 40px;
        display: block;
    }
    a.golj_game:hover {
        background: url(./images/golj_hover.png) no-repeat;
        width: 200px;
        height: 40px;
        display: block;
    }
    .open_items .item .ball-gp {
        float: left;
        min-height: 120px;
        width: 450px;
    }
    .open_items .item .ball-gp .ball {
        float: left;
        height: 75px;
        margin-top: 18px;
    }
    .open_items .item .ball-gp .ball dl {
        width: 40px;
        float: left;
        height: 75px;
        margin-right: 8px;
        text-align: center;
        color: #31373d;
        line-height: 40px;
        font-size: 14px;
    }
    .ball_hk6_ {
        display: inline-block;
        width: 37px;
        height: 37px;
        line-height: 37px;
        text-align: center;
        font-size: 16px;
        font-family: "微软雅黑";
        color: #000;
        /* font-weight: bold; */
        background: transparent url(images/balls_bg.png) no-repeat scroll -402px -448px;
    }
    .ball_hk6_red {background-position: 0 -164px;}
    .ball_hk6_blue { background-position: -44px -164px; }
    .ball_hk6_green { background-position: -87px -164px; }
    .open_items .item .ball-gp .ball-sum {
        width: 60px;
        float: left;
        height: 75px;
        margin-top: 12px;
        line-height: 42px;
        text-align: center;
        font-size: 14px;
        color: #31373d;
    }
    .open_items .item .ball-gp .ball-sum span {
        color: #ff3158;
        font-weight: bold;
    }
    .g_clear {
        clear: both;
    }
    .open_items .item .lo {
        width: 30px;
        float: left;
        height: 138px;
        margin-right: 7px;
    }
    .open_items .item .info .t .next_date2 span {
        float: left;
        height: 28px;
        margin-top: 0px;
        width: 42px;
        text-align: center;
        line-height: 28px;
        color: #633a18;
        font-size: 22px;
        font-weight: bold;
        background-repeat: no-repeat;
    }
    .open_items .item .ball-gp .t {
        min-height: 50px;
        margin-top: 10px;
        color: #6F6C6C;
    }
    ul, li {
        list-style: none;
    }
    .ball_pks_ {
        display: inline-block;
        width: 36px;
        height: 42px;
        line-height: 35px;
        margin-right: 3px;
        text-align: center;
        font-size: 16px;
        font-family: "微软雅黑";
        color: #000;
        font-weight: bold;
        background: transparent url(./images/balls_bg.png) no-repeat scroll -402px -448px;
    }
    .ball_pks1 { background-position: 0px -34px; }
    .ball_pks2 { background-position: -44px -34px; }
    .ball_pks3 { background-position: -87px -34px; }
    .ball_pks4 { background-position: -130px -34px; }
    .ball_pks5 { background-position: -174px -34px; }
    .ball_pks6 { background-position: -218px -34px; }
    .ball_pks7 { background-position: -261px -34px; }
    .ball_pks8 { background-position: -305px -34px; }
    .ball_pks9 { background-position: -350px -34px; }
    .ball_pks10 { background-position: -395px -34px; }
    .open_items .item .ball-gp .d table {
        background-color: #fff;
    }
    .open_items .item .ball-gp .d table th {
        border: 1px solid #dddddd;
        text-align: center;
        border-bottom: none;
        height: 25px;
        font-weight: bold;
        color: #000000;
    }
    .open_items .item .ball-gp .d table td {
        border: 1px solid #dddddd;
        text-align: center;
        height: 25px;
        font-weight: bold;
    }
    .open_items .item .ball-gp .d table td.gray { color: #F00078; }
    .open_items .item .ball-gp .d table td.blue { color: #0072E3; }
    .open_items .item .ball-gp .d table td.green { color: #009100; }
    .open_items .item .ball-gp .d table td.count { color: #CE0000; }
    .ball_s_ {
        display: inline-block;
        width: 35px;
        height: 35px;
        line-height: 35px;
        margin-right: 6px;
        text-align: center;
        font-size: 16px;
        font-family: "微软雅黑";
        color: #000;
        font-weight: bold;
        background: transparent url(images/balls_bg.png) no-repeat scroll -402px -448px;
    }
    .ball_s_blue {
        background-position: -131px -82px;
    }
    .ball_nc_ {
        display: inline-block;
        width: 40px;
        height: 46px;
        background: transparent url(images/balls_bg.png) no-repeat scroll -402px -448px;
        display: inline-block;
        margin-right: 10px;
    }
    .ball_nc01 { background-position: -0px -240px; }
    .ball_nc02 { background-position: -51px -240px; }
    .ball_nc03 { background-position: -97px -240px; }
    .ball_nc04 { background-position: -144px -240px; }
    .ball_nc05 { background-position: -191px -240px; }
    .ball_nc06 { background-position: -242px -240px; }
    .ball_nc07 { background-position: -288px -240px; }
    .ball_nc08 { background-position: -334px -240px; }
    .ball_nc09 { background-position: -381px -240px; }
    .ball_nc10 { background-position: -428px -240px; }
    .ball_nc11 { background-position: -0px -293px; }
    .ball_nc12 { background-position: -51px -293px; }
    .ball_nc13 { background-position: -97px -293px; }
    .ball_nc14 { background-position: -144px -293px; }
    .ball_nc15 { background-position: -191px -293px; }
    .ball_nc16 { background-position: -242px -293px; }
    .ball_nc17 { background-position: -288px -293px; }
    .ball_nc18 { background-position: -334px -293px; }
    .ball_nc19 { background-position: -381px -293px; }
    .ball_nc20 { background-position: -428px -293px; }
</style>

<div class="subban subban4"></div>
<div class="psr">
    <div class="banyuan">

    </div>
</div>
<!-- 主体 -开始 -->
<div class="cp_mn">
    <span class="cp_mn1"><img src="images/cp.png"></span>
    <div class="g_w1000 open_items open_items_other">

        <!--香港彩-->
        <div class="item  items_2  info_1002 ">
            <div class="lo"></div>
            <div class="info">
                <div>
                    <div class="t1 ll"><a href="javascript:void(0)" ><img src="./images/logo1002.png" border="0"></a></div>
                    <div class="d6 ll">
                        <span class="open_term1" id="xgc_kaijaing_qishu"><?php echo $xglhc_result['round']; ?></span>期&nbsp;香港六合彩
                        <div class="t">
                            <div class="l"><span class="next_term" id="xgc_kaijaing_now_round"><?php echo $xglhc_result['round']+1; ?></span>期开奖时间：</div>
                            <div class="next_date next_date2" id="xgc_kaijaing_now_opendate">
                                <?php /*echo $localdate_m*/?><!--月<?php /*echo $localdate_d*/?>日21:30-->
                                <?php echo $xglhc_next_result['openTime'];?>
                            </div>
                        </div>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="lj_game"><a onclick="addTryPlay()" href="javascript:;" class="golj_game"></a></div>
            </div>
            <div class="ball-gp">
                <div >
                    <div class="ball" id="xgc_kaijiang_data">
                        <dl>
                            <span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['1'])?>" id="ball_l1"><?php echo $xglhc_result['number']['1']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['1']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['1']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['1']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['2'])?>" id="ball_l2"><?php echo $xglhc_result['number']['2']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['2']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['2']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['2']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['3'])?>" id="ball_l3"><?php echo $xglhc_result['number']['3']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['3']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['3']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['3']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['4'])?>" id="ball_l4"><?php echo $xglhc_result['number']['4']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['4']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['4']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['4']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['5'])?>" id="ball_l5"><?php echo $xglhc_result['number']['5']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['5']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['5']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['5']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['6'])?>" id="ball_l6"><?php echo $xglhc_result['number']['6']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['6']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['6']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['6']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl>
                        <dl>+</dl><dl><span class="ball_hk6_ <?php echo setSixLotteryLogo($xglhc_result['number']['te'])?>" id="ball_l7"><?php echo $xglhc_result['number']['te']; ?></span><br>
                            <font id="info_<?php echo $xglhc_result['number']['te']; ?>">
                                <?php  echo "<script>document.getElementById(\"info_".$xglhc_result['number']['te']."\").innerHTML=(get_animal_by_ball_time('".(int)$xglhc_result['number']['te']."','".$xglhc_result['openTime']."'));</script>"; ?>
                            </font>
                        </dl></div>
                    <div class="ball-sum">总分<br><span id="xgc_kaijaing_total"><?php echo $xglhc_result['numTotal']; ?></span></div>
                    <div class="g_clear"></div>
                </div>
                <div class="m" id="msgInfo4001" style="display: none;"><span style="color:#ff0000;">&nbsp;</span></div>
                <div class="g_clear"></div>
            </div>
            <div class="g_clear"></div>
        </div>

        <!--香港彩-->
        <!--北京PK拾-->

        <!--<div class="item info_10016 ">
            <div class="lo"></div>
            <div class="info">
                <div>
                    <div class="t1 ll"><a href="javascript:void(0)"><img src="./images/logo10016.png" border="0" onclick="alert('请登入后进行游戏，谢谢~');"></a></div>
                    <div class="d6 ll"><span class="open_term1" id="pk10_kaijaing_qishu"><?php /*echo $bjpks_result['round']; */?></span>期&nbsp;北京PK拾
                        <div class="t">
                            <div class="l"><span class="next_term" id="pk10_now_qishu"><?php /*echo $bjpks_result['round']+1; */?></span>期开奖时间：</div>
                            <div class="next_date next_date2"><span id="pk10_endtime">00:00</span></div>
                        </div>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="lj_game"><a onclick="addTryPlay()" href="javascript:;" class="golj_game"></a> <a href="javascript:void(0)" onclick="javascript:jb();" class="golj_game1"></a></div>
            </div>
            <div class="ball-gp">
                <div class="t " id="pk10_kaijiang_data"><ul class="term_ball">
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['0'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['0']);}else{ echo '10';} */?> ball_lenght10 " title="<?php /*echo $bjpks_result['number']['1']; */?>"style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['1'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['1']);}else{ echo '1';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['1']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['2'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['2']);}else{ echo '2';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['2']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['3'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['3']);}else{ echo '3';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['3']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['4'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['4']);}else{ echo '4';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['4']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['5'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['5']);}else{ echo '5';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['5']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['6'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['6']);}else{ echo '6';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['6']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['7'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['7']);}else{ echo '7';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['7']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['8'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['8']);}else{ echo '8';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['8']; */?>" style="display: inline-block;"></span>
                        <span class="ball_pks_  ball_pks<?php /*if(preg_replace('/^0+/','',$bjpks_result['number']['9'])){ echo preg_replace('/^0+/','',$bjpks_result['number']['9']);}else{ echo '9';} */?> ball_lenght10  " title="<?php /*echo $bjpks_result['number']['9']; */?>" style="display: inline-block;"></span></ul></div>
                <div class="d">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tbody>
                        <tr class="th_header">
                            <th colspan="3">冠亚</th>
                            <th colspan="5">1-5球龙虎</th>
                        </tr>
                        <tr class="td_body">
                            <?php /* $bjpks_gy_sum = $bjpks_result['number']['0'] + $bjpks_result['number']['1']; */?>
                            <td class="count" id="pk10_guanyahe"><?php /*echo $bjpks_gy_sum; */?></td>
                            <td class="blue" id="pk10_guanyadaxiao"><?php /*if($bjpks_gy_sum>11){ echo "大";}elseif($bjpks_gy_sum<=11){ echo "小"; }*/?></td>
                            <td class="blue" id="pk10_guanyadanshuang"><?php /*if($bjpks_gy_sum%2==0){ echo "双";}elseif($bjpks_gy_sum<=11){ echo "单"; }*/?></td>
                            <td class="blue" id="pk10_longhu1"><?php /*echo ($bjpks_result['number']['0']>$bjpks_result['number']['9'])?'龙':'虎' */?></td>
                            <td class="blue" id="pk10_longhu2"><?php /*echo ($bjpks_result['number']['1']>$bjpks_result['number']['8'])?'龙':'虎' */?></td>
                            <td class="blue" id="pk10_longhu3"><?php /*echo ($bjpks_result['number']['2']>$bjpks_result['number']['7'])?'龙':'虎' */?></td>
                            <td class="gray" id="pk10_longhu4"><?php /*echo ($bjpks_result['number']['3']>$bjpks_result['number']['6'])?'龙':'虎' */?></td>
                            <td class="blue" id="pk10_longhu5"><?php /*echo ($bjpks_result['number']['4']>$bjpks_result['number']['5'])?'龙':'虎' */?></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="g_clear"></div>
                </div>
                <div class="g_clear"></div>
            </div>
            <div class="g_clear"></div>
        </div>-->
        <!--北京PK拾结束-->
        <!--欢乐生肖-->
        <!--<div class="item  items_2 info_10011 ">
            <div class="lo"></div>
            <div class="info">
                <div>
                    <div class="t1 ll"><a href="javascript:void(0)"><img src="images/logo10011.png" border="0" onclick="alert('请登入后进行游戏，谢谢~');"></a></div>
                    <div class="d6 ll"><span class="open_term1" id="cqssc_kaijaing_qishu"><?php /*echo $cqssc_result['round']; */?></span>期&nbsp;欢乐生肖
                        <div class="t">
                            <div class="l"><span class="next_term" id="cqssc_now_qishu">
                                    <?php
/*                                    $maxresult = explode("-",$cqssc_result['round']);
                                    if($maxresult[1] == '120') {
                                        echo $maxresult[0]+1 .'-'.'001';
                                    } else {
                                        echo $maxresult[0].'-'.sprintf('%03s', $maxresult[1]+1);
                                    }
                                    */?>
                                </span>期开奖时间：</div>
                            <div class="next_date next_date2"><span id="cqssc_endtime">00:00</span></div>
                        </div>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="lj_game"><a onclick="addTryPlay()" href="javascript:;" class="golj_game"></a> <a href="javascript:void(0)" onclick="javascript:jb();" class="golj_game1"></a></div>
            </div>
            <div class="ball-gp">
                <div class="t " id="cqssc_kaijiang_data"><ul class="term_ball">
                        <span class="  ball_s_ ball_s_blue ball_lenght5  " title="<?php /*echo $cqssc_result['number']['0']; */?>" style="display: inline-block;"><?php /*echo $cqssc_result['number']['0']; */?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght5  " title="<?php /*echo $cqssc_result['number']['1']; */?>" style="display: inline-block;"><?php /*echo $cqssc_result['number']['1']; */?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght5  " title="<?php /*echo $cqssc_result['number']['2']; */?>" style="display: inline-block;"><?php /*echo $cqssc_result['number']['2']; */?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght5  " title="<?php /*echo $cqssc_result['number']['3']; */?>" style="display: inline-block;"><?php /*echo $cqssc_result['number']['3']; */?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght5  " title="<?php /*echo $cqssc_result['number']['4']; */?>" style="display: inline-block;"><?php /*echo $cqssc_result['number']['4']; */?></span></ul></div>
                <div class="d">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tbody>
                        <tr class="th_header">
                            <th colspan="3">总和</th>
                            <th colspan="1">龙虎</th>
                        </tr>
                        <tr class="td_body">
                            <td class="count" id="cqssc_hezhi"><?php /*echo $cqssc_result['numTotal']; */?></td>
                            <td class="blue" id="cqssc_hezhidanshuang"><?php /*if($cqssc_result['numTotal']%2==0){echo "双";}else{ echo "单"; } */?></td>
                            <td class="gray" id="cqssc_hezhidaxiao"><?php /*if($cqssc_result['numTotal']>=23){echo "大";}else{ echo "小"; } */?></td>
                            <td class="blue" id="cqssc_longhu">
                                <?php
/*                                    if($cqssc_result['number']['0']>$cqssc_result['number']['4']){  echo "龙";}
                                    elseif($cqssc_result['number']['0']= @$cqssc_result['number']['4']) {   echo "和"; }
                                    else{  echo "虎";}
                                */?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="g_clear"></div>
                </div>
                <div class="g_clear"></div>
            </div>
            <div class="g_clear"></div>
        </div>-->
        <!--欢乐生肖结束-->
        <!--广东快乐十分-->
        <div class="item  info_1008 ">
            <div class="lo"></div>
            <div class="info">
                <div>
                    <div class="t1 ll"><a href="javascript:void(0)"><img src="images/logo1008.png" border="0" onclick="alert('请登入后进行游戏，谢谢~');"></a></div>
                    <div class="d6 ll"><span class="open_term1" id="gdklsf_kaijaing_qishu"><?php  echo $gdklsf_result['round']; ?></span>期&nbsp;广东快乐十分
                        <div class="t">
                            <div class="l"><span class="next_term" id="gdklsf_now_qishu">
                                    <?php
                                    //20180122-02
                                    //$gdklsf_maxresult = explode("-",$gdklsf_result['round']);
                                    $gdklsf_maxresult = explode("-",$gdklsf_result['round']);
                                    if($gdklsf_maxresult[1] == '84') {
                                        echo $gdklsf_maxresult[0]+1 .'-'.'01';
                                    } else {
                                        //echo $gdklsf_result['round'] + 1;
                                        echo $gdklsf_maxresult[0].'-'.sprintf('%02s', $gdklsf_maxresult[1]+1);
                                    }
                                    ?>
                                </span>期开奖时间：</div>
                            <div class="next_date next_date2"><span id="gdklsf_endtime">00:00</span></div>
                        </div>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="lj_game"><a onclick="addTryPlay()" href="javascript:;" class="golj_game"></a></div>
            </div>
            <div class="ball-gp">
                <div class="t " id="gdklsf_kaijiang_data"><ul class="term_ball">
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['0']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['0']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['1']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['1']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['2']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['2']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['3']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['3']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['4']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['4']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['5']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['5']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['6']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['6']; ?></span>
                        <span class="  ball_s_ ball_s_blue ball_lenght8  " title="<?php echo $gdklsf_result['number']['7']; ?>" style="display: inline-block;"><?php echo $gdklsf_result['number']['7']; ?></span></ul></div>
                <div class="d">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tbody>
                        <tr class="th_header">
                            <th colspan="3">总和</th>
                            <th>尾大小</th>
                            <th colspan="4">1-4龙虎</th>
                        </tr>
                        <tr class="td_body">
                            <td class="count" id="gdklsf_hezhi"><?php echo $gdklsf_result['numTotal']; ?></td>
                            <!--如果总和余2等于0，显示双，否则单-->
                            <td class="gray" id="gdklsf_hezhidanshuang">
                                <?php if($gdklsf_result['numTotal']% 2 == 0){ echo "双"; }else{ echo "单";} ?>
                            </td>
                            <!--如果总和大于85，显示大，否则小-->
                            <td class="gray" id="gdklsf_hezhidaxiao">
                                <?php if($gdklsf_result['numTotal']>=85){ echo "大"; }elseif ($gdklsf_result['numTotal']<85){ echo "小";} ?>
                            </td>
                            <!--如果尾数大于等于11，显示尾大，否则尾小-->
                            <td class="gray max" id="gdklsf_weidaxiao">
                                <?php  if($gdklsf_result['number']['7']>11){ echo "尾大"; }else{ echo "尾小";} ?>
                            </td>
                            <!--如果$gdklsf_result['number']['0']大于$gdklsf_result['number']['7']，显示龙，如果小于,虎-->
                            <td class="gray" id="gdklsf_longhu1">
                                <?php
                                if($gdklsf_result['number']['0']>$gdklsf_result['number']['7']){  echo "龙"; }
                                elseif($gdklsf_result['number']['0']<$gdklsf_result['number']['7']){ echo "虎";}
                                ?>
                            </td>
                            <!--如果$gdklsf_result['number']['1']大于$gdklsf_result['number']['6']，显示龙，如果小于,虎-->
                            <td class="blue" id="gdklsf_longhu2">
                                <?php
                                if($gdklsf_result['number']['1']>$gdklsf_result['number']['6']){  echo "龙"; }
                                elseif($gdklsf_result['number']['1']<$gdklsf_result['number']['6']){ echo "虎";}
                                ?>
                            </td>
                            <!--如果$gdklsf_result['number']['2']大于$gdklsf_result['number']['5']，显示龙，如果小于,虎-->
                            <td class="gray" id="gdklsf_longhu3">
                                <?php
                                if($gdklsf_result['number']['2']>$gdklsf_result['number']['5']){  echo "龙"; }
                                elseif($gdklsf_result['number']['2']<$gdklsf_result['number']['5']){ echo "虎";}
                                ?>
                            </td>
                            <!--如果$gdklsf_result['number']['3']大于$gdklsf_result['number']['4']，显示龙，如果小于,虎-->
                            <td class="blue" id="gdklsf_longhu4">
                                <?php
                                if($gdklsf_result['number']['3']>$gdklsf_result['number']['4']){  echo "龙"; }
                                elseif($gdklsf_result['number']['3']<$gdklsf_result['number']['4']){ echo "虎";}
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="g_clear"></div>
                </div>
                <div class="g_clear"></div>
            </div>
            <div class="g_clear"></div>
        </div>
        <!--广东快乐十分结束-->
        <!--重庆幸运农场-->
        <div class="item items_2 info_10010 ">
            <div class="lo"></div>
            <div class="info">
                <div>
                    <div class="t1 ll"><a href="javascript:void(0)"><img src="images/logo10010.png" border="0" onclick="alert('请登入后进行游戏，谢谢~');"></a></div>
                    <div class="d6 ll"><span class="open_term1" id="cqxync_kaijaing_qishu"><?php echo $cqxync_result['round']; ?></span>期&nbsp;重庆幸运农场
                        <div class="t">
                            <div class="l"><span class="next_term" id="cqxync_now_qishu">
                                    <?php
                                    //20180122-14
                                    $cqxync_res = explode("-",$cqxync_result['round']);
                                    if($cqxync_res[1] == '97') {
                                        echo $cqxync_res[0]+1 .'-'.'01';
                                    } else {
                                        echo $cqxync_res[0] .'-'.++$cqxync_res[1];
                                    }
                                    ?>
                                </span>期开奖时间：</div>
                            <div class="next_date next_date2"><span id="cqxync_endtime">00:00</span></div>
                        </div>
                    </div>
                    <div class="cb"></div>
                </div>
                <div class="lj_game"><a onclick="addTryPlay()" href="javascript:;" class="golj_game"></a></div>
            </div>
            <div class="ball-gp">
                <div class="t " id="cqxync_kaijiang_data"><ul class="term_ball">
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][0];?>" title="<?php echo $cqxync_result['number']['0'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][1];?>" title="<?php echo $cqxync_result['number']['1'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][2];?>" title="<?php echo $cqxync_result['number']['2'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][3];?>" title="<?php echo $cqxync_result['number']['3'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][4];?>" title="<?php echo $cqxync_result['number']['4'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][5];?>" title="<?php echo $cqxync_result['number']['5'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][6];?>" title="<?php echo $cqxync_result['number']['6'];?>" style="display: inline-block;">&nbsp;</span>
                        <span class="  ball_nc_ ball_nc<?php echo $cqxync_result['number'][7];?>" title="<?php echo $cqxync_result['number']['7'];?>" style="display: inline-block;">&nbsp;</span></ul></div>
                <div class="d">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tbody>
                        <tr class="th_header">
                            <th colspan="3">总和</th>
                            <th>尾大小</th>
                            <th colspan="4">1-4龙虎</th>
                        </tr>
                        <tr class="td_body">
                            <td class="count" id="cqxync_hezhi"><?php echo $cqxync_result['numTotal']; ?></td>
                            <td class="gray" id="cqxync_hezhidanshuang">
                                <?php if($cqxync_result['numTotal']% 2 == 0){ echo "双"; }else{ echo "单";} ?>
                            </td>
                            <td class="gray" id="cqxync_hezhidaxiao">
                                <?php if($cqxync_result['numTotal']>=85){ echo "大"; }elseif ($gdklsf_result['numTotal']<85){ echo "小";} ?>
                            </td>
                            <td class="blue max" id="cqxync_weidaxiao">
                                <?php  if($cqxync_result['number']['7']>11){ echo "尾大"; }else{ echo "尾小";} ?>
                            </td>
                            <td class="blue" id="cqxync_longhu1">
                                <?php
                                if($cqxync_result['number']['0']>$cqxync_result['number']['7']){  echo "龙"; }
                                elseif($cqxync_result['number']['0']<$cqxync_result['number']['7']){ echo "虎";}
                                ?>
                            </td>
                            <td class="gray" id="cqxync_longhu2">
                                <?php
                                if($cqxync_result['number']['1']>$cqxync_result['number']['6']){  echo "龙"; }
                                elseif($cqxync_result['number']['1']<$cqxync_result['number']['6']){ echo "虎";}
                                ?>
                            </td>
                            <td class="gray" id="cqxync_longhu3">
                                <?php
                                if($cqxync_result['number']['2']>$cqxync_result['number']['5']){  echo "龙"; }
                                elseif($cqxync_result['number']['2']<$cqxync_result['number']['5']){ echo "虎";}
                                ?>
                            </td>
                            <td class="blue" id="cqxync_longhu4">
                                <?php
                                if($cqxync_result['number']['3']>$cqxync_result['number']['4']){  echo "龙"; }
                                elseif($cqxync_result['number']['3']<$cqxync_result['number']['4']){ echo "虎";}
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="g_clear"></div>
                </div>
                <div class="g_clear"></div>
            </div>
            <div class="g_clear"></div>
        </div>
        <!--重庆幸运农场结束-->

    </div>
</div>
<!-- 主体 -结束 -->

<script type="text/javascript">
    clearTimeout(pk10_count) ;
    clearTimeout(cqssc_count) ;
    clearTimeout(gdklsf_count) ;
    clearTimeout(cqxync_count) ;
    var pk10_count ;
    var cqssc_count ;
    var gdklsf_count ;
    var cqxync_count ;
    var pk10_endtime = 0;
    var cqssc_endtime = 0;
    var gdklsf_endtime = 0;
    var cqxync_endtime = 0;

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

    function jb(ctr) {
        $.jBox("get:/app/member/tran.php?uid=<?php echo $uid?>", {
            title: "彩票额度转换",
            width: 400,
            height: "auto",
            border: 0,
            showIcon: false,
            buttons: {}
        });
    }


    // 北京PK10获取当前游戏信息，返回当前开奖奖期round，距离开奖时间endtime，是否开盘isopen
    function pk10_getroundinfo(){
        var dat = {};
        dat.game_type = 'pk10';
        dat.game_code = '51';
        $.ajax({
            type: "POST",
            dataType:"json",
            url: "../ajaxlottery.php" ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                pk10_endtime = msg.endtime ;
                $("#pk10_now_qishu").html(msg.round); // 期数
                $("#pk10_endtime").html(FormatTime(msg.endtime)); // 开奖时间

            }
        });
    }
    function pk10_cound_endtime() {
         pk10_count = setTimeout('pk10_cound_endtime()', 1000);
        if (pk10_endtime <= 0) {
            pk10_getroundinfo();
        } else {
            pk10_endtime--;
            try {
                document.getElementById('pk10_endtime').innerHTML = FormatTime(pk10_endtime);
            }catch (e) { }

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
            url: "../ajaxlottery.php" ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                cqssc_endtime = msg.endtime ;
                $("#cqssc_now_qishu").html(msg.round); // 期数
                $("#cqssc_endtime").html(FormatTime(msg.endtime)); // 开奖时间
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
            try {
                document.getElementById('cqssc_endtime').innerHTML = FormatTime(cqssc_endtime);
            }catch (e) { }

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
            url: "../ajaxlottery.php" ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                gdklsf_endtime = msg.endtime ;
                $("#gdklsf_now_qishu").html(msg.round); // 期数
                $("#gdklsf_endtime").html(FormatTime(msg.endtime)); // 开奖时间
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
            try {
                document.getElementById('gdklsf_endtime').innerHTML = FormatTime(gdklsf_endtime);
            }catch (e) { }
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
            url: "../ajaxlottery.php" ,
            data: dat,
            dataType: 'json',
            success: function(msg){
                cqxync_endtime = msg.endtime ;
                $("#cqxync_now_qishu").html(msg.round); // 期数
                $("#cqxync_endtime").html(FormatTime(msg.endtime)); // 开奖时间
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
            try {
                document.getElementById('cqxync_endtime').innerHTML = FormatTime(cqxync_endtime);
            }catch (e) { }

        }

    }

    //pk10_cound_endtime();
    //cqssc_cound_endtime();
    gdklsf_cound_endtime();
    cqxync_cound_endtime();

    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    function addTryPlay(){
        document.getElementById("demoplay").value="Yes";
        document.getElementById("ausername").value="demoguest";
        document.getElementById("apassword").value="qwertyu";
        document.getElementById("LoginForm").submit();
    }

</script>