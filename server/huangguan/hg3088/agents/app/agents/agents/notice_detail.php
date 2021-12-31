<?php
session_start();
require_once ("../../member/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST['uid'];
$mtype=$_REQUEST['mtype'];
$langx=$_REQUEST['langx'];
$sql = "select id,level,agname from web_sytnet where uid='$uid' and status=1";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);

	$row = mysqli_fetch_assoc($result);
	$agname=$row['agname'];
	$agid=$row['ID'];
	$langx='zh-tw';
	require ("../../member/include/traditional.$langx.inc.php");
	$enable=$_REQUEST["enable"];
	$enabled=$_REQUEST["enabled"];
	$sort=$_REQUEST["sort"];
	$orderby=$_REQUEST["orderby"];
	$mid=$_REQUEST["id"];
	$sel_agents=$_REQUEST['super_agents_id'];
	$page=$_REQUEST["page"];


//userlog($memname);


$showtype=$_REQUEST['showtype'];
$uid=$_REQUEST['uid'];
$mtype=$_REQUEST['mtype'];
$langx=$_REQUEST['langx'];
$id = $_GET['id'];

$mr = mysqli_query($dbLink,"select * from ".DBPREFIX."web_notices where id=$id and view_ids like '%{".$agname."}%'");
if(mysqli_num_rows($mr)<1) {
	$mr = mysqli_query($dbLink,"select * from ".DBPREFIX."web_notices where id=$id");
	$mmr = mysqli_fetch_assoc($mr);
	$view_ids = $mmr['view_ids'].'{'.$agname.'}';

	mysqli_query($dbMasterLink,"update ".DBPREFIX."web_notices set view_ids='$view_ids' where id = $id");

}

if($_SERVER['REQUEST_METHOD']== "POST")
{
	if($_POST['dell']=='刪除') {
		mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_notices where id = ".$id);

		$msg =  "<script>alert('刪除成功');</script>";
	}
	if($_POST['reply']=='回複') {
		$mysql="insert into ".DBPREFIX."web_notices_reply(title,content,addtime,reply_uid,role,reply_id) values ('(回複:)".$_POST['noticetitle']."','".$_POST['reply_content']."','".time()."','".$agid."','1',$id)";


		mysqli_query($dbMasterLink,$mysql) or die ("數據庫錯誤!");
		$ro = mysqli_query($dbLink,"select last_insert_id()");
		$ro1= mysqli_fetch_assoc($ro);
		$mysql="update ".DBPREFIX."web_notices set curr_reply='1',reply_id='".$ro1[0]."' where id = ".$id;
		mysqli_query($dbMasterLink,$mysql) or die ("數據庫錯誤!");
		//echo "<script languag='JavaScript'>alert('操作成功');</script>";
		$msg =  "<script>alert('回复成功');</script>";
	}
}





?>


