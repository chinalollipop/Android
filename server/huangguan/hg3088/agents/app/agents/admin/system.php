<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$type=$_REQUEST["type"];
$lv = $_REQUEST['lv'] ;

require ("../include/traditional.$langx.inc.php");

$id = $_SESSION['ID']; // 当前用户 id
switch ($type){
    case "UID":
        $mysql="update ".DBPREFIX."web_system_data set uid='".$_REQUEST['SC1']."',uid_tw='".$_REQUEST['SC2']."',uid_en='".$_REQUEST['SC3']."',datasite='".$_REQUEST['SC4']."',datasite_en='".$_REQUEST['SC6']."',datasite_tw='".$_REQUEST['SC5']."',Name='".$_REQUEST['Name']."',Passwd='".$_REQUEST['Passwd']."',Name_tw='".$_REQUEST['Name_tw']."',Passwd_tw='".$_REQUEST['Passwd_tw']."',Name_en='".$_REQUEST['Name_en']."',Passwd_en='".$_REQUEST['Passwd_en']."',InUid='".$_REQUEST['InUid']."',InUid_tw='".$_REQUEST['InUid_tw']."',InUid_en='".$_REQUEST['InUid_en']."',InUrl='".$_REQUEST['InUrl']."',InName='".$_REQUEST['InName']."',InPasswd='".$_REQUEST['InPasswd']."',InName_tw='".$_REQUEST['InName_tw']."',InPasswd_tw='".$_REQUEST['InPasswd_tw']."',InName_en='".$_REQUEST['InName_en']."',InPasswd_en='".$_REQUEST['InPasswd_en']."',SunUid='".$_REQUEST['SunUid']."',SunUid_tw='".$_REQUEST['SunUid_tw']."',SunUid_en='".$_REQUEST['SunUid_en']."',SunUrl='".$_REQUEST['SunUrl']."',SunName='".$_REQUEST['SunName']."',SunPasswd='".$_REQUEST['SunPasswd']."',SunName_tw='".$_REQUEST['SunName_tw']."',SunPasswd_tw='".$_REQUEST['SunPasswd_tw']."',SunName_en='".$_REQUEST['SunName_en']."',SunPasswd_en='".$_REQUEST['SunPasswd_en']."'";
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "MAX":
        $mysql="update ".DBPREFIX."web_system_data set R=".$_REQUEST['M1'].",OU=".$_REQUEST['M2'].",M=".$_REQUEST['M3'].",RE=".$_REQUEST['M4'].",ROU=".$_REQUEST['M5'].",PD=".$_REQUEST['M6'].",T=".$_REQUEST['M7'].",F=".$_REQUEST['M8'].",P=".$_REQUEST['M9'].",PC=".$_REQUEST['M10'].",FS=".$_REQUEST['M11'].",MAX=".$_REQUEST['M12'];
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "ST": // 系统维护操作
        if($_REQUEST['SC3']==1){
            $loginfo_status = '是' ;
            $kickOff = '，踢所有在线会员下线';
        }else{
            $loginfo_status = '否' ;
            $kickOff = '';
        }
        // 开启事务
        $dbMasterLink->autocommit(false);
        $mysql="update ".DBPREFIX."web_system_data set Website=".(int)$_REQUEST['SC3'].",systime='".$_REQUEST['systime']."'";
        if(mysqli_query($dbMasterLink,$mysql)){
            if($_REQUEST['SC3'] == 1){ // 开启系统维护，则踢所有在线会员
                $mysql = "update " . DBPREFIX.MEMBERTABLE." set Oid = 'logout', Online = 0, LogoutTime = now() where Online = 1";
                if(mysqli_query($dbMasterLink, $mysql)){
                    $dbMasterLink->commit();
                    $dbMasterLink->autocommit(true);
                }else{
                    $dbMasterLink->rollback();
                    die('设置失败！');
                }
                // 清除会员redis，便于后续判断会员登录标识
                $redisObj = new Ciredis();
                $redisObj->deleteByPre('loginuser_');
            }else{ // 取消系统维护
                $dbMasterLink->commit();
                $dbMasterLink->autocommit(true);
            }
            $logInfo = $loginname.' 在系统参数设置中 <font class="red">设置了系统维护状态为</font> <font class="blue">'.$loginfo_status.'</font> ' . $kickOff;
            innsertSystemLog($loginname,$lv,$logInfo);  /* 插入系统日志 */
        }else{
            $dbMasterLink->rollback();
            die('设置失败！');
        }
        break;
    case "TM":
        $mysql="update ".DBPREFIX."web_system_data set systime='".$_REQUEST['SC1']."'";
        mysqli_query($dbMasterLink,$mysql) or die ("设置失败!");
        break;
    case "LANGX": // 会员,代理,总代 等弹窗广告
        $mysql="update ".DBPREFIX."web_system_data set Msg_Member='".$_REQUEST['Msg_Member']."',Msg_Member_tw='".$_REQUEST['Msg_Member_tw']."',Msg_Agents='".$_REQUEST['Msg_Agents']."',Msg_Agents_tw='".$_REQUEST['Msg_Agents_tw']."',Msg_World='".$_REQUEST['Msg_World']."',Msg_World_tw='".$_REQUEST['Msg_World_tw']."',Msg_Corprator='".$_REQUEST['Msg_Corprator']."',Msg_Corprator_tw='".$_REQUEST['Msg_Corprator_tw']."' where ID='".$id."'";
        mysqli_query($dbMasterLink,$mysql) or die ("设置失败!");
        break;
    case "NOUD":
        $mysql="update ".DBPREFIX."web_system_data set msg_update=".$_REQUEST['set'];
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "SIX":
        $mysql="update ".DBPREFIX."number_num set MID='".$_REQUEST['mid']."',M_Date='".$_REQUEST['date']."',M_Time='".$_REQUEST['time']."',M_Start='".$_REQUEST['start']."'";
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "OPEN":
        $mysql="update ".DBPREFIX."number_num set Open=".(int)$_REQUEST['open']."";
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "NUMID":
        $mysql="update ".DBPREFIX."web_system_data set OUID='".$_REQUEST['ouid']."',DTID='".$_REQUEST['dtid']."',PMID='".$_REQUEST['pmid']."'";
        mysqli_query($dbMasterLink,$mysql);
        break;
    case "Url": // 设置会员 和 代理 ，管理 地址
        $mysql="update ".DBPREFIX."web_system_data set Member_Url='".$_REQUEST['member_url']."',Agent_Url='".$_REQUEST['agent_url']."',Admin_Url='".$_REQUEST['admin_url']."'";
        mysqli_query($dbMasterLink,$mysql) or die ("设置失败!");
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">设置了</font> 会员地址为 <font class="red">'.$_REQUEST['member_url'].'</font>, 代理地址为 <font class="green">'.$_REQUEST['agent_url'].'</font>,管理地址为 <font class="blue">'.$_REQUEST['admin_url'].'</font>' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        break;
    case "MatchScore":// 接比分账号信息（数据网址、数据网址-新版、账号、密码、UID），方便前台（旧版、新版）观看赛果使用
        $mysql="update ".DBPREFIX."web_system_data set datasite_ms='".$_REQUEST['MS_datasite']."',datasite_ms_new='".$_REQUEST['MS_datasite_new']."',Name_ms='".$_REQUEST['MS_name']."',Passwd_ms='".$_REQUEST['MS_password']."',Uid_ms='".$_REQUEST['Uid_ms']."'";
        mysqli_query($dbMasterLink,$mysql) or die ("更新失败!!");
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">更新了</font> 接比分账号（数据网址、数据网址-新版、账号、密码、UID） <font class="red">'.$_REQUEST['MS_datasite'].'-'.$_REQUEST['MS_datasite_new'].'-'.$_REQUEST['MS_name'].'-'.$_REQUEST['MS_password'].'-'.$_REQUEST['Uid_ms'].'</font>, 帐号为 <font class="green">'.$_REQUEST['MS_name'].'</font> ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        break;
    case "PAGE_NOTICE": // 单页维护公告-20180811
        $categoryId = $_REQUEST['category_id'];
        $state = $_REQUEST['state_' . $categoryId];
        $title = $_REQUEST['title_' . $categoryId];
        $content = $_REQUEST['content_' . $categoryId];
        $aTerminal = $_REQUEST['terminal_id_' . $categoryId]; // 维护区分手机客户端（ios or android）
        $terminalId = implode(',', $aTerminal);
        $stateText = $state == 1 ? "是" : "否";
        $mysql = "UPDATE ". DBPREFIX . "cms_article SET `state`= {$state}, `content` = '{$content}', `terminal_id` = '{$terminalId}',`author_id` = {$_SESSION['ID']}, `author` = '{$_SESSION['UserName']}', 
            `updated_at` = '" . date('Y-m-d H:i:s') . "' WHERE category_id = {$categoryId}";
        mysqli_query($dbMasterLink, $mysql) or die("设置失败！");
        $logInfo = $loginname.' 在系统参数设置中 <font class="red">设置了' . $title . '状态为</font> <font class="blue">' . $stateText . '</font> ' ;
        innsertSystemLog($loginname, $lv, $logInfo);  // 写入系统日志
        break;
}

