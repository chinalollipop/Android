<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 统计存取款金额（审核通过的存取记录&不包括优惠和返水）
if(isset($_REQUEST['user_count']) && !empty($_REQUEST['user_count'])){
    $username = trim($_REQUEST['user_count']); // 统计用户
    $stmt = $dbLink->prepare('SELECT SUM(`moneyf`) AS `total_before`, SUM(`currency_after`) AS `total_after`, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE `UserName` = ? 
            AND `Checked` = 1 AND `Type` IN ("S", "T") AND `discounType` NOT IN (3,4) GROUP BY `Type`');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($record = $result->fetch_assoc()){
        if(isset($record['Type']))
            $data[$record['Type']] = sprintf('%.2f', abs($record['total_after'] - $record['total_before']));
    }
    exit(json_encode($data));
}

//$admin_name=$_SESSION['UserName'];
//$c_sql = "select Competence from ".DBPREFIX."web_system_data where UserName='$admin_name'";
//$c_result = mysqli_query($dbLink,$c_sql);
//$c_row = mysqli_fetch_assoc($c_result);
//$competence = $c_row['Competence']; // 权限控制
//$c_num = explode(",",$competence);

$uid=$_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName']; // 当前登录帐号
$url_name = $_REQUEST['urlname'] ; // 链接带过来的用户名
$lv=$_REQUEST["lv"];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级

require ("../../agents/include/traditional.$langx.inc.php");

/*$sql = "select website,Admin_Url from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$admin_url=explode(";",$row['Admin_Url']);*/
if($url_name){ // 如果有代理商等帐号，查看当前代理商下面会员
    $web=DBPREFIX.'web_agents_data';
}else{
    /*if (in_array($_SERVER['HTTP_HOST'],array($admin_url[0],$admin_url[1],$admin_url[2],$admin_url[3]))){*/
    if($_SESSION['Level'] == 'M') {
        $web=DBPREFIX.'web_system_data';
    }else{
        $web=DBPREFIX.'web_agents_data';
    }
}

$sql_term = " Oid='$uid' and UserName='$loginname' " ;
if($url_name){ // 如果有代理商等帐号，查看当前代理商下面会员
    $sql_term = " UserName='$url_name' " ;
}
$sql = "select ID,Level,UserName,SubName from $web where $sql_term";
// echo $sql;
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$name=$row['UserName'];
$p_level=$row['Level'];
$subUser=$_SESSION['SubUser'];
if ($subUser==0 || $url_name){ // 主账号 , 代理账号也是 0
	$name=$row['UserName'];
}else{ // 子帐号
	$name=$row['SubName'];
}


$competence=$_SESSION['Competence'];
$competence_num=explode(",",$competence);

 //print_r($competence_num) ;

$class='#fefbee';
$bgcolor='E3D46E';
$admin ='admin' ;
switch ($lv){
case 'A': // 公司
    $Title=$Mem_Super;
	$Caption=$Mem_Manager;
	$level='M';
	$lower='B';
	$user='Admin';
	$check="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and";
	$agents="Admin='$admin' and Level='A' and subuser=0 ";
	$data=DBPREFIX.'web_agents_data';
	break;
case 'B': // 股东
    $Title=$Mem_Corprator;
	$Caption=$Mem_Super;
	$level='A';
	$lower='C';
	$user='Super';
	$check="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and";
	$agents="(Admin='$admin' or Super='$name') and Level='B' and subuser=0 ";
	$data=DBPREFIX.'web_agents_data';
	break;
case 'C': // 总代
    $Title=$Mem_World;
	$Caption=$Mem_Corprator;
	$level='B';
	$lower='D';
	$user='Corprator';
	$check="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and";
	$agents="(Admin='$admin' or Super='$name' or Corprator='$name') and Level='C' and subuser=0 ";
	$data=DBPREFIX.'web_agents_data';
	break;
case 'D': // 普通代理
    $Title=$Mem_Agents;
	$Caption=$Mem_World;
	$level='C';
	$lower='MEM';
	$user='World';
	$check="(UserName='$name' or Admin='$admin' or Super='$name' or Corprator='$name' or World='$name') and";
    $agents="(Admin='$admin' or Super='$name' or Corprator='$name' or World='$name' or Agents='$name') and Level='D' and subuser=0 ";
	$data=DBPREFIX.'web_agents_data';
	break;
case 'MEM': // 会员
    $Title=$Mem_Member;
    $Caption=$Mem_Agents;
    $level='D';
    $user='Agents';
    if($userlv=='M'){ // 超级管理员
        $check="(UserName='$name' or Admin='$admin' or Super='$name' or Corprator='$name' or World='$name') and";
        if($url_name){
            $agents="(Agents='$name')";
        }else{
            $agents="(Admin='$admin' or Super='$name' or Corprator='$name' or World='$name' or Agents='$name')";
        }
        $data=DBPREFIX.MEMBERTABLE;
    }else{
        $check="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and";
        $agents="(Admin='$name' or Super='$name' or Corprator='$name' or World='$name' or Agents='$name')";
        $data=DBPREFIX.MEMBERTABLE;
    }

	break;
}

$loginfo= $loginname.'查看'.$Title.'';
$enable=$_REQUEST["enable"];
$disable=$_REQUEST["disable"];
$suspend=$_REQUEST["suspend"];
$logout=$_REQUEST["logout"];
$sort=$_REQUEST["sort"];
$layerCondition=$_REQUEST['layer'];
$userlevelCondition=$_REQUEST['userlevel'];
$active=$_REQUEST["active"];
$orderby=isset($_REQUEST["orderby"])?$_REQUEST["orderby"]:'DESC';
$active_id=$_REQUEST["active_id"];
$username=$_REQUEST["name"];
$page=$_REQUEST["page"];
$search= str_replace(' ','',$_REQUEST["search"]); // 搜索帐号,去除空格
$search_name= $search ; // 搜索帐号
$haschinese = isTrueName($search);  // 是否输入有中文

if ($enable==""){
	$enable='ALL';
}

if ($sort==""){
	$sort='ADDDATE';
}else{

    switch ($sort){
        case 'WinLossCreditBigger':
            $WinLossCreditBiggerOrSamllerMoney= $_REQUEST['WinLossCreditBiggerOrSamllerMoney'];
            $sort='WinLossCredit';
            $sortType='dayu';
            $money = " and `WinLossCredit`>= $WinLossCreditBiggerOrSamllerMoney";
            break;
        case 'WinLossCreditSmaller':
            $WinLossCreditBiggerOrSamllerMoney= $_REQUEST['WinLossCreditBiggerOrSamllerMoney'];
            $sort='WinLossCredit';
            $sortType='xiaoyu';
            $money = " and `WinLossCredit` < $WinLossCreditBiggerOrSamllerMoney";
            break;
        default :
            break;
    }

}

if ($layerCondition!='' and $lv=='MEM'){
    $layer = " and `layer`=".$layerCondition;
}

if ($userlevelCondition!='' and $lv=='MEM'){
    $userlevel = " and `pay_class`='".$userlevelCondition."'";
}

if ($orderby==""){
	$orderby='DESC';
}

