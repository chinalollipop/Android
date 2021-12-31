<?php
header("Content-type:text/html;charset=utf-8");

//生成签名的方法
function create_sign($data,$key){
		ksort($data);
		$sign = strtoupper(md5(json_encode_ex($data) . $key));
		return $sign;
	}
//判断 php版本 编译成 json字符串
function json_encode_ex($value){
		 if (version_compare(PHP_VERSION,'5.4.0','<')){
			$str = json_encode($value);
			$str = preg_replace_callback("#\\\u([0-9a-f]{4})#i","replace_unicode_escape_sequence",$str);
			$str = stripslashes($str);
			return $str;
		}else{
			return json_encode($value,320);
		}
	}
function replace_unicode_escape_sequence($match) {
		return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
	}

// 加密
function encode_pay($data,$pay_public_key){#加密//		
		$pu_key =  openssl_pkey_get_public($pay_public_key);
		if ($pu_key == false){
			echo "打开密钥出错";
			die;
		}
		$encryptData = '';
		$crypto = '';
		foreach (str_split($data, 117) as $chunk) {            
            openssl_public_encrypt($chunk, $encryptData, $pu_key);  
            $crypto = $crypto . $encryptData;
        }

		$crypto = base64_encode($crypto);
		return $crypto;

	}
//发起请求 返回 请求结果
function wx_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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

//解密
 function decode($data,$private_content){   
 		//读取秘钥
		$pr_key = openssl_pkey_get_private($private_content);
			
		if ($pr_key == false){
			echo "打开密钥出错";
			die;
		}
		$data = base64_decode($data);			
		$crypto = '';
		//分段解密   
        foreach (str_split($data, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $pr_key);
            $crypto .= $decryptData;
        }
        return $crypto;
	}




// 对返回结果进行签名认证
function json_to_array($json,$key){
		$array=json_decode($json,true);	
		if ($array['stateCode'] == '00'){
			$sign_string = $array['sign'];
			ksort($array);
			$sign_array = array();
			foreach ($array as $k => $v) {
				if ($k !== 'sign'){
					$sign_array[$k] = $v;
				}
			}
			// 生成签名 并将字母转为大写
			$md5 =  strtoupper(md5(json_encode_ex($sign_array) . $key));
 			if ($md5 == $sign_string){
 				return $sign_array;
 			}else{
 				$result = array();
 				$result['stateCode'] = '99';
 				$result['msg'] = '返回签名验证失败';
 				return $result;
 			}
		}else{
			$result = array();
 			$result['stateCode'] = $array['stateCode'];
 			$result['msg'] = $array['msg'];
			return $result;
		}

	}

// 对返回结果进行签名认证
function callback_to_array($json,$key){
	$array = json_decode($json,true);		
	$sign_string = $array['sign'];
	ksort($array);
	$sign_array = array();
	foreach ($array as $k => $v) {
		if ($k !== 'sign'){
			$sign_array[$k] = $v;
		}
	}

	$md5 =  strtoupper(md5(json_encode_ex($sign_array) . $key));
	if ($md5 == $sign_string){
		return $sign_array;
	}else{
		$result = array();
		$result['payResult'] = '99';
		$result['msg'] = '返回签名验证失败';
		return $result;
	}

}