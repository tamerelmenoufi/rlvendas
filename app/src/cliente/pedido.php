<?php
    include("../../../lib/includes.php");
    if($_POST['SairPedido']){
        $_SESSION = [];
        exit();
    }
?>

<div class="col">
    <div class="col-12">Pedido <?=$_SESSION['AppMesa']?></div>
    <div class="col-12">
        <button SairPedido class="btn btn-danger btn-block">SAIR</button>
    </div>
</div>

<script>
    $(function(){
        $("button[SairPedido]").click(function(){

            $.ajax({
                url:"src/cliente/pedido.php",
                type:"POST",
                data:{
                    SairPedido:'1',
                },
                success:function(dados){
                    window.localStorage.removeItem('AppMesa');
                    window.localStorage.removeItem('AppCliente');

                    $.ajax({
                        url:"src/home/index.php",
                        success:function(dados){
                            $(".ms_corpo").html(dados);
                        }
                    });

                }
            });

        });
    })
</script>