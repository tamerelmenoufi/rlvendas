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
    <h4>Pagar <?=$_SESSION['AppPedido']?> com PIX</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">
                <div class="card text-white bg-info mb-3" style="padding:20px;">
                    <p style="text-align:center">Utilize o QrCode para pagar a sua conta ou copie o códio PIX abaixo.</p>
                    <div style="padding:20px;">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAA…pcNOXzSFEOVBMdL/D4T8KXtKYnK373wAAAABJRU5ErkJggg==" style="width:100%"></i>
                    </div>
                    <p style="text-align:center; font-size:12px;">Seu Código PIX</p>
                    <p style="text-align:center; font-size:10px;">9873DKJHD87e39868885</p>
                    <button class="btn btn-success btn-lg btn-block"><i class="fa-solid fa-copy"></i> Copiar Código PIX</button>
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