<?php
/**
 * 足球滚球
 * Date: 2018/10/17
 */


if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    require (CONFIG_DIR."/include/config.inc.php");
    require (CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/redis.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
    require_once("../../include/config.inc.php");
    require_once("../../include/curl_http.php");
    require_once("../../include/redis.php");
    require_once("../../include/address.mem.php");

// 判斷IP是否在白名單
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登錄失敗!!\\n未被授權訪問的IP!!');
        }
    }
}

// 獲取刷新時間
$mysql = "select udp_ft_re from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime = $row['udp_ft_re'];

// 抓取數據
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");

$m_date = date('Y-m-d');
$dataCount = 0;
$dataArray = [];
for($page = 1; $page <= 10; $page ++) {
    $curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Roll_l.aspx");
    $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Roll.aspx?p=" . $page);
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
    if ($count > 0) {
        $start = 0;
        $insertSql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,M_League,M_League_tw,RB_Show)VALUES";
        for ($i = 0; $i < $count; $i++) {
            $messages = $matches[0][$i];
            $messages = str_replace(");", ")", $messages);
            $messages = str_replace("cha(9)", "", $messages);
            $dataInfo = eval("return $messages;");
            if(!empty($dataInfo)){
                $pos_m = stripos($dataInfo[4], 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($dataInfo[4], '測試'); // 查找联赛名称是否含有 測試
                $pos_mb = stripos($dataInfo[5], 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($dataInfo[5], '測試'); // 检查主队名称是否含有 測試
                $pos_tg = stripos($dataInfo[6], 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($dataInfo[6], '測試'); // 检查客队名称是否含有 測試
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false || $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false) {
                    continue;
                }
                // 時間處理
                $m_date = date('Y') . "-" . $dataInfo[2]; // 日期（2018-09-18）
                $m_time = date('h:ia', strtotime($value[38]) - 12 * 3600); // 美东时间：11:00pm
                $timestamp = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dataInfo[38])) - 12 * 3600);
                // 檢測更新
                $checkSql = "SELECT `MID` FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `MID`='$dataInfo[0]'";
                $checkResult = mysqli_query($dbLink, $checkSql);
                $check = mysqli_num_rows($checkResult);
                if ($check == 0) {
                    if ($start == 0) {
                        $insertSql .= "('$dataInfo[0]','FT','$timestamp','$m_date','$m_time','$dataInfo[5]','$dataInfo[6]','$dataInfo[5]','$dataInfo[6]','$dataInfo[4]','$dataInfo[4]','1')";
                    } else {
                        $insertSql .= ",('$dataInfo[0]','FT','$timestamp','$m_date','$m_time','$dataInfo[5]','$dataInfo[6]','$dataInfo[5]','$dataInfo[6]','$dataInfo[4]','$dataInfo[4]','1')";
                    }
                    $start ++;
                } else {
                    $dataArray[$dataInfo[0]] = [
                        $check,
                        $timestamp,
                        $m_date,
                        $m_time,
                        $dataInfo[4],
                        $dataInfo[5],
                        $dataInfo[6],
                        stripos($dataInfo[12], '*') !== false ? 'C' : 'H',
                        str_replace('*', '', $dataInfo[12]),
                        $dataInfo[10],
                        $dataInfo[11],
                        $dataInfo[16],
                        $dataInfo[18],
                        $dataInfo[17],
                        stripos($dataInfo[28], '*') !== false ? 'C' : 'H',
                        str_replace('*', '', $dataInfo[28]), // 上半让球个数
                        $dataInfo[21],
                        $dataInfo[22],
                        $dataInfo[29],
                        $dataInfo[23],
                        $dataInfo[24]
                    ];
                }
                $dataCount ++;
            }else{
                continue;
            }
        }
        if ($start > 0) { // 有新增数据
            if(!mysqli_query($dbMasterLink, $insertSql))
                exit('入庫足球滾球數據失敗！！！');
        }
    } else {
        break;
    }
}
$redisObj = new Ciredis();
$redisObj->setOne("FT_Running_Num", (int)$dataCount);

