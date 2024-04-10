<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['categoria']) $_SESSION['categoria'] = $_POST['categoria'];

    if($_POST['delete']){
      // $query = "delete from produtos where codigo = '{$_POST['delete']}'";
      $query = "update produtos set deletado = '1' where codigo = '{$_POST['delete']}'";
      sisLog($query);
    }

    if($_POST['situacao']){
      $query = "update produtos set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
      sisLog($query);
      exit();
    }

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
          <h5 class="card-header">Lista Produtos</h5>
          <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
              <button class="btn btn-warning" categoria="">
                Produtos
              </button>
              <button
                  novoCadastro
                  class="btn btn-success btn-sm"
                  data-bs-toggle="offcanvas"
                  href="#offcanvasDireita"
                  role="button"
                  aria-controls="offcanvasDireita"
                  style="margin-left:20px;"
              >Novo</button>

            </div>

            

            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "select * from produtos where categoria = '{$_SESSION['categoria']}' order by produto asc";
                    $result = sisLog($query);
                    
                    while($d = mysqli_fetch_object($result)){
                  ?>
                  <tr>
                    <td class="w-100"><?=$d->produto?></td>
                    <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                      </div>                      
                    </td>
                    <td>
                      <button
                        class="btn btn-primary"
                        edit="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                      >
                        Editar
                      </button>
                      <button <?=(($d->qt)?'disabled':false)?> class="btn btn-danger" delete="<?=$d->codigo?>">
                        Excluir
                      </button>
                    </td>
                  </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
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

        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/produtos/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            $.ajax({
                url:"src/produtos/form.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            $.confirm({
                content:"Deseja realmente excluir o cadastro ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"src/produtos/index.php",
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
            opc = '1';
          }else{
            opc = '0';
          }


          $.ajax({
              url:"src/produtos/index.php",
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

        $("button[categoria]").click(function(){
          Carregando();
            $.ajax({
                url:"src/categorias/index.php",
                type:"POST",
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })


    })
</script>