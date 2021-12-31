<?php
//3des方式加密,加密方式为CBC,填充方式为PaddingPKCS7
//来源：http://www.cnblogs.com/fish-minuet/p/9679117.html
class des3cbc{
    //加密秘钥，
    private $_key;
    private $_iv;
    public function __construct($key){
        $this->_key = $key;
        $this->_iv = $key;
    }
    /**
     * 对字符串进行3DES加密
     * @param string 要加密的字符串
     * @return mixed 加密成功返回加密后的字符串，否则返回false
     */
    public function encrypt3DES($str){
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");
        if ($td === false) {
            return false;
        }
        //检查加密key，iv的长度是否符合算法要求
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));
        if ( empty($this->_iv) ){
            $iv_t = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);//从随机源创建初始向量
        }else{
            $iv_t = $this->_iv;
        }
        $iv = $this->fixLen($iv_t, mcrypt_enc_get_iv_size($td));
        //加密数据长度处理,长度必须是 n * 分组大小，否则需要后补数据，根据不同的补码方式，来补不同的数据
        $str = $this->addPKCS7Padding($str, mcrypt_enc_get_block_size($td));
        //初始化加密所需的缓冲区
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {
            return false;
        }
        $result = mcrypt_generic($td, $str);
        /**
         * 对加密后的数据进行base64加密处理，在入库时，varchar类型会自动移除字符串末尾的“空格”。
         * 由于加密后的数据可能是以空格（ASCII 32）结尾， 这种特性会导致数据损坏。
         * 官方建议请使用 tinyblob/tinytext（或 larger）字段来存储加密数据。
         */
        $result = base64_encode($result);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $result;
    }
    /**
     * 对加密的字符串进行3DES解密
     * @param string 要解密的字符串
     * @return mixed 加密成功返回加密后的字符串，否则返回false
     */
    public function decrypt3DES($str){
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");
        if ($td === false) {
            return false;
        }
        //检查加密key，iv的长度是否符合算法要求
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));
        if ( empty($this->_iv) ){
            $iv_t = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);//从随机源创建初始向量
        }else{
            $iv_t = $this->_iv;
        }
        $iv = $this->fixLen($iv_t, mcrypt_enc_get_iv_size($td));
        //初始化加密所需的缓冲区
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {
            return false;
        }
        $result = mdecrypt_generic($td, base64_decode($str));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        /**
         * 通过 mdecrypt_generic() 函数解密之后的数据是加密之前对加密数据长度补"\0"的数据。
         * 使用 rtrim($str, "\0") 移除字符串末尾的 "\0" 。
         */
        return $this->stripPKSC7Padding($result);
    }
    /**
     * 返回适合算法长度的key，iv字符串,末尾使用0补齐
     * @param string $str key或iv的值
     * @param int $td_len 符合条件的key或iv长度
     * @return string 返回处理后的key或iv值
     */
    private function fixLen($str, $td_len){
        $str_len = strlen($str);
        if ($str_len > $td_len) {
            return substr($str, 0, $td_len);
        } else if($str_len < $td_len) {
            return str_pad($str, $td_len, '0');
        }
        return $str;
    }
    /**
     * 返回适合算法的分组大小的字符串长度，末尾使用\0补齐
     * @param string $str 要加密的字符串
     * @param int $td_group_len 符合算法的分组长度
     * @return string 返回处理后字符串
     */
    private function strPad($str, $td_group_len){
        $padding_len = $td_group_len - (strlen($str) % $td_group_len);
        return str_pad($str, strlen($str) + $padding_len, "\0");
    }
    /**
     * 返回解密后移除字符串末尾的 "\0"的数据
     * @param string $str 解密后的字符串
     * @return string 返回处理后字符串
     */
    private function strUnPad($str){
        return rtrim($str, "\0");
    }
    /**
     * 为字符串添加PKCS7 Padding
     * @param string $str    源字符串
     */
    private function addPKCS7Padding($str, $td_group_len){
        $pad = $td_group_len - (strlen($str) % $td_group_len);
        if ($pad <= $td_group_len) {
                   $char = chr($pad);
            $str .= str_repeat($char, $pad);
        }
        return $str;
    }
    /**
     * 去除字符串末尾的PKCS7 Padding
     * @param string $source    带有padding字符的字符串
     */
	private function stripPKSC7Padding($str){
		$char = substr($str, -1, 1);
		$num = ord($char);
		if($num > 8){//8是此算法的分组大小，可通过mcrypt_enc_get_block_size获取
			return $str;
		}
		$len = strlen($str);
		for($i = $len - 1; $i >= $len - $num; $i--){
			 if(ord(substr($str, $i, 1)) != $num){
					 return $str;
			 }
		}
		$source = substr($str, 0, -$num);
		return $source;
	}
}