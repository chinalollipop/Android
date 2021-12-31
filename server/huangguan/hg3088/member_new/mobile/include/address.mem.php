<?php
//define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
//require_once ROOT_DIR.'/common/config.php';
//$global_vars = array(
//	"BROWSER_IP"		=>	HTTPS_HEAD."://".$_SERVER['HTTP_HOST'],
//	"CASINO"            =>  "SI2",
//);
//while (list($key, $value) = each($global_vars)) {
//  define($key, $value);
//}
function get_ip(){

//   if($_SERVER['HTTP_X_FORWARDED_FOR']){
//
//    $onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//    $c_agentip=1;
//
//   }elseif($_SERVER['HTTP_CLIENT_IP']){
//
//    $onlineip = $_SERVER['HTTP_CLIENT_IP'];
//    $c_agentip=1;
//
//   }else{
//
//    $onlineip = $_SERVER['REMOTE_ADDR'];
//    $c_agentip=0;
//
//   }
//   //$c_agentip记录是否为代理ip
//   return $onlineip;

    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key)
    {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown')
        {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}
?>