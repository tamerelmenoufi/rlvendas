<?php
include("../../lib/includes.php");

?>

<div style="position:fixed;left: 30px; bottom: 20px;z-index: 999">
    <button voltar class="btn btn-primary btn-lg">Continuar Comprando</button>
</div>


<div class="container">
    <div class="col-md-12" style="margin-top: 6rem">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <h2 class="h2 font-weight-bold">Pagamento</h2>
                    <p class="h4">Por favor se direcione até o caixa para efetuar o pagamento</p>
                    <p class="h4 text-center">OU</p>
                    <p class="h4">Você pode Solicitar que o garçon envie comanda de pagamento em sua mesa.</p>
                </div>

                <button class="btn btn-info btn-lg btn-block mt-4">
                    <i class="fa-solid fa-bell-concierge"></i> Solicitar pagamento na mesa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("button[voltar]").click(function () {
            $.ajax({
                url: "pagamento/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });
    });
</script>
