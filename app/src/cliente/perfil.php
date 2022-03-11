<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        $query = "update clientes set nome = '{$_POST['nome']}', email = '{$_POST['email']}' where codigo = '{$_SESSION['AppCliente']}'";
        mysqli_query($con, $query);

        echo json_encode([
            'status' => true,
            'msg' => 'Dados salvo com sucesso',
            'msg' => $_POST['nome'],
        ]);

        exit();
    }

    $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:70px;
        top:0px;
        height:60px;
        background:#fff;
        padding-top:15px;
        z-index:1;
    }

</style>
<div class="PedidoTopoTitulo">
    <h4>Perfil do Cliente</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">

                <div class="form-group">
                    <label for="nome">Telefone</label>
                    <div class="form-control form-control-lg" style="cursor:pointer; background-color:#ccc;"><?=$c->telefone?></div>
                </div>

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control form-control-lg" id="nome" placeholder="Seu Nome Completo" value="<?=$c->nome?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="seuemail@seudominio.com" value="<?=$c->email?>">
                </div>
                <button SalvarDados type="buttom" class="btn btn-primary btn-lg">Salvar dados</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("button[SalvarDados]").click(function(){
            nome = $("#nome").val();
            email = $("#email").val();

            if(!nome || !email){
                $.alert({
                            content:'Preencha os campos do formulário!',
                            title:false,
                            type: "red",
                        });
                return false;
            }

            $.ajax({
                url:"src/cliente/perfil.php",
                type:"POST",
                data:{
                    nome,
                    email,
                    acao:'salvar'
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    //$.alert(retorno.status);
                    if(retorno.status){
                        $.alert({
                            content:'Dados salvos com sucesso!',
                            title:false,
                            type: "green",
                        });
                        $("span[ClienteNomeApp]").html(retorno.msg);
                        PageClose();
                    }
                }
            });

        });

    })
</script>