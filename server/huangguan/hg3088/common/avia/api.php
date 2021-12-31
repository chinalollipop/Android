<?

error_reporting(E_ALL);
ini_set('display_errors','Off');
require_once "config.php";

function callApi ($sub_url, $http_method, $contentType, $data) {
    global $root_url, $auth;
    try {
        $api_url = $root_url . $sub_url;
        $dataStr = $data;

        $headers[] = "";
        $headers[] = "Authorization:{$auth}";

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

//        @error_log(date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/group/avia_api.log');
//        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/avia_api.log');
//        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/avia_api.log');
//        @error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/avia_api.log');

        return $resp;

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

function doLogin ($username)  {
    $sub_url = "/api/user/login";
    $http_method = "POST";
    $data = array(
        "UserName" => $username,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

function createMember ($username, $password)  {

    if (empty($username) || empty($password))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/user/register";
    $http_method = "POST";
    $data = array(
        "UserName" => $username,
        "password" => $password,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

function getAccountDetails ($access_token, $account_id)  {

    if (empty($access_token) || empty($account_id))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/v1/account/{$account_id}";
    $auth = "Bearer " . $access_token;
    $http_method = "GET";
    $data = null;

    return callApi($sub_url, $http_method, null, $data);
}

function getLaunchGameUrl ($access_token, $member_account_id, $game_id, $language)  {
    global $app_id;
    if (empty($access_token) || empty($member_account_id) || empty($game_id) || empty($language))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/v1/launcher/item";
    $auth = "Bearer " . $access_token;
    $http_method = "POST";
    $data = array(
        "account_id" => $member_account_id,
        "item_id" => $game_id,
        "app_id" => $app_id,
        "login_context" => array(
            "lang" => $language
        )
    );

    return callApi($sub_url, $http_method, "json", $data);
}

function getGuestLaunchGameUrl ()  {

    $sub_url = "/api/user/guest";
    $http_method = "POST";
    $data = null;

    return callApi($sub_url, $http_method, null, $data);
}

function createTransaction ($UserName, $Money, $Type, $ID)  {

    if (empty($UserName) || empty($Money) || empty($Type) || empty($ID))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/user/transfer";
    $http_method = "POST";
    $data = array(
        "UserName" => $UserName,
        "Money" => $Money,
        "Type" => $Type,
        "ID" => $ID,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

function getWalletDetails ($UserName)  {

    if (empty($UserName))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/user/balance";
    $http_method = "POST";
    $data = array(
        "UserName" => $UserName,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

function getTransaction ($Type, $StartAt, $EndAt, $PageIndex=1, $PageSize=1000)  {

    if (empty($Type) || empty($StartAt) || empty($EndAt) || empty($PageIndex) || empty($PageSize))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/log/get";
    $http_method = "POST";
    $data = array(
        "OrderType" => 'All',
        "Type" => $Type,
        "StartAt" => $StartAt,
        "EndAt" => $EndAt,
        "PageIndex" => $PageIndex,
        "PageSize" => $PageSize,
    );

    return callApi($sub_url, $http_method, null, $data);
}

function getCategory ()  {

    $sub_url = "/api/log/category";
    $http_method = "POST";
    $data = null;

    return callApi($sub_url, $http_method, null, $data);
}

function getUserReport0 ($StartAt, $EndAt, $PageIndex=1, $PageSize=1000)  {

    if (empty($StartAt) || empty($EndAt) || empty($PageIndex) || empty($PageSize))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/log/UserReport"; // 获取美东时间报表
    $http_method = "POST";
    $data = array(
        "StartAt" => $StartAt,
        "EndAt" => $EndAt,
        "PageIndex" => $PageIndex,
        "PageSize" => $PageSize,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

?>