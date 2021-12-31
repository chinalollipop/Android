<?php 

//前后台公用函数

/*
 * 用户资金账变记录
 * 神游
 * $data array 一条或者多条
 * 0用户id|1用户名|2测试/正式|3操作前金额|4操作金额|5操作后金额|6操作类型|7来源|8数据id或订单号|9描述可为空 
 * return bool
 * */
function addAccountRecords($data,$more=false){
	global $dbMasterLink;
	$sql='';
	$timeCur='';
	$size=count($data);
	if( !is_array($data) || $size<1 ){
		return false;
	}
	$sql = "INSERT INTO ".DBPREFIX."web_account_change_records(`userid`,`userName`,`istest`,`currencyBefore`,`money`,`currencyAfter`,`type`, `source`,`addTime`,`orderid`,`description`)VALUES";
	if($more){
		foreach($data as $key=>$val){
			$timeCur=time();
			if($key==$size-1){
				$sql .= "($val[0],'$val[1]','$val[2]','$val[3]','$val[4]','$val[5]','$val[6]','$val[7]',$timeCur,'$val[8]','$val[9]')" ;
			}else{
				$sql .= "($val[0],'$val[1]','$val[2]','$val[3]','$val[4]','$val[5]','$val[6]','$val[7]',$timeCur,'$val[8]','$val[9]')," ;
			}
		}	
	}else{
		$timeCur=time();
		$sql .= "($data[0],'$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]',$timeCur,'$data[8]','$data[9]')" ;
	}
	$result = mysqli_query($dbMasterLink,$sql);
	if($result){
		return true;
	}else{
		return false;
	}
	
}

/**
 * 检查用户名是否符合规定
 *
 * @param STRING $username 要检查的用户名
 * @return TRUE or FALSE
 */
function is_username($username){
    $strlen = strlen($username);
    $preg = "/^[a-zA-Z0-9_][a-zA-Z0-9_]+$/" ;
    if (!preg_match($preg,$username)) {
        return false;
    } elseif (15 < $strlen || $strlen < 5) {
        return false;
    }
    return true;
}
// 邮箱验证
function isEmail($value,$match='/^[\w\d]+[\wd-.]*@[w\d-.]+\.[\w\d]{2,10}$/i'){
    $v = trim($value);
    if(empty($v)){
        return false;
    }
    return preg_match($match,$v);
}

// 真实姓名验证,一个中文长度为 gbk 2 ,utf-83
function isTrueName($value){
    $strlen = strlen($value);
   // $preg = "/^[u4e00-u9fa5·0-9A-z]+$/" ;
    $preg = "/^[\x7f-\xff]+$/" ;
//    $t = mb_convert_encoding($value,"GBK","utf-8");   //若当前网页为gb2312格式，则需要注释掉这一行

    //新疆等少数民族可能有·
    if(strpos($value,'·')){
        //将·去掉，看看剩下的是不是都是中文
        $str = str_replace("·",'',$value);
        if(preg_match($preg, $str)){
            return true;//全是中文
        }else{
            return false;//不全是中文
        }
    }elseif (30 < $strlen || $strlen < 2) {
        return false;
    }else{
        if(preg_match($preg, $value)){
            return true;//全是中文
        }else{
            return false;//不全是中文
        }
    }

}

// 手机号码 验证
function isPhone($value){
    $preg = "/^1[3456789]\d{9}$/ims" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}

// 微信号码 验证
function isWechat($value){
    $preg = "/^[_a-zA-Z0-9]{5,25}+$/isu" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}

// QQ号码 验证
function isQqNumber($value){
    $preg = "/^\d{5,25}$/isu" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}
// 提款密码 验证
function isPayNumber($value){
    $preg = "/^\d{4,6}$/isu" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}
// 银行卡号码 验证 15-20 位
function isBankNumber($value){
    $preg = "/^(\d{15,20})$/isu" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}

// USDT地址 验证数字、26个英文字母或者下划线组成的字符串
function isUsdtAddress($value){
    $preg = "/^\w+$/isu" ;
    if (!preg_match($preg,$value)) {
        return false;
    }
    return true;
}

// 判断日期格式是否正确
function isDate($str,$format="Y-m-d"){
    $unixTime=strtotime($str);
    $checkDate= date($format,$unixTime);
    if($checkDate==$str)
        return 1;
    else
        return 0;
}

// 判断出生日期是否合法
function checkbirthdate($month,$day,$year)
{
    $min_age = 18; // 最低合法年龄
    $max_age = 122; // 最高合法年龄
    if(!checkdate($month,$day,$year))
    {
        return false;
    }

    $now = new DateTime();
    $then_formatted = sprintf("%d-%d-%d",$year,$month,$day);
    $then = DateTime::createFromFormat("Y-n-j|",$then_formatted);
    $age = $now->diff($then);
    if(($age->y < $min_age) || ($age->y > $max_age))
    {
        return false;
    }
    else
    {
        return true;
    }
}

/*
 *  注册验证公用函数
 *  $username 用户名，$intr 推荐人，$pwd 密码，$cfpwd 确认密码，$alias 真实姓名,
 *  $paypwd 支付密码，$phone 手机号码，$wechat 微信 ,$qq QQ, $ad 广告站，
 *  $bankad 银行地址，$bankac 银行账户
 *  */
function publicRegValidate($username,$intr,$pwd,$cfpwd,$alias,$paypwd,$phone,$wechat,$qq,$ad,$ifapi){
    $gojs = 'history.back(-1);' ;
    if($ad){ // 广告站返回方式
        $gojs = 'window.close();' ;
    }
    if($intr && !is_username($intr)){ // 用户名验证
        if($ifapi){
            $status = '400.8';
            $describe = "推荐人不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('推荐人不符合规范!');".$gojs."</script>" );
        }

    }
    if(!is_username($username)){ // 用户名验证
        if($ifapi){
            $status = '400.9';
            $describe = "用户名不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('用户名不符合规范!');".$gojs."</script>" );
        }

    }
    if($pwd !=$cfpwd){
        if($ifapi){
            $status = '400.10';
            $describe = "密码与确认密码不一致！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('密码与确认密码不一致!');".$gojs."</script>" );
        }

    }
    if(strlen($pwd) >15 || strlen($pwd)<6){
        if($ifapi){
            $status = '400.11';
            $describe = "密码不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('密码不符合规范!');".$gojs."</script>" );
        }
    }
    if($alias && !isTrueName($alias)){ // 真实姓名验证
        if($ifapi){
            $status = '400.12';
            $describe = "真实姓名不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('真实姓名不符合规范!');".$gojs."</script>" );
        }
    }
    if($paypwd && !isPayNumber($paypwd)){ // 支付密码验证
        if($ifapi){
            $status = '400.13';
            $describe = "支付密码不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('支付密码不符合规范!');".$gojs."</script>" );
        }
    }
    if($phone && !isPhone($phone)){ // 手机号码验证
        if($ifapi){
            $status = '400.14';
            $describe = "手机号码不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('手机号码不符合规范!');".$gojs."</script>" );
        }
    }
    if($wechat && !isWechat($wechat)){ // 微信号码验证
        if($ifapi){
            $status = '400.15';
            $describe = "微信号码不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('微信号码不符合规范!');".$gojs."</script>" );
        }

    }
    if($qq && !isQqNumber($qq)){ // QQ号码验证
        if($ifapi){
            $status = '400.16';
            $describe = "QQ号码不符合规范！";
            original_phone_request_response($status,$describe,'');
        }else{
            exit("<script>alert('QQ号码不符合规范!');".$gojs."</script>" );
        }

    }
}

/**
 * 检测用户当前浏览器
 * @return boolean 是否ie浏览器
 */
function chk_ie_browser() {
    $userbrowser = $_SERVER['HTTP_USER_AGENT'];
    if ( preg_match( '/MSIE/i', $userbrowser ) ) {
        $usingie = true;
    } else {
        $usingie = false;
    }
    return $usingie;
}

/**
 *  交易注单请求参数,生成图片,
 *  计划任务，在后台服务器，注意线上 .sh文件
 *  图片地址 /agents/images/order_image/日期/userid/agfegyrehgry.jpg  图片以订单号命名，在用户id文件夹下
 *  建立定时任务，清理5天之前的目录以及文件(shell)
 * */
function transferOrderToImage($data) {
    // playSource 1pc旧版,2pc新版,5综合新版(参照正网),3 苹果wap,4安卓wap,13原生苹果,14原生安卓,22综合版
    if($data['playSource'] == 1) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 2) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 3  || $data['playSource'] == 5) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/m_new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/m_orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 4) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/m_new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/m_orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 5) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/m_new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/m_orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 13) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/m_new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/m_orderPage_' .$data['showVoucher'] .'.php';
    } elseif($data['playSource'] == 14) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/m_new_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/m_orderPage_' .$data['showVoucher'] .'.php';
    }elseif($data['playSource'] == 22) {
        $source = ROOT_DIR .'/agents/app/agents/tmp/multiple_orderPage.php';
        $tmpName = ROOT_DIR .'/agents/app/agents/tmp/orderPage_' .$data['showVoucher'] .'.php';
    }

    //复制文件需要确保脚本执行目录权限
    $re = copy($source ,$tmpName);
    @chmod($tmpName, 0644);
    // 返回url 和 html订单数据
    $returnBetdata = getBetSourceData($data);
     //FILE_APPEND   如果文件 filename 已经存在，追加数据而不是覆盖   LOCK_EX      在写入时获得一个独占锁。
    $res = file_put_contents($tmpName, $returnBetdata['html'], FILE_APPEND|LOCK_EX);

    $url = $returnBetdata['url'];

    $order_image_dir = ROOT_DIR .'/agents/images/order_image/';
    $date = '' . date ( 'Ymd' ) . '/';
    $userid_dir = ''. $data['userid'] . '/';
    if(!file_exists($order_image_dir . $date)) {
        @mkdir ( $order_image_dir . $date );
    }
    if($data['userid'] && !file_exists($order_image_dir . $date .$userid_dir)) {
        @mkdir ( $order_image_dir . $date .$userid_dir); // 创建用户目录
    }

    if($data['playSource']){
        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
    }
//    if($data['playSource'] == 1 || $data['playSource'] == 2) {
//        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
//    } elseif($data['playSource'] == 3) {
//        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
//    } elseif($data['playSource'] == 4) {
//        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
//    } elseif($data['playSource'] == 13) {
//        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
//    } elseif($data['playSource'] == 14) {
//        $out = $order_image_dir . $date . $userid_dir . $data['showVoucher'].'.jpg';
//    }
    if ($data['is_zhgg']!=1) { // 非综合过关
        $exec = '/usr/bin/xvfb-run --server-args="-screen 0, 640x480x8" /usr/bin/CutyCapt --url=' . $url . ' --out=' . $out;
        //$exec = '/usr/bin/xvfb-run /usr/bin/CutyCapt --url='.$url.' --out='.$out;
    }else{ // 综合过关图片大小
        $height = 202 + 73 * $data['teamcount'];
        $exec = '/usr/bin/xvfb-run --server-args="-screen 0, 640x'.$height.'x24" /usr/bin/CutyCapt --url=' . $url . ' --out=' . $out;
    }

    @error_log(date('Y-m-d H:i:s').': '.$exec.PHP_EOL,  3,  '/tmp/orderimage.log');
    exec($exec.' 2>&1', $output, $return);

    //@error_log('return:'.serialize($return).PHP_EOL,  3,  '/tmp/aaa.log');

//    if($return) {// 如果成功，清掉当前订单临时文件
        unlink($tmpName);
//    }
    return true;
}

/**
 * 根据投注来源返回临时url ， 订单的字符
 * @param $source_id  1pc旧版,2pc新版,5综合新版(参照正网),3 苹果wap,4安卓wap,13原生苹果,14原生安卓,22综合版
 * @return  Array
 */
