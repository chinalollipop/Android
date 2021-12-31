<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
ini_set('display_errors','Off');

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

// 管理员登录
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];

$date_start=isset($_REQUEST['date_start'])?$_REQUEST['date_start']:'';
$date_end=isset($_REQUEST['date_end'])?$_REQUEST['date_end']:'';

if ($date_start==''){  // 默认当天
    $date_start=date('Y-m-d');
    $date_end=date('Y-m-d');
}

if($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") < 3) {

    //小于【美东时间】凌晨3点，查询历史注单报表两天前数据
    $date_end = date("Y-m-d", strtotime("-2 day"));
}else if($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") >= 3) {

    //大于【美东时间】凌晨3点,则昨天的历史报表已生成，查询历史注单报表昨天前数据
    $date_end= date("Y-m-d", strtotime("-1 day"));
}

if (strtotime($date_start)> strtotime($date_end)) {
    echo "<script>alert('开始时间不能大于结束时间!');window.history.go(-1);</script>";
    exit;
}
$currentTime = date("Y-m-d", time());
if ($date_start > $currentTime) {
    echo "<script>alert('开始时间不能大于当前时间!');window.history.go(-1);</script>";
    exit;
}

$diffdays = timediff(strtotime($date_start) , strtotime($date_end)); //间隔天数
if ($diffdays['day'] > 15) {
    echo "<script>alert('选择日期范围不允许超过15天,请重新选择!');window.history.go(-1);</script>";
    exit;
}

// 日期 比赛日期
if($date_start == date('Y-m-d') and $date_end == date('Y-m-d')) {
    $dataFlag = false;
} else{
    $dataFlag = true;
    $dates = getDateFromRange($date_start,$date_end);  // 选择的时间日期
    $hg_data = getDataBetHg($date_start, $date_end);    // 获取体育数据笔数,有效额度

    $aCp_default = $database['cpDefault'];
    $cp_data = getDataBetCp($aCp_default, $date_start, $date_end); // 获取彩票数据笔数,有效额度

    $ag_data = getDataBetAg($date_start, $date_end);  // 获取电子数据笔数,有效额度
    $resMemData = betTimeRegMem($date_start,$date_end);  //注册会员  (当前日期体育注册的会员)
}

/*体育/电子/彩票数据 数据处理日期为键*/
foreach ($hg_data as $key => $value) {
    $hg_data[$value['datetime']] = $value;
    unset($hg_data[$key]);
}
foreach ($cp_data as $key => $value) {
    $cp_data[$value['datetime']] = $value;
    unset($cp_data[$key]);
}
foreach ($ag_data as $key => $value) {
    $ag_data[$value['datetime']] = $value;
    unset($ag_data[$key]);
}
foreach($resMemData as $key => $value) {
    $resMemData[$value['datetime']] = $value;
    unset($resMemData[$key]);
}

/*数据整合，用于页面展示*/
$memdata = array();
foreach ($dates as $key => $value) {
    $memdata[$value]['datetime'] = $value;
    $memdata[$value]['count_pay'] = $hg_data[$value]['count_pay'] +  $cp_data[$value]['count_pay'] +  $ag_data[$value]['count_pay'];
    $memdata[$value]['valid_money'] = $hg_data[$value]['valid_money'] +  $cp_data[$value]['valid_money']+  $ag_data[$value]['valid_money'];
    $memdata[$value]['regMem'] = $resMemData[$value]['regMem'];
}


$otherNum = getRegSource(0); // 未知注册来源
$friendNum = getRegSource(1); // 朋友推荐
$scoreNum = getRegSource(2); // 比分网
$onlineNum = getRegSource(3); // 网络广告
$forumNum = getRegSource(4); // 论坛
$countNum = $otherNum['sourcenum']+$friendNum['sourcenum']+$scoreNum['sourcenum']+$onlineNum['sourcenum']+$forumNum['sourcenum'];

/*-------------查询函数start---------------------*/
// 统计不同来源的注册人数
function getRegSource($source) {
    global $dbLink;
    $reg_source_sql = "select count(*) as sourcenum from ".DBPREFIX.MEMBERTABLE." where Source ='$source'";
    $result = mysqli_query($dbLink, $reg_source_sql);
    $source_row = mysqli_fetch_assoc($result);
   return $source_row;
}


