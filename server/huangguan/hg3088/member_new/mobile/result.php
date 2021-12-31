<?php
/**
 * 赛果接口
 * result.php
 *
 * @param game_type
 * @param list_date
 * */
include_once('include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);
}

$uid=$_REQUEST['uid'];
$langx=$_SESSION['Language'];
require ("include/traditional.$langx.inc.php");
$gtype=$_REQUEST['game_type'];
switch ($gtype){
    case 'FT':
        $gametitle='足球';
        break;
    case 'BK':
        $gametitle='篮球';
        break;
    case 'TN':
        $gametitle='网球';
        break;
    case 'VB':
        $gametitle='排球';
        break;
    case 'BS':
        $gametitle='棒球';
        break;
    case 'OP':
        $gametitle='其他';
        break;
}
$list_date=empty($_REQUEST['today'])?$_REQUEST['list_date']:$_REQUEST['today'];

if ($list_date==""){
  	$today=$_REQUEST['today'];
  	if (empty($today)){
  		$today 					= 	date("Y-m-d");
  		$tomorrow 			=		"";
  		$lastday 				= 	date("Y-m-d",mktime (0,0,0,date("m"),date("d")-1,date("Y")));
  	}else{
  		$date_list_1		=		explode("-",$today);
  		$d1							=		mktime(0,0,0,$date_list_1[1],$date_list_1[2],$date_list_1[0]);
  		$tomorrow				=		date('Y-m-d',$d1+24*60*60);
  		$lastday				=		date('Y-m-d',$d1-24*60*60);

  		if ($today>=date('Y-m-d')){
  			$tomorrow='';
  		}
  	}
  	$list_date=$today;
}


$sql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR ,Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$gtype' and M_Date='$list_date' and (MB_Inball !='' or TG_Inball !='' or MB_Inball_HR !='' or TG_Inball_HR !='') order by M_Start,M_League,MB_Team asc" ;
$result = mysqli_query($dbLink,$sql);
$count=mysqli_num_rows($result);
$data_arr = array();
$data_arr_after = array();
while( $row_sou = mysqli_fetch_assoc($result) ){
    if ($row_sou["MB_Inball_HR"]<0 and $row_sou["MB_Inball"]<0 and $row_sou["TG_Inball_HR"]<0 and $row_sou["TG_Inball"]<0) {
        $Intabll_HR = 'Score'.abs($row_sou['MB_Inball_HR']);
        $row_sou["MB_Inball_HR"] = $row_sou["MB_Inball"]=$row_sou["TG_Inball_HR"]=$row_sou["TG_Inball"]= ${$Intabll_HR};
    }
    $data_arr[] = $row_sou ;
}
foreach($data_arr as $k=>$v) { // 按联赛组合数组
    $data_arr_after[$v["M_League"]][] = $v;
}
$cou = count($data_arr_after);
$i = 1;
foreach ($data_arr_after as $k => $v){
    $data[$i]['name'] = $k;
    $data[$i]['result'] = $v;
    foreach ($v as $k2 => $v2){
        $mb_team = str_replace('<font color=gray>','',$v2['MB_Team']);
        $data[$i]['result'][$k2]['MB_Team'] = str_replace('</font>','',$mb_team);
        $tg_team = str_replace('<font color=gray>','',$v2['TG_Team']);
        $data[$i]['result'][$k2]['TG_Team'] = str_replace('</font>','',$tg_team);
    }
    $i++;
}

if (count($data)==0){
    $data = [];
}else{
    $data = array_values($data);
}

$status = '200';
$describe = 'success';
original_phone_request_response($status, $describe, $data);
