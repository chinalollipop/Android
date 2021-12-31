<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../../agents/include/address.mem.php");
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");
include_once ("../include/IpSearch.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$ipdatabase = '../include/ip.dat';
$reader = new IpSearch($ipdatabase);

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$username=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
$userlv = $_REQUEST['userlv'] ; // 当前管理员层级
$parents_id=$_REQUEST["parents_id"];
$parents_name=$_REQUEST["parents_name"];
$name=$_REQUEST["name"];
$keys=$_REQUEST['keys'];


require ("../../agents/include/traditional.$langx.inc.php");

$c_sql = "select SubUser,Competence from ".DBPREFIX."web_system_data where UserName='$username'";
$c_result = mysqli_query($dbLink,$c_sql);
$c_row = mysqli_fetch_assoc($c_result);
$competence = $c_row['Competence']; // 权限控制
$c_num = explode(",",$competence);
// print_r($c_num);

/*if($c_row['SubUser'] ==1){ // 子帐号没有权限
    echo "<script>alert('你没有权限进入此页!');top.location.href='/';</script>";
    exit;
}*/

// 连接彩票主库
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$cmysql = "select RMB_Rate,HKD_Rate,USD_Rate,MYR_Rate,SGD_Rate,THB_Rate,GBP_Rate,JPY_Rate,EUR_Rate,RMB_Rates,HKD_Rates,USD_Rates,MYR_Rates,SGD_Rates,THB_Rates,GBP_Rates,JPY_Rates,EUR_Rates from ".DBPREFIX."web_type_class where ID='1'";
$result = mysqli_query($dbLink,$cmysql);
$crow = mysqli_fetch_assoc($result);

$sql = "select * from ".DBPREFIX.MEMBERTABLE." where ID='$parents_id' and UserName='$name' ";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);


$memberid=$row['ID'];
$agents=$row['Agents'];
$alias=$row['Alias'];
$money=$row['Money'];
$credit=$row['Credit'];
$curtype=$row['CurType'];
$pay_type=$row['Pay_Type'];
$open=$row['OpenType'];
$password=$row['PassWord'];
$Phone=$row['Phone'];
$E_Mail=$row['E_Mail'];
$qq=$row['QQ'];
$Bank_Name=$row['Bank_Name'];
$Bank_Account=$row['Bank_Account'];
$Usdt_Address=$row['Usdt_Address'];
$Address=$row['Address'];
$form =$row['Source']; // 注册来源
$youhui =$row['OfferStatus']; // 会员优惠状态
$Notes =$row['Notes']; // 提款备注
$Deposit_Notes =$row['Deposit_Notes']; // 存款备注
$LoginIP =$row['LoginIP']; // 登录ip
$regIP = $row['RegisterIP']?$row['RegisterIP']:$LoginIP ; // 注册ip
$oweBet = $row['owe_bet']; // 提款打码量

switch ($form){
    case '1': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
        $form_txt = '朋友推荐';
        break ;
    case '2': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
        $form_txt = '比分网';
        break ;
    case '3': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
        $form_txt = '网络广告';
        break ;
    case '4': //  know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
        $form_txt = '论坛';
        break ;
}
//A B C D 盘，赔率转换时使用
switch ($open){
    case "A":
        $type='A';
        break;
    case "B":
        $type='B';
        break;
    case "C":
        $type='C';
        break;
    case "D":
        $type='D';
        break;
}
switch ($lv){
    case 'A':
        $level='A';
        $ag="(UserName='$username' or super='$username' or Corprator='$username' or World='$username') and";
        break;
    case 'B':
        $level='A';
        $ag="(UserName='$username' or super='$username' or Corprator='$username' or World='$username') and";
        break;
    case 'C':
        $level='B';
        $ag="(UserName='$username' or super='$username' or Corprator='$username' or World='$username') and";
        break;
    case 'D':
        $level='C';
        $ag="(UserName='$username' or super='$username' or Corprator='$username' or World='$username') and";
        break;
    case 'MEM':
        $level='D';
        $ag="(UserName='$username' or super='$username' or Corprator='$username' or World='$username') and";
        break;
}

