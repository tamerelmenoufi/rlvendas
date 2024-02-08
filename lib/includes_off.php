<?php
error_reporting(0);
include "connect.php";
$con = AppConnect();
include "confYobom.php";
include "config.php";
include "utils.php";
include "fn.php";
include "vendor/mercado_pago/classes.php";
include "vendor/cielo/classes.php";
include "vendor/mottu/classes.php";
// include "AppWapp.php";
$md5 = md5(date("YmdHis"));
