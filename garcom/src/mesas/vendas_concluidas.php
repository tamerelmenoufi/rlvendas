<?php
    include("../../../lib/includes.php");
?>
<style>
    .vlrP{
        width:80px;
    }
    .vlrN{
        width:80px;
        color:red;
        background-color:#eee;
        border-radius:5px;
    }
    .botao{
        background-color:#007bff !important;
        color:#ffffff !important;
    }
    .botaoN{
        background-color:#28a745 !important;
        color:#ffffff !important;
    }
    .topo<?=$md5?>{
        position:fixed;
        top:0;
        left:0;
        right:0;
        height:60px;
        background:#fff;
        z-index:1;
        padding-left:80px;
        padding-top:10px;
        font-size:25px;
    }
</style>
<div class="topo<?=$md5?>">
    Vendas Realizadas
</div>
<div style="padding:10px;">
<?php
    $data_limite = date( "Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 1, date("Y")));

    $query = "SELECT
                    v.*,
                    m.mesa
                FROM vendas v
                left join mesas m on v.mesa = m.codigo
            where
                (
                    (
                        v.data_finalizacao >= '{$data_limite}' and
                        v.situacao = 'pago'
                    ) or
                    (
                        v.data_finalizacao >= '{$data_limite}' and
                        v.situacao = 'pagar'
                    )
                ) and v.deletado != '1' and v.valor > 0

            order by v.situacao asc, m.mesa asc, v.data_finalizacao desc
                ";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        // echo "{$d->codigo} - mesa ({$d->mesa}) valor: {$d->total}<br>";
?>
<div acao="<?=$d->codigo?>" class="card mb-3 <?=((trim($d->nf_numero))?'botaoN':'botao')?>">
  <div class="card-body">
    <h5 class="card-title">
        <div class="d-flex justify-content-between">
            <span>Venda: <b><?=str_pad($d->codigo, 5, "0", STR_PAD_LEFT)?></b></span>
            <span>MESA: <b><?=$d->mesa?></b></span>
        </div>
    </h5>
    <h6 class="card-subtitle mb-2">
        <div class="d-flex justify-content-between">
            <span>Data Fechamento:</span>
            <span><?=formata_datahora($d->data_finalizacao)?></span>
        </div>
    </h6>
    <p class="card-text">
        <div class="d-flex justify-content-between">
            <span>valor da compra:</span>
            <span class="vlrP"><?="R$ ".number_format($d->valor, 2, ",",false)?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Taxa de Serviço:</span>
            <span class="vlrP"><?="R$ ".number_format($d->taxa, 2, ",",false)?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Desconto:</span>
            <span class="vlrN"><?="R$ ".number_format($d->desconto, 2, ",",false)?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Valor Pago:</span>
            <span class="vlrP"><b><?="R$ ".number_format(($d->valor + $d->taxa - $d->desconto), 2, ",",false)?></b></span>
        </div>
        <div nota="<?=$d->codigo?>" style="display:<?=((trim($d->nf_numero))?'block':'none')?>;">
            <div class="d-flex justify-content-between">
                <span>Nota Fiscal N°:</span>
                <span class="vlrP"><b numero_nota<?=$d->codigo?>><?=$d->nf_numero?></b></span>
            </div>
        </div>

        <table class="table" style="color:#fff">
            <thead>
                <tr>
                    <th>Caixa</th>
                    <th>Forma de Pgamento</th>
                    <th>Atendente</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
        <?php
        $q = "select a.*, b.nome as atendente_nome from vendas_pagamento a left join atendentes b on a.atendente = b.codigo where a.venda = '{$d->codigo}' and a.deletado != '1'";
        $r = mysqli_query($con, $q);
        while($p = mysqli_fetch_object($r)){
        ?>
                <tr>
                    <td><?=$p->caixa?></td>
                    <td><?=$p->forma_pagamento?></td>
                    <td><?=$p->atendente_nome?></td>
                    <td>R$ <?=number_format($p->valor,2,'.',false)?></td>
                </tr>    
        <?php
        }
        ?>
            </tbody>
        </table>


    </p>
  </div>
</div>
<?php
    }
?>
</div>

<script>
    $(function(){
        $("div[acao]").click(function(){
            venda = $(this).attr("acao");
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:'src/produtos/venda_detalhe.php',
                    venda,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>