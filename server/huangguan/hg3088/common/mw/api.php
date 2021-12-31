<?
/**
 * MW接口
 *
 * getDomain  Domain地址
 * oauth 授权
 * getGameList 调用游戏列表
 * getWalletDetails  取得余额
 * createTransaction 额度转换（更新余额）
 * enterGame  进入游戏
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once "config.php";

//加密
function aes_encript($key,$str){
    $encrypted = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $data = base64_encode($encrypted);
    return $data;
}

//解密
function aes_decript($key,$str){
    $encryptedData = base64_decode($str);
    $decrypted = openssl_decrypt($encryptedData, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return $decrypted;
}

function sentApi ($apiUrl, $func, $arr_json) {
    global $siteId, $pi_key, $MW_public_key;
    $signContent = getSignContentString($arr_json);

    // # data數據規則 step 3: this->
    openssl_sign($signContent, $out, $pi_key);
    // RSA签名
    $sign = base64_encode($out);
    $sign = str_replace("\\", "", $sign);
    // 移除多餘斜線

    // # data數據規則 step 4: // 加入 sign 組成 jsonString
    $arr_json["sign"] = $sign;
    $json_str = json_encode($arr_json);

    // # data數據規則 step 5:
    // EC Platform AES Key 生成
    $AES_key = getAESkey();

    // # data數據規則 step 6: // AES加密
    $data = aes_encript($AES_key,$json_str);

    // key數據規則1:
    // 沿用data step5 的  aes key

    // key數據規則2:
    openssl_public_encrypt($AES_key,$key,$MW_public_key);
    // 第三方公钥RSA加密
    $key = base64_encode($key);
    //加密后的内容通常含有特殊字符，需要编码转换下
    $key = str_replace("\\", "", $key);

    $data = urlencode($data);
    $key = urlencode($key);

    // 執行 http url 請求
    $post = array(
        "func"=>$func,
        "resultType"=>"json",
        "lang"=>"cn",
        "siteId"=>$siteId,
        "data"=>$data,
        "key"=>$key,
    );

    $requestFormat = "%sfunc=%s&resultType=%s&siteId=%s&lang=%s&data=%s&key=%s";
    // 建立请求串
    $requestURL = sprintf($requestFormat,$apiUrl, $post["func"], $post["resultType"], $post["siteId"], $post["lang"], $post["data"], $post["key"]);
    $ch = curl_init();
    $options = array(
        CURLOPT_URL=>$apiUrl,
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>$post,    //  必须使用POST
        CURLOPT_RETURNTRANSFER =>true,//  False 時只回傳成功與否
    );

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $failed = curl_errno($ch);
    curl_close($ch);

    if($failed) {
        return generateResponse(false, "System error.");
    }

    $json_res = json_decode($response, true);
    return $json_res;

//        @error_log('api_url -'.$api_url.PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('request -'.$dataStr.PHP_EOL, 3, '/tmp/group/og_api.log');
//        @error_log('response -'.$resp.PHP_EOL, 3, '/tmp/group/og_api.log');

}

// 按照字母字典顺序先后排序  将排序后的参数名以及对应参数值按以下方式进行拼接：
function getSignContentString($dataArray)
{
    $signContent = null;
    ksort($dataArray);

    foreach($dataArray as $key=>$value)
    {
        $signContent .= "$key=$value";
    }

    return $signContent;
}

// 生成16位 aes key
function getAESkey() {
    $aes = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < 16; $i++) {
        $aes .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $aes;
}

function generateResponse ($success, $body) {
    $resp_str = array(
        "success" => $success,
        "body" => $body
    );
    return $resp_str;
}

// EC Server 调用 MWG Server 提供的平台地址接口，获取 MWG 的最新可用的地址 URL，在返回的 Domain
// 地址基础上访问 MWG Server 的其他接口，来进行游戏接入和功能调用
function getDomainUrl(){
    global $toURL;
    $arr_json = array
    (
        'timestamp' => time(),
    );
    try
    {
        $json_res =  sentApi($toURL, "domain", $arr_json);
        $domainUrl = $json_res["domain"];
    }
    catch(Exception $ex)
    {
        $domainUrl = "https://www.666wins.com/as-lobby/";
    }
    return $domainUrl;
}

// 获取游戏列表
/**
 * @param $apiUrl
 * @param $func
 * @param int $deviceType  0:Flash 平台 1:H5 平台 2:APP 平台
 * @return array|mixed
 */
function getGames($apiUrl, $func, $deviceType = 1){
    if (empty($apiUrl) || empty($func))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'timestamp' => time(),
        'deviceType' => $deviceType,
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

/**
 * 代理商信息数据查询
EC Server 可通过调用该接口分页获取该站点下代理商信息。当传递 merchantId 时，只查询某一站
点下的某一代理商信息。
 * @param $apiUrl
 * @param $func
 * @return array|mixed
 */
function getMerchant($apiUrl, $func){
    if (empty($apiUrl) || empty($func))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'page' => 1,
    );
    return sentApi ($apiUrl, $func, $arr_json);
}

