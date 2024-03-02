<?php
    include("../../lib/includes.php");

    VerificarVendaApp('delivery');
?>
<style>

    .rodape{
        position:fixed;
        width:100%;
        height:60px;
        background-color:#c0941f;
        left:0;
        bottom:0;
    }
    .rodape .row .col {
        color:#fff;
        text-align:center;
        font-size:30px;
        padding:0;
        margin:0;
    }
    .user{
        color:#fff;
        text-align:center;
        font-size:35px;
        padding:10px;
        margin:0px;
        margin-right:10px;
    }
    .rodape .row .col p{
        font-size:10px;
        text-align:center;
        color:#fff;
        padding:0;
        margin:0;
    }
    .user p{
        font-size:12px;
        text-align:left;
        color:#fff;
        padding-left:10px;
        padding-top:5px;
        margin:0;
    }

    .pagina{
        position:fixed;
        top:0px;
        bottom:60px;
        width:100%;
        overflow:auto;
        background:#fff;
        /* background-image:url('img/bg.png'); */
        background-size:cover;
        background-position:center;
    }
    .banner{
        height:auto;
        width:100%;
    }
    .banner img{
        height:auto;
        width:100%;
    }
</style>


<div class="pagina">

<div class="banner">
    <img src="img/banner.jpg" />
</div>

<div class="row" style="margin:0; padding:10px; padding-top:20px;">
    <?php
    $q = "select * from vendas where 
                                    app = 'delivery' and 
                                    cliente = '{$_SESSION['AppCliente']}' and 
                                    situacao = 'pago' and deletado != '1' and 
                                    (delivery->'$.situation' not in ('50', '70', '90', '200','30'))";
    $r = mysqli_query($con, $q);
    if(mysqli_num_rows($r)){
    ?>
    <div class="col-12">
        <div pedidos class="alert alert-danger" role="alert">
            <div class="d-flex justify-content-between">
                <i class="fa-solid fa-bag-shopping" style="font-size:70px; margin-right:10px;"></i>
                <div class="w-100">Você possui um pedido em andamento, <b>clique aqui</b> para acompanhar os detalhes.</div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>

    <div class="col-12">
        <button
                class="btn btn-primary btn-lg btn-block m-1"
                style="background-color:#990002; border:0;"
                acao<?=$md5?>
                local="src/produtos/busca.php"
                janela="ms_popup_100"
                categoria = '<?=$d->codigo?>'
                style="opacity:1"
        >
            <i class="fa-solid fa-magnifying-glass"></i> BUSCAR PRODUTO
        </button>
    </div>
</div>


<?php
    $query = "select * from categorias where deletado != '1' and codigo <= 7";
    $result = mysqli_query($con,$query);
    $i=0;
    while($d = mysqli_fetch_object($result)){

        if($i%3 == 0){

            if($i > 0 ) echo "</div>";
?>
    <div class="row" style="margin:0; padding:0; margin-bottom:10px;">
<?php
        }

?>

    <div class="col-4">
        <button
                class="btn btn-success btn-lg btn-block"
                style="background-color:#fff; height:100%; border:0; padding:5px;"
                acao<?=$md5?>
                local="src/produtos/produtos.php?categoria=<?=$d->codigo?>"
                janela="ms_popup_100"
                categoria = '<?=$d->codigo?>'
        >
            <div class="d-flex flex-column bd-highlight align-items-center">
                <img src="img/<?=substr($d->icone,0,-3)?>jpg" style="width:100%; border-radius:20px;">
                <span style="color:#c0941f; font-size:13px; font-weight:bold;"><?=$d->categoria?></span>
            </div>

        </button>
    </div>

<?php
$i++;
    }
    if($i%3 == 0) echo "</div>";
?>

</div>
<div class="rodape"></div>


<script>
    $(function(){

        Carregando('none');

        $.ajax({
            url:"src/produtos/banners.php",
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

            AppPedido = window.localStorage.getItem('AppPedido');
            AppCliente = window.localStorage.getItem('AppCliente');

            // alert('AppPedido:' + AppPedido)
            // alert('AppCliente:' + AppCliente)

            if(
                // (AppPedido != 'undefined' && AppPedido != null) &&
                (AppCliente != 'undefined' && AppCliente != null)
            ){
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
            }else if(!AppPedido || AppPedido === 'undefined' || AppPedido === null){
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:"componentes/camera.php",
                        // local:"src/cliente/cadastro.php",
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }
        })

        $("div[pedidos]").off('click').on('click',function(){
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/cliente/pedidos.php",
                    // local:"src/cliente/cadastro.php",
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });        
        });

    })

</script>