<?php
include("../../../lib/includes.php");

VerificarVendaApp('delivery');

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

$query = "select * from categorias where codigo = '{$_GET['categoria']}' AND delivery = '1' AND deletado != '1'";
$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

$m_q = "select * from categoria_medidas where codigo in({$d->medidas}) AND deletado != '1' "
    . "ORDER BY ordem";
$m_r = mysqli_query($con, $m_q);

while ($m = mysqli_fetch_array($m_r)) {
    $M[$m['codigo']] = [
        "ordem" => $m['ordem'],
        "descricao" => $m['medida']
    ];
}
?>

<style>
    .foto<?=$md5?> {
        background-size: cover;
        background-position: center;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        background-color:#ccc;
    }

    .topo<?=$md5?> {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 55px;
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
        bottom: 75px;
        background-color: orange;
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
        right: 23px;
        font-size: 30px;
        bottom: -23px;
        color: orange;
    }

</style>

<!-- Informativo de pedidos ativos -->

<!-- <span class="IconePedidos"><i
            class="fa-solid fa-bell-concierge animate__animated animate__tada animate__repeat-3"
    ></i></span> -->

<div class="MensagemAddProduto animate__animated animate__shakeY">
    Produto Adicionado!
    <span><i class="fa-solid fa-caret-down"></i></span>
</div>

<!-- Informativo de pedidos ativos -->


<div class="topo<?= $md5 ?>">
    <div class="topo_interno<?=$md5?>"><?= $d->categoria ?></div>
</div>

<div style="position:fixed; width:100%; top:60px; bottom:60px; overflow:auto;" >
<div class="col-md-12">
    <?php
    $query = "select * from produtos where categoria = '{$d->codigo}' AND situacao = '1' AND delivery = '1' AND deletado != '1' order by produto asc";
    $result = mysqli_query($con, $query);
    while ($p = mysqli_fetch_object($result)) {
        $detalhes = json_decode($p->detalhes, true);
        $detalhes_2 = [];
        

        foreach ($detalhes as $key => $val) :
            if($val['valor'] > 0 and $val['quantidade'] > 0){
                $val['ordem'] = $M[$key]['ordem'];
                $detalhes_2[$key] = $val;
            }
        endforeach;
        // print_r($detalhes_2);
        if($detalhes_2){
        ?>
        <div bloco<?=$p->codigo?> class="card mb-3 mt-3 item_button<?= $md5 ?>">
            <div class="row no-gutters">
                <div class="col-4 foto<?= $md5 ?>"
                     style="background-image:url(<?=$urlPainel?>src/volume/produtos/<?= $p->icon ?>)">
                </div>
                <div class="col-8">
                        <div style="font-size:12px; margin-left:10px; height:100px; position:relative;">
                            <div class="bloco" style="font-size:14px; color:#a80e13; font-weight:bold; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; text-overflow: ellipsis; direction: ltr;" bloco="<?=$p->codigo?>"><?= $p->produto ?></div>
                            <div style="overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; text-overflow: ellipsis; direction: ltr;"><?= (($p->descricao)?:$descricao_padrao) ?></div>
                            <div class="d-flex flex-row bd-highlight" style="margin-top:3px; position:absolute; bottom:3px;">

                            <?php

                            aasort($detalhes_2, "ordem");

                            foreach ($detalhes_2 as $key2 => $val) {
                                if ($val["quantidade"] > 0 and $M[$key2]['descricao']) {
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
                                            style="height:auto; background-color:#a80e13; border:0; padding:5px; margin-left:2px; font-size:12px; color:#fff; font-weight:bold; line-height: 1.2;"
                                    >
                                        <?= $M[$key2]['descricao']; ?><br>
                                        R$ <?= number_format($val['valor'], 2, '.', false) ?>
                                    </button>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    }
    ?>
</div>
</div>
<div class="rodape<?= $md5 ?>"></div>
<script>


    $.ajax({
        url:"componentes/ms_topo_interno.php",
        type:"POST",
        data:{
            titulo:$(".topo_interno<?=$md5?>").text(),
        },
        success:function(dados){
            $(".topo_interno<?=$md5?>").html(dados);
        }
    });

    $.ajax({
        url:"componentes/ms_rodape.php",
        success:function(dados){
            $(".rodape<?=$md5?>").html(dados);
        }
    });

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