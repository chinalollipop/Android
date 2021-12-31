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

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$redisObj = new Ciredis();

// http 域名配置
$datajson_url = $redisObj->getSimpleOne('http_ts_url'); // 取redis 设置的值
$datastr_url = json_decode($datajson_url,true) ;
//定义使用 HTTP 的网址
if(!defined("HTTPS_WEBSITE")) {
    define("HTTPS_WEBSITE", $datastr_url['http_url']);
}

if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) ) {
    $mainhost=getMainHostThis();
    if(strpos(HTTPS_WEBSITE, $mainhost) === false) { // https
        if(!defined("WS_HEAD")) {
            define("WS_HEAD", "wss");
        }
        if(!defined("WEBSOCKET_IP")) {
            define("WEBSOCKET_IP", $datastr_url['ts_https_url']);
        }
    }else { // http
        if(!defined("WS_HEAD")) {
            define("WS_HEAD", "ws");
        }
        if(!defined("WEBSOCKET_IP")) {
            define("WEBSOCKET_IP", $datastr_url['ts_http_url']);
        }
    }
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
if($action=='opennews'){ // 系统短信
    $title_new='系统短信' ;
}else{ // 系统消息  sitenews
    $title_new='系统消息' ;
}
$todaydate=date('Y-m-d');
$tomorrowdate=date('Y-m-d',strtotime('1 day'));
$todaytime=date('Y-m-d H:i:s');
if ($form_action=='Y'){ // 新增短信
    $msg= str_replace("\r\n", "",  $_REQUEST['scoll_news']) ; // 简体
    $msg_tw = str_replace("\r\n", "",  $_REQUEST['scoll_news_tw']) ;
    $msg_en = str_replace("\r\n", "",  $_REQUEST['scoll_news_en']);
    $type_radio = $_REQUEST['type_radio']; // 公告类型
    $begintime = $_REQUEST['begintime']; // 公告开始时间
    $endtime = $_REQUEST['endtime']; // 公告开始时间

    if($name){ // 如果存在帐号 ，则给单独会员新增
        $acsql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$name'";
        $acresult = mysqli_query($dbLink,$acsql);
        $accou = mysqli_num_rows($acresult);
        if($accou==0){ // 改会员不存在
            echo "<script language=javascript>alert('会员帐号不存在，请重新输入!');self.location='news.php?uid=$uid&langx=$langx&action=opennews';</script>";
            exit ;
        }else{
            $sql = "select UserName from ".DBPREFIX."web_message_data where UserName='$name' AND MsType='$type_radio'";
            $result = mysqli_query($dbLink,$sql);
            $cou=mysqli_num_rows($result);
            if ($cou < 5){ // 新增单个会员短信 限制 5 条
                $mysql="insert into ".DBPREFIX."web_message_data(UserName,Message,Message_tw,Message_en,Time,Date,type,MsType,BeginTime,EndTime) values ('$name','$msg','$msg_tw','$msg_en','$todaytime','$todaydate','0','$type_radio','$begintime','$endtime')";
            }else{ // 更新单个会员短信
                $mysql="update ".DBPREFIX."web_message_data set UserName='$name',Message='$msg',Message_tw='$msg_tw',Message_en='$msg_en',Time='$todaytime',Date='$todaydate',type='0',MsType='$type_radio',BeginTime='$begintime',EndTime='$endtime' where UserName='$name' AND MsType='$type_radio' ORDER BY Time ASC LIMIT 1";
            }
            $redisRes = $redisObj->setOne($name.'_userEmail','notread'); // 设置该会员未读取消息
            $memmysql="update ".DBPREFIX.MEMBERTABLE." set message_status='notread' where UserName='$name'"; // 更新所有会员信息读取状态
            mysqli_query($dbMasterLink,$memmysql);

        }

    }else{ // 没有输入帐号则给全部会员发短信
        $sql = "select MsType from ".DBPREFIX."web_message_data where type='1' AND MsType='$type_radio'";
        $result = mysqli_query($dbLink,$sql);
        $cou=mysqli_num_rows($result);
        if ($cou < 5){ // 新增会员短信 限制 5 条
            $mysql="insert into ".DBPREFIX."web_message_data(UserName,Message,Message_tw,Message_en,Time,Date,type,MsType,BeginTime,EndTime) values ('ALL','$msg','$msg_tw','$msg_en','$todaytime','$todaydate','1','$type_radio','$begintime','$endtime')";
        }else{ // 更新会员短信
            // 更新最久一条数据
            $mysql="update ".DBPREFIX."web_message_data set UserName='ALL',Message='$msg',Message_tw='$msg_tw',Message_en='$msg_en',Time='$todaytime',Date='$todaydate',MsType='$type_radio',BeginTime='$begintime',EndTime='$endtime' where type='1' AND MsType='$type_radio' ORDER BY Time ASC LIMIT 1 ";

        }
        $memmysql="update ".DBPREFIX.MEMBERTABLE." set message_status='notread'"; // 更新所有会员信息读取状态
        mysqli_query($dbMasterLink,$memmysql);

    }
    mysqli_query($dbMasterLink,$mysql);
    echo "<script language=javascript>alert('新增短信成功!');self.location='news.php?uid=$uid&langx=$langx&action=opennews';</script>";
}
if ($active=='del'){ // 删除 短信 和 公告
    $mysql="delete from ".DBPREFIX."web_message_data where ID='$id'";
    mysqli_query($dbMasterLink,$mysql);

}else if ($active=='edit'){ // 编辑
    $mysql="update ".DBPREFIX.MEMBERTABLE." set Notes='".$_REQUEST['Notes']."',Phone='".$_REQUEST['Phone']."',Alias='".$_REQUEST['Alias']."' where ID='$id'";
    mysqli_query($dbMasterLink,$mysql);
    echo "<Script language=javascript>self.location='news.php?uid=$uid&langx=$langx&action=$action';</script>";
}
function returnType($mstype){
    switch ($mstype){
        case 0:
            $str = '系统短信';
            break;
        case 1:
            $str = '存款公告';
            break;
        case 2:
            $str = '取款公告';
            break;
    }
    return $str ;
}

?>
<html>
<head>
    <title>会员弹窗短信</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body  >
<dl class="main-nav"><dt><?php echo $title_new ?></dt><dd></dd></dl>
<div class="main-ui">
    <?php
    if ($action=='opennews'){ // 会员弹窗短信,系统短信
        ?>
        <table class="m_tab">
            <form method="post" name='form_message' action="" onsubmit="return checkinput();">
                <tr class="m_cen">
                    <td width="185" align="right">帐号:</td>
                    <td width="612" colspan="3" align="left">
                        <input name="name" type="text" value="" minlength="5" maxlength="15" placeholder="不填表示所有会员(限制5条)" /> &nbsp;&nbsp;&nbsp;&nbsp;
                        开始时间：<input style="width: 130px;" type="text" class="za_text_auto" name="begintime"  placeholder="请选择开始时间" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $todaydate?>" readonly/>
                        &nbsp;&nbsp; 结束时间：<input style="width: 130px;" type="text" class="za_text_auto" name="endtime" placeholder="请选择结束时间" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $tomorrowdate?>" readonly/>
                    </td>
                </tr>
                <tr class="m_cen">
                    <td width="185" align="right">更新简体短信息:</td>
                    <td colspan="3"  align="left"><textarea name="scoll_news" cols="85" rows="2" wrap="PHYSICAL"></textarea></td>
                </tr>
                <tr class="m_cen">
                    <td align="right">更新繁体短信息:</td>
                    <td colspan="3"  align="left"><textarea name="scoll_news_tw" cols="85" rows="2" wrap="PHYSICAL"></textarea></td>
                </tr>
                <tr class="m_cen">
                    <td align="right">更新英文短信息:</td>
                    <td colspan="3"  align="left"><textarea name="scoll_news_en" cols="85" rows="2" wrap="PHYSICAL"></textarea></td>
                </tr>
                <tr align="left">
                    <td></td>
                    <td colspan="4">
                        <input type="radio" name="type_radio" value="0" checked="" class="radio-box"> 系统短信 &nbsp;
                        <input type="radio" name="type_radio" value="1" class="radio-box"> 存款公告 &nbsp;
                        <input type="radio" name="type_radio" value="2" class="radio-box"> 取款公告 &nbsp;
                        <input type="submit" value="确定"  name="Submit" class="za_button">
                        <input type="reset" value="取消"  name="Reset" class="za_button">
                        <input type="hidden" name="form_action" value="Y">
                    </td>
                </tr>
            </form>
        </table>
        <br>
        <table class="m_tab">
            <tr class="m_title">
                <td colspan="8">短信息</td>
            </tr>
            <tr class="m_title">
                <td width="30">编号</td>
                <td width="60">帐号</td>
                <td width="80">新增时间</td>
                <td width="80">开始时间</td>
                <td width="80">结束时间</td>
                <td width="80">类型</td>
                <td width="554">内容</td>
                <td width="40">功能</td>
            </tr>
            <?php
            $i=1;
            $messages = [];
            $sql = "select ID,UserName,Date,$message as Message,MsType,BeginTime,EndTime from ".DBPREFIX."web_message_data order by ID desc";
            $result = mysqli_query($dbLink,$sql);
            while ($row = mysqli_fetch_assoc($result)){
                if( $row['UserName'] !== 'ALL' ){
                    $uSql = "select ID FROM ".DBPREFIX.MEMBERTABLE." where username = '".$row['UserName']."'";
                    $uResult = mysqli_query($dbLink,$uSql);
                    $userInfo = mysqli_fetch_assoc($uResult);
                    $row['userid'] = $userInfo["ID"];
                }
                $messages[$row['ID']] = $row;
            ?>
            <tr id="message_<?php echo $i?>" class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
                <td align="center"><?php echo $i?></td>
                <td align="center"><font color=red><?php echo $row['UserName']?></font></td>
                <td align="center"><?php echo $row['Date']?></td>
                <td ><?php echo substr($row['BeginTime'],0,10)?></td>
                <td ><?php echo substr($row['EndTime'],0,10)?></td>
                <td class="red_txt"><?php echo returnType($row['MsType'])?></td>
                <td align="left"><?php echo $row['Message']?></td>
                <td align="center">
                    <a class="a_link" href="javascript:;" onclick="pushMessage(<?php echo $row['ID']?>, '<?php echo 'admin';?>');return false;">推送在线会员</a>&nbsp; |&nbsp;
                    <a class="a_link" href="javascript:;" onclick="deleteMsg(this,'news.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&active=del&name=<?php echo $row['UserName']?>&langx=<?php echo $langx?>&action=opennews')">删除</a>
                </td>
                <?php
                $i=$i+1;
                }

                $messagesJson = json_encode($messages);

                ?>
            </tr>
        </table>
        <?php
    }else if ($action=='sitenews'){ // 系统消息
        ?>
        <table class="m_tab">
            <tr class="m_title">
                <td colspan="10">系统消息</td>
            </tr>
            <tr class="m_title">
                <td width="30">编号</td>
                <td width="60">名字</td>
                <td width="80">日期</td>
                <td width="192">内容</td>
                <td width="90">电话号码</td>
                <td width="80">QQ<br>Skype</td>
                <td width="120">电邮信箱</td>
                <td width="100">IP</td>
                <td width="140">网址</td>
                <td width="32">功能</td>
            </tr>
            <?php
            $i=1;
            $sql = "select ID,Name,Phone,QQnum,Mail,Content,IP,Url,Date from ".DBPREFIX."web_contact_data  order by ID desc";//ORDER BY ID DESC ";
            $result = mysqli_query($dbLink,$sql);
            while ($row = mysqli_fetch_assoc($result)){
            $date=strtotime($row['Date']);
            $datetime=date("Y-m-d",$date).'<br>'.date("H:i:s",$date);
            ?>
            <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>

                <td align="center"><?php echo $i?></td>
                <td align="center"><font color=red><?php echo $row['Name']?></font></td>
                <td align="center"><?php echo $datetime?></td>
                <td align="left"><textarea cols="25" rows="3" wrap="PHYSICAL"><?php echo $row['Content']?></textarea></td>
                <td align="center"><?php echo $row['Phone']?></td>
                <td align="center"><?php echo $row['QQnum']?></td>
                <td align="center"><?php echo $row['Mail']?></td>
                <td align="center"><?php echo $row['IP']?></td>
                <td align="center"><?php echo $row['Url']?></td>
                <td align="center"><a class="a_link" href="javascript:;" onclick="deleteMsg(this,'news.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&active=del&langx=<?php echo $langx?>&action=sitenews')">删除</a></td>
                <?php
                $i=$i+1;
                }
                ?>
            </tr>
        </table>

        <?php
    }
    ?>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script>
    // 验证表单输入
    function checkinput() {
        var $account =document.form_message.name ;
        var $content =document.form_message.scoll_news ;
        var account = trim($account.value) ;  // 帐号
        var content = trim($content.value) ;  // 短信内容
        if(account && !isNum(account)){
            alert(configmsg.accountmsg) ;
            $account.focus() ;
            return false;
        }
        if(content =='' ){
            alert(configmsg.txtmsg) ;
            $content.focus() ;
            return false;
        }
    }
    // 删除短信
    function deleteMsg(obj,url) {
        layer.confirm('确认删除此条短信吗？', {
            title:'提示',
            btn: ['确定','取消'], //按钮
            yes: function(index, layero){
                parent.main.location = url;
                layer.close(index);
                //按钮【按钮一】的回调
            },
            cancel: function(){
                falg = false;
                //右上角关闭回调
            },
        });
    }

    function pushMessage(id, comment) {
        var messageJson =  '<?php echo $messagesJson; ?>';
        var messageInfo = messageJson[id];
        var message = messageInfo.Message;
        var UserName = messageInfo.userid;
        if( messageInfo.MsType == 0 ) var title = "系统短信";
        if( messageInfo.MsType == 1 ) var title = "存款公告";
        if( messageInfo.MsType == 2 ) var title = "取款公告";
        messageJson = JSON.stringify(messageInfo);
        exampleSocket.check_send(messageJson);location.reload();
    }

    var exampleSocket = new WebSocket("<?php echo WS_HEAD;?>://<?php echo WEBSOCKET_IP; ?>");
    exampleSocket.check_send = function (message, callback) {
        exampleSocket.send(message);
        //exampleSocket.waitForConnection(function () {
        //exampleSocket.send(message);
        // }, 1000);
    };

    exampleSocket.onmessage = function (event) {
        console.log(event.data);
    }

    exampleSocket.waitForConnection = function (callback, interval) {
        if (exampleSocket.readyState === 1) {
            callback();
        } else {
            var that = this;
            // optional: implement backoff for interval here
            setTimeout(function () {
                exampleSocket.waitForConnection(callback, interval);
            }, interval);
        }
    };

</script>
</body>
</html>