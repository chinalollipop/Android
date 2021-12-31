<?php
/**
 * 棋牌投注记录、MG电子、电竞、OG
 * Checked  是否结算 ，N 未结注单 Y 已结注单  传空 查全部
 * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
 * date_start 2018-09-18 00:00:01
 * date_end  2018-09-18 23:59:59
 * page 从第0页开始
 */
include_once ("../include/config.inc.php");
require_once '../../../../common/mg/api.php';
require_once '../../../../common/avia/config.php';
require_once '../../../../common/thunfire/config.php';
require_once '../../../../common/og/config.php';
require_once '../../../../common/cq9/config.php';
require_once '../../../../common/bbin/config.php';
require_once '../../../../common/klqp/config.php';
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
function returnChessBet($row){
    global  $kyRoomType;
    $str = '<p class="play_room">'.$kyRoomType[$row['kindid']][$row['serverid']].'</p>' ;
    $str .='<p class="zz_num">桌子号：'.$row['tableid'].'</p>';
    $str .='<p class="zw_num">座位号：'.$row['chairid'].'</p>';
    return $str ;
}
// ly
function returnLyChessBet($row){
    global  $lyRoomType;
    $str = '<p class="play_room">'.$lyRoomType[$row['kindid']][$row['serverid']].'</p>' ;
    $str .='<p class="zz_num">桌子号：'.$row['tableid'].'</p>';
    $str .='<p class="zw_num">座位号：'.$row['chairid'].'</p>';
    return $str ;
}
// vg
function returnVgChessBet($row){
    global  $vgRoomType;
    $str = '<p class="play_room">'.$vgRoomType[$row['roomId']].'</p>' ;
    $str .='<p class="fj_num">房间号：'.$row['roomId'].'</p>';
    $str .='<p class="zz_num">桌子号：'.$row['tableId'].'</p>';
    return $str ;
}
// hg
function returnhgChessBet($row){
    global  $vgRoomType;
    $str = '<p class="play_room">'.$vgRoomType[$row['roomId']].'</p>' ;
    $str .='<p class="fj_num">场次：第'.substr($row['level'], -1, 1).'场</p>';
    $str .='<p class="zz_num">局号：'.$row['board_id'].'</p>';
    return $str ;
}
// mg
function returnmgChessBet($row){
    global  $mgDianziGames;
    $str = '<p class="play_room">'.$mgDianziGames[$row['itemid']].'</p>' ;
    $str .='<p class="fj_num">场次：第'.$row['gameid'].'场</p>';
    $str .='<p class="zz_num">局号：'.$row['roundid'].'</p>';
    return $str ;
}
// avia
function returnaviaChessBet($row){
    global  $aviaCategory ;
    $str = '<div style="text-align: right;">'.
        $row['content'].'<br>'.
        $row['bet'].'<br>'.
        '<font color="#F001FF">'.$row['match_avia'].'</font><br>'.
        $row['league'].' <font color="#009688">['.$aviaCategory[$row['cateID']].']</font><br>'.
        '</div>';
    return $str ;
}
// fire雷火电竞
function returnFireChessBet($row){
    global  $ticket_type ;
    $str = '<p class="play_room">'.'['.$row['event_id'].']'.$row['event_name'].'</p>' ;
    $str .='<p class="fj_num">赛事时间：'.$row['event_datetime'].'</p>';
    $str .='<p class="zz_num">下注：'.$ticket_type[$row['ticket_type']].'</p>';
    return $str ;
}
// oglive
function returnogChessBet($row){
    global  $ogGameBet;
    $str = '<p class="play_room">'.$ogGameBet[$row['gamename']][$row['bet']].'</p>' ;
    $str .='<p class="fj_num">桌号：'.$row['gameid'].'</p>';
    $str .='<p class="zz_num">靴号：'.$row['roundno'].'</p>';
    return $str ;
}

$name = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$Checked = $_REQUEST['Checked'] ;
$Cancel=$_REQUEST['Cancel'];
$gtype = isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'' ; //  kyqp ,lyqp , vgqp, mgdz

