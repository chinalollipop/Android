<?php

error_reporting(E_ALL);
ini_set('display_errors','Off');
include_once "config.php";

function callApi ($sub_url, $access_token, $http_method, $contentType, $data) {
    global $host, $cq_root_url;
    try {
        $api_url = $cq_root_url . $sub_url;
        $dataStr = $data;

        //$headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        $headers[] = "Accept-Encoding:'gzip, deflate'";
        //$headers[] = "Host:{$host}";
        //$headers[] = "Content-Type:'text/plain'";
        $headers[] = "Authorization:{$access_token}";

        if ($contentType == "json") {
            $headers[] = "Content-Type:application/json";
            $dataStr = json_encode($data);
        } else if ($contentType == "query_string"){
            $headers[] = "Content-Type:application/x-www-form-urlencoded";
            $dataStr = http_build_query($data);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        $failed = curl_errno($ch);

        curl_close($ch);

        if($failed) {
            return generateResponse(false, "System error.");
        }

        //$resp = '{"data":null,"status":{"code":"102","message":"User service error.","datetime":"2019-10-15T02:25:56-04:00","traceCode":"bBzEb6RRuj"}}';
        //$resp = '{"data":{"account":"john103","nickname":"john103","password":"8Ftik2v"},"status":{"code":"0","message":"Success","datetime":"2019-10-15T02:26:45-04:00","traceCode":"bBzEhy9Feh"}}';

        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/cq_api.log');
        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/cq_api.log');
        //@error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/cq_api.log');

        $resp_json = json_decode($resp, true);

        if ($resp_json["data"] == false) {
            //return generateResponse(false, $resp_json["status"]);
            return generateResponse(false, $resp_json["status"]);
        } else if ($resp_json["status"]["message"] == 'Success' || $resp_json["data"] == true) { //createPlay checkAccount
            return generateResponse(true, $resp_json["data"]);
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


// 检测账号是否存在
function checkAccount($access_token, $account) {

    //$sub_url = '/gameboy/player/check/:account';
    $sub_url = '/gameboy/player/check/'.$account;
    $http_method = 'GET';
    $data = array();

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//这是 建立Player
function createPlay ($access_token, $account, $member_password, $nickname)  {

    if (empty($account) || empty($member_password))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/player';
    $http_method = "POST";
    $data = array(
        "account" => $account,
        "password" => $member_password,
        "nickname" => $nickname,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//Player登入
function playLogin($access_token, $account, $password){

    if (empty($account) || empty($password)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/gameboy/player/login';
    $http_method = "POST";
    $data = array(
        "account" => $account,
        "password" => $password,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//更改玩家密码
function pwdPlay($access_token, $account, $password){

    if (empty($account) || empty($password)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/gameboy/player/pwd';
    $http_method = "POST";
    $data = array(
        "account" => $account,
        "password" => $password,
    );
    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//取得游戏大厅连接
function playLobbyLink($access_token, $usertoken, $language){

    if (empty($usertoken) || empty($language)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/gameboy/player/lobbylink';
    $http_method = "POST";
    $data = array(
        "usertoken" => $usertoken,
        "lang" => $language,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//Player 取得游戏连接
function playGameLink($access_token, $usertoken, $gamehall, $game_id, $gameplat, $language, $app){

    if (empty($usertoken) || empty($gamehall) || empty($game_id) || empty($gameplat) || empty($language)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/gameboy/player/gamelink';
    $http_method = "POST";
    $data = array(
        "usertoken" => $usertoken,
        "gamehall" => $gamehall,
        "gamecode" => $game_id,
        "gameplat" => $gameplat,
        "lang" => $language,
        "app" => $app,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 游戏列表
function listGameHall($access_token, $gamehall) {
    $sub_url = '/gameboy/game/list/' . $gamehall;
    $http_method = "GET";
    $data = array();
    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//取得余额
function getBalance($access_token, $account){

    if (empty($access_token) || empty($account))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/player/balance/'.$account;
    $http_method = "GET";
    $data = array();

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//存款
function playDeposit ($access_token, $account, $amount, $mtcode){
    if (empty($access_token) || empty($account) || empty($amount) || empty($mtcode))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/player/deposit';
    $http_method = "POST";
    $data = array(
        "account" => $account,
        "amount" => $amount,
        "mtcode" => $mtcode,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//取款
function playWithdraw ($access_token, $account, $amount, $mtcode){
    if (empty($access_token) || empty($account) || empty($amount) || empty($mtcode))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/player/withdraw';
    $http_method = "POST";
    $data = array(
        "account" => $account,
        "amount" => $amount,
        "mtcode" => $mtcode,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//取得時戳
function gameBoyPing ($access_token){
    if (empty($access_token))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/ping';
    $http_method = "GET";
    $data = array(
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//活動列表
function gameBoyList($access_token , $status){
    if (empty($access_token))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/promo/list';
    $http_method = "GET";
    $data = array(
        'status' => $status,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 体彩取得未开奖注单
function recordLotto($access_token,  $starttime, $endtime, $account=null,$seek=null,$gamehall=null,$gametype=null,$gamecode=null,$genrename=null,$roundid=null) {
    if (empty($access_token) || empty($starttime) || empty($endtime))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/order/record/lotto';
    $http_method = "GET";
    $data = array(
        "starttime" => $starttime,
        "endtime" => $endtime,
        "account" => $account,
        "seek" => $seek,
        "gamehall" => $gamehall,
        "gametype" => $gametype,
        "gamecode" => $gamecode,
        "genrename" => $genrename,
        "roundid" => $roundid,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 体彩取得已开奖注单
function viewLotto($access_token,  $starttime, $endtime, $account=null,$seek=null,$gamehall=null,$gametype=null,$gamecode=null,$genrename=null,$roundid=null) {
    if (empty($access_token) || empty($starttime) || empty($endtime))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/order/view/lotto';
    $http_method = "GET";
    $data = array(
        "starttime" => $starttime,
        "endtime" => $endtime,
        "account" => $account,
        "seek" => $seek,
        "gamehall" => $gamehall,
        "gametype" => $gametype,
        "gamecode" => $gamecode,
        "genrename" => $genrename,
        "roundid" => $roundid,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 获取注單查詢
function orderView ($access_token,  $starttime, $endtime, $account, $page, $pagesize=null) {
    if (empty($access_token) || empty($starttime) || empty($endtime))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/gameboy/order/view?starttime='.$starttime.'&endtime='.$endtime.'&account='.$account.'&page='.$page.'&pagesize='.$pagesize;
    $http_method = "GET";
    /*$data = array(
        "starttime" => $starttime,
        "endtime" => $endtime,
        "account" => $account,
        "page" => $page,
        "pagesize" => $pagesize,
    );*/
    $data = array();

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}


//获取 玩游戏 链接
function getLaunchGameUrl($game_key, $type){

    if (empty($game_key) || empty($type))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/game-providers/1/play?key='.$game_key.'&type='.$type;
    $http_method = "GET";
    $data = array();

    return callApi($sub_url, '', $http_method, "json", $data);
}

//获取下注记录
function getBetRecords ($http_method, $contentType, $data)  {
    global $get_record_url;

    try {
        $api_url = $get_record_url . '/transaction';
        $headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        $headers[] = "Accept-Encoding:'gzip, deflate'";

        if ($contentType == "json") {
            $headers[] = "Content-Type:application/json";
            $dataStr = json_encode($data);
        } else if ($contentType == "query_string"){
            $dataStr = http_build_query($data);
        }
        $ch = curl_init();

//        print_r( $api_url);
//        print_r($dataStr);die;


        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        $failed = curl_errno($ch);

        curl_close($ch);

        if($failed) {
            return generateResponse(false, "System error.");
        }

        $resp_json = json_decode($resp, true);

        if ($resp_json["status"] != null) {
            return generateResponse(false, $resp_json["message"]);
        } else if (count($resp_json)>0) {
            return generateResponse(true, $resp_json);
        } else {
            return generateResponse(true, $resp_json);
        }

    } catch (Exception $e) {
        return generateResponse(false, "system error.");
    }
}