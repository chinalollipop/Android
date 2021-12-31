<?

error_reporting(E_ALL);
ini_set('display_errors','Off');
include_once "config.php";

function callApi ($sub_url, $http_method, $contentType, $data) {
    global  $bbin_root_url;
    try {
        //$api_url = $bbin_root_url . $sub_url;
        $api_url = $bbin_root_url;
        $dataStr = $data;

        $headers[] = "";
        $headers[] = "Cache-control:'no-cache'";
        $headers[] = "Connection:'keep-alive'";
        $headers[] = "Accept-Encoding:'gzip, deflate'";
        //$headers[] = "Host:{$host}";
        //$headers[] = "Content-Type:'text/plain'";
        //$headers[] = "X-Token:{$access_token}";

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

        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/bbin_api.log');
        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/bbin_api.log');
        //@error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/bbin_api.log');  
        //response -{"ret":1001,"data":[],"msg":"error: \u6ce8\u518c\u8d26\u53f7\u5931\u8d25\uff0cinfo:[21001] msg:[The account is repeated.]"}
        //response -{"ret":200,"data":"\u6210\u529f","msg":""}


        $resp_json = json_decode($resp, true);
        if ($resp_json["ret"] !== 200) {
            return generateResponse(false, $resp_json["msg"]);
        } else if ($resp_json["ret"] == 200) {
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


//这是 创建会员
function createMemberSignUp ($agent, $md5Key, $username, $password)  {

    if (empty($agent) || empty($username) || empty($password))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.SignUp';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "username" => $username,
        "password" => $password,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//获取 玩游戏 链接
function bbinForwardGame($agent, $md5Key, $username, $password, $page_present, $language){

    if (empty($agent) || empty($md5Key) || empty($username) || empty($password))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.ForwardGame';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "username" => $username,
        "password" => $password,
        "page_present" => $page_present,    //	要进入的大厅 live 视讯 ball 体育 game 电子 Ltlottery 彩票 fisharea 捕鱼
        "language" => $language,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//取得余额
function bbinGetBalance($agent, $md5Key, $username, $password){

    if (empty($agent) || empty($md5Key) || empty($username) || empty($password))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.GetBalance';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "username" => $username,
        "password" => $password,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//钱包金额转入转出
function bbinTransferMoney($agent, $md5Key, $username, $orderId, $type, $score){

    if (empty($agent) || empty($md5Key) || empty($username) || empty($orderId) || empty($score))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.TransferMoney';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "username" => $username,
        "billno" => $orderId,
        "type" => $type,
        "credit" => $score,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//额度转换订单查询
function bbinTransferQuery($agent, $md5Key, $orderId){

    if (empty($agent) || empty($md5Key) || empty($orderId))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.TransferQuery';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "billno" => $orderId,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//获取用户下注注单信息
function bbinV1GetBet($agent, $md5Key, $type) {

    if (empty($agent) || empty($md5Key) || empty($type))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.GetBet';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "type" => $type,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//获取用户下注注单信息
function bbinV1GetBetConfirm($agent, $md5Key, $type, $orderlist) {

    if (empty($agent) || empty($md5Key) || empty($type))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Bbin_V1.GetBetConfirm';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "type" => $type,
        "orderlist" => $orderlist,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//获取代理点数
function agentV1GetAgentPoints($agent, $md5Key) {

    if (empty($agent) || empty($md5Key))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Agent_V1.GetAgentPoints';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}

//查询代理点数变动
function agentV1GetPointsChange($agent, $md5Key) {

    if (empty($agent) || empty($md5Key))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Agent_V1.GetPointsChange';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}


//查询代理点数变动,返回得到order集合
function agentV1GetPointsChangeConfirm($agent, $md5Key, $orderlist) {

    if (empty($agent) || empty($md5Key))
        return generateResponse (false, "Invalid input.");

    $sub_url = 'App.Platform_Agent_V1.GetPointsChangeConfirm';
    $http_method = "POST";
    $data = array(
        "agent" => $agent,
        "service" => $sub_url,
        "page" => $orderlist,
    );
    $data['sign'] = cacuSige($data, $md5Key);

    return callApi($sub_url,  $http_method, "query_string", $data);
}


/**
 * 生成接口请求参数sign（）
 * @param $data
 * @param $md5key
 * @return string
 */
function cacuSige($data , $md5key)
{
    $str = "";

    ksort($data);
    foreach ($data as $k => $v) $str .= "$k=$v";
    $str .= $md5key;

    //@error_log('sign -'.$str.PHP_EOL, 3, '/tmp/group/bbin_api.log');
    return md5($str);
}