// 重新更新
$syssql = "select datasite,datasite_tw,datasite_en,datasite_ms,datasite_ms_new,Uid,Uid_tw,Uid_en,Uid_ms,Name,Name_tw,Name_en,Name_ms,Passwd,Passwd_tw,Passwd_en,Passwd_ms,Uid_ms,Member_Url,Agent_Url,Admin_Url,Msg_Member,Msg_Member_tw,Msg_Agents,Msg_Agents_tw,Msg_World,Msg_World_tw,Msg_Corprator,Msg_Corprator_tw,Website,systime from ".DBPREFIX."web_system_data where Oid='$uid' and UserName='$loginname'";
//echo $syssql;
$sysresult = mysqli_query($dbLink,$syssql);
$row = mysqli_fetch_assoc($sysresult);

$sql = "select M_Time,M_Start,MID from ".DBPREFIX."number_num";
$result = mysqli_query($dbLink,$sql);
$num = mysqli_fetch_assoc($result);
$m_time=date('m/d/Y H:i:s',strtotime($num['M_Time']));
$m_start=date('m/d/Y H:i:s',strtotime($num['M_Start']));
$gid=$num['MID'];
if ($gid<100 and $gid>9){
    $mid='0'.$gid;
}else if($num<10){
    $mid='00'.$gid;
}else{
    $mid=$gid;
}

//查询刷水扩展账号
$sql = "select Type,Datasite,Name,Passwd,Uid,status,ID,Cookie,Ver,source from ".DATAHGPREFIX."web_getdata_account_expand";
$result = mysqli_query($dbCenterSlaveDbLink,$sql);
while($resultFetch = mysqli_fetch_assoc($result)){
    $dataExpand[] = $resultFetch;
}

$numExpand = count($dataExpand);

//查询视屏采集账号
$VideoSql = "select Type,Datasite,Name,liveid,Passwd,Uid,status,ID from ".DBPREFIX."web_official_account_expand where Type=1";
$VideoResult = mysqli_query($dbLink,$VideoSql);
while($VideoResultFetch = mysqli_fetch_assoc($VideoResult)){
    $VideoExpand[] = $VideoResultFetch;
}
$numVideoExpand = count($VideoExpand);



// 单页维护公告-20180811
$sql = "SELECT `id`, `category_id`, `title`, `content`, `state`, `terminal_id` FROM " . DBPREFIX . 'cms_article ORDER BY `id` ASC';
$oResult = mysqli_query($dbLink, $sql);

?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .tanchuang_table input[type="text"]{ width: 100%;}
        .sh_table td p{width: 263px}
        input.width_100{width: 100px;}
    </style>
</head>
<body >
<dl class="main-nav"><dt>系统参数</dt><dd></dd></dl>
<div class="main-ui">
    <!--
