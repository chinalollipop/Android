<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");
$name=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
$action=$_REQUEST['action'];

//echo '<pre>';
//var_dump($_REQUEST);

// 存款 昨日、今日、本星期、上星期----------------------------------Start
$yeterday=date('Y-m-d',time()-86400);
$today=date('Y-m-d');
$this_week_monday= date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日   本周一
$this_week_sunday= date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算  本周日
$last_week_monday = date('l',time()) == 'Monday' ? date('Y-m-d', strtotime('last monday')) : date('Y-m-d',strtotime('-1 week last monday')); // 上周一
$last_week_sunday= date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期   上周日
// 存款 昨日、今日、本星期、上星期----------------------------------End

$page=$_REQUEST['page'];
if ($page==""){
    $page=0;
}
if(empty($action)){ //时间跳转， 点击查询会用到
    $action='S';
}

$title_show = '今日统计' ;
$searchType = '';
$action = "'S','R','T'";
$searchType = ' and Checked=1 '; // 如果是存款则仅查询成功记录-20180810

$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d');
    //$date_e=date('Y-m-d 23:59:59', time()+86400);
    $date_e=date('Y-m-d');
}
$date=date('Y-m-d H:i:s');
$active=$_REQUEST['active'];
$id=$_REQUEST['id'];
$userid=$_REQUEST['userid'];
$username=$_REQUEST['username'];
$search = 1;
if ($username!=''){
    $search .=" AND UserName='$username'";
}
$agents=$_REQUEST['agents'];
if ($agents!=''){
    $search .=" AND Agents='$agents'";
}
$seconds=$_REQUEST["seconds"]; // 刷新时间

$sql = "select ID,userid,Checked,Payway,discounType,Gold,moneyf,currency_after,Type,UserName,Date,Name,Waterno,Phone,Contact,Notes,reason,Bank_Account,Bank_Address,Bank,Order_Code,Cancel,PayType,
        AuditDate,Preferential from ".DBPREFIX."web_sys800_data where $search and addDate between '{$date_s}' and '{$date_e}' and Type IN ($action) $searchType order by ID desc";
// echo $sql;echo '<br>'; die;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);

//$page_size=$num;
//$page_count=ceil($cou/$page_size);
//$offset=$page*$page_size;
//$mysql=$sql."  limit $offset,$page_size;";
//
//$result = mysqli_query($dbLink,$mysql);


?>
<html>
<head>
    <title>今日统计</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body onLoad="onLoad()">
<dl class="main-nav"><dt><?php echo  $title_show ?></dt><dd></dd></dl>
<div class="main-ui width_1300">
    <table class="m_tab">
        <FORM id="myFORM" ACTION="" METHOD=POST name="FrmData">
            <input type="hidden" name="action_type" value="<?php if($action == "'T'"){ echo "'T'";} else { echo '';} ?>"/>
            <tr class="m_title">
                <td colspan="12">
                    <?php echo $title_show ?>：
                    &nbsp;&nbsp;<input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('yesterday')" />&nbsp;
                    <input type="button" class="match_date_today" value="今日" onclick="match_date('today')" />&nbsp;
                    <input type="button" class="match_date_this_week" value="本星期" onclick="match_date('this_week')" />&nbsp;
                    <input type="button" class="match_date_last_week" value="上星期" onclick="match_date('last_week')" />&nbsp;&nbsp;
                </td>
            </tr>
            <tr class="m_title">
                <td colspan="12">
                    <!--<select class="za_select za_select_auto" onChange="document.FrmData.submit();" id="seconds" name="seconds">
                        <option value="手动刷新">手动刷新</option>
                        <option value="10" <?php /*echo $seconds=='10'?'selected':''*/?> >10秒</option>
                        <option value="20" <?php /*echo $seconds=='20'?'selected':''*/?> >20秒</option>
                        <option value="30" <?php /*echo $seconds=='30'?'selected':''*/?> >30秒</option>
                        <option value="60" <?php /*echo $seconds=='60'?'selected':''*/?> >60秒</option>
                    </select>&nbsp;-->
                    <span id="ShowTime"></span>
                    时间区间：
                    <input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
                    至
                    <input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
                    &nbsp;按用户名查找:
                    <input type=TEXT name="username" size=10 value="<?php echo $_REQUEST['username']?>" maxlength=20 class="za_text">
                    &nbsp;按代理查找:
                    <input type=TEXT name="agents" size=10 value="<?php echo $_REQUEST['agents']?>" maxlength=20 class="za_text">
                    <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">

