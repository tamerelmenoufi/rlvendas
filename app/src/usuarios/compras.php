<?php
    include("../../lib/includes/includes.php");
?>
<style>
    .ms_usuario_compras_titulo_topo{
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

    .ms_usuario_compras_100{
        position:relative;
        width:100%;
        height:120px;
        margin-bottom:10px;
    }
    .ms_usuario_compras_100_item{
        position:absolute;
        left:10px;
        right:10px;
        height:100%;
        background-color:#F1F3F2;
        padding-top:10px;
        padding-left:20px;
        padding-right:10px;
        border-radius:25px;
        color:#777777;
        cursor:pointer;
    }

    .ms_usuario_compras_100_botao{
        position:absolute;
        top:20px;
        right:10px;
        height:auto;
        width:auto;
        background-color:#194B38;
        padding:5px;
        border-radius:10px;
        color:#fff;
        cursor:pointer;
    }

</style>

<div class="ms_usuario_compras_titulo_topo">Compras</div>

<?php
for($i=0;$i<3;$i++){
?>
<div
    acao<?=$md5?>
    local="src/usuarios/compras_detalhes.php"
    class="ms_usuario_compras_100">
    <div class="ms_usuario_compras_100_item">
        <h4><i class="fas fa-shopping-cart"></i> DB0000234</h4>
        <p>
            <i class="fas fa-calendar"></i> 23/07/2021 as 14:45
            <div style="margin-top:10px; color:#cccccc">
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
            </div>
        </p>
        <button
                class="ms_usuario_compras_100_botao"
        >
            <i class="fas fa-thumbs-up"></i> Entregue<br><br>
            <i class="fas fa-money-bill-alt"></i> R$ 1.200,00
        </button>
    </div>
</div>
<?php
}
?>
<script>
    $(function(){
        Carregando('none');
        $("div[acao<?=$md5?>]").off('click').on('click',function(){
            local = $(this).attr('local');
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        })

    })
</script>