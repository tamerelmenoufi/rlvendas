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
                    <div class="d-flex justify-content-between">
                        <div>Valor</div>
                        <span>R$ <?=number_format($d->valor, 2,',', false)?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>Taxa Entrega</div>
                        <span>R$ <?=number_format($d->taxa, 2,',', false)?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>Desconto</div>
                        <span>R$ <?=number_format($d->desconto, 2,',', false)?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>Acrescimo</div>
                        <span>R$ <?=number_format($d->acrescimo, 2,',', false)?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>Valor</div>
                        <span>R$ <?=number_format(($d->valor + $d->taxa - $d->desconto + $d->acrescimo), 2,',', false)?></span>
                    </div>
                    <?php
                    if($delivery->Entregador->Id){
                    ?>
                    <div class="d-flex justify-content-between">
                        <div>Entregador</div>
                        <span><?=$delivery->Entregador->Nome?></span>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="d-flex justify-content-between">
                        <div>Situação</div>
                        <span><?=(($d->situacao_entrega)?:'Em Produção')?></span>
                    </div>

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