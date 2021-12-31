<?php
session_start();
include("../include/address.mem.php");
require_once("../include/config.inc.php");
require("../include/define_function_list.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];

$seconds=$_REQUEST["seconds"];
$datatime=date('Y-m-d H:i:s');
if ($seconds==''){
    $seconds=180;
}
$page=$_REQUEST['page'];
if ($page==''){
    $page=0;
}

$redisObj = new Ciredis();
$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ;
$prefix = $datajson['agentid'] . '_'; // 查询用户名时加彩票平台前缀


// 根据条件查询报表（会员名称、投注时间、游戏名称）
$result_type = $_REQUEST['result_type'] ? $_REQUEST['result_type'] : ''; // 查询彩种类型
$thirdUserName = $_REQUEST['username'] ? $_REQUEST['username'] : '';
$prefixthirdUserName = $prefix . $thirdUserName;
//$date_start = $_REQUEST['bettime'] ? $_REQUEST['bettime'] : date('Y-m-d');
//$date_start = strtotime($date_start);
$sWhere = 1;
//$thirdUserName != '' ? $sWhere .= " AND `username` = '$prefixthirdUserName' " : '';   // 用户名
$thirdUserName != '' ? $sWhere .= " AND `username` in ('$thirdUserName' , '$prefixthirdUserName') " : '';   // 用户名
$result_type != '' ? $sWhere .= " AND `type` = '$result_type'" : '';    // 彩种


$date_s=$_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$date_e=$_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
if ($date_s==''){
    /*$date_s=strtotime(date('Y-m-d 00:00:00'));
    $date_e=strtotime(date('Y-m-d 23:59:59', time()));
    $sWhere .=" and actionTime between '{$date_s}' and '{$date_e}'";
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 23:59:59', time());*/

    $sWhere .=" and counted_at between '{$date_s}' and '{$date_e}'";
}else{
    /*$date_s=strtotime($date_s);
    $date_e=strtotime($date_e);
    $sWhere .=" and actionTime between '{$date_s}' and '{$date_e}'";
    $date_s=date('Y-m-d 00:00:00', $date_s);
    $date_e=date('Y-m-d 23:59:59', $date_e);*/

    $sWhere .=" and counted_at between '{$date_s}' and '{$date_e}'";
}

$round = $_REQUEST['actionNo'];  //期号
$round != '' ? $sWhere .= " AND `actionNo` = '$round'" : '';

$wjorderId = $_REQUEST['wjorderId'];  //注单号
$wjorderId !='' ? $sWhere .= " AND `wjorderId` like '%$wjorderId%'" : '';


$mysql = "SELECT `id`,`wjorderId`,`uid`,`username`,`type`,`playedGroup`,`playedId`,`Groupname`,`actionNo`,`actionData`,`actionTime`,`odds`,`rebate`,`rebateMoney`,`fanDian`,`fanDianAmount`,`bonus`,`money`,`lotteryNo`,`kjTime`,`zjCount`,`flag`,`isDelete`,`orderId`,`totalNums`,`totalMoney`,`betInfo`,`status`,`won_count`,`counted_at`,`status_bonus`,`locked_bonus`,`bonus_sent_at`,`status_rebate`,`created_at`,`updated_at`,`is_tester` 
          FROM `" . DBPREFIX . "web_third_ssc_data`
          WHERE $sWhere 
          ORDER BY `id` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$mem_total_money=array();
while($row=@mysqli_fetch_assoc($result)){
    $mem_total_money['money'] += $row['money'];
    $mem_total_money['bonus'] += $row['bonus'];
    $mem_total_money['user_win'] += $row['bonus'];
}
//}
$page_size=50;
$page_count=ceil($count/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size";
$result = mysqli_query($dbLink, $mysql);

//echo '<pre>';
//echo $mysql;
//echo '</pre>';

?>
    <html>
    <head>
        <title>第三方彩票</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gbk">
        <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">
            #myFORM td{ padding: 3.5px 0 0  8px;}
            .mem_total_money td span{ color:red;}
            input.za_text {width: 142px;}
        </style>
    </head>
    <body >
    <form id="myFORM" action="" method=post name="myFORM" >
        <dl class="main-nav">
            <dt>第三方彩票信用盘</dt>
            <dd>
                <table >
                    <tr>
                        <td>
                            <select name="result_type" id="result_type" onchange="self.myFORM.submit()">
                                <option value="">全部</option>
                                <?php
                                foreach($lotteries_name['ssc'] as $key => $value) { ?>
                                      <option value="<?php echo $key?>" <?php if ($result_type==$key) echo 'selected';?> ><?php echo $value?></option>
                                }
                               <?php } ?>
                            </select>
                            期数：<input type=TEXT name="actionNo" size=10 value="<?php echo $round;?>" maxlength=20>
                            注单号：<input type=TEXT name="wjorderId" size=10 value="<?php echo $wjorderId;?>" maxlength=20 class="za_text">
                            注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                            至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                            会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $thirdUserName;?>" maxlength=20 >
                            <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                            共<?php echo $count?>条
                            <select name='page' onChange="self.myFORM.submit()">
                                <?php
                                if ($page_count==0){
                                    $page_count=1;
                                }
                                for($i=0;$i<$page_count;$i++){
                                    if ($i==$page){
                                        echo "<option selected value='$i'>".($i+1)."</option>";
                                    }else{
                                        echo "<option value='$i'>".($i+1)."</option>";
                                    }
                                }
                                ?>
                            </select> 共<?php echo $page_count?> 页
                        </td>
                    </tr>
                </table>
            </dd>
        </dl>
    </form>

    <div class="main-ui">
        <table class="m_tab">

            <tr class="mem_total_money">
                <td colspan="15">
                    总投注总额：<span><?php echo ($mem_total_money['money']>0)?$mem_total_money['money']:'0';?></span>&nbsp;&nbsp;
                    总中奖金额：<span><?php echo ($mem_total_money['bonus']>0)?$mem_total_money['bonus']:'0';?></span>&nbsp;&nbsp;
                    <?php
                    if($thirdUserName && $thirdUserName !=''){
                        //echo ' 会员结果总额：<span>'.round($mem_total_money['money']-$mem_total_money['bonus'],1).'</span>';
                        $mem_profit = $mem_total_money['money']-$mem_total_money['bonus'];
                        if($mem_profit < 0 ) { //小于0 会员输 页面显示黑色 黑色不显示负数
                            echo ' 会员结果总额：<span><font color=black>'.abs(sprintf("%01.2f", $mem_profit)).'</font></span>';
                        } else if($mem_profit > 0 ) { //大于0 会员赢 页面显示红色 红色显示负数
                             echo ' 会员结果总额：<span>'.-sprintf("%01.2f", $mem_profit).'</span>';
                        }
                    }
                    ?>

                </td>
            </tr>

            <tr class="m_title">
                <td align="center">ID</td>
                <td align="center">用户名</td>
                <td align="center">彩种</td>
                <td align="center">期号</td>
                <td align="center">玩法组</td>
                <td align="center">投注号码</td>
                <td align="center">投注金额</td>
                <td align="center">赔率</td>
                <td align="center">中奖金额</td>
                <td align="center">开奖号码</td>
                <td align="center">投注时间</td>
                <td align="center">开奖时间</td>
                <td align="center">注单号</td>
                <td align="center">状态</td>
            </tr>
            <?php

            if($count == 0){
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            }

            while($row=@mysqli_fetch_assoc($result)){
                //$row['bonus']=number_format($row['bonus'],2);

                ?>
                <tr class="m_rig" onmouseover=sbar(this) onmouseout=cbar(this)>
                    <td align="center"><?php echo $row['id']?></td>
                    <td align="center"><?php echo prefixAccountThird($row['username'])?></td>
                    <td align="center"><?php echo $lotteries_name['ssc'][$row['type']] ?></td>
                    <td align="center"><?php echo $row['actionNo']?></td>
                    <td align="center"><?php echo $row['Groupname']?></td>
                    <td align="center"><?php echo $row['actionData']?></td>
                    <td align="center" <?php if ($row['zjCount']>0){ ?> style="color: red;" <?php } ?>><?php echo sprintf("%01.2f", $row['money']) ?></td>
                    <td align="center" <?php if ($row['zjCount']>0){ ?> style="color: red;" <?php } ?>><?php echo $row['odds']?></td>
                    <td align="center" <?php if ($row['zjCount']>0){ ?> style="color: red;" <?php } ?>><?php echo sprintf("%01.3f", $row['bonus']) ?></td>
                    <td align="center"><?php echo $row['lotteryNo']?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$row['actionTime'])?></td>
                    <!--<td align="center"><?php /*echo $row['counted_at']*/?></td>-->
                    <td align="center"><?php echo date('Y-m-d H:i:s',$row['kjTime'])?></td>
                    <td align="center"><?php echo $row['wjorderId']?></td>
                    <td align="center"><?php echo $row['won_count'] > 0 ? "已中奖" :"未中奖";?></td>

                    <!--<td align="center"><?php /*echo number_format($row['money'],2)*/?></td>-->
                    <!--<td align="center" <?php /*if ($row['bonus']<=0){ */?>style="color: red;"<?php /*} */?>><?php /*echo $row['bonus']*/?></td>-->
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    </body>
    <script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
    <script type="text/javascript">
        function sbar(st){
            st.style.backgroundColor='#BFDFFF';
        }
        function cbar(st){
            st.style.backgroundColor='';
        }

    </script>
    </html>
