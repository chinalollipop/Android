<?php
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
function create_sign($array){
	ksort($array); #排列数组 将数组已a-z排序
	$result = '';
	foreach($array as $key=>$v){
		if ($key !== 'notifyurl' && $key !== 'sign'){
			$result  .= $key  . '=' . $v . '&';
		}
	}
	return $result;
}
function my_post($url, $data){
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
//蚂蚁代付查询
function my_query(){
	//获取未处理的蚂蚁代付提款单
	$para = db('remit')->where(['code'=>'myremit','status'=>1])->find();
	if(!empty($para)){
		$list = db('member_cash')->where(['flag' => $para['id'],'status' => 4])->select();
		if(!empty($list)){
			$post_url = 'http://47.244.63.46:8010/api/service/gateway';
			
			$paramers = array();
			$paramers['business'] = 'Query';
			$paramers['business_type'] = 20102;
			//初始化参数
			include APP_PATH . "admin/controller/daifu/des3cbc.php";
			$cpClass = new \des3cbc($para['private_rsa']);
			foreach($list as $k=>$v){
				$paramers['api_sn'] = $v['cashId'];
				$paramers['timestamp'] = time();
				$sign_str = create_sign($paramers);
				$string = $sign_str . 'key=' . $para['md5'];
				//签名
				$paramers['sign'] = strtoupper(md5($string));
				$post_arr = [];
				$params = json_encode_ex($paramers);
				$post_arr['params'] = base64_encode($cpClass->encrypt3DES($params));
				$post_arr['mcode'] = $para['mer_no'];
				$res = my_post($post_url, $post_arr);
				$return = json_decode($res, true);
				$status = isset($return['data']['status'])?trim($return['data']['status']):'';
				if($return['status'] && ($status == '30' || $status == '40' || $status == '50')){
					$amount =  $return['data']['money'];
					if($amount == $v['amount']){
						if($status == '50'){
							$bool = db('member_cash')->where(['cashId' => $v['cashId']])->update(['status' => 1,'info' => '已确定打款，注意查收', 'handleTime' => time()]);//更新
							if ($bool) {
								file_put_contents("success_myremit" . date('ymd') . ".txt", $res .'-'.date('Y-m-d H:i:s'). "\n", FILE_APPEND);
								$bool = db('members')->where('uid=' . $v['uid'])->update(['fcoin' => ['exp', 'fcoin - ' . $v['amount']]]);
								echo '成功';
							}else{
								echo '更新提款单状态失败';
							}
						}else{
							$remit_error = $res;
							if(isset($return['data']['feedback_error'])){
								if(strstr($return['data']['feedback_error'],'|')){
									$art = explode('|',$return['data']['feedback_error']);
									if(isset($art[1])){
										$remit_error = trim($art[1]);
									}
								}else{
									$remit_error = trim($return['data']['feedback_error']);
								}
							}
							db('member_cash')->where(['cashId' => $v['cashId']])->update(['status' => 5,'remit_error' => $remit_error]);//30:执行异常,40:失败结束
						}
					}else{
						echo '订单金额不匹配';
					}
				}else{
					echo $return['msg'];
				}
			}
			exit('ok');
		}else{
			exit('没有蚂蚁代付订单');
		}
	}
}