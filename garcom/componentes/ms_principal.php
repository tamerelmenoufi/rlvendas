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
        /* background:yellow; */
        background-image:url('img/bg.png');
        background-size:cover;
        background-position:center;
        padding:10px;
    }

</style>
<div class="topo"></div>
<div class="pagina">
    <div clss="col">
<div class="row">
    <div class="col-12">
        <button
                class="btn btn-primary btn-lg btn-block m-1"
                acao<?=$md5?>
                local="src/produtos/busca.php"
                janela="ms_popup_100"
                categoria = '<?=$d->codigo?>'
                style="opacity:1"
        >
            BUSCAR PRODUTO
        </button>
    </div>
</div>


<?php
    $query = "select * from categorias where deletado != '1'";
    $result = mysqli_query($con,$query);
    $i=0;
    while($d = mysqli_fetch_object($result)){

        if($i%2 == 0){

            if($i > 0 ) echo "</div>";
?>
    <div class="row">
<?php
        }

?>

    <div class="col-6">
        <button
                class="btn btn-success btn-lg btn-block m-1"
                acao<?=$md5?>
                local="src/produtos/produtos.php?categoria=<?=$d->codigo?>"
                janela="ms_popup_100"
                categoria = '<?=$d->codigo?>'
        >
            <?=$d->categoria?>
        </button>
    </div>

<?php
$i++;
    }
    if($i%2 == 0) echo "</div>";
?>
</div>

</div>
<div class="rodape"></div>


<script>
    $(function(){

        Carregando('none');

        $.ajax({
            url:"componentes/ms_topo.php",
            success:function(dados){
                $(".topo").html(dados);
            }
        });

        $.ajax({
            url:"componentes/ms_rodape.php",
            success:function(dados){
                $(".rodape").html(dados);
            }
        });


        $("button[acao<?=$md5?>]").off('click').on('click',function(){

            AppGarcom = window.localStorage.getItem('AppGarcom');
            AppPedido = window.localStorage.getItem('AppPedido');
            AppCliente = window.localStorage.getItem('AppCliente');



            if(
                (AppGarcom == 'undefined' || AppGarcom == null)
            ){
                Carregando();
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:"src/garcom/login.php",
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }else if(
                (AppPedido != 'undefined' && AppPedido != null) &&
                (AppCliente != 'undefined' && AppCliente != null)
            ){

                categoria = $(this).attr('categoria');

                if(categoria == '8'){
                    local = 'src/produtos/sorvete.php';
                }else if(categoria == '9'){
                    local = 'src/produtos/acompanhamentos.php';
                }else{
                    local = $(this).attr('local');
                }

                // local = ((categoria == '8')?'src/produtos/sorvete.php':$(this).attr('local'));
                // local = ((categoria == '9')?'src/produtos/acompanhamentos.php':$(this).attr('local'));
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
            }else if(!AppPedido || AppPedido === 'undefined' || AppPedido === null){
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        //local:"componentes/camera.php",
                        local:"src/mesas/home.php",
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }else if(!AppCliente || AppCliente === 'undefined' || AppCliente === null){
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:"src/cliente/cadastro.php",
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }
        })


    })

</script>