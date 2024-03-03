<?php
    include("../../lib/includes.php");
    // if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    // if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));
?>
<style>
     .seta<?=$md5?> {
        position: absolute;
        top: -10px;
        left: -17px;
        color:#a80e13;
        font-size:55px;
        z-index: 1;
    }

    .logo<?=$md5?> {
        position: absolute;
        top: 10px;
        left: 65px;
        height:35px;
        z-index: 1;
    }

    .rotulo<?=$md5?> {
        position: absolute;
        top: 10px;
        right: 0;
        height:35px;
        width: auto;
        background-color: #a80e13;
        padding: 5px;
        font-weight: bold;
        color:#fff;
        text-align:right;
        z-index: 1;
    }
</style>
<span class="rotulo<?=$md5?>"><i class="fa-solid fa-caret-left seta<?=$md5?>"></i> <?=$_POST['titulo']?></span>
    <img src="img/logo_interno.png" class="logo<?=$md5?>">

<script>
    $(function(){


    })
</script>