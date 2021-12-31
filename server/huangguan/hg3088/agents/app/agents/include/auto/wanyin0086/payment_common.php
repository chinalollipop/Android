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
            "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoXFXLBrkqoA22WfCWz5FkVEp3C4ofVu4RBxblfZMpmySQavYmA2uUjEx7jlTpgEdvZicUOjYYZvJJ2vkAFkh/dwtZySUqVYXAJ0r+K3JcNnvuLDOfevt/fHWWgYeBYw4JpYkOyh3F6XTi6ddmNdDNDCMQIe3GRDVOZUgHgaqn2lJJ0mpopPTz8rZox+o3FVOGkEBex6zv7+5fK8rk1dRKly7Zn9rMtN+8DI5yXEwDFzZkiShNE7P3o6IyF1CXIVeyPCIFazEItYQwb7h/skJYO0Ioh+epgpXHXWMhVdqFcuhDU+xAMs4ibaYmKNCltMwYOu1NdTnVTNsyqx4SMYraQIDAQAB";

        $pubPem = chunk_split($publicKey,64,"\n");
        $pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";

        $this->ps_public_key =$pubPem;

        // 商戶的RSA私钥，贵司可以透过支付平台的后台产生，但记得储存贵司的公私钥
        $privateKey =
            "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCOlyvoFlfnMG1FDBXLPkhkFCnVj9zzqzCxWhTj9DvqTCDd06Y9eb/SyFTFyDbHBny9Q9r6nztovL0KMPL+9d9+3/jbQBPEMXVY63XsTIvBo6QTmizKS8a2RhFH04C83F3Ufcvft6HL/5rBkbZjDNusoozyoClzyS1MK8QuqmtsMrUzADH0YXvuUlqClQrk8uvnIst5U0znbfWZEe4cwtgXbed8iLWIeg3XvtvC5IX9O5OZb/UFlvfWcKyWkTpvnYdsRoyxMNdOFwe7Du35ABv5ypqp8WoPGnp3ZSFRa6lJIMMA/3I7JrhsoKRZXeLmNwKWJ/cfI2fIEDSpJIqp1E2nAgMBAAECggEAH4PwE2Lk2NdOP2WVZ4+DhoMiX+5lIoIiyAAl/+AaYgWjegPuiUXgjOD0Q6al3MVlSpu1yZDqG5MjoR4ChzGmzS8JnMQD2mgoDJg2Px0IAqt9d0urPKcU63J/HUX0/Ukjf/Sx2+eFXkZHWSMwkaHk57ohtvqJTusXFO/Tc5D9HzOatMETylNoiLnXTAkONVrVbaKz+j89/eNaqRWt10QDB4tUTJOHnLEYx5XlPE4g1TvDc3Gtgl9JRaIhjKIDiWWUjeZgPhpbMJw2YtDnSH94DuPVPQKeli+lmZs4HTexqjT93EI0CWCYw2RD1ZCW9Jj90bN/juv//wj9twRa9cWgaQKBgQD22BllqP/pqJa4kGsJHYwYXakvmbPWORh/BVw8afd8MAMmPPzcAEC1GS8GvFBzjT8Jmz87lyWife1Nx0i2lorDWVhuJK+Dsz72/+UmWG8xZ6eCTBEb9s7rwGrp6eZtd+WCb6QvMG+8OepZpQTvuye06/lM2QnlJtIY6ftXoE/LlQKBgQCT4SKhduzOKlUbxXGrIuoApigfmDxmUCVYqm6KyE45yMzc7TDmGBLK4pqQJhDqNM4iVZ5fDGli+TFu1/x/neIBdon6oL+VwdebHR8+ohci6FCVmoQuMp/dF5Dnvg2WIWL7H9a5MkG8qYe8ZpyzYaLnmyL18bLz4XfaG3xe/SLFSwKBgQCaFFHmDxOO+lj6Y8ssKXFlVDFjMQH8Wi9cOjBU1aBRuHZ+y9raSJ4bbNjZz3o4ZpZOnMVfoP0w7IHP5vEQZ++9+GsmdWfJQX2wGBMlWFYv9I+u2WRknC6VIcqasBHRiuGMBbvqaMWWLGCIDGWEbsJeQF2hBmkSzKSgQI1PKRYl8QKBgFPeftaWbU2sIqLig0otPgvqMJ1iqPbaA6Ra3ODnI76QDOhLucz14Eqi5EhW3ocGNdaxOmXr0yVn8UpNQw5Zy+FKiozTeHUGADMf/CV4pqiMm4+Nl+PfoF0zTWKxnmEERG/qqcxVHYVTnQEULVFrUYoLqXNVhkndGS1hI1N4O60rAoGAUusVUqNferM1dHubH32cZFPbsAbSndy6E4xJqBAZrTbG8rtjZnNmAXWpAAoU3Ohdbr1hdpguH+0Kts0VLvyIfaN35PZS+hAwpBLImIcu3QznnwBXunycKCopj2H+ufzYYf6oZSjHyWYN0DeFCA+cp3ESMToKshgLVXJ275PkKu0=";
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