<?php
/**
 * 半全場
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
$mysql = "select udp_ft_f from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime = $row['udp_ft_f'];

// 抓取數據
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");

$m_date = date('Y-m-d');
$dataCount = 0;
$dataArray = [];
for($page = 1; $page <= 10; $page ++) {
	$curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_BQC_l.aspx");
    $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_BQC.aspx?p=" . $page);
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
        for ($i = 0; $i < $count; $i++) {
            $messages = $arrData[$i];
            $messages = str_replace(");",")",$messages);
            $messages = str_replace("cha(9)","",$messages);
            $dataInfo = eval("return $messages;");
            if(!empty($dataInfo)){
                $pos_m = stripos($dataInfo[2], 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($dataInfo[2], '測試'); // 查找联赛名称是否含有 測試
                $pos_mb = stripos($dataInfo[5], 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($dataInfo[5], '測試'); // 检查主队名称是否含有 測試
                $pos_tg = stripos($dataInfo[6], 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($dataInfo[6], '測試'); // 检查客队名称是否含有 測試
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false || $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                    continue;
                }
                $dataArray[$dataInfo[0]] =[
                    $dataInfo[10],
                    $dataInfo[11],
                    $dataInfo[12],
                    $dataInfo[13],
                    $dataInfo[14],
                    $dataInfo[15],
                    $dataInfo[16],
                    $dataInfo[17],
                    $dataInfo[18]
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
if($dataCount > 0 and count($dataArray) > 0) { //可以抓到数据
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="MBMB = CASE MID " ;
    $t_sql .="MBFT = CASE MID ";
    $l_sql .="MBTG = CASE MID ";
    $tp_sql .="FTMB = CASE MID ";
    $mb_sql .="FTFT = CASE MID ";
    $tb_sql .="FTTG = CASE MID ";
    $mr_sql .="TGMB = CASE MID ";
    $tr_sql .="TGFT = CASE MID ";
    $tt_sql .="TGTG = CASE MID ";
    $pr_sql .="F_Show = CASE MID ";
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
        $pr_sql .= "WHEN $id THEN '1' " ;
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tp_sql.$e_sql.$mb_sql.$e_sql.$tb_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$tt_sql.$e_sql.$pr_sql ;
    $sql .="END WHERE MID IN ($ids)";
    if(!mysqli_query($dbMasterLink, $sql))
        exit('更新入球數數據失敗！！！');
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
      半全場數據接收<br>
      <span id="timeinfo"></span><br>
    <input type=button name=button value="繁體 <?php echo $dataCount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
