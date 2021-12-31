<?php
session_start();
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$name=$_SESSION['UserName'];
$lv = $_REQUEST['lv'] ;


$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
if ($date_start==''){
    $date_start=date('Y-m-d');
}
if ($date_end==''){
    $date_end=date('Y-m-d');
}
// 昨日、今日、本星期、上星期----------------------------------Start 20180517
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$this_week_monday= date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日   本周一
$this_week_sunday= date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算   本周日
function week($format=''){
    $w = date('w');
    $week = $w==0?'last week ':'this week ';
    return strtotime($week.$format);
}
$last_week_monday= date('Y-m-d', strtotime('-1 monday', week())); //无论今天几号,先获取本周一，然后-1 monday为上一个  上周一
$last_week_sunday= date('Y-m-d', strtotime('-1 sunday', week('Sunday'))); //先获取本周日，然后上一个有效周日   上周日
// 昨日、今日、本星期、上星期----------------------------------End 20180517

$username = $_REQUEST['username'];
if (isset($username) && $username!=''){
    $username="AND a.UserName='".$username."'";
}
$withdraw_type = $_REQUEST['withdraw_type'];
//if(!empty($withdraw_type)) { // 提款分类(USDT)
if($withdraw_type == 'USDT') { // 提款分类(USDT)
    $withdraw_where = " and a.PayName = 'USDT'";
}elseif($withdraw_type == 'AUTO') { //自动出款
    $withdraw_where = " and a.is_auto = '1' and a.is_auto_flag = '1'";
}elseif($withdraw_type == 'NAUTO') { //非自动出款
    $withdraw_where = " and a.Locked = '1' and a.Locked_controller != ''";
}

$do_check = $_REQUEST['Checked'] ;
$active=$_REQUEST['active']; // 审核操作
$id=$_REQUEST['id'];
$memname=$_REQUEST['mid']; // 用户名
$gold=$_REQUEST['gold'];
$isauto=$_REQUEST['isauto'];
$date=date('Y-m-d H:i:s');