<table border="0" cellpadding="0" cellspacing="1" class="m_tab">
<form name=UID action="" method=post>
  <TR class="m_title">
    <td>网站名称</td>
    <td>数据网址</td>
    <td>简体UID</td>
    <td>繁体UID</td>
	<td>英文UID</td>
	<td>&nbsp; </td>
  </TR>
  <TR class=m_cen>
      <td>新宝简体</td>
      <td><input class="za_text_auto"  maxLength=30 size=30 value="<?php echo $row['datasite']?>" name=SC4></td>
      <td><input class="za_text_auto" size=30 value="<?php echo $row['Uid']?>" name=SC1></td>
	  <td><input class="za_text_auto" size=30 value="<?php echo $row['Uid_tw']?>" name=SC2></td>
	  <td><input class="za_text_auto" size=30 value="<?php echo $row['Uid_en']?>" name=SC3></td>
      <td width="58" rowspan="3"><input class=za_button type=submit value="确定" name=ft_ch_ok11></td>
        <input type=hidden value="UID" name=type>
  </TR>
  <TR class=m_cen>
      <td>新宝繁体</td>
      <td><input class="za_text_auto"  maxLength=30 size=30 value="<?php echo $row['datasite_tw']?>" name=SC5></td>
      <td>
          帐号 <input class="za_text_auto" size=6 value="<?php echo $row['Name']?>" name=Name><br>
          密码 <input class=za_text size=6 value="<?php echo $row['Passwd']?>" name=Passwd></td>
	  <td>帐号 <input class="za_text_auto" size=6 value="<?php echo $row['Name_tw']?>" name=Name_tw><br>
          密码 <input class=za_text size=6 value="<?php echo $row['Passwd_tw']?>" name=Passwd_tw></td>
	  <td>帐号 <input class="za_text_auto" size=6 value="<?php echo $row['Name_en']?>" name=Name_en><br>
          密码 <input class=za_text size=6 value="<?php echo $row['Passwd_en']?>" name=Passwd_en></td>
  </TR>
  <TR class=m_cen>
      <td width="70">新宝英文</td>
      <td width="210"><input class=za_text  maxLength=30 size=30 value="<?php echo $row['datasite_en']?>" name=SC6></td>
      <td width="210"></td>
	  <td width="210"></td>
	  <td width="210"></td>
  </TR>
