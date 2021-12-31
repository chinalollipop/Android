<?php
session_start();
ini_set('display_errors','On');

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/curl_http.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require CONFIG_DIR."/app/agents/include/define_function_list.inc.php";

	//获取抓数据账号
	$result=array();
	$accoutArr = array();
	$videoNow = $videoFuture = array();
	$accoutArr = getOfficialVideoAccount();
	
	$redisObj = new Ciredis();
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
	foreach($accoutArr as $key=>$value){
		if($value['Uid']!='Array'){
			$uid = $value['Uid'];
			$urlRequset = $value['Datasite'];
			$curl->set_referrer($Datasite);
			$gameDataStr=$curl->fetch_url($value['Datasite']."/app/member/live/game_list.php?&uid=".$value['Uid']."&langx=zh-cn&gtype=All&gdate=All");
			if(strpos($gameDataStr,'GameData')>10 && strpos($gameDataStr,'new Array(')>10){
				$setResult=$redisObj->setOne('gameVideoLists',json_encode($gameDataStr));
				break;
			}else{
				$gameDataStr='';
			}
		}
	}
	
	if($gameDataStr!=''){
		$liveTeamDate=get_content_deal($gameDataStr);
		foreach($liveTeamDate as $key=>$val){
			$val=str_replace('Array(','',$val);
			$val=str_replace(');','',$val);
			$val=str_replace('\'','',$val);
			$valCur=explode(',',$val);
			if(in_array($valCur[0],array('FT','BK'))){
				if($valCur[13]=='perform'){
					if($valCur[6]=='Y'){ 
						$videoNow[]=$valCur[1]; 
					}elseif($valCur[6]=='N'){
						$videoFuture[]=$valCur[1];
					}
				}
			}
		}
		
		$setResult=$redisObj->setOne('gameVideoNow',json_encode($videoNow));
		$setResult=$redisObj->setOne('gameVideoFuture',json_encode($videoFuture));
		
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
	
	$msg = str_replace($a,$b,$html_data);
	preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
	return $matches[0];
}
	
	
?>