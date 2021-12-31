
<?php


class Security  
{ 

	public function decode($data,$key){
		$res = '';
		$n = strlen($data)/16;
		for ($i=0; $i<=$n; $i++)
		{
			$start = $i*16;
			if($start > strlen($data)) $start = strlen($data);
			$str = substr($data,$start,16);
			$sp_encrypted = strtoupper(bin2hex(openssl_encrypt($str,'AES-128-CBC',hex2bin($key), OPENSSL_RAW_DATA)));
			$res = $res.(substr($sp_encrypted,0,32));
		}
		return $res;
	}

}
?>

