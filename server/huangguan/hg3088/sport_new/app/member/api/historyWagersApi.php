<?php
/**
 * 投注记录
 * gtype  赛事类型，FT 足球、BK 篮球
 * Checked  是否结算 ，N 未结注单 Y 已结注单  传空 查全部
 * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
 * date_start 2018-09-18 00:00:01
 * date_end  2018-09-18 23:59:59
 * page 从第0页开始
 */
require ("../include/config.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");

$name = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$gtype = $_REQUEST['gtype'] ;
$Checked = $_REQUEST['Checked'] ;
$Cancel=$_REQUEST['Cancel'];
// 默认查询当天的数据
$date_start = !$_REQUEST['date_start'] ? date('Y-m-d 00:00:00') : $_REQUEST['date_start'] ;
$date_end = !$_REQUEST['date_end'] ? date('Y-m-d H:i:s') : $_REQUEST['date_end'];
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
$betscore_all = 0; // 投注总额
$betscore_all_yx = 0; // 有效投注额额
$m_result_all = 0; // 输赢总额


if ($Checked=='Y'){
    $sWhere .= " and Checked=1";
}elseif($Checked=='N'){
    $sWhere .= " and Checked=0";
}
$Cancel =='Y' ? $sWhere .= " and Cancel=1" : $sWhere .= " and Cancel=0";
$date_start and $date_end ? $sWhere .= " and ((`BetTime` BETWEEN '{$date_start}' AND '{$date_end}') or (`M_Date` BETWEEN '{$date_start}' AND '{$date_end}'))" : '';

// 交易状况页面为未结算注单

$sql = "select ID,MID,LineType,Active,Gtype,M_Date,BetTime,orderNo,$middle as Middle,$bettype as BetType,BetScore,vgold,Gwin,OddsType,Cancel,Danger,Confirmed,M_Result,Checked,ShowType from ".DBPREFIX."web_report_data where Gtype ='$gtype' and M_Name='$name' ".$sWhere." order by orderby,BetTime desc";

// echo $sql;
$result = mysqli_query($dbLink,$sql); // 结算
$cou=mysqli_num_rows($result); // 总数
$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;

