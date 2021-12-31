<?php
/**
 * VG棋牌
 * Date: 2018/11/18
 */

/*
 *
 *
 *  测式环境
    渠道代码: H777
    渠道密码: USqi28#Kib*029n
    接口接口：https://ts0068.com/ChannelApi/
    拉单独立接口：https://ts0068.com/ChannelApi/GameRecord

    正式环境
    接口接口：https://game.91vipgames.com/ChannelApi/
    拉单独立接口：https://game.91vipgames.com/ChannelApi/GameRecord

    试玩地址：https://91gamess.com
 * */

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

    function __construct()
    {
        $datastr = getVgQpSetting() ;

        $this->tryGameUrl = $datastr['demourl'];
        $this->apiUrl = $datastr['apiurl'];
        $this->recordUrl = $datastr['ld_apiurl'];
        $this->channel = $datastr['agentid'];
        $this->lineCode = $datastr['lineCode'];
        $this->deskey = $datastr['deskey'];
        $this->md5Key = $datastr['md5key'];

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
        $recordID = $params['recordID'];    // 注单id
        $orderId = $params['serial']; // 订单号
        $playtype = $params['playtype']; // 游戏分类
        $apitoken = $params['apitoken']; // apitoken

        $param = null;

        switch ($actionFun) {
            case 'GetToken': // 获取访问token   verifycode 安全验证码
                //$this->apiUrl .= '/Security/' . $actionFun;
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'timestamp' => $time,
                    'verifycode' => strtoupper(md5( $this->channel . $time . $this->md5Key))
                ));
                break;
            case 'CreateUser': // 创建游戏账号
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'agent' => $agent,
                ));
                break;
            case 'loginWithChannel': // 登录游戏
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'gametype'  => $gametype,   //游戏类型
                ));
                break;
            case 'TryGame': // 试玩账号  TryGame
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'gametype'  => $gametype,   //游戏类型  1000游戏大厅
                    //'gameversion' => $gameversion,  //游戏版本 1=flash 2=h5
                    //'verifyCode' => strtoupper(md5($this->channel . $gametype . $gameversion . $this->privateKey))
                ));
                break;
            case 'GetBalance': // 查询会员余额
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                ));
                break;
            case 'GetTransRecord': // 获取会员转账记录
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'serial' => $orderId,
                ));
                break;
            case 'Deposit': // 会员向游戏平台存款
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'amount' => $amount, // 元模式
                    'serial' => $orderId,
                ));
                break;
            case 'Withdraw': // 会员向游戏平台取款
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'amount' => $amount, // 元模式
                    'serial' => $orderId,
                ));
                break;
            case 'updateAgent': // 修改用户代理
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'username' => $username,
                    'agent' => $agent,
                ));
                break;
            case 'GetRecordByID': // 注单id获取对战游戏记录
                $param = http_build_query(array(
                    'channel' => $this->channel,
                    'agent' => $agent,
                    'recordID' => $recordID,
                    'playtype' => $playtype,
                ));
                break;
        }
        $this->writeLog('param: ' . $param);
        //$this->writeLog(date('Y-m-d H:i:s' , $time +12*60*60));
        //$url = ($actionFun=='trygame') ? $this->tryGameUrl : (($actionFun=='gamerecordid') ? $this->recordIdUrl : $this->apiUrl);
        if($actionFun=='GetToken') {
            $this->apiUrl .= '/Security/' . $actionFun;
        }else if($actionFun=='GetRecordByID') {  //(https://<server>/ChannelApi/GameRecord/VG/GetRecordByID?recordID=466236778&agent=AA&playtype=1)
            $this->recordUrl .= '/GameRecord/' . $this->channel .'/GetRecordByID';
        } else {
            $this->apiUrl  .= '/API/' . $this->channel .'/' . $actionFun;
        }
        $url = ($actionFun=='GetRecordByID') ? $this->recordUrl : $this->apiUrl;
        $url .= '?' . $param;
        $this->writeLog('request: ' . $url);
        $res = $this->curl_get_content($url,'', '', '', $apitoken);
        $this->writeLog($res);
        /*if ($actionFun !== 'gamerecordid') {
            $res = $this->xmlLoadStr($res); // 如果gamerecordid方法 返回json, 否则返回 xml
        }*/
        //$this->writeLog($res);

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

    protected function curl_get_content($url, $conn_timeout=10, $timeout=60, $user_agent=null, $apitoken=null)
    {
        $headers = array(
            "Accept: application/json",
            "Accept-Encoding: deflate,sdch",
            "Accept-Charset: utf-8;q=1"
        );
        if ($user_agent === null) {
            $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36';
        }

        if($apitoken) {
            $headers[] = "apitoken:{$apitoken}";
        }
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