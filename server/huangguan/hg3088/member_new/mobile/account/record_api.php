<?php
/**
 * record_api.php  流水记录
 * thistype 流水类型 S充值 T提款 Q额度转换 R返水
 * page 页码
 * type_status 订单状态 ALL 全部 0 审核中 1 成功 2 提款第2次审核
 * date_start 2018-09-17
 * date_end  2018-09-18
 */
include_once('../include/config.inc.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);

}
$m_date=date('Y-m-d');
$uid=$_SESSION["Oid"];
$langx=$_SESSION["langx"];
$username=$_SESSION['UserName'];
$thistype = $_REQUEST['thistype'] ;
$type_status = isset($_REQUEST['type_status'])?$_REQUEST['type_status']:'' ; // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败

// app 接收时间格式 2018-12-04 00:00
if($_REQUEST['appRefer'] == '13' || $_REQUEST['appRefer'] == '14') {
    if(!empty($_REQUEST['date_start']) || !empty($_REQUEST['date_end'])) {
        $_REQUEST['date_start'] = date('Y-m-d' , strtotime($_REQUEST['date_start']));
        $_REQUEST['date_end'] = date('Y-m-d' , strtotime($_REQUEST['date_end']));
    }
}

// 默认查询当天的数据
$date_start = !$_REQUEST['date_start'] ? date('Y-m-d 00:00:00') : $_REQUEST['date_start'].' 00:00:00' ;
$date_end = !$_REQUEST['date_end'] ? date('Y-m-d H:i:s') : $_REQUEST['date_end'].' 23:59:59';
$page = intval($_REQUEST['page'])>0?intval($_REQUEST['page']):0;

if($date_start < date("Y-m-d", strtotime("-1 month"))) {  //最多查询一月
    $date_start = date("Y-m-d 00:00:00", strtotime("-1 month"));
}

// 类型：T 提款(默认)，S 存款，R 返水, Q 额度转换记录，C 汇款信息回查
if($thistype =='T'){
    $type = '提款' ;
}else if($thistype =='R'){
    $type = '返水' ;
}else if($thistype =='S'){
    $type = '存款' ;
}else if($thistype =='Q'){
    $type = '额度转换记录';
}else if($thistype =='C'){
    $type = '汇款信息回查';
}

$sWhere=1;
$username ? $sWhere.=" and UserName='{$username}'" : '';
if($type_status=='ALL'|| $type_status==''){ // 全部不需要
    $sWhere.= '';
}else{
    $type_status ? $sWhere.=" and Checked in ({$type_status})" : '';
}
if($thistype=='ALL'){
    $sWhere.= '';
}else{
    $thistype ? $sWhere.=" and Type='{$thistype}'" : '';
}


if($type_status == "ALL") { // discounType 1-8 后台人工录入成功才显示  快速充值显示(9)   线下(0)
    $sWhere.= " and discounType IN (0,1,2,3,4,5,6,7,8,9)";
}else{ // 否则不显示
    $sWhere.= " and discounType NOT IN (1,2,3,4,5,6,7,8)";
}


//$date_start ? $sWhere .= " and Date > '{$date_start}'": '';
//$date_end ? $sWhere .=" and Date < '{$date_end}'" : '';
$date_start ? $sWhere .= " and addDate between '{$date_start}' and '{$date_end}'": ''; // 查询入库时间，与后台一致
$mysql="select Checked,Gold,`Type`,AddDate,notes,Order_code,`Date`,AuditDate,`Name`,Phone,Contact,Bank,Bank_Account,Bank_Address,`From`,`To` from ".DBPREFIX."web_sys800_data where $sWhere order by id desc";
 //echo $mysql;

$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);

$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;
$mysql.="  limit $offset,$page_size;";
$result = mysqli_query($dbLink, $mysql);
$cou_current_page=mysqli_num_rows($result);

$data=array();
$data['total']=$cou; // 总条目
$data['num_per_page']=$page_size; // 每页条数
$data['currentpage']=$page; // 当前页号
$data['page_count']=$page_count; // 总页数
$data['perpage']= $cou_current_page; // 当前页条数

while ($myrow=mysqli_fetch_assoc($result)){
    $myrow['From'] = $myrow['From']?$myrow['From']:'' ;
    $myrow['To'] = $myrow['To']?$myrow['To']:'' ;
    $myrow['Gold'] = bcmul(floatval($myrow['Gold']), 1, 2);
    $myrow['Order_code'] = $myrow['Order_code']?$myrow['Order_code']:''; // 防止 null
    $myrow['AuditDate'] = $myrow['AuditDate']?$myrow['AuditDate']:''; // 防止 null
    $data['rows'][] = $myrow ;
}
if($cou==0){
    $data['rows'] = [] ;
}

$status='200';
$describe='success';
original_phone_request_response($status,$describe,$data);
