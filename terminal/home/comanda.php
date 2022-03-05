<?php
include("../../lib/includes.php");
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

    .itens<?=$md5?> {
        margin: 10px;
    }
</style>

<div class="comanda">

    <div class="container mt-5">
        <div class="row">
            <div class="col-8">
                <?php
                $query = "SELECT * FROM vendas v "
                    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
                    . "WHERE v.situacao = '0'";
                $result = mysqli_query($con, $query);

                while ($d = mysqli_fetch_object($result)):
                    $json = json_decode($d->produto_json);

                    ?>
                    <div class="card my-2">
                        <div class="card-body py-3">
                            <h3 class="text-gray-700 font-weight-bold">
                                <?= $json->produtos[0]->descricao; ?>
                            </h3>
                            <div class="d-flex justify-content-center">
                                <div style="flex: 1">
                                    <?php
                                    $sabores = [];

                                    foreach ($json->produtos as $key => $produto) {
                                        if ($key > 0) {
                                            $sabores[] = $produto->descricao;
                                        }
                                    }

                                    echo '<i class="fa-solid fa-utensils"></i> ' . implode(", ", $sabores);
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
                                        <span valor>
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
                                    <button type="button" class="btn btn-outline-info">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <span class="font-weight-bold mx-2" quantidade><?= $d->quantidade; ?></span>
                                </div>
                                <div>
                                    <button remover type="button" class="btn btn-outline-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="col-4"></div>
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
            $.alert({
                icon : 'fa-solid fa-question',
                title: "Aviso",
                content: "Deseja remover este item?",
                type: "red",
                buttons: {
                    sim: {
                        text: "Sim",
                        btnClass: "btn-red",
                        action: function () {

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
    })
</script>