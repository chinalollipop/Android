<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require (CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require (CONFIG_DIR."/include/redis.php");
    require (CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
    require("../../include/config.inc.php");
    require_once("../../../../../common/sportCenterData.php");
    require("../../include/redis.php");
    require("../../include/curl_http.php");
    require_once("../../include/address.mem.php");

    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }

}

//set_time_limit(0);

$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_ft_tw'];

$m_date=date('Y-m-d');
$bkNum=$ftNum=0;
$langx="en-us";
$accoutArr=getFlushWaterAccount();
$allcount =0 ;
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
foreach($accoutArr as $key=>$value) {//在扩展表中获取账号重新刷水
// 篮球冠军刷水，更新中文简体标题
    /*if($bkNum==0){
        $curl->set_referrer("".$value['Datasite']."/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
        $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=".$langx."&rtype=fs&FStype=BK");
        $matchesBK=dealDataByCurl($html_data);
        $couBK=sizeof($matchesBK);
        if($couBK>0){
            $bkNum=1;
            update_lang_cn($matchesBK, $dbLink, $dbMasterLink);
        }
    }*/
    // 足球冠军刷水，更新中文简体标题
    if($ftNum==0){
//	    $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=".$langx."&rtype=fs&FStype=FT");
//	    $matchesFT=dealDataByCurl($html_data);
        // 首先获取全部联赛的lid
        $postdata = array(
            'p' => 'get_league_list_FS',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'FT',
            'FS' => 'Y',
            'showtype' => 'fu',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);

        if ($aData['status']=='success'){

            $couFT=0;
            $aLid=[];
            foreach ($aData['classifier']['region'] as $k => $v){

                $couLeague = count($v['league']);
                $couFT+=$couLeague;
                if ($couLeague>1){
                    foreach ($v['league'] as $k2 => $v2){
                        $aLid[]=$v2['@attributes']['id'];
                    }
                }
                else{
                    $aLid[]=$v['league']['@attributes']['id'];
                }
            }

            // 循环将每一个联赛下面的全部冠军玩法都捞出来
            unset($postdata);
            unset($aData);
            $postdata = array(
                'p' => 'get_game_list_FS',
                'ver' => date('Y-m-d-H').$value['Ver'],
                'langx' => $langx,
                'uid' => $value['Uid'],
                'gtype' => 'FT',
                'showtype' => 'FU',
                'rtype' => 'fs',
            );
            $aLidFs=[];
            foreach ($aLid as $k => $lid){

                $postdata['league_id'] = $lid;
                $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                $aData = xmlToArray($xml_data);
                $aLidFs[$lid] = $aData;

            }
            $result=$aLidFs;
            if(count($result)>0){
                $ftNum=1;
                data_insert_or_update_2021();
            }
        }
    }
    if($bkNum>0&&$ftNum>0) break;
}

