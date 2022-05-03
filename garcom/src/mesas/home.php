<?php
    include("../../../lib/includes.php");

    if($_POST['mesa']){

        $query = "select * from clientes where telefone = '{$_POST['mesa']}'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result)){
            $d = mysqli_fetch_object($result);
            $_SESSION['AppCliente'] = $d->codigo;
        }else{
            mysqli_query($con, "insert into clientes set telefone = '{$_POST['mesa']}'");
            $_SESSION['AppCliente'] = mysqli_insert_id($con);
        }

        ////////////REMOVER DEPOIS//////////////////////////////////
        $query = "select * from mesas where mesa = '{$_POST['mesa']}'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result)){
            $d = mysqli_fetch_object($result);
            $_SESSION['AppPedido'] = $d->codigo;
        }else{
            mysqli_query($con, "insert into mesas set mesa = '{$_POST['mesa']}'");
            $_SESSION['AppPedido'] = mysqli_insert_id($con);
        }
        ////////////REMOVER DEPOIS//////////////////////////////////




        if($_SESSION['AppCliente'] && $_SESSION['AppPedido']){
            /////////////////INCLUIR O REGISTRO DO PEDIDO//////////////////////
            $query = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' AND situacao in ('producao','preparo') LIMIT 1";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result)) {
                //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' LIMIT 1";
                list($codigo) = mysqli_fetch_row(mysqli_query($con, $query));
                $_SESSION['AppVenda'] = $codigo;
            } else {
                mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', data_pedido = NOW(), situacao = 'producao'");
                $_SESSION['AppVenda'] = mysqli_insert_id($con);
            }
            /////////////////////////////////////////////////////////////////
        }

        echo json_encode([
            "AppCliente" => $_SESSION['AppCliente'],
            "AppPedido" => $_SESSION['AppPedido'] //REMOVER DEPOIS
        ]);

        exit();
    }


    $query = "select * from vendas where situacao != 'pago' and deletado != '1'";
    $result = mysqli_query($con, $query);
    $Ocupadas = [];
    while($d = mysqli_fetch_object($result)){
        $Ocupadas[] = $d->codigo;
    }


    if($_POST['acao'] == 'Sair'){

        $query = "select * from vendas_produtos where venda = '{$_SESSION['AppVenda']}' and deletado != '1' and situacao = 'n'";
        $result = mysqli_query($con, $query);
        $n = mysqli_num_rows($result);

        if($n > 0 and !$_GET['confirm']){
            echo json_encode([
                "status" => "erro",
            ]);
        }else if($_GET['confirm']){
            $_SESSION = [];

        }else{
            echo json_encode([
                "status" => "sucesso",
            ]);
            $_SESSION = [];
        }
        exit();
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
    }
</style>

<div class="ClienteTopoTitulo">
    <h4>
        <i class="fa-solid fa-user"></i> Lista das Mesas
    </h4>
</div>

<div class="col">
    <div class="row">
        <?php

            $query = "select * from mesas where deletado != '1' and situacao != '0' order by mesa";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
        ?>
        <div class="col-4">
            <div acao="<?=$d->codigo?>" class="btn_mesa <?=((in_array($d->codigo, $Ocupadas))?'ocupada':false)?>"><?=$d->mesa?></div>
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
            Carregando();
            $.ajax({
                url:"src/mesas/home.php",
                type:"POST",
                data:{
                    mesa
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    window.localStorage.setItem('AppCliente', retorno.AppCliente);
                    window.localStorage.setItem('AppPedido', retorno.AppPedido);
                    window.location.href="./";
                }
            });
        });

    })
</script>