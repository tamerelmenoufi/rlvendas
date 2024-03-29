<?php

include("../../../lib/includes.php");

$mes = (($_GET['mes']) ?: date("m"));
$ano = (($_GET['ano']) ?: date("Y"));

?>
<style>
    .CaixaValor {
        background-color: blue;
        color: #fff;
        font-weight: bold;
        font-size: 11px;
        padding: 5px;
        border-radius: 3px;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col">


        <h5>Relatórios Gerais</h5>

        <div id="RelatorioCalendario">

            <!-- <div class="table-responsive"> -->
                <table class='table' cellpadding="5" cellspacing="0" border="0" align="center">
                    <tr>
                        <td colspan="7" align="left" class="titulo">
                            Fechamento de caixa em

                            <!-- <select id="OpMes">
                                <option value="">M&eacute;s</option>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    ?>
                                    <option value="<?= $i ?>" <?= (($i == $mes) ? 'selected' : false) ?>><?= $i ?></option>
                                    <?php
                                }
                                ?>
                            </select> -->


                            <div class="btn-group">
                                <button OpMes class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <?= $mes ?>
                                </button>
                                <div class="dropdown-menu">
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                    ?>
                                    <a class="dropdown-item" SelectMes="<?= $i ?>" href="#"><?= $i ?></a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            /
                            <!-- <select id="OpAno">
                                <option value="">Ano</option>
                                <?php
                                for ($i = date(Y); $i > date(Y) - 4; $i--) {
                                    ?>
                                    <option value="<?= $i ?>" <?= (($i == $ano) ? 'selected' : false) ?>><?= $i ?></option>
                                    <?php
                                }
                                ?>
                            </select> -->

                            <div class="btn-group">
                                <button OpAno class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <?= $ano ?>
                                </button>
                                <div class="dropdown-menu">
                                    <?php
                                    for ($i = date(Y); $i > date(Y) - 4; $i--) {
                                    ?>
                                    <a class="dropdown-item" SelectAno="<?= $i ?>" href="#"><?= $i ?></a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php

                    for ($w = $mes;
                    $w <= $mes;
                    $w++){
                    set_time_limit(90);
                    $w = (($w * 1 < 10) ? '0' . $w * 1 : $w);
                    $d1 = mktime(0, 0, 0, $w, 1, $ano); //verifica o primeiro dia do mes
                    $diaSem = date('w', $d1); //verifica a quantidade de dias da semana para o primeiro dia do mes

                    ?>

                    <tr align='center' class='dias_semana'>
                        <td class='lista_titulo' width="14%">Domingo
                        <td class='lista_titulo' width="14%">Segunda
                        <td class='lista_titulo' width="14%">Ter&ccedil;a
                        <td class='lista_titulo' width="14%">Quarta
                        <td class='lista_titulo' width="14%">Quinta
                        <td class='lista_titulo' width="14%">Sexta
                        <td class='lista_titulo' width="14%">S&aacute;bado</td>
                    </tr>

                    <tr>

                        <!--Coloca os dias em Branco-->
                        <?php
                        for ($i = 0; $i < $diaSem; $i++) {
                            echo "<td geral>&nbsp;";
                        }

                        //Enquanto houver dias

                        for ($i = 2; $i < 33; $i++) {
                            $linha = date('d', $d1);


                            //verifica o dia atual

                            if (date(Y) == $ano and date(m) == $w and date(d) == $linha) {
                                $hoje = ' (HOJE)';
                            } else {
                                $hoje = false;
                            }


                            echo "<td geral class='lista_agenda' valign='top' cel>";

                            $ini = date("Y-m-d H:i:s", mktime(16, 1, 0, $w, $linha, $ano));
                            $fim = date("Y-m-d H:i:s", mktime(9, 59, 59, $w, ($linha + 1), $ano));

                            $q = "select
                                    (select sum(b.valor) from vendas a left join vendas_pagamento b on a.codigo = b.venda and b.deletado != '1' where (a.data_finalizacao between '{$ano}-{$w}-{$linha} 10:00:00' and '{$fim}') and a.situacao = 'pago')  as total,
                                    (select sum(b.valor) from vendas a left join vendas_pagamento b on a.codigo = b.venda and b.deletado != '1' where (a.data_finalizacao between '{$ano}-{$w}-{$linha} 10:00:00' and '{$ano}-{$w}-{$linha}  16:00:00') and a.situacao = 'pago')  as turno1,
                                    (select sum(b.valor) from vendas a left join vendas_pagamento b on a.codigo = b.venda and b.deletado != '1' where (a.data_finalizacao between '{$ini}' and '{$fim}') and a.situacao = 'pago')  as turno2
                            ";
                            $r = mysqli_query($con, $q);
                            $d = mysqli_fetch_object($r);
                            echo "$linha " . $hoje;

                            if ($d->total > 0) {
                                echo '<div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    R$ ' . number_format($d->total, 2, ",", ".") . '
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#">10:00 as 16:00 - R$ ' . number_format($d->turno1, 2, ",", ".") . '</a>
                                        <a class="dropdown-item" href="#">16:00 as 00:30 - R$ ' . number_format($d->turno2, 2, ",", ".") . '</a>
                                    </div>
                                </div>';
                            }
                            echo "</td>";
                            // Se Sábado desce uma linha
                            if (date('w', $d1) == 6) {
                                echo "<tr>\n";
                            }
                            $d1 = mktime(0, 0, 0, $w, $i, $ano);
                            if (date('d', $d1) == "01") {
                                break;
                            }
                        }
                        ?>
                    </tr>
                </table>
                <?php
                }
                ?>
            <!-- </div> -->

        </div>




        <?php
        $dOpc = mktime(0,0,0, date("m"),(date("d")-1),date("Y"));
        $ini = date("Y-m-d H:i:s", mktime(10, 0, 0, date("m"),(date("d")-1),date("Y")));
        $fim = date("Y-m-d H:i:s", mktime(9, 59, 59, date("m"),date("d"),date("Y")));

        $q = "select sum(b.valor) as total, b.forma_pagamento from vendas a left join vendas_pagamento b on a.codigo = b.venda and b.deletado != '1' where (a.data_finalizacao between '{$ini}' and '{$fim}') and a.situacao = 'pago' group by b.forma_pagamento order by sum(b.valor) desc";
        // $q = "select sum(a.total) as total from vendas a  where (a.data_finalizacao between '{$ini}' and '{$fim}') and a.situacao = 'pago'";

        $r = mysqli_query($con, $q);
        ?>


        <p style="margin-top:30px;">Venda Diária <?=date("d/m/Y",$dOpc)?></p>

        <ul class="list-group">
        <?php
        $total = 0;
        while($d = mysqli_fetch_object($r)){
        ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?=strtoupper($d->forma_pagamento)?>
                <span class="badge badge-primary badge-pill">R$ <?=number_format($d->total,2,',','.')?></span>
            </li>
        <?php
        $total = ($total + $d->total);
        }
        ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>TOTAL</b>
                <span class="badge badge-primary badge-pill" style="font-size:20px;">R$ <?=number_format($total,2,',','.')?></span>
            </li>
        </ul>



<?php
    $q = "select
                        concat(
                            JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.categoria.descricao')),' ',
                            JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.medida.descricao')),' ',
                            JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.produtos[0].descricao'))
                        ) as descricao,
                        count(*) as qt,
                        JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.produtos[0].valor')) as valor,
                        (count(*)*JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.produtos[0].valor'))) as total
                from vendas a

                    left join vendas_produtos b on a.codigo = b.venda and b.deletado != '1'

                where (a.data_finalizacao between '{$ini}' and '{$fim}') and a.situacao = 'pago'
                group by JSON_UNQUOTE(JSON_EXTRACT(b.produto_json, '$.produtos[0].codigo'))
                order by qt desc";
    $result = mysqli_query($con, $q);
?>

<h5 style="margin-top:30px;">Vendas por produtos mais vendidos em  <?=date("d/m/Y",$dOpc)?></h5>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Total Vendas</th>
        </tr>
    </thead>
    <tbody>

<?php
    while($d = mysqli_fetch_object($result)){
?>
        <tr>
            <td><?=$d->descricao?></td>
            <td><?=$d->qt?></td>
            <td>R$ <?=number_format($d->valor,2,',','.')?></td>
            <td>R$ <?=number_format($d->total,2,',','.')?></td>
        </tr>
<?php
    }
?>
    </tbody>
    </div>
</div>

<script>
    $(function () {


        periodoRelatorio = (mes, ano)=>{
            var url = 'vendas/caixa/index.php';

            $('.loading').fadeIn(200);

            $.ajax({
                url,
                data: {
                    mes,
                    ano
                },
                success: function (data) {
                    $('#palco').html(data);
                }
            })
            .done(function () {
                $('.loading').fadeOut(200);
            })
            .fail(function (error) {
                alert('Error');
                $('.loading').fadeOut(200);
            })
        }

        // $('#OpMes, #OpAno, button[SelectMes]').change(function () {

        //     var mes = $("#OpMes").val();
        //     var ano = $("#OpAno").val();

        //     periodoRelatorio(mes, ano);

        // });

        $('a[SelectMes]').click(function () {

            var mes = $(this).attr("SelectMes");
            $("button[OpMes]").text(mes);
            var ano = $("#OpAno").val();

            periodoRelatorio(mes, ano);

        });

        $('a[SelectAno]').click(function () {

            var ano = $(this).attr("SelectAno");
            $("button[OpAno]").text(ano);
            var mes = $("button[OpMes]").text();

            periodoRelatorio(mes, ano);

        });


    })
</script>