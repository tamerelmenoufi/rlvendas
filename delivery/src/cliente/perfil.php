<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        $query = "update clientes set 
                                    nome = '{$_POST['nome']}', 
                                    email = '{$_POST['email']}',
                                    cep = '{$_POST['cep']}',
                                    logradouro = '{$_POST['logradouro']}',
                                    numero = '{$_POST['numero']}',
                                    complemento = '{$_POST['complemento']}',
                                    ponto_referencia = '{$_POST['ponto_referencia']}',
                                    bairro = '{$_POST['bairro']}',
                                    localidade = '{$_POST['localidade']}',
                                    uf = '{$_POST['uf']}'
                                    
                where codigo = '{$_SESSION['AppCliente']}'";
        mysqli_query($con, $query);
        sisLog(
            [
                'query' => $query,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppCliente']
            ]
        );

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
                    <label for="nome">Nome Completo*</label>
                    <input type="text" class="form-control form-control-lg" id="nome" placeholder="Seu Nome Completo" value="<?=$c->nome?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="seuemail@seudominio.com" value="<?=$c->email?>">
                </div>

                <div class="form-group">
                    <label for="cep">CEP*</label>
                    <input type="text" class="form-control form-control-lg" id="cep" value="<?=$c->cep?>">
                </div>
                <div class="form-group">
                    <label for="logradouro">Logradouro*</label>
                    <input type="text" class="form-control form-control-lg" id="logradouro" value="<?=$c->logradouro?>">
                </div>
                <div class="form-group">
                    <label for="numero">Número*</label>
                    <input type="text" class="form-control form-control-lg" id="numero" value="<?=$c->numero?>">
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input type="text" class="form-control form-control-lg" id="complemento" value="<?=$c->complemento?>">
                </div>
                <div class="form-group">
                    <label for="ponto_referencia">Ponto de Referência*</label>
                    <input type="text" class="form-control form-control-lg" id="ponto_referencia" value="<?=$c->ponto_referencia?>">
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro*</label>
                    <input type="text" class="form-control form-control-lg" id="bairro" value="<?=$c->bairro?>">
                    <input type="hidden" id="localidade" value="Manaus" />
                    <input type="hidden" id="uf" value="AM" />
                </div>

                <button SalvarDados type="buttom" class="btn btn-primary btn-lg">Salvar dados</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){


        $("#cep").blur(function(){
            cep = $(this).val();
            const apiUrl = `https://maps.google.com/maps/api/geocode/json?address=${cep}&key=AIzaSyBSnblPMOwEdteX5UPYXf7XUtJYcbypx6w`;
            // Make a GET request
            fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                console.log(data.results[0].address_components);
                data.results[0].address_components.foreach(function(d){
                    console.log(d.types)
                })
            })
            .catch(error => {
                console.error('Error:', error);
            });

        })


        $("button[SalvarDados]").click(function(){
            nome = $("#nome").val();
            email = $("#email").val();

            cep = $("#cep").val();
            logradouro = $("#logradouro").val();
            numero = $("#numero").val();
            complemento = $("#complemento").val();
            ponto_referencia = $("#ponto_referencia").val();
            bairro = $("#bairro").val();
            localidade = $("#localidade").val();
            uf = $("#uf").val();

            // dados = [];
            // dados.push(nome)
            // dados.push(email)
            // dados.push(cep)
            // dados.push(logradouro)
            // dados.push(numero)
            // dados.push(complemento)
            // dados.push(ponto_referencia)
            // dados.push(bairro)
            // dados.push(localidade)
            // dados.push(uf)

            // console.log(dados)

            // Define the API URL







            if(
                !nome ||
                !email ||
                !cep ||
                !logradouro ||
                !numero ||
                !ponto_referencia ||
                !bairro ||
                !localidade ||
                !uf
            ){
                $.alert({
                    content:'Preencha os campos obrigatórios(*) do formulário!',
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
                    cep,
                    logradouro,
                    numero,
                    complemento,
                    ponto_referencia,
                    bairro,
                    localidade,
                    uf,
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