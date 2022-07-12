<?php
include("../../../lib/includes.php");

if ($_POST['mesa']) {

    $_SESSION['PainelVenda'] = false;
    $_SESSION['PainelCliente'] = false;
    $_SESSION['PainelPedido'] = false;

    $query = "SELECT codigo, cliente, mesa FROM vendas WHERE mesa = '{$_POST['cod_mesa']}' AND deletado != '1' AND situacao in ('producao','preparo') LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result)) {
        //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['PainelCliente']}' AND mesa = '{$_SESSION['PainelPedido']}' AND deletado != '1' LIMIT 1";
        list($codigo, $cliente, $mesa) = mysqli_fetch_row(mysqli_query($con, $query));
        $_SESSION['PainelVenda'] = $codigo;
        $_SESSION['PainelCliente'] = $cliente;
        $_SESSION['PainelPedido'] = $mesa;

    } else {

        $query = "select * from clientes where telefone = '{$_POST['mesa']}'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result)) {
            $d = mysqli_fetch_object($result);
            $_SESSION['PainelCliente'] = $d->codigo;
        } else {
            mysqli_query($con, "insert into clientes set telefone = '{$_POST['mesa']}'");
            $_SESSION['PainelCliente'] = mysqli_insert_id($con);
        }

        ////////////REMOVER DEPOIS//////////////////////////////////
        $query = "select * from mesas where mesa = '{$_POST['mesa']}'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result)) {
            $d = mysqli_fetch_object($result);
            $_SESSION['PainelPedido'] = $d->codigo;
        } else {
            mysqli_query($con, "insert into mesas set mesa = '{$_POST['mesa']}'");
            $_SESSION['PainelPedido'] = mysqli_insert_id($con);
        }
        ////////////REMOVER DEPOIS//////////////////////////////////

    }


    if ($_SESSION['PainelCliente'] && $_SESSION['PainelPedido'] && !$_SESSION['PainelVenda']) {
        /////////////////INCLUIR O REGISTRO DO PEDIDO//////////////////////
        $query = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['PainelCliente']}' AND mesa = '{$_SESSION['PainelPedido']}' AND deletado != '1' AND situacao in ('producao','preparo') LIMIT 1";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result)) {
            //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['PainelCliente']}' AND mesa = '{$_SESSION['PainelPedido']}' AND deletado != '1' LIMIT 1";
            list($codigo) = mysqli_fetch_row(mysqli_query($con, $query));
            $_SESSION['PainelVenda'] = $codigo;
        } else {
            mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['PainelCliente']}', mesa = '{$_SESSION['PainelPedido']}', atendente = '{$_SESSION['PainelGarcom']}', data_pedido = NOW(), situacao = 'producao'");
            $_SESSION['PainelVenda'] = mysqli_insert_id($con);
        }
        /////////////////////////////////////////////////////////////////
    }

    echo json_encode([
        "PainelCliente" => $_SESSION['PainelCliente'],
        "PainelPedido" => $_SESSION['PainelPedido'], //REMOVER DEPOIS
        "PainelVenda" => $_SESSION['PainelVenda'] //REMOVER DEPOIS
    ]);

    exit();
}


$query = "select a.*, (select count(*) from vendas_produtos where venda = a.codigo and deletado != '1') as produtos from vendas a where a.situacao not in ('pago', 'pagar') and a.deletado != '1'";
$result = mysqli_query($con, $query);
$Ocupadas = [];

while ($d = mysqli_fetch_object($result)) {
    $Ocupadas[] = $d->mesa;
    $Produtos[$d->mesa] = $d->produtos;
}


if ($_POST['acao'] == 'Sair') {

    $query = "select * from vendas_produtos where venda = '{$_SESSION['PainelVenda']}' and deletado != '1' and situacao = 'n'";
    $result = mysqli_query($con, $query);
    $n = mysqli_num_rows($result);

    if ($n > 0 and !$_GET['confirm']) {
        echo json_encode([
            "status" => "erro",
        ]);
    } else if ($_GET['confirm']) {
        $_SESSION = [];

    } else {
        echo json_encode([
            "status" => "sucesso",
        ]);
        $_SESSION = [];
    }
    exit();
}

?>
<div class="PainelMesas">
    <style>
        .PainelMesas {
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            bottom: 0;
            overflow-x: auto;
        }

        .ClienteTopoTitulo {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 65px;
            background: #fff;
            padding-left: 70px;
            padding-top: 15px;
            z-index: 1;
        }

        .btn_mesa {
            width: 100%;
            padding: 10px;
            margin: 5px;
            border: solid 1px #ccc;
            border-radius: 5px;
            min-height: 60px;
            font-size: 30px;
            color: #333;
            text-align: center;
            background: #eee;
        }

        .ocupada {
            background: green;
            color: #fff;
        }

        .ComProdutos {
            background: blue;
            color: #fff;
        }

        .PainelMesas::-webkit-scrollbar {
            display: none;
        }

        body::-webkit-scrollbar {
            display: none;
        }
    </style>

    <!-- <div class="ClienteTopoTitulo">
        <h4>
            <i class="fa-solid fa-user"></i> Lista das Mesas
        </h4>
    </div> -->

    <div class="col">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="row">
                    <?php

                    $query = "select * from mesas where deletado != '1' and situacao != '0' and mesa between 0 and 199 order by mesa";
                    $result = mysqli_query($con, $query);
                    while ($d = mysqli_fetch_object($result)) {

                        if ($Produtos[$d->codigo]) {
                            $icone = 'ComProdutos';
                        } else if (in_array($d->codigo, $Ocupadas)) {
                            $icone = 'ocupada';
                        } else {
                            $icone = false;
                        }

                        ?>
                        <div class="col-4">
                            <div acao="<?= $d->mesa ?>" cod="<?= $d->codigo ?>"
                                 class="btn_mesa <?= $icone ?>"><?= str_pad($d->mesa, 3, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="row">
                    <?php

                    $query = "select * from mesas where deletado != '1' and situacao != '0' and mesa between 200 and 400 order by mesa";
                    $result = mysqli_query($con, $query);
                    while ($d = mysqli_fetch_object($result)) {

                        if ($Produtos[$d->codigo]) {
                            $icone = 'ComProdutos';
                        } else if (in_array($d->codigo, $Ocupadas)) {
                            $icone = 'ocupada';
                        } else {
                            $icone = false;
                        }

                        ?>
                        <div class="col-4">
                            <div acao="<?= $d->mesa ?>" cod="<?= $d->codigo ?>"
                                 class="btn_mesa <?= $icone ?>"><?= str_pad($d->mesa, 3, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(function () {

            $("div[acao]").click(function () {
                mesa = $(this).attr("acao");
                cod_mesa = $(this).attr("cod");

                $.ajax({
                    url: "vendas/mesas/home.php",
                    type: "POST",
                    data: {
                        mesa,
                        cod_mesa
                    },
                    success: function (dados) {
                        let retorno = JSON.parse(dados);


                        $.ajax({
                            url: "vendas/telas/categorias.php",
                            success: function (dados) {
                                $("#CorpoTelaVendas").append(dados);
                                janela_login.close();
                                $(".PainelMesas").remove();
                            }
                        });

                        $.ajax({
                            url: "vendas/telas/comanda.php",
                            success: function (dados) {
                                $("#CorpoTelaVendas").append(dados);
                                $(".PainelMesas").remove();
                            }
                        });

                    }
                });
            });

        })
    </script>
</div>