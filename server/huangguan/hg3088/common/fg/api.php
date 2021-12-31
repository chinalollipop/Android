<?php

error_reporting(E_ALL);
ini_set('display_errors','Off');
include_once "config.php";

function callApi ($sub_url, $access_token, $http_method, $contentType, $data) {
    global $host, $fg_root_url;
    try {
        $api_url = $fg_root_url . $sub_url;
        $dataStr = $data;

        //$headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        //$headers[] = "Accept-Encoding:'gzip, deflate'";
        //$headers[] = "Host:{$host}";
        //$headers[] = "Content-Type:'text/plain'";
        //$headers[] = "Accept:'application/json'";
        //$headers[] = "Authorization:{$access_token}";
        $headers[] = "merchantname:{$access_token['merchantname']}";
        $headers[] = "merchantcode:{$access_token['merchantcode']}";

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


        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/fg_api.log');
        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/fg_api.log');
        //@error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/fg_api.log');

        //$resp  = '{"code":107,"data":{},"msg":"invalid member code, it must be between 5-32 characters and no special characters"}';
        //$resp  = '{"code":0,"data":{"openid":"332aG5BKV3dKJwKM_2FZJiygu92ciB_2FZeAErOIPCuG2PAsWpWg"},"msg":"success"}';

        $resp_json = json_decode($resp, true);

        if ($resp_json["code"] !== 0) {
            //return generateResponse(false, $resp_json["status"]);
            return generateResponse(false, $resp_json["msg"]);
        } else if ($resp_json["msg"] == 'success' || $resp_json["code"] == 0) { //createPlay checkAccount
            return generateResponse(true, $resp_json['data']);
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


//这是创建账号
function v3Players ($access_token, $account, $member_password)  {

    if (empty($account) || empty($member_password))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/v3/players';
    $http_method = "POST";
    $data = array(
        "member_code" => $account,
        "password" => $member_password,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}


// 检测账号是否存在
function checkPlayerNames($access_token, $account) {

    $sub_url = '/v3/player_names/'.$account;
    $http_method = 'POST';
    $data = array(
        "member_code" => $account,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 启动FG大厅
function launch_lobby ($access_token, $openid, $language, $owner_id)  {

    if (empty($openid) || empty($language))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/v3/launch_lobby/';
    $http_method = "POST";
    $data = array(
        "openid" => $openid,
        "language" => $language,
        "lobby_code" => 'chess',    //大厅代码 只有棋牌大厅
        "owner_id" => $owner_id,    //站点或厅主
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//进入单个游戏
function V3LaunchGame($access_token, $openid, $game_id, $game_type, $language, $owner_id){
    if (empty($access_token) || empty($openid) || empty($game_id) || empty($game_type))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/v3/launch_game/';
    $http_method = "POST";
    $data = array(
        "openid" => $openid,
        "game_id" => $game_id,  //游戏 id，游戏列表接口的 service_id
        "game_type" => $game_type,   //h5、app
        "language" => $language,
        //"ip" => '',    //Client ip of player
        //"return_url" => '',    //Agent lobby url
        "owner_id" => $owner_id,    //站点或厅主
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//单个游戏试玩
function V3LaunchFreeGame($access_token, $game_id, $game_type, $language, $owner_id){
    if (empty($access_token) || empty($game_id) || empty($game_type))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/v3/launch_free_game/';
    $http_method = "POST";
    $data = array(
        "game_id" => $game_id,  //游戏 id，游戏列表接口的 service_id
        "game_type" => $game_type,   //h5、app
        "language" => $language,
        //"owner_id" => $owner_id,    //站点或厅主
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}


// 游戏列表
function V3Gameslist($access_token, $terminal, $lang) {
    $sub_url = '/v3/games/game_type/' . $terminal . '/language/' . $lang;
    $http_method = "POST";
    $data = array(
        "terminal" => $terminal,
        "lang" => $lang,
    );
    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}


//查询玩家筹码
function getPlayerChips($access_token, $openid){

    if (empty($access_token) || empty($openid)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/v3/player_chips/' . $openid;
    $http_method = "POST";
    $data = array(
        "openid" => $openid,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}


//存取玩家筹码 上下分    下分传负数
function getPlayerUchips($access_token, $openid, $amount, $orderId){

    if (empty($access_token) || empty($openid) || empty($amount) || empty($orderId)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/v3/player_uchips/' . $openid;
    $http_method = "POST";
    $data = array(
        "openid" => $openid,
        "amount" => $amount,
        "externaltransactionid" => $orderId,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//验证存取玩家筹码状态
function playerUchipsCheck($access_token, $orderId){

    if (empty($access_token) || empty($orderId)) {
        return generateResponse(false, "Invalid input.");
    }

    $sub_url = '/v3/player_uchips_check/' . $orderId;
    $http_method = "POST";
    $data = array(
        "externaltransactionid" => $orderId,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 获取游戏总记录数
function getV3LogByCount ($access_token,  $starttime, $endtime, $gt) {
    if (empty($access_token) || empty($starttime) || empty($endtime) || empty($gt))
        return generateResponse (false, "Invalid input.");


    $sub_url = '/v3/agent/log_by_count/gt/' . $gt . '/start_time/' . $starttime . '/end_time/' . $endtime;
    $http_method = "POST";
    $data = array(
        "gt" => $gt,
        "start_time" => $starttime,
        "end_time" => $endtime,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

// 获取注單
function getV3LogRecords ($access_token,  $starttime, $endtime, $gt, $page, $id) {
    if (empty($access_token) || empty($starttime) || empty($endtime) || empty($gt))
        return generateResponse (false, "Invalid input.");

    if($page) { // 如果存在， 则拉取下一页的数据， 最多3000条
        $page = '/page_key/' . $page;
    }

    if($id) {   // 交易单号 , 如存在  只拉这个单号后面数据
        $id  = '/id/' . $id ;
    }

    $sub_url = '/v3_1/agent/log_by_page/gt/' . $gt . $page . $id . '/start_time/' . $starttime . '/end_time/' . $endtime;
    $http_method = "POST";
    $data = array(
        "gt" => $gt,
        "page_key" => $page,
        "id" => $id,
        "start_time" => $starttime,
        "end_time" => $endtime,
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}