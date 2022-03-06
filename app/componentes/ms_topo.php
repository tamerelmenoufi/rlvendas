<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}'"));

?>
<style>
    .topoImg{
        position:absolute;
        height:50px;
        left:10px;
    }
    .DadosTopo{
        text-align:right;
        font-size:11px;
    }
</style>
<div class="row">
    <div class="col-3">
        <img class="topoImg" src="img/logo.png" />
    </div>
    <div class="col-9">
        <?php
            if($c->telefone){
        ?>
            <p class="DadosTopo"><?=$c->telefone?> <?=$c->nome?></p>
        <?php
            }
            if($m->mesa){
        ?>
            <p class="DadosTopo"><?=$m->mesa?></p>
        <?php
            }
        ?>
    </div>
</div>