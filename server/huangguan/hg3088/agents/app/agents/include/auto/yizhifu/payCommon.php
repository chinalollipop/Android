<?php

    $agentPay = 'http://agent.epay777.com/rb-pay-web-merchant/agentPay/';
    /**
     * 签名参数组装排序字符串
     * @param $params
     * @return string
     */
    function getSignMsg($pay_params = array(),$paySecret) {//签名
        $params_str = "";
        $signMsg = "";
        ksort($pay_params);//以参数名的字典顺序排序

        foreach ( $pay_params as $key => $val ) {
            if ($key != "sign" && isset ( $val ) && @$val != "") {
                $params_str .= $key . "=" . $val . "&";
            }
        }
        $params_str .= "paySecret=" . $paySecret;

        $signMsg=strtoupper(md5($params_str));
        return $signMsg;
    }



    /**
     * http curl 请求方法
     * @param $url
     * @param null $postFields
     * @return mixed
     * @throws Exception
     */
    function httpCurl($url, $data, $user_agent=null, $conn_timeout=7, $timeout=5) {
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