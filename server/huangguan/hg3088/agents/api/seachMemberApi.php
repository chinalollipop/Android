<?php
/*
 *  代理新增会员
 * */

include ("../app/agents/include/address.mem.php");
require ("../app/agents/include/config.inc.php");

//checkAdminLogin(); // 同一账号不能同时登陆
$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}
$langx = $_SESSION["langx"];
require ("../app/agents/include/traditional.$langx.inc.php");

$username = $name = $_SESSION['UserName'];
$uid = $_SESSION['Oid'];
$userlv = $_SESSION['admin_level'] ; // 当前管理员层级

$indexType = isset($_REQUEST['indextype'])?$_REQUEST['indextype']:''; // 用于首页统计下线用户

$enable =trim($_REQUEST['enable']);// 状态
$sort =trim($_REQUEST['sort']);// 类型
$orderby =trim($_REQUEST['orderby']);// 排序
$search =trim($_REQUEST['sea_text']) ; // 关键字查询
$haschinese = isTrueName($search);  // 是否输入有中文
$winlosstext =trim($_REQUEST['winlosstext']) ; // 输赢额度大小查询
$page=$_REQUEST["page"];
$parents_id=$_REQUEST['parents_id']; // 代理商用户名

$AddDate=date('Y-m-d H:i:s');// 日期

if ($enable==""){
    $enable='ALL';
}

if ($sort==""){
    $sort='ADDDATE';
}else{

    switch ($sort){
        case 'WinLossCreditBigger':
            $sort='WinLossCredit';
            //$sortType='dayu';
            $money = " and `WinLossCredit`>= $winlosstext";
            break;
        case 'WinLossCreditSmaller':
            $sort='WinLossCredit';
            //$sortType='xiaoyu';
            $money = " and `WinLossCredit` < $winlosstext";
            break;
        default :
            break;
    }

}

if ($orderby==""){
    $orderby='DESC';
}

if ($page==''){
    $page=0;
}
if ($search!=''){
    if($haschinese){ // 有中文
        $search="and (UserName LIKE binary '%$search%' or LoginName LIKE binary '%$search%' or AddDate LIKE binary '%$search%' or Alias LIKE binary '%$search%')";
    }else{
        $search="and (UserName LIKE '%$search%' or LoginName LIKE '%$search%' or AddDate LIKE '%$search%' or Alias LIKE '%$search%' or agent_url LIKE '%$search%')";
    }
    $page_size=512;
}else{
    $search="";
    $page_size=20; //每页展示数量
}
$status ='';
if ($enable=="Y"){
    $status="and Status='0'";
}else if ($enable=="S"){
    $status="and Status='1'";
}else if ($enable=="N"){
    $status="and Status='2'";
}

$agents="(Admin='$name' or Super='$name' or Corprator='$name' or World='$name' or Agents='$name')";
$data=DBPREFIX.MEMBERTABLE;
$seaZd = 'Alias,UserName,WinLossCredit,AddDate,LoginTime,Status,Online'; // Status :0 启用,1 冻结, 2 停用,Online : 1 在线 其他离线
if($indexType){
    $seaZd = 'AddDate,Online'; // Status :0 启用,1 冻结, 2 停用,Online : 1 在线 其他离线
}

if ($parents_id==''){
    $sql = "select $seaZd from $data where $agents $status $money $search order by ".$sort." ".$orderby;
}else{
    $sql = "select $seaZd from $data where $agents $status and $user='$parents_id'  order by ".$sort." ".$orderby;
    $loginfo= $loginname.'查看'.$Caption.''.$parents_id.'的下线';
}
// echo $sql;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
// $page_size=50;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$cou_se=mysqli_num_rows($result);
if ($cou_se==0){
    $page_count=1;
}
$resdata['total']=$cou; // 总条目
$resdata['page_count']=$page_count; // 总页数

if($cou>0){
    while ($aRow = mysqli_fetch_assoc($result)){
        $resdata['rows'][] = $aRow;
    }
}else{
    $resdata['rows'] = array();
}


$status = '200';
$describe = '查询成功!';
original_phone_request_response($status,$describe,$resdata);