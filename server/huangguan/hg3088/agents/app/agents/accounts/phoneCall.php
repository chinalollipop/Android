<?php
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
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$name=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

$active = $_REQUEST['active']; // 审核操作
$date_start =$_REQUEST['date_start'];
$date_end = $_REQUEST['date_end'];
$page=$_REQUEST["page"];
$date=date('Y-m-d H:i:s');
$today=date('Y-m-d');
$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
$seausername = isset($_REQUEST['seausername'])?$_REQUEST['seausername']:''; // 查询用户名
$userid = isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
$Checked = isset($_REQUEST['Checked'])?$_REQUEST['Checked']:'';
$Notes = isset($_REQUEST['Notes'])?$_REQUEST['Notes']:'';
$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

$redisObj = new Ciredis();

if ($page==''){
    $page=0;
}
if ($date_start==''){
    $date_start = $today;
}
if ($date_end==''){
    $date_end=date('Y-m-d', time()+86400);
}
// 统计今天处理回访申请笔数, 审核状态 0 首次提交 1 已回访 2 后期回访 -1拒绝回访
$phonecallcheck = $_REQUEST['phonecallcheck'] ;
if ($phonecallcheck =="call"){
    $data=[];
    // 电话回访笔数
    $result = mysqli_query($dbLink,"select count(1) as cou from ".DBPREFIX."web_member_phonecall where Checked=0 and Type='P' and  AddDate>='$date_start' and  AddDate<='$date_end'");
    $row = mysqli_fetch_assoc($result);
    if($result){
        $data['phonecall_num']=$row['cou'];
        $status = '200';
        $describe = '请求数据成功。';
        original_phone_request_response($status,$describe,$data);
    }else{
        $status = '500';
        $describe = '请求数据失败。';
        original_phone_request_response($status,$describe,$data);
    }

}


if ($active=='Y') { // 审核操作
   $mysql="update ".DBPREFIX."web_member_phonecall set Checked='".$Checked."',Notes='".$Notes."',callUser='$name',AuditDate='$date' where ID=".$id;
   $result = mysqli_query($dbMasterLink,$mysql);
    $setdata = array(
        'day'=> $today ,
        'status'=> $Checked ,
    );

   $redisObj->setOne('phone_call_key_'.$userid,json_encode($setdata)) ; // 更新redis  状态
   $loginfo = $name.' 对会员帐号 <font class="green">'.$username.'</font> 进行了电话回访' ;

}
if($seausername){
    $where .="and UserName='$seausername'" ;
}else{
    $where ='';
}
// 查询数据
$sql="select ID,userid,Checked,UserName,Date,AddDate,AuditDate,callUser,Phone,Notes from ".DBPREFIX."web_member_phonecall where Type='P' and  AddDate>='$date_start' and  AddDate<='$date_end' $where ORDER BY ID DESC";
// echo $sql;
$result = mysqli_query($dbLink,$sql);
$num=0;
$page_size=50;
$data=[];
while ($row = mysqli_fetch_assoc($result)) {
    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
        $data[]=$row;
    }
    $num+=1;
}
$cou = $num;
$page_count=ceil($cou/$page_size);

if ($cou==0){
    $page_count=1;
}


?>
    <html>
    <head>
        <title>电话回访</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    </head>
    <body >
    <dl class="main-nav">
        <dt>电话回访审核</dt>
        <dd>
            <table >
                <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
                    <tr class="m_tline">
                        <td>
                            &nbsp;时间:
                            <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_start?>" onclick="laydate({istime: false, istoday: false,format: 'YYYY-MM-DD'})" >
                            ~
                            <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto text_time" value="<?php echo $date_end?>" onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" >
                        </td>
                        <td>&nbsp;
                            <input type="text" id="seausername" name="seausername" placeholder="查询用户名" value="<?php echo $_REQUEST['seausername'];?>">
                        </td>
                        <td > &nbsp;
                            <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
                        </td>
                        <td>
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
                        </td>
                        <td> <?php echo $page_count?> 页</td>
                    </tr>

                </FORM>
            </table>
        </dd>
    </dl>
    <div class="main-ui width_1300">
        <table class="m_tab">
            <tr class="m_title">
                <td >会员帐号</td>
                <td >提交电话</td>
                <td >提交时间</td>
                <td >状态</td>
                <td >审核</td>
                <td >备注</td>
            </tr>
            <!-- BEGIN DYNAMIC BLOCK: row -->
            <?php
            if ($cou==0){
                ?>
                <tr class="m_cen">
                    <td colspan="11">目前沒有记录</td>
                </tr>
                <?php
            }else{
                foreach ($data as $k => $row){
                    ?>

                    <tr class="m_cen">
                        <td><b><?php echo $row['UserName']?></b></td>
                        <td><?php echo $row['Phone']?></td>
                        <td><?php echo $row['Date']?></td>

                        <td>
                            <?php
                            if($row['Checked']==1)
                            {
                                echo "<font style='color:green'>已回访</font>";
                            }
                            else if($row['Checked']==0) { // 未审核
                                echo "<font style='color:blue'>审核中</font>";
                            }
                            else if($row['Checked']==-1) {
                                echo "<font style='color:red'>拒绝回访 ".$row['Notes']."</font>";
                            }
                            ?>
                        </td>

                        <td width="130" class="check_action">
                            <?php
                            if($row['Checked']==0)
                            {
                            ?>
                            <form  method=post target='_self' style="margin:0px; padding:0px;">
                                <input name="Checked" type="radio" value="1" checked> 确定回访 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!--    <input name="Checked" type="radio" value="2" > 后期回访 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input name="Checked" type="radio" value="-1">拒绝回访-->
                                <input name="Notes" type="text" size=10 class="za_text"><br> <!-- 理由 -->
                                <input type=submit name=send value='提交' onClick="return confirm('确定审核此笔订单？')" class="za_button">
                                <input type=hidden name=id value=<?php echo $row['ID']?>>
                                <input type=hidden name=username value=<?php echo $row['UserName']?>>
                                <input type=hidden name=userid value=<?php echo $row['userid']?>>
                                <input type=hidden name=date_start value=<?php echo $date_start?>>
                                <input type=hidden name=date_end value=<?php echo $date_end?>>
                                <input type=hidden name=active id="active" value=Y></td>
                        </form>
                        <?php
                        }
                        else
                        {
                            echo $row['callUser']."<br>".$row['AuditDate'];
                        }
                        ?>

                        </td>
                        <td>
                            <textarea name="Notes" ID="Notes_<?php echo $row['userid']?>" rows="3" cols="20" ><?php echo $row['Notes']; ?></textarea>
                        </td>
                    </tr>

                    <?php
                }
            }
            ?>
            <!-- END DYNAMIC BLOCK: row -->

        </table>

    </div>

    <script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
    <script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
    <script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript">

        setBodyScroll() ;
    </script>
    </body>
    </html>
    <!-- 插入系统日志 -->
<?php
if ($active=='Y' ){ // 有操作才需要插入
    innsertSystemLog($name,$lv,$loginfo);
    echo "<script>parent.main.location.href='phoneCall.php?uid=$uid&langx=$langx&lv=$lv'</script>";
}
?>