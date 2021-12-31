<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
    require("../../include/config.inc.php");
    require_once("../../../../../common/sportCenterData.php");
    require("../../include/curl_http.php");
    require_once("../../include/address.mem.php");

    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_bk_re'];

//获取刷水账号
$cou=0;
$redisObj = new Ciredis();
$langx="en-us";

$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$dataArray= array() ; // 把需要的数据重新放在数组里面
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	$curl->set_referrer("".$value['Datasite']."/app/member/BK_browse/index.php?rtype=re_all&uid=".$value['Uid']."&langx=".$langx."&mtype=4");
	$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/BK_browse/body_var.php?rtype=re_all&uid=".$value['Uid']."&langx=".$langx."&mtype=4");
	$matches = get_content_deal($html_data);
	$cou=sizeof($matches);
	if($cou>0){//可以抓到数据
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");

            // 将从正网拉取的测试数据过滤掉
            // stripos 查找字符串首次出现的位置（不区分大小写）
            $pos_m = stripos($datainfo[2], 'test'); // 查找联赛名称是否含有 test
            $pos_mb = stripos($datainfo[5], 'test'); // 检查主队名称是否含有 test
            $pos_tg = stripos($datainfo[6], 'test'); // 检查客队名称是否含有 test
            if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false){
                continue;
            }
            $dataArray[$datainfo[0]]=(array($datainfo[2],$datainfo[5],$datainfo[6])); // 把数据放在二维数组里面
		}
		break;
	} 
}
$redisObj->setOne("BK_Running_Num",(int)$cou);

if($cou>0 and count($dataArray)>0) { //可以抓到数据
    //var_dump($dataArray);
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="M_League_en = CASE MID " ;
    $t_sql .="MB_Team_en = CASE MID ";
    $l_sql .="TG_Team_en = CASE MID ";

    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句

    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    // echo $sql ;
    mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");

}
function get_content_deal($html_data){
	$a = array(
		"if(self == top)",
		"<script>",
		"</script>",
		"new Array()",
		"parent.GameBK=new Array();",
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
	$msg = str_replace($a,$b,$html_data);
	preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
	return $matches[0];	
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
      走地数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英语 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
