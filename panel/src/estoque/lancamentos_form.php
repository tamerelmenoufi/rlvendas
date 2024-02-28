<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['cod'];
    }

    if($_POST['acao'] == 'adicionar_fornecedor'){
        $query = "update lancamentos set fornecedor = '{$_POST['codigo']}', usuario = '{$_SESSION['appLogin']->codigo}', data_atualizacao = NOW() where codigo = '{$_SESSION['cod_lancamento']}'";
        $result = sisLog($query);
    }

    if($_POST['acao'] == 'adicionar_produto'){
        $query = "insert into movimentacao set 
                            lancamento = '{$_SESSION['cod_lancamento']}',
                            fornecedor = '{$_POST['fornecedor']}', 
                            produto = '{$_POST['codigo']}',
                            usuario = '{$_SESSION['appLogin']->codigo}',
                            data = NOW()
                            ";
        $result = sisLog($query);
    }

    if($_POST['acao'] == 'excluir_produto_servico'){
        $query = "delete from movimentacao where codigo = '{$_POST['item']}'";
        $result = sisLog($query);

        $query = "UPDATE lancamentos set valor = (select sum(valor_total) from movimentacao where lancamento = '{$_SESSION['cod_lancamento']}'), usuario = '{$_SESSION['appLogin']->codigo}', data_atualizacao = NOW() where codigo = '{$_SESSION['cod_lancamento']}'";
        $result = sisLog($query);
    }    

    if($_POST['acao'] == 'update_lancamento'){

        if($_POST['campo'] == 'data'){
            $valor = dataMysql($_POST['valor']);
        }else{
            $valor = $_POST['valor'];
        }

        $query = "update lancamentos set {$_POST['campo']} = '{$valor}', usuario = '{$_SESSION['appLogin']->codigo}', data_atualizacao = NOW() where codigo = '{$_SESSION['cod_lancamento']}'";
        $result = sisLog($query);

        exit();
    }   

    if($_POST['acao'] == 'update_movimentacao'){

        $query = "update movimentacao set 
                                        {$_POST['campo']} = '{$_POST['valor']}', 
                                        valor_total = '{$_POST['total']}',
                                        usuario = '{$_SESSION['appLogin']->codigo}',
                                        data = NOW()
                where codigo = '{$_POST['movimentacao']}'";
        $result = sisLog($query);

        $query = "update lancamentos set 
                                        valor = '{$_POST['total_geral']}',
                                        usuario = '{$_SESSION['appLogin']->codigo}',
                                        data_atualizacao = NOW()                                        
                where codigo = '{$_SESSION['cod_lancamento']}'";
        $result = sisLog($query);        

        exit();
    }   

    $query = "select a.*, b.nome_razao_social from lancamentos a left join fornecedores b on a.fornecedor = b.codigo where a.codigo = '{$_SESSION['cod_lancamento']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);
?>
<style>
  .btn-perfil{
    padding:5px;
    border-radius:8px;
    color:#fff;
    background-color:#a1a1a1;
    cursor: pointer;
  }
  td, th{
    white-space: nowrap;
  }
  .label{
    font-size:10px;
    color:#a1a1a1;
  }
