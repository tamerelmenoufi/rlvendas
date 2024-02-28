<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['acao'] == 'novo'){

      $numero = uniqid();

      $query = "INSERT INTO lancamentos set numero = '{$numero}', tipo = 's', usuario = '{$_SESSION['appLogin']->codigo}', data_atualizacao = NOW()";
      $result = sisLog($query);
      if(!$result){
        $erro = 'Cadastro não registrado, existe um lançamento com o mesmo número!';
      }
    }

    if($_POST['delete']){

      $query = "DELETE FROM lancamentos Where codigo = '{$_POST['delete']}'";
      $result = sisLog($query);
      
      $query = "DELETE FROM movimentacao Where lancamento = '{$_POST['delete']}'";
      $result = sisLog($query);
      

    }


    if($_POST['filtro'] == 'filtrar'){
      $_SESSION['usuarioBusca'] = $_POST['campo'];
    }elseif($_POST['filtro']){
      $_SESSION['usuarioBusca'] = false;
    }

    if($_SESSION['usuarioBusca']){
      $data = dataMysql($_SESSION['usuarioBusca']);
      $where = " and a.numero = '{$_SESSION['usuarioBusca']}' or a.data = '{$data}' or b.nome_razao_social like '%{$_SESSION['usuarioBusca']}%' ";
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
          <h5 class="card-header">Saídas</h5>
          <div class="card-body">
            <div class="d-none d-md-block">
              <div class="d-flex justify-content-between mb-3">
                  <div class="input-group me-2">
                    <label class="input-group-text" for="inputGroupFile01">Buscar por </label>
                    <input campoBusca type="text" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                    <button filtro="filtrar" class="btn btn-outline-secondary" type="button">Buscar</button>
                    <button filtro="limpar" class="btn btn-outline-danger" type="button">limpar</button>
                    <button
                      novoCadastro
                      class="btn btn-success btn-sm"
                      Xdata-bs-toggle="offcanvas"
                      Xhref="#offcanvasDireita"
                      Xrole="button"
                      Xaria-controls="offcanvasDireita"
                    >Saída</button>                     
                  </div>
              </div>
            </div>

            <div class="d-block d-md-none d-lg-none d-xl-none d-xxl-none">
              <div class="d-flex justify-content-between mb-3">

                  <div class="row">
                    <div class="col-12 mb-2">
                      <div class="input-group">
                        <label class="input-group-text" for="inputGroupFile01">Buscar por </label>
                        <input campoBusca1 type="text" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                      </div>
                    </div>
                    <div class="col-12 mb-2">
                      <button filtro1="filtrar" class="btn btn-outline-secondary w-100" type="button">Buscar</button>
                    </div>
                    <div class="col-12 mb-2">
                      <button filtro1="limpar" class="btn btn-outline-danger w-100" type="button">limpar</button>
                    </div>

                    <div class="col-12 mb-2">
                      <button
                        novoCadastro1
                        class="btn btn-success w-100"
                        Xdata-bs-toggle="offcanvas"
                        Xhref="#offcanvasDireita"
                        Xrole="button"
                        Xaria-controls="offcanvasDireita"
                      >Novo</button> 
                    </div>
                    
                  </div>
              </div>
            </div>

            <div class="table-responsive d-none d-md-block">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th scope="col">Número</th>
                    <!--<th scope="col">Fornecedor</th>-->
                    <th scope="col">Data</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "select a.*, b.nome_razao_social from lancamentos a left join fornecedores b on a.fornecedor = b.codigo where a.deletado != '1' and a.tipo = 's' {$where} order by a.data desc";
                    $result = sisLog($query);
                    
                    while($d = mysqli_fetch_object($result)){
                  ?>
                  <tr>
                    <td class="w-100"><?=$d->numero?></td>
                    <!--<td><?=$d->nome_razao_social?></td>-->
                    <td><?=dataBr($d->data)?></td>
                    <td><?=$d->valor?></td>
                    <td>

                    <div class="form-check form-switch">
                      <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                    </div>

                    </td>
                    <td>
                      <button
                        class="btn btn-primary"
                        edit="<?=$d->codigo?>"
                        Xdata-bs-toggle="offcanvas"
                        Xhref="#offcanvasDireita"
                        Xrole="button"
                        Xaria-controls="offcanvasDireita"
                      >
                        Editar
                      </button>
                      <button class="btn btn-danger" delete="<?=$d->codigo?>">
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


            <div class="d-block d-md-none d-lg-none d-xl-none d-xxl-none">
            <?php
                  $query = "select a.*, b.nome_razao_social from lancamentos a left join fornecedores b on a.fornecedor = b.codigo where a.deletado != '1' and a.tipo = 's' {$where} order by a.data desc";
                  $result = sisLog($query);
                  
                  while($d = mysqli_fetch_object($result)){
                ?>
                <div class="card mb-3 p-3">
                    <div class="row">
                      <div class="col-12 d-flex justify-content-end">
                        <div class="form-check form-switch">
                          <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                          Situação
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <label class="label">Número</label>
                        <div><?=$d->numero?></div>
                      </div>
                    </div>

                    <!--<div class="row">
                      <div class="col-12">
                      <label class="label">Fornecedor</label>
                       <div><?=$d->nome_razao_social?></div>
                      </div>
                    </div>-->
                    
                    <div class="row">
                      <div class="col-12">
                      <label class="label">Data</label>
                       <div><?=$d->data?></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                      <label class="label">Valor</label>
                       <div><?=$d->valor?></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6 p-2">
                        <button
                          class="btn btn-primary w-100"
                          edit="<?=$d->codigo?>"
                          Xdata-bs-toggle="offcanvas"
                          Xhref="#offcanvasDireita"
                          Xrole="button"
                          Xaria-controls="offcanvasDireita"
                        >
                          Editar
                        </button>
                      </div>
                      <div class="col-6 p-2">
                        <button class="btn btn-danger w-100" delete="<?=$d->codigo?>">
                          Excluir
                        </button>
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
</div>


<script>
    $(function(){
        Carregando('none');

        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/estoque/saidas.php",
                type:"POST",
                data:{
                  acao:'novo'
                },
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })

        $("button[novoCadastro1]").click(function(){
            $.ajax({
                url:"src/estoque/saidas.php",
                type:"POST",
                data:{
                  acao:'novo'
                },
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })

        

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[campoBusca]").val();
          $.ajax({
              url:"src/estoque/saidas.php",
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

        $("button[filtro1]").click(function(){
          filtro = $(this).attr("filtro1");
          campo = $("input[campoBusca1]").val();
          $.ajax({
              url:"src/estoque/saidas.php",
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
                url:"src/estoque/saidas_form.php",
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
                            url:"src/estoque/saidas.php",
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
                url:"src/estoque/saidas.php",
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