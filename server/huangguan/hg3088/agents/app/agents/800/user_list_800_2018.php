<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");    
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");    
header("Cache-Control: no-store, no-cache, must-revalidate");    
header("Cache-Control: post-check=0, pre-check=0", false);    
header("Pragma: no-cache"); 
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST['lv']; //插入日志
$bAuth = ADMIN_SUB_USER == $loginname ? 0 : 1; // 限制某账号权限
$admin_sub_user = ADMIN_SUB_USER;
//echo '管理员:'.$loginname.'--bAuth:'.$bAuth;echo '<br>';

/*
 *
 * 现金系统只显示这些订单：
    1、第三方存款 Type=S，Checked=1，Payway!=N Payway=W ,PayType>0
    2、会员前台提款（审核通过并出款成功）   Type=T，Payway=0，Checked=2
    3、后台操作的存提款  手动存款 Type=S，discounType=7，Checked=2。  手动提款 Type=T，discounType=6，Checked=1
        1）显示全部订单，并显示状态
        2）汇总只包含（全部审核通过的）
 *
 */
$active=$_REQUEST['active'];
$rtype=$_REQUEST['type']; // 审核方式 存入(S)，提出类型(T)
$s_type=strval($_REQUEST['save_type']); //存入方式(人工、快速充值，第三方)
//echo '审核方式rtype:'.$rtype.'---存入方式s_type:'.$s_type;echo '<br>';

$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$page=$_REQUEST["page"];
$seach_name = isset($_REQUEST['seach_name'])?$_REQUEST['seach_name']:'' ; // 关键字查询
if ($date_start==''){
	$date_start=date('Y-m-d');
}
if ($date_end==''){
   // $date_end=date('Y-m-d',time()+86400);
    $date_end = date('Y-m-d');
}
if($seach_name){ // 关键字查询
    $seach_name_sql = "and (UserName LIKE '%$seach_name%' or Name LIKE '%$seach_name%')";
}

// 存入审核参数
$id=$_REQUEST['id'];
$memname=$_REQUEST['mid']; // 审核中会有mid 参数
if ($memname==''){
    $mem="";
}else{
    $mem="and a.UserName='$memname'";
}

$dep_gold=$_REQUEST['dep_gold']; // 存入金额
$discountType = $_REQUEST['discount_type']; // 人工操作存取款类别（用于计算输赢额度，不包括优惠和返点）
$updateCredit = ''; // 更新输赢额度，默认为空

$Checked=$_REQUEST['Checked'];
$active=$_REQUEST['active'];
$date=date('Y-m-d H:i:s');
$orderWaterno= isset($_REQUEST['orderWater'])?$_REQUEST['orderWater']:''; // 系统水单号

