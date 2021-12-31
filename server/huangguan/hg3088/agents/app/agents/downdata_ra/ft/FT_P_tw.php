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
$settime=$refurbishTimeData[0]['udp_ft_p'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=p&uid=".$uid."&langx=zh-tw&mtype=4");
$html_data=$curl->fetch_url("".$site."/app/member/FT_browse/body_var.php?rtype=p&uid=".$uid."&langx=zh-tw&mtype=4");

$a = array(
"if(self == top)",
"<script>",
"\n\n"
);
$b = array(
"",
"",
""
);
unset($matches);
unset($datainfo);
$msg = str_replace($a,$b,$html_data);
preg_match_all("/]=new Array\((.+?)\);/is",$msg,$matches);
$cou=sizeof($matches[0]);
$dataArray= array() ; // 把需要的数据重新放在数组里面
if($cou>0){
    for($i=0;$i<$cou;$i++) {
        $messages=$matches[0][$i];
        $messages=str_replace("]=new Array(","",$messages);
        $messages=str_replace("'","",$messages);
        $messages=str_replace(");","",$messages);
        $datainfo=explode(",",$messages);

        // 将从正网拉取的测试数据过滤掉
        // stripos 查找字符串首次出现的位置（不区分大小写）
        $pos_m = stripos($datainfo[2], 'test'); // 查找联赛名称是否含有 test
        $pos_m_tw = stripos($datainfo[2], '測試'); // 查找联赛名称是否含有 測試
        $pos_mb = stripos($datainfo[5], 'test'); // 检查主队名称是否含有 test
        $pos_mb_tw = stripos($datainfo[5], '測試'); // 检查主队名称是否含有 測試
        $pos_tg = stripos($datainfo[6], 'test'); // 检查客队名称是否含有 test
        $pos_tg_tw = stripos($datainfo[6], '測試'); // 检查客队名称是否含有 測試
        if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
            $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
            continue;
        }
        $dataArray[$datainfo[0]]=(array($datainfo[8],$datainfo[9],$datainfo[10])); // 把数据放在二维数组里面
    }

    if (count($dataArray)>0){
        $ids = implode(',', array_keys($dataArray));
        $e_sql .= "END,";
        $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
        $m_sql .="MB_P_Win = CASE MID " ;
        $t_sql .="TG_P_Win = CASE MID ";
        $l_sql .="M_P_Flat = CASE MID ";
        $ps_sql .="P_Show = CASE MID ";

        foreach ($dataArray as $id => $ordinal) {
            $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
            $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
            $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
            $ps_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
        }
        $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$ps_sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
        // echo $sql ;
        mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");
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

var limit="<?php echo $settime?>" ;
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
		curtime=curmin+"秒後自動獲取!" 
	else 
		curtime=cursec+"秒後自動獲取!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      標準過關數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
