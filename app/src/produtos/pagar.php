<?php
    include("../../../lib/includes.php");

    $query = "select
                    sum(a.valor_total) as total,
                    b.nome,
                    b.telefone
                from vendas_produtos a
                    left join clientes b on a.cliente = b.codigo
                where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
    .card-title small{
        font-size:10px;
    }
    .card-title div{
        width:100%;
        text-align:right;
        font-size:14px;
        font-weight:bold;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?></h4>
</div>
<div class="col" style="margin-bottom:60px; margin-top:20px;">
    <div class="col-12">
        Valor total da compra R$ <?=number_format($d->total,2,',','.')?>


        <div class="card bg-light mb-3">
            <div class="card-header">Dados da Compra</div>
            <div class="card-body">
                <h5 class="card-title">
                    <small>Pedido</small>
                    <div><?=$_SESSION['AppPedido']?></div>
                </h5>
                <h5 class="card-title">
                    <small>Valor</small>
                    <div>R$ <?=number_format($d->total,2,',','.')?></div>
                </h5>
                <h5 class="card-title">
                    <small>Cliente</small>
                    <div><?="{$d->nome} {$d->telefone}"?></div>
                </h5>

            </div>
        </div>


    </div>
</div>


<script>
    $(function(){



    })
</script>