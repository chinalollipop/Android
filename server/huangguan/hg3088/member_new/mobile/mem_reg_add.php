<?php

include_once('include/config.inc.php');
require ("include/address.mem.php");
require ("include/define_function_list.inc.php");

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

$ip_addr = get_ip(); // 注册ip

if(strlen($ip_addr)>0){
    $ip_addr_array = explode(',',$ip_addr);
    if(count($ip_addr_array)==1){
        if($iptype ==3 && in_array($ip_addr,$dataiparr) || ( $iptype ==1 && in_array($ip_addr,$dataiparr) ) || ( $iptype ==4 && in_array($ip_addr,$dataiparr) ) ){
            $m_status='501.1';
            $describe = "你已被禁止注册!";
            original_phone_request_response($m_status, $describe, '');
        }
    }elseif(count($ip_addr_array)>1){
        foreach($ip_addr_array as $ipk=>$ipval){
            if($iptype ==3 && in_array($ipval,$dataiparr) || ( $iptype ==1 && in_array($ipval,$dataiparr) ) || ( $iptype ==4 && in_array($ipval,$dataiparr) ) ){
                $m_status='501.1';
                $describe = "你已被禁止注册!";
                original_phone_request_response($m_status, $describe, '');
            }
        }
    }
}

$intr=$_REQUEST['introducer'];  // 介绍人

// APP包代理注册会员默认注册代理
$code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '' ; // 推广码
if(!empty($code)){ // 推广代理app包
    // 查询推广码对应的代理ID
    $sql = 'SELECT `agent_name` FROM ' . DBPREFIX . 'promotion_code WHERE `code` = ' . $code . ' LIMIT 1';
    $result = mysqli_query($dbLink, $sql);
    $promotinCode = mysqli_fetch_assoc($result);
    if(isset($promotinCode['agent_name']) && !empty($promotinCode['agent_name'])){ // 已分配
        $agent = $promotinCode['agent_name'];
    }else{ // 未分配or出错，默认代理
        if ($intr==''){
            $agent= DEFAULT_AGENT; // 默认代理
        }else{
            $agent=$intr;
        }
    }
}else{ // 正常app包
    if ($intr==''){
        $agent= DEFAULT_AGENT; // 默认代理
    }else{
        $agent=$intr;
    }
}

$keys=$_REQUEST['keys'];
// $_REQUEST['appRefer'] 13 苹果，14 安卓
$platform = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ;
if($platform =='13'){ // 苹果
    $online_status = '13' ;
}else if($platform == '14'){ // 安卓
    $online_status = '14' ;
}else{ // web 手机
    $online_status = '1' ;
}