$sql_layer = "SELECT `id`,`title`,`remark`,`status`,`updated_at` FROM " . DBPREFIX . "web_member_data_layer ";
$result_layer = mysqli_query($dbLink, $sql_layer);
$lists = array();
while ($row_layer = mysqli_fetch_assoc($result_layer)){
    $lists[$row_layer['id']] = $row_layer;
}

if($keys=='edit'){ // 基本记录设置
    $id=$_REQUEST["id"];
    $user= str_replace(' ','',$_REQUEST["username"]);
    $layer_id= str_replace(' ','',$_REQUEST["layer_id"]);
    $pasd= str_replace(' ','',$_REQUEST["password"]) ;//密码
    $e_alias= str_replace(' ','',$_REQUEST["alias"]) ;// 真实姓名称
    $e_Phone = isset($_REQUEST['Phone'])?$_REQUEST['Phone']:'';//mobile
    $Notes_edt=$_REQUEST['Notes'];//提款备注
    $Deposit_Notes_edt=$_REQUEST['Deposit_Notes'];//存款备注
    $e_E_Mail = isset($_REQUEST['E_Mail'])?$_REQUEST['E_Mail']:'' ;
    $Bank_Name= str_replace(' ','',$_REQUEST["Bank_Name"]);
    $Bank_Account= str_replace(' ','',$_REQUEST["Bank_Account"]) ; // 银行账号
    $Usdt_Address= str_replace(' ','',$_REQUEST["Usdt_Address"]) ; // USDT地址
    $Address = str_replace(' ','',$_REQUEST["Address"]) ; // 取款密码
    $oweBet = isset($_REQUEST['owe_bet']) ? intval($_REQUEST['owe_bet']) : 0; // 提款打码量
    if(!$e_alias){
        $e_alias = $alias ;
    }
    if(!$pasd){ // 如果没有输入登录密码,用原来的密码
        $mdpasd = $password ;
    }else{
        $mdpasd = passwordEncryption($pasd,$name);
	}
//	if(!$e_Phone){
//        $e_Phone = $Phone ;
//    }
    if(!$e_E_Mail){
        $e_E_Mail = $E_Mail ;
    }

    // 当前代理商最大信用额度
    $asql = "select Credit from ".DBPREFIX."web_agents_data where UserName='$agents' and Level='D'";
    $aresult = mysqli_query($dbLink,$asql);
    $arow = mysqli_fetch_assoc($aresult);
    $acredit=$arow['Credit'];
    //所属代理商累计信用额度
    $bsql="select sum(Credit) as Credit from ".DBPREFIX.MEMBERTABLE." where Agents='$agents' and UserName!='$name'";
    $bresult = mysqli_query($dbLink,$bsql);
    $brow = mysqli_fetch_assoc($bresult);
    $bcredit=$brow['Credit'];
    $money=$gold+$bcredit-$acredit;
    $mysql="select sum(betscore) as BetScore from ".DBPREFIX."web_report_data where M_Name='$name' and M_Date='".date('Y-m-d')."'";
    $result = mysqli_query($dbLink,$mysql);
    $row = mysqli_fetch_assoc($result);
    $betscore=$row['BetScore'];// 有效投注
    $cash=$gold-$betscore; // 总信用额度-有效投注
    // 现金方式
    $mysql="update ".DBPREFIX.MEMBERTABLE." set owe_bet=$oweBet,Alias='$e_alias',Phone='$e_Phone',Bank_Name='$Bank_Name',Bank_Account='$Bank_Account',Usdt_Address='$Usdt_Address',Notes='$Notes_edt',Deposit_Notes='$Deposit_Notes_edt',Address='$Address',E_Mail='$e_E_Mail',PassWord='$mdpasd',layer='$layer_id' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!!!");
	if ($pasd){
        $loginfo= $_SESSION['UserName'].' 在基本资料设置中修改现金会员:<font class="green">'.$name.'</font> 密码:'.$pasd.' (修改成功)';
    }
    if ($Notes != $Notes_edt){
        $loginfo.= ' 存款备注:<font class="red">'.$Deposit_Notes.'</font>';
    }
    if ($Deposit_Notes != $Deposit_Notes_edt){
        $loginfo.= ' 取款备注:<font class="red">'.$Notes.'<font>';
    }
    if ($layer_id>=0){
        $loginfo= $_SESSION['UserName'].'设置用户 '.$name.' 会员分层:<font class="red">'.$lists[$layer_id]['title'].'<font>';
    }
	innsertSystemLog($_SESSION['UserName'],$userlv,$loginfo); /* 插入系统日志 */

    $search = "select hguid from xmcp_gxfc.gxfcy_user where hguid=".$memberid;
    $searchHgUid = mysqli_query($cpMasterDbLink,$search);
    // 如果彩票用户也存在
    if ($searchHgUid) {
        $cpsql = "UPDATE gxfcy_user SET userpsw='".$mdpasd."' where hguid=".$memberid;
        $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
        if($updateUserPass) {
            echo "<Script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx&userlv=M&urlname=$agents';</script>";
        } else {
            // 更改失败还原会员密码
            $date=date("Y-m-d");
            $rallbacksql = "update " . DBPREFIX.MEMBERTABLE." set PassWord='$pasd',EditDate='$date' where Oid='$uid'";
            mysqli_query($dbMasterLink,$rallbacksql);
            //echo "<script languag='JavaScript'>alert('会员密码修改失败!!!!');</script>";
            echo "<script languag='JavaScript'>alert('同步该账号彩票密码修改失败，请检查该账号存在后重新修改!!!!');history.go( -1 );</script>";
        }
    } else {
        echo "<script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx&userlv=M&urlname=$agents';</script>";
    }
}elseif($keys=='editOpenType'){ // 下注资料设置
    $id=$_REQUEST["id"];
    $gold=$_REQUEST["maxcredit"];//总信用额度
    $type=$_REQUEST['type'];//A B C D 盘，赔率转换时使用
    $curtype=$_REQUEST['currency'];//币别
    $pay_type=$_REQUEST['pay_type'];//0输赢额度  1现金额度
    $offerStatus=$_REQUEST['offerStatus'];//会员是否优惠
    // 当前代理商最大信用额度
    $asql = "select Credit from ".DBPREFIX."web_agents_data where UserName='$agents' and Level='D'";
    $aresult = mysqli_query($dbLink,$asql);
    $arow = mysqli_fetch_assoc($aresult);
    $acredit=$arow['Credit'];
    //所属代理商累计信用额度
    $bsql="select sum(Credit) as Credit from ".DBPREFIX.MEMBERTABLE." where Agents='$agents' and UserName!='$name'";
    $bresult = mysqli_query($dbLink,$bsql);
    $brow = mysqli_fetch_assoc($bresult);
    $bcredit=$brow['Credit'];
    $money=$gold+$bcredit-$acredit;
   
    $mysql="select sum(betscore) as BetScore from ".DBPREFIX."web_report_data where M_Name='$name' and M_Date='".date('Y-m-d')."'";
    $result = mysqli_query($dbLink,$mysql);
    $row = mysqli_fetch_assoc($result);
    $betscore=$row['BetScore'];// 有效投注
    $cash=$gold-$betscore; // 总信用额度-有效投注
    // 现金方式
    if ($pay_type==0){
        if ($betscore==''){// 有效投注
            $mysql="update ".DBPREFIX.MEMBERTABLE." set OpenType='$type',CurType='$curtype',Pay_Type='$pay_type',OfferStatus='$offerStatus' where ID='$id'";
            mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");

            $loginfo= $username.' 在下注资料设定中修改信用会员:'.$name.' 密码:'.$pasd.' 名称:'.$alias.' 信用额度:'.$gold.' (修改成功)';
        }else if ($betscore>=$gold){
            $mysql="update ".DBPREFIX.MEMBERTABLE." set Credit=$gold,OpenType='$type',CurType='$curtype',Pay_Type='$pay_type',OfferStatus='$offerStatus' where ID='$id'";
            mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!");

            $loginfo= $username.' 在下注资料设定中修改信用会员:'.$name.' 密码:'.$pasd.' 名称:'.$alias.' 信用额度:'.$gold.' (修改成功)';
            innsertSystemLog($username,$userlv,$loginfo); /* 插入系统日志 */
        }else if ($betscore<$gold){
            $mysql="update ".DBPREFIX.MEMBERTABLE." set Credit=$gold,OpenType='$type',CurType='$curtype',Pay_Type='$pay_type',OfferStatus='$offerStatus' where ID='$id'";
            mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!!");
            $loginfo= $username.' 在下注资料设定中修改信用会员:'.$name.' 密码:'.$pasd.' 名称:'.$alias.' 信用额度:'.$gold.' (修改成功)';
            innsertSystemLog($username,$userlv,$loginfo); /* 插入系统日志 */
        }
        echo "<Script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }else if ($pay_type==1){
        $mysql="update ".DBPREFIX.MEMBERTABLE." set OpenType='$type',CurType='$curtype',OfferStatus='$offerStatus' where ID='$id'";
        mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!!!");

        $loginfo= $username.' 在下注资料设定中修改现金会员 '.$name.' 盘口成功';
        innsertSystemLog($username,$userlv,$loginfo); /* 插入系统日志 */
        echo "<Script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx&userlv=M&urlname=$agents';</script>";
    }

}else{
    $sql = "select ID from ".DBPREFIX."web_agents_data where ID='$id'";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);

    ?>
    <html>
    <head>
        <title>main</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <!--<script src="../../../js/lib/prototype.js?v=<?php echo AUTOVER; ?>" type="text/javascript"></script>