$type = $_REQUEST['type'];
// 首先处理否加锁解锁业务
if($type=='locked'){
    $do_lock = $_REQUEST['locked']; // 0 解锁，1 锁定
    $locked_controller = $_REQUEST['locked_controller'];
    if($do_lock==0){ // 解锁
        $locked_controller = '' ;
    }
    $mysql="update ".DBPREFIX."web_sys800_data set Locked='".$do_lock."',Locked_controller='$locked_controller' where ID=".$id;
    mysqli_query($dbMasterLink,$mysql);
    switch ($do_lock){ // 写入日志状态
        case '0': // 0 解锁
            $loginfo_status = '<font class="red">解锁</font>' ;
            break ;
        case '1': // 0 锁定
            $loginfo_status = '<font class="red">锁定</font>' ;
            break ;
    }
    echo '<script>window.location="cash_out.php?uid='.$uid.'&lv='.$lv.'&date_start='.$date_start.'&date_end='.$date_end.'"</script>' ;
    $loginfo = $locked_controller.' 对会员帐号 <font class="green">'.$memname.'</font> 出款状态置为'.$loginfo_status.',id为 '.$id.',金额为 <font class="red">'.number_format($gold,2).'</font>' ;
}else{
    if ($active=='Y') { // 审核操作 出款  Checked :0 未审核，1 成功，2 已审核需要出款审核 -1 失败 ,-2 刚提交的订单
        $mysql="select `type`,userid from ".DBPREFIX."web_sys800_data where (Checked=1 or Checked=2) && ID=".$id;
        $rs=mysqli_query($dbLink,$mysql);
        $rows=@mysqli_fetch_assoc($rs);
        if($rows['type']=='T') { // 提款
	            $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
	            if($beginFrom){
		            if($do_check==-1) { // 不通过 退回金额
                        $loginfo_status = '<font class="red">退回</font>' ;
		        		$mysql_check=mysqli_query($dbMasterLink,"select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update");
		        		$mysqlCheckResult = mysqli_fetch_assoc($mysql_check);
		        		if($mysqlCheckResult['Checked']&&$mysqlCheckResult['Checked']==2){ // 出款二次审核
		        			$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$rows['userid']}' for update");

			            	if($resultMem){
								$rowMem = mysqli_fetch_assoc($resultMem);
				                $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold,Credit=Credit+$gold where ID='".$rows['userid']."'";
				                if(mysqli_query($dbMasterLink,$mysql)){
				                    $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$do_check."',reviewer='$name',reviewDate='$date' where ID=".$id;
				                    if(mysqli_query($dbMasterLink,$mysql)){
				                    	$moneyLogRes=addAccountRecords(array($rowMem['ID'],$memname,$rowMem['test_flag'],$rowMem['Money'],$gold,$rowMem['Money']+$gold,12,6,$id,"[出款(*)笔]出款失败,入账"));
				                        if($moneyLogRes){
				                        	mysqli_query($dbMasterLink,"COMMIT");	
				                        }else{ mysqli_query($dbMasterLink,"ROLLBACK");}
				                    }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
				                }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }	
							}else{
			            	    mysqli_query($dbMasterLink,"ROLLBACK");
			            	}
					    }else{
		        		    mysqli_query($dbMasterLink,"ROLLBACK");
		        		}
		            }
		            else{ // 通过 确认出款  $do_check = 1
                        $loginfo_status = '<font class="red">确认出款</font>' ;
                        $mysql_check=mysqli_query($dbMasterLink,"select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update");
                        $mysqlCheckResult = mysqli_fetch_assoc($mysql_check);
                        if($mysqlCheckResult['Checked']&&$mysqlCheckResult['Checked']==2){ // 出款二次审核
                            $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$rows['userid']}' for update");
                            if($resultMem) {
                                $mysql="update ".DBPREFIX.MEMBERTABLE." set WithdrawalTimes=WithdrawalTimes+1,WinLossCredit=WinLossCredit-$gold where ID='".$rows['userid']."'"; // 审核提款成功后扣除输赢额度
                                if(mysqli_query($dbMasterLink,$mysql)){
                                    if ($isauto==1){
                                        $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$do_check."',is_auto='1',is_auto_flag='1',reviewer='$name',reviewDate='{$date}' where ID=".$id;
                                    }else{
                                        $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$do_check."',is_auto_flag='0',reviewer='$name',reviewDate='$date' where ID=".$id;
                                    }
                                    if(mysqli_query($dbMasterLink,$mysql)){
                                        $res = level_deal($rows['userid'],$gold,1);//用户层级关系处理
                                        if($res){
                                            mysqli_query($dbMasterLink,"COMMIT");
                                        }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                                    }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                                }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                            }else{
                                 mysqli_query($dbMasterLink,"ROLLBACK");
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                        }
					}
		    }else{ 
	        	mysqli_query($dbMasterLink,"ROLLBACK"); 
	        }
            $loginfo = $name.' 对会员帐号 <font class="green">'.$memname.'</font> 出款状态置为 '.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>' ;
        }
    }
}

// 出款()笔
// 获取当管理员权限，如果是管理员全部显示。如果是子账号显示，根据子账号的分层出入款权限显示会员
if($_SESSION['SubUser'] == '1' && $_SESSION['Competence']) { // 子账号
    $returnlevels = getCompetenceLevel($_SESSION['Competence'] , '1'); //array{'0'=> "a",'1'=> "a",'2'=> "b" ,'3'=> "c"}  status=1代表出款
    $levels = "'".implode("','",array_unique($returnlevels))."'";
    $wherelevel .= "AND b.pay_class IN($levels)";
}

// 提款审核不需要显示在线提款 Payway!=W 包含(人工提款，discounType 6手工提出 8AG掉单存入提出)
$sql="select a.*,b.Money,b.pay_class,b.Notes as Withdrawal_Notes,b.Usdt_Address from ".DBPREFIX."web_sys800_data as a, ".DBPREFIX.MEMBERTABLE." as b where a.userid=b.ID $wherelevel and a.Payway!='W' and a.AddDate>='$date_start' and a.AddDate<='$date_end' and a.Type='T' $username $withdraw_where and (Checked=1 or Checked=2) ORDER BY ID Desc";
//echo $sql;echo '<br>';

$result = mysqli_query($dbLink,$sql);
$gold_total=0;
$num=0;
$page_size=50;
$page=$_REQUEST['page'];
$data=[];

while ($row = mysqli_fetch_assoc($result)) {
	if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
		if ($row['Type'] == 'T') {
			if ($row['Checked'] != 0 and $row['Gold'] > 0) {
				$gold -= $row['Gold'];
			}
		}
		$data[]=$row;
	}
    $gold_total-=$row['Gold'];
	$num+=1;
}
$cou=$num;
$page_count=ceil($cou/$page_size);

$sql=" select method from ".DBPREFIX."gxfcy_autopay where status = 1 ";
$result = mysqli_query($dbLink,$sql);
$autopay = mysqli_fetch_assoc($result);

