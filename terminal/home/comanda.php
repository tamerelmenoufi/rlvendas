<?php
    include("../../lib/includes.php");
?>
<style>
    .comanda{
        position:absolute;
        right:0;
        top:0;
        bottom:0;
        width:40%;
        overflow:auto;
    }
    .itens<?=$md5?>{
        margin:10px;
    }
</style>
<div class="comanda">
<?php
    $query = "select * from categorias";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
    <div class="card itens<?=$md5?>">
        <div class="card-body">
            <li><?=$d->categoria?></li>
        </div>
    </div>
<?php
    }
?>
    <div style="position:fixed; right:20px; bottom:20px;">
        <button class="btn btn-success btn-lg">CONCLUIR COMPRA</button>
        <button class="btn btn-danger btn-lg">SAIR</button>
    </div>
</div>