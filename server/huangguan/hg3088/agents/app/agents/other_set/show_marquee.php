<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$lv=$_REQUEST['lv'];
$level=$_SESSION['Level'];
$mdate=$_REQUEST['mdate'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$msg_id = isset($_REQUEST['msg_id'])?$_REQUEST['msg_id']:'' ; // 消息 id
require ("../include/traditional.$langx.inc.php");

if ($mdate==''){
	$mdate=date('Y-m-d');
}
if($msg_id && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ){ // 删除消息
    $de_sql = "delete from ".DBPREFIX."web_marquee_data where ID='$msg_id'" ;
    // echo $de_sql;
    $de_result = mysqli_query($dbMasterLink,$de_sql);
    getScrollMsg('upd');
}

?>
<html>
<head>
<title>show_marquee</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body onselectstart="return false;">

<dl class="main-nav"><dt>历史讯息</dt><dd></dd></dl>
<div class="main-ui">
  <table border="0" cellpadding="0" cellspacing="0" class="m_tab" >
      <thead>
      <tr>
          <td width="40">编号</td>
          <td width="100">时间</td>
          <td>公告内容</td>
          <?php
              if($level !='D'){
                  echo ' <td width="40">操作</td>';
              }
          ?>

      </tr>
      </thead>
      <tbody class="tableContent">

      </tbody>
  </table>

</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js" ></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script>

    var tipadmin = '<?php echo ($level =='M' && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG)?true:false; ?>';

    getMessage();

    function deleteMsg(obj,url) {
        layer.confirm('确认删除此条公告吗？', {
            title:'提示',
            btn: ['确定','取消'], //按钮
            yes: function(index, layero){
               // parent.main.location.reload();
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

    // 获取公告
    function getMessage() {
        var url = '/api/messageApi.php';
        var $tableContent = $('.tableContent');
        $.ajax({
            type: 'POST',
            url:url,
            data:'',
            dataType:'json',
            success:function(res){
                var str = '';
                if(res.data){
                    for(var i=0;i<res.data.length;i++){
                        str +=  '<tr>'+
                                '<td align="center">'+ (i+1) +'</td>'+
                                '<td align="center">'+ res.data[i].Date +'</td>'+
                                '<td>'+ res.data[i].Message +'</td>';
                            if(tipadmin=='1'){
                                str += '<td class="delete_action">' +
                                    '  <a class="a_link delete_to" href="javascript:;" onclick="deleteMsg(this,\'show_marquee.php?lv=MEM&msg_id='+ res.data[i].ID +'\')" >删除</a>' +
                                    ' </td>';
                            }

                        str +=  '</tr> ';
                    }
                }else{
                    str += '<tr class="no_data"><td align="center" colspan="6"> 暂无消息 </td></tr>';
                }
                $tableContent.html(str);

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });
    }

</script>
</body>
</html>
<?php
if($lv=='MEM'){
$loginfo='查询会员公告'.$mdate.'';
}else{
$loginfo='查询管理公告'.$mdate.'';
}
$user_level = reAdminLevel($level) ;
innsertSystemLog($loginname,$user_level,$loginfo);
?>