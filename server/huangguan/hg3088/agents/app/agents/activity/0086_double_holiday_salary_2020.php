<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$loginname=$_SESSION['UserName'];
$page=$_REQUEST['page'];
$date=date('Y-m-d H:i:s');
$competence=$_SESSION['Competence'];
$competence_num=explode(",",$competence);

$sWhere = 1;
$search_name=$_REQUEST['username']; // 查找会员账号

$search_status = $_REQUEST['status'];
if($search_status) { // 用户状态：1审核通过,2未审核,3已拒绝
    $sWhere .= " and status = $search_status";
}else{ //查找全部
    $search_status='';
}

if ($search_name==''){ // 会员账号
    $mem="";
}else{
    $mem="and username='$search_name'";
}

$sql="select * from `".DBPREFIX."double_holiday_salary_2020` where $sWhere $sWhereTime $mem order by `id` desc";
$result = mysqli_query($dbLink,$sql);
$totalCount=0;
while ($row = mysqli_fetch_array($result)) {
    if($row['status']==1) {
        $totalCount+= $row['gift_gold']; //全部页总计
    }
}
$cou=mysqli_num_rows($result);
$page_size=100;
$page_count=ceil($cou/$page_size);
if ($page==''){
    $page=0;
}
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink,$mysql);
if ($cou==0){
    $page_count=1;
}
$aData = array();
while($row=mysqli_fetch_assoc($result)){
    $aData[]=$row;
}

?>
<html>
<head>
    <title>双节（中秋、国庆）俸禄</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>双节俸禄</dt>
    <dd>
        <!--<a  href='../../../_cli/activity/zhenren_double_holiday_salary_2020.php' target=\"main\" >派发真人双节俸禄</a>&nbsp;&nbsp;
        <a  href='../../../_cli/activity/sport_double_holiday_salary_2020.php' target=\"main\" >派发体育双节俸禄</a>-->
    </dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title"><td colspan="16"><b>双节（中秋、国庆）俸禄</b></td></tr>
        <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
            <tr>
                <td colspan="16">
                    会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $search_name;?>" maxlength=20 class="za_text">
                    审核状态：
                    <select name="status" id="type" class="za_select za_select_auto" onchange="">
                        <option value="0" <?php if($search_status ==0){ echo 'selected';}?>>全部</option>
                        <option value="1" <?php if($search_status ==1){ echo 'selected';}?>>已派发</option>
                        <option value="2" <?php if($search_status ==2){ echo 'selected';}?>>未审核</option>
                        <!--<option value="3" <?php /*if($search_status ==3){ echo 'selected';}*/?>>不符合</option>-->
                        <option value="4" <?php if($search_status ==4){ echo 'selected';}?>>已拒绝</option>
                    </select>
                    <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                    共<?php echo $cou?>条
                    <select name='page' onChange="self.myFORM.submit()">
                        <?php
                        if ($page_count==0){
                            $page_count=1;
                        }
                        for($i=0;$i<$page_count;$i++){
                            if ($i==$page){
                                echo "<option selected value='$i'>".($i+1)."</option>";
                            }else{
                                echo "<option value='$i'>".($i+1)."</option>";
                            }
                        }
                        ?>
                    </select> 共<?php echo $page_count?> 页
                </td>
            </tr>
        </FORM>

        <tr class="m_title">
            <td>ID</td>
            <td>用户ID</td>
            <td>用户</td>
            <td>级别</td>
            <td>真实姓名</td>
            <td>代理线</td>
            <?php
            if($competence_num[38]==1) {  // 手机号码权限控制
                echo '<td>手机号</td>';
            }
            ?>
            <td>投注周期</td>
            <td>礼金内容</td>
            <td>申请时间</td>
            <td>彩金</td>
            <td>审核人</td>
            <td>审核时间</td>
            <td>状态</td>
            <td><font color="red">操作</font></td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="16">没有记录</td></tr>';
        }
        $i=1;
        //        while($row=@mysqli_fetch_assoc($result)){
        foreach ($aData as $k => $row){
        if($row['status'] == 1)
            $pageCount += $row['gift_gold'];
        ?>
        <tr class="m_title">
            <td align="center" width=""><?php echo $i; ?></td>
            <td align="center" width=""><?php echo $row['userid']; ?></td>
            <td align="center" width=""><?php echo $row['username']; ?></td>
            <td align="center" width=""><?php echo $row['level']; ?></td>
            <td align="center" width=""><?php echo $row['Alias']; ?></td>
            <td align="center" width=""><?php echo $row['Agents']; ?></td>
            <?php
            if($competence_num[38]==1) {  // 手机号码权限控制
                echo '<td align="center" width="">'.str_replace('8','*',$row['Phone']).'</td>';
            }
            ?>
            <td align="center" width=""><?php echo $row['count_date_start'].'——'.$row['count_date_end']; ?></td>
            <td align="center" width=""><?php echo $row['EventName']; ?></td>
            <td align="center" width=""><?php echo $row['created_at']; ?></td>
            <td align="center" width=""><?php echo sprintf("%.2f",$row['gift_gold']); ?></td>
            <td align="center" width=""><?php echo $row['auditor']; ?></td>
            <?php
            //0未审核,1已派发,2不符合'
            if ($row['status'] == 2) {
                echo '<td align="center"></td>';
                echo '<td align="center" >未审核</td>';
                echo '<td align="center" >';
                echo '</td>';
            }elseif($row['status'] == 1){
                echo '<td align="center">'.$row['audited_at'].'</td>';
                echo '<td align="center" >已派发</td>';
                echo '<td align="center" ></td>';
            }elseif($row['status'] == 3){
                echo '<td align="center"></td>';
                echo '<td align="center" >不符合</td>';
                echo '<td align="center" >';
                echo '</td>';
            }elseif($row['status'] == 4){
                echo '<td align="center">'.$row['audited_at'].'</td>';
                echo '<td align="center" >已拒绝</td>';
                echo '<td align="center"></td>';
            }
            ?>

            <?php
            $i=$i+1;
            }
            ?>
        </tr>

        <tr class="m_rig2">
            <td colspan="2" >统计</td>
            <td colspan="6" class="red">当前页总计 : <?php echo sprintf("%01.2f", $pageCount)?> </td>
            <td colspan="8" class="red">全部页总计 : <?php echo sprintf("%01.2f", $totalCount)?> </td>
        </tr>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</html>
<!-- 插入系统日志 -->
<?php
if ($action=='send'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>
