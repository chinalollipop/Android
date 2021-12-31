<?php
/**
 * 信付通工具类
 */
class utils
{
    /**
     * 加密方式
     */
    public static function Sign($params, $apiKey)
    {
        ksort($params);
        $string = "";
        foreach ($params as $name => $value) {
            $string .= $name . '=' . $value . '&';
        }
        $string = substr($string, 0, strlen($string) -1 );
        $string .= $apiKey;
        return strtoupper(sha1($string));
    }
    /**
     * 模拟post请求
     */
    public static function postHtml($Url, $PostArry){
        if(!is_array($PostArry)){
            throw new Exception("无法识别的数据类型【PostArry】");
        }
        $FormString = "<body onLoad=\"document.actform.submit()\">正在处理，请稍候.....................<form  id=\"actform\" name=\"actform\" method=\"post\" action=\"" . $Url . "\">";
        foreach($PostArry as $key => $value){
            $FormString .="<input name=\"" . $key . "\" type=\"hidden\" value='" . $value . "'>\r\n";
        }
        $FormString .="</form></body>";
        return $FormString;
    }
    /**
     * 模拟get请求
     */
    public static function getHtml($Url, $PostArry){
        if(!is_array($PostArry)){
            throw new Exception("无法识别的数据类型【PostArry】");
        }
        $FormString = "<body onLoad=\"document.actform.submit()\">正在处理，请稍候.....................<form  id=\"actform\" name=\"actform\" method=\"get\" action=\"" . $Url . "\">";
        foreach($PostArry as $key => $value){
            $FormString .="<input name=\"" . $key . "\" type=\"hidden\" value='" . $value . "'>\r\n";
        }
        $FormString .="</form></body>";
        return $FormString;
    }
    /**
     * 打印成text
     */
    public static function LogWirte($Astring)
    {
        $path = dirname(__FILE__);
        $path = $path."/Log/";
        $file = $path."Log".date('Y-m-d',time()).".txt";
        if(!is_dir($path)){	mkdir($path); }
        $LogTime = date('Y-m-d H:i:s',time());
        if(!file_exists($file))
        {
            $logfile = fopen($file, "w") or die("Unable to open file!");
            fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
            fclose($logfile);
        }else{
            $logfile = fopen($file, "a") or die("Unable to open file!");
            fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
            fclose($logfile);
        }
    }
    /**
     * CURL_POST
     * @param $url
     * @param $postarray
     * @return mixed
     */
    public static function curl_post($url,$postarray){
        $posturl = $url;
        $curl = curl_init($posturl);

        $header = array(
            "Accept: application/json",
            "Accept-Encoding: deflate,sdch",
            "Accept-Charset: utf-8;q=1"
        );
        $header[] = "charset=UTF-8";
        $header[] = "application/x-www-form-urlencoded";
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($postarray));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $err = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if (($err) || ($httpcode !== 200)) {
            //var_dump(curl_errno($curl));
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' response:' . $response. "\n", 3, '/tmp/weiduofu_return.log');
        }

        $response = json_decode($response, true);
        $response['httpcode'] = $httpcode;
        return $response;
    }
    /**
     * curl_get
     * @param $url
     * @param array $data
     * @param int $timeout
     * @return bool|mixed
     */
    public static function curl_get($url, $data = array(), $timeout = 10) {
        if($url == "" || $timeout <= 0){
            return false;
        }
        if($data != array()) {
            $url = $url . '?' . http_build_query($data);
        }
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, false);
        return curl_exec($con);
    }
}