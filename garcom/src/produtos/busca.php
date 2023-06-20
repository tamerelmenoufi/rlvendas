<?php
include("../../../lib/includes.php");

VerificarVendaApp();

function aasort(&$array, $key)
{
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

?>

<style>
    .foto<?=$md5?> {
        background-size: cover;
        background-position: center;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    .topo<?=$md5?> {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 125px;
        background-color: #fff;
        padding: 20px;
        font-weight: bold;
        z-index: 1;
    }

    .IconePedidos {
        position: fixed;
        top: 10px;
        right: 25px;
        font-size: 30px;
        color: green;
        font-weight: bold;
        z-index: 10;
        display: <?=(($_SESSION['AppCarrinho'])?'block':'none')?>;
    }

    .MensagemAddProduto {
        position: fixed;
        right: 80px;
        top: 15px;
        background-color: green;
        color: #fff;
        text-align: center;
        font-weight: bold;
        border-radius: 5px;
        padding: 5px;
        width: auto;
        z-index: 3;
        display: none;
    }

    .MensagemAddProduto span {
        position: absolute;
        right: -8px;
        font-size: 30px;
        top: -3px;
        color: green;
    }
    .corpo_busca{
        position:fixed;
        left:0;
        right:0;
        top:130px;
        bottom:0;
        overflow:auto;
    }

</style>

<!-- Informativo de pedidos ativos -->

<span class="IconePedidos"><i
            class="fa-solid fa-bell-concierge animate__animated animate__tada animate__repeat-3"
    ></i></span>

<div class="MensagemAddProduto animate__animated animate__shakeX">
    Produto Adicionado!
    <span><i class="fa-solid fa-caret-right"></i></span>
</div>

<!-- Informativo de pedidos ativos -->


<div class="topo<?= $md5 ?>">
    <center>Localizar Produtos</center>

    <div class="row" style="margin-top:25px;">
        <input type="text" class="form-control" />
    </div>
</div>



<div class="col-md-12 corpo_busca" >
    <?php

    $query_c = "select * from categorias where situacao = '1' AND deletado != '1'";
    $result_c = mysqli_query($con, $query_c);
    while($d = mysqli_fetch_object($result_c)){


        $query = "select * from produtos where categoria = '{$d->codigo}' AND deletado != '1' order by produto asc";
        $result = mysqli_query($con, $query);

        $m_q = "select * from categoria_medidas where codigo in({$d->medidas}) AND deletado != '1' "
            . "ORDER BY ordem";
        $m_r = mysqli_query($con, $m_q);

        $M = [];

        while ($m = mysqli_fetch_array($m_r)) {
            $M[$m['codigo']] = [
                "ordem" => $m['ordem'],
                "descricao" => $m['medida']
            ];
        }

        while ($p = mysqli_fetch_object($result)) {
            $detalhes = json_decode($p->detalhes, true);
            $detalhes_2 = [];
            ?>
            <div class="card mb-3 item_button<?= $md5 ?>">
                <div class="row no-gutters">
                    <!-- <div class="col-4 foto<?= $md5 ?>"
                        style="background-image:url(../painel/produtos/icon/<?= $p->icon ?>)">
                    </div> -->
                    <div class="col-12">
                        <div class="card-body">
                            <h5 class="card-title"><?= $p->produto ?></h5>
                            <p class="card-text">
                                <p><?= $p->descricao ?></p>
                            <small class="text-muted">

                                <?php
                                foreach ($detalhes as $key => $val) :
                                    if($val['valor'] > 0){
                                        $val['ordem'] = $M[$key]['ordem'];
                                        $detalhes_2[$key] = $val;
                                    }
                                endforeach;

                                aasort($detalhes_2, "ordem");

                                foreach ($detalhes_2 as $key2 => $val) {
                                    if ($val["quantidade"] > 0) {
                                        ?>
                                        <button
                                                acao_medida
                                                opc="<?= $val["quantidade"]; ?>"
                                                produto="<?= $p->codigo ?>"
                                                titulo='<?= "{$d->categoria} - {$p->produto} ({$M[$key2]["descricao"]})" ?>'
                                                categoria='<?= $d->codigo ?>'
                                                medida='<?= $val["quantidade"]; ?>'
                                                valor='<?= $val['valor']; ?>'
                                                class="btn btn-outline-success btn-lg"
                                                style="height:auto; font-size:18px; line-height: 1.2;"
                                        >
                                            <?= $M[$key2]['descricao']; ?><br>
                                            R$ <?= number_format($val['valor'], 2, '.', false) ?>
                                        </button>
                                        <?php
                                    }
                                }
                                ?>

                            </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
    }
    ?>
</div>

<script>

    $("button[acao_medida]").click(function () {
        opc = $(this).attr("opc");
        produto = $(this).attr("produto");
        title = $(this).attr("titulo");
        categoria = $(this).attr("categoria");
        medida = $(this).attr("medida");
        valor = $(this).attr("valor");

        Carregando();
        $.ajax({
            url: "componentes/ms_popup_100.php",
            type: "POST",
            data: {
                local: "src/produtos/produto.php",
                categoria,
                produto,
                medida,
                valor
            },
            success: function (dados) {
                $(".ms_corpo").append(dados);
            }
        });

    });

    $(".IconePedidos").click(function () {
        $.ajax({
            url: "componentes/ms_popup_100.php",
            type: "POST",
            data: {
                local: "src/produtos/pedido.php",
            },
            success: function (dados) {
                PageClose();
                $(".ms_corpo").append(dados);
            }
        });
    });

</script>