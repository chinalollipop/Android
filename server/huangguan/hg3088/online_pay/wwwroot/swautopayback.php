<?php
//header ( 'Content-Type: text/html; charset=utf-8' );
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
//@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/swautopayback.log');
//2019-08-10 13:24:49-php://input:data=LqscnvdWoMK9vekXt6IGfw%2BpVi65W3yUSoc6eXM3N1M5IXInQeqdjOBxEkvepuBzrMJfZPfCJcJp2OsD7Sdlg2Ac0AtckLO%2FoOcKcBUhmDZK0XvWlbEd5UwafA%2FzTGYzbPFgF%2B3r2BuZQaGK8fRbNMo2o19AlCtRzGVEI2%2BiDLZnwFqf%2BSfTm7XFy0mtVFI0jXcBn0WFvdHtiUVbkB%2Flm3fmjelcJM04EBqhaoAAYcr3zrr13trBiy7VFGc5HMACFDiG0YlfmMiOo%2BvMjG9fcWvRpaYJloeFdK%2FdVyWDvYR9ipEnTFT7duAm%2FzAcuLuqfLSPGxFUC57t8Lrjl4LB3Q%3D%3D&client_num=M1000455&order_num=TK20190810011809121029
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/swautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/shunwei/Config.php";
include_once "../class/shunwei/ServiceUtil.php";
include_once "../model/Pay.php";

$post = file_get_contents("php://input");
//$post = "data=O89NEWr6RfPeIMGkMksUxZA4NxiDTCiIQv7ASA%2Fs9isEYxYnDO6wrD%2B50F73HY0nPlja2kUGKhGi9ZVtUHM4k2GvGphqEoWtku%2BZlEfAMrHdX2GUbzgNSViNijHFqzKSvpLkNK59nVyHgixzFDCMQZToTGWtVIu4RiO6yO8%2BSJUZlubJ3r2icm9r5dYYUvIZYHyVQn%2FBMuxSw6x6anI7A97xbAZ74NqZw5fkxvFsmdnQH5%2FUZBDH6DOTy5FWWz8hxbtOYg7%2BfHYtfUyaXu9VPl9InxvNIqYE8ugR%2BBu3HYyf4n6U8Sv8a5oDE4pyVSTEKVEzRU77J7EgAxDj85HCTQ%3D%3D&client_num=M1000455&order_num=TK20190809071205665044";

//http://pay.hgw777.co/swautopayback.php?data=NCjAARfqRa3kjiXczwFYdsaHud+qcmJRglW9Ym2x3s/0Q49uvpaqN8YAtkZ71GofqO3Mh5TpnRkaxCNEAt4MHeRgHcIt+b11gwRd1tSGEhHAd2DzSbozeJ35tmUvpfKuLejuVea5GNbyWyC8yCC/ipwVfrr2OJ1b4Cm5wUNJkwfqkdCNCXflDbLs4IY6cfDTBboGXSAZfOymoCG4yqy4oB2SGZ4d1Bo4/7tePyUks1+EPKFh4TBuZRsmT33893IgohEm2iRcsobOC8yOzRAw4JIk7h3bIv52E+uqqOutA07F2grEBTMliHRR9W9sdGkpfv3jyyih2+xIrVQ/HvWPMg==
//&client_num=M1000455
//&order_num=TK20190809064723806081
/*$_REQUEST = Array 这个无效
(
    [data] => NCjAARfqRa3kjiXczwFYdsaHud+qcmJRglW9Ym2x3s/0Q49uvpaqN8YAtkZ71GofqO3Mh5TpnRkaxCNEAt4MHeRgHcIt+b11gwRd1tSGEhHAd2DzSbozeJ35tmUvpfKuLejuVea5GNbyWyC8yCC/ipwVfrr2OJ1b4Cm5wUNJkwfqkdCNCXflDbLs4IY6cfDTBboGXSAZfOymoCG4yqy4oB2SGZ4d1Bo4/7tePyUks1+EPKFh4TBuZRsmT33893IgohEm2iRcsobOC8yOzRAw4JIk7h3bIv52E+uqqOutA07F2grEBTMliHRR9W9sdGkpfv3jyyih2+xIrVQ/HvWPMg==
    [client_num] => M1000455
    [order_num] => TK20190809064723806081
)*/

