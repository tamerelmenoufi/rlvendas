<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['cod'];
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
                                <button class="btn btn-outline-secondary" type="button" id="busca-fornecedor"><i class="fa-solid fa-plus"></i></button>
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
                                <button class="btn btn-outline-secondary w-100" type="button" id="busca-fornecedor">
                                    <input type="file" style="position:absolute; left:0; right:0; bottom:0; top:0; cursor:pointer; z-index:1; opacity:0" />
                                    <i class="fa-solid fa-paperclip"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 d-flex justify-content-between">
                    <h5>Produtos/Serviços</h5>
                    <button class="btn btn-outline-secondary" type="button" id="busca-fornecedor"><i class="fa-solid fa-plus"></i></button>
                </div>

                <div class="row">
                    
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <div class="input-group mb-3">
                                <input type="text" id="nome" class="form-control" placeholder="Nome do Produto">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="unidade" class="form-label">Uni.</label>
                            <div class="input-group mb-3">
                                <input type="text" id="unidade" class="form-control" placeholder="Unidade">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group mb-3">
                                <input type="text" id="valor" class="form-control" placeholder="000.00">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quant.</label>
                            <div class="input-group mb-3">
                                <input type="text" id="quantidade" class="form-control" placeholder="000">
                            </div>
                        </div>
                    </div>

                </div>


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



        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/estoque/lancamentos_form.php",
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })

        

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[campoBusca]").val();
          $.ajax({
              url:"src/estoque/lancamentos.php",
              type:"POST",
              data:{
                  filtro,
                  campo
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })


        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            $.ajax({
                url:"src/estoque/lancamentos_form.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })

        

        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            $.confirm({
                content:"Deseja realmente excluir o lançamento ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"src/estoque/lancamentos.php",
                            type:"POST",
                            data:{
                                delete:deletar
                            },
                            success:function(dados){
                                $("#paginaHome").html(dados);
                            }
                        })
                    },
                    'NÃO':function(){

                    }
                }
            });

        })


        $(".situacao").change(function(){

            situacao = $(this).attr("situacao");
            opc = false;

            if($(this).prop("checked") == true){
              opc = 'f';
            }else{
              opc = 'a';
            }


            $.ajax({
                url:"src/estoque/lancamentos.php",
                type:"POST",
                data:{
                    situacao,
                    opc
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                }
            })

        });

    })
</script>