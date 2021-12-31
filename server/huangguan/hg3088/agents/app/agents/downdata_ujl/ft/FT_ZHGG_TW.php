<?php
/**
 * 足球綜合過關
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
$mysql = "select udp_ft_pr from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime = $row['udp_ft_pr'];

// 抓取數據
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");

$dataCount = 0;
$dataArray = [];
$curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Hhgg_l.aspx");
$htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Hhgg.aspx");
$htmlData = mb_convert_encoding($htmlData, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
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
                stripos($dataInfo[12], '*') !== false ? 'C' : 'H',
                str_replace('*', '', $dataInfo[12]),
                $dataInfo[10],
                $dataInfo[11],
                $dataInfo[16],
                $dataInfo[17],
                $dataInfo[18],
                $dataInfo[19],
                $dataInfo[20],
                $dataInfo[13],
                $dataInfo[14],
                $dataInfo[15],
                $dataInfo[21],
                $dataInfo[22],
                $dataInfo[24],
                $dataInfo[23],
                stripos($dataInfo[25], '*') !== false ? 'C' : 'H',
                str_replace('*', '', $dataInfo[25]),
                $dataInfo[26]
            ];
            $dataCount ++;
        }else{
            continue;
        }
    }
}

if($dataCount > 0 and count($dataArray)>0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
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
    $mplr_sql .="MB_P_LetB_Rate_H = CASE MID ";
    $tplr_sql .="TG_P_LetB_Rate_H = CASE MID ";
    $tpr_sql .="TG_P_Dime_Rate_H = CASE MID ";
    $mh_sql .="MB_P_Dime_Rate_H = CASE MID ";
    $shp_sql .="ShowTypeHP = CASE MID ";
    $mpl_sql .="M_P_LetB_H = CASE MID ";
    $mpd_sql .="MB_P_Dime_H = CASE MID ";
    $tpd_sql .="TG_P_Dime_H = CASE MID ";
//    $tpsr_sql .="MB_P_Win_Rate_H = CASE MID "; 注意：196-綜合過關沒有半場獨贏
//    $msh_sql .="TG_P_Win_Rate_H = CASE MID ";
//    $mpr_sql .="M_P_Flat_Rate_H = CASE MID ";
    $p3_sql .="P3_Show = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ;
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ;
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ;
        $tg_sql .= "WHEN $id THEN '$ordinal[3]' " ;
        $mp_sql .= "WHEN $id THEN '$ordinal[4]' " ;
        $tp_sql .= "WHEN $id THEN '$ordinal[4]' " ;
        $mr_sql .= "WHEN $id THEN '$ordinal[5]' " ;
        $tr_sql .= "WHEN $id THEN '$ordinal[6]' " ;
        $sp_sql .= "WHEN $id THEN '$ordinal[7]' " ;
        $spd_sql .= "WHEN $id THEN '$ordinal[8]' " ;
        $mv_sql .= "WHEN $id THEN '$ordinal[9]' " ;
        $tv_sql .= "WHEN $id THEN '$ordinal[10]' " ;
        $mf_sql .= "WHEN $id THEN '$ordinal[11]' " ;
        $mplr_sql .= "WHEN $id THEN '$ordinal[12]' " ;
        $tplr_sql .= "WHEN $id THEN '$ordinal[13]' " ;
        $tpr_sql .= "WHEN $id THEN '$ordinal[14]' " ;
        $mh_sql .= "WHEN $id THEN '$ordinal[15]' " ;
        $shp_sql .= "WHEN $id THEN '$ordinal[16]' " ;
        $mpl_sql .= "WHEN $id THEN '$ordinal[17]' " ;
        $mpd_sql .= "WHEN $id THEN '$ordinal[18]' " ;
        $tpd_sql .= "WHEN $id THEN '$ordinal[18]' " ;
//        $tpsr_sql .= "WHEN $id THEN '$ordinal[19]' " ;
//        $msh_sql .= "WHEN $id THEN '$ordinal[20]' " ;
//        $mpr_sql .= "WHEN $id THEN '$ordinal[21]' " ;
        $p3_sql .= "WHEN $id THEN '1' " ;
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tg_sql.$e_sql.$mp_sql.$e_sql.$tp_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$sp_sql.$e_sql.$spd_sql.$e_sql.$mv_sql.$e_sql.$tv_sql.$e_sql.$mf_sql.$e_sql.$shp_sql.$e_sql.$mpl_sql.$e_sql.$mplr_sql.$e_sql.$tplr_sql.$e_sql.$mpd_sql.$e_sql.$tpd_sql.$e_sql.$tpr_sql.$e_sql.$mh_sql.$e_sql.$p3_sql ;
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
      綜合關數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $dataCount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
