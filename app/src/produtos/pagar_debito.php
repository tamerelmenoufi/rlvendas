<?php
    include("../../../lib/includes.php");


    if($_POST['acao'] == 'pagar'){

        require "../../../lib/vendor/rede/Transacao.php";

        $query = "insert into status_rede set venda = '{$_POST['reference']}', data = NOW(), retorno = '{$retorno}'";
        mysqli_query($con, $query);

        require "../../../lib/vendor/rede/Consulta.php";
        $r = json_decode($retorno);

        $query = "update vendas set

                                    operadora = 'rede',
                                    operadora_situacao = '{$r->authorization->status}',
                                    operadora_retorno = '{$retorno}',
                                    valor = '{$_POST['amount']}',
                                    taxa = '{$_POST['taxa']}',
                                    desconto = '{$_POST['desconto']}',
                                    acrescimo = '{$_POST['acrescimo']}',
                                    total = '".($_POST['amount'] + $_POST['taxa'] - $_POST['desconto'] + $_POST['acrescimo'])."',
                                    observacoes = '{$_POST['observacoes']}',
                                    data_finalizacao = NOW(),
                                    situacao = '{$r->authorization->status}',
                                    forma_pagamento = 'debito'

                where codigo = '{$_POST['reference']}'";
        mysqli_query($con, $query);

        if($r->authorization->status == 'Approved'){
            //mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}'");
            $_SESSION['AppVenda'] = false; //mysqli_insert_id($con);
            $_SESSION['AppPedido'] = false;
            $_SESSION['AppCarrinho'] = false;
            echo json_encode([
                'status' => $r->authorization->status,
                'msg' => 'Operação realizada com sucesso!',
                //'AppVenda' => $_SESSION['AppVenda'],
            ]);
        }else if($r->authorization->status == 'Denied')
        {
            echo json_encode([
                'status' => $r->authorization->status,
                'msg' => 'Operação Negada, consulte os dados do Cartão ou entre em contato com sua operadora!',
                //'AppVenda' => $_SESSION['AppVenda'],
            ]);
        }else{
            echo json_encode([
                'status' => false,
                'msg' => 'Ocorreu um erro, tente novamente!',
                //'AppVenda' => $_SESSION['AppVenda'],
            ]);
        }
        exit();
    }

    $query = "select
                    sum(a.valor_total) as total,
                    b.nome,
                    b.telefone
                from vendas_produtos a
                    left join clientes b on a.cliente = b.codigo
                where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
    .card small{
        font-size:12px;
        text-align:left;
    }
    .card input{
        border:solid 1px #ccc;
        border-radius:3px;
        background-color:#eee;
        color:#333;
        font-size:20px;
        text-align:center;
        margin-bottom:15px;
        width:100%;
        text-transform:uppercase;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?> com Débito</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">
                <div class="card text-white bg-info mb-3" style="padding:20px;">

                    <small>Nome</small>
                    <input type="text" id="cartao_nome" placeholder="NOME NO CARTÃO" value='' />
                    <small>Número</small>
                    <input inputmode="numeric" maxlength='19' type="text" id="cartao_numero" placeholder="0000 0000 0000 0000" value='' />
                    <div class="row">
                        <div class="col-4">
                            <small>BANDEIRAS</small>
                            <div class="row">
                                <div class="col">
                                    <h2>
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                    </h2>
                                </div>
                                <div class="col">
                                    <h2>
                                        <i class="fa-brands fa-cc-visa"></i>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <small>MM</small>
                            <input inputmode="numeric" maxlength='2' type="text" id="cartao_validade_mes" placeholder="00" value='' />
                        </div>
                        <div class="col-3">
                            <small>AAAA</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_validade_ano" placeholder="0000" value='' />
                        </div>
                        <div class="col-3">
                            <small>CVV</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_ccv" placeholder="0000" value='' />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("#cartao_numero").mask("9999 9999 9999 9999");
        $("#cartao_validade_mes").mask("99");
        $("#cartao_validade_ano").mask("9999");
        $("#cartao_ccv").mask("9999");

        $("#Pagar").click(function(){

            kind = 'debit';
            reference = '<?=$_SESSION['AppVenda']?>';
            amount = '<?=$d->total?>';
            cardholderName = $("#cartao_nome").val();
            cardNumber = $("#cartao_numero").val();
            expirationMonth = $("#cartao_validade_mes").val();
            expirationYear = $("#cartao_validade_ano").val();
            securityCode = $("#cartao_ccv").val();

            if(
                    !kind
                ||  !reference
                ||  !amount
                ||  !cardholderName
                ||  !cardNumber
                ||  !expirationMonth
                ||  !expirationYear
                ||  !securityCode

            ){
                $.alert('Preenche os dados do cartão corretamente!');
                return false;
            }

            $.ajax({
                url:"src/produtos/pagar_debito.php",
                type:"POST",
                data:{
                    kind,
                    reference,
                    amount,
                    cardholderName,
                    cardNumber,
                    expirationMonth,
                    expirationYear,
                    securityCode,
                    acao:'pagar'
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    if (retorno.status) {
                        window.localStorage.removeItem('AppVenda');
                        window.localStorage.removeItem('AppPedido');
                    }
                    $.alert(retorno.msg);
                    PageClose(2);
                }
            });

        });


    })
</script>