<?php
header("Content-type:text/html;charset=utf8");
class ServiceUtil{

    //记录回调日志
    static function writelog($filename, $msg) {
        $file = dirname(__FILE__) . '/logs/' . $filename . '.log';
        !is_dir(dirname($file)) && mkdir(dirname($file), 0777, true);
        $handle = fopen($file, 'a');
        flock($handle, LOCK_EX);
        fwrite($handle, sprintf("%s %s\r\n", date('Y-m-d H:i:s',time()), $msg));
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    /**
     * 数组转为url字符串 过滤掉空值字段
     * $arr 数组
     */
    static function get_sign($arr) {
        $signmd5="";
        foreach($arr as $x=>$x_value)
        {
            if(!$x_value==""||$x_value==0){
                if($signmd5==""){
                    $signmd5 =$signmd5.$x .'='. $x_value;
                }else{
                    $signmd5 = $signmd5.'&'.$x .'='. $x_value;
                }
            }
        }
        return $signmd5;
    }

    /**
     * 拼接公钥字符串
     *
     */
    static function publicKeyStr($publicStr){
        //公钥
        $public_key = "-----BEGIN PUBLIC KEY-----\r\n";
        foreach (str_split($publicStr,64) as $str){
            $public_key .= $str . "\r\n";
        }
        $public_key .="-----END PUBLIC KEY-----";

        return $public_key;

    }

    //拼接私钥字符串
    static function privateKeyStr($privatekey){

        $private_key = "-----BEGIN PRIVATE KEY-----\r\n";
        foreach (str_split($privatekey,64) as $str){
            $private_key .= $str . "\r\n";
        }
        $private_key .="-----END PRIVATE KEY-----";

        return $private_key;
    }

    /**
     * 公钥验签
     * @param $arr
     * @param $sign
     * @param $path
     * @return int
     * @throws Exception
     */
    static function verify($plainText, $sign, $path) {

        $resource = openssl_pkey_get_public($path);
        $result = openssl_verify($plainText, base64_decode($sign), $resource);
        openssl_free_key($resource);
        if (!$result) {
            return;
        }
        return $result;
    }

    /**
     *
     * RSA公钥验签
     * @param string $strData 验签数据
     * @param string $signature 签名
     * @return boolean true-成功 false-失败
     */
    static function verifyRSA2($strData, $signature, $publicKey) {
        if (!openssl_get_publickey($publicKey)) {
            echo 'verifyTaiping openssl_get_publickey failed.';
            return false;
        }
        $base64Signature = base64_decode($signature);
        if (!openssl_verify($strData, $base64Signature, $publicKey, OPENSSL_ALGO_SHA256)) {
            echo 'openssl_verify failed.';
            return false;
        }
        return true;
    }

    /**
     * 私钥签名
     * @param $plainText
     * @param $path
     * @return string
     * @throws Exception
     */
    static function sign($plainText, $path){
        try {
            $resource = openssl_pkey_get_private($path);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                return;
            }
            return base64_encode($sign);
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * 获取私钥签名 非对称加密（RSA2）
     * @param string $strData 加密数据
     * @param string $privateKey 私钥
     * @return string $signature 签名
     */
    static function signRSA2($strData, $privateKey) {
        if (!openssl_get_privatekey($privateKey)) {
            echo 'encryptTaiping openssl_get_privatekey failed.';
            return false;
        }
        $signature = '';
        if (!openssl_sign($strData, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            echo 'openssl_sign failed.';
            return false;
        }
        $signature = base64_encode($signature);
        return $signature;
    }

    //发起请求
    static function curlPost($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        return $tmpInfo;

    }



}