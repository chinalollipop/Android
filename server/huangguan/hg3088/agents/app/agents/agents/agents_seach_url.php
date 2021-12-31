<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_SESSION["langx"];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级
$loginname=$_SESSION['UserName']; // 当前登录帐号
require ("../../agents/include/traditional.$langx.inc.php");

$loginfo= $loginname.'查看代理商域名';
$enable=$_REQUEST["enable"];
$disable=$_REQUEST["disable"];
$suspend=$_REQUEST["suspend"];
$logout=$_REQUEST["logout"];
$sort=$_REQUEST["sort"];
$active=$_REQUEST["active"];
$orderby=$_REQUEST["orderby"];
$active_id=$_REQUEST["active_id"];
$username=$_REQUEST["name"];
$page=$_REQUEST["page"];
$search= str_replace(' ','',$_REQUEST["search"]); // 搜索帐号,去除空格
$search_name= $search ; // 搜索帐号
$haschinese = isTrueName($search);  // 是否输入有中文


if ($enable==""){
    $enable='ALL';
}

if ($sort==""){
    $sort='ADDDATE';
}

if ($orderby==""){
    $orderby='DESC';
}
if ($page==''){
    $page=0;
}
if ($search!=''){
    if($haschinese){ // 有中文
        $search="and (UserName LIKE binary '%$search%' or LoginName LIKE binary '%$search%' or AddDate LIKE binary '%$search%' or Alias LIKE binary '%$search%')";
    }else{
        $search="and (UserName LIKE '%$search%' or LoginName LIKE '%$search%' or AddDate LIKE '%$search%' or Alias LIKE '%$search%' or agent_url LIKE '%$search%')";
    }
    $num=512;
}else{
    $search="";
    $num=50;
}

$data=DBPREFIX.'web_agents_data';
$admin ='admin' ;
$agents="agent_url!='' and Level='D'";
$status ='';
if ($enable=="Y"){
    $status="and Status='0'";
}else if ($enable=="S"){
    $status="and Status='1'";
}else if ($enable=="N"){
    $status="and Status='2'";
}
$sql = "select UserName,agent_url,Count,AddDate,LoginTime,Status from $data where $agents $status $search order by ".$sort." ".$orderby;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*50;
$mysql=$sql."  limit $offset,$num;";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
// echo $sql ;
if ($cou==0){
    $page_count=1;
}

?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">

</style>

</head>
<body >
<form name="myFORM" action="agents_seach_url.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&urlname=<?php echo $url_name;?>&userlv=<?php echo $userlv;?>" method=POST>

<dl class="main-nav">
    <dt> 代理商域名 </dt>
    <dd>
        <table>
          <tr class="m_tline">
                    <td style="padding-left: 10px;">
                      <select name="enable" id="enable" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                      <option label="<?php echo $Mem_All?>" value="ALL" <?php echo $enable=='ALL'?'selected':'' ?> ><?php echo $Mem_All?></option>
                      <option label="<?php echo $Mem_Enable?>" value="Y" <?php echo $enable=='Y'?'selected':'' ?> ><?php echo $Mem_Enable?></option>
                      <option label="<?php echo $Mem_Suspend?>" value="S" <?php echo $enable=='S'?'selected':'' ?> ><?php echo $Mem_Suspend?></option>
                      <option label="<?php echo $Mem_Disable?>" value="N" <?php echo $enable=='N'?'selected':'' ?> ><?php echo $Mem_Disable?></option>
                      </select>
                    </td>
                    <td >-- <?php echo $Mem_Method?> :</td>
                    <td >
                      <select name="sort" id="sort" onChange="myFORM.search.value='';self.myFORM.submit();" class="za_select za_select_auto">
                      <option label="<?php echo $Title?><?php echo $Mem_Account?>" value="USERNAME" <?php echo $sort=='USERNAME'?'selected':'' ?> ><?php echo $Title?><?php echo $Mem_Account?></option>
                      <option label="<?php echo $Title?><?php echo $Mem_Name?>" value="ALIAS" <?php echo $sort=='ALIAS'?'selected':'' ?> ><?php echo $Title?><?php echo $Mem_Name?></option>
                      <option label="<?php echo $Mem_Add?><?php echo $Mem_Date?>" value="ADDDATE" <?php echo $sort=='ADDDATE'?'selected':'' ?> ><?php echo $Mem_Add?><?php echo $Mem_Date?></option>

                      </select>

                    </td>
                    <td >-- <?php echo $Mem_Totalpage?> :</td>
                    <td >
                      <select id="page" name="page" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                      <?php
                      for($i=0;$i<$page_count;$i++){
                          echo "<option value='$i'>".($i+1)."</option>";
                         }
                      ?>
                      </select>
                    </td>
                    <td > / <?php echo $page_count?>  <?php echo $Mem_Page?></td>
                    <td>
                        --<input type="text" id="dlg_text" value="<?php echo $search_name ?>" class="za_text" size="15" placeholder="请输入关键字">
                        <input type="submit" id="dlg_ok" value="<?php echo $Mem_Search?>" class="za_button" onClick="submitSearchDlg();">
                    </td>
                    <td><input type="hidden" name="search" value="" /></td>


          </tr>
        </table>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab_ag list-tab ">
        <tbody>
        <tr class="m_title_ag">
            <td width="30">序号</td>
            <td width="120" class="login_account">
                <a name="alias"  >登录帐号</a>
            </td>
            <td width="220" class="agents_url">域名</td>
            <td width="80">可用额度</td>
            <td width="80">输赢额度</td>
            <td width="80" class="mem_times" >存取次数</td>
            <td width="130"><a name="new_date">新增日期</a></td>
            <td width="60" class="hy_zk">帐号状况</td>

        </tr>

        <?php
        if ($cou==0){
            ?>
            <tr class="m_title_ag"><td colspan="12"> 暂无数据 </td></tr>
            <?php
        }else{  // 有数据

            $num_sort=0 ;
            while ($row = mysqli_fetch_assoc($result)) {
            $num_sort++;

            ?>
            <tr class="m_cen">
                <td align="center"><?php echo $num_sort;?></td>
                <td class="login_account" >
                    <?php echo $row['UserName'];?>
                </td>

                <td class="agents_url" >
                    <?php echo $row['agent_url'];?>
                </td>
                <td> 0 </td>
                <td class="money_win_lose"> 0 </td>
                <td class="hy_xjzj">
                     <?php echo $row['Count'];?>
                </td>

                <td>
                    <span > <?php echo $row['AddDate']?> </span>

                    <br><?php echo $row['LoginTime']?>
                </td>
                <td class="hy_zk">
                    <?php
                    if ($row['Status']==0){ // 启用
                        echo $Mem_Enable ;
                    }else if ($row['Status']==1){
                        echo '<span style="background-color: Yellow;">'.$Mem_Suspend.'</span>' ;
                    }else if ($row['Status']==2){ // 停用
                        echo '<span style="background-color: Red;">'.$Mem_Disable.'</span>' ;
                    }
                    ?>
                </td>

            </tr>


            <?php

            }
        }
        ?>


        </tbody>
    </table>


</div>
</form>



<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/user_search.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript">

</script>
</body>
</html>
<?php
innsertSystemLog($loginname,$userlv,$loginfo);
?>