if($active=='Y'){ // 接收审核状态
    $mysql="select `type`,userid,count_bet from ".DBPREFIX."web_sys800_data where Checked='0' && ID=".$id;
    $rs=mysqli_query($dbLink,$mysql);
    $rows=@mysqli_fetch_assoc($rs); //array(2) { ["type"]=> string(1) "S" ["userid"]=> string(3) "100" }
    // 判断是否更新输赢额度-20180815
    $updateCredit = $discountType == 3 || $discountType == 4 ? '' : ($rows['type'] == 'S' ? " ,WinLossCredit=WinLossCredit+$dep_gold " : " ,WinLossCredit=WinLossCredit-$dep_gold ");
    // 快速充值更改存款次数
    //$updateDepositTimes = $discountType == 9 ? ($rows['type'] == 'S' ? ",DepositTimes=DepositTimes+1" : "") : '';
    if($rows['type']=='S') { // 人工存款
        $loginfo_status = '<font class="red">手工存入成功</font>' ;
        if($_REQUEST['Checked']==1) { // 入款审核 成功
            // ---------------------------------------------------------count bet start-----------------------------------------------------------------------
            // 判断是否更新打码量（入款更新-20191204）
            $betCount = round($dep_gold); // 打码量四舍五入
            $updateMemberOweBet = $rows['count_bet'] == 0 ? '' : ",owe_bet=owe_bet+$betCount"; // 累计会员提款打码量
            $update800OweBet = $rows['count_bet'] == 0 ? '' : ",owe_bet=$betCount"; // 更新此入款单打码量
            // 判断是否更新打码量统计时间（入款更新-20191204）
            $countBetTime = countBetTime($rows['userid']);
            $updateMemberOweBet .= ($countBetTime == '' && $rows['count_bet'] == 1 ? ",owe_bet_time='$date'" : ""); // 以第一次充值时间为统计打码量的开始时间
            // ---------------------------------------------------------count bet end-----------------------------------------------------------------------
            $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
            if($beginFrom){
                $mysql_check=mysqli_query($dbMasterLink,"select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update");
                $mysqlCheckResult = mysqli_fetch_assoc($mysql_check);
                if( isset($mysqlCheckResult['Checked']) && $mysqlCheckResult['Checked']==0 ){
                    $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
                    if($resultMem){
                        $rowMem = mysqli_fetch_assoc($resultMem);
                        // 人工存入不包含存款次数
                        $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$dep_gold,DepositTimes=DepositTimes+1 $updateCredit $updateMemberOweBet where ID='".$rows['userid']."'";
                        if(mysqli_query($dbMasterLink,$mysql)){
                            $mysql="update ".DBPREFIX."web_sys800_data set Gold='".$dep_gold."',Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date',Preferential='0' $update800OweBet where ID=".$id;
                            if(mysqli_query($dbMasterLink,$mysql)){
                                $res = level_deal($rows['userid'],$dep_gold);//用户层级关系处理
                                $moneyLogRes=addAccountRecords(array($rowMem['ID'],$memname,$rowMem['test_flag'],$rowMem['Money'],$dep_gold,$rowMem['Money']+$dep_gold,11,6,$id,"[存款(*)笔]存款审核,成功入账"));
                                if($res&&$moneyLogRes){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
            }
        }elseif($_REQUEST['Checked']==-1){ // 入款审核 失败
            $loginfo_status = '<font class="red">手工存入失败</font>' ;
            $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date' where ID=".$id;
            mysqli_query($dbMasterLink,$mysql);
        }
        $loginfo = $loginname.' 对现金系统会员帐号 <font class="green">'.$memname.'</font> 入款操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($dep_gold,2).'</font>,id为 '.$id.',水单号为 <font class="blue">'.$orderWaterno.'</font>' ;
    }


    if($rows['type']=='T') { // 人工提款（注意：经确认处理成人工扣款，不做打码量的处理-20191231）
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if($beginFrom) {
            $mysql_check="select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update ";
            $result_check = mysqli_query($dbMasterLink,$mysql_check);
            $row_check = mysqli_fetch_assoc($result_check);
            if($_REQUEST['Checked']==-1) { // 不通过
                $loginfo_status = '<font class="red">手工提款失败</font>' ;
                if(isset($row_check['Checked']) && $row_check['Checked'] == 0) {
                    $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
                    if($resultMem){
                        $rowMem = mysqli_fetch_assoc($resultMem);
                        $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$dep_gold where ID='".$rows['userid']."'";
                        if(mysqli_query($dbMasterLink,$mysql)){
                            $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date' where ID=".$id;
                            $moneyLogRes=addAccountRecords(array($rowMem['ID'],$memname,$rowMem['test_flag'],$rowMem['Money'],$dep_gold,$rowMem['Money']+$dep_gold,12,6,$id,"[现金系统手工审核(*)笔]提款审核,失败入账"));
                            if(mysqli_query($dbMasterLink,$mysql)&&$moneyLogRes){
                                mysqli_query($dbMasterLink,"COMMIT");
                            }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                        }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                    }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
            }elseif($_REQUEST['Checked'] ==1) {
                $loginfo_status = '<font class="red">手工提款成功</font>' ;
                if(isset($row_check['Checked']) && $row_check['Checked'] == 0) {
                    $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money,WinLossCredit from  ".DBPREFIX.MEMBERTABLE." where  ID='{$rows['userid']}' for update");
                    if($resultMem){
                        $mysql="update ".DBPREFIX.MEMBERTABLE." set WithdrawalTimes=WithdrawalTimes+1 $updateCredit where ID='".$rows['userid']."'"; // 审核提款成功后扣除输赢额度（修复提款次数未累计问题）
                        if(mysqli_query($dbMasterLink,$mysql)){
                            $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date' where ID=".$id;
                            if(mysqli_query($dbMasterLink,$mysql)){
                                $res = level_deal($rows['userid'],$dep_gold,1);//用户层级关系处理
                                if($res){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{  mysqli_query($dbMasterLink,"ROLLBACK"); }
                            }else{  mysqli_query($dbMasterLink,"ROLLBACK"); }
                        }else{  mysqli_query($dbMasterLink,"ROLLBACK"); }
                    } else {  mysqli_query($dbMasterLink,"ROLLBACK");  }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                }
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
        }
        $loginfo = $loginname.' 对现金系统会员帐号 <font class="green">'.$memname.'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($dep_gold,2).'</font>,id为 '.$id.',水单号为 <font class="blue">'.$orderWaterno.'</font>' ;
    }


}

if ($rtype==''){ // 审核方式 存入(S)，提出类型(T)
    $type="";
}else{
    $type="and Type='$rtype'";
}

// 第三方
$thirdSql = "SELECT id,title,account_company,thirdpay_code,has_company_youhui from ".DBPREFIX."gxfcy_pay where status=1";
$thirdResult = mysqli_query($dbLink,$thirdSql);
$thirdPay=array();
while ($row = mysqli_fetch_array($thirdResult)){
    $thirdPay[$row['id']]=$row;
}

if($s_type=='rengong'){ // 人工查询
    $sql = "select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data
where `Type` in ('S','T') and discounType in (1,2,3,4,5,6,7,8) and `Checked`=1  and AddDate BETWEEN '$date_start' and '$date_end' $type  $seach_name_sql order by ID DESC";
}else if($s_type=='KSCZ' || !$bAuth){ //s_type = KSCZ   $admin_sub_user=admin668   快速充值包含 自动审核成功的和 后台快速充值存入
    // 自动审核成功  discounType=9  User='' Checked=1  后台快速充值User=不为空  Checked !=-1
    $sql = "select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data
where `Type` = 'S' and discounType =9  and AddDate BETWEEN '$date_start' and '$date_end' $type $seach_name_sql order by ID DESC";
}else if(strpos($s_type,'_') !== false){ // 第三方查询  s_type = third_88
    $aS_type = explode('_',$s_type);
    $s_type = $aS_type[1];
    $save_type=" and PayType='$s_type' ";
    $type= " and Type='S' ";
    $_POST['type'] = 'S'; // 审核方式显示存入
    $sql = "select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data 
where `PayType`>0  and `Checked`=1 and AddDate BETWEEN '$date_start' and '$date_end' $type $save_type $seach_name_sql order by ID DESC";
}else{  // 默认显示
        // 第三方、 `PayType`>0   `Payway` !='N'
        //前台银行卡提款、  `Type` = 'T'  `Payway`=0
        //后台手动存款、手动提款  `Type` in ('S','T')  discounType in (1,2,3,4,5,6,7,8)   Payway=W   Checked != -1(失败的不显示)
        $sql ="select * from (
select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data 
where `PayType`>0  and `Payway` !='N' and `Checked`=1 and `Type` = 'S' and AddDate BETWEEN '$date_start' and '$date_end'
UNION
select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data
where `Type` = 'T' and `Payway`=0 and `Checked`=1 and AddDate BETWEEN '$date_start' and '$date_end'
UNION 
select `ID`,`userid`,`PayType`,`AddDate`,`Date`,`AuditDate`,`Bank`,`Bank_Account`,`Bank_Address`,`Checked`,`CurType`,`discounType`,`Gold`,`moneyf`,`currency_after`,`Name`,`Waterno`,`Notes`,`Order_Code`,`PayName`,`Payway`,`Phone`,`reason`,`Type`,`User`,`UserName` from hgty78_web_sys800_data
where `Type` in ('S','T') and discounType in (1,2,3,4,5,6,7,8,9) and `Checked`!=-1 and AddDate BETWEEN '$date_start' and '$date_end'
) as aa where 1 $type $seach_name_sql order by ID DESC
";

}

//echo '<pre>';
//echo $sql;echo '<br>';
//echo '</pre>';

$result = mysqli_query($dbLink,$sql);
$gold_total=0;
$num=0;
$page_size=50;
if ($page=='') {
    $page=0;
}
$start_limit = $page_size * $page;
$end_limit = $page_size * ($page+1);

while ($row = mysqli_fetch_array($result)) {   // 全部页统计
    if ($start_limit <= $num && $num < $end_limit) {
        if ($thirdPay[$row['PayType']]['has_company_youhui'] == 1) { // 将现金与优惠分开，减去优惠。将对应的三方单号找出来，方便匹配会员真实的充值金额
            $thirdBankOrderNo[] = $row['Order_Code'];
        }
        $cash_lists[] = $row;
    }
}
// 三方订单查询
$thirdBankOrderNo = "'".implode("','",$thirdBankOrderNo)."'";
$sql="select Order_Code,Gold from ".DBPREFIX."web_thirdpay_data where Order_Code in ({$thirdBankOrderNo})";
$result = mysqli_query($dbLink,$sql);
$orderCodeGold=[];
while ($row = mysqli_fetch_array($result)) {
    $orderCodeGold[$row['Order_Code']]=$row;
}

foreach ($cash_lists as $k => $row){
    if($row['discounType'] == '0' and $row['Type']=='S' and $row['PayType']>0 and $row['Checked'] ==1 ){ // 第三方存款 Payway=W
        if (array_key_exists($row['Order_Code'], $orderCodeGold)){
            $row['Gold']=$orderCodeGold[$row['Order_Code']]['Gold'];
            $cash_lists[$k]['Gold']=$orderCodeGold[$row['Order_Code']]['Gold'];
            $gold_total += $row['Gold'];
        }
        else{
            $gold_total += $row['Gold'];
        }
    }
    //if($row['discounType'] == '6' and $row['Type']=='T' and $row['Checked'] ==1 ){ // 人工提款
    if(in_array($row['discounType'] , array('6','8')) and $row['Type']=='T' and $row['Payway'] =='W' and $row['Checked'] ==1 ){ // 人工提款  Payway=W
        $gold_total -= $row['Gold'];
    }

    if(in_array($row['discounType'] , array('1','2','3','4','5','7','9')) and $row['Type']=='S' and $row['Checked'] ==1 ){ // Payway=W   人工存款(1,2,3,4,5,7)  快速充值(9)
        $gold_total += $row['Gold'];
    }
    if($row['discounType'] == '0' and $row['Type']=='T' and $row['Payway']==0 and $row['Checked']==1){ // 前台提款  Payway=''
        $gold_total -= $row['Gold'];
    }
	$num+=1;
}

$cou=$num;
$page_count=ceil($cou/$page_size);
if ($cou==0){
	$page_count=1;
}

?>
<html>
<head>
<title>800系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    input.za_text_auto{width: 100px;}
    .m_title{ font-weight: bold;}
</style>
</head>
<body >
<dl class="main-nav">
    <dt>现金系统</dt>
    <dd>
        <div id="Layer1" class="layer_div" onMouseOver="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')">
            <ul>
              <li class="mou first"><a href="user_list_800_2018.php?uid=<?php echo $uid?>">帐户查询</a></li>
              <li class="mou" ><a href="user_edit_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>">存入帐户</a></li>
              <li class="mou" ><a href="gift_list_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>">赠送彩金</a></li>
            </ul>
        </div>

        <table >
          <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
          <tr class="m_tline">
                <td width="70" >
                    <a class="layer_div_a" href="user_list_800_2018.php?uid=<?php echo $uid?>" onMouseOver="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')"><font color="#990000">帐户作业</font></a>
                </td>
                <td>
                    关键字查找:<input type="text" name="seach_name" value="<?php echo $seach_name?>" class="za_text_auto"/>
                    &nbsp;--&nbsp;日期区间:
                    <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_start?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                    ~&nbsp;
                    <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                    --
                    <input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('yeterday')" />&nbsp;
                    <input type="button" class="match_date_today" value="今日" onclick="match_date('today')" />&nbsp;
                    &nbsp;--&nbsp;审核方式:
                    <!--<select name="type" id="type" class="za_select za_select_auto" onchange="chooseType(this)">-->
                    <select name="type" id="type" class="za_select za_select_auto" onchange="">
                        <option value="">全部类别</option>
                        <option value="S"<?php if($_POST['type']=="S"){?> selected<?php }?>>存入</option>
                        <option value="T"<?php if($_POST['type']=="T"){?> selected<?php }?>>提出</option>
                    </select>
                    &nbsp;--&nbsp;存入方式:
                    <select name="save_type" id="save_type" class="za_select za_select_auto" onchange="">
                        <?php
                            if($bAuth) {?>
                                <option value = "" > 全部</option >
                        <?php }?>
                        <option value="rengong" <?php echo $s_type=='rengong'?'selected':''; ?> >人工</option>
                        <option value="KSCZ" <?php echo $s_type=='KSCZ'?'selected':''; ?> >快速充值</option>
                        <?php
                            if($bAuth) {
                                // 公司银行卡等
//                            $bankSql = "select id,bankcode,bank_name from ".DBPREFIX."gxfcy_bank_data where status=1";
//                            $bankResult = mysqli_query($dbLink,$bankSql);
//                            $bankPay = array();
//                            while ($row = mysqli_fetch_array($bankResult)){
//                                if($row['id'] == $s_type) {
//                                    echo "<option value='bank_{$row['id']}' selected>".$row['bank_name']."</option>";
//                                }else {
//                                    echo "<option value='bank_{$row['id']}'>".$row['bank_name']."</option>";
//                                }
//                                $bankPay[$row['id']]=$row;
//                            }
                                foreach ($thirdPay as $k => $row){
                                    if($row['id'] == $s_type) {
                                        echo "<option value='third_{$row['id']}' selected>".$row['title']."</option>";
                                    }else {
                                        echo "<option value='third_{$row['id']}'>".$row['title']."</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                 &nbsp;
                      <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
                    &nbsp;--&nbsp;总页数:
                      <select id="page" name="page"  class="za_select za_select_auto" onChange="self.myFORM.submit()">
                      <?php
                      if ($page_count==0){
                          $page_count=1;
                      }
                      for($i=0;$i<$page_count;$i++){
                        if($page == $i){
                            echo "<option selected value='$i'>".($i+1)."</option>";
                        }else{
                            echo "<option  value='$i'>".($i+1)."</option>";
                        }

                      }
                      ?>
                      </select>
                     / <?php echo $page_count?> 页</td>
          </tr>

        </FORM>
        </table>
    </dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title">
          <td>会员帐号</td>
          <td>姓名/电话</td>
          <td>银行资料</td>
          <td>币别</td>
          <td>金额变化</td>
          <td>金额(RMB)</td>
          <td>状态</td>
          <td >日期</td>
          <td >审核帐号/日期</td>
          <td width="78">操作</td>
        </tr>
    <?php
    if ($cou==0){
    ?>
        <tr class="m_cen">
            <td colspan="10">目前沒有记录</td>
        </tr>
    <?php
    }else{
        $gold = 0;
		foreach($cash_lists as $key=>$row) {
            if($row['discounType'] == '0' and $row['Type']=='S' and $row['PayType']>0 and $row['Checked'] ==1 ){ // 第三方存款 Payway=W
//                echo "1+ {$row['ID']} <br>";
                $gold += $row['Gold'];
            }
            //if($row['discounType'] == '6' and $row['Type']=='T' and $row['Checked'] ==1 ){ // 人工提款
            if(in_array($row['discounType'] , array('6','8')) and $row['Type']=='T' and $row['Payway'] =='W' and $row['Checked'] ==1 ){ // 人工提款  Payway=W
//                echo "2- {$row['ID']} <br>";
                $gold -= $row['Gold'];
            }
            //if($row['discounType'] == '7' and $row['Checked'] ==1 ){ // 人工存款 Payway=W
            if(in_array($row['discounType'] , array('1','2','3','4','5','7','9')) and $row['Type']=='S' and $row['Checked'] ==1 ){ // 人工存款(1,2,3,4,5,7)  快速充值(9)
//                echo "3+ {$row['ID']} <br>";
                $gold += $row['Gold'];
            }
            if($row['discounType'] == '0' and $row['Type']=='T' and $row['Payway']==0 and $row['Checked']==1){ // 前台提款  Payway=''
//                echo "4- {$row['ID']} <br>";
                $gold -= $row['Gold'];
            }
//            if($row['discounType'] == '0' and $row['Type']=='S' and $row['PayType']==0 and $row['Checked']==1){ // 公司卡存款  Payway=N
////                echo "5+ {$row['ID']} <br>";
//                $gold += $row['Gold'];
//            }
        ?>

                <tr class="m_cen">
                  <td><b><?php echo $row['UserName']?></b></td>
                  <td><b><?php echo $row['Name']?></b><br><?php echo $row['Phone']?></td>
                    <td class="bank_details">
                        <?php
                        if($row['Type']=='S'){
                            if($row['discounType'] =='0' && $row['PayType']>0){ // 第三方显示
                                echo $thirdpay_code[$thirdPay[$row['PayType']]['thirdpay_code']].'-'.$thirdPay[$row['PayType']]['title'].'<br>';
                                echo $row['Order_Code'].'<br>';
                            }elseif(in_array($row['discounType'] , array('1','2','3','4','5','7'))){ //人工存入
                                echo "人工存款 - ".$row['Waterno'];
                            }elseif($row['discounType']==9 || ($row['discounType']==7 && $row['User'] ==''  && $row['Checked'] ==1)){  // 快速充值
                                echo "快速充值 - ".$row['Waterno'];
                            }
                        }elseif($row['Type']=='T'){
                            if(in_array($row['discounType'] , array('6','8'))){ //人工提款
                                echo "人工提款 - ".$row['Waterno'];
                            }else{ //前台提款
                                echo $row['Bank'].'<br>';
                                echo $row['Bank_Address'].'<br>';
                                echo $row['Bank_Account'].'<br>';
                            }
                        }
                         ?>

                    </td>
                    <td><?php echo $row['CurType'];?></td>
                    <td align="left">
                        <?php if($row['Type']=='T'){ ?>
                            提款前：<span style="color: green;"><?php echo sprintf("%01.2f", $row['moneyf']);?></span><br>
                            提款后：<span style="color: red;"><?php echo sprintf("%01.2f", $row['currency_after']);?></span>
                        <? }elseif($row['Type']=='S'){?>
                            充值前：<span style="color: green;"><?php echo sprintf("%01.2f", $row['moneyf']);?></span><br>
                            充值后：<span style="color: red;"><?php echo sprintf("%01.2f", $row['currency_after']);?></span>
                        <? } ?>
                  </td>

                  <td align="right">
                      <font color="<?php echo $row['Checked']!=1?"red":""?>">
                          <?php
                          echo ($row['Type']=='T'?"-":"").sprintf("%01.2f", $row['Gold']);
                          ?>
                      </font></td>
                  <td>
                  <?php
                  if($row['Type'] == 'T'){
                      if($row['Checked']==1)
                      {
                          echo "<font style='color:green'>成功</font>";
                      }
                      else if($row['Checked']==0)
                      {
                          echo "<font style='color:blue'>审核中</font>";
                      }
                      else if($row['Checked']==2)
                      {
                          echo "<font style='color:blue'>出款中</font>";
                      }
                      else if($row['Checked']==-1)
                      {
                          echo "<font style='color:red'>失败</font>";
                      }
                  }
                  elseif($row['Type'] == 'S'){
                      if($row['Checked']==1)
                      {
                          echo "<font style='color:green'>成功</font>";
                      }
                      else if($row['Checked']==0)
                      {
                          echo "<font style='color:blue'>审核中</font>";
                      }
                      else if($row['Checked']==-1)
                      {
                          echo "<font style='color:red'>失败</font>";
                      }
                  }

                  ?>
                  </td>
                    <td class="add_date"><?php echo $row['Date'];?></td>
                  <td >
                  <?php
                    if($row['Checked'] != 0) { //不是审核状态
                        echo $row['User']."<br>".$row['AuditDate'];
                     }
                  ?>

                  </td>
                    <td data-way="<?php echo $row['Payway']?>" data-type="<?php echo $row['discounType']?>" data-name="<?php echo $row['PayName']?>">
                        <?php
                        if($row['Type'] == 'T'){
                            if($row['Checked']==1){ // 审核成功  Payway='W' 人工提款，    Payway=''  前台提款
                                echo $row['Payway'] == 'W' ? '提出' : "前台提出";
                            }elseif($row['Checked']==0){
                        ?>
                        <form  method=post target='_self' style="margin:0px; padding:0px;">
                            <input name="Checked" type="radio" value="1" checked> 成功<br>
                            <input name="Checked" type="radio" value="-1">
                            失败：<input name="reason" type="text" size=10 class="za_text"><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=send value='人工提款' onClick="return confirm('确定审核此笔存款？')" class="za_button">
                            <input type=hidden name=id value=<?php echo $row['ID']?>>
                            <input type=hidden name=mid value=<?php echo $row['UserName']?>>
                            <input type=hidden name=dep_gold value=<?php echo $row['Gold']?>>
                            <input type=hidden name=discount_type value=<?php echo $row['discounType']?>>
                            <input type=hidden name=orderWater value=<?php echo $row['Waterno']?>>
                            <input type=hidden name=lv value=<?php echo $_REQUEST['lv']?>>
                            <input type=hidden name=active value=Y>
                        </form>
                        <?php
                            } elseif($row['Checked']==-1){
                                echo "<font style='color:red'>提款 ".$row['reason']."</font>";
                            }
                            //echo '提出';
                        }elseif($row['Type'] == 'S'){
                            if($row['Checked']==1){
                                echo '存入';
                            }elseif($row['Checked']==0) {
                                //echo "<font style='color:blue'>审核中</font>";
                      ?>
                        <form  method=post target='_self' style="margin:0px; padding:0px;">
                            <!--<input type=hidden name="Checked"  value="1" checked>
                                <input type=submit name="checkdeposit" value='存入审核' onClick="return confirm('确定审核此笔存款？')" class="button">-->
                            <input name="Checked" type="radio" value="1" checked> 成功<br>
                            <input name="Checked" type="radio" value="-1">
                            失败：<input name="reason" type="text" size=10 class="za_text"><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=send value='人工存款' onClick="return confirm('确定审核此笔存款？')" class="za_button">
                            <input type=hidden name=id value=<?php echo $row['ID']?>>
                            <input type=hidden name=mid value=<?php echo $row['UserName']?>>
                            <input type=hidden name=dep_gold value=<?php echo $row['Gold']?>>
                            <input type=hidden name=discount_type value=<?php echo $row['discounType']?>>
                            <input type=hidden name=orderWater value=<?php echo $row['Waterno']?>>
                            <input type=hidden name=lv value=<?php echo $_REQUEST['lv']?>>
                            <input type=hidden name=active value=Y>
                        </form>
                       <?php
                            }elseif($row['Checked']==-1){
                                echo "<font style='color:red'>存入 ".$row['reason']."</font>";
                            }
                        }
                        ?>
                    </td>
                </tr>

        <?php
        }
    }
    ?>
            <!-- END DYNAMIC BLOCK: row -->
            <!--<tr class="m_rig2">
                <td colspan="4"><?php /*echo date("Y-m-d",strtotime($date_start)).'&nbsp;到&nbsp;'.date("Y-m-d",strtotime($date_end)); */?></td>
                <td>总计</td>
                <td  bgcolor="#990000"><font color="#FFFFFF"><?php /*echo sprintf("%01.2f", $gold)*/?></font></td>
                <td colspan="4" > </td>
            </tr>-->
            <tr class="m_rig2">
                <td colspan="4"><?php echo date("Y-m-d",strtotime($date_start)).'&nbsp;到&nbsp;'.date("Y-m-d",strtotime($date_end)); ?></td>
                <td colspan="3">页面总计：<?php echo sprintf("%01.2f", $gold)?></td>

                <td colspan="3">总计：<?php echo sprintf("%01.2f", $gold_total)?></td>


            </tr>
          </table>

</div>
<?php
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$tomorrow=date('Y-m-d',time()+86400);
?>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="JavaScript">
    var yesterday = '<?php echo $yeterday?>';
    var today = '<?php echo $today?>';
    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( str ) {
        var date_start;
        var date_end;
        switch (str){
            case 'yeterday':
                date_start = yesterday;
                date_end = yesterday;
                break;
            case 'today':
                date_start = today;
                date_end = today;
                break;
        }
        var url = 'user_list_800_2018.php';
        var username = '<?php echo $seach_name;?>';
        var type = '<?php echo $_POST['type'];?>';
        var save_type = '<?php echo $s_type;?>';
        var page = '<?php echo $page;?>';

        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='date_end' value='"+date_end+"'>");
        form.append("<input type='hidden' name='seach_name' value='"+username+"'>");
        form.append("<input type='hidden' name='type' value='"+type+"'>");
        form.append("<input type='hidden' name='save_type' value='"+save_type+"'>");
        form.append("<input type='hidden' name='page' value='"+page+"'>");
        $(document.body).append(form);
        form.submit();

    }

    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
        if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
        else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    function MM_findObj(n, d) { //v4.0
        var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
            d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
        if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
        for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
        if(!x && document.getElementById) x=document.getElementById(n); return x;
    }

    function MM_showHideLayers() { //v3.0
        var i,p,v,obj,args=MM_showHideLayers.arguments;
        for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
            if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
            obj.visibility=v; }
    }
    // 类型选择
    function chooseType(obj) {
        var val = obj.value ;
        var obj_str = obj.getAttribute('name') ;
        console.log(obj_str);
        if(val){
            if(obj_str=='save_type'){ // 当选择存入方式时
                document.getElementById('type').options[0].setAttribute('selected', 'selected');
            }else{ // 审核方式
                document.getElementById('save_type').options[0].setAttribute('selected', 'selected');
            }
        }
    }

</script>
</body>
</html>
    <!-- 插入系统日志 -->
<?php
if ($active=='Y'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>