if ($page==''){
	$page=0;
}
if ($search!=''){
    if($haschinese){ // 有中文
        $search="and (UserName LIKE binary '%$search%' or LoginName LIKE binary '%$search%' or AddDate LIKE binary '%$search%' or Alias LIKE binary '%$search%')";
    }else{
        //$search="and (UserName LIKE '%$search%' or LoginName LIKE '%$search%' or AddDate LIKE '%$search%' or Alias LIKE '%$search%' or agent_url LIKE '%$search%')";
        $search="and (UserName = '$search' or LoginName = '$search' or AddDate = '$search' or Alias = '$search' or agent_url = '$search')";
    }
	$num=512;
}else{
	$search="";
	$num=50;
}
$status ='';
if ($enable=="Y"){
	$status="and Status='0'";
}else if ($enable=="S"){
	$status="and Status='1' and isAutoFreeze=0";
}else if ($enable=="N"){
	$status="and Status='2'";
}else if ($enable=="isAutoFreeze"){
	$status="and Status='1' and isAutoFreeze=1";
}else if ($enable=="online"){
	$status="and Online='1'";
}
$agdata="(Super='$username' or Corprator='$username' or World='$username')";
$memdata="(super='$username' or Corprator='$username' or World='$username' or Agents='$username')";

if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG) {
	switch ($active){
		case "Y":
			$loginfo= $loginname.'开通'.$Title.':<font class="green">'.$username.'</font>';
			$mysql="update ".DBPREFIX."web_agents_data set EditType='1' where id=$active_id";
			mysqli_query($dbMasterLink,$mysql);	
			break;
		case "N":
			$loginfo= $loginname.'关闭'.$Title.':<font class="green">'.$username.'</font>';
			$mysql="update ".DBPREFIX."web_agents_data set EditType='0' where id=$active_id";
			mysqli_query($dbMasterLink,$mysql);	
			break;
        case "enable": // 启用
		case "disable": // 停用 2021/02/24 增加踢线功能
        case "suspend": // 冻结 2021/02/24 增加踢线功能
            if($active == 'enable') {  // 启用
                $set_status = 0;
                $loginfo= $loginname.'启用'.$Title.':<font class="green">'.$username.'</font>';
            }elseif($active == 'suspend') {   // 冻结
                $set_status = 1;
                $loginfo= $loginname.'冻结'.$Title.':<font class="green">'.$username.'</font>';
            }elseif($active == 'disable') {  // 停用
                $set_status = 2;
                $loginfo = $loginname . '停用' . $Title . ':<font class="green">' . $username . '</font>';
            }


            if ($lv=='D') { // 代理商页面的停用，才停用相同代理下的用户名
                $mysql="update ".DBPREFIX."web_agents_data set Oid='logout',Online=0,Status=$set_status,LogoutTime=now() where id=$active_id and UserName='$username'";
                mysqli_query($dbMasterLink,$mysql);
//                $mysql="update ".DBPREFIX."web_agents_data set Oid='logout',Online=0,Status=$set_status,LogoutTime=now() where $agdata";
//                mysqli_query($dbMasterLink,$mysql);
                $mysql = "update " . DBPREFIX . MEMBERTABLE . " set Oid='logout',Online=0,Status=$set_status,LogoutTime=now() where $memdata";
                mysqli_query($dbMasterLink, $mysql);
            }else{ // 操作会员
                if($active=='enable'){ // 启用
                    $mysql="update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Online=0,Status=$set_status,LogoutTime=now(),loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where id=$active_id and UserName='$username'";
                }else{ // 停用和冻结
                    $mysql="update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Online=0,Status=$set_status,LogoutTime=now() where id=$active_id and UserName='$username'";
                }

                mysqli_query($dbMasterLink, $mysql);
            }
            // 清除会员redis，便于后续判断会员登录标识
            delMemberLog($active_id);
			break;
        case "unautofreeze":  // 一键解冻所有自动冻结账号
            $loginfo= $loginname.'一键解冻所有自动冻结'.$Title;
            $redisObj = new Ciredis();
            $attTime = $redisObj->getSimpleOne($loginname.'_unautofreeze');
            if($attTime) {
                $allowtime = time()-$attTime;
                if($allowtime<2*60) {
                    echo "<script languag='JavaScript'>alert('请不要频繁操作,2分钟后执行!!');history.go( -1 );</script>";
                    exit();
                }
            }
            // 插入当前申请时间，存入redis, 确保不允许重复申请
            $redisObj->insert($loginname.'_unautofreeze', time(), 2*60);

            $mysql="update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Status=0,LogoutTime=now(),loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where isAutoFreeze=1";
            mysqli_query($dbMasterLink, $mysql);
            break;
        case "logout": // 踢线
			$loginfo= $loginname.'踢线'.$Title.':<font class="green">'.$username.'</font>';
			$mysql="update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Online=0,LogoutTime=now() where id=$active_id";
	        mysqli_query($dbMasterLink, $mysql);
	        // 清除会员redis，便于后续判断会员登录标识
            delMemberLog($active_id);
			break;
		case "del":
			$mysql="SELECT ".DBPREFIX."web_report_data.m_name from ".DBPREFIX."web_report_data,".DBPREFIX.MEMBERTABLE." WHERE (".DBPREFIX.MEMBERTABLE.".UserName=".DBPREFIX."web_report_data.m_name) and ".DBPREFIX.MEMBERTABLE.".ID='$active_id'";
			$result=mysqli_query($dbLink,$mysql);
			$cou=mysqli_num_rows($result);
			if ($cou>0){
				echo wterror("此会员已有投注纪录，无法进行删除！！");
				exit();
			}else{
				$loginfo= $loginname.'删除'.$Title.':<font class="green">'.$username.'</font>';
				$sql="delete from $data where ID='$active_id'";
				mysqli_query($dbMasterLink,$sql);
				$mysql="update ".DBPREFIX."web_agents_data set Count=Count-1 where UserName='".$_REQUEST['aguser']."'";
				mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
			}
			break;	
		case "ForbidBK":
        case "ForbidBKH1":
		case "ForbidBKQ3":
		case "ForbidDJFT":
		case "ForbidDJBK":
            $game=$_REQUEST['game'];
			$userGameSql = "select gameSwitch from ".DBPREFIX.MEMBERTABLE." where ID={$_REQUEST['name']}";
			$agresult = mysqli_query($dbLink,$userGameSql);
		    $userGameRow = mysqli_fetch_assoc($agresult);
			if($_REQUEST['type']=='close'){
				$loginfo= $loginname.'关闭用户'.':<font class="green">'.$username.'</font>'.$game.'投注权限';
			}elseif($_REQUEST['type']=='open'){
				$loginfo= $loginname.'开启用户'.':<font class="green">'.$username.'</font>'.$game.'投注权限';
			}
			
			if($userGameRow['gameSwitch']==''){
				$userGameArr = array();	
			}else{
				if(strpos($userGameRow['gameSwitch'],'|')){
					$userGameArr = explode('|',$userGameRow['gameSwitch']);
				}else{
					$userGameArr[]=$userGameRow['gameSwitch'];
				}
			}
			if($_REQUEST['type']=='close'){
				array_push($userGameArr,$game);
			}elseif($_REQUEST['type']=='open'){
				 $key = array_search($game,$userGameArr);
			    if(isset($userGameArr[$key])){  
			        unset($userGameArr[$key]);  
			    } 
			}
//			print_r($userGameArr);
//			die;
			$userGameStr=implode('|',$userGameArr);
			$Forbidsql="update ".DBPREFIX.MEMBERTABLE." set gameSwitch='".$userGameStr."' where ID={$_REQUEST[name]}";
			mysqli_query($dbMasterLink,$Forbidsql) or die ("操作失败!");
	}
}

$parents_id=$_REQUEST['parents_id'];
if ($parents_id==''){
	$sql = "select * from $data where $agents $status $money $search $layer $userlevel order by ".$sort." ".$orderby;
}else{
	$sql = "select * from $data where $agents $status and $user='$parents_id'  order by ".$sort." ".$orderby;
	$loginfo= $loginname.'查看'.$Caption.''.$parents_id.'的下线';
}
//echo $sql;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*50;
$mysql=$sql."  limit $offset,$num;";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);