function getBetSourceData($data) {
    switch ($data['playSource']){
        case 1:
        case 22: // 综合版
            // 转图片的文本
            $returnBetdata['html'] = '<span><h1>'.$data['caption'].'</h1></span>';
            $returnBetdata['html'] .='<div id="info">
                    <div class="fin_title">
                      <p class="fin_acc">成功提交注单！</p>
                      <p class="p-underline">'.$data['Order_Bet_success'].$data['showVoucher'].'</p>
                      <p class="error">危险球 - 待确认</p>
                    </div>';
            if($data['betplace']) { // 综合过关 要三注 数据不同
                $returnBetdata['html'] .= $data['betplace'];
            } else {
                $returnBetdata['html'] .= '<p class="team">
                    '.$data['s_sleague'].'&nbsp;'.$data['btype'].'&nbsp;
                    '.$data['M_Date'].'<BR>
                    '.$data['s_mb_team'].'&nbsp;&nbsp;<font color=#cc0000>'.$data['Sign'].'</font>  '.$data['s_tg_team'].'<br><em>'.$data['s_m_place'].'</em>&nbsp;@&nbsp;<em><strong>'.$data['w_m_rate'].'</strong></em></p>';
            }
            $returnBetdata['html'] .='         <p class="deal-money">'.$data['Order_Bet_Amount'].''.$data['gold'].'</p></div>';
            $returnBetdata['html'] .= '<p class="foot">
                  <input type="button" name="FINISH" value=" '.$data['Order_Quit'] .' " onClick="parent.close_bet();" class="no">
                  <input type="button" name="PRINT" value=" '.$data['Order_Print'] .' " onClick="window.print()" class="yes">
                </p>
              </div>
            </body>
            </html>';
            $web_url = HTTPS_HEAD ."://admin." . MAIN_URL;  //后台旧版
            //$web_url = HTTPS_HEAD ."://admin." . 'xhg518.cn';
            $returnBetdata['url'] =  $web_url .'/app/agents/tmp/orderPage_'.$data['showVoucher'] .'.php';
            break;
        case 2:
            // 转图片的文本
            $returnBetdata['html'] = '<span><h1>'.$data['caption'].'</h1></span>';
            $returnBetdata['html'] .='<div id="info">
                    <div class="fin_title">
                      <p class="fin_acc">成功提交注单！</p>
                      <p class="p-underline">'.$data['Order_Bet_success'].$data['showVoucher'].'</p>
                      <p class="error">危险球 - 待确认</p>
                    </div>';
            if($data['betplace']) { // 综合过关 要三注 数据不同
                $returnBetdata['html'] .= '<p class="team">'.$data['betplace'].'</p>';
            } else {
                $returnBetdata['html'] .= '<p class="team">
                    '.$data['s_sleague'].'&nbsp;'.$data['btype'].'&nbsp;
                    '.$data['M_Date'].'<BR>
                    '.$data['s_mb_team'].'&nbsp;&nbsp;<font color=#cc0000>'.$data['Sign'].'</font>  '.$data['s_tg_team'].'<br><em>'.$data['s_m_place'].'</em>&nbsp;@&nbsp;<em><strong>'.$data['w_m_rate'].'</strong></em></p>';
            }
            $returnBetdata['html'] .='         <p class="deal-money">'.$data['Order_Bet_Amount'].''.$data['gold'].'</p></div>';
            $returnBetdata['html'] .= '<p class="foot">
                  <input type="button" name="FINISH" value=" '.$data['Order_Quit'] .' " onClick="parent.close_bet();" class="no">
                  <input type="button" name="PRINT" value=" '.$data['Order_Print'] .' " onClick="window.print()" class="yes">
                </p>
              </div>
            </body>
            </html>';
            $web_url = HTTPS_HEAD ."://admin." . MAIN_URL;  //后台旧版
            //$web_url = HTTPS_HEAD ."://admin." . 'xhg518.cn';
            $returnBetdata['url'] =  $web_url .'/app/agents/tmp/orderPage_'.$data['showVoucher'] .'.php';
            break;
        case 3:
        case 4:
        case 5: // 综合版正网下注页
        case 13:
        case 14:

            if ($data['is_zhgg']!=1){ // 非综合过关注单截图

                $returnBetdata['html'] = '
                <div class="bet-sure-content">
                    <div class="order_mem_data">
                        <div class="bet-title bet_caption">'.$data['caption'].'</div>
                        <div class="bet-title">交易成功</div>
                        <div class="bet-title-bottom"> 当前余额：<span class="user_money red_color">'.$data['havemoney'].'</span> 元</div>
                    </div>
                    <ul class="hisInfo">
                        <li> 单号：<span class="bet_order_num">'.$data['showVoucher'].'</span></li>
                        <li class="finish_bet_league">'.$data['s_sleague'].'</li>
                        <li class="finish_bet_team"><span class="ratio_red">'. $data['inball'] . ($data['inball']?'&nbsp;':'') .'</span>'.$data['s_mb_team'].' <font class="ratio_red">'.$data['Sign'].'</font> '.$data['s_tg_team'].'</li>
                        <li class="finish_bet_content"><font class="ratio_red">'.$data['s_m_place'].' <span class="bet_type_color" style="color: #666666">'.$data['btype'].'</span>  @ '.$data['w_m_rate'].'</font></li>
                        <li ><span class="finish_bet_mon">'.$data['gold'].'</span> 元</li>
                        <li >可赢：<span class="finish_bet_win">'.$data['gwin'].'</span> </li>
                    </ul>
                    <div class="finish_bet_btn greenBtn">确定</div>
                </div>
              </body>
              </html>';

            }else{ // 综合过关注单截图

                $s_leagues = explode(',',$data['s_league']);
                $s_m_places = explode(',',$data['s_m_place']);
                $s_mb_teams = explode(',',$data['s_mb_team']);
                $s_tg_teams = explode(',',$data['s_tg_team']);
                $signs = explode(',',$data['sign']);
                $w_m_rates = explode(',',$data['w_m_rate']);
                $btype = explode(',',$data['btype']);

                $returnBetdata['html'] = '<div class="bet-sure-content p3_bet-sure-content">';
                $returnBetdata['html'] .= '<div class="order_mem_data">
                        <div class="bet-title bet_caption">'.$data['caption'].'</div>
                        <div class="bet-title">交易成功</div>
                        <div class="bet-title-bottom"> 当前余额：<span class="user_money red_color">'.$data['havemoney'].'</span> 元</div>
                    </div>

                    <ul class="hisInfo">
                        <li> 单号：<span class="bet_order_num">'.$data['showVoucher'].'</span></li>
                        <ul class="bet_order_allcontent">';

                foreach ($s_leagues as $k => $v){
                    $returnBetdata['html'] .= '
                            <ul class="content_ul_li">
                                <li class="finish_bet_league">'.$s_leagues[$k].'</li>
                                <li class="finish_bet_team">'.$s_mb_teams[$k].' <font class="ratio_red">'.$signs[$k].'</font> '.$s_tg_teams[$k].'</li>
                                <li class="finish_bet_content"><font class="ratio_red">'.$s_m_places[$k].' <span class="bet_type_color" style="color: #666666">'.$btype[$k].'</span>  @ '.$w_m_rates[$k].'</font></li>
                            </ul>';
                }

                $returnBetdata['html'] .= '</ul>
                        <li ><span class="finish_bet_mon">'.$data['gold'].'</span> 元</li>
                        <li >可赢：<span class="finish_bet_win">'.$data['gwin'].'</span> </li>
                    </ul>
                    <div class="finish_bet_btn greenBtn">确定</div>

                </div>
              </body>
              </html>';

            }

            $web_url = HTTPS_HEAD ."://admin." . MAIN_URL;  //后台旧版
            $returnBetdata['url'] =  $web_url .'/app/agents/tmp/m_orderPage_'.$data['showVoucher'] .'.php';
            break;
        default:
            $web_url = HTTPS_HEAD ."://admin." . MAIN_URL;  //PC旧版
            $returnBetdata['url'] =  $web_url .'/app/agents/tmp/orderPage_'.$data['showVoucher'] .'.php';
            break;
    }

    return $returnBetdata;
}

/**
 * 判断文件是否存在，支持本地及远程文件
 * @param  String  $file 文件路径
 * @return Boolean
 */
function check_remote_orderImg_file_exists($url) {
    $curl = curl_init($url); // 不取回数据
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // 发送请求
    $result = curl_exec($curl);
    $found = false; // 如果请求没有发送失败
    if ($result !== false) {
        /** 再检查http响应码是否为200 */
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $found = true;
        }
    }
    curl_close($curl);

    return $found;
}


/**
  * @author:lincoin
  * @date: 2018/6/26
  * @description:手机原生app请求接口时，响应数据的公用方法
  */
function original_phone_request_response($status,$describe,$aData = array()){

//    if ($aData['perpage'] > 0){ // 数据格式，转为list（存款记录、取款记录）
//        $out = array_values($aData['rows']);
//        $aData['rows'] = $out;
//    }elseif(is_array(current($aData))){ // 二维数组，转为list（盘口列表、玩法列表）
//        $aData = array_values($aData);
//    }

//    $aData=array_to_object($aData);

    $response_data = array();
    $response_data['status'] = $status;
    $response_data['describe'] = $describe;
    $response_data['timestamp'] = date("YmdHis");
    $response_data['data'] = $aData;

    $sign_fields = array(
        'status',
        'describe',
        'timestamp',
        'data'
    );
//    $sign = sign_mac($sign_fields, $response_data, APP_KEY);
    $response_data['sign']='';

    exit( str_replace("\\/", "/", json_encode(  $response_data, JSON_UNESCAPED_UNICODE)) );
//    exit( json_encode(  $response_data) );

}

/*
 * 空数组转为字符串
 */
function emptyArrToString($arr){

    foreach ($arr as $k => $v){

        if (empty($v)){
            $arr[$k]='';
        }

    }
    return $arr;

}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

/**
 * 二维数组根据某个相同的值归类
 * @param array $arr 要归类的数组
 * @param string $key 要归为键的字段
 * @return array 归类后的数组
 */
function group_same_key($arr,$key){
    $new_arr = array();
    foreach($arr as $k=>$v ){
        $new_arr[$v[$key]][] = $v;
    }
    return $new_arr;
}

/**
 * 二维数组根据某个字段排序
 * @param array $array 要排序的数组
 * @param string $keys   要排序的键字段
 * @param string $sort  排序类型  SORT_ASC     SORT_DESC
 * @return array 排序后的数组
 */
function array_sort($arr,$key,$type='asc'){
    $keysvalue = $new_array = array();
    // 获取key
    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$key];
    }
    // key排序
    if($type == 'asc'){
        asort($keysvalue);
    }else{
        arsort($keysvalue);
    }
    reset($keysvalue);

    // 根据key排序整理正确二维数组顺序
    foreach ($keysvalue as $k=>$v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * 取出汉字首字母
 */
function _getFirstCharter($str){
    if(empty($str)){
        return '';
    }
    $fchar=ord($str{0});
    if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
    $s1=iconv('UTF-8','gb2312',$str);
    $s2=iconv('gb2312','UTF-8',$s1);
    $s=$s2==$str?$s1:$str;
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319&&$asc<=-20284) return 'A';
    if($asc>=-20283&&$asc<=-19776) return 'B';
    if($asc>=-19775&&$asc<=-19219) return 'C';
    if($asc>=-19218&&$asc<=-18711) return 'D';
    if($asc>=-18710&&$asc<=-18527) return 'E';
    if($asc>=-18526&&$asc<=-18240) return 'F';
    if($asc>=-18239&&$asc<=-17923) return 'G';
    if($asc>=-17922&&$asc<=-17418) return 'H';
    if($asc>=-17417&&$asc<=-16475) return 'J';
    if($asc>=-16474&&$asc<=-16213) return 'K';
    if($asc>=-16212&&$asc<=-15641) return 'L';
    if($asc>=-15640&&$asc<=-15166) return 'M';
    if($asc>=-15165&&$asc<=-14923) return 'N';
    if($asc>=-14922&&$asc<=-14915) return 'O';
    if($asc>=-14914&&$asc<=-14631) return 'P';
    if($asc>=-14630&&$asc<=-14150) return 'Q';
    if($asc>=-14149&&$asc<=-14091) return 'R';
    if($asc>=-14090&&$asc<=-13319) return 'S';
    if($asc>=-13318&&$asc<=-12839) return 'T';
    if($asc>=-12838&&$asc<=-12557) return 'W';
    if($asc>=-12556&&$asc<=-11848) return 'X';
    if($asc>=-11847&&$asc<=-11056) return 'Y';
    if($asc>=-11055&&$asc<=-10247) return 'Z';
    return null;
}

