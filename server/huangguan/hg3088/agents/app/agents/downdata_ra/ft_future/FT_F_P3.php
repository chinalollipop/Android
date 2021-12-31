<?php
require ("../../include/config.inc.php");
require_once("../../../../../common/sportCenterData.php");

require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$refurbishTimeData = refurbishTime();
$uid =$refurbishTimeData[0]['Uid'];
$site=$refurbishTimeData[0]['datasite'];
$settime=$refurbishTimeData[0]['udp_fu_pr'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/FT_future/index.php?rtype=p3&uid=".$uid."&langx=zh-cn&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/FT_future/body_var.php?rtype=p3&uid=".$uid."&langx=zh-cn&mtype=3");
//echo $html_data;exit;
$a = array(
"if(self == top)",
"<script>",
"</script>",
"]=new Array()",
"parent.GameFU=new Array();",
"\n\n",
"_.",
"g([",
"])"
);
$b = array(
"",
"",
"",
"",
"",
"",
"parent.",
"Array(",
")"
);
unset($matches);
unset($datainfo);
$msg = str_replace($a,$b,$html_data);
preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
//echo $msg;exit;
$cou=sizeof($matches[0]);
$dataArray= array() ; // 把需要的数据重新放在数组里面
if($cou>0){
    for($i=0;$i<$cou;$i++){
        $messages=$matches[0][$i];
        $messages=str_replace(");",")",$messages);
        $messages=str_replace("cha(9)","",$messages);
        $datainfo=eval("return $messages;");

        // 将从正网拉取的测试数据过滤掉
        // stripos 查找字符串首次出现的位置（不区分大小写）
        $pos_m = stripos($datainfo[2], 'test'); // 查找联赛名称是否含有 test
        $pos_m_cn = stripos($datainfo[2], '测试'); // 查找联赛名称是否含有 测试
        $pos_mb = stripos($datainfo[5], 'test'); // 检查主队名称是否含有 test
        $pos_mb_cn = stripos($datainfo[5], '测试'); // 检查主队名称是否含有 测试
        $pos_tg = stripos($datainfo[6], 'test'); // 检查客队名称是否含有 test
        $pos_tg_cn = stripos($datainfo[6], '测试'); // 检查客队名称是否含有 测试
        if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
            $pos_m_cn !== false || $pos_mb_cn !== false || $pos_tg_cn !== false){
            continue;
        }

        $MID=$datainfo[0];
        /*
           * $row["MB_P_LetB_Rate_H"];  // 半场让球主队 $datainfo[23] 原来  $datainfo[63]
             $row["TG_P_LetB_Rate_H"]; // 半场让球客队 $datainfo[24] 原来  $datainfo[64]
             $row['MB_P_Dime_Rate_H']; // 半场主队大 $datainfo[28] 原来  $datainfo[68]
             $row['TG_P_Dime_Rate_H']; // 半场客队小 $datainfo[27] 原来  $datainfo[67]
           * */
        $dataArray[$datainfo[0]]=(array($datainfo[7],$datainfo[8],$datainfo[9],$datainfo[10],$datainfo[11],$datainfo[12],$datainfo[13],$datainfo[14],$datainfo[15],$datainfo[16],
            $datainfo[17],$datainfo[18],$datainfo[19],$datainfo[61],$datainfo[62],$datainfo[23],$datainfo[24],$datainfo[65],$datainfo[66],$datainfo[27],
            $datainfo[28],$datainfo[96],$datainfo[97],$datainfo[98])); // 把数据放在二维数组里面
    }

    if (count($dataArray)>0){

        $ids = implode(',', array_keys($dataArray));
        // echo $ids;
        $e_sql .= "END,";
        $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
        $m_sql .="ShowTypeP = CASE MID " ;
        $t_sql .="M_P_LetB = CASE MID ";
        $l_sql .="MB_P_LetB_Rate = CASE MID ";
        $tg_sql .="TG_P_LetB_Rate = CASE MID ";
        $mp_sql .="MB_P_Dime = CASE MID ";
        $tp_sql .="TG_P_Dime = CASE MID ";
        $mr_sql .="MB_P_Dime_Rate = CASE MID ";
        $tr_sql .="TG_P_Dime_Rate = CASE MID ";
        $sp_sql .="S_P_Single_Rate = CASE MID ";
        $spd_sql .="S_P_Double_Rate = CASE MID ";
        $mv_sql .="MB_P_Win_Rate = CASE MID ";
        $tv_sql .="TG_P_Win_Rate = CASE MID ";
        $mf_sql .="M_P_Flat_Rate = CASE MID ";
        $shp_sql .="ShowTypeHP = CASE MID ";
        $mpl_sql .="M_P_LetB_H = CASE MID ";
        $mplr_sql .="MB_P_LetB_Rate_H = CASE MID ";
        $tplr_sql .="TG_P_LetB_Rate_H = CASE MID ";
        $mpd_sql .="MB_P_Dime_H = CASE MID ";
        $tpd_sql .="TG_P_Dime_H = CASE MID ";
        $tpr_sql .="TG_P_Dime_Rate_H = CASE MID ";
        $mh_sql .="MB_P_Dime_Rate_H = CASE MID ";
        $tpsr_sql .="MB_P_Win_Rate_H = CASE MID ";
        $msh_sql .="TG_P_Win_Rate_H = CASE MID ";
        $mpr_sql .="M_P_Flat_Rate_H = CASE MID ";
        $p3_sql .="P3_Show = CASE MID ";
        foreach ($dataArray as $id => $ordinal) {
            $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
            $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
            $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
            $tg_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
            $mp_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
            $tp_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
            $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
            $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
            $sp_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
            $spd_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
            $mv_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
            $tv_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
            $mf_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
            $shp_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
            $mpl_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
            $mplr_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
            $tplr_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
            $mpd_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
            $tpd_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
            $tpr_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
            $mh_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
            $tpsr_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
            $msh_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
            $mpr_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
            $p3_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
        }
        $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tg_sql.$e_sql.$mp_sql.$e_sql.$tp_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$sp_sql.$e_sql.$spd_sql.$e_sql.$mv_sql.$e_sql.$tv_sql.$e_sql.$mf_sql.$e_sql.$shp_sql.$e_sql.$mpl_sql.$e_sql.$mplr_sql.$e_sql.$tplr_sql.$e_sql.$mpd_sql.$e_sql.$tpd_sql.$e_sql.$tpr_sql.$e_sql.$mh_sql.$e_sql.$tpsr_sql.$e_sql.$msh_sql.$e_sql.$mpr_sql.$e_sql.$p3_sql ;
        // echo $sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
        // echo $sql ;
        mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失败!!");

    }
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<script> 

var limit="<?php echo $settime?>" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒后自动获取!" 
	else 
		curtime=cursec+"秒后自动获取!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 
 
</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      综合过关数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="刷新 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
