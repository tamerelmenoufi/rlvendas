<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['cod'];
    }

    if($_POST['acao'] == 'adicionar'){
        $query = "update lancamentos set fornecedor = '{$_POST['codigo']}' where codigo = '{$_SESSION['cod_lancamento']}'";
        $result = sisLog($query);
    }

    $query = "select * from lancamentos where codigo = '{$_SESSION['cod_lancamento']}'";
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
                                <input type="text" id="fornecedor" class="form-control" placeholder="Nome completo do Fornecedor">
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
                                <input type="text" id="data" class="form-control" placeholder="00/00/0000">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group mb-3">
                                <input type="text" id="valor" class="form-control" placeholder="000.00">
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
                    <button class="btn btn-outline-secondary" type="button" id="busca-produtos"><i class="fa-solid fa-plus"></i></button>
                </div>

                <?php
                for($i=0;$i<10; $i++){
                ?>
                
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="nome" class="form-label d-none d-md-block">Nome</label>':false)?>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-danger" style="cursor:pointer"><i class="fa-solid fa-trash-can"></i></span>
                                <input type="text" id="nome" class="form-control" placeholder="Nome do Produto ou Serviço">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="unidade" class="form-label d-none d-md-block">Uni.</label>':false)?>
                            <div class="input-group mb-3">
                                <select class="form-select" id="unidade">
                                    <option selected>Choose...</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="valor_unitario" class="form-label d-none d-md-block">Valor</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" id="valor_unitario" class="form-control" placeholder="000.00">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="quantidade" class="form-label d-none d-md-block">Quant.</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" id="quantidade" class="form-control" placeholder="000">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <?=(($i==0)?'<label for="valor_total" class="form-label d-none d-md-block">Total</label>':false)?>
                            <div class="input-group mb-3">
                                <input type="text" id="valor_total" class="form-control" placeholder="000">
                            </div>
                        </div>
                    </div>

                </div>

                <?php
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
            $.ajax({
                url:"src/estoque/produtos_servicos.php",
                type:"POST",
                data:{
                    lancamento,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        

    })
</script>