//常用简体
$sd="皑蔼碍爱翱袄奥坝罢摆败颁办绊帮绑镑谤剥饱宝报鲍辈贝钡狈备惫绷笔毕毙币闭边编贬变辩辫标鳖别瘪濒滨宾摈饼并拨钵铂驳卜补财参蚕残惭惨灿苍舱仓沧厕侧册测层诧搀掺蝉馋谗缠铲产阐颤场尝长偿肠厂畅钞车彻尘沈陈衬撑称惩诚骋痴迟驰耻齿炽冲虫宠畴踌筹绸丑橱厨锄雏础储触处传疮闯创锤纯绰辞词赐聪葱囱从丛凑蹿窜错达带贷担单郸掸胆惮诞弹当挡党荡档捣岛祷导盗灯邓敌涤递缔颠点垫电淀钓调迭谍叠钉顶锭订丢东动栋冻斗犊独读赌镀锻断缎兑队对吨顿钝夺堕鹅额讹恶饿儿尔饵贰发罚阀珐矾钒烦范贩饭访纺飞诽废费纷坟奋愤粪丰枫锋风疯冯缝讽凤肤辐抚辅赋复负讣妇缚该钙盖干赶秆赣冈刚钢纲岗皋镐搁鸽阁铬个给龚宫巩贡钩沟构购够蛊顾剐关观馆惯贯广规硅归龟闺轨诡柜贵刽辊滚锅国过骇韩汉号阂鹤贺横轰鸿红后壶护沪户哗华画划话怀坏欢环还缓换唤痪焕涣黄谎挥辉毁贿秽会烩汇讳诲绘荤浑伙获货祸击机积饥讥鸡绩缉极辑级挤几蓟剂济计记际继纪夹荚颊贾钾价驾歼监坚笺间艰缄茧检碱硷拣捡简俭减荐槛鉴践贱见键舰剑饯渐溅涧将浆蒋桨奖讲酱胶浇骄娇搅铰矫侥脚饺缴绞轿较秸阶节茎鲸惊经颈静镜径痉竞净纠厩旧驹举据锯惧剧鹃绢杰洁结诫届紧锦仅谨进晋烬尽劲荆觉决诀绝钧军骏开凯颗壳课垦恳抠库裤夸块侩宽矿旷况亏岿窥馈溃扩阔蜡腊莱来赖蓝栏拦篮阑兰澜谰揽览懒缆烂滥捞劳涝乐镭垒类泪篱离里鲤礼丽厉励砾历沥隶俩联莲连镰怜涟帘敛脸链恋炼练粮凉两辆谅疗辽镣猎临邻鳞凛赁龄铃凌灵岭领馏刘龙聋咙笼垄拢陇楼娄搂篓芦卢颅庐炉掳卤虏鲁赂禄录陆驴吕铝侣屡缕虑滤绿峦挛孪滦乱抡轮伦仑沦纶论萝罗逻锣箩骡骆络妈玛码蚂马骂吗买麦卖迈脉瞒馒蛮满谩猫锚铆贸么霉没镁门闷们锰梦谜弥觅幂绵缅庙灭悯闽鸣铭谬谋亩钠纳难挠脑恼闹馁内拟腻撵捻酿鸟聂啮镊镍柠狞宁拧泞钮纽脓浓农疟诺欧鸥殴呕沤盘庞赔喷鹏骗飘频贫苹凭评泼颇扑铺朴谱栖凄脐齐骑岂启气弃讫牵扦钎铅迁签谦钱钳潜浅谴堑枪呛墙蔷强抢锹桥乔侨翘窍窃钦亲寝轻氢倾顷请庆琼穷趋区躯驱龋颧权劝却鹊确让饶扰绕热韧认纫荣绒软锐闰润洒萨鳃赛叁伞丧骚扫涩杀纱筛晒删闪陕赡缮伤赏烧绍赊摄慑设绅审婶肾渗声绳胜圣师狮湿诗尸时蚀实识驶势适释饰视试寿兽枢输书赎属术树竖数帅双谁税顺说硕烁丝饲耸怂颂讼诵擞苏诉肃虽随绥岁孙损笋缩琐锁獭挞抬态摊贪瘫滩坛谭谈叹汤烫涛绦讨腾誊锑题体屉条贴铁厅听烃铜统头秃图涂团颓蜕脱鸵驮驼椭洼袜弯湾顽万网韦违围为潍维苇伟伪纬谓卫温闻纹稳问瓮挝蜗涡窝卧呜钨乌污诬无芜吴坞雾务误锡牺袭习铣戏细虾辖峡侠狭厦吓锨鲜纤咸贤衔闲显险现献县馅羡宪线厢镶乡详响项萧嚣销晓啸蝎协挟携胁谐写泻谢锌衅兴汹锈绣虚嘘须许叙绪续轩悬选癣绚学勋询寻驯训讯逊压鸦鸭哑亚讶阉烟盐严颜阎艳厌砚彦谚验鸯杨扬疡阳痒养样瑶摇尧遥窑谣药爷页业叶医铱颐遗仪彝蚁艺亿忆义诣议谊译异绎荫阴银饮隐樱婴鹰应缨莹萤营荧蝇赢颖哟拥佣痈踊咏涌优忧邮铀犹游诱舆鱼渔娱与屿语吁御狱誉预驭鸳渊辕园员圆缘远愿约跃钥岳粤悦阅云郧匀陨运蕴酝晕韵杂灾载攒暂赞赃脏凿枣灶责择则泽贼赠扎札轧铡闸栅诈斋债毡盏斩辗崭栈战绽张涨帐账胀赵蛰辙锗这贞针侦诊镇阵挣睁狰争帧郑证织职执纸挚掷帜质滞钟终种肿众诌轴皱昼骤猪诸诛烛瞩嘱贮铸筑驻专砖转赚桩庄装妆壮状锥赘坠缀谆着浊兹资渍踪综总纵邹诅组钻";

//常用繁体
$td="皚藹礙愛翺襖奧壩罷擺敗頒辦絆幫綁鎊謗剝飽寶報鮑輩貝鋇狽備憊繃筆畢斃幣閉邊編貶變辯辮標鼈別癟瀕濱賓擯餅並撥缽鉑駁蔔補財參蠶殘慚慘燦蒼艙倉滄廁側冊測層詫攙摻蟬饞讒纏鏟産闡顫場嘗長償腸廠暢鈔車徹塵沈陳襯撐稱懲誠騁癡遲馳恥齒熾沖蟲寵疇躊籌綢醜櫥廚鋤雛礎儲觸處傳瘡闖創錘純綽辭詞賜聰蔥囪從叢湊躥竄錯達帶貸擔單鄲撣膽憚誕彈當擋黨蕩檔搗島禱導盜燈鄧敵滌遞締顛點墊電澱釣調叠諜疊釘頂錠訂丟東動棟凍鬥犢獨讀賭鍍鍛斷緞兌隊對噸頓鈍奪墮鵝額訛惡餓兒爾餌貳發罰閥琺礬釩煩範販飯訪紡飛誹廢費紛墳奮憤糞豐楓鋒風瘋馮縫諷鳳膚輻撫輔賦複負訃婦縛該鈣蓋幹趕稈贛岡剛鋼綱崗臯鎬擱鴿閣鉻個給龔宮鞏貢鈎溝構購夠蠱顧剮關觀館慣貫廣規矽歸龜閨軌詭櫃貴劊輥滾鍋國過駭韓漢號閡鶴賀橫轟鴻紅後壺護滬戶嘩華畫劃話懷壞歡環還緩換喚瘓煥渙黃謊揮輝毀賄穢會燴彙諱誨繪葷渾夥獲貨禍擊機積饑譏雞績緝極輯級擠幾薊劑濟計記際繼紀夾莢頰賈鉀價駕殲監堅箋間艱緘繭檢堿鹼揀撿簡儉減薦檻鑒踐賤見鍵艦劍餞漸濺澗將漿蔣槳獎講醬膠澆驕嬌攪鉸矯僥腳餃繳絞轎較稭階節莖鯨驚經頸靜鏡徑痙競淨糾廄舊駒舉據鋸懼劇鵑絹傑潔結誡屆緊錦僅謹進晉燼盡勁荊覺決訣絕鈞軍駿開凱顆殼課墾懇摳庫褲誇塊儈寬礦曠況虧巋窺饋潰擴闊蠟臘萊來賴藍欄攔籃闌蘭瀾讕攬覽懶纜爛濫撈勞澇樂鐳壘類淚籬離裏鯉禮麗厲勵礫曆瀝隸倆聯蓮連鐮憐漣簾斂臉鏈戀煉練糧涼兩輛諒療遼鐐獵臨鄰鱗凜賃齡鈴淩靈嶺領餾劉龍聾嚨籠壟攏隴樓婁摟簍蘆盧顱廬爐擄鹵虜魯賂祿錄陸驢呂鋁侶屢縷慮濾綠巒攣孿灤亂掄輪倫侖淪綸論蘿羅邏鑼籮騾駱絡媽瑪碼螞馬罵嗎買麥賣邁脈瞞饅蠻滿謾貓錨鉚貿麽黴沒鎂門悶們錳夢謎彌覓冪綿緬廟滅憫閩鳴銘謬謀畝鈉納難撓腦惱鬧餒內擬膩攆撚釀鳥聶齧鑷鎳檸獰甯擰濘鈕紐膿濃農瘧諾歐鷗毆嘔漚盤龐賠噴鵬騙飄頻貧蘋憑評潑頗撲鋪樸譜棲淒臍齊騎豈啓氣棄訖牽扡釺鉛遷簽謙錢鉗潛淺譴塹槍嗆牆薔強搶鍬橋喬僑翹竅竊欽親寢輕氫傾頃請慶瓊窮趨區軀驅齲顴權勸卻鵲確讓饒擾繞熱韌認紉榮絨軟銳閏潤灑薩鰓賽三傘喪騷掃澀殺紗篩曬刪閃陝贍繕傷賞燒紹賒攝懾設紳審嬸腎滲聲繩勝聖師獅濕詩屍時蝕實識駛勢適釋飾視試壽獸樞輸書贖屬術樹豎數帥雙誰稅順說碩爍絲飼聳慫頌訟誦擻蘇訴肅雖隨綏歲孫損筍縮瑣鎖獺撻擡態攤貪癱灘壇譚談歎湯燙濤縧討騰謄銻題體屜條貼鐵廳聽烴銅統頭禿圖塗團頹蛻脫鴕馱駝橢窪襪彎灣頑萬網韋違圍爲濰維葦偉僞緯謂衛溫聞紋穩問甕撾蝸渦窩臥嗚鎢烏汙誣無蕪吳塢霧務誤錫犧襲習銑戲細蝦轄峽俠狹廈嚇鍁鮮纖鹹賢銜閑顯險現獻縣餡羨憲線廂鑲鄉詳響項蕭囂銷曉嘯蠍協挾攜脅諧寫瀉謝鋅釁興洶鏽繡虛噓須許敘緒續軒懸選癬絢學勳詢尋馴訓訊遜壓鴉鴨啞亞訝閹煙鹽嚴顔閻豔厭硯彥諺驗鴦楊揚瘍陽癢養樣瑤搖堯遙窯謠藥爺頁業葉醫銥頤遺儀彜蟻藝億憶義詣議誼譯異繹蔭陰銀飲隱櫻嬰鷹應纓瑩螢營熒蠅贏穎喲擁傭癰踴詠湧優憂郵鈾猶遊誘輿魚漁娛與嶼語籲禦獄譽預馭鴛淵轅園員圓緣遠願約躍鑰嶽粵悅閱雲鄖勻隕運蘊醞暈韻雜災載攢暫贊贓髒鑿棗竈責擇則澤賊贈紮劄軋鍘閘柵詐齋債氈盞斬輾嶄棧戰綻張漲帳賬脹趙蟄轍鍺這貞針偵診鎮陣掙睜猙爭幀鄭證織職執紙摯擲幟質滯鍾終種腫衆謅軸皺晝驟豬諸誅燭矚囑貯鑄築駐專磚轉賺樁莊裝妝壯狀錐贅墜綴諄著濁茲資漬蹤綜總縱鄒詛組鑽";

