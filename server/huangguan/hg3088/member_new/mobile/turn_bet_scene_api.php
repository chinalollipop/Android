<?php
/**
 * /turn_bet_scene_api.php  投注限额接口
 *
 */
include_once('include/config.inc.php');


require ("include/curl_http.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
}

$row=array();

$sql = "select ID,UserName as uname,Pay_Type,Status from ".DBPREFIX.MEMBERTABLE." where ID='{$_SESSION['userid']}' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$row['FT_R_Bet']	= FT_R_Bet;
$row['FT_R_Scene']	= FT_R_Scene;
$row['FT_OU_Bet']	= FT_OU_Bet;
$row['FT_OU_Scene']	= FT_OU_Scene;
$row['FT_VR_Bet']	= FT_VR_Bet;
$row['FT_VR_Scene']	= FT_VR_Scene;
$row['FT_VOU_Bet']	= FT_VOU_Bet;
$row['FT_VOU_Scene']= FT_VOU_Scene;
$row['FT_RE_Bet']	= FT_RE_Bet;
$row['FT_RE_Scene']	= FT_RE_Scene;
$row['FT_ROU_Bet']	= FT_ROU_Bet;
$row['FT_ROU_Scene']= FT_ROU_Scene;
$row['FT_VRE_Bet']	= FT_VRE_Bet;
$row['FT_VRE_Scene']= FT_VRE_Scene;
$row['FT_VROU_Bet']	= FT_VROU_Bet;
$row['FT_VROU_Scene']= FT_VROU_Scene;
$row['FT_RM_Bet']	= FT_RM_Bet;
$row['FT_RM_Scene']	= FT_RM_Scene;
$row['FT_EO_Bet']	= FT_EO_Bet;
$row['FT_EO_Scene']	= FT_EO_Scene;
$row['FT_M_Bet']	= FT_M_Bet;
$row['FT_M_Scene']	= FT_M_Scene;
$row['FT_PD_Bet']	= FT_PD_Bet;
$row['FT_PD_Scene']	= FT_PD_Scene;
$row['FT_T_Bet']	= FT_T_Bet;
$row['FT_T_Scene']	= FT_T_Scene;
$row['FT_F_Bet']	= FT_F_Bet;
$row['FT_F_Scene']	= FT_F_Scene;
$row['FT_P_Bet']	= FT_P_Bet;
$row['FT_P_Scene']	= FT_P_Scene;
$row['FT_PR_Bet']	= FT_PR_Bet;
$row['FT_PR_Scene']	= FT_PR_Scene;
$row['FT_P3_Bet']	= FT_P3_Bet;
$row['FT_P3_Scene']	= FT_P3_Scene;
$row['BK_R_Bet']	= BK_R_Bet;
$row['BK_R_Scene']	= BK_R_Scene;
$row['BK_OU_Bet']	= BK_OU_Bet;
$row['BK_OU_Scene']	= BK_OU_Scene;
$row['BK_VR_Bet']	= BK_VR_Bet;
$row['BK_VR_Scene']	= BK_VR_Scene;
$row['BK_VOU_Bet']	= BK_VOU_Bet;
$row['BK_VOU_Scene']= BK_VOU_Scene;
$row['BK_RE_Bet']	= BK_RE_Bet;
$row['BK_RE_Scene']	= BK_RE_Scene;
$row['BK_ROU_Bet']	= BK_ROU_Bet;
$row['BK_ROU_Scene']= BK_ROU_Scene;
$row['BK_VRE_Bet']	= BK_VRE_Bet;
$row['BK_VRE_Scene']= BK_VRE_Scene;
$row['BK_VROU_Bet']	= BK_VROU_Bet;
$row['BK_VROU_Scene']= BK_VROU_Scene;
$row['BK_EO_Bet']	= BK_EO_Bet;
$row['BK_EO_Scene']	= BK_EO_Scene;
$row['BK_M_Bet']	= BK_M_Bet;
$row['BK_M_Scene']	= BK_M_Scene;
$row['BK_PR_Bet']	= BK_PR_Bet;
$row['BK_PR_Scene']	= BK_PR_Scene;
$row['BK_P3_Bet']	= BK_P3_Bet;
$row['BK_P3_Scene']	= BK_P3_Scene;

echo json_encode($row);


/*$sql="select M,MAX from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    exit(json_encode(["err"=>-1,"msg"=>"请求错误，请联系客服"]));
}else{
    echo json_encode($row);
}*/