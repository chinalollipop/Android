<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
.app_trust img{width: 100%;}
</style>

<div class="app_trust">
    <img src="/<?php echo $tplNmaeSession;?>images/app_xr.jpg" alt="APP信任教程">
</div>

<script type="text/javascript">
    $(function () {



    })
</script>