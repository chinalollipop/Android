<?php

/**
 * 体育中心接口
 * Class ApiSportCenter
 */
class ApiSportCenter
{
    protected $channel;
    protected $agent;
    protected $privateKey;
    protected $apiUrl;
    protected $recordUrl;
    protected $debug = true;
    protected $logPath = '/tmp/sc_log';
    protected $logFile;

    function __construct()
    {
        $redisObj = new Ciredis();
        $sportCenterSet = $redisObj->getSimpleOne('sport_center_set');
        $sportConfig = json_decode($sportCenterSet,true);

        $this->channel = $sportConfig['channel'];
        $this->privateKey = $sportConfig['privateKey'];
        $this->apiUrl = $sportConfig['apiUrl'] . '/app/member/sports/center_api.php';

        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }
        $this->logFile = $this->logPath . '/sc_api_' . date('Ymd', strtotime('+ 12 hour'));
    }

    function main($params)
    {
        $this->writeLog(json_encode($params));

        $sitemid = $params['sitemid'];
        $agent = $params['agent'];
        $methodFun = $params['method'];
        $method = $methodFun;
        $amount = $params['amount'];
        $time = time();
        $orderId = $params['order_id']; // [1-20]
        $key = $params['key']; // 登录秘钥
        $startTime = $params['startTime'];
        $endTime = $params['endTime'];
        $terminal = $params['t'];

        $param = null;
        $sig = md5($sitemid . $this->channel . $time . $this->privateKey);
        switch ($methodFun) {
            case 'loginUrl': // login
                $param = [
                    'sitemid' => $sitemid,
                    'agent' => $agent,
                    'key' => $key,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ];
                break;
            case 'getTryGameUrl': // login
            case 'getUserMoney': // query the money of account
                $param = [
                    'sitemid' => $sitemid,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ];
                break;
            case 'depositMoney': // charge the money of account
            case 'debitMoney': // withdraw the money of account
                $param = [
                    'sitemid' => $sitemid,
                    'amount' => $amount,
                    'oid' => $orderId,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ];
                break;
            case 'getTransRecord': // query the order info by id
                $param = [
                    'sitemid' => $sitemid,
                    'method' => $method,
                    'oid' => $orderId,
                    'time' => $time,
                    'sig' => $sig
                ];
                break;
            case 'getGameRecord': // query game scores
                $param = [
                    'method' => $method,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'time' => $time,
                    'sig' => $sig
                ];
                break;
            default:
                break;
        }
        $this->writeLog('param: ' . json_encode($param));
        $url = $this->apiUrl . '?channel=' . $this->channel . '&t=' . $terminal;
        $url .= '&' . http_build_query($param);
        $this->writeLog('request: ' . $url);
        $res = $this->curl_get_content($url);
        $this->writeLog('curl_get_content: ' . json_encode($res));
        return $res;
    }

    protected function writeLog($log)
    {
        if ($this->debug)
            @file_put_contents($this->logFile, date('Y-m-d H:i:s') . '-' . $log . "\n", FILE_APPEND);
    }

    protected function curl_get_content($url, $conn_timeout=10, $timeout=60, $user_agent=null)
    {
        $headers = array(
            "Accept: application/json",
            "Accept-Encoding: deflate,sdch",
            "Accept-Charset: utf-8;q=1"
        );
        if ($user_agent === null) {
            $user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36';
        }
        $headers[] = $user_agent;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,sdch');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conn_timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $res = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (($err) || ($httpcode !== 200)) {
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' URL:' . $url. "\n", 3, '/tmp/ff_log/ff_api_error.log');
            return null;
        }
        return $res;
    }
}