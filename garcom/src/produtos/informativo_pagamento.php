<?php
include("../../../lib/includes.php");

VerificarVendaApp();

if($_POST['acao'] == 'fechar_conta'){

    $caixa = mysqli_fetch_object(mysqli_query($con, "select * from caixa where situacao = '0'"));

    mysqli_query($con, "update vendas_pagamento set caixa = '{$caixa->caixa}' where venda = '{$_SESSION['AppVenda']}'");

    $query = "SELECT SUM(vp.valor_total) AS total FROM vendas v "
    . "INNER JOIN vendas_produtos vp ON vp.venda = v.codigo "
    . "WHERE v.situacao NOT IN ('pagar','pago') AND "
    . "vp.mesa = '{$_SESSION['AppPedido']}' AND "
    . "vp.cliente = '{$_SESSION['AppCliente']}' AND "
    . "vp.deletado != '1' AND v.codigo = '{$_SESSION['AppVenda']}'";

    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    mysqli_query($con, "update vendas SET
                                            situacao = 'pagar',
                                            caixa = (select caixa from caixa where situacao = '0'),
                                            valor='{$d->total}',
                                            total='{$d->total}',
                                            forma_pagamento='{$_POST['forma_pagamento']}',
                                            data_finalizacao = NOW()
                        where codigo = '{$_SESSION['AppVenda']}'
                ");

    mysqli_query($con, "update vendas_produtos set situacao = 'c' where venda = '{$_SESSION['AppVenda']}'");

    $_SESSION = [];

    echo json_encode([
        'status' => true,
        'msg' => 'Dados salvo com sucesso',
    ]);

    exit();
}

?>

<div class="container">
    <div class="col-md-12" style="margin-top: 3rem">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <h4 class="font-weight-bold">XX Esuqema de pagamento</h4>
                    <?php
                        $q = "select * from vendas_pagamento where venda = '{$_SESSION['AppVenda']}' and deletado != '1'";
                        $r = mysqli_query($con, $q);

                        if(mysqli_num_rows($r)){
                    ?>
                        <table class="table">
                        <thead>
                            <tr>
                                <th>Operação</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr>
                                <td><?=$p->forma_pagamento?></td>
                                <td><?=$p->valor?></td>
                            </tr>
                            <?php
                                $soma_valores = ($soma_valores + $p->valor);
                            }
                            ?>
                            <tr>
                                <th align="right">TOTAL</th>
                                <th><?=number_format($soma_valores,2,',','.')?></th>
                            </tr>
                        </tbody>
                        </table>
                    <?php
                        }
                    ?>
                    <p class="text-center">Por favor se direcione até o caixa para efetuar o pagamento</p>
                    <p class="text-center">OU</p>
                    <p class="text-center">Você pode Solicitar que o garçon envie comanda de pagamento em sua mesa.</p>
                </div>

                <button fechar_conta class="btn btn-info btn-block mt-4">
                    <i class="fa-solid fa-bell-concierge"></i> Solicitar pagamento na mesa<br>Fechar a Conta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {

        $("button[fechar_conta]").click(function () {
            $.ajax({
                url: "src/produtos/informativo_pagamento.php",
                type:"POST",
                data:{
                    acao:'fechar_conta',
                    forma_pagamento:'<?=$_POST['opc']?>',
                },
                success: function (dados) {

                    let retorno = JSON.parse(dados);

                    if(retorno.status){
                        //mySocket.send(<?=$_SESSION['AppVenda']?>);
                        window.localStorage.removeItem('AppPedido');
                        window.localStorage.removeItem('AppVenda');
                        window.localStorage.removeItem('AppCliente');
                        window.location.href='./';
                    }

                }
            });
        });

    });
</script>
