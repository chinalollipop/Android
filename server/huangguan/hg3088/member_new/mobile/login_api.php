<?php
//error_reporting(1);
//ini_set('display_errors','On');
	include_once('include/config.inc.php');
	include('include/address.mem.php');
    require ("include/define_function_list.inc.php");
// $_REQUEST['appRefer'] 0未知,1pc旧版,2pc新版,3苹果,4安卓,13 苹果原生,14 安卓原生
// $_REQUEST['appRefer'] 13 苹果，14 安卓
    $platform = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:1 ;
    $agenttip = isset($_REQUEST['agenttip'])?$_REQUEST['agenttip']:'' ;
    $username = trim($_REQUEST['username']);
    $realname = $_REQUEST['realname'];
    $demoplay = $_REQUEST['demoplay'] ? $_REQUEST['demoplay'] : '' ;
    $or_password = trim($_REQUEST['passwd']);
    $mdpasswd = passwordEncryption($or_password,$username);

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$ip_addr = get_ip();

$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

//$aData = array();
$aData = array_to_object(array());

if(stripos($ip_addr,",")) {
    $ip_addr_array = explode(',',$ip_addr);
    foreach($ip_addr_array as $ip_addr) {
        if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
            $status='501.1';
            $describe = "你已被禁止登录!";
            original_phone_request_response($status,$describe,'');
        }
    }
}else {
    if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
        $status='501.1';
        $describe = "你已被禁止登录!";
        original_phone_request_response($status,$describe,'');
    }
}

// 系统维护时，不允许登录
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
    $status='501.2';
    $describe = $sysMaintenanceData['content'];
    original_phone_request_response($status,$describe,$aData);
}

// 手机维护时，不允许登录
$sql = 'SELECT `title`, `state`, `content`, `mark`, `terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE `id`=8 ';
$oResult = mysqli_query($dbLink, $sql);
$aRow = mysqli_fetch_assoc($oResult);
if ($aRow['state']==1){ // 手机维护

    $aTerminal = explode(',', $aRow['terminal_id']);
    if(in_array($platform, $aTerminal)){
        $status='501.2';
        $describe = $aRow['content'];
        original_phone_request_response($status,$describe,$aData);
    }
}