if($page==0){
    while($allrow = mysqli_fetch_assoc($result)) {
        $betscore_all += $allrow['BetScore'];
        $betscore_all_yx += $allrow['vgold'];
        $m_result_all += $allrow['M_Result'];
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
$data['betscore_all']= number_format($betscore_all); // 投注总额
$data['betscore_all_yx']= number_format($betscore_all_yx); // 有效投注总额
$data['m_result_all']= number_format($m_result_all,2); // 输赢总额

$data2=array();
while($row = mysqli_fetch_assoc($result)) {
    if ($row['Checked']==0){
        if ($row['Danger']==1 or $row['LineType']==9 or $row['LineType']==19 or $row['LineType']==10 or $row['LineType']==20 or $row['LineType']==21 or $row['LineType']==31 or $row['LineType']==50 or
            $row['LineType']==104 or $row['LineType']==105 or $row['LineType']==106 or $row['LineType']==107 or $row['LineType']==115 or $row['LineType']==118 or
            $row['LineType']==119 or $row['LineType']==120 or $row['LineType']==161 or $row['LineType']==122 or $row['LineType']==123 or $row['LineType']==124 or
            $row['LineType']==129 or $row['LineType']==130 or $row['LineType']==134 or $row['LineType']==135 or $row['LineType']==137 or $row['LineType']==141 or
            $row['LineType']==144 or $row['LineType']==142 or $row['LineType']==204 or $row['LineType']==206 and $row['Cancel']==0){
            if ($row['Danger']==1 and $row['Cancel']==0){
                $row['isDanger']=$Order_Pending;
            }else if ($row['Danger']==0 and $row['Cancel']==0 and $row['Gtype']=="FT"){
                $row['isDanger']=$Order_Confirmed;
            }
        }else if ($row['Danger']==0){
            $row['isDanger']='';
        }
    }else{
        $row['isDanger']='';
    }

    $data2[]=$row;
}

foreach ($data2 as $k => $row){

    switch($row['Active']) {
        case 1:
        case 11:
            $Title = $Mem_Soccer;
            break;
        case 2:
        case 22:
            $Title = $Mem_Baseketball;
            break;
    }

    if ($row['Cancel']==1) {
        switch ($row['Confirmed']) {
            case -1:
                $zt = $Score21;
                break;
            case -2:
                $zt = $Score22;
                break;
            case -3:
                $zt = $Score23;
                break;
            case -4:
                $zt = $Score24;
                break;
            case -5:
                $zt = $Score25;
                break;
            case -6:
                $zt = $Score26;
                break;
            case -7:
                $zt = $Score27;
                break;
            case -8:
                $zt = $Score28;
                break;
            case -9:
                $zt = $Score29;
                break;
            case -10:
                $zt = $Score30;
                break;
            case -11:
                $zt = $Score31;
                break;
            case -12:
                $zt = $Score32;
                break;
            case -13:
                $zt = $Score33;
                break;
            case -14:
                $zt = $Score34;
                break;
            case -15:
                $zt = $Score35;
                break;
            case -16:
                $zt = $Score36;
                break;
            case -17:
                $zt = $Score37;
                break;
            case -18:
                $zt = $Score38;
                break;
            case -19:
                $zt = $Score39;
                break;
            case -20:
                $zt = $Score40;
                break;
            case -21:
                $zt = $Score41;
                break;
        }
    }

    $data['rows'][$k]['ID']=$row['ID'];
    $data['rows'][$k]['MID']=$row['MID'];
    $data['rows'][$k]['Gtype']=$row['Gtype'];
    $data['rows'][$k]['LineType']=$row['LineType'];
    $data['rows'][$k]['ShowType']=$row['ShowType'];
    $data['rows'][$k]['BetTime']=$row['BetTime'];
    $data['rows'][$k]['M_Date']=$row['M_Date'];
    $data['rows'][$k]['orderNo']=$row['orderNo'];
    $data['rows'][$k]['Middle']=$row['Middle'];
    $data['rows'][$k]['BetScore']=number_format($row['BetScore'],2);
    $data['rows'][$k]['M_Result']=$row['M_Result'];
    $data['rows'][$k]['Checked']=$row['Checked'];
    $data['rows'][$k]['isDanger']=$row['isDanger'];
    $row['BetType'] = str_ireplace('&nbsp;<font color=red><b>','',$row['BetType']);
    $data['rows'][$k]['Title']=$Title.$row['BetType'];
    $data['rows'][$k]['zt']=$zt?$zt:''; // 赛事取消
}

if($data['rows']){
    foreach ($data['rows'] as $k=> $v){

        $data['rows'][$k]['isDanger'] = $v['isDanger']?$v['isDanger']:'';
        if($v['LineType']==16){

            $v['Middle'] = str_replace('&nbsp;', ' ', $v['Middle']);
            $Middle = explode('<br>', $v['Middle']);
            $bet_content = explode('@',$Middle[1])[0];
            $bet_rate = explode('@',$Middle[1])[1];
            $bet_rate = str_replace('<FONT color=#CC0000><b>', '', $bet_rate);
            $bet_rate = str_replace('</b></FONT>', '', $bet_rate);
            $data['rows'][$k]['Middle'] = [];
            $data['rows'][$k]['M_League'] = $Middle[0];
            $data['rows'][$k]['M_Date'] = $v['M_Date'];
            $data['rows'][$k]['bet_content'] = trim($bet_content);
            $data['rows'][$k]['bet_rate'] = trim($bet_rate);
            $data['rows'][$k]['corner_num'] = '';
            $data['rows'][$k]['first_half'] = '';
            $data['rows'][$k]['font_a'] = '';
            $data['rows'][$k]['vs_team_name1'] = '';
            $data['rows'][$k]['vs_or_let_ball_num'] = '';
            $data['rows'][$k]['vs_team_name2'] = '';
        }
        elseif ($v['LineType']==8){ // 综合过关
            $mid=explode(',',$v['MID']);
            $show=explode(',',$v['ShowType']);
            $Middle = explode('<br>', $v['Middle']);
            foreach($Middle as $k1=>$v1){
                if(!$v1){
                    unset($Middle[$k1]);
                }else{
                    $Middle2[$k1/3][$k1%3] = $v1;
                }
            }

            foreach ($Middle2 as $k2 => $v2){

                $mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$mid[$k2];
                $result1 = mysqli_query($dbLink,$mysql);
                $row1 = mysqli_fetch_assoc($result1);

                if ($row1["MB_Inball"]=='-1'){
                    $font_a3=$Score1;
                    $font_a4=$Score1;
                }else if ($row1["MB_Inball"]=='-2'){
                    $font_a3=$Score2;
                    $font_a4=$Score2;
                }else if ($row1["MB_Inball"]=='-3'){
                    $font_a3=$Score3;
                    $font_a4=$Score3;
                }else if ($row1["MB_Inball"]=='-4'){
                    $font_a3=$Score4;
                    $font_a4=$Score4;
                }else if ($row1["MB_Inball"]=='-5'){
                    $font_a3=$Score5;
                    $font_a4=$Score5;
                }else if ($row1["MB_Inball"]=='-6'){
                    $font_a3=$Score6;
                    $font_a4=$Score6;
                }else if ($row1["MB_Inball"]=='-7'){
                    $font_a3=$Score7;
                    $font_a4=$Score7;
                }else if ($row1["MB_Inball"]=='-8'){
                    $font_a3=$Score8;
                    $font_a4=$Score8;
                }else if ($row1["MB_Inball"]=='-9'){
                    $font_a3=$Score9;
                    $font_a4=$Score9;
                }else if ($row1["MB_Inball"]=='-10'){
                    $font_a3=$Score10;
                    $font_a4=$Score10;
                }else if ($row1["MB_Inball"]=='-11'){
                    $font_a3=$Score11;
                    $font_a4=$Score11;
                }else if ($row1["MB_Inball"]=='-12'){
                    $font_a3=$Score12;
                    $font_a4=$Score12;
                }else if ($row1["MB_Inball"]=='-13'){
                    $font_a3=$Score13;
                    $font_a4=$Score13;
                }else if ($row1["MB_Inball"]=='-14'){
                    $font_a3=$Score14;
                    $font_a4=$Score14;
                }else if ($row1["MB_Inball"]=='-15'){
                    $font_a3=$Score15;
                    $font_a4=$Score15;
                }else if ($row1["MB_Inball"]=='-16'){
                    $font_a3=$Score16;
                    $font_a4=$Score16;
                }else if ($row1["MB_Inball"]=='-17'){
                    $font_a3=$Score17;
                    $font_a4=$Score17;
                }else if ($row1["MB_Inball"]=='-18'){
                    $font_a3=$Score18;
                    $font_a4=$Score18;
                }else if ($row1["MB_Inball"]=='-19'){
                    $font_a3=$Score19;
                    $font_a4=$Score19;
                }else if ($row1["MB_Inball"]=='-51'){
                    $font_a3=$Score51;
                    $font_a4=$Score51;
                }else if ($row1["MB_Inball"]=='-52'){
                    $font_a3=$Score52;
                    $font_a4=$Score52;
                }else if ($row1["MB_Inball"]=='-53'){
                    $font_a3=$Score53;
                    $font_a4=$Score53;
                }else{
                    $font_a3=$row1["TG_Inball"].' : '.$row1["MB_Inball"];
                    $font_a4=$row1["MB_Inball"].' : '.$row1["TG_Inball"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                }

                $M_League_date = explode('&nbsp;', $v2[0]);
                $M_League = $M_League_date[0].' '.$M_League_date[1];
                $vs_team_name = explode('&nbsp;<FONT color=#CC0000>', $v2[1]);
                $vs_team_name1 = $vs_team_name[0];
                $vs_or_let_ball_num_html = explode('</FONT>&nbsp;', $vs_team_name[1]);
                $vs_or_let_ball_num = $vs_or_let_ball_num_html[0];
                $vs_team_name2 = $vs_or_let_ball_num_html[1];
                $bet_rate_html = explode('nbsp;<FONT color=#cc0000><b>', $v2[2]);
                $bet_content = $bet_rate_html[0];
                $bet_rate = explode('</b></FONT>', $bet_rate_html[1])[0];

                $Middle2[$k2]=array();
                $Middle2[$k2]['M_League'] = $M_League;
                $Middle2[$k2]['vs_team_name1'] = $vs_team_name1;
                $Middle2[$k2]['vs_or_let_ball_num'] = $vs_or_let_ball_num;
                $Middle2[$k2]['vs_team_name2'] = $vs_team_name2;
                $bet_content = str_replace('</font>', '</FONT>', $bet_content);
                $bet_content = str_replace('<FONT color=#cc0000>', '', $bet_content);
                $bet_content = str_replace('<font color=#666666>', '', $bet_content);
                $bet_content = str_replace('</FONT>', '', $bet_content);
                $bet_content = str_replace('&nbsp;', ' ', $bet_content);
                $bet_content = str_replace('&', '', $bet_content);
                $bet_content = trim(str_replace('@','',$bet_content));
                $Middle2[$k2]['bet_content'] = $bet_content;
                $Middle2[$k2]['bet_rate'] = $bet_rate;
                $Middle2[$k2]['font_a'] = '';

                if ($show[$k2]=='C'){
                    $pos = strpos($bet_content, '[上半]');
                    if ($pos!==false){
                        $Middle2[$k2]['font_a'] .= $font_a1;
                    }else{
                        $Middle2[$k2]['font_a'] .= $font_a3 == ' : ' ? '' : $font_a3;
                    }

                }else{
                    $pos = strpos($bet_content, '[上半]');
                    if ($pos!==false){
                        $Middle2[$k2]['font_a'] .= $font_a2;
                    }else{
                        $Middle2[$k2]['font_a'] .= $font_a4 == ' : ' ? '' : $font_a4;
                    }

                }

            }

            $data['rows'][$k]['Middle'] = $Middle2; unset($Middle2);
            $data['rows'][$k]['M_League'] = '';
//        $data['rows'][$k]['vs_team_id1'] = '';
//        $data['rows'][$k]['vs_team_id2'] = '';
            $data['rows'][$k]['vs_team_name1'] = '';
            $data['rows'][$k]['vs_or_let_ball_num'] = '';
            $data['rows'][$k]['vs_team_name2'] = '';
            $data['rows'][$k]['bet_content'] = '';
            $data['rows'][$k]['bet_rate'] = '';

        }
        else{
            $v['Middle'] = str_ireplace('FONT','font',$v['Middle']);
            $v['Middle'] = str_ireplace('&nbsp;',' ',$v['Middle']);
//        $v['Middle'] = str_ireplace('vs','VS.',$v['Middle']);
            $v['Middle'] = str_ireplace('COLOR','color',$v['Middle']);

            $Middle = explode('<br>', $v['Middle']);
            $M_League = $Middle[0]; // 联赛名称
            if($v['BetTime'] < $v['M_Date']) { // 明天和明天以后的赛事需要显示日期
                $M_League .= ' '.substr($v['M_Date'],5);
            }

            $vs_team_name = explode('<font color=#0000BB><b>', $Middle[2]);
            $vs_team_name1=str_replace('<font color=gray>','',$vs_team_name[0]);
            $vs_team_name1=str_replace('</font>','',$vs_team_name1);
            $vs_or_let_ball_num_html = explode('</b></font> ', $vs_team_name[1]);
            $vs_or_let_ball_num = $vs_or_let_ball_num_html[0];
            $vs_team_name2_corner = explode('<font color=red><b>',$vs_or_let_ball_num_html[1]);
            $vs_team_name2 = $vs_team_name2_corner[0];
            $vs_team_name2 = str_replace('<font color=gray>','',$vs_team_name2);
            $vs_team_name2=str_replace('</font>','',$vs_team_name2);
            $corner_num = str_replace('</b></font>','',$vs_team_name2_corner[1]);

            // 上半场下半场与全场格式不同分开处理
            if (strpos($Middle[3], '- <font color=#666666>[上半]</font> ')){
                $first_half = '[上半]';
                $Middle[3] = str_replace('- <font color=#666666>[上半]</font> ','',$Middle[3]);
            }else{
                $first_half = '';
            }

            $bet_team_name_play_rate = explode('@', $Middle[3]);
            $bet_content = str_replace('<font color=#cc0000>','',$bet_team_name_play_rate[0]);
            $bet_content = str_replace('</font>','',$bet_content);
            $bet_rate = str_replace('<font color=#cc0000><b>','',$bet_team_name_play_rate[1]);
            $bet_rate = str_replace('</b></font>','',$bet_rate);
            $bet_content = str_replace('<font color=gray>','',$bet_content); // 篮球小节html标签过滤

            $data['rows'][$k]['Middle'] = array();
            $data['rows'][$k]['M_League'] = $M_League;
//        $data['rows'][$k]['vs_team_id1'] = $vs_team_id1;
//        $data['rows'][$k]['vs_team_id2'] = $vs_team_id2;
            $data['rows'][$k]['vs_team_name1'] = trim($vs_team_name1);
            $data['rows'][$k]['vs_or_let_ball_num'] = $vs_or_let_ball_num;
            $data['rows'][$k]['vs_team_name2'] = trim($vs_team_name2);
            $data['rows'][$k]['bet_content'] = $bet_content;
            $data['rows'][$k]['bet_rate'] = $bet_rate;
            $data['rows'][$k]['corner_num'] = $corner_num;
            $data['rows'][$k]['first_half'] = $first_half;
            $data['rows'][$k]['font_a'] = '';

            $mysql="select MB_Team,TG_Team,M_League,M_Start,M_Time,Checked,M_Duration,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,MB_Ball,TG_Ball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$v['MID'];
            $result1 = mysqli_query($dbLink,$mysql);
            $row1 = mysqli_fetch_assoc($result1);
            if ($row1["MB_Inball"]=='-1'){
                if($row1["MB_Inball_HR"]=='-1' and $row1["MB_Inball"]=='-1'){
                    $font_a1=$Score1;
                    $font_a2=$Score1;
                    $font_a3=$Score1;
                    $font_a4=$Score1;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score1;
                    $font_a4=$Score1;
                }
            }else if ($row1["MB_Inball"]=='-2'){
                if($row1["MB_Inball_HR"]=='-2' and $row1["MB_Inball"]=='-2'){
                    $font_a1=$Score2;
                    $font_a2=$Score2;
                    $font_a3=$Score2;
                    $font_a4=$Score2;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score2;
                    $font_a4=$Score2;
                }
            }else if ($row1["MB_Inball"]=='-3'){
                if($row1["MB_Inball_HR"]=='-3' and $row1["MB_Inball"]=='-3'){
                    $font_a1=$Score3;
                    $font_a2=$Score3;
                    $font_a3=$Score3;
                    $font_a4=$Score3;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score3;
                    $font_a4=$Score3;
                }
            }else if ($row1["MB_Inball"]=='-4'){
                if($row1["MB_Inball_HR"]=='-4' and $row1["MB_Inball"]=='-4'){
                    $font_a1=$Score4;
                    $font_a2=$Score4;
                    $font_a3=$Score4;
                    $font_a4=$Score4;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score4;
                    $font_a4=$Score4;
                }
            }else if ($row1["MB_Inball"]=='-5'){
                if($row1["MB_Inball_HR"]=='-5' and $row1["MB_Inball"]=='-5'){
                    $font_a1=$Score5;
                    $font_a2=$Score5;
                    $font_a3=$Score5;
                    $font_a4=$Score5;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score5;
                    $font_a4=$Score5;
                }
            }else if ($row1["MB_Inball"]=='-6'){
                if($row1["MB_Inball_HR"]=='-6' and $row1["MB_Inball"]=='-6')
                {
                    $font_a1=$Score6;
                    $font_a2=$Score6;
                    $font_a3=$Score6;
                    $font_a4=$Score6;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score6;
                    $font_a4=$Score6;
                }
            }else if ($row1["MB_Inball"]=='-7'){
                if($row1["MB_Inball_HR"]=='-7' and $row1["MB_Inball"]=='-7'){
                    $font_a1=$Score7;
                    $font_a2=$Score7;
                    $font_a3=$Score7;
                    $font_a4=$Score7;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score7;
                    $font_a4=$Score7;
                }
            }else if ($row1["MB_Inball"]=='-8'){
                if($row1["MB_Inball_HR"]=='-8' and $row1["MB_Inball"]=='-8')
                {
                    $font_a1=$Score8;
                    $font_a2=$Score8;
                    $font_a3=$Score8;
                    $font_a4=$Score8;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score8;
                    $font_a4=$Score8;
                }
            }else if ($row1["MB_Inball"]=='-9'){
                if($row1["MB_Inball_HR"]=='-9' and $row1["MB_Inball"]=='-9')
                {
                    $font_a1=$Score9;
                    $font_a2=$Score9;
                    $font_a3=$Score9;
                    $font_a4=$Score9;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score9;
                    $font_a4=$Score9;
                }
            }else if ($row1["MB_Inball"]=='-10'){
                if($row1["MB_Inball_HR"]=='-10' and $row1["MB_Inball"]=='-10')
                {
                    $font_a1=$Score10;
                    $font_a2=$Score10;
                    $font_a3=$Score10;
                    $font_a4=$Score10;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score10;
                    $font_a4=$Score10;
                }
            }else if ($row1["MB_Inball"]=='-11'){
                if($row1["MB_Inball_HR"]=='-11' and $row1["MB_Inball"]=='-11'){
                    $font_a1=$Score11;
                    $font_a2=$Score11;
                    $font_a3=$Score11;
                    $font_a4=$Score11;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score11;
                    $font_a4=$Score11;
                }
            }else if ($row1["MB_Inball"]=='-12'){
                if($row1["MB_Inball_HR"]=='-12' and $row1["MB_Inball"]=='-12'){
                    $font_a1=$Score12;
                    $font_a2=$Score12;
                    $font_a3=$Score12;
                    $font_a4=$Score12;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score12;
                    $font_a4=$Score12;
                }
            }else if ($row1["MB_Inball"]=='-13'){
                if($row1["MB_Inball_HR"]=='-13' and $row1["MB_Inball"]=='-13'){
                    $font_a1=$Score13;
                    $font_a2=$Score13;
                    $font_a3=$Score13;
                    $font_a4=$Score13;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score13;
                    $font_a4=$Score13;
                }
            }else if ($row1["MB_Inball"]=='-14'){
                if($row1["MB_Inball_HR"]=='-14' and $row1["MB_Inball"]=='-14'){
                    $font_a1=$Score14;
                    $font_a2=$Score14;
                    $font_a3=$Score14;
                    $font_a4=$Score14;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score14;
                    $font_a4=$Score14;
                }
            }else if ($row1["MB_Inball"]=='-15'){
                if($row1["MB_Inball_HR"]=='-15' and $row1["MB_Inball"]=='-15'){
                    $font_a1=$Score15;
                    $font_a2=$Score15;
                    $font_a3=$Score15;
                    $font_a4=$Score15;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score15;
                    $font_a4=$Score15;
                }
            }else if ($row1["MB_Inball"]=='-16'){
                if($row1["MB_Inball_HR"]=='-16' and $row1["MB_Inball"]=='-16')
                {
                    $font_a1=$Score16;
                    $font_a2=$Score16;
                    $font_a3=$Score16;
                    $font_a4=$Score16;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score16;
                    $font_a4=$Score16;
                }
            }else if ($row1["MB_Inball"]=='-17'){
                if($row1["MB_Inball_HR"]=='-17' and $row1["MB_Inball"]=='-17'){
                    $font_a1=$Score17;
                    $font_a2=$Score17;
                    $font_a3=$Score17;
                    $font_a4=$Score17;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score17;
                    $font_a4=$Score17;
                }
            }else if ($row1["MB_Inball"]=='-18'){
                if($row1["MB_Inball_HR"]=='-18' and $row1["MB_Inball"]=='-18')
                {
                    $font_a1=$Score18;
                    $font_a2=$Score18;
                    $font_a3=$Score18;
                    $font_a4=$Score18;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score18;
                    $font_a4=$Score18;
                }
            }else if ($row1["MB_Inball"]=='-19'){
                if($row1["MB_Inball_HR"]=='-19' and $row1["MB_Inball"]=='-19')
                {
                    $font_a1=$Score19;
                    $font_a2=$Score19;
                    $font_a3=$Score19;
                    $font_a4=$Score19;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score19;
                    $font_a4=$Score19;
                }
            }else if ($row1["MB_Inball"]=='-51'){
                if($row1["MB_Inball_HR"]=='-51' and $row1["MB_Inball"]=='-51')
                {
                    $font_a1=$Score51;
                    $font_a2=$Score51;
                    $font_a3=$Score51;
                    $font_a4=$Score51;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score51;
                    $font_a4=$Score51;
                }
            }else if ($row1["MB_Inball"]=='-52'){
                if($row1["MB_Inball_HR"]=='-52' and $row1["MB_Inball"]=='-52')
                {
                    $font_a1=$Score52;
                    $font_a2=$Score52;
                    $font_a3=$Score52;
                    $font_a4=$Score52;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score52;
                    $font_a4=$Score52;
                }
            }else if ($row1["MB_Inball"]=='-53'){
                if($row1["MB_Inball_HR"]=='-53' and $row1["MB_Inball"]=='-53')
                {
                    $font_a1=$Score53;
                    $font_a2=$Score53;
                    $font_a3=$Score53;
                    $font_a4=$Score53;
                }else{
                    $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
                    $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                    $font_a3=$Score53;
                    $font_a4=$Score53;
                }
            }else{
                $font_a3=$row1["TG_Inball"].' : '.$row1["MB_Inball"];
                $font_a4=$row1["MB_Inball"].' : '.$row1["TG_Inball"];
                $font_a1=$row1["TG_Inball_HR"].' : '.$row1["MB_Inball_HR"];
                $font_a2=$row1["MB_Inball_HR"].' : '.$row1["TG_Inball_HR"];
            }

            $font_a='';
            if( in_array($v['LineType'],array(11,12,13,14,15,16,19,20,31,46,50,165,204,205,206,244)) && $v['Gtype']=="FT"){
                if ($v['ShowType']=='C' and ($v['LineType']==12 or $v['LineType']==19)){
                    if($font_a1==' : '){ // 没有数据
                        $data['rows'][$k]['font_a'] .='' ;
                    }else{
                        $data['rows'][$k]['font_a'] .= $font_a1;
                    }

                }else{
                    if($font_a2==' : ') { // 没有数据
                        $data['rows'][$k]['font_a'] .= '';
                    }else{
                        $data['rows'][$k]['font_a'] .= $font_a2;
                    }

                }
            }else{
                if ($v['ShowType']=='C' and ($v['LineType']==2 or $v['LineType']==9 or $v['LineType']==39 or $v['LineType']==139)){
                    if($font_a3==' : ') { // 没有数据
                        $data['rows'][$k]['font_a'] .= '';
                    }else{
                        $data['rows'][$k]['font_a'] .= $font_a3;
                    }

                }else{
                    if($font_a4==' : ') { // 没有数据
                        $data['rows'][$k]['font_a'] .= '';
                    }else{
                        $data['rows'][$k]['font_a'] .= $font_a4;
                    }

                }
            }
        }

    }
}

if($cou==0){
    $data['rows'] = [] ;
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$data);

