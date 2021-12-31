<?php
/**
*   1. 支持RSA算法, 加解密钥(RSA/ECB/PKCS1Padding)、验签（SHA1WithRSA）
*   2. 网络请求调用
*
*/
class yb_object {

	private $m_private_key;	//商户私钥
	private $p_public_key; //云宝平台RSA公钥
	
	// 初始化装载密钥信息，仅支持pem格式
    public function __construct($m_pri_key_path, $p_pub_key_path){
        $keydata = file_get_contents($m_pri_key_path);
		$this->m_private_key = openssl_pkey_get_private($keydata);
		($this->m_private_key) or die('您使用的私钥格式错误，请检查RSA私钥配置');
		
		$keydata = file_get_contents($p_pub_key_path);
		$this->p_public_key = openssl_get_publickey($keydata);
		($this->p_public_key) or die('您使用的公钥格式错误，请检查RSA公钥配置');
    }
	
	/**
	 * RSA公钥加密
	 * @param $plainText
	 * @return string
	 * @throws \Exception
	 */
	function rsaEncrypt($plainText) {
		$crypto = '';
		foreach (str_split($plainText, 245) as $chunk){
			openssl_public_encrypt($chunk, $encryptData, $this->p_public_key, OPENSSL_PKCS1_PADDING);
			$crypto .= $encryptData;
		}
		return base64_encode($crypto);
	}
	
	// 私钥解密
	function rsaDecrypt($encrypted,$path){
		$original_enc_str = base64_decode($encrypted);
		//echo "===strlen(original_enc_str)=".strlen($original_enc_str)."===<br>";
		$orig_dec_str = '';
		for($i=0;$i<strlen($original_enc_str)/256;$i++)
		{
			$data=substr($original_enc_str,$i*256,256);
			openssl_private_decrypt($data, $decrypt, $this->m_private_key, OPENSSL_PKCS1_PADDING);
			$orig_dec_str.=$decrypt;
		}
		return $orig_dec_str;
	}
    
	// 私钥进行签名
	public function rsaSign($data) {
		openssl_sign($data, $signval, $this->m_private_key, OPENSSL_ALGO_SHA1);
		return base64_encode($signval);
	}
	
	// 公钥验签操作
	function rsaVerify($sign_val, $original_str) {
		$sign_val = base64_decode($sign_val);//得到的签名
		$result=(bool)openssl_verify($original_str, $sign_val, $this->p_public_key);
		return $result;
	}
	
	// 请求数据
	function doPost($url, $data){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_SSLVERSION, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 15);
		//curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data,JSON_UNESCAPED_UNICODE)); 	
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data); 		
		$resp = curl_exec ( $ch );
		curl_close($ch);
		return $resp;
	}
	
	function free() {
		if($this->m_private_key) {
			openssl_free_key($this->m_private_key);
		}
		if($this->p_public_key) {
			openssl_free_key($this->p_public_key);
		}
	}
}