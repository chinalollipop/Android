<?php
/**
 * 开元棋牌工具类
 * Date: 2018/8/22
 */

function curl_get_content($url, $conn_timeout=10, $timeout=35, $user_agent=null)
{
	$headers = array(
			"Accept: application/json",
			"Accept-Encoding: deflate,sdch",
			"Accept-Charset: utf-8;q=1"
			);
	if ($user_agent === null) {
		$user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36';
	}
	$headers[] = $user_agent;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,sdch');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conn_timeout);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$res = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$err = curl_errno($ch);
    $error = curl_error($ch);
	curl_close($ch);

	if (($err) || ($httpcode !== 200)) {
        @error_log('【' . date('Y-m-d H:i:s') . '】ERRNO: ' . $err . ' ERROR:' . $error . 'HTTP_CODE:' . $httpcode . ' URL:' . $url. "\n", 3, '/tmp/ky_log/ky_api_error.log');
        return null;
	}
	return $res;
}

function curl_post_content($url, $data, $user_agent=null, $conn_timeout=7, $timeout=5)
{
	$headers = array(
			'Accept: application/json',
			'Accept-Encoding: deflate',
			'Accept-Charset: utf-8;q=1'
			);
	if ($user_agent === null) {
		$user_agent = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36';
	}
	$headers[] = $user_agent;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conn_timeout);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	if ($data) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	}
	$res = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$err = curl_errno($ch);
	curl_close($ch);
	if (($err) || ($httpcode !== 200)) {
		return null;
	}
	return $res;
}



function desEncode($key, $str)
{
	$str = pkcs5_pad(trim($str), 16);
	$encrypt_str = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING);
	return base64_encode($encrypt_str);  
}

function desDecode($key, $str)
{
	$str = base64_decode($str);  
	$decrypt_str = openssl_decrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING);
	return trim(pkcs5_unpad($decrypt_str));  
}

function mcrypt_desEncode($encryptKey, $str)  
{  
	$str = trim($str);  
	$blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);  
	$str = pkcs7_pad($str, $blocksize);  
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);  
	$encrypt_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encryptKey, $str, MCRYPT_MODE_ECB, $iv);  
	return base64_encode($encrypt_str);  
}  

function mcrypt_desDecode($encryptKey, $str)  
{  
	$str = base64_decode($str);  
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);  
	$decrypt_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encryptKey, $str, MCRYPT_MODE_ECB, $iv);  
	return pkcs7_unpad(trim($decrypt_str));  
}  

function pkcs5_pad($text, $blocksize) 
{
	$pad = $blocksize - (strlen($text) % $blocksize);
	return $text . str_repeat(chr($pad), $pad);
}

function pkcs5_unpad($text) 
{ 
	$pad = ord($text{strlen($text)-1}); 
	if ($pad > strlen($text)) return false; 
	if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false; 
	return substr($text, 0, -1 * $pad); 
}

function pkcs7_pad($source, $blocksize)  
{  
	$source = trim($source);  
	$pad = $blocksize - (strlen($source) % $blocksize);  
	if ($pad <= $blocksize) {  
		$char = chr($pad);  
		$source .= str_repeat($char, $pad);  
	}  
	return $source;  
}  

function pkcs7_unpad($source)  
{  
	$source = trim($source);  
	$char = substr($source, -1);  
	$num = ord($char);  
	if ($num == 62) return $source;  
	$source = substr($source, 0, -$num);  
	return $source;  
}  

function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function microtime_int()
{
	return (int)(microtime(true) * 1000);
}

function timestamp_str($format, $timezone)
{
	set_timezone($timezone);
	return date($format);
}

function set_timezone($default) 
{
	$timezone = "";

	// On many systems (Mac, for instance) "/etc/localtime" is a symlink
	// to the file with the timezone info
//	if (is_link("/etc/localtime")) {
//
//		// If it is, that file's name is actually the "Olsen" format timezone
//		$filename = readlink("/etc/localtime");
//
//		$pos = strpos($filename, "zoneinfo");
//		if ($pos) {
//			// When it is, it's in the "/usr/share/zoneinfo/" folder
//			$timezone = substr($filename, $pos + strlen("zoneinfo/"));
//		} else {
//			// If not, bail
//			$timezone = $default;
//		}
//	}
//	else {
//		// On other systems, like Ubuntu, there's file with the Olsen time
//		// right inside it.
//		$timezone = file_get_contents("/etc/timezone");
//		if (!strlen($timezone)) {
//			$timezone = $default;
//		}
//	}
	date_default_timezone_set($timezone);
}

function get_ip() {
	//Just get the headers if we can or else use the SERVER global.
	if ( function_exists( 'apache_request_headers' ) ) {
		$headers = apache_request_headers();
	} else {
		$headers = $_SERVER;
	}
	//Get the forwarded IP if it exists.
	if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$the_ip = $headers['X-Forwarded-For'];
	} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
	} else {

		$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
	}
	return $the_ip;
}

function jsonp_nocache($output)
{
	set_nocache();
	echo jsonp($output);
}

function set_nocache()
{
	header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
	header("Pragma: no-cache"); //HTTP 1.0
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}

function jsonp($data)
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Credentials', false);
	header('Content-Type: application/json; charset=utf-8');
	$json = json_encode($data);
	if(!isset($_GET['callback']))
		return $json;
	if(is_valid_jsonp_callback($_GET['callback']))
		return "{$_GET['callback']}($json)";
	return false;
}

function is_valid_jsonp_callback($subject)
{
	$identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
	$reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case',
			'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue', 
			'for', 'switch', 'while', 'debugger', 'function', 'this', 'with', 
			'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum', 
			'extends', 'super', 'const', 'export', 'import', 'implements', 'let', 
			'private', 'public', 'yield', 'interface', 'package', 'protected', 
			'static', 'null', 'true', 'false');
	return preg_match($identifier_syntax, $subject)
		&& ! in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
}

$g_union = null;

function get_param($name=null, $default=null)
{
	global $g_union;
	if ($g_union === null) {
		$g_union = array_merge($_GET, $_POST); 
		if (empty($g_union)) {
			if(stristr(@$_SERVER['HTTP_USER_AGENT'], ' MSIE')){
				$msie_post = file_get_contents("php://input");
				parse_str($msie_post, $MY_POST);
				$g_union = $MY_POST;
			}
		}
	}
	if ($name === null) {
		return $g_union;
	}
	$value = @$g_union[$name];

	if ($value === '0') {
		return $value;
	}

	empty($value) && ($value=$default);
	return $value;
}
