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
			$gameDataStr=$curl->fetch_url($value['Datasite']."/app/member/live/game_ioratio.php?&uid=".$value['Uid']."&langx=zh-cn&gtype=FT&gdate=All");
			if(strpos($gameDataStr,'str_even')>10 && strpos($gameDataStr,'loadioratio()')>10){
				$setResult=$redisObj->setOne('gameFtVideoIoratio',json_encode($gameDataStr));
				break;
			}
		}
	}
?>
