<?php
    include("../../lib/includes.php");

    include("prod_conf.php");

    if($_POST['opc']){
        $query = "update vendas_produtos set situacao = '{$_POST['opc']}' where codigo = '{$_POST['cod']}'";
        mysqli_query($con, $query);
        exit();
    }

?>
<style>

    .painel{
        position:fixed;
        width:100%;
        left:0px;
        top:0px;
        bottom:0px;
        overflow:auto;
    }

    /* .sanduiches{
        position:fixed;
        width:50%;
        right:0px;
        top:0px;
        bottom:0px;
        overflow:auto;
    } */

    /* ===== Scrollbar CSS ===== */
    /* Firefox */
    * {
        scrollbar-width: auto;
        scrollbar-color: #ccc #ffffff;
    }

    /* Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 4px;
    }

    *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 2px;
        border: 0;
    }

</style>
<div class="p-3">
    <div class="row">
        <?php

            $query = "select a.*, b.mesa as mesa from vendas_produtos a left join mesas b on a.mesa = b.codigo where a.situacao in('p','i') and a.deletado != '1' and JSON_EXTRACT(produto_json, '$.categoria.codigo') in ({$Categoria}) order by a.data asc";
            $result = mysqli_query($con, $query);

            while($d = mysqli_fetch_object($result)){

                $pedido = json_decode($d->produto_json);
                $sabores = false;
                $ListaPedido = [];
                for($i=0; $i < count($pedido->produtos); $i++){
                    $ListaPedido[] = $pedido->produtos[$i]->descricao;
                }
                if($ListaPedido) $sabores = implode(', ', $ListaPedido);

        ?>
        <div class="col-3">

            <div class="card bg-light mb-3">
                <div class="card-header"><b>MESA:</b> <?=$d->mesa?></div>
                <div class="card-body">
                    <p class="card-text">
                        <?=$d->quantidade?> X <?=$pedido->categoria->descricao?>
                        - <?=$pedido->medida->descricao?> (<?=$sabores?>)<br>
                        <span class="card-text" style="color:red;">
                        <?= $d->produto_descricao?></span>
                    </p>
                </div>
            </div>

        </div>
    <?php
        }
    ?>
    </div>
</div>
<script>
    $(function(){

        // window.location.href='./?geral';

    })



</script>