/**
 * 繁体转化简体
 *
 * @param string $sContent 要转化的字符串
 * @return string 转化之后得到的字符串
 */
function tradition2simple($sContent)
{
    global $td, $sd;
    $simpleCN = '';
    $iContent=mb_strlen($sContent,'UTF-8');

    for($i=0;$i<$iContent;$i++){
        $str=mb_substr($sContent,$i,1,'UTF-8');
        $match=mb_strpos($td,$str,null,'UTF-8');
        $simpleCN.=($match!==false )?mb_substr($sd,$match,1,'UTF-8'):$str;
    }

    return $simpleCN;
}

/**
 * 简体转化繁体
 *
 * @param string $sContent 要转化的字符串
 * @return string 转化之后得到的字符串
 */
function simple2tradition($sContent)
{
    global $td, $sd;
    $traditionalCN = '';
    $iContent=mb_strlen($sContent,'UTF-8');

    for($i=0;$i<$iContent;$i++){
        $str=mb_substr($sContent,$i,1,'UTF-8');
        $match=mb_strpos($sd,$str,null,'UTF-8');
        $traditionalCN.=($match!==false )?mb_substr($td,$match,1,'UTF-8'):$str;
    }

    return $traditionalCN;
}

/* 构建签名原文 */
function sign_src($sign_fields, $map, $md5_key)
{
    // 排序-字段顺序
    sort($sign_fields);
    $sign_src = "";
    foreach ($sign_fields as $field) {
        $sign_src .= $field . "=" . $map[$field] . "&";
    }
    $sign_src .= "KEY=" . $md5_key;

    return $sign_src;
}

/**
 * 计算md5签名  返回的是小写的
 */
function sign_mac($sign_fields, $map, $md5_key)
{
    $sign_src = sign_src($sign_fields, $map, $md5_key);
    return md5($sign_src);
}

/**
 * 判断访问协议
 * @return bool
 */
function bHttps()
{
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return true;
    } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

// 得知网站来源
function knowFromSite($form){
    $form_txt ='' ;
    switch ($form){
        case '1': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
            $form_txt = '朋友推荐';
            break ;
        case '2': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
            $form_txt = '比分网';
            break ;
        case '3': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
            $form_txt = '网络广告';
            break ;
        case '4': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
            $form_txt = '论坛';
            break ;
    }
    return $form_txt ;
}

/*
 *  香港六合彩波色
 *  红波：01,02,07,08,12,13,18,19,23,24,29,30,34,35,40,45,46
 *  蓝波：03,04,09,10,14,15,20,25,26,31,36,37,41,42,47,48
 *  绿波：05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49
 * */

// 六合彩色波展示
function setSixLotteryLogo($num){
    $ball_class = '' ;
    switch ($num){
        case '1':
        case '2':
        case '7':
        case '8':
        case '12':
        case '13':
        case '18':
        case '19':
        case '23':
        case '24':
        case '29':
        case '30':
        case '34':
        case '35':
        case '40':
        case '45':
        case '46':
            $ball_class = 'ball_hk6_red' ;
            break;
        case '3':
        case '4':
        case '9':
        case '10':
        case '14':
        case '15':
        case '20':
        case '25':
        case '26':
        case '31':
        case '36':
        case '37':
        case '41':
        case '42':
        case '47':
        case '48':
            $ball_class = 'ball_hk6_blue' ;
            break;
        case '5':
        case '6':
        case '11':
        case '16':
        case '17':
        case '21':
        case '22':
        case '27':
        case '28':
        case '32':
        case '33':
        case '38':
        case '39':
        case '43':
        case '44':
        case '49':
            $ball_class = 'ball_hk6_green' ;
            break;
    }
    return $ball_class ;
}

// 获取全部会员消息，滚动公告 2020/03/10 调整放到redis
function getScrollMsg($type){
    global $dbMasterLink;
    $redisObj = new Ciredis();
    if(!$type){$type='get';} // 默认获取公告
    if($type=='get'){ // 获取消息
        $msg_member = $redisObj->getSimpleOne('member_message');
        return $msg_member ;
    }if($type=='upd'){ // 更新redis
        $sql="select Message from ".DBPREFIX."web_marquee_data where  Level='MEM' order by ID desc limit 0,1";
        $result = mysqli_query($dbMasterLink,$sql); // 防止从库同步不及时
        $row = mysqli_fetch_assoc($result);
        $msg_member=$row['Message']; // 简体
        $redisObj->setOne('member_message',$msg_member);
    }
}

/*
 *  获取与设置刷水时间
 *  暂时只有足球和篮球
 * */

function refurbishTime($type){
    global $dbMasterLink;
    $redisObj = new Ciredis();
    if(!$type){$type='get';} // 默认获取
    if($type=='get'){ // 获取
        $returnData = $redisObj->getSimpleOne('refurbish_time_datas');
        $datajson = json_decode($returnData,true) ;
        if(!$datajson){ // 若是不存在
            refurbishTime('upd');
        }
        return $datajson ;
    }if($type=='upd'){ // 更新redis
        $sql = "select Uid,datasite,udp_ft_tw,udp_ft_r,udp_ft_v,udp_ft_re,udp_ft_pd,udp_ft_t,udp_ft_f,udp_ft_p,udp_ft_pr,udp_fu_f,udp_fu_v,udp_fu_pd,udp_fu_pr,udp_fu_t,udp_fu_r,udp_bk_tw,udp_bk_r,udp_bk_rq,udp_bk_re,udp_bk_pr,udp_fs_fs from ".DBPREFIX."web_system_data where ID=1";
        $result = mysqli_query($dbMasterLink,$sql); // 防止从库同步不及时
        while ($row = mysqli_fetch_assoc($result)){
            $resData[] = $row;
        }
        // var_dump($resData);
        $redisObj->setOne('refurbish_time_datas',json_encode($resData)) ;
    }

}


function passwordEncryption($password,$username){
	$passwordEncryptionSTR = md5(md5(md5($password).sha1(LOGIN_ENCRYPTIONCODE)).strtolower(trim($username)));
	return $passwordEncryptionSTR;
}

// 随机生成数字字母组合，$len 长度
function getRandomString($len, $chars=null){
    if (is_null($chars)) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}

// 获取会员弹窗公告，存取款公告,$Mstype 0 系统短信 ,1 代表存款公告,2 代表取款公告
function getMemberMessage($username,$mstype){
    // 会员弹窗信息
    global  $dbLink ;
    $todaydate=date('Y-m-d');
    $memname = $username ;
    $addtime = '';
    $msql = "select Message,Time from ".DBPREFIX."web_message_data where UserName='$memname' AND MsType='$mstype' AND BeginTime<='$todaydate' AND EndTime>='$todaydate' ORDER BY Time DESC LIMIT 1";
    $mresult = mysqli_query($dbLink,$msql);
    $mrow = mysqli_fetch_assoc($mresult);
    $mcou=mysqli_num_rows($mresult);
    if($mcou>0){ // 单个会员有短信
        $mem_message=$mrow['Message'];
        $addtime=$mrow['Time'];
    }else{ // 单个会员没有短信，读取全部的
        $allsql = "select Message,Time from ".DBPREFIX."web_message_data where type='1' AND MsType='$mstype' AND BeginTime<='$todaydate' AND EndTime>='$todaydate' ORDER BY Time DESC LIMIT 1";
        $allresult = mysqli_query($dbLink,$allsql);
        $allrow = mysqli_fetch_assoc($allresult);
        $allcou=mysqli_num_rows($allresult);
        if($allcou>0){ // 有全部会员短信
            $mem_message=$allrow['Message'];
            $mcou = $allcou ;
            $addtime=$allrow['Time'];
        }
    }
    return array('mem_message'=>$mem_message,'mcou'=>$mcou,'addtime'=>$addtime);
}

// 后台帐号只能单点登录
function checkAdminLogin(){
    /*判断IP是否在白名单，代理不需要*/
    if(CHECK_IP_SWITCH && $_SESSION['admin_level'] !== 'D') {
        if(!checkip()) {
            echo "<script>alert('后台单点登录失败!!\\n未被授权访问的IP!!');top.location.href='/';</script>";
            exit;
        }
    }

    // 获取当前用户Oid
    $redisObj = new Ciredis();
    $returnOid = $redisObj->getSimpleOne('loginadmin_'.$_SESSION['ID']);
    if($_SESSION['Oid'] != $returnOid) { // 不能同时在线
        // 当前登录用户oid和redis中Oid不一致
        session_destroy();
        echo "<script>alert('您的帐号已在其他地方登录!');top.location.href='/';</script>";
        exit;
    }
}

/**
 * 计算小数点后位数
 * @param $num
 * @return int
 */
function _getFloatLength($num) {
    $count = 0;
    $temp = explode ( '.', $num );
    if (sizeof ( $temp ) > 1) {
        $decimal = end ( $temp );
        $count = strlen ( $decimal );
    }
    return $count;
}

/**
 * 小数保留后2位，只舍不入
 */
function round_num($num){
    if($len = strpos($num,'.')){
        $dian_num = substr($num,$len+1,$len+3);//获取小数点后面的数字
        if(strlen($dian_num) >= 2){//判断小数点后面的数字长度是否大于2
            $new_num = substr($num,0,$len+3);
        }else{//补0
            $new_num = $num.'0';
        }
    }else{//补.00
        $new_num = $num.'.00';
    }
    return $new_num;
}

/*
 * 获取视屏直接账号
 * 返回账号数组
 * 每个IP仅有一条数据
 * */
function getOfficialVideoAccount(){
    
	global $dbMasterLink;
	$accoutArr =$uniqueIpArray= array();
	$sql = "select ID,Datasite,Name,Uid,liveID from ".DBPREFIX."web_official_account_expand where status=0";
	$result = mysqli_query($dbMasterLink,$sql);
	while($dataCur = mysqli_fetch_assoc($result)){
		$accoutArr[$dataCur['Datasite']][] = $dataCur;
	}
	foreach($accoutArr as $key=>$val){
		$randkey=rand(0,count($val)-1);
		$uniqueIpArray[]=$val[$randkey];
	}
	return $uniqueIpArray;

}

/*
 * 关闭用户投注，篮球开关判断
 * $type FT BK
 * */
