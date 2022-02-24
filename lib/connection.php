<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '192.168.0.18') {
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'sis_yobom');
} else {
    define('DB_HOST', '3.93.20.163');
    define('DB_USERNAME', 'yobom');
    define('DB_PASSWORD', 'Y0b0w20zz');
    define('DB_DATABASE', 'yobom');
}

$con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$con) {
    die('Não foi possível conectar: ' . mysqli_connect_error());
}else{
    mysqli_set_charset($con,'utf8');
}
