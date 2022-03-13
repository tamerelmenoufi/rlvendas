<?php

include("../../lib/includes.php");
include "./conf.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'excluir') {
    $codigo = $_POST['codigo'];

    if (exclusao('categoria_medidas', $codigo)) {
        echo json_encode(["status" => true, "msg" => "Registro excluído com sucesso"]);
    } else {
        echo json_encode(["status" => false, "msg" => "Error ao tentar excluír"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'ordenar') {
    $dados = $_POST['dados'];
    $values = [];

    foreach ($dados as $dado) {
        $values[] = "('{$dado['codigo']}','{$dado['ordem']}')";
    }

    $query = "INSERT INTO categoria_medidas (codigo, ordem)"
        . "VALUES " . implode(",", $values) . " ON "
        . "DUPLICATE KEY UPDATE ordem = VALUES(ordem)";

    if (mysqli_query($con, $query)) {
        echo json_encode(["status" => true]);
    } else {
        echo json_encode(["status" => false]);
    }
    exit();
}

$query = "SELECT * FROM categoria_medidas WHERE deletado != '1' ORDER BY ordem, medida ASC";
$result = mysqli_query($con, $query);

?>

<!--<h1 class="h3 mb-2 text-gray-800">Secretarias</h1>-->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="./">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $ConfTitulo ?></li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $ConfTitulo ?>
        </h6>
        <div>
            <button type="button" class="btn btn-primary btn-sm ordenar">
                <i class="fa-solid fa-sort"></i> Ordenar
            </button>
            <button type="button" class="btn btn-success btn-sm" url="<?= $UrlScript; ?>/form.php">
                <i class="fa-solid fa-plus"></i> Novo
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table id="datatable" class="table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Medidas</th>
                    <th>QT. Produtos</th>
                    <th class="mw-20">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($d = mysqli_fetch_object($result)): ?>
                    <tr id="linha-<?= $d->codigo; ?>">
                        <td><?= $d->medida; ?></td>
                        <td><?= $d->qt_produtos; ?></td>
                        <td>
                            <button
                                    class="btn btn-sm btn-link"
                                    url="<?= $UrlScript ?>/form.php?codigo=<?= $d->codigo; ?>"
                            >
                                <i class="fa-solid fa-pencil text-warning"></i>
                            </button>
                            <button class="btn btn-sm btn-link btn-excluir" data-codigo="<?= $d->codigo ?>">
                                <i class="fa-regular fa-trash-can text-danger"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#datatable").DataTable();

        $(".ordenar").click(function () {
            var dados = [];

            $.alert({
                title: "Ordenar medidas",
                content: "url: categorias_medidas/ordenar.php",
                columnClass: "large",
                closeIcon: true,
                buttons: {
                    "SALVAR": {
                        "btnClass": "btn-green",
                        action: function () {
                            $('#sortable li').each(function (e) {
                                var codigo = $(this).data("codigo");

                                dados.push({'codigo': codigo, 'ordem': ($(this).index() + 1)});
                            });

                            $.ajax({
                                url: "categorias_medidas/index.php",
                                method: "POST",
                                dataType: "JSON",
                                data: {
                                    acao: "ordenar",
                                    dados,
                                },
                                success: function (dados) {
                                    if (dados.status) {
                                        tata.success('Sucesso', "Atualizado com sucesso");

                                        $(".loading").fadeIn(300);

                                        $.ajax({
                                            url: "categorias_medidas/index.php",
                                            success: function (dados) {
                                                $(".loading").fadeOut(300);
                                                $('#palco').html(dados);
                                            }
                                        });

                                    } else {

                                    }
                                }
                            });
                        }

                    },
                    "CANCELAR": function () {

                    }
                }
            });
        });

        $('.btn-excluir').click(function () {
            var codigo = $(this).data('codigo');

            $.confirm({
                title: 'Aviso',
                content: 'Deseja excluir este registro?',
                type: 'red',
                icon: 'fa fa-warning',
                buttons: {
                    sim: {
                        text: 'Sim',
                        btnClass: 'btn-red',
                        action: function () {
                            $.ajax({
                                url: '<?= $UrlScript;?>/index.php',
                                method: 'POST',
                                data: {
                                    acao: 'excluir',
                                    codigo
                                },
                                success: function (response) {
                                    let retorno = JSON.parse(response);

                                    if (retorno.status) {
                                        tata.success('Sucesso', retorno.msg);
                                    } else {
                                        tata.error('Error', retorno.msg);
                                    }

                                    $(`#linha-${codigo}`).remove();
                                }
                            })
                        }
                    },
                    nao: {
                        text: 'Não'
                    }
                }
            })
        });
    });
</script>