/*
 * $lock :0未锁定，1 已锁定, $check : 0 首次提交订单 2 二次审核  1成功 -1失败
 * 出款显示处理
 * */
function returnCashOutClass($lock,$check){
    if($lock=='1' || $check=='1'){ // 已锁定
        $returnClass = 'show_content' ;
    }else{
        $returnClass = 'hide_content' ;
    }
   return $returnClass ;
}

?>

<html lang="en">
<head>
<title>800系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .show_content{visibility: visible;}
        .hide_content{visibility: hidden;}
    </style>
</head>
<body>
<dl class="main-nav">
    <dt>出款审核记录</dt>
    <dd>
        <table>
            <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
                <tr class="m_tline">
                    <td width="68">&nbsp;&nbsp;日期区间:</td>
                    <td>
                        <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_start?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >&nbsp;~&nbsp;
                        <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >&nbsp;
                        <input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('yeterday')" />&nbsp;
                        <input type="button" class="match_date_today" value="今日" onclick="match_date('today')" />&nbsp;
                        <input type="button" class="match_date_this_week" value="本星期" onclick="match_date('this_week')" />&nbsp;
                        <input type="button" class="match_date_last_week" value="上星期" onclick="match_date('last_week')" />&nbsp;&nbsp;
                        <input type="text" id="username" name="username" placeholder="查询用户名" value="<?php echo $_REQUEST['username'];?>"  style="width: 90px" /><!--class="select_btn"-->
                    </td>
                    <td width="70">&nbsp;&nbsp;出款分类:</td>
                    <td>
                        <select name="withdraw_type" class="za_select za_select_auto">
                            <option value="">全部</option>
                            <option value="USDT" <?php if($withdraw_type=='USDT'){echo 'selected';} ?> >USDT虚拟货币</option>
                            <option value="AUTO" <?php if($withdraw_type=='AUTO'){echo 'selected';} ?> >自动出款</option>
                            <option value="NAUTO" <?php if($withdraw_type=='NAUTO'){echo 'selected';} ?> >非自动出款</option>
                        </select>
                    </td>
                    <td>
                        <input type=SUBMIT name="SUBMIT" value="查询"  class="za_button">
                    </td>
                    <td>
                        &nbsp;&nbsp;总页数:
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
        <tr class="m_rig2">
            <td colspan="6" class="red">当前页总计：<?php echo sprintf("%01.2f", $gold)?></td>
            <td colspan="6" class="red">全部页总计：<?php echo sprintf("%01.2f", $gold_total)?></td>
        </tr>
        <tr class="m_title">
            <td>编号</td>
            <td >会员帐号</td>
            <td>金额变化</td>
            <td >金额(RMB)</td>
            <td >日期时间</td>
            <td>开户银行</td>
            <td >银行账号</td>
            <td >用户名</td>
            <td >操作</td>
            <td >自动出款</td>
            <td >备注</td>
            <td colspan="2">操作者/审核</td>
        </tr>

        <?php
        if ($cou==0){
            ?>
            <tr class="m_cen">
                <td colspan="12">目前沒有记录</td>
            </tr>
            <?php
        }else{

            foreach ($data as $k => $v){?>
                <tr class="m_cen" data-lock="<?php echo $v['Locked'] ;?>" data-checked="<?php echo $v['Checked']?>" data-lockname="<?php echo $v['Locked_controller'];?>">
                    <td ><?php $k=$k+1; echo $k;?></td>
                    <td ><b><?php echo $v['UserName']?></b></td>
                    <td align="left" >
                        <p class="add_lock_class ">
                            提款前：<span style="color: yellowgreen "><?php echo sprintf("%01.2f", $v['moneyf']);?></span><br>
                            提款后：<span style="color: red"><?php echo sprintf("%01.2f", $v['currency_after']);?></span>
                        </p>
                    </td>
                    <td align="right" class=" gold_num <?php if($v['Gold']>=50000){ echo 'red_strong';}?>">
                        <p class="add_lock_class ">
                            -<?php echo sprintf("%01.2f", $v['Gold'])?>
                        </p>
                    </td>
                    <td > <p class="add_lock_class "> <?php echo $v['Date']; ?> </p> </td>
                    <td >
                        <p class="add_lock_class ">
                            <?php
                            if('USDT提款' == explode('-' , $v['InType'])[0]) {
                                echo '类型：'. $v['InType'] . '<br>' . 'USDT地址：'.$v['Usdt_Address'];
                            }else{
                                echo $v['Bank_Address'].'<br>'.$v['Bank'].'<br>';
                            }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php
                        if( $v['Locked'] == 1 && $v['Checked'] != 1 && $v['Locked_controller'] == $name) echo $v['Bank_Account'];
                        ?>
                    </td>
                    <td><b><?php echo trim($v['Name']); ?></b></td>
                    <td class="do_checked">
                        <?php
                        if($v['Type']=="T" && $v['Checked']==1){
                            echo '<font color="red"><b>已提款</b></font>';
                        }else if($v['Type']=='T' && $v['Checked']==2){ // 再次审核
                            echo '<form method=post name="do_check_form" data-id='.$v["ID"].' data-controller="'.$name.'" class="do_check_form" target="_self">
                                          <input type=hidden name=id value='.$v["ID"].'>
                                          <input type=hidden name=mid value='.$v["UserName"].'>
                                          <input type=hidden name=gold value='.$v["Gold"].'>
                                          <input type=hidden name=type value='.$v["Type"].'>
                                          <input type=hidden name=uid value='.$uid.'>
                                          <input type=hidden name=active id="active" value=Y>
                                          <input type=hidden name=date_start id="date_start" value='.$date_start.'>
                                          <input type=hidden name=date_end id="date_end" value='.$date_end.'>
                                          <input type=hidden name=lv value='.$lv.'>
                                          <input type=hidden class="Checked" name=Checked value=>';
//                           echo $v['Locked'];
                            if( $v['Locked'] == 1 ){ //手动已锁定

                                if( $v['Locked_controller'] == $name ){ // 锁定人可以解锁、确认出款、退回
                                echo '<a href="javascript:;" class="a_link" onclick="doLocked(this)" value="0" username="'.$v["UserName"].'" usergold="'.$v["Gold"].'" date_start="'.$date_start.'" date_end="'.$date_end.'">解锁</a> </br></br>
                                       <a href="javascript:;" class="a_link" onclick="doChecked(this)" value="1" username="'.$v["UserName"].'" usergold="'.$v["Gold"].'">确认出款</a> </br></br>
                                       <a href="javascript:;" class="a_link" onclick="doChecked(this)" value="-1" username="'.$v["UserName"].'" usergold="'.$v["Gold"].'">退回</a> ' ;
                                }else{ // 其他账号只能看到 已锁定
                                    echo '<a href="javascript:;" class="a_link">已锁定</a><br>'.$v['Locked_controller'];
                                }
                            }else{ //手动锁定
                                echo '<a href="javascript:;" class="a_link" onclick="doLocked(this)" value="1" username="'.$v["UserName"].'" usergold="'.$v["Gold"].'" date_start="'.$date_start.'" date_end="'.$date_end.'" is_auto="'.$v["is_auto"].'" is_auto_flag="'.$v["is_auto_flag"].'" >锁 定</a>';
                            }
                            echo '</form>';
                        }?>
                    </td>
                    <td>
                        <?php

                        if ($v['Type']=='T' && $v['Checked']==2 && $v['Locked']==0){

                            if (!empty($autopay["method"])){
                                //$max_auto_withdraw_money = AUTO_WITHDRAW_MAX_MONEY; // 小于等于多少金额允许自动出款
                                $redisObj = new Ciredis();
                                $max_auto_withdraw_money = getSysConfig('max_auto_withdraw_money') ? getSysConfig('max_auto_withdraw_money') : AUTO_WITHDRAW_MAX_MONEY;
                                if (abs($v['Gold']) > $max_auto_withdraw_money){
                                    echo "<font color='red'><strong>超出限额</strong></font>";
                                } else if(strpos($v['Bank'] , '信用')) {
                                    echo "<font color='red'><strong>农社不能自动出款</strong></font>";
                                } else if($v['is_auto'] == 0 ) {
                                    echo "<a href=\"#\" class=\"a_link\" onclick='javascript:autock(".$v['ID'].")'>自动出款</a> ";
                                } else if($v['is_auto'] == 1 && $v['is_auto_flag'] == 2) {
//                                    echo "<font color='#C4C400'><strong>自动出款中</strong></font><br /><br /><a href=\"#\" onclick='javascript:autock(".$v['ID'].")'>再次发送</a>";
                                    echo "<font color='#C4C400'><strong>自动出款中</strong></font>";
                                } else if($v['is_auto'] == 1 && $v['is_auto_flag'] == 0) {
                                    echo "<font color='red'><strong>自动出款失败</strong></font>";
                                }
                            }else {
                                echo "<font color='red'><strong>未启用</strong></font>";
                            }
                        }else{ // 自动出款状态显示
                            if($v['is_auto']==1 && $v['is_auto_flag']==1) {
                                echo "<font color='green'><strong>自动出成功</strong></font>";
                            }else if($v['Checked']==2 && $v['Locked']==1 ) {
                                echo "<font class='has_lock' color='red'><strong>已锁定</strong></font>";
                            }else if($v['Checked']==1 && $v['is_auto']==0 ) {
                                echo "<font color='red'><strong>非自动出款</strong></font>";
                            }else if( $v['is_auto']==1 && $v['is_auto_flag'] == 0  ) {
                                echo "<font color='red'><strong>自动出款失败</strong></font>";
                            }else if( $v['is_auto']==1 && $v['is_auto_flag'] == 2  ) {
//                                echo "<font color='#C4C400'><strong>自动出款中</strong></font><br /><br /><a href=\"#\" onclick='javascript:autock(".$v['ID'].")'>再次发送</a>";
                                echo "<font color='#C4C400'><strong>自动出款中</strong></font>";
                            }else {
                                echo "<font color='red'><strong>已撤销</strong></font>";
                            }
                        }
                        ?>
                    </td>
                    <td>

                        <textarea name="Notes" ID="Notes_<?php echo $v['ID']?>" rows="3" cols="20" ><?php echo $v['Withdrawal_Notes']; ?></textarea><br>
                        <input type="button" class="za_button" value="修改" onclick="btn_edit('<?php echo $v['ID']?>','<?php echo $v['UserName']?>','<?php echo $v['userid']?>','<?php echo $v['Order_Code']?>')">
                    </td>
                    <td class="docheck_ower">
                        <?php
                        echo $v['User'].' / '.$v['reviewer'].'<br>';
                        echo $v['AuditDate'].' <br> '.$v['reviewDate'];
                        ?>
                    </td>
                </tr>
                <?php
            }
        }?>
        <!-- END DYNAMIC BLOCK: row -->

    </table>
