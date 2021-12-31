<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/curl_http.php");
require ("../include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    setcookie('login_uid','');
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}


$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
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
  }else{
  	$today = $list_date;
  	$date_list=mktime(0,0,0,substr($list_date,5,2),substr($list_date,8,2),substr($list_date,0,4));
  	$tomorrow = date("Y-m-d",mktime (0,0,0,date("m",$date_list),date("d",$date_list)+1,date("Y",$date_list)));
  	$lastday  = date("Y-m-d",mktime (0,0,0,date("m",$date_list),date("d",$date_list)-1,date("Y",$date_list)));
  	if (strcmp($tomorrow,date("Y-m-d"))>0){
  		$tomorrow="";
  	}
  }

  if($gtype == 'NFS' || $gtype == 'FI') {
		$yesterday='<a href="result.php?game_type='.$gtype.'&today='.$lastday.'&uid='.$uid.'">昨天</a>';
		if (!empty($tomorrow)){
			$tomorrow='  / <a href="result.php?game_type='.$gtype.'&today='.$tomorrow.'&uid='.$uid.'">明天</a>';
		}
  } else {
		$yesterday='<a href="result.php?game_type='.$gtype.'&list_date='.$lastday.'&uid='.$uid.'">昨天</a>';
		if (!empty($tomorrow)){
			$tomorrow='  / <a href="result.php?game_type='.$gtype.'&list_date='.$tomorrow.'&uid='.$uid.'">明天</a>';
		}
  }

$date_search=$yesterday.$tomorrow;

require ("../include/traditional.$langx.inc.php");

//	$mysql = "select Uid_ms,datasite_ms,NAME_ms,Passwd_ms,datasite,datasite_en,datasite_tw,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
//	$result = mysqli_query($dbMasterLink,$mysql);
//	$row = mysqli_fetch_assoc($result);
//	$suid = $row['Uid_ms'];
//	$site = $row['datasite_ms'];
//	switch($gtype){
//		case "VB":
//		$filename="".$site."/app/member/result/result_vb.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
//		break;
//		case "TN":
//		$filename="".$site."/app/member/result/result_tn.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
//		break;
//		default:
//		$filename="".$site."/app/member/result/result.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
//	}
//
//	$curl = new Curl_HTTP_Client();
//	$curl->store_cookies("/tmp/cookies.txt");
//	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
//	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
//
//	$html_data=$curl->fetch_url($filename);
//	$html_data=str_replace($suid,$uid,$html_data);
//	$res=explode('<td class="mem">',$html_data);
	// $res=explode('<td class="mem">',$res[1]);

$sql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR ,Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$gtype' and M_Date='$list_date' and (MB_Inball !='' or TG_Inball !='' or MB_Inball_HR !='' or TG_Inball_HR !='') order by M_Start,M_League,MB_Team asc" ;
//echo $sql ;
$result = mysqli_query($dbLink,$sql);
$count=mysqli_num_rows($result);
// $row_sou = mysqli_fetch_array($result);
$data_arr = array();
$data_arr_after = array();

while( $row_sou = mysqli_fetch_array($result) ){ // 赋值给数组

    if ($row_sou["MB_Inball_HR"]<0 and $row_sou["MB_Inball"]<0 and $row_sou["TG_Inball_HR"]<0 and $row_sou["TG_Inball"]<0) {
        $Intabll_HR = 'Score'.abs($row_sou['MB_Inball_HR']);
        $row_sou["MB_Inball_HR"] = $row_sou["MB_Inball"]=$row_sou["TG_Inball_HR"]=$row_sou["TG_Inball"]= ${$Intabll_HR};
    }
    $data_arr[] = $row_sou ;
}

foreach($data_arr as $k=>$v) { // 按联赛组合数组
    $data_arr_after[$v["M_League"]][] = $v;
}
// var_dump($data_arr_after);