if ($cou==0){
	$page_count=1;
}

// 更新代理线URL
$agent_url_chg = isset($_REQUEST['agent_url_chg'])?$_REQUEST['agent_url_chg']:'' ;
if( $agent_url_chg && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ){ // 代理线操作
    $agents_name = $_REQUEST['agents_name'] ;
    $agent_url = $_REQUEST['agents_url'] ;
    $agsql = "select World,Corprator,Super,Admin,agent_url from ".DBPREFIX."web_agents_data where UserName='$agents_name'";
    $agresult = mysqli_query($dbLink,$agsql);
    $agrow = mysqli_fetch_assoc($agresult);
    $agworld=$agrow['World'];
    $agcorprator=$agrow['Corprator'];
    $agsuper=$agrow['Super'];
    $agadmin=$agrow['Admin'];
    $agcmsql="update ".DBPREFIX."web_agents_data set agent_url='$agent_url' where UserName='$agents_name'";
    mysqli_query($dbMasterLink,$agcmsql);
    $memsql="update ".DBPREFIX.MEMBERTABLE." set agent_url='$agent_url',World='$agworld',Corprator='$agcorprator',Super='$agsuper',Admin='$agadmin' where Agents='$agents_name'";
    mysqli_query($dbMasterLink,$memsql);
    $rsql="update ".DBPREFIX."web_report_data set agent_url='$agent_url',World='$agworld',Corprator='$agcorprator',Super='$agsuper',Admin='$agadmin',updateTime='".date('Y-m-d H:i:s',time())."' where Agents='$agents_name'";
    mysqli_query($dbMasterLink,$rsql);
    echo "<script Language=javascript>alert('修改成功!');window.location.href='user_browse.php?uid=".$uid."&langx=".$langx."&lv=".$lv."&userlv=".$userlv."';</script>";
}

// 注册来源处理 ,注册端来源,0代表PC端,1代表移动端,2代表后台添加,5广告站,13苹果,14安卓
function returnRegSource($source){
    switch ($source){
        case '0';
            $str = 'PC端';
        break;
        case '1';
            $str = '手机端';
            break;
        case '2';
            $str = '后台添加';
            break;
        case '5';
            $str = '广告站';
            break;
        case '13';
            $str = 'IOS_APP';
            break;
        case '14';
            $str = 'ANDROID_APP';
            break;
        case '22':
            $str = '综合版';
            break;
    }
    return $str ;
}

// 清除会员redis，便于后续判断会员登录标识
function delMemberLog($userid){
    $redisObj = new Ciredis();
    $iOId = $redisObj->getSimpleOne('loginuser_'.$userid);
    if($iOId){
        $redisObj->delete('loginuser_'.$userid);
    }
}

if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])){
    $logincp = 'sport'; // 体育彩票
}else{
    $logincp = 'new'; // 新彩票
}

switch ($sortType){
    case 'dayu':
        $sort='WinLossCreditBigger';
        break;
    case 'xiaoyu':
        $sort='WinLossCreditSmaller';
        break;
}

$sql_layer = "SELECT `id`,`title`,`remark`,`status`,`updated_at` FROM " . DBPREFIX . "web_member_data_layer ";
$result_layer = mysqli_query($dbLink, $sql_layer);
$lists = array();
while ($row_layer = mysqli_fetch_assoc($result_layer)){
    $lists[$row_layer['id']] = $row_layer;
}

?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    .width_1300{width: 100%}
    .m_title_ag {  background-color: <?php echo $class?>; text-align: center; height:25px}
    .self_account,.member_name{width:100px;}
    .daili_table{width:1000px;}
    input.text_time{ width: 85px;}
    .show_money_details {
        display: none;
        background: #ff9900;
        text-align: left;
        padding: 0px 0px 10px 10px;
        position: absolute;
        margin-top: 5px;
        width: 170px;
    }
    .show_money_details:after {
        content: '';
        width: 0;
        height: 0;
        border-bottom: 7px solid #ff9900;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        margin-top: -37px;
        left: 23px;
        position: absolute;
    }
    .close_details {
        float: right;
        display: inline-block;
        width: 13px;
        height: 13px;
        line-height: 10px;
        text-align: center;
        background: #fff;
        margin-right: 5px;
        margin-top: 5px;
        font-family: 微软雅黑;
    }
    .za_button {display: inline-block;line-height: 25px;min-width: 62px;}
    input.za_text{width: 120px;}
</style>

</head>
<body  onLoad="onLoad()"; >
<form name="myFORM" action="user_browse.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&urlname=<?php echo $url_name;?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv;?>" method=POST>

<dl class="main-nav">
    <dt><?php echo $Title?><?php echo $Mem_Manager?></dt>
    <dd>
        <table>
          <tr class="m_tline">
              <td style="padding-left: 10px;">
                  <select name="enable" id="enable" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                      <option label="<?php echo $Mem_All?>" value="ALL" selected="selected"><?php echo $Mem_All?></option>
                      <option label="<?php echo $Mem_Enable?>" value="Y"><?php echo $Mem_Enable?></option>
                      <option label="<?php echo $Mem_Suspend?>" value="S"><?php echo $Mem_Suspend?></option>
                      <option label="<?php echo $Mem_Disable?>" value="N"><?php echo $Mem_Disable?></option>
                      <option label="自动冻结" value="isAutoFreeze">自动冻结</option>
                      <option label="在线" value="online">在线</option>
                  </select>
              </td>
                    <td style="padding-left: 10px; display: <?php echo $lv!='MEM'?'none':'block' ;?>">
                        分层:
                      <select name="layer" id="layer" class="za_select za_select_auto">
                          <?php
                          if ($layerCondition==''){
                              echo '<option label="全部" value="" selected="selected">全部</option>';
                          }
                              echo '<option label="新会员" value="0" >新会员</option>';

                          $selected='';
                          foreach ($lists as $k => $v){
                              if ($k == $layerCondition){
                                  echo '<option label="'.$v['title'].'" value="'.$k.'" selected="selected" >'.$v['title'].'</option>';
                              }else{
                                  echo '<option label="'.$v['title'].'" value="'.$k.'" >'.$v['title'].'</option>';
                              }
                          }
                          ?>
                      </select>
                    </td>
                    <td style="padding-left: 10px; ">
                        层级:
                      <select name="userlevel" id="userlevel" class="za_select za_select_auto">
                          <?php
                          $sqlul="select id,sort,ename,name,deposit_num,deposit_num,max_deposit_money,withdraw_num,withdraw_money,remark,level,start_time,end_time from ".DBPREFIX."gxfcy_userlevel order by sort asc";
                          $resultul = mysqli_query($dbLink,$sqlul);
                          while($rowul = mysqli_fetch_assoc($resultul)){
                              $results[] = $rowul;
                          }
                          if ($userlevelCondition==''){
                              echo '<option label="全部" value="" selected="selected">全部</option>';
                          }
                          foreach ($results as $k => $v){
                              if ($v['ename'] == $userlevelCondition){
                                  echo '<option label="'.$v['name'].'" value="'.$v['ename'].'" selected="selected" >'.$v['name'].'</option>';
                              }else{
                                  echo '<option label="'.$v['name'].'" value="'.$v['ename'].'" >'.$v['name'].'</option>';
                              }
                          }
                          ?>
                      </select>
                    </td>
                    <td style="padding-left: 10px; "><?php echo $Mem_Method?>:</td>
                    <td >