</div>


<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var yesterday = '<?php echo $yeterday?>';
    var today = '<?php echo $today?>';
    var this_week_monday = '<?php echo $this_week_monday?>';
    var this_week_sunday = '<?php echo $this_week_sunday?>';
    var last_week_monday = '<?php echo $last_week_monday?>';
    var last_week_sunday = '<?php echo $last_week_sunday?>';
    var lv = '<?php echo $lv?>' ;
    var thisname = '<?php echo $name;?>' ; // 当前登录帐号

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
        var url = 'cash_out.php';
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

    var uid = '<?php echo $uid?>';
    // 提款备注输入框，点击修改提交并记录下系统日志
    function btn_edit(id,mid,userid,order_number) {
        var active = 'Withdrawal_Notes_edit';
        var Withdrawal_Notes = $("#Notes_"+id).val();
        $.ajax({
            type:"POST",
            url:"withdraw_list_800.php",
            data:{
                id: id,
                userid: userid,
                uid: uid,
                active: active,
                mid: mid,
                lv: lv,
                order_number: order_number,
                Notes: Withdrawal_Notes,
            },
            success:function(data) {
                if (data){
                    alert('更新成功！');
                    location.reload();
                }else{
                    falgetip =false ;
                    alert('更新失败！！');
                }
            }
        })
    }

    // 出款人加锁、解锁
    function doLocked(obj) {
        var url = 'cash_out.php';
        var val = $(obj).attr('value');
        var username = $(obj).attr('username');
        var usergold = $(obj).attr('usergold');
        var date_start = $(obj).attr('date_start');
        var date_end = $(obj).attr('date_end');
        var uid = '<?php echo $uid;?>';
        var id = $(obj).parent('.do_check_form').attr('data-id');
        var locked_controller = $(obj).parent('.do_check_form').attr('data-controller');
// console.log(locked_controller);

        var is_auto = $(obj).attr('is_auto');
        var is_auto_flag = $(obj).attr('is_auto_flag');

        /*if(val == 1 ) { // 锁定时，判断是否自动出款
            if(is_auto ==1 &&  is_auto_flag == 2) { //自动出款中
                alert('自动出款中,稍后再试！');
                return false;
            }
        }*/

        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='locked' value='"+val+"'>");
        form.append("<input type='hidden' name='type' value='locked'>");
        form.append("<input type='hidden' name='uid' value='"+uid+"'>");
        form.append("<input type='hidden' name='id' value='"+id+"'>");
        form.append("<input type='hidden' name='mid' value='"+username+"'>");
        form.append("<input type='hidden' name='gold' value='"+usergold+"'>");
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='date_end' value='"+date_end+"'>");
        form.append("<input type='hidden' name='lv' value='"+lv+"'>");
        form.append("<input type='hidden' name='locked_controller' value='"+locked_controller+"'>");
        $(document.body).append(form);
        form.submit();
    }

    // 审核操作
    function doChecked(obj) {

        var form = $(obj).parent('.do_check_form');
        var val = $(obj).attr('value');
        form.find('.Checked').val(val) ;

        if(val == -1){// 退款
            form.submit();
        }else{ // 已出款

            if (confirm("确认出款？")){
                var isauto = 0;
               /* if(confirm("是否属于自动出款？Is it automatic?")) {
                    isauto=1;
                }*/

                var isauto_intput = $('<input type="hidden" name="isauto" value="'+isauto+'">');
                form.append(isauto_intput);
                form.submit();
            }
        }
    }
    // 打开新窗口，30秒轮训获取汇通出款状态
    function check_ck_status(){
        var reviewer = '<?php echo $_SESSION['UserName']?>'; // 出款人
        var url_update_status = '<?php echo $ht_auto_pay_back_url;?>';
        var openurl = url_update_status+"/check_ck_status.php?reviewer="+reviewer ;
       // window.open(openurl,"check_ck_status","width=1020,height=950,status=no");
        layer.open({
            title:'自动出款',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: openurl
        });
    }

    //自动出款
    function autock(id){

        var _mms="确定自动出款?";
        var method = '<?php echo $autopay["method"];?>';
        var url = 'onlinepay_api.php';
        var datapars = {
            method:method,
            id:id,
            lv:lv
        };
        if (method == 'htpay_cash_autock'){ // 打开新窗口，30秒轮询更新订单状态 (请求汇通出款成功，允许打开窗口刷新出款状态)
            check_ck_status()
        }
        if(confirm(_mms)) {
            $.ajax({
                type: 'POST',
                url: url,
                data: datapars,
                dataType: 'json',
                success: function (ret) {
                    if (ret.err == 0) { // 发送成功

                        alert("自动出款提交成功");
                        autoReload() ;
                    } else {
                        alert(ret.msg);
                        // check_ck_status();
                       // autoReload() ;
                    }
                },
                error: function (ii, jj, kk) {
                    alert('网络错误，请稍后重试');
                    autoReload() ;
                }
            })
        }
    }

    // 刷新页面
    function autoReload() {
        var date_start = '<?php echo $date_start;?>';
        var date_end = '<?php echo $date_end;?>';
        var username = "<?php echo $username;?>";
        window.location.href = "/app/agents/800/cash_out.php?date_start="+date_start+"&date_end="+date_end+"&username="+username;
    }
    
    // 锁定与未锁定处理 ,lock :0未锁定，1 已锁定, checked : 0 首次提交订单 2 二次审核  1成功 -1失败
    function lockAddClassAction() {

        var haslock = $('body .has_lock').length ; //
        var lockNameArr = [] ;
        // console.log(haslock);

        if(haslock>0){ // 有已锁定订单

            var hasnum = 0 ;
            $('.m_tab .m_cen').each(function () {
                var this_lockname = $(this).data('lockname') ;
                if(this_lockname){
                    lockNameArr.push(this_lockname) ;
                }
            });

            $.each(lockNameArr,function (i,v) {
                // console.log(v) ;
                if(thisname==v){ // 只有锁定者才需要
                    hasnum ++ ;
                }
            }) ;
            if(hasnum>0){
                $('.m_tab .m_cen').each(function () {
                    var this_lock = $(this).data('lock') ;
                    var this_checked = $(this).data('checked') ;
                    var this_lockname = $(this).data('lockname') ;
                    if(this_lock==1 && this_checked==2){ // 有已锁定订单
                        $(this).find('.add_lock_class').addClass('show_content') ;
                    }else{
                        $(this).find('.add_lock_class').addClass('hide_content') ;
                    }

                });
            }

           // console.log(hasnum) ;

        }


    }

    lockAddClassAction() ;
    setBodyScroll() ;
</script>
    </body>
    </html>
    <!-- 插入系统日志 -->
<?php
if ($active=='Y' || $type=='locked'){ // 有操作才需要插入
    innsertSystemLog($name,$lv,$loginfo);
}
?>