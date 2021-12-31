<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

$redisObj = new Ciredis();
$redisObj->setOne('USER_SENDMAIL_TOTAL',0);

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");
$id=$_REQUEST['id'];
$name= str_replace(' ','',$_REQUEST["name"]); // 去除空格
$form_action=$_REQUEST['form_action'];
$action=$_REQUEST["action"];
$active=$_REQUEST["active"];
$lv = $_REQUEST['lv'];
$date_start = $_REQUEST['date_start'];
$date_end = $_REQUEST['date_end'];
$username = $_REQUEST['username'];
$todaydate=date('Y-m-d');
$tomorrowdate=date('Y-m-d',strtotime('1 day'));
$todaytime=date('Y-m-d H:i:s');
if ($date_start==''){
    $date_start=date('Y-m-d 00:00:00');
}
if ($date_end==''){
    $date_end=date('Y-m-d 23:59:59');
}
$where = '';
$messages = [];
$pageSize = 16;

//删除消息
if( isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && isset($_REQUEST['id']) && $_REQUEST['id'] > 0){
    if(mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_sendmail_data where ID=".$_REQUEST['id'])){
        mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_sendmail_reply_data where topid=".$_REQUEST['id']);
        $loginfo = $loginname.' 在系统会员消息中 <font class="red">删除</font> 了编号为 <font class="green">'.$_REQUEST['id'].'</font>的消息及所有跟踪回复信息' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo "<script>alert('操作成功!');self.location='usermails.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }else{
        echo "<script>alert('操作失败!');self.location='usermails.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }
}

$where .= "time >='$date_start' and time <='$date_end'";

if( isset($username) && strlen($username)>0 ){
    $where .= " and username = '$username' ";
}

if(strlen($where)>0){
    $where = " where ".$where;
}

$page = isset($_REQUEST['page']) && $_REQUEST['page']>0 ? $_REQUEST['page']:1;
$sql = "select id,username,title,message,`time`,`type` from ".DBPREFIX."web_sendmail_data".$where." order by time desc limit ".(($page - 1) * $pageSize) . "," . $pageSize;

$result = mysqli_query($dbLink,$sql);
$totalSql = "select id from ".DBPREFIX."web_sendmail_data";
$totalResult = mysqli_query($dbLink,$totalSql);
$totalCount = mysqli_num_rows($totalResult);
$page_count = ceil($totalCount/$pageSize);

?>
<html>
<head>
    <title>会员发来的消息</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body  >
<FORM id="myFORM" ACTION="" METHOD=POST name="myFORM" >
    <input type='hidden' name='uid' value='<?php echo $uid;?>'>
<dl class="main-nav">
    <table>
        <tr class="m_tline">
            <td><div class="query_bet_title">会员消息</div></td>
            <td style="float:left;margin-left: 20px">
                消息日期:
                <input type="text" name="date_start" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $date_start?>"  class="za_text_auto" /> -
                <input type="text" name="date_end" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $date_end?>"  class="za_text_auto" />
                会员帐号:
                <input type=TEXT name="username" size=10 value="<?php echo $username?>" minlength="5" maxlength="15" class="za_text_auto" style="width: 100px">
                <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                共<?php echo $cou?>条
                <select name='page' id="page" onChange="self.myFORM.submit()">
                    <?php
                    if ($page_count==0){
                        $page_count=1;
                    }
                    for($i=1;$i<=$page_count;$i++){
                        if ($i==$page){
                            echo "<option selected value='$i'>".$i."</option>";
                        }else{
                            echo "<option value='$i'>".$i."</option>";
                        }
                    }
                    ?>
                </select> 共<?php echo $page_count?> 页
            </td>
        </tr>
    </table>
</dl>
</FORM>
<div class="main-ui" style ="width:95%">
        <table class="m_tab">
            <tr class="m_title">
                <td width="5%">编号</td>
                <td width="9%">用户名</td>
                <td width="6%">类型</td>
                <td width="10%">标题</td>
                <td width="45">内容</td>
                <td width="10%">时间</td>
                <td width="5%">状态</td>
                <td width="10%">操作</td>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)){ ?>
                <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
                    <td align="center"><?php echo $row['id'];?></td>
                    <td align="center"><?php echo $row['username'];?></td>
                    <td align="center"><?php if($row['type']==1){ echo '财务问题'; }if($row['type']==2){ echo '技术问题'; }if($row['type']==3){ echo '业务咨询'; }if($row['type']==4){ echo '意见建议'; }if($row['type']==5){ echo '其他问题'; }?></td>
                    <td align="center"><?php echo $row['title'];?></td>
                    <td align="left"><?php echo $row['message'];?></td>
                    <td align="center"><?php echo $row['time'];?></td>
                    <?php
                        $rowStatus = $rowText = $resultStatus = $color = '';
                        $resultStatus = mysqli_query($dbLink,"select isAdmin from ".DBPREFIX."web_sendmail_reply_data where topid=".$row['id']." order by time desc limit 1");
                        $rowStatus = mysqli_fetch_assoc($resultStatus);
                        if($rowStatus['isAdmin']==1){$color='green';$rowText='已回复';}elseif($rowStatus['isAdmin']==0){$color='red';$rowText='待回复';}
                    ?>
                    <td align="center" style="color:<?php echo $color;?>;"><?php echo $rowText;?></td>
                    <td width="center">
                        <a class="a_link" href=usermails_details.php?uid=<?php echo $uid ?>&lv=<?php echo $lv ?>&action=details&id=<?php echo $row['id'] ?>&langx=<?php echo $langx ?> >查看详情</a> /
                        <a class="a_link" href=usermails.php?uid=<?php echo $uid ?>&lv=<?php echo $lv ?>&action=delete&id=<?php echo $row['id'] ?>&langx=<?php echo $langx ?> >删除</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
</body>
</html>