<!--                      <select name="sort" id="sort" onChange="myFORM.search.value='';self.myFORM.submit();" class="za_select za_select_auto">-->
                      <select name="sort" id="sort" class="za_select za_select_auto">
                      <option label="<?php echo $Title?><?php echo $Mem_Account?>" value="USERNAME"><?php echo $Title?><?php echo $Mem_Account?></option>
                      <option label="<?php echo $Title?><?php echo $Mem_Name?>" value="ALIAS"><?php echo $Title?><?php echo $Mem_Name?></option>
                      <option label="<?php echo $Mem_Add?><?php echo $Mem_Date?>" value="ADDDATE"><?php echo $Mem_Add?><?php echo $Mem_Date?></option>
                      <option label="登录时间" value="LoginTime">登录时间</option>
                      <option label="会员额度" value="Money"> 会员额度</option>
                      <option label="输赢额度" value="WinLossCredit"> 输赢额度</option>
                      <option label="输赢大于" value="WinLossCreditBigger"> 输赢大于</option>
                      <option label="输赢小于" value="WinLossCreditSmaller"> 输赢小于</option>
                      <option label="存款次数" value="DepositTimes"> 存款次数</option>
                      <option label="取款次数" value="WithdrawalTimes"> 取款次数</option>
                      </select>
                      <select name="orderby" id="orderby" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                          <option label="<?php echo $Mem_ASC?>" value="ASC"><?php echo $Mem_ASC?></option>
                          <option label="<?php echo $Mem_DESC?>" value="DESC"><?php echo $Mem_DESC?></option>
                      </select>
                        --<input type="text" name="WinLossCreditBiggerOrSamllerMoney" id="WinLossCreditBiggerOrSamllerMoney" value="<?php echo $WinLossCreditBiggerOrSamllerMoney ?>" class="za_text" <?php echo $WinLossCreditBiggerOrSamllerMoney>0?'':'style="display:none;"';?> size="10" placeholder="输赢大于/小于">
                    </td>
                    <td >-- <?php echo $Mem_Totalpage?>:</td>
                    <td >
                      <select id="page" name="page" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                      <?php
                      for($i=0;$i<$page_count;$i++){
                          echo "<option value='$i'>".($i+1)."</option>";
                         }
                      ?>
                      </select>
                    </td>
                    <td >/<?php echo $page_count?><?php echo $Mem_Page?></td>
                    <td>
                        <input type="text" id="dlg_text" value="<?php echo $search_name ?>" class="za_text" size="15" placeholder="请输入关键字">
                        <input type="submit" id="dlg_ok" value="<?php echo $Mem_Search?>" class="za_button" onClick="submitSearchDlg();">
                    </td>
                    <td><input type="hidden" name="search" value="" /></td>
                    <td >
                        <?php
                       if($userlv!='D' or $url_name ){
                          // echo "<input type='button' name='append' value='$Mem_Add' onClick='document.location='user_add.php?uid=$uid&action=browse_add&lv=$lv&userlv=$userlv&langx=$langx'' class='za_button'>"  ;
                        }else{ // 普通代理商查看会员才有 新增会员
                           echo "<input type='button' name='append' value='$Mem_Add' onClick=document.location='mem_add.php?uid=$uid&action=browse_add&lv=$lv&userlv=$userlv&langx=$langx' class='za_button'>" ;
                        }

                        ?>

                    </td>
                      <?php
                         if($url_name){
                             echo "<td><a href='javascript:history.go( -1 );'>回上一页</a></td>" ;
                          }

                          if ($lv=='MEM' and $userlv!='D'){
                              echo "<td><a href='/app/agents/admin/mem_search.php?uid=$uid&lv=$lv&langx=$langx'>&nbsp;&nbsp;&nbsp;有效会员</a></td>" ;
                          }

                          if($userlv=='M' && $subUser==0 && $lv=='MEM') { //只有管理员看
                              echo "<td><a class='a_link' onclick=CheckUnAutoFreeze('user_browse.php?uid=$uid&active=unautofreeze&lv=$lv&langx=$langx');>&nbsp;&nbsp;&nbsp;一键解冻自动冻结账号</a></td>" ;
                          }
                      ?>
          </tr>
        </table>
    </dd>
