<?php
include_once('include/config.inc.php');
$langx=$_SESSION['Language'];
require ("include/traditional.$langx.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.1';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
    }
}
$langx=$_SESSION['Language'];
require ("include/traditional.$langx.inc.php");

$gtype=$_REQUEST['gtype'];
//$gdate=$_REQUEST['date_start'];
//$gdate1=$_REQUEST['date_end'];
//$mDate=$_REQUEST['today_gmt'];

$username=$_SESSION['UserName'];
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
$page_size=10; // 每页显示条数

$mysql = "select ID from ".DBPREFIX."web_report_data where Gtype ='$gtype' and M_Name='$username' and M_Result!='' order by orderby,bettime desc ";
//echo $mysql; die;
$myresult = mysqli_query($dbLink, $mysql);
$cou=mysqli_num_rows($myresult); // 总数
$page_count=ceil($cou/$page_size); // 总页数

$sql = "select ID,MID,orderNo,LineType,Mtype,M_Date,BetTime,$middle as Middle,BetScore,M_Result,Cancel,Confirmed from ".DBPREFIX."web_report_data where Gtype ='$gtype' and M_Name='$username' and M_Result!='' order by orderby,bettime desc limit ". $page*$page_size .", $page_size";
//echo $sql; die;
$result=mysqli_query($dbLink,$sql);
$cou_current_page=mysqli_num_rows($result); // 当前页条数

$data=array();
$data['total']=$cou; // 总条目
$data['num_per_page']=$page_size; // 每页条数
$data['currentpage']=$page; // 当前页号
$data['page_count']=$page_count; // 总页数
$data['perpage']= $cou_current_page; // 当前页条数

$data2=array();
while ($row = mysqli_fetch_assoc($result)) {
    $data2[]=$row;
}

foreach ($data2 as $k => $row){

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
        $data['rows'][$k]['zt']=$zt;
    }
    $data['rows'][$k]['ID']=$row['ID'];
    $data['rows'][$k]['LineType']=$row['LineType'];
    $data['rows'][$k]['BetTime']=$row['BetTime'];
    $data['rows'][$k]['orderNo']=$row['orderNo'];
    $data['rows'][$k]['Middle']=$row['Middle'];
    $data['rows'][$k]['BetScore']=$row['BetScore'];
    $data['rows'][$k]['M_Result']=$row['M_Result'];
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $status = '200';
    $describe = 'success';

    foreach ($data['rows'] as $k=> $v){

        if ($v['LineType']==8){ // 综合过关
            $Middle = explode('<br>', $v['Middle']);
            foreach($Middle as $k1=>$v1){
                if(!$v1){
                    unset($Middle[$k1]);
                }else{
                    $Middle2[$k1/3][$k1%3] = $v1;
                }
            }

            foreach ($Middle2 as $k2 => $v2){

                $M_League_date = explode('&nbsp;', $v2[0]);
                $M_League = $M_League_date[0];
                $date = $M_League_date[1];
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
                $Middle2[$k2]['bet_content'] = $bet_content;
                $Middle2[$k2]['bet_rate'] = $bet_rate;

            }
            $data['rows'][$k]['Middle'] = $Middle2;
            $data['rows'][$k]['M_League'] = '';
            $data['rows'][$k]['vs_team_id1'] = '';
            $data['rows'][$k]['vs_team_id2'] = '';
            $data['rows'][$k]['vs_team_name1'] = '';
            $data['rows'][$k]['vs_or_let_ball_num'] = '';
            $data['rows'][$k]['vs_team_name2'] = '';
            $data['rows'][$k]['bet_content'] = '';
            $data['rows'][$k]['bet_rate'] = '';

        }else{

            $Middle = explode('<br>', $v['Middle']);
            $M_League = $Middle[0]; // 联赛名称
            $vs_team_id = explode('vs', $Middle[1]);
            $vs_team_id1 = $vs_team_id[0];
            $vs_team_id2 = $vs_team_id[1];
            $vs_team_name = explode('&nbsp;&nbsp;<FONT COLOR=#0000BB><b>', $Middle[2]);
            $vs_team_name1 = $vs_team_name[0];
            $vs_or_let_ball_num_html = explode('</b></FONT>&nbsp;&nbsp;', $vs_team_name[1]);
            $vs_or_let_ball_num = $vs_or_let_ball_num_html[0];
            $vs_team_name2 = explode('&nbsp;&nbsp;<FONT color=red><b>',$vs_or_let_ball_num_html[1])[0];
            $corner_num = str_replace('</b></FONT>','',explode('&nbsp;&nbsp;<FONT color=red><b>',$vs_or_let_ball_num_html[1])[1]);
            $bet_team_name_play_rate = explode('</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>', $Middle[3]);
            $bet_team_name_html = explode('<FONT color=#cc0000>', $bet_team_name_play_rate[0]);
            $bet_content = $bet_team_name_html[1];
            $bet_rate = str_replace('</b></FONT>', '', $bet_team_name_play_rate[1]);

            $data['rows'][$k]['Middle'] = array();
            $data['rows'][$k]['M_League'] = $M_League;
            $data['rows'][$k]['vs_team_id1'] = $vs_team_id1;
            $data['rows'][$k]['vs_team_id2'] = $vs_team_id2;
            $data['rows'][$k]['vs_team_name1'] = $vs_team_name1;
            $data['rows'][$k]['vs_or_let_ball_num'] = $vs_or_let_ball_num;
            $data['rows'][$k]['vs_team_name2'] = $vs_team_name2;
            $bet_content = str_replace('&nbsp;','',$bet_content);
            $bet_content = str_replace('</font>','</FONT>',$bet_content);
            $bet_content = str_replace('</FONT>','',$bet_content);
            $bet_content = str_replace('<font color=#666666>','',$bet_content);
            $data['rows'][$k]['bet_content'] = $bet_content;
            $data['rows'][$k]['bet_rate'] = $bet_rate;
            $data['rows'][$k]['corner_num'] = $corner_num;
        }

    }

    original_phone_request_response($status,$describe,$data);
}else {
    echo json_encode($data);
}
