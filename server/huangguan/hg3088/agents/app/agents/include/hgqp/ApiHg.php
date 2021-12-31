<?php
/**
 * FFYL-皇冠棋牌
 * Date: 2018/11/7
 */
namespace app\agents;

class ApiHg
{
    protected $channel;
    protected $agent;
    protected $privateKey;
    protected $apiUrl;
    protected $recordUrl;
    protected $debug = true;
    protected $logPath = '/tmp/ff_log';
    protected $logFile;

    function __construct($ffConfig)
    {
        $this->channel = $ffConfig['channel'];
        $this->privateKey = $ffConfig['privateKey'];
        $this->apiUrl = $ffConfig['apiUrl'];

        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }
        $this->logFile = $this->logPath . '/ff_api_' . date('Ymd', strtotime('+ 12 hour'));
    }

    function main($params)
    {
        $this->writeLog(json_encode($params));

        $sitemid = $params['sitemid'];
        $agent = $params['agent'];
        $methodFun = $params['method'];
        $method = 'OtherServices.' . $methodFun;
        $amount = $params['amount'];
        $time = time();
        $ids = $params['ids'];
        $orderId = $params['order_id']; // [1-20]

        $param = null;
        $sig = md5($sitemid . $this->channel . $time . $this->privateKey);
        switch ($methodFun) {
            case 'loginUrl': // login
            case 'updateAgent': // update agent
                $param = json_encode([
                    'sitemid' => $sitemid,
                    'agent' => $agent,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
            case 'getUserMoney': // query the money of account
            case 'getTryGameUrl': // login
                $param = json_encode([
                    'sitemid' => $sitemid,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
            case 'depositMoney': // charge the money of account
            case 'debitMoney': // withdraw the money of account
                $param = json_encode([
                    'sitemid' => $sitemid,
                    'amount' => $amount,
                    'order_id' => $orderId,
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
            case 'getTransRecord': // query the order info by id
                $param = json_encode([
                    'sitemid' => $sitemid,
                    'method' => $method,
                    'order_id' => $orderId,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
            case 'getGameRecord': // query game scores
                $param = json_encode([
                    'method' => $method,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
            case 'updateGameRecordSyncStatus':
                $param = json_encode([
                    'method' => $method,
                    'ids' => $ids,
                    'time' => $time,
                    'sig' => $sig
                ]);
                break;
        }
        $this->writeLog('param: ' . $param);
        $url = $this->apiUrl . '?channel=' . $this->channel;
        $url .= '&req=' . $param;
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