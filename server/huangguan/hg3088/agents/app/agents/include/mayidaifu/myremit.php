<?php
/**
 * 蚂蚁代付
 * User: crc
 * Date: 2018-11-08
 * Time: 19:55
 */

include_once ("des3cbc.php");
class myremit{
    public function getBank(){
        $bank = array(
            '888888' => '中国民生银行',
            '999999' => '中国工商银行',
            '1'=>'中国农业银行',
            '2'=>'中国银行',
            '3'=>'中国建设银行',
            '4'=>'交通银行',
            '5'=>'中信银行',
            '6'=>'光大银行',
            '7'=>'华夏银行',
            '8'=>'广发银行',
            '9'=>'平安银行',
            '10'=>'招商银行',
            '11'=>'兴业银行',
            '12'=>'上海浦东发展银行',
            '13'=>'北京银行',
            '14'=>'天津银行',
            '15'=>'河北银行',
            '17'=>'邢台银行',
            '19'=>'承德银行',
            '20'=>'沧州银行',
            '21'=>'廊坊银行',
            '22'=>'衡水银行',
            '23'=>'晋商银行',
            '24'=>'晋城银行',
            '25'=>'晋中银行',
            '26'=>'内蒙古银行',
            '27'=>'包商银行',
            '28'=>'乌海银行',
            '29'=>'鄂尔多斯银行',
            '30'=>'大连银行',
            '32'=>'锦州银行',
            '33'=>'葫芦岛银行',
            '34'=>'营口银行',
            '35'=>'阜新银行',
            '36'=>'吉林银行',
            '37'=>'哈尔滨银行',
            '38'=>'龙江银行',
            '39'=>'南京银行',
            '40'=>'江苏银行',
            '41'=>'苏州银行',
            '43'=>'杭州银行',
            '46'=>'温州银行',
            '47'=>'嘉兴银行',
            '48'=>'湖州银行',
            '49'=>'绍兴银行',
            '52'=>'台州银行',
            '55'=>'福建海峡银行',
            '56'=>'厦门银行',
            '57'=>'泉州银行',
            '58'=>'南昌银行',
            '60'=>'赣州银行',
            '61'=>'上饶银行',
            '62'=>'齐鲁银行',
            '63'=>'青岛银行',
            '64'=>'齐商银行',
            '65'=>'枣庄银行',
            '66'=>'东营银行',
            '67'=>'烟台银行',
            '68'=>'潍坊银行',
            '69'=>'济宁银行',
            '71'=>'莱商银行',
            '73'=>'德州银行',
            '74'=>'临商银行',
            '75'=>'日照银行',
            '76'=>'郑州银行',
            '77'=>'中原银行',
            '78'=>'洛阳银行',
            '79'=>'平顶山银行',
            '81'=>'汉口银行',
            '82'=>'湖北银行',
            '83'=>'华融湘江银行',
            '84'=>'长沙银行',
            '85'=>'广州银行',
            '86'=>'珠海华润银行',
            '87'=>'广东华兴银行',
            '88'=>'广东南粤银行',
            '91'=>'柳州银行',
            '97'=>'德阳银行',
            '101'=>'富滇银行',
            '104'=>'西安银行',
            '105'=>'长安银行',
            '106'=>'兰州银行',
            '107'=>'青海银行',
            '108'=>'宁夏银行',
            '110'=>'昆仑银行',
            '123'=>'恒丰银行',
            '126'=>'渤海银行',
            '127'=>'徽商银行',
            '137'=>'深圳前海微众银行',
            '138'=>'上海银行',
            '143'=>'鄞州银行',
            '145'=>'福建省农村信用社',
            '160'=>'邮政储蓄银行',
            '166'=>'厦门国际银行',
        );
        return $bank;
    }
    public function doRemit(&$para){
        //初始化参数
//		include APP_PATH . "admin/controller/daifu/des3cbc.php";
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

//        print_r($paramers);die;
		//获取签名字符串
		$sign_str = $this->create_sign($paramers);
		$string = $sign_str . 'key=' . $para['md5'];
		//签名
        $paramers['sign'] = strtoupper(md5($string));
		$post_arr = [];
		$params = $this->json_encode_ex($paramers);

//		print_r($paramers);die;
		$post_arr['params'] = base64_encode($cpClass->encrypt3DES($params));
		$post_arr['mcode'] = $para['mer_no'];
        $res = $this->wx_post($para['getway'], $post_arr);
        $return = json_decode($res, true);
        return $return;
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