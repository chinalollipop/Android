<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/jtautopayback.log');

include "../class/config.inc.php";
include "../class/jiutong/util.php";
include "../model/Pay.php";

//秘钥
//$key="810479A90CB5231C908603BC7E8C0D6A";
$sql=" select business_code,business_pwd from ".DBPREFIX."gxfcy_autopay where status = 1 ";

$result = mysqli_query($dbLink,$sql);
$autopay = mysqli_fetch_assoc($result);
$key = $autopay["business_pwd"];

//解密前原文：
$data = $_REQUEST['data'];

//秘钥 原字符串
if($autopay["business_code"] == 'JTZF800836'){ // 6668(98985)商户 RSA回调解密私钥
$private_key_str = 'MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAJapGEhPc5EDcgdO2I0LjbGjv05LELV1Ez5ShsiNqFMxKzbsnecDE7XJ+mE8DthUo4rvL9zF+UxA9afaVAd02n84iOCsB64EWnmB4UbVojbpV2aryLUfUnX+Gk36fZ3VNjLwPiIeJWSg2vm3Rgk3eQTxSMeMwUTbXemFJH176mNxAgMBAAECgYAV++iiLI3FfEY4UMYClsv/PtCcgRGYGNRNBMfMHfeQ5BzVL+O+oNFQdn+Fjrjv0jHnBQ3r3iuJd/UgoBgg2mlDakxwUTIBpnDEGslPrHF0s5rd4e/bAM0Dyujm/X1YJq+LLSJXT4ezaQzk0GvtrL9DgHYbQ/mr+W+cdcGMNZSRwQJBAPcVoIFJy46Kazbmd3gmTNTiPrqICrxPtOmxGZaNZxPWGeWSG/79smfvABpenkF1nHO2XJrdz8Iwvf5j/3QWs6kCQQCcGMdkJ4XFY2jCml9rlpmbrwT1n4NCKecRcpqBwEB/sLt2g4k3kaBENePk5RzvgogPPLVBehe45XuxNWyFCQ6JAkAVRW1d9AZsLQpx3YFnfJSctyWDVXbnI02F2NNFHMNE9+ee1edHGnwjanXtzzt8ky124Lo0MuhR5XRV1DIHwnSRAkAYJjE6ym6EoiOD79QRVgQ/tK0Evv/UjZ0E8wD6T97qolHuPN1OroXn55pxQAzg9QXNrauxDI18+mvPd59pWYRZAkA0+Ox0oLC/SNcdKjSEoiHtdeY05Hk57qdGrt2J2XMgNPq6gC89gEU2ZX6TVxchdZGXuWwAb+rg2lSofsKYB0Sy';
}elseif($autopay["business_code"] == 'JTZF800837'){ // 0086(7557)商户 RSA回调解密私钥
$private_key_str = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIRizrdBvAdbW5vnJxKyV65Bm2MOIDKbFBI6dsaAAcgCrqqe6supvuxgxSg2FwTTMx5ZdwehkTBYbGEU4lmaYAZQv+Df5mKHwsg2D3neOYBLir221Z/bLDZv8It8/nsT2ZwLHPCQ7GuKhXv+FqPpkyMh341JewfV459HOfdOUUSpAgMBAAECgYB2NRvLOiDAmOyFmrKi2Se9astqeTR6u0oCtL1VpPryMqLvqpKQY/MccZPZweJv5n1gfhXwdG2Fs46iaNlrlJJkAG0LiFL4kHXs/tzjaNfwMb/ut2OpokLK01Ztsc6UmzRA/sA2fGAbCxW8OvH8Yq1M7Ghz8oOMKsAo0ca41+JCAQJBAOWPQsVqlTxuX5dfie2VHJHLsY5v0l3f1unVZ8XFT09lgSDDTWRYLBntmrxeVWfH6aoT8mvS5Jvffzikpj77LWECQQCTok7czD2NV5ZMGgyhb6/mTELw6DMvQF044L/9D2h2lqWmpoEuEpngq5Yu+koGPos91T7KjNqWYfBLRXvnPdRJAkEArRE51N5LDtljphr4QK8Xb++yuGEjp3R3calbaCYeagxrssirU0iKTy3sZ7FzECAO0RPiw1mm0sFIB+tSDs+NYQJAS/eZ8v3ZsnBec1x4IV3yOmQFkic5p1fMYGcuA465J0SPirhlAospyTUh+5xpGNt4FOI1BE8Eb5uH5YOkpTHomQJAQKCzzTrX10uhZwnfgbyDGIuWK6K93jD+AXigzJOsi9CdlIgaWgw5YHV9uJQKt6VGnP5RcTHJPXXNeg+qWLpBPg==';
}

// 拼接秘钥；
$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
foreach (str_split($private_key_str,64) as $str){
    $private_key .= $str . "\r\n";
}
$private_key .="-----END PRIVATE KEY-----";

extension_loaded('openssl') or die('php需要openssl扩展支持');

//解密
$data = decode($data,$private_key);

//效验 sign
$rows = callback_to_array($data, $key);

if($rows['remitResult']=='00'){

    $oPayin = new Pay_model($dbMasterLink);
    $oPayin->updateAutoWithdrawer($rows['orderNum'], true);

    echo '0';
}elseif($rows['remitResult']=='99'){

    $oPayin = new Pay_model($dbMasterLink);
    $oPayin->updateAutoWithdrawer($rows['orderNum'], false, 'fail');

    echo '0';
}else{
    echo "错误请求";
}