function judgeBetSwitch($type){
    $return_val='' ;
    if(strpos($_SESSION['gameSwitch'],'|')>0){
        $gameArr=explode('|',$_SESSION['gameSwitch']);
    }else{
        if(strlen($_SESSION['gameSwitch'])>0){
            $gameArr[]=$_SESSION['gameSwitch'];
        }else{
            $gameArr=array();
        }
    }
    $return_val = in_array($type,$gameArr) ; // 匹配 则为1
    return $return_val ;
}

// 插入用户ip登录记录
function addLoginIpLog($data){
    // type 0会员，1代理商
    global $dbMasterLink ;
    $date = date("Y-m-d"); // 日期
    $datetime = date("Y-m-d H:i:s"); // 时间
    $ip_addr = get_ip(); // ip
    $sql = "INSERT INTO ".DBPREFIX."web_loginip_data (`IpUserId`,`IpUserName`,`IpAgents`,`IpType`,`IpWinLossCredit`,`IpAlias`,`IpLoginDate`, `IpLoginTime`,`IpLogin_Url`,`IpLoginIP`) VALUES";
    $sql .= "($data[0],'$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$date','$datetime','".BROWSER_IP."','$ip_addr')" ;
    // echo $sql;die;
    $result = mysqli_query($dbMasterLink,$sql);
}

/* 限制用户ip 访问网站 */
function limitIpSee(){
    $redisObj = new Ciredis();
    /* type : 1 全站，2 登录，3 注册，4 登录/注册 */
    $datastr = $redisObj->getSimpleOne('font_ip_limit');
    $datastr = json_decode($datastr,true) ;
    $iptype = $datastr['type'] ;
    $dataiparr = explode(';',$datastr['list']);
    $ip_addr = get_ip(); // ip
    if($iptype ==1 && in_array($ip_addr,$dataiparr) ){
        exit("<script>window.location.href='http://baidu.com';</script>");
    }
}

/*获取乐游棋牌参数设置*/
function getLyQpSetting(){
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('lyqp_api_set'); // 取redis 设置的值
    $datajson = json_decode($datajson,true) ;
    return $datajson ;
}

/*获取VG棋牌参数设置*/
function getVgQpSetting(){
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('vgqp_api_set'); // 取redis 设置的值
    $datajson = json_decode($datajson,true) ;
    return $datajson ;
}

// 体育下注返回大小中文标志
function returnSportBetDx($type,$MPlace){
    $tip = '大';
    $MPlace = str_replace('O','',$MPlace);
    $MPlace = str_replace('U','',$MPlace);
    switch ($type){
        case 'O': // 大
        case 'C': // 大
            $tip = '大 ';
            break;
        case 'U': // 小
        case 'H': // 小
            $tip = '小 ';
            break;
    }
    return $tip.$MPlace ;
}

// 体育下注返回大小英文标志
function returnSportBetDxEn($type,$MPlace){
    $tip = 'over';
    $MPlace = str_replace('O','',$MPlace);
    $MPlace = str_replace('U','',$MPlace);
    switch ($type){
        case 'O': // 大
        case 'C': // 大
            $tip = 'over ';
            break;
        case 'U': // 小
        case 'H': // 小
            $tip = 'under ';
            break;
    }
    return $tip.$MPlace ;
}

// 银行卡加密处理
function returnBankAccount($count){
    $font_6 = substr($count , 0 , 6); // 前六位
    $start = 7;
    $end = strlen($count)-9;
    $length = strlen(substr($count,$start,$end));
    $font_center='';
    for($i=0;$i<$length;$i++){
        $font_center .= '*';
    }
    $back_3 = substr($count , -3 , 3); // 后三位
    return $font_6.$font_center.$back_3 ;
}
// 真实姓名加密处理,只展示 姓
function returnRealName($name){
    $font_1 = mb_substr($name,0,1,'utf-8'); // 前1位
    return $font_1.'**' ;
}

/**
 * 随机
 * @return string
 */
function getUnionCode(){
    $yCode = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j','k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't','u', 'v', 'w', 'x', 'y', 'z');
    $num = rand(0,count($yCode)-1);
    $orderSn = $yCode[$num] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}

/*
 *  返回赔率变化标志 ,返回css class 类
 *  大于 1，等于 0 ，小于 -1
 *  $old 旧赔率，$new 新赔率
 * */
function returnRateTip($old,$new){
    $val = ''; // 初始值
    if($old == $new){
        $val = '' ;
    }else if($old > $new){ // 赔率变低
        $val = 'rate_green' ;
    }else if($old < $new){ // 赔率变高
        $val = 'rate_red' ;
    }
    return $val;
}

/*
 * 随机返回刷水赔率变化 redis 的值
 * */
function returnOddsChangsStatus(){
    $newChangeOdds = array('fx852','rg124','wc378','rv985','qd891','kq423','hg752','br894','md279','wq354'); // 如果会员未登录，随机定义 10个用于赔率变化处理
    $fetch_num = array_rand($newChangeOdds,1);
    $newChangeOdds = $newChangeOdds[$fetch_num]; // 随机取一个

    if(!$_SESSION['userid']){ // 未登录
        if(!$_SESSION['odd_change'] ){
            $_SESSION['odd_change'] = $newChangeOdds;
        }
    }else{
        $_SESSION['odd_change'] = $_SESSION['userid'] ;
    }
    return $_SESSION['odd_change'];
}


/*
 * 随机返回新旧版配置域名
 * $type : new ,old
 * */
function returnNewOldVersion($type){
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('new_version_set'); // 取redis 设置的值
    $datastr = json_decode($datajson,true) ;
    if($type=='new'){ // 新版域名
        $data_arr = explode(';',$datastr['oldurl']);
    }else{ // 旧版域名
        $data_arr = explode(';',$datastr['newurl']);
    }
    $fetch_num = array_rand($data_arr,1);
    $afterurl = $data_arr[$fetch_num]?$data_arr[$fetch_num]:'/'; // 随机取一个配置的域名，切换新版本链接
    return $afterurl;
}

/*
 *  国民第三方登录密码加密规则
 *  $username 用户名
 *  $password 原密码
 * */
function passwordThird($username, $password) {
    $val = MD5(MD5(MD5(strtolower($username).$password)));
    return $val;
}

// 国民第三方登录密码加密检验规则
function passwordThirdCheck($password, $hash) {
    $ret = crypt($password, $hash);
    if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
        return false;
    }
    $status = 0;
    for ($i = 0; $i < strlen($ret); $i++) {
        $status |= (ord($ret[$i]) ^ ord($hash[$i]));
    }
    return $status === 0;
}

// 三方彩票注单用户名前缀隐藏
function prefixAccountThird($username) {
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
    $datajson = json_decode($datajson,true) ;
    if(strpos($username,$datajson['agentid']) !== false){ //包含前缀gmcp
        $result = explode('_' , $username, 2);     // $result['0'] = 'gmcp'
        return $result['1'];
    }else{
        return $username;
    }
}

/**
 * 根据第三方游戏标识获取对应的终端是否维护
 * @param $pageMark  sport 体育，rb 滚球，today 今日赛事，future 早盘，video ag视讯，game ag电子，lottery 体育彩票，mobile 手机，ky 开元，hgqp 皇冠，vgqp vg，ly  乐游，avia 泛亚，thirdcp 国民彩票，og og视讯，mw cq电子
 * @return array
 */
function getMaintainDataByCategory($pageMark){
    $dbLink = Dbnew::getInstance('slave');
    $sql = 'SELECT `title`, `state`, `content`, `mark`,`terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE `state` = 1 and mark = "' . $pageMark . '" LIMIT 1';
    $oResult = mysqli_query($dbLink, $sql);
    $aRow = mysqli_fetch_assoc($oResult);
    return $aRow;
}

/*
 * 查询是否有未审核的存款订单
 * 如果有未审核的订单不允许继续提交
 * */
function checkDepositOrder($type){
    global $dbLink;
    $userid = $_SESSION['userid'] ;
    $sql = "select Checked from ".DBPREFIX."web_sys800_data where Checked=0 and Type='S' and Payway='N' and userid='$userid'";
    $result = mysqli_query($dbLink, $sql);
    $cou=mysqli_num_rows($result);
    if($cou>0){ // 有未审核的订单
        $describe = '您有未审核的订单，请稍后重试或联系在线客服!';
        if($type){ // 旧版格式
            echo "<script>alert('$describe');history.back();</script>";exit;
        }else{
            $status = '500.22';
            original_phone_request_response($status,$describe);
        }

    }

}

/** 公用方法：计算存款的优惠并返回
   *  普通银行存款根据会员注册时间享受公司存款优惠  小于2016-08-01 , 配置文件中COMPANY_DEPOSIT_TIME
   *
   *  1） 单笔不超过10万元的，赠送1%, 最多50元。1%，（例如：1%则填写0.01）字段: deposit_bank_less_ten_rate
   *  2） 超过等于10万的存款赠送2%，无上限。 2%，（例如：2%则填写0.02）字段: deposit_bank_more_than_ten_rate
   *      单笔金额字段: deposit_bank_money  值：10W
   *  3）【USDT虚拟币优惠】 字段: newer_usdt_deposit_preferential_rate  新用户USDT充值的优惠比率（例如：1%则填写1）
          2020年9月20日当天以及之前注册的会员，虚拟币存款优惠2%
          2020年9月21日开始之后注册的会员，虚拟币存款优惠1%
*/
function preferentialGold($gold, $member_AddDate,$Bank,$usdtPercent) {
    $deposit_bank_money = getSysConfig('deposit_bank_money');   // 10w
    $deposit_bank_less_ten_youhui_rate = getSysConfig('deposit_bank_less_ten_youhui_rate'); //0.01
    $deposit_bank_more_than_ten_youhui_rate = getSysConfig('deposit_bank_more_than_ten_youhui_rate'); //0.02

    if ($Bank=='USDT虚拟货币'){
        /*if ($member_AddDate > '2020-09-20 23:59:59'){
            if($usdtPercent == 1) { $youhui_row = $gold*0.01; }
            elseif($usdtPercent == 2){ $youhui_row = $gold*0.02; }
        }else{
            $youhui_row = $gold*0.02;
        }*/

        if($usdtPercent == 1) { $youhui_row = $gold*0.01; }
        elseif($usdtPercent == 2){ $youhui_row = $gold*0.02; }
        elseif($usdtPercent == 3){ $youhui_row = $gold*0.03; }
        else{ $youhui_row = $gold*0.01; }
    }
    else{

        if($member_AddDate < COMPANY_DEPOSIT_TIME) {
	
            if ($gold < $deposit_bank_money) { // 銀行存款单笔小于10w优惠 0.01
                $youhui_row =$gold * $deposit_bank_less_ten_youhui_rate;
            }else{
                $youhui_row =$gold * $deposit_bank_more_than_ten_youhui_rate; // 0.02
            }
        } else{

            if ($gold>= $deposit_bank_money){ // 銀行存款单笔大于等于10w优惠 0.02
                $youhui_row=$gold * $deposit_bank_more_than_ten_youhui_rate;  // 0.02
            }else{
                $youhui_row =$gold * $deposit_bank_less_ten_youhui_rate; // 0.01
                if($youhui_row>50){
                    $youhui_row=50;
                }
            }
        }
    }
    return $youhui_row;
}

// 系统维护
function ifSysMaintain(){
    global $dbLink;
    $sql = "SELECT `website`, `systime` FROM " . DBPREFIX . "web_system_data LIMIT 1";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);
    $sysMaintenanceData = [
        'isSysMaintain' => $row['website'],
        'content' => $row['systime']
    ];
    $isSysMaintain = $sysMaintenanceData['isSysMaintain'] == 1 ? 1 : 0;
    $_SESSION['sysMaintenanceData'] = $sysMaintenanceData;
    if($isSysMaintain == 1){
        header("Location: /".TPL_NAME."maintenance.php?issys=$isSysMaintain");
        exit();
    }
    // return $sysMaintenanceData;
}

