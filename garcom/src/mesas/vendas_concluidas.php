<?php
    include("../../../lib/includes.php");
?>
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
<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Venda: <b><?=str_pad($d->codigo, 5, "0", STR_PAD_LEFT)?></b> - MESA: <b><?=$d->mesa?></b></h5>
    <h6 class="card-subtitle mb-2 text-muted">Data Fechamento: <?=formata_datahora($d->data_finalizacao)?></h6>
    <p class="card-text">
        <?="valor da compra: R$".number_format($d->valor, 2, ",",false)?><br>
        <?="Taxa de Serviço: R$".number_format($d->taxa, 2, ",",false)?><br>
        <?="Desconto: R$".number_format($d->desconto, 2, ",",false)?><br>
        <?="Valor Pago: R$".number_format(($d->valor + $d->taxa - $d->desconto), 2, ",",false)?>
    </p>
  </div>
</div>
<?php
    }
?>
</div>