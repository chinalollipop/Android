<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require (CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require (CONFIG_DIR."/include/curl_http.php");
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

//set_time_limit(0);

$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_ft_tw'];

$m_date=date('Y-m-d');
$langx="zh-cn";
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");

foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
    // 篮球冠军刷水，更新中文简体标题
	if($bkNum==0){    
	    $curl->set_referrer("".$value['Datasite']."/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
	    $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=".$langx."&rtype=fs&FStype=BK");
	    $matchesBK=dealDataByCurl($html_data);
	    $couBK=sizeof($matchesBK);
	    if($couBK>0){
	    	$bkNum=1;
	    	update_lang_cn($matchesBK, $dbLink, $dbMasterLink);
	    }
	}
    // 足球冠军刷水，更新中文简体标题
    if($ftNum==0){ 
	    $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=".$langx."&rtype=fs&FStype=FT");
	    $matchesFT=dealDataByCurl($html_data);
        $updateaccount =0 ; //用于判断是否有更新数据
	    $couFT=sizeof($matchesFT);
	    if($couFT>0){
			$ftNum=1;
	    	update_lang_cn($matchesFT, $dbLink, $dbMasterLink);   	
	    }
    }
   if($bkNum>0&&$ftNum>0) break;
}


function dealDataByCurl($html_data){
    $a = array(
        "if(self == top)",
        "<script>",
        "</script>",
        "new Array()",
        "\n\n"
    );
    $b = array(
        "",
        "",
        "",
        "",
        ""
    );
    $msg = str_replace($a,$b,$html_data);
    preg_match_all("/new Array\((.+?)\);/is",$msg,$matches);
    return $matches[0];
}


$dataArray= array() ; // 把需要的数据重新放在数组里面
function update_lang_cn($matches, $dbLink, $dbMasterLink){
    global $updateaccount;
    $cou=sizeof($matches);
    for($i=0;$i<$cou;$i++){
        $messages=$matches[$i];
        $messages=str_replace("new Array(","",$messages);
        $messages=str_replace("'","",$messages);
        $messages=str_replace(");","",$messages);
        $datainfo=explode(",",$messages);
        $M_Start=$datainfo[1];
        $k=sizeof($datainfo);
        $MID=$datainfo[0];
        $mshow=$datainfo[4];
        $mcount=$datainfo[5];
        $M_League=$datainfo[2];
        $MB_Team=$datainfo[3];
        $gtype=$datainfo[$k-1];
        $pp=($k-6)/4;
		for($t=0;$t<$pp;$t++) {
        	$show2=$datainfo[4*$t+0+6];
            $tid	=$datainfo[4*$t+1+6];
            $M_Item	=$datainfo[4*$t+2+6];
            $rate	=$datainfo[4*$t+3+6];

            $data[$i.'_'.$t]['MID']=$MID;
            $data[$i.'_'.$t]['M_Start']=$M_Start;
            $data[$i.'_'.$t]['MB_Team']=$MB_Team;
            $data[$i.'_'.$t]['M_League']=$M_League;
            $data[$i.'_'.$t]['M_Item']=$M_Item;
            $data[$i.'_'.$t]['M_Rate']=$rate;
            $data[$i.'_'.$t]['Gid']=$tid;
            $data[$i.'_'.$t]['mcount']=$mcount;
            $data[$i.'_'.$t]['Gtype']=$gtype;
            $data[$i.'_'.$t]['mshow']=$mshow;
            $data[$i.'_'.$t]['mshow2']=$show2;

//            $dataArray[$datainfo[0]]=(array($MB_Team,$M_League,$M_Item)); // 把数据放在二维数组里面
        }

        foreach ($data as $k => $v){
            $dataArray[$k]=(array($v['MID'],$v['MB_Team'],$v['M_League'],$v['M_Item'])); // 把数据放在二维数组里面

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


//	if($allcount>0) { //可以抓到数据

        //var_dump($dataArray);
        /*$ids = implode(',', array_keys($dataArray));
        $e_sql .= "END,";
        $sql = "update ".DBPREFIX."match_crown set ";
        $m_sql .="MB_Team_tw = CASE MID " ;
        $t_sql .="M_League_tw = CASE MID ";
        $l_sql .="M_Item_tw = CASE MID ";

        foreach ($dataArray as $id => $ordinal) {
            $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
            $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
            $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句

        }
        $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
        mysqli_query($dbMasterLink,$sql) or die ("操作失败!!");*/


        $uptime=date('Y-m-d H:i:s');
        $e_sql = "END,";
        $update_sql = "update `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` set "; // update
        $uptime_sql = "uptime = CASE ID ";
        $mshow_sql = "mshow = CASE ID ";
        $mshow2_sql = "mshow2 = CASE ID ";
        $MB_Team_sql = "MB_Team = CASE ID ";
        $M_League_sql = "M_League = CASE ID ";
        $M_Item_sql = "M_Item = CASE ID ";
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

        }
        $ids = rtrim($ids,',');
        $update_sql .= $uptime_sql.$e_sql.$mshow_sql.$e_sql.$mshow2_sql.$e_sql.$MB_Team_sql.$e_sql.$M_League_sql.$e_sql.$M_Item_sql.$e_sql.$Gid_sql.$e_sql.$M_Rate_sql.$e_sql.$mcount_sql.$e_sql.$M_Start_sql;
        $update_sql .="END WHERE ID IN ($ids)"; // 实现一次性更新数据库操作
//        echo $update_sql ;
        mysqli_query($dbMasterLink,$update_sql) or die ("更新操作失敗!!");
//    }
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
      <input type=button name=button value="简体 <?php echo $updateaccount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
