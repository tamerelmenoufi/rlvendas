<?php

include("../../lib/includes.php");
include "./conf.php";

switch($_GET['opc']){
    case 'producao':{
        $where = "AND v.situacao = '{$_GET['opc']}' AND v.deletado != '1' ";
        break;
    }
    case 'preparo':{
        $where = " AND v.situacao = '{$_GET['opc']}' AND v.deletado != '1' ";
        break;
    }
    case 'pagar':{
        $where = " AND v.situacao = '{$_GET['opc']}' AND v.deletado != '1' ";
        break;
    }
    case 'pago':{
        $where = " AND v.situacao = '{$_GET['opc']}' AND v.deletado != '1' ";
        break;
    }
    case 'cancelados':{
        $where = " AND v.deletado = '1' ";
        break;
    }
    default:{
        $where = false;
    }
}


$query = "SELECT v.*, c.telefone, m.mesa AS mesa_descricao, c.nome AS cliente_nome FROM vendas v "
    . "INNER JOIN clientes c ON c.codigo = v.cliente "
    . "INNER JOIN mesas m ON m.codigo = v.mesa "
    . "LEFT JOIN atendentes a ON a.codigo = v.atendente "
    . "WHERE 1 {$where}";

#echo $query;

$result = mysqli_query($con, $query);

?>

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

            <table id="datatable" class="table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Valor</th>
                    <th>Mesa</th>
                    <th>Data do Pedido</th>
                    <th>Situação</th>
                    <!-- <th class="mw-20">Ações</th>-->
                </tr>
                </thead>
                <tbody>
                <?php
                while ($d = mysqli_fetch_object($result)):
                    $status = $d->status == '1' ? 'success' : 'danger'; ?>
                    <tr id="linha-<?= $d->codigo; ?>">
                        <td><?= $d->cliente_nome ?: $d->telefone; ?></td>
                        <td>
                            <?= number_format($d->total, 2, ',', '.'); ?>
                        </td>
                        <td><?= $d->mesa_descricao; ?></td>
                        <td><?= formata_datahora($d->data_pedido, DATA_HM); ?></td>
                        <td>
                            <span class="badge badge-<?= $status; ?>">
                                <?= getSituacaoOptions($d->situacao); ?>
                            </span>
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

<script>
    $(function () {
        $("#datatable").DataTable();
    });
</script>