<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'trocar'){

        $query = "update vendas set mesa = '{$_POST['cod_mesa']}', alertas='Ocorreu alteração de mesa' where codigo = '{$_SESSION['AppVenda']}'";
        $result = mysqli_query($con, $query);

        $_SESSION['AppPedido'] = $_POST['cod_mesa'];
        echo json_encode([
            "status" => "sucesso",
            "AppPedido" => $_POST['cod_mesa']
        ]);

        exit();
    }

    $query = "select a.*, (select count(*) from vendas_produtos where venda = a.codigo and deletado != '1') as produtos from vendas a where a.situacao not in ('pago', 'pagar') and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $Ocupadas = [];
    while($d = mysqli_fetch_object($result)){
        $Ocupadas[] = $d->mesa;
    }




?>
<style>
    .ClienteTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:65px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
    .btn_mesa{
        width:100%;
        padding:10px;
        margin:5px;
        border:solid 1px #ccc;
        border-radius:5px;
        min-height:60px;
        font-size:30px;
        color:#333;
        text-align:center;
        background:#eee;
    }
    .ocupada{
        background:green;
        color:#fff;
    }
    .ComProdutos{
        background:blue;
        color:#fff;
    }
</style>

<div class="ClienteTopoTitulo">
    <h4>
        <i class="fa-solid fa-user"></i> Mesas Disponíveis
    </h4>
</div>

<div class="col">
    <div class="row">
        <?php

            $query = "select * from mesas where deletado != '1' and situacao != '0' ".(($Ocupadas)?" and codigo not in(".implode(", ", $Ocupadas).")":false)." order by mesa";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

        ?>
        <div class="col-4">
            <div acao="<?=$d->mesa?>" cod="<?=$d->codigo?>" class="btn_mesa"><?=str_pad($d->mesa , 3 , '0' , STR_PAD_LEFT)?></div>
        </div>
        <?php
            }
        ?>

    </div>

</div>

<script>
    $(function(){
        Carregando('none');
        $("div[acao]").click(function(){
            mesa = $(this).attr("acao");
            cod_mesa = $(this).attr("cod");
            Carregando();
            $.ajax({
                url:"src/mesas/trocar_mesa.php",
                type:"POST",
                data:{
                    mesa,
                    cod_mesa,
                    acao:'trocar'
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    // window.localStorage.setItem('AppCliente', retorno.AppCliente);
                    alert(retorno.AppPedido);
                    window.localStorage.setItem('AppPedido', retorno.AppPedido);
                    // window.localStorage.setItem('AppVenda', retorno.AppVenda);

                    // window.location.href="./";

                    $.ajax({
                        url: "src/home/index.php",
                        success: function (dados) {
                            PageClose(2);
                            $(".ms_corpo").html(dados);
                        }
                    });

                }
            });
        });

    })
</script>