<?php
require ("../../include/config.inc.php");
require_once("../../../../../common/sportCenterData.php");
require("../../include/redis.php");
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
$settime=$refurbishTimeData[0]['udp_ft_pd'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=hpd&uid=".$uid."&langx=zh-cn&mtype=3");
$allcount=0;
$dataArray= array() ; // 把需要的数据重新放在数组里面
for($page_no=0;$page_no<15;$page_no++){
    $html_data=$curl->fetch_url("".$site."/app/member/FT_browse/body_var.php?rtype=hpd&uid=".$uid."&langx=zh-cn&mtype=4&page_no=".$page_no);

    $a = array(
    "if(self == top)",
    "<script>",
    "</script>",
    "\n\n"
    );
    $b = array(
    "",
    "",
    "",
    ""
    );
    unset($matches);
    unset($datainfo);
    $msg = str_replace($a,$b,$html_data);
    preg_match_all("/]=new Array\((.+?)\);/is",$msg,$matches);
    $cou=sizeof($matches[0]);
    for($i=0;$i<$cou;$i++){
        $messages=$matches[0][$i];
        $messages=str_replace("]=new Array(","",$messages);
        $messages=str_replace("'","",$messages);
        $messages=str_replace(");","",$messages);
        $datainfo=explode(",",$messages);
        if(!empty($datainfo)){

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

            $datainfo[0] = $datainfo[0]-1 ;
            $dataArray[$datainfo[0]]=(array($datainfo[8],$datainfo[9],$datainfo[10],$datainfo[11],$datainfo[12],$datainfo[13],$datainfo[14],$datainfo[15],$datainfo[16],$datainfo[17],$datainfo[18],$datainfo[19],$datainfo[20],$datainfo[21],$datainfo[22],$datainfo[23],$datainfo[24],$datainfo[25],$datainfo[26],$datainfo[27],$datainfo[28],$datainfo[29],$datainfo[30],$datainfo[31],$datainfo[32],$datainfo[33])); // 把数据放在二维数组里面

            $allcount++;
        }else{
            continue;
        }
    }
}

if($allcount>0 and count($dataArray)>0) { //可以抓到数据
    //var_dump($dataArray);
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="MB1TG0H = CASE MID " ;
    $t_sql .="MB2TG0H = CASE MID ";
    $l_sql .="MB2TG1H = CASE MID ";
    $tp_sql .="MB3TG0H = CASE MID ";
    $mb_sql .="MB3TG1H = CASE MID ";
    $tb_sql .="MB3TG2H = CASE MID ";
    $mr_sql .="MB4TG0H = CASE MID ";
    $tr_sql .="MB4TG1H = CASE MID ";
    $tt_sql .="MB4TG2H = CASE MID ";
    $pr_sql .="MB4TG3H = CASE MID ";
    $mf_sql .="MB0TG0H = CASE MID ";
    $mh_sql .="MB1TG1H = CASE MID ";
    $mh2_sql .="MB2TG2H = CASE MID ";
    $mh3_sql .="MB3TG3H = CASE MID ";
    $mh4_sql .="MB4TG4H = CASE MID ";
    $u5_sql .="UP5H = CASE MID ";
    $m1_sql .="MB0TG1H = CASE MID ";
    $m2_sql .="MB0TG2H = CASE MID ";
    $mb1_sql .="MB1TG2H = CASE MID ";
    $mb2_sql .="MB0TG3H = CASE MID ";
    $mb3_sql .="MB1TG3H = CASE MID ";
    $mb4_sql .="MB2TG3H = CASE MID ";
    $mb5_sql .="MB0TG4H = CASE MID ";
    $mb6_sql .="MB1TG4H = CASE MID ";
    $mb7_sql .="MB2TG4H = CASE MID ";
    $mb8_sql .="MB3TG4H = CASE MID ";
    $hs_sql .="HPD_Show = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
        $tp_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
        $mb_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
        $tb_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
        $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
        $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
        $tt_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
        $pr_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
        $mf_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
        $mh_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
        $mh2_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
        $mh3_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
        $mh4_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
        $u5_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
        $m1_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
        $m2_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
        $mb1_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
        $mb2_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
        $mb3_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
        $mb4_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
        $mb5_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
        $mb6_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
        $mb7_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
        $mb8_sql .= "WHEN $id THEN '$ordinal[25]' " ; // 拼接SQL语句
        $hs_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tp_sql.$e_sql.$mb_sql.$e_sql.$tb_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$tt_sql.$e_sql.$pr_sql.$e_sql.$mf_sql.$e_sql.$mh_sql.$e_sql.$mh2_sql.$e_sql.$mh3_sql.$e_sql.$mh4_sql.$e_sql.$u5_sql.$e_sql.$m1_sql.$e_sql.$m2_sql.$e_sql.$mb1_sql.$e_sql.$mb2_sql.$e_sql.$mb3_sql.$e_sql.$mb4_sql.$e_sql.$mb5_sql.$e_sql.$mb6_sql.$e_sql.$mb7_sql.$e_sql.$mb8_sql.$e_sql.$hs_sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    // echo $sql ;
    mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");

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
      上半波胆数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="刷新 <?php echo $allcount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
