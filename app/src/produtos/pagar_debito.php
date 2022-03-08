<?php
    include("../../../lib/includes.php");

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
                            <div class="row">
                                <div style="border:solid 1px green;" class="col">
                                    <h2 style="position:relative; border:solid 1px red; height:100%; width:100%; display:flex; justify-content:center; align-items:center; align-content:center; flex-flow:column nowrap;">
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                    </h2>
                                </div>
                                <div class="col">
                                    <h2 style="display:flex; justify-content:center; align-items:center; align-content:center; flex-flow:column nowrap;">
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
                            <small>CCV</small>
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


    })
</script>