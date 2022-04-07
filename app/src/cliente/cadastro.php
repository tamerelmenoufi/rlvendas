<?php
    include("../../../lib/includes.php");

    if($_POST['telefone']){

        $query = "select * from clientes where telefone = '{$_POST['telefone']}'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result)){
            $d = mysqli_fetch_object($result);
            $_SESSION['AppCliente'] = $d->codigo;
        }else{
            mysqli_query($con, "insert into clientes set telefone = '{$_POST['telefone']}'");
            $_SESSION['AppCliente'] = mysqli_insert_id($con);
        }

        if($_SESSION['AppCliente'] && $_SESSION['AppPedido']){
            /////////////////INCLUIR O REGISTRO DO PEDIDO//////////////////////
            $query = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' AND situacao = 'producao' LIMIT 1";
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
            "AppVenda" => $_SESSION['AppVenda']
        ]);

        exit();
    }
?>

<div class="col">
    <div class="col-12">Cadastro/Acesso do Cliente</div>
    <div class="col-12 mb-3">
        <input type="text" inputmode="numeric" autocomplete="off" class="form-control form-control-lg" id="ClienteTeleofne">
    </div>
    <div class="col-12">
        <button CadastrarCliente class="btn btn-primary btn-block btn-lg">Cadastrar/Acessar</button>
    </div>
</div>

<script>
    $(function(){

        $("#ClienteTeleofne").mask("(99) 99999-9999");

        $("button[CadastrarCliente]").click(function(){
            telefone = $("#ClienteTeleofne").val();
            if(telefone.length === 15){
                $.ajax({
                    url:"src/cliente/cadastro.php",
                    type:"POST",
                    data:{
                        telefone,
                    },
                    success:function(dados){

                        let retorno = JSON.parse(dados);

                        window.localStorage.setItem('AppCliente', retorno.AppCliente);
                        window.localStorage.setItem('AppVenda', retorno.AppVenda);

                        $.ajax({
                            url:"src/home/index.php",
                            success:function(dados){
                                $(".ms_corpo").html(dados);
                            }
                        });

                    }
                });
            }else{
                $.alert('Favor informe o número do seu telefone!');
            }


        });
    })
</script>