<?php
    include("../../lib/includes/includes.php");

?>
<style>
    .ms_icone_100{
        position:relative;
        width:100%;
        height:60px;
        margin-bottom:10px;
    }
    .ms_icone_100_item{
        position:absolute;
        left:10px;
        right:10px;
        height:100%;
        background-color:#F1F3F2;
        padding-top:20px;
        padding-left:50px;
        padding-right:45px;
        border-radius:20px;
        color:#777777;
        cursor:pointer;
    }
    .ms_icone_100_icone_esquerdo{
        position:absolute;
        left:10px;
        top:15px;
        color:#32CB4B;
    }
    .ms_icone_100_icone_direito{
        position:absolute;
        right:15px;
        top:20px;
        color:#777777;
    }
</style>

<div
    pagamentos<?=$md5?>
    valor="Cartão de Débito"
    icone="fa-credit-card"
    cod="1"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="far fa-credit-card fa-2x ms_icone_100_icone_esquerdo"></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Cartão de Débito
    </div>
</div>
<div
    pagamentos<?=$md5?>
    valor="Cartão de Crédito"
    icone="fa-credit-card"
    cod="2"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="fas fa-credit-card fa-2x ms_icone_100_icone_esquerdo"></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Cartão de Crédito
    </div>
</div>
<div
    pagamentos<?=$md5?>
    valor="Transferência PIX"
    icone="fa-file-powerpoint"
    cod="3"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="far fa-file-powerpoint fa-2x ms_icone_100_icone_esquerdo"></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Transferência PIX
    </div>
</div>

<div
    pagamentos<?=$md5?>
    valor="A vista em dinheiro"
    icone="fa-money-bill-alt"
    cod="4"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="far fa-money-bill-alt fa-2x ms_icone_100_icone_esquerdo"></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        A vista em dinheiro
    </div>
</div>


<script>
    $(function(){
        Carregando('none');

        $("div[pagamentos<?=$md5?>]").off('click').on('click',function(){

            local = $(this).attr('local');
            valor = $(this).attr('valor');
            icone = $(this).attr('icone');
            cod = $(this).attr('cod');

            dados = '<p><i class="far '+icone+'"></i> '+valor+'</p>';
            $(".pagamento").html(dados);
            $(".pagamento").attr("cod",cod);

            PageClose();

        })

    })
</script>