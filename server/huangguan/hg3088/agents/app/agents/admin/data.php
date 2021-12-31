<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
// 刷水渠道

$match_pwd=$_REQUEST["match_pwd"]; // 需要输入密码
$pk_pwd = 'bysbgbpky';

$redisObj = new Ciredis();
if(isset($_REQUEST['flush']) && $_REQUEST['flush'] == 1){

    $ujl = '/www/huangguan/hg3088/agents/_cli/flushWay/UJL.locks';
    $huangguan = '/www/huangguan/hg3088/agents/_cli/flushWay/HuangGan.locks';
    $ra686 = '/www/huangguan/hg3088/agents/_cli/flushWay/ra686.locks';

    if(trim($_REQUEST['flushWay'])=='ujl'){
        $writeRes = rename($huangguan,$ujl);
        $redisObj->setOne('flush_match_table', 'match_sports');
        if($writeRes>0){
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports');
            $redisObj->setOne('flush_fs_match_table', 'match_crown');
            exit();
        }else{
            $writeRes = rename($ra686,$ujl);
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports');
            $redisObj->setOne('flush_fs_match_table', 'match_crown');
            exit();
        }
    }
    elseif(trim($_REQUEST['flushWay'])=='ra686'){
        $writeRes = rename($ujl,$ra686);
        if($writeRes>0){
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports_686');
            $redisObj->setOne('flush_fs_match_table', 'match_crown_686');
            exit();
        }else{
            $writeRes = rename($huangguan,$ra686);
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports_686');
            $redisObj->setOne('flush_fs_match_table', 'match_crown_686');
            exit();
        }
    }
    else{
        $writeRes = rename($ujl,$huangguan);
        if($writeRes>0){
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports');
            $redisObj->setOne('flush_fs_match_table', 'match_crown');
            exit();
        }else{
            $writeRes = rename($ra686,$huangguan);
            $redisObj->setOne('flush_way', trim($_REQUEST['flushWay']));
            $redisObj->setOne('flush_match_table', 'match_sports');
            $redisObj->setOne('flush_fs_match_table', 'match_crown');
            exit();
        }
    }

    exit();
//    print_r(error_get_last());  // 打印出最后的错误信息
}

$flushWay = $redisObj->getSimpleOne('flush_way');

$uid=$_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$type=$_REQUEST["type"];

$username='admin'; // 固定 admin

