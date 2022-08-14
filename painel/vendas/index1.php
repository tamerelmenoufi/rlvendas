<?php

include("../../lib/includes.php");
include "./conf.php";

if ($_POST['acao'] == 'NotaPdf') {

    $dadosParaEnviar = http_build_query(
        array(
            'doc' => $_POST['doc'],
        )
    );
    $opcoes = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $dadosParaEnviar
        )
    );
    $contexto = stream_context_create($opcoes);
    $result = file_get_contents('http://html2img.mohatron.com/get.php', false, $contexto);

    return $_POST['doc'];


    exit();

}


if ($_POST['acao'] == 'pago') {
    mysqli_query($con, "update vendas set situacao = 'pago' where codigo = '{$_POST['cod']}'");
}


if ($_GET['opc']) $_SESSION['opc_status'] = $_GET['opc'];

switch ($_SESSION['opc_status']) {
    case 'producao':
    {
        $where = "AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'preparo':
    {
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'pagar':
    {
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'pago':
    {
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'cancelados':
    {
        $where = " AND v.deletado = '1' ";
        break;
    }
    default:
    {
        $where = false;
    }
}

$query = "SELECT v.*, c.telefone, m.mesa AS mesa_descricao, c.nome AS cliente_nome FROM vendas v "
    . "INNER JOIN clientes c ON c.codigo = v.cliente "
    . "INNER JOIN mesas m ON m.codigo = v.mesa "
    . "LEFT JOIN atendentes a ON a.codigo = v.atendente "
    . "WHERE 1 {$where} order by v.codigo desc limit 100";

#echo $query;

$result = mysqli_query($con, $query);

?>

<div id="vendas">
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
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table id="datatable" class="table display nowrap" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Mesa</th>
                        <th>Período</th>
                        <th>Situação</th>
                        <th class="mw-20">Lista</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($d = mysqli_fetch_object($result)):
                        ?>
                        <tr id="linha-<?= $d->codigo; ?>">
                            <td><?= $d->cliente_nome ?: $d->telefone; ?></td>
                            <td>
                                <?= number_format($d->total, 2, ',', '.'); ?>
                            </td>
                            <td><?= $d->mesa_descricao; ?></td>
                            <td>
                                De: <?= formata_datahora($d->data_pedido, DATA_HM); ?><br>
                                Até: <?= formata_datahora($d->data_finalizacao, DATA_HM); ?>
                            </td>
                            <td><?= getSituacaoOptions($d->situacao, $d->codigo) ?></td>
                            <td>
                                <button lista="<?= $d->codigo ?>" class="lista btn btn-primary">
                                    <i class="fa-solid fa-rectangle-list"></i>
                                </button>
                                <!-- <button print="<?= $d->codigo ?>" class="btn btn-success">
                                <i class="fa-solid fa-print"></i>
                            </button> -->

                                <!-- <button print2="<?= $d->codigo ?>" class="btn btn-success">
                                <i class="fa-solid fa-print"></i>
                            </button> -->


                                <div class="btn-group" role="group">
                                    <!-- <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    R$ '.number_format($d->total,2,",",".").'
                                    </button> -->
                                    <button id="btnGroupDrop1" class="btn btn-success dropdown-toggle btn-group-sm"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a print2="<?= $d->codigo ?>" local="terminal1" class="dropdown-item"
                                           href="#">Caixa</a>
                                        <a print2="<?= $d->codigo ?>" local="terminal2" class="dropdown-item" href="#">Terminais</a>
                                    </div>
                                </div>


                            </td>

                            <!--<td>
                            <button
                                    class="btn btn-sm btn-link"
                                    url="<? /*= $UrlScript */
                            ?>/form.php?codigo=<? /*= $d->codigo; */
                            ?>"
                            >
                                <i class="fa-solid fa-pencil text-warning"></i>
                            </button>
                            <button
                                    class="btn btn-sm btn-link btn-excluir"
                                    data-codigo="<? /*= $d->codigo */
                            ?>"
                            >
                                <i class="fa-regular fa-trash-can text-danger"></i>
                            </button>
                        </td>-->
                        </tr>
                    <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#datatable").DataTable({
            responsive: true
        });

        $("span[acao]").click(function () {
            acao = $(this).attr("acao");
            cod = $(this).attr("cod");

            $.confirm({
                content: "Confirma o pagamento?",
                title: false,
                buttons: {
                    'SIM': function () {

                        $.ajax({
                            url: "vendas/index1.php",
                            type: "POST",
                            data: {
                                acao,
                                cod
                            },
                            success: function (dados) {
                                $("#palco").html(dados);
                            }
                        });
                    },
                    'NÃO': function () {

                    }
                }
            });
        });

        $("#vendas").on("click", ".lista", function () {

            cod = $(this).attr("lista");

            $.dialog({
                content: "url:vendas/detalhes.php?cod=" + cod,
                title: false,
                columnClass: 'col-md-8 col-xs-12'
            });
        });

        $("#vendas").on("click", "button[print]", function () {

            cod = $(this).attr("print");
            $.ajax({
                url: "vendas/print.php",
                type: "POST",
                data: {
                    cod,
                },
                success: function (dados) {
                    // $.alert(dados);

                    // acao = '<iframe src="http://localhost/print/print.php?pdf='+dados+'" border="0" width="0" height="0" style="opacity:0"></iframe>';
                    window.open('http://html2img.mohatron.com/pdf/' + dados);
                }
            });

        });

        $("#vendas").on("click", "button[print2]", function (e) {
            e.preventDefault();

            terminal = window.localStorage.getItem('AppTerminal');
            cod = $(this).attr("print2");

            $.ajax({
                url: "vendas/print-2.php",
                type: "POST",
                data: {
                    cod,
                    terminal
                },
                success: function (dados) {
                    //alert('x');
                }
            });

        });

        $("#vendas").on("click", "a[print2]", function (e) {
            e.preventDefault();

            terminal = $(this).attr("local");
            cod = $(this).attr("print2");

            $.ajax({
                url: "vendas/print-2.php",
                type: "POST",
                data: {
                    cod,
                    terminal
                },
                success: function (dados) {
                    //alert('x');
                }
            });

        });


    });
</script>