<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/style/Lotto.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body>
<style>
body,TD{font-size:12px;}
</style>
<?php echo $msg?>
<form method="POST" action="notice_detail.php?uid=<?php echo $uid?>&mtype=<?php echo $mtype?>&langx=<?php echo $langx?>&id=<?php echo $_GET['id']?>">
<div style="margin-top: 5px;margin-left: 5px; padding-bottom: 0px;" class="jiyizk">
	      <div class="jiyizk jiyizk-meniu margint0">
		       <div class="jiyizk-meniuleft floatleft"></div>
			   <div class="jiyizk-meniucenter floatleft font-hblue">
			       <ul>
		               
		               <li class="floatleft font-bblack"><a id="mmm" href="notice_detail.php?uid=<?php echo $uid?>&mtype=<?php echo $mtype?>&langx=<?php echo $langx?>">查看信息</a></li>
			          
			           
			     </ul>
		    </div>
			   <div class="jiyizk-meniuright floatlright"></div>
		  </div>          
          <table cellspacing="1" cellpadding="3" border="0" bgcolor="#cccccc" align="center" width="100%" class="floatleft">
            <tbody>
            <?php

            $r = mysqli_query($dbLink,"select * from ".DBPREFIX."web_notices where id = ".$id);

            if(mysqli_num_rows($r)) {
            	$row = mysqli_fetch_assoc($r);
            	if($row['type']==1 or $row['type']==2) {
            ?>
            <tr>
              <td  bgcolor="#FFFFFF">標題</td>
              <td  bgcolor="#FFFFFF"><?php echo $row['title']?></td>
             
            </tr>
            <tr>
             <td  bgcolor="#FFFFFF">內容</td><td height="150" bgcolor="#FFFFFF"><?php echo $row['content']?></td>
            </tr>
             <tr>
             <td  bgcolor="#FFFFFF" colspan="2" align="center"><input name="dell" type="submit" value="刪除" onclick="return confirm('確認刪除？');"/></td>
            </tr>
            <?php

            $rr = mysqli_query($dbLink,"select * from ".DBPREFIX."web_notices where reply_id = ".$row['id']);
            if(mysqli_num_rows($rr)) {
            	$f = mysqli_fetch_assoc($rr);
             	?>
             	<tr><td bgcolor="#FFFFFF">回複內容</td>
             	<td bgcolor="#FFFFFF"><?php echo $f['content']?></td>
             	</tr>
             	<?php
            }


            ?>
            <?php
            	}
            	if($row['type']==3) {
            	?>
             <tr>
              <td  bgcolor="#FFFFFF">標題</td>
              <td  bgcolor="#FFFFFF"><?php echo $row['title']?></td>
             
            </tr>
            <tr>
             <td  bgcolor="#FFFFFF">內容</td><td height="150" bgcolor="#FFFFFF"><?php echo $row['content']?></td>
            </tr>
            <?php
            	}
            }else {?>
            </tr>
            <td height="100" bgcolor="#fffef4" align="center" style="color: Red;" class="blank" colspan="2">暫無信息</td>
            </tr>
            <?php
            }
            ?>
            
            
          </tbody></table>
  </div>
  <?php
  if($row['type']==3) {
  	$rr = mysqli_query($dbLink,"select * from ".DBPREFIX."web_notices_reply where reply_id = ".$row['id']." order by addtime asc");
  	if(mysqli_num_rows($rr)) {
  		echo "<div style='border:1px solid #929292;clear:both;margin-left:5px;margin-top:20px; width:760px;'><div style='border:2px solid #000000;width:760px;padding:0 10px'><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"   style='width:740px'>";
  		while($f = mysqli_fetch_assoc($rr)){
          	?>
          	 <tr><td><br><?php if($f['role']==1) echo "<span style='color:red'>管理員</span>";else {
          	 	$cr = mysqli_query($dbLink,"select * from web_member where ID = ".$row['addpople']);

          	 	if(mysqli_num_rows($cr)) {
          	 		$ccr = mysqli_fetch_assoc($cr);
          	 		echo "<span style='color:#95BA00'>".$ccr['Alias']."(".$ccr['Memname'].")"."</span>";
          	 	}
          	 }?>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#cccccc"><?php echo date('Y-m-d H:i:s',$f['addtime'])?></span></td></tr>

          	 <tr><td style="padding-left:20px;color:#417DA8;padding-bottom:15px;margin-bottom:20px;border-bottom:1px dashed #CCCCCC">內容:<?php echo $f['content']?></td></tr>
          	 
          	<?php

  		}
  		echo "</table></div></div>";
  	}
          ?>
          <div style='border:1px solid #929292;clear:both;margin-left:5px;margin-top:20px; width:760px;'><div style='border:2px solid #000000;width:760px;padding:0 10px'>
          <table cellspacing="0" cellpadding="0" border="0"   style='width:740px'>
          <tr><td style="border-bottom:1px solid #999999;font-size:16px;"><B>回複信息</B></td></tr>
          <tr><td><textarea name="reply_content" id="reply_content" cols="100"  rows="6"></textarea></td></tr>
          <tr><td align="center"> <input name="reply" type="submit" value="回複" onclick="if(document.getElementById('reply_content').val=='') {alert('請填寫回複內容');return false;}else return true;"/>&nbsp;&nbsp;<input name="dell" type="submit" value="返回" onclick="history.go(-1);return false;"/><input name="noticetitle" type="hidden" value="<?php echo $row['title']?>"/></td></tr>
         
          </div></div>
          <?php
  			}
  			?>
  </form>
</body>
</html>
