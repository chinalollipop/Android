<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];


require ("../../agents/include/traditional.$langx.inc.php");


if($_SESSION['Level'] == 'M') {
    $data=DBPREFIX.'web_system_data';
}else{
    $data=DBPREFIX.'web_agents_data';
}
$sql = "select * from $data where Oid='$uid' and UserName='$loginname'";

$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$name=$row['UserName'];
$password=$row['PassWord'];
$alias=$row['Alias'];
switch ($lv){
    case 'M':
        $Title=$Mem_Manager;
        $shares=100;
        break;
    case 'A':
        $Title=$Mem_Super;
        $shares=100-$row['A_Point'];
        break;
    case 'B':
        $Title=$Mem_Corprator;
        $shares=$row['B_Point'];
        break;
    case 'C':
        $Title=$Mem_World;
        $shares=80;
        break;
    case 'D':
        $Title=$Mem_Agents;
        $shares=$row['D_Point'];
        break;
}
$action=$_REQUEST['action'];
if ($action==1){ // 修改基本资料
    $pasd=passwordEncryption(strtolower($_REQUEST["passwd"]),$name);
    $mysql="update $data set PassWord='$pasd' where Oid='$uid'";
    mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    $loginfo='更改密码'.$Title.':'.$name.' 密码:'.$pasd.' (更改成功)';
    innsertSystemLog($loginname,$lv,$loginfo);
    echo "<Script Language=javascript>alert('$Mem_ChangePasswordSuccess');top.location.href='/';</script>";
}else{
    ?>
    <html>
    <head>
        <title>基本資料設定</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">

        </style>

    </head>
    <body >
    <dl class="main-nav"><dt><?php echo $Mem_Details.$Mem_Settings?></dt>
        <dd>
            <?php echo $Title?>
            -- <?php echo $Mem_Details.$Mem_Settings?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Mem_Account?>:<?php echo $name?>
            -- <?php echo $Mem_Name?>:<?php echo $alias?>
        </dd>
    </dl>
    <div class="main-ui">
        <table class="m_tab_ed">
            <tr align="center">
                <td colspan="2" ><?php echo $Mem_Basic_data?><?php echo $Mem_Settings?></td>
            </tr>
            <form method=post action="" onSubmit="return SubChk();">
                <tr >
                    <td width="163" align="right"><?php echo $Mem_Account?>:</td>
                    <td width="534" align="left"><?php echo $name?></td>
                </tr>
                <tr >
                    <td align="right"><?php echo $Mem_Credit?>:</td>
                    <td align="left" ><?php echo $row['Credit']?></td>
                </tr>

                <tr >
                    <td align="right"><?php echo $Mem_Old_Password?>:</td>
                    <td align="left">
                        <input type="password" name="passwd_old" value="" size=12 maxlength="15"  class="za_text">
                    </td>
                </tr>

                <tr  >
                    <td align="right"><?php echo $Mem_New_Password?>:</td>
                    <td align="left">
                        <input type="password" name="passwd" value="" size=12 maxlength="15"  class="za_text">
                    </td>
                </tr>
                <tr  >
                    <td align="right"><?php echo $Mem_Cofirm_Password?>:</td>
                    <td align="left">
                        <input type="password" name="REpasswd" value="" size=12 maxlength="15" class="za_text">
                    </td>
                </tr>

                <tr >
                    <td align="right"><?php echo $Rep_Percent?>:</td>
                    <td align="left"><?php echo $shares?>%</td>
                </tr>

                <tr >
                    <td height="25" colspan="2">◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
                </tr>

                <tr >

                    <td colspan="2">

                        <input type=submit name="OK" value="<?php echo $Mem_Confirm?>" class="za_button">

                        <input type=button name="cancel" value="<?php echo $Mem_Cancle?>" class="za_button" onClick="javascript:window.close();">
                    </td>
                </tr>

                <input type="hidden" name="action" value="1">
                <input type="hidden" name="uid" value="<?php echo $uid?>">
            </form>
        </table>
        <br>
        <table  class="m_tab_ed">
            <tr >
                <td width="70"><?php echo $Rep_Soccer?></td>
                <td width='57'><?php echo $Rep_Wtype_r?></td>
                <td width='57'><?php echo $Rep_Wtype_ou?></td>
                <td width='57'><?php echo $Rep_Wtype_rb?></td>
                <td width="57"><?php echo $Rep_Wtype_rou?></td>
                <td width="57"><?php echo $Rep_Wtype_oe?></td>
                <td width="57"><?php echo $Rep_Wtype_mr?></td>
                <td width="57"><?php echo $Rep_Wtype_mm?></td>
                <td width="57"><?php echo $Rep_Wtype_pd?></td>
                <td width="57"><?php echo $Rep_Wtype_t?></td>
                <td width="57"><?php echo $Rep_Wtype_f?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_pr?></td>
                <td width="57"><?php echo $Rep_Wtype_pc?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_bet?></td>
                <td><?php echo $row['FT_R_Bet']?></td>
                <td><?php echo $row['FT_OU_Bet']?></td>
                <td><?php echo $row['FT_RE_Bet']?></td>
                <td><?php echo $row['FT_ROU_Bet']?></td>
                <td><?php echo $row['FT_EO_Bet']?></td>
                <td><?php echo $row['FT_RM_Bet']?></td>
                <td><?php echo $row['FT_M_Bet']?></td>
                <td><?php echo $row['FT_PD_Bet']?></td>
                <td><?php echo $row['FT_T_Bet']?></td>
                <td><?php echo $row['FT_F_Bet']?></td>
                <td><?php echo $row['FT_P_Bet']?></td>
                <td><?php echo $row['FT_PR_Bet']?></td>
                <td><?php echo $row['FT_P3_Bet']?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="13" ></td>
            </tr>
        </table>
        <br>
        <table class="table_3">
            <tr>
                <td align='left'>
                    <table  class="m_tab_ed">
                        <tr >
                            <td width="70"><?php echo $Rep_Bask?></td>
                            <td width='57'><?php echo $Rep_Wtype_r?></td>
                            <td width='57'><?php echo $Rep_Wtype_ou?></td>
                            <td width='57'><?php echo $Rep_Wtype_rb?></td>
                            <td width="57"><?php echo $Rep_Wtype_rou?></td>
                            <td width="57"><?php echo $Rep_Wtype_oe?></td>
                            <td width="57"><?php echo $Rep_Wtype_pr?></td>
                            <td width="57"><?php echo $Rep_Wtype_pc?></td>
                            <td width="57"><?php echo $Rep_Wtype_cs?><br>赛事</td>
                            <td width="68"><?php echo $Rep_Wtype_cs?><br>赛事</td>
                        </tr>
                        <tr class="m_cen" >
                            <td  class="m_ag_ed"><?php echo $mem_scence?></td>
                            <td><?php echo $row['BK_R_Scene']?></td>
                            <td><?php echo $row['BK_OU_Scene']?></td>
                            <td><?php echo $row['BK_RE_Scene']?></td>
                            <td><?php echo $row['BK_ROU_Scene']?></td>
                            <td><?php echo $row['BK_EO_Scene']?></td>
                            <td><?php echo $row['BK_PR_Scene']?></td>
                            <td><?php echo $row['BK_P3_Scene']?></td>
                            <td class="m_ag_ed"><?php echo $mem_scence?></td>
                            <td><?php echo $row['FS_FS_Scene']?></td>
                        </tr>
                        <tr class="m_cen" >
                            <td class="m_ag_ed"><?php echo $mem_bet?></td>
                            <td><?php echo $row['BK_R_Bet']?></td>
                            <td><?php echo $row['BK_OU_Bet']?></td>
                            <td><?php echo $row['BK_RE_Bet']?></td>
                            <td><?php echo $row['BK_ROU_Bet']?></td>
                            <td><?php echo $row['BK_EO_Bet']?></td>
                            <td><?php echo $row['BK_PR_Bet']?></td>
                            <td><?php echo $row['BK_P3_Bet']?></td>
                            <td class="m_ag_ed"><?php echo $mem_bet?></td>
                            <td><?php echo $row['FS_FS_Bet']?></td>
                        </tr>
                        <tr class="m_cen" >
                            <td  class="m_ag_ed">&nbsp;</td>
                            <td colspan="9" >&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <!--<td width="250" align='right'>
<table class="m_tab_ed">
  <tr >
    <td><?php /*echo $Rep_Wtype_cs*/?><br>赛事</td>
    <td width="68"><?php /*echo $Rep_Wtype_cs*/?><br>赛事</td>
  </tr>
  <tr class="m_cen">
    <td align="right" class="m_ag_ed" nowrap><?php /*echo $mem_turn_rate*/?> <font color="#CC0000">A</font></td>
    <td rowspan="4"></td>
  </tr>
  <tr class="m_cen">
    <td align="right"class="m_ag_ed"><font color="#CC0000">B</font></td>
  </tr>
  <tr class="m_cen">
    <td align="right"class="m_ag_ed"><font color="#CC0000">C</font></td>
  </tr>
  <tr class="m_cen">
    <td align="right"class="m_ag_ed"><font color="#CC0000">D</font></td>
  </tr>
  <tr class="m_cen">
    <td align="right"class="m_ag_ed"><?php /*echo $mem_scence*/?></td>
    <td><?php /*echo $row['FS_FS_Scene']*/?></td>
  </tr>
  <tr class="m_cen">
    <td align="right"class="m_ag_ed"><?php /*echo $mem_bet*/?></td>
    <td><?php /*echo $row['FS_FS_Bet']*/?></td>
  </tr>

</table>
</td>-->
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
            <tr >
                <td width="70"><?php echo $Rep_Base?></td>
                <td width='57'><?php echo $Rep_Wtype_r?></td>
                <td width='57'><?php echo $Rep_Wtype_ou?></td>
                <td width='57'><?php echo $Rep_Wtype_rb?></td>
                <td width="57"><?php echo $Rep_Wtype_rou?></td>
                <td width="57"><?php echo $Rep_Wtype_oe?></td>
                <td width="57"><?php echo $Rep_Wtype_mm?></td>
                <td width="57"><?php echo $Rep_Wtype_pd?></td>
                <td width="57"><?php echo $Rep_Wtype_t?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_pr?></td>
                <td width="57"><?php echo $Rep_Wtype_pc?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_scence?></td>
                <td><?php echo $row['BS_R_Scene']?></td>
                <td><?php echo $row['BS_OU_Scene']?></td>
                <td><?php echo $row['BS_RE_Scene']?></td>
                <td><?php echo $row['BS_ROU_Scene']?></td>
                <td><?php echo $row['BS_EO_Scene']?></td>
                <td><?php echo $row['BS_M_Scene']?></td>
                <td><?php echo $row['BS_PD_Scene']?></td>
                <td><?php echo $row['BS_T_Scene']?></td>
                <td><?php echo $row['BS_P_Scene']?></td>
                <td><?php echo $row['BS_P_Scene']?></td>
                <td><?php echo $row['BS_PR_Scene']?></td>
                <td><?php echo $row['BS_P3_Scene']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_bet?></td>
                <td><?php echo $row['BS_R_Bet']?></td>
                <td><?php echo $row['BS_OU_Bet']?></td>
                <td><?php echo $row['BS_RE_Bet']?></td>
                <td><?php echo $row['BS_ROU_Bet']?></td>
                <td><?php echo $row['BS_EO_Bet']?></td>
                <td><?php echo $row['BS_M_Bet']?></td>
                <td><?php echo $row['BS_PD_Bet']?></td>
                <td><?php echo $row['BS_T_Bet']?></td>
                <td><?php echo $row['BS_P_Bet']?></td>
                <td><?php echo $row['BS_P_Bet']?></td>
                <td><?php echo $row['BS_PR_Bet']?></td>
                <td><?php echo $row['BS_P3_Bet']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed">&nbsp;</td>
                <td colspan="12" >&nbsp;</td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
            <tr >
                <td width="70"><?php echo $Rep_Tennis?></td>
                <td width='57'><?php echo $Rep_Wtype_r?></td>
                <td width='57'><?php echo $Rep_Wtype_ou?></td>
                <td width='57'><?php echo $Rep_Wtype_rb?></td>
                <td width="57"><?php echo $Rep_Wtype_rou?></td>
                <td width="57"><?php echo $Rep_Wtype_oe?></td>
                <td width="57"><?php echo $Rep_Wtype_mm?></td>
                <td width="57"><?php echo $Rep_Wtype_pd?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_pr?></td>
                <td width="57"><?php echo $Rep_Wtype_pc?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_scence?></td>
                <td><?php echo $row['TN_R_Scene']?></td>
                <td><?php echo $row['TN_OU_Scene']?></td>
                <td><?php echo $row['TN_RE_Scene']?></td>
                <td><?php echo $row['TN_ROU_Scene']?></td>
                <td><?php echo $row['TN_EO_Scene']?></td>
                <td><?php echo $row['TN_M_Scene']?></td>
                <td><?php echo $row['TN_PD_Scene']?></td>
                <td><?php echo $row['TN_P_Scene']?></td>
                <td><?php echo $row['TN_PR_Scene']?></td>
                <td><?php echo $row['TN_P3_Scene']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_bet?></td>
                <td><?php echo $row['TN_R_Bet']?></td>
                <td><?php echo $row['TN_OU_Bet']?></td>
                <td><?php echo $row['TN_RE_Bet']?></td>
                <td><?php echo $row['TN_ROU_Bet']?></td>
                <td><?php echo $row['TN_EO_Bet']?></td>
                <td><?php echo $row['TN_M_Bet']?></td>
                <td><?php echo $row['TN_PD_Bet']?></td>
                <td><?php echo $row['TN_P_Bet']?></td>
                <td><?php echo $row['TN_PR_Bet']?></td>
                <td><?php echo $row['TN_P3_Bet']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed">&nbsp;</td>
                <td colspan="10" >&nbsp;</td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
            <tr>
                <td width="70"><?php echo $Rep_Voll?></td>
                <td width='57'><?php echo $Rep_Wtype_r?></td>
                <td width='57'><?php echo $Rep_Wtype_ou?></td>
                <td width='57'><?php echo $Rep_Wtype_rb?></td>
                <td width="57"><?php echo $Rep_Wtype_rou?></td>
                <td width="57"><?php echo $Rep_Wtype_oe?></td>
                <td width="57"><?php echo $Rep_Wtype_mm?></td>
                <td width="57"><?php echo $Rep_Wtype_pd?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_pr?></td>
                <td width="57"><?php echo $Rep_Wtype_pc?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_scence?></td>
                <td><?php echo $row['VB_R_Scene']?></td>
                <td><?php echo $row['VB_OU_Scene']?></td>
                <td><?php echo $row['VB_RE_Scene']?></td>
                <td><?php echo $row['VB_ROU_Scene']?></td>
                <td><?php echo $row['VB_EO_Scene']?></td>
                <td><?php echo $row['VB_M_Scene']?></td>
                <td><?php echo $row['VB_PD_Scene']?></td>
                <td><?php echo $row['VB_P_Scene']?></td>
                <td><?php echo $row['VB_PR_Scene']?></td>
                <td><?php echo $row['VB_P3_Scene']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_bet?></td>
                <td><?php echo $row['VB_R_Bet']?></td>
                <td><?php echo $row['VB_OU_Bet']?></td>
                <td><?php echo $row['VB_RE_Bet']?></td>
                <td><?php echo $row['VB_ROU_Bet']?></td>
                <td><?php echo $row['VB_EO_Bet']?></td>
                <td><?php echo $row['VB_M_Bet']?></td>
                <td><?php echo $row['VB_PD_Bet']?></td>
                <td><?php echo $row['VB_P_Bet']?></td>
                <td><?php echo $row['VB_PR_Bet']?></td>
                <td><?php echo $row['VB_P3_Bet']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed">&nbsp;</td>
                <td colspan="10" >&nbsp;</td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
            <tr>
                <td width="70"><?php echo $Rep_Other?></td>
                <td width='57'><?php echo $Rep_Wtype_r?></td>
                <td width='57'><?php echo $Rep_Wtype_ou?></td>
                <td width='57'><?php echo $Rep_Wtype_rb?></td>
                <td width="57"><?php echo $Rep_Wtype_rou?></td>
                <td width="57"><?php echo $Rep_Wtype_oe?></td>
                <td width="57"><?php echo $Rep_Wtype_mm?></td>
                <td width="57"><?php echo $Rep_Wtype_pd?></td>
                <td width="57"><?php echo $Rep_Wtype_t?></td>
                <td width="57"><?php echo $Rep_Wtype_f?></td>
                <td width="57"><?php echo $Rep_Wtype_p?></td>
                <td width="57"><?php echo $Rep_Wtype_pr?></td>
                <td width="57"><?php echo $Rep_Wtype_pc?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_scence?></td>
                <td><?php echo $row['OP_R_Scene']?></td>
                <td><?php echo $row['OP_OU_Scene']?></td>
                <td><?php echo $row['OP_RE_Scene']?></td>
                <td><?php echo $row['OP_ROU_Scene']?></td>
                <td><?php echo $row['OP_EO_Scene']?></td>
                <td><?php echo $row['OP_M_Scene']?></td>
                <td><?php echo $row['OP_PD_Scene']?></td>
                <td><?php echo $row['OP_T_Scene']?></td>
                <td><?php echo $row['OP_F_Scene']?></td>
                <td><?php echo $row['OP_P_Scene']?></td>
                <td><?php echo $row['OP_PR_Scene']?></td>
                <td><?php echo $row['OP_P3_Scene']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed"><?php echo $mem_bet?></td>
                <td><?php echo $row['OP_R_Bet']?></td>
                <td><?php echo $row['OP_OU_Bet']?></td>
                <td><?php echo $row['OP_RE_Bet']?></td>
                <td><?php echo $row['OP_ROU_Bet']?></td>
                <td><?php echo $row['OP_EO_Bet']?></td>
                <td><?php echo $row['OP_M_Bet']?></td>
                <td><?php echo $row['OP_PD_Bet']?></td>
                <td><?php echo $row['OP_T_Bet']?></td>
                <td><?php echo $row['OP_F_Bet']?></td>
                <td><?php echo $row['OP_P_Bet']?></td>
                <td><?php echo $row['OP_PR_Bet']?></td>
                <td><?php echo $row['OP_P3_Bet']?></td>
            </tr>
            <tr class="m_cen" >
                <td class="m_ag_ed">&nbsp;</td>
                <td colspan="12" >&nbsp;</td>
            </tr>
        </table>
        <br>
    </div>
    <script type="text/javascript" src="../../../js/agents/jquery.js"></script>
    <script type="text/javascript" src="../../../js/agents/jquery.md5.js"></script>
    <script Language="JavaScript">
        var old_password ='<?php echo $password?>' ;
        function SubChk(){
            if (document.all.passwd_old.value==''){
                document.all.passwd_old.focus();
                alert("<?php echo $Mem_OldPasswordPleaseKeyin?>");
                return false;
            }
            if (document.all.passwd.value==''){
                document.all.passwd.focus();
                alert("<?php echo $Mem_NewPasswordPleaseKeyin?>");
                return false;
            }
            if(document.all.passwd.value.length < 5 ){
                alert('<?php echo $Mem_NewPassword_6_Characters?>');
                return false;
            }
            if(document.all.passwd.value.length > 16 ){
                alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');
                return false;
            }
            var Numflag = 0;
            var Letterflag = 0;
            var pwd = document.all.passwd.value;
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
            if (Numflag == 0 || Letterflag == 0) { alert('<?php echo $Mem_PasswordEnglishNumber?>');return false;
            } else if (Letterflag >= 1 && Letterflag <= 3) {
                msg = "1";
            } else if (Letterflag >= 4 && Letterflag <= 8) {
                msg = "2";
            } else if (Letterflag >= 9 && Letterflag <= 11) {
                msg = "3";
            } else {
                return false;
            }
            if (document.all.REpasswd.value==''){
                document.all.REpasswd.focus();
                alert("<?php echo $Mem_CofirmpasswordPleasekeyin?>");
                return false;
            }

            if(document.all.passwd.value != document.all.REpasswd.value){
                document.all.passwd.focus(); alert("<?php echo $Mem_PasswordConfirmError?>"); return false;
            }

        }
    </script>

    </body>
    </html>
    <?php
}
?>