?>
<html>
<head>
<title>FT_result</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    h2{ height: 29px;background-color: #86715c;}
    .showleg ,#fav_bar,h2{display: none;}
</style>

</head>

<body id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">
<!-- 修改注掉 -->
<table border="0" cellpadding="0" cellspacing="0" id="box">
<!--  <tr>-->
<!--    <td class="top">-->
<!--      <form name="game_result" action="result.php?uid=--><?php //echo $uid ?><!--" method=POST>-->
<!---->
<!--        <h1><em><select name="game_type" class="za_select">-->
<!--            <option value="FT" >足球</option>-->
<!--            <option value="BK">篮球</option>-->
<!--            <option value="BS">棒球</option>-->
<!--            <option value="TN">网球</option>-->
<!--            <option value="VB">排球</option>-->
<!--            <option value="OP">其它</option>-->
<!--          </select>&nbsp;赛事结果</em>-->
<!--  	      <span class="rig">--><?php //echo $date_search?>
<!--		  <input id="today_gmt" type=TEXT name="today" value="--><?php //echo $today;?><!--" size="9" maxlength="10" class="txt">-->
<!--  	      <input type="submit" value="查询" name="submit"></span>-->
<!--        </h1>-->
<!--  </form>-->
<!--	</td>-->
<!--</tr>-->
    <tr>
        <td class="top">
            <form name="game_result" action="result.php?uid=<?php echo $uid ?>" method="POST">
                <input type="hidden" name="game_type" value="FT">
                <h1><em><?php echo $gametitle;?>赛事结果</em>
                    <span class="rig">
                        <?php echo $date_search?>
                        <input id="today_gmt" type="TEXT" name="today" value="<?php echo $today;?>" size="9" maxlength="10" class="txt">
  	                    <input type="submit" value="查询" name="submit"></span>
                </h1>
            </form>
        </td>
    </tr>



  <tr>
    <td class="mem">

        <table border="0" cellspacing="1" cellpadding="0" class="game">
            <tbody>
            <tr class="mem">
                <th class="time">时间</th>
                <!--<th width="6%">场次</th>-->
                <th width="71%">比赛队伍</th>
                <th width="7%">半场</th>
                <th width="7%">全场</th>
            </tr>
            <?php
                if($count>0){
                    foreach($data_arr_after as $key=>$row){
                        // var_dump($row) ;
                         echo '<tr class="league_name" data-league="'.$key.'"><td colspan="11" class="b_hline">' .$key . '</td></tr>';
                         for($i=0;$i<count($row);$i++){
                            echo '<tr class="b_cen">
                                <td>' . substr($row[$i]["M_Date"], 5) . '<br>' . $row[$i]["M_Time"] . '</td> 
                                <td align="left">' . $row[$i]["MB_Team"] . '<br>' . $row[$i]["TG_Team"] . '</td>
                                <td><font color="#CC0000"><b><span style="overflow:hidden;">' . $row[$i]["MB_Inball_HR"] . '</span><br><span style="overflow:hidden;">' . $row[$i]["TG_Inball_HR"] . '</span></b></font></td>
                                <td><font color="#CC0000"><b><span style="overflow:hidden;">' . $row[$i]["MB_Inball"] . '</span><br><span style="overflow:hidden;">' . $row[$i]["TG_Inball"] . '</span></b></font></td>
                              </tr>';
                        }

                   }

                }

            ?>


<!--            <tr class="b_cen">-->
<!--                <td>07-24<br>05:00a</td>-->
<!--                td>0<br>0</td>-->
<!--                <td align="left">亚布洛内茨U21<br>利贝雷茨U21</td>-->
<!--                <td><font color="#CC0000"><b><span style="overflow:hidden;">2</span><br><span style="overflow:hidden;">2</span></b></font></td>-->
<!--                <td><font color="#CC0000"><b><span style="overflow:hidden;">3</span><br><span style="overflow:hidden;">2</span></b></font></td>-->
<!--            </tr>-->
<!---->
<!--            <tr class="b_cen">-->
<!--                <td>07-24<br>05:00a</td>-->
<!--               <td>0<br>0</td>-->
<!--                <td align="left">亚布洛内茨U21<br>利贝雷茨U21</td>-->
<!--                <td><font color="#CC0000"><b><span style="overflow:hidden;">2</span><br><span style="overflow:hidden;">2</span></b></font></td>-->
<!--                <td><font color="#CC0000"><b><span style="overflow:hidden;">3</span><br><span style="overflow:hidden;">2</span></b></font></td>-->
<!--            </tr>-->

            </tbody>
        </table>
    <tr><td id="foot"><b>&nbsp;</b></td></tr>

    <?php
/*        if($res[1]){
            echo $res[1] ;
        }else{ // 没有数据
            $nodata='<table border="0" cellspacing="1" cellpadding="0" class="game">
                   <tbody> 
                   <tr class="mem"><th class="time">时间</th><th width="6%">场次</th><th width="71%">比赛队伍</th><th width="7%">半场</th> <th width="7%">全场</th> </tr>
                 
                 </tbody>
                   </table>
                 <tr><td id="foot" ><b>&nbsp;</b></td></tr>';
            echo $nodata;
        }

        */?>
	</td>
</tr>
</table>
<script language="javascript" src="../../../js/jquery.js"></script>
<script language="javascript" src="../../../js/result.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var langx='<?php echo $langx?>';

</script>
</body>
</html>
