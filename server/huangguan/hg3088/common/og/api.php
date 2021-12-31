<?

//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once "config.php";

function callApi ($sub_url, $access_token, $http_method, $contentType, $data) {
    global $host, $root_url;
    try {
        $api_url = $root_url . $sub_url;
        $dataStr = $data;

        $headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        $headers[] = "Accept-Encoding:'gzip, deflate'";
        $headers[] = "Host:{$host}";
        $headers[] = "X-Token:{$access_token}";

        if ($contentType == "json") {
            $headers[] = "Content-Type:application/json";
            $dataStr = json_encode($data);
        } else if ($contentType == "query_string"){
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

//        @error_log(date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('header -'.json_encode($headers).PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/og_api.log');

        $resp_json = json_decode($resp, true);
        if ($resp_json["status"] == 'error') {
            return generateResponse(false, $resp_json["meta"]);
        } else if ($resp_json["status"] == 'success') {
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

//这是 获取token
function getToken ()  {
    global $host, $root_url, $x_key, $x_operator;

    try {
        $api_url = $root_url . '/token';
        $headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        $headers[] = "Accept-Encoding:'gzip, deflate'";
        $headers[] = "Host:{$host}";
        $headers[] = "X-Key:{$x_key}";
        $headers[] = "X-Operator:{$x_operator}";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        $failed = curl_errno($ch);

        curl_close($ch);

        if($failed) {
            return generateResponse(false, "System error.");
        }

        $resp_json = json_decode($resp, true);
        if ($resp_json["error"] != null) {
            return generateResponse(false, $resp_json["error"]);
        } else if ($resp_json["data"] != null) {
            return generateResponse(true, $resp_json["data"]);
        } else {
            return generateResponse(true, $resp_json);
        }

    } catch (Exception $e) {
        return generateResponse(false, "system error.");
    }
}

//这是 注册会员
function createMember ($access_token, $username, $country, $email, $language ,$birthdate)  {

    if (empty($access_token) || empty($username) || empty($country) || empty($email) || empty($language) || empty($birthdate))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/register';
    $http_method = "POST";
    $data = array(
        "username" => $username,
        "country" => $country,
        "fullname" => $username,
        "email" => $email,
        "language" => $language,
        "birthdate" => $birthdate,
    );

    return callApi($sub_url, $access_token, $http_method, "json", $data);
}

//取得余额
function getWalletDetails($access_token, $username){

    if (empty($access_token) || empty($username))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/game-providers/30/balance?username='.$username;
    $http_method = "GET";
    $data = array(
    );

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//这是 更新余额 （转账）
function createTransaction ($access_token, $username, $action, $amount, $transferId){
    if (empty($access_token) || empty($username) || empty($action) || empty($amount) || empty($transferId))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/game-providers/30/balance';
    $http_method = "POST";
    $data = array(
        "username" => $username,
        "balance" => $amount,
        "action" => $action,
        "transferId" => $transferId,
    );

    return callApi($sub_url, $access_token, $http_method, "json", $data);
}

//转账确认
function createTransactionConfirm($access_token, $username, $transferId){
    if (empty($access_token) || empty($username) || empty($transferId))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/api/checkOGTransfer';
    $http_method = "POST";
    $data = array(
        "username" => $username,
        "transferId" => $transferId,
    );

    return callApi($sub_url, $access_token, $http_method, "json", $data);
}

//取得游戏金钥
function getGameKey($access_token, $username){
    if (empty($access_token) || empty($username))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/game-providers/30/games/ogplus/key?username='.$username;
    $http_method = "GET";
    $data = array();

    return callApi($sub_url, $access_token, $http_method, "query_string", $data);
}

//获取 玩游戏 链接
function getLaunchGameUrl($game_key, $type){

    if (empty($game_key) || empty($type))
        return generateResponse (false, "Invalid input.");

    $sub_url = '/game-providers/30/play?key='.$game_key.'&type='.$type;
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