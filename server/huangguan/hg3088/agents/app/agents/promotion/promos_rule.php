<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");

include_once "../../../../common/promosCommon.php";

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$type = $_REQUEST["type"]; // 活动标识
$title = $_REQUEST["title"]; // 活动标题
$uid = $_SESSION["Oid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];

$gameType = returnGameType();


?>
<html>
<head>
    <title>优惠活动规则</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .main-ui{width: 98%;font-size: 14px;padding: 0 10px;}
        .flex{display: -webkit-flex;display: flex;}
        .list{border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;}
        .list>span, .list>div{padding: 5px;line-height: 25px;}
        .list .title{width:110px;border-right: 1px solid #ccc;}
        .list .li>div {margin-right: 15px;}
        input.za_text_auto {width: 205px;}
        .btn {text-align: center;margin: 15px auto 5px;}
        .btn a{padding: 5px 15px;}
    </style>
</head>
<body >

<div class="main-ui all width_1000">
    <div class="list flex">
        <span class="title"> 活动信息 </span>
        <div class="li flex">
            <div class=""> <span> 活动标题： </span> <span> <?php echo $title;?> </span> </div>
            <div class=""> <span> 活动标识： </span> <span> <?php echo $type;?> </span> </div>
            <div class=""> <span> 主导项： </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promotj"> 活动统计时间 </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promolq"> 活动领取时间 </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promock"> 存款范围 </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promoday"> 存款天数 </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promoyxtz"> 有效投注 </span>
                <span> <input type="radio" class="promoKeys" name="promoKeys" value="promofyl"> 负盈利 </span>
            </div>
        </div>

    </div>
    <div class="list flex">
        <span class="title"> 活动统计时间 </span>
        <div class="li flex">
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="lastMon"><span> 上月 </span> </div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="thisMon"><span> 本月 </span></div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="lastWeek"><span> 上周 </span>  </div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="thisWeek"><span> 本周 </span>  </div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="yesterday"><span> 昨天 </span>  </div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="today"><span> 当天 </span>  </div>
            <div class=""> <input type="radio" class="promoTjDate" name="promoTjDate" value="other"><span> 其他时间 </span><input type="text" class="promoTjDateInput za_text_auto" name="promoTjDateInput" placeholder="以英文 ; 分隔，如 08;18;28"> <span class="red"> 如每月的06/16/26 ，填写06;16;26 </span></div>
        </div>
    </div>
    <div class="list flex">
        <span class="title"> 活动领取时间 </span>
        <div class="li flex">
            <div class=""> <input type="radio" class="promoLqDate" name="promoLqDate" value="today"><span> 当天 </span>  </div>
            <div class=""> <input type="radio" class="promoLqDate" name="promoLqDate" value="week"><span> 当周 </span><input type="text" class="w_promoLqDateInput za_text_auto" name="w_promoLqDateInput" placeholder="以英文 ; 分隔，如 0;1;2;3" style="width: 170px;"> <br> <p class="red"> 0 代表周日，1 代表周一，以此类推</p> </div>
            <div class=""> <input type="radio" class="promoLqDate" name="promoLqDate" value="month"><span> 当月 </span><input type="text" class="m_promoLqDateInput za_text_auto" name="m_promoLqDateInput" placeholder="以英文 ; 分隔，如 01;02;03..."> </div>
            <div class="">
                <span> 时间 </span><input type="text" class="promoLqDateTime za_text_auto" name="promoLqDateTime" value="00-24" placeholder="如 00-24 点" style="width: 80px;"> <br>
                <p class="red"> 如：填写 01-22</p>
            </div>
            <div class="">
                <span> 时间提示 </span><input type="text" class="promoLqDateTimeTip za_text_auto" name="promoLqDateTimeTip" > <br>
                <p class="red"> 如 请于美东时间每月1号，00到24点申请活动</p>
            </div>
        </div>
    </div>
    <div class="list flex">
        <span class="title"> 存款条件 </span>
        <div class="li flex">
            <div class=""> <span> 存款方式 </span>
                <select name="Payway" class="Payway za_select_auto">
                    <option value="ALL"> 全部 </option>
                    <option value="O"> 存款优惠 </option>
                    <option value="N"> 公司入款 </option>
                    <option value="U"> USDT入款 </option>
                    <option value="W"> 在线存款 </option>
                    <option value="A"> 代理佣金 </option>
                    <option value="G"> 人工增加彩金 </option>
                </select>
            </div>
            <div class=""><span> 类型 </span>
                <select name="discounType" class="discounType za_select_auto">
                    <option value="0">全部</option>
                    <option value="1">未知类型</option>
                    <option value="2">在线入款掉单补单</option>
                    <option value="3">周周返点补单</option>
                    <option value="4">优惠</option>
                    <option value="5">公司入款补单</option>
                    <option value="6">手工提出</option>
                    <option value="7">手工存入</option>
                    <option value="8">AG掉单存入提出</option>
                    <option value="9">快速充值存入</option>
                </select>
            </div>
            <div class=""> <span> 存款范围 </span><input type="text" class="promoDeposit za_text_auto" name="promoDeposit" placeholder="存款范围，如 188;288;588"> <br> <p class="red"> 如填写188;288 代表 >=188, >=288</p> </div>
            <div class=""> <span> 存款天数 </span><input type="text" class="promoDepositDay za_text_auto" name="promoDepositDay" placeholder="存款天数，如 10;20;21"> <br> <p class="red"> 如填写10;20;21 代表 >=10, 20，>=21</p> </div>
            <div class=""> <span> 当日第一笔</span><input type="text" class="promoDepositDayFirst za_text_auto" name="promoDepositDayFirst" placeholder="是否只统计第一笔，是1 默认0"> <br> <p class="red"> 如填写1代表统计当日第一笔 , 0 所有存款</p> </div>

        </div>
    </div>
    <div class="list flex">
        <span class="title"> 有效投注(打码量) </span>
        <div class="li flex">
            <div class=""> <span> 金额 </span><input style="width: 320px" type="text" class="promoValid za_text_auto" name="promoValid" placeholder="以英文 ; 分隔，如 1000;3000;8000"> <br> <p class="red"> 1000;3000 代表 >=1000, >=3000</p></div>
            <div class=""> <span> 派送金额 </span><input style="width: 320px" type="text" class="promoBonus za_text_auto" name="promoBonus" placeholder="以英文 ; 分隔，如 18;28-38-58;78"> <br> <p class="red"> (;代表竖向，-代表横向)18;78 代表奖金18,78, 28-38-58代表奖金分类三种，如各代表五分彩28，三分彩38，分分彩58</p></div>
            <div class=""> <span> usdt派送金额 </span><input style="width: 320px" type="text" class="usdtBonus za_text_auto" name="usdtBonus" placeholder="以英文 ; 分隔，如 18;28-38-58;78"> <br></div>
        </div>
    </div>
    <div class="list flex">
        <span class="title"> 负盈利 </span>
        <div class="li flex">
            <div class=""> <span> 金额 </span><input style="width: 320px" type="text" class="promoProfitable za_text_auto" name="promoProfitable" placeholder="以英文 ; 分隔，如 1000;3000;8000"> <br> <p class="red"> 1000;3000 代表 >=1000, >=3000</p></div>
        </div>
    </div>
    <div class="list flex">
        <span class="title"> 统计游戏 </span>
        <div class="li flex">
            <div class="">
                <span> 单独统计： </span>
                <input type="radio" class="mergeOrSplit" name="mergeOrSplit" value="not" checked><span> 否 </span>
                <input type="radio" class="mergeOrSplit" name="mergeOrSplit" value="yes"><span> 是 </span>
            </div>
            <div class=""> <span> 游戏 </span>
                <select name="gameType" class="gameType za_select_auto">
                    <?php
                        foreach ($gameType as $k=>$v){
                            echo "<option value='$k'> $v </option>";
                        }
                    ?>
                </select>
            </div>
            <!-- 选择种类 -->
            <div class="">
                <span> 种类 </span>
                <input style="width: 310px" type="text" class="gameTypeDetail za_text_auto" name="gameTypeDetail" placeholder="以英文 ; 分隔" value="all">
                <span>
                    <input type="radio" class="gameTypeChoose" name="gameTypeChoose" value="main" checked><span> 正选 </span>
                    <input type="radio" class="gameTypeChoose" name="gameTypeChoose" value="over" ><span> 反选 </span> <span class="red">正选-包含所填游戏，反选-除去所填游戏</span>
                </span>
                <br>
                <p class="red">
                    ( 以英文 ; 分隔，全部-all，体育-sport，捕鱼-bygame，棋牌-chess，电子-game，电竞-gaming，彩票-lottery ),<br>
                    ( 体育：皇冠体育-hgsport，BBIN体育-bbinsport ),<br>
                    ( 视讯：AG视讯-aglive，BBIN视讯-bbinlive，OG视讯-oglive ),<br>
                    ( 捕鱼：AG捕鱼-agby，BBIN捕鱼-bbinby ),<br>
                    ( 棋牌：开元棋牌-kyqp，乐游棋牌-lyqp，VG棋牌-vgqp，皇冠棋牌-hgqp，快乐棋牌-klqp ),<br>
                    ( 电子：AG电子-aggame，MG电子-mggame，CQ9电子-cqgame，MW电子-mwgame，FG电子-fggame ),<br>
                    ( 电竞：泛亚电竞-avia，雷火电竞-fire ),<br>
                    ( <br>
                    彩票：BBIN彩票-bbinlottery,<br>
                    皇冠彩票-hglottery,<br>
                    (<br>
                    分分彩系列-ffcSeries,三分彩系列-sfcSeries,五分彩系列-wfcSeries,欢乐生肖-cqssc,北京赛车-bjsc,幸运农场-xync,<br>
                    江苏快三-jsks,广东快乐十分-gdklsf,PC蛋蛋-pcdd,香港六合彩-xglhc,极速赛车-jssc,分分时时彩-ffc,二分时时彩-efc,三分时时彩-sfc,<br>
                    五分时时彩-wfc,极速(分分)快三-jsjsks,幸运飞艇-xyft,极速飞艇-jsft
                    ),<br>
                    (国民:北京赛车五分彩-bjpk105fc,新重庆时时彩-xcqssc,五分快三-jsk35fc,三分快三-jsk33fc,极速3D-gw3d,极速六合彩-jslhc)<br>
                    )<br>
                    )
                </p>
            </div>

        </div>
    </div>

    <div class="btn">
        <a href="javascript:;" class="za_button btnSubmit"> 确定 </a>
    </div>

</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var pro_type = '<?php echo $type;?>';
    var pro_title = '<?php echo $title;?>';
    var parFirst = {action :'get',type:pro_type};

    var $promoTjDateInput = $('.promoTjDateInput'); // 统计时间
    var $w_promoLqDateInput = $('.w_promoLqDateInput'); // 领取日期-周
    var $m_promoLqDateInput = $('.m_promoLqDateInput'); // 领取日期-月
    var $promoLqDateTime = $('.promoLqDateTime'); // 领取时间
    var $promoLqDateTimeTip = $('.promoLqDateTimeTip'); // 领取时间前端提示
    var $Payway = $('.Payway'); // 存款方式
    var $discounType = $('.discounType'); // 存款类型
    var $promoDeposit = $('.promoDeposit'); // 存款范围
    var $promoDepositDay = $('.promoDepositDay'); // 存款天数
    var $promoDepositDayFirst = $('.promoDepositDayFirst'); // 是否统计当日第一笔
    var $promoValid = $('.promoValid'); // 有效投注
    var $promoBonus = $('.promoBonus'); // 派送金额
    var $usdtBonus = $('.usdtBonus'); // 派送金额
    var $promoProfitable = $('.promoProfitable'); // 负盈利
    var $gameType = $('.gameType'); // 统计游戏
    var $gameTypeDetail = $('.gameTypeDetail'); // 统计游戏种类

    promosGetApi(parFirst);
    setPromoAction();

    /*
    *  提交设置
    * */
    function setPromoAction() {
        $('.btnSubmit').off().on('click',function () {
            var promokeys = $('input[name="promoKeys"]:checked').val(); // 主导
            var promotjdate = $('input[name="promoTjDate"]:checked').val(); // 统计时间
            var promoTjDateInput = $promoTjDateInput.val(); // 统计时间
            var promolqdate = $('input[name="promoLqDate"]:checked').val(); // 领取日期
            var promolqdatesec = ''; // 领取日期
            var w_promoLqDateInput = $w_promoLqDateInput.val(); // 领取日期-周
            var m_promoLqDateInput = $m_promoLqDateInput.val(); // 领取日期-月
            var promolqdatetime = $promoLqDateTime.val(); // 领取时间
            var promolqdatetimetip = $promoLqDateTimeTip.val(); // 领取时间前端提示
            var payway = $Payway.val(); // 存款方式
            var discountype = $discounType.val(); // 存款类型
            var promodeposit = $promoDeposit.val(); // 存款范围
            var promodepositday = $promoDepositDay.val(); // 存款天数
            var promodepositdayfirst = $promoDepositDayFirst.val(); // 是否第一笔存款
            var promovalid = $promoValid.val(); // 有效投注
            var promobonus = $promoBonus.val(); // 派送金额
            var usdtbonus = $usdtBonus.val(); // usdt派送金额
            var promoprofitable = $promoProfitable.val(); // 负盈利
            var gametype = $gameType.val(); // 统计游戏
            var gametypedetail = $gameTypeDetail.val(); // 统计游戏种类
            var gametypechoose = $('input[name="gameTypeChoose"]:checked').val(); // 统计游戏种类
            var mergeorsplit = $('input[name="mergeOrSplit"]:checked').val(); // 是否单独统计游戏

           // console.log(promotjdate+'=='+promolqdate+'=='+promolqdatetime)
            if(!promokeys){
                layer.msg('请选择主导选项',{time:alertComTime,shade: [0.2, '#000']});
                return ;
            }
            if(!promotjdate || (promotjdate=='other' && promoTjDateInput=='')){
                layer.msg('请选择或填写活动统计日期',{time:alertComTime,shade: [0.2, '#000']});
                return ;
            }
            if(promotjdate !='other'){
                promoTjDateInput ='';
            }
            if(!promolqdate || (promolqdate=='week' && w_promoLqDateInput=='') || (promolqdate=='month' && m_promoLqDateInput=='')){
                layer.msg('请选择或填写活动领取日期',{time:alertComTime,shade: [0.2, '#000']});
                return ;
            }
            if(promolqdate=='week'){
                promolqdatesec = w_promoLqDateInput;
            }else if(promolqdate=='month'){
                promolqdatesec = m_promoLqDateInput;
            }

            if(!promolqdatetime){
                layer.msg('请选择或填写活动领取时间',{time:alertComTime,shade: [0.2, '#000']});
                return ;
            }

            if(!promolqdatetimetip){
                layer.msg('请填写前端领取时间限制提示',{time:alertComTime,shade: [0.2, '#000']});
                return ;
            }

            var dataParams = {
                action: 'set',
                type: pro_type,
                title: pro_title,
                promokey: promokeys,
                promotjdate: promotjdate,
                promotjdatesec: promoTjDateInput,
                promolqdate: promolqdate,
                promolqdatesec: promolqdatesec,
                promolqdatetime: promolqdatetime,
                promolqdatetimetip: promolqdatetimetip,
                payway: payway,
                discountype: discountype,
                promodeposit: promodeposit,
                promodepositday: promodepositday,
                promodepositdayfirst: promodepositdayfirst,
                promovalid: promovalid,
                promobonus: promobonus,
                usdtbonus: usdtbonus,
                promoprofitable: promoprofitable,
                gametype: gametype,
                gametypedetail: gametypedetail,
                gametypechoose: gametypechoose,
                mergeorsplit: mergeorsplit
            };
            promosGetApi(dataParams);
        })
    }

    /*
    * 接口
    * act:set 设置信息，get 获取信息
    * */
    function promosGetApi(params) {
        var url = '/api/promoSettingApi.php';
        $.ajax({
            type: 'POST',
            url:url,
            data:params,
            dataType:'json',
            success:function(res){
                var str = '';
                if(res){ // 有结果返回
                    layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                    if(params.action=='get'){ // 查询
                        if(res.data[0]){
                            var af_res = res.data[0];
                            $('input[name="promoKeys"]').each(function(){
                                if($(this).val()==af_res.leader){
                                    $(this).attr('checked', 'checked');
                                }
                            });
                            $('input[name="promoTjDate"]').each(function(){
                               if($(this).val()==af_res.statisticsDayType){
                                    $(this).attr('checked', 'checked');
                               }
                            });
                            $promoTjDateInput.val(af_res.statisticsDay);
                            $('input[name="promoLqDate"]').each(function(){
                                if($(this).val()==af_res.receiveDayType){
                                    $(this).attr('checked', 'checked');
                                    $(this).next().next().val(af_res.receiveDay);
                                }
                            });
                            $promoLqDateTime.val(af_res.receiveTime);
                            $promoLqDateTimeTip.val(af_res.promolqDatetimeTip);
                            $Payway.find('option').each(function(){
                                if($(this).val()==af_res.Payway){
                                    $(this).attr('selected', 'selected');
                                }
                            });
                            $discounType.find('option').each(function(){
                                if($(this).val()==af_res.discounType){
                                    $(this).attr('selected', 'selected');
                                }
                            });
                            $promoDeposit.val(af_res.depositLimits);
                            $promoDepositDay.val(af_res.depositDays);
                            $promoDepositDayFirst.val(af_res.depositDaysFirst);
                            $promoValid.val(af_res.validBet);
                            $promoBonus.val(af_res.bonus);
                            $usdtBonus.val(af_res.usdtbonus);
                            $promoProfitable.val(af_res.profitable);

                            $gameType.find('option').each(function(){
                                if($(this).val()==af_res.gameType){
                                    $(this).attr('selected', 'selected');
                                }
                            });
                            $gameTypeDetail.val(af_res.gameTypeDetails);

                            $('input[name="gameTypeChoose"]').each(function(){
                                if($(this).val()==af_res.gameTypeChoose){
                                    $(this).attr('checked', 'checked');
                                }
                            });
                            $('input[name="mergeOrSplit"]').each(function(){
                                if($(this).val()==af_res.mergeOrSplit){
                                    $(this).attr('checked', 'checked');
                                }
                            });

                        }

                    }
                }

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });
    }



</script>
</body>
</html>