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

        <div class="painel">
            <h4 style="position:fixed; top:0; height:40px; z-index:10; width:100%; padding-left:15px; padding-top:5px; background-color:#fff">Dados da cozinha (Produção de LANCHES)</h4>
        <table painel class="table table-striped table-hover" style="margin-top:40px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>MESA</th>
                    <th>QUANTIDADE</th>
                    <th>PRODUTO</th>
                    <th>AÇÃO</th>
                </tr>
            </thead>

            <tbody>
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
        <!-- <div class="card bg-light mb-3">
            <div class="card-body">
                <h5 class="card-title" style="paddig:0; margin:0; font-size:14px; font-weight:bold;">
                    <span style="font-size:20px;"><?=$d->quantidade?></span> <?=$pedido->categoria->descricao?>
                    - <?=$pedido->medida->descricao?> (<?=$sabores?>)
                </h5>
                <p class="card-text" style="padding:0; margin:0; text-align:right">
                    R$ <?= number_format($d->valor_unitario, 2, ',', '.') ?>
                </p>
                <p class="card-text" style="padding-left:15px; margin:0; font-size:14px; color:red;">
                    <?= $d->produto_descricao?>
                </p>
            </div>
        </div> -->
        <tr>

            <td>
                <div class="form-group form-check">
                    <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">
                </div>
            </td>
            <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><?=$d->mesa?></label></td>
            <td><label class="form-check-label <?=(($d->quantidade > 1)?'text-danger':false)?>" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>
            <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">
                <?=$pedido->categoria->descricao?>
                - <?=$pedido->medida->descricao?> (<?=$sabores?>)
                <p class="card-text" style="color:red;">
                <?= $d->produto_descricao?></p>
            </label></td>
            <td style="text-align:right"><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>
        </tr>


        <?php
            }
        ?>
            <tbody>
        </table>

            <output></output>

        </div>


<script>
    $(function(){

        // window.location.href='./?lanches';

    })



</script>