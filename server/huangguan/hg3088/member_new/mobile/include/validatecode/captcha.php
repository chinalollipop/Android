<?php
/**
 * 生成验证码
 * Date: 2018/10/11
 */
session_start();
require_once './ValidateCode.php';
$oVerify = new ValidateCode();
$oVerify->doimg();
$_SESSION['authcode'] = $oVerify->getCode();
