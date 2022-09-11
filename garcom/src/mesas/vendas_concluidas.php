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
    }
    .botao{
        background-color:#007bff !important;
        color:#ffffff !important;
    }
</style>
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
                        v.situacao = 'pagar'
                ) and v.deletado != '1'

            order by v.situacao asc, m.mesa asc, v.data_finalizacao desc
                ";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        // echo "{$d->codigo} - mesa ({$d->mesa}) valor: {$d->total}<br>";
?>
<div class="card mb-3 botao">
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
    </p>
  </div>
</div>
<?php
    }
?>
</div>