</style>
<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
            <h5 class="card-header">
                <div class="alert alert-success salvando" role="alert" style="position:absolute; right:80px; top:15px; padding:1px; font-size:14px; opacity:0;">
                    <i class="fa-solid fa-gear fa-spin"></i> Salvando 
                </div>
                <div class="d-flex justify-content-between">
                    <span>Lançamento <?=$d->numero?></span>
                    <button class="btn btn-warning btn-sm voltar">Volta</button>
                </div>
            </h5>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="fornecedor" class="form-label">Fornecedor</label>
                            <div class="input-group mb-3">
                                <div class="form-control"><?=$d->nome_razao_social?></div>
                                <button 
                                    class="btn btn-outline-secondary" 
                                    type="button" 
                                    id="busca-fornecedor"
                                    lancamento="<?=$_SESSION['cod_lancamento']?>"
                                    data-bs-toggle="offcanvas"
                                    href="#offcanvasDireita"
                                    role="button"
                                    aria-controls="offcanvasDireita"                                        
                                ><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="data" class="form-label">Data</label>
                            <div class="input-group mb-3">
                                <input lancamento="<?=$d->codigo?>" type="text" campo="data" inputmode="numeric" class="form-control datas" placeholder="00/00/0000" value="<?=dataBr($d->data)?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group mb-3">
                                <input readonly lancamento="<?=$d->codigo?>" type="text" campo="valor" inputmode="numeric" class="form-control" placeholder="000.00" value="<?=$d->valor?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="mb-3">
                            <label for="anexo" class="form-label">Anexo</label>
                            <div class="input-group mb-3">
                                <button
                                    class="btn btn-outline-secondary w-100"
                                    type="button"
                                >
                                    <input type="file" style="position:absolute; left:0; right:0; bottom:0; top:0; cursor:pointer; z-index:1; opacity:0" />
                                    <i class="fa-solid fa-paperclip"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 d-flex justify-content-between mt-3 mb-2">
                    <h5>Produtos/Serviços</h5>
                    <?php
                    if($d->fornecedor){
                    ?>
                    <button 
                        class="btn btn-outline-secondary" 
                        type="button" 
                        id="busca-produtos"
                        lancamento="<?=$_SESSION['cod_lancamento']?>"
                        fornecedor="<?=$d->fornecedor?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"       
                    ><i class="fa-solid fa-plus"></i></button>
                    <?php
                    }
                    ?>
                </div>

                <?php
                $q = "select 
                            a.*,
                            b.nome as produto_nome,
                            c.unidade as unidade_sigla,
                            c.descricao as unidade_descricao
                        from movimentacao a
                            left join produtos_servicos b on a.produto = b.codigo
                            left join unidades_medida c on b.unidade = c.codigo
                        where a.lancamento = '{$d->codigo}' order by a.codigo asc";
                $r = mysqli_query($conEstoque, $q);
                $i = 0;
                while($p = mysqli_fetch_object($r)){
                ?>
                
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="nome" class="form-label d-none d-md-block">Nome</label>':false)?>
                            <div class="input-group mb-3">
                                <span delItem="<?=$p->codigo?>" class="input-group-text text-danger" style="cursor:pointer"><i class="fa-solid fa-trash-can"></i></span>
                                <div class="form-control"><?=$p->produto_nome?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="unidade" class="form-label d-none d-md-block">Uni.</label>':false)?>
                            <div class="input-group mb-3">
                                <div class="form-control"><?="{$p->unidade_sigla} ({$p->unidade_descricao})"?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="valor_unitario" class="form-label d-none d-md-block">Valor</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" movimentacao="<?=$p->codigo?>" campo="valor_unitario" class="form-control" placeholder="000.00" value="<?=$p->valor_unitario?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="quantidade" class="form-label d-none d-md-block">Quant.</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" movimentacao="<?=$p->codigo?>" campo="quantidade" class="form-control" placeholder="000" value="<?=$p->quantidade?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="valor_total" class="form-label d-none d-md-block">Total</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" readonly movimentacao="<?=$p->codigo?>" campo="valor_total" class="form-control" placeholder="000" value="<?=$p->valor_total?>">
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                $i++;
                }
                ?>


            </div>
        </div>
      </div>
    </div>

  </div>
</div>