$updateCount = 0;
if($dataCount > 0 and count($dataArray) > 0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $ty_sql .= "Type = CASE MID " ;
    $m_sql .= "M_Start = CASE MID ";
    $t_sql .= "M_Date = CASE MID ";
    $l_sql .= "M_Time = CASE MID ";
    $jl_sql .= "M_League = CASE MID " ; // 简体中文-联赛名称
    $lm_sql .= "M_League_tw = CASE MID ";
    $jht_sql .= "MB_Team = CASE MID "; // 简体中文-主队名称
    $jct_sql .= "TG_Team = CASE MID "; // 简体中文-客队名称
    $mtw_sql .= "MB_Team_tw = CASE MID ";
    $ttw_sql .= "TG_Team_tw = CASE MID ";
    $stp_sql .= "ShowTypeRB = CASE MID ";
    $mr_sql .= "M_LetB_RB = CASE MID ";
    $mrr_sql .= "MB_LetB_Rate_RB = CASE MID ";
    $trr_sql .= "TG_LetB_Rate_RB = CASE MID ";
    $mbd_sql .= "MB_Dime_RB = CASE MID ";
    $tgd_sql .= "TG_Dime_RB = CASE MID ";
    $tgr_sql .= "TG_Dime_Rate_RB = CASE MID ";
    $mbr_sql .= "MB_Dime_Rate_RB = CASE MID ";
    $sdr_sql .= "ShowTypeHRB = CASE MID ";
    $sthr_sql .= "M_LetB_RB_H = CASE MID ";
    $mlh_sql .= "MB_LetB_Rate_RB_H = CASE MID ";
    $mlrh_sql .= "TG_LetB_Rate_RB_H = CASE MID ";
    $tlrh_sql .= "MB_Dime_RB_H = CASE MID ";
    $mdh_sql .= "TG_Dime_RB_H = CASE MID ";
    $tdh_sql .= "TG_Dime_Rate_RB_H = CASE MID ";
    $tdrh_sql .= "MB_Dime_Rate_RB_H = CASE MID ";
    $rbshow_sql .= "RB_Show = CASE MID ";

    foreach ($dataArray as $id => $ordinal) {
        $ty_sql .= "WHEN $id THEN 'FT' ";
        $m_sql .= "WHEN $id THEN '$ordinal[1]' ";
        $t_sql .= "WHEN $id THEN '$ordinal[2]' ";
        $l_sql .= "WHEN $id THEN '$ordinal[3]' ";
        $jl_sql .= "WHEN $id THEN '$ordinal[4]'";
        $lm_sql .= "WHEN $id THEN '$ordinal[4]' ";
        $jht_sql .= "WHEN $id THEN '$ordinal[5]' "; // 简体中文-主队名称
        $jct_sql .= "WHEN $id THEN '$ordinal[6]' ";
        $mtw_sql .= "WHEN $id THEN '$ordinal[5]' ";
        $ttw_sql .= "WHEN $id THEN '$ordinal[6]' ";
        $stp_sql .= "WHEN $id THEN '$ordinal[7]' "; // 滚球让球 H C
        $mr_sql .= "WHEN $id THEN '$ordinal[8]' ";
        $mrr_sql .= "WHEN $id THEN '$ordinal[9]' ";
        $trr_sql .= "WHEN $id THEN '$ordinal[10]' ";
        $mbd_sql .= "WHEN $id THEN '$ordinal[11]' "; // 主队全场大小  O 大  U 小
        $tgd_sql .= "WHEN $id THEN '$ordinal[11]' "; // 客队全场大小  O 大  U 小
        $tgr_sql .= "WHEN $id THEN '$ordinal[12]' ";
        $mbr_sql .= "WHEN $id THEN '$ordinal[13]' ";
        $sdr_sql .= "WHEN $id THEN '$ordinal[14]' ";
        $sthr_sql .= "WHEN $id THEN '$ordinal[15]' ";
        $mlh_sql .= "WHEN $id THEN '$ordinal[16]' ";
        $mlrh_sql .= "WHEN $id THEN '$ordinal[17]' ";
        $tlrh_sql .= "WHEN $id THEN '$ordinal[18]' ";
        $mdh_sql .= "WHEN $id THEN '$ordinal[18]' ";
        $tdh_sql .= "WHEN $id THEN '$ordinal[19]' ";
        $tdrh_sql .= "WHEN $id THEN '$ordinal[20]' ";
        $rbshow_sql .= "WHEN $id THEN '1' ";
        $updateCount ++;
    }

    if($updateCount > 0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$jl_sql.$e_sql.$lm_sql.$e_sql.$jht_sql.$e_sql.$jct_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.$tdh_sql.$e_sql.$tdrh_sql.$e_sql.$rbshow_sql ;
        $sql .= "END WHERE MID IN ($ids)";
        if(!mysqli_query($dbMasterLink, $sql))
            exit('更新足球滾球數據失敗！！！');
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
      走地數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $dataCount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