</form>
</table>
 -->

    <table border="0" cellpadding="0" cellspacing="1" class="m_tab">
        <TR class="m_title">
            <td width="100">账号类型</td>
            <td width="80">数据网址</td>
            <td width="50">帐号</td>
            <td width="50">密码</td>
            <td width="100">uid</td>
            <td width="100">cookie或者ver</td>
            <td width="400" colspan="4" >操作</td>
        </tr>
        <TR class=m_cen>
            <td>
                <select class="za_select_auto" name="typeExpand" id="typeAccExpand" >
                    <option value="zh-tw" >繁体刷水</option>
                    <option value="zh-cn" >简体刷水</option>
                    <option value="en-us" >英体刷水</option>
                    <!-- <option value="ms" >简体比分</option> -->
                </select>
            </td>
            <td><input class="za_text_auto"  maxLength=30 size=30 name="urlExpand" value="" ></td>
            <td><input class="za_text_auto" size=6 name="nameExpand" value="" /></td>
            <td><input class="za_text_auto" size=6 name="passwdExpand" value="" /></td>
            <td><input class="za_text_auto" maxLength=30  size=30 name="uidExpand" value="" /></td>
            <td><input class="za_text_auto" maxLength=30  size=40 name="verExpand" value="" /></td>
            <td colspan="3" ><input class=za_button type=button value="添加" name="okExpand" onclick="addAcountExpand()" /></td>
            <input type="hidden" value="accountExpand" name="type" />
        </TR>
        <TR class=m_cen><td colspan="9" >&nbsp;&nbsp;&nbsp;&nbsp;</td></TR>
        <?php
        if($numExpand && $numExpand>0){
            ?>
            <tr class="sh_table m_title">
                <td width="60">帐号类型</td>
                <td  >数据网址</td>
                <td >帐号</td>
                <td >密码</td>
                <td >uid</td>
                <td width="300" ><p> cookie或者ver</p> </td>
                <td width="40">来源</td>
                <td width="40">状态</td>
                <td >操作</td>
            </tr>
            <?php
            foreach($dataExpand as $key=>$val){
                ?>
                <tr class="sh_table m_cen">
                    <td><?php echo $val['Type'];?></td>
                    <td ><input class="za_text_auto" id="DatasiteEdt_<?php echo $val['ID']; ?>" name="Datasite" value="<?php echo $val['Datasite'];?>" /></td>
                    <td ><input class="za_text_auto width_100" id="nameEdt_<?php echo $val['ID']; ?>" name="nameEdt" value="<?php echo $val['Name'];?>" /></td>
                    <td ><input class="za_text_auto width_100" id="passwdEdt_<?php echo $val['ID']; ?>" name="passwdEdt" value="<?php echo $val['Passwd'];?>" /></td>
                    <td ><input class="za_text_auto" id="uidEdt_<?php echo $val['ID']; ?>" maxLength=30  size=30 name="uidEdt" value="<?php echo $val['Uid'];?>" /></td>
                    <td ><!--<textarea id="cookieEdt_<?php /*echo $val['ID']; */?>" name="cookieEdt" cols="46" rows="5" ><?php /*echo $val['cookie']; */?></textarea>-->
                        <textarea id="verEdt_<?php echo $val['ID']; ?>" name="verEdt" cols="46" rows="3"><?php echo $val['Ver'];?></textarea>
                    </td>
                    <td ><?php if($val['source']==13){ echo 'ios'; }else{  echo 'pc'; }?></td>
                    <?php if($val['status']==1){?>
                        <td >异常</td>
                        <td >
                            <input class=za_button type=button value="删除" name="okExpand" data-account="<?php echo $val['Name']?>" onclick="delAcountExpand(<?php echo $val['ID'];?>,this)" />
                            <input class=za_button type=button value="更新" name="okExpand" onclick="updateAcountExpandNologin(<?php echo $val['ID'];?>)" />
                            <input class=za_button type=button value="更新UID" name="okExpand" data-account="<?php echo $val['Name']?>" data-ssuid="<?php echo $val['Uid'];?>" data-langx="<?php echo $val['Type']; ?>" data-url="<?php echo $val['Datasite'];?>" onclick="updateAcountExpand(<?php echo $val['ID'];?>,this)" />
                        </td>
                    <?php }elseif($val['status']==0){?>
                        <td >正常</td>
                        <td >
                            <input class=za_button type=button value="删除" name="okExpand" data-account="<?php echo $val['Name']?>" onclick="delAcountExpand(<?php echo $val['ID'];?>,this)" />
                            <input class=za_button type=button value="更新" name="okExpand" onclick="updateAcountExpandNologin(<?php echo $val['ID'];?>)" />
                            <input class=za_button type=button value="更新UID" name="okExpand" data-account="<?php echo $val['Name']?>" data-ssuid="<?php echo $val['Uid'];?>" data-langx="<?php echo $val['Type']; ?>" data-url="<?php echo $val['Datasite'];?>" onclick="updateAcountExpand(<?php echo $val['ID'];?>,this)" />
                        </td>
                    <?php }?>
                </tr>
                <?php
            }
        }else{
            ?>
            <tr class=m_cen><td colspan="6" ><div style="font-weight:bold;">----------------空----------------</div></td></tr>
            <?php
        }
        ?>
    </table>
    <br>

    <br>
    <form name="MatchScore" id="MatchScore" action="" method="post">
        <table border="0" cellpadding="0" cellspacing="1" class="m_tab">
            <tr class="m_title">
                <td rowspan="2">接比分数据配置</td>
                <td>数据网址</td>
                <td>数据网址(新版)</td>
                <td>帐号</td>
                <td>密码</td>
                <td>UID</td>
                <td>操作</td>
            </tr>

            <tr class=m_cen>
                <td><input class=za_text  maxLength=30 size=30 value="<?php echo $row['datasite_ms']?>" name="MS_datasite" id="MS_datasite" ></td>
                <td><input class=za_text  maxLength=30 size=30 value="<?php echo $row['datasite_ms_new']?>" name="MS_datasite_new" id="MS_datasite_new"></td>
                <td><input class=za_text size=6 value="<?php echo $row['Name_ms']?>" name="MS_name" id="MS_name" minlength="5" maxlength="15"></td>
                <td><input class=za_text size=6 value="<?php echo $row['Passwd_ms']?>" name="MS_password" id="MS_password" minlength="5" maxlength="15"/></td>
                <td><input class="za_text_auto" maxlength="30" size="30" name="Uid_ms" id="Uid_ms" value="<?php echo $row['Uid_ms']?>"></td>
                <td><input class=za_button type=button value="设定" onclick="checkDataSite()" ></td>
                <input type=hidden value="MatchScore" name=type>

            </tr>

        </table>
    </form>
    <br>

    <table border="0" cellpadding="0" cellspacing="1" class="m_tab">
        <TR class="m_title">
            <td rowspan="2"><font color='red'>新版视频采集账号配置</font></td>
            <td >数据网址</td>
            <td >帐号</td>
            <td >密码</td>
            <td >uid</td>
            <td >liveid</td>
            <td colspan="2">操作</td>
        </tr>
        <TR class=m_cen>
            <td><input class="za_text"  maxLength=30 size=30 name="urlExpandVideo" value="" ></td>
            <td><input class="za_text" size=6 name="nameExpandVideo" value="" /></td>
            <td><input class="za_text" size=6 name="passwdExpandVideo" value="" /></td>
            <td><input class="za_text" maxLength=30  size=30 name="uidExpandVideo" value="" /></td>
            <td><input class="za_text" maxLength=30  size=30 name="liveidExpandVideo" value="" /></td>
            <td colspan="3"><input class=za_button type=button value="添加" name="okExpandVideo" onclick="addAcountExpandVideo()" /></td>
            <input type="hidden" value="accountExpandVideo" name="type" />
        </TR>
        <TR class=m_cen><td colspan="8" >&nbsp;&nbsp;&nbsp;&nbsp;</td></TR>
        <?php
        if($numVideoExpand && $numVideoExpand>0){
            ?>
            <TR class="m_cen">
                <td ></td>
                <td >数据网址</td>
                <td >帐号</td>
                <td >密码</td>
                <td >id</td>
                <td >liveid</td>
                <td >状态</td>
                <td >操作</td>
            </tr>
            <?php
            foreach($VideoExpand as $key=>$val){
                ?>
                <TR class=m_cen>
                    <td ></td>
                    <td ><input class="za_text" id="DatasiteEdtVideo_<?php echo $val['ID']; ?>" name="DatasiteVideo" value="<?php echo $val['Datasite'];?>" /></td>
                    <td ><input class="za_text" id="nameEdtVideo_<?php echo $val['ID']; ?>" name="nameEdtVideo" value="<?php echo $val['Name'];?>" /></td>
                    <td ><input class="za_text" id="passwdEdtVideo_<?php echo $val['ID']; ?>" name="passwdEdtVideo" value="<?php echo $val['Passwd'];?>" /></td>
                    <td ><input class="za_text" id="uidEdtVideo_<?php echo $val['ID']; ?>" maxLength=30  size=30 name="uidEdtVideo" value="<?php echo $val['Uid'];?>" /></td>
                    <td ><input class="za_text" id="liveidEdtVideo_<?php echo $val['ID']; ?>" maxLength=30  size=30 name="liveidEdtVideo" value="<?php echo $val['liveid'];?>" /></td>
                    <?php if($val['status']==1){?>
                        <td >异常</td>
                        <td >
                            <input class=za_button type=button value="删除" name="okExpandVideo" data-account="<?php echo $val['Name']?>" onclick="delAcountExpandVideo(<?php echo $val['ID'];?>,this)" />
                            <input class=za_button type=button value="更新UID" name="okExpandVideo" data-account="<?php echo $val['Name']?>" data-ssuid="<?php echo $val['Uid'];?>" data-langx="<?php echo $val['Type']; ?>" data-url="<?php echo $val['Datasite'];?>" onclick="updateAcountExpandVideo(<?php echo $val['ID'];?>,this)" />
                        </td>
                    <?php }elseif($val['status']==0){?>
                        <td >正常</td>
                        <td >
                            <input class=za_button type=button value="删除" name="okExpandVideo" data-account="<?php echo $val['Name']?>" onclick="delAcountExpandVideo(<?php echo $val['ID'];?>,this)" />
                            <input class=za_button type=button value="更新UID" name="okExpandVideo" data-account="<?php echo $val['Name']?>" data-ssuid="<?php echo $val['Uid'];?>" data-langx="<?php echo $val['Type']; ?>" data-url="<?php echo $val['Datasite'];?>" onclick="updateAcountExpandVideo(<?php echo $val['ID'];?>,this)" />
                        </td>
                    <?php }?>
                </TR>
                <?php
            }
        }else{
            ?>
            <tr class=m_cen><td colspan="8" ><div style="font-weight:bold;">----------------空----------------</div></td></tr>
            <?php
        }
        ?>
    </table>

    <br>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab">
        <TR class="m_title">
            <td >会员-地址</td>
            <td >代理-地址</td>
            <td >管理-地址</td>
            <td >功能</td>
        </tr>
        <form name=Url action="" method=post>
            <TR class=m_cen>
                <td><input class="za_text" value="<?php echo $row['Member_Url']?>" name=member_url></td>
                <td><input class="za_text" value="<?php echo $row['Agent_Url']?>" name=agent_url></td>
                <td><input class="za_text" value="<?php echo $row['Admin_Url']?>" name=admin_url></td>
                <td><input class=za_button type=submit value="设定" name=url_ok></td>
                <input type=hidden value="Url" name=type>
            </TR>
        </form>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab tanchuang_table">
        <form name=LANGX action="" method=post>
            <TR class="m_title">
                <td width="60px">语种</td>
                <td >弹窗公告内容</td>
                <td width="60px"></td>
            </TR>
            <TR class=m_cen>
                <td>会员简体：</td>
                <td align=right><input class=za_text type="text"  maxLength=250 size=155 value="<?php echo $row['Msg_Member']?>" name=Msg_Member></td>
                <td rowspan="8"><input class=za_button type=submit value="确定" name=ft_ch_ok2></td>
                <input type=hidden value="LANGX" name=type>
            </TR>
            <TR class=m_cen>
                <td>会员繁体：</td>
                <td align=right><input class=za_text type="text" maxlength=250 size=155 value="<?php echo $row['Msg_Member_tw']?>" name=Msg_Member_tw></td>
            </TR>
            <TR class=m_cen>
                <td>代理简体：</td>
                <td align=right><input class=za_text type="text" maxLength=250 size=155 value="<?php echo $row['Msg_Agents']?>" name=Msg_Agents></td>
            </TR>
            <TR class=m_cen>
                <td>代理繁体：</td>
                <td align=right><input class=za_text type="text" maxlength=250 size=155 value="<?php echo $row['Msg_Agents_tw']?>" name=Msg_Agents_tw></td>
            </TR>
            <TR class=m_cen>
                <td>总代简体：</td>
                <td align=right><input class=za_text type="text" maxLength=250 size=155 value="<?php echo $row['Msg_World']?>" name=Msg_World></td>
            </TR>
            <TR class=m_cen>
                <td>总代繁体：</td>
                <td align=right><input class=za_text type="text" maxlength=250 size=155 value="<?php echo $row['Msg_World_tw']?>" name=Msg_World_tw></td>
            </TR>
            <TR class=m_cen>
                <td>股东简体：</td>
                <td align=right><input class=za_text type="text" maxLength=250 size=155 value="<?php echo $row['Msg_Corprator']?>" name=Msg_Corprator></td>
            </TR>
            <TR class=m_cen>
                <td>股东繁体：</td>
                <td align=right><input class=za_text type="text" maxlength=250 size=155 value="<?php echo $row['Msg_Corprator_tw']?>" name=Msg_Corprator_tw></td>
            </TR>

        </form>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab tanchuang_table">
        <form name=FTR action="" method=post>
            <tr class=m_cen>
                <td width="60px" class="m_title">系统维护：</td>
                <td >
                    <input type="radio" type="text" name="SC3" value="1" <?php
                    if ($row['Website']==1){
                        echo "checked";
                    }		?>
                    >
                    是
                    <input type="radio" name="SC3" value="0" <?php
                    if ($row['Website']==0){
                        echo "checked";
                    }		?>
                    >
                    否</td>
                <td width="66" class="m_title">维护公告：</td>
                <td width="600"><input class="za_text_auto"  maxLength=300 size=120 value="<?php echo $row['systime']?>" name="systime" /></td>
                <td width="60px"><input class="za_button" type=submit value="确定" name=ft_ch_ok10></td>
                <input type=hidden value="ST" name=type />
            </tr>
        </form>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab tanchuang_table">
        <TR class="m_title">
            <td width="60px">类型</td>
            <td colspan="3">单页面维护公告</td>
            <td width="60px"></td>
        </TR>
        <?php while ($aNotice = mysqli_fetch_assoc($oResult)){?>
            <TR class=m_cen>
                <form name="page_notice" action="" method=post>
                    <input type="hidden" name="type" value="PAGE_NOTICE">
                    <input type="hidden" name="category_id" value="<?php echo $aNotice['category_id']?>"/>
                    <input type="hidden" name="title_<?php echo $aNotice['category_id']?>" value="<?php echo $aNotice['title']?>"/>
                    <td><?php echo $aNotice['title']?></td>
                    <td>
                        <input type="radio" name="state_<?php echo $aNotice['category_id']?>" value="1" <?php
                        if ($aNotice['state'] == 1){
                            echo "checked";
                        }?>
                        >
                        是
                        <input type="radio" name="state_<?php echo $aNotice['category_id']?>" value="0" <?php
                        if ($aNotice['state'] == 0){
                            echo "checked";
                        }?>
                        >
                        否
                        <br>
                        <?php
                        $aTerminals = explode(',', $aNotice['terminal_id']);
                        ?>
                        (<input name="terminal_id_<?php echo $aNotice['category_id'] ?>[]" type="checkbox" value="13" <?php echo(in_array(13, $aTerminals) ? 'checked' : '') ?> />IOS
                        <input name="terminal_id_<?php echo $aNotice['category_id'] ?>[]" type="checkbox" value="14" <?php echo(in_array(14, $aTerminals) ? 'checked' : '') ?> />Android
                        <input name="terminal_id_<?php echo $aNotice['category_id'] ?>[]" type="checkbox" value="1" <?php echo(in_array(1, $aTerminals) ? 'checked' : '') ?> />PC/M版)
                    </td>
                    <td width="66px" class="m_title">维护公告：</td>
                    <td width="600px"><input class="za_text_auto" maxLength=300 size=120 name='content_<?php echo $aNotice['category_id']?>' value="<?php echo $aNotice['content']?>"></td>
                    <td width="60px" ><input class="za_button" type=submit value="确定" name="submit_notice"></td>
                </form>
            </TR>
        <?php }?>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab tanchuang_table">
        <form name= action="" method=post>
            <tr>
                <td width="35%">会员佣金设置</td>
                <td width="35%">体彩佣金比例</td>
                <td>功能</td>
            </tr>
            <TR class=m_cen>
                <td>
                    <select>
                        <option>启动</option>
                        <option>关闭</option>
                    </select>
                </td>
                <td>
                    <select>
                        <option>天天返点</option>
                    </select>
                </td>
                <td>
                    <!--<input class="za_button" type=submit value="提交" name=ft_ch_ok10>-->
                    <a class="za_button" href="rebate_game_settings.php?uid=<?php echo $uid;?>" target="main" style="width:50px; display: block; margin: auto; font-size: 14px " >设定</a><br>
                    <a class="za_button" href="rebate_game_hour_settings.php" target="main" style="width:100px; display: block; margin: auto; font-size: 14px " >时时返点设定</a><br>
                    <a class="za_button" href="rebate_operating.php?uid=<?php echo $uid;?>" target="main" style="width:70px; display: block; margin: auto; font-size: 14px " >手动 退水</a>

                </td>
            </TR>

        </form>
    </table>
    <br>
