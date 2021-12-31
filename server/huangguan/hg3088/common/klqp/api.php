<?php

error_reporting(E_ALL);
ini_set('display_errors','Off');
include_once "config.php";

function callApi ($sub_url,  $http_method, $contentType, $data) {
    global $host, $kl_root_url;
    try {
        $api_url = $kl_root_url . $sub_url;
        $dataStr = $data;

        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        //$headers[] = "Accept-Encoding:'gzip, deflate'";
        //$headers[] = "Content-Type:'text/plain'";
        //$headers[] = "Accept:'application/json'";

        if ($contentType == "json") {
            $headers[] = "Content-Type:application/json";
            $dataStr = json_encode($data);
        } else if ($contentType == "query_string"){
            $headers[] = "Content-Type:application/x-www-form-urlencoded";
            $dataStr = http_build_query($data);
        }

        $ch = curl_init();  //创建一个curl资源

        curl_setopt($ch, CURLOPT_URL, $api_url);    //设置URL和相应的选项
        //设置提交方式
        switch ($http_method) {
            case "GET":
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请  求。这对于执行"DELETE" 或者其他更隐蔽的HTT
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20 ); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        $failed = curl_errno($ch);
        curl_close($ch);

        if($failed) {
            return generateResponse(false, "System error.");
        }

        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/kl_api.log');
        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/kl_api.log');
        //@error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/kl_api.log');

        $resp_json = json_decode($resp, true);

        if ($resp_json["errno"] != 0) { //返回错误
            return generateResponse(false, $resp_json);
        } else if ($resp_json["errno"] == 0) { //正常返回
            return generateResponse(true, $resp_json);
        } else {
            return generateResponse(true, $resp_json);
        }

    } catch (Exception $e) {
        return generateResponse(false, "system error.");
    }
}

function generateResponse ($success, $body) {
    $resp_str = array(
        "success" => $success,
        "body" => $body
    );
    return $resp_str;
}


//登录自动创建账号
function loginUser ($account , $merchantId, $verify = 'login')  {

    if (empty($account) || empty($merchantId))
        return generateResponse (false, "Invalid input.");

    $sub_url = '?c=test&a=' . $verify;
    $http_method = "POST";
    $data = array(
        "merchant_id" => $merchantId,
        "username" => $account,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}


// 获取用户信息
function getPlayerInfo($account , $merchantId, $verify = 'getPlayerInfo') {

    $sub_url = '?c=test&a=' . $verify;
    $http_method = 'POST';
    $data = array(
        "merchant_id" => $merchantId,
        "username" => $account,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

// 进入游戏大厅
function getGamelobby ($userInfo , $merchantId, $token, $platform='')  {
    global $pcUrl, $wapUrl, $hallGameId;
    if (empty($userInfo) || empty($merchantId) || empty($token))
        return generateResponse (false, "Invalid input.");


    if($platform==13 || $platform==14) {
        $data['gameUrl'] = $wapUrl . '/#/home?user_id=' . $userInfo['user_id'] . '&token=' . $token;
    }else {
        $data['gameUrl'] = $pcUrl . '/' . $hallGameId . '/?merId=' . $merchantId . '&userName=' . $userInfo['username'] . '&token=' . $token . '&roomId=1&tableId=1';
    }


    return generateResponse (true, $data);
}


// 体育(商户)转入棋牌账号
function transferIn($account , $merchantId, $amount, $verify = 'transferIn') {

    $sub_url = '?c=test&a=' . $verify;
    $http_method = 'POST';
    $data = array(
        "merchant_id" => $merchantId,
        "username" => $account,
        "amount" => $amount,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

// 棋牌账号转出至体育(商户)
function transferOut($account , $merchantId, $amount, $verify = 'transferOut') {

    $sub_url = '?c=test&a=' . $verify;
    $http_method = 'POST';
    $data = array(
        "merchant_id" => $merchantId,
        "username" => $account,
        "amount" => $amount,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

// 转账提案ID查询
function transferOrder($ref_id , $merchantId, $verify = 'transferOrder') {

    $sub_url = '?c=test&a=' . $verify;
    $http_method = 'POST';
    $data = array(
        "merchant_id" => $merchantId,
        "ref_id" => $ref_id,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

// 获取注單
function getProjectlist ($merchantId, $startDate, $endDate, $verify = 'projectList') {

    $sub_url = '?c=test&a=' . $verify;
    $http_method = 'POST';
    $data = array(
        "merchant_id" => $merchantId,
        "startTime" => $startDate,
        "endTime" => $endDate,
        "verify" => $verify,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}