// 单页维护-20180811
function maintenance($pageMark){
    global $dbLink;
    $sql = 'SELECT `title`, `state`, `content`, `mark`,`terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE `state` = 1 and mark = "' . $pageMark . '" LIMIT 1';
    $oResult = mysqli_query($dbLink, $sql);
    $maintenanceData = [];
    $aRow = mysqli_fetch_assoc($oResult);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    $maintenanceData[$aRow['mark']] = [
        'title' => $aRow['title'],
        'state' => $aRow['state'] == 1 && in_array(1, $aTerminal) ? 1 : 0,
        'content' => $aRow['content']
    ];
    return $maintenanceData;
}

function checkMaintain($showType){
    global $isSysMaintain;
    // 检测系统是否维护中
    if($isSysMaintain == 1)
        return false;
    // 检测体育是否维护中-早盘&今日赛事&滚球维护
    if($showType==''|| $showType=='rb'|| $showType=='today'|| $showType=='future'){ // 先检查是否体育维护
        $sportMaintain = maintenance('sport');
        $isSportMaintain = isset($sportMaintain['sport']) && $sportMaintain['sport']['state'] == 1 ? : 0;
        if($isSportMaintain == 1){
            header("Location: /".TPL_NAME."maintenance.php?type=sport");
            exit();
        }
    }

    $pageMark = $showType ? trim($showType) : 'today'; // 单页维护分类（暂用标题栏分类字段，rb:滚球；today:今日赛事；future:早盘）
    $maintenanceNotice = maintenance($pageMark);
    if(isset($maintenanceNotice[$pageMark]) && $maintenanceNotice[$pageMark]['state'] == 1){
        header("Location: /".TPL_NAME."maintenance.php?type={$pageMark}");
        exit();
    }
}

//自定义函数手机号（前面放4个，后面放2个。中间全部隐藏）
function yc_phone($str){
    $str=$str;
    $resstr=substr_replace($str,'*****',4,5);
    return $resstr;
}

function getTmpObj($val){
    global $isClosedH1, $mem_bkq3_off, $gid, $gid_ary, $tmp_Obj, $rb_tip_r, $show_ior, $rb_tip, $showtype, $gtype;

    // 去掉让球数、大小数的空格
    // 滚球
    if ($val['ratio_hre']){$val['ratio_hre']=str_replace(' ','',$val['ratio_hre']);}
    if ($val['ratio_hrouo']){$val['ratio_hrouo']=str_replace(' ','',$val['ratio_hrouo']);}
    if ($val['ratio_hrouu']){$val['ratio_hrouu']=str_replace(' ','',$val['ratio_hrouu']);}
    if ($val['ratio_re']){$val['ratio_re']=str_replace(' ','',$val['ratio_re']);}
    if ($val['ratio_rouo']){$val['ratio_rouo']=str_replace(' ','',$val['ratio_rouo']);}
    if ($val['ratio_rouu']){$val['ratio_rouu']=str_replace(' ','',$val['ratio_rouu']);}
    // 今日、早盘
    if ($val['hratio']){$val['hratio']=str_replace(' ','',$val['hratio']);}
    if ($val['ratio_ho']){$val['ratio_ho']=str_replace(' ','',$val['ratio_ho']);}
    if ($val['ratio_hu']){$val['ratio_hu']=str_replace(' ','',$val['ratio_hu']);}
    if ($val['ratio']){$val['ratio']=str_replace(' ','',$val['ratio']);}
    if ($val['ratio_o']){$val['ratio_o']=str_replace(' ','',$val['ratio_o']);}
    if ($val['ratio_u']){$val['ratio_u']=str_replace(' ','',$val['ratio_u']);}
    // 独赢
    if ($val['ior_MH']){$val['ior_MH']=round_num($val['ior_MH']);}
    if ($val['ior_MC']){$val['ior_MC']=round_num($val['ior_MC']);}
    if ($val['ior_MN']){$val['ior_MN']=round_num($val['ior_MN']);}
    if ($val['ior_HMH']){$val['ior_HMH']=round_num($val['ior_HMH']);}
    if ($val['ior_HMC']){$val['ior_HMC']=round_num($val['ior_HMC']);}
    if ($val['ior_HMN']){$val['ior_HMN']=round_num($val['ior_HMN']);}

    // 球队进球数 大小 ,让球，大小 处理, 改成后端处理，前端不处理
    $rb_tip = '';
    $rb_tip_r = ''; // 让球
    if ($showtype == 'RB') { // 滚球
        $rb_tip = 'R';
        $rb_tip_r = 'E';
    }
    $show_ior = 100;

    if ($gtype == "FT") {
//        if ($val['gid'] == $gid) {
            $gid_ary[] = $val['gid'];
            $tmp_Obj[$val['gid']] = $val;
            // gid 用master，附属盘口的专属gid放到gid_fs
            global $gid_master;
            if ($val['@attributes']['master']=='Y' and $val['@attributes']['ptype']==''){
                $gid_master=$val['gid'];
                $tmp_Obj[$val['gid']]['gid_fs']=$gid_master;
            }
            else{
                $tmp_Obj[$val['gid']]['gid']=$gid_master;
                $tmp_Obj[$val['gid']]['gid_fs']=$val['gid'];
                if (strpos($val['ptype'],'角球')!==false){ $tmp_Obj[$val['gid']]['description']='角球'; }
                elseif (strpos($val['ptype'],'罚牌')!==false){ $tmp_Obj[$val['gid']]['description']='罚牌数'; }
                elseif (strpos($val['ptype'],'会晋')!==false){ $tmp_Obj[$val['gid']]['description']='会晋级'; }
                elseif (strpos($val['ptype'],'点球')!==false){ $tmp_Obj[$val['gid']]['description']='点球大战'; }
                elseif (strpos($val['ptype'],'加时赛')!==false){ $tmp_Obj[$val['gid']]['description']='加时赛'; }
            }
//        }
    } elseif ($gtype == "BK") {

        $gid_ary[] = $val['gid'];
        $tmp_Obj[$val['gid']] = $val;
    }

    // 让球
    $ior_RH = $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'H'];
    $ior_RC = $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'C'];
    $ior_HRH = $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'H'];
    $ior_HRC = $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'C'];
    $newarry_ior_r1 = get_other_ioratio(GAME_POSITION, $ior_RH, $ior_RC, $show_ior);
    $newarry_ior_r2 = get_other_ioratio(GAME_POSITION, $ior_HRH, $ior_HRC, $show_ior);
    $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'H'] = $newarry_ior_r1[0] ? $newarry_ior_r1[0] : '0';
    $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'C'] = $newarry_ior_r1[1] ? $newarry_ior_r1[1] : '0';
    $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'H'] = $newarry_ior_r2[0] ? $newarry_ior_r2[0] : '0';
    $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'C'] = $newarry_ior_r2[1] ? $newarry_ior_r2[1] : '0';

    // 大小
    $ior_OUH = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUH'];
    $ior_OUC = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUC'];
    $ior_HOUH = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUH'];
    $ior_HOUC = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUC'];
    $newarry_ior_o1 = get_other_ioratio(GAME_POSITION, $ior_OUH, $ior_OUC, $show_ior);
    $newarry_ior_o2 = get_other_ioratio(GAME_POSITION, $ior_HOUH, $ior_HOUC, $show_ior);
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUH'] = $newarry_ior_o1[0] ? $newarry_ior_o1[0] : '0';
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUC'] = $newarry_ior_o1[1] ? $newarry_ior_o1[1] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUH'] = $newarry_ior_o2[0] ? $newarry_ior_o2[0] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUC'] = $newarry_ior_o2[1] ? $newarry_ior_o2[1] : '0';

    // 球队进球数 大小
    $ior_OUHO = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHO'];
    $ior_OUHU = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHU'];
    $ior_OUCO = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCO'];
    $ior_OUCU = $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCU'];
    $ior_HOUHO = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHO'];
    $ior_HOUHU = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHU'];
    $ior_HOUCO = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCO'];
    $ior_HOUCU = $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCU'];
    if ($showtype == 'RB') { // 滚球 球队进球数 大小 半场
        $ior_HRUHO = $tmp_Obj[$val['gid']]['ior_HRUHO'];
        $ior_HRUHU = $tmp_Obj[$val['gid']]['ior_HRUHU'];
        $ior_HRUCO = $tmp_Obj[$val['gid']]['ior_HRUCO'];
        $ior_HRUCU = $tmp_Obj[$val['gid']]['ior_HRUCU'];
        $newarry_ior5 = get_other_ioratio(GAME_POSITION, $ior_HRUHO, $ior_HRUHU, $show_ior);
        $newarry_ior6 = get_other_ioratio(GAME_POSITION, $ior_HRUCO, $ior_HRUCU, $show_ior);
        $tmp_Obj[$val['gid']]['ior_HRUHO'] = $newarry_ior5[0] ? $newarry_ior5[0] : '0';
        $tmp_Obj[$val['gid']]['ior_HRUHU'] = $newarry_ior5[1] ? $newarry_ior5[1] : '0';
        $tmp_Obj[$val['gid']]['ior_HRUCO'] = $newarry_ior6[0] ? $newarry_ior6[0] : '0';
        $tmp_Obj[$val['gid']]['ior_HRUCU'] = $newarry_ior6[1] ? $newarry_ior6[1] : '0';
    }

    $newarry_ior1 = get_other_ioratio(GAME_POSITION, $ior_OUHO, $ior_OUHU, $show_ior);
    $newarry_ior2 = get_other_ioratio(GAME_POSITION, $ior_OUCO, $ior_OUCU, $show_ior);
    $newarry_ior3 = get_other_ioratio(GAME_POSITION, $ior_HOUHO, $ior_HOUHU, $show_ior);
    $newarry_ior4 = get_other_ioratio(GAME_POSITION, $ior_HOUCO, $ior_HOUCU, $show_ior);
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHO'] = $newarry_ior1[0] ? $newarry_ior1[0] : '0';
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHU'] = $newarry_ior1[1] ? $newarry_ior1[1] : '0';
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCO'] = $newarry_ior2[0] ? $newarry_ior2[0] : '0';
    $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCU'] = $newarry_ior2[1] ? $newarry_ior2[1] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHO'] = $newarry_ior3[0] ? $newarry_ior3[0] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHU'] = $newarry_ior3[1] ? $newarry_ior3[1] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCO'] = $newarry_ior4[0] ? $newarry_ior4[0] : '0';
    $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCU'] = $newarry_ior4[1] ? $newarry_ior4[1] : '0';

    //  盘口关掉
    if ($showtype == 'RB') { // 滚球
        $tmp_Obj[$val['gid']]['sw_RT'] = 'N'; // 全场足球滚球总进球数
        $tmp_Obj[$val['gid']]['sw_HRT'] = 'N'; // 半场足球滚球总进球数
//        $tmp_Obj[$val['gid']]['sw_RPD'] = 'N'; // 全场波胆

        if ($gtype == "BK") {
            $tmp_Obj[$val['gid']]['sw_ROUH']='N';
            $tmp_Obj[$val['gid']]['sw_ROUC']='N';
            unset($tmp_Obj[$val['gid']]['ratio_rouco']);
            unset($tmp_Obj[$val['gid']]['ratio_roucu']);
            unset($tmp_Obj[$val['gid']]['ratio_rouho']);
            unset($tmp_Obj[$val['gid']]['ratio_rouhu']);
            unset($tmp_Obj[$val['gid']]['ior_ROUCO']);
            unset($tmp_Obj[$val['gid']]['ior_ROUHO']);
            unset($tmp_Obj[$val['gid']]['ior_ROUCU']);
            unset($tmp_Obj[$val['gid']]['ior_ROUHU']);

            if ((strpos($val['team_h'], '-') !== false and $isClosedH1) ||
//                ($val['se_now'] == 'Q3' and $mem_bkq3_off == 'off') ||
//                ($val['se_now'] == 'H2' and $val['t_count'] <= 1190 and $mem_bkq3_off == 'off') ||
//                ($val['se_now'] == 'HT' and $val['t_count'] > 0 and $val['t_count'] <= 1190 and $mem_bkq3_off == 'off') ||
                ($val['se_now'] == 'H2' and $val['t_count'] <= 600) ||
                $val['se_now'] == "Q4" ||
                ($val['se_now'] == "Q3" && $val['t_count']<=180)) {
                $tmp_Obj[$val['gid']]=array();
                $tmp_Obj[$val['gid']]['datetime'] = $val['datetime'];
                $tmp_Obj[$val['gid']]['gid'] = $val['gid'];
                $tmp_Obj[$val['gid']]['gidm'] = $val['gidm'];
                $tmp_Obj[$val['gid']]['gnum_c'] = $val['gnum_c'];
                $tmp_Obj[$val['gid']]['gnum_h'] = $val['gnum_h'];
                $tmp_Obj[$val['gid']]['gopen'] = 'N';
                $tmp_Obj[$val['gid']]['gtype'] = $val['gtype'];
                $tmp_Obj[$val['gid']]['league'] = $val['league'];
                $tmp_Obj[$val['gid']]['t_count'] = $val['t_count'];
                $tmp_Obj[$val['gid']]['t_status'] = $val['t_status'];
                $tmp_Obj[$val['gid']]['team_c'] = $val['team_c'];
                $tmp_Obj[$val['gid']]['team_h'] = $val['team_h'];
                $tmp_Obj[$val['gid']]['re_time'] = $val['re_time'];
                $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'H'] = '0';
                $tmp_Obj[$val['gid']]['ior_R' . $rb_tip_r . 'C'] = '0';
                $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'H'] = '0';
                $tmp_Obj[$val['gid']]['ior_HR' . $rb_tip_r . 'C'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUH'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUC'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUH'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUC'] = '0';
                $tmp_Obj[$val['gid']]['ior_HRUHO'] = '0';
                $tmp_Obj[$val['gid']]['ior_HRUHU'] = '0';
                $tmp_Obj[$val['gid']]['ior_HRUCO'] = '0';
                $tmp_Obj[$val['gid']]['ior_HRUCU'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHO'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUHU'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCO'] = '0';
                $tmp_Obj[$val['gid']]['ior_' . $rb_tip . 'OUCU'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHO'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUHU'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCO'] = '0';
                $tmp_Obj[$val['gid']]['ior_H' . $rb_tip . 'OUCU'] = '0';
                $tmp_Obj[$val['gid']]['sc_FT_A'] = $val['sc_FT_A'] ? $val['sc_FT_A'] : "";
                $tmp_Obj[$val['gid']]['sc_FT_H'] = $val['sc_FT_H'] ? $val['sc_FT_H'] : "";
                $tmp_Obj[$val['gid']]['sc_H1_A'] = $val['sc_H1_A'] ? $val['sc_H1_A'] : "";
                $tmp_Obj[$val['gid']]['sc_H1_H'] = $val['sc_H1_H'] ? $val['sc_H1_H'] : "";
                $tmp_Obj[$val['gid']]['sc_H2_A'] = $val['sc_H2_A'] ? $val['sc_H2_A'] : "";
                $tmp_Obj[$val['gid']]['sc_H2_H'] = $val['sc_H2_H'] ? $val['sc_H2_H'] : "";
                $tmp_Obj[$val['gid']]['sc_OT_A'] = $val['sc_OT_A'] ? $val['sc_OT_A'] : "";
                $tmp_Obj[$val['gid']]['sc_OT_H'] = $val['sc_OT_H'] ? $val['sc_OT_H'] : "";
                $tmp_Obj[$val['gid']]['sc_Q1_A'] = $val['sc_Q1_A'] ? $val['sc_Q1_A'] : "";
                $tmp_Obj[$val['gid']]['sc_Q1_H'] = $val['sc_Q1_H'] ? $val['sc_Q1_H'] : "";
                $tmp_Obj[$val['gid']]['sc_Q2_A'] = $val['sc_Q2_A'] ? $val['sc_Q2_A'] : "";
                $tmp_Obj[$val['gid']]['sc_Q2_H'] = $val['sc_Q2_H'] ? $val['sc_Q2_H'] : "";
                $tmp_Obj[$val['gid']]['sc_Q3_A'] = $val['sc_Q3_A'] ? $val['sc_Q3_A'] : "";
                $tmp_Obj[$val['gid']]['sc_Q3_H'] = $val['sc_Q3_H'] ? $val['sc_Q3_H'] : "";
                $tmp_Obj[$val['gid']]['sc_Q4_A'] = $val['sc_Q4_A'] ? $val['sc_Q4_A'] : "";
                $tmp_Obj[$val['gid']]['sc_Q4_H'] = $val['sc_Q4_H'] ? $val['sc_Q4_H'] : "";
                $tmp_Obj[$val['gid']]['sc_new'] = $val['sc_new'] ? $val['sc_new'] : "";
                $tmp_Obj[$val['gid']]['score_c'] = $val['score_c'] ? $val['score_c'] : "";
                $tmp_Obj[$val['gid']]['score_h'] = $val['score_h'] ? $val['score_h'] : "";
                $tmp_Obj[$val['gid']]['se_now'] = $val['se_now'] ? $val['se_now'] : "";
                $tmp_Obj[$val['gid']]['se_type'] = $val['se_type'] ? $val['se_type'] : "";
                $tmp_Obj[$val['gid']]['session'] = $val['session'] ? $val['session'] : "";
                $tmp_Obj[$val['gid']]['strong'] = $val['strong'] ? $val['strong'] : "";
            }
        }
    }
    if(isset($tmp_Obj)&&count($tmp_Obj)>0) $result['tmp_Obj']=$tmp_Obj;
    if(isset($gid_ary)&&count($gid_ary)>0) $result['gid_ary']=$gid_ary;

    return $result;
}

