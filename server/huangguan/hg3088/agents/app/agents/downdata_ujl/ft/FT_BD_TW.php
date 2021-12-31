<?php
/**
 * 波膽
 * Date: 2018/10/27
 */
if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    require (CONFIG_DIR."/include/config.inc.php");
    require (CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
    require_once("../../include/config.inc.php");
    require_once("../../include/curl_http.php");
    require_once("../../include/address.mem.php");

// 判斷IP是否在白名單
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登錄失敗!!\\n未被授權訪問的IP!!');
        }
    }
}

// 獲取刷新時間
$mysql = "select udp_ft_pd from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime=$row['udp_ft_pd'];

// 抓取數據
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");

$m_date = date('Y-m-d');
$dataCount = 0;
$dataArray = [];
for($page = 1; $page <= 10; $page ++) {
	$curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_BD_l.aspx");
    $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_BD.aspx?p=" . $page);
//    $htmlData = file_get_contents('./contents.php');
    $htmlData = mb_convert_encoding($htmlData, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
    // 總頁數
    preg_match('/parent.page=(\s+\d+)/', $htmlData, $matchesPage);
    $pageTotal = trim($matchesPage[1]);
    if($page > $pageTotal)
        break;
    // 單頁數據
    preg_match_all("/Array\((.+?)\);/is", $htmlData, $matches);
    $arrData = $matches[0];
    // 整合數據
    $count = sizeof($arrData);
    if($count > 0){
        for ($i = 0; $i < $count; $i++){
            $messages = $arrData[$i];
            $messages = str_replace(");",")",$messages);
            $messages = str_replace("cha(9)","",$messages);
            $dataInfo = eval("return $messages;");
            if (!empty($dataInfo)){
                $pos_m = stripos($dataInfo[4], 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($dataInfo[4], '測試'); // 查找联赛名称是否含有 測試
                $pos_mb = stripos($dataInfo[5], 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($dataInfo[5], '測試'); // 检查主队名称是否含有 測試
                $pos_tg = stripos($dataInfo[6], 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($dataInfo[6], '測試'); // 检查客队名称是否含有 測試
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false || $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                    continue;
                }
                $dataArray[$dataInfo[0]] = [
                    $dataInfo[10],
                    $dataInfo[11],
                    $dataInfo[12],
                    $dataInfo[13],
                    $dataInfo[14],
                    $dataInfo[15],
                    $dataInfo[16],
                    $dataInfo[17],
                    $dataInfo[18],
                    $dataInfo[19],
                    $dataInfo[20],
                    $dataInfo[21],
                    $dataInfo[22],
                    $dataInfo[23],
                    $dataInfo[24],
                    $dataInfo[25],
                    $dataInfo[34],
                    $dataInfo[35],
                    $dataInfo[36],
                    $dataInfo[37],
                    $dataInfo[38],
                    $dataInfo[39],
                    $dataInfo[40],
                    $dataInfo[41],
                    $dataInfo[42],
                    $dataInfo[43]
                ];
                $dataCount ++;
            }else{
                continue;
            }
        }
    }else{
        break;
    }
}

if($dataCount > 0 and count($dataArray) > 0) {
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="MB1TG0 = CASE MID " ;
    $t_sql .="MB2TG0 = CASE MID ";
    $l_sql .="MB2TG1 = CASE MID ";
    $tp_sql .="MB3TG0 = CASE MID ";
    $mb_sql .="MB3TG1 = CASE MID ";
    $tb_sql .="MB3TG2 = CASE MID ";
    $mr_sql .="MB4TG0 = CASE MID ";
    $tr_sql .="MB4TG1 = CASE MID ";
    $tt_sql .="MB4TG2 = CASE MID ";
    $pr_sql .="MB4TG3 = CASE MID ";
    $mf_sql .="MB0TG0 = CASE MID ";
    $mh_sql .="MB1TG1 = CASE MID ";
    $mh2_sql .="MB2TG2 = CASE MID ";
    $mh3_sql .="MB3TG3 = CASE MID ";
    $mh4_sql .="MB4TG4 = CASE MID ";
    $u5_sql .="UP5 = CASE MID ";
    $m1_sql .="MB0TG1 = CASE MID ";
    $m2_sql .="MB0TG2 = CASE MID ";
    $mb1_sql .="MB1TG2 = CASE MID ";
    $mb2_sql .="MB0TG3 = CASE MID ";
    $mb3_sql .="MB1TG3 = CASE MID ";
    $mb4_sql .="MB2TG3 = CASE MID ";
    $mb5_sql .="MB0TG4 = CASE MID ";
    $mb6_sql .="MB1TG4 = CASE MID ";
    $mb7_sql .="MB2TG4 = CASE MID ";
    $mb8_sql .="MB3TG4 = CASE MID ";
    $hs_sql .="PD_Show = CASE MID ";

    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ;
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ;
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ;
        $tp_sql .= "WHEN $id THEN '$ordinal[3]' " ;
        $mb_sql .= "WHEN $id THEN '$ordinal[4]' " ;
        $tb_sql .= "WHEN $id THEN '$ordinal[5]' " ;
        $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ;
        $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ;
        $tt_sql .= "WHEN $id THEN '$ordinal[8]' " ;
        $pr_sql .= "WHEN $id THEN '$ordinal[9]' " ;
        $mf_sql .= "WHEN $id THEN '$ordinal[10]' " ;
        $mh_sql .= "WHEN $id THEN '$ordinal[11]' " ;
        $mh2_sql .= "WHEN $id THEN '$ordinal[12]' " ;
        $mh3_sql .= "WHEN $id THEN '$ordinal[13]' " ;
        $mh4_sql .= "WHEN $id THEN '$ordinal[14]' " ;
        $u5_sql .= "WHEN $id THEN '$ordinal[15]' " ;
        $m1_sql .= "WHEN $id THEN '$ordinal[16]' " ;
        $m2_sql .= "WHEN $id THEN '$ordinal[17]' " ;
        $mb1_sql .= "WHEN $id THEN '$ordinal[18]' " ;
        $mb2_sql .= "WHEN $id THEN '$ordinal[19]' " ;
        $mb3_sql .= "WHEN $id THEN '$ordinal[20]' " ;
        $mb4_sql .= "WHEN $id THEN '$ordinal[21]' " ;
        $mb5_sql .= "WHEN $id THEN '$ordinal[22]' " ;
        $mb6_sql .= "WHEN $id THEN '$ordinal[23]' " ;
        $mb7_sql .= "WHEN $id THEN '$ordinal[24]' " ;
        $mb8_sql .= "WHEN $id THEN '$ordinal[25]' " ;
        $hs_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tp_sql.$e_sql.$mb_sql.$e_sql.$tb_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$tt_sql.$e_sql.$pr_sql.$e_sql.$mf_sql.$e_sql.$mh_sql.$e_sql.$mh2_sql.$e_sql.$mh3_sql.$e_sql.$mh4_sql.$e_sql.$u5_sql.$e_sql.$m1_sql.$e_sql.$m2_sql.$e_sql.$mb1_sql.$e_sql.$mb2_sql.$e_sql.$mb3_sql.$e_sql.$mb4_sql.$e_sql.$mb5_sql.$e_sql.$mb6_sql.$e_sql.$mb7_sql.$e_sql.$mb8_sql.$e_sql.$hs_sql ;
    $sql .= "END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    if(!mysqli_query($dbMasterLink, $sql))
        exit('更新足球波膽數據失敗！！！');
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
      波膽數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $dataCount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