switch ($type){
case "MAX": // 下注设置
	$mysql="update ".DBPREFIX."web_system_data set R=".$_REQUEST['M1'].",OU=".$_REQUEST['M2'].",M=".$_REQUEST['M3'].",RE=".$_REQUEST['M4'].",ROU=".$_REQUEST['M5'].",PD=".$_REQUEST['M6'].",T=".$_REQUEST['M7'].",F=".$_REQUEST['M8'].",P=".$_REQUEST['M9'].",PR=".$_REQUEST['M10'].",P3=".$_REQUEST['M11'].",MAX=".$_REQUEST['M12']." where UserName='".$username."' ";
	mysqli_query($dbMasterLink,$mysql);
	break;
case "FT": // 设置足球刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_ft_tw=".$_REQUEST['udp_ft_tw'].",udp_ft_r=".$_REQUEST['udp_ft_r'].",udp_ft_v=".$_REQUEST['udp_ft_v'].",udp_ft_re=".$_REQUEST['udp_ft_re'].",udp_ft_pd=".$_REQUEST['udp_ft_pd'].",udp_ft_t=".$_REQUEST['udp_ft_t'].",udp_ft_f=".$_REQUEST['udp_ft_f'].",udp_ft_p=".$_REQUEST['udp_ft_p'].",udp_ft_pr=".$_REQUEST['udp_ft_pr'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "BK": // 设置篮球刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_bk_tw=".$_REQUEST['udp_bk_tw'].",udp_bk_r=".$_REQUEST['udp_bk_r'].",udp_bk_rq=".$_REQUEST['udp_bk_rq'].",udp_bk_re=".$_REQUEST['udp_bk_re'].",udp_bk_pr=".$_REQUEST['udp_bk_pr'].",udp_fs_fs=".$_REQUEST['udp_fs_fs'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "BS": // 设置棒球刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_bs_tw=".$_REQUEST['udp_bs_tw'].",udp_bs_r=".$_REQUEST['udp_bs_r'].",udp_bs_v=".$_REQUEST['udp_bs_v'].",udp_bs_re=".$_REQUEST['udp_bs_re'].",udp_bs_pd=".$_REQUEST['udp_bs_pd'].",udp_bs_t=".$_REQUEST['udp_bs_t'].",udp_bs_m=".$_REQUEST['udp_bs_m'].",udp_bs_p=".$_REQUEST['udp_bs_p'].",udp_bs_pr=".$_REQUEST['udp_bs_pr'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "TN":  // 设置网球刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_tn_tw=".$_REQUEST['udp_tn_tw'].",udp_tn_r=".$_REQUEST['udp_tn_r'].",udp_tn_re=".$_REQUEST['udp_tn_re'].",udp_tn_pd=".$_REQUEST['udp_tn_pd'].",udp_tn_p=".$_REQUEST['udp_tn_p'].",udp_tn_pr=".$_REQUEST['udp_tn_pr'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "VB":  // 设置排球刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_vb_tw=".$_REQUEST['udp_vb_tw'].",udp_vb_r=".$_REQUEST['udp_vb_r'].",udp_vb_re=".$_REQUEST['udp_vb_re'].",udp_vb_pd=".$_REQUEST['udp_vb_pd'].",udp_vb_p=".$_REQUEST['udp_vb_p'].",udp_vb_pr=".$_REQUEST['udp_vb_pr'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "OP":  // 设置其他刷水刷新时间
	$mysql="update ".DBPREFIX."web_system_data set udp_op_tw=".$_REQUEST['udp_op_tw'].",udp_op_r=".$_REQUEST['udp_op_r'].",udp_op_v=".$_REQUEST['udp_op_v'].",udp_op_re=".$_REQUEST['udp_op_re'].",udp_op_pd=".$_REQUEST['udp_op_pd'].",udp_op_t=".$_REQUEST['udp_op_t'].",udp_op_f=".$_REQUEST['udp_op_f'].",udp_op_p=".$_REQUEST['udp_op_p'].",udp_op_pr=".$_REQUEST['udp_op_pr'];
	mysqli_query($dbMasterLink,$mysql);
	break;
case "RESULT": // 比分结果
	$mysql="update ".DBPREFIX."web_system_data set udp_ft_results=".$_REQUEST['udp_ft_results'].",udp_ft_score=".$_REQUEST['udp_ft_score'].",udp_bk_results=".$_REQUEST['udp_bk_results'].",udp_bk_score=".$_REQUEST['udp_bk_score'].",udp_bs_results=".$_REQUEST['udp_bs_results'].",udp_bs_score=".$_REQUEST['udp_bs_score'].",udp_tn_results=".$_REQUEST['udp_tn_results'].",udp_tn_score=".$_REQUEST['udp_tn_score'].",udp_vb_results=".$_REQUEST['udp_vb_results'].",udp_vb_score=".$_REQUEST['udp_vb_score'].",udp_op_results=".$_REQUEST['udp_op_results'].",udp_op_score=".$_REQUEST['udp_op_score'];
	mysqli_query($dbMasterLink,$mysql);
	break;
}
if($type){ // 有重新设置
    refurbishTime('upd');
}
$sql = "select * from ".DBPREFIX."web_system_data where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    input.za_text{ width: 60px;}
</style>
</head>
<body  >
<dl class="main-nav"><dt>数据刷新</dt>
    <dd>
        <table>
            <tr class="m_tline">
                <td>&nbsp;&nbsp;
                    <?php
                    if ($match_pwd==$pk_pwd){
                    ?>
                    <a class="a_link" onClick="parent.parent.bb_mem_index.Go_Down_accounts(1);" href="javascript:;" >滚球确认</a> --
                    刷水渠道：<select name="flush_way" id="flush_way" onchange="changeFlushWay(this);">
                        <option value="" <?php echo ($flushWay == '' ? "selected" : '');?> >请选择</option>
                        <option value="ra686" <?php echo ($flushWay == 'ra686' ? "selected" : '');?> >6686</option>
                        <option value="ra" <?php echo ($flushWay == 'ra' ? "selected" : '');?> >正网</option>
                        <option value="ujl" <?php echo ($flushWay == 'ujl' ? "selected" : '');?> >优久乐</option>
                    </select>
                    滚球刷水：<a class="a_link ra686" href="javascript:;" >686高频计划任务</a>
                    今日赛事刷水：<a class="a_link ra" onClick="parent.parent.bb_mem_index.Go_Down_match(1);"  href="javascript:;" >正网</a>
                    <a class="a_link ujl" onClick="parent.parent.bb_mem_index.Go_Down_match(2);"  href="javascript:;" >优久乐</a> --
                    早餐刷水：<a class="a_link ra" onClick="parent.parent.bb_mem_index.Go_Down_future(1);"  href="javascript:;" >正网</a>
                    <a class="a_link ujl" onClick="parent.parent.bb_mem_index.Go_Down_future(2);"  href="javascript:;" >优久乐</a> --
                    <a class="a_link ujl" onClick="parent.parent.bb_mem_index.Go_Down_scoreYjl(1);"  href="javascript:;" >刷比分</a> --
                    <a class="a_link ra" onClick="parent.parent.bb_mem_index.Go_Down_clearing(1);"  href="javascript:;" >自动结算</a> --
                    <a class="a_link ra" onClick="parent.parent.bb_mem_index.Go_Down_url(1);"  href="javascript:;" >刷水速度测试</a>
                    <?php }else{ ?>
                        <?php
                         if($match_pwd){
                          echo '<script>alert(\'密码输入错误\');</script>';
                         }
                        ?>
                        <a href="javascript:CheckCLOSE(0,'data.php?')" title="点击切换水源">点击切换水源</a>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
      <TR class="m_title">
        <td width=115>下注设置</td>
        <td width=65> 让球</td>
        <td width=65>大小球</td>
        <td width=65>标准盘</td>
        <td width=65>滚球</td>
        <td width=65>滚球大小</td>
        <td width=65>波胆</td>
        <td width=65>入球数</td>
        <td width=65>半全场</td>
        <td width=65>标准过关</td>
        <td width=65>让球过关</td>
        <td width=65>综合过关</td>
        <td width=65>最大单场</td>
        <td width=65></td>
      </TR>
      <form name=FTR action="" method=post target="_self" >
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td> 足(网/排/篮/棒)球 </td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['R']?>" name=M1></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['OU']?>" name=M2></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['M']?>" name=M3></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['RE']?>" name=M4></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['ROU']?>" name=M5></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['PD']?>" name=M6></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['T']?>" name=M7></td>
          <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['F']?>" name=M8></td>
          <td><input class=za_text  maxlength=11 size=5 value="<?php echo $row['P']?>" name=M9></td>
          <td><input class=za_text  maxlength=11 size=5 value="<?php echo $row['PR']?>" name=M10></td>
          <td><input class=za_text  maxlength=11 size=5 value="<?php echo $row['P3']?>" name=M11></td>
          <td><input class=za_text  maxlength=11 size=5 value="<?php echo $row['MAX']?>" name=M12></td>
          <td><input class=za_button type=submit value="确定" name=ft_ch_ok1></td>
          <input type=hidden value="MAX" name=type>
        </TR>
      </form>
    </table>
    <br>
    <table class="m_tab">
      <TR class="m_title">
        <td width=100>刷新设置</td>
        <td width=85>单式繁体</td>
        <td width=85>单式</td>
        <td width=85>上半场</td>
        <td width=85>走地</td>
        <td width=85>波胆</td>
        <td width=85>入球</td>
        <td width=85>半全场</td>
        <td width=85>让球过关</td>
        <td width=85>标准过关</td>
        <td width=98>&nbsp; </td>
      </TR>
        <form name=FTR  method=post target="_self">
        <tr class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>足球</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_tw']?>" name=udp_ft_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_r']?>" name=udp_ft_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_v']?>" name=udp_ft_v></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_re']?>" name=udp_ft_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_pd']?>" name=udp_ft_pd></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_t']?>" name=udp_ft_t></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_f']?>" name=udp_ft_f></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_p']?>" name=udp_ft_p></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_ft_pr']?>" name=udp_ft_pr></td>
          <td><input class=za_button type=submit value="确定" name=ft_ok></td>
          <input type=hidden value="FT" name=type>
        </tr>
      </form>
        <form name=BSR action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>棒球</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_tw']?>" name=udp_bs_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_r']?>" name=udp_bs_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_v']?>" name=udp_bs_v></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_re']?>" name=udp_bs_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_pd']?>" name=udp_bs_pd></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_t']?>" name=udp_bs_t></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_m']?>" name=udp_bs_m></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_p']?>" name=udp_bs_p></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_bs_pr']?>" name=udp_bs_pr></td>
          <td><input class=za_button type=submit value="确定" name=bs_ok></td>
          <input type=hidden value="BS" name=type>
        </TR>
      </form>
        <form name=OPR action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>其他</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_tw']?>" name=udp_op_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_r']?>" name=udp_op_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_v']?>" name=udp_op_v></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_re']?>" name=udp_op_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_pd']?>" name=udp_op_pd></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_t']?>" name=udp_op_t></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_f']?>" name=udp_op_f></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_op_p']?>" name=udp_op_p></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_op_pr']?>" name=udp_op_pr></td>
          <td><input class=za_button type=submit value="确定" name=op_ok></td>
          <input type=hidden value="OP" name=type>
        </TR>
      </form>
    </table>
    <br>
    <table width="700" border="0" cellpadding="0" cellspacing="1" class="m_tab">
      <TR class="m_title">
        <td width=100>刷新设置</td>
        <td width=85>单式繁体</td>
        <td width=85>单式</td>
        <td width=85>单节</td>
        <td width=85>走地</td>
        <td width=85>让球过关</td>
        <td width=85>冠军</td>
        <td width=81>&nbsp; </td>
      </TR>
      <form name=BKR action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td> 篮球 </td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_tw']?>" name=udp_bk_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_r']?>" name=udp_bk_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_rq']?>" name=udp_bk_rq></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_re']?>" name=udp_bk_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_pr']?>" name=udp_bk_pr></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_fs_fs']?>" name=udp_fs_fs></td>
          <td><input class=za_button type=submit value="确定" name=bk_ok></td>
          <input type=hidden value="BK" name=type>
        </TR>
      </form>
      <form name=TNR action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>网球</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_tw']?>" name=udp_tn_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_r']?>" name=udp_tn_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_re']?>" name=udp_tn_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_pd']?>" name=udp_tn_pd></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_p']?>" name=udp_tn_p></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_pr']?>" name=udp_tn_pr></td>
          <td><input class=za_button type=submit value="确定" name=tn_ok></td>
          <input type=hidden value="TN" name=type>
        </TR>
      </form>
      <form name=VBR action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>排球</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_tw']?>" name=udp_vb_tw></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_r']?>" name=udp_vb_r></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_re']?>" name=udp_vb_re></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_pd']?>" name=udp_vb_pd></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_p']?>" name=udp_vb_p></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_vb_pr']?>" name=udp_vb_pr></td>
          <td><input class=za_button type=submit value="确定" name=vb_ok></td>
          <input type=hidden value="VB" name=type>
        </TR>
      </form>
    </table>
    <br>
    <table width="975" border="0" cellpadding="0" cellspacing="1" class="m_tab">
      <TR class="m_title">
        <td width=115>刷新设置</td>
        <td width=65>足球比分</td>
        <td width=65>足球结算</td>
        <td width=65>篮球比分</td>
        <td width=65>篮球结算</td>
        <td width=65>棒球比分</td>
        <td width=65>棒球结算</td>
        <td width=65>网球比分</td>
        <td width=65>网球结算</td>
        <td width=65>排球比分</td>
        <td width=65>排球结算</td>
        <td width=65>其他比分</td>
        <td width=65>其他结算</td>
        <td width=65></td>
      </TR>
        <form name=RESULTS action="" method=post target="_self">
        <TR class=m_cen onmouseover=sbar(this) onmouseout=cbar(this)>
          <td>结果</td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_results']?>" name=udp_ft_results></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_ft_score']?>" name=udp_ft_score></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_results']?>" name=udp_bk_results></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bk_score']?>" name=udp_bk_score></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_results']?>" name=udp_bs_results></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_bs_score']?>" name=udp_bs_score></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_results']?>" name=udp_tn_results></td>
          <td><input class=za_text maxLength=11 size=5 value="<?php echo $row['udp_tn_score']?>" name=udp_tn_score></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_vb_results']?>" name=udp_vb_results></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_vb_score']?>" name=udp_vb_score></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_op_results']?>" name=udp_op_results></td>
          <td><input class=za_text maxlength=11 size=5 value="<?php echo $row['udp_op_score']?>" name=udp_op_score></td>
          <td><input class=za_button type=submit value="确定" name=sc_ok></td>
          <input type=hidden value="RESULT" name=type>
        </TR>
      </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var flushWayNow = '<?php echo $flushWay;?>';
    if(flushWayNow == 'ra'){
        $('.ra').show();
        $('.ujl').hide();
        $('.ra686').hide();
    }else if(flushWayNow == 'ujl'){
        $('.ra').hide();
        $('.ujl').show();
        $('.ra686').hide();
    }else if(flushWayNow == 'ra686'){
        $('.ra').hide();
        $('.ujl').hide();
        $('.ra686').show();
    }else{
        $('.ra').hide();
        $('.ujl').hide();
        $('.ra686').hide();
    }
    function changeFlushWay(obj) {
        var flushWay = obj.value;
        if(flushWay){
            $.ajax({
                type: 'GET',
                url: './data.php?flush=1',
                data: {flushWay : flushWay},
                dataType: 'json',
                success: function () {
                    if(flushWay == 'ra'){
                        $('.ra').show();
                        $('.ujl').hide();
                    }else{
                        $('.ra').hide();
                        $('.ujl').show();
                    }
                },
                error: function () {
                    alert('异常错误，请稍后重试!');
                }
            });
        }
    }
    function CheckCLOSE(type,str){ // type: 0 关闭，1 开启
        var con = '<div class="match_input"><input type="password" class="close_match_pwd" maxlength="20" placeholder="请输入操作密码" /></div>';
        var tit = con;

        layer.confirm(tit, {
            title:'提示',
            icon:6,
            btn: ['确定','取消'], //按钮
            yes: function(index, layero){
                var pwd = $('.close_match_pwd').val()? $('.close_match_pwd').val():'';
                str +='&match_pwd='+pwd;
                //parent.main.location = url;
                document.location=str;
                layer.close(index);
                //按钮【按钮一】的回调
            },
            cancel: function(){
                //右上角关闭回调
            },
        });
    }
</script>
</body>
</html>
