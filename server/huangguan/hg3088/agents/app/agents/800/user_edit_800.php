<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");    
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");    
header("Cache-Control: no-store, no-cache, must-revalidate");    
header("Cache-Control: post-check=0, pre-check=0", false);    
header("Pragma: no-cache"); 
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$depositUsername = $_REQUEST['username'];
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

if ($_REQUEST["send_form"]=='OK'){
	//查询存款用户是否存在必填项此处为必填项
	if(strlen($_REQUEST["UserName"])<1){
		$errorStr="请输入会员用户名";
		echo "<script>alert('".$errorStr."');</script>";
		echo "<script languag='JavaScript'>self.location='user_list_800.php?uid=$uid&lv=$lv&langx=$langx'</script>";
	}
	
	$memname=$_REQUEST["UserName"];
	$msql = "SELECT ID,UserName,Alias,Agents,World,Corprator,Super,Admin from ".DBPREFIX.MEMBERTABLE." where UserName='$memname' ";
	$result = mysqli_query($dbLink,$msql);
	$cou=mysqli_num_rows($result);
	if($cou==0){
		$errorStr="用户".$memname."不存在";
		echo "<script>alert('".$errorStr."');</script>";
		echo "<script languag='JavaScript'>self.location='user_list_800.php?uid=$uid&lv=$lv&langx=$langx'</script>";
	}
	$member = mysqli_fetch_assoc($result);
	$memberUserid=$member['ID'];
	$realName = $member['Alias']; // 用户真实姓名
    $agents=$member['Agents'];
    $world=$member['World'];
    $corprator=$member['Corprator'];
    $super=$member['Super'];
    $admin=$member['Admin'];
    /**
     *
     * $payway
     * 账变后台操作：
    1 未知类型
    2 在线入款掉单补单
    3 周周返点补单
    4 优惠
    5 公司入款补单
    6 手工提出
    7 手工存入
    8 AG掉单存入提出
     *
     */
	$payway='W';//$_REQUEST["payway"]
	$type=$_REQUEST["type"];
	$gold=$_REQUEST["gold"];
	$discounType=$_REQUEST["discounType"];
	$send_form=$_REQUEST["send_form"];
	$countBet = $_REQUEST['count_bet']; // 是否计算打码量
	
	switch($payway){
	case 'C':
		$no=$_REQUEST[cc1]."-".$_REQUEST[cc2]."-".$_REQUEST[cc3]."-".$_REQUEST[cc4]."-".$_REQUEST[authorize];
		break;
	case 'A':
		$no=$_REQUEST[atm_no];
		break;
	case 'W':
		$no=$_REQUEST['water_no'];
		break;
	}
	$adddate=date('Y-m-d');
	$datehis=date('Y-m-d H:i:s');
	if($type=='T'){ // 人工提出
		$money="$gold";
		$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where Username='$memname' for update");
        if($resultMem) {
            $rowMem = mysqli_fetch_assoc($resultMem);
            if($rowMem['Money'] >= $money){
                $moneyf = $rowMem['Money']; // 账变前余额
                $currency_after = $rowMem['Money']-$money; // 账变后余额
                $mysql="insert into ".DBPREFIX."web_sys800_data(userid,Payway,discounType,Gold,moneyf,currency_after,AddDate,Type,UserName,Agents,World,Corprator,Super,Admin,CurType,Name,Waterno,Checked,User,Date,AuditDate) values ($memberUserid,'$payway',$discounType,'$money','$moneyf','$currency_after','$adddate','$type','$memname','$agents','$world','$corprator','$super','$admin','RMB','$realName','$no',0,'$loginname','$datehis','$datehis')";
		        if(mysqli_query($dbMasterLink,$mysql)){
		        	$insertID=mysqli_insert_id($dbMasterLink);
                    $mysql = "update " . DBPREFIX.MEMBERTABLE." set Money=Money-$money where ID='" . $rowMem['ID'] . "'";
                    if (mysqli_query($dbMasterLink, $mysql)) {
                    	$moneyLogRes=addAccountRecords(array($rowMem['ID'],$rowMem['UserName'],$rowMem['test_flag'],$rowMem['Money'],$money*-1,$currency_after,12,6,$insertID,"人工出款,操作人:{$loginname}"));
                    	if($moneyLogRes){
	                    	$res = level_deal($memberUserid, $gold, 1);//用户层级关系处理
	                        if ($res) {
	                            mysqli_query($dbMasterLink, "COMMIT");
	                            $loginfo_status = '<font class="red">人工提出</font>';
	                            $loginfo = $loginname.' 对会员帐号 <font class="green">'.$memname.'</font>进行了 <font class="red">'.$loginfo_status.'</font>,金额为 <font class="red">'.number_format($gold,2).'</font>,水单为 <font class="blue">'.$no.'</font> ' ;
			        			innsertSystemLog($loginname,$lv,$loginfo);
			        			echo "<script languag='JavaScript'>self.location='index.php?uid=$uid&lv=$lv&langx=$langx'</script>";
	                        }else{
	                        	mysqli_query($dbMasterLink,"ROLLBACK");
	                        	echo "<script languag='JavaScript'>alert('用户层级关系处理失败！');</script>";
	                        }
                    	}else{
                    		mysqli_query($dbMasterLink,"ROLLBACK");
                    		echo "<script languag='JavaScript'>alert('用户账变记录添加失败！');</script>";
                    	}
                    }else{
                    	mysqli_query($dbMasterLink,"ROLLBACK");
                    	echo "<script languag='JavaScript'>alert('用户金额更新失败！');</script>";
                    }
		        }else{
		        	mysqli_query($dbMasterLink,"ROLLBACK");
		        	echo "<script languag='JavaScript'>alert('出款历史记录添加失败！');</script>";
		        }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "<script languag='JavaScript'>alert('余额不足！');</script>";
            }
        }else{
        	mysqli_query($dbMasterLink,"ROLLBACK");
        	echo "<script languag='JavaScript'>alert('用户资金锁添加失败！');</script>";
        }
 	}elseif($type=='S'){ // 人工存入
	    $money=$gold;
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where Username='$memname' for update");
        if($resultMem) {
            $rowMem = mysqli_fetch_assoc($resultMem);
            if($rowMem['ID']>0) {
                $moneyf = $rowMem['Money']; // 账变前余额
                $currency_after = $rowMem['Money'] + $money; // 账变后余额
                $mysql="insert into ".DBPREFIX."web_sys800_data(userid,Payway,discounType,Gold,moneyf,currency_after,AddDate,Date,Type,UserName,Agents,World,Corprator,Super,Admin,CurType,Name,Waterno,count_bet) values ($memberUserid,'$payway',$discounType,'$money','$moneyf','$currency_after','$adddate','$datehis','$type','$memname','$agents','$world','$corprator','$super','$admin','RMB','$realName','$no','$countBet')";
                $res = mysqli_query($dbMasterLink,$mysql);
                if ($res) {
                    mysqli_query($dbMasterLink, "COMMIT");
                 	/* 插入系统日志 */
			    	$loginfo_status = '<font class="red">人工存入</font>' ;
			        $loginfo = $loginname.' 在账户作业对会员帐号 <font class="green">'.$memname.'</font>进行了 <font class="red">'.$loginfo_status.'</font>,金额为 <font class="red">'.number_format($gold,2).'</font>,水单为 <font class="blue">'.$no.'</font> ' ;
			        innsertSystemLog($loginname,$lv,$loginfo);
			        echo "<script>self.location='index.php?uid=$uid&lv=$lv&langx=$langx'</script>";
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK");
					echo "<script>alert('存款历史记录添加失败！');</script>";
				}
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "<script>alert('用户信息有误！');</script>";
            }
        }else{
        	mysqli_query($dbMasterLink,"ROLLBACK");
        	echo "<script>alert('用户资金锁添加失败！');</script>";
        }
    }
}
?>
<html>
<head>
<title>人工存入提出</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<!--<base target="net_ctl_main">-->
<body onLoad="<?php if($lv=='A'){ ?>showParentb();showParentc();showParentd();<?php }elseif($lv=='B'){ ?>showParentc();showParentd();<?php }elseif($lv=='C'){ ?>showParentd();<?php } ?>" >
<dl class="main-nav">
    <dt>存入帐户</dt>
    <dd>
