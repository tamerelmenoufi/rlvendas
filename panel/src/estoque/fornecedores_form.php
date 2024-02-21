<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update fornecedores set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into fornecedores set data_cadastro = NOW(), {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $query
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from fornecedores where codigo = '{$_POST['codigo']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Cadastro de Fornecedor</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome_razao_social" name="nome_razao_social" placeholder="Nome ou Razão Social" value="<?=$d->nome_razao_social?>">
                    <label for="nome_razao_social">Nome / Razão Social*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="apelido_fantasia" name="apelido_fantasia" placeholder="Apelido ou Nome Fantasia" value="<?=$d->apelido_fantasia?>">
                    <label for="apelido_fantasia">Apelido / Nome Fantazia*</label>
                </div>
                <div class="form-floating mb-3">
                    <select  <?=(($d->codigo)?'disabled':'name="tipo_documento" id="tipo_documento"')?> class="form-select">
                        <option value="cnpj" <?=(($d->tipo_documento == 'cnpj')?'selected':false)?>>CNPJ</option>
                        <option value="cpf" <?=(($d->tipo_documento == 'cpf')?'selected':false)?>>CPF</option>
                    </select>
                    <label for="cpf_cnpj">CPF/CNPJ*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" <?=(($d->codigo)?'readonly':'name="cpf_cnpj" id="cpf_cnpj"')?> class="form-control" placeholder="CPF/CNPJ" value="<?=$d->cpf_cnpj?>">
                    <label for="cpf_cnpj">CPF/CNPJ*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="email" id="email" class="form-control" placeholder="Informe o E-mail" value="<?=$d->email?>">
                    <label for="email">E-mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" value="<?=$d->telefone?>">
                    <label for="telefone">Telefone</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="celular" id="celular" class="form-control" placeholder="Telefone Móvel / WhatsApp" value="<?=$d->celular?>">
                    <label for="celular">Celular/WhatsApp</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Endereço Completo" value="<?=$d->endereco?>">
                    <label for="endereco">Endereço</label>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button voltar<?=$md5?> type="button" class="btn btn-warning btn-ms me-2">Voltar</button>
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['codigo']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("#cpf_cnpj").mask("<?=(($d->tipo_documento == 'cpf')?'999.999.999-99':'99.999.999/9999-99')?>");

            $("#telefone").mask("(99) 9999-9999");
            $("#celular").mask("(99) 99999-9999");

            $("#tipo_documento").change(function(){
                tipo = $(this).val();
                $("#cpf_cnpj").val('');
                if(tipo == 'cpf'){
                    $("#cpf_cnpj").mask("999.999.999-99");
                }else{
                    $("#cpf_cnpj").mask("99.999.999/9999-99");
                }
            })

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                cpf_cnpj = $("#cpf_cnpj").val();
                tipo_documento = $("#tipo_documento").val();
                if(cpf_cnpj && tipo_documento == 'cpf'){
                    if(!validarCPF(cpf_cnpj)){
                        $.alert('Confira o CPF, o informado é inválido!');
                        return;
                    }
                }
                if(cpf_cnpj && tipo_documento == 'cnpj'){
                    if(!validarCNPJ(cpf_cnpj)){
                        $.alert('Confira o CNPJ, o informado é inválido!');
                        return;
                    }
                }

                Carregando();

                $.ajax({
                    url:"src/estoque/fornecedores_form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                    console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/estoque/fornecedores.php",
                                type:"POST",
                                success:function(dados){
                                    $(".LateralDireita").html(dados);
                                }
                            });
                        // }
                    },
                    error:function(erro){

                        // $.alert('Ocorreu um erro!' + erro.toString());
                        //dados de teste
                    }
                });

            });


            $("button[voltar<?=$md5?>]").click(function(){
                $.ajax({
                    url:"src/estoque/fornecedores.php",
                    type:"POST",
                    data:{

                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })
            })

        })
    </script>