<?php
include("../../lib/includes.php");

if (!empty($_POST) and $_POST["acao"] === "remover") {
    $codigo = $_POST["codigo"];

    $query = "UPDATE vendas_produtos SET deletado = '1' WHERE codigo = '{$codigo}'";

    if (@mysqli_query($con, $query)) {
        echo json_encode([
            "status" => "sucesso",
            "valor_total" => getValorTotal(),
        ]);
    }
    exit();
}

if (!empty($_POST) and $_POST["acao"] === "cancelar") {
    $codigo = $_SESSION['ConfVenda'];

    $query = "UPDATE vendas SET situacao = 'cancelado' WHERE codigo = '{$codigo}'";

    if (mysqli_query($con, $query)) {
        echo json_encode([
            "status" => "sucesso",
        ]);
    }

    exit();
}

if (!empty($_GET) and $_GET['acao'] === "atualiza_quantidade") {
    $codigo = $_GET['codigo'];
    $quantidade = $_GET['quantidade'];

    $query = "UPDATE vendas_produtos SET "
        . "quantidade = '{$quantidade}', valor_total = (valor_unitario * {$quantidade}) "
        . "WHERE codigo = '{$codigo}'";

    if (mysqli_query($con, $query)) {
        echo json_encode([
            "status" => "sucesso",
            "valor_total" => getValorTotal(),
        ]);
    }
    exit();
}

function getValorTotal()
{
    global $con;

    $query = "SELECT SUM(vp.valor_total) AS total FROM vendas v "
        . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
        . "WHERE v.situacao = 'producao' AND "
        . "vp.mesa = '{$_SESSION['ConfMesa']}' AND "
        . "vp.cliente = '{$_SESSION['ConfCliente']}' AND "
        . "vp.deletado = '0'";

    $result = mysqli_query($con, $query);
    list($total) = mysqli_fetch_row($result);

    return $total;
}

$_SESSION['categoria'] = $_GET['categoria'];

$query = "SELECT * FROM clientes WHERE codigo = '{$_SESSION['ConfCliente']}'";
$result = mysqli_query($con, $query);

$cliente = mysqli_fetch_object($result);
?>

