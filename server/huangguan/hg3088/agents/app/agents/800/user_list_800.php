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


// 当前第三方数据
$thirdSql = "SELECT id,title,account_company,thirdpay_code from ".DBPREFIX."gxfcy_pay";
$thirdResult = mysqli_query($dbLink,$thirdSql);

if($_SESSION['Level'] == 'M') {
   $web=DBPREFIX.'web_system_data';
}else{
   $web=DBPREFIX.'web_agents_data';
}
switch ($lv){
case 'M':
	$user='Admin';
	break;	
case 'A':
	$user='Super';
	break;
case 'B':
	$user='Corprator';
	break;
case 'C':
	$user='World';
	break;
case 'D':
    $user='Agents';
	break;
}
//$sql = "select ID,UserName,Language,SubUser,SubName from $web where Oid='$uid' and UserName='$loginname'";
//$result = mysqli_query($dbLink,$sql);
//$row = mysqli_fetch_assoc($result);
$subUser=$_SESSION['SubUser'];
if ($subUser==0){
	$name=$_SESSION['UserName'];
}else{
	$name=$_SESSION['SubName'];
}
$sql = "select ID,UserName,CurType from ".DBPREFIX.MEMBERTABLE." where $user='$name' and Pay_Type=1";
$active=$_REQUEST['active'];
$memname=$_REQUEST['mid'];
//echo $memname;
$id=$_REQUEST['id'];
$gold=$_REQUEST['gold'];
$rtype=$_REQUEST['type']; // 审核方式 存入(S)，提出类型(T)
$s_type=strval($_REQUEST['save_type']); // 存入方式(save_S)，提出类型(save_T)已经取消      改为接收当前第三方信息 方便统计
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$page=$_REQUEST["page"];
$seach_name = isset($_REQUEST['seach_name'])?$_REQUEST['seach_name']:'' ; // 关键字查询
if ($memname==''){
	$mem="";
}else{
	$mem="and ".DBPREFIX."web_sys800_data.UserName='$memname'";
}
if ($date_start==''){
	$date_start=date('Y-m-d');
}
if ($date_end==''){
	$date_end=date('Y-m-d',time()+86400);
}
if ($rtype==''){ // 审核方式 存入(S)，提出类型(T)
	$type="";
}else{
	$type="and ".DBPREFIX."web_sys800_data.Type='$rtype'";
}
if($seach_name){ // 关键字查询
    $seach_name_sql = "and (".DBPREFIX."web_sys800_data.UserName LIKE '%$seach_name%' or ".DBPREFIX."web_sys800_data.Name LIKE '%$seach_name%')";
}

// 查询当前第三方
if(!empty($s_type)) {
    $save_type="and ".DBPREFIX."web_sys800_data.PayType='$s_type'";
    if($rtype==''){
        $type="and ".DBPREFIX."web_sys800_data.Type='S'";
    }
}

if ($page==''){
	$page=0;
}
$date=date('Y-m-d H:i:s');

if ($mid==''){
	$sql="select ".DBPREFIX."web_sys800_data.*,".DBPREFIX.MEMBERTABLE.".money from ".DBPREFIX."web_sys800_data,".DBPREFIX.MEMBERTABLE." where ".DBPREFIX."web_sys800_data.UserName=".DBPREFIX.MEMBERTABLE.".UserName $mem $type $save_type $seach_name_sql and ".DBPREFIX."web_sys800_data.AddDate>='$date_start' and ".DBPREFIX."web_sys800_data.AddDate<='$date_end' and ".DBPREFIX."web_sys800_data.Checked=1 and ".DBPREFIX."web_sys800_data.Type !='Q' and ".DBPREFIX."web_sys800_data.Payway !='N' order by AddDate desc";
}else{
	$sql="select ".DBPREFIX."web_sys800_data.*,".DBPREFIX.MEMBERTABLE.".money from ".DBPREFIX."web_sys800_data,".DBPREFIX.MEMBERTABLE." where ".DBPREFIX."web_sys800_data.UserName=".DBPREFIX.MEMBERTABLE.".UserName $mem $type $save_type $seach_name_sql and ".DBPREFIX."web_sys800_data.AddDate>='$date_start' and ".DBPREFIX."web_sys800_data.AddDate<='$date_end' and ".DBPREFIX."web_sys800_data.Type !='Q' and ".DBPREFIX."web_sys800_data.Payway !='N' order by AddDate desc";
}

