<?php
include("../../../../lib/includes.php");


if ($_SERVER['REQUEST_METHOD'] === "GET" and $_GET['acao'] === "excluir") {
    $codigo = $_GET['codigo'];

    $query = "DELETE FROM lista_item WHERE codigo = '$codigo'";

    if (mysql_query($query)) {
        //echo json_encode(['status' => true, 'msg' => 'Registro excluído com sucesso']);
    } else {
        //echo json_encode(['status' => false, 'msg' => 'Error ao excluír', 'query' => $query]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST" and $_POST['acao'] === 'incluir_item') {
    $descricao = $_POST['descricao'];
    $inserir = "INSERT INTO lista_item SET
                                            list_descricao = '" . utf8_decode($descricao) . "',
                                            list_cliente = '{$_SESSION['ms_cli_codigo']}',
                                            list_data = NOW(),
                                            list_situacao = '0'
                                            ";
    mysql_query($inserir);
    exit;
}



?>

<style>
    .ms_usuario_lista_titulo_topo {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 65px;
        background: #fff;
        text-align: center;
        color: #777;
        font-size: 18px;
        font-weight: bold;
        z-index: 10;
        padding: 15px;
    }

    .ms_usuario_lista {
        position: relative;
        width: 100%;
        height: 60px;
        margin-bottom: 10px;
    }

    .ms_usuario_lista_titulo_item {
        position: absolute;
        left: 10px;
        right: 10px;
        height: 100%;
        background-color: #F1F3F2;
        padding-top: 20px;
        padding-left: 50px;
        padding-right: 45px;
        border-radius: 20px;
        color: #777777;
        cursor: pointer;
    }

    .ms_usuario_lista_icone_esquerdo {
        position: absolute;
        left: 10px;
        top: 15px;
        color: #32CB4B;
        opacity: 0.4;
    }

    .ms_usuario_lista_icone_direito {
        position: absolute;
        right: 15px;
        top: 15px;
        color: red;
        opacity: 0.4;
    }


    .ms_usuario_lista_form {
        position: fixed;
        width: 100%;
        height: 60px;
        top: 65px;
        background-color: #fff;
        border: solid 0px red;
        z-index: 10;
    }

    .ms_usuario_lista_form span {
        position: absolute;
        left: 10px;
        top: 10px;
        right: 110px;
    }

    .ms_usuario_lista_form span input {
        position: relative;
        width: 100%;
        padding: 5px;
        padding-left: 35px;
        height: 40px;
        background-color: #F1F4F3;
        background-position: left 10px center;
        background-size: 20px 20px;
        background-repeat: no-repeat;
        border: 0;
        border-radius: 10px;
        color: #777777;
    }

    .ms_usuario_lista_form button {
        position: absolute;
        right: 5px;
        top: 10px;
        width: 100px;
        height: 40px;
        border-radius: 10px;
        color: #4CBB5E;
    }

    .ms_usuario_lista_form svg {
        position: absolute;
        left: 20px;
        top: 22px;
        z-index: 1;
        color: #32CB4B;
    }
</style>

<div class="ms_usuario_lista_titulo_topo">Lista de Itens</div>

<div class="ms_usuario_lista_form">
    <i class="fas fa-pencil-alt"></i>
    <span>
        <input
                typeof="text"
                valor_busca
                type="text"
                value="<?= $d->list_descricao; ?>"
                required
        />
    </span>
    <button
            incluir_item
            type="button"
            class="btn btn-light"
    >
        Incluir
    </button>
</div>
<div conteudo_lista style="margin-top:70px"></div>


<script>
    $(function () {


        $.ajax({
                url: "src/usuarios/lista_nova.php",
                success: function (dados) {
                    $("div[conteudo_lista]").html(dados);

                }

            });

        Carregando('none');

        $("button[incluir_item]").off('click').on('click', function () {
            obj = $(this).parent("div").parent("div");
            var descricao = $("input[valor_busca]").val();

            if(descricao){
                $("input[valor_busca]").val('');
                Carregando();
                $.ajax({
                    url: "src/usuarios/lista.php",
                    type: "POST",
                    data: {acao: 'incluir_item', descricao},
                    success: function (dados) {
                        $.ajax({
                            url: "src/usuarios/lista_nova.php",
                            success: function (dados) {
                                $("div[conteudo_lista]").html(dados);
                            }
                        });
                    }
                });
            }
        })

    })
</script>