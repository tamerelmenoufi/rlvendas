<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        $senha = md5($_POST['nome']);
        $query = "update clientes set senha = '{$senha}' where codigo = '{$_SESSION['AppCliente']}'";
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

                <div class="form-row">
                    <div class="form-group col">
                    <label for="Senha">Senha</label>
                    <input type="text" class="form-control form-control-lg" id="Senha">
                    </div>
                    <div class="form-group col">
                    <label for="ConfirmaSenha">Confirmar Senha</label>
                    <input type="text" class="form-control form-control-lg" id="ConfirmaSenha">
                    </div>
                </div>
                <button SalvarDados type="buttom" class="btn btn-primary btn-lg">Salvar dados</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("button[SalvarDados]").click(function(){
            senha  = $("#senha").val();
            senha1 = $("#senha1").val();

            if(!senha && senha != senha1){
                $.alert({
                    title:false,
                    content:"Preencha os campos corretamente para redefinir sua senha!",
                    type:"red"
                });
                return;
            }

            $.ajax({
                url:"src/cliente/senha.php",
                type:"POST",
                data:{
                    senha,
                    senha1,
                    acao:'salvar'
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    if(retorno.status){
                        $.alert({
                            content:'Senha atualizada com sucesso!',
                            title:false,
                            type: "green",
                        });
                        PageClose();
                    }
                }
            });

        });

    })
</script>