<?php
include("../../lib/includes.php");

if (!empty($_POST) and $_POST["acao"] === "remover") {
    $codigo = $_POST["codigo"];

    $query = "UPDATE vendas_produtos SET deletado = '1' WHERE codigo = '{$codigo}'";

    if (@mysqli_query($con, $query)) {
        echo json_encode([
            "status" => "sucesso",
        ]);
    }
    exit();
}

?>
<style>
    /*.comanda {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 40%;
        overflow: auto;
    }*/

    /*.itens
</style>

<div class="comanda">

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-6">
                <?php
                $query = "SELECT * FROM vendas v "
                    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
                    . "WHERE v.situacao = '0' AND vp.deletado = '0'";

                $result = mysqli_query($con, $query);

                while ($d = mysqli_fetch_object($result)):
                    $json = json_decode($d->produto_json);
                    ?>
                    <input
                            type="hidden"
                            id="valor-<?= $d->codigo; ?>"
                            value=" <?= $json->produtos[0]->valor; ?>"
                    >

                    <div class="card my-2" id="item-<?= $d->codigo; ?>">
                        <div class="card-body">
                            <h5 class="text-gray-700 font-weight-bold">
                                <?= "{$json->categoria->descricao} - {$json->produtos[0]->descricao} ({$json->medida->descricao})" ?>
                            </h5>
                            <div class="d-flex justify-content-center">
                                <div style="flex: 1">
                                    <?php
                                    $sabores = [];

                                    if ($json->produtos) {
                                        foreach ($json->produtos as $key => $produto) {
                                            if ($key > 0) {
                                                $sabores[] = $produto->descricao;
                                            }
                                        }

                                        if (!empty($sabores)) {
                                            echo '<i class="fa-solid fa-utensils"></i> ' . implode(", ", $sabores);
                                        }
                                    }
                                    ?>
                                    <?php if ($d->produto_descricao) { ?>
                                        <p>
                                            <i
                                                    class="fa-solid fa-message"
                                                    title="Observação"
                                            ></i> <?= $d->produto_descricao; ?>
                                        </p>
                                    <?php } ?>
                                </div>
                                <div>
                                    <h4 class="font-weight-bold text-success">
                                        R$
                                        <span valor-<?= $d->codigo; ?>>
                                            <?= number_format(
                                                $d->valor_total,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                            </span>
                                    </h4>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" cod="<?= $d->codigo; ?>" class="btn btn-outline-info menos">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <span
                                            class="font-weight-bold mx-2"
                                            quantidade-<?= $d->codigo; ?>="<?= $d->quantidade; ?>"
                                    >
                                        <?= $d->quantidade; ?>
                                    </span>
                                    <button type="button" cod="<?= $d->codigo; ?>" class="btn btn-outline-info mais">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>

                                </div>
                                <div>
                                    <button remover cod="<?= $d->codigo; ?>" type="button"
                                            class="btn btn-outline-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="col-md-6">
                <div class="card my-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Dados do Pagamento</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botoes fixos -->
    <div style="position:fixed; right:20px; bottom:20px;">
        <button sair class="btn btn-danger btn-lg">SAIR</button>
        <button class="btn btn-success btn-lg">CONCLUIR COMPRA</button>
    </div>
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

        $("button[sair]").click(function () {
            $.ajax({
                url: "home/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });

        $("button[remover]").click(function () {
            var codigo = $(this).attr('cod');

            $.alert({
                icon: 'fa-solid fa-question',
                title: "Aviso",
                content: "Deseja remover este item?",
                type: "red",
                buttons: {
                    sim: {
                        text: "Sim",
                        btnClass: "btn-red",
                        action: function () {
                            $.ajax({
                                url: "home/comanda.php",
                                method: "POST",
                                data: {codigo, acao: "remover"},
                                dataType: "JSON",
                                success: function (dados) {
                                    if (dados.status === "sucesso") {
                                        console.log(`#item-${codigo}`);
                                        $(`#item-${codigo}`).fadeOut(400).remove();
                                    }
                                }
                            })
                        }
                    },
                    nao: {
                        text: "Não",
                        action: function () {

                        }
                    },
                }
            })
        });

        $(".mais").click(function () {
            var cod = $(this).attr("cod");
            var quantidade = Number($(`span[quantidade-${cod}]`).attr(`quantidade-${cod}`));
            quantidade = (quantidade + 1);

            $(`span[quantidade-${cod}]`)
                .attr(`quantidade-${cod}`, quantidade)
                .text(quantidade);

            var valor_original = Number($(`#valor-${cod}`).val());

            let valor = valor_original * quantidade;
            $(`span[valor-${cod}]`).text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        });

        $(".menos").click(function () {
            var cod = $(this).attr("cod");
            var quantidade = Number($(`span[quantidade-${cod}]`).attr(`quantidade-${cod}`));
            quantidade = ((quantidade > 1) ? (quantidade - 1) : 1);

            var valor_original = Number($(`#valor-${cod}`).val());

            $(`span[quantidade-${cod}]`)
                .attr(`quantidade-${cod}`, quantidade)
                .text(quantidade);

            let valor = valor_original * quantidade;

            $(`span[valor-${cod}]`).text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        });

    })
</script>