$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
//echo $mysql ;
$result = mysqli_query($dbLink,$mysql);
if ($cou==0){
	$page_count=1;
}
?>
<html>
<head>
<title>800系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<!--<link rel="stylesheet" href="../../../style/agents/control_calendar.css?v=<?php echo AUTOVER; ?>">-->
<style type="text/css">input.za_text_auto{width: 100px;}</style>
</head>
<!--<base target="net_ctl_main">
<base target="_top">-->
<body >
<dl class="main-nav">
    <dt>现金系统</dt>
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
                <td width="70" >
                    <a class="layer_div_a" href="user_list_800.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" onMouseOver="MM_showHideLayers

    ('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')"><font color="#990000">帐户作业</font></a></td>
                <td >关键字查找:</td>
                <td >
                    <input type="text" name="seach_name" value="<?php echo $seach_name?>" class="za_text_auto"/>
                    <input type="submit" name="submit_name" value="确认" class="za_button">
           <!-- <select name="mid" class="za_select za_select_auto">
                <option value="">全部</option>
                <?php
/*                $msql = "select UserName,CurType from ".DBPREFIX.MEMBERTABLE." where $user='$name' and Pay_Type=1";
                $mresult = mysqli_query($dbLink,$msql);
                while ($mrow = mysqli_fetch_array($mresult)){
                    echo "<option value=$mrow[UserName]>".$mrow['UserName']."==".$mrow['CurType']."</option>";
                }
                */?>
            </select>-->
                </td>
                <td width="68">&nbsp;--&nbsp;日期区间:</td>
                <td>
                  <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_start?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                  &nbsp;</td>
                <td>
                </td>
                <td>~&nbsp;</td>
                <td>
                  <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                  &nbsp;</td>
                <td>
                </td>
                <td width="70">&nbsp;--&nbsp;审核方式:</td>
                <td>
                  <select name="type" id="type" class="za_select za_select_auto" onchange="chooseType(this)">
                  <option value="">全部类别</option>
                  <option value="S"<?php if($_POST['type']=="S"){?> selected<?php }?>>存入</option>
                  <option value="T"<?php if($_POST['type']=="T"){?> selected<?php }?>>提出</option>
                  </select>
                </td>
          <td width="70">&nbsp;--&nbsp;存入方式:</td>
          <td>
              <select name="save_type" id="save_type" class="za_select za_select_auto" onchange="chooseType(this)">
                  <option value="">全部</option>

                  <?php
                  while ($row = mysqli_fetch_array($thirdResult)){
                      if($row[id] == $s_type) {
                          echo "<option value='$row[id]' selected>".$row[title]."</option>";
                      }else {
                          echo "<option value='$row[id]'>".$row[title]."</option>";
                      }
                  }
                  ?>
                  <!--<option value="save_S"<?php /*if($_POST['save_type']=="save_S"){*/?> selected<?php /*}*/?>>人工存入</option>
                  <option value="save_T"<?php /*if($_POST['save_type']=="save_T"){*/?> selected<?php /*}*/?>>人工提出</option>-->
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
<div class="main-ui">
    <table class="m_tab">
            <tr class="m_title">
              <td width="113">会员帐号</td>
              <td width="113">姓名/电话</td>
              <td width="232">银行资料</td>
              <td width="49">币别</td>
              <td width="66">金额(RMB)</td>
              <td width="119">状态</td>
              <td >日期</td>
              <td >审核帐号/日期</td>
              <td >操作</td>
            </tr>
            <!-- BEGIN DYNAMIC BLOCK: row -->
    <?php
    if ($cou==0){
    ?>
    <tr class="m_cen">
              <td colspan="10">目前沒有记录</td>

            </tr>
    <?php
    }else{
        while ($row = mysqli_fetch_array($result)){
        if($row['Type']=='T')
        {  // 提款    审核状态Checked 0 首次提交订单 2 二次审核  1成功 -1失败
            if($row['Checked']!=0 and $row['Gold']>0)
            {
                $gold-=$row['Gold'];
            }else{
                if($row['Checked']!=0)
                    {
                        $gold+=$row['Gold'];
                    }
                }
        }
        else
        {
            $gold+=$row['Gold'];
        }

        ?>

                <tr class="m_cen">
                  <td><b><?php echo $row['UserName']?></b></td>
                  <td><b><?php echo $row['Name']?></b><br><?php echo $row['Phone']?></td>
                    <td class="bank_details">
                        <?php
                        if($row['Type']=="T"){ // 提款
                            // Bank银行， Bank_Address开户行，Bank_Account银行账号
                            echo $row['Bank'].'<br>';
                            echo $row['Bank_Address'].'<br>';
                            echo $row['Bank_Account'].'<br>';
                        }else{ // 存款
                            echo $row['Notes'].'<br>';
                            echo $row['Order_Code'].'<br>';
                        }
                         ?>

                    </td>
                  <td><?php echo $row['CurType'];?></td>

                  <td align="right">
                      <font color="<?php echo $row['Checked']!=0?"red":""?>"><?php echo $row['Type']=='T'?"-":""?><?php echo sprintf("%01.2f", $row['Gold'])?></font></td>

                  <td>
                  <?php
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
                         echo "<font style='color:red'>失败 ".$row['reason']."</font>";
                      }
                  ?>
                  </td>
                    <td class="add_date"><?php echo $row['AddDate'];?></td>
                  <td >
                  <?php
                  if($row['Checked']==0)
                  {
                  ?>
                  <form  method=post target='_self' style="margin:0px; padding:0px;">
                  <input name="Checked" type="radio" value="1" checked> 成功<br>
                  <input name="Checked" type="radio" value="-1">
                  失败：<input name="reason" type="text" size=10 class="za_text"><br>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=send value='提交' onClick="return confirm('确定审核此笔订单？')" class="za_button">
                  <input type=hidden name=id value=<?php echo $row['ID']?>>
                  <input type=hidden name=mid value=<?php echo $row['UserName']?>>
                  <input type=hidden name=gold value=<?php echo $row['Gold']?>>
                  <input type=hidden name=type value=<?php echo $row['Type']?>>
                  <input type=hidden name=uid value=<?php echo $uid?>>
                  <input type=hidden name=date_start value=<?php echo $_REQUEST['date_start']?>>
                  <input type=hidden name=date_end value=<?php echo $_REQUEST['date_end']?>>
                  <input type=hidden name=type value=<?php echo $_REQUEST['type']?>>
                  <input type=hidden name=active id="active" value=Y>
                  </td>
                  </form>
                  <?php
                  }
                  else
                  {
                    echo $row['User']."<br>".$row['AuditDate'];
                  }
                  ?>

                  </td>
                    <td data-way="<?php echo $row['Payway']?>" data-type="<?php echo $row['discounType']?>" data-name="<?php echo $row['PayName']?>">
                        <?php
                        if($row['Type']=='S'){
                            echo '存入';
                        }elseif($row['Type']=='T'){
                            echo '提出';
                        }elseif($row['Type']=='R'){
                            echo '返水';
                        }elseif($row['Type']=='Q'){
                            echo '额度转换';
                        }
                        ?>
                    </td>
                </tr>

        <?php
        }
    }
    ?>
            <!-- END DYNAMIC BLOCK: row -->
            <tr class="m_rig2">
               <td colspan="3"><?php echo date("Y-m-d",strtotime($date_start)).'到'.date("Y-m-d",strtotime($date_end)); ?></td>
              <td colspan="4">总计：<?php echo sprintf("%01.2f", $gold)?></td>

            </tr>
          </table>

</div>

<!--<script language="JavaScript" src="../../../js/agents/simplecalendar.js?v=<?php echo AUTOVER; ?>"></script>-->
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="JavaScript">
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
    // 类型选择
    function chooseType(obj) {
        var val = obj.value ;
        var obj_str = obj.getAttribute('name') ;
        //console.log(obj_str);
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