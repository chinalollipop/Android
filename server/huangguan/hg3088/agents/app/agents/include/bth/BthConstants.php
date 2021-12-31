<?php


/* 充值参数 */
define("PAY_URL", "http://apipay.bth.ph");//充值地址
define("AGENT_PAY_URL", "http://apidf.bth.ph");//代付地址
define("MERCHANT_NAME", "demo");//这里填写商户号
define("MERCHANT_SIGN_KEY", "2c2ca864388c45f1ac86a6974a0c0755");//这里填写签名时需要的私钥key
define("MERCHANT_SIGN_KEY_FOR_SN", "999999510cb58e75f3f9f730fc8bba63");//商户公钥 snkey
define("CHARSET", "UTF-8");//当前系统字符集编码
define("PAY_RETURN_URL", "http://127.0.0.1/back");// 这里填写支付完成后，支付平台后台跳转到的页面地址
define("PAY_NOTIFY_URL", "http://127.0.0.1/notify.php");// 这里填写支付完成后，页面跳转到商户页面的URL，同时告知支付是否成功
define("PAY_TYPE", "b2c");//支付方式


class BthConstants
{
	/* 充值参数 */
    public static $BANK_CODE = "bank_code";
    public static $BANK_CARD_NUMBER = "bank_card_number";
    public static $BANK_CARD_NAME= "bank_card_name";
	public static $RETURN_PARAMS = "return_params";
	public static $MERCHANT_ORDER_ID = "merchant_order_id";
    public static $INPUT_CHARSET = "input_charset";	
	public static $RETURN_URL = "return_url";
    public static $NOTIFY_URL = "notify_url";	
    public static $TRANNS_AMOUNT = "trans_amount";	
    public static $TYPE = "type";
    public static $TIMESTAMP = "timestamp";
	public static $MERCHANT_NAME = "merchant_name";	
    public static $KEY = "key";
    public static $SIGN = "sign";

}

class InputCharset
{
    public static $UTF8 = "UTF-8";
    public static $GBK = "GBK";
}

class BTHURLUtils
{
    static function appendParam(& $sb, $name, $val, $and = true, $charset = null)
    {
        if ($and)
        {
            $sb .= "&";
        }
        else
        {
            $sb .= "?";
        }
        $sb .= $name;
        $sb .= "=";
        if (is_null($val))
        {
            $val = "";
        }
        if (is_null($charset))
        {
            $sb .= $val;
        }
        else
        {
            $sb .= urlencode($val);
        }
    }
}

class KeyBthValues
{

    private $kvs = array();
    private $key_val = '';

    public function __construct($key)
    {
        $this->key_val= $key;
    }

    function items()
    {
        return $this->kvs;
    }
    function add($k, $v)
    {
        if (!is_null($v))
            $this->kvs[$k] = $v;
    }
    function sign()
    {
        return md5($this->link());
    }
    function link()
    {
        $strb = "";
        ksort($this->kvs);
        foreach ($this->kvs as $key => $val)
        {
            BTHURLUtils::appendParam($strb, $key, $val);
        }
        BTHURLUtils::appendParam($strb, BthConstants::$KEY, $this->key_val);
        return $strb;
    }
}
