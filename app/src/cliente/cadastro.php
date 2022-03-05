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
        echo $_SESSION['AppCliente'];
        exit();
    }
?>

<div class="col">
    <div class="col-12">Cadastro de Cliente</div>
    <div class="col-12">
        <input type="text" class="form-control" id="ClienteTeleofne">
    </div>
    <div class="col-12">
        <button CadastrarCliente class="btn btn-primary btn-block">Cadastrar</button>
    </div>
</div>

<script>
    $(function(){

        $("#ClienteTeleofne").mask("99 9 9999-9999");

        $("button[CadastrarCliente]").click(function(){
            telefone = $("#ClienteTeleofne").val();
            if(telefone){
                $.ajax({
                    url:"src/cliente/cadastro.php",
                    type:"POST",
                    data:{
                        telefone,
                    },
                    success:function(dados){
                        window.localStorage.setItem('AppCliente', dados);

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