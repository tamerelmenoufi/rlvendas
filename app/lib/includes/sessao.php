<?php
    include("../../lib/includes/includes.php");

    if($_GET['ms_cli_codigo']) $_SESSION['ms_cli_codigo'] = $_GET['ms_cli_codigo'];

    echo "Sessao Renovada: ".$_SESSION['ms_cli_codigo'];
?>