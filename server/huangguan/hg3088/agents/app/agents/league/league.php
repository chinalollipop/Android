<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname = $_SESSION['UserName'];
$lv =  $_SESSION['admin_level'] ;
$id=$_REQUEST["id"];
$type=$_REQUEST['type'];
$page=$_REQUEST["page"];
$action=$_REQUEST["action"];
$league = isset($_REQUEST["league"])?$_REQUEST["league"]:'';
$leaguename = isset($_REQUEST["leaguename"])?$_REQUEST["leaguename"]:'';

require ("../../agents/include/traditional.$langx.inc.php");

if ($type==''){
	$type='FT';
}
if ($page==''){
	$page=0;
}
if ($league!=''){
$n_sql="and M_League like '%$league%'";
}else{
$n_sql='';
}
if ($action=='del'){ // 删除联盟限制
	$sql="DELETE FROM `".DBPREFIX."match_league` WHERE `ID` ='$id'";
	mysqli_query($dbMasterLink,$sql) or die ("操作失败");
	/* 插入系统日志 */
    $loginfo = $loginname.' 在联盟限制中<font class="red"> 删除了 </font> 联赛名称为 <font class="blue">'.$leaguename.'</font>, id 为 <font class="red">'.$id.'</font> ,type 为 <font class="green">'.$type.'</font> 的联盟限制' ;
    innsertSystemLog($loginname,$lv,$loginfo);
}

$sql = "select ID,$m_league as M_League,R,OU,M,EO,VR,VOU,VM,RB,ROU,VRB,VROU,PD,T,F,CS from ".DBPREFIX."match_league where $m_league!='' and Type='$type' $n_sql order by $m_league desc";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=20;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
if ($cou==0){
	$page_count=1;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>

<body  onload="onLoad()";>

    <FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav"><dt>联盟限制</dt>
        <dd>
           联盟单注限制&nbsp;--&nbsp;&nbsp;类别:
          <select class=za_select onchange=document.myFORM.submit(); id="type" name="type">
                <option value="FT">足球联盟</option>
                <option value="BK">篮球联盟</option>
                <option value="BS">棒球联盟</option>
                <option value="TN">网球联盟</option>
                <option value="VB">排球联盟</option>
                <option value="OP">其它联盟</option>
                <option value="FU">指数联盟</option>
                <option value="FS">特殊联盟</option>
          </select>&nbsp;--&nbsp;
          <select id="page" name="page" onChange="self.myFORM.submit()" class="za_select">
                  <?php
                  for($i=0;$i<$page_count;$i++){
                      echo "<option value='$i'>".($i+1)."</option>";
                     }
                  ?>
          </select> / <?php echo $page_count?>  <?php echo $Mem_Page?>&nbsp;--&nbsp;<a href="add_league.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&tpye=<?php echo $type?>">自动接新联盟</a>&nbsp;
            --联盟快速查找:&nbsp;<input name="league" type="text" id="memname" value="<?php echo $league?>" size="15" >&nbsp;&nbsp;&nbsp;
            <input class=za_button type="submit" name="Submit" value="提交">

        </dd>
    </dl>
<div class="main-ui">
  <table id="glist_table" class="m_tab" >
    <tr class="m_title"> 
        <td width="32">序号</td>
        <td width="230">联盟</td>
        <td width="70">让球</td>
        <td width="70">大小球</td>
        <td width="70">半场让球</td>
        <td width="70">半场大小</td>
        <td width="70">滚球让球</td>
        <td width="70">滚球大小球</td>
        <td width="70">上半滚球<br>让球</td>
        <td width="70">上半滚球<br>大小球</td>
        <td width="70">特殊类</td>
        <td width="70">操作</td>
    </tr>
<?php
$i=1;
while ($row = mysqli_fetch_assoc($result)){
?>
    <tr onmouseover=sbar(this) onmouseout=cbar(this)>
        <td ><?php echo $i?></td>
        <td><?php echo $row['M_League']?></td>
        <td><?php echo $row['R']?></td>
        <td><?php echo $row['OU']?></td>
        <td><?php echo $row['VR']?></td>
        <td><?php echo $row['VOU']?></td>
        <td><?php echo $row['RB']?></td>
        <td><?php echo $row['ROU']?></td>
        <td><?php echo $row['VRB']?></td>
        <td><?php echo $row['VROU']?></td>
        <td><?php echo $row['CS']?></td>
        <td align="center">
            <a class="a_link" href="league_edit.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&langx=<?php echo $langx?>&type=<?php echo $type?>&page=<?php echo $page?>&leaguename=<?php echo $row['M_League']?>">变更</a>&nbsp;/&nbsp;&nbsp;
            <a class="a_link" href="league.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&langx=<?php echo $langx?>&type=<?php echo $type?>&action=del&leaguename=<?php echo $row['M_League']?>">删除</a></td>
    </tr>
<?php
$i++;
}
?>
  </table>
</div>
</form>

<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    function onLoad(){
        var type = document.getElementById('type');
        type.value = '<?php echo $type?>';
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }

</script>

</body>
</html>