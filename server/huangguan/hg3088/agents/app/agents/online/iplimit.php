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

// 管理员登录
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];
$loginname=$_SESSION['UserName'];
$username=$_REQUEST['username'];
$level=$_REQUEST['level'];
$lv=$_REQUEST['lv'];
$tip = '登录成功！！';
$date=date('Y-m-d');
$iptype = isset($_REQUEST['iptype'])?$_REQUEST['iptype']:'' ;
$iplist = isset($_REQUEST['iplist'])?$_REQUEST['iplist']:'' ;
$iplist = str_replace("\r\n","",$iplist);
$iplist = str_replace(" ","",$iplist);

$ipdata = array(
    'type'=>$iptype,
    'list'=>$iplist,
);

$redisObj = new Ciredis();
if($iplist || $iptype){
    $redisObj->setOne('font_ip_limit',json_encode($ipdata)) ;
}

$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;

//var_dump($datastr)  ;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>IP查询</title>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<STYLE type="text/css">
input{min-height: auto;}
.content-textarea {
    width: 50%;
    height: 120px;
    padding: 10px;
    border: 1px solid #727272;
    border-radius: 5px;
    background: rgba(0,0,0,0.2);
    line-height: 150%;
}
    .tj_btn{width: 80px;}
    .m_title p{line-height: 23px;}
</STYLE>
</head>

<body >
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>IP限制管理</dt>
        <dd>

   </dd>
 </dl>
</FORM>
<div class="main-ui">
    <form action="iplimit.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $level?>"  method="post" name="form1" >
        <table class="m_tab">
            <thead>
              <tr  class="m_title">
                <td >序号</td>
                <td >限制位置</td>
                <td >限制IP</td>
              </tr>
            </thead>
            <tbody>
            <tr  class="m_title">
                <td>1</td>
                <td >
                    <!-- type : 1 全站，2 登录，3 注册，4 登录/注册 -->
                    <p> 全站 <input type="checkbox" name="iptype" value="1" <?php echo $datastr['type']==1?'checked':'';?> ></p>
                    <p> 登录 <input type="checkbox" name="iptype" value="2" <?php echo $datastr['type']==2?'checked':'';?> ></p>
                    <p> 注册 <input type="checkbox" name="iptype" value="3" <?php echo $datastr['type']==3?'checked':'';?> ></p>
                    <p> 登录/注册 <input type="checkbox" name="iptype" value="4" <?php echo $datastr['type']==4?'checked':'';?> ></p>
                </td>
                <td >
                    <p style="color: red">多个IP之间用英文字符;分隔</p>
                    <textarea name="iplist" class="content-textarea" placeholder="多个IP之间用英文字符;分隔"><?php echo $datastr['list'];?></textarea>
                    <input type="submit" class="tj_btn za_button" value="提交" />
                </td>
            </tr>
            </tbody>
      </table>
    </form>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script>
    $(function(){
        $(":checkbox").click(function(){
            // 设置当前选中checkbox的状态为checked
            $(this).attr("checked",true);
            $(this).parent('p').siblings().find('input').attr("checked",false); //设置当前选中的checkbox同级(兄弟级)其他checkbox状态为未选中
        });
    });

</script>

</body>
</html>