// 默认查询当天的数据
$date_start = !$_REQUEST['date_start'] ? date('Y-m-d 00:00:00') : $_REQUEST['date_start'] ;
$date_end = !$_REQUEST['date_end'] ? date('Y-m-d H:i:s') : $_REQUEST['date_end'];
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
$betscore_all = 0; // 投注总额
$betscore_all_yx = 0; // 有效投注额额
$m_result_all = 0; // 输赢总额

$page_size=10; // 每页展示条数
if($Checked==''){ // 默认 Y
    $Checked = 'Y';
    if($gtype=='mgdz'){ // mg 没有未结算和已取消
        $Checked = 'N';
    }
}
if($Checked !='Y'){ // 没有其他状态的注单，都是已结算
    $data['total']=0; // 总条目
    $data['num_per_page']=$page_size; // 每页条数
    $data['currentpage']=$page; // 当前页号
    $data['page_count']=0; // 总页数
    $data['perpage']= 0; // 当前页条数
    $data['betscore_all']= number_format($betscore_all,2); // 投注总额
    $data['betscore_all_yx']= number_format($betscore_all_yx,2); // 有效投注总额
    $data['m_result_all']= number_format($m_result_all,2); // 输赢总额
    $data['rows'] = [] ;
}else{

// 时间处理，转为时间戳
//$date_start = strtotime($date_start);
//$date_end = strtotime($date_end);
    if ($gtype=='avia'){
        $date_start and $date_end ? $sWhere .= " and (`rewardAt` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='fire'){
        $date_start and $date_end ? $sWhere .= " and (`settlement_datetime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='oglive'){
        $date_start and $date_end ? $sWhere .= " and (`md_bettingdate` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='bbinlive'){
        $date_start and $date_end ? $sWhere .= " and (`WagersDate` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='mwdz'){
        $date_start and $date_end ? $sWhere .= " and (`logDate` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='cq9dz'){
        $date_start and $date_end ? $sWhere .= " and (`bettime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }elseif ($gtype=='fgdz'){
        $date_start and $date_end ? $sWhere .= " and (`endtime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }else{
        $date_start and $date_end ? $sWhere .= " and (`game_endtime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
    }

    switch ($gtype){
        case 'kyqp':
            $table = 'ky_projects' ;
            break;
        case 'lyqp':
            $table = 'ly_projects' ;
            break;
        case 'vgqp':
            $table = 'vg_projects' ;
            break;
        case 'klqp':
            $table = 'kl_projects' ;
            break;
        case 'hgqp':
            $table = 'ff_projects' ;
            break;
        case 'mgdz':
            $table = 'mg_projects' ;
            break;
        case 'avia':
            $table = 'avia_projects' ;
            break;
        case 'fire':
            $table = 'fire_projects' ;
            break;
        case 'oglive':
            $table = 'og_projects' ;
            break;
        case 'bbinlive':
            $table = 'jx_bbin_projects' ;
            break;
        case 'cq9dz':
            $table = 'cq9_projects' ;
            break;
        case 'mwdz':
            $table = 'mw_projects' ;
            break;
        case 'fgdz':
            $table = 'fg_projects' ;
            break;
    }
// 交易状况页面为未结算注单
    if($gtype == 'vgqp'){
        $sql = "SELECT `createtime`,`gametype`,`serial`,`roomId`,`tableId`,`roundId`, `beforeBalance`,`betamount`,`validbetamount`,`betpoint`,`money`,`serviceMoney`,`game_endtime`,`isBanker`,`gameInfo`,`gameresult`,`info1`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `createtime` DESC";
    }else if($gtype == 'klqp'){
        $name = $klqp_prefix.$name;
        $date_start and $date_end ? $sWhere = " and (`gametime` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
        $sql = "SELECT `userid`,`username`,`project_id`, `merchant_id`,`channel`,`game_id`,`game_name`,`room_id`, `table_id`, `round_id`, `issue_id`, `_method_id`, `code`, `open_code`, `amount`, `prize`, `gametime`,`_cancel_status`,`frm` 
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `gametime` DESC";
    } else if($gtype == 'hgqp'){
        $sql = "SELECT `userid`, `username`, `mid`, `sid`, `board_id`, `serial` ,`ssid`, `level`, `scoins`, `wincoins`, `bet`, `valid_bet`, `board_fee`, `points`, `banker_uid`, `banker_points`, `bottom_points`,`game_endtime` 
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `game_endtime` DESC";
    } else if($gtype == 'mgdz'){
        $name = $mg_prefix.$name;
        $date_start and $date_end ? $sWhere = " and (`transaction_time` BETWEEN '{$date_start}' AND '{$date_end}')" : '';
        $sql = "SELECT `userid`, `username`, `mgid`, `category`, `gameid`, `itemid`, `amount`, `platform`, `ext_item_id`, `roundid`, `itemid`, `balance`,`transaction_time` ,`created_at`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `transaction_time` DESC";
    } else if($gtype == 'avia'){
        $sql = "SELECT `userid`, `username`, `orderID`, `cateID`, `category`, `leagueID`, `league` ,`bet`, `match_avia`, `content`, `result`, `betAmount`, `betMoney`, `money`, `status`, `createAt`, `updateAt`, `startAt`, `endAt`, `resultAt`, `rewardAt`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `createAt` DESC";
    } else if($gtype == 'fire'){
        $Cancel == 'Y' ? $sWhere = " and result_status = 'CANCELLED' " : '';    // 已取消
        $sql = "SELECT `userid`, `username`, `date_created`,`orderID`,`game_type_id`,`game_type_name`,`event_id`,`event_name`,`event_datetime`,`date_created`,`bet_selection`,`bet_type_name`,`settlement_datetime`,`amount`,`settlement_status`,`earnings`,`result_status`,`ticket_type`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `date_created` DESC";
    } else if($gtype == 'oglive'){
        $name = $og_prefix.$name;
        $sql = "SELECT `userid`, `username`, `gamename`, `bettingcode`, `bettingdate`, `md_bettingdate`, `gameid` ,`roundno`, `bet`, `winloseresult`, `bettingamount`, `validbet`, `winloseamount`, `balance`, `status`, `gamecategory`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `md_bettingdate` DESC";
    } else if($gtype == 'bbinlive'){
        $name = strtoupper($bbin_prefix.$name);
        $sql = "SELECT `userid`, `username`,`agents`,`admin`,`WagersID`,`GameKind`,`GameType`,`Result`,`SerialID`,`RoundNo`,`WagerDetail`,`GameCode`,`ResultType`,`Card`,`BetAmount`,`Payoff`,`ExchangeRate`,`Commissionable`,`Commission`,`IsPaid`,`Origin`,`prefix`,`WagersDate`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `WagersDate` DESC";
    } else if($gtype == 'cq9dz'){
        $sql = "SELECT `userid`, `username`, `gamecode`,`round`,`balance`,`win`,`bet`,`jackpot`,`status`,`endroundtime`,`createtime`,`bettime`,`detail`,`singlerowbet`,`gamerole`,`bankertype`,`rake`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `bettime` DESC";
    } else if($gtype == 'mwdz'){
        $name = $mw_prefix.$name;
        $sql = "SELECT `userid`, `username`, `merchantId`,`gameId`,`gameName`,`gameType`,`gameNum`,`playMoney`,`winMoney`,`logDate`,`commission`,`category`,`created_at`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `logDate` DESC";
    } else if($gtype == 'fgdz'){
        $name = $fg_prefix.$name;
        $sql = "SELECT `userid`, `username`,`orderid`,`game_id`,`gt`,`start_chips`,`end_chips`,`all_bets`,`all_wins`,`total_bets`,`jackpot_bonus`,`jp_contri`,`result`,`scene_id`,`bullet_count`,`type`,`begintime`,`endtime`,`device`
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `endtime` DESC";
    } else{
        $sql = "SELECT  `gameid`,  `kindid`, `serverid`, `tableid`, `chairid`, `cardvalue`, `cellscore`, `allbet`, `curscore`, `profit`, `revenue`, `game_starttime`,`game_endtime` 
          FROM `" . DBPREFIX . "$table`
          WHERE username='$name' $sWhere 
          ORDER BY `game_endtime` DESC";
    }
//    echo $sql; die;

    $result = mysqli_query($dbLink,$sql); // 结算
    $cou=mysqli_num_rows($result); // 总数
    $page_count=ceil($cou/$page_size); // 总页数
    $offset=$page*$page_size;

    if($page==0){ // 首页才有
        while($allrow = mysqli_fetch_assoc($result)) {
            if($gtype == 'vgqp'){
                $betscore_all += $allrow['betamount'];
                $betscore_all_yx += $allrow['validbetamount'];
                $m_result_all += $allrow['money'];
            }else if($gtype == 'hgqp'){ // 皇冠棋牌
                $betscore_all += $allrow['bet'];
                $betscore_all_yx += $allrow['valid_bet'];
                $m_result_all += $allrow['wincoins'];
            }else if($gtype == 'klqp'){ // 快乐棋牌
                $betscore_all += $allrow['amount'];
                $betscore_all_yx += $allrow['amount'];
                $m_result_all += ($allrow['prize'] - $allrow['amount']);
            }else if($gtype == 'mgdz'){ // mg电子

                if ($allrow['category'] == 'WAGER'){ // 统计投注
                    $betscore_all += $allrow['amount'];
                    $betscore_all_yx += $allrow['amount'];
                    $m_result_all += $allrow['amount'];
                }elseif ($allrow['category'] == 'PAYOUT'){ // 统计派彩
                    $m_result_all -= $allrow['amount'];
                }
            }else if($gtype == 'avia'){
                $betscore_all += $allrow['betAmount'];
                $betscore_all_yx += $allrow['betMoney'];
                $m_result_all += $allrow['money'];
            }else if($gtype == 'fire'){
                $betscore_all += $allrow['amount'];
                $betscore_all_yx += $allrow['amount'];
                //$m_result_all += ($allrow['earnings'] < 0 ? $allrow['earnings'] : $allrow['earnings']-$allrow['amount']); //总输赢
                if(!empty($allrow['result_status'])) { // 已结算
                    if($allrow['result_status'] == 'WIN') { // 赢
                        $m_result_all += ($allrow['earnings']-$allrow['amount']);
                    } else if($allrow['result_status'] == 'LOSS') {    // 输
                        $m_result_all += $allrow['earnings'];
                    } else if($allrow['result_status'] == 'DRAW') {    // 和
                        $m_result_all += ($allrow['earnings']-$allrow['amount']);
                    } else if($allrow['result_status'] == 'CANCELLED') {  // 取消
                        $m_result_all += '0.00';
                    }
                }
            }else if($gtype == 'oglive'){
                $betscore_all += $allrow['bettingamount'];
                $betscore_all_yx += $allrow['validbet'];
                $m_result_all += $allrow['winloseamount'];
            }else if($gtype == 'bbinlive'){
                $betscore_all += $allrow['BetAmount'];
                $betscore_all_yx += $allrow['BetAmount'];
                $m_result_all += $allrow['Payoff'];
            }else if($gtype == 'cq9dz'){
                $betscore_all += $allrow['bet'];
                $betscore_all_yx += $allrow['bet'];
                $m_result_all += $allrow['win'];
            }else if($gtype == 'mwdz'){
                $betscore_all += $allrow['playMoney'];
                $betscore_all_yx += $allrow['playMoney'];
                $m_result_all += ($allrow['winMoney']-$allrow['playMoney']);
            }else if($gtype == 'fgdz'){
                $betscore_all += $allrow['all_bets'];
                $betscore_all_yx += $allrow['all_bets'];
                $m_result_all += ($allrow['all_wins']-$allrow['all_bets']);
            }else{
                $betscore_all += $allrow['allbet'];
                $betscore_all_yx += $allrow['cellscore'];
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
        if($gtype == 'vgqp'){
            $data['rows'][$k]['orderNo']= $row['roundId'];
            $data['rows'][$k]['BetTime']= $row['createtime'];
            $data['rows'][$k]['betContent']= returnVgChessBet($row);
            $data['rows'][$k]['Title']= $vgGameType[$row['gametype']]?$vgGameType[$row['gametype']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['betamount'],2);
            $data['rows'][$k]['M_Result']= $row['money'];
        }else if($gtype == 'hgqp'){
            $data['rows'][$k]['orderNo']= $row['serial'];
            $data['rows'][$k]['BetTime']= $row['game_endtime'];
            $data['rows'][$k]['betContent']= returnhgChessBet($row);
            $data['rows'][$k]['Title']= $ffGameType[$row['ssid']]?$ffGameType[$row['ssid']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['bet'],2);
            $data['rows'][$k]['M_Result']= $row['wincoins'];
        }else if($gtype == 'klqp'){
            $data['rows'][$k]['orderNo']= $row['project_id'];
            $data['rows'][$k]['BetTime']= $row['gametime'];
            $data['rows'][$k]['betContent']= $row['issue_id']; // 牌局编号
            $data['rows'][$k]['Title']= $row['game_name'];
            $data['rows'][$k]['BetScore']= number_format($row['amount'],2);
            $data['rows'][$k]['M_Result']= $row['prize'];
        }else if($gtype == 'mgdz'){
            $data['rows'][$k]['orderNo']= $row['roundid'];
            $data['rows'][$k]['BetTime']= $row['transaction_time'];
            $data['rows'][$k]['betContent']= returnmgChessBet($row);
            $data['rows'][$k]['Title']= $mgDianziGames[$row['itemid']]?$mgDianziGames[$row['itemid']]:'';
            if ($row['category'] == 'WAGER'){ // 投注
                $data['rows'][$k]['BetScore']= number_format($row['amount'],2);
                $data['rows'][$k]['M_Result']= -(number_format($row['amount'],2));
            }elseif ($row['category'] == 'PAYOUT'){ // 派彩
                $data['rows'][$k]['BetScore']= '派彩';
                $data['rows'][$k]['M_Result']= number_format($row['amount'],2);
            }
        }else if($gtype == 'avia'){
            $data['rows'][$k]['orderNo']= $row['orderID'];
//            $dealTime =
//                '下注:'.$row['createAt'].'<br>'.
//                '开奖:'.$row['resultAt'].'<br>'.
//                '结算:'.$row['rewardAt'];
            $data['rows'][$k]['BetTime']= $row['createAt'];
            $data['rows'][$k]['betContent']= returnaviaChessBet($row);
            $data['rows'][$k]['Title']= $aviaCategory[$row['cateID']]?$aviaCategory[$row['cateID']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['betAmount'],2);
            $data['rows'][$k]['M_Result']= $row['money'];
        }else if($gtype == 'fire'){
            $data['rows'][$k]['orderNo']= $row['orderID'];
            $data['rows'][$k]['BetTime']= $row['date_created'];
            $data['rows'][$k]['betContent']= returnFireChessBet($row);
            $data['rows'][$k]['Title']= $thunFireCategory[$row['game_type_id']] ? $thunFireCategory[$row['game_type_id']] : '连串';
            $data['rows'][$k]['BetScore']= number_format($row['amount'],2);
            //   $data['rows'][$k]['M_Result'] = sprintf("%.2f",$row['earnings']-$row['amount'] );
            if(!empty($row['result_status'])) { // 已结算
                if($row['result_status'] == 'WIN') { // 赢
                    $data['rows'][$k]['M_Result'] += sprintf("%.2f",$row['earnings']-$row['amount']);
                } else if($row['result_status'] == 'LOSS') {    // 输
                    $data['rows'][$k]['M_Result'] += sprintf("%.2f",$row['earnings']);
                } else if($row['result_status'] == 'DRAW') {    // 和
                    $data['rows'][$k]['M_Result'] += sprintf("%.2f",$row['earnings']-$row['amount']);
                } else if($row['result_status'] == 'CANCELLED') {  // 取消
                    $data['rows'][$k]['M_Result'] += '';
                    $data['rows'][$k]['count'] = 0; // ( count : 0 未结算 1 已结算 )
                    $data['rows'][$k]['cancel'] = 1; // ( cancel :0 未取消，1 已取消 )
                }
            } else {
                $data['rows'][$k]['M_Result'] = $thunFireSettlementStatus[$row['settlement_status']];
                $data['rows'][$k]['count'] = 0; // ( count : 0 未结算 1 已结算)
                $data['rows'][$k]['cancel'] = 0; // ( cancel :0 未取消，1 已取消 )
            }

        }else if($gtype == 'oglive'){
            $data['rows'][$k]['orderNo']= $row['bettingcode'];
            $data['rows'][$k]['BetTime']= $row['md_bettingdate'];
            $data['rows'][$k]['betContent']= returnogChessBet($row);
            $data['rows'][$k]['Title']= $ogGamename[$row['gamename']]?$ogGamename[$row['gamename']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['bettingamount'],2);
            $data['rows'][$k]['M_Result']= $row['winloseamount'];
        }else if($gtype == 'bbinlive'){
            $data['rows'][$k]['orderNo']= $row['WagersID'];
            $data['rows'][$k]['BetTime']= $row['WagersDate'];
            $data['rows'][$k]['betContent']= $bbGameCateType[$row['GameKind']][$row['GameType']] ? $bbGameCateType[$row['GameKind']][$row['GameType']] : '';
            $data['rows'][$k]['Title']= $bbGameCateType[$row['GameKind']][$row['GameType']] ? $bbGameCateType[$row['GameKind']][$row['GameType']] : '';
            $data['rows'][$k]['BetScore']= number_format($row['BetAmount'],2);
            $data['rows'][$k]['M_Result']= $row['Payoff'];
        }else if($gtype == 'cq9dz'){
            $data['rows'][$k]['orderNo']= $row['round'];
            $data['rows'][$k]['BetTime']= $row['bettime'];
            $data['rows'][$k]['betContent']= $row['detail'];
            $data['rows'][$k]['Title']= $cqDianziGames[$row['gamecode']]?$cqDianziGames[$row['gamecode']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['bet'],2);
            $data['rows'][$k]['M_Result']= $row['win']-$row['bet'];
        }else if($gtype == 'mwdz'){
            $data['rows'][$k]['orderNo']= $row['gameNum'];
            $data['rows'][$k]['BetTime']= $row['logDate'];
            $data['rows'][$k]['betContent']= $row['gameNum'];
            $data['rows'][$k]['Title']= $row['gameName']?$row['gameName']:'';
            $data['rows'][$k]['BetScore']= number_format($row['playMoney'],2);
            $data['rows'][$k]['M_Result']= $row['winMoney']-$row['playMoney'];
        }else if($gtype == 'fgdz'){
            $data['rows'][$k]['orderNo']= $row['orderid'];
            $data['rows'][$k]['BetTime']= $row['endtime'];
            $data['rows'][$k]['betContent']= $afgGameList[$row['game_id']];
            $data['rows'][$k]['Title']= $afgGameList[$row['game_id']]?$afgGameList[$row['game_id']]:'';
            $data['rows'][$k]['BetScore']= number_format($row['all_bets'],2);
            $data['rows'][$k]['M_Result']= $row['all_wins']-$row['all_bets'];
        }else{
            $data['rows'][$k]['orderNo']= $row['gameid'];
            $data['rows'][$k]['BetTime']= $row['game_endtime'];
            if($gtype=='kyqp'){
                $data['rows'][$k]['betContent']= returnChessBet($row);
                $data['rows'][$k]['Title']= $kyGameType[$row['kindid']]?$kyGameType[$row['kindid']]:'';
            }else{ // 乐游
                $data['rows'][$k]['betContent']= returnLyChessBet($row);
                $data['rows'][$k]['Title']= $lyGameType[$row['kindid']]?$lyGameType[$row['kindid']]:'';
            }
            $data['rows'][$k]['BetScore']= number_format($row['allbet'],2);
            $data['rows'][$k]['M_Result']= $row['profit'];
        }

        $data['rows'][$k]['font_a']= ''; // 占位
        $data['rows'][$k]['zt']= '';  // 占位
    }

// var_dump($data);

    if($cou==0){
        $data['rows'] = [] ;
    }
}


$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$data);