<script>
    $(function(){
        Carregando('none');

        $(".datas").mask("99/99/9999");

        $(".voltar").click(function(){
          $.ajax({
              url:"src/estoque/lancamentos.php",
              type:"POST",
              data:{

              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })


        $("#busca-fornecedor").click(function(){
            lancamento = $(this).attr("lancamento");
            $.ajax({
                url:"src/estoque/fornecedores.php",
                type:"POST",
                data:{
                    lancamento,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("#busca-produtos").click(function(){
            lancamento = $(this).attr("lancamento");
            fornecedor = $(this).attr("fornecedor");
            $.ajax({
                url:"src/estoque/produtos_servicos.php",
                type:"POST",
                data:{
                    lancamento,
                    fornecedor,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("span[delItem]").click(function(){
            codigo = $(this).attr("delItem");
            $.confirm({
                content:"Deseja realmente excluir o Item da lista de lançamentos?",
                type:"red",
                title:"Aviso de Exclusão",
                buttons:{
                    'sim':{
                        text:"Sim",
                        btnClass:"btn btn-danger",
                        action:function(){
                            $.ajax({
                                url:"src/estoque/lancamentos_form.php",
                                type:"POST",
                                data:{
                                    item:codigo,
                                    cod_lancamento:'<?=$_SESSION['cod_lancamento']?>',
                                    acao:'excluir_produto_servico'
                                },
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                }
                            })
                        }
                    },
                    'nao':{
                        text:"Não",
                        btnClass:"btn btn-success",
                        action:function(){

                        }
                    },
                }
            })
        })

        $("input[lancamento]").blur(function(){
            campo = $(this).attr("campo");
            cod_lancamento = $(this).attr("lancamento");
            valor = $(this).val();
            // if(!valor) return false;
            $(".salvando").css("opacity","1");
            $.ajax({
                url:"src/estoque/lancamentos_form.php",
                type:"POST",
                data:{
                    campo,
                    valor,
                    cod_lancamento,
                    acao:'update_lancamento'
                },
                success:function(dados){
                    //$("#paginaHome").html(dados);
                    setTimeout(() => {
                        $(".salvando").css("opacity","0");
                    }, 2000);
                }
            })

        })

        $("input[movimentacao]").blur(function(){
            campo = $(this).attr("campo");
            cod_lancamento = '<?=$_SESSION['cod_lancamento']?>';
            movimentacao = $(this).attr("movimentacao");
            valor = $(this).val();
            // if(!valor) return false;

            total = $(`input[movimentacao="${movimentacao}"][campo="valor_total"]`).val();
            total_geral = $(`input[lancamento="${cod_lancamento}"][campo="valor"]`).val();

            $(".salvando").css("opacity","1");
            $.ajax({
                url:"src/estoque/lancamentos_form.php",
                type:"POST",
                data:{
                    campo,
                    valor,
                    total,
                    total_geral,
                    cod_lancamento,
                    movimentacao,
                    acao:'update_movimentacao'
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                    setTimeout(() => {
                        $(".salvando").css("opacity","0");
                    }, 2000);
                }
            })

        })


        $(`input[movimentacao][campo="quantidade"]`).keyup(function(){
            quantidade = $(this).val();
            cod = $(this).attr("movimentacao");
            valor = $(`input[movimentacao="${cod}"][campo="valor_unitario"]`).val();
            total = (quantidade * valor);
            $(`input[movimentacao="${cod}"][campo="valor_total"]`).val(total.toFixed(2));

            tot = 0;
            $(`input[movimentacao][campo="valor_total"]`).each(function(){
                tot = (tot*1 + ($(this).val())*1);
            })
            $(`input[lancamento="<?=$_SESSION['cod_lancamento']?>"][campo="valor"]`).val(tot.toFixed(2));

        })

        $(`input[movimentacao][campo="valor_unitario"]`).keyup(function(){
            valor = $(this).val();
            cod = $(this).attr("movimentacao");
            quantidade = $(`input[movimentacao="${cod}"][campo="quantidade"]`).val();
            total = (quantidade * valor);
            $(`input[movimentacao="${cod}"][campo="valor_total"]`).val(total.toFixed(2));

            tot = 0;
            $(`input[movimentacao][campo="valor_total"]`).each(function(){
                tot = (tot*1 + ($(this).val())*1);
            })
            $(`input[lancamento="<?=$_SESSION['cod_lancamento']?>"][campo="valor"]`).val(tot.toFixed(2));

        })

    })
</script>