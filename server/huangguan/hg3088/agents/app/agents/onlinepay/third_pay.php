<?php
//ini_set("display_errors","On");
//error_reporting(E_ALL);

session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");    
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");    
header("Cache-Control: no-store, no-cache, must-revalidate");    
header("Cache-Control: post-check=0, pre-check=0", false);    
header("Pragma: no-cache"); 
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$date_start = isset($_REQUEST['date_start'])?$_REQUEST['date_start'] : date('Y-m-d');
$date_end = isset($_REQUEST['date_end'])?$_REQUEST['date_end'] : date('Y-m-d 23:59:59');

$lv=$_REQUEST['lv']; //插入日志
$admin_sub_user = ADMIN_SUB_USER;

$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : ''; // 账号
$order_number = isset($_REQUEST['order_number']) ? $_REQUEST['order_number'] : ''; // 系统订单号
$sourceSearch = isset($_REQUEST['source']) ? $_REQUEST['source'] : ''; // 来源
$gxfcyPayId = isset($_REQUEST['gxfcyPayId']) ? $_REQUEST['gxfcyPayId'] : ''; // 三方支付

if(!empty($username)) {
    $where.=" and UserName= '" . $username ."'";
}
if(!empty($order_number)) {
    $where.=" and Order_Code LIKE '%" . $order_number ."%'";
}
if(!empty($sourceSearch)) {
    $where.=" and playSource= '" . $sourceSearch ."'";
}
if(!empty($gxfcyPayId)) {
    $where.=" and PayType= '" . $gxfcyPayId ."'";
}

if ($page==''){
    $page=0;
}

//投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓
$playSourceData = [0=>'未知', 1=>'旧版', 2=>'新版',3=>'ios', 4=>'android', 5=>'新版',13=>'ios原生', 14=>'android原生', 22=>'综合版'];


// 当前第三方数据
$thirdSql = "SELECT id,title,account_company,thirdpay_code from ".DBPREFIX."gxfcy_pay  where status=1";
$thirdResult = mysqli_query($dbLink,$thirdSql);

// 三方订单查询
$sql="select userid,UserName,Alias,merchantName,PayType,PayName,thirdpay_code,Order_Code,thirdSysOrder,Gold,UserTime,CallbackTime,Status,playSource,ip from ".DBPREFIX."web_thirdpay_data where UserTime>='$date_start' and UserTime<='$date_end' $where  ORDER BY ID DESC";
//echo '<br>';
//echo $sql;
$result = mysqli_query($dbLink,$sql);

$gold_total=0;
$num=0;
$page_size=50;
$page=$_REQUEST['page'];
$data=[];
while ($row = mysqli_fetch_array($result)) {
    if($row['thirdpay_code'] == 'wdf') { // 维多付
        $aBanklist = array(
            'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipay'=>'支付宝转卡','w_alipayqr'=>'支付宝转支','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',
        );
    }
    if($row['thirdpay_code'] == 'clzldz') { // 村里最靓的仔
        $aBanklist = array(
            '920'=>'银联闪付',
        );
    }

    if($row['thirdpay_code'] == 'ccx') { // 璀璨星
        $aBanklist = array(
            '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'综合支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
        );
    }

    if($row['thirdpay_code'] == 'csj') { // 创世纪
        $aBanklist = array(
            '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
        );
    }

    if($row['thirdpay_code'] == 'autopay') { // autopay
        $aBanklist = array(
            '1'=>'支付宝扫码','2'=>'支付宝APP','3'=>'微信扫码','4'=>'微信APP','5'=>'网银支付',
        );
    }
    $row['PayFromBank'] = $aBanklist[$row['PayName']];

    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
        $data[]=$row;
    }
    $num+=1;
}
$cou=$num;
$page_count=ceil($cou/$page_size);
if ($cou==0){
    $page_count=1;
}
?>

<html>
<head>
<title>第三方订单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    input.za_text_auto{width: 100px;}
    .m_title{ font-weight: bold;}
