<?php
include("../../lib/includes.php");

$query = "SELECT SUM(vp.valor_total) AS total FROM vendas v "
    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
    . "WHERE v.situacao = 'producao' AND "
    . "vp.mesa = '{$_SESSION['ConfMesa']}' AND "
    . "vp.cliente = '{$_SESSION['ConfCliente']}' AND "
    . "vp.deletado != '1' AND v.codigo = '{$_SESSION['ConfVenda']}'";

$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

?>

<div id="pagamento" class="mt-5">
    <div style="position:fixed;left: 20px; bottom: 20px;z-index: 999">
        <button class="btn btn-danger btn-lg " sair_venda>SAIR DO TERMINAL</button>
        <button voltar class="btn btn-warning btn-lg">CONTINUAR COMPRANDO</button>
    </div>

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="h4 font-weight-bold">Dados da Compra</h4>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="h5">
                                <b>PEDIDO</b>
                            </div>
                            <div class="h5 font-weight-bold">
                                <?= $_SESSION['ConfMesa']; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="h5">
                                <b>TOTAL</b>
                            </div>
                            <div class="h5 text-success font-weight-bold">
                                R$ <?= number_format($d->total, 2, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-body">
                    <h4 class="h4 font-weight-bold">Formas de Pagamento desejada</h4>
                    <hr>
                    <div class="px-md-5">
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="pix"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-solid fa-money-bill-1-wave"></i> Dinheiro
                            </a>
                        </h5>
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="pix"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-brands fa-pix"></i> Pix
                            </a>
                        </h5>
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="debito"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-solid fa-credit-card"></i> Débito /
                                Crédito
                            </a>
                        </h5>
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="dinheiro"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-solid fa-credit-card"></i> Dinheiro
                            </a>
                        </h5>

                    </div>
                </div>
            </div>
        </div>
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

        $("button[voltar]").click(function () {
            $.ajax({
                url: "home/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $("a[pagar]").click(function () {
            opc = $(this).attr("opc");

            $.ajax({
                url: `pagamento/informativo_pagamento.php`,
                data:{opc},
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });


        $("button[sair_venda]").click(function () {
            $.confirm({
                icon: "fa-solid fa-right-from-bracket",
                content: false,
                title: "Deseja realmente sair do terminal?",
                columnClass: "medium",
                type: "red",
                buttons: {
                    'nao': {
                        text: "NÃO, Continuar",
                        action: function () {

                        }
                    },
                    'sim': {
                        text: "Sim, Sair",
                        btnClass: 'btn-red',
                        action: function () {
                            window.localStorage.clear();
                            $.ajax({
                                url: "home/index.php?sair=1",
                                dataType: "JSON",
                                success: function (dados) {
                                    if (dados.status === "erro") {

                                        $.confirm({
                                            icon: "fa-solid fa-right-from-bracket",
                                            content: false,
                                            title: "Você ainda não confirmou seus últimos pedidos para inciarmos o preparo.<br><br>Por favor escolha uma das opções:",
                                            columnClass: "medium",
                                            type: "red",
                                            buttons: {
                                                'nao': {
                                                    text: "Sair mesmo!",
                                                    action: function () {
                                                        $("#body").load("home/index.php?sair=1&confirm=1");
                                                    }
                                                },
                                                'sim': {
                                                    text: "Quero Confirmar",
                                                    action: function () {
                                                        $("#body").load("home/comanda.php");
                                                    }
                                                }
                                            }
                                        })

                                    }else{
                                        $("#body").load("home/index.php");
                                    }
                                }
                            });
                        },
                    }

                }
            });
        });


    });
</script>
