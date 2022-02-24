<?php

if (session_id() === "") {
    session_start();
}

function getUrl()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }


    // if ($_SERVER['HTTP_HOST'] === 'localhost' or $_SERVER['HTTP_HOST'] === '192.168.0.18') return $protocol . "://localhost/yobom/";

    return $protocol . "://192.168.0.18/yobom/";
    // return 'http://lib.yobom.com.br/';
}

$caminho_vendor = getUrl() . "lib/vendor";

date_default_timezone_set('America/Manaus');

