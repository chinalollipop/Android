<?php


class AppConstants
{
    public static $NOTIFY_URL = "notify_url";
    public static $RETURN_URL = "return_url";
    public static $PAY_TYPE = "pay_type";
    public static $BANK_CODE = "bank_code";
    public static $MERCHANT_CODE = "merchant_code";
    public static $ORDER_NO = "order_no";
    public static $ORDER_AMOUNT = "order_amount";
    public static $ORDER_TIME = "order_time";
    public static $REQ_REFERER = "req_referer";
    public static $CUSTOMER_IP = "customer_ip";
    public static $RETURN_PARAMS = "return_params";
    public static $ACCOUNT_NAME = "account_name";
    public static $ACCOUNT_NUMBER = "account_number";

    public static $NOTIFY_TYPE = "notify_type";
    public static $TRADE_NO = "trade_no";
    public static $TRADE_TIME = "trade_time";
    public static $TRADE_STATUS = "trade_status";
    public static $NOW_DATE = "now_date";

    public static $KEY = "key";
    public static $SIGN = "sign";

}

class URLUtils
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

class KeyValues
{
    private $kvs = array();
    private $mer_key;
    function setkey($key){
        if (!is_null($key)){
            $this->mer_key=$key;
        }
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
            URLUtils::appendParam($strb, $key, $val);
        }
        URLUtils::appendParam($strb, AppConstants::$KEY, $this->mer_key);
        $strb = substr($strb, 1, strlen($strb) - 1);
        return $strb;
    }
}

function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (REQ_CUSTOMER_ID != null)
        $ip = REQ_CUSTOMER_ID;
    return $ip;
}
