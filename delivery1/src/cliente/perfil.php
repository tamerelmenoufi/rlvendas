<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        $query = "update clientes set 
                                    nome = '{$_POST['nome']}', 
                                    cpf = '{$_POST['cpf']}', 
                                    email = '{$_POST['email']}',
                                    cep = '{$_POST['cep']}',
                                    logradouro = '{$_POST['logradouro']}',
                                    numero = '{$_POST['numero']}',
                                    complemento = '{$_POST['complemento']}',
                                    ponto_referencia = '{$_POST['ponto_referencia']}',
                                    bairro = '{$_POST['bairro']}',
                                    localidade = '{$_POST['localidade']}',
                                    uf = '{$_POST['uf']}',
                                    coordenadas = '{$_POST['coordenadas']}'
                                    
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
        padding-left:70px;
        left:0px;
        top:0px;
        right:0px;
        height:60px;
        background:#fff;
        padding-top:15px;
        z-index:1;
    }
    .form-group span{
        color:#a1a1a1;
        font-size:12px;
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
                    <label for="cpf">CPF*</label>
                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="cpf" placeholder="Informe seu CPF" value="<?=$c->cpf?>">
                </div>

                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="seuemail@seudominio.com" value="<?=$c->email?>">
                </div>

                <div class="form-group">
                    <label for="cep">CEP*</label>
                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="cep" value="<?=$c->cep?>">
                    <span>Procure informar o CEP correto para facilitar o preenchimento automático de alguns campos.</span>
                </div>
                <div class="form-group">
                    <label for="logradouro">Rua*</label>
                    <input type="text" class="form-control form-control-lg" id="logradouro" value="<?=$c->logradouro?>">
                    <span>Informe neste campo apenas o nome da avenida, rua ou beco de sua localização</span>
                </div>
                <div class="form-group">
                    <label for="numero">Número*</label>
                    <input type="text" class="form-control form-control-lg" id="numero" value="<?=$c->numero?>">
                    <span>Neste campo você precisa informar o número de sua casa, número do seu condomínio ou número do seu prédio</span>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input type="text" class="form-control form-control-lg" id="complemento" value="<?=$c->complemento?>">
                    <span>No complemento, informe se reside em um condomínio, informando o bloco, quadra, lote de sua moradia</span>
                </div>
                <div class="form-group">
                    <label for="ponto_referencia">Ponto de Referência*</label>
                    <input type="text" class="form-control form-control-lg" id="ponto_referencia" value="<?=$c->ponto_referencia?>">
                    <span>Informe aqui um ponto de referência conhecido nas proximidades de sua casa (ex: Igreja, posto de gasolina, escola, etc.)</span>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro*</label>
                    <input type="text" class="form-control form-control-lg" id="bairro" value="<?=$c->bairro?>">
                    <input type="hidden" id="localidade" value="Manaus" />
                    <input type="hidden" id="uf" value="AM" />
                    <input type="hidden" id="coordenadas" value="" />
                </div>

                <button SalvarDados type="buttom" class="btn btn-primary btn-lg">Salvar dados</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("#cpf").mask("999.999.999-99");
        $("#cep").mask("99999-999");

        $("#cep").blur(function(){
            cep = $(this).val();

            logradouro = $("#logradouro").val('');
            numero = $("#numero").val('');
            complemento = $("#complemento").val('');
            ponto_referencia = $("#ponto_referencia").val('');
            bairro = $("#bairro").val('');

            if(cep.length > 0 && (cep.length != 9 || cep.substring(0,2) != 69)){
                return false;
            }            

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

                console.log(data.status)

                if(data.status != 'OK'){
                    $.alert('CEP não localizado, favor confira e tente novamente!');
                    // $("#cep").val('');
                    // return
                }

                console.log(data);
                console.log(data.results[0].address_components);
                retorno = data.results[0].address_components;
                lat = data.results[0].geometry.location.lat;
                lng = data.results[0].geometry.location.lng;
                coordenadas = `${lat},${lng}`;
                console.log(coordenadas);
                $("#coordenadas").val(coordenadas);
                retorno.map((r) => {
                    r.types.map((tipo)=>{
                        // console.log(tipo)
                        if(tipo == 'route'){
                            //logradouro
                            console.log(r.long_name)
                            $("#logradouro").val(r.long_name);
                        }else if(tipo == 'sublocality_level_1'){
                            //bairro
                            console.log(r.long_name)
                            $("#bairro").val(r.long_name);
                        }
                        
                    })
                })
            })
            .catch(error => {
                console.error('Error:', error);
            });

        })


        $("button[SalvarDados]").click(function(){
            nome = $("#nome").val();
            cpf = $("#cpf").val();
            email = $("#email").val();

            cep = $("#cep").val();
            logradouro = $("#logradouro").val();
            numero = $("#numero").val();
            complemento = $("#complemento").val();
            ponto_referencia = $("#ponto_referencia").val();
            bairro = $("#bairro").val();
            localidade = $("#localidade").val();
            uf = $("#uf").val();
            coordenadas = $("#coordenadas").val();

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
                !cpf ||
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

            if(cep.length > 0 && (cep.length != 9 || cep.substring(0,2) != 69)){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
                return false;
            }

            if((cpf.length > 0 && cpf.length != 14) || !validarCPF(cpf)){
                $.alert({
                    title:"Erro",
                    content:"CPF incorreto ou inválido favor conferir os dados!",
                    type:"red"
                })
                return false;
            }

            $.ajax({
                url:"src/cliente/perfil.php",
                type:"POST",
                data:{
                    nome,
                    cpf,
                    email,
                    cep,
                    logradouro,
                    numero,
                    complemento,
                    ponto_referencia,
                    bairro,
                    localidade,
                    uf,
                    coordenadas,
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