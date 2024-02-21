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
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fornecedor" class="form-label">Fornecedor</label>
                            <div class="input-group mb-3">
                                <input type="text" id="fornecedor" class="form-control" placeholder="Nome completo do Fornecedor">
                                <button class="btn btn-outline-secondary" type="button" id="busca-fornecedor"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data" class="form-label">Data</label>
                            <div class="input-group mb-3">
                                <input type="text" id="data" class="form-control" placeholder="00/00/0000">
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