<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$userid = $_SESSION['userid'];
$userName = $_SESSION['UserName'];

$redisObj = new Ciredis();

require ("../include/traditional.$langx.inc.php");

//当前是否请求发信箱默认为收件箱
$webPageCur = isset($_REQUEST['cur']) && $_REQUEST['cur']=='sendmails' ? 1:0;

//获取系统消息
$messages = [];
$pageSize = 8;
$pageNum = isset($_REQUEST['page']) && $_REQUEST['page']>0 ? $_REQUEST['page']:1;
$sql = "select ID,UserName,Time,$message as Message,MsType,BeginTime,EndTime from ".DBPREFIX."web_message_data where ( UserName='".$userName."' or UserName='ALL' ) order by Time desc limit ".(($pageNum - 1) * $pageSize) . "," . $pageSize;
$result = mysqli_query($dbLink,$sql);
$totalSql = "select ID from ".DBPREFIX."web_message_data where ( UserName='".$userName."' or UserName='ALL' )";
$totalResult = mysqli_query($dbLink,$totalSql);
$totalCount = mysqli_num_rows($totalResult);
$endPage = ceil($totalCount/$pageSize);


//未读消息数量
$datetimeCur = date('Y-m-d H:i:s',time());
$sqlMessageCur = "select id from ".DBPREFIX."web_message_data where ( UserName='".$userName."' or UserName='ALL' ) and BeginTime < '$datetimeCur'  and EndTime > '$datetimeCur' ";
$resultMessageCur = mysqli_query($dbLink,$sqlMessageCur);
$numMessageCur = mysqli_num_rows($resultMessageCur);

if( $numMessageCur > 0 ){
    $readCountkey = 'Message_readinbox_'.$userid;
    $userMailRead = $redisObj->getSimpleOne($readCountkey);
    $userMailReadArr=[];
    if( strlen($userMailRead)>0 ){
        $userMailReadArr = explode(':',$userMailRead);
    }
    //var_dump($userMailReadArr);
    while ($rowUnRead = mysqli_fetch_assoc($resultMessageCur)) {
        if(in_array($rowUnRead['id'],$userMailReadArr)){ $numMessageCur = $numMessageCur-1; }
    }
}else{//删除已度过的id记录
    $readCountkey = 'Message_readinbox_'.$userid;
    $redisObj->delete($readCountkey);
}

//获取发送信息
$sendmails = [];
$pageNumS = isset($_REQUEST['pageS']) && $_REQUEST['pageS']>0 ? $_REQUEST['pageS']:1;
$sqlSendMails = "select id,userid,username,title,message,time,type from ".DBPREFIX."web_sendmail_data where Userid={$_SESSION['userid']}  order by id desc limit ".(($pageNumS - 1) * $pageSize) . "," . $pageSize;
$resultSendMails = mysqli_query($dbLink,$sqlSendMails);

$totalSendSql = "select id from ".DBPREFIX."web_sendmail_data where Userid={$_SESSION['userid']}";
$totalSendResult = mysqli_query($dbLink,$totalSendSql);
$numSendMails = mysqli_num_rows($totalSendResult);
$endPageS = ceil($numSendMails/$pageSize);

?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>消息中心</title>
    <link href="../../../style/message/ui-dialog.css?v=<?php echo AUTOVER; ?>" type="text/css" rel="stylesheet">
    <link href="../../../style/message/message.css?v=<?php echo AUTOVER; ?>" type="text/css" rel="stylesheet">
