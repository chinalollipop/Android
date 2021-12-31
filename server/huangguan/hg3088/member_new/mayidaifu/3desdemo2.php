<?php
class TestController extends Yaf_Controller_Abstract
{
    private $req;

    //初始化方法
    public function init(){
        $rs = F_Helper_Function::callback(false,"服务器维护",9999);
        //exit(json_encode($rs));
        $this->req = $this->getRequest();
    }

    public function testAction(){
        $key = "18a6928e7337f02f9c60602691f2c4f7";
        $des = new F_Helper_3Des($key);
        //        10101添加转账任务
        $str = array(
            'business'=>'Transfer',
            'business_type'=>10101,
            'api_sn'=>'1234567895',
            'notify_url'=>urlencode('http://xxx.xxx.xxx/asd/fds'),
            'money'=>'2000',
            'bene_no'=>'6212264100034404114',
            'bank_id'=>1,
            'payee'=>urlencode('收款人'),
            'phone'=>'13982048111',
        );

//        20101查询交易明细
//        $str = array(
//            'business'=>'Query',
//            'business_type'=>20101
//        );

               // 20102查询转账任务
//        $str = array(
//            'business'=>'Query',
//            'business_type'=>20102,
//            'api_sn'=>'1234567894'
//        );

        $api_test = new Api_Common($str,$key);
        $params = $api_test->requestParamsEncode();
        var_dump($params);
        exit;
    }

}

class Api_Common
{
    /** 请求参数 */
    protected $parameters;
    /** 密钥 */
    protected $key;
    /** 请求超时时间秒 */
    protected $time_out = 30;

    /**
     *构告初始
     *@param array $post 参数数组值
     */
    public function __construct($post=null,$key=null){
        //设置参数
        if($post) $this->setAllParams($post);
        if($key) $this->key = $key;
    }

    /**
     *获取参数值
     *@param string $parameter 参数键
     *@return string 参数值
     */
    public function getParameter($parameter){
        return isset($this->parameters[$parameter])?$this->parameters[$parameter]:'';
    }

    /**
     *设置参数值
     *@param string $parameter 参数键
     *@param string $parameterValue 参数值
     */
    public function setParameter($parameter, $parameterValue){
        $this->parameters[$parameter] = $parameterValue;
    }

    /**
     * 一次性设置参数
     *@param array $post 参数数组
     *@param array $filterField 过虑参数
     */
    public function setAllParams($post,$filterField=null){
        if($filterField){
            foreach($filterField as $k=>$v){
                unset($post[$v]);
            }
        }
        //判断是否存在空值，空值不提交
        foreach($post as $k=>$v){
            if($v == ""){
                unset($post[$k]);
            }
        }
        $this->parameters = $post;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    public function argSort($para){
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para){
        $para_filter = array();
        foreach ($para as $k=>$v){
            if($k == "sign" || $v == "") continue;
            else $para_filter[$k] = $para[$k];
        }
        return $para_filter;
    }

    /**
     * 生成签名
     * @param array $data 签名数组
     * return string 签名后的字符串
     */
    public function dataSign($data){
        $data = $this->paraFilter($data);
        $data = $this->argSort($data);
        $data_signstr = "";
        foreach ($data as $k => $v) {
            $data_signstr .= $k . '=' . $v . '&';
        }
        $data_signstr .= 'key='.$this->key;
        return strtoupper(md5($data_signstr));
    }

    /**
     * 验证签名
     * @param array $data 数组
     * @param array $sign 签名
     * return bool
     */
    public function verifySign($data,$sign){
        $verify_sign = $this->dataSign($data);
        if($verify_sign != $sign) return false;
        return true;
    }

    /**
     * 请求参数加密
     * @return string 加密字符串
     */
    public function requestParamsEncode(){
        $des = new F_Helper_3Des($this->key);
        $this->setParameter('timestamp',time());
        $this->setParameter('sign',$this->dataSign($this->parameters));
        $params = json_encode($this->parameters);
//        var_dump($params);
//        var_dump($des->encrypt($params));
//        var_dump(base64_encode($des->encrypt($params)));exit;
//        return $des->encrypt($params);
        return base64_encode($des->encrypt($params));
    }

    /**
     * 检查签名与请求时间是否超时
     */
    public function checkTimestamp(){
        $timestamp = intval($this->getParameter("timestamp"));
        if (($timestamp + $this->time_out) < time()) {
            //return F_Helper_Function::callback(false,"请求超时", 3000);
        }
        return F_Helper_Function::callback();
    }
}

class F_Helper_3Des
{
    private $key = "";
    private $iv = "";

    /**
     * 构造
     *
     * @param string $key
     */
    function __construct($key)
    {
        $this->key = $key;
        $this->iv = $key;
    }

    /**
     *加密
     * @param <type> $value
     * @return <type>
     */
    public function encrypt($value)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        $value = $this->PaddingPKCS7($value);
        mcrypt_generic_init($td, $this->key, $this->iv);
        $ret = base64_encode(mcrypt_generic($td, $value));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    /**
     *解密
     * @param <type> $value
     * @return <type>
     */
    public function decrypt($value)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->iv);
        $ret = trim(mdecrypt_generic($td, base64_decode($value)));
        $ret = $this->UnPaddingPKCS7($ret);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    private function PaddingPKCS7($data)
    {
        $block_size = mcrypt_get_block_size('tripledes', 'cbc');
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    private function UnPaddingPKCS7($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}
