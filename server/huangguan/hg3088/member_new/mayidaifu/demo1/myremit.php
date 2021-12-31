<?php
/**
 * 蚂蚁代付
 * User: crc
 * Date: 2018-11-08
 * Time: 19:55
 */
class myremit{
    public function getBank(){
        $bank = array(
            '888888' => '民生银行',
            '999999' => '中国工商银行',
            '1' => '中国农业银行',
            '2' => '中国银行',
            '3' => '中国建设银行',
            '4' => '中国交通银行',
            '5' => '中信银行',
            '6' => '光大银行',
            '7' => '华夏银行',
            '8' => '广发银行',
            '9' => '深圳发展银行',
            '9' => '平安银行',
            '10' => '招商银行',
            '11' => '兴业银行',
            '12' => '浦发银行',
            '13' => '北京银行',
            '14' => '天津银行',
            '15' => '河北银行',
            '16' => '邯郸市商业银行',
            '39' => '南京银行',
            '43' => '杭州银行',
            '44' => '宁波银行',
            '138' => '上海银行',
            '126' => '渤海银行',
            '161' => '东亚银行',
            '124' => '浙商银行',
            '160' => '中国邮政储蓄银行',
        );
        return $bank;
    }
    public function doRemit(&$para){
        //初始化参数
		include APP_PATH . "admin/controller/daifu/des3cbc.php";
        $cpClass = new \des3cbc($para['private_rsa']);
		
		$paramers = array();
        $paramers['business'] = 'Transfer';
        $paramers['business_type'] = 10101;
        $paramers['api_sn'] = $para['cashId'];
        $paramers['money'] = $para['amount'];
        $paramers['bene_no'] = $para['accNo'];
		if($para['bankCode']=='999999'){
			$bankCode = 0;
		}else if($para['bankCode']=='888888'){
			$bankCode = -1;
		}else{
			$bankCode = intval($para['bankCode']);
		}
        $paramers['bank_id'] = $bankCode;
        $paramers['payee'] = urlencode($para['accName']);
        $paramers['timestamp'] = time();
        $paramers['notify_url'] = $para['callBackUrl'];
		//获取签名字符串
		$sign_str = $this->create_sign($paramers);
		$string = $sign_str . 'key=' . $para['md5'];
		//签名
        $paramers['sign'] = strtoupper(md5($string));
		$post_arr = [];
		$params = $this->json_encode_ex($paramers);
		$post_arr['params'] = base64_encode($cpClass->encrypt3DES($params));
		$post_arr['mcode'] = $para['mer_no'];
        $res = $this->wx_post($para['getway'], $post_arr);
        $return = json_decode($res, true);
        if (!empty($return)) {
            if ($return['status']) {
				$ps = APP_PATH . "admin/controller/daifu/";
				file_put_contents($ps."myremit_succ" . date('ym') . ".txt", $para['cashId'].':'.$params.':'.$res.'-'.date('Y-m-d H:i:s'). "\n", FILE_APPEND);
				db('member_cash')->where(['cashId' => $para['cashId']])->update(['status' => 4, 'flag' => $para['id'],'admin'=>$para['adminuser']]);//修改订单状态 表中flag字段记录代付表中的id
				$log_data = [
					'aid' => $para['admin_id'],
					'username' => $para['adminuser'],
					'type' => 8,
					'time' => time(),
					'uid' => $para['uid']
				];
				db('admin_log')->insert($log_data);//添加管理员操作日志
				exit(json_encode(['code' => 200, 'data' => '', 'msg' => '申请成功']));
            } else
                exit(json_encode(['code' => 201, 'error' => '错误码：'.$return['errorCode'].'    错误信息：'.$return['msg']]));
        } else
            exit(json_encode(['code' => 201, 'error' => '无响应']));
    }
    public function wx_post($url, $data){
        $ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type'=>'application/x-www-form-urlencoded;charset=utf-8'));        
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
    }
    public function create_sign($array){
        ksort($array); #排列数组 将数组已a-z排序
		$result = '';
		foreach($array as $key=>$v){
			if ($key !== 'notifyurl' && $key !== 'sign'){
				$v = trim($v);
				if($v != '0'){
					$result  .= $key  . '=' . $v . '&';
				}
			}
		}
		return $result;
    }
	public function json_encode_ex($value){
		 if (version_compare(PHP_VERSION,'5.4.0','<')){
			$str = json_encode($value);
			$str = preg_replace_callback("#\\\u([0-9a-f]{4})#i","replace_unicode_escape_sequence",$str);
			$str = stripslashes($str);
			return $str;
		}else{
			return json_encode($value,320);
		}
	}
	public function replace_unicode_escape_sequence($match) {
		return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
	}
}