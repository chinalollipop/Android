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
$name=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

$active=$_REQUEST['active']; // 审核操作
$memname=$_REQUEST['mid'];

$id=$_REQUEST['id'];
$gold=$_REQUEST['gold']; // 提款金额
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$page=$_REQUEST["page"];

if ($page==''){
	$page=0;
}
$date=date('Y-m-d H:i:s');


if ($active=='Y') { // 审核操作 出款  Checked :0 未审核，1 成功，-1 失败 ,-2 刚提交的订单
    $mysql="select `type`,userid from ".DBPREFIX."web_sys800_data where Checked='0' && ID=".$id;
	$rs=mysqli_query($dbLink,$mysql);
	$rows=@mysqli_fetch_assoc($rs);
	if($rows['type']=='T') { // 提款
		$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
		if($beginFrom){
			if($_REQUEST['Checked']==-1) { // 不通过
                $loginfo_status = '<font class="red">失败</font>' ;
				$mysql_check="select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update ";
				$result_check = mysqli_query($dbMasterLink,$mysql_check);
				$row_check = mysqli_fetch_assoc($result_check);
				if(isset($row_check['Checked']) && $row_check['Checked'] == 0) {
					$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
					if($resultMem){
						$rowMem = mysqli_fetch_assoc($resultMem);
						$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold,Credit=Credit+$gold where ID='".$rows['userid']."'";
						if(mysqli_query($dbMasterLink,$mysql)){
							$mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$name',AuditDate='$date' where ID=".$id;
							$moneyLogRes=addAccountRecords(array($rowMem['ID'],$memname,$rowMem['test_flag'],$rowMem['Money'],$gold,$rowMem['Money']+$gold,12,6,$id,"[提款审核(*)笔]提款审核,失败入账"));
							if(mysqli_query($dbMasterLink,$mysql)&&$moneyLogRes){
								mysqli_query($dbMasterLink,"COMMIT");				
							}else{ mysqli_query($dbMasterLink,"ROLLBACK"); }	
						}else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
					}else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
				}else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
			}else{ // 通过
                // ---------------------------------------------------------count bet start-----------------------------------------------------------------------
                // 更新打码量（出款更新-20191204）
                $updateMemberOweBet = "owe_bet=0"; // 会员提款打码量清0
                // 判断是否更新打码量统计时间（出款款更新-20191204）
                $countBetTime = countBetTime($rows['userid']);
                $updateMemberOweBet .= ($date > $countBetTime ? ",owe_bet_time='$date'" : ""); // 以最近一次审核通过的时间为统计打码量的开始时间
                // ---------------------------------------------------------count bet end-------------------------------------------------------------------------
                $loginfo_status = '<font class="red">成功</font>' ;
                $mysql_check="select Checked from ".DBPREFIX."web_sys800_data where ID=".$id." for update ";
                $result_check = mysqli_query($dbMasterLink,$mysql_check);
                $row_check = mysqli_fetch_assoc($result_check);
                if(isset($row_check['Checked']) && $row_check['Checked'] == 0) {
                    $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$rows['userid']}' for update");
                    if($resultMem){
                        $sql = "update ".DBPREFIX.MEMBERTABLE." set $updateMemberOweBet where ID='".$rows['userid']."'"; // 审核提款成功后会员打码量清0&更新统计时间
                        if(mysqli_query($dbMasterLink, $sql)){
                            $mysql="update ".DBPREFIX."web_sys800_data set Checked='".$_REQUEST['Checked']."',reason='".$_REQUEST['reason']."',User='$name',AuditDate='$date' where ID=".$id;
                            if(mysqli_query($dbMasterLink,$mysql)){
                                $res = level_deal($rows['userid'],$gold,1);//用户层级关系处理
                                if($res){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{ mysqli_query($dbMasterLink,"ROLLBACK"); }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                        }
                    } else {
                        mysqli_query($dbMasterLink,"ROLLBACK");
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                }
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");	
		}
        $loginfo = $name.' 对会员帐号 <font class="green">'.$memname.'</font> 首次提款审核状态为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>' ;
	}
}elseif($active=='Withdrawal_Notes_edit'){
    $order_number= isset($_REQUEST['order_number'])?$_REQUEST['order_number']:''; // 系统订单号
    $userid = $_REQUEST['userid'];
    $Withdrawwal_Notes = $_REQUEST['Notes'];
    $mysql="update ".DBPREFIX.MEMBERTABLE." set Notes='$Withdrawwal_Notes' where ID='$userid'";
    if(mysqli_query($dbMasterLink,$mysql)){
        $loginfo = $name.' 对会员帐号 <font class="green">'.$memname.'</font> 提款备注操作为<font class="red">'.$Withdrawwal_Notes.'</font>,id为 '.$id.',订单号为 <font class="blue">'.$order_number.'</font>' ;
    }
}

if($_SESSION['SubUser'] == '1' && $_SESSION['Competence']) { // 子账号
    $returnlevels = getCompetenceLevel($_SESSION['Competence'] , '1'); //status=1代表提款审核
    $levels = "'".implode("','",array_unique($returnlevels))."'";
    $wherelevel .= "AND b.pay_class IN($levels)";

    // 存款()笔层级权限
//    $returncklevels = getCompetenceLevel($sysdata['Competence'] , '0'); //status=0代表存款审核
//    $cklevels = "'".implode("','",array_unique($returncklevels))."'";
//    $wherecklevel .= "AND b.pay_class IN($cklevels)";
    // 存款()笔线下银行权限
    if(!empty($_SESSION['Bank_competence'])) {
        $bank_competences = $_SESSION['Bank_competence'];
        $where_ck_bank .= "AND a.PayType IN($bank_competences)";
    }
    if($_SESSION['Bank_competence'] == ''){ //子账号线下银行为空
        $where_ck_bank .= "AND a.PayType =''";
    }

}
if ($memname==''){
    $mem="";
}else{
    $mem="and a.UserName='$memname'";
}
// 提款审核不需要显示在线提款 Payway!=W 包含(人工提款，discounType 6手工提出 8AG掉单存入提出)
$sql="select a.*,b.WinLossCredit,b.money,b.pay_class,b.Notes as Withdrawal_Notes,b.Usdt_Address from ".DBPREFIX."web_sys800_data AS a,".DBPREFIX.MEMBERTABLE." AS b where a.Checked='0' and a.userid=b.ID $wherelevel and a.Payway!='W' and a.Type='T' $mem ORDER BY ID DESC";
//echo $sql . '<br>';
$result = mysqli_query($dbLink,$sql);
$num=0;
$page_size=50;
$data=[];
while ($row = mysqli_fetch_assoc($result)) {
    $aUserId[] = $row['userid'];    //查询会员
    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
        $data[]=$row;
    }
    $num+=1;
}
$cou=$num;
$page_count=ceil($cou/$page_size);

if ($cou==0){
    $page_count=1;
}
if ($date_start==''){
    $date_start=date('Y-m-d');
}
if ($date_end==''){
    $date_end=date('Y-m-d', time()+86400);
}

// 提款审核笔，出款笔，存款笔
$withdrawcheck =$_REQUEST['withdrawcheck'] ;
if ($withdrawcheck !=""){
    $data=[];
    // 统计未审核笔数
    $data['withdraw_num'] = $cou;

    // 统计出款笔数 (出款只要显示未处理的笔数，不需要显示确定之后的笔数)
    $result = mysqli_query($dbLink,"select count(1) as cou from ".DBPREFIX."web_sys800_data as a,".DBPREFIX.MEMBERTABLE." as b where a.Checked=2 and a.Type='T' and a.userid=b.ID $wherelevel and a.AddDate>='$date_start' and a.AddDate<='$date_end'");
    $row = mysqli_fetch_assoc($result);
    $data['withdraw_num_1']=$row['cou'];

    // 统计存款笔数 (未处理的公司存款需要显示出来)
    $result = mysqli_query($dbLink,"select count(1) as cou from ".DBPREFIX."web_sys800_data as a,".DBPREFIX.MEMBERTABLE." as b where a.Checked=0 and a.Type='S' and a.Payway='N' and a.userid=b.ID $wherecklevel $where_ck_bank and a.AddDate>='$date_start' and a.AddDate<='$date_end'");
    $row = mysqli_fetch_assoc($result);
    $data['deposit_num_1']=$row['cou'];
    echo json_encode($data);
    exit;
}


//统计会员已出多少笔(统计 审核中、已提款) , 审核操作 出款  Checked :0 未审核，1 成功，-1 失败 ,-2 刚提交的订单
if ($cou>0){
    $aUserId = array_unique($aUserId); // 去重
    $userids = implode(',', $aUserId);
    $wUser = "AND a.userid IN ($userids)";
    $wCheck =  " a.Checked !=-1";
    $sql = "select userid,count(1) as cou from ".DBPREFIX."web_sys800_data as a,".DBPREFIX.MEMBERTABLE." as b where 
            $wCheck and a.userid=b.ID $wUser $wherelevel and a.Payway!='W' and a.Type='T' and a.AddDate>='$date_start' and a.AddDate<='$date_end' 
            group by a.userid";
    $res_num = mysqli_query($dbLink, $sql);
    $cou_num = mysqli_num_rows($res_num);
    if($cou_num>0) {
        while ($row = mysqli_fetch_assoc($res_num)){
            //[ '321341' => Array( 'userid' => 321341 ,'cou' => 2),  '321354' => Array('userid' => 321354 ,'cou' => 6)]
            $dataNum[$row['userid']] = $row['cou'];
        }
    }
}

?>
<html>
<head>
<title>800系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    .show_user_bet {display: inline-block;line-height: 25px;}
</style>
</head>
<!--<base target="net_ctl_main">
<base target="_top">-->
<body >
<dl class="main-nav">
    <dt>提款审核</dt>
    <dd>
    <table >
      <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
      <tr class="m_tline">
          <td>
              &nbsp;时间:
              <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_start?>" onclick="laydate({istime: false, istoday: false,format: 'YYYY-MM-DD'})" >
              ~
              <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
          </td>
          <td>&nbsp;
              <input type="text" id="mid" name="mid" placeholder="查询用户名" value="<?php echo $_REQUEST['mid'];?>">
          </td>
          <td > &nbsp;
              <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
          </td>
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
          <td> <?php echo $page_count?> 页</td>
      </tr>

    </FORM>
    </table>
    </dd>
</dl>
<div class="main-ui width_1300">
    <table class="m_tab">
            <tr class="m_title">
              <td >会员帐号</td>
              <td >姓名/电话</td>
              <td >金额变化</td>
              <td >输赢额度</td>
              <td >提款资料</td>
              <td >提款次数</td>
              <td >类别</td>
              <td >金额(RMB)</td>
              <td >状态</td>
              <td >审核</td>
              <td >备注</td>
            </tr>
            <!-- BEGIN DYNAMIC BLOCK: row -->
    <?php
    if ($cou==0){
    ?>
    <tr class="m_cen">
              <td colspan="11">目前沒有记录</td>
            </tr>
    <?php
    }else{
    foreach ($data as $k => $row){
    if($row['Type']=='T') {
        if($row['Checked']!=0 and $row['Gold']>0) {
            $gold-=$row['Gold'];
        }else{
            if($row['Checked']!=0){
                $gold+=$row['Gold'];
            }
        }
    }else{
        $gold+=$row['Gold'];
    }

    ?>

            <tr class="m_cen">
              <td><b><?php echo $row['UserName']?></b></td>
              <td><b><?php echo $row['Name']?></b><br><?php echo $row['Phone']?></td>
              <td class="money_change" align="left">
                  <p >
                      提款前：<span style="color: yellowgreen "><?php echo sprintf("%01.2f", $row['moneyf']);?></span><br>
                      提款后：<span style="color: red"><?php echo sprintf("%01.2f", $row['currency_after']);?></span>
                  </p>
              </td>
              <td class="money_win_lose"> <?php echo number_format($row['WinLossCredit'],0);?> </td>
              <td >
                    <?php if($row['Type']=="T"){?>
                        <?php if('USDT提款' == explode('-' , $row['InType'])[0] ){ ?>
                            类型：<?php echo isset($row['InType'])?$row['InType']:''; ?><br>
                            USDT地址：<?php echo isset($row['Usdt_Address'])?$row['Usdt_Address']:'';?><br>
                            账户姓名：<?php echo $row['Name']?>
                        <?php } else {?>
                            开户银行：<?php echo $row['Bank'].'--'.$row['Bank_Address']?><br>
                            银行账号：<?php echo $row['Bank_Account']?><br>
                            账户姓名：<?php echo $row['Name']?>
                        <?php }?>
                    <?php }?>
              </td>
              <td><?php echo !empty($dataNum[$row['userid']])? $dataNum[$row['userid']] : 0; ?></td> <!--提款次数-->

              <td><?php if($row['Type']=="S"){?>存入<?php }else{ ?>提出<?php }?></td>

              <td align="right"><font color="<?php echo $row['Checked']!=0?"red":""?>"><?php echo $row['Type']=='T'?"-":""?><?php echo sprintf("%01.2f", $row['Gold'])?></font></td>

              <td>
              <?php
      if($row['Checked']==1)
      {
         echo "<font style='color:green'>成功</font>";
      }
      else if($row['Checked']==0) { // 未审核
         echo "<font style='color:blue'>审核中</font>";
      }
      else if($row['Checked']==-1) {
         echo "<font style='color:red'>失败 ".$row['reason']."</font>";
      }
      ?>
              </td>

              <td width="149" class="check_action">
              <?php
              if($row['Checked']==0)
              {
              ?>
              <form  method=post target='_self' style="margin:0px; padding:0px;">
              <input name="Checked" type="radio" value="2" checked> 成功 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="Checked" type="radio" value="-1">失败
              <input name="reason" type="text" size=10 class="za_text"><br>
              <a class="show_user_bet za_button" href="javascript:;" data-username="<?php echo $row['UserName']?>">打码量</a>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=send value='提交' onClick="return confirm('确定审核此笔订单？')" class="za_button">
              <input type=hidden name=id value=<?php echo $row['ID']?>>
              <input type=hidden name=mid value=<?php echo $row['UserName']?>>
              <input type=hidden name=gold value=<?php echo $row['Gold']?>>
              <input type=hidden name=type value=<?php echo $row['Type']?>>
              <input type=hidden name=uid value=<?php echo $uid?>>
              <input type=hidden name=date_start value=<?php echo $date_start?>>
              <input type=hidden name=date_end value=<?php echo $date_end?>>
              <input type=hidden name=active id="active" value=Y></td>
              </form>
              <?php
              }
              else
              {
                echo $row['User']."<br>".$row['AuditDate'];
              }
              ?>

              </td>
                <td>
                    <textarea name="Notes" ID="Notes_<?php echo $row['ID']?>" rows="3" cols="20" ><?php echo $row['Withdrawal_Notes']; ?></textarea><br>
                    <input type="button" class="za_button" value="修改" onclick="btn_edit('<?php echo $row['ID']?>','<?php echo $row['UserName']?>','<?php echo $row['userid']?>','<?php echo $row['Order_Code']?>')">
                </td>
            </tr>

    <?php
    }
    }
    ?>
            <!-- END DYNAMIC BLOCK: row -->

          </table>

</div>

<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var lv = '<?php echo $lv?>' ;
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
    setBodyScroll();
    showMemberBet();

    // 会员打码量审核
    function showMemberBet() {
        $('.show_user_bet').on('click',function () {
            var username = $(this).data('username');
            var user_bet = {};
            var notice = '';
            // 获取会员已打码量
            $.ajax({
                type:"GET",
                url:"../agents/user_bet.php",
                async:false,
                data:{username : username},
                dataType:'json',
                success:function(response){
                    if(response.status == 200){
                        user_bet = response.data;
                        if(user_bet.total_bet < user_bet.owe_bet){
                            notice = '打码量不足，不能提款';
                        }else{
                            notice = '打码量已经满足要求，可以提款';
                        }
                    }
                }
            });
            var str = '<table class="table" border="1" cellspacing="0" cellpadding="5" width="100%"> <tbody>';
            $.each(user_bet.bet_list, function (i,v) {
                str += '<tr><td>' + v.msg + '</td><td>' + v.value + '</td></tr>';
            });
            str += '<tr><td colspan="2" align="top">合计打码量<span class="total_bet">' + user_bet.total_bet + '</span>，要求提款打码量' + user_bet.owe_bet + '<br><span><font color="red">' + notice + '</span></font></td></tr></tbody></table>';
            layer.alert(str, {title:username + ' 打码量' });
        })
    }
</script>
</body>
</html>
<!-- 插入系统日志 -->
<?php
if ($active=='Y' || $active=='Withdrawal_Notes_edit'){ // 有操作才需要插入
    innsertSystemLog($name,$lv,$loginfo);
    echo "<script>parent.main.location.href='withdraw_list_800.php?uid=$uid&langx=$langx&lv=$lv'</script>";
}
?>