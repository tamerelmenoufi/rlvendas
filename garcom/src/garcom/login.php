<?php
include("../../../lib/includes.php");

if ($_POST['cpf'] and $_POST['senha']) {

    $query = "select * from atendentes where cpf = '{$_POST['cpf']}' and senha = '" . md5($_POST['senha']) . "'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result)) {
        $d = mysqli_fetch_object($result);
        $_SESSION['AppGarcom'] = $d->codigo;
        $_SESSION['AppPerfil'] = json_decode($d->perfil);
        $status = 'sucesso';
    } else {
        $status = 'erro';
        $_SESSION['AppGarcom'] = false;
        $_SESSION['AppPerfil'] = false;
    }

    echo json_encode([
        "AppGarcom" => $_SESSION['AppGarcom'],
        "status" => $status
    ]);

    exit();
}
?>

<style>
    .jqbtk-container .btn {
        border: 1px solid #999999;
    }
</style>
<div class="col">
    <!-- <div class="col-12">Cadastro/Acesso do Cliente</div> -->
    <div class="col-12">
        <h4 class="mb-4 text-center">Informe seus dados de acesso</h4>
    </div>

    <div class="col-12 mb-3">
        <label for="cpf">Digite seu CPF</label>
        <input
                style="text-align:center"
                type="text"
                inputmode="numeric"
                autocomplete="off"
                class="form-control form-control-lg"
                id="cpf"
                name="cpf"
                value=""
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
                name="senha"
                value=""
        >
    </div>

    <div class="col-12 mt-4">
        <button AcessoGarcom class="btn btn-primary btn-block btn-lg">Acesso do Garçom</button>
    </div>
</div>

<script>
    $(function () {
        $("#cpf").mask("999.999.999-99");

        if (isDesktop) {
            $('#cpf').keyboard({
                layout: [
                    [['1'], ['2'], ['3'], ['4'], ['5'], ['6'], ['7'], ['8'], ['9'], ['0'], ['del']],
                ]
            });

            $('#senha').keyboard();
        }


        $("button[AcessoGarcom]").click(function () {
            cpf = $("#cpf").val();
            senha = $("#senha").val();

            if (cpf && senha) {
                $.ajax({
                    url: "src/garcom/login.php",
                    type: "POST",
                    data: {
                        cpf,
                        senha
                    },
                    success: function (dados) {

                        let retorno = JSON.parse(dados);

                        if (retorno.status == 'sucesso') {
                            window.localStorage.setItem('AppGarcom', retorno.AppGarcom);
                            $.ajax({
                                url: "src/home/index.php",
                                success: function (dados) {
                                    $(".ms_corpo").html(dados);
                                    PageClose();
                                }
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