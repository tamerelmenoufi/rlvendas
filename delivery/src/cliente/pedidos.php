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
                $q = "select a.*, b.descricao as situacao_entrega from vendas a left join delivery_status b on a.delivery->>'$.situation' = b.cod where 
                                                a.app = 'delivery' and 
                                                a.cliente = '{$_SESSION['AppCliente']}' and 
                                                a.situacao = 'pago' and a.deletado != '1' order by a.codigo desc";
                $r = mysqli_query($con, $q);
                if($d = mysqli_fetch_object($r)){
                    $delivery = json_decode($d->delivery);
            ?>
            <div class="card">
                <h5 class="card-header">Pedido #<?=$d->codigo?></h5>
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
                        <div><b>Total</b></div>
                        <span><b>R$ <?=number_format(($d->valor + $d->taxa - $d->desconto + $d->acrescimo), 2,',', false)?></b></span>
                    </div>
                    <?php
                    if($delivery->deliveryMan->id){
                    ?>
                    <div class="d-flex justify-content-between mt-3">
                        <div>Entregador</div>
                        <span><?=$delivery->deliveryMan->name?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Telefone (Entregador)</div>
                        <span><?='('.$delivery->deliveryMan->ddd.') '.$delivery->deliveryMan->phone?></span>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="d-flex justify-content-start">
                        <div>Situação </div>
                        <span> <?=(($d->situacao_entrega)?:'Em Produção')?></span>
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