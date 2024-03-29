<?php
    include("../../../lib/includes.php");

    if($_POST['cpf'] and $_POST['senha']){

        $query = "select * from atendentes where cpf = '{$_POST['cpf']}' and senha = '".md5($_POST['senha'])."' and situacao = '1' and deletado != '1'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result)){
            $d = mysqli_fetch_object($result);
            $_SESSION['AppGarcom'] = $d->codigo;
            $_SESSION['AppPerfil'] = json_decode($d->perfil);
            $status = 'sucesso';
            $q = "update atendentes set restart = '0' where codigo = '{$d->codigo}'";
            mysqli_query($con, $q);
            sisLog(
                [
                    'query' => $q,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $d->codigo
                ]
            );
        }else{
            $status = 'erro';
            $_SESSION['AppGarcom'] = false;
            $_SESSION['AppPerfil'] = false;
        }

        echo json_encode([
            "AppGarcom" => $_SESSION['AppGarcom'],
            "status" => $status,
            "query" => $query,
        ]);

        exit();
    }
?>

<div class="col">
    <!-- <div class="col-12">Cadastro/Acesso do Cliente</div> -->
    <h4 class="col-12 mb-4">Informe seus dados de acesso</h4>

    <div class="col-12 mb-3">
        <label for="cpf">Digite seu CPF</label>
        <input style="text-align:center" type="text" inputmode="numeric" autocomplete="off" class="form-control form-control-lg" id="cpf">
    </div>
    <div class="col-12 mb-3">
    <label for="senha">Informe sua senha</label>
        <input style="text-align:center" type="password" inputmode="numeric" autocomplete="off" class="form-control form-control-lg" id="senha">
    </div>
    <div class="col-12 mt-4">
        <button AcessoGarcom class="btn btn-primary btn-block btn-lg">Acesso do Garçom</button>
    </div>
</div>

<script>
    $(function(){

        $("#cpf").mask("999.999.999-99");

        if(terminal){
            $('#cpf').keyboard();
            $('#senha').keyboard();
        }

        $("button[AcessoGarcom]").click(function(){
            cpf = $("#cpf").val();
            senha = $("#senha").val();

            if(cpf && senha){
                $.ajax({
                    url:"src/garcom/login.php",
                    type:"POST",
                    data:{
                        cpf,
                        senha
                    },
                    success:function(dados){

                        let retorno = JSON.parse(dados);

                        if(retorno.status == 'sucesso'){
                            window.localStorage.setItem('AppGarcom', retorno.AppGarcom);
                            $.ajax({
                                url:"src/home/index.php",
                                success:function(dados){
                                    $(".ms_corpo").html(dados);
                                    PageClose();
                                }
                            });

                        }else{
                            $.alert('Dados incorretos, favor tente novamente!');
                        }


                    }
                });
            }else{
                //$.alert('Favor informe o número do seu telefone!');
                $.alert('Favor informe os seus dados de acesso!'); //REMOVER DEPOIS
            }


        });
    })
</script>