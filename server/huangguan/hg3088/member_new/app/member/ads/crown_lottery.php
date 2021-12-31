<?php
/**
 * 彩票广告站API
 * 1.普通登录
 * 2.游客登录
 * 3.注册
 * Date: 2019/3/22
 */
ini_set('display_errors', 'ON');
include_once "../include/config.inc.php";
include_once "../include/address.mem.php";
include_once "../include/curl_http.php";

// IP限制
$redisObj = new Ciredis();
$limitIps = $redisObj->getSimpleOne('font_ip_limit');
$aLimitIps = json_decode($limitIps,true);
$ipType = $aLimitIps['type']; // 1 全站，2 登录，3 注册，4 登录/注册
$aIpList= explode(';', $aLimitIps['list']);
$ipAddress = get_ip();
if($ipType ==2 && in_array($ipAddress, $aIpList) || ( $ipType == 1 && in_array($ipAddress, $aIpList) ) || ( $ipType == 4 && in_array($ipAddress, $aIpList) ) ){
    exit(json_encode( ['code' => '4000', 'message' => '抱歉，您已被禁止登录！'] ) );
}

// 接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$username= isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
$password = isset($_REQUEST['password']) && $_REQUEST['password'] ? trim($_REQUEST['password']) : '';

// 处理请求
$now = date('Y-m-d H:i:s');
$date = date('Y-m-d');
$langx = 'zh-cn';

