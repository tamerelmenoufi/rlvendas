<?php
    include("../../lib/includes.php");

    VerificarVendaApp('delivery');
?>
<style>
    .topo{
        position:fixed;
        width:100%;
        height:60px;
        background-color:#990002;
        left:0;
        top:0;
    }

    .rodape{
        position:fixed;
        width:100%;
        height:110px;
        background-color:#990002;
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
        bottom:110px;
        width:100%;
        overflow:auto;
        /* background:yellow; */
        background-image:url('img/bg.png');
        background-size:cover;
        background-position:center;
        padding:10px;
        padding-top:20px;
    }

</style>
<div class="topo"></div>
<div class="pagina">
<div class="row" style="margin:0; padding:0;">
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

        if($d->horas_bloqueio){
            list($h1,$h2) = explode(",",$d->horas_bloqueio);
            list($H1, $m1) = explode(":",$h1);
            list($H2, $m2) = explode(":",$h2);
            $hora1 = mktime($H1,$m1,0,date("m"),date("d"),date("Y"));
            $hora2 = mktime($H2,$m2,0,date("m"),date("d"),date("Y"));
            $agora = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        }

        if(date("N") != $d->dias_bloqueio and ($hora1 <= $agora and $agora <= $hora2)){

        if($i%2 == 0){

            if($i > 0 ) echo "</div>";
?>
    <div class="row" style="margin:0; padding:0; margin-bottom:10px;">
<?php
        }

?>

    <div class="col-6">
        <button
                class="btn btn-success btn-lg btn-block m-1"
                style="background-color:#ed8d22; height:100%; border:0;"
                acao<?=$md5?>
                local="src/produtos/produtos.php?categoria=<?=$d->codigo?>"
                janela="ms_popup_100"
                categoria = '<?=$d->codigo?>'
        >
            <div class="d-flex justify-content-between align-items-center">
                <img src="img/<?=$d->icone?>" style="height:50px;">
                <span><?=$d->categoria. $d->dias_bloqueio?></span>
            </div>

        </button>
    </div>

<?php
$i++;
    }
    }
    if($i%2 == 0) echo "</div>";
?>

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