<style>
    /* ===== Scrollbar CSS ===== */
    /* Firefox */
    .comanda * {
        scrollbar-width: auto;
        scrollbar-color: #999 #ffffff;
    }

    /* Chrome, Edge, and Safari */
    .comanda *::-webkit-scrollbar {
        width: 10px;
    }

    .comanda *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    .comanda *::-webkit-scrollbar-thumb {
        background-color: #999;
        border-radius: 2px;
        border: 0;
    }

    .my-2:nth-of-type(1) {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
</style>

<div class="comanda">

    <div class="col-md-12 mt-5">
        <div class="row">
            <div id="comanda-content" class="col-md-7" style="height: 90vh; overflow-y: auto">
                <?php
                $query = "SELECT * FROM vendas v "
                    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
                    . "WHERE v.situacao = 'producao' AND "
                    . "vp.mesa = '{$_SESSION['ConfMesa']}' AND "
                    . "vp.cliente = '{$_SESSION['ConfCliente']}' AND "
                    . "v.situacao = 'producao' AND vp.deletado = '0'";

                #echo $query;
                $result = mysqli_query($con, $query);

                $existeVenda = mysqli_num_rows($result);

                if ($existeVenda) {
                    while ($d = mysqli_fetch_object($result)):
                        $json = json_decode($d->produto_json);

                        $queryProduto = "SELECT icon FROM produtos WHERE codigo = '{$json->produtos[0]->codigo}'";
                        list($img) = mysqli_fetch_row(mysqli_query($con, $queryProduto));
                        ?>
                        <input
                                type="hidden"
                                id="valor-<?= $d->codigo; ?>"
                                value=" <?= $d->valor_unitario; ?>"
                        >

                        <div class="card my-2" id="item-<?= $d->codigo; ?>">
                            <div class="card-body py-4 pt-3">


                                <div class="d-flex justify-content-center flex-row">

                                    <div>
                                        <img
                                                src="../painel/produtos/icon/<?= $img ?>"
                                                class="img-thumbnail"
                                                style="width: 180px; max-height: 115px"
                                        >
                                    </div>

                                    <div class="px-2" style="flex: 1">
                                        <h5 class="h5 font-weight-bold">
                                            <?= "{$json->categoria->descricao} - {$json->produtos[0]->descricao} ({$json->medida->descricao})" ?>
                                        </h5>

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
                                                <p class="mb-0">
                                                    <i
                                                            class="fa-solid fa-message"
                                                            title="Observação"
                                                    ></i> <?= $d->produto_descricao; ?>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>


                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button
                                                type="button"
                                                cod="<?= $d->codigo; ?>"
                                                class="btn btn-outline-info menos"
                                        >
                                            <i class="fa-solid fa-minus"></i>
                                        </button>

                                        <span
                                                class="font-weight-bold mx-2"
                                                quantidade-<?= $d->codigo; ?>="<?= $d->quantidade; ?>"
                                        ><?= $d->quantidade; ?></span>

                                        <button
                                                type="button"
                                                cod="<?= $d->codigo; ?>"
                                                class="btn btn-outline-info mais"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <button
                                                remover
                                                cod="<?= $d->codigo; ?>"
                                                type="button"
                                                class="btn btn-outline-danger ml-3"
                                        >
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                    <div>

                                        <h4 class="font-weight-bold text-success">
                                            R$
                                            <span valor-<?= $d->codigo; ?>>
                                            <?= number_format(
                                                $d->valor_unitario * $d->quantidade,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                            </span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                } else { ?>
                    <div class="text-center" style="margin-top: 4rem">
                        <i class="fa-solid fa-face-frown h1 text-center"></i>
                        <h3 style="font-weight: 600" class="h3 text">Você ainda não tem nenhum pedido</h3>
                    </div>
                <?php } ?>
            </div>

            <div class="col-md-5">
                <div class="card my-2">
                    <div class="card-body">
                        <h4 class="font-weight-bold h4">Informações do pedido</h4>
                        <hr>
                        <div class="row">
                            <div class="col-4 font-weight-bold h5">Mesa</div>
                            <div class="col-6 h5"><?= $_SESSION['ConfMesa']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-4 font-weight-bold h5">Cliente</div>
                            <div class="col-6 h5">
                                <?= $cliente->nome ?: $cliente->telefone; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 font-weight-bold h5">
                                Total
                            </div>
                            <div class="col-md-8 h5 text-success">
                                R$ <span valor_total>
                                    <?= number_format(
                                        getValorTotal(),
                                        2,
                                        ',',
                                        '.'
                                    ); ?>
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 font-weight-bold">
                                <h4 class="font-weight-bold h4">Observações</h4>
                                <div class="col-12">
                                    <div class="texto_detalhes" style="min-height: 40px"></div>
                                </div>
                                <button
                                        incluir_observacao
                                        type="button"
                                        class="btn btn-sm incluir_observacao mb-1 font-weight-bold btn-block btn-outline-info"
                                    <?= !$existeVenda ? "disabled" : ""; ?>
                                >
                                    <i class="fa-solid fa-pen-to-square"></i> ADICIONAR OBSERVAÇÃO
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">

                        <hr>
                        <div>
                            <button
                                    concluir_pedido
                                    class="btn btn-primary btn-lg btn-block mb-1 font-weight-bold"
                                <?= !$existeVenda ? "disabled" : ""; ?>
                            >
                                CONCLUIR COMPRA
                            </button>
                        </div>

                        <div>
                            <button
                                    cancelar_pedido
                                    categoria="<?= $categoria; ?>"
                                    class="btn btn-danger btn-lg btn-block font-weight-bold"
                                <?= !$existeVenda ? "disabled" : ""; ?>
                            >
                                CANCELAR PEDIDO
                            </button>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>

    <div style="position:fixed;bottom: 20px;left: 20px">
        <button
                sair
                categoria="<?= $categoria; ?>"
                class="btn btn-primary btn-lg btn-block"
        >
            VOLTAR
        </button>
    </div>

</div>


<script>
    $(function () {
        var time = null;

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

        $.ajax({
            url: "cardapio/detalhes.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $("button[sair]").click(function () {
            var categoria = $(this).attr("categoria");
            var url = "";

            if (categoria) {
                url = `cardapio/produtos.php?categoria=${categoria}`;
            } else {
                url = "home/index.php";
            }

            $.ajax({
                url,
                method: "GET",
                data: {
                    categoria,
                },
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $("button[remover]").click(function () {
            var codigo = $(this).attr('cod');

            $.alert({
                icon: "fa-solid fa-question",
                title: "Tem certeza de que deseja remover este produto?",
                content: false,
                columnClass: "medium",
                type: "red",
                buttons: {
                    nao: {
                        text: "Não, Remover",
                        action: function () {

                        }
                    },
                    sim: {
                        text: "Sim, Remover",
                        btnClass: "btn-red",
                        action: function () {
                            $.ajax({
                                url: "home/comanda.php",
                                method: "POST",
                                dataType: "JSON",
                                data: {
                                    codigo,
                                    acao: "remover"
                                },
                                success: function (dados) {
                                    if (dados.status === "sucesso") {
                                        let valor_total = Number(dados.valor_total);

                                        if (!valor_total) {
                                            $("#comanda-content").html(`
                                            <div class="text-center" style="margin-top: 4rem">
                                                <i class="fa-solid fa-face-frown h1 text-center"></i>
                                                <h3 style="font-weight: 600" class="h3 text">Você ainda não tem nenhum pedido</h3>
                                            </div>
                                            `);

                                            $("button[incluir_observacao], button[concluir_pedido], button[cancelar_pedido]")
                                                .attr("disabled", "");
                                        }

                                        $("span[valor_total]").text(valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2}));

                                        $(`#item-${codigo}`).fadeOut(400).remove();

                                    }
                                }
                            })
                        }
                    },
                }
            });
        });

        $(".mais").click(function () {
            var cod = $(this).attr("cod");
            var quantidade = Number($(`span[quantidade-${cod}]`).attr(`quantidade-${cod}`));
            quantidade = (quantidade + 1);

            $(`span[quantidade-${cod}]`)
                .attr(`quantidade-${cod}`, quantidade)
                .text(quantidade);

            atualiza_quantidade(cod, quantidade);

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

            atualiza_quantidade(cod, quantidade);

            let valor = valor_original * quantidade;

            $(`span[valor-${cod}]`).text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        });

        $(".incluir_observacao").click(function () {
            $("#keyboard_body").css("display", "block");
        });

        $("button[concluir_pedido]").click(function () {
            $.ajax({
                url: "pagamento/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });

        $("button[cancelar_pedido]").click(function () {

            $.alert({
                icon: "fa-solid fa-question",
                title: "Tem certeza de que deseja cancelar seu pedido?",
                content: false,
                columnClass: "medium",
                type: "red",
                buttons: {
                    nao: {
                        text: "Não",
                        action: function () {
                        }
                    },
                    sim: {
                        text: "Sim, Cancelar",
                        btnClass: "btn-red",
                        action: function () {
                            $.ajax({
                                url: "home/comanda.php",
                                method: "POST",
                                dataType: "JSON",
                                data: {
                                    codigo,
                                    acao: "cancelar"
                                },
                                success: function (dados) {
                                    if (dados.status === "sucesso") {
                                        $.ajax({
                                            url: "home/index.php",
                                            success: function (dados) {
                                                $("#body").html(dados);
                                            }
                                        })
                                    }
                                }
                            })
                        }
                    },
                }
            });

        });

        function atualiza_quantidade(codigo, quantidade) {
            $.ajax({
                url: "home/comanda.php",
                method: "GET",
                dataType: "JSON",
                data: {
                    acao: "atualiza_quantidade",
                    codigo,
                    quantidade
                },
                success: function (dados) {
                    let valor_total = Number(dados.valor_total);

                    $("span[valor_total]")
                        .text(valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                }
            });
        }


    });
</script>