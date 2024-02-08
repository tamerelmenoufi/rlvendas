<?php
    include("../../../lib/includes.php");

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:70px;
        top:0px;
        height:60px;
        background:#fff;
        padding-top:15px;
        z-index:1;
    }

</style>
<div class="PedidoTopoTitulo">
    <h4>Seus Pedidos</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">
            <?php
                $q = "select a.*, b.descricao as situacao_entrega from vendas a left join delivery_status b on a.delivery->>'$.Situacao' = b.cod where 
                                                a.app = 'delivery' and 
                                                a.cliente = '{$_SESSION['AppCliente']}' and 
                                                a.situacao = 'pago' and a.deletado != '1' order by a.codigo desc";
                $r = mysqli_query($con, $q);
                if($d = mysqli_fetch_object($r)){
                    $delivery = json_decode($d->delivery);
            ?>
            <div class="card">
                <h5 class="card-header">Pedido <?=$d->codigo?></h5>
                <div class="card-body">
                    <!-- <h5 class="card-title">Special title treatment</h5> -->
                    Valor: <?=number_format($d->valor, 2,',', false)?><br>
                    Taxa Entrega: <?=number_format($d->taxa, 2,',', false)?><br>
                    Desconto: <?=number_format($d->desconto, 2,',', false)?><br>
                    Acrescimo: <?=number_format($d->acrescimo, 2,',', false)?><br>
                    Valor: <?=number_format(($d->valor + $d->taxa - $d->desconto + $d->acrescimo), 2,',', false)?>
                    <h6 class="card-subtitle mb-2 text-muted">Entrega</h6>
                    Entregador: <?=$delivery->Entregador->Nome?><br>
                    Situação: <?=(($d->situacao_entrega)?:'Em Produção')?><br>

                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                </div>
            </div>
            <?php
                }
            ?>
            </div>
    </div>
</div>

<script>
    $(function(){


    })
</script>