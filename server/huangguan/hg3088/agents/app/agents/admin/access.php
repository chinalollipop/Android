<?php
session_start();
include ("../include/address.mem.php");
include_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");
$name=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
$action=$_REQUEST['action'];

// 存款 昨日、今日、本星期、上星期----------------------------------Start
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$this_week_monday= date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日   本周一
$this_week_sunday= date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算  本周日
$last_week_monday = date('l',time()) == 'Monday' ? date('Y-m-d', strtotime('last monday')) : date('Y-m-d',strtotime('-1 week last monday')); // 上周一
$last_week_sunday= date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期   上周日
// 存款 昨日、今日、本星期、上星期----------------------------------End

$page=$_REQUEST['page'];
if ($page==""){
	$page=0;
}
if(empty($action)){ //时间跳转， 点击查询会用到
    $actionsql ='S';
}
$money_title = '提款';
$title_show = '会员提款' ;
$searchType = '';
if($action =='S'){ // 会员存款 以及返水记录
    $actionsql = "'S','R'";
    $title_show = '会员存款' ;
    $money_title = '充值';
    $searchType = ' and Checked=1 '; // 如果是存款则仅查询成功记录-20180810
}else{ // 提款
    $actionsql = "'".$action."'" ;
}
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d');
    //$date_e=date('Y-m-d 23:59:59', time()+86400);
    $date_e=date('Y-m-d');
}
$date=date('Y-m-d H:i:s');
$active=$_REQUEST['active'];
$id=$_REQUEST['id'];
$userid=$_REQUEST['userid'];
$username=$_REQUEST['username'];
$seconds=$_REQUEST["seconds"]; // 刷新时间
$depositType=$_REQUEST['depositType'];
/*echo '<pre>';
print_r($_REQUEST);
die();*/
$redisObj = new Ciredis();
$deposit_bank_rate['deposit_bank_money'] = getSysConfig('deposit_bank_money');
$deposit_bank_rate['deposit_bank_less_ten_youhui_rate'] = getSysConfig('deposit_bank_less_ten_youhui_rate');
$deposit_bank_rate['deposit_bank_more_than_ten_youhui_rate'] = getSysConfig('deposit_bank_more_than_ten_youhui_rate');