</style>
</head>
<body >
<dl class="main-nav">
    <dt>第三方订单</dt>
    <dd>
        <table >
          <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
          <tr class="m_tline">
                <td>
                    会员账号：<input type="text" class="za_text_auto" name="username" value="<?php echo $username;?>">&nbsp;&nbsp;
                    订单号：<input type="text" size=20 maxlength=20 class="za_text_auto" name="order_number" value="<?php echo $order_number;?>">&nbsp;&nbsp;
                    &nbsp;--&nbsp;回调日期区间:
                    <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_start?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                    ~&nbsp;
                    <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                    --
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
                    </SELECT>&nbsp;&nbsp;&nbsp;&nbsp;
                    三方支付:
                    <select name="gxfcyPayId" id="type" class="za_select za_select_auto" onchange="">
                        <option value="">全部</option>
                        <?php
                        while ($row = mysqli_fetch_array($thirdResult)){
                            if($row[id] == $gxfcyPayId) {
                                echo "<option value='$row[id]' selected>".$row['title']."</option>";
                            }else {
                                echo "<option value='$row[id]'>".$row['title']."</option>";
                            }
                        }
                        ?>
                    </select>
                 &nbsp;
                      <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
                    &nbsp;--&nbsp;总页数:
                      <select id="page" name="page"  class="za_select za_select_auto" onChange="self.myFORM.submit()">
                      <?php
                      if ($page_count==0){
                          $page_count=1;
                      }
                      for($i=0;$i<$page_count;$i++){
                        if($page == $i){
                            echo "<option selected value='$i'>".($i+1)."</option>";
                        }else{
                            echo "<option  value='$i'>".($i+1)."</option>";
                        }

                      }
                      ?>
                      </select>
                     / <?php echo $page_count?> 页</td>
          </tr>

        </FORM>
        </table>
    </dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title">
          <td>商户名称</td>
          <td>支付通道</td>
          <td>商户订单号</td>
          <td>三方系统订单号</td>
          <td>会员账号</td>
          <td>充值金额</td>
          <td>用户提交时间</td>
          <td>回调时间</td>
          <td>状态</td>
          <td>充值终端</td>
          <td>用户IP地址</td>
        </tr>
    <?php
    if ($cou==0){
    ?>
        <tr class="m_cen">
            <td colspan="10">目前沒有记录</td>
        </tr>
    <?php
    }else{
		foreach($data as $key=>$row) {

        ?>
                <tr class="m_cen">
                  <td><b><?php echo $row['merchantName']?></b></td>
                  <td><b><?php echo $row['PayFromBank']?></b></td>
                  <td><?php echo $row['Order_Code'];?></td>
                  <td align="left"><?php echo isset($row['thirdSysOrder']) ? $row['thirdSysOrder'] : '';?></td>
                  <td><?php echo $row['UserName'];?></td>
                  <td><?php echo sprintf("%01.2f", $row['Gold'])?></td>
                  <td><?php echo $row['UserTime'];?></td>
                  <td><?php echo !empty($row['CallbackTime']) ? $row['CallbackTime'] : '';?></td>
                  <td><?php
                      if($row['Status'] == 0) {
                          echo '未支付';
                      }elseif($row['Status'] == 1) {
                          echo '回调成功,已支付';
                      }elseif($row['Status'] == 2) {
                          echo '处理中';
                      }?></td>
                  <td><?php echo $playSourceData[$row['playSource']];?></td>
                  <td><?php echo $row['ip'];?></td>
                </tr>

        <?php
        }
    }
    ?>
          </table>

</div>
<?php
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$tomorrow=date('Y-m-d',time()+86400);
?>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="JavaScript">
    var yesterday = '<?php echo $yeterday?>';
    var today = '<?php echo $today?>';
    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( str ) {
        var date_start;
        var date_end;
        switch (str){
            case 'yeterday':
                date_start = yesterday;
                date_end = yesterday;
                break;
            case 'today':
                date_start = today;
                date_end = today;
                break;
        }
        var url = 'user_list_800_2018.php';
        var username = '<?php echo $seach_name;?>';
        var type = '<?php echo $_POST['type'];?>';
        var save_type = '<?php echo $s_type;?>';
        var page = '<?php echo $page;?>';

        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='date_end' value='"+date_end+"'>");
        form.append("<input type='hidden' name='seach_name' value='"+username+"'>");
        form.append("<input type='hidden' name='type' value='"+type+"'>");
        form.append("<input type='hidden' name='save_type' value='"+save_type+"'>");
        form.append("<input type='hidden' name='page' value='"+page+"'>");
        $(document.body).append(form);
        form.submit();

    }

    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
        if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
        else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    function MM_findObj(n, d) { //v4.0
        var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
            d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
        if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
        for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
        if(!x && document.getElementById) x=document.getElementById(n); return x;
    }
</script>
</body>
</html>
    <!-- 插入系统日志 -->
<?php
//if ($active=='Y'){ // 有操作才需要插入
//    innsertSystemLog($loginname,$lv,$loginfo);
//}
?>