// 2021新版冠军刷水
function data_insert_or_update_2021(){
    global $result,$dbMasterLink,$dbLink,$allcount;

    foreach ($result as $lid => $league){
        if (!isset($league['game']['gamecount'])){
            foreach ($league['game'] as $k => $match){
                $leagues[]=$match;
            }
        }else{
            $leagues[]=$league['game'];
        }
    }

    if(isset($leagues)&&is_array($leagues)&&count($leagues)>0) {
        foreach ($leagues as $i => $match) {
            $M_Start=$match['datetime'];
            $MID=$match['gid'];
            $mcount=$match['gamecount'];
            $M_League=$match['league'];
            $MB_Team=$match['teamsname'];
            $gtype=$match['FStype'];
            $mshow=$match['gopen'];
            foreach ($match['rtypes'] as $t => $rtype){
                $data[$i.'_'.$t]['MID']=$MID;
                $data[$i.'_'.$t]['M_Start']=$M_Start;
                $data[$i.'_'.$t]['MB_Team']=$MB_Team;
                $data[$i.'_'.$t]['M_League']=$M_League;
                $data[$i.'_'.$t]['M_Item']=$rtype['teams'];
                $data[$i.'_'.$t]['M_Rate']=$rtype['ioratio'];
                $data[$i.'_'.$t]['Gid']=$rtype['rtype'];
                $data[$i.'_'.$t]['mcount']=$mcount;
                $data[$i.'_'.$t]['Gtype']=$gtype;
                $data[$i.'_'.$t]['mshow']=$mshow;
                $data[$i.'_'.$t]['mshow2']=$rtype['result'];
            }
        }

        // 捞出数据库已有的数据，与拉取数据比较，方便找到哪些是插入的数据、和更新的数据
        $dateToday=date('Y-m-d');
        $sql = "select ID,MID,Gid from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where M_Start>='$dateToday' ";
        $checkresult = mysqli_query($dbLink,$sql);
        $aCrt=[];
        while ($crs=mysqli_fetch_assoc($checkresult)){
            $aCrt[$crs['MID'].'_'.$crs['Gid']] = $crs;
        }

        // 循环拉取到的冠军数据，将插入和更新的数据分开
        $start = 0;
        $insert_sql = "INSERT INTO ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."(MID,M_Start,MB_Team_en,M_League_en,M_Item_en,M_Rate,Gid,mcount,Gtype,mshow,mshow2) VALUES" ;

        $uptime=date('Y-m-d H:i:s');
        $updateaccount =0 ; //用于判断是否有更新数据
        $e_sql = "END,";
        $update_sql = "update `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` set "; // update
        $uptime_sql = "uptime = CASE ID ";
        $mshow_sql = "mshow = CASE ID ";
        $mshow2_sql = "mshow2 = CASE ID ";
        $MB_Team_sql = "MB_Team_en = CASE ID ";
        $M_League_sql = "M_League_en = CASE ID ";
        $M_Item_sql = "M_Item_en = CASE ID ";
        $Gid_sql = "Gid = CASE ID ";
        $M_Rate_sql = "M_Rate = CASE ID ";
        $mcount_sql = "mcount = CASE ID ";
        $M_Start_sql = "M_Start = CASE ID ";
        $ids='';
        foreach ($data as $k => $v){

            $MID=$v['MID'];
            $M_Start=$v['M_Start'];
            $MB_Team=$v['MB_Team'];
            $M_League=$v['M_League'];
            $M_Item=$v['M_Item'];
            $rate=$v['M_Rate'];
            $tid=$v['Gid'];
            $mcount=$v['mcount'];
            $gtype=$v['Gtype'];
            $mshow=$v['mshow'];
            $mshow2=$v['mshow2'];

            $sCheckKey = $MID.'_'.$tid;
            if (array_key_exists($sCheckKey, $aCrt)){

                $id = $aCrt[$sCheckKey]['ID'];
                $ids .= $id.',';

                // 存在则更新
                $uptime_sql .= "WHEN $id THEN '$uptime' ";
                $mshow_sql .= "WHEN $id THEN '$mshow' ";
                $mshow2_sql .= "WHEN $id THEN '$mshow2' ";
                $MB_Team_sql .= "WHEN $id THEN '$MB_Team' ";
                $M_League_sql .= "WHEN $id THEN '$M_League' ";
                $M_Item_sql .= "WHEN $id THEN '$M_Item' ";
                $Gid_sql .= "WHEN $id THEN '$tid' ";
                $M_Rate_sql .= "WHEN $id THEN '$rate' ";
                $mcount_sql .= "WHEN $id THEN '$mcount' ";
                $M_Start_sql .= "WHEN $id THEN '$M_Start' ";
                $updateaccount++;

            }
            else{
                // 不存在则插入
                if($start == 0) {
                    $insert_sql .="('$MID','$M_Start','$MB_Team','$M_League','$M_Item','$rate','$tid','$mcount','$gtype','$mshow','$mshow2')" ;
                } else{
                    $insert_sql .=",('$MID','$M_Start','$MB_Team','$M_League','$M_Item','$rate','$tid','$mcount','$gtype','$mshow','$mshow2')" ;
                }
                $start++;

            }

        }

        if($start>0){ // 有新增数据
            mysqli_query($dbMasterLink,$insert_sql) or die ("新入库操作失敗!");
        }

        if($updateaccount>0){
            $ids = rtrim($ids,',');
            $update_sql .= $uptime_sql.$e_sql.$mshow_sql.$e_sql.$mshow2_sql.$e_sql.$MB_Team_sql.$e_sql.$M_League_sql.$e_sql.$M_Item_sql.$e_sql.$Gid_sql.$e_sql.$M_Rate_sql.$e_sql.$mcount_sql.$e_sql.$M_Start_sql;
            $update_sql .="END WHERE ID IN ($ids)"; // 实现一次性更新数据库操作
            if (mysqli_query($dbMasterLink,$update_sql)){}else{
                die ("更新操作失敗!!");
            }
        }
    }
    $allcount = $updateaccount+$start;

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
      冠军-單式數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英文 <?php echo $allcount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>

</html>
