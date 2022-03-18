<?php
include("../../lib/includes.php");

function venda($cliente, $mesa)
{
    global $con;

    $query = "SELECT codigo FROM vendas WHERE cliente = '{$cliente}' AND mesa = '{$mesa}' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result)) {
        $queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$cliente}' AND mesa = '{$mesa}' LIMIT 1";
        list($codigo) = mysqli_fetch_row(mysqli_query($con, $queryInsert));

        $_SESSION['ConfVenda'] = $codigo;
    } else {
        $data_pedido = date('d-m-Y H:i:s');
        mysqli_query($con, "INSERT INTO vendas SET cliente = '{$cliente}', mesa = '{$mesa}',data_pedido = '{$data_pedido}', situacao = 'producao'");
        $_SESSION['ConfVenda'] = mysqli_insert_id($con);
    }

}

if ($_POST['cliente']) {
    #$telefone = '(' . substr($_POST['cliente'], 0, 2) . ') ' . substr($_POST['cliente'], 2, 1) . ' ' . substr($_POST['cliente'], 3, 4) . '-' . substr($_POST['cliente'], 7, 4);
    $telefone = $_POST['cliente'];

    $query = "SELECT * FROM clientes WHERE telefone = '{$telefone}'";
    $result = mysqli_query($con, $query);
    $c = mysqli_fetch_object($result);

    if ($c->codigo) {
        $_SESSION['ConfCliente'] = $c->codigo;

        echo json_encode([
            'status' => 'sucesso',
            'cliente' => $c->codigo,
        ]);
    } else {
        mysqli_query($con, "INSERT INTO clientes SET telefone = '{$telefone}'");
        $codigo = mysqli_insert_id($con);

        $_SESSION['ConfCliente'] = $codigo;

        echo json_encode([
            'status' => 'sucesso',
            'cliente' => $codigo,
        ]);
    }
    venda($_SESSION['ConfCliente'], $_SESSION['ConfMesa']);
    exit();
}

?>

<style>
    #OpcMesa {
        text-align: center;
        font-size: 40px;
        font-weight: bold;
    }

    #OpcCliente {
        background-color: #FFFFFF;
        color: #5a5c69;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col">
            <center>
                <h2>INFORME SEU TELEFONE/WHATSAPP</h2>
            </center>
            <!-- <div class="form-control form-control-lg" id="OpcCliente"></div> -->
            <input type="text" class="form-control form-control-g" id="OpcCliente" readonly disabled>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col">
            <?php
            for ($i = 1; $i <= 9; $i++) {
                ?>
                <div style="width:<?= (100 / 11) ?>%; float:left; padding-right:5px;">
                    <button type="button" class="btn btn-outline-dark btn-lg btn-block tecla"><?= $i ?></button>
                </div>
                <?php
            }
            ?>
            <div style="width:<?= (100 / 11) ?>%; float:left; padding-right:5px;">
                <button type="button" class="btn btn-outline-dark btn-lg btn-block tecla">0</button>
            </div>
            <div style="width:<?= (100 / 11) ?>%; float:left;">
                <button type="button" class="btn btn-dark btn-lg btn-block apaga">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>

        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col">
            <button class="btn btn-success btn-block btn-lg" AcessarCliente>ACESSAR</button>
        </div>
        <div class="col">
            <button class="btn btn-info btn-block btn-lg" LimparCliente>LIMPAR</button>
        </div>
        <div class="col">
            <button class="btn btn-danger btn-block btn-lg" CancelarCliente>CANCELAR</button>
        </div>
    </div>
</div>

<script>
    $(function () {
        //$("#OpcCliente").masck("(99) 9 9999-9999");

        $(".tecla").click(function () {
            tecla = $(this).text();
            cliente = $("#OpcCliente").val().toString();

            let mascara = mphone(cliente + tecla);

            $("#OpcCliente").val(mascara);
        });

        $(".apaga").click(function () {
            cliente = $("#OpcCliente").val();
            cliente = cliente.substring(0, cliente.length - 1);
            $("#OpcCliente").val(cliente);
        });

        $("button[LimparCliente]").click(function () {
            $("#OpcCliente").val('');
        });

        $("button[CancelarCliente]").click(function () {
            JanelaDefineCliente.close();
            $.ajax({
                url: "home/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $("button[AcessarCliente]").click(function () {
            cliente = $("#OpcCliente").val();

            if (!validatePhone(cliente)) {
                $.alert({
                    title: 'Aviso',
                    content: "Por favor digite um número válido",
                    type: "red",
                });

                return false;
            }

            $.ajax({
                url: "home/definir_cliente.php",
                type: "POST",
                data: {
                    cliente,
                },
                success: function (dados) {
                    let retorno = JSON.parse(dados);
                    if (retorno.status === 'sucesso') {
                        window.localStorage.setItem('ConfCliente', retorno.cliente);
                        JanelaDefineCliente.close();

                        $.ajax({
                            url: "home/index.php",
                            success: function (dados) {
                                $("#body").html(dados);
                            }
                        });
                    }
                },
                error: function () {

                }
            });


        });

        function masked(data) {
            setTimeout(function () {
                var v = mphone(data);
                if (v != data) {
                    data = v;
                }
            }, 1);
        }

        function mphone(v) {
            var r = v.replace(/\D/g, "");

            r = r.replace(/^0/, "");

            if (r.length > 10) {
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (r.length > 5) {
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (r.length > 2) {
                r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                r = r.replace(/^(\d*)/, "($1");
            }

            return r;
        }

        //Validação de telefone
        function validatePhone(phone) {
            var result = phone.replace(/[^a-zA-Z0-9]/g, '');
            var regex = new RegExp('^((1[1-9])|([2-9][0-9]))((3[0-9]{3}[0-9]{4})|(9[0-9]{3}[0-9]{5}))$');

            return regex.test(result);
        }

    })
</script>