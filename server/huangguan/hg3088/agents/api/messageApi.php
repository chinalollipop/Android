<?php
require ("../app/agents/include/config.inc.php");

//checkAdminLogin(); // 同一账号不能同时登陆
$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}
$lv = 'MEM';

$sql="select ID, Date,Message from ".DBPREFIX."web_marquee_data where  Level='$lv' order by ID desc limit 10"; // 限定 10 条
$result = mysqli_query($dbLink,$sql);

while ($aRow = mysqli_fetch_assoc($result)){
    $resdata[] = $aRow;
}
original_phone_request_response(200, 'success', $resdata);