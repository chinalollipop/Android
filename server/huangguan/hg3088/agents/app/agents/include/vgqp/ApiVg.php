<?php
/**
 * VG棋牌
 * Date: 2018/11/18
 */
namespace app\agents;

class ApiVg
{
    protected $channel; // 运营平台渠道号
    protected $agent;   // 代理
    protected $privateKey;  //渠道密码
    protected $apiUrl;
    protected $tryGameUrl;
    protected $recordIdUrl;
    protected $debug = true;
    protected $logPath = '/tmp/vg_log';
    protected $logFile;

    function __construct($vgConfig)
    {
        $this->channel = $vgConfig['channel'];
        $this->privateKey = $vgConfig['privateKey'];
        $this->apiUrl = $vgConfig['apiUrl'];
        $this->tryGameUrl = $vgConfig['tryGameUrl'];
        $this->recordIdUrl = $vgConfig['recordIdUrl'];

        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }
        $this->logFile = $this->logPath . '/vg_api_' . date('Ymd', strtotime('+ 12 hour'));
    }

    function main($params)
    {
        $this->writeLog(json_encode($params));
        $username = $params['username']; // 会员账号
        $agent = $params['agent'];  // 代理
        $actionFun = $params['action'];
        $action = $params['action'];
        $gametype = $params['gametype'];    //游戏类型
        $gameversion = $params['gameversion']; //游戏版本 1=PC 2=移动端
        $amount = $params['amount'];
        $time = time();
        $id = $params['id'];    // 注单id
        $orderId = $params['serial']; // 订单号

        $param = null;

        switch ($actionFun) {
            case 'create': // 创建游戏账号
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'channel' => $this->channel,
                    'agent' => $agent,
                    'verifyCode' => strtoupper(md5($username . $action . $this->channel . $agent . $this->privateKey))
                ));
                break;
            case 'loginWithChannel': // 登录游戏
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'channel' => $this->channel,
                    'gametype'  => $gametype,   //游戏类型
                    'gameversion' => $gameversion,  //游戏版本 1=PC 2=移动端
                    'verifyCode' => strtoupper(md5($username . $action . $this->channel . $gametype . $gameversion . $this->privateKey))
                ));
                break;
            case 'trygame': // 试玩账号
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'gametype'  => $gametype,   //游戏类型  1000游戏大厅
                    'gameversion' => $gameversion,  //游戏版本 1=flash 2=h5
                    'verifyCode' => strtoupper(md5($this->channel . $gametype . $gameversion . $this->privateKey))
                ));
                break;
            case 'balance': // 同步会员余额
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'channel' => $this->channel,
                    'verifyCode' => strtoupper(md5($username . $action . $this->channel . $this->privateKey))
                ));
                break;
            case 'transRecord': // 获取会员转账记录
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'channel' => $this->channel,
                    'serial' => $orderId,
                    'verifyCode' => strtoupper(md5($username . $action . $this->channel . $orderId . $this->privateKey))
                ));
                break;
            case 'deposit': // 会员向游戏平台存款
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'serial' => $orderId,
                    'amount' => $amount, // 元模式
                    'channel' => $this->channel,
                    'verifyCode' => strtoupper(md5($username . $action . $orderId . $amount . $this->channel . $this->privateKey))
                ));
                break;
            case 'withdraw': // 会员向游戏平台取款
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'serial' => $orderId,
                    'amount' => $amount, // 元模式
                    'channel' => $this->channel,
                    'verifyCode' => strtoupper(md5($username . $action . $orderId . $amount . $this->channel . $this->privateKey))
                ));
                break;
            case 'updateAgent': // 修改用户代理
                $param = http_build_query(array(
                    'username' => $username,
                    'action' => $action,
                    'channel' => $this->channel,
                    'agent' => $agent,
                    'verifyCode' => strtoupper(md5($username . $action . $this->channel . $agent . $this->privateKey))
                ));
                break;
            case 'gamerecordid': // 注单id获取对战游戏记录
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'agent' => $agent,
                    'id' => $id,
                    'verifyCode' => strtoupper(md5($this->channel . $agent . $id . $this->privateKey))
                ));
                break;
        }
        $this->writeLog('param: ' . $param);
        $url = ($actionFun=='trygame') ? $this->tryGameUrl : (($actionFun=='gamerecordid') ? $this->recordIdUrl : $this->apiUrl);
        $url .= '?' . $param;
        $this->writeLog('request: ' . $url);
        $res = $this->curl_get_content($url);
        if ($actionFun !== 'gamerecordid') {
            $res = $this->xmlLoadStr($res); // 如果gamerecordid方法 返回json, 否则返回 xml
        }
        $this->writeLog($res);

        return $res;
    }

    // 解析xml返回数据
    protected function xmlLoadStr($data_xml)
    {
        $data_xml = str_replace('&', '&amp;', $data_xml);
        $object_xml = simplexml_load_string($data_xml,'SimpleXMLElement', LIBXML_NOCDATA);
        $xml_json = json_encode($object_xml);
        //$xml_array = json_decode($xml_json, true);
        return $xml_json;
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
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' URL:' . $url. "\n", 3, '/tmp/vg_log/vg_api_error.log');
            return null;
        }
        return $res;
    }
}