if ($keys=='add'){
    $AddDate=date('Y-m-d H:i:s');//新增日期
    $EditDate=date('Y-m-d');//修改日期
    $username=trim($_REQUEST['username']);//帐号
    $password=trim($_REQUEST['password']);//密码
    $password2=trim($_REQUEST['password2']);// 确认密码
//    $paypassword=mysqli_real_escape_string($_REQUEST['paypassword']);// 提款密码
//    $question=mysqli_real_escape_string($_REQUEST['question']);// 密码提示问题
//    $answer=mysqli_real_escape_string($_REQUEST['answer']);// 密码提示答案
//    $birthday=mysqli_real_escape_string($_REQUEST['birthday']);// 出生日期
    $alias=trim($_REQUEST['alias']);// 真实姓名
    $phone=trim($_REQUEST['phone']); //手机
    $wechat=trim($_REQUEST['wechat']); // 微信（增加微信、QQ注册选择-20200115）
    $qq=trim($_REQUEST['qq']); // QQ
    $source=trim($_REQUEST['know_site']);// 来源 source 替换成 know_site
    $Url = getenv("HTTP_HOST");
    $mdpasswd = passwordEncryption($password,$username);

    $stop_execution = 0;
//    $birthday_y = date('Y', strtotime($birthday));
//    $birthday_m = date('m', strtotime($birthday));
//    $birthday_d = date('d', strtotime($birthday));

    if($intr && !is_username($intr)){ // 代理验证
        $m_status='401.1';
        $describe = "推荐人".$intr."不符合规范!";
    }
    if(!is_username($username)){ // 用户名验证
        $m_status='401.2';
        $describe = "用户名".$username."不符合规范!";
    }
    if($password !=$password2){
        $m_status='401.3';
        $describe = "密码与确认密码不一致!";
    }
    if(strlen($password) >15 || strlen($password)<6){
        $m_status='401.4';
        $describe = "密码不符合规范!";
    }

//    if(!isPayNumber($paypassword)){ // 支付密码验证
//        $m_status='401.6';
//        $describe = "支付密码不符合规范!";
//    }
    if($phone && !isPhone($phone)){ // 手机号码验证
        $m_status='401.7';
        $describe = "手机号码不符合规范!";
    }
    if($wechat && !isWechat($wechat)){ // 微信号码验证
        $m_status='401.8';
        $describe = "微信号码不符合规范!";
    }
    if($qq && !isWechat($qq)){ // QQ号码验证
        $m_status='401.9';
        $describe = "QQ号码不符合规范!";
    }
//    if(!checkbirthdate($birthday_m,$birthday_d,$birthday_y)){ // 出生日期验证
//        $m_status='401.9';
//        $describe = "出生日期不符合规范!年龄必须满足18-122岁之间";
//    }
    // 新增验证码
    $yzm_input = isset($_REQUEST['verifycode'])?$_REQUEST['verifycode']:'' ;
    if(!$platform){ // app 暂时不效验
        if(!$yzm_input){
            $m_status='404.11';
            $describe = "请输入验证码!";
        }

        if( LOGIN_IS_VERIFY_CODE) {
            if ($_SESSION['tncode_check'] == 'ok') {
                $_SESSION['tncode_check'] = null;
            } else {
                $status = '404.2';
                $describe = "验证码输入错误！";
                original_phone_request_response($status, $describe, $aData);
            }
        }
    }

    if(isset($m_status) && $m_status != 200) {
        original_phone_request_response($m_status, $describe, $aData);
    }

    $fields = "`ID`,`UserName`,`agent_url`,`World`,`Corprator`,`Super`,`Admin`,`Sports`,`Lottery`";
    $sql = "select $fields from ".DBPREFIX."web_agents_data where UserName='$agent'";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);
    $cous = mysqli_num_rows($result);

    if($cous==0){

        $m_status='401.10';
        $describe = "您输入的推荐代理 $agent 不存在。请查证输入的代理正确登记，谢谢!";
        original_phone_request_response($m_status,$describe,$aData);

    }
    $agent = $row['UserName'];
    $agent_url = $row['agent_url']?$row['agent_url']:'' ;
    $thisurl = getMainHost() ; // 获取当前url
    $urlsql = "select $fields from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
    $urlresult = mysqli_query($dbLink,$urlsql);
    $urlcou = mysqli_num_rows($urlresult);
    $urlrow = mysqli_fetch_assoc($urlresult);
    if($urlcou>0){
        $agent = $urlrow['UserName'] ;
        $agent_url = $thisurl ;
        $row = $urlrow;
    }

    $world=$row['World'];
    $corprator=$row['Corprator'];
    $super=$row['Super'];
    $admin=$row['Admin'];
    $sports=$row['Sports'];
    $lottery=$row['Lottery'];
    $agent == TEST_AGENT ? $test_flag = 1 : $test_flag = 0; // 判断是否测试代理线

    if ($alias_allows_duplicate && !empty($alias)){
        if(!isTrueName($alias)){ // 真实姓名验证
            $m_status='401.5';
            $describe = "真实姓名不符合规范!";
            original_phone_request_response($m_status,$describe,$aData);
        }
        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$alias'";
        $mresult = mysqli_query($dbMasterLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou>0){
            $m_status = '400.6';
            $describe = "真实姓名【{$alias}】已存在，请联系在线客服进行处理";
            original_phone_request_response($m_status,$describe,$aData);
        }
    }

    $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
    $mresult = mysqli_query($dbMasterLink,$msql);
    $mcou = mysqli_num_rows($mresult);

    if ($mcou>0){

        $m_status='401.11';
        $describe = "帐户已经有人使用，请重新注册！";
        original_phone_request_response($m_status,$describe,$aData);
    }
    else{

        $sql="insert into ".DBPREFIX.MEMBERTABLE." set ";
        $sql.="UserName='".$username."',";
        $sql.="LoginName='".$username."',";
        $sql.="PassWord='".$mdpasswd."',";
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
        $sql.="QQ='".$qq."',";
        $sql.="Source='".$source."',";
//        $sql.="Address='".$paypassword."',";
//        $sql.="question='".$question."',";
//        $sql.="answer='".$answer."',";
//        $sql.="birthday='".$birthday."',";
        $sql.="RegisterIP='".$ip_addr."',";
        $sql.="regSource='".$online_status."',";
        $sql.="Url='".$Url."',";
        $sql.="Reg='1' ";

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if(mysqli_query($dbMasterLink,$sql)){
            $mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$agent'";
            if(mysqli_query($dbMasterLink,$mysql)) { // 注册成功
                $m_status='200';
                $describe = 'success';
                mysqli_query($dbMasterLink, "COMMIT");

                // 是否是代理商推广域名
//                if($urlcou>0){
//                    // 判断是否代理商独立域名，需要跳转到主域名 开始
//                    $thisurl = getMainHost();
//                    $urlsql = "select `UserName`,`agent_url` from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
//                    $urlresult = mysqli_query($dbLink,$urlsql);
//                    $urlcou = mysqli_num_rows($urlresult);
//                    $urlrow = mysqli_fetch_assoc($urlresult);
//
//                    $m_status = '300.1';
//                    $describe = '';
//                    $aData = array(
//                        'agenturl'=>$urlrow['agent_url'] ,
//                        'agentchangeurl'=>returnAgentUrl('m').'/login?username='.$username.'&passwd='.$password.'&agenttip=1' ,
//                    );
//                    original_phone_request_response($m_status,$describe,$aData);
//
//                    return ;
//
//                    // 判断是否代理商独立域名，需要跳转到主域名 结束
//                }

            }else {
                $m_status='500.2';
                $describe = "更新代理下级会员个数操作失败!";
                mysqli_query($dbMasterLink,"ROLLBACK");
                original_phone_request_response($m_status,$describe,$aData);

            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");

            $m_status='500.1';
            $describe = "注册新会员操作失败!";
            original_phone_request_response($m_status,$describe,$aData);
        }

    }
}


