<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require(CONFIG_DIR."/include/traditional.zh-cn.inc.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
    require ('../include/curl_http.php');
    require ("../include/traditional.zh-cn.inc.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$redisObj = new Ciredis();

$m=0;
$langx="zh-cn";
$accoutArr = array();
$accoutArr=getFlushWaterAccount();//数组随机排序

$curl = new Curl_HTTP_Client();
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

//在扩展表中获取账号重新刷水
foreach($accoutArr as $key=>$value){
    if( $value['cookie'] =='' ){
        $dateCur = date('Y-m-d',time());
        $curl->set_cookie("gamePoint_21059363={$dateCur}%2A0%2A0; gamePoint_21059364={$dateCur}%2A0%2A0; gamePoint_21059365={$dateCur}%2A0%2A0; gamePoint_21059366={$dateCur}%2A2%2A0; gamePoint_21059367={$dateCur}%2A2%2A0; gamePoint_21059368={$dateCur}%2A2%2A0; gamePoint_21059369={$dateCur}%2A2%2A0;");
    }else{
        $curl->set_cookie($value['cookie']);
    }
	$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
	$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=re&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
	$matches = get_content_deal($html_data);
	$cou=sizeof($matches);
	if($cou>0){//可以抓到数据
		for($i=0;$i<$cou;$i++){
            $datainfo = [];
		    $messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");

			//查询是否已经处理过该赛事半场比分
            $result = mysqli_query($dbMasterLink,"select MB_Inball_HR,TG_Inball_HR,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID=".$datainfo[0]);
            $num = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($num==1){
                //只处理没有比分的情况 如果管理员已经处理过，则跳出当前赛事，继续下一个赛事
                if( (strlen($row['MB_Inball_HR'])>0 && strlen($row['TG_Inball_HR'])>0) || $row['Score_Source'] == 3 ){
                    continue;
                }

                if( strpos($datainfo[1], ">半场<") && strpos($datainfo[48], ">半场<")){
                    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball_HR='$datainfo[18]',TG_Inball_HR='$datainfo[19]' where MID='$datainfo[0]' and `Type`='FT'";
                    mysqli_query($dbMasterLink,$sql) or die ("操作失败1!");
                    $redisObj->pushMessage('MatchScorefinishList',(int)$datainfo[0]);
                    $m=$m+1;
                }

            }
		}
		break;
	}
}

function get_content_deal($html_data){
    $a = array(
        "if(self == top)",
        "<script>",
        "</script>",
        "new Array()",
        "parent.GameFT=new Array();",
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

    $html_data = str_replace($a,$b,$html_data);
	preg_match_all("/Array\((.+?)\);/is",$html_data,$matches);
	return $matches[0];	
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>足球接比分</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
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
		curtime=curmin+"秒后自动本页获取最新数据！" 
	else 
		curtime=cursec+"秒后自动本页获取最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<body>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center"><br><?php echo $list_date?><br><br><span id="timeinfo"></span><br>
      <input type=button name=button value="足球上半比分更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
