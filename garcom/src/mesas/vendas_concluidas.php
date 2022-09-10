<?php
    include("../../../lib/includes.php");

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
    <h5 class="card-title">Card title</h5>
    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
    <p class="card-text"><?="{$d->codigo} - mesa ({$d->mesa}) valor: {$d->total}"?></p>
    <a href="#" class="card-link">Card link</a>
    <a href="#" class="card-link">Another link</a>
  </div>
</div>
<?php
    }