function xmlToArray($xml){
    libxml_disable_entity_loader(true); 	//禁止引用外部xml实体
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(str_replace('{}','""',json_encode($xmlstring)),true);
    return $val;
}

// 读取图片缓存
function getPicConfig($key = ''){
    global $redisObj;
    $sysPicConfigSet = $redisObj->getSimpleOne('pic_config_set');
    $pconfigPicSet = json_decode($sysPicConfigSet, true);
    if($key == ''){
        return $pconfigPicSet;
    }else{
        return isset($pconfigPicSet[$key]) ? $pconfigPicSet[$key] : '';
    }
}

// 查询会员分层的状态
function getUserLayerById($id){
    global $dbLink;
    $mysql = "select id,title,status from ".DBPREFIX."web_member_data_layer where id = {$id}";
    $result = mysqli_query($dbLink, $mysql);
    $layer = mysqli_fetch_assoc($result);
    return $layer;
}

// 返回代理登录地址 $type : m 返回M 版 地址
function returnAgentUrl($type){
    global $ulrarr;
    if(!$type){$type='';}
    $allulrarr = explode(',',$ulrarr) ;
    $fetch_num = array_rand($allulrarr,1);
    $afterurl = $allulrarr[$fetch_num]; // 随机取一个配置的域名
    $comurl = HTTPS_HEAD.'://'.($type=='m'?'m.':'ag.').$afterurl;
    return $comurl;
}

/**
 *  生成手机验证码
 */
function generateRandNum($length = 6)
{
    $randNum = '';
    for ($i = 0; $i < $length; $i++)
    {
        $randNum .= chr(mt_rand(48, 57));
    }

    return $randNum;
}

//返回优惠分类类型
function returnPromosType($type){
    global $dbMasterLink;
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('promos_type_arr'); // 取redis 设置的值
    $datastr = json_decode($datajson,true) ;
    // var_dump($datastr);
    if(!$datastr || $type=='edit'){
        $categorySql = "SELECT `id`, `name`, `tag` FROM " . DBPREFIX . "web_promos_category WHERE status=1  order by sort ASC";
        $categoryResult = mysqli_query($dbMasterLink, $categorySql);
        $categoryList = array();
        while ($categoryRow = mysqli_fetch_assoc($categoryResult)){
            $categoryList[$categoryRow['id']] = $categoryRow;
        }
        $datastr = $categoryList;
        $redisObj->setOne('promos_type_arr',json_encode($categoryList));
    }
return $datastr;
}


/*
 * 返回优惠活动列表
 * $plat: 1:新版；2：旧版( 威尼斯人PC )；3：手机版；4：广告版( 威尼斯人手机 )
 * */
function returnPromosList($type,$plat){
    global $dbMasterLink;
    $redisObj = new Ciredis();
    $datajson = $redisObj->getSimpleOne('promos_list_arr'); // 取redis 设置的值
    $datastr = json_decode($datajson,true) ;
    $allList = $lists = array();
    // var_dump($datastr);
    if(!$datastr || $type=='edit'){
        $sql = "SELECT `id`,`title`,`subtitle`,`pic_url`,`content_url`,`pic_url_mobile`,`content_url_mobile`,`pic_url_ad`,`content_url_ad`,`category_id`,`website`,`api_url`,`api_url_old`,`api_url_mobile`,`flag`,`created_at`,`updated_at` FROM " . DBPREFIX . "web_promos WHERE `status`=1 ORDER BY `sequence` ASC";
        $result = mysqli_query($dbMasterLink, $sql);

        while ($row = mysqli_fetch_assoc($result)){
            $allList[] = $row; // 所有
        }
        $datastr = $allList;
        $redisObj->setOne('promos_list_arr',json_encode($allList));
    }else{ // 获取数据

        foreach($datastr as $key => $v){
            switch ($plat){
                case 1:
                    $imgurl = $v['pic_url'];
                    $contenturl = $v['content_url'];
                    $ajaxurl = $v['api_url'];
                break;
                case 2:
                    $imgurl = $v['pic_url'];
                    $contenturl = $v['content_url'];
                    $ajaxurl = $v['api_url_old'];
                break;
                case 3:
                    $imgurl = $v['pic_url_mobile'];
                    $contenturl = $v['content_url_mobile'];
                    $ajaxurl = $v['api_url_mobile'];
                break;
                case 4:
                    $imgurl = $v['pic_url_ad'];
                    $contenturl = $v['content_url_ad'];
                    $ajaxurl = $v['api_url_mobile'];
                break;
            }
            // 支持的站点展示
            $aWebsite = explode(',', $v['website']);
            if(in_array($plat, $aWebsite)){ // 1:新版；2：旧版( 威尼斯人PC )；3：手机版；4：广告版( 威尼斯人手机 )
                $lists[] = [
                    'website' => $plat,
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'title1' => $v['subtitle'],
                    'imgurl' => PROMOS_PIC_DOMAIN .$imgurl ,
                    'contenturl' => PROMOS_PIC_DOMAIN .$contenturl,
                    'type' => $v['category_id'],
                    'ajaxurl' => $ajaxurl,
                    'flag' => $v['flag'],
                    'date' => substr($v['created_at'], 0, 10),
                ];

            }
        }

    }

    return $lists;
}

/**
 * usdt汇率
 * 会员冲币-0.03  提币+0.01
 * 冲币是会员存款，存款汇率要低，对会员才有好处
 */
