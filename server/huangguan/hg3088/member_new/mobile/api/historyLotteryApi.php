<?php
/**
 * 彩票投注记录
 * Checked  是否结算 ，N 未结注单 Y 已结注单  传空 查全部
 * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
 * date_start 2018-09-18 00:00:01
 * date_end  2018-09-18 23:59:59
 * page 从第0页开始
 */
include_once('../include/config.inc.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");

$config ['allgame']=array('3'=>'廣東快乐十分','47'=>'重庆快乐十分','2'=>'欢乐生肖','51'=>'北京赛车(PK10)','69'=>'香港六合彩','159'=>'江苏快三','168'=>'幸运飞艇','189'=>'极速赛车','207'=>'分分彩','407'=>'三分彩','507'=>'五分彩','607'=>'腾讯二分彩','222'=>'极速飞艇','304'=>'PC蛋蛋','384'=>'极速快三');
$config ['game']=array(
    3=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    47=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    2=>array('5'=>'第一球','6'=>'第二球','7'=>'第三球','8'=>'第四球','9'=>'第五球','10'=>'1-5大小','11'=>'1-5单双','12'=>'总和大小','13'=>'总和单双','14'=>'龙虎和','15'=>'前三','16'=>'中三','17'=>'后三'),
    51=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    69=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    159=>array('161'=>'猜必出','162'=>'大小单双','163'=>'通选-&gt;豹子','164'=>'三同号','165'=>'和值','166'=>'二不同号','167'=>'二同号复选','378'=>'猜必不出','379'=>'三不同','380'=>'二同号单选','381'=>'通选-&gt;顺子','382'=>'通选-&gt;对子','383'=>'通选-&gt;三不同'),
    168=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    189=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    207=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五',),
    407=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五',),
    507=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五',),
    607=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五',),
    222=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    304=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-单双','29'=>'1-8尾数大小','30'=>'1-8合数单双','31'=>'1-8方位','32'=>'1-8中發白','33'=>'总和大小','34'=>'总和单双','35'=>'总和尾数大小','36'=>'龙虎','37'=>'任選二','38'=>'选二连直','39'=>'选二连组','40'=>'任选三','41'=>'选三前直','42'=>'选三前组','43'=>'任选四','44'=>'任选五'),
    384=>array('386'=>'猜必出','387'=>'大小单双','388'=>'通选-&gt;豹子','389'=>'三同号','390'=>'和值','391'=>'二不同号','392'=>'二同号复选','393'=>'猜必不出','394'=>'三不同','395'=>'二同号单选','396'=>'通选-&gt;顺子','397'=>'通选-&gt;对子','398'=>'通选-&gt;三不同')
);
// 注销理由
$config ['cp_type'] = array (
    1 => "水位錯誤",
    2 => "非正常投注",
    3 => "未接受注单",
    4 => "官方未开答"
);

// 返回彩票投注内容
function returnLotteryBet($row){
    global $config,$cpMasterDbLink ;
    $betcontent ='' ;
    if($row['game_code']==69){ // 香港六合彩
        $types = $config ['lottery_menu'];
        $lhcContent = getxq($cpMasterDbLink,$row,$types);
        $betcontent = $lhcContent.'</span> @ <span style="color:red">'.$row['user_rate'].'</span>' ;
    }else{
        $typecode= $config['ten_typecode']+$config['ssc_typecode']+$config['saiche_typecode']+$config['k3_typecode']+$config['xyft_typecode']+$config['jsft_typecode']+$config['jssaiche_typecode']+$config['jsSSC_typecode']+$config['sfcSSC_typecode']+$config['wfcSSC_typecode']+$config['efcSSC_typecode']+$config['pcdd_typecode']+$config['jsk3_typecode'];
        $betcontent = getcp($row['type_code'],$row['happy8'],$row['drop_content'],$row['game_code'],$typecode).'</span> @ <span style="color:red">'.$row['user_rate'].'</span>' ;
        if(in_array($row['type_code'],array(2032,2035,2038,2039)) && $row['game_code']!=69){
            $betcontent = '<br/>复式『 '.($row['total']/$row['drop_money']).' 组 』<br/>'.($row['drop_content']) ;
        }

    }
    return $betcontent ;
}

function getcp($type_code,$happy8,$drop_content,$game_code=2,$typecode){

    $ssc_code=array(2,45,46);
    $ten_code=array(3,47,48);
    if(in_array($game_code,$ssc_code)){
        if($type_code<=1004){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1005 && $type_code<=1008){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1009 && $type_code<=1015){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==207){
        if($type_code<=1104){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1105 && $type_code<=1108){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1109 && $type_code<=1115){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==407){ // 三分彩
        if($type_code<=1204){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1205 && $type_code<=1208){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1209 && $type_code<=1215){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==507){ // 五分彩
        if($type_code<=1304){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1305 && $type_code<=1308){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1309 && $type_code<=1315){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==607){ // 腾讯二分彩
        if($type_code<=1404){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1405 && $type_code<=1408){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1409 && $type_code<=1415){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==51){
        if($type_code<=3010 or $type_code==3021){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3011 && $type_code<=3020){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==168){
        if($type_code<=3110 or $type_code==3121){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3111 && $type_code<=3120){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==222){
        if($type_code<=3310 or $type_code==3321){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3311 && $type_code<=3320){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==159){
        if($type_code <= 1505){
            $name="猜必出";
        }else if($type_code == 1506 || $type_code == 1507 || $type_code == 1556  || $type_code == 1557 ){
            $name="大小单双";
        }else if($type_code == 1508 || $type_code == 1558 || $type_code == 1559 || $type_code == 1560 ){
            $name="通选";
        }else if( $type_code >= 1509 && $type_code <= 1514 ){
            $name="三同号";
        }else if( ($type_code >= 1515 && $type_code <= 1528) ||  $type_code == 1611 ||  $type_code == 1612 ) {
            $name="和值";
        }else if($type_code > 1528 && $type_code <= 1543 ){
            $name="二不同号";
        }else if($type_code > 1543 && $type_code <= 1549 ){
            $name="二同号复选";
        }else if($type_code > 1549 && $type_code <= 1555 ){
            $name="猜必不出";
        }else if($type_code > 1560 && $type_code <= 1580 ){
            $name="三不同";
        }else if($type_code > 1580 && $type_code <= 1610 ){
            $name="二同号单选";
        }


        return $name."『 ".$typecode[$type_code][1]."』";
    }elseif ($game_code==384){ // 极速快三
        if($type_code <= 2505){
            $name="猜必出";
        }else if($type_code == 2506 || $type_code == 2507 || $type_code == 2556  || $type_code == 2557 ){
            $name="大小单双";
        }else if($type_code == 2508 || $type_code == 2558 || $type_code == 2559 || $type_code == 2560 ){
            $name="通选";
        }else if( $type_code >= 2509 && $type_code <= 2514 ){
            $name="三同号";
        }else if( ($type_code >= 2515 && $type_code <= 2528) ||  $type_code == 2611 ||  $type_code == 2612 ) {
            $name="和值";
        }else if($type_code > 2528 && $type_code <= 2543 ){
            $name="二不同号";
        }else if($type_code > 2543 && $type_code <= 2549 ){
            $name="二同号复选";
        }else if($type_code > 2549 && $type_code <= 2555 ){
            $name="猜必不出";
        }else if($type_code > 2560 && $type_code <= 2580 ){
            $name="三不同";
        }else if($type_code > 2580 && $type_code <= 2610 ){
            $name="二同号单选";
        }


        return $name."『 ".$typecode[$type_code][1]."』";
    }elseif ($game_code==304){
        return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
    }elseif ($game_code==189){
        if($type_code<=3210 or $type_code==3221){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3211 && $type_code<=3220){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }else{
        if ($type_code<=2008){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=2009 && $type_code<=2023){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }else{
            return $typecode[$type_code][2];
        }
    }
}

function getxq($cpMasterDbLink,$row,$types){
    $sql="select id, classid,class1,class2,class3,rate,locked from gxfcy_xq_defrate ";
    $sql.=" where id>0 order by id asc";
    $program_char = "utf8" ;
    mysqli_set_charset( $cpMasterDbLink , $program_char );

    $result = mysqli_query($cpMasterDbLink ,$sql);
    $cou=mysqli_num_rows($result);
    if ($cou<0){
        exit(array(['-1'=>'error']));
    }
    $XQgameinfo = array();
    while($XQrow=@mysqli_fetch_assoc($result)){
        $XQgameinfo[$XQrow['id']]=$XQrow;
    }

    if($row['happy8']==12){
        $_tmps=explode(",",$row['drop_content']);
        $_tmpsrate=explode(",",$row['xq_rate']);
        $res= "过关:<br>";
        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class2']."『 ".$XQgameinfo[$v]['class3']."』 @ ".$_tmpsrate[$k]."<br>";
        }
    }else if($row['happy8']==13){
        $res= $XQgameinfo[$row['xq_de_id']]['class1']."『 ".$XQgameinfo[$row['xq_de_id']]['class2']."』";
        $res.= "<BR>复式『 ".$row['count_pay']." 组 』";
        $_tmps=explode(",",$row['drop_content']);
        $res.= "<BR>".implode("、", $_tmps)."<br>";
    }else if($row['happy8']>=17 and $row['happy8']<=21){
        $types=$types;
        $_tmps=explode(",",$row['drop_content']);
        $res= "合肖『 ".$types[$row['happy8']]."』<br>";
        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class3']."、";
        }
    }else if($row['happy8']>=27 and $row['happy8']<=47){
        $_tmps=explode(",",$row['drop_content']);
        $res= $XQgameinfo[$_tmps[0]]['class1']."『 ".$XQgameinfo[$_tmps[0]]['class2']."』";
        $res.= "<BR>复式『 ".$row['count_pay']." 组 』<BR>";

        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class3']."、";
        }
    }else{
        $res= $XQgameinfo[$row['xq_de_id']]['class2']."『 ".$XQgameinfo[$row['xq_de_id']]['class3']."』";
    }
    return $res;
}



$name = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$Checked = $_REQUEST['Checked'] ;
$Cancel=$_REQUEST['Cancel'];
// 默认查询当天的数据
$date_start = !$_REQUEST['date_start'] ? date('Y-m-d 00:00:00') : $_REQUEST['date_start'] ;
$date_end = !$_REQUEST['date_end'] ? date('Y-m-d H:i:s') : $_REQUEST['date_end'];
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;
$betscore_all = 0; // 投注总额
$betscore_all_yx = 0; // 有效投注额额
$m_result_all = 0; // 输赢总额

// 时间处理，转为时间戳
$date_start = strtotime($date_start);
$date_end = strtotime($date_end);

if ($Checked=='Y'){ // count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消
    $sWhere .= " and count=1";
}elseif($Checked=='N'){
    $sWhere .= " and count=0";
}
$Cancel =='Y' ? $sWhere .= " and cancel=1" : $sWhere .= " and cancel=0";
$date_start and $date_end ? $sWhere .= " and (`bet_time` BETWEEN '{$date_start}' AND '{$date_end}')" : '';

// 交易状况页面为未结算注单

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$sql ="select id,user_win,username,round,game_code,type_code,cp_type,drop_money,bet_time,valid_money,happy8,drop_content,xq_rate,xq_de_id,count_pay,user_rate,count,cancel from `".$database['cpDefault']['prefix']."bill` where username='$name' $sWhere order by `bet_time` desc";
// echo $sql;

$result = mysqli_query($cpMasterDbLink,$sql); // 结算
$cou=mysqli_num_rows($result); // 总数
$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;

if($page==0){
    while($allrow = mysqli_fetch_assoc($result)) {
        $betscore_all += $allrow['drop_money'];
        $betscore_all_yx += $allrow['valid_money'];
        $m_result_all += $allrow['user_win'];
    }
}else{
    $betscore_all = 0;
    $betscore_all_yx = 0;
    $m_result_all = 0 ;
}

$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($cpMasterDbLink, $mysql);
$cou_current_page=mysqli_num_rows($result); // 总数

$data=array();
$data['total']=$cou; // 总条目
$data['num_per_page']=$page_size; // 每页条数
$data['currentpage']=$page; // 当前页号
$data['page_count']=$page_count; // 总页数
$data['perpage']= $cou_current_page; // 当前页条数
$data['betscore_all']= number_format($betscore_all,2); // 投注总额
$data['betscore_all_yx']= number_format($betscore_all_yx,2); // 有效投注总额
$data['m_result_all']= number_format($m_result_all,2); // 输赢总额

// $row = mysqli_fetch_array($result);

$data2=array();
while ($row = mysqli_fetch_assoc($result)) {
    $data2[] = $row;
}

foreach ($data2 as $k => $row){
    $data['rows'][$k]['Middle']= []; // 占位
    $data['rows'][$k]['betContent']= ''; // 占位
    $data['rows'][$k]['orderNo']= $row['id'];
    $data['rows'][$k]['BetTime']= date('Y-m-d H:i:s',$row['bet_time']);
   // $data['rows'][$k]['betContent']= returnLotteryBet($row);
    $data['rows'][$k]['Title']= $config ['allgame'][$row['game_code']];
    $data['rows'][$k]['BetScore']= number_format($row['drop_money'],2);
    $data['rows'][$k]['M_Result']= $row['user_win'];
    $data['rows'][$k]['font_a']= ''; // 占位
    $data['rows'][$k]['count'] = $row['count']; // ( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
    $data['rows'][$k]['cancel'] = $row['cancel']; // ( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
    $data['rows'][$k]['zt']= $row['cp_type']?$config['cp_type'][$row['cp_type']]:''; // 异常注单( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
}

// var_dump($data);

if($cou==0){
    $data['rows'] = [] ;
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$data);

