<?
//define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
//require_once ROOT_DIR.'/common/config.php';
//$global_vars = array(
//	"BROWSER_IP"		=>	HTTPS_HEAD."://".$_SERVER['HTTP_HOST'],
//	"CASINO"            =>  "SI2",
//);
//while (list($key, $value) = each($global_vars)) {
//  define($key, $value);
//}
function get_ip(){

   if($_SERVER['HTTP_X_FORWARDED_FOR']){
  
    $onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $c_agentip=1;
   
   }elseif($_SERVER['HTTP_CLIENT_IP']){
  
    $onlineip = $_SERVER['HTTP_CLIENT_IP'];
    $c_agentip=1;
   
   }else{
  
    $onlineip = $_SERVER['REMOTE_ADDR'];
    $c_agentip=0;
   
   }
   //$c_agentip��¼�Ƿ�Ϊ����ip
   return $onlineip;
}
?>