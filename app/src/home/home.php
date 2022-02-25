<?php
    include("../../lib/includes/includes.php");

    if($_GET['cliente']) $_SESSION['ms_cli_codigo'] = $_GET['cliente'];

?>
<style>

</style>

<object home componente="ms_barra_topo_fixo" ></object>
<object home componente="ms_card_promocoes_scroll" ></object>
<object home componente="ms_categoria_scroll" ></object>
<object home componente="ms_card_produtos_100" ></object>
<object home componente="ms_barra_fundo_fixo" ></object>

<script>

    //window.localStorage.setItem('ms_cli_codigo','2');

    $(function(){
        AppComponentes('home');
    })
</script>