<script src="../../../js/lib/scriptaculous.js?v=<?php echo AUTOVER; ?>" type="text/javascript"></script>-->

    </head>
    <body >
    <dl class="main-nav">
        <dt><?php echo $Mem_Member.$Mem_Edit?></dt>
        <dd></dd>
    </dl>
    <div class="main-ui all width_1000">
        <FORM NAME="myFORM" ACTION="mem_edit.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&parents_id=<?php echo $parents_id?>&name=<?php echo $name?>&langx=<?php echo $langx?>&pay_type=<?php echo $pay_type?> " METHOD=POST onSubmit="return SubChk()">
            <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $parents_id?>">
            <INPUT TYPE=HIDDEN NAME="keys" VALUE="edit">
            <INPUT TYPE=HIDDEN NAME="ratio" VALUE="">
            <INPUT TYPE=HIDDEN NAME="new_ratio" VALUE="">
            <INPUT TYPE=HIDDEN NAME="enable" VALUE="Y">
            <INPUT TYPE=HIDDEN NAME="ag_name" VALUE="">
            <INPUT TYPE=HIDDEN NAME="userlv" VALUE="<?php echo $userlv?>">
            <INPUT TYPE=HIDDEN NAME="line_chang" VALUE="<?php echo $open?>">
            <input type=HIDDEN name="SS" value="">
            <input type=HIDDEN name="SR" value="">
            <input type=HIDDEN name="TS" value="">
            <input type="hidden" name="s_low_order_gold" value="">
            <input type="hidden" name="s_low_order_gold_pc" value="">
            <table  class="m_tab_ed">
                <tr class="m_title_edit">
                    <td colspan="2" ><h4><?php echo $Mem_Basic_data?><?php echo $Mem_Settings?></h4></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed" width="140"><?php echo $Mem_Member?><?php echo $Mem_Account?> :</td>
                    <td align="left" class="name_title"><?php echo $name?></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed" width="140"><?php echo '会员分层'?> :</td>
                    <td align="left" class="name_layer">
                        <select name="layer_id" id="layer_id">
                            <?php
                            echo '<option value="0">新会员</option>';
                            foreach ($lists as $k => $v){
                                if ($k == $_REQUEST['layer']){
                                    echo '<option value="'.$k.'" selected>'.$v['title'].'</option>';
                                }else{
                                    echo '<option value="'.$k.'">'.$v['title'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed"><?php echo $Mem_Member?><?php echo $Mem_Name?> :</td>
                    <td align="left" class="real_name">
                        <?php
                        if($c_num[41]==1){  // 真实姓名编辑权限控制
                             echo '<input type="text" name="alias" value="'.$alias.'"  minlength="2" maxlength="20" class="za_text">' ;
                        }else{
                            echo $alias ;
                        }
                        ?>

                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed"><?php echo $Mem_Password?> :</td>
                    <td align="left"><input type="password" name="password" value=""  minlength="6" maxlength="15" class="za_text">
                        ◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?>
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed"><?php echo $Mem_Cofirm_Password?> :</td>
                    <td align="left"><input type="password" name="passwd" value=""  minlength="6" maxlength="15" class="za_text">
                    </td>
                </tr>
                <?php
                if($c_num[38]==1){  // 手机号码权限控制
                    echo ' <tr class="m_bc_ed"><td class="m_co_ed">手机 :</td> <td align="left"> <input type="text" name="Phone" value="'.$Phone.'"  minlength="11" maxlength="11" class="za_text"> </td></tr>';
                   }

                if($c_num[39]==1){ // 微信权限控制
                    echo '<tr class="m_bc_ed"><td class="m_co_ed">微信:</td> <td align="left"><input type="text" name="E_Mail" value="'.$E_Mail.'" class="za_text">  </td></tr> ';
                    echo '<tr class="m_bc_ed"><td class="m_co_ed">QQ:</td> <td align="left"><input type="text" name="QQ" value="'.$qq.'" class="za_text">  </td></tr> ';
                }
                ?>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">收款银行:</td>
                    <td align="left"><input type="text" name="Bank_Name" value="<?php echo $Bank_Name?>" size=25 class="za_text"></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">收款账号:</td>
                    <td align="left"><input type="text" name="Bank_Account" value="<?php echo $Bank_Account?>" size=25 class="za_text"></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">USDT地址:</td>
                    <td align="left"><input type="text" name="Usdt_Address" value="<?php echo $Usdt_Address?>" size=25 class="za_text"></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">取款密码:</td>
                    <td align="left"><input type="password" name="Address" value="<?php echo $Address?>" class="za_text"></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">提款打码量:</td>
                    <td align="left"><input type="owe_bet" name="owe_bet" value="<?php echo $oweBet?>" class="za_text"></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">来自:</td>
                    <td align="left"><?php echo $form_txt ;?></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">注册IP:</td>
                    <td align="left"><?php echo $regIP ;?> |&nbsp;
                    <?php
                    $ipArea = $reader->get($regIP);
                    $aIpArea = explode('|',$ipArea);
                    $aIpArea = array_slice($aIpArea,0,6);
                    $sIpArea = implode('|',$aIpArea);
                    echo $sIpArea ;
                    ?>
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">存款备注:</td>
                    <td align="left"><textarea name="Deposit_Notes" rows="3" cols="20"><?php echo $Deposit_Notes ;?></textarea></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">提款备注:</td>
                    <td align="left"><textarea name="Notes" rows="3" cols="20"><?php echo $Notes ;?></textarea></td>
                </tr>

                <tr class="m_bc_ed" align="center">
                    <td colspan="2">
                        <input type=SUBMIT name="OK" value="<?php echo $Mem_Confirm?>" class="za_button">
                        &nbsp; &nbsp; &nbsp;
                        <input type=BUTTON name="FormsButton" value="<?php echo $Mem_Cancle?>" id="FormsButton" onClick="window.location.replace('user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv?>&langx=<?php echo $langx?>&enable=Y');" class="za_button">
                    </td>
                </tr>
            </table>
        </form>
        <FORM NAME="myFORM1" ACTION="mem_edit.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&parents_id=<?php echo $parents_id?>&name=<?php echo $name?>&langx=<?php echo $langx?>&pay_type=<?php echo $pay_type?> " METHOD=POST onSubmit="return SubChk1()">
            <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $parents_id?>">
            <INPUT TYPE=HIDDEN NAME="keys" VALUE="editOpenType">
            <INPUT TYPE=HIDDEN NAME="ratio" VALUE="">
            <INPUT TYPE=HIDDEN NAME="new_ratio" VALUE="">
            <INPUT TYPE=HIDDEN NAME="enable" VALUE="Y">
            <INPUT TYPE=HIDDEN NAME="ag_name" VALUE="">
            <INPUT TYPE=HIDDEN NAME="userlv" VALUE="<?php echo $userlv?>">
            <INPUT TYPE=HIDDEN NAME="line_chang" VALUE="<?php echo $open?>">
            <input type=HIDDEN name="SS" value="">
            <input type=HIDDEN name="SR" value="">
            <input type=HIDDEN name="TS" value="">
            <input type="hidden" name="s_low_order_gold" value="">
            <input type="hidden" name="s_low_order_gold_pc" value="">
            <table width="780" border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
                <tr class="m_title_edit">
                    <td colspan="2" ><h4><?php echo $Mem_Betting_data?><?php echo $Mem_Settings?></h4></td>
                </tr>
                <tr class="m_bc_ed">
                    <td width="140" class="m_co_ed"><?php echo $Mem_Games_Available?> :</td>
                    <td align="left">
                        <select name="type" class="za_select">
                            <option label="A" <?php if($open=='A'){echo "selected";} ?> value="A">A</option>
                            <option label="B" <?php if($open=='B'){echo "selected";} ?> value="B">B</option>
                            <option label="C" <?php if($open=='C'){echo "selected";} ?> value="C">C</option>
                            <option label="D" <?php if($open=='D'){echo "selected";} ?> value="D">D</option>
                            <option label="" value="">未知</option>
                        </select>
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed"><?php echo $Mem_Bet_Way?> :</td>
                    <td align="left">
                        <select name="pay_type" class="za_select" onChange="MM_show()" disabled>
                            <option label="<?php echo $Mem_Credit?>" value="0" selected="selected"><?php echo $Mem_Credit?></option>
                            <option label="<?php echo $Mem_Cash?>" value="1"><?php echo $Mem_Cash?></option>
                        </select>
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_co_ed"><?php echo $Mem_Currency_setup?> :</td>
                    <td align="left">
                        <select name="currency" class="za_select" onChange="Chg_Mcy('now');Chg_Mcy('mx')">
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_RMB?>" <?php if($curtype=="RMB"){echo "selected";} ?> value="RMB" selected="selected"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_RMB?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_HKD?>" <?php if($curtype=="HKD"){echo "selected";} ?> value="HKD"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_HKD?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_USD?>" <?php if($curtype=="USD"){echo "selected";} ?> value="USD"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_USD?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_MYR?>" <?php if($curtype=="MYR"){echo "selected";} ?> value="MYR"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_MYR?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_SGD?>" <?php if($curtype=="SGD"){echo "selected";} ?> value="SGD"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_SGD?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_THB?>" <?php if($curtype=="THB"){echo "selected";} ?> value="THB"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_THB?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_GBP?>" <?php if($curtype=="GBP"){echo "selected";} ?> value="GBP"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_GBP?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_JPY?>" <?php if($curtype=="JPY"){echo "selected";} ?> value="JPY"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_JPY?></option>
                            <option label="<?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_EUR?>" <?php if($curtype=="EUR"){echo "selected";} ?> value="EUR"><?php echo $Mem_radio_RMB?>-&gt;<?php echo $Mem_radio_EUR?></option>
                        </select>
                        <?php echo $Mem_Today_Exchange?> :<font color="#FF0033" id="mcy_now">0</font>&nbsp;(<?php echo $Mem_Today_Exchange_Reference?>)
                    </td>
                </tr>
                <?php
                if ($pay_type==0){
                    ?>
                    <tr id='credit_0' class="m_bc_ed">
                        <td class="m_co_ed" ><?php echo $Mem_Credit_Amount?> :</td>
                        <td align="left">
                            <input type="text" name="maxcredit" value="<?php echo $credit?>" size=12 maxlength="15" class="za_text" onKeyUp="Chg_Mcy('mx');" onKeyPress="return CheckKey();">&nbsp;
                            <?php
                            switch($curtype){
                                case 'HKD':
                                    echo $Mem_radio_HKD;
                                    break;
                                case 'USD':
                                    echo $Mem_radio_USD;
                                    break;
                                case 'MYR':
                                    echo $Mem_radio_MYR;
                                    break;
                                case 'SGD':
                                    echo $Mem_radio_SGD;
                                    break;
                                case 'THB':
                                    echo $Mem_radio_THB;
                                    break;
                                case 'GBP':
                                    echo $Mem_radio_GBP;
                                    break;
                                case 'JPY':
                                    echo $Mem_radio_JPY;
                                    break;
                                case 'EUR':
                                    echo $Mem_radio_EUR;
                                    break;
                                case 'RMB':
                                    echo $Mem_radio_RMB;
                                    break;
                                case 'NTD':
                                    echo $Mem_radio_NTD;
                                    break;
                                case '':
                                    echo $Mem_radio_RMB;
                                    break;
                            }
                            ?>:<font color="#FF0033" id="mcy_mx">0</font></font>
                        </td>
                    </tr>
                    <?php
                }else if ($pay_type==1){
                    ?>
                    <tr id='credit_1' class="m_bc_ed">
                        <td class="m_co_ed" width="140"><?php echo $Mem_Cash?> :</td>
                        <td align="left">
                            <?php echo $money?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Mem_Cash?>请到现金系统修改
                        </td>

                    </tr>
                    <?php
                }
                ?>
                <tr class="m_bc_ed">
                    <td class="m_co_ed">公司入款 :</td>
                    <td align="left">
                        <select name="offerStatus" class="za_select">
                            <option label="" value="">未知</option>
                            <option label="不优惠" <?php if($youhui==0){echo "selected";} ?> value="0">不优惠</option>
                            <option label="优惠" <?php if($youhui==1){echo "selected";} ?> value="1">优惠</option>
                        </select>
                    </td>
                </tr>
                <tr class="m_bc_ed" align="center">
                    <td colspan="2">
                        <input type=SUBMIT name="OK" value="<?php echo $Mem_Confirm?>" class="za_button">
                        &nbsp; &nbsp; &nbsp;
                        <input type=BUTTON name="FormsButton2" value="<?php echo $Mem_Cancle?>" id="FormsButton2" onClick="window.location.replace('user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv?>&langx=<?php echo $langx?>&enable=Y');" class="za_button">
                    </td>
                </tr>
            </table>
        </form>
    </div>


    <script>
        function SubChk(){
            var Numflag = 0;
            var Letterflag = 0;
            var pwd = document.all.password.value;
//        console.log(pwd);
            if(pwd){ // 密码有输入才需要验证
                for (idx = 0; idx < pwd.length; idx++) {
                    //====== 密碼只可使用字母(不分大小寫)與數字
                    if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
                        alert("<?php echo $Mem_PasswordEnglishNumber_6_Characters_12_CharactersMax?>");
                        return false;
                    }
                    if ((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z')){
                        Letterflag++;
                    }
                    if ((pwd.charAt(idx)>= "0" && pwd.charAt(idx) <= "9")){
                        Numflag++;
                    }
                }
                var msg = "";
                if (Numflag == 0 || Letterflag == 0) {
                    alert('<?php echo $Mem_PasswordEnglishNumber?>');return false;
                } else if (Letterflag >= 1 && Letterflag <= 3) {
                    msg = "1";
                } else if (Letterflag >= 4 && Letterflag <= 8) {
                    msg = "2";
                } else if (Letterflag >= 9 && Letterflag <= 11) {
                    msg = "3";
                } else {
                    return false;
                }

                if(pwd.length < 6 ){alert('<?php echo $Mem_NewPassword_6_Characters?>');return false;}
                if(pwd.length > 15 ){alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');return false;}
            }
            if(pwd != document.all.passwd.value){
                document.all.password.focus(); alert("<?php echo $Mem_PasswordConfirmError?>"); return false; }
            document.all.OK.disabled = true;
            document.all.FormsButton.disabled = true;
            if(!confirm("<?php echo $Mem_Whether_Edit?> <?php echo $Mem_Member?> ?")){
                document.all.OK.disabled = false;
                document.all.FormsButton.disabled = false;
                return false;
            }
            if (document.all.keys.value == 'add' && document.all.new_ratio.value != 1 ){
                alert('您已經改變了會員的幣值與網站設定幣值不同，\n\n所有的單場单注限额將被歸零，\n\n請重新進入詳細設定更新.');
            }
            if (document.all.keys.value == 'edit' && document.all.type.value != document.all.line_chang.value){
                alert('<?php echo $Mem_alert_type?>');
            }

        }

        function SubChk1(){
            if(!confirm("<?php echo $Mem_Whether_Edit?> <?php echo $Mem_Member?>盘口信息?")){
                document.all.OK.disabled = false;
                document.all.FormsButton2.disabled = false;
                return false;
            }
            if (document.all.keys.value == 'add' && document.all.new_ratio.value != 1 ){
                alert('您已經改變了會員的幣值與網站設定幣值不同，\n\n所有的單場单注限额將被歸零，\n\n請重新進入詳細設定更新.');
            }
            if (document.all.keys.value == 'edit' && document.all.type.value != document.all.line_chang.value){
                alert('<?php echo $Mem_alert_type?>');
            }

        }

        /*function MM_show(){
            var p,obj0,obj1;
            p=document.myFORM.pay_type.value;
            obj0=credit_0.style;
            obj1=credit_1.style;

            obj0.display=(p==1)?'none':'block';
            obj1.display=(p==0)?'none':'block';
        }*/

        function Chg_Mcy(a){
            curr=new Array();
            curr_now=new Array();
            curr['RMB']=<?php echo $crow['RMB_Rate']?>;
            curr['HKD']=<?php echo $crow['HKD_Rate']?>;
            curr['USD']=<?php echo $crow['USD_Rate']?>;
            curr['MYR']=<?php echo $crow['MYR_Rate']?>;
            curr['SGD']=<?php echo $crow['SGD_Rate']?>;
            curr['THB']=<?php echo $crow['THB_Rate']?>;
            curr['GBP']=<?php echo $crow['GBP_Rate']?>;
            curr['JPY']=<?php echo $crow['JPY_Rate']?>;
            curr['EUR']=<?php echo $crow['EUR_Rate']?>;

            curr_now['RMB']=<?php echo $crow['RMB_Rates']?>;
            curr_now['HKD']=<?php echo $crow['HKD_Rates']?>;
            curr_now['USD']=<?php echo $crow['USD_Rates']?>;
            curr_now['MYR']=<?php echo $crow['MYR_Rates']?>;
            curr_now['SGD']=<?php echo $crow['SGD_Rates']?>;
            curr_now['THB']=<?php echo $crow['THB_Rates']?>;
            curr_now['GBP']=<?php echo $crow['GBP_Rates']?>;
            curr_now['JPY']=<?php echo $crow['JPY_Rates']?>;
            curr_now['EUR']=<?php echo $crow['EUR_Rates']?>;


            if (document.all.ratio.value==''){
                tmp=document.all.currency.options[document.all.currency.selectedIndex].value;
                ratio=eval(curr[tmp]);
                ratio_now=eval(curr_now[tmp]);
                document.all.new_ratio.value=ratio;
            }else{
                ratio=eval(document.all.ratio.value);
                ratio_now=eval(document.all.new_ratio.value);
            }
            if (a=='mx')
            {
                tmp_count=ratio*eval(document.all.maxcredit.value);
                tmp_count=Math.round(tmp_count*100);
                tmp_count=tmp_count/100;
                document.all.mcy_mx.innerHTML=tmp_count;
            }
            if (a=='now')
            {
                document.all.mcy_now.innerHTML=ratio_now;
            }
        }

        function CheckKey(){
            if(event.keyCode < 48 || event.keyCode > 57){alert("<?php echo $Mem_Enter_Numbers?>"); return false;}
        }
        function onload() {
            var obj_type = document.getElementById('type');
            obj_type.value = '<?php echo $type?>';
            var obj_pay_type = document.getElementById('pay_type');
            obj_pay_type.value = '<?php echo $pay_type?>';
            var obj_currency = document.getElementById('currency');
            obj_currency.value = '<?php echo $curtype?>';

            Chg_Mcy('now');
            Chg_Mcy('mx');
            //MM_show();
        }
        //建議帳號用
        function chg_username(newname) {
            document.myFORM.username.value=newname;
        }
        function selchg(s1,s2) {
            if (s1.selectedIndex==(s1.length-1)) {
                s2.selectedIndex = s2.length-1;
            }
        }

        //佔成制下拉霸更換時頁面更新
        function winloss_type_change() {
//不做動作
        }

    </script>

    </body>
    </html>
    <?php
}

?>