</head>
<body>
<div class="account-content pg-promocode">
    <div class="a-t-b"><span class="a-t-t">消息中心</span></div>
    <div class="mcj_a">
        <div class="mcj_b">
            <ul class="mcj_c m-module">
                <li id="tab0" class="active" data-type="get" data-user-id="48156">
                    <div class="logo-msgbox-r"></div>
                    <div class="mcj_d">收件箱</div>
                    <div id="receive-newcounts"><? echo $numMessageCur; ?></div>
                </li>
                <li id="tab1" class="" data-type="getS" data-user-id="48156">
                    <div class="logo-msgbox-s"></div>
                    <div class="mcj_d">发件箱</div>
                </li>
                <li id="tab2" class="">
                    <div class="logo-msgbox-n"></div>
                    <div class="mcj_d">发送新消息</div>
                </li>
            </ul>
        </div>
        <div class="mcj_e">
            <!-- 收件箱 -->
            <div class="mcj_f m-item active" id="inbox" >
                <!-- 信息列表 -->
                <ul class="mcj_g" >
                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        $title = '';
                        if( $row['MsType'] == 0 ){ $title="系统短信";}if( $row['MsType'] == 1 ){ $title="存款公告";}if( $row['MsType'] == 2 ){ $title="取款公告";}
                        ?>
                        <li class="sxx_li <?php if(in_array($row['ID'],$userMailReadArr)){ echo 'mem_readed';}?>">
                            <div class="clearfix"><div class="mcj_h"></div>
                                <div class="mcj_i"></div>
                                <div class="mcj_j">
                                    <a href="#" data-title="<?php echo $title;?>" data-content="<?php echo $row['Message']; ?>" data-time="<?php echo $row['Time'];?>" data-id="<?php echo $row['ID'];?>"><?php echo $title;?></a>
                                </div>
                                <div class="mcj_k"><?php echo $row['Time'];?></div>
                            </div>
                        </li>
                    <?php }?>
                </ul>
                <!-- 分页、信息删除 -->
                <div class="mcj_l" style="">
                    <div class="page page-inbox light-theme simple-pagination" style="width: 60%; display: block;">
                        <ul id="inboxPage">当前第&nbsp;&nbsp;
                            <select id="mailsPages">
                                <?php for($i=1;$i<=$endPage;$i++){?>
                                    <option value=<?php echo $i; ?> <?php if($i==$pageNum){ echo 'selected'; }?> ><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            &nbsp;&nbsp;页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<em><?php echo $endPage;?></em>页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<em><?php echo $totalCount;?></em>条记录</ul>
                    </div>
                </div>
            </div>
            <!-- 发件箱 -->
            <div class="mcj_f m-item" id="outbox">
                <!-- 信息列表 -->
                <ul class="mcj_g" id="outboxInfos">
                    <?php if($numSendMails==0){?>
                        <h3 class="message-no">正在读取您的消息哦~</h3>
                    <?php }else{
                    while ($rowSend = mysqli_fetch_assoc($resultSendMails)) {
                        $checkReplyResult = $checkReplyRow = '';
                        $checkReplyResult = mysqli_query($dbLink,"select isAdmin from ".DBPREFIX."web_sendmail_reply_data where topid=".$rowSend['id']." ORDER BY `time` DESC LIMIT 1");
                        $checkReplyRow = mysqli_fetch_assoc($checkReplyResult);
                        $titleSend = '';
                        if($rowSend['type']==1){ $titleSend='财务问题'; }
                        if($rowSend['type']==2){ $titleSend='技术问题'; }
                        if($rowSend['type']==3){ $titleSend='业务咨询'; }
                        if($rowSend['type']==4){ $titleSend='意见建议'; }
                        if($rowSend['type']==5){ $titleSend='其他问题'; }
                    ?>
                        <li class="">
                            <div class="clearfix"><div class="mcj_h"></div><div class="mcj_i"></div><div class="mcj_j"><a href="#" data-title="<?php echo $rowSend['']; ?>" data-content="<?php echo $rowSend['message']; ?>" data-time="<?php echo $rowSend['time'];?>" data-id="<?php echo $rowSend['id'];?>"><?php echo '【'.$titleSend.'】'.$rowSend['title'];?></a><?php if($checkReplyRow['isAdmin']==1){ echo "<font color='red'><strong>&nbsp;new</strong></font>"; }?></div><div class="mcj_k"><?php echo $rowSend['time'];?></div></div>
                        </li>
                    <?php }
                    }
                    ?>
                </ul>
                <!-- 分页、信息删除 -->
                <div class="mcj_l" style="">
                    <div class="page page-inbox light-theme simple-pagination" style="width: 60%; display: block;">
                        <ul id="inboxPage">当前第&nbsp;&nbsp;
                            <select id="sendMailsPages">
                                <?php for($j=1;$j<=$endPageS;$j++){?>
                                    <option value=<?php echo $j; ?> <?php if($j==$pageNumS){ echo 'selected'; }?> ><?php echo $j; ?></option>
                                <?php } ?>
                            </select>
                            &nbsp;&nbsp;页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<em><?php echo $endPageS;?></em>页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<em><?php echo $numSendMails;?></em>条记录</ul>
                    </div>
                </div>
            </div>
            <!-- 发送新消息 -->
            <div class="m-item">
                <form class="mc-content">
                    <input type="hidden" name="action" id="action" value="create">
                    <input type="hidden" name="addpople" id="addpople" value="48156">
                    <div class="mcj_o">
                        <div class="mcj_p">发送至:</div>
                        <div class="mcj_q sl-2">
                            <select class="select" name="msgType" id="msgType">
                                <option value="1">财务问题</option>
                                <option value="2">技术问题</option>
                                <option value="3">业务咨询</option>
                                <option value="4">意见建议</option>
                                <option value="5">其他问题</option>
                            </select>
                        </div>
                        <div class="mc-qtips">存款未到账者请留下转账姓名，转账时间和转账金额，我们会尽快为您办理。</div>
                    </div>
                    <div class="mcj_title">
                        <div class="k">标题:</div>
                        <div class="v"><input type="text" name="msgTitle" id="msgTitle" autocomplete="off" maxlength="50" placeholder="最长50个字符"></div>
                    </div>
                    <div class="mcj_r">
                        <div class="mcj_u">内容:</div>
                        <div class="mcj_s"><textarea class="mcj_t" name="msgContent" id="msgContent" maxlength="1000" placeholder="请详细描述您要咨询的问题，我们的客服人员会及时的回复您的消息，谢谢！（限1000个中文字符）"></textarea></div>
                    </div>
                    <div class="mcj_v">
                        <div class="mcj_w"></div>
                        <button class="btn-gray-2 mcj_x" id="btn-send">
                            <span>发送</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../../js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../js/plugin/dialog-plus-min.js?v=<?php echo AUTOVER; ?>" type="text/javascript" charset="utf-8"></script>
<script src="../../../js/message.js?v=<?php echo AUTOVER; ?>" type="text/javascript" charset="utf-8"></script>
<script>
    var $userId ='<?php echo $uid ?>';

    $('#mailsPages').change(function(){
        var pageCur = <?php echo $pageNum; ?> ;
        var pageTo = $(this).children('option:selected').val();
        if(pageTo!=pageCur){
            window.location.href="message.php?uid="+$userId+"&page="+pageTo+"&langx=zh-cn";
        }
    })

    $('#sendMailsPages').change(function(){
        var pageCurS = <?php echo $pageNumS; ?> ;
        var pageToS = $(this).children('option:selected').val();
        //if(pageToS!=pageCurS){
            var datapars = {type :'sendMailsPage',pageS :pageToS,pageNum :8};
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                dataType: 'json',
                data: datapars ,
                success: function (res) {
                    //var infosObj = JSON.parse(res));
                    var infosHTML = '';
                    $.each(res, function(idx, obj) {
                        //console.log(obj);
                        var titleSend=isNew='';
                        if(obj.type==1){ titleSend='财务问题'; }
                        if(obj.type==2){ titleSend='技术问题'; }
                        if(obj.type==3){ titleSend='业务咨询'; }
                        if(obj.type==4){ titleSend='意见建议'; }
                        if(obj.type==5){ titleSend='其他问题'; }

                        if(obj.isAdmin==1){ isNew ="<font color='red'><strong>&nbsp;new</strong></font>"; }
                        infosHTML += "<li class=''><div class='clearfix'><div class='mcj_h'></div><div class='mcj_i'></div><div class='mcj_j'><a href='#' data-title='"+obj.title+"' data-content='"+obj.message+"' data-time='"+obj.time+"' data-id="+obj.id+">【"+titleSend+'】'+obj.title+"</a>"+isNew+"</div><div class='mcj_k'>"+obj.time+"</div></div> </li>";
                        $("#outboxInfos").html(infosHTML);
                    });
                }
            })
        //}
    })

</script>
</body></html>