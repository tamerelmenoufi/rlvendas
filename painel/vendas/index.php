<?php

include("../../lib/includes.php");
include "./conf.php";

if($_POST['acao'] == 'NotaPdf'){

    $documento = GerarPDF($_POST['doc']);

    $dados = json_decode($documento);

    file_put_contents($dados->name, base64_decode($dados->doc));

    echo "{$dados->name}";
    exit();

}


if($_POST['acao'] == 'pago'){
    mysqli_query($con, "update vendas set situacao = 'pago' where codigo = '{$_POST['cod']}'");
}


if($_GET['opc']) $_SESSION['opc_status'] = $_GET['opc'];

switch($_SESSION['opc_status']){
    case 'producao':{
        $where = "AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'preparo':{
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'pagar':{
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
        break;
    }
    case 'pago':{
        $where = " AND v.situacao = '{$_SESSION['opc_status']}' AND v.deletado != '1' ";
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
                        <td><?= formata_datahora($d->data_pedido, DATA_HM); ?></td>
                        <td><?=getSituacaoOptions($d->situacao, $d->codigo)?></td>
                        <td>
                            <button lista="<?=$d->codigo?>" class="btn btn-primary">
                                <i class="fa-solid fa-rectangle-list"></i>
                            </button>
                            <button print="<?=$d->codigo?>" class="btn btn-success">
                                <i class="fa-solid fa-print"></i>
                            </button>


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

        $("span[acao]").click(function(){
            acao = $(this).attr("acao");
            cod = $(this).attr("cod");
            $.confirm({
                content:"Confirma o pagamento?",
                title:false,
                buttons:{
                    'SIM':function(){

                        $.ajax({
                            url:"vendas/index.php",
                            type:"POST",
                            data:{
                                acao,
                                cod
                            },
                            success:function(dados){
                                $("#palco").html(dados);
                            }
                        });


                    },
                    'NÃO':function(){

                    }
                }
            });
        });


        $("button[lista]").click(function(){
            cod = $(this).attr("lista");
            $.dialog({
                content:"url:vendas/detalhes.php?cod="+cod,
                title:false,
                columnClass: 'col-md-8'
            });
        });

        $("button[print]").click(function(){
            cod = $(this).attr("print");
            $.ajax({
                url:"vendas/print.php",
                type:"POST",
                data:{
                    cod,
                },
                success:function(dados){
                    $.ajax({
                        url:"vendas/index.php",
                        type:"POST",
                        data:{
                            acao:'NotaPdf',
                            doc:dados,
                        },
                        success:function(dados){

                            $.alert('Dados enviados!');
                            acao = '<iframe src="ttp://localhost/print/print.php?pdf='+dados+'" border="0" width="0" height="0" style="opacity:0"></iframe>';
                            $("body").append(acao);
                            //Dados de teste

                        }
                    });
                }
            });

        });

    });
</script>