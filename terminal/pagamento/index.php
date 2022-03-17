<?php
include("../../lib/includes.php");

$query = "SELECT SUM(vp.valor_total) AS total FROM vendas v "
    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
    . "WHERE v.situacao = '0' AND "
    . "vp.mesa = '{$_SESSION['ConfMesa']}' AND "
    . "vp.cliente = '{$_SESSION['ConfCliente']}' AND "
    . "vp.deletado = '0'";

$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

?>

<div id="pagamento" class="mt-5">
    <div style="position:fixed;left: 30px; bottom: 20px;z-index: 999">
        <button voltar class="btn btn-primary btn-lg">VOLTAR</button>
    </div>

    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="h4 font-weight-bold">Dados da Compra</h4>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <b>PEDIDO</b>
                            </div>
                            <div>
                                <?= $_SESSION['ConfMesa']; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <b>TOTAL</b>
                            </div>
                            <div>
                                R$ <?= number_format($d->total, 2, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-body">
                    <h4 class="h4 font-weight-bold">Formas de Pagamento</h4>
                    <hr>
                    <div class="px-md-5">
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="pix"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-brands fa-pix"></i> PIX
                            </a>
                        </h5>
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="debito"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-solid fa-credit-card"></i> Débito
                            </a>
                        </h5>
                        <h5 class="card-title">
                            <a
                                    pagar
                                    opc="credito"
                                    class="btn btn-info btn-lg btn-block">
                                <i class="fa-solid fa-credit-card"></i> Crédito
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
                url: "home/comanda.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $("a[pagar]").click(function () {
            opc = $(this).attr("opc");

            $.ajax({
                url: `pagamento/pagar_${opc}.php`,
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });
    });
</script>
