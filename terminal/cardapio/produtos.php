<?php
include("../../lib/includes.php");

$categoria = $_GET['categoria'];

$query = "SELECT * FROM categorias WHERE codigo = '{$_GET['categoria']}'";
$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

$m_q = "SELECT * FROM categoria_medidas WHERE codigo IN({$d->medidas}) ORDER BY ordem ASC";
$m_r = mysqli_query($con, $m_q);

while ($m = mysqli_fetch_array($m_r)) {
    $M[$m['codigo']] = [
        "ordem" => $m['ordem'],
        "descricao" => $m['medida']
    ];
}

$_SESSION['categoria'] = "";
?>

<style>
    .cardapio_produtos {
        position: absolute;
        left: 0;
        top: 50px;
        bottom: 20px;
        width: 100%;
        padding-bottom: 40px;
        overflow: auto;
    }

    .foto<?=$md5?> {
        background-size: cover;
        background-position: center;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
</style>

<div class="cardapio_produtos">

    <div class="col-md-12">
        <?php
        $query = "SELECT * FROM produtos WHERE categoria = {$d->codigo}";
        $result = mysqli_query($con, $query);

        while ($p = mysqli_fetch_object($result)) {
            $detalhes = json_decode($p->detalhes);
            ?>
            <div class="card mb-3 item_button<?= $md5 ?>">
                <div class="row no-gutters">
                    <div class="col-md-4 foto<?= $md5 ?>"
                         style="background-image:url('../painel/produtos/icon/<?= $p->icon ?>')">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold h4"><?= $p->produto ?></h5>
                            <p class="card-text h5 mb-3"><?= $p->descricao ?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <?php
                                    foreach ($detalhes as $key => $val) :
                                        $val->ordem = $M[$val->medida]['ordem'];
                                    endforeach;

                                    usort($detalhes, function ($a, $b) {
                                        return strcmp($a->ordem, $b->ordem);
                                    });

                                    foreach ($detalhes as $val) :
                                        if ($val->quantidade > 0) { ?>
                                            <button
                                                    acao_medida
                                                    opc="<?= $val->quantidade; ?>"
                                                    produto="<?= $p->codigo ?>"
                                                    titulo='<?= "{$d->categoria} - {$p->produto} ({$M[$val->medida]['descricao']})" ?>'
                                                    categoria='<?= $d->codigo ?>'
                                                    medida='<?= $val->quantidade ?>'
                                                    valor='<?= $val->valor ?>'
                                                    class="btn btn-outline-success"
                                                    style="height:60px; font-weight: 600;padding: 2px 25px"
                                            >
                                                <?= $M[$val->quantidade]['descricao'] ?>
                                                <br>
                                                R$ <?= number_format(
                                                    $val->valor,
                                                    2,
                                                    ',',
                                                    '.'
                                                ); ?>
                                            </button>
                                            <?php
                                        }
                                    endforeach;
                                    ?>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
        /*
    ?>
    </div>

    <div class="col-md-6">
    <?php
        $query = "select * from categoria_medidas where codigo in({$d->medidas})";
        $result = mysqli_query($con, $query);
        while($m = mysqli_fetch_object($result)){
    ?>
            <button
                    type="button"
                    class="btn btn-primary btn-lg btn-block item_button<?=$md5?>"
                    categoria="<?=$m->codigo?>"
            ><?=$m->medida?></button>
    <?php
        }
    ?>
    </div>

    <?php
        //*/
        ?>
    </div>
</div>

<div style="position:fixed; left:20px; bottom:20px;">
    <button class="btn btn-primary btn-lg btn-block cardapio" cardapio>CARDÁPIO</button>
</div>

<div style="position:fixed; right:40px; bottom:20px;">
    <button
            type="button"
            class="btn btn-primary btn-lg btn-block comanda"
            categoria="<?= $categoria; ?>"
    >
        <i class="fa-solid fa-bag-shopping"></i>
    </button>
</div>

<script>
    $(function () {
        $.ajax({
            url: "home/header.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $.ajax({
            url: "home/footer.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $("button[acao_medida]").click(function () {
            opc = $(this).attr("opc");
            produto = $(this).attr("produto");
            title = $(this).attr("titulo");
            categoria = $(this).attr("categoria");
            medida = $(this).attr("medida");
            valor = $(this).attr("valor");

            // $('button[produto="'+produto+'"]').removeClass("active");
            // $(this).addClass('active');


            $.ajax({
                url: "cardapio/produto.php",
                data: {
                    categoria,
                    produto,
                    medida,
                    valor
                },
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $("button[cardapio]").click(function () {
            $.ajax({
                url: "home/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $(".comanda").click(function () {
            var categoria = $(this).attr("categoria");

            $.ajax({
                url: "home/comanda.php",
                method: "GET",
                data: {categoria},
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });
    });

</script>