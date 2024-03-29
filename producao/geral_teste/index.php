<?php
    include("../../lib/includes.php");

    include("prod_conf.php");

    if($_POST['opc']){
        $query = "update vendas_produtos set situacao = '{$_POST['opc']}' where codigo = '{$_POST['cod']}'";
        mysqli_query($con, $query);
        sisLog(
            [
                'query' => $query,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_POST['cod']
            ]
        );
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

    .clipe{
        position:absolute;
        right:10px;
        top:-10px;
        z-index: 10;
        font-size:30px;
        color:red;
    }
    div[pedido]{
        position:relative;
    }
    .acoes{
        font-size:10px;
        color:#ccc;
    }
    div[acao]{
        cursor:pointer;
    }
</style>
<div class="p-3">
    <div class="row">
        <?php

            $query = "select a.*, b.mesa as mesa from vendas_produtos a left join mesas b on a.mesa = b.codigo where a.situacao in('p','i') and a.deletado != '1' and JSON_EXTRACT(produto_json, '$.categoria.codigo') in ({$Categoria}) order by a.ordem asc";
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

            <div pedido="<?=$d->codigo?>" venda="<?=$d->venda?>" class="card text-white bg-dark mb-3">
                <i class="fa-solid fa-paperclip clipe"></i>
                <div class="card-header bg-dark"><b>MESA:</b> <?=(($d->mesa)?:'DELIVERY #'.$d->venda)?></div>
                <div class="card-body">
                    <p class="card-text">

                        <?=$d->quantidade?> x <?=$pedido->categoria->descricao?>
                        - <?=$pedido->medida->descricao?> (<?=$sabores?>)<br>
                        <span class="card-text" style="color:red;">
                        <?= $d->produto_descricao?></span>
                    </p>
                    <div class="row acoes">
                        <div class='col' acao='pedido'>
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Pedido
                        </div>
                        <div class='col' acao='preparar'>
                            <i class="fa-solid fa-check"></i> Preparando
                        </div>
                        <div class='col' acao='concluir'>
                            <i class="fa-solid fa-check-double"></i> Concluir
                        </div>
                    </div>
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