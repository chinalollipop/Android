<?php
/**
 * 登录
 * Date: 2019/3/22
 */
include_once 'include/curl_http.php';
include_once 'include/config.inc.php';

$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
$password = isset($_REQUEST['password']) && $_REQUEST['password'] ? trim($_REQUEST['password']) : '';
$introducer = isset($_REQUEST['introducer']) && $_REQUEST['introducer'] ? trim($_REQUEST['introducer']) : '';
$password2 = isset($_REQUEST['password2']) && $_REQUEST['password2'] ? trim($_REQUEST['password2']) : '';
$alias = isset($_REQUEST['alias']) && $_REQUEST['alias'] ? trim($_REQUEST['alias']) : '';
$payPassword = isset($_REQUEST['paypassword']) && $_REQUEST['paypassword'] ? trim($_REQUEST['paypassword']) : '';
$phone = isset($_REQUEST['phone']) && $_REQUEST['phone'] ? trim($_REQUEST['phone']) : '';
$wechat = isset($_REQUEST['wechat']) && $_REQUEST['wechat'] ? trim($_REQUEST['wechat']) : '';
$bankName = isset($_REQUEST['bank_name']) && $_REQUEST['bank_name'] ? trim($_REQUEST['bank_name']) : '';
$bankAccount = isset($_REQUEST['bank_account']) && $_REQUEST['bank_account'] ? trim($_REQUEST['bank_account']) : '';
$bankAddress = isset($_REQUEST['bank_address']) && $_REQUEST['bank_address'] ? trim($_REQUEST['bank_address']) : '';

$curl = new Curl_HTTP_Client();
$userAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
$curl->set_user_agent($userAgent);
$curl->store_cookies("/tmp/lottery_cookies.txt");

$postData = [];
switch ($action){
    case 'login':
        $postData = [
            'username' => $username,
            'password' => $password,
        ];
        break;
    case 'guest_login':
        break;
    case 'register':
        $postData = [
            'introducer' => $introducer,
            'username' => $username,
            'password' => $password,
            'password2' => $password2,
            'alias' => $alias,
            'paypassword' => $payPassword,
            'phone' => $phone,
            'wechat' => $wechat,
        ];
        break;
    case 'agent_register':
        $postData = [
            'username' => $username,
            'password' => $password,
            'password2' => $password2,
            'alias' => $alias,
            'paypassword' => $payPassword,
            'phone' => $phone,
            'wechat' => $wechat,
            'bank_name' => $bankName,
            'bank_account' => $bankAccount,
            'bank_address' => $bankAddress,
        ];
        break;
    default:
        break;
}
$postData['action'] = $action;

$requestUrl = HEAD_CROWN_LOTTERY . '://' . CROWN_LOTTERY . '/app/member/ads/crown_lottery.php';
$result = $curl->send_post_data($requestUrl, $postData);
echo $result;

