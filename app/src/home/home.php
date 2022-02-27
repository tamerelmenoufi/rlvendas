<?php
    include("../../../lib/includes.php");

    if($_GET['cliente']) $_SESSION['ms_cli_codigo'] = $_GET['cliente'];

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