<?php
    /**
     * MD5 加签
     * @param $params
     * @param $secretKey
     * @return string
     */
    function md5Sign($params, $md5Key, $charset){
        return strtoupper(md5(getSignContent($params, $charset)."&key=". $md5Key));
    }

    /**
     * MD5 验签
     * @param $params
     * @return string
     */
    function md5SignCheck($params){
        if(md5Sign($params) == $params["sign"]){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 签名参数组装排序字符串
     * @param $params
     * @return string
     */
    function getSignContent($params, $targetCharset) {
        ksort($params);
        $params["sign"] = null; //移除sign，不参与加密

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = characet($v, $targetCharset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = "UTF-8";
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    /**
     * 生成随机单号
     * @return string
     */
    function buildOrderNo(){
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }


    /**
     * 生成秒数
     * @return float
     */
    function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * http curl 请求方法
     * @param $url
     * @param null $postFields
     * @return mixed
     * @throws Exception
     */
    function httpCurl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $postCharset = "UTF-8";
        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;

        if (is_array($postFields) && 0 < count($postFields)) {
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) //判断是不是文件上传
                {
                    $postBodyString .= "$k=" . urlencode(characet($v, $postCharset)) . "&";
                    $encodeArray[$k] = characet($v, $postCharset);
                } 
                
                else {
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }

            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }

        if ($postMultipart) {
            $headers = array('content-type: multipart/form-data;charset=' . $postCharset . ';boundary=' . $this->getMillisecond());
        } else {
            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $postCharset);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }
