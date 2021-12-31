<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include_once "../include/config.inc.php";
include_once "../include/address.mem.php";
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/group/fire_api.log');

$postData = file_get_contents('php://input');
$postOid = json_decode($postData, true);
/*$postOid = array(1) { ["token"]=>"fd92cd49015d8ecbae12ra2" }*/

$uid = $partner_member_token = isset($postOid['token']) ? $postOid['token'] : '';  // 在线会员 token
$sql = "SELECT `ID`, `Oid`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status <= 1 ";
//echo $sql . '<br>';
$result = mysqli_query($dbLink,$sql);
$mcou = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
$UserName = $row['UserName'];

if($mcou == 0) {
    $status = '401.1';
    $describe = "雷火电竞回调异常，token值:" . $partner_member_token . "请重新检测!";
    original_phone_request_response($status,$describe,$aData);
}

//$status = '200';
//$describe = 'success';
//$aData['loginName'] = $UserName;
//original_phone_request_response($status,$describe,$aData);

$response_data['loginName'] = $UserName;
echo  json_encode( $response_data, JSON_UNESCAPED_UNICODE);



?>
