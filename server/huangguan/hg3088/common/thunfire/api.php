<?php
error_reporting(E_ALL);
ini_set('display_errors','Off');
require_once "config.php";

function callApi ($sub_url, $http_method, $contentType, $data) {
    global $root_url, $api_url_main, $auth;
    try {
        // 第一次进入游戏用api_url_main. 确认会员partner_member_token是否创建
        if($http_method == 'GET' && isset($data['token']) && isset($data['partner_member_token']) ) {
            $root_url = $api_url_main;
        }
        $api_url = $root_url . $sub_url;
        //$dataStr = $data;

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
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($failed) {
            return generateResponse(false, "System error.");
        }

        //'http_response_code -'.http_response_code()
        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/fire_api.log');
        @error_log('request -'.$dataStr.'--httpCode:'.$httpCode.'--failed:'.$failed.PHP_EOL, 3, '/tmp/group/fire_api.log');
        //@error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/fire_api.log');

        $resp_json = json_decode($resp, true);

        if($httpCode == 200 || $httpCode == 201) {
            return generateResponse(true, $resp_json);
        }else{
            return generateResponse(false, $resp_json);
        }

        //return $resp;

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

/**
 *  根据会员token创建账号流程
 *  1. 请求格式. https://{{api-url-main}}/api/game-client/v4/game-launch/?token={{public_token}}&partner_member_token={{partner_member_token}}
 *  2. 请求回调地址，确认会员token，返回loginName
 *  3. 如果有了就不创建, 没有自动创建
 */
function apiUrlMain ($api_url_main, $public_token, $partner_member_token)  {

    if (empty($api_url_main) || empty($public_token) || empty($partner_member_token))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/game-client/v4/game-launch/?";
    $http_method = "GET";
    $data = array(
        "token" => $public_token,
        "partner_member_token" => $partner_member_token,
    );
    $sub_url .= http_build_query($data);

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 *  进入大厅
 *  iframe_url: https://{{spi-url}}/launch.html?auth={{public_token}}&token={{partner_member_token}}
 **/
function getIframeUrl ($iframe_url, $public_token, $partner_member_token) {

    if (empty($iframe_url) || empty($public_token) || empty($partner_member_token))
        return generateResponse (false, "Invalid input.");

    $sub_url = $iframe_url . '/launch.html?';
    $data = array(
        "auth" => $public_token,
        "token" => $partner_member_token,
    );
    $sub_url .= http_build_query($data);

    return $sub_url;
}

/**
 *  创建账号  20200619
 *  请求格式. https://{{spi-url}}/api/v2/members/
 */
function createFireMember ($operator_id, $username)  {

    if (empty($operator_id) || empty($username))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/v2/members/";
    $http_method = "POST";
    $data = array(
        "member_code" => $username,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 * 用户额度
 **/
function getMemberBalance ($username) {
    if (empty($username))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/v2/balance/?";
    $http_method = "GET";
    $data = array(
        "LoginName" => $username,
    );
    $sub_url .= http_build_query($data);

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 * 存款，会员不存在自动创建账号
 **/
function depositMember ($operator_id, $username, $amount, $reference_no) {
    if (empty($operator_id) || empty($username) || empty($amount) || empty($reference_no))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/v2/deposit/";
    $http_method = "POST";
    $data = array(
        "member" => $username,
        "operator_id" => $operator_id,
        "amount" => $amount,
        "reference_no" => $reference_no,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 * 提款
 **/
function withdrawMember ($operator_id, $username, $amount, $reference_no) {
    if (empty($operator_id) || empty($username) || empty($amount) || empty($reference_no))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/v2/withdraw/";
    $http_method = "POST";
    $data = array(
        "member" => $username,
        "operator_id" => $operator_id,
        "amount" => $amount,
        "reference_no" => $reference_no,
    );

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 * 注单
 **/
function getBetTransaction ($orderID, $LoginName, $bet_type, $from_datetime, $to_datetime, $from_set_datetime, $to_set_datetime, $set_status, $from_mod_datetime, $to_mod_datetime, $PageIndex=1, $PageSize=1000)  {

   if (empty($from_mod_datetime)|| empty($to_mod_datetime) || empty($PageIndex) || empty($PageSize))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/api/v2/bet-transaction/?";
    $http_method = "GET";
    $data = array(
        "id" => $orderID,   // 注单号ID
        "LoginName" => $LoginName,  // 会员ID
        "bet_type" => $bet_type,    // 盘口类型
        "from_datetime" => $from_datetime,  // 下注时间 (从)
        "to_datetime" => $to_datetime,      // 下注时间 (到)
        "from_settlement_datetime" => $from_set_datetime,// 结算时间 (从)
        "to_settlement_datetime" => $to_set_datetime,    // 结算时间 (到)
        "settlement_status" => $set_status,         // 结算状态
        "from_modified_datetime" => $from_mod_datetime,    // 更改时间 (从)
        "to_modified_datetime" => $to_mod_datetime,      // 更改时间 (到)
        "page" => $PageIndex,    // 页面 默认1
        "page_size" => $PageSize,  // 默认 50，最大 1000
    );
    $sub_url .= http_build_query($data);

    return callApi($sub_url, $http_method, "query_string", $data);
}

/**
 * 此接口根据需要获取
 * 获取会员总输赢 计算方法, 以下算法 任选一个
 *  例：https://{{spi-url}}/api/v2/member-summary/?from_settlement_datetime=2020-04-01T00%3A00%3A00-04%3A00&to_settlement_datetime=2020-04-30T00%3A00%3A00-04%3A00&page=1&page_size=1000
 *  1. 总数下注额 amount：113 、 总输赢额 earnings: 285.46(赢)-12.00(输)=273.46
 *  2. 总数下注额 amount：113-12(输) = 101(本金)  、 盈利额：(earnings-amount) =172.46 、 101+172.16= (总输赢额) 273.46
 *  3. result_status已结算(不为空)，result_status !=CANCELLED(不包含已取消赛事)， sum(amount)、sum(earnings)
 **/
function getMemberSummary($from_set_datetime, $to_set_datetime, $PageIndex=1, $PageSize=1000 ) {

    $sub_url = "/api/v2/member-summary/?";
    $http_method = "GET";
    $data = array(
        "from_settlement_datetime" => $from_set_datetime,    // 结算时间 （从）
        "to_settlement_datetime" => $to_set_datetime,        // 结算时间 （到）
        "page" => $PageIndex,    // 页面 默认1
        "page_size" => $PageSize,  // 默认 50，最大 1000
    );
    $sub_url .= http_build_query($data);

    return callApi($sub_url, $http_method, "query_string", $data);

}

/**
 * 根据赛事结果获取该用户盈利额
 **/
function getRewardWith($row) {

    if(!empty($row['result_status'])) { // 已结算
        if($row['result_status'] == 'WIN') { // 赢
            $reward = sprintf("%.2f",$row['earnings']-$row['amount']);
        } else if($row['result_status'] == 'LOSS') {    // 输
            $reward = sprintf("%.2f",$row['earnings']);
        } else if($row['result_status'] == 'DRAW') {    // 和
            $reward = sprintf("%.2f",$row['earnings']-$row['amount']);
        } else if($row['result_status'] == 'CANCELLED') {  // 取消
            $reward = '';
        }
    }else{
        $reward = '';
    }

    return $reward;
}

//=========================end=====================


?>

