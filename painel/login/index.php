<?php
include("../../lib/includes.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = mysql_real_escape_string($_POST['usuario']);
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
            ['status' => false,
                'msg' => 'Usuário ou senha incorreto',
                'query' => $query,
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

<body>

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-12 col-md-9 mt-5">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col-md-12">
                            <div class="p-4 p-md-5 p-lg-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-700 mb-4">Acesso ao sistema painel</h1>
                                </div>
                                <form class="form-login">
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
                                    <button type="submit" class="btn btn-danger btn-user btn-block">
                                        Entrar
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="#">Esqueceu a senha?</a>
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
        $(".form-login").submit(function (e) {
            e.preventDefault();

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