if($active=='Y'){  //接收未处理 存款/提款审核通过
	$gold=$_REQUEST['gold'];
	$usr_gold=$_REQUEST['usr_gold']; // 金额
	$usr_Bank_Account=$_REQUEST['usr_Bank_Account']; // 银行账号
	if($_REQUEST['type']=='T') {
	    if($_REQUEST['Payway'] == 'W') { //资金已在现金系统对资金手工存取时进行处理，此处更改状态，
            $checked = '1';   //人工提款一次出款成功,
            $loginfo_status = '<font class="red">人工出款成功</font>' ;
        } else {
            $checked = '2';  //前台提款二次审核
            $loginfo_status = '<font class="red">请二次审核出款</font>' ;
        }
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        if($beginFrom){
            $mysql_check="select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update ";
            $result_check = mysqli_query($dbMasterLink,$mysql_check);
            $row_check = mysqli_fetch_assoc($result_check);
            if(isset($row_check['Checked'])&&$row_check['Checked']==0){
                $mysql="update ".DBPREFIX."web_sys800_data set Checked='$checked',User='$name',AuditDate='$date' where ID=$id";
                if(mysqli_query($dbMasterLink,$mysql)){
                    mysqli_query($dbMasterLink,"COMMIT");
                    $loginfo = $name.' 对会员帐号 <font class="green">'.$username.'</font> <font class="red">'. $title_show.'</font>订单进行了处理,更改审核状态为'.$loginfo_status;
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('提款审核操作失败！');</script>";
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "<script>alert('已审核,操作失败！');</script>";
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
        }
//        $mysql="update ".DBPREFIX."web_sys800_data set Checked='$checked' where ID=$id";
//		if(!mysqli_query($dbMasterLink,$mysql)){
//			echo "<script>alert('提款审核操作失败！');</script>";
//		}
	}elseif($_REQUEST['type']=='S') { //存款不需要二次审核
        $checked = '1';
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        if($beginFrom){
    		$mysql_check="select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update ";
		    $result_check = mysqli_query($dbMasterLink,$mysql_check);
		    $row_check = mysqli_fetch_assoc($result_check);
		    if(isset($row_check['Checked'])&&$row_check['Checked']==0){
		    	$mysql="update ".DBPREFIX."web_sys800_data set Checked='$checked',User='$name',AuditDate='$date' where ID=$id";
		    	if(mysqli_query($dbMasterLink,$mysql)){
		    		$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID='{$userid}' for update");
					if($resultMem){
						$rowMem = mysqli_fetch_assoc($resultMem);
						$mysqlMoney="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold,DepositTimes=DepositTimes+1,Credit=Credit+$gold where ID=".$rowMem['ID'];
						if(mysqli_query($dbMasterLink,$mysqlMoney)){
							$moneyLogRes=addAccountRecords(array($rowMem['ID'],$rowMem['UserName'],$rowMem['test_flag'],$rowMem['Money'],$gold,$rowMem['Money']+$gold,11,6,$id,"[账号管理-会员存款-未处理]存款审核,成功入账"));
							if($moneyLogRes){
								mysqli_query($dbMasterLink,"COMMIT");	
								$loginfo = $name.' 在会员存款页对会员帐号 <font class="green">'.$username.'</font> <font class="red">'. $title_show.'</font>订单进行了处理,金额为 <font class="red">'.number_format($gold,2).'</font>,银行账号为 <font class="blue">'.$usr_Bank_Account.'</font>';
							}else{
								mysqli_query($dbMasterLink,"ROLLBACK");
								echo "<script>alert('用户资金账变日志添加失败！');</script>";
							}
						}else{
							mysqli_query($dbMasterLink,"ROLLBACK");
							echo "<script>alert('用户资金账户账变失败！');</script>";
						}        	
					}else{
						mysqli_query($dbMasterLink,"ROLLBACK");
						echo "<script>alert('用户资金账户锁定失败！');</script>";	
					}
		    	}else{
					mysqli_query($dbMasterLink,"ROLLBACK");
					echo "<script>alert('订单更新失败！');</script>";	
				} 
		    }else{
		    	mysqli_query($dbMasterLink,"ROLLBACK");
		    	echo "<script>alert('已审核,操作失败！');</script>";	
		    }
	    }else{
        	mysqli_query($dbMasterLink,"ROLLBACK");
        	echo "<script>alert('提款审核事务开启失败！');</script>";	
        }
    }
    echo "<Script language=javascript>self.location='access.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}else if ($active=='del'){ // 删除功能已注掉
	//$mysql="delete from ".DBPREFIX."web_sys800_data where ID='$id'";
	//mysqli_query($dbMasterLink,$mysql);
	echo "<Script language=javascript>self.location='access.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}else if ($active=='res'){ // 恢复
	$gold=$_REQUEST['gold'];
	$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold,Credit=Credit+$gold where UserName='".$username."'";
	mysqli_query($dbMasterLink,$mysql);
	
	$mysql="update ".DBPREFIX."web_sys800_data set checked='1',Cancel='1' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
	echo "<Script language=javascript>self.location='access.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}
$search = preg_replace('# #','',isset($_REQUEST['search'])?$_REQUEST['search']:'') ;
$search_content = preg_replace('# #','',isset($_REQUEST['search_content'])?$_REQUEST['search_content']:'') ;
$searchsql = '';
if ($search!=''){
    $num=60;
    $searchsql ="UserName='$search' and ";
}else{
    $num=100;
}
if($search_content){
     $searchsql .= "(Notes='$search_content' or reason='$search_content') and";
}
if ($depositType){
    if ($depositType=='快速充值'){
        $searchsql .= " Payway = 'W' and discounType=9 and";
    }
    elseif ($depositType=='公司入款'){
        $searchsql .= " Payway = 'N' and";
    }
    elseif ($depositType=='三方充值'){
        $searchsql .= " Payway = 'W' and discounType=0 and Notes='即时入账' and";
    }
    elseif ($depositType=='手动存款优惠'){
        $searchsql .= " Payway = 'W' and discounType = 4 and";
    }
    elseif ($depositType=='手动存款'){
        $searchsql .= " Payway = 'W' and discounType in (1,2,3,5,6,7,8) and";
    }
    else{
        $searchsql .= " Notes='$depositType' and";
    }
}

$sql = "select ID,userid,Checked,Payway,discounType,Gold,moneyf,currency_after,Type,UserName,Date,Name,Waterno,Notes,reason,Bank_Account,Bank_Address,Bank,DepositAccount,InType,Cancel,
        AuditDate,Preferential,owe_bet from ".DBPREFIX."web_sys800_data where $searchsql addDate between '{$date_s}' and '{$date_e}' and Type IN ($actionsql) $searchType order by ID desc";
//echo $sql;echo '<br>';
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
// 总统计
$totalCount = 0;
if($cou){
    while ($record = mysqli_fetch_assoc($result)){
        if($record['Checked'] == 1)
            $totalCount += $record['Gold'];
    }
}

$page_size=$num;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink,$mysql);

// 针对现金系统存入账户优惠类别显示
function getDiscounTypeInfo($discounType){
    if($discounType ==1) {
        $discouninfo = "未知类型";
    }elseif($discounType ==2) {
        $discouninfo =  "在线入款掉单补单";
    }elseif($discounType ==3) {
        $discouninfo =  "周周返点补单";
    } elseif($discounType ==4) {
        $discouninfo =  "优惠";
    } elseif($discounType ==5) {
        $discouninfo =  "公司入款补单";
    } elseif($discounType ==6) {
        $discouninfo =  "手工提出";
    } elseif($discounType ==7) {
        $discouninfo = "手工存入";
    } elseif($discounType ==8) {
        $discouninfo = "AG掉单存入提出";
    }
    return $discouninfo;
}
?>
<html>
<head>
<title>会员存取</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    input.za_text{width: auto;}
</style>
</head>

<body onLoad="onLoad()">
<dl class="main-nav"><dt><?php echo  $title_show ?></dt><dd></dd></dl>
<div class="main-ui width_1300">
    <table class="m_tab">
    <FORM id="myFORM" ACTION="" METHOD=POST name="FrmData">
        <input type="hidden" name="action" value="<?php echo $action; ?>"/>
    <tr class="m_title">
        <td colspan="11">
            <?php echo $title_show ?>：
            &nbsp;&nbsp;<input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('yesterday')" />&nbsp;
            <input type="button" class="match_date_today" value="今日" onclick="match_date('today')" />&nbsp;
            <input type="button" class="match_date_this_week" value="本星期" onclick="match_date('this_week')" />&nbsp;
            <input type="button" class="match_date_last_week" value="上星期" onclick="match_date('last_week')" />&nbsp;&nbsp;
        </td>
    </tr>
	<tr class="m_title">
	  <td colspan="11">
          <select class="za_select za_select_auto" onChange="document.FrmData.submit();" id="seconds" name="seconds">
              <option value="手动刷新">手动刷新</option>
              <option value="10" <?php echo $seconds=='10'?'selected':''?> >10秒</option>
              <option value="20" <?php echo $seconds=='20'?'selected':''?> >20秒</option>
              <option value="30" <?php echo $seconds=='30'?'selected':''?> >30秒</option>
              <option value="60" <?php echo $seconds=='60'?'selected':''?> >60秒</option>
          </select>&nbsp;
          <span id="ShowTime"></span>
          时间区间：
          <input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
          至
          <input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
          &nbsp;关键字查找:
          <input type=TEXT name="search" size=10 value="<?php echo $search?>" maxlength=20 class="za_text" placeholder="会员账号">
          <?php
          if($action == 'S'){
          ?>
          &nbsp;存款类型
          <select class="za_select za_select_auto" onChange="document.FrmData.submit();" id="depositType" name="depositType">
              <option ></option>
              <option value="手动存款" <?php echo $depositType=='手动存款'?'selected':''; ?> >手动存款</option>
              <option value="手动存款优惠" <?php echo $depositType=='手动存款优惠'?'selected':''; ?> >手动存款优惠</option>
              <option value="快速充值" <?php echo $depositType=='快速充值'?'selected':''; ?> >快速充值</option>
              <option value="三方充值" <?php echo $depositType=='三方充值'?'selected':''; ?> >三方充值</option>
              <option value="公司入款" <?php echo $depositType=='公司入款'?'selected':''; ?> >公司入款</option>
              <option value="天天返水" <?php echo $depositType=='天天返水'?'selected':''; ?> >彩金：天天返水</option>
              <option value="时时返水" <?php echo $depositType=='时时返水'?'selected':''; ?> >彩金：时时返水</option>
              <option value="每月逢6必发" <?php echo $depositType=='每月逢6必发'?'selected':''; ?> >彩金：每月逢6必发</option>
              <option value="每月逢8必发" <?php echo $depositType=='每月逢8必发'?'selected':''; ?> >彩金：每月逢8必发</option>
              <option value="棋牌游戏彩金" <?php echo $depositType=='棋牌游戏彩金'?'selected':''; ?> >彩金：棋牌游戏彩金</option>
              <option value="全勤回馈奖" <?php echo $depositType=='全勤回馈奖'?'selected':''; ?> >彩金：全勤回馈奖</option>
              <option value="周周转运金" <?php echo $depositType=='周周转运金'?'selected':''; ?> >彩金：周周转运金</option>
              <option value="国庆有惊喜 优惠乐翻天" <?php echo $depositType=='国庆有惊喜 优惠乐翻天'?'selected':''; ?> >彩金：国庆有惊喜 优惠乐翻天</option>
              <option value="彩票洗码之王" <?php echo $depositType=='彩票洗码之王'?'selected':''; ?> >彩金：彩票洗码之王</option>
              <option value="双旦迎春彩金" <?php echo $depositType=='双旦迎春彩金'?'selected':''; ?> >彩金：双旦迎春彩金</option>
              <option value="下载APP免费领取彩金" <?php echo $depositType=='下载APP免费领取彩金'?'selected':''; ?> >彩金：下载APP免费领取彩金</option>
              <option value="APP幸运红包活动" <?php echo $depositType=='APP幸运红包活动'?'selected':''; ?> >彩金：APP幸运红包活动</option>
              <option value="VIP晋升彩金" <?php echo $depositType=='VIP晋升彩金'?'selected':''; ?> >彩金：VIP晋升彩金</option>
              <option value="中秋国庆赠送彩金" <?php echo $depositType=='中秋国庆赠送彩金'?'selected':''; ?> >彩金：中秋国庆赠送彩金</option>
          </select>&nbsp;
          <?php } ?>
          <input type=TEXT name="search_content" size=10 value="<?php echo $search_content?>" maxlength=20 class="za_text" placeholder="其他关键字" style="display:none">
          <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
          <select name='page' id="page" onChange="self.myFORM.submit()">

              <?php
              if ($page_count==0){
                  $page_count=1;
              }
              for($i=0;$i<$page_count;$i++){
                  if ($i==$page){
                      echo "<option selected value='$i'>".($i+1)."</option>";
                  }else{
                      echo "<option value='$i'>".($i+1)."</option>";
                  }
              }
              ?>
          </select> 共<?php echo $page_count?> 页
      </td>
	</tr>
    </FORM>
	<tr class="m_title">
	  <td width="4%">编号</td>
	  <td width="10%">帐号</td>
	  <td width="8%">会员姓名</td>
	  <td width="10%">金额变化</td>
	  <td width="10%">金额</td>
	  <td width="15%">日期时间</td>
	  <td width="15%">开户银行</td>
	  <td width="10%">银行账号</td>
      <?php if($action == 'S'){?>
          <td width="5%">打码量</td>
      <?php }?>
      <td width="5%">操作</td>
      <td width="10%">备注 / 理由</td>
	</tr>
    <?php if ($cou == 0){ ?>
    <tr class="m_cen">
        <td colspan="13">目前沒有记录</td>
    </tr> <?php } ?>
<?php
$i=1;
$pageCount = '0.00';
while ($row = mysqli_fetch_assoc($result)){
$id=$row['ID'];
if($row['Checked'] == 1)
    $pageCount += $row['Gold'];
?>
  <tr class="m_cen">
    <td align="center"><?php echo $i?></td>
    <td align="center"><font color=red><?php echo $row['UserName']?></font></td>
    <td class="user_name"> <?php echo $row['Name'] ;?> </td>
    <td class="b_name"><?php echo $money_title?>前：<font class="green"><?php echo sprintf("%.2f", $row['moneyf'])?></font><br><?php echo $money_title?>后：<font class="red"><?php echo sprintf("%.2f", $row['currency_after'])?></font></td>
    <td align="center" class="user_gold"><?php echo number_format($row['Gold'],2)?></td>
	<td ><?php echo $row['AuditDate']?></td>
    <td align="center">
        <?php
        if('USDT提款' == explode('-' , $row['InType'])[0]) {
            echo '类型：'. $row['InType'] . '<br>';
        }else{
            echo $row['Bank_Address'].'<br>'.$row['Bank'].'<br>';
        }
        ?>
    </td>
    <td align="center">
        <?php
        if($row['Type']=='S'){
            if(in_array($row['discounType'] , array('1','2','3','4','5','7','9')) and $row['Payway']=='W'){ //水单存款
                echo $row['Waterno'];
            }else{ //  Payway=N公司存  // W第三方
                echo $row['Bank_Account'];
                if ($row['Bank']=='USDT虚拟货币') {
                    if($row['Preferential']>0) {
                        $aDepositAccount = explode('-',$row['DepositAccount']);
                        if($aDepositAccount[3] == 1){
                            echo '优惠：'.'1%';
                        }
                        elseif($aDepositAccount[3] == 2){
                            echo '优惠：'.'2%';
                        }
                        else{
                            echo '无优惠';
                        }
                    }
                }else{
                    //echo $row['currency_after']-$row['moneyf'] < 100000 ? '。优惠：1%' : '。优惠：2%';
                    echo $row['currency_after']-$row['moneyf'] > $deposit_bank_rate['deposit_bank_money'] ? ' 优惠：'.($deposit_bank_rate['deposit_bank_more_than_ten_youhui_rate']*100)."%" : ' 优惠：'.($deposit_bank_rate['deposit_bank_less_ten_youhui_rate']*100)."%";
                }
            }
        } elseif($row['Type']=='T'){
            if(in_array($row['discounType'] , array('6','8')) and $row['Payway']=='W'){  // 人工提款
                echo $row['Waterno'];
            }else{
                echo $row['Bank_Account'];// 前台用户提款
            }
        }

        ?></td>
    <?php if($action == 'S'){?>
        <td align="center"><?php echo $row['owe_bet']?></td>
    <?php }?>
	<td align="center do_action">
<?php
if ($row['Checked']==0){ // 未处理 (Checked: 0 首次提交订单 2 二次审核  1成功 -1失败)
?>
	<form  method=post target='_self'>
        <input type=hidden name=lv value=<?php echo $lv?>>
        <input type=hidden name=usr_gold value=<?php echo $row['Gold']?>>
        <input type=hidden name=usr_Bank_Account value=<?php echo $row['Bank_Account']?>>
        <!--<input type=submit name=send value=未处理 onClick="return confirm('确定审核此笔单?')" class="za_button">-->
        <?php
            if($row['Type']=='T') {
                if($row['Payway']=='W' || in_array($row['discounType'] , array('6','8'))) { // 人工处理 出款审核一次
                    echo '<input type=submit name=send value=人工处理 onClick="return confirm(\'确定审核此笔单?\')" class="za_button">';
                }else{ //前台出款审核两次
                    echo '<input type=submit name=send value=未处理 onClick="return confirm(\'确定审核此笔单?\')" class="za_button">';
                }
            } elseif ($row['Type']=='S') { // 存款一次
                echo '<input type=submit name=send value=未处理 onClick="return confirm(\'确定审核此笔单?\')" class="za_button">';
            }
        ?>
        <input type=hidden name=Payway value=<?php echo $row['Payway']?>>
        <input type=hidden name=id value=<?php echo $row['ID']?>>
        <input type=hidden name=userid value=<?php echo $row['userid']?>>
        <input type=hidden name=mid value=<?php echo $row['UserName']?>>
        <input type=hidden name=username value=<?php echo $row['UserName']?>>
        <?php
        if($row['Type']=='S') {
           // $S_Process_Gold += $row['Gold'];
        ?>
            <input type=hidden name=gold value=<?php echo $row['Gold']?>>
        <?php }?>
        <?php
        if($row['Type']=='T') {
           // $T_Process_Gold += $row['Gold'];
        ?>
            <input type=hidden name=gold value=0>
        <?php }?>
        <input type=hidden name=type value=<?php echo $row['Type']?>>
        <input type=hidden name=uid value=<?php echo $uid?>>
        <input type=hidden name=active value=Y>
    </form>

<?php
}else if($row['Checked']==1){ // 成功
?>  
    <?php
//    switch($row['Type']){
//    case 'S':
//        echo '<span class="red">已确定</span>';
//        // 统计当前已确定存款金额
//        $S_Success_Gold += $row['Gold'];
//    break;
//    case 'T':
//    echo '<span class="red">已提款</span>';
//        $T_Success_Gold += $row['Gold'];
//    break;
//    }


    ?>
<?php
}else if($row['Checked']==2){ // 0 首次提交订单 2 二次审核  1成功 -1失败
    if(($row['Type'])=='T'){
        echo '<span>提款审核中</span>';
       // $T_Second_Gold += $row['Gold'];
    }
} else{ //  Checked -1 失败
    if(($row['Type'])=='S'){ // 存款
        echo '<span class="blue">不确定</span>';
       // $S_Failed_Gold += $row['Gold'];
    }else{ // 提款
        echo '<span>已恢复</span>';
       // $T_Failed_Gold += $row['Gold'];
    }

}
?>
    <?php if ($row['Cancel']==1){ // 1 已恢复 ,0 没有操作 ?>
    <!--<span> 已恢复</span>-->
    <?php }else{?>
        <?php if ($row['Type']=='T'){?>
    <!--    <a href="javascript:resume('access.php?uid=--><?php //echo $uid?><!--&id=--><?php //echo $row['ID']?><!--&active=res&username=--><?php //echo $row['UserName']?><!--&gold=--><?php //echo $row['Gold']?><!--&langx=--><?php //echo $langx?><!--&action=--><?php //echo $action?><!--&page=--><?php //echo $page?><!--')">恢复</a>-->
        <?php }?>
    <?php }?>
    &nbsp;
<!--    <a href="javascript:Delete('access.php?uid=--><?php //echo $uid?><!--&id=--><?php //echo $row['ID']?><!--&active=del&langx=--><?php //echo $langx?><!--&action=--><?php //echo $action?><!--&page=--><?php //echo $page?><!--')">删除</a>-->
	</td>

    <td align="beizhu" data-type="<?php echo $row['Type'];?>">
        <?php
        if($row['discounType'] !=0){
            echo getDiscounTypeInfo($row['discounType']);
        }else{
            echo $row['Notes'].' / '.$row['reason'];
        }?>
    </td>

    <?php
$i=$i+1;
}

?>
  </tr>
   <!-- <?php /* if($action =='S'){  */?>
            <tr>
                <td colspan="2"><?php /*echo date("Y-m-d",strtotime($date_s)).'到'.date("Y-m-d",strtotime($date_e)); */?></td>
                <td>已确认存款:<?php /*echo number_format($S_Success_Gold ,2); */?></td>
                <td>未处理存款:<?php /*echo number_format($S_Process_Gold ,2); */?></td>
                <td>不确定,存款失败:<?php /*echo number_format($S_Failed_Gold ,2); */?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    <?php /*}elseif ($action =='T'){ */?>
        <tr>
            <td  colspan="2"><?php /*echo date("Y-m-d",strtotime($date_s)).'到'.date("Y-m-d",strtotime($date_e)); */?></td>
            <td>已提款成功:<?php /*echo number_format($T_Success_Gold ,2); */?></td>
            <td>未处理提款:<?php /*echo number_format($T_Process_Gold ,2); */?></td>
            <td>提款审核中:<?php /*echo number_format($T_Second_Gold ,2); */?></td>
            <td>提款失败，已恢复:<?php /*echo number_format($T_Failed_Gold ,2); */?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    --><?php /*}*/?>
    <tr class="m_rig2">
        <td colspan="2" ><?php  echo $date_s.'到'.$date_e;?></td>
        <td colspan="4" class="red">当前页总计 : <?php echo sprintf("%.2f", $pageCount)?> </td>
        <td colspan="5" class="red">全部页总计 : <?php echo sprintf("%.2f", $totalCount)?> </td>
    </tr>
</table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="javascript">
    var yesterday = '<?php echo $yeterday?>';
    var today = '<?php echo $today?>';
    var this_week_monday = '<?php echo $this_week_monday?>';
    var this_week_sunday = '<?php echo $this_week_sunday?>';
    var last_week_monday = '<?php echo $last_week_monday?>';
    var last_week_sunday = '<?php echo $last_week_sunday?>';
    var lv = '<?php echo $lv?>' ;
    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( str ) {
        var date_start;
        var date_end;
        switch (str){
            case 'yesterday':
                date_start = yesterday;
                date_end = yesterday;
                break;
            case 'today':
                date_start = today;
                date_end = today;
                break;
            case 'this_week':
                date_start = this_week_monday;
                date_end = this_week_sunday;
                break;
            case 'last_week':
                date_start = last_week_monday;
                date_end = last_week_sunday;
                break;
        }
        $("#date_start").val(date_start);
        $("#date_end").val(date_end);
        document.FrmData.submit();
    }

    function onLoad(){
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }

    function resume(str) {
        if(confirm("是否确定恢复金额?"))
            document.location=str;
    }
    function Delete(str) {
        if(confirm("是否确定删除纪录?"))
            document.location=str;
    }
    // 自动刷新
    var second="<?php echo $seconds?>";
    function auto_refresh(){
        if(second !=''){
            if (second==1){
                document.FrmData.submit();
            }else if(second=='手动刷新'){ // 手动刷新
                ShowTime.innerText = '' ;
            } else{
                second-=1
                curmin=Math.floor(second);
                curtime=curmin+"秒后刷新" ;
                ShowTime.innerText=curtime ;
                setTimeout("auto_refresh()",1000)
            }
        }
    }
    auto_refresh();
</script>
</body>
</html>
    <!-- 插入系统日志 -->
<?php
if ($active=='Y'){ // 有操作才需要插入
    innsertSystemLog($name,$lv,$loginfo);
}
?>
