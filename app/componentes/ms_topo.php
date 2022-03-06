<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}'"));

?>
<style>
    .topoImg{
        height:50px;
        margin-left:10px;
    }
    .DadosTopo{
        text-align:right;
        font-size:12px;
        padding:5px;
        margin-right:10px;
        color:#fff;
    }
</style>
<div class="row">
    <div class="col-4">
        <img class="topoImg" src="img/logo.png" />
    </div>
    <div class="col-8">
        <?php
            if($c->telefone){
        ?>
            <div class="DadosTopo"><?=$c->telefone?> <?=$c->nome?></div>
        <?php
            }
            if($m->mesa){
        ?>
            <div class="DadosTopo">Pedido <?=$m->mesa?></div>
        <?php
            }
        ?>
    </div>
</div>