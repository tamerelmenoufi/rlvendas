<?php
    include("../../lib/includes.php");
?>
<style>
    .topo{
        position:fixed;
        width:100%;
        height:60px;
        background:red;
        left:0;
        top:0;
        text-align:center;
    }
    .topo img{
        height:50px;

    }

    .rodape{
        position:fixed;
        width:100%;
        height:65px;
        background:red;
        left:0;
        bottom:0;
    }
    .rodape .row .col{
        color:#fff;
        text-align:center;
        font-size:30px;
    }
    .rodape .row .col p{
        font-size:10px;
        text-align:center;
        color:#fff;
        padding:0;
        margin:0;
    }

    .pagina{
        position:fixed;
        top:60px;
        bottom:65px;
        width:100%;
        overflow:auto;
        background:yellow;
        padding:10px;
    }

</style>
<div class="topo">
    <img src="img/logo.png" />
</div>
<div class="pagina">
<?php
    $query = "select * from categorias where deletado != '1'";
    $result = mysqli_query($con,$query);
    while($d = mysqli_fetch_object($result)){
?>
    <button
            class="btn btn-success btn-lg btn-block"
            acao<?=$md5?>
            local="src/produtos/produtos.php?categoria=<?=$d->codigo?>"
            janela="ms_popup_100"
    >
        <?=$d->categoria?>
    </button>
<?php
    }
?>
</div>
<div class="rodape">
    <div class="row">
        <div class="col"><i class="fa-solid fa-circle-user"></i><p>Cliente</p></div>
        <div class="col"><i class="fa-solid fa-bell-concierge"></i><p>Pedido</p></div>
        <div class="col"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div>
    </div>
</div>


<script>
    $(function(){

        Carregando('none');

        $("button[acao<?=$md5?>]").off('click').on('click',function(){
            local = $(this).attr('local');
            janela = $(this).attr('janela');
            Carregando();
            $.ajax({
                url:"componentes/"+janela+".php",
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