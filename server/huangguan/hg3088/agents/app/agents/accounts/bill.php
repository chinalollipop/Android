<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
ini_set('display_errors','Off');

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

// 管理员登录
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$username=isset($_REQUEST['username'])?trim($_REQUEST['username']):'';
$sdate=isset($_REQUEST['sdate'])?$_REQUEST['sdate']:'';
$edate=isset($_REQUEST['edate'])?$_REQUEST['edate']:'';

$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");
//$level M 管理员
//$level D 代理

if($sdate==''){
	$dateTodayStart=strtotime(date('Y-m-d 00:00:00'));
}else{
	$dateTodayStart=strtotime($sdate);
}
if($edate==''){
	$dateTodayEnd=strtotime(date('Y-m-d 23:59:59'));
}else{
	$dateTodayEnd=strtotime($edate)+86340;
}

$typeSearch='';
$sourceSearch='';
$adminSearch='';

$search['type']=array(-20,-10,1,2,3,4,5,11,12,13,21,22,23,24,25,26,27,28,29,34,35,36,37,38,39,40,41,42,43,44,45,48,49,50,51,52,53,54,55,56,57,58,59,60);
$search['source']=array(-1,-3,1,2,3,4,5,6,7,8,9,22);// '0未知,1pc旧版,2pc新版,3苹果,4安卓,22 综合版'
if(strlen($username)>0){
	$where= "where addTime > '".$dateTodayStart."'";
	$where.= " and addTime <= '".$dateTodayEnd."'";
	$where.=" and userName='{$username}'";
	
	if( isset($_REQUEST['type']) && in_array($_REQUEST['type'],$search['type']) ){
		$typeSearch=$_REQUEST['type'];
		if($typeSearch==-10){ // 存提提款
			$where.=" and type in (11,12)";
		}elseif($typeSearch==-20){ // 额度转换
			$where.=" and type in (21,22,23,24,26,27,28,29,34,35,36,37,38,39,40,41,42,43,44,45,48,49,50,51,52,53,54,55,56,57,58,59,60)";
		}else{
			$where.=" and type={$typeSearch}";	
		}
	}
	
	if( isset($_REQUEST['source']) && in_array($_REQUEST['source'],$search['source']) ){
		$sourceSearch=$_REQUEST['source'];
		if($typeSearch==-1){ // 旧版新版PC,综合版
			$where.=" and source in (1,2,22)";
		}elseif($typeSearch==-3){
			$where.=" and source in (3,4)";
		}else{
			$where.=" and source={$sourceSearch}";	
		}
	}
	
	if( isset($_REQUEST['admin']) && strlen($_REQUEST['admin'])>0 ){
		$adminSearch=$_REQUEST['admin'];
		$where.=" and description like '%$adminSearch%'";	
	}
    
	$sql="select ID,userid,userName,istest,currencyBefore,money,currencyAfter,`type`,source,`addTime`,orderid,description from ".DBPREFIX."web_account_change_records ".$where." order by ID desc";
	$result=mysqli_query($dbLink,$sql);
}else{
	$result=array();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>IP查询</title>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
input{min-height: auto;}
input.za_text_auto {width: 110px;}
</style>
</head>
<!--meta http-equiv="refresh" content="30; url=online.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>$lv=M"-->
<body onLoad="onLoad()";>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>会员账单</dt>
        <dd>
        <table >
          <tr class="m_tline" >
            <td>
            	账 号<input type="text" class="za_text_auto" name="username" value="<?php echo $username;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	操作时间
                    <input type="text" class="za_text_auto" name="sdate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                    <input type="text" class="za_text_auto" name="edate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				类型<SELECT name=type>
						<option value=0 <?php if($typeSearch==0) echo "selected";?> >全部</option>
						<option value=1 <?php if($typeSearch==1) echo "selected";?> >注单</option>
						<option value=2 <?php if($typeSearch==2) echo "selected";?> >取消订单</option>
						<option value=3 <?php if($typeSearch==3) echo "selected";?> >派彩</option>
						<option value=4 <?php if($typeSearch==4) echo "selected";?> >返水</option>
						<option value=56 <?php if($typeSearch==56) echo "selected";?> >时时返水</option>
						<option value=5 <?php if($typeSearch==5) echo "selected";?> >注单确认(订单恢复)</option>
						<option value=-10 <?php if($typeSearch==-10) echo "selected";?> >存提提款</option>
						<option value=11 <?php if($typeSearch==11) echo "selected";?> >存款</option>
						<option value=12 <?php if($typeSearch==12) echo "selected";?> >提款</option>
                        <option value=13 <?php if($typeSearch==13) echo "selected";?> >彩金</option>
						<option value=-20 <?php if($typeSearch==-20) echo "selected";?> >额度转换</option>
						<option value=21 <?php if($typeSearch==21) echo "selected";?> >额度转换体育到彩票</option>
						<option value=22 <?php if($typeSearch==22) echo "selected";?> >额度转换彩票到体育</option>
						<option value=23 <?php if($typeSearch==23) echo "selected";?> >额度转换体育到真人电子</option>
						<option value=24 <?php if($typeSearch==24) echo "selected";?> >额度转换真人电子到体育</option>
						<option value=24 <?php if($typeSearch==25) echo "selected";?> >额度修正
                        <option value=26 <?php if($typeSearch==26) echo "selected";?> >额度转换体育到开元棋牌</option>
                        <option value=27 <?php if($typeSearch==27) echo "selected";?> >额度转换开元棋牌到体育</option>
                        <option value=34 <?php if($typeSearch==34) echo "selected";?> >额度转换体育到乐游棋牌</option>
                        <option value=35 <?php if($typeSearch==35) echo "selected";?> >额度转换乐游到体育棋牌</option>
                        <!--<option value=28 <?php /*if($typeSearch==28) echo "selected";*/?> >额度转换体育到皇冠棋牌</option>-->
                        <option value=29 <?php if($typeSearch==29) echo "selected";?> >额度转换皇冠棋牌到体育</option>
                        <option value=30 <?php if($typeSearch==30) echo "selected";?> >额度转换体育到VG棋牌</option>
                        <option value=31 <?php if($typeSearch==31) echo "selected";?> >额度转换VG棋牌到体育</option>
                        <option value=36 <?php if($typeSearch==36) echo "selected";?> >额度转换体育到MG电子</option>
                        <option value=37 <?php if($typeSearch==37) echo "selected";?> >额度转换MG电子到体育</option>
                        <option value=38 <?php if($typeSearch==38) echo "selected";?> >额度转换体育到三方彩票</option>
                        <option value=39 <?php if($typeSearch==39) echo "selected";?> >额度转换三方彩票到体育</option>
                        <option value=40 <?php if($typeSearch==40) echo "selected";?> >额度转换体育到OG</option>
                        <option value=41 <?php if($typeSearch==41) echo "selected";?> >额度转换OG到体育</option>
                        <option value=42 <?php if($typeSearch==42) echo "selected";?> >额度转换体育到CQ9电子</option>
                        <option value=43 <?php if($typeSearch==43) echo "selected";?> >额度转换CQ9电子到体育</option>
                        <option value=44 <?php if($typeSearch==44) echo "selected";?> >额度转换体育到MW</option>
                        <option value=45 <?php if($typeSearch==45) echo "selected";?> >额度转换MW到体育</option>
                       <!-- <option value=46 <?php /*if($typeSearch==46) echo "selected";*/?> >额度转换平台到皇冠体育</option>
                        <option value=47 <?php /*if($typeSearch==47) echo "selected";*/?> >额度转换皇冠体育到平台</option>-->
                        <option value=48 <?php if($typeSearch==48) echo "selected";?> >额度转换体育到FG</option>
                        <option value=49 <?php if($typeSearch==49) echo "selected";?> >额度转换FG到体育</option>
                        <option value=50 <?php if($typeSearch==50) echo "selected";?> >额度转换体育到泛亚电竞</option>
                        <option value=51 <?php if($typeSearch==51) echo "selected";?> >额度转换泛亚电竞到体育</option>
                        <option value=52 <?php if($typeSearch==52) echo "selected";?> >额度转换体育到BBIN</option>
                        <option value=53 <?php if($typeSearch==53) echo "selected";?> >额度转换BBIN到体育</option>
                        <!--DS 54  55-->
                        <option value=57 <?php if($typeSearch==57) echo "selected";?> >额度转换体育到快乐棋牌</option>
                        <option value=58 <?php if($typeSearch==58) echo "selected";?> >额度转换快乐棋牌到体育</option>
                        <option value=59 <?php if($typeSearch==59) echo "selected";?> >额度转换体育到雷火电竞</option>
                        <option value=60 <?php if($typeSearch==60) echo "selected";?> >额度转换雷火电竞到体育</option>
					</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				来源<SELECT name=source>
						<option value=0 <?php if($sourceSearch==0) echo "selected";?> >全部</option>
						<option value=1 <?php if($sourceSearch==1) echo "selected";?> >旧版PC</option>
						<option value=2 <?php if($sourceSearch==2) echo "selected";?> >新版PC</option>
						<option value=22 <?php if($sourceSearch==22) echo "selected";?> >综合版</option>
						<option value=-1 <?php if($sourceSearch==-1) echo "selected";?> >旧版/新版PC/综合版</option>
						<option value=3 <?php if($sourceSearch==3) echo "selected";?> >IOS移动端</option>
						<option value=13 <?php if($sourceSearch==13) echo "selected";?> >原生IOS</option>
						<option value=4 <?php if($sourceSearch==4) echo "selected";?>  >Android移动端</option>
						<option value=14 <?php if($sourceSearch==14) echo "selected";?>  >原生Android</option>
						<option value=-3 <?php if($sourceSearch==-3) echo "selected";?>  >移动端</option>
						<option value=6 <?php if($sourceSearch==6) echo "selected";?>  >管理后台</option>
						<option value=7 <?php if($sourceSearch==7) echo "selected";?>  >PC回调上分</option>
						<option value=8 <?php if($sourceSearch==8) echo "selected";?>  >计划任务</option>
						<option value=9 <?php if($sourceSearch==9) echo "selected";?>  >WEB脚本</option>
                        <option value=23 <?php if($sourceSearch==23) echo "selected";?> >API调用</option>
					</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				管理员<input type="text" class="za_text_auto" name="admin" value="<?php echo $adminSearch;?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="za_button" type="submit" name="Submit" value="查询">
            </td>
          </tr>
        </table>
   </dd>
 </dl>
<div class="main-ui">
	<table class="m_tab">
          <tr  class="m_title"> 
            <td width="34">ID</td>
            <td width="">账 号</td>
            <td width="">操作前金额</td>
            <td width="">操作金额</td>
            <td width="">操作后金额</td>
            <td width="">类型</td>
            <td width="">账号类型</td>
            <td width="">操作ID/流水号</td>
            <td width="">来源</td>
            <td width="">操作时间</td>
            <td width="">描述</td>
          </tr>
		<?php while($mrow=mysqli_fetch_assoc($result)){?>
          <tr class="m_cen" onMouseOut="this.style.backgroundColor=''" onMouseOver="this.style.backgroundColor='#BFDFFF'" bgcolor="#FFFFFF"> 
            <td><?php echo $mrow["ID"] ?></td> 
            <td ><?php echo $mrow["userName"] ?></td>
            <td ><font color="blue"><?php echo $mrow["currencyBefore"] ?></font></td>
            <td ><font color="red"><?php echo $mrow["money"] ?></font></td>
            <td ><font color="green"><?php echo $mrow["currencyAfter"] ?></font></td>
            <td >
            	<?php
            	switch ($mrow["type"]){
	            		case 1:  echo "注单";break;
						case 2:  echo "取消订单";break;
	            		case 3:  echo "派彩";break;
	            		case 4:  echo "返水";break;
	            		case 5:  echo "注单确认(订单恢复)";break;
	            		case 11: echo "存款";break;
						case 12: echo "提款";break;
                        case 13: echo "彩金";break;
						case 21: echo "额度转换体育到彩票";break;
						case 22: echo "额度转换彩票到体育";break;
						case 23: echo "额度转换体育到真人电子";break;
						case 24: echo "额度转换真人电子到体育";break;
						case 25: echo "额度修正";break;
						case 26: echo "额度转换体育到开元棋牌";break;
						case 27: echo "额度转换开元棋牌到体育";break;
//						case 28: echo "额度转换体育到皇冠棋牌";break;
//						case 29: echo "额度转换皇冠棋牌到体育";break;
						case 30: echo "额度转换体育到VG棋牌";break;
						case 31: echo "额度转换VG棋牌到体育";break;
						case 34: echo "额度转换体育到乐游棋牌";break;
						case 35: echo "额度转换乐游棋牌到体育";break;
                        case 36: echo "额度转换体育到MG电子";break;
                        case 37: echo "额度转换MG电子到体育";break;
                        case 38: echo "额度转换体育到三方彩票";break;
                        case 39: echo "额度转换三方彩票到体育";break;
                        case 40: echo "额度转换体育到OG";break;
                        case 41: echo "额度转换OG到体育";break;
                        case 42: echo "额度转换体育到CQ9电子";break;
                        case 43: echo "额度转换CQ9电子到体育";break;
                        case 44: echo "额度转换体育到MW";break;
                        case 45: echo "额度转换MW到体育";break;
//                        case 46: echo "额度转换平台到皇冠体育";break;
//                        case 47: echo "额度转换皇冠体育到平台";break;
                        case 48: echo "额度转换体育到FG";break;
                        case 49: echo "额度转换FG到体育";break;
                        case 50: echo "额度转换体育到泛亚电竞";break;
                        case 51: echo "额度转换泛亚电竞到体育";break;
                        case 52: echo "额度转换体育到BBIN";break;
                        case 53: echo "额度转换BBIN到体育";break;
                        case 56: echo "时时返水";break;
                        case 57: echo "额度转换体育到快乐棋牌";break;
                        case 58: echo "额度转换快乐棋牌到体育";break;
                        case 59: echo "额度转换体育到雷火电竞";break;
                        case 60: echo "额度转换雷火电竞到体育";break;
						default: echo "未知";
            		}
             	?>
            </td>
            <td ><?php if($mrow["istest"]==0){echo '正式';}elseif($mrow["istest"]==1){echo '测试';} ?></td>
            <td ><?php echo $mrow["orderid"]?></td>
            <td >
            	<?php
            		switch ($mrow["source"]){
	            		case 1:  echo "旧版PC";break;
						case 2:  echo "新版PC";break;
						case 22:  echo "综合版";break;
						case 3:  echo "IOS移动端";break;
						case 4:  echo "Android移动端";break;
						case 13:  echo "原生IOS端";break;
						case 14:  echo "原生Android端";break;
						case 6:  echo "管理后台";break;
						case 7:  echo "PC回调上分";break;
						case 8:  echo "计划任务";break;
						case 9:  echo "WEB脚本";break;
						default: echo "未知";
            		}
            	?>
            </td>
            <td ><?php echo date('Y-m-d H:i:s',$mrow["addTime"])?></td>
            <td class="miao_s"><?php echo $mrow["description"]?></td>
          </tr>
		<?php }?>
	</table>
</div>

</form>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script>
    
</script>
</body>
</html>