<!--                    <select name='page' id="page" onChange="self.myFORM.submit()">-->
<!---->
<!--                        --><?php
//                        if ($page_count==0){
//                            $page_count=1;
//                        }
//                        for($i=0;$i<$page_count;$i++){
//                            if ($i==$page){
//                                echo "<option selected value='$i'>".($i+1)."</option>";
//                            }else{
//                                echo "<option value='$i'>".($i+1)."</option>";
//                            }
//                        }
//                        ?>
<!--                    </select> 共--><?php //echo $page_count?><!-- 页-->
                </td>
            </tr>
        </FORM>
        <tr class="m_title">
            <td width="12%">统计时间</td>
            <td width="8%">公司存款</td>
            <td width="10%">在线支付(第三方)</td>
            <td width="8%">快速充值</td>
            <td width="8%">人工存款</td>
            <td width="8%">存款人数</td>
            <td width="8%">天天返水</td>
            <td width="8%">人工增加彩金</td>
            <td width="8%">优惠彩金</td>
            <td width="8%">公司入款优惠</td>
            <td width="8%">提款</td>
            <td width="8%">提款人数</td>

        </tr>

        <?php
            // 项目入款 (公司入款 + 在线支付 + 手动存款)
            // 虚拟存入 (返水 + 优惠 + 彩金)
            // 实际收入 (公司入款 + 在线支付 + 手动存款 - 会员出款)
            while ($row = mysqli_fetch_assoc($result)){

                if($row['Payway'] == 'N' and $row['discounType'] == '0' and $row['Type']=='S' ) {// 公司存款
//                    $company_cz += $row['Gold'];
                    $company_cz += ($row['currency_after']-$row['moneyf']);
                    $company_cz_yh += ($row['Gold']-($row['currency_after']-$row['moneyf']));
                    $company_cz_rs[] = $row['userid'];  // 公司存款人数
                }

                if($row['Payway'] == 'W' and  $row['discounType'] == '0' and $row['Type']=='S' and $row['PayType']>0 ){// 在线支付(第三方)
                    $third_cz += $row['Gold'];
                    $third_cz_rs[] = $row['userid'];  // 第三方存款人数
                }

                if($row['Payway'] == 'W' and  $row['discounType'] == '9' ){ // Payway=W     快速充值(9)
                    $kscz_cz += $row['Gold'];
                    $kscz_cz_rs[] = $row['userid'];  // 快速充值人数
                }

                if($row['Payway'] == 'W' and in_array($row['discounType'] , array('1','2','3','4','5','7')) ){ // Payway=W   人工存款(1,2,3,4,5,7)
                    $rgck_cz += $row['Gold'];
                }

                if($row['Payway'] == 'R' and  $row['discounType'] == '0'  and $row['Type']=='R'){ // Payway=R 返水
                    $ttfs_fs += $row['Gold'];
                }

                if($row['Payway'] == 'O' and  $row['discounType'] == '0'  and $row['Type']=='S'){ // Payway='O'优惠彩金
                    $yhcj_cj += $row['Gold'];
                }

                if($row['Payway'] == 'G' and  $row['discounType'] == '0'  and $row['Type']=='S'){ // Payway='G' 人工增加彩金
                    $rg_cz_cj += $row['Gold'];
                }

                if($row['discounType'] == '0'  and $row['Type']=='T') { // 提款成功
                    $tkcg_tk += $row['Gold'];
                    $tkcg_tk_rs[] = $row['userid'];  // 提款人数
                }
            }

            $totalDeposit = $company_cz + $third_cz + $kscz_cz + $rgck_cz; //项目入款
            $virtualDeposit = $ttfs_fs + $yhcj_cj;  // 虚拟存入
            $actualIncome = $totalDeposit - $tkcg_tk;   // 实际收益 不用减虚拟存入


            $company_cz_rs = !empty($company_cz_rs) ? $company_cz_rs : [];
            $third_cz_rs = !empty($third_cz_rs) ? $third_cz_rs : [];
            $kscz_cz_rs = !empty($kscz_cz_rs) ? $kscz_cz_rs : [];

            $companyThird_cz_rs = array_merge($company_cz_rs , $third_cz_rs); // 快速充值、线下存款 人数合并
            $depositUniqueRs = count(array_unique(array_merge($companyThird_cz_rs,$kscz_cz_rs))); // 存款人数去重
            $WithdrawalUniqueRs = count(array_unique($tkcg_tk_rs)); // 提款人数去重
        ?>


        <tr class="m_cen">
            <td align="center" class="user_gold"><?php  echo $date_s.'到'.$date_e;?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $company_cz); ?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $third_cz);?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $kscz_cz);?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $rgck_cz);?></td>
            <td align="center" ><?php echo $depositUniqueRs;?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $ttfs_fs);?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $rg_cz_cj);?></td> <!-- 人工增加彩金 -->
            <td align="center" ><?php echo sprintf("%01.2f", $yhcj_cj);?></td>
            <td align="center" ><?php echo sprintf("%01.2f", $company_cz_yh);?></td> <!-- 公司入款优惠 -->
            <td align="center" ><?php echo sprintf("%01.2f", $tkcg_tk);?></td>
            <td align="center" ><?php echo $WithdrawalUniqueRs;?></td>

        </tr>

        <?php if ($cou == 0){ ?>
            <tr class="m_cen">
                <td colspan="13">目前沒有记录</td>
            </tr>
        <?php } ?>

        <tr class="m_rig2 ">
            <td colspan="13" align="left" style="line-height: 23px;">
                项目入款 (公司入款 + 在线支付 + 手动存款)：<?php echo sprintf("%01.2f", $totalDeposit); ?>  <br>
                虚拟存入 (返水 + 优惠彩金)：<?php echo sprintf("%01.2f", $virtualDeposit); ?>    <br>
                实际收入 (公司入款 + 在线支付 + 手动存款 - 会员出款)：<?php echo sprintf("%01.2f", $actualIncome); ?>   <br>
                <span style="color: red">注：本页内容仅供参考，不作其他用途，具体请以报表中为准！</span>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="javascript">
    var yesterday = '<?php echo $yeterday?>';
    var today = '<?php echo $today?>';
    var this_week_monday = '<?php echo $this_week_monday?>';
    var this_week_sunday = '<?php echo $this_week_sunday?>';
    var last_week_monday = '<?php echo $last_week_monday?>';
    var last_week_sunday = '<?php echo $last_week_sunday?>';
    var lv = '<?php echo $lv?>' ;
    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( str ) {
        var date_start;
        var date_end;
        switch (str){
            case 'yesterday':
                date_start = yesterday;
                date_end = yesterday;
                break;
            case 'today':
                date_start = today;
                date_end = today;
                break;
            case 'this_week':
                date_start = this_week_monday;
                date_end = this_week_sunday;
                break;
            case 'last_week':
                date_start = last_week_monday;
                date_end = last_week_sunday;
                break;
        }
        $("#date_start").val(date_start);
        $("#date_end").val(date_end);
        document.FrmData.submit();
    }

    function onLoad(){
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }

    function resume(str) {
        if(confirm("是否确定恢复金额?"))
            document.location=str;
    }
    function Delete(str) {
        if(confirm("是否确定删除纪录?"))
            document.location=str;
    }
    // 自动刷新
    var second="<?php echo $seconds?>";
    function auto_refresh(){
        if(second !=''){
            if (second==1){
                document.FrmData.submit();
            }else if(second=='手动刷新'){ // 手动刷新
                ShowTime.innerText = '' ;
            } else{
                second-=1
                curmin=Math.floor(second);
                curtime=curmin+"秒后刷新" ;
                ShowTime.innerText=curtime ;
                setTimeout("auto_refresh()",1000)
            }
        }
    }
    auto_refresh();
</script>
</body>
</html>
<!-- 插入系统日志 -->
<?php
/*if ($active=='Y'){ // 有操作才需要插入
    innsertSystemLog($name,$lv,$loginfo);
}*/
?>
