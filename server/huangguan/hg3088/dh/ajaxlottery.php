<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");

require ("./include/config.inc.php");
require ("./include/curl_http.php");

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());


    //香港六合彩   game_code=69
    //欢乐生肖  game_code=2
    //广东快乐十分 game_code=3
    //重庆幸运农场 game_code=47

if($_POST['game_type'] == 'pk10') {
    $game_code = $_POST['game_code']; //北京PK十   game_code=51
    $gameinfo = gameround($game_code);;
    echo json_encode($gameinfo);
    //echo json_encode(array('endtime' => 299, 'isopen'=>1, 'round'=> 674249));
    return true;
} else if ($_POST['game_type'] == 'cqssc') {
    $game_code = $_POST['game_code']; //欢乐生肖  game_code=2
    $gameinfo = gameround($game_code);
    echo json_encode($gameinfo);
    //echo json_encode(array('endtime' => 299, 'isopen'=>1, 'round'=> 674249));
    return true;
} else if ($_POST['game_type'] == 'klsf') {
    $game_code = $_POST['game_code']; //广东快乐十分 game_code=3
    $gameinfo = gameround($game_code);
    echo json_encode($gameinfo);
    //echo json_encode(array('endtime' => 299, 'isopen'=>1, 'round'=> 674249));
    return true;
} else if ($_POST['game_type'] == 'cqxync') {
    $game_code = $_POST['game_code']; //重庆幸运农场 game_code=47
    $gameinfo = gameround($game_code);
    echo json_encode($gameinfo);
    //echo json_encode(array('endtime' => 299, 'isopen'=>1, 'round'=> 674249));
    return true;
} else{
    return false;
}
/**
 * 獲取當前游戏時間,期数
 * Enter description here ...
 * @param int $game_code
 * @return Array result
 */
 function gameround($game_code){
    global $cpMasterDbLink , $database;
    if($game_code == 51) {  //北京PK十    game_code=51  (10位)
        $table=$database['cpDefault']['prefix']."saicheopen";
    } else if($game_code == 2) { //欢乐生肖  game_code=2
        $table=$database['cpDefault']['prefix']."3dopen";
    } else if($game_code == 3 || $game_code == 47) { //广东快乐十分 game_code=3    重庆幸运农场 game_code=47
        $table=$database['cpDefault']['prefix']."tenopen";

    } else if($game_code == 69) { //香港六合彩   game_code=69
        $table=$database['cpDefault']['prefix']."xq_open";
    }

     $time=time();
     //5秒没开奖,自动关盘
     $todaytime=mktime(06,0,0,date("m"),date("d"),date("Y"));
     $nowsql="select 1 from $table where game_code=$game_code ";
     $nowsql.="and endtime<".time()." and endtime>".$todaytime." and number='' ";

     $res = mysqli_query($cpMasterDbLink,$nowsql);
     $unopen = mysqli_num_rows($res);
     if($unopen>=5000){
         $isopen=0;
         $round="";
         $endtime=0;
     }else{

         $sql="select round,endtime from $table force index(endtime) where game_code=$game_code and endtime>".time()." ";
         $sql.="and number='' order by id asc limit 1";
         $res = mysqli_query($cpMasterDbLink,$sql);

         $num = mysqli_num_rows($res);
         if($num==1){
             $row=mysqli_fetch_assoc($res);
             $round=$row['round'];
             $endtime=$row['endtime']-$time;
             $isopen=$endtime<=600?1:0;//十分鐘內才开盤
         }else{
             $isopen=0;
             $round=0;
             $endtime=0;
         }
     }
     $result['round']=$round;
     $result['endtime']=$endtime;
     $result['isopen']=$isopen;
     return $result;
}


?>