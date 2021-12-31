<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$name= isset($_REQUEST['name'])?$_REQUEST['name']:'';
$seconds=$_REQUEST["seconds"];
$page=$_REQUEST["page"];
$date_start=$_REQUEST['date_start'];

$seachcontent = preg_replace('# #','',isset($_REQUEST['seachcontent'])?$_REQUEST['seachcontent']:''); // 去除所有空格，搜索内容

require ("../include/traditional.$langx.inc.php");

if ($seconds==''){
	$seconds=180;
}
if ($date_start=='') {
	$date_start=date('Y-m-d');
}
if ($page==''){
	$page=0;
}
$seachsql = "";
if($name){
    $seachsql .="AND UserName='$name'";
}
if($seachcontent){
    $seachsql .= "AND ConText LIKE '%$seachcontent%'";
}

$sql = "select * from ".DBPREFIX."web_mem_log_data where LoginTime like '%$date_start%' $seachsql order by LoginTime desc ";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
// echo $sql;
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
if ($cou==0){
	$page_count=1;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body onLoad="onLoad();auto_refresh()">
<form name="myFORM" action="" method=POST>
    <dl class="main-nav">
        <dt>系统日志</dt>
        <dd>
         <table >
            <tr class="m_tline">
              <td  width="140">&nbsp;线上数据－<font color="#CC0000">日志</font><font color="#CC0000">&nbsp;</font></td>
              <td >
              <select class="za_select za_select_auto" onChange="document.myFORM.submit();" id="seconds" name="seconds">
                <option value="10">10秒</option>
                <option value="30">30秒</option>
                <option value="60">60秒</option>
                <option value="90">90秒</option>
                <option value="120">120秒</option>
                <option value="180">180秒</option>
              </select>&nbsp;&nbsp;&nbsp;<span id=ShowTime></span></td>
              <td  >日期:</td>
              <td>
                  <input class="za_text_auto" id="date_start" name="date_start" value="<?php echo $date_start;?>" onclick="laydate({istime: true,format: 'YYYY-MM-DD',choose:checkDate})"  readonly/>
              </td>
                <td class="m_tline" >
                    --<input type="text" name="seachcontent" value="<?php echo $seachcontent;?>" class="za_text" size="15" placeholder="请输入关键字">
                    <input type="BUTTON" name="btn_search" value="快速查询" onclick="document.myFORM.submit();" class="za_button"></td>
              <td  width="137">
              <select class="za_select za_select_auto" onchange=document.myFORM.submit(); id="page" name="page">
                      <?php
                      for($i=0;$i<$page_count;$i++){
                          echo "<option value='$i'>".($i+1)."</option>";
                         }
                      ?>
              </select>
               / <?php echo $page_count?>  <?php echo $Mem_Page?>	  </td>
              <td ><a href="javascript:history.go( -1 );">回上一页</a></td>


            </tr>
        </table>
        </dd>
    </dl>
 <div class="main-ui">
        <table class="m_tab">
          <tr class="m_title">
            <td width="51">序 号</td>
            <td width="69">帐 号</td>
            <td width="69">级 别</td>
            <td width="139">活动时间</td>
            <td width="543">活动内容</td>
            <td width="97">登陆IP</td>
          </tr>
        <?php
        $i=1;
        if ($row = mysqli_fetch_assoc($result)){
        do {

        ?>
          <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
            <td width="51"><?php echo $i?></td>
            <td width="69"><?php echo $row["UserName"]?></td>
            <td width="69"><?php echo $row["Level"]?></td>
            <td width="139"><font color="#CC0000"><?php echo $row["LoginTime"]?></font></td>
            <td width="543" align="left"><?php echo $row["ConText"]?></td>
            <td width="97"><?php echo $row["LoginIP"]?></td>
          </tr>
        <?php
        $i++;
        }
        while ($row=mysqli_fetch_array($result));
        }else{
                echo "<tr height=20><td colspan=9 ><font color=#FFFFFF><div align=center>暂无数据</div></font></td></tr>";
        }
        ?>
        </table>
 </div>
</form>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>
<script>
    function checkDate() {
        document.myFORM.submit();
    }
    function onLoad() {
        var obj_seconds = document.getElementById('seconds');
        obj_seconds.value = '<?php echo $seconds?>';
        var obj_date_start = document.getElementById('date_start');
        obj_date_start.value = '<?php echo $date_start?>';
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }
    var second="<?php echo $seconds?>"
    function auto_refresh(){
        if (second==1){
            window.location.href='showlog.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&name=<?php echo $name?>&date_start=<?php echo $date_start?>&seconds=<?php echo $seconds?>&page=<?php echo $page?>'; //刷新页面
        }else{
            second-=1
            curmin=Math.floor(second)
            curtime=curmin+"秒自动更新"
            ShowTime.innerText=curtime
            setTimeout("auto_refresh()",1000)
        }
    }
</script>

</body>
</html>