</dl>
<div class="main-ui width_1300">
<?php
if ($cou==0){
?>

<table  class=" list-tab">
    <tr class="m_title_ag"> 
      <td height="30" align=center><?php echo $mem_nomem?></td>
    </tr>
  </table>
<?php
}else{
?>
  <table class="m_tab_ag list-tab <?php echo ($userlv=='D'?'daili_table':'')?>">
    <tr class="m_title_ag">
      <td width="30">序号</td>
      <?php
        if( $userlv=='D'){ // 会员管理 普通代理才有
            echo "<td class='member_name'><a name='alias' >$Rep_Member$Mem_Name</a></td> " ; // 会员名称
        }
        ?>

        <?php

        if($userlv=='M'){ // 会员管理  上一级帐号
         echo "<td width='95' class='dl_username'><a >".$Caption.$Mem_Account ."</a></td>
             <td class='login_account'><a name='alias' href='javascript:changeSort(\"ALIAS\")' class='a_link'>登录帐号</a></td> ";
        }
        ?>
     <?php

        if($userlv=='M' and $lv !='MEM'){ // 代理商管理
            echo "<td class='self_account'><a href='javascript:changeSort(\"ALIAS\")' class='a_link'>".$Title.$Mem_Account."</a></td>";
        }else{ // 会员管理
            echo "<td class='self_account'>".$Title.$Mem_Account."</td>" ;
        }
        if($lv=='D'){ // 只有代理商才有
            echo "<td class='agents_url'>域名</td>" ;
        }
     if ($userlv!='D' && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ) { // 功能列 ，普通代理没有权限
         echo " <td class='money_ky' >分层</td>" ;
     }
        if($userlv=='M'){
            echo " <td class='money_ky' width='80'>$Mem_Money</td>" ;
        }
        ?>

      <td width="80"><?php echo $Mem_Credit?></td>
        <?php
        if($userlv !='D' and $lv !='MEM'){ //  下级总计  普通代理商没有
        echo ' <td width="60" class="hy_xjzj">'. $Mem_Lower.$Mem_Total.'</td>' ;
        }
        ?>
        <?php if($userlv=='M'){ ?>
            <td class="mem_times" width="80">存取次数</td>
            <?php if($lv == 'MEM') {?>
            <td class="mem_owe_bet" width="80">提款打码量</td>
        <? }} ?>
     <?php
     if($userlv !='D' and $lv=='MEM'){ //  新增日期  普通代理商没有链接
         echo '<td width="116"><a name="new_date" class="a_link" href="javascript:changeSort(\'NEW_DATE\')">'.$Mem_Add.$Mem_Date.'</a></td><td >注册来源</td>' ;
     }else{ // 普通代理
         echo '<td width="116"><a name="new_date" >'.$Mem_Add.$Mem_Date.'</a></td>' ;
     }
     ?>


        <td width="60" class="hy_zk"><?php echo $Mem_Account?><?php echo $Mem_Status?></td>
    <?php
    if ($lv=='MEM'){ // 改单列 ，会员管理不显示
        echo "<td width='50' class='hy_status'>状态</td>" ;
    }
    ?>
        <?php
        if ($userlv!='D' && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ){ // 功能列 ，普通代理没有权限
             echo '<td width="'.($lv=='MEM'?260:120).'px" class="hy_action">'.$Mem_Function.'</td>' ;
        }
        ?>

    </tr>	
<?php
$num_sort=0 ;
while ($row = mysqli_fetch_assoc($result)){
$num_sort++ ;
$id=$row['ID'];	
$username=$row['UserName'];
$linetype=$row['LineType'];
$pay_password = $row['Address'] ;
if($lv=='D'){
    $pay_password = $row['PassWord_Safe'] ;
}
$gameForbidArr=explode('|',$row['gameSwitch']);
?>
    <tr  class="m_cen">
      <td align="center"><?php echo $num_sort ?></td> <!-- 序号 -->
        <?php
        if($userlv=='M'){ // 会员管理
        ?>

            <td class="dl_username"><a ><?php echo $row[$user] ?></a></td>    <!-- 上一级帐号 -->

            <td class="login_account"> <!-- 登录帐号 -->
                <a href="javascript:" class="a_link " onClick="line_open('<?php echo $id?>','<?php echo $username?>','<?php echo $row['Alias']?>','<?php echo ($competence_num[38]==1?$row['Phone']:'');?>','<?php echo ($competence_num[48]==1?$pay_password:''); ?>','<?php echo (substr($row['birthday'],0,10)=='0000-00-00')?"":substr($row['birthday'],0,10);?>','<?php echo ($lv=='MEM'?$row['E_Mail']:$row['wechat'])?>');">
                    <?php echo $row['UserName']?><br>
                    <?php echo $row['Alias']?>
                </a>
            </td>
        <?php
        }
        ?>

       <?php
        if( $userlv=='D'){ // 会员管理 普通代理才有
         echo "<td><a name='alias' >".$row['Alias']."</a></td> " ; // 会员名称
        }
        ?>
       <?php
        if($userlv=='M' and $lv !='MEM'){ // 代理商管理
           echo "<td class='self_account'><a class='a_link' href='user_browse.php?urlname=".$row['UserName']."&langx=$langx&lv=MEM&userlv=$lv' target='main'>".$row['UserName']."</a></td>";
        }else{ // 会员管理
           echo "<td class='self_account'>".$row['UserName']."</td>" ;
        }
       if($lv=='D'){ // 只有代理商才有
           echo "<td class='agents_url'>
                <form  name='change_agent_url' id='change_agent_url' method='POST' onsubmit='return getAgentVal(this)'>
                     <input type='hidden' name='agents_name' value='".$row['UserName']."' />
                     <input type='text' style='width:260px;' class='za_text' name='agents_url' value='".$row['agent_url']."' /><br>
                     <input type='submit'name='agent_url_chg' value='修改' class='za_button' /><span class='red'>*</span>多个域名以英文,分开
                </form>
                </td>" ;
       }
       if ($userlv!='D' && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ) { // 功能列 ，普通代理没有权限
           echo " <td class='money_ky'>";
           if ($row['layer']==0){
               echo '新会员';
           }else{
               echo $lists[$row['layer']]['title'];
           }
           echo "</td>" ;
       }
           if($userlv=='M'){
                echo "<td class='money_ky'>".number_format($row['Money'],0)."</td>";
           }
        ?>

      <td class="money_win_lose"><?php echo number_format($row['WinLossCredit'],0)?></td>
        <?php if($userlv=='M'){ ?>
        <td class="data_details">
            <a href="javascript:;" class="a_link" onclick="showDetails(this, '<?php echo $row['UserName']?>', 'show')">
                <span id="s_count_<?php echo $row['UserName']?>"><?php echo $row['DepositTimes']?></span><br>
                <span id="t_count_<?php echo $row['UserName']?>"><?php echo $row['WithdrawalTimes']?></span>
            </a>
            <div class="show_money_details">
                <a href="javascript:;" class="close_details" onclick="showDetails(this)">x</a><br>
                存款金额：<span id="s_<?php echo $row['UserName']?>"></span><br>
                取款金额：<span id="t_<?php echo $row['UserName']?>"></span>
            </div>
        </td>
        <?php if($lv == 'MEM') {?>
        <td class="owe_bet"><?php echo $row['owe_bet'];?><br>
            <a class="show_user_bet za_button" href="javascript:;" data-username="<?php echo $row['UserName']?>" data-time="<?php echo (empty($row['owe_bet_time']) || $row['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $row['owe_bet_time'])?>">打码量</a><br>
            <a class="show_user_bet_month za_button" href="javascript:;" data-username="<?php echo $row['UserName']?>" data-time="<?php echo  (empty($row['owe_bet_time']) || $row['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $row['owe_bet_time'])?>">上月打码量</a>
        </td>
        <? }} ?>
        <?php
        if($userlv !='D' and $lv !='MEM'){ //  下级总计  普通代理商没有
          echo ' <td class="hy_xjzj">'.$row['Count'].'</td>' ;
        }
        ?>

      <td> <!-- 日期列 -->
<?php
$todaytime=time();
$addtime=strtotime($row['AddDate']);
$time=($todaytime-$addtime)/86400;
if($time<30){
?>
          <span style="background-color: rgb(255,255,0);"><?php echo $row['AddDate']?></span>
<?php
}else{
?>    
          <?php echo $row['AddDate']?>
<?php
}
?>	  
          <br><?php echo $row['LoginTime']?>
	  </td>
        <?php
        if($userlv !='D' and $lv=='MEM') { //  新增日期  普通代理商没有链接
        echo '<td>'.returnRegSource($row['regSource']).'</td>' ;
        }
        ?>
      <td class="hy_zk"> <!-- 状况列 停启用-->
        <?php
        if ($row['Status']==0){ // 启用
            echo $Mem_Enable ;
        }else if ($row['Status']==1){
            if ($row['isAutoFreeze']==1){
                echo '<span style="background-color: Yellow;">自动冻结</span>' ;
            }else{
                echo '<span style="background-color: Yellow;">'.$Mem_Suspend.'</span>' ;
            }
        }else if ($row['Status']==2){ // 停用
             echo '<span style="background-color: Red;">'.$Mem_Disable.'</span>' ;
        }
        ?>
      </td>
<?php
 if ($lv=='MEM'){
?>
 <td class="hy_status">  <!-- 状态列 -->
<?php
if ($row['Online']==1){ // 在线
?>
<a href="javascript:CheckOnline('user_browse.php?uid=<?php echo $uid?>&active=logout&lv=<?php echo $lv?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id?>&name=<?php echo $username?>&langx=<?php echo $langx?>')"><span style='color: #F00;'>在线</span></a>
<?php
}else{
?>
离线
<?php
}
?>  
      </td>
<?php
}
?>
   <?php // 功能列
        if ($userlv!='D' ){ // 功能列 ，普通代理没有权限
        ?>
        <td align="left"> <!-- 功能列 -->
            <?php
             //if($subUser !=1){ // 子帐号没有修改权限
           // echo $subUser.'=='.$competence_num[43] ;
            if($subUser == 0 || $competence_num[43]==1 ){ //管理员或 判断子帐号是否有修改权限
                if ($lv!='MEM'){
                ?>
                <a class="a_link"
                   href=user_edit.php?uid=<?php echo $uid ?>&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&action=browse_edit&parents_id=<?php echo $id ?>&name=<?php echo $username ?>&enable=ALL&line=ND&langx=<?php echo $langx ?>&layer=<?php echo $row['layer']?>><?php echo $Mem_Edit ?></a> /
                <!--<a class="a_link" href=user_set.php?uid=<?php /*echo $uid */?>&lv=<?php /*echo $lv */?>&userlv=<?php /*echo $userlv */?>&parents_id=<?php /*echo $id */?>&name=<?php /*echo $username */?>&langx=<?php /*echo $langx */?> ><?php /*echo $Mem_Details */?><?php /*echo $Mem_Settings */?></a> /-->
                <?php
                }else{
                ?>
                <a class="a_link"
                   href=mem_edit.php?uid=<?php echo $uid ?>&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&action=browse_edit&parents_id=<?php echo $id ?>&name=<?php echo $username ?>&enable=ALL&line=ND&langx=<?php echo $langx ?>&layer=<?php echo $row['layer']?>><?php echo $Mem_Edit ?></a> /
               <!-- <a class="a_link" href=mem_set.php?uid=<?php /*echo $uid */?>&lv=<?php /*echo $lv */?>&userlv=<?php /*echo $userlv */?>&parents_id=<?php /*echo $id */?>&name=<?php /*echo $username */?>&langx=<?php /*echo $langx */?> ><?php /*echo $Mem_Details */?><?php /*echo $Mem_Settings */?></a> /-->
                <?php
                }

             }
            ?>

        <?php
            if ($row['Status']==0){  // 0 正常启用，2 停用
            ?>
            <a class="a_link"
               href="javascript:CheckSTOP('user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=disable&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $username ?>&langx=<?php echo $langx ?>')"><?php echo $Mem_Disable ?></a> /
            <a class="a_link"
               href="javascript:CheckSUSPEND('user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=suspend&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $username ?>&langx=<?php echo $langx ?>')"><?php echo $Mem_Suspend ?></a> /
            <?php
            }else{ // 已停用
            ?>
            <a class="a_link"
               href="javascript:CheckSTOP('user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=enable&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $username ?>&langx=<?php echo $langx ?>')"><?php echo $Mem_Enable ?></a> /
            <font color="gray"><?php echo $Mem_Suspend ?></font> /
            <?php
            }
            ?>
        <?php
            if ($lv=='MEM'){
            ?>
            <a class="a_link"
               href="javascript:CheckOnline('user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=logout&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $username ?>&langx=<?php echo $langx ?>')"><?php echo $Mem_Kick ?></a> /
            <?php
            }
            ?>
        <?php
            if ($p_level=='M'){
            ?>
            <!--<a class="a_link"
               href="javascript:CheckDEL('user_browse.php?uid=<?php echo $uid ?>&langx=<?php echo $langx ?>&active=del&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $username ?>&aguser=<?php echo $row[$user] ?>')"><?php echo $Mem_Delete ?></a> -->
            <?php
            if ($lv=='MEM' && $_SESSION['UserName'] == 'admin'){ // 查看会员,仅admin可转移会员-20180807
            ?>
            <a class="a_link" href="javascript:"
               onClick="change_line_open('<?php echo $id ?>','<?php echo $username ?>');">转移</a> /
            <?php
            }
            ?>
        <?php
            }
           if ($lv=='MEM'){
               if(in_array('BK',$gameForbidArr)){
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BK','open','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBK&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球投注?')"><font color='red' >启用篮球</font></a> /
                   <?php
               }else{
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BK','close','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBK&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球投注?')">停用篮球</a> /
                   <?php
               }
               echo '<br><br>';
               if(in_array('BKH1',$gameForbidArr)){ // 篮球滚球上半场
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BKH1','open','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBKH1&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球滚球上半场投注?')"><font color='red' >启用篮球上半场</font></a> /
                   <?php
               }else{
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BKH1','close','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBKH1&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球滚球上半场投注?')">停用篮球上半场</a> /
                   <?php
               }

               if(in_array('BKQ3',$gameForbidArr)){
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BKQ3','open','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBKQ3&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球滚球第3节投注?')"><font color='red' >启用篮球滚球第3节</font></a> /
                   <?php
               }else{
                   ?>
                   <a class="a_link" href="javascript:BetForbid('BKQ3','close','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidBKQ3&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户篮球滚球第3节投注?')">停用篮球滚球第3节</a> /
                   <?php
               }
               echo '<br><br>';
               if(in_array('DJFT',$gameForbidArr)){
                   ?>
                   <a class="a_link" href="javascript:BetForbid('DJFT','open','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidDJFT&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户电竞足球投注?')"><font color='red' >启用电竞足球</font></a> /
                   <?php
               }else{
                   ?>
                   <a class="a_link" href="javascript:BetForbid('DJFT','close','user_browse.php?uid=<?php echo $uid ?>&active=ForbidDJFT&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认开启该用户电竞足球投注?')">停用电竞足球</a> /
                   <?php
               }
               if(in_array('DJBK',$gameForbidArr)){
                   ?>
                   <a class="a_link" href="javascript:BetForbid('DJBK','open','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidDJBK&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认禁止该用户电竞篮球投注?')"><font color='red' >启用电竞篮球</font></a>
                   <?php
               }else{
                   ?>
                   <a class="a_link" href="javascript:BetForbid('DJBK','close','user_browse.php?uid=<?php echo $uid ?>&search=<?php echo $search_name ?>&active=ForbidDJBK&lv=<?php echo $lv ?>&userlv=<?php echo $userlv ?>&active_id=<?php echo $id ?>&name=<?php echo $row['ID']; ?>&langx=<?php echo $langx ?>','是否确认禁止该用户电竞篮球投注?')">停用电竞篮球</a>
                   <?php
               }
               echo '<br>';
               echo '<a class="show_mem_money za_button" href="javascript:;" data-username="'.$row['UserName'].'">厅室余额</a> ';
           }
        ?>
        </td>
    <?php // 功能列
    }
    ?>

  </tr>
<?php
}
?> 
  </table>
<?php
}
?>
  <BR>
  <table  class="m_tab table_width_400">
  <tr class="m_cen">
  	<td><?php echo $Mem_Add?> <?php echo $Mem_Date?></td>
  	<td><SPAN STYLE="background-color: rgb(255,255,0);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></td>
  	<td><?php echo $Mem_Add?><?php echo $Mem_Account?><?php echo $Mem_month?></td>
  </tr>
  <tr class="m_cen">
  	<td><?php echo $Mem_Account?> <?php echo $Mem_Status?></td>
  	<td><span style="background-color: rgb(255,0,0);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
  	<td><?php echo $Mem_Disable?></td>
  </tr>
  </table>
