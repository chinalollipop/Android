<?php
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_REQUEST["langx"];
require ("../include/traditional.$langx.inc.php");
$uid=$_REQUEST["uid"];
$active=$_REQUEST['active'];
$uid=$_REQUEST['uid'];
$id=$_REQUEST['id'];
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];
$key=$_REQUEST['key'];
$confirmed=$_REQUEST['confirmed'];
$loginname=$_SESSION['UserName'];

switch($gtype){
    case 'FT':
        $table="and (Active=1 or Active=11) ";
        break;
    case 'BK':
        $table="and (Active=2 or Active=22) ";
        break;
    case 'BS':
        $table="and (Active=3 or Active=33) ";
        break;
    case 'TN':
        $table="and (Active=4 or Active=44) ";
        break;
    case 'VB':
        $table="and (Active=5 or Active=55) ";
        break;
    case 'OP':
        $table="and (Active=6 or Active=66) ";
        break;
    case 'FU':
        $table="and (Active=7 or Active=77) ";
        break;
    case 'FS':
        $table="and (LineType=16)";
        break;
}

//取消注单
if($key=='cancel'){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");
    if($beginFrom){
        $rresult = mysqli_query($dbMasterLink, "select userid,M_Name,Pay_Type,BetScore,M_Result,Cancel from ".DBPREFIX."web_report_data where id=$id and Pay_Type=1 for update");
        $rrow = mysqli_fetch_assoc($rresult);
        if($rrow['Cancel']==0){
            $userid=$rrow['userid'];
            $username=$rrow['M_Name'];
            $betscore=$rrow['BetScore'];
            $m_result=$rrow['M_Result'];
            $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
            if($resultMem){
                $rowMem = mysqli_fetch_assoc($resultMem);
                if($m_result==''){
                    $moneyLog=$betscore;
                    $moneyDesLog='：退回用户投注金额';
                    $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID=".$userid;
                }else{

                    if (int($rowMem['Money']) < intval($m_result)){
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('用户资金不足，取消订单失败！');</script>";
                        echo "<script languag='JavaScript'>self.location='showdata.php?uid=$uid&id=$id&gid=$gid&gtype=$gtype&langx=$langx'</script>";
                        exit;
                    }

                    $moneyLog=$m_result*-1;
                    if($m_result==0){
                        $moneyDesLog='：和局,无资金变化';
                    }elseif($m_result>0){
                        $moneyDesLog="：取消派彩,平台入款{$m_result}";
                    }elseif($m_result<0){
                        if($m_result==$betscore*-1){
                            $moneyDesLog="：退回用户投注金额";
                        }else{
                            $moneyDesLog="：取消派彩,平台入款{$m_result}";
                        }
                    }
                    $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID=".$userid;
                }
                if(mysqli_query($dbMasterLink,$u_sql)){
                    $sql="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Cancel=1,Confirmed='$confirmed',Danger=0,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where id=".$id;
                    if(mysqli_query($dbMasterLink,$sql)){
                        if($gtype=='FS'){
                            $moneyDesLog="[审核比分-冠军-注单核查]".$moneyDesLog.",操作人:{$loginname}";
                        }else{
                            $moneyDesLog="[审核比分-注单核查]".$moneyDesLog.",操作人:{$loginname}";
                        }

                        $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$moneyLog,$rowMem['Money']+$moneyLog,2,6,$id,$moneyDesLog));
                        if($moneyLogRes){
                            mysqli_query($dbMasterLink,"COMMIT");
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "<script>alert('用户资金账变添加失败！');</script>";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('订单更新失败！');</script>";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('用户资金账户更新失败！');</script>";
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "<script>alert('用户资金锁定失败！');</script>";
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            echo "<script>alert('订单已被取消,不能重复操作！');</script>";
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        echo "<script>alert('事务开启失败！');</script>";
    }
    echo "<script languag='JavaScript'>self.location='showdata.php?uid=$uid&id=$id&gid=$gid&gtype=$gtype&langx=$langx'</script>";
}

//恢复注单
if($key=='resume'){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");
    if($beginFrom){
        $rsql = "select userid,M_Name,Pay_Type,BetScore,M_Result,Checked from ".DBPREFIX."web_report_data where ID=$id and Pay_Type=1 for update";
        $rresult = mysqli_query($dbMasterLink, $rsql);
        if($rresult){
            $rrow = mysqli_fetch_assoc($rresult);
            $userid=$rrow['userid'];
            $username=$rrow['M_Name'];
            $betscore=$rrow['BetScore'];
            $m_result=$rrow['M_Result'];
            if($rrow['Checked']==1){
                $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                if($resultMem){
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    $cash=$betscore+$m_result;
                    if($cash>0 && $rowMem['Money'] < $cash) {
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('用户资金不足,恢复订单失败！');</script>";
                        echo "<script languag='JavaScript'>self.location='showdata.php?uid=$uid&id=$id&gid=$gid&gtype=$gtype&langx=$langx'</script>";
                        exit;
                    }
                    if(mysqli_query($dbMasterLink,"update ".DBPREFIX.MEMBERTABLE." SET Money=Money-$cash where UserName='$username' and Pay_Type=1")){
                        if($gtype=='FS'){
                            $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$cash*-1,$rowMem['Money']-$cash,5,6,$id,"[审核比分-冠军-注单核查],操作人:{$loginname}"));
                        }else{
                            $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$cash*-1,$rowMem['Money']-$cash,5,6,$id,"[审核比分-注单核查],操作人:{$loginname}"));
                        }
                        if($moneyLogRes){
                            $sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Danger=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where id='$id'";
                            if(mysqli_query($dbMasterLink,$sql)){
                                mysqli_query($dbMasterLink,"COMMIT");
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                echo "<script>alert('订单状态更新失败！');</script>";
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "<script>alert('用户资金账变添加失败！');</script>";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('用户资金账户更新失败！');</script>";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('用户资金锁定失败！');</script>";
                }
            }else{
                $sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Danger=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where id='$id'";
                if(mysqli_query($dbMasterLink,$sql)){
                    mysqli_query($dbMasterLink,"COMMIT");
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('订单状态更新失败！');</script>";
                }
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            echo "<script>alert('订单锁定失败！');</script>";
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        echo "<script>alert('事务开启失败！');</script>";
    }
    echo "<script languag='JavaScript'>self.location='showdata.php?uid=$uid&id=$id&gid=$gid&gtype=$gtype&langx=$langx'</script>";
}

if($gtype=='FS'){
    $sql = "SELECT MID,MB_Team,M_League,M_Start,Cancel FROM `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` WHERE MID=".$gid." GROUP BY MID order by M_Start asc";
    $result1 = mysqli_query($dbLink, $sql);
    $mrow = mysqli_fetch_assoc($result1);
}else{
    $mysql="select MB_Team,MB_Inball_HR,MB_Inball,TG_Team,TG_Inball_HR,TG_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='".$gtype."' and MID='".$gid."'";
    $result1 = mysqli_query($dbLink, $mysql);
    $mrow = mysqli_fetch_assoc($result1);
}

$gameBets=[];
if($gtype=='FS'){
    $mysqlFs="select ID,MID,orderNo,Active,playSource,LineType,Mtype,Pay_Type,Gtype,M_Date,BetTime,BetScore,CurType,$middle as Middle,$bettype as BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Confirmed,Danger from ".DBPREFIX."web_report_data where MID='$gid' and LineType=16 order by bettime,linetype,mtype";
    $resultBase = mysqli_query($dbLink, $mysqlFs);
    while ($row = mysqli_fetch_assoc($resultBase)){ $gameBets[]=$row; }
}else{
    $mysqlBase="select ID,MID,orderNo,Active,playSource,LineType,Mtype,Pay_Type,Gtype,M_Date,BetTime,BetScore,CurType,$middle as Middle,$bettype as BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Confirmed,Danger from ".DBPREFIX."web_report_data where MID='$gid' and lineType!=8 order by bettime,linetype,mtype";
    $resultBase = mysqli_query($dbLink, $mysqlBase);
    while ($row = mysqli_fetch_assoc($resultBase)){ $gameBets[$row['ID']]=$row; }
    $mysqlCf="select ID,MID,orderNo,Active,playSource,LineType,Mtype,Pay_Type,Gtype,M_Date,BetTime,BetScore,CurType,$middle as Middle,$bettype as BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Confirmed,Danger from ".DBPREFIX."web_report_data where FIND_IN_SET('$gid',MID)>0 and lineType=8 order by bettime,linetype,mtype";
    $resultCf = mysqli_query($dbLink, $mysqlCf);
    while ($row = mysqli_fetch_assoc($resultCf)){ if(!isset($gameBets['ID'])) $gameBets[]=$row; }
}

?>
<html>
<head>
    <title></title>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body >
<dl class="main-nav">
    <dt>相关注单</dt>
    <?php if($gtype=='FS'){?>
        <dd>注单核查--<font color="red">冠军</font>--日期:<font color="red"><?php echo $mrow['M_Start'] ?></font>&nbsp;&nbsp;&nbsp;&nbsp;--联赛名称:<font color="red"><?php echo $mrow['M_League'] ?></font>&nbsp;&nbsp;&nbsp;&nbsp;--赛事名称:<font color="red"><?php echo $mrow['MB_Team'] ?></font><a href="javascript:history.go( -1 );">&nbsp;&nbsp;&nbsp;&nbsp;回上一页</a></dd>
    <?php }else{?>
        <dd>注单核查 --主队：<?php echo $mrow['MB_Team']?>&nbsp;&nbsp;&nbsp;&nbsp;上半场：<font color=red>(&nbsp;<?php echo $mrow['MB_Inball_HR']?>&nbsp;)</font>&nbsp;&nbsp;全场：<font color=red>(&nbsp;<?php echo $mrow['MB_Inball']?>&nbsp;)</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;客队：<?php echo $mrow['TG_Team']?>&nbsp;&nbsp;&nbsp;&nbsp;上半场：<font color=red>(&nbsp;<?php echo $mrow['TG_Inball_HR']?>&nbsp;)</font>&nbsp;&nbsp;全场：<font color=red>(&nbsp;<?php echo $mrow['TG_Inball']?>&nbsp;)</font> &nbsp;&nbsp;<a href="javascript:history.go( -1 );">回上一页</a> </dd>
    <?php }?>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <tr class="m_title">
            <td width="85">投注时间</td>
            <td width="85">用户名称</td>
            <td width="110">球赛种类</td>
            <td width="325">內容</td>
            <td width="95">投注金额</td>
            <td width="95">会员结果</td>
            <td width="50">操作</td>
            <td width="121">功能</td>
        </tr>
        <?php
        foreach ($gameBets as $key=>$row){
            echo " ";
            switch($row['Active']){
                case 1:
                    $Title=$Mnu_Soccer;
                    break;
                case 11:
                    $Title=$Mnu_Soccer;
                    break;
                case 2:
                    $Title=$Mnu_BasketBall;
                    break;
                case 22:
                    $Title=$Mnu_BasketBall;
                    break;
                case 3:
                    $Title=$Mnu_Base;
                    break;
                case 33:
                    $Title=$Mnu_Base;
                    break;
                case 4:
                    $Title=$Mnu_Tennis;
                    break;
                case 44:
                    $Title=$Mnu_Tennis;
                    break;
                case 5:
                    $Title=$Mnu_Voll;
                    break;
                case 55:
                    $Title=$Mnu_Voll;
                    break;
                case 6:
                    $Title=$Mnu_Other;
                    break;
                case 66:
                    $Title=$Mnu_Other;
                    break;
                case 7:
                    $Title=$Mnu_Stock;
                    break;
                case 77:
                    $Title=$Mnu_Stock;
                    break;
                case 8:
                    $Title=$Mnu_Guan;
                    break;
            }
            switch ($row['OddsType']){
                case 'H':
                    $Odds='<BR><font color =green>'.$Rep_HK.'</font>';
                    break;
                case 'M':
                    $Odds='<BR><font color =green>'.$Rep_Malay.'</font>';
                    break;
                case 'I':
                    $Odds='<BR><font color =green>'.$Rep_Indo.'</font>';
                    break;
                case 'E':
                    $Odds='<BR><font color =green>'.$Rep_Euro.'</font>';
                    break;
                case '':
                    $Odds='';
                    break;
            }
            $time=strtotime($row['BetTime']);
            $times=date("Y-m-d",$time).'<br>'.date("H:i:s",$time);

            if ($row['Danger']==1 or $row['Cancel']==1) {
                $bettimes='<font color="#FFFFFF"><span style="background-color: #FF0000">'.$times.'</span></font>';
                $betscore='<S><font color=#cc0000>'.number_format($row['BetScore']).'</font></S>';
            }else{
                $bettimes=$times;
                $betscore=number_format($row['BetScore']);
            }
            ?>
            <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
                <td><?php echo $bettimes?></td>
                <td><?php echo $row['M_Name']?><br/>
                    <font color="#cc0000"><?php echo $row['OpenType']?></font><br/>
                    <?php
                    //投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓
                    switch ($row['playSource']){
                        case '0':
                            echo '未知';
                            break;
                        case '1':
                            echo '旧版';
                            break;
                        case '2':
                            echo '新版';
                            break;
                        case '3':
                            echo 'ios';
                            break;
                        case '4':
                            echo 'android';
                            break;
                        case '13':
                            echo 'ios原生';
                            break;
                        case '14':
                            echo 'android原生';
                            break;
                        case '22':
                            echo '综合版';
                            break;
                    }?>
                </td>
                <td><?php echo $Title?><?php echo $row['BetType']?><?php echo $Odds?><br>
                    <font color="#0000CC"><?php echo $row['orderNo']?></font></td>
                <td align="right">
                    <?php
                    echo $row['Middle'];
                    ?>
                </td>
                <td align="right">
                    <?php
                    // 投注金额
                    //echo $betscore;
                    if($row['Cancel']==1){
                        echo "<font color=green >".floor($row['Glost'])."</font><br/>";
                        $betscore='<S><font color=#cc0000>'.floor($row['BetScore']).'</font></S>';
                        echo $betscore."<br/>";
                        echo floor($row['Glost']-$row['BetScore'])."<br/>";
                    }else{
                        echo "<font color=green >".floor($row['Glost'])."</font><br/>";
                        $betscore=floor($row['BetScore']);
                        echo $betscore."<br/>";
                        echo "<font color=red >".floor($row['Glost']-$row['BetScore'])."</font><br/>";
                    }
                    ?>
                </td>
                <td align="right">
                    <?php
                    if($row['Cancel']==0){
                        ?>
                        <?php
                        if($row['M_Result']<0){ //小于0 会员输 页面显示黑色 黑色不显示负数
                            echo abs(sprintf("%01.1f", $row['M_Result']));
                        }elseif($row['M_Result']>0) { //大于0 会员赢 页面显示红色 红色显示负数
                            echo "<font color=red>".-sprintf("%01.1f", $row['M_Result'])."</font>";
                        }else{
                            echo number_format($row['M_Result'],1);
                        }
                        //echo number_format($row['M_Result'],1);
                        ?>
                        <?php
                    }else{
                        ?>
                        <font color=red>
                            <?php
                            switch($row['Confirmed']){
                                case 0:
                                    echo $zt=$Score20;
                                    break;
                                case -1:
                                    echo $zt=$Score21;
                                    break;
                                case -2:
                                    echo $zt=$Score22;
                                    break;
                                case -3:
                                    echo $zt=$Score23;
                                    break;
                                case -4:
                                    echo $zt=$Score24;
                                    break;
                                case -5:
                                    echo $zt=$Score25;
                                    break;
                                case -6:
                                    echo $zt=$Score26;
                                    break;
                                case -7:
                                    echo $zt=$Score27;
                                    break;
                                case -8:
                                    echo $zt=$Score28;
                                    break;
                                case -9:
                                    echo $zt=$Score29;
                                    break;
                                case -10:
                                    echo $zt=$Score30;
                                    break;
                                case -11:
                                    echo $zt=$Score31;
                                    break;
                                case -12:
                                    echo $zt=$Score32;
                                    break;
                                case -13:
                                    echo $zt=$Score33;
                                    break;
                                case -14:
                                    echo $zt=$Score34;
                                    break;
                                case -15:
                                    echo $zt=$Score35;
                                    break;
                                case -16:
                                    echo $zt=$Score36;
                                    break;
                                case -17:
                                    echo $zt=$Score37;
                                    break;
                                case -18:
                                    echo $zt=$Score38;
                                    break;
                                case -19:
                                    echo $zt=$Score39;
                                    break;
                                case -20:
                                    echo $zt=$Score40;
                                    break;
                                case -21:
                                    echo $zt=$Score41;
                                    break;
                            }
                            ?>
                        </font>
                        <?php
                    }
                    ?>		  </td>
                <td><font color=red>
                        <?php
                        if ($row['Cancel']==1){
                            echo '<a class="a_link" href="showdata.php?uid='.$uid.'&id='.$row['ID'].'&gid='.$row['MID'].'&pay_type='.$row['Pay_Type'].'&key=resume&result='.$row['M_Result'].'&user='.$row['M_Name'].'&confirmed=0&gtype='.$gtype.'&langx='.$langx.'"><font color=red><b>恢复</b></font></a>';
                        }else{ ?>
                            <font color=blue><b>正常</b></font>
                        <?php }?>
                    </font></td>
                <td width="121">
                    <SELECT onchange=javascript:CheckSTOP(this.options[this.selectedIndex].value) size=1 name=select1>
                        <option>注单处理</option>
                        <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-1&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score21?></option>
                        <?php if($gtype!='FS'){?>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-1&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score21?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-2&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score22?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-3&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score23?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-4&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score24?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-5&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score25?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-6&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score26?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-7&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score27?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-8&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score28?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-9&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score29?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-10&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score30?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-11&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score31?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-12&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score32?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-13&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score33?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-14&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score34?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-15&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score35?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-16&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score36?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-17&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score37?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-18&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score38?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-19&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score39?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-20&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score40?></option>
                            <option value="showdata.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&pay_type=<?php echo $row['Pay_Type']?>&key=cancel&result=<?php echo $row['M_Result']?>&user=<?php echo $row['M_Name']?>&confirmed=-21&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"><?php echo $Score41?></option>
                        <?php }?>
                    </SELECT>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<script language=javascript>
    function CheckSTOP(str){
        if(confirm("确实取消本场注单吗?"))
            document.location=str;
    }
    function reload(){
        location.reload();
    }
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</body>
</html>