switch ($action){
    case 'login':
        $cryptPassword = pwdEncryption($password, $username); // 加密后密码
        if($username == '' || $password == ''){
            exit(json_encode(['code' => '4001', 'message' => '登录错误！请检查用户名或密码！']));
        }

        // 检测当前登录会员&查询会员信息
        $stmt = $dbLink->prepare("SELECT * FROM ".DBPREFIX.MEMBERTABLE." where `UserName` = ? and `PassWord` = ? and Status <= 1 LIMIT 1");
        $stmt->bind_param('ss', $username, $cryptPassword);
        $stmt->execute();
        $aUser = $stmt->get_result()->fetch_assoc();
        if(empty($aUser)) {
            exit(json_encode( ['code' => '4002', 'message' => '登录错误！请检查用户名或密码！']));
        }

        // 试玩账号禁止登录
        if($aUser['Agents'] == 'demoguest'){
            exit(json_encode( ['code' => '4003', 'message' => '此账号为试玩账号,请注册正式用户！']));
        }

        $str = time('s');
        $uid = strtolower(substr(md5($str),0,10).substr(md5($username),0,10).'ra'.rand(0,9));
        $redisObj->setOne('loginuser_'.$aUser['ID'] , $uid);// 写入redis
//        setcookie("TestCookie",111, time()+3600, '/');

        $_SESSION['langx'] = $langx;
        $_SESSION['UserName'] = $aUser['UserName'];
        $_SESSION['Oid'] = $uid;
        $_SESSION['userid'] = $aUser['ID'];
        $_SESSION['DepositTimes'] = $aUser['DepositTimes']; //存款次数
        $_SESSION['Agents'] = $aUser['Agents'];
        $_SESSION['Language'] = $aUser['Language'];
        $_SESSION['Pay_Type'] = $aUser['Pay_Type'];
        $_SESSION['Status'] = $aUser['Status'];
        $_SESSION['Admin'] = $aUser['Admin'];
        $_SESSION['Alias'] = $aUser['Alias'];
        $_SESSION['World'] = $aUser['World'];
        $_SESSION['Corprator'] = $aUser['Corprator'];
        $_SESSION['Super'] = $aUser['Super'];
        $_SESSION['Bank_Name'] = $aUser['Bank_Name'];
        $_SESSION['Bank_Account'] = $aUser['Bank_Account'];
        $_SESSION['Bank_Address'] = $aUser['Bank_Address'];
        $_SESSION['Phone'] = $aUser['Phone'];
        $_SESSION['Notes'] = $aUser['Notes'];
        $_SESSION['OpenType'] = $aUser['OpenType'];
        $_SESSION['OnlineTime'] = $aUser['OnlineTime'];
        $_SESSION['AddDate'] = $aUser['AddDate'];
        $_SESSION['pay_class'] = $aUser['pay_class'];
        $_SESSION['test_flag'] = $aUser['test_flag'];
        $_SESSION['password'] = $aUser['PassWord'];
        $_SESSION['gameSwitch'] = $aUser['gameSwitch'];
        $_SESSION['ratio'] = $aUser['ratio'];
        $_SESSION['CurType'] = $aUser['CurType'];

        $onlinetime = strtotime($aUser['OnlineTime']);
        $sql = "update ".DBPREFIX.MEMBERTABLE." set online_status=0,Oid='$uid',LoginDate='$date',LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ipAddress',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
        if(!mysqli_query($dbMasterLink,$sql)){
            exit(json_encode( ['code' => '4004', 'message' => '网络异常，请稍后重试！']));
        }

        // 记录登录 ip记录
        $loginArr = array(
            0 => $_SESSION['userid'] ,
            1 => $_SESSION['UserName'] ,
            2 => $_SESSION['Agents'] ,
            3 => 0 , // 0会员，1代理商
            4 => $aUser['WinLossCredit'], // 信用额度
            5 => $_SESSION['Alias'],
        );
        addLoginIpLog($loginArr);
//        echo json_encode(['code' => 0, 'data' => []]);
        // 同时登录彩票
        $data = loginLottery();
        exit($data);
        break;
    case 'guest_login':
        // 查询试玩账号
        $demoSql = "SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest' and Oid='logout' AND Online=0";
        $demoResult = mysqli_query($dbLink, $demoSql);
        $demoCou = mysqli_num_rows($demoResult);
        if($demoCou == 0){
            $demoSql = "SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest'";
            $demoResult = mysqli_query($dbLink,$demoSql);
            $demoCou = mysqli_num_rows($demoResult);
            if($demoCou == 0){
                exit(json_encode( ['code' => '4005', 'message' => '暂无空闲体验用户,请注册真实用户！']));
            }
        }
        $demoUsers = [];
        while($demoRow = mysqli_fetch_assoc($demoResult)){
            $demoUsers[$demoRow["ID"]] = $demoRow;
        }
        $demoUser = $demoUsers[array_rand($demoUsers)];

        $str = time('s');
        $uid=strtolower(substr(md5($str),0,10).substr(md5($demoUser['UserName']),0,10).'ra'.rand(0,9));
        $redisObj->setOne('loginuser_'.$demoUser['ID'] , $uid); // 写入redis

        $_SESSION['langx'] = $langx;
        $_SESSION['UserName']=$demoUser['UserName'];
        $_SESSION['Oid'] = $uid;
        $_SESSION['userid']=$demoUser['ID'];
        $_SESSION['Agents']=$demoUser['Agents'];
        $_SESSION['Language']=$demoUser['Language'];
        $_SESSION['Pay_Type']=$demoUser['Pay_Type'];
        $_SESSION['Status']=$demoUser['Status'];
        $_SESSION['Admin']=$demoUser['Admin'];
        $_SESSION['Alias']=$demoUser['Alias'];
        $_SESSION['World']=$demoUser['World'];
        $_SESSION['Corprator']=$demoUser['Corprator'];
        $_SESSION['Super']=$demoUser['Super'];
        $_SESSION['Bank_Name']=$demoUser['Bank_Name'];
        $_SESSION['Bank_Account']=$demoUser['Bank_Account'];
        $_SESSION['Bank_Address']=$demoUser['Bank_Address'];
        $_SESSION['Phone']=$demoUser['Phone'];
        $_SESSION['Notes']=$demoUser['Notes'];
        $_SESSION['OpenType']=$demoUser['OpenType'];
        $_SESSION['OnlineTime']=$demoUser['OnlineTime'];
        $_SESSION['pay_class']=$demoUser['pay_class'];
        $_SESSION['test_flag']=$demoUser['test_flag'];
        $_SESSION['password']=$demoUser['PassWord'];

        $onlinetime = strtotime($demoUser['OnlineTime']);
        $sql = "update ".DBPREFIX.MEMBERTABLE." set online_status=0,Money=2000,Credit=0,WinLossCredit=0,Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ipAddress',Language='$langx',Url='".BROWSER_IP."' where ID=".$demoUser['ID']." and Status<=1";
        if(!mysqli_query($dbMasterLink,$sql)) {
            exit(json_encode( ['code' => '4006', 'message' => '暂无空闲体验用户,请注册真实用户！']));
        }
        // 记录登录 ip记录
        $loginArr = [
            0 => $_SESSION['userid'],
            1 => $_SESSION['UserName'],
            2 => $_SESSION['Agents'],
            3 => 0 , // 0会员，1代理商
            4 => 0 , // 信用额度
            5 => $_SESSION['Alias'],
        ];
        addLoginIpLog($loginArr);

        // 清理测试用户历史数据
        mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_report_data where userid=" . $demoUser['ID'] . " and M_Name='" . $demoUser['UserName']."'");
        $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
        $userCpRow=mysqli_query($cpMasterDbLink,"select id from gxfcy_user where hguid=".$demoUser['ID']." and username='".$demoUser['UserName']."'");
        $userCp = mysqli_fetch_assoc($userCpRow);
        if($userCp['id'] > 1000){
            mysqli_query($cpMasterDbLink,"delete from gxfcy_bill where userid=".$userCp['id']." and username='".$demoUser['UserName']."'");
            mysqli_query($cpMasterDbLink,"update gxfcy_user set currency=2000,lcurrency=2000 where id=".$userCp['id']." and username='".$demoUser['UserName']."'");
        }

        // 同时登录彩票
        $data = loginLottery();
        exit($data);
        break;
    case 'register':
        $AddDate=date('Y-m-d H:i:s');//新增日期
        $EditDate=date('Y-m-d');//修改日期
        $intr = trim($_REQUEST['introducer']);  // 介绍人
        if ($intr == ''){
            $agent= DEFAULT_AGENT; // 默认代理
        }else{
            $agent = $intr;
        }
        $online_status = 5; // 广告站
        $username = mysqli_real_escape_string($dbLink,$_REQUEST['username']);//帐号
        $password = mysqli_real_escape_string($dbLink,$_REQUEST['password']);//密码
        $password2 = mysqli_real_escape_string($dbLink,$_REQUEST['password2']);// 确认密码
        $alias = mysqli_real_escape_string($dbLink,$_REQUEST['alias']);// 真实姓名
        $paypassword = mysqli_real_escape_string($dbLink,$_REQUEST['paypassword']);// 提款密码
        $phone = mysqli_real_escape_string($dbLink,$_REQUEST['phone']); //手机
        $wechat = mysqli_real_escape_string($dbLink,$_REQUEST['wechat']); //微信
        $source = mysqli_real_escape_string($dbLink,$_REQUEST['know_site']);// 来源 source 替换成 know_site
        $Url = getenv("HTTP_HOST");

        $stop_execution = 0;
        if($intr && !is_username($intr)){ // 代理验证
            exit(json_encode( ['code' => '4007', 'message' => '推荐人不符合规范！']));
        }
        if(!is_username($username)){ // 用户名验证
            exit(json_encode( ['code' => '4008', 'message' => '用户名不符合规范！']));
        }
        if($password !=$password2){
            exit(json_encode( ['code' => '4009', 'message' => '密码与确认密码不一致！']));
        }
        if(strlen($password) != 32){
            exit(json_encode( ['code' => '4010', 'message' => '密码不符合规范！']));
        }
        if(!isTrueName($alias)){ // 真实姓名验证
            exit(json_encode( ['code' => '4011', 'message' => '真实姓名不符合规范！']));
        }
        if(!isPayNumber($paypassword)){ // 支付密码验证
            exit(json_encode( ['code' => '4012', 'message' => '支付密码不符合规范！']));
        }
        if(!isWechat($wechat)){ // 微信验证
            exit(json_encode( ['code' => '4013', 'message' => 'QQ号码不符合规范！']));
        }
        if(!isPhone($phone)){ // 手机号码验证
            exit(json_encode( ['code' => '4014', 'message' => '手机号码不符合规范！']));
        }

        $fields = "`ID`,`UserName`,`agent_url`,`World`,`Corprator`,`Super`,`Admin`,`Sports`,`Lottery`";
        $sql = "select $fields from ".DBPREFIX."web_agents_data where UserName='$agent'";
        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $cous = mysqli_num_rows($result);
        if($cous==0){
            exit(json_encode( ['code' => '4015', 'message' => "您输入的推荐代理 $agent 不存在。请查证输入的代理正确登记，谢谢！"]));
        }
        // 代理线
        $agent_url = $row['agent_url'] ? $row['agent_url'] : '';
        $thisurl = getMainHost(); // 获取当前url
        $urlsql = "select $fields from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
        $urlresult = mysqli_query($dbLink,$urlsql);
        $urlcou = mysqli_num_rows($urlresult);
        $urlrow = mysqli_fetch_assoc($urlresult);
        if($urlcou > 0){
            $agent = $urlrow['UserName'] ;
            $agent_url = $thisurl ;
            $row = $urlrow;
        }
        $world = $row['World'];
        $corprator = $row['Corprator'];
        $super = $row['Super'];
        $admin = $row['Admin'];
        $sports = $row['Sports'];
        $lottery = $row['Lottery'];
        $agent == TEST_AGENT ? $test_flag = 1 : $test_flag = 0; // 判断是否测试代理线

        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
        $mresult = mysqli_query($dbLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou > 0){
            exit(json_encode( ['code' => '4016', 'message' => '帐户已经有人使用，请重新注册！']));
        }

        // 入库
        $sql="insert into ".DBPREFIX.MEMBERTABLE." set ";
        $sql.="UserName='".$username."',";
        $sql.="LoginName='".$username."',";
        $sql.="PassWord='".pwdEncryption($password,$username)."',";
        $sql.="Credit='0',";
        $sql.="Money='0',";
        $sql.="test_flag='".$test_flag."',";
        $sql.="Alias='".$alias."',";
        $sql.="Sports='".$sports."',";
        $sql.="Lottery='".$lottery."',";
        $sql.="AddDate='".$AddDate."',";
        $sql.="EditDate='".$EditDate."',";
        $sql.="Status='0',";
        $sql.="CurType='RMB',";
        $sql.="pay_class='a',"; // 支付分层，默认未分层 a
        $sql.="Pay_Type='1',";
        $sql.="Opentype='".REG_OPEN_TYPE."',";
        $sql.="Agents='".$agent."',";
        $sql.="agent_url='".$agent_url."',"; // 代理线
        $sql.="World='".$world."',";
        $sql.="Corprator='".$corprator."',";
        $sql.="Super='".$super."',";
        $sql.="Admin='".$admin."',";
        $sql.="Phone='".$phone."',";
        $sql.="E_Mail='".$wechat."',"; // 这个字段用于微信
        $sql.="Source='".$source."',";
        $sql.="Address='".$paypassword."',";
        $sql.="RegisterIP='".$ipAddress."',";
        $sql.="regSource='".$online_status."',";
        $sql.="Url='".$Url."',";
        $sql.="Reg='1' ";

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	// 开启事务
        if(!mysqli_query($dbMasterLink, $sql)) {
            mysqli_query($dbMasterLink,"ROLLBACK");
            exit(json_encode( ['code' => '4017', 'message' => '抱歉，注册新会员失败！']));
        }
        $mysql = "update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$agent'";
        if(!mysqli_query($dbMasterLink,$mysql)) {
            mysqli_query($dbMasterLink,"ROLLBACK");
            exit(json_encode(['code' => '4018', 'message' => '抱歉，注册新会员失败！']));
        }
        mysqli_query($dbMasterLink, "COMMIT");
//        exit(json_encode(['code' => 0, 'message' => '恭喜你，注册成功！']));

        // 注册成功，自动登录
        $cryptPassword = pwdEncryption(trim($password), $username);

        $sql = "SELECT test_flag,Money,birthday,pay_class,UserName,OpenType,Pay_Type,PassWord,Credit,WinLossCredit,LoginDate,OnlineTime,EditDate,ID,Oid,Agents,Language,Status,Admin,
                Alias,World,Corprator,Super,E_Mail,Phone,Notes,Bank_Name,Bank_Account,Bank_Address,AddDate 
                FROM `".DBPREFIX.MEMBERTABLE."` WHERE UserName='$username' AND PassWord='$cryptPassword' AND Status<2 ";
        $result = mysqli_query($dbMasterLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);
        if ($cou == 0){
            exit(json_encode( ['code' => '4019', 'message' => '抱歉，用户名或密码错误！']));
        }

        $str = time('s');
        $uid = strtolower(substr(md5($str), 0, 10) . substr(md5($username), 0, 10) . 'ra' . rand(0, 9));

        $_SESSION['DepositTimes'] = $row['DepositTimes']; //存款次数
        $_SESSION['UserName'] = $row['UserName'];
        $_SESSION['Oid'] = $uid;
        $_SESSION['userid'] = $row['ID'];
        $_SESSION['Agents'] = $row['Agents'];
        $_SESSION['Language'] = 'zh-cn'; // 默认简体中文
        $_SESSION['Pay_Type'] = $row['Pay_Type'];
        $_SESSION['Status'] = $row['Status'];
        $_SESSION['Admin'] = $row['Admin'];
        $_SESSION['Alias'] = $row['Alias'];
        $_SESSION['World'] = $row['World'];
        $_SESSION['Corprator'] = $row['Corprator'];
        $_SESSION['Super'] = $row['Super'];
        $_SESSION['E_Mail'] = $row['E_Mail']; // 微信
        $_SESSION['Phone'] = $row['Phone'];
        $_SESSION['Notes'] = $row['Notes'];
        $_SESSION['OpenType'] = $row['OpenType'];
        $_SESSION['OnlineTime'] = $row['OnlineTime'];
        $_SESSION['pay_class'] = $row['pay_class'];
        $_SESSION['test_flag'] = $row['test_flag'];
        $_SESSION['password']=$row['PassWord'];
        $_SESSION['birthday'] = (substr($row['birthday'],0,10)=='0000-00-00')?"":substr($row['birthday'],0,10) ;
        $_SESSION['Money'] = floor($row['Money']);
        $_SESSION['gameSwitch']=$row['gameSwitch'];
        $_SESSION['Bank_Name']=$row['Bank_Name'];
        $_SESSION['Bank_Account']=$row['Bank_Account'];
        $_SESSION['Bank_Address']=$row['Bank_Address'];
        $_SESSION['AddDate']=$row['AddDate'];
        $_SESSION['BindCard_Flag'] = $row['Bank_Name'] && $row['Bank_Account'] && $row['Bank_Address'] ? 1 : 0; // 是否设置银行卡

        // 将当期登录用户userid , Oid写入redis
        $redisObj->setOne('loginuser_' . $row['ID'], $uid);// 写入redis
        $langx = $row['Language'];
        $sql = "update ".DBPREFIX.MEMBERTABLE." set online_status='$online_status',Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ipAddress',Language='$langx',Url='" . BROWSER_IP . "' where UserName='$username' and Status<=1";
        if(!mysqli_query($dbMasterLink,$sql)){
            exit(json_encode( ['code' => '4004', 'message' => '网络异常，请稍后重试！']));
        }

        // 记录登录 ip记录
        $loginArr = array(
            0 => $_SESSION['userid'] ,
            1 => $_SESSION['UserName'] ,
            2 => $_SESSION['Agents'] ,
            3 => 0 , // 0会员，1代理商
            4 => $aUser['WinLossCredit'], // 信用额度
            5 => $_SESSION['Alias'],
        );
        addLoginIpLog($loginArr);

        // 同时登录彩票
        $data = loginLottery();
        exit($data);
        break;
    case 'agent_login':
        $data = getAgentUrl($ulrarr);
        exit(json_encode(['code' => 0, 'data' => $data]));
        break;
    case 'agent_register':
        $AddDate = date('Y-m-d H:i:s');//新增日期
        $alias = isset($_REQUEST['alias']) && $_REQUEST['alias'] ? trim($_REQUEST['alias']) : ''; //真实姓名
        $phone = isset($_REQUEST['phone']) && $_REQUEST['phone'] ? trim($_REQUEST['phone']) : ''; //手机
        $wechat = isset($_REQUEST['wechat']) && $_REQUEST['wechat'] ? trim($_REQUEST['wechat']) : ''; //微信
        $username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : ''; //帐号
        $password = isset($_REQUEST['password']) && $_REQUEST['password'] ? trim($_REQUEST['password']) : ''; //密码
        $password2 = isset($_REQUEST['password2']) && $_REQUEST['password2'] ? trim($_REQUEST['password2']) : ''; //密码
        $address = isset($_REQUEST['address']) && $_REQUEST['address'] ? trim($_REQUEST['address']) : ''; //QQ/Skype
        $bank_name = isset($_REQUEST['bank_name']) && $_REQUEST['bank_name'] ? trim($_REQUEST['bank_name']) : ''; //银行名称
        $bank_account = isset($_REQUEST['bank_account']) && $_REQUEST['bank_account'] ? trim($_REQUEST['bank_account']) : ''; //银行账号
        $bank_address = isset($_REQUEST['bank_address']) && $_REQUEST['bank_address'] ? trim($_REQUEST['bank_address']) : ''; //银行地址
        $paypassword = isset($_REQUEST['paypassword']) && $_REQUEST['paypassword'] ? trim($_REQUEST['paypassword']) : ''; //提款密码
        $e_mail = isset($_REQUEST['email']) && $_REQUEST['email'] ? trim($_REQUEST['email']) : ''; //邮箱
        $Competence= '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,1,1,0,1,' ; // 用于显示该所拥有的权限，代理 D 层级 都是这个
        $online_status = 5; // 注册来源广告站

        if(!is_username($username)){ // 用户名验证
            exit(json_encode( ['code' => '4021', 'message' => '用户名不符合规范！']));
        }
        if($password != $password2){
            exit(json_encode( ['code' => '4022', 'message' => '密码与确认密码不一致！']));
        }
        if(strlen($password) != 32){
            exit(json_encode( ['code' => '4023', 'message' => '密码不符合规范！']));
        }
        if(!isTrueName($alias)){ // 真实姓名验证
            exit(json_encode( ['code' => '4024', 'message' => '真实姓名不符合规范！']));
        }
        if(!isPhone($phone)){ // 手机号码验证
            exit(json_encode( ['code' => '4025', 'message' => '手机号码不符合规范！']));
        }
        if(!isWechat($wechat)){ // 微信号码验证
            exit(json_encode( ['code' => '4026', 'message' => '微信号码不符合规范！']));
        }
        if(!$bank_address){ // 银行卡号验证
            exit(json_encode( ['code' => '4027', 'message' => '开户行地址不符合规范！']));
        }
        if(!isBankNumber($bank_account)){ // 银行卡号验证
            exit(json_encode( ['code' => '4028', 'message' => '银行卡号不符合规范！']));
        }

        $sql = "select ID from ".DBPREFIX."web_agents_data where UserName='$username'";
        $result = mysqli_query($dbLink, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0){
            exit(json_encode( ['code' => '4029', 'message' => '帐户已经有人使用，请重新注册！']));
        }

        $sql = "select UserName,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='cdm323'"; // 默认总代理
        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $world = $row['UserName'];
        $corprator = $row['Corprator']; //股东 B
        $super = $row['Super'];  //公司 A
        $admin = $row['Admin']; //管理员（？子账号）
        $sports = $row['Sports'];
        $lottery = $row['Lottery'];

        $sql = "insert into " . DBPREFIX . "web_agents_data set ";
        $sql .= "Level='D',"; // Agent 代理 D  World 总代 C  Corprator 股东 B  Super 公司 A  Admin 管理员（？子账号）
        $sql .= "UserName='" . $username . "',";
        $sql .= "LoginName='" . $username . "',";
        $sql .= "PassWord='" . pwdEncryption($password, $username) . "',";
        $sql .= "PassWord_Safe='" . $paypassword . "',";
        $sql .= "Credit='0',";
        $sql .= "Alias='" . $alias . "',";
        $sql .= "AddDate='" . $AddDate . "',";
        $sql .= "Status='0',"; // 0 默认开启，原来是1
        $sql .= "LineType='" . $linetype . "',";
        $sql .= "wager='1',";
        $sql .= "UseDate='0',";
        $sql .= "A_Point='100',";
        $sql .= "B_Point='0',";
        $sql .= "C_Point='0',";
        $sql .= "D_Point='0',";
        $sql .= "World='" . $world . "',";
        $sql .= "Corprator='" . $corprator . "',";
        $sql .= "Super='" . $super . "',";
        $sql .= "Admin='" . $admin . "',";
        $sql .= "Bank_Name='" . $bank_name . "',";
        $sql .= "Bank_Address='" . $bank_address . "',";
        $sql .= "Bank_Account='" . $bank_account . "',";
        $sql .= "E_Mail='" . $e_mail . "',";
        $sql .= "Phone='" . $phone . "',";
        $sql .= "wechat='" . $wechat . "',";
        $sql .= "Address='" . $address . "',";
        $sql .= "RegisterIP='" . $ipAddress . "',";
        $sql .= "regSource='" . $online_status . "',";
        $sql .= "Competence='" . $Competence . "',";
        $sql .= "Reg='1';";
        $agents_in = mysqli_query($dbMasterLink, $sql);
        if (!$agents_in) {
            exit(json_encode( ['code' => '4030', 'message' => '操作失败！']));
        }

        $mysql = "update " . DBPREFIX . "web_agents_data set Count=Count+1 where UserName='$world'";
        $agents_up = mysqli_query($dbMasterLink, $mysql);
        if (!$agents_up) {
            exit(json_encode( ['code' => '4031', 'message' => '操作失败！']));
        }
        $data = getAgentUrl($ulrarr);
        exit(json_encode(['code' => 0, 'message' => '代理注册成功！', 'data' => $data]));
        break;
    case 'logout':
        session_destroy();
        unset($_SESSION);
        exit(json_encode(['code' => 0, 'message' => '已退出登录！']));
        break;
    default:
        exit(json_encode(['code' => -1, 'message' => '抱歉，您的请求不予处理！']));
        break;
}


