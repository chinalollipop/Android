<?php
/**
 *  获取subHook 状态推送
 * */
$logPath = '/tmp/subhook';
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, $logPath.'/subhook.log');


$key='4533b3e45fc60c778af26bdd388bf55f';        // subhook 密钥
$token=$_POST['token'];
$signature=md5($token.$key);
if($signature == $_POST['signature']){
    $subhook = fopen($logPath.'/'.date('Ymd', strtotime()).'-subhook.log', 'a+');
    if ($subhook){
        fwrite($subhook, date('Y-m-d H:i:s').print_r($_POST,true));
        fclose($subhook);
    }
}

?>