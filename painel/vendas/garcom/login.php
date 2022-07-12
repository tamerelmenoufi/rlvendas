<?php
include("../../../lib/includes.php");

if ($_POST['cpf'] and $_POST['senha']) {
    $cpf = trim($_POST['cpf']);
    $senha = trim(md5($_POST['senha']));

    $query_atendente = "SELECT * from atendentes WHERE cpf = '{$cpf}' and senha = '{$senha}' LIMIT 1";
    $result = mysqli_query($con, $query_atendente);

    if (mysqli_num_rows($result)) {
        $d = mysqli_fetch_object($result);
        $_SESSION['PainelGarcom'] = $d->codigo;
        $status = 'sucesso';
    } else {
        $status = 'erro';
        $_SESSION['PainelGarcom'] = false;
    }

    echo json_encode([
        "PainelGarcom" => $_SESSION['PainelGarcom'],
        "status" => $status,
        "sql" => $query_atendente,
        "db" => mysqli_error($con)
    ]);

    exit();
}
?>

<div class="col">
    <!-- <div class="col-12">Cadastro/Acesso do Cliente</div> -->
    <h5 class="col-12 text-center mb-3">Informe seus dados de acesso</h5>
    <div class="col-12 mb-3">
        <label for="cpf">Digite seu CPF</label>
        <input
                style="text-align:center"
                type="text"
                inputmode="numeric"
                autocomplete="off"
                class="form-control form-control-lg"
                id="cpf"
        >
    </div>
    <div class="col-12 mb-3">
        <label for="cpf">Informe sua senha</label>
        <input
                style="text-align:center"
                type="password"
                inputmode="numeric"
                autocomplete="off"
                class="form-control form-control-lg"
                id="senha"
        >
    </div>
    <div class="col-12 mt-3">
        <button AcessoGarcom class="btn btn-primary btn-block btn-lg">Acesso do Garçom</button>
    </div>
</div>

<script>
    $(function () {

        $("#cpf").mask("999.999.999-99");

        $("button[AcessoGarcom]").click(function () {
            cpf = $("#cpf").val();
            senha = $("#senha").val();

            if (cpf && senha) {
                $.ajax({
                    url: "vendas/garcom/login.php",
                    type: "POST",
                    data: {
                        cpf,
                        senha
                    },
                    success: function (dados) {
                        let retorno = JSON.parse(dados);

                        if (retorno.status == 'sucesso') {

                            $(".TelaVendas").css("display", "none");
                            $(".TelaVendas").html("");


                            $('.loading').fadeIn(200);

                            $.ajax({
                                url: "vendas/home.php",
                                success: function (data) {
                                    $(".TelaVendas").html(data);
                                    janela_login.close(); //Fecha modal login
                                }
                            })
                                .done(function () {
                                    $('.loading').fadeOut(200);
                                    $(".TelaVendas").css("display", "block");
                                })
                                .fail(function (error) {
                                    alert('Error');
                                    $('.loading').fadeOut(200);
                                });


                        } else {
                            $.alert('Dados incorretos, favor tente novamente!');
                        }


                    }
                });
            } else {
                //$.alert('Favor informe o número do seu telefone!');
                $.alert('Favor informe os seus dados de acesso!'); //REMOVER DEPOIS
            }


        });
    })
</script>