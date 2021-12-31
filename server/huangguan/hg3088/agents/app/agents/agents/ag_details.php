<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require_once ("../../agents/include/config.inc.php");
include ("../../agents/include/address.mem.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$id=$_REQUEST["id"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

$sql = "select ID,Level,UserName,SubUser,SubName from ".DBPREFIX."web_system_data where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
$agname=$row['Agname'];
$agid=$row['ID'];
$langx='zh-tw';
require ("../../agents/include/traditional.$langx.inc.php");


$year=$_REQUEST['year'];
$mon=$_REQUEST['mon'];
$day=$_REQUEST['day'];
$year1=$_REQUEST['year1'];
$mon1=$_REQUEST['mon1'];
$day1=$_REQUEST['day1'];

$start_time=($year."-".$mon."-".$day);
$end_time=($year1."-".$mon1."-".$day1);

$sql="select UserName from ".DBPREFIX."web_agents_data where Status=0 and id=$id and level='D'";
//print_r($_REQUEST);
$tresult = mysqli_query($dbLink,$sql);
$trow = @mysqli_fetch_assoc($tresult);
$sql="select count(*) from ".DBPREFIX.MEMBERTABLE." where Agents='".$trow[0]."'";
$result = mysqli_query($dbLink,$sql);
$row = @mysqli_fetch_assoc($result);
$countmember=$row[0];

$sql="select UserName from ".DBPREFIX.MEMBERTABLE." where Agents='".$trow[0]."'";
$result = mysqli_query($dbLink,$sql);
while($row = @mysqli_fetch_assoc($result)){
$UserName.="'".$row['UserName']."',";
}
$UserName=substr($UserName,0,-1);


$sql="select sum(gold) from ".DBPREFIX."web_sys800_data where UserName in ($UserName) and AddDate>='".$start_time."' and AddDate<='".$end_time."' and Type='S'";
$result = mysqli_query($dbLink,$sql);
$row = @mysqli_fetch_assoc($result);
$inpay=empty($row[0])?0:$row[0];

$sql="select sum(gold) from ".DBPREFIX."web_sys800_data where UserName in ($UserName) and AddDate>='".$start_time."' and AddDate<='".$end_time."' and Type='S'";
$result = mysqli_query($dbLink,$sql);
$row = @mysqli_fetch_assoc($result);
$okpay=empty($row[0])?0:$row[0];



?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">

</style>
</head>
<body  >
    <form name="myFORM" action="ag_details.php?uid=<?php echo $uid?>" method=POST>
        <dl class="main-nav">
            <dt>分红明细</dt>
            <dd>
            <table>
              <tr class="m_tline">
                        <td >
                          选择代理商
                          <select class="za_select za_select_auto" id="id"  name="id">
                            <option value="" selected><?php echo $rep_pay_type_all?></option>
                            <?php
                            $mysql="select ID,UserName from ".DBPREFIX."web_agents_data where Status=0 and level='D'";
                            $ag_result = mysqli_query($dbLink, $mysql);
                            while ($ag_row = mysqli_fetch_assoc($ag_result)){
                                if ($id==$ag_row['ID']){
                                    echo "<option value=".$ag_row['ID']." selected>".$ag_row['UserName']."</option>";
                                    $sel_agents=$ag_row['UserName'];
                                }else{
                                    echo "<option value=".$ag_row['ID'].">".$ag_row['UserName']."</option>";

                                }
                            }
                            ?>
                        </select>

                            <select id=year class=lan name=year>
                              <?php for($i=date("Y")-3;$i<=date("Y");$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("Y")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 年
                            <select id=select class=lan name=mon>
                               <?php for($i=1;$i<=12;$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("m")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 月
                            <select  id=dayDdl class=lan name=day>
                              <?php for($i=1;$i<=31;$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("d")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 日

                            -
                           <select id=select8 class=lan name=year1>
                              <?php for($i=date("Y")-3;$i<=date("Y");$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("Y")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 年
                            <select id=select9 class=lan name=mon1>
                                    <?php for($i=1;$i<=12;$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("m")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 月
                            <select  id=select10 class=lan name=day1>
                              <?php for($i=1;$i<=31;$i++){ ?>
                                <option value="<?php echo  $i ?>" <?php if($i==date("d")){ ?>selected<?php } ?>><?php echo  $i ?></option>
                              <?php } ?>
                              </select> 日
                          <input type="submit" id="submit" name="submit" value="查询" class="za_button">
                     </td>
              </tr>

           </table>
            </dd>
        </dl>
 <div class="main-ui">
  <table class="m_tab">
       <tr class="m_title">
          <td width="60">&nbsp;</td>
          <td width="76">会员帐号</td>
          <td width="94">充值金额</td>
            <td width="86">有效金额</td>
            <td width="91">代理佣金</td>

        </tr>
        <tr class="m_cen">
          <td>统计</td>
          <td><?php echo $countmember ?>    /人</td>
          <td><?php echo $inpay ?></td>
          <td ><?php echo $okpay ?></td>
            <td ><font color="#ff3300">
          <?php echo $okpay*0.2 ?>    </font></td>

        </tr>
        <?php

    ?>
    </table>
</div>
    </form>


<script type="text/javascript" src="../../../js/agents/user_search.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

</body>
</html>