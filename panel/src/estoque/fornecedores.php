<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['lancamento'];
    }

    if($_POST['acao'] == 'excluir'){
        $query = "delete from fornecedores where codigo = '{$_POST['codigo']}'";
        $result = sisLog($query);
    }

    if($_POST['filtro'] == 'filtrar'){
        $_SESSION['textoBusca'] = $_POST['campo'];
      }elseif($_POST['filtro']){
        $_SESSION['textoBusca'] = false;
      }
  
      if($_SESSION['textoBusca']){
        $cpf = str_replace( ['.','-','/'], false, $_SESSION['textoBusca']);
        $where = " and a.nome_razao_social like '%{$_SESSION['textoBusca']}%' or REPLACE( REPLACE( REPLACE( a.cpf_cnpj, '/', '' ), '.', '' ), '-', '' ) = '{$cpf}' ";
      }

    $query = "select * from lancamentos where codigo = '{$_SESSION['cod_lancamento']}'";
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
<h4 class="Titulo<?=$md5?>">Lançamento <?=$d->numero?></h4>
<h6>Selecione um Fornecedor</h6>
<div class="input-group">
<label class="input-group-text" for="inputGroupFile01">Buscar por </label>
    <input textoBusca type="text" class="form-control" value="<?=$_SESSION['textoBusca']?>" aria-label="Digite a informação para a busca">
    <button filtro="filtrar" class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
    <button filtro="limpar" class="btn btn-outline-danger" type="button"><i class="fa-solid fa-eraser"></i></button>
    <button novo class="btn btn-outline-primary" type="button"><i class="fa-solid fa-file-circle-plus"></i></button>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome/Razão Social</th>
            <th>CPF</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
<?php
    $query = "select a.*, (select count(*) from lancamentos where fornecedor = a.codigo) as qt from fornecedores a where 1 {$where} order by a.nome_razao_social limit 100";
    $result = sisLog($query);
    while($d = mysqli_fetch_object($result)){
?> 
        <tr>
            <td><?=$d->nome_razao_social?></td>
            <td><?=$d->cpf_cnpj?></td>
            <td>
                <i class="fa-regular fa-square-plus text-success me-3" acao="adicionar" codigo="<?=$d->codigo?>" style="cursor:pointer"></i>
                <i class="fa-solid fa-pen-to-square me-3 text-primary" acao="editar" codigo="<?=$d->codigo?>" style="cursor:pointer"></i>
                <?php
                if(!$d->qt){
                ?>
                <i class="fa-solid fa-trash-can text-danger" acao="excluir" codigo="<?=$d->codigo?>" style="cursor:pointer"></i>
                <?php
                }
                ?>
            </td>
        </tr>
<?php
    }
?>
    </tbody>
</table>


<script>
    $(function(){

        Carregando('none');

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[textoBusca]").val();
          $.ajax({
              url:"src/estoque/fornecedores.php",
              type:"POST",
              data:{
                  filtro,
                  campo
              },
              success:function(dados){
                $(".LateralDireita").html(dados);
              }
          })
        })

        $("button[novo]").click(function(){
          $.ajax({
              url:"src/estoque/fornecedores_form.php",
              type:"POST",
              data:{

              },
              success:function(dados){
                $(".LateralDireita").html(dados);
              }
          })
        })

        $("i[acao]").click(function(){
            acao = $(this).attr("acao");
            codigo = $(this).attr("codigo");
            if(acao == 'editar'){

                $.ajax({
                    url:"src/estoque/fornecedores_form.php",
                    type:"POST",
                    data:{
                        codigo
                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })

            }else if(acao == 'excluir'){

                $.confirm({
                    content:"Deseja realmente excluir o registro do fornecedor?",
                    title:"Excluir Registro",
                    type:'red',
                    buttons:{
                        'sim':{
                            text:'Sim',
                            btnClass:'btn btn-danger',
                            action:function(){

                                $.ajax({
                                    url:"src/estoque/fornecedores.php",
                                    type:"POST",
                                    data:{
                                        acao,
                                        codigo
                                    },
                                    success:function(dados){
                                        $(".LateralDireita").html(dados);
                                    }
                                })

                            }
                        },
                        'nao':{
                            text:'Não',
                            btnClass:'btn btn-success',
                            action:function(){

                            }
                        }
                    }
                })

            }else if(acao == 'adicionar'){
                $.ajax({
                    url:"src/estoque/lancamentos_form.php",
                    type:"POST",
                    data:{
                        acao:'adicionar_fornecedor',
                        codigo,
                        cod:'<?=$_SESSION['cod_lancamento']?>'
                    },
                    success:function(dados){
                        $(".LateralDireita").html('');
                        $("#paginaHome").html(dados);
                        let myOffCanvas = document.getElementById('offcanvasDireita');
                        let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                        openedCanvas.hide();
                    }
                })
            }

        })

    })
</script>