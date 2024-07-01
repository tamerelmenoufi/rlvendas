<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    

    $query = " SELECT
            (select count(*) from produtos where situacao = '1' and deletado != '1') as quantidade_produtos,
            (select count(*) from vendas where situacao = 'pago') as quantidade_vendas,
            (select count(*) from entregadores where deletado != '1') as quantidade_entregadores,
            (select count(*) from vendas where situacao = 'pago' and producao = 'entregue') as quantidade_entregue,

            (select sum(valor_total) from vendas where situacao = 'pago') as total_vendas,
            (select sum(valor_entrega) from vendas where situacao = 'pago') as total_entregas,
            (select sum(valor_entrega) from vendas where situacao = 'cancelado') as total_cancelados
         

    ";
    // $result = mysqli_query($con,$query);

    // $d = mysqli_fetch_object($result);
    
?>
<style>

</style>


<div class="m-3">
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo Geral</h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Produtos</span>
                <h1><?=number_format($d->quantidade_produtos,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Vendas</span>
                <h1><?=number_format($d->quantidade_vendas,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Entregadores</span>
                <h1><?=number_format($d->quantidade_entregadores,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-secondary" role="alert">
                <span>Entregas</span>
                <h1><?=number_format($d->quantidade_entregue,0,',','.')?></h1>
            </div>
        </div>
        
    </div>
</div>


<div class="m-3">
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo Financeiro</h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Total de Vendas</span>
                <h1>R$ <?=number_format($d->total_vendas,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Custo de entregas</span>
                <h1>R$ <?=number_format($d->total_entregas,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Tickt Médio</span>
                <h1>R$ <?=number_format( ($d->total_vendas) ,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-danger" role="alert">
                <span>Devoluções</span>
                <h1>R$ <?=number_format($d->total_cancelados,2,',','.')?></h1>
            </div>
        </div>

        
    </div>
</div>

<script>
    $(function(){
        Carregando('none')
        
    })
</script>