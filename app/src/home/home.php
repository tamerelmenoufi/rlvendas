<?php
    include("../../../lib/includes.php");

    if($_GET['cliente']) $_SESSION['AppCliente'] = $_GET['cliente'];
    if($_GET['mesa']) $_SESSION['AppMesa'] = $_GET['mesa'];

?>
<style>

</style>

<object home componente="ms_principal" ></object>

<script>

    //window.localStorage.setItem('ms_cli_codigo','2');

    $(function(){
        AppComponentes('home');
    })
</script>