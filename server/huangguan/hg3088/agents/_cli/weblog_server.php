<?php

error_reporting(1);
ini_set('display_errors','On');

if(php_sapi_name() != "cli"){
    exit("只能在_cli模式下面运行！");
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/configUserCli.php";

//初始化redis
$ciredis = new Ciredis();
$server = new swoole_websocket_server("127.0.0.1", 5601);

$server->set(array(
    'daemonize'=>1,
    'worker_num' => 128,                    //创建的进程数
    'reactor_num'=>128,
    'debug_mode'=> 0,
    'backlog'=>4096,
    //'task_worker_num'=>64,                //设置异步任务的工作进程数
    'log_file' => __DIR__.'/web_swoole/webs_swoole.log',
    //'heartbeat_check_interval' => 3600,
    //'max_request' => 10,
    //'heartbeat_idle_time' => 600
));

$client_fd_key = "client_fd_for_message_key_";
$push_fd_userid = "push_message_fd_";


//初始化redis列表
$ciredis->deleteByPre($client_fd_key);

$server->on('open', function (swoole_websocket_server $server, $request) use($client_fd_key,$push_fd_userid) {
    $ciredis = new Ciredis();
    $userid_array = $request->get;//连接后接收到的参数
    $userid = (int)$userid_array['userid'];//接受的userid
    if($userid>0){
        $fd_last_thisuser = $ciredis->getSimpleOne($client_fd_key.$userid);
        if(strlen($fd_last_thisuser)){  $ciredis->delete($push_fd_userid.$fd_last_thisuser); }//删除已过期的连接ID
        $ciredis->setOne($client_fd_key.$userid,$request->fd, 3600);//设置用户对应的连接ID
        $ciredis->setOne($push_fd_userid.$request->fd,$userid, 3600);//设置连接ID对应的用户
        //error_log($request->fd.'$userid='.$userid." opened!\n\r", 3, __DIR__.'/web_swoole/web_log.txt');
    }
});



$server->on('message', function (swoole_websocket_server $server, $frame) use($client_fd_key, $conn) {
    $ciredis = new Ciredis();
    $dataJson = $frame->data;
    //error_log(' messaged '.$dataJson.'\n\r', 3, __DIR__.'/web_swoole/web_log.txt');
    $data = json_decode($dataJson,true);
    if( count($data) < 3 || strlen($data['MsType'])==0 || strlen($data['Message'])==0 || strlen($data['UserName'])==0){ exit; }

    if($data['UserName']=='ALL') {//推送全部
        $fd_cur_array = [];
        $fd_cur_array = $ciredis->execute(array("keys", $client_fd_key.'*'));
        //Array( [0] => client_fd_for_message_key_0 )
        //error_log(' messaged All'.print_r($fd_cur_array, 1)."\n\r", 3, __DIR__.'/web_swoole/web_log.txt');
        foreach($fd_cur_array as $key=>$val) {
            $fd_cur='';
            $fd_cur = $ciredis->getSimpleOne($val);
            if($frame->fd != $fd_cur && strlen($fd_cur)>0) {
                //error_log('for_push_'.$val.'_'.$fd_cur."\n\r", 3, __DIR__.'/web_swoole/web_log.txt');
                $server->push($fd_cur, $dataJson );
            }
        }
    }else{//推送个人
        //error_log(' messaged 1'.'\n\r', 3, __DIR__.'/web_swoole/web_log.txt');
        $fd_cur = $ciredis->getSimpleOne($client_fd_key.$data['userid']);
        //error_log(' messaged 2'.$fd_cur.'\n\r', 3, __DIR__.'/web_swoole/web_log.txt');
        $server->push($fd_cur, $dataJson );
        //error_log(' messaged 3'.'\n\r', 3, __DIR__.'/web_swoole/web_log.txt');
    }

});

$server->on('close', function ($ser, $fd) use($client_fd_key,$push_fd_userid) {
    //连接中断删除对应的连接信息
    $ciredis = new Ciredis();
    $fd_for_userid = $ciredis->getSimpleOne($push_fd_userid.$fd);
    if(strlen($fd_for_userid)>0){
        $ciredis->delete($client_fd_key.$fd_for_userid);
        $ciredis->delete($push_fd_userid.$fd);
    }
    error_log('delete '.print_r($fd, 1)."\n\r", 3, __DIR__.'/web_swoole/web_log.txt');
});

$server->start();