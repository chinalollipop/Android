<?php
/**
 * 乐游棋牌API
 * Date: 2018/8/22
 */

/*
 *
    正式:6668
    后台地址：http://leg.leg111.com:9001/default
    接口地址：https://legapi.leg111.com:189/channelHandle
    拉单独立接口：https://legrec.leg111.com:190/getRecordHandle
    账号：HG
    密码：fdf78868&&&**
    agent：70822
    Deskey: 7E9166596F8927DF
    Md5key: EDBDED3F332E08AF

    正式:0086
    账号：HuangG
    密码：HuangG123*
    agent：70901
    Deskey: 515097F83528A231
    Md5key: E1BDFC8988596F4A
 * */

class LyApiProxy
{
    protected $agent;
    protected $desKey;
    protected $md5Key;
    protected $apiUrl;
    protected $recordUrl;
    public $lineCode;
    protected $debug = true;
    protected $logPath = '/tmp/ly_log';
    protected $logFile;

    function __construct()
    {

        $datastr = getLyQpSetting() ;

        $this->agent = $datastr['agentid'];
        $this->desKey = $datastr['deskey'];
        $this->md5Key = $datastr['md5key'];
        $this->apiUrl = $datastr['apiurl'];
        $this->recordUrl = $datastr['ld_apiurl'];
        $this->lineCode = $datastr['lineCode'];
        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }
        $this->logFile = $this->logPath . '/ly_api_' . date('Ymd', strtotime('+ 12 hour'));

    }

    function lyMain($params){
        $this->writeLog(json_encode($params));

        $s = $params['s']; // 操作子类型：0
        $account = $params['account']; // 会员账号
        $money = $params['money'];  // 上分的金额,如果不携带分数传 0
        $timestamp = $this->microtime_int();  // 当前时间，时间戳
        $time_str = $this->timestamp_str('YmdHis', 'Etc/GMT+4');
        $ip = $this->get_ip(); // 客户端请求 IP(玩家 IP)
        $orderId = $params['orderId']; // 流水号（格式：代理编号+yyyyMMddHHmmssSSS+ account）
        $orderId || ($orderId = $this->agent . $time_str . $account);
        $lineCode = $this->lineCode; // 代理下面的站点标识,用防止站点之间导分。(区分同一个代理账号下面的不同站点，值自定义,长度 10 字符以内的英文或者数字。请千万不要一个玩家一个 lineCode)
        $key = md5($this->agent . $timestamp . $this->md5Key) ; // Md5 校验字符串Encrypt.MD5(agent+timestamp+ MD5Key)

        $this->writeLog('client ip address: ' . $ip . ' account: ' . $account . ' orderId: ' . $orderId);

        $param = null;
        switch ($subCmd = intval($s)) {
            case 0: // 创建账号并上分
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account,
                    'money' => $money,
                    'lineCode' => $lineCode,
                    'ip' => $ip,
                    'orderid' => $orderId,
                    'lang' => 'zh-CN'
                ));
                break;
            case 1: // query the money of account
            case 5: // check if the account is online
            case 7: // 查询玩家的游戏内总分、玩家可下分、玩家在线状态 query the game's total coin or money
            case 8: // 此接口用以将在线的玩家强制离线 force one player offline
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account
                ));
                break;
            case 2: // 此接口用来为账号上分 charge the money of account
            case 3: // 此接口用来为账号下分 withdraw the money of account
                $param = http_build_query(array(
                    's' => $s,
                    'account' => $account,
                    'orderid' => $orderId,
                    'money' => $money,
                    'ip' => $ip
                ));
                break;
            case 4: // 此接口用来查询玩家上下分的订单信息，通过 status 状态来判断上下分是否成功 query the order info by id
                $param = http_build_query(array(
                    's' => $s,
                    'orderid' => $orderId,
                ));
                break;
            case 6: // 拉取对局注单 query game scores
            case 9: // 此接口用以获取游戏注单，注单内包含此局对局详情 ID
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
                'param' => $this->desEncode($this->desKey, $param),
                'key' => $key
            ));
        $this->writeLog('request: ' . $url);
        $res = $this->curl_get_content($url);
        $this->writeLog('curl_get_content: ' . json_encode($res));
        return $res;
    }


    function microtime_int(){
        return (int)(microtime(true) * 1000);
    }

    function timestamp_str($format, $timezone){
        $this->set_timezone($timezone);
        return date($format);
    }
    function set_timezone($default){
        $timezone = "";
        date_default_timezone_set($timezone);
    }

    function get_ip() {
        //Just get the headers if we can or else use the SERVER global.
        if ( function_exists( 'apache_request_headers' ) ) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists.
        if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
        }
        return $the_ip;
    }

    function curl_get_content($url, $conn_timeout=10, $timeout=35, $user_agent=null){
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
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' URL:' . $url. "\n", 3, '/tmp/ly_log/ly_api_error.log');
            return null;
        }
        return $res;
    }
    function desEncode($key, $str){
        $str = $this->pkcs5_pad(trim($str), 16);
        $encrypt_str = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING);
        return base64_encode($encrypt_str);
    }
    function pkcs5_pad($text, $blocksize){
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function writeLog($log)
    {
        if ($this->debug)
            @file_put_contents($this->logFile, $log . "\n", FILE_APPEND);
    }

}