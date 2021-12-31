<?php
/**
 * 开元棋牌API
 * Date: 2018/8/22
 */
require_once 'utils.php';

class ApiProxy
{
    protected $agent;
    protected $desKey;
    protected $md5Key;
    protected $apiUrl;
    protected $recordUrl;
    protected $lineCode;
    protected $tester;
    protected $debug = true;
    protected $logPath = '/tmp/ky_log';
    protected $logFile;

    function __construct($kyConfig)
    {
        $this->agent = $kyConfig['agent'];
        $this->desKey = $kyConfig['desKey'];
        $this->md5Key = $kyConfig['md5Key'];
        $this->apiUrl = $kyConfig['apiUrl'];
        $this->recordUrl = $kyConfig['recordUrl'];
        $this->lineCode = $kyConfig['lineCode'];
        $this->tester = $kyConfig['tester'];

        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }
        $this->logFile = $this->logPath . '/ky_api_' . date('Ymd', strtotime('+ 12 hour'));
    }

    function main($params)
    {
        $this->writeLog(json_encode($params));

        $s = $params['s'];
        $account = $params['account'];
        $money = $params['money'];
        
        $timestamp = microtime_int();
        $time_str = timestamp_str('YmdHis', 'Etc/GMT+4');
        $ip = get_ip();

        $orderId = $params['orderId'];
        $orderId || ($orderId = $this->agent . $time_str . $account);
        
        $this->writeLog('client ip address: ' . $ip . ' account: ' . $account . ' orderId: ' . $orderId);

        $param = null;
        switch ($subCmd = intval($s)) {
            case 0: // login
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account,
                    'money' => $money,
                    'lineCode' => $this->lineCode,
                    'ip' => $ip,
                    'orderid' => $orderId,
                    'lang' => 'zh-CN'
                ));
                break;
            case 1: // query the money of account
            case 5: // check if the account is online
            case 7: // query the game's total coin or money
            case 8: // force one player offline
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account
                ));
                break;
            case 2: // charge the money of account
            case 3: // withdraw the money of account
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account,
                    'orderid' => $orderId,
                    'money' => $money,
                    'ip' => $ip
                ));
                break;
            case 4: // query the order info by id
                $param = http_build_query(array(
                    's' => $s,
                    'orderid' => $orderId,
                ));
                break;
            case 6: // query game scores
            case 16:
                $param = http_build_query(array(
                    's' => $s,
                    'startTime' => $params['startTime'],
                    'endTime' => $params['endTime']
                ));
                break;

        }
        $this->writeLog('param: ' . $param);
        $url = ($subCmd == 6 || $subCmd == 16) ? $this->recordUrl : $this->apiUrl;
        $url .= '?' . http_build_query(array(
                'agent' => $this->agent,
                'timestamp' => $timestamp,
                'param' => desEncode($this->desKey, $param),
                'key' => md5($this->agent . $timestamp . $this->md5Key)
            ));
        $this->writeLog('request: ' . $url);
        $res = curl_get_content($url);
        $this->writeLog('curl_get_content: ' . json_encode($res));
        return $res;
    }

    protected function writeLog($log)
    {
        if ($this->debug)
            @file_put_contents($this->logFile, $log . "\n", FILE_APPEND);
    }

}