<div id="Layer1" class="layer_div" onMouseOver="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')">
    <ul>
        <li class="mou first"><a href="user_list_800_2018.php">帐户查询</a></li>
        <li class="mou" ><a href="user_edit_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>">存入帐户</a></li>
    </ul>
</div>

<table >
  <tr class="m_tline">
    <td width="70">
        <a class="layer_div_a" href="user_edit_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" onMouseOver="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')"><font color="#990000">帐户作业</font></a>
    </td>
      <td> <a href="javascript:history.go( -1 );">回上一页</a> </td>
  </tr>
</table>
    </dd>
</dl>
<div class="main-ui">
          <form method="post" action="" onSubmit="return SubChk()">
            <table  class="m_tab">
              <tr >
                <td width="110" class="m_title">存入帐号</td>
                <td align="left">
    <?php
    if($lv=='A'){
    ?>
                &nbsp;&nbsp;股东
                  <select name="Corprator" class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>&nbsp;&nbsp;总代理
                  <select name="World"  class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>&nbsp;&nbsp;代理商
                  <select name="Agents"  class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>
    <?php
    }else if($lv=='B'){
    ?>
                  </select>&nbsp;&nbsp;总代理
                  <select name="World"  class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>&nbsp;&nbsp;代理商
                  <select name="Agents"  class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>
    <?php
    }else if($lv=='C'){
    ?>
                  </select>&nbsp;&nbsp;代理商
                  <select name="Agents"  class="za_select" onChange="showChild();showMem();Chg_Mcy();">
                  <option value=''>-----</option>
                  </select>
    <?php
    }
    ?>
                  会员
                  <input type="text" name="UserName" size=8 value="" minlength="5" maxlength="15" class="za_text">
                </td>
              </tr>
              <tr>
                <td class="m_title">付款类别</td>
                <td align="left">水单 <input type="text" name="water_no" size="25" maxlength="50" class="za_text"> </td>
              </tr>
              <tr>
                <td class="m_title">方式</td>
                <td align="left">
                  <input type="radio" name="type" value="S" checked>存入
                  <input type="radio" name="type" value="T">提出
                </td>
              </tr>
              <tr>
                <td class="m_title">类别</td>
                <td align="left">
                    <select name="discounType" class="za_select">
                        <option value=1 >未知类型</option>
                        <option value=2 >在线入款掉单补单</option>
                        <option value=3 >周周返点补单</option>
                        <option value=4 >优惠</option>
                        <option value=5 >公司入款补单</option>
                        <option value=6 >手工提出</option>
                        <option value=7 >手工存入</option>
                        <option value=8 >AG掉单存入提出</option>
                        <option value=9 >快速充值存入</option>
                    </select>
                </td>
              </tr>
              <tr >
                <td class="m_title">金额</td>
                <td align="left">
                  <input type="text" name="gold" size="10" maxlength="10" class="za_text"  onKeyPress="return CheckKey()" value="0">
                    人民币 :<font color=red id=mcy_gold>0</font></td>
              </tr>
              <tr>
                <td class="m_title">计算打码量</td>
                <td align="left">
                    <input type="radio" name="count_bet" value="0">不计算
                    <input type="radio" name="count_bet" value="1" checked>计算
                    &nbsp;&nbsp;&nbsp;&nbsp;<font color="red"> 注明：若计算打码量按默认1倍计算</font></td>
              </tr>
              <tr >
                <td class="m_title">&nbsp;</td>
                <td align="left">
                  <input type="submit" name="Submit" value="确定" class="za_button">
                  &nbsp;&nbsp;&nbsp;
                  <input type="reset" name="Submit2" value="重设" class="za_button">
                  <input type="hidden" name="send_form" value="OK">
                  <input type="hidden" name="uid" value="<?php echo $uid?>">
                </td>
              </tr>
            </table>
    </form>