function loginLottery()
{
    global $dbLink, $redisObj;
    $hgUserId = $_SESSION['userid'];
    $hgPwd = $_SESSION['password'];
    $username = $_SESSION['UserName'];
    $cpUrl = HTTPS_HEAD . "://" . CP_URL . '.' . CROWN_LOTTERY;

    $uniqueUnionCode = getUnionCode();
    $redisObj->setOne($hgUserId . '_HG_UNION_CP', serialize($uniqueUnionCode));
    $resultA = mysqli_query($dbLink,"select ID from ".DBPREFIX."web_agents_data where UserName='" . $aUser['Agents'] . "'");
    $rowA = mysqli_fetch_assoc($resultA);

    $hg_union_agentid = CP_UNION_VALID;
    $id = CP_UNION_VALID - $hgUserId;
    $ida = $hg_union_agentid - $rowA['ID'];
    $name = $username;
    $pwd = $hgPwd;
    $key = md5($pwd . $uniqueUnionCode.md5($name));
    $test_flag = $aUser['test_flag']; // test_flag 0 为正式用户，1 为测试用户

    $urlLogin = $cpUrl . '/login/login_ok_api.winer?agent='.CP_AGENT.'&id='.$id.'&ida='.$ida.'&name='.$name.'&pwd='.$pwd.'&key='.$key.'&flag='.$test_flag;
    $response = [
        'code' => 0,
        'data' => ['cpUrl' => $cpUrl . '/main?sign=0.asf&l_sign=1', 'urlLogin' => $urlLogin],
    ];
    return json_encode($response);
}

/**
 * 前台代理域名
 * @param $domain
 * @return array
 */
function getAgentUrl($domain)
{
    $agentUrl = explode(',', $domain);
    $rn = array_rand($agentUrl);
    $data = [
        'agent_url' => HTTPS_HEAD . '://ag.' . $agentUrl[$rn],
    ];
    return $data;
}

/**
 * 加密
 * @param $md5Password
 * @param $username
 * @return string
 */
function pwdEncryption($md5Password, $username)
{
    $pwdEncryptionSTR = md5(md5($md5Password.sha1(LOGIN_ENCRYPTIONCODE)).strtolower(trim($username)));
    return $pwdEncryptionSTR;
}


