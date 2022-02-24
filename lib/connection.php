<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '192.168.0.18') {
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'sis_yobom');
} else {
    define('DB_HOST', 'yobom.com.br');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'SenhaDoBanco');
    define('DB_DATABASE', 'app');
}

$con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$con) {
    die('Não foi possível conectar: ' . mysqli_connect_error());
}else{
    mysqli_set_charset($con,'utf8');
}
