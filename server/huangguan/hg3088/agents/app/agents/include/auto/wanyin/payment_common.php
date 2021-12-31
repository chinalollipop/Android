<?php 
/**
 * payment Sample Code 支付范例代码
 *  
 * @author 
 * @Date 
 * @Version 3.2
 * @Desc 
 */
 class payment_class{

    /**
    * 共用参数区
    */
    function __construct($merchantCode,$notifyUrl){
    
    /**需调整参数*/
    $this->withdrawUrl = 'https://api.wanyinpay2.com/rsa/withdraw';  //payment提现API接口
    $this->depositUrl = 'https://api.wanyinpay2.com/rsa/deposit';  //payment充值API接口
    $this->merchantCode = $merchantCode;              //payment代理商号
    $this->depositCallbackUrl = 'http://www.XXXX.com/callback.php';     //Callback URL 这部份需要贵司更换为贵司的充值回调连接
    $this->withdrawCallbackUrl = $notifyUrl;    //Callback URL 这部份需要贵司更换为贵司的提现回调连接

        //支付平台公钥
        $publicKey=
            "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhAo4gq43QQ0cS84S3UJRkHYaZFJXyc0K0iLM0Teu7Cpl5VnFox7xXNaiptf7iB4RmFCPyp04geYJOTqQygurssnJB0cGi+HuL8C3TBCOFSl+TmkonubuJeyt+c9UxzLpc+aW2oBgomyrf89ZnT7/LnxPxUQ5pnLeNAFg+F1zT1rF8kgIvS4fRGseYl1WUoGg3+20Wej5RIU5UF0pIJEnGFuznOamtwhID8JCQS8y1VoHSYhG736rTrwmXy2OmDaaLzJeqHlIP4ITPBpMYuvISvVTrusjkQFkP5WPducml0OcQ2Jqjtt89M8HXMCHvIzERPQNvZCstUDpmry5H9xSUwIDAQAB";

        $pubPem = chunk_split($publicKey,64,"\n");
        $pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";

        $this->ps_public_key =$pubPem;

        // 商戶的RSA私钥，贵司可以透过支付平台的后台产生，但记得储存贵司的公私钥
        $privateKey =
            "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCfRkiqAyr83H7oA5v+be3QtG+D+UbZm9n4bJg5jlefzCq5l+Yuzu9xgedb08fzYLd2ynzaOhHxwqYef4AC1lSjhPEGn568bMQh39OpLciIzc8hJVrKvJqrJ2GcQkrQKC/1m1u12zoyXuYTMFywt2CQOa0lroETw42UYO9voeGuwQlgwKYlskgI3tY3CCfmzcyB6mg69PDuEJtYjtuL9kWmMMQxcEHp8X4oPbwRAhUwMrdjAE+X9wbJf39E9glsoQndAIFB1HGIGZ3t9CDPLEjLLoEB1l60dCBWardsj0l7pkO3p8cUFr7Md/CruB6oWDunaB+u7k8njrjg3xhiKUSDAgMBAAECggEAROEqT4vrLUtV/pSBA7kanaVKjLJi93y0+QBNnKZ4Mn0jBPwx6ZBIcLgTC908nX34GYFcGSv/0qOxH4NYIuH53HWDnq+tACOxdkzLxmAsVy1aJCV5bC/AW8A+MEti+z5F71tuf+j89AeiTC4gB7RmKDkpLiCXZaquMbryET7a0K2td7U7ar3BtSXx215CbytpA9UhPnJ2ssZymmvD2ht9FCJjiOE3rsU2kHXSZzW+yfWnT16P/esxXUcZJlfpwxDjdrUEB6okeD7/Rl6gw0T1Znuv2p1Z8/UuTcTjfoHAV71//WBFWH19Ea3iuDFeot7nwfpjijKIjbqD93oDgUbGcQKBgQDu2YXp/tpgRBlXhEitCj00hWv/6NUKab02lNPomnFE97Xz7o1yGh8IDKVUI79H31si8BgaZ/Bbm7wbYhqzu2DPbLDyrq5uefm0YQUaRTW9Z3i60wew2l9tijpLpWHi6TkirkqvgO62VuINycLtmTYWvgEhViaxmwjaayMajbwZjwKBgQCqtgeZxi9IMRe8bslBQdtmQpEc+ami3r/Fg2ESphX2zaozf9AJfmBw0VONXclw4Sju9rFeNYHTHcof5PFWR5m8YmV2lAg4r9Rw2HJP642UlvNousO5/3J9aCJRoBLD3VNRBwyIkJ6m+D3Qwz8R/CtQyKYnQkUf7A8zzKCh1LHjzQKBgH3/adFCi9h0lBnCwsPlvtvR9mMNZDUWCqacZB00O8RyGB4SHbOva+dHJH/6S5GSlfUcStrDMdxhjx2y5vshQbSqVb3vwDyvQ7aP560wDWVZDCsh39C/oc+bN51oJPcaqPVOlD94+HRf6Of1I0tLo2jj2pzHYaoXatgt5FrBohSpAoGABpW+9U+Pw1khcUNUwA+qRueKcXOv93hgLV5EAFQnxL1qM1Ja1ALr9W4dqvZTLueAjLc3YErwFxSvF0vdg1Z/t6SUBV7wcj9WEoRG5I5Rh8nK2d9abXJNGElMCZoDH0sKS7XZ5equNAKfX84oQKlZgmQ7BIip4fFZJMZf694ofoECgYBDHEpKSeoWXtD4O5r4NKqxyFT4ikLGCtdcj+5qkw4Kb8F88VZy0UeWFE+LxR3YMcxUn7w/xFSScEl/pgbVQSGSxKZzT8CcoVq8pGhPXsgxZai+IbE96Ni8bPhDv0vewq690jR/STgnwVoBYVnFy/4iTkxZNJt0o3n66j2zvI+BHQ==";
        $priPem = chunk_split($privateKey,64,"\n");
        $priPem = "-----BEGIN PRIVATE KEY-----\n" . $priPem . "-----END PRIVATE KEY-----\n";
        $this->merchantPrivateKey=$priPem ;


		// Common 固定常数
		$this-> RISK_LVL	= '1';
		$this-> PLATFORM	= 'PC';
    }
	

    /*
    * 充值申请
    * $bankCode(string)         银行代码(只有网关使用到)
    * $serviceType(string)      服务类型
    * $amount(string)           金额(支持到小数点俩位)
    * $merchantUser(string)     商户用户名称
    * $merchantOrderNo(string)  商户订单代码(不能重复)
    * $note(string)             备注(可为空字段)
    * $return                   回传值
    */
    public function depositRequest($bankCode, $serviceType, $amount, $merchantUser, $merchantOrderNo, $note){
		 
//网关service type:1
//需要使用bankcode，其馀都不需要使用到bankcode
//参数也需要一并拿掉
if  ( $serviceType === "1"  ) { 
        $depositParameters = array(
        'amount' => $amount,
        'platform' => $this-> PLATFORM,
        'note' => $note ,
        'bank_code' =>$bankCode, 
        'service_type' => $serviceType,
        'merchant_user' => $merchantUser, 
        'merchant_order_no' => $merchantOrderNo,
        'risk_level' => $this-> RISK_LVL,
        'callback_url' => $this->depositCallbackUrl,
        ); 
}
else
    {
        $depositParameters = array(
        'amount' => $amount,
        'platform' => $this-> PLATFORM,
        'note' => $note ,
        'service_type' => $serviceType,
        'merchant_user' => $merchantUser, 
        'merchant_order_no' => $merchantOrderNo,
        'risk_level' => $this-> RISK_LVL,
        'callback_url' => $this->depositCallbackUrl,
        ); 
    }
        
        //json格式化后在去掉反斜线
        $ex =  stripslashes(json_encode($depositParameters));
        
        //使用支付后台公钥 加密json data
        $ret_e = $this->encrypt($ex,$this->ps_public_key); 
        
        //制作簽名
        $tmp_sign = $this->sign($ret_e); 
        
        //组合post字段转换特殊字符
        $tmpSend="merchant_code=".$this->merchantCode."&data=".rawurlencode($ret_e)."&sign=".rawurlencode($tmp_sign);
        
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n", 
                'content' => $tmpSend
            )
        );
        
        //传送请求
        $context = stream_context_create($opts); 
        
        //接收返回信息
        $result = file_get_contents($this->depositUrl, false, $context);
        
        $J=json_decode($result);
        $tmp_str=$J->data;
        $tmp_error=$J->error_code; 
        
        $decrypted = "";
        $decodeStr =($tmp_str);  
        
        //解密信息
        $decrypted =$this->decrypt($decodeStr );
        //判断是否有错误,有错误输出错误代码，没错误输出充值连结
        if (empty($transaction_url)==1) {
            $J2=json_decode($decrypted); 
            $result=$J2->transaction_url;
        }
        
        if (!$tmp_error=="") { 
            $result= $tmp_error;
        }
        
        return  $result;
    }
    
    
    /**
     * 提现申请
     * $bankCode(string)         银行代码
     * $card_num(string)         银行卡卡号
     * $amount(string)           金额(支持到小数点俩位)
     * $merchantUser(string)     商户用户名称
     * $merchantOrderNo(string)  商户订单代码(不能重复)     
     * $card_name(string)        银行卡姓名
     * $bank_branch(string)      银行卡分行
     * $bank_province(string)    银行卡省份
     * $bank_city(string)        银行卡城市
     * $return                   回传值
     */
    public function withdrawRequest($bankCode, $card_num, $amount, $merchantUser, $merchantOrderNo, $card_name , $bank_branch, $bank_province, $bank_city)
    {
        
        $withdrawParameters = array(  
        'amount' => $amount, 
        'platform'  =>  $this-> PLATFORM,
        'bank_code'  =>  $bankCode, 
        'merchant_user'  =>  $merchantUser,
        'merchant_order_no'  =>  $merchantOrderNo,        
        'card_num'  =>  $card_num,        
        'card_name'  =>  $card_name,
        'bank_branch'  =>  $bank_branch,
        'bank_province'  =>  $bank_province,
        'bank_city'  =>  $bank_city,        
        'callback_url'  =>  $this->withdrawCallbackUrl,
        );
 
        //json格式化后在去掉反斜线
        $ex =  urldecode(json_encode(array_map('urlencode', $withdrawParameters)));

//        @error_log($ex.PHP_EOL, 3, '/tmp/onlinepay_api.log');
        
        //使用捷付支付后台公钥 加密json data
        $ret_e = $this->encrypt($ex,$this->ps_public_key); 
         
        //制作簽名
        $x = $this->sign($ret_e);
        
        //组合post字段转换特殊字符
        $tmpSend="merchant_code=".$this->merchantCode."&data=".rawurlencode($ret_e)."&sign=".rawurlencode($x); 

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n", 
                'content' => $tmpSend
            )
        );
        
        //传送请求
        $context = stream_context_create($opts);
        
        //接收返回信息
        $result = file_get_contents($this->withdrawUrl, false, $context);

        $J=json_decode($result);
        $tmp_str=$J->data;
        $tmp_error=$J->error_code;

        $decrypted = "";
        $decodeStr =  ($tmp_str);
        
        //解密信息
        $decrypted =$this->decrypt($decodeStr );
            
            //判断是否有错误,有错误输出错误代码，没错误输出 交易ID
            if ($tmp_error=='') {
                $J2=json_decode($decrypted); 
                $result=$J2->trans_id;
            } else {
                $result=$tmp_error;
            }         
        
        return  $result; 
    }   
 


    //call back 验签
    public function verifySign($data,$sign )
    {
    $tmp_sign=openssl_verify( ($data), base64_decode(($sign)), $this->ps_public_key  );   //平台公钥验签
    $result=$tmp_sign; 
        return  $result;
    }  
 

    
 
    //提现callback解密
    public function depositCallback($data){
        $decodeStr = base64_decode($data);
        $enArray = str_split($decodeStr, 256);
        foreach ($enArray as $va) {
            openssl_private_decrypt($va,$decryptedTemp,$this->merchantPrivateKey );//私钥解密
            $decrypted .= $decryptedTemp;
        } 
        $result=$decrypted; 
        return  $result;
    } 
 

