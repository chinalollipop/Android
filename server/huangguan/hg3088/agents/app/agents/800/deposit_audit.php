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
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

$active=$_REQUEST['active'];
$memname=$_REQUEST['mid']; // 审核中会有mid 参数
if(!empty($_REQUEST['username'])){ // 存款 昨日、今日、本星期、上星期 接收用户名
    $memname=$_REQUEST['username'];
}
//echo $memname;
$id=$_REQUEST['id'];
$gold=$_REQUEST['gold'];
$winloss_gold = $_REQUEST['winloss_gold']; // 实际存款金额，不包括优惠（用于计算会员输赢额度20180815）
$preferential=$_REQUEST['preferential'];
$Checked=$_REQUEST['Checked'];
$deposit_type=strval($_REQUEST['deposit_type']); // 存款分类
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$seconds=$_REQUEST["seconds"]; // 刷新时间

// 存款 昨日、今日、本星期、上星期----------------------------------Start
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$this_week_monday= date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日   本周一
$this_week_sunday= date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算   本周日
$last_week_monday = date('l',time()) == 'Monday' ? date('Y-m-d', strtotime('last monday')) : date('Y-m-d',strtotime('-1 week last monday')); // 上周一
$last_week_sunday= date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期   上周日
// 存款 昨日、今日、本星期、上星期----------------------------------End


$page=$_REQUEST["page"];
$action=$_REQUEST['action']; // 类型 T 提款 S 存款
$order_number= isset($_REQUEST['order_number'])?$_REQUEST['order_number']:''; // 系统订单号
$typetile ='提款' ;
$typetip ='提出' ;
if(empty($action)){ //时间跳转， 点击查询会用到
    $action='S';
}
if($action=='S'){
    $typetile ='存款' ;
    $typetip ='存入' ;
}
if ($memname==''){
	$mem="";
}else{
	$mem="and UserName='$memname'";
}

if($Checked == -1){  // 存款失败
    $sCheckd = " and Checked != 1";
}elseif($Checked == 1){ // 存款成功
    $sCheckd = " and Checked = 1";
}

if(!empty($deposit_type)) { // 存款分类(公司入款汇款银行)
    $deposit_where = " and PayType = '$deposit_type'";
}

if ($date_start==''){
	$date_start=date('Y-m-d');
}
if ($date_end==''){
	//$date_end=date('Y-m-d',time()+86400);
	$date_end=date('Y-m-d');
}

if ($page==''){
	$page=0;
}
$date=date('Y-m-d H:i:s');

//mysqli_query($cpMasterDbLink,"ROLLBACK");
//$commitFrom = mysqli_query($dbMasterLink,"COMMIT");

$redisObj = new Ciredis();
switch ($_REQUEST['type']){
    case 'edit':
        $redisObj->setOne('newer_usdt_deposit_preferential_rate', $_REQUEST['newer_usdt_deposit_preferential_rate']);
        exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        break;
    default:
        break;
}

