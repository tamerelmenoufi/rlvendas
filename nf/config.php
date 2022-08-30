<?php

// CONEXAO PDO MySQL
$PDO = new PDO("mysql:host=yobom.com.br;dbname=app", "root", "SenhaDoBanco");


// ENDEREÇO DA API
$endpoint = "http://nf.mohatron.com/API-NFE/api-nfe/"; // COM BARRA NO FINAL


ini_set('display_errors', 'Off');
