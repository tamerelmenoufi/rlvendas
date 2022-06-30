<?php
include("../../lib/includes.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = md5($_POST['senha']);

    $query = "SELECT * FROM usuarios WHERE usuario = '{$usuario}' AND senha = '{$senha}' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result)) {
        $d = mysqli_fetch_array($result);

        if ($d['status'] === '0') {
            echo json_encode(['status' => false, 'msg' => 'Usuário inativo']);
        } else {
            $_SESSION['usuario'] = $d;
            echo json_encode(['status' => true]);
        }

    } else {
        echo json_encode(
            [
                'status' => false,
                'msg' => 'Usuário ou senha incorreto',
                'query' => $query
            ]);
    }
    exit();
}

session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="description" content="Sistema de Gerenciamento">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <?php include "../../lib/header.php"; ?>
</head>

<body class="">

<style>
    .bg-login-imagem {
        background: url('../../img/yobom.png'), no-repeat;
        background-repeat: no-repeat;
        background-size: contain;
    }

</style>
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9 mt-5">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-imagem"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 font-weight-bold mb-4 text-danger">Painel de Controle</h1>
                                </div>
                                <form class="user" id="form-login">

                                    <div class="form-group">
                                        <input
                                                type="text"
                                                class="form-control form-control-user"
                                                id="usuario"
                                                name="usuario"
                                                aria-describedby="usuario"
                                                placeholder="Usuário"
                                                autocomplete="false"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <input
                                                type="password"
                                                class="form-control form-control-user"
                                                name="senha"
                                                id="senha"
                                                placeholder="senha"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <select
                                            id="terminal"
                                            class="form-control form-control-user"
                                            placeholder="Caixa"
                                        >
                                            <option value="terminal1">Caixa</option>
                                            <option value="terminal2">Terminais</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-user btn-block">
                                        Entrar
                                    </button>

                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="#">Esqueci minha senha?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="<?= $caminho_vendor; ?>/jquery/jquery.min.js"></script>
<script src="<?= $caminho_vendor; ?>/bootstrap4/js/bootstrap.bundle.min.js"></script>
<script src="<?= $caminho_vendor; ?>/startbootstrap-sb-admin-2/js/sb-admin-2.min.js"></script>
<script src="<?= $caminho_vendor; ?>/tata/tata.js"></script>
<script src="<?= $caminho_vendor; ?>/tata/index.js"></script>

<script>
    $(function () {

        Terminal = window.localStorage.getItem('AppTerminal');

        if(Terminal != null && Terminal != undefined && Terminal){
            $("#terminal").val(Terminal);
        }


        $("#form-login").submit(function (e) {
            e.preventDefault();

            var terminal = $("#terminal").val();
            window.localStorage.setItem('AppTerminal', terminal);

            $.ajax({
                url: 'index.php',
                data: $(this).serializeArray(),
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    if (response.status) {
                        window.location = '../index.php';
                    } else {
                        tata.error('Aviso', response.msg);
                    }
                }

            })
        });
    });
</script>
</body>