//=================================================================================================================================


        /*
         *自定义错误处理方式
         */
        private function _error($msg)
        {
            die('RSA Error:' . $msg); //TODO
        }
    
        /**
         * 检查填充类型
         * 加密只支持PKCS1_PADDING
         * 解密支持PKCS1_PADDING和NO_PADDING
         *
         * $padding int 填充模式(OPENSSL_PKCS1_PADDING,OPENSSL_NO_PADDING ...etc.)
         * $type string 加密en/解密de
         * $ret bool
         */
        private function _checkPadding($padding, $type)
        {
            if ($type == 'en') {
                switch ($padding) {
                    case OPENSSL_PKCS1_PADDING:
                        $ret = true;
                        break;
                    default:
                        $ret = false;
                }
            } else {
                switch ($padding) {
                    case OPENSSL_PKCS1_PADDING:
                    case OPENSSL_NO_PADDING:
                        $ret = true;
                        break;
                    default:
                        $ret = false;
                }
            }
            return $ret;
        }
    
        private function _encode($data, $code)
        {
            switch (strtolower($code)) {
                case 'base64':
                    $data = base64_encode('' . $data);
                    break;
                case 'hex':
                    $data = bin2hex($data);
                    break;
                case 'bin':
                default:
            }
            return $data;
        }
    
        private function _decode($data, $code)
        {
            switch (strtolower($code)) {
                case 'base64':
                    $data = base64_decode($data);
                    break;
                case 'hex':
                    $data = $this->_hex2bin($data);
                    break;
                case 'bin':
                default:
            }
            return $data;
        }
        
        private function _hex2bin($hex = false)
        {
            $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
            return $ret;
        }
 
 
        /**
         * 生成签名
         * $data string 签名材料
         * $code string 签名编码（base64/hex/bin）
         * $ret 签名值
         */
        public function sign($data, $code = 'base64')
        {
            $ret = false;
            if (openssl_sign($data, $ret, $this->merchantPrivateKey ,OPENSSL_ALGO_SHA1 )) {
                $ret = $this->_encode($ret, $code);
            }
            return $ret;
        }
    
        /**
         * 驗證簽名
         *
         * @param string 簽名材料
         * @param string 簽名值
         * @param string 簽名編碼（base64/hex/bin）
         * @return bool
         */
        public function verify($data, $sign, $code = 'base64')
        {
            $ret = false;
            $sign = $this->_decode($sign, $code);
            if ($sign !== false) {
                switch (openssl_verify($data, $sign, $this->ps_public_key )) {
                    case 1:
                        $ret = true;
                        break;
                    case 0:
                    case -1:
                    default:
                        $ret = false;
                }
            }
            return $ret;
        }
    
        /**
         * 加密
         *
         * @param string 明文
         * @param string 密文編碼（base64/hex/bin）
         * @param int 填充方式(所以目前僅支持OPENSSL_PKCS1_PADDING)
         * @return string 密文
         */
        public function encrypt($data , $code = 'base64', $padding = OPENSSL_PKCS1_PADDING )
        {
            $ret = false; 
            if (!$this->_checkPadding($padding, 'en')) $this->_error('padding error');
            $tmpCode=""; 

           
            //明文过长分段加密
            foreach (str_split($data, 117) as $chunk) {
                openssl_public_encrypt($chunk, $encryptData, $this->ps_public_key, $padding);  
                $tmpCode .=$encryptData ;
                $ret = base64_encode($tmpCode);
                
            } 
            return $ret;
        }
    
        /**
         * 解密
         *
         * @param string 密文
         * @param string 密文編碼（base64/hex/bin）
         * @param int 填充方式（OPENSSL_PKCS1_PADDING / OPENSSL_NO_PADDING）
         * @param bool 是否翻轉明文（When passing Microsoft CryptoAPI-generated RSA cyphertext, revert the bytes in the block）
         * @return string 明文
         */
        public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
        {
            $ret = false;
            $data = $this->_decode($data, $code);
            if (!$this->_checkPadding($padding, 'de')) $this->_error('padding error');
            if ($data !== false) { 
                
            $enArray = str_split($data, 256);
            foreach ($enArray as $va) {
                openssl_private_decrypt($va,$decryptedTemp,$this->merchantPrivateKey);//私钥解密
                $ret .= $decryptedTemp;
            }  
                }
			else
			{
				echo "<br>解密失敗<br>".$data;
			}
			
			
			
			
            return $ret;
        }
 
	
	
 }