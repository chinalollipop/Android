<?php 
ini_set('display_errors','OFF');
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/curl_http.php");

$redisObj = new Ciredis();

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['gtype'];
$code=$_REQUEST['code'];
$urlDataStr='';

/*echo '<pre>';
print_r($_REQUEST);
echo '<br/>';*/

$urlDataStr = $redisObj->getSimpleOne($code);
/*if(isset($urlDataStr) && strlen($urlDataStr)>20){
	//var_dump($urlDataStr);
	$urlDataStr=json_decode($urlDataStr);
	echo $urlDataStr;
}else{*/
	if($_REQUEST['type']=='img'&&strlen($code)>10){
		$accoutArr = getOfficialVideoAccount();
		$curl = new Curl_HTTP_Client();
		$curl->store_cookies("cookies.txt"); 
		$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
		foreach($accoutArr as $key=>$value){
			if($value['Uid']!='Array'){
				$uid = $value['Uid'];
				$urlRequset = $value['Datasite'];
				$curl->set_referrer($Datasite);
				$gameDataStr=$curl->fetch_url($value['Datasite']."/app/member/live/imgVideo.php?&uid=".$value['Uid']."&langx=zh-cn&gameary=".$code);
				if(strpos($gameDataStr,'parent.GetVideoImg')>-1){
					$urlDataStr=str_replace('<script>','',$gameDataStr);	
					$urlDataStr=str_replace('</script>','',$urlDataStr);
					$urlDataStr=str_replace('parent.GetVideoImg(\'','',$urlDataStr);
					$urlDataStr=trim(str_replace('\');','',$urlDataStr));
					$redisObj->insert($code,json_encode($urlDataStr),300);
					echo $urlDataStr;
					break;
				}
			}
		}
	}
//}



?>