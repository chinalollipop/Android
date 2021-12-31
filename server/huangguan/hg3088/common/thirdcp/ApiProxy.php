<?php
/**
 * 第三方国民彩票注单API
 * Date: 2018/8/22
 *     正式:体育国民
 *     拉单独立接口：http://api.dh5588.com/service
 *     agent：gmcp
 *     Deskey: GMCPC20190811
 *     Md5key: EDBDED3F332E08AF
 */

class ThirdApiProxy
{
    protected $agent;
    protected $desKey;
    protected $md5Key;
    protected $apiUrl;
    protected $recordUrl;
    public $lineCode;
    protected $debug = true;
    protected $logPath = '/tmp/thirdcp_log';
    protected $logFile;

    function __construct()
    {
        $redisObj = new Ciredis();
        $datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 第三方彩票信息 取redis 设置的值
        $datastr = json_decode($datajson,true) ;

        $this->agent = $datastr['agentid'];     //代理商ID
        $this->desKey = $datastr['deskey'];     //Md5key
        $this->apiUrl = $datastr['apiurl'];     //接口地址
        $this->recordUrl = $datastr['ld_apiurl'];   //拉单独立接口

        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
            chmod($this->logPath, 0777);
        }

        // 根据第三方彩票代理标识记录相关日志
        $this->logFile = $this->logPath . '/' .$this->agent . '_api_' . date('Ymd', strtotime('+ 12 hour'));

    }

    function thirdMain($params){
        $this->writeLog(json_encode($params));

        $s = $params['s']; // 注单类型
        if(isset($params['s'] ) && $params['s'] ==1 ) {     //信用盘
            $packet = 'Credit';
            $action = 'GetSscData';
        }
        if (isset($params['s'] ) && $params['s'] ==2) {     //官方盘
            $packet = 'Game';
            $action = 'GetProjectData';
        }
        if (isset($params['s'] ) && $params['s'] ==3) {     //官方追号
            $packet = 'Game';
            $action = 'GetTraceData';
        }

        $terminal_id = isset($params['terminal_id']) ? $params['terminal_id'] : 1;
        $account = isset($params['account']) ? $params['account'] : ''; // 会员账号

        //$timestamp = $this->microtime_int();  // 当前时间，时间戳
        $time_str = $this->timestamp_str('YmdHis', 'Etc/GMT+4');

        $lottery_id = isset($params['lottery_id']) ? $params['lottery_id'] : ''; // 彩种
        $orderId = isset($params['orderId']) ? $params['orderId'] : ''; // 流水号（格式：代理编号+yyyyMMddHHmmssSSS+ account）
        $orderId || ($orderId = $this->agent . $time_str . $account);
        //$key = md5($this->agent . $timestamp . $this->md5Key) ; // Md5 校验字符串Encrypt.MD5(agent+timestamp+ MD5Key)

        //$this->writeLog('client ip address: ' . $ip . ' account: ' . $account . ' orderId: ' . $orderId);

        $param = null;
        switch ($subCmd = intval($s)) {
            case 0: // 创建账号并上分
                break;
            case 1:   // 拉取信用盘注单
                $param = http_build_query(array(
                    's' => $s,
                    'startTime' => $params['startTime'],
                    'endTime' => $params['endTime']
                ));
                break;
            case 2:   // 拉取官方注单
                $param = http_build_query(array(
                    's' => $s,
                    'startTime' => $params['startTime'],
                    'endTime' => $params['endTime']
                ));
            case 3:   // 拉取官方追号
                $param = http_build_query(array(
                    's' => $s,
                    'startTime' => $params['startTime'],
                    'endTime' => $params['endTime']
                ));
                break;

        }
        $this->writeLog('param: ' . $param);

        $url = ($subCmd == 1 || $subCmd == 2 || $subCmd == 3) ? $this->recordUrl : $this->apiUrl;
        $url .= '?' . http_build_query(array(
                'packet' => $packet,
                'action' => $action,
                //'lottery_id' => isset($lottery_id) ? $lottery_id : '',
                'terminal_id' => $terminal_id,
                'agent' => $this->agent,
                'param' => $this->desEncode($this->desKey, $param),
                'desKey' => $this->desKey
            ));
        $this->writeLog('request: ' . $url);
        $res = $this->curl_get_content($url);
        //$this->writeLog('curl_get_content: ' . json_encode($res)); // 返回数据
        return $res;
    }


    function microtime_int(){
        return (int)(microtime(true) * 1000);
    }

    function timestamp_str($format, $timezone){
        $this->set_timezone($timezone);
        return date($format);
    }
    function set_timezone($timezone){
        //$timezone = "";
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
            $_SERVER['REMOTE_ADDR'] = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
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
        //设置curl默认访问为IPv4
        /*if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))  {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }*/
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
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' URL:' . $url. "\n", 3, '/tmp/thirdcp_log/third_api_error.log');
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