/**
 * 获取指定日期段内每一天的日期
 * @param  Date  $startdate 开始日期
 * @param  Date  $enddate   结束日期
 * @return Array
 */
function getDateFromRange($startdate, $enddate) {

    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);

    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;

    // 保存每天日期
    $date = array();

    for($i=0; $i<$days; $i++){
        $date[] = date('Y-m-d', $stimestamp+(86400*$i));
    }
    return $date;
}


//  统计比赛日期当天 (体育/真人视讯/电子AG/彩票) 所有会员投注笔数 ，有效投注额总数
// 统计体育历史注单笔数 ，有效投注
function getDataBetHg($date_start, $date_end) {
    global $dbLink;
    $hg_history_sql = "select sum(count_pay) as count_pay,sum(valid_money) as valid_money,M_Date as datetime from ".DBPREFIX."web_report_history_report_data where M_Date >= '".$date_start."' and M_Date<='$date_end' GROUP BY `datetime`";
//    @error_log('hg_history_report:'.$hg_history_sql.PHP_EOL,  3,  '/tmp/aaa.log');
    $res_hg = mysqli_query($dbLink, $hg_history_sql);
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg>0) {
        while($hg_search = mysqli_fetch_assoc($res_hg)){
            $hg_data[] = $hg_search;
        }
    }
    return $hg_data;
}

// 彩票主数据历史注单报表笔数 ，有效投注
function getDataBetCp($aCp_default, $date_start, $date_end) {
    $start_day_cp = strtotime($date_start)-12*3600; // 转为北京时间时间戳
    $end_day_cp = strtotime($date_end)-12*3600; // 转为北京时间时间戳
    $cp_history_sql = "select sum(count_pay) as count_pay, sum(valid_money) as valid_money,FROM_UNIXTIME(bet_time, '%Y-%m-%d') as datetime from gxfcy_history_bill_report where bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' GROUP BY datetime";
    $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
    $res_cp = mysqli_query($cpDbLink, $cp_history_sql);
    $cou_cp = mysqli_num_rows($res_cp);
    if ($cou_cp>0) {
        while($cp_search = mysqli_fetch_assoc($res_cp)){
            $cp_data[] = $cp_search;
        }
    }
    return $cp_data;
}

// 统计AG历史报表数据笔数 ，有效投注
function getDataBetAg($date_start, $date_end) {
    global $dbLink;
    $start_day_ag = date("Y-m-d 00:00:00",strtotime($date_start)); // 转为北京时间时间戳
    $end_day_ag = date("Y-m-d 23:59:59",strtotime($date_end)); // 转为北京时间时间戳
    $ag_history_sql = "select sum(count_pay) as count_pay, sum(valid_money) as valid_money,date_format(bet_time ,'%Y-%m-%d' ) as datetime from ".DBPREFIX."ag_projects_history_report where  bet_time BETWEEN '".$start_day_ag."' and '".$end_day_ag."' GROUP BY datetime ";
    $res_ag = mysqli_query($dbLink, $ag_history_sql);
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag>0) {
        while ($ag_search = mysqli_fetch_assoc($res_ag)){
            $ag_data[] = $ag_search;
        }
    }
    return $ag_data;
}

// 注册会员
function betTimeRegMem($date_start, $date_end) {
    global $dbLink;
    $start_regTime = date("Y-m-d 00:00:00",strtotime($date_start)); // 开始时间
    $end_regTime = date("Y-m-d 23:59:59",strtotime($date_end)); // 结束时间
    $mem_reg_sql = "select count(*) as regMem,date_format(AddDate ,'%Y-%m-%d') as datetime from  ".DBPREFIX.MEMBERTABLE." where AddDate BETWEEN '".$start_regTime."' and '".$end_regTime."' GROUP BY datetime";
    $res_mem = mysqli_query($dbLink, $mem_reg_sql);
    $cou_mem = mysqli_num_rows($res_mem);
    if ($cou_mem>0) {
        while ($resMemRow = mysqli_fetch_assoc($res_mem)){
            $resMemData[] = $resMemRow;
        }
    }
    return $resMemData;
}

