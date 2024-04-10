<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);
        unset($data['senha']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        if($_POST['senha']){
            $attr[] = "senha = '" . md5($_POST['senha']) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update atendentes set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into atendentes set data_cadastro = NOW(), {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'situacao' => true,
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from atendentes where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);

    $GetPerfis = json_decode($d->perfil);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Cadastro do Usuário</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$d->nome?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="<?=$d->cpf?>">
                    <label for="cpf">CPF*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" value="<?=$d->telefone?>">
                    <label for="telefone">Telefone</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$d->email?>">
                    <label for="email">E-mail</label>
                </div>

                <div class="row mt-2 mb-3">
                <div class="col-md-12">
                    <h5>Perfil de Acesso</h5>
                    <?php
                    $perfis = [
                        ['name' => 'ExcluirProduto', 'value' => 'Excluir Produto em produção'],
                    ];
                    foreach($perfis as $indice => $valor){
                    ?>
                    <div class="form-group">
                        <div class="form-check">
                        <input
                                perfil
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="<?=$valor['name']?>"
                                <?=(($GetPerfis[$indice]->value)?'checked':false)?>
                        >
                        <label class="form-check-label" for="<?=$valor['name']?>">
                            <?=$valor['value']?>
                        </label>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

                <div class="form-floating mb-3">
                    <input type="text" name="senha" id="senha" class="form-control" placeholder="E-mail" value="">
                    <label for="senha">Senha</label>
                </div>
                <?php
                if($d->codigo != 1){
                ?>
                <div class="form-floating mb-3">
                    <select name="situacao" class="form-control" id="situacao">
                        <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
                        <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                    </select>
                    <label for="email">Situação</label>
                </div>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("#cpf").mask("999.999.999-99");
            $("#telefone").mask("(99) 9 9999-9999");


            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                cpf = $("#cpf").val();
                if(cpf){
                    if(!validarCPF(cpf)){
                        $.alert('Confira o CPF, o informado é inválido!');
                        return;
                    }
                }

                perfil = [];
                $("input[perfil]").each(function(){
                    perfil.push({name: $(this).attr("id"), value: $(this).prop("checked")});
                });

                if(perfil){
                    campos.push({name: 'perfil', value: JSON.stringify(perfil)});
                }

                Carregando();

                $.ajax({
                    url:"src/atendentes/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                    //console.log(dados)
                        // if(dados.situacao){
                            $.ajax({
                                url:"src/atendentes/index.php",
                                type:"POST",
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                    let myOffCanvas = document.getElementById('offcanvasDireita');
                                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                                    openedCanvas.hide();
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

        })
    </script>