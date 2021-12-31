<?php
define("CACHE_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include_once CACHE_DIR.'/common/config.php';

// 前台注单截图走的是这，截图使用
if(!defined("HTTPS_HEAD")){
    define("HTTPS_HEAD", "http");
}
$global_vars = array(
	"BROWSER_IP"		=>	HTTPS_HEAD."://".$_SERVER['HTTP_HOST'],
);
while (list($key, $value) = each($global_vars)) {
  define($key, $value);
}
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
//      //$c_agentip记录是否为代理ip
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

/*判断IP是否在后台访问白名单之内*/
function checkip() {
    if(strpos($_SERVER['PHP_SELF'],'wateraccount.php')){
        return true;
    }else{
        $ip_addr = get_ip();
//        $admin_ips = explode(',' , ADMIN_LIST_IP);
//        foreach($admin_ips as $value) {
//            // 去除空白字符
//            $new_admin_ips[] = preg_replace("/(\s+)/",'',$value);
//        }
        $admin_ips = '';
        $cacheFile = CACHE_DIR . '/agents/tmp/ipwhitelist.txt';
        if (file_exists($cacheFile)) {
            $admin_ips = file_get_contents($cacheFile);
        }
        $new_admin_ips = json_decode($admin_ips);
        if(!in_array($ip_addr , $new_admin_ips)) {
            return false;
        }
        return true;
    }
}

?>