/**
 * 授权（获取登陆接口以及参数）
 */
function oauth($apiUrl, $func, $uid, $utoken, $jumpType='0', $gameId){
    global $merchantId;
    if (empty($apiUrl) || empty($func) || empty($uid) || empty($utoken))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'timestamp' => time(),
        'uid' => $uid,
        'utoken' => $utoken,
        'merchantId' => $merchantId,
        'jumpType' => $jumpType,  // 跳转页面类型（0：游戏大厅；1：查询页面；2：APP 引导页面；3：获取 APP启动信息；）可以不填，默认为跳转游戏大厅
        'gameId' => $gameId
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

//取得余额
function getWalletDetails($apiUrl, $func, $uid, $utoken){
    global $merchantId;

    if (empty($apiUrl) || empty($func) || empty($uid) || empty($utoken))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'timestamp' => time(),
        'uid' => $uid,
        'utoken' => $utoken,
        'merchantId' => $merchantId,
//        'currency' => '',
        'getType' => 0, // 0 不返回货币单位 1 游戏信息数据单位为 MW 币 2 游戏信息数据单位为用户注册货币
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

//货币转入准备、货币转出准备
function transferPrepare ($apiUrl, $func, $uid, $utoken, $inOrOut, $money, $transferOrderNo, $transferOrderTime, $transferClientIp){
    global $merchantId;
    if (empty($apiUrl) || empty($func) || empty($uid) || empty($utoken) || empty($money) || empty($transferOrderNo) || empty($transferOrderTime) || empty($transferClientIp))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'timestamp' => time(),
        'uid' => $uid,
        'utoken' => $utoken,
        'transferType' => $inOrOut, // 0:转入 1:转出
        'transferAmount' => $money,
        'transferOrderNo' => $transferOrderNo,
        'transferOrderTime' => $transferOrderTime,
        'transferClientIp' => $transferClientIp,
        'merchantId' => $merchantId,
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

//货币转入确认、货币转出确认
function transferPay ($apiUrl, $func, $uid, $utoken, $asinTransferOrderNo, $asinTransferOrderTime, $transferOrderNo, $money, $transferClientIp){
    global $merchantId;
    if (empty($apiUrl) || empty($func) || empty($uid) || empty($utoken) || empty($asinTransferOrderNo) || empty($asinTransferOrderTime) || empty($transferOrderNo) || empty($money) || empty($transferClientIp))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'timestamp' => time(),
        'uid' => $uid,
        'utoken' => $utoken,
        'asinTransferOrderNo' => $asinTransferOrderNo,
        'asinTransferOrderTime' => $asinTransferOrderTime,
        'transferOrderNo' => $transferOrderNo,
        'transferAmount' => $money,
        'transferClientIp' => $transferClientIp,
        'merchantId' => $merchantId,
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

//用户时间段游戏查询
function usersgm ($apiUrl, $func, $beginTime, $endTime) {
    global $merchantId;

    if (empty($apiUrl) || empty($func) || empty($beginTime) || empty($endTime))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'queryType' => 0, // 查询类型：0 是代理商下用户查询；1 是站点下用户查询
        'isFlip' => 0, // 是否开启翻页功能，0：不开启；1：是开启，默认不开启
//        'uid' => '',
        'merchantId' => $merchantId,
        'beginTime' => $beginTime,
        'endTime' => $endTime,
        'page' => '', //起始为 1（当翻页功能未开启时，可以不传或为空）
        'getType' => 0, // 0 不返回货币单位，仅返回货币为CNY 的游戏数据 1 游戏信息数据单位为 MW 币 2 游戏信息数据单位为用户注册货币
        'gameId' => 0, // 0 为查询所有游戏 查询单一游戏时可指定 gameId
    );

    return sentApi ($apiUrl, $func, $arr_json);
}

//站点流水日志查询
function siteUsergamelog ($apiUrl, $func, $beginTime, $endTime) {
    global $merchantId;

    if (empty($apiUrl) || empty($func) || empty($beginTime) || empty($endTime))
        return generateResponse (false, "Invalid input.");

    $arr_json = array
    (
        'beginTime' => $beginTime,
        'endTime' => $endTime,
        'page' => 1,
        'iGetLogInfoType' => 0, //是否返回转帐纪录。0 为不返回，1 为返回转帐及流水纪录，默认不返回
        'isFlip' => 0, // 是否开启翻页功能，0：不开启；1：是开启，默认不开启
//        'merchantId' => $merchantId,
        'getType' => 0, // 0 不返回货币单位，仅返回货币为CNY 的游戏数据 1 游戏信息数据单位为 MW 币 2 游戏信息数据单位为用户注册货币
    );

    return sentApi ($apiUrl, $func, $arr_json);
}