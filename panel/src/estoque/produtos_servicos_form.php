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
            $query = "update produtos_servicos set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into produtos_servicos set {$attr}";
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


    $query = "select * from produtos_servicos where codigo = '{$_POST['codigo']}'";
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
<h4 class="Titulo<?=$md5?>">Cadastro de Produto/Serviço</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome ou Razão Social" value="<?=$d->nome?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição"><?=$d->descricao?></textarea>
                    <label for="descricao">Descrição*</label>
                </div>
                <div class="form-floating mb-3">
                    <select  name="unidade" id="unidade" class="form-select">
                        <?php
                            $q = "select * from unidades_medida order by unidade";
                            $r = mysqli_query($conEstoque,$q);
                            while($u = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$u->codigo?>" <?=(($d->unidade == $u->codigo)?'selected':false)?>><?=$u->unidade?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="unidade">Unidade*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="valor" id="valor" class="form-control" placeholder="000.00" value="<?=$d->valor?>">
                    <label for="valor">Valor*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" readonly class="form-control" placeholder="000" value="<?=$d->quantidade?>">
                    <label for="quantidade">Quantidade</label>
                </div>
                <div class="form-floating mb-3">
                    <select  name="situacao" id="situacao" class="form-select">
                        <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                        <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
                    </select>
                    <label for="situacao">Situação*</label>
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


            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                Carregando();

                $.ajax({
                    url:"src/estoque/produtos_servicos_form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                    console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/estoque/produtos_servicos.php",
                                type:"POST",
                                data:{
                                    fornecedor:'<?=$_POST['fornecedor']?>'
                                },
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
                    url:"src/estoque/produtos_servicos.php",
                    type:"POST",
                    data:{
                        fornecedor:'<?=$_POST['fornecedor']?>'
                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })
            })

        })
    </script>