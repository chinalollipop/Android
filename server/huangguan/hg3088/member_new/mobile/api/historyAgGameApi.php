<?php
/**
 * AG真人/电子游戏 捕鱼 投注记录
 * Checked  是否结算 ，N 未结注单 Y 已结注单  传空 查全部
 * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
 * date_start 2018-09-18 00:00:01
 * date_end  2018-09-18 23:59:59
 * page 从第0页开始
 */
include_once('../include/config.inc.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");

/*
 *  返回投注内容
 * */
function returnAgBet($row){
    global  $playType;
    $slottype = array(1=>'普通',2=>'免費',8=>'Jackpot',9=>'Jackpot',10=>'紅利',11=>'紅利'); // 电子注单类型
    $playTypes=$playType['baijiale'];
    $str = '<p class="play_room">'. $row['slottype']>0 ? $slottype[$row['slottype']] : $playTypes[$row['playType']] .'</p>' ;
    $str .='<p class="jh_num">局号：'.$row['gamecode'].'</p>';
    return $str ;
}

function returnAgByBet($row){
    $str = '<p class="play_room">子弹数量：'. $row['BulletOutNum'] .'</p>' ;
    $str .='<p class="zc_num">子弹价值(支出)：'.$row['Cost'].'</p>';
    $str .='<p class="sr_num">鱼价值(收入)：'.$row['Earn'].'</p>';
    return $str ;
}

$name = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$Checked = $_REQUEST['Checked'] ;
$Cancel=$_REQUEST['Cancel'];
$gtype = isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'' ; //  aglive ,aggame ,agby

if($gtype =='aggame'){ // 电子游戏
    $game_type_chk = " AND `slottype` > 0 "  ;
}else if($gtype =='aglive'){ // 真人娱乐
    $game_type_chk = "AND `playType` > 0 ";
}else{ // 全部
    $game_type_chk = "";
}

// 默认查询当天的数据
$date_start = !$_REQUEST['date_start'] ? date('Y-m-d 00:00:00') : $_REQUEST['date_start'] ;
$date_end = !$_REQUEST['date_end'] ? date('Y-m-d H:i:s') : $_REQUEST['date_end'];
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
$betscore_all = 0; // 投注总额
$betscore_all_yx = 0; // 有效投注额额
$m_result_all = 0; // 输赢总额

// 时间处理，转为时间戳
//$date_start = strtotime($date_start);
//$date_end = strtotime($date_end);

$date_start and $date_end ? $sWhere .= " and (`bettime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';

// ag 用户名处理
$sPrefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_';
$agUsername = $name ? $sPrefix.$name : '';

$agGames = $agGames+$agDianziGames; // 真人和电子名称组合

// 交易状况页面为未结算注单
if($gtype == 'agby') { // 捕鱼
    $sql = "select * from `".DBPREFIX."ag_buyu_scene` where UserName='$agUsername' and EndTime between '{$date_start}' and '{$date_end}' order by `EndTime` desc";
}else{ // 真人 电子
    $sql = "select username,bettime,gamename,thirdprojectid,gamecode,slottype,playType,amount,valid_money,profit,iswin from `".DBPREFIX."ag_projects` where username='$agUsername' $sWhere $game_type_chk order by `bettime` desc";
}

// echo $sql;

$result = mysqli_query($dbLink,$sql); // 结算
$cou=mysqli_num_rows($result); // 总数
$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;

if($page==0){
    while($allrow = mysqli_fetch_assoc($result)) {
        if($gtype == 'agby'){ // 捕鱼
            $betscore_all += $allrow['Cost']; //  子弹价值（支出）
            $betscore_all_yx += $allrow['Cost']; // 子弹价值（支出）
            $profit = $allrow['Earn'] - $allrow['Cost'];
            $m_result_all += $profit;
        }else{
            $betscore_all += $allrow['amount'];
            $betscore_all_yx += $allrow['valid_money'];
            $m_result_all += $allrow['profit'];
        }

    }
}else{
    $betscore_all = 0;
    $betscore_all_yx = 0;
    $m_result_all = 0 ;
}

$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink, $mysql);
$cou_current_page=mysqli_num_rows($result); // 总数

$data=array();
$data['total']=$cou; // 总条目
$data['num_per_page']=$page_size; // 每页条数
$data['currentpage']=$page; // 当前页号
$data['page_count']=$page_count; // 总页数
$data['perpage']= $cou_current_page; // 当前页条数
$data['betscore_all']= number_format($betscore_all,2); // 投注总额
$data['betscore_all_yx']= number_format($betscore_all_yx,2); // 有效投注总额
$data['m_result_all']= number_format($m_result_all,2); // 输赢总额

// $row = mysqli_fetch_array($result);

$data2=array();
while ($row = mysqli_fetch_assoc($result)) {
    $data2[] = $row;
}

foreach ($data2 as $k => $row){
    $data['rows'][$k]['Middle']= []; // 占位
    $data['rows'][$k]['betContent']= ''; // 占位
    if($gtype == 'agby') { // 捕鱼
        $data['rows'][$k]['orderNo']= $row['SceneId'];
        $data['rows'][$k]['BetTime']= $row['StartTime'];
       // $data['rows'][$k]['betContent']= returnAgByBet($row);
        $data['rows'][$k]['Title']= '房间号：'.$row['RoomId'];
        $data['rows'][$k]['BetScore']= $row['Cost'];
        $data['rows'][$k]['M_Result']= $row['Earn'] - $row['Cost'];
    }else{ // 真人电子
        $data['rows'][$k]['orderNo']= $row['thirdprojectid'];
        $data['rows'][$k]['BetTime']= $row['bettime'];
       // $data['rows'][$k]['betContent']= returnAgBet($row);
        $data['rows'][$k]['Title']= $agGames[$row['gamename']];
        $data['rows'][$k]['BetScore']= number_format($row['amount'],2);
        $data['rows'][$k]['M_Result']= $row['profit'];
    }

    $data['rows'][$k]['font_a']= ''; // 占位
    $data['rows'][$k]['zt']= '';  // 占位
}

// var_dump($data);

if($cou==0){
    $data['rows'] = [] ;
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$data);

