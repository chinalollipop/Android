<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/**
 * 第三方自动出款
 */
include("../include/address.mem.php");
include("../include/config.php");
include("../include/jiutong/util.php");
require("../include/huitong/helper.php");
include_once ("../include/bth/BthConstants.php"); // BTN
include_once ("../include/bth/Security.php");   // BTN
include_once ("../include/mayidaifu/myremit.php");   // 蚂蚁代付类

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    exit(json_encode(['err' => '-1', 'msg' => '请重新登录']));
}

@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/onlinepay_api.log');

$autopaymethod=$_REQUEST['method'];
$id=(int)$_REQUEST['id'];
$lv = $_REQUEST['lv'] ;

switch ($autopaymethod){
    case 'csjpay_cash_autock': // 创世纪下发
        include_once ("../include/auto/juming/ServiceUtil.php");   //引用聚名公共文件使用
        include_once ("../include/auto/chuangshiji/Config.php");

        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);

        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取创世纪第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'csjpay_cash_autock' and status = 1";
            $result = mysqli_query($dbLink, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!empty($row)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = bcmul(floatval($user_record_info['Gold'])*100,1,2);  //金额，以分为单位

                // 查询会员IP
                $sql="select ID,UserName,Alias,Bank_Name,Bank_Address,Bank_Account,LoginIP from ".DBPREFIX.MEMBERTABLE." where ID=".$user_record_info['userid'];
                $result=mysqli_query($dbLink,$sql);
                $myrow=mysqli_fetch_array($result);

                $appKey = $row['business_pwd']; // 商户秘钥
                //$requestUrl = 'http://test.qmz918.com/api/cash/placeCash';    // 测试地址
                $requestUrl = 'https://api.bixin88.com/api/cash/placeCash';    // 生产代付地址
                // 签名列表
                $postParams["merchno"]   = strval($row['business_code']);        // 商户号
                $postParams["orderId"]      = $user_record_info['Order_Code'];   //商户名订单ID
                $postParams["amount"]       = bcmul($user_record_info['Gold'],1,2);
                $postParams["account"]      = trim($user_record_info['Name']);   //收款人姓名
                $postParams["tradeType"]    = '1';          // 1：对私；2：对公；（目前只支持对私交易）
                $postParams["cardNo"]       = $user_record_info['Bank_Account']; // 银行卡号
                $postParams["bankName"]     = $user_record_info['Bank'];         // 银行中文名称
                $postParams["depositBank"]  = $user_record_info['Bank_Address']; // 开户支行
                $postParams["asyncUrl"]     = $row['url'];  // 回调地址
                $postParams["timestamp"]    = date('YmdHis', time()+12*60*60); //系统请求时间戳，精确到秒，格式为： yyyyMMddHHmmss 例如：20190102172328 (北京时间)
                $postParams["attach"]       = $myrow['UserName'].'|'.$row['id'].'|'.$myrow['ID'];               //会员名称|渠道id|用户Oid|支付方式代码

                ksort($postParams);    // 排序
                $sign_str = ServiceUtil::get_sign($postParams);     // 拼接md5字符串
                $string = $sign_str . '&secretKey=' . $appKey;      // 末尾添加商户秘钥
                $md5Sign  = strtolower(md5($string));               // md5生成信息摘要，并转为小写

                $privateKey = ServiceUtil::privateKeyStr(Config::privateKey);  //获取商户私钥
                $postParams["sign"] = ServiceUtil::signRSA2($md5Sign,$privateKey);   //信息摘要（MD5）结合非对称加密（RSA2）对传 生成签名

                $result = send_post($requestUrl, $postParams);
                $resultArr = json_decode($result, true);
                //result:{"responseContent":{"code":0,"msg":"成功","timestamp":"20210114134957","merchno":"4b284a5186","orderId":"TK20210114014944321341311517","orderNo":"p1610603397067","status":0},"sign":"aVEYMV9tSo7B+d82cbdenE7Z/J5Uvl/fJQt98Gq9AbEGs3OUTwW5ImbtTeOz4sIKWcdLb9Q5kjdh4DWdLnxOoRTO7f85XYwd331mVJIdvY6FjPs1Dmm/ALsFshwTl8KjJidjJaam6BDMfoUWjGPJRi9JT4ImF4SYC7qO7Dfv5v1AHkTTTwt2v3MMmr/qLjeLZ7/GprelCBu937YC31zUj5B8qSb4M76E9alSVpmwgWfTzkq/D4+TsPRERgi3SR48CDezCnkeSRa6FG7Z9yDykQ+AX7NRXvg0YHWyBri/qEc7kQYcVyNhBQJUu3X1rG51owO9lBuNJoFfZOoXZHncCQ=="}

                if($resultArr['responseContent']["code"] == "0" && $resultArr['responseContent']["msg"] == "成功"){  //创世纪代付数据提交成功
                    // 0：处理中，1：成功，2：失败"
                    if($resultArr['responseContent']["status"] == "0" || $resultArr['responseContent']["status"] == "1"){
                        mysqli_query($dbMasterLink,"COMMIT");
                        $err_resonse = array('err' => '0', 'msg' => '创世纪自动出款验签成功,请求成功,处理中!');
                    }else if($resultArr['responseContent']["status"] == "2"){
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-8', 'msg' => '创世纪自动出款返回失败');
                    }
                }else{  //创世纪数据提交失败
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => '提交失败,'.$resultArr['responseContent']['msg']);
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询创世纪第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'xcpay_cash_autock': // 星辰下发
        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            mysqli_query($dbMasterLink,"ROLLBACK");
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }

        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);

        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取星辰第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'xcpay_cash_autock' and status = 1";
            $result = mysqli_query($dbLink, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!empty($row)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = bcmul(floatval($user_record_info['Gold'])*100,1,2);  //金额，以分为单位

                // 查询会员IP
                $sql="select ID,UserName,Alias,Bank_Name,Bank_Address,Bank_Account,LoginIP from ".DBPREFIX.MEMBERTABLE." where ID=".$user_record_info['userid'];
                $result=mysqli_query($dbLink,$sql);
                $myrow=mysqli_fetch_array($result);
                $autopayBank=['招商银行'=>'CMB','中国工商银行'=>'ICBC','中国建设银行'=>'CCB','中国银行'=>'BOC','中国农业银行'=>'ABOC','交通银行'=>'BOCOM','浦发银行'=>'SPDB','广发银行'=>'CGB','中信银行'=>'ECITIC','光大银行'=>'CEB','兴业银行'=>'CIB','平安银行'=>'SDB','中国民生银行'=>'CMBC','华夏银行'=>'HXB','邮政储蓄银行'=>'PSBC','北京银行'=>'BOBJ','上海银行'=>'BOS','杭州银行'=>'HZB','北京农商银行'=>'BJRCB','汉口银行'=>'HKBCHINA','晋城银行'=>'SXJS','南京银行'=>'NJCB','浙江稠州商业银行'=>'CSCB','宁波银行'=>'NBCB','顺德农村商业银行'=>'SDBC','恒丰银行'=>'EGBANK','浙商银行'=>'CZB','渤海银行'=>'CBHB','微商银行'=>'HSBANK','上海农商银行'=>'SHRCB','深圳农商银行'=>'SNXS','其它银行'=>'OTHER',];

                $appKey = $row['business_pwd']; // 秘钥
                //$requestUrl = 'http://pay.jlwl33.com/api/pay/submit_withdrawal';    // 测试地址
                $requestUrl = 'https://api.alipayliving.com/api/pay/submit_withdrawal';    // 生产代付地址
                $inputData = array(
                    'mch_id' => $row['business_code'],                  //商户
                    'order_id' => $user_record_info['Order_Code'],      //商户订单ID 代付订单号
                    'amount' =>$orderAmount,
                    'notify_url' => $row['url'],                        //回调地址，为空则不接受回调
                    'passwd' => MD5('hg789789'),                   //安全密码 111111
                    'bank' => $autopayBank[$myrow['Bank_Name']],        //支付银行 银行代码
                    'bank_site' => $myrow['Bank_Address'],              //支行名称
                    'bank_account' => strval($user_record_info['Bank_Account']),  //银行卡号
                    'bank_account_name' => $user_record_info['Name'],    //开户名
                    'time_stamp' => time()+12*60*60,    //格式 时间戳,例 1563863896（取当前时间，该参数参与验证，且请求时间超过60秒的系统会直接拒绝）

                );
                $sign = getSign($inputData, $appKey);
                $inputData['sign'] = $sign;

                $result = send_post($requestUrl, $inputData);
                $resultArr = json_decode($result, true);
                if ($resultArr['code'] === 0){
                    mysqli_query($dbMasterLink,"COMMIT");
                    $err_resonse = array('err' => '0', 'msg' => '星辰下发提交成功！');
                } else {
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => $resultArr['msg']);
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询星辰第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'wanyinpay_cash_autock':
        if (TPL_FILE_NAME=='0086'){
            require_once '../include/auto/wanyin0086/payment_common.php';
        }elseif(TPL_FILE_NAME=='6668'){
            require_once '../include/auto/wanyin/payment_common.php';
        }
        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            mysqli_query($dbMasterLink,"ROLLBACK");
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取万银第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'wanyinpay_cash_autock' and status = 1";
            $result = mysqli_query($dbLink, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!empty($row)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = round($user_record_info['Gold'] * 100 / 100);

                // 查询会员IP
                $sql="select ID,UserName,Alias,Bank_Name,Bank_Address,Bank_Account,LoginIP from ".DBPREFIX.MEMBERTABLE." where ID=".$user_record_info['userid'];
                $result=mysqli_query($dbLink,$sql);
                $myrow=mysqli_fetch_array($result);
                $wanyinBank=["上海农商银行"=>"0116","上海农村商业银行"=>"0151","上海浦东发展银行"=>"1356","上海银行"=>"0128","东亚银行（中国）有限公司"=>"0112","东兴国民村镇银行"=>"9655","东莞农村商业银行"=>"7357","东莞银行"=>"7823","东营银行"=>"6481","中信银行"=>"0118","中原银行"=>"8945","中国信托商业银行"=>"0144","中国农业银行"=>"0105","中国工商银行"=>"0102","中国建设银行"=>"0103","中国民生银行"=>"0106","中国银联"=>"0143","中国银行"=>"0110","九江银行"=>"0165","云南省农村信用社"=>"1234","交通银行"=>"0129","佛山顺德农村商业银行"=>"1543","保定银行"=>"5564","光大银行"=>"0109","兰州银行"=>"5891","兰州银行股份有限公司"=>"5891","兴业银行"=>"0107","内蒙古农村信用社"=>"0234","农村信用合作社"=>"0156","农村信用社"=>"6666","北京农商行"=>"5349","北京农村商业银行"=>"0134","北京银行"=>"0111","华夏银行"=>"0123","南京银行"=>"0137","厦门农商银行"=>"5782","厦门国际银行"=>"6890","厦门银行"=>"9564","台州银行"=>"6162","吉林省农村信用社"=>"5133","吉林银行"=>"7921","哈尓滨银行"=>"7632","嘉兴银行"=>"8510","四川农信用"=>"1213","四川天府银行"=>"5223","四川省农村信用社联合社"=>"1325","城市商业银行"=>"0146","大同银行"=>"1563","大连农商银行"=>"0145","大连农村商业银行"=>"1562","大连银行"=>"4281","天津农商银行"=>"7903","天津农村商业银行"=>"1347","天津银行"=>"3423","威海市商业银行"=>"0124","宁夏银行"=>"3452","宁夏黄河农村商业银行"=>"0542","宁波银行"=>"1378","安徽省农村信用社联合社"=>"0164","富滇银行"=>"0127","山东省农村信用合作社"=>"4562","山西省农村信用合作社"=>"1429","平安银行"=>"0114","广东农村商业银行"=>"1362","广东南粤银行"=>"1258","广东省农村信用合作社"=>"1425","广发银行"=>"0131","广州市农村商业银行"=>"0149","广州市商业银行"=>"0150","广州银行"=>"6294","广西农村信用社"=>"0352","广西北部湾银行"=>"1245","广西壮族自治区农村信用社联合社"=>"1326","广西省农村信用社"=>"6563","廊坊银行"=>"0233","张家口银行"=>"2875","德州银行"=>"7620","徽商银行"=>"0152","思南长征村镇银行"=>"2364","恒丰银行"=>"0003","成都农商银行"=>"0004","成都银行"=>"7463","招商银行"=>"0101","无锡农村商业银行"=>"7439","日照银行"=>"0120","昆仑银行"=>"5689","晋商银行网上银行"=>"8632","杭州银行"=>"7898","柳州银行"=>"3589","桂林银行股份有限公司"=>"3790","武汉农村商业银行"=>"5641","汇丰银行"=>"0154","汉口银行"=>"7892","江南农村商业银行"=>"8429","江苏农信用"=>"0161","江苏省农村信用社联合社"=>"7894","江苏银行"=>"0168","江西农村信用社"=>"1232","江西银行"=>"8953","江门农商银行"=>"2363","沈阳农商银行"=>"6342","河北农信用"=>"0163","河北银行"=>"9342","河南农信"=>"0122","河南农信用"=>"0159","洛阳银行"=>"5428","济宁银行"=>"7509","浙商银行"=>"0135","浙江农信用"=>"0880","浙江泰隆商业银行"=>"0136","浙江省农村信用社"=>"0321","浙江网商银行"=>"7785","浦发银行"=>"0162","海南省农村信用社"=>"7321","深圳发展银行"=>"0132","深圳巿农村商业银行"=>"5632","渤海银行"=>"5678","湖北农信"=>"1210","湖北省农村信用社"=>"7429","湖北银行"=>"6427","湖南农信用"=>"0142","湖洲银行"=>"9654","潍坊银行"=>"6227","潮州农商银行"=>"0166","焦作中旅银行"=>"0213","玉溪市农商银行"=>"1422","甘肃省农村信用社"=>"9233","甘肃银行"=>"2689","盛京银行"=>"4578","福建省农村信用社"=>"2532","花旗银行"=>"0157","苏州银行"=>"0147","西安银行"=>"4289","贵州省农村信用社"=>"2137","贵州银行"=>"9231","贵阳银行"=>"2152","赣州银行"=>"7645","辽宁省农村信用合作社"=>"1523","邮政储蓄银行"=>"0117","郑州银行"=>"7640","鄂尔多斯银行"=>"4532","重庆三峡银行"=>"3429","重庆农村商业银行"=>"7834","长安银行"=>"2335","长沙市商业银行"=>"0153","长沙银行"=>"7289","陕西农信用"=>"1233","青岛农商银行"=>"7593","青海农信用"=>"1236","鞍山银行"=>"5487","顺德农村商业银行"=>"6830","黑龙江农村信用合作社"=>"3451","齐鲁银行"=>"3689",];
                $wanyinErrorCodeList=[
                    "E00001"=>"merchant not found (商户不存在)",
                    "E00002"=>"missing required parameter (缺少参数)",
                    "E00003"=>"sign error (签名档错误)",
                    "E00004"=>"invalid request amount (请求金额无效)",
                    "E00005"=>"merchant_order_no already exists (订单号已存在)",
                    "E00006"=>"insufficient merchant balance (商户馀额不足)",
                    "E00007"=>"invalid bank code (错误的银行代码)",
                    "E00008"=>"maintenance mode (系统维护中)",
                    "E00009"=>"service type is not allowed for the merchant (商户不允许使用此服务类型)",
                    "E00010"=>"unknown service error (未知的服务器错误)",
                    "E00012"=>"ip is not allowed (IP 是不允许拜访，需要加白名单)",
                    "E00013"=>"invalid parameter format (无效的参数格式)",
                    "E00014"=>"no channel is available for this merchant (此商户没有可用的渠道，商户帐号设置错误)",
                    "E00015"=>"order number does not exist (订单号不存在)",
                    "E00016"=>"merchant is over the deposit quota limit (商户充值额度已达上限)",
                    "E00017"=>"get balance API to be used every 5 seconds only (帐户馀额请求请勿在5秒内重复尝试)",
                    "E00018"=>"transaction already exist (交易已经存在)",
                    "E00019"=>"transaction does not exist (交易不存在)",
                    "E00020"=>"invalid transaction status (交易状态无效)",
                    "E00021"=>"third party error (第三方错误)",
                    "E00022"=>"invalid encryption key (无效的加密公钥)",
                    "E00023"=>"missing encryption public (商户公钥未提交)",
                ];
                $appId = $row['business_code'];
                $notifyUrl = $row['url'];
                $payment_class = new payment_class($appId,$notifyUrl);


                //呼叫充值所需要參數
                $bankCode = $wanyinBank[$myrow['Bank_Name']];                //银行代码
                $amount = $orderAmount;                                     //提现金额RMB
                $merchantUser = $user_record_info['userid'].'|'.$appId;              //商户用户号：可自定义
                $merchantOrderNo = $user_record_info['Order_Code'];         //商户订单号
                $card_num = $user_record_info['Bank_Account'];              //银行卡号
                $card_name = $user_record_info['Name'];                     //开户名
                $bank_branch = $myrow['Bank_Address'];
                $bank_province = $myrow['Bank_Address'];
                $bank_city = $myrow['Bank_Address'];

                if (strlen($bankCode)<1){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-7', 'msg' => '会员银行与万银不匹配');
                    exit( json_encode($err_resonse));
                }

                //呼叫提现申请
                $result= $payment_class -> withdrawRequest($bankCode, $card_num, $amount, $merchantUser, $merchantOrderNo, $card_name , $bank_branch, $bank_province, $bank_city);

                //判断返回的字符是否是错误字符 TRUE 错误，FALSE 则提交成功
                $ishas = array_key_exists($result,$wanyinErrorCodeList);
                if ($ishas){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => $wanyinErrorCodeList[$result]);
                }else{
                    mysqli_query($dbMasterLink,"COMMIT");
                    $err_resonse = array('err' => '0', 'msg' => 'wanyinpay提交成功！');
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询wanyinpay第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'xunfupay_cash_autock':
        require_once '../include/auto/xunfu/payment_common.php';
        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取xunfu第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'xunfupay_cash_autock' and status = 1";
            $result = mysqli_query($dbLink, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!empty($row)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = round($user_record_info['Gold'] * 100 / 100);

                // 查询会员IP
                $sql="select ID,UserName,Alias,Bank_Name,Bank_Address,Bank_Account,LoginIP from ".DBPREFIX.MEMBERTABLE." where ID=".$user_record_info['userid'];
                $result=mysqli_query($dbLink,$sql);
                $myrow=mysqli_fetch_array($result);
                $xunfuBank=["上海农商银行"=>"0116","上海农村商业银行"=>"0151","上海浦东发展银行"=>"1356","上海银行"=>"0128","东亚银行（中国）有限公司"=>"0112","东兴国民村镇银行"=>"9655","东莞农村商业银行"=>"7357","东莞银行"=>"7823","东营银行"=>"6481","中信银行"=>"0118","中原银行"=>"8945","中国信托商业银行"=>"0144","中国农业银行"=>"0105","中国工商银行"=>"0102","中国建设银行"=>"0103","中国民生银行"=>"0106","中国银联"=>"0143","中国银行"=>"0110","九江银行"=>"0165","云南省农村信用社"=>"1234","交通银行"=>"0129","佛山顺德农村商业银行"=>"1543","保定银行"=>"5564","光大银行"=>"0109","兰州银行"=>"5891","兰州银行股份有限公司"=>"5891","兴业银行"=>"0107","内蒙古农村信用社"=>"0234","农村信用合作社"=>"0156","农村信用社"=>"6666","北京农商行"=>"5349","北京农村商业银行"=>"0134","北京银行"=>"0111","华夏银行"=>"0123","南京银行"=>"0137","厦门农商银行"=>"5782","厦门国际银行"=>"6890","厦门银行"=>"9564","台州银行"=>"6162","吉林省农村信用社"=>"5133","吉林银行"=>"7921","哈尓滨银行"=>"7632","嘉兴银行"=>"8510","四川农信用"=>"1213","四川天府银行"=>"5223","四川省农村信用社联合社"=>"1325","城市商业银行"=>"0146","大同银行"=>"1563","大连农商银行"=>"0145","大连农村商业银行"=>"1562","大连银行"=>"4281","天津农商银行"=>"7903","天津农村商业银行"=>"1347","天津银行"=>"3423","威海市商业银行"=>"0124","宁夏银行"=>"3452","宁夏黄河农村商业银行"=>"0542","宁波银行"=>"1378","安徽省农村信用社联合社"=>"0164","富滇银行"=>"0127","山东省农村信用合作社"=>"4562","山西省农村信用合作社"=>"1429","平安银行"=>"0114","广东农村商业银行"=>"1362","广东南粤银行"=>"1258","广东省农村信用合作社"=>"1425","广发银行"=>"0131","广州市农村商业银行"=>"0149","广州市商业银行"=>"0150","广州银行"=>"6294","广西农村信用社"=>"0352","广西北部湾银行"=>"1245","广西壮族自治区农村信用社联合社"=>"1326","广西省农村信用社"=>"6563","廊坊银行"=>"0233","张家口银行"=>"2875","德州银行"=>"7620","徽商银行"=>"0152","思南长征村镇银行"=>"2364","恒丰银行"=>"0003","成都农商银行"=>"0004","成都银行"=>"7463","招商银行"=>"0101","无锡农村商业银行"=>"7439","日照银行"=>"0120","昆仑银行"=>"5689","晋商银行网上银行"=>"8632","杭州银行"=>"7898","柳州银行"=>"3589","桂林银行股份有限公司"=>"3790","武汉农村商业银行"=>"5641","汇丰银行"=>"0154","汉口银行"=>"7892","江南农村商业银行"=>"8429","江苏农信用"=>"0161","江苏省农村信用社联合社"=>"7894","江苏银行"=>"0168","江西农村信用社"=>"1232","江西银行"=>"8953","江门农商银行"=>"2363","沈阳农商银行"=>"6342","河北农信用"=>"0163","河北银行"=>"9342","河南农信"=>"0122","河南农信用"=>"0159","洛阳银行"=>"5428","济宁银行"=>"7509","浙商银行"=>"0135","浙江农信用"=>"0880","浙江泰隆商业银行"=>"0136","浙江省农村信用社"=>"0321","浙江网商银行"=>"7785","浦发银行"=>"0162","海南省农村信用社"=>"7321","深圳发展银行"=>"0132","深圳巿农村商业银行"=>"5632","渤海银行"=>"5678","湖北农信"=>"1210","湖北省农村信用社"=>"7429","湖北银行"=>"6427","湖南农信用"=>"0142","湖洲银行"=>"9654","潍坊银行"=>"6227","潮州农商银行"=>"0166","焦作中旅银行"=>"0213","玉溪市农商银行"=>"1422","甘肃省农村信用社"=>"9233","甘肃银行"=>"2689","盛京银行"=>"4578","福建省农村信用社"=>"2532","花旗银行"=>"0157","苏州银行"=>"0147","西安银行"=>"4289","贵州省农村信用社"=>"2137","贵州银行"=>"9231","贵阳银行"=>"2152","赣州银行"=>"7645","辽宁省农村信用合作社"=>"1523","邮政储蓄银行"=>"0117","郑州银行"=>"7640","鄂尔多斯银行"=>"4532","重庆三峡银行"=>"3429","重庆农村商业银行"=>"7834","长安银行"=>"2335","长沙市商业银行"=>"0153","长沙银行"=>"7289","陕西农信用"=>"1233","青岛农商银行"=>"7593","青海农信用"=>"1236","鞍山银行"=>"5487","顺德农村商业银行"=>"6830","黑龙江农村信用合作社"=>"3451","齐鲁银行"=>"3689",];
                $xunfuErrorCodeList=[
                    "E00001"=>"merchant not found (商户不存在)",
                    "E00002"=>"missing required parameter (缺少参数)",
                    "E00003"=>"sign error (签名档错误)",
                    "E00004"=>"invalid request amount (请求金额无效)",
                    "E00005"=>"merchant_order_no already exists (订单号已存在)",
                    "E00006"=>"insufficient merchant balance (商户馀额不足)",
                    "E00007"=>"invalid bank code (错误的银行代码)",
                    "E00008"=>"maintenance mode (系统维护中)",
                    "E00009"=>"service type is not allowed for the merchant (商户不允许使用此服务类型)",
                    "E00010"=>"unknown service error (未知的服务器错误)",
                    "E00012"=>"ip is not allowed (IP 是不允许拜访，需要加白名单)",
                    "E00013"=>"invalid parameter format (无效的参数格式)",
                    "E00014"=>"no channel is available for this merchant (此商户没有可用的渠道，商户帐号设置错误)",
                    "E00015"=>"order number does not exist (订单号不存在)",
                    "E00016"=>"merchant is over the deposit quota limit (商户充值额度已达上限)",
                    "E00017"=>"get balance API to be used every 5 seconds only (帐户馀额请求请勿在5秒内重复尝试)",
                    "E00018"=>"transaction already exist (交易已经存在)",
                    "E00019"=>"transaction does not exist (交易不存在)",
                    "E00020"=>"invalid transaction status (交易状态无效)",
                    "E00021"=>"third party error (第三方错误)",
                    "E00022"=>"invalid encryption key (无效的加密公钥)",
                    "E00023"=>"missing encryption public (商户公钥未提交)",
                ];
                $appId = $row['business_code'];
                $notifyUrl = $row['url'];
                $payment_class = new payment_class($appId,$notifyUrl);


                //呼叫充值所需要參數
                $bankCode = $xunfuBank[$myrow['Bank_Name']];                //银行代码
                $amount = $orderAmount;                                     //提现金额RMB
                $merchantUser = $user_record_info['userid'].'|'.$appId;              //商户用户号：可自定义
                $merchantOrderNo = $user_record_info['Order_Code'];         //商户订单号
                $card_num = $user_record_info['Bank_Account'];              //银行卡号
                $card_name = $user_record_info['Name'];                     //开户名
                $bank_branch = $myrow['Bank_Address'];
                $bank_province = $myrow['Bank_Address'];
                $bank_city = $myrow['Bank_Address'];

                if (strlen($bankCode)<1){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-7', 'msg' => '会员银行与迅付不匹配');
                    exit( json_encode($err_resonse));
                }

                //呼叫提现申请
                $result= $payment_class -> withdrawRequest($bankCode, $card_num, $amount, $merchantUser, $merchantOrderNo, $card_name , $bank_branch, $bank_province, $bank_city);

                //判断返回的字符是否是错误字符 TRUE 错误，FALSE 则提交成功
                $ishas = array_key_exists($result,$xunfuErrorCodeList);
                if ($ishas){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => $xunfuErrorCodeList[$result]);
                }else{
                    mysqli_query($dbMasterLink,"COMMIT");
                    $err_resonse = array('err' => '0', 'msg' => 'xunfupay提交成功！');
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询xunfupay第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'autopay_cash_autock': // HiPay3127
        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取autopay第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'autopay_cash_autock' and status = 1";
            $result = mysqli_query($dbLink, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!empty($row)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = round($user_record_info['Gold'] * 100 / 100);

                // 查询会员IP
                $sql="select ID,UserName,Alias,Bank_Name,Bank_Address,Bank_Account,LoginIP from ".DBPREFIX.MEMBERTABLE." where ID=".$user_record_info['userid'];
                $result=mysqli_query($dbLink,$sql);
                $myrow=mysqli_fetch_array($result);
                $autopayBank=["上海农商银行"=>"17","上海华瑞银行"=>"173","上海浦东发展银行"=>"193","上海银行"=>"13","上饶银行"=>"51","东亚银行（中国）有限公司"=>"26","东莞农村商业银行"=>"40","东莞银行"=>"42","东营银行"=>"52","中信银行"=>"7","中原银行"=>"108","中国农业银行"=>"2","中国工商银行"=>"1","中国建设银行"=>"4","中国民生银行"=>"8","中国银联"=>"194","中国银行"=>"3","中德住房储蓄银行"=>"100","丹东银行"=>"102","乌海银行"=>"127","乌鲁木齐市商业银行"=>"150","乐山市商业银行"=>"126","九江银行股份有限公司"=>"70","云南省农村信用社"=>"134","交通银行"=>"5","企业银行"=>"112","众邦银行"=>"180","保定银行"=>"178","光大银行"=>"9","兰州银行股份有限公司"=>"72","兴业银行"=>"15","内蒙古银行"=>"92","凉山州商业银行"=>"188","包商银行股份有限公司"=>"35","北京农村商业银行"=>"18","北京银行"=>"14","华夏银行"=>"16","华融湘江银行"=>"169 none","南京银行"=>"24","南充市商业银行"=>"161","南宁江南国民村镇银行"=>"192","南昌银行"=>"50","厦门国际银行"=>"166","厦门银行"=>"44","友利银行"=>"117","台州银行"=>"75","吉林银行"=>"91","吴江农村商业银行"=>"133","嘉兴银行清算中心"=>"93","四川天府银行"=>"197","四川新网银行"=>"105","四川省联社"=>"162","大连农村商业银行"=>"184 none","大连银行"=>"62","天津农商银行"=>"37","天津滨海农村商业银行股份有限公司"=>"99","天津银行"=>"19","太仓农商行"=>"118","威海市商业银行"=>"60","宁夏银行"=>"147","宁波东海银行"=>"186","宁波银行"=>"21","宜宾市商业银行"=>"101","富滇银行"=>"151","富邦华一银行"=>"141","山东省农联社"=>"195","平安银行"=>"20","广东华兴银行"=>"137","广东南粤银行股份有限公司"=>"107","广东省农村信用社联合社"=>"122","广发银行"=>"12","广州农村商业银行"=>"87","广州银行"=>"30","广西农村信用社"=>"90","广西北部湾银行"=>"45","广西壮族自治区农村信用社联合社"=>"190","廊坊银行"=>"164","张家港农村商业银行"=>"49","德州银行"=>"146","徽商银行"=>"32","恒丰银行"=>"82","恒生银行"=>"185","成都农商银行"=>"165","成都银行"=>"31","承德银行"=>"153","抚顺银行"=>"84","招商银行"=>"10","攀枝花市商业银行"=>"131","新韩银行中国"=>"41","无锡农村商业银行"=>"96","日照银行"=>"58","昆仑银行"=>"135","昆山农村商业银行"=>"47","晋中银行"=>"168","晋商银行网上银行"=>"77","晋城银行"=>"116","曲靖市商业银行"=>"167","朝阳银行"=>"104","杭州银行"=>"25","枣庄银行"=>"119","柳州银行"=>"156","桂林银行股份有限公司"=>"159","武汉农村商业银行"=>"144","汇丰银行"=>"182","汉口银行"=>"76","江苏常熟农村商业银行"=>"157","江苏江南农村商业银行"=>"123","江苏江阴农村商业银行"=>"95","江苏银行"=>"74","江苏长江商行"=>"139","沧州银行"=>"142","河北银行股份有限公司"=>"81","河南省农村信用社"=>"196","泉州银行"=>"160","泰安市商业银行"=>"59","洛阳银行"=>"155","济宁银行"=>"53","浙商银行"=>"23","浙江民泰商业银行"=>"149","浙江泰隆商业银行"=>"109","浙江省农村信用社"=>"191","浙江稠州商业银行"=>"29","浙江网商银行"=>"171","浦发银行"=>"11","海南银行"=>"125","海口联合农村商业银行"=>"189","深圳农商行"=>"39","深圳前海微众银行"=>"114","渣打银行"=>"181","渤海银行"=>"22","温州民商银行"=>"179","温州银行"=>"67","湖北银行"=>"106","湖州银行"=>"66","潍坊银行"=>"132","烟台银行"=>"61","焦作中旅银行"=>"140","甘肃银行"=>"176","盛京银行"=>"98","石嘴山银行"=>"174","福建海峡银行"=>"128","绍兴银行"=>"145","绵阳市商业银行"=>"158","自贡银行"=>"136","花旗银行"=>"172","苏州银行"=>"48","莱商银行"=>"54","营口沿海银行"=>"177","营口银行"=>"115","葫芦岛银行"=>"65","衡水银行"=>"120","西安银行"=>"94","西藏银行"=>"183","贵州省农村信用社"=>"97","贵州银行"=>"124","贵阳银行"=>"129","赣州银行"=>"113","辽阳银行"=>"175","邢台银行"=>"130","邮政储蓄银行"=>"6","邯郸市商业银行"=>"111","郑州银行"=>"143","鄂尔多斯银行"=>"152","鄞州银行"=>"68","重庆三峡银行"=>"170","重庆农村商业银行"=>"154","重庆富民银行"=>"103","重庆银行股份有限公司"=>"73","金华银行"=>"121","锦州银行"=>"63","长安银行"=>"163","长沙银行"=>"46","长治银行"=>"187","青岛银行"=>"57","青海银行"=>"148","韩亚银行"=>"36","齐商银行"=>"56","齐鲁银行"=>"138","龙江银行"=>"110",];

                $appId = $row['business_code'];
                $appKey = $row['business_pwd'];
                $notifyUrl = $row['url'];
                $requestUrl = 'http://pay-test.autopayla.com/DMAW2KD7/autoPay/sendOrder.zv';
                $inputData = array(
                    'appId' => $appId,
                    'orderType' => '1',
                    'merchOrderNo' => $user_record_info['Order_Code'],         //商户订单号
                    'orderDate' => date('YmdHis'),            //订单提交时间 yyyyMMddHHmmss
                    'amount' => $orderAmount,                       //提现金额RMB
                    'accNo' => $user_record_info['Bank_Account'],              //银行卡号
                    'accName' => $user_record_info['Name'],                        //开户名
                    'bankId' => $autopayBank[$myrow['Bank_Name']],                            //银行代码
                    'notifyUrl' => $notifyUrl,                  //回调地址，为空则不接受回调
                    'clientIp' => $myrow['LoginIP'],               //请求者的ip
                );
                $sign = getSign($inputData, $appKey);
                $inputData['bankBranch'] = $myrow['Bank_Address'];
                $inputData['province'] = $myrow['Bank_Address'];
                $inputData['city'] = $myrow['Bank_Address'];
                $inputData['version'] = 'V1.0';
//                $inputData['merchRemark'] = '请求备注，回调时后原样返回';
                $inputData['signType'] = 'MD5';
                $inputData['sign'] = $sign;
                $result = send_post($requestUrl, $inputData);

                if ($result['code'] === '0000'){
                    mysqli_query($dbMasterLink,"COMMIT");
                    $err_resonse = array('err' => '0', 'msg' => 'Autopay提交成功！');
                } else {
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => $result['msg']);
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询autopay第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'hipay_cash_autock': // HiPay3127
        require_once '../include/hipay/AutoHiPay.php';
        if($id == 0) {
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate = date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if (!mysqli_query($dbMasterLink,$sql_update)) {
            exit(json_encode(['err' => '-3', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from " . DBPREFIX . "web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink, $sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 向第三方发起自动提款
        if ($user_record_info['Type'] == 'T' and $user_record_info['Checked'] == 2 and $user_record_info['Locked'] == 0) {
            // 获取HiPay第三方的信息
            $sql = "select * from " . DBPREFIX . "gxfcy_autopay where method = 'hipay_cash_autock' and status = 1";
            $resultHt = mysqli_query($dbLink, $sql);
            $hiPayInfo = mysqli_fetch_assoc($resultHt);
            if(!empty($hiPayInfo)) {
                $currentDate = date('Y-m-d H:i:s');
                $orderAmount = round($user_record_info['Gold'] * 100 / 100);
                $params = [
                    'merchantNo' => $hiPayInfo['business_code'],            // 商户号
                    'merchantOrderNo' => $user_record_info['Order_Code'],   // 商户订单号
                    'outNo' => $user_record_info['Bank_Account'],           // 当type=1的时候，outNo为银行卡号，当type=2的时候，outNo为支付宝账
                    'outName' => $user_record_info['Name'],                 // 当type=1的时候,outName为银行卡户主姓名，当type=2的时候,outName为支付宝姓名
                    'bankName' => $user_record_info['Bank'],                // 银行名称：当type是1的时候必填
                    'amount' => $orderAmount,                               // 出款金额（金额为整数，不能带小数点）
                    'key' => $hiPayInfo['business_pwd'],                    // 商户密钥
                    'type' => 1,                                            // 支付类型 1：微信 2：支付宝，仅支持1
                    'notifyUrl' => $hiPayInfo['url']                        // 回调地址
                ];

                $autoHiPayObj = new AutoHiPay($params);
                $result = $autoHiPayObj->payAction();

                if ($result['result'] === true){
                    mysqli_query($dbMasterLink,"COMMIT");
                    $err_resonse = array('err' => '0', 'msg' => 'HiPay3721提交成功！');
                } else {
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-6', 'msg' => $result['msg']);
                }
            } else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-5', 'msg' => '查询HiPay第三方失败!');
            }
        } else {
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-4', 'msg' => '订单已经被处理，不要重复提交！');
        }

        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>';
        innsertSystemLog($_SESSION['UserName'], $lv, $loginfo);
        exit( json_encode($err_resonse));
        break;
    case 'zspay_cash_autock': // 泽圣下发
        if($id==0){
            exit(json_encode(['err' => '-2', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0){

            $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
            if($resultMem) {
                // 向第三方发起自动提款
                //获取泽圣第三方的信息
                $sqlZs=" select * from ".DBPREFIX."autopay where method = 'zspay_cash_autock' and status = 1" ;
                $resultZs = mysqli_query($dbLink,$sqlZs);
                $zsinfo = mysqli_fetch_assoc($resultZs);
                if(!empty($zsinfo)) {
                    $merchantCode = $zsinfo["business_code"];
                    $md5Key = $zsinfo["business_pwd"];
                    $intoCardName = preg_replace("/[\d一二三四五六七八九十]/u","",$user_record_info['Name']);
                    $intoCardNo = $user_record_info['Bank_Account'];
                    $bankCode = "";
                    $bankName = "";
                    $intoCardType = "2"; // 1-对公 2-对私
                    $remark = "jiesuan";
                    $type = "04"; // 03-非实时付款到银行卡;04-实时付款到银行卡
                    $nonceStr=MD5(time());//随机生成，
                    $outOrderId=$user_record_info['Order_Code'];//商户订单号
                    $outOrderId = str_replace("_", "-", $outOrderId);
                    $totalAmount=$user_record_info['Gold']*100;//金额
                    $notifyUrl=$zsinfo["url"];

                    //测试地址
                    $url1 = "http://192.168.0.12:7798/payment/payment.do";
                    //正式地址
//                    $url1 = 'http://spayment.zsagepay.com/payment/payment.do';

                    // 参与签名字段
                    $sign_fields1 = Array(
                        "bankCode",
                        "bankName",
                        "intoCardName",
                        "intoCardNo",
                        "intoCardType",
                        "merchantCode",
                        "nonceStr",
                        "outOrderId",
                        "totalAmount",
                        "type"
                    );

                    $map1 = Array(
                        "bankCode" => $bankCode,
                        "bankName" => $bankName,
                        "intoCardName" => $intoCardName,
                        "intoCardNo" => $intoCardNo,
                        "intoCardType" => $intoCardType,
                        "merchantCode" => $merchantCode,
                        "nonceStr" => $nonceStr,
                        "outOrderId" => $outOrderId,
                        "totalAmount" => (int)$totalAmount,
                        "type" => $type
                    );

                    $sign0 = sign_mac($sign_fields1, $map1, $md5Key);
                    // 将小写字母转成大写字母
                    $sign1 = strtoupper($sign0);

                    // 使用方法
                    $post_data1 = array(
                        'bankCode' => $bankCode,
                        'bankName' => $bankName,
                        'intoCardName' => $intoCardName,
                        'intoCardNo' => $intoCardNo,
                        'intoCardType' => $intoCardType,
                        'merchantCode' =>$merchantCode,
                        'nonceStr' => $nonceStr,
                        'outOrderId' => $outOrderId,
                        'totalAmount' => (int)$totalAmount,
                        'type' => $type,
                        'remark' => $remark,
                        'sign' => $sign1,
                        'notifyUrl'  => $notifyUrl
                    );
                    $res = send_post($url1, $post_data1);
                    $res = json_decode($res);
                    if($res->code == "00") {
                        $mysql="update ".DBPREFIX.MEMBERTABLE." set WithdrawalTimes=WithdrawalTimes+1 where ID='".$user_record_info['userid']."'";
                        if(mysqli_query($dbMasterLink,$mysql)) {
                            // 更新订单状态
                            $reviewDate=date('Y-m-d H:i:s');
                            $is_auto = 1;
                            $is_auto_flag = 1;
                            $sql_update = "update ".DBPREFIX."web_sys800_data set Checked=1,is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
                            if(mysqli_query($dbMasterLink,$sql_update)){
                                $res = level_deal($user_record_info['userid'],$user_record_info['Gold'],1);//用户层级关系处理
                                if($res){
                                    mysqli_query($dbMasterLink,"COMMIT");

                                }else{
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    $err_resonse = array('err' => '-8', 'msg' => '用户层级关系处理失败!');

                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => '提款成功，更新提款状态失败!');
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '更新提款次数失败!');
                        }
                    }else{
                        // 更新订单状态
                        $resp_desc = "自动出款失败";
                        $reviewDate=date('Y-m-d H:i:s');
                        $is_auto = 1;
                        $is_auto_flag = 0;
                        $sql_update = "update ".DBPREFIX."web_sys800_data set Checked=1,is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}',auto_memo='{$resp_desc}' WHERE `ID` = {$id}";
                        if(mysqli_query($dbMasterLink,$sql_update)){
                            mysqli_query($dbMasterLink,"COMMIT");
                            $err_resonse = array('err' => '0', 'success');
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-10', 'msg' => '自动出款失败，更新提款状态失败!');
                        }
                        $err_resonse = array('err' => '-9', 'msg' => '自动出款失败，请查看失败原因并手动出款！!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-5', 'msg' => '查询泽圣第三方失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
            }
        }
        else{

            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理!');

        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);

        exit( json_encode($err_resonse) );

        break;
    case 'jtpay_cash_autock': // 久通下发
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate=date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if(!mysqli_query($dbMasterLink,$sql_update)){
            exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 久通代付支持银行代码字典：
        $jtXiafaBankList=array(
            'BOC'=>'中国银行',
            'ABC'=>'中国农业银行',
            'ICBC'=>'中国工商银行',
            'CCB'=>'中国建设银行',
            'BCM'=>'交通银行',
            'CMB'=>'中国招商银行',
            'CEB'=>'中国光大银行',
            'CMBC'=>'中国民生银行',
            'HXB'=>'华夏银行',
            'CIB'=>'兴业银行',
            'CNCB'=>'中信银行',
            'SPDB'=>'浦发银行',
            'PSBC'=>'邮政储蓄银行',
        );
        $bankCode = array_search( $user_record_info['Bank'], $jtXiafaBankList);
        if ($bankCode != false){

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
    
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 向第三方发起自动提款
                    //获取久通第三方的信息
                    $sqlJt=" select * from ".DBPREFIX."gxfcy_autopay where method = 'jtpay_cash_autock' and status = 1" ;
                    $resultJt = mysqli_query($dbLink,$sqlJt);
                    $jtinfo = mysqli_fetch_assoc($resultJt);
                    if(!empty($jtinfo)) {

                        $amount = floatval($user_record_info['Gold'])*100;
                        $data['version']="V3.1.0.0";
                        $data['merNo']=$jtinfo["business_code"];
                        $data['orderNum']= $user_record_info['Order_Code'];
                        $data['amount']=''.$amount.'';
                        $data['bankCode']=$bankCode;
                        $data['bankAccountName']=$user_record_info['Name'];
                        $data['bankAccountNo']=$user_record_info['Bank_Account'];
                        $data['callBackUrl']=$jtinfo['url'];
                        $data['charset']="UTF-8";

                        $key = $jtinfo['business_pwd'];

                        $data['sign']=create_sign($data,$key);//签名（字母大写）	32	是

                        //转成json字符串
                        $json = json_encode_ex($data);

                        //代付 公钥字符串
                        if($jtinfo["business_code"] == 'JTZF800836') { // 6668(98985)商户 RSA代付加密公钥
                            $remit_public_key_str="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC2uzK1hXP/ackPkmoCpOPcL6Z4tcZYTndCe5pI6/LHgsd9EZHFNGsr62gSs+1AiVQl2EDFVXolRLj67eissCmJ0CoCgzUS3VnlziRaUCeMd/tTtzB/9UJE7trvxEPwRhQiUtina4qlluIb8X+0GV7hP8HlfOjX8akwOAS2TPMwxwIDAQAB";
                        } elseif($jtinfo["business_code"] == 'JTZF800837'){ // 0086(7557)商户 RSA代付加密公钥
                            $remit_public_key_str="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDE9K6bkkwhmOFu2P5/K5k1ts4RJMw0f0Prpco2x9Kf0ors1R+JMccyprYPYuBpdpJa55GnGTie2J/MSdj5qoa5ltzWxnuoFq6T43vMuXFIJQ/e6ptYQQUnSkfvpPPQWhnvBeJZa9JEYM2DWirjsCJcgwGJcmUxyGgHzLcIRNTb3wIDAQAB";
                        }
                        $remit_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
                        foreach (str_split($remit_public_key_str,64) as $str){
                            $remit_public_key .=  $str . "\r\n";
                        }
                        $remit_public_key .= "-----END PUBLIC KEY-----";

                        //加密
                        $dataStr =encode_pay($json,$remit_public_key);

                        //请求原文
                        $param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version='.$data['version'];

                        //代付请求地址
//                        $remit_url="http://120.79.47.213:8083/api/remit.action"; // 测试
                        $remit_url="http://api.jiutongpay.com/api/remit.action"; // 正式


                        //发起请求
                        $result = wx_post($remit_url,$param);

                        //效验 sign;
                        $rows = json_to_array($result,$key);

                        if ($rows['stateCode'] == '00'){
//                            echo "代付创建成功,以下是订单数据</br>";
//                            var_dump($rows);

                            $err_resonse = array('err' => '0', 'msg' => $rows['stateCode']);
                            mysqli_query($dbMasterLink,"COMMIT");
//                            // 更新订单状态
//                            $reviewDate=date('Y-m-d H:i:s');
//                            $is_auto = 1;
//                            $is_auto_flag = 1;
//                            $sql_update = "update ".DBPREFIX."web_sys800_data set Checked=1,is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
//                            if(mysqli_query($dbMasterLink,$sql_update)){
//                                $res = level_deal($user_record_info['userid'],$user_record_info['Gold'],1);//用户层级关系处理
//                                if($res){
//                                    mysqli_query($dbMasterLink,"COMMIT");
//
//                                }else{
//                                    mysqli_query($dbMasterLink,"ROLLBACK");
//                                    $err_resonse = array('err' => '-8', 'msg' => '用户层级关系处理失败!');
//                                }
//                            }else{
//                                mysqli_query($dbMasterLink,"ROLLBACK");
//                                $err_resonse = array('err' => '-7', 'msg' => '提款成功，更新提款状态失败!');
//                            }

                        }else{
//                            echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];
                            $err_resonse = array('err' => '-9', 'msg' => "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg']);
                            mysqli_query($dbMasterLink,"COMMIT");
                            // 更新订单状态
//                            $resp_desc = "自动出款失败";
//                            $reviewDate=date('Y-m-d H:i:s');
//                            $is_auto = 1;
//                            $is_auto_flag = 0;
//                            $sql_update = "update ".DBPREFIX."web_sys800_data set Checked=1,is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}',auto_memo='{$resp_desc}' WHERE `ID` = {$id}";
//                            if(mysqli_query($dbMasterLink,$sql_update)){
//                                mysqli_query($dbMasterLink,"COMMIT");
//                                $err_resonse = array('err' => '0', 'success');
//                            }else{
//
//                                mysqli_query($dbMasterLink,"ROLLBACK");
////                                $err_resonse = array('err' => '-10', 'msg' => '自动出款失败，更新提款状态失败!');
//
//                            }
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询久通第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }
            else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持自动出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);

        exit( json_encode($err_resonse) );

        break;
    case 'htpay_cash_autock': // 汇通下发
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate=date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if(!mysqli_query($dbMasterLink,$sql_update)){
            exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        $htXiafaBankList = array(
            'ABC'=>'中国农业银行',
            'BOC'=>'中国银行',
            'BOCOM'=>'交通银行',
            'CCB'=>'中国建设银行',
            'ICBC'=>'中国工商银行',
            'PSBC'=>'邮政储蓄银行',
            'CMBC'=>'招商银行',
            'SPDB'=>'浦发银行',
            'CEBBANK'=>'中国光大银行',
            'ECITIC'=>'中信银行',
            'PINGAN'=>'平安银行',
            'CMBCS'=>'中国民生银行',
            'HXB'=>'华夏银行',
            'CGB'=>'广发银行',
            'BCCB'=>'北京银行',
            'CIB'=>'兴业银行',
        );
        $bankCode = array_search( $user_record_info['Bank'], $htXiafaBankList);
        if ($bankCode != false){
            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {

                // 向第三方发起自动提款
                //获取汇通第三方的信息
                $sqlHt=" select * from ".DBPREFIX."gxfcy_autopay where method = 'htpay_cash_autock' and status = 1" ;
                $resultHt = mysqli_query($dbLink,$sqlHt);
                $htinfo = mysqli_fetch_assoc($resultHt);
                if(!empty($htinfo)) {
                    $currentDate = date('Y-m-d H:i:s');
                    $key = $htinfo['business_pwd'];
                    $orderAmount=round($user_record_info['Gold']*100/100);
                    $kvs = new KeyValues();
                    $kvs->setkey($key);
                    $kvs->add(AppConstants::$BANK_CODE, $bankCode);
                    $kvs->add(AppConstants::$MERCHANT_CODE, $htinfo['business_code']);
                    $kvs->add(AppConstants::$TRADE_NO, $user_record_info['Order_Code']);
                    $kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
                    $kvs->add(AppConstants::$ORDER_TIME, $currentDate);
                    $kvs->add(AppConstants::$ACCOUNT_NAME, $user_record_info['Name']);
                    $kvs->add(AppConstants::$ACCOUNT_NUMBER, $user_record_info['Bank_Account']);

                    $sign = $kvs->sign();

                    $gatewayUrl = 'https://api.huitongvip.com/remit.html';
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$MERCHANT_CODE, $htinfo['business_code'],false);
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_NO, $orderNo);
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_AMOUNT, $orderAmount);
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_TIME, $currentDate);
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$ACCOUNT_NAME, urlencode($user_record_info['Name']));
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$ACCOUNT_NUMBER, $user_record_info['Bank_Account'], true);
//                    URLUtils::appendParam($gatewayUrl, AppConstants::$SIGN, $sign);
                    $param = 'merchant_code='.$htinfo['business_code'].'&trade_no='.$user_record_info['Order_Code'].'&order_amount='.$orderAmount.'&order_time='.$currentDate.'&bank_code='.$bankCode.'&account_name='.$user_record_info['Name'].'&account_number='.$user_record_info['Bank_Account'].'&sign='.$sign;
                    //发起请求
                    $result = wx_post($gatewayUrl,$param);
                    $array=json_decode($result,true);
                    if ($array['is_success']){
                        $sign_string = $array['sign'];

                        // bank_status 0 未处理，1 银行处理中 2 已打款 3 失败
                        if($array['bank_status'] == 0){
                            $err_resonse = array('err' => '-7', 'msg' => '汇通->未处理');
                        }elseif($array['bank_status'] == 1){
                            $err_resonse = array('err' => '-8', 'msg' => '汇通->银行处理中');
                        }elseif($array['bank_status'] == 3){
                            $err_resonse = array('err' => '-9', 'msg' => '汇通->失败');
                        }elseif($array['bank_status'] == 2){

                            mysqli_query($dbMasterLink,"COMMIT");
                            $err_resonse = array('err' => '0', 'msg' => '汇通->已打款');
                        }

                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => $array['errror_msg']);
                    }
                }
                else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询汇通第三方失败!');
                }
            }
            else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }
        else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持自动出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'mayipay_cash_autock': // 蚂蚁代付
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate=date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if(!mysqli_query($dbMasterLink,$sql_update)){
            exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
        }
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        $myremit = new myremit();
        $myXiafaBankList = $myremit->getBank();
        $bankCode = array_search( $user_record_info['Bank'], $myXiafaBankList);
//        print_r($bankCode);die;
        if ($bankCode != false){
            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {

                // 向第三方发起自动提款
                //查询蚂蚁代付的信息
                $sqlMy=" select * from ".DBPREFIX."gxfcy_autopay where method = 'mayipay_cash_autock' and status = 1" ;
                $resultMy = mysqli_query($dbLink,$sqlMy);
                $myinfo = mysqli_fetch_assoc($resultMy);
                if(!empty($myinfo)) {
                    $orderAmount=number_format($user_record_info['Gold']*100/100,2);
                    $orderAmount=str_replace(',','',$orderAmount);
                    $para = array(
                        'private_rsa'=>$myinfo['business_pwd'],
                        'cashId'=>$user_record_info['Order_Code'],
                        'amount'=>$orderAmount,
                        'accNo'=>$user_record_info['Bank_Account'],
                        'bankCode'=>$bankCode,
                        'accName'=>$user_record_info['Name'],
                        'callBackUrl'=>urlencode($myinfo['url']),
                        'md5'=>$myinfo['business_pwd'],
                        'mer_no'=>$myinfo['business_code'],
                        //'getway'=>'http://putorder.daxiangpaypay.com/service/getway/index',
                        'getway'=>'https://putorder.aerfapay.com/service/getway/index',
                    );
//                    print_r($para);die;

                    $aErrorCode = array(
                        '1000'=>'请求参数错误',
                        '1001'=>'签名错误',
                        '1002'=>'转账金额错误',
                        '1003'=>'手机号错误',
                        '1004'=>'银行编码错误',
                        '1005'=>'Api订单号已存在',
                        '1006'=>'没有可转账的银行卡',
                        '1007'=>'异步通知地址错误',
                        '1008'=>'手续费不足',
                        '1009'=>'手续费扣减失败',
                        '1010'=>'代付金不足',
                        '1011'=>'代付金扣减失败',
                        '2000'=>'商户唯一标识错误',
                        '5000'=>'系统忙,提交失败',
                        '6000'=>'请调用查询接口,确定api_sn订单状态',
                        '3000'=>'请求超时',
                        '9999'=>'服务器维护',
                        '0000'=>'系统异常错误',
                    );
                    $array = $myremit->doRemit($para);
                    if ($array['status']){
                        mysqli_query($dbMasterLink,"COMMIT");
                        $err_resonse = array('err' => '0', 'msg' => '蚂蚁代付已提交');
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => $aErrorCode[$array['errorCode']].':'.$array['msg']);
                    }
                }
                else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询蚂蚁第三方失败!');
                }
            }
            else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }
        else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持自动出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">确认出款</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'sftpay_cash_autock': // 顺丰通下发

        break;
    case 'xefpay_cash_autock': // 新E付下发

        break;
    case 'pay_cash_autock': // 必付下发

        break;
    case 'bthpay_cash_autock': // BTH自动出款
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        //首先更新订单状态，如果订单状态更新成功，才进行下一步
        $is_auto = 1;
        $is_auto_flag = 2;
        $reviewDate=date('Y-m-d H:i:s');
        $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
        if(!mysqli_query($dbMasterLink,$sql_update)){
            exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // BTH代付支持银行代码：
        $bthXiafaBankList=array(
            'ABC'=>'中国农业银行',
            'BJBANK'=>'北京银行',
            'BJRCB'=>'北京农商银行',
            'BOC'=>'中国银行',
            'CEB'=>'中国光大银行',
            'CIB'=>'兴业银行',
            'CITIC'=>'中信银行',
            'CMBC'=>'中国民生银行',
            'ICBC'=>'中国工商银行',
            'NBBANK'=>'宁波银行',
            'SPABANK'=>'平安银行',
            'HXBANK'=>'华夏银行',
            'SPDB'=>'浦发银行',
            'PSBC'=>'邮政储蓄银行',
            'HZCB'=>'杭州银行',
            'COMM'=>'交通银行',
            'CMB'=>'招商银行',
            'CCB'=>'中国建设银行',
            'GDB'=>'广发银行',
        );
        $bankCode = array_search( $user_record_info['Bank'], $bthXiafaBankList);
        if ($bankCode != false){

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0

                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取BTH第三方的信息
                    $sqlBth=" select * from ".DBPREFIX."gxfcy_autopay where method = 'bthpay_cash_autock' and status = 1" ;
                    $resultBth = mysqli_query($dbLink,$sqlBth);
                    $bthinfo = mysqli_fetch_assoc($resultBth);
                    if(!empty($bthinfo)) {
                        // 请求数据
                        $data['merchant_name']=$bthinfo['business_code']; //商户名
                        $data['input_charset']="UTF-8"; //字符编码
                        $amount = bcmul(floatval($user_record_info['Gold']),1,2); // 交易金额 元
                        $data['trans_amount']=''.$amount.'';    // 交易金额
                        $data['merchant_order_id']= $user_record_info['Order_Code']; //商户名订单ID
                        $data['bank_code']=$bankCode;   //银行编码
                        $data['type']="b2c";    //充值类型
                        $data['return_params']="BTH";  //自定义参数
                        $data['bank_card_number']=$user_record_info['Bank_Account']; //银行卡号
                        $data['bank_card_name']=$user_record_info['Name'];//持卡人姓名
                        $data['notify_url']=$bthinfo['url']; //代付通知地址
                        $data['timestamp'] = time();

                        $key_val=$bthinfo['business_pwd'];
                        /* 签名   对参数进行赋值 */
                        $kvs = new KeyBthValues($key_val);
                        $kvs->add(BthConstants::$MERCHANT_NAME, $data['merchant_name']);
                        $kvs->add(BthConstants::$INPUT_CHARSET, CHARSET);
                        $kvs->add(BthConstants::$TRANNS_AMOUNT, $data['trans_amount']);
                        $kvs->add(BthConstants::$MERCHANT_ORDER_ID, $data['merchant_order_id']);
                        $kvs->add(BthConstants::$BANK_CODE, $data['bank_code']);
                        $kvs->add(BthConstants::$TYPE, $data['type']);
                        $kvs->add(BthConstants::$RETURN_PARAMS, $data['return_params']);
                        $kvs->add(BthConstants::$BANK_CARD_NUMBER, $data['bank_card_number']);
                        $kvs->add(BthConstants::$BANK_CARD_NAME,  $data['bank_card_name']);
                        $kvs->add(BthConstants::$NOTIFY_URL, $data['notify_url']);
                        $kvs->add(BthConstants::$TIMESTAMP, $data['timestamp']);
                        /* 获取签名值 */
                        $firstsign = $kvs->sign();
                        //签名要求：代付接口，第一次MD5签名之后得到的结果加上商户名称，再次MD5，结果为sign的值    MD5（Md5(string)+merchant_name）
                        $data['sign'] = MD5($firstsign.$bthinfo['business_code']);

                        $gatewayUrl = AGENT_PAY_URL.'/pay/gateway';   //代付地址
                        $result = wx_post($gatewayUrl,$data);  //发起请求
                        $array=json_decode($result,true); //响应报文

                        if ($array['code'] == 1){   //代付数据提交成功
                            $returndata = $array['data'];
                            // status 1已提交2处理中3代付成功4代付失败5异常(不能失败处理)
                            if($returndata['status']==1 || $returndata['status']==2 || $returndata['status']==3){
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => 'BTH自动出款已提交,处理中!');
                            }elseif($returndata['status']==4 || $returndata['status']==5){
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => 'BTH自动出款失败,代付异常!');
                            }
                        }else{  //代付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '代付数据提交失败!');
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询BTH第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }
            else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持BTH自动出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);

        exit( json_encode($err_resonse) );
        break;
    case 'ybpay_cash_autock': // 云宝自动出款
        include_once ("../include/yunbao/utils/yunbao_object.php");   // 云宝
        include_once ("../include/yunbao/yunbao.config.php");   // 云宝商户信息配置
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 云宝代付支持银行代码：
        $ybXiafaBankList=array(
            'ABC'=>'中国农业银行','CIB'=>'兴业银行','BOC'=>'中国银行', 'GZCB'=>'广州银行', 'CCB'=>'中国建设银行',
            'SRCB'=>'上海农村商业银行', 'ICBC'=>'中国工商银行','BOB'=>'北京银行', 'BCOM'=>'交通银行','CBHB'=>'渤海银行',
            'CMB'=>'招商银行','BJRCB'=>'北京农商银行','PSBC'=>'邮政储蓄银行','NJCB'=>'南京银行', 'SPDB'=>'浦发银行',
            'BEA'=>'东亚银行','CEB'=>'光大银行', 'NBCB'=>'宁波银行','CITIC'=>'中信银行','HZB'=>'杭州银行',
            'CMBC'=>'中国民生银行','HSB'=>'徽商银行','PAB'=>'平安银行','CZB'=>'浙商银行','HXB'=>'华夏银行',
            'SHB'=>'上海银行','GDB'=>'广发银行','DLB'=>'大连银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $ybXiafaBankList); // ICBC
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取云宝出款的信息
                    $sqlYb=" select * from ".DBPREFIX."gxfcy_autopay where method = 'ybpay_cash_autock' and status = 1" ;
                    $resultYb = mysqli_query($dbLink,$sqlYb);
                    $ybinfo = mysqli_fetch_assoc($resultYb);
                    if(!empty($ybinfo)) {
                        $reqTm = date("YmdHis"); // 提交时间 YmdHis
                        //$reqNo = "REQ" . $reqTm;  // 交易流水号
                        //公共参数
                        $data = array(
                            "version" => $version,
                            "merchantId" => $ybinfo['business_code'], // 商户号
                            "charset" => $charset,
                            "signType" => $signtype,    //签名类型
                            "cipher" => ""
                        );
                        //业务参数
                        $payload = array(
                            "reqNo" => $user_record_info['Order_Code'],  //商户请求流水号
                            "reqTime" => $reqTm,  //提交时间
                            "transAmt" => bcmul(floatval($user_record_info['Gold'])*100, 1, 2),  // 交易金额: 分
                            "acctName" => $user_record_info['Name'],//收款人
                            "acctNo" => $user_record_info['Bank_Account'], //收款人银行账号
                            "acctType" => "1",  //1-对私，2-对公  为空默认对私
                            "bankCode" => $bankCode,    //收款人开户行代码
                            "province" => "",
                            "city" => "",
                            "branchBankName" => "",
                            "notifyUrl" => $ybinfo['url'], // 异步回调地址
                        );
                        $sign = "";
                        $plainReqPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

                        // private_key.pem 商户私钥  platform_public_key.pem  云宝平台RSA公钥  长度2048
                        //$yb = new yb_object($yunbao_config['private_key_path'], $yunbao_config['yunbao_public_key_path']);
                        if($ybinfo['business_code'] == '100000181') {
                            $yb = new yb_object("../include/yunbao/certs/private_key.pem", "../include/yunbao/certs/platform_public_key.pem");
                        }elseif($ybinfo['business_code'] == '100000200'){
                            $yb = new yb_object("../include/yunbao/certs/private_key_200.pem", "../include/yunbao/certs/platform_public_key.pem");
                        }

                        $data["cipher"] = $yb->rsaEncrypt($plainReqPayload);  //业务参数转换成json 云宝平台rsa公钥加密,生成请求业务参数密文
                        ksort($data);  //根据键，以升序对关联数组进行排序

                        //data 公共参数生成签名字符串
                        $signstr = implode('&',array_map(function($key,$value){
                            return $key.'='.$value;
                        },array_keys($data),$data));

                        $data["sign"] = $yb->rsaSign($signstr);   //私钥进行签名

                        $postdata = http_build_query($data); //x-www-form-urlencoded方式有返回结果
                        $respstring = $yb->doPost($api_pay, $postdata);
                        //$respstring = wx_post($api_pay, $data);
                        $respData = json_decode($respstring, true);

                        //代付数据返回参数
                        if($respData["code"] == '00000') {
                            $respEncPayload = $respData["cipher"];//密文数据
                            /*{"reqNo":"REQ20180907232157","transNo":"10000000004493815","transAmt":100,"status":0,"transTime":"20180908112201"}*/
                            $respPlainPayload = $yb->rsaDecrypt($respEncPayload); //解密返回明文数据
                            $respPlainPayData = json_decode($respPlainPayload, true);
                            // status 代付状态  0-处理中  1-支付成功  2-支付失败，己退汇
                            if($respPlainPayData['status '] ==0 || $respPlainPayData['status '] ==1){
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => '云宝自动出款已提交,处理中!');
                            }elseif($respPlainPayData['status '] ==2){
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => '云宝自动出款失败,代付异常!');
                            }
                        }else{  //代付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => $respData['msg']);
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询云宝第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持云宝出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'huitaopay_cash_autock': // 汇淘自动出款
        include_once ("../include/huitao/PayUtils.php");   // 汇淘
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 汇淘代付支持银行代码：
        $huitaoXiafaBankList=array(
            'BANK_ABC'=>'中国农业银行','BANK_BOC'=>'中国银行','BANK_BOCOM'=>'交通银行','BANK_CCB'=>'中国建设银行','BANK_ICBC'=>'中国工商银行','BANK_PSBC'=>'邮政储蓄银行','BANK_CMB'=>'招商银行','BANK_SPDB'=>'浦发银行','BANK_CEB'=>'光大银行','BANK_CITIC'=>'中信银行','BANK_PAB'=>'平安银行','BANK_CMBC'=>'中国民生银行','BANK_HXBC'=>'华夏银行','BANK_GDB'=>'广发银行','BANK_BOBJ'=>'北京银行','BANK_BOS'=>'上海银行','BANK_CIB'=>'兴业银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $huitaoXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取汇淘出款的信息
                    $sqlHt=" select * from ".DBPREFIX."gxfcy_autopay where method = 'huitaopay_cash_autock' and status = 1" ;
                    $resultHt = mysqli_query($dbLink,$sqlHt);
                    $htinfo = mysqli_fetch_assoc($resultHt);
                    if(!empty($htinfo)) {
                        //基本参数
                        $postParams["MerNo"]        = $htinfo['business_code'];  //商户号
                        $postParams["Version"]      = "V2.5.1";   //版本号
                        $postParams["ServerName"]   = "hcTransferPay";  // 服务名称 汇淘上送统一接口
                        $postParams["ReqTime"]      = date('Y-m-d H:i:s',time());   // 发送时间
                        $postParams["SignType"]     = "MD5";    // 签名方式

                        //业务参数
                        $postParams["TransId"]      = $user_record_info['Order_Code'];  //   商户名订单ID 代付订单号
                        $postParams["Amount"]       = bcmul(floatval($user_record_info['Gold']),1,2); // 汇淘金额 元
                        $postParams["BankCode"]     = $bankCode;   // 银行编码
                        $postParams["BusType"]      = "PRV";    //PUB-对公，PRV-对私
                        $postParams["AccountName"]  = $user_record_info['Name'];//收款人姓名
                        $postParams["CardNo"]       = $user_record_info['Bank_Account'];   // 收款卡号
                        $postParams["ReturnRemark"] = '汇淘出款'; // 回传参数  $user_record_info['reason']
                        $postParams["NotifyURL"]    = $htinfo['url']; //回调地址
                        $postParams["SignInfo"] = md5Sign($postParams, $htinfo['business_pwd'], "UTF-8");  // 参数 商户密钥 字符集

                        //$gatewayUrl = "https://pay.huitaopay.com/gwapi/transfer"; //支付接口地址
                        $gatewayUrl = "https://transfer.yeemapay.com/gwapi/transfer"; //汇淘最新支付接口地址
                        $result = wx_post($gatewayUrl,$postParams);  //发起请求
                        $array=json_decode($result,true); //响应报文

                        if ($array['ResCode'] == 'RESPONSE_SUCCESS'){   //汇淘代付数据提交成功
                            $returndata = $array['RespContent'];
                            //TransStatus代付订单状态     ok 代付成功  fail 代付失败 transing 银行处理中
                            if($returndata['TransStatus']=='ok' || $returndata['TransStatus']=='transing'){
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => '汇淘自动出款已提交,处理中!');
                            }elseif($returndata['TransStatus']=='fail'){
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => '汇淘自动出款异常,代付失败!');
                            }
                        }elseif($array['ResCode'] == 'ILLEGAL_CARD_BIN_ERROR'){  //代付银行卡BIN信息未找到
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-9', 'msg' => '汇淘代付银行卡BIN信息未找到!');
                        }elseif($array['ResCode'] == 'PARTNER_ACCOUNT_BALANCE_PRIVILEGE'){  //代付商户账户余额不足
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-8', 'msg' => '汇淘代付商户账户余额不足!');
                        }else{  //代付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '汇淘代付数据提交失败!');
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询汇淘第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持汇淘银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'dfpay_cash_autock': // 多付自动出款
        include_once ("../include/huitao/PayUtils.php");   // //多付引用汇淘文件 用于md5加密
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 多付支持银行代码：
        $duofuXiafaBankList=array(
            'BANK_CCB'=>'中国建设银行','BANK_CMB'=>'招商银行','BANK_ICBC'=>'中国工商银行','BANK_BOC'=>'中国银行','BANK_ABC'=>'中国农业银行','BANK_BOCOM'=>'交通银行','BANK_HXBC'=>'华夏银行','BANK_CMBC'=>'中国民生银行','BANK_CIB'=>'兴业银行','BANK_SPDB'=>'浦发银行','BANK_GDB'=>'广发银行','BANK_CITIC'=>'中信银行','BANK_CEB'=>'光大银行','BANK_PAB'=>'平安银行','BANK_PSBC'=>'邮政储蓄银行','BANK_BEA'=>'东亚银行','BANK_BOS'=>'上海银行','BANK_BOBJ'=>'北京银行','BANK_SRCB'=>'上海农商银行','BANK_BJRCB'=>'北京农商银行','BANK_NBCB'=>'宁波银行','BANK_ZJTLCB'=>'浙江泰隆商业银行','BANK_HZCB'=>'杭州银行','BANK_BON'=>'南京银行','BANK_TCCB'=>'天津银行','BANK_CBHB'=>'渤海银行','BANK_CZB'=>'浙商银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $duofuXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取多付出款的信息
                    $sqlDf=" select * from ".DBPREFIX."gxfcy_autopay where method = 'dfpay_cash_autock' and status = 1" ;
                    $resultDf = mysqli_query($dbLink,$sqlDf);
                    $dfinfo = mysqli_fetch_assoc($resultDf);
                    if(!empty($dfinfo)) {
                        //基本参数
                        $postParams["version"]      = "V1.0.5";
                        $postParams["serviceName"]  = "openTransferPay"; // 服务名称
                        $postParams["reqTime"]      = date('Y-m-d H:i:s', time()+12*60*60);
                        $postParams["merchantId"]   = $dfinfo['business_code'];

                        //业务参数
                        $postParams["busType"]      = "PRV"; //PUB-对公，PRV-对私
                        $postParams["merOrderNo"]   = $user_record_info['Order_Code'];   //   商户名订单ID 代付订单号
                        $postParams["orderAmount"]  = bcmul(floatval($user_record_info['Gold']),1,2); // 多付金额 元
                        $postParams["bankCode"]     = $bankCode;   // 银行编码
                        $postParams["accountName"]  = trim($user_record_info['Name']);//收款人姓名
                        $postParams["accountCardNo"]= strval($user_record_info['Bank_Account']); // 收款卡号
                        $postParams["clientReqIP"]  = get_ip();
                        $postParams["notifyUrl"]    = $dfinfo['url']; //回调地址
                        $postParams["extendParams"] = "多付出款"; //是否笔数  否
                        $postParams["signType"]     = "MD5";
                        $postParams["sign"] = md5Sign($postParams, $dfinfo['business_pwd'], "UTF-8");

                        //$gatewayUrl = "https://payment.duopay.net/gateway/transferPay"; //支付接口地址
                        $gatewayUrl = "https://withdraw.duobaipay.com/gateway/transferPay"; //支付接口地址
                        $result = wx_post($gatewayUrl,$postParams);  //发起请求
                        $array=json_decode($result,true); //响应报文

                        if ($array['respCode'] == 'SUCCESS'){   //多付代付数据提交成功
                            $returndata = $array["respBody"];
                            //transferStatus代付订单状态     success 交易成功  failure 代付失败  pending 银行处理中
                            if($returndata['transferStatus']=='PENDING' || $returndata['transferStatus']=='SUCCESS'){
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => '多付自动出款已成功,处理中!');
                            }else{  //多付自动出款异常,代付失败!
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => '多付自动出款异常,代付失败!');
                            }
                        }else{  //代付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '多付数据提交失败!');
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询多付第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持多付银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'zrbpay_cash_autock': // 智融宝代付宝自动出款
        include_once ("../include/daifubao/payCommon.php");   // 引用代付宝文件 用于hmac 生成
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);

        // 智融宝(代付宝)银行代码：
        $duofuXiafaBankList=array(
            'ICBC'=>'中国工商银行','CMBCHINA'=>'招商银行','ABC'=>'中国农业银行','CCB'=>'中国建设银行','BOCO'=>'交通银行','CIB'=>'兴业银行','CMBC'=>'中国民生银行','CEB'=>'光大银行','BOC'=>'中国银行','ECITIC'=>'中信银行','GDB'=>'广发银行','SPDB'=>'浦发银行','POST'=>'邮政储蓄银行','PINGANBANK'=>'平安银行','HXB'=>'华夏银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $duofuXiafaBankList); // 返回银行对应键

        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取智融宝出款的信息
                    $sqlDf=" select * from ".DBPREFIX."gxfcy_autopay where method = 'zrbpay_cash_autock' and status = 1" ;
                    $resultDf = mysqli_query($dbLink,$sqlDf);
                    $dfinfo = mysqli_fetch_assoc($resultDf);
                    if(!empty($dfinfo)) {
                        $merchant_key   = strval($dfinfo['business_pwd']); // 商户密钥

                        // 拼装提交数据
                        $value['p0_Cmd']      = "SettOrderPay"; //固定值
                        $value['p1_MerId']   = strval($dfinfo['business_code']); // 商户名称
                        $value['p2_Order']      = $user_record_info['Order_Code'];   // 商户名订单ID 代付宝订单号
                        $value['p3_Amt']       = bcmul(floatval($user_record_info['Gold']),1,2); // 单位元 精确到分
                        $value['p4_Name']      = trim($user_record_info['Name']); //收款人
                        $value['p7_BankCard']   = strval($user_record_info['Bank_Account']); // 收款卡号
                        $value['p8_BankType']   = $bankCode;//收款银行卡编码
                        $value['p_Url']        = $dfinfo['url']; //通知地址
                        $value['hmac'] = getReqHmacString($value['p2_Order'],$value['p3_Amt'],$value['p4_Name'],$value['p7_BankCard'],$value['p8_BankType'],$value['p_Url'], $value['p1_MerId'], $merchant_key);
                        $postParams[] = $value;

                        // json 后提交数据
                        $data=array(
                            'count'=>count($postParams),
                            'datalist'=>$postParams,
                        );


                        //string(111) "{"r0_Cmd":"SettOrderPay","r1_Code":"1","r2_TrxId":"TK20190416051323153429","r6_Order":"TK20190416051323153429"}"
                        $result = postJson(json_encode($data));  //发起请求
                        $array=json_decode($result,true); //响应报文

                        //r2_TrxId平台订单号    r6_Order商户订单号
                        if ($array['r1_Code'] == 1 &&  $array['r0_Cmd'] == 'SettOrderPay' && ($array['r2_TrxId'] == $array['r6_Order'])){   //代付宝数据提交成功
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => '代付宝自动出款提交成功,处理中!');
                        }else{  //代付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '代付宝数据提交失败!');
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询代付宝第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持代付宝银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'swpay_cash_autock': // 顺为自动出款
        include_once ("../include/shunwei/ServiceUtil.php");   //引用顺为文件
        include_once ("../include/shunwei/Config.php");
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 顺为支持银行代码：
        $shunWeiXiafaBankList=array(
            'ICBC'=>'中国工商银行','ABC'=>'中国农业银行','CCB'=>'中国建设银行','BOC'=>'中国银行','CMB'=>'招商银行','BCM'=>'交通银行','CIB'=>'兴业银行','CMBC'=>'中国民生银行','CEB'=>'光大银行','PAB'=>'平安银行','CITIC'=>'中信银行','CGB'=>'广发银行','SPDB'=>'浦发银行','PSBC'=>'邮政储蓄银行','HXB' =>'华夏银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $shunWeiXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取顺为出款的信息
                    $sqlSw=" select * from ".DBPREFIX."gxfcy_autopay where method = 'swpay_cash_autock' and status = 1" ;
                    $resultSw = mysqli_query($dbLink,$sqlSw);
                    $swinfo = mysqli_fetch_assoc($resultSw);
                    if(!empty($swinfo)) {
                        //request_sign签名列表
                        $arr["client_num"]   = $swinfo['business_code']; // 商户号
                        $arr["order_num"]   = $user_record_info['Order_Code'];  // 订单号
                        $arr["amount"]  = bcmul(floatval($user_record_info['Gold'])*100,1,2); // 订单金额 分
                        $arr["bank_account_name"]  = trim($user_record_info['Name']);//持卡人姓名
                        $arr["bank_account_no"] = strval($user_record_info['Bank_Account']); //持卡人卡号
                        $arr["bank_code"]     = $bankCode;   // 银行编码

                        $randStr = ServiceUtil::generateRandNum(count($arr) + 1);//随机字符串
                        $arr["random_str"] = $randStr;
                        $params = ServiceUtil::signStr($arr, $randStr);//待签名数组
                        $jsonStr = json_encode($params, JSON_UNESCAPED_UNICODE);//待签名字符串
                        $params["request_sign"] = md5($jsonStr.$swinfo['business_pwd']);//signKey签名秘钥   签名
                        $params["callback_url"]    = $swinfo['url']; //回调地址

                        // 加密
                        $publicKey = ServiceUtil::publicKeyStr(Config::remitPublickey);   // remitPublick 代付公钥
                        $encryptData = ServiceUtil::encrypt($publicKey, json_encode($params));

                        //请求参数
                        $reqData["request_body"] = urlencode($encryptData);
                        $reqData["interface_version"] = md5("1.0.0".Config::headerKey);
                        $reqDataStr = ServiceUtil::get_sign($reqData);

                        //发起请求
                        $result = ServiceUtil::streamContextCreate(Config::remitUrl, $reqDataStr, Config::headerKey);

                        if($result["state_code"] == 200 && $result["message"] == "成功"){  //顺为代付数据提交成功
                            //验签
                            $resSign = $result["sign"];
                            unset($result["message"], $result["state_code"], $result["sign"]);
                            $checkSignArr = ServiceUtil::signStr($result, $result["random_str"]);
                            $resJsonStr = json_encode($checkSignArr, JSON_UNESCAPED_UNICODE);//待签名字符串
                            $checkSign = md5($resJsonStr.$swinfo['business_pwd']) ;
                            if($checkSign == $resSign){
                                mysqli_query($dbMasterLink,"COMMIT");
                                $err_resonse = array('err' => '0', 'msg' => '顺为自动出款验签成功,请求成功,处理中!');
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                //$err_resonse = array('err' => '-7', 'msg' => '顺为数据提交失败!');
                                $err_resonse = array('err' => '-7', 'msg' => '顺为自动出款验签失败');
                            }
                        }else{  //顺为数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            //$err_resonse = array('err' => '-6', 'msg' => '顺为数据提交失败!');
                            $err_resonse = array('err' => '-6', 'msg' => '提交失败,'.$result['message']);
                        }

                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询顺为第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持顺为银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'yzfpay_cash_autock': // 易支付下发
        include_once ("../include/auto/yizhifu/payCommon.php");   // 引用易支付文件 用于md5加密
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 易支付支持银行代码：
        $yzfXiafaBankList=array(
            'CMB'=>'招商银行','ICBC'=>'中国工商银行','CCB'=>'中国建设银行','BOC'=>'中国银行','ABC'=>'中国农业银行','BOCM'=>'交通银行','CGB'=>'广发银行','CITIC'=>'中信银行','CEB'=>'光大银行','CMBC'=>'中国民生银行','HXB'=>'华夏银行','PSBC'=>'邮政储蓄银行','BCCB'=>'北京银行','CIB'=>'兴业银行','PUFA'=>'浦发银行','SHANGHAI'=>'上海银行','PINGAN'=>'平安银行','HSBC'=>'汇丰银行','QDCB'=>'青岛银行','DYCB'=>'东亚银行（中国）有限公司','HSCB'=>'徽商银行','HZCB'=>'杭州银行','NJCB'=>'南京银行','NBCB'=>'宁波银行','ZGCCB'=>'中关村银行','YBCB'=>'宜宾银行','BHCB'=>'渤海银行','HFCB'=>'恒丰银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $yzfXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取易支付出款的信息
                    $sqlYzf=" select * from ".DBPREFIX."gxfcy_autopay where method = 'yzfpay_cash_autock' and status = 1" ;
                    $resultYzf = mysqli_query($dbLink,$sqlYzf);
                    $yzfinfo = mysqli_fetch_assoc($resultYzf);
                    if(!empty($yzfinfo)) {
                        $postParams["payKey"]   = strval($yzfinfo['business_code']);  // 商户
                        $postParams["orderNo"]   = $user_record_info['Order_Code'];   //   商户名订单ID 代付订单号
                        $postParams["bankAccountName"]  = trim($user_record_info['Name']);//收款人姓名
                        $postParams["bankAccountNo"]= $user_record_info['Bank_Account']; // 收款卡号
                        $postParams["bankCode"]     =   $bankCode;   // 银行编码
                        $postParams["settAmount"]  = bcmul($user_record_info['Gold'],1,2); //bcmul(floatval($user_record_info['Gold']),1,2)  sprintf("%01.2f", floatval($user_record_info['Gold']));
                        $postParams["signType"]     = "MD5";    //签名算法，固定MD5

                        $postParams["sign"] = getSignMsg($postParams, $yzfinfo['business_pwd'], "UTF-8"); //上述非空字段签名

                        $gatewayUrl = $agentPay . "pay"; //支付接口地址
                        $result = httpCurl($gatewayUrl,$postParams);  //发起请求
                        $array=json_decode($result,true); //响应报文

                        //{"sign":"1CBE5B09318C859B17041A78F6F322F1","result":"success","signType":"MD5","settOrderNo":"4Y202006282312254182","msg":"成功"}
                        //@error_log('array:'.serialize($array) . PHP_EOL,  3,  '/tmp/aaa.log');

                        if ($array['result'] == 'success' && isset($array['settOrderNo']) ) {   //易支付数据提交成功

                            $redisObj = new Ciredis();
                            $redisObj->pushMessage('yzfpay_cash_autock',$postParams["orderNo"]);

                            mysqli_query($dbMasterLink,"COMMIT");
                            $err_resonse = array('err' => '0', 'msg' => '易支付自动出款已成功,处理中!');

                        }else{  //易支付数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '易支付数据提交失败!'. $array['msg']);
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询易支付第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持易支付银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'jmpay_cash_autock': // 聚名下发
        include_once ("../include/auto/juming/Config.php");   // 引用聚名文件 用于md5加密
        include_once ("../include/auto/juming/ServiceUtil.php");   // 引用聚名文件 用于md5加密
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 聚名支持银行代码：
        $jmXiafaBankList=array(
            'ICBC'=>'中国工商银行','CCB'=>'中国建设银行','ABC'=>'中国农业银行','PSBC'=>'邮政储蓄银行','BOC'=>'中国银行','COMM'=>'交通银行','CMB'=>'招商银行','CEB'=>'光大银行','CIB'=>'兴业银行','CMBC'=>'中国民生银行','BCCB'=>'北京银行','CITIC'=>'中信银行','GDB'=>'广发银行','SDB'=>'深圳发展银行','SPDB'=>'上海浦东发展银行','PINGANBANK'=>'平安银行','HXB'=>'华夏银行','SHB'=>'上海银行','CBHB'=>'渤海银行','HKBEA'=>'东亚银行（中国）有限公司','NBCB'=>'宁波银行','CZB'=>'浙商银行','NJCB'=>'南京银行','HZCB'=>'杭州银行','BJRCB'=>'北京农村商业银行','SRCB'=>'上海农商银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $jmXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取聚名出款的信息
                    $sqlJm=" select * from ".DBPREFIX."gxfcy_autopay where method = 'jmpay_cash_autock' and status = 1" ;
                    $resultJm = mysqli_query($dbLink,$sqlJm);
                    $jminfo = mysqli_fetch_assoc($resultJm);
                    if(!empty($jminfo)) {
                        // 签名列表
                        $postParams["merchantId"]   = Config::nerchNo;  // 商户号
                        //$postParams["merchantId"]   = strval($jminfo['business_code']);  // 商户号
                        $postParams["version"]   = "1.0.0"; //版本号
                        $postParams["merchantOrderNo"]   = $user_record_info['Order_Code'];   //   商户名订单ID 代付订单号
                        $postParams["amount"]  = bcmul($user_record_info['Gold'],1,2);
                        $postParams["bankCode"]     =   $bankCode;   // 银行编码
                        $postParams["bankcardAccountNo"]= $user_record_info['Bank_Account']; // 收款卡号
                        $postParams["bankcardAccountName"]  = trim($user_record_info['Name']);//收款人姓名
                        $postParams["notifyUrl"]  = $jminfo['url']; //回调地址

                        ksort($postParams);    // 排序
                        $signData = ServiceUtil::get_sign($postParams);     //拼接签名字符串
                        $privateKey = ServiceUtil::privateKeyStr(Config::privateKey);  //获取私钥
                        $sign = ServiceUtil::sign($signData,$privateKey);   //生成签名
                        $postParams["sign"] = $sign;

                        $reqData = json_encode($postParams, JSON_UNESCAPED_UNICODE);

                        //ServiceUtil::writelog("debug","代付下单订单号：".$postParams["merchantOrderNo"]);
                        $result  = ServiceUtil::curlPost(Config::remiturl, $reqData);
                        $resultArr = json_decode($result, true);

                        if($resultArr["code"] == "0" && $resultArr["msg"] == "操作成功"){  //聚名代付数据提交成功
                            // 验签
                            $reSign = $resultArr["sign"];
                            unset($resultArr["sign"]);
                            ksort($resultArr);
                            //拼接签名字符串
                            $signData = ServiceUtil::get_sign($resultArr);
                            $publickey = ServiceUtil::publicKeyStr(Config::publicKey);
                            $flag = ServiceUtil::verify($signData, $reSign, $publickey);
                            if($flag){
                                // 0：处理中，1：成功，2：失败"
                                if($resultArr["status"] == "0" || $resultArr["status"] == "1"){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                    $err_resonse = array('err' => '0', 'msg' => '聚名自动出款验签成功,请求成功,处理中!');
                                }else if($resultArr["status"] == "2"){
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    $err_resonse = array('err' => '-8', 'msg' => '聚名自动出款返回失败');
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $err_resonse = array('err' => '-7', 'msg' => '聚名自动出款验签失败');
                            }
                        }else{  //聚名数据提交失败
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => '提交失败,'.$resultArr['msg']);
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询聚名第三方配置失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持聚名银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    case 'tfpay_cash_autock': // 腾飞自动出款
        if($id==0){
            exit(json_encode(['err' => '-10', 'msg' => '订单不存在!']));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $sql_check = "select ID,userid,UserName,`Name`,Bank,Bank_Account,Bank_Address,Order_Code,Checked,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$id} for update";
        $res_check = mysqli_query($dbMasterLink,$sql_check);
        $user_record_info = mysqli_fetch_assoc($res_check);
        // 腾飞支持银行代码：
        $tfXiafaBankList=array(
            'ICBC'=>'中国工商银行','ABC'=>'中国农业银行','CCB'=>'中国建设银行','CMB'=>'招商银行','PSBC'=>'邮政储蓄银行','CIB'=>'兴业银行','CMBC'=>'中国民生银行','BOCO'=>'交通银行','ECITIC'=>'中信银行','CEB'=>'光大银行','BOC'=>'中国银行','HXB'=>'华夏银行','PINGAN'=>'平安银行','SPDB'=>'浦发银行','BCCB'=>'北京银行','BHB'=>'渤海银行','DGB'=>'东莞银行','GZCB'=>'广州银行','HZB'=>'杭州银行','CZB'=>'浙商银行','NJCB'=>'南京银行','NBCB'=>'宁波银行','BJRCB'=>'北京农村商业银行','TJYH'=>'天津银行','HBYH'=>'河北银行股份有限公司','NMGYH'=>'内蒙古银行','EEDSYH'=>'鄂尔多斯银行','DLYH'=>'大连银行','JLYH'=>'吉林银行','HEBYH'=>'哈尓滨银行','JSYH'=>'江苏银行','WZYH'=>'温州银行','SXYH'=>'绍兴银行','SMYH'=>'厦门银行','QZYH'=>'泉州银行','NCYH'=>'南昌银行','QDYH'=>'青岛银行','YTYH'=>'烟台银行','ZZYH'=>'郑州银行','HUBYH'=>'湖北银行','CSYH'=>'长沙银行','CDYH'=>'成都银行','CQYH'=>'重庆银行','GZYH'=>'贵州银行','XAYH'=>'西安银行','LZYH'=>'兰州银行股份有限公司','QHYH'=>'青海银行','NXYH'=>'宁夏银行','HYYH'=>'韩亚银行','ZDYH'=>'中德银行','SMGJYH'=>'厦门国际银行','SDB'=>'深圳发展银行','GDB'=>'广发银行','SHB'=>'上海银行','DGNS'=>'东莞农村商业银行','MYSH'=>'绵阳市商业银行','GDNS'=>'广东省农村信用社联合社','CDNS'=>'成都农村商业银行','ZYYH'=>'中原银行','LZYH2'=>'柳州银行','SCNYS'=>'四川省农村信用合作社','JXYH'=>'江西银行','GZNS'=>'贵州省农村信用社','SNCCB'=>'遂宁银行','LSCCB'=>'乐山市商业银行','JJBANK'=>'九江银行','KMNX'=>'昆明农村信用社','GSNX'=>'甘肃省农村信用社','SHRCB'=>'上海农商银行','GXNXS'=>'广西农村信用社','GLYH'=>'桂林银行','FJNXS'=>'福建省农村信用社','AHNXS'=>'安徽省农村信用社联合社','GYYH'=>'贵阳银行','GSNHJ'=>'甘肃省农村合作金融结算服务中心','HSYH'=>'徽商银行','HBNXS'=>'湖北省农村信用社','JSNXS'=>'江苏省农村信用社联合社','JXNXS'=>'江西农村信用社','HUNNXS'=>'湖南省农村信用社联合社','QHNXS'=>'青海省农村信用社','SJYH'=>'盛京银行','SDNXS'=>'山东省农村信用合作社','SXNXS'=>'陕西省农村信用社联合社','ZJNXS'=>'浙江省农村信用社联合社','WSYH'=>'浙江网商银行','XJWWENXS'=>'新疆维吾尔自治区农村信用社联合社','NMGNXS'=>'内蒙古农村信用社','TZCB'=>'台州银行','BOSZ'=>'苏州银行','GSBANK'=>'甘肃银行','HENNXS'=>'河南省农村信用社','JLNXS'=>'吉林省农村信用社','YNNXS'=>'云南省农村信用社','GZNSH'=>'广州农村商业银行','HEBNXS'=>'河北农信用','YKYH'=>'营口银行','SZNXS'=>'深圳巿农村商业银行','RZYH'=>'日照银行','JNYH'=>'济宁银行','TJNSYH'=>'天津农商银行','SZNCSYYH'=>'深圳农村商业银行','JNSYYH'=>'济南商业银行','CQSXYH'=>'重庆三峡银行','CQNSYH'=>'重庆农村商业银行','BSYH'=>'包商银行股份有限公司','CSNCSYYH'=>'常熟农村商业银行','EEDSNSYH'=>'鄂尔多斯农商银行','FDYH'=>'富滇银行','JSHYH'=>'晋商银行网上银行','WHNCSYYH'=>'武汉农村商业银行','QLYH'=>'齐鲁银行','CAYH'=>'长安银行','BBWYH'=>'广西北部湾银行','SXNSYH'=>'陕西农商银行','JZZL'=>'焦作中旅银行','SDNSH'=>'顺德农村商业银行','ZGNXS'=>'农村信用社','HNNXS'=>'海南省农村信用社','SZNSH'=>'深圳农商行','GZBK'=>'赣州银行','OTHER'=>'其他银行',
        );
        $bankCode = array_search($user_record_info['Bank'], $tfXiafaBankList); // 返回银行对应键
        if ($bankCode != false){
            //首先更新订单状态，如果订单状态更新成功，才进行下一步
            $reviewDate=date('Y-m-d H:i:s');
            $sql_update = "update ".DBPREFIX."web_sys800_data set is_auto=1,is_auto_flag=2,reviewer='{$_SESSION['UserName']}',reviewDate='{$reviewDate}' WHERE `ID` = {$id}";
            if(!mysqli_query($dbMasterLink,$sql_update)){
                exit(json_encode(['err' => '-11', 'msg' => '订单状态更新失败！']));
            }

            if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {//  and Type = 'T' and Checked=2 and Locked=0
                $resultMem = mysqli_query($dbMasterLink,"select Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$user_record_info['userid']}' for update");
                if($resultMem) {
                    // 获取代付出款的信息
                    $sqlDf=" select * from ".DBPREFIX."gxfcy_autopay where method = 'tfpay_cash_autock' and status = 1" ;
                    $resultDf = mysqli_query($dbLink,$sqlDf);
                    $dfinfo = mysqli_fetch_assoc($resultDf);
                    if(!empty($dfinfo)) {
                        $postParams["mch_code"]   = strval($dfinfo['business_code']);  // 商户
                        $postParams["amount"]  = bcmul($user_record_info['Gold'],1,2);
                        $postParams["acct_no"]= $user_record_info['Bank_Account']; // 收款卡号
                        $postParams["acct_name"]  = trim($user_record_info['Name']);//收款人姓名
                        $postParams["bank_code"]     =   $bankCode;   // 银行编码
                        $postParams["out_trade_no"]   = strval($user_record_info['Order_Code']);   //商户名订单ID 代付订单号
                        $postParams["notify_url"]  = $dfinfo['url']; //回调地址
                        $postParams["sign"] = getSign($postParams, $dfinfo['business_pwd']); //上述非空字段签名

                        $gatewayUrl = "http://www.tfpay88.com/openapi/daifu"; //支付接口地址
                        $result = send_post($gatewayUrl,$postParams);  //发起请求
                        $resultArr=json_decode($result,true); //响应报文

                        //@error_log('array:'.serialize($resultArr) . PHP_EOL,  3,  '/tmp/group/aaa.log');
                        if ($resultArr['rst'] === 0){  //腾飞代付数据提交成功
                            mysqli_query($dbMasterLink,"COMMIT");
                            $err_resonse = array('err' => '0', 'msg' => '腾飞下发提交成功!');
                        } else {
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $err_resonse = array('err' => '-6', 'msg' => $resultArr['msg']);
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $err_resonse = array('err' => '-5', 'msg' => '查询腾飞第三方失败!');
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $err_resonse = array('err' => '-4', 'msg' => '查询用户信息失败!');
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $err_resonse = array('err' => '-3', 'msg' => '订单已经被处理，不要重复提交！');
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $err_resonse = array('err' => '-2', 'msg' => '会员银行不支持腾飞银行出款！');
        }
        //记录用户操作日志
        $loginfo_status = '<font class="red">'.$err_resonse['msg'].'</font>' ;
        $loginfo = $_SESSION['UserName'].' 对会员帐号 <font class="green">'.$user_record_info['UserName'].'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($user_record_info['Gold'],2).'</font>' ;
        innsertSystemLog($_SESSION['UserName'],$lv,$loginfo);
        exit( json_encode($err_resonse) );
        break;
    default:
        exit(json_encode(['err' => '-100', 'msg' => 'method异常，请检查自动出款配置']));
        break;
}

function getSign($data, $appKey){
    if (!$appKey) {
        return false;
    }
    unset($data['sign']);
    ksort($data);
    $dataStr = '';
    foreach ($data as $k => $v) {
        $dataStr .= $k . '=' . $v . "&";
    }
    $signStr = trim($dataStr, "&") . "&key=". $appKey;
//    echo '待签字串:'.$signStr.'<br>';
    $sign = strtoupper(md5($signStr));
//    echo '签名字串:'.$sign.'<br>';
    return $sign;
}

function send_post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超時時間（單位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}