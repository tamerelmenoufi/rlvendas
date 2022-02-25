<?php
    include("../../lib/includes/includes.php");
?>
<style>
    .ms_usuario_compras_detalhes_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:65px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
    }
    .ms_usuario_compras_detalhes{
        position:fixed;
        top:70px;
        left:20px;
        right:20px;
        bottom:10px;
        overflow:auto;
    }
    .ms_usuario_compras_detalhes_comanda{
        position:absolute;
        top:0;
        left:0;
        width:100%;
        padding:10px;
        background:#FFFBD8;
        color:#777777;
        font-size:12px;
        border-radius:10px;
        border:solid 1px #FFF7AB;
        height:auto;
    }
    .ms_usuario_compras_detalhes_comanda_item{
        position:relative;
    }
    .ms_usuario_compras_detalhes_comanda_item span[valor_total]{
        position:absolute;
        right:10px;
        border:solid 0px red;
        width:110px;
        height:20px;
        text-align:right;
    }
    .ms_usuario_compras_detalhes_comanda_item span[valor_unitario]{
        position:absolute;
        right:120px;
        border:solid 0px red;
        width:110px;
        height:20px;
        text-align:right;
    }
</style>

<div class="ms_usuario_compras_detalhes_topo">Detalhes da Compra</div>

<div class="ms_usuario_compras_detalhes">
    <div class="ms_usuario_compras_detalhes_comanda">
        <h3>DB00000123</h3>
        <p style="margin-bottom:50px;">Manaus, 23 de Julho de 2021 as 17:54</p>



    <div class="ms_usuario_compras_detalhes_comanda_item">
        <div style="position:relative; height:20px;">
            <b>DESCRIÇÃO</b>
            <span valor_unitario><b>Vl Uin.</b></span>
            <span valor_total><b>Vl Tot.</b></span>
        </div>
    </div>

<?php
    $tot = 0;
    for($i=0;$i<50;$i++){

        $qt = rand(1, 6);
        $valor = rand(12, 27);

?>
    <div class="ms_usuario_compras_detalhes_comanda_item">
        <div style="position:relative; height:20px;"><?=$qt?> x Descrição do produto <?=$i+1?></div>
        <div style="position:relative; height:30px;">
            <span valor_unitario>R$ <?=number_format($valor,2,',','.')?></span>
            <span valor_total>R$ <?=number_format($qt*$valor,2,',','.')?></span>
        </div>
    </div>
<?php
        $tot = ($tot + ($qt*$valor));
    }
?>

        <div class="ms_usuario_compras_detalhes_comanda_item">
            <div style="position:relative; height:20px;">
                <b>TOTAL DA COMPRA</b>
                <span valor_total><b>R$ <?=number_format($tot,2,',','.')?></b></span>
            </div>
        </div>

    </div>
</div>


<script>
    $(function(){

    })
</script>