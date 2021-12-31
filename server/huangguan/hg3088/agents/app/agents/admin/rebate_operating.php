<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$loginname = $_SESSION['UserName'];
$action = $_REQUEST['action'];

// 手动退水：一般处理时间在每天下午3点-3点30分后，处理前一天的退水
$now_hour = date('H');
if ($now_hour < 3 && $now_hour > 12 ){
    die("稍候再来吧，退水数据暂未生成~~");
}
$rebate_date = date('Y-m-d', strtotime('-1 days'));

// 手动批量退水
if( $action == 'rebate_2_users' ){


    // 判断会员是否重复
    $data_all = json_decode(stripslashes($_REQUEST['dataall']),true);
    $username_list = array_column($data_all, 'username');

    if(count($username_list) != count(array_unique($username_list))){
        die(json_encode(["err"=>-1,"msg"=>"会员名重复，请检查数据后再提交"]));
    }

    error_log(date('Y-m-d H:i:s').'-----------------返水开始---------------'.PHP_EOL, 3, '/tmp/group/rebate_operating.log');


    // 首先根据会员名称、返水金额，查询返水数据表进行检查匹配，
    // 然后匹配后更新会员资金（会员加锁-》更新资金）
    // 账变记录
    // 最后更新返水状态
    //
    $num = 0; // 返水个数
    $R_total = 0;
    foreach ( $data_all as $k => $v ) {

        $result=mysqli_query($dbMasterLink, "START TRANSACTION");
        if (!$result) {
            die('事务开启失败！ ' . mysqli_error($dbMasterLink));
        }

        // 先锁定当前返水记录的状态，防止并发重复加钱
        $lockStatus =mysqli_query($dbMasterLink, "select status from " . DBPREFIX . "rebate_history_report where userid = '" . $v['user_id'] . "' and R_date = '" . $rebate_date . "' for update ");
        if ($lockStatus) {
            $aStatus = mysqli_fetch_assoc($lockStatus);

            if($aStatus['status']==0){
                // 添加资金锁
                $lockMoney = mysqli_query($dbMasterLink, "select ID,test_flag, Agents, World, Corprator, Super, Admin,Alias,Phone,Money from " . DBPREFIX.MEMBERTABLE." WHERE ID = '{$v['user_id']}' for update ");
                if ($lockMoney){

                    $aUser = mysqli_fetch_assoc($lockMoney);
                    $moneyf = $aUser['Money'];
                    $currency_after = $aUser['Money'] + $v['R_total'];
                    // 更新会员资金
                    $result = mysqli_query($dbMasterLink, "update " . DBPREFIX.MEMBERTABLE." set Money=Money + " . $v['R_total'] . " where ID = '" . $v['user_id'] . "' ");
                    if ($result) {

                        $oDatetime = new DateTime('NOW');
                        $sTime8 = dechex($oDatetime->format('U')); // 8bit
                        $sUser6 = sprintf("%06s", substr(dechex($aUser['ID']), 0, 6)); // 6bit
                        $sTrans_no = 'REBATE' . $sTime8 . $sUser6; //AG平台 订单号生成规则

                        $data['userid'] = $v['user_id'];
                        $data['Checked'] = 1;
                        $data['Payway'] = 'R'; // Rebate
                        $data['reason'] = '返水';
                        $data['AuditDate'] = date("Y-m-d H:i:s");
                        $data['Gold'] = $v['R_total'];
                        $data['moneyf'] = $moneyf;
                        $data['currency_after'] = $currency_after;
                        $data['AddDate'] = date("Y-m-d", time());
                        $data['Type'] = 'R';
                        $data['UserName'] = $v['username'];
                        $data['Agents'] = $aUser['Agents'];
                        $data['World'] = $aUser['World'];
                        $data['Corprator'] = $aUser['Corprator'];
                        $data['Super'] = $aUser['Super'];
                        $data['Admin'] = $aUser['Admin'];
                        $data['CurType'] = 'RMB';
                        $data['Date'] = date("Y-m-d H:i:s", time());
                        $data['Name'] = $aUser['Alias'];
                        $data['Waterno'] = '';
                        $data['Phone'] = $aUser['Phone'];
                        $data['Notes'] = '天天返水';
                        $data['test_flag'] = $aUser['test_flag'];
                        $data['Order_Code'] = $sTrans_no;

                        $sInsData = '';
                        foreach ($data as $key => $value) {
                            if ($key == 'Order_Code') {
                                $sInsData .= "`$key` = '{$value}'";
                            } else {
                                $sInsData .= "`$key` = '{$value}',";
                            }
                        }

                        // 插入返水记录
                        $in = mysqli_query($dbMasterLink, "insert into `" . DBPREFIX . "web_sys800_data` set $sInsData");
                        if ($in) {

                            $result = mysqli_query($dbMasterLink, "update `" . DBPREFIX . "rebate_history_report` set `status`=1 where `userid`='" . $v['user_id'] . "' and `R_date`='".$rebate_date."' ");
                            if ($result) {
                                // 插入返水账变        0用户id|1用户名|2测试/正式|3操作前金额|4操作金额|5操作后金额|6操作类型|7来源|8数据id或订单号|9描述可为空
                                // 添加返水类型备注（type -4 - 返水，source - 5-后台）
                                $moneyLogRes=addAccountRecords(array($v['user_id'],$v['username'],$aUser['test_flag'],$moneyf,$v['R_total'],$currency_after,4,6,'',"[游戏管理-系统参数-手动退水]{$rebate_date}天天返水入账,操作人:{$loginname}"));
                                if($moneyLogRes) {
                                    mysqli_query($dbMasterLink, "COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    die(json_encode(["err" => -9, "msg" => "添加返水账变日志失败！"]));
                                }
                            }else{
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                die(json_encode(["err" => -5, "msg" => "返水状态变更失败！"]));
                            }

                        } else {
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            die(json_encode(["err" => -7, "msg" => "账变记录插入失败！"]));
                        }
                    }else{
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        die(json_encode(["err" => -4, "msg" => "更新会员资金失败！"]));
                    }
                }else{
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    die(json_encode(["err" => -3, "msg" => "锁定会员资金失败！"]));
                }
            }else{
                mysqli_query($dbMasterLink, "ROLLBACK");
                die(json_encode(["err" => -8, "msg" => "不能重复返水！"]));
            }
        }else{
            mysqli_query($dbMasterLink, "ROLLBACK");
            die(json_encode(["err" => -2, "msg" => "返水订单加锁失败！"]));
        }

        $num++;
        $R_total += $v['R_total'];

    }

    error_log('----------------- 返水结束 ---------------'.PHP_EOL, 3,'/tmp/group/rebate_operating.log');

    die(json_encode(["err"=>0,"num"=>$num, "R_total"=>$R_total]));

}else{
    $result_data = mysqli_query($dbLink,"select * from ".DBPREFIX."rebate_history_report WHERE R_date = '".$rebate_date."' and status =0 order by username");
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        $r_data=[];
        while ($row = mysqli_fetch_assoc($result_data)){
            $r_data[]=$row;
        }
    }
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>手动退水</title>
    <style type="text/css">
        .rebate_user_search{width: 40px; height: 25px; margin: auto; text-align: center; line-height: 25px; background-color: #ffffbe; border: 1px solid #000000;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>记录查询</dt>
    <dd>返水时间：<?php echo $rebate_date;?></dd>
</dl>
<div class="">

    <table class="m_tab">
        <tr  class="m_title" style="font-weight: bold;" >
            <td>选择</td>
            <td>会员账号</td>
            <td>体育码量</td>
            <td>AG视讯码量</td>
            <td>AG电子码量</td>
            <!--<td>彩票码量</td>-->
            <td>AG捕鱼王打鱼码量</td>
            <td>AG捕鱼王养鱼码量</td>
            <td>开元棋牌码量</td>
            <!--<td>皇冠棋牌码量</td>-->
            <td>VG棋牌码量</td>
            <td>乐游棋牌码量</td>
            <td>快乐棋牌码量</td>
            <td>MG电子码量</td>
            <td>泛亚电竞码量</td>
            <td>雷火电竞码量</td>
            <td>OG视讯码量</td>
            <td>BBIN视讯码量</td>
            <td>MW电子码量</td>
            <td>CQ9电子码量</td>
            <td>FG电子码量</td>
            <td>返水金额</td>
        </tr>
            <?php
            if (count($r_data) == 0){
                ?>
                <tr><td colspan="20"><br>没有数据<br></td></tr>
            <?php } ?>

        <!--<form name="myform" action="rebate_operating.php?uid=<?php /*echo $uid;*/?>" method="post">-->
            <!--<input type="hidden" name="type" value="rebate_2_users">-->
            <?php
            $R_total_all=0;
            foreach ($r_data as $k => $v){
                $R_total_all+=intval($v['R_total']);
                // AG用户名 转成HG用户名后再显示
                $delimiter = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_';
                if(strpos($v['username'],$delimiter) !== false){
                    $aUsername=explode($delimiter,$v['username']);
                    $v['username']=$aUsername[1];
                }
                ?>
                <tr>
                    <td><input class="data_input_val" data-user-id="<?php echo $v['userid'];?>" data-username="<?php echo $v['username'];?>" data-money="<?php echo $v['R_total'];?>" name="chk_<?php echo $v['username'];?>" type="checkbox" checked="checked" value="<?php echo $v['id'];?>" /></td>
                    <td><?php echo $v['username'];?></td>
                    <td><?php echo number_format($v['total_hg'],2);?></td>
                    <td><?php echo number_format($v['total_ag'],2);?></td>
                    <td><?php echo number_format($v['total_ag_dianzi'],2);?></td>
                    <!--<td><?php /*echo $v['total_cp'];*/?></td>-->
                    <td><?php echo number_format($v['total_ag_dayu'],2);?></td>
                    <td><?php echo number_format($v['total_ag_yangyu'],2);?></td>
                    <td><?php echo number_format($v['total_ky'],2);?></td>
                    <!--<td><?php /*echo number_format($v['total_hgqp'],2);*/?></td>-->
                    <td><?php echo number_format($v['total_vgqp'],2);?></td>
                    <td><?php echo number_format($v['total_lyqp'],2);?></td>
                    <td><?php echo number_format($v['total_klqp'],2);?></td>
                    <td><?php echo number_format($v['total_mg'],2);?></td>
                    <td><?php echo number_format($v['total_avia'],2);?></td>
                    <td><?php echo number_format($v['total_fire'],2);?></td>
                    <td><?php echo number_format($v['total_og'],2);?></td>
                    <td><?php echo number_format($v['total_bbin'],2);?></td>
                    <td><?php echo number_format($v['total_mw'],2);?></td>
                    <td><?php echo number_format($v['total_cq'],2);?></td>
                    <td><?php echo number_format($v['total_fg'],2);?></td>
                    <td><?php echo number_format($v['R_total'],2);?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="20" >
                    会员数量：<?php echo count($r_data).' - '.number_format($R_total_all,0); ?>&nbsp;&nbsp;
                    <input id="submit_action" style="width: 80px; height: 30px; line-height: 30px;" type="button" value="提交" onclick="rebateToUser()">
                </td>
            </tr>
        <!--</form>-->
    </table>


</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

<script type="text/javascript">
var userid = '<?php echo $uid;?>' ;
// 异步提交退水操作  rebate_operating.php?uid=
function rebateToUser() {
    var url ='rebate_operating.php' ;
    var data_all =[] ;
    $('.data_input_val').each(function () {
        if(this.checked){
            var user_id = $(this).attr('data-user-id');
            var username = $(this).attr('data-username') ;
            var money = $(this).attr('data-money') ;
            data_all.push({user_id:user_id,username:username,R_total:money}) ;
        }
    }) ;
    var datapars = {
        uid:userid,
        action:'rebate_2_users',
        dataall:JSON.stringify(data_all),
    }
    $.ajax({
        type: 'POST',
        url:url,
        data:datapars,
        dataType:'json',
        success:function(ret){
            // console.log(ret);
            // 返水提交成功后，显示返水会员数量，返水金额。
            if (ret.err==0){
                alert("返水成功。会员数：" + ret.num + "，总共返水金额："+ ret.R_total + "元");
                window.location.href="/app/agents/admin/rebate.php?uid="+userid;
            }else{
                alert(ret.msg);
            }
        },
        error:function(ii,jj,kk){
            alert('网络错误，请稍后重试');
        }

    });
}


</script>
</body>
</html>


