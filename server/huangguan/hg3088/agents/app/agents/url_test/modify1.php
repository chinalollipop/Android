<?php
require_once("../../../include/conn_ft.php");
require_once("../../../include/config.inc.php");

$url=base64_decode($_REQUEST['url']);
$updateSQL="update web_system set datasite='$url'";
mysqli_query($dbLink,$updateSQL);
header("location:http://lotus1.kk9000.com/admin/user_manager/url_test/test_url.php");
?>