</div>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>

<script type="text/javascript">
    window.onerror = function(){
        return true;
    }
    var lv = '<?php echo $lv?>' ;
    var loginname = '<?php echo $loginname?>' ;

    var now  = new Date("<?php echo date('m/d/Y H:i:s')?>");

    function GetServerTime(){
        var urodz     = new Date("<?php echo $m_time?>");
        now.setTime(now.getTime()+250);
        days    = (urodz - now) / 1000 / 60 / 60 / 24;
        daysRound   = Math.floor(days);
        hours    = (urodz - now) / 1000 / 60 / 60 - (24 * daysRound);
        hoursRound   = Math.floor(hours);
        minutes   = (urodz - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
        minutesRound  = Math.floor(minutes);
        seconds   = (urodz - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
        secondsRound  = Math.round(seconds);
        //document.getElementById("date").innerHTML   = daysRound;
        //document.getElementById("time").innerHTML   = hoursRound + ":" + minutesRound + ":" + secondsRound;
        if (daysRound < 0){
            m_time.innerHTML="<b><font color=green>已经开盘</font></b>";
        }else{
            m_time.innerHTML="<b><font color=green>距离开盘时间还有"+daysRound+"天"+hoursRound+"小时"+minutesRound+"分"+secondsRound+"秒"+"</font></b>";
        }
    }
    setInterval("GetServerTime()",250);


    var now  = new Date("<?php echo date('m/d/Y H:i:s')?>");
    function GetServerStart(){
        var urodz     = new Date("<?php echo $m_start?>");
        now.setTime(now.getTime()+250);
        days    = (urodz - now) / 1000 / 60 / 60 / 24;
        daysRound   = Math.floor(days);
        hours    = (urodz - now) / 1000 / 60 / 60 - (24 * daysRound);
        hoursRound   = Math.floor(hours);
        minutes   = (urodz - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
        minutesRound  = Math.floor(minutes);
        seconds   = (urodz - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
        secondsRound  = Math.round(seconds);
        //document.getElementById("date").innerHTML   = daysRound;
        //document.getElementById("time").innerHTML   = hoursRound + ":" + minutesRound + ":" + secondsRound;
        if (daysRound < 0){
            m_start.innerHTML="<b><font color=red>已经封盘</font></b>";
        }else{
            m_start.innerHTML="<b><font color=red>距离封盘时间还有"+daysRound+"天"+hoursRound+"小时"+minutesRound+"分"+secondsRound+"秒"+"</font></b>";
        }
    }
    setInterval("GetServerStart()",250);


    // 设置接比分账号信息（数据网址、数据网址-新版、账号、密码、UID），方便前台（旧版、新版）观看赛果使用
    // 如果数据都不为空的话，刷一下水
    // 如果刷水不为空的话，更新到数据库
    // 否则弹框提醒检查账号或者更换账号
    function checkDataSite() {

        var siteurl = document.getElementById('MS_datasite').value;
        var siteNewUrl = document.getElementById('MS_datasite_new').value;
        var sitename = document.getElementById('MS_name').value;
        var sitepsw = document.getElementById('MS_password').value;
        var siteUid = document.getElementById('Uid_ms').value;

        if(siteurl =='' ||siteNewUrl =='' || sitename=='' || sitepsw=='' || siteUid==''){
            alert('请输入内容!') ;
            return;
        }


        $.ajax({
            type: 'POST',
            url: '../ajax.php',
            data:{
                type:'testScoreExpandAccData',
                siteurl:siteurl,
                siteNewUrl:siteNewUrl,
                siteUid:siteUid,
            },
            dataType: 'json',
            success:function(res){

                // console.log(res)

                alert(res.message);
                if (res.status == 0){
                    var formMatchScore = document.getElementById("MatchScore");
                    formMatchScore.submit();

                }else{
                    window.location.reload();
                    return;
                }

            }
        });

    }
    // 添加刷水帐号
    function addAcountExpand(){
        var typeExpand = $("#typeAccExpand option:selected").val();
        var urlExpand = $("input[name='urlExpand']").val();
        var nameExpand= $("input[name='nameExpand']").val();
        var passwdExpand = $("input[name='passwdExpand']").val();
        var uidExpand = $("input[name='uidExpand']").val();
        var verExpand = $("input[name='verExpand']").val();

        if(urlExpand.length==0 || nameExpand.length==0 || passwdExpand.length==0 ){
            alert('参数不能为空');
            return false ;
        }

        var uid = "<?php echo $uid;?>";
        var exitAccoutArr = new Array();

        $.each(exitAccoutArr,function(i,n){
            if( nameExpand == n){
                //alert('账号已存在不能重复添加！');
                return false;
            }
        });
        // 如果UID不为空的话，那么就需要去刷一下水
        // 如果可以刷到水，则增加到数据库
        // 否则还走之前的逻辑
        //if( uidExpand.length!=0 ){
	
        if( uidExpand.length==0 ){
            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    uid:uid,
                    type:'testExpandAccData',
                    typeEx:typeExpand,
                    urlEx:urlExpand,
                    uidEx:uidExpand,
                },
                dataType: 'json',
                success:function(res){
                    if (res.status==1){ // 刷水异常，弹框提醒
                        alert(res.message);
                    }else{ // 刷水成功添加刷水账号
                        $.ajax({
                            type: 'POST',
                            url: '../ajax.php',
                            data:{
                                uid:uid,
                                type:'accountExpandNoLogin',
                                typeEx:typeExpand,
                                urlEx:urlExpand,
                                nameEx:nameExpand,
                                passwdEx:passwdExpand,
                                uidEx:uidExpand,
                            },
                            dataType: 'json',
                            success:function(res){
                                alert(res.message);
                                if(res.status==0){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });
        }else{
            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    //uid:uid,
                    uid:uidExpand,
                    verEx:verExpand,
                    type:'accountExpand',
                    typeEx:typeExpand,
                    urlEx:urlExpand,
                    nameEx:nameExpand,
                    passwdEx:passwdExpand,
                    lv:lv ,
                    loginname:loginname ,
                },
                dataType: 'json',
                success:function(res){
                    alert(res.message);
                    if(res.status==0){
                        window.location.reload();
                    }
                }
            });
        }
    }
    // 删除刷水帐号
    function delAcountExpand(id,obj){
        var uid = "<?php echo $uid;?>";
        var account = $(obj).data('account') ;
        $.ajax({
            type:'POST',
            url:'../ajax.php',
            data:{
                uid:uid,
                type:'delAccEx',
                id:id,
                account:account ,
                lv:lv ,
                loginname:loginname ,
            },
            dataType: 'json',
            success:function(res){
                alert(res.message);
                if(res.status==0){
                    window.location.reload();
                }
            }
        });
    }
    // 更新刷水帐号 uid， 或者更新（数据网址、用户名、密码、uid）
    function updateAcountExpand(id,obj){
        var uid = "<?php echo $uid;?>";
        var account = $(obj).data('account') ;
        var ssuid = $(obj).data('ssuid') ; // 修改前的uid
        var langx = $(obj).data('langx') ;
        if (langx.length==0) langx = 'zh-cn';
        var url = $(obj).data('url') ;

        // 点更新UID的时候，去判断下UID是否有变化，
        // 如果UID有变化，那么点击更新UID的时候，就要去拿新UID刷水下，
        //    如果刷到水，则成功更新数据。刷不到水则弹框提醒重新检查账号
        // 如果UID没有变化，则走之前的逻辑
        var DatasiteEdt = $("#DatasiteEdt_"+id).val();
        var nameEdt = $("#nameEdt_"+id).val();
        var passwdEdt = $("#passwdEdt_"+id).val();
        var uidEdt = $("#uidEdt_"+id).val();

        if (uidEdt == ssuid){
            $.ajax({
                type:'POST',
                url:'../ajax.php',
                data:{
                    uid:uid,
                    type:'updateAccEx',
                    id:id,
                    account:account ,
                    passwd:passwdEdt,
                    datasiteedt:DatasiteEdt,
                    lv:lv ,
                    loginname:loginname ,
                },
                dataType: 'json',
                success:function(res){
                    alert(res.message);
                    if(res.status==0){
                        window.location.reload();
                    }
                }
            });
        }else{ // 修改uid先检查刷水
            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    uid:uid,
                    type:'testExpandAccData',
                    typeEx:langx,
                    urlEx:DatasiteEdt,
                    uidEx:uidEdt,
                },
                dataType: 'json',
                success:function(res){
                    if(res.status==1){ // 刷水异常，弹框提醒
                        alert(res.message);
                        window.location.reload();
                    }else{ // 刷水成功更新刷水账号

                        $.ajax({
                            type: 'POST',
                            url: '../ajax.php',
                            data:{
                                uid:uid,
                                type:'updateAccExNoLogin',
                                id:id,
                                DatasiteEdt:DatasiteEdt,
                                nameEdt:nameEdt,
                                passwdEdt:passwdEdt,
                                uidEdt:uidEdt,
                            },
                            dataType: 'json',
                            success:function(res){
                                alert(res.message);
                                if(res.status==0){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });
        }
    }

    // 更新刷水帐号 uid， 或者更新（数据网址、用户名、密码、uid、cookie）
    function updateAcountExpandNologin(id){
        var uid = "<?php echo $uid;?>";
        var DatasiteEdt = $("#DatasiteEdt_"+id).val();
        var nameEdt = $("#nameEdt_"+id).val();
        var passwdEdt = $("#passwdEdt_"+id).val();
        var uidEdt = $("#uidEdt_"+id).val();
        var cookieEdt = $("#cookieEdt_"+id).val();
        var verEdt = $("#verEdt_"+id).val();
        $.ajax({
            type: 'POST',
            url: '../ajax.php',
            data:{
                uid:uid,
                type:'updateAcountExpandNologin',
                id:id,
                DatasiteEdt:DatasiteEdt,
                nameEdt:nameEdt,
                passwdEdt:passwdEdt,
                uidEdt:uidEdt,
                cookieEdt:cookieEdt,
                verEdt:verEdt,
            },
            dataType: 'json',
            success:function(res){
                alert(res.message);
                if(res.status==0){
                    window.location.reload();
                }
            }
        });
    }

    //添加视屏采集账号
    function addAcountExpandVideo(){
        var urlExpand = $("input[name='urlExpandVideo']").val();
        var nameExpand= $("input[name='nameExpandVideo']").val();
        var passwdExpand = $("input[name='passwdExpandVideo']").val();
        var uidExpand = $("input[name='uidExpandVideo']").val();
        var livedExpand = $("input[name='liveidExpandVideo']").val();

        if(urlExpand.length==0 || nameExpand.length==0 || passwdExpand.length==0 ){
            alert('参数不能为空');
            return false ;
        }

        var uid = "<?php echo $uid;?>";
        var exitAccoutArr = new Array();

        $.each(exitAccoutArr,function(i,n){
            if( nameExpand == n){
                //alert('账号已存在不能重复添加！');
                return false;
            }
        });
        // 如果UID不为空的话，那么就需要去刷一下水
        // 如果可以刷到水，则增加到数据库
        // 否则还走之前的逻辑
        if( uidExpand.length!=0 ){

            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    uid:uid,
                    type:'testExpandAccData',
                    typeEx:'zh-cn',
                    urlEx:urlExpand,
                    uidEx:uidExpand,
                },
                dataType: 'json',
                success:function(res){
                    if (res.status==1){ // 刷水异常，弹框提醒
                        alert(res.message);
                    }else{ // 刷水成功添加刷水账号
                        $.ajax({
                            type: 'POST',
                            url: '../ajax.php',
                            data:{
                                uid:uid,
                                type:'accountExpandNoLoginVideo',
                                livedEx:livedExpand,
                                urlEx:urlExpand,
                                nameEx:nameExpand,
                                passwdEx:passwdExpand,
                                uidEx:uidExpand,
                            },
                            dataType: 'json',
                            success:function(res){
                                alert(res.message);
                                if(res.status==0){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });
        }else{
            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    uid:uid,
                    type:'accountExpandVideo',
                    livedEx:livedExpand,
                    urlEx:urlExpand,
                    nameEx:nameExpand,
                    passwdEx:passwdExpand,
                    lv:lv ,
                    loginname:loginname ,
                },
                dataType: 'json',
                success:function(res){
                    alert(res.message);
                    if(res.status==0){
                        window.location.reload();
                    }
                }
            });
        }
    }
    // 删除视屏采集帐号
    function delAcountExpandVideo(id,obj){
        var uid = "<?php echo $uid;?>";
        var account = $(obj).data('account') ;
        $.ajax({
            type:'POST',
            url:'../ajax.php',
            data:{
                uid:uid,
                type:'delAccExVideo',
                id:id,
                account:account ,
                lv:lv ,
                loginname:loginname ,
            },
            dataType: 'json',
            success:function(res){
                alert(res.message);
                if(res.status==0){
                    window.location.reload();
                }
            }
        });
    }
    // 更新视屏采集帐号 uid， 或者更新（数据网址、用户名、密码、uid）
    function updateAcountExpandVideo(id,obj){
        var uid = "<?php echo $uid;?>";
        var account = $(obj).data('account') ;
        var ssuid = $(obj).data('ssuid') ; // 修改前的uid
        var langx = $(obj).data('langx') ;
        if (langx.length==0) langx = 'zh-cn';
        var url = $(obj).data('url') ;

        // 点更新UID的时候，去判断下UID是否有变化，
        // 如果UID有变化，那么点击更新UID的时候，就要去拿新UID刷水下，
        //    如果刷到水，则成功更新数据。刷不到水则弹框提醒重新检查账号
        // 如果UID没有变化，则走之前的逻辑
        var DatasiteEdt = $("#DatasiteEdtVideo_"+id).val();
        var nameEdt = $("#nameEdtVideo_"+id).val();
        var passwdEdt = $("#passwdEdtVideo_"+id).val();
        var uidEdt = $("#uidEdtVideo_"+id).val();
        var liveidEdt = $("#liveidEdtVideo_"+id).val();
        if(liveidEdt==''||liveidEdt=='undefined'){
            alert('liveid不能为空!');
            return false;
        }

        if (uidEdt == ssuid){
            $.ajax({
                type:'POST',
                url:'../ajax.php',
                data:{
                    uid:uid,
                    type:'updateAccExVideo',
                    id:id,
                    account:account ,
                    passwd:passwdEdt,
                    datasiteedt:DatasiteEdt,
                    liveid:liveidEdt,
                    lv:lv ,
                    loginname:loginname ,
                },
                dataType: 'json',
                success:function(res){
                    alert(res.message);
                    if(res.status==0){
                        window.location.reload();
                    }
                }
            });
        }else{ // 修改uid先检查刷水
            $.ajax({
                type: 'POST',
                url: '../ajax.php',
                data:{
                    uid:uid,
                    type:'testExpandAccData',
                    typeEx:langx,
                    urlEx:DatasiteEdt,
                    uidEx:uidEdt,
                },
                dataType: 'json',
                success:function(res){
                    if(res.status==1){ // 刷水异常，弹框提醒
                        alert(res.message);
                        window.location.reload();
                    }else{ // 刷水成功更新刷水账号

                        $.ajax({
                            type: 'POST',
                            url: '../ajax.php',
                            data:{
                                uid:uid,
                                type:'updateAccExNoLogin',
                                id:id,
                                DatasiteEdt:DatasiteEdt,
                                nameEdt:nameEdt,
                                passwdEdt:passwdEdt,
                                uidEdt:uidEdt,
                            },
                            dataType: 'json',
                            success:function(res){
                                alert(res.message);
                                if(res.status==0){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                }
            });
        }
    }

</script>
</body>
</html>