if ($active=='Y') {
	$mysql="select `type`,userid from ".DBPREFIX."web_sys800_data where Checked='0' && ID=".$id;
	$rs=mysqli_query($dbLink,$mysql);
    $iCou = mysqli_num_rows($rs);
	$rows=@mysqli_fetch_assoc($rs);
    if($iCou==0){
        echo '<script>alert("已审核过,请查看结果!");self.location="deposit_audit.php?uid='.$uid.'&langx='.$langx.'&lv='.$lv.'&action=S"</script>';
        exit;
    }
	if($rows['type']=='S') {
		if($_REQUEST['Checked']==1) { // 入款审核 成功

            // --------------------------------------------------------公司入款不优惠分层 Start-------------------------------------------
            $member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}'";
            $member_query = mysqli_query($dbLink,$member_sql);
            $memberinfo = mysqli_fetch_assoc($member_query);
            $sUserlayer = $memberinfo['layer'];
            // 检查当前会员是否设置不准操作额度分层
            // 检查分层是否开启 status 1 开启 0 关闭
            // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
            $layerId=2;
            if ($sUserlayer==$layerId){
                $layer = getUserLayerById($layerId);
                if ($layer['status']==1) {
                    $preferential = 0;
                    $gold = $winloss_gold;
                }
            }
            // --------------------------------------------------------公司入款不优惠分层 End-------------------------------------------

            // ---------------------------------------------------------count bet start-----------------------------------------------------------------------
            // 公司入款，默认更新打码量（入款更新-20191204）
            $betCount = round($dep_gold); // 打码量四舍五入
            $updateMemberOweBet = ",owe_bet=owe_bet+$gold"; // 累计会员提款打码量
            $update800OweBet = ",owe_bet=$gold"; // 更新此入款单打码量
            // 判断是否更新打码量统计时间（入款更新-20191204）
            $countBetTime = countBetTime($rows['userid']);
            $updateMemberOweBet .= ($countBetTime == '' ? ",owe_bet_time='$date'" : ",owe_bet_time='$countBetTime'"); // 更新会员打码量开始统计时间
            // ---------------------------------------------------------count bet end-----------------------------------------------------------------------
            $loginfo_status = '<font class="red">成功</font>' ;
			$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
			if($beginFrom){
				$mysql_check=mysqli_query($dbMasterLink,"select Checked,DepositAccount from ".DBPREFIX."web_sys800_data where ID=".$id." for update");
			    $mysqlCheckResult = mysqli_fetch_assoc($mysql_check);
			    if( isset($mysqlCheckResult['Checked']) && $mysqlCheckResult['Checked']==0 ){
			    	$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
					if($resultMem){
						$rowMem = mysqli_fetch_assoc($resultMem);
						$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold,Credit=Credit+$gold,WinLossCredit=WinLossCredit+$winloss_gold,DepositTimes=DepositTimes+1 $updateMemberOweBet where ID='".$rows['userid']."'"; // 公司入款成功后增加输赢额度&增加打码量
						if(mysqli_query($dbMasterLink,$mysql)){
                            $DepositAccount=$mysqlCheckResult['DepositAccount'];
                            if($_REQUEST['youhui_percent']>0 && $preferential == 1){
                                $DepositAccount .= '-'.$_REQUEST['youhui_percent'];
                            }
							$mysql="update ".DBPREFIX."web_sys800_data set DepositAccount='$DepositAccount',Gold='".$gold."',Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date',Preferential='$preferential' $update800OweBet where ID=".$id; // 公司入款成功后增加打码量
							if(mysqli_query($dbMasterLink,$mysql)){
								$res = level_deal($rows['userid'],$gold);//用户层级关系处理
								$moneyLogRes=addAccountRecords(array($rowMem['ID'],$memname,$rowMem['test_flag'],$rowMem['Money'],$gold,$rowMem['Money']+$gold,11,6,$id,"[存款(*)笔]存款审核,成功入账"));
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
            $loginfo_status = '<font class="red">失败</font>' ;
			$mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$loginname',AuditDate='$date' where ID=".$id;
			mysqli_query($dbMasterLink,$mysql);
		}
        $loginfo = $loginname.' 对会员帐号 <font class="green">'.$memname.'</font> 入款操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$id.',订单号为 <font class="blue">'.$order_number.'</font>' ;
	}
	echo '<script>self.location="deposit_audit.php?uid='.$uid.'&langx='.$langx.'&lv='.$lv.'&action=S"</script>' ;
}elseif($active=='Deposit_Notes_edit'){
    $userid = $_REQUEST['userid'];
    $Deposit_Notes = $_REQUEST['Deposit_Notes'];
    $mysql="update ".DBPREFIX.MEMBERTABLE." set Deposit_Notes='$Deposit_Notes' where ID='$userid'";
    if(mysqli_query($dbMasterLink,$mysql)){
        $loginfo = $loginname.' 对会员帐号 <font class="green">'.$memname.'</font> 入款备注操作为<font class="red">'.$Deposit_Notes.'</font>,id为 '.$id.',订单号为 <font class="blue">'.$order_number.'</font>' ;
    }
}

//$newer_usdt_deposit_preferential_rate = $redisObj->getSimpleOne('newer_usdt_deposit_preferential_rate');
$newer_usdt_deposit_preferential_rate = getSysConfig('newer_usdt_deposit_preferential_rate');
$deposit_bank_rate['deposit_bank_money'] = getSysConfig('deposit_bank_money');
$deposit_bank_rate['deposit_bank_less_ten_youhui_rate'] = getSysConfig('deposit_bank_less_ten_youhui_rate');
$deposit_bank_rate['deposit_bank_more_than_ten_youhui_rate'] = getSysConfig('deposit_bank_more_than_ten_youhui_rate');


// 存款()笔
// 当前管理员层级
//if($row['SubUser'] == '1' && $row['Competence']) { // 子账号
//    $returnlevels = getCompetenceLevel($row['Competence'] , '0'); //status=0代表存款
//    $levels = "'".implode("','",array_unique($returnlevels))."'";
//    $wherelevel .= "AND b.pay_class IN($levels)";
//}

if($_SESSION['SubUser'] == '1') { // 子账号允许查看的线下银行权限
    if(!empty($_SESSION['Bank_competence'])) {
        $bank_competences = $_SESSION['Bank_competence'];
        $where_bank .= "AND PayType IN($bank_competences)";
    }
    if($_SESSION['Bank_competence'] == ''){ //子账号线下银行为空
        $where_bank .= "AND PayType =''";
    }
}

// 存款() 只显示公司入款记录 , 支付宝，微信扫码入款不优惠
//$sql="select a.*,b.Alias,b.money,b.AddDate as member_AddDate,b.OfferStatus,b.Deposit_Notes from ".DBPREFIX."web_sys800_data as a,".DBPREFIX.MEMBERTABLE." as b where 1 $sCheckd and a.Type='$action' and a.Payway='N' $deposit_where and a.userid=b.ID $wherelevel $where_bank $mem $type and a.AddDate>='$date_start' and a.AddDate<='$date_end' ORDER BY ID DESC";
$sql="select * from ".DBPREFIX."web_sys800_data where 1 $sCheckd and Type='$action' and Payway='N' $deposit_where $where_bank $mem $type and AddDate>='$date_start' and AddDate<='$date_end' ORDER BY ID DESC";
//echo $sql;echo '<br>';
$result = mysqli_query($dbLink,$sql);
$gold_total=0;
$num=0;
$page_size=50;
$page=$_REQUEST['page'];
while ($row = mysqli_fetch_assoc($result)) {
    $aData[] = $row;
}

$aUserids = array_unique(array_column($aData,'userid'));
$sUserids = implode(',',$aUserids);
$sqlUser = "select ID, AddDate as member_AddDate, OfferStatus, Deposit_Notes from ".DBPREFIX.MEMBERTABLE." where ID in ({$sUserids}) ";
$resultUser = mysqli_query($dbLink,$sqlUser);
while ($rowUser = mysqli_fetch_assoc($resultUser)) {
    $aUser[$rowUser['ID']] = $rowUser;
}

foreach ($aData as $k => $v){
    $aData[$k]['member_AddDate'] = $aUser[$v['userid']]['member_AddDate']; // 注册日期
    $aData[$k]['OfferStatus'] = $aUser[$v['userid']]['OfferStatus']; // 是否优惠
    $aData[$k]['Deposit_Notes'] = $aUser[$v['userid']]['Deposit_Notes']; // 存款备注
}

$data=[];
foreach ($aData as $k => $row){
    if($row['Checked']==1) {
        $gold_total += ($row['currency_after'] - $row['moneyf']); //全部页总计
    }
    $youhui_total_all_page += $row['Gold'] - ($row['currency_after'] - $row['moneyf']); // 全部页优惠额度总计
    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {

        // 优惠总额 // 0 无优惠，1有优惠，已优惠金额返回初始金额
        if($row['Preferential']==1) {
            $row['Gold'] = $row['currency_after']-$row['moneyf']; //存款实际金额
            $Gold_no_youhui = $row['currency_after'] - $row['moneyf']; //存款金额（无优惠）
            $aDepositAccount = explode('-',$row['DepositAccount']);
            $youhui_row = preferentialGold($Gold_no_youhui, $row['member_AddDate'],$row['Bank'],$aDepositAccount[3]);
            $youhui_total += $youhui_row;
        }
        if($row['Checked']==1) {
            $gold+= $row['Gold'];  // 当前页总计，显示时才需要四舍五入
        }
        $data[]=$row;
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
    <style>
        .data_details{width: 220px;text-align: left;}
        input.text_time{ width: 85px;}
        .show_bank_details {
            display: none;
            background: #ff9900;
            text-align: left;
            padding: 10px 0 10px 10px;
            position: absolute;
            margin-top: -73px;
            width: 170px;
        }
        .show_bank_details:after {
            content: '';
            width: 0;
            height: 0;
            border-top: 7px solid #ff9900;
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
            margin-top: 26px;
            left: 23px;
            position: absolute;
        }
        .close_details {
            float: right;
            display: inline-block;
            width: 13px;
            height: 13px;
            line-height: 10px;
            text-align: center;
            background: #fff;
            margin-right: 5px;
            font-family: 微软雅黑;

        }

    </style>
</head>
<!--<base target="net_ctl_main">
<base target="_top">-->
<body >
<dl class="main-nav">
    <dt><?php echo $typetile;?>审核记录</dt>
    <dd>
  <div id="Layer1" class="layer_div" onMouseOver="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')">
        <ul>
          <li class="mou first"><a href="user_list_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>">帐户查询</a></li>
          <li class="mou" ><a href="user_edit_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>">存入帐户</a></li>
        </ul>
    </div>

    <table >
      <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
      <tr class="m_tline">
          <td>
              <select class="za_select za_select_auto" onChange="document.FrmData.submit();" id="seconds" name="seconds">
                  <option value="手动刷新">手动刷新</option>
                  <option value="10" <?php echo $seconds=='10'?'selected':''?> >10秒</option>
                  <option value="20" <?php echo $seconds=='20'?'selected':''?> >20秒</option>
                  <option value="30" <?php echo $seconds=='30'?'selected':''?> >30秒</option>
                  <option value="60" <?php echo $seconds=='60'?'selected':''?> >60秒</option>
              </select>&nbsp;
              <span id="ShowTime"></span>
          </td>
                <td>
                    &nbsp;时间:
                    <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_start?>" onclick="laydate({istime: false, istoday: false,format: 'YYYY-MM-DD'})" >
                    ~
                    <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                </td>
                <td>
                    &nbsp;&nbsp;<input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('yeterday')" />&nbsp;
                    <input type="button" class="match_date_today" value="今日" onclick="match_date('today')" />&nbsp;
                    <input type="button" class="match_date_this_week" value="本星期" onclick="match_date('this_week')" />&nbsp;
                    <input type="button" class="match_date_last_week" value="上星期" onclick="match_date('last_week')" />&nbsp;&nbsp;
                    <input type="text" id="username" name="username" placeholder="查询用户名" value="<?php echo $_REQUEST['username'];?>" style="width: 90px">
                </td>


                <td width="70">&nbsp;--&nbsp;是否确定:</td>
                <td>
                  <select name="Checked" class="za_select za_select_auto">
                  <option value="">全部</option>
                  <option value="1"<?php if($_POST['Checked']=="1"){?> selected<?php }?>>确定</option>
                  <option value="-1"<?php if($_POST['Checked']=="-1"){?> selected<?php }?>>未确定</option>
                  </select>
                </td>
                <td width="70">&nbsp;&nbsp;存款分类:</td>
                <td>
                    <select name="deposit_type" class="za_select za_select_auto">
                        <option value="">全部</option>
                        <?php
                        // 公司银行账号
                        $companyBankSql = "SELECT id,bankcode,bank_name,bank_user,class,status from ".DBPREFIX."gxfcy_bank_data where status=1 order by sort asc";
                        $companyBankResult = mysqli_query($dbLink,$companyBankSql);
//                        $companyAccount=array();
                        while ($rowBank = mysqli_fetch_array($companyBankResult)){
                            $bank_user = !empty($rowBank['bank_user']) ? '-' . $rowBank['bank_user'] : '';
                            if($rowBank['id'] == $deposit_type) {
                                echo "<option value='{$rowBank['id']}' selected>".$rowBank['bank_name'].$bank_user."</option>";
                            }else {
                                echo "<option value='{$rowBank['id']}'>".$rowBank['bank_name'].$bank_user."</option>";
                            }
//                            $companyAccount[$rowBank['id']]=$rowBank;
                        }

                        ?>
                    </select>
                </td>
                <td > &nbsp;
                  <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
                </td>
                <td width="58">&nbsp;--&nbsp;总页数:</td>
                <td>
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
                </td>
                <td> / <?php echo $page_count?> 页</td>
      </tr>

    </FORM>
    </table>
    </dd>
</dl>
<div class="main-ui width_1300">
    <table class="m_tab">
        <tr>
            <td colspan="13">
                <form></form>
                新用户USDT充值的优惠比率（例如：1%则填写1）：<?php echo $newer_usdt_deposit_preferential_rate;?>
                <!--<input type="text" id="newer_usdt_deposit_preferential_rate" name="newer_usdt_deposit_preferential_rate" value="<?php /*echo $newer_usdt_deposit_preferential_rate;*/?>">
                <input type="button" class="za_button btn_edit_1" onclick="btn_edit_newer_usdt_preferential_rate()" value="修改">-->
            </td>
        </tr>
        <tr class="m_rig2">
            <td colspan="2" ><?php  echo $date_start.'-'.$date_end;?></td>
            <td colspan="2" class="red">总优惠金额 : <?php
                //                echo sprintf("%01.2f", $preferentialAfterTotal-$preferentialBeforeTotal)
                echo sprintf("%01.2f", $youhui_total);
                ?></td>
            <td colspan="3" class="red">当前页总计 : <?php echo sprintf("%01.2f", $gold)?> </td>
            <td colspan="4" class="red">全部页优惠额度总计 : <?php echo sprintf("%01.2f", $youhui_total_all_page);?> </td>
            <td colspan="2" class="red">全部页总计 : <?php echo sprintf("%01.2f", $gold_total)?> </td>
        </tr>
            <tr class="m_title">
              <td width="3%">编号</td>
              <td width="11.5%">系统订单号</td>
              <td width="6%">会员帐号</td>
              <!--<td>姓名/电话</td>-->
              <td width="10%">金额变化</td>
              <td width="12%" align="left">开户银行</td>
              <td width="4%">优惠</td>
              <td width="6%">金额(RMB)</td>
              <td width="10%">日期时间</td>
              <td width="5%">状态</td>
              <td colspan="2" width="10%" >审核</td>
              <td width="10%">管理员审核理由</td>
              <td width="10%">用户存款备注</td>
            </tr>
            <!-- BEGIN DYNAMIC BLOCK: row -->
    <?php
    if ($cou==0){
    ?>
    <tr class="m_cen">
              <td colspan="14">目前沒有记录</td>

            </tr>
    <?php
    }else{

        foreach ($data as $k =>$v){
            $item_gold = sprintf("%01.2f", $v['Gold']); // 存入金额
            $item_gold_original = $item_gold;

    ?>

            <tr class="m_cen">
                <td><?php echo $k+1;?></td>
                <td><?php echo $v['Order_Code']?></td>
                <td><font class="red"><?php echo $v['UserName']?></font><br><?php echo $v['Name']?></td>
              <!--<td><b><?php /*echo $v['Name']*/?></b><br><?php /*echo $v['Phone']*/?></td>-->
              <td style="text-align: left;"><!--金额变化-->
                  充值前：<span style="color: yellowgreen "><?php echo sprintf("%01.2f", $v['moneyf']);?></span><br>
                  充值后：<span style="color: red"><?php echo sprintf("%01.2f", $v['currency_after']);?></span>
              </td>
              <td class="data_details">
              <?php if($v['PayType']==""){?> <!-- 支付宝扫码等 -->
             <!-- 转入银行：<?php /*echo $v['Bank']*/?><br>
              汇款方式：<?php /*echo $v['Payway']=='N'?'公司入款':'';*/?><br>
              汇款地点：<?php /*echo $v['Bank_Address']*/?><br>-->

              <a ><?php echo $v['DepositAccount']?></a>

              <?php } else{ ?> <!-- 公司入款 -->
                  <a href="javascript:;" class="a_link" onclick="showDetails(this,'show')"><?php echo $v['DepositAccount']?></a>
                  <div class="show_bank_details">
                      <?php echo $v['InType']?> <a href="javascript:;" class="close_details" onclick="showDetails(this,'')">x</a><br>
                      持卡人姓名：<?php echo (empty($v['CardName']) ? $v['Name'] : $v['CardName']);?>
                  </div>
             <?php }?>
              </td>
                <td class="promos_rate"><!--优惠显示-->
                    <?php
                    // 优惠列
                    //     1） 单笔不超过10万元的，赠送1%, 最多50元，（例如：1%则填写0.01）字段: deposit_bank_less_ten_rate
                    //    2）超过等于10万的存款赠送2%，无上限, （例如：2%则填写0.02）字段: deposit_bank_more_than_ten_rate
                    //       单笔金额字段: deposit_bank_money   值：10W
                    //    3）【USDT虚拟币优惠】 字段: newer_usdt_deposit_preferential_rate  新用户USDT充值的优惠比率（例如：1%则填写1）
                    //          2020年9月20日当天以及之前注册的会员，虚拟币存款优惠2%
                    //          2020年9月21日开始之后注册的会员，虚拟币存款优惠1%
                    if($v['Preferential']>0){ // 有优惠
                        if ($v['Bank']=='USDT虚拟货币'){
                            //if ($v['member_AddDate']>'2020-09-20 23:59:59') {
                                $aDepositAccount = explode('-',$v['DepositAccount']);
                                if($aDepositAccount[3] == 1){
                                    echo '1%';
                                }
                                elseif($aDepositAccount[3] == 2){
                                    echo '2%';
                                }
                                else{
                                    echo '无优惠';
                                }
                           // }
                           // else {
                           //     echo '2%';
                           // }
                        }else{
                            //echo $item_gold>=100000 ? '2%' : '1%';
                            echo $item_gold>=$deposit_bank_rate['deposit_bank_money'] ? ($deposit_bank_rate['deposit_bank_more_than_ten_youhui_rate']*100)."%" : ($deposit_bank_rate['deposit_bank_less_ten_youhui_rate']*100)."%";
                        }
                    }else{ // 没有优惠
                        echo '无优惠';
                    }

                    ?>
                </td>

                <!--金额  公司入款参加优惠 公司微信扫码支付宝扫码不参加优惠-->
                <?php if($v['Checked']!=0){ // 审核状态 0 首次提交订单 2 二次审核  1成功 -1失败 ?>
                    <!--<td align="right"><font color="red">--><?php //echo $item_gold; ?><!--</font></td>-->
                    <!--根据需求 无论当前是否选择优惠 存多少显示多少-->
                    <td align="center" data-gold="<?php echo $v['Gold']?>" class="<?php if($v['Gold']>=50000){ echo 'red_strong';}?>">
                        <?php echo sprintf("%01.2f",$v['Gold']); ?>
                    </td>
                <?php }else{
                        //echo '注册时间：'.$v['member_AddDate'].'---配置时间'.COMPANY_DEPOSIT_TIME;
                        // 存款 根据需求 公司入款默认不需要显示优惠(存多少显示多少)， 实际是优惠后金额。公司微信扫码支付宝扫码不参加优惠
                        if(/*$v['PayName'] !== 'ALISAOMA' && */$v['PayName'] !== 'WXSAOMA' && $v['OfferStatus'] == 1) {
                            //  根据会员注册时间享受公司存款优惠  小于2016-08-01 , 配置文件中COMPANY_DEPOSIT_TIME
                            $youhui_row = preferentialGold($item_gold,$v['member_AddDate'],$v['Bank'],$newer_usdt_deposit_preferential_rate);
                            $item_gold += $youhui_row;
                        }
                        ?>
                        <td align="center" id="deposit_coupon_<?php echo $v['ID'];?>" data-gold="<?php echo $item_gold_original;?>" data-id="<?php echo $v['ID'];?>" onclick="<?php echo ($v['OfferStatus']==1)? 'DepositCoupon(this)':'';?>" >

                            <span id="gold_show_<?php echo $v['ID'];?>">
                                <?php
                                $Gold_no_youhui = $v['currency_after'] - $v['moneyf']; //存款金额（无优惠）
                                echo $v['Preferential']==1?$youhui_row:$item_gold_original;
                                ?>
                            </span>
                            <br><br>
                            <span id="youhui_show_<?php echo $v['ID'];?>" style="display: none;">
                                <input type="radio" name="is_youhui_<?php echo $v['ID'];?>" value="1" checked="checked">优惠
                                <input type="radio" name="is_youhui_<?php echo $v['ID'];?>" value="0">不优惠
                            </span>
<!--                            <input type="hidden" name="time_flag_--><?php //echo $v['ID']?><!--" value="--><?php //if($v['member_AddDate']<COMPANY_DEPOSIT_TIME){echo '1';}else{ echo '0';} ?><!--">-->
                            <input type="hidden" name="youhui_gold_<?php echo $v['ID']?>" value="<?php echo $youhui_row;?>">
                        </td>
                <?php }?>

                <td><?php echo $v['Date']?></td>
              <td>
              <?php
      if($v['Checked']==1)
      {
         echo "<font style='color:green'>成功</font>";
      }
      else if($v['Checked']==0)
      {
         echo "<font style='color:blue'>审核中</font>";
      }
      else if($v['Checked']==-1)
      {
         echo "<font style='color:red'>失败</font>";
      }
      ?>
              </td>

              <td colspan="2">
              <?php
              if($v['Checked']==0){
              ?>
              <form  method=post target='_self' style="margin:0px; padding:0px;">
              <input name="Checked" type="radio" value="1" checked> 成功 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="Checked" type="radio" value="-1"> 失败
              <input name="reason" type="text" size=10 class="za_text"><br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=send value='提交' onClick="return confirm('确定审核此笔订单？')" class="za_button">
              <input type=hidden name=id value=<?php echo $v['ID']?>>
              <input type=hidden name=mid value=<?php echo $v['UserName']?>>
              <input type=hidden id="form_gold_<?php echo $v['ID'];?>" name=gold value=<?php echo $item_gold;?>>
              <input type="hidden" name="winloss_gold" value="<?php echo $item_gold_original?>" />
              <input type=hidden id="youhui_<?php echo $v['ID'];?>" name=preferential value=<?php if(/*$v['PayName']=='ALISAOMA' || */$v['PayName']=='WXSAOMA' || $v['OfferStatus']==0){echo '0';}else{echo '1';}?>>
              <?php
              //if ($v['Bank']=='USDT虚拟货币' and $v['member_AddDate']>'2020-09-20 23:59:59') {
              if ($v['Bank']=='USDT虚拟货币') {
                  echo '<input type=hidden name="youhui_percent" value="'.$newer_usdt_deposit_preferential_rate.'">';
              }
              ?>
              <input type=hidden name=type value=<?php echo $v['Type']?>>
              <input type=hidden name=uid value=<?php echo $uid?>>
              <input type=hidden name=date_start value=<?php echo $date_start?>>
              <input type=hidden name=date_end value=<?php echo $date_end?>>
              <input type=hidden name=type value=<?php echo $_REQUEST['type']?>>
              <input type=hidden name=order_number value=<?php echo $v['Order_Code']?>>
              <input type=hidden name=lv value=<?php echo $_REQUEST['lv']?>>
              <input type=hidden name=active id="active" value=Y></td>
              </form>
              <?php
              } else {
                echo $v['User']."<br>".$v['AuditDate'];
              }
              ?>

              </td>
                <td><?php echo $v['reason'];?></td>
                <td>
                    <textarea name="Deposit_Notes" ID="Deposit_Notes_<?php echo $v['ID']?>" rows="3" cols="20" ><?php echo $v['Deposit_Notes']; ?></textarea>
                    <input type="button" class="za_button" value="修改" onclick="btn_edit('<?php echo $v['ID']?>','<?php echo $v['UserName']?>','<?php echo $v['userid']?>','<?php echo $v['Order_Code']?>')">
                </td>
            </tr>

    <?php
    }
    }
    ?>
         <!-- END DYNAMIC BLOCK: row -->

    </table>

</div>

<!--<script language="JavaScript" src="../../../js/agents/simplecalendar.js?v=<?php echo AUTOVER; ?>"></script>-->
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script language="JavaScript">
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
            case 'yeterday':
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
        var uid = '<?php echo $uid;?>';
        var url = 'deposit_audit.php';
        var username = myFORM.username.value;

        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='uid' value='"+uid+"'>");
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='date_end' value='"+date_end+"'>");
        form.append("<input type='hidden' name='username' value='"+username+"'>");
        form.append("<input type='hidden' name='lv' value='"+lv+"'>");
        $(document.body).append(form);
        form.submit();

    }
    // 存款备注输入框，点击修改提交并记录下系统日志
    var uid = '<?php echo $uid?>';
    function btn_edit(id,mid,userid,order_number) {
        var active = 'Deposit_Notes_edit';
        var Deposit_Notes = $("#Deposit_Notes_"+id).val();

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"deposit_audit.php",
            data:{
                id: id,
                userid: userid,
                uid: uid,
                active: active,
                mid: mid,
                lv: lv,
                order_number: order_number,
                Deposit_Notes: Deposit_Notes,
            },
            success:function(data) {
                if (data){
                    alert('更新成功！');
                    location.reload();
                }else{
                    alert('更新失败！！');
                }
            }
        })
    }

/* 1）单笔不超过10万元的，赠送1%, 最多50元，
   2）超过等于10万的存款赠送2%，无上限 */
    function DepositCoupon(e) {

        var td_id='#'+e.id; // 获取当前html TD标签的 ID
        var gold = $(td_id).attr('data-gold'); // 公司存款金额
        var data_id = $(td_id).attr('data-id'); // 订单唯一id值
        $('#youhui_show_'+data_id).show();// 显示优惠、不优惠
        var form_hidden_gold = '#form_gold_'+data_id; // 审核时表单的gold容器
        var is_youhui = $('input:radio[name=is_youhui_'+data_id+']:checked').val(); // 优惠 1 优惠 0 不优惠
        // var time_flag_id = $('input[name=time_flag_'+data_id+']').val();
        var youhui_gold = $('input[name=youhui_gold_'+data_id+']').val();   // 优惠额

        if (is_youhui == 1){ // 优惠 无上限
            gold=parseFloat(Number(gold)+Number(youhui_gold)).toFixed(2);
            $('#gold_show_'+data_id).text(gold);
            $(form_hidden_gold).val(gold); // 表单中的额度
            $('#youhui_'+data_id).val(is_youhui); // 优惠
        }else{ // 不优惠
            gold=parseFloat(Number(gold)).toFixed(2);
            $('#gold_show_'+data_id).text(gold);
            $(form_hidden_gold).val(gold); // 表单中的额度
            $('#youhui_'+data_id).val(is_youhui);
        }
    }


    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
        if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
        else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    function MM_jumpMenu(targ,selObj,restore){ //v3.0
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
    }
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

    // 显示/ 隐藏银行资料
    function showDetails(obj,type) {
        if(type=='show'){ // 显示
            $(obj).next().show() ;
            $(obj).parents('.m_cen').siblings('.m_cen').find('.show_bank_details').hide() ;
        }else{ // 隐藏
            $(obj).parent('.show_bank_details').hide();
        }
    }
    // 自动刷新
    var second="<?php echo $seconds?>";

    function auto_refresh(){
        if(second !=''){
            if (second==1){
                window.location.href='deposit_audit.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&seconds=<?php echo $seconds?>&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>&page=<?php echo $page?>'; //刷新页面
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

    function btn_edit_newer_usdt_preferential_rate() {
        var type = 'edit';
        var langx = "<?php echo $langx;?>";
        var lv = "<?php echo $lv?>";
        var newer_usdt_deposit_preferential_rate = $("#newer_usdt_deposit_preferential_rate").val();
        $.ajax({
            type:"POST",
            url:"deposit_audit.php",
            data:{
                type : type,
                newer_usdt_deposit_preferential_rate : newer_usdt_deposit_preferential_rate,
            },
            success:function(response) {

                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='deposit_audit.php?uid='+uid+'&langx='+langx+'&lv='+lv+'action=S'
                }
            }
        })
    }
    auto_refresh();
</script>
</body>
</html>
    <!-- 插入系统日志 -->
<?php
if ($active=='Y' || $active == 'Deposit_Notes_edit'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>