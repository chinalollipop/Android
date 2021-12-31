<?php
include_once('include/config.inc.php');


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.1';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);
    }else {
        exit(json_encode(['err' => '-1', 'msg' => '请重新登录']));
    }
}
$langx=$_SESSION['Language'];
require ("include/traditional.$langx.inc.php");

$name = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$gtype = $_REQUEST['gtype'] ; // 查询类型 2018 新增
//$type = $_REQUEST['type'] ; // 查询类型 2018 新增 ，0 未结注单，1已结注单
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
//$chk_cw=$_REQUEST['chk_cw'];
//
//if ($chk_cw=='' or $chk_cw=='Y'){
//    $chk_cw='N';
//    $display='Y';
//    $ncancel=" and Cancel=0 and M_Result='' ";
//    $caption=$Tod_Watch_Canceled_Wagers; // 观看取消交易单
//    $nosql = "select ID from ".DBPREFIX."web_report_data where M_Name='$name' and Cancel=1 and M_Date='".date('Y-m-d')."' order by orderby,BetTime desc";
//}else{
//    $chk_cw='Y';
//    $display='N';
//    $ncancel=" and Cancel=1 and M_Date='".date('Y-m-d')."'";
//    $caption=$Tod_Watch_Normal_Wagers; // 观看有效交易单
//    $nosql = "select ID from ".DBPREFIX."web_report_data where M_Name='$name' and Cancel=0 and M_Result='' order by orderby,BetTime desc";
//}

// 交易状况页面为未结算注单
$sql = "select ID,MID,LineType,Active,Gtype,M_Date,BetTime,orderNo,$middle as Middle,$bettype as BetType,BetScore,Gwin,OddsType,Cancel,Danger,Confirmed from ".DBPREFIX."web_report_data where Cancel=0 and M_Result='' and Gtype ='$gtype' and M_Name='$name' order by orderby,BetTime desc";
$result = mysqli_query($dbLink,$sql); // 结算
$cou=mysqli_num_rows($result); // 总数
//echo $sql; die;
//$resultn = mysqli_query($dbMasterLink,$nosql); // 查询取消注单条目 | 或者有效注单条目
//$nocount=mysqli_num_rows($resultn);

$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink, $mysql);
$cou_current_page=mysqli_num_rows($result); // 总数

$data=array();
$data['total']=$cou; // 总条目
$data['num_per_page']=$page_size; // 每页条数
$data['currentpage']=$page; // 当前页号
$data['page_count']=$page_count; // 总页数
$data['perpage']= $cou_current_page; // 当前页条数

$data2=array();
while($row = mysqli_fetch_assoc($result)) {
    $data2[]=$row;
}

foreach ($data2 as $k => $row){
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
