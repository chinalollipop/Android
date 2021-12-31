<?php
session_start();
include "../../../../app/member/include/address.mem.php";
include "../../../../app/member/include/config.inc.php";

$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // fydj lhdj
$uid = $_SESSION['Oid'];
$testuid = '3e3d444a6054eae7c22cra8' ;
$aviaTryDomain = getSysConfig('avia_try_domain'); // 泛亚电竞试玩地址
$thunFireTryDomain = getSysConfig('thunfire_try_domain'); // 雷火电竞试玩地址

if($gametype=='fydj'){  // 泛亚
    $dj_url = '/app/member/avia/avia_api.php?action=getLaunchGameUrl';
}else{
    $dj_url = '/app/member/thunfire/fire_api.php?action=getLaunchGameUrl';
}

?>
<style>
.page-dianjing iframe{background: #fff;}
</style>

<div class="page-dianjing">
    <iframe id="body_dzjj" name="body_dzjj" src="<?php echo BROWSER_IP.$dj_url;?>" width="100%" height="100%" frameborder="0">

    </iframe>
</div>
