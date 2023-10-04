<?php
    include("../../../lib/includes.php");



    if($_POST['mesa']){

        $_SESSION['AppVenda'] = false;
        $_SESSION['AppCliente'] = false;
        $_SESSION['AppPedido'] = false;


        $query = "SELECT codigo, cliente, mesa FROM vendas WHERE mesa = '{$_POST['cod_mesa']}' AND deletado != '1' AND situacao in ('producao','preparo') LIMIT 1";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result)) {
            //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' LIMIT 1";
            list($codigo, $cliente, $mesa) = mysqli_fetch_row(mysqli_query($con, $query));
            $_SESSION['AppVenda'] = $codigo;
            $_SESSION['AppCliente'] = $cliente;
            $_SESSION['AppPedido'] = $mesa;

        } else {

            $query = "select * from clientes where telefone = '{$_POST['mesa']}'";
            $result = mysqli_query($con, $query);
            if(mysqli_num_rows($result)){
                $d = mysqli_fetch_object($result);
                $_SESSION['AppCliente'] = $d->codigo;
            }else{
                $q = "insert into clientes set telefone = '{$_POST['mesa']}'";
                mysqli_query($con, $q);
                $_SESSION['AppCliente'] = mysqli_insert_id($con);
                sisLog(
                    [
                        'query' => $q,
                        'file' => $_SERVER["PHP_SELF"],
                        'sessao' => $_SESSION,
                        'registro' => $_SESSION['AppCliente']
                    ]
                );
            }

            ////////////REMOVER DEPOIS//////////////////////////////////
            $query = "select * from mesas where mesa = '{$_POST['mesa']}'";
            $result = mysqli_query($con, $query);
            if(mysqli_num_rows($result)){
                $d = mysqli_fetch_object($result);
                $_SESSION['AppPedido'] = $d->codigo;
            }else{
                $q = "insert into mesas set mesa = '{$_POST['mesa']}'";
                mysqli_query($con, $q);
                $_SESSION['AppPedido'] = mysqli_insert_id($con);
                sisLog(
                    [
                        'query' => $q,
                        'file' => $_SERVER["PHP_SELF"],
                        'sessao' => $_SESSION,
                        'registro' => $_SESSION['AppPedido']
                    ]
                );
            }
            ////////////REMOVER DEPOIS//////////////////////////////////

        }



        // if($_SESSION['AppCliente'] && $_SESSION['AppPedido'] && !$_SESSION['AppVenda']){
        //     /////////////////INCLUIR O REGISTRO DO PEDIDO//////////////////////
        //     $query = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' AND situacao in ('producao','preparo') LIMIT 1";
        //     $result = mysqli_query($con, $query);

        //     if (mysqli_num_rows($result)) {
        //         //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' LIMIT 1";
        //         list($codigo) = mysqli_fetch_row(mysqli_query($con, $query));
        //         $_SESSION['AppVenda'] = $codigo;
        //     } else {
        //         mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', atendente = '{$_SESSION['AppGarcom']}', data_pedido = NOW(), situacao = 'producao'");
        //         $_SESSION['AppVenda'] = mysqli_insert_id($con);
        //     }
        //     /////////////////////////////////////////////////////////////////
        // }

        echo json_encode([
            "AppCliente" => $_SESSION['AppCliente'],
            "AppPedido" => $_SESSION['AppPedido'], //REMOVER DEPOIS
            "AppVenda" => (($_SESSION['AppVenda'])?:false) //REMOVER DEPOIS
        ]);

        exit();
    }


    // VerificarVendaApp();



    $query = "select a.*, (select count(*) from vendas_produtos where venda = a.codigo and deletado != '1') as produtos from vendas a where a.situacao not in ('pago') and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $Ocupadas = [];
    while($d = mysqli_fetch_object($result)){
        $Ocupadas[] = $d->mesa;
        $Produtos[$d->mesa] = $d->produtos;
        $App[$d->mesa] = $d->app;

        if($d->situacao == 'pagar'){
            $Pagar[$d->mesa] = true;
        }else{
            $Pagar[$d->mesa] = false;
        }
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
        /* -removido_identificacao_ocupado_na_mesa */
        background:green;
        color:#fff;
    }
    .ocupadaApp{
        /* -removido_identificacao_ocupado_na_mesa */
        background:brown;
        color:#fff;
    }
    .ComProdutos{
        background:blue;
        color:#fff;
    }
    .Pagar{
        background:orange;
        color:#fff;
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

            $q = "UPDATE mesas set blq = '0'";
            mysqli_query($con, $q);
            sisLog(
                [
                    'query' => $q,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => 
                ]
            );
            $query = "select * from mesas where deletado != '1' and situacao != '0' /*and mesa <= 199*/ order by mesa";
            $result = mysqli_query($con, $query);
            $BlqLista = [];
            while($d = mysqli_fetch_object($result)){

                $blq = false;
                if( $Pagar[$d->codigo]){
                    $blq = true;
                    $icone = 'Pagar';
                    $BlqLista[] = $d->codigo;
                }else if( $Produtos[$d->codigo]){
                    $icone = 'ComProdutos';
                    $BlqLista[] = $d->codigo;
                }else if(in_array($d->codigo, $Ocupadas) and $App[$d->codigo] != 'mesa'){
                    $icone = 'ocupada';
                    $BlqLista[] = $d->codigo;
                }else if(in_array($d->codigo, $Ocupadas) and $App[$d->codigo] == 'mesa'){
                    $icone = 'ocupadaApp';
                    $BlqLista[] = $d->codigo;
                }else{
                    $icone = false;
                }

        ?>
        <div class="col-4">
            <div <?=(($blq)?false:' acao="'.$d->mesa.'" ')?> cod="<?=$d->codigo?>" class="btn_mesa <?=$icone?>"><?=str_pad($d->mesa , 3 , '0' , STR_PAD_LEFT)?></div>
        </div>
        <?php
            }
            if($BlqLista){
                mysqli_query($con, "UPDATE mesas set blq = '1' WHERE codigo IN (".implode(",",$BlqLista).")");
            }
        ?>

    </div>

    <!-- <div class="row">
        <?php
        /*

            $query = "select * from mesas where deletado != '1' and situacao != '0' order by mesa";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

                $blq = false;
                if( $Pagar[$d->codigo]){
                    $blq = true;
                    $icone = 'Pagar';
                }else if( $Produtos[$d->codigo]){
                    $icone = 'ComProdutos';
                }else if(in_array($d->codigo, $Ocupadas)){
                    $icone = 'ocupada';
                }else{
                    $icone = false;
                }

        ?>
        <div class="col-4">
            <div <?=(($blq)?false:' acao="'.$d->mesa.'" ')?> cod="<?=$d->codigo?>" class="btn_mesa <?=$icone?>"><?=str_pad($d->mesa , 3 , '0' , STR_PAD_LEFT)?></div>
        </div>
        <?php
            }
            //*/
        ?>

    </div> -->

</div>

<script>
    $(function(){
        Carregando('none');
        $("div[acao]").click(function(){
            mesa = $(this).attr("acao");
            cod_mesa = $(this).attr("cod");
            Carregando();
            $.ajax({
                url:"src/mesas/home.php",
                type:"POST",
                data:{
                    mesa,
                    cod_mesa
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    window.localStorage.setItem('AppCliente', retorno.AppCliente);
                    window.localStorage.setItem('AppPedido', retorno.AppPedido);
                    if(retorno.AppVenda){
                        window.localStorage.setItem('AppVenda', retorno.AppVenda);
                    }else{
                        window.localStorage.removeItem('AppVenda');
                    }
                    
                    

                    // window.location.href="./";

                    $.ajax({
                        url: "src/home/index.php",
                        success: function (dados) {
                            PageClose();
                            $(".ms_corpo").html(dados);
                        }
                    });

                }
            });
        });

    })
</script>