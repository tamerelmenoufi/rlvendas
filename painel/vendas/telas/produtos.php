<?php
include("../../../lib/includes.php");

//VerificarVendaApp();


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

$query = "select * from categorias where codigo = '{$_GET['categoria']}' AND deletado != '1'";
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
    .topo<?= $md5 ?>{
        position:fixed;
        top:20px;
        left:20px;
        font-size:30px;
        font-weight:bold;
    }
    .ListaProdutosVendas{
        position:absolute;
        top:210px;
        left:0;
        width:60%;
        bottom:60px;
        border:solid 1px red;
        overflow-x: auto;
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
    .ListaProdutosVendas::-webkit-scrollbar {
        display: none;
    }
</style>

<div class="ListaProdutosVendas">


    <div class="MensagemAddProduto animate__animated animate__shakeX">
        Produto Adicionado!
        <span><i class="fa-solid fa-caret-right"></i></span>
    </div>

    <!-- Informativo de pedidos ativos -->


    <div class="topo<?= $md5 ?>">
        <center><?= $d->categoria ?></center>
    </div>


    <div class="col-md-12">
        <?php
        $query = "select * from produtos where categoria = '{$d->codigo}' AND deletado != '1'";
        $result = mysqli_query($con, $query);
        while ($p = mysqli_fetch_object($result)) {
            $detalhes = json_decode($p->detalhes, true);
            $detalhes_2 = [];
            ?>
            <div class="card mb-3 item_button<?= $md5 ?>">
                <div class="row no-gutters">
                    <div class="col-12">
                        <div class="card-body">
                            <h5 class="card-title"><?= $p->produto ?></h5>
                            <p class="card-text"><?= $p->descricao ?></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <small class="text-muted">

                                <?php
                                foreach ($detalhes as $key => $val) :
                                    $val['ordem'] = $M[$key]['ordem'];
                                    $detalhes_2[$key] = $val;
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
                                                class="btn btn-outline-success btn-xs"
                                                style="height:60px; font-size:20px; line-height: 1.2;"
                                        >
                                            <?= $M[$key2]['descricao']; ?><br>
                                            R$ <?= number_format($val['valor'], 2, ',', '.') ?>
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


            <?php
        }
        ?>
    </div>
    </div>
</div>
<script>

    $(function(){

        $("button[acao_medida]").click(function () {
            opc = $(this).attr("opc");
            produto = $(this).attr("produto");
            title = $(this).attr("titulo");
            categoria = $(this).attr("categoria");
            medida = $(this).attr("medida");
            valor = $(this).attr("valor");

            $.ajax({
                url:"vendas/telas/produto.php",
                type: "POST",
                data: {
                    local: "src/produtos/produto.php",
                    categoria,
                    produto,
                    medida,
                    valor
                },
                success:function(dados){
                    $("#CorpoTelaVendas").append(dados);
                }
            });


        });
    })
</script>