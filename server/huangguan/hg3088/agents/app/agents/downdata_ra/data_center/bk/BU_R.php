<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(dirname(__FILE__)))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
    require("../../../include/config.inc.php");
    require_once("../../../../../../common/sportCenterData.php");
    require("../../../include/curl_http.php");
    require_once("../../../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$dcRedisObj = new Ciredis('datacenter');
$keyList=DATA_CENTER_PREFIX."_BU_R_List";
$keyListLen = $dcRedisObj->lenMessage($keyList);
$keyListLen[0] = $keyListLen[0] > 3000 ? 3000 :$keyListLen[0]+1;

$result=[];
$insertKeys = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,MB_Team, TG_Team, MB_Team_tw, TG_Team_tw, MB_Team_en, TG_Team_en,M_Date,M_Time,M_Start,M_League,M_League_tw, M_League_en,M_Type,MB_MID,TG_MID)VALUES";
for($i=0;$i<$keyListLen[0];$i++) {
    $gid = $rowLocal='';
    $gid = $dcRedisObj->popMessage(DATA_CENTER_PREFIX."_BU_R_List");
    if($gid[0]>0){
        $checkLocalSql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$gid[0];
        $checkLocalResult = mysqli_query($dbMasterLink,$checkLocalSql);
        $rowLocal = mysqli_num_rows($checkLocalResult);
        if($rowLocal==0 || $rowLocal==1){
            $checkSql="select MID,Type,MB_Team, TG_Team, MB_Team_tw, TG_Team_tw, MB_Team_en, TG_Team_en,M_Date,M_Time,M_Start,M_League,M_League_tw,M_League_en,M_Type,MB_MID,TG_MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$gid[0];
            $checkresult = mysqli_query($dbCenterMasterDbLink,$checkSql);
            $row = mysqli_fetch_assoc($checkresult);
            if($rowLocal==0){
                if($row[MID]!=''&&$row[Type]!=''&&$row[MB_Team]!=''&&$row[TG_Team]!=''&& $row[MB_Team_tw]!=''&& $row[TG_Team_tw]!=''&&$row[MB_Team_en]!=''&& $row[TG_Team_en]!=''&&
                    $row[M_Date]!=''&&$row[M_Time]!=''&&$row[M_Start]!=''&& $row[M_League]!=''&&$row[M_League_tw]!=''&& $row[M_League_en]!=''&&$row[M_Type]!=''&&$row[MB_MID]!=''&& $row[TG_MID]!=''){
                    $insertValue=$insertSql='';
                    $insertValue="('$row[MID]','$row[Type]','$row[MB_Team]','$row[TG_Team]','$row[MB_Team_tw]','$row[TG_Team_tw]','$row[MB_Team_en]','$row[TG_Team_en]','$row[M_Date]','$row[M_Time]','$row[M_Start]','$row[M_League]','$row[M_League_tw]','$row[M_League_en]','$row[M_Type]','$row[MB_MID]','$row[TG_MID]')";
                    $insertSql=$insertKeys.$insertValue;
                    if(mysqli_query($dbMasterLink,$insertSql)){ echo "Success {$gid[0]}\n\r"; }else{echo " Failed {$gid[0]}\n\r";}
                }else{
                    echo "data have null \n\r";
                }
            }else{
                if($row[MID]!=''&&$row[Type]!=''&&$row[MB_Team]!=''&&$row[TG_Team]!=''&& $row[MB_Team_tw]!=''&& $row[TG_Team_tw]!=''&&$row[MB_Team_en]!=''&& $row[TG_Team_en]!=''&&
                    $row[M_Date]!=''&&$row[M_Time]!=''&&$row[M_Start]!=''&&$row[M_League]!=''&&$row[M_League_tw]!=''&& $row[M_League_en]!=''&&$row[M_Type]!=''&&$row[MB_MID]!=''&& $row[TG_MID]!=''){
                    $updateValue="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set Type='$row[Type]',MB_Team='$row[MB_Team]',TG_Team='$row[TG_Team]',MB_Team_tw='$row[MB_Team_tw]',TG_Team_tw='$row[TG_Team_tw]',MB_Team_en='$row[MB_Team_en]',TG_Team_en='$row[TG_Team_en]',M_Date='$row[M_Date]',M_Time='$row[M_Time]',M_Start='$row[M_Start]',M_League='$row[M_League]',M_League_tw='$row[M_League_tw]',M_League_en='$row[M_League_en]',M_Type='$row[M_Type]',MB_MID='$row[MB_MID]',TG_MID='$row[TG_MID]' where MID=$row[MID]";
                    if(mysqli_query($dbMasterLink,$updateValue)){ echo "Success {$gid[0]}\n\r"; }else{echo " Failed {$gid[0]}\n\r";}
                }else{
                    echo "update data have null \n\r";
                }
            }

        }else{
            echo "Exist more gid \n\r";
        }
    }else{
        echo "Failed0 no gid \n\r";
    }
}

echo $keyListLen[0].' | '.date('Y-m-d H:i:s',time())."\n\r";


