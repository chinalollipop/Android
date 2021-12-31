<?php
session_start();
include "../../../../app/member/include/config.inc.php";

header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

$companyName = COMPANY_NAME;

?>

<html>
<head>
    <title><?php echo $companyName;?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon">
    <meta name="keywords" content="<?php echo $companyName;?>">
    <meta name="description" content="<?php echo $companyName;?>">

    <style>
        body{margin: 0;padding: 0}
        img{width: 100%;}
    </style>
</head>

<body >

<img class="kidnap" src="../../images/fjcjc.jpg"/>

</body>
<script type="text/javascript" src="/js/jquery.js"></script>

</html>