</div>
</form>

<!--会员资料-->
<div id="line_type" class="line_type_width" style="display: none;position: absolute;">
<table class="list-tab">
           <tr >
             <td  colspan="2">--资料-- <a class="close_window" onClick="line_close();"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a> </td>
           </tr>
           <tr>
              <td >会员帐号：</td><td align="left"><div id="user_name"></div></td>
           </tr>
           <tr>
              <td >真实姓名：</td><td align="left"><div id="user_alias"></div></td>
           </tr>
            <tr>
                <td >出生日期：</td><td align="left"><div id="user_birthday"></div></td>
            </tr>
            <?php
                if($competence_num[39]==1){ // 微信权限控制
                    echo '<tr> <td >微信号码：</td><td align="left"><div id="user_wechat"></div></td> </tr> ';
                }
                if($competence_num[38]==1){  // 手机号码权限控制
                    echo '<tr> <td >电话号码：</td> <td align="left"><div id="user_phone"></div></td> </tr>';
                    }
            if($competence_num[48]==1){  // 会员资金密码
                echo ' <tr><td >取款密码：</td><td align="left"><div id="user_address"></div></td></tr>';
            }
                ?>




</table>
</div>

<!--会员转移-->
<div id="change_line_type" class="line_type_width" style="display: none;position: absolute;">
<table class="list-tab">
    <form name='line_type' method="POST" action="shift_member_act.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv ?>&langx=<?php echo $langx?>">
        <tr >
          <td colspan="2" >
              --会员转移设置--
              <a class="close_window"  onClick="change_line_close();">
                  <img src="/images/agents/top/edit_dot.gif" width="16" height="14">
              </a>
          </td>
        </tr>
        <tr>
        <td >会员帐号：</td><td align="left"><div id="user"></div></td>
        </tr>
        <tr>
            <td >代理帐号：
            </td>
            <td align="left">
                <input type="hidden" name="tid" value=""/>
                <input type="hidden" name="name" value=""/>
                <input type="text" name="agents" value="" class="za_text_auto" size="15" minlength="5" maxlength="15">
            </td>
         </tr>
         <tr>
             <td colspan="2"> <input type="submit" class="za_button" value="<?php echo $Mem_Confirm?>"> </td>
         </tr>
    </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/user_search.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript">
    var logincp = '<?php echo $logincp;?>'; // sport ,new
    showMemberBet();
    showMemberBetMonth();
    showMemberMoney() ;


    $("#sort").change(function(){
        var sortval = $("#sort").val();
        if (sortval == 'WinLossCreditBigger' || sortval == 'WinLossCreditSmaller'){
            $('#WinLossCreditBiggerOrSamllerMoney').show()
        }else{
            $('#WinLossCreditBiggerOrSamllerMoney').hide()
        }
    });

    function onLoad() {
        var obj_enable = document.getElementById('enable');
        obj_enable.value = '<?php echo $enable?>';
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
        var obj_sort=document.getElementById('sort');
        obj_sort.value='<?php echo $sort?>';
        var obj_orderby=document.getElementById('orderby');
        obj_orderby.value='<?php echo $orderby?>';
    }

    function CheckEditY(str){
        var enable_s = document.all.enable.value;
        var page = document.all.page.value;
        if(confirm("<?php echo $Mem_Confirm?> ( 开启修改注单功能 ) <?php echo $Title?> ?"))
            document.location=str+"&enable_s="+enable_s+"&page="+page;
    }

    function CheckEditN(str){
        var enable_s = document.all.enable.value;
        var page = document.all.page.value;
        if(confirm("<?php echo $Mem_Confirm?> ( 关闭修改注单功能 ) <?php echo $Title?> ?"))
            document.location=str+"&enable_s="+enable_s+"&page="+page;
    }
    function CheckOnline(str){
        var enable_s = document.all.enable.value;
        var page = document.all.page.value;
        if(confirm("<?php echo $Mem_Confirm?> ( 离线 ) <?php echo $Title?> ?"))
            document.location=str+"&enable_s="+enable_s+"&page="+page;
    }
    function BetForbid(game,type,str,confirmTxt){
        var enable_s = document.all.enable.value;
        var page = document.all.page.value;
        if(type=='open'){
        	if(confirm(confirmTxt)){
        		document.location=str+"&game="+game+"&type="+type+"&enable_s="+enable_s+"&page="+page;
            }
		}else{
    		if(confirm(confirmTxt)){
            	document.location=str+"&game="+game+"&type="+type+"&enable_s="+enable_s+"&page="+page;
            }
		}
    }
    // 停用帐户
    function CheckSTOP(str){
        var enable_s = document.all.enable.value;
        var page = document.all.page.value;
        if(confirm("<?php echo $Mem_Confirm?> (<?php echo $Mem_Disable?> /<?php echo $Mem_Enable?> ) <?php echo $Title?> ?"))
            document.location=str+"&enable_s="+enable_s+"&page="+page;
    }
    // 删除帐户
    function CheckDEL(str){
        if(confirm("<?php echo $Mem_Confirm?> <?php echo $Mem_Delete?> <?php echo $Title?> ?"))
            document.location=str;
    }
    // 冻结帐户
    function CheckSUSPEND(str){
        if(confirm("<?php echo $Mem_Confirm?> <?php echo $Mem_Suspend?> <?php echo $Title?> ?"))
            document.location=str;
    }
    // 一键解冻所有自动冻结帐户
    function CheckUnAutoFreeze(str){
        if(confirm("<?php echo $Mem_Confirm?> 一键解冻自动冻结的 <?php echo $Title?> ?"))
            document.location=str;
    }
    function CheckWINLOSS_EN(str){
        if(confirm("<?php echo $Mem_Confirm?> <?php echo $Mem_Percent?><?php echo $Mem_Edit?><?php echo $Mem_Enable?> / <?php echo $Mem_Percent?><?php echo $Mem_Edit?><?php echo $Mem_Disable?> <?php echo $Title?> ?"))
            document.location=str;
    }
    // 代理线输入验证
    function getAgentVal(obj) {
       // console.log(obj.agents_url)
        if(!obj.agents_url.value){
            alert('请先输入代理域名!');
            return false ;
        }
    }
    // 显示/隐藏统计
    function showDetails(obj, username = '', type = '') {
        var deposit ='0.00', withdraw = '0.00';
        if(type == 'show' && username != ''){
            if($(obj).find('#s_count_' + username).html() != 0 || $(obj).find('#t_count_' + username).html() != 0){
                // 获取存取款统计值
                $.ajax({
                    type:"POST",
                    url:"user_browse.php",
                    async:false,
                    data:{user_count : username},
                    success:function(data){
                        var credit = $.parseJSON(data);
                        if(credit.S !== undefined)
                            deposit = credit.S;
                        if(credit.T !== undefined)
                            withdraw = credit.T;
                    }
                });
            }
            $('#s_' + username).html(deposit);
            $('#t_' + username).html(withdraw);
            $(obj).next().show() ;
            $(obj).parents('.m_cen').siblings('.m_cen').find('.show_money_details').hide();
        }else{ // 隐藏
            $(obj).parent('.show_money_details').hide();
        }
    }

    // 显示厅室余额
    function showMemberMoney() {
        $('.show_mem_money').on('click',function () {
            var username = $(this).data('username');
            var str = '<div>' ;
               // '<p>皇冠体育余额：<span class="sc_ye"> 加载中.. </span></p>' +
                    if(logincp=='new'){
                        str +=  '<p>国民彩票余额：<span class="gmcp_ye">加载中.. </span></p>';
                    }else{
                        str += '<p>彩票余额：<span class="cp_ye">加载中.. </span></p>';
                    }

            str += '<p>AG余额：<span class="ag_ye"> 加载中.. </span></p>' +
                '<p>开元棋牌余额：<span class="kyqp_ye">加载中.. </span></p>' +
               // '<p>皇冠棋牌余额：<span class="hgqp_ye">加载中.. </span></p>' +
                '<p>VG棋牌余额：<span class="vgqp_ye">加载中.. </span></p>' +
                '<p>乐游棋牌余额：<span class="lyqp_ye">加载中.. </span></p>' +
                '<p>快乐棋牌余额：<span class="klqp_ye">加载中.. </span></p>' +
                '<p>MG电子余额：<span class="mg_ye">加载中.. </span></p>' +
                '<p>泛亚电竞余额：<span class="avia_ye">加载中.. </span></p>' +
                '<p>雷火电竞余额：<span class="fire_ye">加载中.. </span></p>' +
                '<p>OG视讯余额：<span class="og_ye">加载中.. </span></p>' +
                '<p>BBIN视讯余额：<span class="bbin_ye">加载中.. </span></p>' +
                '<p>MW电子余额：<span class="mw_ye">加载中.. </span></p>' +
                '<p>CQ9电子余额：<span class="cq9_ye">加载中.. </span></p>' +
                '<p>FG电子余额：<span class="fg_ye">加载中.. </span></p>' +
                '</div>';
            layer.alert(str,{title: username+' 各厅室余额'});
            //getQipaiBalance(username,'sc');
            if(logincp=='new'){
                getQipaiBalance(username,'gmcp')
            }else{
                agCpDoAction(username,'cp') ;
            }
            getQipaiBalance(username,'ky');
            //getQipaiBalance(username,'ff');
            getQipaiBalance(username,'vg');
            getQipaiBalance(username,'ly');
            getQipaiBalance(username,'kl');
            getQipaiBalance(username,'mg');
            getQipaiBalance(username,'avia');
            getQipaiBalance(username,'fire');
            agCpDoAction(username,'ag') ;
            getQipaiBalance(username,'og');
            getQipaiBalance(username,'bbin');
            getQipaiBalance(username,'mw');
            getQipaiBalance(username,'cq9');
            getQipaiBalance(username,'fg');

        })
    }
    // 获取棋牌余额
    function getQipaiBalance(username,type) {
        var ajaxurl = '/app/agents/include/ky/ky_api.php';
        switch (type){
            case 'sc': // sc
                ajaxurl = '/app/agents/include/sportcenter/sport_api.php'
                break;
            case 'ky': // 开元
                ajaxurl = '/app/agents/include/ky/ky_api.php'
                break;
            case 'vg': // vg
                ajaxurl = '/app/agents/include/vgqp/vg_api.php'
                break;
            case 'ff': // ff
                ajaxurl = '/app/agents/include/hgqp/hg_api.php'
                break;
            case 'ly': // ly
                ajaxurl = '/app/agents/include/lyqp/ly_api.php'
                break;
            case 'kl': // kl
                ajaxurl = '/app/agents/include/klqp/kl_api.php'
                break;
            case 'mg': // mg
                ajaxurl = '/app/agents/include/mg/mg_api.php'
                break;
            case 'avia': // avia
                ajaxurl = '/app/agents/include/avia/avia_api.php'
                break;
            case 'fire': // fire
                ajaxurl = '/app/agents/include/thunfire/fire_api.php'
                break;
            case 'gmcp': // gmcp
                ajaxurl = '/app/agents/include/gmcp/cp_api.php'
                break;
            case 'og': // og
                ajaxurl = '/app/agents/include/og/og_api.php'
                break;
            case 'bbin': // bbin
                ajaxurl = '/app/agents/include/bbin/bbin_api.php'
                break;
            case 'mw': // mw
                ajaxurl = '/app/agents/include/mw/mw_api.php'
                break;
            case 'cq9': // cq9
                ajaxurl = '/app/agents/include/cq9/cq9_api.php'
                break;
            case 'fg': // fg
                ajaxurl = '/app/agents/include/fg/fg_api.php'
                break;
        }
        var data = {
            action : 'b',
            username : username,
        };
        $.ajax({
            type : 'POST',
            url : ajaxurl+'?_='+ Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    switch (type){
                        case 'sc': // sc
                            $('body').find('.sc_ye').html(item.data.sc_balance);
                            break;
                        case 'ky': // 开元
                            $('body').find('.kyqp_ye').html(item.data.ky_balance);
                            break;
                        case 'vg': // vg
                            $('body').find('.vgqp_ye').html(item.data.vg_balance);
                            break;
                        case 'ff': // ff
                            $('body').find('.hgqp_ye').html(item.data.ff_balance);
                            break;
                        case 'ly': // ly
                            $('body').find('.lyqp_ye').html(item.data.ly_balance);
                            break;
                        case 'kl': // kl
                            $('body').find('.klqp_ye').html(item.data.kl_balance);
                            break;
                        case 'mg': // mg
                            $('body').find('.mg_ye').html(item.data.mg_balance);
                            break;
                        case 'avia': // avia
                            $('body').find('.avia_ye').html(item.data.avia_balance);
                            break;
                        case 'fire': // fire
                            $('body').find('.fire_ye').html(item.data.fire_balance);
                            break;
                        case 'gmcp': // gmcp
                            $('body').find('.gmcp_ye').html(item.data.gmcp_balance);
                            break;
                        case 'og': // og
                            $('body').find('.og_ye').html(item.data.og_balance);
                            break;
                        case 'bbin': // bbin
                            $('body').find('.bbin_ye').html(item.data.bbin_balance);
                            break;
                        case 'mw': // mw
                            $('body').find('.mw_ye').html(item.data.mw_balance);
                            break;
                        case 'cq9': // cq9
                            $('body').find('.cq9_ye').html(item.data.cq_balance);
                            break;
                        case 'fg': // fg
                            $('body').find('.fg_ye').html(item.data.fg_balance);
                            break;
                    }


                } else {
                    $('.this_account').html('0.00');
                    $('.change_to_hg').attr('data-monval','0.00');
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }

    // ag  余额
    function agCpDoAction(username,type) {
        var data ;
        var ajaxurl = '/app/agents/include/ag_api.php?_='+Math.random() ; // ag
        data={
            action:'b',
            username:username,
        }
        if(type=='cp'){
            ajaxurl = '/app/agents/include/cp_ajaxTran.php?_='+Math.random() ;
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl ,
            data:data,
            dataType:'json',
            success:function(ret){
               // console.log(ret);
                if(ret.err==0){ // 获取数据成功
                    if(type=='cp'){
                        $('.cp_ye').html(ret.cp_balance);
                    }else{
                        $('.ag_ye').html(ret.balance_ag);
                    }

                }
                else{
                    if(type=='cp'){
                        $('.cp_ye').html('0.00');
                    }else{
                        $('.ag_ye').html('0.00');
                    }
                }

            },
            error:function(){
                alert('网络错误，请稍后重试');
            }
        });
    }

    // 显示会员打码量
    function showMemberBet() {
        $('.show_user_bet').on('click',function () {
            var username = $(this).data('username');
            var owe_bet_time = $(this).data('time');
            var user_bet = {};
            // 获取会员已打码量
            $.ajax({
                type:"GET",
                url:"user_bet.php",
                async:false,
                data:{username : username},
                dataType:'json',
                success:function(response){
                    if(response.status == 200){
                        user_bet = response.data;
                    }
                }
            });
            var str = '<table class="table" border="1" cellspacing="0" cellpadding="5" width="100%"> <tbody>';
            $.each(user_bet.bet_list, function (i,v) {
                str += '<tr><td>' + v.msg + '</td><td>' + v.value + '</td></tr>\n';
            });
            str += '<tr>' +
                '<td>总计</td>' +
                '<td align="top"><font color="red"><span class="total_bet">' + user_bet.total_bet + '</span><br>统计的开始时间:<br><span class="total_time">' + owe_bet_time + '</span></font>' +
                '</td>' +
                '</tr>' +
                '</tbody></table>';
            layer.alert(str, {title:username + ' 打码量' });
        })
    }

    // 显示会员上月打码量
    function showMemberBetMonth() {
        $('.show_user_bet_month').on('click',function () {
            var username = $(this).data('username');
            var owe_bet_time = $(this).data('time');
            var type = 'lm';
            var user_bet = {};
            // 获取会员已打码量
            $.ajax({
                type:"GET",
                url:"user_bet.php",
                async:false,
                data:{username : username, type : type},
                dataType:'json',
                success:function(response){
                    if(response.status == 200){
                        user_bet = response.data;
                    }
                }
            });
            var str = '<table class="table" border="1" cellspacing="0" cellpadding="5" width="100%"> <tbody>';
            $.each(user_bet.bet_list, function (i,v) {
                str += '<tr><td>' + v.msg + '</td><td>' + v.value + '</td></tr>\n';
            });
            str += '<tr>' +
                '<td>总计</td>' +
                '<td align="top"><font color="red"><span class="total_bet">' + user_bet.total_bet + '</span><br>统计开始时间:<br><span class="total_time">' + user_bet.dateStart + '</span><br>统计结束时间:<br><span class="total_time">' + user_bet.dateEnd + '</span></font>' +
                '</td>' +
                '</tr>' +
                '</tbody></table>';
            layer.alert(str, {title:username + ' 上月打码量' });
        })
    }
</script>
</body>
</html>
<?php
innsertSystemLog($loginname,$userlv,$loginfo);
?>