// 获取当前日期注册的存款会员数量
function getResDepositMem($dateTime) {
    global $dbLink;
    $startDepTime = date("Y-m-d 00:00:00",strtotime($dateTime)); // 开始时间
    $endDepTime = date("Y-m-d 23:59:59",strtotime($dateTime)); // 结束时间
    // 当天注册的会员 （98,99,100）
    $reg_depmem_sql = "select ID,UserName from ".DBPREFIX.MEMBERTABLE." where AddDate BETWEEN '$startDepTime' and '$endDepTime'";
    $result = mysqli_query($dbLink, $reg_depmem_sql);
    while ($getResRow = mysqli_fetch_assoc($result)){
        $getResIds[] = $getResRow['ID']; // 当天注册会员id
    }

    $memIdArray = implode(',', $getResIds);
    $deposit_sql = "select count(distinct userid) as userid from ".DBPREFIX."web_sys800_data where Type='S' AND Checked=1 AND AddDate BETWEEN '$startDepTime' and '$endDepTime' AND userid IN($memIdArray)";
    $depResult = mysqli_query($dbLink, $deposit_sql);
    $depMemRow = mysqli_fetch_assoc($depResult);
    return $depMemRow['userid'];
}

/* 获取查询时间月份*/
function timediff($begin_time, $end_time) {
    if ( $begin_time < $end_time ) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval( $timediff / 86400 );
    $remain = $timediff % 86400;
    $hours = intval( $remain / 3600 );
    $remain = $remain % 3600;
    $mins = intval( $remain / 60 );
    $secs = $remain % 60;
    $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
    return $res;
}
/*-------------查询函数end---------------------*/
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>有效会员</title>
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        input{min-height: auto;}
        input.za_text_auto {width: 110px;}
    </style>
</head>
<!--meta http-equiv="refresh" content="30; url=online.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>$lv=M"-->
<body onLoad="onLoad()";>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>有效会员</dt>
        <dd>
            <table >
                <tr class="m_tline" >
                    <td>
                        操作时间
                        <input type="text" class="za_text_auto" name="date_start" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $date_start;?>" readonly/>~~
                        <input type="text" class="za_text_auto" name="date_end" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $date_end;?>" readonly/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="za_button" type="submit" name="Submit" value="查询">
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
    <div class="main-ui">
        <table class="m_tab" style="width: 1200px">
            <tr  class="m_title">
                <td width="180">日期</td>
                <!--<td width="">会员数</td>-->
                <td width="">笔数</td>
                <td width="">有效额度</td>
                <td width="">注册会员</td>
                <td width="">存款会员</td>
            </tr>
            <?php if ($dataFlag == false){ ?>
                <tr class="m_cen">
                    <td colspan="13">暂无数据,请选择时间查询</td>
                </tr> <?php }else{ ?>
            <?php
                foreach($memdata as $key => $value) {

            ?>
                <tr class="m_cen" onMouseOut="this.style.backgroundColor=''" onMouseOver="this.style.backgroundColor='#BFDFFF'" bgcolor="#FFFFFF">
                    <td><?php echo $value["datetime"] ?></td>
                    <td><?php echo $value["count_pay"] ?></td>
                    <td><?php echo $value["valid_money"]>0 ? sprintf("%.2f",$value["valid_money"]):0 ?></td>
                    <td><?php echo $value["regMem"]>0 ? $value["regMem"]:0 ?></td>
                    <td><?php
                        // 这一天注册会员大于零，当天注册会员的充值人数)
                        if($value["regMem"] > 0){
                            echo getResDepositMem($value["datetime"]);
                        }else{
                            echo '0';
                        }
                        ?></td>
                </tr>
            <?php }
            } ?>
        </table>
        <br><br>
        <table class="m_tab" style="width: 500px">
            <tr  class="m_title"  width="80">
                <td width="20">项目</td>
                <td width="20">人数</td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20"></td>
                <td width="20"><?php echo $countNum;?></td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20">朋友推荐</td>
                <td width="20"><?php echo $friendNum['sourcenum'];?></td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20">比分网</td>
                <td width="20"><?php echo $scoreNum['sourcenum'];?></td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20">网络广告</td>
                <td width="20"><?php echo $onlineNum['sourcenum'];?></td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20">论坛</td>
                <td width="20"><?php echo $forumNum['sourcenum'];?></td>
            </tr>
            <tr  class="m_title"  width="80">
                <td width="20">其他</td>
                <td width="20"><?php echo $otherNum['sourcenum'];?></td>
            </tr>
        </table>
    </div>

</form>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script>

</script>
</body>
</html>