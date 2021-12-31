<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include('include/config.inc.php');
//require ("include/redis.php");
//include('include/define_function_list.inc.php');
$keys=$_REQUEST['keys'];

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

if ($keys=='add'){

    $EditDate=date('Y-m-d');//新增日期
    $AddDate=date('Y-m-d H:i:s');//新增日期时间
    $alias=$_REQUEST['alias'];// 真实姓名
    $phone=$_REQUEST['phone']; //手机
    $wechat=$_REQUEST['wechat']; //微信
    $username=$_REQUEST['username'];//帐号
    $password=$_REQUEST['password'];//密码
    $password2=$_REQUEST['password2'];//密码
    $address=$_REQUEST['address'];//QQ/Skype
    $bank_name=$_REQUEST['bank_name'];//银行名称
    $bank_account=$_REQUEST['bank_account'];//银行账号
    $bank_address=$_REQUEST['bank_address'];//银行地址
    $paypassword=$_REQUEST['paypassword'];// 提款密码
    $e_mail='';//邮箱
    $ip_addr=getenv("REMOTE_ADDR");//IP
    $Competence= '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,1,1,0,1,' ; // 用于显示该所拥有的权限，代理 D 层级 都是这个

    if($iptype ==3 && in_array($ip_addr,$dataiparr) || ( $iptype ==1 && in_array($ip_addr,$dataiparr) ) || ( $iptype ==4 && in_array($ip_addr,$dataiparr) ) ){
        exit("<script>alert('你已被禁止注册!');window.close();</script>");
    }

    publicRegValidate($username,'',$password,$password2,$alias,'',$phone,$wechat,'','ad');

    if(!$bank_address){ // 银行卡号验证
        echo "<script>alert('开户行地址不符合规范!');window.close();</script>" ;
        exit;
    }
    if(!isBankNumber($bank_account)){ // 银行卡号验证
        echo "<script>alert('银行卡号不符合规范!');window.close();</script>" ;
        exit;
    }
$sql = "select UserName,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='cdm323'"; // 默认总代理
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$world=$row['UserName'];
$corprator=$row['Corprator']; //  股东 B
$super=$row['Super'];  // 公司 A
$admin=$row['Admin']; // 管理员（？子账号）
$sports=$row['Sports'];
$lottery=$row['Lottery'];



$msql = "select ID from ".DBPREFIX."web_agents_data where UserName='$username'";
$mresult = mysqli_query($dbLink,$msql);
$mcou = mysqli_num_rows($mresult);
if ($mcou>0){
		echo "<script languag='JavaScript'>alert('帐户已经有人使用，请重新注册！');self.close();</script>";
		exit();
}else{

/*    // 生成唯一的代理前缀(5位字符串)，方便会员管理
    $sPrefix = make_char(5);
    $sql = "select * from ".DBPREFIX."web_agents_data where Prefix='$sPrefix'";
    $result = mysqli_query($dbLink,$msql);
    $cou = mysqli_num_rows($mresult);
    if( $cou !=0 ){
        $sPrefix = make_char(5);
    }*/

    $sql="insert into ".DBPREFIX."web_agents_data set ";
$sql.="Level='D',"; // Agent 代理 D  World 总代 C  Corprator 股东 B  Super 公司 A  Admin 管理员（？子账号）
$sql.="UserName='".$username."',";
$sql.="LoginName='".$username."',";
$sql.="PassWord='".passwordEncryption($password,$username)."',";
$sql.="PassWord_Safe='".$paypassword."',";
$sql.="Credit='0',";
$sql.="Alias='".$alias."',";
$sql.="AddDate='".$AddDate."',";
$sql.="EditDate='".$EditDate."',";
$sql.="Status='0',"; // 0 默认开启，原来是1
$sql.="LineType='".$linetype."',";
$sql.="wager='1',";
$sql.="UseDate='0',";
$sql.="A_Point='100',";
$sql.="B_Point='0',";
$sql.="C_Point='0',";
$sql.="D_Point='0',";

//$sql.="Prefix='".$sPrefix."',";
$sql.="World='".$world."',";
$sql.="Corprator='".$corprator."',";
$sql.="Super='".$super."',";
$sql.="Admin='".$admin."',";
$sql.="Bank_Name='".$bank_name."',";
$sql.="Bank_Address='".$bank_address."',";
$sql.="Bank_Account='".$bank_account."',";
$sql.="E_Mail='".$e_mail."',";
$sql.="Phone='".$phone."',";
$sql.="wechat='".$wechat."',";
$sql.="Address='".$address."',";
$sql.="RegisterIP='".$ip_addr."',";
$sql.="regSource='5',";
$sql.="Competence='".$Competence."',";
$sql.="Reg='1';";


mysqli_query($dbMasterLink,$sql) or die ("操作失败!!!");
$mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$world'";
mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!");
$agentUrl = returnAgentUrl().'/app/agents/chk_login.php?actionType=login_ad&level=D&UserName='.$username.'&PassWord='.$password; // 代理登录链接

}
}
?>
<?php
if ($keys=='add'){
?>
<script languag='JavaScript'>
    alert('恭喜注册已成功！\n帐号：<?php echo $username?>\n密码：<?php echo $password?>\n名称：<?php echo $alias?>\n银行地址：<?php echo $bank_address?>\n银行账号：<?php echo $bank_account?>\n手机号码：<?php echo $phone?>');
    parent.location='<?php echo $agentUrl; ?>';
</script>
<?php
}
?>
