<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/7/24
 * Time: 19:02
 */


class ServiceUtil
{
    //记录日志函数
    public static function writelog($filename, $msg) {
        $file = dirname(__FILE__) . '/logs/' . $filename . '.log';
        !is_dir(dirname($file)) && mkdir(dirname($file), 0777, true);
        $handle = fopen($file, 'a');
        flock($handle, LOCK_EX);
        fwrite($handle, sprintf("%s %s\r\n", date('Y-m-d H:i:s',time()), $msg));
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    /**
     * 拼接字符串
     * $arr 数组
     * @param $arr
     * @return string
     */
    public static function get_sign($arr) {
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
     *  签名字符串拼接
     */
    public static function signStr($arr, $randStr){
        ksort($arr);
        $randArr = str_split($randStr);
        $arrKey = array_keys($arr);
        $signArr = array();
        foreach ($randArr  as $value){
            $k = $arrKey[$value];
            $signArr[$k] = $arr[$k];
        }
        return $signArr;
    }

    /**
     *  随机数
     */
    public static function generateRandNum($n){
        $randArr = array();
        do{
            $num = rand(0, $n-1);
            if(!in_array($num, $randArr)){
                array_push($randArr, $num);
            }
        }while(count($randArr) < $n);
        return implode($randArr);
    }


    /**
     * 拼接公钥字符串
     * @param $publicStr
     * @return string
     */
    public static function publicKeyStr($publicStr){
        //公钥
        $public_key = "-----BEGIN PUBLIC KEY-----\r\n";
        foreach (str_split($publicStr,64) as $str){
            $public_key .= $str . "\r\n";
        }
        $public_key .="-----END PUBLIC KEY-----";

        return $public_key;

    }

    /**
     * 拼接私钥字符串
     * @param $privatekey
     * @return string
     */
    public static function privateKeyStr($privatekey){

        $private_key = "-----BEGIN PRIVATE KEY-----\r\n";
        foreach (str_split($privatekey,64) as $str){
            $private_key .= $str . "\r\n";
        }
        $private_key .="-----END PRIVATE KEY-----";

        return $private_key;
    }

    /**
     * 公钥加密
     * $publicKey 公钥密文
     * $data 加密数据
     * @param $publicKey
     * @param $data
     * @return string
     */

    public static function encrypt($publicKey, $data) {

        $key = openssl_get_publickey($publicKey);

        $original_arr = str_split($data,117);
        foreach($original_arr as $o) {
            $sub_enc = null;
            openssl_public_encrypt($o,$sub_enc,$key);
            $original_enc_arr[] = $sub_enc;
        }

        openssl_free_key($key);
        $original_enc_str = base64_encode(implode('',$original_enc_arr));
        return $original_enc_str;
    }

    /**
     * [decode 私钥解密]
     * @param  [type] $data       [待解密字符串]
     * @param  [type] $privateKey [私钥]
     * @return [type]             [description]
     */
    public static function privateDecrypt($data,$privateKey){
        //读取秘钥
        $pr_key = openssl_pkey_get_private($privateKey);
        if ($pr_key == false){
            echo "打开密钥出错";
            die;
        }

        $data = base64_decode($data);
        openssl_private_decrypt($data, $decryptData, $pr_key);
        return $decryptData;
    }

    /**
     * POST模拟请求
     * @param $url 请求地址
     * @param $dataStr 请求参数
     * @return bool|mix|mixed|string
     * @throws Exception
     */
    public static function streamContextCreate($url,$dataStr, $headerKey){
        $httpData["method"] = "POST";
        $httpData["header"] = "Content-type: application/x-www-form-urlencoded\r\n"."security_header_key: ".$headerKey."";
        $httpData["content"] = $dataStr;
        $http["http"]=$httpData;
        $resultData = "";
        try {
            $context = stream_context_create($http);
            $result = file_get_contents($url, false, $context);
            $resultData= json_decode($result,true);
        } catch (Exception $e) {
            throw $e;
        }
        return $resultData;
    }

    /**
     * 发送请求
     * @param $url
     * @param $param
     * @return bool|mixed
     * @throws Exception
     */
    public static function http_post_json($url, $param)
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        try {

            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //正式环境时解开注释
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);

            if (!$data) {
                throw new \Exception('请求出错');
            }

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}