// 注册成功，自动登录。并返回基本信息


$sql = "SELECT test_flag,Money,birthday,pay_class,UserName,OpenType,Pay_Type,PassWord,Credit,WinLossCredit,LoginDate,OnlineTime,EditDate,ID,Oid,Agents,Language,Status,Admin,Alias,World,
        Corprator,Super,E_Mail,Phone,Notes,Bank_Name,Bank_Account,Bank_Address,AddDate FROM `".DBPREFIX.MEMBERTABLE."`  WHERE UserName='$username' AND PassWord='$mdpasswd' AND Status<2 ";

$result = mysqli_query($dbMasterLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);

if ($cou==0){
    $m_status='401.1';
    $describe = "用户名或密码错误";
}
else {

    $str = time('s');
    $uid = strtolower(substr(md5($str), 0, 10) . substr(md5($username), 0, 10) . 'ra' . rand(0, 9));

    $credit = $row['Credit'];
    $date = date("Y-m-d");
    $todaydate = strtotime(date("Y-m-d"));
    $editdate = strtotime($row['EditDate']);

    $_SESSION['DepositTimes']= 0; // 新注册用户默认存款次数 0
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
    $_SESSION['thirdPassword'] = passwordThird($username,$password) ;
    $_SESSION['third_PassWord']= ''; // 是否存在第三方密码，存在即是导过来的会员，不存在就是新注册的
    $_SESSION['originPassword']= $password;// 原始密码
    $_SESSION['thirdUserName']= $datajson['agentid'].'_'.$username;
    $_SESSION['password']=$row['PassWord'];
    $_SESSION['birthday'] = (substr($row['birthday'],0,10)=='0000-00-00')?"":substr($row['birthday'],0,10) ;
    $_SESSION['Money'] = formatMoney($row['Money']);
    $_SESSION['gameSwitch']=$row['gameSwitch'];
    $_SESSION['Bank_Name']=$row['Bank_Name'];
    $_SESSION['Bank_Account']=$row['Bank_Account'];
    $_SESSION['Bank_Address']=$row['Bank_Address'];
    $_SESSION['AddDate']=$row['AddDate'];
    $time = ($todaydate - $editdate) / 86400;
    $_SESSION['BindCard_Flag'] = $row['Bank_Name'] && $row['Bank_Account'] && $row['Bank_Address'] ? 1 : 0; // 是否设置银行卡
    $_SESSION['Address'] = '';

    // 将当期登录用户userid , Oid写入redis

    $redisObj->setOne('loginuser_' . $row['ID'], $uid);// 写入redis
    $langx = $row['Language'];
    $sql = "update ".DBPREFIX.MEMBERTABLE." set online_status='$online_status',Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='" . BROWSER_IP . "' where UserName='$username' and Status<=1";
    $res = mysqli_query($dbMasterLink, $sql);
    if ($res) {
        $loginArr = array(
            0=>$_SESSION['userid'] ,
            1=>$_SESSION['UserName'] ,
            2=>$_SESSION['Agents'] ,
            3=>0 , // 0会员，1代理商
            4=>$row['WinLossCredit'] , // 信用额度
            5=>$_SESSION['Alias']  ,
        ) ;
        addLoginIpLog($loginArr) ; // 记录登录 ip记录

        $m_status = '200';
        $describe = '用户登录成功';
        $btset=singleset('M'); // 原生安卓苹果，投注最低限额，投注最高限额
        $aData = array(
            'UserName'=>$_SESSION['UserName'],
            'userid'=>$_SESSION['userid'],
            'Agents'=> $_SESSION['Agents'],
            'LoginTime'=>date('Y-m-d H:i:s'),
            'birthday'=>$_SESSION['birthday'],
            'Money'=>$_SESSION['Money'],
            'Phone'=>$_SESSION['Phone'],
            'test_flag'=>$_SESSION['test_flag'],
            'Oid'=>$_SESSION['Oid'],
            'Alias'=>$_SESSION['Alias']?returnRealName($_SESSION['Alias']):'',
            'Alias_hide'=>$_SESSION['Alias']?returnRealName($_SESSION['Alias']):'',
            'AddDate'=> $_SESSION['AddDate'],
            'E_Mail'=>$_SESSION['E_Mail'],
            'BindCard_Flag'=>$_SESSION['BindCard_Flag'] . '',
            'BetMinMoney'=>$btset[0],
            'BetMaxMoney'=>$btset[1],
            'DOWNLOAD_APP_GIFT_GOLD'=>DOWNLOAD_APP_GIFT_GOLD, // 老会员领取彩金金额
            'DOWNLOAD_APP_GIFT_DEPOSIT'=>DOWNLOAD_APP_GIFT_DEPOSIT, // 老会员领取彩金（存款总额）
            'membermessage'=>getMemberMessage($username,'0') // 系统短信 会员弹窗信息
        );

    } else {
        $m_status = '500';
        $describe = "会员登录操作失败！";
    }
}

original_phone_request_response($m_status,$describe,$aData);