if($platform =='13'){ // 苹果
        $online_status = '13' ;
    }else if($platform == '14'){ // 安卓
        $online_status = '14' ;
    }else{ // web 手机
        $online_status = '1' ;
//        if(!$agenttip){ // 代理域名重新登录不需要走这里
//            // 判断是否代理商独立域名，需要跳转到主域名 开始
//            $thisurl = getMainHost();
//            $urlsql = "select `UserName`,`agent_url` from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
//            $urlresult = mysqli_query($dbLink,$urlsql);
//            $urlcou = mysqli_num_rows($urlresult);
//            $urlrow = mysqli_fetch_assoc($urlresult);
//
//            if($urlcou>0){
//                $status = '300.1';
//                $describe = '';
//                $aData = array(
//                    'agenturl'=>$urlrow['agent_url'] ,
//                    'agentchangeurl'=>returnAgentUrl('m').'/login?username='.$username.'&passwd='.$or_password.'&agenttip=1' ,
//                );
//                original_phone_request_response($status,$describe,$aData);
//            }
//
//            // 判断是否代理商独立域名，需要跳转到主域名 结束
//        }

    }


    if($username!='' && $or_password !=''){
        if($demoplay =='Yes'){

//            if(!$platform) { // app 暂时不效验
//                if($_SESSION['tncode_check'] =='ok'){
//                    $_SESSION['tncode_check'] = null;
//                }else{
//                    $status = '404.2';
//                    $describe = "验证码输入错误！";
//                    original_phone_request_response($status,$describe,$aData);
//                }
//            }

            $sql="SELECT ID,test_flag,Money,birthday,pay_class,layer,UserName,OpenType,Pay_Type,PassWord,third_PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,EditDate,ID,Oid,Agents,Language,Status,Admin,Alias,World,
                    Corprator,Super,E_Mail,Phone,Bank_Name,Bank_Account,Bank_Address,Usdt_Address,gameSwitch,AddDate FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest' and Oid='logout' AND Online=0";
            $result = mysqli_query($dbLink,$sql);
            $cou = mysqli_num_rows($result);
            if($cou==0){
                $sql="SELECT ID,test_flag,Money,birthday,pay_class,layer,UserName,OpenType,Pay_Type,PassWord,third_PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,EditDate,ID,Oid,Agents,Language,Status,Admin,Alias,World,
                    Corprator,Super,E_Mail,Phone,Bank_Name,Bank_Account,Bank_Address,Usdt_Address,gameSwitch,AddDate FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest'";
                $result = mysqli_query($dbLink,$sql);
                $cou = mysqli_num_rows($result);
            }
            if($cou>0){
                while($row = mysqli_fetch_assoc($result)){
                    $demoUsers[$row["ID"]] = $row;
                }
                $row=$demoUsers[array_rand($demoUsers)];
            }
        }else{

            // 新增验证码
            $yzm_input = $_REQUEST['yzm_input'] ;
            if(!($platform=='13' || $platform=='14') && !$agenttip){ // app 暂时不效验
                if(!$yzm_input){
                    $status='404.1';
                    $describe = "请输入验证码!";
                    original_phone_request_response($status,$describe,$aData);
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

            // 导入创富会员，重名的用户需要把前缀去掉，来生成彩票登录的密码
            if (SAME_USERNAME_ADD_PREFIX )
            {
                // 用户名不带指定前缀的生成密码
                if (strpos($username, SAME_USERNAME_PREFIX) === false){
                    $third_pwd = passwordThird($username,$or_password) ;
                }
                else{// 用户名自带指定前缀的，需要去掉前缀，然后生成密码
                    $username_for_pw = explode(SAME_USERNAME_PREFIX, $username)[1];
                    $third_pwd = passwordThird($username_for_pw,$or_password) ;
                }
            }
            else{
                $third_pwd = passwordThird($username,$or_password) ; // 国民第三方密码加密
            }

            $sql = "SELECT test_flag,Money,birthday,pay_class,layer,UserName,OpenType,Pay_Type,PassWord,third_PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,EditDate,ID,Oid,Agents,Language,Status,loginTimesOfFail,resetTimesOfFail,isAutoFreeze,AutoFreezeDate,Admin,Alias,World,
                    Corprator,Super,E_Mail,Phone,Bank_Name,Bank_Account,Bank_Address,Usdt_Address,gameSwitch,AddDate,Address FROM `".DBPREFIX.MEMBERTABLE."`  WHERE UserName='$username' ";
            $result = mysqli_query($dbLink,$sql);
            $row = mysqli_fetch_assoc($result);
            $cou = mysqli_num_rows($result);

            if ($platform==13 or $platform==14){}
            else{

                $loginVerifyRealname = getSysConfig('login_verify_realname');
                if ($loginVerifyRealname == 1){
                    if ($row['Alias'] != $realname){
                        $status='401.33';
                        $describe = "登录错误！请输入正确的账户名字！";
                        original_phone_request_response($status,$describe,$aData);
                    }
                }
            }
        }
        // 当会员输入错误五次，显示：用户名或密码错误，您还有4次机会（您的账号已被冻结使用24小时，请与在线客服联系。）
        // 登录时 判断冻结，冻结剩余时间，没有冻结的则正常登录
        // 忘记密码这边：提款密码输入错误三次，账号自动冻结，24小时自动解封
        if(!$demoplay){
            if ($row['Status']==1){

                if ($row['isAutoFreeze']==0){
                    $describe = "您的账号被冻结，请与在线客服联系。";
                    $status = '400.7';
                    original_phone_request_response($status,$describe,$aData);
                }
                else{
                    // 冻结24小时解冻
                    if ((time()-strtotime($row['AutoFreezeDate']))>24*60*60){
                        $sql="update ".DBPREFIX.MEMBERTABLE." set Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where UserName='$username'";
                        $res = mysqli_query($dbMasterLink,$sql);
                        $row['Status']=0;
                    }
                    else{ // 冻结24小时内
                        $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
                        $status = '400.8';
                        original_phone_request_response($status,$describe,$aData);
                    }
                }
            }

            if ($row['Status']==2){ // 账号停用
                $describe = "由于阁下账号长时间未登陆，阁下账号已停用，请您联系客服进行启用，谢谢!";
                $status = '400.81';
                original_phone_request_response($status,$describe,$aData);
            }

            if($row['PassWord']){ // 体育这边存在密码
                if($row['PassWord'] !=$mdpasswd){
                    $nowdate = date('Y-m-d H:i:s');
                    $iloginTimesOfFail = $row['loginTimesOfFail']+1; // 失败次数
                    // 登录失败大于等于5次，自动冻结
                    if ($iloginTimesOfFail>=5){
                        $sql="update ".DBPREFIX.MEMBERTABLE." set Status=1,loginTimesOfFail=".$iloginTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
                        $res = mysqli_query($dbMasterLink,$sql);
                    }else{
                        // 小于5次，更新失败次数和时间
                        $sql="update ".DBPREFIX.MEMBERTABLE." set loginTimesOfFail=".$iloginTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
                        $res = mysqli_query($dbMasterLink,$sql);
                    }

                    if ($iloginTimesOfFail>=5){
                        $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
                    }
                    else{
                        $describe = "用户名或密码错误,您还有".(5-$iloginTimesOfFail)."次机会";
                    }
                    $status = '400.7';
                    original_phone_request_response($status,$describe,$aData);
                }
            }else{ // 从第三方导入的会员
                if ( !passwordThirdCheck($third_pwd,$row['third_PassWord']) ){
                    $describe = "登录错误！请检查用户名或密码！";
                    $status = '400.17';
                    original_phone_request_response($status,$describe,$aData);
                }
            }
        }

        // 导入过来的彩票平台老用户，是否增加代理前缀（以全新用户来登录），TRUE 需要前缀，FALSE 不需要前缀
        if (USERNAME_NEED_CP_AGENT_PREFIX){
            $third_UserName = $datajson['agentid'].'_'.$username;
        }else{
            if(!$row['third_PassWord']){ // 体育这边新注册的帐号
                $third_UserName = $datajson['agentid'].'_'.$username ;
            }else{
                $third_UserName = $username ;
            }
        }

            $str = time('s');
            $uid = strtolower(substr(md5($str), 0, 10) . substr(md5($username), 0, 10) . 'ra' . rand(0, 9));

            $credit = $row['Credit'];
            $date = date("Y-m-d");
            $todaydate = strtotime(date("Y-m-d"));
            $editdate = strtotime($row['EditDate']);
            $_SESSION['DepositTimes']=$row['DepositTimes']; //存款次数
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
            $_SESSION['Phone'] = $row['Phone'];
            $_SESSION['E_Mail'] = $row['E_Mail']; // 微信
            $_SESSION['OpenType'] = $row['OpenType'];
            $_SESSION['OnlineTime'] = $row['OnlineTime'];
            $_SESSION['pay_class'] = $row['pay_class'];
            $_SESSION['layer'] = $row['layer'];
            $_SESSION['test_flag'] = $row['test_flag'];
            $_SESSION['password']=$row['PassWord'];
            $_SESSION['thirdPassword']= $third_pwd;
            $_SESSION['third_PassWord']= $row['third_PassWord']?$row['third_PassWord']:''; // 是否存在第三方密码，存在即是导过来的会员，不存在就是新注册的
            $_SESSION['thirdUserName']= $third_UserName;
            $_SESSION['originPassword']= $or_password;// 原始密码
            $_SESSION['birthday'] = (substr($row['birthday'],0,10)=='0000-00-00')?"":substr($row['birthday'],0,10) ;
            $_SESSION['Money'] = ($demoplay =='Yes') ? formatMoney(2000) : formatMoney($row['Money']);
            $_SESSION['gameSwitch']=$row['gameSwitch'];
            $_SESSION['Bank_Name']=$row['Bank_Name'];
            $_SESSION['Bank_Account']=$row['Bank_Account'];
            $_SESSION['Bank_Address']=$row['Bank_Address'];
            $_SESSION['Usdt_Address']=$row['Usdt_Address'];
            $_SESSION['AddDate']=$row['AddDate'];
            $time = ($todaydate - $editdate) / 86400;
            $_SESSION['BindCard_Flag'] = $row['Bank_Name'] && $row['Bank_Account'] && $row['Bank_Address'] ? 1 : 0; // 是否设置银行卡
            $_SESSION['Address'] = $row['Address'];

            // 判断会员状态是否启用，否则退出
            if ($_SESSION['Status'] != 0){
                $status='401.3';
                $describe = "非常抱歉，您的账号已冻结或已停用，请您联系客服！";
            }else{
                // 将当期登录用户userid , Oid写入redis

                $redisObj->setOne('loginuser_'.$row['ID'], $uid);// 写入redis
                $langx=$row['Language'];

                if($demoplay =='Yes'){
                    //清理测试用户历史数据
                    mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_report_data where userid=".$row['ID']." and M_Name='".$row['UserName']."'");
                    $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
                    $userCpRow=mysqli_query($cpMasterDbLink,"select id from gxfcy_user where username='".$row['UserName']."'");
                    $userCp = mysqli_fetch_assoc($userCpRow);
                    if($userCp['id']>1000){
                        mysqli_query($cpMasterDbLink,"delete from gxfcy_bill where username='".$row['UserName']."'");
                        mysqli_query($cpMasterDbLink,"update gxfcy_user set currency=2000,lcurrency=2000 where username='".$row['UserName']."'");
                    }
                    $sql="update ".DBPREFIX.MEMBERTABLE." set online_status='$online_status',Money=2000,Credit=0,WinLossCredit=0,Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where ID=".$row['ID']." and Status<=1";
                    $res = mysqli_query($dbMasterLink,$sql);
                }else{
                    if($row['PassWord']){ // 有登录密码
                        $sql="update ".DBPREFIX.MEMBERTABLE." set online_status='$online_status',Oid='$uid',Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00', LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
                    }else{ // 没有登录密码
                        $sql="update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdpasswd',online_status='$online_status',Oid='$uid',Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00', LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
                    }

                    $res = mysqli_query($dbMasterLink,$sql);
                }

                if($res){
                    $loginArr = array(
                        0=>$_SESSION['userid'] ,
                        1=>$_SESSION['UserName'] ,
                        2=>$_SESSION['Agents'] ,
                        3=>0 , // 0会员，1代理商
                        4=>$row['WinLossCredit'] , // 信用额度
                        5=>$_SESSION['Alias']  ,
                    ) ;
                    addLoginIpLog($loginArr) ; // 记录登录 ip记录

                    $status = '200';
                    $describe = '登录成功!';
                    $btset=singleset('M'); // 原生安卓苹果，投注最低限额，投注最高限额
                    $aData = array(
                        'UserName'=>$_SESSION['UserName'],
                        'Agents'=> $_SESSION['Agents'],
                        'LoginTime'=>date('Y-m-d H:i:s'),
                        'birthday'=>$_SESSION['birthday'],
                        'Money'=>$_SESSION['Money'],
                        'Phone'=>yc_phone($_SESSION['Phone']),
                        'test_flag'=>$_SESSION['test_flag'],
                        'userid'=>$_SESSION['userid'],
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

                    if($aData['test_flag'] == 1 && $aData['Agents'] == 'demoguest'){
                        $uid = $aData['Oid'];
                        $aData['chess_demo_url'] = array(
                            'ky_demo_url' => "http://play.ky206.com/jump.do",
                            'ly_demo_url' => "https://demo.leg666.com",
                            //'hg_demo_url' => "/hgqp/index.php?flag=test&tip=app",
                            'vg_demo_url' => "https://sw.vgvip88.com",
                        );

                    }

                }else{
                    $status = '500';
                    $describe = "会员登录操作失败！";
                }

            }

    }else{
        $status='401.2';
        $describe = "请确认您的帐户名或密码正确，请重试。";
    }

    original_phone_request_response($status,$describe,$aData);
