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
                    <input value='TAMER M. ELMENOUFI' />
                    <small>Número</small>
                    <input inputmode="numeric" maxlength='19' type="text" id="cartao_numero" value='1144 3241 5783 3327' />
                    <div class="row">
                        <div class="col-2">
                            <small>MM</small>
                            <input value='12' />
                        </div>
                        <div class="col-3">
                            <small>AAAA</small>
                            <input value='2027' />
                        </div>
                        <div class="col-4">

                        </div>
                        <div class="col-3">
                            <small>CCV</small>
                            <input value='835' />
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


    })
</script>