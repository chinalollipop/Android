<?php

class Des3 {
    private $key = "";
    private $iv = "";


    function __construct ($key)
    {
        if (empty($key)) {
            echo 'key is not valid';
            exit();
        }
        $this->key = $key;
        $this->iv = substr($key,0,8);

    }

    public function encrypt ($value) {

        $value = $this->PaddingPKCS7($value);
        $key = $this->key;
        $iv  = $this->iv;
        $cipher = "DES-EDE3-CBC";
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $result = openssl_encrypt($value, $cipher, $key, OPENSSL_SSLV23_PADDING, $iv);
        }
        return $result;



    }

    public function decrypt ($value) {
        $key       = $this->key;
        $iv        = $this->iv;
        $decrypted = openssl_decrypt($value, 'DES-EDE3-CBC', $key, OPENSSL_SSLV23_PADDING, $iv);
        $ret = $this->UnPaddingPKCS7($decrypted);
        return $ret;
    }


    private function PaddingPKCS7 ($data) {
        $block_size = 8;
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    private function UnPaddingPKCS7($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, - 1 * $pad);
    }
}

?>