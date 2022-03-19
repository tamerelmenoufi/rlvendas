<?php
include("../../lib/includes.php");

?>

<style>
    .img-qrcode {
        margin-top: 10px;
        width: 150px;
    }
</style>
<div class="container" style="margin-top: 5rem">
    <div class="card">
        <div class="card-body mb-4">
            <h4 class="h4 font-weight-bold">Pagamento com pix</h4>

            <p class="h5 mb-4">Conclua seu pagamento via o App de seu banco. Aponte a camera do celular para código</p>

            <div class="d-flex align-items-center justify-content-center mt-2 flex-column">
                <h4 class="font-weight-bold">Pagamento pelo QR code</h4>

                <img
                        class="img-qrcode"
                        src="../img/qrcode_test.png"
                        alt="Falha ao carregar QR code"
                >
            </div>

        </div>
    </div>
</div>

<div style="position: fixed; bottom: 15px; left: 15px;">
    <button voltar class="btn btn-primary btn-lg">VOLTAR</button>
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
                url: "pagamento/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });
    });
</script>