<?php
/**
 * 自动出款-HiPay3127 工具类
 * Date: 2020/1/8
 */
class AutoHiPay
{
    private $payInfo;
    private $publicKey;
    private $postUrl = 'https://hipay3721.com/payweb/outPay';

    public function __construct($payInfo = []) {
        $this->payInfo = $payInfo;
        $this->publicKey = file_get_contents(__DIR__  . '/rsa_public_key.pem');
    }

    //发起支付请求
    public function payAction() {
        // 商户号
        $data['merchantNo'] = $this->payInfo['merchantNo'];
        // 商户订单号
        $data['merchantOrderNo'] = $this->payInfo['merchantOrderNo'];
        // 当type=1的时候，outNo为银行卡号，当type=2的时候，outNo为支付宝账
        $data['outNo'] = $this->payInfo['outNo'];
        // 当type=1的时候,outName为银行卡户主姓名，当type=2的时候,outName为支付宝姓名
        $data['outName'] = $this->payInfo['outName'];
        // 银行名称：当type是1的时候必填
        $data['bankName'] = $this->payInfo['bankName'];
        // 出款金额（金额为整数，不能带小数点）
        $data['amount'] = $this->payInfo['amount'];
        // 商户密钥
        $data['key'] = $this->payInfo['key'];
        // 支付类型 1：微信 2：支付宝
        $data['type'] = $this->payInfo['type'];

        $originData = $this->getParameterStr($data); // 格式化参数（sign、pr明文参数）
        $postParam['sign'] = md5($originData); // 请求参数sign
        $prEncodeString = $this->publicEncrypt($originData);
        $postParam['pr'] = urlencode($prEncodeString); // 请求参数pr
        $nuEncodeString = $this->publicEncrypt($this->payInfo['notifyUrl']);
        $postParam['nu'] = urlencode($nuEncodeString); // 请求参数nu

        $result = $this->curlPost($this->postUrl, $postParam);
        $return = json_decode($result, true);
        return $return;
    }

    /**
     * 获取待RSA、MD5加密的参数字符串
     * merchantNo=pwb1553510308696&MerchantOrderNo=0000000005&OutNo=66666669&OutName=银行卡出款&bankNam=中国农业银行&amount=10000&key=27298aba450ef021afa40dd1f7762576&type=1
     * @param $data
     * @return string
     */
    public function getParameterStr($data){
        $parameter = 'merchantNo='.$data['merchantNo'].'&merchantOrderNo='.$data['merchantOrderNo'].'&outNo='.$data['outNo'].'&outName='.$data['outName'].'&bankName='.$data['bankName'].'&amount='.$data['amount'].'&key='.$data['key'].'&type='.$data['type'];
        return $parameter;
    }

    /**
     * @uses 公钥加密
     * @param string $data
     * @return null|string
     */
    public function publicEncrypt($data = '') {
        if (!is_string($data)) {
            return null;
        }

        return openssl_public_encrypt($data, $encrypted, openssl_pkey_get_public($this->publicKey)) ? base64_encode($encrypted) : null;
    }

    /**
     * POST提交
     * @param $url
     * @param $data
     * @return mixed
     */
    public function curlPost($url, $data){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type'=>'application/x-www-form-urlencoded;charset=utf-8'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if (($err) || ($httpCode !== 200)) {
            @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpCode . ' URL:' . $url. "\n", 3, '/tmp/hipay_request_api_error.log');
            return null;
        }
        return $output;
    }
}