function returnUsdtRate(){
    $depositUsdtRate = getSysConfig('deposit_usdt_rate');
    $withdrawalUsdtRate = getSysConfig('withdrawal_usdt_rate');
    //$url = 'https://otc-api.huobi.fm/v1/data/trade-market?coinId=2&currency=1&tradeType=sell&currPage=2&payMethod=0&country=37&blockType=general&online=1&range=0&amount=';
    //$url = 'https://apiv2.bitz.com/Market/currencyCoinRate?coins=usdt';
/*    $url = 'https://otcapinew.ahighapi.com/Settings/getMarketRate'; // https://otc.bitz.com/cn/trade/buy/usdt
    $sUsdtRate = file_get_contents($url);
    $aUsdtRate = json_decode($sUsdtRate, true);
    $sDepositRate = $aUsdtRate['data']['usdt_cny']['buy_rate']+$depositUsdtRate;
    $sWithdrawalsRate = $aUsdtRate['data']['usdt_cny']['buy_rate']+$withdrawalUsdtRate;*/
    // 火币网
    $url = 'https://www.huobi.com/-/x/general/exchange_rate/list?r=pj0xc&x-b3-traceid=e87431eb571b68168548c36bcce43b5b';
    $sUsdtRate = file_get_contents($url);
    $aUsdtRate = json_decode($sUsdtRate, true);
    $sDepositRate = $aUsdtRate['data'][123]['rate']+$depositUsdtRate;
    $sWithdrawalsRate = $aUsdtRate['data'][123]['rate']+$withdrawalUsdtRate;
    $rate['usdt_rate'] = sprintf("%.2f", round($sDepositRate,2));
    $rate['withdrawals_usdt_rate'] = sprintf("%.2f", round($sWithdrawalsRate,2));

    // 如果当前机器获取赔率失败，则从新加坡域名获取赔率
    if ($rate['usdt_rate']<1){
        $singaporeUrl = getSysConfig('singapore_url');
        $url = $singaporeUrl."/api/usdtRateApiOfSingapore.php";
        $sUsdtRate = file_get_contents($url);
        $aUsdtRate = json_decode($sUsdtRate, true);
        $rate['usdt_rate'] = $aUsdtRate['data']['usdt_rate'];
        $rate['withdrawals_usdt_rate'] = $aUsdtRate['data']['withdrawals_usdt_rate'];
    }

    return $rate;
}

/**
 * 旧版跳转新版需要用到 , 晋级活动计划任务(月俸禄)需要用到
 * Status = 0 启用
 * Status = 1 冻结
 * Status=  1 and isAutoFreeze=1   自动冻结
 * Status = 2 停用
 *
 * 根据用户名返回用户信息
 */
function returnMemberID($username , $user_id){
    global $dbLink;
    $username = trim($username);
    if(!empty($username)){
        $where = "UserName = '$username'";
    }
    if(!empty($user_id)){
        $where = "ID = '$user_id'";
    }

    $sql = "SELECT test_flag,UserName,AddDate,EditDate,layer,ID,Oid,Agents,Status FROM `".DBPREFIX.MEMBERTABLE."` WHERE  $where AND Status=0 ";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);

    if($cou > 0) {
        return $row;
    }else {
        return false;
    }
}

/**
 * 获取跳转后的域名与uid, 根据uid更新url
 */
function getNewdomain(){
    global $html_data;

    $ss = $html_data;
    $ss = str_replace("\"",'', $ss); // 去除双引号
    $ss = str_replace('\'','', $ss); // 去除单引号
    $ss = str_replace(' ','', $ss); // 去除空格

    $ss = substr($ss, strpos($ss, 'http://')); //截取 http:// 之后字符，包括 http://
    $data['url']=substr($ss,0,strpos($ss, 'method=POST')); //截取 method=POST 之前字符
    $data['uid'] = end(explode('value=', explode('><inputtype', $ss)[0]));

    @error_log(date("Y-m-d H:i:s").'--新地址:'. $data['url'] .'--uid:'. $data['uid'] .PHP_EOL, 3, '/tmp/group/newdomain.log');
    return $data;
}

/**
 * 旧版、新版，m版 投注验证刷水uid 是否掉线。记录redis
 */
function checkAccountExpand($html_data , $name) {

    $ss = $html_data;
    $ss = str_replace("\"",'', $ss); // 去除双引号
    $ss = str_replace('\'','', $ss); // 去除单引号
    $ss = str_replace(' ','', $ss); // 去除空格

    // 被踢出， 记录redis， 后台提示
    if(strpos($ss ,'logout_warn') !== false) {
        // 例:<script>window.open('http://205.201.2.163/tpl/logout_warn.html','_top')</script>"
        $replace = array('<script>', '(', 'window.open', '/tpl/logout_warn.html', ',', '_top', ')', '</script>');
        $url = htmlspecialchars(str_replace($replace, '', $ss)); //预定义的字符转换为 HTML 实体

        $redisObj = new Ciredis();
        $account_expand_site = $redisObj->getSimpleOne('getdata_account_expand_site');
        if(!$account_expand_site) {
            $redisObj->insert('getdata_account_expand_site', $url, 5*60);
        }
        @error_log(date("Y-m-d H:i:s").'--正网地址被踢出:'. $url .'--被踢账号:'. $name .PHP_EOL, 3, '/tmp/group/logout_warn.log');
    }
}

/**
 * @param $aData
 * @return bool|string
 */
function getLids($aData){
    $aLid=$aLeagueRegion=[];
    // 只有一个地区，跟有2个或2个以上的情况需要分别处理
    // 联赛同样，也是要分开处理
    if ($aData['classifier']['region']['league']){
        // 1个地区的处理方式
        if(count($aData['classifier']['region']['league']) ==1 ) { // 当前地区只有一个联赛时，则直接将联赛的LID 添加到数据集合aLid中
            $aLid[]=$aData['classifier']['region']['league']['@attributes']['id'];
            $aLeagueRegion[$aData['classifier']['region']['league']['@attributes']['name']]=$aData['classifier']['region']['@attributes']['name'];

        }elseif (count($aData['classifier']['region']['league']) >1){ // 当前地区有多个联赛，则直接循环获取LID添加到数据集合aLid中
            foreach($aData['classifier']['region']['league'] as $k => $v) {
                $aLid[]= $v['@attributes']['id'];
                $aLeagueRegion[$v['@attributes']['name']]=$aData['classifier']['region']['@attributes']['name'];
            }
        }else{//  其他情况
            $aLid[] = !empty($aData['coupons']['coupon'][0]['lid']) ? $aData['coupons']['coupon'][0]['lid'] : $aData['coupons']['coupon']['lid'];
        }
    }
    else{// 多个地区的处理方式
        foreach ($aData['classifier']['region'] as $k => $v){

            // 一个联赛和多个联赛的处理
            if ($v['league']['@attributes']){ // 当前地区只有一个联赛时，则直接将联赛的LID 添加到数据集合aLid中
                $aLid[]=$v['league']['@attributes']['id'];
                $aLeagueRegion[$v['league']['@attributes']['name']]=$v['@attributes']['name'];
            }
            else{ // 当前地区有多个联赛，则直接循环获取LID添加到数据集合aLid中
                foreach ($v['league'] as $k2 => $v2){
                    $aLid[]=$v2['@attributes']['id'];
                    $aLeagueRegion[$v2['@attributes']['name']]=$v['@attributes']['name'];
                }
            }
        }
    }
    if (count($aLid)==0){
        print_r($aData);
        return false;
    }
    $lid = implode(',',$aLid);
    $data['lid'] = $lid;
    $data['aLeagueRegion'] = $aLeagueRegion;

    return $data;
}

/**
 * 6686刷水，从数据中捞出全部的联赛ID  lid，以及联赛所属地区=
 *
 * @param $aData
 * @return array|bool
 */
function getLids686($aData){
    $aLid=[];
    // 分别捞取，最后汇总。country 下面的lid ，popular下面的lid
    if (count($aData['data']['country'])>0){
        foreach ($aData['data']['country'] as $k => $v){
            foreach ($v['competitions'] as $k2 => $v2){
                $aLid[] = $v2['competitionId'][0];
            }
        }
    }
    if (count($aData['data']['menu'])>0){
        foreach ($aData['data']['menu'] as $k => $v){
            foreach ($v['competitions'] as $k2 => $v2){
                $aLid[] = $v2['competitionId'][0];
            }
        }
    }
    if (count($aData['data']['popular'])>0){
        foreach ($aData['data']['popular'] as $k => $v){
            foreach ($v['competitions'] as $k2 => $v2){
                $aLid[] = $v2['competitionId'][0];
            }
        }
    }
    // jack 添加，今日足球最新数据结构
    if (count($aData['data']['competitionList'])>0){
        foreach ($aData['data']['competitionList'] as $k => $v){
            // 拿到二级联赛ID
            foreach ($v['seasons'] as $k2 => $v2){
                $aLid[] = $v2['id'];
                $aLeagueRegion[$v2['name']] = $v['name'];
            }
        }
    }
    $aLid=array_unique($aLid);
    if (count($aLid)==0){
        return false;
    }
    $lid = implode(',',$aLid);

    $aTmp = [
        'lid'=>$lid,
        'leagueRegion'=>$aLeagueRegion,
    ];
    return $aTmp;
}

/**
 * 根据输入的时间，返回 12小时制的时分，（格式：03:00p）
 * @param $datetime
 * @return false|string
 */
function getMtime($datetime){
    $m_time = date('g:ia',strtotime($datetime));
    $h = explode(':',$m_time)[0];
    if ($h<10){
        $m_time = '0'.$m_time;
    }
    $m_time = rtrim($m_time,'m');
    return $m_time;
}

/**
 * 时间格式转换，例如：将 07-14 11:00P  转为 2021-07-14 23:00:00
 *
 * @param $datetime
 * @return string
 */
function translateDatetime($datetime){
    $mDate=explode(' ',strtoupper($datetime));  //array(2) { [0]=> string(5) "07-14" [1]=> string(6) "12:00P" }
    $mm_date=date('Y')."-".$mDate[0];    //2021-07-14
    $m_time=strtolower($mDate[1]);  //12:00P
    $hhmmstr=explode(":",$m_time);  //array(2) { [0]=> string(2) "12" [1]=> string(3) "00p" }
    $hh=$hhmmstr[0];    //12
    $ap = substr($m_time, strlen($m_time) - 1, 1);
    if ($ap=='p' and $hh<>12){
        $hh+=12;
    }
    $tmp = $mm_date." ".$hh.":".substr($hhmmstr[1],0,strlen($hhmmstr[1])-1).":00";

    return $tmp;
}

/**
 * 篮球小节中文转化
 * @param string
 * @return string
 */
function translatGameTeam($teamName){

    if(mb_strpos($teamName,'第一节')) {
        $teamName = str_replace('一','1',$teamName);
    }elseif(mb_strpos($teamName,'第二节')) {
        $teamName = str_replace('二','2',$teamName);
    }elseif(mb_strpos($teamName,'第三节')) {
        $teamName = str_replace('三','3',$teamName);
    }elseif(mb_strpos($teamName,'第四节')) {
        $teamName = str_replace('四','4',$teamName);
    }

    return $teamName;
}

/**
 * 分割数组
 * @param $data  需要分割的一维数组
 * @param $columns  每个数组的值的数量
 * @return array  返回一个二维数组
 */
function array_chunk_vertical($data, $columns) {
    $n = count($data) ;
    $per_column = floor($n / $columns) ;
    $rest = $n % $columns ;

    // The map
    $per_columns = array( ) ;
    for ( $i = 0 ; $i < $columns ; $i++ ) {
        $per_columns[$i] = $per_column + ($i < $rest ? 1 : 0) ;
    }

    $tabular = array( ) ;
    foreach ( $per_columns as $rows ) {
        for ( $i = 0 ; $i < $rows ; $i++ ) {
            $tabular[$i][ ] = array_shift($data) ;
        }
    }

    return $tabular ;
}
