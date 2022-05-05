<?php
    include("../../../lib/includes.php");

    if($_GET['garcom']) $_SESSION['AppGarcom'] = $_GET['garcom'];
    if($_GET['cliente']) $_SESSION['AppCliente'] = $_GET['cliente'];
    if($_GET['pedido']) $_SESSION['AppPedido'] = $_GET['pedido'];
    if($_GET['Garcom']) $_SESSION['AppGarcom'] = $_GET['Garcom'];

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