<?php
include("../../lib/includes.php");

?>

<style>
    .card {
        border-radius: 10px;
    }

    .card small {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
    }

    .card input {
        border: solid 1px #ccc;
        border-radius: 8px;
        background-color: #FFFFFF !important;
        color: #999;
        font-size: 20px;
        margin-bottom: 20px;
        padding: 5px 15px;
        width: 100%;
    }

    .card input::placeholder {
        text-transform: initial;
    }

    .card .icone {
        width: 50px;
        height: 50px;
    }

    .jconfirm-box {
        background: #144766 !important;
    }
</style>
<div class="container" style="margin-top: 5rem">
    <div class="card">
        <div class="card-body mb-4">
            <h4 class="h4 font-weight-bold">Pagamento no cartão de débito</h4>

            <p></p>

            <div class="d-flex align-items-center justify-content-center mt-2 flex-column">

                <div style="margin-bottom:20px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="card text-white bg-info mb-3" style="padding:20px;">
                                <small>NOME</small>
                                <input
                                        class="form-control"
                                        type="text"
                                        id="cartao_nome"
                                        name="cartao_nome"
                                        placeholder="Nome no cartão"
                                        value=""
                                        readonly
                                />

                                <small>NÚMERO</small>
                                <input
                                        class="form-control"
                                        inputmode="numeric"
                                        maxlength='19'
                                        type="text"
                                        id="cartao_numero"
                                        name="cartao_numero"
                                        placeholder="Número do cartão"
                                        value=""
                                        readonly
                                />

                                <div class="row">
                                    <div class="col-4">
                                        <small>BANDEIRAS</small>
                                        <div class="row">
                                            <div class="col">
                                                <h2>
                                                    <i class="fa-brands fa-cc-mastercard icone"></i>

                                                    <i class="fa-brands fa-cc-visa icone"></i>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <small>MM</small>
                                        <input
                                                class="form-control"
                                                inputmode="numeric"
                                                maxlength='2'
                                                type="text"
                                                id="cartao_validade_mes"
                                                name="cartao_validade_mes"
                                                placeholder="00"
                                                value=""
                                                readonly
                                        />
                                    </div>
                                    <div class="col-3">
                                        <small>AAAA</small>
                                        <input
                                                class="form-control"
                                                inputmode="numeric"
                                                maxlength="4"
                                                type="text"
                                                id="cartao_validade_ano"
                                                name="cartao_validade_ano"
                                                placeholder="0000"
                                                value=""
                                                readonly
                                        />
                                    </div>
                                    <div class="col-3">
                                        <small>CVV</small>
                                        <input
                                                class="form-control"
                                                inputmode="numeric"
                                                maxlength="4"
                                                type="text"
                                                id="cartao_ccv"
                                                name="cartao_ccv"
                                                placeholder="0000"
                                                value=""
                                                readonly
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

    <div id="keyboard"></div>

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

        $(".card input").click(function () {
            var name = $(this).attr("name");
            var valor = $(this).val();

            dialogTeclado = $.alert({
                title: false,
                content: `url: pagamento/teclado.php?id=${name}&valor=${valor}`,
                columnClass: "xlarge",
                buttons: {
                    "cancelar": {
                        text: "CANCELAR",
                        action: function () {
                            $(`search_field-${name}`).val("");
                            $(`#${name}`).text("");
                            dialogTeclado.close();
                        }
                    },
                    "adicionar": {
                        text: "CONFIRMAR",
                        btnClass: "btn-green",
                        action: function () {
                            $(`#${name}`).val($(`#search_field-${name}`).val());
                            dialogTeclado.close();
                        },
                    }
                }
            });
        });
    });
</script>