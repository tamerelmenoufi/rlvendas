<?php
include("../../../lib/includes.php");

VerificarVendaApp();

if($_POST['acao'] == 'fechar_conta'){

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
                                            valor='{$d->total}',
                                            total='{$d->total}',
                                            forma_pagamento='{$_POST['forma_pagamento']}',
                                            data_finalizacao = NOW()
                        where codigo = '{$_SESSION['AppVenda']}'
                ");

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
                    <h4 class="font-weight-bold">Pagamento com <?=$_POST['opc']?></h4>
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
                        window.localStorage.removeItem('AppPedido');
                        window.localStorage.removeItem('AppVenda');
                        window.location.href='./';
                    }

                }
            });
        });

    });
</script>
