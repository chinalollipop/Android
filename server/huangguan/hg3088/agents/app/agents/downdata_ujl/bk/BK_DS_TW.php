<?php
/**
 * 籃球單式
 * Date: 2018/10/28
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
$mysql = "select udp_bk_tw from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime = $row['udp_bk_tw'];

// 抓取數據
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");

$m_date = date('Y-m-d');
$dataCount = 0;
$dataArray = [];
for($page = 1; $page <= 20; $page ++){
    $curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/BK_Browser/BK_DS_l.aspx");
    $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/BK_Browser/BK_DS.aspx?p=" . $page);
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
        $start = 0;
        $insertSql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (`MID`,`Type`,`M_Start`,`M_Date`,`M_Time`,`M_League`,`M_League_tw`,`MB_Team`,`MB_Team_tw`,`TG_Team`,`TG_Team_tw`,`M_LetB`,`MB_LetB_Rate`,`TG_LetB_Rate`,`MB_Dime`,`TG_Dime`,`TG_Dime_Rate`,`MB_Dime_Rate`,`S_Single_Rate`,`S_Double_Rate`,`M_Type`,`S_Show`,`more`)VALUES";
        for($i = 0; $i < $count; $i++){
            $messages = $arrData[$i];
            $messages = str_replace(");",")",$messages);
            $messages = str_replace("cha(9)","",$messages);
            $dataInfo = eval("return $messages;");
            
            $pos_m = stripos($dataInfo[3], 'test'); // 查找联赛名称是否含有 test
            $pos_m_tw = stripos($dataInfo[3], '測試'); // 查找联赛名称是否含有 測試
            $pos_mb = stripos($dataInfo[4], 'test'); // 检查主队名称是否含有 test
            $pos_mb_tw = stripos($dataInfo[4], '測試'); // 检查主队名称是否含有 測試
            $pos_tg = stripos($dataInfo[5], 'test'); // 检查客队名称是否含有 test
            $pos_tg_tw = stripos($dataInfo[5], '測試'); // 检查客队名称是否含有 測試
            if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false || $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                continue;
            }
            // 時間處理
            $m_date = date('Y') . "-" . $dataInfo[1]; // 日期（2018-09-18）
            $m_time = strtolower($dataInfo[2]); // 時間（04:30a）
            $timestamp = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dataInfo[19])) - 12 * 3600);
            // 檢測更新
            $checkSql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$dataInfo[0]' and M_Date='$m_date'";
            $checkResult = mysqli_query($dbLink, $checkSql);
            $check = mysqli_num_rows($checkResult);
            if($check == 0){
                if($start == 0) {
                    $insertSql .= "('$dataInfo[0]','BK','$timestamp','$m_date','$m_time','$dataInfo[3]','$dataInfo[3]','$dataInfo[4]','$dataInfo[4]','$dataInfo[5]','$dataInfo[5]','$dataInfo[10]','$dataInfo[8]','$dataInfo[9]','$dataInfo[11]','$dataInfo[11]','$dataInfo[13]','$dataInfo[12]','$dataInfo[14]','$dataInfo[15]','$dataInfo[6]','1','0')";
                }else {
                    $insertSql .= ",('$dataInfo[0]','BK','$timestamp','$m_date','$m_time','$dataInfo[3]','$dataInfo[3]','$dataInfo[4]','$dataInfo[4]','$dataInfo[5]','$dataInfo[5]','$dataInfo[10]','$dataInfo[8]','$dataInfo[9]','$dataInfo[11]','$dataInfo[11]','$dataInfo[13]','$dataInfo[12]','$dataInfo[14]','$dataInfo[15]','$dataInfo[6]','1','0')";
                }
                $start ++;
            }else{
                $dataArray[$dataInfo[0]] = [
                    $check,
                    $timestamp,
                    $m_date,
                    $m_time,
                    $dataInfo[3],
                    $dataInfo[4],
                    $dataInfo[5],
                    stripos($dataInfo[10], '*') !== false ? 'C' : 'H',
                    str_replace('*', '', $dataInfo[10]),
                    $dataInfo[8],
                    $dataInfo[9],
                    $dataInfo[11],
                    $dataInfo[13],
                    $dataInfo[12],
                    $dataInfo[30],
                    $dataInfo[31],
                    $dataInfo[14],
                    $dataInfo[15],
                    $dataInfo[24],
                    $dataInfo[25],
                    $dataInfo[26],
                    $dataInfo[27],
                    $dataInfo[28],
                    $dataInfo[29],
                    $dataInfo[6]
                ];
             }
            $dataCount ++;
        }
        if($start > 0){
            if(!mysqli_query($dbMasterLink, $insertSql))
                exit('入庫籃球單式數據失敗！！！');
        }
    }else{
        break;
    }
}

$redisObj = new Ciredis();
$redisObj->setOne("BK_Today_Num", (int)$dataCount);
$updateCount = 0; //用于判断是否有更新数据
if($dataCount > 0 and count($dataArray) > 0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $ty_sql .="Type = CASE MID ";
    $m_sql .="M_Start = CASE MID ";
    $t_sql .="M_Date = CASE MID ";
    $l_sql .="M_Time = CASE MID ";
    $jm_sql .="M_League = CASE MID ";
    $lm_sql .="M_League_tw = CASE MID ";
//    $mid_sql .="MB_MID = CASE MID "; 注意：196-籃球單式抓取的數據沒有此字段
//    $tid_sql .="TG_MID = CASE MID ";
    $mt_sql .="MB_Team = CASE MID ";
    $tt_sql .="TG_Team = CASE MID ";
    $mtw_sql .="MB_Team_tw = CASE MID ";
    $ttw_sql .="TG_Team_tw = CASE MID ";
    $stp_sql .="ShowTypeR = CASE MID ";
    $mr_sql .="M_LetB = CASE MID ";
    $mrr_sql .="MB_LetB_Rate = CASE MID ";
    $trr_sql .="TG_LetB_Rate = CASE MID ";
    $mbd_sql .="MB_Dime = CASE MID ";
    $tgd_sql .="TG_Dime = CASE MID ";
    $tgr_sql .="TG_Dime_Rate = CASE MID ";
    $mbr_sql .="MB_Dime_Rate = CASE MID ";
    $sdr_sql .="MB_Win_Rate = CASE MID ";
    $sthr_sql .="TG_Win_Rate = CASE MID ";
//    $mlh_sql .="M_Flat_Rate = CASE MID "; 注意：196-籃球單式抓取的數據沒有此字段
    $mlrh_sql .="S_Single_Rate = CASE MID ";
    $tlrh_sql .="S_Double_Rate = CASE MID ";
    $mdh_sql .="MB_Dime_H = CASE MID ";
    $tdh_sql .="MB_Dime_S_H = CASE MID ";
    $tdrh_sql .="MB_Dime_Rate_H = CASE MID ";
    $rbshow_sql .="MB_Dime_Rate_S_H = CASE MID ";
    $tdha_sql .="TG_Dime_H = CASE MID ";
    $tdsh_sql .="TG_Dime_S_H = CASE MID ";
    $tdrsh_sql .="TG_Dime_Rate_H = CASE MID ";
    $tgdrsh_sql .="TG_Dime_Rate_S_H = CASE MID ";
    $more_sql .="more = CASE MID ";
//    $mwrh_sql .="MB_Win_Rate_H = CASE MID ";
//    $twrh_sql .="TG_Win_Rate_H = CASE MID ";
//    $eid_sql .="Eventid = CASE MID ";
//    $emlrh_sql .="M_Flat_Rate_H = CASE MID ";
//    $hot_sql .="Hot = CASE MID ";
//    $play_sql .="Play = CASE MID ";
    $sshow_sql .="S_Show = CASE MID ";
    $mtyp_sql .="M_Type = CASE MID ";

    foreach ($dataArray as $id => $ordinal) {
        $ty_sql .= "WHEN $id THEN 'BK' " ;
        $m_sql .= "WHEN $id THEN '$ordinal[1]' " ;
        $t_sql .= "WHEN $id THEN '$ordinal[2]' " ;
        $l_sql .= "WHEN $id THEN '$ordinal[3]' " ;
        $jm_sql .= "WHEN $id THEN '$ordinal[4]' " ;
        $lm_sql .= "WHEN $id THEN '$ordinal[4]' " ;
//        $mid_sql .= "WHEN $id THEN '$ordinal[5]' " ;
//        $tid_sql .= "WHEN $id THEN '$ordinal[6]' " ;
        $mt_sql .= "WHEN $id THEN '$ordinal[5]' " ;
        $tt_sql .= "WHEN $id THEN '$ordinal[6]' " ;
        $mtw_sql .= "WHEN $id THEN '$ordinal[5]' " ;
        $ttw_sql .= "WHEN $id THEN '$ordinal[6]' " ;
        $stp_sql .= "WHEN $id THEN '$ordinal[7]' " ;
        $mr_sql .= "WHEN $id THEN '$ordinal[8]' " ;
        $mrr_sql .= "WHEN $id THEN '$ordinal[9]' " ;
        $trr_sql .= "WHEN $id THEN '$ordinal[10]' " ;
        $mbd_sql .= "WHEN $id THEN '$ordinal[11]' " ;
        $tgd_sql .= "WHEN $id THEN '$ordinal[11]' " ;
        $tgr_sql .= "WHEN $id THEN '$ordinal[12]' " ;
        $mbr_sql .= "WHEN $id THEN '$ordinal[13]' " ;
        $sdr_sql .= "WHEN $id THEN '$ordinal[14]' " ;
        $sthr_sql .= "WHEN $id THEN '$ordinal[15]' " ;
//        $mlh_sql .= "WHEN $id THEN '$ordinal[19]' " ;
        $mlrh_sql .= "WHEN $id THEN '$ordinal[16]' " ;
        $tlrh_sql .= "WHEN $id THEN '$ordinal[17]' " ;
        $mdh_sql .= "WHEN $id THEN '$ordinal[18]' " ;
        $tdh_sql .= "WHEN $id THEN '$ordinal[18]' " ;
        $tdrh_sql .= "WHEN $id THEN '$ordinal[19]' " ;
        $rbshow_sql .= "WHEN $id THEN '$ordinal[20]' " ;
        $tdha_sql .= "WHEN $id THEN '$ordinal[21]' " ;
        $tdsh_sql .= "WHEN $id THEN '$ordinal[21]' " ;
        $tdrsh_sql .= "WHEN $id THEN '$ordinal[22]' " ;
        $tgdrsh_sql .= "WHEN $id THEN '$ordinal[23]' " ;
        $more_sql .= "WHEN $id THEN '0' " ;
//        $mwrh_sql .= "WHEN $id THEN '$ordinal[32]' " ; // 196-無
//        $twrh_sql .= "WHEN $id THEN '$ordinal[33]' " ;
//        $eid_sql .= "WHEN $id THEN '$ordinal[33]' " ;
//        $emlrh_sql .= "WHEN $id THEN '$ordinal[34]' " ;
//        $hot_sql .= "WHEN $id THEN '$ordinal[35]' " ;
//        $play_sql .= "WHEN $id THEN '$ordinal[36]' " ;
        $sshow_sql .= "WHEN $id THEN '1' " ;
        $mtyp_sql .= "WHEN $id THEN '$ordinal[24]' " ;
        $updateCount ++;
    }
    if($updateCount>0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$jm_sql.$e_sql.$lm_sql.$e_sql.$mt_sql.$e_sql.$tt_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.
            $tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.$tdh_sql.$e_sql.$tdrh_sql.$e_sql.$rbshow_sql.$e_sql.
            $tdha_sql.$e_sql.$tdsh_sql.$e_sql.$tdrsh_sql.$e_sql.$tgdrsh_sql.$e_sql.$more_sql.$e_sql.$sshow_sql.$e_sql.$mtyp_sql ;
        $sql .="END WHERE MID IN ($ids)";
        if(!mysqli_query($dbMasterLink, $sql))
            exit('更新籃球單式數據失敗！！！');
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
    var limit = "<?php echo $settime?>";
    if (document.images){
        var parselimit = limit;
    }
    function beginrefresh(){
        if (!document.images)
            return;
        if (parselimit == 1)
            window.location.reload();
        else{
            parselimit -= 1;
            curmin = Math.floor(parselimit);
            if (curmin != 0)
                curtime = curmin + "秒後自動獲取!";
            else
                curtime = cursec + "秒後自動獲取!";
            timeinfo.innerText = curtime;
            setTimeout("beginrefresh()", 1000);
        }
    }
    window.onload = beginrefresh;
</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100" height="70" align="center">
      單式數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $dataCount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
