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
$adminid=$_SESSION['ID'];
require ("../include/traditional.$langx.inc.php");
$id=$_REQUEST['id'];
$name= str_replace(' ','',$_REQUEST["name"]); // 去除空格
$form_action=$_REQUEST['form_action'];
$action=$_REQUEST["action"];
$active=$_REQUEST["active"];
$lv = $_REQUEST['lv'];
$todaydate=date('Y-m-d');
$tomorrowdate=date('Y-m-d',strtotime('1 day'));
$todaytime=date('Y-m-d H:i:s');

if( isset($_REQUEST['action']) && $_REQUEST['action']=='details' ){

    if( isset($_REQUEST['id']) && $_REQUEST['id'] > 0){
        $messages = [];
        $topId=$_REQUEST['id'];
        $sql = "select id,username,title,message,`time`,`type` from ".DBPREFIX."web_sendmail_data where id=".$_REQUEST['id'];
        $result = mysqli_query($dbLink,$sql);
        $rowTop = mysqli_fetch_assoc($result);
        $messages[] = $rowTop;
        $detailSql = "select id,topid,lastid,userid,username,message,time,isAdmin from ".DBPREFIX."web_sendmail_reply_data where topid=".$topId." order by time";
        $detailResult = mysqli_query($dbLink,$detailSql);
        while ($detailsRow = mysqli_fetch_assoc($detailResult)){
            $detailsRow['type']='';
            $detailsRow['title']='';
            $messages[] = $detailsRow;
        }
    }else{
        echo "<script>alert('参数缺失!');self.location='usermails.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }

/*    echo '<pre>';
    print_r($messages);
    echo '<br/>';*/

}

//回复
if( isset($_REQUEST['action']) && $_REQUEST['action']=='reply'){
    if( isset($_REQUEST['id']) && $_REQUEST['id'] > 0 && isset($_REQUEST['topid']) && $_REQUEST['topid'] > 0){
        $lastId = $_REQUEST['id'];
        $topId = $_REQUEST['topid'];
        $message = $_REQUEST['text'];
        $mysqlRelay = "insert into ".DBPREFIX."web_sendmail_reply_data(topid,lastid,userid,username,message,time,isAdmin) values ($topId,$lastId,$adminid,'$loginname','$message','".date('Y-m-d H:i:s',time())."',1)";
        if(mysqli_query($dbMasterLink,$mysqlRelay)){
            echo '操作成功！';exit;
        }else{
            return '操作失败！';exit;
        }
    }else{
        return "参数缺失!";exit;
    }
}

?>
<html>
<head>
    <title>会员发来的消息</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    /*.m_tab{table-layout:fixed}*/
    .m_tab td{width: 10%;}
    .m_tab td:nth-child(3){width: 70%;}
</style>
</head>
<body  >
<FORM id="myFORM" ACTION="" METHOD=POST name="myFORM" >
    <input type='hidden' name='uid' value='<?php echo $uid;?>'>
<dl class="main-nav">
    <table>
        <tr class="m_tline">
            <td><div class="query_bet_title">会员消息详情</div></td>
            <td><div style="float:right;"><a class="a_link" href="usermails.php?langx=zh-cn&lv=M" target="main">返回消息列表</a></div></td>
        </tr>
    </table>
</dl>
</FORM>
<div class="main-ui" style ="">
        <table class="m_tab">
            <tr class="m_title">
                <td>编号</td>
                <td>发信人</td>
                <td>内容</td>
                <td>时间</td>
            </tr>
            <?php foreach($messages as $key=>$row){ ?>
                <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
                    <td align="center"><?php echo $row['id'];?></td>
                    <?php if($row['isAdmin']==1){?>
                        <td ><?php echo $row['username']; ?><span style="color:red;">【admin】</span></td>
                    <?php }else{?>
                        <td ><?php echo $row['username']; ?></td>
                    <?php }?>
                    <td align="left"><?php
                                        $type = '';
                                        if($row['type']==1){ $type='财务问题'; }
                                        if($row['type']==2){ $type='技术问题'; }
                                        if($row['type']==3){ $type='业务咨询'; }
                                        if($row['type']==4){ $type='意见建议'; }
                                        if($row['type']==5){ $type='其他问题'; }
                                            if(strlen($type)>0){echo '【'.$type.'__'.$row['title'].'】<br/><br/>';}
                                            echo $row['message'];
                                        ?>
                    </td>
                    <td align="center"><?php echo $row['time'];?></td>
                </tr>
            <?php } ?>
            <?php if($row['isAdmin']==0){?>
            <tr>
                <td align="center"> / </td>
                <td align="right"> <?php echo $loginname; ?><span style="color:red;">【admin】</span </td>
                <td align="left" >
                    <textarea id=<?php echo 'replay_'.$topId.'_'.$row['id'] ?> rows="3" cols="130" ></textarea>
                </td>
                <td align="center">
                    <a class="a_link" href="javascript:;" onclick="replayMessage(<?php echo $topId; ?>,<?php echo $row['id'] ?>)" >回复</a>
                </td>
            </tr>
            <?php } ?>
        </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script>
    function replayMessage(topid,id){
        var replayContain = $('#replay_'+topid+'_'+id).val();
        if( replayContain=='' ){ alert('回复内容不能为空！');return false; }
        $.post("usermails_details.php", {
            uid: '<?php echo $uid; ?>',
            action: 'reply',
            topid:topid,
            id:id,
            langx:'zh-cn',
            text:replayContain
        }, function(e) {
            alert(e);
            window.location.reload();
        })
    }
</script>
</body>
</html>