<?php
/*
 *  这个文件没有用
 * */
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$uid=$_REQUEST['uid'];
$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

$curl->set_referrer("".$site."/app/member/browse_FS/loadgame_R.php?uid=".$uid."&langx=en-us&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/browse_FS/reloadgame_R.php?uid=".$uid."&langx=en-uschoice=ALL&LegGame=&pages=1&records=40&FStype=&area_id=&item_id=&rtype=fs");

//echo $html_data;exit;
$a = array(
"if(self == top)",
"<script>",
"</script>",
"]=new Array()",
"parent.GameBU=new Array();",
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
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace(");",")",$messages);
	$messages=str_replace("cha(9)","",$messages);
	$datainfo=eval("return $messages;");
	$ntype = '';
    $ftype = '';
	$team = '';
    $rate = '';
    $num = $datainfo[5];
    for ($s=0; $s<$num; ++$s){
         $game_num = $s * 4 + 4;		 
		 $team = $team . $datainfo[$game_num + 4].",";
	}
	$gtype=$datainfo[$gname_si + 6];
    $dataArray[$datainfo[0]]=(array($team,$datainfo[2],$datainfo[3])); // 把数据放在二维数组里面
//	$sql="update ".DBPREFIX."match_crown set MB_Team_en='$team',M_League_en='$datainfo[2]',M_Item_en='$datainfo[3]' where MID='$datainfo[0]'";
//	mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
}

if($cou>0) { //可以抓到数据
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set ";
    $m_sql .="MB_Team_en = CASE MID " ;
    $t_sql .="M_League_en = CASE MID ";
    $l_sql .="M_Item_en = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    // echo $sql ;
    mysqli_query($dbMasterLink,$sql) or die ("操作失败!!");

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
      冠军数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英文 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>