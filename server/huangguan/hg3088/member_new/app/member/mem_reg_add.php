<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("include/config.inc.php");
require ("include/address.mem.php");
include dirname(dirname(dirname(__FILE__)))."/style/tncode/TnCode.class.php";

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

$intr=$_REQUEST['introducer'];  // 介绍人
$main_host = getMainHost();
if ($intr==''){
	$agent= DEFAULT_AGENT; // 默认代理
}else{
	$agent=$intr;
}
$or_Source = 0 ; // 0 旧版

$keys=$_REQUEST['keys'];
if ($keys=='add'){ // 注册优化-20181009
    $AddDate=date('Y-m-d H:i:s');//新增日期
    $EditDate=date('Y-m-d');//修改日期
    $alias=$_REQUEST['alias'];// 真实姓名
    $phone=$_REQUEST['phone']; //手机
    $wechat = $_REQUEST['wechat']; // 微信（增加微信、QQ注册选择-20200115）
    $qq = $_REQUEST['qq']; // QQ
    $username= trim($_REQUEST['username']);//帐号
    $password= trim($_REQUEST['password']);//密码
    $password2= trim($_REQUEST['password2']);// 确认密码
    $thirdLottery = isset($_REQUEST['thirdLottery'])?$_REQUEST['thirdLottery']:'' ; // 第三方 彩票code

    $source= isset($_REQUEST['know_site'])?$_REQUEST['know_site']:'';// 备注 notes 替换成 know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
//    $birthday = $_REQUEST['birthday'];
//    $question = $_REQUEST['question'];
//    $answer = $_REQUEST['answer'];
//    $w_url = $_POST['website'];// 注册来源网址
//    $paypassword=$_REQUEST['paypassword'];// 取款密码
//    $ratio=$_REQUEST['radio'];// 性别

    $ip_addr = get_ip();

    if(strlen($ip_addr)>0){
        $ip_addr_array = explode(',',$ip_addr);
        if(count($ip_addr_array)==1){
            if($iptype ==3 && in_array($ip_addr,$dataiparr) || ( $iptype ==1 && in_array($ip_addr,$dataiparr) ) || ( $iptype ==4 && in_array($ip_addr,$dataiparr) ) ){
                exit("<script>alert('你已被禁止注册!');history.go(-1);</script>");
            }
        }elseif(count($ip_addr_array)>1){
            foreach($ip_addr_array as $ipk=>$ipval){
                if($iptype ==3 && in_array($ipval,$dataiparr) || ( $iptype ==1 && in_array($ipval,$dataiparr) ) || ( $iptype ==4 && in_array($ipval,$dataiparr) ) ){
                    exit("<script>alert('你已被禁止注册!');history.go(-1);</script>");
                }
            }
        }
    }

    // 新增验证码
    if(!$_REQUEST['verifycode'])
        exit("<script>alert('请输入验证码!');history.go(-1);</script>");

    if(LOGIN_IS_VERIFY_CODE) {
        $tn = new TnCode();
        if ($_SESSION['tncode_check'] == 'ok') {
            //$_SESSION['tncode_check'] = null;
        } else {
            exit("<script>alert('验证码输入错误!');history.go(-1);</script>");
        }
    }
    publicRegValidate($username,$intr,$password,$password2,$alias,$paypassword,$phone,$wechat,'','');

$sql = "select  ID,UserName,World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='$agent'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cous = mysqli_num_rows($result);
if($cous==0){
// $agent='ddm999'; // ddm999 没有这个代理
    echo "<script>alert('您输入的推荐代理 $agent 不存在。请查证输入的浏览地址正确登记，谢谢!');history.back(-1);</script>";
    exit;
}
$agent = $row['UserName'];
$agent_url = '' ;
$thisurl = getMainHost() ; // 获取当前url $_SERVER["HTTP_HOST"]
$urlsql = "select  ID,UserName,World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
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
        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$alias'";
        $mresult = mysqli_query($dbMasterLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou>0){
            exit("<script>alert('真实姓名【{$alias}】已存在，请联系在线客服进行处理');history.go(-1);</script>");
        }
    }

$msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
$mresult = mysqli_query($dbMasterLink,$msql);
$mcou = mysqli_num_rows($mresult);

if ($mcou>0){
		echo "<script languag='JavaScript'>alert('帐户已经有人使用，请重新注册！');self.location='javascript:history.go(-1)';</script>";
		exit();
}else{
$pay_class = 'a' ; // 支付分层，默认未分层 a
$Pay_Type = '1' ; // 用户所属盘口全部为D
$langx = 'zh-cn' ;
$mdpasswd = passwordEncryption($password,$username);

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
$sql.="pay_class='".$pay_class."',"; // 支付分层，默认未分层 a
$sql.="Pay_Type='".$Pay_Type."',";
$sql.="Opentype='".REG_OPEN_TYPE."',"; // 用户所属盘口全部为D
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
$sql.="Language='".$langx."',";
//$sql.="birthday='".$birthday."',";
//$sql.="question='".$question."',";
//$sql.="answer='".$answer."',";
//$sql.="Url='".$w_url."',";
//$sql.="Address='".$paypassword."',";
//$sql.="ratio='".$ratio."',";
$sql.="RegisterIP='".$ip_addr."',";
$sql.="regSource='".$or_Source."',";
$sql.="Reg='1' ";

$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
if(mysqli_query($dbMasterLink,$sql)) {
		$mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$agent'";
		if(mysqli_query($dbMasterLink,$mysql)) {
			mysqli_query($dbMasterLink, "COMMIT");
		}else {
			mysqli_query($dbMasterLink,"ROLLBACK");
			die ("操作失败!!!");
		}
}else {
	mysqli_query($dbMasterLink,"ROLLBACK");
	die ("操作失败!!!");
}

}
}
?>
<?php
if ($keys=='add'){
    $notice = '';
    if($phone){
        $notice .= '手机号码：' . $phone . '\n';
    }
    if($wechat){
        $notice .= '微信号码：' . $wechat . '\n';
    }
    if($qq){
        $notice .= 'QQ号码：' . $qq . '\n';
    }
?>
<script languag='JavaScript'>alert('恭喜注册已成功！\n帐号：<?php echo $username?>\n密码：<?php echo $password?>\n<?php echo $notice?>来源：<?php echo knowFromSite($source)?>');parent.location='<?php echo HTTPS_HEAD?>://<?php echo $main_host; ?>/login.php?username=<?php echo  $username?>&password=<?php echo  $password?>&langx=zh-cn&code=first&yzm_input=<?php echo rand();?>';</script>
<?php
}
?>