$resultArr = urlToArray($post);
/* 以这个为准
$resultArr = $post = array(3) {
  ["data"]=>
  string(364) "O89NEWr6RfPeIMGkMksUxZA4NxiDTCiIQv7ASA%2Fs9isEYxYnDO6wrD%2B50F73HY0nPlja2kUGKhGi9ZVtUHM4k2GvGphqEoWtku%2BZlEfAMrHdX2GUbzgNSViNijHFqzKSvpLkNK59nVyHgixzFDCMQZToTGWtVIu4RiO6yO8%2BSJUZlubJ3r2icm9r5dYYUvIZYHyVQn%2FBMuxSw6x6anI7A97xbAZ74NqZw5fkxvFsmdnQH5%2FUZBDH6DOTy5FWWz8hxbtOYg7%2BfHYtfUyaXu9VPl9InxvNIqYE8ugR%2BBu3HYyf4n6U8Sv8a5oDE4pyVSTEKVEzRU77J7EgAxDj85HCTQ%3D%3D"
  ["client_num"]=>
  string(8) "M1000455"
  ["order_num"]=>
  string(22) "TK20190809071205665044"
}
*/

$client_num = $resultArr["client_num"]; // 商户号
// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$client_num' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$swinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

//string(344) "O89NEWr6RfPeIMGkMksUxZA4NxiDTCiIQv7ASA/s9isEYxYnDO6wrD+50F73HY0nPlja2kUGKhGi9ZVtUHM4k2GvGphqEoWtku+ZlEfAMrHdX2GUbzgNSViNijHFqzKSvpLkNK59nVyHgixzFDCMQZToTGWtVIu4RiO6yO8+SJUZlubJ3r2icm9r5dYYUvIZYHyVQn/BMuxSw6x6anI7A97xbAZ74NqZw5fkxvFsmdnQH5/UZBDH6DOTy5FWWz8hxbtOYg7+fHYtfUyaXu9VPl9InxvNIqYE8ugR+Bu3HYyf4n6U8Sv8a5oDE4pyVSTEKVEzRU77J7EgAxDj85HCTQ=="
$data = urldecode($resultArr["data"]);

//获取私钥
$privateKey = ServiceUtil::privateKeyStr(Config::privateKey);
//解密数据
$result = ServiceUtil::privateDecrypt($data,$privateKey);

$result = json_decode($result, true);
/*$result  = array(8) {
  ["amount"]=>
  string(4) "2000"
  ["bank_code"]=>
  string(3) "ABC"
  ["client_num"]=>
  string(8) "M1000455"
  ["order_num"]=>
  string(22) "TK20190809071205665044"
  ["random_str"]=>
  string(7) "5326104"
  ["remit_date"]=>
  string(14) "20190810103625"
  ["remit_result"]=>
  string(7) "SUCCESS"
  ["sign"]=>
  string(32) "d0009815e29f2f2a401adbdb49856a77"
}*/
$merOrderNo = $result['order_num'];     // 商户订单号

//验签
$resSign = $result["sign"];
unset($result["sign"]);
$checkSignArr = ServiceUtil::signStr($result, $result["random_str"]);
$resJsonStr = json_encode($checkSignArr, JSON_UNESCAPED_UNICODE);//待签名字符串
$checkSign = md5($resJsonStr.$swinfo['business_pwd']) ;
if($checkSign == $resSign){
    if($result["remit_result"] == "SUCCESS"){
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        echo "ok";
        //echo "顺为代付成功";
    } else {
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        echo "顺为代付失败";
    }
    exit;
}else{
    echo "<script type='text/javascript'>alert('顺为代付出款通知,校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}



//转为数组
function urlToArray($str)
{
    $str = trim($str,'&');
    $arr = explode('&',$str);
    foreach ($arr as  $v){
        $arrnew[] = explode('=',$v);
    }
    foreach ($arrnew as $k => $v){
        $array[$v[0]] = $v[1];
    }
    return $array;

}

