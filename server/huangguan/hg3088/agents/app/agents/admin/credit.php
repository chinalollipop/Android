<?php
session_start();
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_REQUEST["langx"];
require ("../include/traditional.$langx.inc.php");
$action=$_REQUEST['action'];
$page=$_REQUEST['page'];

if ($page==""){
	$page=0;
}
$active=$_REQUEST['active'];
$id=$_REQUEST['id'];
$username=$_REQUEST['username'];
if($active=='Y'){
	$notes=$_REQUEST['notes'];
	$Phone=$_REQUEST['Phone'];
	$Alias=$_REQUEST['Alias'];	
	//print_r($_REQUEST);
    $mysql="update ".DBPREFIX.MEMBERTABLE." set notes='$notes',Phone='$Phone',Alias='$Alias' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
	echo "<Script language=javascript>self.location='userinfo.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}else if ($active=='del'){
	echo "<Script language=javascript>self.location='userinfo.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}

// 关键字查找
$search=$_REQUEST['search'];
if ($search!=''){
    $num=25;
    $search="and (UserName like '%$search%' or Bank_Account like '%$search%' or Phone like '%$search%' or Notes like '%$search%')";
}else{
    $num=25;
}

// 查询会员表用户名 在  (账单表审核状态成功的存款 分组用户)
$sql = "select ID,UserName,AddDate,Money from ".DBPREFIX.MEMBERTABLE." where test_flag=0 and username in (select username from ".DBPREFIX."web_sys800_data where checked=1 and `Cancel`=0 and `Type`='S' group by username)  $search order by ID desc";
$result = mysqli_query($dbLink, $sql);
$cou=mysqli_num_rows($result);
$page_size=$num;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size";
 //echo $mysql;
$result = mysqli_query($dbLink, $mysql);


/**
 * @param $player
 * web_sys800_data type账变表,  Gold出款金额  , S 存款 T 提款 Q 额度转换记录 C 汇款信息回查 R返水
 */
function thirdTransfer($dbLink , $player) {
    $where = " `checked`=1 and `Type` = 'Q'";
    // 一. 1> 用户HG转账到AG, 提款要相加
    $h2a_sql = "select sum(Gold) as Golds  from " . DBPREFIX . "web_sys800_data where  Username='" . $player . "' and ".$where." and `From` = 'hg' and `To` = 'ag'";
    $h2a_result = mysqli_query($dbLink, $h2a_sql);
    $cou_h2a = mysqli_num_rows($h2a_result);
    if ($cou_h2a > 0) {
        $h2a_row = mysqli_fetch_assoc($h2a_result);
        $aThird['hg2ag'] = !empty($h2a_row['Golds']) ? $h2a_row['Golds'] : 0;
    }

    // 2> 用户AG转账到HG, 存款要相加
    $a2h_sql = "select sum(Gold) as Golds from " . DBPREFIX . "web_sys800_data where  Username='".$player."' and ".$where." and `From` = 'ag' and `To` = 'hg'";
    $a2h_result = mysqli_query($dbLink, $a2h_sql);
    $cou_a2h = mysqli_num_rows($a2h_result);
    if ($cou_a2h > 0) {
        $a2h_row = mysqli_fetch_assoc($a2h_result);
        $aThird['ag2hg'] = !empty($a2h_row['Golds']) ? $a2h_row['Golds'] : 0;
    }

    // 二. 1. 用户HG转账到cp彩票 ,  提款要相加
    $h2c_sql = "select sum(Gold) as Golds from " . DBPREFIX . "web_sys800_data where  Username='".$player."' and ".$where." and `From` = 'hg' and `To` = 'cp'";
    $h2c_result = mysqli_query($dbLink, $h2c_sql);
    $cou_h2c = mysqli_num_rows($h2c_result);
    if ($cou_h2c > 0) {
        $h2c_row = mysqli_fetch_assoc($h2c_result);
        $aThird['hg2cp'] = !empty($h2c_row['Golds']) ? $h2c_row['Golds'] : 0;
    }
    // 2. 用户cp彩票转账到AG, 存款要相加
    $c2h_sql = "select sum(Gold) as Golds from " . DBPREFIX . "web_sys800_data where  Username='" . $player . "' and ".$where." and `From` = 'cp' and `To` = 'hg'";
    $c2h_result = mysqli_query($dbLink, $c2h_sql);
    $cou_c2h = mysqli_num_rows($c2h_result);
    if ($cou_c2h > 0) {
        $c2h_row = mysqli_fetch_assoc($c2h_result);
        $aThird['cp2hg'] = !empty($c2h_row['Golds']) ? $c2h_row['Golds'] : 0;
    }

    return $aThird;
}


// 当前时间是否大于美东3点
if((int)date("G") >= 0 && (int)date("G") < 3){
    //搜索当前时间小于【美东时间】凌晨3点,则昨天的历史报表还未生成，需分为两段,
    //设置好起始时间(前一天开始时间)以及 结束时间（到现在时间）
    $neededSearchCurrentBillTable = true;
    $current_start_day = date("Y-m-d", strtotime("-1 day")); //2018-05-17 00:00:01
    //从历史报表里面搜索的截止时间为前天晚上的23:59:59
    $date_end = date("Y-m-d", strtotime("-2 day"));
}else if((int)date("G") >= 3 && (int)date("G") <= 23) {

    //搜索当前时间大于【美东时间】凌晨3点,则昨天的历史报表已生成，
    //需要设置好起始时间(今天开始时间)以及 结束时间（到现在时间）  今天的报表从现有的订单记录里面计算
    $neededSearchCurrentBillTable = true;
    $current_start_day = date("Y-m-d");  //2018-05-18 00:00:01
    //从历史报表里面搜索的截止时间为昨天晚上的23:59:59
    $date_end = date("Y-m-d", strtotime("-1 day")); //2018-05-17 23:59:59

}


/**
 * 获取总帐输/赢
 * web_report_data                  M_Result(派彩结算金额) ,   BetScore有效投注,  M_Date,  BetTime 投注时间
 * web_report_history_report_data   count_pay 总注数, total下注总额, valid_money有效金额, user_win输赢结果, bet_time下注时间
 */
function getZzsyData($dbLink, $player , $current_start_day ,  $date_end) {

    $mo_Where =" and `M_Date`>= '$current_start_day'" ;   //读取注单表大于等于当天的数据  比如 北京时间0519 10:20 相当于美东时间0518 22:20
    $le_Where =" and `M_Date`<= '$date_end'" ;  // 大于三点,读取历史注单表昨天以前数据。 小于三点,读取历史注单表前天小于23:59:59之前的数据

    //当天注单表总帐输/赢
    //select sum(M_Result) as M_Results from hgty78_web_report_data where M_Name='john101' and Cancel=0 and `BetTime`> '2018-05-18 00:00:01'
    $sql_today_z = "select  sum(M_Result) as M_Results from ".DBPREFIX."web_report_data where M_Name='".$player."' and checked = 1 and Cancel=0 and M_result <> '' $mo_Where ";
//    echo '<pre>';
//    echo $sql_today_z;
//    echo '</pre>';
    //echo '当天注单表总帐输/赢'.$sql_today_z;echo '<br>';
    $sql_res_z = mysqli_query($dbLink, $sql_today_z);
    $cou_res_z = mysqli_num_rows($sql_res_z);
    if($cou_res_z > 0) {
        $res_current_zgsy = mysqli_fetch_assoc($sql_res_z);
        $returnData['today_zzsy'] = !empty($res_current_zgsy['M_Results']) ? $res_current_zgsy['M_Results']:0;
    }

    // 历史注单表总帐输/赢
    $sql_history_z = "select  sum(user_win) as user_wins from ".DBPREFIX."web_report_history_report_data where username='".$player."' $le_Where ";
//    echo '<pre>';
//    echo $sql_history_z;
//    echo '</pre>';
    //echo '历史注单表总帐输/赢'.$sql_history_z;echo '<br>';
    $sql_his_z = mysqli_query($dbLink, $sql_history_z);
    $cou_his_z = mysqli_num_rows($sql_his_z);
    if($cou_his_z > 0) {
        $res_his_zgsy = mysqli_fetch_assoc($sql_his_z);
        $returnData['history_zzsy'] = !empty($res_his_zgsy['user_wins']) ? $res_his_zgsy['user_wins']:0;
    }

    // 合到体育注单总帐输/赢
    $returnData['total_zzsy'] = sprintf("%.2f",$returnData['today_zzsy']) + sprintf("%.2f",$returnData['history_zzsy']);
    return $returnData['total_zzsy'];
}

/**
 * 获取未结算金额
 * web_report_data                  M_Result(派彩结算金额) ,   BetScore有效投注,  BetTime 投注时间
 */
function getWjsjeData($dbLink, $player , $current_start_day , $date_end) {
    //当天注单表未结算金额
    $sql_today_w = "select sum(BetScore) as BetScores from ".DBPREFIX."web_report_data where M_Name='".$player."' and Cancel=0 and Checked=0 and M_Result='' ";
    $sql_res_w = mysqli_query($dbLink, $sql_today_w);
    $cou_res_w = mysqli_num_rows($sql_res_w);
    if($cou_res_w > 0) {
        $res_current_wjsje = mysqli_fetch_assoc($sql_res_w);
        $returnData['today_wjszs'] = !empty($res_current_wjsje['BetScores']) ? $res_current_wjsje['BetScores']:0;
    }

    // 合到体育注单未结算金额
    $returnData['total_wjszs'] = $returnData['today_wjszs'];
    return $returnData['total_wjszs'];
}

/**
 * 获取有效投注额总数
 * web_report_data                  M_Result(派彩结算金额) ,   BetScore有效投注,  BetTime 投注时间
 * web_report_history_report_data   count_pay 总注数, total下注总额, valid_money有效金额, user_win输赢结果, M_Date下注日期, bet_time下注时间
 */
function getYxtzData($dbLink, $player , $current_start_day ,  $date_end) {

    $mo_Where =" and `M_Date`>= '$current_start_day'" ;   //读取注单表大于等于当天的数据  比如 北京时间0519 10:20 相当于美东时间0518 22:20
    $le_Where =" and `M_Date`<= '$date_end'" ;  // 大于三点,读取历史注单表昨天以前数据。 小于三点,读取历史注单表前天小于23:59:59之前的数据

    //当天有效投注额总数
    $sql_today_y = "select sum(BetScore) as BetScores from ".DBPREFIX."web_report_data where M_Name='".$player."' and Cancel=0 $mo_Where" ;
    $sql_res_y = mysqli_query($dbLink, $sql_today_y);
    $cou_res_y = mysqli_num_rows($sql_res_y);
    if($cou_res_y > 0) {
        $res_current_yxtz = mysqli_fetch_assoc($sql_res_y);
        $returnData['today_yxtz'] = !empty($res_current_yxtz['BetScores']) ? $res_current_yxtz['BetScores']:0;
    }

    // 历史注单表有效投注额
    $sql_history_y = "select sum(valid_money) as valid_moneys from ".DBPREFIX."web_report_history_report_data where username='".$player."'$le_Where" ;
    $sql_his_y = mysqli_query($dbLink, $sql_history_y);
    $cou_his_y = mysqli_num_rows($sql_his_y);
    if($cou_his_y > 0) {
        $res_his_yxtz = mysqli_fetch_assoc($sql_his_y);
        $returnData['history_yxtz'] = !empty($res_his_yxtz['valid_moneys']) ? $res_his_yxtz['valid_moneys']:0;
    }

    // 合到体育注单有效投注
    $returnData['total_yxtz'] = sprintf("%.2f",$returnData['today_yxtz']) + sprintf("%.2f",$returnData['history_yxtz']);
    return $returnData['total_yxtz'];
}



?>
<html>
<head>
<title>会员信息</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body onLoad="onLoad()" >
<dl class="main-nav"><dt>现金额度检查</dt>
    <dd>  </dd>
</dl>
<div class="main-ui">
<table class="m_tab">
	<tr class="m_title">
      <form id="myFORM" ACTION="" METHOD=POST name="FrmData">
	  <td colspan="9">关键字查找:
	  <input type=TEXT name="search" size=10 value="" maxlength=20 class="za_text">
      <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
	  </td>     
	  <td colspan="2">
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
   </form>
	</tr>
	<tr class="m_title">
	  <td width="30">编号</td>
	  <td width="100">帐号</td>
	  <td width="90">开户日期</td>
	  <td width="90">存款总数</td>
	  <td width="90">取款总数</td>
	  <td width="90">总帐输/赢</td>
	  <td width="90">未结算金额</td>
	  <td width="90">有效投注额总数</td>
	  <td width="90">目前额度</td>
      <td>误差</td>
      <td>功能</td>
	</tr>
<?php
$i=1; // mysqli_fetch_assoc
// die();

while ($row = mysqli_fetch_assoc($result)){

$id=$row['ID'];
$sql_s = "select sum(gold) from ".DBPREFIX."web_sys800_data where Type IN ('S','R') and Checked=1 and Username='".$row['UserName']."'" ; // 存款总数（包含返水）
$sql_t = "select sum(gold) from ".DBPREFIX."web_sys800_data where Type='T' and Username='".$row['UserName']."' and checked IN (0,1,2)" ; // 提款总数（审核通过并出款 1 、未审核 0 、已审核未出款 2）
//$sql_z = "select sum(M_Result) from ".DBPREFIX."web_report_data where M_Name='".$row['UserName']."' and Cancel=0" ; // 总帐输/赢
$sql_w = "select sum(BetScore) from ".DBPREFIX."web_report_data where M_Name='".$row['UserName']."' and Cancel=0 and Checked=0 and M_Result=''" ; // 未结算金额
//$sql_y = "select sum(BetScore) from ".DBPREFIX."web_report_data where M_Name='".$row['UserName']."' and Cancel=0" ; // 有效投注额总数

$ckzs = sprintf("%.2f", mysqli_fetch_array(mysqli_query($dbLink,$sql_s))[0]) ;	// 帐变表存款总数
$qkzs = sprintf("%.2f", mysqli_fetch_array(mysqli_query($dbLink,$sql_t))[0]) ;	// 帐变表提款总数
//$zzzs = intval(mysqli_fetch_array(mysqli_query($dbLink,$sql_z))[0]) ;   // 总帐输/赢
$wjszs = sprintf("%.2f", mysqli_fetch_array(mysqli_query($dbLink,$sql_w))[0]) ;	// 未结算金额
//$yxzs = intval(mysqli_fetch_array(mysqli_query($dbLink,$sql_y))[0]) ;	// 有效投注额总数

/**
 * 返回数据  体育hg到真人ag  $aThird['hg2ag']，真人ag到体育hg  $aThird['ag2hg'], 体育hg到彩票cp  $aThird['hg2cp'], 彩票cp到体育hg  $aThird['cp2hg'] 金额
 */
$aThird = thirdTransfer($dbLink , $row['UserName']);
$aThird_ck = sprintf("%.2f",$aThird['ag2hg']) + sprintf("%.2f",$aThird['cp2hg']); //ag，cp转入体育总和
$aThird_qk = sprintf("%.2f",$aThird['hg2ag']) + sprintf("%.2f",$aThird['hg2cp']); //体育转出到ag，cp总和

//echo '<pre>';
//echo $ckzs.'-'.$aThird_ck;
//echo '</pre>';

$ckzs += $aThird_ck; // 处理后存款总数
$qkzs += $aThird_qk; // 处理后提款总数


$zzzs = getZzsyData($dbLink, $row['UserName'] , $current_start_day ,  $date_end);   // 总帐输/赢
//$wjszs = getWjsjeData($dbLink, $row['UserName'] , $current_start_day ,  $date_end);  // 未结算金额
$yxzs = getYxtzData($dbLink, $row['UserName'] , $current_start_day ,  $date_end);    // 有效投注额总数

?>
  <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)> 
   
    <td align="center"><?php echo $i?></td>
    <!-- 账户-->
    <td align="center"><font color=red><?php echo $row['UserName']?></font></td>
    <!-- 开户日期-->
    <td align="center"><?php echo $row['AddDate']?></td>
    <!-- 存款总数-->
	<td align="center"><?php echo number_format($ckzs , 2) ?></td>
	<!-- 取款总数-->
    <td align="center"><?php echo number_format($qkzs , 2)?></td>
    <!-- 总帐输/赢 -->
    <td align="center"><?php echo number_format($zzzs , 2)?></td>
    <!-- 未结算金额 -->
    <td align="center"><?php echo number_format($wjszs, 2)?></td>
    <!-- 有效投注额总数 -->
    <td align="center"><?php echo number_format($yxzs, 2);?></td>
    <!-- 目前额度 -->
    <td align="center">
    <?php echo number_format($row['Money'], 2); ?></td>
    <!-- 误差 -->
	<td align="center">
	<?php	echo number_format($ckzs-$qkzs+$zzzs-$wjszs-$row['Money'] , 2); ?>
	</td>
    <td align="center">
    <!-- <input  type="submit" class="za_button" value="修正">  -->
<!--      	<input type="button" data-account="<?php echo $row['UserName']?>" data-currentid="<?php echo $id;?>" data-amount="<?php echo round($row['Money'],2);?>" data-difference="<?php echo round($ckzs-$qkzs+$zzzs-$wjszs-$row['Money'],2);?>" class="submitBtn" value="修正"> -->
    </td>
 
    <?php
$i=$i+1;
}
?>

  </tr>
</table>

</div>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="javascript">
    function onLoad(){
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }
    $(function () {
    	$('.submitBtn').click(function () {
        var currentid = $(this).data('currentid');
        var amount = $(this).data('amount');
        var difference = $(this).data('difference');
        var account = $(this).data('account'); // 会员帐号
        var postData = {currentid:currentid,amount:amount,difference:difference,account:account};
        
	        if (postData) {
	            // 修正误差
	            $.post('./correction.php', postData, function(json) {	
		            //console.log(json);	       
	                if (json.status = 1) {
	                	alert(json.info);
	                	location.reload(true);
	                } else {
	                    alert(json.info);
	                }
	            }, 'json')
	        }
    	});
    });
    
</script>

</body>
</html>