</div>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script language=javascript>
    function showParentb(){
        document.forms[0].Corprator.options.length=1;
        document.forms[0].Corprator.options[0].text="<?php echo $corprator?>";
        document.forms[0].Corprator.options[0].value="<?php echo $corprator?>";
    }
    function showParentc(){
        document.forms[0].World.options.length=1;
        document.forms[0].World.options[0].text="<?php echo $world?>";
        document.forms[0].World.options[0].value="<?php echo $world?>";
    }
    function showParentd(){
        document.forms[0].Agents.options.length=1;
        document.forms[0].Agents.options[0].text="<?php echo $agents?>";
        document.forms[0].Agents.options[0].value="<?php echo $agents?>";
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

    function CheckKey(){
        if(event.keyCode == 13) return false;
        if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("存入金额仅能输入数字!!"); return false;}
    }

    function Chg_Mcy(){
        curr=new Array();
        curr['RMB']=1;
        curr['HKD']=1.045;
        curr['USD']=8.12;
        curr['MYR']=2.18;
        curr['SGD']=4.9;
        curr['THB']=0.21;
        curr['GBP']=14.5;
        curr['JPY']=0.078;
        curr['EUR']=9.93;
        curr['IND']=0.0009;

        tmp=document.all.UserName.options[document.all.UserName.selectedIndex].value;
        tmp=tmp.split("-");
        str=tmp[1];

        ratio=eval(curr[str]);
        tmp_count=ratio*eval(document.all.gold.value);
        document.all.mcy_gold.innerHTML=tmp_count;
    }

    function SubChk()
    {
        if (document.all.UserName.value.length ==0)
        {
            alert('请输入会员名称');
            document.all.UserName.focus();
            return false;
        }

        if(document.all.water_no.value.length ==0)
        {
            alert('请输入水单号码');
            document.all.water_no.focus();
            return false;
        }
        if (document.all.gold.value.length==0 || document.all.gold.value==0){
            alert('请输入金额');
            document.all.gold.focus();
            return false;
        }

        var  disType = document.all.discounType.value;
        var  type = $("input[name='type']:checked").val();
        var countBet = $("input[name='count_bet']:checked").val();

        // '1','2','3','4','5','7','9' 存入  6,8 提出
        if((disType==6 && type=='S') || (disType==8 && type=='S') || (disType==1 && type=='T') || (disType==2 && type=='T') || (disType==3 && type=='T') || (disType==4 && type=='T') || (disType==5 && type=='T') || (disType==7 && type=='T') || (disType==9 && type=='T') || (type=='T' && countBet==1)){
            alert('方式与类别冲突，请重新选择');
            return false;
        }

        if (confirm('确定 存入/提出 该帐号??'))
        {
            return true;
        }else{
            return false;
        }
    }


</script>


</body>
</html>